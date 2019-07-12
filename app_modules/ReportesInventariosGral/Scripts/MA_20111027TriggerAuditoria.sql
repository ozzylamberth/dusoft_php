CREATE OR REPLACE FUNCTION public.au_contratacion()
RETURNS trigger AS
$$
DECLARE

  indicador RECORD;
  columnas RECORD;
  
	old_plan_id 	integer;
	old_empresa_id character(2);
	old_tipo_tercero_id character varying(3);	
	old_tercero_id  character varying(32);		
	old_plan_descripcion  character varying(60);	
	old_tipo_cliente  character varying(2);		
	old_num_contrato  character varying(20);		
	old_fecha_inicio date;	
	old_fecha_final 	date;
	old_monto_contrato numeric(16,2);	
	old_saldo_contrato numeric(16,2);	
	old_tope_maximo_factura numeric(12,2);	
	old_sw_autoriza_sin_bd character(1);	
	old_sw_afiliacion character(1);	
	old_sw_tipo_plan character(1);	
	old_fecha_registro timestamp;	
	old_sw_paragrafados_cd character(1);	
	old_sw_paragrafados_imd 	character(1);
	old_usuario_id integer;	
	old_estado character(1);	
	old_sw_facturacion_agrupada character(1);
	old_servicios_contratados text;
	old_protocolos  character varying(255);	
	old_contacto text;	
	old_nombre_cuota_moderadora 	character varying(20);	
	old_nombre_copago character varying(20);		
	old_lineas_atencion 	text;
	old_monto_contrato_mensual 	numeric(16,2);	
	old_sw_base_liquidacion_imd 	character varying(1);	
	old_sw_exceder_monto_mensual character varying(1);	
	old_tipo_liquidacion_id integer;
	old_actividad_incumplimientos smallint;	
	old_meses_consulta_base_datos smallint;
	old_telefono_cancelacion_cita character varying(40);
	old_horas_cancelacion character varying(2);	
	old_observacion text;	
	old_tipo_liquidacion_cargo 	integer;
	old_tipo_para_imd integer;	
	old_dias_credito_cartera smallint;
	old_sw_contrata_hospitalizacion 	character(1);
	old_lista_precios character varying(4);	
	old_porcentaje_utilidad 	numeric(15,2);
	old_protocolo_internacion text;	
	old_sw_rips_con_cargo_cups 	character(1);
	old_marca_prioridad_atencion character varying(1);		
	old_sw_solicita_autorizacion_admision 	character varying(1);
	old_porcentaje_descuento_iym numeric(12,2);	
	old_mensaje_plan text;	
	old_sw_afiliados character(1);
	old_programas_id 	integer;
	old_sw_desc_nomina character varying(1);	
	old_programa_consulta_externa_id 	integer;
	old_sw_multimedicas integer;
  
	new_plan_id 	integer;
	new_empresa_id character(2);
	new_tipo_tercero_id character varying(3);	
	new_tercero_id  character varying(32);		
	new_plan_descripcion  character varying(60);	
	new_tipo_cliente  character varying(2);		
	new_num_contrato  character varying(20);		
	new_fecha_inicio date;	
	new_fecha_final 	date;
	new_monto_contrato numeric(16,2);	
	new_saldo_contrato numeric(16,2);	
	new_tope_maximo_factura numeric(12,2);	
	new_sw_autoriza_sin_bd character(1);	
	new_sw_afiliacion character(1);	
	new_sw_tipo_plan character(1);	
	new_fecha_registro timestamp;	
	new_sw_paragrafados_cd character(1);	
	new_sw_paragrafados_imd 	character(1);
	new_usuario_id integer;	
	new_estado character(1);	
	new_sw_facturacion_agrupada character(1);
	new_servicios_contratados text;
	new_protocolos  character varying(255);	
	new_contacto text;	
	new_nombre_cuota_moderadora 	character varying(20);	
	new_nombre_copago character varying(20);		
	new_lineas_atencion 	text;
	new_monto_contrato_mensual 	numeric(16,2);	
	new_sw_base_liquidacion_imd 	character varying(1);	
	new_sw_exceder_monto_mensual character varying(1);	
	new_tipo_liquidacion_id integer;
	new_actividad_incumplimientos smallint;	
	new_meses_consulta_base_datos smallint;
	new_telefono_cancelacion_cita character varying(40);
	new_horas_cancelacion character varying(2);	
	new_observacion text;	
	new_tipo_liquidacion_cargo 	integer;
	new_tipo_para_imd integer;	
	new_dias_credito_cartera smallint;
	new_sw_contrata_hospitalizacion 	character(1);
	new_lista_precios character varying(4);	
	new_porcentaje_utilidad 	numeric(15,2);
	new_protocolo_internacion text;	
	new_sw_rips_con_cargo_cups 	character(1);
	new_marca_prioridad_atencion character varying(1);		
	new_sw_solicita_autorizacion_admision 	character varying(1);
	new_porcentaje_descuento_iym numeric(12,2);	
	new_mensaje_plan text;	
	new_sw_afiliados character(1);
	new_programas_id 	integer;
	new_sw_desc_nomina character varying(1);	
	new_programa_consulta_externa_id 	integer;
	new_sw_multimedicas integer;
	
	usuario_registro_ integer;
	fecha_registro timestamp;
  
