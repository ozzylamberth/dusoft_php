 /*TRIGGER DE CABECERA DE LAS RECEPCIONES PARCIALES*/
  CREATE OR REPLACE FUNCTION public.au_profesionales()
RETURNS trigger AS
$$
DECLARE

  indicador RECORD;
  columnas RECORD;
  
	old_tipo_id_tercero character varying(3);
	old_tercero_id 	character varying(32);
	old_nombre 	character varying(100);
	old_tipo_profesional 	character varying(2);
	old_tarjeta_profesional 	character(20);
	old_estado 	character(1);
	old_sexo_id 	character(1);
	old_universidad 	character varying(60);
	old_sw_registro_defuncion 	character(1);
	old_fecha_registro timestamp;	
	old_usuario_id 	integer;
	old_observacion 	character varying(256);
	old_registro_salud_departamental 	character varying(60);
	old_sw_profesional_formula 	character(1);
	old_tipo_clase_profesional_id 	character varying(2);
	old_furips_tipo_id_tercero 	character varying(3);
	old_furips_tercero_id 	character varying(32);
	old_firma character varying(30);
  
	new_tipo_id_tercero character varying(3);
	new_tercero_id 	character varying(32);
	new_nombre 	character varying(100);
	new_tipo_profesional 	character varying(2);
	new_tarjeta_profesional 	character(20);
	new_estado 	character(1);
	new_sexo_id 	character(1);
	new_universidad 	character varying(60);
	new_sw_registro_defuncion 	character(1);
	new_fecha_registro timestamp;	
	new_usuario_id 	integer;
	new_observacion 	character varying(256);
	new_registro_salud_departamental 	character varying(60);
	new_sw_profesional_formula 	character(1);
	new_tipo_clase_profesional_id 	character varying(2);
	new_furips_tipo_id_tercero 	character varying(3);
	new_furips_tercero_id 	character varying(32);
	new_firma character varying(30);
	
	usuario_registro_ integer;
	fecha_registro timestamp;
  
