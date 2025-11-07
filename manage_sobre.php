<?php
require_once __DIR__ . '/auth.php';
require_admin();
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $historia = $_POST['historia'] ?? '';
    $objetivos = $_POST['objetivos'] ?? '';
    $pdo->exec('TRUNCATE TABLE sobre');
    $pdo->prepare('INSERT INTO sobre (historia, objetivos) VALUES (?,?)')->execute([$historia, $objetivos]);
    header('Location: /webcemom/manage_sobre.php'); exit;
}

$sobre = $pdo->query('SELECT * FROM sobre ORDER BY id DESC LIMIT 1')->fetch();
include __DIR__ . '/admin_header.php';
?>
<h2>Editar 'Sobre nosotros'</h2>
<div class="card">
    <form method="post">
        <label>Nuestra historia</label>
        <textarea name="historia" rows="6"><?=htmlspecialchars($sobre['historia'] ?? '')?></textarea>
        <label>Objetivos</label>
        <textarea name="objetivos" rows="4"><?=htmlspecialchars($sobre['objetivos'] ?? '')?></textarea>
        <button type="submit">Guardar</button>
    </form>
</div>
<?php include __DIR__ . '/admin.footer.php'; ?>