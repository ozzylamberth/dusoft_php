CREATE TABLE public.inv_notas_facturas_proveedor_tmp (
  doc_nota_tmp_id    integer NOT NULL DEFAULT 0,
  factura_proveedor  varchar(40) NOT NULL,
  usuario_id         integer NOT NULL,
  tipo_id_tercero    varchar(3) NOT NULL,
  tercero_id         integer NOT NULL,
  fecha_registro     timestamp WITHOUT TIME ZONE NOT NULL,
  empresa_id         varchar(2) NOT NULL,
  documento_id       integer,
  /* Keys */
  CONSTRAINT inv_notas_facturas_proveedor_tmp_pkey
    PRIMARY KEY (doc_nota_tmp_id)
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.inv_notas_facturas_proveedor_tmp
  OWNER TO siis;

COMMENT ON TABLE public.inv_notas_facturas_proveedor_tmp
  IS 'Antes de crear una nota, esta debe crearse primero en un documento temporal';

COMMENT ON COLUMN public.inv_notas_facturas_proveedor_tmp.doc_nota_tmp_id
  IS 'Es el Id temporal del Documento a crear';

COMMENT ON COLUMN public.inv_notas_facturas_proveedor_tmp.factura_proveedor
  IS 'Es el numero de la factura a la que hacemos referencia la Nota DEBITO/CREDITO.';

COMMENT ON COLUMN public.inv_notas_facturas_proveedor_tmp.usuario_id
  IS 'Usuario que realiza la nota y quien tiene permiso para hacer algo con la nota';

COMMENT ON COLUMN public.inv_notas_facturas_proveedor_tmp.tipo_id_tercero
  IS 'Tipo de Identificacion del Tercero (NIT, CC, RC etc...)';

COMMENT ON COLUMN public.inv_notas_facturas_proveedor_tmp.tercero_id
  IS 'Es el numero de identificacion del tercero';

COMMENT ON COLUMN public.inv_notas_facturas_proveedor_tmp.fecha_registro
  IS 'La fecha de creacion del documento';

COMMENT ON COLUMN public.inv_notas_facturas_proveedor_tmp.empresa_id
  IS 'Empresa Dueña del Documento temporal';

COMMENT ON COLUMN public.inv_notas_facturas_proveedor_tmp.documento_id
  IS 'Documento al que se va a utilizar en la creacion de notas debito/credito';