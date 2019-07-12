<?php

/* * *************************************************************************************************
 *
 * Web Service ws_afiliaciones.php
 *
 * Fecha: 20-VI-2013
 * Autor: Steven H. Gamboa
 *
 * Descripción:
 *
 * ************************************************************************************************* */

require_once('../../nusoap/lib/nusoap.php');
// ----> CAMBIAR URL <----
$ns = "http://10.0.2.170/Pruebas_DUSOFT_DUANA_/ws/afiliaciones/ws_crud_afiliaciones.php";
$server = new soap_server();
$server->configureWSDL('AFILIACIONES C.R.U.D', $ns);
$server->wsdl->schemaTargetNamespace = $ns;

$server->register('ejecutar_query', array('sql' => 'xsd:string'
        ), array('return' => 'xsd:string'), $ns);

$server->register('ejecutar_query_selects', array('sql' => 'xsd:string'
        ), array('return' => 'xsd:string'), $ns);

require_once("conexionpg.php");


function ejecutar_query($query) {
    //require_once("conexionpg.php");
    global $conexionn;

    $result = pg_query($conexionn, $query);
    
    if ($result) {
        registrar_log($query, '', '0');
        return true;
    } else {
        
        registrar_log(pg_escape_string($query), pg_escape_string(pg_last_error($conexionn)) , '1');
       
        return "Error en el insert de los datos: " . $query;
    }
}

function ejecutar_query_selects($query) {
    //require_once("conexionpg.php");
    global $conexionn;

    $result = pg_query($conexionn, $query);

    if ($result) {
        while ($row = pg_fetch_array($result)) {
            $dato = $row[max_orden];
        }
        registrar_log($query, '', '0');
    } else {
        
        registrar_log(pg_escape_string($query), pg_escape_string(pg_last_error($conexionn)) , '1');
        
        $dato = "Error en la sentencia select: " . pg_last_error($conexionn);
    }

    return $dato;
}



function registrar_log($query, $resultado, $error) {

    global $conexionn;

    $sql = "insert into logs_pacientes_ws (query, resultado, sw_error) values ('{$query}', '{$resultado}', '{$error}' ) ;";
 
    $result = pg_query($conexionn, $sql);
     
    if ($result) {
        return true;
    } else {
        return "Error en el insert de los datos: -" . pg_last_error();
    }
}

if (isset($HTTP_RAW_POST_DATA)) {
    $input = $HTTP_RAW_POST_DATA;
} else {
    $input = implode("rn", file('php://input'));
}
$server->service($input);
?>