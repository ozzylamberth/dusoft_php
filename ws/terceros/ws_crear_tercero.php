<?php
require_once('../../classes/CalculoFechas/CalculoFechas.class.php');
require_once('../../nusoap/lib/nusoap.php');
//require_once('Services_JSON.php');
$server = new nusoap_server;
$server->configureWSDL('FormulacionWs', 'urn:formulacion_ws');

require_once("conexionpg.php");
//$url = "http://10.0.2.170/DUSOFT_DUANA/ws/Formulacion/ws_formulacion.php?wsdl";

//======================= Registrar Funciones ==========================
$server->register('crearTercero', array('WS_terceros' => 'tns:WS_terceros'), array('return' => 'tns:WS_resultado'), "urn:terceros", "urn:ws_crear_tercero#crearTercero", "rpc", "encoded", "Webservice: Permite la Sincronizacion de los terceros de FI - ASISTENCIAL");
$server->register('actualizarTercero', array('WS_terceros' => 'tns:WS_terceros'), array('return' => 'tns:WS_resultado'), "urn:terceros", "urn:ws_crear_tercero#actualizarTercero", "rpc", "encoded", "Webservice: Permite la Sincronizacion de los terceros de FI - ASISTENCIAL");

/*
$server->register('crearProvedor', array('WS_provedor' => 'tns:WS_provedor'), array('return' => 'tns:WS_resultado'), "urn:provedor", "urn:ws_crear_tercero#crearProvedor", "rpc", "encoded", "Webservice: Permite la Sincronizacion de los provedores de COSMITET - DUANA");

$server->register('crearCliente', array('WS_cliente' => 'tns:WS_cliente'), array('return' => 'tns:WS_resultado'), "urn:cliente", "urn:ws_crear_tercero#crearCliente", "rpc", "encoded", "Webservice: Permite la Sincronizacion de los clientes de COSMITET - DUANA");
*/

// Estructura de respuesta
$server->wsdl->addComplexType('WS_resultado', 'complexType', 'struct', 'all', '', array('msj' => array('name' => 'msj', 'type' => 'xsd:string'), 'estado' => array('name' => 'estado', 'type' => 'xsd:boolean'), 'datos' => array('name' => 'datos', 'type' => 'xsd:string')));


