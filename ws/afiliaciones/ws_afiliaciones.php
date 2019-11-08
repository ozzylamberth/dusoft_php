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
//$ns = "http://10.0.2.170/Pruebas_DUSOFT_DUANA_/ws/afiliaciones/ws_afiliaciones.php";
$ns = "http://10.0.2.237/APP/DUSOFT_DUANA/ws/afiliaciones/ws_afiliaciones.php";
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

$server->register('update_epsafiliadosdatos', array('primer_apellido' => 'xsd:string',
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
    'afiliado_id' => 'xsd:string',
    'afiliado_tipo_id_anterior' => 'xsd:string',
    'afiliado_id_anterior' => 'xsd:string'
        ), array('return' => 'xsd:string'), $ns);

$server->register('insertar_pacientes', array('primer_apellido' => 'xsd:string',
    'segundo_apellido' => 'xsd:string',
    'primer_nombre' => 'xsd:string',
    'segundo_nombre' => 'xsd:string',
    'fecha_nacimiento' => 'xsd:string',
    'tipo_sexo_id' => 'xsd:string',
    'tipo_pais_id' => 'xsd:string',
    'tipo_dpto_id' => 'xsd:string',
    'tipo_mpio_id' => 'xsd:string',
    'zona_residencia' => 'xsd:string',
    'direccion_residencia' => 'xsd:string',
    'telefono_residencia' => 'xsd:string',
    'telefono_movil' => 'xsd:string',
    'afiliado_tipo_id' => 'xsd:string',
    'afiliado_id' => 'xsd:string'
        ), array('return' => 'xsd:string'), $ns);

$server->register('insertar_eps_afiliados_beneficiarios', array('eps_afiliacion_id' => 'xsd:string',
    'afiliado_tipo_id' => 'xsd:string',
    'afiliado_id' => 'xsd:string',
    'cotizante_tipo_id' => 'xsd:string',
    'cotizante_id' => 'xsd:string',
    'parentesco_id' => 'xsd:string',
    'observaciones' => 'xsd:string'
        ), array('return' => 'xsd:string'), $ns);


$server->register('update_eps_afiliados_beneficiarios', array('eps_afiliacion_id' => 'xsd:string',
    'afiliado_tipo_id' => 'xsd:string',
    'afiliado_id' => 'xsd:string',
    'cotizante_tipo_id' => 'xsd:string',
    'cotizante_id' => 'xsd:string',
    'parentesco_id' => 'xsd:string',
    'observaciones' => 'xsd:string',
    'accion_ultima_actualizacion' => 'xsd:string',
    'eps_afiliacion_id_anterior' => 'xsd:string',
    'afiliado_tipo_id_anterior' => 'xsd:string',
    'afiliado_id_anterior' => 'xsd:string'
        ), array('return' => 'xsd:string'), $ns);

$server->register('update_pacientes', array('primer_apellido' => 'xsd:string',
    'segundo_apellido' => 'xsd:string',
    'primer_nombre' => 'xsd:string',
    'segundo_nombre' => 'xsd:string',
    'fecha_nacimiento' => 'xsd:string',
    'tipo_sexo_id' => 'xsd:string',
    'tipo_pais_id' => 'xsd:string',
    'tipo_dpto_id' => 'xsd:string',
    'tipo_mpio_id' => 'xsd:string',
    'zona_residencia' => 'xsd:string',
    'direccion_residencia' => 'xsd:string',
    'telefono_residencia' => 'xsd:string',
    'telefono_movil' => 'xsd:string',
    'afiliado_tipo_id' => 'xsd:string',
    'afiliado_id' => 'xsd:string',
    'afiliado_id_anterior' => 'xsd:string',
    'afiliado_tipo_id_anterior' => 'xsd:string'
        ), array('return' => 'xsd:string'), $ns);

$server->register('insertar_epsafiliaciones', array('eps_afiliacion_id' => 'xsd:string',
    'eps_tipo_afiliacion_id' => 'xsd:string',
    'fecha_recepcion' => 'xsd:string',
    'usuario_id' => 'xsd:string'
        ), array('return' => 'xsd:string'), $ns);

$server->register('update_epsafiliaciones', array('eps_afiliacion_id' => 'xsd:string',
    'eps_tipo_afiliacion_id' => 'xsd:string',
    'fecha_recepcion' => 'xsd:string',
    'usuario_id' => 'xsd:string',
    'eps_afiliacion_id_anterior' => 'xsd:string'
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
    'subestado_afiliado_id' => 'xsd:string',
    'estado_afiliado_id' => 'xsd:string',
    'usuario_id' => 'xsd:string'
        ), array('return' => 'xsd:string'), $ns);

