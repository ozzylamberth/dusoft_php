CREATE OR REPLACE FUNCTION public.au_pedidos_farmacia()
RETURNS trigger AS
$$
DECLARE
  
  indicador RECORD;
  columnas RECORD;
    
	old_solicitud_prod_a_bod_ppal_id integer;
	old_farmacia_id character(2);	
	old_centro_utilidad character(2);		
	old_bodega character(2);		
	old_observacion text;	
	old_usuario_id 	integer;
	old_fecha_registro timestamp;	
	old_empresa_destino character(2);		
	old_sw_despachado character(1);		
	old_sw_despacho character(1);	
  
	new_solicitud_prod_a_bod_ppal_id integer;
	new_farmacia_id character(2);	
	new_centro_utilidad character(2);		
	new_bodega character(2);		
	new_observacion text;	
	new_usuario_id 	integer;
	new_fecha_registro timestamp;	
	new_empresa_destino character(2);		
	new_sw_despachado character(1);		
	new_sw_despacho character(1);	
  
  usuario_registro_ integer;
  fecha_registro timestamp;
  
BEGIN
  SELECT * INTO indicador
  FROM   acciones AC,
         novedad_x_acciones NA
  WHERE  AC.descripcion = TG_OP
  AND    NA.accion_id = AC.accion_id
  AND    NA.table_name = 'solicitud_productos_a_bodega_principal';
 
 /* Inicializar Variables */
 
  IF TG_OP = 'DELETE' THEN
   
	old_solicitud_prod_a_bod_ppal_id := OLD.solicitud_prod_a_bod_ppal_id;
	old_farmacia_id := OLD.farmacia_id;
	old_centro_utilidad := OLD.centro_utilidad;
	old_bodega := OLD.bodega;
	old_observacion := OLD.observacion;
	old_usuario_id 	:= OLD.usuario_id;
	old_fecha_registro := OLD.fecha_registro;
	old_empresa_destino := OLD.empresa_destino;
	old_sw_despachado := OLD.sw_despachado;
	old_sw_despacho := OLD.sw_despacho;
  
	usuario_registro_ := OLD.usuario_id;
	fecha_registro := OLD.fecha_registro;
  END IF; 
  
  IF TG_OP = 'UPDATE' THEN
	old_solicitud_prod_a_bod_ppal_id := OLD.solicitud_prod_a_bod_ppal_id;
	old_farmacia_id := OLD.farmacia_id;
	old_centro_utilidad := OLD.centro_utilidad;
	old_bodega := OLD.bodega;
	old_observacion := OLD.observacion;
	old_usuario_id 	:= OLD.usuario_id;
	old_fecha_registro := OLD.fecha_registro;
	old_empresa_destino := OLD.empresa_destino;
	old_sw_despachado := OLD.sw_despachado;
	old_sw_despacho := OLD.sw_despacho;

	new_solicitud_prod_a_bod_ppal_id := NEW.solicitud_prod_a_bod_ppal_id;
	new_farmacia_id := NEW.farmacia_id;
	new_centro_utilidad := NEW.centro_utilidad;
	new_bodega := NEW.bodega;
	new_observacion := NEW.observacion;
	new_usuario_id 	:= NEW.usuario_id;
	new_fecha_registro := NEW.fecha_registro;
	new_empresa_destino := NEW.empresa_destino;
	new_sw_despachado := NEW.sw_despachado;
	new_sw_despacho := NEW.sw_despacho;
	
	usuario_registro_ := NEW.usuario_id;
	fecha_registro := NEW.fecha_registro;
  
  END IF; 
  
  IF TG_OP = 'INSERT' THEN
	new_solicitud_prod_a_bod_ppal_id := NEW.solicitud_prod_a_bod_ppal_id;
	new_farmacia_id := NEW.farmacia_id;
	new_centro_utilidad := NEW.centro_utilidad;
	new_bodega := NEW.bodega;
	new_observacion := NEW.observacion;
	new_usuario_id 	:= NEW.usuario_id;
	new_fecha_registro := NEW.fecha_registro;
	new_empresa_destino := NEW.empresa_destino;
	new_sw_despachado := NEW.sw_despachado;
	new_sw_despacho := NEW.sw_despacho;
  
	usuario_registro_ := NEW.usuario_id;
	fecha_registro := NEW.fecha_registro;
  END IF;
  
  IF indicador.accion_id IS NOT NULL THEN
    INSERT INTO auditorias_generales
    (
      accion_id,
      novedad_indicador_id,
      table_name,
      
      campo_1, 
      nuevo_valor_1,
      antiguo_valor_1 ,    
      
      campo_2, 
      nuevo_valor_2,
      antiguo_valor_2,
      
      campo_3, 
      nuevo_valor_3,
      antiguo_valor_3,
      
      campo_4, 
      nuevo_valor_4,
      antiguo_valor_4,
      
      campo_5, 
      nuevo_valor_5,
      antiguo_valor_5,
	  
      campo_6, 
      nuevo_valor_6,
      antiguo_valor_6,
	  
      campo_7, 
      nuevo_valor_7,
      antiguo_valor_7,
	  
      campo_8, 
      nuevo_valor_8,
      antiguo_valor_8,
	  
      campo_9, 
      nuevo_valor_9,
      antiguo_valor_9,
	  
      campo_10, 
      nuevo_valor_10,
      antiguo_valor_10,
   
     usuario_registro

    )
    VALUES
    (
      indicador.accion_id,
      indicador.novedad_indicador_id,
      'solicitud_productos_a_bodega_principal',
      
      'solicitud_prod_a_bod_ppal_id',
      new_solicitud_prod_a_bod_ppal_id,
      old_solicitud_prod_a_bod_ppal_id,
      
      'farmacia_id',    
      new_farmacia_id,
      old_farmacia_id,
     
      'centro_utilidad',
      new_centro_utilidad,
      old_centro_utilidad,
      
      'bodega',
      new_bodega,
      old_bodega,
      
      'observacion',
      new_observacion,
      old_observacion,
      
      'usuario_id',
      new_usuario_id,
      old_usuario_id,
      
      'fecha_registro',
      new_fecha_registro,
      old_fecha_registro,
      
      'empresa_destino',
      new_empresa_destino,
      old_empresa_destino,
      
      'sw_despachado',
      new_sw_despachado,
      old_sw_despachado,
      
      'sw_despacho',
      new_sw_despacho,
      old_sw_despacho,
         
     usuario_registro_
    );
  END IF;
    
RETURN NEW;
END
$$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER;

ALTER FUNCTION public.au_pedidos_farmacia()
  OWNER TO "admin";
  
  CREATE TRIGGER aut_auditoria_pedidos_farmacia
  AFTER INSERT OR UPDATE OR DELETE
  ON public.solicitud_productos_a_bodega_principal
  FOR EACH ROW
  EXECUTE PROCEDURE public.au_pedidos_farmacia();

  
  /*
  * DETALLE PEDIDOS PARA AUDITAR
  */
  
  CREATE OR REPLACE FUNCTION public.au_pedidos_farmacia_detalle()
