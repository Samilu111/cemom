<?php
require_once __DIR__ . '/auth.php';
require_admin();
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emergencia = $_POST['emergencia'] ?? '';
    $otras = $_POST['otras'] ?? '';
    $pdo->exec('TRUNCATE TABLE contacto');
    $pdo->prepare('INSERT INTO contacto (emergencia, otras) VALUES (?,?)')->execute([$emergencia, $otras]);
    header('Location: /webcemom/manage_contacto.php'); exit;
}

$c = $pdo->query('SELECT * FROM contacto ORDER BY id DESC LIMIT 1')->fetch();
include __DIR__ . '/admin_header.php';
?>
<h2>Contacto</h2>
<div class="card">
    <form method="post">
        <label>Teléfono de emergencia</label>
        <input type="text" name="emergencia" value="<?=htmlspecialchars($c['emergencia'] ?? '')?>">
        <label>Otras instrucciones / datos</label>
        <textarea name="otras" rows="4"><?=htmlspecialchars($c['otras'] ?? '')?></textarea>
        <button type="submit">Guardar</button>
    </form>
</div>
<?php include __DIR__ . '/admin.footer.php'; ?>