<?php

/*
 * Fecha Creacion: 17-VI-2014
 * Creado Por: Steven H. Gamboa
 *
 * Descripcion General: Web Service que permite diferentes consultas a la BD (Cosmitet). 
 * 					   Consumo principal para ws_documentosBodega.php
 *
 */
require_once('../nusoap/lib/nusoap.php');
$server = new nusoap_server;
$server->configureWSDL('ConsultasDocumentosBodegaWs', 'urn:consultasDB_ws');

/* Trae el ultimo de inv_bodegas_movimiento_tmp */
//Registro de funcion, Input y Output
$server->register('dataNextValIdCabecera', array(), array('return' => 'tns:WSResultadoIdCabecera'), "urn:consultasDB_ws", "urn:consultasDB_ws#dataNextValIdCabecera", "rpc", "encoded", "Metodo-Funcion para traer el siguiente valor de la tabla inv_bodegas_movimiento_tmp");

//Ouput
$server->wsdl->addComplexType('WSResultadoIdCabecera', 'complexType', 'struct', 'all', '', array('estado' => array('name' => 'estado', 'type' => 'xsd:boolean'),
    'descripcion' => array('name' => 'descripcion', 'type' => 'xsd:string'),
    'nextValId' => array('name' => 'nextValId', 'type' => 'xsd:int')
        )
);

function dataNextValIdCabecera() {
    require_once ("conexionpg.php");

    $sql = " SELECT (COALESCE(MAX(doc_tmp_id),0) + 1) as next_id FROM inv_bodegas_movimiento_tmp; ";

    $result = pg_query($conexionn, $sql);

    if ($row = pg_fetch_array($result)) {
        return array('estado' => (bool) '1',
            'descripcion' => 'Ultimo id seleccionado',
            'nextValId' => $row['next_id']
        );
    } else {
        return array('estado' => (bool) '0',
            'descripcion' => 'Error al generar la consulta',
            'nextValId' => 'null'
        );
    }
}

/* Trae el ultimo de inv_bodegas_movimiento_tmp_d */
//Registro de funcion, Input y Output
$server->register('dataNextValIdD', array(), array('return' => 'tns:WSResultadoIdDetalle'), "urn:consultasDB_ws", "urn:consultasDB_ws#dataNextValIdD", "rpc", "encoded", "Metodo-Funcion para traer el siguiente valor de la tabla inv_bodegas_movimiento_tmp_d");

//Ouput
$server->wsdl->addComplexType('WSResultadoIdDetalle', 'complexType', 'struct', 'all', '', array('estado' => array('name' => 'estado', 'type' => 'xsd:boolean'),
    'descripcion' => array('name' => 'descripcion', 'type' => 'xsd:string'),
    'nextValId' => array('name' => 'nextValId', 'type' => 'xsd:string')
        )
);

function dataNextValIdD() {
    require_once ("conexionpg.php");

    $sql = " SELECT nextval('inv_bodegas_movimiento_tmp_d_item_id_seq'::regclass) as next_id; ";

    $result = pg_query($conexionn, $sql);

    if ($row = pg_fetch_array($result)) {
        return array('estado' => (bool) '1',
            'descripcion' => 'Ultimo id seleccionado',
            'nextValId' => $row['next_id']
        );
    } else {
        return array('estado' => (bool) '0',
            'descripcion' => 'Error al generar la consulta',
            'nextValId' => 'null'
        );
    }
}

/* Trae la empresa, centro de utilidad, bodega y codigo del producto */
//Registro de funcion, Input y Output
$server->register('dataDocumentosBodega', array('usuarioID' => 'xsd:int',
    'docTmpId' => 'xsd:int',
    'codigoProducto' => 'xsd:string'), array('return' => 'tns:WSResultadoDataDB'), "urn:consultasDB_ws", "urn:consultasDB_ws#dataDocumentosBodega", "rpc", "encoded", "Metodo-Funcion para traer datos para documentos bodega");

//Ouput
$server->wsdl->addComplexType('WSResultadoDataDB', 'complexType', 'struct', 'all', '', array('estado' => array('name' => 'estado', 'type' => 'xsd:boolean'),
    'empresaId' => array('name' => 'empresaId', 'type' => 'xsd:string'),
    'centroUtilidad' => array('name' => 'centroUtilidad', 'type' => 'xsd:string'),
    'bodega' => array('name' => 'bodega', 'type' => 'xsd:string'),
    'codigoProducto' => array('name' => 'codigoProducto', 'type' => 'xsd:string')
        )
);

