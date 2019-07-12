<?php

/**
 * $Id: app_Notas_y_Monitoreo_adminclasses_HTML.php,v 1.1 2005/09/09 16:26:01 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Contiene los metodos visuales para realizar la administracion de usuarios
 */

class app_Notas_y_Monitoreo_adminclasses_HTML extends app_Notas_y_Monitoreo_admin
{
	/**
	*Constructor de la clase app_Usuarios_user_HTML
	*El constructor de la clase app_Usuarios_user_HTML se encarga de llamar
	*a la clase app_Usuarios_user quien se encarga de el tratamiento
	* de la base de datos.
	*/

  	function app_Notas_y_Monitoreo_admin_HTML()
	{
		$this->salida='';
		$this->system_Notas_y_Monitoreo_admin();
		return true;
	}



}//fin clase user
?>

