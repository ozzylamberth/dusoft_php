<?php

/**
 * $Id: app_Auditores_admin.php,v 1.2 2005/06/02 15:38:07 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

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
