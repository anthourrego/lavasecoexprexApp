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
    echo $lib->jqueryUI();
    echo $lib->moment();
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
                <th scope="col">Nº</th>
                <th scope="col">Nº</th>
                <th scope="col">Fecha Entrega</th>
                <th scope="col">Telefono Cliente</th>
                <th scope="col">Abono</th>
                <th scope="col">Total</th>
                <th scope="col">Acciones</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
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
          <input type="hidden" required name="id" value="">
          <div class="modal-body">
            <label for="telefono">Telefono <span class="text-danger">*</span></label>
            <div class="input-group form-group">
              <input type="number" name="telefono" required class="form-control" placeholder="Escriba el telefono"  aria-describedby="button-search" onblur="buscarCliente()">
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
            <div class="d-flex justify-content-end">
              <button type="button" class="btn btn-secondary m-1" data-dismiss="modal"><i class="fas fa-times"></i> Cerrar</button>
              <button id="btnCrearFactura" type="submit" class="btn btn-primary m-1"><i class="fas fa-paper-plane"></i> Crear</button>
            </div>
          </div>
        </form>


        <h5 class="text-center mt-1"> Datos Factura</h5>
        <div class="col-6">
          
        </div>
        <div class="modal-body" style="padding: 15px;">
          <form id="formFechaEntrega">
            <div class="form-label-group form-group col-md-6 mb-1">
              <label for="fechaEntrega">Fecha Entrega<span class="text-danger">*</span></label>
              <input type="text" id="fechaEntrega" name="fechaEntrega" class="form-control datepicker" placeholder="Fecha" autocomplete="off" required>
            </div>
          </form>

          
          <div class="col-md-12 column">
            
            <table class="table table-bordered table-hover" id="tabla_factura">
              <thead>
                <tr >
                  <th class="text-center">
                    Producto
                  </th>
                  <th class="text-center">
                    Servicio
                  </th>
                  <th class="text-center">
                    Cantidad
                  </th>
                  <th class="text-center">
                    Precio
                  </th>
                </tr>
              </thead>
              <tbody id="body_tabla">
                <tr id='addr0'>
                  <td>
                    <select required class="custom-select" required name='producto0' id="producto0">
                      <option value='0' disabled selected >Seleccione un opción</option>
                    </select>
                  </td>
                  <td>
                    <select required class="custom-select" required name='servicio0' id="servicio0">
                      <option value='0' disabled selected >Seleccione un opción</option>
                    </select>
                  </td>
                  <td>
                    <input type="text" required name='cantidad0' placeholder='Cantidad' class="form-control" id="cantidad0"/>
                  </td>
                  <td>
                  <input type="text" id="precio0" required name='precio0' placeholder='Precio' class="form-control" onblur="calcularTotal()" />
                  </td>
                </tr>
                <tr id='addr1'></tr>
              </tbody>
            </table>
            <div class="d-flex justify-content-end">
              <h5 class="m-1">
                Abono:
              </h5>
              <div class="form-group">
                <input type="text" name="abono" id="abono_input" onblur="calcularTotal()" class="form-control text-center form-control-sm" required autocomplete="off">
              </div>
            </div>
            <div class="d-flex justify-content-end">
              <h5 class="m-1">
                Total:
              </h5>
              <div class="form-group">
                <input type="text" name="total" id="total_input" class="form-control text-center form-control-sm" required autocomplete="off">
              </div>
            </div>
          </div>
          <button id="add_row" class="btn btn-secondary btn-lg btn-block">
            <i class="fas fa-plus"></i>
            Agregar Item
          </button>

          <button id='btnfacturar' class="btn btn-primary btn-lg btn-block">
            <i class="far fa-save"></i>
            Facturar
          </button>
        </div>
      </div>
    </div>
  </div>

</body>
<?php 
  echo $lib->cambioPantalla();
