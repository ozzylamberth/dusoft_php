<?php

require_once('../../nusoap/lib/nusoap.php');
$server = new nusoap_server;
$server->configureWSDL('FormulacionWs', 'urn:formulacion_ws');

require_once ("conexionpg.php");
//$url = "http://10.0.2.170/Pruebas_DUSOFT_DUANA_/ws/Formulacion/ws_formulacion.php?wsdl";
//$url = "http://10.0.2.170/DUSOFT_DUANA/ws/Formulacion/ws_formulacion.php?wsdl";
//======================= Registrar Funciones ==========================
// Insertar Formula
$server->register('sincronizarFormula', array('datos_evolucion' => 'tns:WS_datos_evolucion',
    'medicamentos_recetados' => 'tns:WS_listado_medicamentos_recetados',
    'formulacion_antecedentes' => 'tns:WS_listado_formulacion_antecedentes'), array('return' => 'tns:WS_resultado'), "urn:formulacion_ws", "urn:formulacion_ws#sincronizarFormula", "rpc", "encoded", "Webservice: Permite la Sincronizacion de las formulas de COSMITET - DUANA para el proceso de DISPENSACION");

// Insertar Transcripcion
$server->register('sincronizarTranscripcion', array('datos_evolucion' => 'tns:WS_datos_evolucion',
    'medicamentos_recetados' => 'tns:WS_listado_medicamentos_recetados',
    'formulacion_antecedentes' => 'tns:WS_listado_formulacion_antecedentes'), array('return' => 'tns:WS_resultado'), "urn:formulacion_ws", "urn:formulacion_ws#sincronizarFormula", "rpc", "encoded", "Webservice: Permite la Sincronizacion de las Transcripciones de COSMITET - DUANA para el proceso de DISPENSACION");

// Agregar Medicamento
$server->register('agregarMedicamentos', array('datos_evolucion' => 'tns:WS_datos_evolucion',
    'medicamentos_recetados' => 'tns:WS_listado_medicamentos_recetados',
    'formulacion_antecedentes' => 'tns:WS_listado_formulacion_antecedentes'), array('return' => 'tns:WS_resultado'), "urn:formulacion_ws", "urn:formulacion_ws#sincronizarFormula", "rpc", "encoded", "Webservice: Permite Agregar Medicamentos a una Formula enviada desde COSMITET - DUANA");

// Modificar Medicamento
$server->register('modificarMedicamentos', array('datos_evolucion' => 'tns:WS_datos_evolucion',
    'medicamentos_recetados' => 'tns:WS_listado_medicamentos_recetados',
    'formulacion_antecedentes' => 'tns:WS_listado_formulacion_antecedentes'), array('return' => 'tns:WS_resultado'), "urn:formulacion_ws", "urn:formulacion_ws#sincronizarFormula", "rpc", "encoded", "Webservice: Permite Modificar Medicamentos a una Formula enviada desde COSMITET - DUANA");

// Eliminar Medicamento
$server->register('eliminarMedicamento', array('numero_formula' => 'xsd:string',
    'codigo_producto' => 'xsd:string'), array('return' => 'tns:WS_resultado'), "urn:formulacion_ws", "urn:formulacion_ws#sincronizarFormula", "rpc", "encoded", "Webservice: Permite la Sincronizacion de las formulas de COSMITET - DUANA para el proceso de DISPENSACION");

/**
 * +Descripcion: Metodo encargado de consultar el estado de la formula
 */
// Agregar Medicamento
$server->register('consultar_dispensacion_formula', array('numero_formula' => 'xsd:string',
    'tipo_formula' => 'xsd:string'), array('return' => 'tns:WS_resultado_dispensacion_formula'), "urn:formulacion_ws", "urn:formulacion_ws#sincronizarFormula", "rpc", "encoded", "Webservice: Permite consultar si la formula ya tiene medicamentos despachados");

$server->wsdl->addComplexType('WS_resultado_dispensacion_formula', 'complexType', 'struct', 'all', '', array('msj' => array('name' => 'msj', 'type' => 'xsd:string'), 'continuar' => array('name' => 'continuar', 'type' => 'xsd:boolean'), 'tipoEstado' => array('name' => 'tipoEstado', 'type' => 'xsd:string')));
 



// Estructura para los datos de evolucion.
$server->wsdl->addComplexType('WS_datos_evolucion', 'complexType', 'struct', 'all', '', array(
    'numero_evolucion' => array('name' => 'numero_evolucion', 'type' => 'xsd:int'),
    'numero_formula' => array('name' => 'numero_formula', 'type' => 'xsd:string'),
    'ingreso' => array('name' => 'ingreso', 'type' => 'xsd:string'),
    'fecha' => array('name' => 'fecha', 'type' => 'xsd:string'),
    'usuario_id' => array('name' => 'usuario_id', 'type' => 'xsd:string'),
    'departamento' => array('name' => 'departamento', 'type' => 'xsd:string'),
    'estado' => array('name' => 'estado', 'type' => 'xsd:string'),
    'hc_modulo' => array('name' => 'hc_modulo', 'type' => 'xsd:string'),
    'sw_edicion' => array('name' => 'sw_edicion', 'type' => 'xsd:string'),
    'fecha_cierre' => array('name' => 'fecha_cierre', 'type' => 'xsd:string'),
    'numerodecuenta' => array('name' => 'numerodecuenta', 'type' => 'xsd:string'),
    'historia_clinica_tipo_cierre_id' => array('name' => 'historia_clinica_tipo_cierre_id', 'type' => 'xsd:string'),
    'observacion_cierre' => array('name' => 'observacion_cierre', 'type' => 'xsd:string'),
    'diag_principal' => array('name' => 'diag_principal', 'type' => 'xsd:string'),
    'plan_id' => array('name' => 'plan_id', 'type' => 'xsd:string'),
    'descripcion_plan' => array('name' => 'descripcion_plan', 'type' => 'xsd:string'),
    'modificacion' => array('name' => 'modificacion', 'type' => 'xsd:string'),
	'tipo_formula' => array('name' => 'tipo_formula', 'type' => 'xsd:string')
));

// Estructura para los datos  de medicamentos recetados
$server->wsdl->addComplexType('WS_datos_medicamentos_recetados', 'complexType', 'struct', 'all', '', array(
    'codigo_producto' => array('name' => 'codigo_producto', 'type' => 'xsd:string'),
    'cantidad' => array('name' => 'cantidad', 'type' => 'xsd:int'),
    'observacion' => array('name' => 'observacion', 'type' => 'xsd:string'),
    'sw_paciente_no_pos' => array('name' => 'sw_paciente_no_pos', 'type' => 'xsd:string'),
    'via_administracion_id' => array('name' => 'via_administracion_id', 'type' => 'xsd:string'),
    'dosis' => array('name' => 'dosis', 'type' => 'xsd:string'),
    'unidad_dosificacion' => array('name' => 'unidad_dosificacion', 'type' => 'xsd:string'),
    'tipo_opcion_posologia_id' => array('name' => 'tipo_opcion_posologia_id', 'type' => 'xsd:string'),
    'cantidadperiocidad' => array('name' => 'cantidadperiocidad', 'type' => 'xsd:string'),
    'justificacion_reformula' => array('name' => 'justificacion_reformula', 'type' => 'xsd:string'),
    'numero_formula' => array('name' => 'numero_formula', 'type' => 'xsd:string'),
    'dias_tratamiento' => array('name' => 'dias_tratamiento', 'type' => 'xsd:string'),
    'bloqueo' => array('name' => 'bloqueo', 'type' => 'xsd:string'),
    'usuario_id' => array('name' => 'usuario_id', 'type' => 'xsd:string'),
    'usuario_id_modifica' => array('name' => 'usuario_id_modifica', 'type' => 'xsd:string'),
    'cantidad_dia' => array('name' => 'cantidad_dia', 'type' => 'xsd:string')
));

// Estructura para la lista de datos de medicamentos recetados.
$server->wsdl->addComplexType('WS_listado_medicamentos_recetados', 'complexType', 'array', '', 'SOAP-ENC:Array', array(), array(
    array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:WS_datos_medicamentos_recetados[]')
        ), 'tns:WS_datos_medicamentos_recetados'
);

