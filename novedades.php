<?php
require_once __DIR__ . '/auth.php';
require_login();
require_once __DIR__ . '/db.php';
$stmt = $pdo->query('SELECT n.*, u.email as author FROM novedades n LEFT JOIN users u ON u.id = n.author_id ORDER BY created_at DESC');
$posts = $stmt->fetchAll();
?>
<?php include __DIR__ . '/header.php'; ?>
<h2>Novedades</h2>

<?php if (empty($posts)): ?>
    <p>No hay novedades aún.</p>
<?php endif; ?>

<?php foreach ($posts as $p): ?>
    <article class="card">
        <h3><?= htmlspecialchars($p['title']) ?></h3>
        <p class="small">
            Por <?= htmlspecialchars($p['author'] ?: 'Anónimo') ?> · 
            <?= date('d/m/Y H:i', strtotime($p['created_at'])) ?>
        </p>

        <?php if (!empty($p['media_url'])): ?>
            <?php if ($p['media_type'] === 'image'): ?>
                <div class="media-container">
                    <img src="<?= htmlspecialchars($p['media_url']) ?>" alt="Imagen de la novedad">
                </div>
            <?php elseif ($p['media_type'] === 'video'): ?>
                <div class="media-container">
                    <video controls>
                        <source src="<?= htmlspecialchars($p['media_url']) ?>" type="video/mp4">
                        Tu navegador no soporta la reproducción de video.
                    </video>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div><?= nl2br(htmlspecialchars($p['body'])) ?></div>
    </article>
<?php endforeach; ?>

<?php include __DIR__ . '/footer.php'; ?>
