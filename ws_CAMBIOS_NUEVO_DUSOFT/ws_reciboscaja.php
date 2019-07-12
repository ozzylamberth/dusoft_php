<?php

/*
 * Fecha Creacion: 22-I-2014
 * Creado Por: Steven H. Gamboa
 *
 * Descripcion General: Web Service que permite crear un Recibo de Caja/Tesoreria en la BD -Cosmitet, Dumian-. 
 * 					   Consumo principal para FI
 *
 */
//http://192.168.200.3/SIIS/ws/ws_reciboscaja.php?wsdl
require_once('../nusoap/lib/nusoap.php');
$server = new nusoap_server;
$server->configureWSDL('RecibosCajaWs', 'urn:recibosCaja_ws');

require_once ("codificacion_productos/conexionpg.php");
$url = "http://10.0.2.170/Pruebas_DUSOFT_DUANA_/ws/ws_consultas.php?wsdl";


/* Crea el recibo de caja en la BD -Cosmitet-Dumian- con datos recibidos de FI */

//Inputs
//Input DatosGeneral (datos necesarios para realizar algunas consultas en la BD -Cosmitet,Dumian-, y realizar algunas validaciones -Formas de Pago-)
$server->wsdl->addComplexType('WSDatosGeneral', 'complexType', 'struct', 'all', '', array('tipoFormaPago' => array('name' => 'tipoFormaPago', 'type' => 'xsd:string'),
    'empresaId' => array('name' => 'empresaId', 'type' => 'xsd:string'),
    'documentoId' => array('name' => 'documentoId', 'type' => 'xsd:string'),
    'prefijo' => array('name' => 'prefijo', 'type' => 'xsd:string'),
    'consecutivoFI' => array('name' => 'consecutivoFI', 'type' => 'xsd:string')
        )
);

$server->wsdl->addComplexType('WSArrayDatosGeneral', 'complexType', 'array', '', 'SOAP-ENC:Array', array(), array(
    array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:WSDatosGeneral[]')
        ), 'tns:WSDatosGeneral'
);

//Input ReciboCaja (datos necesarior para crear el recibo caja)
$server->wsdl->addComplexType('WSReciboCaja', 'complexType', 'struct', 'all', '', array('empresaId' => array('name' => 'empresaId', 'type' => 'xsd:string'),
    'centroUtilidad' => array('name' => 'centroUtilidad', 'type' => 'xsd:string'),
    'totalAbono' => array('name' => 'totalAbono', 'type' => 'xsd:int'),
    'totalEfectivo' => array('name' => 'totalEfectivo', 'type' => 'xsd:int'),
    'totalCheques' => array('name' => 'totalCheques', 'type' => 'xsd:int'),
    'totalTarjetas' => array('name' => 'totalTarjetas', 'type' => 'xsd:int'),
    'totalConsignacion' => array('name' => 'totalConsignacion', 'type' => 'xsd:int'),
    'terceroTipo' => array('name' => 'terceroTipo', 'type' => 'xsd:string'),
    'terceroId' => array('name' => 'terceroId', 'type' => 'xsd:string'),
    'usuarioId' => array('name' => 'usuarioId', 'type' => 'xsd:int'),
    'observacion' => array('name' => 'observacion', 'type' => 'xsd:string')
        )
);

$server->wsdl->addComplexType('WSArrayWSReciboCaja', 'complexType', 'array', '', 'SOAP-ENC:Array', array(), array(
    array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:WSReciboCaja[]')
        ), 'tns:WSReciboCaja'
);

//Input Conceptos (datos necesarios para asociarle los conceptos)
$server->wsdl->addComplexType('WSConceptos', 'complexType', 'struct', 'all', '', array('conceptoId' => array('name' => 'conceptoId', 'type' => 'xsd:string'),
    'naturaleza' => array('name' => 'naturaleza', 'type' => 'xsd:string'),
    'valor' => array('name' => 'valor', 'type' => 'xsd:int')
        )
);

$server->wsdl->addComplexType('WSArrayWSConceptos', 'complexType', 'array', '', 'SOAP-ENC:Array', array(), array(
    array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:WSConceptos[]')
        ), 'tns:WSConceptos'
);

