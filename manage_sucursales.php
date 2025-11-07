<?php
require_once __DIR__ . '/auth.php';
require_admin();
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $ap_am = $_POST['horario_apertura_am'] ?? null;
$cp_am = $_POST['horario_cierre_am'] ?? null;
$ap_pm = $_POST['horario_apertura_pm'] ?? null;
$cp_pm = $_POST['horario_cierre_pm'] ?? null;

    $id = $_POST['id'] ?? null;
    if ($id) {
    $pdo->prepare('UPDATE sucursales 
        SET nombre=?, direccion=?, telefono=?, 
            horario_apertura_am=?, horario_cierre_am=?, 
            horario_apertura_pm=?, horario_cierre_pm=? 
        WHERE id=?')
        ->execute([$nombre, $direccion, $telefono, $ap_am, $cp_am, $ap_pm, $cp_pm, $id]);
} else {
    $pdo->prepare('INSERT INTO sucursales 
        (nombre, direccion, telefono, 
         horario_apertura_am, horario_cierre_am, 
         horario_apertura_pm, horario_cierre_pm)
        VALUES (?,?,?,?,?,?,?)')
        ->execute([$nombre, $direccion, $telefono, $ap_am, $cp_am, $ap_pm, $cp_pm]);
}

    header('Location: /webcemom/manage_sucursales.php'); exit;
}
if (isset($_GET['delete'])) { $pdo->prepare('DELETE FROM sucursales WHERE id=?')->execute([(int)$_GET['delete']]); header('Location: /webcemom/manage_sucursales.php'); exit; }
$items = $pdo->query('SELECT * FROM sucursales ORDER BY nombre')->fetchAll();
$editing = null;
if (isset($_GET['edit'])) { $editing = $pdo->prepare('SELECT * FROM sucursales WHERE id=?'); $editing->execute([(int)$_GET['edit']]); $editing = $editing->fetch(); }
include __DIR__ . '/admin_header.php';
?>
<h2>Sucursales</h2>
<div class="card">
    <form method="post">
        <input type="hidden" name="id" value="<?= $editing['id'] ?? '' ?>">

        <label>Nombre</label>
        <input type="text" name="nombre" required value="<?= htmlspecialchars($editing['nombre'] ?? '') ?>">

        <label>Dirección</label>
        <input type="text" name="direccion" value="<?= htmlspecialchars($editing['direccion'] ?? '') ?>">

        <label>Teléfono</label>
        <input type="text" name="telefono" value="<?= htmlspecialchars($editing['telefono'] ?? '') ?>">

        <fieldset style="border:1px solid #ddd; padding:1rem; margin-top:1rem;">
            <legend>Horario de Mañana</legend>
            <label>Apertura (AM)</label>
            <input type="time" name="horario_apertura_am" value="<?= htmlspecialchars($editing['horario_apertura_am'] ?? '') ?>">
            <label>Cierre (AM)</label>
            <input type="time" name="horario_cierre_am" value="<?= htmlspecialchars($editing['horario_cierre_am'] ?? '') ?>">
        </fieldset>

        <fieldset style="border:1px solid #ddd; padding:1rem; margin-top:1rem;">
            <legend>Horario de Tarde</legend>
            <label>Apertura (PM)</label>
            <input type="time" name="horario_apertura_pm" value="<?= htmlspecialchars($editing['horario_apertura_pm'] ?? '') ?>">
            <label>Cierre (PM)</label>
            <input type="time" name="horario_cierre_pm" value="<?= htmlspecialchars($editing['horario_cierre_pm'] ?? '') ?>">
        </fieldset>

        <button type="submit"><?= $editing ? 'Actualizar' : 'Crear' ?></button>
    </form>
</div>

<div class="card admin-list">
    <table>
        <thead><tr><th>ID</th><th>Nombre</th><th>Tel</th><th>Acciones</th></tr></thead>
        <tbody>
        <?php foreach ($items as $it): ?>
            <tr><td><?=$it['id']?></td><td><?=htmlspecialchars($it['nombre'])?></td><td><?=htmlspecialchars($it['telefono'])?></td><td><a href="?edit=<?=$it['id']?>">Editar</a> | <a href="?delete=<?=$it['id']?>" onclick="return confirm('Borrar?')">Borrar</a></td></tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include __DIR__ . '/admin.footer.php'; ?>