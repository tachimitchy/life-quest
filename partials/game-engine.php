<script>
(function () {
'use strict';
var C = window.LQ_CONF;
var W = 400, H = 225, GROUND = 160, G = 720, RUN = 88, JUMP = -290, MAXFALL = 420;
var SPR = {"boy": {"rows": ["..HHHHH...", ".HHHHHHH..", ".HHHSSSS..", ".HHSSSKS..", "..SSSSSS..", "RRRRRRRR..", "..BBBBBB..", ".SBBBBBBS.", "..BBBBBB..", "..PPPPPP..", ".PP....PP.", ".PP....PP.", ".OO....OO."], "pal": {"H": "#6b3e1e", "S": "#f2c29b", "K": "#111111", "R": "#d6342c", "B": "#3b63c9", "P": "#2b3a67", "O": "#3a2412"}}, "girl": {"rows": ["..HHHHHR..", ".HHHHHHH..", ".HHHSSSSH.", ".HHSSSKSH.", ".HHSSSSS..", ".HHDDDDH..", ".HSDDDDS..", "..DDDDDD..", ".DDDDDDDD.", ".DDDDDDDD.", "..SS..SS..", "..SS..SS..", "..OO..OO.."], "pal": {"H": "#3a1f12", "R": "#ff6fae", "S": "#f2c29b", "K": "#111111", "D": "#e0559f", "O": "#5a2a1a"}}, "ranger": {"rows": ["..NNNNN...", ".NNNNNNN..", ".NNSSSSN..", ".NNSSKSN..", "..NSSSSN..", "..NNNNNN..", ".SNBBBBNS.", ".SNBBBBNS.", "..BBBBBB..", "..PPPPPP..", "..PP..PP..", "..PP..PP..", "..OO..OO.."], "pal": {"N": "#2f8f43", "S": "#f2c29b", "K": "#111111", "B": "#8a5a2b", "P": "#4a3a22", "O": "#2a1a0e"}}, "knight": {"rows": ["...RR.....", "..GGGGGG..", ".GGGGGGGG.", ".GGKKKKGG.", ".GGGGGGGG.", "..GGGGGG..", ".WGGGGGGW.", ".SGGGGGGS.", "..GGYYGG..", "..GGGGGG..", "..GG..GG..", "..GG..GG..", "..OO..OO.."], "pal": {"G": "#9aa3b8", "W": "#dfe6f5", "R": "#d6342c", "K": "#1a1a2e", "S": "#f2c29b", "Y": "#ffd54a", "O": "#3a2412"}}, "mage": {"rows": ["....MM....", "...MMMM...", "..MMMMMM..", ".MMMMMMMM.", "..HSSSSH..", "..HSSKSH..", "..VVVVVV..", ".SVVVVVVS.", "..VVYYVV..", "..VVVVVV..", ".VVVVVVVV.", ".VVVVVVVV.", "..OO..OO.."], "pal": {"M": "#5b2fb0", "V": "#7a3fd0", "H": "#e8d9ff", "Y": "#ffd54a", "S": "#f2c29b", "K": "#111111", "O": "#3a2412"}}, "ninja": {"rows": ["..KKKKK...", ".KKKKKKK..", ".RRRRRRR..", ".KKSESSK..", "..KKKKKK..", "..KKKKKK..", ".KKKKKKKK.", ".SKKRRKKS.", "..KKKKKK..", "..KKKKKK..", "..KK..KK..", "..KK..KK..", "..RR..RR.."], "pal": {"K": "#2f3558", "R": "#d6342c", "S": "#f2c29b", "E": "#ffffff"}}}, BOSSDATA = [{"rows": [".......Y..Y..Y......", ".......YYYYYYY......", ".....BBBBBBBBBBB....", "...BBBBBBBBBBBBBBB..", "..BBBLLBBBBBBBBBBBB.", "..BBBBBBBBBBBBBBBBB.", ".BBBBKKBBBBBBKKBBBB.", ".BBBBKKBBBBBBKKBBBB.", ".BBBBBBBBBBBBBBBBBB.", ".BBBBBBBBWWWWBBBBBB.", ".BBBBBBBBBBBBBBBBBB.", "..BBBBBBBBBBBBBBBB..", "...BBBBBBBBBBBBBB...", "....DDDDDDDDDDDD...."], "pal": {"Y": "#ffd54a", "B": "#4aa3ff", "L": "#b9e0ff", "K": "#0b1d3d", "W": "#ffffff", "D": "#2b6fb0"}}, {"rows": [".....GGGGGGGGGG.....", "....GGGGGGGGGGGG....", "....GGEEGGGGEEGG....", "....GGGGGGGGGGGG....", "....GGGMMMMMMGGG....", ".GGGGGGGGGGGGGGGGGG.", "GGGGGGGGGGGGGGGGGGGG", "GGGGGGGGGGGGGGGGGGGG", "GGG.GGGGGGGGGGGG.GGG", "GGG.GGGGGGGGGGGG.GGG", "GGG.GGGGGGGGGGGG.GGG", "....GGGGGGGGGGGG....", "....GGGG....GGGG....", "....GGGG....GGGG....", "...GGGGGG..GGGGGG...", "...DDDDDD..DDDDDD..."], "pal": {"G": "#8d93a8", "E": "#ffa726", "M": "#3a3f55", "D": "#5b6075"}}, {"rows": ["..H..............H..", "..HH............HH..", "...HRRRRRRRRRRRRH...", "....RRRRRRRRRRRR....", "....RRWKRRRRWKRR....", "....RRRRRRRRRRRR....", ".....RRRRYYRRRR.....", "..PPP.RRRRRRRR.PPP..", ".PPPPPRRYYYYRRPPPPP.", "PPPPPPRRYYYYRRPPPPPP", ".PPPPPRRYYYYRRPPPPP.", "..PPP.RRYYYYRR.PPP..", "......RRRRRRRR......", ".....RRRR..RRRR.....", ".....RRRR..RRRR.....", "....DDDDD..DDDDD...."], "pal": {"H": "#f5e6c8", "R": "#e5484d", "W": "#ffffff", "K": "#111111", "Y": "#ffd54a", "P": "#7a3fd0", "D": "#7a1f1f"}}], SLIMEDATA = {"rows": ["....BBBB....", "..BBBBBBBB..", ".BBLBBBBBBB.", ".BBBBBBBBBB.", "BBBKBBBBKBBB", "BBBKBBBBKBBB", "BBBBBBBBBBBB", ".BBBBBBBBBB."], "pal": {"B": "#4aa3ff", "L": "#b9e0ff", "K": "#0b1d3d"}};
var THEMES = [
  { name: 'Sunny Meadow', sky: ['#8d8fd6', '#b7a3dd', '#f0b9a4', '#f8d3a0'], m1: '#8b8fc4', m2: '#6c6e8e', hill: '#4f8f3c', night: false, boss: 'Slime King' },
  { name: 'Stone Valley', sky: ['#d9784f', '#ee9a68', '#f5c186', '#fbe3b0'], m1: '#b5736b', m2: '#7a4f5e', hill: '#6b7a3a', night: false, boss: 'Stone Golem' },
  { name: 'Starry Night', sky: ['#04061a', '#0b0f3a', '#1a1a5a', '#2a2272'], m1: '#25285a', m2: '#32356a', hill: '#1f4a2e', night: true, boss: 'Fire Drake' }
];

var cv = document.getElementById('game'), ctx = cv.getContext('2d');
ctx.imageSmoothingEnabled = false;
var $ = function (id) { return document.getElementById(id); };
function esc(s) { return String(s).replace(/[&<>"']/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]; }); }
function rnd(a, b) { return a + Math.random() * (b - a); }
function clamp(v, a, b) { return Math.max(a, Math.min(b, v)); }
function shuffle(a) { for (var i = a.length - 1; i > 0; i--) { var j = Math.floor(Math.random() * (i + 1)); var t = a[i]; a[i] = a[j]; a[j] = t; } return a; }
function hit(a, b) { return a.x < b.x + b.w && a.x + a.w > b.x && a.y < b.y + b.h && a.y + a.h > b.y; }

// ---------- sprites ----------
function sheet(rows, pal, scale) {
  var h = rows.length, w = rows[0].length, c = document.createElement('canvas');
  c.width = w * scale; c.height = h * scale;
  var g = c.getContext('2d');
  for (var y = 0; y < h; y++) for (var x = 0; x < w; x++) { var ch = rows[y][x]; if (ch !== '.') { g.fillStyle = pal[ch]; g.fillRect(x * scale, y * scale, scale, scale); } }
  return c;
}
function flip(c) { var o = document.createElement('canvas'); o.width = c.width; o.height = c.height; var g = o.getContext('2d'); g.translate(c.width, 0); g.scale(-1, 1); g.drawImage(c, 0, 0); return o; }
function tint(c, color) { var o = document.createElement('canvas'); o.width = c.width; o.height = c.height; var g = o.getContext('2d'); g.drawImage(c, 0, 0); g.globalCompositeOperation = 'source-atop'; g.fillStyle = color; g.fillRect(0, 0, o.width, o.height); return o; }
function legsIn(rows) {
  return rows.map(function (r, i) {
    if (i < 10 || r[4] !== '.' || r[5] !== '.') return r;
    var b = '..........'.split('');
    for (var x = 0; x < 10; x++) if (r[x] !== '.') b[x < 5 ? x + 1 : x - 1] = r[x];
    return b.join('');
  });
}
var me = SPR[C.character] || SPR.boy;
var pA = sheet(me.rows, me.pal, 1), pB = sheet(legsIn(me.rows), me.pal, 1);
var PF = { r: [pA, pB], l: [flip(pA), flip(pB)] };
var slimeImg = sheet(SLIMEDATA.rows, SLIMEDATA.pal, 1);
var bossImg = BOSSDATA.map(function (b) { var c = sheet(b.rows, b.pal, 3); return { n: c, w: tint(c, '#ffffff') }; });

// ---------- sound effects ----------
var ac = null;
function beep(f1, f2, dur, type, delay) {
  var t = ac.currentTime + (delay || 0), o = ac.createOscillator(), g = ac.createGain();
  var vol = (window.LQMusic ? window.LQMusic.getVolume() : 0.25) * 0.35;
  o.type = type || 'square'; o.frequency.setValueAtTime(f1, t); o.frequency.linearRampToValueAtTime(f2, t + dur);
  g.gain.setValueAtTime(Math.max(0.0001, vol), t); g.gain.exponentialRampToValueAtTime(0.0001, t + dur);
  o.connect(g); g.connect(ac.destination); o.start(t); o.stop(t + dur + 0.02);
}
function sfx(k) {
  try {
    if (window.LQMusic && (window.LQMusic.isMuted() || window.LQMusic.getVolume() === 0)) return;
    if (!ac) { var AC = window.AudioContext || window.webkitAudioContext; if (!AC) return; ac = new AC(); }
    if (ac.state === 'suspended') ac.resume();
    if (k === 'jump') beep(300, 560, 0.12); else if (k === 'coin') { beep(880, 1100, 0.07); beep(1320, 1320, 0.1, 'square', 0.07); }
    else if (k === 'right') { beep(520, 520, 0.1); beep(780, 780, 0.1, 'square', 0.1); beep(1040, 1040, 0.16, 'square', 0.2); }
    else if (k === 'wrong') { beep(220, 120, 0.3, 'sawtooth'); } else if (k === 'hurt') beep(200, 80, 0.2, 'sawtooth');
    else if (k === 'stomp') beep(400, 150, 0.1); else if (k === 'hit') { beep(150, 60, 0.25, 'square'); }
    else if (k === 'win') { [523, 659, 784, 1046].forEach(function (f, i) { beep(f, f, 0.18, 'square', i * 0.14); }); }
  } catch (e) {}
}

// ---------- state ----------
var pool = [], ST = null, S = {}, P = {}, cam = 0, parts = [], stageDefs = [];
var keys = { left: false, right: false, jump: false };

function newSlime(minX, maxX) { return { x: minX + Math.random() * (maxX - minX - 12), y: GROUND - 8, w: 12, h: 8, minX: minX, maxX: maxX, dir: Math.random() < 0.5 ? -1 : 1, dead: 0 }; }

function buildStage(i, gq, bq) {
  var th = THEMES[i % THEMES.length];
  var st = { i: i, th: th, ground: [], plats: [], gates: [], enemies: [], coins: [], decor: [], bossQs: bq.slice(), bossMax: bq.length, boss: null, wall: null, bossStarted: false };
  var x = 0;
  function seg(len) { var s = { x: x, w: len }; st.ground.push(s); x += len; return s; }
  seg(150);
  gq.forEach(function (q, gi) {
    var a = seg(Math.round(rnd(170, 230)));
    st.enemies.push(newSlime(a.x + 60, a.x + a.w - 30));
    if (i > 0 && gi % 2 === 0) st.enemies.push(newSlime(a.x + 20, a.x + 90));
    if (gi % 2 === 0) { st.plats.push({ x: a.x + 70, y: GROUND - 42, w: 52, h: 8 }); st.coins.push({ x: a.x + 88, y: GROUND - 56 }, { x: a.x + 104, y: GROUND - 56 }); }
    for (var k = 0; k < 3; k++) st.coins.push({ x: a.x + 30 + k * 28, y: GROUND - 16 });
    if (gi % 2 === 1) { st.coins.push({ x: x + 8, y: GROUND - 50 }, { x: x + 20, y: GROUND - 58 }); x += 34; }
    var b = seg(110);
    st.gates.push({ x: b.x + b.w - 24, y: GROUND - 40, w: 12, h: 40, opened: false, anim: 0, cool: 0, q: q });
  });
  seg(70);
  var arena = seg(360); st.arenaX = arena.x; st.width = x;
  function onGround(px) { return st.ground.some(function (g) { return px > g.x + 10 && px < g.x + g.w - 10; }); }
  for (var dx = 24; dx < x - 360; dx += rnd(55, 95)) if (onGround(dx)) st.decor.push({ x: dx, k: Math.random() < 0.6 ? 'tree' : 'bush', s: rnd(0.85, 1.2) });
  for (var ax = st.arenaX + 20; ax < x; ax += rnd(70, 110)) st.decor.push({ x: ax, k: Math.random() < 0.5 ? 'tree' : 'bush', s: rnd(0.85, 1.2) });
  return st;
}

function startGame() {
  var q = shuffle(pool.slice()), n = q.length;
  var stages = n >= 18 ? 3 : (n >= 10 ? 2 : 1);
  var per = Math.min(8, Math.floor(n / stages)), boss = Math.max(2, Math.floor(per * 0.4)), gates = per - boss;
  stageDefs = [];
  for (var i = 0; i < stages; i++) stageDefs.push({ gq: q.splice(0, gates), bq: q.splice(0, boss) });
  S = { phase: 'intro', si: 0, stages: stages, hearts: 3, score: 0, correct: 0, answered: 0, wrong: 0, seconds: 0, streak: 0, bosses: 0, inv: 0, shake: 0, anim: null };
  loadStage(0);
  showIntro();
}
function loadStage(i) { S.si = i; ST = buildStage(i, stageDefs[i].gq, stageDefs[i].bq); parts = []; P = { x: 30, y: GROUND - 13, w: 8, h: 13, vx: 0, vy: 0, dir: 1, onGround: false, anim: 0, safeX: 30, coyote: 0, held: false, atk: 0 }; cam = 0; }

// ---------- overlays and question box ----------
function overlay(html) { var o = $('overlay'); o.innerHTML = '<div class="card">' + html + '</div>'; o.hidden = false; }
function hideOverlay() { $('overlay').hidden = true; }
function setStatus(t) { var s = $('status'); if (s) s.textContent = t; }
function fmt(sec) { sec = Math.floor(sec); return Math.floor(sec / 60) + ':' + ('0' + (sec % 60)).slice(-2); }

function showIntro() {
  var th = ST.th;
  overlay('<h2>Stage ' + (S.si + 1) + ' of ' + S.stages + '</h2><p class="big">' + esc(th.name) + '</p><p>Walk to the gates and answer the questions to open them. Defeat <b>' + esc(th.boss) + '</b> at the end!</p>' +
    '<p class="keys">Move: ← → or A D &nbsp; Jump: Space or ↑ &nbsp; Answer: click or 1-4</p><p>Jump on slimes to squash them. Avoid the pits!</p><button class="px px-green" id="go">Start</button>');
  S.phase = 'intro';
  $('go').onclick = function () { hideOverlay(); S.phase = 'play'; $('game').focus(); };
}

var askCb = null, askOpen = false;
function ask(q, tag, cb) {
  var opts = q.options.map(function (o, i) { return { t: o, ok: i === q.answer }; });
  shuffle(opts);
  $('qtag').textContent = tag; $('qtext').textContent = q.q;
  var box = $('qopts'); box.innerHTML = '';
  opts.forEach(function (o, i) {
    var b = document.createElement('button'); b.type = 'button'; b.className = 'qopt'; b.innerHTML = '<span class="k">' + (i + 1) + '</span><span>' + esc(o.t) + '</span>';
    b.onclick = function () { pick(i); }; box.appendChild(b);
  });
  askCb = { opts: opts, cb: cb, done: false };
  $('qbox').hidden = false; askOpen = true;
}
function pick(i) {
  if (!askCb || askCb.done) return;
  askCb.done = true;
  var btns = $('qopts').children, ok = askCb.opts[i].ok;
  for (var k = 0; k < btns.length; k++) { if (askCb.opts[k].ok) btns[k].classList.add('good'); }
  if (!ok) btns[i].classList.add('bad');
  S.answered++;
  if (ok) { S.correct++; S.streak++; S.score += 100 + Math.min(50, S.streak * 10); sfx('right'); } else { S.wrong++; S.streak = 0; sfx('wrong'); }
  var cb = askCb.cb;
  setTimeout(function () { $('qbox').hidden = true; askOpen = false; askCb = null; cb(ok); }, ok ? 800 : 1500);
}

// ---------- gameplay ----------
function solids() {
  var s = [];
  ST.ground.forEach(function (g) { s.push({ x: g.x, y: GROUND, w: g.w, h: 70 }); });
  ST.plats.forEach(function (p) { s.push(p); });
  ST.gates.forEach(function (g) { if (!g.opened) s.push(g); });
  if (ST.wall) s.push(ST.wall);
  return s;
}
function move(e, dt, sol) {
  e.x += e.vx * dt;
  sol.forEach(function (s) { if (hit(e, s)) { if (e.vx > 0) e.x = s.x - e.w; else if (e.vx < 0) e.x = s.x + s.w; } });
  e.y += e.vy * dt; e.onGround = false;
  sol.forEach(function (s) { if (hit(e, s)) { if (e.vy > 0) { e.y = s.y - e.h; e.onGround = true; } else if (e.vy < 0) e.y = s.y + s.h; e.vy = 0; } });
}
function burst(x, y, color, n) { for (var i = 0; i < n; i++) parts.push({ x: x, y: y, vx: rnd(-70, 70), vy: rnd(-110, -20), life: rnd(0.3, 0.7), c: color }); }

function hurt(kx) {
  if (S.inv > 0 || S.phase === 'over') return;
  S.hearts--; S.inv = 1.4; S.shake = 0.25; S.streak = 0; sfx('hurt');
  P.vy = -130; P.vx = kx || 0;
  if (S.hearts <= 0) endGame(false);
}
function respawn() { P.x = Math.max(20, P.safeX - 16); P.y = GROUND - 14; P.vx = 0; P.vy = 0; }

function update(dt) {
  var camTarget = ST.bossStarted ? ST.width - W : clamp(P.x - W / 2 + P.w / 2, 0, ST.width - W);
  cam = ST.bossStarted ? cam + (camTarget - cam) * Math.min(1, dt * 6) : camTarget;
  if (S.inv > 0) S.inv -= dt; if (S.shake > 0) S.shake -= dt;
  parts = parts.filter(function (p) { p.life -= dt; p.x += p.vx * dt; p.y += p.vy * dt; p.vy += 400 * dt; return p.life > 0; });
  if (S.phase === 'play' || S.phase === 'question' || S.phase === 'bossintro' || S.phase === 'bossq' || S.phase === 'bossanim') S.seconds += dt;
  if (S.phase === 'bossanim') return bossAnim(dt);
  if (S.phase === 'bossintro') { S.timer -= dt; if (S.timer <= 0) nextBossRound(); return; }
  if (S.phase !== 'play') return;

  var ax = (keys.right ? 1 : 0) - (keys.left ? 1 : 0);
  P.vx = ax * RUN; if (ax) P.dir = ax;
  if (keys.jump && !P.held && (P.onGround || P.coyote > 0)) { P.vy = JUMP; P.onGround = false; P.coyote = 0; P.held = true; sfx('jump'); }
  if (!keys.jump) P.held = false;
  P.vy = Math.min(MAXFALL, P.vy + G * dt);
  move(P, dt, solids());
  if (P.x < 0) P.x = 0;
  if (P.onGround) {
    P.coyote = 0.08;
    var onSeg = ST.ground.filter(function (g) { return P.x > g.x + 22 && P.x + P.w < g.x + g.w - 22; })[0];
    if (onSeg && !ST.gates.some(function (g) { return !g.opened && Math.abs(g.x - P.x) < 30; })) P.safeX = P.x;
  } else P.coyote -= dt;
  P.anim += dt * (P.onGround && Math.abs(P.vx) > 5 ? 1 : 0);
  if (P.y > H + 20) { S.hearts--; S.inv = 1.4; S.streak = 0; sfx('hurt'); if (S.hearts <= 0) return endGame(false); respawn(); }

  ST.enemies.forEach(function (e) {
    if (e.dead) { e.dead -= dt; return; }
    e.x += e.dir * 20 * dt; if (e.x < e.minX) e.dir = 1; if (e.x + e.w > e.maxX) e.dir = -1;
    if (hit(P, e)) {
      if (P.vy > 40 && (P.y + P.h) - e.y < 9) { e.dead = 0.35; P.vy = -170; S.score += 25; sfx('stomp'); burst(e.x + 6, e.y + 4, '#4aa3ff', 8); }
      else hurt(P.x < e.x ? -90 : 90);
    }
  });
  ST.enemies = ST.enemies.filter(function (e) { return !(e.dead && e.dead <= 0); });
  ST.coins = ST.coins.filter(function (c) { if (hit(P, { x: c.x - 4, y: c.y - 4, w: 8, h: 8 })) { S.score += 10; sfx('coin'); burst(c.x, c.y, '#ffd54a', 5); return false; } return true; });

  ST.gates.forEach(function (g) {
    if (g.cool > 0) g.cool -= dt;
    if (g.opened) { g.anim = Math.min(1, g.anim + dt * 2); return; }
    if (g.cool <= 0 && P.x + P.w >= g.x - 3 && P.x <= g.x + g.w + 3 && P.y + P.h > g.y) {
      S.phase = 'question'; P.vx = 0;
      ask(g.q, 'Gate question', function (ok) {
        if (ok) { g.opened = true; burst(g.x + 6, g.y + 20, '#c8c8d8', 14); S.phase = 'play'; }
        else { g.cool = 1.2; P.x -= 24; S.hearts--; S.shake = 0.25; S.inv = 1; if (S.hearts <= 0) { endGame(false); } else { S.phase = 'play'; } }
      });
    }
  });

  if (!ST.bossStarted && P.x > ST.arenaX + 60) startBoss();
}

// ---------- boss fight ----------
function startBoss() {
  ST.bossStarted = true; ST.wall = { x: ST.arenaX + 10, y: 0, w: 10, h: GROUND };
  var bt = ST.i % 3, img = bossImg[bt].n;
  ST.boss = { x: ST.arenaX + 270, y: GROUND - img.height, w: img.width, h: img.height, hp: ST.bossMax, max: ST.bossMax, flash: 0, t: bt, dead: 0, bx: ST.arenaX + 270 };
  P.x = ST.arenaX + 60; P.y = GROUND - P.h; P.vx = 0; P.vy = 0; P.dir = 1; P.onGround = true;
  S.phase = 'bossintro'; S.timer = 1.6;
}
function nextBossRound() {
  if (ST.boss.hp <= 0) return;
  S.phase = 'bossq';
  ask(ST.bossQs[0], 'Boss fight: ' + THEMES[ST.i % 3].boss, function (ok) {
    if (ok) { ST.bossQs.shift(); S.anim = { kind: 'attack', t: 0, done: false }; }
    else { ST.bossQs.push(ST.bossQs.shift()); S.anim = { kind: 'bossatk', t: 0, done: false }; }
    S.phase = 'bossanim';
  });
}
function bossAnim(dt) {
  var a = S.anim, b = ST.boss; a.t += dt; if (b.flash > 0) b.flash -= dt;
  var home = ST.arenaX + 60;
  if (a.kind === 'attack') {
    P.atk = a.t < 0.7 ? 1 : 0;
    P.x = a.t < 0.3 ? home + (b.bx - 50 - home) * (a.t / 0.3) : (a.t < 0.55 ? b.bx - 50 : b.bx - 50 + (home - b.bx + 50) * ((a.t - 0.55) / 0.35));
    if (!a.done && a.t >= 0.3) { a.done = true; b.hp--; b.flash = 0.3; S.shake = 0.2; sfx('hit'); burst(b.x + 10, b.y + 24, '#ffffff', 14); }
    if (a.t >= 0.9) { P.x = home; P.atk = 0; if (b.hp <= 0) { S.anim = { kind: 'die', t: 0 }; sfx('win'); } else { S.phase = 'bossintro'; S.timer = 0.2; } }
  } else if (a.kind === 'bossatk') {
    b.x = a.t < 0.35 ? b.bx - (b.bx - home - 70) * (a.t / 0.35) : (a.t < 0.55 ? home + 70 : home + 70 + (b.bx - home - 70) * ((a.t - 0.55) / 0.35));
    if (!a.done && a.t >= 0.35) { a.done = true; S.hearts--; S.inv = 0.8; S.shake = 0.3; sfx('hurt'); burst(P.x + 4, P.y + 6, '#e5484d', 10); if (S.hearts <= 0) return endGame(false); }
    if (a.t >= 0.9) { b.x = b.bx; S.phase = 'bossintro'; S.timer = 0.2; }
  } else if (a.kind === 'die') {
    if (a.t > 1.4) { S.bosses++; S.score += 300; stageClear(); }
  }
}
function stageClear() {
  S.score += 200; S.phase = 'stageclear';
  var last = S.si >= S.stages - 1;
  if (last) { endGame(true); return; }
  overlay('<h2>Stage clear!</h2><p class="big">' + esc(ST.th.name) + ' complete</p><p>Hearts: ' + S.hearts + ' &nbsp; Score: ' + S.score + '</p><button class="px px-green" id="nx">Next stage</button>');
  $('nx').onclick = function () { hideOverlay(); loadStage(S.si + 1); showIntro(); };
}

// ---------- ending and saving the score ----------
function endGame(win) {
  if (S.phase === 'over' || S.phase === 'victory') return;
  S.phase = win ? 'victory' : 'over';
  if (win) { S.score += Math.max(0, Math.round(300 - S.seconds)); sfx('win'); }
  var perfect = win && S.wrong === 0;
  var html = '<h2>' + (win ? 'You did it!' : 'Game over') + '</h2><p class="big">' + esc(C.docTitle) + '</p>' +
    '<div class="stats2"><div><b>' + S.score + '</b>Score</div><div><b>' + S.correct + '/' + S.answered + '</b>Correct</div><div><b>' + fmt(S.seconds) + '</b>Time</div></div>' +
    '<p id="xp">Saving your score...</p><div class="btns"><button class="px px-green" id="again">Play again</button><a class="px px-stone" href="' + C.backUrl + '">Dashboard</a><a class="px px-gold" href="/scores">My scores</a></div>';
  overlay(html);
  $('again').onclick = function () { hideOverlay(); startGame(); };
  if (S.answered < 1) { $('xp').textContent = ''; return; }
  fetch(C.saveUrl, { method: 'POST', headers: { 'Content-Type': 'application/json' }, credentials: 'same-origin',
    body: JSON.stringify({ csrf: C.csrf, doc_type: C.docType, doc_id: C.docId, score: S.score, correct: S.correct, total: S.answered, seconds: Math.floor(S.seconds), bosses: S.bosses, perfect: perfect }) })
    .then(function (r) { return r.json(); })
    .then(function (d) {
      var el = $('xp'); if (!el) return;
      if (d && d.ok) el.innerHTML = '+' + d.xp_gained + ' XP' + (d.leveled_up ? ' &nbsp; <b class="lv">LEVEL UP! You are now level ' + d.level + '</b>' : ' &nbsp; Level ' + d.level);
      else el.textContent = 'Your score could not be saved.';
    }).catch(function () { var el = $('xp'); if (el) el.textContent = 'Your score could not be saved.'; });
}

// ---------- drawing ----------
var skyCache = {};
function sky(th) {
  var k = th.name; if (skyCache[k]) return skyCache[k];
  var g = ctx.createLinearGradient(0, 0, 0, GROUND); [0, 0.4, 0.75, 1].forEach(function (s, i) { g.addColorStop(s, th.sky[i]); }); return (skyCache[k] = g);
}
function ridge(color, par, base, amp, f1, f2) {
  ctx.fillStyle = color;
  for (var sx = 0; sx < W; sx += 4) {
    var wx = sx + cam * par, h = base - amp * (0.5 + 0.5 * Math.sin(wx * f1) + 0.35 * Math.sin(wx * f2 + 1.3));
    ctx.fillRect(sx, Math.round(h), 4, GROUND - Math.round(h) + 2);
  }
}
function tree(x, y, s, night) {
  var th = Math.round(22 * s), tw = 6;
  ctx.fillStyle = '#5a3a1c'; ctx.fillRect(x, y - th, tw, th);
  var c1 = night ? '#1f5a2b' : '#2f8f43', c2 = night ? '#164221' : '#237a37';
  ctx.fillStyle = c2; ctx.fillRect(x - 10, y - th - 6, 26, 14); ctx.fillStyle = c1; ctx.fillRect(x - 7, y - th - 14, 20, 12); ctx.fillRect(x - 3, y - th - 20, 12, 8);
}
function bush(x, y, s, night) { ctx.fillStyle = night ? '#1f5a2b' : '#3a9a4a'; ctx.fillRect(x, y - 8 * s, 18 * s, 8 * s); ctx.fillRect(x + 3, y - 12 * s, 12 * s, 5 * s); }

function draw() {
  var th = ST.th;
  ctx.save();
  if (S.shake > 0) ctx.translate(Math.round(rnd(-2, 2)), Math.round(rnd(-2, 2)));
  ctx.fillStyle = sky(th); ctx.fillRect(0, 0, W, H);
  if (th.night) { ctx.fillStyle = '#fff'; for (var i = 0; i < 40; i++) { var sx = (i * 97 + 13) % W, sy = (i * 53 + 7) % 110; if ((i + Math.floor(S.seconds * 2)) % 5) ctx.fillRect(sx, sy, 1, 1); } ctx.fillStyle = '#f4f2e6'; ctx.fillRect(330, 28, 18, 18); }
  else { ctx.fillStyle = '#fff1be'; ctx.fillRect(310 - cam * 0.02, 40, 22, 22); }
  ridge(th.m1, 0.15, 130, 46, 0.011, 0.027);
  ridge(th.m2, 0.3, 146, 30, 0.017, 0.041);
  ctx.fillStyle = th.hill; for (var hx = 0; hx < W; hx += 4) { var hh = 150 - 8 * Math.sin((hx + cam * 0.5) * 0.02); ctx.fillRect(hx, Math.round(hh), 4, GROUND - Math.round(hh) + 2); }

  ctx.translate(-Math.round(cam), 0);
  ST.decor.forEach(function (d) { if (d.x > cam - 30 && d.x < cam + W + 30) (d.k === 'tree' ? tree : bush)(Math.round(d.x), GROUND, d.s, th.night); });
  // ground
  ST.ground.forEach(function (g) {
    if (g.x + g.w < cam - 4 || g.x > cam + W + 4) return;
    ctx.fillStyle = '#7a4a24'; ctx.fillRect(g.x, GROUND, g.w, H - GROUND);
    ctx.fillStyle = '#5e3818'; ctx.fillRect(g.x, GROUND + 28, g.w, H - GROUND - 28); ctx.fillStyle = '#4a2c12'; ctx.fillRect(g.x, GROUND + 50, g.w, H - GROUND - 50);
    for (var x = Math.max(g.x, Math.floor(cam / 8) * 8); x < Math.min(g.x + g.w, cam + W + 8); x += 8) for (var y = GROUND + 8; y < H; y += 8) {
      var hsh = ((x * 73856093) ^ (y * 19349663)) >>> 0; var m = hsh % 9;
      if (m === 0) { ctx.fillStyle = '#946236'; ctx.fillRect(x + 2, y + 2, 3, 3); } else if (m === 1) { ctx.fillStyle = '#4f2f14'; ctx.fillRect(x + 1, y + 3, 4, 2); } else if (m === 2) { ctx.fillStyle = '#8d8f9c'; ctx.fillRect(x + 2, y + 1, 3, 3); }
    }
    ctx.fillStyle = th.night ? '#2f8a35' : '#4caf3d'; ctx.fillRect(g.x, GROUND, g.w, 5); ctx.fillStyle = th.night ? '#1f6b28' : '#2d7a24'; ctx.fillRect(g.x, GROUND + 5, g.w, 2);
  });
  ST.plats.forEach(function (p) { ctx.fillStyle = '#a0693a'; ctx.fillRect(p.x, p.y, p.w, p.h); ctx.fillStyle = '#4caf3d'; ctx.fillRect(p.x, p.y, p.w, 3); ctx.fillStyle = '#7a4a24'; ctx.fillRect(p.x, p.y + p.h - 2, p.w, 2); });
  ST.gates.forEach(function (g) {
    var h = g.opened ? Math.round(g.h * (1 - g.anim)) : g.h; if (h <= 0) return;
    ctx.fillStyle = '#7b7f94'; ctx.fillRect(g.x, g.y + (g.h - h), g.w, h); ctx.fillStyle = '#a8acc0'; ctx.fillRect(g.x, g.y + (g.h - h), 3, h);
    ctx.fillStyle = '#5d6176'; for (var y = g.y + (g.h - h) + 8; y < GROUND; y += 8) ctx.fillRect(g.x, y, g.w, 1);
    if (!g.opened) { ctx.fillStyle = '#ffd54a'; ctx.font = 'bold 10px monospace'; ctx.fillText('?', g.x + 3, g.y + 15 + Math.sin(S.seconds * 4) * 1.5); }
  });
  ST.coins.forEach(function (c) { var by = c.y + Math.sin(S.seconds * 4 + c.x) * 1.5; ctx.fillStyle = '#ffd54a'; ctx.fillRect(c.x - 3, by - 3, 6, 6); ctx.fillStyle = '#fff3b0'; ctx.fillRect(c.x - 2, by - 2, 2, 2); });
  ST.enemies.forEach(function (e) { if (e.dead) { ctx.drawImage(slimeImg, Math.round(e.x), Math.round(e.y + 5), 12, 3); } else ctx.drawImage(slimeImg, Math.round(e.x), Math.round(e.y + (Math.sin(S.seconds * 6 + e.x) > 0.5 ? 1 : 0))); });

  if (ST.boss) {
    var b = ST.boss, bi = bossImg[b.t];
    if (S.anim && S.anim.kind === 'die') { var f = S.anim.t; if (Math.floor(f * 10) % 2 === 0) ctx.drawImage(bi.n, Math.round(b.x), Math.round(b.y + f * 14)); }
    else ctx.drawImage(b.flash > 0 ? bi.w : bi.n, Math.round(b.x), Math.round(b.y + Math.sin(S.seconds * 3) * 2));
  }
  // player
  var blink = S.inv > 0 && Math.floor(S.inv * 12) % 2 === 0;
  if (!blink) {
    var fr = Math.floor(P.anim * 8) % 2, img = (P.dir < 0 ? PF.l : PF.r)[P.onGround ? fr : 0];
    ctx.drawImage(img, Math.round(P.x - 1), Math.round(P.y));
  }
  if (P.atk) { var sx2 = P.x + 8, sy2 = P.y + 2, sw = Math.floor(S.anim ? S.anim.t * 20 : 0) % 2; ctx.fillStyle = '#dfe9ff'; ctx.fillRect(sx2, sy2 + (sw ? 4 : -6), 3, 12); ctx.fillStyle = '#ffd54a'; ctx.fillRect(sx2 - 2, sy2 + 5, 7, 2); ctx.fillStyle = '#fff'; ctx.fillRect(sx2 + 6, sy2 + 2, 10, 2); }
  parts.forEach(function (p) { ctx.fillStyle = p.c; ctx.fillRect(Math.round(p.x), Math.round(p.y), 2, 2); });
  ctx.restore();

  if (ST.boss && ST.bossStarted) {
    var bb = ST.boss, pct = Math.max(0, bb.hp / bb.max);
    ctx.fillStyle = '#000'; ctx.fillRect(100, 8, 200, 14); ctx.fillStyle = '#5a1a1a'; ctx.fillRect(102, 10, 196, 10); ctx.fillStyle = '#e5484d'; ctx.fillRect(102, 10, Math.round(196 * pct), 10);
    ctx.fillStyle = '#fff'; ctx.font = 'bold 8px monospace'; ctx.fillText(THEMES[ST.i % 3].boss, 104, 18);
  }
}

// ---------- HUD, loop, input ----------
function hud() {
  var h = ''; for (var i = 0; i < 3; i++) h += (i < S.hearts ? '❤️' : '🖤');
  $('h-hearts').textContent = h; $('h-score').textContent = S.score; $('h-time').textContent = fmt(S.seconds); $('h-stage').textContent = 'Stage ' + (S.si + 1) + '/' + S.stages;
}
var lastT = 0;
function loop(now) {
  var dt = Math.min(0.033, (now - lastT) / 1000 || 0); lastT = now;
  if (ST) { update(dt); draw(); hud(); }
  requestAnimationFrame(loop);
}
function keyFor(e) { var k = e.key; return (k === 'ArrowLeft' || k === 'a' || k === 'A') ? 'left' : (k === 'ArrowRight' || k === 'd' || k === 'D') ? 'right' : (k === ' ' || k === 'ArrowUp' || k === 'w' || k === 'W') ? 'jump' : null; }
window.addEventListener('keydown', function (e) {
  if (/^(TEXTAREA|INPUT)$/.test(e.target.tagName)) return;
  if (askOpen && /^[1-4]$/.test(e.key)) { pick(parseInt(e.key, 10) - 1); return; }
  var k = keyFor(e); if (k) { keys[k] = true; e.preventDefault(); }
});
window.addEventListener('keyup', function (e) { var k = keyFor(e); if (k) keys[k] = false; });
window.addEventListener('blur', function () { keys.left = keys.right = keys.jump = false; });
[['t-left', 'left'], ['t-right', 'right'], ['t-jump', 'jump']].forEach(function (p) {
  var el = $(p[0]); if (!el) return;
  el.addEventListener('pointerdown', function (e) { e.preventDefault(); keys[p[1]] = true; });
  ['pointerup', 'pointerleave', 'pointercancel'].forEach(function (ev) { el.addEventListener(ev, function () { keys[p[1]] = false; }); });
});

// ---------- loading the reviewer ----------
function fallback(text) {
  overlay('<h2>We need a little help</h2><p>We could not make enough questions from this file automatically. Paste text from your reviewer below (term: meaning lines work best), or play with the demo questions.</p>' +
    '<textarea id="paste" rows="6" placeholder="Heart: pumps blood through the body&#10;Lungs: organs used for breathing"></textarea>' +
    '<div class="btns"><button class="px px-green" id="mk">Make questions</button><button class="px px-gold" id="demo">Use demo questions</button><a class="px px-stone" href="' + C.backUrl + '">Back</a></div><p id="msg"></p>');
  if (text) $('paste').value = text.slice(0, 6000);
  $('mk').onclick = function () { var q = window.LQQ.build($('paste').value); if (q.length < 4) { $('msg').textContent = 'Still not enough. Try more lines like "Term: meaning".'; return; } pool = q; hideOverlay(); startGame(); };
  $('demo').onclick = function () { pool = window.LQQ.build(window.LQQ.demoText); hideOverlay(); startGame(); };
}
async function boot() {
  overlay('<h2>Loading your reviewer</h2><p class="big">' + esc(C.docTitle) + '</p><p id="status">Getting ready...</p>');
  var text = '';
  try {
    if (C.testText) text = C.testText;
    else if (C.mime === 'application/pdf') { var r = await window.LQQ.pdfText(C.rawUrl, setStatus); text = r.text; if (text.replace(/\s/g, '').length < 200) text = await window.LQQ.ocrPdf(r.pdf, setStatus); }
    else text = await window.LQQ.ocrImage(C.rawUrl, setStatus);
  } catch (e) { if (window.console) console.warn(e); }
  setStatus('Making your questions...');
  var q = window.LQQ.build(text);
  if (q.length < 4) return fallback(text);
  pool = q; hideOverlay(); startGame();
}
window.LQGame = { state: function () { return { S: S, P: P, ST: ST, pool: pool }; }, pick: pick, keys: keys };
requestAnimationFrame(loop);
boot();
})();
</script>