//Input Formas de Pago
//Cheque
$server->wsdl->addComplexType('WSCheque', 'complexType', 'struct', 'all', '', array('banco' => array('name' => 'banco', 'type' => 'xsd:string'),
    'cheque' => array('name' => 'cheque', 'type' => 'xsd:string'),
    'girador' => array('name' => 'girador', 'type' => 'xsd:string'),
    'fechaCheque' => array('name' => 'fechaCheque', 'type' => 'xsd:string'),
    'fecha' => array('name' => 'fecha', 'type' => 'xsd:string'),
    'fechaRegistro' => array('name' => 'fechaRegistro', 'type' => 'xsd:string'),
    'ctaCte' => array('name' => 'ctaCte', 'type' => 'xsd:string'),
    'valor' => array('name' => 'valor', 'type' => 'xsd:int')
        )
);

$server->wsdl->addComplexType('WSArrayWSCheque', 'complexType', 'array', '', 'SOAP-ENC:Array', array(), array(
    array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:WSCheque[]')
        ), 'tns:WSCheque'
);

//Consignacion
$server->wsdl->addComplexType('WSConsignacion', 'complexType', 'struct', 'all', '', array('numeroCuenta' => array('name' => 'numeroCuenta', 'type' => 'xsd:string'),
    'numeroTransaccion' => array('name' => 'numeroTransaccion', 'type' => 'xsd:string'),
    'fechaTransaccion' => array('name' => 'fechaTransaccion', 'type' => 'xsd:string'),
    'valor' => array('name' => 'valor', 'type' => 'xsd:int')
        )
);

$server->wsdl->addComplexType('WSArrayWSConsignacion', 'complexType', 'array', '', 'SOAP-ENC:Array', array(), array(
    array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:WSConsignacion[]')
        ), 'tns:WSConsignacion'
);

//Tarjeta Credito
$server->wsdl->addComplexType('WSTarjetaCredito', 'complexType', 'struct', 'all', '', array('tarjeta' => array('name' => 'tarjeta', 'type' => 'xsd:string'),
    'socio' => array('name' => 'socio', 'type' => 'xsd:string'),
    'fechaExpira' => array('name' => 'fechaExpira', 'type' => 'xsd:string'),
    'autorizadoPor' => array('name' => 'autorizadoPor', 'type' => 'xsd:string'),
    'autorizacion' => array('name' => 'autorizacion', 'type' => 'xsd:string'),
    'fecha' => array('name' => 'fecha', 'type' => 'xsd:string'),
    'fechaRegistro' => array('name' => 'fechaRegistro', 'type' => 'xsd:string'),
    'tarjetaNumero' => array('name' => 'tarjetaNumero', 'type' => 'xsd:string'),
    'valor' => array('name' => 'valor', 'type' => 'xsd:int')
        )
);

$server->wsdl->addComplexType('WSArrayWSTarjetaCredito', 'complexType', 'array', '', 'SOAP-ENC:Array', array(), array(
    array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:WSTarjetaCredito[]')
        ), 'tns:WSTarjetaCredito'
);

//Tarjeta Debito
$server->wsdl->addComplexType('WSTarjetaDebito', 'complexType', 'struct', 'all', '', array('autorizacion' => array('name' => 'autorizacion', 'type' => 'xsd:string'),
    'tarjeta' => array('name' => 'tarjeta', 'type' => 'xsd:string'),
    'tarjetaNumero' => array('name' => 'tarjetaNumero', 'type' => 'xsd:string'),
    'valor' => array('name' => 'valor', 'type' => 'xsd:int')
        )
);

$server->wsdl->addComplexType('WSArrayWSTarjetaDebito', 'complexType', 'array', '', 'SOAP-ENC:Array', array(), array(
    array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:WSTarjetaDebito[]')
        ), 'tns:WSTarjetaDebito'
);

//Ouput
$server->wsdl->addComplexType('WSResultado', 'complexType', 'struct', 'all', '', array('descripcion' => array('name' => 'descripcion', 'type' => 'xsd:string'),
    'estado' => array('name' => 'estado', 'type' => 'xsd:boolean')
        )
);

