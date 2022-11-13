<?php

//Una sesión: espacio reservado en la memoria del servidor, donde podemos
//almacenar datos utilizando CLAVES: VALORES. Estos valores son globales
session_start();      //Encabezado script PHP

$_SESSION['login'] = false;
$_SESSION['idusuario'] = "";
$_SESSION['apellidos'] = "";
$_SESSION['nombres'] = "";

require_once '../models/Usuario.php';
//Accceso a otro modelo
require_once '../models/Desbloqueo.php';

if (isset($_GET['operacion'])){
  
  $usuario = new Usuario();   //Instancia

  if ($_GET['operacion'] == 'login'){

    //Prueba
    // echo json_encode($usuario->login($_GET['email']));

    //0. Array que será leído por la VISTA(formulario-login)
    $resultado = [
      "acceso"    => false,
      "mensaje"   => "",
      "apellidos" => "",
      "nombres"   => ""
    ];

    //1. Verificar si existe el usuario (data = 0, 1)
    $data = $usuario->login($_GET['email']);

    if ($data){
      //2. El usuario sí existe
      $claveEncriptada = $data['claveacceso'];

      //3. Comprobar la clave de entrada(login) con la 
      if (password_verify($_GET['clave'], $claveEncriptada)){

        //Enviamos toda la info del usuario
        $resultado["acceso"] =  true;
        $resultado["mensaje"] = "Bienvenido al Sistema";
        $resultado["apellidos"] = $data['apellidos'];
        $resultado["nombres"] = $data['nombres'];

        $_SESSION['login'] = true;
        $_SESSION['idusuario'] = $data['idusuario'];
        $_SESSION['apellidos'] = $data['apellidos'];
        $_SESSION['nombres'] = $data['nombres'];

      }else{

        //La contraseña es incorrecta
        $resultado["acceso"] = false;
        $resultado["mensaje"] = "La contraseña es incorrecta";
      }


    }else{
      //No existe usuario
      $resultado["acceso"] = false;
      $resultado["mensaje"] = "El usuario NO existe";
    }

    //Enviando datos al view...
    echo json_encode($resultado);

  }

  if ($_GET['operacion'] == 'signup'){
    $resultado = [
      "apellidos"   => $_GET['apellidos'],
      "nombres"     => $_GET['nombres'],
      "email"       => $_GET['email'],
      "claveacceso" => password_hash($_GET['claveacceso'], PASSWORD_BCRYPT),
      "nivelacceso" => 'I',
    ];

    $usuario->signup($resultado);
  }

  if ($_GET['operacion'] == 'listarUsuarios'){
    echo json_encode($usuario->listarUsuarios());
  }

  if ($_GET['operacion'] == 'promoverUsuario'){
    $usuario->promoverUsuario($_GET['idusuario']);
  }

  if ($_GET['operacion'] == 'degradarUsuario'){
    $usuario->degradarUsuario($_GET['idusuario']);
  }

  if ($_GET['operacion'] == 'inhabilitarUsuario'){
    $usuario->inhabilitarUsuario($_GET['idusuario']);
  }

  if ($_GET['operacion'] == 'reiniciarUsuario'){
    $datosSolicitados = [
      "idusuario" => $_GET['idusuario'],
      "claveacceso" => password_hash($_GET['claveacceso'], PASSWORD_BCRYPT)
    ];
    
    $usuario->reiniciarUsuario($datosSolicitados);
  }

  if ($_GET['operacion'] == 'getTelefono'){
    $desbloqueo = new Desbloqueo();
    $resultado = $usuario->getTelefono($_GET['email']);
    $finProceso = [
      "status" => false,
      "idusuario" => "",
      "mensaje" => ""
    ];
    
    // echo json_encode($resultado);
    //Si el usuario existe, entonces creamos y enviamos el codigo desbloqueo
    if ($resultado){
      $idusuario = $resultado['idusuario'];
      $coddesbloqueo = random_int(1000, 9999);
      $desbloqueo->registrarDesbloqueo($idusuario, $coddesbloqueo);

      $finProceso["status"] = true;
      $finProceso["idusuario"] = $resultado['idusuario'];
      $finProceso["mensaje"] = "Se generó el código correctamente";

      // echo ;
    }else{
      //No encontramos el email...
      $finProceso["mensaje"] = "No encontramos al usuario";
    }
    
    echo json_encode($finProceso);
  }

  if ($_GET['operacion'] == 'logout'){
    session_destroy();
    session_unset();
    header("Location:../index.php");
  }
}
