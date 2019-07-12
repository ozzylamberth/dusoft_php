<?php

/* * *************************************************************************************************
 *
 * Web Service ws_afiliaciones.php
 *
 * Fecha: 17-VI-2013
 * Autor: Steven H. Gamboa
 *
 * Descripción:
 *
 * ************************************************************************************************* */

require_once('../../nusoap/lib/nusoap.php');
// ----> CAMBIAR URL <----
$ns = "http://10.0.2.170/Pruebas_DUSOFT_DUANA_/ws/afiliaciones/ws_afiliaciones.php";
//$ns = "http://10.0.2.170/DUSOFT_DUANA/ws/afiliaciones/ws_afiliaciones.php";
$server = new soap_server();
$server->configureWSDL('AFILIACIONES', $ns);
$server->wsdl->schemaTargetNamespace = $ns;

$server->register('insertar_epsafiliadosdatos', array('primer_apellido' => 'xsd:string',
    'segundo_apellido' => 'xsd:string',
    'primer_nombre' => 'xsd:string',
    'segundo_nombre' => 'xsd:string',
    'fecha_nacimiento' => 'xsd:string',
    'fecha_afiliacion_sgss' => 'xsd:string',
    'tipo_sexo_id' => 'xsd:string',
    'ciuo_88_grupo_primario' => 'xsd:string',
    'tipo_pais_id' => 'xsd:string',
    'tipo_dpto_id' => 'xsd:string',
    'tipo_mpio_id' => 'xsd:string',
    'zona_residencia' => 'xsd:string',
    'direccion_residencia' => 'xsd:string',
    'telefono_residencia' => 'xsd:string',
    'telefono_movil' => 'xsd:string',
    'usuario_registro' => 'xsd:string',
    'usuario_ultima_actualizacion' => 'xsd:string',
    'afiliado_tipo_id' => 'xsd:string',
    'afiliado_id' => 'xsd:string'
        ), array('return' => 'xsd:string'), $ns);

$server->register('insertar_epsafiliaciones', array('eps_afiliacion_id' => 'xsd:string',
    'eps_tipo_afiliacion_id' => 'xsd:string',
    'fecha_recepcion' => 'xsd:string',
    'usuario_id' => 'xsd:string'
        ), array('return' => 'xsd:string'), $ns);

$server->register('insertar_epsafiliados', array('eps_afiliacion_id' => 'xsd:string',
    'afiliado_tipo_id' => 'xsd:string',
    'afiliado_id' => 'xsd:string',
    'eps_tipo_afiliado_id' => 'xsd:string',
    'fecha_afiliacion' => 'xsd:string',
    'eps_anterior' => 'xsd:string',
    'fecha_afiliacion_eps_anterior' => 'xsd:string',
    'semanas_cotizadas_eps_anterior' => 'xsd:string',
    'plan_atencion' => 'xsd:string',
    'tipo_afiliado_atencion' => 'xsd:string',
    'rango_afiliado_atencion' => 'xsd:string',
    'eps_punto_atencion_id' => 'xsd:string',
    'fecha_vencimiento' => 'xsd:string',
    'observaciones' => 'xsd:string',
    'usuario_id' => 'xsd:string'
        ), array('return' => 'xsd:string'), $ns);

$server->register('insertar_epsafiliadoscotizantes', array('eps_afiliacion_id' => 'xsd:string',
    'afiliado_tipo_id' => 'xsd:string',
    'afiliado_id' => 'xsd:string',
    'ciiu_r3_division' => 'xsd:string',
    'ciiu_r3_grupo' => 'xsd:string',
    'ciiu_r3_clase' => 'xsd:string',
    'telefono_dependencia' => 'xsd:string',
    'estrato_socioeconomico_id' => 'xsd:string',
    'tipo_estado_civil_id' => 'xsd:string',
    'tipo_aportante_id' => 'xsd:string',
    'estamento_id' => 'xsd:string',
    'codigo_afp' => 'xsd:string',
    'ingreso_mensual' => 'xsd:string',
    'fecha_ingreso_laboral' => 'xsd:string',
    'codigo_dependencia_id' => 'xsd:string',
    'usuario_id' => 'xsd:string',
    'sirh_per_codigo' => 'xsd:string',
    'ter_codigo' => 'xsd:string',
    'parentesco_id' => 'xsd:string'
        ), array('return' => 'xsd:string'), $ns);

