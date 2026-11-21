<?php
require_once __DIR__ . '/../lib/auth.php';

auth_logout();          // clears the login cookies and signs out of Supabase
header('Location: /');  // back to the landing page; the user must log in again
exit;