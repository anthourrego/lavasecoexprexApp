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
  
  $lib = new Libreria;
?>   

<!DOCTYPE html>
<html lang="en">
  <head>
    <?php  
      echo $lib->metaTagsRequired();
      echo $lib->iconoPag();
    ?>  
    <title>404 | LavaSecoExprex</title>
    <?php  
      echo $lib->jquery();
      echo $lib->bootstrap();
      echo $lib->fontAwesome();
    ?>
    <!-- Custom fonts for this template-->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <style>
      *{
        font-family: "Nunito", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
      }

      .error {
        color: #5a5c69;
        font-size: 7rem;
        position: relative;
        line-height: 1;
        width: 12.5rem;
      }

      @-webkit-keyframes noise-anim {
        0% {
          clip: rect(32px, 9999px, 16px, 0);
        }
        5% {
          clip: rect(5px, 9999px, 24px, 0);
        }
        10% {
          clip: rect(77px, 9999px, 87px, 0);
        }
        15% {
          clip: rect(91px, 9999px, 95px, 0);
        }
        20% {
          clip: rect(74px, 9999px, 9px, 0);
        }
        25% {
          clip: rect(37px, 9999px, 32px, 0);
        }
        30% {
          clip: rect(56px, 9999px, 27px, 0);
        }
        35% {
          clip: rect(35px, 9999px, 33px, 0);
        }
        40% {
          clip: rect(89px, 9999px, 6px, 0);
        }
        45% {
          clip: rect(81px, 9999px, 77px, 0);
        }
        50% {
          clip: rect(64px, 9999px, 69px, 0);
        }
        55% {
          clip: rect(12px, 9999px, 11px, 0);
        }
        60% {
          clip: rect(59px, 9999px, 11px, 0);
        }
        65% {
          clip: rect(69px, 9999px, 59px, 0);
        }
        70% {
          clip: rect(74px, 9999px, 65px, 0);
        }
        75% {
          clip: rect(56px, 9999px, 79px, 0);
        }
        80% {
          clip: rect(80px, 9999px, 64px, 0);
        }
        85% {
          clip: rect(87px, 9999px, 29px, 0);
        }
        90% {
          clip: rect(16px, 9999px, 21px, 0);
        }
        95% {
          clip: rect(69px, 9999px, 43px, 0);
        }
        100% {
          clip: rect(75px, 9999px, 63px, 0);
        }
      }

      @keyframes noise-anim {
        0% {
          clip: rect(32px, 9999px, 16px, 0);
        }
        5% {
          clip: rect(5px, 9999px, 24px, 0);
        }
        10% {
          clip: rect(77px, 9999px, 87px, 0);
        }
        15% {
          clip: rect(91px, 9999px, 95px, 0);
        }
        20% {
          clip: rect(74px, 9999px, 9px, 0);
        }
        25% {
          clip: rect(37px, 9999px, 32px, 0);
        }
        30% {
          clip: rect(56px, 9999px, 27px, 0);
        }
        35% {
          clip: rect(35px, 9999px, 33px, 0);
        }
        40% {
          clip: rect(89px, 9999px, 6px, 0);
        }
        45% {
          clip: rect(81px, 9999px, 77px, 0);
        }
        50% {
          clip: rect(64px, 9999px, 69px, 0);
        }
        55% {
          clip: rect(12px, 9999px, 11px, 0);
        }
        60% {
          clip: rect(59px, 9999px, 11px, 0);
        }
        65% {
          clip: rect(69px, 9999px, 59px, 0);
        }
        70% {
          clip: rect(74px, 9999px, 65px, 0);
        }
        75% {
          clip: rect(56px, 9999px, 79px, 0);
        }
        80% {
          clip: rect(80px, 9999px, 64px, 0);
        }
        85% {
          clip: rect(87px, 9999px, 29px, 0);
        }
        90% {
          clip: rect(16px, 9999px, 21px, 0);
        }
        95% {
          clip: rect(69px, 9999px, 43px, 0);
        }
        100% {
          clip: rect(75px, 9999px, 63px, 0);
        }
      }

      .error:after {
        content: attr(data-text);
        position: absolute;
        left: 2px;
        text-shadow: -1px 0 #e74a3b;
        top: 0;
        color: #5a5c69;
        background: #f8f9fc;
        overflow: hidden;
        clip: rect(0, 900px, 0, 0);
        animation: noise-anim 2s infinite linear alternate-reverse;
      }

      @-webkit-keyframes noise-anim-2 {
        0% {
          clip: rect(12px, 9999px, 52px, 0);
        }
        5% {
          clip: rect(42px, 9999px, 39px, 0);
        }
        10% {
          clip: rect(64px, 9999px, 36px, 0);
        }
        15% {
          clip: rect(52px, 9999px, 15px, 0);
        }
        20% {
          clip: rect(79px, 9999px, 7px, 0);
        }
        25% {
          clip: rect(17px, 9999px, 41px, 0);
        }
        30% {
          clip: rect(15px, 9999px, 20px, 0);
        }
        35% {
          clip: rect(62px, 9999px, 87px, 0);
        }
        40% {
          clip: rect(94px, 9999px, 11px, 0);
        }
        45% {
          clip: rect(49px, 9999px, 10px, 0);
        }
        50% {
          clip: rect(82px, 9999px, 4px, 0);
        }
        55% {
          clip: rect(70px, 9999px, 100px, 0);
        }
        60% {
          clip: rect(62px, 9999px, 23px, 0);
        }
        65% {
          clip: rect(51px, 9999px, 56px, 0);
        }
        70% {
          clip: rect(41px, 9999px, 24px, 0);
        }
        75% {
          clip: rect(6px, 9999px, 85px, 0);
        }
        80% {
          clip: rect(96px, 9999px, 58px, 0);
        }
        85% {
          clip: rect(16px, 9999px, 24px, 0);
        }
        90% {
          clip: rect(40px, 9999px, 31px, 0);
        }
        95% {
          clip: rect(91px, 9999px, 34px, 0);
        }
        100% {
          clip: rect(87px, 9999px, 26px, 0);
        }
      }

      @keyframes noise-anim-2 {
        0% {
          clip: rect(12px, 9999px, 52px, 0);
        }
        5% {
          clip: rect(42px, 9999px, 39px, 0);
        }
        10% {
          clip: rect(64px, 9999px, 36px, 0);
        }
        15% {
          clip: rect(52px, 9999px, 15px, 0);
        }
        20% {
          clip: rect(79px, 9999px, 7px, 0);
        }
        25% {
          clip: rect(17px, 9999px, 41px, 0);
        }
        30% {
          clip: rect(15px, 9999px, 20px, 0);
        }
        35% {
          clip: rect(62px, 9999px, 87px, 0);
        }
        40% {
          clip: rect(94px, 9999px, 11px, 0);
        }
        45% {
          clip: rect(49px, 9999px, 10px, 0);
        }
        50% {
          clip: rect(82px, 9999px, 4px, 0);
        }
        55% {
          clip: rect(70px, 9999px, 100px, 0);
        }
        60% {
          clip: rect(62px, 9999px, 23px, 0);
        }
        65% {
          clip: rect(51px, 9999px, 56px, 0);
        }
        70% {
          clip: rect(41px, 9999px, 24px, 0);
        }
        75% {
          clip: rect(6px, 9999px, 85px, 0);
        }
        80% {
          clip: rect(96px, 9999px, 58px, 0);
        }
        85% {
          clip: rect(16px, 9999px, 24px, 0);
        }
        90% {
          clip: rect(40px, 9999px, 31px, 0);
        }
        95% {
          clip: rect(91px, 9999px, 34px, 0);
        }
        100% {
          clip: rect(87px, 9999px, 26px, 0);
        }
      }

      .error:before {
        content: attr(data-text);
        position: absolute;
        left: -2px;
        text-shadow: 1px 0 #4e73df;
        top: 0;
        color: #5a5c69;
        background: #f8f9fc;
        overflow: hidden;
        clip: rect(0, 900px, 0, 0);
        animation: noise-anim-2 3s infinite linear alternate-reverse;
      }

    </style>

  </head>
  <body class="vh-100 d-flex justify-content-center align-items-center">
    <!-- 404 Error Text -->
    <div class="text-center">
      <div class="error mx-auto" data-text="404">404</div>
      <p class="lead text-gray-800 mb-5">La página no existe</p>
    </div>
  </body>
</html>
