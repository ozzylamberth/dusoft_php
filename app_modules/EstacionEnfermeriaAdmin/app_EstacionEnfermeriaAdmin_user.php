<?

 /**
 * $Id: app_EstacionEnfermeriaAdmin_user.php,v 1.3 2005/06/09 21:09:34 duvan Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo de administracion de la Estacion de Enfermeria modulo para la atencion del paciente 
 */






/**
*		class app_EstacionEnfermeria_user
*
*		Clase que maneja todas los metodos que llaman a las vistas relacionadas a la estaci�n de Enfermer�a
*		ubicadas en la clase hija html
*		ubicacion => app_modules/EstacionEnfermeria/app_EstacionEnfermeria_user.php
*		fecha creaci�n => 04/05/2004 10:35 am
*
*		@Author => jairo Duvan Diaz Martinez
*		@version =>
*		@package SIIS
*/
class app_EstacionEnfermeriaAdmin_user extends classModulo
{
	var $frmError = array();


	/**
	*		app_EstacionEnfermeria_user()
	*
	*		constructor
	*
	*		@Author Arley Vel�squez
	*		@access Public
	*		@return bool
	*/
	function app_EstacionEnfermeriaAdmin_user()//Constructor padre
	{
		return true;
	}


	/**
	*		main
	*
	*		Esta funci�n permite seleccionar todas las estaciones de enfermeria
	*		organizadas por su empresa, centro de utilidad, unidad funcional y departamento
	*		a la cual pertenecen.
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool
	*/
	function main()
	{
		return true;
	}//FIN main

}//fin class
?>
