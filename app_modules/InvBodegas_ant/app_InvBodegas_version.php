<?php

/**
 * $Id: app_InvBodegas_version.php,v 1.2 2005/06/02 19:28:26 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_InvBodegas_version
{
    var $informacion =array();

    function app_InvBodegas_version()
    {
        $this->informacion=array(
        'version'=>'1',
        'subversion'=>'0',
        'revision'=>'0',
        'fecha'=>'01/27/2005',
        'autor'=>'LORENA ARAGON',
        'descripcion_cambio' => '',
        'requiere_sql' => false,
        'modulos_requeridos' => 'jpgraph/Temperatura_Y_Humedad','despacho_medicamentos','tarifario_cargos',
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

