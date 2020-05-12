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

	require_once($ruta_raiz . "clases/funciones_generales.php");
	require_once($ruta_raiz . "clases/Conectar.php");
	require_once($ruta_raiz . "clases/Session.php");

	class Permisos extends Bd{

		function __construct(){
			parent::__construct(); //Inicializamos la Bd
		}

		/* function validarPermisoPadre($user, $permiso){
			$this->conectar();

			$permisos = $this->consulta("SELECT * FROM mandino_permisos INNER JOIN mandino_permisos_usuarios ON mandino_permisos.mp_id = mandino_permisos_usuarios.fk_mp WHERE mandino_permisos.fk_mp = :fk_mp AND mandino_permisos_usuarios.fk_u = :fk_u", array(":fk_mp" => $permiso, ":fk_u" => $user));

			$this->desconectar();

			if ($permisos['cantidad_registros'] > 0) {
				return 1;
			}else{
				return 0;
			}
		}
 */
		function validarPermiso($user, $modulo){
			$this->conectar();

			$permisos = $this->consulta("SELECT * FROM usuarios_modulos AS um INNER JOIN modulos AS m ON um.fk_modulo = m.id WHERE um.fk_usuario = :id_usuario AND m.nombre = :modulo AND m.estado = 1 AND um.estado = 1", array(":modulo" => $modulo, ":id_usuario" => $user));

			$this->desconectar();
		
			if ($permisos['cantidad_registros'] == 1) {
				return 1;
			}else{
				return 0;
			}
		}
	}
?>