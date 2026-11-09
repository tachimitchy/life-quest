<?php
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/lq.php';
require_once __DIR__ . '/../lib/characters.php';

$user  = require_login(['student']);
$first = explode(' ', trim($user['full_name']))[0] ?: 'Adventurer';

// Character shown next to the welcome message (picking happens in the profile window)
$chars    = lq_characters();
$level    = lq_player_level($user);
$charId   = lq_char_current($user);
$charInfo = $chars[$charId];

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
        /* Clickable profile character in the welcome box */
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
        @media (prefers-reduced-motion: reduce) { .hero-pick .lq-sprite { transition: none; } }
      </style>

      <?php if (!$dbReady): ?>
        <div class="alert alert-error" role="alert">The database is not set up yet. Run <b>supabase-setup.sql</b> in the Supabase SQL Editor.</div>
      <?php endif; ?>

      <section class="welcome">
        <div class="hero-box">
          <button type="button" class="hero-pick" data-open-profile="chars" aria-label="Open your profile (currently <?= lq_e($charInfo['name']) ?>)">
            <?= lq_char_svg($charId) ?>
          </button>
          <span class="hero-edit" aria-hidden="true">Profile</span>
        </div>
        <div>
          <h1>Welcome back, <?= lq_e($first) ?>!</h1>
          <p>Keep studying and reach new heights!</p>
          <p class="lv-chip">Level <?= (int)$level ?> &middot; <?= lq_e($charInfo['name']) ?></p>
        </div>
      </section>

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
      </script>
<?php require __DIR__ . '/../partials/app-close.php'; ?>