$server->register('insertar_epsafiliadoscotizantesconvenios', array('eps_afiliacion_id' => 'xsd:string',
    'afiliado_tipo_id' => 'xsd:string',
    'afiliado_id' => 'xsd:string',
    'convenio_tipo_id_tercero' => 'xsd:string',
    'convenio_tercero_id' => 'xsd:string',
    'fecha_inicio_convenio' => 'xsd:string',
    'fecha_vencimiento_convenio' => 'xsd:string',
    'usuario_id' => 'xsd:string'
        ), array('return' => 'xsd:string'), $ns);

$server->register('consultar_paciente', array('tipo_id_paciente' => 'xsd:string',
    'paciente_id' => 'xsd:string'
        ), array('return' => 'xsd:string'), $ns);

$server->register('consultar_eps_afiliados', array('tipo_id_paciente' => 'xsd:string',
    'paciente_id' => 'xsd:string',
    'eps_afiliacion_id' => 'xsd:string'
        ), array('return' => 'xsd:string'), $ns);

$server->register('consultar_eps_afiliaciones', array('eps_afiliacion_id' => 'xsd:string'
        ), array('return' => 'xsd:string'), $ns);

$server->register('consultar_eps_afiliados_beneficiarios', array('tipo_id_paciente' => 'xsd:string',
    'paciente_id' => 'xsd:string'
        ), array('return' => 'xsd:string'), $ns);

$server->register('consultar_eps_afiliados_cotizantes', array('tipo_id_paciente' => 'xsd:string',
    'paciente_id' => 'xsd:string'
        ), array('return' => 'xsd:string'), $ns);


require_once("conexionpg.php");

function insertar_epsafiliadosdatos($primer_apellido, $segundo_apellido, $primer_nombre, $segundo_nombre, $fecha_nacimiento, $fecha_afiliacion_sgss, $tipo_sexo_id, $ciuo_88_grupo_primario, $tipo_pais_id, $tipo_dpto_id, $tipo_mpio_id, $zona_residencia, $direccion_residencia, $telefono_residencia, $telefono_movil, $usuario_registro, $usuario_ultima_actualizacion, $afiliado_tipo_id, $afiliado_id) {
    
    //require_once("conexionpg.php");
    global $conexionn;

    $query = "INSERT INTO eps_afiliados_datos ( primer_apellido, 
                segundo_apellido, 
                primer_nombre, 
                segundo_nombre, 
                fecha_nacimiento,
                fecha_afiliacion_sgss,
                tipo_sexo_id,
                ciuo_88_grupo_primario,
                tipo_pais_id,
                tipo_dpto_id,
                tipo_mpio_id,
                zona_residencia,
                direccion_residencia,
                telefono_residencia,
                telefono_movil,
                usuario_registro,
                fecha_registro,
                usuario_ultima_actualizacion,
                fecha_ultima_actualizacion,
                afiliado_tipo_id,
                afiliado_id)
              VALUES ('" . $primer_apellido . "', 
               		  '" . $segundo_apellido . "', 
               		  '" . $primer_nombre . "',
               		  '" . $segundo_nombre . "',
               		  '" . $fecha_nacimiento . "',
               		  " . $fecha_afiliacion_sgss . ",
               		  '" . $tipo_sexo_id . "',
               		  '" . $ciuo_88_grupo_primario . "',
               		  '" . $tipo_pais_id . "',
               		  '" . $tipo_dpto_id . "',
               		  '" . $tipo_mpio_id . "',
               		  '" . $zona_residencia . "',
               		  '" . $direccion_residencia . "',
               		  '" . $telefono_residencia . "',
               		  '" . $telefono_movil . "',
               		  '" . $usuario_registro . "',
               		  now(),
               		  '" . $usuario_ultima_actualizacion . "',
               		  now(),
               		  '" . $afiliado_tipo_id . "',
               		  '" . $afiliado_id . "'); ";

    $result = pg_query($conexionn, $query);

    if ($result) {
        registrar_log($query, '', '0');
        return true;
    } else {
        registrar_log(pg_escape_string($query), pg_escape_string(pg_last_error($conexionn)) , '1');
        return "Error en el insert de los datos: " . $query;
    }
}

