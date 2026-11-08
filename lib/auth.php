<?php
require_once __DIR__ . '/env.php';
require_once __DIR__ . '/supabase.php';
require_once __DIR__ . '/view.php';

const AT_COOKIE = 'lq_at';   // access token (JWT)
const RT_COOKIE = 'lq_rt';   // refresh token

// Where to send people. Change these to match your real page URLs.
const LOGIN_PATH = '/login.php';

if (!function_exists('is_https')) {
  function is_https(): bool {
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
  }
}

function set_auth_cookies(string $access, string $refresh, int $expiresIn): void {
  $base = ['path' => '/', 'secure' => is_https(), 'httponly' => true, 'samesite' => 'Lax'];
  setcookie(AT_COOKIE, $access,  $base + ['expires' => time() + $expiresIn]);
  setcookie(RT_COOKIE, $refresh, $base + ['expires' => time() + 60 * 60 * 24 * 30]);
  $_COOKIE[AT_COOKIE] = $access;
  $_COOKIE[RT_COOKIE] = $refresh;
}

function clear_auth_cookies(): void {
  $base = ['path' => '/', 'secure' => is_https(), 'httponly' => true, 'samesite' => 'Lax'];
  foreach ([AT_COOKIE, RT_COOKIE] as $name) {
    setcookie($name, '', $base + ['expires' => time() - 3600]);
    unset($_COOKIE[$name]);
  }
}

function jwt_payload(string $jwt): ?array {
  $parts = explode('.', $jwt);
  if (count($parts) !== 3) return null;
  $json = base64_decode(strtr($parts[1], '-_', '+/'));
  $data = $json ? json_decode($json, true) : null;
  return is_array($data) ? $data : null;
}

// Returns an error message, or null on success.
function auth_login(string $email, string $password): ?string {
  $r = sb('POST', '/auth/v1/token?grant_type=password', ['email' => $email, 'password' => $password]);
  if ($r['status'] !== 200 || empty($r['data']['access_token'])) {
    return $r['data']['error_description'] ?? $r['data']['msg'] ?? 'Invalid email or password.';
  }
  set_auth_cookies($r['data']['access_token'], $r['data']['refresh_token'], (int)($r['data']['expires_in'] ?? 3600));
  return null;
}

function auth_refresh(): bool {
  $rt = $_COOKIE[RT_COOKIE] ?? '';
  if ($rt === '') return false;
  $r = sb('POST', '/auth/v1/token?grant_type=refresh_token', ['refresh_token' => $rt]);
  if ($r['status'] !== 200 || empty($r['data']['access_token'])) {
    clear_auth_cookies();   // stale token: start clean
    return false;
  }
  set_auth_cookies($r['data']['access_token'], $r['data']['refresh_token'], (int)($r['data']['expires_in'] ?? 3600));
  return true;
}

function auth_logout(): void {
  if (!empty($_COOKIE[AT_COOKIE])) sb('POST', '/auth/v1/logout', null, $_COOKIE[AT_COOKIE]);
  clear_auth_cookies();
}

// ['id','email','jwt','full_name','username','role'] or null
function current_user(): ?array {
  static $cache = false;
  if ($cache !== false) return $cache;
  $cache = null;

  $jwt = $_COOKIE[AT_COOKIE] ?? '';
  $p = $jwt !== '' ? jwt_payload($jwt) : null;

  if (!$p || ($p['exp'] ?? 0) < time() + 30) {
    if (!auth_refresh()) return null;
    $jwt = $_COOKIE[AT_COOKIE];
    $p = jwt_payload($jwt);
  }
  $id = $p['sub'] ?? null;
  if (!$id) return null;

  // FIX: your profiles table has full_name, not username.
  // PostgREST verifies the JWT for us; a bad token comes back as 401.
  $r = sb('GET', '/rest/v1/profiles?id=eq.' . rawurlencode($id) . '&select=full_name,role', null, $jwt);
  $row = $r['data'][0] ?? null;
  if ($r['status'] !== 200 || !$row) return null;

  $name = $row['full_name'] ?: explode('@', $p['email'] ?? 'player')[0];

  return $cache = [
    'id' => $id, 'email' => $p['email'] ?? '', 'jwt' => $jwt,
    'full_name' => $name,
    'username'  => $name,          // kept so older pages that use "username" still work
    'role' => $row['role'],
  ];
}

function home_for_role(string $role): string {
  return ['student' => '/dashboard', 'teacher' => '/teacher', 'admin' => '/admin'][$role] ?? '/';
}

// Use at the top of pages (redirects) or actions ($json = true -> JSON errors).
// Call it BEFORE any HTML is printed, because it sets cookies and redirects.
function require_login(array $roles = [], bool $json = false): array {
  $u = current_user();
  if (!$u) {
    $json ? json_out(['error' => 'Not logged in'], 401) : redirect(LOGIN_PATH);
  }
  if ($roles && !in_array($u['role'], $roles, true)) {
    $json ? json_out(['error' => 'Forbidden'], 403) : redirect(home_for_role($u['role']));
  }
  return $u;
}