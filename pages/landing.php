<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Life Quest</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=VT323&display=swap" rel="stylesheet" />
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

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
      position: relative;
      width: 100%;
      max-width: 440px;
      padding: 2.5rem 2rem 2rem;
      background: #12183a;
      border: 4px solid var(--stone);
      box-shadow:
        0 0 0 4px var(--stone-dark),
        8px 8px 0 4px rgba(0, 0, 0, 0.45);
      text-align: center;
    }

    .header {
      margin-bottom: 2rem;
    }

    .title {
      font-family: "Press Start 2P", "Courier New", monospace;
      font-size: clamp(2.2rem, 11vw, 3.4rem);
      line-height: 1.15;
      letter-spacing: 0.04em;
      color: var(--gold);
      text-shadow:
        3px 3px 0 var(--gold-dark),
        6px 6px 0 #6b3d00,
        9px 9px 0 rgba(0, 0, 0, 0.5);
      animation: bob 3s steps(6) infinite;
    }

    .title span {
      display: block;
    }

    .title .quest {
      color: #7ee86b;
      text-shadow:
        3px 3px 0 var(--grass-dark),
        6px 6px 0 #14400f,
        9px 9px 0 rgba(0, 0, 0, 0.5);
    }

    .caption {
      margin-top: 1.75rem;
      font-size: 1.6rem;
      line-height: 1.2;
      color: #dbe3ff;
    }

    .sub-caption {
      margin-top: 0.5rem;
      font-size: 1.25rem;
      color: #8e9ad0;
    }

    .menu {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .btn {
      display: block;
      width: 100%;
      padding: 0.9rem 1rem;
      font-family: "Press Start 2P", "Courier New", monospace;
      font-size: 0.85rem;
      text-decoration: none;
      color: #fff;
      border: 4px solid #000;
      cursor: pointer;
      text-shadow: 2px 2px 0 rgba(0, 0, 0, 0.5);
      transition: transform 0.08s steps(2);
    }

    .btn:hover,
    .btn:focus-visible {
      transform: translateY(-3px);
      outline: none;
    }

    .btn:active {
      transform: translateY(2px);
    }

    .btn:focus-visible {
      outline: 3px dashed var(--gold);
      outline-offset: 4px;
    }

    .btn-login {
      background: var(--grass);
      box-shadow: inset -4px -4px 0 var(--grass-dark), inset 4px 4px 0 #8be07a;
    }

    .btn-register {
      background: #e0a800;
      box-shadow: inset -4px -4px 0 var(--gold-dark), inset 4px 4px 0 #ffe27a;
    }

    .btn-about {
      background: var(--stone);
      box-shadow: inset -4px -4px 0 var(--stone-dark), inset 4px 4px 0 #c3c9d8;
    }

    @keyframes bob {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-6px); }
    }

    @media (prefers-reduced-motion: reduce) {
      .title { animation: none; }
      .btn { transition: none; }
    }
  
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
      <h1 class="title"><span class="lq-title"><span class="lq-life">Life</span><span class="lq-quest">Quest</span></span></h1>
      <p class="caption">Turn your reviewer into an adventure.</p>
      <p class="sub-caption">Explore stages, defeat bosses, and prove what you know.</p>
    </header>

    <nav class="menu" aria-label="Main menu">
      <a href="/login" class="btn btn-login">Login</a>
      <a href="/register" class="btn btn-register">Register</a>
      <a href="/about" class="btn btn-about">About</a>
    </nav>
  </main>

</body>
</html>