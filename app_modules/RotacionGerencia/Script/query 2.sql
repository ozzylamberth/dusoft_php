CREATE TABLE inv_rotaciones_detalle
(
  empresa_id CHARACTER(2) NOT NULL,
  centro_utilidad CHARACTER(2) NOT NULL,
  bodega CHARACTER VARYING(2) NOT NULL,
  codigo_producto CHARACTER VARYING(50) NOT NULL,
  fecha DATE NOT NULL,
  tipo_doc_general_id CHARACTER VARYING(4) NOT NULL,
  cantidad_egreso NUMERIC(14,4) NOT NULL,
  cantidad_ingreso NUMERIC(14,4) NOT NULL,
  inv_rotacion_detalle_id SERIAL NOT NULL
); 

ALTER TABLE inv_rotaciones_detalle ADD PRIMARY KEY(inv_rotacion_detalle_id);
ALTER TABLE inv_rotaciones_detalle ADD UNIQUE (empresa_id,centro_utilidad,bodega,codigo_producto,fecha,tipo_doc_general_id);

ALTER TABLE inv_rotaciones_detalle ADD FOREIGN KEY(empresa_id,centro_utilidad,bodega)
REFERENCES bodegas(empresa_id,centro_utilidad,bodega) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE inv_rotaciones_detalle ADD FOREIGN KEY(codigo_producto)
REFERENCES inventarios_productos(codigo_producto) ON UPDATE CASCADE ON DELETE CASCADE;

COMMENT ON TABLE inv_rotaciones_detalle IS 'Tabla donde se almacenan lo datos correspondeientes a la rotacion';
COMMENT ON COLUMN inv_rotaciones_detalle.inv_rotacion_detalle_id IS '(PK) Identificador de la tabla';
COMMENT ON COLUMN inv_rotaciones_detalle.empresa_id IS '(FK) Identificador de la empresa';
COMMENT ON COLUMN inv_rotaciones_detalle.centro_utilidad IS '(FK) Identificador del centro de utilidad';
COMMENT ON COLUMN inv_rotaciones_detalle.bodega IS '(FK) Identificador de la bodega';
COMMENT ON COLUMN inv_rotaciones_detalle.codigo_producto IS '(FK) Identificador del codigo de producto';
COMMENT ON COLUMN inv_rotaciones_detalle.fecha IS 'Fecha de registro del producto';
COMMENT ON COLUMN inv_rotaciones_detalle.tipo_doc_general_id IS 'Tipo de documento';
COMMENT ON COLUMN inv_rotaciones_detalle.cantidad_ingreso IS 'Cantidad ingresada';
COMMENT ON COLUMN inv_rotaciones_detalle.cantidad_egreso IS 'Cantidad egresada';

INSERT INTO inv_rotaciones_detalle
(
SELECT  a.empresa_id,
        a.centro_utilidad,
        a.bodega,
        e.codigo_producto,
        a.fecha_registro::date,
        d.tipo_doc_general_id,
        SUM(CASE WHEN d.inv_tipo_movimiento = 'E' THEN e.cantidad ELSE 0 END) AS cantidad_egreso,
        SUM(CASE WHEN d.inv_tipo_movimiento = 'I' THEN e.cantidad ELSE 0 END) AS cantidad_ingreso
FROM    inv_bodegas_movimiento a,
        inv_bodegas_movimiento_d e,
        documentos c,
        inventarios_productos f,
        inv_grupos_inventarios i , 
        tipos_doc_generales d
WHERE   a.empresa_id = e.empresa_id
AND     a.prefijo = e.prefijo
AND     a.numero = e.numero
AND     a.documento_id = c.documento_id
AND     a.empresa_id = c.empresa_id
AND     c.tipo_doc_general_id = d.tipo_doc_general_id
AND     e.codigo_producto = f.codigo_producto
AND     f.grupo_id = I.grupo_id
AND     i.sw_medicamento = '1'
AND     a.fecha_registro < NOW()
GROUP BY 1,2,3,4,5,6
UNION ALL
SELECT  b.empresa_id,
        b.centro_utilidad,
        b.bodega,
        c.codigo_producto,
        a.fecha_registro::date,
        '----' AS tipo_doc_general_id,
        SUM(c.cantidad) AS cantiad_egreso,
        0 AS cantidad_ingreso
FROM    bodegas_documentos a,
        bodegas_doc_numeraciones b,
        bodegas_documentos_d c,
        inventarios_productos d,
        inv_grupos_inventarios g 
WHERE   a.bodegas_doc_id = b.bodegas_doc_id
AND     b.tipo_movimiento IN('E')
AND     a.bodegas_doc_id = c.bodegas_doc_id
AND     a.numeracion = c.numeracion
AND     c.codigo_producto = d.codigo_producto
AND     d.grupo_id = g.grupo_id
AND     g.sw_medicamento = '1'
AND     a.fecha_registro < NOW()
GROUP BY 1,2,3,4,5,6,8
)