function dataDocumentosBodega($usuarioID, $docTmpId, $codigoProducto) {
    require_once ("conexionpg.php");

    $sql = "SELECT 	c.empresa_id,
                    c.centro_utilidad,
                    c.bodega,
                    c.codigo_producto
            FROM 		inv_bodegas_movimiento_tmp as a 
            INNER JOIN 	inv_bodegas_documentos as b ON (b.bodegas_doc_id = a.bodegas_doc_id)
			INNER JOIN 	existencias_bodegas as c ON (c.empresa_id = b.empresa_id AND c.centro_utilidad = b.centro_utilidad AND c.bodega = b.bodega)
            WHERE 	a.usuario_id = '" . $usuarioID . "'
                    AND a.doc_tmp_id = '" . $docTmpId . "'
                    AND c.codigo_producto ='" . $codigoProducto . "'; ";

    $result = pg_query($conexionn, $sql);

    if ($row = pg_fetch_array($result)) {
        return array('estado' => (bool) '1',
            'empresaId' => $row['empresa_id'],
            'centroUtilidad' => $row['centro_utilidad'],
            'bodega' => $row['bodega'],
            'codigoProducto' => $row['codigo_producto']
        );
    } else {
        return array('estado' => (bool) '0',
            'empresaId' => 'null',
            'centroUtilidad' => 'null',
            'bodega' => 'null',
            'codigoProducto' => 'null'
        );
    }
}

/* Trae la bodega -bodegas_doc_id- */
//Registro de funcion, Input y Output
$server->register('dataInvBodegasDocumentos', array('documentoId' => 'xsd:string',
    'bodega' => 'xsd:string'), array('return' => 'tns:WSResultadoInvBD'), "urn:consultasDB_ws", "urn:consultasDB_ws#dataInvBodegasDocumentos", "rpc", "encoded", "Metodo-Funcion para traer el documento bodega");

//Ouput
$server->wsdl->addComplexType('WSResultadoInvBD', 'complexType', 'struct', 'all', '', array('estado' => array('name' => 'estado', 'type' => 'xsd:boolean'),
    'bodegasDocId' => array('name' => 'bodegasDocId', 'type' => 'xsd:int')
        )
);

function dataInvBodegasDocumentos($documentoId, $bodega) {
    require_once ("conexionpg.php");

    $sql = "SELECT 	bodegas_doc_id 
			FROM 	inv_bodegas_documentos 
			WHERE 	documento_id = '" . $documentoId . "' 
			AND 	bodega = '" . $bodega . "'; ";
    logCache($sql);
    $result = pg_query($conexionn, $sql);

    if ($row = pg_fetch_array($result)) {
        return array('estado' => (bool) '1',
            'bodegasDocId' => $row['bodegas_doc_id']
        );
    } else {
        return array('estado' => (bool) '0',
            'bodegasDocId' => 'null'
        );
    }
}

/* Verifica si existe en Tabla inv_bodegas_movimiento_tmp_compras_directas */
//Registro de funcion, Input y Output
$server->register('dataComprasDirectasTmp', array('usuarioId' => 'xsd:int',
    'docTmpId' => 'xsd:int'), array('return' => 'tns:WSResultadodataComprasDirectasTmp'), "urn:consultasDB_ws", "urn:consultasDB_ws#dataComprasDirectasTmp", "rpc", "encoded", "Metodo-Funcion para traer el documento bodega");

//Ouput
$server->wsdl->addComplexType('WSResultadodataComprasDirectasTmp', 'complexType', 'struct', 'all', '', array('estado' => array('name' => 'estado', 'type' => 'xsd:boolean'),
    'descripcion' => array('name' => 'descripcion', 'type' => 'xsd:string')
        )
);

function dataComprasDirectasTmp($usuarioId, $docTmpId) {
    require_once ("conexionpg.php");

    $sql = "SELECT 	doc_tmp_id 
			FROM 	inv_bodegas_movimiento_tmp_compras_directas 
			WHERE 	usuario_id = '" . $usuarioId . "' 
			AND 	doc_tmp_id = '" . $docTmpId . "'; ";
    $file = fopen("../cache/Desarrollo.sql", "a+");
    fwrite($file, $sql . PHP_EOL);
    fclose($file);
    $result = pg_query($conexionn, $sql);

    if ($row = pg_fetch_array($result)) {
        return array('estado' => (bool) '0',
            'descripcion' => 'Ya se encuentra creado'
        );
    } else {
        return array('estado' => (bool) '1',
            'descripcion' => 'El registro esta libre para grabar'
        );
    }
}

function logCache($sql) {
    $file = fopen("../cache/LogBodegas.sql", "a+");
    fwrite($file, $sql . PHP_EOL);
    fclose($file);
}

if (isset($HTTP_RAW_POST_DATA)) {
    $input = $HTTP_RAW_POST_DATA;
} else {
    $input = implode("rn", file('php://input'));
}
$server->service($input);
?>