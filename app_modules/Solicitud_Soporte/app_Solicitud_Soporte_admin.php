<?php

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

