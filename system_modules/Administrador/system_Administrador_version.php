<?php

class system_Administrador_version
{
	var $informacion =array();

	function system_Administrador_version()
	{
				$this->informacion=array(
        'version'=>'1',
        'subversion'=>'0',
        'revision'=>'0',
        'fecha'=>'01/27/2005',
        'autor'=>'JAIRO DUVAN DIAZ MARTINEZ',
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


