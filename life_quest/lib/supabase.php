<?php
require_once __DIR__ . '/env.php';

function sb(string $method, string $path, ?array $body = null, ?string $jwt = null): array {
  $anon = env('SUPABASE_ANON_KEY');
  $ch = curl_init(env('SUPABASE_URL') . $path);
  curl_setopt_array($ch, [
    CURLOPT_CUSTOMREQUEST => $method,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_HTTPHEADER => [
      'apikey: ' . $anon,
      'Authorization: Bearer ' . ($jwt ?: $anon),
      'Content-Type: application/json',
      'Prefer: return=representation',
    ],
  ]);
  if ($body !== null) curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
  $res = curl_exec($ch);
  $code = $res === false ? 0 : curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);
  return ['status' => $code, 'data' => $res === false ? null : json_decode($res, true)];
}