$server->register('crearReciboCaja', array('datosGeneral' => 'tns:WSDatosGeneral',
    'datosReciboCaja' => 'tns:WSReciboCaja',
    'datosConceptos' => 'tns:WSConceptos',
    'datosCheque' => 'tns:WSCheque',
    'datosConsignacion' => 'tns:WSConsignacion',
    'datosTarjetaCredito' => 'tns:WSTarjetaCredito',
    'datosTarjetaDebito' => 'tns:WSTarjetaDebito'
        ), array('return' => 'tns:WSResultado'), "urn:recibosCaja_ws", "urn:recibosCaja_ws#crearReciboCaja", "rpc", "encoded", "Webservice: permite crear recibos de caja-tesoreria desde FI a la BD -Cosmitet,Dumian-");


/*
 * Obligatorios: $datosGeneral,$datosReciboCaja,$datosConceptos
 * Opcionales: $datosCheque,$datosConsignacion,$datosTCredito,$datosTDebito
 * Los "opcionales" dependen del tipo de forma de pago; si es diferente de Efectivo, alguno de los "opcionales" debera validarse.
 */

function crearReciboCaja($datosGeneral, $datosReciboCaja, $datosConceptos, $datosCheque, $datosConsignacion, $datosTCredito, $datosTDebito) {
    
    
  /*  return array(
        'descripcion' => "error de validacion test" . print_r($datosReciboCaja, true),
            'estado' => (bool) '0'
     );*/
    $validacionDatos = validacionesDatos($datosGeneral, $datosReciboCaja, $datosConceptos, $datosCheque, $datosConsignacion, $datosTCredito, $datosTDebito);

    if ($validacionDatos['estado'] == false) {
        return array('descripcion' => $validacionDatos['descripcion'],
            'estado' => (bool) '0');
    }

    //Funcion que realiza los Inserts a la BD -Cosmitet,Dumian-
    $result = createInserts($datosGeneral, $datosReciboCaja, $datosConceptos, $datosCheque, $datosConsignacion, $datosTCredito, $datosTDebito);

    return array('descripcion' => $result['descripcion'],
        'estado' => (bool) $result['estado']);
    /* return array('estado'=>(bool) '1',
      'descripcion'=>'Numero de Registros 11'); */
}

function createInserts($datosGeneral, $datosReciboCaja, $datosConceptos, $datosCheque, $datosConsignacion, $datosTCredito, $datosTDebito) {
    //require_once ("conexionpg.php");
    global $conexion;

    $cajaId = getCaja($datosReciboCaja['empresaId'], $datosReciboCaja['centroUtilidad']);
    $rcTipoDocumento = getRcTipoDocumento($datosReciboCaja['empresaId'], $datosReciboCaja['centroUtilidad']);
    $controlAnticipos = validarControlAnticipos($datosReciboCaja);
    $sqlRecibosCaja = sqlRecibosCaja($datosGeneral, $datosReciboCaja, $cajaId, $rcTipoDocumento);
    $sqlRCDetalle = sqlRCDetalle($datosGeneral, $datosReciboCaja);
    $sqlRcConceptos = sqlRcConceptos($datosGeneral, $datosReciboCaja, $datosConceptos);


    $sqlControlAnticipos = sqlControlAnticipos($datosReciboCaja);
    if ($controlAnticipos) {
        $sqlControlAnticipos = sqlUpdateControlAnticipos($datosReciboCaja);
    } 

    switch ($datosGeneral['tipoFormaPago']) {
        case 'EF': $sqlFormaPago = " ";
            break;

        case 'CH': $sqlFormaPago = sqlCheque($datosGeneral, $datosReciboCaja, $datosCheque);
            break;

        case 'CO': $sqlFormaPago = sqlConsignacion($datosGeneral, $datosReciboCaja, $datosConsignacion);
            break;

        case 'TC': $sqlFormaPago = sqlTarjetaCredito($datosGeneral, $datosReciboCaja, $datosTCredito);
            break;

        case 'TD': $sqlFormaPago = sqlTarjetaDebito($datosGeneral, $datosReciboCaja, $datosTDebito);
            break;

        default: $sqlFormaPago = " ";
            break;
    }

    $sql = " ";
    $sql .= $sqlRecibosCaja . " " . $sqlRCDetalle . " " . $sqlRcConceptos . " " . $sqlFormaPago . " " . $sqlControlAnticipos;
    
    $result = pg_query($conexion, $sql);
    
    if ($result) {
        return array('descripcion' => "Datos guardados correctamente",
            'estado' => (bool) '1');
    } else {

        return array('descripcion' => "Se genero un error en los inserts..".pg_last_error($conexion). "=======t ipo de ago ". $datosGeneral['tipoFormaPago']. "  =======  ". print_r($datosReciboCaja, true),  
            'estado' => (bool) '0');
    }
}

