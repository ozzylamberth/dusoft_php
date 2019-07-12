-- DROP FUNCTION public.bodega_cierre(char, char, char, varchar);

CREATE OR REPLACE FUNCTION public.bodega_cierre
(
   char,
   char,
   char,
   varchar
)
RETURNS boolean AS
$$
DECLARE
  fecha_inicial DATE;
  fecha_final DATE;
  VAR_LAPSO ALIAS FOR $2;
  VAR_EMPRESA ALIAS FOR $1;
  VAR_CENTRO ALIAS FOR $3;
  VAR_BODEGA ALIAS FOR $4;
  VAR_COUNT1 INTEGER;
  VAR_COUNT2 INTEGER;
BEGIN
  fecha_inicial := TO_DATE(SUBSTRING(VAR_LAPSO FROM 1 FOR 4) ||'-' ||SUBSTRING(VAR_LAPSO FROM 5 FOR 2) || '-01','YYYY-MM-DD');
  fecha_final := fecha_inicial +  INTERVAL '1 month';

 
   
  INSERT INTO inv_bodegas_movimiento_cierres_por_lapso
  SELECT  VAR_LAPSO,
          z.empresa_id,
          z.centro_utilidad,
          z.bodega,
          z.codigo_producto ,
          0 as  existencia_inicial,
          z.ingresos,
          z.egresos,
          e.existencia,
          0 as diferencia,
          0 as costo
  FROM    (
            SELECT  x.empresa_id,
                    x.centro_utilidad,
                    x.bodega,
                    x.codigo_producto,
                    SUM(x.ingresos) as ingresos,
                    SUM(x.egresos) as egresos
            FROM    (
                      (
                        SELECT  a.empresa_id,
                                a.centro_utilidad,
                                a.bodega ,
                                b.codigo_producto,
                                CASE WHEN d.inv_tipo_movimiento IN ('E','T')  THEN 0  ELSE b.cantidad END as ingresos,
                                CASE WHEN d.inv_tipo_movimiento IN ('E','T')  THEN b.cantidad  ELSE 0 END as egresos
                        FROM    inv_bodegas_movimiento a,
                                inv_bodegas_movimiento_d b,
                                documentos c,
                                tipos_doc_generales d
                        WHERE   a.empresa_id = VAR_EMPRESA
                        AND     a.bodega = VAR_BODEGA
                        AND     a.fecha_registro::date >= fecha_inicial
                        AND     a.fecha_registro::date < fecha_final
                        AND     b.empresa_id = a.empresa_id
                        AND     b.prefijo = a.prefijo
                        AND     b.numero = a.numero
                        AND     c.documento_id = a.documento_id
                        AND     c.empresa_id = a.empresa_id
                        AND     d.tipo_doc_general_id = c.tipo_doc_general_id
                      )
                      UNION ALL
                      (
                        SELECT  c.empresa_id,
                                c.centro_utilidad,
                                c.bodega,
                                a.codigo_producto,
                                CASE WHEN d.cargo = 'IMD'  THEN 0  ELSE a.cantidad END as ingresos,
                                CASE WHEN d.cargo = 'IMD'  THEN a.cantidad  ELSE 0 END as egresos
                        FROM    bodegas_documentos_d a,
                                bodegas_documentos b,
                                bodegas_doc_numeraciones c,
                                cuentas_detalle d
                        WHERE   c.empresa_id = VAR_EMPRESA
                        AND     c.bodega = VAR_BODEGA
                        AND     b.bodegas_doc_id = c.bodegas_doc_id
                        AND     b.fecha_registro::date >= fecha_inicial
                        AND     b.fecha_registro::date < fecha_final
                        AND     a.bodegas_doc_id = b.bodegas_doc_id
                        AND     a.numeracion = b.numeracion
                        AND     d.consecutivo = a.consecutivo
                        AND     d.empresa_id = VAR_EMPRESA
                      )
                      
                      UNION ALL
                      (
                        SELECT  c.empresa_id,
                        c.centro_utilidad,
                        c.bodega,
                        a.codigo_producto,
                        CASE WHEN c.tipo_movimiento <> 'I'  THEN 0 END as ingresos,
                        CASE WHEN c.tipo_movimiento= 'E'  THEN a.cantidad  ELSE 0 END as egresos
                        FROM    bodegas_documentos_d a,
                        bodegas_documentos b,
                        bodegas_doc_numeraciones c
                        WHERE   c.empresa_id = VAR_EMPRESA
                        AND     c.bodega = VAR_BODEGA
                        AND     b.bodegas_doc_id = c.bodegas_doc_id
                        AND     a.bodegas_doc_id = b.bodegas_doc_id
                        AND     a.numeracion = b.numeracion
                        AND     a.consecutivo NOT IN
                        (SELECT consecutivo
                        FROM cuentas_detalle
                        WHERE TRUE
                        AND     empresa_id = VAR_EMPRESA
                        AND        consecutivo = a.consecutivo
                        )                                                  
                        AND     b.fecha_registro::date >= fecha_inicial
                        AND     b.fecha_registro::date < fecha_final
                      )
                      UNION ALL
                      (
                        SELECT  t.empresa_id ,
                                t.centro_utilidad_destino as centro_utilidad,
                                t.bodega_destino as bodega,
                                b.codigo_producto,
                                b.cantidad as ingresos,
                                0 as egresos
                        FROM    inv_bodegas_movimiento_traslados t,
                                inv_bodegas_movimiento a,
                                inv_bodegas_movimiento_d b
                        WHERE   a.empresa_id = VAR_EMPRESA
                        AND     a.fecha_registro::date >= fecha_inicial
                        AND     a.fecha_registro::date < fecha_final
                        AND     a.empresa_id = t.empresa_id
                        AND     a.prefijo = t.prefijo
                        AND     a.numero = t.numero
                        AND     t.bodega_destino = VAR_BODEGA
                        AND     b.empresa_id = a.empresa_id
                        AND     b.prefijo = a.prefijo
                        AND     b.numero = a.numero
                      )
                      UNION ALL
                      (
                        SELECT  empresa_id,
                                centro_utilidad,
                                bodega,
                                codigo_producto,
                                0 as ingresos,
                                0 as egresos
                        FROM    existencias_bodegas
                        WHERE   empresa_id = VAR_EMPRESA
                        AND     bodega = VAR_BODEGA                       
                      )
                    ) AS x
            GROUP BY 1,2,3,4
          ) z,
          existencias_bodegas e
  WHERE   e.empresa_id = z.empresa_id
  AND     e.centro_utilidad = z.centro_utilidad
  AND     e.bodega = z.bodega
  AND     e.codigo_producto = z.codigo_producto;

  RETURN TRUE;
END;
$$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY DEFINER;

ALTER FUNCTION public.bodega_cierre(char, char, char, varchar)
  OWNER TO "admin";