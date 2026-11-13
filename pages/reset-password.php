<?php
require_once __DIR__ . '/../lib/auth.php';
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
if (empty($_SESSION['csrf'])) {
  $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

$error = null;
$done  = false;
$token = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $token    = $_POST['access_token'] ?? '';
  $password = $_POST['password'] ?? '';
  $confirm  = $_POST['confirm_password'] ?? '';

  if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
    $error = 'Invalid form submission. Please refresh and try again.';
  } elseif ($token === '') {
    $error = 'This reset link is invalid or has expired.';
  } elseif (strlen($password) < 8) {
    $error = 'Password must be at least 8 characters.';
  } elseif ($password !== $confirm) {
    $error = 'Passwords do not match.';
  } else {
    $r = sb('PUT', '/auth/v1/user', ['password' => $password], $token);
    if ($r['status'] >= 200 && $r['status'] < 300) {
      $done = true;
      $_SESSION['csrf'] = bin2hex(random_bytes(32));
    } else {
      $error = $r['data']['msg'] ?? $r['data']['error_description'] ?? 'Could not reset your password. The link may have expired.';
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reset Password - Life Quest</title>
  <?php require __DIR__ . '/../partials/auth-style.php'; ?>
</head>
<body>
<?php require __DIR__ . '/../partials/scene.php'; ?>

  <main class="card">
    <header class="header">
      <span class="lq-title lq-sm"><span class="lq-life">Life</span> <span class="lq-quest">Quest</span></span>
      <h1>New Password</h1>
      <p>Choose a new password for your account.</p>
    </header>

    <?php if ($done): ?>
      <div class="alert alert-success" role="status">Password updated! You can now log in.</div>
    <?php else: ?>
      <?php if ($error): ?>
        <div class="alert alert-error" role="alert"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <div id="invalid" class="alert alert-error" role="alert" hidden>
        This reset link is invalid or has expired. <a href="/forgot-password">Request a new one</a>.
      </div>

      <form id="reset-form" action="/reset-password" method="POST">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>" />
        <input type="hidden" id="access_token" name="access_token" value="<?= htmlspecialchars($token) ?>" />

        <div class="form-group">
          <label for="password">New Password</label>
          <input type="password" id="password" name="password" data-pw placeholder="At least 8 characters" required minlength="8" autocomplete="new-password" />
        </div>

        <div class="form-group">
          <label for="confirm_password">Confirm Password</label>
          <input type="password" id="confirm_password" name="confirm_password" data-pw placeholder="Type it again" required minlength="8" autocomplete="new-password" />
        </div>

        <label class="show-pw"><input type="checkbox" class="js-show-pw" /> Show password</label>
        <button type="submit" class="btn-submit">Save password</button>
      </form>

      <script>
        // The email link puts the reset token after the # in the address.
        // PHP can't see that part, so this copies it into the form.
        (function () {
          var form = document.getElementById('reset-form');
          var bad = document.getElementById('invalid');
          var field = document.getElementById('access_token');
          var params = new URLSearchParams(window.location.hash.slice(1));
          var token = params.get('access_token');

          if (token && params.get('type') === 'recovery') {
            field.value = token;
            history.replaceState(null, '', window.location.pathname);  // hide the token from the address bar
          } else if (!field.value) {
            form.hidden = true;
            bad.hidden = false;
          }
        })();
      </script>
    <?php endif; ?>

    <footer class="footer">
      <a href="/login">Back to login</a>
    </footer>
  </main>
</body>
</html>