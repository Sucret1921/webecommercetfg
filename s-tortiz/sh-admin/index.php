<?php



require '../includes/basededatos.php';
require 'clases/adminFunciones.php';

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si ya está logeado como administrador, redirigir a inicio.php
if (isset($_SESSION['id_usuario']) && isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'Administrador') {
    header('Location: inicio.php');
    exit;
}

$db = new Database();
$con = $db->conectar();

/*
$password = password_hash('admin123', PASSWORD_DEFAULT);
$sql = "INSERT INTO admin (usuario, password, nombre,email, activo, fecha_alta) VALUES ('admin', '$password', 'Administrador', 'hector19@gmail.com', 1, NOW())";
$con->query($sql);
*/

$error = [];

// Verificar si el formulario fue enviado   

if(!empty($_POST)) {
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);

    //VALIDACIONES PARA EL FORMULARIO
    if(esNull([$usuario, $password])) {
        $error[] = 'Rellena todos los campos, son obligatorios.';
    }

    // Si no hay errores, intento loguear
    if(count($error) == 0) {
        $msg = login($usuario, $password, $con);
        if($msg !== true) {
            $error[] = $msg;
        }
    }
}

// Protección: solo usuarios admin pueden acceder a cualquier página de /sh-admin
// Permitir acceso solo a la pantalla de login si no está logueado
$pagina_actual = basename($_SERVER['PHP_SELF']);
if ($pagina_actual !== 'index.php' && (!isset($_SESSION['id_usuario']) || !isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'Administrador')) {
    header('Location: index.php');
    exit;
}
?>

<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- FORMULARIO DE ENTRADA -->

<main>
<div class="form-login d-flex align-items-center justify-content-center min-vh-100">
    <div class="login-box">
        <h2 class="mb-4 text-center text-primary"><i class="fa-solid fa-user-shield me-2"></i> Acceso Administrador</h2>
        <?php if (!empty($error)): ?>
          <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?php foreach ($error as $err): ?>
              <div><?php echo htmlspecialchars($err); ?></div>
            <?php endforeach; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
          </div>
        <?php endif; ?>
        <form method="post" action="" autocomplete="off">
            <input type="hidden" name="proceso" value="login">
            <div class="mb-3 input-icon">
                <label for="usuario" class="form-label">Usuario</label>
                <input type="text" class="form-control" id="usuario" name="usuario" autocomplete="username">
               
            </div>
            <div class="mb-3 input-icon">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" autocomplete="current-password">

            </div>
            
            <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-arrow-right-to-bracket me-2"></i> Entrar</button>
        </form>
        <div class="text-center mt-3">
            <a href="../index.php" class="text-secondary"><i class="fa-solid fa-arrow-left me-1"></i> Volver a la tienda principal</a>
        </div>
    </div>
</div>
</main>
<link rel="stylesheet" href="css/admin-login.css">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>