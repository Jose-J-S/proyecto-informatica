<?php

require_once '../models/Desbloqueo.php';

if (isset($_GET['operacion'])){
  
  $desbloqueo = new Desbloqueo();

  if ($_GET['operacion'] == 'validarCodigo'){
    $idusuario = $_GET['idusuario'];
    $coddesbloqueo = $_GET['coddesbloqueo'];

    $resultado = $desbloqueo->validarCodigo($idusuario, $coddesbloqueo);
    echo json_encode($resultado);
  }
}

?>