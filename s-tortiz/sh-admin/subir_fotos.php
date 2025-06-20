<?php
// subir_fotos.php - Gestión de imágenes para productos
// Debe llamarse con ?id=ID_PRODUCTO

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'Administrador') {
    header('Location: index.php');
    exit;
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_producto = intval($_GET['id']);
    $carpeta = __DIR__ . "/../images/productos/{$id_producto}";
    if (!is_dir($carpeta)) {
        mkdir($carpeta, 0777, true);
    }

    $mensaje = '';
    // SUBIDA DE IMAGEN PRINCIPAL
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tipo_subida']) && $_POST['tipo_subida'] === 'principal' && isset($_FILES['imagen_principal'])) {
        $extension = strtolower(pathinfo($_FILES['imagen_principal']['name'], PATHINFO_EXTENSION));
        $tmp = $_FILES['imagen_principal']['tmp_name'];
        if ($extension === 'jpg' || $extension === 'jpeg') {
            // Elimina principal anterior si existe
            foreach (glob($carpeta . '/principal.*') as $old) { unlink($old); }
            $destino_jpg = $carpeta . "/principal.jpg";
            if (move_uploaded_file($tmp, $destino_jpg)) {
                $mensaje = '<div class="alert alert-success"><i class="fas fa-check-circle me-1"></i> Imagen principal subida/cambiada.</div>';
            } else {
                $mensaje = '<div class="alert alert-danger"><i class="fas fa-times-circle me-1"></i> Error al subir la imagen principal.</div>';
            }
        } else {
            $mensaje = '<div class="alert alert-warning"><i class="fas fa-exclamation-triangle me-1"></i> Formato inválido: la imagen principal debe ser JPG o JPEG.</div>';
        }
    }
    // SUBIDA DE IMAGENES CARRUSEL
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tipo_subida']) && $_POST['tipo_subida'] === 'carrusel' && isset($_FILES['imagen_carrusel'])) {
        $nombre_archivo = 'foto_' . uniqid();
        $extension = strtolower(pathinfo($_FILES['imagen_carrusel']['name'], PATHINFO_EXTENSION));
        $destino = $carpeta . "/{$nombre_archivo}.{$extension}";
        if (move_uploaded_file($_FILES['imagen_carrusel']['tmp_name'], $destino)) {
            $mensaje = 'Imagen para carrusel subida.';
        } else {
            $mensaje = 'Error al subir la imagen del carrusel.';
        }
    }

    // Eliminar imagen si se solicita (por GET, igual que en compras)
    if (isset($_GET['eliminar_img']) && isset($_GET['pid'])) {
        $pid = basename($_GET['pid']);
        $img_a_borrar = __DIR__ . '/../images/productos/' . $pid . '/' . basename($_GET['eliminar_img']);
        if (file_exists($img_a_borrar)) {
            unlink($img_a_borrar);
            $redir = isset($_GET['id']) ? 'subir_fotos.php?id=' . urlencode($_GET['id']) : 'subir_fotos.php';
            header('Location: ' . $redir);
            exit;
        }
    }

    $imagenes = array_values(array_filter(scandir($carpeta), function($f) {
        return !in_array($f, ['.', '..']);
    }));
    $img_principal = null;
    $imagenes_carrusel = [];
    foreach ($imagenes as $img) {
        if (preg_match('/^principal\./', $img)) {
            $img_principal = $img;
        } else {
            $imagenes_carrusel[] = $img;
        }
    }
} else {
    $productos_dir = __DIR__ . '/../images/productos';
    $productos = array_filter(scandir($productos_dir), function($f) use ($productos_dir) {
        return is_dir($productos_dir . '/' . $f) && !in_array($f, ['.', '..']);
    });
}

