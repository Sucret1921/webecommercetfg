<link rel="stylesheet" href="css/admin-error.css">
<?php

// ---- FUNCIONES PARA GESTIONAR VALIDACIONES DE FORMULARIO DE REGISTRO DE CLIENTES Y USUARIOS -----

/*
function esEmail($email){
    return filter_var($email, FILTER_VALIDATE_EMAIL) ? false : true;
}
*/
function esNull(array $parametros){
    foreach ($parametros as $parametro) {
        if(strlen(trim($parametro)) < 1 ){
            return true;
        }
    }
    return false;
}


function usuarioExistente($usuario, $con)
{

    $sql = $con->prepare("SELECT id FROM usuarios WHERE usuario = ? LIMIT 1");
    if ($sql->execute([$usuario])) {
        if($sql->fetchColumn() > 0) {
            return true; // El usuario ya existe
        }
    }
    return false;
}

function emailExistente($email, $con)
{

    $sql = $con->prepare("SELECT id FROM clientes WHERE email = ? LIMIT 1");
    if ($sql->execute([$email])) {
        if($sql->fetchColumn() > 0) {
            return true; // El email ya existe
        }
    }
    return false;
}

function telefonoExistente($telefono, $con)
{
    $sql = $con->prepare("SELECT id FROM clientes WHERE telefono = ? LIMIT 1");
    if ($sql->execute([$telefono])) {
        if($sql->fetchColumn() > 0) {
            return true; // El teléfono ya existe
        }
    }
    return false;
}

function dniExistente($dni, $con)
{
    $sql = $con->prepare("SELECT id FROM clientes WHERE dni = ? LIMIT 1");
    if ($sql->execute([$dni])) {
        if($sql->fetchColumn() > 0) {
            return true; // El DNI ya existe
        }
    }
    return false;   
}

function validarDNI($dni) {
    // Formato: 8 dígitos y una letra
    if (!preg_match('/^[0-9]{8}[A-Za-z]$/', $dni)) {
        return false;
    }
    $letra = strtoupper(substr($dni, -1));
    $numeros = substr($dni, 0, 8);
    $letras = 'TRWAGMYFPDXBNJZSQVHLCKE';
    return ($letra == $letras[$numeros % 23]);
}

function validarTelefono($telefono) {
    // Solo permite 9 dígitos numéricos
    return preg_match('/^[0-9]{9}$/', $telefono);
}

// --- FIN DE LAS FUNCIONES PARA GESTIONAR VALIDACIONES DE FORMULARIO DE REGISTRO DE CLIENTES Y USUARIOS ---


//FUNCION PARA MOSTRAR MENSAJE DE ERRORES DE FORMULARIO

function mostrarMensajeErrorValidacion(array $errores)
{
    if (count($errores) > 0) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert"><ul>';
            
        foreach ($errores as $error) {
            echo "<li>$error</li>";
        }
        echo '</ul>';
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
}

// GENERAR TOKEN PARA RECUPERAR LA CONTASEÑA

function generarToken(){
    return md5(uniqid(mt_rand(), false));
}


// FUNCIONES PARA REGISTRAR CLIENTES Y USUARIOS
// Estas funciones se encargan de insertar los datos del cliente y usuario en la base de datos

function registrarCliente(array $datos, $con)
{
    $sql = "INSERT INTO clientes (nombres, apellidos, email, telefono, estatus, dni, fecha_alta) VALUES (?, ?, ?, ?, 1, ?, NOW())";
    $sql = $con->prepare($sql);
    if ($sql->execute($datos)) {
        return $con->lastInsertId();
    }
    return 0;
}

function registrarUsuario(array $datos, $con)
{
    $sql = "INSERT INTO usuarios (id_cliente, usuario, password, token) VALUES (?, ?, ?, ?)";
    $sql = $con->prepare($sql);
    if ($sql->execute($datos)) {
        return true;
    }
    return false;
}


// FUNCION PARA INICIAR SESION DE USUARIO

function login($usuario, $password, $con)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $sql = "SELECT id, usuario, password, nombre FROM admin WHERE usuario LIKE ? AND activo = 1 LIMIT 1";
    $stmt = $con->prepare($sql);
    $stmt->execute([$usuario]);
    if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
     
            if(password_verify($password, $row['password'])) {
                $_SESSION['id_usuario'] = $row['id'];
                $_SESSION['usuario'] = $usuario;
                $_SESSION['tipo_usuario'] = $row['nombre'];
                header("Location: inicio.php");
                exit;
            } else {
                return 'Usuario o contraseña incorrectos';
            }
    }
    return 'Usuario o contraseña incorrectos';
}


// FUNCIONES PARA RECUPERAR CONTRASEÑAS 

function solicitarPassword($user_id, $con){
    $token = generarToken();

    $sql = $con->prepare("UPDATE usuarios SET token_password = ?, password_request=1 WHERE id = ?");
    if($sql->execute([$token, $user_id])) {
        return $token;
    }
    return null;
} 


