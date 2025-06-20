<?php


require 'includes/config.php';
require 'includes/basededatos.php';
require_once 'includes/categoria_public.php';

$db = new Database();
$con = $db->conectar();

// Filtros
$categoria_id = isset($_GET['categoria_id']) ? intval($_GET['categoria_id']) : '';
$nombre = isset($_GET['nombre']) ? trim($_GET['nombre']) : '';
$estado = isset($_GET['estado']) ? $_GET['estado'] : '1'; // 1=activos, 0=inactivos, ''=todos

// Obtener categorías
$categorias = CategoriaPublic::getAll($con);

// Construir consulta de productos
$where = [];
$params = [];
if ($estado !== '') {
    $where[] = 'activo = ?';
    $params[] = $estado;
}
if ($categoria_id) {
    $where[] = 'categoria_id = ?';
    $params[] = $categoria_id;
}
if ($nombre !== '') {
    $where[] = 'nombre LIKE ?';
    $params[] = "%$nombre%";
}
$where_sql = $where ? implode(' AND ', $where) : '1';
$sql = $con->prepare("SELECT id, nombre, precio FROM productos WHERE $where_sql");
$sql->execute($params);
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);


// BANNER
require 'header.html.php';

?>


<main>
  <div class="min-vh-100 d-flex flex-column">
    <div class="container py-4 flex-grow-1">
      <!-- Sección informativa animada sobre el catálogo -->
      <div class="row mb-5 align-items-center animate__animated animate__fadeInDown">
        <div class="col-md-6 mb-4 mb-md-0">
          <img src="images/imagenesGlobales/catalogo.jpg" class="img-fluid rounded-4 shadow" alt="Catálogo de productos" style="min-height:220px;object-fit:cover;">
        </div>
        <div class="col-md-6">
          <h2 class="text-primary mb-3 text-white"><i class="fas fa-store text-white"></i> Bienvenido al Catálogo</h2>
          <p class="lead text-white">Aquí puedes explorar todos nuestros productos, filtrar por categoría y buscar lo que necesitas de forma rápida y sencilla. ¡Descubre las mejores ofertas y novedades!</p>
          <ul class="list-group list-group-flush mb-3">
            <li class="list-group-item bg-transparent text-white"><i class="fas fa-filter text-white me-2"></i> Filtra por categoría y nombre</li>
            <li class="list-group-item bg-transparent text-white"><i class="fas fa-search text-white me-2"></i> Busca productos fácilmente</li>
            <li class="list-group-item bg-transparent text-white"><i class="fas fa-tags text-white me-2"></i> Descubre promociones y descuentos</li>
            <li class="list-group-item bg-transparent text-white"><i class="fas fa-cart-plus text-white me-2"></i> Añade productos al carrito con un clic</li>
          </ul>
        </div>
      </div>
      <!-- Sección de ventajas animadas -->
      <div class="row mb-5 g-4 animate__animated animate__fadeInUp">
        <div class="col-md-4">
          <div class="card h-100 shadow border-0 text-center animate__animated animate__zoomIn">
            <img src="images/imagenesGlobales/granvariedad.jpg" class="card-img-top" alt="Variedad de productos" style="height:180px;object-fit:cover;">
            <div class="card-body">
              <h5 class="card-title text-primary"><i class="fas fa-boxes"></i> Gran variedad</h5>
              <p class="card-text">Encuentra cientos de productos de tecnología, hogar y más.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card h-100 shadow border-0 text-center animate__animated animate__zoomIn" style="animation-delay:0.2s;">
            <img src="images/imagenesGlobales/comprasegura.jpg" class="card-img-top" alt="Compra segura" style="height:180px;object-fit:cover;">
            <div class="card-body">
              <h5 class="card-title text-success"><i class="fas fa-lock"></i> Compra segura</h5>
              <p class="card-text">Tus datos y pagos están protegidos con la máxima seguridad.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card h-100 shadow border-0 text-center animate__animated animate__zoomIn" style="animation-delay:0.4s;">
            <img src="images/imagenesGlobales/entrega.jpg" class="card-img-top" alt="Entrega rápida" style="height:180px;object-fit:cover;">
            <div class="card-body">
              <h5 class="card-title text-warning"><i class="fas fa-shipping-fast"></i> Entrega rápida</h5>
              <p class="card-text">Recibe tus pedidos en casa en 24-72h, sin complicaciones.</p>
            </div>
          </div>
        </div>
      </div>
      <form class="row g-3 mb-4 bg-light p-3 rounded shadow-sm" method="get">
        <div class="col-md-3">
          <label for="nombre" class="form-label fw-bold">Nombre</label>
          <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($nombre) ?>" placeholder="Buscar producto...">
        </div>
        <div class="col-md-3">
          <label for="categoria_id" class="form-label fw-bold">Categoría</label>
          <select class="form-select" id="categoria_id" name="categoria_id">
            <option value="">Todas</option>
            <?php foreach ($categorias as $cat): ?>
              <option value="<?= $cat['id'] ?>" <?= ($categoria_id == $cat['id']) ? 'selected' : '' ?>><?= htmlspecialchars($cat['nombre']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      
        <div class="col-md-3 d-flex align-items-end">
          <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter"></i> Filtrar</button>
        </div>
      </form>
      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        <?php foreach ($resultado as $row) { ?>
          <div class="col d-flex align-items-stretch">
            <div class="card h-100 shadow border-0 rounded-4 overflow-hidden position-relative">
              <?php
              $id = $row['id'];
              $imagen = "images/productos/" . $id . "/principal.jpg";
              if (!file_exists($imagen)) {
                $imagen = "images/productos/nofoto.jpg";
              }
              ?>
              <div class="bg-white d-flex justify-content-center align-items-center" style="height:240px;">
                <img src="<?php echo $imagen; ?>" class="producto-imagen" alt="<?php echo htmlspecialchars($row['nombre']); ?>">
              </div>
              <div class="card-body d-flex flex-column justify-content-end">
                <div class="mt-5 pt-2 text-center">
                  <h5 class="card-title fw-bold mb-2 text-primary product-title-custom"><?php echo htmlspecialchars($row['nombre']); ?></h5>
                  <p class="card-text fs-5 mb-3 text-black product-price-custom">Precio: <span class="fw-semibold">€<?php echo number_format($row['precio'], 2, ',', '.'); ?></span></p>
                </div>
                <div class="d-flex justify-content-center gap-2 mt-auto">
                  <a href="detalles.php?id=<?php echo $row['id']; ?>&token=<?php echo hash_hmac('md5', $row['id'], KEY_TOKEN); ?>" class="btn btn-outline-primary rounded-pill px-3">Ver detalles</a>
                  <button class="btn btn-primary rounded-pill px-3" type="button"
                    onclick="addProducto(<?php echo $row['id']; ?>, '<?php echo hash_hmac('md5', $row['id'], KEY_TOKEN); ?>')">
                    <i class="bi bi-cart-plus"></i> Añadir al carrito
                  </button>
                </div>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
    <?php include 'footer.html.php'; ?>
  </div>
</main>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="js/index.js"></script>
<link rel="stylesheet" href="css/producto-imagen.css">
<link href="css/fondopaginas.css" rel="stylesheet">
</body>
</html>