<?php
// Saves a finished game: the score, XP, level-ups and achievements are worked out in the database.
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/lq.php';

header('Content-Type: application/json');
header('Cache-Control: no-store');

function done(array $body, int $code = 200): never {
  http_response_code($code);
  echo json_encode($body);
  exit;
}

$user = current_user();
if (!$user || $user['role'] !== 'student') done(['ok' => false, 'error' => 'Not allowed'], 401);
if ($_SERVER['REQUEST_METHOD'] !== 'POST')  done(['ok' => false, 'error' => 'POST only'], 405);

$in = json_decode((string)file_get_contents('php://input'), true);
if (!is_array($in)) done(['ok' => false, 'error' => 'Bad request'], 400);

lq_session();
if (!hash_equals($_SESSION['lq_csrf'] ?? '', (string)($in['csrf'] ?? ''))) done(['ok' => false, 'error' => 'Please reload the page'], 403);

$docId   = lq_is_uuid((string)($in['doc_id'] ?? '')) ? (string)$in['doc_id'] : null;
$isClass = ($in['doc_type'] ?? '') === 'class';
$int = fn($k, $max) => max(0, min($max, (int)($in[$k] ?? 0)));

$r = lq_rpc('submit_score', [
  'p_doc'       => $isClass ? null : $docId,
  'p_class_doc' => $isClass ? $docId : null,
  'p_score'     => $int('score', 20000),
  'p_correct'   => $int('correct', 500),
  'p_total'     => $int('total', 500),
  'p_seconds'   => $int('seconds', 86400),
  'p_bosses'    => $int('bosses', 3),
  'p_perfect'   => !empty($in['perfect']),
], $user['jwt']);

if (!lq_ok($r)) done(['ok' => false, 'error' => lq_err($r, 'Could not save your score')]);
done(['ok' => true] + (is_array($r['data']) ? $r['data'] : []));