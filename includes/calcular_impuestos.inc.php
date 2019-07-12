<?php

/**
 * $Id: funciones_datalab.inc.php,v 1.3 2009/12/24 14:37:04 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Funciones para el manejo de equivalencias de datalab
 */
//funciones de equivalencia para datalab

function UpdateImpuestosFacFacturas($empresa_id, $prefijo, $factura_fiscal)
{
    //TRAE DATOS COMO: FF.total_factura, e.reteica, e.base_reteica, e.base_retencion, e.retecre, e.val_honorario, e.val_integral
    $encabezado_factura = obtenerEncabezadoFactura($empresa_id, $prefijo, $factura_fiscal);



    //TRAE LOS numerosdecuenta ASOCIADAS A LA FACTURA
    $numero_cuentas = obtenerNumeroCuentas($empresa_id, $prefijo, $factura_fiscal);



    $numerodecuenta = "";
    for ($i = 0; $i < count($numero_cuentas); $i++)
    {
        $numerodecuenta .= "'" . $numero_cuentas[$i]['numerodecuenta'] . "',";
    }
    $numerodecuenta = substr($numerodecuenta, 0, -1);
    //TRAE LOS SIGUIENTES DATOS: sum(c.sw_integral)as total_c, sum(d.sw_integral)as total_d
    $detalle_factura_servicios = obtenerDetalleFacturaServicios($numerodecuenta);




    $porcentajerete = 0;
    $por_ica = 0;
    $val_medicamento = 0;
    $por_retecre = 0;
    //DETERMINA SI APLICA RETENCION Y PORCENTAJE DE MEDICAMENTOS
    if ($encabezado_factura['int_retencion'] == 1)
    {
        if ($encabezado_factura['total_factura'] >= $encabezado_factura['base_retencion'])
        {
            if ($detalle_factura_servicios['integral'] == 0)
            {
                $porcentajerete = $encabezado_factura['val_honorario'];
                $tValorMedSer = obtenerValorSerMed($numerodecuenta);

                if (count($tValorMedSer) > 0)
                {
                    $val_medicamento = $encabezado_factura['val_medicamento'];
                }
            }
            else
            {
                $porcentajerete = $encabezado_factura['val_integral'];
                $val_medicamento = 0;
            }
        }
    }
    //DETERMINA SI APLICA RETEICA
    if ($encabezado_factura['int_reteica'] == 1)
    {
        if ($encabezado_factura['total_factura'] >= $encabezado_factura['base_reteica'])
        {
            $por_ica = $encabezado_factura['reteica'];
        }
    }
    //DETERMINA SI APLICA RETECRE
    if ($encabezado_factura['int_retecre'] == 1)
    {
        $por_retecre = $encabezado_factura['retecre'];
    }

    $result = ActualizarImpuestosFacFacturas($empresa_id, $prefijo, $factura_fiscal, $porcentajerete, $por_ica, $por_retecre, $val_medicamento);
    return $result;
}

function obtenerEncabezadoFactura($empresa_id, $prefijo, $factura_fiscal)
{
    list($dbconn) = GetDBconn();
    $sql = "SELECT FF.total_factura, e.reteica, e.base_reteica, e.base_retencion, e.retecre, e.val_honorario, e.val_integral, 	
					e.val_medicamento, e.int_retecre, e.int_reteica, e.int_retencion
				FROM empresas e INNER JOIN fac_facturas FF ON(FF.empresa_id = e.empresa_id)
				WHERE FF.empresa_id = '" . $empresa_id . "' 
				   AND FF.prefijo = '" . $prefijo . "' 
				   AND FF.factura_fiscal = '" . $factura_fiscal . "'; ";

    $result = $dbconn->Execute($sql);

    if ($dbconn->ErrorNo() != 0)
    {
        $this->error = "Error al Consultar los tipos de Diagnostico";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        echo $this->mensajeDeError;
        return false;
    }

    $var = array();
    while (!$result->EOF)
    {
        $var = $result->GetRowAssoc($ToUpper = false);
        $result->MoveNext();
    }
    $result->Close();
    return $var;
}

function obtenerNumeroCuentas($empresa_id, $prefijo, $factura_fiscal)
{
    list($dbconn) = GetDBconn();
    $sql = "";
    $sql .="SELECT 	numerodecuenta 
                FROM 	fac_facturas_cuentas 
                WHERE 	empresa_id = '" . $empresa_id . "' 
                AND 	prefijo = '" . $prefijo . "' 
                AND 	factura_fiscal = '" . $factura_fiscal . "'; ";

//		print_r("Cuentas sql: ".$sql);
    $result = $dbconn->Execute($sql);

    if ($dbconn->ErrorNo() != 0)
    {
        $this->error = "Error al Consultar los tipos de Diagnostico";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        echo $this->mensajeDeError;
        return false;
    }

    $var = array();
    while (!$result->EOF)
    {
        $var[] = $result->GetRowAssoc($ToUpper = false);
        $result->MoveNext();
    }
    $result->Close();
    return $var;
}

function obtenerDetalleFacturaServicios($numerodecuenta)
{
    list($dbconn) = GetDBconn();
    $sql = "";

    $sql .= "   
			SELECT sum(c.sw_integral)as integral
			FROM cups c INNER JOIN cuentas_detalle cd ON (cd.cargo_cups = c.cargo)
			WHERE cd.numerodecuenta IN  (" . $numerodecuenta . ") 
				AND CD.cargo NOT IN ('IMD','DIMD')
				AND CD.facturado = 1
				AND NOT(CD.paquete_codigo_id IS NOT NULL AND CD.sw_paquete_facturado = 0)";

    $result = $dbconn->Execute($sql);

    if ($dbconn->ErrorNo() != 0)
    {
        $this->error = "Error al Consultar los tipos de Diagnostico";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        echo $this->mensajeDeError;
        return false;
    }

    $var = array();
    while (!$result->EOF)
    {
        $var = $result->GetRowAssoc($ToUpper = false);
        $result->MoveNext();
    }
    $result->Close();
    return $var;
}

