<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/auth.php';
require_admin();
$user = current_user();
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Panel de Administración - CEMOM</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body class="admin-mode">
<header class="site-header">
    <div class="wrap">
        <div class="logo">
            <a href="/webcemom/novedades.php">
                <img src="logo.png" alt="logo">
            </a>
            <div class="menu-toggle">
  <span></span>
  <span></span>
  <span></span>
</div>
        </div>
        <nav>
            <a href="/webcemom/manage_novedades.php">Novedades</a>
            <a href="/webcemom/manage_odontologos.php">Odontólogos</a>
            <a href="/webcemom/manage_especialidades.php">Especialidades</a>
            <a href="/webcemom/manage_sucursales.php">Sucursales</a>
            <a href="/webcemom/manage_sobre.php">Sobre nosotros</a>
            <a href="/webcemom/manage_contacto.php">Contacto</a>
            <a id="darkToggle" style="margin-left:10px;  cursor:pointer;">Cambiar Modo
  <img id="darkIcon" src="dienteosc.png" alt="Modo oscuro" style="width:20px; height:20px; vertical-align:middle;">
</a>
            <a href="/webcemom/novedades.php" class="btn">Volver al inicio</a>
            <a href="/webcemom/logout.php">Cerrar Sesion (<?=htmlspecialchars($user['name'])?>)</a>
             
</nav>
</div>
</header>
<main class="wrap">
</body>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const toggleBtn = document.getElementById("darkToggle");
  const darkIcon = document.getElementById("darkIcon");
  const body = document.body;

  // Verificar tema guardado
  if (localStorage.getItem("cemom-theme") === "dark") {
    body.classList.add("dark");
    darkIcon.src = "diente.png"; // icono del sol
    darkIcon.alt = "Modo claro";
  }

  toggleBtn.addEventListener("click", () => {
    body.classList.toggle("dark");
    const isDark = body.classList.contains("dark");
    darkIcon.src = isDark ? "diente.png" : "dienteosc.png";
    darkIcon.alt = isDark ? "Modo claro" : "Modo oscuro";
    localStorage.setItem("cemom-theme", isDark ? "dark" : "light");
  });
});
</script>
<script>
const menuToggle = document.querySelector(".menu-toggle");
const nav = document.querySelector(".site-header nav");

menuToggle.addEventListener("click", () => {
  menuToggle.classList.toggle("active");
  nav.classList.toggle("show");
});

</script>
</html>
