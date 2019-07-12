
<?php

/**
*Modulo para el manejo de reservas de camas de la clinica y hospitales.
*
* @Jairo Duvan Diaz Martinez
* ultima actualizacion: Jairo Duvan Diaz Martinez -->lunes 1 de marzo 2004
*/

// ----------------------------------------------------------------------
// SIIS v 0.1
// Copyright (C) 2003 InterSoftware Ltda.
// Email: intersof@telesat.com.co
// ----------------------------------------------------------------------

/**
*Contiene los metodos para realizar la administracion de usuarios
*/

class app_ReservasCamas_admin extends classModulo
{


	function app_ReservasCamas_admin()
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


