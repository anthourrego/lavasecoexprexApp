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

function crearUsuario(){
  $db = new Bd();
  $db->conectar();
  $resp = array();
  global $usuario;

  if (validarUsuario($_POST["usuario"]) == 0) {
    $password = cadena_db_insertar($_POST['pass']);
    $repassword = cadena_db_insertar($_POST['rePass']);

    if ($password == $repassword) {
      $password = encriptarPass($password);

      $id_registro = $db->sentencia("INSERT INTO usuarios (usuario, nombres, apellidos, correo, password, estado, fecha_creacion, fk_creador) VALUES (:usuario, :nombres, :apellidos, :correo, :password, :estado, :fecha_creacion, :fk_creador)", 
      array(":usuario" => $_POST["usuario"], 
            ":nombres" => $_POST["nombre"], 
            ":apellidos" => $_POST["apellidos"], 
            ":correo" => $_POST["correo"], 
            ":password" => $password, 
            ":estado" => 1, 
            ":fecha_creacion" => date("Y-m-d H:i:s"), 
            ":fk_creador" => $usuario["id"]));

      if ($id_registro > 0) {
        $db->insertLogs("usuarios", $id_registro, "Se crea el usuario {$_POST['usuario']}", $usuario["id"]);
        $resp['success'] = true;
        $resp['msj'] = 'Se ha registrado correctamente.';
      } else {
        $resp['success'] = false;
        $resp['msj'] = 'Error al realizar el registro.';
      }
      
    }else{
      $resp['success'] = false;
      $resp['msj'] = 'Las contraseñas no coinciden.';
    }

  }else{
    $resp['success'] = false;
    $resp['msj'] = 'El usuario <b>' . $_REQUEST["usuario"] . '</b> ya se encuentra en uso.';
  }

  $db->desconectar();
  return json_encode($resp);
}

function validarUsuario($usuario){
  $db = new Bd();
  $db->conectar();
  $resp = 0;

  $verificar = $db->consulta("SELECT usuario FROM usuarios WHERE usuario = :usuario", array(":usuario" => $usuario));
  
  if ($verificar["cantidad_registros"] > 0) {
    $resp = $verificar["cantidad_registros"];
  }

  $db->desconectar();

  return $resp;
}

function listaUsuarios(){
  $table      = 'usuarios';
  // Table's primary key
  $primaryKey = 'id';
  // indexes
  $columns = array(
              array( 'db' => '`u1`.`id`',                  'dt' => 'id',              'field' => 'id' ),
              array( 'db' => '`u1`.`usuario`',             'dt' => 'usuario',         'field' => 'usuario' ),
              array( 'db' => '`u1`.`nombres`',             'dt' => 'nombres',         'field' => 'nombres' ),
              array( 'db' => '`u1`.`apellidos`',           'dt' => 'apellidos',       'field' => 'apellidos' ),
              array( 'db' => '`u1`.`correo`',              'dt' => 'correo',          'field' => 'correo' ),
              array( 'db' => '`u1`.`fecha_creacion`',      'dt' => 'fecha_creacion',  'field' => 'fecha_creacion' ),
              array( 'db' => '`u2`.`usuario`',             'dt' => 'creador',         'field' => 'creador', 'as' => 'creador' )
            );
    
  $sql_details = array(
                  'user' => BDUSER,
                  'pass' => BDPASS,
                  'db'   => BDNAME,
                  'host' => BDSERVER
                );
      
  $joinQuery = "FROM `{$table}` AS `u1` INNER JOIN `{$table}` AS `u2` ON `u1`.`fk_creador` = `u2`.`id`";
  $extraWhere= "`u1`.`estado` = 1";
  $groupBy = "";
  $having = "";
  return json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having));
}

function inHabilitarUsuario(){
  global $usuario;
  $db = new Bd();
  $db->conectar();

  $db->sentencia("UPDATE usuarios SET estado = 0 WHERE id = :id", array(":id" => $_POST["id"]));
  $db->insertLogs("usuarios", $_POST["id"], "Se inhabilita el usuario {$_POST['usuario']}", $usuario["id"]);

  $db->desconectar();

  return json_encode(1);
}

function cambiarPass(){
  global $usuario;
  $db = new Bd();
  $db->conectar();
  $password = cadena_db_insertar($_POST['cambioPass']);
  $repassword = cadena_db_insertar($_POST['cambioRePass']);
  $resp = array();

  if ($password == $repassword) {
    $password = encriptarPass($password);

    $db->sentencia("UPDATE usuarios SET password = :password WHERE id = :id", array(":id" => $_POST["id"], ":password" => $password));
    $db->insertLogs("usuarios", $_POST["id"], "Se cambia la contrase del usuario {$_POST['usuario']}", $usuario["id"]);

    $resp['success'] = true;
    $resp['msj'] = 'Se ha cambiado la contraseña.';
  }else{
    $resp['success'] = false;
    $resp['msj'] = 'Las contraseñas no coinciden.';
  }

  $db->desconectar();
  return json_encode($resp);
}

function editarUsuario(){
  global $usuario;
  $db = new Bd();
  $resp = array();
  $db->conectar();
  $datosUsuario = datosUsuario($_POST["id"]);

  if ($datosUsuario != 0) {
    if ($datosUsuario['nombres'] != $_POST["nombres"] || $datosUsuario['apellidos'] != $_POST["apellidos"] || $datosUsuario['correo'] != $_POST["correo"]) {
      
      $db->sentencia("UPDATE usuarios SET nombres = :nombres, apellidos = :apellidos, correo = :correo WHERE id = :id", array(":nombres" => cadena_db_insertar($_POST["nombres"]), ":apellidos" => cadena_db_insertar($_POST["apellidos"]), ":correo" => cadena_db_insertar($_POST["correo"]), ":id" => $_POST["id"]));

      $db->insertLogs("usuarios", $_POST["id"], "Se edita el usuario {$_POST['usuario']}", $usuario["id"]);

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

function datosUsuario($id){
  $db = new Bd();
  $db->conectar();
  $resp = 0;

  $usuario = $db->consulta("SELECT * FROM usuarios WHERE id = :id", array(":id" => $id));

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