BEGIN
  SELECT * INTO indicador
  FROM   acciones AC,
         novedad_x_acciones NA
  WHERE  AC.descripcion = TG_OP
  AND    NA.accion_id = AC.accion_id
  AND    NA.table_name = 'profesionales';
 
 /* Inicializar Variables */
 
  IF TG_OP = 'DELETE' THEN
   
	old_tipo_id_tercero 	:= OLD.tipo_id_tercero;
	old_tercero_id 	:= OLD.tercero_id;
	old_nombre 	:= OLD.nombre;
	old_tipo_profesional 	:= OLD.tipo_profesional;
	old_tarjeta_profesional 	:= OLD.tarjeta_profesional;
	old_estado 	:= OLD.estado;
	old_sexo_id 	:= OLD.sexo_id;
	old_universidad 	:= OLD.universidad;
	old_sw_registro_defuncion 	:= OLD.sw_registro_defuncion;
	old_fecha_registro 	:= OLD.fecha_registro;
	old_usuario_id 	:= OLD.usuario_id;
	old_observacion 	:= OLD.observacion;
	old_registro_salud_departamental 	:= OLD.registro_salud_departamental;
	old_sw_profesional_formula 	:= OLD.sw_profesional_formula;
	old_tipo_clase_profesional_id 	:= OLD.tipo_clase_profesional_id;
	old_furips_tipo_id_tercero 	:= OLD.furips_tipo_id_tercero;
	old_furips_tercero_id 	:= OLD.furips_tercero_id;
	old_firma := OLD.firma;
	
	usuario_registro_ := OLD.usuario_id;
	fecha_registro := NOW();
  END IF; 
  
  IF TG_OP = 'UPDATE' THEN
	new_tipo_id_tercero 	:= NEW.tipo_id_tercero;
	new_tercero_id 	:= NEW.tercero_id;
	new_nombre 	:= NEW.nombre;
	new_tipo_profesional 	:= NEW.tipo_profesional;
	new_tarjeta_profesional 	:= NEW.tarjeta_profesional;
	new_estado 	:= NEW.estado;
	new_sexo_id 	:= NEW.sexo_id;
	new_universidad 	:= NEW.universidad;
	new_sw_registro_defuncion 	:= NEW.sw_registro_defuncion;
	new_fecha_registro 	:= NEW.fecha_registro;
	new_usuario_id 	:= NEW.usuario_id;
	new_observacion 	:= NEW.observacion;
	new_registro_salud_departamental 	:= NEW.registro_salud_departamental;
	new_sw_profesional_formula 	:= NEW.sw_profesional_formula;
	new_tipo_clase_profesional_id 	:= NEW.tipo_clase_profesional_id;
	new_furips_tipo_id_tercero 	:= NEW.furips_tipo_id_tercero;
	new_furips_tercero_id 	:= NEW.furips_tercero_id;
	new_firma := NEW.firma;
	
	usuario_registro_ := NEW.usuario_id;
	fecha_registro := NOW();
  
	old_tipo_id_tercero 	:= OLD.tipo_id_tercero;
	old_tercero_id 	:= OLD.tercero_id;
	old_nombre 	:= OLD.nombre;
	old_tipo_profesional 	:= OLD.tipo_profesional;
	old_tarjeta_profesional 	:= OLD.tarjeta_profesional;
	old_estado 	:= OLD.estado;
	old_sexo_id 	:= OLD.sexo_id;
	old_universidad 	:= OLD.universidad;
	old_sw_registro_defuncion 	:= OLD.sw_registro_defuncion;
	old_fecha_registro 	:= OLD.fecha_registro;
	old_usuario_id 	:= OLD.usuario_id;
	old_observacion 	:= OLD.observacion;
	old_registro_salud_departamental 	:= OLD.registro_salud_departamental;
	old_sw_profesional_formula 	:= OLD.sw_profesional_formula;
	old_tipo_clase_profesional_id 	:= OLD.tipo_clase_profesional_id;
	old_furips_tipo_id_tercero 	:= OLD.furips_tipo_id_tercero;
	old_furips_tercero_id 	:= OLD.furips_tercero_id;
	old_firma := OLD.firma;
  END IF; 
  
  IF TG_OP = 'INSERT' THEN
	new_tipo_id_tercero 	:= NEW.tipo_id_tercero;
	new_tercero_id 	:= NEW.tercero_id;
	new_nombre 	:= NEW.nombre;
	new_tipo_profesional 	:= NEW.tipo_profesional;
	new_tarjeta_profesional 	:= NEW.tarjeta_profesional;
	new_estado 	:= NEW.estado;
	new_sexo_id 	:= NEW.sexo_id;
	new_universidad 	:= NEW.universidad;
	new_sw_registro_defuncion 	:= NEW.sw_registro_defuncion;
	new_fecha_registro 	:= NEW.fecha_registro;
	new_usuario_id 	:= NEW.usuario_id;
	new_observacion 	:= NEW.observacion;
	new_registro_salud_departamental 	:= NEW.registro_salud_departamental;
	new_sw_profesional_formula 	:= NEW.sw_profesional_formula;
	new_tipo_clase_profesional_id 	:= NEW.tipo_clase_profesional_id;
	new_furips_tipo_id_tercero 	:= NEW.furips_tipo_id_tercero;
	new_furips_tercero_id 	:= NEW.furips_tercero_id;
	new_firma := NEW.firma;
	
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

     usuario_registro

    )
    VALUES
    (
      indicador.accion_id,
      indicador.novedad_indicador_id,
      'profesionales',
      
      'tipo_id_tercero',
      new_tipo_id_tercero,
      old_tipo_id_tercero,
      
      'tercero_id',    
      new_tercero_id,
      old_tercero_id,
      
      'nombre',
      new_nombre,
      old_nombre,
      
      'tipo_profesional',
      new_tipo_profesional,
      old_tipo_profesional,
      
      'tarjeta_profesional',
      new_tarjeta_profesional,
      old_tarjeta_profesional,
      
      'estado',
      new_estado,
      old_estado,
      
      'sexo_id',
      new_sexo_id,
      old_sexo_id,
      
      'universidad',
      new_universidad,
      old_universidad,
      
      'sw_registro_defuncion',
      new_sw_registro_defuncion,
      old_sw_registro_defuncion,

      'fecha_registro',
      new_fecha_registro,
      old_fecha_registro,

      'usuario_id',
      new_usuario_id,
      old_usuario_id,

      'observacion',
      new_observacion,
      old_observacion,

      'registro_salud_departamental',
      new_registro_salud_departamental,
      old_registro_salud_departamental,

      'sw_profesional_formula',
      new_sw_profesional_formula,
      old_sw_profesional_formula,

      'tipo_clase_profesional_id',
      new_tipo_clase_profesional_id,
      old_tipo_clase_profesional_id,

      'furips_tipo_id_tercero',
      new_furips_tipo_id_tercero,
      old_furips_tipo_id_tercero,

      'furips_tercero_id',
      new_furips_tercero_id,
      old_furips_tercero_id,

      'firma',
      new_firma,
      old_firma,
	  
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

ALTER FUNCTION public.au_profesionales()
  OWNER TO "admin";
  
  CREATE TRIGGER aut_auditoria_profesionales
  AFTER INSERT OR UPDATE OR DELETE
  ON public.profesionales
  FOR EACH ROW
  EXECUTE PROCEDURE au_profesionales();

  
 /*TRIGGER PARA LA AUDITORIA DE VENTAS FARMACIA*/
  CREATE OR REPLACE FUNCTION public.au_fac_facturas_contado()
RETURNS trigger AS
$$
DECLARE

  indicador RECORD;
  columnas RECORD;
  
	old_empresa_id 	character(2);
	old_centro_utilidad 	character(2);
	old_factura_fiscal 	integer;
	old_prefijo 	character varying(4);
	old_total_abono 	numeric(12,2);
	old_total_efectivo 	numeric(12,2);
	old_total_cheques 	numeric(12,2);
	old_total_tarjetas 	numeric(12,2);
	old_tipo_id_tercero 	character varying(3);
	old_tercero_id 	character varying(32);
	old_estado 	character(1);
	old_fecha_registro 	timestamp;
	old_usuario_id 	integer;
	old_caja_id 	integer;
	old_cierre_caja_id 	integer;
	old_total_bonos 	numeric(12,2);
	old_consecutivo 	integer;
	old_numerodecuenta 	integer;
	old_sw_cuota_moderadora 	integer;
	old_documento_id integer;
  
	new_empresa_id 	character(2);
	new_centro_utilidad 	character(2);
	new_factura_fiscal 	integer;
	new_prefijo 	character varying(4);
	new_total_abono 	numeric(12,2);
	new_total_efectivo 	numeric(12,2);
	new_total_cheques 	numeric(12,2);
	new_total_tarjetas 	numeric(12,2);
	new_tipo_id_tercero 	character varying(3);
	new_tercero_id 	character varying(32);
	new_estado 	character(1);
	new_fecha_registro 	timestamp;
	new_usuario_id 	integer;
	new_caja_id 	integer;
	new_cierre_caja_id 	integer;
	new_total_bonos 	numeric(12,2);
	new_consecutivo 	integer;
	new_numerodecuenta 	integer;
	new_sw_cuota_moderadora 	integer;
	new_documento_id integer;
	
	usuario_registro_ integer;
	fecha_registro timestamp;
  
BEGIN
  SELECT * INTO indicador
  FROM   acciones AC,
         novedad_x_acciones NA
  WHERE  AC.descripcion = TG_OP
  AND    NA.accion_id = AC.accion_id
  AND    NA.table_name = 'fac_facturas_contado';
 
 /* Inicializar Variables */
 
  IF TG_OP = 'DELETE' THEN
   
	old_empresa_id 	:= OLD.empresa_id; 	
	old_centro_utilidad 	:= OLD.centro_utilidad; 	
	old_factura_fiscal 	:= OLD.factura_fiscal; 	
	old_prefijo 	:= OLD.prefijo; 	
	old_total_abono 	:= OLD.total_abono; 	
	old_total_efectivo 	:= OLD.total_efectivo; 	
	old_total_cheques 	:= OLD.total_cheques; 	
	old_total_tarjetas 	:= OLD.total_tarjetas; 	
	old_tipo_id_tercero 	:= OLD.tipo_id_tercero; 	
	old_tercero_id 	:= OLD.tercero_id; 	
	old_estado 	:= OLD.estado; 	
	old_fecha_registro 	:= OLD.fecha_registro; 	
	old_usuario_id 	:= OLD.usuario_id; 	
	old_caja_id 	:= OLD.caja_id; 	
	old_cierre_caja_id 	:= OLD.cierre_caja_id; 	
	old_total_bonos 	:= OLD.total_bonos; 	
	old_consecutivo 	:= OLD.consecutivo; 	
	old_numerodecuenta 	:= OLD.numerodecuenta; 	
	old_sw_cuota_moderadora 	:= OLD.sw_cuota_moderadora; 	
	old_documento_id := OLD.documento_id;
	
	usuario_registro_ := OLD.usuario_id;
	fecha_registro := NOW();
  END IF; 
  
  IF TG_OP = 'UPDATE' THEN
	new_empresa_id 	:= NEW.empresa_id; 	
	new_centro_utilidad 	:= NEW.centro_utilidad; 	
	new_factura_fiscal 	:= NEW.factura_fiscal; 	
	new_prefijo 	:= NEW.prefijo; 	
	new_total_abono 	:= NEW.total_abono; 	
	new_total_efectivo 	:= NEW.total_efectivo; 	
	new_total_cheques 	:= NEW.total_cheques; 	
	new_total_tarjetas 	:= NEW.total_tarjetas; 	
	new_tipo_id_tercero 	:= NEW.tipo_id_tercero; 	
	new_tercero_id 	:= NEW.tercero_id; 	
	new_estado 	:= NEW.estado; 	
	new_fecha_registro 	:= NEW.fecha_registro; 	
	new_usuario_id 	:= NEW.usuario_id; 	
	new_caja_id 	:= NEW.caja_id; 	
	new_cierre_caja_id 	:= NEW.cierre_caja_id; 	
	new_total_bonos 	:= NEW.total_bonos; 	
	new_consecutivo 	:= NEW.consecutivo; 	
	new_numerodecuenta 	:= NEW.numerodecuenta; 	
	new_sw_cuota_moderadora 	:= NEW.sw_cuota_moderadora; 	
	new_documento_id := NEW.documento_id;
	
	usuario_registro_ := NEW.usuario_id;
	fecha_registro := NOW();
  
	old_empresa_id 	:= OLD.empresa_id; 	
	old_centro_utilidad 	:= OLD.centro_utilidad; 	
	old_factura_fiscal 	:= OLD.factura_fiscal; 	
	old_prefijo 	:= OLD.prefijo; 	
	old_total_abono 	:= OLD.total_abono; 	
	old_total_efectivo 	:= OLD.total_efectivo; 	
	old_total_cheques 	:= OLD.total_cheques; 	
	old_total_tarjetas 	:= OLD.total_tarjetas; 	
	old_tipo_id_tercero 	:= OLD.tipo_id_tercero; 	
	old_tercero_id 	:= OLD.tercero_id; 	
	old_estado 	:= OLD.estado; 	
	old_fecha_registro 	:= OLD.fecha_registro; 	
	old_usuario_id 	:= OLD.usuario_id; 	
	old_caja_id 	:= OLD.caja_id; 	
	old_cierre_caja_id 	:= OLD.cierre_caja_id; 	
	old_total_bonos 	:= OLD.total_bonos; 	
	old_consecutivo 	:= OLD.consecutivo; 	
	old_numerodecuenta 	:= OLD.numerodecuenta; 	
	old_sw_cuota_moderadora 	:= OLD.sw_cuota_moderadora; 	
	old_documento_id := OLD.documento_id;
  END IF; 
  
  IF TG_OP = 'INSERT' THEN
	new_empresa_id 	:= NEW.empresa_id; 	
	new_centro_utilidad 	:= NEW.centro_utilidad; 	
	new_factura_fiscal 	:= NEW.factura_fiscal; 	
	new_prefijo 	:= NEW.prefijo; 	
	new_total_abono 	:= NEW.total_abono; 	
	new_total_efectivo 	:= NEW.total_efectivo; 	
	new_total_cheques 	:= NEW.total_cheques; 	
	new_total_tarjetas 	:= NEW.total_tarjetas; 	
	new_tipo_id_tercero 	:= NEW.tipo_id_tercero; 	
	new_tercero_id 	:= NEW.tercero_id; 	
	new_estado 	:= NEW.estado; 	
	new_fecha_registro 	:= NEW.fecha_registro; 	
	new_usuario_id 	:= NEW.usuario_id; 	
	new_caja_id 	:= NEW.caja_id; 	
	new_cierre_caja_id 	:= NEW.cierre_caja_id; 	
	new_total_bonos 	:= NEW.total_bonos; 	
	new_consecutivo 	:= NEW.consecutivo; 	
	new_numerodecuenta 	:= NEW.numerodecuenta; 	
	new_sw_cuota_moderadora 	:= NEW.sw_cuota_moderadora; 	
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
      'fac_facturas_contado',
      
      'empresa_id',
      new_empresa_id,
      old_empresa_id,
      
      'centro_utilidad',    
      new_centro_utilidad,
      old_centro_utilidad,
      
      'factura_fiscal',
      new_factura_fiscal,
      old_factura_fiscal,
      
      'prefijo',
      new_prefijo,
      old_prefijo,
      
      'total_abono',
      new_total_abono,
      old_total_abono,
      
      'total_efectivo',
      new_total_efectivo,
      old_total_efectivo,
      
      'total_cheques',
      new_total_cheques,
      old_total_cheques,
      
      'total_tarjetas',
      new_total_tarjetas,
      old_total_tarjetas,
      
      'tipo_id_tercero',
      new_tipo_id_tercero,
      old_tipo_id_tercero,

      'tercero_id',
      new_tercero_id,
      old_tercero_id,

      'estado',
      new_estado,
      old_estado,

      'fecha_registro',
      new_fecha_registro,
      old_fecha_registro,

      'usuario_id',
      new_usuario_id,
      old_usuario_id,

      'caja_id',
      new_caja_id,
      old_caja_id,

      'cierre_caja_id',
      new_cierre_caja_id,
      old_cierre_caja_id,

      'total_bonos',
      new_total_bonos,
      old_total_bonos,

      'consecutivo',
      new_consecutivo,
      old_consecutivo,

      'numerodecuenta',
      new_numerodecuenta,
      old_numerodecuenta,

      'sw_cuota_moderadora',
      new_sw_cuota_moderadora,
      old_sw_cuota_moderadora,

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

ALTER FUNCTION public.au_fac_facturas_contado()
  OWNER TO "admin";

  CREATE TRIGGER aut_auditoria_fac_facturas_contado
  AFTER INSERT OR UPDATE OR DELETE
  ON public.fac_facturas_contado
  FOR EACH ROW
  EXECUTE PROCEDURE au_fac_facturas_contado();

  
    
 /*TRIGGER PARA LA AUDITORIA DE VENTAS FARMACIA*/
  CREATE OR REPLACE FUNCTION public.au_fac_facturas()
RETURNS trigger AS
$$
DECLARE

  indicador RECORD;
  columnas RECORD;
  
	old_empresa_id 	character(2);
	old_prefijo 	character varying(4);
	old_factura_fiscal 	integer;
	old_estado 	character(1);
	old_usuario_id 	integer;
	old_fecha_registro 	timestamp;
	old_total_factura 	numeric(12,2);
	old_gravamen 	numeric(12,2);
	old_valor_cargos 	numeric(12,2);
	old_valor_cuota_paciente 	numeric(12,2);
	old_valor_cuota_moderadora 	numeric(12,2);
	old_descuento 	numeric(12,2);
	old_plan_id 	integer;
	old_tipo_id_tercero 	character varying(3);
	old_tercero_id 	character varying(32);
	old_sw_clase_factura 	character(1);
	old_concepto 	text;
	old_total_capitacion_real 	numeric(12,2);
	old_documento_id 	integer;
	old_tipo_factura 	character(1);
	old_documento_contable_id 	integer;
	old_saldo 	numeric(12,2);
	old_fecha_vencimiento_factura 	date;
	old_retencion_fuente 	numeric(12,2);
	old_sw_proceso 	integer;
	old_rango 	character varying(40);
	old_sw_imp_copia character(1);
  
	new_empresa_id 	character(2);
	new_prefijo 	character varying(4);
	new_factura_fiscal 	integer;
	new_estado 	character(1);
	new_usuario_id 	integer;
	new_fecha_registro 	timestamp;
	new_total_factura 	numeric(12,2);
	new_gravamen 	numeric(12,2);
	new_valor_cargos 	numeric(12,2);
	new_valor_cuota_paciente 	numeric(12,2);
	new_valor_cuota_moderadora 	numeric(12,2);
	new_descuento 	numeric(12,2);
	new_plan_id 	integer;
	new_tipo_id_tercero 	character varying(3);
	new_tercero_id 	character varying(32);
	new_sw_clase_factura 	character(1);
	new_concepto 	text;
	new_total_capitacion_real 	numeric(12,2);
	new_documento_id 	integer;
	new_tipo_factura 	character(1);
	new_documento_contable_id 	integer;
	new_saldo 	numeric(12,2);
	new_fecha_vencimiento_factura 	date;
	new_retencion_fuente 	numeric(12,2);
	new_sw_proceso 	integer;
	new_rango 	character varying(40);
	new_sw_imp_copia character(1);
	
	usuario_registro_ integer;
	fecha_registro timestamp;
  
BEGIN
  SELECT * INTO indicador
  FROM   acciones AC,
         novedad_x_acciones NA
  WHERE  AC.descripcion = TG_OP
  AND    NA.accion_id = AC.accion_id
  AND    NA.table_name = 'fac_facturas';
 
 /* Inicializar Variables */
 
  IF TG_OP = 'DELETE' THEN
   
	old_empresa_id 	:= OLD.empresa_id;
	old_prefijo 	:= OLD.prefijo;
	old_factura_fiscal 	:= OLD.factura_fiscal;
	old_estado 	:= OLD.estado;
	old_usuario_id 	:= OLD.usuario_id;
	old_fecha_registro 	:= OLD.fecha_registro;
	old_total_factura 	:= OLD.total_factura;
	old_gravamen 	:= OLD.gravamen;
	old_valor_cargos 	:= OLD.valor_cargos;
	old_valor_cuota_paciente 	:= OLD.valor_cuota_paciente;
	old_valor_cuota_moderadora 	:= OLD.valor_cuota_moderadora;
	old_descuento 	:= OLD.descuento;
	old_plan_id 	:= OLD.plan_id;
	old_tipo_id_tercero 	:= OLD.tipo_id_tercero;
	old_tercero_id 	:= OLD.tercero_id;
	old_sw_clase_factura 	:= OLD.sw_clase_factura;
	old_concepto 	:= OLD.concepto;
	old_total_capitacion_real 	:= OLD.total_capitacion_real;
	old_documento_id 	:= OLD.documento_id;
	old_tipo_factura 	:= OLD.tipo_factura;
	old_documento_contable_id 	:= OLD.documento_contable_id;
	old_saldo 	:= OLD.saldo;
	old_fecha_vencimiento_factura 	:= OLD.fecha_vencimiento_factura;
	old_retencion_fuente 	:= OLD.retencion_fuente;
	old_sw_proceso 	:= OLD.sw_proceso;
	old_rango 	:= OLD.rango;
	old_sw_imp_copia := OLD.sw_imp_copia;
	
	usuario_registro_ := OLD.usuario_id;
	fecha_registro := NOW();
  END IF; 
  
  IF TG_OP = 'UPDATE' THEN
	new_empresa_id 	:= NEW.empresa_id;
	new_prefijo 	:= NEW.prefijo;
	new_factura_fiscal 	:= NEW.factura_fiscal;
	new_estado 	:= NEW.estado;
	new_usuario_id 	:= NEW.usuario_id;
	new_fecha_registro 	:= NEW.fecha_registro;
	new_total_factura 	:= NEW.total_factura;
	new_gravamen 	:= NEW.gravamen;
	new_valor_cargos 	:= NEW.valor_cargos;
	new_valor_cuota_paciente 	:= NEW.valor_cuota_paciente;
	new_valor_cuota_moderadora 	:= NEW.valor_cuota_moderadora;
	new_descuento 	:= NEW.descuento;
	new_plan_id 	:= NEW.plan_id;
	new_tipo_id_tercero 	:= NEW.tipo_id_tercero;
	new_tercero_id 	:= NEW.tercero_id;
	new_sw_clase_factura 	:= NEW.sw_clase_factura;
	new_concepto 	:= NEW.concepto;
	new_total_capitacion_real 	:= NEW.total_capitacion_real;
	new_documento_id 	:= NEW.documento_id;
	new_tipo_factura 	:= NEW.tipo_factura;
	new_documento_contable_id 	:= NEW.documento_contable_id;
	new_saldo 	:= NEW.saldo;
	new_fecha_vencimiento_factura 	:= NEW.fecha_vencimiento_factura;
	new_retencion_fuente 	:= NEW.retencion_fuente;
	new_sw_proceso 	:= NEW.sw_proceso;
	new_rango 	:= NEW.rango;
	new_sw_imp_copia := NEW.sw_imp_copia;
	
	usuario_registro_ := NEW.usuario_id;
	fecha_registro := NOW();
  
	old_empresa_id 	:= OLD.empresa_id;
	old_prefijo 	:= OLD.prefijo;
	old_factura_fiscal 	:= OLD.factura_fiscal;
	old_estado 	:= OLD.estado;
	old_usuario_id 	:= OLD.usuario_id;
	old_fecha_registro 	:= OLD.fecha_registro;
	old_total_factura 	:= OLD.total_factura;
	old_gravamen 	:= OLD.gravamen;
	old_valor_cargos 	:= OLD.valor_cargos;
	old_valor_cuota_paciente 	:= OLD.valor_cuota_paciente;
	old_valor_cuota_moderadora 	:= OLD.valor_cuota_moderadora;
	old_descuento 	:= OLD.descuento;
	old_plan_id 	:= OLD.plan_id;
	old_tipo_id_tercero 	:= OLD.tipo_id_tercero;
	old_tercero_id 	:= OLD.tercero_id;
	old_sw_clase_factura 	:= OLD.sw_clase_factura;
	old_concepto 	:= OLD.concepto;
	old_total_capitacion_real 	:= OLD.total_capitacion_real;
	old_documento_id 	:= OLD.documento_id;
	old_tipo_factura 	:= OLD.tipo_factura;
	old_documento_contable_id 	:= OLD.documento_contable_id;
	old_saldo 	:= OLD.saldo;
	old_fecha_vencimiento_factura 	:= OLD.fecha_vencimiento_factura;
	old_retencion_fuente 	:= OLD.retencion_fuente;
	old_sw_proceso 	:= OLD.sw_proceso;
	old_rango 	:= OLD.rango;
	old_sw_imp_copia := OLD.sw_imp_copia;
  END IF; 
  
  IF TG_OP = 'INSERT' THEN
	new_empresa_id 	:= NEW.empresa_id;
	new_prefijo 	:= NEW.prefijo;
	new_factura_fiscal 	:= NEW.factura_fiscal;
	new_estado 	:= NEW.estado;
	new_usuario_id 	:= NEW.usuario_id;
	new_fecha_registro 	:= NEW.fecha_registro;
	new_total_factura 	:= NEW.total_factura;
	new_gravamen 	:= NEW.gravamen;
	new_valor_cargos 	:= NEW.valor_cargos;
	new_valor_cuota_paciente 	:= NEW.valor_cuota_paciente;
	new_valor_cuota_moderadora 	:= NEW.valor_cuota_moderadora;
	new_descuento 	:= NEW.descuento;
	new_plan_id 	:= NEW.plan_id;
	new_tipo_id_tercero 	:= NEW.tipo_id_tercero;
	new_tercero_id 	:= NEW.tercero_id;
	new_sw_clase_factura 	:= NEW.sw_clase_factura;
	new_concepto 	:= NEW.concepto;
	new_total_capitacion_real 	:= NEW.total_capitacion_real;
	new_documento_id 	:= NEW.documento_id;
	new_tipo_factura 	:= NEW.tipo_factura;
	new_documento_contable_id 	:= NEW.documento_contable_id;
	new_saldo 	:= NEW.saldo;
	new_fecha_vencimiento_factura 	:= NEW.fecha_vencimiento_factura;
	new_retencion_fuente 	:= NEW.retencion_fuente;
	new_sw_proceso 	:= NEW.sw_proceso;
	new_rango 	:= NEW.rango;
	new_sw_imp_copia := NEW.sw_imp_copia;
	
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

     usuario_registro

    )
    VALUES
    (
      indicador.accion_id,
      indicador.novedad_indicador_id,
      'fac_facturas',
      
      'empresa_id',
      new_empresa_id,
      old_empresa_id,
      
      'prefijo',    
      new_prefijo,
      old_prefijo,
      
      'factura_fiscal',
      new_factura_fiscal,
      old_factura_fiscal,
      
      'estado',
      new_estado,
      old_estado,
      
      'usuario_id',
      new_usuario_id,
      old_usuario_id,
      
      'fecha_registro',
      new_fecha_registro,
      old_fecha_registro,
      
      'total_factura',
      new_total_factura,
      old_total_factura,
      
      'gravamen',
      new_gravamen,
      old_gravamen,
      
      'valor_cargos',
      new_valor_cargos,
      old_valor_cargos,

      'valor_cuota_paciente',
      new_valor_cuota_paciente,
      old_valor_cuota_paciente,

      'valor_cuota_moderadora',
      new_valor_cuota_moderadora,
      old_valor_cuota_moderadora,

      'descuento',
      new_descuento,
      old_descuento,

      'plan_id',
      new_plan_id,
      old_plan_id,

      'tipo_id_tercero',
      new_tipo_id_tercero,
      old_tipo_id_tercero,

      'tercero_id',
      new_tercero_id,
      old_tercero_id,

      'sw_clase_factura',
      new_sw_clase_factura,
      old_sw_clase_factura,

      'concepto',
      new_concepto,
      old_concepto,

      'total_capitacion_real',
      new_total_capitacion_real,
      old_total_capitacion_real,

      'documento_id',
      new_documento_id,
      old_documento_id,

      'tipo_factura',
      new_tipo_factura,
      old_tipo_factura,

      'documento_contable_id',
      new_documento_contable_id,
      old_documento_contable_id,

      'saldo',
      new_saldo,
      old_saldo,

      'fecha_vencimiento_factura',
      new_fecha_vencimiento_factura,
      old_fecha_vencimiento_factura,

      'retencion_fuente',
      new_retencion_fuente,
      old_retencion_fuente,

      'sw_proceso',
      new_sw_proceso,
      old_sw_proceso,

      'rango',
      new_rango,
      old_rango,

      'sw_imp_copia',
      new_sw_imp_copia,
      old_sw_imp_copia,
	  
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

ALTER FUNCTION public.au_fac_facturas()
  OWNER TO "admin";
  
  CREATE TRIGGER aut_auditoria_fac_facturas
  AFTER INSERT OR UPDATE OR DELETE
  ON public.fac_facturas
  FOR EACH ROW
  EXECUTE PROCEDURE au_fac_facturas();

  
/*
*AUDITORIA DEVOLUCION VENTA
*/
  CREATE OR REPLACE FUNCTION public.au_rc_devoluciones_ventas()
RETURNS trigger AS
$$
DECLARE

  indicador RECORD;
  columnas RECORD;
  
	old_devolucionv_id 	integer;
	old_prefijo 	character varying(4);
	old_empresa_id 	character(2);
	old_centro_utilidad 	character(2);
	old_prefijo_factura 	character varying(4);
	old_factura_fiscal 	integer;
	old_total_devolucion 	numeric(12,2);
	old_estado 	character(1);
	old_fecha_registro 	timestamp;
	old_bodegas_doc_id 	integer;
	old_bodegas_numeracion 	integer;
	old_usuario_id integer;
  
	new_devolucionv_id 	integer;
	new_prefijo 	character varying(4);
	new_empresa_id 	character(2);
	new_centro_utilidad 	character(2);
	new_prefijo_factura 	character varying(4);
	new_factura_fiscal 	integer;
	new_total_devolucion 	numeric(12,2);
	new_estado 	character(1);
	new_fecha_registro 	timestamp;
	new_bodegas_doc_id 	integer;
	new_bodegas_numeracion 	integer;
	new_usuario_id integer;
	
	usuario_registro_ integer;
	fecha_registro timestamp;
  
BEGIN
  SELECT * INTO indicador
  FROM   acciones AC,
         novedad_x_acciones NA
  WHERE  AC.descripcion = TG_OP
  AND    NA.accion_id = AC.accion_id
  AND    NA.table_name = 'rc_devoluciones_ventas';
 
 /* Inicializar Variables */
 
  IF TG_OP = 'DELETE' THEN
   
	old_devolucionv_id 	:= OLD.devolucionv_id;
	old_prefijo 	:= OLD.prefijo; 	
	old_empresa_id 	:= OLD.empresa_id; 	
	old_centro_utilidad 	:= OLD.centro_utilidad; 	
	old_prefijo_factura 	:= OLD.prefijo_factura;	
	old_factura_fiscal 	:= OLD.factura_fiscal;
	old_total_devolucion 	:= OLD.total_devolucion;
	old_estado 	:= OLD.estado;
	old_fecha_registro 	:= OLD.fecha_registro;
	old_bodegas_doc_id 	:= OLD.bodegas_doc_id;
	old_bodegas_numeracion 	:= OLD.bodegas_numeracion;
	old_usuario_id := OLD.usuario_id;
	
	usuario_registro_ := OLD.usuario_id;
	fecha_registro := NOW();
  END IF; 
  
  IF TG_OP = 'UPDATE' THEN
	new_devolucionv_id 	:= NEW.devolucionv_id;
	new_prefijo 	:= NEW.prefijo; 	
	new_empresa_id 	:= NEW.empresa_id; 	
	new_centro_utilidad 	:= NEW.centro_utilidad; 	
	new_prefijo_factura 	:= NEW.prefijo_factura;	
	new_factura_fiscal 	:= NEW.factura_fiscal;
	new_total_devolucion 	:= NEW.total_devolucion;
	new_estado 	:= NEW.estado;
	new_fecha_registro 	:= NEW.fecha_registro;
	new_bodegas_doc_id 	:= NEW.bodegas_doc_id;
	new_bodegas_numeracion 	:= NEW.bodegas_numeracion;
	new_usuario_id := NEW.usuario_id;
	
	usuario_registro_ := NEW.usuario_id;
	fecha_registro := NOW();
  
	old_devolucionv_id 	:= OLD.devolucionv_id;
	old_prefijo 	:= OLD.prefijo; 	
	old_empresa_id 	:= OLD.empresa_id; 	
	old_centro_utilidad 	:= OLD.centro_utilidad; 	
	old_prefijo_factura 	:= OLD.prefijo_factura;	
	old_factura_fiscal 	:= OLD.factura_fiscal;
	old_total_devolucion 	:= OLD.total_devolucion;
	old_estado 	:= OLD.estado;
	old_fecha_registro 	:= OLD.fecha_registro;
	old_bodegas_doc_id 	:= OLD.bodegas_doc_id;
	old_bodegas_numeracion 	:= OLD.bodegas_numeracion;
	old_usuario_id := OLD.usuario_id;
  END IF; 
  
  IF TG_OP = 'INSERT' THEN
	new_devolucionv_id 	:= NEW.devolucionv_id;
	new_prefijo 	:= NEW.prefijo; 	
	new_empresa_id 	:= NEW.empresa_id; 	
	new_centro_utilidad 	:= NEW.centro_utilidad; 	
	new_prefijo_factura 	:= NEW.prefijo_factura;	
	new_factura_fiscal 	:= NEW.factura_fiscal;
	new_total_devolucion 	:= NEW.total_devolucion;
	new_estado 	:= NEW.estado;
	new_fecha_registro 	:= NEW.fecha_registro;
	new_bodegas_doc_id 	:= NEW.bodegas_doc_id;
	new_bodegas_numeracion 	:= NEW.bodegas_numeracion;
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

      campo_12, 
      nuevo_valor_12,
      antiguo_valor_12,

     usuario_registro

    )
    VALUES
    (
      indicador.accion_id,
      indicador.novedad_indicador_id,
      'rc_devoluciones_ventas',
      
      'devolucionv_id',
      new_devolucionv_id,
      old_devolucionv_id,
      
      'prefijo',
      new_prefijo,
      old_prefijo,
      
      'empresa_id',
      new_empresa_id,
      old_empresa_id,
      
      'centro_utilidad',
      new_centro_utilidad,
      old_centro_utilidad,
      
      'prefijo_factura',
      new_prefijo_factura,
      old_prefijo_factura,
      
      'factura_fiscal',
      new_factura_fiscal,
      old_factura_fiscal,
      
      'total_devolucion',
      new_total_devolucion,
      old_total_devolucion,
      
      'estado',
      new_estado,
      old_estado,
      
      'fecha_registro',
      new_fecha_registro,
      old_fecha_registro,
      
      'bodegas_doc_id',
      new_bodegas_doc_id,
      old_bodegas_doc_id,
      
      'bodegas_numeracion',
      new_bodegas_numeracion,
      old_bodegas_numeracion,
      
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

ALTER FUNCTION public.au_rc_devoluciones_ventas()
  OWNER TO "admin";
  
  CREATE TRIGGER aut_auditoria_rc_devoluciones_ventas
  AFTER INSERT OR UPDATE OR DELETE
  ON public.rc_devoluciones_ventas
  FOR EACH ROW
  EXECUTE PROCEDURE au_rc_devoluciones_ventas();
