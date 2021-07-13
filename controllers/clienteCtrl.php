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

        case 'NuevoCliente':
            require_once '../class/cliente.php';
            $user = new cliente;
            $user->NuevoCliente($data->data);
            break;
        case 'EditaCliente':
            require_once '../class/cliente.php';
            $user = new cliente;
            $user->EditaCliente($data->data);
            break;

        case 'listarCliente':
            require_once '../class/cliente.php';
            $user = new cliente;
            $user->ListarCliente();
            break;

        case 'BuscarCliente':
            require_once '../class/cliente.php';
            $user = new cliente;
            $user->BuscarCliente($data->data);
            break;

        case 'EliminaCliente':
            require_once '../class/cliente.php';
            $user = new cliente;
            $user->EliminaCliente($data->data);
            break;

        default:
            echo 'ninguna opción.';
            break;
    }
} else {
    echo 'ninguna opción valida.';
}


