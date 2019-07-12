CREATE TABLE public.inv_notas_facturas_proveedor_d_tmp (
  doc_nota_tmp_id  integer NOT NULL,
  item_id          serial NOT NULL,
  empresa_id       varchar(2) NOT NULL,
  concepto         text NOT NULL,
  codigo_producto  varchar(50),
  valor_concepto   numeric(14,2) NOT NULL,
  lote             varchar(30),
  /* Keys */
  CONSTRAINT inv_notas_facturas_proveedor_d_tmp_pkey
    PRIMARY KEY (item_id)
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.inv_notas_facturas_proveedor_d_tmp
  OWNER TO siis;

COMMENT ON TABLE public.inv_notas_facturas_proveedor_d_tmp
  IS 'Es el detalle de la nota de la factura Proveedor';

COMMENT ON COLUMN public.inv_notas_facturas_proveedor_d_tmp.doc_nota_tmp_id
  IS 'Relaciona con la cabecera del documento temporal con el detalle';

COMMENT ON COLUMN public.inv_notas_facturas_proveedor_d_tmp.item_id
  IS 'Campo serial donde va almacenando los Items de las notas DEBITO/CREDITO';

COMMENT ON COLUMN public.inv_notas_facturas_proveedor_d_tmp.empresa_id
  IS 'Empresa dueña del documento';

COMMENT ON COLUMN public.inv_notas_facturas_proveedor_d_tmp.concepto
  IS 'descripcion del concepto de la nota CREDITO/DEBITO';

COMMENT ON COLUMN public.inv_notas_facturas_proveedor_d_tmp.codigo_producto
  IS 'Si la nota tiene asociada un producto';

COMMENT ON COLUMN public.inv_notas_facturas_proveedor_d_tmp.valor_concepto
  IS 'Registra el valor del concepto de la nota DEBITO/CREDITO';

COMMENT ON COLUMN public.inv_notas_facturas_proveedor_d_tmp.lote
  IS 'Lote Del Producto para la nota';