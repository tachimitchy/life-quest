<?php
// Change this one line if your images live somewhere else.
$assets = '/css/assets';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Life Quest | Study. Play. Achieve.</title>
    <meta name="description" content="Life Quest turns your reviewer into a platformer adventure.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700&family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body class="bg-scene">
    <main class="screen landing">
        <div class="logo-wrap">
            <img src="<?= $assets ?>/logo.png" alt="Life Quest - Study. Play. Achieve." class="logo-img">
        </div>

        <nav class="menu" aria-label="Main menu">
            <a href="/login" class="btn btn-login">Login</a>
            <a href="/register" class="btn btn-register">Register</a>
            <a href="/about" class="btn btn-about">About</a>
        </nav>

        <img src="<?= $assets ?>/characters.png" alt="" class="characters" aria-hidden="true">
    </main>
</body>
</html>