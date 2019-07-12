<?php
/*
function enviarRCWSFISiPago($encabezado,$cuenta_tercero,$cuenta_forma_pago,$documento,$empresa_id)
{
    require_once ('nusoap/lib/nusoap.php');
    $url_wsdl = "http://10.0.6.184:8080/SinergiasFinanciero3-ejb/getGestionInformacionContableWS/getGestionInformacionContableWS?wsdl";
    $soapclient = new nusoap_client($url_wsdl,true);
    $function = "crearInformacionContable";

    //Codigo de Documento Encabezado
    $coddocumentoencabezado = getDocumentoEncabezadoFI($documento,$empresa_id);
    
    if(strlen($encabezado['observacion'])<10)
    {
        $encabezado['observacion'] = "SIN OBSERVACION PARA EL ENCABEZADO";
    }
    else
    {
        $encabezado['observacion'] = $encabezado['observacion'];
    }
    $encabezado_rc = array('coddocumentoencabezado'=>$coddocumentoencabezado['prefijo_fi'],
                            'codempresa'=>'COS',
                            'estadoencabezado'=>'4',
                            'fecharegistroencabezado'=>$encabezado['fecha_registro'],
                            'identerceroencabezado'=>$encabezado['tercero_id'],
                            'numerodocumentoencabezado'=>$encabezado['recibo_caja'],
                            'observacionencabezado'=>$encabezado['observacion'],
                            'usuariocreacion'=>$encabezado['usuario_id']
                           );
    $asiento = array();
    
    //DEBITO
    $asiento[0] = array('codcentrocostoasiento'=>'0',
                           'codcentroutilidadasiento'=>'0',
                           'codcuentaasiento'=>$cuenta_tercero,
                           'codlineacostoasiento'=>'0',
                           'identerceroasiento'=>$encabezado['tercero_id'],
                           'observacionasiento'=>'SIN OBSERVACION PARA EL ASIENTO',
                           'valorbaseasiento'=>'0',
                           'valorcreditoasiento'=>'0',
                           'valordebitoasiento'=>(int)($encabezado['total_abono']),
                           'valortasaasiento'=>'0'
                          );
    
    //CREDITO
    $asiento[1] = array('codcentrocostoasiento'=>'0',
                           'codcentroutilidadasiento'=>'0',
                           'codcuentaasiento'=>$cuenta_forma_pago,
                           'codlineacostoasiento'=>'0',
                           'identerceroasiento'=>$encabezado['tercero_id'],
                           'observacionasiento'=>'SIN OBSERVACION PARA EL ASIENTO',
                           'valorbaseasiento'=>'0',
                           'valorcreditoasiento'=>(int)($encabezado['total_abono']),
                           'valordebitoasiento'=>'0',
                           'valortasaasiento'=>'0'
                          );
    
    $inputs = array('encabezadofactura'=>$encabezado_rc,
                    'asientoscontables'=>$asiento);
    
    $resultado = $soapclient->call($function,$inputs);
    
    return $resultado;
}
*/
function getDocumentoEncabezadoFI($doc_id,$empresa_id)
{
    $sql = "";
    $sql .= "SELECT prefijo_fi 
             FROM   documentos
             WHERE  documento_id = '".$doc_id."' 
             AND    empresa_id = '".$empresa_id."'; ";
    
    if (!$rst = ConexionBaseDatos($sql))
        return false;

    //$datos = array();
    if (!$rst->EOF) {
        $datos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
    }

    $rst->Close();
    return $datos;
}

function getTipoTarjeta($tabla,$prefijo,$id_rc)
{
    $sql = "";
    $sql .= "SELECT *
             FROM   ".$tabla."
             WHERE  prefijo = '".$prefijo."' 
             AND    recibo_caja = '".$id_rc."' ; ";
    
    if (!$rst = ConexionBaseDatos($sql))
        return false;

    //$datos = array();
    while (!$rst->EOF) {
        $datos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
    }

    $rst->Close();
    return $datos;
}

