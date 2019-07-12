<?php

/**
 * $Id: app_Os_Atencion_version.php,v 1.3 2010/02/26 12:36:19 sandra Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com) 
 * @package IPSOFT-SIIS
 *
 *
 */

class app_Os_Atencion_version
{
	var $informacion =array();

	function app_Os_Atencion_version()
	{
		$this->informacion=array(
        'version'=>'1',
        'subversion'=>'0',
        'revision'=>'0',
        'fecha'=>'01/27/2005',
        'autor'=>'JAIRO DUVAN DIAZ MARTINEZ',
        'descripcion_cambio' => '',
        'requiere_sql' => false,
        'modulos_requeridos' => 'Autorizacion 1.0,tarifario_cargos.inc 1.0',
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

		