RETURNS trigger AS
$$
DECLARE
  
  indicador RECORD;
  columnas RECORD;
    
	old_solicitud_prod_a_bod_ppal_det_id integer;
	old_solicitud_prod_a_bod_ppal_id integer;	
	old_farmacia_id character(2);
	old_centro_utilidad character(2);	
	old_bodega character(2);	
	old_codigo_producto 	character varying(30);
	old_cantidad_solic numeric(14,4);	
	old_tipo_producto 	character varying(1);
	old_usuario_id integer;	
	old_fecha_registro timestamp;	
	old_sw_pendiente character(1);	
	old_observacion text;
  
	new_solicitud_prod_a_bod_ppal_det_id integer;
	new_solicitud_prod_a_bod_ppal_id integer;	
	new_farmacia_id character(2);
	new_centro_utilidad character(2);	
	new_bodega character(2);	
	new_codigo_producto 	character varying(30);
	new_cantidad_solic numeric(14,4);	
	new_tipo_producto 	character varying(1);
	new_usuario_id integer;	
	new_fecha_registro timestamp;	
	new_sw_pendiente character(1);	
	new_observacion text;
  
  usuario_registro_ integer;
  fecha_registro timestamp;
  
BEGIN
  SELECT * INTO indicador
  FROM   acciones AC,
         novedad_x_acciones NA
  WHERE  AC.descripcion = TG_OP
  AND    NA.accion_id = AC.accion_id
  AND    NA.table_name = 'solicitud_productos_a_bodega_principal_detalle';
 
 /* Inicializar Variables */
 
  IF TG_OP = 'DELETE' THEN
   
	old_solicitud_prod_a_bod_ppal_det_id := OLD.solicitud_prod_a_bod_ppal_det_id;
	old_solicitud_prod_a_bod_ppal_id := OLD.solicitud_prod_a_bod_ppal_id;
	old_farmacia_id := OLD.farmacia_id;
	old_centro_utilidad := OLD.centro_utilidad;
	old_bodega := OLD.bodega;
	old_codigo_producto := OLD.codigo_producto;
	old_cantidad_solic := OLD.cantidad_solic;
	old_tipo_producto := OLD.tipo_producto;
	old_usuario_id := OLD.usuario_id;
	old_fecha_registro := OLD.fecha_registro;
	old_sw_pendiente := OLD.sw_pendiente;
	old_observacion := OLD.observacion;
  
	usuario_registro_ := OLD.usuario_id;
	fecha_registro := OLD.fecha_registro;
  END IF; 
  
  IF TG_OP = 'UPDATE' THEN
	old_solicitud_prod_a_bod_ppal_det_id := OLD.solicitud_prod_a_bod_ppal_det_id;
	old_solicitud_prod_a_bod_ppal_id := OLD.solicitud_prod_a_bod_ppal_id;
	old_farmacia_id := OLD.farmacia_id;
	old_centro_utilidad := OLD.centro_utilidad;
	old_bodega := OLD.bodega;
	old_codigo_producto := OLD.codigo_producto;
	old_cantidad_solic := OLD.cantidad_solic;
	old_tipo_producto := OLD.tipo_producto;
	old_usuario_id := OLD.usuario_id;
	old_fecha_registro := OLD.fecha_registro;
	old_sw_pendiente := OLD.sw_pendiente;
	old_observacion := OLD.observacion;

	new_solicitud_prod_a_bod_ppal_det_id := NEW.solicitud_prod_a_bod_ppal_det_id;
	new_solicitud_prod_a_bod_ppal_id := NEW.solicitud_prod_a_bod_ppal_id;
	new_farmacia_id := NEW.farmacia_id;
	new_centro_utilidad := NEW.centro_utilidad;
	new_bodega := NEW.bodega;
	new_codigo_producto := NEW.codigo_producto;
	new_cantidad_solic := NEW.cantidad_solic;
	new_tipo_producto := NEW.tipo_producto;
	new_usuario_id := NEW.usuario_id;
	new_fecha_registro := NEW.fecha_registro;
	new_sw_pendiente := NEW.sw_pendiente;
	new_observacion := NEW.observacion;
	
	usuario_registro_ := NEW.usuario_id;
	fecha_registro := NEW.fecha_registro;
  
  END IF; 
  
  IF TG_OP = 'INSERT' THEN
	new_solicitud_prod_a_bod_ppal_det_id := NEW.solicitud_prod_a_bod_ppal_det_id;
	new_solicitud_prod_a_bod_ppal_id := NEW.solicitud_prod_a_bod_ppal_id;
	new_farmacia_id := NEW.farmacia_id;
	new_centro_utilidad := NEW.centro_utilidad;
	new_bodega := NEW.bodega;
	new_codigo_producto := NEW.codigo_producto;
	new_cantidad_solic := NEW.cantidad_solic;
	new_tipo_producto := NEW.tipo_producto;
	new_usuario_id := NEW.usuario_id;
	new_fecha_registro := NEW.fecha_registro;
	new_sw_pendiente := NEW.sw_pendiente;
	new_observacion := NEW.observacion;
  
	usuario_registro_ := NEW.usuario_id;
	fecha_registro := NEW.fecha_registro;
  END IF;
  
  IF indicador.accion_id IS NOT NULL THEN
    INSERT INTO auditorias_generales
    (
      accion_id,
      novedad_indicador_id,
      table_name,
      
      campo_1, 
      nuevo_valor_1,
      antiguo_valor_1 ,    
      
      campo_2, 
      nuevo_valor_2,
      antiguo_valor_2,
      
      campo_3, 
      nuevo_valor_3,
      antiguo_valor_3,
      
      campo_4, 
      nuevo_valor_4,
      antiguo_valor_4,
      
      campo_5, 
      nuevo_valor_5,
      antiguo_valor_5,
	  
      campo_6, 
      nuevo_valor_6,
      antiguo_valor_6,
	  
      campo_7, 
      nuevo_valor_7,
      antiguo_valor_7,
	  
      campo_8, 
      nuevo_valor_8,
      antiguo_valor_8,
	  
      campo_9, 
      nuevo_valor_9,
      antiguo_valor_9,
	  
      campo_10, 
      nuevo_valor_10,
      antiguo_valor_10,
	  
      campo_11, 
      nuevo_valor_11,
      antiguo_valor_11,
	  
      campo_12, 
      nuevo_valor_12,
      antiguo_valor_12,
   
     usuario_registro

    )
    VALUES
    (
      indicador.accion_id,
      indicador.novedad_indicador_id,
      'solicitud_productos_a_bodega_principal_detalle',
      
      'solicitud_prod_a_bod_ppal_det_id',
      new_solicitud_prod_a_bod_ppal_det_id,
      old_solicitud_prod_a_bod_ppal_det_id,
      
      'solicitud_prod_a_bod_ppal_id',    
      new_solicitud_prod_a_bod_ppal_id,
      old_solicitud_prod_a_bod_ppal_id,
     
      'farmacia_id',
      new_farmacia_id,
      old_farmacia_id,
      
      'centro_utilidad',
      new_centro_utilidad,
      old_centro_utilidad,
      
      'bodega',
      new_bodega,
      old_bodega,
      
      'codigo_producto',
      new_codigo_producto,
      old_codigo_producto,
      
      'cantidad_solic',
      new_cantidad_solic,
      old_cantidad_solic,
      
      'tipo_producto',
      new_tipo_producto,
      old_tipo_producto,
      
      'usuario_id',
      new_usuario_id,
      old_usuario_id,
      
      'fecha_registro',
      new_fecha_registro,
      old_fecha_registro,
      
      'sw_pendiente',
      new_sw_pendiente,
      old_sw_pendiente,
      
      'observacion',
      new_observacion,
      old_observacion,
         
     usuario_registro_
    );
  END IF;
    
