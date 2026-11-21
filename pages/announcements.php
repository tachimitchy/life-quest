<?php
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/lq.php';

$user     = require_login();                                   // students, teachers and admins
$isAdmin  = $user['role'] === 'admin';
$canPost  = in_array($user['role'], ['teacher', 'admin'], true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!lq_csrf_ok()) {
    lq_flash('error', 'Invalid form submission. Please try again.');
    lq_go('/announcements');
  }
  $action = $_POST['action'] ?? '';

  if ($action === 'post') {
    $title = trim((string)($_POST['title'] ?? ''));
    $body  = trim((string)($_POST['body'] ?? ''));
    $cat   = ($isAdmin && ($_POST['category'] ?? '') === 'game_update') ? 'game_update' : 'general';

    if (!$canPost) {
      lq_flash('error', 'Only teachers and admins can post announcements.');
    } elseif ($title === '' || mb_strlen($title) > 80) {
      lq_flash('error', 'The title is required (up to 80 characters).');
    } elseif ($body === '' || mb_strlen($body) > 600) {
      lq_flash('error', 'The message is required (up to 600 characters).');
    } else {
      $r = lq_request('POST', '/rest/v1/announcements', [
        'author_id'   => $user['id'],
        'author_name' => $user['full_name'],
        'author_role' => $user['role'],
        'category'    => $cat,
        'title'       => $title,
        'body'        => $body,
      ], $user['jwt'], ['Prefer: return=minimal']);
      lq_ok($r) ? lq_flash('success', 'Announcement posted!')
                : lq_flash('error', 'Could not post it. Did you run supabase-setup.sql?');
    }
  } elseif ($action === 'delete') {
    $id   = (string)($_POST['id'] ?? '');
    $rows = lq_is_uuid($id)
      ? lq_rows(lq_request('GET', '/rest/v1/announcements?select=author_id&id=eq.' . rawurlencode($id) . '&limit=1', null, $user['jwt']))
      : [];
    if ($rows && ($isAdmin || $rows[0]['author_id'] === $user['id'])) {
      lq_request('DELETE', '/rest/v1/announcements?id=eq.' . rawurlencode($id), null, $user['jwt'], ['Prefer: return=minimal']);
      lq_flash('success', 'Announcement removed.');
    } else {
      lq_flash('error', 'You cannot remove that announcement.');
    }
  }
  lq_go('/announcements');
}

$res  = lq_request('GET', '/rest/v1/announcements?select=id,author_id,author_name,author_role,category,title,body,created_at&order=created_at.desc&limit=30', null, $user['jwt']);
$list = lq_rows($res);

$pageTitle = 'Announcements';
$active    = 'announcements';
require __DIR__ . '/../partials/app-open.php';
?>
      <h1 class="page-title">Announcements</h1>
      <p class="lead"><?= $canPost ? 'Share news with your students.' : 'News from your teachers and the Life Quest team.' ?></p>

      <?php if (!lq_ok($res)): ?>
        <div class="alert alert-error" role="alert">The database is not set up yet. Run <b>supabase-setup.sql</b> in the Supabase SQL Editor.</div>
      <?php endif; ?>

      <?php if ($canPost): ?>
        <form class="panel" action="/announcements" method="POST">
          <h2>New announcement</h2>
          <input type="hidden" name="csrf" value="<?= lq_e(lq_csrf()) ?>" />
          <input type="hidden" name="action" value="post" />
          <div class="field">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" maxlength="80" placeholder="New reviewer uploaded!" required />
          </div>
          <div class="field">
            <label for="body">Message</label>
            <textarea id="body" name="body" maxlength="600" placeholder="Write your update here..." required></textarea>
          </div>
          <?php if ($isAdmin): ?>
            <div class="field">
              <label for="category">Type</label>
              <select id="category" name="category">
                <option value="general">General announcement</option>
                <option value="game_update">Game update (also shows on every student's dashboard)</option>
              </select>
            </div>
          <?php endif; ?>
          <button type="submit" class="px px-green">Post</button>
        </form>
      <?php endif; ?>

      <?php if (!$list): ?>
        <div class="panel empty"><span class="big" aria-hidden="true">📢</span>No announcements yet.</div>
      <?php endif; ?>

      <?php foreach ($list as $a): ?>
        <article class="ann<?= $a['category'] === 'game_update' ? ' update' : '' ?>">
          <h3><?= lq_e($a['title']) ?></h3>
          <p><?= lq_e($a['body']) ?></p>
          <div class="meta">
            <?php if ($a['category'] === 'game_update'): ?><span class="badge">GAME UPDATE</span><?php endif; ?>
            <span><?= lq_e($a['author_name']) ?> (<?= lq_e(ucfirst($a['author_role'])) ?>)</span>
            <span><?= lq_e(lq_when($a['created_at'])) ?></span>
            <?php if ($isAdmin || $a['author_id'] === $user['id']): ?>
              <form action="/announcements" method="POST" onsubmit="return confirm('Remove this announcement?');">
                <input type="hidden" name="csrf" value="<?= lq_e(lq_csrf()) ?>" />
                <input type="hidden" name="action" value="delete" />
                <input type="hidden" name="id" value="<?= lq_e($a['id']) ?>" />
                <button type="submit" class="px px-red">Remove</button>
              </form>
            <?php endif; ?>
          </div>
        </article>
      <?php endforeach; ?>
<?php require __DIR__ . '/../partials/app-close.php'; ?>