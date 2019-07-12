SELECT 
			CASE 
			WHEN ((a.existencia-COALESCE(c.cantidad_pendiente,0))-COALESCE(d.total_cantidad,0)) > 0 THEN 
			((a.existencia-COALESCE(c.cantidad_pendiente,0))-COALESCE(d.total_cantidad,0)) ELSE '0' 
			END as disponible, 
			a.existencia, 
			a.codigo_producto
FROM 	existencias_bodegas as a 
			JOIN inventarios_productos as b ON (a.codigo_producto = b.codigo_producto) 
			LEFT JOIN 
							( select a.codigo_producto, 
							SUM(a.cantidad_pendiente) as cantidad_pendiente 
							from 
									( 
										SELECT sd.codigo_producto, 
										SUM(sd.cantidad_solic) as cantidad_pendiente 
										from solicitud_productos_a_bodega_principal_detalle sd, 
										solicitud_productos_a_bodega_principal s 
										where sd.solicitud_prod_a_bod_ppal_id = s.solicitud_prod_a_bod_ppal_id 
										and s.empresa_destino = '03' 
										and s.sw_despacho = '0' 
									GROUP BY sd.codigo_producto 
									UNION 
										SELECT sd.codigo_producto, 
										SUM(ips.cantidad_pendiente) as cantidad_pendiente 
										from solicitud_productos_a_bodega_principal_detalle sd, 
										solicitud_productos_a_bodega_principal s, 
										inv_mov_pendientes_solicitudes_frm ips 
										where sd.solicitud_prod_a_bod_ppal_id = s.solicitud_prod_a_bod_ppal_id 
										and s.empresa_destino = '03' 
										and s.sw_despacho = '1' 
										and sd.solicitud_prod_a_bod_ppal_id = ips.solicitud_prod_a_bod_ppal_id 
										and sd.solicitud_prod_a_bod_ppal_id = ips.solicitud_prod_a_bod_ppal_id 
										and sd.solicitud_prod_a_bod_ppal_det_id = ips.solicitud_prod_a_bod_ppal_det_id 
										GROUP BY sd.codigo_producto )as a 
							WHERE TRUE GROUP BY a.codigo_producto )as c ON (a.codigo_producto = c.codigo_producto) 
			LEFT JOIN ( 
									SELECT 
									b.codigo_producto, 
									SUM((b.numero_unidades - b.cantidad_despachada)) as total_cantidad 
									FROM ventas_ordenes_pedidos AS a 
									JOIN ventas_ordenes_pedidos_d AS b ON (a.pedido_cliente_id = b.pedido_cliente_id) 
									AND (a.estado = '1') 
									AND (a.empresa_id = '03') 
									AND (b.numero_unidades <> b.cantidad_despachada) 
									WHERE TRUE 
									GROUP BY b.codigo_producto
								UNION
									SELECT 
									x.codigo_producto,
									SUM(x.cantidad_solic) as total_cantidad
									FROM
									solicitud_pro_a_bod_prpal_tmp as x
									JOIN solicitud_bodega_principal_aux as y ON(x.farmacia_id = y.farmacia_id)
									AND (x.centro_utilidad = y.centro_utilidad)
									AND (x.bodega = y.bodega)
									AND (x.usuario_id = y.usuario_id)
									WHERE TRUE
									AND y.empresa_destino = '03'
									GROUP BY x.codigo_producto
								)as d ON (a.codigo_producto = d.codigo_producto) 
WHERE TRUE 
AND a.empresa_id = '03' 
AND a.centro_utilidad = '1' 
AND a.bodega = '03' 
AND a.existencia >0