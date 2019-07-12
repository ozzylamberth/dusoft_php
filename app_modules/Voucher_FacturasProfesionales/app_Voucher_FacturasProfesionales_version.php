<?php

/**
 * $Id: app_Voucher_FacturasProfesionales_version.php,v 1.1 2006/11/27 13:50:10 lorena Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_Voucher_FacturasProfesionales_version
{
    var $informacion =array();

    function app_Voucher_FacturasProfesionales_version()
    {
        $this->informacion=array(
        'version'=>'1',
        'subversion'=>'0',
        'revision'=>'0',
        'fecha'=>'22/11/2006',
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

