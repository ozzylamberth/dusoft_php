<?php

/**hc_modules/EstacionEnfermeria/app_EstacionEnfermeria_admin.php  31/10/2003 9 am
* ----------------------------------------------------------------------
* Autor: Rosa Maria Angel Diez
* Proposito del Archivo: Manejo de las actividades de la Estacion de enfermería
* ----------------------------------------------------------------------
*/

	/**
	*Contiene los metodos para realizar el triage y admision de los pacientes
	*/
class app_EstacionEnfermeriaControlA_admin extends classModulo
{
	var $frmError = array();


	/**
	*		app_EstacionEnfermeria_admin()
	*
	*		constructor
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool
	*/
	function app_EstacionEnfermeriaControlA_admin()//Constructor padre
	{
		return true;
	}

	/**
	*		main()
	*
	*		Llama a la vista de las habitaciones
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool
	*/
	function main()
	{
		return true;
	}//FIN MAIN


}//fin class

?>
