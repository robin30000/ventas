<?php

require_once 'conection.php';

class venta
{

    /*
     * metodo para generar la factura y los detalles
     */
    public function FacturaVenta($data)
    {
        session_start();

        try {

            /*
             * se valida que el cliente este activo
             */

            $valida = Conection::getInstance()->prepare("select * from cliente where id = ? and estado = 1");
            $valida->bindParam(1, $data->cliente_id, PDO::PARAM_INT);
            $valida->execute();

            if ($valida->rowCount != 1) {
                $response = array('state' => 0, 'msg' => 'El usuario no se encuentra activo.');
                echo json_encode($response);
                return;
            }

            /*
             * se inicia la transaccion
             */

            Conection::getInstance()->beginTransaction();

            /*
             * se valida que el producto este activo y que haya disponibilidad
             */

            foreach ($data->producto as $producto) {

                $result = Conection::getInstance()->prepare("select cantidad from producto where id = ? and estado = 1");
                $result->bindParam(1, $producto->producto_id, PDO::PARAM_INT);
                $result->execute();
                $res = $result->fetch(PDO::FETCH_OBJ);
                if ($res->cantidad == 0) {
                    $response = array('El producto ' . $res->producto . ' se encuentra agotado o incativo');
                    echo json_encode($response);
                    return;
                }
                $cantidadProducto = ($res->cantidad - $producto->cantidad);
                if ($cantidadProducto <= 0) {
                    $response = array('El producto ' . $res->producto . ' se encuentra agotado o incativo');
                    echo json_encode($response);
                    return;
                }

                /*
                 * se actualiza la cantidad del producto
                 */

                $stmt = Conection::getInstance()->prepare("update producto set cantidad = cantidad - ? where id = ?");

                $stmt->bindParam(1, $producto->cantidad, PDO::PARAM_INT);
                $stmt->bindParam(2, $producto->producto_id, PDO::PARAM_INT);
                $stmt->execute();

            }

            /*
             * se genera la factuta
             */

            $stmt1 = Conection::getInstance()->prepare("insert into factura (valor_total, cliente_id) 
                                                  values (0,?)");
            $stmt1->bindParam(1, $data->cliente_id, PDO::PARAM_INT);
            $stmt1->execute();
            $factura_id = Conection::getInstance()->lastInsertId();

            /*
             * se consulta los detalles de el producto y luego se genera el detalle de la factura
             */

            foreach ($data->producto as $producto) {

                $result = Conection::getInstance()->prepare("select cantidad, producto, precio, iva from producto where id = ?");
                $result->bindParam(1, $producto->producto_id, PDO::PARAM_INT);
                $result->execute();

                $res = $result->fetch(PDO::FETCH_OBJ);
                $valorTotal = $producto->cantidad * $res->precio;

                if ($res->iva > 0) {
                    $valorTota = $valorTotal * ($res->iva / 100);
                    $valorTotal = $valorTotal + $valorTota;
                }

                $detalle = Conection::getInstance()->prepare("INSERT INTO detalle_factura (
                                                                                                    factura_id,
                                                                                                    producto_id,
                                                                                                    cantidad,
                                                                                                    valor
                                                                                                )
                                                                                                VALUES
                                                                                                    (?,?,?,?)");
                $detalle->bindParam(1, $factura_id, PDO::PARAM_INT);
                $detalle->bindParam(2, $producto->producto_id, PDO::PARAM_INT);
                $detalle->bindParam(3, $producto->cantidad, PDO::PARAM_INT);
                $detalle->bindParam(4, $valorTotal, PDO::PARAM_INT);
                $detalle->execute();
            }
            /*
             * se ingresa el valor total en la factura
             */
            $stmt1 = Conection::getInstance()->prepare("update factura set valor_total = ? where id = ?");
            $stmt1->bindParam(1, $valorTotal, PDO::PARAM_INT);
            $stmt1->bindParam(2, $factura_id, PDO::PARAM_INT);
            $stmt1->execute();

            Conection::getInstance()->commit();

            /*
             * se genera los datos de la factura
             */

            $valTot = Conection::getInstance()->prepare("SELECT
                                                                    sum(valor) valorTotal,
                                                                    c.cliente,
                                                                    c.email,
                                                                    c.telefono
                                                                FROM
                                                                    detalle_factura d
                                                                INNER JOIN factura f ON (f.id = d.factura_id)
                                                                INNER JOIN cliente c ON f.cliente_id = c.id
                                                                WHERE
                                                                    factura_id = ?");
            $valTot->bindParam(1, $factura_id, PDO::PARAM_INT);
            $valTot->execute();

            $resDatoscliente = $valTot->fetch(PDO::FETCH_OBJ);

            $detProp = Conection::getInstance()->prepare("SELECT
                                                                    p.producto,
                                                                    d.cantidad,
                                                                    P.precio,
                                                                    p.iva
                                                                FROM
                                                                    detalle_factura d
                                                                INNER JOIN producto p ON (p.id = d.producto_id)
                                                                WHERE
                                                                    d.factura_id = ?");
            $detProp->bindParam(1, $factura_id, PDO::PARAM_INT);
            $detProp->execute();

            $detalleProducto = $detProp->fetchAll(PDO::FETCH_ASSOC);

            if ($result->rowCount()) {
                $res = array('state' => 1, 'detalle' => $detalleProducto, 'cliente' => $resDatoscliente);
            } else {
                $res = array('state' => 0, 'msg' => 'Ah ocurrido un error interno intentalo nuevamente.');
            }

            echo json_encode($res);

        } catch (Exception $e) {
            echo 'Error ' . $e->getMessage();
        }

    }

    /*
     * metodo para traer los datos de una factura
     */
    public function ConsultaFactura($id)
    {
        session_start();
        try {
            $valTot = Conection::getInstance()->prepare("SELECT
                                                                    sum(valor) valorTotal,
                                                                    c.cliente,
                                                                    c.email,
                                                                    c.telefono
                                                                FROM
                                                                    detalle_factura d
                                                                INNER JOIN factura f ON (f.id = d.factura_id)
                                                                INNER JOIN cliente c ON f.cliente_id = c.id
                                                                WHERE
                                                                    factura_id = ?");
            $valTot->bindParam(1, $id, PDO::PARAM_INT);
            $valTot->execute();

            $resDatoscliente = $valTot->fetch(PDO::FETCH_OBJ);

            $detProp = Conection::getInstance()->prepare("SELECT
                                                                    p.producto,
                                                                    d.cantidad,
                                                                    P.precio,
                                                                    p.iva
                                                                FROM
                                                                    detalle_factura d
                                                                INNER JOIN producto p ON (p.id = d.producto_id)
                                                                WHERE
                                                                    d.factura_id = ?");
            $detProp->bindParam(1, $id, PDO::PARAM_INT);
            $detProp->execute();

            $detalleProducto = $detProp->fetchAll(PDO::FETCH_ASSOC);

            if ($detProp->rowCount()) {
                $res = array('state' => 1, 'detalle' => $detalleProducto, 'cliente' => $resDatoscliente);
            } else {
                $res = array('state' => 0, 'msg' => 'Ah ocurrido un error interno intentalo nuevamente.');
            }

            echo json_encode($res);
        } catch (Exception $e) {
            echo 'Error ' . $e->getMessage();
        }
    }

}