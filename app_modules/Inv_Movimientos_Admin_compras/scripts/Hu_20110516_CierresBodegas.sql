ALTER TABLE bodegas ADD COLUMN lapso_cerrar CHARACTER(6);
COMMENT ON COLUMN bodegas.lapso_cerrar IS 'Valor del lapso que aun no ha tenido cierre en la bodega';
ALTER TABLE bodegas ADD COLUMN lapso_cerrado CHARACTER(6);
COMMENT ON COLUMN bodegas.lapso_cerrado IS 'Valor del ultimo lapso cerrado';

DROP FUNCTION public.generar_inv_bodegas_movimiento_costo_por_lapso(char, char, integer, integer, char, varchar);
DROP FUNCTION public.generar_inv_bodegas_movimiento_costo_por_lapso(char, char, integer, integer);
DROP FUNCTION public.bodega_cierre_existencias(char, char, varchar);


ALTER TABLE inv_bodegas_movimiento_costo_por_lapso_d ALTER COLUMN movimiento_id SET DEFAULT nextval('inv_bodegas_movimiento_costo_por_lapso_d_movimiento_id_seq'::regclass);
ALTER TABLE inv_bodegas_movimiento_costo_por_lapso_d ALTER COLUMN clase_id TYPE CHARACTER VARYING(4);
ALTER TABLE inv_bodegas_movimiento_costo_por_lapso_d ALTER COLUMN subclase_id TYPE CHARACTER VARYING(10);

ALTER TABLE inv_bodegas_movimiento_costo_por_lapso ADD COLUMN centro_utilidad CHARACTER(2);
ALTER TABLE inv_bodegas_movimiento_costo_por_lapso ADD COLUMN bodega CHARACTER VARYING(2);

ALTER TABLE inv_bodegas_movimiento_costo_por_lapso ADD FOREIGN KEY(	empresa_id,centro_utilidad,bodega)
REFERENCES bodegas(	empresa_id,centro_utilidad,bodega) ON UPDATE CASCADE ON DELETE RESTRICT; 

COMMENT ON COLUMN inv_bodegas_movimiento_costo_por_lapso.centro_utilidad IS '(FK) Referencia al centro de utilidad';
COMMENT ON COLUMN inv_bodegas_movimiento_costo_por_lapso.bodega IS '(FK) Referencia a la bodega';

ALTER TABLE inv_bodegas_movimiento_costo_por_lapso DROP CONSTRAINT inv_bodegas_movimiento_costo_por_lapso_empresa_id_key;
ALTER TABLE inv_bodegas_movimiento_costo_por_lapso DROP CONSTRAINT inv_bodegas_movimiento_costo_por_lapso_empresa_id_fkey;
ALTER TABLE inv_bodegas_movimiento_costo_por_lapso DROP CONSTRAINT inv_bodegas_movimiento_costo_por_lapso_empresa_id_fkey1;
ALTER TABLE inv_bodegas_movimiento_cierres_por_lapso DROP CONSTRAINT inv_bodegas_movimiento_cierres_por_lapso_empresa_id_fkey1;



CREATE OR REPLACE FUNCTION generar_inv_bodegas_movimiento_costo_por_lapso(character, character, integer, integer, character, character varying) RETURNS VOID AS $$
DECLARE
  FechaInicial DATE;
  FechaFinal DATE;
  FechaDoc DATE;
  VAR_DOC_ID ALIAS FOR $3;
  VAR_EMPRESA ALIAS FOR $1;
  VAR_LAPSO ALIAS FOR $2;
  VAR_USUARIO ALIAS FOR $4;
  VAR_CENTRO ALIAS FOR $5;
  VAR_BODEGA ALIAS FOR $6;
  DATOS_DOC RECORD;
  VAR_COUNT INTEGER;
