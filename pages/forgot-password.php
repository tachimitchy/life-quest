<?php
require_once __DIR__ . '/../lib/auth.php';
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
if (empty($_SESSION['csrf'])) {
  $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

$error = null;
$sent  = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');

  if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
    $error = 'Invalid form submission. Please refresh and try again.';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = 'Please enter a valid email address.';
  } else {
    // Where the email link sends the user. Set APP_URL in .env for your live site.
    $base = getenv('APP_URL') ?: ((is_https() ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']);
    $r = sb('POST', '/auth/v1/recover?redirect_to=' . rawurlencode(rtrim($base, '/') . '/reset-password'), ['email' => $email]);

    if ($r['status'] === 429) {
      $error = 'Too many requests. Please wait a few minutes and try again.';
    } else {
      $sent = true;   // same message whether or not the email has an account
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Forgot Password - Life Quest</title>
  <?php require __DIR__ . '/../partials/auth-style.php'; ?>
</head>
<body>
<?php require __DIR__ . '/../partials/scene.php'; ?>

  <main class="card">
    <header class="header">
      <span class="lq-title lq-sm"><span class="lq-life">Life</span> <span class="lq-quest">Quest</span></span>
      <h1>Forgot Password</h1>
      <p>Enter your email and we’ll send you a reset link.</p>
    </header>

    <?php if ($error): ?>
      <div class="alert alert-error" role="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($sent): ?>
      <div class="alert alert-success" role="status">
        If that email has an account, a reset link is on its way. Check your inbox and spam folder.
      </div>
    <?php else: ?>
      <form action="/forgot-password" method="POST">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>" />

        <div class="form-group">
          <label for="email">Email address</label>
          <input type="email" id="email" name="email" placeholder="name@example.com" required autocomplete="email" />
        </div>

        <button type="submit" class="btn-submit">Send link</button>
      </form>
    <?php endif; ?>

    <footer class="footer">
      Remembered it? <a href="/login">Login</a>
    </footer>
  </main>
</body>
</html>