RETURN NEW;
END
$$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER;

ALTER FUNCTION public.au_pedidos_farmacia_detalle()
  OWNER TO "admin";
  
  CREATE TRIGGER aut_auditoria_pedidos_farmacia_detalle
  BEFORE INSERT OR UPDATE OR DELETE
  ON public.solicitud_productos_a_bodega_principal_detalle
  FOR EACH ROW
  EXECUTE PROCEDURE au_pedidos_farmacia_detalle();

  /*
  * TRIGGER DE AUDITORIA PARA COMPRAS.
  */
  CREATE OR REPLACE FUNCTION public.au_compras()
RETURNS trigger AS
$$
DECLARE
  
  indicador RECORD;
  columnas RECORD;
    
	old_orden_pedido_id 	integer;
	old_codigo_proveedor_id 	integer;
	old_empresa_id character(2);
	old_fecha_orden date;
	old_usuario_id integer;	
	old_estado character(1);	
	old_fecha_envio date;	
	old_fecha_recibido date;
	old_empresa_id_pedido character(2);	
	old_sw_unificada 	character(2);
	old_fecha_registro timestamp;	
	old_observacion text;	
	old_codigo_unidad_negocio character varying(2);
	old_preorden_id integer;
  
	new_orden_pedido_id 	integer;
	new_codigo_proveedor_id 	integer;
	new_empresa_id character(2);
	new_fecha_orden date;
	new_usuario_id integer;	
	new_estado character(1);	
	new_fecha_envio date;	
	new_fecha_recibido date;
	new_empresa_id_pedido character(2);	
	new_sw_unificada 	character(2);
	new_fecha_registro timestamp;	
	new_observacion text;	
	new_codigo_unidad_negocio character varying(2);
	new_preorden_id integer;
  
  usuario_registro_ integer;
  fecha_registro timestamp;
  
BEGIN
  SELECT * INTO indicador
  FROM   acciones AC,
         novedad_x_acciones NA
  WHERE  AC.descripcion = TG_OP
  AND    NA.accion_id = AC.accion_id
  AND    NA.table_name = 'compras_ordenes_pedidos';
 
 /* Inicializar Variables */
 
  IF TG_OP = 'DELETE' THEN
   
	old_orden_pedido_id :=	OLD.orden_pedido_id; 	
	old_codigo_proveedor_id :=OLD.codigo_proveedor_id; 	
	old_empresa_id :=	OLD.empresa_id; 	
	old_fecha_orden :=	OLD.fecha_orden; 	
	old_usuario_id :=	OLD.usuario_id; 	
	old_estado :=	OLD.estado; 	
	old_fecha_envio :=	OLD.fecha_envio;
	old_fecha_recibido :=	OLD.fecha_recibido;
	old_empresa_id_pedido :=	OLD.empresa_id_pedido;
	old_sw_unificada :=	OLD.sw_unificada;	
	old_fecha_registro :=	OLD.fecha_registro; 	
	old_observacion := OLD.observacion; 	
	old_codigo_unidad_negocio := OLD.codigo_unidad_negocio; 	
	old_preorden_id := OLD.preorden_id;
  
	usuario_registro_ := OLD.usuario_id;
	fecha_registro := OLD.fecha_registro;
  END IF; 
  
  IF TG_OP = 'UPDATE' THEN
	old_orden_pedido_id :=	OLD.orden_pedido_id; 	
	old_codigo_proveedor_id :=OLD.codigo_proveedor_id; 	
	old_empresa_id :=	OLD.empresa_id; 	
	old_fecha_orden :=	OLD.fecha_orden; 	
	old_usuario_id :=	OLD.usuario_id; 	
	old_estado :=	OLD.estado; 	
	old_fecha_envio :=	OLD.fecha_envio;
	old_fecha_recibido :=	OLD.fecha_recibido;
	old_empresa_id_pedido :=	OLD.empresa_id_pedido;
	old_sw_unificada :=	OLD.sw_unificada;	
	old_fecha_registro :=	OLD.fecha_registro; 	
	old_observacion := OLD.observacion; 	
	old_codigo_unidad_negocio := OLD.codigo_unidad_negocio; 	
	old_preorden_id := OLD.preorden_id;

	new_orden_pedido_id :=	NEW.orden_pedido_id; 	
	new_codigo_proveedor_id :=NEW.codigo_proveedor_id; 	
	new_empresa_id :=	NEW.empresa_id; 	
	new_fecha_orden :=	NEW.fecha_orden; 	
	new_usuario_id :=	NEW.usuario_id; 	
	new_estado :=	NEW.estado; 	
	new_fecha_envio :=	NEW.fecha_envio;
	new_fecha_recibido :=	NEW.fecha_recibido;
	new_empresa_id_pedido :=	NEW.empresa_id_pedido;
	new_sw_unificada :=	NEW.sw_unificada;	
	new_fecha_registro :=	NEW.fecha_registro; 	
	new_observacion := NEW.observacion; 	
	new_codigo_unidad_negocio := NEW.codigo_unidad_negocio; 	
	new_preorden_id := NEW.preorden_id;
	
	usuario_registro_ := NEW.usuario_id;
	fecha_registro := NEW.fecha_registro;
  
  END IF; 
  
  IF TG_OP = 'INSERT' THEN
	new_orden_pedido_id :=	NEW.orden_pedido_id; 	
	new_codigo_proveedor_id :=NEW.codigo_proveedor_id; 	
	new_empresa_id :=	NEW.empresa_id; 	
	new_fecha_orden :=	NEW.fecha_orden; 	
	new_usuario_id :=	NEW.usuario_id; 	
	new_estado :=	NEW.estado; 	
	new_fecha_envio :=	NEW.fecha_envio;
	new_fecha_recibido :=	NEW.fecha_recibido;
	new_empresa_id_pedido :=	NEW.empresa_id_pedido;
	new_sw_unificada :=	NEW.sw_unificada;	
	new_fecha_registro :=	NEW.fecha_registro; 	
	new_observacion := NEW.observacion; 	
	new_codigo_unidad_negocio := NEW.codigo_unidad_negocio; 	
	new_preorden_id := NEW.preorden_id;
  
	usuario_registro_ := NEW.usuario_id;
	fecha_registro := NEW.fecha_registro;
  END IF;
  
  IF indicador.accion_id IS NOT NULL THEN
    INSERT INTO auditorias_generales
    (
      accion_id,
      novedad_indicador_id,
      table_name,
      
      campo_1, 
      nuevo_valor_1,
      antiguo_valor_1 ,    
      
      campo_2, 
      nuevo_valor_2,
      antiguo_valor_2,
      
      campo_3, 
      nuevo_valor_3,
      antiguo_valor_3,
      
      campo_4, 
      nuevo_valor_4,
      antiguo_valor_4,
      
      campo_5, 
      nuevo_valor_5,
      antiguo_valor_5,
	  
      campo_6, 
      nuevo_valor_6,
      antiguo_valor_6,
	  
      campo_7, 
      nuevo_valor_7,
      antiguo_valor_7,
	  
      campo_8, 
      nuevo_valor_8,
      antiguo_valor_8,
	  
      campo_9, 
      nuevo_valor_9,
      antiguo_valor_9,
	  
      campo_10, 
      nuevo_valor_10,
      antiguo_valor_10,
	  
      campo_11, 
      nuevo_valor_11,
      antiguo_valor_11,
	  
      campo_12, 
      nuevo_valor_12,
      antiguo_valor_12,
	  
      campo_13, 
      nuevo_valor_13,
      antiguo_valor_13,
	  
      campo_14, 
      nuevo_valor_14,
      antiguo_valor_14,
   
     usuario_registro

    )
    VALUES
    (
      indicador.accion_id,
      indicador.novedad_indicador_id,
      'compras_ordenes_pedidos',
      
      'orden_pedido_id',
      new_orden_pedido_id,
      old_orden_pedido_id,
      
      'codigo_proveedor_id',    
      new_codigo_proveedor_id,
      old_codigo_proveedor_id,
     
      'empresa_id',
      new_empresa_id,
      old_empresa_id,
      
      'fecha_orden',
      new_fecha_orden,
      old_fecha_orden,
      
      'usuario_id',
      new_usuario_id,
      old_usuario_id,
      
      'estado',
      new_estado,
      old_estado,
      
      'fecha_envio',
      new_fecha_envio,
      old_fecha_envio,
      
      'fecha_recibido',
      new_fecha_recibido,
      old_fecha_recibido,
      
      'empresa_id_pedido',
      new_empresa_id_pedido,
      old_empresa_id_pedido,
      
      'sw_unificada',
      new_sw_unificada,
      old_sw_unificada,
      
      'fecha_registro',
      new_fecha_registro,
      old_fecha_registro,
      
      'observacion',
      new_observacion,
      old_observacion,
      
      'codigo_unidad_negocio',
      new_codigo_unidad_negocio,
      old_codigo_unidad_negocio,
      
      'preorden_id',
      new_preorden_id,
      old_preorden_id,
         
     usuario_registro_
    );
  END IF;
    
