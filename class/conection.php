<?php

class Conection extends PDO
{

    private static $instance;
    private static $tipo_de_base = 'mysql';
    private static $host = 'localhost';
    private static $nombre_de_base = 'ventas';
    private static $usuario = 'root';
    private static $contrasena = '';

    private static $opciones = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    public function __construct()
    {
        try {
            parent::__construct(SELF::$tipo_de_base . ':host=' . SELF::$host . ';dbname=' . SELF::$nombre_de_base, SELF::$usuario, SELF::$contrasena, SELF::$opciones);
        } catch (PDOException $e) {
            echo 'Ha surgido un error y no se puede conectar a la base de datos. Detalle: ' . $e->getMessage();
            exit;
        }
    }

    public static function getInstance()
    {
        if (!isset (self::$instance)) {
            try {

                if (self::$instance === null) {
                    $ins = new Conection();
                    $ins->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $ins->setAttribute(PDO::ATTR_EMULATE_PREPARES, TRUE);
                    self::$instance = $ins;
                    return self::$instance;
                }

            } catch (PDOException $e) {
                echo 'Ha surgido un error y no se puede conectar a la base de datos. Detalle: ' . $e->getMessage();
                exit;
            }
        }
        return self::$instance;
    }

}
