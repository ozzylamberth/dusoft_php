<?php

/**
 * $Id: app_CajaGeneral_version.php,v 1.5 2010/11/18 14:18:05 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_CajaGeneral_version
{
	var $informacion =array();

	function app_CajaGeneral_version()
	{
		$this->informacion=array(
        'version'=>'1',
        'subversion'=>'0',
        'revision'=>'0',
        'fecha'=>'01/27/2005',
        'autor'=>'JAIRO DUVAN DIAZ MARTINEZ',
        'descripcion_cambio' => '',
        'requiere_sql' => false,
        'modulos_requeridos' => 'Autorizacion 1.0 ,Os_atencion,AgendaMedica 			1.0,tarifario_cargos.inc',
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

		
