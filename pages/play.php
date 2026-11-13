<?php
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/lq.php';

$user   = require_login(['student']);
$player = lq_player($user);

// Pick the reviewer: a teacher's file (?cdoc=), one of the student's own (?doc=), or the newest one.
function pick_doc(array $user, string $table, string $extra): ?array {
  $rows = lq_rows(lq_request('GET', '/rest/v1/' . $table . '?select=id,title,file_path,mime_type' . $extra, null, $user['jwt']));
  return $rows[0] ?? null;
}
$cdoc = (string)($_GET['cdoc'] ?? '');
$id   = (string)($_GET['doc'] ?? '');
$type = 'own';
if (lq_is_uuid($cdoc)) {
  $doc = pick_doc($user, 'class_documents', '&id=eq.' . rawurlencode($cdoc) . '&limit=1');
  $type = 'class';
} elseif (lq_is_uuid($id)) {
  $doc = pick_doc($user, 'user_documents', '&id=eq.' . rawurlencode($id) . '&limit=1');
} else {
  $doc = pick_doc($user, 'user_documents', '&order=created_at.desc&limit=1');
  if (!$doc) { $doc = pick_doc($user, 'class_documents', '&order=created_at.desc&limit=1'); $type = 'class'; }
}

// No reviewer yet: back to the dashboard, which asks the student to upload one.
if (!$doc) lq_go('/dashboard?need=upload');

// The game page asks for the file itself (?raw=1) so it can read the text inside.
if (isset($_GET['raw'])) {
  $bytes = lq_download($user['jwt'], $doc['file_path']);
  if ($bytes === null) { http_response_code(404); exit('File not found'); }
  header('Content-Type: ' . ($doc['mime_type'] ?: 'application/octet-stream'));
  header('X-Content-Type-Options: nosniff');
  header('Cache-Control: private, no-store');
  header('Content-Length: ' . strlen($bytes));
  echo $bytes;
  exit;
}

