<?php


require 'includes/config.php';
require 'includes/basededatos.php';
require 'clases/clientesFunciones.php';

$db = new Database();
$con = $db->conectar();

$error = [];

// Verificar si el formulario fue enviado   

if(!empty($_POST)) {
    $nombres = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['correo']);
    $telefono = trim($_POST['telefono']);
    $dni = trim($_POST['dni']);
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['contrasena']);
    $repassword = trim($_POST['repetir_contrasena']);


    //VALIDACIONES PARA EL FORMULARIO


    if(esNull([$nombres, $apellidos, $email, $telefono, $dni, $usuario, $password, $repassword])) {
        $error[] = 'Rellena todos los campos, son obligatorios.';
    }

    if(!validarPassword($password, $repassword)) {
        $error[] = 'Las contraseñas no coinciden.';
    }

    if(usuarioExistente($usuario, $con)) {
        $error[] = "El nombre de usuario $usuario ya existe";
    }

    if(emailExistente($email, $con)) {
        $error[] = "El email $email ya existe";
    }

    if(telefonoExistente($telefono, $con)) {
        $error[] = "El teléfono $telefono ya existe";
    }

    if(!validarTelefono($telefono)) {
        $error[] = "El teléfono debe tener exactamente 9 dígitos numéricos.";
    }

    if(dniExistente($dni, $con)) {
        $error[] = "El DNI $dni ya existe";
    }

    if(!validarDNI($dni)) {
        $error[] = "El DNI no es válido.";
    }



    // FIN DE LAS VALIDACIONES

    if(count($error) == 0) {
        $id = registrarCliente([$nombres, $apellidos, $email, $telefono, $dni], $con);

        if($id > 0) {
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);
            $token = generarToken();

            if(!registrarUsuario([$id, $usuario, $pass_hash, $token], $con)) {
                $error[] = 'Error al registrar usuario.';
            }
        } else {
            $error[] = 'Error al registrar el cliente.';
        }
    }
}

// BANNER
require 'header.html.php';
?>

<!-- FORMULARIO DE ENTRADA -->

<main>
<div class="container">
    <h2 class="my-4">Datos del cliente</h2>
    
    <?php mostrarMensajeErrorValidacion($error); ?> 

    <p class="text-muted mb-3">Los campos marcados con <span class="text-danger">*</span> son obligatorios.</p>
    
    <form method="post" action="">
        <div class="row g-3">
            <div class="col-md-6">
                <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nombre" name="nombre">
            </div>
            <div class="col-md-6">
                <label for="apellidos" class="form-label">Apellidos <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="apellidos" name="apellidos">
            </div>
            <div class="col-md-6">
                <label for="correo" class="form-label">Correo electrónico <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="correo" name="correo">
            </div>
            <div class="col-md-6">
                <label for="telefono" class="form-label">Teléfono <span class="text-danger">*</span></label>
                <input type="tel" class="form-control" id="telefono" name="telefono">
            </div>
            <div class="col-md-6">
                <label for="dni" class="form-label">DNI <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="dni" name="dni">
            </div>
            <div class="col-md-6">
                <label for="usuario" class="form-label">Usuario <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="usuario" name="usuario">
            </div>
            <div class="col-md-6">
                <label for="contrasena" class="form-label">Contraseña <span class="text-danger">*</span></label>
                <input type="password" class="form-control" id="contrasena" name="contrasena">
            </div>
            <div class="col-md-6">
                <label for="repetir_contrasena" class="form-label">Repetir contraseña <span class="text-danger">*</span></label>
                <input type="password" class="form-control" id="repetir_contrasena" name="repetir_contrasena">
            </div>
        </div>
        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Registrarse</button>
        </div>
    </form>
</div>
</main>

<link rel="stylesheet" href="css/registrarestilo.css">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>