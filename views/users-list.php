<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Usuarios</title>
  <!--  Boostrap 4.6 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
  <!-- DataTable -->
  <link rel="stylesheet" href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
  <!-- DataTable Responsive -->
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css">
</head>

<body>
  <div class="mt-5" style='width: 95%; margin: 0 auto;'>
    <h2 class="text-center mb-4">Lista de Usuarios</h2>

    <div class="table-responsive">
      <table class="table display responsive nowrap" width="100%" id="tabla-usuarios">
        <!-- <colgroup>
          <col width="2%">
          <col width="20%">
          <col width="20%">
          <col width="20%">
          <col width="12%">
          <col width="12%">
          <col width="8%">
        </colgroup> -->
        <thead class="table-info">
          <tr>
            <th>#</th>
            <th><i class="fa-solid fa-user"></i> Apellidos</th>
            <th><i class="fa-regular fa-user"></i> Nombres</th>
            <th><i class="fa-solid fa-at"></i> Email</th>
            <th><i class="fa-solid fa-user-shield"></i> Nivel Acceso</th>
            <th><i class="fa-regular fa-clock"></i> Fecha Registro</th>
            <th><i class="fa-solid fa-wheat-awn-circle-exclamation"></i> Acciones</th>
          </tr>
        </thead>

        <tbody>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Zona de Modales -->
  <div class="modal fade" id="modal-reiniciar" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="titulo-modal-reiniciar" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary text-light">
          <h5 class="modal-title" id="titulo-modal-reiniciar">Reinicio de contraseña</h5>
          <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <!-- Formulario de reinicio de contraseña -->
          <form action="" id="formulario-reiniciar" autocomplete="off">
            <!-- Creación de controles -->
            <div class="form-group">
              <label for="clave1">Contraseña:</label>
              <input type="password" class="form-control form-control-sm" id="clave1">
            </div>

            <div class="form-group">
              <label for="clave2">Repetir contraseña:</label>
              <input type="password" class="form-control form-control-sm" id="clave2">
            </div>
          </form>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" id="cancelar-modal" data-dismiss="modal"><i class="fa-solid fa-thumbs-down"></i> Cancelar</button>
          <button type="button" class="btn btn-success btn-sm" id="guardar-clave"><i class="fa-solid fa-star"></i> Guardar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Libreria jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

  <!-- SweetAlert2 -->
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Boostrap 4.6 -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>

  <!-- Datatable -->
  <script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

  <!-- DataTable Responsive -->
  <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>

  <!-- Font Awesome -->
  <script src="https://kit.fontawesome.com/7ac08d8e48.js" crossorigin="anonymous"></script>

  <!--  Promover : Pasar de invitado a admin
        Degradar : Pasar de admin a invitado
        Inhabilitar : Cambiar de estado a '0'
        Reiniciar : Formulario para reiniciar contraseña -->

  <!-- Funciones definidas -->
  <script>
    $(document).ready(function() {
      var idusuario = 0;

      function alertarToast(titulo = "", textoMensaje = "", icono = "") {
        Swal.fire({
          title: titulo,
          text: textoMensaje,
          icon: icono,
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 1500,
          timerProgressBar: true
        });
      }

      function mostrarUsuarios() {
        $.ajax({
          url: '../controllers/usuario.controller.php',
          type: 'GET',
          data: 'operacion=listarUsuarios',
          success: function(result) {
            let nivelAcceso = '';
            let registros = JSON.parse(result);
            let tabla = $("#tabla-usuarios").DataTable();
            tabla.destroy();

            $("#tabla-usuarios tbody").html("");

            registros.forEach(registro => {
              nivelAcceso = registro['nivelacceso'] == 'A' ? 'Administrador' : 'Invitado';
              let nuevaFila = `
                <tr>
                  <td>${registro['idusuario']}</td>
                  <td>${registro['apellidos']}</td>
                  <td>${registro['nombres']}</td>
                  <td>${registro['email']}</td>
                  <td>${nivelAcceso}</td>
                  <td>${registro['fechacreacion']}</td>
                  <td>
                    <a href='#' data-nivelacceso='${registro['nivelacceso']}' data-idusuario='${registro['idusuario']}' class='btn btn-dark btn-sm promover' title='Promover'><i class='fa-solid fa-arrow-up-wide-short'></i></a>
                    <a href='#' data-nivelacceso='${registro['nivelacceso']}' data-idusuario='${registro['idusuario']}' class='btn btn-secondary btn-sm degradar' title='Degradar'><i class='fa-solid fa-arrow-down-wide-short'></i></a>
                    <a href='#' data-idusuario='${registro['idusuario']}' class='btn btn-danger btn-sm inhabilitar' title='Inhabilitar'><i class='fa-solid fa-user-xmark'></i></a>
                    <a href='#' data-idusuario='${registro['idusuario']}' class='btn btn-primary btn-sm reiniciar' title='Reiniciar'><i class='fa-solid fa-user-gear'></i></a>
                  </td>
                </tr>
              `;

              $("#tabla-usuarios tbody").append(nuevaFila);

            })

            $('#tabla-usuarios').DataTable({
              language: {
                url: '//cdn.datatables.net/plug-ins/1.12.1/i18n/es-MX.json'
              }
            });
          }
        })
      }

      //Reiniciará los valores del formulario
      /* function reiniciarFormulario() {
        $("#formulario-usuarios")[0].reset();
      } */

      $("#tabla-usuarios tbody").on("click", ".promover", function() {
        idusuario = $(this).data("idusuario");
        nivelacceso = $(this).data("nivelacceso");

        if (nivelacceso == "A") {
          Swal.fire({
            title: "Error",
            text: "Este usuario no puede ser promovido",
            icon: "error",
            footer: "Ingeniería de Software con IA",
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
          });
        } else {
          Swal.fire({
            title: "Usuarios",
            text: "¿Esta seguro de promover a este usuario?",
            icon: "question",
            footer: "Ingeniería de Software con IA",
            confirmButtonText: "Aceptar",
            confirmButtonColor: "#38AD4D",
            showCancelButton: true,
            cancelButtonText: "Cancelar",
            cancelButtonColor: "#D3280A"
          }).then(result => {
            if (result.isConfirmed) {
              $.ajax({
                url: '../controllers/usuario.controller.php',
                type: 'GET',
                data: {
                  'operacion': 'promoverUsuario',
                  'idusuario': idusuario
                },
                success: function(result) {
                  if (result == "") {
                    Swal.fire({
                      title: "Promovido correctamente",
                      text: "Usuario promovido con éxito",
                      icon: "success",
                      toast: true,
                      position: 'bottom-end',
                      showConfirmButton: false,
                      timer: 2000,
                      timerProgressBar: true
                    });
                    idusuario = ``;
                    mostrarUsuarios();
                  }
                }
              });

            }
          })
        }
      });

      $("#tabla-usuarios tbody").on("click", ".degradar", function() {
        idusuario = $(this).data("idusuario");
        nivelacceso = $(this).data("nivelacceso");

        if (nivelacceso == "I") {
          Swal.fire({
            title: "Error",
            text: "Este usuario no puede ser degradado",
            icon: "error",
            footer: "Ingeniería de Software con IA",
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
          });
        } else {
          Swal.fire({
            title: "Usuarios",
            text: "¿Está seguro de degradar a este usuario?",
            icon: "question",
            footer: "Ingeniería de Software con IA",
            confirmButtonText: "Aceptar",
            confirmButtonColor: "#38AD4D",
            showCancelButton: true,
            cancelButtonText: "Cancelar",
            cancelButtonColor: "#D3280A"
          }).then(result => {
            if (result.isConfirmed) {
              $.ajax({
                url: '../controllers/usuario.controller.php',
                type: 'GET',
                data: {
                  'operacion': 'degradarUsuario',
                  'idusuario': idusuario
                },
                success: function(result) {
                  if (result == "") {

                    Swal.fire({
                      title: "Degradado correctamente",
                      text: "Usuario degradado con éxito",
                      icon: "success",
                      toast: true,
                      position: 'bottom-end',
                      showConfirmButton: false,
                      timer: 2000,
                      timerProgressBar: true
                    });

                    idusuario = '';
                    mostrarUsuarios();
                  }
                }
              });
            }
          });
        }
      });

      $("#tabla-usuarios tbody").on("click", ".inhabilitar", function() {
        idusuario = $(this).data("idusuario");

        Swal.fire({
          title: "Inhabilitar",
          text: "¿Esta seguro de inhabilitar a este usuario?",
          icon: "question",
          footer: "Ingeniería de Software con IA",
          confirmButtonText: "Aceptar",
          confirmButtonColor: "#38AD4D",
          showCancelButton: true,
          cancelButtonText: "Cancelar",
          cancelButtonColor: "#D3280A"
        }).then(result => {
          if (result.isConfirmed) {
            $.ajax({
              url: '../controllers/usuario.controller.php',
              type: 'GET',
              data: {
                'operacion': 'inhabilitarUsuario',
                'idusuario': idusuario
              },
              success: function(result) {
                if (result == "") {

                  Swal.fire({
                    title: "Inhabilitado correctamente",
                    text: "Usuario inhabilitado con éxito",
                    icon: "success",
                    toast: true,
                    position: 'bottom-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                  });

                  idusuario = '';
                  mostrarUsuarios();
                }
              }
            });
          }
        });
      });

      $("#tabla-usuarios tbody").on("click", ".reiniciar", function() {
        $("#modal-reiniciar").modal("show");
        idusuario = $(this).data("idusuario");

        $("#guardar-clave").on("click", function() {
          let clave1 = $("#clave1").val();
          let clave2 = $("#clave2").val();

          if (clave1 != clave2) {
            alertarToast("Ha sucecido un error", "Las claves no coinciden", "error");
          } else {
            $.ajax({
              url: '../controllers/usuario.controller.php',
              type: 'GET',
              data: {
                'operacion': 'reiniciarUsuario',
                'idusuario': idusuario,
                'claveacceso': clave1
              },
              success: function(result) {
                $("#formulario-reiniciar")[0].reset();
                $("#modal-reiniciar").modal("hide");
                alertarToast("Actualizado", "Contraseña actualizada correctamente", "success")
              }
            });
          }
        });
      });

      mostrarUsuarios();
    });
  </script>

</body>

</html>