// Estructura para los datos  de formulacion de antecedentes.
$server->wsdl->addComplexType('WS_datos_formulacion_antecedentes', 'complexType', 'struct', 'all', '', array(
    'numero_formula' => array('name' => 'numero_formula', 'type' => 'xsd:int'),
    'tipo_id_paciente' => array('name' => 'tipo_id_paciente', 'type' => 'xsd:string'),
    'paciente_id' => array('name' => 'paciente_id', 'type' => 'xsd:string'),
    'medico_id' => array('name' => 'medico_id', 'type' => 'xsd:int'),
    'codigo_medicamento' => array('name' => 'codigo_medicamento', 'type' => 'xsd:string'),
    'dosis' => array('name' => 'dosis', 'type' => 'xsd:string'),
    'unidad_dosificacion' => array('name' => 'unidad_dosificacion', 'type' => 'xsd:string'),
    'frecuencia' => array('name' => 'frecuencia', 'type' => 'xsd:string'),
    'sw_permanente' => array('name' => 'sw_permanente', 'type' => 'xsd:string'),
    'sw_formulado' => array('name' => 'sw_formulado', 'type' => 'xsd:string'),
    'tiempo_total' => array('name' => 'tiempo_total', 'type' => 'xsd:string'),
    'perioricidad_entrega' => array('name' => 'perioricidad_entrega', 'type' => 'xsd:string'),
    'descripcion' => array('name' => 'descripcion', 'type' => 'xsd:string'),
    'tiempo_perioricidad_entrega' => array('name' => 'tiempo_perioricidad_entrega', 'type' => 'xsd:int'),
    'unidad_perioricidad_entrega' => array('name' => 'unidad_perioricidad_entrega', 'type' => 'xsd:string'),
    'cantidad' => array('name' => 'cantidad', 'type' => 'xsd:int'),
    'sw_mostrar' => array('name' => 'sw_mostrar', 'type' => 'xsd:int'),
    'fecha_registro' => array('name' => 'fecha_registro', 'type' => 'xsd:string'),
    'fecha_formulacion' => array('name' => 'fecha_formulacion', 'type' => 'xsd:string'),
    'fecha_finalizacion' => array('name' => 'fecha_finalizacion', 'type' => 'xsd:string'),
    'fecha_modificacion' => array('name' => 'fecha_modificacion', 'type' => 'xsd:string')
)); 

// Estructura para la lista de datos  de formulacion de antecedentes.
$server->wsdl->addComplexType('WS_listado_formulacion_antecedentes', 'complexType', 'array', '', 'SOAP-ENC:Array', array(), array(
    array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:WS_datos_formulacion_antecedentes[]')
        ), 'tns:WS_datos_formulacion_antecedentes'
);
 
// Estructura para los datos  de ingreso.
$server->wsdl->addComplexType('WS_datos_ingreso', 'complexType', 'struct', 'all', '', array('evolucion_id' => array('name' => 'evolucion_id', 'type' => 'xsd:string')));
 
// Estructura para los datos  de cuentas.
$server->wsdl->addComplexType('WS_datos_cuentas', 'complexType', 'struct', 'all', '', array('evolucion_id' => array('name' => 'evolucion_id', 'type' => 'xsd:string')));

// Estructura para los datos  de planes rangos.
$server->wsdl->addComplexType('WS_datos_planes_rangos', 'complexType', 'struct', 'all', '', array('evolucion_id' => array('name' => 'evolucion_id', 'type' => 'xsd:string')));

// Estructura para los datos  de planes.
$server->wsdl->addComplexType('WS_datos_planes', 'complexType', 'struct', 'all', '', array('evolucion_id' => array('name' => 'evolucion_id', 'type' => 'xsd:string')));

// Estructura de respuesta
$server->wsdl->addComplexType('WS_resultado', 'complexType', 'struct', 'all', '', array('msj' => array('name' => 'msj', 'type' => 'xsd:string'), 'estado' => array('name' => 'estado', 'type' => 'xsd:boolean'), 'datos' => array('name' => 'datos', 'type' => 'xsd:string')));
 
