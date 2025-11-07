<?php
require_once __DIR__ . '/auth.php';
require_admin();
require_once __DIR__ . '/db.php';

$uploadDir = __DIR__ . '/uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$especialidades = $pdo->query('SELECT * FROM especialidades')->fetchAll();
$sucursales = $pdo->query('SELECT * FROM sucursales')->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $matricula = $_POST['matricula'] ?? '';
    $especialidad_id = $_POST['especialidad_id'] ?: null;
    $sucursal_id = $_POST['sucursal_id'] ?: null;
    $id = $_POST['id'] ?? null;
    $fotoName = null;
    if (!empty($_FILES['foto']['tmp_name']) && is_uploaded_file($_FILES['foto']['tmp_name'])) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $fotoName = uniqid('foto_') . '.' . preg_replace('/[^a-zA-Z0-9]/', '', $ext);
        $dest = $uploadDir . $fotoName;
        move_uploaded_file($_FILES['foto']['tmp_name'], $dest);
    }

    if ($id) {
        if ($fotoName) {
            $pdo->prepare('UPDATE odontologos SET nombre=?, apellido=?, matricula=?, foto=?, especialidad_id=?, sucursal_id=? WHERE id=?')
                ->execute([$nombre,$apellido,$matricula,$fotoName,$especialidad_id,$sucursal_id,$id]);
        } else {
            $pdo->prepare('UPDATE odontologos SET nombre=?, apellido=?, matricula=?, especialidad_id=?, sucursal_id=? WHERE id=?')
                ->execute([$nombre,$apellido,$matricula,$especialidad_id,$sucursal_id,$id]);
        }
    } else {
        $pdo->prepare('INSERT INTO odontologos (nombre, apellido, matricula, foto, especialidad_id, sucursal_id) VALUES (?,?,?,?,?,?)')
            ->execute([$nombre,$apellido,$matricula,$fotoName,$especialidad_id,$sucursal_id]);
    }
    header('Location: /webcemom/manage_odontologos.php'); exit;
}
if (isset($_GET['delete'])) { $id=(int)$_GET['delete']; $pdo->prepare('DELETE FROM odontologos WHERE id=?')->execute([$id]); header('Location: /webcemom/manage_odontologos.php'); exit; }

$items = $pdo->query('SELECT o.*, e.nombre as especialidad, s.nombre as sucursal FROM odontologos o LEFT JOIN especialidades e ON e.id=o.especialidad_id LEFT JOIN sucursales s ON s.id=o.sucursal_id ORDER BY o.apellido')->fetchAll();
$editing = null;
if (isset($_GET['edit'])) { $editing = $pdo->prepare('SELECT * FROM odontologos WHERE id=?'); $editing->execute([(int)$_GET['edit']]); $editing = $editing->fetch(); }
include __DIR__ . '/admin_header.php';
?>
<h2>Odontólogos</h2>
<div class="card">
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $editing['id'] ?? '' ?>">
        <label>Nombre</label>
        <input type="text" name="nombre" required value="<?=htmlspecialchars($editing['nombre'] ?? '')?>">
        <label>Apellido</label>
        <input type="text" name="apellido" required value="<?=htmlspecialchars($editing['apellido'] ?? '')?>">
        <label>Matrícula</label>
        <input type="text" name="matricula" value="<?=htmlspecialchars($editing['matricula'] ?? '')?>">
        <label>Especialidad</label>
        <select name="especialidad_id">
            <option value="">--</option>
            <?php foreach ($especialidades as $es): ?><option value="<?=$es['id']?>" <?=($editing && $editing['especialidad_id']==$es['id'])?'selected':''?>><?=htmlspecialchars($es['nombre'])?></option><?php endforeach; ?>
        </select>
        <label>Sucursal</label>
        <select name="sucursal_id">
            <option value="">--</option>
            <?php foreach ($sucursales as $s): ?><option value="<?=$s['id']?>" <?=($editing && $editing['sucursal_id']==$s['id'])?'selected':''?>><?=htmlspecialchars($s['nombre'])?></option><?php endforeach; ?>
        </select>
        <label>Foto (opcional)</label>
        <input type="file" name="foto" accept="image/*">
        <button type="submit"><?= $editing ? 'Actualizar' : 'Crear' ?></button>
    </form>
</div>
<div class="card admin-list">
    <table>
        <thead><tr><th>Nombre</th><th>Especialidad</th><th>Sucursal</th><th>Acciones</th></tr></thead>
        <tbody>
        <?php foreach ($items as $it): ?>
            <tr>
                <td><?=htmlspecialchars($it['apellido'].', '.$it['nombre'])?></td>
                <td><?=htmlspecialchars($it['especialidad'])?></td>
                <td><?=htmlspecialchars($it['sucursal'])?></td>
                <td><a href="?edit=<?=$it['id']?>">Editar</a> | <a href="?delete=<?=$it['id']?>" onclick="return confirm('Borrar?')">Borrar</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include __DIR__ . '/admin.footer.php'; ?>