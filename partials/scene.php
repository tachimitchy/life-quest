<style>
  /* ===== Pixel adventure scene (pure CSS, no images) ===== */
  .scene { position: fixed; inset: 0; z-index: -1; overflow: hidden; image-rendering: pixelated;
    background: linear-gradient(180deg, #8d8fd6 0%, #b7a3dd 38%, #f0b9a4 66%, #f8d3a0 82%); }
  .scene * { position: absolute; }

  .sun { left: 82%; bottom: 38%; width: 64px; height: 64px; border-radius: 50%; background: #fff1be;
    box-shadow: 0 0 0 10px rgba(255, 241, 190, 0.35), 0 0 0 22px rgba(255, 241, 190, 0.18); }

  .cloud { height: 22px; background: #f6c4b6; }
  .cloud::before, .cloud::after { content: ""; position: absolute; background: inherit; }
  .cloud::before { left: 16%; top: -14px; width: 42%; height: 14px; }
  .cloud::after { left: 46%; top: -26px; width: 28%; height: 26px; }
  .cl1 { top: 14%; width: 170px; }
  .cl2 { top: 24%; width: 210px; background: #f7b9a0; }
  .cl3 { top: 8%; width: 120px; background: #cdbff0; }

  .m-far { left: 0; right: 0; bottom: 22%; height: 34%; background: #8b8fc4;
    clip-path: polygon(0 100%, 0 62%, 8% 62%, 14% 45%, 20% 45%, 27% 60%, 36% 38%, 40% 38%, 48% 58%, 56% 50%, 62% 50%, 70% 66%, 78% 42%, 84% 42%, 92% 58%, 100% 46%, 100% 100%); }
  .m-near { left: 50%; width: 46%; bottom: 20%; height: 52%;
    background: linear-gradient(105deg, #a2a4be 0 48%, #6c6e8e 48%);
    clip-path: polygon(0 100%, 18% 52%, 24% 52%, 30% 30%, 36% 30%, 42% 8%, 48% 8%, 56% 34%, 62% 34%, 68% 22%, 74% 22%, 84% 60%, 100% 100%); }
  .m-hill { left: 0; right: 0; bottom: 14%; height: 12%; background: #4f8f3c;
    clip-path: polygon(0 100%, 0 50%, 25% 20%, 55% 45%, 80% 10%, 100% 40%, 100% 100%); }

  .forest { left: 0; top: 0; bottom: 14%; width: 30%; background: linear-gradient(90deg, #0d2a1a, #17402a 70%, transparent); }
  .tr1 { left: 4%; top: 28%; bottom: 14%; width: 6%; min-width: 34px; background: linear-gradient(90deg, #35210f 0 30%, #5a3a1c 30% 70%, #35210f 70%); }
  .tr2 { left: 17%; top: 26%; bottom: 14%; width: 4%; min-width: 24px; background: linear-gradient(90deg, #35210f 0 30%, #5a3a1c 30% 70%, #35210f 70%); }
  .cn { background: linear-gradient(#2c7d3a 0 30%, #1b5a2b 30% 70%, #123f1e 70%);
    clip-path: polygon(20% 0, 80% 0, 80% 12%, 100% 12%, 100% 85%, 80% 85%, 80% 100%, 20% 100%, 20% 88%, 0 88%, 0 12%, 20% 12%); }
  .cn1 { left: -4%; top: -4%; width: 20%; height: 36%; }
  .cn2 { left: 12%; top: 2%; width: 15%; height: 28%; }

  .pine { bottom: 14%; width: clamp(30px, 5vw, 70px); height: clamp(80px, 22vh, 200px);
    background: linear-gradient(90deg, #2b8240 0 50%, #1b5a2b 50%);
    clip-path: polygon(50% 0, 66% 24%, 58% 24%, 80% 54%, 68% 54%, 92% 100%, 8% 100%, 32% 54%, 20% 54%, 42% 24%, 34% 24%); }
  .p1 { left: 21%; } .p2 { left: 26%; height: clamp(110px, 30vh, 260px); } .p3 { right: 28%; } .p4 { right: 24%; height: clamp(60px, 16vh, 150px); }

  .castle { right: 4vw; bottom: 18%; width: 150px; height: 96px; }
  .c-wall { left: 0; bottom: 0; width: 150px; height: 34px; background: #a3a2b0; }
  .c-wall::before { content: ""; position: absolute; left: 0; right: 0; top: -8px; height: 8px;
    background: repeating-linear-gradient(90deg, #a3a2b0 0 10px, transparent 10px 18px); }
  .c-t { bottom: 0; background: #b4b3c0; }
  .c-t::before { content: ""; position: absolute; left: -5px; right: -5px; top: -22px; height: 22px; background: #d2582a;
    clip-path: polygon(50% 0, 100% 100%, 0 100%); }
  .ct1 { left: 12px; width: 28px; height: 70px; }
  .ct2 { left: 60px; width: 34px; height: 92px; }
  .ct3 { left: 112px; width: 26px; height: 62px; }

  .ground { left: 0; right: 0; bottom: 0; height: 14%; min-height: 64px; border-top: 14px solid #4caf3d;
    box-shadow: inset 0 6px 0 #2d7a24;
    background: repeating-linear-gradient(90deg, transparent 0 28px, #55311a 28px 32px),
                repeating-linear-gradient(0deg, transparent 0 28px, #55311a 28px 32px), #7a4a24; }

  /* ===== LIFE QUEST title (pure CSS) ===== */
  .lq-title { display: block; text-align: center; font-family: "Press Start 2P", "Courier New", monospace;
    text-transform: uppercase; line-height: 1.1; }
  .lq-title span { display: block; -webkit-background-clip: text; background-clip: text; color: transparent;
    text-shadow: none; letter-spacing: 0.02em; }
  .lq-life { font-size: clamp(2.2rem, 11vw, 3.6rem); background-image: linear-gradient(180deg, #ffffff 0%, #b8c8f0 100%);
    filter: drop-shadow(3px 0 0 #1d2f6f) drop-shadow(-3px 0 0 #1d2f6f) drop-shadow(0 3px 0 #1d2f6f) drop-shadow(0 -3px 0 #1d2f6f) drop-shadow(0 7px 0 #000); }
  .lq-quest { margin-top: 0.4rem; font-size: clamp(2.4rem, 12vw, 3.9rem); background-image: linear-gradient(180deg, #ffe84d 0%, #ff9a1f 100%);
    filter: drop-shadow(3px 0 0 #3a1500) drop-shadow(-3px 0 0 #3a1500) drop-shadow(0 3px 0 #3a1500) drop-shadow(0 -3px 0 #3a1500) drop-shadow(0 7px 0 #000); }
  .lq-sm { margin-bottom: 0.9rem; }
  .lq-sm span { display: inline; margin: 0; font-size: 1.05rem; }
  .lq-sm .lq-life, .lq-sm .lq-quest { filter: drop-shadow(2px 0 0 #1d2f6f) drop-shadow(-2px 0 0 #1d2f6f) drop-shadow(0 2px 0 #1d2f6f) drop-shadow(0 -2px 0 #1d2f6f) drop-shadow(0 4px 0 #000); }
  .lq-sm .lq-quest { filter: drop-shadow(2px 0 0 #3a1500) drop-shadow(-2px 0 0 #3a1500) drop-shadow(0 2px 0 #3a1500) drop-shadow(0 -2px 0 #3a1500) drop-shadow(0 4px 0 #000); }

  /* ===== extra life: cloud drift, birds, fireflies, lamp, bushes, flowers ===== */
  .scene { --g: max(14%, 64px); }
  @keyframes drift { to { transform: translateX(calc(100vw + 480px)); } }
  .cl1 { left: -240px; animation: drift 95s linear infinite; animation-delay: -30s; }
  .cl2 { left: -240px; right: auto; animation: drift 70s linear infinite; animation-delay: -55s; }
  .cl3 { left: -240px; animation: drift 120s linear infinite; animation-delay: -90s; }

  .bird { left: -30px; width: 16px; height: 8px; animation: fly 32s linear infinite; }
  .bird::before, .bird::after { content: ""; position: absolute; top: 0; width: 8px; height: 3px; background: #3b2f5a; }
  .bird::before { left: 0; transform-origin: right center; animation: flapL 0.6s steps(1) infinite; }
  .bird::after { left: 8px; transform-origin: left center; animation: flapR 0.6s steps(1) infinite; }
  .b1 { top: 16%; } .b2 { top: 27%; animation-delay: -14s; transform: scale(0.8); }
  @keyframes fly { 0% { transform: translate(0, 0); } 50% { transform: translate(55vw, -24px); } 100% { transform: translate(calc(100vw + 60px), 8px); } }
  @keyframes flapL { 0% { transform: rotate(28deg); } 50% { transform: rotate(-24deg); } }
  @keyframes flapR { 0% { transform: rotate(-28deg); } 50% { transform: rotate(24deg); } }

  .ff { width: 5px; height: 5px; background: #fff3a0; box-shadow: 0 0 6px 2px rgba(255, 243, 160, 0.6); animation: blink 2.6s steps(2) infinite; }
  @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0.15; } }

  .lamp { left: 27.5%; bottom: var(--g); width: 6px; height: 100px; background: #3a2412; }
  .lamp::before { content: ""; position: absolute; left: -9px; top: -6px; width: 24px; height: 22px; background: #ffd25a;
    border: 3px solid #3a2412; box-shadow: 0 0 18px 6px rgba(255, 210, 90, 0.55); animation: glow 3s steps(3) infinite; }
  @keyframes glow { 50% { box-shadow: 0 0 26px 10px rgba(255, 210, 90, 0.75); } }

  .bush { bottom: var(--g); width: 74px; height: 34px; background: linear-gradient(#4fc060 0 35%, #2f8f43 35%);
    clip-path: polygon(0 100%, 0 55%, 12% 55%, 12% 30%, 30% 30%, 30% 8%, 70% 8%, 70% 30%, 88% 30%, 88% 55%, 100% 55%, 100% 100%); }
  .bu1 { left: 29%; } .bu2 { left: 66%; width: 60px; } .bu3 { left: 91%; } .bu4 { left: 2%; width: 90px; height: 40px; background: linear-gradient(#2f8f43 0 35%, #1f6b34 35%); }

  .rt-c { bottom: calc(var(--g) + 26px); width: 74px; height: 64px; background: linear-gradient(#52c063 0 30%, #2f8f43 30% 70%, #1f6b34 70%);
    clip-path: polygon(20% 0, 80% 0, 80% 12%, 100% 12%, 100% 85%, 80% 85%, 80% 100%, 20% 100%, 20% 88%, 0 88%, 0 12%, 20% 12%); }
  .rt-t { bottom: var(--g); width: 14px; height: 30px; background: linear-gradient(90deg, #4a2f1b 0 55%, #35210f 55%); }
  .rt1c { right: 10%; } .rt1t { right: calc(10% + 30px); }
  .rt2c { left: 60%; width: 56px; height: 50px; } .rt2t { left: calc(60% + 21px); height: 26px; }

  .flw { left: 0; bottom: var(--g); width: 6px; height: 6px; box-shadow:
    6vw -6px #ff5c8a, 6vw 0 #2f8f43, 11vw -6px #ffd54a, 11vw 0 #2f8f43, 19vw -6px #ffffff, 19vw 0 #2f8f43, 29vw -6px #ff8a3d, 29vw 0 #2f8f43, 34vw -6px #ff5c8a, 34vw 0 #2f8f43, 41vw -6px #ffd54a, 41vw 0 #2f8f43, 57vw -6px #ffffff, 57vw 0 #2f8f43, 63vw -6px #ff8a3d, 63vw 0 #2f8f43, 71vw -6px #ff5c8a, 71vw 0 #2f8f43, 77vw -6px #ffd54a, 77vw 0 #2f8f43, 86vw -6px #ffffff, 86vw 0 #2f8f43, 93vw -6px #ff8a3d, 93vw 0 #2f8f43; }

  /* walking hero (pixel sprite, 2 frames) */
  .walker { bottom: calc(var(--g) + 12em - 2px); left: -80px; width: 1em; height: 1em; font-size: 5px;
    animation: stroll 28s linear infinite, step 0.5s steps(1) infinite; }
  @keyframes stroll { to { left: calc(100% + 80px); } }
  @keyframes step {
    0% { box-shadow: 2em 0em #6b3e1e, 3em 0em #6b3e1e, 4em 0em #6b3e1e, 5em 0em #6b3e1e, 6em 0em #6b3e1e, 1em 1em #6b3e1e, 2em 1em #6b3e1e, 3em 1em #6b3e1e, 4em 1em #6b3e1e, 5em 1em #6b3e1e, 6em 1em #6b3e1e, 7em 1em #6b3e1e, 1em 2em #6b3e1e, 2em 2em #6b3e1e, 3em 2em #6b3e1e, 4em 2em #f2c29b, 5em 2em #f2c29b, 6em 2em #f2c29b, 7em 2em #f2c29b, 1em 3em #6b3e1e, 2em 3em #6b3e1e, 3em 3em #f2c29b, 4em 3em #f2c29b, 5em 3em #f2c29b, 6em 3em #111111, 7em 3em #f2c29b, 2em 4em #f2c29b, 3em 4em #f2c29b, 4em 4em #f2c29b, 5em 4em #f2c29b, 6em 4em #f2c29b, 7em 4em #f2c29b, 0em 5em #d6342c, 1em 5em #d6342c, 2em 5em #d6342c, 3em 5em #d6342c, 4em 5em #d6342c, 5em 5em #d6342c, 6em 5em #d6342c, 7em 5em #d6342c, 2em 6em #3b63c9, 3em 6em #3b63c9, 4em 6em #3b63c9, 5em 6em #3b63c9, 6em 6em #3b63c9, 7em 6em #3b63c9, 1em 7em #f2c29b, 2em 7em #3b63c9, 3em 7em #3b63c9, 4em 7em #3b63c9, 5em 7em #3b63c9, 6em 7em #3b63c9, 7em 7em #3b63c9, 8em 7em #f2c29b, 2em 8em #3b63c9, 3em 8em #3b63c9, 4em 8em #3b63c9, 5em 8em #3b63c9, 6em 8em #3b63c9, 7em 8em #3b63c9, 2em 9em #2b3a67, 3em 9em #2b3a67, 4em 9em #2b3a67, 5em 9em #2b3a67, 6em 9em #2b3a67, 7em 9em #2b3a67, 1em 10em #2b3a67, 2em 10em #2b3a67, 7em 10em #2b3a67, 8em 10em #2b3a67, 1em 11em #2b3a67, 2em 11em #2b3a67, 7em 11em #2b3a67, 8em 11em #2b3a67, 1em 12em #3a2412, 2em 12em #3a2412, 7em 12em #3a2412, 8em 12em #3a2412; transform: translateY(0); }
    50% { box-shadow: 2em 0em #6b3e1e, 3em 0em #6b3e1e, 4em 0em #6b3e1e, 5em 0em #6b3e1e, 6em 0em #6b3e1e, 1em 1em #6b3e1e, 2em 1em #6b3e1e, 3em 1em #6b3e1e, 4em 1em #6b3e1e, 5em 1em #6b3e1e, 6em 1em #6b3e1e, 7em 1em #6b3e1e, 1em 2em #6b3e1e, 2em 2em #6b3e1e, 3em 2em #6b3e1e, 4em 2em #f2c29b, 5em 2em #f2c29b, 6em 2em #f2c29b, 7em 2em #f2c29b, 1em 3em #6b3e1e, 2em 3em #6b3e1e, 3em 3em #f2c29b, 4em 3em #f2c29b, 5em 3em #f2c29b, 6em 3em #111111, 7em 3em #f2c29b, 2em 4em #f2c29b, 3em 4em #f2c29b, 4em 4em #f2c29b, 5em 4em #f2c29b, 6em 4em #f2c29b, 7em 4em #f2c29b, 1em 5em #d6342c, 2em 5em #d6342c, 3em 5em #d6342c, 4em 5em #d6342c, 5em 5em #d6342c, 6em 5em #d6342c, 7em 5em #d6342c, 0em 6em #d6342c, 1em 6em #d6342c, 2em 6em #3b63c9, 3em 6em #3b63c9, 4em 6em #3b63c9, 5em 6em #3b63c9, 6em 6em #3b63c9, 7em 6em #3b63c9, 1em 7em #f2c29b, 2em 7em #3b63c9, 3em 7em #3b63c9, 4em 7em #3b63c9, 5em 7em #3b63c9, 6em 7em #3b63c9, 7em 7em #3b63c9, 8em 7em #f2c29b, 2em 8em #3b63c9, 3em 8em #3b63c9, 4em 8em #3b63c9, 5em 8em #3b63c9, 6em 8em #3b63c9, 7em 8em #3b63c9, 2em 9em #2b3a67, 3em 9em #2b3a67, 4em 9em #2b3a67, 5em 9em #2b3a67, 6em 9em #2b3a67, 7em 9em #2b3a67, 2em 10em #2b3a67, 3em 10em #2b3a67, 4em 10em #2b3a67, 5em 10em #2b3a67, 6em 10em #2b3a67, 7em 10em #2b3a67, 3em 11em #2b3a67, 4em 11em #2b3a67, 5em 11em #2b3a67, 6em 11em #2b3a67, 3em 12em #3a2412, 4em 12em #3a2412, 5em 12em #3a2412, 6em 12em #3a2412; transform: translateY(-1em); }
  }

  /* little slime hopping the other way */
  .slime { bottom: calc(var(--g) - 2px); right: -50px; width: 34px; height: 24px;
    background: radial-gradient(circle at 28% 30%, #b9e0ff 0 4px, transparent 5px), #4aa3ff;
    clip-path: polygon(0 100%, 0 60%, 10% 36%, 30% 14%, 70% 14%, 90% 36%, 100% 60%, 100% 100%);
    animation: crawl 46s linear infinite, hop 0.9s steps(3) infinite; }
  .slime::before { content: ""; position: absolute; left: 9px; top: 9px; width: 4px; height: 7px; background: #0b1d3d; box-shadow: 12px 0 #0b1d3d; }
  @keyframes crawl { to { right: calc(100% + 50px); } }
  @keyframes hop { 0%, 100% { transform: translateY(0) scale(1.1, 0.9); } 50% { transform: translateY(-16px) scale(0.92, 1.1); } }

  @media (prefers-reduced-motion: reduce) {
    .scene *, .scene *::before, .scene *::after, .lq-badge * { animation: none !important; }
    .walker { left: 74%; } .slime { right: 70%; }
  }

  /* ===== logo-style decoration above the big title ===== */
  .lq-badge { position: relative; display: block; width: 140px; height: 84px; margin: 12px auto 0.2rem; }
  .lq-paper { position: absolute; left: 36px; top: 8px; width: 52px; height: 66px; border: 4px solid #f2b800;
    background: radial-gradient(circle at 11px 14px, #fff6dc 0 2px, #1b1b1b 2.5px 4px, transparent 4.5px), linear-gradient(#26408b, #26408b) 22px 12px / 20px 3px no-repeat, radial-gradient(circle at 11px 27px, #fff6dc 0 2px, #1b1b1b 2.5px 4px, transparent 4.5px), linear-gradient(#26408b, #26408b) 22px 25px / 20px 3px no-repeat, radial-gradient(circle at 11px 40px, #fff6dc 0 2px, #1b1b1b 2.5px 4px, transparent 4.5px), linear-gradient(#26408b, #26408b) 22px 38px / 20px 3px no-repeat, radial-gradient(circle at 11px 53px, #fff6dc 0 2px, #1b1b1b 2.5px 4px, transparent 4.5px), linear-gradient(#26408b, #26408b) 22px 51px / 20px 3px no-repeat, #fff6dc; box-shadow: 0 0 0 3px #000, 4px 5px 0 3px rgba(0, 0, 0, 0.35); transform: rotate(-5deg); }
  .lq-pencil { position: absolute; right: 22px; top: 2px; width: 14px; height: 54px; border: 3px solid #000;
    background: linear-gradient(90deg, #ffd23a 0 50%, #e3a400 50%); transform: rotate(38deg); }
  .lq-pencil::before { content: ""; position: absolute; left: -3px; bottom: -18px; width: 14px; height: 14px;
    background: linear-gradient(#f3c89a 0 55%, #222 55%); clip-path: polygon(0 0, 100% 0, 50% 100%); }
  .lq-pencil::after { content: ""; position: absolute; left: -3px; top: -15px; width: 14px; height: 10px; background: #e0443a; border: 3px solid #000; }
  .lq-ray { position: absolute; width: 4px; height: 12px; background: #ffd23a; animation: blink 1.6s steps(2) infinite; }
  .lq-ray.r1 { left: 24px; top: -2px; transform: rotate(-38deg); }
  .lq-ray.r2 { left: 68px; top: -10px; animation-delay: -0.5s; }
  .lq-ray.r3 { left: 108px; top: -2px; transform: rotate(38deg); animation-delay: -1s; }
</style>

<div class="scene" aria-hidden="true">
  <div class="cloud cl1"></div><div class="cloud cl2"></div><div class="cloud cl3"></div>
  <div class="sun"></div>
  <div class="m-far"></div>
  <div class="m-near"></div>
  <div class="castle">
    <div class="c-wall"></div>
    <div class="c-t ct1"></div><div class="c-t ct2"></div><div class="c-t ct3"></div>
  </div>
  <div class="m-hill"></div>
  <div class="pine p1"></div><div class="pine p2"></div><div class="pine p3"></div><div class="pine p4"></div>
  <div class="forest"></div>
  <div class="tr1"></div><div class="tr2"></div>
  <div class="cn cn1"></div><div class="cn cn2"></div>
  <div class="bird b1"></div><div class="bird b2"></div>
  <div class="rt-t rt1t"></div><div class="rt-c rt1c"></div><div class="rt-t rt2t"></div><div class="rt-c rt2c"></div>
  <div class="lamp"></div>
  <div class="ff" style="left:6%;top:62%;animation-delay:-0.3s"></div><div class="ff" style="left:14%;top:70%;animation-delay:-1.1s"></div><div class="ff" style="left:22%;top:58%;animation-delay:-1.9s"></div><div class="ff" style="left:26%;top:74%;animation-delay:-0.7s"></div><div class="ff" style="left:80%;top:66%;animation-delay:-1.4s"></div><div class="ff" style="left:90%;top:60%;animation-delay:-2.2s"></div>
  <div class="ground"></div>
  <div class="bush bu1"></div><div class="bush bu2"></div><div class="bush bu3"></div><div class="bush bu4"></div>
  <div class="flw"></div>
  <div class="slime"></div>
  <div class="walker"></div>
</div>