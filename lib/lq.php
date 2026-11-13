<?php
// Shared helpers for the dashboard pages (database calls, uploads, flash messages, theme).
require_once __DIR__ . '/env.php';
date_default_timezone_set('Asia/Manila');   // times on the pages are shown in Philippine time

const LQ_BUCKET    = 'reviewers';
const LQ_MAX_BYTES = 4 * 1024 * 1024;       // 4 MB (Vercel limits request size; raise on your own server)

function lq_e($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

function lq_session(): void {
  if (session_status() !== PHP_SESSION_ACTIVE) session_start();
}

function lq_go(string $to): never {
  header('Location: ' . $to);
  exit;
}

function lq_csrf(): string {
  lq_session();
  if (empty($_SESSION['lq_csrf'])) $_SESSION['lq_csrf'] = bin2hex(random_bytes(32));
  return $_SESSION['lq_csrf'];
}

function lq_csrf_ok(): bool {
  lq_session();
  return hash_equals($_SESSION['lq_csrf'] ?? '', (string)($_POST['csrf'] ?? ''));
}

function lq_flash(string $type, string $msg): void {
  lq_session();
  $_SESSION['lq_flash'] = ['type' => $type, 'msg' => $msg];
}

function lq_flash_pull(): ?array {
  lq_session();
  $f = $_SESSION['lq_flash'] ?? null;
  unset($_SESSION['lq_flash']);
  return $f;
}

// ---------- Supabase calls (always sent with the logged-in user's token) ----------
function lq_request(string $method, string $path, ?array $json = null, ?string $jwt = null,
                    array $headers = [], ?string $raw = null, ?string $mime = null): array {
  $anon = (string)getenv('SUPABASE_ANON_KEY');
  $url  = rtrim((string)getenv('SUPABASE_URL'), '/') . $path;
  $h    = ['apikey: ' . $anon];
  if ($jwt) $h[] = 'Authorization: Bearer ' . $jwt;   // only the logged-in user's token goes here
  $h    = array_merge($h, $headers);
  $body = null;
  if ($raw !== null) {
    $body = $raw;
    $h[]  = 'Content-Type: ' . ($mime ?: 'application/octet-stream');
  } elseif ($json !== null) {
    $body = json_encode($json);
    $h[]  = 'Content-Type: application/json';
  }
  $ch = curl_init($url);
  curl_setopt_array($ch, [
    CURLOPT_CUSTOMREQUEST  => $method,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 30,
    CURLOPT_HTTPHEADER     => $h,
  ]);
  if ($body !== null) curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
  $res    = curl_exec($ch);
  $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);
  return ['status' => $status, 'data' => ($res !== false && $res !== '') ? json_decode($res, true) : null];
}

function lq_ok(array $r): bool   { return $r['status'] >= 200 && $r['status'] < 300; }
function lq_rows(array $r): array { return (lq_ok($r) && is_array($r['data'])) ? $r['data'] : []; }
function lq_is_uuid(string $v): bool { return (bool)preg_match('/^[0-9a-fA-F-]{36}$/', $v); }

// ---------- Files ----------
function lq_signed_url(string $jwt, string $path): ?string {
  $r = lq_request('POST', '/storage/v1/object/sign/' . LQ_BUCKET . '/' . $path, ['expiresIn' => 600], $jwt);
  $u = is_array($r['data']) ? ($r['data']['signedURL'] ?? null) : null;
  return $u ? rtrim((string)getenv('SUPABASE_URL'), '/') . '/storage/v1' . $u : null;
}

