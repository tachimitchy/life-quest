<?php
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/lq.php';

$user   = require_login(['admin']);
$player = lq_player($user);
$first  = explode(' ', trim($user['full_name']))[0] ?: 'Admin';

// Counts of each kind of account (admins are allowed to read every profile)
$pRes   = lq_request('GET', '/rest/v1/profiles?select=role', null, $user['jwt']);
$counts = array_count_values(array_column(lq_rows($pRes), 'role'));

$updates = lq_rows(lq_request('GET',
  '/rest/v1/announcements?select=title,body,created_at&category=eq.game_update&order=created_at.desc&limit=3', null, $user['jwt']));

$pageTitle = 'Admin Dashboard';
$active    = 'dashboard';
require __DIR__ . '/../partials/app-open.php';
?>
      <section class="welcome">
        <div class="hero-box" aria-hidden="true"><?= lq_sprite($player['character']) ?></div>
        <div>
          <h1>Welcome, Admin <?= lq_e($first) ?>!</h1>
          <p>Tell everyone when the game gets an update.</p>
        </div>
      </section>

      <h2 class="section-title">Quick Actions</h2>
      <div class="actions">
        <a href="/announcements" class="tile tile-play">
          <span class="big" aria-hidden="true">📢</span>
          <strong>Post Game Update</strong>
          <span class="sub">Shows on every student's dashboard</span>
        </a>
        <a href="/profile" class="tile tile-upload">
          <span class="big" aria-hidden="true">🧑‍🎤</span>
          <strong>My Profile</strong>
        </a>
      </div>

      <h2 class="section-title">Players</h2>
      <div class="stats">
        <div class="stat"><b><?= (int)($counts['student'] ?? 0) ?></b>Students</div>
        <div class="stat"><b><?= (int)($counts['teacher'] ?? 0) ?></b>Teachers</div>
        <div class="stat"><b><?= (int)($counts['admin'] ?? 0) ?></b>Admins</div>
      </div>
      <?php if (!lq_ok($pRes)): ?>
        <div class="alert alert-error" role="alert">Could not read the player counts. Run <b>supabase-setup-2.sql</b> in the Supabase SQL Editor.</div>
      <?php endif; ?>

      <h2 class="section-title">Recent Game Updates</h2>
      <?php if (!$updates): ?>
        <div class="panel empty"><span class="big" aria-hidden="true">📢</span>No game updates posted yet.</div>
      <?php endif; ?>
      <?php foreach ($updates as $u): ?>
        <article class="ann update">
          <h3><?= lq_e($u['title']) ?></h3>
          <p><?= lq_e(mb_strimwidth((string)$u['body'], 0, 200, '…')) ?></p>
          <div class="meta"><span><?= lq_e(lq_when($u['created_at'])) ?></span></div>
        </article>
      <?php endforeach; ?>
<?php require __DIR__ . '/../partials/app-close.php'; ?>