  /*
  * PEDIDOS AUDITORIA
  */
  CREATE OR REPLACE FUNCTION public.au_pedidos_cliente_detalle()
RETURNS trigger AS	
$$
DECLARE
  
  indicador RECORD;
  columnas RECORD;
    
	old_item_id integer;
	old_pedido_cliente_id integer;
	old_codigo_producto 	character varying(50);
	old_porc_iva 	numeric(5,3);
	old_numero_unidades integer;	
	old_valor_unitario numeric(15,2)	;
	old_fecha_registro timestamp;	
	old_usuario_id 	integer;
	old_cantidad_despachada integer;
  
	new_item_id integer;
	new_pedido_cliente_id integer;
	new_codigo_producto 	character varying(50);
	new_porc_iva 	numeric(5,3);
	new_numero_unidades integer;	
	new_valor_unitario numeric(15,2)	;
	new_fecha_registro timestamp;	
	new_usuario_id 	integer;
	new_cantidad_despachada integer;
  
  usuario_registro_ integer;
  fecha_registro timestamp;
  
BEGIN
  SELECT * INTO indicador
  FROM   acciones AC,
         novedad_x_acciones NA
  WHERE  AC.descripcion = TG_OP
  AND    NA.accion_id = AC.accion_id
  AND    NA.table_name = 'ventas_ordenes_pedidos_d';
 
 /* Inicializar Variables */
 
  IF TG_OP = 'DELETE' THEN
   
	old_item_id 	:= OLD.item_id;
	old_pedido_cliente_id := OLD.pedido_cliente_id;
	old_codigo_producto 	:= OLD.codigo_producto;
	old_porc_iva 	:= OLD.porc_iva;
	old_numero_unidades := OLD.numero_unidades;	
	old_valor_unitario 	:= OLD.valor_unitario;
	old_fecha_registro := OLD.fecha_registro;
	old_usuario_id 	:= OLD.usuario_id;
	old_cantidad_despachada := OLD.cantidad_despachada;
  
	usuario_registro_ := OLD.usuario_id;
	fecha_registro := NOW();
  END IF; 
  
  IF TG_OP = 'UPDATE' THEN
	old_item_id 	:= OLD.item_id;
	old_pedido_cliente_id := OLD.pedido_cliente_id;
	old_codigo_producto 	:= OLD.codigo_producto;
	old_porc_iva 	:= OLD.porc_iva;
	old_numero_unidades := OLD.numero_unidades;	
	old_valor_unitario 	:= OLD.valor_unitario;
	old_fecha_registro := OLD.fecha_registro;
	old_usuario_id 	:= OLD.usuario_id;
	old_cantidad_despachada := OLD.cantidad_despachada;

	new_item_id 	:= NEW.item_id;
	new_pedido_cliente_id := NEW.pedido_cliente_id;
	new_codigo_producto 	:= NEW.codigo_producto;
	new_porc_iva 	:= NEW.porc_iva;
	new_numero_unidades := NEW.numero_unidades;	
	new_valor_unitario 	:= NEW.valor_unitario;
	new_fecha_registro := NEW.fecha_registro;
	new_usuario_id 	:= NEW.usuario_id;
	new_cantidad_despachada := NEW.cantidad_despachada;
	
	usuario_registro_ := NEW.usuario_id;
	fecha_registro := NOW();
  
  END IF; 
  
  IF TG_OP = 'INSERT' THEN
	
	new_item_id 	:= NEW.item_id;
	new_pedido_cliente_id := NEW.pedido_cliente_id;
	new_codigo_producto 	:= NEW.codigo_producto;
	new_porc_iva 	:= NEW.porc_iva;
	new_numero_unidades := NEW.numero_unidades;	
	new_valor_unitario 	:= NEW.valor_unitario;
	new_fecha_registro := NEW.fecha_registro;
	new_usuario_id 	:= NEW.usuario_id;
	new_cantidad_despachada := NEW.cantidad_despachada;
  
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
      'ventas_ordenes_pedidos_d',
      
      'item_id',
      new_item_id,
      old_item_id,
      
      'pedido_cliente_id',    
      new_pedido_cliente_id,
      old_pedido_cliente_id,
     
      'codigo_producto',
      new_codigo_producto,
      old_codigo_producto,
      
      'porc_iva',
      new_porc_iva,
      old_porc_iva,
      
      'numero_unidades',
      new_numero_unidades,
      old_numero_unidades,
      
      'valor_unitario',
      new_valor_unitario,
      old_valor_unitario,
      
      'fecha_registro',
      new_fecha_registro,
      old_fecha_registro,
      
      'usuario_id',
      new_usuario_id,
      old_usuario_id,
      
      'cantidad_despachada',
      new_cantidad_despachada,
      old_cantidad_despachada,
                     
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

