<?php
require '../includes/config.php';
require '../includes/basededatos.php';
session_start();

$admin = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '';
$adminId = $_SESSION['admin_id'] ?? null; // Ajusta según tu sistema

$mensaje = '';
$error = '';

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
    <h2 class="mb-4">Configuración de Administrador</h2>
    <?php if ($mensaje) { ?>
        <div class="alert alert-success text-center"> <?php echo $mensaje; ?> </div>
    <?php } ?>
    <?php if ($error) { ?>
        <div class="alert alert-danger text-center"> <?php echo $error; ?> </div>
    <?php } ?>
    <form method="post" autocomplete="off">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required>
        </div>
        <div class="mb-3">
            <label for="correo" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" name="correo" value="<?php echo htmlspecialchars($correo); ?>" required>
        </div>
        <div class="mb-3">
            <label for="password_old" class="form-label">Contraseña actual (obligatoria para cambiar la contraseña)</label>
            <input type="password" class="form-control" name="password_old">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Nueva contraseña (opcional)</label>
            <input type="password" class="form-control" name="password">
        </div>
        <button type="submit" class="btn btn-primary w-100">Guardar cambios</button>
    </form>
</div>
<?php require 'footer.php'; ?>
