<?php

class app_AtencionUrgenciasHospitalizacion_adminclasses_HTML extends app_AtencionUrgenciasHospitalizacion_admin
{

	function app_AtencionUrgenciasHospitalizacion_admin_HTML()
	{
	    $this->app_AtencionUrgenciasHospitalizacion_admin(); //Constructor del padre 'modulo'
		$this->salida='';
		return true;
	}

	function CreacionAgenda()
	{
		$this->salida  = ThemeAbrirTabla('CREACI�N AGENDA MEDICA');
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

