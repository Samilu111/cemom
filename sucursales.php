<?php
require_once __DIR__ . '/auth.php';
require_login();
require_once __DIR__ . '/db.php';
$stmt = $pdo->query('SELECT * FROM sucursales ORDER BY nombre');
$sucursales = $stmt->fetchAll();
?>
<?php include __DIR__ . '/header.php'; ?>
<h2>Sucursales</h2>
<?php if (empty($sucursales)): ?>
    <p>No hay sucursales registradas.</p>
<?php endif; ?>
<?php foreach ($sucursales as $s): ?>
    <div class="card">
        <h3><?=htmlspecialchars($s['nombre'])?></h3>
        <p><strong>Dirección:</strong> <?=htmlspecialchars($s['direccion'])?></p>
        <p><strong>Teléfono:</strong> <?=htmlspecialchars($s['telefono'])?></p>
        <p class="small">
    <?php
    $am_inicio = $s['horario_apertura_am'];
    $am_fin = $s['horario_cierre_am'];
    $pm_inicio = $s['horario_apertura_pm'];
    $pm_fin = $s['horario_cierre_pm'];

    $partes = [];

    // Mostrar solo si ambos existen y no son 00:00:00
    if (!empty($am_inicio) && !empty($am_fin) && $am_inicio !== '00:00:00' && $am_fin !== '00:00:00') {
        $partes[] = date('H:i', strtotime($am_inicio)) . " am - " . date('H:i', strtotime($am_fin)) . " am";
    }

    if (!empty($pm_inicio) && !empty($pm_fin) && $pm_inicio !== '00:00:00' && $pm_fin !== '00:00:00') {
        $partes[] = date('H:i', strtotime($pm_inicio)) . " pm - " . date('H:i', strtotime($pm_fin)) . " pm";
    }

    if (!empty($partes)) {
        echo "<strong>Horario:</strong> " . implode(' y ', $partes);
    } else {
        echo "<strong>Horario:</strong> No disponible";
    }
    ?>
</p>


        <?php if (!empty($s['direccion'])): ?>
            <div class="map" style="margin-top:.5rem">
                <iframe width="100%" height="250" frameborder="0" style="border:0"
                    src="https://www.google.com/maps?q=<?=urlencode($s['direccion'])?>&output=embed" allowfullscreen>
                </iframe>
            </div>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
<?php include __DIR__ . '/footer.php'; ?>