function sqlTarjetaDebito($datosGeneral, $datosReciboCaja, $datosTDebito) {
    $sql = "INSERT INTO tarjetas_mov_debito
            ( 
            empresa_id, 
            centro_utilidad, 
            recibo_caja, 
            prefijo, 
            autorizacion, 
            tarjeta, 
            total, 
            tarjeta_numero 
            )
            VALUES 
            (
            '" . $datosReciboCaja['empresaId'] . "',
            '" . $datosReciboCaja['centroUtilidad'] . "',
            '" . $datosGeneral['consecutivoFI'] . "',
            '" . $datosGeneral['prefijo'] . "',
            '" . $datosTDebito['autorizacion'] . "',
            '" . $datosTDebito['tarjeta'] . "',
            '" . $datosTDebito['valor'] . "',
            '" . $datosTDebito['tarjetaNumero'] . "'
            ); ";

    return $sql;
}

function sqlTarjetaCredito($datosGeneral, $datosReciboCaja, $datosTCredito) {
    $sql = "INSERT INTO tarjetas_mov_credito( 
            tarjeta , 
            empresa_id , 
            centro_utilidad , 
            recibo_caja, 
            prefijo, 
            autorizacion, 
            socio, 
            fecha_expira, 
            autorizado_por, 
            total, 
            usuario_id, 
            fecha, 
            fecha_registro, 
            tarjeta_numero 
            ) 
            VALUES (
            '" . $datosTCredito['tarjeta'] . "',
            '" . $datosReciboCaja['empresaId'] . "',
            '" . $datosReciboCaja['centroUtilidad'] . "',
            '" . $datosGeneral['consecutivoFI'] . "',
            '" . $datosGeneral['prefijo'] . "',
            '" . $datosTCredito['autorizacion'] . "',
            '" . $datosTCredito['socio'] . "',
            '" . $datosTCredito['fechaExpira'] . "',
            '" . $datosTCredito['autorizadoPor'] . "',
            '" . $datosTCredito['valor'] . "',
            '" . $datosReciboCaja['usuarioId'] . "',
            '" . $datosTCredito['fecha'] . "',
            '" . $datosTCredito['fechaRegistro'] . "',
            '" . $datosTCredito['tarjetaNumero'] . "'
            ); ";

    return $sql;
}

function sqlConsignacion($datosGeneral, $datosReciboCaja, $datosConsignacion) {
    $sql = "INSERT INTO bancos_consignaciones( 
            empresa_id, 
            centro_utilidad, 
            recibo_caja, 
            prefijo, 
            numero_cuenta, 
            valor, 
            numero_transaccion, 
            fecha_transaccion
            )
            VALUES(
            '" . $datosReciboCaja['empresaId'] . "',
            '" . $datosReciboCaja['centroUtilidad'] . "',
            '" . $datosGeneral['consecutivoFI'] . "',
            '" . $datosGeneral['prefijo'] . "',
            '" . $datosConsignacion['numeroCuenta'] . "',
            '" . $datosConsignacion['valor'] . "',
            '" . $datosConsignacion['numeroTransaccion'] . "',
            '" . $datosConsignacion['fechaTransaccion'] . "'
            ); ";

    return $sql;
}

