<?php

/**
 * $Id: app_InterfazContable_adminclasses_HTML.php,v 1.1 2005/06/29 22:53:53 darling Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * MODULO para el Manejo de Usuarios del Sistema
 */


/**
*Contiene los metodos visuales para realizar la administracion de usuarios
*/

class app_Admisiones_adminclasses_HTML extends app_Admisiones_admin
{
	/**
	*Constructor de la clase app_Usuarios_user_HTML
	*El constructor de la clase app_Usuarios_user_HTML se encarga de llamar
	*a la clase app_Usuarios_user quien se encarga de el tratamiento
	* de la base de datos.
	*/

  function app_Admisiones_admin_HTML()
	{
		$this->salida='';
		$this->system_Admisiones_admin();
		return true;
	}



}//fin clase user
?>

