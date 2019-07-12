<?php

/**
 * $Id: app_RespuestasAuditor_adminclasses_HTML.php,v 1.1 2005/11/09 19:23:35 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Contiene los metodos visuales para realizar la administracion de usuarios
 */

class app_RespuestasAuditor_adminclasses_HTML extends app_RespuestasAuditor_admin
{
	/**
	*Constructor de la clase app_Usuarios_user_HTML
	*El constructor de la clase app_Usuarios_user_HTML se encarga de llamar
	*a la clase app_Usuarios_user quien se encarga de el tratamiento
	* de la base de datos.
	*/

  	function app_RespuestasAuditor_admin_HTML()
	{
		$this->salida='';
		$this->system_RespuestasAuditor_admin();
		return true;
	}



}//fin clase user
?>

