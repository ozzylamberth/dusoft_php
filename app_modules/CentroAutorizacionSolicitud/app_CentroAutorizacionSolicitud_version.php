<?php

/**
 * $Id: app_CentroAutorizacionSolicitud_version.php,v 1.2 2005/06/02 16:57:07 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_CentroAutorizacionSolicitud_version
{
	var $informacion =array();

	function app_CentroAutorizacionSolicitud_version()
	{
				$this->informacion=array(
				'version'=>'1',
				'subversion'=>'0',
				'revision'=>'0',
				'fecha'=>'01/27/2005',
				'autor'=>'DARLING LILIANA DORADO',
				'descripcion_cambio' => '',
				'requiere_sql' => false,
				'modulos_requeridos' => '',
				'requerimientos_adicionales' => 'Pacientes 1.0,CentroAutorizacion 1.0,tarifario_cargos.inc.php 1.0, malla_validadora.inc.php 1.0',
				'version_kernel' => '1.0'
				);
	}

	function GetVersion()
	{
        return $this->informacion;
	}
}
?>

		
