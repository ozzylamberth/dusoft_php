CREATE OR REPLACE FUNCTION public.aut_corte_lapso()
RETURNS trigger AS
$$
DECLARE
fecha_cierre_lapso  date;
BEGIN

	IF TG_OP = 'UPDATE' THEN
		fecha_cierre_lapso := TO_CHAR(((((substring(OLD.lapso from 0 for 5)||'-'||substring(OLD.lapso from 5 for 8)||'-'||'01')::date)+ '1 months'::interval)- '1 day'::interval),'YYYY-MM-DD');
		IF (fecha_cierre_lapso= NEW.ultima_fecha_corte) THEN
			/*RAISE EXCEPTION 'IGUALES [%], A [%] ',fecha_cierre_lapso, NEW.ultima_fecha_corte;*/
			NEW.estado = 0;
		END IF;
	
	END IF;

	
	return NEW;

END;
$$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER;

ALTER FUNCTION public.aut_corte_lapso()
  OWNER TO "admin";

  CREATE TRIGGER aut_estado_corte
  BEFORE UPDATE
  ON public.ff_cortes_mensual
  FOR EACH ROW
  EXECUTE PROCEDURE public.aut_corte_lapso();


  
  CREATE TABLE public.ff_cortes (
  empresa_id        char(2) NOT NULL,
  centro_utilidad   char(2) NOT NULL,
  numero            integer NOT NULL,
  lapso             char(6) NOT NULL,
  corte_general_id  integer NOT NULL,
  usuario_id        integer NOT NULL DEFAULT 2,
  fecha_registro    timestamp WITHOUT TIME ZONE NOT NULL DEFAULT NOW(),
  PRIMARY KEY (empresa_id, centro_utilidad, numero, lapso),
  /* Foreign keys */
  CONSTRAINT foreign_key01
    FOREIGN KEY (empresa_id, centro_utilidad)
    REFERENCES public.centros_utilidad(empresa_id, centro_utilidad)
    ON DELETE RESTRICT
    ON UPDATE CASCADE, 
  CONSTRAINT foreign_key02
    FOREIGN KEY (empresa_id, centro_utilidad, lapso, corte_general_id)
    REFERENCES public.ff_cortes_mensual(empresa_id, centro_utilidad, lapso, corte_general_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE, 
  CONSTRAINT foreign_key03
    FOREIGN KEY (usuario_id)
    REFERENCES public.system_usuarios(usuario_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.ff_cortes
  OWNER TO siis;

COMMENT ON TABLE public.ff_cortes
  IS 'Tabla que permite registrar la cabecera del corte realizado en la farmacia';

COMMENT ON COLUMN public.ff_cortes.empresa_id
  IS 'Empresa que Registrar el Corte';

COMMENT ON COLUMN public.ff_cortes.centro_utilidad
  IS 'Centro de Utilidad que registra el corte';

COMMENT ON COLUMN public.ff_cortes.numero
  IS 'Numero del Corte Mensual';

COMMENT ON COLUMN public.ff_cortes.lapso
  IS 'Lapso en el cual, se està haciendo el corte. El Formato es AAAAMM';

COMMENT ON COLUMN public.ff_cortes.corte_general_id
  IS 'Referencia a la Tabla Maestra donde se guardan los parametros de los cortes por farmacia';

COMMENT ON COLUMN public.ff_cortes.usuario_id
  IS 'Usuario que Registra el Corte';

COMMENT ON COLUMN public.ff_cortes.fecha_registro
  IS 'Fecha de Registro del Corte';

  
  CREATE TABLE public.ff_cortes_detalle (
  item_id               serial NOT NULL,
  empresa_id            char(2) NOT NULL,
  centro_utilidad       char(2) NOT NULL,
  numero                integer NOT NULL,
  lapso                 char(6) NOT NULL,
  formula_id            integer NOT NULL,
  plan_id               integer NOT NULL,
  codigo_producto       varchar(50) NOT NULL,
  cantidad              numeric(14,4) NOT NULL,
  total_venta           numeric(13,2) NOT NULL,
  pendiente_dispensado  char NOT NULL DEFAULT 0,
  /* Keys */
  CONSTRAINT ff_cortes_detalle_pkey
    PRIMARY KEY (item_id),
  /* Foreign keys */
  CONSTRAINT foreign_key01
    FOREIGN KEY (empresa_id, centro_utilidad, numero, lapso)
    REFERENCES public.ff_cortes(empresa_id, centro_utilidad, numero, lapso)
    ON DELETE RESTRICT
    ON UPDATE CASCADE, 
  CONSTRAINT foreign_key02
    FOREIGN KEY (empresa_id, codigo_producto)
    REFERENCES public.inventarios(empresa_id, codigo_producto)
    ON DELETE RESTRICT
    ON UPDATE CASCADE, 
  CONSTRAINT foreign_key03
    FOREIGN KEY (plan_id)
    REFERENCES public.planes(plan_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE, 
  CONSTRAINT foreign_key04
    FOREIGN KEY (formula_id)
    REFERENCES public.esm_formula_externa(formula_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.ff_cortes_detalle
  OWNER TO siis;

COMMENT ON TABLE public.ff_cortes_detalle
  IS 'Tabla que permite Registrar las formulas que hacen parte del Corte mensual';

COMMENT ON COLUMN public.ff_cortes_detalle.item_id
  IS 'Llave primaria consecutiva';

COMMENT ON COLUMN public.ff_cortes_detalle.empresa_id
  IS 'Empresa que registra el Corte';

COMMENT ON COLUMN public.ff_cortes_detalle.centro_utilidad
  IS 'Centro de Utilidad que registra el corte (farmacia)';

COMMENT ON COLUMN public.ff_cortes_detalle.numero
  IS 'Numero del Corte de la Farmacia';

COMMENT ON COLUMN public.ff_cortes_detalle.lapso
  IS 'Lapso que hace parte el Corte AAAAMM';

COMMENT ON COLUMN public.ff_cortes_detalle.formula_id
  IS 'Identificador Interno de la Formula, que hace parte del corte';

COMMENT ON COLUMN public.ff_cortes_detalle.plan_id
  IS 'Plan que hace parte la formula';

COMMENT ON COLUMN public.ff_cortes_detalle.codigo_producto
  IS 'Codigo del producto Dispensado';

COMMENT ON COLUMN public.ff_cortes_detalle.cantidad
  IS 'Cantidad Dispensada';

COMMENT ON COLUMN public.ff_cortes_detalle.total_venta
  IS 'Costo total de la dispensacion';

COMMENT ON COLUMN public.ff_cortes_detalle.pendiente_dispensado
  IS 'Permite marcar aquellos productos que habian quedado pendientes (1) Si Fue Dispensado Por Pendiente (0) Si Fue Dispensado Normal';
  
  ALTER TABLE public.ff_cortes
  ADD COLUMN corte_auditado char NOT NULL DEFAULT 0;

COMMENT ON COLUMN public.ff_cortes.corte_auditado
  IS 'Permite Marcar a un corte si este ha sido Auditado (1) o no ha Sido Auditado (0)';

  ALTER TABLE public.ff_cortes
  ADD COLUMN usuario_audita integer;

COMMENT ON COLUMN public.ff_cortes.usuario_audita
  IS 'Usuario que Audita el Corte';

  ALTER TABLE public.ff_cortes
  ADD COLUMN fecha_auditoria timestamp WITHOUT TIME ZONE;

COMMENT ON COLUMN public.ff_cortes.fecha_auditoria
  IS 'Fecha en la cual se realiza la auditoria del corte';

  ALTER TABLE public.ff_cortes
  ADD COLUMN fecha_inicial date;

COMMENT ON COLUMN public.ff_cortes.fecha_inicial
  IS 'Fecha Inicial del periodo Corte';
  
ALTER TABLE public.ff_cortes
  ADD COLUMN fecha_final date;

COMMENT ON COLUMN public.ff_cortes.fecha_final
  IS 'Fecha Final Corte';
  
  ALTER TABLE public.ff_cortes
  ADD COLUMN observacion text;

COMMENT ON COLUMN public.ff_cortes.observacion
  IS 'Texto donde se Diligencia la observacion a la auditoria';