RETURN NEW;
END
$$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER;

ALTER FUNCTION public.au_compras()
  OWNER TO "admin";
  
  CREATE TRIGGER aut_auditoria_compras
  BEFORE INSERT OR UPDATE OR DELETE
  ON public.compras_ordenes_pedidos
  FOR EACH ROW
  EXECUTE PROCEDURE au_compras();

  
  
  /*
  * TRIGGER DE AUDITORIA PARA COMPRAS. (DETALLE)
  */
  CREATE OR REPLACE FUNCTION public.au_compras_detalle()
RETURNS trigger AS	
$$
DECLARE
  
  indicador RECORD;
  columnas RECORD;
    
	old_orden_pedido_id 	integer;
	old_codigo_producto 	character varying(50);
	old_numero_unidades numeric(18,2);
	old_valor 	double precision;
	old_porc_iva 	numeric(5,3);
	old_estado 	character(1);
	old_numero_unidades_recibidas 	numeric(18,1);
	old_preorden_detalle_id integer;
	old_item_id 	integer;
	old_sw_ingresonc character varying(1);
  
	new_orden_pedido_id 	integer;
	new_codigo_producto 	character varying(50);
	new_numero_unidades numeric(18,2);
	new_valor 	double precision;
	new_porc_iva 	numeric(5,3);
	new_estado 	character(1);
	new_numero_unidades_recibidas 	numeric(18,1);
	new_preorden_detalle_id integer;
	new_item_id 	integer;
	new_sw_ingresonc character varying(1);
  
  usuario_registro_ integer;
  fecha_registro timestamp;
  
