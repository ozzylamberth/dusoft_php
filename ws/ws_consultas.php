<?php

/*
 * Fecha Creacion: 21-I-2014
 * Creado Por: Steven H. Gamboa
 *
 * Descripcion General: Web Service que permite diferentes consultas a la BD (Cosmitet, Dumian). 
 * 					   Consumo principal para FI
 *
 */
//http://10.0.1.80/desarrollo/DUMIAN_COPIA/ws/ws_consultas.php?wsdl
require_once('../nusoap/lib/nusoap.php');
require_once ("codificacion_productos/conexionpg.php");

$server = new nusoap_server;
$server->configureWSDL('ConsultasWs', 'urn:consultas_ws');


/* Trae documento_id,prefijo,empresa_id de tabla documentos, como input el prefijo de FI */

//Ouput
$server->wsdl->addComplexType('WSResultado', 'complexType', 'struct', 'all', '', array('documento' => array('name' => 'documento', 'type' => 'xsd:string'),
    'prefijo' => array('name' => 'prefijo', 'type' => 'xsd:string'),
    'empresa' => array('name' => 'empresa', 'type' => 'xsd:string'),
    'estado' => array('name' => 'estado', 'type' => 'xsd:boolean')
        )
);

//Registro de funcion, Input y Output
$server->register('datosDocumentos', array('prefijoFI' => 'xsd:string'), array('return' => 'tns:WSResultado'), "urn:consultas_ws", "urn:consultas_ws#datosDocumentos", "rpc", "encoded", "Metodo-Funcion para traer datos de documentos");

function datosDocumentos($prefijoFI) {

    global $conexion;


    $sql = "   select b.documento_id, b.prefijo, b.empresa_id   
                from prefijos_financiero  a
                inner join documentos b on a.id = b.prefijos_financiero_id
                where a.prefijo = '{$prefijoFI}'";

    $result = pg_query($conexion, $sql);

    if ($row = pg_fetch_array($result)) {
        return array('documento' => $row['documento_id'],
            'prefijo' => $row['prefijo'],
            'empresa' => $row['empresa_id'],
            'estado' => (bool) '1');
    } else {
        return array('documento' => 'null',
            'prefijo' => "{$conexion}",
            'empresa' => 'null',
            'estado' => (bool) '0');
    }
}

/* Trae el Id y Tipo del tercero asociado al recibo de caja */
//Ouput
$server->wsdl->addComplexType('WSResultadoTerceroReciboCaja', 'complexType', 'struct', 'all', '', array('descripcion' => array('name' => 'descripcion', 'type' => 'xsd:string'), 'tipoTercero' => array('name' => 'tipoTercero', 'type' => 'xsd:string'), 'terceroId' => array('name' => 'terceroId', 'type' => 'xsd:string'), 'estado' => array('name' => 'estado', 'type' => 'xsd:boolean')));


//Registro de funcion, Input y Output
$server->register('datosTerceroRecibosCaja', array('prefijo' => 'xsd:string', 'reciboCajaId' => 'xsd:string', 'centroUtilidad' => 'xsd:string', 'empresaId' => 'xsd:string'), 
        array('return' => 'tns:WSResultadoTerceroReciboCaja'), "urn:consultas_ws", "urn:consultas_ws#datosTerceroRecibosCaja", "rpc", "encoded", "Webservice: Metodo-Funcion para para confirmar si el recibo existe o no");

function datosTerceroRecibosCaja($prefijo, $reciboCajaId, $centroUtilidad, $empresaId)
{
     global $conexion;
    $sql = "SELECT 	tipo_id_tercero, 
					tercero_id 
			FROM 	recibos_caja 
			WHERE 	recibo_caja = '" . $reciboCajaId . "' 
			AND		prefijo = '" . $prefijo . "'
			AND 	centro_utilidad='" . $centroUtilidad . "' 
			AND 	empresa_id = '" . $empresaId . "'; ";

    $result = pg_query($conexion, $sql);
    

    if ($row = pg_fetch_array($result))
    {
        return array('descripcion' => "El recibo existe", 'tipoTercero' => $row['tipo_id_tercero'], 'terceroId' => $row['tercero_id'], 'estado' => (bool) '1');
    }
    else
    {
        return array('descripcion' => 'El recibo no existe', 'tipoTercero' => '', 'terceroId' => '', 'estado' => (bool) '0', 'debug' => $sql);
    }
}