/////////////////////////////////////////////////TERCEROS////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$server->wsdl->addComplexType('WS_terceros', 'complexType', 'struct', 'all', '',
    array(
        'codigoDian' => array('name' => 'codigoDian', 'type' => 'xsd:string'),
        'numeroIdentificacion' => array('name' => 'numeroIdentificacion', 'type' => 'xsd:string'),
        'codigoPais' => array('name' => 'codigoPais', 'type' => 'xsd:string'),
        'codigoDepartamento' => array('name' => 'codigoDepartamento', 'type' => 'xsd:string'),
        'codigoCiudad' => array('name' => 'codigoCiudad', 'type' => 'xsd:string'),
        'direccion' => array('name' => 'direccion', 'type' => 'xsd:string'),
        'telefono' => array('name' => 'telefono', 'type' => 'xsd:string'),
        //'fax' => array('name' => 'fax', 'type' => 'xsd:string'),
        'emailPrincipal' => array('name' => 'emailPrincipal', 'type' => 'xsd:string'),
        //'nombre_usuario' => array('name' => 'nombre_usuario', 'type' => 'xsd:string'),
        'nombre' => array('name' => 'nombre', 'type' => 'xsd:string'),
        'apellidos' => array('name' => 'apellidos', 'type' => 'xsd:string'),
        'naturaleza' => array('name' => 'naturaleza', 'type' => 'xsd:string')
    )
);
/*
$server->wsdl->addComplexType('WS_provedor', 'complexType', 'struct', 'all', '', array(
    'empresa_id' => array('name' => 'empresa_id', 'type' => 'xsd:string'), 'tipo_id_tercero' => array('name' => 'tipo_id_tercero', 'type' => 'xsd:string'), 'tercero_id' => array('name' => 'tercero_id', 'type' => 'xsd:string'), 'empresa_id_centro' => array('name' => 'empresa_id_centro', 'type' => 'xsd:string'), 'centro_utilidad' => array('name' => 'centro_utilidad', 'type' => 'xsd:string'), 'estado' => array('name' => 'estado', 'type' => 'xsd:string'), 'dias_gracia' => array('name' => 'dias_gracia', 'type' => 'xsd:string'), 'dias_credito' => array('name' => 'dias_credito', 'type' => 'xsd:string'), 'tiempo_entrega' => array('name' => 'tiempo_entrega', 'type' => 'xsd:string'), 'descuento_por_contado' => array('name' => 'descuento_por_contado', 'type' => 'xsd:string'), 'cupo' => array('name' => 'cupo', 'type' => 'xsd:string'), 'sw_regimen_comun' => array('name' => 'sw_regimen_comun', 'type' => 'xsd:string'), 'sw_gran_contribuyente' => array('name' => 'sw_gran_contribuyente', 'type' => 'xsd:string'), 'actividad_id' => array('name' => 'actividad_id', 'type' => 'xsd:string'), 'porcentaje_rtf' => array('name' => 'porcentaje_rtf', 'type' => 'xsd:string'), 'porcentaje_ica' => array('name' => 'porcentaje_ica', 'type' => 'xsd:string'), 'representante_ventas' => array('name' => 'representante_ventas', 'type' => 'xsd:string'), 'telefono_representante_ventas' => array('name' => 'telefono_representante_ventas', 'type' => 'xsd:string'), 'nombre_gerente' => array('name' => 'nombre_gerente', 'type' => 'xsd:string'), 'prioridad_compra' => array('name' => 'prioridad_compra', 'type' => 'xsd:string'), 'telefono_gerente' => array('name' => 'telefono_gerente', 'type' => 'xsd:string'), 'porcentaje_reteiva' => array('name' => 'porcentaje_reteiva', 'type' => 'xsd:string'), 'sw_rtf' => array('name' => 'sw_rtf', 'type' => 'xsd:string'), 'sw_reteiva' => array('name' => 'sw_reteiva', 'type' => 'xsd:string'), 'sw_ica' => array('name' => 'sw_ica', 'type' => 'xsd:string'), 'sw_pago_abono_cta' => array('name' => 'sw_pago_abono_cta', 'type' => 'xsd:string'), 'sw_pago_efectivo' => array('name' => 'sw_pago_efectivo', 'type' => 'xsd:string'), 'sw_pago_cheque' => array('name' => 'sw_pago_cheque', 'type' => 'xsd:string'), 'plazo' => array('name' => 'plazo', 'type' => 'xsd:string'), 'maneja_iva' => array('name' => 'maneja_iva', 'type' => 'xsd:string'), 'dependencia_id' => array('name' => 'dependencia_id', 'type' => 'xsd:string'), 'area_servicio_id' => array('name' => 'area_servicio_id', 'type' => 'xsd:string'), 'valor_honorarios' => array('name' => 'valor_honorarios', 'type' => 'xsd:string'), 'porc_comision' => array('name' => 'porc_comision', 'type' => 'xsd:string'), 'horas_contratadas' => array('name' => 'horas_contratadas', 'type' => 'xsd:string'), 'cxp_proveedor' => array('name' => 'cxp_proveedor', 'type' => 'xsd:string'), 'valor_hora' => array('name' => 'valor_hora', 'type' => 'xsd:string'), 'cargo_id' => array('name' => 'cargo_id', 'type' => 'xsd:string'), 'tipo_proveedor' => array('name' => 'tipo_proveedor', 'type' => 'xsd:string'), 'calificacion_id' => array('name' => 'calificacion_id', 'type' => 'xsd:string'), 'ret_permanente' => array('name' => 'ret_permanente', 'type' => 'xsd:string'), 'man_imp_dist' => array('name' => 'man_imp_dist', 'type' => 'xsd:string'), 'departamento' => array('name' => 'departamento', 'type' => 'xsd:string'), 'municipio' => array('name' => 'municipio', 'type' => 'xsd:string'), 'direccion' => array('name' => 'direccion', 'type' => 'xsd:string'), 'fax' => array('name' => 'fax', 'type' => 'xsd:string'), 'gprcodigo' => array('name' => 'gprcodigo', 'type' => 'xsd:string'), 'sw_cree' => array('name' => 'sw_cree', 'type' => 'xsd:string'), 'porcentaje_cree' => array('name' => 'porcentaje_cree', 'type' => 'xsd:string'), 'excluir_fletes' => array('name' => 'excluir_fletes', 'type' => 'xsd:string'), 'divisa_pago_id' => array('name' => 'divisa_pago_id', 'type' => 'xsd:string'), 'divisa_factura_id' => array('name' => 'divisa_factura_id', 'type' => 'xsd:string'), 'codigo_ciuu' => array('name' => 'codigo_ciuu', 'type' => 'xsd:string')
));

$server->wsdl->addComplexType('WS_cliente', 'complexType', 'struct', 'all', '', array(
    'empresa_id' => array('name' => 'empresa_id', 'type' => 'xsd:string'), 'tipo_id_tercero' => array('name' => 'tipo_id_tercero', 'type' => 'xsd:string'), 'tercero_id' => array('name' => 'tercero_id', 'type' => 'xsd:string'), 'sw_gran_contribuyente' => array('name' => 'sw_gran_contribuyente', 'type' => 'xsd:string'), 'porcentaje_reteiva' => array('name' => 'porcentaje_reteiva', 'type' => 'xsd:string'), 'porcentaje_ica' => array('name' => 'porcentaje_ica', 'type' => 'xsd:string'), 'observacion' => array('name' => 'observacion', 'type' => 'xsd:string'), 'porcentaje_rtf' => array('name' => 'porcentaje_rtf', 'type' => 'xsd:string'), 'sw_rtf' => array('name' => 'sw_rtf', 'type' => 'xsd:string'), 'sw_reteiva' => array('name' => 'sw_reteiva', 'type' => 'xsd:string'), 'sw_regimen_comun' => array('name' => 'sw_regimen_comun', 'type' => 'xsd:string'), 'codigo_unidad_negocio' => array('name' => 'codigo_unidad_negocio', 'type' => 'xsd:string'), 'sw_ica' => array('name' => 'sw_ica', 'type' => 'xsd:string'), 'tipo_cliente' => array('name' => 'tipo_cliente', 'type' => 'xsd:string'), 'sw_cree' => array('name' => 'sw_cree', 'type' => 'xsd:string'), 'porcentaje_cree' => array('name' => 'porcentaje_cree', 'type' => 'xsd:string'), 'cuenta_contable' => array('name' => 'cuenta_contable', 'type' => 'xsd:string'),
));
*/

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///  FUNCIONES SECUNDARIAS
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///  CREAR TERCERO
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function traducirCodigoDian($codigoDian)
{
    if($codigoDian == 31){
        $tipoIdTercero = 'NIT';
    }elseif($codigoDian == 13){
        $tipoIdTercero = 'CC';
    }elseif($codigoDian == 12){
        $tipoIdTercero = 'TI';
    }elseif($codigoDian == 11){
        $tipoIdTercero = 'RC';
    }elseif($codigoDian == 22){
        $tipoIdTercero = 'CE';
    }elseif($codigoDian == 41){
        $tipoIdTercero = 'PA';
    }

    return $tipoIdTercero;
}