BEGIN
  SELECT * INTO indicador
  FROM   acciones AC,
         novedad_x_acciones NA
  WHERE  AC.descripcion = TG_OP
  AND    NA.accion_id = AC.accion_id
  AND    NA.table_name = 'compras_ordenes_pedidos_detalle';
 
 /* Inicializar Variables */
 
  IF TG_OP = 'DELETE' THEN
   
	old_orden_pedido_id 	:= OLD.orden_pedido_id;
	old_codigo_producto 	:= OLD.codigo_producto;
	old_numero_unidades := OLD.numero_unidades;	
	old_valor 	:= OLD.valor;
	old_porc_iva 	:= OLD.porc_iva;
	old_estado := OLD.estado;	
	old_numero_unidades_recibidas 	:= OLD.numero_unidades_recibidas;
	old_preorden_detalle_id := OLD.preorden_detalle_id;	
	old_item_id := OLD.item_id;	
	old_sw_ingresonc := OLD.sw_ingresonc;
  
	usuario_registro_ := 2;
	fecha_registro := NOW();
  END IF; 
  
  IF TG_OP = 'UPDATE' THEN
	old_orden_pedido_id 	:= OLD.orden_pedido_id;
	old_codigo_producto 	:= OLD.codigo_producto;
	old_numero_unidades := OLD.numero_unidades;	
	old_valor 	:= OLD.valor;
	old_porc_iva 	:= OLD.porc_iva;
	old_estado := OLD.estado;	
	old_numero_unidades_recibidas 	:= OLD.numero_unidades_recibidas;
	old_preorden_detalle_id := OLD.preorden_detalle_id;	
	old_item_id := OLD.item_id;	
	old_sw_ingresonc := OLD.sw_ingresonc;

	new_orden_pedido_id 	:= NEW.orden_pedido_id;
	new_codigo_producto 	:= NEW.codigo_producto;
	new_numero_unidades := NEW.numero_unidades;	
	new_valor 	:= NEW.valor;
	new_porc_iva 	:= NEW.porc_iva;
	new_estado := NEW.estado;	
	new_numero_unidades_recibidas 	:= NEW.numero_unidades_recibidas;
	new_preorden_detalle_id := NEW.preorden_detalle_id;	
	new_item_id := NEW.item_id;	
	new_sw_ingresonc := NEW.sw_ingresonc;
	
	usuario_registro_ := 2;
	fecha_registro := NOW();
  
  END IF; 
  
  IF TG_OP = 'INSERT' THEN
	new_orden_pedido_id 	:= NEW.orden_pedido_id;
	new_codigo_producto 	:= NEW.codigo_producto;
	new_numero_unidades := NEW.numero_unidades;	
	new_valor 	:= NEW.valor;
	new_porc_iva 	:= NEW.porc_iva;
	new_estado := NEW.estado;	
	new_numero_unidades_recibidas 	:= NEW.numero_unidades_recibidas;
	new_preorden_detalle_id := NEW.preorden_detalle_id;	
	new_item_id := NEW.item_id;	
	new_sw_ingresonc := NEW.sw_ingresonc;
  
	usuario_registro_ := 2;
	fecha_registro := NOW();
  END IF;
  
  IF indicador.accion_id IS NOT NULL THEN
    INSERT INTO auditorias_generales
    (
      accion_id,
      novedad_indicador_id,
      table_name,
      
      campo_1, 
      nuevo_valor_1,
      antiguo_valor_1 ,    
      
      campo_2, 
      nuevo_valor_2,
      antiguo_valor_2,
      
      campo_3, 
      nuevo_valor_3,
      antiguo_valor_3,
      
      campo_4, 
      nuevo_valor_4,
      antiguo_valor_4,
      
      campo_5, 
      nuevo_valor_5,
      antiguo_valor_5,
	  
      campo_6, 
      nuevo_valor_6,
      antiguo_valor_6,
	  
      campo_7, 
      nuevo_valor_7,
      antiguo_valor_7,
	  
      campo_8, 
      nuevo_valor_8,
      antiguo_valor_8,
	  
      campo_9, 
      nuevo_valor_9,
      antiguo_valor_9,
	  
      campo_10, 
      nuevo_valor_10,
      antiguo_valor_10,
	  
   
     usuario_registro

    )
    VALUES
    (
      indicador.accion_id,
      indicador.novedad_indicador_id,
      'compras_ordenes_pedidos_detalle',
      
      'orden_pedido_id',
      new_orden_pedido_id,
      old_orden_pedido_id,
      
      'codigo_producto',    
      new_codigo_producto,
      old_codigo_producto,
     
      'numero_unidades',
      new_numero_unidades,
      old_numero_unidades,
      
      'valor',
      new_valor,
      old_valor,
      
      'porc_iva',
      new_porc_iva,
      old_porc_iva,
      
      'estado',
      new_estado,
      old_estado,
      
      'numero_unidades_recibidas',
      new_numero_unidades_recibidas,
      old_numero_unidades_recibidas,
      
      'preorden_detalle_id',
      new_preorden_detalle_id,
      old_preorden_detalle_id,
      
      'item_id',
      new_item_id,
      old_item_id,
      
      'sw_ingresonc',
      new_sw_ingresonc,
      old_sw_ingresonc,
               
     usuario_registro_
    );
  END IF;
    
RETURN NEW;
END
$$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER;

ALTER FUNCTION public.au_compras_detalle()
  OWNER TO "admin";

  CREATE TRIGGER aut_auditoria_compras_detalle
  AFTER INSERT OR UPDATE OR DELETE
  ON public.compras_ordenes_pedidos_detalle
  FOR EACH ROW
  EXECUTE PROCEDURE au_compras_detalle();

  
  /*
  * TRIGGER PARA LA AUDITORIA DE CONTRATOS CLIENTE
  */
    CREATE OR REPLACE FUNCTION public.au_contrato_cliente()
RETURNS trigger AS	
$$
DECLARE
  
  indicador RECORD;
  columnas RECORD;
    
	old_contrato_cliente_id 	integer;
	old_empresa_id 	character(2);
	old_descripcion 	character(30);
	old_fecha_inicio date;	
	old_fecha_final date;	
	old_tipo_id_tercero 	character varying(3);
	old_tercero_id character varying(32);	
	old_codigo_unidad_negocio character varying(2);	
	old_contrato_generico character(30);	
	old_condiciones_cliente text;	
	old_observaciones text;
	old_estado 	character(1);
	old_porcentaje_genericos numeric(9,4);
	old_porcentaje_marcas numeric(9,4);
	old_porcentajes_insumos numeric(9,4);	
	old_tipo_id_vendedor character(3);	
	old_vendedor_id character varying(32);	
	old_valor_contrato numeric(20,4);
	old_saldo numeric(20,0);
	old_usuario_id integer;	
	old_fecha_registro timestamp;
  
	new_contrato_cliente_id 	integer;
	new_empresa_id 	character(2);
	new_descripcion 	character(30);
	new_fecha_inicio date;	
	new_fecha_final date;	
	new_tipo_id_tercero 	character varying(3);
	new_tercero_id character varying(32);	
	new_codigo_unidad_negocio character varying(2);	
	new_contrato_generico character(30);	
	new_condiciones_cliente text;	
	new_observaciones text;
	new_estado 	character(1);
	new_porcentaje_genericos numeric(9,4);
	new_porcentaje_marcas numeric(9,4);
	new_porcentajes_insumos numeric(9,4);	
	new_tipo_id_vendedor character(3);	
	new_vendedor_id character varying(32);	
	new_valor_contrato numeric(20,4);
	new_saldo numeric(20,0);
	new_usuario_id integer;	
	new_fecha_registro timestamp;
  
  usuario_registro_ integer;
  fecha_registro timestamp;
  
