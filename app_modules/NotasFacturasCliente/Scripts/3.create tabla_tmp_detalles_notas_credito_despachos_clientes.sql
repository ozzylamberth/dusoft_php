CREATE TABLE "public"."tmp_detalles_notas_credito_despachos_clientes" (
  "tmp_detalle_nota_credito_despacho_cliente_id" INTEGER DEFAULT nextval('tmp_detalles_notas_credito_de_tmp_detalle_nota_credito_desp_seq'::regclass) NOT NULL, 
  "tmp_nota_credito_despacho_cliente_id" INTEGER NOT NULL, 
  "item_id" INTEGER NOT NULL, 
  "valor" NUMERIC(12,4) NOT NULL, 
  "observacion" TEXT, 
  "valor_iva" NUMERIC(12,4) NOT NULL, 
  "valor_rtf" NUMERIC(12,4) NOT NULL, 
  "valor_ica" NUMERIC(12,4) NOT NULL, 
  "movimiento_id" INTEGER, 
  CONSTRAINT "tmp_detalles_notas_credito_despachos_clientes_pkey" PRIMARY KEY("tmp_detalle_nota_credito_despacho_cliente_id"), 
  CONSTRAINT "tmp_detalles_notas_credito_despachos_clientes_item_id" FOREIGN KEY ("item_id")
    REFERENCES "public"."inv_facturas_despacho_d"("item_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
    NOT DEFERRABLE, 
  CONSTRAINT "tmp_detalles_notas_credito_despachos_clientes_movimiento_id" FOREIGN KEY ("movimiento_id")
    REFERENCES "public"."inv_bodegas_movimiento_d"("movimiento_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
    NOT DEFERRABLE, 
  CONSTRAINT "tmp_detalles_notas_credito_despachos_clientes_tmp_nota_credito_" FOREIGN KEY ("tmp_nota_credito_despacho_cliente_id")
    REFERENCES "public"."tmp_notas_credito_despachos_clientes"("tmp_nota_credito_despacho_cliente_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
    NOT DEFERRABLE
) WITHOUT OIDS;

COMMENT ON TABLE "public"."tmp_detalles_notas_credito_despachos_clientes"
IS 'Tabla que almacena los detalles de las notas de crédito temporales';

COMMENT ON COLUMN "public"."tmp_detalles_notas_credito_despachos_clientes"."tmp_detalle_nota_credito_despacho_cliente_id"
IS 'Identificación (PK) del detalle de la nota de crédito temporal';

COMMENT ON COLUMN "public"."tmp_detalles_notas_credito_despachos_clientes"."tmp_nota_credito_despacho_cliente_id"
IS 'Identificación (FK) de la nota crédito temporal a la cual pertenece el detalle';

COMMENT ON COLUMN "public"."tmp_detalles_notas_credito_despachos_clientes"."item_id"
IS 'Identificación (FK) del item (producto) de la factura a la que pertenece la nota crédito temporal a la que pertencece el detalle';

COMMENT ON COLUMN "public"."tmp_detalles_notas_credito_despachos_clientes"."valor"
IS 'Valor del detalle de la nota de crédito temporal';

COMMENT ON COLUMN "public"."tmp_detalles_notas_credito_despachos_clientes"."observacion"
IS 'Observación del detalle de la nota de crédito temporal';

COMMENT ON COLUMN "public"."tmp_detalles_notas_credito_despachos_clientes"."valor_iva"
IS 'Valor del iva del detalle de la nota de crédito temporal';

COMMENT ON COLUMN "public"."tmp_detalles_notas_credito_despachos_clientes"."valor_rtf"
IS 'Valor de la retefuente del detalle de la nota de crédito temporal';

COMMENT ON COLUMN "public"."tmp_detalles_notas_credito_despachos_clientes"."valor_ica"
IS 'Valor del ica del detalle de la nota de crédito temporal';

COMMENT ON COLUMN "public"."tmp_detalles_notas_credito_despachos_clientes"."movimiento_id"
IS 'Identificación (FK) del detalle (del movimiento) de la devolución a la que pertenece la nota crédito temporal a la que pertencece el detalle';