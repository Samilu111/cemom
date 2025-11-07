<?php
require_once __DIR__ . '/auth.php';
require_login();
require_once __DIR__ . '/db.php';
$stmt = $pdo->query('SELECT * FROM sobre ORDER BY id DESC LIMIT 1');
$sobre = $stmt->fetch();
?>
<?php include __DIR__ . '/header.php'; ?>
<h2>Sobre nosotros</h2>
<?php if ($sobre): ?>
    <div class="card">
        <h3>Nuestra historia</h3>
        <div><?=nl2br(htmlspecialchars($sobre['historia']))?></div>
        <h4>Objetivos</h4>
        <div><?=nl2br(htmlspecialchars($sobre['objetivos']))?></div>
    </div>
<?php else: ?>
    <p>No hay información disponible.</p>
<?php endif; ?>
<?php include __DIR__ . '/footer.php'; ?>