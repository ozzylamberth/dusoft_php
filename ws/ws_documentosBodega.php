<?php

/*
 *
 * Web Service ws_documentosBodega.php
 *
 * Fecha: 17-VI-2014
 * Autor: Steven H. Gamboa
 *
 * Descripcion: Permite registrar los productos de DUANA (inv_bodegas_movimiento, inv_bodegas_movimiento_d) a COSMITET (en tmp).
 *
 */

require_once('../nusoap/lib/nusoap.php');
$server = new nusoap_server;
$server->configureWSDL('DocumentosBodegaWs', 'urn:documentosBodega_ws');

/* Guarda la cabecera de los productos inv_bodegas_movimiento_tmp */
//Registro de funcion e Inputs
$server->register('bodegasMovimientoTmp', array('usuarioId' => 'xsd:int',
    'bodegasDoc' => 'xsd:string',
    'observacion' => 'xsd:string',
    'documentoId' => 'xsd:string'
        ), array('return' => 'tns:WSResultadoBodegasMovimientoTmp'), "urn:documentosBodega_ws", "urn:documentosBodega_ws#bodegasMovimientoTmp", "rpc", "encoded", "Metodo-Funcion para guardar los datos en inv_bodegas_movimiento_tmp");

//Ouput
$server->wsdl->addComplexType('WSResultadoBodegasMovimientoTmp', 'complexType', 'struct', 'all', '', array('estado' => array('name' => 'estado', 'type' => 'xsd:boolean'),
    'docTmpId' => array('name' => 'docTmpId', 'type' => 'xsd:int'),
    'descripcion' => array('name' => 'descripcion', 'type' => 'xsd:string')
        )
);

function bodegasMovimientoTmp($usuarioId, $bodegasDoc, $observacion, $documentoId) {
    require_once ("conexionpg.php");

    $docTmpId = nextValBodegasMovimientoTmp();



    if (empty($docTmpId)) {
        return array('estado' => (bool) '0',
            'docTmpId' => '0',
            'descripcion' => 'El campo - doc_tmp_id - esta vacio');
    }
    if (empty($usuarioId)) {
        return array('estado' => (bool) '0',
            'docTmpId' => '0',
            'descripcion' => 'El campo - usuarioId - esta vacio');
    }
    if (empty($bodegasDoc)) {
        return array('estado' => (bool) '0',
            'docTmpId' => '0',
            'descripcion' => 'El campo - bodegasDoc - esta vacio');
    }
    $bodegasDocId = getDocumentoBodega($documentoId, $bodegasDoc);

    if ($docTmpId['nextValId'] != 'null') {
        $docTmpId['nextValId'] = $docTmpId['nextValId'];
    }

    if ($bodegasDocId['bodegasDocId'] != 'null') {
        $bodegasDocId['bodegasDocId'] = $bodegasDocId['bodegasDocId'];
    }

    $sql = " INSERT INTO inv_bodegas_movimiento_tmp (	usuario_id,
														doc_tmp_id,
														bodegas_doc_id,
														observacion,
														fecha_registro
													) 
					VALUES ('" . $usuarioId . "',
							" . $docTmpId['nextValId'] . ",
							" . $bodegasDocId['bodegasDocId'] . ",
							'" . $observacion . "',
							now()
							); ";
    logCache($sql);
    $result = pg_query($conexionn, $sql);

    if ($result) {
        return array('descripcion' => "Datos guardados correctamente",
            'docTmpId' => $docTmpId['nextValId'],
            'estado' => (bool) '1');
    } else {
        return array('descripcion' => "Se genero un error al guardar los datos en inv_bodegas_movimiento_tmp",
            'docTmpId' => '0',
            'estado' => (bool) '0');
    }
}

