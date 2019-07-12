/*	UNA VARIABLE DE MODULO QUE PARAMETRIZA POR DEFECTO LA BODEGA PRINCIPAL DE DESPACHO	*/
/* MODULO: PedidosFarmacia_A_BodegaPrincipal */
/*	
	VARIABLE = VALOR
	bodega_default_despacho_FD 	=03 	
	empresa_default_despacho_FD	=03 
*/


CREATE OR REPLACE FUNCTION public.solicitar_productos_bodega()
RETURNS trigger AS
$$
DECLARE
  identificador CHARACTER VARYING(30);
  empresa_despacho RECORD;
  farmacia RECORD;
  centro_despacho RECORD;
  bodega_despacho RECORD;
  usuario RECORD;
  productos RECORD;
  producto_pedido RECORD;
  producto_disponible RECORD;
  farmacia_descripcion RECORD;
  mensaje_ varchar;
  asunto_ varchar;
BEGIN

IF NEW.existencia = 0 THEN


		--OBTIENE EL USUARIO QUE AUTORIZA LOS DESPACHOS DE UN PEDIDO TEMPORAL
		SELECT
		a.valor,
		b.usuario_id,
		b.nombre,
		b.usuario as login
		INTO  usuario
		FROM   system_modulos_variables as a
		LEFT JOIN system_usuarios as b ON (a.valor = b.usuario_id)
		WHERE  modulo = ''
		AND    modulo_tipo = ''
		AND    variable = 'usuario_pedido_farmacia_'||OLD.empresa_id;
		
		--OBTIENE LA EMPRESA DE DESPACHO POR DEFAULT
		SELECT INTO empresa_despacho valor
		FROM   system_modulos_variables
		WHERE  modulo = 'PedidosFarmacia_A_BodegaPrincipal'
		AND    modulo_tipo = 'app'
		AND    variable = 'empresa_default_despacho_'||OLD.empresa_id;
		--OBTIENE EL CENTRO DE UTILIDAD DE DESPACHO POR DEFAULT
		SELECT INTO centro_despacho valor
		FROM   system_modulos_variables
		WHERE  modulo = 'PedidosFarmacia_A_BodegaPrincipal'
		AND    modulo_tipo = 'app'
		AND    variable = 'centro_default_despacho_'||OLD.empresa_id;
		--OBTIENE LA BODEGA DE DESPACHO POR DEFAULT
		SELECT INTO bodega_despacho valor
		FROM   system_modulos_variables
		WHERE  modulo = 'PedidosFarmacia_A_BodegaPrincipal'
		AND    modulo_tipo = 'app'
		AND    variable = 'bodega_default_despacho_'||OLD.empresa_id;
		
		-- SACA EL NOMBRE DE LA FARMACIA QUE HACE EL PEDIDO TEMPORAL
		SELECT
		g.razon_social,
		f.descripcion as descripcion_centro,
		e.descripcion as descripcion_bodega
		INTO farmacia_descripcion
		FROM 
		bodegas as e 
		JOIN centros_utilidad as f ON (e.empresa_id = f.empresa_id)
		AND (e.centro_utilidad = f.centro_utilidad)
		JOIN empresas as g ON (f.empresa_id = g.empresa_id)
		WHERE TRUE
		AND e.empresa_id = OLD.empresa_id
		AND e.bodega = OLD.bodega
		AND e.centro_utilidad = OLD.centro_utilidad;
		
	IF usuario.valor IS NOT NULL 		AND empresa_despacho.valor IS NOT NULL 
													AND centro_despacho.valor IS NOT NULL 
													AND  bodega_despacho.valor IS NOT NULL THEN
        
		identificador := OLD.empresa_id||OLD.centro_utilidad||OLD.codigo_producto;
       	
		-- VERIFICO SI HAY UNA TABLA AUXILIAR PARA INSERTAR O NO EN LA TABLA
		SELECT *  INTO farmacia
		FROM
		solicitud_bodega_principal_aux
		WHERE TRUE
		AND farmacia_id = OLD.empresa_id
		AND centro_utilidad = OLD.centro_utilidad
		AND bodega = OLD.bodega
		AND empresa_destino = empresa_despacho.valor
		AND centro_destino = centro_despacho.valor
		AND bogega_destino = bodega_despacho.valor
		AND usuario_id = usuario.valor;
				
				IF farmacia.usuario_id IS NULL THEN
					INSERT INTO solicitud_bodega_principal_aux
						(
						farmacia_id,
						centro_utilidad,
						bodega,
						empresa_destino,
						centro_destino,
						bogega_destino, 	
						usuario_id
						)
					VALUES
						(
						OLD.empresa_id,
						OLD.centro_utilidad,
						OLD.bodega,
						empresa_despacho.valor,
						centro_despacho.valor,
						bodega_despacho.valor,
						usuario.valor::integer
						);
				END IF;
			
				-- SE OBTIENE INFORMACION DEL PRODUCTO
				SELECT INTO productos *
				FROM   inventarios_productos
				WHERE  codigo_producto = OLD.codigo_producto;
				
				-- SE VERIFICA DE QUE EL PRODUCTO NO ESTÈ EN TEMPORAL PARA Q NO BLOQUEE PROCESOS
				SELECT INTO producto_pedido *
				FROM   solicitud_pro_a_bod_prpal_tmp
				WHERE  TRUE
				AND farmacia_id = OLD.empresa_id
				AND centro_utilidad = OLD.centro_utilidad
				AND bodega = OLD.bodega
				AND codigo_producto = OLD.codigo_producto;
				
				
				-- SE OBTIENE LAS CANTIDADES QUE HAY DISPONIBLES PARA PEDIRLE A LA BODEGA PRINCIPAL
				SELECT 
							CASE 
							WHEN ((a.existencia-COALESCE(c.cantidad_pendiente,0))-COALESCE(d.total_cantidad,0)) > a.existencia_minima 
							THEN  a.existencia_minima 
							ELSE ((a.existencia-COALESCE(c.cantidad_pendiente,0))-COALESCE(d.total_cantidad,0))
							END as disponible, 
							((a.existencia-COALESCE(c.cantidad_pendiente,0))-COALESCE(d.total_cantidad,0)) as disponible_total,
							a.existencia, 
							a.codigo_producto,
							a.existencia_minima
							INTO producto_disponible
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
														and s.empresa_destino = empresa_despacho.valor 
														and s.sw_despacho = '0' 
														AND sd.codigo_producto = OLD.codigo_producto
													GROUP BY sd.codigo_producto 
													UNION 
														SELECT sd.codigo_producto, 
														SUM(ips.cantidad_pendiente) as cantidad_pendiente 
														from solicitud_productos_a_bodega_principal_detalle sd, 
														solicitud_productos_a_bodega_principal s, 
														inv_mov_pendientes_solicitudes_frm ips 
														where sd.solicitud_prod_a_bod_ppal_id = s.solicitud_prod_a_bod_ppal_id 
														and s.empresa_destino = empresa_despacho.valor 
														and s.sw_despacho = '1' 
														and sd.solicitud_prod_a_bod_ppal_id = ips.solicitud_prod_a_bod_ppal_id 
														and sd.solicitud_prod_a_bod_ppal_id = ips.solicitud_prod_a_bod_ppal_id 
														and sd.solicitud_prod_a_bod_ppal_det_id = ips.solicitud_prod_a_bod_ppal_det_id 
														AND sd.codigo_producto = OLD.codigo_producto
														GROUP BY sd.codigo_producto )as a 
											WHERE TRUE GROUP BY a.codigo_producto )as c ON (a.codigo_producto = c.codigo_producto) 
							LEFT JOIN ( 
													SELECT 
													b.codigo_producto, 
													SUM((b.numero_unidades - b.cantidad_despachada)) as total_cantidad 
													FROM ventas_ordenes_pedidos AS a 
													JOIN ventas_ordenes_pedidos_d AS b ON (a.pedido_cliente_id = b.pedido_cliente_id) 
													AND (a.estado = '1') 
													AND (a.empresa_id = empresa_despacho.valor ) 
													AND (b.numero_unidades <> b.cantidad_despachada) 
													WHERE TRUE 
													AND b.codigo_producto = OLD.codigo_producto
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
													AND y.empresa_destino = empresa_despacho.valor 
													AND x.codigo_producto = OLD.codigo_producto
													GROUP BY x.codigo_producto
												)as d ON (a.codigo_producto = d.codigo_producto) 
				WHERE TRUE 
				AND a.empresa_id = empresa_despacho.valor  
				AND a.centro_utilidad = centro_despacho.valor 
				AND a.bodega = bodega_despacho.valor 
				AND a.existencia >0
				AND ((a.existencia-COALESCE(c.cantidad_pendiente,0))-COALESCE(d.total_cantidad,0)) >0
				AND a.existencia_minima >0
				AND a.codigo_producto = OLD.codigo_producto;			
				
				-- FIN OBTIENE LAS CANTIDADES QUE HAY DISPONIBLES PARA PEDIRLE A LA BODEGA PRINCIPAL

				
				-- INGRESO DEL PEDIDO TEMPORAL
				IF producto_pedido.usuario_id IS NULL THEN
					IF producto_disponible.disponible IS NOT NULL THEN
						INSERT INTO solicitud_pro_a_bod_prpal_tmp
						(
						  soli_a_bod_prpal_tmp_id,
						  farmacia_id,
						  centro_utilidad,
						  bodega,
						  codigo_producto,
						  cantidad_solic,
						  usuario_id,
						  tipo_producto,
						  observacion
						)
						VALUES
						(
						  identificador,
						  OLD.empresa_id,
						  OLD.centro_utilidad,
						  OLD.bodega,
						  OLD.codigo_producto,
						  producto_disponible.disponible,
						  usuario.valor::integer,
						  productos.tipo_producto_id::integer,
						  'PRODUCTO PEDIDO AUTOMATICAMENTE - SISTEMA'
						);
						
						mensaje_ :=' EL PRODUCTO ' 	|| fc_descripcion_producto(OLD.codigo_producto) 
																	|| ' Ha sido Solicitado Automaticamente Por el Sistema, para: '
																	|| ', Empresa: ' || farmacia_descripcion.razon_social 
																	|| ', Centro de Utilidad: ' || farmacia_descripcion.descripcion_centro 
																	|| ' y Bodega ' || farmacia_descripcion.descripcion_bodega 
																	|| '. Cantidad: '  || round(producto_disponible.disponible)
																	|| ' Usuario Quien Autoriza: '||usuario.usuario_id
																	|| ':' || usuario.login ||'-'||usuario.nombre;
						asunto_ := ' SOLICITUD AUTOMATICA DE :' 	|| fc_descripcion_producto(OLD.codigo_producto)
																						|| ', Empresa: ' || farmacia_descripcion.razon_social 
																						|| ', Centro de Utilidad: ' || farmacia_descripcion.descripcion_centro 
																						|| ' y Bodega ' || farmacia_descripcion.descripcion_bodega 
																						|| ' Usuario Autorizador: '||usuario.usuario_id
																						|| ':' || usuario.login ||'-'||usuario.nombre;
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
						OLD.empresa_id,
						NOW(),
						mensaje_,
						'0'
						); 
						
					END IF;
				END IF;
	END IF;
END IF;
  RETURN NEW;
END
$$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY DEFINER;

ALTER FUNCTION public.solicitar_productos_bodega()
  OWNER TO "admin";

GRANT EXECUTE
 ON FUNCTION public.solicitar_productos_bodega()
TO "admin";

GRANT EXECUTE
 ON FUNCTION public.solicitar_productos_bodega()
TO PUBLIC;
