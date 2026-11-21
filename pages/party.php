<?php
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/lq.php';

$user = require_login(['student', 'admin']);
$jwt  = $user['jwt'];

// ---------- JSON for the lobby (the page asks for this every 2 seconds) ----------
if (isset($_GET['state'])) {
  header('Content-Type: application/json');
  $id = (string)($_GET['id'] ?? '');
  if (!lq_is_uuid($id)) { http_response_code(400); echo '{"error":"bad id"}'; exit; }
  $r = lq_rpc('party_state', ['p_id' => $id], $jwt);
  if (!lq_ok($r)) { http_response_code(403); echo json_encode(['error' => lq_err($r, 'Could not load the party')]); exit; }
  echo json_encode($r['data']);
  exit;
}

// ---------- Live score from the game (JSON POST to /party?score=1) ----------
if (isset($_GET['score']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
  header('Content-Type: application/json');
  $b = json_decode((string)file_get_contents('php://input'), true);
  $b = is_array($b) ? $b : [];
  lq_session();
  if (!hash_equals((string)($_SESSION['lq_csrf'] ?? ''), (string)($b['csrf'] ?? '')) || !lq_is_uuid((string)($b['party_id'] ?? ''))) {
    http_response_code(400);
    echo '{"ok":false}';
    exit;
  }
  $r = lq_rpc('submit_party_score', ['p_id' => (string)$b['party_id'], 'p_score' => (int)($b['score'] ?? 0)], $jwt);
  echo json_encode(['ok' => lq_ok($r)]);
  exit;
}

$error = null;

// ---------- Button actions ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = (string)($_POST['action'] ?? '');
  $pid    = (string)($_POST['party_id'] ?? '');

  if (!lq_csrf_ok()) {
    $error = 'Invalid form submission. Please refresh and try again.';

  } elseif ($action === 'create') {
    $name = mb_substr(trim((string)($_POST['name'] ?? '')), 0, 40);
    $r = lq_rpc('create_party', ['p_name' => $name], $jwt);
    if (lq_ok($r)) lq_go('/party');
    $error = lq_err($r, 'Could not create the party.');

  } elseif ($action === 'join') {
    $code = strtoupper(trim((string)($_POST['code'] ?? '')));
    if ($code === '') {
      $error = 'Please type a party code.';
    } else {
      $r = lq_rpc('join_party', ['p_code' => $code], $jwt);
      if (lq_ok($r)) lq_go('/party');
      $error = lq_err($r, 'Could not join that party.');
    }

  } elseif (in_array($action, ['leave', 'start'], true) && lq_is_uuid($pid)) {
    $r = lq_rpc($action === 'leave' ? 'leave_party' : 'start_party', ['p_id' => $pid], $jwt);
    if (lq_ok($r)) lq_go('/party');
    $error = lq_err($r, 'That did not work. Please try again.');

  } elseif ($action === 'upload' && lq_is_uuid($pid)) {
    $u = lq_store_upload($user);
    if (!$u['ok']) {
      $error = $u['msg'];
    } else {
      $r = lq_rpc('set_party_reviewer', [
        'p_id' => $pid, 'p_path' => $u['path'], 'p_title' => $u['title'], 'p_mime' => $u['mime'],
      ], $jwt);
      if (lq_ok($r)) lq_go('/party');
      lq_delete_object($jwt, $u['path']);   // undo the upload
      $error = lq_err($r, 'Could not share the reviewer.');
    }
  }
}

// ---------- Which party am I in? ----------
$mine = lq_rpc('my_party', [], $jwt);
if (!lq_ok($mine)) {
  $error = $error ?: 'Party mode is not set up yet. Run supabase-party.sql in the Supabase SQL Editor.';
}
$partyId = (is_string($mine['data'] ?? null) && lq_is_uuid($mine['data'])) ? $mine['data'] : '';

