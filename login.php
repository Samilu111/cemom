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
    $stmt = $pdo->prepare('SELECT id, password FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: /webcemom/novedades.php');
        exit;
    } else {
        $error = 'Credenciales inválidas.';
    }
}
include __DIR__ . '/loginheader.php';
?>
<h2>Ingresar</h2>
<?php if ($error): ?><div class="card"><?=htmlspecialchars($error)?></div><?php endif; ?>
<div class="card">
    <form method="post">
        <label>Email</label>
        <input type="email" name="email" required>
        <label>Contraseña</label>
        <input type="password" name="password" required>
        <button type="submit">Ingresar</button>
    </form>
    <p class="small">¿No tienes cuenta? <a href="/webcemom/register.php">Regístrate</a></p>
</div>
<?php include __DIR__ . '/footer.php'; ?>