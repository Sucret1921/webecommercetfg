<?php


require 'includes/config.php';
require 'includes/basededatos.php';
require 'clases/clientesFunciones.php';

$db = new Database();
$con = $db->conectar();

print_r($_SESSION); 


// Obtener el id_cliente de la sesión de forma segura
$idCliente = isset($_SESSION['user_cliente']) ? $_SESSION['user_cliente'] : null;

$compras = [];

if ($idCliente) {
    $sql = $con->prepare("SELECT id_transaccion, fecha, status, total FROM compra WHERE id_cliente = ? ORDER BY fecha DESC");
    $sql->execute([$idCliente]);
    
    $compras = $sql->fetchAll(PDO::FETCH_ASSOC);
}

// BANNER
require 'header.html.php';

?>

<main>
<div class="container my-5">
    <h2 class="mb-4">Mis Compras</h2>
    <?php if (empty($compras)) { ?>
        <div class="alert alert-info text-center" role="alert">
            No tienes compras registradas.
        </div>
    <?php } else { ?>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Transacción</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($compras as $compra) { ?>
                <tr>
                    <td>
                        <span class="text-monospace"><?php echo htmlspecialchars($compra['id_transaccion']); ?></span>
                    </td>
                    <td>
                        <?php echo date('d/m/Y H:i', strtotime($compra['fecha'])); ?>
                    </td>
                    <td>
                        <?php
                        $status = $compra['status'];
                        $badge = 'secondary';
                        if ($status == 'COMPLETED') $badge = 'success';
                        elseif ($status == 'PENDING') $badge = 'warning';
                        elseif ($status == 'CANCELLED') $badge = 'danger';
                        ?>
                        <span class="badge bg-<?php echo $badge; ?>">
                            <?php echo ucfirst(strtolower($status)); ?>
                        </span>
                    </td>
                    <td>
                        <span class="fw-bold text-success"><?php echo MONEDA . number_format($compra['total'], 2, '.', ','); ?></span>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <?php } ?>
</div>
</main>

<link rel="stylesheet" href="css/registrarestilo.css">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>