$server->register('update_epsafiliados', array('eps_afiliacion_id' => 'xsd:string',
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
    'usuario_id' => 'xsd:string',
    'eps_afiliacion_id_anterior' => 'xsd:string',
    'subestado_afiliado_id' => 'xsd:string',
    'estado_afiliado_id' => 'xsd:string',
    'afiliado_tipo_id_anterior' => 'xsd:string',
    'afiliado_id_anterior' => 'xsd:string',
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

$server->register('update_epsafiliadoscotizantes', array('eps_afiliacion_id' => 'xsd:string',
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
    'parentesco_id' => 'xsd:string',
    'eps_afiliacion_id_anterior' => 'xsd:string',
    'afiliado_tipo_id_anterior' => 'xsd:string',
    'afiliado_id_anterior' => 'xsd:string',
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

$server->register('update_epsafiliadoscotizantesconvenios', array('eps_afiliacion_id' => 'xsd:string',
    'afiliado_tipo_id' => 'xsd:string',
    'afiliado_id' => 'xsd:string',
    'convenio_tipo_id_tercero' => 'xsd:string',
    'convenio_tercero_id' => 'xsd:string',
    'fecha_inicio_convenio' => 'xsd:string',
    'fecha_vencimiento_convenio' => 'xsd:string',
    'usuario_id' => 'xsd:string',
    'eps_afiliacion_id_anterior' => 'xsd:string',
    'afiliado_tipo_id_anterior' => 'xsd:string',
    'afiliado_id_anterior' => 'xsd:string',
        ), array('return' => 'xsd:string'), $ns);

$server->register('consultar_paciente', array('tipo_id_paciente' => 'xsd:string',
    'paciente_id' => 'xsd:string'
        ), array('return' => 'xsd:string'), $ns);

$server->register('consultar_eps_afiliados_datos', array('tipo_id_paciente' => 'xsd:string',
    'paciente_id' => 'xsd:string'
        ), array('return' => 'tns:WS_resultado_eps_afiliados_datos'), "urn:afiliaciones_ws", "urn:afiliaciones_ws#consultar", "rpc", "encoded", "Webservice: Permite consultar si existe un paciente en afiliacion eps");

$server->wsdl->addComplexType('WS_resultado_eps_afiliados_datos', 'complexType', 'struct', 'all', '', array('msj' => array('name' => 'msj', 'type' => 'xsd:string'),
    'continuar' => array('name' => 'continuar', 'type' => 'xsd:boolean'),
    'estado' => array('name' => 'tipoEstado', 'type' => 'xsd:string')));


$server->register('consultar_eps_afiliados', array('tipo_id_paciente' => 'xsd:string',
    'paciente_id' => 'xsd:string', 'eps_afiliacion_id' => 'xsd:string'
        ), array('return' => 'xsd:string'), $ns);

$server->register('consultar_eps_afiliaciones', array('eps_afiliacion_id' => 'xsd:string'
        ), array('return' => 'xsd:string'), $ns);

$server->register('consultar_eps_afiliados_activo', array('tipo_id_paciente' => 'xsd:string',
    'paciente_id' => 'xsd:string', 'eps_afiliacion_id' => 'xsd:string'
        ), array('return' => 'xsd:string'), $ns);

$server->register('consultar_eps_afiliados_beneficiarios', array('tipo_id_paciente' => 'xsd:string',
    'paciente_id' => 'xsd:string', 'eps_afiliacion_id' => 'xsd:string'
        ), array('return' => 'xsd:string'), $ns);

$server->register('consultar_eps_afiliados_cotizantes', array('tipo_id_paciente' => 'xsd:string',
    'paciente_id' => 'xsd:string', 'eps_afiliacion_id' => 'xsd:string'
        ), array('return' => 'xsd:string'), $ns);


require_once("conexionpg.php");

function insertar_epsafiliadosdatos($primer_apellido, $segundo_apellido, $primer_nombre, $segundo_nombre, $fecha_nacimiento, $fecha_afiliacion_sgss, $tipo_sexo_id, $ciuo_88_grupo_primario, $tipo_pais_id, $tipo_dpto_id, $tipo_mpio_id, $zona_residencia, $direccion_residencia, $telefono_residencia, $telefono_movil, $usuario_registro, $usuario_ultima_actualizacion, $afiliado_tipo_id, $afiliado_id) {

    //require_once("conexionpg.php");
    global $conexionn;

    if (is_null($segundo_apellido) || $segundo_apellido == NULL || $segundo_apellido == 'NULL' || strlen($segundo_apellido) < 4) {
        $segundo = "";
        $segundo_apellido = "'" . $segundo . "',";
    } else {
        $segundo_apellido = str_replace("'", "", $segundo_apellido);
        $segundo_apellido = "'" . $segundo_apellido . "',";
    }
    if ($telefono_movil == 'NULL' || strlen($telefono_movil) < 6) {
        $telefono = "";
        $telefono_movil = "'" . $telefono . "',";
    } else {
        $telefono_movil = "" . $telefono_movil . ",";
    }


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
              VALUES (" . $primer_apellido . ", 
               		    $segundo_apellido
               		  " . $primer_nombre . ",
               		  " . $segundo_nombre . ",
               		  " . $fecha_nacimiento . ",
               		  " . $fecha_afiliacion_sgss . ",
               		  " . $tipo_sexo_id . ",
               		  " . $ciuo_88_grupo_primario . ",
               		  " . $tipo_pais_id . ",
               		  " . $tipo_dpto_id . ",
               		  " . $tipo_mpio_id . ",
               		  " . $zona_residencia . ",
               		  " . $direccion_residencia . ",
               		  " . $telefono_residencia . ",
               		   $telefono_movil
               		  " . $usuario_registro . ",
               		  now(),
               		  " . $usuario_ultima_actualizacion . ",
               		  now(),
               		  " . $afiliado_tipo_id . ",
               		  " . $afiliado_id . "); ";

    $result = pg_query($conexionn, $query);

    //insertar_pacientes($primer_apellido, $segundo_apellido, $primer_nombre, $segundo_nombre, $fecha_nacimiento, $fecha_afiliacion_sgss, $tipo_sexo_id, $ciuo_88_grupo_primario, $tipo_pais_id, $tipo_dpto_id, $tipo_mpio_id, $zona_residencia, $direccion_residencia, $telefono_residencia, $telefono_movil, $usuario_registro, $usuario_ultima_actualizacion, $afiliado_tipo_id, $afiliado_id);
    registrar_log($query, '', '0');
    if ($result) {
        registrar_log($query, '', '0');
        return true;
    } else {
        registrar_log(pg_escape_string($query), pg_escape_string(pg_last_error($conexionn)), '1');
        return "Error en el insert de los datos: " . $query;
    }
}

function update_epsafiliadosdatos($primer_apellido, $segundo_apellido, $primer_nombre, $segundo_nombre, $fecha_nacimiento, $fecha_afiliacion_sgss, $tipo_sexo_id, $ciuo_88_grupo_primario, $tipo_pais_id, $tipo_dpto_id, $tipo_mpio_id, $zona_residencia, $direccion_residencia, $telefono_residencia, $telefono_movil, $usuario_registro, $usuario_ultima_actualizacion, $afiliado_tipo_id, $afiliado_id, $afiliado_tipo_id_anterior, $afiliado_id_anterior) {

    //require_once("conexionpg.php");
    global $conexionn;

    if (is_null($segundo_apellido) || $segundo_apellido == NULL || $segundo_apellido == 'NULL' || strlen($segundo_apellido) < 4) {
        $segundo = "";
        $segundo_apellido = "segundo_apellido = '" . $segundo . "' , ";
    } else {
        $segundo_apellido = str_replace("'", "", $segundo_apellido);
        $segundo_apellido = "segundo_apellido = '" . $segundo_apellido . "',";
    }
    if ($direccion_residencia == 'NULL' || strlen($direccion_residencia) < 5) {
        $direccion= "";
        $direccion_residencia = "direccion_residencia = '" . $direccion . "', ";
    } else {
        $direccion_residencia = "direccion_residencia = " . $direccion_residencia . ", ";
    }
    if ($telefono_movil == 'NULL' || strlen($telefono_movil) < 6) {
        $telefono = "";
        $telefono_movil = "telefono_movil = '" . $telefono . "', ";
    } else {
        $telefono_movil = "telefono_movil = " . $telefono_movil . ", ";
    }

    $query = "
               UPDATE eps_afiliados_datos
               SET 
                primer_apellido = " . $primer_apellido . ", 
               $segundo_apellido
                primer_nombre = " . $primer_nombre . ", 
                segundo_nombre = " . $segundo_nombre . ", 
                fecha_nacimiento =  " . $fecha_nacimiento . ",
                fecha_afiliacion_sgss = " . $fecha_afiliacion_sgss . ",
                tipo_sexo_id = " . $tipo_sexo_id . ",
                ciuo_88_grupo_primario = " . $ciuo_88_grupo_primario . ",
                tipo_pais_id = " . $tipo_pais_id . ",
                tipo_dpto_id = " . $tipo_dpto_id . ",
                tipo_mpio_id =  " . $tipo_mpio_id . ",
                zona_residencia =  " . $zona_residencia . ",
                $direccion_residencia
                telefono_residencia = " . $telefono_residencia . ",
                $telefono_movil
                usuario_ultima_actualizacion =  " . $usuario_registro . ",
                fecha_ultima_actualizacion = now(),
                afiliado_tipo_id = " . $afiliado_tipo_id . ",
                afiliado_id = " . $afiliado_id . "
                where 
                afiliado_tipo_id = " . $afiliado_tipo_id_anterior . " and 
                afiliado_id = " . $afiliado_id_anterior . " ; ";

    $result = pg_query($conexionn, $query);

    registrar_log($query, '', '0');
    if ($result) {
        registrar_log($query, '', '0');
        return true;
    } else {
        registrar_log(pg_escape_string($query), pg_escape_string(pg_last_error($conexionn)), '1');
        return "Error en el insert de los datos: " . $query;
    }
}

function insertar_pacientes($primer_apellido, $segundo_apellido, $primer_nombre, $segundo_nombre, $fecha_nacimiento, $tipo_sexo_id, $tipo_pais_id, $tipo_dpto_id, $tipo_mpio_id, $zona_residencia, $direccion_residencia, $telefono_residencia, $telefono_movil, $afiliado_tipo_id, $afiliado_id) {

    //require_once("conexionpg.php");
    global $conexionn;

    if (is_null($segundo_apellido) || $segundo_apellido == 'NULL' || $segundo_apellido == NULL || strlen($segundo_apellido) < 4) {
        $segundo = "";
        $segundo_apellido = "'" . $segundo . "',";
    } else {
        $segundo_apellido = str_replace("'", "", $segundo_apellido);
        $segundo_apellido = "'" . $segundo_apellido . "',";
    }
    if ($telefono_movil == 'NULL' || strlen($telefono_movil) < 6) {
        $telefono = "";
        $telefono_movil = "'" . $telefono . "',";
    } else {
        $telefono_movil = "" . $telefono_movil . ",";
    }
    $query = " INSERT INTO pacientes(
                    paciente_id,
                    tipo_id_paciente,
                    primer_apellido,
                    segundo_apellido,
                    primer_nombre,
                    segundo_nombre,
                    fecha_nacimiento,
                    fecha_nacimiento_es_calculada,
                    residencia_direccion,
                    residencia_telefono,
                    celular_telefono,
                    zona_residencia,
                    ocupacion_id,
                    fecha_registro,
                    sexo_id,
                    tipo_pais_id,
                    tipo_dpto_id, tipo_mpio_id, usuario_id,
                    tipo_bloqueo_id)
                    VALUES (
                    " . $afiliado_id . ",
                    " . $afiliado_tipo_id . ",
                    " . $primer_apellido . ",
                    $segundo_apellido
                    " . $primer_nombre . ",
                    " . $segundo_nombre . ",
                    " . $fecha_nacimiento . ",
                    '0',
                    " . $direccion_residencia . ",
                    " . $telefono_residencia . ",
                    $telefono_movil
                    " . $zona_residencia . ",
                    NULL,
                    'NOW()',
                    " . $tipo_sexo_id . ",
                    " . $tipo_pais_id . ",
                    " . $tipo_dpto_id . ",
                    " . $tipo_mpio_id . ",
                    '123',
                    '1'
                    );";

    $result = pg_query($conexionn, $query);

    if ($result) {
        registrar_log(pg_escape_string($query), '', '0');
        return true;
    } else {
        registrar_log(pg_escape_string($query), pg_escape_string(pg_last_error($conexionn)), '1');
        return "Error en el insert de los datos: " . $query;
    }
}

function insertar_eps_afiliados_beneficiarios($eps_afiliacion_id, $afiliado_tipo_id, $afiliado_id, $cotizante_tipo_id, $cotizante_id, $parentesco_id, $observaciones) {

    //require_once("conexionpg.php");
    global $conexionn;


    $query = " INSERT INTO eps_afiliados_beneficiarios(
                eps_afiliacion_id, 
                afiliado_tipo_id, 
                afiliado_id, 
                cotizante_tipo_id, 
                cotizante_id, 
                parentesco_id, 
                usuario_registro, 
                fecha_registro, 
                observaciones)
                VALUES (
                " . $eps_afiliacion_id . ", 
                " . $afiliado_tipo_id . ", 
                " . $afiliado_id . ", 
                " . $cotizante_tipo_id . ", 
                " . $cotizante_id . ", 
                " . $parentesco_id . ", 
                '123', 
                'now()', 
                " . $observaciones . "
                );";

    $result = pg_query($conexionn, $query);
    registrar_log($query, '', '0');
    if ($result) {
        registrar_log($query, '', '0');
        return true;
    } else {
        registrar_log(pg_escape_string($query), pg_escape_string(pg_last_error($conexionn)), '1');
        return "Error en el insert de los datos: " . $query;
    }
}

function update_eps_afiliados_beneficiarios($eps_afiliacion_id, $afiliado_tipo_id, $afiliado_id, $cotizante_tipo_id, $cotizante_id, $parentesco_id, $observaciones, $accion_ultima_actualizacion, $eps_afiliacion_id_anterior, $afiliado_tipo_id_anterior, $afiliado_id_anterior) {

    //require_once("conexionpg.php");
    global $conexionn;


    $query = " UPDATE eps_afiliados_beneficiarios
                SET 
                cotizante_tipo_id=" . $cotizante_tipo_id . ", 
                cotizante_id=" . $cotizante_id . ", 
                parentesco_id=" . $parentesco_id . ",
                usuario_ultima_actualizacion='123', 
                accion_ultima_actualizacion=" . $accion_ultima_actualizacion . ", 
                fecha_ultima_actualizacion='now()', 
                observaciones=" . $observaciones . ",
                eps_afiliacion_id=" . $eps_afiliacion_id . ",
                afiliado_tipo_id=" . $afiliado_tipo_id . ",
                afiliado_id=" . $afiliado_id . "
              WHERE 
                eps_afiliacion_id=" . $eps_afiliacion_id_anterior . " and 
                afiliado_tipo_id=" . $afiliado_tipo_id_anterior . " and 
                afiliado_id=" . $afiliado_id_anterior . ";";

    $result = pg_query($conexionn, $query);
    registrar_log($query, '', '0');
    if ($result) {
        registrar_log($query, '', '0');
        return true;
    } else {
        registrar_log(pg_escape_string($query), pg_escape_string(pg_last_error($conexionn)), '1');
        return "Error en el insert de los datos: " . $query;
    }
}

function update_pacientes($primer_apellido, $segundo_apellido, $primer_nombre, $segundo_nombre, $fecha_nacimiento, $tipo_sexo_id, $tipo_pais_id, $tipo_dpto_id, $tipo_mpio_id, $zona_residencia, $direccion_residencia, $telefono_residencia, $telefono_movil, $afiliado_tipo_id, $afiliado_id, $afiliado_id_anterior, $afiliado_tipo_id_anterior) {

    //require_once("conexionpg.php");
    global $conexionn;

    if (is_null($segundo_apellido) || $segundo_apellido == NULL || $segundo_apellido == 'NULL' || strlen($segundo_apellido) < 4) {
        $segundo = "";
        $segundo_apellido = "segundo_apellido = '" . $segundo . "' , ";
    } else {
        $segundo_apellido = str_replace("'", "", $segundo_apellido);
        $segundo_apellido = "segundo_apellido = '" . $segundo_apellido . "',";
    }


    if ($direccion_residencia == 'NULL' || strlen($direccion_residencia) < 5) {
        $direccion= "";
        $direccion_residencia = "residencia_direccion = '" . $direccion . "', ";
    } else {
        $direccion_residencia = "residencia_direccion = " . $direccion_residencia . ", ";
    }
    
    if ($telefono_movil == 'NULL' || strlen($telefono_movil) < 6) {
        $telefono = "";
        $telefono_movil = "celular_telefono = '" . $telefono . "', ";
    } else {
        $telefono_movil = "celular_telefono = " . $telefono_movil . ", ";
    }

    $query = " UPDATE pacientes
               SET                    
                    primer_apellido= " . $primer_apellido . ",
                    $segundo_apellido
                    primer_nombre = " . $primer_nombre . ",
                    segundo_nombre =  " . $segundo_nombre . ",
                    fecha_nacimiento = " . $fecha_nacimiento . ",
                    fecha_nacimiento_es_calculada= '0',
                    $direccion_residencia
                    residencia_telefono=" . $telefono_residencia . ",
                    zona_residencia= " . $zona_residencia . ",
                    sexo_id= " . $tipo_sexo_id . ",
                    tipo_pais_id= " . $tipo_pais_id . ",
                    tipo_dpto_id = " . $tipo_dpto_id . ",
                    tipo_mpio_id = " . $tipo_mpio_id . ",
                    paciente_id = " . $afiliado_id . ",
                    $telefono_movil
                    tipo_id_paciente = " . $afiliado_tipo_id . "
                    where 
                    paciente_id = " . $afiliado_id_anterior . " AND 
                    tipo_id_paciente = " . $afiliado_tipo_id_anterior . ";";

    $result = pg_query($conexionn, $query);

    if ($result) {
        registrar_log(pg_escape_string($query), '', '0');
        return true;
    } else {
        registrar_log(pg_escape_string($query), pg_escape_string(pg_last_error($conexionn)), '1');
        return "Error en el insert de los datos: " . $query;
    }
}

function insertar_epsafiliaciones($eps_afiliacion_id, $eps_tipo_afiliacion_id, $fecha_recepcion, $usuario_id) {

    //require_once("conexionpg.php");
    global $conexionn;

    if ($fecha_recepcion == "") {
        $fecha_recepcion = 'now()';
    }

    if ($eps_afiliacion_id == "" || $eps_tipo_afiliacion_id == "") {
        return "Debe enviar todos los parametros que sean obligatorios";
    }

    $query = "INSERT INTO eps_afiliaciones ( eps_afiliacion_id,
											eps_tipo_afiliacion_id,
											fecha_recepcion,
											usuario_registro,
											fecha_registro,
											usuario_ultima_actualizacion,
											fecha_ultima_actualizacion)
					VALUES (" . $eps_afiliacion_id . ",
							" . $eps_tipo_afiliacion_id . ",
							" . $fecha_recepcion . ",
							" . $usuario_id . ",
							now(),
							" . $usuario_id . ",
							now() ); ";

    $result = pg_query($conexionn, $query);

    if ($result) {
        registrar_log($query, '', '0');
        return true;
    } else {
        registrar_log(pg_escape_string($query), pg_escape_string(pg_last_error($conexionn)), '1');
        return "Error en el insert de los datos: " . $query;
    }
}

function update_epsafiliaciones($eps_afiliacion_id, $eps_tipo_afiliacion_id, $fecha_recepcion, $usuario_id, $eps_afiliacion_id_anterior) {

    //require_once("conexionpg.php");
    global $conexionn;

    if ($fecha_recepcion == "") {
        $fecha_recepcion = 'now()';
    }

    if ($eps_afiliacion_id == "" || $eps_afiliacion_id_anterior == "") {
        return "Debe enviar todos los parametros que sean obligatorios";
    }

    $query = "UPDATE eps_afiliaciones
               SET  
                eps_tipo_afiliacion_id = " . $eps_tipo_afiliacion_id . ",
                fecha_recepcion = " . $fecha_recepcion . ",
                usuario_ultima_actualizacion = " . $usuario_id . ",
                fecha_ultima_actualizacion = now(),
                eps_afiliacion_id = " . $eps_afiliacion_id . "
                where 
                eps_afiliacion_id = " . $eps_afiliacion_id_anterior . " ;";

    $result = pg_query($conexionn, $query);

    if ($result) {
        registrar_log($query, '', '0');
        return true;
    } else {
        registrar_log(pg_escape_string($query), pg_escape_string(pg_last_error($conexionn)), '1');
        return "Error en el insert de los datos: " . $query;
    }
}

function rango_afiliado_atencion($rango_afiliado_atencion, $condicional) {

    if ($condicional == '770') {
        return str_replace("  ", " ", str_replace("POS", "", $rango_afiliado_atencion));
    }
    if ($condicional == '769') {
        return str_replace("  ", " ", str_replace("PAC", "", $rango_afiliado_atencion));
    }
}

function insertar_epsafiliados($eps_afiliacion_id, $afiliado_tipo_id, $afiliado_id, $eps_tipo_afiliado_id, $fecha_afiliacion, $eps_anterior, $fecha_afiliacion_eps_anterior, $semanas_cotizadas_eps_anterior, $plan_atencion, $tipo_afiliado_atencion, $rango_afiliado_atencion, $eps_punto_atencion_id, $fecha_vencimiento, $observaciones, $subestado_afiliado_id, $estado_afiliado_id, $usuario_id) {

    //require_once("conexionpg.php");
    global $conexionn;
    
//    "plan_atencion:: '372' 
//tipo_afiliado_atencion::  '6' 
//rango_afiliado_atencion::  'PUERTOS PAC'"

    $rango_afiliado_atencion = trim($rango_afiliado_atencion);
    $rango_afiliado_atencion2 = trim($rango_afiliado_atencion);
     $plan_atencion22=$plan_atencion;
    if ($plan_atencion == "'1'") {
        $plan_atencion = '8';
        $rango_afiliado_atencion = str_replace("  ", " ", str_replace("'", "", $rango_afiliado_atencion) . ' MAGISTERIO');
    }
    if ($plan_atencion == "'2'") {
        $plan_atencion = '7';
        $rango_afiliado_atencion = str_replace("  ", " ", str_replace("'", "", $rango_afiliado_atencion) . ' MAGISTERIO');
    }
    if ($plan_atencion == "'372'" && $rango_afiliado_atencion == 'PUERTOS PAC') {
        $plan_atencion = '770';
        $rango_afiliado_atencion = rango_afiliado_atencion(str_replace("'", "", $rango_afiliado_atencion), "770");
    }
    if ($plan_atencion == "'372'" && $rango_afiliado_atencion != 'PUERTOS PAC') {
        $plan_atencion = '769';
        $rango_afiliado_atencion = rango_afiliado_atencion(str_replace("'", "", $rango_afiliado_atencion), "769");
    }

    if ($eps_afiliacion_id == "" || $afiliado_tipo_id == "" || $afiliado_id == "" || $eps_tipo_afiliado_id == "") {
        return "Debe enviar todos los parametros que sean obligatorios";
    }


    if ($fecha_afiliacion_eps_anterior == "") {
        $fecha_afiliacion_eps_anterior = 'now()';
    }
    if ($fecha_vencimiento == "") {
        $fecha_vencimiento = 'now()';
    }

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
                                                            subestado_afiliado_id,
                                                            estado_afiliado_id,
				                            usuario_registro  , 
                				            fecha_registro    , 
				                            usuario_ultima_actualizacion  , 
                				            fecha_ultima_actualizacion) 
                	VALUES (" . $eps_afiliacion_id . ",
                			" . $afiliado_tipo_id . ",
                			" . $afiliado_id . ",
                			" . $eps_tipo_afiliado_id . ",
                			" . $fecha_afiliacion . ",
                			" . $eps_anterior . ",
                			" . $fecha_afiliacion_eps_anterior . ",
                			" . $semanas_cotizadas_eps_anterior . ",
                			" . $plan_atencion . ",
                			" . $tipo_afiliado_atencion . ",
                			'" . trim($rango_afiliado_atencion) . "',
                			" . $eps_punto_atencion_id . ",
                			" . $fecha_vencimiento . ",
                			" . $observaciones . ",
                			" . $subestado_afiliado_id . ",
                			" . $estado_afiliado_id . ",
                			" . $usuario_id . ",
                			now(),
                			" . $usuario_id . ",
                			now() ); ";

    $result = pg_query($conexionn, $query);

    if ($result) {
        registrar_log($query, '', '0');
        return true;
    } else {
        registrar_log("plan_atencion:: ".$plan_atencion22." tipo_afiliado_atencion::".$tipo_afiliado_atencion." rango_afiliado_atencion".$rango_afiliado_atencion, pg_escape_string(pg_last_error($conexionn)), '1');
        return "Error en el insert de los datos: " . $query;
    }
}

