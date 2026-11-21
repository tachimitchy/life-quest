<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=VT323&display=swap" rel="stylesheet" />
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  [hidden] { display: none !important; }

  :root {
    --night: #0b1026; --night-2: #1b2150;
    --gold: #ffd54a; --gold-dark: #c47f00;
    --grass: #4caf3d; --grass-dark: #2d7a24;
    --dirt: #7a4a24; --dirt-dark: #55311a;
    --stone: #8b93a7; --stone-dark: #4b5266;
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
    width: 100%; max-width: 440px; padding: 2.25rem 2rem 2rem;
    background: #12183a;
    border: 4px solid var(--stone);
    box-shadow: 0 0 0 4px var(--stone-dark), 8px 8px 0 4px rgba(0, 0, 0, 0.45);
  }

  .header { margin-bottom: 1.75rem; text-align: center; }
  .header h1 {
    font-family: "Press Start 2P", "Courier New", monospace;
    font-size: clamp(1.2rem, 6vw, 1.6rem);
    line-height: 1.4;
    color: var(--gold);
    text-shadow: 3px 3px 0 var(--gold-dark), 6px 6px 0 rgba(0, 0, 0, 0.5);
  }
  .header p { margin-top: 1.25rem; font-size: 1.4rem; color: #8e9ad0; }

  .form-group { margin-bottom: 1.1rem; }
  label { display: block; margin-bottom: 0.35rem; font-size: 1.35rem; color: #dbe3ff; }

  input[type="email"], input[type="password"] {
    width: 100%; padding: 0.6rem 0.85rem;
    font-family: inherit; font-size: 1.4rem; color: #fff;
    background: #0b1026; border: 3px solid var(--stone-dark); outline: none;
  }
  input:focus { border-color: var(--gold); box-shadow: 0 0 0 3px rgba(255, 213, 74, 0.25); }

  .btn-submit {
    width: 100%; margin-top: 0.5rem; padding: 0.9rem 1rem;
    font-family: "Press Start 2P", "Courier New", monospace; font-size: 0.85rem;
    color: #fff; background: var(--grass); border: 4px solid #000;
    box-shadow: inset -4px -4px 0 var(--grass-dark), inset 4px 4px 0 #8be07a;
    text-shadow: 2px 2px 0 rgba(0, 0, 0, 0.5);
    cursor: pointer; transition: transform 0.08s steps(2);
  }
  .btn-submit:hover { transform: translateY(-3px); }
  .btn-submit:active { transform: translateY(2px); }
  .btn-submit:focus-visible { outline: 3px dashed var(--gold); outline-offset: 4px; }

  .alert { margin-bottom: 1.25rem; padding: 0.75rem 1rem; font-size: 1.3rem; border: 3px solid; }
  .alert a { color: var(--gold); }
  .alert-error { color: #ffd0d0; background: #3a1220; border-color: #e5484d; }
  .alert-success { color: #d4ffd0; background: #11331a; border-color: var(--grass); }

  .footer { margin-top: 1.75rem; text-align: center; font-size: 1.35rem; color: #8e9ad0; }
  .footer a { color: var(--gold); font-weight: 700; text-decoration: none; }
  .footer a:hover { text-decoration: underline; }

  @media (prefers-reduced-motion: reduce) { .btn-submit { transition: none; } }

    .logo { display: block; width: min(300px, 80%); height: auto; margin: 0 auto; image-rendering: auto; filter: drop-shadow(0 6px 0 rgba(0, 0, 0, 0.35)); }
    .logo-sm { width: min(150px, 45%); margin-bottom: 0.9rem; }
    .title .logo { width: min(340px, 90%); }
    .header h1 { line-height: 1; }
  </style>