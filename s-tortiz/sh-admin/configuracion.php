<?php
require '../includes/config.php';
require '../includes/basededatos.php';
session_start();

// Protección de acceso: solo permitir admins logueados
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../index.php');
    exit;
}

$admin = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '';
$adminId = $_SESSION['admin_id'] ?? null; // Ajusta según tu sistema

$mensaje = '';
$error = '';

// Inicializar conexión a la base de datos
$db = new Database();
$con = $db->conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevoNombre = trim($_POST['nombre']);
    $nuevoCorreo = trim($_POST['correo']);
    $nuevaPass = trim($_POST['password']);
    $passAntigua = trim($_POST['password_old']);

    if ($adminId) {
        // Obtener la contraseña actual
        $sql = $con->prepare("SELECT password FROM administradores WHERE id=?");
        $sql->execute([$adminId]);
        $row = $sql->fetch(PDO::FETCH_ASSOC);
        $passActualHash = $row ? $row['password'] : '';

        // Si quiere cambiar la contraseña, debe poner la antigua correctamente
        if ($nuevaPass) {
            if (!$passAntigua || !password_verify($passAntigua, $passActualHash)) {
                $error = 'La contraseña actual es incorrecta.';
            } else {
                $sql = $con->prepare("UPDATE administradores SET nombre=?, email=?, password=? WHERE id=?");
                $sql->execute([$nuevoNombre, $nuevoCorreo, password_hash($nuevaPass, PASSWORD_DEFAULT), $adminId]);
                $mensaje = 'Datos y contraseña actualizados correctamente.';
                $_SESSION['usuario'] = $nuevoNombre;
            }
        } else {
            // Solo cambia nombre/correo
            $sql = $con->prepare("UPDATE administradores SET nombre=?, email=? WHERE id=?");
            $sql->execute([$nuevoNombre, $nuevoCorreo, $adminId]);
            $mensaje = 'Datos actualizados correctamente.';
            $_SESSION['usuario'] = $nuevoNombre;
        }
    }
}

// Obtener datos actuales
$nombre = '';
$correo = '';
if ($adminId) {
    $sql = $con->prepare("SELECT nombre, email FROM administradores WHERE id=?");
    $sql->execute([$adminId]);
    $row = $sql->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $nombre = $row['nombre'];
        $correo = $row['email'];
    }
}
require 'header.php';
?>
</div>
<div class="container my-5" style="max-width: 500px;">
    <div class="card shadow-lg border-0">
        <div class="card-body p-4">
            <h3 class="mb-4 text-center text-primary"><i class="fa-solid fa-gear me-2"></i>Configuración de Administrador</h3>
            <?php if ($mensaje): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-circle-check me-1"></i> <?php echo $mensaje; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            <?php endif; ?>
            <form method="post" autocomplete="off">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <div class="input-icon position-relative">
                        <input type="text" class="form-control ps-5" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>">
                        <i class="fa-solid fa-user position-absolute top-50 start-0 translate-middle-y ms-3 text-primary"></i>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="correo" class="form-label">Correo electrónico</label>
                    <div class="input-icon position-relative">
                        <input type="email" class="form-control ps-5" id="correo" name="correo" value="<?php echo htmlspecialchars($correo); ?>">
                        <i class="fa-solid fa-envelope position-absolute top-50 start-0 translate-middle-y ms-3 text-primary"></i>
                    </div>
                </div>
                <hr>
                <div class="mb-3">
                    <label for="password_old" class="form-label">Contraseña actual</label>
                    <div class="input-icon position-relative">
                        <input type="password" class="form-control ps-5" id="password_old" name="password_old">
                        <i class="fa-solid fa-lock position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"></i>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Nueva contraseña</label>
                    <div class="input-icon position-relative">
                        <input type="password" class="form-control ps-5" id="password" name="password">
                        <i class="fa-solid fa-key position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"></i>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-3"><i class="fa-solid fa-floppy-disk me-2"></i>Guardar cambios</button>
            </form>
            <?php if ($error): ?>
                <div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-1"></i> <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php require 'footer.php'; ?>