BEGIN
  SELECT * INTO indicador
  FROM   acciones AC,
         novedad_x_acciones NA
  WHERE  AC.descripcion = TG_OP
  AND    NA.accion_id = AC.accion_id
  AND    NA.table_name = 'vnts_contratos_clientes';
 
 /* Inicializar Variables */
 
  IF TG_OP = 'DELETE' THEN
   
	old_contrato_cliente_id 	:= OLD.contrato_cliente_id;
	old_empresa_id := OLD.empresa_id;
	old_descripcion 	:= OLD.descripcion;
	old_fecha_inicio := OLD.fecha_inicio;
	old_fecha_final := OLD.fecha_final;
	old_tipo_id_tercero := OLD.tipo_id_tercero;
	old_tercero_id := OLD.tercero_id;
	old_codigo_unidad_negocio := OLD.codigo_unidad_negocio;
	old_contrato_generico := OLD.contrato_generico;
	old_condiciones_cliente := OLD.condiciones_cliente;
	old_observaciones := OLD.observaciones;
	old_estado := OLD.estado;
	old_porcentaje_genericos := OLD.porcentaje_genericos;
	old_porcentaje_marcas := OLD.porcentaje_marcas;
	old_porcentajes_insumos := OLD.porcentajes_insumos;
	old_tipo_id_vendedor := OLD.tipo_id_vendedor;
	old_vendedor_id := OLD.vendedor_id;
	old_valor_contrato := OLD.valor_contrato;
	old_saldo := OLD.saldo;
	old_usuario_id := OLD.usuario_id;
	old_fecha_registro := OLD.fecha_registro;
  
	usuario_registro_ := OLD.usuario_id;
	fecha_registro := NOW();
  END IF; 
  
  IF TG_OP = 'UPDATE' THEN
	old_contrato_cliente_id 	:= OLD.contrato_cliente_id;
	old_empresa_id := OLD.empresa_id;
	old_descripcion 	:= OLD.descripcion;
	old_fecha_inicio := OLD.fecha_inicio;
	old_fecha_final := OLD.fecha_final;
	old_tipo_id_tercero := OLD.tipo_id_tercero;
	old_tercero_id := OLD.tercero_id;
	old_codigo_unidad_negocio := OLD.codigo_unidad_negocio;
	old_contrato_generico := OLD.contrato_generico;
	old_condiciones_cliente := OLD.condiciones_cliente;
	old_observaciones := OLD.observaciones;
	old_estado := OLD.estado;
	old_porcentaje_genericos := OLD.porcentaje_genericos;
	old_porcentaje_marcas := OLD.porcentaje_marcas;
	old_porcentajes_insumos := OLD.porcentajes_insumos;
	old_tipo_id_vendedor := OLD.tipo_id_vendedor;
	old_vendedor_id := OLD.vendedor_id;
	old_valor_contrato := OLD.valor_contrato;
	old_saldo := OLD.saldo;
	old_usuario_id := OLD.usuario_id;
	old_fecha_registro := OLD.fecha_registro;

	new_contrato_cliente_id 	:= NEW.contrato_cliente_id;
	new_empresa_id := NEW.empresa_id;
	new_descripcion 	:= NEW.descripcion;
	new_fecha_inicio := NEW.fecha_inicio;
	new_fecha_final := NEW.fecha_final;
	new_tipo_id_tercero := NEW.tipo_id_tercero;
	new_tercero_id := NEW.tercero_id;
	new_codigo_unidad_negocio := NEW.codigo_unidad_negocio;
	new_contrato_generico := NEW.contrato_generico;
	new_condiciones_cliente := NEW.condiciones_cliente;
	new_observaciones := NEW.observaciones;
	new_estado := NEW.estado;
	new_porcentaje_genericos := NEW.porcentaje_genericos;
	new_porcentaje_marcas := NEW.porcentaje_marcas;
	new_porcentajes_insumos := NEW.porcentajes_insumos;
	new_tipo_id_vendedor := NEW.tipo_id_vendedor;
	new_vendedor_id := NEW.vendedor_id;
	new_valor_contrato := NEW.valor_contrato;
	new_saldo := NEW.saldo;
	new_usuario_id := NEW.usuario_id;
	new_fecha_registro := NEW.fecha_registro;
	
	usuario_registro_ := NEW.usuario_id;
	fecha_registro := NOW();
  
  END IF; 
  
  IF TG_OP = 'INSERT' THEN
	
	new_contrato_cliente_id 	:= NEW.contrato_cliente_id;
	new_empresa_id := NEW.empresa_id;
	new_descripcion 	:= NEW.descripcion;
	new_fecha_inicio := NEW.fecha_inicio;
	new_fecha_final := NEW.fecha_final;
	new_tipo_id_tercero := NEW.tipo_id_tercero;
	new_tercero_id := NEW.tercero_id;
	new_codigo_unidad_negocio := NEW.codigo_unidad_negocio;
	new_contrato_generico := NEW.contrato_generico;
	new_condiciones_cliente := NEW.condiciones_cliente;
	new_observaciones := NEW.observaciones;
	new_estado := NEW.estado;
	new_porcentaje_genericos := NEW.porcentaje_genericos;
	new_porcentaje_marcas := NEW.porcentaje_marcas;
	new_porcentajes_insumos := NEW.porcentajes_insumos;
	new_tipo_id_vendedor := NEW.tipo_id_vendedor;
	new_vendedor_id := NEW.vendedor_id;
	new_valor_contrato := NEW.valor_contrato;
	new_saldo := NEW.saldo;
	new_usuario_id := NEW.usuario_id;
	new_fecha_registro := NEW.fecha_registro;
  
	usuario_registro_ := NEW.usuario_id;
	fecha_registro := NOW();
  END IF;
  
  IF indicador.accion_id IS NOT NULL THEN
    INSERT INTO auditorias_generales
    (
      accion_id,
      novedad_indicador_id,
      table_name,
      
      campo_1, 
      nuevo_valor_1,
      antiguo_valor_1 ,    
      
      campo_2, 
      nuevo_valor_2,
      antiguo_valor_2,
      
      campo_3, 
      nuevo_valor_3,
      antiguo_valor_3,
      
      campo_4, 
      nuevo_valor_4,
      antiguo_valor_4,
      
      campo_5, 
      nuevo_valor_5,
      antiguo_valor_5,
	  
      campo_6, 
      nuevo_valor_6,
      antiguo_valor_6,
	  
      campo_7, 
      nuevo_valor_7,
      antiguo_valor_7,
	  
      campo_8, 
      nuevo_valor_8,
      antiguo_valor_8,
	  
      campo_9, 
      nuevo_valor_9,
      antiguo_valor_9,
	  
      campo_10, 
      nuevo_valor_10,
      antiguo_valor_10,
	  
      campo_11, 
      nuevo_valor_11,
      antiguo_valor_11,
	  
      campo_12, 
      nuevo_valor_12,
      antiguo_valor_12,
	  
      campo_13, 
      nuevo_valor_13,
      antiguo_valor_13,
	  
      campo_14, 
      nuevo_valor_14,
      antiguo_valor_14,
	  
      campo_15, 
      nuevo_valor_15,
      antiguo_valor_15,
	  
      campo_16, 
      nuevo_valor_16,
      antiguo_valor_16,
	  
      campo_17, 
      nuevo_valor_17,
      antiguo_valor_17,
	  
      campo_18, 
      nuevo_valor_18,
      antiguo_valor_18,
	  
      campo_19, 
      nuevo_valor_19,
      antiguo_valor_19,
	  
   
     usuario_registro

    )
    VALUES
    (
      indicador.accion_id,
      indicador.novedad_indicador_id,
      'vnts_contratos_clientes',
      
      'contrato_cliente_id',
      new_contrato_cliente_id,
      old_contrato_cliente_id,
      
      'empresa_id',    
      new_empresa_id,
      old_empresa_id,
     
      'descripcion',
      new_descripcion,
      old_descripcion,
      
      'fecha_inicio',
      new_fecha_inicio,
      old_fecha_inicio,
      
      'fecha_final',
      new_fecha_final,
      old_fecha_final,
      
      'tipo_id_tercero',
      new_tipo_id_tercero,
      old_tipo_id_tercero,
      
      'tercero_id',
      new_tercero_id,
      old_tercero_id,
      
      'codigo_unidad_negocio',
      new_codigo_unidad_negocio,
      old_codigo_unidad_negocio,
      
      'contrato_generico',
      new_contrato_generico,
      old_contrato_generico,
      
      'condiciones_cliente',
      new_condiciones_cliente,
      old_condiciones_cliente,
      
      'observaciones',
      new_observaciones,
      old_observaciones,
      
      'estado',
      new_estado,
      old_estado,
      
      'porcentaje_genericos',
      new_porcentaje_genericos,
      old_porcentaje_genericos,
      
      'porcentaje_marcas',
      new_porcentaje_marcas,
      old_porcentaje_marcas,
      
      'porcentajes_insumos',
      new_porcentajes_insumos,
      old_porcentajes_insumos,
      
      'tipo_id_vendedor',
      new_tipo_id_vendedor,
      old_tipo_id_vendedor,
      
      'vendedor_id',
      new_vendedor_id,
      old_vendedor_id,
      
      'valor_contrato',
      new_valor_contrato,
      old_valor_contrato,
      
      'saldo',
      new_saldo,
      old_saldo,
      
               
     usuario_registro_
    );
  END IF;
    
