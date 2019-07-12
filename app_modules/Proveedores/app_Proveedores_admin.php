
<?php

/**
* Modulo de Contratación (PHP).
*
* Modulo para el manejo de la contratación (determinar las características de los planes)
*
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* app_Contratación_admin.php
*
* Clase que establece los métodos de acceso y búsqueda de información con las opciones
* de los detalles de los planes, ajustados a las características de los servicios y de
* los clientes con los cuales se va a contratar
* Modulo administrativo
**/

class app_Contratacion_admin extends classModulo
{
	function app_Contratacion_admin()
	{
		return true;
	}

/**
* Funcion donde se llama la funcion MenudeEmpresas
* @return boolean
**/

	function main()
	{
		$this->Menu();
		return true;
	}

/*
 * Esta funcion se devuleve al modulo en donde se pueden ver los modulos y los
 * departamentos segÚn el permiso del usuario.
*/

	function Retornar()
	{
		$this->ReturnMetodoExterno($_SESSION['USER_ADMIN_MOD']['CONTENEDOR'], $_SESSION['USER_ADMIN_MOD']['MOD'], $_SESSION['USER_ADMIN_MOD']['TIPO'], $_SESSION['USER_ADMIN_MOD']['METODO'],array("mod"=>$_SESSION['USER_ADMIN_MOD']['MODULO']));
		return true;
	}

	function RetornarPermisos()
	{
		$this->ReturnMetodoExterno($_SESSION['USER_ADMIN_MOD']['CONTENEDOR'], $_SESSION['USER_ADMIN_MOD']['MOD'], $_SESSION['USER_ADMIN_MOD']['TIPO'], 'TraerDatos',array("tabla"=>'userpermisos_contratacion',"permiso"=>'CONTRATACION'));
		return true;
	}

}//fin de la clase
?>