function sincronizarFormula($datos_evolucion, $medicamentos_recetados, $formulacion_antecedentes) {

     $datosPrincipales = "";
     $datosEvolucion = "";
     $medicamentosRecetados = "";

     registrar_logs('00001', '0000',"formulacion_antecedentes >>>".print_r($datos_evolucion,true), 4); 
     registrar_logs('00002', '0000',"medicamentos_recetados >>> ". print_r($medicamentos_recetados,true),4);
     registrar_logs('000085', '0000',"datos_evolucion >>> ".print_r($formulacion_antecedentes,true),4);

     foreach ($formulacion_antecedentes as $key => $value) {

        $datosEvolucion .=  " numero_formula ".  $value['numero_formula'] 
                         ." tipo_id_paciente:". $value['tipo_id_paciente']  
                         ." paciente_id: ". $value['paciente_id'] 
                         ." codigo_medicamento: ". $value['codigo_medicamento']  
                         ." fecha_registro: ".$value['fecha_registro']  
                         ." fecha_finalizacion: ". $value['fecha_finalizacion']  
                         ." medico_id:". $value['medico_id'] 
                         ." dosis: ". $value['dosis'] 
                         ." unidad_dosificacion: ". $value['unidad_dosificacion']  
                         ." frecuencia: ". $value['frecuencia']                        
                         ." tiempo_total: ". $value['tiempo_total'] 
                         ." perioricidad_entrega: ".$value['perioricidad_entrega'] 
                         ." descripcion: ". $value['descripcion']  
                         ." fecha_formulacion: ". $value['fecha_formulacion']                      
                         ." tiempo_perioricidad_entrega: ".$value['tiempo_perioricidad_entrega']
                         ." unidad_perioricidad_entrega: ".$value['unidad_perioricidad_entrega']                        
                         ." cantidad: ".$value['cantidad']                       
                         ." sw_mostrar: ". $value['sw_mostrar']  
                         ." justificacion_reformula: ". $value['justificacion_reformula'] 
                         ." sw_anulado: ". $value['sw_anulado']  
                         ." sw_autorizado: ". $value['sw_autorizado']                        
                         ." observacion_autorizacion: ". $value['observacion_autorizacion']  
                         ." sw_estado: ". $value['sw_estado'];
						 
						
                        
                  
     }


      foreach ($medicamentos_recetados as $key => $value) {

        $medicamentosRecetados .=    
						 " codigo_producto: ".$value['codigo_producto'] 
                        ." cantidad: ".$value['cantidad']
                        ." observacion: ".  $value['observacion']
                        ." sw_paciente_no_pos: ".$value['sw_paciente_no_pos']  
                        ." via_administracion_id: ".$value['via_administracion_id'] 
                        ." dosis: ".$value['dosis']  
                        ." unidad_dosificacion: ".$value['unidad_dosificacion'] 
                        ." tipo_opcion_posologia_id: ".$value['tipo_opcion_posologia_id']
                        ." cantidadperiocidad: ".$value['cantidadperiocidad'] 
                        ." justificacion_reformula: ".$value['justificacion_reformula']  
                        ." numero_formula: ".$value['numero_formula']  
                        ." dias_tratamiento". $value['dias_tratamiento']  
                        ." bloqueo: ".$value['bloqueo']  
                        ." usuario_id: ".  $value['usuario_id']           
                        ." usuario_id_modifica: ".$value['usuario_id_modifica']
                        ." cantidad_dia: ".$value['cantidad_dia'] ;
                      
                        
                   
     }

     
      
     
   
        $datosPrincipales .=  "numero_evolucion: ". $datos_evolucion['numero_evolucion']." - numero_formula".
                         $datos_evolucion['numero_formula'] ." - ingreso ".  
                         $datos_evolucion['ingreso'] ." - fecha ".
                         $datos_evolucion['fecha']  ." -  usuario_id ".
                         $datos_evolucion['usuario_id']  ." - departamento ".
                         $datos_evolucion['departamento']  ." - estado".
                         $datos_evolucion['estado'] ." - estahc_modulodo ".
                         $datos_evolucion['hc_modulo'] ." - sw_edicion ".
                         $datos_evolucion['sw_edicion']  ." -fecha_cierre ".
                         $datos_evolucion['fecha_cierre']  ." numerodecuenta ".                    
                         $datos_evolucion['numerodecuenta']  ." historia_clinica_tipo_cierre_id ".
                         $datos_evolucion['historia_clinica_tipo_cierre_id']  ." observacion_cierre ".
                         $datos_evolucion['observacion_cierre']  ." diag_principal ".
                         $datos_evolucion['diag_principal']  ." plan_id ".                    
                         $datos_evolucion['plan_id']." descripcion_plan ".
                         $datos_evolucion['descripcion_plan'] ."".
                         $datos_evolucion['modificacion']."tipo_formula: ".
                         $datos_evolucion['tipo_formula'];
                        
 


     registrar_logs('00001', '0000',"formulacion_antecedentes >>>".$datosEvolucion, 2);
     registrar_logs('00002', '0000',"medicamentos_recetados >>> ". $medicamentosRecetados,2);
     registrar_logs('000085', '0000',"datos_evolucion >>> ".$datosPrincipales,2);
	//die(); 
    // Validacion de Datos    
    $validacion_evolucion = validar_datos_evolucion_formulacion($datos_evolucion);
    $validacion_medicamentos_recetados = validacion_medicamentos($medicamentos_recetados);
    $validacion_formulacion_antecedentes = validacion_formulacion_antecedentes($formulacion_antecedentes);

    // Validar si el numero de formula existe
    $validar_formula = consultar_formula($datos_evolucion['numero_formula'],
									     $datos_evolucion['modificacion'],
										 $datos_evolucion['tipo_formula']);

    // Validar si la formula no ha sido despachada
    $validar_dispensacion = consultar_dispensacion_formula($datos_evolucion['numero_formula'], 
														   $datos_evolucion['tipo_formula']);


    $msjs = array_merge($validacion_evolucion['msj'], $validacion_medicamentos_recetados['msj'], $validacion_formulacion_antecedentes['msj'], $validar_formula['msj'], $validar_dispensacion['msj']);
    
	
    if ($validacion_evolucion['continuar'] && $validacion_medicamentos_recetados['continuar'] && $validacion_formulacion_antecedentes['continuar'] && $validar_formula['continuar'] && $validar_dispensacion['continuar']) {
		
		//registrar_logs('00002', '0000',"$datos_evolucion['modificacion']".$datos_evolucion['modificacion'],2);
        if ($datos_evolucion['modificacion'] == "1") {
            eliminar_formulacion($validar_dispensacion['evolucion_id']);
        }

        // Insertar Cabecera Evolucion
        $es_transcripcion = false;
        $resultado_insertar_evolucion = insertar_evolucion($datos_evolucion, $es_transcripcion);
		
        if (!is_null($resultado_insertar_evolucion['evolucion_id'])) {

            $evolucion_id = $resultado_insertar_evolucion['evolucion_id'];

            // Insertar Medicamentos Recetados
            $resultado_insertar_medicamentos_recetados = insertar_medicamentos_recetados($evolucion_id, 
																					     $medicamentos_recetados, 
																						 $es_transcripcion,
																						 $datos_evolucion['tipo_formula']);

            if ($resultado_insertar_medicamentos_recetados['continuar']) {

                //Insertar Formulaci?n Antecedentes
                $resultado_insertar_formulacion_antecedentes = insertar_formulacion_antecedentes($evolucion_id, 
																								 $formulacion_antecedentes, 
																								 $es_transcripcion,
																								 $datos_evolucion['tipo_formula']);


                if ($resultado_insertar_formulacion_antecedentes['continuar']) {

                    registrar_logs($evolucion_id, $datos_evolucion['numero_formula'], "Formula Ingresada Correctamnte en DUANA");

                    return array('msj' => "Formula Ingresada Correctamente en DUANA", 'estado' => (bool) '1', 'datos' => '');
                } else {

                    $resultado_eliminar_evolucion = eliminar_formulacion($evolucion_id);

                    registrar_logs($evolucion_id, $datos_evolucion['numero_formula'], $resultado_insertar_formulacion_antecedentes['msj']);

                    return array('msj' => $resultado_insertar_formulacion_antecedentes['msj'], 'estado' => (bool) '0', 'datos' => $resultado_eliminar_evolucion);
                }
            } else {
                $resultado_eliminar_evolucion = eliminar_formulacion($evolucion_id);

                registrar_logs($evolucion_id, $datos_evolucion['numero_formula'], $resultado_insertar_medicamentos_recetados['msj']);

                return array('msj' => $resultado_insertar_medicamentos_recetados['msj'], 'estado' => (bool) '0', 'datos' => $resultado_eliminar_evolucion);
            }
        } else {

            registrar_logs($resultado_insertar_evolucion['evolucion_id'], $datos_evolucion['numero_formula'], $resultado_insertar_evolucion['msj']);

            return array('msj' => $resultado_insertar_evolucion['msj'], 'estado' => (bool) '0', 'datos' => '');
        }
    } else {

        registrar_logs(NULL, $datos_evolucion['numero_formula'], join(",", $msjs));

        return array('msj' => join(",", $msjs), 'estado' => (bool) '0', 'datos' => '');
    }
}

function sincronizarTranscripcion($datos_evolucion, $medicamentos_recetados, $formulacion_antecedentes) {

    // Validacion de Datos    
    $validacion_evolucion = validar_datos_evolucion_transcripcion($datos_evolucion);
    $validacion_medicamentos_recetados = validacion_medicamentos($medicamentos_recetados);
    $validacion_formulacion_antecedentes = validacion_formulacion_antecedentes($formulacion_antecedentes);

    // Validar si el numero de formula existe
    $validar_formula = consultar_formula($datos_evolucion['numero_formula'], $datos_evolucion['modificacion'],$datos_evolucion['tipo_formula']);

    // Validar si la formula no ha sido despachada
    $validar_dispensacion = consultar_dispensacion_formula($datos_evolucion['numero_formula'],$datos_evolucion['tipo_formula']);

    $msjs = array_merge($validacion_evolucion['msj'], $validacion_medicamentos_recetados['msj'], $validacion_formulacion_antecedentes['msj'], $validar_formula['msj'], $validar_dispensacion['msj']);


    if ($validacion_evolucion['continuar'] && $validacion_medicamentos_recetados['continuar'] && $validacion_formulacion_antecedentes['continuar'] && $validar_formula['continuar'] && $validar_dispensacion['continuar']) {

        if ($datos_evolucion['modificacion'] == "1") {
            eliminar_formulacion($validar_dispensacion['evolucion_id']);
        }

        // Insertar Cabecera Evolucion
        $es_transcripcion = true;
        $resultado_insertar_evolucion = insertar_evolucion($datos_evolucion, $es_transcripcion);

        if (!is_null($resultado_insertar_evolucion['evolucion_id'])) {

            $evolucion_id = $resultado_insertar_evolucion['evolucion_id'];

            // Insertar Medicamentos Recetados
            $resultado_insertar_medicamentos_recetados = insertar_medicamentos_recetados($evolucion_id, 
																						 $medicamentos_recetados, 
																						 $es_transcripcion,
																						 $datos_evolucion['tipo_formula']);

            if ($resultado_insertar_medicamentos_recetados['continuar']) {

                //Insertar Formulaci?n Antecedentes
                $resultado_insertar_formulacion_antecedentes = insertar_formulacion_antecedentes($evolucion_id, 
																								 $formulacion_antecedentes, 
																								 $es_transcripcion,
																								 $datos_evolucion['tipo_formula']);


                if ($resultado_insertar_formulacion_antecedentes['continuar']) {

                    registrar_logs($evolucion_id, $datos_evolucion['numero_formula'], "Formula Ingresada Correctamnte en DUANA");

                    return array('msj' => "Formula Ingresada Correctamnte en DUANA", 'estado' => (bool) '1', 'datos' => '');
                } else {

                    $resultado_eliminar_evolucion = eliminar_formulacion($evolucion_id);

                    registrar_logs($evolucion_id, $datos_evolucion['numero_formula'], $resultado_insertar_formulacion_antecedentes['msj']);

                    return array('msj' => $resultado_insertar_formulacion_antecedentes['msj'], 'estado' => (bool) '0', 'datos' => $resultado_eliminar_evolucion);
                }
            } else {
                $resultado_eliminar_evolucion = eliminar_formulacion($evolucion_id);

                registrar_logs($evolucion_id, $datos_evolucion['numero_formula'], $resultado_insertar_medicamentos_recetados['msj']);

                return array('msj' => $resultado_insertar_medicamentos_recetados['msj'], 'estado' => (bool) '0', 'datos' => $resultado_eliminar_evolucion);
            }
        } else {

            registrar_logs($resultado_insertar_evolucion['evolucion_id'], $datos_evolucion['numero_formula'], $resultado_insertar_evolucion['msj']);

            return array('msj' => $resultado_insertar_evolucion['msj'], 'estado' => (bool) '0', 'datos' => '');
        }
    } else {

        registrar_logs(NULL, $datos_evolucion['numero_formula'], join(",", $msjs));

        return array('msj' => join(",", $msjs), 'estado' => (bool) '0', 'datos' => '');
    }
}

