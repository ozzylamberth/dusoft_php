<?php

/**
 * $Id: app_Pagares_adminclasses_HTML.php,v 1.1 2005/08/25 18:38:17 darling Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * MODULO para el Manejo de Usuarios del Sistema
 */


/**
*Contiene los metodos visuales para realizar la administracion de usuarios
*/

class app_Pagares_adminclasses_HTML extends app_Pagares_admin
{
	/**
	*Constructor de la clase app_Usuarios_user_HTML
	*El constructor de la clase app_Usuarios_user_HTML se encarga de llamar
	*a la clase app_Usuarios_user quien se encarga de el tratamiento
	* de la base de datos.
	*/

  function app_Pagares_admin_HTML()
	{
		$this->salida='';
		$this->system_Pagares_admin();
		return true;
	}



}//fin clase user
?>

