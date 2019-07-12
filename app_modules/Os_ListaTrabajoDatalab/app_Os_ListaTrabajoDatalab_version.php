<?php

class app_Os_ListaTrabajoDatalab_version
{
	var $informacion =array();

	function app_Os_ListaTrabajoDatalab_version()
	{
		$this->informacion=array(
				'version'=>'1',
				'subversion'=>'0',
				'revision'=>'0',
				'fecha'=>'01/27/2005',
				'autor'=>'CLAUDIA LILIANA ZUÑIGA',
				'descripcion_cambio' => '',
				'requiere_sql' => false,
				'modulos_requeridos' => '',
				'requerimientos_adicionales' => '',
				'version_kernel' => '1.0'
				);
	}
	function GetVersion()
	{
			return $this->informacion;
	}
}
?>

		
