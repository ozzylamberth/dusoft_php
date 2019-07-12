CREATE OR REPLACE FUNCTION public.au_inv_notas_credito_proveedor()
RETURNS trigger AS
$$
DECLARE

  indicador RECORD;
  columnas RECORD;
  
	old_empresa_id 	character(3);
	old_prefijo 	character varying(4);
	old_numero 	integer;
	old_numero_factura 	character varying(40);
	old_codigo_proveedor_id 	integer;
	old_fecha_registro 	timestamp;
	old_usuario_id 	integer;
	old_valor_nota 	numeric(20,4);
	old_documento_id integer;
  
	new_empresa_id 	character(3);
	new_prefijo 	character varying(4);
	new_numero 	integer;
	new_numero_factura 	character varying(40);
	new_codigo_proveedor_id 	integer;
	new_fecha_registro 	timestamp;
	new_usuario_id 	integer;
	new_valor_nota 	numeric(20,4);
	new_documento_id integer;
	
	usuario_registro_ integer;
	fecha_registro timestamp;
  
BEGIN
  SELECT * INTO indicador
  FROM   acciones AC,
         novedad_x_acciones NA
  WHERE  AC.descripcion = TG_OP
  AND    NA.accion_id = AC.accion_id
  AND    NA.table_name = 'inv_notas_credito_proveedor';
 
 /* Inicializar Variables */
 
  IF TG_OP = 'DELETE' THEN
   
	old_empresa_id 	:= OLD.empresa_id; 	
	old_prefijo 	:= OLD.prefijo;
	old_numero 	:= OLD.numero;
	old_numero_factura 	:= OLD.numero_factura;
	old_codigo_proveedor_id 	:= OLD.codigo_proveedor_id;
	old_fecha_registro 	:= OLD.fecha_registro;
	old_usuario_id 	:= OLD.usuario_id;
	old_valor_nota 	:= OLD.valor_nota; 	
	old_documento_id := OLD.documento_id;
	
	usuario_registro_ := OLD.usuario_id;
	fecha_registro := NOW();
  END IF; 
  
  IF TG_OP = 'UPDATE' THEN
	new_empresa_id 	:= NEW.empresa_id; 	
	new_prefijo 	:= NEW.prefijo;
	new_numero 	:= NEW.numero;
	new_numero_factura 	:= NEW.numero_factura;
	new_codigo_proveedor_id 	:= NEW.codigo_proveedor_id;
	new_fecha_registro 	:= NEW.fecha_registro;
	new_usuario_id 	:= NEW.usuario_id;
	new_valor_nota 	:= NEW.valor_nota; 	
	new_documento_id := NEW.documento_id;
	
	usuario_registro_ := NEW.usuario_id;
	fecha_registro := NOW();
  
	old_empresa_id 	:= OLD.empresa_id; 	
	old_prefijo 	:= OLD.prefijo;
	old_numero 	:= OLD.numero;
	old_numero_factura 	:= OLD.numero_factura;
	old_codigo_proveedor_id 	:= OLD.codigo_proveedor_id;
	old_fecha_registro 	:= OLD.fecha_registro;
	old_usuario_id 	:= OLD.usuario_id;
	old_valor_nota 	:= OLD.valor_nota; 	
	old_documento_id := OLD.documento_id;
  END IF; 
  
  IF TG_OP = 'INSERT' THEN
	new_empresa_id 	:= NEW.empresa_id; 	
	new_prefijo 	:= NEW.prefijo;
	new_numero 	:= NEW.numero;
	new_numero_factura 	:= NEW.numero_factura;
	new_codigo_proveedor_id 	:= NEW.codigo_proveedor_id;
	new_fecha_registro 	:= NEW.fecha_registro;
	new_usuario_id 	:= NEW.usuario_id;
	new_valor_nota 	:= NEW.valor_nota; 	
	new_documento_id := NEW.documento_id;
	
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

   

     usuario_registro

    )
    VALUES
    (
      indicador.accion_id,
      indicador.novedad_indicador_id,
      'inv_notas_credito_proveedor',
      
      'empresa_id',
      new_empresa_id,
      old_empresa_id,
      
      'prefijo',
      new_prefijo,
      old_prefijo,
      
      'numero',
      new_numero,
      old_numero,
      
      'numero_factura',
      new_numero_factura,
      old_numero_factura,
      
      'codigo_proveedor_id',
      new_codigo_proveedor_id,
      old_codigo_proveedor_id,
      
      'fecha_registro',
      new_fecha_registro,
      old_fecha_registro,
      
      'usuario_id',
      new_usuario_id,
      old_usuario_id,
      
      'valor_nota',
      new_valor_nota,
      old_valor_nota,
      
      'documento_id',
      new_documento_id,
      old_documento_id,
      
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

ALTER FUNCTION public.au_inv_notas_credito_proveedor()
  OWNER TO "admin";
  
  CREATE TRIGGER aut_auditoria_inv_notas_credito_proveedor
  AFTER INSERT OR UPDATE OR DELETE
  ON public.inv_notas_credito_proveedor
  FOR EACH ROW
  EXECUTE PROCEDURE au_inv_notas_credito_proveedor();

  
  /*
  *	AUDITORIA DETALLE NOTA CREDITO PROVEEDOR
  */
  CREATE OR REPLACE FUNCTION public.au_inv_notas_credito_proveedor_d()
RETURNS trigger AS
$$
DECLARE

  indicador RECORD;
  columnas RECORD;
  
	old_empresa_id 	character(2);
	old_prefijo 	character varying(4);
	old_numero 	integer;
	old_item_id 	integer;
	old_codigo_producto 	character varying(50);
	old_concepto 	character(2);
	old_valor_concepto 	numeric(20,4);
	old_concepto_especifico 	character(3);
	old_cantidad 	integer;
	old_valor_unitario 	numeric(20,4);
	old_observacion 	text;
	old_porc_iva 	numeric(9,2);
	old_sube_baja_costo character(1);
  
	new_empresa_id 	character(2);
	new_prefijo 	character varying(4);
	new_numero 	integer;
	new_item_id 	integer;
	new_codigo_producto 	character varying(50);
	new_concepto 	character(2);
	new_valor_concepto 	numeric(20,4);
	new_concepto_especifico 	character(3);
	new_cantidad 	integer;
	new_valor_unitario 	numeric(20,4);
	new_observacion 	text;
	new_porc_iva 	numeric(9,2);
	new_sube_baja_costo character(1);
	
	usuario_registro_ integer;
	fecha_registro timestamp;
  
BEGIN
  SELECT * INTO indicador
  FROM   acciones AC,
         novedad_x_acciones NA
  WHERE  AC.descripcion = TG_OP
  AND    NA.accion_id = AC.accion_id
  AND    NA.table_name = 'inv_notas_credito_proveedor_d';
 
 /* Inicializar Variables */
 
  IF TG_OP = 'DELETE' THEN
   	
	old_empresa_id 	:= OLD.empresa_id;
	old_prefijo 	:= OLD.prefijo;
	old_numero 	:= OLD.numero; 	
	old_item_id 	:= OLD.item_id;	
	old_codigo_producto 	:= OLD.codigo_producto;
	old_concepto 	:= OLD.concepto;
	old_valor_concepto 	:= OLD.valor_concepto;
	old_concepto_especifico 	:= OLD.concepto_especifico;
	old_cantidad 	:= OLD.cantidad;
	old_valor_unitario 	:= OLD.valor_unitario;
	old_observacion 	:= OLD.observacion;
	old_porc_iva 	:= OLD.porc_iva;
	old_sube_baja_costo := OLD.sube_baja_costo;
	
	usuario_registro_ := 2;
	fecha_registro := NOW();
  END IF; 
  
  IF TG_OP = 'UPDATE' THEN
	new_empresa_id 	:= NEW.empresa_id;
	new_prefijo 	:= NEW.prefijo;
	new_numero 	:= NEW.numero; 	
	new_item_id 	:= NEW.item_id;	
	new_codigo_producto 	:= NEW.codigo_producto;
	new_concepto 	:= NEW.concepto;
	new_valor_concepto 	:= NEW.valor_concepto;
	new_concepto_especifico 	:= NEW.concepto_especifico;
	new_cantidad 	:= NEW.cantidad;
	new_valor_unitario 	:= NEW.valor_unitario;
	new_observacion 	:= NEW.observacion;
	new_porc_iva 	:= NEW.porc_iva;
	new_sube_baja_costo := NEW.sube_baja_costo;
	
	usuario_registro_ := 2;
	fecha_registro := NOW();
  
	old_empresa_id 	:= OLD.empresa_id;
	old_prefijo 	:= OLD.prefijo;
	old_numero 	:= OLD.numero; 	
	old_item_id 	:= OLD.item_id;	
	old_codigo_producto 	:= OLD.codigo_producto;
	old_concepto 	:= OLD.concepto;
	old_valor_concepto 	:= OLD.valor_concepto;
	old_concepto_especifico 	:= OLD.concepto_especifico;
	old_cantidad 	:= OLD.cantidad;
	old_valor_unitario 	:= OLD.valor_unitario;
	old_observacion 	:= OLD.observacion;
	old_porc_iva 	:= OLD.porc_iva;
	old_sube_baja_costo := OLD.sube_baja_costo;
  END IF; 
  
  IF TG_OP = 'INSERT' THEN
	new_empresa_id 	:= NEW.empresa_id;
	new_prefijo 	:= NEW.prefijo;
	new_numero 	:= NEW.numero; 	
	new_item_id 	:= NEW.item_id;	
	new_codigo_producto 	:= NEW.codigo_producto;
	new_concepto 	:= NEW.concepto;
	new_valor_concepto 	:= NEW.valor_concepto;
	new_concepto_especifico 	:= NEW.concepto_especifico;
	new_cantidad 	:= NEW.cantidad;
	new_valor_unitario 	:= NEW.valor_unitario;
	new_observacion 	:= NEW.observacion;
	new_porc_iva 	:= NEW.porc_iva;
	new_sube_baja_costo := NEW.sube_baja_costo;
	
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
      
      campo_13, 
      nuevo_valor_13,
      antiguo_valor_13,
       
     usuario_registro

    )
    VALUES
    (
      indicador.accion_id,
      indicador.novedad_indicador_id,
      'inv_notas_credito_proveedor_d',
      
      'empresa_id',
      new_empresa_id,
      old_empresa_id,
      
      'prefijo',    
      new_prefijo,
      old_prefijo,
      
      'numero',
      new_numero,
      old_numero,
      
      'item_id',
      new_item_id,
      old_item_id,
      
      'codigo_producto',
      new_codigo_producto,
      old_codigo_producto,
      
      'concepto',
      new_concepto,
      old_concepto,
      
      'valor_concepto',
      new_valor_concepto,
      old_valor_concepto,
      
      'concepto_especifico',
      new_concepto_especifico,
      old_concepto_especifico,
      
      'cantidad',
      new_cantidad,
      old_cantidad,
      
      'valor_unitario',
      new_valor_unitario,
      old_valor_unitario,
      
      'observacion',
      new_observacion,
      old_observacion,
      
      'porc_iva',
      new_porc_iva,
      old_porc_iva,
      
      'sube_baja_costo',
      new_sube_baja_costo,
      old_sube_baja_costo,

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

ALTER FUNCTION public.au_inv_notas_credito_proveedor_d()
  OWNER TO "admin";
  
  CREATE TRIGGER aut_auditoria_inv_notas_credito_proveedor_d
  AFTER INSERT OR UPDATE OR DELETE
  ON public.inv_notas_credito_proveedor_d
  FOR EACH ROW
  EXECUTE PROCEDURE au_inv_notas_credito_proveedor_d();

  /*AUDITORIA NOTAS DEBITO*/
  CREATE OR REPLACE FUNCTION public.au_inv_notas_debito_proveedor()
RETURNS trigger AS
$$
DECLARE

  indicador RECORD;
  columnas RECORD;
  
	old_empresa_id 	character(3);
	old_prefijo 	character varying(4);
	old_numero 	integer;
	old_numero_factura 	character varying(40);
	old_codigo_proveedor_id 	integer;
	old_fecha_registro 	timestamp;
	old_usuario_id 	integer;
	old_valor_nota 	numeric(20,4);
	old_documento_id integer;
  
	new_empresa_id 	character(3);
	new_prefijo 	character varying(4);
	new_numero 	integer;
	new_numero_factura 	character varying(40);
	new_codigo_proveedor_id 	integer;
	new_fecha_registro 	timestamp;
	new_usuario_id 	integer;
	new_valor_nota 	numeric(20,4);
	new_documento_id integer;
	
	usuario_registro_ integer;
	fecha_registro timestamp;
  
BEGIN
  SELECT * INTO indicador
  FROM   acciones AC,
         novedad_x_acciones NA
  WHERE  AC.descripcion = TG_OP
  AND    NA.accion_id = AC.accion_id
  AND    NA.table_name = 'inv_notas_debito_proveedor';
 
 /* Inicializar Variables */
 
  IF TG_OP = 'DELETE' THEN
   
	old_empresa_id 	:= OLD.empresa_id; 	
	old_prefijo 	:= OLD.prefijo;
	old_numero 	:= OLD.numero;
	old_numero_factura 	:= OLD.numero_factura;
	old_codigo_proveedor_id 	:= OLD.codigo_proveedor_id;
	old_fecha_registro 	:= OLD.fecha_registro;
	old_usuario_id 	:= OLD.usuario_id;
	old_valor_nota 	:= OLD.valor_nota; 	
	old_documento_id := OLD.documento_id;
	
	usuario_registro_ := OLD.usuario_id;
	fecha_registro := NOW();
  END IF; 
  
  IF TG_OP = 'UPDATE' THEN
	new_empresa_id 	:= NEW.empresa_id; 	
	new_prefijo 	:= NEW.prefijo;
	new_numero 	:= NEW.numero;
	new_numero_factura 	:= NEW.numero_factura;
	new_codigo_proveedor_id 	:= NEW.codigo_proveedor_id;
	new_fecha_registro 	:= NEW.fecha_registro;
	new_usuario_id 	:= NEW.usuario_id;
	new_valor_nota 	:= NEW.valor_nota; 	
	new_documento_id := NEW.documento_id;
	
	usuario_registro_ := NEW.usuario_id;
	fecha_registro := NOW();
  
	old_empresa_id 	:= OLD.empresa_id; 	
	old_prefijo 	:= OLD.prefijo;
	old_numero 	:= OLD.numero;
	old_numero_factura 	:= OLD.numero_factura;
	old_codigo_proveedor_id 	:= OLD.codigo_proveedor_id;
	old_fecha_registro 	:= OLD.fecha_registro;
	old_usuario_id 	:= OLD.usuario_id;
	old_valor_nota 	:= OLD.valor_nota; 	
	old_documento_id := OLD.documento_id;
  END IF; 
  
  IF TG_OP = 'INSERT' THEN
	new_empresa_id 	:= NEW.empresa_id; 	
	new_prefijo 	:= NEW.prefijo;
	new_numero 	:= NEW.numero;
	new_numero_factura 	:= NEW.numero_factura;
	new_codigo_proveedor_id 	:= NEW.codigo_proveedor_id;
	new_fecha_registro 	:= NEW.fecha_registro;
	new_usuario_id 	:= NEW.usuario_id;
	new_valor_nota 	:= NEW.valor_nota; 	
	new_documento_id := NEW.documento_id;
	
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

   

     usuario_registro

    )
    VALUES
    (
      indicador.accion_id,
      indicador.novedad_indicador_id,
      'inv_notas_debito_proveedor',
      
      'empresa_id',
      new_empresa_id,
      old_empresa_id,
      
      'prefijo',
      new_prefijo,
      old_prefijo,
      
      'numero',
      new_numero,
      old_numero,
      
      'numero_factura',
      new_numero_factura,
      old_numero_factura,
      
      'codigo_proveedor_id',
      new_codigo_proveedor_id,
      old_codigo_proveedor_id,
      
      'fecha_registro',
      new_fecha_registro,
      old_fecha_registro,
      
      'usuario_id',
      new_usuario_id,
      old_usuario_id,
      
      'valor_nota',
      new_valor_nota,
      old_valor_nota,
      
      'documento_id',
      new_documento_id,
      old_documento_id,
      
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

ALTER FUNCTION public.au_inv_notas_debito_proveedor()
  OWNER TO "admin";
  
  CREATE TRIGGER aut_auditoria_inv_notas_debito_proveedor
  AFTER INSERT OR UPDATE OR DELETE
  ON public.inv_notas_debito_proveedor
  FOR EACH ROW
  EXECUTE PROCEDURE au_inv_notas_debito_proveedor();

  
  /*
  *	AUDITORIA DETALLE NOTA CREDITO PROVEEDOR
  */
  CREATE OR REPLACE FUNCTION public.au_inv_notas_debito_proveedor_d()
RETURNS trigger AS
$$
DECLARE

  indicador RECORD;
  columnas RECORD;
  
	old_empresa_id 	character(2);
	old_prefijo 	character varying(4);
	old_numero 	integer;
	old_item_id 	integer;
	old_codigo_producto 	character varying(50);
	old_concepto 	character(2);
	old_valor_concepto 	numeric(20,4);
	old_concepto_especifico 	character(3);
	old_cantidad 	integer;
	old_valor_unitario 	numeric(20,4);
	old_observacion 	text;
	old_porc_iva 	numeric(9,2);
	old_sube_baja_costo character(1);
  
	new_empresa_id 	character(2);
	new_prefijo 	character varying(4);
	new_numero 	integer;
	new_item_id 	integer;
	new_codigo_producto 	character varying(50);
	new_concepto 	character(2);
	new_valor_concepto 	numeric(20,4);
	new_concepto_especifico 	character(3);
	new_cantidad 	integer;
	new_valor_unitario 	numeric(20,4);
	new_observacion 	text;
	new_porc_iva 	numeric(9,2);
	new_sube_baja_costo character(1);
	
	usuario_registro_ integer;
	fecha_registro timestamp;
  
BEGIN
  SELECT * INTO indicador
  FROM   acciones AC,
         novedad_x_acciones NA
  WHERE  AC.descripcion = TG_OP
  AND    NA.accion_id = AC.accion_id
  AND    NA.table_name = 'inv_notas_debito_proveedor_d';
 
 /* Inicializar Variables */
 
  IF TG_OP = 'DELETE' THEN
   	
	old_empresa_id 	:= OLD.empresa_id;
	old_prefijo 	:= OLD.prefijo;
	old_numero 	:= OLD.numero; 	
	old_item_id 	:= OLD.item_id;	
	old_codigo_producto 	:= OLD.codigo_producto;
	old_concepto 	:= OLD.concepto;
	old_valor_concepto 	:= OLD.valor_concepto;
	old_concepto_especifico 	:= OLD.concepto_especifico;
	old_cantidad 	:= OLD.cantidad;
	old_valor_unitario 	:= OLD.valor_unitario;
	old_observacion 	:= OLD.observacion;
	old_porc_iva 	:= OLD.porc_iva;
	old_sube_baja_costo := OLD.sube_baja_costo;
	
	usuario_registro_ := 2;
	fecha_registro := NOW();
  END IF; 
  
  IF TG_OP = 'UPDATE' THEN
	new_empresa_id 	:= NEW.empresa_id;
	new_prefijo 	:= NEW.prefijo;
	new_numero 	:= NEW.numero; 	
	new_item_id 	:= NEW.item_id;	
	new_codigo_producto 	:= NEW.codigo_producto;
	new_concepto 	:= NEW.concepto;
	new_valor_concepto 	:= NEW.valor_concepto;
	new_concepto_especifico 	:= NEW.concepto_especifico;
	new_cantidad 	:= NEW.cantidad;
	new_valor_unitario 	:= NEW.valor_unitario;
	new_observacion 	:= NEW.observacion;
	new_porc_iva 	:= NEW.porc_iva;
	new_sube_baja_costo := NEW.sube_baja_costo;
	
	usuario_registro_ := 2;
	fecha_registro := NOW();
  
	old_empresa_id 	:= OLD.empresa_id;
	old_prefijo 	:= OLD.prefijo;
	old_numero 	:= OLD.numero; 	
	old_item_id 	:= OLD.item_id;	
	old_codigo_producto 	:= OLD.codigo_producto;
	old_concepto 	:= OLD.concepto;
	old_valor_concepto 	:= OLD.valor_concepto;
	old_concepto_especifico 	:= OLD.concepto_especifico;
	old_cantidad 	:= OLD.cantidad;
	old_valor_unitario 	:= OLD.valor_unitario;
	old_observacion 	:= OLD.observacion;
	old_porc_iva 	:= OLD.porc_iva;
	old_sube_baja_costo := OLD.sube_baja_costo;
  END IF; 
  
  IF TG_OP = 'INSERT' THEN
	new_empresa_id 	:= NEW.empresa_id;
	new_prefijo 	:= NEW.prefijo;
	new_numero 	:= NEW.numero; 	
	new_item_id 	:= NEW.item_id;	
	new_codigo_producto 	:= NEW.codigo_producto;
	new_concepto 	:= NEW.concepto;
	new_valor_concepto 	:= NEW.valor_concepto;
	new_concepto_especifico 	:= NEW.concepto_especifico;
	new_cantidad 	:= NEW.cantidad;
	new_valor_unitario 	:= NEW.valor_unitario;
	new_observacion 	:= NEW.observacion;
	new_porc_iva 	:= NEW.porc_iva;
	new_sube_baja_costo := NEW.sube_baja_costo;
	
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
      
      campo_13, 
      nuevo_valor_13,
      antiguo_valor_13,
       
     usuario_registro

    )
    VALUES
    (
      indicador.accion_id,
      indicador.novedad_indicador_id,
      'inv_notas_debito_proveedor_d',
      
      'empresa_id',
      new_empresa_id,
      old_empresa_id,
      
      'prefijo',    
      new_prefijo,
      old_prefijo,
      
      'numero',
      new_numero,
      old_numero,
      
      'item_id',
      new_item_id,
      old_item_id,
      
      'codigo_producto',
      new_codigo_producto,
      old_codigo_producto,
      
      'concepto',
      new_concepto,
      old_concepto,
      
      'valor_concepto',
      new_valor_concepto,
      old_valor_concepto,
      
      'concepto_especifico',
      new_concepto_especifico,
      old_concepto_especifico,
      
      'cantidad',
      new_cantidad,
      old_cantidad,
      
      'valor_unitario',
      new_valor_unitario,
      old_valor_unitario,
      
      'observacion',
      new_observacion,
      old_observacion,
      
      'porc_iva',
      new_porc_iva,
      old_porc_iva,
      
      'sube_baja_costo',
      new_sube_baja_costo,
      old_sube_baja_costo,

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

ALTER FUNCTION public.au_inv_notas_debito_proveedor_d()
  OWNER TO "admin";
  
  CREATE TRIGGER aut_auditoria_inv_notas_debito_proveedor_d
  AFTER INSERT OR UPDATE OR DELETE
  ON public.inv_notas_debito_proveedor_d
  FOR EACH ROW
  EXECUTE PROCEDURE au_inv_notas_debito_proveedor_d();

  /*
  * AUDITORIA CONTRATO PROVEEDOR
  */
    CREATE OR REPLACE FUNCTION public.au_contratacion_produc_proveedor()
RETURNS trigger AS
$$
DECLARE

  indicador RECORD;
  columnas RECORD;
  
	old_contratacion_prod_id 	integer;
	old_empresa_id 	character(2);
	old_no_contrato 	character varying(32);
	old_descripcion 	character varying(250);
	old_fecha_inicio 	date;
	old_fecha_vencimiento 	date;
	old_tipo_id_tercero 	character varying(3);
	old_tercero_id 	character varying(32);
	old_condiciones_entrega 	character varying(100);
	old_usuario_id 	integer;
	old_fecha_registro 	timestamp;
	old_observaciones 	text;
	old_estado 	character(1);
	old_codigo_proveedor_id 	integer;
	old_porcentaje_genericos 	numeric(9,2);
	old_sw_cliente 	character(1);
	old_porcentaje_marcas numeric(9,2);
	
	
	new_contratacion_prod_id 	integer;
	new_empresa_id 	character(2);
	new_no_contrato 	character varying(32);
	new_descripcion 	character varying(250);
	new_fecha_inicio 	date;
	new_fecha_vencimiento 	date;
	new_tipo_id_tercero 	character varying(3);
	new_tercero_id 	character varying(32);
	new_condiciones_entrega 	character varying(100);
	new_usuario_id 	integer;
	new_fecha_registro 	timestamp;
	new_observaciones 	text;
	new_estado 	character(1);
	new_codigo_proveedor_id 	integer;
	new_porcentaje_genericos 	numeric(9,2);
	new_sw_cliente 	character(1);
	new_porcentaje_marcas numeric(9,2);
	
	usuario_registro_ integer;
	fecha_registro timestamp;
  
BEGIN
  SELECT * INTO indicador
  FROM   acciones AC,
         novedad_x_acciones NA
  WHERE  AC.descripcion = TG_OP
  AND    NA.accion_id = AC.accion_id
  AND    NA.table_name = 'contratacion_produc_proveedor';
 
 /* Inicializar Variables */
 
  IF TG_OP = 'DELETE' THEN
   
	old_contratacion_prod_id 	:= OLD.contratacion_prod_id;
	old_empresa_id 	:= OLD.empresa_id;
	old_no_contrato 	:= OLD.no_contrato;	
	old_descripcion 	:= OLD.descripcion;
	old_fecha_inicio 	:= OLD.fecha_inicio;
	old_fecha_vencimiento 	:= OLD.fecha_vencimiento;
	old_tipo_id_tercero 	:= OLD.tipo_id_tercero;
	old_tercero_id 	:= OLD.tercero_id;
	old_condiciones_entrega 	:= OLD.condiciones_entrega;
	old_usuario_id 	:= OLD.usuario_id;
	old_fecha_registro 	:= OLD.fecha_registro;
	old_observaciones 	:= OLD.observaciones;
	old_estado 	:= OLD.estado;
	old_codigo_proveedor_id 	:= OLD.codigo_proveedor_id;
	old_porcentaje_genericos 	:= OLD.porcentaje_genericos; 	
	old_sw_cliente 	:= OLD.sw_cliente;
	old_porcentaje_marcas := OLD.porcentaje_marcas;
	
	usuario_registro_ := OLD.usuario_id;
	fecha_registro := NOW();
  END IF; 
  
  IF TG_OP = 'UPDATE' THEN
	new_contratacion_prod_id 	:= NEW.contratacion_prod_id;
	new_empresa_id 	:= NEW.empresa_id;
	new_no_contrato 	:= NEW.no_contrato;	
	new_descripcion 	:= NEW.descripcion;
	new_fecha_inicio 	:= NEW.fecha_inicio;
	new_fecha_vencimiento 	:= NEW.fecha_vencimiento;
	new_tipo_id_tercero 	:= NEW.tipo_id_tercero;
	new_tercero_id 	:= NEW.tercero_id;
	new_condiciones_entrega 	:= NEW.condiciones_entrega;
	new_usuario_id 	:= NEW.usuario_id;
	new_fecha_registro 	:= NEW.fecha_registro;
	new_observaciones 	:= NEW.observaciones;
	new_estado 	:= NEW.estado;
	new_codigo_proveedor_id 	:= NEW.codigo_proveedor_id;
	new_porcentaje_genericos 	:= NEW.porcentaje_genericos; 	
	new_sw_cliente 	:= NEW.sw_cliente;
	new_porcentaje_marcas := NEW.porcentaje_marcas;
	
	usuario_registro_ := NEW.usuario_id;
	fecha_registro := NOW();
  
	old_contratacion_prod_id 	:= OLD.contratacion_prod_id;
	old_empresa_id 	:= OLD.empresa_id;
	old_no_contrato 	:= OLD.no_contrato;	
	old_descripcion 	:= OLD.descripcion;
	old_fecha_inicio 	:= OLD.fecha_inicio;
	old_fecha_vencimiento 	:= OLD.fecha_vencimiento;
	old_tipo_id_tercero 	:= OLD.tipo_id_tercero;
	old_tercero_id 	:= OLD.tercero_id;
	old_condiciones_entrega 	:= OLD.condiciones_entrega;
	old_usuario_id 	:= OLD.usuario_id;
	old_fecha_registro 	:= OLD.fecha_registro;
	old_observaciones 	:= OLD.observaciones;
	old_estado 	:= OLD.estado;
	old_codigo_proveedor_id 	:= OLD.codigo_proveedor_id;
	old_porcentaje_genericos 	:= OLD.porcentaje_genericos; 	
	old_sw_cliente 	:= OLD.sw_cliente;
	old_porcentaje_marcas := OLD.porcentaje_marcas;
  END IF; 
  
  IF TG_OP = 'INSERT' THEN
	new_contratacion_prod_id 	:= NEW.contratacion_prod_id;
	new_empresa_id 	:= NEW.empresa_id;
	new_no_contrato 	:= NEW.no_contrato;	
	new_descripcion 	:= NEW.descripcion;
	new_fecha_inicio 	:= NEW.fecha_inicio;
	new_fecha_vencimiento 	:= NEW.fecha_vencimiento;
	new_tipo_id_tercero 	:= NEW.tipo_id_tercero;
	new_tercero_id 	:= NEW.tercero_id;
	new_condiciones_entrega 	:= NEW.condiciones_entrega;
	new_usuario_id 	:= NEW.usuario_id;
	new_fecha_registro 	:= NEW.fecha_registro;
	new_observaciones 	:= NEW.observaciones;
	new_estado 	:= NEW.estado;
	new_codigo_proveedor_id 	:= NEW.codigo_proveedor_id;
	new_porcentaje_genericos 	:= NEW.porcentaje_genericos; 	
	new_sw_cliente 	:= NEW.sw_cliente;
	new_porcentaje_marcas := NEW.porcentaje_marcas;
	
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

	  usuario_registro

    )
    VALUES
    (
      indicador.accion_id,
      indicador.novedad_indicador_id,
      'contratacion_produc_proveedor',
      
      'contratacion_prod_id',
      new_contratacion_prod_id,
      old_contratacion_prod_id,
	  
      'empresa_id',
      new_empresa_id,
      old_empresa_id,
      
      'no_contrato',
      new_no_contrato,
      old_no_contrato,
            
      'descripcion',
      new_descripcion,
      old_descripcion,
      
      'fecha_inicio',
      new_fecha_inicio,
      old_fecha_inicio,
      
      'fecha_vencimiento',
      new_fecha_vencimiento,
      old_fecha_vencimiento,
      
      'tipo_id_tercero',
      new_tipo_id_tercero,
      old_tipo_id_tercero,
      
      'tercero_id',
      new_tercero_id,
      old_tercero_id,
      
      'condiciones_entrega',
      new_condiciones_entrega,
      old_condiciones_entrega,
      
      'usuario_id',
      new_usuario_id,
      old_usuario_id,
      
      'fecha_registro',
      new_fecha_registro,
      old_fecha_registro,
      
      'observaciones',
      new_observaciones,
      old_observaciones,
      
      'estado',
      new_estado,
      old_estado,
      
      'codigo_proveedor_id',
      new_codigo_proveedor_id,
      old_codigo_proveedor_id,
      
      'porcentaje_genericos',
      new_porcentaje_genericos,
      old_porcentaje_genericos,
      
      'sw_cliente',
      new_sw_cliente,
      old_sw_cliente,
      
      'porcentaje_marcas',
      new_porcentaje_marcas,
      old_porcentaje_marcas,
      
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

ALTER FUNCTION public.au_contratacion_produc_proveedor()
  OWNER TO "admin";
  
  CREATE TRIGGER aut_auditoria_contratacion_produc_proveedor
  AFTER INSERT OR UPDATE OR DELETE
  ON public.contratacion_produc_proveedor
  FOR EACH ROW
  EXECUTE PROCEDURE au_contratacion_produc_proveedor();

  
  /*
  * DETALLE CONTRATACION PROVEEDOR
  */
     CREATE OR REPLACE FUNCTION public.au_contratacion_produc_prov_detalle()
RETURNS trigger AS
$$
DECLARE

  indicador RECORD;
  columnas RECORD;
  
	old_contrato_produc_prov_det_id 	integer;
	old_empresa_id 	character(2);
	old_contratacion_prod_id 	integer;
	old_codigo_producto 	character varying(30);
	old_precio 	numeric(15,2);
	old_valor_pactado 	numeric(15,2);
	old_valor_porcentaje 	numeric(9,2);
	old_valor_total_pactado 	numeric(16,2);
	old_usuario_id 	integer;
	old_fecha_registro timestamp;
	
	new_contrato_produc_prov_det_id 	integer;
	new_empresa_id 	character(2);
	new_contratacion_prod_id 	integer;
	new_codigo_producto 	character varying(30);
	new_precio 	numeric(15,2);
	new_valor_pactado 	numeric(15,2);
	new_valor_porcentaje 	numeric(9,2);
	new_valor_total_pactado 	numeric(16,2);
	new_usuario_id 	integer;
	new_fecha_registro timestamp;
	
	usuario_registro_ integer;
	fecha_registro timestamp;
  
BEGIN
  SELECT * INTO indicador
  FROM   acciones AC,
         novedad_x_acciones NA
  WHERE  AC.descripcion = TG_OP
  AND    NA.accion_id = AC.accion_id
  AND    NA.table_name = 'contratacion_produc_prov_detalle';
 
 /* Inicializar Variables */
 
  IF TG_OP = 'DELETE' THEN
   
	old_contrato_produc_prov_det_id 	:= OLD.contrato_produc_prov_det_id;
	old_empresa_id 	:= OLD.empresa_id;
	old_contratacion_prod_id 	:= OLD.contratacion_prod_id;
	old_codigo_producto 	:= OLD.codigo_producto;
	old_precio 	:= OLD.precio;
	old_valor_pactado 	:= OLD.valor_pactado;
	old_valor_porcentaje 	:= OLD.valor_porcentaje;
	old_valor_total_pactado 	:= OLD.valor_total_pactado;
	old_usuario_id 	:= OLD.usuario_id;
	old_fecha_registro := OLD.fecha_registro;
	
	usuario_registro_ := OLD.usuario_id;
	fecha_registro := NOW();
  END IF; 
  
  IF TG_OP = 'UPDATE' THEN
	new_contrato_produc_prov_det_id 	:= NEW.contrato_produc_prov_det_id;
	new_empresa_id 	:= NEW.empresa_id;
	new_contratacion_prod_id 	:= NEW.contratacion_prod_id;
	new_codigo_producto 	:= NEW.codigo_producto;
	new_precio 	:= NEW.precio;
	new_valor_pactado 	:= NEW.valor_pactado;
	new_valor_porcentaje 	:= NEW.valor_porcentaje;
	new_valor_total_pactado 	:= NEW.valor_total_pactado;
	new_usuario_id 	:= NEW.usuario_id;
	new_fecha_registro := NEW.fecha_registro;
	
	usuario_registro_ := NEW.usuario_id;
	fecha_registro := NOW();
  
	old_contrato_produc_prov_det_id 	:= OLD.contrato_produc_prov_det_id;
	old_empresa_id 	:= OLD.empresa_id;
	old_contratacion_prod_id 	:= OLD.contratacion_prod_id;
	old_codigo_producto 	:= OLD.codigo_producto;
	old_precio 	:= OLD.precio;
	old_valor_pactado 	:= OLD.valor_pactado;
	old_valor_porcentaje 	:= OLD.valor_porcentaje;
	old_valor_total_pactado 	:= OLD.valor_total_pactado;
	old_usuario_id 	:= OLD.usuario_id;
	old_fecha_registro := OLD.fecha_registro;
  END IF; 
  
  IF TG_OP = 'INSERT' THEN
	new_contrato_produc_prov_det_id 	:= NEW.contrato_produc_prov_det_id;
	new_empresa_id 	:= NEW.empresa_id;
	new_contratacion_prod_id 	:= NEW.contratacion_prod_id;
	new_codigo_producto 	:= NEW.codigo_producto;
	new_precio 	:= NEW.precio;
	new_valor_pactado 	:= NEW.valor_pactado;
	new_valor_porcentaje 	:= NEW.valor_porcentaje;
	new_valor_total_pactado 	:= NEW.valor_total_pactado;
	new_usuario_id 	:= NEW.usuario_id;
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
 
	  usuario_registro

    )
    VALUES
    (
      indicador.accion_id,
      indicador.novedad_indicador_id,
      'contratacion_produc_prov_detalle',
      
      'contrato_produc_prov_det_id',
      new_contrato_produc_prov_det_id,
      old_contrato_produc_prov_det_id,
	  
      'empresa_id',
      new_empresa_id,
      old_empresa_id,
      
      'contratacion_prod_id',
      new_contratacion_prod_id,
      old_contratacion_prod_id,
            
      'codigo_producto',
      new_codigo_producto,
      old_codigo_producto,
      
      'precio',
      new_precio,
      old_precio,
      
      'valor_pactado',
      new_valor_pactado,
      old_valor_pactado,
      
      'valor_porcentaje',
      new_valor_porcentaje,
      old_valor_porcentaje,
      
      'valor_total_pactado',
      new_valor_total_pactado,
      old_valor_total_pactado,
      
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

ALTER FUNCTION public.au_contratacion_produc_prov_detalle()
  OWNER TO "admin";
  
 
CREATE TRIGGER aut_auditoria_contratacion_produc_prov_detalle
  AFTER INSERT OR UPDATE OR DELETE
  ON public.contratacion_produc_prov_detalle
  FOR EACH ROW
  EXECUTE PROCEDURE au_contratacion_produc_prov_detalle();

  /*
* CONTRATO PROVEEDOR POLITICAS
*/
  
       CREATE OR REPLACE FUNCTION public.au_contratacion_produc_proveedor_politicas()
RETURNS trigger AS
$$
DECLARE

  indicador RECORD;
  columnas RECORD;
  
	old_politica_producto_id 	integer;
	old_contrato_produc_prov_det_id 	integer;
	old_politica 	text;
	old_usuario_id 	integer;
	old_fecha_registro timestamp;
	
	new_politica_producto_id 	integer;
	new_contrato_produc_prov_det_id 	integer;
	new_politica 	text;
	new_usuario_id 	integer;
	new_fecha_registro timestamp;
	
	usuario_registro_ integer;
	fecha_registro timestamp;
  
BEGIN
  SELECT * INTO indicador
  FROM   acciones AC,
         novedad_x_acciones NA
  WHERE  AC.descripcion = TG_OP
  AND    NA.accion_id = AC.accion_id
  AND    NA.table_name = 'contratacion_produc_proveedor_politicas';
 
 /* Inicializar Variables */
 
  IF TG_OP = 'DELETE' THEN
   
	old_politica_producto_id 	:= OLD.politica_producto_id;
	old_contrato_produc_prov_det_id 	:= OLD.contrato_produc_prov_det_id;
	old_politica 	:= OLD.politica;
	old_usuario_id 	:= OLD.usuario_id;
	old_fecha_registro := OLD.fecha_registro;
	
	usuario_registro_ := OLD.usuario_id;
	fecha_registro := NOW();
  END IF; 
  
  IF TG_OP = 'UPDATE' THEN
	new_politica_producto_id 	:= NEW.politica_producto_id;
	new_contrato_produc_prov_det_id 	:= NEW.contrato_produc_prov_det_id;
	new_politica 	:= NEW.politica;
	new_usuario_id 	:= NEW.usuario_id;
	new_fecha_registro := NEW.fecha_registro;
	
	usuario_registro_ := NEW.usuario_id;
	fecha_registro := NOW();
  
	old_politica_producto_id 	:= OLD.politica_producto_id;
	old_contrato_produc_prov_det_id 	:= OLD.contrato_produc_prov_det_id;
	old_politica 	:= OLD.politica;
	old_usuario_id 	:= OLD.usuario_id;
	old_fecha_registro := OLD.fecha_registro;
  END IF; 
  
  IF TG_OP = 'INSERT' THEN
	new_politica_producto_id 	:= NEW.politica_producto_id;
	new_contrato_produc_prov_det_id 	:= NEW.contrato_produc_prov_det_id;
	new_politica 	:= NEW.politica;
	new_usuario_id 	:= NEW.usuario_id;
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
      'contratacion_produc_proveedor_politicas',
      
      'politica_producto_id',
      new_politica_producto_id,
      old_politica_producto_id,
	  
      'contrato_produc_prov_det_id',
      new_contrato_produc_prov_det_id,
      old_contrato_produc_prov_det_id,
      
      'politica',
      new_politica,
      old_politica,
            
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

ALTER FUNCTION public.au_contratacion_produc_proveedor_politicas()
  OWNER TO "admin";
  
  CREATE TRIGGER aut_auditoria_contratacion_produc_proveedor_politicas
  AFTER INSERT OR UPDATE OR DELETE
  ON public.contratacion_produc_proveedor_politicas
  FOR EACH ROW
  EXECUTE PROCEDURE au_contratacion_produc_proveedor_politicas();

  
  /*AUDITORIA TABLA: INVENTARIOS*/
 CREATE OR REPLACE FUNCTION public.au_inventarios()
RETURNS trigger AS
$$
DECLARE

  indicador RECORD;
  columnas RECORD;
  
	old_empresa_id 	character(2);
	old_codigo_producto 	character varying(50);
	old_existencia_minima 	numeric(9,2);
	old_existencia_maxima 	numeric(9,2);
	old_existencia 	numeric(9,2);
	old_costo_anterior 	numeric(15,2);
	old_costo 	numeric(15,2);
	old_costo_penultima_compra 	numeric(15,2);
	old_costo_ultima_compra 	numeric(15,2);
	old_precio_venta_anterior 	numeric(15,2);
	old_precio_venta 	numeric(15,2);
	old_precio_minimo 	numeric(15,2);
	old_precio_maximo 	numeric(15,2);
	old_sw_vende 	character(1);
	old_usuario_id 	integer;
	old_estado 	character(1);
	old_fecha_registro 	timestamp;
	old_sw_servicio 	character(1);
	old_grupo_contratacion_id 	character varying(10);
	old_nivel_autorizacion_id 	character varying(2);
	old_cssp 	numeric(15,2);
	old_cantidad_max_formulacion integer;
	
	new_empresa_id 	character(2);
	new_codigo_producto 	character varying(50);
	new_existencia_minima 	numeric(9,2);
	new_existencia_maxima 	numeric(9,2);
	new_existencia 	numeric(9,2);
	new_costo_anterior 	numeric(15,2);
	new_costo 	numeric(15,2);
	new_costo_penultima_compra 	numeric(15,2);
	new_costo_ultima_compra 	numeric(15,2);
	new_precio_venta_anterior 	numeric(15,2);
	new_precio_venta 	numeric(15,2);
	new_precio_minimo 	numeric(15,2);
	new_precio_maximo 	numeric(15,2);
	new_sw_vende 	character(1);
	new_usuario_id 	integer;
	new_estado 	character(1);
	new_fecha_registro 	timestamp;
	new_sw_servicio 	character(1);
	new_grupo_contratacion_id 	character varying(10);
	new_nivel_autorizacion_id 	character varying(2);
	new_cssp 	numeric(15,2);
	new_cantidad_max_formulacion integer;
	
	usuario_registro_ integer;
	fecha_registro timestamp;
  
BEGIN
  SELECT * INTO indicador
  FROM   acciones AC,
         novedad_x_acciones NA
  WHERE  AC.descripcion = TG_OP
  AND    NA.accion_id = AC.accion_id
  AND    NA.table_name = 'inventarios';
 
 /* Inicializar Variables */
 
  IF TG_OP = 'DELETE' THEN
   
	old_empresa_id 	:= OLD.empresa_id;
	old_codigo_producto 	:= OLD.codigo_producto;
	old_existencia_minima 	:= OLD.existencia_minima;
	old_existencia_maxima 	:= OLD.existencia_maxima;	
	old_existencia 	:= OLD.existencia;
	old_costo_anterior 	:= OLD.costo_anterior;
	old_costo 	:= OLD.costo;
	old_costo_penultima_compra 	:= OLD.costo_penultima_compra;
	old_costo_ultima_compra 	:= OLD.costo_ultima_compra;
	old_precio_venta_anterior 	:= OLD.precio_venta_anterior;	
	old_precio_venta 	:= OLD.precio_venta;
	old_precio_minimo 	:= OLD.precio_minimo;
	old_precio_maximo 	:= OLD.precio_maximo;	
	old_sw_vende 	:= OLD.sw_vende;
	old_usuario_id 	:= OLD.usuario_id;	
	old_estado 	:= OLD.estado;
	old_fecha_registro 	:= OLD.fecha_registro;
	old_sw_servicio 	:= OLD.sw_servicio;
	old_grupo_contratacion_id 	:= OLD.grupo_contratacion_id;
	old_nivel_autorizacion_id 	:= OLD.nivel_autorizacion_id;
	old_cssp 	:= OLD.cssp;
	old_cantidad_max_formulacion := OLD.cantidad_max_formulacion;
	
	usuario_registro_ := OLD.usuario_id;
	fecha_registro := NOW();
  END IF; 
  
  IF TG_OP = 'UPDATE' THEN
	new_empresa_id 	:= NEW.empresa_id;
	new_codigo_producto 	:= NEW.codigo_producto;
	new_existencia_minima 	:= NEW.existencia_minima;
	new_existencia_maxima 	:= NEW.existencia_maxima;	
	new_existencia 	:= NEW.existencia;
	new_costo_anterior 	:= NEW.costo_anterior;
	new_costo 	:= NEW.costo;
	new_costo_penultima_compra 	:= NEW.costo_penultima_compra;
	new_costo_ultima_compra 	:= NEW.costo_ultima_compra;
	new_precio_venta_anterior 	:= NEW.precio_venta_anterior;	
	new_precio_venta 	:= NEW.precio_venta;
	new_precio_minimo 	:= NEW.precio_minimo;
	new_precio_maximo 	:= NEW.precio_maximo;	
	new_sw_vende 	:= NEW.sw_vende;
	new_usuario_id 	:= NEW.usuario_id;	
	new_estado 	:= NEW.estado;
	new_fecha_registro 	:= NEW.fecha_registro;
	new_sw_servicio 	:= NEW.sw_servicio;
	new_grupo_contratacion_id 	:= NEW.grupo_contratacion_id;
	new_nivel_autorizacion_id 	:= NEW.nivel_autorizacion_id;
	new_cssp 	:= NEW.cssp;
	new_cantidad_max_formulacion := NEW.cantidad_max_formulacion;
	
	usuario_registro_ := NEW.usuario_id;
	fecha_registro := NOW();
  
	old_empresa_id 	:= OLD.empresa_id;
	old_codigo_producto 	:= OLD.codigo_producto;
	old_existencia_minima 	:= OLD.existencia_minima;
	old_existencia_maxima 	:= OLD.existencia_maxima;	
	old_existencia 	:= OLD.existencia;
	old_costo_anterior 	:= OLD.costo_anterior;
	old_costo 	:= OLD.costo;
	old_costo_penultima_compra 	:= OLD.costo_penultima_compra;
	old_costo_ultima_compra 	:= OLD.costo_ultima_compra;
	old_precio_venta_anterior 	:= OLD.precio_venta_anterior;	
	old_precio_venta 	:= OLD.precio_venta;
	old_precio_minimo 	:= OLD.precio_minimo;
	old_precio_maximo 	:= OLD.precio_maximo;	
	old_sw_vende 	:= OLD.sw_vende;
	old_usuario_id 	:= OLD.usuario_id;	
	old_estado 	:= OLD.estado;
	old_fecha_registro 	:= OLD.fecha_registro;
	old_sw_servicio 	:= OLD.sw_servicio;
	old_grupo_contratacion_id 	:= OLD.grupo_contratacion_id;
	old_nivel_autorizacion_id 	:= OLD.nivel_autorizacion_id;
	old_cssp 	:= OLD.cssp;
	old_cantidad_max_formulacion := OLD.cantidad_max_formulacion;
  END IF; 
  
  IF TG_OP = 'INSERT' THEN
	new_empresa_id 	:= NEW.empresa_id;
	new_codigo_producto 	:= NEW.codigo_producto;
	new_existencia_minima 	:= NEW.existencia_minima;
	new_existencia_maxima 	:= NEW.existencia_maxima;	
	new_existencia 	:= NEW.existencia;
	new_costo_anterior 	:= NEW.costo_anterior;
	new_costo 	:= NEW.costo;
	new_costo_penultima_compra 	:= NEW.costo_penultima_compra;
	new_costo_ultima_compra 	:= NEW.costo_ultima_compra;
	new_precio_venta_anterior 	:= NEW.precio_venta_anterior;	
	new_precio_venta 	:= NEW.precio_venta;
	new_precio_minimo 	:= NEW.precio_minimo;
	new_precio_maximo 	:= NEW.precio_maximo;	
	new_sw_vende 	:= NEW.sw_vende;
	new_usuario_id 	:= NEW.usuario_id;	
	new_estado 	:= NEW.estado;
	new_fecha_registro 	:= NEW.fecha_registro;
	new_sw_servicio 	:= NEW.sw_servicio;
	new_grupo_contratacion_id 	:= NEW.grupo_contratacion_id;
	new_nivel_autorizacion_id 	:= NEW.nivel_autorizacion_id;
	new_cssp 	:= NEW.cssp;
	new_cantidad_max_formulacion := NEW.cantidad_max_formulacion;
	
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
      'inventarios',
      
      'empresa_id',
      new_empresa_id,
      old_empresa_id,
	  
      'codigo_producto',
      new_codigo_producto,
      old_codigo_producto,
      
      'existencia_minima',
      new_existencia_minima,
      old_existencia_minima,
            
      'existencia_maxima',
      new_existencia_maxima,
      old_existencia_maxima,
      
      'existencia',
      new_existencia,
      old_existencia,
      
      'costo_anterior',
      new_costo_anterior,
      old_costo_anterior,
      
      'costo',
      new_costo,
      old_costo,
      
      'costo_penultima_compra',
      new_costo_penultima_compra,
      old_costo_penultima_compra,
      
      'costo_ultima_compra',
      new_costo_ultima_compra,
      old_costo_ultima_compra,
      
      'precio_venta_anterior',
      new_precio_venta_anterior,
      old_precio_venta_anterior,
      
      'precio_venta',
      new_precio_venta,
      old_precio_venta,
      
      'precio_minimo',
      new_precio_minimo,
      old_precio_minimo,
      
      'precio_maximo',
      new_precio_maximo,
      old_precio_maximo,
      
      'sw_vende',
      new_sw_vende,
      old_sw_vende,
      
      'usuario_id',
      new_usuario_id,
      old_usuario_id,
      
      'estado',
      new_estado,
      old_estado,
      
      'fecha_registro',
      new_fecha_registro,
      old_fecha_registro,
      
      'sw_servicio',
      new_sw_servicio,
      old_sw_servicio,
      
      'grupo_contratacion_id',
      new_grupo_contratacion_id,
      old_grupo_contratacion_id,
      
      'nivel_autorizacion_id',
      new_nivel_autorizacion_id,
      old_nivel_autorizacion_id,
      
      'cssp',
      new_cssp,
      old_cssp,
      
      'cantidad_max_formulacion',
      new_cantidad_max_formulacion,
      old_cantidad_max_formulacion,
    
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

ALTER FUNCTION public.au_inventarios()
  OWNER TO "admin";
  
  CREATE TRIGGER aut_auditoria_inventarios
  AFTER INSERT OR UPDATE OR DELETE
  ON public.inventarios
  FOR EACH ROW
  EXECUTE PROCEDURE au_inventarios();

  
  /*
  * AUDITORIA: EXISTENCIAS BODEGAS
  */
 CREATE OR REPLACE FUNCTION public.au_existencias_bodegas()
RETURNS trigger AS
$$
DECLARE

  indicador RECORD;
  columnas RECORD;
  
		old_empresa_id 	character(2);
		old_centro_utilidad 	character(2);
		old_codigo_producto 	character varying(18);
		old_bodega 	character(2);
		old_existencia 	numeric(9,2);
		old_existencia_minima 	numeric(9,2);
		old_existencia_maxima 	numeric(9,2);
		old_usuario_id 	integer;
		old_fecha_registro 	timestamp;
		old_estado 	character varying(1);
		old_ubicacion_id 	integer;
		old_sw_control_fecha_vencimiento 	character varying(1);
		old_local_prod 	character varying(50);
		old_fecha_movimiento timestamp;
	
	  	new_empresa_id 	character(2);
		new_centro_utilidad 	character(2);
		new_codigo_producto 	character varying(18);
		new_bodega 	character(2);
		new_existencia 	numeric(9,2);
		new_existencia_minima 	numeric(9,2);
		new_existencia_maxima 	numeric(9,2);
		new_usuario_id 	integer;
		new_fecha_registro 	timestamp;
		new_estado 	character varying(1);
		new_ubicacion_id 	integer;
		new_sw_control_fecha_vencimiento 	character varying(1);
		new_local_prod 	character varying(50);
		new_fecha_movimiento timestamp;
	
	usuario_registro_ integer;
	fecha_registro timestamp;
  
BEGIN
  SELECT * INTO indicador
  FROM   acciones AC,
         novedad_x_acciones NA
  WHERE  AC.descripcion = TG_OP
  AND    NA.accion_id = AC.accion_id
  AND    NA.table_name = 'existencias_bodegas';
 
 /* Inicializar Variables */
 
  IF TG_OP = 'DELETE' THEN
   
	old_empresa_id 	:= OLD.empresa_id;
	old_centro_utilidad 	:= OLD.centro_utilidad;
	old_codigo_producto 	:= OLD.codigo_producto;
	old_bodega 	:= OLD.bodega;
	old_existencia 	:= OLD.existencia;
	old_existencia_minima 	:= OLD.existencia_minima;
	old_existencia_maxima 	:= OLD.existencia_maxima;	
	old_usuario_id 	:= OLD.usuario_id;
	old_fecha_registro 	:= OLD.fecha_registro;
	old_estado 	:= OLD.estado;
	old_ubicacion_id 	:= OLD.ubicacion_id;
	old_sw_control_fecha_vencimiento 	:= OLD.sw_control_fecha_vencimiento;
	old_local_prod 	:= OLD.local_prod;
	old_fecha_movimiento := OLD.fecha_movimiento;
	
	usuario_registro_ := OLD.usuario_id;
	fecha_registro := NOW();
  END IF; 
  
  IF TG_OP = 'UPDATE' THEN
	new_empresa_id 	:= NEW.empresa_id;
	new_centro_utilidad 	:= NEW.centro_utilidad;
	new_codigo_producto 	:= NEW.codigo_producto;
	new_bodega 	:= NEW.bodega;
	new_existencia 	:= NEW.existencia;
	new_existencia_minima 	:= NEW.existencia_minima;
	new_existencia_maxima 	:= NEW.existencia_maxima;	
	new_usuario_id 	:= NEW.usuario_id;
	new_fecha_registro 	:= NEW.fecha_registro;
	new_estado 	:= NEW.estado;
	new_ubicacion_id 	:= NEW.ubicacion_id;
	new_sw_control_fecha_vencimiento 	:= NEW.sw_control_fecha_vencimiento;
	new_local_prod 	:= NEW.local_prod;
	new_fecha_movimiento := NEW.fecha_movimiento;
	
	usuario_registro_ := NEW.usuario_id;
	fecha_registro := NOW();
  
	old_empresa_id 	:= OLD.empresa_id;
	old_centro_utilidad 	:= OLD.centro_utilidad;
	old_codigo_producto 	:= OLD.codigo_producto;
	old_bodega 	:= OLD.bodega;
	old_existencia 	:= OLD.existencia;
	old_existencia_minima 	:= OLD.existencia_minima;
	old_existencia_maxima 	:= OLD.existencia_maxima;	
	old_usuario_id 	:= OLD.usuario_id;
	old_fecha_registro 	:= OLD.fecha_registro;
	old_estado 	:= OLD.estado;
	old_ubicacion_id 	:= OLD.ubicacion_id;
	old_sw_control_fecha_vencimiento 	:= OLD.sw_control_fecha_vencimiento;
	old_local_prod 	:= OLD.local_prod;
	old_fecha_movimiento := OLD.fecha_movimiento;
  END IF; 
  
  IF TG_OP = 'INSERT' THEN
	new_empresa_id 	:= NEW.empresa_id;
	new_centro_utilidad 	:= NEW.centro_utilidad;
	new_codigo_producto 	:= NEW.codigo_producto;
	new_bodega 	:= NEW.bodega;
	new_existencia 	:= NEW.existencia;
	new_existencia_minima 	:= NEW.existencia_minima;
	new_existencia_maxima 	:= NEW.existencia_maxima;	
	new_usuario_id 	:= NEW.usuario_id;
	new_fecha_registro 	:= NEW.fecha_registro;
	new_estado 	:= NEW.estado;
	new_ubicacion_id 	:= NEW.ubicacion_id;
	new_sw_control_fecha_vencimiento 	:= NEW.sw_control_fecha_vencimiento;
	new_local_prod 	:= NEW.local_prod;
	new_fecha_movimiento := NEW.fecha_movimiento;

	
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
      'existencias_bodegas',
      
      'empresa_id',
      new_empresa_id,
      old_empresa_id,
	  
      'centro_utilidad',
      new_centro_utilidad,
      old_centro_utilidad,
      
      'codigo_producto',
      new_codigo_producto,
      old_codigo_producto,
            
      'bodega',
      new_bodega,
      old_bodega,
      
      'existencia',
      new_existencia,
      old_existencia,
      
      'existencia_minima',
      new_existencia_minima,
      old_existencia_minima,
      
      'existencia_maxima',
      new_existencia_maxima,
      old_existencia_maxima,
      
      'usuario_id',
      new_usuario_id,
      old_usuario_id,
      
      'fecha_registro',
      new_fecha_registro,
      old_fecha_registro,
      
      'estado',
      new_estado,
      old_estado,
      
      'ubicacion_id',
      new_ubicacion_id,
      old_ubicacion_id,
      
      'sw_control_fecha_vencimiento',
      new_sw_control_fecha_vencimiento,
      old_sw_control_fecha_vencimiento,
      
      'local_prod',
      new_local_prod,
      old_local_prod,
      
      'fecha_movimiento',
      new_fecha_movimiento,
      old_fecha_movimiento,
      
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

ALTER FUNCTION public.au_existencias_bodegas()
  OWNER TO "admin";
  
  CREATE TRIGGER aut_auditoria_existencias_bodegas
  AFTER INSERT OR UPDATE OR DELETE
  ON public.existencias_bodegas
  FOR EACH ROW
  EXECUTE PROCEDURE au_existencias_bodegas();
