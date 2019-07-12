ALTER TABLE public.inv_buzon_compras
  ADD COLUMN sw_tipo_usuario char NOT NULL DEFAULT 1;

COMMENT ON COLUMN public.inv_buzon_compras.sw_tipo_usuario
  IS 'Define el tipo de usuario que puede leer un mensaje.(0)Farmacia, (1) Compras y (2) Ambos';


CREATE OR REPLACE FUNCTION public.inv_alertas_rep_productos()
RETURNS trigger AS
$$
DECLARE

mensaje_ varchar;

asunto_ varchar;
datos_ RECORD;
documento RECORD;
pedido RECORD;

BEGIN


	SELECT
	d.documento_id,
	e.inv_tipo_movimiento,
	h.razon_social,
	g.descripcion as descripcion_centro,
	f.descripcion as descripcion_bodega
	INTO documento
	FROM
	inv_bodegas_movimiento as b 
	JOIN inv_bodegas_documentos as c ON (b.documento_id = c.documento_id)
	AND (b.empresa_id = c.empresa_id)
	AND (b.centro_utilidad = c.centro_utilidad)
	AND (b.bodega = c.bodega)
	JOIN documentos as d ON (c.empresa_id = d.empresa_id)
	AND (c.documento_id = d.documento_id)
	JOIN tipos_doc_generales as e ON (d.tipo_doc_general_id = e.tipo_doc_general_id)
	JOIN bodegas as f ON (b.empresa_id = f.empresa_id)
	AND (b.centro_utilidad = f.centro_utilidad)
	AND (b.bodega = f.bodega)
	JOIN centros_utilidad as g ON (f.empresa_id = g.empresa_id)
	AND (f.centro_utilidad = g.centro_utilidad)
	JOIN empresas as h ON (g.empresa_id = h.empresa_id)
	WHERE TRUE
	AND b.empresa_id = NEW.empresa_id
	AND b.prefijo = NEW.prefijo
	AND b.numero = NEW.numero;

		IF documento.inv_tipo_movimiento ='I' THEN
			mensaje_ :=' Ha llegado el Producto ' 	|| fc_descripcion_producto(NEW.codigo_producto) 
																	|| ', Lote :' || NEW.lote 
																	|| ',Fecha Vencimiento:' || NEW.fecha_vencimiento 
																	|| ', A la Empresa: ' || documento.razon_social 
																	|| ', Centro de Utilidad: ' || documento.descripcion_centro 
																	|| ' y Bodega ' || documento.descripcion_bodega 
																	|| '. Cantidad: '  || round(NEW.cantidad);
			asunto_ := ' Ha Llegado El Producto :' || fc_descripcion_producto(NEW.codigo_producto);
			INSERT INTO inv_buzon_compras
			(
			asunto,
			empresa_id,
			fecha_mensaje,
			mensaje,
			sw_tipo_usuario
			)
			VALUES
			(
			asunto_,
			NEW.empresa_id,
			NOW(),
			mensaje_,
			'1'
			); 
		
	--VERIFICA CON UNA CONSULTA, SI EL PRODUCTO QUE HA INGRESADO ESTA PENDIENTE EN ALGUN PEDIDO
			FOR  pedido IN SELECT
					a.solicitud_prod_a_bod_ppal_id,
					a.codigo_producto,
					a.cantidad_pendiente,
					a.cantidad_solicitada,
					a.farmacia_id,
					d.razon_social,
					a.centro_utilidad,
					c.descripcion as descripcion_centro,
					a.bodega,
					b.descripcion as descripcion_bodega
					from
					(
						SELECT 
						sd.solicitud_prod_a_bod_ppal_id,
						sd.codigo_producto,
						sd.cantidad_solic as cantidad_pendiente,
						sd.cantidad_solic as cantidad_solicitada,
						sd.farmacia_id,
						s.centro_utilidad,
						s.bodega,
						s.usuario_id
						from
						solicitud_productos_a_bodega_principal_detalle sd,
						solicitud_productos_a_bodega_principal s
						WHERE TRUE
						AND	sd.codigo_producto = NEW.codigo_producto
						and   sd.solicitud_prod_a_bod_ppal_id = s.solicitud_prod_a_bod_ppal_id
						and   s.empresa_destino = NEW.empresa_id
						and   s.sw_despacho = '0'
					UNION     
							SELECT 
							sd.solicitud_prod_a_bod_ppal_id,
							sd.codigo_producto,
							ips.cantidad_pendiente,
							ips.cantidad_solicitad as cantidad_solicitada,
							ips.farmacia_id,
							s.centro_utilidad,
							s.bodega,
							s.usuario_id
							from
							solicitud_productos_a_bodega_principal_detalle sd,
							solicitud_productos_a_bodega_principal s,
							inv_mov_pendientes_solicitudes_frm ips
							WHERE TRUE
							AND	sd.codigo_producto = NEW.codigo_producto
							and   sd.solicitud_prod_a_bod_ppal_id = s.solicitud_prod_a_bod_ppal_id
							and   s.empresa_destino = NEW.empresa_id
							and   s.sw_despacho = '1'
							and   sd.solicitud_prod_a_bod_ppal_id = ips.solicitud_prod_a_bod_ppal_id
							and   sd.solicitud_prod_a_bod_ppal_id = ips.solicitud_prod_a_bod_ppal_id
							and   sd.solicitud_prod_a_bod_ppal_det_id = ips.solicitud_prod_a_bod_ppal_det_id
					)as a
					JOIN bodegas as b ON (a.farmacia_id = b.empresa_id)
					AND (a.centro_utilidad = b.centro_utilidad)
					AND (a.bodega = b.bodega)
					JOIN centros_utilidad as c ON (b.empresa_id = c.empresa_id)
					AND (b.centro_utilidad = c.centro_utilidad)
					JOIN empresas as d ON (c.empresa_id = d.empresa_id)	
					ORDER BY a.solicitud_prod_a_bod_ppal_id LOOP
					
					mensaje_ :=' El Producto: ' 	|| fc_descripcion_producto(NEW.codigo_producto) 
															|| ', Ha Llegado A la Empresa: ' || documento.razon_social 
															|| ', Centro de Utilidad: ' || documento.descripcion_centro 
															|| ' Y Bodega ' || documento.descripcion_bodega 
															|| '. Cantidad: '  || round(NEW.cantidad)
															|| ' Que esta Pendiente por Despachar en el Pedido #:'|| pedido.solicitud_prod_a_bod_ppal_id
															|| ' FARMACIA: '|| pedido.razon_social
															|| ' - '|| pedido.descripcion_centro
															|| ' - '|| pedido.descripcion_bodega;
										
					asunto_ := ' HA LLEGADO UN PRODUCTO A LA BODEGA, PENDIENTE POR DESPACHAR :' 	|| fc_descripcion_producto(NEW.codigo_producto)
																																					|| '  Pedido #'|| pedido.solicitud_prod_a_bod_ppal_id
																																					|| ' FARMACIA: '|| pedido.razon_social
																																					|| ' - '|| pedido.descripcion_centro
																																					|| ' - '|| pedido.descripcion_bodega;
					INSERT INTO inv_buzon_compras
					(
					asunto,
					empresa_id,
					fecha_mensaje,
					mensaje,
					sw_tipo_usuario
					)
					VALUES
					(
					asunto_,
					 pedido.farmacia_id,
					NOW(),
					mensaje_,
					'0'
					); 
					END LOOP;
				
		
		
		END IF;

		

return NEW;


END;
$$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER;

ALTER FUNCTION public.inv_alertas_rep_productos()
  OWNER TO "admin";

GRANT EXECUTE
 ON FUNCTION public.inv_alertas_rep_productos()
TO "admin";

GRANT EXECUTE
 ON FUNCTION public.inv_alertas_rep_productos()
TO PUBLIC;

GRANT EXECUTE
 ON FUNCTION public.inv_alertas_rep_productos()
TO siis;

CREATE TRIGGER inv_alerta_recepcion_productos
  AFTER INSERT
  ON public.inv_bodegas_movimiento_d
  FOR EACH ROW
  EXECUTE PROCEDURE public.inv_alertas_rep_productos();