BEGIN
  SELECT * INTO indicador
  FROM   acciones AC,
         novedad_x_acciones NA
  WHERE  AC.descripcion = TG_OP
  AND    NA.accion_id = AC.accion_id
  AND    NA.table_name = 'planes';
 
 /* Inicializar Variables */
 
  IF TG_OP = 'DELETE' THEN
   
	old_plan_id 	:= OLD.plan_id; 	
	old_empresa_id 	:= OLD.empresa_id; 	
	old_tipo_tercero_id 	:= OLD.tipo_tercero_id; 	
	old_tercero_id 	:= OLD.tercero_id; 	
	old_plan_descripcion 	:= OLD.plan_descripcion; 	
	old_tipo_cliente 	:= OLD.tipo_cliente; 	
	old_num_contrato 	:= OLD.num_contrato; 	
	old_fecha_inicio 	:= OLD.fecha_inicio; 	
	old_fecha_final 	:= OLD.fecha_final; 	
	old_monto_contrato 	:= OLD.monto_contrato; 	
	old_saldo_contrato 	:= OLD.saldo_contrato; 	
	old_tope_maximo_factura 	:= OLD.tope_maximo_factura; 	
	old_sw_autoriza_sin_bd 	:= OLD.sw_autoriza_sin_bd; 	
	old_sw_afiliacion 	:= OLD.sw_afiliacion; 	
	old_sw_tipo_plan 	:= OLD.sw_tipo_plan; 	
	old_fecha_registro 	:= OLD.fecha_registro; 	
	old_sw_paragrafados_cd 	:= OLD.sw_paragrafados_cd; 	
	old_sw_paragrafados_imd 	:= OLD.sw_paragrafados_imd; 	
	old_usuario_id 	:= OLD.usuario_id; 	
	old_estado 	:= OLD.estado; 	
	old_sw_facturacion_agrupada 	:= OLD.sw_facturacion_agrupada; 	
	old_servicios_contratados 	:= OLD.servicios_contratados; 	
	old_protocolos 	:= OLD.protocolos; 	
	old_contacto 	:= OLD.contacto; 	
	old_nombre_cuota_moderadora 	:= OLD.nombre_cuota_moderadora; 	
	old_nombre_copago 	:= OLD.nombre_copago; 	
	old_lineas_atencion 	:= OLD.lineas_atencion; 	
	old_monto_contrato_mensual 	:= OLD.monto_contrato_mensual; 	
	old_sw_base_liquidacion_imd 	:= OLD.sw_base_liquidacion_imd; 	
	old_sw_exceder_monto_mensual 	:= OLD.sw_exceder_monto_mensual; 	
	old_tipo_liquidacion_id 	:= OLD.tipo_liquidacion_id; 	
	old_actividad_incumplimientos 	:= OLD.actividad_incumplimientos; 	
	old_meses_consulta_base_datos 	:= OLD.meses_consulta_base_datos; 	
	old_telefono_cancelacion_cita 	:= OLD.telefono_cancelacion_cita; 	
	old_horas_cancelacion 	:= OLD.horas_cancelacion; 	
	old_observacion 	:= OLD.observacion; 	
	old_tipo_liquidacion_cargo 	:= OLD.tipo_liquidacion_cargo; 	
	old_tipo_para_imd 	:= OLD.tipo_para_imd; 	
	old_dias_credito_cartera 	:= OLD.dias_credito_cartera; 	
	old_sw_contrata_hospitalizacion 	:= OLD.sw_contrata_hospitalizacion; 	
	old_lista_precios 	:= OLD.lista_precios; 	
	old_porcentaje_utilidad 	:= OLD.porcentaje_utilidad; 	
	old_protocolo_internacion 	:= OLD.protocolo_internacion; 	
	old_sw_rips_con_cargo_cups 	:= OLD.sw_rips_con_cargo_cups; 	
	old_marca_prioridad_atencion 	:= OLD.marca_prioridad_atencion; 	
	old_sw_solicita_autorizacion_admision 	:= OLD.sw_solicita_autorizacion_admision; 	
	old_porcentaje_descuento_iym 	:= OLD.porcentaje_descuento_iym; 	
	old_mensaje_plan 	:= OLD.mensaje_plan; 	
	old_sw_afiliados 	:= OLD.sw_afiliados; 	
	old_programas_id 	:= OLD.programas_id; 	
	old_sw_desc_nomina 	:= OLD.sw_desc_nomina; 	
	old_programa_consulta_externa_id 	:= OLD.programa_consulta_externa_id; 	
	old_sw_multimedicas := OLD.sw_multimedicas;
	
	usuario_registro_ := OLD.usuario_id;
	fecha_registro := NOW();
  END IF; 
  
  IF TG_OP = 'UPDATE' THEN
	new_plan_id 	:= NEW.plan_id; 	
	new_empresa_id 	:= NEW.empresa_id; 	
	new_tipo_tercero_id 	:= NEW.tipo_tercero_id; 	
	new_tercero_id 	:= NEW.tercero_id; 	
	new_plan_descripcion 	:= NEW.plan_descripcion; 	
	new_tipo_cliente 	:= NEW.tipo_cliente; 	
	new_num_contrato 	:= NEW.num_contrato; 	
	new_fecha_inicio 	:= NEW.fecha_inicio; 	
	new_fecha_final 	:= NEW.fecha_final; 	
	new_monto_contrato 	:= NEW.monto_contrato; 	
	new_saldo_contrato 	:= NEW.saldo_contrato; 	
	new_tope_maximo_factura 	:= NEW.tope_maximo_factura; 	
	new_sw_autoriza_sin_bd 	:= NEW.sw_autoriza_sin_bd; 	
	new_sw_afiliacion 	:= NEW.sw_afiliacion; 	
	new_sw_tipo_plan 	:= NEW.sw_tipo_plan; 	
	new_fecha_registro 	:= NEW.fecha_registro; 	
	new_sw_paragrafados_cd 	:= NEW.sw_paragrafados_cd;	
	new_sw_paragrafados_imd 	:= NEW.sw_paragrafados_imd; 	
	new_usuario_id 	:= NEW.usuario_id; 	
	new_estado 	:= NEW.estado; 	
	new_sw_facturacion_agrupada 	:= NEW.sw_facturacion_agrupada; 	
	new_servicios_contratados 	:= NEW.servicios_contratados; 	
	new_protocolos 	:= NEW.protocolos; 	
	new_contacto 	:= NEW.contacto; 	
	new_nombre_cuota_moderadora 	:= NEW.nombre_cuota_moderadora; 	
	new_nombre_copago 	:= NEW.nombre_copago; 	
	new_lineas_atencion 	:= NEW.lineas_atencion; 	
	new_monto_contrato_mensual 	:= NEW.monto_contrato_mensual; 	
	new_sw_base_liquidacion_imd 	:= NEW.sw_base_liquidacion_imd; 	
	new_sw_exceder_monto_mensual 	:= NEW.sw_exceder_monto_mensual; 	
	new_tipo_liquidacion_id 	:= NEW.tipo_liquidacion_id; 	
	new_actividad_incumplimientos 	:= NEW.actividad_incumplimientos; 	
	new_meses_consulta_base_datos 	:= NEW.meses_consulta_base_datos; 	
	new_telefono_cancelacion_cita 	:= NEW.telefono_cancelacion_cita; 	
	new_horas_cancelacion 	:= NEW.horas_cancelacion; 	
	new_observacion 	:= NEW.observacion; 	
	new_tipo_liquidacion_cargo 	:= NEW.tipo_liquidacion_cargo; 	
	new_tipo_para_imd 	:= NEW.tipo_para_imd; 	
	new_dias_credito_cartera 	:= NEW.dias_credito_cartera; 	
	new_sw_contrata_hospitalizacion 	:= NEW.sw_contrata_hospitalizacion; 	
	new_lista_precios 	:= NEW.lista_precios;	
	new_porcentaje_utilidad 	:= NEW.porcentaje_utilidad; 	
	new_protocolo_internacion 	:= NEW.protocolo_internacion; 	
	new_sw_rips_con_cargo_cups 	:= NEW.sw_rips_con_cargo_cups; 	
	new_marca_prioridad_atencion 	:= NEW.marca_prioridad_atencion; 	
	new_sw_solicita_autorizacion_admision 	:= NEW.sw_solicita_autorizacion_admision; 	
	new_porcentaje_descuento_iym 	:= NEW.porcentaje_descuento_iym; 	
	new_mensaje_plan 	:= NEW.mensaje_plan; 	
	new_sw_afiliados 	:= NEW.sw_afiliados; 	
	new_programas_id 	:= NEW.programas_id; 	
	new_sw_desc_nomina 	:= NEW.sw_desc_nomina; 	
	new_programa_consulta_externa_id 	:= NEW.programa_consulta_externa_id; 	
	new_sw_multimedicas := NEW.sw_multimedicas;
	
	usuario_registro_ := NEW.usuario_id;
	fecha_registro := NEW.fecha_registro;
  
	old_plan_id 	:= OLD.plan_id; 	
	old_empresa_id 	:= OLD.empresa_id; 	
	old_tipo_tercero_id 	:= OLD.tipo_tercero_id; 	
	old_tercero_id 	:= OLD.tercero_id; 	
	old_plan_descripcion 	:= OLD.plan_descripcion; 	
	old_tipo_cliente 	:= OLD.tipo_cliente; 	
	old_num_contrato 	:= OLD.num_contrato; 	
	old_fecha_inicio 	:= OLD.fecha_inicio; 	
	old_fecha_final 	:= OLD.fecha_final; 	
	old_monto_contrato 	:= OLD.monto_contrato; 	
	old_saldo_contrato 	:= OLD.saldo_contrato; 	
	old_tope_maximo_factura 	:= OLD.tope_maximo_factura; 	
	old_sw_autoriza_sin_bd 	:= OLD.sw_autoriza_sin_bd; 	
	old_sw_afiliacion 	:= OLD.sw_afiliacion; 	
	old_sw_tipo_plan 	:= OLD.sw_tipo_plan;	
	old_fecha_registro 	:= OLD.fecha_registro; 	
	old_sw_paragrafados_cd 	:= OLD.sw_paragrafados_cd; 	
	old_sw_paragrafados_imd 	:= OLD.sw_paragrafados_imd; 	
	old_usuario_id 	:= OLD.usuario_id; 	
	old_estado 	:= OLD.estado; 	
	old_sw_facturacion_agrupada 	:= OLD.sw_facturacion_agrupada; 	
	old_servicios_contratados 	:= OLD.servicios_contratados; 	
	old_protocolos 	:= OLD.protocolos; 	
	old_contacto 	:= OLD.contacto; 	
	old_nombre_cuota_moderadora 	:= OLD.nombre_cuota_moderadora; 	
	old_nombre_copago 	:= OLD.nombre_copago; 	
	old_lineas_atencion 	:= OLD.lineas_atencion; 	
	old_monto_contrato_mensual 	:= OLD.monto_contrato_mensual; 	
	old_sw_base_liquidacion_imd 	:= OLD.sw_base_liquidacion_imd; 	
	old_sw_exceder_monto_mensual 	:= OLD.sw_exceder_monto_mensual; 	
	old_tipo_liquidacion_id 	:= OLD.tipo_liquidacion_id; 	
	old_actividad_incumplimientos 	:= OLD.actividad_incumplimientos; 	
	old_meses_consulta_base_datos 	:= OLD.meses_consulta_base_datos; 	
	old_telefono_cancelacion_cita 	:= OLD.telefono_cancelacion_cita; 	
	old_horas_cancelacion 	:= OLD.horas_cancelacion; 	
	old_observacion 	:= OLD.observacion; 	
	old_tipo_liquidacion_cargo 	:= OLD.tipo_liquidacion_cargo; 	
	old_tipo_para_imd 	:= OLD.tipo_para_imd; 	
	old_dias_credito_cartera 	:= OLD.dias_credito_cartera; 	
	old_sw_contrata_hospitalizacion 	:= OLD.sw_contrata_hospitalizacion; 	
	old_lista_precios 	:= OLD.lista_precios; 	
	old_porcentaje_utilidad 	:= OLD.porcentaje_utilidad; 	
	old_protocolo_internacion 	:= OLD.protocolo_internacion; 	
	old_sw_rips_con_cargo_cups 	:= OLD.sw_rips_con_cargo_cups; 	
	old_marca_prioridad_atencion 	:= OLD.marca_prioridad_atencion; 	
	old_sw_solicita_autorizacion_admision 	:= OLD.sw_solicita_autorizacion_admision; 	
	old_porcentaje_descuento_iym 	:= OLD.porcentaje_descuento_iym; 	
	old_mensaje_plan 	:= OLD.mensaje_plan; 	
	old_sw_afiliados 	:= OLD.sw_afiliados; 	
	old_programas_id 	:= OLD.programas_id; 	
	old_sw_desc_nomina 	:= OLD.sw_desc_nomina; 	
	old_programa_consulta_externa_id 	:= OLD.programa_consulta_externa_id; 	
	old_sw_multimedicas := OLD.sw_multimedicas;
  END IF; 
  
  IF TG_OP = 'INSERT' THEN
	new_plan_id 	:= NEW.plan_id;	
	new_empresa_id 	:= NEW.empresa_id;
	new_tipo_tercero_id 	:= NEW.tipo_tercero_id; 	
	new_tercero_id 	:= NEW.tercero_id; 	
	new_plan_descripcion 	:= NEW.plan_descripcion; 	
	new_tipo_cliente 	:= NEW.tipo_cliente; 	
	new_num_contrato 	:= NEW.num_contrato; 	
	new_fecha_inicio 	:= NEW.fecha_inicio; 	
	new_fecha_final 	:= NEW.fecha_final; 	
	new_monto_contrato 	:= NEW.monto_contrato; 	
	new_saldo_contrato 	:= NEW.saldo_contrato; 	
	new_tope_maximo_factura 	:= NEW.tope_maximo_factura; 	
	new_sw_autoriza_sin_bd 	:= NEW.sw_autoriza_sin_bd; 	
	new_sw_afiliacion 	:= NEW.sw_afiliacion; 	
	new_sw_tipo_plan 	:= NEW.sw_tipo_plan; 	
	new_fecha_registro 	:= NEW.fecha_registro; 	
	new_sw_paragrafados_cd 	:= NEW.sw_paragrafados_cd; 	
	new_sw_paragrafados_imd 	:= NEW.sw_paragrafados_imd; 	
	new_usuario_id 	:= NEW.usuario_id; 	
	new_estado 	:= NEW.estado; 	
	new_sw_facturacion_agrupada 	:= NEW.sw_facturacion_agrupada; 	
	new_servicios_contratados 	:= NEW.servicios_contratados; 	
	new_protocolos 	:= NEW.protocolos; 	
	new_contacto 	:= NEW.contacto; 	
	new_nombre_cuota_moderadora 	:= NEW.nombre_cuota_moderadora; 	
	new_nombre_copago 	:= NEW.nombre_copago; 	
	new_lineas_atencion 	:= NEW.lineas_atencion;	
	new_monto_contrato_mensual 	:= NEW.monto_contrato_mensual; 	
	new_sw_base_liquidacion_imd 	:= NEW.sw_base_liquidacion_imd; 	
	new_sw_exceder_monto_mensual 	:= NEW.sw_exceder_monto_mensual; 	
	new_tipo_liquidacion_id 	:= NEW.tipo_liquidacion_id; 	
	new_actividad_incumplimientos 	:= NEW.actividad_incumplimientos; 	
	new_meses_consulta_base_datos 	:= NEW.meses_consulta_base_datos;
	new_telefono_cancelacion_cita 	:= NEW.telefono_cancelacion_cita; 	
	new_horas_cancelacion 	:= NEW.horas_cancelacion; 	
	new_observacion 	:= NEW.observacion; 	
	new_tipo_liquidacion_cargo 	:= NEW.tipo_liquidacion_cargo; 	
	new_tipo_para_imd 	:= NEW.tipo_para_imd; 	
	new_dias_credito_cartera 	:= NEW.dias_credito_cartera; 	
	new_sw_contrata_hospitalizacion 	:= NEW.sw_contrata_hospitalizacion; 	
	new_lista_precios 	:= NEW.lista_precios; 	
	new_porcentaje_utilidad 	:= NEW.porcentaje_utilidad; 	
	new_protocolo_internacion 	:= NEW.protocolo_internacion; 	
	new_sw_rips_con_cargo_cups 	:= NEW.sw_rips_con_cargo_cups; 	
	new_marca_prioridad_atencion 	:= NEW.marca_prioridad_atencion; 	
	new_sw_solicita_autorizacion_admision 	:= NEW.sw_solicita_autorizacion_admision; 	
	new_porcentaje_descuento_iym 	:= NEW.porcentaje_descuento_iym; 	
	new_mensaje_plan 	:= NEW.mensaje_plan; 	
	new_sw_afiliados 	:= NEW.sw_afiliados; 	
	new_programas_id 	:= NEW.programas_id; 	
	new_sw_desc_nomina 	:= NEW.sw_desc_nomina; 	
	new_programa_consulta_externa_id 	:= NEW.programa_consulta_externa_id; 	
	new_sw_multimedicas := NEW.sw_multimedicas;
	
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

      campo_27, 
      nuevo_valor_27,
      antiguo_valor_27,

      campo_28, 
      nuevo_valor_28,
      antiguo_valor_28,

      campo_29, 
      nuevo_valor_29,
      antiguo_valor_29,

      campo_30, 
      nuevo_valor_30,
      antiguo_valor_30,

      campo_31, 
      nuevo_valor_31,
      antiguo_valor_31,
      
      campo_32, 
      nuevo_valor_32,
      antiguo_valor_32,

      campo_33, 
      nuevo_valor_33,
      antiguo_valor_33,

      campo_34, 
      nuevo_valor_34,
      antiguo_valor_34,

      campo_35, 
      nuevo_valor_35,
      antiguo_valor_35,

      campo_36, 
      nuevo_valor_36,
      antiguo_valor_36,

      campo_37, 
      nuevo_valor_37,
      antiguo_valor_37,

      campo_38, 
      nuevo_valor_38,
      antiguo_valor_38,

      campo_39, 
      nuevo_valor_39,
      antiguo_valor_39,

      campo_40, 
      nuevo_valor_40,
      antiguo_valor_40,

      campo_41, 
      nuevo_valor_41,
      antiguo_valor_41,

      campo_42, 
      nuevo_valor_42,
      antiguo_valor_42,

      campo_43, 
      nuevo_valor_43,
      antiguo_valor_43,

      campo_44, 
      nuevo_valor_44,
      antiguo_valor_44,

      campo_45, 
      nuevo_valor_45,
      antiguo_valor_45,

      campo_46, 
      nuevo_valor_46,
      antiguo_valor_46,

      campo_47, 
      nuevo_valor_47,
      antiguo_valor_47,

      campo_48, 
      nuevo_valor_48,
      antiguo_valor_48,

      campo_49, 
      nuevo_valor_49,
      antiguo_valor_49,

      campo_50, 
      nuevo_valor_50,
      antiguo_valor_50,

      campo_51, 
      nuevo_valor_51,
      antiguo_valor_51,

      campo_52, 
      nuevo_valor_52,
      antiguo_valor_52,

      campo_53, 
      nuevo_valor_53,
      antiguo_valor_53,

     usuario_registro

    )
    VALUES
    (
      indicador.accion_id,
      indicador.novedad_indicador_id,
      'planes',
      
      'plan_id',
      new_plan_id,
      old_plan_id,
      
      'empresa_id',    
      new_empresa_id,
      old_empresa_id,
      
      'tipo_tercero_id',
      new_tipo_tercero_id,
      old_tipo_tercero_id,
      
      'tercero_id',
      new_tercero_id,
      old_tercero_id,
      
      'plan_descripcion',
      new_plan_descripcion,
      old_plan_descripcion,
      
      'tipo_cliente',
      new_tipo_cliente,
      old_tipo_cliente,
      
      'num_contrato',
      new_num_contrato,
      old_num_contrato,
      
      'fecha_inicio',
      new_fecha_inicio,
      old_fecha_inicio,

      'fecha_final',
      new_fecha_final,
      old_fecha_final,

      'monto_contrato',
      new_monto_contrato,
      old_monto_contrato,

      'saldo_contrato',
      new_saldo_contrato,
      old_saldo_contrato,

      'tope_maximo_factura',
      new_tope_maximo_factura,
      old_tope_maximo_factura,

      'sw_autoriza_sin_bd',
      new_sw_autoriza_sin_bd,
      old_sw_autoriza_sin_bd,

      'sw_afiliacion',
      new_sw_afiliacion,
      old_sw_afiliacion,

      'sw_tipo_plan',
      new_sw_tipo_plan,
      old_sw_tipo_plan,

      'fecha_registro',
      new_fecha_registro,
      old_fecha_registro,

      'sw_paragrafados_cd',
      new_sw_paragrafados_cd,
      old_sw_paragrafados_cd,

      'sw_paragrafados_imd',
      new_sw_paragrafados_imd,
      old_sw_paragrafados_imd,

      'usuario_id',
      new_usuario_id,
      old_usuario_id,

      'estado',
      new_estado,
      old_estado,

      'sw_facturacion_agrupada',
      new_sw_facturacion_agrupada,
      old_sw_facturacion_agrupada,

      'servicios_contratados',
      new_servicios_contratados,
      old_servicios_contratados,

      'protocolos',
      new_protocolos,
      old_protocolos,

      'contacto',
      new_contacto,
      old_contacto,

      'nombre_cuota_moderadora',
      new_nombre_cuota_moderadora,
      old_nombre_cuota_moderadora,

      'nombre_copago',
      new_nombre_copago,
      old_nombre_copago,

      'lineas_atencion',
      new_lineas_atencion,
      old_lineas_atencion,

      'monto_contrato_mensual',
      new_monto_contrato_mensual,
      old_monto_contrato_mensual,

      'sw_base_liquidacion_imd',
      new_sw_base_liquidacion_imd,
      old_sw_base_liquidacion_imd,
      
      'sw_exceder_monto_mensual',
      new_sw_exceder_monto_mensual,
      old_sw_exceder_monto_mensual,
      
      'tipo_liquidacion_id',
      new_tipo_liquidacion_id,
      old_tipo_liquidacion_id,
      
      'actividad_incumplimientos',
      new_actividad_incumplimientos,
      old_actividad_incumplimientos,
      
      'meses_consulta_base_datos',
      new_meses_consulta_base_datos,
      old_meses_consulta_base_datos,
      
      'telefono_cancelacion_cita',
      new_telefono_cancelacion_cita,
      old_telefono_cancelacion_cita,
      
      'horas_cancelacion',
      new_horas_cancelacion,
      old_horas_cancelacion,
	  
      'observacion',
      new_observacion,
      old_observacion,
	  
      'tipo_liquidacion_cargo',
      new_tipo_liquidacion_cargo,
      old_tipo_liquidacion_cargo,
	  
      'tipo_para_imd',
      new_tipo_para_imd,
      old_tipo_para_imd,
	  
      'dias_credito_cartera',
      new_dias_credito_cartera,
      old_dias_credito_cartera,
	  
      'sw_contrata_hospitalizacion',
      new_sw_contrata_hospitalizacion,
      old_sw_contrata_hospitalizacion,
	  
      'lista_precios',
      new_lista_precios,
      old_lista_precios,
	  
      'porcentaje_utilidad',
      new_porcentaje_utilidad,
      old_porcentaje_utilidad,
	  
      'protocolo_internacion',
      new_protocolo_internacion,
      old_protocolo_internacion,
	  
      'sw_rips_con_cargo_cups',
      new_sw_rips_con_cargo_cups,
      old_sw_rips_con_cargo_cups,
	  
      'marca_prioridad_atencion',
      new_marca_prioridad_atencion,
      old_marca_prioridad_atencion,
	  
      'sw_solicita_autorizacion_admision',
      new_sw_solicita_autorizacion_admision,
      old_sw_solicita_autorizacion_admision,
	  
      'porcentaje_descuento_iym',
      new_porcentaje_descuento_iym,
      old_porcentaje_descuento_iym,
	  
      'mensaje_plan',
      new_mensaje_plan,
      old_mensaje_plan,
	  
      'sw_afiliados',
      new_sw_afiliados,
      old_sw_afiliados,
	  
      'programas_id',
      new_programas_id,
      old_programas_id,
	  
      'sw_desc_nomina',
      new_sw_desc_nomina,
      old_sw_desc_nomina,
	  
      'programa_consulta_externa_id',
      new_programa_consulta_externa_id,
      old_programa_consulta_externa_id,
	  
      'sw_multimedicas',
      new_sw_multimedicas,
      old_sw_multimedicas,
	        
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

ALTER FUNCTION public.au_contratacion()
  OWNER TO "admin";
  
  CREATE TRIGGER aut_auditoria_planes
  AFTER INSERT OR UPDATE OR DELETE
  ON public.planes
  FOR EACH ROW
  EXECUTE PROCEDURE au_contratacion();

  /*
  *CUENTAS
  */
  CREATE OR REPLACE FUNCTION public.au_cuentas()
RETURNS trigger AS
$$
DECLARE

  indicador RECORD;
  columnas RECORD;
  
	old_empresa_id 	character(2);
	old_centro_utilidad character(2);	
	old_numerodecuenta integer; 	
	old_ingreso integer;	
	old_plan_id integer;	
	old_fecha_cierre timestamp;	
	old_total_cuenta numeric(12,2);	
	old_abono_efectivo numeric(12,2);	
	old_abono_cheque numeric(12,2);	
	old_abono_tarjetas numeric(12,2);	
	old_abono_chequespf numeric(12,2);	
	old_abono_letras numeric(12,2);	
	old_gravamen_valor_cubierto numeric(12,2);	
	old_valor_cuota_paciente numeric(12,2);	
	old_valor_nocubierto numeric(12,2);	
	old_valor_cubierto 	numeric(12,2);	
	old_porcentaje_descuento_empresa numeric(7,4);	
	old_estado character(1);	
	old_usuario_id integer;	
	old_fecha_registro timestamp;	
	old_gravamen_valor_nocubierto numeric(12,2);	
	old_valor_descuento_empresa numeric(12,2);	
	old_valor_descuento_paciente numeric(12,2);	
	old_valor_cuota_moderadora numeric(12,2);	
	old_tipo_afiliado_id character varying(2);	
	old_porcentaje_descuento_paciente numeric(7,4);	
	old_semanas_cotizadas smallint;	
	old_abono_bonos 	numeric(12,2);	
	old_autorizacion_int integer;	
	old_autorizacion_ext integer;	
	old_sw_estado_paciente character(1);
	old_usuario_cierre integer;	
	old_valor_total_paciente numeric(12,2);	
	old_valor_total_empresa numeric(12,2);	
	old_valor_descuento_cuota_paciente numeric(12,2);	
	old_valor_descuento_cuota_moderadora 	numeric(12,2);	
	old_valor_total_cargos numeric(12,2);	
	old_rango character varying(40);	
	old_sw_liquidacion_manual_habitaciones character(1);	
	old_sw_corte character(1);	
	old_departamento character varying(6);
  
	new_empresa_id 	character(2);
	new_centro_utilidad character(2);	
	new_numerodecuenta integer; 	
	new_ingreso integer;	
	new_plan_id integer;	
	new_fecha_cierre timestamp;	
	new_total_cuenta numeric(12,2);	
	new_abono_efectivo numeric(12,2);	
	new_abono_cheque numeric(12,2);	
	new_abono_tarjetas numeric(12,2);	
	new_abono_chequespf numeric(12,2);	
	new_abono_letras numeric(12,2);	
	new_gravamen_valor_cubierto numeric(12,2);	
	new_valor_cuota_paciente numeric(12,2);	
	new_valor_nocubierto numeric(12,2);	
	new_valor_cubierto 	numeric(12,2);	
	new_porcentaje_descuento_empresa numeric(7,4);	
	new_estado character(1);	
	new_usuario_id integer;	
	new_fecha_registro timestamp;	
	new_gravamen_valor_nocubierto numeric(12,2);	
	new_valor_descuento_empresa numeric(12,2);	
	new_valor_descuento_paciente numeric(12,2);	
	new_valor_cuota_moderadora numeric(12,2);	
	new_tipo_afiliado_id character varying(2);	
	new_porcentaje_descuento_paciente numeric(7,4);	
	new_semanas_cotizadas smallint;	
	new_abono_bonos 	numeric(12,2);	
	new_autorizacion_int integer;	
	new_autorizacion_ext integer;	
	new_sw_estado_paciente character(1);
	new_usuario_cierre integer;	
	new_valor_total_paciente numeric(12,2);	
	new_valor_total_empresa numeric(12,2);	
	new_valor_descuento_cuota_paciente numeric(12,2);	
	new_valor_descuento_cuota_moderadora 	numeric(12,2);	
	new_valor_total_cargos numeric(12,2);	
	new_rango character varying(40);	
	new_sw_liquidacion_manual_habitaciones character(1);	
	new_sw_corte character(1);	
	new_departamento character varying(6);
	
	usuario_registro_ integer;
	fecha_registro timestamp;
  
BEGIN
  SELECT * INTO indicador
  FROM   acciones AC,
         novedad_x_acciones NA
  WHERE  AC.descripcion = TG_OP
  AND    NA.accion_id = AC.accion_id
  AND    NA.table_name = 'cuentas';
 
 /* Inicializar Variables */
 
  IF TG_OP = 'DELETE' THEN
   
	old_empresa_id 	:= OLD.empresa_id;
	old_centro_utilidad 	:= OLD.centro_utilidad;	
	old_numerodecuenta 	:= OLD.numerodecuenta; 	
	old_ingreso 	:= OLD.ingreso; 	
	old_plan_id 	:= OLD.plan_id; 	
	old_fecha_cierre 	:= OLD.fecha_cierre; 	
	old_total_cuenta 	:= OLD.total_cuenta; 	
	old_abono_efectivo 	:= OLD.abono_efectivo; 	
	old_abono_cheque 	:= OLD.abono_cheque; 	
	old_abono_tarjetas 	:= OLD.abono_tarjetas; 	
	old_abono_chequespf := OLD.abono_chequespf; 	
	old_abono_letras 	:= OLD.abono_letras; 	
	old_gravamen_valor_cubierto 	:= OLD.gravamen_valor_cubierto; 	
	old_valor_cuota_paciente 		:= OLD.valor_cuota_paciente; 	
	old_valor_nocubierto 	:= OLD.valor_nocubierto; 	
	old_valor_cubierto 	:= OLD.valor_cubierto; 	
	old_porcentaje_descuento_empresa 	:= OLD.porcentaje_descuento_empresa; 	
	old_estado 	:= OLD.estado; 	
	old_usuario_id := OLD.usuario_id; 	
	old_fecha_registro 	:= OLD.fecha_registro; 	
	old_gravamen_valor_nocubierto 	:= OLD.gravamen_valor_nocubierto; 	
	old_valor_descuento_empresa 	:= OLD.valor_descuento_empresa; 	
	old_valor_descuento_paciente 	:= OLD.valor_descuento_paciente; 	
	old_valor_cuota_moderadora 	:= OLD.valor_cuota_moderadora; 	
	old_tipo_afiliado_id 	:= OLD.tipo_afiliado_id; 	
	old_porcentaje_descuento_paciente 	:= OLD.porcentaje_descuento_paciente; 	
	old_semanas_cotizadas 	:= OLD.semanas_cotizadas; 	
	old_abono_bonos 	:= OLD.abono_bonos; 	
	old_autorizacion_int 	:= OLD.autorizacion_int; 	
	old_autorizacion_ext 	:= OLD.autorizacion_ext; 	
	old_sw_estado_paciente 	:= OLD.sw_estado_paciente; 	
	old_usuario_cierre 	:= OLD.usuario_cierre; 	
	old_valor_total_paciente 	:= OLD.valor_total_paciente; 	
	old_valor_total_empresa 	:= OLD.valor_total_empresa; 	
	old_valor_descuento_cuota_paciente 	:= OLD.valor_descuento_cuota_paciente; 	
	old_valor_descuento_cuota_moderadora 	:= OLD.valor_descuento_cuota_moderadora; 	
	old_valor_total_cargos 	:= OLD.valor_total_cargos; 	
	old_rango 	:= OLD.rango; 	
	old_sw_liquidacion_manual_habitaciones 	:= OLD.sw_liquidacion_manual_habitaciones; 	
	old_sw_corte 	:= OLD.sw_corte; 	
	old_departamento := OLD.departamento;
	
	usuario_registro_ := OLD.usuario_id;
	fecha_registro := NOW();
  END IF; 
  
  IF TG_OP = 'UPDATE' THEN
	new_empresa_id 	:= NEW.empresa_id;
	new_centro_utilidad 	:= NEW.centro_utilidad;	
	new_numerodecuenta 	:= NEW.numerodecuenta; 	
	new_ingreso 	:= NEW.ingreso; 	
	new_plan_id 	:= NEW.plan_id; 	
	new_fecha_cierre 	:= NEW.fecha_cierre; 	
	new_total_cuenta 	:= NEW.total_cuenta; 	
	new_abono_efectivo 	:= NEW.abono_efectivo; 	
	new_abono_cheque 	:= NEW.abono_cheque; 	
	new_abono_tarjetas 	:= NEW.abono_tarjetas; 	
	new_abono_chequespf := NEW.abono_chequespf; 	
	new_abono_letras 	:= NEW.abono_letras; 	
	new_gravamen_valor_cubierto 	:= NEW.gravamen_valor_cubierto; 	
	new_valor_cuota_paciente 		:= NEW.valor_cuota_paciente; 	
	new_valor_nocubierto 	:= NEW.valor_nocubierto; 	
	new_valor_cubierto 	:= NEW.valor_cubierto; 	
	new_porcentaje_descuento_empresa 	:= NEW.porcentaje_descuento_empresa; 	
	new_estado 	:= NEW.estado; 	
	new_usuario_id := NEW.usuario_id; 	
	new_fecha_registro 	:= NEW.fecha_registro; 	
	new_gravamen_valor_nocubierto 	:= NEW.gravamen_valor_nocubierto; 	
	new_valor_descuento_empresa 	:= NEW.valor_descuento_empresa; 	
	new_valor_descuento_paciente 	:= NEW.valor_descuento_paciente; 	
	new_valor_cuota_moderadora 	:= NEW.valor_cuota_moderadora; 	
	new_tipo_afiliado_id 	:= NEW.tipo_afiliado_id; 	
	new_porcentaje_descuento_paciente 	:= NEW.porcentaje_descuento_paciente; 	
	new_semanas_cotizadas 	:= NEW.semanas_cotizadas; 	
	new_abono_bonos 	:= NEW.abono_bonos; 	
	new_autorizacion_int 	:= NEW.autorizacion_int; 	
	new_autorizacion_ext 	:= NEW.autorizacion_ext; 	
	new_sw_estado_paciente 	:= NEW.sw_estado_paciente; 	
	new_usuario_cierre 	:= NEW.usuario_cierre; 	
	new_valor_total_paciente 	:= NEW.valor_total_paciente; 	
	new_valor_total_empresa 	:= NEW.valor_total_empresa; 	
	new_valor_descuento_cuota_paciente 	:= NEW.valor_descuento_cuota_paciente; 	
	new_valor_descuento_cuota_moderadora 	:= NEW.valor_descuento_cuota_moderadora; 	
	new_valor_total_cargos 	:= NEW.valor_total_cargos; 	
	new_rango 	:= NEW.rango; 	
	new_sw_liquidacion_manual_habitaciones 	:= NEW.sw_liquidacion_manual_habitaciones; 	
	new_sw_corte 	:= NEW.sw_corte; 	
	new_departamento := NEW.departamento;
	
	usuario_registro_ := NEW.usuario_id;
	fecha_registro := NOW();
  
	old_empresa_id 	:= OLD.empresa_id;
	old_centro_utilidad 	:= OLD.centro_utilidad;	
	old_numerodecuenta 	:= OLD.numerodecuenta; 	
	old_ingreso 	:= OLD.ingreso; 	
	old_plan_id 	:= OLD.plan_id; 	
	old_fecha_cierre 	:= OLD.fecha_cierre; 	
	old_total_cuenta 	:= OLD.total_cuenta; 	
	old_abono_efectivo 	:= OLD.abono_efectivo; 	
	old_abono_cheque 	:= OLD.abono_cheque; 	
	old_abono_tarjetas 	:= OLD.abono_tarjetas; 	
	old_abono_chequespf := OLD.abono_chequespf; 	
	old_abono_letras 	:= OLD.abono_letras; 	
	old_gravamen_valor_cubierto 	:= OLD.gravamen_valor_cubierto; 	
	old_valor_cuota_paciente 		:= OLD.valor_cuota_paciente; 	
	old_valor_nocubierto 	:= OLD.valor_nocubierto; 	
	old_valor_cubierto 	:= OLD.valor_cubierto; 	
	old_porcentaje_descuento_empresa 	:= OLD.porcentaje_descuento_empresa; 	
	old_estado 	:= OLD.estado; 	
	old_usuario_id := OLD.usuario_id; 	
	old_fecha_registro 	:= OLD.fecha_registro; 	
	old_gravamen_valor_nocubierto 	:= OLD.gravamen_valor_nocubierto; 	
	old_valor_descuento_empresa 	:= OLD.valor_descuento_empresa; 	
	old_valor_descuento_paciente 	:= OLD.valor_descuento_paciente; 	
	old_valor_cuota_moderadora 	:= OLD.valor_cuota_moderadora; 	
	old_tipo_afiliado_id 	:= OLD.tipo_afiliado_id; 	
	old_porcentaje_descuento_paciente 	:= OLD.porcentaje_descuento_paciente; 	
	old_semanas_cotizadas 	:= OLD.semanas_cotizadas; 	
	old_abono_bonos 	:= OLD.abono_bonos; 	
	old_autorizacion_int 	:= OLD.autorizacion_int; 	
	old_autorizacion_ext 	:= OLD.autorizacion_ext; 	
	old_sw_estado_paciente 	:= OLD.sw_estado_paciente; 	
	old_usuario_cierre 	:= OLD.usuario_cierre; 	
	old_valor_total_paciente 	:= OLD.valor_total_paciente; 	
	old_valor_total_empresa 	:= OLD.valor_total_empresa; 	
	old_valor_descuento_cuota_paciente 	:= OLD.valor_descuento_cuota_paciente; 	
	old_valor_descuento_cuota_moderadora 	:= OLD.valor_descuento_cuota_moderadora; 	
	old_valor_total_cargos 	:= OLD.valor_total_cargos; 	
	old_rango 	:= OLD.rango; 	
	old_sw_liquidacion_manual_habitaciones 	:= OLD.sw_liquidacion_manual_habitaciones; 	
	old_sw_corte 	:= OLD.sw_corte; 	
	old_departamento := OLD.departamento;
  END IF; 
  
  IF TG_OP = 'INSERT' THEN
	new_empresa_id 	:= NEW.empresa_id;
	new_centro_utilidad 	:= NEW.centro_utilidad;	
	new_numerodecuenta 	:= NEW.numerodecuenta; 	
	new_ingreso 	:= NEW.ingreso; 	
	new_plan_id 	:= NEW.plan_id; 	
	new_fecha_cierre 	:= NEW.fecha_cierre; 	
	new_total_cuenta 	:= NEW.total_cuenta; 	
	new_abono_efectivo 	:= NEW.abono_efectivo; 	
	new_abono_cheque 	:= NEW.abono_cheque; 	
	new_abono_tarjetas 	:= NEW.abono_tarjetas; 	
	new_abono_chequespf := NEW.abono_chequespf; 	
	new_abono_letras 	:= NEW.abono_letras; 	
	new_gravamen_valor_cubierto 	:= NEW.gravamen_valor_cubierto; 	
	new_valor_cuota_paciente 		:= NEW.valor_cuota_paciente; 	
	new_valor_nocubierto 	:= NEW.valor_nocubierto; 	
	new_valor_cubierto 	:= NEW.valor_cubierto; 	
	new_porcentaje_descuento_empresa 	:= NEW.porcentaje_descuento_empresa; 	
	new_estado 	:= NEW.estado; 	
	new_usuario_id := NEW.usuario_id; 	
	new_fecha_registro 	:= NEW.fecha_registro; 	
	new_gravamen_valor_nocubierto 	:= NEW.gravamen_valor_nocubierto; 	
	new_valor_descuento_empresa 	:= NEW.valor_descuento_empresa; 	
	new_valor_descuento_paciente 	:= NEW.valor_descuento_paciente; 	
	new_valor_cuota_moderadora 	:= NEW.valor_cuota_moderadora; 	
	new_tipo_afiliado_id 	:= NEW.tipo_afiliado_id; 	
	new_porcentaje_descuento_paciente 	:= NEW.porcentaje_descuento_paciente; 	
	new_semanas_cotizadas 	:= NEW.semanas_cotizadas; 	
	new_abono_bonos 	:= NEW.abono_bonos; 	
	new_autorizacion_int 	:= NEW.autorizacion_int; 	
	new_autorizacion_ext 	:= NEW.autorizacion_ext; 	
	new_sw_estado_paciente 	:= NEW.sw_estado_paciente; 	
	new_usuario_cierre 	:= NEW.usuario_cierre; 	
	new_valor_total_paciente 	:= NEW.valor_total_paciente; 	
	new_valor_total_empresa 	:= NEW.valor_total_empresa; 	
	new_valor_descuento_cuota_paciente 	:= NEW.valor_descuento_cuota_paciente; 	
	new_valor_descuento_cuota_moderadora 	:= NEW.valor_descuento_cuota_moderadora; 	
	new_valor_total_cargos 	:= NEW.valor_total_cargos; 	
	new_rango 	:= NEW.rango; 	
	new_sw_liquidacion_manual_habitaciones 	:= NEW.sw_liquidacion_manual_habitaciones; 	
	new_sw_corte 	:= NEW.sw_corte; 	
	new_departamento := NEW.departamento;
	
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

      campo_27, 
      nuevo_valor_27,
      antiguo_valor_27,

      campo_28, 
      nuevo_valor_28,
      antiguo_valor_28,

      campo_29, 
      nuevo_valor_29,
      antiguo_valor_29,

      campo_30, 
      nuevo_valor_30,
      antiguo_valor_30,

      campo_31, 
      nuevo_valor_31,
      antiguo_valor_31,
      
      campo_32, 
      nuevo_valor_32,
      antiguo_valor_32,

      campo_33, 
      nuevo_valor_33,
      antiguo_valor_33,

      campo_34, 
      nuevo_valor_34,
      antiguo_valor_34,

      campo_35, 
      nuevo_valor_35,
      antiguo_valor_35,

      campo_36, 
      nuevo_valor_36,
      antiguo_valor_36,

      campo_37, 
      nuevo_valor_37,
      antiguo_valor_37,

      campo_38, 
      nuevo_valor_38,
      antiguo_valor_38,

      campo_39, 
      nuevo_valor_39,
      antiguo_valor_39,

      campo_40, 
      nuevo_valor_40,
      antiguo_valor_40,

      campo_41, 
      nuevo_valor_41,
      antiguo_valor_41,

     usuario_registro

    )
    VALUES
    (
      indicador.accion_id,
      indicador.novedad_indicador_id,
      'cuentas',
      
      'empresa_id',
      new_empresa_id,
      old_empresa_id,
      
      'centro_utilidad',    
      new_centro_utilidad,
      old_centro_utilidad,
      
      'numerodecuenta',
      new_numerodecuenta,
      old_numerodecuenta,
      
      'ingreso',
      new_ingreso,
      old_ingreso,
      
      'plan_id',
      new_plan_id,
      old_plan_id,
      
      'fecha_cierre',
      new_fecha_cierre,
      old_fecha_cierre,
      
      'total_cuenta',
      new_total_cuenta,
      old_total_cuenta,
      
      'abono_efectivo',
      new_abono_efectivo,
      old_abono_efectivo,

      'abono_cheque',
      new_abono_cheque,
      old_abono_cheque,

      'abono_tarjetas',
      new_abono_tarjetas,
      old_abono_tarjetas,

      'abono_chequespf',
      new_abono_chequespf,
      old_abono_chequespf,

      'abono_letras',
      new_abono_letras,
      old_abono_letras,

      'gravamen_valor_cubierto',
      new_gravamen_valor_cubierto,
      old_gravamen_valor_cubierto,

      'valor_cuota_paciente',
      new_valor_cuota_paciente,
      old_valor_cuota_paciente,

      'valor_nocubierto',
      new_valor_nocubierto,
      old_valor_nocubierto,

      'valor_cubierto',
      new_valor_cubierto,
      old_valor_cubierto,

      'porcentaje_descuento_empresa',
      new_porcentaje_descuento_empresa,
      old_porcentaje_descuento_empresa,

      'estado',
      new_estado,
      old_estado,

      'usuario_id',
      new_usuario_id,
      old_usuario_id,

      'fecha_registro',
      new_fecha_registro,
      old_fecha_registro,

      'gravamen_valor_nocubierto',
      new_gravamen_valor_nocubierto,
      old_gravamen_valor_nocubierto,

      'valor_descuento_empresa',
      new_valor_descuento_empresa,
      old_valor_descuento_empresa,

      'valor_descuento_paciente',
      new_valor_descuento_paciente,
      old_valor_descuento_paciente,

      'valor_cuota_moderadora',
      new_valor_cuota_moderadora,
      old_valor_cuota_moderadora,

      'tipo_afiliado_id',
      new_tipo_afiliado_id,
      old_tipo_afiliado_id,

      'porcentaje_descuento_paciente',
      new_porcentaje_descuento_paciente,
      old_porcentaje_descuento_paciente,

      'semanas_cotizadas',
      new_semanas_cotizadas,
      old_semanas_cotizadas,

      'abono_bonos',
      new_abono_bonos,
      old_abono_bonos,

      'autorizacion_int',
      new_autorizacion_int,
      old_autorizacion_int,
      
      'autorizacion_ext',
      new_autorizacion_ext,
      old_autorizacion_ext,
      
      'sw_estado_paciente',
      new_sw_estado_paciente,
      old_sw_estado_paciente,
      
      'usuario_cierre',
      new_usuario_cierre,
      old_usuario_cierre,
      
      'valor_total_paciente',
      new_valor_total_paciente,
      old_valor_total_paciente,
      
      'valor_total_empresa',
      new_valor_total_empresa,
      old_valor_total_empresa,
      
      'valor_descuento_cuota_paciente',
      new_valor_descuento_cuota_paciente,
      old_valor_descuento_cuota_paciente,
	  
      'valor_descuento_cuota_moderadora',
      new_valor_descuento_cuota_moderadora,
      old_valor_descuento_cuota_moderadora,
	  
      'valor_total_cargos',
      new_valor_total_cargos,
      old_valor_total_cargos,
	  
      'rango',
      new_rango,
      old_rango,
	  
      'sw_liquidacion_manual_habitaciones',
      new_sw_liquidacion_manual_habitaciones,
      old_sw_liquidacion_manual_habitaciones,
	  
      'sw_corte',
      new_sw_corte,
      old_sw_corte,
	  
      'departamento',
      new_departamento,
      old_departamento,
	  
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

ALTER FUNCTION public.au_cuentas()
  OWNER TO "admin";
  
  CREATE TRIGGER aut_auditoria_cuentas
  AFTER INSERT OR UPDATE OR DELETE
  ON public.cuentas
  FOR EACH ROW
  EXECUTE PROCEDURE au_cuentas();

  /*
  * AUDITORIA CUENTAS DETALLE
  */
  
  
  CREATE OR REPLACE FUNCTION public.au_cuentas_detalle()
RETURNS trigger AS
$$
DECLARE

  indicador RECORD;
  columnas RECORD;
  
	old_transaccion 	integer;
	old_empresa_id character(2);	
	old_centro_utilidad character(2);
	old_numerodecuenta 	integer;
	old_departamento 	character varying(6);
	old_tarifario_id 	character varying(4);
	old_cargo 	character varying(10);
	old_cantidad 	numeric(9,2);
	old_precio numeric(12,2);
	old_porcentaje_descuento_empresa numeric(9,2);
	old_valor_cargo numeric(12,2);
	old_valor_nocubierto 	numeric(12,2);
	old_valor_cubierto numeric(12,2);
	old_facturado character(1);
	old_fecha_cargo timestamp;
	old_usuario_id 	integer;
	old_fecha_registro timestamp;
	old_sw_liq_manual character(1);
	old_valor_descuento_empresa numeric(9,2);
	old_valor_descuento_paciente numeric(9,2);
	old_porcentaje_descuento_paciente numeric(9,2);
	old_servicio_cargo character varying(2);
	old_autorizacion_int integer;
	old_autorizacion_ext integer;
	old_porcentaje_gravamen numeric(9,2);
	old_sw_cuota_paciente character(1);
	old_sw_cuota_moderadora character(1);
	old_codigo_agrupamiento_id integer;
	old_consecutivo integer;
	old_cargo_cups 	character varying(10);
	old_sw_cargue 	character(2);
	old_departamento_al_cargar character(6);
	old_paquete_codigo_id integer;
	old_sw_paquete_facturado switch;
  
	new_transaccion 	integer;
	new_empresa_id character(2);	
	new_centro_utilidad character(2);
	new_numerodecuenta 	integer;
	new_departamento 	character varying(6);
	new_tarifario_id 	character varying(4);
	new_cargo 	character varying(10);
	new_cantidad 	numeric(9,2);
	new_precio numeric(12,2);
	new_porcentaje_descuento_empresa numeric(9,2);
	new_valor_cargo numeric(12,2);
	new_valor_nocubierto 	numeric(12,2);
	new_valor_cubierto numeric(12,2);
	new_facturado character(1);
	new_fecha_cargo timestamp;
	new_usuario_id 	integer;
	new_fecha_registro timestamp;
	new_sw_liq_manual character(1);
	new_valor_descuento_empresa numeric(9,2);
	new_valor_descuento_paciente numeric(9,2);
	new_porcentaje_descuento_paciente numeric(9,2);
	new_servicio_cargo character varying(2);
	new_autorizacion_int integer;
	new_autorizacion_ext integer;
	new_porcentaje_gravamen numeric(9,2);
	new_sw_cuota_paciente character(1);
	new_sw_cuota_moderadora character(1);
	new_codigo_agrupamiento_id integer;
	new_consecutivo integer;
	new_cargo_cups 	character varying(10);
	new_sw_cargue 	character(2);
	new_departamento_al_cargar character(6);
	new_paquete_codigo_id integer;
	new_sw_paquete_facturado switch;
	
	usuario_registro_ integer;
	fecha_registro timestamp;
  
BEGIN
  SELECT * INTO indicador
  FROM   acciones AC,
         novedad_x_acciones NA
  WHERE  AC.descripcion = TG_OP
  AND    NA.accion_id = AC.accion_id
  AND    NA.table_name = 'cuentas_detalle';
 
 /* Inicializar Variables */
 
  IF TG_OP = 'DELETE' THEN
   
	old_transaccion 	:= OLD.transaccion;
	old_empresa_id := OLD.empresa_id;	
	old_centro_utilidad 	:= OLD.centro_utilidad;
	old_numerodecuenta 	:= OLD.numerodecuenta;
	old_departamento 	:= OLD.departamento;
	old_tarifario_id 	:= OLD.tarifario_id;
	old_cargo 	:= OLD.cargo;
	old_cantidad 	:= OLD.cantidad;
	old_precio 	:= OLD.precio;
	old_porcentaje_descuento_empresa 	:= OLD.porcentaje_descuento_empresa;
	old_valor_cargo 	:= OLD.valor_cargo;
	old_valor_nocubierto 	:= OLD.valor_nocubierto;
	old_valor_cubierto 	:= OLD.valor_cubierto;
	old_facturado 	:= OLD.facturado;
	old_fecha_cargo 	:= OLD.fecha_cargo;
	old_usuario_id 	:= OLD.usuario_id;
	old_fecha_registro 	:= OLD.fecha_registro;
	old_sw_liq_manual 	:= OLD.sw_liq_manual;
	old_valor_descuento_empresa 	:= OLD.valor_descuento_empresa;
	old_valor_descuento_paciente 	:= OLD.valor_descuento_paciente;
	old_porcentaje_descuento_paciente 	:= OLD.porcentaje_descuento_paciente;
	old_servicio_cargo 	:= OLD.servicio_cargo;
	old_autorizacion_int 	:= OLD.autorizacion_int;
	old_autorizacion_ext 	:= OLD.autorizacion_ext;
	old_porcentaje_gravamen 	:= OLD.porcentaje_gravamen;
	old_sw_cuota_paciente 	:= OLD.sw_cuota_paciente;
	old_sw_cuota_moderadora 	:= OLD.sw_cuota_moderadora;
	old_codigo_agrupamiento_id 	:= OLD.codigo_agrupamiento_id;
	old_consecutivo 	:= OLD.consecutivo;
	old_cargo_cups 	:= OLD.cargo_cups;
	old_sw_cargue 	:= OLD.sw_cargue;
	old_departamento_al_cargar 	:= OLD.departamento_al_cargar;
	old_paquete_codigo_id 	:= OLD.paquete_codigo_id ;
	old_sw_paquete_facturado := OLD.sw_paquete_facturado;
	
	usuario_registro_ := OLD.usuario_id;
	fecha_registro := NOW();
  END IF; 
  
  IF TG_OP = 'UPDATE' THEN
	new_transaccion 	:= NEW.transaccion;
	new_empresa_id := NEW.empresa_id;	
	new_centro_utilidad 	:= NEW.centro_utilidad;
	new_numerodecuenta 	:= NEW.numerodecuenta;
	new_departamento 	:= NEW.departamento;
	new_tarifario_id 	:= NEW.tarifario_id;
	new_cargo 	:= NEW.cargo;
	new_cantidad 	:= NEW.cantidad;
	new_precio 	:= NEW.precio;
	new_porcentaje_descuento_empresa 	:= NEW.porcentaje_descuento_empresa;
	new_valor_cargo 	:= NEW.valor_cargo;
	new_valor_nocubierto 	:= NEW.valor_nocubierto;
	new_valor_cubierto 	:= NEW.valor_cubierto;
	new_facturado 	:= NEW.facturado;
	new_fecha_cargo 	:= NEW.fecha_cargo;
	new_usuario_id 	:= NEW.usuario_id;
	new_fecha_registro 	:= NEW.fecha_registro;
	new_sw_liq_manual 	:= NEW.sw_liq_manual;
	new_valor_descuento_empresa 	:= NEW.valor_descuento_empresa;
	new_valor_descuento_paciente 	:= NEW.valor_descuento_paciente;
	new_porcentaje_descuento_paciente 	:= NEW.porcentaje_descuento_paciente;
	new_servicio_cargo 	:= NEW.servicio_cargo;
	new_autorizacion_int 	:= NEW.autorizacion_int;
	new_autorizacion_ext 	:= NEW.autorizacion_ext;
	new_porcentaje_gravamen 	:= NEW.porcentaje_gravamen;
	new_sw_cuota_paciente 	:= NEW.sw_cuota_paciente;
	new_sw_cuota_moderadora 	:= NEW.sw_cuota_moderadora;
	new_codigo_agrupamiento_id 	:= NEW.codigo_agrupamiento_id;
	new_consecutivo 	:= NEW.consecutivo;
	new_cargo_cups 	:= NEW.cargo_cups;
	new_sw_cargue 	:= NEW.sw_cargue;
	new_departamento_al_cargar 	:= NEW.departamento_al_cargar;
	new_paquete_codigo_id 	:= NEW.paquete_codigo_id ;
	new_sw_paquete_facturado := NEW.sw_paquete_facturado;
	
	usuario_registro_ := NEW.usuario_id;
	fecha_registro := NOW();
  
	old_transaccion 	:= OLD.transaccion;
	old_empresa_id := OLD.empresa_id;	
	old_centro_utilidad 	:= OLD.centro_utilidad;
	old_numerodecuenta 	:= OLD.numerodecuenta;
	old_departamento 	:= OLD.departamento;
	old_tarifario_id 	:= OLD.tarifario_id;
	old_cargo 	:= OLD.cargo;
	old_cantidad 	:= OLD.cantidad;
	old_precio 	:= OLD.precio;
	old_porcentaje_descuento_empresa 	:= OLD.porcentaje_descuento_empresa;
	old_valor_cargo 	:= OLD.valor_cargo;
	old_valor_nocubierto 	:= OLD.valor_nocubierto;
	old_valor_cubierto 	:= OLD.valor_cubierto;
	old_facturado 	:= OLD.facturado;
	old_fecha_cargo 	:= OLD.fecha_cargo;
	old_usuario_id 	:= OLD.usuario_id;
	old_fecha_registro 	:= OLD.fecha_registro;
	old_sw_liq_manual 	:= OLD.sw_liq_manual;
	old_valor_descuento_empresa 	:= OLD.valor_descuento_empresa;
	old_valor_descuento_paciente 	:= OLD.valor_descuento_paciente;
	old_porcentaje_descuento_paciente 	:= OLD.porcentaje_descuento_paciente;
	old_servicio_cargo 	:= OLD.servicio_cargo;
	old_autorizacion_int 	:= OLD.autorizacion_int;
	old_autorizacion_ext 	:= OLD.autorizacion_ext;
	old_porcentaje_gravamen 	:= OLD.porcentaje_gravamen;
	old_sw_cuota_paciente 	:= OLD.sw_cuota_paciente;
	old_sw_cuota_moderadora 	:= OLD.sw_cuota_moderadora;
	old_codigo_agrupamiento_id 	:= OLD.codigo_agrupamiento_id;
	old_consecutivo 	:= OLD.consecutivo;
	old_cargo_cups 	:= OLD.cargo_cups;
	old_sw_cargue 	:= OLD.sw_cargue;
	old_departamento_al_cargar 	:= OLD.departamento_al_cargar;
	old_paquete_codigo_id 	:= OLD.paquete_codigo_id ;
	old_sw_paquete_facturado := OLD.sw_paquete_facturado;
  END IF; 
  
  IF TG_OP = 'INSERT' THEN
	new_transaccion 	:= NEW.transaccion;
	new_empresa_id := NEW.empresa_id;	
	new_centro_utilidad 	:= NEW.centro_utilidad;
	new_numerodecuenta 	:= NEW.numerodecuenta;
	new_departamento 	:= NEW.departamento;
	new_tarifario_id 	:= NEW.tarifario_id;
	new_cargo 	:= NEW.cargo;
	new_cantidad 	:= NEW.cantidad;
	new_precio 	:= NEW.precio;
	new_porcentaje_descuento_empresa 	:= NEW.porcentaje_descuento_empresa;
	new_valor_cargo 	:= NEW.valor_cargo;
	new_valor_nocubierto 	:= NEW.valor_nocubierto;
	new_valor_cubierto 	:= NEW.valor_cubierto;
	new_facturado 	:= NEW.facturado;
	new_fecha_cargo 	:= NEW.fecha_cargo;
	new_usuario_id 	:= NEW.usuario_id;
	new_fecha_registro 	:= NEW.fecha_registro;
	new_sw_liq_manual 	:= NEW.sw_liq_manual;
	new_valor_descuento_empresa 	:= NEW.valor_descuento_empresa;
	new_valor_descuento_paciente 	:= NEW.valor_descuento_paciente;
	new_porcentaje_descuento_paciente 	:= NEW.porcentaje_descuento_paciente;
	new_servicio_cargo 	:= NEW.servicio_cargo;
	new_autorizacion_int 	:= NEW.autorizacion_int;
	new_autorizacion_ext 	:= NEW.autorizacion_ext;
	new_porcentaje_gravamen 	:= NEW.porcentaje_gravamen;
	new_sw_cuota_paciente 	:= NEW.sw_cuota_paciente;
	new_sw_cuota_moderadora 	:= NEW.sw_cuota_moderadora;
	new_codigo_agrupamiento_id 	:= NEW.codigo_agrupamiento_id;
	new_consecutivo 	:= NEW.consecutivo;
	new_cargo_cups 	:= NEW.cargo_cups;
	new_sw_cargue 	:= NEW.sw_cargue;
	new_departamento_al_cargar 	:= NEW.departamento_al_cargar;
	new_paquete_codigo_id 	:= NEW.paquete_codigo_id ;
	new_sw_paquete_facturado := NEW.sw_paquete_facturado;
	
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

      campo_27, 
      nuevo_valor_27,
      antiguo_valor_27,

      campo_28, 
      nuevo_valor_28,
      antiguo_valor_28,

      campo_29, 
      nuevo_valor_29,
      antiguo_valor_29,

      campo_30, 
      nuevo_valor_30,
      antiguo_valor_30,

      campo_31, 
      nuevo_valor_31,
      antiguo_valor_31,
      
      campo_32, 
      nuevo_valor_32,
      antiguo_valor_32,

      campo_33, 
      nuevo_valor_33,
      antiguo_valor_33,
      
     usuario_registro

    )
    VALUES
    (
      indicador.accion_id,
      indicador.novedad_indicador_id,
      'cuentas_detalle',
      
      'transaccion',
      new_transaccion,
      old_transaccion,
      
      'empresa_id',    
      new_empresa_id,
      old_empresa_id,
      
      'centro_utilidad',
      new_centro_utilidad,
      old_centro_utilidad,
      
      'numerodecuenta',
      new_numerodecuenta,
      old_numerodecuenta,
      
      'departamento',
      new_departamento,
      old_departamento,
      
      'tarifario_id',
      new_tarifario_id,
      old_tarifario_id,
      
      'cargo',
      new_cargo,
      old_cargo,
      
      'cantidad',
      new_cantidad,
      old_cantidad,

      'precio',
      new_precio,
      old_precio,

      'porcentaje_descuento_empresa',
      new_porcentaje_descuento_empresa,
      old_porcentaje_descuento_empresa,

      'valor_cargo',
      new_valor_cargo,
      old_valor_cargo,

      'valor_nocubierto',
      new_valor_nocubierto,
      old_valor_nocubierto,

      'valor_cubierto',
      new_valor_cubierto,
      old_valor_cubierto,

      'facturado',
      new_facturado,
      old_facturado,

      'fecha_cargo',
      new_fecha_cargo,
      old_fecha_cargo,

      'usuario_id',
      new_usuario_id,
      old_usuario_id,

      'fecha_registro',
      new_fecha_registro,
      old_fecha_registro,

      'sw_liq_manual',
      new_sw_liq_manual,
      old_sw_liq_manual,

      'valor_descuento_empresa',
      new_valor_descuento_empresa,
      old_valor_descuento_empresa,

      'valor_descuento_paciente',
      new_valor_descuento_paciente,
      old_valor_descuento_paciente,

      'servicio_cargo',
      new_servicio_cargo,
      old_servicio_cargo,

      'autorizacion_int',
      new_autorizacion_int,
      old_autorizacion_int,

      'autorizacion_ext',
      new_autorizacion_ext,
      old_autorizacion_ext,

      'porcentaje_gravamen',
      new_porcentaje_gravamen,
      old_porcentaje_gravamen,

      'sw_cuota_paciente',
      new_sw_cuota_paciente,
      old_sw_cuota_paciente,

      'sw_cuota_moderadora',
      new_sw_cuota_moderadora,
      old_sw_cuota_moderadora,

      'codigo_agrupamiento_id',
      new_codigo_agrupamiento_id,
      old_codigo_agrupamiento_id,

      'consecutivo',
      new_consecutivo,
      old_consecutivo,

      'cargo_cups',
      new_cargo_cups,
      old_cargo_cups,
      
      'sw_cargue',
      new_sw_cargue,
      old_sw_cargue,
      
      'departamento_al_cargar',
      new_departamento_al_cargar,
      old_departamento_al_cargar,
      
      'paquete_codigo_id',
      new_paquete_codigo_id,
      old_paquete_codigo_id,
      
      'sw_paquete_facturado',
      new_sw_paquete_facturado,
      old_sw_paquete_facturado,
      	  
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

ALTER FUNCTION public.au_cuentas_detalle()
  OWNER TO "admin";
  
  CREATE TRIGGER aut_auditoria_cuentas_detalle
  AFTER INSERT OR UPDATE OR DELETE
  ON public.cuentas_detalle
  FOR EACH ROW
  EXECUTE PROCEDURE au_cuentas_detalle();

  
  /*
  * AUDITORIA FACTURAS DESPACHO
  */
  
  
  CREATE OR REPLACE FUNCTION public.au_facturas_despacho()
RETURNS trigger AS
$$
DECLARE

  indicador RECORD;
  columnas RECORD;
  
	old_empresa_id 	character(2);
	old_factura_fiscal integer;	
	old_prefijo character varying(4);	
	old_documento_id integer;	
	old_tipo_id_tercero character varying(3);
	old_tercero_id character varying(32);	
	old_valor_notacredito numeric(20,4);	
	old_valor_notadebito numeric(20,4);	
	old_fecha_registro timestamp;	
	old_usuario_id integer;	
	old_valor_total numeric(20,4);	
	old_tipo_id_vendedor character(3);
	old_vendedor_id character varying(32);	
	old_pedido_cliente_id integer;
	old_fecha_vencimiento_factura date;
	old_observaciones 	text;
	old_porcentaje_rtf 	numeric(9,4);
	old_porcentaje_ica numeric(9,4);
	old_porcentaje_reteiva numeric(9,4);	
	old_saldo numeric(20,4);
  
	new_empresa_id 	character(2);
	new_factura_fiscal integer;	
	new_prefijo character varying(4);	
	new_documento_id integer;	
	new_tipo_id_tercero character varying(3);
	new_tercero_id character varying(32);	
	new_valor_notacredito numeric(20,4);	
	new_valor_notadebito numeric(20,4);	
	new_fecha_registro timestamp;
	new_usuario_id integer;	
	new_valor_total numeric(20,4);	
	new_tipo_id_vendedor character(3);
	new_vendedor_id character varying(32);	
	new_pedido_cliente_id integer;
	new_fecha_vencimiento_factura date;
	new_observaciones 	text;
	new_porcentaje_rtf 	numeric(9,4);
	new_porcentaje_ica numeric(9,4);
	new_porcentaje_reteiva numeric(9,4);	
	new_saldo numeric(20,4);
	
	usuario_registro_ integer;
	fecha_registro timestamp;
  
BEGIN
  SELECT * INTO indicador
  FROM   acciones AC,
         novedad_x_acciones NA
  WHERE  AC.descripcion = TG_OP
  AND    NA.accion_id = AC.accion_id
  AND    NA.table_name = 'inv_facturas_despacho';
 
 /* Inicializar Variables */
 
  IF TG_OP = 'DELETE' THEN
   
	old_empresa_id 	:= OLD.empresa_id; 	
	old_factura_fiscal 	:= OLD.factura_fiscal; 	
	old_prefijo 	:= OLD.prefijo; 	
	old_documento_id 	:= OLD.documento_id; 	
	old_tipo_id_tercero 	:= OLD.tipo_id_tercero; 	
	old_tercero_id 	:= OLD.tercero_id; 	
	old_valor_notacredito 	:= OLD.valor_notacredito; 	
	old_valor_notadebito 	:= OLD.valor_notadebito; 	
	old_fecha_registro 	:= OLD.fecha_registro; 	
	old_usuario_id 	:= OLD.usuario_id; 	
	old_valor_total 	:= OLD.valor_total; 	
	old_tipo_id_vendedor 	:= OLD.tipo_id_vendedor; 	
	old_vendedor_id 	:= OLD.vendedor_id; 	
	old_pedido_cliente_id 	:= OLD.pedido_cliente_id; 	
	old_fecha_vencimiento_factura 	:= OLD.fecha_vencimiento_factura; 	
	old_observaciones 	:= OLD.observaciones; 	
	old_porcentaje_rtf 	:= OLD.porcentaje_rtf; 	
	old_porcentaje_ica 	:= OLD.porcentaje_ica; 	
	old_porcentaje_reteiva 	:= OLD.porcentaje_reteiva; 	
	old_saldo := OLD.saldo;
	
	usuario_registro_ := OLD.usuario_id;
	fecha_registro := NOW();
  END IF; 
  
  IF TG_OP = 'UPDATE' THEN
	new_empresa_id 	:= NEW.empresa_id; 	
	new_factura_fiscal 	:= NEW.factura_fiscal; 	
	new_prefijo 	:= NEW.prefijo; 	
	new_documento_id 	:= NEW.documento_id; 	
	new_tipo_id_tercero 	:= NEW.tipo_id_tercero; 	
	new_tercero_id 	:= NEW.tercero_id; 	
	new_valor_notacredito 	:= NEW.valor_notacredito; 	
	new_valor_notadebito 	:= NEW.valor_notadebito; 	
	new_fecha_registro 	:= NEW.fecha_registro; 	
	new_usuario_id 	:= NEW.usuario_id; 	
	new_valor_total 	:= NEW.valor_total; 	
	new_tipo_id_vendedor 	:= NEW.tipo_id_vendedor; 	
	new_vendedor_id 	:= NEW.vendedor_id; 	
	new_pedido_cliente_id 	:= NEW.pedido_cliente_id; 	
	new_fecha_vencimiento_factura 	:= NEW.fecha_vencimiento_factura; 	
	new_observaciones 	:= NEW.observaciones; 	
	new_porcentaje_rtf 	:= NEW.porcentaje_rtf; 	
	new_porcentaje_ica 	:= NEW.porcentaje_ica; 	
	new_porcentaje_reteiva 	:= NEW.porcentaje_reteiva; 	
	new_saldo := NEW.saldo;
	
	usuario_registro_ := NEW.usuario_id;
	fecha_registro := NOW();
  
	old_empresa_id 	:= OLD.empresa_id; 	
	old_factura_fiscal 	:= OLD.factura_fiscal; 	
	old_prefijo 	:= OLD.prefijo; 	
	old_documento_id 	:= OLD.documento_id; 	
	old_tipo_id_tercero 	:= OLD.tipo_id_tercero; 	
	old_tercero_id 	:= OLD.tercero_id; 	
	old_valor_notacredito 	:= OLD.valor_notacredito; 	
	old_valor_notadebito 	:= OLD.valor_notadebito; 	
	old_fecha_registro 	:= OLD.fecha_registro; 	
	old_usuario_id 	:= OLD.usuario_id; 	
	old_valor_total 	:= OLD.valor_total; 	
	old_tipo_id_vendedor 	:= OLD.tipo_id_vendedor; 	
	old_vendedor_id 	:= OLD.vendedor_id; 	
	old_pedido_cliente_id 	:= OLD.pedido_cliente_id; 	
	old_fecha_vencimiento_factura 	:= OLD.fecha_vencimiento_factura; 	
	old_observaciones 	:= OLD.observaciones; 	
	old_porcentaje_rtf 	:= OLD.porcentaje_rtf; 	
	old_porcentaje_ica 	:= OLD.porcentaje_ica; 	
	old_porcentaje_reteiva 	:= OLD.porcentaje_reteiva; 	
	old_saldo := OLD.saldo;
  END IF; 
  
  IF TG_OP = 'INSERT' THEN
	new_empresa_id 	:= NEW.empresa_id; 	
	new_factura_fiscal 	:= NEW.factura_fiscal; 	
	new_prefijo 	:= NEW.prefijo; 	
	new_documento_id 	:= NEW.documento_id; 	
	new_tipo_id_tercero 	:= NEW.tipo_id_tercero; 	
	new_tercero_id 	:= NEW.tercero_id; 	
	new_valor_notacredito 	:= NEW.valor_notacredito; 	
	new_valor_notadebito 	:= NEW.valor_notadebito; 	
	new_fecha_registro 	:= NEW.fecha_registro; 	
	new_usuario_id 	:= NEW.usuario_id; 	
	new_valor_total 	:= NEW.valor_total; 	
	new_tipo_id_vendedor 	:= NEW.tipo_id_vendedor; 	
	new_vendedor_id 	:= NEW.vendedor_id; 	
	new_pedido_cliente_id 	:= NEW.pedido_cliente_id; 	
	new_fecha_vencimiento_factura 	:= NEW.fecha_vencimiento_factura; 	
	new_observaciones 	:= NEW.observaciones; 	
	new_porcentaje_rtf 	:= NEW.porcentaje_rtf; 	
	new_porcentaje_ica 	:= NEW.porcentaje_ica; 	
	new_porcentaje_reteiva 	:= NEW.porcentaje_reteiva; 	
	new_saldo := NEW.saldo;
	
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
      
     usuario_registro

    )
    VALUES
    (
      indicador.accion_id,
      indicador.novedad_indicador_id,
      'inv_facturas_despacho',
      
      'empresa_id',
      new_empresa_id,
      old_empresa_id,
      
      'factura_fiscal',    
      new_factura_fiscal,
      old_factura_fiscal,
      
      'prefijo',
      new_prefijo,
      old_prefijo,
      
      'documento_id',
      new_documento_id,
      old_documento_id,
      
      'tipo_id_tercero',
      new_tipo_id_tercero,
      old_tipo_id_tercero,
      
      'tercero_id',
      new_tercero_id,
      old_tercero_id,
      
      'valor_notacredito',
      new_valor_notacredito,
      old_valor_notacredito,
      
      'valor_notadebito',
      new_valor_notadebito,
      old_valor_notadebito,

      'fecha_registro',
      new_fecha_registro,
      old_fecha_registro,

      'usuario_id',
      new_usuario_id,
      old_usuario_id,

      'valor_total',
      new_valor_total,
      old_valor_total,

      'tipo_id_vendedor',
      new_tipo_id_vendedor,
      old_tipo_id_vendedor,

      'vendedor_id',
      new_vendedor_id,
      old_vendedor_id,

      'pedido_cliente_id',
      new_pedido_cliente_id,
      old_pedido_cliente_id,

      'fecha_vencimiento_factura',
      new_fecha_vencimiento_factura,
      old_fecha_vencimiento_factura,

      'observaciones',
      new_observaciones,
      old_observaciones,

      'porcentaje_rtf',
      new_porcentaje_rtf,
      old_porcentaje_rtf,

      'porcentaje_ica',
      new_porcentaje_ica,
      old_porcentaje_ica,

      'porcentaje_reteiva',
      new_porcentaje_reteiva,
      old_porcentaje_reteiva,

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

ALTER FUNCTION public.au_facturas_despacho()
  OWNER TO "admin";
  
  CREATE TRIGGER aut_auditoria_facturas_despacho
  AFTER INSERT OR UPDATE OR DELETE
  ON public.inv_facturas_despacho
  FOR EACH ROW
  EXECUTE PROCEDURE au_facturas_despacho();

  
  /*
  * AUDITORIA FACTURAS DEPACHOS DETALLE
  */
  
   
  CREATE OR REPLACE FUNCTION public.au_facturas_despacho_detalle()
RETURNS trigger AS
$$
DECLARE

  indicador RECORD;
  columnas RECORD;
  
	old_item_id 	integer;
	old_prefijo 	character varying(4);
	old_factura_fiscal integer;	
	old_observacion text;	
	old_codigo_producto character varying(50);	
	old_cantidad 	integer;
	old_fecha_vencimiento 	date;
	old_lote character varying(30);	
	old_valor_unitario 	numeric(20,4);
	old_empresa_id character(2);	
	old_cantidad_devuelta integer; 	
	old_porc_iva numeric(9,4);
  
	new_item_id 	integer;
	new_prefijo 	character varying(4);
	new_factura_fiscal integer;	
	new_observacion text;	
	new_codigo_producto character varying(50);	
	new_cantidad 	integer;
	new_fecha_vencimiento 	date;
	new_lote character varying(30);	
	new_valor_unitario 	numeric(20,4);
	new_empresa_id character(2);	
	new_cantidad_devuelta integer; 	
	new_porc_iva numeric(9,4);
	
	usuario_registro_ integer;
	fecha_registro timestamp;
  
BEGIN
  SELECT * INTO indicador
  FROM   acciones AC,
         novedad_x_acciones NA
  WHERE  AC.descripcion = TG_OP
  AND    NA.accion_id = AC.accion_id
  AND    NA.table_name = 'inv_facturas_despacho_d';
 
 /* Inicializar Variables */
 
  IF TG_OP = 'DELETE' THEN
   
	old_item_id 	:= OLD.item_id; 	
	old_prefijo 	:= OLD.prefijo; 	
	old_factura_fiscal 	:= OLD.factura_fiscal; 	
	old_observacion 	:= OLD.observacion; 	
	old_codigo_producto 	:= OLD.codigo_producto; 	
	old_cantidad 	:= OLD.cantidad; 	
	old_fecha_vencimiento 	:= OLD.fecha_vencimiento; 	
	old_lote 	:= OLD.lote; 	
	old_valor_unitario 	:= OLD.valor_unitario; 	
	old_empresa_id 	:= OLD.empresa_id; 	
	old_cantidad_devuelta 	:= OLD.cantidad_devuelta; 	
	old_porc_iva := OLD.porc_iva;
	
	usuario_registro_ := 2;
	fecha_registro := NOW();
  END IF; 
  
  IF TG_OP = 'UPDATE' THEN
	new_item_id 	:= NEW.item_id; 	
	new_prefijo 	:= NEW.prefijo; 	
	new_factura_fiscal 	:= NEW.factura_fiscal; 	
	new_observacion 	:= NEW.observacion; 	
	new_codigo_producto 	:= NEW.codigo_producto; 	
	new_cantidad 	:= NEW.cantidad; 	
	new_fecha_vencimiento 	:= NEW.fecha_vencimiento; 	
	new_lote 	:= NEW.lote; 	
	new_valor_unitario 	:= NEW.valor_unitario; 	
	new_empresa_id 	:= NEW.empresa_id; 	
	new_cantidad_devuelta 	:= NEW.cantidad_devuelta; 	
	new_porc_iva := NEW.porc_iva;
	
	usuario_registro_ := 2;
	fecha_registro := NOW();
  
	old_item_id 	:= OLD.item_id; 	
	old_prefijo 	:= OLD.prefijo; 	
	old_factura_fiscal 	:= OLD.factura_fiscal; 	
	old_observacion 	:= OLD.observacion; 	
	old_codigo_producto 	:= OLD.codigo_producto; 	
	old_cantidad 	:= OLD.cantidad; 	
	old_fecha_vencimiento 	:= OLD.fecha_vencimiento; 	
	old_lote 	:= OLD.lote; 	
	old_valor_unitario 	:= OLD.valor_unitario; 	
	old_empresa_id 	:= OLD.empresa_id; 	
	old_cantidad_devuelta 	:= OLD.cantidad_devuelta; 	
	old_porc_iva := OLD.porc_iva;
  END IF; 
  
  IF TG_OP = 'INSERT' THEN
	new_item_id 	:= NEW.item_id; 	
	new_prefijo 	:= NEW.prefijo; 	
	new_factura_fiscal 	:= NEW.factura_fiscal; 	
	new_observacion 	:= NEW.observacion; 	
	new_codigo_producto 	:= NEW.codigo_producto; 	
	new_cantidad 	:= NEW.cantidad; 	
	new_fecha_vencimiento 	:= NEW.fecha_vencimiento; 	
	new_lote 	:= NEW.lote; 	
	new_valor_unitario 	:= NEW.valor_unitario; 	
	new_empresa_id 	:= NEW.empresa_id; 	
	new_cantidad_devuelta 	:= NEW.cantidad_devuelta; 	
	new_porc_iva := NEW.porc_iva;
	
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
      'inv_facturas_despacho_d',
      
      'item_id',
      new_item_id,
      old_item_id,
      
      'prefijo',    
      new_prefijo,
      old_prefijo,
      
      'factura_fiscal',
      new_factura_fiscal,
      old_factura_fiscal,
      
      'observacion',
      new_observacion,
      old_observacion,
      
      'codigo_producto',
      new_codigo_producto,
      old_codigo_producto,
      
      'cantidad',
      new_cantidad,
      old_cantidad,
      
      'fecha_vencimiento',
      new_fecha_vencimiento,
      old_fecha_vencimiento,
      
      'lote',
      new_lote,
      old_lote,

      'valor_unitario',
      new_valor_unitario,
      old_valor_unitario,

      'empresa_id',
      new_empresa_id,
      old_empresa_id,

      'cantidad_devuelta',
      new_cantidad_devuelta,
      old_cantidad_devuelta,

      'porc_iva',
      new_porc_iva,
      old_porc_iva,

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

ALTER FUNCTION public.au_facturas_despacho_detalle()
  OWNER TO "admin";
  
  
  CREATE TRIGGER aut_auditoria_facturas_despacho_d
  AFTER INSERT OR UPDATE OR DELETE
  ON public.inv_facturas_despacho_d
  FOR EACH ROW
  EXECUTE PROCEDURE au_facturas_despacho_detalle();

