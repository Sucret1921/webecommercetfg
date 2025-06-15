<?php
require '../includes/config.php';
require '../includes/basededatos.php';

$db = new Database();
$con = $db->conectar();

// Alta usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'nuevo') {
    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $estatus = isset($_POST['estatus']) ? 1 : 0;
    $sql = $con->prepare("INSERT INTO clientes (nombres, apellidos, email, telefono, estatus) VALUES (?, ?, ?, ?, ?)");
    $sql->execute([$nombres, $apellidos, $email, $telefono, $estatus]);
    header("Location: usuarios.php");
    exit;
}

// Eliminar usuario
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $sql = $con->prepare("DELETE FROM clientes WHERE id=?");
    $sql->execute([$id]);
    header("Location: usuarios.php");
    exit;
}

// Cambiar estado usuario
if (isset($_GET['toggle'])) {
    $id = intval($_GET['toggle']);
    $sql = $con->prepare("UPDATE clientes SET estatus = NOT estatus WHERE id=?");
    $sql->execute([$id]);
    header("Location: usuarios.php");
    exit;
}

// Listar usuarios
$sql = $con->prepare("SELECT id, nombres, apellidos, email, telefono, estatus FROM clientes ORDER BY id DESC");
$sql->execute();
$usuarios = $sql->fetchAll(PDO::FETCH_ASSOC);

require 'header.php';
?>
</div>
<div class="container my-5 mt-4 mb-5">
    <h2 class="mb-4">Gestión de Usuarios</h2>
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#nuevoUsuarioModal">Nuevo usuario</button>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u) { ?>
                <tr>
                    <td><?php echo $u['id']; ?></td>
                    <td><?php echo htmlspecialchars($u['nombres'] . ' ' . $u['apellidos']); ?></td>
                    <td><?php echo htmlspecialchars($u['email']); ?></td>
                    <td><?php echo htmlspecialchars($u['telefono']); ?></td>
                    <td>
                        <span class="badge bg-<?php echo $u['estatus'] ? 'success' : 'danger'; ?>">
                            <?php echo $u['estatus'] ? 'Activo' : 'Baja'; ?>
                        </span>
                    </td>
                    <td>
                        <a href="?toggle=<?php echo $u['id']; ?>" class="btn btn-sm btn-warning">
                            <?php echo $u['estatus'] ? 'Dar de baja' : 'Dar de alta'; ?>
                        </a>
                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#eliminarUsuarioModal" data-id="<?php echo $u['id']; ?>" data-nombre="<?php echo htmlspecialchars($u['nombres'] . ' ' . $u['apellidos']); ?>">Eliminar</button>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Nuevo Usuario -->
<div class="modal fade" id="nuevoUsuarioModal" tabindex="-1" aria-labelledby="nuevoUsuarioModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="">
      <input type="hidden" name="accion" value="nuevo">
      <div class="modal-header">
        <h5 class="modal-title" id="nuevoUsuarioModalLabel">Nuevo usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="nombres" class="form-label">Nombres</label>
          <input type="text" class="form-control" name="nombres" required>
        </div>
        <div class="mb-3">
          <label for="apellidos" class="form-label">Apellidos</label>
          <input type="text" class="form-control" name="apellidos" required>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" class="form-control" name="email" required>
        </div>
        <div class="mb-3">
          <label for="telefono" class="form-label">Teléfono</label>
          <input type="text" class="form-control" name="telefono" required>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="estatus" id="estatus" checked>
          <label class="form-check-label" for="estatus">Activo</label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Eliminar Usuario -->
<div class="modal fade" id="eliminarUsuarioModal" tabindex="-1" aria-labelledby="eliminarUsuarioModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="eliminarUsuarioModalLabel">Eliminar usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <p>¿Seguro que quieres eliminar al usuario <span id="nombreUsuarioEliminar" class="fw-bold"></span>?</p>
      </div>
      <div class="modal-footer">
        <form method="get" action="">
          <input type="hidden" name="eliminar" id="idUsuarioEliminar">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-danger">Eliminar</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="js/usuarios.js"></script>
<?php require 'footer.php'; ?>


