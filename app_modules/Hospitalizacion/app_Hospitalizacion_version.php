<?php

class app_Hospitalizacion_version
{
	var informacion =array()

	function app_Hospitalizacion_version()
	{
		$this->informacion=array(
		'version'=>'1',
		'subversion'=>'0',
		'fecha_creacion'=>'19/10/2003',
		'autor'=>'Alexander Giraldo'
		);
	}

	function GetVersion()
	{
        return $this->informacion['version'] . '.' . this->informacion['subversion'];
	}

	function GetInformacion()
	{
        return $this->informacion;
	}
}
?>

		
