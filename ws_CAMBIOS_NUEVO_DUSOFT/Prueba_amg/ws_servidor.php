<?php
require_once('../../nusoap/lib/nusoap.php');
$server = new nusoap_server();
$server->configureWSDL('Mi Web Service #1', 'urn:mi_ws1');
// Parametros de entrada
$server->wsdl->addComplexType(  'datos_persona_entrada', 
                                'complexType', 
                                'struct', 
                                'all', 
                                '',
                                array('nombre'   => array('name' => 'nombre','type' => 'xsd:string'),
                                      'email'    => array('name' => 'email','type' => 'xsd:string'),
                                      'telefono' => array('name' => 'telefono','type' => 'xsd:string'),
                                      'ano_nac'  => array('name' => 'ano_nac','type' => 'xsd:int'))
);
// Parametros de Salida
$server->wsdl->addComplexType(  'datos_persona_salidad', 
                                'complexType', 
                                'struct', 
                                'all', 
                                '',
                                array('mensaje'   => array('name' => 'mensaje','type' => 'xsd:string'))
);

$server->register(  'calculo_edad', // nombre del metodo o funcion
                    array('datos_persona_entrada' => 'tns:datos_persona_entrada'), // parametros de entrada
                    array('return' => 'tns:datos_persona_salidad'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:hellowsdl2#calculo_edad', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'La siguiente funcion recibe los parametros de la persona y calcula la Edad' // documentation
);

function calculo_edad($datos) {
    $edad_actual = date('Y') - $datos['ano_nac'];
    $msg = 'Hola, ' . $datos['nombre'] . '. Hemos procesado la siguiente informacion ' . $datos['email'] . ', telefono'. $datos['telefono'].' y su Edad actual es: ' . $edad_actual . '.'; 
    return array('mensaje' => $msg);
}
$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);

/*
$server->register('sincronizarUsuario', array('usuario' => 'tns:WS_datos_usuario'), 
                                          array('return' => 'tns:WS_resultado'), 
                                            "urn:mi_ws1", 
                                            "urn:mi_ws1#sincronizarUsuario", 
                                            "rpc", 
                                            "encoded",
                                            "Webservice: Permite la Sincronizacion de las Usuarios de AMG - DUANA para el proceso de Usuario");


$server->wsdl->addComplexType('WS_datos_usuario', 'complexType', 'struct', 'all', '', array('evolucion_id' => array('name' => 'evolucion_id', 'type' => 'xsd:string')));

// Estructura de respuesta
$server->wsdl->addComplexType('WS_resultado', 'complexType', 'struct', 'all', '', array('msj' => array('name' => 'msj', 'type' => 'xsd:string'), 'estado' => array('name' => 'estado', 'type' => 'xsd:boolean'), 'datos' => array('name' => 'datos', 'type' => 'xsd:string')));

$server->wsdl->addComplexType('WS_listado_formulacion_antecedentes', 'complexType', 'array', '', 'SOAP-ENC:Array', array(), array(
    array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:WS_datos_formulacion_antecedentes[]')
        ), 'tns:WS_datos_formulacion_antecedentes'
);
/*
 *Estructura para la lista de datos  de formulacion de antecedentes.
$server->wsdl->addComplexType('WS_listado_formulacion_antecedentes', 'complexType', 'array', '', 'SOAP-ENC:Array', array(), array(
    array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:WS_datos_formulacion_antecedentes[]')
        ), 'tns:WS_datos_formulacion_antecedentes'
);
 
function sincronizarFormula($datos_evolucion) {
    $validar_usuer=validar($datos_evolucion);
    if ($validar_usuer['continuar']){
       $vars= consultar_user($datos_evolucion);
    }else{
        $vars= " ERROR";
    }
    return array('msj' => join(",", $vars), 'estado' => (bool) '0', 'datos' => '');
}

function consultar_user($datos_evolucion){
    if($datos_evolucion['usuario_id']=='02'){
        $vars=" OK ";
    }else{
        $vars=" No EXISTE ";
    }
   
    return $vars;
}

function validar($datos_evolucion){
    $continuar=true;
    if($datos_evolucion['usuario_id']==''){
        $msj=" USUARIO VACIO ";
        $continuar=false;
    }
    return array("continuar" => $continuar, "msj" => $msj);
}

//INVOCA EL SERVICIO
if (isset($HTTP_RAW_POST_DATA)) {
    $input = $HTTP_RAW_POST_DATA;
} else {
    $input = implode("rn", file('php://input'));
}
$server->service($input);*/

?>