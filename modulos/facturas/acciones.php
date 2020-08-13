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

function facturar(){
  $db = new Bd();
  $db->conectar();
  $resp = array();
  global $usuario;


  $datos = array(
    ":fk_cliente" => $_POST["idCliente"],
    ":total" => $_POST["total"], 
    ":abono" => $_POST["abono"], 
    ":fecha_creacion" => date("Y-m-d H:i:s"), 
    ":fk_creador" => $usuario["id"],
    ":datos_tabla" => $_POST["datosTabla"],
    ":fecha_entrega" => date("Y-m-d", strtotime($_POST["fechaEntrega"])),
    ":estado" => 1 
  );
  $id_factura = $db->sentencia("INSERT INTO facturas (fk_cliente, total, abono, fecha_creacion, fk_creador, datos_tabla, fecha_entrega, estado) VALUES (:fk_cliente, :total, :abono, :fecha_creacion, :fk_creador, :datos_tabla, :fecha_entrega, :estado)", $datos);

  if ($id_factura > 0) {
    $db->insertLogs("facturas", $id_factura, " factura numero  {$id_factura}", $usuario["id"]);
    $resp['success'] = true;
    $resp['msj'] = "factura {$id_factura} generada";
  } else {
    $resp['success'] = false;
    $resp['msj'] = 'Error al realizar el registro';
  }
  

  $db->desconectar();

  return json_encode($resp);
}

function validarNombre($nombre, $id = 0){
  $db = new Bd();
  $db->conectar();
  $resp = 0;

  if ($id == 0) {
    $verificar = $db->consulta("SELECT nombre FROM servicios WHERE nombre = :nombre", array(":nombre" => $nombre));
  } else {
    $verificar = $db->consulta("SELECT nombre FROM servicios WHERE nombre = :nombre AND id != :id", array(":nombre" => $nombre, ":id" => $id));
  }
  
  if ($verificar["cantidad_registros"] > 0) {
    $resp = $verificar["cantidad_registros"];
  }

  $db->desconectar();

  return $resp;
}

function lista(){
  $table      = 'facturas';
  // Table's primary key
  $primaryKey = 'id';
  // indexes
  $columns = array(
              array( 'db' => 'p.id',               'dt' => 'id',             'field' => 'id' ),
              array( 'db' => 'p.fk_cliente',           'dt' => 'fk_cliente',         'field' => 'fk_cliente' ),
              array( 'db' => 'p.fecha_entrega',           'dt' => 'fecha_entrega',         'field' => 'fecha_entrega' ),
              array( 'db' => 'p.fecha_creacion',   'dt' => 'fecha_creacion', 'field' => 'fecha_creacion' ),
              array( 'db' => 'p.total',           'dt' => 'total',         'field' => 'total' ),
              array( 'db' => 'p.abono',           'dt' => 'abono',         'field' => 'abono' ),
              array( 'db' => 'p.estado',           'dt' => 'estado',         'field' => 'estado' ),
              array( 'db' => 'p.datos_tabla',           'dt' => 'datos_tabla',         'field' => 'datos_tabla' ),
              array( 'db' => 'u.usuario',          'dt' => 'creador',        'field' => 'creador', 'as' => 'creador' ),
              array( 'db' => 'c.telefono',          'dt' => 'telefono_cliente',  'field' => 'telefono_cliente', 'as' => 'telefono_cliente' )
            );
    
  $sql_details = array(
                  'user' => BDUSER,
                  'pass' => BDPASS,
                  'db'   => BDNAME,
                  'host' => BDSERVER
                );
      
  $joinQuery = "FROM {$table} AS p INNER JOIN usuarios AS u ON p.fk_creador = u.id INNER JOIN clientes AS c on p.fk_cliente = c.id";
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

  $db->sentencia("UPDATE facturas SET estado = :estado WHERE id = :id", $array);
  $db->insertLogs("facturas", $_POST["id"], "Se " . $estado ." la factura {$_POST["id"]}", $usuario["id"]);

  $db->desconectar();

  return json_encode(1);
}

