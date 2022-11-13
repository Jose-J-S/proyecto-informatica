<?php

require_once 'Conexion.php';

class Desbloqueo extends Conexion {
  private $acceso;

  public function __CONSTRUCT(){
    $this->acceso = parent::getConexion();
  }

  public function registrarDesbloqueo($idusuario, $coddesbloqueo){
    try{
      $consulta = $this->acceso->prepare("CALL spu_desbloqueos_registrar(?,?)");
      $consulta->execute(array($idusuario, $coddesbloqueo));
    }catch(Exception $e){
      die($e->getMessage());
    }
  }

  //Este método retorna resultado, mensaje
  public function validarCodigo($idusuario, $coddesbloqueo){
    try{
      $consulta = $this->acceso->prepare("CALL spu_desbloqueos_validar(?,?)");
      $consulta->execute(array($idusuario, $coddesbloqueo));

      $dato = $consulta->fetch(PDO::FETCH_ASSOC);
      return $dato;
    }catch(Exception $e){
      die($e->getMessage());
    }
  }
  
}

?>