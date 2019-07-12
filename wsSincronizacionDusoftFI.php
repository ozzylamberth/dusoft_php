<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
	
$VISTA = "HTML";
$_ROOT = "";
	
include	"includes/enviroment.inc.php";
require_once ("./classes/AutoCarga/AutoCarga.class.php");
require_once ("./ConexionBD.class.php");
require_once('./nusoap/lib/nusoap.php');
require_once('./ws/Formulacion/Services_JSON.php'); 

$server = new nusoap_server;

$server->configureWSDL('SincronizacionFI', 'urn:cuentas_x_pagar_fi_ws');

// CUENTAS POR PAGAR
$server->register('cuentas_x_pagar_fi', array('empresa_id' => 'xsd:string', 'codigo_proveedor_id' => 'xsd:string', 'numero_factura' => 'xsd:string'),
     array('return' => 'tns:WS_resultado'), "urn:cuentas_x_pagar_fi_ws", "urn:cuentas_x_pagar_fi_ws#sincronizarFormula", "rpc", "encoded", "Webservice: METODO ENCARGADO DE SINCRONIZAR CUENTAS POR PAGAR FI");
		
// Estructura de respuesta
$server->wsdl->addComplexType('WS_resultado', 'complexType', 'struct', 'all', '', array('msj' => array('name' => 'msj', 'type' => 'xsd:string')));

// CUENTAS POR PAGAR
$server->register('sincronizarFi', array('function' => 'xsd:string','parametros' => 'tns:parametros'),//,'wsdl:arrayType' => 'tns:parametros[]'
     array('return' => 'tns:WS_resultado'), "urn:sincronizarFi_ws", "urn:sincronizarFi_ws#sincronizarFi", "rpc", "encoded", "Webservice: METODO ENCARGADO DE SINCRONIZAR A FI");
  

// Estructura para los datos  de formulacion de antecedentes.
$server->wsdl->addComplexType('parametros', 'complexType', 'struct', 'all', '', array(
    'parametro_1' => array('name' => 'parametro_1', 'type' => 'xsd:string'),
    'parametro_2' => array('name' => 'parametro_2', 'type' => 'xsd:string'),
    'parametro_3' => array('name' => 'parametro_3', 'type' => 'xsd:string'),    
    'parametro_4' => array('name' => 'parametro_4', 'type' => 'xsd:string'),
    'parametro_5' => array('name' => 'parametro_5', 'type' => 'xsd:string'),
    'parametro_6' => array('name' => 'parametro_6', 'type' => 'xsd:string'),    
)); 


function sincronizarFi($function, $parametross) {
    $Services_JSON = new Services_JSON;
    $dusoft_fi = AutoCarga::factory("SincronizacionDusoftFI", "SincronizacionDusoftFI", "", "");
    //$function = 'cuentas_x_pagar_fi';
    $resultado = call_user_func_array(array($dusoft_fi, $function), $parametross['parametros']);
    $respuesta = $Services_JSON->encode($resultado);
    return array('msj' => print_r($respuesta,true));
}

//function cuentas_x_pagar_fi($empresa_id,$codigo_proveedor_id,$numero_factura){
//$empresa_id='03';
//$codigo_proveedor_id='541';
//$numero_factura='14000';
//$dusoft_fi = AutoCarga::factory("SincronizacionDusoftFI", "SincronizacionDusoftFI", "", "");
//$resultado_sincronizacion_ws = $dusoft_fi->cuentas_x_pagar_fi($empresa_id, $codigo_proveedor_id, $numero_factura);
//return $resultado_sincronizacion_ws;
//        
//}

//INVOCA EL SERVICIO
if (isset($HTTP_RAW_POST_DATA)) {
    $input = $HTTP_RAW_POST_DATA;
} else {
    $input = implode("rn", file('php://input'));
}
$server->service($input);

?>