function sqlCheque($datosGeneral, $datosReciboCaja, $datosCheque) {
    $sql = "INSERT INTO cheques_mov( 
            empresa_id, 
            centro_utilidad, 
            recibo_caja, 
            prefijo, 
            banco, 
            cheque, 
            girador, 
            fecha_cheque, 
            total, 
            fecha, 
            estado, 
            usuario_id, 
            fecha_registro, 
            cta_cte
            )
            VALUES (
            '" . $datosReciboCaja['empresaId'] . "', 
            '" . $datosReciboCaja['centroUtilidad'] . "', 
            '" . $datosGeneral['consecutivoFI'] . "', 
            '" . $datosGeneral['prefijo'] . "',	
            '" . $datosCheque['banco'] . "', 
            '" . $datosCheque['cheque'] . "', 
            '" . $datosCheque['girador'] . "', 
            '" . $datosCheque['fechaCheque'] . "', 
            '" . $datosCheque['valor'] . "', 
            '" . $datosCheque['fecha'] . "', 
            '0', 
            '" . $datosReciboCaja['usuarioId'] . "', 
            '" . $datosCheque['fechaRegistro'] . "', 
            '" . $datosCheque['ctaCte'] . "'
            ); ";

    return $sql;
}

function sqlRecibosCaja($datosGeneral, $datosReciboCaja, $cajaId, $rcTipoDocumento) {
    $sql = "INSERT INTO recibos_caja(
            empresa_id,
            centro_utilidad,
            recibo_caja,
            prefijo,
            fecha_ingcaja,
            total_abono,
            total_efectivo,
            total_cheques,
            total_tarjetas,
            total_consignacion,
            otros,
            tipo_id_tercero,
            tercero_id,
            estado,
            fecha_registro,
            usuario_id,
            caja_id,
            documento_id,
            cuenta_tipo_id,
            rc_tipo_documento,
            sw_recibo_tesoreria,
            tercero_id_endoso,
            tipo_id_tercero_endoso,
            observacion 
            )
            VALUES (
            '" . $datosReciboCaja['empresaId'] . "',
            '" . $datosReciboCaja['centroUtilidad'] . "',
            '" . $datosGeneral['consecutivoFI'] . "',
            '" . $datosGeneral['prefijo'] . "',
            '{$datosReciboCaja['fechaRegistroFi']}',
            '" . $datosReciboCaja['totalAbono'] . "',
            '" . $datosReciboCaja['totalEfectivo'] . "',
            '" . $datosReciboCaja['totalCheques'] . "',
            '" . $datosReciboCaja['totalTarjetas'] . "',
            '" . $datosReciboCaja['totalConsignacion'] . "',
            NULL,
            '" . $datosReciboCaja['terceroTipo'] . "',
            '" . $datosReciboCaja['terceroId'] . "',
            '2',
            '{$datosReciboCaja['fechaRegistroFi']}',
            '" . $datosReciboCaja['usuarioId'] . "',
            '" . $cajaId['cajaId'] . "',
            '" . $datosGeneral['documentoId'] . "',
            '07',
            '" . $rcTipoDocumento['rcTipoDocumento'] . "',
            '0',
            NULL,
            NULL,
            '" . $datosReciboCaja['observacion'] . "'
            );";

    /* $sql .= " UPDATE rc_control_anticipos 
      SET saldo = saldo + " . $datosReciboCaja['totalAbono'] . "
      WHERE empresa_id = '" . $datosReciboCaja['empresaId'] . "'
      AND tipo_id_tercero = '" . $datosReciboCaja['terceroTipo'] . "'
      AND tercero_id = '" . $datosReciboCaja['terceroId'] . "'; "; */


    return $sql;
}

function sqlUpdateControlAnticipos($datosReciboCaja) {
    $sql = "UPDATE  rc_control_anticipos 
            SET     saldo = saldo + " . $datosReciboCaja['totalAbono'] . " 
            WHERE   empresa_id = '" . $datosReciboCaja['empresaId'] . "'
            AND     tipo_id_tercero = '" . $datosReciboCaja['terceroTipo'] . "'
            AND     tercero_id = '" . $datosReciboCaja['terceroId'] . "'; ";

    return $sql;
}

