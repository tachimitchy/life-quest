<?php
require_once __DIR__ . '/../lib/auth.php';

$error = null;
$registered = !empty($_GET['registered']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $error = auth_login(trim($_POST['email'] ?? ''), $_POST['password'] ?? '');
  if (!$error) {
    $u = current_user();
    redirect(home_for_role($u['role'] ?? ''));
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Life Quest</title>
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
      padding: 1.5rem 1.5rem 1.5rem;
      font-family: "VT323", "Courier New", monospace;
      background: #2b2a5a;
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
      font-size: clamp(1.4rem, 7vw, 1.9rem);
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

    input[type="email"]:focus,
    input[type="password"]:focus {
      border-color: var(--gold);
      box-shadow: 0 0 0 3px rgba(255, 213, 74, 0.25);
    }

    .options {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 1.25rem;
      font-size: 1.3rem;
    }

    .remember-me {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      margin: 0;
      font-size: 1.3rem;
      color: #8e9ad0;
      cursor: pointer;
    }

    .remember-me input { width: 1.1rem; height: 1.1rem; accent-color: var(--grass); }

    .forgot-password { color: var(--gold); text-decoration: none; }
    .forgot-password:hover { text-decoration: underline; }

    .btn-submit {
      width: 100%;
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

    .btn-back {
      display: block;
      width: 100%;
      margin-top: 1rem;
      padding: 0.9rem 1rem;
      text-align: center;
      text-decoration: none;
      font-family: "Press Start 2P", "Courier New", monospace;
      font-size: 0.85rem;
      color: #fff;
      background: var(--stone);
      border: 4px solid #000;
      box-shadow: inset -4px -4px 0 var(--stone-dark), inset 4px 4px 0 #c3c9d8;
      text-shadow: 2px 2px 0 rgba(0, 0, 0, 0.5);
      transition: transform 0.08s steps(2);
    }

    .btn-back:hover { transform: translateY(-3px); }
    .btn-back:active { transform: translateY(2px); }
    .btn-back:focus-visible { outline: 3px dashed var(--gold); outline-offset: 4px; }

    .alert {
      margin-bottom: 1.25rem;
      padding: 0.75rem 1rem;
      font-size: 1.3rem;
      color: #ffd0d0;
      background: #3a1220;
      border: 3px solid #e5484d;
    }

    .alert-success { color: #d4ffd0; background: #11331a; border-color: var(--grass); }

    .footer { margin-top: 1.75rem; text-align: center; font-size: 1.35rem; color: #8e9ad0; }
    .footer a { color: var(--gold); font-weight: 700; text-decoration: none; }
    .footer a:hover { text-decoration: underline; }

    @media (prefers-reduced-motion: reduce) { .btn-submit, .btn-back { transition: none; } }
  
    .logo { display: block; width: min(300px, 80%); height: auto; margin: 0 auto; image-rendering: auto; filter: drop-shadow(0 6px 0 rgba(0, 0, 0, 0.35)); }
    .logo-sm { width: min(150px, 45%); margin-bottom: 0.9rem; }
    .title .logo { width: min(340px, 90%); }
    .header h1 { line-height: 1; }
  </style>
</head>
<body>
<?php require __DIR__ . '/../partials/scene.php'; ?>

  <main class="card">
    <header class="header">
      <h1><span class="lq-badge" aria-hidden="true"><span class="lq-paper"></span><span class="lq-pencil"></span><span class="lq-ray r1"></span><span class="lq-ray r2"></span><span class="lq-ray r3"></span></span><span class="lq-title"><span class="lq-life">Life</span><span class="lq-quest">Quest</span></span></h1>
      <p>Please enter your details to sign in.</p>
    </header>

    <?php if ($registered && !$error): ?>
      <div class="alert alert-success" role="status">Account created! Please log in.</div>
    <?php endif; ?>

    <?php if ($error): ?>
      <div class="alert" role="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="/login" method="POST">
      <div class="form-group">
        <label for="email">Email address</label>
        <input
          type="email"
          id="email"
          name="email"
          placeholder="name@example.com"
          required
          autocomplete="email"
        />
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input
          type="password"
          id="password"
          name="password"
          placeholder="••••••••"
          required
          autocomplete="current-password"
        />
      </div>

      <div class="options">
        <label class="remember-me">
          <input type="checkbox" name="remember" />
          <span>Remember me</span>
        </label>
        <a href="/forgot-password" class="forgot-password">Forgot password?</a>
      </div>

      <button type="submit" class="btn-submit">Sign in</button>
    </form>

    <a href="/" class="btn-back">Back to Home</a>

    <footer class="footer">
      Don’t have an account? <a href="/register">Sign up</a>
    </footer>
  </main>

</body>
</html>