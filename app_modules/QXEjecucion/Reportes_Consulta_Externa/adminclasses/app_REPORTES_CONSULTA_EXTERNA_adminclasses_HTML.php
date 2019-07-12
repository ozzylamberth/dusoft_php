<?php

/**
 * $Id: app_REPORTES_CONSULTA_EXTERNA_adminclasses_HTML.php,v 1.2 2005/06/03 18:46:59 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_REPORTES_CONSULTA_EXTERNA_adminclasses_HTML extends app_REPORTES_CONSULTA_EXTERNA_admin
{

	function app_REPORTES_CONSULTA_EXTERNA_admin_HTML()
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

