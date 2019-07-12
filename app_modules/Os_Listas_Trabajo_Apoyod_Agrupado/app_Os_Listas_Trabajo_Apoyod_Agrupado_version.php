<?php

/**
 * $Id: app_Os_Listas_Trabajo_Apoyod_Agrupado_version.php,v 1.1.1.1 2009/09/11 20:36:53 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_Os_Listas_Trabajo_Apoyod_version
{
	var $informacion =array();

	function app_Os_Listas_Trabajo_Apoyod_version()
	{
		 $this->informacion=array(
        'version'=>'1',
        'subversion'=>'0',
        'revision'=>'0',
        'fecha'=>'01/27/2005',
        'autor'=>'CLAUDIA LILIANA ZUÑIGA CAÑON',
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
}
?>


