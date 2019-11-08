<?php

//require_once ('nusoap/lib/nusoap.php');
require_once('../../nusoap/lib/nusoap.php');

class SincronizacionCosmitet {

    var $produccion = true;

    function sincronizarFormula() {


        $url_servicio = "http://10.0.2.170/Pruebas_DUSOFT_DUANA_/ws/codificacion_productos/ws_producto.php?wsdl";

        $funcionWs = "insertar_principioactivo";
//        $funcionWs = "agregar_nivel_atencion";
        //$funcionWs = "sincronizarTranscripcion";
        //$funcionWs = "agregarMedicamentos";
        $clienteWs = new nusoap_client($url_servicio, true);

       
      
        $datos_medicamentos = array();

        $datos_medicamentos[0]['codigo_producto'] = "FOFOA0031250";
        $datos_medicamentos[0]['cantidad'] = "9";
        $datos_medicamentos[0]['observacion'] = "PRUEBA IMPRESION MEDIA CARTA";
        $datos_medicamentos[0]['sw_paciente_no_pos'] = "0";
        $datos_medicamentos[0]['via_administracion_id'] = "1";
        $datos_medicamentos[0]['dosis'] = "1";
        $datos_medicamentos[0]['unidad_dosificacion'] = "TABLETA (S)";
        $datos_medicamentos[0]['tipo_opcion_posologia_id'] = "1";
        $datos_medicamentos[0]['cantidadperiocidad'] = "9";
        $datos_medicamentos[0]['justificacion_reformula'] = "N/A";
        $datos_medicamentos[0]['numero_formula'] = "1503";
        $datos_medicamentos[0]['dias_tratamiento'] = "3";
        $datos_medicamentos[0]['bloqueo'] = "0";
        $datos_medicamentos[0]['usuario_id'] = "2005";
        $datos_medicamentos[0]['usuario_id_modifica'] = "NULL";
        $datos_medicamentos[0]['cantidad_dia'] = "9";

        $datos_medicamentos[1]['codigo_producto'] = "FOFOA0031251";
        $datos_medicamentos[1]['cantidad'] = "9";
        $datos_medicamentos[1]['observacion'] = "PRUEBA IMPRESION MEDIA CARTA";
        $datos_medicamentos[1]['sw_paciente_no_pos'] = "0";
        $datos_medicamentos[1]['via_administracion_id'] = 'NULL';
        $datos_medicamentos[1]['dosis'] = "1";
        $datos_medicamentos[1]['unidad_dosificacion'] = 'NULL';
        $datos_medicamentos[1]['tipo_opcion_posologia_id'] = "1";
        $datos_medicamentos[1]['cantidadperiocidad'] = "9";
        $datos_medicamentos[1]['justificacion_reformula'] = "N/A";
        $datos_medicamentos[1]['numero_formula'] = "2005";
        $datos_medicamentos[1]['dias_tratamiento'] = "3";
        $datos_medicamentos[1]['bloqueo'] = "0";
        $datos_medicamentos[1]['usuario_id'] = "3667";
        $datos_medicamentos[1]['usuario_id_modifica'] = "NULL";
        $datos_medicamentos[1]['cantidad_dia'] = "9";


        
//        $parametros = array('agregar_nivel_atencion' => $datos_medicamentos);
        
        $datos_medicamentos[0]['subclase_id'] = "AAAA";
        $datos_medicamentos[0]['descripcion'] = "PRUEBA";
        
        $parametros = array('insertar_principioactivo' => $datos_medicamentos);
          
          
        /*echo "<pre>";
        print_r($parametros);
        echo "</pre>";
        exit();*/

        $error_ws = false;
        $err = $clienteWs->getError();
        //print_r($parametros['agregar_nivel_atencion']);
        
        if ($err) {
            $error = $err;
        }
        $result = $clienteWs->call($funcionWs, $parametros);
        var_dump($result);
        if ($clienteWs->fault) {
            $error_ws = true;
            $resultado = $result;
        } else {
            $err = $clienteWs->getError();
         //  var_dump($err);
            if ($err) {
                $error_ws = true;
                $error = $err;
            } else {
                $error_ws = false;
                $resultado = $result;
            }
        }

        if ($error_ws) {
            $mensajeWs = 'Se ha Generado un error con el Ws de bodegasMovimientoTmp.., no se ha podido establecer conexion';
        }

        /* echo "<h2>Request</h2>";
          echo "<pre>" . htmlspecialchars($clienteWs->request, ENT_QUOTES) . "</pre>";
          echo "<h2>Response</h2>";
          echo "<pre>" . htmlspecialchars($clienteWs->response, ENT_QUOTES) . "</pre>"; */
/*
        echo "<pre>";
        var_dump($resultado);
        print_r($resultado);
        echo "</pre>";
        exit();*/


        return $resultado;
    }

}

$formulacion = new SincronizacionCosmitet();
$formulacion->sincronizarFormula();
?>