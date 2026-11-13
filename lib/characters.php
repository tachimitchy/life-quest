<?php
/* Life Quest - playable characters, level locks, and saving the player's choice.
   Used by pages/dashboard.php (the character picker). */
require_once __DIR__ . '/env.php';

/* ------------------------------------------------------------------
   CHARACTER LIST
   'level' = the player level needed to unlock that character.
   Add or change characters here: name, gender label, level, sprite, colors.
   Color keys: H hair/hood  S skin  E eyes  B body  P pants  C accent
               D boots  T hat  W beard
------------------------------------------------------------------- */
function lq_characters(): array {
  return [
    'leo' => [
      'name' => 'Squire Leo', 'gender' => 'Boy', 'level' => 1, 'grid' => 'boy',
      'pal' => ['H'=>'#6b3d1a','S'=>'#f2c9a0','E'=>'#1b1b2f','B'=>'#3d7be0','P'=>'#5b4636','C'=>'#ffd54a','D'=>'#3a2a1c'],
    ],
    'mia' => [
      'name' => 'Squire Mia', 'gender' => 'Girl', 'level' => 1, 'grid' => 'girl',
      'pal' => ['H'=>'#2b1b17','S'=>'#f7d2b0','E'=>'#1b1b2f','B'=>'#e0457b','C'=>'#ffd54a','D'=>'#5a2a3a'],
    ],
    'merlo' => [
      'name' => 'Wizard Merlo', 'gender' => 'Boy', 'level' => 5, 'grid' => 'wizard',
      'pal' => ['T'=>'#6a3fc7','S'=>'#f2c9a0','E'=>'#1b1b2f','W'=>'#f1f1f5','B'=>'#4a2f9e','D'=>'#2c1f55'],
    ],
    'luna' => [
      'name' => 'Witch Luna', 'gender' => 'Girl', 'level' => 5, 'grid' => 'witch',
      'pal' => ['T'=>'#3b2a7a','H'=>'#ff8fd0','S'=>'#f7d2b0','E'=>'#1b1b2f','B'=>'#5b3fc7','C'=>'#ffd54a','D'=>'#2c1f55'],
    ],
    'kai' => [
      'name' => 'Ranger Kai', 'gender' => 'Boy', 'level' => 10, 'grid' => 'boy',
      'pal' => ['H'=>'#2f7d32','S'=>'#e9b98f','E'=>'#1b1b2f','B'=>'#4c9a2a','P'=>'#6b4a2a','C'=>'#c47f00','D'=>'#3a2a1c'],
    ],
    'faye' => [
      'name' => 'Ranger Faye', 'gender' => 'Girl', 'level' => 10, 'grid' => 'girl',
      'pal' => ['H'=>'#8a5a2b','S'=>'#f2c9a0','E'=>'#1b1b2f','B'=>'#3f9a3a','C'=>'#c47f00','D'=>'#3a2a1c'],
    ],
    'shade' => [
      'name' => 'Shadow Ninja', 'gender' => 'Any', 'level' => 15, 'grid' => 'boy',
      'pal' => ['H'=>'#c0392b','S'=>'#2b2b3a','E'=>'#ffffff','B'=>'#222233','P'=>'#222233','C'=>'#c0392b','D'=>'#111118'],
    ],
    'champion' => [
      'name' => 'Golden Champion', 'gender' => 'Any', 'level' => 20, 'grid' => 'boy',
      'pal' => ['H'=>'#ffd54a','S'=>'#f2c9a0','E'=>'#1b1b2f','B'=>'#e6b422','P'=>'#c47f00','C'=>'#d63031','D'=>'#8a5a00'],
    ],
  ];
}

/* Pixel sprites: 12 columns x 14 rows. '.' = empty. */
function lq_char_grids(): array {
  return [
    'boy' => [
      '....HHHH....',
      '...HHHHHH...',
      '...HSSSSH...',
      '...SESSES...',
      '...SSSSSS...',
      '....SSSS....',
      '..BBBBBBBB..',
      '.SBBBBBBBBS.',
      '.SBCCCCCCBS.',
      '..BBBBBBBB..',
      '...PP..PP...',
      '...PP..PP...',
      '...PP..PP...',
      '..DDD..DDD..',
    ],
    'girl' => [
      '....HCCH....',
      '...HHHHHH...',
      '..HHSSSSHH..',
      '..HSESSESH..',
      '..HSSSSSSH..',
      '..HHHSSHHH..',
      '..HBBBBBBH..',
      '.SBBBBBBBBS.',
      '.SBBCCCCBBS.',
      '..BBBBBBBB..',
      '.BBBBBBBBBB.',
      '...SS..SS...',
      '...SS..SS...',
      '..DDD..DDD..',
    ],
    'wizard' => [
      '.....TT.....',
      '....TTTT....',
      '...TTTTTT...',
      '.TTTTTTTTTT.',
      '...SESSES...',
      '...SSSSSS...',
      '...WWWWWW...',
      '..BBWWWWBB..',
      '.SBBBWWBBBS.',
      '.SBBBBBBBBS.',
      '..BBBBBBBB..',
      '..BBBBBBBB..',
      '...BB..BB...',
      '..DDD..DDD..',
    ],
    'witch' => [
      '.....TT.....',
      '....TTTT....',
      '...TTTTTT...',
      '.TTTTTTTTTT.',
      '..HSESSESH..',
      '..HSSSSSSH..',
      '..HHSSSSHH..',
      '..HBBBBBBH..',
      '.SBBBBBBBBS.',
      '.SBBCCCCBBS.',
      '..BBBBBBBB..',
      '.BBBBBBBBBB.',
      '...SS..SS...',
      '..DDD..DDD..',
    ],
  ];
}

