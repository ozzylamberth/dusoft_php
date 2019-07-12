<?php



//170
require_once("nusoap/lib/nusoap.php");

echo '<h2>Request ...</h2>';
echo "<pre>";
var_dump($_REQUEST);
echo "</pre>";


ini_set("soap.wsdl_cache_enabled", "0");
$url_wsdl = "http://10.0.6.184:8080/SinergiasFinanciero3-ejb/TerceroClasificacionFiscalGet/TerceroClasificacionFiscalGet?wsdl";
$soapclient = new nusoap_client($url_wsdl, true);
$function = "impuestoreteivacodigo";
$inputs = array('codigoiva' => '001');


$err = $soapclient->getError();
if ($err) {
    // Display the error
    echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
    // At this point, you know the call that follows will fail
}

$result = $soapclient->call($function, $inputs);
// Check for a fault
if ($soapclient->fault) {
    echo '<h2>Fault</h2><pre>';
    print_r($result);
    echo '</pre>';
} else {
    // Check for errors
    $err = $soapclient->getError();
    if ($err) {
        // Display the error
        echo '<h2>Error</h2><pre>' . $err . '</pre>';
    } else {
        // Display the result
        echo '<h2>Result</h2><pre>';
        print_r($result);
        echo '</pre>';
    }
}
// Display the request and response
echo '<h2>Request</h2>';
echo '<pre>' . htmlspecialchars($soapclient->request, ENT_QUOTES) . '</pre>';
echo '<h2>Response</h2>';
echo '<pre>' . htmlspecialchars($soapclient->response, ENT_QUOTES) . '</pre>';
// Display the debug messages
echo '<h2>Debug</h2>';
echo '<pre>' . htmlspecialchars($soapclient->debug_str, ENT_QUOTES) . '</pre>';
echo "<pre>";
print_r($result);
echo "</pre>";
?>