<?php
require_once __DIR__ . '/../lib/env.php';
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (!function_exists('e')) {
    function e(string $v): string {
        return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
    }
}

$supabaseUrl = getenv('SUPABASE_URL');
$supabaseKey = getenv('SUPABASE_ANON_KEY');

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

$errors  = [];
$success = false;
$old     = ['username' => '', 'email' => '', 'role' => 'student'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
        $errors[] = 'Invalid form submission. Please refresh and try again.';
    }

    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';
    $role     = $_POST['role'] ?? 'student';

    $old = ['username' => $username, 'email' => $email, 'role' => $role];

    if (!preg_match('/^[A-Za-z0-9_]{3,20}$/', $username)) {
        $errors[] = 'Username must be 3-20 characters (letters, numbers, underscore).';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }
    if ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    }
    if (!in_array($role, ['student', 'teacher'], true)) {
        $errors[] = 'Please choose a valid role.';
    }

    if (!$errors) {
        $ch = curl_init($supabaseUrl . '/auth/v1/signup');   // <- changed line
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'apikey: ' . $supabaseKey,                    // <- changed line
            ],
            CURLOPT_POSTFIELDS     => json_encode([
                'email'    => $email,
                'password' => $password,
                'data'     => ['username' => $username, 'requested_role' => $role],
            ]),
        ]);
        $response = curl_exec($ch);
        $status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $result = json_decode($response, true);

        if ($status >= 200 && $status < 300) {
            $success = true;
            $old = ['username' => '', 'email' => '', 'role' => 'student'];
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        } else {
            $errors[] = $result['msg'] ?? $result['error_description'] ?? 'Registration failed. Please try again.';
        }
    }
}
?>