RETURN NEW;
END
$$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER;

ALTER FUNCTION public.au_contrato_cliente()
  OWNER TO "admin";
  
  CREATE TRIGGER aut_auditoria_contratos_clientes
  AFTER INSERT OR UPDATE OR DELETE
  ON public.vnts_contratos_clientes
  FOR EACH ROW
  EXECUTE PROCEDURE au_contrato_cliente();

  /*
  * Auditoria al detalle de un contrato Cliente... Productos
  */
  CREATE OR REPLACE FUNCTION public.au_contrato_cliente_productos()
RETURNS trigger AS	
$$
DECLARE
  
  indicador RECORD;
  columnas RECORD;
    
	old_contrato_cliente_id 	integer;
	old_codigo_producto character varying(50);	
	old_precio_pactado numeric(20,4);	
	old_usuario_id integer;	
	old_fecha_registro timestamp;
  
	new_contrato_cliente_id 	integer;
	new_codigo_producto character varying(50);	
	new_precio_pactado numeric(20,4);	
	new_usuario_id integer;	
	new_fecha_registro timestamp;
  
  usuario_registro_ integer;
  fecha_registro timestamp;
  
BEGIN
  SELECT * INTO indicador
  FROM   acciones AC,
         novedad_x_acciones NA
  WHERE  AC.descripcion = TG_OP
  AND    NA.accion_id = AC.accion_id
  AND    NA.table_name = 'vnts_contratos_clientes_productos';
 
 /* Inicializar Variables */
 
  IF TG_OP = 'DELETE' THEN
   
	old_contrato_cliente_id := OLD.contrato_cliente_id;
	old_codigo_producto := OLD.codigo_producto;
	old_precio_pactado := OLD.precio_pactado;
	old_usuario_id := OLD.usuario_id;
	old_fecha_registro := OLD.fecha_registro;
  
	usuario_registro_ := OLD.usuario_id;
	fecha_registro := NOW();
  END IF; 
  
  IF TG_OP = 'UPDATE' THEN
	old_contrato_cliente_id := OLD.contrato_cliente_id;
	old_codigo_producto := OLD.codigo_producto;
	old_precio_pactado := OLD.precio_pactado;
	old_usuario_id := OLD.usuario_id;
	old_fecha_registro := OLD.fecha_registro;

	new_contrato_cliente_id := NEW.contrato_cliente_id;
	new_codigo_producto := NEW.codigo_producto;
	new_precio_pactado := NEW.precio_pactado;
	new_usuario_id := NEW.usuario_id;
	new_fecha_registro := NEW.fecha_registro;
	
	usuario_registro_ := NEW.usuario_id;
	fecha_registro := NOW();
  
  END IF; 
  
  IF TG_OP = 'INSERT' THEN
	
	new_contrato_cliente_id := NEW.contrato_cliente_id;
	new_codigo_producto := NEW.codigo_producto;
	new_precio_pactado := NEW.precio_pactado;
	new_usuario_id := NEW.usuario_id;
	new_fecha_registro := NEW.fecha_registro;
  
	usuario_registro_ := NEW.usuario_id;
	fecha_registro := NOW();
  END IF;
  
  IF indicador.accion_id IS NOT NULL THEN
    INSERT INTO auditorias_generales
    (
      accion_id,
      novedad_indicador_id,
      table_name,
      
      campo_1, 
      nuevo_valor_1,
      antiguo_valor_1 ,    
      
      campo_2, 
      nuevo_valor_2,
      antiguo_valor_2,
      
      campo_3, 
      nuevo_valor_3,
      antiguo_valor_3,
      
      campo_4, 
      nuevo_valor_4,
      antiguo_valor_4,
      
      campo_5, 
      nuevo_valor_5,
      antiguo_valor_5,
   
     usuario_registro

    )
    VALUES
    (
      indicador.accion_id,
      indicador.novedad_indicador_id,
      'vnts_contratos_clientes_productos',
      
      'contrato_cliente_id',
      new_contrato_cliente_id,
      old_contrato_cliente_id,
      
      'codigo_producto',    
      new_codigo_producto,
      old_codigo_producto,
     
      'precio_pactado',
      new_precio_pactado,
      old_precio_pactado,
      
      'usuario_id',
      new_usuario_id,
      old_usuario_id,
      
      'fecha_registro',
      new_fecha_registro,
      old_fecha_registro,
               
     usuario_registro_
    );
  END IF;
    
RETURN NEW;
END
$$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER;

ALTER FUNCTION public.au_contrato_cliente_productos()
  OWNER TO "admin";
  
  CREATE TRIGGER aut_auditoria_contratos_productos
  AFTER INSERT OR UPDATE OR DELETE
  ON public.vnts_contratos_clientes_productos
  FOR EACH ROW
  EXECUTE PROCEDURE au_contrato_cliente_productos();

  /*
  * PEDIDOS AUDITORIA
  */
  CREATE OR REPLACE FUNCTION public.au_pedidos_cliente()
RETURNS trigger AS	
$$
DECLARE
  
  indicador RECORD;
  columnas RECORD;
    
	old_pedido_cliente_id integer;
	old_empresa_id character(2);
	old_tipo_id_tercero character varying(3);	
	old_tercero_id character varying(32);	
	old_fecha_registro timestamp;	
	old_usuario_id integer;	
	old_fecha_envio date;	
	old_estado character(1);
	old_tipo_id_vendedor character(3)	;
	old_vendedor_id character varying(32);	
	old_fecha_registro_anulacion 	timestamp;
	old_usuario_anulador integer;	
	old_observacion_anulacion 	text;
	old_observacion text;
  
	new_pedido_cliente_id integer;
	new_empresa_id character(2);
	new_tipo_id_tercero character varying(3);	
	new_tercero_id character varying(32);	
	new_fecha_registro timestamp;	
	new_usuario_id integer;	
	new_fecha_envio date;	
	new_estado character(1);
	new_tipo_id_vendedor character(3)	;
	new_vendedor_id character varying(32);	
	new_fecha_registro_anulacion 	timestamp;
	new_usuario_anulador integer;	
	new_observacion_anulacion 	text;
	new_observacion text;
  
  usuario_registro_ integer;
  fecha_registro timestamp;
  
