<?php

require_once('../../nusoap/lib/nusoap.php');
$server = new nusoap_server;
$server->configureWSDL('FormulacionWs', 'urn:formulacion_ws');

require_once ("../codificacion_productos/conexionpg.php");
$url = "http://10.0.2.170/Pruebas_DUSOFT_DUANA_/ws/Formulacion/ws_transcripcion.php?wsdl";


// Registrar Funciones
$server->register('sincronizarTranscripcion', array('datos_evolucion' => 'tns:WS_datos_evolucion'), array('return' => 'tns:WS_resultado'), "urn:formulacion_ws", "urn:formulacion_ws#sincronizarTranscripcion", "rpc", "encoded", "Webservice: Permite la Sincronizacion de las formulas de COSMITET - DUANA para el proceso de DISPENSACION");


// Estructura para los datos de evolucion.
$server->wsdl->addComplexType('WS_datos_evolucion', 'complexType', 'struct', 'all', '', array('evolucion_id' => array('name' => 'evolucion_id', 'type' => 'xsd:string')));

// Estructura para los datos  de medicamentos recetados
$server->wsdl->addComplexType('WS_datos_medicamentos_recetados', 'complexType', 'struct', 'all', '', array('evolucion_id' => array('name' => 'evolucion_id', 'type' => 'xsd:string')));

// Estructura para los datos  de formulacion de antecedentes.
$server->wsdl->addComplexType('WS_datos_formulacion_antecedentes', 'complexType', 'struct', 'all', '', array('evolucion_id' => array('name' => 'evolucion_id', 'type' => 'xsd:string')));

// Estructura para los datos  de ingreso.
$server->wsdl->addComplexType('WS_datos_ingreso', 'complexType', 'struct', 'all', '', array('evolucion_id' => array('name' => 'evolucion_id', 'type' => 'xsd:string')));

// Estructura para los datos  de cuentas.
$server->wsdl->addComplexType('WS_datos_cuentas', 'complexType', 'struct', 'all', '', array('evolucion_id' => array('name' => 'evolucion_id', 'type' => 'xsd:string')));

// Estructura para los datos  de planes rangos.
$server->wsdl->addComplexType('WS_datos_planes_rangos', 'complexType', 'struct', 'all', '', array('evolucion_id' => array('name' => 'evolucion_id', 'type' => 'xsd:string')));

// Estructura para los datos  de planes.
$server->wsdl->addComplexType('WS_datos_planes', 'complexType', 'struct', 'all', '', array('evolucion_id' => array('name' => 'evolucion_id', 'type' => 'xsd:string')));




// Estructura de respuesta
$server->wsdl->addComplexType('WS_resultado', 'complexType', 'struct', 'all', '', array('msj' => array('name' => 'msj', 'type' => 'xsd:string'), 'estado' => array('name' => 'estado', 'type' => 'xsd:boolean')));

function sincronizarTranscripcion() {
    
}

//INVOCA EL SERVICIO
if (isset($HTTP_RAW_POST_DATA)) {
    $input = $HTTP_RAW_POST_DATA;
} else {
    $input = implode("rn", file('php://input'));
}
$server->service($input);
?>