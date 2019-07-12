<?php

/**
 * $Id: app_SalidaPacientes_adminclasses_HTML.php,v 1.2 2005/06/03 19:32:12 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * MODULO para el Manejo de Usuarios del Sistema
 */

/**
*Contiene los metodos visuales para realizar la administracion de usuarios
*/

class app_SalidaPacientes_adminclasses_HTML extends app_SalidaPacientes_admin
{
	/**
	*Constructor de la clase app_Usuarios_user_HTML
	*El constructor de la clase app_Usuarios_user_HTML se encarga de llamar
	*a la clase app_Usuarios_user quien se encarga de el tratamiento
	* de la base de datos.
	*/

  function app_SalidaPacientes_admin_HTML()
	{
		$this->salida='';
		$this->system_SalidaPacientes_admin();
		return true;
	}



}//fin clase user
?>