function obtenerValoresDepartamento($numerodecuenta)
{
    list($dbconn) = GetDBconn();
    $sql = "";

    $sql .= "	SELECT d.val_honorario, d.val_integral
					FROM departamentos d INNER JOIN cuentas_detalle cd ON(d.departamento = cd.departamento)
					WHERE cd.numerodecuenta = " . $numerodecuenta . "
					LIMIT 1 ";

    $result = $dbconn->Execute($sql);

    if ($dbconn->ErrorNo() != 0)
    {
        $this->error = "Error al Consultar los tipos de Diagnostico";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        echo $this->mensajeDeError;
        return false;
    }

    $var = array();
    while (!$result->EOF)
    {
        $var = $result->GetRowAssoc($ToUpper = false);
        $result->MoveNext();
    }
    $result->Close();
    return $var;
}

function ActualizarImpuestosFacFacturas($empresa_id, $prefijo, $factura_fiscal, $valor_retencion, $valor_ica, $valor_cre, $valor_medicamentos)
{
    list($dbconn) = GetDBconn();
    $sql = "";
    $sql .= "UPDATE fac_facturas
				 SET valor_retencion = " . $valor_retencion . ",
					valor_reteica   = " . $valor_ica . ",
					valor_retecre   = " . $valor_cre . ",
					valor_medicamento = " . $valor_medicamentos . "
				 WHERE empresa_id  = '" . $empresa_id . "'
					  AND prefijo =   '" . $prefijo . "'
					  AND factura_fiscal = " . $factura_fiscal . ";";

    //echo $sql;
    $resulta = $dbconn->Execute($sql);

    if ($dbconn->ErrorNo() != 0)
    {
        $this->error = "Error al actualizar Impuestos en fac_facturas";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
    }

    return true;
}

function obtenerValorSerMed($numerodecuenta)
{
    list($dbconn) = GetDBconn();
    $sql = "SELECT 'M' AS tipo, SUM(CD.valor_cargo) as valor_cargo
				FROM cuentas_detalle CD
					INNER JOIN bodegas_documentos_d BDD ON(CD.consecutivo = BDD.consecutivo)
					INNER JOIN inventarios_productos IP ON(BDD.codigo_producto = IP.codigo_producto)
					INNER JOIN inv_subclases_inventarios II ON (II.grupo_id = IP.grupo_id AND II.clase_id = IP.clase_id 
						AND II.subclase_id = IP.subclase_id)
				WHERE CD.numerodecuenta IN (" . $numerodecuenta . ") 
					AND CD.cargo IN ('IMD','DIMD')
					AND CD.facturado = '1'
					AND NOT(CD.paquete_codigo_id IS NOT NULL AND CD.sw_paquete_facturado = '0')
				GROUP BY 1 ";

    $result = $dbconn->Execute($sql);

    if ($dbconn->ErrorNo() != 0)
    {
        $this->error = "Error al Consultar los tipos de Diagnostico";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        echo $this->mensajeDeError;
        return false;
    }

    $var = array();
    while (!$result->EOF)
    {
        $var[] = $result->GetRowAssoc($ToUpper = false);
        $result->MoveNext();
    }
    $result->Close();
    return $var;
}

function obtenerValorSerMed1($numerodecuenta)
{
    list($dbconn) = GetDBconn();
    $sql = "    SELECT a.tipo, a.valor_cargo
					FROM(
							SELECT 'M' AS tipo, SUM(CD.valor_cargo) as valor_cargo
							FROM cuentas_detalle CD
								INNER JOIN bodegas_documentos_d BDD ON(CD.consecutivo = BDD.consecutivo)
								INNER JOIN inventarios_productos IP ON(BDD.codigo_producto = IP.codigo_producto)
								INNER JOIN inv_subclases_inventarios II ON (II.grupo_id = IP.grupo_id AND II.clase_id = IP.clase_id 
									AND II.subclase_id = IP.subclase_id)
							WHERE CD.numerodecuenta IN (" . $numerodecuenta . ") 
								AND CD.cargo IN ('IMD','DIMD')
								AND CD.facturado = '1'
								AND NOT(CD.paquete_codigo_id IS NOT NULL AND CD.sw_paquete_facturado = '0')
							GROUP BY 1    
							UNION    
							SELECT  'S' AS tipo, SUM(CD.valor_cargo) as valor_cargo 
							FROM cuentas_detalle CD 
							WHERE CD.numerodecuenta IN (" . $numerodecuenta . ") 
								AND CD.cargo NOT IN ('IMD','DIMD')
								AND CD.facturado = '1' 
								AND NOT(CD.paquete_codigo_id IS NOT NULL AND CD.sw_paquete_facturado = '0')
						)as a";

    $result = $dbconn->Execute($sql);

    if ($dbconn->ErrorNo() != 0)
    {
        $this->error = "Error al Consultar los tipos de Diagnostico";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        echo $this->mensajeDeError;
        return false;
    }

    $var = array();
    while (!$result->EOF)
    {
        $var[] = $result->GetRowAssoc($ToUpper = false);
        $result->MoveNext();
    }
    $result->Close();
    return $var;
}

?>