function insertar_epsafiliaciones($eps_afiliacion_id, $eps_tipo_afiliacion_id, $fecha_recepcion, $usuario_id) {
    
    //require_once("conexionpg.php");
    global $conexionn;

    $query = "INSERT INTO eps_afiliaciones ( eps_afiliacion_id,
											eps_tipo_afiliacion_id,
											fecha_recepcion,
											usuario_registro,
											fecha_registro,
											usuario_ultima_actualizacion,
											fecha_ultima_actualizacion)
					VALUES (" . $eps_afiliacion_id . ",
							'" . $eps_tipo_afiliacion_id . "',
							'" . $fecha_recepcion . "',
							" . $usuario_id . ",
							now(),
							" . $usuario_id . ",
							now() ); ";

    $result = pg_query($conexionn, $query);

    if ($result) {
        registrar_log($query, '', '0');
        return true;
    } else {
        registrar_log(pg_escape_string($query), pg_escape_string(pg_last_error($conexionn)) , '1');
        return "Error en el insert de los datos: " . $query;
    }
}

function insertar_epsafiliados($eps_afiliacion_id, $afiliado_tipo_id, $afiliado_id, $eps_tipo_afiliado_id, $fecha_afiliacion, $eps_anterior, $fecha_afiliacion_eps_anterior, $semanas_cotizadas_eps_anterior, $plan_atencion, $tipo_afiliado_atencion, $rango_afiliado_atencion, $eps_punto_atencion_id, $fecha_vencimiento, $observaciones, $usuario_id) {
    
    //require_once("conexionpg.php");
    global $conexionn;

    $query = "INSERT INTO eps_afiliados ( eps_afiliacion_id, 
                            				afiliado_tipo_id  , 
                           				 	afiliado_id , 
                            				eps_tipo_afiliado_id  , 
				                            fecha_afiliacion  , 
                				            eps_anterior  , 
				                            fecha_afiliacion_eps_anterior     , 
                				            semanas_cotizadas_eps_anterior    , 
				                            plan_atencion, 
                				            tipo_afiliado_atencion, 
				                            rango_afiliado_atencion, 
                				            eps_punto_atencion_id, 
				                            fecha_vencimiento , 
                				            observaciones     , 
				                            usuario_registro  , 
                				            fecha_registro    , 
				                            usuario_ultima_actualizacion  , 
                				            fecha_ultima_actualizacion) 
                	VALUES (" . $eps_afiliacion_id . ",
                			'" . $afiliado_tipo_id . "',
                			'" . $afiliado_id . "',
                			'" . $eps_tipo_afiliado_id . "',
                			'" . $fecha_afiliacion . "',
                			" . $eps_anterior . ",
                			" . $fecha_afiliacion_eps_anterior . ",
                			" . $semanas_cotizadas_eps_anterior . ",
                			" . $plan_atencion . ",
                			'" . $tipo_afiliado_atencion . "',
                			'" . $rango_afiliado_atencion . "',
                			'" . $eps_punto_atencion_id . "',
                			" . $fecha_vencimiento . ",
                			'" . $observaciones . "',
                			" . $usuario_id . ",
                			now(),
                			" . $usuario_id . ",
                			now() ); ";

    $result = pg_query($conexionn, $query);

    if ($result) {
        registrar_log($query, '', '0');
        return true;
    } else {
        registrar_log(pg_escape_string($query), pg_escape_string(pg_last_error($conexionn)) , '1');
        return "Error en el insert de los datos: " . $query;
    }
}

