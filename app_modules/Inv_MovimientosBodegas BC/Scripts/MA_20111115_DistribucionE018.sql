CREATE TABLE public.inv_bodegas_movimiento_tmp_distribucion (
  usuario_id       integer NOT NULL,
  doc_tmp_id       integer NOT NULL,
  plan_id          integer NOT NULL,
  tipo_formula_id  integer NOT NULL,
  requisicion      varchar(10) NOT NULL,
  /* Keys */
  CONSTRAINT inv_bodegas_movimiento_tmp_distribucion_index01
    PRIMARY KEY (plan_id, requisicion),
  /* Foreign keys */
  CONSTRAINT foreign_key01
    FOREIGN KEY (usuario_id, doc_tmp_id)
    REFERENCES public.inv_bodegas_movimiento_tmp(usuario_id, doc_tmp_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE, 
  CONSTRAINT foreign_key02
    FOREIGN KEY (plan_id)
    REFERENCES public.planes(plan_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE, 
  CONSTRAINT foreign_key03
    FOREIGN KEY (tipo_formula_id)
    REFERENCES public.esm_tipos_formulas(tipo_formula_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.inv_bodegas_movimiento_tmp_distribucion
  OWNER TO siis;

COMMENT ON TABLE public.inv_bodegas_movimiento_tmp_distribucion
  IS 'Tabla que Permite Generar un Documento Temporal para la Dispensacion por Distribucion';

COMMENT ON COLUMN public.inv_bodegas_movimiento_tmp_distribucion.usuario_id
  IS 'Usuario que Genera el Documento temporal';

COMMENT ON COLUMN public.inv_bodegas_movimiento_tmp_distribucion.doc_tmp_id
  IS 'Numero de Documento Temporal';

COMMENT ON COLUMN public.inv_bodegas_movimiento_tmp_distribucion.plan_id
  IS 'Plan Que hace la Solicitud de Medicamentos';

COMMENT ON COLUMN public.inv_bodegas_movimiento_tmp_distribucion.tipo_formula_id
  IS 'Tipo de Dispensacion';

COMMENT ON COLUMN public.inv_bodegas_movimiento_tmp_distribucion.requisicion
  IS 'Numero de Orden de Requisicion, Solicitud que hacen a la Farmacia';

  CREATE TABLE public.inv_bodegas_movimiento_distribucion (
  empresa_id       char(2) NOT NULL,
  prefijo          varchar(4) NOT NULL,
  numero           integer NOT NULL,
  plan_id          integer NOT NULL,
  tipo_formula_id  integer NOT NULL,
  requisicion      varchar(10) NOT NULL,
  PRIMARY KEY (empresa_id, prefijo, numero),
  /* Foreign keys */
  CONSTRAINT foreign_key01
    FOREIGN KEY (empresa_id, prefijo, numero)
    REFERENCES public.inv_bodegas_movimiento(empresa_id, prefijo, numero)
    ON DELETE RESTRICT
    ON UPDATE CASCADE, 
  CONSTRAINT foreign_key02
    FOREIGN KEY (plan_id)
    REFERENCES public.planes(plan_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE, 
  CONSTRAINT foreign_key03
    FOREIGN KEY (tipo_formula_id)
    REFERENCES public.esm_tipos_formulas(tipo_formula_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.inv_bodegas_movimiento_distribucion
  OWNER TO siis;

COMMENT ON TABLE public.inv_bodegas_movimiento_distribucion
  IS 'Documento Real Para la dispensacion por Distribucion';

COMMENT ON COLUMN public.inv_bodegas_movimiento_distribucion.empresa_id
  IS 'Empresa que Genera el Documento';

COMMENT ON COLUMN public.inv_bodegas_movimiento_distribucion.prefijo
  IS 'Prefijo del documento';

COMMENT ON COLUMN public.inv_bodegas_movimiento_distribucion.numero
  IS 'Numero del Documento Generado';

COMMENT ON COLUMN public.inv_bodegas_movimiento_distribucion.plan_id
  IS 'Plan q ha solicitado los medicamentos';

COMMENT ON COLUMN public.inv_bodegas_movimiento_distribucion.tipo_formula_id
  IS 'Tipo de Dispensacion Asignado en el documento';

COMMENT ON COLUMN public.inv_bodegas_movimiento_distribucion.requisicion
  IS 'Numero de Orden de Requisicion, Solicitud que hacen a la Farmacia';
  
  /*
  * TRIGGER PARA ACTUALIZAR TOPES Y COSTO VENTA AL MOMENTO 
  *  DE HACER UNA ENTREGA POR DISTRIBUCION
  */
  CREATE OR REPLACE FUNCTION public.aut_saldos_topes_inv()
RETURNS trigger AS
$$
DECLARE

	datos_formula RECORD;
	parametros_topes RECORD;
	parametros_topes_mensual RECORD;

BEGIN

	SELECT 
	*
	INTO datos_formula
	FROM inv_bodegas_movimiento_distribucion
	WHERE TRUE
	AND prefijo = NEW.prefijo
	AND numero = NEW.numero
	AND empresa_id = NEW.empresa_id;
	
	SELECT tope_mensual 
	INTO parametros_topes
	FROM esm_topes_dispensacion 
	WHERE empresa_id = NEW.empresa_id
	AND centro_utilidad = NEW.centro_utilidad
	AND tipo_formula_id = datos_formula.tipo_formula_id;
	
	IF datos_formula.tipo_formula_id IS NOT NULL THEN
	
		UPDATE inv_bodegas_movimiento_d
		SET 	total_costo_pedido = (fc_precio_producto_plan(datos_formula.plan_id,NEW.codigo_producto,NEW.empresa_id,'0','0')*NEW.cantidad),
				valor_unitario = fc_precio_producto_plan(datos_formula.plan_id,NEW.codigo_producto,NEW.empresa_id,'0','0')
		WHERE TRUE
		AND prefijo = NEW.prefijo
		AND numero = NEW.numero
		AND empresa_id = NEW.empresa_id
		AND codigo_producto = NEW.codigo_producto;
		
	
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
		AND a.empresa_id = NEW.empresa_id
		AND a.centro_utilidad = NEW.centro_utilidad
		AND a.tipo_formula_id = datos_formula.tipo_formula_id;
	
		
		
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
				NEW.empresa_id,
				NEW.centro_utilidad,
				datos_formula.tipo_formula_id,
				TO_CHAR(NOW(),'YYYYMM'),
				parametros_topes.tope_mensual,
				parametros_topes.tope_mensual
				);
			END IF;
		
			UPDATE esm_topes_dispensacion_mensual
			SET saldo = saldo - (fc_precio_producto_plan(datos_formula.plan_id,NEW.codigo_producto,NEW.empresa_id,'0','0')*NEW.cantidad)
			WHERE TRUE
			AND lapso = TO_CHAR(NOW(),'YYYYMM')
			AND empresa_id = NEW.empresa_id
			AND centro_utilidad = NEW.centro_utilidad
			AND tipo_formula_id = datos_formula.tipo_formula_id;
	/*RAISE EXCEPTION 'Datos [%] [%],[%],[%][%]',datos_empresa.empresa_id,datos_empresa.centro_utilidad,datos_formula.tipo_formula,TO_CHAR(NOW(),'YYYYMM'),datos_formula.formula_id;*/
	END IF;
	
	return NEW;

END;
$$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER;

ALTER FUNCTION public.aut_saldos_topes_inv()
  OWNER TO "admin";
  
  CREATE TRIGGER ff_topes_distribucion
  AFTER INSERT
  ON public.inv_bodegas_movimiento_d
  FOR EACH ROW
  EXECUTE PROCEDURE aut_saldos_topes_inv();