ALTER FUNCTION public.au_pedidos_cliente_detalle()
  OWNER TO "admin";
  
  
  CREATE TRIGGER aut_auditoria_pedidos_clientes_detalle
  AFTER INSERT OR UPDATE OR DELETE
  ON public.ventas_ordenes_pedidos_d
  FOR EACH ROW
  EXECUTE PROCEDURE au_pedidos_cliente_detalle();

  
  /*
  * Trigger auditoria Para La tabla de terceros
  */
    CREATE OR REPLACE FUNCTION public.au_terceros()
RETURNS trigger AS	
$$
DECLARE
  
  indicador RECORD;
  columnas RECORD;
    
	old_tipo_id_tercero character varying(3);	
	old_tercero_id character varying(32);		
	old_tipo_pais_id character varying(4);		
	old_tipo_dpto_id character varying(4);		
	old_tipo_mpio_id character varying(4);		
	old_direccion character varying(100);		
	old_telefono character varying(30);		
	old_fax 	character varying(15);	
	old_email character varying(60);		
	old_celular character varying(15);		
	old_sw_persona_juridica character(1);	
	old_cal_cli character(1);	
	old_usuario_id integer;	
	old_fecha_registro timestamp;	
	old_busca_persona character varying(25);		
	old_nombre_tercero character varying(100);		
	old_dv character(1);
	old_tipo_bloqueo_id character varying(3);	
	old_empresa_id character varying(2);	
  
	new_tipo_id_tercero character varying(3);	
	new_tercero_id character varying(32);		
	new_tipo_pais_id character varying(4);		
	new_tipo_dpto_id character varying(4);		
	new_tipo_mpio_id character varying(4);		
	new_direccion character varying(100);		
	new_telefono character varying(30);		
	new_fax 	character varying(15);	
	new_email character varying(60);		
	new_celular character varying(15);		
	new_sw_persona_juridica character(1);	
	new_cal_cli character(1);	
	new_usuario_id integer;	
	new_fecha_registro timestamp;	
	new_busca_persona character varying(25);		
	new_nombre_tercero character varying(100);		
	new_dv character(1);
	new_tipo_bloqueo_id character varying(3);	
	new_empresa_id character varying(2);	
  
  usuario_registro_ integer;
  fecha_registro timestamp;
  
