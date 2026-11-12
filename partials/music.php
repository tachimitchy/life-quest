<div class="lq-music" id="lq-music">
  <button type="button" id="lq-mute" aria-label="Mute or unmute the music" title="Music">🔊</button>
  <input type="range" id="lq-vol" min="0" max="100" step="1" aria-label="Music volume" />
</div>
<style>
  .lq-music { position: fixed; right: 12px; bottom: 12px; z-index: 60; display: flex; align-items: center; gap: 0.5rem; padding: 0.25rem 0.6rem 0.25rem 0.35rem;
    background: #12183a; border: 3px solid #000; box-shadow: inset -3px -3px 0 #121741, inset 3px 3px 0 #2e377a, 0 0 0 2px #8b93a7; }
  .lq-music button { font-size: 1.3rem; line-height: 1; padding: 0.15rem 0.3rem; cursor: pointer; background: transparent; border: 0; }
  .lq-music button:focus-visible { outline: 3px dashed #ffd54a; outline-offset: 2px; }
  .lq-music input[type="range"] { width: 90px; accent-color: #4caf3d; }
  @media (max-width: 480px) { .lq-music input[type="range"] { width: 64px; } }
</style>
<script>
// Chill adventure ambience made with the browser's own sound engine (no music files needed).
// Browsers only allow sound after the first click, tap or key press, so it starts then.
(function () {
  function load(k, d) { try { var v = localStorage.getItem(k); return v === null ? d : v; } catch (e) { return d; } }
  function save(k, v) { try { localStorage.setItem(k, v); } catch (e) {} }

  var vol = parseFloat(load('lq_vol', '0.25')); if (!(vol >= 0 && vol <= 1)) vol = 0.25;
  var muted = load('lq_muted', '0') === '1';
  var AC = window.AudioContext || window.webkitAudioContext;
  var ctx = null, master = null, bus = null, next = 0, step = 0;

  var btn = document.getElementById('lq-mute'), slider = document.getElementById('lq-vol');
  function level() { return muted ? 0 : vol * 0.5; }
  function sync() {
    if (btn) btn.textContent = (muted || vol === 0) ? '🔇' : (vol < 0.4 ? '🔉' : '🔊');
    if (slider) slider.value = Math.round(vol * 100);
    window.dispatchEvent(new CustomEvent('lq-audio', { detail: { vol: vol, muted: muted } }));
  }
  function apply() { if (master && ctx) master.gain.setTargetAtTime(level(), ctx.currentTime, 0.08); sync(); }

  window.LQMusic = {
    getVolume: function () { return vol; },
    isMuted: function () { return muted; },
    setVolume: function (v) { vol = Math.max(0, Math.min(1, v)); save('lq_vol', vol); if (vol > 0 && muted) { muted = false; save('lq_muted', '0'); } apply(); go(); },
    setMuted: function (m) { muted = !!m; save('lq_muted', muted ? '1' : '0'); apply(); go(); }
  };

  function hz(m) { return 440 * Math.pow(2, (m - 69) / 12); }
  function impulse(sec, decay) {
    var len = ctx.sampleRate * sec, b = ctx.createBuffer(2, len, ctx.sampleRate);
    for (var c = 0; c < 2; c++) { var d = b.getChannelData(c); for (var i = 0; i < len; i++) d[i] = (Math.random() * 2 - 1) * Math.pow(1 - i / len, decay); }
    return b;
  }
  function note(f, t, dur, type, peak, attack) {
    var o = ctx.createOscillator(), g = ctx.createGain();
    o.type = type; o.frequency.value = f;
    g.gain.setValueAtTime(0.0001, t);
    g.gain.linearRampToValueAtTime(peak, t + attack);
    g.gain.exponentialRampToValueAtTime(0.0001, t + dur);
    o.connect(g); g.connect(bus); o.start(t); o.stop(t + dur + 0.05);
  }

  // Am7 - Fmaj7 - C - G, with a soft pentatonic melody floating above
  var chords = [[57, 60, 64, 67], [53, 57, 60, 64], [48, 55, 60, 64], [55, 59, 62, 67]];
  var melody = [[72, 76, 79, 81, 84], [72, 74, 76, 79, 81], [72, 76, 79, 84, 76], [71, 74, 79, 83, 74]];
  var BEAT = 0.5;

  function playStep(s, t) {
    var ci = Math.floor(s / 8) % chords.length, ch = chords[ci];
    if (s % 8 === 0) {
      ch.forEach(function (m) { note(hz(m), t, 4.2, 'sine', 0.05, 1.3); });
      note(hz(ch[0] - 12), t, 4.2, 'sine', 0.07, 0.9);
    }
    if (Math.random() < 0.62) {
      var pool = melody[ci];
      note(hz(pool[Math.floor(Math.random() * pool.length)]), t, 1.8, 'triangle', 0.055, 0.02);
    }
  }
  function schedule() {
    while (next < ctx.currentTime + 0.5) { playStep(step, next); next += BEAT; step++; }
    setTimeout(schedule, 120);
  }

  function build() {
    if (!AC || ctx) return;
    try {
      ctx = new AC();
      master = ctx.createGain(); master.gain.value = level(); master.connect(ctx.destination);
      bus = ctx.createBiquadFilter(); bus.type = 'lowpass'; bus.frequency.value = 2400;
      var dry = ctx.createGain(); dry.gain.value = 0.75; bus.connect(dry); dry.connect(master);
      var rev = ctx.createConvolver(); rev.buffer = impulse(3, 2.5);
      var wet = ctx.createGain(); wet.gain.value = 0.55; bus.connect(rev); rev.connect(wet); wet.connect(master);
      next = ctx.currentTime + 0.1; schedule();
    } catch (e) { ctx = null; }
  }
  function go() { build(); if (ctx && ctx.state === 'suspended') ctx.resume(); }

  ['pointerdown', 'keydown', 'touchstart'].forEach(function (ev) {
    window.addEventListener(ev, function once() { go(); if (ctx && ctx.state === 'running') window.removeEventListener(ev, once); }, { passive: true });
  });
  document.addEventListener('visibilitychange', function () {
    if (!ctx) return;
    if (document.hidden) ctx.suspend(); else if (!muted || true) ctx.resume();
  });

  if (btn) btn.addEventListener('click', function () { window.LQMusic.setMuted(!(muted || vol === 0) ? true : false); if (vol === 0) window.LQMusic.setVolume(0.25); });
  if (slider) slider.addEventListener('input', function () { window.LQMusic.setVolume(slider.value / 100); });
  sync();
  go();   // works right away if the browser already allows sound for this site

  // "Show password" checkboxes (login, register, settings): toggles every password box in the same form
  document.addEventListener('change', function (e) {
    var cb = e.target;
    if (!cb.classList || !cb.classList.contains('js-show-pw')) return;
    var form = cb.closest('form'); if (!form) return;
    form.querySelectorAll('input[data-pw]').forEach(function (i) { i.type = cb.checked ? 'text' : 'password'; });
  });
})();
</script>