function update_epsafiliados($eps_afiliacion_id, $afiliado_tipo_id, $afiliado_id, $eps_tipo_afiliado_id, $fecha_afiliacion, $eps_anterior, $fecha_afiliacion_eps_anterior, $semanas_cotizadas_eps_anterior, $plan_atencion, $tipo_afiliado_atencion, $rango_afiliado_atencion, $eps_punto_atencion_id, $fecha_vencimiento, $observaciones, $usuario_id, $eps_afiliacion_id_anterior, $subestado_afiliado_id, $estado_afiliado_id, $afiliado_tipo_id_anterior, $afiliado_id_anterior) {

    global $conexionn;

    $rango_afiliado_atencion = trim($rango_afiliado_atencion);
    $rango_afiliado_atencion2 = trim($rango_afiliado_atencion);
     $plan_atencion22=$plan_atencion;

    if ($plan_atencion == "'1'") {
        $plan_atencion = '8';
        $rango_afiliado_atencion = str_replace("  ", " ", str_replace("'", "", $rango_afiliado_atencion) . ' MAGISTERIO');
    }
    if ($plan_atencion == "'2'") {
        $plan_atencion = '7';
        $rango_afiliado_atencion = str_replace("  ", " ", str_replace("'", "", $rango_afiliado_atencion) . ' MAGISTERIO');
    }
    if ($plan_atencion == "'3'") {
        $plan_atencion = '770';
        $rango_afiliado_atencion = rango_afiliado_atencion(str_replace("'", "", $rango_afiliado_atencion), "770");
    }
    if ($plan_atencion == "'4'") {
        $plan_atencion = '769';
        $rango_afiliado_atencion = rango_afiliado_atencion(str_replace("'", "", $rango_afiliado_atencion), "769");
    }
    
    if ($plan_atencion == "'372'" && $rango_afiliado_atencion == 'PUERTOS PAC') {
        $plan_atencion = '770';
        $rango_afiliado_atencion = rango_afiliado_atencion(str_replace("'", "", $rango_afiliado_atencion), "770");
    }
    if ($plan_atencion == "'372'" && $rango_afiliado_atencion != 'PUERTOS PAC') {
        $plan_atencion = '769';
        $rango_afiliado_atencion = rango_afiliado_atencion(str_replace("'", "", $rango_afiliado_atencion), "769");
    }


    if ($eps_afiliacion_id == "" || $afiliado_tipo_id == "" || $afiliado_id == "" || $eps_tipo_afiliado_id == "") {
        return "Debe enviar todos los parametros que sean obligatorios";
    }
    if ($fecha_afiliacion_eps_anterior == "") {
        $fecha_afiliacion_eps_anterior = 'now()';
    }
    if ($fecha_vencimiento == "") {
        $fecha_vencimiento = 'now()';
    }


    $query = "UPDATE eps_afiliados
               SET    eps_tipo_afiliado_id  = " . $eps_tipo_afiliado_id . ",
                fecha_afiliacion = " . $fecha_afiliacion . ", 
                eps_anterior  = " . $eps_anterior . ", 
                fecha_afiliacion_eps_anterior   = " . $fecha_afiliacion_eps_anterior . ",
                semanas_cotizadas_eps_anterior   = " . $semanas_cotizadas_eps_anterior . ", 
                plan_atencion = " . $plan_atencion . ",
                tipo_afiliado_atencion = " . $tipo_afiliado_atencion . ",
                rango_afiliado_atencion = '" . trim($rango_afiliado_atencion) . "',
                eps_punto_atencion_id = " . $eps_punto_atencion_id . ",
                fecha_vencimiento = " . $fecha_vencimiento . ", 
                observaciones    = " . $observaciones . ", 
                usuario_ultima_actualizacion = " . $usuario_id . ",
                fecha_ultima_actualizacion = now(),
                eps_afiliacion_id = " . $eps_afiliacion_id . ",
                 subestado_afiliado_id = " . $subestado_afiliado_id . ",
                 estado_afiliado_id = " . $estado_afiliado_id . ",
                afiliado_tipo_id = " . $afiliado_tipo_id . ",
                afiliado_id = " . $afiliado_id . " 
                where 
                eps_afiliacion_id = " . $eps_afiliacion_id_anterior . " and 
                afiliado_tipo_id = " . $afiliado_tipo_id_anterior . " and 
                afiliado_id = " . $afiliado_id_anterior . "; ";

    $result = pg_query($conexionn, $query);

    if ($result) {
        registrar_log($query, '', '0');
        return true;
    } else {
         registrar_log("plan_atencion:: ".$plan_atencion22." tipo_afiliado_atencion::".$tipo_afiliado_atencion." rango_afiliado_atencion".$rango_afiliado_atencion, pg_escape_string(pg_last_error($conexionn)), '1');
       
//        registrar_log(pg_escape_string($query) . " plan_atencion: " . $plan_atencion, pg_escape_string(pg_last_error($conexionn)), '1');
        return "Error en el insert de los datos: " . $query; //." fecha_afiliacion_eps_anterior:: ".$fecha_afiliacion_eps_anterior." fecha_vencimiento:: ".$fecha_vencimiento;
    }
}