function _agregarMedicamentos($datos_evolucion, $medicamentos_recetados, $formulacion_antecedentes) {

    // Validacion de Datos  
    $es_transcripcion = ($datos_evolucion['es_transcripcion'] == "1") ? true : false;

    $validacion_evolucion = validar_datos_evolucion_formulacion($datos_evolucion);

    if ($es_transcripcion) {
        $validacion_evolucion = validar_datos_evolucion_transcripcion($datos_evolucion);
    }

    $validacion_medicamentos_recetados = validacion_medicamentos($medicamentos_recetados);
    $validacion_formulacion_antecedentes = validacion_formulacion_antecedentes($formulacion_antecedentes);

    // Validar si la formula no ha sido despachada
    $validar_dispensacion = consultar_dispensacion_formula($datos_evolucion['numero_formula'],$datos_evolucion['tipo_formula']);

    $msjs = array_merge($validacion_evolucion['msj'], $validacion_medicamentos_recetados['msj'], $validacion_formulacion_antecedentes['msj'], $validar_dispensacion['msj']);

    if ($validacion_evolucion['continuar'] && $validacion_medicamentos_recetados['continuar'] && $validacion_formulacion_antecedentes['continuar'] && $validar_dispensacion['continuar']) {

        // Insertar Medicamentos        
        $evolucion_id = $validar_dispensacion['evolucion_id'];

        // Insertar Medicamentos Recetados
        $resultado_insertar_medicamentos_recetados = insertar_medicamentos_recetados($evolucion_id, 
		                                                                             $medicamentos_recetados, 
																					 $es_transcripcion,
																					 $datos_evolucion['tipo_formula']);

        if ($resultado_insertar_medicamentos_recetados['continuar']) {

            //Insertar Formulaci?n Antecedentes
            $resultado_insertar_formulacion_antecedentes = insertar_formulacion_antecedentes($evolucion_id, 
																							 $formulacion_antecedentes, 
																							 $es_transcripcion,
																							 $datos_evolucion['tipo_formula']);


            if ($resultado_insertar_formulacion_antecedentes['continuar']) {

                registrar_logs($evolucion_id, $datos_evolucion['numero_formula'], "Medicamento Ingresado Correctamente en la Formula No.{$datos_evolucion['numero_formula']}");

                return array('msj' => "Medicamento Ingresado Correctamente en la Formula No.{$datos_evolucion['numero_formula']}", 'estado' => (bool) '1', 'datos' => '');
            } else {

                //??$resultado_eliminar_evolucion = eliminar_formulacion($evolucion_id);

                registrar_logs($evolucion_id, $datos_evolucion['numero_formula'], $resultado_insertar_formulacion_antecedentes['msj']);

                return array('msj' => $resultado_insertar_formulacion_antecedentes['msj'], 'estado' => (bool) '0', 'datos' => $resultado_eliminar_evolucion);
            }
        } else {
            //?$resultado_eliminar_evolucion = eliminar_formulacion($evolucion_id);

            registrar_logs($evolucion_id, $datos_evolucion['numero_formula'], $resultado_insertar_medicamentos_recetados['msj']);

            return array('msj' => $resultado_insertar_medicamentos_recetados['msj'], 'estado' => (bool) '0', 'datos' => $resultado_eliminar_evolucion);
        }
    } else {

        registrar_logs(NULL, $datos_evolucion['numero_formula'], join(",", $msjs));

        return array('msj' => join(",", $msjs), 'estado' => (bool) '0', 'datos' => '');
    }
}

function _modificarMedicamentos($datos_evolucion, $medicamentos_recetados, $formulacion_antecedentes) {

    // Validacion de Datos  
    $es_transcripcion = ($datos_evolucion['es_transcripcion'] == "1") ? true : false;

    $validacion_evolucion = validar_datos_evolucion_formulacion($datos_evolucion);

    if ($es_transcripcion) {
        $validacion_evolucion = validar_datos_evolucion_transcripcion($datos_evolucion);
    }

    $validacion_medicamentos_recetados = validacion_medicamentos($medicamentos_recetados);
    $validacion_formulacion_antecedentes = validacion_formulacion_antecedentes($formulacion_antecedentes);

    // Validar si la formula no ha sido despachada
    $validar_dispensacion = consultar_dispensacion_formula($datos_evolucion['numero_formula'],$datos_evolucion['tipo_formula']);

    $msjs = array_merge($validacion_evolucion['msj'], $validacion_medicamentos_recetados['msj'], $validacion_formulacion_antecedentes['msj'], $validar_dispensacion['msj']);

    if ($validacion_evolucion['continuar'] && $validacion_medicamentos_recetados['continuar'] && $validacion_formulacion_antecedentes['continuar'] && $validar_dispensacion['continuar']) {

        // Modificar Medicamentos        
        $evolucion_id = $validar_dispensacion['evolucion_id'];

        // Modificar Medicamentos Recetados
        $resultado_modificar_medicamentos_recetados = modificar_medicamentos_recetados($evolucion_id, $medicamentos_recetados, $es_transcripcion);

        if ($resultado_modificar_medicamentos_recetados['continuar']) {

            //Modificar Formulaci?n Antecedentes
            $resultado_modificar_formulacion_antecedentes = modificar_formulacion_antecedentes($evolucion_id, $formulacion_antecedentes, $es_transcripcion);


            if ($resultado_modificar_formulacion_antecedentes['continuar']) {

                registrar_logs($evolucion_id, $datos_evolucion['numero_formula'], "Medicamentos Modificados Correctamente en la Formula No.{$datos_evolucion['numero_formula']}");

                return array('msj' => "Medicamentos Modificados Correctamente en la Formula No.{$datos_evolucion['numero_formula']}", 'estado' => (bool) '1', 'datos' => '');
            } else {

                //??$resultado_eliminar_evolucion = eliminar_formulacion($evolucion_id);

                registrar_logs($evolucion_id, $datos_evolucion['numero_formula'], $resultado_modificar_formulacion_antecedentes['msj']);

                return array('msj' => $resultado_modificar_formulacion_antecedentes['msj'], 'estado' => (bool) '0', 'datos' => $resultado_eliminar_evolucion);
            }
        } else {
            //?$resultado_eliminar_evolucion = eliminar_formulacion($evolucion_id);

            registrar_logs($evolucion_id, $datos_evolucion['numero_formula'], $resultado_modificar_medicamentos_recetados['msj']);

            return array('msj' => $resultado_modificar_medicamentos_recetados['msj'], 'estado' => (bool) '0', 'datos' => $resultado_eliminar_evolucion);
        }
    } else {

        registrar_logs(NULL, $datos_evolucion['numero_formula'], join(",", $msjs));

        return array('msj' => join(",", $msjs), 'estado' => (bool) '0', 'datos' => '');
    }
}

function _eliminarMedicamento($numero_formula, $codigo_producto) {

    $msj = array();
    $continuar = true;

    if ($numero_formula == "") {
        $continuar = false;
        array_push($msj, "numero_formula inv?lida");
    }

    if ($codigo_producto == "") {
        $continuar = false;
        array_push($msj, "Codigo Producto Invalido");
    }
    $msjs = join(",", $msj);

    if ($continuar) {

        $resultado_eliminar_medicamento_recetado = eliminar_medicamento_recetado($evolucion_id, $codigo_producto);
        $resultado_eliminar_formulacion_antecedente = eliminar_formulacion_antecedente($evolucion_id, $codigo_producto);
    } else {
        return array('msj' => join(",", $msjs), 'estado' => (bool) '0', 'datos' => '');
    }
}

