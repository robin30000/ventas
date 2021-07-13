<?php
error_reporting(E_ALL);
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
$data = json_decode(file_get_contents("php://input"));

if (isset($data->method)) {

    switch ($data->method) {

        case 'FacturaVenta':
            require_once '../class/venta.php';
            $user = new venta();
            $user->FacturaVenta($data->data);
            break;

        case 'ConsultaFactura':
            require_once '../class/venta.php';
            $user = new venta();

            $user->ConsultaFactura($data->id);
            break;

        case 'totalVentas':
            require_once '../class/venta.php';
            $user = new venta();

            $user->totalVentas();
            break;

        default:
            echo 'ninguna opción.';
            break;
    }
} else {
    echo 'ninguna opción valida.';
}