function getDocumentoBodega($documentoId, $bodega) {
    logCache("$documentoId - $bodega");

    $url = "http://10.0.0.3/pg9/larry.sanchez/asistencial/ws/ws_consultasDocumentosBodega.php?wsdl";
    $soapClient = new nusoap_client($url, true);
    $function = 'dataInvBodegasDocumentos';
    $inputs = array('documentoId' => $documentoId,
        'bodega' => $bodega);
    $result = $soapClient->call($function, $inputs);

    if ($result['estado'] == false) {
        return array('bodegasDocId' => 'null',
            'estado' => (bool) '0');
    }

    return array('bodegasDocId' => $result['bodegasDocId'],
        'estado' => (bool) '1');
}

function nextValBodegasMovimientoTmp() {
    $url = "http://10.0.0.3/pg9/larry.sanchez/asistencial/ws/ws_consultasDocumentosBodega.php?wsdl";
    $soapClient = new nusoap_client($url, true);
    $function = 'dataNextValIdCabecera';
    $inputs = array();
    $result = $soapClient->call($function, $inputs);

    if ($result['estado'] == false) {
        return array('nextValId' => 'null',
            'estado' => (bool) '0');
    }

    return array('nextValId' => $result['nextValId'],
        'estado' => (bool) '1');
}

/* Guarda el Detalle de los productos inv_bodegas_movimiento_tmp_d */
//Registro de funcion e Inputs
$server->register('bodegasMovimientoTmpD', array('usuarioId' => 'xsd:int',
    'docTmpId' => 'xsd:int',
    'tipoTercero' => 'xsd:string',
    'terceroId' => 'xsd:string',
    'documentoCompra' => 'xsd:string',
    'fechaDocCompra' => 'xsd:string',
    'codigoProducto' => 'xsd:string',
    'cantidad' => 'xsd:float',
    'porcentajeGravamen' => 'xsd:float',
    'totalCosto' => 'xsd:float',
    'fechaVencimiento' => 'xsd:string',
    'lote' => 'xsd:string',
    'localizacionProducto' => 'xsd:string',
    'totalcostoPedido' => 'xsd:float',
    'valorUnitario' => 'xsd:float',
    'descuento' => 'xsd:float'
        ), array('return' => 'tns:WSResultadoBodegasMovimientoTmpD'), "urn:documentosBodega_ws", "urn:documentosBodega_ws#bodegasMovimientoTmpD", "rpc", "encoded", "Metodo-Funcion para guardar los datos en inv_bodegas_movimiento_tmp_d");

//Ouput
$server->wsdl->addComplexType('WSResultadoBodegasMovimientoTmpD', 'complexType', 'struct', 'all', '', array('estado' => array('name' => 'estado', 'type' => 'xsd:boolean'),
    'descripcion' => array('name' => 'descripcion', 'type' => 'xsd:string')
        )
);