function insertar_epsafiliadoscotizantes($eps_afiliacion_id, $afiliado_tipo_id, $afiliado_id, $ciiu_r3_division, $ciiu_r3_grupo, $ciiu_r3_clase, $telefono_dependencia, $estrato_socioeconomico_id, $tipo_estado_civil_id, $tipo_aportante_id, $estamento_id, $codigo_afp, $ingreso_mensual, $fecha_ingreso_laboral, $codigo_dependencia_id, $usuario_id, $sirh_per_codigo, $ter_codigo, $parentesco_id) {

    //require_once("conexionpg.php");
    global $conexionn;

    if ($fecha_ingreso_laboral == "") {
        $fecha_ingreso_laboral = 'now()';
    }

    if ($eps_afiliacion_id == "" || $afiliado_tipo_id == "" || $afiliado_id == "" || $telefono_dependencia == "" || $tipo_estado_civil_id == "" || $tipo_aportante_id == "" || $estamento_id == "" || $codigo_dependencia_id == "") {
        return "Debe enviar todos los parametros que sean obligatorios";
    }


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
        					" . $afiliado_tipo_id . ",
        					" . $afiliado_id . ",
        					" . $ciiu_r3_division . ",
        					" . $ciiu_r3_grupo . ",
        					" . $ciiu_r3_clase . ",
        					" . $telefono_dependencia . ",
        					" . $estrato_socioeconomico_id . ",
        					" . $tipo_estado_civil_id . ",
        					" . $tipo_aportante_id . ",
        					" . $estamento_id . ",
        					" . $codigo_afp . ",
        					" . $ingreso_mensual . ",
        					" . $fecha_ingreso_laboral . ",
        					" . $codigo_dependencia_id . ",
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
        registrar_log(pg_escape_string($query), pg_escape_string(pg_last_error($conexionn)), '1');
        return "Error en el insert de los datos: " . $query;
    }
}

