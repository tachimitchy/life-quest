<?php
/* Life Quest - profile window (opens from the sidebar profile or the dashboard hero).
   Included by partials/app-open.php. Needs $user to be set by the page. */
if (!isset($user) || !is_array($user)) { return; }

$pf       = lq_profile_data($user);
$pfChars  = lq_characters();
$pfLevel  = (int)$pf['level'];
$pfCharId = $pf['char_id'];
$pfHome   = home_for_role((string)($user['role'] ?? ''));
$pfBack   = strtok((string)($_SERVER['REQUEST_URI'] ?? $pfHome), '?') ?: $pfHome;
$pfNext   = null;
foreach ($pfChars as $c) {
  if ($c['level'] > $pfLevel && ($pfNext === null || $c['level'] < $pfNext['level'])) $pfNext = $c;
}
?>
<style>
  .pf.pxdialog {
    width: calc(100% - 1.5rem); max-width: 620px; max-height: 92vh; overflow: auto;
    padding: 0; color: #dbe3ff; background: #12183a;
    border: 4px solid #8b93a7; box-shadow: 0 0 0 4px #4b5266, 8px 8px 0 4px rgba(0, 0, 0, 0.45);
    font-family: "VT323", "Courier New", monospace; font-size: 1.3rem;
  }
  .pf::backdrop { background: rgba(5, 8, 25, 0.7); }
  .pf h2, .pf h3, .pf p { margin: 0; }

  .pf-head { display: flex; align-items: center; gap: 1rem; padding: 1.1rem 1.25rem; background: #1b2150; border-bottom: 4px solid #000; }
  .pf-avatar { flex: none; display: flex; align-items: center; justify-content: center; width: 88px; height: 88px; background: #0b1026; border: 4px solid #ffd54a; }
  .pf-avatar .lq-sprite { width: 64px; height: auto; image-rendering: pixelated; }
  .pf-id { flex: 1; min-width: 0; }
  .pf-id h2 { font-family: "Press Start 2P", "Courier New", monospace; font-size: 0.95rem; line-height: 1.5; color: #ffd54a; overflow-wrap: anywhere; }
  .pf-id p { margin-top: 0.35rem; color: #8e9ad0; }
  .pf-x { flex: none; align-self: flex-start; width: 36px; height: 36px; font: inherit; color: #fff; background: #e5484d; border: 3px solid #000; cursor: pointer; box-shadow: inset -3px -3px 0 #8f1d22, inset 3px 3px 0 #ff9a9d; }

  .pf-tabs { display: flex; gap: 0.5rem; padding: 0.75rem 1.25rem 0; }
  .pf-tab { flex: 1; padding: 0.6rem 0.4rem; font-family: "Press Start 2P", "Courier New", monospace; font-size: 0.55rem; color: #c3c9d8; background: #4b5266; border: 3px solid #000; cursor: pointer; box-shadow: inset -3px -3px 0 #343a4c, inset 3px 3px 0 #6b7388; }
  .pf-tab.is-on { color: #fff; background: #4caf3d; box-shadow: inset -3px -3px 0 #2d7a24, inset 3px 3px 0 #8be07a; }
  .pf-tab:focus-visible, .pf-x:focus-visible { outline: 3px dashed #ffd54a; outline-offset: 2px; }

  .pf-panel { padding: 1rem 1.25rem; }
  .pf-panel[hidden] { display: none; }

  .pf-rows { display: grid; gap: 0.6rem; }
  .pf-row { display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; padding: 0.6rem 0.8rem; background: #0b1026; border: 3px solid #4b5266; }
  .pf-row dt { color: #8e9ad0; flex: none; }
  .pf-row dd { flex: 1; margin: 0; text-align: right; color: #fff; overflow-wrap: anywhere; min-width: 0; }
  .pf-link { padding: 0.25rem 0.55rem; font: inherit; font-size: 1.1rem; color: #ffd54a; background: transparent; border: 2px dashed #ffd54a; cursor: pointer; }

  .pf-name-form { margin-top: 0.75rem; padding: 0.8rem; background: #0b1026; border: 3px solid #ffd54a; }
  .pf-name-form[hidden] { display: none; }
  .pf-name-form label { display: block; margin-bottom: 0.35rem; }
  .pf-name-form input[type="text"] { width: 100%; padding: 0.55rem 0.75rem; font: inherit; font-size: 1.4rem; color: #fff; background: #12183a; border: 3px solid #4b5266; outline: none; }
  .pf-name-form input[type="text"]:focus { border-color: #ffd54a; }
  .pf-btns { display: flex; flex-wrap: wrap; gap: 0.6rem; margin-top: 0.75rem; }

  .pf-count { margin-bottom: 0.75rem; color: #8e9ad0; }
  .pf-ach-list { display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); gap: 0.6rem; }
  .pf-ach { display: flex; align-items: center; gap: 0.7rem; padding: 0.6rem 0.75rem; background: #0b1026; border: 3px solid #4b5266; }
  .pf-ach .ico { flex: none; font-size: 1.8rem; line-height: 1; }
  .pf-ach strong { display: block; font-weight: 400; color: #fff; }
  .pf-ach small { color: #8e9ad0; font-size: 1.05rem; }
  .pf-ach.is-earned { border-color: #4caf3d; background: #11331a; }
  .pf-ach.is-earned .ico::after { content: " ✔"; font-size: 1rem; color: #7ee86b; }
  .pf-ach.is-locked .ico { filter: grayscale(1) brightness(0.5); }
  .pf-ach.is-locked strong { color: #8b93a7; }

  .pf-next { margin-bottom: 0.25rem; color: #8e9ad0; }
  .pf-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(118px, 1fr)); gap: 0.7rem; margin-top: 0.75rem; }
  .pf-card { position: relative; display: flex; flex-direction: column; align-items: center; gap: 0.3rem; width: 100%; padding: 0.8rem 0.4rem 0.65rem; font: inherit; text-align: center; color: #fff; background: #0b1026; border: 4px solid #000; box-shadow: inset -4px -4px 0 #070b1c, inset 4px 4px 0 #2a3270; cursor: pointer; transition: transform 0.08s steps(2); }
  .pf-card:hover, .pf-card:focus-visible { transform: translateY(-3px); }
  .pf-card:focus-visible { outline: 3px dashed #ffd54a; outline-offset: 3px; }
  .pf-card .lq-sprite { width: 60px; height: auto; image-rendering: pixelated; }
  .pf-card .nm { line-height: 1.1; }
  .pf-card .tg { font-size: 1rem; color: #8e9ad0; }
  .pf-card.is-selected { border-color: #ffd54a; box-shadow: inset -4px -4px 0 #7a5a00, inset 4px 4px 0 #3b3a10; }
  .pf-card.is-locked { cursor: not-allowed; background: #070b1c; }
  .pf-card.is-locked .lq-sprite { filter: grayscale(1) brightness(0.35); }
  .pf-card.is-locked .nm { color: #8b93a7; }
  .pf-badge, .pf-lock { font-family: "Press Start 2P", "Courier New", monospace; font-size: 0.5rem; line-height: 1.5; padding: 3px 5px; border: 2px solid #000; }
  .pf-badge { color: #fff; background: #4caf3d; }
  .pf-lock { color: #ffd54a; background: #3a1220; border-color: #e5484d; }
  @keyframes pf-shake { 0%,100% { transform: translateX(0); } 25% { transform: translateX(-4px); } 75% { transform: translateX(4px); } }
  .pf-card.shake { animation: pf-shake 0.25s steps(4); }

  .pf-foot { display: flex; flex-wrap: wrap; gap: 0.6rem; justify-content: flex-end; padding: 0.9rem 1.25rem 1.1rem; border-top: 4px solid #000; background: #1b2150; }
  .pf-foot .px { text-decoration: none; }

  [data-open-profile] { cursor: pointer; }
  [data-open-profile]:hover { filter: brightness(1.12); }
  [data-open-profile]:focus-visible { outline: 3px dashed #ffd54a; outline-offset: 4px; }

  @media (prefers-reduced-motion: reduce) { .pf-card { transition: none; } .pf-card.shake { animation: none; } }
</style>

<dialog id="lq-profile" class="pxdialog pf" aria-labelledby="pf-title">
  <div class="pf-head">
    <div class="pf-avatar"><?= lq_char_svg($pfCharId) ?></div>
    <div class="pf-id">
      <h2 id="pf-title"><?= lq_e($user['full_name']) ?></h2>
      <p><?= lq_e(ucfirst((string)$user['role'])) ?> &middot; Level <?= $pfLevel ?></p>
      <p><?= lq_e($pfChars[$pfCharId]['name']) ?></p>
    </div>
    <button type="button" class="pf-x" id="pf-close" aria-label="Close profile">&#10005;</button>
  </div>

  <div class="pf-tabs" role="tablist">
    <button type="button" class="pf-tab is-on" role="tab" data-tab="info">Profile</button>
    <button type="button" class="pf-tab" role="tab" data-tab="ach">Achievements</button>
    <button type="button" class="pf-tab" role="tab" data-tab="chars">Characters</button>
  </div>

  <!-- PROFILE -->
  <section class="pf-panel" data-panel="info">
    <dl class="pf-rows">
      <div class="pf-row">
        <dt>Username</dt>
        <dd id="pf-name-text"><?= lq_e($user['full_name']) ?></dd>
        <button type="button" class="pf-link" id="pf-name-edit">&#9998; Edit</button>
      </div>
      <div class="pf-row"><dt>Role</dt><dd><?= lq_e(ucfirst((string)$user['role'])) ?></dd></div>
      <div class="pf-row"><dt>Level</dt><dd><?= $pfLevel ?></dd></div>
      <div class="pf-row"><dt>Email</dt><dd><?= lq_e($user['email']) ?></dd></div>
    </dl>

    <form class="pf-name-form" id="pf-name-form" method="POST" action="<?= lq_e($pfHome) ?>" hidden>
      <input type="hidden" name="csrf" value="<?= lq_e(lq_csrf()) ?>" />
      <input type="hidden" name="action" value="profile_name" />
      <input type="hidden" name="back" value="<?= lq_e($pfBack) ?>" />
      <label for="pf-name-input">Change your username</label>
      <input type="text" id="pf-name-input" name="full_name" value="<?= lq_e($user['full_name']) ?>" minlength="2" maxlength="60" required autocomplete="name" />
      <div class="pf-btns">
        <button type="submit" class="px px-green">Save</button>
        <button type="button" class="px px-stone" id="pf-name-cancel">Cancel</button>
      </div>
    </form>
  </section>

  <!-- ACHIEVEMENTS -->
  <section class="pf-panel" data-panel="ach" hidden>
    <p class="pf-count"><?= (int)$pf['earned'] ?> of <?= count($pf['achievements']) ?> achievements earned</p>
    <div class="pf-ach-list">
      <?php foreach ($pf['achievements'] as $a): ?>
        <div class="pf-ach <?= $a[3] ? 'is-earned' : 'is-locked' ?>">
          <span class="ico" aria-hidden="true"><?= $a[0] ?></span>
          <div><strong><?= lq_e($a[1]) ?></strong><small><?= lq_e($a[2]) ?></small></div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- CHARACTERS -->
  <section class="pf-panel" data-panel="chars" hidden>
    <p class="pf-next">
      <?php if ($pfNext): ?>Next unlock: <b><?= lq_e($pfNext['name']) ?></b> at Level <?= (int)$pfNext['level'] ?>
      <?php else: ?>You unlocked every character. Legendary!<?php endif; ?>
    </p>
    <form method="POST" action="<?= lq_e($pfHome) ?>">
      <input type="hidden" name="csrf" value="<?= lq_e(lq_csrf()) ?>" />
      <input type="hidden" name="action" value="character" />
      <input type="hidden" name="back" value="<?= lq_e($pfBack) ?>" />
      <div class="pf-grid">
        <?php foreach ($pfChars as $id => $c): ?>
          <?php $unlocked = $pfLevel >= $c['level']; $selected = ($id === $pfCharId); ?>
          <?php if ($unlocked): ?>
            <button type="submit" name="character" value="<?= lq_e($id) ?>" class="pf-card<?= $selected ? ' is-selected' : '' ?>"<?= $selected ? ' aria-pressed="true"' : '' ?>>
              <?= lq_char_svg($id) ?>
              <span class="nm"><?= lq_e($c['name']) ?></span>
              <span class="tg"><?= lq_e($c['gender']) ?></span>
              <?php if ($selected): ?><span class="pf-badge">IN USE</span><?php endif; ?>
            </button>
          <?php else: ?>
            <div class="pf-card is-locked" role="button" tabindex="0" data-no-sfx aria-disabled="true" aria-label="<?= lq_e($c['name']) ?>, locked until Level <?= (int)$c['level'] ?>">
              <?= lq_char_svg($id) ?>
              <span class="nm"><?= lq_e($c['name']) ?></span>
              <span class="tg"><?= lq_e($c['gender']) ?></span>
              <span class="pf-lock">&#128274; LEVEL <?= (int)$c['level'] ?></span>
            </div>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
    </form>
  </section>

  <div class="pf-foot">
    <a class="px px-green" id="pf-back" href="<?= lq_e($pfHome) ?>">Back to Dashboard</a>
    <button type="button" class="px px-stone" id="pf-close2">Close</button>
  </div>
</dialog>

<script>
(function () {
  var dlg = document.getElementById('lq-profile');
  if (!dlg) return;

  var LEVEL  = <?= $pfLevel ?>;
  var SPRITE = <?= json_encode(lq_char_svg($pfCharId)) ?>;
  var HOME   = <?= json_encode($pfHome) ?>;

  /* ---- tabs ---- */
  var tabs   = dlg.querySelectorAll('.pf-tab');
  var panels = dlg.querySelectorAll('.pf-panel');
  function showTab(name) {
    Array.prototype.forEach.call(tabs, function (t) { t.classList.toggle('is-on', t.getAttribute('data-tab') === name); });
    Array.prototype.forEach.call(panels, function (p) { p.hidden = p.getAttribute('data-panel') !== name; });
  }
  Array.prototype.forEach.call(tabs, function (t) {
    t.addEventListener('click', function () { showTab(t.getAttribute('data-tab')); });
  });

  /* ---- open / close ---- */
  function openProfile(tab) { showTab(tab || 'info'); if (!dlg.open) dlg.showModal(); }
  window.lqOpenProfile = openProfile;

  document.addEventListener('click', function (e) {
    var t = e.target.closest ? e.target.closest('[data-open-profile]') : null;
    if (!t) return;
    e.preventDefault();
    openProfile(t.getAttribute('data-open-profile') || 'info');
  });
  document.addEventListener('keydown', function (e) {
    if (e.key !== 'Enter' && e.key !== ' ') return;
    var t = e.target.closest ? e.target.closest('[data-open-profile][role="button"]') : null;
    if (!t) return;
    e.preventDefault();
    openProfile(t.getAttribute('data-open-profile') || 'info');
  });

  function closeProfile() { dlg.close(); }
  document.getElementById('pf-close').addEventListener('click', closeProfile);
  document.getElementById('pf-close2').addEventListener('click', closeProfile);
  dlg.addEventListener('click', function (e) { if (e.target === dlg) closeProfile(); });

  // "Back to Dashboard": if you are already there, just close the window.
  document.getElementById('pf-back').addEventListener('click', function (e) {
    if (location.pathname.replace(/\/+$/, '') === HOME.replace(/\/+$/, '')) { e.preventDefault(); closeProfile(); }
  });

  /* ---- edit name ---- */
  var form   = document.getElementById('pf-name-form');
  var input  = document.getElementById('pf-name-input');
  var editBt = document.getElementById('pf-name-edit');
  editBt.addEventListener('click', function () { form.hidden = false; editBt.hidden = true; input.focus(); input.select(); });
  document.getElementById('pf-name-cancel').addEventListener('click', function () { form.hidden = true; editBt.hidden = false; });

  /* ---- locked characters ---- */
  function nope(card) {
    card.classList.remove('shake'); void card.offsetWidth; card.classList.add('shake');
    if (window.LQ_SFX) window.LQ_SFX.play('wrong');
  }
  Array.prototype.forEach.call(dlg.querySelectorAll('.pf-card.is-locked'), function (card) {
    card.addEventListener('click', function () { nope(card); });
    card.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); nope(card); }
    });
  });

  /* ---- make the sidebar profile clickable ----
     Finds the "Student · Level 1" block in the sidebar, shows the chosen
     character in its avatar, and opens this window when clicked. */
  function setupSidebar() {
    var root = document.querySelector('.app > :first-child');
    if (!root) return;
    var walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, null);
    var node, hit = null;
    while ((node = walker.nextNode())) {
      if (/Level\s*\d+/i.test(node.nodeValue)) { hit = node; break; }
    }
    if (!hit) return;

    hit.nodeValue = hit.nodeValue.replace(/Level\s*\d+/i, 'Level ' + LEVEL);

    var el  = hit.parentElement;
    var box = (el && el.parentElement && el.parentElement !== root) ? el.parentElement : el;
    if (!box || box.hasAttribute('data-open-profile')) return;

    var kids = box.children;
    for (var i = 0; i < kids.length; i++) {
      if (kids[i] !== el && kids[i].textContent.trim() === '') {
        kids[i].innerHTML = SPRITE;
        kids[i].style.display = 'flex';
        kids[i].style.alignItems = 'center';
        kids[i].style.justifyContent = 'center';
        var s = kids[i].querySelector('svg');
        if (s) { s.style.width = '56px'; s.style.height = 'auto'; s.style.imageRendering = 'pixelated'; }
        break;
      }
    }

    box.setAttribute('data-open-profile', 'info');
    box.setAttribute('role', 'button');
    box.setAttribute('tabindex', '0');
    box.setAttribute('aria-label', 'Open your profile');
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', setupSidebar);
  else setupSidebar();
})();
</script>