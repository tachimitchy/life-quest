<?php
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/lq.php';

$user = require_login(['student']);

$res     = lq_request('GET',
  '/rest/v1/game_scores?select=score,correct,total,time_seconds,played_at,user_documents(title)&order=played_at.desc&limit=50',
  null, $user['jwt']);
$scores  = lq_rows($res);
$dbReady = lq_ok($res);

$count = count($scores);
$best  = $count ? max(array_column($scores, 'score')) : 0;
$avg   = $count ? (int)round(array_sum(array_column($scores, 'score')) / $count) : 0;

$pageTitle = 'Scores';
$active    = 'scores';
require __DIR__ . '/../partials/app-open.php';
?>
      <h1 class="page-title">Scores</h1>
      <p class="lead">Your results from every game you have played.</p>

      <?php if (!$dbReady): ?>
        <div class="alert alert-error" role="alert">The database is not set up yet. Run <b>supabase-setup.sql</b> in the Supabase SQL Editor.</div>
      <?php endif; ?>

      <div class="stats">
        <div class="stat"><b><?= (int)$best ?></b>Best score</div>
        <div class="stat"><b><?= (int)$count ?></b>Games played</div>
        <div class="stat"><b><?= (int)$avg ?></b>Average score</div>
      </div>

      <h2 class="section-title">Game History</h2>
      <?php if (!$scores): ?>
        <div class="panel empty">
          <span class="big" aria-hidden="true">🏆</span>
          No games played yet. Start an adventure to earn your first score!
        </div>
      <?php else: ?>
        <div class="panel table-wrap">
          <table class="scores">
            <thead><tr><th>Date</th><th>Reviewer</th><th>Score</th><th>Correct</th><th>Time</th></tr></thead>
            <tbody>
              <?php foreach ($scores as $s):
                $t = isset($s['time_seconds']) ? (int)$s['time_seconds'] : null; ?>
                <tr>
                  <td><?= lq_e(lq_when($s['played_at'])) ?></td>
                  <td><?= lq_e($s['user_documents']['title'] ?? 'Deleted reviewer') ?></td>
                  <td><?= (int)$s['score'] ?></td>
                  <td><?= isset($s['correct'], $s['total']) ? (int)$s['correct'] . ' / ' . (int)$s['total'] : '-' ?></td>
                  <td><?= $t === null ? '-' : sprintf('%d:%02d', intdiv($t, 60), $t % 60) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
<?php require __DIR__ . '/../partials/app-close.php'; ?>