function insertar_epsafiliadoscotizantes($eps_afiliacion_id, $afiliado_tipo_id, $afiliado_id, $ciiu_r3_division, $ciiu_r3_grupo, $ciiu_r3_clase, $telefono_dependencia, $estrato_socioeconomico_id, $tipo_estado_civil_id, $tipo_aportante_id, $estamento_id, $codigo_afp, $ingreso_mensual, $fecha_ingreso_laboral, $codigo_dependencia_id, $usuario_id, $sirh_per_codigo, $ter_codigo, $parentesco_id) {
    
    //require_once("conexionpg.php");
    global $conexionn;

    $query = "INSERT INTO eps_afiliados_cotizantes (  	eps_afiliacion_id,
                                                    	afiliado_tipo_id,
                                                        afiliado_id,
                                                        ciiu_r3_division,
                                                        ciiu_r3_grupo,
                                                        ciiu_r3_clase,
                                                        telefono_dependencia,
                                                        estrato_socioeconomico_id,
                                                        tipo_estado_civil_id,
                                                        tipo_aportante_id,
                                                        estamento_id,
                                                        codigo_afp,
                                                        ingreso_mensual,
                                                        fecha_ingreso_laboral,
                                                        codigo_dependencia_id,
                                                        usuario_registro,
                                                        fecha_registro,
                                                        usuario_ultima_actualizacion,
                                                        fecha_ultima_actualizacion, 
                                                        sirh_per_codigo, 
                                                        ter_codigo , 
                                                        parentesco_id ) 
        			VALUES (" . $eps_afiliacion_id . ",
        					'" . $afiliado_tipo_id . "',
        					'" . $afiliado_id . "',
        					" . $ciiu_r3_division . ",
        					" . $ciiu_r3_grupo . ",
        					" . $ciiu_r3_clase . ",
        					'" . $telefono_dependencia . "',
        					" . $estrato_socioeconomico_id . ",
        					'" . $tipo_estado_civil_id . "',
        					'" . $tipo_aportante_id . "',
        					'" . $estamento_id . "',
        					" . $codigo_afp . ",
        					" . $ingreso_mensual . ",
        					" . $fecha_ingreso_laboral . ",
        					'" . $codigo_dependencia_id . "',
        					" . $usuario_id . ",
        					now(),
        					" . $usuario_id . ",
        					now(),
        					" . $sirh_per_codigo . ",
        					" . $ter_codigo . ",
        					" . $parentesco_id . "
        					)";

    $result = pg_query($conexionn, $query);

    if ($result) {
        registrar_log($query, '', '0');
        return true;
    } else {
        registrar_log(pg_escape_string($query), pg_escape_string(pg_last_error($conexionn)) , '1');
        return "Error en el insert de los datos: " . $query;
    }
}

function insertar_epsafiliadoscotizantesconvenios($eps_afiliacion_id, $afiliado_tipo_id, $afiliado_id, $convenio_tipo_id_tercero, $convenio_tercero_id, $fecha_inicio_convenio, $fecha_vencimiento_convenio, $usuario_id) {
    
    //require_once("conexionpg.php");
    global $conexionn;

    $query = "INSERT INTO eps_afiliados_cotizantes_convenios (eps_afiliacion_id,
															  afiliado_tipo_id,
															  afiliado_id,
															  convenio_tipo_id_tercero,
															  convenio_tercero_id,
															  fecha_inicio_convenio,
															  fecha_vencimiento_convenio,
															  usuario_registro,
															  fecha_registro,
															  usuario_ultima_actualizacion,
															  fecha_ultima_actualizacion)
					VALUES (" . $eps_afiliacion_id . ",
							'" . $afiliado_tipo_id . "',
							'" . $afiliado_id . "',
							'" . $convenio_tipo_id_tercero . "',
							'" . $convenio_tercero_id . "',
							'" . $fecha_inicio_convenio . "',
							'" . $fecha_vencimiento_convenio . "',
							" . $usuario_id . ",
							now(),
							" . $usuario_id . ",
							now() ); ";

    $result = pg_query($conexionn, $query);

    if ($result) {
        registrar_log($query, '', '0');
        return true;
    } else {
        registrar_log(pg_escape_string($query), pg_escape_string(pg_last_error($conexionn)) , '1');
        return "Error en el insert de los datos: " . $query;
    }
}