BEGIN
  SELECT  INTO DATOS_DOC * 
  FROM    documentos 
  WHERE   empresa_id = VAR_EMPRESA 
  AND     documento_id = VAR_DOC_ID;

  IF NOT FOUND THEN
    RAISE EXCEPTION 'EL DOCUMENTO [%] DE LA EMPRESA [%] NO EXISTE.', VAR_DOC_ID, VAR_EMPRESA;
  END IF;

  IF DATOS_DOC.sw_estado != '1' THEN
    RAISE EXCEPTION 'EL DOCUMENTO [%] NO ESTA EN ESTADO ACTIVO', VAR_DOC_ID;
  END IF;

  IF DATOS_DOC.tipo_doc_general_id != 'CV01' THEN
    RAISE EXCEPTION 'EL DOCUMENTO [%] NO PERTENECE AL TIPO GENERAL CV01', DATOS_DOC.tipo_doc_general_id;
  END IF;

  VAR_COUNT := (  SELECT  COUNT(*) 
                  FROM    inv_bodegas_movimiento_costo_por_lapso 
                  WHERE   empresa_id = VAR_EMPRESA 
                  AND     lapso = VAR_LAPSO
                  AND     centro_utilidad = VAR_CENTRO
                  AND     bodega = VAR_BODEGA
                );

  IF VAR_COUNT > 0 THEN
    RAISE EXCEPTION 'EL LAPSO [%] DE LA EMPRESA [%] Y LA BODEGA[%] YA TIENE UN DOCUMENTO DE RESUMEN.', VAR_LAPSO, VAR_EMPRESA, VAR_BODEGA;
  END IF;

  FechaInicial := TO_DATE(SUBSTRING(VAR_LAPSO from 1 for 4) ||'-' ||SUBSTRING(VAR_LAPSO FROM 5 FOR 2) || '-01','YYYY-MM-DD');
  FechaFinal   := FechaInicial +  INTERVAL '1 month';
  FechaDoc     := FechaFinal - INTERVAL '1 day';

  VAR_COUNT := (  SELECT  COUNT(*)
                  FROM    bodegas_documentos BD,
                          bodegas_doc_numeraciones BN
                  WHERE   BD.fecha >= FechaInicial
                  AND     BD.fecha < FechaFinal
                  AND     BD.bodegas_doc_id = BN.bodegas_doc_id
                  AND     BN.empresa_id = VAR_EMPRESA
                  AND     BN.centro_utilidad = VAR_CENTRO
                  AND     BN.bodega = VAR_BODEGA
                );
  IF VAR_COUNT = 0 THEN
    RETURN;
  END IF;
  
  INSERT INTO inv_bodegas_movimiento_costo_por_lapso
  (
    empresa_id,
    centro_utilidad,
    bodega,
    prefijo,
    numero,
    documento_id,
    lapso,
    usuario_id,
    fecha_documento,
    fecha_registro
  )
  VALUES
  (
    VAR_EMPRESA,
    VAR_CENTRO,
    VAR_BODEGA,
    DATOS_DOC.prefijo,
    DATOS_DOC.numeracion,
    VAR_DOC_ID,
    VAR_LAPSO,
    VAR_USUARIO,
    FechaDoc,
    NOW()
  );

  UPDATE  documentos 
  SET     numeracion = numeracion +1
  WHERE   empresa_id = VAR_EMPRESA 
  AND     documento_id = VAR_DOC_ID;

  INSERT INTO inv_bodegas_movimiento_costo_por_lapso_d
  (
    empresa_id,
    prefijo,
    numero,
    centro_utilidad,
    bodega,
    departamento,
    grupo_id,
    clase_id,
    subclase_id,
    codigo_producto,
    cantidad,
    devolucion,
    costo_cargos,
    costo_devoluciones,
    cantidad_total,
    costo_total
  )
  SELECT  BN.empresa_id,
          DATOS_DOC.prefijo AS prefijo,
          DATOS_DOC.numeracion AS numero,
          BN.centro_utilidad,
          BN.bodega,
          A.departamento,
          IV.grupo_id,
          IV.clase_id,
          IV.subclase_id,
          A.codigo_producto,
          A.cantidad,
          A.devolucion,
          A.costo_cargos,
          A.costo_devoluciones,
          (A.cantidad - A.devolucion) as cantidad_total,
          (A.costo_cargos - A.costo_devoluciones) as costo_total
  FROM    (
            SELECT  a.bodegas_doc_id,
                    c.departamento,
                    b.codigo_producto,
                    SUM(CASE WHEN c.cargo = 'IMD'  THEN c.cantidad ELSE 0 END) as cantidad,
                    SUM(CASE WHEN c.cargo = 'DIMD' THEN c.cantidad ELSE 0 END) as devolucion,
                    SUM(CASE WHEN c.cargo = 'IMD'  THEN (b.cantidad * b.total_costo) ELSE 0 END) as costo_cargos,
                    SUM(CASE WHEN c.cargo = 'DIMD'  THEN (b.cantidad * b.total_costo) ELSE 0 END) as costo_devoluciones
            FROM    bodegas_documentos a,
                    bodegas_documentos_d b,
                    cuentas_detalle c
            WHERE   a.fecha >= FechaInicial
            AND     a.fecha < FechaFinal
            AND     a.bodegas_doc_id = b.bodegas_doc_id
            AND     a.numeracion = b.numeracion
            AND     b.consecutivo = c.consecutivo
            AND     c.empresa_id = VAR_EMPRESA
            AND     c.cargo IN ('IMD','DIMD')
            GROUP BY 1,2,3
          ) A,
          bodegas_doc_numeraciones BN,
          inventarios_productos IV
  WHERE   BN.empresa_id = VAR_EMPRESA
  AND     BN.bodegas_doc_id = A.bodegas_doc_id
  AND     BN.centro_utilidad = VAR_CENTRO
  AND     BN.bodega = VAR_BODEGA
  AND     A.codigo_producto = IV.codigo_producto
  ORDER BY  BN.centro_utilidad, BN.bodega, A.departamento,
            IV.grupo_id, IV.clase_id, IV.subclase_id, A.codigo_producto;
  RETURN;
