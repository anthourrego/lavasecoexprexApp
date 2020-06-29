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


function lista(){
  $table      = 'gastos_tipo';
  // Table's primary key
  $primaryKey = 'id';
  // indexes
  $columns = array(
              array( 'db' => 'gt.id',               'dt' => 'id',             'field' => 'id' ),
              array( 'db' => 'gt.nombre',           'dt' => 'nombre',         'field' => 'nombre' ),
              array( 'db' => 'gt.fecha_creacion',   'dt' => 'fecha_creacion', 'field' => 'fecha_creacion' ),
              array( 'db' => 'gt.estado',           'dt' => 'estado',         'field' => 'estado' ),
              array( 'db' => 'u.usuario',           'dt' => 'creador',        'field' => 'creador', 'as' => 'creador' )
            );
    
  $sql_details = array(
                  'user' => BDUSER,
                  'pass' => BDPASS,
                  'db'   => BDNAME,
                  'host' => BDSERVER
                );
      
  $joinQuery = "FROM {$table} AS gt INNER JOIN usuarios AS u ON gt.fk_creador = u.id";
  $extraWhere= "gt.estado = " . $_REQUEST["estado"];
  $groupBy = "";
  $having = "";
  return json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having));
}

function crear(){
  $db = new Bd();
  $db->conectar();
  $resp = array();
  global $usuario;

  if (validarNombre($_POST["nombre"]) == 0) {
    $datos = array(
              ":nombre" => $_POST["nombre"], 
              ":fecha_creacion" => date("Y-m-d H:i:s"), 
              ":fk_creador" => $usuario["id"], 
              ":estado" => 1 
            );

    $id_registro = $db->sentencia("INSERT INTO gastos_tipo (nombre, fecha_creacion, fk_creador, estado) VALUES (:nombre, :fecha_creacion, :fk_creador, :estado)", $datos);
  
    if ($id_registro > 0) {
      $db->insertLogs("gastos_tipo", $id_registro, "Se crea item gasto {$_POST["nombre"]}", $usuario["id"]);
      $resp['success'] = true;
      $resp['msj'] = "El item {$_POST["nombre"]} se ha creado";
    } else {
      $resp['success'] = false;
      $resp['msj'] = 'Error al realizar el registro';
    }
  }else{
    $resp['success'] = false;
    $resp['msj'] = "El nombre <b>{$_POST["nombre"]}</b> ya se encuentra en uso";
  }

  $db->desconectar();

  return json_encode($resp);
}

function validarNombre($nombre, $id = 0){
  $db = new Bd();
  $db->conectar();
  $resp = 0;

  if ($id == 0) {
    $verificar = $db->consulta("SELECT nombre FROM gastos_tipo WHERE nombre = :nombre", array(":nombre" => $nombre));
  } else {
    $verificar = $db->consulta("SELECT nombre FROM gastos_tipo WHERE nombre = :nombre AND id != :id", array(":nombre" => $nombre, ":id" => $id));
  }
  
  if ($verificar["cantidad_registros"] > 0) {
    $resp = $verificar["cantidad_registros"];
  }

  $db->desconectar();

  return $resp;
}

function cambiarEstado(){
  global $usuario;
  $db = new Bd();
  $db->conectar();
  
  $estado = $_POST["estado"] == 1 ? 'inhabilita' : 'habilita';

  $array = array(
    ":id" => $_POST["id"],
    ":estado" => ($_POST["estado"] == 1 ? 0 : 1),
  );

  $db->sentencia("UPDATE gastos_tipo SET estado = :estado WHERE id = :id", $array);
  $db->insertLogs("gastos_tipo", $_POST["id"], "Se " . $estado ." el item {$_POST['nombre']}", $usuario["id"]);

  $db->desconectar();

  return json_encode(1);
}


function editar(){
  global $usuario;
  $db = new Bd();
  $resp = array();
  $db->conectar();
  $datosItem = datos($_POST["id"]);

  if ($datosItem != 0) {
    if ($datosItem['nombre'] != $_POST["nombre"]) {
      if (validarNombre($_POST["nombre"], $_POST["id"]) == 0) {
        $datos = array(
                  ":id" => $_POST["id"],
                  ":nombre" => $_POST["nombre"]
                );
        
        $db->sentencia("UPDATE gastos_tipo SET nombre = :nombre WHERE id = :id", $datos);
  
        $db->insertLogs("gastos_tipo", $_POST["id"], "Se edita el item {$_POST['nombre']}", $usuario["id"]);
  
        $resp["success"] = true;
        $resp["msj"] = "El item se ha actualiza correctamente";
      } else {
        $resp["success"] = false;
        $resp["msj"] = "El nombre <b>{$_POST['nombre']}</b> ya se encuentra en uso";
      }
    }else{
      $resp["success"] = false;
      $resp["msj"] = "Por favor realize algún cambio";
    }
  }else{
    $resp["success"] = false;
    $resp["msj"] = "El item no es valido";
  }
  $db->desconectar();
  return json_encode($resp);
}

function datos($id){
  $db = new Bd();
  $db->conectar();
  $resp = 0;

  $datos = $db->consulta("SELECT * FROM gastos_tipo WHERE id = :id", array(":id" => $id));

  if ($datos["cantidad_registros"] == 1) {
    $resp = $datos[0];
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