<?php

require 'includes/config.php';
require 'includes/basededatos.php';

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirigir a login.php si no está logueado
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

$db = new Database();
$con = $db->conectar();

$productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;

$lista_carrito = array();

if ($productos != null) {
  foreach ($productos as $clave => $cantidad) {
    $sql = $con->prepare("SELECT id, nombre, precio, descuento, ? AS cantidad FROM productos WHERE id=? AND activo=1");
    $sql->execute([$cantidad, $clave]);
    $producto = $sql->fetch(PDO::FETCH_ASSOC);
    if ($producto) {
      $lista_carrito[] = $producto;
    }
  }
}

// BANNER
require 'header.html.php';

?>


<main>
  <div class="container my-5">
    <div class="mx-auto p-4 bg-white rounded-4 shadow border border-3 border-primary" style="max-width: 900px;">
      <div class="table-responsive">
        <table class="table align-middle table-bordered table-hover shadow-sm bg-white rounded">
          <thead class="border-bottom border-dark">
            <tr>
              <th class="fw-bold">Producto</th>
              <th class="fw-bold">Precio</th>
              <th class="fw-bold">Cantidad</th>
              <th class="fw-bold">Subtotal</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php if ($lista_carrito == null) {
              echo '<tr><td colspan="5" class="text-center fw-bold text-muted">Lista vacía</td></tr>';
            } else {
              $total = 0;
              foreach ($lista_carrito as $producto) {
                $_id = $producto['id'];
                $nombre = $producto['nombre'];
                $precio = $producto['precio'];
                $descuento = $producto['descuento'];
                $precio_desc = $precio - (($precio * $descuento) / 100);
                $subtotal = $productos[$_id] * $precio_desc;
                $total += $subtotal;
                ?>

                <tr>
                  <td><?php echo $nombre; ?></td>
                  <td class="text-success fw-semibold"> <?php echo MONEDA . number_format($precio_desc, 2, '.', ','); ?> </td>
                  <td style="max-width: 100px;"> 
                    <input type="number" min="1" max="10" step="1"
                      onchange="actualizarCantidad(this.value, <?php echo $_id; ?>)" 
                      value="<?php echo $productos[$_id]; ?>"
                      id="cantidad_<?php echo $_id; ?>" 
                      class="form-control form-control-sm text-center border">
                  </td>
                  <td id="subtotal_<?php echo $_id; ?>" class="fw-medium"><?php echo MONEDA . number_format($subtotal, 2, '.', ','); ?></td>
                  <td>
                    <a href="#" class="btn btn-warning btn-sm text-dark fw-semibold" data-bs-id="<?php echo $_id; ?>"
                      data-bs-toggle="modal" data-bs-target="#eliminaModal">Eliminar</a>
                  </td>
                </tr>
              <?php } ?>

              <tr>
                <td colspan="3"> </td>
                <td colspan="2">
                  <p class="h3" id="total"><?php echo MONEDA . number_format($total, 2, '.', ','); ?> </p>
                </td>
            </tbody>
          <?php } ?>
        </table>
      </div>

  <!-- Botón de pago con validación que hay productos -->
      <?php if ($lista_carrito != null) { ?>
      <div class="row">
        <div class="col-md-5 offset-md-7 d-grid gap-2">
          <a href="pago.php" class="btn btn-light btn-lg d-flex align-items-center justify-content-center gap-2 border border-primary text-primary fw-bold"><i class="fas fa-credit-card"></i> Realizar pago</a>
        </div>
      </div>
    </div>
    <?php } ?>
</main>

<!-- Modal Eliminar producto carrito -->
<div class="modal fade" id="eliminaModal" tabindex="-1" role="dialog" aria-labelledby="eliminaModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="eliminaModalLabel">Cuidado</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ¿Está seguro de eliminar el producto del carrito?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button id="btn-elimina" class="btn btn-danger" onclick="eliminar()">Eliminar</button>
      </div>
    </div>
  </div>
</div>

<link href="css/fondopaginas.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script>
  const MONEDA = '<?php echo MONEDA; ?>';
</script>
<script src="js/checkout.js"></script>

</body>

</html>