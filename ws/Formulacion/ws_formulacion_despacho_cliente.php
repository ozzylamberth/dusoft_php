<?php
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
   require_once('../../nusoap/lib/nusoap.php');
	require_once('Services_JSON.php'); 
	$Services_JSON = new Services_JSON;
    $i=0;   
    $sincronizar['formula']= "182989";//"160517";
    $sincronizar['tipo']="1";
    
	 
    //echo "<pre>"; print_r($sincronizar);   
    $wsdl = 'http://10.0.2.237/APP/PRUEBAS_CRISTIAN/ws/Formulacion/ws_formulacion_despacho.php?wsdl';
    $soapclient = new nusoap_client($wsdl, true);
    $function = "despachoFormula";
    $respuesta = $soapclient->call($function, $sincronizar);    
	$err = $soapclient->getError();
	
    //print_r($err); 
	echo "<pre>";	
	//print_r($respuesta['name'])
	print_r($Services_JSON->decode($respuesta['name']));
?>     