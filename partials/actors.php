<div id="lq-actors" class="lq-actors" aria-hidden="true"></div>
<script>
// A hero with a sword and an explorer girl walk toward each other, stop to chat when they meet,
// and can be grabbed with the mouse (or a finger). Each click makes them react with an emoji.
(function () {
  var layer = document.getElementById('lq-actors');
  var groundEl = document.querySelector('.scene .ground');
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var W = 72, H = 78;
  var emotes = ['😠', '😡', '💢', '😤', '🥺', '😂', '❤️', '😮'];

  function make(cls, dir, x, speed) {
    var el = document.createElement('div');
    el.className = 'actor' + (dir < 0 ? ' left' : '');
    el.innerHTML = '<div class="flipper"><i class="spr ' + cls + '"></i></div><div class="bubble" hidden></div>';
    layer.appendChild(el);
    return { el: el, spr: el.querySelector('.spr'), bub: el.querySelector('.bubble'), x: x, y: 0, dir: dir,
             speed: speed, state: 'walk', vy: 0, hop: 0, clicks: 0, bubUntil: 0, drag: null, moved: false, ox: 0, oy: 0 };
  }

  var hero = make('spr-hero', 1, reduce ? window.innerWidth * 0.12 : -90, 38);
  var girl = make('spr-girl', -1, reduce ? window.innerWidth * 0.7 : window.innerWidth + 40, 32);
  var actors = [hero, girl];
  var cooldown = 0, talking = false;

  function groundTop() { return groundEl ? groundEl.getBoundingClientRect().top : window.innerHeight - 90; }

  function say(a, text, ms, emoji) {
    a.bub.textContent = text;
    a.bub.className = 'bubble' + (emoji ? ' emo' : '');
    a.bub.hidden = false;
    a.bubUntil = performance.now() + ms;
  }

  function setWalking(a, on) { a.spr.classList.toggle('walking', on && !reduce); }

  // ----- conversation when they meet -----
  var script = [[0, 'Hi there! 👋'], [1, 'Hey! Nice sword!'], [0, 'Thanks! ⚔️'], [1, 'Good luck studying! ⭐']];
  function startTalk() {
    talking = true;
    [hero, girl].forEach(function (a) { a.state = 'talk'; setWalking(a, false); });
    var i = 0;
    (function next() {
      if (hero.state !== 'talk' || girl.state !== 'talk') return endTalk();
      if (i >= script.length) return endTalk();
      var line = script[i++];
      say(line[0] === 0 ? hero : girl, line[1], 1300, false);
      (line[0] === 0 ? girl : hero).bub.hidden = true;
      setTimeout(next, 1400);
    })();
  }
  function endTalk() {
    talking = false;
    cooldown = performance.now() + 14000;
    [hero, girl].forEach(function (a) {
      if (a.state === 'talk') { a.state = 'walk'; setWalking(a, true); }
      if (a.bubUntil < performance.now() + 100) a.bub.hidden = true;
    });
  }

  // ----- click and drag -----
  actors.forEach(function (a) {
    a.el.addEventListener('pointerdown', function (ev) {
      ev.preventDefault();
      try { a.el.setPointerCapture(ev.pointerId); } catch (e) {}
      a.drag = { x: ev.clientX, y: ev.clientY };
      a.ox = ev.clientX - a.x; a.oy = ev.clientY - a.y; a.moved = false;
    });
    a.el.addEventListener('pointermove', function (ev) {
      if (!a.drag) return;
      if (!a.moved && Math.hypot(ev.clientX - a.drag.x, ev.clientY - a.drag.y) > 6) {
        a.moved = true; a.state = 'drag'; setWalking(a, false); say(a, '😮', 600000, true);
      }
      if (a.moved) { a.x = ev.clientX - a.ox; a.y = ev.clientY - a.oy; }
    });
    function release() {
      if (!a.drag) return;
      a.drag = null;
      if (a.moved) { a.state = 'fall'; a.vy = 0; say(a, '😵', 1200, true); }
      else {
        say(a, emotes[a.clicks++ % emotes.length], 1400, true);   // first clicks are angry, then cute
        a.hop = 16;
      }
    }
    a.el.addEventListener('pointerup', release);
    a.el.addEventListener('pointercancel', release);
  });

  // ----- main loop -----
  var last = performance.now();
  function frame(now) {
    var dt = Math.min(0.05, (now - last) / 1000); last = now;
    var gt = groundTop(), floor = gt - H + 4, vw = window.innerWidth;

    actors.forEach(function (a) {
      if (a.state === 'walk' && !reduce) {
        a.x += a.dir * a.speed * dt;
        if (a.dir > 0 && a.x > vw + 20) a.x = -W - 20;
        if (a.dir < 0 && a.x < -W - 20) a.x = vw + 20;
        if (!a.spr.classList.contains('walking')) setWalking(a, true);
      }
      if (a.state === 'fall') {
        a.vy += 900 * dt; a.y += a.vy * dt;
        if (a.y >= floor) { a.y = floor; a.vy = 0; a.state = 'walk'; setWalking(a, true); }
      } else if (a.state !== 'drag') {
        a.y = floor;
      }
      if (a.hop > 0) a.hop = Math.max(0, a.hop - 70 * dt);
      a.el.style.left = a.x + 'px';
      a.el.style.top = (a.y - a.hop) + 'px';
      if (a.bubUntil && now > a.bubUntil && !(talking && a.state === 'talk')) { a.bub.hidden = true; a.bubUntil = 0; }
    });

    // do they meet? (hero walks right, girl walks left, both on the ground)
    var gap = girl.x - hero.x;
    if (!talking && !reduce && now > cooldown && hero.state === 'walk' && girl.state === 'walk' &&
        hero.dir > 0 && girl.dir < 0 && gap > 10 && gap < 64) startTalk();

    requestAnimationFrame(frame);
  }
  requestAnimationFrame(frame);
})();
</script>