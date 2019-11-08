<?php

//require_once ('nusoap/lib/nusoap.php');
require_once('../../nusoap/lib/nusoap.php');

class SincronizacionCosmitet {

    var $produccion = true;

    function sincronizarFormula() {
 

        //$url_servicio = "http://10.0.2.170/Pruebas_DUSOFT_DUANA_/ws/Formulacion/ws_formulacion.php?wsdl";
		$url_servicio = "http://10.0.2.170/DUSOFT_DUANA/ws/Formulacion/ws_formulacion.php?wsdl";

        $funcionWs = "sincronizarFormula";
       // $funcionWs = "sincronizarTranscripcion";
        //$funcionWs = "agregarMedicamentos";
        $clienteWs = new nusoap_client($url_servicio, true);
		$numeroFormula = "221796";
		$tipoFormula = "0";
		$paciente = "29236223";
		$modificacion = "0";
        //$datos_evoluvion['evolucion_id'] = 9771206;
        $datos_evoluvion['ingreso'] = "5239699";
        $datos_evoluvion['fecha'] = "2015-10-20 10:22:34.413187  ";
        $datos_evoluvion['usuario_id'] = "3667";
        $datos_evoluvion['departamento'] = "090901";
        $datos_evoluvion['estado'] = "0";
        $datos_evoluvion['hc_modulo'] = "ConsultaExterna";
        $datos_evoluvion['sw_edicion'] = "0";
        $datos_evoluvion['fecha_cierre'] = "2015-10-27 14:17:25.008167";
        $datos_evoluvion['numerodecuenta'] = "5499307";
        $datos_evoluvion['historia_clinica_tipo_cierre_id'] = 26;
        $datos_evoluvion['observacion_cierre'] = "N/A";
        $datos_evoluvion['diag_principal'] = "1";
        $datos_evoluvion['plan_id'] = 95;
        $datos_evoluvion['descripcion_plan'] = "0";
        $datos_evoluvion['numero_formula'] = $numeroFormula; 
        $datos_evoluvion['es_transcripcion'] = "0";
		$datos_evoluvion['tipo_formula'] = $tipoFormula;
		$datos_evoluvion['modificacion']= $modificacion;
		
        $datos_medicamentos = array();
		
		/***********************************************************/
		
        $datos_medicamentos[0]['codigo_producto'] = "FOFOA0311259";
        $datos_medicamentos[0]['cantidad'] = "21";
        $datos_medicamentos[0]['observacion'] = "N/A";
        $datos_medicamentos[0]['sw_paciente_no_pos'] = "0";
        $datos_medicamentos[0]['via_administracion_id'] = "1";
        $datos_medicamentos[0]['dosis'] = "1.00";
        $datos_medicamentos[0]['unidad_dosificacion'] = "TABLETA (S)";
        $datos_medicamentos[0]['tipo_opcion_posologia_id'] = "1";
        $datos_medicamentos[0]['cantidadperiocidad'] = "8";
        $datos_medicamentos[0]['justificacion_reformula'] = "N/A";
        $datos_medicamentos[0]['numero_formula'] = $numeroFormula;
        $datos_medicamentos[0]['dias_tratamiento'] = "7";
        $datos_medicamentos[0]['bloqueo'] = "0";
        $datos_medicamentos[0]['usuario_id'] = "3667";
        $datos_medicamentos[0]['usuario_id_modifica'] = "3667";
        $datos_medicamentos[0]['cantidad_dia'] = "3.00";

        $datos_medicamentos[1]['codigo_producto'] = "FOFOA0311259";
        $datos_medicamentos[1]['cantidad'] = "21";
        $datos_medicamentos[1]['observacion'] = "N/A";
        $datos_medicamentos[1]['sw_paciente_no_pos'] = "0";
        $datos_medicamentos[1]['via_administracion_id'] = "1";
        $datos_medicamentos[1]['dosis'] = "1.00";
        $datos_medicamentos[1]['unidad_dosificacion'] = "TABLETA (S)";
        $datos_medicamentos[1]['tipo_opcion_posologia_id'] = "1";
        $datos_medicamentos[1]['cantidadperiocidad'] = "8";
        $datos_medicamentos[1]['justificacion_reformula'] = "N/A";
        $datos_medicamentos[1]['numero_formula'] = $numeroFormula;
        $datos_medicamentos[1]['dias_tratamiento'] = "7";
        $datos_medicamentos[1]['bloqueo'] = "0";
        $datos_medicamentos[1]['usuario_id'] = "3667";
        $datos_medicamentos[1]['usuario_id_modifica'] = "3667";
        $datos_medicamentos[1]['cantidad_dia'] = "3.00";

		
		/**********************************************************************/
		
		$datos_medicamentos[2]['codigo_producto'] = "FOFOD0200299";
        $datos_medicamentos[2]['cantidad'] = "1";
        $datos_medicamentos[2]['observacion'] = "1 CUCHARADA CADA 12 HORAS";
        $datos_medicamentos[2]['sw_paciente_no_pos'] = "0";
        $datos_medicamentos[2]['via_administracion_id'] = "1";
        $datos_medicamentos[2]['dosis'] = "1.00";
        $datos_medicamentos[2]['unidad_dosificacion'] = "ml";
        $datos_medicamentos[2]['tipo_opcion_posologia_id'] = "1";
        $datos_medicamentos[2]['cantidadperiocidad'] = "10";
        $datos_medicamentos[2]['justificacion_reformula'] = "N/A";
        $datos_medicamentos[2]['numero_formula'] = $numeroFormula;
        $datos_medicamentos[2]['dias_tratamiento'] = "10";
        $datos_medicamentos[2]['bloqueo'] = "0";
        $datos_medicamentos[2]['usuario_id'] = "3667";
        $datos_medicamentos[2]['usuario_id_modifica'] = "3667";
        $datos_medicamentos[2]['cantidad_dia'] = "3.00";
		
		$datos_medicamentos[3]['codigo_producto'] = "FOFOD0200299";
        $datos_medicamentos[3]['cantidad'] = "1";
        $datos_medicamentos[3]['observacion'] = "1 CUCHARADA CADA 12 HORAS";
        $datos_medicamentos[3]['sw_paciente_no_pos'] = "0";
        $datos_medicamentos[3]['via_administracion_id'] = "1";
        $datos_medicamentos[3]['dosis'] = "1.00";
        $datos_medicamentos[3]['unidad_dosificacion'] = "ml";
        $datos_medicamentos[3]['tipo_opcion_posologia_id'] = "1";
        $datos_medicamentos[3]['cantidadperiocidad'] = "10";
        $datos_medicamentos[3]['justificacion_reformula'] = "N/A";
        $datos_medicamentos[3]['numero_formula'] = $numeroFormula;
        $datos_medicamentos[3]['dias_tratamiento'] = "10";
        $datos_medicamentos[3]['bloqueo'] = "0";
        $datos_medicamentos[3]['usuario_id'] = "3667";
        $datos_medicamentos[3]['usuario_id_modifica'] = "3667";
        $datos_medicamentos[3]['cantidad_dia'] = "3.00";
		
		
		/**************************************************/
		
		$datos_medicamentos[4]['codigo_producto'] = "FOFOL0161760";
        $datos_medicamentos[4]['cantidad'] = "5";
        $datos_medicamentos[4]['observacion'] = "EN LA NOCHE";
        $datos_medicamentos[4]['sw_paciente_no_pos'] = "0";
        $datos_medicamentos[4]['via_administracion_id'] = "1";
        $datos_medicamentos[4]['dosis'] = "1.00";
        $datos_medicamentos[4]['unidad_dosificacion'] = "TABLETA (S)";
        $datos_medicamentos[4]['tipo_opcion_posologia_id'] = "1";
        $datos_medicamentos[4]['cantidadperiocidad'] = "24";
        $datos_medicamentos[4]['justificacion_reformula'] = "N/A";
        $datos_medicamentos[4]['numero_formula'] = $numeroFormula;
        $datos_medicamentos[4]['dias_tratamiento'] = "5";
        $datos_medicamentos[4]['bloqueo'] = "0";
        $datos_medicamentos[4]['usuario_id'] = "3667";
        $datos_medicamentos[4]['usuario_id_modifica'] = "3667";
        $datos_medicamentos[4]['cantidad_dia'] = "1.00";
		
		$datos_medicamentos[5]['codigo_producto'] = "FOFOL0161760";
        $datos_medicamentos[5]['cantidad'] = "5";
        $datos_medicamentos[5]['observacion'] = "EN LA NOCHE";
        $datos_medicamentos[5]['sw_paciente_no_pos'] = "0";
        $datos_medicamentos[5]['via_administracion_id'] = "1";
        $datos_medicamentos[5]['dosis'] = "1.00";
        $datos_medicamentos[5]['unidad_dosificacion'] = "TABLETA (S)";
        $datos_medicamentos[5]['tipo_opcion_posologia_id'] = "1";
        $datos_medicamentos[5]['cantidadperiocidad'] = "24";
        $datos_medicamentos[5]['justificacion_reformula'] = "N/A";
        $datos_medicamentos[5]['numero_formula'] = $numeroFormula;
        $datos_medicamentos[5]['dias_tratamiento'] = "5";
        $datos_medicamentos[5]['bloqueo'] = "0";
        $datos_medicamentos[5]['usuario_id'] = "3667";
        $datos_medicamentos[5]['usuario_id_modifica'] = "3667";
        $datos_medicamentos[5]['cantidad_dia'] = "1.00";
		
		
		
		
		
        $datos_antecedentes = array();

        $datos_antecedentes[0]['tipo_id_paciente'] = "CC";
        $datos_antecedentes[0]['paciente_id'] = $paciente;
        $datos_antecedentes[0]['codigo_medicamento'] = "FOFOA0031250";
        $datos_antecedentes[0]['medico_id'] = "3667";
        $datos_antecedentes[0]['dosis'] = "1";
        $datos_antecedentes[0]['unidad_dosificacion'] = "TABLETA (SIIS)";
        $datos_antecedentes[0]['frecuencia'] = "Cada 86 Hora(s)";
        $datos_antecedentes[0]['sw_permanente'] = "0";
        $datos_antecedentes[0]['sw_formulado'] = "1";
        $datos_antecedentes[0]['tiempo_total'] = "3 dia(s) ";
        $datos_antecedentes[0]['perioricidad_entrega'] = "9 (TABLETA O CAPSULA POR 500000)  cada docientos dia(s)";
        $datos_antecedentes[0]['descripcion'] = "PRUEBA IMPRESION MEDIA CARTA";
        $datos_antecedentes[0]['tiempo_perioricidad_entrega'] = "25";
        $datos_antecedentes[0]['unidad_perioricidad_entrega'] = "dia(s)"; 
        $datos_antecedentes[0]['cantidad'] = "9";
        $datos_antecedentes[0]['sw_mostrar'] = "1";
        $datos_antecedentes[0]['fecha_registro'] = "2015-03-09";
        $datos_antecedentes[0]['fecha_finalizacion'] = "2015-03-20";
        $datos_antecedentes[0]['fecha_formulacion'] = "2015-03-09";
        $datos_antecedentes[0]['fecha_modificacion'] = "NULL";
        $datos_antecedentes[0]['numero_formula'] = $numeroFormula;
        
        $datos_antecedentes[1]['tipo_id_paciente'] = "CC";
        $datos_antecedentes[1]['paciente_id'] = $paciente;
        $datos_antecedentes[1]['codigo_medicamento'] = "FOFOA0031251";
        $datos_antecedentes[1]['medico_id'] = "3667";
        $datos_antecedentes[1]['dosis'] = "1";
        $datos_antecedentes[1]['unidad_dosificacion'] = "TABLETA ZZZZZ (S)";
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
        $datos_antecedentes[1]['numero_formula'] = $numeroFormula;

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