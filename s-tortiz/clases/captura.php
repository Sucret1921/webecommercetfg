<?php

require '../includes/config.php';
require '../includes/basededatos.php';


$db = new Database();
$con = $db->conectar();


$json = file_get_contents('php://input');
$datos = json_decode($json, true);

// Verificar si la decodificación fue exitosa
if (is_array($datos)) {

    $id_transaccion = $datos['details']['id'];
    $total = $datos['details']['purchase_units'][0]['amount']['value'];
    $status = $datos['details']['status'];
    $fecha = $datos['details']['update_time'];
    $fecha_nueva = date('Y-m-d H:i:s', strtotime($fecha));

    /*
    $idCliente = $_SESSION['id_cliente'];
    $sql = $con->prepare("SELECT email FROM clientes WHERE id = ? AND estatus = 1");
    $sql->execute([$idCliente]);
    $row_cliente = $sql->fetch(PDO::FETCH_ASSOC);
*/
    $email = $datos['details']['payer']['email_address'];
    //$email = $row_cliente['email'];
    $id_cliente = $datos['details']['payer']['payer_id'];
    //$id_cliente = isset($_SESSION['id_cliente']);


    // Guardar los datos en la tabla compra
    $sql = $con->prepare("INSERT INTO compra (id_transaccion, fecha, status, email, id_cliente, total) VALUES (?, ?, ?, ?, ?, ?)");
    $sql->execute([$id_transaccion, $fecha_nueva, $status, $email, $id_cliente, $total]);
    $id_compra = $con->lastInsertId();

    if ($id_compra > 0) {
        $productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;

        if ($productos != null) {
            foreach ($productos as $id_producto => $cantidad) {
                // Obtener los datos del producto
                $sql = $con->prepare("SELECT nombre, precio, descuento FROM productos WHERE id=? AND activo=1");
                $sql->execute([$id_producto]);
                $row_producto = $sql->fetch(PDO::FETCH_ASSOC);

                if ($row_producto) {
                    $nombre = $row_producto['nombre'];
                    $precio = $row_producto['precio'];
                    $descuento = $row_producto['descuento'];
                    $precio_desc = $precio - (($precio * $descuento) / 100);

                    // Insertar los detalles de la compra en la tabla detalle_compra
                    $sql_insert = $con->prepare("INSERT INTO detalle_compra (id_compra, id_producto, nombre, precio, cantidad) VALUES (?, ?, ?, ?, ?)");
                    $sql_insert->execute([$id_compra, $id_producto, $nombre, $precio_desc, $cantidad]);
                }
                include "enviar_email.php"; // Enviar correo electrónico al cliente
            }
        }

        // Limpiar el carrito después de completar la compra
        unset($_SESSION['carrito']['productos']);
    }
}