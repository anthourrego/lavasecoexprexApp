<?php  
	$max_salida=10; // Previene algun posible ciclo infinito limitando a 10 los ../
  $ruta_raiz=$ruta="";
  while($max_salida>0){
    if(@is_file($ruta.".htaccess")){
      $ruta_raiz=$ruta; //Preserva la ruta superior encontrada
      break;
    }
    $ruta.="../";
    $max_salida--;
  }

  include_once($ruta_raiz . 'clases/librerias.php');
  include_once($ruta_raiz . 'clases/sessionActiva.php');
  include_once($ruta_raiz . 'clases/Permisos.php');

  $session = new Session();
  $lib = new Libreria;
  $permisos = new Permisos();

  $usuario = $session->get("usuario");

  if ($permisos->validarPermiso($usuario['id'], 'productos') == 0) {
    header('Location: ' . $ruta_raiz . 'modulos/');
  }

?>

<!DOCTYPE html>
<html>
<head>
  <title></title>
  <?php  
    echo $lib->jquery();
    echo $lib->adminLTE();
    echo $lib->bootstrap();
    echo $lib->fontAwesome();
    echo $lib->sweetAlert2();
    echo $lib->jqueryValidate();
    echo $lib->datatables();
    echo $lib->bootstrapTreeView();
    echo $lib->proyecto();
  ?>
</head>
<body>

  <!-- Content Header (Page header) -->
  <div div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-12">
          <h1 class="m-0 text-dark">Productos</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-header d-flex justify-content-end">
          <button class="btn btn-success btnCrearProducto" data-toggle="tooltip" title="Crear producto"><i class="fas fa-plus"></i></button>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table id="tablaProductos" class="table table-bordered table-hover table-sm w-100">
            <thead class="thead-light">
              <tr>
                <th scope="col">Id</th>
                <th scope="col">Nombre</th>
                <th scope="col">Precio</th>
                <th scope="col">Fecha Creación</th>
                <th scope="col">Creador</th>
                <th scope="col">Acciones</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->

  <!-- Modal Crear Producto -->
  <div class="modal fade" id="modalCrearProducto" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-plus"></i> Crear producto</h5>
        </div>
        <form id="formCrearProducto" autocomplete="off">
          <input type="hidden" name="accion" value="crearProducto">
          <div class="modal-body">
            <div class="form-group">
              <label for="nombre">Nombre <span class="text-danger">*</span></label>
              <input type="text" name="nombre" class="form-control" placeholder="Escriba el nombre" required autocomplete="off">
            </div>
            <div class="form-group">
              <label for="precio">Precio <span class="text-danger">*</span></label>
              <input type="number" name="precio" class="form-control" placeholder="Escriba el precio" onKeyPress="return soloNumeros(event)" required autocomplete="off">
            </div>
          </div>
          <div class="modal-footer d-flex justify-content-between">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cerrar</button>
            <button id="btnCrearProducto" type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Crear</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Editar Producto -->
  <div class="modal fade" id="modalEditarProducto" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-edit"></i> Editar Producto | <span id="modalEditarProductoTitulo">N/A</span></h5>
        </div>
        <form id="formEditarProducto" autocomplete="off">
          <input type="hidden" name="accion" value="editarProducto">
          <input type="hidden" name="id" value="0">
          <div class="modal-body">
            <div class="form-group">
              <label for="nombre">Nombre <span class="text-danger">*</span></label>
              <input type="text" name="nombre" class="form-control" required autocomplete="off">
            </div>
            <div class="form-group">
              <label for="precio">Precio <span class="text-danger">*</span></label>
              <input type="text" name="precio" class="form-control" required autocomplete="off">
            </div>
          </div>
          <div class="modal-footer d-flex justify-content-between">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cerrar</button>
            <button id="btnEditarProducto" type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Editar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

</body>
<?php 
  echo $lib->cambioPantalla();
