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

  require_once($ruta_raiz . 'clases/funciones_generales.php');
  require_once($ruta_raiz . 'clases/Session.php');
  
  class Libreria{
    private $cadena_libreria;
    private $ruta_libreria;
    private $ruta_iconos;

    public function __construct(){
      $this->cadena_libreria='';
      $this->ruta_libreria= RUTA_RAIZ .'librerias/';
      $this->ruta_iconos= RUTA_RAIZ  .'assets/img/';
    }

    public function metaTagsRequired(){
      $this->cadena_libreria = '<!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">';  
      return $this->cadena_libreria;
    }

    public function iconoPag(){
      $this->cadena_libreria = '<link rel="shortcut icon" href="' . $this->ruta_iconos . 'favicon.ico">';
      return($this->cadena_libreria);
    }

    public function bootstrap(){
      $this->cadena_libreria = '
  <link rel="stylesheet" type="text/css" href="' . $this->ruta_libreria .'bootstrap/css/bootstrap.min.css">
  <script type="text/javascript" src="'. $this->ruta_libreria .'bootstrap/js/bootstrap.bundle.min.js"></script>';
      return($this->cadena_libreria);
    }

    public function jquery(){
      $this->cadena_libreria = '
  <script type="text/javascript" src="'. $this->ruta_libreria .'jquery/jquery-3.4.1.min.js"></script><script type="text/javascript"></script>';
      return($this->cadena_libreria);
    }

    public function jqueryUI(){
      $this->cadena_libreria = '
      <script src="'.$this->ruta_libreria.'jquery-ui/jquery-ui.min.js"></script>
			<script src="'.$this->ruta_libreria.'jquery-ui/jquery-ui-es.js"></script>
			<link rel="stylesheet" href="'.$this->ruta_libreria.'jquery-ui/jquery-ui.min.css" />';
      return($this->cadena_libreria);
      
    }

    public function jqueryValidate($validar = 1, $element = "form-group"){
      $this->cadena_libreria = '
  <script type="text/javascript" src="'. $this->ruta_libreria .'jquery-validate/jquery.validate.min.js"></script>
  <script type="text/javascript" src="'. $this->ruta_libreria .'jquery-validate/localization/messages_es.min.js"></script>
  <script>
    $(function(){
      jQuery.validator.setDefaults({
        debug: true,
        errorElement: "em",
        errorPlacement: function (error, element) {
          error.addClass("invalid-feedback");
          element.closest(".' . $element .'").append(error);
        },
        highlight: function (element, errorClass, validClass) {
          $(element).addClass("is-invalid");
          $(element).removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass) {
          $(element).removeClass("is-invalid");
          $(element).addClass("is-valid");
        }
      });';
  if ($validar == 1) {
    $this->cadena_libreria .= '$("form").validate();';
  }
  $this->cadena_libreria .= '
    });
  </script>';
      return($this->cadena_libreria); 
    }

    public function alertify(){
      $this->cadena_libreria = '
  <!-- Alertify - Tema de Bootstrap -->
  <link rel="stylesheet" href="'. $this->ruta_libreria .'alertifyjs/css/alertify.min.css?1"/>
  <link rel="stylesheet" href="'. $this->ruta_libreria .'alertifyjs/css/themes/bootstrap.min.css?1"/>
  <script type="text/javascript" src="'. $this->ruta_libreria .'alertifyjs/alertify.min.js?1"></script>
  <script type="text/javascript">
    //override defaults
    alertify.defaults.transition = "slide";
    alertify.defaults.theme.ok = "btn btn-primary";
    alertify.defaults.theme.cancel = "btn btn-danger";
    alertify.defaults.theme.input = "form-control";
  </script>';
      return($this->cadena_libreria);
    }

    public function datatables(){
      $this->cadena_libreria = '
  <!-- Data Tables -->
  <link rel="stylesheet" href="'. $this->ruta_libreria .'dataTables/datatables.min.css">  
  <script src="'. $this->ruta_libreria .'dataTables/datatables.min.js" charset="utf-8"></script>';
      return($this->cadena_libreria); 
    }

    public function fontAwesome(){
      $this->cadena_libreria = '
  <!-- Font Awesome -->
  <link rel="stylesheet" href="'. $this->ruta_libreria .'fontawesome/css/all.css"/>';
      return($this->cadena_libreria); 
    }

    public function bootstrapTempusDominus(){
      $this->cadena_libreria = '
      <!-- ================ Iconos de Font Awesome 4 ========================== -->
      <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.css" />
    <!-- ==================== Bootstrap Tempus Dominus ===================== -->
    <link rel="stylesheet" href="' . $this->ruta_libreria . 'tempus-dominus/css/tempusdominus-bootstrap-4.min.css"/>
    
    <script type="text/javascript" src="' . $this->ruta_libreria . 'tempus-dominus/js/tempusdominus-bootstrap-4.min.js"></script>
    <script type="text/javascript" src="' . $this->ruta_libreria . 'tempus-dominus/js/underscore-min.js"></script>';
    return $this->cadena_libreria;
    }

    public function bsCustomFileInput(){
      //Este nos ayuda con los input fila en boostrap se inicia como $(function(){bsCustomFileInput.init();});
      $this->cadena_libreria = '
      <!-- bs-custom-file-input -->
      <script type="text/javascript" src="' . $this->ruta_libreria . 'bs-custom-file-input/bs-custom-file-input.min.js"></script>
      <script>
        $(function(){bsCustomFileInput.init();});
      </script>';
      return $this->cadena_libreria;
    }

    public function proyecto(){
      $this->cadena_libreria = '
      <!-- Intranet -->
      <link rel="stylesheet" href="' . $this->ruta_libreria . 'proyecto/proyecto.css"/>
      <script type="text/javascript" src="' . $this->ruta_libreria . 'proyecto/proyecto.js"></script>';
      return $this->cadena_libreria;
    }

    public function fullCalendar(){
      $this->cadena_libreria = '
      <!-- Full Calendar -->
      <link href="' . $this->ruta_libreria . 'fullcalendar/core/main.css" rel="stylesheet" />
      <link href="' . $this->ruta_libreria . 'fullcalendar/daygrid/main.css" rel="stylesheet" />
      <link href="' . $this->ruta_libreria . 'fullcalendar/timegrid/main.css" rel="stylesheet" />
      <script src="' . $this->ruta_libreria . 'fullcalendar/core/main.js"></script>
      <script src="' . $this->ruta_libreria . 'fullcalendar/core/locales-all.js"></script>
      <script src="' . $this->ruta_libreria . 'fullcalendar/interaction/main.js"></script>
      <script src="' . $this->ruta_libreria . 'fullcalendar/daygrid/main.js"></script>
      <script src="' . $this->ruta_libreria . 'fullcalendar/timegrid/main.js"></script>
      <script src="' . $this->ruta_libreria . 'fullcalendar/resource-common/main.min.js"></script>
      <script src="' . $this->ruta_libreria . 'fullcalendar/resource-daygrid/main.min.js"></script>
      <script src="' . $this->ruta_libreria . 'fullcalendar/resource-timegrid/main.min.js"></script>';
      return $this->cadena_libreria;
    }

    public function echarts(){
      $this->cadena_libreria = '
      <!-- Full Calendar -->
      <script src="' . $this->ruta_libreria . 'echarts/echarts.js?1"></script>';
      return $this->cadena_libreria;
    }

    public function jqueryForm(){
      $this->cadena_libreria = '
      <!-- JQuert Form -->
      <script src="' . $this->ruta_libreria . 'jquery.form/jquery.form.js?1"></script>';
      return $this->cadena_libreria;
    }

    public function lightbox(){
      $this->cadena_libreria = '
      <!-- Intranet -->
      <link rel="stylesheet" href="' . $this->ruta_libreria . 'lightbox/lightbox.css"/>
      <script type="text/javascript" src="' . $this->ruta_libreria . 'lightbox/lightbox.js"></script>';
      return $this->cadena_libreria;
    }


    public function bootstrapTreeView(){
      $this->cadena_libreria = '
      <!-- Arbol bootstrap -->
      <link rel="stylesheet" href="'. $this->ruta_libreria .'bootstrap-treeview/css/bootstrap-treeview.css?0"/>
      <script type="text/javascript" src="'. $this->ruta_libreria .'bootstrap-treeview/js/bootstrap-treeview.js?0"></script>';
      return($this->cadena_libreria);
    }

    public function sweetAlert2(){
      $this->cadena_libreria = '
      <!-- Sweet Alert 2 -->
      <script type="text/javascript" src="'. $this->ruta_libreria .'sweetAlert2/js/sweetAlert2.js"></script>
      <link rel="stylesheet" href="'. $this->ruta_libreria .'sweetAlert2/css/sweetalert2.css"/>';
      return($this->cadena_libreria);
    }

    public function moment(){ 
      $this->cadena_libreria = '
      <!-- Moment -->
      <script type="text/javascript" src="' . $this->ruta_libreria . 'moment/moment.js"></script>
      <script type="text/javascript" src="' . $this->ruta_libreria . 'moment/es.js"></script>';
      return($this->cadena_libreria);
    }

    public function adminLTE(){
      $this->cadena_libreria = '
      <!-- adminLTE -->
      <link rel="stylesheet" href="'. $this->ruta_libreria .'adminLTE/css/adminlte.css"/>
      <script type="text/javascript" src="' . $this->ruta_libreria . 'adminLTE/js/adminlte.js"></script>';
      return($this->cadena_libreria);
    }

    public function overlayScrollbars(){
      $this->cadena_libreria = '
      <!-- overlayScrollbars -->
      <link rel="stylesheet" href="'. $this->ruta_libreria .'overlayScrollbars/css/OverlayScrollbars.min.css"/>
      <script type="text/javascript" src="' . $this->ruta_libreria . 'overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>';
      return($this->cadena_libreria);
    }


    public function cambioPantalla(){
      $this->cadena_libreria = '
      <script type="text/javascript">
        top.$("#cargando").modal("show");
        $(function(){
          /*Mostramos los datos dentro del input file de boostrap */
          $(".custom-file-input").on("change",function(){
            $(this).next(".custom-file-label").addClass("selected").html($(this).val());
          });

          localStorage.url' . PROYECTO . ' = window.location;
          var insideIframe = window.top !== window.self;
          if(!insideIframe){
            window.location.href="' . RUTA_RAIZ . 'central";
          }
        });
      </script>';
      return $this->cadena_libreria;
    }
  }

?>