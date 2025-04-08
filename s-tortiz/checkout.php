<?php

require 'includes/config.php';
require 'includes/basededatos.php';

$db = new Database();
$con = $db->conectar();

$productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;

print_r($_SESSION);

$lista_carrito = array();

if($productos != null) {
    foreach ($productos as $clave => $cantidad ){
        $sql = $con->prepare("SELECT id, nombre, precio, descuento, ? AS cantidad FROM productos WHERE id=? AND activo=1");
        $sql->execute([$cantidad, $clave]);
        $producto = $sql->fetch(PDO::FETCH_ASSOC);
        if ($producto) {
            $lista_carrito[] = $producto;
        }
    }
}


//session_destroy();

print_r($_SESSION);


// BANNER
require 'header.html.php';

?>


<main>
<div class="container my-5">
  <div class="table-responsive">
    <table class="table align-middle">
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
            $subtotal = $cantidad * $precio_desc;
            $total += $subtotal;
        ?>
        <tr>
          <td><?php echo $nombre; ?></td>
          <td class="text-success fw-semibold">
            <?php echo MONEDA . number_format($precio_desc, 2, '.', ','); ?></td>
          <td style="max-width: 100px;">
            <input type="number"
                   min="1"
                   max="10"
                   step="1"
                   onchange="actualizarCantidad(this.value, <?php echo $_id; ?>)"
                   value="<?php echo $cantidad; ?>"
                   id="cantidad_<?php echo $_id; ?>"
                   class="form-control form-control-sm text-center border">
                   
          </td>
          <td id="subtotal_" class="fw-medium"><?php echo MONEDA . number_format($subtotal, 2, '.', ','); ?></td>
          <td>
            <a href="#"
               class="btn btn-warning btn-sm text-dark fw-semibold"
               data-bs-id="<?php echo $_id; ?>"
               data-bs-toggle="modal"
               data-bs-target="#eliminaModal">
              Eliminar
            </a>
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
<div class="row">
    <div class="col-md-5 offset-md-7 d-grid gap-2">
        <button class="btn btn-primary btn-1">Realizar pago </button>
          </div>
        </div>
     </div>
</main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  
    <script>
      //
      function actualizarCantidad(cantidad, id) {
        let url ='clases/actualizar_carrito.php'
        let formData = new FormData()
        formData.append('action', 'agregar')
        formData.append('id', id)
        formData.append('cantidad', cantidad)
      

      fetch(url, {
        method: 'POST',
        body: formData
      }).then(response => response.json())
      .then(data => {
      if(data.ok) {
        let divsubtotal = document.getElementById('subtotal_' + id)
        divsubtotal.innerHTML = data.sub

        let elemento = document.getElementById("num_cart")
        elemento.innerHTML = data.numero
      }
    })
  }
    </script>


</body>
</html>