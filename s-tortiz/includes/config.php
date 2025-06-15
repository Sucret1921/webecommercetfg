<?php

define("CLIENT_ID", "ARE5wNyXQql5Zx3bEOCH1-5tIbFvq7H7luRkYzcLtzK1W1NUCsAoyOlh1lunumlWhMzGnp5hVZKlyLaE");
define("CURRENCY","EUR");
define("KEY_TOKEN", "HOF.fer-192*");
define("MONEDA", "€");

session_start();



$num_cart = 0;

if (isset($_SESSION['carrito']['productos'])) {
    $num_cart = array_sum($_SESSION['carrito']['productos']);
}




?>