function validarCamposTercero($tercero)
{
    $response['status'] = false;
    $response['error'] = '';
    $response['error_count'] = 0;
    $codigosDianValidos = array(11, 12, 13, 22, 31, 41);
    $naturalezasValidas = array('NATURAL', 'JURIDICA');
    extract($tercero, EXTR_OVERWRITE);

    if(!isset($codigoDian) || $codigoDian === '' || $codigoDian === '?' || !in_array($codigoDian, $codigosDianValidos)){
        $response['error_count']++;
        $response['error'] .= 'Campo codigoDian no puede estar vacio y debe ser valido! ';
    }
    if(!isset($numeroIdentificacion) || $numeroIdentificacion === '' || $numeroIdentificacion === '?'){
        $response['error_count']++;
        $response['error'] .= 'Campo tercero_id no puede estar vacio! ';
    }
    if(!isset($codigoPais) || $codigoPais === '' || $codigoPais === '?'){
        $response['error_count']++;
        $response['error'] .= 'Campo codigoPais no puede estar vacio! ';
    }
    if(!isset($codigoDepartamento) || $codigoDepartamento === '' || $codigoDepartamento === '?'){
        $response['error_count']++;
        $response['error'] .= 'Campo codigoDepartamento no puede estar vacio! ';
    }
    if(!isset($codigoCiudad) || $codigoCiudad === '' || $codigoCiudad === '?'){
        $response['error_count']++;
        $response['error'] .= 'Campo codigoCiudad no puede estar vacio! ';
    }
    if(!isset($direccion) || $direccion === '' || $direccion === '?'){
        $response['error_count']++;
        $response['error'] .= 'Campo direccion no puede estar vacio! ';
    }
    if(!isset($telefono) || $telefono === '' || $telefono === '?'){
        $response['error_count']++;
        $response['error'] .= 'Campo telefono no puede estar vacio! ';
    }
    if(!isset($emailPrincipal) || $emailPrincipal === '' || $emailPrincipal === '?'){
        $response['error_count']++;
        $response['error'] .= 'Campo emailPrincipal no puede estar vacio! ';
    }
    if(!isset($nombre) || $nombre === '' || $nombre === '?'){
        $response['error_count']++;
        $response['error'] .= 'Campo nombre no puede estar vacio! ';
    }
    /*
    if(isset($apellidos) && ($apellidos === '' || $apellidos === '?')){
        //$response['error_count']++;
        //$response['error'] .= 'Campo apellidos no puede estar vacio! ';
    }
    */
    if(!isset($naturaleza) || $naturaleza === '' || $naturaleza === '?' || !in_array($naturaleza, $naturalezasValidas)){
        $response['error_count']++;
        $response['error'] .= 'Campo naturaleza no puede estar vacio y debe ser valido! ';
    }
    if(!existePaisDepartamentoMunicipio($tercero)){
        $response['error_count']++;
        $response['error'] .= 'Campos Pais, Ciudad y municipio no existen en la BD!';
    }

    if($response['error_count'] === 0){
        $response['status'] = true;
    }

    return $response;
}

function existeTercero($tercero)
{
    $response = false;
    $numeroIdentificacion = false;
    $tipo_id_tercero = false;
    extract($tercero, EXTR_OVERWRITE);

    $sqlExisteTercero = '
        SELECT
            tercero_id
        FROM
            terceros
        WHERE
            tipo_id_tercero = \''.$tipo_id_tercero.'\'
            AND
            tercero_id = \''.$numeroIdentificacion.'\';
    ';
    $consultaExisteTercero = pg_query($GLOBALS['conexionn'], $sqlExisteTercero);
    $datos = pg_fetch_all($consultaExisteTercero);
    if(isset($datos[0]['tercero_id'])){
        $response = true;
    }

    //var_dump($sqlExisteTercero);
    return $response;
}

