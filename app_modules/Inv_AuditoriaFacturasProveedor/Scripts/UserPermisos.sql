CREATE TABLE public.userpermisos_auditoriafacturasproveedor (
  empresa_id         char(2) NOT NULL,
  indice_automatico  serial NOT NULL,
  usuario_id         integer NOT NULL,
  /* Keys */
  CONSTRAINT userpermisos_auditoriafacturasproveedor_pkey
    PRIMARY KEY (empresa_id, usuario_id), 
  CONSTRAINT userpermisos_auditoriafacturasproveedor_indice_automatico_key
    UNIQUE (indice_automatico)
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.userpermisos_auditoriafacturasproveedor
  OWNER TO siis;

COMMENT ON TABLE public.userpermisos_auditoriafacturasproveedor
  IS 'Permisos modulo de Auditoria auditoriafacturasproveedor';

COMMENT ON COLUMN public.userpermisos_auditoriafacturasproveedor.empresa_id
  IS 'Empresa a la que pertenece el usuario';

COMMENT ON COLUMN public.userpermisos_auditoriafacturasproveedor.indice_automatico
  IS 'Campo Serial';

COMMENT ON COLUMN public.userpermisos_auditoriafacturasproveedor.usuario_id
  IS 'usuario del Módulo';