BEGIN
  SELECT * INTO indicador
  FROM   acciones AC,
         novedad_x_acciones NA
  WHERE  AC.descripcion = TG_OP
  AND    NA.accion_id = AC.accion_id
  AND    NA.table_name = 'terceros';
 
 /* Inicializar Variables */
 
  IF TG_OP = 'DELETE' THEN
   
	old_tipo_id_tercero := OLD.tipo_id_tercero;
	old_tercero_id  := OLD.tercero_id;	
	old_tipo_pais_id  := OLD.tipo_pais_id;	
	old_tipo_dpto_id  := OLD.tipo_dpto_id;	
	old_tipo_mpio_id  := OLD.tipo_mpio_id;	
	old_direccion  := OLD.direccion;	
	old_telefono  := OLD.telefono;	
	old_fax  := OLD.fax;	
	old_email  := OLD.email;	
	old_celular  := OLD.celular;	
	old_sw_persona_juridica  := OLD.sw_persona_juridica;	
	old_cal_cli  := OLD.cal_cli;	
	old_usuario_id  := OLD.usuario_id;	
	old_fecha_registro  := OLD.fecha_registro;	
	old_busca_persona  := OLD.busca_persona;	
	old_nombre_tercero  := OLD.nombre_tercero;	
	old_dv  := OLD.dv;	
	old_tipo_bloqueo_id  := OLD.tipo_bloqueo_id;	
	old_empresa_id  := OLD.empresa_id;
  
	usuario_registro_ := OLD.usuario_id;
	fecha_registro := NOW();
  END IF; 
  
  IF TG_OP = 'UPDATE' THEN
	old_tipo_id_tercero := OLD.tipo_id_tercero;
	old_tercero_id  := OLD.tercero_id;	
	old_tipo_pais_id  := OLD.tipo_pais_id;	
	old_tipo_dpto_id  := OLD.tipo_dpto_id;	
	old_tipo_mpio_id  := OLD.tipo_mpio_id;	
	old_direccion  := OLD.direccion;	
	old_telefono  := OLD.telefono;	
	old_fax  := OLD.fax;	
	old_email  := OLD.email;	
	old_celular  := OLD.celular;	
	old_sw_persona_juridica  := OLD.sw_persona_juridica;	
	old_cal_cli  := OLD.cal_cli;	
	old_usuario_id  := OLD.usuario_id;	
	old_fecha_registro  := OLD.fecha_registro;	
	old_busca_persona  := OLD.busca_persona;	
	old_nombre_tercero  := OLD.nombre_tercero;	
	old_dv  := OLD.dv;	
	old_tipo_bloqueo_id  := OLD.tipo_bloqueo_id;	
	old_empresa_id  := OLD.empresa_id;

	new_tipo_id_tercero := NEW.tipo_id_tercero;
	new_tercero_id  := NEW.tercero_id;	
	new_tipo_pais_id  := NEW.tipo_pais_id;	
	new_tipo_dpto_id  := NEW.tipo_dpto_id;	
	new_tipo_mpio_id  := NEW.tipo_mpio_id;	
	new_direccion  := NEW.direccion;	
	new_telefono  := NEW.telefono;	
	new_fax  := NEW.fax;	
	new_email  := NEW.email;	
	new_celular  := NEW.celular;	
	new_sw_persona_juridica  := NEW.sw_persona_juridica;	
	new_cal_cli  := NEW.cal_cli;	
	new_usuario_id  := NEW.usuario_id;	
	new_fecha_registro  := NEW.fecha_registro;	
	new_busca_persona  := NEW.busca_persona;	
	new_nombre_tercero  := NEW.nombre_tercero;	
	new_dv  := NEW.dv;	
	new_tipo_bloqueo_id  := NEW.tipo_bloqueo_id;	
	new_empresa_id  := NEW.empresa_id;
	
	usuario_registro_ := NEW.usuario_id;
	fecha_registro := NOW();
  
  END IF; 
  
  IF TG_OP = 'INSERT' THEN
	
	new_tipo_id_tercero := NEW.tipo_id_tercero;
	new_tercero_id  := NEW.tercero_id;	
	new_tipo_pais_id  := NEW.tipo_pais_id;	
	new_tipo_dpto_id  := NEW.tipo_dpto_id;	
	new_tipo_mpio_id  := NEW.tipo_mpio_id;	
	new_direccion  := NEW.direccion;	
	new_telefono  := NEW.telefono;	
	new_fax  := NEW.fax;	
	new_email  := NEW.email;	
	new_celular  := NEW.celular;	
	new_sw_persona_juridica  := NEW.sw_persona_juridica;	
	new_cal_cli  := NEW.cal_cli;	
	new_usuario_id  := NEW.usuario_id;	
	new_fecha_registro  := NEW.fecha_registro;	
	new_busca_persona  := NEW.busca_persona;	
	new_nombre_tercero  := NEW.nombre_tercero;	
	new_dv  := NEW.dv;	
	new_tipo_bloqueo_id  := NEW.tipo_bloqueo_id;	
	new_empresa_id  := NEW.empresa_id;
  
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
      'terceros',
      
      'tipo_id_tercero',
      new_tipo_id_tercero,
      old_tipo_id_tercero,
      
      'tercero_id',    
      new_tercero_id,
      old_tercero_id,
     
      'tipo_pais_id',
      new_tipo_pais_id,
      old_tipo_pais_id,
      
      'tipo_dpto_id',
      new_tipo_dpto_id,
      old_tipo_dpto_id,
      
      'tipo_mpio_id',
      new_tipo_mpio_id,
      old_tipo_mpio_id,
      
      'direccion',
      new_direccion,
      old_direccion,
      
      'telefono',
      new_telefono,
      old_telefono,
      
      'fax',
      new_fax,
      old_fax,
      
      'email',
      new_email,
      old_email,
      
      'celular',
      new_celular,
      old_celular,
      
      'sw_persona_juridica',
      new_sw_persona_juridica,
      old_sw_persona_juridica,
      
      'cal_cli',
      new_cal_cli,
      old_cal_cli,
      
      'usuario_id',
      new_usuario_id,
      old_usuario_id,
      
      'fecha_registro',
      new_fecha_registro,
      old_fecha_registro,
      
      'busca_persona',
      new_busca_persona,
      old_busca_persona,
      
      'nombre_tercero',
      new_nombre_tercero,
      old_nombre_tercero,
      
      'dv',
      new_dv,
      old_dv,
      
      'tipo_bloqueo_id',
      new_tipo_bloqueo_id,
      old_tipo_bloqueo_id,
      
      'empresa_id',
      new_empresa_id,
      old_empresa_id,
           
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