/* Trae centro_utilidad de tabla departamentos, como input centro_utilidad_fi,centro_costo_fi */

//Ouput
$server->wsdl->addComplexType('WSResultadoCentroUtilidad', 'complexType', 'struct', 'all', '', array('centro_utilidad' => array('name' => 'centro_utilidad', 'type' => 'xsd:string'),
    'estado' => array('name' => 'estado', 'type' => 'xsd:boolean')
        )
);

//Registro de funcion, Input y Output
$server->register('centroUtilidad', array('centroCostoFI' => 'xsd:string'), array('return' => 'tns:WSResultadoCentroUtilidad'), "urn:consultas_ws", "urn:consultas_ws#centroUtilidad", "rpc", "encoded", "Metodo-Funcion para traer el centro de utilidad");

function centroUtilidad($centroCosto) {

    global $conexion;

    $sql = "SELECT centro_utilidad FROM departamentos WHERE centro_costo_fi = '{$centroCosto}' LIMIT 1; ";

    $result = pg_query($conexion, $sql);

    if ($row = pg_fetch_array($result)) {
        return array('centro_utilidad' => $row['centro_utilidad'],
            'estado' => (bool) '1');
    } else {
        return array('centro_utilidad' => 'null',
            'estado' => (bool) '0');
    }
}

/* Confirma si el tercero existe en la tabla terceros en la BD -Cosmitet,Dumian- */

//Ouput
$server->wsdl->addComplexType('WSResultadoTercero', 'complexType', 'struct', 'all', '', array('descripcion' => array('name' => 'descripcion', 'type' => 'xsd:string'),
    'estado' => array('name' => 'estado', 'type' => 'xsd:boolean')
        )
);

//Registro de funcion, Input y Output
$server->register('datosTercero', array('tipoTercero' => 'xsd:string', 'idTercero' => 'xsd:string'), array('return' => 'tns:WSResultadoTercero'), "urn:consultas_ws", "urn:consultas_ws#datosTercero", "rpc", "encoded", "Metodo-Funcion para confirmar si el tercero existe");

function datosTercero($tipoTercero, $idTercero) {

    global $conexion;


    $sql = "SELECT nombre_tercero FROM terceros WHERE tipo_id_tercero = '{$tipoTercero}' AND tercero_id = '{$idTercero}';";
	file_put_contents('ws.log', $sql);

    $result = pg_query($conexion, $sql);

    if ($row = pg_fetch_array($result)) {
        return array('descripcion' => "El tercero existe. Nombre Tercero: " . $row['nombre_tercero'],
            'estado' => (bool) '1');
    } else {
        return array('descripcion' => 'El tercero no se encuentra registrado en la BD.',
            'estado' => (bool) '0');
    }
}

if (!function_exists('file_put_contents')) {
    function file_put_contents($filename, $data) {
        $f = @fopen($filename, 'w');
        if (!$f) {
            return false;
        } else {
            $bytes = fwrite($f, $data);
            fclose($f);
            return $bytes;
        }
    }
}

/* Trae la caja_id de la tabla cajas */

//Ouput
$server->wsdl->addComplexType('WSResultadoCaja', 'complexType', 'struct', 'all', '', array('descripcion' => array('name' => 'descripcion', 'type' => 'xsd:string'),
    'estado' => array('name' => 'estado', 'type' => 'xsd:boolean')
        )
);

//Registro de funcion, Input y Output
$server->register('datosCaja', array('empresaId' => 'xsd:string'), array('return' => 'tns:WSResultadoCaja'), "urn:consultas_ws", "urn:consultas_ws#datosCaja", "rpc", "encoded", "Metodo-Funcion para traer el id de la Caja");

