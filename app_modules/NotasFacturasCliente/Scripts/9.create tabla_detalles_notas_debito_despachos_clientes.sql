CREATE TABLE "public"."detalles_notas_debito_despachos_clientes" (
  "detalle_nota_debito_despacho_cliente_id" SERIAL NOT NULL, 
  "nota_debito_despacho_cliente_id" INTEGER NOT NULL,   
  "item_id" integer NOT NULL,   
  "valor" NUMERIC(12,4) NOT NULL, 
  "observacion" TEXT, 
  "valor_iva" NUMERIC(12,4) NOT NULL, 
  "valor_rtf" NUMERIC(12,4) NOT NULL, 
  "valor_ica" NUMERIC(12,4) NOT NULL, 
  
  CONSTRAINT "detalles_notas_debito_despachos_clientes_pkey" PRIMARY KEY("detalle_nota_debito_despacho_cliente_id"), 
  
  CONSTRAINT "detalles_notas_debito_despachos_clientes_nota_debito_despacho_cliente_id" FOREIGN KEY ("nota_debito_despacho_cliente_id")
    REFERENCES "public"."notas_debito_despachos_clientes"("nota_debito_despacho_cliente_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
    NOT DEFERRABLE, 
	
  CONSTRAINT "detalles_notas_debito_despachos_clientes_item_id" FOREIGN KEY ("item_id")
    REFERENCES "public"."inv_facturas_despacho_d"("item_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
    NOT DEFERRABLE
) WITHOUT OIDS;

ALTER TABLE public.detalles_notas_debito_despachos_clientes
  OWNER TO "admin";

COMMENT ON TABLE public.detalles_notas_debito_despachos_clientes
  IS 'Tabla que almacena los detalles de las notas de débito';

COMMENT ON COLUMN public.detalles_notas_debito_despachos_clientes.detalle_nota_debito_despacho_cliente_id
  IS 'Identificación (PK) del detalle de la nota de débito';

COMMENT ON COLUMN public.detalles_notas_debito_despachos_clientes.nota_debito_despacho_cliente_id
  IS 'Identificación (FK) de la nota débito a la cual pertenece el detalle';

COMMENT ON COLUMN public.detalles_notas_debito_despachos_clientes.item_id
  IS 'Identificación (FK) del item (producto) de la factura a la que pertenece la nota débito a la que pertencece el detalle';

COMMENT ON COLUMN public.detalles_notas_debito_despachos_clientes.valor
  IS 'Valor del detalle de la nota de débito';

COMMENT ON COLUMN public.detalles_notas_debito_despachos_clientes.observacion
  IS 'Observación del detalle de la nota de débito';

COMMENT ON COLUMN public.detalles_notas_debito_despachos_clientes.valor_iva
  IS 'Valor del iva del detalle de la nota de débito';

COMMENT ON COLUMN public.detalles_notas_debito_despachos_clientes.valor_rtf
  IS 'Valor de la retefuente del detalle de la nota de débito';

COMMENT ON COLUMN public.detalles_notas_debito_despachos_clientes.valor_ica
  IS 'Valor del ica del detalle de la nota de débito';