ALTER FUNCTION public.au_terceros()
  OWNER TO "admin";
  
  CREATE TRIGGER aut_auditoria_terceros
  AFTER INSERT OR UPDATE OR DELETE
  ON public.terceros
  FOR EACH ROW
  EXECUTE PROCEDURE au_terceros();

  /*
  * Trigger auditoria Para La tabla de terceros Proveedores
  */
    CREATE OR REPLACE FUNCTION public.au_terceros_proveedores()
RETURNS trigger AS	
$$
DECLARE
  
  indicador RECORD;
  columnas RECORD;
    
	old_codigo_proveedor_id 	integer;
	old_empresa_id character(2);	
	old_tipo_id_tercero character varying(3);	
	old_tercero_id character varying(32);		
	old_empresa_id_centro character(2);	
	old_centro_utilidad character(2);
	old_estado character(1);	
	old_dias_gracia 	smallint;
	old_dias_credito smallint;	
	old_tiempo_entrega smallint;	
	old_descuento_por_contado numeric(5,2);
	old_cupo numeric(16,2);	
	old_sw_regimen_comun character(1);	
	old_sw_gran_contribuyente character(1);	
	old_actividad_id character varying(10);		
	old_porcentaje_rtf 	numeric(9,4);
	old_porcentaje_ica numeric(9,4);	
	old_representante_ventas character varying(40);		
	old_telefono_representante_ventas 	character varying(20);	
	old_nombre_gerente 	character varying(40);	
	old_prioridad_compra character varying(2);		
	old_telefono_gerente character varying(20);		
	old_porcentaje_reteiva 	numeric(9,4);
	old_sw_rtf character(1);	
	old_sw_reteiva character(1);
	old_sw_ica character(1);
  
	new_codigo_proveedor_id 	integer;
	new_empresa_id character(2);	
	new_tipo_id_tercero character varying(3);	
	new_tercero_id character varying(32);		
	new_empresa_id_centro character(2);	
	new_centro_utilidad character(2);
	new_estado character(1);	
	new_dias_gracia 	smallint;
	new_dias_credito smallint;	
	new_tiempo_entrega smallint;	
	new_descuento_por_contado numeric(5,2);
	new_cupo numeric(16,2);	
	new_sw_regimen_comun character(1);	
	new_sw_gran_contribuyente character(1);	
	new_actividad_id character varying(10);		
	new_porcentaje_rtf 	numeric(9,4);
	new_porcentaje_ica numeric(9,4);	
	new_representante_ventas character varying(40);		
	new_telefono_representante_ventas 	character varying(20);	
	new_nombre_gerente 	character varying(40);	
	new_prioridad_compra character varying(2);		
	new_telefono_gerente character varying(20);		
	new_porcentaje_reteiva 	numeric(9,4);
	new_sw_rtf character(1);	
	new_sw_reteiva character(1);
	new_sw_ica character(1);
  
  usuario_registro_ integer;
  fecha_registro timestamp;
  
