<?php
	/**
  * $Id: app_Cartera_admin.php,v 1.4 2007/12/10 15:44:33 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
  *
  */
	class app_Cartera_admin extends classModulo
	{
		function app_Cartera_admin()
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
			$this->ReturnMetodoExterno($_SESSION['USER_ADMIN_MOD']['CONTENEDOR'], $_SESSION['USER_ADMIN_MOD']['MOD'], $_SESSION['USER_ADMIN_MOD']['TIPO'], 'TraerDatos',array("tabla"=>'userpermisos_cartera',"permiso"=>'CARTERA'));
			return true;
		}
	}//fin de la clase
?>
