<?php
error_reporting(0);
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
$data = json_decode(file_get_contents("php://input"));
if (isset($data->method)) {
    switch ($data->method) {

        case 'NuevoProducto':
            require_once '../class/producto.php';
            $user = new producto();
            $user->NuevoProducto($data->data);
            break;
        case 'EditaProducto':
            require_once '../class/producto.php';
            $user = new producto();
            $user->EditaProducto($data->data);
            break;
        case 'BuscarProducto':
            require_once '../class/producto.php';
            $user = new producto();
            $user->BuscarProducto($data->data);
            break;
        case 'EliminaProducto':
            require_once '../class/producto.php';
            $user = new producto();
            $user->EliminaProducto($data->data);
            break;

        default:
            echo 'ninguna opción.';
            break;
    }
} else {
    echo 'ninguna opción valida.';
}


