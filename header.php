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
                <img src="logo pequeño.jpg" alt="logo">
            </a>
            <div class="menu-toggle">
  <span></span>
  <span></span>
  <span></span>
</div>
        </div>
        <nav>
            <a href="/webcemom/novedades.php">Novedades</a>
            <a href="/webcemom/about.php">Sobre nosotros</a>
            <a href="/webcemom/odontologos.php">Odontólogos</a>
            <a href="/webcemom/especialidades.php">Especialidades</a>
            <a href="/webcemom/sucursales.php">Sucursales</a>
            <a href="/webcemom/contacto.php">Contacto</a>
            <a id="darkToggle" style="margin-left:10px;  cursor:pointer;">Cambiar Modo
  <img id="darkIcon" src="dienteosc.png" alt="Modo oscuro" style="width:20px; height:20px; vertical-align:middle;">
</a>

            <?php if ($user): ?>
                <?php if ($user['role'] === 'admin'): ?>
                    <a href="/webcemom/manage_novedades.php" class="btn">Editar Paginas</a>
                <?php endif; ?>
                <a href="/webcemom/logout.php">Cerrar Sesion (<?=htmlspecialchars($user['name'])?>)</a> 
            <?php else: ?>
                <a href="/webcemom/login.php">Ingresar</a>
                <a href="/webcemom/register.php">Registro</a>
            <?php endif; ?>
            
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
