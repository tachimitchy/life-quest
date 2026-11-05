<?php
function env(string $key, ?string $default = null): ?string {
  $v = getenv($key);
  if ($v === false || $v === '') $v = $_ENV[$key] ?? $_SERVER[$key] ?? null;
  return ($v === null || $v === '') ? $default : $v;
}?>