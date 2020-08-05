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
          <h1 class="m-0 text-dark"><i class="fas fa-money-check-alt"></i> Facturas </h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
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
            <button class="btn btn-success btnCrearFactura" data-toggle="tooltip" title="Crear Factura"><i class="fas fa-plus"></i></button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table id="tabla" class="table table-bordered table-hover table-sm w-100">
            <thead class="thead-light">
              <tr>
                <th scope="col">Id</th>
                <th scope="col">Nombre</th>
                <th scope="col">Precio</th>
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
  <div class="modal fade" id="modalCrearfactura" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="tituloModalCrear">titulo</h5>
        </div>

        <h5 class="text-center mt-1"> Datos Cliente</h5>
        <form id="formClientes">
          <input type="hidden" name="accion" value="crearCliente">
          <input type="hidden" name="id" value="">
          <div class="modal-body">
            <label for="telefono">Telefono <span class="text-danger">*</span></label>
            <div class="input-group form-group">
              <input type="text" name="telefono" class="form-control" placeholder="Escriba el telefono"  aria-describedby="button-search">
              <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" id="btnBuscar">Buscar</button>
              </div>
            </div>
            <div class="form-group">
              <label for="nombre">Nombre<span class="text-danger">*</span></label>
              <input type="text" name="nombre" class="form-control" placeholder="Escriba el nombre" required autocomplete="off">
            </div>
            <div class="form-group">
              <label for="direccion">Dirección <span class="text-danger">*</span></label>
              <textarea class="form-control" required name="direccion" rows="3" placeholder="Escriba una dirección"></textarea>
            </div>
          </div>
          
        
        </form>

        <form id="formCrearFactura" autocomplete="off">
          <input type="hidden" name="accion" value="crearFactura">
          <input type="hidden" name="id" value="">
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
            <button id="btnCrearFactura" type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Crear</button>
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
    $('.btnCrearFactura').on("click", function(){
      $("#formCrearFactura")[0].reset();
      $("#formCrearFactura :input[name='accion']").val('crearFactura');
      $("#tituloModalCrear").html(`<i class="fas fa-plus"></i> Crear Factura`);
      $('#btnCrearFactura').html(`<i class="fas fa-paper-plane"></i> Crear`);
      $("#modalCrearfactura").modal("show");
    });


    $("#btnBuscar").on("click", ()=> {
      buscarCliente();
    })

    //Editar
    $(document).on("click", ".btnEditarFactura", function(){
      let datos = $(this).data("datos");
      $("#tituloModalCrear").html(`<i class="fas fa-edit"></i> Editar Factura | ${datos['nombre']}`);
      $("#formCrearFactura :input").removeClass("is-valid");
      $("#formCrearFactura :input").removeClass("is-invalid");
      $("#formCrearFactura :input[name='id']").val(datos["id"]);
      $("#formCrearFactura :input[name='nombre']").val(datos["nombre"]);
      $("#formCrearFactura :input[name='precio']").val(datos["precio"]);
      $("#formCrearFactura :input[name='accion']").val('editarFactura');
      $('#btnCrearFactura').html(`<i class="fas fa-paper-plane"></i> Editar`);
      $("#modalCrearfactura").modal("show");
    });


    //Cambio de estado
    $("#selectEstado").change(function () {
      $('#tabla').dataTable().fnDestroy();
      lista();
    });

    $("#formCrearFactura").submit(function(event){
      estado = $("#formCrearFactura :input[name='accion']").val() == 'crearFactura' ? 1 : 2;
      event.preventDefault();
      if($("#formCrearFactura").valid()){
        $.ajax({
          type: "POST",
          url: "acciones",
          cache: false,
          contentType: false,
          dataType: 'json',
          processData: false,
          data: new FormData(this),
          beforeSend: function(){
            $('#formCrearFactura :input').attr("disabled", true);
            //Desabilitamos el botón
            $('#btnCrearFactura').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ${estado == 1 ? 'Creando' : 'Editando'}...`);
            $("#btnCrearFactura").attr("disabled" , true);
          },
          success: function(data){
            if (data.success) {
              $("#tabla").DataTable().ajax.reload();
              $("#formCrearFactura")[0].reset();
              $("#formCrearFactura :input").removeClass("is-valid");
              $("#formCrearFactura :input").removeClass("is-invalid");
              $("#modalCrearfactura").modal("hide");
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
            $('#formCrearFactura :input').attr("disabled", false);
            $('#btnCrearFactura').html(`<i class="fas fa-paper-plane"></i> ${estado == 1 ? 'Crear' : 'Editar'}`);
            $("#btnCrearFactura").attr("disabled", false);
          },
          complete: function(){
            //Habilitamos el botón
            $('#formCrearFactura :input').attr("disabled", false);
            $('#btnCrearFactura').html(`<i class="fas fa-paper-plane"></i> ${estado == 1 ? 'Crear' : 'Editar'}`);
            $("#btnCrearFactura").attr("disabled", false);
          }
        });
      }
    });

    lista();
  });

  function buscarCliente(){
    $.ajax({
      url: 'acciones',
      type: 'POST',
      dataType: 'json',
      data: {
        accion: "buscarCliente", 
        telefono: $("#formClientes :input[name='telefono']").val()
      },
      success: function(data){
        if(data.success){
          $("#formClientes :input[name='telefono']").val();
          $("#formClientes :input[name='nombre']").val(data.msj[0].nombre);
          $("#formClientes :input[name='direccion']").val(data.msj[0].direccion);
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
        { data: "precio" },
        {
          "render": function (nTd, sData, oData, iRow, iCol) {
            return `<div class="d-flex justify-content-center">
                      <button type="button" class="btn btn-primary btn-sm mx-1 btnEditarFactura" data-toggle="tooltip" title="Editar" data-datos='${JSON.stringify(oData)}'><i class="fas fa-edit"></i></button>
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
      title: `¿Estas seguro de ${datos['estado'] == 1 ? 'inhabilitar' : 'habilitar'} la factura ${datos['nombre']} ?`,
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
                title: `Se ha ${datos['estado'] == 1 ? 'inhabilitado' : 'habilitado'} la factura ${datos['nombre']}`,
                showConfirmButton: false,
                timer: 5000
              });
            }else{
              Swal.fire({
                icon: 'warning',
                html: `Error al ${datos['estado'] == 1 ? 'inhabilitar' : 'habilitar'} la factura ${datos['nombre']}`
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