<?php

/**
 * $Id: app_SalidaPacientes_version.php,v 1.2 2005/06/03 19:32:12 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_SalidaPacientes_version
{
	var $informacion =array();

	function app_SalidaPacientes_version()
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
				'requerimientos_adicionales' => 'Admisiones 1.0,funciones_admision.inc.php 1.0',
				'version_kernel' => '1.0'
				);
	}

	function GetVersion()
	{
        return $this->informacion;
	}
}
?>

		
