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
require($ruta_raiz . "clases/SSP.php");
require($ruta_raiz . "clases/Session.php");

$session = new Session();

$usuario = $session->get("usuario");

function arbolModulos($mod = 0){
  $arbol = array();
  $db = new Bd();
  $db->conectar();

  $modulos = $db->consulta("SELECT * FROM modulos WHERE fk_modulo = :fk_modulo AND estado = 1", array(":fk_modulo" => $mod));

  for ($i=0; $i < $modulos["cantidad_registros"]; $i++) { 
    
    $hijos = $db->consulta("SELECT * FROM modulos WHERE fk_modulo = :fk_modulo AND estado = 1", array(":fk_modulo" => $modulos[$i]["id"]));

    if ($hijos["cantidad_registros"] > 0) {
      $arbol[] = array(
                "idModulo" => $modulos[$i]["id"],
                "text" => $modulos[$i]["tag"],
                "nombre" => $modulos[$i]["nombre"],
                "icono" => $modulos[$i]["icono"],
                "ruta" => $modulos[$i]["ruta"],
                "fk_modulo_tipo" => $modulos[$i]["fk_modulo_tipo"],
                "creador" => $modulos[$i]["fk_creador"],
                "fk_modulo" => $modulos[$i]["fk_modulo"],
                "fechaCreacion" => $modulos[$i]["fecha_creacion"], 
                "tags" => [$hijos['cantidad_registros']],
                "nodes" => arbolModulos($modulos[$i]["id"])
              );
    }else {
      $arbol[] = array(
                "idModulo" => $modulos[$i]["id"],
                "text" => $modulos[$i]["tag"],
                "nombre" => $modulos[$i]["nombre"],
                "icono" => $modulos[$i]["icono"],
                "ruta" => $modulos[$i]["ruta"],
                "fk_modulo_tipo" => $modulos[$i]["fk_modulo_tipo"],
                "creador" => $modulos[$i]["fk_creador"],
                "fk_modulo" => $modulos[$i]["fk_modulo"],
                "fechaCreacion" => $modulos[$i]["fecha_creacion"],
              );
    }
  }

  $db->desconectar();

  if ($mod == 0) {
    return json_encode($arbol);
  } else {
    return $arbol;
  }
}

function listaTipoModulos(){
  $db = new Bd();
  $db->conectar();
  $resp = [];

  $datos = $db->consulta("SELECT * FROM modulo_tipo WHERE estado = 1");

  if ($datos["cantidad_registros"] > 0) {
    $resp["success"] = true;
    $resp["msj"] = $datos; 
  } else {
    $resp["success"] = false;
    $resp["msj"] = "No se ha encontrado datos"; 
  }

  $db->desconectar();

  return json_encode($resp);
}

function listaModulos(){
  $db = new Bd();
  $db->conectar();
  $resp = [];

  $datos = $db->consulta("SELECT * FROM modulos WHERE estado = 1");

  if ($datos["cantidad_registros"] > 0) {
    $resp["success"] = true;
    $resp["msj"] = $datos;
  } else {
    $resp["success"] = false;
    $resp["msj"] = "No se han encontrado datos";
  }

  $db->desconectar();

  return json_encode($resp);
}

