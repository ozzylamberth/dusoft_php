<?php

/**
 * $Id: app_AutorizacionQx_version.php,v 1.3 2005/06/21 17:08:57 darling Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_AutorizacionQx_version
{
	var $informacion =array();

	function app_AutorizacionQx_version()
	{
				$this->informacion=array(
				'version'=>'1',
				'subversion'=>'0',
				'revision'=>'0',
				'fecha'=>'',
				'autor'=>'DARLING LILIANA DORADO',
				'descripcion_cambio' => '',
				'requiere_sql' => false,
				'modulos_requeridos' => '',
				'requerimientos_adicionales' => '',
				'version_kernel' => ''
				);
	}

	function GetVersion()
	{
        return $this->informacion;
	}
}
?>

		
