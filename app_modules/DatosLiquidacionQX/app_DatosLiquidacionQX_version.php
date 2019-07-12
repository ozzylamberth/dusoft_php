<?php

/**
 * $Id: app_DatosLiquidacionQX_version.php,v 1.1 2005/09/02 20:23:22 lorena Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_Inventarios_version
{
    var $informacion =array();

    function app_Inventarios_version()
    {
        $this->informacion=array(
        'version'=>'1',
        'subversion'=>'0',
        'revision'=>'0',
        'fecha'=>'01/27/2005',
        'autor'=>'LORENA ARAGON',
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

		
