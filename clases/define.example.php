<?php 
 //Duplica este archivo y quitale el example que queda "define.php" para la ruta raiz donde esta tu proyecto y el servidor de pruebas donde los vas a redireccionar
 
 //Zona Horaria
 date_default_timezone_set('America/Bogota');

 //Esta es la ruta disco desde la raiz del servidor hasta donde se encuentran los scripts
 define("RUTA_RAIZ","/intranet/"); 

 //Se utiliza para la sesion de y url del proyecto
 define("PROYECTO", "intranet");

 //conexion general a bd, son las contantes que usa la clase Bd por defecto
 /*
  BDTYPE
		1 mysql
		2 oracle
		3 sql server

  */
  define("BDNAME","intranet");
  define("BDSERVER","localhost");
  define("BDUSER","root");
  define("BDPASS","");
  define("BDTYPE", 1);