function consultar_paciente($tipo_id_paciente, $paciente_id) {

    //require_once("conexionpg.php");
    global $conexionn;

    $sql = " select * from pacientes a where a.tipo_id_paciente = '{$tipo_id_paciente}' and a.paciente_id= '{$paciente_id}' ";

    $result = pg_query($conexionn, $sql);

    if ($result) {

        $arr = pg_num_rows($result);
        registrar_log($sql, '', '0');
        return "{$arr}";
    } else {
        registrar_log(pg_escape_string($sql), pg_escape_string(pg_last_error($conexionn)) , '1');
        return "0";
    }
}

function consultar_eps_afiliados($tipo_id_paciente, $paciente_id, $eps_afiliacion_id) {

    //require_once("conexionpg.php");
    global $conexionn;

    $sql = " select * 
             from eps_afiliados a 
             where 
             a.afiliado_tipo_id = '{$tipo_id_paciente}' and 
             a.afiliado_id= '{$paciente_id}' and
             a.eps_afiliacion_id='{$eps_afiliacion_id}' ;";



    $result = pg_query($conexionn, $sql);

    if ($result) {

        $arr = pg_num_rows($result);
        
        registrar_log($sql, '', '0');
     
        return "{$arr}";
    } else {
        
        registrar_log(pg_escape_string($sql), pg_escape_string(pg_last_error($conexionn)) , '1');
        
        return "0";
    }
}

function consultar_eps_afiliaciones($eps_afiliacion_id) {

    //require_once("conexionpg.php");
    global $conexionn;

    $sql = " select * from eps_afiliaciones a where a.eps_afiliacion_id = '{$eps_afiliacion_id}' ";

    $result = pg_query($conexionn, $sql);

    if ($result) {

        $arr = pg_num_rows($result);
        
        registrar_log($sql, '', '0');
        
        return "{$arr}";
    } else {
        registrar_log(pg_escape_string($sql), pg_escape_string(pg_last_error($conexionn)) , '1');
        
        return "0";
    }
}

function consultar_eps_afiliados_beneficiarios($tipo_id_paciente, $paciente_id) {

    //require_once("conexionpg.php");
    global $conexionn;

    $sql = " select * from eps_afiliados_beneficiarios a where a.afiliado_tipo_id = '{$tipo_id_paciente}' and a.afiliado_id= '{$paciente_id}' ";

    $result = pg_query($conexionn, $sql);

    if ($result) {

        $arr = pg_num_rows($result);

        registrar_log($sql, '', '0');
        
        return "{$arr}";
    } else {
        registrar_log(pg_escape_string($sql), pg_escape_string(pg_last_error($conexionn)) , '1');
        
        return "0";
    }
}

function consultar_eps_afiliados_cotizantes($tipo_id_paciente, $paciente_id) {

    //require_once("conexionpg.php");
    global $conexionn;

    $sql = " select * from eps_afiliados_cotizantes a where a.afiliado_tipo_id = '{$tipo_id_paciente}' and a.afiliado_id= '{$paciente_id}' ";



    $result = pg_query($conexionn, $sql);



    if ($result) {

        $arr = pg_num_rows($result);
        
        registrar_log($sql, '', '0');
        
        return "{$arr}";
    } else {
        
        registrar_log(pg_escape_string($sql), pg_escape_string(pg_last_error($conexionn)) , '1');
        
        return "0";
    }
}

function registrar_log($query, $resultado, $error) {

    global $conexionn;

    $sql = "insert into logs_pacientes_ws (query, resultado, sw_error) values ('{$query}', '{$resultado}', '{$error}' ) ;";
 
    $result = pg_query($conexionn, $sql);
     
    if ($result) {
        return true;
    } else {
        return "Error en el insert de los datos: -" . pg_last_error();
    }
}

;

if (isset($HTTP_RAW_POST_DATA)) {
    $input = $HTTP_RAW_POST_DATA;
} else {
    $input = implode("rn", file('php://input'));
}
$server->service($input);
?>