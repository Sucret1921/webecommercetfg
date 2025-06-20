<?php
require '../includes/config.php';
require '../includes/basededatos.php';
require 'clases/categoria.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'Administrador') {
    header('Location: index.php');
    exit;
}

$db = new Database();
$con = $db->conectar();
$categoriaObj = new Categoria($con);
$categorias = $categoriaObj->getAll();

// Manejo de formularios (alta y edición)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    $accion = $_POST['accion'];
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    $activo = isset($_POST['activo']) ? 1 : 0;
    $categoria_id = !empty($_POST['categoria_id']) ? intval($_POST['categoria_id']) : null;

    if ($accion === 'nuevo') {
        $stmt = $con->prepare("INSERT INTO productos (nombre, descripcion, precio, activo, categoria_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $descripcion, $precio, $activo, $categoria_id]);
        $nuevo_id = $con->lastInsertId();
        // Crear carpeta para imágenes del producto
        $ruta = __DIR__ . '/../images/productos/' . $nuevo_id;
        if (!is_dir($ruta)) {
            mkdir($ruta, 0777, true);
        }
    } elseif ($accion === 'editar' && isset($_POST['id'])) {
        $id = intval($_POST['id']);
        $stmt = $con->prepare("UPDATE productos SET nombre=?, descripcion=?, precio=?, activo=?, categoria_id=? WHERE id=?");
        $stmt->execute([$nombre, $descripcion, $precio, $activo, $categoria_id, $id]);
    }
    header("Location: productos.php");
    exit;
}

// Baja de producto
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $stmt = $con->prepare("DELETE FROM productos WHERE id=?");
    $stmt->execute([$id]);
    // Eliminar carpeta de imágenes del producto
    $ruta = __DIR__ . '/../images/productos/' . $id;
    if (is_dir($ruta)) {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($ruta, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        rmdir($ruta);
    }
    header("Location: productos.php");
    exit;
}

// Toggle activo/inactivo
if (isset($_GET['toggle'])) {
    $id = intval($_GET['toggle']);
    $stmt = $con->prepare("UPDATE productos SET activo = NOT activo WHERE id=?");
    $stmt->execute([$id]);
    header("Location: productos.php");
    exit;
}

// Listado de productos
$stmt = $con->prepare("SELECT * FROM productos ORDER BY id DESC");
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

