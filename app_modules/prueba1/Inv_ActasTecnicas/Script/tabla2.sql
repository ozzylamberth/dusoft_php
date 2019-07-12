CREATE TABLE public.inv_notas_facturas_proveedor_d (
  item_id          serial NOT NULL,
  empresa_id       varchar(2) NOT NULL,
  prefijo          varchar(4) NOT NULL,
  numero           integer NOT NULL,
  concepto         varchar(100) NOT NULL,
  codigo_producto  varchar(50),
  valor_concepto   numeric(14,2) NOT NULL,
  lote             varchar(30),
  /* Keys */
  CONSTRAINT inv_notas_facturas_proveedor_d_pkey
    PRIMARY KEY (item_id)
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.inv_notas_facturas_proveedor_d
  OWNER TO siis;

COMMENT ON TABLE public.inv_notas_facturas_proveedor_d
  IS 'Tabla donde se guardaran el Detalle de una Nota (debito/credito) de una factura del proveedor';

COMMENT ON COLUMN public.inv_notas_facturas_proveedor_d.empresa_id
  IS 'Empresa q realiza la nota (débito/credito)';

COMMENT ON COLUMN public.inv_notas_facturas_proveedor_d.prefijo
  IS 'Prefijo del Documento nota (Debito/Credito)';

COMMENT ON COLUMN public.inv_notas_facturas_proveedor_d.numero
  IS 'Numero del documento (Debito/Credito)';

COMMENT ON COLUMN public.inv_notas_facturas_proveedor_d.concepto
  IS 'Concepto de la Nota(Debito/Credito)';

COMMENT ON COLUMN public.inv_notas_facturas_proveedor_d.codigo_producto
  IS 'Producto al que puede estar asociado a la nota';

COMMENT ON COLUMN public.inv_notas_facturas_proveedor_d.valor_concepto
  IS 'Valor del concepto de la nota (Debito/Credito)';

COMMENT ON COLUMN public.inv_notas_facturas_proveedor_d.lote
  IS 'Lote del producto asociado';