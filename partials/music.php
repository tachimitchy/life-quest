<?php /* Life Quest - ambient background music + mute / volume controls.
   Include once per page, right before </body>:
   <?php require __DIR__ . '/../partials/music.php'; ?>
*/ ?>
<style>
  .lq-audio {
    position: fixed;
    right: 14px;
    bottom: 14px;
    z-index: 9999;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 12px;
    background: #12183a;
    border: 4px solid #000;
    box-shadow: inset -4px -4px 0 #4b5266, inset 4px 4px 0 #8b93a7, 4px 4px 0 rgba(0, 0, 0, 0.45);
    font-family: "Press Start 2P", "Courier New", monospace;
  }
  .lq-audio button {
    font-family: inherit;
    font-size: 0.6rem;
    color: #fff;
    background: #4caf3d;
    border: 3px solid #000;
    box-shadow: inset -3px -3px 0 #2d7a24, inset 3px 3px 0 #8be07a;
    padding: 8px 10px;
    min-width: 76px;
    cursor: pointer;
    text-shadow: 2px 2px 0 rgba(0, 0, 0, 0.5);
  }
  .lq-audio button.is-muted {
    background: #e5484d;
    box-shadow: inset -3px -3px 0 #8f1d22, inset 3px 3px 0 #ff9a9d;
  }
  .lq-audio button:focus-visible,
  .lq-audio input:focus-visible { outline: 3px dashed #ffd54a; outline-offset: 3px; }
  .lq-audio input[type="range"] {
    width: 90px;
    height: 8px;
    accent-color: #ffd54a;
    cursor: pointer;
  }
  @media (max-width: 420px) {
    .lq-audio { right: 8px; bottom: 8px; padding: 6px 8px; gap: 6px; }
    .lq-audio input[type="range"] { width: 64px; }
  }
</style>

<div class="lq-audio" role="group" aria-label="Music controls">
  <button type="button" id="lq-mute" aria-pressed="false">♪ ON</button>
  <input type="range" id="lq-vol" min="0" max="100" step="1" value="35" aria-label="Music volume" />
</div>

<script>
(function () {
  var KEY = 'lq_audio';
  var state = { vol: 0.35, muted: false };
  try {
    var saved = JSON.parse(localStorage.getItem(KEY));
    if (saved) {
      if (typeof saved.vol === 'number') state.vol = Math.min(1, Math.max(0, saved.vol));
      state.muted = !!saved.muted;
    }
  } catch (e) {}

  var muteBtn = document.getElementById('lq-mute');
  var volEl = document.getElementById('lq-vol');

  function save() {
    try { localStorage.setItem(KEY, JSON.stringify(state)); } catch (e) {}
  }

  var AC = window.AudioContext || window.webkitAudioContext;
  if (!AC) { document.querySelector('.lq-audio').style.display = 'none'; return; }

  var ctx = new AC();
  var master = ctx.createGain();
  var padBus = ctx.createGain();
  var pluckBus = ctx.createGain();
  var delay = ctx.createDelay(1.0);
  var feedback = ctx.createGain();
  var wet = ctx.createGain();

  padBus.gain.value = 0.5;
  pluckBus.gain.value = 0.5;
  delay.delayTime.value = 0.42;
  feedback.gain.value = 0.38;
  wet.gain.value = 0.45;

  padBus.connect(master);
  pluckBus.connect(master);
  pluckBus.connect(delay);
  delay.connect(feedback);
  feedback.connect(delay);
  delay.connect(wet);
  wet.connect(master);
  master.connect(ctx.destination);

  function applyVolume() {
    // keep it gentle: max slider = 0.5 gain
    var target = state.muted ? 0 : state.vol * 0.5;
    master.gain.setTargetAtTime(target, ctx.currentTime, 0.08);
    volEl.value = Math.round(state.vol * 100);
    muteBtn.textContent = state.muted || state.vol === 0 ? '♪ OFF' : '♪ ON';
    muteBtn.classList.toggle('is-muted', state.muted || state.vol === 0);
    muteBtn.setAttribute('aria-pressed', String(state.muted));
  }

  // Am - F - C - G  (chill adventure feel)
  var chords = [
    [110.00, 130.81, 164.81, 220.00],
    [87.31, 130.81, 174.61, 220.00],
    [130.81, 164.81, 196.00, 261.63],
    [98.00, 146.83, 196.00, 246.94]
  ];
  var CHORD_LEN = 8;     // seconds per chord
  var NOTE_LEN = 0.5;    // seconds per arpeggio note
  var pattern = [0, 2, 1, 3, 2, 1, 3, 2, 0, 2, 1, 3, 2, 3, 1, 2];
  var nextChordTime = 0;
  var chordIndex = 0;

  function playPad(freq, t, dur) {
    [['sine', 0], ['triangle', 4]].forEach(function (cfg) {
      var o = ctx.createOscillator();
      var g = ctx.createGain();
      var f = ctx.createBiquadFilter();
      o.type = cfg[0];
      o.frequency.value = freq;
      o.detune.value = cfg[1];
      f.type = 'lowpass';
      f.frequency.value = 900;
      g.gain.setValueAtTime(0.0001, t);
      g.gain.linearRampToValueAtTime(0.09, t + 2.2);
      g.gain.linearRampToValueAtTime(0.0001, t + dur + 1.5);
      o.connect(f); f.connect(g); g.connect(padBus);
      o.start(t);
      o.stop(t + dur + 1.6);
    });
  }

  function playPluck(freq, t) {
    var o = ctx.createOscillator();
    var g = ctx.createGain();
    o.type = 'triangle';
    o.frequency.value = freq;
    g.gain.setValueAtTime(0.0001, t);
    g.gain.exponentialRampToValueAtTime(0.14, t + 0.02);
    g.gain.exponentialRampToValueAtTime(0.0001, t + 1.6);
    o.connect(g); g.connect(pluckBus);
    o.start(t);
    o.stop(t + 1.7);
  }

  function scheduleChord(t) {
    var chord = chords[chordIndex % chords.length];
    chord.forEach(function (n) { playPad(n, t, CHORD_LEN); });
    var steps = CHORD_LEN / NOTE_LEN;
    for (var i = 0; i < steps; i++) {
      // leave a few gaps so it breathes
      if (i % 8 === 7 || (i % 4 === 3 && Math.random() < 0.4)) continue;
      var tone = chord[pattern[i % pattern.length] % chord.length] * 2;
      playPluck(tone, t + i * NOTE_LEN);
    }
    chordIndex++;
  }

  function scheduler() {
    if (ctx.state !== 'running') return;
    if (nextChordTime < ctx.currentTime) nextChordTime = ctx.currentTime + 0.1;
    while (nextChordTime < ctx.currentTime + 4) {
      scheduleChord(nextChordTime);
      nextChordTime += CHORD_LEN;
    }
  }
  setInterval(scheduler, 1000);

  // Browsers block sound until the user interacts, so we try now
  // and fall back to the first click / key press / touch.
  var gestureEvents = ['pointerdown', 'keydown', 'touchstart'];
  function unlock() {
    ctx.resume().then(function () {
      if (ctx.state === 'running') {
        gestureEvents.forEach(function (ev) { document.removeEventListener(ev, unlock, true); });
        scheduler();
      }
    });
  }
  gestureEvents.forEach(function (ev) { document.addEventListener(ev, unlock, true); });
  ctx.resume().then(function () { if (ctx.state === 'running') unlock(); }).catch(function () {});

  // Pause when the tab is hidden
  document.addEventListener('visibilitychange', function () {
    if (document.hidden) { ctx.suspend(); } else { ctx.resume(); }
  });

  muteBtn.addEventListener('click', function () {
    if (state.vol === 0) { state.vol = 0.35; state.muted = false; }
    else { state.muted = !state.muted; }
    applyVolume(); save();
  });

  volEl.addEventListener('input', function () {
    state.vol = volEl.value / 100;
    if (state.vol > 0) state.muted = false;
    applyVolume(); save();
  });

  applyVolume();
})();
</script>