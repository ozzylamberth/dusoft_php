<?php

/**
 * $Id: app_Contratacion_admin.php,v 1.1.1.1 2009/09/11 20:36:29 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

/**
* app_Contratación_admin.php
**/

class app_Contratacion_admin extends classModulo
{
	function app_Contratacion_admin()
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
		$this->ReturnMetodoExterno($_SESSION['USER_ADMIN_MOD']['CONTENEDOR'], $_SESSION['USER_ADMIN_MOD']['MOD'], $_SESSION['USER_ADMIN_MOD']['TIPO'], 'TraerDatos',array("tabla"=>'userpermisos_contratacion',"permiso"=>'CONTRATACION'));
		return true;
	}

}//fin de la clase
?>
