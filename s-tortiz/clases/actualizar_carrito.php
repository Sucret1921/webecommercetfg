<?php

require '../includes/config.php';
require '../includes/basededatos.php';

$datos = ['ok' => false];

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $id = isset($_POST['id']) ? $_POST['id'] : 0;

    if ($action === 'agregar') {
        $cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : 0;
        $respuesta = actualizarCantidad($id, $cantidad);

        if ($respuesta['subtotal'] > 0) {
            $datos['ok'] = true;
            $datos['nombre'] = $respuesta['nombre'];
            $datos['precio'] = MONEDA . number_format($respuesta['precio'], 2, '.', ',');
            $datos['cantidad'] = $respuesta['cantidad'];
            $datos['sub'] = MONEDA . number_format($respuesta['subtotal'], 2, '.', ',');

            // Calcular el número total de productos en el carrito
            $datos['numero'] = array_sum($_SESSION['carrito']['productos']);
        }
    } else if ($action === 'eliminar') {
        $datos['ok'] = eliminar($id);
    } else if ($action === 'actualizar') {
        $cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : 0;
        $respuesta = actualizarCantidad($id, $cantidad);

        if ($respuesta['subtotal'] > 0) {
            $datos['ok'] = true;
            $datos['nombre'] = $respuesta['nombre'];
            $datos['precio'] = MONEDA . number_format($respuesta['precio'], 2, '.', ',');
            $datos['cantidad'] = $respuesta['cantidad'];
            $datos['sub'] = MONEDA . number_format($respuesta['subtotal'], 2, '.', ',');

            // Calcular el número total de productos en el carrito
            $datos['numero'] = array_sum($_SESSION['carrito']['productos']);
        }
    }
}

echo json_encode($datos);

function actualizarCantidad($id, $cantidad) {
    $res = ['subtotal' => 0, 'nombre' => '', 'precio' => 0, 'cantidad' => 0];

    if ($id > 0 && $cantidad > 0 && is_numeric($cantidad)) {
        if (!isset($_SESSION['carrito']['productos'])) {
            $_SESSION['carrito']['productos'] = [];
        }

        // Actualizar la cantidad del producto en la sesión
        $_SESSION['carrito']['productos'][$id] = $cantidad;

        $db = new Database();
        $con = $db->conectar();

        $sql = $con->prepare("SELECT nombre, precio, descuento FROM productos WHERE id=? AND activo=1 LIMIT 1");
        $sql->execute([$id]);
        $row = $sql->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $precio = $row['precio'];
            $descuento = $row['descuento'];
            $precio_desc = $precio - (($precio * $descuento) / 100);
            $res['subtotal'] = $cantidad * $precio_desc;
            $res['nombre'] = $row['nombre'];
            $res['precio'] = $precio_desc;
            $res['cantidad'] = $cantidad;
        }
    }

    return $res;
}

function eliminar($id) {
    if ($id > 0) {
        if (isset($_SESSION['carrito']['productos'][$id])) {
            unset($_SESSION['carrito']['productos'][$id]);
            return true;
        }
    }
    return false;
}
