<?php

/* * *************************************************************************************************
 *
 * Web Service ws_profesionales.php
 *
 * Fecha: 2015-10-07
 *
 * ************************************************************************************************* */

require_once('../../nusoap/lib/nusoap.php');
// ----> CAMBIAR URL <----
$ns = "http://10.0.2.170/Pruebas_DUSOFT_DUANA_/ws/profesionales/ws_profesionales.php";
$server = new soap_server();
$server->configureWSDL('PROFESIONALES', $ns);
$server->wsdl->schemaTargetNamespace = $ns;

$server->register('ejecutar_query', array('sql' => 'xsd:string'
        ), array('return' => 'xsd:string'), $ns);
		
$server->register('consultar_especialidad', array('especialidad' => 'xsd:string'
        ), array('return' => 'xsd:string'), $ns);
		
$server->register('consultar_tercero', array('tipo_id_tercero' => 'xsd:string',
											 'tercero_id' => 'xsd:string'
        ), array('return' => 'xsd:string'), $ns);

require_once("conexionpg.php");

function ejecutar_query($query) {
    
    global $conexionn;

    $result = pg_query($conexionn, $query);
    
    if ($result) {
        return true;
    } else {
        return "Error en el insert de los datos: " . $query;
    }
}

function consultar_especialidad($especialidad) {
	global $conexionn;

    $sql = "select 	* 
			from 	especialidades e 
			where 	e.especialidad = '{$especialidad}' ";

    $result = pg_query($conexionn, $sql);

    if ($result) {

        $arr = pg_num_rows($result);
        return "{$arr}";
    } else {
        return "0";
    }
}

function consultar_tercero($tipo_id_tercero, $tercero_id) {
	global $conexionn;

    $sql = "select 	* 
			from 	terceros t 
			where 	t.tipo_id_tercero = '{$tipo_id_tercero}'
					and t.tercero_id = '{$tercero_id}' ";

    $result = pg_query($conexionn, $sql);

    if ($result) {

        $arr = pg_num_rows($result);
        return "{$arr}";
    } else {
        return "0";
    }
}

if (isset($HTTP_RAW_POST_DATA)) {
    $input = $HTTP_RAW_POST_DATA;
} else {
    $input = implode("rn", file('php://input'));
}
$server->service($input);
?>