// Checks the uploaded file and stores it in private storage. Returns ['ok'=>bool,'msg'=>string, ...file details]
function lq_store_upload(array $user): array {
  $f = $_FILES['reviewer'] ?? null;
  if (!$f || $f['error'] === UPLOAD_ERR_NO_FILE) return ['ok' => false, 'msg' => 'Please choose a file first.'];
  if (in_array($f['error'], [UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE], true) || $f['size'] > LQ_MAX_BYTES) {
    return ['ok' => false, 'msg' => 'That file is too big. The limit is 4 MB.'];
  }
  if ($f['error'] !== UPLOAD_ERR_OK) return ['ok' => false, 'msg' => 'Upload failed. Please try again.'];

  $mime    = (new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
  $allowed = ['application/pdf' => 'pdf', 'image/png' => 'png', 'image/jpeg' => 'jpg'];
  if (!isset($allowed[$mime])) return ['ok' => false, 'msg' => 'Only PDF files or scanned images (PNG, JPG) are allowed.'];

  $orig = pathinfo((string)$f['name'], PATHINFO_FILENAME);
  $safe = trim(strtolower(preg_replace('/[^A-Za-z0-9_-]+/', '-', $orig)), '-');
  $safe = substr($safe !== '' ? $safe : 'reviewer', 0, 60);
  $path = $user['id'] . '/' . bin2hex(random_bytes(6)) . '-' . $safe . '.' . $allowed[$mime];

  $up = lq_request('POST', '/storage/v1/object/' . LQ_BUCKET . '/' . $path, null, $user['jwt'],
                   ['x-upsert: false'], file_get_contents($f['tmp_name']), $mime);
  if (!lq_ok($up)) {
    return ['ok' => false, 'msg' => 'Could not store the file. ' . lq_err($up, '')];
  }
  return [
    'ok' => true, 'msg' => '', 'path' => $path, 'mime' => $mime, 'size' => (int)$f['size'],
    'title'     => mb_substr(trim($orig) !== '' ? trim($orig) : 'Reviewer', 0, 100),
    'file_name' => mb_substr(basename((string)$f['name']), 0, 150),
  ];
}

// Downloads a private file with the logged-in user's token (the game reads the reviewer through the site itself).
function lq_download(string $jwt, string $path): ?string {
  $ch = curl_init(rtrim((string)getenv('SUPABASE_URL'), '/') . '/storage/v1/object/authenticated/' . LQ_BUCKET . '/' . $path);
  curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 30,
    CURLOPT_HTTPHEADER     => ['apikey: ' . (string)getenv('SUPABASE_ANON_KEY'), 'Authorization: Bearer ' . $jwt],
  ]);
  $body   = curl_exec($ch);
  $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);
  return ($body !== false && $status === 200) ? $body : null;
}

function lq_delete_object(string $jwt, string $path): void {
  lq_request('DELETE', '/storage/v1/object/' . LQ_BUCKET . '/' . $path, null, $jwt);
}

// Student upload. Returns [true|false, message]
function lq_handle_upload(array $user): array {
  $u = lq_store_upload($user);
  if (!$u['ok']) return [false, $u['msg']];

  $row = lq_request('POST', '/rest/v1/user_documents', [
    'user_id' => $user['id'], 'title' => $u['title'], 'file_name' => $u['file_name'],
    'file_path' => $u['path'], 'file_size' => $u['size'], 'mime_type' => $u['mime'],
  ], $user['jwt'], ['Prefer: return=minimal']);

  if (!lq_ok($row)) {
    lq_delete_object($user['jwt'], $u['path']);   // undo
    return [false, 'The file uploaded but could not be saved. Did you run supabase-setup.sql?'];
  }
  lq_award($user['jwt'], 'first_upload');
  return [true, 'Reviewer uploaded! You can play it anytime.'];
}

// ---------- Theme (Day / Night) ----------
function lq_is_night(): bool { return (($_COOKIE['lq_theme'] ?? '') === 'night'); }

function lq_set_theme(string $theme): void {
  $theme = $theme === 'night' ? 'night' : 'day';
  setcookie('lq_theme', $theme, ['expires' => time() + 31536000, 'path' => '/', 'samesite' => 'Lax']);
  $_COOKIE['lq_theme'] = $theme;
}

// ---------- Display helpers ----------
function lq_when(?string $iso): string {
  if (!$iso) return '';
  try {
    $d = new DateTime($iso);
    $d->setTimezone(new DateTimeZone('Asia/Manila'));
    return $d->format('M j, Y g:i A');
  } catch (Throwable $e) {
    return '';
  }
}

function lq_size($bytes): string {
  $b = (int)$bytes;
  return $b >= 1048576 ? round($b / 1048576, 1) . ' MB' : max(1, (int)round($b / 1024)) . ' KB';
}

