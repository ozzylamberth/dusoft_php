   CREATE OR REPLACE FUNCTION public.au_facturas_recepciones_parciales_d()
RETURNS trigger AS
$$
DECLARE

  indicador RECORD;
  columnas RECORD;
  
	old_recepcion_parcial_id integer;
	old_item_id 	integer;
	old_codigo_producto 	character varying(50);
	old_cantidad 	numeric(18,0);
	old_valor double precision;
	old_porc_iva 	numeric(9,2);
	old_lote 	character varying(255);
	old_fecha_vencimiento date;
  
	new_recepcion_parcial_id integer;
	new_item_id 	integer;
	new_codigo_producto 	character varying(50);
	new_cantidad 	numeric(18,0);
	new_valor double precision;
	new_porc_iva 	numeric(9,2);
	new_lote 	character varying(255);
	new_fecha_vencimiento date;
	
	usuario_registro_ integer;
	fecha_registro timestamp;
  
BEGIN
  SELECT * INTO indicador
  FROM   acciones AC,
         novedad_x_acciones NA
  WHERE  AC.descripcion = TG_OP
  AND    NA.accion_id = AC.accion_id
  AND    NA.table_name = 'inv_recepciones_parciales_d';
 
 /* Inicializar Variables */
 
  IF TG_OP = 'DELETE' THEN
   
	
	old_recepcion_parcial_id := OLD.recepcion_parcial_id;
	old_item_id 	:= OLD.item_id; 	
	old_codigo_producto 	:= OLD.codigo_producto; 	
	old_cantidad 	:= OLD.cantidad; 	
	old_valor 	:= OLD.valor; 	
	old_porc_iva 	:= OLD.porc_iva; 	
	old_lote 	:= OLD.lote; 	
	old_fecha_vencimiento := OLD.fecha_vencimiento;
	
	usuario_registro_ := 2;
	fecha_registro := NOW();
  END IF; 
  
  IF TG_OP = 'UPDATE' THEN
	new_recepcion_parcial_id := NEW.recepcion_parcial_id;
	new_item_id 	:= NEW.item_id; 	
	new_codigo_producto 	:= NEW.codigo_producto; 	
	new_cantidad 	:= NEW.cantidad; 	
	new_valor 	:= NEW.valor; 	
	new_porc_iva 	:= NEW.porc_iva; 	
	new_lote 	:= NEW.lote; 	
	new_fecha_vencimiento := NEW.fecha_vencimiento;
	
	usuario_registro_ := 2;
	fecha_registro := NOW();
  
	old_recepcion_parcial_id := OLD.recepcion_parcial_id;
	old_item_id 	:= OLD.item_id; 	
	old_codigo_producto 	:= OLD.codigo_producto; 	
	old_cantidad 	:= OLD.cantidad; 	
	old_valor 	:= OLD.valor; 	
	old_porc_iva 	:= OLD.porc_iva; 	
	old_lote 	:= OLD.lote; 	
	old_fecha_vencimiento := OLD.fecha_vencimiento;
  END IF; 
  
  IF TG_OP = 'INSERT' THEN
	new_recepcion_parcial_id := NEW.recepcion_parcial_id;
	new_item_id 	:= NEW.item_id; 	
	new_codigo_producto 	:= NEW.codigo_producto; 	
	new_cantidad 	:= NEW.cantidad; 	
	new_valor 	:= NEW.valor; 	
	new_porc_iva 	:= NEW.porc_iva; 	
	new_lote 	:= NEW.lote; 	
	new_fecha_vencimiento := NEW.fecha_vencimiento;
	
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
       
     usuario_registro

    )
    VALUES
    (
      indicador.accion_id,
      indicador.novedad_indicador_id,
      'inv_recepciones_parciales_d',
      
      'recepcion_parcial_id',
      new_recepcion_parcial_id,
      old_recepcion_parcial_id,
      
      'item_id',    
      new_item_id,
      old_item_id,
      
      'codigo_producto',
      new_codigo_producto,
      old_codigo_producto,
      
      'cantidad',
      new_cantidad,
      old_cantidad,
      
      'valor',
      new_valor,
      old_valor,
      
      'porc_iva',
      new_porc_iva,
      old_porc_iva,
      
      'lote',
      new_lote,
      old_lote,
      
      'fecha_vencimiento',
      new_fecha_vencimiento,
      old_fecha_vencimiento,

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

ALTER FUNCTION public.au_facturas_recepciones_parciales_d()
  OWNER TO "admin";
  
  CREATE TRIGGER "aut_auditoria_recepciones parciales_d"
  AFTER INSERT OR UPDATE OR DELETE
  ON public.inv_recepciones_parciales_d
  FOR EACH ROW
  EXECUTE PROCEDURE au_facturas_recepciones_parciales_d();

  
  /*TRIGGER DE CABECERA DE LAS RECEPCIONES PARCIALES*/
  CREATE OR REPLACE FUNCTION public.au_facturas_recepciones_parciales()
RETURNS trigger AS
$$
DECLARE

  indicador RECORD;
  columnas RECORD;
  
	old_recepcion_parcial_id 	integer;
	old_empresa_id 	character varying(2);
	old_centro_utilidad 	character varying(2);
	old_bodega 	character varying(2);
	old_orden_pedido_id integer;	
	old_documento_id integer;	
	old_prefijo character varying(4);	
	old_numero 	integer;
	old_sw_facturado 	character(1);
	old_fecha_registro timestamp;	
	old_usuario_id integer;
  
	new_recepcion_parcial_id 	integer;
	new_empresa_id 	character varying(2);
	new_centro_utilidad 	character varying(2);
	new_bodega 	character varying(2);
	new_orden_pedido_id integer;	
	new_documento_id integer;	
	new_prefijo character varying(4);	
	new_numero 	integer;
	new_sw_facturado 	character(1);
	new_fecha_registro timestamp;	
	new_usuario_id integer;
	
	usuario_registro_ integer;
	fecha_registro timestamp;
  
BEGIN
  SELECT * INTO indicador
  FROM   acciones AC,
         novedad_x_acciones NA
  WHERE  AC.descripcion = TG_OP
  AND    NA.accion_id = AC.accion_id
  AND    NA.table_name = 'inv_recepciones_parciales';
 
 /* Inicializar Variables */
 
  IF TG_OP = 'DELETE' THEN
   
	old_recepcion_parcial_id := OLD.recepcion_parcial_id;
	old_empresa_id 	:= OLD.empresa_id; 	
	old_centro_utilidad 	:= OLD.centro_utilidad; 	
	old_bodega 	:= OLD.bodega; 	
	old_orden_pedido_id 	:= OLD.orden_pedido_id; 	
	old_documento_id 	:= OLD.documento_id; 	
	old_prefijo 	:= OLD.prefijo; 	
	old_numero 	:= OLD.numero; 	
	old_sw_facturado 	:= OLD.sw_facturado; 	
	old_fecha_registro 	:= OLD.fecha_registro; 	
	old_usuario_id := OLD.usuario_id;
	
	usuario_registro_ := OLD.usuario_id;
	fecha_registro := NOW();
  END IF; 
  
  IF TG_OP = 'UPDATE' THEN
	new_recepcion_parcial_id := NEW.recepcion_parcial_id;
	new_empresa_id 	:= NEW.empresa_id; 	
	new_centro_utilidad 	:= NEW.centro_utilidad; 	
	new_bodega 	:= NEW.bodega; 	
	new_orden_pedido_id 	:= NEW.orden_pedido_id; 	
	new_documento_id 	:= NEW.documento_id; 	
	new_prefijo 	:= NEW.prefijo; 	
	new_numero 	:= NEW.numero; 	
	new_sw_facturado 	:= NEW.sw_facturado; 	
	new_fecha_registro 	:= NEW.fecha_registro; 	
	new_usuario_id := NEW.usuario_id;
	
	usuario_registro_ := NEW.usuario_id;
	fecha_registro := NOW();
  
	old_recepcion_parcial_id := OLD.recepcion_parcial_id;
	old_empresa_id 	:= OLD.empresa_id; 	
	old_centro_utilidad 	:= OLD.centro_utilidad; 	
	old_bodega 	:= OLD.bodega; 	
	old_orden_pedido_id 	:= OLD.orden_pedido_id; 	
	old_documento_id 	:= OLD.documento_id; 	
	old_prefijo 	:= OLD.prefijo; 	
	old_numero 	:= OLD.numero; 	
	old_sw_facturado 	:= OLD.sw_facturado; 	
	old_fecha_registro 	:= OLD.fecha_registro; 	
	old_usuario_id := OLD.usuario_id;
  END IF; 
  
  IF TG_OP = 'INSERT' THEN
	new_recepcion_parcial_id := NEW.recepcion_parcial_id;
	new_empresa_id 	:= NEW.empresa_id; 	
	new_centro_utilidad 	:= NEW.centro_utilidad; 	
	new_bodega 	:= NEW.bodega; 	
	new_orden_pedido_id 	:= NEW.orden_pedido_id; 	
	new_documento_id 	:= NEW.documento_id; 	
	new_prefijo 	:= NEW.prefijo; 	
	new_numero 	:= NEW.numero; 	
	new_sw_facturado 	:= NEW.sw_facturado; 	
	new_fecha_registro 	:= NEW.fecha_registro; 	
	new_usuario_id := NEW.usuario_id;
	
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

     usuario_registro

    )
    VALUES
    (
      indicador.accion_id,
      indicador.novedad_indicador_id,
      'inv_recepciones_parciales',
      
      'recepcion_parcial_id',
      new_recepcion_parcial_id,
      old_recepcion_parcial_id,
      
      'empresa_id',    
      new_empresa_id,
      old_empresa_id,
      
      'centro_utilidad',
      new_centro_utilidad,
      old_centro_utilidad,
      
      'bodega',
      new_bodega,
      old_bodega,
      
      'orden_pedido_id',
      new_orden_pedido_id,
      old_orden_pedido_id,
      
      'documento_id',
      new_documento_id,
      old_documento_id,
      
      'prefijo',
      new_prefijo,
      old_prefijo,
      
      'numero',
      new_numero,
      old_numero,

      'sw_facturado',
      new_sw_facturado,
      old_sw_facturado,

      'fecha_registro',
      new_fecha_registro,
      old_fecha_registro,

      'usuario_id',
      new_usuario_id,
      old_usuario_id,
	  
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

ALTER FUNCTION public.au_facturas_recepciones_parciales()
  OWNER TO "admin";
  
  CREATE TRIGGER aut_auditoria_recepciones_parciales
  AFTER INSERT OR UPDATE OR DELETE
  ON public.inv_recepciones_parciales
  FOR EACH ROW
  EXECUTE PROCEDURE au_facturas_recepciones_parciales();

  /*
  AUDITORIA FACTURAS PROVEEDOR
  */
    
     CREATE OR REPLACE FUNCTION public.au_facturas_proveedores_d()
RETURNS trigger AS
$$
DECLARE

  indicador RECORD;
  columnas RECORD;
  
	old_codigo_producto 	character varying(50);
	old_porc_iva 	numeric(9,2);
	old_recepcion_parcial_id integer;	
	old_cantidad 	numeric(18,2);
	old_valor 	double precision;
	old_lote character varying(255);	
	old_fecha_vencimiento date;	
	old_numero_factura 	character varying(40);
	old_item_id 	integer;
	old_cantidad_devuelta integer;
	old_codigo_proveedor_id integer;
  
	new_codigo_producto 	character varying(50);
	new_porc_iva 	numeric(9,2);
	new_recepcion_parcial_id integer;	
	new_cantidad 	numeric(18,2);
	new_valor 	double precision;
	new_lote character varying(255);	
	new_fecha_vencimiento date;	
	new_numero_factura 	character varying(40);
	new_item_id 	integer;
	new_cantidad_devuelta integer;
	new_codigo_proveedor_id integer;
	
	usuario_registro_ integer;
	fecha_registro timestamp;
  
BEGIN
  SELECT * INTO indicador
  FROM   acciones AC,
         novedad_x_acciones NA
  WHERE  AC.descripcion = TG_OP
  AND    NA.accion_id = AC.accion_id
  AND    NA.table_name = 'inv_facturas_proveedores_d';
 
 /* Inicializar Variables */
 
  IF TG_OP = 'DELETE' THEN
   
	
	old_codigo_producto 	:= OLD.codigo_producto;
	old_porc_iva 	:= OLD.porc_iva; 	
	old_recepcion_parcial_id 	:= OLD.recepcion_parcial_id; 	
	old_cantidad 	:= OLD.cantidad; 	
	old_valor 	:= OLD.valor; 	
	old_lote 	:= OLD.lote; 	
	old_fecha_vencimiento 	:= OLD.fecha_vencimiento; 	
	old_numero_factura 	:= OLD.numero_factura; 	
	old_item_id 	:= OLD.item_id; 	
	old_cantidad_devuelta 	:= OLD.cantidad_devuelta; 	
	old_codigo_proveedor_id := OLD.codigo_proveedor_id;
	
	usuario_registro_ := 2;
	fecha_registro := NOW();
  END IF; 
  
  IF TG_OP = 'UPDATE' THEN
	new_codigo_producto 	:= NEW.codigo_producto;
	new_porc_iva 	:= NEW.porc_iva; 	
	new_recepcion_parcial_id 	:= NEW.recepcion_parcial_id; 	
	new_cantidad 	:= NEW.cantidad; 	
	new_valor 	:= NEW.valor; 	
	new_lote 	:= NEW.lote; 	
	new_fecha_vencimiento 	:= NEW.fecha_vencimiento; 	
	new_numero_factura 	:= NEW.numero_factura; 	
	new_item_id 	:= NEW.item_id; 	
	new_cantidad_devuelta 	:= NEW.cantidad_devuelta; 	
	new_codigo_proveedor_id := NEW.codigo_proveedor_id;
	
	usuario_registro_ := 2;
	fecha_registro := NOW();
  
	old_codigo_producto 	:= OLD.codigo_producto;
	old_porc_iva 	:= OLD.porc_iva; 	
	old_recepcion_parcial_id 	:= OLD.recepcion_parcial_id; 	
	old_cantidad 	:= OLD.cantidad; 	
	old_valor 	:= OLD.valor; 	
	old_lote 	:= OLD.lote; 	
	old_fecha_vencimiento 	:= OLD.fecha_vencimiento; 	
	old_numero_factura 	:= OLD.numero_factura; 	
	old_item_id 	:= OLD.item_id; 	
	old_cantidad_devuelta 	:= OLD.cantidad_devuelta; 	
	old_codigo_proveedor_id := OLD.codigo_proveedor_id;
  END IF; 
  
  IF TG_OP = 'INSERT' THEN
	new_codigo_producto 	:= NEW.codigo_producto;
	new_porc_iva 	:= NEW.porc_iva; 	
	new_recepcion_parcial_id 	:= NEW.recepcion_parcial_id; 	
	new_cantidad 	:= NEW.cantidad; 	
	new_valor 	:= NEW.valor; 	
	new_lote 	:= NEW.lote; 	
	new_fecha_vencimiento 	:= NEW.fecha_vencimiento; 	
	new_numero_factura 	:= NEW.numero_factura; 	
	new_item_id 	:= NEW.item_id; 	
	new_cantidad_devuelta 	:= NEW.cantidad_devuelta; 	
	new_codigo_proveedor_id := NEW.codigo_proveedor_id;
	
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
      
      campo_11, 
      nuevo_valor_11,
      antiguo_valor_11,
       
     usuario_registro

    )
    VALUES
    (
      indicador.accion_id,
      indicador.novedad_indicador_id,
      'inv_facturas_proveedores_d',
      
      'codigo_producto',
      new_codigo_producto,
      old_codigo_producto,
      
      'porc_iva',    
      new_porc_iva,
      old_porc_iva,
      
      'recepcion_parcial_id',
      new_recepcion_parcial_id,
      old_recepcion_parcial_id,
      
      'cantidad',
      new_cantidad,
      old_cantidad,
      
      'valor',
      new_valor,
      old_valor,
      
      'lote',
      new_lote,
      old_lote,
      
      'fecha_vencimiento',
      new_fecha_vencimiento,
      old_fecha_vencimiento,
      
      'numero_factura',
      new_numero_factura,
      old_numero_factura,
      
      'item_id',
      new_item_id,
      old_item_id,
      
      'cantidad_devuelta',
      new_cantidad_devuelta,
      old_cantidad_devuelta,
      
      'codigo_proveedor_id',
      new_codigo_proveedor_id,
      old_codigo_proveedor_id,
	  
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

ALTER FUNCTION public.au_facturas_proveedores_d()
  OWNER TO "admin";
  
  CREATE TRIGGER aut_auditoria_facturas_proveedor_d
  AFTER INSERT OR UPDATE OR DELETE
  ON public.inv_facturas_proveedores_d
  FOR EACH ROW
  EXECUTE PROCEDURE au_facturas_proveedores_d();

  /*
  * AUDITORIA FACTURAS PROVEEDOR
  */
    
    CREATE OR REPLACE FUNCTION public.aut_auditoria_facturas_proveedor()
RETURNS trigger AS
$$
DECLARE

  indicador RECORD;
  columnas RECORD;
  
	old_codigo_proveedor_id 	integer;
	old_numero_factura 	character varying(40);
	old_fecha_registro 	timestamp;
	old_observaciones 	text;
	old_empresa_id 	character varying(2);
	old_centro_utilidad 	character varying(2);
	old_bodega 	character varying(2);
	old_valor_descuento 	numeric(18,4);
	old_porc_rtf 	numeric(9,2);
	old_porc_ica 	numeric(9,2);
	old_usuario_id 	integer;
	old_sw_verificado 	character(1);
	old_observacion_verificacion 	text;
	old_calificacion_verificacion 	character(1);
	old_usuario_id_verificador 	integer;
	old_fecha_verificacion 	timestamp;
	old_sw_estado 	character(1);
	old_valor_factura 	numeric(20,4);
	old_saldo 	numeric(20,4);
	old_valor_notas_debito 	numeric(20,4);
	old_valor_notas_credito 	numeric(20,4);
	old_porc_rtiva 	numeric(9,2);
  
	new_codigo_proveedor_id 	integer;
	new_numero_factura 	character varying(40);
	new_fecha_registro 	timestamp;
	new_observaciones 	text;
	new_empresa_id 	character varying(2);
	new_centro_utilidad 	character varying(2);
	new_bodega 	character varying(2);
	new_valor_descuento 	numeric(18,4);
	new_porc_rtf 	numeric(9,2);
	new_porc_ica 	numeric(9,2);
	new_usuario_id 	integer;
	new_sw_verificado 	character(1);
	new_observacion_verificacion 	text;
	new_calificacion_verificacion 	character(1);
	new_usuario_id_verificador 	integer;
	new_fecha_verificacion 	timestamp;
	new_sw_estado 	character(1);
	new_valor_factura 	numeric(20,4);
	new_saldo 	numeric(20,4);
	new_valor_notas_debito 	numeric(20,4);
	new_valor_notas_credito 	numeric(20,4);
	new_porc_rtiva 	numeric(9,2);
	
	usuario_registro_ integer;
	fecha_registro timestamp;
  
BEGIN
  SELECT * INTO indicador
  FROM   acciones AC,
         novedad_x_acciones NA
  WHERE  AC.descripcion = TG_OP
  AND    NA.accion_id = AC.accion_id
  AND    NA.table_name = 'inv_facturas_proveedores';
 
 /* Inicializar Variables */
 
  IF TG_OP = 'DELETE' THEN
   
	old_codigo_proveedor_id 	:= OLD.codigo_proveedor_id; 	
	old_numero_factura 	:= OLD.numero_factura; 	
	old_fecha_registro 	:= OLD.fecha_registro; 	
	old_observaciones 	:= OLD.observaciones; 	
	old_empresa_id 	:= OLD.empresa_id; 	
	old_centro_utilidad 	:= OLD.centro_utilidad; 	
	old_bodega 	:= OLD.bodega; 	
	old_valor_descuento 	:= OLD.valor_descuento; 	
	old_porc_rtf 	:= OLD.porc_rtf; 	
	old_porc_ica 	:= OLD.porc_ica; 	
	old_usuario_id 	:= OLD.usuario_id; 	
	old_sw_verificado 	:= OLD.sw_verificado; 	
	old_observacion_verificacion 	:= OLD.observacion_verificacion; 	
	old_calificacion_verificacion 	:= OLD.calificacion_verificacion; 	
	old_usuario_id_verificador 	:= OLD.usuario_id_verificador; 	
	old_fecha_verificacion 	:= OLD.fecha_verificacion; 	
	old_sw_estado 	:= OLD.sw_estado; 	
	old_valor_factura 	:= OLD.valor_factura; 	
	old_saldo 	:= OLD.saldo; 	
	old_valor_notas_debito 	:= OLD.valor_notas_debito; 	
	old_valor_notas_credito 	:= OLD.valor_notas_credito; 	
	old_porc_rtiva 	:= OLD.porc_rtiva; 	
	
	usuario_registro_ := OLD.usuario_id;
	fecha_registro := NOW();
  END IF; 
  
  IF TG_OP = 'UPDATE' THEN
	new_codigo_proveedor_id 	:= NEW.codigo_proveedor_id; 	
	new_numero_factura 	:= NEW.numero_factura; 	
	new_fecha_registro 	:= NEW.fecha_registro; 	
	new_observaciones 	:= NEW.observaciones; 	
	new_empresa_id 	:= NEW.empresa_id; 	
	new_centro_utilidad 	:= NEW.centro_utilidad; 	
	new_bodega 	:= NEW.bodega; 	
	new_valor_descuento 	:= NEW.valor_descuento; 	
	new_porc_rtf 	:= NEW.porc_rtf; 	
	new_porc_ica 	:= NEW.porc_ica; 	
	new_usuario_id 	:= NEW.usuario_id; 	
	new_sw_verificado 	:= NEW.sw_verificado; 	
	new_observacion_verificacion 	:= NEW.observacion_verificacion; 	
	new_calificacion_verificacion 	:= NEW.calificacion_verificacion; 	
	new_usuario_id_verificador 	:= NEW.usuario_id_verificador; 	
	new_fecha_verificacion 	:= NEW.fecha_verificacion; 	
	new_sw_estado 	:= NEW.sw_estado; 	
	new_valor_factura 	:= NEW.valor_factura; 	
	new_saldo 	:= NEW.saldo; 	
	new_valor_notas_debito 	:= NEW.valor_notas_debito; 	
	new_valor_notas_credito 	:= NEW.valor_notas_credito; 	
	new_porc_rtiva 	:= NEW.porc_rtiva; 	
	
	usuario_registro_ := NEW.usuario_id;
	fecha_registro := NOW();
  
	old_codigo_proveedor_id 	:= OLD.codigo_proveedor_id; 	
	old_numero_factura 	:= OLD.numero_factura; 	
	old_fecha_registro 	:= OLD.fecha_registro; 	
	old_observaciones 	:= OLD.observaciones; 	
	old_empresa_id 	:= OLD.empresa_id; 	
	old_centro_utilidad 	:= OLD.centro_utilidad; 	
	old_bodega 	:= OLD.bodega; 	
	old_valor_descuento 	:= OLD.valor_descuento; 	
	old_porc_rtf 	:= OLD.porc_rtf; 	
	old_porc_ica 	:= OLD.porc_ica; 	
	old_usuario_id 	:= OLD.usuario_id; 	
	old_sw_verificado 	:= OLD.sw_verificado; 	
	old_observacion_verificacion 	:= OLD.observacion_verificacion; 	
	old_calificacion_verificacion 	:= OLD.calificacion_verificacion; 	
	old_usuario_id_verificador 	:= OLD.usuario_id_verificador; 	
	old_fecha_verificacion 	:= OLD.fecha_verificacion; 	
	old_sw_estado 	:= OLD.sw_estado; 	
	old_valor_factura 	:= OLD.valor_factura; 	
	old_saldo 	:= OLD.saldo; 	
	old_valor_notas_debito 	:= OLD.valor_notas_debito; 	
	old_valor_notas_credito 	:= OLD.valor_notas_credito; 	
	old_porc_rtiva 	:= OLD.porc_rtiva; 	
  END IF; 
  
  IF TG_OP = 'INSERT' THEN
	new_codigo_proveedor_id 	:= NEW.codigo_proveedor_id; 	
	new_numero_factura 	:= NEW.numero_factura; 	
	new_fecha_registro 	:= NEW.fecha_registro; 	
	new_observaciones 	:= NEW.observaciones; 	
	new_empresa_id 	:= NEW.empresa_id; 	
	new_centro_utilidad 	:= NEW.centro_utilidad; 	
	new_bodega 	:= NEW.bodega; 	
	new_valor_descuento 	:= NEW.valor_descuento; 	
	new_porc_rtf 	:= NEW.porc_rtf; 	
	new_porc_ica 	:= NEW.porc_ica; 	
	new_usuario_id 	:= NEW.usuario_id; 	
	new_sw_verificado 	:= NEW.sw_verificado; 	
	new_observacion_verificacion 	:= NEW.observacion_verificacion; 	
	new_calificacion_verificacion 	:= NEW.calificacion_verificacion; 	
	new_usuario_id_verificador 	:= NEW.usuario_id_verificador; 	
	new_fecha_verificacion 	:= NEW.fecha_verificacion; 	
	new_sw_estado 	:= NEW.sw_estado; 	
	new_valor_factura 	:= NEW.valor_factura; 	
	new_saldo 	:= NEW.saldo; 	
	new_valor_notas_debito 	:= NEW.valor_notas_debito; 	
	new_valor_notas_credito 	:= NEW.valor_notas_credito; 	
	new_porc_rtiva 	:= NEW.porc_rtiva; 	
	
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

      campo_20, 
      nuevo_valor_20,
      antiguo_valor_20,

      campo_21, 
      nuevo_valor_21,
      antiguo_valor_21,

      campo_22, 
      nuevo_valor_22,
      antiguo_valor_22,

     usuario_registro

    )
    VALUES
    (
      indicador.accion_id,
      indicador.novedad_indicador_id,
      'inv_facturas_proveedores',
      
      'codigo_proveedor_id',
      new_codigo_proveedor_id,
      old_codigo_proveedor_id,
      
      'numero_factura',
      new_numero_factura,
      old_numero_factura,
      
      'fecha_registro',
      new_fecha_registro,
      old_fecha_registro,
      
      'observaciones',
      new_observaciones,
      old_observaciones,
      
      'empresa_id',
      new_empresa_id,
      old_empresa_id,
      
      'centro_utilidad',
      new_centro_utilidad,
      old_centro_utilidad,
      
      'bodega',
      new_bodega,
      old_bodega,
      
      'valor_descuento',
      new_valor_descuento,
      old_valor_descuento,
      
      'porc_rtf',
      new_porc_rtf,
      old_porc_rtf,
      
      'porc_ica',
      new_porc_ica,
      old_porc_ica,
      
      'usuario_id',
      new_usuario_id,
      old_usuario_id,
      
      'sw_verificado',
      new_sw_verificado,
      old_sw_verificado,
      
      'observacion_verificacion',
      new_observacion_verificacion,
      old_observacion_verificacion,
      
      'calificacion_verificacion',
      new_calificacion_verificacion,
      old_calificacion_verificacion,
      
      'usuario_id_verificador',
      new_usuario_id_verificador,
      old_usuario_id_verificador,
      
      'fecha_verificacion',
      new_fecha_verificacion,
      old_fecha_verificacion,
      
      'sw_estado',
      new_sw_estado,
      old_sw_estado,
      
      'valor_factura',
      new_valor_factura,
      old_valor_factura,
      
      'saldo',
      new_saldo,
      old_saldo,
      
      'valor_notas_debito',
      new_valor_notas_debito,
      old_valor_notas_debito,
      
      'valor_notas_credito',
      new_valor_notas_credito,
      old_valor_notas_credito,
      
      'porc_rtiva',
      new_porc_rtiva,
      old_porc_rtiva,
	  
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

ALTER FUNCTION public.aut_auditoria_facturas_proveedor()
  OWNER TO "admin";
  
  CREATE TRIGGER aut_auditoria_facturas_proveedor
  AFTER INSERT OR UPDATE OR DELETE
  ON public.inv_facturas_proveedores
  FOR EACH ROW
  EXECUTE PROCEDURE aut_auditoria_facturas_proveedor();

  
    /*
  AUDITORIA FORMULA EXT. MEDICAMENTOS
  */
     CREATE OR REPLACE FUNCTION public.au_formula_ext_medicamentos()
RETURNS trigger AS
$$
DECLARE

  indicador RECORD;
  columnas RECORD;
  
	old_fe_medicamento_id 	integer;
	old_formula_id 	integer;
	old_codigo_producto 	character varying(40);
	old_cantidad 	numeric(7,2);
	old_observacion 	text;
	old_tiempo_tratamiento 	integer;
	old_unidad_tiempo_tratamiento 	character(1);
	old_sw_marcado 	character(1);
	old_sw_autorizado 	character(1);
	old_usuario_autoriza_id 	integer;
	old_observacion_autorizacion 	text;
	old_fecha_registro_autorizacion 	timestamp;
  
	new_fe_medicamento_id 	integer;
	new_formula_id 	integer;
	new_codigo_producto 	character varying(40);
	new_cantidad 	numeric(7,2);
	new_observacion 	text;
	new_tiempo_tratamiento 	integer;
	new_unidad_tiempo_tratamiento 	character(1);
	new_sw_marcado 	character(1);
	new_sw_autorizado 	character(1);
	new_usuario_autoriza_id 	integer;
	new_observacion_autorizacion 	text;
	new_fecha_registro_autorizacion 	timestamp;
	
	usuario_registro_ integer;
	fecha_registro timestamp;
  
BEGIN
  SELECT * INTO indicador
  FROM   acciones AC,
         novedad_x_acciones NA
  WHERE  AC.descripcion = TG_OP
  AND    NA.accion_id = AC.accion_id
  AND    NA.table_name = 'esm_formula_externa_medicamentos';
 
 /* Inicializar Variables */
 
  IF TG_OP = 'DELETE' THEN
   	
	old_fe_medicamento_id 	:= OLD.fe_medicamento_id; 	
	old_formula_id 	:= OLD.formula_id; 	
	old_codigo_producto 	:= OLD.codigo_producto; 	
	old_cantidad 	:= OLD.cantidad; 	
	old_observacion 	:= OLD.observacion; 	
	old_tiempo_tratamiento 	:= OLD.tiempo_tratamiento; 	
	old_unidad_tiempo_tratamiento 	:= OLD.unidad_tiempo_tratamiento; 	
	old_sw_marcado 	:= OLD.sw_marcado; 	
	old_sw_autorizado 	:= OLD.sw_autorizado; 	
	old_usuario_autoriza_id 	:= OLD.usuario_autoriza_id; 	
	old_observacion_autorizacion 	:= OLD.observacion_autorizacion; 	
	old_fecha_registro_autorizacion 	:= OLD.fecha_registro_autorizacion; 	
	
	usuario_registro_ := 2;
	fecha_registro := NOW();
  END IF; 
  
  IF TG_OP = 'UPDATE' THEN
	new_fe_medicamento_id 	:= NEW.fe_medicamento_id; 	
	new_formula_id 	:= NEW.formula_id; 	
	new_codigo_producto 	:= NEW.codigo_producto; 	
	new_cantidad 	:= NEW.cantidad; 	
	new_observacion 	:= NEW.observacion; 	
	new_tiempo_tratamiento 	:= NEW.tiempo_tratamiento; 	
	new_unidad_tiempo_tratamiento 	:= NEW.unidad_tiempo_tratamiento; 	
	new_sw_marcado 	:= NEW.sw_marcado; 	
	new_sw_autorizado 	:= NEW.sw_autorizado; 	
	new_usuario_autoriza_id 	:= NEW.usuario_autoriza_id; 	
	new_observacion_autorizacion 	:= NEW.observacion_autorizacion; 	
	new_fecha_registro_autorizacion 	:= NEW.fecha_registro_autorizacion; 	
	
	usuario_registro_ := 2;
	fecha_registro := NOW();
  
	old_fe_medicamento_id 	:= OLD.fe_medicamento_id; 	
	old_formula_id 	:= OLD.formula_id; 	
	old_codigo_producto 	:= OLD.codigo_producto; 	
	old_cantidad 	:= OLD.cantidad; 	
	old_observacion 	:= OLD.observacion; 	
	old_tiempo_tratamiento 	:= OLD.tiempo_tratamiento; 	
	old_unidad_tiempo_tratamiento 	:= OLD.unidad_tiempo_tratamiento; 	
	old_sw_marcado 	:= OLD.sw_marcado; 	
	old_sw_autorizado 	:= OLD.sw_autorizado; 	
	old_usuario_autoriza_id 	:= OLD.usuario_autoriza_id; 	
	old_observacion_autorizacion 	:= OLD.observacion_autorizacion; 	
	old_fecha_registro_autorizacion 	:= OLD.fecha_registro_autorizacion; 	
  END IF; 
  
  IF TG_OP = 'INSERT' THEN
	new_fe_medicamento_id 	:= NEW.fe_medicamento_id; 	
	new_formula_id 	:= NEW.formula_id; 	
	new_codigo_producto 	:= NEW.codigo_producto; 	
	new_cantidad 	:= NEW.cantidad; 	
	new_observacion 	:= NEW.observacion; 	
	new_tiempo_tratamiento 	:= NEW.tiempo_tratamiento; 	
	new_unidad_tiempo_tratamiento 	:= NEW.unidad_tiempo_tratamiento; 	
	new_sw_marcado 	:= NEW.sw_marcado; 	
	new_sw_autorizado 	:= NEW.sw_autorizado; 	
	new_usuario_autoriza_id 	:= NEW.usuario_autoriza_id; 	
	new_observacion_autorizacion 	:= NEW.observacion_autorizacion; 	
	new_fecha_registro_autorizacion 	:= NEW.fecha_registro_autorizacion; 	
	
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
      'esm_formula_externa_medicamentos',
      
      'fe_medicamento_id',
      new_fe_medicamento_id,
      old_fe_medicamento_id,
      
      'formula_id',
      new_formula_id,
      old_formula_id,
      
      'codigo_producto',
      new_codigo_producto,
      old_codigo_producto,
      
      'cantidad',
      new_cantidad,
      old_cantidad,
      
      'observacion',
      new_observacion,
      old_observacion,
      
      'tiempo_tratamiento',
      new_tiempo_tratamiento,
      old_tiempo_tratamiento,
      
      'unidad_tiempo_tratamiento',
      new_unidad_tiempo_tratamiento,
      old_unidad_tiempo_tratamiento,
      
      'sw_marcado',
      new_sw_marcado,
      old_sw_marcado,
      
      'sw_autorizado',
      new_sw_autorizado,
      old_sw_autorizado,
      
      'usuario_autoriza_id',
      new_usuario_autoriza_id,
      old_usuario_autoriza_id,
      
      'observacion_autorizacion',
      new_observacion_autorizacion,
      old_observacion_autorizacion,
      
      'fecha_registro_autorizacion',
      new_fecha_registro_autorizacion,
      old_fecha_registro_autorizacion,
	  
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

ALTER FUNCTION public.au_formula_ext_medicamentos()
  OWNER TO "admin";
  
  CREATE TRIGGER aut_auditoria_formula_ext_medicamentos
  AFTER INSERT OR UPDATE OR DELETE
  ON public.esm_formula_externa_medicamentos
  FOR EACH ROW
  EXECUTE PROCEDURE au_formula_ext_medicamentos();

  
  /*
  * AUDITORIA BODEGAS DOCUMENTOS_D
  */
     CREATE OR REPLACE FUNCTION public.au_bodegas_documentos_d()
RETURNS trigger AS
$$
DECLARE

  indicador RECORD;
  columnas RECORD;
  
	old_consecutivo 	integer;
	old_codigo_producto 	character varying(50);
	old_cantidad 	numeric(14,4);
	old_total_costo 	numeric(13,2);
	old_bodegas_doc_id 	integer;
	old_numeracion 	integer;
	old_iva_compra 	numeric(5,3);
	old_fecha_vencimiento 	date;
	old_lote character varying(255);
	old_sw_pactado 	character varying(1);
	old_cantidad_devuelta 	numeric(14,4);
	old_total_venta numeric (13,2);
  
	new_consecutivo 	integer;
	new_codigo_producto 	character varying(50);
	new_cantidad 	numeric(14,4);
	new_total_costo 	numeric(13,2);
	new_bodegas_doc_id 	integer;
	new_numeracion 	integer;
	new_iva_compra 	numeric(5,3);
	new_fecha_vencimiento 	date;
	new_lote character varying(255);
	new_sw_pactado 	character varying(1);
	new_cantidad_devuelta 	numeric(14,4);
	new_total_venta numeric (13,2);
	
	usuario_registro_ integer;
	fecha_registro timestamp;
  
BEGIN
  SELECT * INTO indicador
  FROM   acciones AC,
         novedad_x_acciones NA
  WHERE  AC.descripcion = TG_OP
  AND    NA.accion_id = AC.accion_id
  AND    NA.table_name = 'bodegas_documentos_d';
 
 /* Inicializar Variables */
 
  IF TG_OP = 'DELETE' THEN
   	
	old_consecutivo 	:= OLD.consecutivo; 	
	old_codigo_producto 	:= OLD.codigo_producto; 	
	old_cantidad 	:= OLD.cantidad; 	
	old_total_costo 	:= OLD.total_costo; 	
	old_bodegas_doc_id 	:= OLD.bodegas_doc_id; 	
	old_numeracion 	:= OLD.numeracion; 	
	old_iva_compra 	:= OLD.iva_compra; 	
	old_fecha_vencimiento 	:= OLD.fecha_vencimiento; 	
	old_lote 	:= OLD.lote; 	
	old_sw_pactado 	:= OLD.sw_pactado; 	
	old_cantidad_devuelta 	:= OLD.cantidad_devuelta; 	
	old_total_venta := OLD.total_venta;
	
	usuario_registro_ := 2;
	fecha_registro := NOW();
  END IF; 
  
  IF TG_OP = 'UPDATE' THEN
	new_consecutivo 	:= NEW.consecutivo; 	
	new_codigo_producto 	:= NEW.codigo_producto; 	
	new_cantidad 	:= NEW.cantidad; 	
	new_total_costo 	:= NEW.total_costo; 	
	new_bodegas_doc_id 	:= NEW.bodegas_doc_id; 	
	new_numeracion 	:= NEW.numeracion; 	
	new_iva_compra 	:= NEW.iva_compra; 	
	new_fecha_vencimiento 	:= NEW.fecha_vencimiento; 	
	new_lote 	:= NEW.lote; 	
	new_sw_pactado 	:= NEW.sw_pactado; 	
	new_cantidad_devuelta 	:= NEW.cantidad_devuelta; 	
	new_total_venta := NEW.total_venta; 	
	
	usuario_registro_ := 2;
	fecha_registro := NOW();
  
	old_consecutivo 	:= OLD.consecutivo; 	
	old_codigo_producto 	:= OLD.codigo_producto; 	
	old_cantidad 	:= OLD.cantidad; 	
	old_total_costo 	:= OLD.total_costo; 	
	old_bodegas_doc_id 	:= OLD.bodegas_doc_id; 	
	old_numeracion 	:= OLD.numeracion; 	
	old_iva_compra 	:= OLD.iva_compra; 	
	old_fecha_vencimiento 	:= OLD.fecha_vencimiento; 	
	old_lote 	:= OLD.lote; 	
	old_sw_pactado 	:= OLD.sw_pactado; 	
	old_cantidad_devuelta 	:= OLD.cantidad_devuelta; 	
	old_total_venta := OLD.total_venta;
  END IF; 
  
  IF TG_OP = 'INSERT' THEN
	new_consecutivo 	:= NEW.consecutivo; 	
	new_codigo_producto 	:= NEW.codigo_producto; 	
	new_cantidad 	:= NEW.cantidad; 	
	new_total_costo 	:= NEW.total_costo; 	
	new_bodegas_doc_id 	:= NEW.bodegas_doc_id; 	
	new_numeracion 	:= NEW.numeracion; 	
	new_iva_compra 	:= NEW.iva_compra; 	
	new_fecha_vencimiento 	:= NEW.fecha_vencimiento; 	
	new_lote 	:= NEW.lote; 	
	new_sw_pactado 	:= NEW.sw_pactado; 	
	new_cantidad_devuelta 	:= NEW.cantidad_devuelta; 	
	new_total_venta := NEW.total_venta;
	
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
      'bodegas_documentos_d',
      
      'consecutivo',
      new_consecutivo,
      old_consecutivo,
      
      'codigo_producto',
      new_codigo_producto,
      old_codigo_producto,
      
      'cantidad',
      new_cantidad,
      old_cantidad,
      
      'total_costo',
      new_total_costo,
      old_total_costo,
      
      'bodegas_doc_id',
      new_bodegas_doc_id,
      old_bodegas_doc_id,
      
      'numeracion',
      new_numeracion,
      old_numeracion,
      
      'iva_compra',
      new_iva_compra,
      old_iva_compra,
      
      'fecha_vencimiento',
      new_fecha_vencimiento,
      old_fecha_vencimiento,
      
      'lote',
      new_lote,
      old_lote,
      
      'sw_pactado',
      new_sw_pactado,
      old_sw_pactado,
      
      'cantidad_devuelta',
      new_cantidad_devuelta,
      old_cantidad_devuelta,
      
      'total_venta',
      new_total_venta,
      old_total_venta,
	  
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

ALTER FUNCTION public.au_bodegas_documentos_d()
  OWNER TO "admin";
  
  CREATE TRIGGER aut_auditoria_bodegas_documentos_d
  AFTER INSERT OR UPDATE OR DELETE
  ON public.bodegas_documentos_d
  FOR EACH ROW
  EXECUTE PROCEDURE au_bodegas_documentos_d();

  
  /*
  * AUDITORIA BODEGAS DOCUMENTOS
  */
    
    CREATE OR REPLACE FUNCTION public.au_bodegas_documentos()
RETURNS trigger AS
$$
DECLARE

  indicador RECORD;
  columnas RECORD;
  
	old_bodegas_doc_id 	integer;
	old_numeracion 	integer;
	old_fecha 	date;
	old_total_costo 	numeric(13,2);
	old_transaccion 	integer;
	old_observacion 	text;
	old_usuario_id 	integer;
	old_fecha_registro 	timestamp;
	old_centro_utilidad_transferencia character varying(2);	
	old_bodega_destino_transferencia character varying(2);
  
	new_bodegas_doc_id 	integer;
	new_numeracion 	integer;
	new_fecha 	date;
	new_total_costo 	numeric(13,2);
	new_transaccion 	integer;
	new_observacion 	text;
	new_usuario_id 	integer;
	new_fecha_registro 	timestamp;
	new_centro_utilidad_transferencia character varying(2);	
	new_bodega_destino_transferencia character varying(2);
	
	usuario_registro_ integer;
	fecha_registro timestamp;
  
BEGIN
  SELECT * INTO indicador
  FROM   acciones AC,
         novedad_x_acciones NA
  WHERE  AC.descripcion = TG_OP
  AND    NA.accion_id = AC.accion_id
  AND    NA.table_name = 'bodegas_documentos';
 
 /* Inicializar Variables */
 
  IF TG_OP = 'DELETE' THEN
   
	old_bodegas_doc_id 	:= OLD.bodegas_doc_id; 	
	old_numeracion 	:= OLD.numeracion; 	
	old_fecha 	:= OLD.fecha; 	
	old_total_costo 	:= OLD.total_costo; 	
	old_transaccion 	:= OLD.transaccion; 	
	old_observacion 	:= OLD.observacion; 	
	old_usuario_id 	:= OLD.usuario_id; 	
	old_fecha_registro 	:= OLD.fecha_registro; 	
	old_centro_utilidad_transferencia 	:= OLD.centro_utilidad_transferencia; 	
	old_bodega_destino_transferencia := OLD.bodega_destino_transferencia;
	
	usuario_registro_ := OLD.usuario_id;
	fecha_registro := NOW();
  END IF; 
  
  IF TG_OP = 'UPDATE' THEN
	new_bodegas_doc_id 	:= NEW.bodegas_doc_id; 	
	new_numeracion 	:= NEW.numeracion; 	
	new_fecha 	:= NEW.fecha; 	
	new_total_costo 	:= NEW.total_costo; 	
	new_transaccion 	:= NEW.transaccion; 	
	new_observacion 	:= NEW.observacion; 	
	new_usuario_id 	:= NEW.usuario_id; 	
	new_fecha_registro 	:= NEW.fecha_registro; 	
	new_centro_utilidad_transferencia 	:= NEW.centro_utilidad_transferencia; 	
	new_bodega_destino_transferencia := NEW.bodega_destino_transferencia;	
	
	usuario_registro_ := NEW.usuario_id;
	fecha_registro := NOW();
  
	old_bodegas_doc_id 	:= OLD.bodegas_doc_id; 	
	old_numeracion 	:= OLD.numeracion; 	
	old_fecha 	:= OLD.fecha; 	
	old_total_costo 	:= OLD.total_costo; 	
	old_transaccion 	:= OLD.transaccion; 	
	old_observacion 	:= OLD.observacion; 	
	old_usuario_id 	:= OLD.usuario_id; 	
	old_fecha_registro 	:= OLD.fecha_registro; 	
	old_centro_utilidad_transferencia 	:= OLD.centro_utilidad_transferencia; 	
	old_bodega_destino_transferencia := OLD.bodega_destino_transferencia;
  END IF; 
  
  IF TG_OP = 'INSERT' THEN
	new_bodegas_doc_id 	:= NEW.bodegas_doc_id; 	
	new_numeracion 	:= NEW.numeracion; 	
	new_fecha 	:= NEW.fecha; 	
	new_total_costo 	:= NEW.total_costo; 	
	new_transaccion 	:= NEW.transaccion; 	
	new_observacion 	:= NEW.observacion; 	
	new_usuario_id 	:= NEW.usuario_id; 	
	new_fecha_registro 	:= NEW.fecha_registro; 	
	new_centro_utilidad_transferencia 	:= NEW.centro_utilidad_transferencia; 	
	new_bodega_destino_transferencia := NEW.bodega_destino_transferencia;
	
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

     usuario_registro

    )
    VALUES
    (
      indicador.accion_id,
      indicador.novedad_indicador_id,
      'bodegas_documentos',
      
      'bodegas_doc_id',
      new_bodegas_doc_id,
      old_bodegas_doc_id,
      
      'numeracion',
      new_numeracion,
      old_numeracion,
      
      'fecha',
      new_fecha,
      old_fecha,
      
      'total_costo',
      new_total_costo,
      old_total_costo,
      
      'transaccion',
      new_transaccion,
      old_transaccion,
      
      'observacion',
      new_observacion,
      old_observacion,
      
      'usuario_id',
      new_usuario_id,
      old_usuario_id,
      
      'fecha_registro',
      new_fecha_registro,
      old_fecha_registro,
      
      'centro_utilidad_transferencia',
      new_centro_utilidad_transferencia,
      old_centro_utilidad_transferencia,
      
      'bodega_destino_transferencia',
      new_bodega_destino_transferencia,
      old_bodega_destino_transferencia,
      	  
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

ALTER FUNCTION public.au_bodegas_documentos()
  OWNER TO "admin";
  
  CREATE TRIGGER aut_auditoria_bodegas_documentos
  AFTER INSERT OR UPDATE OR DELETE
  ON public.bodegas_documentos
  FOR EACH ROW
  EXECUTE PROCEDURE au_bodegas_documentos();
