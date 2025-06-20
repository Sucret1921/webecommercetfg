<?php
// sh-admin/mensajes.php - Listado de mensajes de contacto
require '../includes/config.php';
require '../includes/basededatos.php';

session_start();

if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'Administrador') {
    header('Location: index.php');
    exit;
}

$db = new Database();
$con = $db->conectar();

// Eliminar mensaje si se solicita
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $con->prepare('DELETE FROM mensajes_contacto WHERE id=?')->execute([$id]);
    header('Location: mensajes.php');
    exit;
}

$mensajes = $con->query('SELECT * FROM mensajes_contacto ORDER BY fecha DESC')->fetchAll(PDO::FETCH_ASSOC);

require 'header.php';

?>

</div>
<div class="container my-5 mt-4 mb-5">
    <h2 class="mb-4"><i class="fas fa-envelope"></i> Mensajes de Contacto</h2>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th><i class="fas fa-user"></i> Nombre</th>
                    <th><i class="fas fa-at"></i> Email</th>
                    <th><i class="fas fa-comment"></i> Mensaje</th>
                    <th><i class="fas fa-clock"></i> Fecha</th>
                    <th><i class="fas fa-cogs"></i> Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($mensajes as $m): ?>
                <tr>
                    <td><?php echo htmlspecialchars($m['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($m['email']); ?></td>
                    <td><?php echo nl2br(htmlspecialchars($m['mensaje'])); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($m['fecha'])); ?></td>
                    <td>
                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#eliminarModal" data-id="<?php echo $m['id']; ?>">
                            <i class="fas fa-trash-alt"></i> Eliminar
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<!-- Modal Eliminar Mensaje -->
<div class="modal fade" id="eliminarModal" tabindex="-1" aria-labelledby="eliminarModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="eliminarModalLabel">Eliminar mensaje</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        ¿Seguro que quieres eliminar este mensaje?
      </div>
      <div class="modal-footer">
        <form method="get" action="">
          <input type="hidden" name="eliminar" id="idMensajeEliminar">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-danger">Eliminar</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php require 'footer.php'; ?>
<script src="js/mensajes.js"></script>

