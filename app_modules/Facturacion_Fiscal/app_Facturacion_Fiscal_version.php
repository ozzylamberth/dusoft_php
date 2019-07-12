<?php

/**
 * $Id: app_Facturacion_Fiscal_version.php,v 1.3 2010/12/06 22:13:37 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_Facturacion_Fical_version
{
	var $informacion =array();

	function app_Facturacion_Fical_version()
	{
				$this->informacion=array(
				'version'=>'1',
				'subversion'=>'0',
				'revision'=>'0',
				'fecha'=>'01/27/2005',
				'autor'=>'DARLING LILIANA DORADO',
				'descripcion_cambio' => '',
				'requiere_sql' => false,
				'modulos_requeridos' => 'Facturacion 1.0,funciones_facturacion 1.0, rips.inc.php 1.0,tarifario.inc.php 1.0,funciones_admision.inc.php 1.0,tarifario.inc.php 1.0',
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

		
