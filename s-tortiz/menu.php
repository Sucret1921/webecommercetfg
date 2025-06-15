<?php


require 'includes/config.php';
require 'includes/basededatos.php';

$db = new Database();
$con = $db->conectar();

$sql = $con->prepare("SELECT id, nombre, precio FROM productos WHERE activo=1");
$sql->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

//session_destroy();
print_r($_SESSION);
// BANNER
require 'header.html.php';

?>


<main>

  <div class="container">
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
      <?php foreach ($resultado as $row) { ?>
        <div class="col">
          <div class="card shadow-sm" data-id="<?php echo $row['id']; ?>" data-token="<?php echo hash_hmac('md5', $row['id'], KEY_TOKEN); ?>">
            <?php

            $id = $row['id'];
            $imagen = "images/productos/" . $id . "/principal.jpg";

            if (!file_exists($imagen)) {
              $imagen = "images/productos/nofoto.jpg";
            }
            ?>
            <img src="<?php echo $imagen; ?>" class="card-img-top">
            <div class="card-body">
              <h5 class="card-title"><?php echo $row['nombre']; ?></h5>
              <p class="card-text"> $ <?php echo number_format($row['precio'], 2, '.', ','); ?> </p>
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                  <a href="detalles.php?id=<?php echo $row['id']; ?>&token=<?php echo hash_hmac('md5', $row['id'], KEY_TOKEN); ?>"
                    class="btn btn-primary">Detalles</a>
                </div>
                <button class="btn btn-outline-success" type="button"
                  onclick="addProducto(<?php echo $row['id']; ?>, '<?php echo hash_hmac('md5', $row['id'], KEY_TOKEN); ?>')">
                  Agregar al carro</button>

                    
              </div>
            </div>
          </div>
        </div>
      <?php } ?>
</main>


</body>

</html>