function crearModulo(){
  global $usuario;
  $db = new Bd();
  $db->conectar();
  $resp = [];
  $icono = NULL;
  $ruta = NULL;
  $nombre = cadena_db_insertar($_POST["nombre"]);

  if (@$_POST['icono'] && @$_POST['ruta']) {
    $icono = $_POST['icono'];
    $ruta = $_POST['ruta'];
  }

  if (validarNombreModulo($nombre) == 0) {
    $datos = array(
              ":nombre" => $nombre, 
              ":tag" => cadena_db_insertar($_POST["tag"]), 
              ":icono" => $icono, 
              ":ruta" => $ruta, 
              ":fk_modulo" => $_POST["selectModulos"], 
              ":fecha_creacion" => date("Y-m-d H:i:s"), 
              ":fk_creador" => $usuario["id"], 
              ":fk_modulo_tipo" => $_POST["modulo-tipo"], 
              ":estado" => 1
            );

    $id_registro = $db->sentencia("INSERT INTO modulos (nombre, tag, icono, ruta, fk_modulo, fecha_creacion, fk_creador, fk_modulo_tipo, estado) VALUES (:nombre, :tag, :icono, :ruta, :fk_modulo, :fecha_creacion, :fk_creador, :fk_modulo_tipo, :estado)", $datos);
    
    if ($id_registro) {
      $db->insertLogs("modulos", $id_registro, "Se ha creado el módulo {$nombre}", $usuario["id"]);
  
      $resp["success"] = true;
      $resp["msj"] = "Se ha creado el módulo {$nombre} correctamente";
    }else{
      $resp["success"] = false;
      $resp["msj"] = "Error al crear el modulo.";
    }
  } else {
    $resp["success"] = false;
    $resp["msj"] = "El nombre <b>{$nombre}</b> ya se enecuntra en uso.";
  }

  $db->desconectar();

  return json_encode($resp);
}

function validarNombreModulo($nombre){
  $db = new Bd(); 
  $db->conectar();
  $resp = 0;

  $validar = $db->consulta("SELECT nombre FROM modulos WHERE nombre = :nombre AND estado = 1", array(":nombre" => $nombre));

  if ($validar["cantidad_registros"] > 0) {
    $resp = 1;
  }

  $db->desconectar();

  return $resp;
}

function elminarModulo(){
  global $usuario;
  $db = new Bd();
  $db->conectar();

  $db->sentencia("UPDATE modulos SET estado = 0 WHERE id = :id", array(":id" => $_POST["id"]));
  $db->insertLogs("modulos", $_POST["id"], "Se inhabilita el módulo {$_POST['modulo']}", $usuario["id"]);

  $db->desconectar();

  return json_encode(1);
}

function editarModulo(){
  global $usuario;
  $db = new Bd();
  $resp = array();
  $db->conectar();
  $datosModulo = datosModulo($_POST["idModulo"]);
  $icono = NULL;
  $ruta = NULL;
  $tag = cadena_db_insertar($_POST["tag"]);
  if (@$_POST["icono"] && @$_POST["ruta"]) {
    $icono = $_POST["icono"];
    $ruta = $_POST["ruta"];
  }

  if ($datosModulo != 0) {
    if ($datosModulo['fk_modulo'] != $_POST["modPadre"] || $datosModulo['fk_modulo_tipo'] != $_POST["modulo-tipo"] || $datosModulo['tag'] != $tag || $datosModulo['icono'] != $icono || $datosModulo['ruta'] != $ruta) {
      
      $datos = array(
                ":id" => $_POST["idModulo"],
                ":tag" => $tag,
                ":icono" => $icono,
                ":ruta" => $ruta,
                ":fk_modulo" => $_POST["modPadre"],
                ":fk_modulo_tipo" => $_POST["modulo-tipo"],
              );

      $db->sentencia("UPDATE modulos SET tag = :tag, icono = :icono, ruta = :ruta, fk_modulo = :fk_modulo, fk_modulo_tipo = :fk_modulo_tipo WHERE id = :id", $datos);

      $db->insertLogs("modulos", $_POST["idModulo"], "Se edita el modulos {$datosModulo['nombre']}", $usuario["id"]);

      $resp["success"] = true;
      $resp["msj"] = "El usuario se ha actualiza correctamente";
    }else{
      $resp["success"] = false;
      $resp["msj"] = "Por favor realize algún cambio";
    }
  }else{
    $resp["success"] = false;
    $resp["msj"] = "El usuario no es valido";
  }


  $db->desconectar();
  return json_encode($resp);
}

function datosModulo($id){
  $db = new Bd();
  $db->conectar();
  $resp = 0;

  $usuario = $db->consulta("SELECT * FROM modulos WHERE id = :id", array(":id" => $id));

  if ($usuario["cantidad_registros"] == 1) {
    $resp = $usuario[0];
  }

  $db->desconectar();
  return $resp;
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