<?php

require_once '../models/Producto.php';

  if(isset($_GET['operacion'])){

    $producto = new Producto();

    if($_GET['operacion'] == 'listarProductos'){
      echo json_encode($producto->listarProductos());
    }

    if($_GET['operacion'] == 'registrarProducto'){
      $datosSolicitados = [
        "idclasificacion"   => $_GET['idclasificacion'],
        "idmarca"       => $_GET['idmarca'],
        "descripcion"     => $_GET['descripcion'],
        "esnuevo"       => $_GET['esnuevo'],
        "numeroserie"     => $_GET['numeroserie'],
        "precio"      => $_GET['precio']
      ];

      $producto->registrarProducto($datosSolicitados);
    }

    if($_GET['operacion'] == 'eliminarProducto'){
      $producto->eliminarProducto($_GET['idproducto']);
    }
    
    if ($_GET['operacion'] == 'getProducto'){
      echo json_encode($producto->getProducto($_GET['idproducto']));
    }

    if($_GET['operacion'] == 'actualizarProducto'){
      $datosSolicitados = [
        "idproducto"    => $_GET['idproducto'],
        "idclasificacion"   => $_GET['idclasificacion'],
        "idmarca"       => $_GET['idmarca'],
        "descripcion"     => $_GET['descripcion'],
        "esnuevo"       => $_GET['esnuevo'],
        "numeroserie"     => $_GET['numeroserie'],
        "precio"      => $_GET['precio']
      ];

      $producto->actualizarProducto($datosSolicitados);
    }


  }

?>