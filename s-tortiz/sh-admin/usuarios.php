<?php
require '../includes/config.php';
require '../includes/basededatos.php';

$db = new Database();
$con = $db->conectar();

// Editar usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'editar') {
    $id        = intval($_POST['id']);
    $nombres   = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $dni       = trim($_POST['dni']);
    $email     = trim($_POST['email']);
    $telefono  = trim($_POST['telefono']);
    $estatus   = isset($_POST['estatus']) ? 1 : 0;

    $sql = $con->prepare(
        "UPDATE clientes SET nombres=?, apellidos=?, dni=?, email=?, telefono=?, estatus=? WHERE id=?"
    );
    $sql->execute([$nombres, $apellidos, $dni, $email, $telefono, $estatus, $id]);

    header("Location: usuarios.php");
    exit;
}

// Alta usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'nuevo') {
    $nombres   = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $dni       = trim($_POST['dni']);
    $email     = trim($_POST['email']);
    $telefono  = trim($_POST['telefono']);
    $estatus   = isset($_POST['estatus']) ? 1 : 0;

    $sql = $con->prepare(
        "INSERT INTO clientes (nombres, apellidos, dni, email, telefono, estatus) VALUES (?, ?, ?, ?, ?, ?)"
    );
    $sql->execute([$nombres, $apellidos, $dni, $email, $telefono, $estatus]);

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
$sql = $con->prepare(
    "SELECT id, nombres, apellidos, dni, email, telefono, estatus FROM clientes ORDER BY id DESC"
);
$sql->execute();
$usuarios = $sql->fetchAll(PDO::FETCH_ASSOC);

require 'header.php';
?>
</div>
<div class="container my-5 mt-4 mb-5">
    <h2 class="mb-4">Gestión de Usuarios</h2>
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#nuevoUsuarioModal">
      <i class="fas fa-user-plus"></i> Nuevo usuario
    </button>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID <i class="fas fa-hashtag"></i></th>
                    <th>Nombre <i class="fas fa-user"></i></th>
                    <th>DNI <i class="fas fa-id-card"></i></th>
                    <th>Email <i class="fas fa-envelope"></i></th>
                    <th>Teléfono <i class="fas fa-phone"></i></th>
                    <th>Estado <i class="fas fa-toggle-on"></i></th>
                    <th>Acciones <i class="fas fa-cogs"></i></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= htmlspecialchars($u['nombres'].' '.$u['apellidos']) ?></td>
                    <td><?= htmlspecialchars($u['dni']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= htmlspecialchars($u['telefono']) ?></td>
                    <td>
                        <span class="badge bg-<?= $u['estatus'] ? 'success' : 'danger' ?>">
                            <?= $u['estatus'] ? 'Activo' : 'Baja' ?>
                        </span>
                    </td>
                    <td>
                        <a href="?toggle=<?= $u['id'] ?>" class="btn btn-sm btn-warning">
                            <i class="fas fa-toggle-on"></i> <?= $u['estatus'] ? 'Dar de baja' : 'Dar de alta' ?>
                        </a>
                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editarUsuarioModal"
                            data-id="<?= $u['id'] ?>"
                            data-nombres="<?= htmlspecialchars($u['nombres']) ?>"
                            data-apellidos="<?= htmlspecialchars($u['apellidos']) ?>"
                            data-dni="<?= htmlspecialchars($u['dni']) ?>"
                            data-email="<?= htmlspecialchars($u['email']) ?>"
                            data-telefono="<?= htmlspecialchars($u['telefono']) ?>"
                            data-estatus="<?= $u['estatus'] ?>">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#eliminarUsuarioModal"
                            data-id="<?= $u['id'] ?>"
                            data-nombre="<?= htmlspecialchars($u['nombres'].' '.$u['apellidos']) ?>">
                            <i class="fas fa-trash-alt"></i> Eliminar
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- modales_usuarios.php -->

<!-- Modal Nuevo Usuario -->
<div class="modal fade" id="nuevoUsuarioModal" tabindex="-1" aria-labelledby="nuevoUsuarioModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="usuarios.php">
      <input type="hidden" name="accion" value="nuevo">
      <div class="modal-header">
        <h5 class="modal-title" id="nuevoUsuarioModalLabel"><i class="fas fa-user-plus"></i> Nuevo usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="nuevoNombres" class="form-label">Nombres</label>
          <input type="text" class="form-control" id="nuevoNombres" name="nombres" required>
        </div>
        <div class="mb-3">
          <label for="nuevoApellidos" class="form-label">Apellidos</label>
          <input type="text" class="form-control" id="nuevoApellidos" name="apellidos" required>
        </div>
        <div class="mb-3">
          <label for="nuevoDni" class="form-label">DNI</label>
          <input type="text" class="form-control" id="nuevoDni" name="dni" required>
        </div>
        <div class="mb-3">
          <label for="nuevoEmail" class="form-label">Email</label>
          <input type="email" class="form-control" id="nuevoEmail" name="email" required>
        </div>
        <div class="mb-3">
          <label for="nuevoTelefono" class="form-label">Teléfono</label>
          <input type="text" class="form-control" id="nuevoTelefono" name="telefono" required>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="nuevoEstatus" name="estatus" checked>
          <label class="form-check-label" for="nuevoEstatus">Activo</label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Editar Usuario -->
<div class="modal fade" id="editarUsuarioModal" tabindex="-1" aria-labelledby="editarUsuarioModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="usuarios.php">
      <input type="hidden" name="accion" value="editar">
      <input type="hidden" name="id" id="editUsuarioId">
      <div class="modal-header">
        <h5 class="modal-title" id="editarUsuarioModalLabel"><i class="fas fa-edit"></i> Editar usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="editNombres" class="form-label">Nombres</label>
          <input type="text" class="form-control" id="editNombres" name="nombres" required>
        </div>
        <div class="mb-3">
          <label for="editApellidos" class="form-label">Apellidos</label>
          <input type="text" class="form-control" id="editApellidos" name="apellidos" required>
        </div>
        <div class="mb-3">
          <label for="editDni" class="form-label">DNI</label>
          <input type="text" class="form-control" id="editDni" name="dni" required>
        </div>
        <div class="mb-3">
          <label for="editEmail" class="form-label">Email</label>
          <input type="email" class="form-control" id="editEmail" name="email" required>
        </div>
        <div class="mb-3">
          <label for="editTelefono" class="form-label">Teléfono</label>
          <input type="text" class="form-control" id="editTelefono" name="telefono" required>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="editEstatus" name="estatus">
          <label class="form-check-label" for="editEstatus">Activo</label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Guardar cambios</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Eliminar Usuario -->
<div class="modal fade" id="eliminarUsuarioModal" tabindex="-1" aria-labelledby="eliminarUsuarioModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="get" action="usuarios.php">
      <input type="hidden" name="eliminar" id="idUsuarioEliminar">
      <div class="modal-header">
        <h5 class="modal-title" id="eliminarUsuarioModalLabel"><i class="fas fa-trash-alt"></i> Eliminar usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <p>¿Seguro que quieres eliminar al usuario <strong id="nombreUsuarioEliminar"></strong>?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-danger">Eliminar</button>
      </div>
    </form>
  </div>
</div>

<script src="js/usuarios.js"></script>
<?php require 'footer.php'; ?>
