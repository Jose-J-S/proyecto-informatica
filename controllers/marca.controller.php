<?php

require_once '../models/Marca.php';

  if(isset($_GET['operacion'])){

    $marca = new Marca();

    if($_GET['operacion'] == 'listarMarcas'){
      $dataMarcas = $marca->listarMarcas();
      echo json_encode($dataMarcas);
    }
  }

?>