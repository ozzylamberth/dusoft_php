<?php

class app_Solicitud_Reserva_Sangre_version
{
    var $informacion =array();

    function app_Solicitud_Reserva_Sangre_version()
    {
        $this->informacion=array(
        'version'=>'1',
        'subversion'=>'0',
        'revision'=>'0',
        'fecha'=>'01/27/2005',
        'autor'=>'LORENA ARAGON',
        'descripcion_cambio' => '',
        'requiere_sql' => false,
        'modulos_requeridos' => 'Pacientes','CalendarioEstandard',
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


