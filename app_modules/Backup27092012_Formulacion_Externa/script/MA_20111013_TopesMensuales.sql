CREATE OR REPLACE FUNCTION public.aut_saldos_topes()
RETURNS trigger AS
$$
DECLARE

	datos_empresa RECORD;
	datos_formula RECORD;
	parametros_topes RECORD;
	parametros_topes_mensual RECORD;

BEGIN

	SELECT 
	a.empresa_id,
	a.centro_utilidad, 	
	a.bodega
	INTO datos_empresa
	FROM
	bodegas_doc_numeraciones as a
	WHERE 
	a.bodegas_doc_id = NEW.bodegas_doc_id;
	
	/* esm_formulacion_despachos_medicamentos_pendientes */
	
	SELECT 
	b.formula_id,
	b.formula_papel,
	b.fecha_formula,	
	b.tipo_formula
	INTO datos_formula
	FROM
	esm_formulacion_despachos_medicamentos as a 
	JOIN esm_formula_externa as  b ON (a.formula_id = b.formula_id)
	WHERE TRUE
	AND a.bodegas_doc_id = NEW.bodegas_doc_id
	AND a.numeracion = NEW.numeracion;
	
		IF datos_formula.formula_id IS NULL THEN
			SELECT 
			b.formula_id,
			b.formula_papel,
			b.fecha_formula,	
			b.tipo_formula
			INTO datos_formula
			FROM
			esm_formulacion_despachos_medicamentos_pendientes as a 
			JOIN esm_formula_externa as  b ON (a.formula_id = b.formula_id)
			WHERE TRUE
			AND a.bodegas_doc_id = NEW.bodegas_doc_id
			AND a.numeracion = NEW.numeracion;
		END IF;
	
	SELECT tope_mensual 
	INTO parametros_topes
	FROM esm_topes_dispensacion 
	WHERE empresa_id = datos_empresa.empresa_id
	AND centro_utilidad = datos_empresa.centro_utilidad
	AND tipo_formula_id = datos_formula.tipo_formula;
	
	
	
	IF parametros_topes.tope_mensual IS NOT NULL THEN
	
		SELECT 
		a.empresa_id,
		a.centro_utilidad,
		a.tipo_formula_id,
		a.lapso,
		a.tope_mensual,
		a.saldo
		INTO parametros_topes_mensual
		FROM
		esm_topes_dispensacion_mensual AS a
		WHERE TRUE
		AND a.lapso = TO_CHAR(NOW(),'YYYYMM')
		AND a.empresa_id = datos_empresa.empresa_id
		AND a.centro_utilidad = datos_empresa.centro_utilidad
		AND a.tipo_formula_id = datos_formula.tipo_formula;
	
		
		
			IF parametros_topes_mensual.saldo IS NULL THEN
				INSERT INTO esm_topes_dispensacion_mensual
				(
				empresa_id,
				centro_utilidad,
				tipo_formula_id,
				lapso,
				tope_mensual,
				saldo
				)
				VALUES
				(
				datos_empresa.empresa_id,
				datos_empresa.centro_utilidad,
				datos_formula.tipo_formula,
				TO_CHAR(NOW(),'YYYYMM'),
				parametros_topes.tope_mensual,
				parametros_topes.tope_mensual
				);
			END IF;
		
			UPDATE esm_topes_dispensacion_mensual
			SET saldo = saldo - NEW.total_venta
			WHERE TRUE
			AND lapso = TO_CHAR(NOW(),'YYYYMM')
			AND empresa_id = datos_empresa.empresa_id
			AND centro_utilidad = datos_empresa.centro_utilidad
			AND tipo_formula_id = datos_formula.tipo_formula;
	/*RAISE EXCEPTION 'Datos [%] [%],[%],[%][%]',datos_empresa.empresa_id,datos_empresa.centro_utilidad,datos_formula.tipo_formula,TO_CHAR(NOW(),'YYYYMM'),datos_formula.formula_id;*/
	END IF;
	
	return NEW;

END;
$$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER;

ALTER FUNCTION public.aut_saldos_topes()
  OWNER TO "admin";
  
  CREATE TRIGGER aut_topes_formulacion
  BEFORE INSERT
  ON public.bodegas_documentos_d
  FOR EACH ROW
  EXECUTE PROCEDURE public.aut_saldos_topes();
