<?php


require 'includes/config.php';
require 'includes/basededatos.php';
require 'clases/clientesFunciones.php';

$db = new Database();
$con = $db->conectar();

$proceso = isset($_GET['pago']) ? 'pago' : 'login';

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


<header>

</header>


<!-- FORMULARIO DE ENTRADA -->

<main>
<div class="form-login d-flex align-items-center justify-content-center min-vh-100">
    <div class="login-box">
        <h2 class="mb-4 text-center"><i class="fa-solid fa-right-to-bracket me-2"></i>Iniciar Sesión</h2>

        <?php if (!empty($error)): ?>
          <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?php foreach ($error as $err): ?>
              <div><?php echo htmlspecialchars($err); ?></div>
            <?php endforeach; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
          </div>
        <?php endif; ?>

        <form method="post" action="login.php">
        <input type="hidden" name="proceso" value="<?php echo $proceso; ?>">

            <div class="mb-3 input-icon">
                <label for="usuario" class="form-label"><i class="fa-solid fa-user me-1"></i>Usuario o correo electrónico</label>
                <input type="text" class="form-control ps-5" id="usuario" name="usuario" autocomplete="username">
                
            </div>
            <div class="mb-3 input-icon">
                <label for="password" class="form-label"><i class="fa-solid fa-lock me-1"></i>Contraseña</label>
                <input type="password" class="form-control ps-5" id="password" name="password" autocomplete="current-password">
                
            </div>
            
            <button type="submit" class="btn btn-primary w-100 mt-3"><i class="fa-solid fa-arrow-right-to-bracket me-2"></i>Acceder</button>
        </form>
        
        <div class="text-center mt-3">
            <span>¿No tienes cuenta?</span> <a href="registrar.php">Crea una gratis</a>
        </div>
        <div class="text-center mt-3">
            <a href="index.php" class="text-secondary"><i class="fa-solid fa-arrow-left me-1"></i> Volver a la tienda principal</a>
        </div>
    </div>
</div>
</main>


<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Tenda Online</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" crossorigin="anonymous">
  <link rel="stylesheet" href="css/loginform.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

</body>
</html>