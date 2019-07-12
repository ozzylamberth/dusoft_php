<?php

/**
 * $Id: app_Reportes_Consulta_Externa_version.php,v 1.1.1.1 2009/09/11 20:36:56 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_Reportes_Consulta_Externa_version
{
    var $informacion =array();

    function app_Reportes_Consulta_Externa_version()
    {
        $this->informacion=array(
        'version'=>'1',
        'subversion'=>'0',
        'revision'=>'0',
        'fecha'=>'01/27/2005',
        'autor'=>'JORGE ELIECER AVILA GARZON',
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

        
