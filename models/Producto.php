<?php

require_once "Conexion.php";

  class Producto extends Conexion{

    //Objeto que almacene la conexion que obtenemos
    private $acceso;

    //Constructor

    public function __construct(){
      $this->acceso = parent::getConexion();
    }

    public function listarProductos(){
      try {

        //Preparamos la consulta
        $consulta = $this->acceso->prepare("CALL spu_productos_listar()");

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

    public function registrarProducto($datosGuardar){
      try {
        $consulta = $this->acceso->prepare("CALL spu_productos_registrar (?,?,?,?,?,?)");

        $consulta->execute(array(
          $datosGuardar['idclasificacion'],
          $datosGuardar['idmarca'],
          $datosGuardar['descripcion'],
          $datosGuardar['esnuevo'],
          $datosGuardar['numeroserie'],
          $datosGuardar['precio']
        ));
      } 
      catch (Exception $e) {
        die($e->getMessage());
      }
    }

    public function eliminarProducto($idproducto){
      try{
        $consulta = $this->acceso->prepare("CALL spu_productos_eliminar(?)");
        $consulta->execute(array($idproducto));
      }
      catch(Exception $e){
        die($e->getMessage());
      }
    }

    public function getProducto($idproducto){
      try{
        $consulta = $this->acceso->prepare("CALL spu_productos_obtener(?)");
        $consulta->execute(array($idproducto));

        return $consulta->fetch(PDO::FETCH_ASSOC);
      }
      catch(Exception $e){
        die($e->getMessage());
      }
    }

    public function actualizarProducto($datosGuardar){
      try{
        $consulta = $this->acceso->prepare("CALL spu_productos_actualizar (?,?,?,?,?,?,?)");
        
        $consulta->execute(array(
          $datosGuardar['idproducto'],
          $datosGuardar['idclasificacion'],
          $datosGuardar['idmarca'],
          $datosGuardar['descripcion'],
          $datosGuardar['esnuevo'],
          $datosGuardar['numeroserie'],
          $datosGuardar['precio']
        ));
      }
      catch(Exception $e){
        die($e->getMessage());
      }
    }

  }
  
?>