<?php

require_once '../models/Clasificacion.php';

  if(isset($_GET['operacion'])){

    $clasificacion = new Clasificacion();

    if($_GET['operacion'] == 'listarClasificaciones'){
      $dataClasificacion = $clasificacion->listarClasificaciones();
      echo json_encode($dataClasificacion);
    }
  }

?>