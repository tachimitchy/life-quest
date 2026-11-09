<?php
// Opens a dashboard-style page. Set $user, $active and $pageTitle before including this file.
require_once __DIR__ . '/../lib/profile.php';
if (isset($user) && is_array($user)) {
  lq_profile_handle_post($user);   // saves a new name / character, then redirects back
}
header('Cache-Control: no-store, max-age=0');   // so "Back" after logout can't show a cached page
$flash = lq_flash_pull();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= lq_e($pageTitle) ?> - Life Quest</title>
  <?php require __DIR__ . '/app-style.php'; ?>
</head>
<body>
<?php require __DIR__ . '/scene.php'; ?>

  <div class="app">
    <?php require __DIR__ . '/sidebar.php'; ?>
    <?php require __DIR__ . '/profile.php'; ?>

    <main class="main">
      <?php if ($flash): ?>
        <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'error' ?>" role="status"><?= lq_e($flash['msg']) ?></div>
      <?php endif; ?>