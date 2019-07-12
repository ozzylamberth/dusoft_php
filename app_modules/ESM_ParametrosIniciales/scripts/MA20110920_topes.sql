CREATE TABLE public.esm_topes_dispensacion (
  empresa_id       char(2) NOT NULL,
  centro_utilidad  char(2) NOT NULL,
  tipo_formula_id  integer NOT NULL,
  tope_mensual     numeric(20,4) NOT NULL DEFAULT 0,
  usuario_id       integer NOT NULL,
  fecha_registro   timestamp(1) WITHOUT TIME ZONE NOT NULL DEFAULT NOW(),
  PRIMARY KEY (empresa_id, centro_utilidad, tipo_formula_id),
  /* Foreign keys */
  CONSTRAINT foreign_key01
    FOREIGN KEY (empresa_id, centro_utilidad)
    REFERENCES public.centros_utilidad(empresa_id, centro_utilidad)
    ON DELETE RESTRICT
    ON UPDATE CASCADE, 
  CONSTRAINT foreign_key02
    FOREIGN KEY (tipo_formula_id)
    REFERENCES public.esm_tipos_formulas(tipo_formula_id)
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

ALTER TABLE public.esm_topes_dispensacion
  OWNER TO siis;

COMMENT ON TABLE public.esm_topes_dispensacion
  IS 'Tabla que permite parametrizar los topes de dispensacion FormulacionExterna';

COMMENT ON COLUMN public.esm_topes_dispensacion.empresa_id
  IS 'Empresa que se le aplica el tope';

COMMENT ON COLUMN public.esm_topes_dispensacion.centro_utilidad
  IS 'Centro de Utilidad que aplicarà el tope';

COMMENT ON COLUMN public.esm_topes_dispensacion.tipo_formula_id
  IS 'Es el tipo de dispensacion al que se le aplica los topes de dispensacion';

COMMENT ON COLUMN public.esm_topes_dispensacion.tope_mensual
  IS 'Tope Mensual Que ha sido parametrizado, y que serà aplicado en los meses';

COMMENT ON COLUMN public.esm_topes_dispensacion.usuario_id
  IS 'Usuario que registra el paràmetro';

COMMENT ON COLUMN public.esm_topes_dispensacion.fecha_registro
  IS 'Fecha de registro';

  
  CREATE TABLE public.esm_topes_dispensacion_mensual (
  empresa_id       char(2) NOT NULL,
  centro_utilidad  char(2) NOT NULL,
  tipo_formula_id  integer NOT NULL,
  lapso            char(6) NOT NULL,
  tope_mensual     numeric(20) NOT NULL DEFAULT 0,
  saldo            numeric(20) NOT NULL DEFAULT 0,
  usuario_id       integer NOT NULL DEFAULT 2,
  fecha_registro   timestamp(1) WITHOUT TIME ZONE NOT NULL DEFAULT NOW(),
  PRIMARY KEY (empresa_id, centro_utilidad, tipo_formula_id, lapso),
  /* Foreign keys */
  CONSTRAINT foreign_key01
    FOREIGN KEY (empresa_id, centro_utilidad, tipo_formula_id)
    REFERENCES public.esm_topes_dispensacion(empresa_id, centro_utilidad, tipo_formula_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE, 
  CONSTRAINT foreign_key02
    FOREIGN KEY (usuario_id)
    REFERENCES public.system_usuarios(usuario_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.esm_topes_dispensacion_mensual
  OWNER TO siis;

COMMENT ON TABLE public.esm_topes_dispensacion_mensual
  IS 'Tabla que permite diligenciar los topes mensuales por tipo de dispensacion';

COMMENT ON COLUMN public.esm_topes_dispensacion_mensual.empresa_id
  IS 'Empresa que aplica los topes';

COMMENT ON COLUMN public.esm_topes_dispensacion_mensual.centro_utilidad
  IS 'Centro de Utilidad que aplica los topes';

COMMENT ON COLUMN public.esm_topes_dispensacion_mensual.tipo_formula_id
  IS 'Tipo de Dispensacion que aplicarà el tope';

COMMENT ON COLUMN public.esm_topes_dispensacion_mensual.lapso
  IS 'Lapso (AAAAMM) del Tope por centro de Utilidad';

COMMENT ON COLUMN public.esm_topes_dispensacion_mensual.tope_mensual
  IS 'Tope Mensual';

COMMENT ON COLUMN public.esm_topes_dispensacion_mensual.saldo
  IS 'Saldo del tope Mensual';

COMMENT ON COLUMN public.esm_topes_dispensacion_mensual.usuario_id
  IS 'Usuario que Registra la informacion';

COMMENT ON COLUMN public.esm_topes_dispensacion_mensual.fecha_registro
  IS 'Fecha de registro';
