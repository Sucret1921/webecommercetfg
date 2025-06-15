<?php

require '../includes/config.php';

$datos = ['ok' => false];

if (isset($_POST['id']) && isset($_POST['token'])) {
    $id = $_POST['id'];
    $token = $_POST['token'];

    $token_tmp = hash_hmac('md5', $id, KEY_TOKEN);

    if ($token === $token_tmp) {
        if (!isset($_SESSION['carrito']['productos'])) {
            $_SESSION['carrito']['productos'] = [];
        }

        // Incrementar la cantidad del producto si ya existe, o agregarlo con cantidad 1
        if (isset($_SESSION['carrito']['productos'][$id])) {
            $_SESSION['carrito']['productos'][$id] += 1;
        } else {
            $_SESSION['carrito']['productos'][$id] = 1;
        }

        // Calcular el número total de productos en el carrito
        $datos['numero'] = array_sum($_SESSION['carrito']['productos']);
        $datos['ok'] = true;
    } else {
        $datos['ok'] = false;
        $datos['error'] = 'Token inválido';
    }
} else {
    $datos['ok'] = false;
    $datos['error'] = 'ID o token no proporcionado';
}

echo json_encode($datos);