<?php
session_start();

//CUIDADO!
//Si el usuario YA inició sesión, no debe visualizar este view
if (isset($_SESSION['login']) && $_SESSION['login'] == true) {
  header("location:views/producto.view.php");
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bienvenido</title>
  <!--  Boostrap 4.6 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
  <!-- Estilos CSS -->
  <link rel="stylesheet" href="views/css/estilos.css">
</head>

<body>
  <header>
    <a class="btn btn-dark btn-sm" id="registrarse" href="newuser.php"><i class="fa-solid fa-user-plus"></i> Registrarse</a>
    <a class="btn btn-success btn-sm" id="login" href="index.php"><i class="fa-solid fa-user-plus"></i> Iniciar Sesión</a>
  </header>

  <main>
    <section>
      <form action="" autocomplete="off">
        <article>
          <h1 style="color: #1E3AA4;">Restablecer contraseña</h1>
          <hr>
          <p>Escribe tu cuenta de usuario</p>
        </article>

        <article>
          <img src="views/img/logo2.gif" style="height: 110px;">
        </article>

        <article>
          <div class="form-group">
            <div class="input-group">
              <input type="text" class="form-control form-control-sm" id="email" placeholder="alguien@correo.com" autofocus="true">
              <div class="input-group-append">
                <button type="button" class="btn btn-success btn-sm" id="enviar-SMS">Enviar SMS</button>
              </div>
            </div>
          </div>
        </article><br>

        <article class="row">
          <div class="col-md-3"><input type="text" class="form-control text-center val" maxlength="1" id="v1"></div>
          <div class="col-md-3"><input type="text" class="form-control text-center val" maxlength="1" id="v2"></div>
          <div class="col-md-3"><input type="text" class="form-control text-center val" maxlength="1" id="v3"></div>
          <div class="col-md-3"><input type="text" class="form-control text-center val" maxlength="1" id="v4"></div>
          <br><br>
          <div class="input-group-append col-md-4">
            <button type="button" class="btn btn-primary btn-sm" id="comprobar">Comprobar</button>
          </div>
        </article>

      </form>
    </section>
  </main>

  <footer>
    &copy; Derechos Reservados
  </footer>

  <!-- Libreria jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

  <!-- jQuery Mask (Ofuscado) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

  <!-- Boostrap 4.6 -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>

  <!-- Font Awesome -->
  <script src="https://kit.fontawesome.com/7ac08d8e48.js" crossorigin="anonymous"></script>

  <script>
    $(document).ready(function() {
      var idusuario = '';

      //Configuramos las cajas de texto
      $(".val").mask("0");
      
      //Controlando las tabulaciones al momento de ingresar el token
      $("#v1").keyup(function (){
        //No podemos movernos si es que no tenemos datos
        if ($(this).val() != ""){
          $("#v2").focus();
        }
      });

      $("#v2").keyup(function (){if ($(this).val() != ""){$("#v3").focus();}});
      $("#v3").keyup(function (){if ($(this).val() != ""){$("#v4").focus();}});

      //PRIMERA FUNCIÓN A EJECUTARSE
      function enviarSMS(){
        let email = $("#email").val();

        if (email != ""){
          $.ajax({
            url: './controllers/usuario.controller.php',
            type: 'GET',
            dataType: 'JSON',
            data: {'operacion': 'getTelefono', 'email': email},
            success: function (result){
              idusuario = result.idusuario;
              console.log(result);
            }
          });
        }
      }


      function validarCodigo(){
        let v1 = $("#v1").val();
        let v2 = $("#v2").val();
        let v3 = $("#v3").val();
        let v4 = $("#v4").val();
        let codigo = '';

        if (v1 != '' && v2 != '' && v3 != '' && v4 != ''){
          codigo = v1 + v2 + v3 + v4;
          
          $.ajax({
            url: './controllers/desbloqueo.controller.php',
            type: 'GET',
            dataType: 'JSON',
            data: {
              'operacion': 'validarCodigo',
              'idusuario': idusuario,
              'coddesbloqueo': codigo
            },
            success: function (result){
              console.log(result);
            }
          });
        }
      }

      $("#enviar-SMS").click(enviarSMS);
      $("#comprobar").click(validarCodigo);

    });
  </script>
</body>

</html>