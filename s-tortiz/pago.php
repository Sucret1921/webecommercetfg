<?php

require 'includes/config.php';
require 'includes/basededatos.php';

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
            $lista_carrito[] = $producto; // Asegúrate de agregar cada producto al array
        }
    }
} else {
    header("Location: index.php");
    exit;
}

// BANNER
require 'header.html.php';

?>


<main>
    <div class="container my-5">
        <div class="row">
            <div class="col-6">
                <h4>Detalles de pago</h4>
                <div id="paypal-button-container"></div>
            </div>
            <div class="col-6">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="border-bottom border-dark">
                            <tr>
                                <th class="fw-bold">Producto</th>
                                <th class="fw-bold">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($lista_carrito == null) {
                                echo '<tr><td colspan="2" class="text-center fw-bold text-muted">Lista vacía</td></tr>';
                            } else {
                                $total = 0;
                                foreach ($lista_carrito as $producto) {
                                    $_id = $producto['id'];
                                    $nombre = $producto['nombre'];
                                    $precio = $producto['precio'];
                                    $descuento = $producto['descuento'];
                                    $precio_desc = $precio - (($precio * $descuento) / 100);
                                    $subtotal = $producto['cantidad'] * $precio_desc;
                                    $total += $subtotal;
                                    ?>

                                    <tr>
                                        <td><?php echo $nombre; ?></td>
                                        <td id="subtotal_<?php echo $_id; ?>" class="fw-medium">
                                            <?php echo MONEDA . number_format($subtotal, 2, '.', ','); ?>
                                        </td>
                                    </tr>
                                <?php } ?>

                                <tr>
                                    <td class="fw-bold">Total</td>
                                    <td class="fw-bold">
                                        <p class="h3" id="total"><?php echo MONEDA . number_format($total, 2, '.', ','); ?></p>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
  const PAGO_TOTAL = <?php echo isset($total) ? json_encode(number_format($total, 2, '.', '')) : '0'; ?>;
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>

<!-- IMPLEMENTACIÓN PAYPAL -->
<script
        src="https://www.paypal.com/sdk/js?client-id=<?php echo CLIENT_ID; ?>&buyer-country=FR&currency=<?php echo CURRENCY ?>&components=buttons&enable-funding=venmo,card&disable-funding=paylater"
        data-sdk-integration-source="developer-studio"></script>
<script src="js/pago.js"></script>
</body>

</html>