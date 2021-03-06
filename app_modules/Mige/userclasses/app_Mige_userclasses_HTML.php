<?php

/**
 * $Id: app_Mige_userclasses_HTML.php,v 1.1 2005/07/25 20:25:37 ehudes Exp $
 *
 * @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 * @package IPSOFT-SIIS
 * 
 * M?dulo para abrir el MIGE desde una sesion de SIIS
 */

/**
 * Clase Vista para abir el MIGE desde SIIS
 * 
 * @author Ehudes Fern?n Garc?a <efgarcia@ipsoft-sa.com>
 * @package IPSOFT-SIIS
 */
class app_Mige_userclasses_HTML extends app_Mige_user
{
	/**
	 * constructor
	 *
	 * access public
	 */
	function app_Mige_userclasses_HTML()
	{
		$this->app_Mige_user();
	}
	
	/**
	 * Funci?n principal del m?dulo
	 *
	 * @access public
	 */
	function main()
	{
		$this->ras->AbrirMige(UserGetUID(),session_id());  return true;
	}
}