function update_epsafiliadoscotizantes($eps_afiliacion_id, $afiliado_tipo_id, $afiliado_id, $ciiu_r3_division, $ciiu_r3_grupo, $ciiu_r3_clase, $telefono_dependencia, $estrato_socioeconomico_id, $tipo_estado_civil_id, $tipo_aportante_id, $estamento_id, $codigo_afp, $ingreso_mensual, $fecha_ingreso_laboral, $codigo_dependencia_id, $usuario_id, $sirh_per_codigo, $ter_codigo, $parentesco_id, $eps_afiliacion_id_anterior, $afiliado_tipo_id_anterior, $afiliado_id_anterior) {

    //require_once("conexionpg.php");
    global $conexionn;

    if ($eps_afiliacion_id == "" || $afiliado_tipo_id == "" || $afiliado_id == "" || $telefono_dependencia == "" || $tipo_estado_civil_id == "" || $tipo_aportante_id == "" || $estamento_id == "" || $codigo_dependencia_id == "") {
        return "Debe enviar todos los parametros que sean obligatorios";
    }

    if ($fecha_ingreso_laboral == "") {
        $fecha_ingreso_laboral = 'now()';
    }

    $query = " UPDATE eps_afiliados_cotizantes
                SET                        
                ciiu_r3_division = " . $ciiu_r3_division . ",
                ciiu_r3_grupo = " . $ciiu_r3_grupo . ",
                ciiu_r3_clase = " . $ciiu_r3_clase . ",
                telefono_dependencia = " . $telefono_dependencia . ",
                estrato_socioeconomico_id = " . $estrato_socioeconomico_id . ",
                tipo_estado_civil_id = " . $tipo_estado_civil_id . ",
                tipo_aportante_id = " . $tipo_aportante_id . ",
                estamento_id = " . $estamento_id . ",
                codigo_afp = " . $codigo_afp . ",
                ingreso_mensual = " . $ingreso_mensual . ",
                fecha_ingreso_laboral = " . $fecha_ingreso_laboral . ",
                codigo_dependencia_id = " . $codigo_dependencia_id . ",                                                       
                usuario_ultima_actualizacion = " . $usuario_id . ",
                fecha_ultima_actualizacion = now(),
                sirh_per_codigo = " . $sirh_per_codigo . ",
                ter_codigo = " . $ter_codigo . ", 
                parentesco_id =" . $parentesco_id . ",
                eps_afiliacion_id = " . $eps_afiliacion_id . ",
                afiliado_tipo_id = " . $afiliado_tipo_id . ",
                afiliado_id = " . $afiliado_id . " 
                where 
                eps_afiliacion_id = " . $eps_afiliacion_id_anterior . " and 
                afiliado_tipo_id = " . $afiliado_tipo_id_anterior . " and 
                afiliado_id = " . $afiliado_id_anterior . " ;";

    $result = pg_query($conexionn, $query);

    if ($result) {
        registrar_log($query, '', '0');
        return true;
    } else {
        registrar_log(pg_escape_string($query), pg_escape_string(pg_last_error($conexionn)), '1');
        return "Error en el insert de los datos: " . $query;
    }
}

