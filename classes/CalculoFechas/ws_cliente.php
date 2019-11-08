<?php

//require_once ('nusoap/lib/nusoap.php');
require_once('../../nusoap/lib/nusoap.php');

class Sincronizar {

    var $produccion = true;

    function calcularDiasHabiles() {
 
		$url_servicio = "http://10.0.2.237/APP/PRUEBAS_CRISTIAN/classes/CalculoFechas/Prueba.php?wsdl";
 
        $funcionWs = "SumarDiasHabiles";     
        $clienteWs = new nusoap_client($url_servicio, true);		
        $parametros = array('fecha_base' => "2016-08-12",'dias_vigencia' => "8");
            
        $error_ws = false;
        $err = $clienteWs->getError();

        if ($err) {
            $error = $err;
        }
        $result = $clienteWs->call($funcionWs, $parametros);

        if ($clienteWs->fault) {
            $error_ws = true;
            $resultado = $result;
        } else {
            $err = $clienteWs->getError();
            if ($err) {
                $error_ws = true;
                $error = $err;
            } else {
                $error_ws = false;
                $resultado = $result;
            }
        }

        if ($error_ws) {
            $mensajeWs = 'Se ha Generado un error con el Ws ..., no se ha podido establecer conexion';
        }
		/*echo "<h2>Request</h2>";
          echo "<pre>" . htmlspecialchars($clienteWs->request, ENT_QUOTES) . "</pre>";
          echo "<h2>Response</h2>";
          echo "<pre>" . htmlspecialchars($clienteWs->response, ENT_QUOTES) . "</pre>"; */

        echo "<pre>";
        var_dump($resultado);
        print_r($resultado);
        echo "</pre>";
        exit();


        return $resultado;
    }

}

$formulacion = new Sincronizar();
$formulacion->calcularDiasHabiles();
?>