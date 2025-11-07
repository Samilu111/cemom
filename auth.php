<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/db.php';

function is_logged_in() {
    return !empty($_SESSION['user_id']);
}

function current_user() {
    global $pdo;
    if (!is_logged_in()) return null;
    $stmt = $pdo->prepare('SELECT id, email, name, role FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: /webcemom/login.php');
        exit;
    }
}

function require_admin() {
    if (!is_logged_in()) {
        header('Location: /webcemom/login.php');
        exit;
    }
    $user = current_user();
    if (!$user || $user['role'] !== 'admin') {
        http_response_code(403);
        echo 'Acceso denegado. Solo administradores.';
        exit;
    }
}

