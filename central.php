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

  require_once($ruta_raiz . 'clases/librerias.php');
  require_once($ruta_raiz . 'clases/sessionActiva.php');
  
  $usuario = $session->get("usuario");
    
  $lib = new Libreria;
?>

<!doctype html>
<html lang="es">
<head>
  <?php  
    echo $lib->metaTagsRequired();
    echo $lib->iconoPag();
  ?>  
  <title id="tituloPagina">Dashboard | LavaSecoExprex</title>

  <?php  
    echo $lib->jquery();
    echo $lib->adminLTE();
    echo $lib->bootstrap();
    echo $lib->fontAwesome();
    echo $lib->alertify();
    echo $lib->proyecto();
    echo $lib->sweetAlert2();
    echo $lib->overlayScrollbars();
    
  ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed sidebar-collapse">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light d-block d-lg-none">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-light-success elevation-4">
    <!-- Brand Logo -->
    <div class="d-flex justify-content-between">
      <a href="#" class="brand-link">
        <img src="<?php echo($ruta_raiz); ?>assets/img/logo.png" alt="LavaSecoExprex Logo" class="brand-image">
      </a>
      <a class="brand-link text-right mr-3" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </div>
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <ul class="nav nav-pills nav-sidebar flex-column nav-flat nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
          with font-awesome or any other icon font library -->
        <li class="nav-item has-treeview user-panel mt-2 pb-2 mb-2">
          <a href="#" class="nav-link">
            <i class="nav-icon far fa-user-circle"></i>
            <p>
              <?php echo($usuario["usuario"]); ?>
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
            <i class=""></i>
              <a href="#" class="nav-link">
                <i class="fas fa-user-edit nav-icon"></i>
                <p>Perfil</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" onclick="top.cerrarSesion();" class="nav-link">
                <i class="fas fa-sign-out-alt nav-icon"></i>
                <p>Cerrar Sesión</p>
              </a>
            </li>
          </ul>
        </li>
      </ul>
      
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-flat nav-child-indent" id="modulos" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="<?php $ruta_raiz ?>modulos/usuarios/" data-modulo="Usuarios" target="object-contenido" class="nav-link link moduloUsuarios">
              <i class="nav-icon fas fa-users"></i>
              <p>Usuarios</p>
            </a>
          </li>
          <li class="nav-item has-treeview">
            <a href="<?php $ruta_raiz ?>modulos/modulos/" data-modulo="Modulos" target="object-contenido" class="nav-link link moduloModulos">
              <i class="nav-icon fas fa-lock"></i>
              <p>Módulos</p>
            </a>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-circle"></i>
              <p>
                Level 1
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Level 2</p>
                </a>
              </li>
              <li class="nav-item has-treeview">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Level 2
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Level 3</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Level 3</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Level 3</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Level 2</p>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <div class="wrapper">
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper vh-100">
      <object type="text/html" id="object-contenido" name="object-contenido" data="" class="w-100 vh-100 border-0"></object>
    </div>
  </div>
  <!-- ./wrapper -->

  <!-- Modal de Cargando -->
  <div class="modal fade modal-cargando" id="cargando" tabindex="1" role="dialog" aria-labelledby="cargandoTitle" aria-hidden="true" data-keyboard="false" data-focus="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="box-loading">
        <div class="loader">
          <div class="loader-1">
            <div class="loader-2">
            </div>
          </div>
        </div>
        <div>
          <img class="w-50" src="<?php echo($ruta_raiz); ?>assets/img/logo.svg" alt="">
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Sesion Cerrada -->
  <div class="modal fade" id="cerrarSession" tabindex="-1" role="dialog" aria-labelledby="cerrarSessionTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body text-center">
          <i class="fas fa-exclamation fa-7x text-warning mt-3 mb-3"></i>
          <h2>Lo sentimos, la sesión ha caducado</h2>
          Favor ingresar nuevamente, Gracias.
        </div>
        <div class="modal-footer d-flex justify-content-center">
          <a class="btn btn-primary" href="<?php echo $ruta_raiz; ?>">Cerrar <i class="fas fa-sign-out-alt"></i></a>
        </div>
      </div>
    </div>
  </div>
