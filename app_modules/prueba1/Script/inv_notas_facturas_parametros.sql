-- Table: public.inv_notas_facturas_parametros

-- DROP TABLE public.inv_notas_facturas_parametros;

CREATE TABLE public.inv_notas_facturas_parametros (
  id_parametros         integer NOT NULL,
  modulo                varchar(100) NOT NULL,
  empresa_id            varchar(2) NOT NULL,
  documento_id_credito  integer NOT NULL,
  documento_id_debito   integer NOT NULL,
  /* Keys */
  CONSTRAINT inv_notas_facturas_parametros_pkey
    PRIMARY KEY (id_parametros)
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.inv_notas_facturas_parametros
  OWNER TO siis;

COMMENT ON TABLE public.inv_notas_facturas_parametros
  IS 'Parámetros para los módulos de notas DEBITO/CREDITO facturas';

COMMENT ON COLUMN public.inv_notas_facturas_parametros.id_parametros
  IS 'Es el Id del parámetro a ingresar';

COMMENT ON COLUMN public.inv_notas_facturas_parametros.modulo
  IS 'Descripcion del Módulo que pertenece el parámetro';

COMMENT ON COLUMN public.inv_notas_facturas_parametros.empresa_id
  IS 'Empresa Dueña del parámetro';

COMMENT ON COLUMN public.inv_notas_facturas_parametros.documento_id_credito
  IS 'Parametriza que Documento hace parte para los Creditos segun el módulo';

COMMENT ON COLUMN public.inv_notas_facturas_parametros.documento_id_debito
  IS 'Parametriza que Documento hace parte para los Debitos segun el módulo';

  