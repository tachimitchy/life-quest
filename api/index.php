<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = rtrim($path, '/') ?: '/';

$routes = [
    '/'         => __DIR__ . '/../pages/home.php',
    '/register' => __DIR__ . '/../pages/register.php',
    '/login'    => __DIR__ . '/../pages/login.php',
    '/logout'   => __DIR__ . '/../pages/logout.php',
    '/student'  => __DIR__ . '/../pages/student.php',
    '/teacher'  => __DIR__ . '/../pages/teacher.php',
    '/admin'    => __DIR__ . '/../pages/admin.php',
    '/play'     => __DIR__ . '/../pages/play.php',
];

if (isset($routes[$path])) {
    require $routes[$path];
} else {
    http_response_code(404);
    require __DIR__ . '/../pages/404.php';
}?>