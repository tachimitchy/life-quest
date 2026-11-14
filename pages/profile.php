<?php
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/lq.php';

$user   = require_login();                 // students, teachers and admins
$player = lq_player($user);
$chars  = lq_characters();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!lq_csrf_ok()) {
    lq_flash('error', 'Invalid form submission. Please try again.');
    lq_go('/profile');
  }
  $action = $_POST['action'] ?? '';

  if ($action === 'rename') {
    $name = trim((string)($_POST['name'] ?? ''));
    if (!preg_match('/^[\p{L}\p{M} .\'-]{2,60}$/u', $name)) {
      lq_flash('error', 'Name must be 2-60 characters (letters, spaces, . \' -).');
    } else {
      $r = lq_rpc('update_my_name', ['p_name' => $name], $user['jwt']);
      lq_ok($r) ? lq_flash('success', 'Your name was updated!')
                : lq_flash('error', lq_err($r, 'Could not update your name. Did you run supabase-setup-2.sql?'));
    }
  } elseif ($action === 'character') {
    $id = (string)($_POST['id'] ?? '');
    if (!isset($chars[$id])) {
      lq_flash('error', 'Unknown character.');
    } else {
      $r = lq_rpc('set_character', ['p_id' => $id], $user['jwt']);
      lq_ok($r) ? lq_flash('success', 'You are now playing as the ' . $chars[$id]['name'] . '!')
                : lq_flash('error', lq_err($r, 'Could not change your character.'));
    }
  }
  lq_go('/profile');
}

// Achievements earned so far (and any level badges that are now due)
$earnedRows = lq_rows(lq_request('GET', '/rest/v1/user_achievements?select=achievement_id', null, $user['jwt']));
$earned = array_column($earnedRows, 'achievement_id');
foreach ([5 => 'level_5', 10 => 'level_10'] as $lv => $aid) {
  if ($player['level'] >= $lv && !in_array($aid, $earned, true)) { lq_award($user['jwt'], $aid); $earned[] = $aid; }
}

$me   = $chars[$player['character']];
$pct  = min(100, $player['xp'] % 100);

$pageTitle = 'My Profile';
$active    = '';
require __DIR__ . '/../partials/app-open.php';
?>
      <h1 class="page-title">My Profile</h1>

      <div class="panel profile-card">
        <div class="big-spr" aria-hidden="true"><?= lq_sprite($player['character']) ?></div>
        <div style="flex:1 1 220px;">
          <h2><?= lq_e($user['full_name']) ?></h2>
          <p><?= lq_e($me['name']) ?> &middot; <?= lq_e(ucfirst($user['role'])) ?></p>
          <p>Level <?= (int)$player['level'] ?></p>
          <div class="xp" role="img" aria-label="Experience progress"><i style="width:<?= (int)$pct ?>%"></i></div>
          <small><?= (int)$player['xp'] ?> XP</small>
        </div>
      </div>

      <div class="panel">
        <h2>Account</h2>
        <form action="/profile" method="POST" class="inline" style="margin-bottom:1rem;">
          <input type="hidden" name="csrf" value="<?= lq_e(lq_csrf()) ?>" />
          <input type="hidden" name="action" value="rename" />
          <div class="field">
            <label for="name">Username</label>
            <input type="text" id="name" name="name" value="<?= lq_e($user['full_name']) ?>" maxlength="60" required />
          </div>
          <button type="submit" class="px px-green">Save name</button>
        </form>
        <div class="kv"><b>Role</b><span><?= lq_e(ucfirst($user['role'])) ?></span></div>
        <div class="kv"><b>Level</b><span><?= (int)$player['level'] ?></span></div>
        <div class="kv"><b>Email</b><span><?= lq_e($user['email']) ?></span></div>
      </div>

      <h2 class="section-title">Achievements</h2>
      <div class="achs">
        <?php foreach (lq_achievements() as $aid => [$icon, $label, $desc]): $got = in_array($aid, $earned, true); ?>
          <div class="ach<?= $got ? ' earned' : '' ?>" title="<?= lq_e($desc) ?>">
            <span class="ico" aria-hidden="true"><?= $icon ?></span>
            <b><?= lq_e($label) ?></b>
            <?= lq_e($got ? 'Unlocked!' : $desc) ?>
          </div>
        <?php endforeach; ?>
      </div>

      <h2 class="section-title">Choose Your Character</h2>
      <div class="chars">
        <?php foreach ($chars as $cid => $c):
          $unlocked = $player['level'] >= $c['level'];
          $current  = $player['character'] === $cid; ?>
          <div class="char<?= $unlocked ? '' : ' locked' ?><?= $current ? ' current' : '' ?>">
            <?php if (!$unlocked): ?><span class="lock" aria-hidden="true">🔒</span><?php endif; ?>
            <div class="face" aria-hidden="true"><?= lq_sprite($cid) ?></div>
            <div><?= lq_e($c['name']) ?></div>
            <small><?= lq_e($c['tag']) ?> &middot; <?= $unlocked ? 'Level ' . (int)$c['level'] : 'Reach Level ' . (int)$c['level'] ?></small>
            <?php if ($current): ?>
              <span class="badge">IN USE</span>
            <?php elseif ($unlocked): ?>
              <form action="/profile" method="POST">
                <input type="hidden" name="csrf" value="<?= lq_e(lq_csrf()) ?>" />
                <input type="hidden" name="action" value="character" />
                <input type="hidden" name="id" value="<?= lq_e($cid) ?>" />
                <button type="submit" class="px px-green">Select</button>
              </form>
            <?php else: ?>
              <span class="badge" style="background:#8b93a7;">LOCKED</span>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
<?php require __DIR__ . '/../partials/app-close.php'; ?>