BEGIN
  SELECT * INTO indicador
  FROM   acciones AC,
         novedad_x_acciones NA
  WHERE  AC.descripcion = TG_OP
  AND    NA.accion_id = AC.accion_id
  AND    NA.table_name = 'ventas_ordenes_pedidos';
 
 /* Inicializar Variables */
 
  IF TG_OP = 'DELETE' THEN
   
	old_pedido_cliente_id := OLD.pedido_cliente_id;
	old_empresa_id := OLD.empresa_id;
	old_tipo_id_tercero := OLD.tipo_id_tercero;
	old_tercero_id := OLD.tercero_id;
	old_fecha_registro := OLD.fecha_registro;
	old_usuario_id := OLD.usuario_id;
	old_fecha_envio := OLD.fecha_envio;
	old_estado := OLD.estado;
	old_tipo_id_vendedor := OLD.tipo_id_vendedor;
	old_vendedor_id := OLD.vendedor_id;
	old_fecha_registro_anulacion := OLD.fecha_registro_anulacion;
	old_usuario_anulador := OLD.usuario_anulador;
	old_observacion_anulacion := OLD.observacion_anulacion;
	old_observacion := OLD.observacion;
  
	usuario_registro_ := OLD.usuario_id;
	fecha_registro := NOW();
  END IF; 
  
  IF TG_OP = 'UPDATE' THEN
	old_pedido_cliente_id := OLD.pedido_cliente_id;
	old_empresa_id := OLD.empresa_id;
	old_tipo_id_tercero := OLD.tipo_id_tercero;
	old_tercero_id := OLD.tercero_id;
	old_fecha_registro := OLD.fecha_registro;
	old_usuario_id := OLD.usuario_id;
	old_fecha_envio := OLD.fecha_envio;
	old_estado := OLD.estado;
	old_tipo_id_vendedor := OLD.tipo_id_vendedor;
	old_vendedor_id := OLD.vendedor_id;
	old_fecha_registro_anulacion := OLD.fecha_registro_anulacion;
	old_usuario_anulador := OLD.usuario_anulador;
	old_observacion_anulacion := OLD.observacion_anulacion;
	old_observacion := OLD.observacion;

	new_pedido_cliente_id := NEW.pedido_cliente_id;
	new_empresa_id := NEW.empresa_id;
	new_tipo_id_tercero := NEW.tipo_id_tercero;
	new_tercero_id := NEW.tercero_id;
	new_fecha_registro := NEW.fecha_registro;
	new_usuario_id := NEW.usuario_id;
	new_fecha_envio := NEW.fecha_envio;
	new_estado := NEW.estado;
	new_tipo_id_vendedor := NEW.tipo_id_vendedor;
	new_vendedor_id := NEW.vendedor_id;
	new_fecha_registro_anulacion := NEW.fecha_registro_anulacion;
	new_usuario_anulador := NEW.usuario_anulador;
	new_observacion_anulacion := NEW.observacion_anulacion;
	new_observacion := NEW.observacion;
	
	usuario_registro_ := NEW.usuario_id;
	fecha_registro := NOW();
  
  END IF; 
  
  IF TG_OP = 'INSERT' THEN
	
	new_pedido_cliente_id := NEW.pedido_cliente_id;
	new_empresa_id := NEW.empresa_id;
	new_tipo_id_tercero := NEW.tipo_id_tercero;
	new_tercero_id := NEW.tercero_id;
	new_fecha_registro := NEW.fecha_registro;
	new_usuario_id := NEW.usuario_id;
	new_fecha_envio := NEW.fecha_envio;
	new_estado := NEW.estado;
	new_tipo_id_vendedor := NEW.tipo_id_vendedor;
	new_vendedor_id := NEW.vendedor_id;
	new_fecha_registro_anulacion := NEW.fecha_registro_anulacion;
	new_usuario_anulador := NEW.usuario_anulador;
	new_observacion_anulacion := NEW.observacion_anulacion;
	new_observacion := NEW.observacion;
  
	usuario_registro_ := NEW.usuario_id;
	fecha_registro := NOW();
  END IF;
  
  IF indicador.accion_id IS NOT NULL THEN
    INSERT INTO auditorias_generales
    (
      accion_id,
      novedad_indicador_id,
      table_name,
      
      campo_1, 
      nuevo_valor_1,
      antiguo_valor_1 ,    
      
      campo_2, 
      nuevo_valor_2,
      antiguo_valor_2,
      
      campo_3, 
      nuevo_valor_3,
      antiguo_valor_3,
      
      campo_4, 
      nuevo_valor_4,
      antiguo_valor_4,
      
      campo_5, 
      nuevo_valor_5,
      antiguo_valor_5,
      
      campo_6, 
      nuevo_valor_6,
      antiguo_valor_6,
      
      campo_7, 
      nuevo_valor_7,
      antiguo_valor_7,
      
      campo_8, 
      nuevo_valor_8,
      antiguo_valor_8,
      
      campo_9, 
      nuevo_valor_9,
      antiguo_valor_9,
      
      campo_10, 
      nuevo_valor_10,
      antiguo_valor_10,
      
      campo_11, 
      nuevo_valor_11,
      antiguo_valor_11,
      
      campo_12, 
      nuevo_valor_12,
      antiguo_valor_12,
      
      campo_13, 
      nuevo_valor_13,
      antiguo_valor_13,
      
      campo_14, 
      nuevo_valor_14,
      antiguo_valor_14,
   
     usuario_registro

    )
    VALUES
    (
      indicador.accion_id,
      indicador.novedad_indicador_id,
      'ventas_ordenes_pedidos',
      
      'pedido_cliente_id',
      new_pedido_cliente_id,
      old_pedido_cliente_id,
      
      'empresa_id',    
      new_empresa_id,
      old_empresa_id,
     
      'tipo_id_tercero',
      new_tipo_id_tercero,
      old_tipo_id_tercero,
      
      'tercero_id',
      new_tercero_id,
      old_tercero_id,
      
      'fecha_registro',
      new_fecha_registro,
      old_fecha_registro,
      
      'usuario_id',
      new_usuario_id,
      old_usuario_id,
      
      'fecha_envio',
      new_fecha_envio,
      old_fecha_envio,
      
      'estado',
      new_estado,
      old_estado,
      
      'tipo_id_vendedor',
      new_tipo_id_vendedor,
      old_tipo_id_vendedor,
      
      'vendedor_id',
      new_vendedor_id,
      old_vendedor_id,
      
      'fecha_registro_anulacion',
      new_fecha_registro_anulacion,
      old_fecha_registro_anulacion,
      
      'usuario_anulador',
      new_usuario_anulador,
      old_usuario_anulador,
      
      'observacion_anulacion',
      new_observacion_anulacion,
      old_observacion_anulacion,
      
      'observacion',
      new_observacion,
      old_observacion,
               
     usuario_registro_
    );
  END IF;
    
RETURN NEW;
END
$$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER;

ALTER FUNCTION public.au_pedidos_cliente()
  OWNER TO "admin";
  
  CREATE TRIGGER aut_auditoria_pedidos_cliente
  AFTER INSERT OR UPDATE OR DELETE
  ON public.ventas_ordenes_pedidos
  FOR EACH ROW
  EXECUTE PROCEDURE au_pedidos_cliente();
