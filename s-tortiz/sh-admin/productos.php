<?php
require '../includes/config.php';
require '../includes/basededatos.php';

$db = new Database();
$con = $db->conectar();

// Alta producto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'nuevo') {
    $nombre = trim($_POST['nombre']);
    $precio = floatval($_POST['precio']);
    $activo = isset($_POST['activo']) ? 1 : 0;
    $sql = $con->prepare("INSERT INTO productos (nombre, precio, activo) VALUES (?, ?, ?)");
    $sql->execute([$nombre, $precio, $activo]);
    header("Location: productos.php");
    exit;
}

// Baja producto
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $sql = $con->prepare("DELETE FROM productos WHERE id=?");
    $sql->execute([$id]);
    header("Location: productos.php");
    exit;
}

// Cambiar estado producto
if (isset($_GET['toggle'])) {
    $id = intval($_GET['toggle']);
    $sql = $con->prepare("UPDATE productos SET activo = NOT activo WHERE id=?");
    $sql->execute([$id]);
    header("Location: productos.php");
    exit;
}

// Listar productos
$sql = $con->prepare("SELECT * FROM productos ORDER BY id DESC");
$sql->execute();
$productos = $sql->fetchAll(PDO::FETCH_ASSOC);

require 'header.php';
?>
</div>
<div class="container my-5 mt-4 mb-5">
    <h2 class="mb-4">Gestión de Productos</h2>
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#nuevoProductoModal">Nuevo producto</button>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Activo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $p) { ?>
                <tr>
                    <td><?php echo $p['id']; ?></td>
                    <td><?php echo htmlspecialchars($p['nombre']); ?></td>
                    <td><?php echo MONEDA . number_format($p['precio'], 2, '.', ','); ?></td>
                    <td>
                        <span class="badge bg-<?php echo $p['activo'] ? 'success' : 'danger'; ?>">
                            <?php echo $p['activo'] ? 'Activo' : 'Inactivo'; ?>
                        </span>
                    </td>
                    <td>
                        <a href="?toggle=<?php echo $p['id']; ?>" class="btn btn-sm btn-warning">Cambiar estado</a>
                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#eliminarModal" data-id="<?php echo $p['id']; ?>" data-nombre="<?php echo htmlspecialchars($p['nombre']); ?>">Eliminar</button>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Nuevo Producto -->
<div class="modal fade" id="nuevoProductoModal" tabindex="-1" aria-labelledby="nuevoProductoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="">
      <input type="hidden" name="accion" value="nuevo">
      <div class="modal-header">
        <h5 class="modal-title" id="nuevoProductoModalLabel">Nuevo producto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="nombre" class="form-label">Nombre</label>
          <input type="text" class="form-control" name="nombre" required>
        </div>
        <div class="mb-3">
          <label for="precio" class="form-label">Precio</label>
          <input type="number" step="0.01" class="form-control" name="precio" required>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="activo" id="activo" checked>
          <label class="form-check-label" for="activo">Activo</label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Eliminar Producto -->
<div class="modal fade" id="eliminarModal" tabindex="-1" aria-labelledby="eliminarModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="eliminarModalLabel">Eliminar producto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <p>¿Seguro que quieres eliminar el producto <span id="nombreProductoEliminar" class="fw-bold"></span>?</p>
      </div>
      <div class="modal-footer">
        <form method="get" action="">
          <input type="hidden" name="eliminar" id="idProductoEliminar">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-danger">Eliminar</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="js/productos.js"></script>
<?php require 'footer.php'; ?>

