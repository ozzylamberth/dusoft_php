<?php

/**app_modules/InsumosMedicamentosCirugia/userclasses/app_InsumosMedicamentosCirugia_adminclasses_HTML.php
* 31/10/2003 9 am
* ----------------------------------------------------------------------
* Autor: Lorena Aragón G.
* Proposito del Archivo: Manejo visual de la estacion de enfermer&iacute;a
* ----------------------------------------------------------------------
*/

class app_InsumosMedicamentosCirugia_adminclasses_HTML extends app_InsumosMedicamentosCirugia_admin
{
	/**
	*		app_InsumosMedicamentosCirugia_adminclasses_HTML()
	*
	*		constructor
	*
	*		@Author Lorena Aragón G.
	*		@access Private
	*		@return string
	*/
	function app_InsumosMedicamentosCirugia_adminclasses_HTML()
	{
	  $this->app_InsumosMedicamentosCirugia_QX_admin(); //Constructor del padre 'modulo'
		$this->salida = "";
		return true;
	}


}//CLASS
?>