END;
$$ LANGUAGE 'plpgsql' SECURITY DEFINER;

CREATE OR REPLACE FUNCTION bodega_cierre(character, character, character, character varying) RETURNS BOOLEAN AS $$
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
$$ LANGUAGE 'plpgsql' SECURITY DEFINER;

CREATE OR REPLACE FUNCTION bodega_cierre_existencias(character, character, character varying) RETURNS BOOLEAN AS $$
DECLARE
  fecha_inicial DATE;
  fecha_final DATE;
  lapso_anterior CHARACTER(6);
  VAR_LAPSO ALIAS FOR $2;
  VAR_EMPRESA ALIAS FOR $1;
  VAR_BODEGA ALIAS FOR $3;
BEGIN
  fecha_inicial := TO_DATE(SUBSTRING(VAR_LAPSO FROM 1 FOR 4) ||'-' ||SUBSTRING(VAR_LAPSO FROM 5 FOR 2) || '-01','YYYY-MM-DD');
  fecha_final := fecha_inicial -  interval '1 month';
  lapso_anterior := TO_CHAR(fecha_final,'YYYYMM');
  
  UPDATE  inv_bodegas_movimiento_cierres_por_lapso AS a
  SET     existencia_inicial = COALESCE(
            (
              SELECT  b.existencia_final
              FROM    inv_bodegas_movimiento_cierres_por_lapso AS b
              WHERE   b.lapso = lapso_anterior
              AND     b.empresa_id = VAR_EMPRESA
              AND     b.bodega = VAR_BODEGA
              AND     b.empresa_id = a.empresa_id
              AND     b.centro_utilidad = a.centro_utilidad
              AND     b.bodega = a.bodega
              AND     b.codigo_producto = a.codigo_producto
            ),0)
  WHERE   a.lapso = VAR_LAPSO
  AND     a.empresa_id = VAR_EMPRESA
  AND     a.bodega = VAR_BODEGA;
  RETURN TRUE;
END;
$$ LANGUAGE 'plpgsql' SECURITY DEFINER;

CREATE OR REPLACE FUNCTION bodega_cierre_movimiento(character, character, character varying) RETURNS BOOLEAN AS $$
BEGIN  
  UPDATE  inv_bodegas_movimiento_cierres_por_lapso
  SET     existencia_final  = ( existencia_inicial + ingresos - egresos)
  WHERE   lapso = $2
  AND     empresa_id = $1
  AND     bodega = $3;
  RETURN TRUE;
END;
$$ LANGUAGE 'plpgsql' SECURITY DEFINER;