function insertar_epsafiliadoscotizantesconvenios($eps_afiliacion_id, $afiliado_tipo_id, $afiliado_id, $convenio_tipo_id_tercero, $convenio_tercero_id, $fecha_inicio_convenio, $fecha_vencimiento_convenio, $usuario_id) {

    //require_once("conexionpg.php");
    global $conexionn;

    if ($eps_afiliacion_id == "" || $afiliado_tipo_id == "" || $afiliado_id == "" ||
            fecha_inicio_convenio == "" || fecha_vencimiento_convenio == "" || usuario_registro == "" || accion_ultima_actualizacion == "") {
        return "Debe enviar todos los parametros que sean obligatorios";
    }

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
							" . $afiliado_tipo_id . ",
							" . $afiliado_id . ",
							" . $convenio_tipo_id_tercero . ",
							" . $convenio_tercero_id . ",
							" . $fecha_inicio_convenio . ",
							" . $fecha_vencimiento_convenio . ",
							" . $usuario_id . ",
							now(),
							" . $usuario_id . ",
							now() ); ";

    $result = pg_query($conexionn, $query);

    if ($result) {
        registrar_log($query, '', '0');
        return true;
    } else {
        registrar_log(pg_escape_string($query), pg_escape_string(pg_last_error($conexionn)), '1');
        return "Error en el insert de los datos: " . $query;
    }
}

