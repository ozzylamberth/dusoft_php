
<?php

/**
* Modulo de SolicitudesFrecuentes (PHP).
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* app_SolicitudesFrecuentes_admin.php
**/

class app_SolicitudesFrecuentes_admin extends classModulo
{
	function app_SolicitudesFrecuentes_admin()
	{
		return true;
	}

	function main()
	{
		$this->Menu();
		return true;
	}

	function Retornar()
	{
		$this->ReturnMetodoExterno($_SESSION['USER_ADMIN_MOD']['CONTENEDOR'], $_SESSION['USER_ADMIN_MOD']['MOD'], $_SESSION['USER_ADMIN_MOD']['TIPO'], $_SESSION['USER_ADMIN_MOD']['METODO'],array("mod"=>$_SESSION['USER_ADMIN_MOD']['MODULO']));
		return true;
	}

	function RetornarPermisos()
	{
		$this->ReturnMetodoExterno($_SESSION['USER_ADMIN_MOD']['CONTENEDOR'], $_SESSION['USER_ADMIN_MOD']['MOD'], $_SESSION['USER_ADMIN_MOD']['TIPO'], 'TraerDatos',array("tabla"=>'userpermisos_solicitudes_frecuentes',"permiso"=>'SOLICITUDES FRECUENTES'));
		return true;
	}

}//fin de la clase
?>
