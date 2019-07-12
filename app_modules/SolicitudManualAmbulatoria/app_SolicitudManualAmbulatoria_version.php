<?php

/**
 * $Id: app_SolicitudManualAmbulatoria_version.php,v 1.2 2005/06/03 19:39:54 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_SolicitudManualAmbulatoria_version
{
	var $informacion =array();

	function app_SolicitudManualAmbulatoria_version()
	{
				$this->informacion=array(
				'version'=>'1',
				'subversion'=>'0',
				'revision'=>'0',
				'fecha'=>'01/27/2005',
				'autor'=>'DARLING LILIANA DORADO',
				'descripcion_cambio' => '',
				'requiere_sql' => false,
				'modulos_requeridos' => 'Pacientes 1.0,Autorizacion 1.0,tarifario_cargos.inc.php 1.0,malla_validadora.inc.php 1.0',
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

		
