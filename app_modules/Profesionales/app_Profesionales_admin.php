<?php

/**
 * $Id: app_Profesionales_admin.php,v 1.2 2010/02/24 12:09:54 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_CreacionAgenda_admin extends classModulo
{

	function app_CreacionAgenda_admin()
	{
		return true;
	}

	function main()
	{
		$this->Menu();
		return true;
	}

}
?>
