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

// Aplicar descuento masivo
if (isset($_POST['descuento_masivo'])) {
    $porcentaje = intval($_POST['descuento_masivo']);
    $sql = $con->prepare("UPDATE productos SET descuento = ?");
    $sql->execute([$porcentaje]);
    $mensaje = "¡Descuento de verano aplicado a todos los productos!";
}

// Actualizar descuento individual
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_producto']) && isset($_POST['descuento'])) {
    $id = intval($_POST['id_producto']);
    $descuento = intval($_POST['descuento']);
    $sql = $con->prepare("UPDATE productos SET descuento = ? WHERE id = ?");
    $sql->execute([$descuento, $id]);
    $mensaje = "Descuento actualizado para el producto #$id";
}

// Listar productos
$sql = $con->prepare("SELECT id, nombre, descuento FROM productos ORDER BY id DESC");
$sql->execute();
$productos = $sql->fetchAll(PDO::FETCH_ASSOC);

require 'header.php';
?>
</div>
<div class="container my-5 mt-4 mb-5">
    <h2 class="mb-4"> Rebaixes <span class="badge bg-warning text-dark ms-2"></span></h2>
    <?php if (!empty($mensaje)) { ?>
        <div class="alert alert-success"> <?php echo $mensaje; ?> </div>
    <?php } ?>
    <div class="mb-3">
        <form method="post" class="d-inline">
            <button name="descuento_masivo" value="50" class="btn btn-danger"><i class="fas fa-fire"></i> Rebajar todo al 50%</button>
        </form>
        <form method="post" class="d-inline ms-2">
            <button name="descuento_masivo" value="25" class="btn btn-warning"><i class="fas fa-bolt"></i> Rebajar todo al 25%</button>
        </form>
        <form method="post" class="d-inline ms-2">
            <button name="descuento_masivo" value="10" class="btn btn-info"><i class="fas fa-tag"></i> Rebajar todo al 10%</button>
        </form>
        <form method="post" class="d-inline ms-2">
            <button name="descuento_masivo" value="5" class="btn btn-success"><i class="fas fa-leaf"></i> Rebajar todo al 5%</button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID <i class="fas fa-hashtag"></i></th>
                    <th>Nombre <i class="fas fa-box"></i></th>
                    <th>Descuento (%) <i class="fas fa-percent"></i></th>
                    <th>Acción <i class="fas fa-edit"></i></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $p) { ?>
                <tr>
                    <td><?php echo $p['id']; ?></td>
                    <td><?php echo htmlspecialchars($p['nombre']); ?></td>
                    <td>
                        <form method="post" class="d-flex align-items-center">
                            <input type="hidden" name="id_producto" value="<?php echo $p['id']; ?>">
                            <input type="number" name="descuento" value="<?php echo $p['descuento']; ?>" min="0" max="100" class="form-control form-control-sm me-2" style="width:80px;">
                            <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-save"></i> Actualizar</button>
                        </form>
                    </td>
                    <td>
                        <span class="badge bg-info text-dark"><i class="fas fa-user-tag"></i> Descuento individual</span>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?php require 'footer.php'; ?>
