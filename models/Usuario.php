<?php

require_once 'Conexion.php';

class Usuario extends Conexion {
  
  private $acceso;

  public function __construct(){
    $this->acceso = parent::getConexion();
  }

  //Consumirá a nuestro SPU (Procedimiento Almacenado)
  public function login($email){
    try{
      $consulta = $this->acceso->prepare("CALL spu_usuarios_login(?)");
      //Un arreglo no tiene un límite de objetos definidos
      $consulta->execute(array($email));

      //fetch     : Devuelve al menos 1 usuario (id, dni, ruc)
      //fetchAll  : 
      $datos = $consulta->fetch(PDO::FETCH_ASSOC);
      return $datos;
    }
    catch(Exception $e){
      die($e->getMessage());
    }
  }

  public function signup($datosGuardar){
    try{
      $consulta = $this->acceso->prepare("CALL spu_usuarios_signup(?,?,?,?,?)");
      //Un arreglo no tiene un límite de objetos definidos
      $consulta->execute(array(
        $datosGuardar['apellidos'],
        $datosGuardar['nombres'],
        $datosGuardar['email'],
        $datosGuardar['claveacceso'],
        $datosGuardar['nivelacceso']
      ));
    }catch(Exception $e){
      die($e->getMessage());
    }
  }

  public function listarUsuarios(){
    try{
      $consulta = $this->acceso->prepare("CALL spu_usuarios_listar()");
      $consulta->execute();
      $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
      return $datos;
    }catch(Exception $e){
      die($e->getMessage());
    }
  }

  public function promoverUsuario($idusuario){
    try{
      $consulta = $this->acceso->prepare("CALL spu_usuarios_promover(?)");
      $consulta->execute(array($idusuario));
    }
    catch(Exception $e){
      die($e->getMessage());
    }
  }

  public function degradarUsuario($idusuario){
    try{
      $consulta = $this->acceso->prepare("CALL spu_usuarios_degradar(?)");
      $consulta->execute(array($idusuario));
    }
    catch(Exception $e){
      die($e->getMessage());
    }
  }

  public function inhabilitarUsuario($idusuario){
    try{
      $consulta = $this->acceso->prepare("CALL spu_usuarios_inhabilitar(?)");
      $consulta->execute(array($idusuario));
    }
    catch(Exception $e){
      die($e->getMessage());
    }
  }

  public function reiniciarUsuario($datosGuardar){
    try{
      $consulta = $this->acceso->prepare("CALL spu_usuarios_reiniciar(?,?)");
      $consulta->execute(array(
        $datosGuardar['idusuario'],
        $datosGuardar['claveacceso']
      ));
    }
    catch(Exception $e){
      die($e->getMessage());
    }
  }

  public function getTelefono($email){
    try{
      $consulta = $this->acceso->prepare("CALL spu_usuarios_gettelefono(?)");
      $consulta->execute(array($email));

      // fetch : Para restriccion UNIQUE (1 dato como máximo 'ejm: el email')
      // fetchAll : (Obtener conjunto de registros - resultado)
      $datos = $consulta->fetch(PDO::FETCH_ASSOC);
      return $datos;
    }catch(Exception $e){
      die($e->getMessage());
    }
  }

} //Fin de la clase Usuario

?>