function editarServicio(){
  global $usuario;
  $db = new Bd();
  $resp = array();
  $db->conectar();
  $datoServicio = datosServicio($_POST["id"]);

  if ($datoServicio != 0) {
    if ($datoServicio['nombre'] != $_POST["nombre"] || $datoServicio['precio'] != $_POST["precio"]) {
      if (validarNombre($_POST["nombre"], $_POST["id"]) == 0) {
        $datos = array(
                  ":id" => $_POST["id"],
                  ":nombre" => $_POST["nombre"],
                  ":precio" => $_POST["precio"]
                );
        
        $db->sentencia("UPDATE servicios SET nombre = :nombre, precio = :precio WHERE id = :id", $datos);
  
        $db->insertLogs("servicios", $_POST["id"], "Se edita el servicio {$_POST['nombre']}", $usuario["id"]);
  
        $resp["success"] = true;
        $resp["msj"] = "El servicio se ha actualiza correctamente";
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

function datosServicio($id){
  $db = new Bd();
  $db->conectar();
  $resp = 0;

  $datos = $db->consulta("SELECT * FROM servicios WHERE id = :id", array(":id" => $id));

  if ($datos["cantidad_registros"] == 1) {
    $resp = $datos[0];
  }

  $db->desconectar();
  return $resp;
}

function buscarCliente(){

  global $usuario;
  $db = new Bd();
  $db->conectar();
  $resp = array();

  $datos = $db->consulta("SELECT * FROM clientes WHERE estado = 1 and telefono = ".$_POST['telefono']);

  if ($datos["cantidad_registros"] > 0) {
    $resp['success'] = true;
    $resp['msj'] = $datos;
  } else {
    $resp['success'] = false;
    $resp['msj'] = 'no hay datos';
  }
  $db->desconectar();

  return json_encode($resp);

}

function getById(){
  global $usuario;
  $db = new Bd();
  $db->conectar();
  $resp = array();

  $datos = $db->consulta("SELECT * FROM clientes WHERE id = ".$_GET['id']);

  if ($datos["cantidad_registros"] > 0) {
    $resp['success'] = true;
    $resp['msj'] = $datos;
  } else {
    $resp['success'] = false;
    $resp['msj'] = 'no hay datos';
  }
  $db->desconectar();

  return json_encode($resp);
}

function traerProductos(){
  $db = new Bd();
  $db->conectar();
  $resp = array();

  $datos = $db->consulta("SELECT * FROM productos WHERE estado = 1");

  if ($datos["cantidad_registros"] > 0) {
    $resp['success'] = true;
    $resp['msj'] = $datos;
  } else {
    $resp['success'] = false;
    $resp['msj'] = 'no hay datos';
  }

  $db->desconectar();

  return json_encode($resp);

}

function traerServicios(){
  $db = new Bd();
  $db->conectar();
  $resp = array();

  $datos = $db->consulta("SELECT * FROM servicios WHERE estado = 1");

  if ($datos["cantidad_registros"] > 0) {
    $resp['success'] = true;
    $resp['msj'] = $datos;
  } else {
    $resp['success'] = false;
    $resp['msj'] = 'no hay datos';
  }

  $db->desconectar();

  return json_encode($resp);

}

function editarCliente(){
  global $usuario;
  $db = new Bd();
  $resp = array();
  $db->conectar();

  $datos = array(
    ":id" => $_POST["id"],
    ":nombre" => $_POST["nombre"],
    ":telefono" => $_POST["telefono"],
    ":direccion" => $_POST["direccion"]
  );

  $db->sentencia("UPDATE clientes SET nombre = :nombre, telefono = :telefono, direccion = :direccion WHERE id = :id", $datos);
  $db->insertLogs("clientes", $_POST["id"], "Se edita el cliente {$_POST['nombre']}", $usuario["id"]);

  $resp["success"] = true;
  $resp["msj"] = "Cliente Editado";

  $db->desconectar();
  return json_encode($resp);

}

function crearCliente(){
   $db = new Bd();
  $db->conectar();
  $resp = array();
  global $usuario;


  $datos = array(
    ":nombre" => $_POST["nombre"],
    ":telefono" => $_POST["telefono"], 
    ":direccion" => $_POST["direccion"], 
    ":fecha_creacion" => date("Y-m-d H:i:s"), 
    ":fk_creador" => $usuario["id"],
    ":estado" => 1 
  );
  $id_cliente = $db->sentencia("INSERT INTO clientes (nombre, telefono, direccion, fecha_creacion, fk_creador, estado) VALUES (:nombre, :telefono, :direccion, :fecha_creacion, :fk_creador, :estado)", $datos);

  if ($id_cliente > 0) {
    $db->insertLogs("clientes", $id_cliente, " cliente creado   {$_POST['nombre']}", $usuario["id"]);
    $resp['success'] = true;
    $resp['msj'] = 'Cliente Creado';
    $resp['id_creado'] = $id_cliente;
  } else {
    $resp['success'] = false;
    $resp['msj'] = 'Error al realizar el registro';
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