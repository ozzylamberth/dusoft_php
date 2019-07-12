<?php

/**
 * $Id: app_Notas_y_Monitoreo_admin.php,v 1.1 2005/09/09 16:26:01 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Contiene los metodos para realizar la administracion de usuarios
 */

class app_ModuloAdminPsicologia_admin extends classModulo
{


	function app_ModuloAdminPsicologia_admin()
	{
		$this->limit=GetLimitBrowser();
     	return true;
	}




/**
* Funcion donde se llama la funcion MenudeEmpresas
* @return boolean
*/

	function main()
	{

		$this->Menu();
		return true;
	}



/*Esta funcion se devuleve al modulo en donde se pueden ver los modulos y los
 * departamentos segun el permiso del usuario.
*/
function Retornar()
{
	$this->ReturnMetodoExterno($_SESSION['USER_ADMIN_MOD']['CONTENEDOR'], $_SESSION['USER_ADMIN_MOD']['MOD'], $_SESSION['USER_ADMIN_MOD']['TIPO'], $_SESSION['USER_ADMIN_MOD']['METODO'],array("mod"=>$_SESSION['USER_ADMIN_MOD']['MODULO']));
	return true;
}

function RetornarPermisos()
{
	$this->ReturnMetodoExterno($_SESSION['USER_ADMIN_MOD']['CONTENEDOR'], $_SESSION['USER_ADMIN_MOD']['MOD'], $_SESSION['USER_ADMIN_MOD']['TIPO'], 'TraerDatos',array("tabla"=>'userpermisos_auditoria_medica',"permiso"=>'AUDITORIA_MEDICA'));
	return true;
}

}//fin clase user

?>