function validar_datos_evolucion_formulacion($datos_evolucion) {

    $msj = array();
    $continuar = true;

    if (empty($datos_evolucion)) {
        $continuar = false;
        array_push($msj, "Los datos de la evolucion est?n vacios");
    }

    /* if ($datos_evolucion['numero_evolucion'] == "" || is_null($datos_evolucion['numero_evolucion']) || $datos_evolucion['numero_evolucion'] == 0 || $datos_evolucion['numero_evolucion'] == "0") {
      $continuar = false;
      array_push($msj, "El numero_evolucion esta vacio y no pude ser 0");
      } */

    if (trim($datos_evolucion['numero_formula']) == "") {
        $continuar = false;
        array_push($msj, "numero_formula inv?lida");
    }
 
    if (trim($datos_evolucion['modificacion']) == "") {
        $continuar = false;
        array_push($msj, "campo modificacion inv?lida");
    }

    if (trim($datos_evolucion['ingreso']) == "" || is_null($datos_evolucion['ingreso']) || $datos_evolucion['ingreso'] == 0 || $datos_evolucion['ingreso'] == "0") {
        $continuar = false;
        array_push($msj, "El Ingreso esta vacio y no pude ser 0");
    }

    if (trim($datos_evolucion['fecha']) == "" || is_null($datos_evolucion['fecha']) || $datos_evolucion['fecha'] == 0 || $datos_evolucion['fecha'] == "0") {
        $continuar = false;
        array_push($msj, "Fecha Incorrecta");
    }

    if (trim($datos_evolucion['usuario_id']) == "" || is_null($datos_evolucion['usuario_id']) || $datos_evolucion['usuario_id'] == 0 || $datos_evolucion['usuario_id'] == "0") {
        $continuar = false;
        array_push($msj, "usuario_id Incorrecta");
    }

    if (trim($datos_evolucion['departamento']) == "" || is_null($datos_evolucion['departamento']) || $datos_evolucion['departamento'] == 0 || $datos_evolucion['departamento'] == "0") {
        $continuar = false;
        array_push($msj, "departamento Incorrecto");
    }

    if (trim($datos_evolucion['estado']) == "" || is_null($datos_evolucion['estado']) || $datos_evolucion['estado'] == "null") {
        $continuar = false;
        array_push($msj, "estado Incorrecto");
    }

    if (trim($datos_evolucion['hc_modulo']) == "" || is_null($datos_evolucion['hc_modulo'])) {
        $continuar = false;
        array_push($msj, "hc_modulo Incorrecto");
    }

    if (trim($datos_evolucion['sw_edicion']) == "" || is_null($datos_evolucion['sw_edicion'])) {
        $continuar = false;
        array_push($msj, "sw_edicion Incorrecto");
    }

    if (trim($datos_evolucion['fecha_cierre']) == "" || is_null($datos_evolucion['fecha_cierre']) || $datos_evolucion['fecha_cierre'] == 0 || $datos_evolucion['fecha_cierre'] == "0") {
        $continuar = false;
        array_push($msj, "fecha_cierre Incorrecto");
    }

    return array("continuar" => $continuar, "msj" => $msj);
}

function validar_datos_evolucion_transcripcion($datos_evolucion) {

    $msj = array();
    $continuar = true;

    if (empty($datos_evolucion)) {
        $continuar = false;
        array_push($msj, "Los datos de la evolucion est?n vacios");
    }

    /* if ($datos_evolucion['numero_evolucion'] == "" || is_null($datos_evolucion['numero_evolucion']) || $datos_evolucion['numero_evolucion'] == 0 || $datos_evolucion['numero_evolucion'] == "0") {
      $continuar = false;
      array_push($msj, "El numero_evolucion esta vacio y no pude ser 0");
      } */

    /* if ($datos_evolucion['ingreso'] == "" || is_null($datos_evolucion['ingreso']) || $datos_evolucion['ingreso'] == 0 || $datos_evolucion['ingreso'] == "0") {
      $continuar = false;
      array_push($msj, "El Ingreso esta vacio y no pude ser 0");
      } */

    if (trim($datos_evolucion['numero_formula']) == "") {
        $continuar = false;
        array_push($msj, "numero_formula inv?lida");
    }

    if (trim($datos_evolucion['modificacion']) == "") {
        $continuar = false;
        array_push($msj, "campo modificacion inv?lida");
    }

    if (trim($datos_evolucion['fecha']) == "" || is_null($datos_evolucion['fecha']) || $datos_evolucion['fecha'] == 0 || $datos_evolucion['fecha'] == "0") {
        $continuar = false;
        array_push($msj, "Fecha Incorrecta");
    }

    if (trim($datos_evolucion['usuario_id']) == "" || is_null($datos_evolucion['usuario_id']) || $datos_evolucion['usuario_id'] == 0 || $datos_evolucion['usuario_id'] == "0") {
        $continuar = false;
        array_push($msj, "usuario_id Incorrecta");
    }

    /* if ($datos_evolucion['departamento'] == "" || is_null($datos_evolucion['departamento']) || $datos_evolucion['departamento'] == 0 || $datos_evolucion['departamento'] == "0") {
      $continuar = false;
      array_push($msj, "departamento Incorrecto");
      } */

    if (trim($datos_evolucion['estado']) == "" || is_null($datos_evolucion['estado']) || $datos_evolucion['estado'] == "null") {
        $continuar = false;
        array_push($msj, "estado Incorrecto");
    }

    /* if ($datos_evolucion['hc_modulo'] == "" || is_null($datos_evolucion['hc_modulo'])) {
      $continuar = false;
      array_push($msj, "hc_modulo Incorrecto");
      } */

    if (trim($datos_evolucion['sw_edicion']) == "" || is_null($datos_evolucion['sw_edicion'])) {
        $continuar = false;
        array_push($msj, "sw_edicion Incorrecto");
    }

    /* if ($datos_evolucion['fecha_cierre'] == "" || is_null($datos_evolucion['fecha_cierre']) || $datos_evolucion['fecha_cierre'] == 0 || $datos_evolucion['fecha_cierre'] == "0") {
      $continuar = false;
      array_push($msj, "fecha_cierre Incorrecto");
      } */

    return array("continuar" => $continuar, "msj" => $msj);
}

function validacion_medicamentos($medicamentos_recetados) {

    $msj = array();
    $continuar = true;

    if (empty($medicamentos_recetados)) {
        $continuar = false;
        array_push($msj, "Los datos de medicamentos_recetados estan vacios ");
    }

    foreach ($medicamentos_recetados as $key => $value) {

        if (trim($value['codigo_producto']) == "" || is_null($value['codigo_producto'])) {
            $continuar = false;
            array_push($msj, "codigo_producto inv?lido");
        }
        if (trim($value['cantidad']) == "" || is_null($value['cantidad']) || $value['cantidad'] < 1) {
            $continuar = false;
            array_push($msj, "{$value['cantidad']} cantidad en hc_medicamentos_recetados_amb inv?lido");
        }
        if (trim($value['observacion']) == "") {
            $continuar = false;
            array_push($msj, "observacion inv?lido");
        }
        if (trim($value['sw_paciente_no_pos']) == "" || is_null($value['sw_paciente_no_pos'])|| $value['sw_paciente_no_pos'] == "NULL") {
            $continuar = false;
            array_push($msj, "sw_paciente_no_pos inv?lido");
        }
        if (trim($value['via_administracion_id']) == "") {
            $continuar = false;
            array_push($msj, "via_administracion_id inv?lido");
        }
        if (trim($value['dosis']) == "") {
            $continuar = false;
            array_push($msj, "dosis inv?lido");
        }
        if (trim($value['unidad_dosificacion']) == "") {
            $continuar = false;
            array_push($msj, "unidad_dosificacion inv?lido");
        }
        if (trim($value['tipo_opcion_posologia_id']) == "") {
            $continuar = false;
            array_push($msj, "tipo_opcion_posologia_id inv?lido");
        }
        if (trim($value['cantidadperiocidad']) == "" || $value['cantidadperiocidad'] < 1) {
            $continuar = false;
            array_push($msj, "cantidadperiocidad inv?lido");
        }
        if (trim($value['justificacion_reformula']) == "") {
            $continuar = false;
            array_push($msj, "justificacion_reformula inv?lido");
        }
        if (trim($value['numero_formula']) == "") {
            $continuar = false;
            array_push($msj, "numero_formula inv?lido");
        }
        if (trim($value['dias_tratamiento']) == "" || $value['dias_tratamiento'] < 1) {
            $continuar = false;
            array_push($msj, "dias_tratamiento inv?lido");
        }
        if (trim($value['bloqueo']) == "") {
            $continuar = false;
            array_push($msj, "bloqueo inv?lido");
        }
        if (trim($value['usuario_id']) == "") {
            $continuar = false;
            array_push($msj, "usuario_id inv?lido");
        }
        if (trim($value['usuario_id_modifica']) == "") {
            $continuar = false;
            array_push($msj, "usuario_id_modifica inv?lido");
        }
        if (trim($value['cantidad_dia']) == "" || $value['cantidad_dia'] < 1) {
            $continuar = false;
            array_push($msj, "cantidad_dia inv?lido");
        }
    }

    return array("continuar" => $continuar, "msj" => $msj);
}

