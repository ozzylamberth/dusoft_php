<?php

/**
 * $Id: app_InvCodificacionPtos_version.php,v 1.2 2005/06/02 19:33:44 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_InvCodificacionPtos_version
{
    var $informacion =array();

    function app_InvCodificacionPtos_version()
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


		
