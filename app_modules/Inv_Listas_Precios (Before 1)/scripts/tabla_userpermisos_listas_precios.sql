-- Table: public.userpermisos_listas_precios

-- DROP TABLE public.userpermisos_listas_precios;

CREATE TABLE public.userpermisos_listas_precios (
  listas_precios_id  serial NOT NULL,
  usuario_id         integer,
  empresa_id         varchar(2),
  /* Keys */
  CONSTRAINT userpermisos_listas_precios_pkey
    PRIMARY KEY (listas_precios_id)
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.userpermisos_listas_precios
  OWNER TO "admin";

COMMENT ON TABLE public.userpermisos_listas_precios
  IS 'asignación de permisos al módulo listas precios';

COMMENT ON COLUMN public.userpermisos_listas_precios.listas_precios_id
  IS 'PK tabla';

COMMENT ON COLUMN public.userpermisos_listas_precios.usuario_id
  IS 'ID de usuario';

COMMENT ON COLUMN public.userpermisos_listas_precios.empresa_id
  IS 'Id de empresa';
