<?php  
  @session_start();
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

  require_once($ruta_raiz . 'clases/librerias.php');
  require_once($ruta_raiz . 'clases/Session.php');
  
  $lib = new Libreria;
  $session = new Session();

  if(@$session->exist('usuario')){
    header('location: '. $ruta_raiz . 'central');
    die();
  }
?>    

<!doctype html>
<html lang="es">
  <head>
    <?php  
      echo $lib->metaTagsRequired();
      echo $lib->iconoPag();
    ?>  
    <title>Ingresar | LavaSecoExprex</title>
    <?php  
      echo $lib->jquery();
      echo $lib->bootstrap();
      echo $lib->proyecto();
      echo $lib->jqueryValidate();
      echo $lib->alertify();
      echo $lib->sweetAlert2();
      echo $lib->fontAwesome();
    ?>

<style>
      :root {
        --input-padding-x: 1.5rem;
        --input-padding-y: 0.75rem;
      }

      .login {
        min-height: 100vh;
      }

      .btn-login {
        font-size: 0.9rem;
        letter-spacing: 0.05rem;
        padding: 0.75rem 1rem;
        border-radius: 2rem;
      }

      .form-label-group {
        position: relative;
        margin-bottom: 1rem;
      }

      .form-label-group>input,
      .form-label-group>label,
      .form-label-group>button,
      .form-label-group>select {
        padding: var(--input-padding-y) var(--input-padding-x);
        height: auto;
        border-radius: 2rem;
      }

      .form-label-group>label {
        position: absolute;
        top: 0;
        left: 0;
        display: block;
        width: 100%;
        margin-bottom: 0;
        /* Override default `<label>` margin */
        line-height: 1.5;
        color: #495057;
        cursor: text;
        /* Match the input under the label */
        border: 1px solid transparent;
        border-radius: .25rem;
        transition: all .1s ease-in-out;
      }

      .form-label-group input::-webkit-input-placeholder {
        color: transparent;
      }

      .form-label-group input:-ms-input-placeholder {
        color: transparent;
      }

      .form-label-group input::-ms-input-placeholder {
        color: transparent;
      }

      .form-label-group input::-moz-placeholder {
        color: transparent;
      }

      .form-label-group input::placeholder {
        color: transparent;
      }

      .form-label-group input:not(:placeholder-shown) {
        padding-top: calc(var(--input-padding-y) + var(--input-padding-y) * (2 / 3));
        padding-bottom: calc(var(--input-padding-y) / 3);
      }

      .form-label-group input:not(:placeholder-shown)~label {
        padding-top: calc(var(--input-padding-y) / 3);
        padding-bottom: calc(var(--input-padding-y) / 3);
        font-size: 12px;
        color: #777;
      }

    </style>
  </head>
  <body>
  <div class="container-fluid position-absolute">
      <div class="row no-gutter">
        <div class="d-none d-lg-flex col-lg-6 p-0">
          <img class="w-100 vh-100" src="https://i.pinimg.com/564x/46/6d/51/466d51be1145e5a48dc91901a4b41cbc.jpg">
        </div>
        <div class="col-12 col-lg-6 overflow-auto" style="max-height: 100vh !important">
          <div class="login d-flex align-items-center py-5">
            <div class="container">
              <div class="row">
                <!-- Formulario de Login -->
                <div id="contentLogin" class="col-12 col-md-10 col-xl-7 mx-auto">
                  <div class="text-center">
                    <!-- <img class="w-50 mb-5" src="assets/img/logo.svg"> -->
                    <div style="line-height: 30px; padding: 20px 0px; width: 100%">
                      <a href="index.php" style="font-size:80px; font-family: 'fuente-ciaf';" class="text-dark">CIAF</a><br>
                      <span class="eslogan" style="font-family: 'fuente-slogan';" >Educación Superior</span>
                    </div>
                  </div>
                  <form id="formLogin" autocomplete="off">
                    <input type="hidden" name="accion" value="iniciarSesion">
                    <div class="form-label-group">
                      <input type="email" id="correo" name="correo" class="form-control" placeholder="Correo electrónico" required autocomplete="off">
                      <label for="correo">Correo electrónico</label>
                    </div>

                    <div class="form-label-group">
                      <input type="password" id="password" name="password" class="form-control" placeholder="Contraseña" required autocomplete="new-password">
                      <label for="password">Contraseña</label>
                    </div>

                    <button class="btn btn-lg  btn-info btn-block btn-login text-uppercase font-weight-bold mb-2" id="btn-inciar" type="submit">
                      Ingresar <i class="fas fa-sign-in-alt"></i>
                    </button>

                    <div class="text-center mt-4">
                      ¿Aún no tienes una cuenta? <a href="#">Registrarse</a> <br><br>
                      <a id="olvidarClave" style="color: #007bff;cursor: pointer;">¿Has olvidado tu contraseña?</a>
                    </div>
                  </form>
                  <p class="mt-5 mb-3 text-muted text-center"><?php echo(date('Y')) ?> &copy; SENGLISH</p>
                  <img class="w-100 mb-4" src="<?php echo($ruta_raiz) ?>assets/img/secretaria.png" alt="">
                </div>

                <!-- Formulario de registro -->
                <div id="contentRegistro" class="col-12 col-lg-11 col-xl-10 mx-auto">
                  <div class="text-center">
                    <img class="w-30 mb-5" src="assets/img/logo.svg">
                  </div>
                  <form id="formRegistro" class="form-row" autocomplete="off">
                    <input type="hidden" name="accion" value="registrarse">

                    <div class="col-12 col-xl-6">
                      <div class="form-label-group">
                        <select class="custom-select" name="tipo_documento" id="tipo_documento" required>
                          <option value="0" selected disabled>Tipo de identificación</option>
                        </select>
                      </div>
                    </div>

                    <div class="col-12 col-xl-6">
                      <div class="form-label-group">
                        <input type="text" minlength="7" id="nro_documento" name="nro_documento" class="form-control" placeholder="Nro de identificación" autocomplete="off" required>
                        <label for="nro_documento">Nro de identificación<span class="text-danger">*</span></label>
                      </div>
                    </div>

                    <div class="col-12 col-xl-6">
                      <div class="form-label-group">
                        <select class="custom-select" name="tipo_persona" id="tipo_persona" required>
                          <option value="0" selected disabled>Tipo de persona</option>
                        </select>
                      </div>
                    </div>

                    <div class="col-12 col-xl-6">
                      <div class="form-label-group">
                        <input type="text" id="fecha" name="fecha" class="form-control datepicker" placeholder="Fecha Nacimiento" autocomplete="off" required>
                        <label for="fecha">Fecha Nacimiento<span class="text-danger">*</span></label>
                      </div>
                    </div>

                    <div class="col-12 col-xl-6">
                      <div class="form-label-group">
                        <input type="text" id="nombres" name="nombres" class="form-control" placeholder="Nombres" autocomplete="off" required>
                        <label for="nombres">Nombres<span class="text-danger">*</span></label>
                      </div>
                    </div>

                    <div class="col-12 col-xl-6">
                      <div class="form-label-group">
                        <input type="text" id="apellidos" name="apellidos" class="form-control" placeholder="Apellidos" autocomplete="off" required>
                        <label for="apellidos">Apellidos<span class="text-danger">*</span></label>
                      </div>
                    </div>
                    
                    <div class="col-12 col-xl-6">
                      <div class="form-label-group">
                        <input type="email" id="reCorreo" name="reCorreo" class="form-control" placeholder="Correo electrónico" autocomplete="off" required>
                        <label for="reCorreo">Correo electrónico<span class="text-danger">*</span></label>
                      </div>
                    </div>

                    <div class="col-12 col-xl-6">
                      <div class="form-label-group">
                        <input type="tel" minlength="7" id="tel" name="tel" class="form-control" placeholder="Teléfono" autocomplete="off" required onKeyPress="return soloNumeros(event)">
                        <label for="tel">Teléfono Whatsapp<span class="text-danger">*</span></label>
                      </div>
                    </div>


                    <div class="col-12 col-xl-6">
                      <div class="form-label-group">
                        <input type="password" id="rePassword" name="rePassword" class="form-control" placeholder="Contraseña" required autocomplete="new-password">
                        <label for="rePassword">Contraseña<span class="text-danger">*</span></label>
                      </div>
                    </div>

                    <div class="col-12 col-xl-6">
                      <div class="form-label-group">
                        <input type="password" id="rerePassword" name="rerePassword" class="form-control" placeholder="Confirmar Contraseña" required autocomplete="new-password">
                        <label for="rerePassword">Confirmar contraseña<span class="text-danger">*</span></label>
                      </div>
                    </div>

                    <div class="col-12 col-lg-6 mx-auto text-center">
                      <button class="btn btn-lg  btn-info btn-block btn-login text-uppercase font-weight-bold mb-2" id="btn-registro" type="submit">
                        Registrarse <i class="fas fa-sign-in-alt"></i>
                      </button>
                    </div>
                  </form>
                  <div class="text-center mt-4">
                    ¿Ya tienes una cuenta? <a href="?reg=0">Iniciar Sesión</a>
                  </div>
                  <p class="mt-5 mb-3 text-muted text-center">2020 &copy; Fruturo</p>
                </div>

                <!-- Formulario de Recuperacion  -->
                <div id="contentRecuperacion" class="col-12 col-lg-11 col-xl-10 mx-auto">
                  <div class="text-center">
                    <img class="w-30 mb-5" src="assets/img/logo.svg">
                  </div>
                  <form id="formRecuperacion" class="form-row" autocomplete="off">
                    <input type="hidden" name="accion" value="cambiarClave">
                    <input type="hidden" name="correoCambio" id="correoCambio">
                    <div class="col-12">
                      <div class="form-label-group">
                        <input type="email" id="correoCambioClave" name="correoElectronico" class="form-control" placeholder="Correo electrónico" required autocomplete="off">
                        <label for="correoCambioClave">Correo electrónico</label>
                      </div>
                    </div>

                    <div class="col-12 col-xl-6">
                      <div class="form-label-group">
                        <input type="password" id="recuperacionPassword" name="recuperacionPassword" class="form-control" placeholder="Contraseña" required autocomplete="new-password">
                        <label for="recuperacionPassword">Contraseña<span class="text-danger">*</span></label>
                      </div>
                    </div>

                    <div class="col-12 col-xl-6">
                      <div class="form-label-group">
                        <input type="password" id="rerecuperacionPassword" name="rerecuperacionPassword" class="form-control" placeholder="Confirmar Contraseña" required autocomplete="new-password">
                        <label for="rerecuperacionPassword">Confirmar contraseña<span class="text-danger">*</span></label>
                      </div>
                    </div>

                    <div class="col-12 col-xl-6 mx-auto text-center">
                      <button class="btn btn-lg  btn-info btn-block btn-login text-uppercase font-weight-bold mb-2" id="btnCambiarClave" type="submit">
                        Cambiar Contraseña <i class="fas fa-sign-in-alt"></i>
                      </button>
                    </div>
                  </form>
                  <div class="text-center mt-4">
                    ¿Ya tienes una cuenta? <a href="?reg=0">Iniciar Sesión</a>
                  </div>
                  <p class="mt-5 mb-3 text-muted text-center">2020 &copy; Fruturo</p>
                </div>

                <!-- Contenido de Activacion  -->
                <div id="contentActivacion" class="col-12 col-lg-11 col-xl-10 mx-auto">
                  <div class="text-center">
                    <img class="w-30 " src="assets/img/logo.svg">
                    <h3 class="mb-5">Activación de cuenta</h3>
                  </div>
                  <form id="formActivacion" class="form-row" autocomplete="off">
                    <input type="hidden" name="accion" value="activarCuenta">
                    <input type="hidden" name="correoActivacion" id="correoActivacion">
                    <input type="hidden" name="idActivacion" id="idActivacion">

                    <div class="col-12">
                      <div class="form-label-group">
                        <input type="email" id="correoActivacion2" name="correoElectronico" class="form-control" placeholder="Correo electrónico" required autocomplete="off">
                        <label for="correoActivacion2">Correo electrónico</label>
                      </div>
                    </div>

                    <div class="col-12 col-lg-6 mx-auto text-center">
                      <button class="btn btn-lg  btn-info btn-block btn-login text-uppercase font-weight-bold mb-2" id="btnActivarCuenta" type="submit">
                        Activar Cuenta <i class="fas fa-sign-in-alt"></i>
                      </button>
                    </div>
                  </form>
                  <div class="text-center mt-4">
                    ¿Ya tienes una cuenta? <a href="?reg=0">Iniciar Sesión</a>
                  </div>
                  <p class="mt-5 mb-3 text-muted text-center">2020 &copy; Fruturo</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>

  <div class="modal fade" id="modalRecuperarClave" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-unlock-alt"></i>&nbsp; Recuperar Contraseña</h5>
        </div>
        <form id="formRecuperarClave" autocomplete="off">
          <div class="modal-body mt-3">
            <input type="hidden" name="accion" value="recuperarClave">
            <div class="form-label-group">
              <input type="email" id="correoRecuperar" name="correo" class="form-control" placeholder="Correo electrónico" required autocomplete="off">
              <label for="correoRecuperar">Correo electrónico</label>
            </div>
          </div>
          <div class="modal-footer d-flex justify-content-between">
            <button class="btn  btn-info rounded-pill w-40 w-md-25" tyspe="submit" id="btnRecuperarClave"><i class="fas fa-paper-plane"></i> Recuperar</button>
            <button class="btn btn-primary rounded-pill w-40 w-md-25" data-dismiss="modal"><i class="fas fa-times"></i> Cerrar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script type="text/javascript">
  $(function(){
    $("#contentRegistro, #contentLogin, #contentRecuperacion, #contentActivacion").hide();

    if (getUrl('reg') == 1) {
      TiposDocumentos();
      TipoPersonas();
      TiposPerfiles();
      
      $(".datepicker").datepicker({ 
        dateFormat: "yy-mm-dd", 
        maxDate: "-18Y",
        yearRange: "-100:-18",
        changeMonth: true,
        changeYear: true
      });

      $("#fecha").val(moment().subtract(18, 'years').format("YYYY-MM-DD"));
      $("#contentRegistro").show(1000);
      $("#nro_documento").focus();
    }else if(getUrl('recuperar')){
      // se pone visible el formulario de cambio de clave
      $("#contentRecuperacion").show(1000);
      var token_recuperacion = getUrl('recuperar');
      findUserByToken(token_recuperacion, 'recuperar');

    }else if(getUrl('activar')){
      $("#contentActivacion").show(1000);
      var token_activacion = getUrl('activar');
      findUserByToken(token_activacion, 'activar');

    }else{
      $("#contentLogin").show(1000);
      $("#correo").focus();
    }

    $("#formLogin").validate();

    $("#formLogin").submit(function(event){
      event.preventDefault();
      if($("#formLogin").valid()){
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
            $('#btn-inciar').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Iniciando...`);
            $("#btn-inciar").attr("disabled" , true);
          },
          success: function(data){
            if (data.success ) {
              window.location.href = '<?php echo($ruta_raiz); ?>central';
            }else{
              Swal.fire({
                icon: 'error',
                html: data.msj
              })
            }
          },
          error: function(){
            alertify.error("Error al inicar sesion.");
          },
          complete: function(){
            //Habilitamos el botón
            $('#formLogin :input').attr("disabled", false);
            $('#btn-inciar').html(`Ingresar <i class="fas fa-sign-in-alt"></i>`);
            $("#btn-inciar").attr("disabled", false);
          } 
        });
      }
    });

    $("#formRegistro").validate({
      rules: {
        tel: {
          required: true,
          number: true
        },
        reCorreo: {
          required: true,
          email: true
        },
        rePassword: "required",
        rerePassword: {
          equalTo: "#rePassword"
        }
      }
    });

    $("#formRecuperarClave").validate({
      rules: {
        correo: {
          required: true,
          email: true
        }
      }
    });

    $("#formRecuperacion").validate({
      rules: {
        recuperacionPassword: {
          required: true
        },
        rerecuperacionPassword: "required",
        rerecuperacionPassword: {
          equalTo: "#recuperacionPassword"
        }
      }
    });

    $("#formRegistro").submit(function(event){
      event.preventDefault();
      if($("#formRegistro").valid()){
        $.ajax({
          type: "POST",
          url: "acciones",
          cache: false,
          contentType: false,
          dataType: 'json',
          processData: false,
          data: new FormData(this),
          beforeSend: function(){
            $('#formRegistro :input').attr("disabled", true);
            //Desabilitamos el botón
            $('#btn-registro').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Registrarse...`);
            $("#btn-registro").attr("disabled" , true);
          },
          success: function(data){
            if (data.success) {
              Swal.fire({
                icon: 'success',
                html: data.msj,
                preConfirm: () => {
                  location.href = '?reg=0'
                }
              })
            }else{
              Swal.fire({
                icon: 'error',
                html: data.msj
              })
            }
          },
          error: function(data){
            //Habilitamos el botón
            if(data.statusText == 'OK'){
              Swal.fire({
                icon: 'success',
                html: 'Se ha registrado correctamente',
                preConfirm: () => {
                  location.href = '?reg=0'
                }
              })
            }else{
              //Habilitamos el botón
              alertify.error("Error al registrar.");
            }

          },
          complete: function(){
            //Habilitamos el botón
            $('#formRegistro :input').attr("disabled", false);
            $('#btn-registro').html(`Registrarse <i class="fas fa-sign-in-alt"></i>`);
            $("#btn-registro").attr("disabled", false);
          }
        });
      }
    });

    // evento click opcion recuperar clave
    $("#olvidarClave").click(function(){
      //Some code
      $("#modalRecuperarClave").modal("show");
    });

    // formulario solo email
    $('#formRecuperarClave').submit(
      function(event){
        event.preventDefault();
        if($("#formRecuperarClave").valid()){
          $.ajax({
            type: "POST",
            url: "acciones",
            cache: false,
            contentType: false,
            dataType: 'json',
            processData: false,
            data: new FormData(this),
            beforeSend: function(){
              $('#formRecuperarClave :input').attr("disabled", true);
              $('#btnRecuperarClave').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`);
              $('#btnRecuperarClave').attr("disabled", true);
            },
            success: function(data){
              if (data.success) {
                Swal.fire({
                  icon: 'success',
                  html: data.msj,
                })
              }else{
                Swal.fire({
                  icon: 'error',
                  html: data.msj
                })
              }
            },
            error: function(data){
              if(data.statusText == 'OK'){
                Swal.fire({
                  icon: 'success',
                  html: 'Correo enviado correctamente',
                  preConfirm: () => {
                    $("#modalRecuperarClave").modal("hide");
                  }
                })
              }else{
                //Habilitamos el botón
                alertify.error("Error al registrar.");
              }
            },
            complete: function(){
              //Habilitamos el botón
              $('#formRecuperarClave :input').attr("disabled", false);
              $('#correoRecuperar').val('');
              $('#btnRecuperarClave').html(`Recuperar <i class="fas fa-paper-plane">`);
              $('#btnRecuperarClave').attr("disabled", false);
              
            }
          });
        }
      }
    );

    
    //formulario activacion cuenta
    $('#formActivacion').submit(
      function(event){
        event.preventDefault();
        $.ajax({
            type: "POST",
            url: "acciones",
            cache: false,
            contentType: false,
            dataType: 'json',
            processData: false,
            data: new FormData(this),
            beforeSend: function(){
              $('#btnActivarCuenta').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true">Activando cuenta ...</span>`);
              $('#btnActivarCuenta').attr("disabled", true);
            },
            success: function(data){
              if (data.success) {
                Swal.fire({
                  icon: 'success',
                  html: data.msj,
                  preConfirm: () => {
                    location.href = '?reg=0'
                  }
                })
              }else{
                Swal.fire({
                  icon: 'error',
                  html: data.msj
                })
              }
            },
            error: function(data){
              if(data.statusText == 'OK'){
                Swal.fire({
                  icon: 'success',
                  html: 'Correo enviado correctamente',
                  preConfirm: () => {
                    $("#modalRecuperarClave").modal("hide");
                  }
                })
              }else{
                //Habilitamos el botón
                alertify.error("Error al registrar.");
              }
            },
            complete: function(){
              //Habilitamos el botón
              $('#btnActivarCuenta').html(`Recuperar <i class="fas fa-paper-plane">`);
              $('#btnActivarCuenta').attr("disabled", false);
              
            }
          });
      }
    );

    // formulario cambio de clave
    $('#formRecuperacion').submit(function(event){
      event.preventDefault();
      if($("#formRecuperacion").valid()){
        $.ajax({
          type: "POST",
          url: "acciones",
          cache: false,
          contentType: false,
          dataType: 'json',
          processData: false,
          data: new FormData(this),
          beforeSend: function(){
            $('#btnCambiarClave').attr("disabled", true);
            $('#btnCambiarClave').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Cambiando clave...`);
          },
          success: function(data){
             if (data.success) {
                Swal.fire({
                  icon: 'success',
                  html: data.msj,
                  preConfirm: () => {
                    location.href = '?reg=0'
                  }
                })
              }else{
                Swal.fire({
                  icon: 'error',
                  html: data.msj
                })
              };
          },
          error: function(data){
            console.log('error: ', data);
          }
        });
      }
    });
  });

  // función para traer los tipos de documento
  function TiposDocumentos(){
    $.ajax({
      url: "<?php echo($ruta_raiz); ?>modulos/usuarios/tipo_documento/acciones",
      type: "POST",
      dataType: "json",
      data: {
        accion: "listaTipoDocumento",
      },
      success: function(datos){
        $('#tipo_documento').empty();
        $('#tipo_documento').append(`<option value="0" selected disabled>Tipo de identificación</option>`);
        if (datos.msj['cantidad_registros'] > 0) {
          for (let i = 0; i < datos.msj['cantidad_registros']; i++) { 
            $("#tipo_documento").append(`<option value="${datos.msj[i].id}">${datos.msj[i].abreviacion} - ${datos.msj[i].nombre}</option>`);
          }
        }
      },
      error: function(e){
        console.log(e);
      }
    });
  }

  // función para traer los tipos de persona
  function TipoPersonas(){
    $.ajax({
      url: "<?php echo($ruta_raiz); ?>modulos/usuarios/tipo_persona/acciones",
      type: "POST",
      dataType: "json",
      data: {
        accion: "listaTipoPersona",
      },
      success: function(datos){
        $('#tipo_persona').empty();
        $('#tipo_persona').append(`<option value="0" selected disabled>Tipo de persona</option>`);
        if (datos.msj['cantidad_registros'] > 0) {
          for (let i = 0; i < datos.msj['cantidad_registros']; i++) { 
            $("#tipo_persona").append(`<option value="${datos.msj[i].id}">${datos.msj[i].nombre}</option>`);
          }
        }
      },
      error: function(e){
        console.log(e);
      }
    });
  }

  //función para traer los tipos de perfiles
  function TiposPerfiles(){
    $.ajax({
      url: "<?php echo($ruta_raiz); ?>modulos/usuarios/perfiles/acciones",
      type: "POST",
      dataType: "json",
      data: {
        accion: "listaPerfiles",
      },
      success: function(datos){
        $('#perfil').empty();
        $('#perfil').append(`<option value="0" selected disabled>Tipo de perfil</option>`);
        if (datos.msj['cantidad_registros'] > 0) {
          for (let i = 0; i < datos.msj['cantidad_registros']; i++) { 
            $("#perfil").append(`<option value="${datos.msj[i].id}">${datos.msj[i].nombre}</option>`);
          }
        }
      },
      error: function(e){
        console.log(e);
      }
    });
  }

  // Traer datos de usuario vía token
  function findUserByToken(token, tipo){
    $.ajax({
      url: "acciones",
      type: "POST",
      dataType: "json",
      data: {
        accion: tipo == 'recuperar' ? "findUserByToken" : 'findUserByTokenActivacion',
        token
      },
      success: function(datos){
        if(datos[0]){
          // se setean datos de usuario en formulario correspondiente
          if(tipo == 'activar'){
            $('#correoActivacion').val(datos[0].correo);
            $('#correoActivacion2').val(datos[0].correo);
            $('#correoActivacion2').attr("disabled", true);
            $('#idActivacion').val(datos[0].id);
          
          }else if(tipo == 'recuperar'){
            $('#correoCambioClave').val(datos[0].correo);
            $('#correoCambio').val(datos[0].correo);
            $('#correoCambioClave').attr("disabled", true);
          }else{
            location.href = '?reg=0';
          }
          
        }else{
          location.href = '?reg=0';
        }

      },
      error: function(error){
        console.log({error});
      }
    });

  }

</script>
</html>