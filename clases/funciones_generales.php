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

  require_once($ruta_raiz . 'clases/define.php');

  function textoblanco($texto){
    $conv= array(" " => "");
    //Guardamos el resultado en una variable
    $textblanco = strtr($texto, $conv);
    /* Cuenta cuantos caracteres tiene el texto */
    $cont = strlen($textblanco);
    /* Retornamos la cantidad */
    return $cont;
  }

  function cadena_db_insertar($cadena){
    $cadena=htmlentities($cadena, ENT_QUOTES, "UTF-8",false);
    $cadena=htmlspecialchars_decode($cadena,ENT_NOQUOTES);		
    
    //$cadena=str_replace("'", "''", $cadena);
    
    switch (BDTYPE) {
      case 1: //mysql
        
        break;
      case 2: //oracle
        
        break;
      case 3: //sqlServer	
        
        break;
      default:
        break;
    }
      
    return($cadena);		
  }


  function cadena_db_obtener($cadena){
    $cadena=html_entity_decode($cadena);
    return($cadena);	
  }

?>