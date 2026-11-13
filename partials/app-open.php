<?php
// Opens a dashboard-style page. Set $user, $active and $pageTitle before including this file.
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
  <script>try{var a=parseFloat(localStorage.getItem('lq_alpha'));if(a>=0.3&&a<=1){var r=document.documentElement;r.style.setProperty('--ui-alpha',a);if(a<0.9)r.classList.add('ui-clear');}}catch(e){}</script>
</head>
<body>
<?php require __DIR__ . '/scene.php'; ?>

  <div class="app">
    <?php require __DIR__ . '/sidebar.php'; ?>

    <main class="main">
      <?php if ($flash): ?>
        <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'error' ?>" role="status"><?= lq_e($flash['msg']) ?></div>
      <?php endif; ?>