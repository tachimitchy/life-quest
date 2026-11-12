<?php
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/lq.php';

$user = require_login(['student']);

// Pick the reviewer: a teacher's file (?cdoc=), one of the student's own (?doc=), or the newest one.
function pick_doc(array $user, string $table, string $extra): ?array {
  $rows = lq_rows(lq_request('GET', '/rest/v1/' . $table . '?select=id,title,created_at' . $extra, null, $user['jwt']));
  return $rows[0] ?? null;
}
$cdoc = (string)($_GET['cdoc'] ?? '');
$id   = (string)($_GET['doc'] ?? '');
$doc  = null;
if (lq_is_uuid($cdoc))     $doc = pick_doc($user, 'class_documents', '&id=eq.' . rawurlencode($cdoc) . '&limit=1');
elseif (lq_is_uuid($id))   $doc = pick_doc($user, 'user_documents', '&id=eq.' . rawurlencode($id) . '&limit=1');
else                       $doc = pick_doc($user, 'user_documents', '&order=created_at.desc&limit=1')
                              ?? pick_doc($user, 'class_documents', '&order=created_at.desc&limit=1');

// No reviewer yet: back to the dashboard, which asks the student to upload one.
if (!$doc) lq_go('/dashboard?need=upload');

$pageTitle = 'Start Adventure';
$active    = 'dashboard';
require __DIR__ . '/../partials/app-open.php';
?>
      <h1 class="page-title">Start Adventure</h1>
      <div class="panel">
        <h2>Reviewer ready</h2>
        <p><b><?= lq_e($doc['title']) ?></b></p>
        <p>Uploaded <?= lq_e(lq_when($doc['created_at'])) ?></p>
      </div>
      <div class="panel empty">
        <span class="big" aria-hidden="true">⚔️</span>
        The adventure is still being built. Your reviewer is saved and will be ready to play the moment the game launches!
      </div>
      <div class="btns-row">
        <a class="px px-green" href="/dashboard">Back to Dashboard</a>
        <a class="px px-stone" href="/documents">My Documents</a>
      </div>
<?php require __DIR__ . '/../partials/app-close.php'; ?>