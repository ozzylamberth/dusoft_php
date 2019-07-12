<?php

/**
 * $Id: $
 * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_MantenimientoCuentas_version
{
	var $informacion =array();

	function app_MantenimientoCuentas_version()
	{
				$this->informacion=array(
				'version'=>'1',
				'subversion'=>'0',
				'revision'=>'0',
				'fecha'=>'24/08/2007',
				'autor'=>'CAH',
				'descripcion_cambio' => '',
				'requiere_sql' => false,
				'modulos_requeridos' => ,
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

		
