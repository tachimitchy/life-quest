<?php
/* Life Quest - profile helpers (edit name, pick character, achievements).
   Used by partials/app-open.php and partials/profile.php. */
require_once __DIR__ . '/characters.php';

/* Small Supabase helper. Returns [httpStatus, decodedJson|null]. */
function lq_prof_http(string $method, string $path, ?array $body, string $jwt, array $extra = []): array {
  $base = rtrim((string)getenv('SUPABASE_URL'), '/');
  $key  = (string)getenv('SUPABASE_ANON_KEY');
  if ($base === '' || $jwt === '') return [0, null];

  $ch = curl_init($base . $path);
  curl_setopt_array($ch, [
    CURLOPT_CUSTOMREQUEST  => $method,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 10,
    CURLOPT_HTTPHEADER     => array_merge([
      'Content-Type: application/json',
      'apikey: ' . $key,
      'Authorization: Bearer ' . $jwt,
    ], $extra),
  ]);
  if ($body !== null) curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
  $res    = curl_exec($ch);
  $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  return [$status, $res === false ? null : json_decode($res, true)];
}

/* Saves a new display name in the profiles table. Returns [ok, message]. */
function lq_profile_set_name(array $user, string $name): array {
  $name = trim((string)preg_replace('/\s+/u', ' ', $name));
  if (!preg_match('/^[\p{L}\p{M} .\'-]{2,60}$/u', $name)) {
    return [false, 'Name must be 2-60 characters (letters, spaces, . \' -).'];
  }

  [$status, $data] = lq_prof_http(
    'PATCH',
    '/rest/v1/profiles?id=eq.' . rawurlencode((string)$user['id']),
    ['full_name' => $name],
    (string)$user['jwt'],
    ['Prefer: return=representation']
  );

  if ($status === 200 && is_array($data) && count($data) > 0) {
    // Keep the sign-in profile data in sync too (best effort).
    lq_char_http('PUT', '/auth/v1/user', ['data' => ['full_name' => $name]], (string)$user['jwt']);
    return [true, 'Name updated!'];
  }
  if ($status === 200) {
    return [false, 'Your name could not be changed (database permission). Run the profile SQL from the instructions.'];
  }
  return [false, 'Could not save your name. Please try again.'];
}

/* Handles the two profile forms. Runs at the top of app-open.php,
   before any HTML is printed, so it can redirect. */
function lq_profile_handle_post(array $user): void {
  if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') return;
  $action = (string)($_POST['action'] ?? '');
  if (!in_array($action, ['profile_name', 'character'], true)) return;

  $back = (string)($_POST['back'] ?? '');
  if (!preg_match('#^/[A-Za-z0-9_\-/]*$#', $back)) $back = home_for_role((string)($user['role'] ?? ''));

  if (!lq_csrf_ok()) {
    lq_flash('error', 'Invalid form submission. Please try again.');
    lq_go($back);
  }

  if ($action === 'profile_name') {
    [$ok, $msg] = lq_profile_set_name($user, (string)($_POST['full_name'] ?? ''));
  } else {
    [$ok, $msg] = lq_char_choose($user, (string)($_POST['character'] ?? ''), lq_player_level($user));
  }
  lq_flash($ok ? 'success' : 'error', $msg);
  lq_go($back);
}

/* Everything the profile window needs. */
function lq_profile_data(array $user): array {
  $level  = lq_player_level($user);
  $charId = lq_char_current($user);
  $meta   = lq_char_meta($user);

  [$st, $docs] = lq_prof_http('GET', '/rest/v1/user_documents?select=id&limit=5', null, (string)$user['jwt']);
  $docCount = ($st === 200 && is_array($docs)) ? count($docs) : 0;
  $chosen   = !empty($meta['character']);

  // [icon, title, how to earn it, earned?]
  $ach = [
    ['🌱', 'Welcome, Adventurer', 'Create your Life Quest account.', true],
    ['🎭', 'Hero Chosen',         'Pick your character.',            $chosen],
    ['📄', 'First Reviewer',      'Upload your first reviewer.',     $docCount >= 1],
    ['📚', 'Bookworm',            'Upload 5 reviewers.',             $docCount >= 5],
    ['⭐', 'Rising Star',         'Reach Level 5.',                  $level >= 5],
    ['🛡️', 'Veteran',             'Reach Level 10.',                 $level >= 10],
    ['🥷', 'Elite',               'Reach Level 15.',                 $level >= 15],
    ['👑', 'Legend',              'Reach Level 20.',                 $level >= 20],
  ];

  $earned = 0;
  foreach ($ach as $a) { if ($a[3]) $earned++; }

  return [
    'level'        => $level,
    'char_id'      => $charId,
    'achievements' => $ach,
    'earned'       => $earned,
  ];
}