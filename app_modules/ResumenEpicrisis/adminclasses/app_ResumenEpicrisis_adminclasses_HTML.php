<?php

/**
 * $Id: app_ResumenEpicrisis_adminclasses_HTML.php,v 1.1 2006/01/04 21:43:38 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_ResumenEpicrisis_adminclasses_HTML extends app_ResumenEpicrisis_admin
{
	/**
	*Constructor de la clase app_Usuarios_user_HTML
	*El constructor de la clase app_Usuarios_user_HTML se encarga de llamar
	*a la clase app_Usuarios_user quien se encarga de el tratamiento
	* de la base de datos.
	*/

  	function app_ResumenEpicrisis_admin_HTML()
	{
		$this->salida='';
		$this->system_ResumenEpicrisis_admin();
		return true;
	}

}//fin clase user

?>