$state = null;
if ($partyId) {
  $s     = lq_rpc('party_state', ['p_id' => $partyId], $jwt);
  $state = lq_ok($s) ? $s['data'] : null;
}
$isHost = $state && (($state['host_id'] ?? '') === $user['id']);

$pageTitle = 'Party Mode';
$active    = 'party';
require __DIR__ . '/../partials/app-open.php';
?>
      <style>
        .pty-code { font-size: 2.6rem; letter-spacing: .35rem; text-align: center; margin: .5rem 0 1rem; }
        .pty-row  { display: flex; gap: .6rem; flex-wrap: wrap; align-items: center; margin-top: .75rem; }
        .pty-row input[type=text], .pty-row input[type=file] { flex: 1; min-width: 180px; padding: .6rem; }
        .pty-members { list-style: none; padding: 0; margin: .75rem 0 0; }
        .pty-members li { display: flex; align-items: center; gap: .75rem; padding: .5rem 0; border-bottom: 1px solid rgba(128,128,128,.25); }
        .pty-members .score { margin-left: auto; font-weight: 700; }
        .pty-av { flex: none; width: 30px; height: 40px; overflow: hidden; position: relative; }
        .pty-av .spr { transform: scale(.45); transform-origin: top left; position: absolute; top: 0; left: 0; }
        .pty-count { font-size: 2rem; text-align: center; font-weight: 700; margin: .75rem 0; }
      </style>

      <h1 class="section-title">Party Mode</h1>

      <?php if ($error): ?>
        <div class="alert alert-error" role="alert"><?= lq_e($error) ?></div>
      <?php endif; ?>

<?php if (!$state): ?>
      <div class="panel">
        <h2 class="section-title">Host a party</h2>
        <p>Make a code and share it with your classmates.</p>
        <form method="POST" action="/party" class="pty-row">
          <input type="hidden" name="csrf" value="<?= lq_e(lq_csrf()) ?>">
          <input type="hidden" name="action" value="create">
          <input type="text" name="name" maxlength="40" placeholder="Party name (optional)">
          <button type="submit" class="px px-green">Create Party</button>
        </form>
      </div>

      <div class="panel">
        <h2 class="section-title">Join a party</h2>
        <form method="POST" action="/party" class="pty-row">
          <input type="hidden" name="csrf" value="<?= lq_e(lq_csrf()) ?>">
          <input type="hidden" name="action" value="join">
          <input type="text" name="code" maxlength="6" placeholder="Enter 6-letter code" autocomplete="off" style="text-transform:uppercase">
          <button type="submit" class="px px-gold">Join</button>
        </form>
      </div>

<?php else: ?>
      <div class="panel">
        <h2 class="section-title" id="pty-name"><?= lq_e($state['name']) ?></h2>
        <p>Share this code with your friends:</p>
        <div class="pty-code" id="pty-code"><?= lq_e($state['code']) ?></div>
        <p id="pty-status">Waiting for the host to start…</p>
        <div class="pty-count" id="pty-count" hidden></div>
        <p id="pty-reviewer"></p>
      </div>

      <div class="panel">
        <h2 class="section-title">Players (<span id="pty-n">0</span>/10)</h2>
        <ul class="pty-members" id="pty-members"></ul>
      </div>

<?php if ($isHost): ?>
      <div class="panel">
        <h2 class="section-title">Host controls</h2>
        <p>Upload a reviewer (PDF, PNG or JPG, up to 4 MB). Everyone in the party can use it.</p>
        <form method="POST" action="/party" enctype="multipart/form-data" class="pty-row">
          <input type="hidden" name="csrf" value="<?= lq_e(lq_csrf()) ?>">
          <input type="hidden" name="action" value="upload">
          <input type="hidden" name="party_id" value="<?= lq_e($state['id']) ?>">
          <input type="file" name="reviewer" accept=".pdf,.png,.jpg,.jpeg,application/pdf,image/png,image/jpeg">
          <button type="submit" class="px px-gold">Share Reviewer</button>
        </form>
        <form method="POST" action="/party" class="pty-row" id="pty-start-form">
          <input type="hidden" name="csrf" value="<?= lq_e(lq_csrf()) ?>">
          <input type="hidden" name="action" value="start">
          <input type="hidden" name="party_id" value="<?= lq_e($state['id']) ?>">
          <button type="submit" class="px px-green">▶ Start Game for Everyone</button>
        </form>
      </div>
