<?php
require_once __DIR__ . '/auth.php';
// Si no está logueado, lo mandamos al login
if (!is_logged_in()) {
    header('Location: /webcemom/login.php');
    exit;
}
// Redirigir a novedades como página principal
header('Location: /webcemom/novedades.php');
exit;
