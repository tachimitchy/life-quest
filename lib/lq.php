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
  $h    = array_merge(['apikey: ' . $anon, 'Authorization: Bearer ' . ($jwt ?: $anon)], $headers);
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

// Returns [true|false, message]
function lq_handle_upload(array $user): array {
  $f = $_FILES['reviewer'] ?? null;
  if (!$f || $f['error'] === UPLOAD_ERR_NO_FILE) return [false, 'Please choose a file first.'];
  if (in_array($f['error'], [UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE], true) || $f['size'] > LQ_MAX_BYTES) {
    return [false, 'That file is too big. The limit is 4 MB.'];
  }
  if ($f['error'] !== UPLOAD_ERR_OK) return [false, 'Upload failed. Please try again.'];

  $mime    = (new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
  $allowed = ['application/pdf' => 'pdf', 'image/png' => 'png', 'image/jpeg' => 'jpg'];
  if (!isset($allowed[$mime])) return [false, 'Only PDF files or scanned images (PNG, JPG) are allowed.'];

  $orig = pathinfo((string)$f['name'], PATHINFO_FILENAME);
  $safe = trim(strtolower(preg_replace('/[^A-Za-z0-9_-]+/', '-', $orig)), '-');
  $safe = substr($safe !== '' ? $safe : 'reviewer', 0, 60);
  $path = $user['id'] . '/' . bin2hex(random_bytes(6)) . '-' . $safe . '.' . $allowed[$mime];

  $up = lq_request('POST', '/storage/v1/object/' . LQ_BUCKET . '/' . $path, null, $user['jwt'],
                   ['x-upsert: false'], file_get_contents($f['tmp_name']), $mime);
  if (!lq_ok($up)) {
    $why = is_array($up['data']) ? ($up['data']['message'] ?? '') : '';
    return [false, 'Could not store the file. ' . $why];
  }

  $row = lq_request('POST', '/rest/v1/user_documents', [
    'user_id'   => $user['id'],
    'title'     => mb_substr(trim($orig) !== '' ? trim($orig) : 'Reviewer', 0, 100),
    'file_name' => mb_substr(basename((string)$f['name']), 0, 150),
    'file_path' => $path,
    'file_size' => (int)$f['size'],
    'mime_type' => $mime,
  ], $user['jwt'], ['Prefer: return=minimal']);

  if (!lq_ok($row)) {
    lq_request('DELETE', '/storage/v1/object/' . LQ_BUCKET . '/' . $path, null, $user['jwt']);   // undo
    return [false, 'The file uploaded but could not be saved. Did you run supabase-setup.sql?'];
  }
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