function update_epsafiliadoscotizantesconvenios($eps_afiliacion_id, $afiliado_tipo_id, $afiliado_id, $convenio_tipo_id_tercero, $convenio_tercero_id, $fecha_inicio_convenio, $fecha_vencimiento_convenio, $usuario_id, $eps_afiliacion_id_anterior, $afiliado_tipo_id_anterior, $afiliado_id_anterior) {

    //require_once("conexionpg.php");
    global $conexionn;

    if ($eps_afiliacion_id_anterior == "" || $afiliado_tipo_id_anterior == "" || $afiliado_id_anterior == "" ||
            fecha_inicio_convenio == "" || fecha_vencimiento_convenio == "" || usuario_registro == "" || accion_ultima_actualizacion == "") {
        return "Debe enviar todos los parametros que sean obligatorios";
    }

    $query = "UPDATE 
                eps_afiliados_cotizantes_convenios
                SET 
                convenio_tipo_id_tercero = " . $convenio_tipo_id_tercero . ",
                convenio_tercero_id = " . $convenio_tercero_id . ",
                fecha_inicio_convenio = " . $fecha_inicio_convenio . ",
                fecha_vencimiento_convenio = " . $fecha_vencimiento_convenio . ",                       
                usuario_ultima_actualizacion = " . $usuario_id . ",
                fecha_ultima_actualizacion = now(),
                eps_afiliacion_id = " . $eps_afiliacion_id . ",
                afiliado_tipo_id = " . $afiliado_tipo_id . ",
                afiliado_id = " . $afiliado_id . " 
                where 
                eps_afiliacion_id = " . $eps_afiliacion_id_anterior . " and 
                afiliado_tipo_id = " . $afiliado_tipo_id_anterior . " and 
                afiliado_id = " . $afiliado_id_anterior . " ;";

    $result = pg_query($conexionn, $query);

    if ($result) {
        registrar_log($query, '', '0');
        return true;
    } else {
        registrar_log(pg_escape_string($query), pg_escape_string(pg_last_error($conexionn)), '1');
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
        registrar_log(pg_escape_string($sql), pg_escape_string(pg_last_error($conexionn)), '1');
        return "0";
    }
}

