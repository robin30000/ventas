<?php

require_once 'conection.php';

class producto
{
    /*
     * metodo para crer un producto
     */
    public function NuevoProducto($data)
    {

        session_start();

        try {

            $consultProducto = Conection::getInstance()->prepare("select * from producto where producto = ?");
            $consultProducto->bindParam(1, $data->producto, PDO::PARAM_STR);
            $consultProducto->execute();

            if ($consultProducto->rowCount()) {
                $response = array('state' => 0, 'El producto ya se encuentra registrado');
                echo json_encode($response);
                return;
            }
            $images = "../images/" . md5($data->foto);
            $result = Conection::getInstance()->prepare("insert into producto(producto, precio, descripcion, foto, iva, cantidad) 
                                                        VALUES (?,?,?,?,?,?)");
            $result->bindParam(1, $data->producto, PDO::PARAM_STR);
            $result->bindParam(2, $data->precio, PDO::PARAM_INT);
            $result->bindParam(3, $data->descripcion, PDO::PARAM_STR);
            $result->bindParam(4, $images, PDO::PARAM_STR);
            $result->bindParam(5, $data->iva, PDO::PARAM_INT);
            $result->bindParam(6, $data->cantidad, PDO::PARAM_INT);
            $result->execute();

            if ($result->rowCount()) {
                $response = array('state' => 1, 'msg' => 'El produto se ingreso correctamente');
            } else {
                $response = array('state' => 0, 'msg' => 'Ah ocurrido un error interno intentalo nuevamente.');
            }

            echo json_encode($response);

        } catch (Exception $e) {
            echo 'Error ' . $e->getMessage();
        }
    }

    /*
     * metodo para editar un producto
     */
    public function EditaProducto($data)
    {
        session_start();
        try {
            $images = "../images/" . md5($data->foto);
            $result = Conection::getInstance()->prepare("update producto set producto = ?, precio = ?, descripcion = ?,
                                                        foto = ?, iva = ?, cantidad = ? where id = ?");
            $result->bindParam(1, $data->producto, PDO::PARAM_STR);
            $result->bindParam(2, $data->precio, PDO::PARAM_INT);
            $result->bindParam(3, $data->descripcion, PDO::PARAM_STR);
            $result->bindParam(4, $images, PDO::PARAM_STR);
            $result->bindParam(5, $data->iva, PDO::PARAM_INT);
            $result->bindParam(6, $data->cantidad, PDO::PARAM_INT);
            $result->bindParam(7, $data->id, PDO::PARAM_INT);
            $result->execute();

            if ($result->rowCount()) {
                $response = array('stare' => 1, 'msg' => 'El producto se actualizo correctamente.');
            } else {
                $response = array('stare' => 0, 'msg' => 'Ah ocurrido un error interno intentalo nuevamente');
            }
            echo json_encode($response);

        } catch (Exception $e) {
            echo 'Error ' . $e->getMessage();
        }
    }

    /*
     * metodo para buscar un producto
     */
    public function BuscarProducto($data)
    {

        session_start();

        try {

            $dato = "%$data%";
            $result = Conection::getInstance()->prepare("select * from producto where producto like ?");
            $result->bindParam(1, $dato, PDO::PARAM_STR);
            $result->execute();

            if ($result->rowCount()) {
                $res = array('state' => 1, 'msg' => $result->fetch(PDO::FETCH_ASSOC));
            } else {
                $res = array('state' => 0, 'msg' => 'No se encontro el producto.');
            }
            echo json_encode($res);

        } catch (Exception $e) {
            echo 'Error ' . $e->getMessage();
        }

    }

    /*
     * metodo para elimnar un producto (cambia el estado)
     */
    public function EliminaProducto($id)
    {
        session_start();
        try {

            $result = Conection::getInstance()->prepare("update producto set estado = 0 where id = ?");
            $result->bindParam(1, $id, PDO::PARAM_STR);
            $result->execute();

            if ($result->rowCount()) {
                $res = array('state' => 1, 'msg' => "El producto se a eliminado correctamente.");
            } else {
                $res = array('state' => 0, 'msg' => 'No se encontro el producto.');
            }
            echo json_encode($res);

        } catch (Exception $e) {
            echo 'Error ' . $e->getMessage();
        }
    }

}