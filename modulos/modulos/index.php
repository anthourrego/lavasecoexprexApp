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

  if ($permisos->validarPermiso($usuario['id'], 'usuarios') == 0) {
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
          <h1 class="m-0 text-dark">Módulos</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container">
      <div class="card">
        <div class="card-header d-flex justify-content-end">
          <button class="btn btn-danger mx-1" id="btnEliminar" value="0" data-nombre="0" disabled data-toggle="tooltip" title="Eliminar"><i class="far fa-trash-alt"></i></button>
          <button class="btn btn-success mx-1" id="btnEditar"  disabled data-toggle="tooltip" title="Editar"><i class="far fa-edit"></i></button>
          <button class="btn btn-primary mx-1 btnCrearModulo" data-toggle="tooltip" title="Crear"><i class="fas fa-plus"></i></button>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
        <div class="row">
          <div class="col-12 col-md-6">
            <div class="form-group">
              <input type="input" class="form-control" id="input-search" placeholder="Buscar un módulo" value="" autocomplete="off">
            </div>
            <div id="treeview1"></div>
          </div>
          <div class="col-12 col-md-6 mt-4 mt-md-0">
            <form id="formEditarModulo">
              <input type="hidden" name="accion" value="editarModulo">
              <input type="hidden" name="idModulo" value="0">
              <div class="form-group row">
                <label class="col-12 col-lg-4 align-self-center" for="modPadre">Módulo Padre <span class="text-danger">*</span>:</label>
                <div class="col-12 col-lg-8">
                  <select class="custom-select" name="modPadre" id="selectModPadre" disabled required>
                    <option value="0" selected disabled>Raíz</option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label for="modulo-tipo" class="col-12 col-lg-4">Tipo Módulo <span class="text-danger">*</span>:</label>
                <div class="col-12 col-lg-8">
                  <select class="custom-select tipoModulo" name="modulo-tipo" disabled required>
                    <option value='0' disabled selected>Seleccione un opción</option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label for="nombre" class="col-12 col-lg-4">Nombre <span class="text-danger">*</span>:</label>
                <div class="col-12 col-lg-8">
                  <input type="text" name="nombre" class="form-control" disabled required autocomplete="off">
                  <small class="text-danger">Sin espacios y/o Caracteres especiales</small>
                </div>
              </div>
              <div class="form-group row">
                <label for="nombre" class="col-12 col-lg-4">Etiqueta <span class="text-danger">*</span>:</label>
                <div class="col-12 col-lg-8">
                  <input type="text" name="tag" class="form-control" disabled required autocomplete="off">
                  <small class="text-danger">Nombre a mostrar</small>
                </div>
              </div>
              <div class="form-group row">
                <label for="nombre" class="col-12 col-lg-4">Icono <span class="text-danger">*</span>:</label>
                <div class="col-12 col-lg-8">
                  <input type="text" name="icono" class="form-control" placeholder="Icono de Font Awesome" disabled required autocomplete="off">
                </div>
              </div>
              <div class="form-group row">
                <label for="nombre" class="col-12 col-lg-4">Ruta <span class="text-danger">*</span>:</label>
                <div class="col-12 col-lg-8">
                  <input type="text" name="ruta" class="form-control" placeholder="Escriba la ruta final de módulo" disabled required autocomplete="off">
                </div>
              </div>
              <div class="text-right">
                <button type="submit" class="btn btn-success" name="btnGuardar" disabled><i class="far fa-save"></i> Guardar</button>
              </div>
            </form>
          </div>
        </div>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->

  <!-- Modal Crear Usuario -->
  <div class="modal fade" id="modalCrearModulo" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-plus"></i> Crear Módulo</h5>
        </div>
        <form id="formCrearModulo" autocomplete="off">
          <input type="hidden" name="accion" value="crearModulo">
          <div class="modal-body">
            <div class="form-group">
              <label for="modulo-tipo">Módulo Padre <span class="text-danger">*</span></label>
              <select class="custom-select selectModulos" name="selectModulos" required>
                <option value='0' selected>Raíz</option>
              </select>
            </div>
            <div class="form-group">
              <label for="modulo-tipo">Tipo Módulo <span class="text-danger">*</span></label>
              <select class="custom-select tipoModulo" name="modulo-tipo" required>
                <option value='0' disabled selected>Seleccione un opción</option>
              </select>
            </div>
            <div class="form-group">
              <label for="nombre">Nombre <span class="text-danger">*</span></label>
              <input type="text" name="nombre" class="form-control" required autocomplete="off">
              <small class="text-danger">Sin espacios y/o Caracteres especiales</small>
            </div>
            <div class="form-group">
              <label for="nombre">Etiqueta <span class="text-danger">*</span></label>
              <input type="text" name="tag" class="form-control" required autocomplete="off">
              <small class="text-danger">Nombre a mostrar</small>
            </div>
            <div class="form-group">
              <label for="nombre">Icono <span class="text-danger">*</span></label>
              <input type="text" name="icono" class="form-control" placeholder="Icono de Font Awesome" required autocomplete="off">
            </div>
            <div class="form-group">
              <label for="nombre">Ruta <span class="text-danger">*</span></label>
              <input type="text" name="ruta" class="form-control" placeholder="Escriba la ruta final de módulo" required autocomplete="off">
            </div>
          </div>
          <div class="modal-footer d-flex justify-content-between">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cerrar</button>
            <button id="btnCrearModulo" type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Crear</button>
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
    cargarArbol(0);
    $('[data-toggle="tooltip"]').tooltip();

    //Quitar los espacio cuando escribar el nombre del modulo
    $("#formCrearModulo :input[name='nombre']").keyup(function() {
      var txt = $(this).val();
      $(this).val(txt.replace(/ /g, ""));
    });

    //Botón de editar tecnólogia
    $("#btnEditar").on("click", function(){
      $("#formEditarModulo :input[name='idModulo']").attr("disabled", false);
      $("#formEditarModulo :input[name='modulo-tipo']").attr("disabled", false);

      if ($("#formEditarModulo :input[name='modulo-tipo']").val() == 1) {
        $("#formEditarModulo :input[name='icono']").attr("disabled", false);
        $("#formEditarModulo :input[name='ruta']").attr("disabled", false);
      }

      $("#formEditarModulo :input[name='modPadre']").attr("disabled", false);
      $("#formEditarModulo :input[name='tag']").attr("disabled", false);
      $("#formEditarModulo :input[name='btnGuardar']").attr("disabled", false);
    });


    //Cargamos la opciones del tipo modulo
    $.ajax({
      url: "acciones",
      type: "GET",
      dataType: "json",
      data: {
        accion: 'listaTipoModulos'
      },
      success: function(data){
        if (data.success) {
          $(`.tipoModulo`).empty();
          $(`.tipoModulo`).append(`
            <option value="0" disabled selected>Seleccione un opción</option>
          `);
          for (let i = 0; i < data.msj.cantidad_registros; i++) {
            $(`.tipoModulo`).append(`
              <option value="${data.msj[i].id}">${data.msj[i].nombre}</option>
            `);
          }
        }else{
          Swal.fire({
            icon: 'warning',
            html: data.msj
          });
        }
      },
      error: function(){
        Swal.fire({
          icon: 'error',
          html: 'No se han encontrado datos'
        });
      }
    });

    //Abrir el modal de crear modulo
    $(".btnCrearModulo").on("click", function(){
      $("#formCrearModulo :input").removeClass("is-valid");
      $("#formCrearModulo :input").removeClass("is-invalid");
      $("#modalCrearModulo").modal("show");
      listaModulos(2);
    });

    //Formulario de crear módulo
    $("#formCrearModulo :input[name='modulo-tipo']").on("change", function(){
      if ($(this).val() == 2) {
        $("#formCrearModulo :input[name='icono']").attr("disabled", true);
        $("#formCrearModulo :input[name='ruta']").attr("disabled", true);
      }else{
        $("#formCrearModulo :input[name='icono']").attr("disabled", false);
        $("#formCrearModulo :input[name='ruta']").attr("disabled", false);
      }
    });

    //Formulario de crear módulo
    $("#formEditarModulo :input[name='modulo-tipo']").on("change", function(){
      if ($(this).val() == 2) {
        $("#formEditarModulo :input[name='icono']").attr("disabled", true);
        $("#formEditarModulo :input[name='ruta']").attr("disabled", true);
      }else{
        $("#formEditarModulo :input[name='icono']").attr("disabled", false);
        $("#formEditarModulo :input[name='ruta']").attr("disabled", false);
      }
    });

    $("#formCrearModulo").submit(function(event){
      event.preventDefault();
      if($(this).valid()){
        $.ajax({
          type: "POST",
          url: "acciones",
          cache: false,
          contentType: false,
          dataType: 'json',
          processData: false,
          data: new FormData(this),
          beforeSend: function(){
            $('#formCrearModulo :input').attr("disabled", true);
            //Desabilitamos el botón
            $('#btnCrearModulo').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Creando...`);
            $("#btnCrearModulo").attr("disabled" , true);
          },
          success: function(data){
            if (data.success) {
              cargarArbol(0);
              $("#formCrearModulo")[0].reset();
              $("#formCrearModulo :input").removeClass("is-valid");
              $("#formCrearModulo :input").removeClass("is-invalid");
              $("#modalCrearModulo").modal("hide");
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
            $('#formCrearModulo :input').attr("disabled", false);
            $('#btnCrearModulo').html(`<i class="fas fa-paper-plane"></i> Crear`);
            $("#btnCrearModulo").attr("disabled", false);
          },
          complete: function(){
            //Habilitamos el botón
            $('#formCrearModulo :input').attr("disabled", false);
            $('#btnCrearModulo').html(`<i class="fas fa-paper-plane"></i> Crear`);
            $("#btnCrearModulo").attr("disabled", false);
          }
        });
      }
    });

    //Formulario editar módulo
    $("#formEditarModulo").submit(function(e){
      e.preventDefault();
      if($(this).valid()){
        $.ajax({
          type: "POST",
          dataType: "json",
          url: "acciones",
          cache: false,
          contentType: false,
          processData: false,
          data: new FormData(this),
          success: function(data){
            if (data.success == true) {
              //Tomados el id del módulo seleccionada actualmente
              let idSelect = $("#formEditarModulo :input[name='tecPadre']").val();

              //Se cargan todos los datos datos del arbol y del select
              cargarArbol();
              listaModulos(0, idSelect);
              
              //Volvemos a deshabilitar los campos
              $("#formEditarModulo :input[name='idModulo']").attr("disabled", true);
              $("#formEditarModulo :input[name='modulo-tipo']").attr("disabled", true);
              $("#formEditarModulo :input[name='modPadre']").attr("disabled", true);
              $("#formEditarModulo :input[name='nombre']").attr("disabled", true);
              $("#formEditarModulo :input[name='tag']").attr("disabled", true);
              $("#formEditarModulo :input[name='icono']").attr("disabled", true);
              $("#formEditarModulo :input[name='ruta']").attr("disabled", true);
              $("#formEditarModulo :input[name='btnGuardar']").attr("disabled", true);

              Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: 'success',
                title: data.msj,
                showConfirmButton: false,
                timer: 5000
              });
            } else {
              Swal.fire({
                icon: 'error',
                html: data.msj
              });
            }
          },
          error: function(data){
            Swal.fire({
              icon: 'error',
              html: 'No se han encontrado datos'
            });
          }
        });
      }
    });

    //Acción al click botón eliminar
    $("#btnEliminar").on("click", function(){
      let id = $(this).val();
      let nombre = $(this).data("nombre");
      if (id != 0) {
        Swal.fire({
          title: "¿Estas seguro de eliminar el módulo " + nombre + "?",
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
                accion: "elminarModulo", 
                id: id,
                modulo: nombre
              },
              success: function(data){
                if (data == 1) {
                  cargarArbol();
                  listaModulos();

                  $("#formEditarModulo :input[name='idModulo']").val(0);
                  $("#formEditarModulo :input[name='modulo-tipo']").val(0);
                  $("#formEditarModulo :input[name='modPadre']").val("");
                  $("#formEditarModulo :input[name='nombre']").val("");
                  $("#formEditarModulo :input[name='tag']").val("");
                  $("#formEditarModulo :input[name='icono']").val("");
                  $("#formEditarModulo :input[name='ruta']").val("");
                  
                  $("#formEditarModulo :input[name='idModulo']").attr("disabled", true);
                  $("#formEditarModulo :input[name='modulo-tipo']").attr("disabled", true);
                  $("#formEditarModulo :input[name='modPadre']").attr("disabled", true);
                  $("#formEditarModulo :input[name='nombre']").attr("disabled", true);
                  $("#formEditarModulo :input[name='tag']").attr("disabled", true);
                  $("#formEditarModulo :input[name='icono']").attr("disabled", true);
                  $("#formEditarModulo :input[name='ruta']").attr("disabled", true);
                  $("#formEditarModulo :input[name='btnGuardar']").attr("disabled", true);

                  Swal.fire({
                    toast: true,
                    position: 'bottom-end',
                    icon: 'success',
                    title: "Se ha eliminado el módulo " + nombre,
                    showConfirmButton: false,
                    timer: 5000
                  });
                }else{
                  Swal.fire({
                    icon: 'warning',
                    html: "Error al eliminar el módulo " + nombre
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
      }else{
        Swal.fire({
          icon: 'warning',
          html: 'No ha seleccionado un módulo'
        })
      }
    });
  });

  function listaModulos(idSelect = 0, fkModulo = 0, idModulo = 0){
    $.ajax({
      url: "acciones",
      type: "GET",
      dataType: "json",
      data: {
        accion: "listaModulos"
      },
      success: function(data){
        if (data.success) {
          $inputSelect = "#formEditarModulo :input[name='modPadre'], #formCrearModulo :input[name='selectModulos']";
          if (idSelect == 1) {
            $inputSelect = "#formEditarModulo :input[name='modPadre']";
          }else if(idSelect == 2){
            $inputSelect = "#formCrearModulo :input[name='selectModulos']";
          }
  
          $($inputSelect).empty();
  
          $($inputSelect).append(`
            <option value="0" selected>Raíz</option>
          `);
          for (let i = 0; i < data.msj.cantidad_registros; i++) {
            $($inputSelect).append(`
              <option value="${data.msj[i].id}">${data.msj[i].nombre}</option>
            `); 
          }
  
          //Datos para crear tecnólogia
          if (idSelect == 2) {
            val = 0
            if ($("#formEditarModulo :input[name='modPadre']").val() != null) {
              val = $("#formEditarModulo :input[name='modPadre']").val();
            }
            $("#formCrearModulo :input[name='selectModulos']").val(val);
          }
          
          //Seleccionamos el fk padre de la tecnologia seleccionada
          if (fkModulo != 0) {
            $("#formEditarModulo :input[name='modPadre']").val(fkModulo);
          }
        }
      },
      error: function(){
        Swal.fire({
          icon: 'error',
          html: 'No se han encontrado datos'
        });
      }
    });
  }

  function cargarArbol(iniciar = 1){
    $.ajax({
      url: "acciones",
      type: "POST",
      dataType: "json",
      data: {
        accion: "arbolModulos"
      },
      success: function(data){
        if (iniciar == 1) {
          arbol = $('#treeview1').treeview('getExpanded');
        }

        var initSelectableTree = function() {
          return $('#treeview1').treeview({
            levels: 1,
            data: data,
            showTags: true,
            onNodeSelected: function(event, node) {
              $("#formEditarModulo :input").removeClass("is-valid");
              $("#formEditarModulo :input").removeClass("is-invalid");
              //Cargamos el select
              listaModulos(0, node.fk_modulo, node.idModulo);

              //Motramos todos los campos del selece en editar si hemos ocultado alguno
              $("#formEditarModulo :input[name='modPadre'] option").show();
              
              //Enviamos id al botón eliminar
              $("#btnEliminar").val(node.idModulo);
              $("#btnEliminar").data("nombre", node.text);

              //Volvemos a deshabilitar los campos
              $("#formEditarModulo :input[name='idModulo']").attr("disabled", true);
              $("#formEditarModulo :input[name='modulo-tipo']").attr("disabled", true);
              $("#formEditarModulo :input[name='modPadre']").attr("disabled", true);
              $("#formEditarModulo :input[name='nombre']").attr("disabled", true);
              $("#formEditarModulo :input[name='tag']").attr("disabled", true);
              $("#formEditarModulo :input[name='icono']").attr("disabled", true);
              $("#formEditarModulo :input[name='ruta']").attr("disabled", true);
              $("#formEditarModulo :input[name='btnGuardar']").attr("disabled", true);

              //Datos para editar;
              $("#formEditarModulo :input[name='idModulo']").val(node.idModulo);
              $("#formEditarModulo :input[name='modulo-tipo']").val(node.fk_modulo_tipo);
              $("#formEditarModulo :input[name='modPadre']").val(node.fk_modulo);
              $("#formEditarModulo :input[name='nombre']").val(node.nombre);
              $("#formEditarModulo :input[name='tag']").val(node.text);
              $("#formEditarModulo :input[name='icono']").val(node.icono);
              $("#formEditarModulo :input[name='ruta']").val(node.ruta);

              //Ocultamos la tecnólogia seleccionada en el select
              $("#formEditarModulo :input[name='modPadre'] option[value='" + node.idModulo + "']").hide();
              
              //Habilitamos los botónes para editar
              $("#btnEditar").prop("disabled", false);
              $("#btnEliminar").prop("disabled", false);

            }
          });
        };

        var $selectableTree = initSelectableTree();

        var findSelectableNodes = function() {
          return $selectableTree.treeview('search', [ $('#input-search').val(), { ignoreCase: false, exactMatch: false } ])
        };
        var selectableNodes = findSelectableNodes();

        $('#input-search').on('keyup', function (e) {
          $('#treeview1').treeview('collapseAll', { silent:true });
          selectableNodes = findSelectableNodes();
        });

        if (iniciar == 1) {
          if (arbol.length > 0) {
            for (let i = 0; i < arbol.length; i++) {
              $('#treeview1').treeview('expandNode', [ arbol[i].nodeId, { silent: true } ]);
            }
          }
        }
      },
      error: function(){
        Swal.fire({
          icon: 'error',
          html: 'No se han encontrado datos'
        })
      },
      complete: function(){
        cerrarCargando();
      }
    });
  }
</script>
</html>