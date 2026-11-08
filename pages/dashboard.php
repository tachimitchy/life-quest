<?php
require_once __DIR__ . '/../lib/auth.php';

$user  = require_login(['student']);     // only logged-in students can see this page
$name  = $user['full_name'];
$first = explode(' ', trim($name))[0] ?: 'Adventurer';
$level = 1;                               // later: read this from your game_progress table
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard - Life Quest</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=VT323&display=swap" rel="stylesheet" />
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --gold: #ffd54a; --gold-dark: #c47f00;
      --grass: #4caf3d; --grass-dark: #2d7a24;
      --stone: #8b93a7; --stone-dark: #4b5266;
      --red: #e5484d; --red-dark: #9c2a2e;
    }

    body {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1.25rem 1.25rem calc(max(14vh, 64px) + 1.25rem);
      font-family: "VT323", "Courier New", monospace;
      background: #2b2a5a;
      color: #dbe3ff;
    }

    .app {
      display: grid;
      grid-template-columns: 250px 1fr;
      width: 100%;
      max-width: 980px;
      background: #12183a;
      border: 4px solid var(--stone);
      box-shadow: 0 0 0 4px var(--stone-dark), 8px 8px 0 4px rgba(0, 0, 0, 0.45);
    }

    /* ---------- sidebar ---------- */
    .side { padding: 1.5rem 1rem; background: #0e1330; border-right: 4px solid var(--stone-dark); }

    .profile { text-align: center; margin-bottom: 1.5rem; }

    .avatar {
      position: relative;
      width: 84px; height: 84px;
      margin: 0 auto 0.8rem;
      background: #2b2a5a;
      border: 4px solid var(--gold);
      box-shadow: 0 0 0 3px #000;
    }
    .avatar i { position: absolute; left: 6px; top: 18px; width: 1em; height: 1em; font-size: 6px; box-shadow: 2em 0em #6b3e1e, 3em 0em #6b3e1e, 4em 0em #6b3e1e, 5em 0em #6b3e1e, 6em 0em #6b3e1e, 1em 1em #6b3e1e, 2em 1em #6b3e1e, 3em 1em #6b3e1e, 4em 1em #6b3e1e, 5em 1em #6b3e1e, 6em 1em #6b3e1e, 7em 1em #6b3e1e, 1em 2em #6b3e1e, 2em 2em #6b3e1e, 3em 2em #6b3e1e, 4em 2em #f2c29b, 5em 2em #f2c29b, 6em 2em #f2c29b, 7em 2em #f2c29b, 1em 3em #6b3e1e, 2em 3em #6b3e1e, 3em 3em #f2c29b, 4em 3em #f2c29b, 5em 3em #f2c29b, 6em 3em #111111, 7em 3em #f2c29b, 2em 4em #f2c29b, 3em 4em #f2c29b, 4em 4em #f2c29b, 5em 4em #f2c29b, 6em 4em #f2c29b, 7em 4em #f2c29b, 0em 5em #d6342c, 1em 5em #d6342c, 2em 5em #d6342c, 3em 5em #d6342c, 4em 5em #d6342c, 5em 5em #d6342c, 6em 5em #d6342c, 7em 5em #d6342c, 2em 6em #3b63c9, 3em 6em #3b63c9, 4em 6em #3b63c9, 5em 6em #3b63c9, 6em 6em #3b63c9, 7em 6em #3b63c9; }

    .profile-name { font-family: "Press Start 2P", "Courier New", monospace; font-size: 0.75rem; line-height: 1.5; color: #fff; word-break: break-word; }
    .profile-role { margin-top: 0.4rem; font-size: 1.2rem; color: #8e9ad0; }

    .nav { display: flex; flex-direction: column; gap: 0.6rem; }

    .nav a {
      display: flex; align-items: center; gap: 0.7rem;
      padding: 0.6rem 0.75rem;
      font-size: 1.3rem;
      line-height: 1.1;
      min-width: 0;
      overflow-wrap: break-word;
      text-decoration: none;
      color: #dbe3ff;
      background: #1b2150;
      border: 3px solid #000;
      box-shadow: inset -3px -3px 0 #121741, inset 3px 3px 0 #2e377a;
      transition: transform 0.08s steps(2);
    }
    .nav a:hover, .nav a:focus-visible { transform: translateX(3px); outline: none; }
    .nav a:focus-visible { outline: 3px dashed var(--gold); outline-offset: 3px; }
    .nav .icon { width: 1.6rem; text-align: center; }
    .nav a.active { color: #fff; background: var(--grass); box-shadow: inset -3px -3px 0 var(--grass-dark), inset 3px 3px 0 #8be07a; text-shadow: 2px 2px 0 rgba(0, 0, 0, 0.5); }
    .nav a.logout { margin-top: 0.6rem; background: var(--red); color: #fff; box-shadow: inset -3px -3px 0 var(--red-dark), inset 3px 3px 0 #ff8f93; }

    /* ---------- main ---------- */
    .main { padding: 1.25rem; min-width: 0; }

    .welcome {
      display: flex; align-items: center; gap: 1rem;
      padding: 1rem 1.25rem;
      margin-bottom: 1.25rem;
      background: linear-gradient(90deg, #232a6b, #1b2150);
      border: 3px solid var(--gold);
      box-shadow: inset 0 0 0 3px #000;
    }
    .hero-box { position: relative; flex: none; width: 66px; height: 92px; }
    .hero-lg { position: absolute; left: 3px; top: 10px; width: 1em; height: 1em; font-size: 6px; animation: step 1s steps(1) infinite; }

    .welcome h1 { font-family: "Press Start 2P", "Courier New", monospace; font-size: clamp(0.9rem, 2.6vw, 1.2rem); line-height: 1.5; color: var(--gold); text-shadow: 3px 3px 0 var(--gold-dark), 5px 5px 0 rgba(0, 0, 0, 0.5); }
    .welcome p { margin-top: 0.5rem; font-size: 1.6rem; color: #dbe3ff; }

    .section-title { margin-bottom: 0.9rem; font-family: "Press Start 2P", "Courier New", monospace; font-size: 0.8rem; color: #fff; }

    .actions { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem; }

    .tile {
      display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.5rem;
      min-height: 150px; padding: 0.9rem;
      text-align: center; text-decoration: none; color: #fff;
      background: #1b2150;
      border: 4px solid #000;
      transition: transform 0.08s steps(2);
    }
    .tile:hover, .tile:focus-visible { transform: translateY(-4px); outline: none; }
    .tile:focus-visible { outline: 3px dashed var(--gold); outline-offset: 4px; }
    .tile .big { position: relative; font-size: 3.6rem; line-height: 1; }
    .tile .plus { position: absolute; right: -0.55rem; bottom: -0.2rem; width: 1.3rem; height: 1.3rem; font-size: 1rem; line-height: 1.3rem; background: var(--grass); border: 2px solid #000; font-family: "Press Start 2P", monospace; }
    .tile strong { font-family: "Press Start 2P", "Courier New", monospace; font-size: 0.8rem; line-height: 1.5; text-shadow: 2px 2px 0 rgba(0, 0, 0, 0.5); }
    .tile span.sub { font-size: 1.35rem; color: #c3c9ee; }
    .tile-upload { box-shadow: inset -4px -4px 0 var(--grass-dark), inset 4px 4px 0 #3c8a30, 0 0 0 3px var(--grass); }
    .tile-play { box-shadow: inset -4px -4px 0 var(--gold-dark), inset 4px 4px 0 #a07a00, 0 0 0 3px var(--gold); }

    .announce { display: flex; align-items: center; gap: 1rem; padding: 1rem 1.25rem; font-size: 1.5rem; background: #0b1026; border: 3px solid var(--stone-dark); }
    .announce .icon { font-size: 2.2rem; }

    @media (max-width: 760px) {
      .app { grid-template-columns: 1fr; }
      .side { border-right: 0; border-bottom: 4px solid var(--stone-dark); }
      .nav { flex-direction: row; flex-wrap: wrap; }
      .nav a { flex: 1 1 140px; }
      .actions { grid-template-columns: 1fr; }
      .tile { min-height: 150px; }
    }

    @media (max-width: 480px) {
      .nav a { flex-basis: 100%; }
      .welcome { flex-direction: column; text-align: center; }
    }

    @media (prefers-reduced-motion: reduce) {
      .hero-lg { animation: none; }
      .nav a, .tile { transition: none; }
    }
  </style>
</head>
<body>
<?php require __DIR__ . '/../partials/scene.php'; ?>

  <div class="app">
    <aside class="side">
      <div class="profile">
        <div class="avatar" aria-hidden="true"><i></i></div>
        <div class="profile-name"><?= htmlspecialchars($name) ?></div>
        <div class="profile-role">Student &middot; Level <?= (int)$level ?></div>
      </div>

      <nav class="nav" aria-label="Dashboard menu">
        <a href="/dashboard" class="active" aria-current="page"><span class="icon" aria-hidden="true">🏠</span>Dashboard</a>
        <a href="/documents"><span class="icon" aria-hidden="true">📄</span>My Documents</a>
        <a href="/scores"><span class="icon" aria-hidden="true">🏆</span>Scores</a>
        <a href="/announcements"><span class="icon" aria-hidden="true">📢</span>Announcements</a>
        <a href="/settings"><span class="icon" aria-hidden="true">⚙️</span>Settings</a>
        <a href="/logout" class="logout"><span class="icon" aria-hidden="true">🚪</span>Logout</a>
      </nav>
    </aside>

    <main class="main">
      <section class="welcome">
        <div class="hero-box" aria-hidden="true"><span class="hero-lg"></span></div>
        <div>
          <h1>Welcome back, <?= htmlspecialchars($first) ?>!</h1>
          <p>Keep studying and reach new heights!</p>
        </div>
      </section>

      <h2 class="section-title">Quick Actions</h2>
      <div class="actions">
        <a href="/documents" class="tile tile-upload">
          <span class="big" aria-hidden="true">📄<span class="plus">+</span></span>
          <strong>Upload</strong>
          <span class="sub">Reviewer (PDF)</span>
        </a>
        <a href="/play" class="tile tile-play">
          <span class="big" aria-hidden="true">⚔️</span>
          <strong>Start Adventure</strong>
        </a>
      </div>

      <h2 class="section-title">Latest Announcement</h2>
      <div class="announce">
        <span class="icon" aria-hidden="true">📢</span>
        <span>No new announcements.</span>
      </div>
    </main>
  </div>

</body>
</html>