function validacion_formulacion_antecedentes($formulacion_antecedentes) {

    $msj = array();
    $continuar = true;

    if (empty($formulacion_antecedentes)) {
        $continuar = false;
        array_push($msj, "Los datos de formulacion_antecedentes estan vacios ");
    }

    foreach ($formulacion_antecedentes as $key => $value) {

        if (trim($value['numero_formula']) == "") {
            $continuar = false;
            array_push($msj, "numero_formula inv?lida");
        }

        if (trim($value['tipo_id_paciente']) == "") {
            $continuar = false;
            array_push($msj, "tipo_id_paciente inv?lido");
        }
        if (trim($value['paciente_id']) == "") {
            $continuar = false;
            array_push($msj, "paciente_id inv?lido");
        }
        if (trim($value['medico_id']) == "") {
            $continuar = false;
            array_push($msj, "medico_id inv?lido");
        }
        if (trim($value['codigo_medicamento']) == "") {
            $continuar = false;
            array_push($msj, "codigo_medicamento inv?lido");
        }
        if (trim($value['dosis']) == "") {
            $continuar = false;
            array_push($msj, "dosis inv?lido");
        }
        if (trim($value['unidad_dosificacion']) == "") {
            $continuar = false;
            array_push($msj, "unidad_dosificacion inv?lido");
        }
        if (trim($value['frecuencia']) == "") {
            $continuar = false;
            array_push($msj, "frecuencia inv?lido");
        }
        if (trim($value['sw_permanente']) == "") {
            $continuar = false;
            array_push($msj, "sw_permanente inv?lido");
        }
        if (trim($value['sw_formulado']) == "") {
            $continuar = false;
            array_push($msj, "sw_formulado inv?lido");
        }
        if (trim($value['tiempo_total']) == "") {
            $continuar = false;
            array_push($msj, "tiempo_total inv?lido");
        }
        if (trim($value['perioricidad_entrega']) == "") {
            $continuar = false;
            array_push($msj, "perioricidad_entrega inv?lido");
        }
        if (trim($value['descripcion']) == "") {
            $continuar = false;
            array_push($msj, "descripcion");
        }
        if (trim($value['tiempo_perioricidad_entrega']) == "") {
            $continuar = false;
            array_push($msj, "tiempo_perioricidad_entrega inv?lido");
        }
        if (trim($value['unidad_perioricidad_entrega']) == "") {
            $continuar = false;
            array_push($msj, "unidad_perioricidad_entrega inv?lido");
        }
        if (trim($value['cantidad']) == "") {
            $continuar = false;
            array_push($msj, "cantidad en hc_formulacion_antecedentes inv?lida");
        }
        if (trim($value['sw_mostrar']) == "") {
            $continuar = false;
            array_push($msj, "sw_mostrar inv?lido");
        }
        if (trim($value['fecha_registro']) == "") {
            $continuar = false;
            array_push($msj, "fecha_registro inv?lido");
        }
        if (trim($value['fecha_finalizacion']) == "") {
            $continuar = false;
            array_push($msj, "fecha_finalizacion inv?lido");
        }
        if (trim($value['fecha_formulacion']) == "") {
            $continuar = false;
            array_push($msj, "fecha_formulacion inv?lido");
        }
        if (trim($value['fecha_modificacion']) == "") {
            $continuar = false;
            array_push($msj, "fecha_modificacion inv?lido");
        }
    }

    return array("continuar" => $continuar, "msj" => $msj);
}

/*
*+Descripcion: metodo encargado de validar la existencia de una formula en la tabla hc_formulacion_antecedentes
               si la formula ya existe retornara un mensaje corroborando que la Formula No ... ya existe y pasara
			   al metodo consultar_dispensacion_formula el cual retornara el mensaje Formula No... no ha sido despachada
			   
			   si la formula no existe retornara un mensaje corroborando que la Formula NO Existe y pasara 
			   al metodo consultar_dispensacion_formula el cual retornara el mensaje Formula No... no ha sido econtrada
*/
function consultar_formula($numero_formula, $modificacion = "0",$tipo_formula) {

    global $conexion;

    $sql = "select * from hc_formulacion_antecedentes a where a.numero_formula = '{$numero_formula}' AND a.transcripcion_medica ='{$tipo_formula}'";
    registrar_logs('000','000', $sql,'1');
    $result = pg_query($conexion, $sql);

    if ($result) {

        $continuar = true;
        $msj = "La Formula NO Existe";

        if (pg_num_rows($result) > 0) {

            $continuar = false;
            if ($modificacion == "1")
                $continuar = true;

            $msj = "La Formula No.{$numero_formula} ya existe";
        }
    } else {
        $continuar = false;
        $msj = "Se ha generado un error consultado en hc_formulacion_antecedentes ( " . pg_last_error($conexion) . " ) ";
    }

    return array("msj" => $msj, "continuar" => $continuar);
}

/**
* +Descripcion: Metodo encargado de consultar la dispensacion de una formula segun el tipo de formula
*               Formulaci?n_medicamentos=0
*				Formulaci?n_insumos=2
*				Transcripci?n_medicamentos=1
*				Transcripci?n_insumos=3
*/
function consultar_dispensacion_formula($numero_formula,$tipo_formula) {

    global $conexion;
    $evolucion_id = 0;
    $msj = "";
    $continuar = false;

    // Consultar No. Evolucion
    $sql = "select a.evolucion_id from hc_formulacion_antecedentes a where a.numero_formula ='{$numero_formula}' AND a.transcripcion_medica ='{$tipo_formula}' group by 1";
    registrar_logs($evolucion_id,$evolucion_id.'000', $sql,'1');
    $result = pg_query($conexion, $sql);

    if ($result) {
        
		/*
		*+Descripcion: se valida si la formula esta en la tabla hc_formulacion_antecedentes
		*/
        if (pg_num_rows($result) > 0) {

            $arr = pg_fetch_object($result);
            $msj = "Formula Encontrada";
            $evolucion_id = $arr->evolucion_id;

            // Consultar Dispensacion
            $sql = "select * from (
                        select a.evolucion_id,1 from hc_formulacion_despachos_medicamentos a
                        union
                        select a.evolucion_id,2 from hc_formulacion_despachos_medicamentos_pendientes a
                        union
                        select a.evolucion_id,3 from hc_dispensacion_medicamentos_tmp a                        
                    ) as b where b.evolucion_id={$evolucion_id}";
         registrar_logs($evolucion_id,$evolucion_id.'000', $sql,'9');
            $result = pg_query($conexion, $sql);

            if ($result) {
                $continuar = true;
                $msj = "La Formula No.{$numero_formula} no ha sido despachada.";
                if (pg_num_rows($result) > 0) {
                    $continuar = false;
                    $msj = "La Formula No.{$numero_formula} ya tiene medicamentos despachados";
                }
            } else {
                $msj = "Se ha genereado un error consultado hc_formulacion_despachos_medicamentos (" . pg_last_error($conexion) . " )";
            }
        } else {
            $continuar = true;
            $msj = "Formula No.{$numero_formula} no ha sido encontrada ";
        }
    } else {
        $msj = "Se ha genereado un error consultado hc_formulacion_antecedentes (" . pg_last_error($conexion) . " )";
    }

    return array("msj" => $msj, "continuar" => $continuar, "evolucion_id" => $evolucion_id);
}