function getCuentaContableFormaPago($id_forma_pago)
{
    $sql = "";
    $sql .= "SELECT cuenta_contable
             FROM   forma_pago_rt
             WHERE  id_forma_pago = '".$id_forma_pago."'; ";
    
    if (!$rst = ConexionBaseDatos($sql))
        return false;

    //$datos = array();
    while (!$rst->EOF) {
        $datos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
    }

    $rst->Close();
    return $datos;
}

function getCuentaContableTercero($tercero)
{
    require_once ('nusoap/lib/nusoap.php');
    $url_wsdl = "http://10.0.6.184:8080/SinergiasFinanciero3-ejb/getTercerosClienteWS/getTercerosClienteWS?wsdl";
    $soapclient = new nusoap_client($url_wsdl,true);
    $function = "buscarTerceroCliente";
    
    $inputs = array('idempresa'=>'1',
                    'numeroidentificacion'=>$tercero);
    
    $resultado = $soapclient->call($function,$inputs);
    
    return $resultado;
}

//Datos para el WS FI
function getEncabezadoRCWSFI($rc_id,$prefijo)
{
    $sql = "";
    $sql .= "   SELECT  RC.*,
                        TO_CHAR(RC.fecha_registro, 'YYYY-MM-DD') as fecha_registro 
                FROM    recibos_caja RC
                WHERE   RC.recibo_caja = '".$rc_id."'
                AND     RC.prefijo = '".$prefijo."'; ";
    if (!$rst = ConexionBaseDatos($sql))
        return false;

    //$datos = array();
    while (!$rst->EOF) {
        $datos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
    }

    $rst->Close();
    return $datos;
}

