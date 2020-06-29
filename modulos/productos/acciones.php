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

function crearProducto(){
  $db = new Bd();
  $db->conectar();
  $resp = array();
  global $usuario;
  $nombre = cadena_db_insertar($_POST["nombre"]);

  if (validarNombre($nombre) == 0) {
    $datos = array(
              ":nombre" => $nombre, 
              ":precio" => $_POST["precio"], 
              ":fecha_creacion" => date("Y-m-d H:i:s"), 
              ":fk_creador" => $usuario["id"], 
              ":estado" => 1 
            );
    $id_registro = $db->sentencia("INSERT INTO productos (nombre, precio, fecha_creacion, fk_creador, estado) VALUES (:nombre, :precio, :fecha_creacion, :fk_creador, :estado)", $datos);
  
    if ($id_registro > 0) {
      $db->insertLogs("productos", $id_registro, "Se crea el prudcto {$nombre}", $usuario["id"]);
      $resp['success'] = true;
      $resp['msj'] = "El producto {$nombre} se ha creado";
    } else {
      $resp['success'] = false;
      $resp['msj'] = 'Error al realizar el registro';
    }
  }else{
    $resp['success'] = false;
    $resp['msj'] = "El nombre <b>{$nombre}</b> ya se encuentra en uso";
  }

  $db->desconectar();

  return json_encode($resp);
}

function validarNombre($nombre, $id = 0){
  $db = new Bd();
  $db->conectar();
  $resp = 0;

  if ($id == 0) {
    $verificar = $db->consulta("SELECT nombre FROM productos WHERE nombre = :nombre", array(":nombre" => $nombre));
  } else {
    $verificar = $db->consulta("SELECT nombre FROM productos WHERE nombre = :nombre AND id != :id", array(":nombre" => $nombre, ":id" => $id));
  }
  
  if ($verificar["cantidad_registros"] > 0) {
    $resp = $verificar["cantidad_registros"];
  }

  $db->desconectar();

  return $resp;
}

function lista(){
  $table      = 'productos';
  // Table's primary key
  $primaryKey = 'id';
  // indexes
  $columns = array(
              array( 'db' => 'p.id',               'dt' => 'id',             'field' => 'id' ),
              array( 'db' => 'p.nombre',           'dt' => 'nombre',         'field' => 'nombre' ),
              array( 'db' => 'p.precio',           'dt' => 'precio',         'field' => 'precio' ),
              array( 'db' => 'p.fecha_creacion',   'dt' => 'fecha_creacion', 'field' => 'fecha_creacion' ),
              array( 'db' => 'p.estado',           'dt' => 'estado',         'field' => 'estado' ),
              array( 'db' => 'u.usuario',          'dt' => 'creador',        'field' => 'creador', 'as' => 'creador' )
            );
    
  $sql_details = array(
                  'user' => BDUSER,
                  'pass' => BDPASS,
                  'db'   => BDNAME,
                  'host' => BDSERVER
                );
      
  $joinQuery = "FROM {$table} AS p INNER JOIN usuarios AS u ON p.fk_creador = u.id";
  $extraWhere= "p.estado = " . $_REQUEST["estado"];
  $groupBy = "";
  $having = "";
  return json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having));
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

  $db->sentencia("UPDATE productos SET estado = :estado WHERE id = :id", $array);
  $db->insertLogs("usuarios", $_POST["id"], "Se " . $estado ." el producto {$_POST['nombre']}", $usuario["id"]);

  $db->desconectar();

  return json_encode(1);
}


function editarProducto(){
  global $usuario;
  $db = new Bd();
  $resp = array();
  $db->conectar();
  $datosProducto = datosProducto($_POST["id"]);

  if ($datosProducto != 0) {
    if ($datosProducto['nombre'] != $_POST["nombre"] || $datosProducto['precio'] != $_POST["precio"]) {
      if (validarNombre($_POST["nombre"], $_POST["id"]) == 0) {
        $datos = array(
                  ":id" => $_POST["id"],
                  ":nombre" => $_POST["nombre"],
                  ":precio" => $_POST["precio"]
                );
        
        $db->sentencia("UPDATE productos SET nombre = :nombre, precio = :precio WHERE id = :id", $datos);
  
        $db->insertLogs("productos", $_POST["id"], "Se edita el producto {$_POST['nombre']}", $usuario["id"]);
  
        $resp["success"] = true;
        $resp["msj"] = "El producto se ha actualiza correctamente";
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
    $resp["msj"] = "El producto no es valido";
  }
  $db->desconectar();
  return json_encode($resp);
}

function datosProducto($id){
  $db = new Bd();
  $db->conectar();
  $resp = 0;

  $datos = $db->consulta("SELECT * FROM productos WHERE id = :id", array(":id" => $id));

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