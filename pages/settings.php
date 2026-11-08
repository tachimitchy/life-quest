<?php
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/lq.php';

$user = require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (lq_csrf_ok()) {
    lq_set_theme((string)($_POST['theme'] ?? 'day'));
    lq_flash('success', 'Settings saved!');
  } else {
    lq_flash('error', 'Invalid form submission. Please try again.');
  }
  lq_go('/settings');
}

$night = lq_is_night();

$pageTitle = 'Settings';
$active    = 'settings';
require __DIR__ . '/../partials/app-open.php';
?>
      <h1 class="page-title">Settings</h1>
      <p class="lead">Make Life Quest look the way you like.</p>

      <div class="panel">
        <h2>Account</h2>
        <p>Name: <?= lq_e($user['full_name']) ?></p>
        <p>Email: <?= lq_e($user['email']) ?></p>
        <p>Role: <?= lq_e(ucfirst($user['role'])) ?></p>
      </div>

      <form class="panel" action="/settings" method="POST">
        <h2>Appearance</h2>
        <p style="margin-bottom:1rem;">Night Time turns the background into a starry night, and the letters and buttons twinkle like stars.</p>
        <input type="hidden" name="csrf" value="<?= lq_e(lq_csrf()) ?>" />
        <div class="theme-options">
          <label class="theme-option">
            <input type="radio" name="theme" value="day" <?= $night ? '' : 'checked' ?> />
            <span><em aria-hidden="true">☀️</em>Day Time</span>
          </label>
          <label class="theme-option">
            <input type="radio" name="theme" value="night" <?= $night ? 'checked' : '' ?> />
            <span><em aria-hidden="true">🌙</em>Night Time</span>
          </label>
        </div>
        <button type="submit" class="px px-green">Save</button>
      </form>
<?php require __DIR__ . '/../partials/app-close.php'; ?>