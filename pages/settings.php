<?php
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/lq.php';

$user = require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!lq_csrf_ok()) {
    lq_flash('error', 'Invalid form submission. Please try again.');
    lq_go('/settings');
  }
  $action = $_POST['action'] ?? '';

  if ($action === 'theme') {
    lq_set_theme((string)($_POST['theme'] ?? 'day'));
    lq_flash('success', 'Settings saved!');

  } elseif ($action === 'password') {
    $cur  = (string)($_POST['current'] ?? '');
    $new  = (string)($_POST['new'] ?? '');
    $conf = (string)($_POST['confirm'] ?? '');

    if ($cur === '' || $new === '') {
      lq_flash('error', 'Please fill in every password box.');
    } elseif (strlen($new) < 8) {
      lq_flash('error', 'The new password must be at least 8 characters.');
    } elseif ($new !== $conf) {
      lq_flash('error', 'The new passwords do not match.');
    } elseif ($new === $cur) {
      lq_flash('error', 'Your new password must be different from the current one.');
    } else {
      // 1) make sure the current password is right, 2) save the new one in Supabase
      $check = lq_request('POST', '/auth/v1/token?grant_type=password', ['email' => $user['email'], 'password' => $cur]);
      if (!lq_ok($check) || empty($check['data']['access_token'])) {
        lq_flash('error', 'Your current password is not correct.');
      } else {
        $upd = lq_request('PUT', '/auth/v1/user', ['password' => $new], $user['jwt']);
        if (lq_ok($upd)) {
          lq_flash('success', 'Password changed! Use your new password the next time you log in.');
        } else {
          $why = is_array($upd['data']) ? ($upd['data']['msg'] ?? $upd['data']['message'] ?? '') : '';
          lq_flash('error', 'Could not change your password. ' . $why);
        }
      }
    }
  }
  lq_go('/settings');
}

$night = lq_is_night();

$pageTitle = 'Settings';
$active    = 'settings';
require __DIR__ . '/../partials/app-open.php';
?>
      <h1 class="page-title">Settings</h1>
      <p class="lead">Make Life Quest look and sound the way you like.</p>

      <div class="panel">
        <h2>Account</h2>
        <div class="kv"><b>Name</b><span><?= lq_e($user['full_name']) ?></span></div>
        <div class="kv"><b>Email</b><span><?= lq_e($user['email']) ?></span></div>
        <div class="kv"><b>Role</b><span><?= lq_e(ucfirst($user['role'])) ?></span></div>
        <p style="margin-top:0.6rem;"><a class="px px-stone" href="/profile">Edit profile</a></p>
      </div>

      <form class="panel" action="/settings" method="POST">
        <h2>Background</h2>
        <p style="margin-bottom:1rem;">Night Time turns the background into a starry night, and the letters and buttons twinkle like stars.</p>
        <input type="hidden" name="csrf" value="<?= lq_e(lq_csrf()) ?>" />
        <input type="hidden" name="action" value="theme" />
        <div class="theme-options">
          <label class="theme-option">
            <input type="radio" name="theme" value="day" <?= $night ? '' : 'checked' ?> />
            <span><em aria-hidden="true">☀️</em>Day Time</span>
          </label>
          <label class="theme-option">
            <input type="radio" name="theme" value="night" <?= $night ? 'checked' : '' ?> />
            <span><em aria-hidden="true">🌙</em>Night Time</span>
          </label>
        </div>
        <button type="submit" class="px px-green">Save</button>
      </form>

      <div class="panel">
        <h2>Screen see-through</h2>
        <p>Slide to the left to see more of the game background behind the menus. The buttons and letters stay easy to read.</p>
        <div class="row">
          <input type="range" id="set-alpha" min="30" max="100" step="5" aria-label="Menu transparency" />
          <span id="alpha-val"></span>
        </div>
      </div>

      <div class="panel">
        <h2>Sound</h2>
        <div class="row">
          <input type="range" id="set-vol" min="0" max="100" step="1" aria-label="Music volume" />
          <span id="vol-val"></span>
        </div>
        <div class="row">
          <label class="check"><input type="checkbox" id="set-mute" /> Mute the music</label>
        </div>
      </div>

      <form class="panel" action="/settings" method="POST">
        <h2>Change password</h2>
        <input type="hidden" name="csrf" value="<?= lq_e(lq_csrf()) ?>" />
        <input type="hidden" name="action" value="password" />
        <div class="field">
          <label for="current">Current password</label>
          <input type="password" id="current" name="current" data-pw required autocomplete="current-password" />
        </div>
        <div class="field">
          <label for="new">New password</label>
          <input type="password" id="new" name="new" data-pw minlength="8" required autocomplete="new-password" />
        </div>
        <div class="field">
          <label for="confirm">Confirm new password</label>
          <input type="password" id="confirm" name="confirm" data-pw minlength="8" required autocomplete="new-password" />
        </div>
        <label class="show-pw"><input type="checkbox" class="js-show-pw" /> Show password</label>
        <button type="submit" class="px px-green">Change password</button>
      </form>

      <script>
        (function () {
          var alpha = document.getElementById('set-alpha'), aval = document.getElementById('alpha-val');
          var saved = parseFloat(localStorage.getItem('lq_alpha'));
          alpha.value = Math.round((saved >= 0.3 && saved <= 1 ? saved : 1) * 100);
          function setAlpha() {
            var a = alpha.value / 100;
            aval.textContent = alpha.value + '% solid';
            document.documentElement.style.setProperty('--ui-alpha', a);
            document.documentElement.classList.toggle('ui-clear', a < 0.9);
            try { localStorage.setItem('lq_alpha', a); } catch (e) {}
          }
          alpha.addEventListener('input', setAlpha); setAlpha();

          var vol = document.getElementById('set-vol'), vval = document.getElementById('vol-val'), mute = document.getElementById('set-mute');
          function pull() {
            if (!window.LQMusic) return;
            vol.value = Math.round(LQMusic.getVolume() * 100); vval.textContent = vol.value + '%'; mute.checked = LQMusic.isMuted();
          }
          vol.addEventListener('input', function () { LQMusic.setVolume(vol.value / 100); });
          mute.addEventListener('change', function () { LQMusic.setMuted(mute.checked); });
          window.addEventListener('lq-audio', pull); pull();
        })();
      </script>
<?php require __DIR__ . '/../partials/app-close.php'; ?>