// ---------- Characters, achievements, player data ----------
function lq_characters(): array {
  return [
    'boy'    => ['name' => 'Adventurer', 'tag' => 'Boy',    'level' => 1],
    'girl'   => ['name' => 'Explorer',   'tag' => 'Girl',   'level' => 1],
    'ranger' => ['name' => 'Ranger',     'tag' => 'Hooded', 'level' => 3],
    'knight' => ['name' => 'Knight',     'tag' => 'Boy',    'level' => 5],
    'mage'   => ['name' => 'Mage',       'tag' => 'Girl',   'level' => 8],
    'ninja'  => ['name' => 'Ninja',      'tag' => 'Shadow', 'level' => 10],
  ];
}

function lq_achievements(): array {
  return [
    'first_upload' => ['📄', 'First Reviewer', 'Upload your first reviewer.'],
    'joined_class' => ['🎓', 'Class Member',   'Join a teacher\'s class with a code.'],
    'level_5'      => ['⭐', 'Rising Star',    'Reach level 5.'],
    'level_10'     => ['🌟', 'Legend',         'Reach level 10.'],
    'first_boss'   => ['🐉', 'Boss Slayer',    'Defeat your first boss.'],
    'high_score'   => ['🏆', 'Top Scorer',     'Get a perfect score in a game.'],
  ];
}

function lq_sprite(string $id, string $cls = ''): string {
  return '<i class="spr spr-' . lq_e($id) . ($cls !== '' ? ' ' . lq_e($cls) : '') . '"></i>';
}

// Character, level and xp (safe defaults if the part-2 SQL has not been run yet)
function lq_player(array $user): array {
  static $cache = null;
  if ($cache !== null) return $cache;
  $cache = ['character' => 'boy', 'level' => 1, 'xp' => 0];
  $rows = lq_rows(lq_request('GET', '/rest/v1/profiles?select=character_id,level,xp&id=eq.' . rawurlencode($user['id']), null, $user['jwt']));
  if ($rows) {
    $c = (string)($rows[0]['character_id'] ?? 'boy');
    $cache['character'] = isset(lq_characters()[$c]) ? $c : 'boy';
    $cache['level']     = max(1, (int)($rows[0]['level'] ?? 1));
    $cache['xp']        = max(0, (int)($rows[0]['xp'] ?? 0));
  }
  return $cache;
}

function lq_rpc(string $fn, array $args, string $jwt): array {
  return lq_request('POST', '/rest/v1/rpc/' . $fn, $args, $jwt);
}

function lq_award(string $jwt, string $id): void {
  lq_rpc('award_achievement', ['p_id' => $id], $jwt);
}

function lq_err(array $r, string $fallback): string {
  return (is_array($r['data']) && !empty($r['data']['message'])) ? (string)$r['data']['message'] : $fallback;
}

// Sets a role with the server-only service key (used at sign-up for Teacher and Admin accounts).
function lq_service_set_role(string $uid, string $role): bool {
  $key = (string)getenv('SUPABASE_SERVICE_ROLE_KEY');
  if ($key === '' || !in_array($role, ['student', 'teacher', 'admin'], true)) return false;
  $ch = curl_init(rtrim((string)getenv('SUPABASE_URL'), '/') . '/rest/v1/profiles?id=eq.' . rawurlencode($uid));
  curl_setopt_array($ch, [
    CURLOPT_CUSTOMREQUEST  => 'PATCH',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 15,
    CURLOPT_POSTFIELDS     => json_encode(['role' => $role]),
    CURLOPT_HTTPHEADER     => array_merge(
      ['apikey: ' . $key, 'Content-Type: application/json', 'Prefer: return=minimal'],
      // New "sb_secret_..." keys go in apikey only. Old service_role keys are JWTs and also go in Authorization.
      str_starts_with($key, 'sb_') ? [] : ['Authorization: Bearer ' . $key]
    ),
  ]);
  curl_exec($ch);
  $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);
  return $status >= 200 && $status < 300;
}

// A short class code that is easy to read out loud (no 0/O or 1/I)
function lq_make_code(int $len = 6): string {
  $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
  $out = '';
  for ($i = 0; $i < $len; $i++) $out .= $chars[random_int(0, strlen($chars) - 1)];
  return $out;
}