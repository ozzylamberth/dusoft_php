CREATE TABLE public.despachos (
  despacho_id  		serial NOT NULL,
  planilla_despacho_id					integer NULL,
  transportadora_id          			integer NULL,
  placa_vehiculo           			    character(6) NOT NULL,
  numero_guia							bigint NOT NULL,
  nombre_conductor						character varying(60) NOT NULL,
  usuario_id        integer NOT NULL,
  fecha_registro    timestamp WITHOUT TIME ZONE DEFAULT now(),
  /* Keys */
  CONSTRAINT despachos_pkey
    PRIMARY KEY (despacho_id),
  /* Foreign keys */
  CONSTRAINT despachos_planilla_despacho_id_fkey
	FOREIGN KEY (planilla_despacho_id)
	REFERENCES planillas_despachos (planilla_despacho_id)
	ON DELETE RESTRICT
	ON UPDATE CASCADE,
  CONSTRAINT despachos_transportadora_id_fkey
    FOREIGN KEY (transportadora_id)
    REFERENCES public.inv_transportadoras(transportadora_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT despachos_usuario_id_fkey
    FOREIGN KEY (usuario_id)
    REFERENCES public.system_usuarios(usuario_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.despachos
  OWNER TO "admin";

COMMENT ON TABLE public.despachos
  IS 'Tabla que almacena la información general del despacho';
  
COMMENT ON COLUMN public.despachos.despacho_id
  IS 'PK tabla, número único del despacho';
  
COMMENT ON COLUMN public.despachos.planilla_despacho_id
  IS 'Id de la planilla (de despacho) relacionada al (detalle de) despacho';
  
COMMENT ON COLUMN public.despachos.transportadora_id
  IS 'Id de la transportadora con la cual se hace el despacho';
  
COMMENT ON COLUMN public.despachos.placa_vehiculo
  IS 'Placa del vehículo con el cual se envía el despacho';
  
COMMENT ON COLUMN public.despachos.numero_guia
  IS 'Número de guía con la cual se envía el despacho';
  
COMMENT ON COLUMN public.despachos.nombre_conductor
  IS 'Nombre del conductor que maneja el vehículo en el cual se hace el despacho';

COMMENT ON COLUMN public.despachos.usuario_id
  IS 'Usuario que registra';

COMMENT ON COLUMN public.despachos.fecha_registro
  IS 'Fecha de registro';