<?php endif; ?>

      <form method="POST" action="/party" class="pty-row">
        <input type="hidden" name="csrf" value="<?= lq_e(lq_csrf()) ?>">
        <input type="hidden" name="action" value="leave">
        <input type="hidden" name="party_id" value="<?= lq_e($state['id']) ?>">
        <button type="submit" class="px px-stone"><?= $isHost ? 'Close Party' : 'Leave Party' ?></button>
      </form>

      <script>
      (function () {
        var P  = <?= json_encode($state, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
        var ME = <?= json_encode($user['id']) ?>;
        var offset = 0;       // server clock minus this device's clock
        var going  = false;

        function render(s) {
          P = s;
          offset = s.now_ms - Date.now();

          document.getElementById('pty-n').textContent = s.members.length;
          var ul = document.getElementById('pty-members');
          ul.textContent = '';
          s.members.forEach(function (m) {
            var li = document.createElement('li');
            var av = document.createElement('span');
            av.className = 'pty-av';
            var i = document.createElement('i');
            i.className = 'spr spr-' + String(m.character).replace(/[^a-z]/g, '');
            av.appendChild(i);
            var nm = document.createElement('span');
            nm.textContent = m.name + (m.id === s.host_id ? ' 👑' : '') + (m.id === ME ? ' (you)' : '');
            var sc = document.createElement('span');
            sc.className = 'score';
            sc.textContent = s.status === 'lobby' ? '' : m.score + ' pts';
            li.appendChild(av); li.appendChild(nm); li.appendChild(sc);
            ul.appendChild(li);
          });

          document.getElementById('pty-reviewer').textContent =
            s.has_reviewer ? '📄 Shared reviewer: ' + (s.reviewer_title || 'Reviewer')
                           : 'No reviewer shared yet. The host can upload one.';
          tick();
        }

        function tick() {
          var st = document.getElementById('pty-status');
          var ct = document.getElementById('pty-count');
          if (P.status === 'playing' && P.start_ms) {
            var left = P.start_ms - (Date.now() + offset);
            if (left > 0) {
              st.textContent = 'Get ready!';
              ct.hidden = false;
              ct.textContent = 'Starting in ' + Math.ceil(left / 1000);
            } else if (!going && -left < 20000) {
              going = true;
              ct.hidden = false;
              ct.textContent = 'GO!';
              location.href = '/play?party=' + encodeURIComponent(P.id);
            } else if (!going) {
              // The game started a while ago (for example, you came back from the game screen)
              st.textContent = 'The game is in progress.';
              ct.hidden = false;
              if (!ct.querySelector('a')) {
                ct.textContent = '';
                var a = document.createElement('a');
                a.href = '/play?party=' + encodeURIComponent(P.id);
                a.className = 'px px-green';
                a.textContent = 'Back to the game';
                ct.appendChild(a);
              }
            }
          } else {
            st.textContent = 'Waiting for the host to start…';
            ct.hidden = true;
          }
        }

        function poll() {
          if (going) return;
          fetch('/party?state=1&id=' + encodeURIComponent(P.id), { credentials: 'same-origin' })
            .then(function (r) {
              if (r.status === 403) { location.href = '/party'; throw 0; }   // party closed
              return r.json();
            })
            .then(render)
            .catch(function () {})
            .then(function () { setTimeout(poll, 2000); });
        }

        render(P);
        setInterval(tick, 200);
        setTimeout(poll, 2000);
      })();
      </script>
<?php endif; ?>
<?php require __DIR__ . '/../partials/app-close.php'; ?>