/**
*+Descripcion: Metodo el cual almacena los datos enviados al momento de crear una formula, y se valida
               si es transcripcion o formulacion y despues se creara la evolucion que se asociara a la
			   formula
*/
function insertar_evolucion($datos_evolucion, $es_transcripcion = false) {

    global $conexion;

    $sql = "INSERT INTO hc_evoluciones 
            (   ingreso, 
                fecha, 
                usuario_id, 
                departamento, 
                estado, 
                hc_modulo, 
                sw_edicion, 
                fecha_cierre, 
                numerodecuenta, 
                historia_clinica_tipo_cierre_id, 
                observacion_cierre, 
                diag_principal
             ) VALUES ( 
                '{$datos_evolucion['ingreso']}',
                '{$datos_evolucion['fecha']}',
                '{$datos_evolucion['usuario_id']}',
                '{$datos_evolucion['departamento']}',
                '{$datos_evolucion['estado']}',
                '{$datos_evolucion['hc_modulo']}',
                '{$datos_evolucion['sw_edicion']}',
                '{$datos_evolucion['fecha_cierre']}',
                '{$datos_evolucion['numerodecuenta']}',
                '{$datos_evolucion['historia_clinica_tipo_cierre_id']}',
                '{$datos_evolucion['observacion_cierre']}',
                '{$datos_evolucion['diag_principal']}'
             ) RETURNING evolucion_id; ";

    // Si los datos que se van a insertar son de transcripcion medica                    
    if ($es_transcripcion) {

        $sql = "INSERT INTO hc_evoluciones 
            (   ingreso, 
                fecha, 
                usuario_id, 
                departamento, 
                estado, 
                hc_modulo, 
                sw_edicion, 
                fecha_cierre, 
                numerodecuenta, 
                historia_clinica_tipo_cierre_id, 
                observacion_cierre, 
                diag_principal
             ) VALUES ( 
                NULL,
                '{$datos_evolucion['fecha']}',
                '{$datos_evolucion['usuario_id']}',
                '{$datos_evolucion['departamento']}',
                '{$datos_evolucion['estado']}',
                NULL,
                '{$datos_evolucion['sw_edicion']}',
                NULL,
                NULL,
                NULL,
                NULL,
                NULL
             ) RETURNING evolucion_id; ";
    }
    registrar_logs('001','000', $sql,'1');
    $result = pg_query($conexion, $sql);

    $msj = "";
    $evolucion_id = null;

    if ($result) {

        $arr = pg_fetch_object($result);
        $msj = "Datos Ingresados Correctamente en hc_evoluciones";
        $evolucion_id = $arr->evolucion_id;
    } else {
        $msj = "Se ha genereado un error insertando en hc_evoluciones (" . pg_last_error($conexion) . " )";
    }

    return array("msj" => $msj, "evolucion_id" => $evolucion_id);
}

/**
*+Descripcion: Metodo encargado de almacenar en la tabla hc_medicamentos_recetados_amb los medicamentos ? insumos asociados 
               a una formula validando el tipo de formula:
			   Formulaci?n_medicamentos=0
               Formulaci?n_insumos=2
               Transcripci?n_medicamentos=1
               Transcripci?n_insumos=3
**/
function insertar_medicamentos_recetados($evolucion_id, $medicamentos_recetados, $es_transcripcion = false,$tipo_formula) {

    global $conexion;

    $sql = " "; 

    $transcripcion_medica = "0";
    if ($es_transcripcion) {
        $transcripcion_medica = "1";
    }

    foreach ($medicamentos_recetados as $key => $value) {

        $via_administracion_id = (is_null($value['via_administracion_id']) || $value['via_administracion_id'] == "NULL") ? 'NULL' : "'{$value['via_administracion_id']}'";
        $unidad_dosificacion = (is_null($value['unidad_dosificacion']) || $value['unidad_dosificacion'] == "NULL" ) ? "''" : "'{$value['unidad_dosificacion']}'";

        $sql .= "INSERT INTO hc_medicamentos_recetados_amb
                (   codigo_producto,
    			    evolucion_id,
                    cantidad,
                    observacion,
                    sw_paciente_no_pos,
                    via_administracion_id,
                    dosis,
                    unidad_dosificacion,
                    tipo_opcion_posologia_id,
                    cantidadperiocidad,
                    justificacion_reformula,
                    numero_formula,
                    dias_tratamiento,
                    bloqueo,
                    usuario_id,
                    usuario_id_modifica,
                    cantidad_dia,
                    transcripcion_medica
                ) VALUES (
                    '{$value['codigo_producto']}', 
					 {$evolucion_id}, 
                    {$value['cantidad']}, 
                    '{$value['observacion']}', 
                    '{$value['sw_paciente_no_pos']}', 
                    {$via_administracion_id}, 
                    {$value['dosis']}, 
                    {$unidad_dosificacion}, 
                    {$value['tipo_opcion_posologia_id']}, 
                    {$value['cantidadperiocidad']}, 
                    '{$value['justificacion_reformula']}', 
                    {$value['numero_formula']}, 
                    {$value['dias_tratamiento']}, 
                    '{$value['bloqueo']}', 
                    {$value['usuario_id']}, 
                    {$value['usuario_id_modifica']}, 
                    {$value['cantidad_dia']},
                    '{$tipo_formula}'
                );";
    }
    registrar_logs($evolucion_id,$evolucion_id.'777', $sql,'1');
    $result = pg_query($conexion, $sql);

    if ($result) {
        $continuar = true;
        $msj = "Datos Ingresados Correctamente en hc_medicamentos_recetados";
    } else {
        $continuar = false;
        $msj = "Se ha generado un error insertando en hc_medicamentos_recetados_amb ( " . pg_last_error($conexion) . " ) ";
    }

    return array("msj" => $msj, "continuar" => $continuar);
}

function insertar_formulacion_antecedentes($evolucion_id, $formulacion_antecedentes, $es_transcripcion = false,$tipo_formula) {

    global $conexion;

    $sql = " ";
    $transcripcion_medica = "0";
    if ($es_transcripcion) {
        $transcripcion_medica = "1";
    }

    foreach ($formulacion_antecedentes as $key => $value) {
        
        $sw_permanente = (is_null($value['sw_permanente']) || $value['sw_permanente'] == "NULL") ? "''" : "'{$value['sw_permanente']}'";
        $sw_formulado = (is_null($value['sw_formulado']) || $value['sw_formulado'] == "NULL") ? "''" : "'{$value['sw_formulado']}'";
        
        $sql .= "   INSERT INTO hc_formulacion_antecedentes
                    (
                        evolucion_id,
                        numero_formula,
                        tipo_id_paciente,
                        paciente_id,
                        codigo_medicamento,
                        fecha_registro,
                        fecha_finalizacion,
                        medico_id,
                        dosis,
                        unidad_dosificacion,
                        frecuencia,
                        sw_permanente,
                        sw_formulado,
                        tiempo_total,
                        perioricidad_entrega,
                        descripcion,
                        fecha_formulacion,
                        medico_id_update,
                        fecha_reformulacion,
                        tiempo_perioricidad_entrega,
                        unidad_perioricidad_entrega,
                        cantidad,
                        cantidad_entrega,
                        fecha_modificacion,
                        sw_mostrar,
                        justificacion_reformula,
                        sw_anulado,
                        sw_autorizado,
                        usuario_autoriza_id,
                        observacion_autorizacion,
                        fecha_registro_autorizacion,
                        sw_estado,
                        transcripcion_medica
                    ) VALUES (
                        {$evolucion_id},
                        '{$value['numero_formula']}',
                        '{$value['tipo_id_paciente']}',
                        '{$value['paciente_id']}',
                        '{$value['codigo_medicamento']}',
                        '{$value['fecha_registro']}',
                        '{$value['fecha_finalizacion']}',
                        {$value['medico_id']},
                        {$value['dosis']},
                        '{$value['unidad_dosificacion']}',
                        '{$value['frecuencia']}',
                        {$sw_permanente},
                        {$sw_formulado},
                        '{$value['tiempo_total']}',
                        '{$value['perioricidad_entrega']}',
                        '{$value['descripcion']}',
                        '{$value['fecha_formulacion']}',
                        NULL,
                        NULL,
                        {$value['tiempo_perioricidad_entrega']},
                        '{$value['unidad_perioricidad_entrega']}',
                        NULL,
                        {$value['cantidad']},
                        NULL,
                        '{$value['sw_mostrar']}',
                        '{$value['justificacion_reformula']}',
                        '{$value['sw_anulado']}',
                        '{$value['sw_autorizado']}',
                        NULL,
                        '{$value['observacion_autorizacion']}',
                        NULL,
                        '{$value['sw_estado']}',
                        '{$tipo_formula}'    
                    ); ";
    }

    registrar_logs($evolucion_id,$evolucion_id.'000', $sql,'1');
    $result = pg_query($conexion, $sql);

    if ($result) {
        $continuar = true;
        $msj = "Datos Ingresados Correctamente en hc_formulacion_antecedentes";
    } else {
        $continuar = false;
        $msj = "Se ha generado un error insertando en hc_formulacion_antecedentes ( " . pg_last_error($conexion) . " ) ";
    }

    return array("msj" => $msj, "continuar" => $continuar);
}

