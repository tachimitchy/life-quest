<?php
function e(?string $v): string {
  return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

function redirect(string $to): void {
  header('Location: ' . $to);
  exit;
}

function is_https(): bool {
  return !empty($_SERVER['HTTPS']) || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https';
}

function json_out(array $data, int $status = 200): void {
  http_response_code($status);
  header('Content-Type: application/json');
  echo json_encode($data);
  exit;
}

// partial('header', ['title' => 'Student']) -> includes partials/header.php
function partial(string $name, array $data = []): void {
  extract($data, EXTR_SKIP);
  require __DIR__ . '/../partials/' . $name . '.php';
}

// CSRF without sessions (double-submit cookie)
function csrf_token(): string {
  if (empty($_COOKIE['lq_csrf'])) {
    $t = bin2hex(random_bytes(32));
    setcookie('lq_csrf', $t, [
      'path' => '/', 'secure' => is_https(), 'httponly' => true, 'samesite' => 'Lax',
    ]);
    $_COOKIE['lq_csrf'] = $t;
  }
  return $_COOKIE['lq_csrf'];
}

function csrf_check(): bool {
  $c = $_COOKIE['lq_csrf'] ?? '';
  return $c !== '' && hash_equals($c, $_POST['csrf'] ?? '');
}?>