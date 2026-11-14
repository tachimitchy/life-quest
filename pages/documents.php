<?php
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/lq.php';

$user = require_login(['student']);

// Looks up the stored file path of one of the student's own uploads, or of a teacher file from a joined class.
function find_path(array $user, string $table, string $id): ?string {
  if (!lq_is_uuid($id)) return null;
  $rows = lq_rows(lq_request('GET', '/rest/v1/' . $table . '?select=file_path&id=eq.' . rawurlencode($id) . '&limit=1', null, $user['jwt']));
  return $rows[0]['file_path'] ?? null;
}

// ---- Open a file: make a short-lived private link and go there ----
if (isset($_GET['open']) || isset($_GET['copen'])) {
  $table = isset($_GET['copen']) ? 'class_documents' : 'user_documents';
  $path  = find_path($user, $table, (string)($_GET['copen'] ?? $_GET['open']));
  $url   = $path ? lq_signed_url($user['jwt'], $path) : null;
  if ($url) lq_go($url);
  lq_flash('error', 'Could not open that file.');
  lq_go('/documents');
}

// ---- Upload, delete, or join a class with a teacher's code ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!lq_csrf_ok()) {
    lq_flash('error', 'Invalid form submission. Please try again.');
    lq_go('/documents');
  }
  $action = $_POST['action'] ?? '';

  if ($action === 'upload') {
    [$ok, $msg] = lq_handle_upload($user);
    lq_flash($ok ? 'success' : 'error', $msg);

  } elseif ($action === 'delete') {
    $id   = (string)($_POST['id'] ?? '');
    $path = find_path($user, 'user_documents', $id);
    if ($path) {
      lq_delete_object($user['jwt'], $path);
      lq_request('DELETE', '/rest/v1/user_documents?id=eq.' . rawurlencode($id), null, $user['jwt'], ['Prefer: return=minimal']);
      lq_flash('success', 'Reviewer deleted.');
    } else {
      lq_flash('error', 'That file was not found.');
    }

  } elseif ($action === 'join') {
    $code = strtoupper(trim((string)($_POST['code'] ?? '')));
    if (!preg_match('/^[A-Z0-9]{4,12}$/', $code)) {
      lq_flash('error', 'Please type a valid code from your teacher.');
    } else {
      $r = lq_rpc('join_class', ['p_code' => $code], $user['jwt']);
      if (lq_ok($r)) {
        lq_award($user['jwt'], 'joined_class');
        lq_flash('success', 'You joined ' . (is_string($r['data']) ? $r['data'] : 'the class') . '! Your teacher\'s reviewers are now in your documents.');
      } else {
        $m = lq_err($r, '');
        lq_flash('error', stripos($m, 'invalid code') !== false ? 'That code was not found. Please check it with your teacher.' : 'Could not use that code. Did you run supabase-setup-2.sql?');
      }
    }
  }
  lq_go('/documents');
}

$res     = lq_request('GET', '/rest/v1/user_documents?select=id,title,file_name,file_size,mime_type,created_at&order=created_at.desc', null, $user['jwt']);
$docs    = lq_rows($res);
$dbReady = lq_ok($res);
$classDocs = lq_rows(lq_request('GET', '/rest/v1/class_documents?select=id,title,file_size,mime_type,created_at,teacher_classes(name)&order=created_at.desc', null, $user['jwt']));

$pageTitle = 'My Documents';
$active    = 'documents';
require __DIR__ . '/../partials/app-open.php';
?>
      <div class="page-head">
        <h1 class="page-title">My Documents</h1>
        <form class="join-form" action="/documents" method="POST">
          <input type="hidden" name="csrf" value="<?= lq_e(lq_csrf()) ?>" />
          <input type="hidden" name="action" value="join" />
          <input type="text" name="code" maxlength="12" placeholder="CLASS CODE" aria-label="Class code from your teacher" autocomplete="off" required />
          <button type="submit" class="px px-gold">Join</button>
        </form>
      </div>
      <p class="lead">Every reviewer you upload is saved here. Have a code from your teacher? Type it in the box on the right.</p>

      <?php if (!$dbReady): ?>
        <div class="alert alert-error" role="alert">The database is not set up yet. Run <b>supabase-setup.sql</b> and <b>supabase-setup-2.sql</b> in the Supabase SQL Editor.</div>
      <?php endif; ?>

      <form class="panel" action="/documents" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf" value="<?= lq_e(lq_csrf()) ?>" />
        <input type="hidden" name="action" value="upload" />
        <div class="field">
          <label for="reviewer">Choose a reviewer from your device (PDF, or a scanned PNG/JPG, up to 4 MB)</label>
          <input type="file" id="reviewer" name="reviewer" accept=".pdf,.png,.jpg,.jpeg,application/pdf,image/png,image/jpeg" required />
        </div>
        <button type="submit" class="px px-green">Upload</button>
      </form>

      <?php if ($classDocs): ?>
        <h2 class="section-title">From My Teachers</h2>
        <?php foreach ($classDocs as $d): ?>
          <div class="doc" style="border-left-color: var(--gold);">
            <div>
              <h3><?= lq_e($d['title']) ?></h3>
              <small><?= lq_e($d['teacher_classes']['name'] ?? 'Class') ?> &middot; <?= lq_e(lq_when($d['created_at'])) ?> &middot; <?= lq_e(lq_size($d['file_size'] ?? 0)) ?></small>
            </div>
            <div class="btns">
              <a class="px px-green" href="/play?cdoc=<?= lq_e($d['id']) ?>">Play</a>
              <a class="px px-stone" href="/documents?copen=<?= lq_e($d['id']) ?>" target="_blank" rel="noopener">Open</a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>

      <h2 class="section-title">My Uploads</h2>
      <?php if (!$docs): ?>
        <div class="panel empty"><span class="big" aria-hidden="true">📄</span>Nothing here yet. Upload your first reviewer to begin!</div>
      <?php endif; ?>

      <?php foreach ($docs as $d): ?>
        <div class="doc">
          <div>
            <h3><?= lq_e($d['title']) ?></h3>
            <small><?= lq_e(lq_when($d['created_at'])) ?> &middot; <?= lq_e(lq_size($d['file_size'] ?? 0)) ?> &middot; <?= ($d['mime_type'] ?? '') === 'application/pdf' ? 'PDF' : 'Scanned image' ?></small>
          </div>
          <div class="btns">
            <a class="px px-green" href="/play?doc=<?= lq_e($d['id']) ?>">Play</a>
            <a class="px px-stone" href="/documents?open=<?= lq_e($d['id']) ?>" target="_blank" rel="noopener">Open</a>
            <form action="/documents" method="POST" onsubmit="return confirm('Delete this reviewer? This cannot be undone.');">
              <input type="hidden" name="csrf" value="<?= lq_e(lq_csrf()) ?>" />
              <input type="hidden" name="action" value="delete" />
              <input type="hidden" name="id" value="<?= lq_e($d['id']) ?>" />
              <button type="submit" class="px px-red">Delete</button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
<?php require __DIR__ . '/../partials/app-close.php'; ?>