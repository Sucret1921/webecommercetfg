<?php



require '../includes/basededatos.php';
require 'clases/adminFunciones.php';


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


?>

<!-- FORMULARIO DE ENTRADA -->

<main>
<div class="form-login d-flex align-items-center justify-content-center min-vh-100">
    <div class="login-box">
        <h2 class="mb-4 text-center">Iniciar como administrador</h2>

        <?php mostrarMensajeErrorValidacion($error); ?>

        <form method="post" action="" autocomplete="off">
            
        <input type="hidden" name="proceso" value="<?php echo $proceso; ?>">

            <div class="mb-3">
                <label for="usuario" class="form-label" name="usuario">Usuario</label>
                <input type="text" class="form-control" id="usuario" name="usuario" autocomplete="username">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" autocomplete="current-password">
            </div>
            <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>
        <div class="text-center mt-2">
            <a href="recuperarPass.php">¿Olvidaste tu contraseña?</a>
        </div>
    </div>
</div>
</main>
<link rel="stylesheet" href="css/admin-login.css">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>