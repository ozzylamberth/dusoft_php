<?


 /**
 * $Id: app_EstacionEnfermeriaAdmin_userclasses_HTML.php,v 1.3 2005/06/09 21:09:16 duvan Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo de administracion de la Estacion de Enfermeria modulo para la atencion del paciente 
 */





/**
*		class app_EstacionEnfermeria_userclasses_HTML
*
*		Clase que maneja todas las funciones de vistas y consultas a la base de datos
*		relacionadas a la estaci&oacute;n de Enfermer&iacute;a
*		ubicacion => app_modules/EstacionEnfermeria/userclasses/app_EstacionEnfermeria_userclasses_HTML.php
*		fecha creaci&oacute;n => 04/05/2004 10:35 am
*
*		@Author jairo Duvan Diaz Martinez
*		@version =>
*		@package SIIS
*/
class app_EstacionEnfermeriaAdmin_userclasses_HTML extends app_EstacionEnfermeriaAdmin_user
{

	/**
	*		app_EstacionEnfermeria_userclasses_HTML()
	*
	*		constructor
	*
	*		@Author jairo Duvan Diaz Martinez.
	*		@access Private
	*		@return boolean
	*/
	function app_EstacionEnfermeria_userclasses_HTML()
	{
	  $this->app_EstacionEnfermeria_user(); //Constructor del padre 'modulo'
		$this->salida = "";
		return true;
	}




}//fin class
?>
