<?php

/**
 * $Id: app_BioEstadsitica_adminclasses_HTML.php,v 1.3 2005/06/02 16:01:53 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Contiene los metodos visuales para realizar la administracion de usuarios
 */

class app_BioEstadistica_adminclasses_HTML extends app_BioEstadistica_admin
{
	/**
	*Constructor de la clase app_Usuarios_user_HTML
	*El constructor de la clase app_Usuarios_user_HTML se encarga de llamar
	*a la clase app_Usuarios_user quien se encarga de el tratamiento
	* de la base de datos.
	*/

  function app_BioEstadistica_admin_HTML()
	{
		$this->salida='';
		$this->system_BioEstadistica_admin();
		return true;
	}



}//fin clase user
?>

