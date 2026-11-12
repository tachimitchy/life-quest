<?php
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/lq.php';

$user   = require_login(['teacher']);
$player = lq_player($user);
$first  = explode(' ', trim($user['full_name']))[0] ?: 'Teacher';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!lq_csrf_ok()) {
    lq_flash('error', 'Invalid form submission. Please try again.');
    lq_go('/teacher');
  }
  $action = $_POST['action'] ?? '';
  $cid    = (string)($_POST['class_id'] ?? '');

  if ($action === 'create_class') {
    $name = trim((string)($_POST['name'] ?? ''));
    if (mb_strlen($name) < 2 || mb_strlen($name) > 60) {
      lq_flash('error', 'Give the class a name (2 to 60 characters).');
    } else {
      $made = false;
      for ($i = 0; $i < 4 && !$made; $i++) {          // try again if the random code is already taken
        $r = lq_request('POST', '/rest/v1/teacher_classes',
          ['teacher_id' => $user['id'], 'name' => $name, 'code' => lq_make_code()], $user['jwt'], ['Prefer: return=minimal']);
        $made = lq_ok($r);
        if (!$made && $r['status'] !== 409) break;
      }
      $made ? lq_flash('success', 'Class created! Share its code with your students.')
            : lq_flash('error', 'Could not create the class. Did you run supabase-setup-2.sql?');
    }

  } elseif ($action === 'delete_class' && lq_is_uuid($cid)) {
    $files = lq_rows(lq_request('GET', '/rest/v1/class_documents?select=file_path&class_id=eq.' . rawurlencode($cid), null, $user['jwt']));
    foreach ($files as $f) lq_delete_object($user['jwt'], $f['file_path']);
    lq_request('DELETE', '/rest/v1/teacher_classes?id=eq.' . rawurlencode($cid), null, $user['jwt'], ['Prefer: return=minimal']);
    lq_flash('success', 'Class deleted.');

  } elseif ($action === 'upload_doc' && lq_is_uuid($cid)) {
    $u = lq_store_upload($user);
    if (!$u['ok']) {
      lq_flash('error', $u['msg']);
    } else {
      $row = lq_request('POST', '/rest/v1/class_documents', [
        'class_id' => $cid, 'teacher_id' => $user['id'], 'title' => $u['title'], 'file_name' => $u['file_name'],
        'file_path' => $u['path'], 'file_size' => $u['size'], 'mime_type' => $u['mime'],
      ], $user['jwt'], ['Prefer: return=minimal']);
      if (lq_ok($row)) {
        lq_flash('success', 'Reviewer added! Students who joined this class can now play it.');
      } else {
        lq_delete_object($user['jwt'], $u['path']);
        lq_flash('error', 'The file could not be saved to that class.');
      }
    }

  } elseif ($action === 'delete_doc') {
    $id   = (string)($_POST['id'] ?? '');
    $rows = lq_is_uuid($id) ? lq_rows(lq_request('GET', '/rest/v1/class_documents?select=file_path&id=eq.' . rawurlencode($id) . '&limit=1', null, $user['jwt'])) : [];
    if ($rows) {
      lq_delete_object($user['jwt'], $rows[0]['file_path']);
      lq_request('DELETE', '/rest/v1/class_documents?id=eq.' . rawurlencode($id), null, $user['jwt'], ['Prefer: return=minimal']);
      lq_flash('success', 'Reviewer removed.');
    }
  }
  lq_go('/teacher');
}

$cRes     = lq_request('GET', '/rest/v1/teacher_classes?select=id,name,code,created_at&order=created_at.desc', null, $user['jwt']);
$classes  = lq_rows($cRes);
$allDocs  = lq_rows(lq_request('GET', '/rest/v1/class_documents?select=id,class_id,title,file_size,created_at&order=created_at.desc', null, $user['jwt']));
$members  = lq_rows(lq_request('GET', '/rest/v1/class_students?select=class_id', null, $user['jwt']));
$studentCount = array_count_values(array_column($members, 'class_id'));

