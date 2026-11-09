<?php
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/lq.php';
require_once __DIR__ . '/../lib/characters.php';

$user  = require_login(['student']);
$first = explode(' ', trim($user['full_name']))[0] ?: 'Adventurer';

// Character system
$chars = lq_characters();
$level = lq_player_level($user);

// Player picked a character
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'character') {
  if (!lq_csrf_ok()) {
    lq_flash('error', 'Invalid form submission. Please try again.');
  } else {
    [$ok, $msg] = lq_char_choose($user, (string)($_POST['character'] ?? ''), $level);
    lq_flash($ok ? 'success' : 'error', $msg);
  }
  lq_go(strtok($_SERVER['REQUEST_URI'], '?'));
}

$charId = lq_char_current($user);
$charInfo = $chars[$charId];

// Next character to unlock (for the hint in the picker)
$next = null;
foreach ($chars as $c) {
  if ($c['level'] > $level && ($next === null || $c['level'] < $next['level'])) $next = $c;
}

// Has the student uploaded a reviewer yet?
$docsRes = lq_request('GET', '/rest/v1/user_documents?select=id&limit=1', null, $user['jwt']);
$dbReady = lq_ok($docsRes);
$hasDoc  = count(lq_rows($docsRes)) > 0;

// Latest game update posted by an Admin
$annRows = lq_rows(lq_request('GET',
  '/rest/v1/announcements?select=title,body,author_name,created_at&category=eq.game_update&order=created_at.desc&limit=1',
  null, $user['jwt']));
$latest = $annRows[0] ?? null;