?>
<script>
  $(function(){
    $('[data-toggle="tooltip"]').tooltip();

    //Se abre la modal para crear usuarios
    $('.btnCrearProducto').on("click", function(){
      $("#modalCrearProducto").modal("show");
    });

    $("#formCrearProducto").submit(function(event){
      event.preventDefault();
      if($("#formCrearProducto").valid()){
        $.ajax({
          type: "POST",
          url: "acciones",
          cache: false,
          contentType: false,
          dataType: 'json',
          processData: false,
          data: new FormData(this),
          beforeSend: function(){
            $('#formCrearProducto :input').attr("disabled", true);
            //Desabilitamos el botón
            $('#btnCrearProducto').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Creando...`);
            $("#btnCrearProducto").attr("disabled" , true);
          },
          success: function(data){
            if (data.success) {
              $("#tablaProductos").DataTable().ajax.reload();
              $("#formCrearProducto")[0].reset();
              $("#formCrearProducto :input").removeClass("is-valid");
              $("#formCrearProducto :input").removeClass("is-invalid");
              $("#modalCrearProducto").modal("hide");
              Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: 'success',
                title: data.msj,
                showConfirmButton: false,
                timer: 5000
              });
            }else{
              Swal.fire({
                icon: 'error',
                html: data.msj
              })
            }
          },
          error: function(){
            //Habilitamos el botón
            Swal.fire({
              icon: 'error',
              html: 'Error al registrar.'
            });
            //Habilitamos el botón
            $('#formCrearProducto :input').attr("disabled", false);
            $('#btnCrearProducto').html(`<i class="fas fa-paper-plane"></i> Crear`);
            $("#btnCrearProducto").attr("disabled", false);
          },
          complete: function(){
            //Habilitamos el botón
            $('#formCrearProducto :input').attr("disabled", false);
            $('#btnCrearProducto').html(`<i class="fas fa-paper-plane"></i> Crear`);
            $("#btnCrearProducto").attr("disabled", false);
          }
        });
      }
    });

    //Editar Usuario
    $(document).on("click", ".btnEditarProducto", function(){
      let id = $(this).data("id");
      let nombre = $(this).data("nombre");
      let precio = $(this).data("precio");
      $("#modalEditarProductoTitulo").html(nombre);
      $("#formEditarProducto :input").removeClass("is-valid");
      $("#formEditarProducto :input").removeClass("is-invalid");
      $("#formEditarProducto :input[name='id']").val(id);
      $("#formEditarProducto :input[name='nombre']").val(nombre);
      $("#formEditarProducto :input[name='precio']").val(precio);
      $("#modalEditarProducto").modal("show");
    });

    //Formulario de editar producto
    $("#formEditarProducto").submit(function(event){
      event.preventDefault();
      if($("#formEditarProducto").valid()){
        $.ajax({
          type: "POST",
          url: "acciones",
          cache: false,
          contentType: false,
          dataType: 'json',
          processData: false,
          data: new FormData(this),
          beforeSend: function(){
            $('#formEditarProducto :input').attr("disabled", true);
            //Desabilitamos el botón
            $('#btnEditarProducto').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Editando...`);
            $("#btnEditarProducto").attr("disabled" , true);
          },
          success: function(data){
            if (data.success) {
              $("#tablaProductos").DataTable().ajax.reload();
              $("#formEditarProducto")[0].reset();
              $("#formEditarProducto :input").removeClass("is-valid");
              $("#formEditarProducto :input").removeClass("is-invalid");
              $("#modalEditarProducto").modal("hide");
              Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: 'success',
                title: data.msj,
                showConfirmButton: false,
                timer: 5000
              });
            }else{
              Swal.fire({
                icon: 'warning',
                html: data.msj
              })
            }
          },
          error: function(){
            //Habilitamos el botón
            Swal.fire({
              icon: 'error',
              html: 'Error al enviar los datos.'
            });
            //Habilitamos el botón
            $('#formEditarProducto :input').attr("disabled", false);
            $('#btnEditarProducto').html(`<i class="fas fa-paper-plane"></i> Editar`);
            $("#btnEditarProducto").attr("disabled", false);
          },
          complete: function(){
            //Habilitamos el botón
            $('#formEditarProducto :input').attr("disabled", false);
            $('#btnEditarProducto').html(`<i class="fas fa-paper-plane"></i> Editar`);
            $("#btnEditarProducto").attr("disabled", false);
          }
        });
      }
    });


    $("#tablaProductos").DataTable({
      stateSave: true,
      responsive: true,
      processing: true,
      serverSide: true,
      pageLength: 25,
      language: {
        url: "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
      },
      ajax: {
          url: "acciones",
          type: "GET",
          dataType: "json",
          data: {
            accion: 'listaProductos'
          },
          complete: function(){
            $('[data-toggle="tooltip"]').tooltip('hide');
            $('[data-toggle="tooltip"]').tooltip();
            cerrarCargando();
          }
      },
      columns: [
          {
            data: "id"
          },
          {
            data: "nombre"
          },
          {
            data: "precio"
          },
          {
            data: "fecha_creacion"
          },
          {
            data: "creador"
          },
          {
            "render": function (nTd, sData, oData, iRow, iCol) {
              return `<div class="d-flex justify-content-center">
                        <button type="button" class="btn btn-primary btn-sm mx-1 btnEditarProducto" data-toggle="tooltip" title="Editar" data-id="${oData.id}" data-nombre="${oData.nombre}" data-precio="${oData.precio}"><i class="fas fa-edit"></i></button>
                        <button type="button" class="btn btn-danger btn-sm mx-1" onClick="elminarProducto(${oData.id}, '${oData.nombre}')"><i class="fas fa-user-minus" data-toggle="tooltip" title="Eliminar"></i></button>
                      </div>`;
            }
          }
      ],
      columnDefs: [
        {
          className: "dt-center",
          targets: "_all"
        },
        {
          targets: [0],
          visible: false
        }
      ],
      lengthChange: true,
      order: [
        [0, "asc"]
      ], //Ordenar (columna,orden)
    });
  });

  function elminarProducto(id, nombre){
    Swal.fire({
      title: "¿Estas seguro de eliminar el producto " + nombre + "?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: '<i class="far fa-trash-alt"></i> Si',
      cancelButtonText: '<i class="fa fa-times"></i> No'
    }).then((result) => {
      if (result.value) {
        $.ajax({
          url: 'acciones',
          type: 'POST',
          dataType: 'json',
          data: {
            accion: "inHabilitarProducto", 
            id: id,
            nombre: nombre
          },
          success: function(data){
            if (data == 1) {
              $("#tablaProductos").DataTable().ajax.reload();
              Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: 'success',
                title: "Se ha eliminado el producto " + nombre,
                showConfirmButton: false,
                timer: 5000
              });
            }else{
              Swal.fire({
                icon: 'warning',
                html: "Error al eliminar el producto " + nombre
              })
            }
          },
          error: function(){
            Swal.fire({
              icon: 'error',
              html: 'No se han enviado los datos'
            })
          }
        });
      }
    });
  }
</script>
</html>