BEGIN
  SELECT * INTO indicador
  FROM   acciones AC,
         novedad_x_acciones NA
  WHERE  AC.descripcion = TG_OP
  AND    NA.accion_id = AC.accion_id
  AND    NA.table_name = 'terceros_proveedores';
 
 /* Inicializar Variables */
 
  IF TG_OP = 'DELETE' THEN
   
	old_codigo_proveedor_id 	:= OLD.codigo_proveedor_id;
	old_empresa_id 	:= OLD.empresa_id; 	
	old_tipo_id_tercero 	:= OLD.tipo_id_tercero; 	
	old_tercero_id 	:= OLD.tercero_id; 	
	old_empresa_id_centro 	:= OLD.empresa_id_centro; 	
	old_centro_utilidad 	:= OLD.centro_utilidad; 	
	old_estado 	:= OLD.estado; 	
	old_dias_gracia 	:= OLD.dias_gracia; 	
	old_dias_credito 	:= OLD.dias_credito; 	
	old_tiempo_entrega 	:= OLD.tiempo_entrega; 	
	old_descuento_por_contado 	:= OLD.descuento_por_contado; 	
	old_cupo 	:= OLD.cupo; 	
	old_sw_regimen_comun := OLD.sw_regimen_comun; 	
	old_sw_gran_contribuyente := OLD.sw_gran_contribuyente; 	
	old_actividad_id 	:= OLD.actividad_id; 	
	old_porcentaje_rtf 	:= OLD.porcentaje_rtf; 	
	old_porcentaje_ica 	:= OLD.porcentaje_ica; 	
	old_representante_ventas 	:= OLD.representante_ventas; 	
	old_telefono_representante_ventas 	:= OLD.telefono_representante_ventas; 	
	old_nombre_gerente 	:= OLD.nombre_gerente; 	
	old_prioridad_compra 	:= OLD.prioridad_compra; 	
	old_telefono_gerente 	:= OLD.telefono_gerente; 	
	old_porcentaje_reteiva 	:= OLD.porcentaje_reteiva; 	
	old_sw_rtf 	:= OLD.sw_rtf; 	
	old_sw_reteiva 	:= OLD.sw_reteiva; 	
	old_sw_ica := OLD.sw_ica;
  
	usuario_registro_ := 2;
	fecha_registro := NOW();
  END IF; 
  
  IF TG_OP = 'UPDATE' THEN
	old_codigo_proveedor_id 	:= OLD.codigo_proveedor_id;
	old_empresa_id 	:= OLD.empresa_id; 	
	old_tipo_id_tercero 	:= OLD.tipo_id_tercero; 	
	old_tercero_id 	:= OLD.tercero_id; 	
	old_empresa_id_centro 	:= OLD.empresa_id_centro; 	
	old_centro_utilidad 	:= OLD.centro_utilidad; 	
	old_estado 	:= OLD.estado; 	
	old_dias_gracia 	:= OLD.dias_gracia; 	
	old_dias_credito 	:= OLD.dias_credito; 	
	old_tiempo_entrega 	:= OLD.tiempo_entrega; 	
	old_descuento_por_contado 	:= OLD.descuento_por_contado; 	
	old_cupo 	:= OLD.cupo; 	
	old_sw_regimen_comun := OLD.sw_regimen_comun; 	
	old_sw_gran_contribuyente := OLD.sw_gran_contribuyente; 	
	old_actividad_id 	:= OLD.actividad_id; 	
	old_porcentaje_rtf 	:= OLD.porcentaje_rtf; 	
	old_porcentaje_ica 	:= OLD.porcentaje_ica; 	
	old_representante_ventas 	:= OLD.representante_ventas; 	
	old_telefono_representante_ventas 	:= OLD.telefono_representante_ventas; 	
	old_nombre_gerente 	:= OLD.nombre_gerente; 	
	old_prioridad_compra 	:= OLD.prioridad_compra; 	
	old_telefono_gerente 	:= OLD.telefono_gerente; 	
	old_porcentaje_reteiva 	:= OLD.porcentaje_reteiva; 	
	old_sw_rtf 	:= OLD.sw_rtf; 	
	old_sw_reteiva 	:= OLD.sw_reteiva; 	
	old_sw_ica := OLD.sw_ica;

	new_codigo_proveedor_id 	:= NEW.codigo_proveedor_id;
	new_empresa_id 	:= NEW.empresa_id; 	
	new_tipo_id_tercero 	:= NEW.tipo_id_tercero; 	
	new_tercero_id 	:= NEW.tercero_id; 	
	new_empresa_id_centro 	:= NEW.empresa_id_centro; 	
	new_centro_utilidad 	:= NEW.centro_utilidad; 	
	new_estado 	:= NEW.estado; 	
	new_dias_gracia 	:= NEW.dias_gracia; 	
	new_dias_credito 	:= NEW.dias_credito; 	
	new_tiempo_entrega 	:= NEW.tiempo_entrega; 	
	new_descuento_por_contado 	:= NEW.descuento_por_contado; 	
	new_cupo 	:= NEW.cupo; 	
	new_sw_regimen_comun := NEW.sw_regimen_comun; 	
	new_sw_gran_contribuyente := NEW.sw_gran_contribuyente; 	
	new_actividad_id 	:= NEW.actividad_id; 	
	new_porcentaje_rtf 	:= NEW.porcentaje_rtf; 	
	new_porcentaje_ica 	:= NEW.porcentaje_ica; 	
	new_representante_ventas 	:= NEW.representante_ventas; 	
	new_telefono_representante_ventas 	:= NEW.telefono_representante_ventas; 	
	new_nombre_gerente 	:= NEW.nombre_gerente; 	
	new_prioridad_compra 	:= NEW.prioridad_compra; 	
	new_telefono_gerente 	:= NEW.telefono_gerente; 	
	new_porcentaje_reteiva 	:= NEW.porcentaje_reteiva; 	
	new_sw_rtf 	:= NEW.sw_rtf; 	
	new_sw_reteiva 	:= NEW.sw_reteiva; 	
	new_sw_ica := NEW.sw_ica;
	
	usuario_registro_ := 2;
	fecha_registro := NOW();
  
  END IF; 
  
  IF TG_OP = 'INSERT' THEN
	
	new_codigo_proveedor_id 	:= NEW.codigo_proveedor_id;
	new_empresa_id 	:= NEW.empresa_id; 	
	new_tipo_id_tercero 	:= NEW.tipo_id_tercero; 	
	new_tercero_id 	:= NEW.tercero_id; 	
	new_empresa_id_centro 	:= NEW.empresa_id_centro; 	
	new_centro_utilidad 	:= NEW.centro_utilidad; 	
	new_estado 	:= NEW.estado; 	
	new_dias_gracia 	:= NEW.dias_gracia; 	
	new_dias_credito 	:= NEW.dias_credito; 	
	new_tiempo_entrega 	:= NEW.tiempo_entrega; 	
	new_descuento_por_contado 	:= NEW.descuento_por_contado; 	
	new_cupo 	:= NEW.cupo; 	
	new_sw_regimen_comun := NEW.sw_regimen_comun; 	
	new_sw_gran_contribuyente := NEW.sw_gran_contribuyente; 	
	new_actividad_id 	:= NEW.actividad_id; 	
	new_porcentaje_rtf 	:= NEW.porcentaje_rtf; 	
	new_porcentaje_ica 	:= NEW.porcentaje_ica; 	
	new_representante_ventas 	:= NEW.representante_ventas; 	
	new_telefono_representante_ventas 	:= NEW.telefono_representante_ventas; 	
	new_nombre_gerente 	:= NEW.nombre_gerente; 	
	new_prioridad_compra 	:= NEW.prioridad_compra; 	
	new_telefono_gerente 	:= NEW.telefono_gerente; 	
	new_porcentaje_reteiva 	:= NEW.porcentaje_reteiva; 	
	new_sw_rtf 	:= NEW.sw_rtf; 	
	new_sw_reteiva 	:= NEW.sw_reteiva; 	
	new_sw_ica := NEW.sw_ica;
  
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
      
      campo_23, 
      nuevo_valor_23,
      antiguo_valor_23,
      
      campo_24, 
      nuevo_valor_24,
      antiguo_valor_24,
      
      campo_25, 
      nuevo_valor_25,
      antiguo_valor_25,
      
      campo_26, 
      nuevo_valor_26,
      antiguo_valor_26,
               
     usuario_registro

    )
    VALUES
    (
      indicador.accion_id,
      indicador.novedad_indicador_id,
      'terceros_proveedores',
      
      'codigo_proveedor_id',
      new_codigo_proveedor_id,
      old_codigo_proveedor_id,
      
      'empresa_id',    
      new_empresa_id,
      old_empresa_id,
     
      'tipo_id_tercero',
      new_tipo_id_tercero,
      old_tipo_id_tercero ,
      
      'tercero_id',
      new_tercero_id,
      old_tercero_id,
      
      'empresa_id_centro',
      new_empresa_id_centro,
      old_empresa_id_centro,
      
      'centro_utilidad',
      new_centro_utilidad,
      old_centro_utilidad,
      
      'estado',
      new_estado,
      old_estado,
      
      'dias_gracia',
      new_dias_gracia,
      old_dias_gracia,
      
      'dias_credito',
      new_dias_credito,
      old_dias_credito,
      
      'tiempo_entrega',
      new_tiempo_entrega,
      old_tiempo_entrega,
      
      'descuento_por_contado',
      new_descuento_por_contado,
      old_descuento_por_contado,
      
      'cupo',
      new_cupo,
      old_cupo,
      
      'sw_regimen_comun',
      new_sw_regimen_comun,
      old_sw_regimen_comun,
      
      'sw_gran_contribuyente',
      new_sw_gran_contribuyente,
      old_sw_gran_contribuyente,
      
      'actividad_id',
      new_actividad_id,
      old_actividad_id,
      
      'porcentaje_rtf',
      new_porcentaje_rtf,
      old_porcentaje_rtf,
      
      'porcentaje_ica',
      new_porcentaje_ica,
      old_porcentaje_ica,
      
      'representante_ventas',
      new_representante_ventas,
      old_representante_ventas,
      
      'telefono_representante_ventas',
      new_telefono_representante_ventas,
      old_telefono_representante_ventas,
      
      'nombre_gerente',
      new_nombre_gerente,
      old_nombre_gerente,
      
      'prioridad_compra',
      new_prioridad_compra,
      old_prioridad_compra,
      
      'telefono_gerente',
      new_telefono_gerente,
      old_telefono_gerente,
      
      'porcentaje_reteiva',
      new_porcentaje_reteiva,
      old_porcentaje_reteiva,
      
      'sw_rtf',
      new_sw_rtf,
      old_sw_rtf,
      
      'sw_reteiva',
      new_sw_reteiva,
      old_sw_reteiva,
      
      'sw_ica',
      new_sw_ica,
      old_sw_ica,
           
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

ALTER FUNCTION public.au_terceros_proveedores()
  OWNER TO "admin";
  
  CREATE TRIGGER aut_auditoria_terceros_proveedores
  AFTER INSERT OR UPDATE OR DELETE
  ON public.terceros_proveedores
  FOR EACH ROW
  EXECUTE PROCEDURE au_terceros_proveedores();

  
  /*
  * Trigger auditoria Para La tabla de terceros Clientes
  */
    CREATE OR REPLACE FUNCTION public.au_terceros_clientes()
RETURNS trigger AS	
$$
DECLARE
  
  indicador RECORD;
  columnas RECORD;
    
	old_empresa_id 	character(2);
	old_tipo_id_tercero character varying(3);
	old_tercero_id character varying(32);	
	old_sw_gran_contribuyente character(1);	
	old_porcentaje_reteiva numeric(9,4);	
	old_porcentaje_ica numeric(9,4);	
	old_observacion text;	
	old_porcentaje_rtf 	numeric (9,4);
	old_sw_rtf character(1);	
	old_sw_reteiva character(1);	
	old_sw_regimen_comun character(1);	
	old_codigo_unidad_negocio character varying(2);	
	old_sw_ica character(1);	
	old_tipo_cliente character varying(4);
  
	new_empresa_id 	character(2);
	new_tipo_id_tercero character varying(3);
	new_tercero_id character varying(32);	
	new_sw_gran_contribuyente character(1);	
	new_porcentaje_reteiva numeric(9,4);
	new_porcentaje_ica numeric(9,4);	
	new_observacion text;	
	new_porcentaje_rtf 	numeric (9,4);
	new_sw_rtf character(1);	
	new_sw_reteiva character(1);	
	new_sw_regimen_comun character(1);	
	new_codigo_unidad_negocio character varying(2);	
	new_sw_ica character(1);	
	new_tipo_cliente character varying(4);
  
  usuario_registro_ integer;
  fecha_registro timestamp;
  
BEGIN
  SELECT * INTO indicador
  FROM   acciones AC,
         novedad_x_acciones NA
  WHERE  AC.descripcion = TG_OP
  AND    NA.accion_id = AC.accion_id
  AND    NA.table_name = 'terceros_clientes';
 
 /* Inicializar Variables */
 
  IF TG_OP = 'DELETE' THEN
   
	old_empresa_id 	:= OLD.empresa_id; 	
	old_tipo_id_tercero 	:= OLD.tipo_id_tercero;
	old_tercero_id 	:= OLD.tercero_id; 	
	old_sw_gran_contribuyente 	:= OLD.sw_gran_contribuyente; 	
	old_porcentaje_reteiva 	:= OLD.porcentaje_reteiva; 	
	old_porcentaje_ica 	:= OLD.porcentaje_ica; 	
	old_observacion 	:= OLD.observacion; 	
	old_porcentaje_rtf 	:= OLD.porcentaje_rtf; 	
	old_sw_rtf 	:= OLD.sw_rtf; 	
	old_sw_reteiva 	:= OLD.sw_reteiva; 	
	old_sw_regimen_comun 	:= OLD.sw_regimen_comun; 	
	old_codigo_unidad_negocio 	:= OLD.codigo_unidad_negocio; 	
	old_sw_ica 	:= OLD.sw_ica; 	
	old_tipo_cliente := OLD.tipo_cliente;
  
	usuario_registro_ := 2;
	fecha_registro := NOW();
  END IF; 
  
  IF TG_OP = 'UPDATE' THEN
	old_empresa_id 	:= OLD.empresa_id; 	
	old_tipo_id_tercero 	:= OLD.tipo_id_tercero;
	old_tercero_id 	:= OLD.tercero_id; 	
	old_sw_gran_contribuyente 	:= OLD.sw_gran_contribuyente; 	
	old_porcentaje_reteiva 	:= OLD.porcentaje_reteiva; 	
	old_porcentaje_ica 	:= OLD.porcentaje_ica; 	
	old_observacion 	:= OLD.observacion; 	
	old_porcentaje_rtf 	:= OLD.porcentaje_rtf; 	
	old_sw_rtf 	:= OLD.sw_rtf; 	
	old_sw_reteiva 	:= OLD.sw_reteiva; 	
	old_sw_regimen_comun 	:= OLD.sw_regimen_comun; 	
	old_codigo_unidad_negocio 	:= OLD.codigo_unidad_negocio; 	
	old_sw_ica 	:= OLD.sw_ica; 	
	old_tipo_cliente := OLD.tipo_cliente;

	new_empresa_id 	:= NEW.empresa_id; 	
	new_tipo_id_tercero 	:= NEW.tipo_id_tercero;
	new_tercero_id 	:= NEW.tercero_id; 	
	new_sw_gran_contribuyente 	:= NEW.sw_gran_contribuyente; 	
	new_porcentaje_reteiva 	:= NEW.porcentaje_reteiva; 	
	new_porcentaje_ica 	:= NEW.porcentaje_ica; 	
	new_observacion 	:= NEW.observacion; 	
	new_porcentaje_rtf 	:= NEW.porcentaje_rtf; 	
	new_sw_rtf 	:= NEW.sw_rtf; 	
	new_sw_reteiva 	:= NEW.sw_reteiva; 	
	new_sw_regimen_comun 	:= NEW.sw_regimen_comun; 	
	new_codigo_unidad_negocio 	:= NEW.codigo_unidad_negocio; 	
	new_sw_ica 	:= NEW.sw_ica; 	
	new_tipo_cliente := NEW.tipo_cliente;
	
	usuario_registro_ := 2;
	fecha_registro := NOW();
  
  END IF; 
  
  IF TG_OP = 'INSERT' THEN
	
	new_empresa_id 	:= NEW.empresa_id; 	
	new_tipo_id_tercero 	:= NEW.tipo_id_tercero;
	new_tercero_id 	:= NEW.tercero_id; 	
	new_sw_gran_contribuyente 	:= NEW.sw_gran_contribuyente; 	
	new_porcentaje_reteiva 	:= NEW.porcentaje_reteiva; 	
	new_porcentaje_ica 	:= NEW.porcentaje_ica; 	
	new_observacion 	:= NEW.observacion; 	
	new_porcentaje_rtf 	:= NEW.porcentaje_rtf; 	
	new_sw_rtf 	:= NEW.sw_rtf; 	
	new_sw_reteiva 	:= NEW.sw_reteiva; 	
	new_sw_regimen_comun 	:= NEW.sw_regimen_comun; 	
	new_codigo_unidad_negocio 	:= NEW.codigo_unidad_negocio; 	
	new_sw_ica 	:= NEW.sw_ica; 	
	new_tipo_cliente := NEW.tipo_cliente;
  
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
      
      campo_14, 
      nuevo_valor_14,
      antiguo_valor_14,
                     
     usuario_registro

    )
    VALUES
    (
      indicador.accion_id,
      indicador.novedad_indicador_id,
      'terceros_clientes',
      
      'empresa_id',
      new_empresa_id,
      old_empresa_id,
      
      'tipo_id_tercero',    
      new_tipo_id_tercero,
      old_tipo_id_tercero,
     
      'tercero_id',
      new_tercero_id,
      old_tercero_id ,
      
      'sw_gran_contribuyente',
      new_sw_gran_contribuyente,
      old_sw_gran_contribuyente,
      
      'porcentaje_reteiva',
      new_porcentaje_reteiva,
      old_porcentaje_reteiva,
      
      'porcentaje_ica',
      new_porcentaje_ica,
      old_porcentaje_ica,
      
      'observacion',
      new_observacion,
      old_observacion,
      
      'porcentaje_rtf',
      new_porcentaje_rtf,
      old_porcentaje_rtf,
      
      'sw_rtf',
      new_sw_rtf,
      old_sw_rtf,
      
      'sw_reteiva',
      new_sw_reteiva,
      old_sw_reteiva,
      
      'sw_regimen_comun',
      new_sw_regimen_comun,
      old_sw_regimen_comun,
      
      'codigo_unidad_negocio',
      new_codigo_unidad_negocio,
      old_codigo_unidad_negocio,
      
      'sw_ica',
      new_sw_ica,
      old_sw_ica,
      
      'tipo_cliente',
      new_tipo_cliente,
      old_tipo_cliente,
      
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

ALTER FUNCTION public.au_terceros_clientes()
  OWNER TO "admin";
  
  CREATE TRIGGER aut_auditoria_terceros_clientes
  AFTER INSERT OR UPDATE OR DELETE
  ON public.terceros_clientes
  FOR EACH ROW
  EXECUTE PROCEDURE au_terceros_clientes();

  
