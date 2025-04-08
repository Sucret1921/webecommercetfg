<?php

require '../includes/config.php';
require '../includes/basededatos.php';


$datos = ['ok' => false];

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $id = isset($_POST['id']) ? $_POST['id'] : 0;

    if ($action === 'agregar') {
        $cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : 0;
        $respuesta = agregar($id, $cantidad);
        
        if ($respuesta > 0) {
            $datos['ok'] = true;
        }

        $datos['sub'] = MONEDA . number_format($respuesta, 2, '.', ',');
    }
}

echo json_encode($datos);

function agregar($id, $cantidad) {
    $res = 0;

    if ($id > 0 && $cantidad > 0 && is_numeric($cantidad)) {
        if (!isset($_SESSION['carrito']['productos'])) {
            $_SESSION['carrito']['productos'] = [];
        }

        // Sumar cantidad si ya existe
        if (isset($_SESSION['carrito']['productos'][$id])) {
            $_SESSION['carrito']['productos'][$id] += $cantidad;
        } else {
            $_SESSION['carrito']['productos'][$id] = $cantidad;
        }

        $db = new Database();
        $con = $db->conectar();

        $sql = $con->prepare("SELECT precio, descuento FROM productos WHERE id=? AND activo=1 LIMIT 1");
        $sql->execute([$id]);
        $row = $sql->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $precio = $row['precio'];
            $descuento = $row['descuento'];
            $precio_desc = $precio - (($precio * $descuento) / 100);
            $res = $cantidad * $precio_desc;
        }
    }

    return $res;
}
