<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ws_cliente_p
 *
 * andres mauricio gonzalez
 */
require_once('../../nusoap/lib/nusoap.php');



//require_once('lib/nusoap.php');
     
    $cliente = new nusoap_client('http://10.0.2.170/Pruebas_DUSOFT_DUANA_/ws/Prueba_amg/ws_servidor.php');
    //print_r($cliente);
     
    $datos_persona_entrada = array( "datos_persona_entrada" => array(    
                                                                    'nombre'    => "Mauricio A.",
                                                                    'email'     => "ealpizar@ticosoftweb.com",
                                                                    'telefono'  => "8700-5455",
                                                                    'ano_nac'   => 1980)
                                                                    );
 
    $resultado = $cliente->call('calculo_edad',$datos_persona_entrada);
     echo "version ".$cliente->XMLSchemaVersion."<br>";
     echo "autotype ".$cliente->authtype ."<br>";
     echo "debug level ".$cliente->debugLevel."<br>";
     echo "persis ".$cliente->useHTTPPersistentConnection()."<br>";
     echo "time ". $cliente->getmicrotime()."<br>";
     echo "proxy ".$cliente->getProxyClassCode()."<br>";
     echo "cabecera ".$cliente->getHeader()."<br>";
     echo "error ".$cliente->getError();
    print_r($resultado);
/*class ws_cliente_p {
    
    var $produccion = true;
    
    function sincronizar_usuario(){
        $url_servicio = "http://10.0.2.170/Pruebas_DUSOFT_DUANA_/ws/Prueba_amg/ws_servidor.php?wsdl";
echo $url_servicio;
        $funcionWs = "sincronizarFormula";
        $clienteWs = new nusoap_client($url_servicio, true);
        $datosUser['usuario_id']='01';
        $parametros = array('sincronizarUsuario' => $datosUser);
        
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
            $resultado = 'Se ha Generado un error con el Ws de systen_usuarios.., no se ha podido establecer conexion';
        }else{
            $resultado = ' Conexion OK';
        }

        echo "<pre>";
        var_dump($resultado);
        print_r($resultado);
        echo "</pre>";
        return $resultado;
    }
    
}
$formulacion = new ws_cliente_p();
$formulacion->sincronizar_usuario();*/

?>