function sqlControlAnticipos($datosReciboCaja) {
    $sql = "INSERT INTO rc_control_anticipos(empresa_id,
                                             tipo_id_tercero,
                                             tercero_id,
                                             saldo
                                            ) 
                    VALUES ('" . $datosReciboCaja['empresaId'] . "',
                            '" . $datosReciboCaja['terceroTipo'] . "',
                            '" . $datosReciboCaja['terceroId'] . "',
                            '" . $datosReciboCaja['totalAbono'] . "'); ";

    return $sql;
}

function sqlRCDetalle($datosGeneral, $datosReciboCaja) {
    $sql = "INSERT INTO rc_detalles (
            empresa_id, 
            centro_utilidad, 
            recibo_caja, 
            prefijo, 
            valor_actual
            )
            VALUES (
            '" . $datosReciboCaja['empresaId'] . "',
            '" . $datosReciboCaja['centroUtilidad'] . "',
            '" . $datosGeneral['consecutivoFI'] . "',
            '" . $datosGeneral['prefijo'] . "',
            '" . $datosReciboCaja['totalAbono'] . "'
            ); ";

    return $sql;
}

function sqlRcConceptos($datosGeneral, $datosReciboCaja, $datosConceptos) {
    $sql = "";

    //2 o mas conceptos
    if (isset($datosConceptos[0]['conceptoId'])) {
        for ($i = 0; $i < count($datosConceptos); $i++) {
            $sql .= "INSERT INTO rc_detalle_tesoreria_conceptos( 
                    empresa_id, 
                    centro_utilidad, 
                    recibo_caja, 
                    prefijo, 
                    concepto_id, 
                    naturaleza, 
                    departamento, 
                    valor 
                    ) 
                   VALUES (	
                    '" . $datosReciboCaja['empresaId'] . "',
                    '" . $datosReciboCaja['centroUtilidad'] . "',
                    '" . $datosGeneral['consecutivoFI'] . "',
                    '" . $datosGeneral['prefijo'] . "',
                    '" . $datosConceptos[$i]['conceptoId'] . "',
                    '" . $datosConceptos[$i]['naturaleza'] . "',
                    NULL,
                    '" . $datosConceptos[$i]['valor'] . "'
                   ); ";
        }
    } else { //Un Solo concepto
        $sql = "INSERT INTO rc_detalle_tesoreria_conceptos( 
                empresa_id, 
                centro_utilidad, 
                recibo_caja, 
                prefijo, 
                concepto_id, 
                naturaleza, 
                departamento, 
                valor 
		) 
		VALUES (	
		'" . $datosReciboCaja['empresaId'] . "',
		'" . $datosReciboCaja['centroUtilidad'] . "',
		'" . $datosGeneral['consecutivoFI'] . "',
		'" . $datosGeneral['prefijo'] . "',
		'" . $datosConceptos['conceptoId'] . "',
		'" . $datosConceptos['naturaleza'] . "',
		NULL,
		'" . $datosConceptos['valor'] . "'
		); ";
    }

    return $sql;
}

function getRcTipoDocumento($empresaId, $centroUtilidad) {
    //$url = "http://10.0.0.44/SIIS/ws/ws_consultas.php?wsdl";

    global $url;

    $soapClient = new nusoap_client($url, true);
    $function = 'datosTiposDocumentos';
    $inputs = array('empresaId' => $empresaId,
        'centroUtilidad' => $centroUtilidad);
    $result = $soapClient->call($function, $inputs);

    if ($result['estado'] == false) {
        return array('rcTipoDocumento' => 'null',
            'estado' => (bool) '0');
    }

    return array('rcTipoDocumento' => $result['descripcion'],
        'estado' => (bool) '1');
}

function getCaja($empresaId, $centroUtilidad) {
    //$url = "http://10.0.0.44/SIIS/ws/ws_consultas.php?wsdl";

    global $url;

    $soapClient = new nusoap_client($url, true);
    $function = 'datosCaja';
    $inputs = array('empresaId' => $empresaId,
        'centroUtilidad' => $centroUtilidad);
    $result = $soapClient->call($function, $inputs);

    if ($result['estado'] == false) {
        return array('cajaId' => 'null',
            'estado' => (bool) '0');
    }

    return array('cajaId' => $result['descripcion'],
        'estado' => (bool) '1');
}

