<?php

/**
 * $Id: app_ResumenEpicrisis_version.php,v 1.3 2005/12/22 21:23:54 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_ResumenEpicrisis_version
{
	var $informacion =array();

	function app_ResumenEpicrisis_version()
	{
        $this->informacion=array(
        'version'=>'1',
        'subversion'=>'0',
        'revision'=>'0',
        'fecha'=>'01/27/2005',
        'autor'=>'TIZZIANO PEREA OCORO',
        'descripcion_cambio' => '',
        'requiere_sql' => false,
        'modulos_requeridos' => '',
        'requerimientos_adicionales' => '',
        'version_kernel' => '1.0'
		);
	}

	function GetVersion()
	{
        return $this->informacion;
	}

}//fin clase user
?>

		
