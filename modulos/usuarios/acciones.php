<?php
@session_start();
header("Access-Control-Allow-Origin:*");
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

require($ruta_raiz . "clases/funciones_generales.php");
require($ruta_raiz . "clases/Conectar.php");
require($ruta_raiz . "clases/Session.php");

function sessionActiva(){
  $session = new Session();
  if ($session->exist('usuario') == true) {
    return 1;
  }else{
    return 0;
  }
}

function iniciarSesion(){
  $db = new Bd();
  $db->conectar();
  $resp = array();
  $pass = cadena_db_insertar($_POST['password']);

  $usuario = $db->consulta('SELECT * FROM usuarios WHERE estado = 1 AND usuario = :usuario', array(':usuario' => $_POST["usuario"]));

  if ($usuario['cantidad_registros'] == 1) {
    if(password_verify($pass, $usuario[0]['password'])){
      $session = new Session();

      $array_session_usuario = array();
      $array_session_usuario["id"] = $usuario[0]['id'];
      $array_session_usuario["usuario"] = $usuario[0]['usuario'];
      $array_session_usuario["password"] = $usuario[0]['password'];

      $session->set('usuario', $array_session_usuario);

      $resp['success'] = true;
      $resp['msj'] = 'Iniciar sesión'; 
    }else{
      $resp['success'] = false;
      $resp['msj'] = 'Contraseña incorrecta';
    }
  }else{
    $resp['success'] = false;
    $resp['msj'] = 'Correo y/o Contraseña son incorrectos';
  }

  $db->desconectar();

  return json_encode($resp);
}

if(@$_REQUEST['accion']){
  if(function_exists($_REQUEST['accion'])){
    echo($_REQUEST['accion']());
  }else{
    echo 'Accion '.$_REQUEST['accion'].' no Existe';
  }
}else{
  echo 'No se ha seleccionado alguna acción';
}