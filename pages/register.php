<?php
require_once __DIR__ . '/../lib/env.php';
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (!function_exists('e')) {
    function e(string $v): string {
        return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
    }
}

$supabaseUrl = getenv('SUPABASE_URL');
$supabaseKey = getenv('SUPABASE_ANON_KEY');

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

$errors  = [];
$success = false;
$old     = ['full_name' => '', 'email' => '', 'role' => 'student'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
        $errors[] = 'Invalid form submission. Please refresh and try again.';
    }

    $fullName = trim($_POST['full_name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';
    $role     = $_POST['role'] ?? 'student';

    $old = ['full_name' => $fullName, 'email' => $email, 'role' => $role];

    if (!preg_match('/^[\p{L}\p{M} .\'-]{2,60}$/u', $fullName)) {
        $errors[] = 'Full name must be 2-60 characters (letters, spaces, . \' -).';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }
    if ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    }
    if (!in_array($role, ['student', 'teacher'], true)) {
        $errors[] = 'Please choose Student or Teacher.';
    }

    if (!$errors) {
        $ch = curl_init($supabaseUrl . '/auth/v1/signup');
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'apikey: ' . $supabaseKey,
            ],
            CURLOPT_POSTFIELDS     => json_encode([
                'email'    => $email,
                'password' => $password,
                // Saved as the role the user asked for. Approve Teacher accounts on your side.
                'data'     => ['full_name' => $fullName, 'requested_role' => $role],
            ]),
        ]);
        $response = curl_exec($ch);
        $status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false) {
            $errors[] = 'Could not reach the server. Please try again.';
        } else {
            $result = json_decode($response, true);

            if ($status >= 200 && $status < 300) {
                $success = true;
                $old = ['full_name' => '', 'email' => '', 'role' => 'student'];
                $_SESSION['csrf'] = bin2hex(random_bytes(32));
            } else {
                $errors[] = $result['msg'] ?? $result['error_description'] ?? 'Registration failed. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Create Account - Life Quest</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=VT323&display=swap" rel="stylesheet" />
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --night: #0b1026;
      --night-2: #1b2150;
      --gold: #ffd54a;
      --gold-dark: #c47f00;
      --grass: #4caf3d;
      --grass-dark: #2d7a24;
      --dirt: #7a4a24;
      --dirt-dark: #55311a;
      --stone: #8b93a7;
      --stone-dark: #4b5266;
    }

    body {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1.5rem 1.5rem 7rem;
      font-family: "VT323", "Courier New", monospace;
      background:
        radial-gradient(2px 2px at 12% 18%, #fff 50%, transparent 51%),
        radial-gradient(2px 2px at 28% 62%, #fff 50%, transparent 51%),
        radial-gradient(3px 3px at 47% 12%, #ffe9a8 50%, transparent 51%),
        radial-gradient(2px 2px at 63% 44%, #fff 50%, transparent 51%),
        radial-gradient(2px 2px at 78% 20%, #fff 50%, transparent 51%),
        radial-gradient(3px 3px at 90% 58%, #ffe9a8 50%, transparent 51%),
        linear-gradient(180deg, var(--night) 0%, var(--night-2) 100%);
      background-attachment: fixed;
    }

    .ground {
      position: fixed;
      left: 0; right: 0; bottom: 0;
      height: 5rem;
      border-top: 16px solid var(--grass);
      box-shadow: inset 0 -8px 0 var(--grass-dark);
      background:
        repeating-linear-gradient(90deg, transparent 0 28px, var(--dirt-dark) 28px 32px),
        repeating-linear-gradient(0deg, transparent 0 28px, var(--dirt-dark) 28px 32px),
        var(--dirt);
      pointer-events: none;
    }

    .card {
      width: 100%;
      max-width: 440px;
      padding: 2.25rem 2rem 2rem;
      background: #12183a;
      border: 4px solid var(--stone);
      box-shadow: 0 0 0 4px var(--stone-dark), 8px 8px 0 4px rgba(0, 0, 0, 0.45);
    }

    .header { margin-bottom: 1.75rem; text-align: center; }

    .header h1 {
      font-family: "Press Start 2P", "Courier New", monospace;
      font-size: clamp(1.3rem, 6vw, 1.7rem);
      line-height: 1.3;
      color: var(--gold);
      text-shadow: 3px 3px 0 var(--gold-dark), 6px 6px 0 rgba(0, 0, 0, 0.5);
    }

    .header p { margin-top: 1.25rem; font-size: 1.4rem; color: #8e9ad0; }

    .form-group { margin-bottom: 1.1rem; }

    label {
      display: block;
      margin-bottom: 0.35rem;
      font-size: 1.35rem;
      color: #dbe3ff;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 0.6rem 0.85rem;
      font-family: inherit;
      font-size: 1.4rem;
      color: #fff;
      background: #0b1026;
      border: 3px solid var(--stone-dark);
      outline: none;
    }

    input:focus {
      border-color: var(--gold);
      box-shadow: 0 0 0 3px rgba(255, 213, 74, 0.25);
    }

    .role-picker { margin-bottom: 1.25rem; border: 0; }
    .role-picker legend { margin-bottom: 0.35rem; font-size: 1.35rem; color: #dbe3ff; }
    .role-options { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
    .role-option { position: relative; margin: 0; cursor: pointer; }
    .role-option input {
      position: absolute;
      opacity: 0;
      inset: 0;
      cursor: pointer;
    }
    .role-option span {
      display: block;
      padding: 0.8rem 0.5rem;
      text-align: center;
      font-family: "Press Start 2P", "Courier New", monospace;
      font-size: 0.7rem;
      color: #c3c9d8;
      background: var(--stone-dark);
      border: 4px solid #000;
      box-shadow: inset -4px -4px 0 #343a4c, inset 4px 4px 0 #6b7388;
    }
    .role-option input:checked + span {
      color: #fff;
      background: var(--grass);
      box-shadow: inset -4px -4px 0 var(--grass-dark), inset 4px 4px 0 #8be07a;
      text-shadow: 2px 2px 0 rgba(0, 0, 0, 0.5);
    }
    .role-option input:focus-visible + span { outline: 3px dashed var(--gold); outline-offset: 4px; }

    .btn-submit {
      width: 100%;
      margin-top: 0.5rem;
      padding: 0.9rem 1rem;
      font-family: "Press Start 2P", "Courier New", monospace;
      font-size: 0.85rem;
      color: #fff;
      background: var(--grass);
      border: 4px solid #000;
      box-shadow: inset -4px -4px 0 var(--grass-dark), inset 4px 4px 0 #8be07a;
      text-shadow: 2px 2px 0 rgba(0, 0, 0, 0.5);
      cursor: pointer;
      transition: transform 0.08s steps(2);
    }

    .btn-submit:hover { transform: translateY(-3px); }
    .btn-submit:active { transform: translateY(2px); }
    .btn-submit:focus-visible { outline: 3px dashed var(--gold); outline-offset: 4px; }

    .alert {
      margin-bottom: 1.25rem;
      padding: 0.75rem 1rem;
      font-size: 1.3rem;
      border: 3px solid;
    }
    .alert ul { padding-left: 1.2rem; }
    .alert-error { color: #ffd0d0; background: #3a1220; border-color: #e5484d; }
    .alert-success { color: #d4ffd0; background: #11331a; border-color: var(--grass); }

    .footer { margin-top: 1.75rem; text-align: center; font-size: 1.35rem; color: #8e9ad0; }
    .footer a { color: var(--gold); font-weight: 700; text-decoration: none; }
    .footer a:hover { text-decoration: underline; }

    @media (prefers-reduced-motion: reduce) { .btn-submit { transition: none; } }
  </style>
</head>
<body>

  <main class="card">
    <header class="header">
      <h1>Create Account</h1>
      <p>Join Life Quest and start your adventure.</p>
    </header>

    <?php if ($errors): ?>
      <div class="alert alert-error" role="alert">
        <ul>
          <?php foreach ($errors as $err): ?>
            <li><?= e($err) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="alert alert-success" role="status">
        Account created! You can now log in. If you don't get in, check your email for a confirmation link.
      </div>
    <?php endif; ?>

    <form action="register.php" method="POST" novalidate>
      <input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>" />

      <fieldset class="role-picker">
        <legend>I am a</legend>
        <div class="role-options">
          <label class="role-option">
            <input type="radio" name="role" value="student" <?= $old['role'] !== 'teacher' ? 'checked' : '' ?> />
            <span>Student</span>
          </label>
          <label class="role-option">
            <input type="radio" name="role" value="teacher" <?= $old['role'] === 'teacher' ? 'checked' : '' ?> />
            <span>Teacher</span>
          </label>
        </div>
      </fieldset>

      <div class="form-group">
        <label for="full_name">Full Name</label>
        <input type="text" id="full_name" name="full_name" placeholder="Juan Dela Cruz"
               value="<?= e($old['full_name']) ?>" required autocomplete="name" />
      </div>

      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" placeholder="name@example.com"
               value="<?= e($old['email']) ?>" required autocomplete="email" />
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="At least 8 characters"
               required minlength="8" autocomplete="new-password" />
      </div>

      <div class="form-group">
        <label for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Type it again"
               required minlength="8" autocomplete="new-password" />
      </div>

      <button type="submit" class="btn-submit">Register</button>
    </form>

    <footer class="footer">
      Already have an account? <a href="login.php">Login</a>
    </footer>
  </main>

  <div class="ground" aria-hidden="true"></div>

  <script src="script.js"></script>
</body>
</html>