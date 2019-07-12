ALTER TABLE public.userpermisos_formulacion_externa_facturacion
  ADD COLUMN centro_utilidad char(2);

COMMENT ON COLUMN public.userpermisos_formulacion_externa_facturacion.centro_utilidad
  IS 'Centro de Utilidad';

  ALTER TABLE public.userpermisos_formulacion_externa_facturacion
  ADD CONSTRAINT foreign_key03
  FOREIGN KEY (empresa_id, centro_utilidad)
    REFERENCES public.centros_utilidad(empresa_id, centro_utilidad)
    ON DELETE RESTRICT
    ON UPDATE CASCADE;

	CREATE TABLE public.ff_cortes_generales (
  corte_general_id  serial NOT NULL PRIMARY KEY,
  empresa_id        char(2) NOT NULL,
  centro_utilidad   char(2) NOT NULL,
  numeracion        integer NOT NULL DEFAULT 1,
  lapso             char(6) NOT NULL,
  usuario_id        integer NOT NULL DEFAULT 2,
  fecha_registro    timestamp(1) WITHOUT TIME ZONE NOT NULL DEFAULT NOW(),
  /* Keys */
  CONSTRAINT ff_cortes_generales_index01
    UNIQUE (empresa_id, centro_utilidad),
  /* Foreign keys */
  CONSTRAINT foreign_key01
    FOREIGN KEY (empresa_id, centro_utilidad)
    REFERENCES public.centros_utilidad(empresa_id, centro_utilidad)
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

ALTER TABLE public.ff_cortes_generales
  OWNER TO siis;

COMMENT ON TABLE public.ff_cortes_generales
  IS 'Tabla que permite establecer los cortes (Parametros generales) por centro de Utilidad';

COMMENT ON COLUMN public.ff_cortes_generales.corte_general_id
  IS 'Consecutivo del Parametro Gneral de Corte por centro de utilidad';

COMMENT ON COLUMN public.ff_cortes_generales.empresa_id
  IS 'Empresa que harà parte de la parametrizacion';

COMMENT ON COLUMN public.ff_cortes_generales.centro_utilidad
  IS 'Centro de Utilidad parametrizado';

COMMENT ON COLUMN public.ff_cortes_generales.numeracion
  IS 'Conteo por Centro de Utilidad de los cortes.';

COMMENT ON COLUMN public.ff_cortes_generales.lapso
  IS 'Lapso donde empieza los cortes en formato AAAAMM';

COMMENT ON COLUMN public.ff_cortes_generales.usuario_id
  IS 'Usuario que hace el registro';

COMMENT ON COLUMN public.ff_cortes_generales.fecha_registro
  IS 'Fecha de Registro';

  
  CREATE TABLE public.ff_cortes_mensual (
  empresa_id          char(2) NOT NULL,
  centro_utilidad     char(2) NOT NULL,
  corte_general_id    integer NOT NULL,
  lapso               char(6) NOT NULL,
  numero              integer NOT NULL,
  ultimo_dia_mes      integer,
  ultima_fecha_corte  date NOT NULL,
  usuario_id          integer NOT NULL DEFAULT 2,
  fecha_registro      timestamp(1) WITHOUT TIME ZONE NOT NULL DEFAULT NOW(),
  PRIMARY KEY (empresa_id, centro_utilidad, corte_general_id, lapso),
  /* Foreign keys */
  CONSTRAINT foreign_key01
    FOREIGN KEY (corte_general_id)
    REFERENCES public.ff_cortes_generales(corte_general_id)
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

ALTER TABLE public.ff_cortes_mensual
  OWNER TO siis;

COMMENT ON TABLE public.ff_cortes_mensual
  IS 'Tabla que Permite llevar el registro Mensual de los Cortes por centro de Utilidad';

COMMENT ON COLUMN public.ff_cortes_mensual.empresa_id
  IS 'Empresa del Corte';

COMMENT ON COLUMN public.ff_cortes_mensual.centro_utilidad
  IS 'Centro de Utilidad del Corte';

COMMENT ON COLUMN public.ff_cortes_mensual.corte_general_id
  IS 'Parametro General del Corte';

COMMENT ON COLUMN public.ff_cortes_mensual.lapso
  IS 'Lapso del Corte AAAAMM';

COMMENT ON COLUMN public.ff_cortes_mensual.numero
  IS 'Numero de Cortes que lleva el Mes';

COMMENT ON COLUMN public.ff_cortes_mensual.ultimo_dia_mes
  IS 'Define hasta cuando es el ultimo dia de del mes para el corte';

COMMENT ON COLUMN public.ff_cortes_mensual.ultima_fecha_corte
  IS 'Ultima Fecha seleccionada para el corte del lapso';

COMMENT ON COLUMN public.ff_cortes_mensual.usuario_id
  IS 'Usuario que Registra el lapso';

COMMENT ON COLUMN public.ff_cortes_mensual.fecha_registro
  IS 'Fecha de Registro del Lapso (Corte)';

  ALTER TABLE public.ff_cortes_mensual
  ADD COLUMN estado char NOT NULL DEFAULT 1;

COMMENT ON COLUMN public.ff_cortes_mensual.estado
  IS 'Estado que Permite identificar si ''1'' Si El Lapso se encuentra aun abierto. Y ''0'' si el lapso ya se encuentra cerrado';

  ALTER TABLE public.ff_cortes_generales
  ALTER COLUMN lapso
  DROP NOT NULL;

  ALTER TABLE public.ff_cortes_generales
  ALTER COLUMN lapso
  SET NOT NULL;

  ALTER TABLE public.ff_cortes_mensual
  ALTER COLUMN ultima_fecha_corte
  DROP NOT NULL;

  ALTER TABLE public.ff_cortes_mensual
  ALTER COLUMN numero
  SET DEFAULT 0;
