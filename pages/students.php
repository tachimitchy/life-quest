<?php
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/lq.php';

$user = require_login(['teacher']);

// One call returns every class with its students, join dates, tries and scores.
$res     = lq_rpc('teacher_report', [], $user['jwt']);
$ready   = lq_ok($res);
$classes = ($ready && is_array($res['data'])) ? $res['data'] : [];

$mmss = fn($s) => $s === null ? '-' : intdiv((int)$s, 60) . ':' . sprintf('%02d', (int)$s % 60);

$totalStudents = 0; $totalTries = 0;
foreach ($classes as $c) {
  foreach ($c['students'] ?? [] as $s) { $totalStudents++; $totalTries += (int)$s['tries']; }
}

$pageTitle = 'Students & Leaderboard';
$active    = 'students';
require __DIR__ . '/../partials/app-open.php';
?>
      <h1 class="page-title">Students &amp; Leaderboard</h1>
      <p class="lead">See who joined your classes, how many times they played your reviewers, and who has the highest score.</p>

      <?php if (!$ready): ?>
        <div class="alert alert-error" role="alert">The report is not set up yet. Run <b>supabase-setup-4.sql</b> in the Supabase SQL Editor.</div>
      <?php endif; ?>

      <div class="stats">
        <div class="stat"><b><?= count($classes) ?></b>Classes</div>
        <div class="stat"><b><?= (int)$totalStudents ?></b>Students joined</div>
        <div class="stat"><b><?= (int)$totalTries ?></b>Total tries</div>
      </div>

      <?php if (!$classes && $ready): ?>
        <div class="panel empty"><span class="big" aria-hidden="true">🎓</span>You have no classes yet. Create a class code on your dashboard first.</div>
      <?php endif; ?>

      <?php foreach ($classes as $c):
        $students = $c['students'] ?? [];
        // highest score first; students who have not played yet go last
        usort($students, function ($a, $b) {
          $x = $a['best'] ?? -1; $y = $b['best'] ?? -1;
          return ($y <=> $x) ?: ((int)$a['tries'] <=> (int)$b['tries']);
        });
        $rank = 0; ?>
        <div class="panel">
          <div class="class-head">
            <div>
              <h2><?= lq_e($c['name']) ?></h2>
              <small><?= count($students) ?> student(s) joined</small>
            </div>
            <div>Code: <span class="code"><?= lq_e($c['code']) ?></span></div>
          </div>

          <?php if (!$students): ?>
            <div class="empty">No students have joined this class yet. Share the code above.</div>
          <?php else: ?>
            <div class="table-wrap">
              <table class="scores wide">
                <thead>
                  <tr>
                    <th>#</th><th>Student</th><th class="num">Best score</th><th class="num">Tries</th>
                    <th class="num">Average</th><th class="num">Fastest</th><th>Joined</th><th>Last played</th><th>Check</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($students as $s):
                    $tries = (int)$s['tries'];
                    if ($tries > 0) $rank++;
                    $flags = [];
                    foreach ($s['docs'] ?? [] as $d) { if ((int)$d['tries'] >= 5) { $flags[] = ['Many tries', '']; break; } }
                    if ($s['fastest_per_q'] !== null && (float)$s['fastest_per_q'] < 4) $flags[] = ['Very fast run', 'fast'];
                    $medal = $tries > 0 ? ([1 => '🥇', 2 => '🥈', 3 => '🥉'][$rank] ?? $rank) : '-'; ?>
                    <tr>
                      <td class="medal"><?= $medal ?></td>
                      <td class="who">
                        <?= lq_e($s['name']) ?>
                        <?php foreach ($s['docs'] ?? [] as $d): ?>
                          <span class="sub"><?= lq_e($d['title']) ?> &times;<?= (int)$d['tries'] ?> (best <?= (int)$d['best'] ?>)</span>
                        <?php endforeach; ?>
                        <?php if ($tries === 0): ?><span class="sub">No tries yet</span><?php endif; ?>
                      </td>
                      <td class="num"><?= $tries ? (int)$s['best'] : '-' ?></td>
                      <td class="num"><?= $tries ?></td>
                      <td class="num"><?= $tries ? (int)$s['avg_score'] : '-' ?></td>
                      <td class="num"><?= lq_e($mmss($s['fastest'])) ?></td>
                      <td class="nowrap"><?= lq_e(lq_day($s['joined_at'])) ?></td>
                      <td class="nowrap"><?= $s['last_played'] ? lq_e(lq_day($s['last_played'])) : '-' ?></td>
                      <td class="nowrap">
                        <?php foreach ($flags as [$label, $cls]): ?><span class="flag <?= $cls ?>">⚠ <?= lq_e($label) ?></span><?php endforeach; ?>
                        <?php if (!$flags && $tries): ?>OK<?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>

      <p class="legend">
        <b>Tries</b> counts every finished game on your reviewers. <b>⚠ Many tries</b> means a student played the same reviewer 5 or more times.
        <b>⚠ Very fast run</b> means a game was finished in under 4 seconds per question. These are only hints, so talk to the student before deciding anything.
      </p>
<?php require __DIR__ . '/../partials/app-close.php'; ?>