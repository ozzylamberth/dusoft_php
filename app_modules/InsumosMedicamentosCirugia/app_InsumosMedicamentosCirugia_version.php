<?php

class app_InsumosMedicamentosCirugia_version
{
	var $informacion =array()

	function app_InsumosMedicamentosCirugia_version()
	{
		$this->informacion=array(
        'version'=>'1',
        'subversion'=>'0',
        'revision'=>'0',
        'fecha'=>'11/30/2005',
        'autor'=>'LORENA ARAGON',
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

	function GetInformacion()
  {
    return $this->informacion;
	}
}
?>

		
