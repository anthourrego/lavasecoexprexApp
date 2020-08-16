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
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="tituloModalCrear">titulo</h5>
          <button type="button" class="btn btn-secondary m-1" data-dismiss="modal"><i class="fas fa-times"></i></button>

        </div>

        <h5 class="text-left mt-1 p-2"> Datos Cliente</h5>
        <form id="formClientes">
          <input type="hidden" name="accion" value="crearCliente">
          <input type="hidden" required name="id" value="">
          <div class="modal-body">
            <label for="telefono">Telefono <span class="text-danger">*</span></label>
            <div class="input-group form-group">
              <input type="number" name="telefono" required class="form-control" placeholder="Escriba el telefono"  aria-describedby="button-search" onblur="buscarCliente()" >
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
              <button id="btnEditarCliente" type="button" class="btn btn-primary m-1">
                <i class="fas fa-edit"></i>
                Editar
              </button>
              <button id="btnGuardarCliente" type="submit" class="btn btn-success m-1">
                <i class="fas fa-save"></i>
                Guardar
              </button>
              <button id="btnCancelarCliente" type="button" class="btn btn-secondary m-1">
                <i class="fas fa-times"></i>
                Cancelar
              </button>

            </div>
          </div>
        </form>


        <h5 class="text-left mt-1 p-2"> Datos Factura</h5>
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
  let cantidad_filas = 1;
  let precios = {};
  $(function(){
    $('[data-toggle="tooltip"]').tooltip();

    /* $("#formClientes :input[name='nombre'], #formClientes :input[name='direccion']").attr('disabled', true);*/
    $("#btnEditarCliente, #btnGuardarCliente, #btnCancelarCliente").attr('hidden', true);
    
    //Crear
    $('.btnCrearFactura').on("click", function(){
      $("#tituloModalCrear").html(`<i class="fas fa-plus"></i> Crear Factura`);
      /* $('#btnCrearFactura').html(`<i class="fas fa-paper-plane"></i> Crear`); */
      resettabla();
      $('#formClientes').trigger("reset");
      $("#modalCrearfactura").modal("show");
    });

    $("#btnBuscar").on("click", ()=> {
      buscarCliente();
    })

    $(".datepicker").datepicker({ 
      dateFormat: "yy-mm-dd", 
    });


    $("#btnEditarCliente").on("click", function(){
      $("#formClientes :input[name='accion']").val('editarCliente');
      $("#formClientes :input[name='nombre'], #formClientes :input[name='direccion']").removeAttr('disabled');
      $("#btnEditarCliente").attr('hidden', true);
      $("#btnGuardarCliente, #btnCancelarCliente").removeAttr("hidden");
      if($("#formClientes :input[name='accion']").val()=='editarCliente'){
        $('#btnGuardarCliente').html(`<i class="fas fa-save"></i> Guardar`);
      }
    })

    $("#btnCancelarCliente").on("click", function(){
      $("#formClientes :input[name='accion']").val('crearCliente');
      //$("#formClientes :input[name='nombre'], #formClientes :input[name='direccion']").attr('disabled',true);
      $("#btnEditarCliente,#btnGuardarCliente, #btnCancelarCliente").attr("hidden", true);
      $("#formClientes :input[name='nombre'], #formClientes :input[name='direccion'], #formClientes :input[name='telefono']").val('');
    });



    $("#formClientes").submit(function(event){
      event.preventDefault();
      if($("#formClientes").valid()){
        $.ajax({
            type: "POST",
          url: "acciones",
          cache: false,
          contentType: false,
          dataType: 'json',
          processData: false,
          data: new FormData(this),
          beforeSend: function(){
            $('#formLogin :input').attr("disabled", true);
            //Desabilitamos el botón
            $('#btnGuardarCliente').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> guardando...`);
            $("#btnGuardarCliente").attr("disabled" , true);
          },
          success: function(data){
            if(data.success){

              Swal.fire({
                icon: 'success',
                html: data.msj
              });
              $("#formClientes :input[name='nombre'], #formClientes :input[name='direccion']").attr('disabled',true);
              $("#btnGuardarCliente, #btnCancelarCliente").attr("hidden", true);
              $("#btnEditarCliente").removeAttr('hidden');
              $('#btnGuardarCliente').html(`<i class="fas fa-save"></i> Guardar`);
              $("#btnGuardarCliente").removeAttr("disabled");

              if(data.id_creado){
                buscarCliente(data.id_creado);
              }

            }
          },
          error: function(){
            Swal.fire({
              icon: 'error',
              html: "Error."
            });
            //Habilitamos el botón
            $('#formClientes :input').attr("disabled", false);
            $('#btnGuardarCliente').html(`<i class="fas fa-save"></i> Guardar`);
            $("#btnGuardarCliente").attr("hidden", false);
            $("#btnGuardarCliente").removeAttr("disabled");
          },
        });
      }
      
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
          <select class="custom-select" required name='producto${cantidad_filas}' id="producto${cantidad_filas}" onchange="seleccionar(this)">
            <option value='0' disabled selected >Seleccione un opción</option>
          </select>
        </td>
        <td>
          <select class="custom-select" required name='servicio${cantidad_filas}' id="servicio${cantidad_filas}">
            <option value='0' disabled selected >Seleccione un opción</option>
          </select>
        </td>
        <td>
          <input required name='cantidad${cantidad_filas}' type='number' placeholder='Cantidad'  class='form-control' id='cantidad${cantidad_filas}' onchange="seleccionar(this)" >
        </td>
        <td>
          <input required name='precio${cantidad_filas}'  id='precio${cantidad_filas}' type='text' placeholder='Precio'  class='form-control' onblur="calcularTotal()">
        </td>`
      );
      $('#cantidad'+cantidad_filas).val(1);
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
    if($("#formClientes :input[name='accion']").val() != 'editarCliente'){
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
              //$("#formClientes :input[name='nombre'], #formClientes :input[name='direccion']").removeAttr("disabled")
              $("#btnEditarCliente").attr('hidden', false);
              $("#btnCancelarCliente").attr('hidden', false);
            }else{
              
              $("#formClientes :input[name='accion']").val('crearCliente');
              $("#formClientes :input[name='id']").val('');
              $("#formClientes :input[name='telefono']").val();
              $("#formClientes :input[name='nombre']").val('');
              $("#formClientes :input[name='direccion']").val('');
              $("#btnEditarCliente").attr('hidden', true);
              $("#formClientes :input[name='nombre'], #formClientes :input[name='direccion']").removeAttr("disabled");
              $('#btnGuardarCliente').html(`<i class="fas fa-save"></i> Crear`);
              $('#btnGuardarCliente, #btnCancelarCliente').removeAttr("hidden");

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
    }else{
      console.log('no ni mergas');
    }
    
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

  function traerProductos(){
    $.ajax({
          url: 'acciones',
          type: 'GET',
          dataType: 'json',
          data: {
            accion: "traerProductos", 
          },
          success: function(data){
            // si no hay precios guardados, se setea el objeto de precios
            if(!Object.keys(precios).length){
              for (let i = 0; i < data.msj.cantidad_registros; i++) {
                precios[data.msj[i].id] = data.msj[i].precio;
              }
            }

            if(data.success){
              const cond =  cantidad_filas;
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


  function traerServicios(){
    $.ajax({
          url: 'acciones',
          type: 'GET',
          dataType: 'json',
          data: {
            accion: "traerServicios", 
          },
          success: function(data){
            if(data.success){
              const cond = cantidad_filas;
              for (let i = 0; i < data.msj.cantidad_registros; i++) {
                $(`#servicio${cantidad_filas - 1}`).append(`
                  <option value="${data.msj[i].id}" ${data.msj[i].id == 3 ? 'selected' : '' }>${data.msj[i].nombre}</option>
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
            $("#tabla").DataTable().ajax.reload();
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

  function resettabla(){
    $("#body_tabla").empty();
    $('#tabla_factura').append('<tr id="addr0"></tr>');

    $('#addr0').html(`
      <td>
        <select class="custom-select" required name='producto0' id="producto0" onchange="seleccionar(this)">
          <option value='0' disabled selected >Seleccione un opción</option>
        </select>
      </td>
      <td>
        <select class="custom-select" required name='servicio0' id="servicio0">
          <option value='0' disabled selected >Seleccione un opción</option>
        </select>
      </td>
      <td>
        <input required name='cantidad0' type='number' placeholder='Cantidad'  class='form-control' id='cantidad0' onchange="seleccionar(this)">
      </td>
      <td>
        <input required name='precio0'  id='precio0' type='text' placeholder='Precio'  class='form-control' onblur="calcularTotal()">
      </td>`
    );
    $('#cantidad0').val(1);
    traerProductos();
    traerServicios();
    $('#tabla_factura').append('<tr id="addr1"></tr>');
    cantidad_filas = 1;

  }

  function seleccionar(elemento, tipo){
    const numeroId = elemento.id[elemento.id.length - 1];
    const producto = $('#producto'+numeroId).val();
    const cantidad = $('#cantidad'+numeroId).val();
    if(producto && cantidad){
      const precioTmp = parseInt(precios[producto]) * parseInt(cantidad);
      $('#precio'+numeroId).val(precioTmp);
      calcularTotal();
    }

  }



</script>
</html>