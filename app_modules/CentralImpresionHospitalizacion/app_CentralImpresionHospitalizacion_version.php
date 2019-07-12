<?php

/**
 * $Id: app_CentralImpresionHospitalizacion_version.php,v 1.2 2010/05/12 19:05:46 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_CentralImpresionHospitalizacion_version
{
	var $informacion =array();

	function app_CentralImpresionHospitalizacion_version()
	{
				$this->informacion=array(
				'version'=>'1',
				'subversion'=>'0',
				'revision'=>'0',
				'fecha'=>'01/27/2005',
				'autor'=>'DARLING LILIANA DORADO',
				'descripcion_cambio' => '',
				'requiere_sql' => false,
				'modulos_requeridos' => 'CentroAutorizacion 1.0, funciones_central_impresion.inc.php 1.0,malla_validadora.inc.php 1.0,tarifario_cargos.inc.php 1.0',
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

		