$pageTitle = 'Dashboard';
$active    = 'dashboard';
require __DIR__ . '/../partials/app-open.php';
?>
      <style>
        /* Clickable profile character */
        .hero-box { position: relative; }
        .hero-pick {
          display: flex; align-items: center; justify-content: center;
          width: 100%; height: 100%; margin: 0; padding: 0;
          border: 0; background: transparent; color: inherit; font: inherit; cursor: pointer;
        }
        .hero-pick .lq-sprite { display: block; width: 72px; height: auto; image-rendering: pixelated; transition: transform 0.08s steps(2); }
        .hero-pick:hover .lq-sprite, .hero-pick:focus-visible .lq-sprite { transform: translateY(-3px); }
        .hero-pick:focus-visible { outline: 3px dashed #ffd54a; outline-offset: 4px; }
        .hero-edit {
          position: absolute; right: -8px; bottom: -10px; padding: 4px 6px;
          font-family: "Press Start 2P", "Courier New", monospace; font-size: 0.6rem;
          color: #fff; background: #4caf3d; border: 3px solid #000;
          box-shadow: inset -2px -2px 0 #2d7a24, inset 2px 2px 0 #8be07a;
          pointer-events: none;
        }
        .lv-chip { margin-top: 0.35rem; font-size: 1.1rem; opacity: 0.85; }

        /* Character picker */
        .cp { width: calc(100% - 2rem); max-width: 580px; max-height: 90vh; overflow: auto; }
        .cp h2 { margin-bottom: 0.25rem; }
        .cp-sub { margin-bottom: 0.25rem; }
        .cp-next { margin-bottom: 0.75rem; font-size: 1.1rem; opacity: 0.85; }
        .cp-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(128px, 1fr)); gap: 0.75rem; margin: 1rem 0; }
        .cp-card {
          position: relative; display: flex; flex-direction: column; align-items: center; gap: 0.35rem;
          width: 100%; padding: 0.85rem 0.5rem 0.7rem;
          font: inherit; text-align: center; color: #fff; background: #12183a;
          border: 4px solid #000;
          box-shadow: inset -4px -4px 0 #0b1026, inset 4px 4px 0 #2a3270;
          cursor: pointer; transition: transform 0.08s steps(2);
        }
        .cp-card:hover, .cp-card:focus-visible { transform: translateY(-3px); }
        .cp-card:focus-visible { outline: 3px dashed #ffd54a; outline-offset: 3px; }
        .cp-card .lq-sprite { width: 64px; height: auto; image-rendering: pixelated; }
        .cp-name { font-size: 1.25rem; line-height: 1.1; }
        .cp-tag { font-size: 1rem; color: #8e9ad0; }
        .cp-card.is-selected { border-color: #ffd54a; box-shadow: inset -4px -4px 0 #7a5a00, inset 4px 4px 0 #3b3a10; }
        .cp-badge {
          font-family: "Press Start 2P", "Courier New", monospace; font-size: 0.55rem;
          padding: 3px 5px; color: #fff; background: #4caf3d; border: 2px solid #000;
        }
        .cp-card.is-locked { cursor: not-allowed; background: #0b1026; }
        .cp-card.is-locked .lq-sprite { filter: grayscale(1) brightness(0.35); }
        .cp-card.is-locked .cp-name { color: #8b93a7; }
        .cp-lock {
          font-family: "Press Start 2P", "Courier New", monospace; font-size: 0.55rem; line-height: 1.5;
          padding: 3px 5px; color: #ffd54a; background: #3a1220; border: 2px solid #e5484d;
        }
        @keyframes cp-shake { 0%,100% { transform: translateX(0); } 25% { transform: translateX(-4px); } 75% { transform: translateX(4px); } }
        .cp-card.shake { animation: cp-shake 0.25s steps(4); }
        @media (prefers-reduced-motion: reduce) { .cp-card.shake { animation: none; } .cp-card, .hero-pick .lq-sprite { transition: none; } }
      </style>

      <?php if (!$dbReady): ?>
        <div class="alert alert-error" role="alert">The database is not set up yet. Run <b>supabase-setup.sql</b> in the Supabase SQL Editor.</div>
      <?php endif; ?>

      <section class="welcome">
        <div class="hero-box">
          <button type="button" class="hero-pick" id="open-picker" aria-label="Change your character (currently <?= lq_e($charInfo['name']) ?>)">
            <?= lq_char_svg($charId) ?>
          </button>
          <span class="hero-edit" aria-hidden="true">Change</span>
        </div>
        <div>
          <h1>Welcome back, <?= lq_e($first) ?>!</h1>
          <p>Keep studying and reach new heights!</p>
          <p class="lv-chip">Level <?= (int)$level ?> &middot; <?= lq_e($charInfo['name']) ?></p>
        </div>
      </section>

      <dialog id="char-picker" class="pxdialog cp" aria-labelledby="cp-title">
        <form method="POST">
          <input type="hidden" name="csrf" value="<?= lq_e(lq_csrf()) ?>" />
          <input type="hidden" name="action" value="character" />
          <h2 id="cp-title">Choose Your Hero</h2>
          <p class="cp-sub">Your level: <b><?= (int)$level ?></b></p>
          <?php if ($next): ?>
            <p class="cp-next">Next unlock: <b><?= lq_e($next['name']) ?></b> at Level <?= (int)$next['level'] ?></p>
          <?php else: ?>
            <p class="cp-next">You unlocked every character. Legendary!</p>
          <?php endif; ?>

          <div class="cp-grid">
            <?php foreach ($chars as $id => $c): ?>
              <?php $unlocked = $level >= $c['level']; $selected = $id === $charId; ?>
              <?php if ($unlocked): ?>
                <button type="submit" name="character" value="<?= lq_e($id) ?>" class="cp-card<?= $selected ? ' is-selected' : '' ?>" <?= $selected ? 'aria-pressed="true"' : '' ?>>
                  <?= lq_char_svg($id) ?>
                  <span class="cp-name"><?= lq_e($c['name']) ?></span>
                  <span class="cp-tag"><?= lq_e($c['gender']) ?></span>
                  <?php if ($selected): ?><span class="cp-badge">IN USE</span><?php endif; ?>
                </button>
              <?php else: ?>
                <div class="cp-card is-locked" role="button" tabindex="0" data-no-sfx aria-disabled="true" aria-label="<?= lq_e($c['name']) ?>, locked until Level <?= (int)$c['level'] ?>">
                  <?= lq_char_svg($id) ?>
                  <span class="cp-name"><?= lq_e($c['name']) ?></span>
                  <span class="cp-tag"><?= lq_e($c['gender']) ?></span>
                  <span class="cp-lock">&#128274; LEVEL <?= (int)$c['level'] ?></span>
                </div>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>

          <div class="btns">
            <button type="button" class="px px-stone" id="cp-close">Close</button>
          </div>
        </form>
      </dialog>

      <h2 class="section-title">Quick Actions</h2>
      <div class="actions">
        <form id="quick-upload" action="/documents" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="csrf" value="<?= lq_e(lq_csrf()) ?>" />
          <input type="hidden" name="action" value="upload" />
          <input type="file" id="qfile" name="reviewer" accept=".pdf,.png,.jpg,.jpeg,application/pdf,image/png,image/jpeg" hidden />
          <button type="button" class="tile tile-upload" id="upload-btn">
            <span class="big" aria-hidden="true">📄<span class="plus">+</span></span>
            <strong>Upload</strong>
            <span class="sub">Reviewer (PDF)</span>
          </button>
        </form>

        <?php if ($hasDoc): ?>
          <a href="/play" class="tile tile-play">
            <span class="big" aria-hidden="true">⚔️</span>
            <strong>Start Adventure</strong>
          </a>
        <?php else: ?>
          <button type="button" class="tile tile-play" id="play-btn">
            <span class="big" aria-hidden="true">⚔️</span>
            <strong>Start Adventure</strong>
          </button>
        <?php endif; ?>
      </div>

      <h2 class="section-title">Latest Announcement</h2>
      <div class="announce">
        <span class="icon" aria-hidden="true">📢</span>
        <?php if ($latest): ?>
          <div>
            <strong><?= lq_e($latest['title']) ?></strong><br />
            <span><?= lq_e(mb_strimwidth((string)$latest['body'], 0, 160, '…')) ?></span><br />
            <small><?= lq_e($latest['author_name']) ?> &middot; <?= lq_e(lq_when($latest['created_at'])) ?> &middot; <a href="/announcements">See all</a></small>
          </div>
        <?php else: ?>
          <span>No new announcements.</span>
        <?php endif; ?>
      </div>

      <?php if (!$hasDoc): ?>
        <dialog id="need-upload" class="pxdialog">
          <h2>Upload a Reviewer First!</h2>
          <p>Your adventure is built from your reviewer. Choose a PDF or scanned file from your device to begin.</p>
          <div class="btns">
            <button type="button" class="px px-green" id="dlg-choose">Choose File</button>
            <button type="button" class="px px-stone" id="dlg-close">Not now</button>
          </div>
        </dialog>
      <?php endif; ?>

      <script>
        (function () {
          var file = document.getElementById('qfile');
          var form = document.getElementById('quick-upload');
          document.getElementById('upload-btn').addEventListener('click', function () { file.click(); });
          file.addEventListener('change', function () { if (file.files.length) form.submit(); });

          var dlg = document.getElementById('need-upload');
          var play = document.getElementById('play-btn');
          if (dlg && play) {
            play.addEventListener('click', function () { dlg.showModal(); });
            document.getElementById('dlg-choose').addEventListener('click', function () { dlg.close(); file.click(); });
            document.getElementById('dlg-close').addEventListener('click', function () { dlg.close(); });
            <?php if (!empty($_GET['need'])): ?>dlg.showModal();<?php endif; ?>
          }
        })();

        // Character picker
        (function () {
          var picker = document.getElementById('char-picker');
          document.getElementById('open-picker').addEventListener('click', function () { picker.showModal(); });
          document.getElementById('cp-close').addEventListener('click', function () { picker.close(); });
          // click outside the box closes it
          picker.addEventListener('click', function (e) { if (e.target === picker) picker.close(); });

          // locked characters: shake + "nope" sound
          function nope(card) {
            card.classList.remove('shake');
            void card.offsetWidth;
            card.classList.add('shake');
            if (window.LQ_SFX) window.LQ_SFX.play('wrong');
          }
          Array.prototype.forEach.call(picker.querySelectorAll('.cp-card.is-locked'), function (card) {
            card.addEventListener('click', function () { nope(card); });
            card.addEventListener('keydown', function (e) {
              if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); nope(card); }
            });
          });
        })();
      </script>
<?php require __DIR__ . '/../partials/app-close.php'; ?>