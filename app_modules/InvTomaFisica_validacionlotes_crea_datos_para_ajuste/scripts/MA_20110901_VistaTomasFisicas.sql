-- View: public.tomas_fisicas

-- DROP VIEW public.tomas_fisicas;

CREATE VIEW public.tomas_fisicas
(
  toma_fisica_id,
  centro_utilidad,
  bodega,
  empresa_id,
  descripcion_bodega,
  etiqueta,
  etiqueta_x_producto,
  codigo_producto,
  descripcion,
  unidad_id,
  descripcion_unidad,
  existencia,
  fecha_vencimiento,
  lote,
  costo,
  sw_ajusteautomatico,
  conteo_1,
  validacion_conteo_1,
  diferencia_1,
  conteo_2,
  validacion_conteo_2,
  diferencia_2,
  diferencia_1con2,
  conteo_3,
  validacion_conteo_3,
  diferencia_3,
  diferencia_2con3,
  diferencia_1con3,
  nueva_existencia,
  diferencia,
  num_conteo_nueva_existencia,
  sw_manual,
  sw_actualizado
)
AS
		SELECT 
		x.toma_fisica_id, 
		x.centro_utilidad, 
		x.bodega, 
		x.empresa_id, 
		x.descripcion_bodega,
		x.etiqueta, 
		x.etiqueta_x_producto,
		x.codigo_producto, 
		x.descripcion, 
		x.unidad_id,
		x.descripcion_unidad, 
		x.existencia, 
		x.fecha_vencimiento, 
		x.lote, 
		x.costo,
		x.sw_ajusteautomatico, 
		y.conteo AS conteo_1, 
		CASE 
		WHEN (y.usuario_valido IS NULL) 
		THEN '0'::text 
		ELSE '1'::text 
		END AS validacion_conteo_1,
		((x.existencia)::numeric - y.conteo) AS diferencia_1, 
		z.conteo AS conteo_2,
		CASE 
		WHEN (z.usuario_valido IS NULL) 
		THEN '0'::text 
		ELSE '1'::text 
		END AS validacion_conteo_2, 
		((x.existencia)::numeric - z.conteo) AS diferencia_2,
		(y.conteo - z.conteo) AS diferencia_1con2, 
		w.conteo AS conteo_3, 
		CASE 
		WHEN (w.usuario_valido IS NULL) 
		THEN '0'::text 
		ELSE '1'::text 
		END AS validacion_conteo_3, 
		((x.existencia)::numeric - w.conteo) AS diferencia_3,
		(z.conteo - w.conteo) AS diferencia_2con3,
		(y.conteo - w.conteo) AS diferencia_1con3, 
		u.nueva_existencia, 
		(u.nueva_existencia - (x.existencia)::numeric) AS diferencia, 
		u.num_conteo AS num_conteo_nueva_existencia, 
		u.sw_manual,
		u.sw_actualizado
		FROM (
				SELECT DISTINCT 
				a.toma_fisica_id, 
				a.centro_utilidad, 
				a.bodega, 
				a.empresa_id, 
				d.descripcion AS descripcion_bodega, 
				a.etiqueta, 
				a.etiqueta_x_producto,
				b.codigo_producto, 
				b.descripcion,
				b.unidad_id, 
				c.descripcion AS descripcion_unidad, 
				e.existencia_actual AS existencia, 
				e.fecha_vencimiento, 
				e.lote, 
				i.costo, 
				b.sw_ajusteautomatico
					FROM 
							inv_toma_fisica t, 
							inv_toma_fisica_d a, 
							inventarios_productos b,
							unidades c, 
							bodegas d, 
							existencias_bodegas_lote_fv e, 
							inventarios i
					WHERE 
							t.sw_estado::bpchar = '1'::bpchar 
							AND t.toma_fisica_id = a.toma_fisica_id 
							AND a.bodega::text = e.bodega::text 
							AND a.bodega::text = d.bodega::text 
							AND a.centro_utilidad = d.centro_utilidad 
							AND a.empresa_id = d.empresa_id 
							AND a.centro_utilidad = e.centro_utilidad 
							AND a.empresa_id = e.empresa_id 
							AND a.codigo_producto::text = e.codigo_producto::text 
							AND a.lote::text = e.lote::text 
							AND a.fecha_vencimiento = e.fecha_vencimiento 
							AND e.empresa_id = i.empresa_id 
							AND e.codigo_producto::text = i.codigo_producto::text 
							AND i.codigo_producto::text = b.codigo_producto::text 
							AND b.unidad_id::text = c.unidad_id::text
							ORDER BY a.toma_fisica_id, a.bodega, a.empresa_id, d.descripcion,
											a.etiqueta, b.codigo_producto, b.descripcion, b.unidad_id,
											c.descripcion, e.existencia_actual, e.fecha_vencimiento, e.lote,
											i.costo, b.sw_ajusteautomatico
				) x LEFT JOIN inv_toma_fisica_conteos y ON y.toma_fisica_id = x.toma_fisica_id 
									AND y.etiqueta = x.etiqueta 
									AND y.num_conteo =1
				LEFT JOIN inv_toma_fisica_conteos z ON z.toma_fisica_id = x.toma_fisica_id 
									AND z.etiqueta = x.etiqueta 
									AND z.num_conteo = 2
				LEFT JOIN inv_toma_fisica_conteos w ON w.toma_fisica_id = x.toma_fisica_id 
									AND w.etiqueta = x.etiqueta 
									AND w.num_conteo = 3
				LEFT JOIN inv_toma_fisica_update u ON u.toma_fisica_id = x.toma_fisica_id 
									AND u.etiqueta = x.etiqueta;

ALTER TABLE public.tomas_fisicas
  OWNER TO "admin";