/* Draws a character as an inline SVG pixel sprite. */
function lq_char_svg(string $id, string $extraClass = ''): string {
  $chars = lq_characters();
  $c     = $chars[$id] ?? $chars['leo'];
  $grid  = lq_char_grids()[$c['grid']];
  $pal   = $c['pal'];
  $rects = '';
  foreach ($grid as $y => $row) {
    $len = strlen($row);
    $x = 0;
    while ($x < $len) {
      $ch = $row[$x];
      if ($ch === '.' || !isset($pal[$ch])) { $x++; continue; }
      $start = $x;
      while ($x < $len && $row[$x] === $ch) { $x++; }
      $rects .= '<rect x="' . $start . '" y="' . $y . '" width="' . ($x - $start) . '" height="1" fill="' . $pal[$ch] . '"/>';
    }
  }
  return '<svg class="lq-sprite ' . htmlspecialchars($extraClass, ENT_QUOTES, 'UTF-8') . '" viewBox="0 0 12 14" shape-rendering="crispEdges" role="img" aria-label="'
       . htmlspecialchars($c['name'], ENT_QUOTES, 'UTF-8') . '">' . $rects . '</svg>';
}

/* ------------------------------------------------------------------
   TALKING TO SUPABASE (saves the choice in the user's profile data,
   so no new database table is needed)
------------------------------------------------------------------- */
function lq_char_http(string $method, string $path, ?array $body, string $jwt): ?array {
  $base = rtrim((string)getenv('SUPABASE_URL'), '/');
  $key  = (string)getenv('SUPABASE_ANON_KEY');
  if ($base === '' || $jwt === '') return null;

  $ch = curl_init($base . $path);
  curl_setopt_array($ch, [
    CURLOPT_CUSTOMREQUEST  => $method,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 10,
    CURLOPT_HTTPHEADER     => [
      'Content-Type: application/json',
      'apikey: ' . $key,
      'Authorization: Bearer ' . $jwt,
    ],
  ]);
  if ($body !== null) curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
  $res    = curl_exec($ch);
  $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  if ($res === false || $status < 200 || $status >= 300) return null;
  $data = json_decode($res, true);
  return is_array($data) ? $data : null;
}

/* The user's saved profile data (cached for the rest of the request). */
function lq_char_meta(array $user): array {
  static $cache = [];
  $uid = (string)($user['id'] ?? '');
  if (isset($cache[$uid])) return $cache[$uid];
  $d = lq_char_http('GET', '/auth/v1/user', null, (string)($user['jwt'] ?? ''));
  return $cache[$uid] = (is_array($d['user_metadata'] ?? null) ? $d['user_metadata'] : []);
}

/* ------------------------------------------------------------------
   PLAYER LEVEL
   >>> This is the ONE place that decides the player's level. <<<
   Right now it looks for a level saved on the user (user['level'] or
   'level' in their profile data) and falls back to Level 1.
   Once your game saves real XP / level, change it here.
   LQ_DEV_LEVEL is only for testing unlocks: set it as an environment
   variable (e.g. 20) and remove it before going live.
------------------------------------------------------------------- */
function lq_player_level(array $user): int {
  $dev = getenv('LQ_DEV_LEVEL');
  if ($dev !== false && $dev !== '' && ctype_digit($dev)) return max(1, (int)$dev);

  if (isset($user['level']) && is_numeric($user['level'])) return max(1, (int)$user['level']);

  $meta = lq_char_meta($user);
  if (isset($meta['level']) && is_numeric($meta['level'])) return max(1, (int)$meta['level']);

  return 1;
}

/* The character the player is currently using. */
function lq_char_current(array $user): string {
  if (session_status() !== PHP_SESSION_ACTIVE) { @session_start(); }
  $chars = lq_characters();
  $uid   = (string)($user['id'] ?? '');
  $id    = $_SESSION['lq_char'][$uid] ?? null;

  if (!is_string($id) || !isset($chars[$id])) {
    $meta = lq_char_meta($user);
    $id   = (string)($meta['character'] ?? 'leo');
    if (!isset($chars[$id])) $id = 'leo';
    $_SESSION['lq_char'][$uid] = $id;
  }
  return $id;
}

/* Saves a new choice. Checks the level on the server, so locked
   characters cannot be picked by editing the page. Returns [ok, message]. */
function lq_char_choose(array $user, string $id, int $level): array {
  $chars = lq_characters();
  if (!isset($chars[$id])) return [false, 'That character does not exist.'];

  $c = $chars[$id];
  if ($level < $c['level']) return [false, 'Reach Level ' . $c['level'] . ' to unlock ' . $c['name'] . '.'];

  $r = lq_char_http('PUT', '/auth/v1/user', ['data' => ['character' => $id]], (string)($user['jwt'] ?? ''));
  if ($r === null) return [false, 'Could not save your character. Please try again.'];

  if (session_status() !== PHP_SESSION_ACTIVE) { @session_start(); }
  $_SESSION['lq_char'][(string)($user['id'] ?? '')] = $id;
  return [true, $c['name'] . ' is ready for adventure!'];
}