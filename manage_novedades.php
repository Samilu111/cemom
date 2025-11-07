<?php
require_once __DIR__ . '/auth.php';
require_admin();
require_once __DIR__ . '/db.php';

$uploadDir = __DIR__ . '/uploads/';

// Crear carpeta uploads si no existe
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Crear/Editar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $body = $_POST['body'] ?? '';
    $id = $_POST['id'] ?? null;
    $mediaType = null;
    $mediaUrl = null;

    // Manejo de subida de archivos (imagen o video)
    if (!empty($_FILES['media']['name'])) {
        $fileTmp = $_FILES['media']['tmp_name'];
        $fileName = basename($_FILES['media']['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $newName = uniqid('media_') . '.' . $fileExt;
        $targetPath = $uploadDir . $newName;

        if (in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif'])) {
            $mediaType = 'image';
        } elseif (in_array($fileExt, ['mp4', 'webm', 'ogg'])) {
            $mediaType = 'video';
        }

        if ($mediaType) {
            move_uploaded_file($fileTmp, $targetPath);
            $mediaUrl = '/webcemom/uploads/' . $newName;
        }
    }

    if ($id) {
        if ($mediaUrl) {
            $stmt = $pdo->prepare('UPDATE novedades SET title=?, body=?, media_type=?, media_url=? WHERE id=?');
            $stmt->execute([$title, $body, $mediaType, $mediaUrl, $id]);
        } else {
            $stmt = $pdo->prepare('UPDATE novedades SET title=?, body=? WHERE id=?');
            $stmt->execute([$title, $body, $id]);
        }
    } else {
        $stmt = $pdo->prepare('INSERT INTO novedades (title, body, author_id, media_type, media_url) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$title, $body, $_SESSION['user_id'], $mediaType, $mediaUrl]);
    }

    header('Location: /webcemom/manage_novedades.php');
    exit;
}

// Borrar
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare('DELETE FROM novedades WHERE id=?')->execute([$id]);
    header('Location: /webcemom/manage_novedades.php');
    exit;
}

// Listar
$posts = $pdo->query('SELECT n.*, u.email as author FROM novedades n LEFT JOIN users u ON u.id=n.author_id ORDER BY created_at DESC')->fetchAll();

$editing = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $editing = $pdo->prepare('SELECT * FROM novedades WHERE id=?');
    $editing->execute([$id]);
    $editing = $editing->fetch();
}

include __DIR__ . '/admin_header.php';
?>

<h2>Administrar Novedades</h2>

<div class="card">
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $editing['id'] ?? '' ?>">
        
        <label>Título</label>
        <input type="text" name="title" required value="<?= htmlspecialchars($editing['title'] ?? '') ?>">

        <label>Contenido</label>
        <textarea name="body" rows="6" required><?= htmlspecialchars($editing['body'] ?? '') ?></textarea>

        <label>Archivo multimedia (opcional)</label>
        <input type="file" name="media" accept="image/*,video/*">

        <?php if (!empty($editing['media_url'])): ?>
            <p><strong>Actual:</strong></p>
            <?php if ($editing['media_type'] === 'image'): ?>
                <img src="<?= htmlspecialchars($editing['media_url']) ?>" alt="Imagen actual" style="max-width:200px; border-radius:8px;">
            <?php elseif ($editing['media_type'] === 'video'): ?>
                <video src="<?= htmlspecialchars($editing['media_url']) ?>" controls style="max-width:200px; border-radius:8px;"></video>
            <?php endif; ?>
        <?php endif; ?>

        <button type="submit"><?= $editing ? 'Actualizar' : 'Publicar' ?></button>
    </form>
</div>

<div class="card admin-list">
    <h3>Lista</h3>
    <table>
        <thead><tr><th>ID</th><th>Título</th><th>Autor</th><th>Fecha</th><th>Acciones</th></tr></thead>
        <tbody>
        <?php foreach ($posts as $p): ?>
            <tr>
                <td><?= $p['id'] ?></td>
                <td><?= htmlspecialchars($p['title']) ?></td>
                <td><?= htmlspecialchars($p['author'] ?? '') ?></td>
                <td><?= htmlspecialchars($p['created_at']) ?></td>
                <td>
                    <a href="?edit=<?= $p['id'] ?>">Editar</a> |
                    <a href="?delete=<?= $p['id'] ?>" onclick="return confirm('¿Borrar esta novedad?')">Borrar</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/admin.footer.php'; ?>
