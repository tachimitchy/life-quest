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

  .ground { left: 0; right: 0; bottom: 0; height: 14%; min-height: 64px; overflow: hidden; border-top: 14px solid #4caf3d;
    box-shadow: inset 0 6px 0 #3a8a2c, inset 0 10px 0 #2d7a24;
    background: linear-gradient(180deg, #8a5a2e 0%, #7a4a24 35%, #5e3818 75%, #4a2c12 100%); }
  .ground::before { content: ""; position: absolute; left: 0; top: 0; width: 8px; height: 8px; box-shadow: 57vw 89px #6f7280, 99vw 77px #a0693a, 65vw 93px #946236, 23vw 120px #5e3818, 60vw 98px #5e3818, 23vw 30px #a0693a, 38vw 36px #6b4020, 68vw 106px #8d8f9c, 5vw 94px #a0693a, 57vw 101px #8d8f9c, 78vw 101px #946236, 79vw 19px #6f7280, 67vw 26px #6b4020, 4vw 42px #946236, 76vw 21px #6f7280, 59vw 59px #a0693a, 75vw 43px #5e3818, 29vw 99px #4f2f14, 63vw 18px #8d8f9c, 10vw 76px #8d8f9c, 35vw 70px #5e3818, 10vw 108px #4f2f14, 40vw 115px #946236, 65vw 54px #6b4020, 8vw 90px #6f7280, 13vw 69px #6b4020, 37vw 67px #6b4020, 2vw 105px #6b4020, 27vw 44px #6b4020, 60vw 66px #8d8f9c, 50vw 71px #6b4020, 72vw 98px #946236, 99vw 104px #4f2f14, 43vw 29px #4f2f14, 42vw 19px #a0693a, 97vw 33px #946236, 31vw 108px #6b4020, 1vw 25px #a0693a, 62vw 40px #8d8f9c, 71vw 42px #a0693a, 65vw 42px #8d8f9c, 98vw 34px #a0693a, 82vw 67px #6b4020, 50vw 71px #946236, 0vw 52px #6f7280, 75vw 56px #6b4020, 26vw 41px #a0693a, 77vw 100px #5e3818, 12vw 23px #946236, 27vw 74px #4f2f14, 1vw 116px #5e3818, 42vw 55px #a0693a, 9vw 27px #6b4020, 26vw 92px #8d8f9c, 31vw 19px #5e3818, 47vw 65px #5e3818, 58vw 34px #5e3818, 61vw 91px #946236, 49vw 41px #8d8f9c, 19vw 57px #946236, 78vw 49px #8d8f9c, 24vw 38px #8d8f9c, 80vw 88px #946236, 87vw 67px #a0693a, 77vw 28px #a0693a, 6vw 31px #6b4020, 4vw 83px #4f2f14, 30vw 112px #8d8f9c, 50vw 50px #a0693a, 76vw 80px #4f2f14, 66vw 40px #8d8f9c, 8vw 34px #946236, 61vw 89px #8d8f9c, 78vw 96px #6b4020, 35vw 45px #946236, 95vw 20px #6b4020, 34vw 70px #a0693a, 31vw 25px #6b4020, 22vw 54px #4f2f14, 67vw 91px #946236, 11vw 64px #946236, 57vw 60px #8d8f9c, 93vw 106px #5e3818, 74vw 35px #5e3818, 4vw 20px #a0693a, 45vw 107px #4f2f14, 4vw 20px #5e3818, 81vw 27px #a0693a, 8vw 111px #4f2f14, 40vw 35px #6b4020; }
  .ground::after { content: ""; position: absolute; left: 0; right: 0; top: 0; height: 6px; background: repeating-linear-gradient(90deg, #5fc24d 0 8px, #4caf3d 8px 14px, #5fc24d 14px 20px, #3f9a31 20px 28px); }
  .tufts { left: 0; bottom: var(--g); width: 4px; height: 6px; box-shadow: 10vw 0px #3f9a31, 58vw -6px #3f9a31, 70vw 0px #3f9a31, 48vw -6px #3f9a31, 95vw 0px #3f9a31, 6vw -6px #3f9a31, 95vw 0px #3f9a31, 95vw -6px #3f9a31, 91vw 0px #3f9a31, 17vw -6px #3f9a31, 44vw 0px #3f9a31, 46vw -6px #3f9a31, 11vw 0px #3f9a31, 88vw -6px #3f9a31, 61vw 0px #3f9a31, 10vw -6px #3f9a31, 54vw 0px #3f9a31, 4vw -6px #3f9a31, 64vw 0px #3f9a31, 74vw -6px #3f9a31, 2vw 0px #3f9a31, 80vw -6px #3f9a31, 85vw 0px #3f9a31, 49vw -6px #3f9a31, 49vw 0px #3f9a31, 75vw -6px #3f9a31, 2vw 0px #3f9a31, 78vw -6px #3f9a31, 10vw 0px #3f9a31, 11vw -6px #3f9a31, 12vw 0px #3f9a31, 82vw -6px #3f9a31, 15vw 0px #3f9a31, 33vw -6px #3f9a31, 54vw 0px #3f9a31, 94vw -6px #3f9a31, 43vw 0px #3f9a31, 50vw -6px #3f9a31, 95vw 0px #3f9a31, 89vw -6px #3f9a31, 75vw 0px #3f9a31, 59vw -6px #3f9a31, 57vw 0px #3f9a31, 60vw -6px #3f9a31, 70vw 0px #3f9a31, 11vw -6px #3f9a31, 67vw 0px #3f9a31, 97vw -6px #3f9a31, 66vw 0px #3f9a31, 4vw -6px #3f9a31, 40vw 0px #3f9a31, 77vw -6px #3f9a31; }
  .sign { left: 14%; bottom: var(--g); width: 6px; height: 44px; background: #5a3a1c; }
  .sign::before { content: "➜"; position: absolute; left: -22px; top: -4px; width: 50px; height: 24px; font: 14px/22px "Press Start 2P", monospace; text-align: center; color: #3a2412; background: #c8955a; border: 3px solid #5a3a1c; }
  .fence { left: 71%; bottom: var(--g); width: 150px; height: 30px;
    background: linear-gradient(#a0693a, #a0693a) 0 7px / 100% 5px no-repeat, linear-gradient(#a0693a, #a0693a) 0 19px / 100% 5px no-repeat,
                repeating-linear-gradient(90deg, #7a4a24 0 8px, transparent 8px 30px); }
  .rock { bottom: var(--g); width: 44px; height: 26px; background: linear-gradient(#a8acc0 0 40%, #7b7f94 40%);
    clip-path: polygon(0 100%, 6% 55%, 28% 25%, 62% 10%, 88% 40%, 100% 100%); }
  .rk1 { left: 33%; } .rk2 { left: 86%; width: 34px; height: 20px; }
  .mush { bottom: var(--g); width: 18px; height: 22px; }
  .mush::before { content: ""; position: absolute; left: 0; top: 0; width: 18px; height: 11px; background: #e5484d; box-shadow: inset 5px 3px 0 -1px #fff;
    clip-path: polygon(0 100%, 0 55%, 15% 15%, 85% 15%, 100% 55%, 100% 100%); }
  .mush::after { content: ""; position: absolute; left: 6px; top: 11px; width: 6px; height: 11px; background: #f3e6cf; }
  .ms1 { left: 5%; } .ms2 { left: 38%; transform: scale(0.8); transform-origin: bottom; } .ms3 { left: 63%; }
  .fire { left: 24%; bottom: var(--g); width: 26px; height: 8px; background: #4a2c12; }
  .fire::before { content: ""; position: absolute; left: 6px; bottom: 6px; width: 14px; height: 20px; background: linear-gradient(#ffd54a, #ff8a1f 60%, #e5484d);
    clip-path: polygon(50% 0, 78% 35%, 100% 70%, 80% 100%, 20% 100%, 0 70%, 24% 35%); transform-origin: bottom; animation: flick 0.5s steps(2) infinite; }
  @keyframes flick { 50% { transform: scale(1.12, 1.3); } }
  .night .fire { box-shadow: 0 -8px 40px 14px rgba(255, 140, 40, 0.5); }
  .night :is(.sign, .fence, .rock, .mush, .tufts) { filter: brightness(0.55); }

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

  /* ===== Night Time (chosen in Settings) ===== */
  .stars { display: none; left: 0; top: 0; width: 3px; height: 3px; animation: twinkle 3s steps(2) infinite; }
  .s1 { box-shadow: 82vw 42vh 0 0 #fff3b0, 76vw 5vh 0 0 #ffffff, 75vw 39vh 0 0 #fff3b0, 52vw 5vh 0 0 #cfe0ff, 30vw 4vh 0 0 #ffffff, 73vw 10vh 0 0 #ffffff, 39vw 28vh 0 0 #cfe0ff, 20vw 36vh 0 0 #ffffff, 17vw 38vh 0 0 #fff3b0, 41vw 37vh 0 0 #cfe0ff, 89vw 13vh 0 0 #ffffff, 15vw 39vh 0 0 #cfe0ff, 75vw 42vh 0 0 #ffffff, 26vw 25vh 0 0 #ffffff, 14vw 37vh 0 0 #ffffff, 93vw 6vh 0 0 #fff3b0, 74vw 5vh 0 0 #fff3b0, 81vw 15vh 0 0 #ffffff, 65vw 45vh 0 0 #ffffff, 70vw 29vh 0 0 #ffffff, 42vw 31vh 0 0 #cfe0ff, 76vw 31vh 0 0 #fff3b0, 48vw 21vh 0 0 #ffffff, 33vw 52vh 0 0 #cfe0ff, 25vw 46vh 0 0 #ffffff, 33vw 7vh 0 0 #ffffff; }
  .s2 { width: 2px; height: 2px; animation-delay: -1.5s; box-shadow: 60vw 6vh 0 0 #cfe0ff, 13vw 19vh 0 0 #fff3b0, 62vw 46vh 0 0 #cfe0ff, 87vw 6vh 0 0 #fff3b0, 9vw 48vh 0 0 #fff3b0, 91vw 21vh 0 0 #cfe0ff, 84vw 38vh 0 0 #fff3b0, 89vw 54vh 0 0 #fff3b0, 59vw 20vh 0 0 #cfe0ff, 93vw 26vh 0 0 #ffffff, 87vw 24vh 0 0 #ffffff, 4vw 31vh 0 0 #cfe0ff, 47vw 12vh 0 0 #fff3b0, 80vw 9vh 0 0 #ffffff, 65vw 5vh 0 0 #fff3b0, 29vw 51vh 0 0 #ffffff, 38vw 10vh 0 0 #fff3b0, 96vw 17vh 0 0 #fff3b0, 52vw 27vh 0 0 #ffffff, 65vw 7vh 0 0 #cfe0ff, 23vw 30vh 0 0 #ffffff, 53vw 37vh 0 0 #cfe0ff, 37vw 58vh 0 0 #cfe0ff, 19vw 54vh 0 0 #fff3b0, 57vw 57vh 0 0 #fff3b0, 72vw 19vh 0 0 #cfe0ff, 92vw 28vh 0 0 #fff3b0, 47vw 45vh 0 0 #cfe0ff, 50vw 16vh 0 0 #fff3b0, 21vw 7vh 0 0 #cfe0ff; }
  @keyframes twinkle { 50% { opacity: 0.25; } }

  .scene.night { background: linear-gradient(180deg, #04061a 0%, #0b0f3a 45%, #1a1a5a 75%, #2a2272 100%); }
  .night .stars { display: block; }
  .night .sun { left: 91%; bottom: 40%; background: radial-gradient(circle at 35% 38%, #cfcdbf 0 5px, transparent 6px), radial-gradient(circle at 66% 64%, #cfcdbf 0 4px, transparent 5px), #f4f2e6;
    box-shadow: 0 0 0 8px rgba(244, 242, 230, 0.12), 0 0 46px 16px rgba(190, 205, 255, 0.35); }
  .night .cloud { background: #2b3170 !important; opacity: 0.6; }
  .night .bird { display: none; }
  .night .m-far { background: #25285a; }
  .night .m-near { background: linear-gradient(105deg, #4a4d86 0 48%, #32356a 48%); }
  .night .m-hill { background: #1f4a2e; }
  .night :is(.forest, .tr1, .tr2, .cn, .pine, .bush, .rt-c, .rt-t, .ground) { filter: brightness(0.5) saturate(0.9); }
  .night .c-wall { background: #4a4960; }
  .night .c-t { background: #5b5a76; }
  .night .c-t::before { background: #7a3320; }
  .night .c-t::after { content: ""; position: absolute; left: 50%; top: 14px; width: 6px; height: 9px; margin-left: -3px; background: #ffd25a; box-shadow: 0 0 8px 2px #ffd25a; }
  .night .ff { box-shadow: 0 0 10px 4px rgba(255, 243, 160, 0.8); }
  .night .lamp::before { box-shadow: 0 0 30px 12px rgba(255, 210, 90, 0.8); }

  /* letters and buttons shine like stars at night */
  @keyframes glowpulse { from { filter: drop-shadow(0 0 3px rgba(255, 255, 170, 0.5)); } to { filter: drop-shadow(0 0 12px rgba(255, 255, 200, 0.95)); } }
  @keyframes starbtn { from { filter: brightness(1) drop-shadow(0 0 3px rgba(255, 255, 190, 0.45)); } to { filter: brightness(1.22) drop-shadow(0 0 10px rgba(255, 255, 190, 0.9)); } }
  body:has(.scene.night) :is(.lq-title, .lq-badge, .header h1:not(.title), .welcome h1, .page-title) { animation: glowpulse 2.6s ease-in-out infinite alternate; }
  body:has(.scene.night) :is(.btn, .btn-submit, .btn-back, .tile, .nav a, .px, .role-option span, .theme-option span) { animation: starbtn 2.2s ease-in-out infinite alternate; }
  body:has(.scene.night) :is(.btn, .btn-submit, .btn-back, .tile, .nav a, .px):nth-child(2n) { animation-delay: -1s; }
  body:has(.scene.night) :is(.btn, .btn-submit, .btn-back, .tile, .nav a, .px):nth-child(3n) { animation-delay: -1.7s; }
  body:has(.scene.night) :is(.caption, .sub-caption, .header p, .profile-role, .section-title, .welcome p, .lead, .footer, label, legend, .announce, .panel p) { text-shadow: 0 0 8px rgba(190, 205, 255, 0.75); }
  @media (prefers-reduced-motion: reduce) {
    body:has(.scene.night) :is(.lq-title, .lq-badge, .header h1, .welcome h1, .page-title, .btn, .btn-submit, .btn-back, .tile, .nav a, .px, .role-option span, .theme-option span) { animation: none; }
  }
  /* ===== pixel characters ===== */
  .spr { position: relative; display: block; width: 1em; height: 1em; font-size: 6px; }
  .spr-boy { box-shadow: 2em 0em #6b3e1e, 3em 0em #6b3e1e, 4em 0em #6b3e1e, 5em 0em #6b3e1e, 6em 0em #6b3e1e, 1em 1em #6b3e1e, 2em 1em #6b3e1e, 3em 1em #6b3e1e, 4em 1em #6b3e1e, 5em 1em #6b3e1e, 6em 1em #6b3e1e, 7em 1em #6b3e1e, 1em 2em #6b3e1e, 2em 2em #6b3e1e, 3em 2em #6b3e1e, 4em 2em #f2c29b, 5em 2em #f2c29b, 6em 2em #f2c29b, 7em 2em #f2c29b, 1em 3em #6b3e1e, 2em 3em #6b3e1e, 3em 3em #f2c29b, 4em 3em #f2c29b, 5em 3em #f2c29b, 6em 3em #111111, 7em 3em #f2c29b, 2em 4em #f2c29b, 3em 4em #f2c29b, 4em 4em #f2c29b, 5em 4em #f2c29b, 6em 4em #f2c29b, 7em 4em #f2c29b, 0em 5em #d6342c, 1em 5em #d6342c, 2em 5em #d6342c, 3em 5em #d6342c, 4em 5em #d6342c, 5em 5em #d6342c, 6em 5em #d6342c, 7em 5em #d6342c, 2em 6em #3b63c9, 3em 6em #3b63c9, 4em 6em #3b63c9, 5em 6em #3b63c9, 6em 6em #3b63c9, 7em 6em #3b63c9, 1em 7em #f2c29b, 2em 7em #3b63c9, 3em 7em #3b63c9, 4em 7em #3b63c9, 5em 7em #3b63c9, 6em 7em #3b63c9, 7em 7em #3b63c9, 8em 7em #f2c29b, 2em 8em #3b63c9, 3em 8em #3b63c9, 4em 8em #3b63c9, 5em 8em #3b63c9, 6em 8em #3b63c9, 7em 8em #3b63c9, 2em 9em #2b3a67, 3em 9em #2b3a67, 4em 9em #2b3a67, 5em 9em #2b3a67, 6em 9em #2b3a67, 7em 9em #2b3a67, 1em 10em #2b3a67, 2em 10em #2b3a67, 7em 10em #2b3a67, 8em 10em #2b3a67, 1em 11em #2b3a67, 2em 11em #2b3a67, 7em 11em #2b3a67, 8em 11em #2b3a67, 1em 12em #3a2412, 2em 12em #3a2412, 7em 12em #3a2412, 8em 12em #3a2412; }
  .spr-girl { box-shadow: 2em 0em #3a1f12, 3em 0em #3a1f12, 4em 0em #3a1f12, 5em 0em #3a1f12, 6em 0em #3a1f12, 7em 0em #ff6fae, 1em 1em #3a1f12, 2em 1em #3a1f12, 3em 1em #3a1f12, 4em 1em #3a1f12, 5em 1em #3a1f12, 6em 1em #3a1f12, 7em 1em #3a1f12, 1em 2em #3a1f12, 2em 2em #3a1f12, 3em 2em #3a1f12, 4em 2em #f2c29b, 5em 2em #f2c29b, 6em 2em #f2c29b, 7em 2em #f2c29b, 8em 2em #3a1f12, 1em 3em #3a1f12, 2em 3em #3a1f12, 3em 3em #f2c29b, 4em 3em #f2c29b, 5em 3em #f2c29b, 6em 3em #111111, 7em 3em #f2c29b, 8em 3em #3a1f12, 1em 4em #3a1f12, 2em 4em #3a1f12, 3em 4em #f2c29b, 4em 4em #f2c29b, 5em 4em #f2c29b, 6em 4em #f2c29b, 7em 4em #f2c29b, 1em 5em #3a1f12, 2em 5em #3a1f12, 3em 5em #e0559f, 4em 5em #e0559f, 5em 5em #e0559f, 6em 5em #e0559f, 7em 5em #3a1f12, 1em 6em #3a1f12, 2em 6em #f2c29b, 3em 6em #e0559f, 4em 6em #e0559f, 5em 6em #e0559f, 6em 6em #e0559f, 7em 6em #f2c29b, 2em 7em #e0559f, 3em 7em #e0559f, 4em 7em #e0559f, 5em 7em #e0559f, 6em 7em #e0559f, 7em 7em #e0559f, 1em 8em #e0559f, 2em 8em #e0559f, 3em 8em #e0559f, 4em 8em #e0559f, 5em 8em #e0559f, 6em 8em #e0559f, 7em 8em #e0559f, 8em 8em #e0559f, 1em 9em #e0559f, 2em 9em #e0559f, 3em 9em #e0559f, 4em 9em #e0559f, 5em 9em #e0559f, 6em 9em #e0559f, 7em 9em #e0559f, 8em 9em #e0559f, 2em 10em #f2c29b, 3em 10em #f2c29b, 6em 10em #f2c29b, 7em 10em #f2c29b, 2em 11em #f2c29b, 3em 11em #f2c29b, 6em 11em #f2c29b, 7em 11em #f2c29b, 2em 12em #5a2a1a, 3em 12em #5a2a1a, 6em 12em #5a2a1a, 7em 12em #5a2a1a; }
  .spr-ranger { box-shadow: 2em 0em #2f8f43, 3em 0em #2f8f43, 4em 0em #2f8f43, 5em 0em #2f8f43, 6em 0em #2f8f43, 1em 1em #2f8f43, 2em 1em #2f8f43, 3em 1em #2f8f43, 4em 1em #2f8f43, 5em 1em #2f8f43, 6em 1em #2f8f43, 7em 1em #2f8f43, 1em 2em #2f8f43, 2em 2em #2f8f43, 3em 2em #f2c29b, 4em 2em #f2c29b, 5em 2em #f2c29b, 6em 2em #f2c29b, 7em 2em #2f8f43, 1em 3em #2f8f43, 2em 3em #2f8f43, 3em 3em #f2c29b, 4em 3em #f2c29b, 5em 3em #111111, 6em 3em #f2c29b, 7em 3em #2f8f43, 2em 4em #2f8f43, 3em 4em #f2c29b, 4em 4em #f2c29b, 5em 4em #f2c29b, 6em 4em #f2c29b, 7em 4em #2f8f43, 2em 5em #2f8f43, 3em 5em #2f8f43, 4em 5em #2f8f43, 5em 5em #2f8f43, 6em 5em #2f8f43, 7em 5em #2f8f43, 1em 6em #f2c29b, 2em 6em #2f8f43, 3em 6em #8a5a2b, 4em 6em #8a5a2b, 5em 6em #8a5a2b, 6em 6em #8a5a2b, 7em 6em #2f8f43, 8em 6em #f2c29b, 1em 7em #f2c29b, 2em 7em #2f8f43, 3em 7em #8a5a2b, 4em 7em #8a5a2b, 5em 7em #8a5a2b, 6em 7em #8a5a2b, 7em 7em #2f8f43, 8em 7em #f2c29b, 2em 8em #8a5a2b, 3em 8em #8a5a2b, 4em 8em #8a5a2b, 5em 8em #8a5a2b, 6em 8em #8a5a2b, 7em 8em #8a5a2b, 2em 9em #4a3a22, 3em 9em #4a3a22, 4em 9em #4a3a22, 5em 9em #4a3a22, 6em 9em #4a3a22, 7em 9em #4a3a22, 2em 10em #4a3a22, 3em 10em #4a3a22, 6em 10em #4a3a22, 7em 10em #4a3a22, 2em 11em #4a3a22, 3em 11em #4a3a22, 6em 11em #4a3a22, 7em 11em #4a3a22, 2em 12em #2a1a0e, 3em 12em #2a1a0e, 6em 12em #2a1a0e, 7em 12em #2a1a0e; }
  .spr-knight { box-shadow: 3em 0em #d6342c, 4em 0em #d6342c, 2em 1em #9aa3b8, 3em 1em #9aa3b8, 4em 1em #9aa3b8, 5em 1em #9aa3b8, 6em 1em #9aa3b8, 7em 1em #9aa3b8, 1em 2em #9aa3b8, 2em 2em #9aa3b8, 3em 2em #9aa3b8, 4em 2em #9aa3b8, 5em 2em #9aa3b8, 6em 2em #9aa3b8, 7em 2em #9aa3b8, 8em 2em #9aa3b8, 1em 3em #9aa3b8, 2em 3em #9aa3b8, 3em 3em #1a1a2e, 4em 3em #1a1a2e, 5em 3em #1a1a2e, 6em 3em #1a1a2e, 7em 3em #9aa3b8, 8em 3em #9aa3b8, 1em 4em #9aa3b8, 2em 4em #9aa3b8, 3em 4em #9aa3b8, 4em 4em #9aa3b8, 5em 4em #9aa3b8, 6em 4em #9aa3b8, 7em 4em #9aa3b8, 8em 4em #9aa3b8, 2em 5em #9aa3b8, 3em 5em #9aa3b8, 4em 5em #9aa3b8, 5em 5em #9aa3b8, 6em 5em #9aa3b8, 7em 5em #9aa3b8, 1em 6em #dfe6f5, 2em 6em #9aa3b8, 3em 6em #9aa3b8, 4em 6em #9aa3b8, 5em 6em #9aa3b8, 6em 6em #9aa3b8, 7em 6em #9aa3b8, 8em 6em #dfe6f5, 1em 7em #f2c29b, 2em 7em #9aa3b8, 3em 7em #9aa3b8, 4em 7em #9aa3b8, 5em 7em #9aa3b8, 6em 7em #9aa3b8, 7em 7em #9aa3b8, 8em 7em #f2c29b, 2em 8em #9aa3b8, 3em 8em #9aa3b8, 4em 8em #ffd54a, 5em 8em #ffd54a, 6em 8em #9aa3b8, 7em 8em #9aa3b8, 2em 9em #9aa3b8, 3em 9em #9aa3b8, 4em 9em #9aa3b8, 5em 9em #9aa3b8, 6em 9em #9aa3b8, 7em 9em #9aa3b8, 2em 10em #9aa3b8, 3em 10em #9aa3b8, 6em 10em #9aa3b8, 7em 10em #9aa3b8, 2em 11em #9aa3b8, 3em 11em #9aa3b8, 6em 11em #9aa3b8, 7em 11em #9aa3b8, 2em 12em #3a2412, 3em 12em #3a2412, 6em 12em #3a2412, 7em 12em #3a2412; }
  .spr-mage { box-shadow: 4em 0em #5b2fb0, 5em 0em #5b2fb0, 3em 1em #5b2fb0, 4em 1em #5b2fb0, 5em 1em #5b2fb0, 6em 1em #5b2fb0, 2em 2em #5b2fb0, 3em 2em #5b2fb0, 4em 2em #5b2fb0, 5em 2em #5b2fb0, 6em 2em #5b2fb0, 7em 2em #5b2fb0, 1em 3em #5b2fb0, 2em 3em #5b2fb0, 3em 3em #5b2fb0, 4em 3em #5b2fb0, 5em 3em #5b2fb0, 6em 3em #5b2fb0, 7em 3em #5b2fb0, 8em 3em #5b2fb0, 2em 4em #e8d9ff, 3em 4em #f2c29b, 4em 4em #f2c29b, 5em 4em #f2c29b, 6em 4em #f2c29b, 7em 4em #e8d9ff, 2em 5em #e8d9ff, 3em 5em #f2c29b, 4em 5em #f2c29b, 5em 5em #111111, 6em 5em #f2c29b, 7em 5em #e8d9ff, 2em 6em #7a3fd0, 3em 6em #7a3fd0, 4em 6em #7a3fd0, 5em 6em #7a3fd0, 6em 6em #7a3fd0, 7em 6em #7a3fd0, 1em 7em #f2c29b, 2em 7em #7a3fd0, 3em 7em #7a3fd0, 4em 7em #7a3fd0, 5em 7em #7a3fd0, 6em 7em #7a3fd0, 7em 7em #7a3fd0, 8em 7em #f2c29b, 2em 8em #7a3fd0, 3em 8em #7a3fd0, 4em 8em #ffd54a, 5em 8em #ffd54a, 6em 8em #7a3fd0, 7em 8em #7a3fd0, 2em 9em #7a3fd0, 3em 9em #7a3fd0, 4em 9em #7a3fd0, 5em 9em #7a3fd0, 6em 9em #7a3fd0, 7em 9em #7a3fd0, 1em 10em #7a3fd0, 2em 10em #7a3fd0, 3em 10em #7a3fd0, 4em 10em #7a3fd0, 5em 10em #7a3fd0, 6em 10em #7a3fd0, 7em 10em #7a3fd0, 8em 10em #7a3fd0, 1em 11em #7a3fd0, 2em 11em #7a3fd0, 3em 11em #7a3fd0, 4em 11em #7a3fd0, 5em 11em #7a3fd0, 6em 11em #7a3fd0, 7em 11em #7a3fd0, 8em 11em #7a3fd0, 2em 12em #3a2412, 3em 12em #3a2412, 6em 12em #3a2412, 7em 12em #3a2412; }
  .spr-ninja { box-shadow: 2em 0em #2f3558, 3em 0em #2f3558, 4em 0em #2f3558, 5em 0em #2f3558, 6em 0em #2f3558, 1em 1em #2f3558, 2em 1em #2f3558, 3em 1em #2f3558, 4em 1em #2f3558, 5em 1em #2f3558, 6em 1em #2f3558, 7em 1em #2f3558, 1em 2em #d6342c, 2em 2em #d6342c, 3em 2em #d6342c, 4em 2em #d6342c, 5em 2em #d6342c, 6em 2em #d6342c, 7em 2em #d6342c, 1em 3em #2f3558, 2em 3em #2f3558, 3em 3em #f2c29b, 4em 3em #ffffff, 5em 3em #f2c29b, 6em 3em #f2c29b, 7em 3em #2f3558, 2em 4em #2f3558, 3em 4em #2f3558, 4em 4em #2f3558, 5em 4em #2f3558, 6em 4em #2f3558, 7em 4em #2f3558, 2em 5em #2f3558, 3em 5em #2f3558, 4em 5em #2f3558, 5em 5em #2f3558, 6em 5em #2f3558, 7em 5em #2f3558, 1em 6em #2f3558, 2em 6em #2f3558, 3em 6em #2f3558, 4em 6em #2f3558, 5em 6em #2f3558, 6em 6em #2f3558, 7em 6em #2f3558, 8em 6em #2f3558, 1em 7em #f2c29b, 2em 7em #2f3558, 3em 7em #2f3558, 4em 7em #d6342c, 5em 7em #d6342c, 6em 7em #2f3558, 7em 7em #2f3558, 8em 7em #f2c29b, 2em 8em #2f3558, 3em 8em #2f3558, 4em 8em #2f3558, 5em 8em #2f3558, 6em 8em #2f3558, 7em 8em #2f3558, 2em 9em #2f3558, 3em 9em #2f3558, 4em 9em #2f3558, 5em 9em #2f3558, 6em 9em #2f3558, 7em 9em #2f3558, 2em 10em #2f3558, 3em 10em #2f3558, 6em 10em #2f3558, 7em 10em #2f3558, 2em 11em #2f3558, 3em 11em #2f3558, 6em 11em #2f3558, 7em 11em #2f3558, 2em 12em #d6342c, 3em 12em #d6342c, 6em 12em #d6342c, 7em 12em #d6342c; }
  .spr-hero { box-shadow: 2em 0em #6b3e1e, 3em 0em #6b3e1e, 4em 0em #6b3e1e, 5em 0em #6b3e1e, 6em 0em #6b3e1e, 1em 1em #6b3e1e, 2em 1em #6b3e1e, 3em 1em #6b3e1e, 4em 1em #6b3e1e, 5em 1em #6b3e1e, 6em 1em #6b3e1e, 7em 1em #6b3e1e, 1em 2em #6b3e1e, 2em 2em #6b3e1e, 3em 2em #6b3e1e, 4em 2em #f2c29b, 5em 2em #f2c29b, 6em 2em #f2c29b, 7em 2em #f2c29b, 1em 3em #6b3e1e, 2em 3em #6b3e1e, 3em 3em #f2c29b, 4em 3em #f2c29b, 5em 3em #f2c29b, 6em 3em #111111, 7em 3em #f2c29b, 2em 4em #f2c29b, 3em 4em #f2c29b, 4em 4em #f2c29b, 5em 4em #f2c29b, 6em 4em #f2c29b, 7em 4em #f2c29b, 0em 5em #d6342c, 1em 5em #d6342c, 2em 5em #d6342c, 3em 5em #d6342c, 4em 5em #d6342c, 5em 5em #d6342c, 6em 5em #d6342c, 7em 5em #d6342c, 2em 6em #3b63c9, 3em 6em #3b63c9, 4em 6em #3b63c9, 5em 6em #3b63c9, 6em 6em #3b63c9, 7em 6em #3b63c9, 1em 7em #f2c29b, 2em 7em #3b63c9, 3em 7em #3b63c9, 4em 7em #3b63c9, 5em 7em #3b63c9, 6em 7em #3b63c9, 7em 7em #3b63c9, 8em 7em #f2c29b, 2em 8em #3b63c9, 3em 8em #3b63c9, 4em 8em #3b63c9, 5em 8em #3b63c9, 6em 8em #3b63c9, 7em 8em #3b63c9, 2em 9em #2b3a67, 3em 9em #2b3a67, 4em 9em #2b3a67, 5em 9em #2b3a67, 6em 9em #2b3a67, 7em 9em #2b3a67, 1em 10em #2b3a67, 2em 10em #2b3a67, 7em 10em #2b3a67, 8em 10em #2b3a67, 1em 11em #2b3a67, 2em 11em #2b3a67, 7em 11em #2b3a67, 8em 11em #2b3a67, 1em 12em #3a2412, 2em 12em #3a2412, 7em 12em #3a2412, 8em 12em #3a2412, 10em 1em #dfe9ff, 10em 2em #dfe9ff, 10em 3em #dfe9ff, 10em 4em #dfe9ff, 10em 5em #dfe9ff, 10em 6em #dfe9ff, 10em 0em #ffffff, 9em 7em #ffd54a, 10em 7em #ffd54a, 11em 7em #ffd54a, 10em 8em #7a4a24, 10em 9em #7a4a24; }
  @keyframes step-hero { 0% { box-shadow: 2em 0em #6b3e1e, 3em 0em #6b3e1e, 4em 0em #6b3e1e, 5em 0em #6b3e1e, 6em 0em #6b3e1e, 1em 1em #6b3e1e, 2em 1em #6b3e1e, 3em 1em #6b3e1e, 4em 1em #6b3e1e, 5em 1em #6b3e1e, 6em 1em #6b3e1e, 7em 1em #6b3e1e, 1em 2em #6b3e1e, 2em 2em #6b3e1e, 3em 2em #6b3e1e, 4em 2em #f2c29b, 5em 2em #f2c29b, 6em 2em #f2c29b, 7em 2em #f2c29b, 1em 3em #6b3e1e, 2em 3em #6b3e1e, 3em 3em #f2c29b, 4em 3em #f2c29b, 5em 3em #f2c29b, 6em 3em #111111, 7em 3em #f2c29b, 2em 4em #f2c29b, 3em 4em #f2c29b, 4em 4em #f2c29b, 5em 4em #f2c29b, 6em 4em #f2c29b, 7em 4em #f2c29b, 0em 5em #d6342c, 1em 5em #d6342c, 2em 5em #d6342c, 3em 5em #d6342c, 4em 5em #d6342c, 5em 5em #d6342c, 6em 5em #d6342c, 7em 5em #d6342c, 2em 6em #3b63c9, 3em 6em #3b63c9, 4em 6em #3b63c9, 5em 6em #3b63c9, 6em 6em #3b63c9, 7em 6em #3b63c9, 1em 7em #f2c29b, 2em 7em #3b63c9, 3em 7em #3b63c9, 4em 7em #3b63c9, 5em 7em #3b63c9, 6em 7em #3b63c9, 7em 7em #3b63c9, 8em 7em #f2c29b, 2em 8em #3b63c9, 3em 8em #3b63c9, 4em 8em #3b63c9, 5em 8em #3b63c9, 6em 8em #3b63c9, 7em 8em #3b63c9, 2em 9em #2b3a67, 3em 9em #2b3a67, 4em 9em #2b3a67, 5em 9em #2b3a67, 6em 9em #2b3a67, 7em 9em #2b3a67, 1em 10em #2b3a67, 2em 10em #2b3a67, 7em 10em #2b3a67, 8em 10em #2b3a67, 1em 11em #2b3a67, 2em 11em #2b3a67, 7em 11em #2b3a67, 8em 11em #2b3a67, 1em 12em #3a2412, 2em 12em #3a2412, 7em 12em #3a2412, 8em 12em #3a2412, 10em 1em #dfe9ff, 10em 2em #dfe9ff, 10em 3em #dfe9ff, 10em 4em #dfe9ff, 10em 5em #dfe9ff, 10em 6em #dfe9ff, 10em 0em #ffffff, 9em 7em #ffd54a, 10em 7em #ffd54a, 11em 7em #ffd54a, 10em 8em #7a4a24, 10em 9em #7a4a24; transform: translateY(0); } 50% { box-shadow: 2em 0em #6b3e1e, 3em 0em #6b3e1e, 4em 0em #6b3e1e, 5em 0em #6b3e1e, 6em 0em #6b3e1e, 1em 1em #6b3e1e, 2em 1em #6b3e1e, 3em 1em #6b3e1e, 4em 1em #6b3e1e, 5em 1em #6b3e1e, 6em 1em #6b3e1e, 7em 1em #6b3e1e, 1em 2em #6b3e1e, 2em 2em #6b3e1e, 3em 2em #6b3e1e, 4em 2em #f2c29b, 5em 2em #f2c29b, 6em 2em #f2c29b, 7em 2em #f2c29b, 1em 3em #6b3e1e, 2em 3em #6b3e1e, 3em 3em #f2c29b, 4em 3em #f2c29b, 5em 3em #f2c29b, 6em 3em #111111, 7em 3em #f2c29b, 2em 4em #f2c29b, 3em 4em #f2c29b, 4em 4em #f2c29b, 5em 4em #f2c29b, 6em 4em #f2c29b, 7em 4em #f2c29b, 1em 5em #d6342c, 2em 5em #d6342c, 3em 5em #d6342c, 4em 5em #d6342c, 5em 5em #d6342c, 6em 5em #d6342c, 7em 5em #d6342c, 0em 6em #d6342c, 1em 6em #d6342c, 2em 6em #3b63c9, 3em 6em #3b63c9, 4em 6em #3b63c9, 5em 6em #3b63c9, 6em 6em #3b63c9, 7em 6em #3b63c9, 1em 7em #f2c29b, 2em 7em #3b63c9, 3em 7em #3b63c9, 4em 7em #3b63c9, 5em 7em #3b63c9, 6em 7em #3b63c9, 7em 7em #3b63c9, 8em 7em #f2c29b, 2em 8em #3b63c9, 3em 8em #3b63c9, 4em 8em #3b63c9, 5em 8em #3b63c9, 6em 8em #3b63c9, 7em 8em #3b63c9, 2em 9em #2b3a67, 3em 9em #2b3a67, 4em 9em #2b3a67, 5em 9em #2b3a67, 6em 9em #2b3a67, 7em 9em #2b3a67, 2em 10em #2b3a67, 3em 10em #2b3a67, 4em 10em #2b3a67, 5em 10em #2b3a67, 6em 10em #2b3a67, 7em 10em #2b3a67, 3em 11em #2b3a67, 4em 11em #2b3a67, 5em 11em #2b3a67, 6em 11em #2b3a67, 3em 12em #3a2412, 4em 12em #3a2412, 5em 12em #3a2412, 6em 12em #3a2412, 10em 1em #dfe9ff, 10em 2em #dfe9ff, 10em 3em #dfe9ff, 10em 4em #dfe9ff, 10em 5em #dfe9ff, 10em 6em #dfe9ff, 10em 0em #ffffff, 9em 7em #ffd54a, 10em 7em #ffd54a, 11em 7em #ffd54a, 10em 8em #7a4a24, 10em 9em #7a4a24; transform: translateY(-0.8em); } }
  @keyframes step-girl { 0% { box-shadow: 2em 0em #3a1f12, 3em 0em #3a1f12, 4em 0em #3a1f12, 5em 0em #3a1f12, 6em 0em #3a1f12, 7em 0em #ff6fae, 1em 1em #3a1f12, 2em 1em #3a1f12, 3em 1em #3a1f12, 4em 1em #3a1f12, 5em 1em #3a1f12, 6em 1em #3a1f12, 7em 1em #3a1f12, 1em 2em #3a1f12, 2em 2em #3a1f12, 3em 2em #3a1f12, 4em 2em #f2c29b, 5em 2em #f2c29b, 6em 2em #f2c29b, 7em 2em #f2c29b, 8em 2em #3a1f12, 1em 3em #3a1f12, 2em 3em #3a1f12, 3em 3em #f2c29b, 4em 3em #f2c29b, 5em 3em #f2c29b, 6em 3em #111111, 7em 3em #f2c29b, 8em 3em #3a1f12, 1em 4em #3a1f12, 2em 4em #3a1f12, 3em 4em #f2c29b, 4em 4em #f2c29b, 5em 4em #f2c29b, 6em 4em #f2c29b, 7em 4em #f2c29b, 1em 5em #3a1f12, 2em 5em #3a1f12, 3em 5em #e0559f, 4em 5em #e0559f, 5em 5em #e0559f, 6em 5em #e0559f, 7em 5em #3a1f12, 1em 6em #3a1f12, 2em 6em #f2c29b, 3em 6em #e0559f, 4em 6em #e0559f, 5em 6em #e0559f, 6em 6em #e0559f, 7em 6em #f2c29b, 2em 7em #e0559f, 3em 7em #e0559f, 4em 7em #e0559f, 5em 7em #e0559f, 6em 7em #e0559f, 7em 7em #e0559f, 1em 8em #e0559f, 2em 8em #e0559f, 3em 8em #e0559f, 4em 8em #e0559f, 5em 8em #e0559f, 6em 8em #e0559f, 7em 8em #e0559f, 8em 8em #e0559f, 1em 9em #e0559f, 2em 9em #e0559f, 3em 9em #e0559f, 4em 9em #e0559f, 5em 9em #e0559f, 6em 9em #e0559f, 7em 9em #e0559f, 8em 9em #e0559f, 2em 10em #f2c29b, 3em 10em #f2c29b, 6em 10em #f2c29b, 7em 10em #f2c29b, 2em 11em #f2c29b, 3em 11em #f2c29b, 6em 11em #f2c29b, 7em 11em #f2c29b, 2em 12em #5a2a1a, 3em 12em #5a2a1a, 6em 12em #5a2a1a, 7em 12em #5a2a1a; transform: translateY(0); } 50% { box-shadow: 2em 0em #3a1f12, 3em 0em #3a1f12, 4em 0em #3a1f12, 5em 0em #3a1f12, 6em 0em #3a1f12, 7em 0em #ff6fae, 1em 1em #3a1f12, 2em 1em #3a1f12, 3em 1em #3a1f12, 4em 1em #3a1f12, 5em 1em #3a1f12, 6em 1em #3a1f12, 7em 1em #3a1f12, 1em 2em #3a1f12, 2em 2em #3a1f12, 3em 2em #3a1f12, 4em 2em #f2c29b, 5em 2em #f2c29b, 6em 2em #f2c29b, 7em 2em #f2c29b, 8em 2em #3a1f12, 1em 3em #3a1f12, 2em 3em #3a1f12, 3em 3em #f2c29b, 4em 3em #f2c29b, 5em 3em #f2c29b, 6em 3em #111111, 7em 3em #f2c29b, 8em 3em #3a1f12, 1em 4em #3a1f12, 2em 4em #3a1f12, 3em 4em #f2c29b, 4em 4em #f2c29b, 5em 4em #f2c29b, 6em 4em #f2c29b, 7em 4em #f2c29b, 1em 5em #3a1f12, 2em 5em #3a1f12, 3em 5em #e0559f, 4em 5em #e0559f, 5em 5em #e0559f, 6em 5em #e0559f, 7em 5em #3a1f12, 1em 6em #3a1f12, 2em 6em #f2c29b, 3em 6em #e0559f, 4em 6em #e0559f, 5em 6em #e0559f, 6em 6em #e0559f, 7em 6em #f2c29b, 2em 7em #e0559f, 3em 7em #e0559f, 4em 7em #e0559f, 5em 7em #e0559f, 6em 7em #e0559f, 7em 7em #e0559f, 1em 8em #e0559f, 2em 8em #e0559f, 3em 8em #e0559f, 4em 8em #e0559f, 5em 8em #e0559f, 6em 8em #e0559f, 7em 8em #e0559f, 8em 8em #e0559f, 1em 9em #e0559f, 2em 9em #e0559f, 3em 9em #e0559f, 4em 9em #e0559f, 5em 9em #e0559f, 6em 9em #e0559f, 7em 9em #e0559f, 8em 9em #e0559f, 2em 10em #f2c29b, 3em 10em #f2c29b, 4em 10em #f2c29b, 5em 10em #f2c29b, 6em 10em #f2c29b, 7em 10em #f2c29b, 3em 11em #f2c29b, 4em 11em #f2c29b, 5em 11em #f2c29b, 6em 11em #f2c29b, 3em 12em #5a2a1a, 4em 12em #5a2a1a, 5em 12em #5a2a1a, 6em 12em #5a2a1a; transform: translateY(-0.8em); } }
  .spr-hero.walking { animation: step-hero 0.5s steps(1) infinite; }
  .spr-girl.walking { animation: step-girl 0.5s steps(1) infinite; }
  @keyframes bob { 50% { transform: translateY(-0.8em); } }

  /* ===== walking people, speech bubbles, grabbing ===== */
  .lq-actors { position: fixed; inset: 0; z-index: 40; pointer-events: none; overflow: hidden; }
  .actor { position: absolute; left: 0; top: 0; width: 12em; height: 13em; font-size: 6px; pointer-events: auto; cursor: grab; touch-action: none; user-select: none; -webkit-user-select: none; }
  .actor:active { cursor: grabbing; }
  .actor .flipper { position: absolute; inset: 0; }
  .actor.left .flipper { transform: scaleX(-1); }
  .actor .spr { position: absolute; left: 0; top: 0; }
  .actor .bubble { position: absolute; left: 50%; bottom: 100%; margin-bottom: 6px; transform: translateX(-50%); white-space: nowrap;
    padding: 2px 8px; font: 20px/1.2 "VT323", monospace; color: #222; background: #fff; border: 3px solid #000; box-shadow: 3px 3px 0 rgba(0, 0, 0, 0.35); }
  .actor .bubble::after { content: ""; position: absolute; left: 50%; top: 100%; width: 8px; height: 8px; margin-left: -4px; background: #fff; border: 0 solid #000; border-width: 0 3px 3px 0; transform: translateY(-5px) rotate(45deg); }
  .actor .bubble.emo { font-size: 30px; line-height: 1; padding: 2px 6px; }

  .show-pw { display: flex; align-items: center; gap: 0.5rem; margin: -0.35rem 0 1rem; font-size: 1.3rem; color: #8e9ad0; cursor: pointer; }
  .show-pw input { width: 1.1rem; height: 1.1rem; accent-color: #4caf3d; }
</style>

<?php $__night = (($_COOKIE['lq_theme'] ?? '') === 'night'); ?>
<div class="scene<?= $__night ? ' night' : '' ?>" aria-hidden="true">
  <div class="stars s1"></div><div class="stars s2"></div>
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
  <div class="tufts"></div><div class="sign"></div><div class="fence"></div><div class="fire"></div>
  <div class="rock rk1"></div><div class="rock rk2"></div><div class="mush ms1"></div><div class="mush ms2"></div><div class="mush ms3"></div>
  <div class="bush bu1"></div><div class="bush bu2"></div><div class="bush bu3"></div><div class="bush bu4"></div>
  <div class="flw"></div>
  <div class="slime"></div>
</div>
<?php require __DIR__ . '/actors.php'; ?>
<?php require __DIR__ . '/music.php'; ?>