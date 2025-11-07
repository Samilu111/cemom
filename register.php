<?php
require_once __DIR__ . '/db.php';
session_start();

if (!empty($_SESSION['user_id'])) {
    header('Location: /webcemom/index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $name = $_POST['name'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email inválido.';
    }

    if (!$error) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare('INSERT INTO users (email, password, name) VALUES (?, ?, ?)');
            $stmt->execute([$email, $hash, $name]);
            $_SESSION['user_id'] = $pdo->lastInsertId();
            header('Location: /webcemom/novedades.php');
            exit;
        } catch (PDOException $e) {
            $error = 'Error al registrar: este correo ya está en uso.';
        }
    }
}

include __DIR__ . '/header.php';
?>

<main class="auth-page">
  <div class="auth-card">
    <h2>Crear cuenta</h2>

    <?php if ($error): ?>
      <div class="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" autocomplete="off">
      <div class="input-group">
        <label>Nombre</label>
        <input type="text" name="name" placeholder="Tu nombre completo" required>
      </div>

      <div class="input-group">
        <label>Email</label>
        <input type="email" name="email" placeholder="ejemplo@correo.com" required>
      </div>

      <div class="input-group">
        <label>Contraseña</label>
        <input type="password" name="password" placeholder="••••••••" required>
      </div>

      <button type="submit" class="btn btn-accent">Registrarme</button>
    </form>

    <p class="switch-link">¿Ya tienes una cuenta? 
      <a href="/webcemom/login.php">Inicia sesión</a>
    </p>
  </div>
</main>

<?php include __DIR__ . '/footer.php'; ?>