function bodegasMovimientoTmpD($usuarioId, $docTmpId, $tipoTercero, $terceroId, $documentoCompra, $fechaDocCompra, $codigoProducto, $cantidad, $porcentajeGravamen, $totalCosto, $fechaVencimiento, $lote, $localizacionProducto, $totalcostoPedido, $valorUnitario, $descuento) {
    require_once ("conexionpg.php");

    $itemId = nextValBodegasMovimientoTmpD();
    if (empty($itemId)) {
        return array('estado' => (bool) '0',
            'descripcion' => 'El campo - item_id - esta vacio');
    }
    if (strlen($usuarioId) < 1) {
        return array('estado' => (bool) '0',
            'descripcion' => 'El campo - usuarioId - esta vacio');
    }
    if (strlen($docTmpId) < 1) {
        return array('estado' => (bool) '0',
            'descripcion' => 'El campo - docTmpId - esta vacio');
    }
    if (strlen($tipoTercero) < 1) {
        return array('estado' => (bool) '0',
            'descripcion' => 'El campo - tipoTercero - esta vacio');
    }
    if (strlen($terceroId) < 1) {
        return array('estado' => (bool) '0',
            'descripcion' => 'El campo - terceroId - esta vacio');
    }
    if (strlen($documentoCompra) < 1) {
        return array('estado' => (bool) '0',
            'descripcion' => 'El campo - documentoCompra - esta vacio');
    }
    if (strlen($fechaDocCompra) < 1) {
        return array('estado' => (bool) '0',
            'descripcion' => 'El campo - fechaDocCompra - esta vacio');
    }
    if (strlen($codigoProducto) < 1) {
        return array('estado' => (bool) '0',
            'descripcion' => 'El campo - codigoProducto - esta vacio');
    }
    if (strlen($cantidad) < 1) {
        return array('estado' => (bool) '0',
            'descripcion' => 'El campo - cantidad - esta vacio');
    }
    if (strlen($porcentajeGravamen) < 1) {
        return array('estado' => (bool) '0',
            'descripcion' => 'El campo - porcentajeGravamen - esta vacio');
    }
    if (strlen($totalCosto) < 1) {
        return array('estado' => (bool) '0',
            'descripcion' => 'El campo - totalCosto - esta vacio');
    }
    if (strlen($fechaVencimiento) < 1) {
        return array('estado' => (bool) '0',
            'descripcion' => 'El campo - fechaVencimiento - esta vacio');
    }
    if (strlen($lote) < 1) {
        return array('estado' => (bool) '0',
            'descripcion' => 'El campo - lote - esta vacio');
    }
    if (strlen($localizacionProducto) < 1) {
        return array('estado' => (bool) '0',
            'descripcion' => 'El campo - localizacionProducto - esta vacio');
    }
    if (strlen($totalcostoPedido) < 1) {
        return array('estado' => (bool) '0',
            'descripcion' => 'El campo - totalcostoPedido - esta vacio');
    }
    if (strlen($valorUnitario) < 1) {
        return array('estado' => (bool) '0',
            'descripcion' => 'El campo - valorUnitario - esta vacio');
    }
    if (strlen($descuento) < 1) {
        return array('estado' => (bool) '0',
            'descripcion' => 'El campo - descuento - esta vacio');
    }
    $dataDocumentosBodega = getDataBodegasMovimientoTmpD($usuarioId, $docTmpId, $codigoProducto);
    $dataExisteComprasDirectasTmp = getDataComprasDirectasTmp($usuarioId, $docTmpId);

    if (!$dataDocumentosBodega['estado']) {
        return array('estado' => (bool) '0',
            'descripcion' => 'El producto no existe');
    }

    foreach ($dataDocumentosBodega as $key => $value) {
        if (empty($value) || $value == 'null') {
            $dataDocumentosBodega[$key] = 'NULL';
        } else {
            $dataDocumentosBodega[$key] = "'" . $value . "'";
        }
    }

    $sql = "INSERT INTO inv_bodegas_movimiento_tmp_d
                (
                    item_id,
                    usuario_id,
                    doc_tmp_id,
                    empresa_id,
                    centro_utilidad,
                    bodega,
                    codigo_producto,
                    cantidad,
                    porcentaje_gravamen,
                    total_costo,
                    fecha_vencimiento,
                    lote,
                    local_prod,
                    total_costo_pedido,
                    valor_unitario,
                    descuento
                )
                VALUES
                (
                	'" . $itemId['nextValId'] . "',
                	'" . $usuarioId . "',
                	'" . $docTmpId . "',
                	" . $dataDocumentosBodega['empresaId'] . ",
                	" . $dataDocumentosBodega['centroUtilidad'] . ",
                	" . $dataDocumentosBodega['bodega'] . ",
                	" . $dataDocumentosBodega['codigoProducto'] . ",
                	'" . $cantidad . "',
                	'" . $porcentajeGravamen . "',
                	'" . $totalCosto . "',
                	'" . $fechaVencimiento . "',
                	'" . $lote . "',
                	'" . $localizacionProducto . "',
                	'" . $totalcostoPedido . "',
                	'" . $valorUnitario . "',
                	'" . $descuento . "'
                ); ";

    $sql .= " ";
    if ($dataExisteComprasDirectasTmp['estado'] == '1') {
        $sql .= " INSERT INTO inv_bodegas_movimiento_tmp_compras_directas 
				 (
				 	usuario_id,
				 	doc_tmp_id, 
				 	tipo_id_tercero,
				 	tercero_id,
				 	documento_compra,
				 	fecha_doc_compra
				 )
				 VALUES 
				 (
				 	'" . $usuarioId . "',
				 	'" . $docTmpId . "',
				 	'" . $tipoTercero . "',
				 	'" . $terceroId . "',
				 	'" . $documentoCompra . "',
				 	'" . $fechaDocCompra . "'
				 ); ";
    }
    logCache($sql);
    $result = pg_query($conexionn, $sql);

    if ($result) {
        return array('descripcion' => "Datos guardados correctamente",
            'estado' => (bool) '1');
    } else {
        return array('descripcion' => "Se genero un error al guardar los datos en inv_bodegas_movimiento_tmp_d y/o inv_bodegas_movimiento_tmp_compras_directas",
            'estado' => (bool) '0');
    }
}

