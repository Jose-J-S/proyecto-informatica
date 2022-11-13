<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro de usuarios</title>
  <!--  Boostrap 4.6 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
</head>

<body>
  <div class="container mt-5">
    <h2>Formulario de Registro</h2>
    <p>Por favor complete el formulario</p>
    <hr>

    <div class="card">
      <form action="" id="formulario-usuario" autocomplete="off">
        <div class="card-header bg-info text-light">
          <strong>Datos solicitados</strong>
        </div>

        <div class="card-body">
          <div class="form-group">
            <label for="apellidos">Apellidos:</label>
            <input type="text" class="form-control form-control-sm" id="apellidos" autofocus="true">
          </div>

          <div class="form-group">
            <label for="nombres">Nombres:</label>
            <input type="text" class="form-control form-control-sm" id="nombres">
          </div>

          <div class="form-group">
            <label for="email">Correo electrónico:</label>
            <input type="email" class="form-control form-control-sm" id="email">
          </div>

          <div class="form-group">
            <label for="clave1">Contraseña:</label>
            <input type="password" class="form-control form-control-sm" id="clave1">
          </div>

          <div class="form-group">
            <label for="clave2">Repetir Contraseña:</label>
            <input type="password" class="form-control form-control-sm" id="clave2">
          </div>
        </div>

        <div class="card-footer text-right">
          <button class="btn btn-secondary btn-sm" id="cancelar" type="reset"><i class="fa-solid fa-arrow-rotate-left"></i> Cancelar</button>
          <a class="btn btn-dark btn-sm" id="ini-usu" href="index.php"><i class="fa-solid fa-right-to-bracket"></i> Iniciar Sesión</a>
          <button class="btn btn-primary btn-sm" id="registrar" type="button"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
        </div> <!-- Fin footer -->
      </form>
    </div> <!-- Fin card -->
  </div> <!-- Fin container -->

  <!-- Libreria jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

  <!-- SweetAlert -->
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Boostrap 4.6 -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>

  <!-- Font Awesome -->
  <script src="https://kit.fontawesome.com/7ac08d8e48.js" crossorigin="anonymous"></script>

  <script>
    $(document).ready(function() {

      let apellidos = "";
      let nombres = "";
      let email = "";
      let clave1 = "";
      let clave2 = "";

      function alertar(textoMensaje = "") {
        Swal.fire({
          title: "Usuarios",
          text: textoMensaje,
          icon: "info",
          footer: "SENATI - Ingeniería de Software",
          timer: 2000,
          confirmButtonText: "Aceptar"
        });
      }

      //Envía datos por AJAX al controller
      function enviarDatosAjax() {
        //Array contiendo la info que necesita el controlador
        let datos = {
          "operacion": "signup",
          "apellidos": apellidos,
          "nombres": nombres,
          "email": email,
          "claveacceso": clave1
        };

        $.ajax({
          url: './controllers/usuario.controller.php',
          type: 'GET',
          data: datos,
          success: function(result) {
            if ($.trim(result) == "") {
              alertar("Nuevo usuario registrado");
            }
          }
        });
      }

      function signup() {
        apellidos = $("#apellidos").val();
        nombres = $("#nombres").val();
        email = $("#email").val();
        clave1 = $("#clave1").val();
        clave2 = $("#clave2").val();

        //Comprobamos todos los campos
        if (apellidos == "" || nombres == "" || email == "" || clave1 == "" || clave2 == "") {
          alertar("complete el formulario por favor");
        } else {
          //Verificamos las claves
          if (clave1 != clave2) {
            alertar("Las contraseñas no coinciden");
          } else {
            //Preguntamos si desea guardar los datos ingresados
            Swal.fire({
              title: "Usuarios",
              text: "¿Desea registrarse?",
              icon: "question",
              footer: "SENATI - Ingeniería de Software",
              confirmButtonText: "Aceptar",
              showCancelButton: true,
              cancelButtonText: "Cancelar"
            }).then((result) => {
              if (result.isConfirmed) {
                enviarDatosAjax();
                $("#formulario-usuario")[0].reset();
              }
            }); //Fin SweetAlert
          } //Fin else
        } //Fin else
      } //Fin función

      $("#registrar").click(signup);

      $("#apellidos").keypress(function(e) {
        if (e.which == 13) {
          signup();
        }
      });
      $("#nombres").keypress(function(e) {
        if (e.which == 13) {
          signup();
        }
      });
      $("#email").keypress(function(e) {
        if (e.which == 13) {
          signup();
        }
      });
      $("#clave1").keypress(function(e) {
        if (e.which == 13) {
          signup();
        }
      });
      $("#clave2").keypress(function(e) {
        if (e.which == 13) {
          signup();
        }
      });
    });
  </script>

  <script>
    document.getElementById("cancelar").addEventListener("click", () => {
      document.getElementById("apellidos").focus();
    });
  </script>
</body>

</html>