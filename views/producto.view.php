<?php
session_start();

//Si no existe la sesión
if (!isset($_SESSION['login']) || $_SESSION['login'] == false) {
  header("Location:../index.php");
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Productos</title>
  <!--  Boostrap 4.6 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
  <!-- Table -->
  <link rel="stylesheet" href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
  <!-- DataTable Responsive -->
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css">
  <!-- Estilos CSS -->
  <link rel="stylesheet" href="css/estilos2.css">
</head>

<body>
  <header>
    <a class="btn btn-light btn-sm" href="../controllers/usuario.controller.php?operacion=logout"><i class="fa-solid fa-power-off"></i> Cerrar Sesión</a>
  </header>

  <main>
    <section>
      <article>
        <h2>Módulo de productos</h2>
        <p><?= $_SESSION['apellidos'] ?> <?= $_SESSION['nombres'] ?></p>
        <button class="btn btn-primary btn-sm" type="button" data-toggle="modal" data-target="#modal-productos" id="mostrar-modal-registro"><i class="fa-solid fa-feather"></i> Registrar un producto</button>
      </article>

      <article>
        <table class="table display responsive nowrap" id="tabla-productos">
          <thead class="table-primary">
            <tr>
              <th>#</th>
              <th>Clasificacion</th>
              <th>Marca</th>
              <th>Descripcion</th>
              <th>Nuevo</th>
              <th>Serie</th>
              <th>Precio</th>
              <th>Comandos</th>
            </tr>
          </thead>

          <tbody>
          </tbody>
        </table>
      </article>
    </section>
  </main>

  <!-- Modal -->
  <div class="modal fade" data-backdrop="static" data-keyboard="false" id="modal-productos" tabindex="-1" aria-labelledby="titulo-modal-productos" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary text-light">
          <h5 class="modal-title" id="titulo-modal-productos">Registro de productos</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span class="text-light" aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">

          <!-- Formulario de registro de productos -->
          <form action="" id="formulario-productos" autocomplete="off">

            <div class="row">
              <div class="form-group col-md-6">
                <label for="clasificaciones">Clasificaciones:</label>
                <select name="clasificaciones" id="clasificaciones" class="form-control form-control-sm">
                  <option value="">Seleccione</option>
                </select>
              </div>

              <div class="form-group col-md-6">
                <label for="marcas">Marcas:</label>
                <select name="marcas" id="marcas" class="form-control form-control-sm">
                  <option value="">Seleccione</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label for="descripcion">Descripcion:</label>
              <input type="text" class="form-control form-control-sm" id="descripcion">
            </div>

            <div class="form-group">
              <label for="numeroserie">Número de serie:</label>
              <input type="text" class="form-control form-control-sm" id="numeroserie" placeholder="Campo opcional">
            </div>

            <!-- Todos los campos compartiran la misma fila -->
            <div class="row">
              <div class="form-group col-md-6">
                <label for="esnuevo">Producto nuevo:</label>
                <select name="esnuevo" id="esnuevo" class="form-control form-control-sm">
                  <option value="S">Si</option>
                  <option value="N">No</option>
                </select>
              </div>

              <div class="form-group col-md-6">
                <label for="precio">Precio:</label>
                <input type="number" class="form-control form-control-sm" id="precio" placeholder="S/.">
              </div>
            </div>
            <!-- Fin del formulario -->
          </form>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" id="cancelar-modal" data-dismiss="modal"><i class="fa-solid fa-thumbs-down"></i> Cancelar</button>
          <button type="button" class="btn btn-success btn-sm" id="guardar-producto"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
        </div>
      </div>
    </div>
  </div>

  <footer>
    &copy; Derechos Reservados
  </footer>

  <!-- Libreria jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

  <!-- Boostrap 4.6 -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>

  <!-- Datatable -->
  <script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

  <!-- DataTable Responsive -->
  <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>

  <!-- Font Awesome -->
  <script src="https://kit.fontawesome.com/7ac08d8e48.js" crossorigin="anonymous"></script>

  <!-- Funciones definidas -->
  <script>
    $(document).ready(function() {

      var idproducto = 0;
      var datosNuevos = true;

      var datos = {
        'operacion': "",
        'idproducto': "",
        'idclasificacion': "",
        'idmarca': "",
        'descripcion': "",
        'esnuevo': "",
        'numeroserie': "",
        'precio': 0
      };

      function mostrarProductos() {
        $.ajax({
          url: '../controllers/producto.controller.php',
          type: 'GET',
          data: 'operacion=listarProductos',
          success: function(result) {

            let numeroSerie = '';
            let registros = JSON.parse(result);

            let tabla = $("#tabla-productos").DataTable();
            tabla.destroy();

            $("#tabla-productos tbody").html("");

            registros.forEach(registro => {

              //Operador ternario
              numeroSerie = registro['numeroserie'] == null ? '' : registro['numeroserie'];

              let nuevaFila = `
                <tr>
                <td>${registro['idproducto']}</td>
                <td>${registro['clasificacion']}</td>
                <td>${registro['marca']}</td>
                <td>${registro['descripcion']}</td>
                <td>${registro['esnuevo']}</td>
                <td>${numeroSerie}</td>
                <td>${registro['precio']}</td>
                <td>
                  <a href='#' data-idproducto='${registro['idproducto']}' class='btn btn-danger btn-sm eliminar'><i class='fa-solid fa-trash'></i></a>
                  <a href='#' data-idproducto='${registro['idproducto']}' class='btn btn-info btn-sm editar'><i class='fa-solid fa-pen-to-square'></i></a>
                </td>
                </tr>
                `;
              $("#tabla-productos tbody").append(nuevaFila);

            })
            $('#tabla-productos').DataTable({
              language: {
                url: '//cdn.datatables.net/plug-ins/1.12.1/i18n/es-MX.json'
              }
            });
          }
        })
      }

      function listarClasificaciones() {
        $.ajax({
          url: '../controllers/clasificacion.controller.php',
          type: 'GET',
          data: 'operacion=listarClasificaciones',
          success: function(result) {
            let registros = JSON.parse(result);
            let elementosLista = ``;

            if (registros.length > 0) {

              elementosLista = `<option>Seleccione</option>`;

              registros.forEach(registro => {
                elementosLista += `<option value=${registro['idclasificacion']}>${registro['clasificacion']}</option>`;
              })
            } else {
              elementosLista = `<option>No hay datos asignados</option>`
            }
            $("#clasificaciones").html(elementosLista);
          }
        })
      }

      function listarMarcas() {
        $.ajax({
          url: '../controllers/marca.controller.php',
          type: 'GET',
          data: 'operacion=listarMarcas',
          success: function(result) {
            let registros = JSON.parse(result);
            let elementosLista = ``;

            if (registros.length > 0) {

              elementosLista = `<option>Seleccione</option>`;

              registros.forEach(registro => {
                elementosLista += `<option value=${registro['idmarca']}>${registro['marca']}</option>`;
              })
            } else {
              elementosLista = `<option>No hay datos asignados</option>`
            }
            $("#marcas").html(elementosLista);
          }
        })
      }

      function reiniciarFormulario() {
        $("#formulario-productos")[0].reset();
      }

      function registrarProducto() {

        /*
        Cuando se le asigna {} o [] a un objeto, se está REDEFINIENDO
        se le está volviendo a construir / sobreescribiendo
        */

        //El objeto datos ha sido creado en ámbito GLOBAL
        datos['idclasificacion'] = $("#clasificaciones").val();
        datos['idmarca'] = $("#marcas").val();
        datos['descripcion'] = $("#descripcion").val();
        datos['esnuevo'] = $("#esnuevo").val();
        datos['numeroserie'] = $("#numeroserie").val();
        datos['precio'] = $("#precio").val();

        //Verificar el proceso
        if (datosNuevos) {
          datos['operacion'] = "registrarProducto";
        } else {
          datos['operacion'] = "actualizarProducto";
          datos['idproducto'] = idproducto;
        }

        console.log(datos);

        if (confirm("¿Estas seguro de guardar el registro?")) {
          $.ajax({
            url: '../controllers/producto.controller.php',
            type: 'GET',
            data: datos,
            success: function(result) {

              console.log(result);
              alert("Proceso terminado correctamente");
              mostrarProductos();

              $("#modal-productos").modal("hide");

            }
          });
        }
      }

      $("#tabla-productos tbody").on("click", ".eliminar", function() {
        idproducto = $(this).data("idproducto");

        if (confirm("¿Está seguro de eliminar el registro?")) {
          $.ajax({
            url: '../controllers/producto.controller.php',
            type: 'GET',
            data: {
              'operacion': 'eliminarProducto',
              'idproducto': idproducto
            },
            success: function(result) {
              if (result == "") {
                idproducto = '';
                mostrarProductos();
              }
            }
          });
        }
      });

      $("#tabla-productos tbody").on("click", ".editar", function() {
        idproducto = $(this).data("idproducto");

        //Enviando parámetros a DATA
        //data: 'operacion=getProducto&idproducto=' + idproducto,

        $.ajax({
          url: '../controllers/producto.controller.php',
          type: 'GET',
          dataType: 'JSON',
          data: {
            'operacion': 'getProducto',
            'idproducto': idproducto
          },
          success: function(result) {
            $("#clasificaciones").val(result['idclasificacion']);
            $("#marcas").val(result['idmarca']);
            $("#descripcion").val(result['descripcion']);
            $("#numeroserie").val(result['numeroserie']);
            $("#esnuevo").val(result['esnuevo']);
            $("#precio").val(result['precio']);

            //Cambiando configuración modal
            $("#titulo-modal-productos").html("Actualizar datos");
            $(".modal-header").removeClass("bg-primary");
            $(".modal-header").addClass("bg-info");

            $("#modal-productos").modal("show");
            datosNuevos = false;
            console.log(result);
          }
        });
      });

      function abrirModalRegistro() {
        datosNuevos = true;

        $(".modal-header").removeClass("bg-info");
        $(".modal-header").addClass("bg-primary");
        $("#titulo-modal-productos").html("Registrar producto");
        $("#formulario-productos")[0].reset();
      }

      $("#mostrar-modal-registro").click(abrirModalRegistro);
      $("#guardar-producto").click(registrarProducto);
      $("#cancelar-modal").click(reiniciarFormulario);

      mostrarProductos();
      listarClasificaciones();
      listarMarcas();

    });
  </script>
</body>

</html>