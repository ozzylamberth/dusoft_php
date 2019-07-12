<?php

/**
 * $Id: app_AuditoriaMedica_adminclasses_HTML.php,v 1.1 2005/08/31 12:55:33 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Contiene los metodos visuales para realizar la administracion de usuarios
 */

class app_AuditoriaMedica_adminclasses_HTML extends app_AuditoriaMedica_admin
{
	/**
	*Constructor de la clase app_Usuarios_user_HTML
	*El constructor de la clase app_Usuarios_user_HTML se encarga de llamar
	*a la clase app_Usuarios_user quien se encarga de el tratamiento
	* de la base de datos.
	*/

  	function app_AuditoriaMedica_admin_HTML()
	{
		$this->salida='';
		$this->system_AuditoriaMedica_admin();
		return true;
	}



}//fin clase user
?>

