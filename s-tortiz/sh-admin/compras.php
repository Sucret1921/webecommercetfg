<?php
require '../includes/config.php';
require '../includes/basededatos.php';

session_start();
if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'Administrador') {
    header('Location: index.php');
    exit;
}

$db = new Database();
$con = $db->conectar();

// Eliminar compra
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $sql = $con->prepare("DELETE FROM compra WHERE id=?");
    $sql->execute([$id]);
    header("Location: compras.php");
    exit;
}

// Cambiar estado compra
if (isset($_GET['toggle']) && isset($_GET['status'])) {
    $id = intval($_GET['toggle']);
    $nuevoEstado = $_GET['status'];
    $sql = $con->prepare("UPDATE compra SET status=? WHERE id=?");
    $sql->execute([$nuevoEstado, $id]);
    header("Location: compras.php");
    exit;
}

// Listar compras
$sql = $con->prepare("SELECT id, id_transaccion, fecha, status, email, total FROM compra ORDER BY fecha DESC");
$sql->execute();
$compras = $sql->fetchAll(PDO::FETCH_ASSOC);

require 'header.php';
?>
</div>
<div class="container my-5 mt-4 mb-5">
    <h2 class="mb-4">Transacciones</h2>
    <div class="table-responsive">
        <table class="table table-hover align-middle" id="comprasTable">
            <thead class="table-dark">
                <tr>
                    <th>ID <i class="fas fa-hashtag"></i></th>
                    <th>Transacción <i class="fas fa-receipt"></i></th>
                    <th>Fecha <i class="fas fa-calendar-alt"></i></th>
                    <th>Email <i class="fas fa-envelope"></i></th>
                    <th>Total <i class="fas fa-euro-sign"></i></th>
                    <th>Estado <i class="fas fa-info-circle"></i></th>
                    <th>Acciones <i class="fas fa-cogs"></i></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($compras as $c) { ?>
                <tr>
                    <td><?php echo $c['id']; ?></td>
                    <td><?php echo htmlspecialchars($c['id_transaccion']); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($c['fecha'])); ?></td>
                    <td><?php echo htmlspecialchars($c['email']); ?></td>
                    <td><?php echo MONEDA . number_format($c['total'], 2, '.', ','); ?></td>
                    <td>
                        <span class="badge bg-<?php echo $c['status'] == 'COMPLETED' ? 'success' : ($c['status'] == 'PENDING' ? 'warning' : 'danger'); ?>">
                            <?php echo htmlspecialchars($c['status']); ?>
                        </span>
                    </td>
                    <td>
                        <a href="?toggle=<?php echo $c['id']; ?>&status=COMPLETED" class="btn btn-sm btn-success">Completar</a>
                        <a href="?toggle=<?php echo $c['id']; ?>&status=PENDING" class="btn btn-sm btn-warning">Pendiente</a>
                        <a href="?toggle=<?php echo $c['id']; ?>&status=CANCELLED" class="btn btn-sm btn-danger">Cancelar</a>
                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#eliminarModal" data-id="<?php echo $c['id']; ?>" data-nombre="
                        <?php echo htmlspecialchars($c['id_transaccion']); ?>"><i class="fas fa-trash-alt"></i> Eliminar</button>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Eliminar Compra -->
<div class="modal fade" id="eliminarCompraModal" tabindex="-1" aria-labelledby="eliminarCompraModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="eliminarCompraModalLabel">Eliminar compra</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <p>¿Seguro que quieres eliminar la compra <span id="transaccionCompraEliminar" class="fw-bold"></span>?</p>
      </div>
      <div class="modal-footer">
        <form method="get" action="">
          <input type="hidden" name="eliminar" id="idCompraEliminar">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-danger">Eliminar</button>
        </form>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2" crossorigin="anonymous"></script>
<script src="js/compras.js"></script>
<?php require 'footer.php'; ?>
