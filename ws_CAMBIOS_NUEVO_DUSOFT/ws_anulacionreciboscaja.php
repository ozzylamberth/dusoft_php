<?php

/*
 * Fecha Creacion: 02-V-2014
 * Creado Por: Steven H. Gamboa
 *
 * Descripcion General: Web Service que permite Anular un Recibo de Caja/Tesoreria en la BD -Cosmitet, Dumian-. 
 * 					   Consumo principal para FI
 *
 */

require_once('../nusoap/lib/nusoap.php');
$server = new nusoap_server;
$server->configureWSDL('AnularRecibosCajaWs', 'urn:anularRecibosCaja_ws');


//Inputs
$server->register('anularReciboCaja', array('empresaId' => 'xsd:string',
    'centroUtilidad' => 'xsd:string',
    'reciboCajaId' => 'xsd:int',
    'prefijoReciboCaja' => 'xsd:string'
        ), array('return' => 'tns:WSResultado'), "urn:anularRecibosCaja_ws", "urn:anularRecibosCaja_ws#anularReciboCaja", "rpc", "encoded", "Webservice: permite anular recibos de caja-tesoreria desde FI a la BD -Cosmitet,Dumian-");

//Ouput
$server->wsdl->addComplexType('WSResultado', 'complexType', 'struct', 'all', '', array('descripcion' => array('name' => 'descripcion', 'type' => 'xsd:string'),
    'estado' => array('name' => 'estado', 'type' => 'xsd:boolean')
        )
);

function anularReciboCaja($empresaId, $centroUtilidad, $reciboCajaId, $prefijoReciboCaja)
{
    global $conexion;
    /*return array('descripcion' => $empresaId . " " . $centroUtilidad. " " . $reciboCajaId . " " . $prefijoReciboCaja,
            'estado' => (bool) '0');*/ 
    //Validaciones, todos los campos deben estar completos
    if (empty($empresaId))
    {
        return array('descripcion' => "El campo empresaId es obligatorio",
            'estado' => (bool) '0');
    }
    elseif (empty($centroUtilidad))
    {
        return array('descripcion' => "El campo centroUtilidad es obligatorio",
            'estado' => (bool) '0');
    }
    elseif (empty($reciboCajaId) || !is_numeric($reciboCajaId))
    {
        return array('descripcion' => "El campo reciboCajaId es obligatorio y es numerico",
            'estado' => (bool) '0');
    }
    elseif (empty($prefijoReciboCaja))
    {
        return array('descripcion' => "El campo prefijoReciboCaja es obligatorio",
            'estado' => (bool) '0');
    }

    $sqlUpdateEstadoReciboCaja = sqlUpdateEstadoReciboCaja($empresaId, $centroUtilidad, $reciboCajaId, $prefijoReciboCaja);
    $saldoRC = getSaldoRecibosCaja($empresaId, $centroUtilidad, $reciboCajaId, $prefijoReciboCaja);
    $dataTerceroReciboCaja = getTerceroReciboCaja($empresaId, $centroUtilidad, $reciboCajaId, $prefijoReciboCaja);
    if (empty($dataTerceroReciboCaja['tipoTercero']))
    {
        return array('descripcion' => "El tipo tercero no se encontro." ,
            'estado' => (bool) '0');
    }
    elseif (empty($dataTerceroReciboCaja['terceroId']))
    {
        return array('descripcion' => "El tercero id no se encontro",
            'estado' => (bool) '0');
    }
    $sqlUpdateSaldoRcControlAnticipos = sqlUpdateSaldoRcControlAnticipos($saldoRC['saldoRC'], $empresaId, $dataTerceroReciboCaja['tipoTercero'], $dataTerceroReciboCaja['terceroId']);
    $sql = " ";
    $sql .= "  " . $sqlUpdateEstadoReciboCaja . " " . $sqlUpdateSaldoRcControlAnticipos . "  ";

    $result = pg_query($conexion, $sql);

    if ($result)
    {
        return array('descripcion' => "Datos guardados correctamente. El recibo de caja Fue Anulado",
            'estado' => (bool) '1');
    }
    else
    {
        return array('descripcion' => "Se genero un error en el update al anular el recibo de caja" .  $sql,
            'estado' => (bool) '0');
    }
}

function getTerceroReciboCaja($empresaId, $centroUtilidad, $reciboCajaId, $prefijoReciboCaja)
{
    $url = "http://10.0.2.170/Pruebas_DUSOFT_DUANA_/ws/ws_consultas.php?wsdl";
    $soapClient = new nusoap_client($url, true);
    $function = 'datosTerceroRecibosCaja';
    $inputs = array('prefijo' => $prefijoReciboCaja,
        'reciboCajaId' => $reciboCajaId,
        'centroUtilidad' => $centroUtilidad,
        'empresaId' => $empresaId);
    $result = $soapClient->call($function, $inputs);

    if ($result['estado'] == false || $result['estado'] == 0)
    {
        return array('descripcion' => 'La consulta al recibo de caja no arrojo resultados',
            'tipoTercero' => '',
            'terceroId' => '',
            'estado' => (bool) '0',
            'debug' => $result['debug']
            );
    }

    return array('descripcion' => $result['descripcion'],
        'tipoTercero' => $result['tipoTercero'],
        'terceroId' => $result['terceroId'],
        'estado' => (bool) '1');
}

function getSaldoRecibosCaja($empresaId, $centroUtilidad, $reciboCajaId, $prefijoReciboCaja)
{
    $url = "http://10.0.2.170/Pruebas_DUSOFT_DUANA_/ws/ws_consultas.php?wsdl";
    $soapClient = new nusoap_client($url, true);
    $function = 'datosSaldoRC';
    $inputs = array('empresaId' => $empresaId,
        'centroUtilidad' => $centroUtilidad,
        'reciboCajaId' => $reciboCajaId,
        'prefijoReciboCaja' => $prefijoReciboCaja);
    $result = $soapClient->call($function, $inputs);

    if ($result['estado'] == false || $result['estado'] == 0)
    {
        return array('saldoRC' => 'null',
            'estado' => (bool) '0');
    }

    return array('saldoRC' => $result['descripcion'],
        'estado' => (bool) '1');
}

function sqlUpdateEstadoReciboCaja($empresaId, $centroUtilidad, $reciboCajaId, $prefijoReciboCaja)
{
    $sql = " ";
    $sql .= "UPDATE recibos_caja 
			 SET	estado = '1' 
			 WHERE	empresa_id = '" . $empresaId . "' 
			 AND	centro_utilidad = '" . $centroUtilidad . "' 
			 AND	recibo_caja = '" . $reciboCajaId . "' 
			 AND	prefijo = '" . $prefijoReciboCaja . "'; ";

    return $sql;
}

function sqlUpdateSaldoRcControlAnticipos($saldo, $empresaId, $tipoTercero, $terceroId)
{
    $sql = " ";
    $sql .= "UPDATE	rc_control_anticipos 
			 SET	saldo = (saldo - " . $saldo . ") 
			 WHERE 	empresa_id = '" . $empresaId . "' 
			 AND	tipo_id_tercero = '" . $tipoTercero . "' 
			 AND	tercero_id = '" . $terceroId . "'; ";

    return $sql;
}

//INVOCA EL SERVICIO
if (isset($HTTP_RAW_POST_DATA))
{
    $input = $HTTP_RAW_POST_DATA;
}
else
{
    $input = implode("rn", file('php://input'));
}
$server->service($input);
?>