function getDataBodegasMovimientoTmpD($usuarioId, $docTmpId, $codigoProducto) {
    $url = "http://10.0.0.3/pg9/larry.sanchez/asistencial/ws/ws_consultasDocumentosBodega.php?wsdl";
    $soapClient = new nusoap_client($url, true);
    $function = 'dataDocumentosBodega';
    $inputs = array('usuarioID' => $usuarioId,
        'docTmpId' => $docTmpId,
        'codigoProducto' => $codigoProducto);
    $result = $soapClient->call($function, $inputs);

    if ($result['estado'] == false) {
        return array('estado' => (bool) '0',
            'empresaId' => 'null',
            'centroUtilidad' => 'null',
            'bodega' => 'null',
            'codigoProducto' => 'null'
        );
    }

    return array('estado' => (bool) '1',
        'empresaId' => $result['empresaId'],
        'centroUtilidad' => $result['centroUtilidad'],
        'bodega' => $result['bodega'],
        'codigoProducto' => $result['codigoProducto']
    );
}

function logCache($sql) {
    $file = fopen("../cache/LogBodegas.sql", "a+");
    fwrite($file, $sql . PHP_EOL);
    fclose($file);
}

function nextValBodegasMovimientoTmpD() {
    $url = "http://10.0.0.3/pg9/larry.sanchez/asistencial/ws/ws_consultasDocumentosBodega.php?wsdl";
    $soapClient = new nusoap_client($url, true);
    $function = 'dataNextValIdD';
    $inputs = array();
    $result = $soapClient->call($function, $inputs);

    if ($result['estado'] == false) {
        return array('nextValId' => 'null',
            'estado' => (bool) '0');
    }

    return array('nextValId' => $result['nextValId'],
        'estado' => (bool) '1');
}

function getDataComprasDirectasTmp($usuarioId, $docTmpId) {
    $url = "http://10.0.0.3/pg9/larry.sanchez/asistencial/ws/ws_consultasDocumentosBodega.php?wsdl";
    $soapClient = new nusoap_client($url, true);
    $function = 'dataComprasDirectasTmp';
    $inputs = array('usuarioId' => $usuarioId, 'docTmpId' => $docTmpId);
    $result = $soapClient->call($function, $inputs);

    if ($result['estado'] == false || $result['estado'] == '') {
        return array('descripcion' => $result['descripcion'],
            'estado' => (bool) '0');
    }

    return array('descripcion' => $result['descripcion'],
        'estado' => (bool) '1');
}

if (isset($HTTP_RAW_POST_DATA)) {
    $input = $HTTP_RAW_POST_DATA;
} else {
    $input = implode("rn", file('php://input'));
}
$server->service($input);
?>