$pageTitle = 'Teacher Dashboard';
$active    = 'dashboard';
require __DIR__ . '/../partials/app-open.php';
?>
      <?php if (!lq_ok($cRes)): ?>
        <div class="alert alert-error" role="alert">The database is not set up yet. Run <b>supabase-setup-2.sql</b> in the Supabase SQL Editor.</div>
      <?php endif; ?>

      <section class="welcome">
        <div class="hero-box" aria-hidden="true"><?= lq_sprite($player['character']) ?></div>
        <div>
          <h1>Welcome, Teacher <?= lq_e($first) ?>!</h1>
          <p>Create a class code, add reviewers and keep your students up to date.</p>
        </div>
      </section>

      <h2 class="section-title">Quick Actions</h2>
      <div class="actions">
        <a href="#new-class" class="tile tile-upload">
          <span class="big" aria-hidden="true">🔑</span>
          <strong>Create Class Code</strong>
        </a>
        <a href="/announcements" class="tile tile-play">
          <span class="big" aria-hidden="true">📢</span>
          <strong>Post Announcement</strong>
        </a>
      </div>

      <form class="panel" id="new-class" action="/teacher" method="POST">
        <h2>New class</h2>
        <input type="hidden" name="csrf" value="<?= lq_e(lq_csrf()) ?>" />
        <input type="hidden" name="action" value="create_class" />
        <div class="inline">
          <div class="field">
            <label for="cname">Class name</label>
            <input type="text" id="cname" name="name" maxlength="60" placeholder="Biology 10-A" required />
          </div>
          <button type="submit" class="px px-green">Create code</button>
        </div>
      </form>

      <h2 class="section-title">My Classes</h2>
      <?php if (!$classes): ?>
        <div class="panel empty"><span class="big" aria-hidden="true">🎓</span>No classes yet. Create one to get a code for your students.</div>
      <?php endif; ?>

      <?php foreach ($classes as $c):
        $cdocs = array_filter($allDocs, fn($d) => $d['class_id'] === $c['id']); ?>
        <div class="panel">
          <div class="class-head">
            <div>
              <h2><?= lq_e($c['name']) ?></h2>
              <small><?= (int)($studentCount[$c['id']] ?? 0) ?> student(s) joined</small>
            </div>
            <div>Code: <span class="code"><?= lq_e($c['code']) ?></span></div>
          </div>

          <?php foreach ($cdocs as $d): ?>
            <div class="doc">
              <div><h3><?= lq_e($d['title']) ?></h3><small><?= lq_e(lq_when($d['created_at'])) ?> &middot; <?= lq_e(lq_size($d['file_size'] ?? 0)) ?></small></div>
              <form action="/teacher" method="POST" onsubmit="return confirm('Remove this reviewer from the class?');">
                <input type="hidden" name="csrf" value="<?= lq_e(lq_csrf()) ?>" />
                <input type="hidden" name="action" value="delete_doc" />
                <input type="hidden" name="id" value="<?= lq_e($d['id']) ?>" />
                <button type="submit" class="px px-red">Remove</button>
              </form>
            </div>
          <?php endforeach; ?>

          <form action="/teacher" method="POST" enctype="multipart/form-data" class="inline" style="margin-top:0.8rem;">
            <input type="hidden" name="csrf" value="<?= lq_e(lq_csrf()) ?>" />
            <input type="hidden" name="action" value="upload_doc" />
            <input type="hidden" name="class_id" value="<?= lq_e($c['id']) ?>" />
            <div class="field">
              <label for="f<?= lq_e($c['id']) ?>">Add a reviewer for this class (PDF or scanned image, up to 4 MB)</label>
              <input type="file" id="f<?= lq_e($c['id']) ?>" name="reviewer" accept=".pdf,.png,.jpg,.jpeg,application/pdf,image/png,image/jpeg" required />
            </div>
            <button type="submit" class="px px-green">Upload</button>
          </form>

          <form action="/teacher" method="POST" style="margin-top:0.8rem;" onsubmit="return confirm('Delete this class and its reviewers? Students will lose access.');">
            <input type="hidden" name="csrf" value="<?= lq_e(lq_csrf()) ?>" />
            <input type="hidden" name="action" value="delete_class" />
            <input type="hidden" name="class_id" value="<?= lq_e($c['id']) ?>" />
            <button type="submit" class="px px-red">Delete class</button>
          </form>
        </div>
      <?php endforeach; ?>
<?php require __DIR__ . '/../partials/app-close.php'; ?>