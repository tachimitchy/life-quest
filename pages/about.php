<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>About - Life Quest</title>
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
      padding: 2rem 1.5rem 8rem;
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
      max-width: 640px;
      padding: 2.25rem 2rem 2rem;
      background: #12183a;
      border: 4px solid var(--stone);
      box-shadow: 0 0 0 4px var(--stone-dark), 8px 8px 0 4px rgba(0, 0, 0, 0.45);
    }

    .header { margin-bottom: 1.5rem; text-align: center; }

    .header h1 {
      font-family: "Press Start 2P", "Courier New", monospace;
      font-size: clamp(1.3rem, 6vw, 1.8rem);
      line-height: 1.3;
      color: var(--gold);
      text-shadow: 3px 3px 0 var(--gold-dark), 6px 6px 0 rgba(0, 0, 0, 0.5);
    }

    .description {
      margin-bottom: 1.75rem;
      font-size: 1.6rem;
      line-height: 1.25;
      color: #dbe3ff;
      text-align: center;
    }

    .features {
      display: grid;
      gap: 1rem;
      margin-bottom: 1.75rem;
    }

    .feature {
      padding: 0.9rem 1rem;
      background: #0b1026;
      border: 3px solid var(--stone-dark);
      border-left: 10px solid var(--grass);
    }

    .feature:nth-child(2) { border-left-color: #e5484d; }
    .feature:nth-child(3) { border-left-color: var(--gold); }

    .feature h2 {
      margin-bottom: 0.4rem;
      font-family: "Press Start 2P", "Courier New", monospace;
      font-size: 0.8rem;
      line-height: 1.4;
      color: #fff;
    }

    .feature p { font-size: 1.4rem; line-height: 1.2; color: #8e9ad0; }

    .roles {
      margin-bottom: 1.75rem;
      font-size: 1.4rem;
      line-height: 1.25;
      color: #8e9ad0;
      text-align: center;
    }

    .roles strong { color: var(--gold); font-weight: 400; }

    .menu {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 0.75rem;
    }

    .btn {
      display: block;
      padding: 0.85rem 0.5rem;
      text-align: center;
      text-decoration: none;
      font-family: "Press Start 2P", "Courier New", monospace;
      font-size: 0.7rem;
      color: #fff;
      border: 4px solid #000;
      text-shadow: 2px 2px 0 rgba(0, 0, 0, 0.5);
      transition: transform 0.08s steps(2);
    }

    .btn:hover, .btn:focus-visible { transform: translateY(-3px); outline: none; }
    .btn:active { transform: translateY(2px); }
    .btn:focus-visible { outline: 3px dashed var(--gold); outline-offset: 4px; }

    .btn-login { background: var(--grass); box-shadow: inset -4px -4px 0 var(--grass-dark), inset 4px 4px 0 #8be07a; }
    .btn-register { background: #e0a800; box-shadow: inset -4px -4px 0 var(--gold-dark), inset 4px 4px 0 #ffe27a; }
    .btn-home { background: var(--stone); box-shadow: inset -4px -4px 0 var(--stone-dark), inset 4px 4px 0 #c3c9d8; }

    @media (max-width: 480px) { .menu { grid-template-columns: 1fr; } }
    @media (prefers-reduced-motion: reduce) { .btn { transition: none; } }
  </style>
</head>
<body>

  <main class="card">
    <header class="header">
      <h1>About Life Quest</h1>
    </header>

    <p class="description">
      Project Life Quest is a web-based educational adventure game that transforms a student’s
      reviewer into a Terraria-style platform adventure. Instead of simply reading notes and
      answering a quiz, players explore stages, fight bosses, and demonstrate their knowledge
      to progress.
    </p>

    <section class="features" aria-label="How the game works">
      <article class="feature">
        <h2>Explore stages</h2>
        <p>Each stage is built from reviewer topics. Move through the world and meet questions along the way.</p>
      </article>
      <article class="feature">
        <h2>Fight bosses</h2>
        <p>Show what you know to defeat a boss and unlock the next stage.</p>
      </article>
      <article class="feature">
        <h2>Beat your best score</h2>
        <p>Race the timer, climb the scoreboard, and try to top your own highest score.</p>
      </article>
    </section>

    <p class="roles">
      Made for <strong>Students</strong> who play, <strong>Teachers</strong> who guide their classes,
      and <strong>Admins</strong> who keep everything running.
    </p>

    <nav class="menu" aria-label="Page links">
      <a href="/login" class="btn btn-login">Login</a>
      <a href="/register" class="btn btn-register">Register</a>
      <a href="/index" class="btn btn-home">Home</a>
    </nav>
  </main>

  <div class="ground" aria-hidden="true"></div>

  <script src="script.js"></script>
</body>
</html>