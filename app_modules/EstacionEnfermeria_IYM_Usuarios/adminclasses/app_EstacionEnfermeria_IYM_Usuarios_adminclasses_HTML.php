<?php

/**app_modules/EstacionEnfermeria_IYM_Usuarios/userclasses/app_EEstacionEnfermeria_IYM_Usuarios_adminclasses_HTML.php
* 31/10/2003 9 am
* ----------------------------------------------------------------------
* Autor: Lorena Aragón G.
* Proposito del Archivo: Manejo visual de la estacion de enfermer&iacute;a
* ----------------------------------------------------------------------
*/

class app_EstacionEnfermeria_IYM_Usuarios_adminclasses_HTML extends app_EstacionEnfermeria_IYM_Usuarios_admin
{
	/**
	*		app_EstacionEnfermeria_IYM_Usuarios_adminclasses_HTML()
	*
	*		constructor
	*
	*		@Author Lorena Aragón G.
	*		@access Private
	*		@return string
	*/
	function app_EstacionEnfermeria_IYM_Usuarios_adminclasses_HTML(){
	  $this->app_EstacionEnfermeria_IYM_Usuarios_admin(); //Constructor del padre 'modulo'
		$this->salida = "";
		return true;
	}


}//CLASS
?>

