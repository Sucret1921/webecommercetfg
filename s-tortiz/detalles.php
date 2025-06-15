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



<main>

  <div class="container">
    <div class="row">
      <div class="col-md-6 order-md-1">

        <div id="carouselImages" class="carousel slide carousel-fade">
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
        <h2><?php echo $nombre; ?></h2>

        <?php if ($descuento > 0) { ?>
          <p><del><?php echo MONEDA . number_format($precio, 2, '.', ','); ?></del></p>
          <h2>
            <?php echo MONEDA . number_format($precio_desc, 2, '.', ','); ?>
            <small class="text-success"><?php echo $descuento; ?>% descuento</small>
          </h2>

        <?php } else { ?>

          <h2><?php echo MONEDA . number_format($precio, 2, '.', ','); ?></h2>

        <?php } ?>

        <p class="lead">
          <?php echo $descripcion; ?>
        </p>

        <div class="d-grid gap-3 col10 mx-auto">
          <button class="btn btn-primary" type="button"> Comprar ahora</button>
          <button class="btn btn-outline-primary" type="button"
            onclick="addProducto(<?php echo $id; ?>, '<?php echo $token_tmp; ?>')"> Agregar al carro</button>
        </div>
      </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script>

  function addProducto(id, token) {
    let url = 'clases/carrito.php'
    let formData = new FormData()
    formData.append('id', id)
    formData.append('token', token)


    fetch(url, {
      method: 'POST',
      body: formData,
      mode: 'cors'
    }).then(response => response.json())
      .then(data => {
        if (data.ok) {
          let elemento = document.getElementById("num_cart")
          elemento.innerHTML = data.numero
        }
      })
  }



</script>



</body>

</html>