</body>
<script type="text/javascript">
  /* $("#cargando").modal("show"); */
  var idleTime = 0; 
  $(function(){
    //Tiempo en que valida la session
    window.idleInterval = setInterval(validarSession, 600000); // 10 minute 

    //modulosUsuarios();
    
    if (localStorage.url<?php echo(PROYECTO) ?> == null) {
      $("#object-contenido").attr("data", "modulos/");
    }else{
      $("#object-contenido").attr("data", localStorage.url<?php echo(PROYECTO) ?>);
    }
    
    if (localStorage.moduloActual<?php echo(PROYECTO) ?> != null) {
      $(".link").removeClass("active");
      $('.modulo' + localStorage.moduloActual<?php echo(PROYECTO) ?>).addClass("active");
      $('#tituloPagina').html(localStorage.moduloActual<?php echo(PROYECTO) ?> + ' | LavaSecoExprex');
    }

    $(document).on("click", ".link", function(){
      $(".link").removeClass("active");
      $(this).addClass("active");
      localStorage.moduloActual<?php echo(PROYECTO) ?> = $(this).data('modulo');
      $('#tituloPagina').html($(this).data('modulo') + ' | LavaSecoExprex');
    });
  });

  function validarSession(){
    $.ajax({
      type: 'POST',
      url: "<?php echo $ruta_raiz ?>acciones",
      data: {accion: "sessionActiva"},
      success: function(data){
        if (data == 0) {
          localStorage.removeItem("url<?php echo(PROYECTO) ?>");
          localStorage.removeItem('moduloActual<?php echo(PROYECTO) ?>');
          $("#cerrarSession").modal("show");
        }
      },
      error: function(data){
        Swal.fire({
          icon: 'error',
          html: 'No se ha podido validar la session'
        });
      }
    });
  }

  function modulosUsuarios(){
    $.ajax({
      type: 'POST',
      url: "<?php echo $ruta_raiz ?>modulos/modulos/acciones",
      data: {
        accion: "modulosUsuario"
      },
      success: function(data){
        data = JSON.parse(data);
        cargarMenu(data);
        /* $("#modulos").empty();
        for (let i = 0; i < data.msj.cantidad_registros; i++) {
          $("#modulos").append(`
            <li class="nav-item">
              <a href="<?php $ruta_raiz ?>modulos/${data.msj[i].ruta}" data-modulo="${data.msj[i].tag}" target="object-contenido" class="nav-link link modulo${data.msj[i].tag}">
                <i class="nav-icon ${data.msj[i].icono}"></i>
                <p>${data.msj[i].tag}</p>
              </a>
            </li>
          `);
        } */
      },
      error: function(data){
        Swal.fire({
          icon: 'error',
          html: 'No se ha podido validar los modulos'
        });
      },
      complete: function(){
        if (localStorage.moduloActual<?php echo(PROYECTO) ?> != null) {
          $(".link").removeClass("active");
          $('.modulo' + localStorage.moduloActual<?php echo(PROYECTO) ?>).addClass("active");
          $('#tituloPagina').html(localStorage.moduloActual<?php echo(PROYECTO) ?> + ' | LavaSecoExprex');
        }
      }
    });
  }

  function cargarMenu(data){
    console.log(data);
  }

  function cerrarSesion(){
    Swal.fire({
      title: '¿Estas seguro de cerrar sesión?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si',
      cancelButtonText: 'No'
    }).then((result) => {
      if (result.value) {
        localStorage.removeItem("url<?php echo(PROYECTO) ?>");
        localStorage.removeItem('moduloActual<?php echo(PROYECTO) ?>');
        window.location.href='<?php echo $ruta_raiz ?>clases/sessionCerrar';
      }
    });
    
  }
</script>
</html>