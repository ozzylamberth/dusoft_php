<?php


class ConsultasSql {

    function ConsultarProductosVentasPublico($fechaInicio,$fechaFinal) {
        $sql = "select             
		    a.empresa_id,
		    a.prefijo, 
                    a.factura_fiscal,
                    b.codigo_producto,
                    e.descripcion as laboratorio,
                    d.descripcion as molecula,
                    fc_descripcion_producto(b.codigo_producto) as descripcion,
                    b.lote,
                    TO_CHAR(b.fecha_vencimiento,'DD/MM/YYYY') AS fecha_vencimiento,
                    b.cantidad:: integer,
                    c.porc_iva,
                    ((b.cantidad * b.total_costo) * (c.porc_iva/100)) as valor_iva,
                    b.total_costo:: integer,
                    (b.cantidad * b.total_costo) as subtotal,
                    ((b.cantidad * b.total_costo) + (b.cantidad * b.total_costo) * (c.porc_iva/100)) as valor_total,
                    f.sw_medicamento,
                    f.sw_insumos,
                    to_char(z.fecha_registro,'DD/MM/YY') as fecha_registro,
                    z.total_abono,
                    (b.cantidad * g.costo) as costo,
                    ('VENTA PARTICULAR MES DE'||' '||upper(to_char(z.fecha_registro, 'TMMonth'))||' -'||' '||to_char(z.fecha_registro,'YYYY')) as observacion
                    from fac_facturas_contado as z
                    inner join facturas_documentos_bodega a on (a.factura_fiscal=z.factura_fiscal and a.prefijo=z.prefijo)
                    inner join bodegas as h on (h.empresa_id=a.empresa_id and bodega='17')
                    inner join bodegas_documentos_d b on a.bodegas_doc_id = b.bodegas_doc_id and a.bodegas_numeracion = b.numeracion
                    inner join inventarios_productos c on b.codigo_producto = c.codigo_producto
                    inner join inventarios as g on (g.codigo_producto = c.codigo_producto and g.empresa_id=z.empresa_id)
                    inner join inv_grupos_inventarios f on c.grupo_id = f.grupo_id
                    inner join inv_subclases_inventarios d on c.grupo_id = d.grupo_id and c.clase_id = d.clase_id and c.subclase_id = d.subclase_id
                    inner join inv_clases_inventarios e on d.grupo_id = e.grupo_id and d.clase_id = e.clase_id
                    where 
                    z.fecha_registro between '".$fechaInicio." 00:00:00' and '".$fechaFinal." 23:59:59' and a.empresa_id='17' and bodega='17';";
//echo "<pre>"; print_r($sql);
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF) {
            $datos[$rst->fields[2]][] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

 
    function UpdateActualizacion($usuario_id, $asunto, $descripcion, $fecha_fin, $actualizacion_id) {
        $hora = '23:59:59.59';
        $sql = "UPDATE actualizaciones SET
                usuario_id= '" . $usuario_id . "',
                asunto= '" . utf8_decode($asunto) . "',
                descripcion='" . $descripcion . "',
                fecha_actu=now(),
                fecha_fin='" . $fecha_fin . ' ' . $hora . "'
                where  actualizacion_id= '" . $actualizacion_id . "'; ";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $rst->Close();
        return true;
    }


    function ConexionBaseDatos($sql) {
        list($dbconn) = GetDBConn();
        $rst = $dbconn->Execute($sql);

        if ($dbconn->ErrorNo() != 0) {
            $this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
            echo "<b class=\"label\">" . $this->frmError['MensajeError'] . "<br> ERROR: " . $sql . "</b>";
            return false;
        }

        return $rst;
    }
}
?>