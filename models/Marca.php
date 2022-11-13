<?php

require_once "Conexion.php";

  class Marca extends Conexion{

    //Objeto que almacene la conexion que obtenemos
    private $acceso;

    //Constructor

    public function __construct(){
      $this->acceso = parent::getConexion();
    }

    public function listarMarcas(){
      try {

        //Preparamos la consulta
        $consulta = $this->acceso->prepare("CALL spu_marcas_listar()");

        //Ejecutamos la consulta

        $consulta->execute();

        //Almacenamos el resultado de la consulta
        //fetchAll = todos los registros
        //FETCH_ASSOC = constante que representa a array asociativo
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);

        //Devolver los datos
        return $datos;
      } 
      catch (Exception $e) {
        die($e->getMessage());
      }
    }
  }
  
?>