function datosCaja($empresaId) {

    global $conexion;

    $sql = "SELECT caja_id FROM	cajas WHERE empresa_id = '{$empresaId}' AND cuenta_tipo_id = '07'; ";

    $result = pg_query($conexion, $sql);

    if (pg_num_rows($result) > 1) {
        return array('descripcion' => '2',
            'estado' => (bool) '1');
    } else {
        if ($row = pg_fetch_array($result)) {
            return array('descripcion' => $row['caja_id'],
                'estado' => (bool) '1');
        } else {
            return array('descripcion' => 'null',
                'estado' => (bool) '0');
        }
    }
}

/* Trae el rc_tipo_documento de la tabla rc_tipos_documentos */
//Ouput
$server->wsdl->addComplexType('WSResultadoTiposDocumentos', 'complexType', 'struct', 'all', '', array('descripcion' => array('name' => 'descripcion', 'type' => 'xsd:string'),
    'estado' => array('name' => 'estado', 'type' => 'xsd:boolean')
        )
);

//Registro de funcion, Input y Output
$server->register('datosTiposDocumentos', array('empresaId' => 'xsd:string'), array('return' => 'tns:WSResultadoTiposDocumentos'), "urn:consultas_ws", "urn:consultas_ws#datosTiposDocumentos", "rpc", "encoded", "Metodo-Funcion para traer el id del tipo de documento");

function datosTiposDocumentos($empresaId) {

    global $conexion;

    //$sql = "SELECT rc_tipo_documento FROM rc_tipos_documentos WHERE descripcion ILIKE 'PAGO DE FACTURAS%' AND empresa_id = '{$empresaId}'; ";

    $sql = "SELECT rc_tipo_documento FROM rc_tipos_documentos WHERE descripcion ILIKE 'PAGO FACTURAS%' AND empresa_id = '{$empresaId}'; ";

    $result = pg_query($conexion, $sql);

    if (pg_num_rows($result) > 1) {
        return array('descripcion' => '3',
            'estado' => (bool) '1');
    } else {
        if ($row = pg_fetch_array($result)) {
            return array('descripcion' => $row['rc_tipo_documento'],
                'estado' => (bool) '1');
        } else {
            return array('descripcion' => 'null',
                'estado' => (bool) '0');
        }
    }
}

/* Confirma si el recibo de caja existe o no en la base de datos -Cosmitet, Dumian- */
//Ouput
$server->wsdl->addComplexType('WSResultadoReciboCaja', 'complexType', 'struct', 'all', '', array('descripcion' => array('name' => 'descripcion', 'type' => 'xsd:string'),
    'estado' => array('name' => 'estado', 'type' => 'xsd:boolean')
        )
);

//Registro de funcion, Input y Output
$server->register('datosRecibosCaja', array('prefijo' => 'xsd:string', 'consecutivoId' => 'xsd:string', 'centroUtilidad' => 'xsd:string', 'empresaId' => 'xsd:string'), array('return' => 'tns:WSResultadoReciboCaja'), "urn:consultas_ws", "urn:consultas_ws#datosRecibosCaja", "rpc", "encoded", "Metodo-Funcion para para confirmar si el recibo existe o no");

function datosRecibosCaja($prefijo, $consecutivoId, $centroUtilidad, $empresaId) {

    global $conexion;

    $sql = "SELECT recibo_caja FROM recibos_caja WHERE 	recibo_caja = '{$consecutivoId}' AND prefijo = '{$prefijo}' AND centro_utilidad='{$centroUtilidad}' AND empresa_id = '{$empresaId}'; ";

    $result = pg_query($conexion, $sql);

    if ($row = pg_fetch_array($result)) {
        return array('descripcion' => "El recibo existe. No puede ser creado de nuevo",
            'estado' => (bool) '0');
    } else {
        return array('descripcion' => 'El recibo no existe. Puede ser usado',
            'estado' => (bool) '1');
    }
}

/* Trae el codigo del banco de la bd -Cosmitet,Dumian- */
//Ouput
$server->wsdl->addComplexType('WSResultadoBancos', 'complexType', 'struct', 'all', '', array('descripcion' => array('name' => 'descripcion', 'type' => 'xsd:string'),
    'estado' => array('name' => 'estado', 'type' => 'xsd:boolean')
        )
);
//Registro de funcion, Input y Output
$server->register('datosBancos', array('bancoIdFi' => 'xsd:string'), array('return' => 'tns:WSResultadoBancos'), "urn:consultas_ws", "urn:consultas_ws#datosBancos", "rpc", "encoded", "Metodo-Funcion para traer el id del tipo de documento");

