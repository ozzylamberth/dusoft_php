CREATE OR REPLACE FUNCTION public.aut_cuadre_automatico_tf()
RETURNS trigger AS
$$
DECLARE
	toma_fisica RECORD;
	datos_tomas_1 RECORD;
	datos_tomas_2 RECORD;
		
BEGIN

	SELECT 
	numero_conteos
	INTO toma_fisica
	FROM
	inv_toma_fisica
	WHERE TRUE
	AND		toma_fisica_id=OLD.toma_fisica_id;
	
	
	
	/* OBTENER DATOS EN CASO DE SER 1 CONTEO PARA CUADRE AUTOMATICO */
	SELECT 	etiqueta,
	etiqueta_x_producto,
	codigo_producto,
	empresa_id,
	centro_utilidad,
	bodega,
	costo,
	existencia,
	fecha_vencimiento,
	lote,
	conteo_1,
	diferencia_1,
	validacion_conteo_1
	INTO datos_tomas_1
	FROM   tomas_fisicas
	WHERE  TRUE
	AND		toma_fisica_id=OLD.toma_fisica_id
	AND		etiqueta=OLD.etiqueta
	AND      nueva_existencia IS NULL
	AND      conteo_1 IS NOT NULL
	AND      conteo_2 IS NULL 
	AND		(abs(diferencia_1::integer)) =0;
	
	
	/* OBTENER DATOS EN CASO DE SER DOS CONTEOS O MAS PARA CUADRE AUTOMATICO */
	SELECT
	etiqueta,
	codigo_producto,
	empresa_id,
	centro_utilidad,
	bodega,
	costo,
	existencia,
	fecha_vencimiento,
	lote,
	conteo_1,
	conteo_2,
	diferencia_1,
	diferencia_2,
	diferencia_1con2,
	validacion_conteo_2
	INTO datos_tomas_2
	FROM    tomas_fisicas
	WHERE  TRUE
	AND		toma_fisica_id=OLD.toma_fisica_id
	AND		etiqueta=OLD.etiqueta
	AND       nueva_existencia IS NULL
	AND       conteo_2 IS NOT NULL
	AND       conteo_3 IS NULL
	AND		(abs(diferencia_1con2::integer)) =0;
	
	
	IF OLD.usuario_valido IS NULL AND NEW.usuario_valido IS NOT NULL THEN
			IF OLD.num_conteo = '1'::integer AND toma_fisica.numero_conteos ='1'::integer THEN
				IF datos_tomas_1.etiqueta IS NOT NULL THEN
					INSERT INTO inv_toma_fisica_update
					(
					toma_fisica_id, 	
					etiqueta, 	
					num_conteo, 	
					sw_manual,	
					empresa_id, 	
					centro_utilidad, 	
					bodega, 	
					codigo_producto, 	
					existencia, 	
					nueva_existencia, 	
					costo, 
					fecha_vencimiento, 	
					lote
					)
					VALUES
					(
					OLD.toma_fisica_id,
					OLD.etiqueta,
					OLD.num_conteo,
					'0',
					datos_tomas_1.empresa_id,
					datos_tomas_1.centro_utilidad,
					datos_tomas_1.bodega,
					datos_tomas_1.codigo_producto,
					datos_tomas_1.existencia,
					datos_tomas_1.conteo_1,
					datos_tomas_1.costo,
					datos_tomas_1.fecha_vencimiento,
					datos_tomas_1.lote
					);
				END IF;
			END IF;
	
			IF NEW.num_conteo = '2'::integer THEN
				IF datos_tomas_2.etiqueta IS NOT NULL THEN
					INSERT INTO inv_toma_fisica_update
					(
					toma_fisica_id, 	
					etiqueta, 	
					num_conteo, 	
					sw_manual,	
					empresa_id, 	
					centro_utilidad, 	
					bodega, 	
					codigo_producto, 	
					existencia, 	
					nueva_existencia, 	
					costo, 
					fecha_vencimiento, 	
					lote
					)
					VALUES
					(
					OLD.toma_fisica_id,
					datos_tomas_2.etiqueta,
					OLD.num_conteo,
					'0',
					datos_tomas_2.empresa_id,
					datos_tomas_2.centro_utilidad,
					datos_tomas_2.bodega,
					datos_tomas_2.codigo_producto,
					datos_tomas_2.existencia,
					datos_tomas_2.conteo_2,
					datos_tomas_2.costo,
					datos_tomas_2.fecha_vencimiento,
					datos_tomas_2.lote
					);
				END IF;
			END IF;
	END IF;

	return NEW;

END;
$$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER;

ALTER FUNCTION public.aut_cuadre_automatico_tf()
OWNER TO "admin";

ALTER TABLE public.inv_toma_fisica_conteos
  DISABLE TRIGGER tgg_inv_toma_fisica_update_conteos;

  CREATE TRIGGER aut_cuadres_automaticos_validaciones
  AFTER UPDATE
  ON public.inv_toma_fisica_conteos
  FOR EACH ROW
  EXECUTE PROCEDURE public.aut_cuadre_automatico_tf();

COMMENT ON TRIGGER aut_cuadres_automaticos_validaciones
  ON public.inv_toma_fisica_conteos
  IS 'Trigger que permite Cuadrar Conteos, si estos ya no presentan diferencias en el segundo';

  ALTER TABLE public.existencias_bodegas_lote_fv
  ADD COLUMN ubicacion_id integer;

COMMENT ON COLUMN public.existencias_bodegas_lote_fv.ubicacion_id
  IS 'Donde se ingresa la ubicacion id de este producto que se encuentra en la tabla bodegas_ubicaciones';

ALTER TABLE public.existencias_bodegas_lote_fv
  ADD COLUMN fecha_registro timestamp(1) WITHOUT TIME ZONE NOT NULL DEFAULT NOW();

COMMENT ON COLUMN public.existencias_bodegas_lote_fv.fecha_registro
  IS 'Fecha de Creacion del Lote';
