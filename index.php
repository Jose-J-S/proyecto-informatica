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
  <link rel="stylesheet" href="views/css/estilos.css">
</head>

<body>
  <header>
    <a class="btn btn-dark btn-sm" id="registrarse" href="newuser.php"><i class="fa-solid fa-user-plus"></i> Registrarse</a>
  </header>

  <main>
    <section>
      <form action="" autocomplete="off">
        <article>
          <h1 style="color: #1E3AA4;">Iniciar Sesión</h1>
          <hr>
          <p>Acceda con correo electrónico y contraseña</p>
        </article>

        <article>
          <img src="views/img/logo2.gif" style="height: 140px;">
        </article>

        <article>
          <div class="input-group input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fa-solid fa-at"></i></span>
            </div>
            <input type="email" class="form-control" id="email" autofocus="true" placeholder="Correo electrónico">
          </div>

          <div class="input-group input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
            </div>
            <input type="password" class="form-control" id="clave" placeholder="Contraseña">
          </div>
          <hr>

          <div class="form-group">
            <a href="restore-user.php">¿Olvidé mi contraseña?</a><br><br>
            <button class="btn btn-secondary btn-sm" id="cancelar" type="reset"><i class="fa-solid fa-arrow-rotate-left"></i> Cancelar</button>
            <button class="btn btn-info btn-sm" id="acceder" type="button"><i class="fa-solid fa-right-to-bracket"></i>
              Acceder
            </button>
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

  <!-- Boostrap 4.6 -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>

  <!-- Font Awesome -->
  <script src="https://kit.fontawesome.com/7ac08d8e48.js" crossorigin="anonymous"></script>

  <script>
    $(document).ready(function() {

      function login() {
        let email = $("#email").val();
        let clave = $("#clave").val();

        $.ajax({
          url: './controllers/usuario.controller.php',
          type: 'GET',
          dataType: 'JSON',
          data: {
            'operacion': 'login',
            'email': email,
            'clave': clave
          },
          success: function(result) {
            // console.log(result);
            if (result.acceso) {
              alert(`Bienvenido al Sistema ${result.apellidos} ${result.nombres}`);
              window.location.href = "views/producto.view.php"
            } else {
              alert(result.mensaje);
            }
          }
        });
      }

      $("#acceder").click(login);

      $("#email").keypress(function(e) {
        if (e.which == 13) {
          login();
        }
      });

      $("#clave").keypress(function(e) {
        if (e.which == 13) {
          login();
        }
      });

    });
  </script>

  <script>
    document.getElementById("cancelar").addEventListener("click", () => {
      document.getElementById("email").focus();
    });
  </script>
</body>

</html>