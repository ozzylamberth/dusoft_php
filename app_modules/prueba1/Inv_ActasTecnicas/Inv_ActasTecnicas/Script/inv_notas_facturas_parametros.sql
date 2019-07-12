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
  IS 'Par�metros para los m�dulos de notas DEBITO/CREDITO facturas';

COMMENT ON COLUMN public.inv_notas_facturas_parametros.id_parametros
  IS 'Es el Id del par�metro a ingresar';

COMMENT ON COLUMN public.inv_notas_facturas_parametros.modulo
  IS 'Descripcion del M�dulo que pertenece el par�metro';

COMMENT ON COLUMN public.inv_notas_facturas_parametros.empresa_id
  IS 'Empresa Due�a del par�metro';

COMMENT ON COLUMN public.inv_notas_facturas_parametros.documento_id_credito
  IS 'Parametriza que Documento hace parte para los Creditos segun el m�dulo';

COMMENT ON COLUMN public.inv_notas_facturas_parametros.documento_id_debito
  IS 'Parametriza que Documento hace parte para los Debitos segun el m�dulo';

  