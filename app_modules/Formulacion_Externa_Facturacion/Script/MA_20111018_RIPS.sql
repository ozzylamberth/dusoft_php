ALTER TABLE public.eps_planes_parametros
  ADD COLUMN tabla_afiliados varchar(100);

COMMENT ON COLUMN public.eps_planes_parametros.tabla_afiliados
  IS 'Campo Opcional, donde se registra la tabla que se encuentran los afiliados del plan.';


CREATE TABLE public.ff_envios_parametros (
  empresa_id      char(2) NOT NULL PRIMARY KEY,
  numeracion      integer NOT NULL DEFAULT 1,
  usuario_id      integer NOT NULL DEFAULT 2,
  fecha_registro  timestamp(1) WITHOUT TIME ZONE NOT NULL DEFAULT NOW(),
  /* Foreign keys */
  CONSTRAINT foreign_key01
    FOREIGN KEY (empresa_id)
    REFERENCES public.empresas(empresa_id)
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

ALTER TABLE public.ff_envios_parametros
  OWNER TO siis;

COMMENT ON TABLE public.ff_envios_parametros
  IS 'Parametros de los envìos de Rips por empresa';

COMMENT ON COLUMN public.ff_envios_parametros.empresa_id
  IS 'Empresa a la que pertenece el paràmetro';

COMMENT ON COLUMN public.ff_envios_parametros.numeracion
  IS 'Control de Numeracion de envìos de Rips a las EPS.';

COMMENT ON COLUMN public.ff_envios_parametros.usuario_id
  IS 'Usuario que Registra el Paràmetro';

COMMENT ON COLUMN public.ff_envios_parametros.fecha_registro
  IS 'Fecha de registro del paràmetro';

  CREATE TABLE public.ff_envios_rips (
  empresa_id      char(2) NOT NULL,
  numeracion      integer NOT NULL,
  fecha_registro  timestamp(1) WITHOUT TIME ZONE NOT NULL,
  usuario_id      integer NOT NULL,
  PRIMARY KEY (empresa_id, numeracion),
  /* Foreign keys */
  CONSTRAINT foreign_key01
    FOREIGN KEY (empresa_id)
    REFERENCES public.ff_envios_parametros(empresa_id)
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

ALTER TABLE public.ff_envios_rips
  OWNER TO siis;

COMMENT ON TABLE public.ff_envios_rips
  IS 'Tabla donde se registran por empresa los envios de Rips a las EPS';

COMMENT ON COLUMN public.ff_envios_rips.empresa_id
  IS 'Empresa que Hace el Envìo de Registros RIPS';

COMMENT ON COLUMN public.ff_envios_rips.numeracion
  IS 'Numero de envìo de informes RIPS';

COMMENT ON COLUMN public.ff_envios_rips.fecha_registro
  IS 'Fecha de Registro ENVIO-RIPS';

COMMENT ON COLUMN public.ff_envios_rips.usuario_id
  IS 'Usuario que hace el registro del envìo Rips';

  
  CREATE TABLE public.ff_envios_rips_detalle (
  empresa_id      char(2) NOT NULL,
  numeracion      integer NOT NULL,
  prefijo         varchar(4) NOT NULL,
  factura_fiscal  integer NOT NULL,
  plan_id         integer NOT NULL,
  usuario_id      integer NOT NULL,
  fecha_registro  timestamp(1) WITHOUT TIME ZONE NOT NULL DEFAULT NOW(),
  PRIMARY KEY (empresa_id, prefijo, factura_fiscal),
  /* Foreign keys */
  CONSTRAINT foreign_key01
    FOREIGN KEY (empresa_id, prefijo, factura_fiscal)
    REFERENCES public.fac_facturas(empresa_id, prefijo, factura_fiscal)
    ON DELETE RESTRICT
    ON UPDATE CASCADE, 
  CONSTRAINT foreign_key02
    FOREIGN KEY (empresa_id, numeracion)
    REFERENCES public.ff_envios_rips(empresa_id, numeracion)
    ON DELETE RESTRICT
    ON UPDATE CASCADE, 
  CONSTRAINT foreign_key03
    FOREIGN KEY (plan_id)
    REFERENCES public.planes(plan_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE, 
  CONSTRAINT foreign_key04
    FOREIGN KEY (usuario_id)
    REFERENCES public.system_usuarios(usuario_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.ff_envios_rips_detalle
  OWNER TO siis;

COMMENT ON TABLE public.ff_envios_rips_detalle
  IS 'Tabla q permite registrar las facturas que son incluidos en los RIPS.';

COMMENT ON COLUMN public.ff_envios_rips_detalle.empresa_id
  IS 'Es la Empresa que registra el envìo y quien ha generado facturas de Formulacion';

COMMENT ON COLUMN public.ff_envios_rips_detalle.numeracion
  IS 'Numero del envìo';

COMMENT ON COLUMN public.ff_envios_rips_detalle.prefijo
  IS 'Prefijo de la Factura que hace parte del Envìo RIPS';

COMMENT ON COLUMN public.ff_envios_rips_detalle.factura_fiscal
  IS 'Numero de la Factura que hace parte del Envìo RIPS';

COMMENT ON COLUMN public.ff_envios_rips_detalle.plan_id
  IS 'Codigo del Plan que hace parte del envìo RIPS';

COMMENT ON COLUMN public.ff_envios_rips_detalle.usuario_id
  IS 'Usuario que registra el detalle del envìo';

COMMENT ON COLUMN public.ff_envios_rips_detalle.fecha_registro
  IS 'Fecha de Registro del detalle del envìo';

  ALTER TABLE public.ff_envios_rips
  ALTER COLUMN fecha_registro
  SET DEFAULT NOW();
