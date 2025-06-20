<?php
require 'includes/config.php';
require 'includes/basededatos.php';
$db = new Database();
$con = $db->conectar();

$id = isset($_GET['id']) ? $_GET['id'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';


// TRATAR ERROR DEL TOKEN EN CASO DE BORRARLO
if ($id == '' || $token == '') {
  echo 'Error al procesar la petición';
  exit;
} else {

  $token_tmp = hash_hmac('md5', $id, KEY_TOKEN);
  // CAMBIA EL TOKEN POR CADA ID DE LAS IMAGENES
  if ($token == $token_tmp) {
  } else {
    echo 'Error al procesar la petición';
    exit;
  }

}

// Verificar si el producto existe y está activo
$sql = $con->prepare("SELECT COUNT(id) FROM productos WHERE id=? AND activo=1");
$sql->execute([$id]);
if ($sql->fetchColumn() > 0) {

  // Obtener los datos del producto
  $sql = $con->prepare("SELECT nombre, descripcion, precio, descuento FROM productos WHERE id=? AND activo=1 LIMIT 1");
  $sql->execute([$id]);
  $row = $sql->fetch(PDO::FETCH_ASSOC);

  // Asignar parámetros
  $nombre = $row['nombre'];
  $descripcion = $row['descripcion'];
  $precio = $row['precio'];
  $descuento = $row['descuento'];
  $precio_desc = $precio - (($precio * $descuento) / 100);

  // Ruta de imágenes
  $dir_images = 'images/productos/' . $id . '/';
  $rutaImg = $dir_images . 'principal.jpg';

  // Comprobar si existe la imagen principal
  if (!file_exists($rutaImg)) {
    $rutaImg = 'images/nofoto.jpg';
  }

  // Cargar imágenes adicionales
  $imagenes = array();
  if (file_exists($dir_images)) {
    $dir = dir($dir_images);

    while (($archivo = $dir->read()) !== false) {
      if ($archivo !== 'principal.jpg' && (strpos($archivo, '.jpg') || strpos($archivo, '.jpeg'))) {
        $imagenes[] = $dir_images . $archivo;
      }
    }
    $dir->close();

  } else {
    echo 'Error al procesar la petición';
    exit;
  }
}

// BANNER
require 'header.html.php';

?>


<link rel="stylesheet" href="css/detalles.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />


<main>
  <div class="container">
    <div class="row">
      <div class="col-md-6 order-md-1">
        <div id="carouselImages" class="carousel slide carousel-fade" style="background:rgba(255,255,255,0.95);border-radius:1.2rem;box-shadow:0 4px 24px #0d6efd22;border:4px solid #fff;padding:1.2rem;">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="<?php echo $rutaImg; ?>" class="d-block w-100">
            </div>
            <?php foreach ($imagenes as $img) { ?>
              <div class="carousel-item">
                <img src="<?php echo $img; ?>" class="d-block w-100">
              </div>
            <?php } ?>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#carouselImages" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#carouselImages" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>
        </div>
      </div>
      <div class="col-md-6 order-md-2">
        <div class="detalle-destacado">
          <h2><i class="fa-solid fa-box-open me-2"></i><?php echo $nombre; ?></h2>
          <?php if ($descuento > 0) { ?>
            <p><del><?php echo MONEDA . number_format($precio, 2, '.', ','); ?></del></p>
            <div class="precio-final">
              <?php echo MONEDA . number_format($precio_desc, 2, '.', ','); ?>
              <span class="descuento"><i class="fa-solid fa-bolt"></i> <?php echo $descuento; ?>% dto</span>
            </div>
          <?php } else { ?>
            <div class="precio-final"><?php echo MONEDA . number_format($precio, 2, '.', ','); ?></div>
          <?php } ?>
          <p class="lead mt-3 mb-2">
            <?php echo $descripcion; ?>
          </p>
          <div class="d-grid gap-3 col10 mx-auto">
            <button class="btn btn-outline-primary" type="button"
              onclick="addProducto(<?php echo $id; ?>, '<?php echo $token_tmp; ?>')"><i class="fa-solid fa-cart-plus me-1"></i> Agregar al carro</button>
          </div>
          <hr>
          <ul class="list-unstyled mb-0">
            <li class="mb-2"><i class="fa-solid fa-cube me-2 text-primary"></i>Referencia: <strong>#<?php echo $id; ?></strong></li>
            <li class="mb-2"><i class="fa-solid fa-boxes-stacked me-2 text-primary"></i>Stock: <strong>Disponible</strong></li>
            <li class="mb-2"><i class="fa-solid fa-calendar-days me-2 text-primary"></i>Última actualización: <strong><?php echo date('d/m/Y'); ?></strong></li>
            <li class="mb-2"><i class="fa-solid fa-truck-fast me-2 text-primary"></i>Entrega estimada: <strong>24/48h</strong></li>
            <li class="mb-2"><i class="fa-solid fa-shield-halved me-2 text-primary"></i>Pago seguro y protegido</li>
            <li class="mb-2"><i class="fa-solid fa-arrows-rotate me-2 text-primary"></i>Devolución fácil 14 días</li>
            <li class="mb-2"><i class="fa-solid fa-star me-2 text-primary"></i>Garantía de satisfacción</li>
          </ul>
        </div>
        <div class="detalle-beneficios">
          <div class="detalle-beneficio"><i class="fa-solid fa-leaf"></i> Producto eco-friendly</div>
          <div class="detalle-beneficio"><i class="fa-solid fa-award"></i> Calidad certificada</div>
          <div class="detalle-beneficio"><i class="fa-solid fa-gift"></i> Ideal para regalo</div>
          <div class="detalle-beneficio"><i class="fa-solid fa-hand-holding-heart"></i> Soporte postventa</div>
        </div>
      </div>
    </div>
  </div>
</main>
 <?php include 'footer.html.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script src="js/detalles.js"></script>



</body>

</html>