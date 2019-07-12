<?php

/**
 * $Id: app_AtencionInterconsulta_admin.php,v 1.2 2005/06/02 15:29:42 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_AtencionUrgenciasHospitalizacion_admin extends classModulo
{

	function app_AtencionUrgenciasHospitalizacion_admin()
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

