<?php
require_once __DIR__ . '/auth.php';
require_admin();
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $id = $_POST['id'] ?? null;
    if ($id) {
        $pdo->prepare('UPDATE especialidades SET nombre=? WHERE id=?')->execute([$nombre, $id]);
    } else {
        $pdo->prepare('INSERT INTO especialidades (nombre) VALUES (?)')->execute([$nombre]);
    }
    header('Location: /webcemom/manage_especialidades.php'); exit;
}
if (isset($_GET['delete'])) { $pdo->prepare('DELETE FROM especialidades WHERE id=?')->execute([(int)$_GET['delete']]); header('Location: /webcemom/manage_especialidades.php'); exit; }
$items = $pdo->query('SELECT * FROM especialidades ORDER BY nombre')->fetchAll();
$editing = null;
if (isset($_GET['edit'])) { $editing = $pdo->prepare('SELECT * FROM especialidades WHERE id=?'); $editing->execute([(int)$_GET['edit']]); $editing = $editing->fetch(); }
include __DIR__ . '/admin_header.php';
?>
<h2>Especialidades</h2>
<div class="card">
    <form method="post">
        <input type="hidden" name="id" value="<?= $editing['id'] ?? '' ?>">
        <label>Nombre</label>
        <input type="text" name="nombre" required value="<?=htmlspecialchars($editing['nombre'] ?? '')?>">
        <button type="submit"><?= $editing ? 'Actualizar' : 'Crear' ?></button>
    </form>
</div>
<div class="card admin-list">
    <table>
        <thead><tr><th>ID</th><th>Nombre</th><th>Acciones</th></tr></thead>
        <tbody>
        <?php foreach ($items as $it): ?>
            <tr><td><?=$it['id']?></td><td><?=htmlspecialchars($it['nombre'])?></td><td><a href="?edit=<?=$it['id']?>">Editar</a> | <a href="?delete=<?=$it['id']?>" onclick="return confirm('Borrar?')">Borrar</a></td></tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include __DIR__ . '/admin.footer.php'; ?>