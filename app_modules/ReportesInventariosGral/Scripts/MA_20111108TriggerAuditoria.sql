 CREATE OR REPLACE FUNCTION public.au_inv_bodegas_userpermisos()
RETURNS trigger AS
$$
DECLARE

  indicador RECORD;
  columnas RECORD;
  
	old_documento_id 	integer;
	old_empresa_id 	character(2);
	old_centro_utilidad 	character(2);
	old_bodega 	character(2);
	old_usuario_id integer;
	
	new_documento_id 	integer;
	new_empresa_id 	character(2);
	new_centro_utilidad 	character(2);
	new_bodega 	character(2);
	new_usuario_id integer;
	
	usuario_registro_ integer;
	fecha_registro timestamp;
  
BEGIN
  SELECT * INTO indicador
  FROM   acciones AC,
         novedad_x_acciones NA
  WHERE  AC.descripcion = TG_OP
  AND    NA.accion_id = AC.accion_id
  AND    NA.table_name = 'inv_bodegas_userpermisos';
 
 /* Inicializar Variables */
 
  IF TG_OP = 'DELETE' THEN
   
	old_documento_id 	:= OLD.documento_id;
	old_empresa_id 	:= OLD.empresa_id;
	old_centro_utilidad 	:= OLD.centro_utilidad;
	old_bodega 	:= OLD.bodega;
	old_usuario_id := OLD.usuario_id;
	
	usuario_registro_ := 2;
	fecha_registro := NOW();
  END IF; 
  
  IF TG_OP = 'UPDATE' THEN
	new_documento_id 	:= NEW.documento_id;
	new_empresa_id 	:= NEW.empresa_id;
	new_centro_utilidad 	:= NEW.centro_utilidad;
	new_bodega 	:= NEW.bodega;
	new_usuario_id := NEW.usuario_id;
	
	usuario_registro_ := 2;
	fecha_registro := NOW();
  
	old_documento_id 	:= OLD.documento_id;
	old_empresa_id 	:= OLD.empresa_id;
	old_centro_utilidad 	:= OLD.centro_utilidad;
	old_bodega 	:= OLD.bodega;
	old_usuario_id := OLD.usuario_id;
  END IF; 
  
  IF TG_OP = 'INSERT' THEN
	new_documento_id 	:= NEW.documento_id;
	new_empresa_id 	:= NEW.empresa_id;
	new_centro_utilidad 	:= NEW.centro_utilidad;
	new_bodega 	:= NEW.bodega;
	new_usuario_id := NEW.usuario_id;
	
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
      
      usuario_registro

    )
    VALUES
    (
      indicador.accion_id,
      indicador.novedad_indicador_id,
      'inv_bodegas_userpermisos',
      
      'documento_id',
      new_documento_id,
      old_documento_id,
	  
      'empresa_id',
      new_empresa_id,
      old_empresa_id,
      
      'centro_utilidad',
      new_centro_utilidad,
      old_centro_utilidad,
            
      'bodega',
      new_bodega,
      old_bodega,
      
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

ALTER FUNCTION public.au_inv_bodegas_userpermisos()
  OWNER TO "admin";
  
  CREATE TRIGGER aut_auditoria_inv_bodegas_userpermisos
  AFTER INSERT OR UPDATE OR DELETE
  ON public.inv_bodegas_userpermisos
  FOR EACH ROW
  EXECUTE PROCEDURE au_inv_bodegas_userpermisos();

  /*
  * AUDITORIA inv_bodegas_documentos
  */
  CREATE OR REPLACE FUNCTION public.au_inv_bodegas_documentos()
RETURNS trigger AS
$$
DECLARE

  indicador RECORD;
  columnas RECORD;
  
	old_documento_id 	integer;
	old_empresa_id 	character(2);
	old_centro_utilidad character(2);	
	old_bodega 	character(2);
	old_bodegas_doc_id 	integer;
	old_sw_estado 	character(1);
	old_sw_genera_auto_despacho 	character(1);
	old_usuario_defecto_despacho integer;
	
	new_documento_id 	integer;
	new_empresa_id 	character(2);
	new_centro_utilidad character(2);	
	new_bodega 	character(2);
	new_bodegas_doc_id 	integer;
	new_sw_estado 	character(1);
	new_sw_genera_auto_despacho 	character(1);
	new_usuario_defecto_despacho integer;
	
	usuario_registro_ integer;
	fecha_registro timestamp;
  
BEGIN
  SELECT * INTO indicador
  FROM   acciones AC,
         novedad_x_acciones NA
  WHERE  AC.descripcion = TG_OP
  AND    NA.accion_id = AC.accion_id
  AND    NA.table_name = 'inv_bodegas_documentos';
 
 /* Inicializar Variables */
 
  IF TG_OP = 'DELETE' THEN
   
	old_documento_id 	:= OLD.documento_id;
	old_empresa_id 	:= OLD.empresa_id; 	
	old_centro_utilidad := OLD.centro_utilidad;
	old_bodega 	:= OLD.bodega;
	old_bodegas_doc_id 	:= OLD.bodegas_doc_id;
	old_sw_estado 	:= OLD.sw_estado;
	old_sw_genera_auto_despacho 	:= OLD.sw_genera_auto_despacho;
	old_usuario_defecto_despacho := OLD.usuario_defecto_despacho;
	
	usuario_registro_ := 2;
	fecha_registro := NOW();
  END IF; 
  
  IF TG_OP = 'UPDATE' THEN
	new_documento_id 	:= NEW.documento_id;
	new_empresa_id 	:= NEW.empresa_id; 	
	new_centro_utilidad := NEW.centro_utilidad;
	new_bodega 	:= NEW.bodega;
	new_bodegas_doc_id 	:= NEW.bodegas_doc_id;
	new_sw_estado 	:= NEW.sw_estado;
	new_sw_genera_auto_despacho 	:= NEW.sw_genera_auto_despacho;
	new_usuario_defecto_despacho := NEW.usuario_defecto_despacho;
	
	usuario_registro_ := 2;
	fecha_registro := NOW();
  
	old_documento_id 	:= OLD.documento_id;
	old_empresa_id 	:= OLD.empresa_id; 	
	old_centro_utilidad := OLD.centro_utilidad;
	old_bodega 	:= OLD.bodega;
	old_bodegas_doc_id 	:= OLD.bodegas_doc_id;
	old_sw_estado 	:= OLD.sw_estado;
	old_sw_genera_auto_despacho 	:= OLD.sw_genera_auto_despacho;
	old_usuario_defecto_despacho := OLD.usuario_defecto_despacho;
  END IF; 
  
  IF TG_OP = 'INSERT' THEN
	new_documento_id 	:= NEW.documento_id;
	new_empresa_id 	:= NEW.empresa_id; 	
	new_centro_utilidad := NEW.centro_utilidad;
	new_bodega 	:= NEW.bodega;
	new_bodegas_doc_id 	:= NEW.bodegas_doc_id;
	new_sw_estado 	:= NEW.sw_estado;
	new_sw_genera_auto_despacho 	:= NEW.sw_genera_auto_despacho;
	new_usuario_defecto_despacho := NEW.usuario_defecto_despacho;
	
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
      'inv_bodegas_documentos',
      
      'documento_id',
      new_documento_id,
      old_documento_id,
	  
      'empresa_id',
      new_empresa_id,
      old_empresa_id,
      
      'centro_utilidad',
      new_centro_utilidad,
      old_centro_utilidad,
            
      'bodega',
      new_bodega,
      old_bodega,
      
      'bodegas_doc_id',
      new_bodegas_doc_id,
      old_bodegas_doc_id,
      
      'sw_estado',
      new_sw_estado,
      old_sw_estado,
      
      'sw_genera_auto_despacho',
      new_sw_genera_auto_despacho,
      old_sw_genera_auto_despacho,
      
      'usuario_defecto_despacho',
      new_usuario_defecto_despacho,
      old_usuario_defecto_despacho,
      
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

ALTER FUNCTION public.au_inv_bodegas_documentos()
  OWNER TO "admin";
  
  CREATE TRIGGER aut_auditoria_inv_bodegas_documentos
  AFTER INSERT OR UPDATE OR DELETE
  ON public.inv_bodegas_documentos
  FOR EACH ROW
  EXECUTE PROCEDURE au_inv_bodegas_documentos();