function insertTercero($tercero)
{
    $response = '';
    $tipo_id_tercero = '';
    $numeroIdentificacion = '';
    $codigoPais = '';
    $codigoDepartamento = '';
    $codigoCiudad = '';
    $direccion = '';
    $telefono = '';
    $fax = '';
    $celular = '';
    $fecha_registro = date('Y-m-d H:i:s');
    $emailPrincipal = '';
    $nombre = '';
    $apellidos = '';
    $busca_persona = '';
    $cal_cli = 0;
    $sw_persona_juridica = '';
    $naturaleza = '';
    $usuario_id = 0;
    extract($tercero, EXTR_OVERWRITE);

    if($apellidos != '' && $apellidos != ' ' && $apellidos != '?'){
        $nombre .= ' '.$apellidos;
    }
    if($naturaleza == 'NATURAL'){
        $sw_persona_juridica = 0;
    }elseif($naturaleza == 'JURIDICA'){
        $sw_persona_juridica = 1;
    }

    $sqlCrearTercero = '
        INSERT INTO terceros
        (
            tipo_id_tercero, 
            tercero_id,
            tipo_pais_id,
            tipo_dpto_id,
            tipo_mpio_id,
            direccion,
            telefono,
            fax,
            email,
            celular,
            sw_persona_juridica,
            cal_cli,
            usuario_id,
            fecha_registro,
            busca_persona,
            nombre_tercero
        )
        VALUES
        (
            \''.$tipo_id_tercero.'\', 
            \''.$numeroIdentificacion.'\',
            \''.$codigoPais.'\', 
            \''.$codigoDepartamento.'\', 
            \''.$codigoCiudad.'\',
            \''.$direccion.'\', 
            \''.$telefono.'\', 
            \''.$fax.'\',
            \''.$emailPrincipal.'\', 
            \''.$celular.'\', 
            \''.$sw_persona_juridica.'\', 
            \''.$cal_cli.'\', 
            \''.$usuario_id.'\', 
            \''.$fecha_registro.'\', 
            \''.$busca_persona.'\', 
            \''.$nombre.'\'
        );
    ';
    if(pg_query($GLOBALS['conexionn'], $sqlCrearTercero)){
        $response = true;
    }

    //var_dump($sqlCrearTercero);
    return $response;
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///  CREAR PROVEDOR
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function validarCamposProvedor($provedor)
{
    $response['status'] = false;
    $response['error'] = '';
    $response['error_count'] = 0;
    extract($provedor, EXTR_OVERWRITE);

    if(!isset($empresa_id) || $empresa_id === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo empresa_id no puede estar vacio! ';
    }
    if(!isset($tercero_id) || $tercero_id === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo tercero_id no puede estar vacio! ';
    }
    if(!isset($empresa_id_centro) || $empresa_id_centro === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo empresa_id_centro no puede estar vacio! ';
    }
    if(!isset($centro_utilidad) || $centro_utilidad === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo centro_utilidad no puede estar vacio! ';
    }
    if(!isset($estado) || $estado === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo estado no puede estar vacio! ';
    }
    if(!isset($dias_gracia) || $dias_gracia === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo dias_gracia no puede estar vacio! ';
    }
    if(!isset($dias_credito) || $dias_credito === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo dias_credito no puede estar vacio! ';
    }
    if(!isset($tiempo_entrega) || $tiempo_entrega === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo tiempo_entrega no puede estar vacio! ';
    }
    if(!isset($descuento_por_contado) || $descuento_por_contado === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo descuento_por_contado no puede estar vacio! ';
    }
    if(!isset($cupo) || $cupo === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo cupo no puede estar vacio! ';
    }
    if(!isset($sw_regimen_comun) || $sw_regimen_comun === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo sw_regimen_comun no puede estar vacio! ';
    }
    if(!isset($sw_gran_contribuyente) || $sw_gran_contribuyente === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo sw_gran_contribuyente no puede estar vacio! ';
    }
    if(!isset($actividad_id) || $actividad_id === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo actividad_id no puede estar vacio! ';
    }
    if(!isset($porcentaje_rtf) || $porcentaje_rtf === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo porcentaje_rtf no puede estar vacio! ';
    }
    if(!isset($porcentaje_ica) || $porcentaje_ica === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo porcentaje_ica no puede estar vacio! ';
    }
    if(!isset($representante_ventas) || $representante_ventas === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo representante_ventas no puede estar vacio! ';
    }
    if(!isset($telefono_representante_ventas) || $telefono_representante_ventas === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo telefono_representante_ventas no puede estar vacio! ';
    }
    if(!isset($nombre_gerente) || $nombre_gerente === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo nombre_gerente no puede estar vacio! ';
    }
    if(!isset($prioridad_compra) || $prioridad_compra === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo prioridad_compra no puede estar vacio! ';
    }
    if(!isset($telefono_gerente) || $telefono_gerente === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo telefono_gerente no puede estar vacio! ';
    }
    if(!isset($porcentaje_reteiva) || $porcentaje_reteiva === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo porcentaje_reteiva no puede estar vacio! ';
    }
    if(!isset($sw_rtf) || $sw_rtf === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo sw_rtf no puede estar vacio! ';
    }
    if(!isset($sw_reteiva) || $sw_reteiva === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo sw_reteiva no puede estar vacio! ';
    }
    if(!isset($sw_ica) || $sw_ica === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo sw_ica no puede estar vacio! ';
    }
    if(!isset($sw_pago_abono_cta) || $sw_pago_abono_cta === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo sw_pago_abono_cta no puede estar vacio! ';
    }
    if(!isset($sw_pago_efectivo) || $sw_pago_efectivo === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo sw_pago_efectivo no puede estar vacio! ';
    }
    if(!isset($sw_pago_cheque) || $sw_pago_cheque === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo sw_pago_cheque no puede estar vacio! ';
    }
    if(!isset($plazo) || $plazo === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo plazo no puede estar vacio! ';
    }
    if(!isset($maneja_iva) || $maneja_iva === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo maneja_iva no puede estar vacio! ';
    }
    if(!isset($dependencia_id) || $dependencia_id === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo dependencia_id no puede estar vacio! ';
    }
    if(!isset($area_servicio_id) || $area_servicio_id === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo area_servicio_id no puede estar vacio! ';
    }
    if(!isset($valor_honorarios) || $valor_honorarios === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo valor_honorarios no puede estar vacio! ';
    }
    if(!isset($porc_comision) || $porc_comision === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo porc_comision no puede estar vacio! ';
    }
    if(!isset($horas_contratadas) || $horas_contratadas === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo horas_contratadas no puede estar vacio! ';
    }
    if(!isset($cxp_proveedor) || $cxp_proveedor === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo cxp_proveedor no puede estar vacio! ';
    }
    if(!isset($valor_hora) || $valor_hora === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo valor_hora no puede estar vacio! ';
    }
    if(!isset($cargo_id) || $cargo_id === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo cargo_id no puede estar vacio! ';
    }
    if(!isset($tipo_proveedor) || $tipo_proveedor === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo tipo_proveedor no puede estar vacio! ';
    }
    if(!isset($calificacion_id) || $calificacion_id === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo calificacion_id no puede estar vacio! ';
    }
    if(!isset($ret_permanente) || $ret_permanente === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo ret_permanente no puede estar vacio! ';
    }
    if(!isset($man_imp_dist) || $man_imp_dist === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo man_imp_dist no puede estar vacio! ';
    }
    if(!isset($departamento) || $departamento === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo departamento no puede estar vacio! ';
    }
    if(!isset($municipio) || $municipio === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo municipio no puede estar vacio! ';
    }
    if(!isset($direccion) || $direccion === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo direccion no puede estar vacio! ';
    }
    if(!isset($fax) || $fax === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo fax no puede estar vacio! ';
    }
    if(!isset($gprcodigo) || $gprcodigo === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo gprcodigo no puede estar vacio! ';
    }
    if(!isset($sw_cree) || $sw_cree === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo sw_cree no puede estar vacio! ';
    }
    if(!isset($porcentaje_cree) || $porcentaje_cree === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo porcentaje_cree no puede estar vacio! ';
    }
    if(!isset($excluir_fletes) || $excluir_fletes === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo excluir_fletes no puede estar vacio! ';
    }
    if(!isset($divisa_pago_id) || $divisa_pago_id === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo divisa_pago_id no puede estar vacio! ';
    }
    if(!isset($divisa_factura_id) || $divisa_factura_id === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo divisa_factura_id no puede estar vacio! ';
    }
    if(!isset($codigo_ciuu) || $codigo_ciuu === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo codigo_ciuu no puede estar vacio! ';
    }

    if($response['error_count'] === 0){
        $response['status'] = true;
    }

    return $response;
}

function existeProvedor($provedor)
{
    $response = false;
    $tercero_id = false;
    $tipo_id_tercero = false;

    extract($provedor, EXTR_OVERWRITE);

    $sqlExisteProvedor = '
        SELECT
            empresa_id
        FROM
            terceros_proveedores
        WHERE
            tipo_id_tercero = \''.$tipo_id_tercero.'\'
            AND
            tercero_id = \''.$tercero_id.'\';
    ';
    if($consultaExisteProvedor = pg_query($GLOBALS['conexionn'], $sqlExisteProvedor)){
        $existeProvedor = pg_fetch_all($consultaExisteProvedor);
        if(isset($existeProvedor[0]['empresa_id'])){
            $response = true;
        }
    }

    return $response;
}

function insertProvedor($provedor)
{
    $response = false;
    $empresa_id = false;
    $tipo_id_tercero = false;
    $tercero_id = false;
    $empresa_id_centro = false;
    $centro_utilidad = false;
    $estado = false;
    $dias_gracia = false;
    $dias_credito = false;
    $tiempo_entrega = false;
    $descuento_por_contado = false;
    $cupo = false;
    $sw_regimen_comun = false;
    $sw_gran_contribuyente = false;
    $actividad_id = false;
    $porcentaje_rtf = false;
    $porcentaje_ica = false;
    $representante_ventas = false;
    $telefono_representante_ventas = false;
    $nombre_gerente = false;
    $prioridad_compra = false;
    $telefono_gerente = false;
    $porcentaje_reteiva = false;
    $sw_rtf = false;
    $sw_reteiva = false;
    $sw_ica = false;
    $sw_pago_abono_cta = false;
    $sw_pago_efectivo = false;
    $sw_pago_cheque = false;
    $plazo = false;
    $maneja_iva = false;
    $dependencia_id = false;
    $area_servicio_id = false;
    $valor_honorarios = false;
    $porc_comision = false;
    $horas_contratadas = false;
    $cxp_proveedor = false;
    $valor_hora = false;
    $cargo_id = false;
    $tipo_proveedor = false;
    $calificacion_id = false;
    $ret_permanente = false;
    $man_imp_dist = false;
    $departamento = false;
    $municipio = false;
    $direccion = false;
    $fax = false;
    $gprcodigo = false;
    $sw_cree = false;
    $porcentaje_cree = false;
    $excluir_fletes = false;
    $divisa_pago_id = false;
    $divisa_factura_id = false;
    $codigo_ciuu = false;
    extract($provedor, EXTR_OVERWRITE);

    $sqlCrearProvedor = '
        INSERT INTO terceros_proveedores
        (
            empresa_id,
            tipo_id_tercero,
            tercero_id,
            empresa_id_centro,
            centro_utilidad,
            estado,
            dias_gracia,
            dias_credito,
            tiempo_entrega,
            descuento_por_contado,
            cupo,
            sw_regimen_comun,
            sw_gran_contribuyente,
            actividad_id,
            porcentaje_rtf,
            porcentaje_ica,
            representante_ventas,
            telefono_representante_ventas,
            nombre_gerente,
            prioridad_compra,
            telefono_gerente,
            porcentaje_reteiva,
            sw_rtf,
            sw_reteiva,
            sw_ica,
            sw_pago_abono_cta,
            sw_pago_efectivo,
            sw_pago_cheque,
            plazo,
            maneja_iva,
            dependencia_id,
            area_servicio_id,
            valor_honorarios,
            porc_comision,
            horas_contratadas,
            cxp_proveedor,
            valor_hora,
            cargo_id,
            tipo_proveedor,
            calificacion_id,
            ret_permanente,
            man_imp_dist,
            departamento,
            municipio,
            direccion,
            fax,
            gprcodigo,
            sw_cree,
            porcentaje_cree,
            excluir_fletes,
            divisa_pago_id,
            divisa_factura_id,
            codigo_ciuu
        )
        VALUES
        (
            \''.$empresa_id.'\',
            \''.$tipo_id_tercero.'\',
            \''.$tercero_id.'\',
            NULL,
            NULL,
            \'1\',
            \''.$dias_gracia.'\',
            \''.$dias_credito.'\',
            \''.$tiempo_entrega.'\',
            \''.$descuento_por_contado.'\',
            \''.$cupo.'\',
            \''.$sw_regimen_comun.'\',
            \''.$sw_gran_contribuyente.'\',
            \''.$actividad_id.'\',
            \''.$porcentaje_rtf.'\',
            \''.$porcentaje_ica.'\',
            \''.$representante_ventas.'\',
            \''.$telefono_representante_ventas.'\',
            \''.$nombre_gerente.'\',
            \''.$prioridad_compra.'\',
            \''.$telefono_gerente.'\',
            \''.$porcentaje_reteiva.'\',
            \''.$sw_rtf.'\',
            \''.$sw_reteiva.'\',
            \''.$sw_ica.'\',
            \''.$sw_pago_abono_cta.'\',
            \''.$sw_pago_efectivo.'\',
            \''.$sw_pago_cheque.'\',
            \'0\',
            NULL,
            NULL,
            NULL,
            NULL,
            NULL,
            NULL,
            NULL,
            NULL,
            NULL,
            \'0\',
            NULL,
            NULL,
            NULL,
            NULL,
            NULL,
            NULL,
            NULL,
            NULL,
            \''.$sw_cree.'\',
            \''.$porcentaje_cree.'\',
            NULL,
            NULL,
            NULL,
            NULL
        )
    ';
    if($consultaCrearProvedor = pg_query($GLOBALS['conexionn'], $sqlCrearProvedor)){
        $response = true;
    }

    return $response;
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///  CREAR CLIENTE
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function validarCamposCliente($cliente)
{
    $response['status'] = false;
    $response['error'] = '';
    $response['error_count'] = 0;
    extract($cliente, EXTR_OVERWRITE);

    if(!isset($empresa_id) || $empresa_id === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo empresa_id no puede estar vacio! ';
    }
    if(!isset($tipo_id_tercero) || $tipo_id_tercero === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo tipo_id_tercero no puede estar vacio! ';
    }
    if(!isset($tercero_id) || $tercero_id === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo tercero_id no puede estar vacio! ';
    }
    if(!isset($sw_gran_contribuyente) || $sw_gran_contribuyente === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo sw_gran_contribuyente no puede estar vacio! ';
    }
    if(!isset($porcentaje_reteiva) || $porcentaje_reteiva === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo porcentaje_reteiva no puede estar vacio! ';
    }
    if(!isset($porcentaje_ica) || $porcentaje_ica === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo porcentaje_ica no puede estar vacio! ';
    }
    if(!isset($observacion) || $observacion === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo observacion no puede estar vacio! ';
    }
    if(!isset($porcentaje_rtf) || $porcentaje_rtf === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo porcentaje_rtf no puede estar vacio! ';
    }
    if(!isset($sw_rtf) || $sw_rtf === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo sw_rtf no puede estar vacio! ';
    }
    if(!isset($sw_reteiva) || $sw_reteiva === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo sw_reteiva no puede estar vacio! ';
    }
    if(!isset($sw_regimen_comun) || $sw_regimen_comun === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo sw_regimen_comun no puede estar vacio! ';
    }
    if(!isset($codigo_unidad_negocio) || $codigo_unidad_negocio === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo codigo_unidad_negocio no puede estar vacio! ';
    }
    if(!isset($sw_ica) || $sw_ica === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo sw_ica no puede estar vacio! ';
    }
    if(!isset($tipo_cliente) || $tipo_cliente === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo tipo_cliente no puede estar vacio! ';
    }
    if(!isset($sw_cree) || $sw_cree === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo sw_cree no puede estar vacio! ';
    }
    if(!isset($porcentaje_cree) || $porcentaje_cree === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo porcentaje_cree no puede estar vacio! ';
    }
    if(!isset($cuenta_contable) || $cuenta_contable === ''){
        $response['error_count']++;
        $response['error'] .= 'Campo cuenta_contable no puede estar vacio! ';
    }

    if($response['error_count'] === 0){
        $response['status'] = true;
    }

    return $response;
}

function existeCliente($cliente)
{
    $response = false;
    $tipo_id_tercero = '';
    $tercero_id = '';
    extract($cliente, EXTR_OVERWRITE);

    $sqlExisteCliente = '
        SELECT
            empresa_id
        FROM
            terceros_clientes
        WHERE
            tipo_id_tercero = \''.$tipo_id_tercero.'\'
            AND
            tercero_id = \''.$tercero_id.'\';
    ';
    if($consultaExisteCliente = pg_query($GLOBALS['conexionn'], $sqlExisteCliente)){
        $existeCliente = pg_fetch_all($consultaExisteCliente);
        if(isset($existeCliente[0]['empresa_id'])){
            $response = true;
        }
    }

    return $response;
}

function existePaisDepartamentoMunicipio($tercero){
    $response = false;
    $pais = trim($tercero['codigoPais']);
    $departamento = trim($tercero['codigoDepartamento']);
    $municipìo = trim($tercero['codigoCiudad']);

    $sqlPaisDepartamentoMunicipio = '
        SELECT
            municipio
        FROM
            tipo_mpios
        WHERE
            tipo_pais_id = \''.$pais.'\'
            AND
            tipo_dpto_id = \''.$departamento.'\'
            AND
            tipo_mpio_id = \''.$municipìo.'\'
    ';
    $queryPaisDepartamentoMunicipio = pg_query($GLOBALS['conexionn'], $sqlPaisDepartamentoMunicipio);
    $datos = pg_fetch_all($queryPaisDepartamentoMunicipio);
    if(isset($datos[0]['municipio'])){
        $response = true;
    }
    //var_dump($response);
    return $response;
}

function insertCliente($cliente)
{
    $response = false;
    $empresa_id = '';
    $tipo_id_tercero = '';
    $tercero_id = '';
    $sw_gran_contribuyente = '';
    $porcentaje_reteiva = '';
    $porcentaje_ica = '';
    $observacion = '';
    $porcentaje_rtf = '';
    $sw_rtf = '';
    $sw_reteiva = '';
    $sw_regimen_comun = '';
    $codigo_unidad_negocio = '';
    $sw_ica = '';
    $tipo_cliente = '';
    $sw_cree = '';
    $porcentaje_cree = '';
    $cuenta_contable = '';
    extract($cliente, EXTR_OVERWRITE);

    $sqlCrearCliente = '
        INSERT INTO terceros_clientes
        (
            empresa_id,
            tipo_id_tercero,
            tercero_id,
            sw_gran_contribuyente,
            porcentaje_reteiva,
            porcentaje_ica,
            observacion,
            porcentaje_rtf,
            sw_rtf,
            sw_reteiva,
            sw_regimen_comun,
            codigo_unidad_negocio,
            sw_ica,
            tipo_cliente,
            sw_cree,
            porcentaje_cree,
            cuenta_contable
        )
        VALUES
        (
            \''.$empresa_id.'\',
            \''.$tipo_id_tercero.'\',
            \''.$tercero_id.'\',
            \''.$sw_gran_contribuyente.'\',
            \''.$porcentaje_reteiva.'\',
            \''.$porcentaje_ica.'\',
            NULL,
            \''.$porcentaje_rtf.'\',
            \''.$sw_rtf.'\',
            \''.$sw_reteiva.'\',
            \''.$sw_regimen_comun.'\',
            NULL,
            \''.$sw_ica.'\',
            \''.$tipo_cliente.'\',
            \''.$sw_cree.'\',
            \''.$porcentaje_cree.'\',
            NULL
        );
    ';
    if($consultaCrearCliente = pg_query($GLOBALS['conexionn'], $sqlCrearCliente)){
        $response = true;
    }

    return $response;
}

function updateTercero($tercero)
{
    $response = false;
    $tipo_id_tercero = '';
    $tercero_id = '';
    $camposUpdate = '';
    $camposUpdates = '';

    extract($tercero, EXTR_OVERWRITE);
    if(isset($codigoPais)){
        $camposUpdates .= 'tipo_pais_id = \''.$codigoPais.'\', ';
    }
    if(isset($codigoDepartamento)){
        $camposUpdates .= 'tipo_dpto_id = \''.$codigoDepartamento.'\', ';
    }
    if(isset($codigoCiudad)){
        $camposUpdates .= 'tipo_mpio_id = \''.$codigoCiudad.'\', ';
    }
    if(isset($direccion)){
        $camposUpdates .= 'direccion = \''.$direccion.'\', ';
    }
    if(isset($telefono)){
        $camposUpdates .= 'telefono = \''.$telefono.'\', ';
    }
    if(isset($emailPrincipal)){
        $camposUpdates .= 'email = \''.$emailPrincipal.'\', ';
    }
    if(isset($nombre) && isset($apellidos)){
        $fullName = $nombre.' '.$apellidos;
        $camposUpdates .= 'nombre_tercero = \''.$fullName.'\', ';
    }
    if(isset($sw_persona_juridica)){
        $camposUpdates .= 'sw_persona_juridica = \''.$sw_persona_juridica.'\', ';
    }
    $camposUpdate = substr($camposUpdates, 0, -2);

    $sqlActualizarTercero = '
        UPDATE
            terceros
        SET
            '.$camposUpdate.'
        WHERE
            tipo_id_tercero = \''.$tipo_id_tercero.'\'
            AND
            tercero_id = \''.$numeroIdentificacion.'\'
    ;';

    if(pg_query($GLOBALS['conexionn'], $sqlActualizarTercero)){
        $response = true;
    }
    //var_dump($sqlActualizarTercero);

    return $response;
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// FUNCIONES PRINCIPALES
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function crearTercero($tercero)
{
    $response = validarCamposTercero($tercero);
    if($response['status']){
        $tercero['tipo_id_tercero'] = traducirCodigoDian($tercero['codigoDian']);

        if(existeTercero($tercero)){
            $msjs = 'El tercero ya existe!';
        }else{
            if(insertTercero($tercero)){
                $msjs = 'Tercero registrado!';
            }else{
                $msjs = 'Error al registar Tercero!';
            }
        }
    }else{
        $msjs = $response['error'];
    }

    return array('msj' => $msjs);
}

function actualizarTercero($tercero)
{
    $response = validarCamposTercero($tercero);
    if($response['status']){
        $tercero['tipo_id_tercero'] = traducirCodigoDian($tercero['codigoDian']);

        if(!existeTercero($tercero)){
            $msjs = 'El tercero no existe!';
        }else{
            if(updateTercero($tercero)){
                $msjs = 'Tercero actualizado!';
            }else{
                $msjs = 'Error al actualizar Tercero!';
            }
        }
    }else{
        $msjs = $response['error'];
    }

    return array('msj' => $msjs);
}

function crearProvedor($provedor)
{
    $response = validarCamposProvedor($provedor);
    if($response['status']){
        if(existeTercero($provedor)){
            if(!existeProvedor($provedor)){
                if(insertProvedor($provedor)){
                    $msjs = 'Provedor registrado con exito!';
                }else{
                    $msjs = 'Error al registrar el Provedor!';
                }
            }else{
                $msjs = 'Provedor ya estaba registrado!';
            }
        }else{
            $msjs = 'Primero debe registrarse como Tercero!';
        }
    }else{
        $msjs = $response['error'];
    }

    return array('msj' => $msjs);
}

function crearCliente($cliente)
{
    $response = validarCamposCliente($cliente);
    if($response['status']){
        if(existeTercero($cliente)){
            if(!existeCliente($cliente)){
                if(insertCliente($cliente)){
                    $msjs = 'Cliente registrado con exito!';
                }else{
                    $msjs = 'Error al registrar el Cliente!';
                }
            }else{
                $msjs = 'El cliente ya estaba registrado!';
            }
        }else{
            $msjs = 'Primero debe registrarse como Tercero!';
        }
    }else{
        $msjs = $response['error'];
    }

    return array('msj' => $msjs);
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    INVOCA EL SERVICIO
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if(isset($HTTP_RAW_POST_DATA)){
    $input = $HTTP_RAW_POST_DATA;
}else{
    $input = implode("rn", file('php://input'));
}
$server->service($input);
?>