header('Cache-Control: no-store, max-age=0');
$rawUrl = '/play?' . ($type === 'class' ? 'cdoc=' : 'doc=') . rawurlencode($doc['id']) . '&raw=1';
$conf = [
  'character' => $player['character'],
  'docTitle'  => $doc['title'],
  'docType'   => $type,
  'docId'     => $doc['id'],
  'mime'      => $doc['mime_type'],
  'rawUrl'    => $rawUrl,
  'saveUrl'   => '/save-score',
  'backUrl'   => '/dashboard',
  'csrf'      => lq_csrf(),
];
$confJson = json_encode($conf, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title>Adventure - Life Quest</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=VT323&display=swap" rel="stylesheet" />
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    [hidden] { display: none !important; }
    :root { --gold: #ffd54a; --gold-dark: #c47f00; --grass: #4caf3d; --grass-dark: #2d7a24; --stone: #8b93a7; --stone-dark: #4b5266; }
    body { min-height: 100vh; display: flex; flex-direction: column; align-items: center; gap: 0.5rem; padding: 0.5rem; background: #0b1026; color: #dbe3ff;
      font-family: "VT323", "Courier New", monospace; user-select: none; -webkit-user-select: none; }
    .bar { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.6rem; width: min(100%, 1000px);
      font-family: "Press Start 2P", "Courier New", monospace; font-size: 0.6rem; color: #fff; }
    .bar b { color: var(--gold); font-weight: 400; }
    .px { display: inline-block; padding: 0.7rem 1rem; font-family: "Press Start 2P", "Courier New", monospace; font-size: 0.6rem; line-height: 1.4; color: #fff; text-decoration: none;
      text-align: center; cursor: pointer; border: 4px solid #000; text-shadow: 2px 2px 0 rgba(0, 0, 0, 0.5); }
    .px:hover { transform: translateY(-2px); }
    .px-green { background: var(--grass); box-shadow: inset -3px -3px 0 var(--grass-dark), inset 3px 3px 0 #8be07a; }
    .px-gold { background: #e0a800; box-shadow: inset -3px -3px 0 var(--gold-dark), inset 3px 3px 0 #ffe27a; }
    .px-stone { background: var(--stone); box-shadow: inset -3px -3px 0 var(--stone-dark), inset 3px 3px 0 #c3c9d8; }
    .wrap { position: relative; width: min(calc(100vw - 16px), calc((100vh - 120px) * 16 / 9), 1000px); aspect-ratio: 16 / 9; }
    canvas { display: block; width: 100%; height: 100%; image-rendering: pixelated; background: #000; border: 4px solid var(--stone); box-shadow: 0 0 0 4px var(--stone-dark); outline: none; }

    .overlay, .qbox { position: fixed; z-index: 50; inset: 0; display: flex; align-items: center; justify-content: center; padding: 0.6rem; background: rgba(5, 7, 26, 0.74); overflow: auto; }
    .card, .qcard { width: min(92%, 640px); max-height: 100%; overflow: auto; padding: clamp(0.6rem, 2vw, 1.2rem); text-align: center; background: #12183a; border: 4px solid var(--stone);
      box-shadow: 0 0 0 4px var(--stone-dark), 6px 6px 0 4px rgba(0, 0, 0, 0.5); font-size: clamp(1rem, 2.4vw, 1.6rem); }
    .card h2 { margin-bottom: 0.6rem; font-family: "Press Start 2P", "Courier New", monospace; font-size: clamp(0.7rem, 2vw, 1rem); line-height: 1.5; color: var(--gold); }
    .card p { margin-bottom: 0.5rem; }
    .card .big { font-size: clamp(1.2rem, 3vw, 2rem); color: #fff; word-break: break-word; }
    .card .keys { color: #8e9ad0; }
    .card textarea { width: 100%; margin-bottom: 0.6rem; padding: 0.5rem; font: inherit; color: #fff; background: #0b1026; border: 3px solid var(--stone-dark); user-select: text; -webkit-user-select: text; }
    .card .btns { display: flex; flex-wrap: wrap; gap: 0.6rem; justify-content: center; margin-top: 0.4rem; }
    .stats2 { display: flex; justify-content: center; gap: 1.2rem; margin: 0.6rem 0; }
    .stats2 div { min-width: 70px; }
    .stats2 b { display: block; font-family: "Press Start 2P", "Courier New", monospace; font-size: clamp(0.8rem, 2.2vw, 1.1rem); font-weight: 400; color: var(--gold); margin-bottom: 0.3rem; }
    .lv { color: #8be07a; font-weight: 400; }
    .qtag { margin-bottom: 0.5rem; font-family: "Press Start 2P", "Courier New", monospace; font-size: clamp(0.5rem, 1.5vw, 0.7rem); color: var(--gold); }
    .qtext { margin-bottom: 0.7rem; font-size: clamp(1.05rem, 2.8vw, 1.8rem); line-height: 1.15; color: #fff; }
    .qopt { display: flex; align-items: center; gap: 0.6rem; width: 100%; margin: 0.35rem 0; padding: 0.45rem 0.7rem; text-align: left; font: inherit; font-size: clamp(0.95rem, 2.3vw, 1.5rem);
      line-height: 1.1; color: #fff; cursor: pointer; background: #1b2150; border: 3px solid #000; box-shadow: inset -3px -3px 0 #121741, inset 3px 3px 0 #2e377a; }
    .qopt:hover { background: #262d6a; }
    .qopt .k { flex: none; width: 1.6em; height: 1.6em; line-height: 1.6em; text-align: center; font-family: "Press Start 2P", monospace; font-size: 0.6em; color: #000; background: var(--gold); }
    .qopt.good { background: #2d7a24; } .qopt.bad { background: #9c2a2e; }
    .touch { display: none; gap: 0.6rem; width: min(100%, 1000px); justify-content: space-between; }
    .touch button { flex: 1; padding: 1rem 0.5rem; font-family: "Press Start 2P", monospace; font-size: 0.8rem; color: #fff; background: #1b2150; border: 4px solid #000; touch-action: none; }
    .touch button:active { background: var(--grass); }
    @media (pointer: coarse) { .touch { display: flex; } .wrap { width: min(calc(100vw - 16px), calc((100vh - 230px) * 16 / 9)); } }
    body.game .lq-music { position: static; z-index: 1; right: auto; bottom: auto; margin: 0.2rem 0 0.4rem; }
  </style>
</head>
<body class="game">
  <header class="bar">
    <a class="px px-stone" href="/dashboard">&larr; Exit</a>
    <span id="h-stage">Stage 1</span>
    <span id="h-hearts">❤️❤️❤️</span>
    <span>Score <b id="h-score">0</b></span>
    <span>Time <b id="h-time">0:00</b></span>
  </header>

  <div class="wrap">
    <canvas id="game" width="400" height="225" tabindex="0" aria-label="Life Quest game"></canvas>
    <div id="overlay" class="overlay" hidden></div>
    <div id="qbox" class="qbox" hidden>
      <div class="qcard"><div class="qtag" id="qtag"></div><div class="qtext" id="qtext"></div><div id="qopts"></div></div>
    </div>
  </div>

  <div class="touch" aria-hidden="true">
    <button type="button" id="t-left">&#9664;</button>
    <button type="button" id="t-jump">JUMP</button>
    <button type="button" id="t-right">&#9654;</button>
  </div>

  <script>window.LQ_CONF = <?= $confJson ?>;</script>
  <?php require __DIR__ . '/../partials/music.php'; ?>
  <?php require __DIR__ . '/../partials/game-questions.php'; ?>
  <?php require __DIR__ . '/../partials/game-engine.php'; ?>
</body>
</html>