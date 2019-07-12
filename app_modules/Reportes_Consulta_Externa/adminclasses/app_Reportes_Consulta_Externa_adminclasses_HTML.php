<?php

/**
 * $Id: app_REPORTES_CONSULTA_EXTERNA_adminclasses_HTML.php,v 1.3 2006/03/13 18:07:57 lorena Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_Reportes_Consulta_Externa_adminclasses_HTML extends app_Reportes_Consulta_Externa_admin
{

	function app_Reportes_Consulta_Externa_admin_HTML()
	{
	    $this->app_AtencionUrgenciasHospitalizacion_admin(); //Constructor del padre 'modulo'
		$this->salida='';
		return true;
	}

	function CreacionAgenda()
	{
		$this->salida  = ThemeAbrirTabla('CREACIÓN AGENDA MEDICA');
		$this->salida .= ThemeCerrarTabla();
    return true;
	}

	function forma2()
	{
		$this->salida  = ThemeAbrirTabla('MI TABLA 2');
		$this->salida .= "<br><a href=\"" . ModuloGetURL('app','AgendaMedica','admin','CreacionAgenda') ."\">LLAMAR METODO MAIN</a><br><br>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
}

?>

