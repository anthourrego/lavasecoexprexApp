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
  <title id="tituloPagina">Dashboard | SEnglish</title>

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
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="modulos/usuarios/" data-modulopadre="0" data-modulo="Usuarios" target="object-contenido" class="nav-link"><i class="nav-icon fas fa-users"></i> Usuarios</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="modulos/modulos/" data-modulopadre="0" data-modulo="Modulos" target="object-contenido" class="nav-link"><i class="nav-icon fab fa-modx"></i> Modulos</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="modulos/productos/" data-modulopadre="0" data-modulo="Productos" target="object-contenido" class="nav-link">Productos</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="modulos/gastos" data-modulopadre="0" data-modulo="Gastos" target="object-contenido" class="nav-link">Gastos</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="modulos/clientes" data-modulopadre="0" data-modulo="Clientes" target="object-contenido" class="nav-link">Clientes</a>
      </li>
      <li class="nav-item dropdown">
        <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Modulos</a>
        <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow" style="left: 0px; right: inherit;">
          <li><a href="#" class="dropdown-item">Some action </a></li>
          <li><a href="#" class="dropdown-item">Some other action</a></li>

          <li class="dropdown-divider"></li>

          <!-- Level two dropdown-->
          <li class="dropdown-submenu dropdown-hover">
            <a id="dropdownSubMenu2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">Hover for action</a>
            <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
              <li>
                <a tabindex="-1" href="#" class="dropdown-item">level 2</a>
              </li>

              <!-- Level three dropdown-->
              <li class="dropdown-submenu">
                <a id="dropdownSubMenu3" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">level 2</a>
                <ul aria-labelledby="dropdownSubMenu3" class="dropdown-menu border-0 shadow">
                  <li><a href="#" class="dropdown-item">3rd level</a></li>
                  <li><a href="#" class="dropdown-item">3rd level</a></li>
                </ul>
              </li>
              <!-- End Level three -->

              <li><a href="#" class="dropdown-item">level 2</a></li>
              <li><a href="#" class="dropdown-item">level 2</a></li>
            </ul>
          </li>
          <!-- End Level two -->
        </ul>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <img width="30px" src="<?php echo($ruta_raiz); ?>assets/img/usuario.png"); ?>
        </a>  
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
          <span class="dropdown-item-text text-center"><?php echo($usuario["usuario"]); ?></span>
          <a class="dropdown-item modal-link" href="<?php echo RUTA_RAIZ ?>modulos/usuarios/editar_perfil" target="object-contenido"><i class="fas fa-user-edit"></i> Perfil</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="javascript:cerrarSesion();"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-info elevation-4">
    <!-- Brand Logo -->
    <div class="d-flex justify-content-between">
      <a href="<?php echo($ruta_raiz); ?>modulos/" class="brand-link">
        <img src="<?php echo($ruta_raiz); ?>assets/img/i_ciafi.png" alt="S.English Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">S.English</span>
      </a>
    </div>
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-1">
        <ul class="nav nav-pills nav-sidebar flex-column nav-flat nav-child-indent" id="modulos" data-widget="treeview" role="menu" data-accordion="false">
          <!-- <li class="nav-item has-treeview user-panel mt-2 pb-2 mb-2">
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
          </li>-->
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
          <img class="w-50" src="<?php echo($ruta_raiz); ?>assets/img/i_ciafi.png" alt="">
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

    modulosUsuarios();
    
    if (localStorage.url<?php echo(PROYECTO) ?> == null) {
      $("#object-contenido").attr("data", "modulos/");
    }else{
      $("#object-contenido").attr("data", localStorage.url<?php echo(PROYECTO) ?>);
    }
    
    if (localStorage.moduloActual<?php echo(PROYECTO) ?> != null) {
      $(".link").removeClass("active");
      $('.modulo' + localStorage.moduloActual<?php echo(PROYECTO) ?>).addClass("active");
      /* $(".modulo" + $('.modulo' + localStorage.moduloActual<?php echo(PROYECTO) ?>).data("modulopadre")).addClass("active"); */
      $('#tituloPagina').html(localStorage.moduloActual<?php echo(PROYECTO) ?> + ' | SEnglish');
    }

    $(document).on("click", ".link", function(){
      let moduloPadre = $(this).data('modulopadre');
      $(".link").removeClass("active");
      $(this).addClass("active");
      localStorage.moduloActual<?php echo(PROYECTO) ?> = $(this).data('modulo');
      $('#tituloPagina').html($(this).data('modulo') + ' | SEnglish');
      if (moduloPadre != 0) {
        $(".modulo" + $(this).data("modulopadre")).addClass("active");
      }
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
        if (data.success) {
          $("#modulos").empty();
          $("#modulos").append(`
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
          `);
          cargarMenu(data.msj);
        }
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
          /* $(".modulo" + $('.modulo' + localStorage.moduloActual<?php echo(PROYECTO) ?>).data("modulopadre")).addClass("active"); */
          $('#tituloPagina').html(localStorage.moduloActual<?php echo(PROYECTO) ?> + ' | SEnglish');
        }
      }
    });
  }

  function cargarMenu(data, nivel = 0, moduloPadre = '0'){
    let modH = '';
    let moduloPadre2 = moduloPadre;
    for (let i = 0; i < data.length; i++) {
      if (typeof data[i].hijos !== 'undefined') {
        if(nivel == 0){
          moduloPadre = data[i].tag;
        }
        modH += `
          <li class="nav-item has-treeview">
            <a href="<?php $ruta_raiz ?>modulos/${data[i].ruta}" data-modulopadre="${moduloPadre}" data-modulo="${data[i].tag}" target="object-contenido" class="nav-link link modulo${data[i].tag}">
              <i class="nav-icon ${data[i].icono}"></i>
              <p>
                ${data[i].tag}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
        `;

        modH += cargarMenu(data[i].hijos, (nivel+1), moduloPadre);

        modH += `
            </ul>
          </li>
        `;
      }else{
        modH +=`
          <li class="nav-item">
            <a href="<?php $ruta_raiz ?>modulos/${data[i].ruta}" data-modulopadre="${moduloPadre2}" data-modulo="${data[i].tag}" target="object-contenido" class="nav-link link modulo${data[i].tag}">
              <i class="nav-icon ${data[i].icono}"></i>
              <p>${data[i].tag}</p>
            </a>
          </li>
        `;
      }

    }
    if(nivel === 0){
      $("#modulos").append(modH);
    }else{
      return modH;
    }
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