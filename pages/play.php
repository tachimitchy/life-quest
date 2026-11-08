<?php
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/lq.php';

$user = require_login(['student']);

// Use the chosen reviewer (?doc=...) or the most recent one.
$id  = (string)($_GET['doc'] ?? '');
$sel = '/rest/v1/user_documents?select=id,title,file_name,created_at';
$q   = lq_is_uuid($id) ? $sel . '&id=eq.' . rawurlencode($id) . '&limit=1' : $sel . '&order=created_at.desc&limit=1';
$doc = lq_rows(lq_request('GET', $q, null, $user['jwt']))[0] ?? null;

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