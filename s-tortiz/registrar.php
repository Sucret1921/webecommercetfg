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


?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registro - Tenda Online</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" crossorigin="anonymous">
  <link rel="stylesheet" href="css/registrarestilo.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

<main>
  <div class="form-register d-flex align-items-center justify-content-center min-vh-100">
    <div class="register-box">
      <h2 class="mb-4 text-center"><i class="fa-solid fa-user-plus me-2"></i>Crear cuenta</h2>
      <?php if (!empty($error)): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
          <?php foreach ($error as $err): ?>
            <div><i class="fa-solid fa-triangle-exclamation me-1"></i> <?php echo htmlspecialchars($err); ?></div>
          <?php endforeach; ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
      <?php endif; ?>
      <p class="text-muted mb-3">Los campos marcados con <span class="text-danger">*</span> son obligatorios.</p>
      <form method="post" action="">
        <div class="row g-3">
          <div class="col-md-6">
            <label for="nombre" class="form-label"><i class="fa-solid fa-user me-1"></i>Nombre <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
          </div>
          <div class="col-md-6">
            <label for="apellidos" class="form-label"><i class="fa-solid fa-user-tag me-1"></i>Apellidos <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="apellidos" name="apellidos" required>
          </div>
          <div class="col-md-6">
            <label for="correo" class="form-label"><i class="fa-solid fa-envelope me-1"></i>Correo electrónico <span class="text-danger">*</span></label>
            <input type="email" class="form-control" id="correo" name="correo" required>
          </div>
          <div class="col-md-6">
            <label for="telefono" class="form-label"><i class="fa-solid fa-phone me-1"></i>Teléfono <span class="text-danger">*</span></label>
            <input type="tel" class="form-control" id="telefono" name="telefono" required>
          </div>
          <div class="col-md-6">
            <label for="dni" class="form-label"><i class="fa-solid fa-id-card me-1"></i>DNI <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="dni" name="dni" required>
          </div>
          <div class="col-md-6">
            <label for="usuario" class="form-label"><i class="fa-solid fa-user-circle me-1"></i>Usuario <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="usuario" name="usuario">
          </div>
          <div class="col-md-6">
            <label for="contrasena" class="form-label"><i class="fa-solid fa-lock me-1"></i>Contraseña <span class="text-danger">*</span></label>
            <input type="password" class="form-control" id="contrasena" name="contrasena" required>
          </div>
          <div class="col-md-6">
            <label for="repetir_contrasena" class="form-label"><i class="fa-solid fa-lock me-1"></i>Repetir contraseña <span class="text-danger">*</span></label>
            <input type="password" class="form-control" id="repetir_contrasena" name="repetir_contrasena" required>
          </div>
        </div>
        <div class="mt-4">
          <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-arrow-right-to-bracket me-2"></i>Registrarse</button>
          
        </div>
      </form>
      <div class="text-center mt-3">
        <a href="login.php" class="text-secondary"><i class="fa-solid fa-arrow-left me-1"></i> Volver al login</a>
      </div>
    </div>
  </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

</body>
</html>