<?php

require 'includes/config.php';
require 'includes/basededatos.php';
require 'clases/clientesFunciones.php';

$db = new Database();
$con = $db->conectar();

$error = [];

// Verificar si el formulario fue enviado   

if(!empty($_POST)) {
   $email = trim($_POST['email']);

    //VALIDACIONES PARA EL FORMULARIO
    if(esNull([$email])) {
        $error[] = 'Rellena todos los campos, son obligatorios.';
    }

    // Validar formato de email

    if(count($error) == 0) {
            $sql = $con->prepare("SELECT usuarios.id, clientes.nombres FROM usuarios INNER JOIN clientes ON usuarios.id_cliente = clientes.id WHERE clientes.email = LIKE ? LIMIT 1");
            $sql ->execute([$email]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $user_id = $row['id'];
            $nombres = $row['nombres'];

            $token = solicitarPassword($user_id, $con);

            // IMPLEMENTAR ENVÍO DE CORREO AQUÍ
    }

}


require 'header.html.php';

?>


<main>
<div class="form-login d-flex align-items-center justify-content-center min-vh-100">
    <div class="login-box">
        <h2 class="mb-4 text-center">Recuperar contraseña</h2>
        <?php mostrarMensajeErrorValidacion($error); ?>
        <form method="post" action="recuperarPass.php">
            <div class="mb-3">
                <label for="correo" class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" id="correo" name="correo" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Continuar</button>
        </form>
        <div class="text-center mt-3">
            <a href="login.php">Iniciar sesión</a>
        </div>
    </div>
</div>
</main>
<link rel="stylesheet" href="css/loginestilo.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>



</body>

</html>