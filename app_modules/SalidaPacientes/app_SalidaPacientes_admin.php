<?php

/**
 * $Id: app_SalidaPacientes_admin.php,v 1.2 2005/06/03 19:32:12 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * MODULO Administrativo para el Manejo de Usuarios del Sistema
 */

/**
*Contiene los metodos para realizar la administracion de usuarios
*/

class app_SalidaPacientes_admin extends classModulo
{


	function app_SalidaPacientes_admin()
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
	$this->ReturnMetodoExterno($_SESSION['USER_ADMIN_MOD']['CONTENEDOR'], $_SESSION['USER_ADMIN_MOD']['MOD'], $_SESSION['USER_ADMIN_MOD']['TIPO'], 'TraerDatos',array("tabla"=>'userpermisos_autorizaciones_puntos',"permiso"=>'AUTORIZACION SOLICITUD'));
	return true;
}

}//fin clase user

?>




