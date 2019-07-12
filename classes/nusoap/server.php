<?
require_once("lib/nusoap.php");
$ns="http://192.168.1.28/SIIS/nusoap";

$server = new soap_server();
$server->configureWSDL('CanadaTaxCalculator',$ns,'http://192.168.1.28/nusoap/server.php');
$server->wsdl->schemaTargetNamespace=$ns;

$server->register('CalculateOntarioTax',
array('amount' => 'xsd:string'),
array('return' => 'xsd:string'),
$ns,
'http://192.168.1.28/nusoap/server.php/CalculateOntarioTax'
);


function CalculateOntarioTax($amount){
$amount=(double)$amount;
if(!is_double($amount))
return new soap_fault('Client','','Debe suplir un número válido'); 
$taxcalc=$amount*0.15;
return new soapval('return','xsd:string',$taxcalc);
}
$HTTP_RAW_POST_DATA;
$server->service($HTTP_RAW_POST_DATA);

?>