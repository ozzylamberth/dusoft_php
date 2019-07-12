INSERT INTO inv_bodegas_movimiento_tmp_d
(item_id,
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
lote)
SELECT
nextval('inv_bodegas_movimiento_tmp_d_item_id_seq'::regclass) as serial,
'1350' AS usuario_id,
'302' as doc_tmp_id,
'03' as empresa_id,
'1' as centro_utilidad,
'PR' as bodega,
b.codigo_producto,
200 as cantidad,
porc_iva,
(200*b.costo) as costo,
'2020-01-12' as fecha_vencimiento,
'LOTE'||nextval('inv_bodegas_movimiento_tmp_d_item_id_seq'::regclass) as lote
FROM
inventarios_productos as a
JOIN inventarios as b ON (a.codigo_producto = b.codigo_producto)
WHERE TRUE
AND b.empresa_id = '03'
AND a.descripcion ILIKE '%ACETA%'
LIMIT 40