<?php

//require_once ('nusoap/lib/nusoap.php');
require_once('../../nusoap/lib/nusoap.php');

class SincronizacionCosmitet {

    var $produccion = true;

    function sincronizarFormula() {


        $url_servicio = "http://10.0.2.170/Pruebas_DUSOFT_DUANA_3/ws/Formulacion/ws_formulacion.php?wsdl";

        //$funcionWs = "sincronizarFormula";
       //$funcionWs = "sincronizarTranscripcion";
        $funcionWs = "agregarMedicamentos";
        $clienteWs = new nusoap_client($url_servicio, true);

        //$datos_evoluvion['evolucion_id'] = 9771206;
        $datos_evoluvion['ingreso'] = "40604541";
        $datos_evoluvion['fecha'] = "2016-02-29 15:14:34.413187  ";
        $datos_evoluvion['usuario_id'] = "3667";
        $datos_evoluvion['departamento'] = "020202";
        $datos_evoluvion['estado'] = "0";
        $datos_evoluvion['hc_modulo'] = "ConsultaExterna";
        $datos_evoluvion['sw_edicion'] = "0";
        $datos_evoluvion['fecha_cierre'] = "2014-12-22 15:16:22";
        $datos_evoluvion['numerodecuenta'] = "4193285";
        $datos_evoluvion['historia_clinica_tipo_cierre_id'] = 26;
        $datos_evoluvion['observacion_cierre'] = "NULL";
        $datos_evoluvion['diag_principal'] = "0";
        $datos_evoluvion['plan_id'] = 8;
        $datos_evoluvion['descripcion_plan'] = "REGION 1 MAGISTERIO, MAGISALUD 2";
        $datos_evoluvion['numero_formula'] = "2005";
        $datos_evoluvion['es_transcripcion'] = "0";
        $datos_evoluvion['cantidad_medicamentos'] = "2";


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
        $datos_medicamentos[1]['via_administracion_id'] = "1";
        $datos_medicamentos[1]['dosis'] = "1";
        $datos_medicamentos[1]['unidad_dosificacion'] = "TABLETA (S)";
        $datos_medicamentos[1]['tipo_opcion_posologia_id'] = "1";
        $datos_medicamentos[1]['cantidadperiocidad'] = "9";
        $datos_medicamentos[1]['justificacion_reformula'] = "N/A";
        $datos_medicamentos[1]['numero_formula'] = "2005";
        $datos_medicamentos[1]['dias_tratamiento'] = "3";
        $datos_medicamentos[1]['bloqueo'] = "0";
        $datos_medicamentos[1]['usuario_id'] = "3667";
        $datos_medicamentos[1]['usuario_id_modifica'] = "NULL";
        $datos_medicamentos[1]['cantidad_dia'] = "9";


        $datos_antecedentes = array();

        $datos_antecedentes[0]['tipo_id_paciente'] = "CC";
        $datos_antecedentes[0]['paciente_id'] = "31572931";
        $datos_antecedentes[0]['codigo_medicamento'] = "FOFOA0031250";
        $datos_antecedentes[0]['medico_id'] = "3667";
        $datos_antecedentes[0]['dosis'] = "1";
        $datos_antecedentes[0]['unidad_dosificacion'] = "TABLETA (S)";
        $datos_antecedentes[0]['frecuencia'] = "Cada 8 Hora(s)";
        $datos_antecedentes[0]['sw_permanente'] = "0";
        $datos_antecedentes[0]['sw_formulado'] = "1";
        $datos_antecedentes[0]['tiempo_total'] = "3 dia(s) ";
        $datos_antecedentes[0]['perioricidad_entrega'] = "9 (TABLETA O CAPSULA POR 500MG)  cada 25 dia(s)";
        $datos_antecedentes[0]['descripcion'] = "PRUEBA IMPRESION MEDIA CARTA";
        $datos_antecedentes[0]['tiempo_perioricidad_entrega'] = "25";
        $datos_antecedentes[0]['unidad_perioricidad_entrega'] = "dia(s)";
        $datos_antecedentes[0]['cantidad'] = "9";
        $datos_antecedentes[0]['sw_mostrar'] = "1";
        $datos_antecedentes[0]['fecha_registro'] = "2015-03-09";
        $datos_antecedentes[0]['fecha_finalizacion'] = "2015-03-20";
        $datos_antecedentes[0]['fecha_formulacion'] = "2015-03-09";
        $datos_antecedentes[0]['fecha_modificacion'] = "NULL";
        $datos_antecedentes[0]['numero_formula'] = "2005";
        
        $datos_antecedentes[1]['tipo_id_paciente'] = "CC";
        $datos_antecedentes[1]['paciente_id'] = "31572931";
        $datos_antecedentes[1]['codigo_medicamento'] = "FOFOA0031251";
        $datos_antecedentes[1]['medico_id'] = "3667";
        $datos_antecedentes[1]['dosis'] = "1";
        $datos_antecedentes[1]['unidad_dosificacion'] = "TABLETA (S)";
        $datos_antecedentes[1]['frecuencia'] = "Cada 8 Hora(s)";
        $datos_antecedentes[1]['sw_permanente'] = "0";
        $datos_antecedentes[1]['sw_formulado'] = "1";
        $datos_antecedentes[1]['tiempo_total'] = "3 dia(s) ";
        $datos_antecedentes[1]['perioricidad_entrega'] = "9 (TABLETA O CAPSULA POR 500MG)  cada 25 dia(s)";
        $datos_antecedentes[1]['descripcion'] = "PRUEBA IMPRESION MEDIA CARTA";
        $datos_antecedentes[1]['tiempo_perioricidad_entrega'] = "25";
        $datos_antecedentes[1]['unidad_perioricidad_entrega'] = "dia(s)";
        $datos_antecedentes[1]['cantidad'] = "9";
        $datos_antecedentes[1]['sw_mostrar'] = "1";
        $datos_antecedentes[1]['fecha_registro'] = "2015-03-09";
        $datos_antecedentes[1]['fecha_finalizacion'] = "2015-03-20";
        $datos_antecedentes[1]['fecha_formulacion'] = "2015-03-09";
        $datos_antecedentes[1]['fecha_modificacion'] = "NULL";
        $datos_antecedentes[1]['numero_formula'] = "2005";

        $parametros = array('datos_evolucion' => $datos_evoluvion,
            'medicamentos_recetados' => $datos_medicamentos,
            'formulacion_antecedentes' => $datos_antecedentes);
        
        /*echo "<pre>";
        print_r($parametros);
        echo "</pre>";
        exit();*/

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
            $mensajeWs = 'Se ha Generado un error con el Ws de bodegasMovimientoTmp.., no se ha podido establecer conexion';
        }

        /* echo "<h2>Request</h2>";
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

$formulacion = new SincronizacionCosmitet();
$formulacion->sincronizarFormula();
?>