function modificar_medicamentos_recetados($evolucion_id, $medicamentos_recetados, $es_transcripcion = false) {

    global $conexion;

    $sql = " ";

    $transcripcion_medica = "0";
    if ($es_transcripcion) {
        $transcripcion_medica = "1";
    }

    foreach ($medicamentos_recetados as $key => $value) {

        $sql .="UPDATE hc_medicamentos_recetados_amb SET                                     
                cantidad = {$value['cantidad']},
                observacion = '{$value['observacion']}',
                sw_paciente_no_pos = '{$value['sw_paciente_no_pos']}',
                via_administracion_id = '{$value['via_administracion_id']}',
                dosis = {$value['dosis']},
                unidad_dosificacion = '{$value['unidad_dosificacion']}',
                tipo_opcion_posologia_id = {$value['tipo_opcion_posologia_id']},
                cantidadperiocidad = {$value['cantidadperiocidad']},
                justificacion_reformula = '{$value['justificacion_reformula']}',
                dias_tratamiento = {$value['dias_tratamiento']},
                bloqueo = '{$value['bloqueo']}',
                usuario_id = {$value['usuario_id']},
                usuario_id_modifica = {$value['usuario_id_modifica']},
                cantidad_dia = {$value['cantidad_dia']},
                transcripcion_medica = '{$transcripcion_medica}' WHERE evolucion_id = {$evolucion_id} and codigo_producto = '{$value['codigo_producto']}' ; ";
    }
    registrar_logs($evolucion_id,$evolucion_id.'000', $sql,'1');
    $result = pg_query($conexion, $sql);

    if ($result) {
        $continuar = true;
        $msj = "Datos Modificados Correctamente en hc_medicamentos_recetados_amb";
    } else {
        $continuar = false;
        $msj = "Se ha generado un error modificando en hc_medicamentos_recetados_amb ( " . pg_last_error($conexion) . " ) ";
    }

    return array("msj" => $msj, "continuar" => $continuar);
}

function modificar_formulacion_antecedentes($evolucion_id, $formulacion_antecedentes, $es_transcripcion = false) {

    global $conexion;

    $sql = " ";
    $transcripcion_medica = "0";
    if ($es_transcripcion) {
        $transcripcion_medica = "1";
    }

    foreach ($formulacion_antecedentes as $key => $value) {

        $sql .="UPDATE hc_formulacion_antecedentes SET                                                                
                fecha_registro = '{$value['fecha_registro']}',
                fecha_finalizacion = '{$value['fecha_finalizacion']}',
                medico_id = {$value['medico_id']},
                dosis = {$value['dosis']},
                unidad_dosificacion = '{$value['unidad_dosificacion']}',
                frecuencia = '{$value['frecuencia']}',
                sw_permanente = '{$value['sw_permanente']}',
                sw_formulado = '{$value['sw_formulado']}',
                tiempo_total =  '{$value['tiempo_total']}',
                perioricidad_entrega = '{$value['perioricidad_entrega']}',
                descripcion = '{$value['descripcion']}',
                fecha_formulacion  = '{$value['fecha_formulacion']}',
                medico_id_update = NULL,
                fecha_reformulacion = NULL,
                tiempo_perioricidad_entrega = {$value['tiempo_perioricidad_entrega']},
                unidad_perioricidad_entrega = '{$value['unidad_perioricidad_entrega']}',
                cantidad = NULL,
                cantidad_entrega = {$value['cantidad']},
                fecha_modificacion = NULL,
                sw_mostrar = '{$value['sw_mostrar']}',
                justificacion_reformula = '{$value['justificacion_reformula']}',
                sw_anulado =  '{$value['sw_anulado']}',
                sw_autorizado = '{$value['sw_autorizado']}',
                usuario_autoriza_id = NULL,
                observacion_autorizacion = '{$value['observacion_autorizacion']}',
                fecha_registro_autorizacion = NULL,
                sw_estado = '{$value['sw_estado']}',
                transcripcion_medica = '{$transcripcion_medica}' WHERE  evolucion_id = {$evolucion_id}  and codigo_medicamento = '{$value['codigo_medicamento']}' ; ";
    }

    registrar_logs($evolucion_id,$evolucion_id.'000', $sql,'1');
    $result = pg_query($conexion, $sql);

    if ($result) {
        $continuar = true;
        $msj = "Datos Modificados Correctamente en hc_formulacion_antecedentes";
    } else {
        $continuar = false;
        $msj = "Se ha generado un error modificando en hc_formulacion_antecedentes ( " . pg_last_error($conexion) . " ) ";
    }

    return array("msj" => $msj, "continuar" => $continuar);
}

function eliminar_medicamento_recetado($evolucion_id, $codigo_producto) {

    global $conexion;

    $sql = "DELETE FROM hc_medicamentos_recetados_amb WHERE evolucion_id = {$evolucion_id} AND codigo_producto = '{$codigo_producto}' ;";
    registrar_logs($evolucion_id,$evolucion_id.'000', $sql,'1');
    $result = pg_query($conexion, $sql);

    if ($result) {
        $continuar = true;
        $msj = " Medicamento {$codigo_producto} Eliminado Correctamente del Evolucion No.{$evolucion_id} ";
    } else {
        $continuar = false;
        $msj = " Se ha Generado un Error al Eliminar el Medicamento de la Formula No.{$evolucion_id} en DUANA ( " . pg_last_error($conexion) . " )";
    }

    return array("msj" => $msj, "continuar" => $continuar);
}

function eliminar_formulacion_antecedente($evolucion_id, $codigo_producto) {

    global $conexion;

    $sql = "DELETE FROM hc_formulacion_antecedentes WHERE evolucion_id = {$evolucion_id} AND codigo_medicamento = '{$codigo_producto}' ;";
       registrar_logs($evolucion_id,$evolucion_id.'000', $sql,'1');
    $result = pg_query($conexion, $sql);

    if ($result) {
        $continuar = true;
        $msj = " Medicamento {$codigo_producto} Eliminado Correctamente del Evolucion No.{$evolucion_id} ";
    } else {
        $continuar = false;
        $msj = " Se ha Generado un Error al Eliminar el Medicamento de la Formula No.{$evolucion_id} en DUANA ( " . pg_last_error($conexion) . " )";
    }

    return array("msj" => $msj, "continuar" => $continuar);
}

function eliminar_formulacion($evolucion_id) {

    global $conexion;

    $sql = "DELETE FROM hc_formulacion_antecedentes_historia WHERE evolucion_id = {$evolucion_id} ;            
            DELETE FROM hc_medicamentos_recetados_amb WHERE evolucion_id = {$evolucion_id} ;            
            DELETE FROM hc_formulacion_antecedentes WHERE evolucion_id = {$evolucion_id} ; 
            DELETE FROM hc_evoluciones WHERE evolucion_id = {$evolucion_id} ; ";
         registrar_logs($evolucion_id,$evolucion_id.'000', $sql,'1');
    $result = pg_query($conexion, $sql);

    if ($result) {
        $msj = " Formula No. {$evolucion_id} Eliminada en DUANA ";
    } else {
        $msj = " Se ha Generado un Erro al Eliminar la Formula en DUANA ( " . pg_last_error($conexion) . " )";
    }

    return $msj;
}

function registrar_logs($evolucion_id, $numero_formula, $mensaje,$tipo='0') {

    global $conexion;

    $evolucion_id = (empty($evolucion_id)) ? 'NULL' : $evolucion_id;
    $numero_formula = (empty($numero_formula)) ? 'NULL' : $numero_formula;
     $mensaje=pg_escape_string($mensaje);
    $sql = " INSERT INTO logs_formulacion_ws (evolucion_id, numero_formula, mensaje,tipo)
             VALUES ({$evolucion_id}, {$numero_formula}, '{$mensaje}','{$tipo}' ); ";
    
    $result = pg_query($conexion, $sql);

    if ($result) {
        $continuar = true;
        $msj = "LOG Registrado";
    } else {
        $continuar = false;
        $msj = "Se ha generado un error insertando en logs_formulacion_ws ( " . pg_last_error($conexion) . " ) ";
    }

    return array("msj" => $msj, "continuar" => $continuar);
}

//INVOCA EL SERVICIO
if (isset($HTTP_RAW_POST_DATA)) {
    $input = $HTTP_RAW_POST_DATA;
} else {
    $input = implode("rn", file('php://input'));
}
$server->service($input);
?>