function validacionesDatos($datosGeneral, $datosReciboCaja, $datosConceptos, $datosCheque, $datosConsignacion, $datosTCredito, $datosTDebito) {
    //Validacion de datosGeneral -Todos los campos obligatorios-
    foreach ($datosGeneral as $camposGeneral => $valoresGeneral) {
        if (empty($valoresGeneral) || trim($valoresGeneral) == '') {

            return array('descripcion' => "El campo, de 'datosGeneral', " . $camposGeneral . " se encuentra vacio. Todos los campos son requeridos",
                'estado' => (bool) '0');
        }
    }

    //Validacion de datosReciboCaja -Todos los campos obligatorios-
    foreach ($datosReciboCaja as $camposReciboCaja => $valoresReciboCaja) {
        if (strlen($valoresReciboCaja) < 1) {
            return array('descripcion' => "El campo, de 'datosReciboCaja', " . $camposReciboCaja . " se encuentra vacio. Todos los campos son requeridos",
                'estado' => (bool) '0');
        }
    }

    //Validacion de datosConceptos
    if (isset($datosConceptos[0]['conceptoId'])) {
        foreach ($datosConceptos as $posicionConceptos) {
            foreach ($posicionConceptos as $camposConceptos => $valoresConceptos) {
                if (strlen($valoresConceptos) < 1) {
                    return array('descripcion' => "El campo, de 'datosConceptos', " . $camposConceptos . " se encuentra vacio. Todos los campos son requeridos",
                        'estado' => (bool) '0');
                }
            }
        }
    } else {
        foreach ($datosConceptos as $camposConceptos => $valoresConceptos) {
            if (strlen($valoresConceptos) < 1) {
                return array('descripcion' => "El campo, de 'datosConceptos', " . $camposConceptos . " se encuentra vacio. Todos los campos son requeridos",
                    'estado' => (bool) '0');
            }
        }
    }

    //Validacion de Forma de Pago (Cheque,Consignacion,Tarjeta Credito y Tarjeta Debito)
    switch ($datosGeneral['tipoFormaPago']) {
        case 'EF': $retornoValidacion = validarEfectivo();
            break;

        case 'CH': $retornoValidacion = validarCheque($datosCheque);
            break;

        case 'CO': $retornoValidacion = validarConsignacion($datosConsignacion);
            break;

        case 'TC': $retornoValidacion = validarTarjetaCredito($datosTCredito);
            break;

        case 'TD': $retornoValidacion = validarTarjetaDebito($datosTDebito);
            break;

        default: $retornoValidacion = array('campoVacio' => 'tipoFormaPago', 'formaPagoVacio' => 'datosGeneral', 'estado' => (bool) '0');
            break;
    }

    if ($retornoValidacion['estado'] == false) {
        return array('descripcion' => "El campo, de '" . $retornoValidacion['formaPagoVacio'] . "', " . $retornoValidacion['campoVacio'] . " se encuentra vacio o no es valido (permitidos: EF,CH,CO,TC,TD). Todos los campos son requeridos",
            'estado' => (bool) '0');
    }

    //Valida si existe el tercero
    $validacionTercero = validarTercero($datosReciboCaja);

    if ($validacionTercero == false) {
        return array('descripcion' => "El Tercero no se encuentra creado",
            'estado' => (bool) '0');
    }

    //Valida si existe el recibo de caja -Si existe, no puede ser creado; de lo contrario si-
    $validacionReciboCaja = validarReciboCaja($datosGeneral['prefijo'], $datosGeneral['consecutivoFI'], $datosReciboCaja['centroUtilidad'], $datosGeneral['empresaId']);

    if ($validacionReciboCaja == false) {
        return array('descripcion' => "El Recibo de caja ya se encuentra creado",
            'estado' => (bool) '0');
    }

    //Si todas las validaciones han pasado retorna mensaje de exito
    return array('descripcion' => "Los campos estan completos. Bien pelado !!!!",
        'estado' => (bool) '1');
}

