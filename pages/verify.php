<?php
// Asks for the 6-digit code that Supabase emails to the person's Gmail, then logs them in.
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/lq.php';

lq_session();
$email = strtolower(trim((string)($_GET['email'] ?? $_POST['email'] ?? '')));
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) lq_go('/register');

$error = null;
$info  = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!lq_csrf_ok()) {
    $error = 'Invalid form submission. Please refresh and try again.';

  } elseif (($_POST['action'] ?? '') === 'resend') {
    $wait = 60 - (time() - (int)($_SESSION['lq_resend_at'] ?? 0));
    if ($wait > 0) {
      $error = 'Please wait ' . $wait . ' seconds before asking for a new code.';
    } else {
      $_SESSION['lq_resend_at'] = time();
      $r = lq_request('POST', '/auth/v1/resend', ['type' => 'signup', 'email' => $email]);
      if (lq_ok($r)) $info = 'A new code was sent. Check your Gmail inbox and your spam folder.';
      else $error = ($r['status'] === 429) ? 'Too many emails right now. Please wait a few minutes and try again.' : 'Could not send a new code. Please try again later.';
    }

  } else {
    $code  = preg_replace('/\D/', '', (string)($_POST['code'] ?? ''));
    $tries = array_filter($_SESSION['lq_vtries'] ?? [], fn($t) => $t > time() - 600);   // at most 8 guesses per 10 minutes
    if (count($tries) >= 8) {
      $error = 'Too many wrong codes. Please wait a few minutes, or ask for a new code.';
    } elseif (strlen($code) < 6) {
      $error = 'Please type the 6-digit code from your email.';
    } else {
      $ok = false;
      foreach (['email', 'signup'] as $type) {          // Supabase accepts one of these names depending on its version
        $r = lq_request('POST', '/auth/v1/verify', ['type' => $type, 'email' => $email, 'token' => $code]);
        if (lq_ok($r) && !empty($r['data']['access_token'])) { $ok = true; break; }
      }
      if ($ok) {
        unset($_SESSION['lq_vtries']);
        set_auth_cookies($r['data']['access_token'], $r['data']['refresh_token'], (int)($r['data']['expires_in'] ?? 3600));
        $u = current_user();
        lq_go($u ? home_for_role($u['role']) : '/login');
      }
      $tries[] = time();
      $_SESSION['lq_vtries'] = array_values($tries);
      $error = 'That code is not correct or has expired. You can ask for a new one below.';
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Verify your email - Life Quest</title>
  <?php require __DIR__ . '/../partials/auth-style.php'; ?>
  <style>
    .code-input { width: 100%; padding: 0.6rem 0.85rem; text-align: center; letter-spacing: 0.5em; font-family: "Press Start 2P", "Courier New", monospace; font-size: 1.2rem;
      color: #fff; background: #0b1026; border: 3px solid var(--stone-dark); outline: none; }
    .code-input:focus { border-color: var(--gold); box-shadow: 0 0 0 3px rgba(255, 213, 74, 0.25); }
    .btn-alt { background: var(--stone); box-shadow: inset -4px -4px 0 var(--stone-dark), inset 4px 4px 0 #c3c9d8; }
    .hint { margin-top: 1rem; font-size: 1.25rem; color: #8e9ad0; text-align: center; }
  </style>
</head>
<body>
<?php require __DIR__ . '/../partials/scene.php'; ?>

  <main class="card">
    <header class="header">
      <span class="lq-title lq-sm"><span class="lq-life">Life</span> <span class="lq-quest">Quest</span></span>
      <h1>Verify Email</h1>
      <p>We sent a 6-digit code to <b><?= lq_e($email) ?></b>. Type it below to confirm it is really you.</p>
    </header>

    <?php if (!empty($_GET['rolefail'])): ?>
      <div class="alert alert-error" role="alert">Your account was created, but the Teacher or Admin role could not be set. Please contact an admin.</div>
    <?php endif; ?>
    <?php if ($error): ?><div class="alert alert-error" role="alert"><?= lq_e($error) ?></div><?php endif; ?>
    <?php if ($info): ?><div class="alert alert-success" role="status"><?= lq_e($info) ?></div><?php endif; ?>

    <form action="/verify" method="POST">
      <input type="hidden" name="csrf" value="<?= lq_e(lq_csrf()) ?>" />
      <input type="hidden" name="email" value="<?= lq_e($email) ?>" />
      <div class="form-group">
        <label for="code">Verification code</label>
        <input class="code-input" type="text" id="code" name="code" inputmode="numeric" pattern="[0-9]*" maxlength="8" autocomplete="one-time-code" placeholder="000000" required autofocus />
      </div>
      <button type="submit" class="btn-submit">Verify</button>
    </form>

    <form action="/verify" method="POST" style="margin-top:0.8rem;">
      <input type="hidden" name="csrf" value="<?= lq_e(lq_csrf()) ?>" />
      <input type="hidden" name="email" value="<?= lq_e($email) ?>" />
      <input type="hidden" name="action" value="resend" />
      <button type="submit" class="btn-submit btn-alt">Send a new code</button>
    </form>

    <p class="hint">No email? Check your spam folder. The code works for one hour.</p>

    <footer class="footer"><a href="/login">Back to login</a></footer>
  </main>
</body>
</html>