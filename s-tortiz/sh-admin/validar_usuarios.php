<?php
require '../includes/config.php';
require '../includes/basededatos.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'Administrador') {
    header('Location: index.php');
    exit;
}

$db = new Database();
$con = $db->conectar();

// Validar usuario
if (isset($_GET['validar'])) {
    $id = intval($_GET['validar']);
    $sql = $con->prepare("UPDATE usuarios SET activacion = 1 WHERE id=?");
    $sql->execute([$id]);
    header('Location: validar_usuarios.php');
    exit;
}

// Listar usuarios pendientes de validación
$sql = $con->prepare("SELECT id, usuario, activacion FROM usuarios WHERE activacion = 0 ORDER BY id DESC");
$sql->execute();
$usuarios = $sql->fetchAll(PDO::FETCH_ASSOC);

require 'header.php';
?>
</div>
<div class="container my-5 mt-4 mb-5">
    <h2 class="mb-4"> usuarios endientes</h2>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= htmlspecialchars($u['usuario']) ?></td>
                    <td>
                        <a href="?validar=<?= $u['id'] ?>" class="btn btn-success btn-sm">
                            <i class="fas fa-check"></i> Validar
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if (empty($usuarios)): ?>
            <div class="alert alert-info mt-3">No hay usuarios pendientes de validación.</div>
        <?php endif; ?>
    </div>
</div>
<?php require 'footer.php'; ?>
