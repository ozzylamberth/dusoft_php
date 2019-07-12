<?php

/**
 * $Id: app_Reportes_Consulta_Externa_admin.php,v 1.2 2005/06/03 18:46:59 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_REPORTES_CONSULTA_EXTERNA_admin extends classModulo
{

	function app_REPORTES_CONSULTA_EXTERNA_admin()
	{
		return true;
	}

	function main()
	{
    $this->CreacionAgenda();
		return true;
	}

	function prueba()
	{
    $this->forma2();
		return true;
	}
}



?>

