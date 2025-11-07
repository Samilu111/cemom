<?php
require_once __DIR__ . '/auth.php';
require_login();
require_once __DIR__ . '/db.php';
$sucursal_id = isset($_GET['sucursal']) ? (int)$_GET['sucursal'] : null;
$sucursales = $pdo->query('SELECT id, nombre FROM sucursales ORDER BY nombre')->fetchAll();
$sql = 'SELECT o.*, e.nombre as especialidad, s.nombre as sucursal FROM odontologos o LEFT JOIN especialidades e ON e.id=o.especialidad_id LEFT JOIN sucursales s ON s.id=o.sucursal_id';
if ($sucursal_id) $sql .= ' WHERE o.sucursal_id = '.(int)$sucursal_id;
$stmt = $pdo->query($sql);
$odontologos = $stmt->fetchAll();
?>
<?php include __DIR__ . '/header.php'; ?>
<h2>Odontólogos</h2>
<div class="card">
    <form method="get">
        <label>Filtrar por sucursal</label>
        <select name="sucursal" onchange="this.form.submit()">
            <option value="">Todas</option>
            <?php foreach ($sucursales as $s): ?>
                <option value="<?=$s['id']?>" <?=($sucursal_id == $s['id'])?'selected':''?>><?=htmlspecialchars($s['nombre'])?></option>
            <?php endforeach; ?>
        </select>
    </form>
</div>
<?php if (empty($odontologos)): ?>
    <p>No se encontraron odontólogos.</p>
<?php endif; ?>
<?php foreach ($odontologos as $o): ?>
    <div class="card">
        <h3><?=htmlspecialchars($o['nombre'].' '.$o['apellido'])?></h3>
        <p><strong>Matrícula:</strong> <?=htmlspecialchars($o['matricula'])?></p>
        <p><strong>Especialidad:</strong> <?=htmlspecialchars($o['especialidad'])?></p>
        <p><strong>Sucursal:</strong> <?=htmlspecialchars($o['sucursal'])?></p>
        <?php if (!empty($o['foto'])): ?>
            <img src="/webcemom/uploads/<?=htmlspecialchars($o['foto'])?>" alt="foto" style="max-width:150px">
        <?php endif; ?>
    </div>
<?php endforeach; ?>
<?php include __DIR__ . '/footer.php'; ?>