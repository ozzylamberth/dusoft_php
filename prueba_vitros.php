<?php
// Contenido.php  05/08/2002
// ----------------------------------------------------------------------

// Copyright (C) 2002 Alexander Giraldo
// Emai: alexgiraldo777@yahoo.com

// ----------------------------------------------------------------------
// Autor: Alexander Giraldo
// ----------------------------------------------------------------------

//Definir el tipo de vista a usar
$VISTA='HTML';
include 'includes/enviroment.inc.php';

	if(!IncludeClass('Vitros','Vitros')){
		echo "ERROR AL INCLUIR LA CLASE :S";
		return false;
	}
	if(class_exists('Vitros'))
	{
		$vitros = new Vitros();
		$res=$vitros->ManipulaCadena("R0000014");
		//$res=$vitros->ConsultaResultadosVitrosBD('$','051128-1');
		echo "<br>fin ";
		print_r($res);
 		echo "<br> ->";
	}

/**
* prueba socket
*/


// 			error_reporting(E_ALL);
// 			
// 			/* Permitir que el script permanezca en espera de conexiones. */
// 			set_time_limit(0);
// 			
// 			/* Habilitar vaciado de salida implicito, de modo que veamos lo que
// 			* obtenemos a medida que va llegando. */
// 			ob_implicit_flush();
// 			
// 			$direccion = '192.168.1.27';
// 			$puerto    = 10000;
// 			
// 			if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) < 0) {
// 					echo "socket_create() fall&oacute;: motivo: " . socket_strerror($sock) . "\n";
// 			}
// 			
// 			if (($ret = socket_bind($sock, $direccion, $puerto)) < 0) {
// 					echo "socket_bind() fall&oacute;: motivo: " . socket_strerror($ret) . "\n";
// 			}
// 			
// 			if (($ret = socket_listen($sock, 5)) < 0) {
// 					echo "socket_listen() fall&oacute;: motivo: " . socket_strerror($ret) . "\n";
// 			}
// 			
// 			do {
// 					if (($mens_sock = socket_accept($sock)) < 0) {
// 							echo "socket_accept() fall&oacute;: motivo " . socket_strerror($mens_sock) . "\n";
// 							break;
// 					}
// 					/* Enviar instrucciones. */
// 					$mensaje = "\nBienvenido al Servidor de Prueba PHP. \n" .
// 										"Para salir, escriba 'salir'. " .
// 										"Para detener el servidor, escriba 'detener'.\n";
// 					socket_write($mens_sock, $mensaje, strlen($mensaje));
// 			
// 					do {
// 							if (false === ($buf = socket_read($mens_sock, 2048, PHP_NORMAL_READ))) {
// 									echo "socket_read() fall&oacute;: motivo: " . socket_strerror($ret) . "\n";
// 									break 2;
// 							}
// 							if (!$buf = trim($buf)) {
// 									continue;
// 							}
// 							if ($buf == 'salir') {
// 									break;
// 							}
// 							if ($buf == 'detener') {
// 									socket_close($mens_sock);
// 									break 2;
// 							}
// 							$respuesta = "PHP: Usted dijo '$buf'.\n";
// 							socket_write($mens_sock, $respuesta, strlen($respuesta));
// 							echo "$buf\n";
// 					} while (true);
// 					socket_close($mens_sock);
// 			} while (true);
// 			
// 			socket_close($sock);

		
		
?>
