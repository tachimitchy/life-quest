<?php
$in = json_decode(file_get_contents('php://input'), true) ?? [];
$stageId = (int)($in['stage_id'] ?? 0);

$stage = sb('GET', '/rest/v1/stages?id=eq.' . $stageId . '&select=par_time_sec,quest_id', null, $user['jwt'])['data'][0] ?? null;
if (!$stage) { http_response_code(404); exit(json_encode(['error' => 'Stage not found'])); }

$correct = max(0, min(100, (int)($in['correct'] ?? 0)));
$hits    = max(0, (int)($in['hits'] ?? 0));
$time    = max(1, (int)($in['time_sec'] ?? 1));
$score   = max(0, ($correct * 100) + (max(0, $stage['par_time_sec'] - $time) * 2) - ($hits * 10));

// previous best BEFORE saving this attempt
$prev = sb('GET', '/rest/v1/attempts?student_id=eq.' . $user['id'] . '&quest_id=eq.' . $stage['quest_id'] . '&order=score.desc&limit=1&select=score', null, $user['jwt'])['data'][0]['score'] ?? 0;

sb('POST', '/rest/v1/attempts', [
  'student_id' => $user['id'], 'quest_id' => $stage['quest_id'], 'stage_id' => $stageId,
  'score' => $score, 'time_sec' => $time, 'correct_count' => $correct,
], $user['jwt']);

echo json_encode(['score' => $score, 'best' => max($score, $prev), 'new_high' => $score > $prev]);