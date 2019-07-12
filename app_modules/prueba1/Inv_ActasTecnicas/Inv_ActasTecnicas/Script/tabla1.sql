CREATE TABLE public.inv_notas_facturas_proveedor (
  empresa_id       varchar(2) NOT NULL,
  numero           integer NOT NULL,
  prefijo          varchar(4) NOT NULL,
  documento_id     integer NOT NULL,
  factura          varchar(40) NOT NULL,
  usuario_id       integer NOT NULL,
  tipo_id_tercero  varchar(3) NOT NULL,
  tercero_id       integer NOT NULL,
  fecha_registro   date NOT NULL,
  valor_nota       numeric(14,2) NOT NULL,
  sw_anulado       varchar(1) NOT NULL DEFAULT 0,
  /* Keys */
  CONSTRAINT inv_notas_facturas_proveedor_pkey
    PRIMARY KEY (empresa_id, numero, prefijo)
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.inv_notas_facturas_proveedor
  OWNER TO siis;

COMMENT ON TABLE public.inv_notas_facturas_proveedor
  IS 'Tabla donde se guardaran las notas a las facturas de un proveedor';

COMMENT ON COLUMN public.inv_notas_facturas_proveedor.empresa_id
  IS 'Empresa a la que realiza la nota (debito/credito)';

COMMENT ON COLUMN public.inv_notas_facturas_proveedor.numero
  IS 'Numero del Documento';

COMMENT ON COLUMN public.inv_notas_facturas_proveedor.prefijo
  IS 'Prefijo del documento de Notas (Debito/Credito)';

COMMENT ON COLUMN public.inv_notas_facturas_proveedor.documento_id
  IS 'Id del Documento Parametrizado en la tabla Documentos';

COMMENT ON COLUMN public.inv_notas_facturas_proveedor.usuario_id
  IS 'Usuario que realiza la nota a la factura';

COMMENT ON COLUMN public.inv_notas_facturas_proveedor.tipo_id_tercero
  IS 'Tipo de Identificacion del tercero';

COMMENT ON COLUMN public.inv_notas_facturas_proveedor.tercero_id
  IS 'Identificacion del Tercero';

COMMENT ON COLUMN public.inv_notas_facturas_proveedor.fecha_registro
  IS 'Fecha de realizada la nota';

COMMENT ON COLUMN public.inv_notas_facturas_proveedor.valor_nota
  IS 'Es el valor de la Nota (Sea Debito/Credito)';

COMMENT ON COLUMN public.inv_notas_facturas_proveedor.sw_anulado
  IS 'Dice si una nota (Debito/Credito) ha sido Anulada (''1'') o Activa(''0'')';