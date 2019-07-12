<?php

/**
 * $Id: app_Control_Cierre_version.php,v 1.2 2005/06/02 18:33:08 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_Control_Cierre_version
{
	var $informacion =array();

	function app_Control_Cierre_version()
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
        'requerimientos_adicionales' => 'control_cierre.inc 1.0 ',
        'version_kernel' => '1.0'
		);
	}

	function GetVersion()
	{
        return $this->informacion;
	}
}
?>

		
