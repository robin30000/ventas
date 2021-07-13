<?php

require_once 'conection.php';

class cliente
{

    /*
     * metodo para crear los clientes
     */
    public function NuevoCliente($data)
    {
        session_start();
        try {

            if (!filter_var($data->cliente, FILTER_SANITIZE_STRING)) {
                $res = array('state' => 0, 'msg' => 'El nombre no es valido');
                echo json_encode($res);
                return;
            }
            if (!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
                $res = array('state' => 0, 'msg' => 'El correo no es valido');
                echo json_encode($res);
                return;
            }
            if (!filter_var($data->telefono, FILTER_SANITIZE_NUMBER_INT)) {
                $res = array('state' => 0, 'msg' => 'El telefono no es valido');
                echo json_encode($res);
                return;
            }

            $result = Conection::getInstance()->prepare("insert into cliente (cliente, telefono, email) 
                                                  values (?,?,?)");
            $result->bindParam(1, $data->cliente, PDO::PARAM_STR);
            $result->bindParam(2, $data->telefono, PDO::PARAM_STR);
            $result->bindParam(3, $data->email, PDO::PARAM_STR);
            $result->execute();


            if ($result->rowCount()) {
                $res = array('state' => 1, 'msg' => 'El cliente se a registrado correctamente');
            } else {
                $res = array('state' => 0, 'msg' => 'Ah ocurrido un error interno intentalo nuevamente.');
            }
            echo json_encode($res);

        } catch (Exception $e) {
            echo 'Error ' . $e->getMessage();
        }
    }

    /*
     * metodo para listar todos los clientes
     */
    public function ListarCliente()
    {
        session_start();

        try {

            $result = Conection::getInstance()->prepare("select id, cliente, email, telefono, case estado when 1 then 'Activo' else 'Inactivo' end estado from cliente");
            $result->execute();

            if ($result->rowCount()) {
                $res = array('state' => 1, 'msg' => $result->fetch(PDO::FETCH_ASSOC));
            } else {
                $res = array('state' => 0, 'msg' => 'No se encontraron clientes registrados.');
            }
            echo json_encode($res);

        } catch (Exception $e) {
            echo 'Error ' . $e->getMessage();
        }
    }

    /*
     * metodo para buscar los clientes
     */
    public function BuscarCliente($data)
    {
        session_start();

        try {
            $data->data = filter_var($data->data, FILTER_SANITIZE_NUMBER_INT);

            $dato = "%$data->data%";
            $result = Conection::getInstance()->prepare("select * from cliente where cliente like ?");
            $result->bindParam(1, $dato, PDO::PARAM_STR);
            $result->execute();

            if ($result->rowCount()) {
                $res = array('state' => 1, 'msg' => $result->fetch(PDO::FETCH_ASSOC));
            } else {
                $res = array('state' => 0, 'msg' => 'No se encontraron clientes registrados.');
            }
            echo json_encode($res);

        } catch (Exception $e) {
            echo 'Error ' . $e->getMessage();
        }
    }

    /*
     * metodo para editar un cliente
     */
    public function EditaCliente($data)
    {
        session_start();
        try {

            if (!filter_var($data->cliente, FILTER_SANITIZE_STRING)) {
                $res = array('state' => 0, 'msg' => 'El nombre no es valido');
                echo json_encode($res);
                return;
            }
            if (!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
                $res = array('state' => 0, 'msg' => 'El correo no es valido');
                echo json_encode($res);
                return;
            }
            if (!filter_var($data->telefono, FILTER_SANITIZE_NUMBER_INT)) {
                $res = array('state' => 0, 'msg' => 'El telefono no es valido');
                echo json_encode($res);
                return;
            }


            $result = Conection::getInstance()->prepare("update cliente set cliente = ?, telefono = ?, email = ? where id = ?");
            $result->bindParam(1, $data->cliente, PDO::PARAM_STR);
            $result->bindParam(2, $data->telefono, PDO::PARAM_STR);
            $result->bindParam(3, $data->email, PDO::PARAM_STR);
            $result->bindParam(4, $data->id, PDO::PARAM_STR);
            $result->execute();


            if ($result->rowCount()) {
                $res = array('state' => 1, 'msg' => 'El cliente se a editado correctamente');
            } else {
                $res = array('state' => 0, 'msg' => 'Ah ocurrido un error interno intentalo nuevamente.');
            }
            echo json_encode($res);

        } catch (Exception $e) {
            echo 'Error ' . $e->getMessage();
        }
    }

    /*
     * metodo para eliminar (cambia el estado de el cliente)
     */
    public function EliminaCliente($id)
    {
        session_start();

        try {

            $result = Conection::getInstance()->prepare("update cliente set estado = 0 where id = ?");
            $result->bindParam(1, $id, PDO::PARAM_INT);
            $result->execute();

            if ($result->rowCount()) {
                $res = array('state' => 1, 'msg' => 'El cliente se a desactivado correctamente.');
            } else {
                $res = array('state' => 0, 'msg' => 'No se encontraron clientes registrados.');
            }

            echo json_encode($res);

        } catch (Exception $e) {
            echo 'Error ' . $e->getMessage();
        }
    }

}

