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
  .cl1 { top: 14%; left: 8%; width: 170px; }
  .cl2 { top: 24%; right: 6%; width: 210px; background: #f7b9a0; }
  .cl3 { top: 8%; left: 48%; width: 120px; background: #cdbff0; }

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
  <div class="ground"></div>
</div>