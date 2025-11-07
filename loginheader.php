<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/auth.php';
$user = is_logged_in() ? current_user() : null;
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>CEMOM Odontología</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header class="site-header">
    <div class="wrap">
        <div class="logo">
            <a href="/webcemom/index.php">
                <img src="logo pequeño.jpg" alt="CEMOM Logo">
            </a>
        </div>
    </div>
</header>
<main class="wrap">
</body>
</html>