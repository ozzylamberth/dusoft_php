<?php

/**
 * $Id: app_Os_Listas_Trabajo_Apoyod_Agrupado_admin.php,v 1.1.1.1 2009/09/11 20:36:53 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo de Entrega de Resultados (PHP).
 * Modulo para el manejo de listas de trabajo para entrega de resultados
 */

class app_Os_Listas_Trabajo_Apoyod_admin extends classModulo
{
	function app_Os_Listas_Trabajo_Apoyod_admin()
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
	$this->ReturnMetodoExterno($_SESSION['USER_ADMIN_MOD']['CONTENEDOR'], $_SESSION['USER_ADMIN_MOD']['MOD'], $_SESSION['USER_ADMIN_MOD']['TIPO'], 'TraerDatos',array("tabla"=>'cajas_usuarios',"permiso"=>'CAJA GENERAL'));
	return true;
}

}//fin clase user

?>