function getDetalleRCWSFI($rc_id,$prefijo)
{
    $sql = "";
/*	
    $sql .= "   SELECT  RDTF.*,
                        '27052501' AS cuenta_debito,
                        CASE WHEN CXP.cuenta != '' THEN CXP.cuenta ELSE '0' END AS cuenta_credito,
                        '0' AS centro_costo,
                        '0' AS centro_utilidad,
                        '0' AS linea_costo
                FROM    rc_detalle_tesoreria_facturas RDTF 
                LEFT JOIN fac_facturas FF ON (RDTF.prefijo_factura = FF.prefijo AND RDTF.factura_fiscal = FF.factura_fiscal) 
                LEFT JOIN cg_mov_01.cuentas_x_planes CXP ON (CXP.plan_id = FF.plan_id)
                WHERE RDTF.recibo_caja = '".$rc_id."' 
                AND RDTF.prefijo = '".$prefijo."'; ";
				
    $sql .= "   SELECT  DISTINCT RDTF.recibo_caja,
                        RDTF.*,
                        RDTF.valor_detalle as valor_efectivo,
                        RCT.cuenta AS cuenta_credito,
                        '0' AS centro_costo,
                        '0' AS centro_utilidad,
                        '0' AS linea_costo
                FROM    facturas_rc_detalles RDTF 
					LEFT JOIN fac_facturas FF ON (RDTF.prefijo_factura = FF.prefijo AND RDTF.factura_fiscal = FF.factura_fiscal) 
                    LEFT JOIN rc_detalle_tesoreria_conceptos RDTC ON (RDTC.prefijo=RDTF.prefijo_rc AND RDTC.recibo_caja=RDTF.recibo_caja) 
                    LEFT JOIN rc_conceptos_tesoreria RCT ON (RCT.concepto_id = RDTC.concepto_id)
                WHERE RDTF.rc_id_tras = '".$rc_id."' 
                AND RDTF.rc_prefijo_tras = '".$prefijo."'; ";
				
*/
    $sql .= "   SELECT  DISTINCT RDTF.valor_detalle as valor_efectivo,
					RCT1.cuenta AS cuenta_credito,
					'0' AS centro_costo,
					'0' AS centro_utilidad,
					'0' AS linea_costo,
					RCT.cuenta as cuenta_concepto,
					RDTC.concepto_id,
					RDTC.valor,
					RDTC.naturaleza
                FROM    facturas_rc_detalles RDTF 
					LEFT JOIN fac_facturas FF ON (RDTF.prefijo_factura = FF.prefijo AND RDTF.factura_fiscal = FF.factura_fiscal) 
					LEFT JOIN rc_detalle_tesoreria_conceptos RDTC ON (
						RDTC.empresa_id = RDTF.empresa_id
						AND RDTC.centro_utilidad = RDTF.centro_utilidad
						AND RDTC.prefijo=RDTF.rc_prefijo_tras 
						AND RDTC.recibo_caja=RDTF.rc_id_tras
					) 
					LEFT JOIN rc_conceptos_tesoreria RCT ON (RCT.concepto_id = RDTC.concepto_id
						AND RCT.empresa_id = RDTC.empresa_id)
					LEFT JOIN rc_detalle_tesoreria_conceptos RDTC1 ON (
						RDTC1.prefijo=RDTF.prefijo_rc
						AND RDTC1.recibo_caja=RDTF.recibo_caja
					) 
					LEFT JOIN rc_conceptos_tesoreria RCT1 ON (RCT1.concepto_id = RDTC1.concepto_id
						AND RCT1.empresa_id = RDTC1.empresa_id)
                WHERE RDTF.rc_id_tras = '".$rc_id."' 
                AND RDTF.rc_prefijo_tras = '".$prefijo."'; ";
    
    if (!$rst = ConexionBaseDatos($sql))
        return false;

    $datos = array();
    while (!$rst->EOF) {
        $datos[] = $rst->GetRowAssoc($ToUpper = false);
        ;
        $rst->MoveNext();
    }

    $rst->Close();
    return $datos;
}
/*
function getConceptosDetalleWSFI($rc_id,$prefijo)
{
    $sql = "";
    $sql .= "SELECT RDTC.* 
             FROM   rc_detalle_tesoreria_conceptos RDTC 
             WHERE  recibo_caja = '".$rc_id."' 
             AND    prefijo = '".$prefijo."'; ";
    
    if (!$rst = ConexionBaseDatos($sql))
        return false;

    $datos = array();
    while (!$rst->EOF) {
        $datos[] = $rst->GetRowAssoc($ToUpper = false);
        ;
        $rst->MoveNext();
    }

    $rst->Close();
    return $datos;
}
*/
function enviarRCWSFI($encabezado, $detalle, $documento, $empresa_id)
{
    require_once ('nusoap/lib/nusoap.php');
    $url_wsdl = "http://10.0.6.184:8080/SinergiasFinanciero3-ejb/getGestionInformacionContableWS/getGestionInformacionContableWS?wsdl";
	$url_cliente = "http://10.0.6.184:8080/SinergiasFinanciero3-ejb/getTercerosClienteWS/getTercerosClienteWS?wsdl";
	
	$soapclientc = new nusoap_client($url_cliente, true);			
	$paramsc = array('idempresa' => '1', 'numeroidentificacion' => $encabezado['tercero_id']);
	$functionc = 'buscarTerceroCliente';
	$result = $soapclientc->call($functionc,$paramsc);
	
	if(count($result['buscarTerceroClienteResult']) > 0)
	{
		$cuentaTercero = $result['buscarTerceroClienteResult']['codcuentaporcobrar'];
	}
	

    $soapclient = new nusoap_client($url_wsdl,true);
    $function = "crearInformacionContable";
    
    //Codigo de Documento Encabezado
    $coddocumentoencabezado = getDocumentoEncabezadoFI($documento,$empresa_id);

    if(strlen($encabezado['observacion'])<10)
    {
        $encabezado['observacion'] = "SIN OBSERVACION PARA EL ENCABEZADO";
    }
    else
    {
        $encabezado['observacion'] = $encabezado['observacion'];
    }
    $encabezado_rc = array('coddocumentoencabezado'=> $coddocumentoencabezado['prefijo_fi'],
                            'codempresa'=>'COS',
                            'estadoencabezado'=>'4',
                            'fecharegistroencabezado'=>$encabezado['fecha_registro'],
                            'identerceroencabezado'=>$encabezado['tercero_id'],
                            'numerodocumentoencabezado'=>$encabezado['recibo_caja'],
                            'observacionencabezado'=>$encabezado['observacion'],
                            'usuariocreacion'=>$encabezado['usuario_id']
                           );
    $asiento = array();
    $total_saldo = 0;
    for($i=0;$i<count($detalle);$i++)
    {
		if($i ==0){
			$asiento[] = array('codcentrocostoasiento'=>$detalle[$i]['centro_costo'],
							   'codcentroutilidadasiento'=>$detalle[$i]['centro_utilidad'],
							   'codcuentaasiento'=>$detalle[$i]['cuenta_credito'],
							   'codlineacostoasiento'=>$detalle[$i]['linea_costo'],
							   'identerceroasiento'=>$encabezado['tercero_id'],
							   'observacionasiento'=>'SIN OBSERVACION PARA EL ASIENTO',
							   'valorbaseasiento'=>'0',
							   'valorcreditoasiento'=>(int)($detalle[$i]['valor_efectivo']),
							   'valordebitoasiento'=>'0',
							   'valortasaasiento'=>'0'
							  );
		}		  
		if($detalle[$i]['valor']>0){
			$asiento[] = array('codcentrocostoasiento'=>$detalle[$i]['centro_costo'],
							   'codcentroutilidadasiento'=>$detalle[$i]['centro_utilidad'],
							   'codcuentaasiento'=>$detalle[$i]['cuenta_concepto'],
							   'codlineacostoasiento'=>$detalle[$i]['linea_costo'],
							   'identerceroasiento'=>$encabezado['tercero_id'],
							   'observacionasiento'=>'ASIENTO PARA EL CONCEPTO',
							   'valorbaseasiento'=>'0',
							   'valorcreditoasiento'=>0,
							   'valordebitoasiento'=>(int)($detalle[$i]['valor']),
							   'valortasaasiento'=>'0'
							  );
		}
//        $total_saldo += $detalle[$i]['valor_efectivo'];
    }
    
    $asiento[] = array('codcentrocostoasiento'=>$detalle[0]['centro_costo'],
                           'codcentroutilidadasiento'=>$detalle[0]['centro_utilidad'],
                           'codcuentaasiento'=>$cuentaTercero,
                           'codlineacostoasiento'=>$detalle[0]['linea_costo'],
                           'identerceroasiento'=>$encabezado['tercero_id'],
                           'observacionasiento'=>'SIN OBSERVACION PARA EL ASIENTO',
                           'valorbaseasiento'=>'0',
                           'valorcreditoasiento'=>'0',
                           'valordebitoasiento'=>(int)($encabezado['total_abono']),
                           'valortasaasiento'=>'0'
                          );
    $inputs = array('encabezadofactura'=>$encabezado_rc,
                    'asientoscontables'=>$asiento);
    
    echo "<pre>";
    print_r($inputs);
    echo "</pre>";
	
    $resultado = $soapclient->call($function,$inputs);
    echo "<pre>";
    print_r($resultado);
    echo "</pre>";
    
    return $resultado;
}

function insertDataWSFacturacionFI($prefijo,$factura,$descripcion,$estado)
{
    $sql = "";
    $sql .= "INSERT INTO facturacion_ws_fi (prefijo,factura_fiscal,mensaje,estado) 
                    VALUES ('".$prefijo."',
                            '".$factura."',
                            '".$descripcion."',
                            '".$estado."');";
    
    if (!$rst = ConexionBaseDatos($sql))
        return false;
    
    return true;
}
//FIN datos FI

function ConexionBaseDatos($sql) {
        list($dbconn) = GetDBConn();
        //$dbconn->debug = true;
        $rst = $dbconn->Execute($sql);

        if ($dbconn->ErrorNo() != 0) {
            $this->parametro = "MensajeError";
            $this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg() . "<br>$sql";
            return false;
        }
        return $rst;
    }
?>