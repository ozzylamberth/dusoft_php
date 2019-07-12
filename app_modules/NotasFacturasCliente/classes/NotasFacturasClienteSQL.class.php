<?php

class NotasFacturasClienteSQL extends ConexionBD {
    /*     * *******************************
     * Constructor
     * ******************************* */

    function NotasFacturasClienteSQL()
    {
        
    }

    function ObtenerFactura($factura, $empresa_id)
    {
        $sql = "SELECT DISTINCT ON (ifd.empresa_id, ifd.factura_fiscal, ifd.prefijo) 
                        ifd.factura_fiscal, ifd.prefijo, t.tipo_id_tercero, t.tercero_id, t.nombre_tercero, 0 as tipo_factura, CAST(ifd.fecha_registro AS date) AS fecha_registro, ifd.valor_total, ifd.saldo, ibmdc.empresa_id AS empresa_id_devolucion, ibmdc.prefijo AS prefijo_devolucion, 
                         to_char(ifd.fecha_registro, 'yyyy') as anio_factura, ifd.porcentaje_rtf, ifd.porcentaje_ica, ifd.empresa_id,
                        (SELECT 
                            MAX(ibmdc_sc.numero) 
                        FROM 
                            inv_facturas_despacho ifd_sc 
                            LEFT OUTER JOIN inv_bodegas_movimiento_devolucion_cliente ibmdc_sc ON (ifd_sc.empresa_id = ibmdc_sc.empresa_id AND ifd_sc.factura_fiscal = ibmdc_sc.numero_doc_cliente AND ifd_sc.prefijo = ibmdc_sc.prefijo_doc_cliente) 
                        WHERE 
                            ifd_sc.factura_fiscal = " . $factura . " 
                            AND ifd_sc.empresa_id = '" . $empresa_id . "' 
                        ) AS numero_devolucion, 
                        (SELECT 
                            MAX(ncdc_sc.numero_devolucion) 
                        FROM 
                            inv_facturas_despacho ifd_sc 
                            LEFT OUTER JOIN notas_credito_despachos_clientes ncdc_sc ON (ifd_sc.empresa_id = ncdc_sc.empresa_id AND ifd_sc.factura_fiscal = ncdc_sc.factura_fiscal AND ifd_sc.prefijo = ncdc_sc.prefijo) 
                        WHERE 
                            ifd_sc.factura_fiscal = " . $factura . " 
                            AND ifd_sc.empresa_id = '" . $empresa_id . "' 
                        ) AS numero_devolucion_nota_credito 
                    FROM 
                        inv_facturas_despacho ifd
                        INNER JOIN terceros t ON (ifd.tipo_id_tercero = t.tipo_id_tercero AND ifd.tercero_id = t.tercero_id)
                        LEFT OUTER JOIN inv_bodegas_movimiento_devolucion_cliente ibmdc ON (ifd.empresa_id = ibmdc.empresa_id AND ifd.factura_fiscal = ibmdc.numero_doc_cliente AND ifd.prefijo = ibmdc.prefijo_doc_cliente)
                        LEFT OUTER JOIN inv_bodegas_movimiento ibm ON (ibmdc.empresa_id = ibm.empresa_id AND ibmdc.prefijo_doc_cliente = ibm.prefijo AND ibmdc.numero_doc_cliente = ibm.numero)
                    WHERE 
                        ifd.factura_fiscal = " . $factura . "
                        AND ifd.empresa_id = '" . $empresa_id . "'
                        
                         UNION ALL
                         
                        SELECT DISTINCT ON (ifd.empresa_id, ifd.factura_fiscal, ifd.prefijo) 
                        ifd.factura_fiscal, ifd.prefijo, t.tipo_id_tercero, t.tercero_id, t.nombre_tercero, 1 as tipo_factura, CAST(ifd.fecha_registro AS date) AS fecha_registro, ifd.valor_total, ifd.saldo, ibmdc.empresa_id AS empresa_id_devolucion, ibmdc.prefijo AS prefijo_devolucion, 
                        to_char(ifd.fecha_registro, 'yyyy') as anio_factura, ifd.porcentaje_rtf, ifd.porcentaje_ica, ifd.empresa_id,
                        (SELECT 
                            MAX(ibmdc_sc.numero) 
                        FROM 
                            inv_facturas_agrupadas_despacho ifd_sc 
                            LEFT OUTER JOIN inv_bodegas_movimiento_devolucion_cliente ibmdc_sc ON (ifd_sc.empresa_id = ibmdc_sc.empresa_id AND ifd_sc.factura_fiscal = ibmdc_sc.numero_doc_cliente AND ifd_sc.prefijo = ibmdc_sc.prefijo_doc_cliente) 
                        WHERE 
                            ifd_sc.factura_fiscal = " . $factura . " 
                            AND ifd_sc.empresa_id = '" . $empresa_id . "' 
                        ) AS numero_devolucion, 
                        (SELECT 
                            MAX(ncdc_sc.numero_devolucion) 
                        FROM 
                            inv_facturas_agrupadas_despacho ifd_sc 
                            LEFT OUTER JOIN notas_credito_despachos_clientes_agrupados ncdc_sc ON (ifd_sc.empresa_id = ncdc_sc.empresa_id AND ifd_sc.factura_fiscal = ncdc_sc.factura_fiscal AND ifd_sc.prefijo = ncdc_sc.prefijo) 
                        WHERE 
                            ifd_sc.factura_fiscal = " . $factura . " 
                            AND ifd_sc.empresa_id = '" . $empresa_id . "' 
                        ) AS numero_devolucion_nota_credito 
                    FROM 
                        inv_facturas_agrupadas_despacho ifd
                        INNER JOIN terceros t ON (ifd.tipo_id_tercero = t.tipo_id_tercero AND ifd.tercero_id = t.tercero_id)
                        LEFT OUTER JOIN inv_bodegas_movimiento_devolucion_cliente ibmdc ON (ifd.empresa_id = ibmdc.empresa_id AND ifd.factura_fiscal = ibmdc.numero_doc_cliente AND ifd.prefijo = ibmdc.prefijo_doc_cliente)
                        LEFT OUTER JOIN inv_bodegas_movimiento ibm ON (ibmdc.empresa_id = ibm.empresa_id AND ibmdc.prefijo_doc_cliente = ibm.prefijo AND ibmdc.numero_doc_cliente = ibm.numero)
                    WHERE 
                        ifd.factura_fiscal = " . $factura . "
                        AND ifd.empresa_id = '" . $empresa_id . "'";
        
      //echo "<pre>". $sql."</pre><br><br>";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

    function ObtenerDetalleFacturaNotaCreditoValor($factura, $empresa_id, $tipo)
    {
        $sql = "SELECT DISTINCT ON (ifdd.item_id) ifd.factura_fiscal, ifd.fecha_registro, ifd.valor_total, fc_descripcion_producto(ifdd.codigo_producto) AS producto, ifdd.item_id, ifdd.codigo_producto, ifdd.cantidad, (ifdd.cantidad - ifdd.cantidad_devuelta) as cantidad_existente,  ifdd.lote, ifdd.valor_unitario,
            /*ifdd.cantidad_devuelta, */tdncdc.tmp_detalle_nota_credito_despacho_cliente_id, COALESCE(tdncdc.valor, 0) AS valor, tdncdc.observacion, 
                        (round((tdncdc.valor / ifdd.cantidad), 4)) AS valor_digitado_nota, 
                        ((ifdd.cantidad * ifdd.valor_unitario) - (COALESCE(tdncdc.valor, 0))) AS diferencia , tdncdc.valor_iva, tncdc.valor_nota, tncdc.descripcion
                    FROM inv_facturas_despacho ifd 
                        INNER JOIN inv_facturas_despacho_d ifdd ON (ifd.empresa_id = ifdd.empresa_id 
                            AND ifd.factura_fiscal = ifdd.factura_fiscal 
                            AND ifd.prefijo = ifdd.prefijo) 
                        LEFT OUTER JOIN tmp_notas_credito_despachos_clientes tncdc ON (ifd.empresa_id = tncdc.empresa_id 
                            AND ifd.factura_fiscal = tncdc.factura_fiscal 
                            AND ifd.prefijo = tncdc.prefijo AND tncdc.tipo = '{$tipo}') 
                        LEFT OUTER JOIN tmp_detalles_notas_credito_despachos_clientes tdncdc ON (tncdc.tmp_nota_credito_despacho_cliente_id = tdncdc.tmp_nota_credito_despacho_cliente_id 
                            AND ifdd.item_id = tdncdc.item_id) 
                    WHERE ifd.factura_fiscal = " . $factura . " 
                        AND ifd.empresa_id = '" . $empresa_id . "' 
                    GROUP BY 
                        ifd.factura_fiscal, ifd.fecha_registro, ifd.valor_total, producto, ifdd.item_id, ifdd.codigo_producto, ifdd.cantidad, ifdd.cantidad_devuelta,  ifdd.lote, ifdd.valor_unitario, /*ifdd.cantidad_devuelta, */tdncdc.tmp_detalle_nota_credito_despacho_cliente_id, valor, tdncdc.observacion, tdncdc.valor_iva , tncdc.valor_nota, tncdc.descripcion
                            
                     UNION ALL
                     
                     SELECT DISTINCT ON (ifdd.item_id) ifd.factura_fiscal, ifd.fecha_registro, ifd.valor_total, fc_descripcion_producto(ifdd.codigo_producto) AS producto, ifdd.item_id, ifdd.codigo_producto, ifdd.cantidad, (ifdd.cantidad - ifdd.cantidad_devuelta) as cantidad_existente, ifdd.lote, ifdd.valor_unitario,
                    /*ifdd.cantidad_devuelta, */tdncdc.tmp_detalle_nota_credito_despacho_cliente_id, COALESCE(tdncdc.valor, 0) AS valor, tdncdc.observacion, 
                        (round((tdncdc.valor / ifdd.cantidad), 4)) AS valor_digitado_nota, 
                        ((ifdd.cantidad * ifdd.valor_unitario) - (COALESCE(tdncdc.valor, 0))) AS diferencia , tdncdc.valor_iva, tncdc.valor_nota, tncdc.descripcion
                    FROM inv_facturas_agrupadas_despacho ifd 
                        INNER JOIN inv_facturas_agrupadas_despacho_d ifdd ON (ifd.empresa_id = ifdd.empresa_id 
                            AND ifd.factura_fiscal = ifdd.factura_fiscal 
                            AND ifd.prefijo = ifdd.prefijo) 
                        LEFT OUTER JOIN tmp_notas_credito_despachos_clientes tncdc ON (ifd.empresa_id = tncdc.empresa_id 
                            AND ifd.factura_fiscal = tncdc.factura_fiscal 
                            AND ifd.prefijo = tncdc.prefijo AND tncdc.tipo = '{$tipo}') 
                        LEFT OUTER JOIN tmp_detalles_notas_credito_despachos_clientes tdncdc ON (tncdc.tmp_nota_credito_despacho_cliente_id = tdncdc.tmp_nota_credito_despacho_cliente_id 
                            AND ifdd.item_id = tdncdc.item_id) 
                     WHERE ifd.factura_fiscal = " . $factura . " 

                        AND ifd.empresa_id = '" . $empresa_id . "' 
                    GROUP BY 
                        ifd.factura_fiscal, ifd.fecha_registro, ifd.valor_total, producto, ifdd.item_id, ifdd.codigo_producto, ifdd.cantidad, ifdd.cantidad_devuelta, ifdd.lote, ifdd.valor_unitario, /*ifdd.cantidad_devuelta, */tdncdc.tmp_detalle_nota_credito_despacho_cliente_id, valor, tdncdc.observacion,tdncdc.valor_iva, tncdc.valor_nota, tncdc.descripcion";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

    function ObtenerDetalleFacturaNotaDebito($factura, $empresa_id)
    {
        $sql = "SELECT DISTINCT ON (ifdd.item_id) ifd.factura_fiscal, ifd.fecha_registro, ifd.valor_total, fc_descripcion_producto(ifdd.codigo_producto) AS producto, ifdd.item_id, ifdd.codigo_producto, ifdd.cantidad, ifdd.lote, ifdd.valor_unitario, /*ifdd.cantidad_devuelta, */tdnddc.tmp_detalle_nota_debito_despacho_cliente_id, COALESCE(tdnddc.valor, 0) AS valor, tdnddc.observacion, 
                        (round((tdnddc.valor / ifdd.cantidad), 4)) AS valor_digitado_nota/*, 
                        ((ifdd.cantidad * ifdd.valor_unitario) - (COALESCE(tdnddc.valor, 0))) AS diferencia*/ , tdnddc.valor_iva
                    FROM inv_facturas_despacho ifd 
                        INNER JOIN inv_facturas_despacho_d ifdd ON (ifd.empresa_id = ifdd.empresa_id 
                            AND ifd.factura_fiscal = ifdd.factura_fiscal 
                            AND ifd.prefijo = ifdd.prefijo) 
                        LEFT OUTER JOIN tmp_notas_debito_despachos_clientes tnddc ON (ifd.empresa_id = tnddc.empresa_id 
                            AND ifd.factura_fiscal = tnddc.factura_fiscal 
                            AND ifd.prefijo = tnddc.prefijo) 
                        LEFT OUTER JOIN tmp_detalles_notas_debito_despachos_clientes tdnddc ON (tnddc.tmp_nota_debito_despacho_cliente_id = tdnddc.tmp_nota_debito_despacho_cliente_id 
                            AND ifdd.item_id = tdnddc.item_id) 
                    WHERE ifd.factura_fiscal = " . $factura . " 
                        AND ifd.empresa_id = '" . $empresa_id . "' 
                    GROUP BY 
                        ifd.factura_fiscal, ifd.fecha_registro, ifd.valor_total, producto, ifdd.item_id, ifdd.codigo_producto, ifdd.cantidad, ifdd.lote, ifdd.valor_unitario, /*ifdd.cantidad_devuelta, */tdnddc.tmp_detalle_nota_debito_despacho_cliente_id, valor, tdnddc.observacion , tdnddc.valor_iva

                    UNION ALL 

                    SELECT DISTINCT ON (ifdd.item_id) ifd.factura_fiscal, ifd.fecha_registro, ifd.valor_total, fc_descripcion_producto(ifdd.codigo_producto) AS producto, ifdd.item_id, ifdd.codigo_producto, ifdd.cantidad, ifdd.lote, ifdd.valor_unitario, /*ifdd.cantidad_devuelta, */tdnddc.tmp_detalle_nota_debito_despacho_cliente_id, COALESCE(tdnddc.valor, 0) AS valor, tdnddc.observacion, 
                          (round((tdnddc.valor / ifdd.cantidad), 4)) AS valor_digitado_nota/*, 
                          ((ifdd.cantidad * ifdd.valor_unitario) - (COALESCE(tdnddc.valor, 0))) AS diferencia*/  , tdnddc.valor_iva
                      FROM inv_facturas_agrupadas_despacho ifd 
                          INNER JOIN inv_facturas_agrupadas_despacho_d ifdd ON (ifd.empresa_id = ifdd.empresa_id 
                              AND ifd.factura_fiscal = ifdd.factura_fiscal 
                              AND ifd.prefijo = ifdd.prefijo) 
                          LEFT OUTER JOIN tmp_notas_debito_despachos_clientes tnddc ON (ifd.empresa_id = tnddc.empresa_id 
                              AND ifd.factura_fiscal = tnddc.factura_fiscal 
                              AND ifd.prefijo = tnddc.prefijo) 
                          LEFT OUTER JOIN tmp_detalles_notas_debito_despachos_clientes tdnddc ON (tnddc.tmp_nota_debito_despacho_cliente_id = tdnddc.tmp_nota_debito_despacho_cliente_id 
                              AND ifdd.item_id = tdnddc.item_id) 
                      WHERE ifd.factura_fiscal = " . $factura . " 
                          AND ifd.empresa_id = '" . $empresa_id . "' 
                      GROUP BY 
                          ifd.factura_fiscal, ifd.fecha_registro, ifd.valor_total, producto, ifdd.item_id, ifdd.codigo_producto, ifdd.cantidad, ifdd.lote, ifdd.valor_unitario, /*ifdd.cantidad_devuelta, */tdnddc.tmp_detalle_nota_debito_despacho_cliente_id, valor, tdnddc.observacion , tdnddc.valor_iva";
        //echo $sql."<br><br><br>";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

    function ObtenerDetalleFacturaNotaCreditoDevolucion($factura, $empresa_id, $tipo_factura = '0', $tipo)
    {        
        $facturasdetalle = "inv_facturas_despacho_d";
        $tablafactura = "inv_facturas_despacho";
        $tablanota     = "notas_credito_despachos_clientes";
        $tabladetallenota = "detalles_notas_credito_despachos_clientes";
        
        if($tipo_factura == '1'){
            $facturasdetalle = "inv_facturas_agrupadas_despacho_d";
            $tablafactura = "inv_facturas_agrupadas_despacho";
            $tablanota = "notas_credito_despachos_clientes_agrupados";
            $tabladetallenota = "detalles_notas_credito_despachos_clientes_agrupados";
        }
        

        
        $sql = "
                   SELECT DISTINCT on (g.movimiento_id)  f.numero, a.factura_fiscal, a.fecha_registro, a.valor_total, fc_descripcion_producto(b.codigo_producto) AS producto,
                    b.item_id, b.codigo_producto, b.cantidad, b.lote, b.valor_unitario, b.cantidad_devuelta,
                      f.documento_id, g.movimiento_id, 
                     (CASE WHEN g.cantidad IS NULL THEN 0 ELSE CAST(g.cantidad AS INTEGER) END) AS cantidad_producto_devuelto, 
                     (CASE WHEN g.cantidad IS NOT NULL THEN (g.cantidad * b.valor_unitario) ELSE NULL END) AS valor_total_producto_devuelto
                   FROM {$tablafactura} a 
                   INNER JOIN {$facturasdetalle} b ON (a.empresa_id = b.empresa_id AND a.factura_fiscal = b.factura_fiscal AND a.prefijo = b.prefijo)
                   INNER  JOIN inv_bodegas_movimiento_devolucion_cliente e ON ( a.empresa_id = e.empresa_id AND a.factura_fiscal = e.numero_doc_cliente AND a.prefijo = e.prefijo_doc_cliente )
                   LEFT  JOIN inv_bodegas_movimiento f ON (e.empresa_id = f.empresa_id AND e.prefijo = f.prefijo AND e.numero = f.numero )
                   right  JOIN inv_bodegas_movimiento_d g ON (
                                f.empresa_id = g.empresa_id AND f.prefijo = g.prefijo AND f.numero = g.numero AND b.codigo_producto = g.codigo_producto and b.lote = g.lote and b.fecha_vencimiento = g.fecha_vencimiento
                                and g.movimiento_id NOT IN(
                            	SELECT distinct on (b.movimiento_id) aa.movimiento_id FROM inv_bodegas_movimiento_d aa
                            	inner join {$tabladetallenota} b on aa.movimiento_id = b.movimiento_id  
                                inner join {$tablanota} c on c.nota_credito_despacho_cliente_id = b.nota_credito_despacho_cliente_id
                            	where c.factura_fiscal = a.factura_fiscal  
                            )
                            and g.movimiento_id NOT IN(
                            	SELECT distinct on (b.movimiento_id) aa.movimiento_id FROM inv_bodegas_movimiento_d aa
                            	inner join tmp_detalles_notas_credito_despachos_clientes b on aa.movimiento_id = b.movimiento_id  
                                    inner join tmp_notas_credito_despachos_clientes c on c.tmp_nota_credito_despacho_cliente_id = b.tmp_nota_credito_despacho_cliente_id
                            	where c.factura_fiscal = a.factura_fiscal
                            )
                   
                    ) 
             WHERE a.factura_fiscal = " . $factura . " 
                        AND a.empresa_id = '" . $empresa_id . "' 
                   GROUP BY 
                     a.factura_fiscal, a.fecha_registro, a.valor_total, producto, b.item_id, b.codigo_producto, b.cantidad, b.lote, b.valor_unitario, b.cantidad_devuelta,  
                       f.documento_id, g.movimiento_id, f.numero, g.cantidad, g.empresa_id, g.prefijo";

     //echo "<pre>".$sql."</pre><br><br>";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }
    
    
    function buscarTemporalesNotaCreditoPorDevolucion($factura, $empresa_id, $tipo_factura = '0', $tipo){
        $facturasdetalle = "inv_facturas_despacho_d";
        $tablafactura = "inv_facturas_despacho";
        $tablanota     = "notas_credito_despachos_clientes";
        if($tipo_factura == '1'){
            $facturasdetalle = "inv_facturas_agrupadas_despacho_d";
            $tablafactura = "inv_facturas_agrupadas_despacho";
            $tablanota     = "notas_credito_despachos_clientes_agrupados";
        }
        
        $sql = "
                    SELECT  distinct on(g.movimiento_id) f.numero, a.factura_fiscal, a.fecha_registro, a.valor_total, fc_descripcion_producto(b.codigo_producto) AS producto,
                    b.item_id, b.codigo_producto, b.cantidad, b.lote, b.valor_unitario, b.cantidad_devuelta, d.tmp_detalle_nota_credito_despacho_cliente_id,
                     COALESCE(d.valor, 0) AS valor, d.observacion, f.documento_id, g.movimiento_id, 
                     (CASE WHEN g.cantidad IS NULL THEN 0 ELSE CAST(g.cantidad AS INTEGER) END) AS cantidad_producto_devuelto, 
                     (CASE WHEN g.cantidad IS NOT NULL THEN (g.cantidad * b.valor_unitario) ELSE NULL END) AS valor_total_producto_devuelto, 
                     (round((d.valor / b.cantidad), 4)) AS valor_digitado_nota, 
                     ((b.cantidad * b.valor_unitario) - (COALESCE(d.valor, 0))) AS diferencia, d.valor_iva, c.tipo
                   FROM {$tablafactura} a 
                   INNER JOIN {$facturasdetalle} b ON (a.empresa_id = b.empresa_id AND a.factura_fiscal = b.factura_fiscal AND a.prefijo = b.prefijo)
                   INNER  JOIN tmp_notas_credito_despachos_clientes c ON (a.empresa_id = c.empresa_id  AND a.factura_fiscal = c.factura_fiscal AND a.prefijo = c.prefijo AND c.tipo='{$tipo}') 
                   INNER  JOIN tmp_detalles_notas_credito_despachos_clientes d ON (d.tmp_nota_credito_despacho_cliente_id = c.tmp_nota_credito_despacho_cliente_id AND b.item_id = d.item_id AND c.tipo='{$tipo}') 
                   INNER  JOIN inv_bodegas_movimiento_devolucion_cliente e ON (a.empresa_id = e.empresa_id AND a.factura_fiscal = e.numero_doc_cliente AND a.prefijo = e.prefijo_doc_cliente)
                   INNER  JOIN inv_bodegas_movimiento f ON (e.empresa_id = f.empresa_id AND e.prefijo = f.prefijo AND e.numero = f.numero )
                   right  JOIN inv_bodegas_movimiento_d g ON (f.empresa_id = g.empresa_id AND f.prefijo = g.prefijo AND f.numero = g.numero AND b.codigo_producto = g.codigo_producto and b.lote = g.lote and b.fecha_vencimiento = g.fecha_vencimiento  and g.movimiento_id = d.movimiento_id) 
              WHERE b.factura_fiscal = " . $factura . " 
                        AND a.empresa_id = '" . $empresa_id . "' 
                   GROUP BY 
                     a.factura_fiscal, a.fecha_registro, a.valor_total, producto, b.item_id, b.codigo_producto, b.cantidad, b.lote, b.valor_unitario, b.cantidad_devuelta,  
                     d.tmp_detalle_nota_credito_despacho_cliente_id, valor, d.observacion, f.documento_id, g.movimiento_id, f.numero, g.cantidad, g.empresa_id, g.prefijo, d.valor_iva, c.tipo";

      //echo "<pre>".$sql."</pre><br><br>";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }
    
    function GuardarNotaCreditoTemporalPorValor($empresa_id, $factura_fiscal, $prefijo, $tipo, $tipo_factura, $concepto_id, $valor, $descripcion)
    {
        $sql = "INSERT INTO tmp_notas_credito_despachos_clientes(empresa_id, factura_fiscal, prefijo, tipo, usuario_id, sw_agrupada, concepto_id, valor_nota, descripcion)
                    VALUES ('" . $empresa_id . "', " . $factura_fiscal . ", '" . $prefijo . "', '" . $tipo . "', " . UserGetUID() . ", '{$tipo_factura}', '{$concepto_id}', {$valor}, '{$descripcion}') RETURNING tmp_nota_credito_despacho_cliente_id";
        //echo $sql."<br><br><br>";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }
    
    function modificarConcepto($idtemporal, $concepto_id){
        $sql = "UPDATE tmp_notas_credito_despachos_clientes SET concepto_id = {$concepto_id} WHERE tmp_nota_credito_despacho_cliente_id = {$idtemporal}";
        
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        
        return true;
    }

    function GuardarNotaCreditoTemporalPorDevolucion($empresa_id, $factura_fiscal, $prefijo, $empresa_id_devolucion, $prefijo_devolucion, $numero_devolucion, $tipo, $tipo_factura)
    {
        $sql = "INSERT INTO tmp_notas_credito_despachos_clientes(empresa_id, factura_fiscal, prefijo, empresa_id_devolucion, prefijo_devolucion, numero_devolucion, tipo, usuario_id, sw_agrupada)
                    VALUES ('" . $empresa_id . "', " . $factura_fiscal . ", '" . $prefijo . "', '" . $empresa_id_devolucion . "', '" . $prefijo_devolucion . "', " . $numero_devolucion . ", '" . $tipo . "', " . UserGetUID() . ", '{$tipo_factura}') RETURNING tmp_nota_credito_despacho_cliente_id";
        //echo $sql."<br><br><br>";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

    function GuardarNotaDebitoTemporal($empresa_id, $factura_fiscal, $prefijo, $tipo_factura)
    {
        $sql = "INSERT INTO tmp_notas_debito_despachos_clientes(empresa_id, factura_fiscal, prefijo, usuario_id, sw_agrupada)
                    VALUES ('" . $empresa_id . "', " . $factura_fiscal . ", '" . $prefijo . "', " . UserGetUID() . ", '{$tipo_factura}') RETURNING tmp_nota_debito_despacho_cliente_id";
        //echo $sql."<br><br><br>";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

    function ObtenerValoresImpuestosNotaTemporal($item_id, $valor, $tipo_factura = '0')
    {
        
        $tablafactura = "inv_facturas_despacho";
        $tablafacturadetalle = "inv_facturas_despacho_d";     
        
        if($tipo_factura == '1'){
            $tablafactura = "inv_facturas_agrupadas_despacho";
            $tablafacturadetalle = "inv_facturas_agrupadas_despacho_d";
        }
        
        $sql = "SELECT 
                        ((" . $valor . " / 100) * ifdd.porc_iva) AS valor_total_iva_nota, ((" . $valor . " / 100) * ifd.porcentaje_rtf) AS valor_total_rtf_nota, ((" . $valor . " / 1000) * ifd.porcentaje_ica) AS valor_total_ica_nota  
                    FROM 
                        {$tablafacturadetalle} ifdd 
                        INNER JOIN {$tablafactura} ifd ON (ifdd.empresa_id = ifd.empresa_id 
                            AND ifdd.factura_fiscal = ifd.factura_fiscal 
                            AND ifdd.prefijo = ifd.prefijo)
                    WHERE 
                        ifdd.item_id = " . $item_id . " ";
      // echo "tipo de factura {$tipo_factura}"."<br><br><br>"; 
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

    function GuardarDetalleNotaCreditoTemporal($tmp_nota_credito_despacho_cliente_id, $item_id, $valor, $observacion, $valor_iva, $valor_rtf, $valor_ica, $movimiento_id)
    {
        $sql = "INSERT INTO tmp_detalles_notas_credito_despachos_clientes(tmp_nota_credito_despacho_cliente_id, item_id, valor, observacion, valor_iva, valor_rtf, valor_ica, movimiento_id)
                    VALUES (" . $tmp_nota_credito_despacho_cliente_id . ", " . $item_id . ", " . ceil($valor) . ", '" . $observacion . "', '" .ceil($valor_iva) . "', '" .ceil($valor_rtf) . "', '" .ceil($valor_ica) . "', $movimiento_id) ";
        //echo $sql."<br><br><br>"; 
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

    function GuardarDetalleNotaDebitoTemporal($tmp_nota_debito_despacho_cliente_id, $item_id, $valor, $observacion, $valor_iva, $valor_rtf, $valor_ica)
    {
        $sql = "INSERT INTO tmp_detalles_notas_debito_despachos_clientes(tmp_nota_debito_despacho_cliente_id, item_id, valor, observacion, valor_iva, valor_rtf, valor_ica)
                    VALUES (" . $tmp_nota_debito_despacho_cliente_id . ", " . $item_id . ", " . $valor . ", '" . $observacion . "', " . $valor_iva . ", " . $valor_rtf . ", " . $valor_ica . ") ";
        //echo $sql."<br><br><br>";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

    function ObtenerIdNotaCreditoTemporal($empresa_id, $factura_fiscal, $prefijo_factura, $tipo)
    {
        $sql = "SELECT 
                        tmp_nota_credito_despacho_cliente_id ,
                        concepto_id
                    FROM 
                        tmp_notas_credito_despachos_clientes 
                    WHERE 
                        empresa_id = '" . $empresa_id . "'
                        AND factura_fiscal = '" . $factura_fiscal . "'
                        AND prefijo = '" . $prefijo_factura . "'  
                        AND tipo='{$tipo}'";
                        
        //echo $sql."<br>";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

    function ObtenerIdNotaDebitoTemporal($empresa_id, $factura_fiscal, $prefijo_factura)
    {
        $sql = "SELECT 
                        tmp_nota_debito_despacho_cliente_id 
                    FROM 
                        tmp_notas_debito_despachos_clientes 
                    WHERE 
                        empresa_id = '" . $empresa_id . "'
                        AND factura_fiscal = '" . $factura_fiscal . "'
                        AND prefijo = '" . $prefijo_factura . "' ";
        //echo $sql."<br>";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

    function EliminarDetalleNotaCreditoTemporal($tmp_detalle_nota_credito_despacho_cliente_id)
    {
        
          $sql = "SELECT a.tmp_nota_credito_despacho_cliente_id,
                    total
                    FROM
                     tmp_detalles_notas_credito_despachos_clientes a
                     inner join (
                            SELECT COUNT(b.tmp_nota_credito_despacho_cliente_id) AS total, b.tmp_nota_credito_despacho_cliente_id FROM
                         tmp_detalles_notas_credito_despachos_clientes b GROUP BY 2

                     ) as b on b.tmp_nota_credito_despacho_cliente_id = a.tmp_nota_credito_despacho_cliente_id


                      WHERE  tmp_detalle_nota_credito_despacho_cliente_id = {$tmp_detalle_nota_credito_despacho_cliente_id}";
                      
         if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$resultado->EOF)
        {
            $datos = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        
        $sql = " DELETE FROM 
                        tmp_detalles_notas_credito_despachos_clientes 
                    WHERE 
                        tmp_detalle_nota_credito_despacho_cliente_id = " . $tmp_detalle_nota_credito_despacho_cliente_id . " ";
        //echo $sql."<br><br><br>";
        if (!$resultado = $this->ConexionBaseDatos($sql))
        {
            $cad = "Operacion Invalida";
            return false;
        }
        
                
        //borrar la cabecera si el registro del detalle era solo uno 
        if(!empty($datos) && $datos["total"] <= 1){
            $this->eleminarNotaConcepto($datos["tmp_nota_credito_despacho_cliente_id"]);
        }


        $resultado->Close();
        return true;
    }
    
    
    function eleminarNotaConcepto($tmp_detalle_nota_credito_despacho_cliente_id){
        $sql = " DELETE FROM 
                        tmp_notas_credito_despachos_clientes
                    WHERE 
                        tmp_nota_credito_despacho_cliente_id = " . $tmp_detalle_nota_credito_despacho_cliente_id . " ";
        //echo $sql."<br><br><br>";
        if (!$resultado = $this->ConexionBaseDatos($sql))
        {
            $cad = "Operacion Invalida";
            return false;
        }
        $resultado->Close();
        return true;
    }
    
    
    function EliminarDetalleNotaDebitoTemporal($tmp_detalle_nota_debito_despacho_cliente_id)
    {
        $sql = " DELETE FROM 
                        tmp_detalles_notas_debito_despachos_clientes 
                    WHERE 
                        tmp_detalle_nota_debito_despacho_cliente_id = " . $tmp_detalle_nota_debito_despacho_cliente_id . " ";
        //echo $sql."<br><br><br>";
        if (!$resultado = $this->ConexionBaseDatos($sql))
        {
            $cad = "Operacion Invalida";
            return false;
        }
        $resultado->Close();
        return true;
    }

    function GuardarNotaCredito($nota_credito_despacho_cliente_id, $tipo_factura = "0")
    {
        $tabla = "notas_credito_despachos_clientes";
        if($tipo_factura == '1'){
            $tabla  = "notas_credito_despachos_clientes_agrupados";
        }
         
        
        $concepto = $this->obtenerConceptoNotaTemporal($nota_credito_despacho_cliente_id);
        
        if(is_null($concepto) || $concepto["concepto_id"] == 1){
             $sql = "INSERT INTO {$tabla}(
                        SELECT nextval('notas_credito_despachos_clien_nota_credito_despacho_cliente_seq'::regclass) AS nota_credito_despacho_cliente_id, empresa_id, factura_fiscal, prefijo, empresa_id_devolucion, prefijo_devolucion, numero_devolucion, (SELECT SUM(valor) FROM tmp_detalles_notas_credito_despachos_clientes WHERE tmp_nota_credito_despacho_cliente_id = " . $nota_credito_despacho_cliente_id . ") AS valor, tipo, " . UserGetUID() . " AS usuario_id, NOW(), concepto_id
                        FROM tmp_notas_credito_despachos_clientes 
                        WHERE tmp_nota_credito_despacho_cliente_id = " . $nota_credito_despacho_cliente_id . "
                    ) RETURNING nota_credito_despacho_cliente_id";
        } else {
            $sql = "INSERT INTO {$tabla}(
                        SELECT nextval('notas_credito_despachos_clien_nota_credito_despacho_cliente_seq'::regclass) AS nota_credito_despacho_cliente_id, empresa_id, factura_fiscal, prefijo, empresa_id_devolucion, prefijo_devolucion, numero_devolucion,valor_nota AS valor, tipo, " . UserGetUID() . " AS usuario_id, NOW(), concepto_id, descripcion
                        FROM tmp_notas_credito_despachos_clientes 
                        WHERE tmp_nota_credito_despacho_cliente_id = " . $nota_credito_despacho_cliente_id . "
                    ) RETURNING nota_credito_despacho_cliente_id";
        }

           
        
    // echo $sql."<br><br><br>";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }
    
    
    function obtenerConceptoNotaTemporal($nota){
        $sql = "SELECT concepto_id FROM tmp_notas_credito_despachos_clientes WHERE tmp_nota_credito_despacho_cliente_id = {$nota}";
        
    //  echo $sql."<br><br><br>";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

    function GuardarNotaDebito($nota_debito_despacho_cliente_id, $tipo_factura = '0')
    {
        
        $tabla = "notas_debito_despachos_clientes";
        
        if($tipo_factura  == '1'){
            $tabla = "notas_debito_despachos_clientes_agrupados";
        }
        
        $sql = "INSERT INTO {$tabla}(
                        SELECT nextval('notas_debito_despachos_client_nota_debito_despacho_cliente__seq'::regclass) AS nota_debito_despacho_cliente_id, empresa_id, factura_fiscal, prefijo, (SELECT SUM(valor) FROM tmp_detalles_notas_debito_despachos_clientes WHERE tmp_nota_debito_despacho_cliente_id = " . $nota_debito_despacho_cliente_id . ") AS valor, " . UserGetUID() . " AS usuario_id
                        FROM tmp_notas_debito_despachos_clientes 
                        WHERE tmp_nota_debito_despacho_cliente_id = " . $nota_debito_despacho_cliente_id . "
                    ) RETURNING nota_debito_despacho_cliente_id";
        //echo $sql."<br><br><br>";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

    function GuardarDetalleNotaCredito($nota_credito_despacho_cliente_id, $tmp_nota_credito_despacho_cliente_id, $tipo_factura = '0')
    {
        $tabla = "detalles_notas_credito_despachos_clientes";
        if($tipo_factura == '1'){
           $tabla = "detalles_notas_credito_despachos_clientes_agrupados";
        } 
        
        $sql = "INSERT INTO {$tabla}(
                        SELECT nextval('detalles_notas_credito_despac_detalle_nota_credito_despacho_seq'::regclass) AS detalle_nota_credito_despacho_cliente_id, " . $nota_credito_despacho_cliente_id . " AS nota_credito_despacho_cliente_id, item_id, valor, observacion, valor_iva, valor_rtf, valor_ica, movimiento_id  
                        FROM tmp_detalles_notas_credito_despachos_clientes  
                        WHERE tmp_nota_credito_despacho_cliente_id = " . $tmp_nota_credito_despacho_cliente_id . "
                    )";
        
        //echo $sql."<br><br><br>";
        if (!$resultado = $this->ConexionBaseDatos($sql))
        {
            $cad = "Operacion Invalida";
            return false;
        }
        $resultado->Close();
        return true;
    }

    function GuardarDetalleNotaDebito($nota_debito_despacho_cliente_id, $tmp_nota_debito_despacho_cliente_id, $tipo_factura)
    {
        
        $tabla = "detalles_notas_debito_despachos_clientes";
        
        if($tipo_factura == '1'){
            $tabla = "detalles_notas_debito_despachos_clientes_agrupados";
        }
        
        $sql = "INSERT INTO {$tabla}(
                        SELECT nextval('detalles_notas_debito_despach_detalle_nota_debito_despacho__seq'::regclass) AS detalle_nota_debito_despacho_cliente_id, " . $nota_debito_despacho_cliente_id . " AS nota_debito_despacho_cliente_id, item_id, valor, observacion, valor_iva, valor_rtf, valor_ica 
                        FROM tmp_detalles_notas_debito_despachos_clientes 
                        WHERE tmp_nota_debito_despacho_cliente_id = " . $tmp_nota_debito_despacho_cliente_id . "
                    )";
        //echo $sql."<br><br><br>";
        if (!$resultado = $this->ConexionBaseDatos($sql))
        {
            $cad = "Operacion Invalida";
            return false;
        }
        $resultado->Close();
        return true;
    }

    function ObtenerValorTotalNotaCredito($nota_credito_despacho_cliente_id, $tipo_factura = '0')
    {
        
        $concepto_nota = $this->obtenerConceptoPorNota($nota_credito_despacho_cliente_id);
        
        
        
      if(is_null($concepto_nota) || $concepto_nota["id"] == 1){
                $tabla = "detalles_notas_credito_despachos_clientes";
                if($tipo_factura == '1'){
                    $tabla = "detalles_notas_credito_despachos_clientes_agrupados";
                } 

                $sql = "SELECT 
                                SUM(valor) AS valor_total, SUM(valor_iva) AS valor_total_iva, SUM(valor_rtf) AS valor_total_rtf, SUM(valor_ica) AS valor_total_ica 
                            FROM 
                                {$tabla}
                            WHERE 
                                nota_credito_despacho_cliente_id = " . $nota_credito_despacho_cliente_id . " 
                            GROUP BY 
                                nota_credito_despacho_cliente_id ";

       } else {
            $sql = "SELECT 
                               valor AS valor_total, 0 AS valor_total_iva, 0 AS valor_total_rtf, 0  AS valor_total_ica 
                            FROM 
                                notas_credito_despachos_clientes
                            WHERE 
                                	nota_credito_despacho_cliente_id = {$nota_credito_despacho_cliente_id}
                             
                             UNION ALL 
                             
                             SELECT 
                               valor AS valor_total, 0 AS valor_total_iva, 0 AS valor_total_rtf, 0  AS valor_total_ica 
                            FROM 
                                notas_credito_despachos_clientes_agrupados
                            WHERE 
                                	nota_credito_despacho_cliente_id = {$nota_credito_despacho_cliente_id}";
                                            
        }
        
        //echo $sql."<br>";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }
    
    function obtenerConceptoPorNota($nota_id){
        
        $sql = "SELECT a.concepto_id as id, b.descripcion, b.naturaleza, b.cuenta FROM notas_credito_despachos_clientes a
                   INNER JOIN  concepto_nota b ON b.id = a.concepto_id
                   WHERE a.nota_credito_despacho_cliente_id  = {$nota_id}
                   
                   UNION
                   
                   SELECT a.concepto_id as id, b.descripcion, b.naturaleza, b.cuenta FROM notas_credito_despachos_clientes_agrupados a
                   INNER JOIN  concepto_nota b ON b.id = a.concepto_id
                   WHERE a.nota_credito_despacho_cliente_id  = {$nota_id}
                   ";
                   
                   
           if (!$rst = $this->ConexionBaseDatos($sql)){
               return false;
           }
           
           //echo "<pre>".$sql."</pre>";
           
            $datos = array();
            while (!$rst->EOF)
            {
                $datos = $rst->GetRowAssoc($ToUpper = false);
                $rst->MoveNext();
            }
            $rst->Close();
            
            if(count($datos) == 0){
                $datos  = null;
            }

            return $datos;
    }

    function ObtenerValorTotalNotaDebito($nota_debito_despacho_cliente_id, $tipo_factura = '0')
    {
        $tabla = "detalles_notas_debito_despachos_clientes";
        if($tipo_factura == '1'){
            $tabla = "detalles_notas_debito_despachos_clientes_agrupados";
        }
        
        $sql = "SELECT 
                        SUM(valor) AS valor_total, SUM(valor_iva) AS valor_total_iva, SUM(valor_rtf) AS valor_total_rtf, SUM(valor_ica) AS valor_total_ica 
                    FROM 
                        {$tabla} 
                    WHERE 
                        nota_debito_despacho_cliente_id = " . $nota_debito_despacho_cliente_id . " 
                    GROUP BY 
                        nota_debito_despacho_cliente_id ";
        //echo $sql."<br><br><br>";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

    function ActualizarFacturaNotaCredito($valor_notacredito, $empresa_id, $factura_fiscal, $prefijo, $tipo = "", $tipo_factura = '0')
    {

        $valornota = $valor_notacredito;

        if ($tipo == "DEVOLUCIÓN")
        {
            //ya se descuenta en la devolucion decumentos bodega
            $valornota = 0;
        }
        
        $tabla = "inv_facturas_despacho";
        
        if($tipo_factura == '1'){
            $tabla = "inv_facturas_agrupadas_despacho";
        } 
        
        $sql = "UPDATE {$tabla} 
                  SET valor_notacredito = (valor_notacredito + " . $valor_notacredito . " ),
                          saldo = (saldo - " . $valornota . ")
                  WHERE empresa_id = '" . $empresa_id . "' 
                      AND factura_fiscal = " . $factura_fiscal . " 
                      AND prefijo = '" . $prefijo . "'";

        
        //echo $sql."<br>";
        if (!$resultado = $this->ConexionBaseDatos($sql))
        {
            $cad = "Operacion Invalida";
            return false;
        }
        $resultado->Close();
        return true;
    }

    function ActualizarFacturaNotaDebito($valor_notadebito, $empresa_id, $factura_fiscal, $prefijo, $tipo_factura = "0")
    {
        $tabla = "inv_facturas_despacho";
        
        if($tipo_factura == '1'){
            $tabla = "inv_facturas_agrupadas_despacho";
        }
        
        $sql = "UPDATE {$tabla} 
                    SET valor_notadebito = (valor_notadebito + " . $valor_notadebito . " ),
                            saldo = (saldo + " . $valor_notadebito . ")
                    WHERE empresa_id = '" . $empresa_id . "' 
                        AND factura_fiscal = " . $factura_fiscal . " 
                        AND prefijo = '" . $prefijo . "'";
        //echo $sql."<br>";
        if (!$resultado = $this->ConexionBaseDatos($sql))
        {
            $cad = "Operacion Invalida";
            return false;
        }
        $resultado->Close();
        return true;
    }

    function EliminarDetallesNotasCreditoTemporal($tmp_nota_credito_despacho_cliente_id)
    {
        $sql = " DELETE FROM 
                        tmp_detalles_notas_credito_despachos_clientes  
                    WHERE 
                        tmp_nota_credito_despacho_cliente_id = " . $tmp_nota_credito_despacho_cliente_id . " ";
        //echo $sql."<br><br><br>";
        if (!$resultado = $this->ConexionBaseDatos($sql))
        {
            $cad = "Operacion Invalida";
            return false;
        }
        $resultado->Close();
        return true;
    }

    function EliminarDetallesNotasDebitoTemporal($tmp_nota_debito_despacho_cliente_id)
    {
        $sql = " DELETE FROM 
                        tmp_detalles_notas_debito_despachos_clientes  
                    WHERE 
                        tmp_nota_debito_despacho_cliente_id = " . $tmp_nota_debito_despacho_cliente_id . " ";
        //echo $sql."<br><br><br>";
        if (!$resultado = $this->ConexionBaseDatos($sql))
        {
            $cad = "Operacion Invalida";
            return false;
        }
        $resultado->Close();
        return true;
    }

    function EliminarNotaCreditoTemporal($tmp_nota_credito_despacho_cliente_id)
    {
        $sql = "DELETE FROM tmp_notas_credito_despachos_clientes  
                    WHERE tmp_nota_credito_despacho_cliente_id = " . $tmp_nota_credito_despacho_cliente_id . "";
        //echo $sql."<br><br><br>";
        if (!$resultado = $this->ConexionBaseDatos($sql))
        {
            $cad = "Operacion Invalida";
            return false;
        }
        $resultado->Close();
        return true;
    }

    function EliminarNotaDebitoTemporal($tmp_nota_debito_despacho_cliente_id)
    {
        $sql = "DELETE FROM tmp_notas_debito_despachos_clientes  
                    WHERE tmp_nota_debito_despacho_cliente_id = " . $tmp_nota_debito_despacho_cliente_id . "";
        //echo $sql."<br><br><br>";
        if (!$resultado = $this->ConexionBaseDatos($sql))
        {
            $cad = "Operacion Invalida";
            return false;
        }
        $resultado->Close();
        return true;
    }

    function ObtenerDetalleNotaCredito($nota_credito_despacho_cliente_id, $tipo_factura = '0')
    {
        /*$sql = "SELECT 
                        dncdc.detalle_nota_credito_despacho_cliente_id, ifdd.codigo_producto, fc_descripcion_producto(ifdd.codigo_producto) AS descripcion, ibmd.cantidad AS cantidad_devuelta, ifdd.item_id, dncdc.valor 
                    FROM 
                        detalles_notas_credito_despachos_clientes dncdc 
                        INNER JOIN inv_facturas_despacho_d ifdd ON (dncdc.item_id = ifdd.item_id) 
                        LEFT OUTER JOIN inv_bodegas_movimiento_d ibmd ON (dncdc.movimiento_id = ibmd.movimiento_id) 
                    WHERE 
                        dncdc.nota_credito_despacho_cliente_id = " . $nota_credito_despacho_cliente_id . " ";*/
        
        $tabladetalle = "detalles_notas_credito_despachos_clientes";
        $tablafacturadetalle = "inv_facturas_despacho_d";
        
        if($tipo_factura == '1'){
            $tabladetalle = "detalles_notas_credito_despachos_clientes_agrupados";
            $tablafacturadetalle = "inv_facturas_agrupadas_despacho_d";
        }
        
        $sql = "SELECT dncdc.detalle_nota_credito_despacho_cliente_id, ifdd.codigo_producto, fc_descripcion_producto(ifdd.codigo_producto) AS descripcion,
                    ibmd.cantidad AS cantidad_devuelta, ifdd.item_id, dncdc.valor, b.sw_medicamento, b.sw_insumos, dncdc.valor_iva, dncdc.valor_rtf,  dncdc.valor_ica, c.costo, (ifdd.cantidad_devuelta * c.costo) as total_costo, dncdc.observacion
                     FROM {$tabladetalle} dncdc 
                     INNER JOIN {$tablafacturadetalle} ifdd ON (dncdc.item_id = ifdd.item_id)
                      LEFT OUTER JOIN inv_bodegas_movimiento_d ibmd ON (dncdc.movimiento_id = ibmd.movimiento_id) 
                     INNER JOIN inventarios_productos a ON ifdd.codigo_producto = a.codigo_producto
                     INNER JOIN inv_grupos_inventarios b ON a.grupo_id = b.grupo_id
                     INNER JOIN inventarios c  ON c.codigo_producto = ifdd.codigo_producto AND ifdd.empresa_id = c.empresa_id
                      WHERE dncdc.nota_credito_despacho_cliente_id =  " . $nota_credito_despacho_cliente_id . " ";
        
      //  echo $sql."<br><br><br>";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

    function ObtenerDetalleNotaDebito($nota_debito_despacho_cliente_id, $tipo_factura = '0')
    {
        /*$sql = "SELECT 
                        dnddc.detalle_nota_debito_despacho_cliente_id, ifdd.codigo_producto, fc_descripcion_producto(ifdd.codigo_producto) AS descripcion, ifdd.item_id, dnddc.valor 
                    FROM 
                        detalles_notas_debito_despachos_clientes dnddc 
                        INNER JOIN inv_facturas_despacho_d ifdd ON (dnddc.item_id = ifdd.item_id) 
                    WHERE 
                        dnddc.nota_debito_despacho_cliente_id = " . $nota_debito_despacho_cliente_id . " ";*/
         $tabla = "detalles_notas_debito_despachos_clientes";           
         $factura = "inv_facturas_despacho_d";
        if($tipo_factura == '1'){
            $tabla = "detalles_notas_debito_despachos_clientes_agrupados";
             $factura = "inv_facturas_agrupadas_despacho_d";
        }
         $sql = "SELECT 
                        dnddc.detalle_nota_debito_despacho_cliente_id, ifdd.codigo_producto, fc_descripcion_producto(ifdd.codigo_producto) AS descripcion, ifdd.item_id, dnddc.valor,
                        b.sw_medicamento, b.sw_insumos, dnddc.valor_iva, dnddc.valor_rtf, dnddc.valor_ica, dnddc.observacion
                    FROM 
                        {$tabla} dnddc 
                        INNER JOIN {$factura} ifdd ON (dnddc.item_id = ifdd.item_id) 
                        INNER JOIN inventarios_productos a ON ifdd.codigo_producto = a.codigo_producto
                        INNER JOIN inv_grupos_inventarios b ON a.grupo_id = b.grupo_id
                    WHERE 
                        dnddc.nota_debito_despacho_cliente_id = " . $nota_debito_despacho_cliente_id . " ";
        
       
        //echo $sql."<br><br><br>";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

    function ObtenerInformacionTerceroFacturaNotaCredito($nota_credito_despacho_cliente_id, $tipo_factura = '0')
    {   
        
        $tablanota = "notas_credito_despachos_clientes";
        $tablafactura = "inv_facturas_despacho";
        
        if($tipo_factura == "1"){
            $tablanota = "notas_credito_despachos_clientes_agrupados";
             $tablafactura = "inv_facturas_agrupadas_despacho";
        }
        
            $sql = "SELECT 
                        ncdc.tipo, CAST(ifd.fecha_registro AS date) AS fecha_registro, CAST(ncdc.fecha_registro AS date) AS fecha_registro_nota , t.tipo_id_tercero, t.tercero_id, t.direccion, t.telefono, t.nombre_tercero, ifd.factura_fiscal, ifd.prefijo, to_char(ifd.fecha_registro, 'yyyy') as anio_factura,ifd.porcentaje_rtf, ifd.porcentaje_reteiva, ifd.empresa_id, ifd.porcentaje_ica, ncdc.concepto_id, c.descripcion as descripcion_concepto, ncdc.descripcion AS descripcion_nota
                    FROM 
                        {$tablanota} ncdc 
                        INNER JOIN {$tablafactura} ifd ON (ncdc.empresa_id = ifd.empresa_id AND ncdc.factura_fiscal = ifd.factura_fiscal AND ncdc.prefijo = ifd.prefijo) 
                        INNER JOIN terceros t ON (ifd.tipo_id_tercero = t.tipo_id_tercero AND ifd.tercero_id = t.tercero_id) 
                        LEFT JOIN concepto_nota c ON (ncdc.concepto_id = c.id)
                    WHERE 
                        ncdc.nota_credito_despacho_cliente_id = " . $nota_credito_despacho_cliente_id . " ";
        
        //echo $sql."<br><br><br>";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

    function ObtenerInformacionTerceroFacturaNotaDebito($nota_debito_despacho_cliente_id, $tipo_factura = '0')
    {
        
        $tabla = "notas_debito_despachos_clientes";
        $tablafactura = "inv_facturas_despacho";
        
        if($tipo_factura == '1'){
            $tabla = "notas_debito_despachos_clientes_agrupados";
            $tablafactura = "inv_facturas_agrupadas_despacho";
        }
        
        $sql = "SELECT 
                        CAST(ifd.fecha_registro AS date) AS fecha_registro, CAST(nddc.fecha_registro AS date) AS fecha_registro_nota, t.tipo_id_tercero, t.tercero_id, t.direccion, t.telefono, t.nombre_tercero, ifd.factura_fiscal, ifd.prefijo, to_char(ifd.fecha_registro, 'yyyy') as anio_factura,ifd.porcentaje_rtf, ifd.porcentaje_reteiva, ifd.empresa_id, ifd.porcentaje_ica
                    FROM 
                        {$tabla} nddc 
                        INNER JOIN {$tablafactura} ifd ON (nddc.empresa_id = ifd.empresa_id AND nddc.factura_fiscal = ifd.factura_fiscal AND nddc.prefijo = ifd.prefijo) 
                        INNER JOIN terceros t ON (ifd.tipo_id_tercero = t.tipo_id_tercero AND ifd.tercero_id = t.tercero_id) 
                    WHERE 
                        nddc.nota_debito_despacho_cliente_id = " . $nota_debito_despacho_cliente_id . " ";
        //echo $sql."<br><br><br>";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

    function ObtenerNotasCredito($factura, $empresa_id)
    {
       
        $sql = "
                     SELECT 
                    ifd.factura_fiscal, ifd.prefijo, t.tipo_id_tercero, t.tercero_id, t.nombre_tercero, ifd.fecha_registro, ifd.valor_total, ifd.saldo, /*ibmdc.numero*/ ncdc.nota_credito_despacho_cliente_id, ncdc.valor AS valor_nota, ncdc.tipo AS tipo_nota, ncdc.fecha_registro AS fecha_registro_nota, 0 as tipo_factura, a.estado, case when a.estado=0 then 'Sincronizado' else 'NO sincronizado' end as descripcion_estado, ncdc.nota_credito_despacho_cliente_id, b.descripcion as descripcion_concepto
                FROM 
                    inv_facturas_despacho ifd
                    INNER JOIN terceros t ON (ifd.tipo_id_tercero = t.tipo_id_tercero AND ifd.tercero_id = t.tercero_id)
                    INNER JOIN notas_credito_despachos_clientes ncdc ON (ifd.empresa_id = ncdc.empresa_id AND ifd.factura_fiscal = ncdc.factura_fiscal AND ifd.prefijo = ncdc.prefijo) 
                    LEFT JOIN concepto_nota b ON ncdc.concepto_id = b.id
                    LEFT JOIN logs_facturacion_clientes_ws_fi a ON
                     a.factura_fiscal =  ifd.factura_fiscal AND a.prefijo = ifd.prefijo AND ncdc.nota_credito_despacho_cliente_id = a.numero_nota
                WHERE 
                    ifd.factura_fiscal = " . $factura . "
                      OR ncdc.nota_credito_despacho_cliente_id = ".$factura."
                     AND ifd.empresa_id = '" . $empresa_id . "'


                UNION ALL 


                SELECT 
                    ifd.factura_fiscal, ifd.prefijo, t.tipo_id_tercero, t.tercero_id, t.nombre_tercero, ifd.fecha_registro, ifd.valor_total, ifd.saldo, /*ibmdc.numero*/ ncdc.nota_credito_despacho_cliente_id, ncdc.valor AS valor_nota, ncdc.tipo AS tipo_nota, ncdc.fecha_registro AS fecha_registro_nota, 1 as tipo_factura, a.estado, case when a.estado=0 then 'Sincronizado' else 'NO sincronizado' end as descripcion_estado, ncdc.nota_credito_despacho_cliente_id, b.descripcion as descripcion_concepto
                FROM 
                    inv_facturas_agrupadas_despacho ifd
                    INNER JOIN terceros t ON (ifd.tipo_id_tercero = t.tipo_id_tercero AND ifd.tercero_id = t.tercero_id)
                    INNER JOIN notas_credito_despachos_clientes_agrupados ncdc ON (ifd.empresa_id = ncdc.empresa_id AND ifd.factura_fiscal = ncdc.factura_fiscal AND ifd.prefijo = ncdc.prefijo) 
                     LEFT JOIN concepto_nota b ON ncdc.concepto_id = b.id
                    LEFT JOIN logs_facturacion_clientes_ws_fi a ON a.factura_fiscal =  ifd.factura_fiscal AND a.prefijo = ifd.prefijo AND ncdc.nota_credito_despacho_cliente_id = a.numero_nota 
                WHERE 
                     ifd.factura_fiscal = " . $factura . "
                      OR ncdc.nota_credito_despacho_cliente_id = ".$factura."
                     AND ifd.empresa_id = '" . $empresa_id . "'

                ORDER BY 4";
      //echo "<pre>".$sql."</pre><br>";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

    function ObtenerNotasDebito($factura, $empresa_id)
    {
        $sql = "SELECT 
                        ifd.factura_fiscal, ifd.prefijo, t.tipo_id_tercero, t.tercero_id, t.nombre_tercero, ifd.fecha_registro, ifd.valor_total, ifd.saldo, nddc.nota_debito_despacho_cliente_id, nddc.valor AS valor_nota, nddc.fecha_registro AS fecha_registro_nota, 0 as tipo_factura,
                         a.estado, case when a.estado=0 then 'Sincronizado' else 'NO sincronizado' end as descripcion_estado, nddc.nota_debito_despacho_cliente_id
                    FROM 
						(
						   select estado, factura_fiscal, prefijo, numero_nota from logs_facturacion_clientes_ws_fi
						   where estado = '0' limit 1
						 
						) as a ,
                        inv_facturas_despacho ifd
                        INNER JOIN terceros t ON (ifd.tipo_id_tercero = t.tipo_id_tercero AND ifd.tercero_id = t.tercero_id)
                        INNER JOIN notas_debito_despachos_clientes nddc ON (ifd.empresa_id = nddc.empresa_id AND ifd.factura_fiscal = nddc.factura_fiscal AND ifd.prefijo = nddc.prefijo) 
                        -- LEFT JOIN logs_facturacion_clientes_ws_fi a ON  a.factura_fiscal =  ifd.factura_fiscal AND a.prefijo = ifd.prefijo AND nddc.nota_debito_despacho_cliente_id = a.numero_nota
                    WHERE 
                        ifd.factura_fiscal = " . $factura . "
                        OR nddc.nota_debito_despacho_cliente_id = ".$factura."
                        AND ifd.empresa_id = '" . $empresa_id . "'
                     
                    UNION ALL
                    
                    SELECT 
                    ifd.factura_fiscal, ifd.prefijo, t.tipo_id_tercero, t.tercero_id, t.nombre_tercero, ifd.fecha_registro, ifd.valor_total, ifd.saldo, nddc.nota_debito_despacho_cliente_id, nddc.valor AS valor_nota, nddc.fecha_registro AS fecha_registro_nota, 1 as tipo_factura,
                    a.estado, case when a.estado=0 then 'Sincronizado' else 'NO sincronizado' end as descripcion_estado, nddc.nota_debito_despacho_cliente_id
                    FROM 
					(
                       select estado, factura_fiscal, prefijo, numero_nota from logs_facturacion_clientes_ws_fi
                       where estado = '0' limit 1
                     
                    ) as a ,
                    inv_facturas_agrupadas_despacho ifd
                    INNER JOIN terceros t ON (ifd.tipo_id_tercero = t.tipo_id_tercero AND ifd.tercero_id = t.tercero_id)
                    INNER JOIN notas_debito_despachos_clientes_agrupados nddc ON (ifd.empresa_id = nddc.empresa_id AND ifd.factura_fiscal = nddc.factura_fiscal AND ifd.prefijo = nddc.prefijo) 
                    -- LEFT JOIN logs_facturacion_clientes_ws_fi a ON a.factura_fiscal =  ifd.factura_fiscal AND a.prefijo = ifd.prefijo AND nddc.nota_debito_despacho_cliente_id = a.numero_nota
                    WHERE 
                    ifd.factura_fiscal = " . $factura . "
                    OR nddc.nota_debito_despacho_cliente_id = ".$factura."
                    AND ifd.empresa_id = '" . $empresa_id . "'
                        
                    ORDER BY nombre_tercero";
      // echo "<pre>".$sql."</pre><br>";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }
    
    
    function obtenerEncabezadoNotaCreditoCliente($id_nota){
        $sql = "SELECT a. *, b.tipo_id_tercero, b.tercero_id,  to_char(b.fecha_registro, 'yyyy') as anio_factura, b.prefijo, b.factura_fiscal, 0 as tipo_factura, to_char(b.fecha_registro, 'YYYY') as fecha_registro, b.porcentaje_rtf, b.porcentaje_reteiva, b.porcentaje_ica,
                    to_char(a.fecha_registro, 'dd/mm/yyyy') as fecha_nota
                    FROM notas_credito_despachos_clientes a
                    INNER JOIN inv_facturas_despacho b ON
                    a.factura_fiscal = b.factura_fiscal AND a.prefijo = b.prefijo AND a.empresa_id = b.empresa_id
                    WHERE nota_credito_despacho_cliente_id = '".$id_nota."'
                        
                    UNION ALL
                    
                    SELECT a. *, b.tipo_id_tercero, b.tercero_id,  to_char(b.fecha_registro, 'yyyy') as anio_factura, b.prefijo, b.factura_fiscal, 1 as tipo_factura, to_char(b.fecha_registro, 'YYYY') as fecha_registro, b.porcentaje_rtf, b.porcentaje_reteiva, b.porcentaje_ica,
                    to_char(a.fecha_registro, 'dd/mm/yyyy') as fecha_nota
                    FROM notas_credito_despachos_clientes_agrupados a
                    INNER JOIN inv_facturas_agrupadas_despacho b ON
                    a.factura_fiscal = b.factura_fiscal AND a.prefijo = b.prefijo AND a.empresa_id = b.empresa_id
                    WHERE nota_credito_despacho_cliente_id = '".$id_nota."'";
       //echo "<pre>". $sql."</pre>";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }
    
    function ObtenerConceptos(){
        $sql = "SELECT * FROM concepto_nota WHERE sw_mostrar = '1' ORDER BY id ASC";
         if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }
    
     function ObtenerConceptosPorId($id){
        $sql = "SELECT * FROM concepto_nota WHERE id={$id}";
         if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos= $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }
    
    
     function obtenerEncabezadoNotaDebitoCliente($id_nota){
        $sql = "SELECT a. *, b.tipo_id_tercero, b.tercero_id,  to_char(b.fecha_registro, 'yyyy') as anio_factura, b.prefijo, b.factura_fiscal, 0 as tipo_factura, to_char(b.fecha_registro, 'YYYY') as fecha_registro, b.porcentaje_rtf, b.porcentaje_reteiva, b.porcentaje_ica,
                    to_char(a.fecha_registro, 'dd/mm/yyyy') as fecha_nota
                    FROM notas_debito_despachos_clientes a
                    INNER JOIN inv_facturas_despacho b ON
                    a.factura_fiscal = b.factura_fiscal AND a.prefijo = b.prefijo AND a.empresa_id = b.empresa_id
                    WHERE nota_debito_despacho_cliente_id = '".$id_nota. "'
                        
                    UNION ALL
                    
                    SELECT a. *, b.tipo_id_tercero, b.tercero_id,  to_char(b.fecha_registro, 'yyyy') as anio_factura, b.prefijo, b.factura_fiscal, 1 as tipo_factura, to_char(b.fecha_registro, 'YYYY') as fecha_registro, b.porcentaje_rtf, b.porcentaje_reteiva, b.porcentaje_ica,
                    to_char(a.fecha_registro, 'dd/mm/yyyy') as fecha_nota
                    FROM notas_debito_despachos_clientes_agrupados a
                    INNER JOIN inv_facturas_agrupadas_despacho b ON
                    a.factura_fiscal = b.factura_fiscal AND a.prefijo = b.prefijo AND a.empresa_id = b.empresa_id
                    WHERE nota_debito_despacho_cliente_id = '".$id_nota. "'";
       //echo $sql."<br>";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

}

?>