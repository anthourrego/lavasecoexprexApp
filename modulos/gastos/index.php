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

  if ($permisos->validarPermiso($usuario['id'], 'gastos') == 0) {
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
    <div class="container">
      <div class="row mb-2">
        <div class="col-12">
          <h1 class="m-0 text-dark"><i class="fas fa-file-invoice-dollar"></i> Gastos tipo</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container">
      <div class="card">
        <div class="card-header">
          <div class="d-flex justify-content-between">
            <div class="input-group w-md-25 w-75">
              <select class="custom-select" id="selectEstado" name="status">
                <option value="1" selected>Activo</option>
                <option value="0">Inactivo</option>
              </select>
              <div class="input-group-append">
                <label class="input-group-text" for="inputGroupSelect02">Estado</label>
              </div>
            </div>
            <button class="btn btn-success btnCrear" data-toggle="tooltip" title="Crear item gasto"><i class="fas fa-plus"></i></button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table id="tabla" class="table table-bordered table-hover table-sm w-100">
            <thead class="thead-light">
              <tr>
                <th scope="col">Id</th>
                <th scope="col">Nombre</th>
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
  <div class="modal fade" id="modalCrear" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="tituloModalCrear">titulo</h5>
        </div>
        <form id="formCrear" autocomplete="off">
          <input type="hidden" name="accion" value="crear">
          <input type="hidden" name="id" value="">
          <div class="modal-body">
            <div class="form-group">
              <label for="nombre">Nombre <span class="text-danger">*</span></label>
              <input type="text" name="nombre" class="form-control" placeholder="Escriba el nombre" required autocomplete="off">
            </div>
          </div>
          <div class="modal-footer d-flex justify-content-between">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cerrar</button>
            <button id="btnCrear" type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Crear</button>
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

    //Crear
    $('.btnCrear').on("click", function(){
      $("#formCrear")[0].reset();
      $("#formCrear :input[name='accion']").val('crear');
      $("#tituloModalCrear").html(`<i class="fas fa-plus"></i> Crear item gasto`);
      $('#btnCrear').html(`<i class="fas fa-paper-plane"></i> Crear`);
      $("#modalCrear").modal("show");
    });

    //Editar
    $(document).on("click", ".btnEditar", function(){
      let datos = $(this).data("datos");
      $("#tituloModalCrear").html(`<i class="fas fa-edit"></i> Editar item gasto | ${datos['nombre']}`);
      $("#formCrear :input").removeClass("is-valid");
      $("#formCrear :input").removeClass("is-invalid");
      $("#formCrear :input[name='id']").val(datos["id"]);
      $("#formCrear :input[name='nombre']").val(datos["nombre"]);
      $("#formCrear :input[name='accion']").val('editar');
      $('#btnCrear').html(`<i class="fas fa-paper-plane"></i> Editar`);
      $("#modalCrear").modal("show");
    });


    //Cambio de estado
    $("#selectEstado").change(function () {
      $('#tabla').dataTable().fnDestroy();
      lista();
    });

    $("#formCrear").submit(function(event){
      estado = $("#formCrear :input[name='accion']").val() == 'crear' ? 1 : 2;
      event.preventDefault();
      if($("#formCrear").valid()){
        $.ajax({
          type: "POST",
          url: "acciones",
          cache: false,
          contentType: false,
          dataType: 'json',
          processData: false,
          data: new FormData(this),
          beforeSend: function(){
            $('#formCrear :input').attr("disabled", true);
            //Desabilitamos el botón
            $('#btnCrear').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ${estado == 1 ? 'Creando' : 'Editando'}...`);
            $("#btnCrear").attr("disabled" , true);
          },
          success: function(data){
            if (data.success) {
              $("#tabla").DataTable().ajax.reload();
              $("#formCrear")[0].reset();
              $("#formCrear :input").removeClass("is-valid");
              $("#formCrear :input").removeClass("is-invalid");
              $("#modalCrear").modal("hide");
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
              html: 'Error al registrar.'
            });
            //Habilitamos el botón
            $('#formCrear :input').attr("disabled", false);
            $('#btnCrear').html(`<i class="fas fa-paper-plane"></i> ${estado == 1 ? 'Crear' : 'Editar'}`);
            $("#btnCrear").attr("disabled", false);
          },
          complete: function(){
            //Habilitamos el botón
            $('#formCrear :input').attr("disabled", false);
            $('#btnCrear').html(`<i class="fas fa-paper-plane"></i> ${estado == 1 ? 'Crear' : 'Editar'}`);
            $("#btnCrear").attr("disabled", false);
          }
        });
      }
    });

    lista();
  });

  function lista(){

    var estado = $("#selectEstado").val();

    $("#tabla").DataTable({
      stateSave: true,
      responsive: true,
      processing: true,
      serverSide: true,
      pageLength: 25,
      language: {
        url: "<?php echo($ruta_raiz); ?>librerias/dataTables/Spanish.json"
      },
      ajax: {
        url: "acciones",
        type: "GET",
        dataType: "json",
        data: {
          accion: 'lista',
          estado: estado
        },
        complete: function(){
          $('[data-toggle="tooltip"]').tooltip('hide');
          $('[data-toggle="tooltip"]').tooltip();
          cerrarCargando();
        }
      },
      columns: [
        { data: "id" },
        { data: "nombre" },
        { data: "fecha_creacion" },
        { data: "creador" },
        {
          "render": function (nTd, sData, oData, iRow, iCol) {
            return `<div class="d-flex justify-content-center">
                      <button type="button" class="btn btn-primary btn-sm mx-1 btnEditar" data-toggle="tooltip" title="Editar" data-datos='${JSON.stringify(oData)}'><i class="fas fa-edit"></i></button>
                      <button type="button" class="btn ${estado == 1 ? 'btn-danger' : 'btn-success'} btn-sm mx-1" onClick='cambiarEstado(${JSON.stringify(oData)})' data-toggle="tooltip" title="${estado == 1 ? 'Inactivar' : 'Activar'}"><i class="fas ${estado == 1 ? 'fa-trash-alt' : 'fa-check'}"></i></button>
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
      lengthMenu: [
        [ 10, 25, 50, -1 ],
        [ '10', '25', '50', 'todos' ]
      ],
      order: [
        [0, "asc"]
      ], //Ordenar (columna,orden)
    });
  }

  function cambiarEstado(datos){
    Swal.fire({
      title: `¿Estas seguro de ${datos['estado'] == 1 ? 'inhabilitar' : 'habilitar'} el item ${datos['nombre']} ?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: `<i class="fas ${datos['estado'] == 1 ? 'fa-trash-alt' : 'fa-check'}"></i> Si`,
      cancelButtonText: '<i class="fa fa-times"></i> No'
    }).then((result) => {
      if (result.value) {
        $.ajax({
          url: 'acciones',
          type: 'POST',
          dataType: 'json',
          data: {
            accion: "cambiarEstado", 
            id: datos['id'],
            nombre: datos['nombre'],
            estado: datos['estado'],
          },
          success: function(data){
            if (data == 1) {
              $("#tabla").DataTable().ajax.reload();
              Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: 'success',
                title: `Se ha ${datos['estado'] == 1 ? 'inhabilitado' : 'habilitado'} el item ${datos['nombre']}`,
                showConfirmButton: false,
                timer: 5000
              });
            }else{
              Swal.fire({
                icon: 'warning',
                html: `Error al ${datos['estado'] == 1 ? 'inhabilitar' : 'habilitar'} el item ${datos['nombre']}`
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