function consultar_eps_afiliados_datos($tipo_id_paciente, $paciente_id) {

    //require_once("conexionpg.php");
    global $conexionn;
    $continuar = false;
    $estado = 0;
    $msj = "";
    $sql = "select * from eps_afiliados_datos a where a.afiliado_tipo_id = '{$tipo_id_paciente}' and a.afiliado_id= '{$paciente_id}' ";

    $result = pg_query($conexionn, $sql);

    if ($result) {

        $arr = pg_num_rows($result);

        registrar_log($sql, '', '0');

        if ($arr > 0) {

            $continuar = true;
            $msj = "El paciente si se encuentra en la tabla eps_afiliados_datos";
            $estado = 1;
        } else {
            $continuar = false;
            $msj = "El paciente No existe";
            $estado = 2;
        }
    } else {
        registrar_log(pg_escape_string($sql), pg_escape_string(pg_last_error($conexionn)), '1');
        $continuar = false;
        $msj = "Error interno";
        $estado = 3;
    }

    //return $continuar;
    return array("msj" => $msj, "continuar" => $continuar, "estado" => $estado);
}

function consultar_eps_afiliados($tipo_id_paciente, $paciente_id, $eps_afiliacion_id) {

    //require_once("conexionpg.php");
    global $conexionn;

    $sql = " select * from eps_afiliados a where  a.afiliado_tipo_id = '{$tipo_id_paciente}' and a.afiliado_id= '{$paciente_id}' and a.eps_afiliacion_id= '{$eps_afiliacion_id}' ";



    $result = pg_query($conexionn, $sql);

    if ($result) {

        $arr = pg_num_rows($result);

        registrar_log($sql, '', '0');

        return "{$arr}";
    } else {

        registrar_log(pg_escape_string($sql), pg_escape_string(pg_last_error($conexionn)), '1');


        return "0";
    }
}

function consultar_eps_afiliados_activo($tipo_id_paciente, $paciente_id, $eps_afiliacion_id) {

    //require_once("conexionpg.php");
    global $conexionn;

    $sql = " select * from eps_afiliados a "
            . "where  a.afiliado_tipo_id = '{$tipo_id_paciente}' "
            . "and a.afiliado_id= '{$paciente_id}' "
            . "and a.estado_afiliado_id= 'AC' "
            . "and a.eps_afiliacion_id= '{$eps_afiliacion_id}' ";



    $result = pg_query($conexionn, $sql);

    if ($result) {

        $arr = pg_num_rows($result);

        registrar_log($sql, '', '0');

        return "{$arr}";
    } else {

        registrar_log(pg_escape_string($sql), pg_escape_string(pg_last_error($conexionn)), '1');


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
        registrar_log(pg_escape_string($sql), pg_escape_string(pg_last_error($conexionn)), '1');

        return "0";
    }
}

function consultar_eps_afiliados_beneficiarios($tipo_id_paciente, $paciente_id, $eps_afiliacion_id) {

    //require_once("conexionpg.php");
    global $conexionn;

    $sql = " select * from eps_afiliados_beneficiarios a where a.afiliado_tipo_id = '{$tipo_id_paciente}' and a.afiliado_id= '{$paciente_id}' and a.eps_afiliacion_id = '{$eps_afiliacion_id}' ";

    $result = pg_query($conexionn, $sql);

    if ($result) {

        $arr = pg_num_rows($result);

        registrar_log($sql, '', '0');

        return "{$arr}";
    } else {
        registrar_log(pg_escape_string($sql), pg_escape_string(pg_last_error($conexionn)), '1');

        return "0";
    }
}

function consultar_eps_afiliados_cotizantes($tipo_id_paciente, $paciente_id, $eps_afiliacion_id) {

    //require_once("conexionpg.php"); 
    global $conexionn;

    //$sql = " select * from eps_afiliados_cotizantes a where a.afiliado_tipo_id = '{$tipo_id_paciente}' and a.afiliado_id= '{$paciente_id}' ";
    $sql = " select * from eps_afiliados_cotizantes a where a.afiliado_tipo_id = '{$tipo_id_paciente}' and a.afiliado_id= '{$paciente_id}' and a.eps_afiliacion_id = '{$eps_afiliacion_id}' ";


    $result = pg_query($conexionn, $sql);



    if ($result) {

        $arr = pg_num_rows($result);

        registrar_log($sql, '', '0');

        return "{$arr}";
    } else {

        registrar_log(pg_escape_string($sql), pg_escape_string(pg_last_error($conexionn)), '1');

        return "0";
    }
}

function registrar_log($query, $resultado, $error) {

    global $conexionn;
    $query=pg_escape_string($query);
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