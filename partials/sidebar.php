<?php
// Expects: $user (from current_user) and $active (dashboard, documents, scores, announcements, settings)
$__role    = $user['role'];
$__student = $__role === 'student';
$__player  = lq_player($user);
$__level   = $__player['level'];

$__link = function (string $href, string $key, string $icon, string $label, string $extra = '') use ($active): string {
  $cls = trim(($active === $key ? 'active ' : '') . $extra);
  return '<a href="' . $href . '"' . ($cls !== '' ? ' class="' . $cls . '"' : '') . ($active === $key ? ' aria-current="page"' : '') . '>'
       . '<span class="icon" aria-hidden="true">' . $icon . '</span>' . $label . '</a>';
};
?>
<aside class="side">
  <div class="profile">
    <a class="profile-link" href="/profile" title="View your profile">
      <div class="avatar" aria-hidden="true"><?= lq_sprite($__player['character']) ?></div>
      <div class="profile-name"><?= lq_e($user['full_name']) ?></div>
      <div class="profile-role"><?= lq_e(ucfirst($__role)) ?><?= $__student ? ' &middot; Level ' . (int)$__level : '' ?></div>
      <span class="profile-hint">View profile</span>
    </a>
  </div>

  <nav class="nav" aria-label="Menu">
    <?= $__link(home_for_role($__role), 'dashboard', '🏠', 'Dashboard') ?>
    <?php if ($__student): ?>
      <?= $__link('/documents', 'documents', '📄', 'My Documents') ?>
      <?= $__link('/scores', 'scores', '🏆', 'Scores') ?>
    <?php endif; ?>
    <?= $__link('/announcements', 'announcements', '📢', 'Announcements') ?>
    <?= $__link('/settings', 'settings', '⚙️', 'Settings') ?>
    <?= $__link('/logout', 'logout', '🚪', 'Logout', 'logout') ?>
  </nav>
</aside>