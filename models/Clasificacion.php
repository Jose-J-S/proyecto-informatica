<?php

require_once "Conexion.php";

  class Clasificacion extends Conexion{

    //Objeto que almacene la conexion que obtenemos
    private $acceso;

    //Constructor

    public function __construct(){
      $this->acceso = parent::getConexion();
    }

    public function listarClasificaciones(){
      try {

        //Preparamos la consulta
        $consulta = $this->acceso->prepare("CALL spu_clasificaciones_listar()");

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