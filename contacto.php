<?php
require_once __DIR__ . '/auth.php';
require_login();
require_once __DIR__ . '/db.php';
$sucursales = $pdo->query('SELECT nombre, telefono, direccion FROM sucursales ORDER BY nombre')->fetchAll();
$contact = $pdo->query('SELECT * FROM contacto ORDER BY id DESC LIMIT 1')->fetch();
?>
<?php include __DIR__ . '/header.php'; ?>
<h2>Contacto</h2>
<div class="card">
    <h3>Contacto de emergencia</h3>
    <p><?=htmlspecialchars($contact['emergencia'] ?? 'No definido')?></p>
    <?php if (!empty($contact['otras'])): ?>
        <h4>Otras instrucciones</h4>
        <div><?=nl2br(htmlspecialchars($contact['otras']))?></div>
    <?php endif; ?>
</div>
<h3>Sucursales y teléfonos</h3>
<?php foreach ($sucursales as $s): ?>
    <div class="card">
        <h4><?=htmlspecialchars($s['nombre'])?></h4>
        <p>Tel: <?=htmlspecialchars($s['telefono'])?></p>
        <p>Dirección: <?=htmlspecialchars($s['direccion'])?></p>
    </div>
<?php endforeach; ?>
<?php include __DIR__ . '/footer.php'; ?>