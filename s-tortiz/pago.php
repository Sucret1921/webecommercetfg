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



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>


<!-- IMPLEMENTACIÓN PAYPAL -->
    <script
            src="https://www.paypal.com/sdk/js?client-id=<?php echo CLIENT_ID; ?>&buyer-country=FR&currency=<?php echo CURRENCY ?>&components=buttons&enable-funding=venmo,card&disable-funding=paylater"
            data-sdk-integration-source="developer-studio"></script>


        <script>
            
                    paypal.Buttons({
                        createOrder: function (data, actions) {
                            return actions.order.create({
                                purchase_units: [{
                                    amount: {
                                        value: '<?php echo number_format($total, 2, '.', ''); ?>'
                                    }
                                }]
                            });
                        },
                        onApprove: function (data, actions) {
                            let URL = 'clases/captura.php';
                            return actions.order.capture().then(function (details) {        
                                return fetch(URL, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        details: details,
                                    })
                                }).then(function (response) {
                                    //email
                                    window.location.href = "VentanaEmergenteCompletado.html";
                                    
                                }).then(function (data) {
                                    if (data.ok) {
                                        window.location.href = "pago.php?success=true";
                                    } else {
                                        alert('Error al procesar el pago');
                                    }
                                });
                            });
                        }
                    }).render('#paypal-button-container');
            
            </script>

<!-- FIN IMPLEMENTACIÓN PAYPAL -->

<script>
    function actualizarCantidad(cantidad, id) {
        let url = 'clases/actualizar_carrito.php';
        let formData = new FormData();
        formData.append('action', 'actualizar');
        formData.append('id', id);
        formData.append('cantidad', cantidad);

        fetch(url, {
            method: 'POST',
            body: formData
        }).then(response => response.json())
            .then(data => {
                if (data.ok) {
                    // Actualiza el subtotal del producto
                    let divsubtotal = document.getElementById('subtotal_' + id);
                    divsubtotal.innerHTML = data.sub;

                    // Actualiza el total del carrito
                    let total = 0;
                    document.querySelectorAll('[id^="subtotal_"]').forEach(function (subtotalElement) {
                        let subtotalText = subtotalElement.innerHTML.replace(/[^\d.-]/g, ''); // Elimina símbolos de moneda
                        total += parseFloat(subtotalText);
                    });

                    let totalElement = document.getElementById('total');
                    totalElement.innerHTML = '<?php echo MONEDA; ?>' + total.toFixed(2);

                    // Actualiza el número de productos únicos en el carrito
                    let elemento = document.getElementById("num_cart");
                    elemento.innerHTML = data.numero;
                }
            });
    }
</script>

</body>

</html>