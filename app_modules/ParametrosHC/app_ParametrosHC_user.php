
<?php

/**
* Modulo de Parametros de Historia Clinica (PHP).
*
* Modulo que permite parametrizar las características de la historia cllinca
*
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* app_ParametrosHC_user.php
*
* Clase que establece un acceso a lostres modulos de historia clinica,
* que son: Promoción y Prevención, Solicitudes Frecuentes y Salud Ocupacional
**/

class app_ParametrosHC_user extends classModulo
{
	function app_ParametrosHC_user()
	{
		return true;
	}

	function main()
	{
		$this->PrincipalParaHC();
		return true;
	}

}//fin de la clase
?>