require_once 'header.php';
require_once '../includes/basededatos.php';
$db = new Database();
$con = $db->conectar();
?>
</div>
<main class="container mt-4">
<?php if (isset($_GET['id']) && is_numeric($_GET['id'])): ?>
    <h2> Gestión de Fotos al Producto #<?php echo $id_producto; ?></h2>
    <div class="alert alert-warning d-flex align-items-center" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <div>
            <strong>Formatos permitidos para la imagen principal:</strong> <span class="text-danger">.jpg, .jpeg</span><br>
            Si subes otro formato, no se aceptará y se mostrará un error.
        </div>
    </div>
    <div class="alert alert-info d-flex align-items-center" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        <div>
            No es necesario que la imagen se llame <strong>principal</strong> al subirla, el sistema la renombrará automáticamente.<br>
            Si experimentas algún fallo, revisa que la imagen principal esté en formato JPG/JPEG y que no haya archivos antiguos con otro nombre en la carpeta del producto.
        </div>
    </div>
    <?php if ($mensaje): ?>
        <?php echo $mensaje; ?>
    <?php endif; ?>
    <div class="row mb-4">
        <div class="col-md-6">
            <h4><i class="fas fa-star"></i> Imagen principal</h4>
            <?php if ($img_principal): ?>
                <img src="../images/productos/<?php echo $id_producto . '/' . $img_principal; ?>" class="img-thumbnail mb-2" style="max-width:180px;max-height:180px;">
            <?php else: ?>
                <div class="text-muted">No hay imagen principal</div>
            <?php endif; ?>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="tipo_subida" value="principal">
                <input type="file" name="imagen_principal" accept="image/*" required>
                <button type="submit" class="btn btn-primary btn-sm mt-2"><i class="fas fa-upload"></i> Subir/Cambiar principal</button>
            </form>
        </div>
        <div class="col-md-6">
            <h4><i class="fas fa-images"></i> Imágenes para carrusel</h4>
            <form method="post" enctype="multipart/form-data" class="mb-2">
                <input type="hidden" name="tipo_subida" value="carrusel">
                <input type="file" name="imagen_carrusel" accept="image/*" required>
                <button type="submit" class="btn btn-primary btn-sm mt-2"><i class="fas fa-plus"></i> Añadir al carrusel</button>
            </form>
            <div style="display:flex;gap:10px;flex-wrap:wrap;">
                <?php foreach ($imagenes_carrusel as $img): ?>
                    <div style="text-align:center;">
                        <img src="../images/productos/<?php echo $id_producto . '/' . $img; ?>" class="img-thumbnail" style="max-width:90px;max-height:90px;"><br>
                        <span style="font-size:12px;">Carrusel</span><br>
                        <a href="#" class="btn btn-danger btn-sm mt-1" data-bs-toggle="modal" data-bs-target="#eliminarImgModal" data-img="<?php echo htmlspecialchars($img); ?>" data-pid="<?php echo htmlspecialchars($id_producto); ?>"><i class="fas fa-trash-alt"></i> Eliminar</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <hr>
    <a href="subir_fotos.php" class="btn btn-secondary mt-3"><i class="fas fa-list"></i> Ver todas las imágenes de todos los productos</a>
<?php else: ?>
    <h2>Gestión de Fotos de Todos los Productos</h2>
    <div class="table-responsive">
    <table class="table table-striped align-middle" id="tablaProductosFotos">
        <thead class="table-dark">
            <tr>
                <th><i class="fas fa-hashtag"></i> ID Producto</th>
                <th><i class="fas fa-star"></i> Imagen principal</th>
                <th><i class="fas fa-images"></i> Imágenes carrusel</th>
                <th><i class="fas fa-cogs"></i> Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($productos as $pid): 
            $carpeta = $productos_dir . '/' . $pid;
            $imagenes = array_values(array_filter(scandir($carpeta), function($f) {
                return !in_array($f, ['.', '..']);
            }));
            $img_principal = null;
            $imagenes_carrusel = [];
            foreach ($imagenes as $img) {
                if (preg_match('/^principal\./', $img)) {
                    $img_principal = $img;
                } else {
                    $imagenes_carrusel[] = $img;
                }
            }
            // Obtener nombre del producto
            $nombre_prod = '';
            $stmt = $con->prepare("SELECT nombre FROM productos WHERE id = ? LIMIT 1");
            $stmt->execute([$pid]);
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $nombre_prod = $row['nombre'];
            }
        ?>
            <tr>
                <td><?php echo htmlspecialchars($pid); ?> <span class="text-muted small">- <?php echo htmlspecialchars($nombre_prod); ?></span></td>
                <td>
                    <?php if ($img_principal): ?>
                        <img src="../images/productos/<?php echo $pid . '/' . $img_principal; ?>" class="img-thumbnail" style="max-width:60px;max-height:60px;">
                    <?php else: ?>
                        <span class="text-muted"><i class="fas fa-image"></i> Sin principal</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if (count($imagenes_carrusel)): ?>
                        <?php foreach ($imagenes_carrusel as $img): ?>
                            <img src="../images/productos/<?php echo $pid . '/' . $img; ?>" class="img-thumbnail" style="max-width:40px;max-height:40px;margin-right:3px;">
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span class="text-muted"><i class="fas fa-images"></i> Sin carrusel</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="subir_fotos.php?id=<?php echo $pid; ?>" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Añadir fotos</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2" crossorigin="anonymous"></script>
<?php endif; ?>

<!-- Modal Bootstrap para eliminar imagen (adaptado para todos los productos) -->
<div class="modal fade" id="eliminarImgModal" tabindex="-1" aria-labelledby="eliminarImgModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="eliminarImgModalLabel">Eliminar imagen</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        ¿Seguro que quieres eliminar esta imagen?
      </div>
      <div class="modal-footer">
        <a href="#" class="btn btn-danger" id="confirmDeleteBtn">Eliminar</a>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>
</main>
<script src="js/subir_fotos.js"></script>
<?php require_once 'footer.php'; ?>