function validarReciboCaja($prefijo, $consecutivoId, $centroUtilidad, $empresaId) {
    //$url = "http://10.0.0.44/SIIS/ws/ws_consultas.php?wsdl";

    global $url;

    $soapClient = new nusoap_client($url, true);
    $function = 'datosRecibosCaja';
    $inputs = array('prefijo' => $prefijo,
        'consecutivoId' => $consecutivoId,
        'centroUtilidad' => $centroUtilidad,
        'empresaId' => $empresaId);
    $result = $soapClient->call($function, $inputs);

    if ($result['estado'] == false) {
        return (bool) '0';
    }

    return (bool) '1';
}

function validarEfectivo() {
    return array('campoVacio' => "Todos los campos completos",
        'formaPagoVacio' => 'datosEfectivo',
        'estado' => (bool) '1');
}

function validarCheque($datosCheque) {
    foreach ($datosCheque as $camposCheque => $valoresCheque) {
        if (strlen($valoresCheque) < 1) {
            return array('campoVacio' => $camposCheque,
                'formaPagoVacio' => 'datosCheque',
                'estado' => (bool) '0');
        }
    }

    return array('campoVacio' => "Todos los campos completos",
        'formaPagoVacio' => 'datosCheque',
        'estado' => (bool) '1');
}

function validarConsignacion($datosConsignacion) {
    foreach ($datosConsignacion as $camposConsignacion => $valoresConsignacion) {
        if (strlen($valoresConsignacion) < 1 && $camposConsignacion != 'numeroTransaccion') {
            return array('campoVacio' => $camposConsignacion,
                'formaPagoVacio' => 'datosConsignacion',
                'estado' => (bool) '0');
        }
    }

    return array('campoVacio' => "Todos los campos completos",
        'formaPagoVacio' => 'datosConsignacion',
        'estado' => (bool) '1');
}

function validarTarjetaCredito($datosTCredito) {
    foreach ($datosTCredito as $camposTCredito => $valoresTCredito) {
        if (strlen($valoresTCredito) < 1) {
            return array('campoVacio' => $camposTCredito,
                'formaPagoVacio' => 'datosTCredito',
                'estado' => (bool) '0');
        }
    }

    return array('campoVacio' => "Todos los campos completos",
        'formaPagoVacio' => 'datosTCredito',
        'estado' => (bool) '1');
}

function validarTarjetaDebito($datosTDebito) {
    foreach ($datosTDebito as $camposTDebito => $valoresTDebito) {
        if (strlen($valoresTDebito) < 1) {
            return array('campoVacio' => $camposTDebito,
                'formaPagoVacio' => 'datosTDebito',
                'estado' => (bool) '0');
        }
    }

    return array('campoVacio' => "Todos los campos completos",
        'formaPagoVacio' => 'datosTDebito',
        'estado' => (bool) '1');
}

function validarTercero($datosReciboCaja) {
    //$url = "http://10.0.0.44/SIIS/ws/ws_consultas.php?wsdl";

    global $url;

    $soapClient = new nusoap_client($url, true);
    $function = 'datosTercero';
    $inputs = array('tipoTercero' => $datosReciboCaja['terceroTipo'],
        'idTercero' => $datosReciboCaja['terceroId']);
    $result = $soapClient->call($function, $inputs);

    if ($result['estado'] == false) {
        return (bool) '0';
    }

    return (bool) '1';
}

function validarControlAnticipos($datosReciboCaja) {

    global $url;

    $soapClient = new nusoap_client($url, true);
    $function = 'datosControlAnticipos';
    $inputs = array('empresaID' => $datosReciboCaja['empresaId'],
        'tipoTercero' => $datosReciboCaja['terceroTipo'],
        'terceroId' => $datosReciboCaja['terceroId']);
    $result = $soapClient->call($function, $inputs);

    if ($result['estado'] == false) {
        //return (bool) '0';
        return false;
    }
    //return (bool) '1';
    return true;
}

//INVOCA EL SERVICIO
if (isset($HTTP_RAW_POST_DATA)) {
    $input = $HTTP_RAW_POST_DATA;
} else {
    $input = implode("rn", file('php://input'));
}
$server->service($input);
?>