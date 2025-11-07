<?php
require_once __DIR__ . '/auth.php';
require_login();
require_once __DIR__ . '/db.php';
$stmt = $pdo->query('SELECT * FROM especialidades ORDER BY nombre');
$items = $stmt->fetchAll();
?>
<?php include __DIR__ . '/header.php'; ?>
<h2>Especialidades</h2>
<?php if (empty($items)): ?>
    <p>No hay especialidades aún.</p>
<?php endif; ?>
<?php foreach ($items as $it): ?>
    <div class="card">
        <h3><?=htmlspecialchars($it['nombre'])?></h3>
    </div>
<?php endforeach; ?>
<?php include __DIR__ . '/footer.php'; ?>