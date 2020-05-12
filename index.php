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
      html,
      body {
        height: 100%;
      }

      body {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-align: center;
        align-items: center;
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
      }

      .form-signin {
        width: 100%;
        max-width: 420px;
        padding: 15px;
        margin: auto;
      }

      .form-label-group {
        position: relative;
        margin-bottom: 1rem;
      }

      .form-label-group > input,
      .form-label-group > label {
        height: 3.125rem;
        padding: .75rem;
      }

      .form-label-group > label {
        position: absolute;
        top: 0;
        left: 0;
        display: block;
        width: 100%;
        margin-bottom: 0; /* Override default `<label>` margin */
        line-height: 1.5;
        color: #495057;
        pointer-events: none;
        cursor: text; /* Match the input under the label */
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
        padding-top: 1.25rem;
        padding-bottom: .25rem;
      }

      .form-label-group input:not(:placeholder-shown) ~ label {
        padding-top: .25rem;
        padding-bottom: .25rem;
        font-size: 12px;
        color: #777;
      }

      /* Fallback for Edge
      -------------------------------------------------- */
      @supports (-ms-ime-align: auto) {
        .form-label-group > label {
          display: none;
        }
        .form-label-group input::-ms-input-placeholder {
          color: #777;
        }
      }

      /* Fallback for IE
      -------------------------------------------------- */
      @media all and (-ms-high-contrast: none), (-ms-high-contrast: active) {
        .form-label-group > label {
          display: none;
        }
        .form-label-group input:-ms-input-placeholder {
          color: #777;
        }
      }
    </style>
  </head>
  <body>
    <form class="form-signin" id="formLogin" autocomplete="off">
      <div class="text-center">
        <img class="w-75" src="<?php echo($ruta_raiz) ?>assets/img/logo.svg">
      </div>

      <input type="hidden" name="accion" value="iniciarSesion">
      <div class="form-label-group">
        <input type="text" name="usuario" class="form-control" placeholder="Usuario" required autofocus autocomplete="off">
        <label for="usuario">Usuario</label>
      </div>

      <div class="form-label-group">
        <input type="password" name="password" class="form-control" placeholder="Contraseña" required autofocus autocomplete="off">
        <label for="password">Contraseña</label>
      </div>

      <button class="btn btn-lg btn-verdeOscuro btn-block" id="btn-inciar" type="submit">Iniciar Sesión <i class="fas fa-sign-in-alt"></i></button>
      <p class="mt-5 mb-3 text-muted text-center">&copy; 2020</p>
    </form>
  </body>

  <script type="text/javascript">
    $(function(){
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
                });
                //Habilitamos el botón
                $('#formLogin :input').attr("disabled", false);
                $('#btn-inciar').html(`Ingresar <i class="fas fa-sign-in-alt"></i>`);
                $("#btn-inciar").attr("disabled", false);
              }
            },
            error: function(){
              Swal.fire({
                icon: 'error',
                html: "Error al inicar sesion."
              });
              //Habilitamos el botón
              $('#formLogin :input').attr("disabled", false);
              $('#btn-inciar').html(`Ingresar <i class="fas fa-sign-in-alt"></i>`);
              $("#btn-inciar").attr("disabled", false);
            },
          });
        }
      });

    });
  </script>
</html>