require 'header.php';
?>
</div>
<div class="container my-5 mt-4 mb-5">
    <h2 class="mb-4">Gestión de Productos</h2>
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#nuevoProductoModal">
        <i class="fas fa-plus"></i> Nuevo producto
    </button>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID <i class="fas fa-hashtag"></i></th>
                    <th>Nombre <i class="fas fa-box"></i></th>
                    <th>Descripción <i class="fas fa-align-left"></i></th>
                    <th>Precio <i class="fas fa-euro-sign"></i></th>
                    <th>Activo <i class="fas fa-toggle-on"></i></th>
                    <th>Categoría <i class="fas fa-tags"></i></th>
                    <th>Acciones <i class="fas fa-cogs"></i></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><?= htmlspecialchars($p['nombre'], ENT_QUOTES) ?></td>
                    <td><?= nl2br(htmlspecialchars($p['descripcion'], ENT_QUOTES)) ?></td>
                    <td><?= MONEDA . number_format($p['precio'], 2, '.', ',') ?></td>
                    <td>
                        <span class="badge bg-<?= $p['activo'] ? 'success' : 'danger' ?>">
                            <?= $p['activo'] ? 'Activo' : 'Inactivo' ?>
                        </span>
                    </td>
                    <td>
                        <?php
                        $cat = array_filter($categorias, function($c) use ($p) { return $c['id'] == $p['categoria_id']; });
                        $cat = $cat ? array_shift($cat) : null;
                        echo $cat ? '<span class="badge" style="background:'.$cat['color'].'"><i class="fas '.$cat['icono'].'"></i> '.htmlspecialchars($cat['nombre']).'</span>' : '<span class="text-muted">Sin categoría</span>';
                        ?>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editarProductoModal"
                            data-id="<?= $p['id'] ?>"
                            data-nombre="<?= htmlspecialchars($p['nombre'], ENT_QUOTES) ?>"
                            data-descripcion="<?= htmlspecialchars($p['descripcion'], ENT_QUOTES) ?>"
                            data-precio="<?= htmlspecialchars($p['precio'], ENT_QUOTES) ?>"
                            data-activo="<?= $p['activo'] ?>"
                            data-categoria_id="<?= $p['categoria_id'] ?>">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                        <a href="?toggle=<?= $p['id'] ?>" class="btn btn-sm btn-warning">
                            <i class="fas fa-toggle-on"></i> Cambiar estado
                        </a>
                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#eliminarModal"
                            data-id="<?= $p['id'] ?>" data-nombre="<?= htmlspecialchars($p['nombre'], ENT_QUOTES) ?>">
                            <i class="fas fa-trash-alt"></i> Eliminar
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Nuevo Producto -->
<div class="modal fade" id="nuevoProductoModal" tabindex="-1" aria-labelledby="nuevoProductoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="">
      <input type="hidden" name="accion" value="nuevo">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="nuevoProductoModalLabel"><i class="fas fa-plus"></i> Nuevo producto</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="nombreNuevo" class="form-label"><i class="fas fa-tag"></i> Nombre</label>
          <input type="text" class="form-control" id="nombreNuevo" name="nombre" required maxlength="100">
        </div>
        <div class="mb-3">
          <label for="descripcionNuevo" class="form-label"><i class="fas fa-align-left"></i> Descripción</label>
          <textarea class="form-control" id="descripcionNuevo" name="descripcion" rows="2" maxlength="255" required></textarea>
        </div>
        <div class="mb-3">
          <label for="precioNuevo" class="form-label"><i class="fas fa-euro-sign"></i> Precio</label>
          <input type="number" class="form-control" id="precioNuevo" name="precio" min="0" step="0.01" required>
        </div>
        <div class="mb-3">
          <label for="categoriaNuevo" class="form-label"><i class="fas fa-tags"></i> Categoría</label>
          <select class="form-select" id="categoriaNuevo" name="categoria_id">
            <option value="">Sin categoría</option>
            <?php foreach ($categorias as $cat): ?>
              <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nombre']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-check mb-2">
          <input class="form-check-input" type="checkbox" id="activoNuevo" name="activo" checked>
          <label class="form-check-label" for="activoNuevo">
            <i class="fas fa-check-circle text-success"></i> Activo
          </label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancelar</button>
        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Editar Producto -->
<div class="modal fade" id="editarProductoModal" tabindex="-1" aria-labelledby="editarProductoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="">
      <input type="hidden" name="accion" value="editar">
      <input type="hidden" name="id" id="editar-id">
      <div class="modal-header">
        <h5 class="modal-title" id="editarProductoModalLabel">Editar producto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="editar-nombre" class="form-label">Nombre</label>
          <input type="text" class="form-control" name="nombre" id="editar-nombre" required>
        </div>
        <div class="mb-3">
          <label for="editar-descripcion" class="form-label">Descripción</label>
          <textarea class="form-control" name="descripcion" id="editar-descripcion" rows="3" required></textarea>
        </div>
        <div class="mb-3">
          <label for="editar-precio" class="form-label">Precio</label>
          <input type="number" step="0.01" class="form-control" name="precio" id="editar-precio" required>
        </div>
        <div class="mb-3">
          <label for="editar-categoria" class="form-label">Categoría</label>
          <select class="form-select" name="categoria_id" id="editar-categoria">
            <option value="">Sin categoría</option>
            <?php foreach ($categorias as $cat): ?>
              <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nombre']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="activo" id="editar-activo">
          <label class="form-check-label" for="editar-activo">Activo</label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Guardar cambios</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Eliminar Producto -->
<div class="modal fade" id="eliminarModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Eliminar producto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>¿Seguro que quieres eliminar <strong id="nombreProductoEliminar"></strong>?</p>
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

</script>
<script src="js/productos.js"></script>
<?php require 'footer.php'; ?>
