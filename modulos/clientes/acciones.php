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
  $table      = 'clientes';
  // Table's primary key
  $primaryKey = 'id';
  // indexes
  $columns = array(
              array( 'db' => 'c.id',               'dt' => 'id',             'field' => 'id' ),
              array( 'db' => 'c.nombre',           'dt' => 'nombre',         'field' => 'nombre' ),
              array( 'db' => 'c.telefono',         'dt' => 'telefono',       'field' => 'telefono' ),
              array( 'db' => 'c.direccion',        'dt' => 'direccion',      'field' => 'direccion' ),
              array( 'db' => 'c.fecha_creacion',   'dt' => 'fecha_creacion', 'field' => 'fecha_creacion' ),
              array( 'db' => 'c.estado',           'dt' => 'estado',         'field' => 'estado' ),
              array( 'db' => 'u.usuario',           'dt' => 'creador',       'field' => 'creador', 'as' => 'creador' )
            );
    
  $sql_details = array(
                  'user' => BDUSER,
                  'pass' => BDPASS,
                  'db'   => BDNAME,
                  'host' => BDSERVER
                );
      
  $joinQuery = "FROM {$table} AS c INNER JOIN usuarios AS u ON c.fk_creador = u.id";
  $extraWhere= "c.estado = " . $_REQUEST["estado"];
  $groupBy = "";
  $having = "";
  return json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having));
}

function crear(){
  $db = new Bd();
  $db->conectar();
  $resp = array();
  global $usuario;

  if (validar($_POST["telefono"]) == 0) {
    $datos = array(
              ":nombre" => $_POST["nombre"],
              ":telefono" => $_POST["telefono"], 
              ":direccion" => $_POST["direccion"], 
              ":fecha_creacion" => date("Y-m-d H:i:s"), 
              ":fk_creador" => $usuario["id"], 
              ":estado" => 1 
            );

    $id_registro = $db->sentencia("INSERT INTO clientes (nombre, telefono, direccion, fecha_creacion, fk_creador, estado) VALUES (:nombre, :telefono, :direccion, :fecha_creacion, :fk_creador, :estado)", $datos);
  
    if ($id_registro > 0) {
      $db->insertLogs("clientes", $id_registro, "Se crea cliente {$_POST["nombre"]}", $usuario["id"]);
      $resp['success'] = true;
      $resp['msj'] = "El cliente {$_POST["nombre"]} se ha creado";
    } else {
      $resp['success'] = false;
      $resp['msj'] = 'Error al realizar el registro';
    }
  }else{
    $resp['success'] = false;
    $resp['msj'] = "El teléfono <b>{$_POST["telefono"]}</b> ya se encuentra en uso";
  }

  $db->desconectar();

  return json_encode($resp);
}

function validar($telefono, $id = 0){
  $db = new Bd();
  $db->conectar();
  $resp = 0;

  if ($id == 0) {
    $verificar = $db->consulta("SELECT telefono FROM clientes WHERE telefono = :telefono", array(":telefono" => $telefono));
  } else {
    $verificar = $db->consulta("SELECT telefono FROM clientes WHERE telefono = :telefono AND id != :id", array(":telefono" => $telefono, ":id" => $id));
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

  $db->sentencia("UPDATE clientes SET estado = :estado WHERE id = :id", $array);
  $db->insertLogs("clientes", $_POST["id"], "Se " . $estado ." el cliente {$_POST['nombre']}", $usuario["id"]);

  $db->desconectar();

  return json_encode(1);
}

function editar(){
  global $usuario;
  $db = new Bd();
  $resp = array();
  $db->conectar();
  $datos = datos($_POST["id"]);

  if ($datos != 0) {
    if ($datos['nombre'] != $_POST["nombre"] || $datos['telefono'] != $_POST["telefono"] || $datos['direccion'] != $_POST["direccion"]) {
      if (validar($_POST["telefono"], $_POST["id"]) == 0) {

        $datos = array(
                  ":id" => $_POST["id"],
                  ":nombre" => $_POST["nombre"],
                  ":telefono" => $_POST["telefono"],
                  ":direccion" => $_POST["direccion"]
                );
        
        $db->sentencia("UPDATE clientes SET nombre = :nombre, telefono = :telefono, direccion = :direccion WHERE id = :id", $datos);
  
        $db->insertLogs("clientes", $_POST["id"], "Se edita el cliente {$_POST['nombre']}", $usuario["id"]);
  
        $resp["success"] = true;
        $resp["msj"] = "El cliente se ha actualiza correctamente";
      } else {
        $resp["success"] = false;
        $resp['msj'] = "El teléfono <b>{$_POST["telefono"]}</b> ya se encuentra en uso";
      }
    }else{
      $resp["success"] = false;
      $resp["msj"] = "Por favor realize algún cambio";
    }
  }else{
    $resp["success"] = false;
    $resp["msj"] = "El cliente no es valido";
  }
  $db->desconectar();
  return json_encode($resp);
}

function datos($id){
  $db = new Bd();
  $db->conectar();
  $resp = 0;

  $datos = $db->consulta("SELECT * FROM clientes WHERE id = :id", array(":id" => $id));

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