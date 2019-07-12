<?php

class app_Autorizacion_version
{
	var informacion =array()

	function app_Autorizacion_version()
	{
				$this->informacion=array(
				'version'=>'1',
				'subversion'=>'0',
				'revision'=>'0',
				'fecha'=>'01/27/2005',
				'autor'=>'DARLING LILIANA DORADO',
				'descripcion_cambio' => '',
				'requiere_sql' => false,
				'modulos_requeridos' => 'Pacientes 1.0',
				'requerimientos_adicionales' => '',
				'version_kernel' => '1.0'
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

		
