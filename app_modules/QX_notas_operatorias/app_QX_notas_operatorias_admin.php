<?php

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