?>
<script>
  var cantidad_filas = 1;
  var datosClienteEditar = {};
  $(function(){
    $('[data-toggle="tooltip"]').tooltip();

    //$("#formClientes :input[name='nombre'], #formClientes :input[name='direccion']").attr('disabled', true);

    //Crear
    $('.btnCrearFactura').on("click", function(){
      $("#tituloModalCrear").html(`<i class="fas fa-plus"></i> Crear Factura`);
      $('#btnCrearFactura').html(`<i class="fas fa-paper-plane"></i> Crear`);
      $("#modalCrearfactura").modal("show");
    });

    $("#btnBuscar").on("click", ()=> {
      buscarCliente();
    })

    $(".datepicker").datepicker({ 
      dateFormat: "yy-mm-dd", 
      /* maxDate: "-18Y",
      changeMonth: true,
      changeYear: true */
    });

    //Editar
    $(document).on("click", ".btnEditarFactura", function(){
      let datos = $(this).data("datos");

      $.ajax({
        async: true,
        url: 'acciones',
        type: 'GET',
        dataType: 'json',
        data: {
          accion: "getById",
          id: datos['id']
        },
        success: function(data){
          if(data.success){
            console.log(datos);
            $("#tituloModalCrear").html(`<i class="fas fa-edit"></i> Editar Factura | ${datos['id']}`);
            // formulario fecha entrega 
            $("#formFechaEntrega :input").removeClass("is-valid");
            $("#formFechaEntrega :input").removeClass("is-invalid");
            $("#formFechaEntrega :input[name='fechaEntrega']").val(moment(datos["fecha_entrega"]).locale('es').format('YYYY[-]MM[-]DD'));

            // formulario cliente
            $("#formClientes :input").removeClass("is-valid");
            $("#formClientes :input").removeClass("is-invalid");
            $("#formClientes :input[name='id']").val(data.msj[0].id);
            $("#formClientes :input[name='telefono']").val(data.msj[0].telefono);
            $("#formClientes :input[name='nombre']").val(data.msj[0].nombre);
            $("#formClientes :input[name='direccion']").val(data.msj[0].direccion);

            $("#formCrearFactura :input[name='nombre']").val(datos["nombre"]);
            $("#formCrearFactura :input[name='precio']").val(datos["precio"]);
            $("#formCrearFactura :input[name='accion']").val('editarFactura');
            $('#btnCrearFactura').html(`<i class="fas fa-paper-plane"></i> Editar`);
            $("#modalCrearfactura").modal("show");

            renderTabla(datos['datos_tabla']);

          }
        },
        error: function(){
          Swal.fire({
            icon: 'error',
            html: 'No se han enviado los datos'
          })
        }
      });
    });

    //Cambio de estado
    $("#selectEstado").change(function () {
      $('#tabla').dataTable().fnDestroy();
      lista();
    });

    $("#btnfacturar").click(function(){
      console.log( $('#formFechaEntrega').valid() , $('#formClientes').valid())
      if( $('#formFechaEntrega').valid() && $('#formClientes').valid()){
        facturar();
      }
    
    });
    
    $("#add_row").click(function(){
      $('#addr'+cantidad_filas).html(`
        <td>
          <select class="custom-select" required name='producto${cantidad_filas}' id="producto${cantidad_filas}">
            <option value='0' disabled selected >Seleccione un opción</option>
          </select>
        </td>
        <td>
          <select class="custom-select" required name='servicio${cantidad_filas}' id="servicio${cantidad_filas}">
            <option value='0' disabled selected >Seleccione un opción</option>
          </select>
        </td>
        <td>
          <input required name='cantidad${cantidad_filas}' type='text' placeholder='Cantidad'  class='form-control' id='cantidad${cantidad_filas}' >
        </td>
        <td>
          <input required name='precio${cantidad_filas}'  id='precio${cantidad_filas}' type='text' placeholder='Precio'  class='form-control' onblur="calcularTotal()">
        </td>`
      );
      $('#tabla_factura').append('<tr id="addr'+(cantidad_filas+1)+'"></tr>');
      cantidad_filas++;
      calcularTotal();
      traerProductos();
      traerServicios();
    });

    $("#delete_row").click(function(){
      if(cantidad_filas>1){
      $("#addr"+(cantidad_filas-1)).html('');
      cantidad_filas--;
      }
    });
    traerProductos();
    traerServicios();
    lista();
  });

  function buscarCliente(){
    if($("#formClientes :input[name='telefono']").val()){
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
            $("#formClientes :input[name='id']").val(data.msj[0].id);
            $("#formClientes :input[name='telefono']").val();
            $("#formClientes :input[name='nombre']").val(data.msj[0].nombre);
            $("#formClientes :input[name='direccion']").val(data.msj[0].direccion);
            $("#formClientes :input[name='nombre'], #formClientes :input[name='direccion']").removeAttr("disabled")
          }else{
            Swal.fire({
              icon: 'info',
              html: 'No existe cliente, debe crearlo'
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
  }

  function getClientById(id){
    datosClienteEditar = {};
    
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
         /*  $('[data-toggle="tooltip"]').tooltip('hide');
          $('[data-toggle="tooltip"]').tooltip(); */
          cerrarCargando();
        }
    },
      columns: [
        { data: "id" },
        { data: "id" },
        { render: function (nTd, sData, oData, iRow, iCol) { 
          return moment(oData.fecha_entrega).locale('es').format('D [de] MMMM');
        }},
        { data: "telefono_cliente" },
        { data: "abono" },
        { data: "total" },
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
      title: `¿Estas seguro de ${datos['estado'] == 1 ? 'inhabilitar' : 'habilitar'} La factura ${datos['id']} ?`,
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
            estado: datos['estado'],
          },
          success: function(data){
            if (data == 1) {
              $("#tabla").DataTable().ajax.reload();
              Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: 'success',
                title: `Se ha ${datos['estado'] == 1 ? 'inhabilitado' : 'habilitado'} La factura ${datos['id']}`,
                showConfirmButton: false,
                timer: 5000
              });
            }else{
              Swal.fire({
                icon: 'warning',
                html: `Error al ${datos['estado'] == 1 ? 'inhabilitar' : 'habilitar'} la factura ${datos['id']}`
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

  function traerProductos(i=null){
    console.log(i);
    $.ajax({
          url: 'acciones',
          type: 'GET',
          dataType: 'json',
          data: {
            accion: "traerProductos", 
          },
          success: function(data){
            if(data.success){
              const cond = i ? i : cantidad_filas;
              for (let i = 0; i < data.msj.cantidad_registros; i++) {
                $(`#producto${cantidad_filas - 1}`).append(`
                  <option value="${data.msj[i].id}">${data.msj[i].nombre}</option>
                `);
              }
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

  function traerServicios(i=null){
    $.ajax({
          url: 'acciones',
          type: 'GET',
          dataType: 'json',
          data: {
            accion: "traerServicios", 
          },
          success: function(data){
            if(data.success){
              const cond = i ? i : cantidad_filas;
              for (let i = 0; i < data.msj.cantidad_registros; i++) {
                $(`#servicio${cantidad_filas - 1}`).append(`
                  <option value="${data.msj[i].id}">${data.msj[i].nombre}</option>
                `);
              }
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

  function calcularTotal(){
    let total = 0;
    for(let i = 0; i<= cantidad_filas;i++){
      if($("#precio"+i).val()){
        total+= parseInt($("#precio"+i).val());
      }
    }
    if($("#abono_input").val()){
      total -= parseInt($("#abono_input").val());
    }
    $("#total_input").val(total);
  }

  function tablaJson(){
   let arrayTmp = [];
    for(let i = 0; i< cantidad_filas ; i++){
      const obj = {}
      obj['producto'] = $("#producto"+i).val();
      obj['servicio'] = $("#servicio"+i).val();
      obj['cantidad'] = $("#cantidad"+i).val();
      obj['precio'] = $("#precio"+i).val();
      arrayTmp.push(obj);
    }
    return JSON.stringify(arrayTmp);
    //console.log(JSON.parse(JSON.stringify(arrayTmp)));
  }

  function facturar(){

    $.ajax({
        url: 'acciones',
        type: 'POST',
        dataType: 'json',
        data: {
          accion: 'facturar',
          datosTabla: tablaJson(),
          idCliente: $("#formClientes :input[name='id']").val(),
          total: $("#total_input").val(),
          abono: $("#abono_input").val(),
          fechaEntrega: $("#formFechaEntrega :input[name='fechaEntrega']").val()
        },
        beforeSend: function(){

        },
        success: function(data){
          if (data.success) {
            $("#formFechaEntrega :input").removeClass("is-valid");
            $("#formClientes :input").removeClass("is-valid");
            $("#formFechaEntrega :input").removeClass("is-invalid");
            $("#formFechaEntrega :input").removeClass("is-invalid");
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

        },
        complete: function(){

        }
    });
  }

  function renderTabla(datos){
    const array = JSON.parse(datos);
    $("#body_tabla").empty();
    for(let i = 0; i< array.length ; i++){

      let fila = `
      <td>
        <select class="custom-select" required name='producto${i}' id="producto${i}">
          <option value='0' disabled selected >Seleccione un opción</option>
        </select>
      </td>
      <td>
        <select class="custom-select" required name='servicio${i}' id="servicio${i}">
          <option value='0' disabled selected >Seleccione un opción</option>
        </select>
      </td>
      <td>
        <input required name='cantidad${i}' type='text' placeholder='Cantidad'  class='form-control' id='cantidad${i}' >
      </td>
      <td>
        <input required name='precio${i}'  id='precio${i}' type='text' placeholder='Precio'  class='form-control' onblur="calcularTotal()">
      </td>`;
      
      $('#tabla_factura').append('<tr id="addr'+(i)+'"></tr>');
      $('#addr'+i).html(fila);
      // seteo valores
      console.log(array[i].producto);
      $("#producto"+i).val(array[i].producto);
      $("#servicio"+i).val(array[i].servicio);
      $("#cantidad"+i).val(array[i].cantidad);
      $("#precio"+i).val(array[i].precio);
      cantidad_filas = i;

    }
  }


</script>
</html>