function datosBancos($bancoIdFi) {

    global $conexion;

    $sql = "SELECT banco FROM bancos WHERE banco_fi = '{$bancoIdFi}'; ";

    $result = pg_query($conexion, $sql);

    if ($row = pg_fetch_array($result)) {
        return array('descripcion' => $row['banco'],
            'estado' => (bool) '1');
    } else {
        return array('descripcion' => 'El Banco no esta creado',
            'estado' => (bool) '0');
    }
}

/* Trae datos de la tabla rc_control_anticipos */
//Ouput
$server->wsdl->addComplexType('WSResultadoControlAnticipos', 'complexType', 'struct', 'all', '', array('descripcion' => array('name' => 'descripcion', 'type' => 'xsd:string'),
    'estado' => array('name' => 'estado', 'type' => 'xsd:boolean')
        )
);
//Registro de funcion, Input y Output
$server->register('datosControlAnticipos', array('empresaID' => 'xsd:string', 'tipoTercero' => 'xsd:string', 'terceroId' => 'xsd:string'), array('return' => 'tns:WSResultadoControlAnticipos'), "urn:consultas_ws", "urn:consultas_ws#datosControlAnticipos", "rpc", "encoded", "Metodo-Funcion para validar si existe el registro en la tabla rc_control_anticipos");

function datosControlAnticipos($empresaId, $tipoTercero, $terceroId) {
    
    global $conexion;

    $sql = "SELECT tercero_id FROM rc_control_anticipos  WHERE empresa_id = '{$empresaId}'  AND tipo_id_tercero = '{$tipoTercero}' AND tercero_id = '{$terceroId}' ; ";

    $result = pg_query($conexion, $sql);

    if ($row = pg_fetch_array($result)) {
        return array('descripcion' => $row['tercero_id'],
            'estado' => (bool) '1');
    } else {
        return array('descripcion' => 'El registro no esta creado',
            'estado' => (bool) '0');
    }
}


/* Trae el saldo de la tabla recibos_caja */
//Ouput
$server->wsdl->addComplexType('WSResultadoSaldoRC', 'complexType', 'struct', 'all', '', array('descripcion' => array('name' => 'descripcion', 'type' => 'xsd:string'), 'estado' => array('name' => 'estado', 'type' => 'xsd:boolean')));
//Registro de funcion, Input y Output
$server->register('datosSaldoRC', array('empresaId' => 'xsd:string', 'centroUtilidad' => 'xsd:string', 'reciboCajaId' => 'xsd:int', 'prefijoReciboCaja' => 'xsd:string'), array('return' => 'tns:WSResultadoSaldoRC'), "urn:consultas_ws", "urn:consultas_ws#datosSaldoRC", "rpc", "encoded", "Metodo-Funcion para traer el saldo del recibos de caja");

function datosSaldoRC($empresaId, $centroUtilidad, $reciboCajaId, $prefijoReciboCaja)
{

       global $conexion;
    $sql = "SELECT 	total_abono 
			FROM 	recibos_caja 
			WHERE 	empresa_id = '" . $empresaId . "' 
			AND		centro_utilidad = '" . $centroUtilidad . "' 
			AND		recibo_caja = '" . $reciboCajaId . "' 
			AND		prefijo = '" . $prefijoReciboCaja . "' ; ";

    $result = pg_query($conexion, $sql);

    if ($row = pg_fetch_array($result))
    {
        return array('descripcion' => $row['total_abono'], 'estado' => (bool) '1');
    }
    else
    {
        return array('descripcion' => 'Error al consultar total_abono en recibos_caja', 'estado' => (bool) '0');
    }
}

//INVOCA EL SERVICIO

if (isset($HTTP_RAW_POST_DATA)) {
    $input = $HTTP_RAW_POST_DATA;
} else {
    $input = implode("rn", file('php://input'));
}
$server->service($input);
?>