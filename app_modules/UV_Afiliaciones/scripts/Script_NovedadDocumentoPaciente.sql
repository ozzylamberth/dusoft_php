CREATE OR REPLACE function ingresar_novedades_datos_afiliados() RETURNS TRIGGER AS $$
DECLARE
  indice NUMERIC;
  estamento RECORD;
  sgsss CHARACTER VARYING(12);
  eps_afiliacion INTEGER;
BEGIN
  eps_afiliacion := (SELECT MAX(eps_afiliacion_id) FROM eps_afiliados
                WHERE  afiliado_tipo_id = OLD.afiliado_tipo_id
                AND    afiliado_id = OLD.afiliado_id);
                
  SELECT  estamento_id INTO estamento
  FROM    eps_afiliados_cotizantes
  WHERE   afiliado_tipo_id = NEW.afiliado_tipo_id
  AND     afiliado_id = NEW.afiliado_id
  AND     eps_afiliacion_id = eps_afiliacion
  GROUP BY estamento_id;
                
  sgsss := (SELECT codigo_sgsss FROM empresas WHERE empresa_id = '01');             
  IF NEW.afiliado_tipo_id != OLD.afiliado_tipo_id OR NEW.afiliado_id != OLD.afiliado_id OR NEW.fecha_nacimiento != OLD.fecha_nacimiento THEN
    indice := (SELECT NEXTVAL('eps_novedades_registros_eps_novedad_registro_id_seq')); 
    INSERT INTO eps_novedades_registros( 
      eps_novedad_registro_id,
      estamento_id ,
      eps_afiliacion_id,
      codigo_sgss_entidad ,
      afiliado_tipo_id ,
      afiliado_id ,
      primer_apellido ,
      segundo_apellido ,
      primer_nombre ,
      segundo_nombre ,
      fecha_nacimiento ,
      codigo_novedad ,
      fecha_inicio_novedad ,
      nuevo_valor_1,
      nuevo_valor_2,
      nuevo_valor_3,
      antiguo_valor_1,
      antiguo_valor_2,
      antiguo_valor_3,
      usuario_registro)
    VALUES ( 
      indice,
      estamento.estamento_id,
      eps_afiliacion,
      sgsss,
      OLD.afiliado_tipo_id,
      OLD.afiliado_id,
      OLD.primer_apellido ,
      OLD.segundo_apellido ,
      OLD.primer_nombre ,
      OLD.segundo_nombre ,
      OLD.fecha_nacimiento,
      'N01',
      NOW(),
      NEW.afiliado_tipo_id,
      NEW.afiliado_id,
      NEW.fecha_nacimiento,
      OLD.afiliado_tipo_id,
      OLD.afiliado_id,
      OLD.fecha_nacimiento,
      NEW.usuario_ultima_actualizacion 	
    );
    NEW.accion_ultima_actualizacion = indice;
  END IF;
  
  
  /*IF NEW.afiliado_id != OLD.afiliado_id OR NEW.afiliado_tipo_id != OLD.afiliado_tipo_id THEN
    indice := (SELECT NEXTVAL('eps_novedades_registros_eps_novedad_registro_id_seq')); 
    INSERT INTO eps_novedades_registros( 
      eps_novedad_registro_id,
      estamento_id ,
      eps_afiliacion_id,
      codigo_sgss_entidad ,
      afiliado_tipo_id ,
      afiliado_id ,
      primer_apellido ,
      segundo_apellido ,
      primer_nombre ,
      segundo_nombre ,
      fecha_nacimiento ,
      codigo_novedad ,
      fecha_inicio_novedad ,
      nuevo_valor_1,
      nuevo_valor_2,
      nuevo_valor_3,
      antiguo_valor_1,
      antiguo_valor_2,
      antiguo_valor_3,
      usuario_registro)
    VALUES ( 
      indice,
      estamento.estamento_id,
      eps_afiliacion,
      sgsss,
      NEW.afiliado_tipo_id,
      NEW.afiliado_id,
      OLD.primer_apellido ,
      OLD.segundo_apellido ,
      OLD.primer_nombre ,
      OLD.segundo_nombre ,
      NEW.fecha_nacimiento,
      'N20',
      NOW(),
      NEW.afiliado_tipo_id,
      NEW.afiliado_id,
      NEW.fecha_nacimiento,
      OLD.afiliado_tipo_id,
      OLD.afiliado_id,
      OLD.fecha_nacimiento,
      NEW.usuario_ultima_actualizacion 	
    );
    NEW.accion_ultima_actualizacion = indice;
  END IF;*/
  
  
  IF NEW.primer_nombre != OLD.primer_nombre OR NEW.segundo_nombre != OLD.segundo_nombre THEN
    indice := (SELECT NEXTVAL('eps_novedades_registros_eps_novedad_registro_id_seq')); 
    INSERT INTO eps_novedades_registros( 
      eps_novedad_registro_id,
      estamento_id ,
      eps_afiliacion_id,
      codigo_sgss_entidad ,
      afiliado_tipo_id ,
      afiliado_id ,
      primer_apellido ,
      segundo_apellido ,
      primer_nombre ,
      segundo_nombre ,
      fecha_nacimiento ,
      codigo_novedad ,
      fecha_inicio_novedad ,
      nuevo_valor_1,
      nuevo_valor_2,
      antiguo_valor_1,
      antiguo_valor_2,
      usuario_registro)
    VALUES ( 
      indice,
      estamento.estamento_id,
      eps_afiliacion,
      sgsss,
      NEW.afiliado_tipo_id,
      NEW.afiliado_id,
      NEW.primer_apellido ,
      NEW.segundo_apellido ,
      OLD.primer_nombre ,
      OLD.segundo_nombre ,
      NEW.fecha_nacimiento,
      'N02',
      NOW(),
      NEW.primer_nombre,
      NEW.segundo_nombre,
      OLD.primer_nombre,
      OLD.segundo_nombre,
      NEW.usuario_ultima_actualizacion 	
    );
    NEW.accion_ultima_actualizacion = indice;
  END IF;
  
  IF NEW.primer_apellido != OLD.primer_apellido OR NEW.segundo_apellido != OLD.segundo_apellido THEN
    indice := (SELECT NEXTVAL('eps_novedades_registros_eps_novedad_registro_id_seq')); 
    INSERT INTO eps_novedades_registros( 
      eps_novedad_registro_id,
      estamento_id ,
      eps_afiliacion_id,
      codigo_sgss_entidad ,
      afiliado_tipo_id ,
      afiliado_id ,
      primer_apellido ,
      segundo_apellido ,
      primer_nombre ,
      segundo_nombre ,
      fecha_nacimiento ,
      codigo_novedad ,
      fecha_inicio_novedad ,
      nuevo_valor_1,
      nuevo_valor_2,
      antiguo_valor_1,
      antiguo_valor_2,
      usuario_registro)
    VALUES ( 
      indice,
      estamento.estamento_id,
      eps_afiliacion,
      sgsss,
      NEW.afiliado_tipo_id,
      NEW.afiliado_id,
      OLD.primer_apellido ,
      OLD.segundo_apellido ,
      NEW.primer_nombre ,
      NEW.segundo_nombre ,
      NEW.fecha_nacimiento,
      'N03',
      NOW(),
      NEW.primer_apellido,
      NEW.segundo_apellido,
      OLD.primer_apellido,
      OLD.segundo_apellido,
      NEW.usuario_ultima_actualizacion 	
    );
    NEW.accion_ultima_actualizacion = indice;
  END IF;
  
  IF NEW.tipo_dpto_id != OLD.tipo_dpto_id OR NEW.tipo_mpio_id != OLD.tipo_mpio_id THEN
    indice := (SELECT NEXTVAL('eps_novedades_registros_eps_novedad_registro_id_seq')); 
    INSERT INTO eps_novedades_registros( 
      eps_novedad_registro_id,
      estamento_id ,
      eps_afiliacion_id,
      codigo_sgss_entidad ,
      afiliado_tipo_id ,
      afiliado_id ,
      primer_apellido ,
      segundo_apellido ,
      primer_nombre ,
      segundo_nombre ,
      fecha_nacimiento ,
      codigo_novedad ,
      fecha_inicio_novedad ,
      nuevo_valor_1,
      nuevo_valor_2,
      antiguo_valor_1,
      antiguo_valor_2,
      usuario_registro)
    VALUES ( 
      indice,
      estamento.estamento_id,
      eps_afiliacion,
      sgsss,
      NEW.afiliado_tipo_id,
      NEW.afiliado_id,
      NEW.primer_apellido ,
      NEW.segundo_apellido ,
      NEW.primer_nombre ,
      NEW.segundo_nombre ,
      NEW.fecha_nacimiento,
      'N04',
      NOW(),
      NEW.tipo_dpto_id,
      NEW.tipo_mpio_id,
      OLD.tipo_dpto_id,
      OLD.tipo_mpio_id,
      NEW.usuario_ultima_actualizacion 	
    );
    NEW.accion_ultima_actualizacion = indice;
  END IF;
  
  IF NEW.tipo_sexo_id != OLD.tipo_sexo_id THEN
    indice := (SELECT NEXTVAL('eps_novedades_registros_eps_novedad_registro_id_seq')); 
    INSERT INTO eps_novedades_registros( 
      eps_novedad_registro_id,
      estamento_id ,
      eps_afiliacion_id,
      codigo_sgss_entidad ,
      afiliado_tipo_id ,
      afiliado_id ,
      primer_apellido ,
      segundo_apellido ,
      primer_nombre ,
      segundo_nombre ,
      fecha_nacimiento ,
      codigo_novedad ,
      fecha_inicio_novedad ,
      nuevo_valor_1,
      antiguo_valor_1,
      usuario_registro)
    VALUES ( 
      indice,
      estamento.estamento_id,
      eps_afiliacion,
      sgsss,
      NEW.afiliado_tipo_id,
      NEW.afiliado_id,
      NEW.primer_apellido ,
      NEW.segundo_apellido ,
      NEW.primer_nombre ,
      NEW.segundo_nombre ,
      NEW.fecha_nacimiento,
      'N17',
      NOW(),
      NEW.tipo_sexo_id,
      OLD.tipo_sexo_id,
      NEW.usuario_ultima_actualizacion 	
    );
    NEW.accion_ultima_actualizacion = indice;
  END IF;
  
  IF NEW.zona_residencia != OLD.zona_residencia THEN
    indice := (SELECT NEXTVAL('eps_novedades_registros_eps_novedad_registro_id_seq')); 
    INSERT INTO eps_novedades_registros( 
      eps_novedad_registro_id,
      estamento_id ,
      eps_afiliacion_id,
      codigo_sgss_entidad ,
      afiliado_tipo_id ,
      afiliado_id ,
      primer_apellido ,
      segundo_apellido ,
      primer_nombre ,
      segundo_nombre ,
      fecha_nacimiento ,
      codigo_novedad ,
      fecha_inicio_novedad ,
      nuevo_valor_1,
      antiguo_valor_1,
      usuario_registro)
    VALUES ( 
      indice,
      estamento.estamento_id,
      eps_afiliacion,
      sgsss,
      NEW.afiliado_tipo_id,
      NEW.afiliado_id,
      NEW.primer_apellido ,
      NEW.segundo_apellido ,
      NEW.primer_nombre ,
      NEW.segundo_nombre ,
      NEW.fecha_nacimiento,
      'N19',
      NOW(),
      NEW.zona_residencia,
      OLD.zona_residencia,
      NEW.usuario_ultima_actualizacion 	
    );
    NEW.accion_ultima_actualizacion = indice;
  END IF;
  
  IF NEW.fecha_afiliacion_sgss != OLD.fecha_afiliacion_sgss THEN
    indice := (SELECT NEXTVAL('eps_novedades_registros_eps_novedad_registro_id_seq')); 
    INSERT INTO eps_novedades_registros( 
      eps_novedad_registro_id,
      estamento_id ,
      eps_afiliacion_id,
      codigo_sgss_entidad ,
      afiliado_tipo_id ,
      afiliado_id ,
      primer_apellido ,
      segundo_apellido ,
      primer_nombre ,
      segundo_nombre ,
      fecha_nacimiento ,
      codigo_novedad ,
      fecha_inicio_novedad ,
      nuevo_valor_1,
      antiguo_valor_1,
      usuario_registro)
    VALUES ( 
      indice,
      estamento.estamento_id,
      eps_afiliacion,
      sgsss,
      NEW.afiliado_tipo_id,
      NEW.afiliado_id,
      NEW.primer_apellido ,
      NEW.segundo_apellido ,
      NEW.primer_nombre ,
      NEW.segundo_nombre ,
      NEW.fecha_nacimiento,
      'N18',
      NOW(),
      TO_CHAR(NEW.fecha_afiliacion_sgss,'DD/MM/YYYY'),
      TO_CHAR(OLD.fecha_afiliacion_sgss,'DD/MM/YYYY'),
      NEW.usuario_ultima_actualizacion 	
    );
     NEW.accion_ultima_actualizacion = indice;
  END IF;
  RETURN NEW;
END;
$$ Language 'plpgsql';