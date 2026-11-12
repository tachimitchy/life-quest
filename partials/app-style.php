<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=VT323&display=swap" rel="stylesheet" />
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  :root { --gold: #ffd54a; --gold-dark: #c47f00; --grass: #4caf3d; --grass-dark: #2d7a24;
    --stone: #8b93a7; --stone-dark: #4b5266; --red: #e5484d; --red-dark: #9c2a2e; }
  button { font: inherit; color: inherit; }
  body { min-height: 100vh; display: flex; align-items: center; justify-content: center;
    padding: 1.25rem 1.25rem calc(max(14vh, 64px) + 1.25rem);
    font-family: "VT323", "Courier New", monospace; background: #2b2a5a; color: #dbe3ff; }

  .app { display: grid; grid-template-columns: 250px 1fr; width: 100%; max-width: 980px;
    background: rgb(18 24 58 / var(--ui-alpha, 1)); border: 4px solid var(--stone);
    box-shadow: 0 0 0 4px var(--stone-dark), 8px 8px 0 4px rgba(0, 0, 0, 0.45); }

  /* sidebar */
  .side { padding: 1.5rem 1rem; background: rgb(14 19 48 / var(--ui-alpha, 1)); border-right: 4px solid var(--stone-dark); }
  .profile { text-align: center; margin-bottom: 1.5rem; }
  .avatar { position: relative; width: 84px; height: 84px; margin: 0 auto 0.8rem; background: #2b2a5a;
    border: 4px solid var(--gold); box-shadow: 0 0 0 3px #000; }
  .avatar .spr { position: absolute; left: 12px; top: 4px; font-size: 5px; }
  .profile-link { display: block; padding: 0.4rem; text-decoration: none; color: inherit; border: 3px solid transparent; }
  .profile-link:hover, .profile-link:focus-visible { border-color: var(--gold); outline: none; }
  .profile-link:hover .avatar { transform: translateY(-3px); }
  .profile-hint { display: block; margin-top: 0.3rem; font-size: 1.1rem; color: var(--gold); }
  .profile-name { font-family: "Press Start 2P", "Courier New", monospace; font-size: 0.75rem; line-height: 1.5; color: #fff; word-break: break-word; }
  .profile-role { margin-top: 0.4rem; font-size: 1.2rem; color: #8e9ad0; }
  .nav { display: flex; flex-direction: column; gap: 0.6rem; }
  .nav a { display: flex; align-items: center; gap: 0.7rem; padding: 0.6rem 0.75rem; font-size: 1.3rem; line-height: 1.1;
    min-width: 0; overflow-wrap: break-word; text-decoration: none; color: #dbe3ff; background: #1b2150; border: 3px solid #000;
    box-shadow: inset -3px -3px 0 #121741, inset 3px 3px 0 #2e377a; transition: transform 0.08s steps(2); }
  .nav a:hover, .nav a:focus-visible { transform: translateX(3px); outline: none; }
  .nav a:focus-visible { outline: 3px dashed var(--gold); outline-offset: 3px; }
  .nav .icon { width: 1.6rem; text-align: center; }
  .nav a.active { color: #fff; background: var(--grass); box-shadow: inset -3px -3px 0 var(--grass-dark), inset 3px 3px 0 #8be07a; text-shadow: 2px 2px 0 rgba(0, 0, 0, 0.5); }
  .nav a.logout { margin-top: 0.6rem; background: var(--red); color: #fff; box-shadow: inset -3px -3px 0 var(--red-dark), inset 3px 3px 0 #ff8f93; }

  /* main area */
  .main { padding: 1.25rem; min-width: 0; }
  .page-title { margin-bottom: 0.9rem; font-family: "Press Start 2P", "Courier New", monospace; font-size: clamp(0.9rem, 2.6vw, 1.1rem);
    line-height: 1.5; color: var(--gold); text-shadow: 3px 3px 0 var(--gold-dark), 5px 5px 0 rgba(0, 0, 0, 0.5); }
  .lead { margin-bottom: 1.25rem; font-size: 1.5rem; color: #8e9ad0; }
  .section-title { margin-bottom: 0.9rem; font-family: "Press Start 2P", "Courier New", monospace; font-size: 0.8rem; color: #fff; }

  .welcome { display: flex; align-items: center; gap: 1rem; padding: 1rem 1.25rem; margin-bottom: 1.25rem;
    background: linear-gradient(90deg, #232a6b, #1b2150); border: 3px solid var(--gold); box-shadow: inset 0 0 0 3px #000; }
  .hero-box { position: relative; flex: none; width: 66px; height: 92px; }
  .hero-box .spr { position: absolute; left: 3px; top: 6px; font-size: 6px; animation: bob 1s steps(2) infinite; }
  .welcome h1 { font-family: "Press Start 2P", "Courier New", monospace; font-size: clamp(0.9rem, 2.6vw, 1.2rem); line-height: 1.5;
    color: var(--gold); text-shadow: 3px 3px 0 var(--gold-dark), 5px 5px 0 rgba(0, 0, 0, 0.5); }
  .welcome p { margin-top: 0.5rem; font-size: 1.6rem; color: #dbe3ff; }

  .alert { margin-bottom: 1.25rem; padding: 0.7rem 1rem; font-size: 1.35rem; border: 3px solid; }
  .alert-error { color: #ffd0d0; background: #3a1220; border-color: var(--red); }
  .alert-success { color: #d4ffd0; background: #11331a; border-color: var(--grass); }

  .actions { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem; }
  .actions form { display: flex; }
  .tile { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.5rem; width: 100%;
    min-height: 150px; padding: 0.9rem; text-align: center; text-decoration: none; color: #fff; cursor: pointer;
    background: #1b2150; border: 4px solid #000; transition: transform 0.08s steps(2); }
  .tile:hover, .tile:focus-visible { transform: translateY(-4px); outline: none; }
  .tile:focus-visible { outline: 3px dashed var(--gold); outline-offset: 4px; }
  .tile .big { position: relative; font-size: 3.6rem; line-height: 1; }
  .tile .plus { position: absolute; right: -0.55rem; bottom: -0.2rem; width: 1.3rem; height: 1.3rem; font-size: 1rem; line-height: 1.3rem;
    background: var(--grass); border: 2px solid #000; font-family: "Press Start 2P", monospace; }
  .tile strong { font-family: "Press Start 2P", "Courier New", monospace; font-size: 0.8rem; line-height: 1.5; text-shadow: 2px 2px 0 rgba(0, 0, 0, 0.5); }
  .tile .sub { font-size: 1.35rem; color: #c3c9ee; }
  .tile-upload { box-shadow: inset -4px -4px 0 var(--grass-dark), inset 4px 4px 0 #3c8a30, 0 0 0 3px var(--grass); }
  .tile-play { box-shadow: inset -4px -4px 0 var(--gold-dark), inset 4px 4px 0 #a07a00, 0 0 0 3px var(--gold); }

  .announce { display: flex; align-items: center; gap: 1rem; padding: 1rem 1.25rem; font-size: 1.5rem; background: rgb(11 16 38 / var(--ui-alpha, 1)); border: 3px solid var(--stone-dark); }
  .announce .icon { font-size: 2.2rem; }
  .announce small { font-size: 1.15rem; color: #8e9ad0; }
  .announce a { color: var(--gold); }

  /* pixel buttons */
  .px { display: inline-block; padding: 0.7rem 1rem; font-family: "Press Start 2P", "Courier New", monospace; font-size: 0.65rem; line-height: 1.4;
    color: #fff; text-decoration: none; text-align: center; cursor: pointer; border: 4px solid #000;
    text-shadow: 2px 2px 0 rgba(0, 0, 0, 0.5); transition: transform 0.08s steps(2); }
  .px:hover, .px:focus-visible { transform: translateY(-2px); outline: none; }
  .px:focus-visible { outline: 3px dashed var(--gold); outline-offset: 3px; }
  .px-green { background: var(--grass); box-shadow: inset -3px -3px 0 var(--grass-dark), inset 3px 3px 0 #8be07a; }
  .px-gold { background: #e0a800; box-shadow: inset -3px -3px 0 var(--gold-dark), inset 3px 3px 0 #ffe27a; }
  .px-stone { background: var(--stone); box-shadow: inset -3px -3px 0 var(--stone-dark), inset 3px 3px 0 #c3c9d8; }
  .px-red { background: var(--red); box-shadow: inset -3px -3px 0 var(--red-dark), inset 3px 3px 0 #ff8f93; }

  /* forms and panels */
  .panel { padding: 1rem 1.25rem; margin-bottom: 1.25rem; background: rgb(11 16 38 / var(--ui-alpha, 1)); border: 3px solid var(--stone-dark); }
  .panel h2 { margin-bottom: 0.8rem; font-family: "Press Start 2P", "Courier New", monospace; font-size: 0.75rem; color: #fff; }
  .panel p { font-size: 1.4rem; margin-bottom: 0.3rem; }
  .field { margin-bottom: 1rem; }
  .field label { display: block; margin-bottom: 0.35rem; font-size: 1.35rem; color: #dbe3ff; }
  input[type="text"], textarea, select { width: 100%; padding: 0.6rem 0.85rem; font: inherit; font-size: 1.4rem; color: #fff;
    background: #12183a; border: 3px solid var(--stone-dark); outline: none; }
  textarea { min-height: 110px; resize: vertical; }
  input:focus, textarea:focus, select:focus { border-color: var(--gold); box-shadow: 0 0 0 3px rgba(255, 213, 74, 0.25); }
  input[type="file"] { max-width: 100%; font: inherit; font-size: 1.3rem; color: #dbe3ff; }

  /* documents and announcements */
  .doc { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.8rem; padding: 0.85rem 1rem; margin-bottom: 0.75rem;
    background: rgb(11 16 38 / var(--ui-alpha, 1)); border: 3px solid var(--stone-dark); border-left: 10px solid var(--grass); }
  .doc h3, .ann h3 { font-size: 1.55rem; font-weight: 400; color: #fff; word-break: break-word; }
  .doc small { display: block; font-size: 1.2rem; color: #8e9ad0; }
  .doc .btns, .btns-row { display: flex; flex-wrap: wrap; gap: 0.5rem; }
  .doc form, .ann form { display: inline; }
  .ann { padding: 0.9rem 1.1rem; margin-bottom: 0.9rem; background: rgb(11 16 38 / var(--ui-alpha, 1)); border: 3px solid var(--stone-dark); border-left: 10px solid var(--stone); }
  .ann.update { border-left-color: var(--gold); }
  .ann p { margin-top: 0.4rem; font-size: 1.4rem; white-space: pre-wrap; word-break: break-word; }
  .ann .meta { display: flex; flex-wrap: wrap; align-items: center; gap: 0.6rem; margin-top: 0.6rem; font-size: 1.15rem; color: #8e9ad0; }
  .badge { display: inline-block; padding: 0 0.5rem; font-size: 1.1rem; color: #000; background: var(--gold); border: 2px solid #000; }
  .empty { padding: 1.75rem 1rem; text-align: center; font-size: 1.5rem; color: #8e9ad0; }
  .empty .big { display: block; margin-bottom: 0.5rem; font-size: 3rem; }

  /* scores */
  .stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.25rem; }
  .stat { padding: 1rem 0.5rem; text-align: center; font-size: 1.3rem; background: #1b2150; border: 3px solid #000;
    box-shadow: inset -3px -3px 0 #121741, inset 3px 3px 0 #2e377a; }
  .stat b { display: block; margin-bottom: 0.4rem; font-family: "Press Start 2P", "Courier New", monospace; font-size: 1.1rem; font-weight: 400; color: var(--gold); }
  .table-wrap { overflow-x: auto; }
  table.scores { width: 100%; border-collapse: collapse; font-size: 1.35rem; }
  table.scores th, table.scores td { padding: 0.55rem 0.6rem; text-align: left; border-bottom: 3px solid #1b2150; }
  table.scores th { font-family: "Press Start 2P", "Courier New", monospace; font-size: 0.55rem; font-weight: 400; color: var(--gold); }

  /* settings */
  .theme-options { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem; }
  .theme-option { position: relative; cursor: pointer; }
  .theme-option input { position: absolute; inset: 0; opacity: 0; cursor: pointer; }
  .theme-option span { display: flex; flex-direction: column; align-items: center; gap: 0.5rem; padding: 1.2rem 0.5rem;
    font-family: "Press Start 2P", "Courier New", monospace; font-size: 0.7rem; color: #c3c9d8; background: var(--stone-dark);
    border: 4px solid #000; box-shadow: inset -4px -4px 0 #343a4c, inset 4px 4px 0 #6b7388; }
  .theme-option em { font-style: normal; font-size: 2.4rem; }
  .theme-option input:checked + span { color: #fff; background: var(--grass); box-shadow: inset -4px -4px 0 var(--grass-dark), inset 4px 4px 0 #8be07a; }
  .theme-option input:focus-visible + span { outline: 3px dashed var(--gold); outline-offset: 4px; }

  /* pop-up */
  dialog.pxdialog { margin: auto; width: calc(100% - 2rem); max-width: 420px; padding: 1.5rem; text-align: center; color: #dbe3ff;
    background: #12183a; border: 4px solid var(--stone); box-shadow: 0 0 0 4px var(--stone-dark), 8px 8px 0 4px rgba(0, 0, 0, 0.5); }
  dialog.pxdialog::backdrop { background: rgba(5, 7, 26, 0.7); }
  dialog.pxdialog h2 { margin-bottom: 0.9rem; font-family: "Press Start 2P", "Courier New", monospace; font-size: 0.85rem; line-height: 1.6; color: var(--gold); }
  dialog.pxdialog p { margin-bottom: 1.25rem; font-size: 1.5rem; }
  dialog.pxdialog .btns { display: flex; flex-wrap: wrap; justify-content: center; gap: 0.75rem; }


  /* page header with a code box on the right */
  .page-head { display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 0.4rem; }
  .join-form { display: flex; align-items: center; gap: 0.5rem; }
  .join-form input { width: 150px; text-transform: uppercase; letter-spacing: 0.12em; padding: 0.45rem 0.6rem; font: inherit; font-size: 1.4rem; color: #fff; background: #12183a; border: 3px solid var(--stone-dark); outline: none; }
  .join-form input:focus { border-color: var(--gold); }
  .join-form .px { padding: 0.6rem 0.8rem; }
  .code { display: inline-block; padding: 0.15rem 0.7rem; font-family: "Press Start 2P", "Courier New", monospace; font-size: 0.85rem; letter-spacing: 0.15em; color: #000; background: var(--gold); border: 3px solid #000; user-select: all; }

  /* profile */
  .kv { display: flex; flex-wrap: wrap; gap: 0.2rem 1rem; margin-bottom: 0.35rem; font-size: 1.45rem; }
  .kv b { min-width: 100px; font-weight: 400; color: var(--gold); }
  .profile-card { display: flex; align-items: center; gap: 1.25rem; flex-wrap: wrap; }
  .big-spr { position: relative; flex: none; width: 90px; height: 104px; background: #2b2a5a; border: 4px solid var(--gold); box-shadow: 0 0 0 3px #000; }
  .big-spr .spr { position: absolute; left: 11px; top: 11px; font-size: 6.5px; }
  .xp { height: 16px; margin: 0.3rem 0 0.2rem; background: #1b2150; border: 3px solid #000; }
  .xp i { display: block; height: 100%; background: var(--grass); }
  .chars { display: grid; grid-template-columns: repeat(auto-fill, minmax(135px, 1fr)); gap: 0.9rem; margin-bottom: 1.25rem; }
  .char { position: relative; padding: 0.7rem 0.5rem; text-align: center; font-size: 1.3rem; background: rgb(11 16 38 / var(--ui-alpha, 1)); border: 3px solid var(--stone-dark); }
  .char.current { border-color: var(--gold); }
  .char .face { position: relative; width: 70px; height: 84px; margin: 0 auto 0.4rem; }
  .char .face .spr { position: absolute; left: 5px; top: 3px; font-size: 5.5px; }
  .char.locked .face { filter: grayscale(1) brightness(0.45); }
  .char .lock { position: absolute; top: 0.3rem; right: 0.4rem; font-size: 1.5rem; }
  .char small { display: block; margin: 0.15rem 0 0.5rem; font-size: 1.15rem; color: #8e9ad0; }
  .char .px { padding: 0.45rem 0.6rem; font-size: 0.55rem; }
  .achs { display: grid; grid-template-columns: repeat(auto-fill, minmax(165px, 1fr)); gap: 0.8rem; margin-bottom: 1.25rem; }
  .ach { padding: 0.7rem; text-align: center; font-size: 1.25rem; background: rgb(11 16 38 / var(--ui-alpha, 1)); border: 3px solid var(--stone-dark); }
  .ach .ico { display: block; margin-bottom: 0.2rem; font-size: 2rem; }
  .ach b { display: block; font-weight: 400; color: #fff; }
  .ach.earned { border-color: var(--gold); }
  .ach:not(.earned) { opacity: 0.5; filter: grayscale(1); }

  /* settings sliders and classes */
  input[type="range"] { width: 100%; accent-color: var(--grass); }
  .row { display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; margin-bottom: 0.8rem; font-size: 1.4rem; }
  .row label.check { display: flex; align-items: center; gap: 0.5rem; cursor: pointer; }
  .row label.check input { width: 1.2rem; height: 1.2rem; accent-color: var(--grass); }
  .class-head { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.8rem; margin-bottom: 0.8rem; }
  .class-head h2 { margin: 0; }
  .inline { display: flex; gap: 0.6rem; align-items: flex-end; flex-wrap: wrap; }
  .inline .field { flex: 1 1 180px; margin-bottom: 0; }

  /* when the panels are see-through, keep the writing easy to read */
  .ui-clear .app :is(p, label, small, td, th, legend, .lead, .kv, .row, .announce span, .doc h3, .ann h3, .char, .ach) { text-shadow: 0 1px 3px #000, 0 0 6px #000; }

  @media (max-width: 760px) {
    .app { grid-template-columns: 1fr; }
    .side { border-right: 0; border-bottom: 4px solid var(--stone-dark); }
    .nav { flex-direction: row; flex-wrap: wrap; }
    .nav a { flex: 1 1 140px; }
    .actions { grid-template-columns: 1fr; }
    .tile { min-height: 130px; }
  }
  @media (max-width: 480px) {
    .nav a { flex-basis: 100%; }
    .welcome { flex-direction: column; text-align: center; }
    .stats { grid-template-columns: 1fr; }
  }
  @media (prefers-reduced-motion: reduce) {
    .hero-box .spr { animation: none; }
    .nav a, .tile, .px { transition: none; }
  }
</style>