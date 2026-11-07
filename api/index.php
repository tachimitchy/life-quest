<?php
// Turn these two OFF before you publish the site, so visitors never see raw errors.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = rtrim($path, '/') ?: '/';

// Address in the browser => file inside /pages
$routes = [
    '/'          => 'landing.php',
    '/about'     => 'about.php',
    '/register'  => 'register.php',
    '/login'     => 'login.php',
    '/logout'    => 'logout.php',
    '/dashboard' => 'dashboard.php',
    '/student'   => 'student.php',
    '/teacher'   => 'teacher.php',
    '/admin'     => 'admin.php',
    '/play'      => 'play.php',
];

$file = isset($routes[$path]) ? __DIR__ . '/../pages/' . $routes[$path] : null;

if ($file && is_file($file)) {
    require $file;
} else {
    http_response_code(404);
    require __DIR__ . '/../pages/404.php';
}