CREATE TABLE "public"."tmp_notas_credito_despachos_clientes" (
  "tmp_nota_credito_despacho_cliente_id" INTEGER DEFAULT nextval('tmp_notas_credito_despachos_c_tmp_nota_credito_despacho_cli_seq'::regclass) NOT NULL, 
  "empresa_id" CHAR(2) NOT NULL, 
  "factura_fiscal" INTEGER NOT NULL, 
  "prefijo" VARCHAR(4) NOT NULL, 
  "empresa_id_devolucion" CHAR(2), 
  "prefijo_devolucion" VARCHAR(4), 
  "numero_devolucion" INTEGER, 
  "tipo" VARCHAR(10) NOT NULL, 
  "usuario_id" INTEGER NOT NULL, 
  "fecha_registro" TIMESTAMP WITHOUT TIME ZONE DEFAULT now() NOT NULL, 
  CONSTRAINT "tmp_notas_credito_despachos_clientes_pkey" PRIMARY KEY("tmp_nota_credito_despacho_cliente_id"), 
  CONSTRAINT "tmp_notas_credito_despachos_clientes_empresa_id_devolucion_pref" FOREIGN KEY ("empresa_id_devolucion", "prefijo_devolucion", "numero_devolucion")
    REFERENCES "public"."inv_bodegas_movimiento_devolucion_cliente"("empresa_id", "prefijo", "numero")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
    NOT DEFERRABLE, 
  CONSTRAINT "tmp_notas_credito_despachos_clientes_empresa_id_factura_fiscal_" FOREIGN KEY ("empresa_id", "factura_fiscal", "prefijo")
    REFERENCES "public"."inv_facturas_despacho"("empresa_id", "factura_fiscal", "prefijo")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
    NOT DEFERRABLE, 
  CONSTRAINT "tmp_notas_credito_despachos_clientes_usuario_id" FOREIGN KEY ("usuario_id")
    REFERENCES "public"."system_usuarios"("usuario_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
    NOT DEFERRABLE
) WITHOUT OIDS;

COMMENT ON TABLE "public"."tmp_notas_credito_despachos_clientes"
IS 'Tabla que almacena la información general (cabecera) de las notas de crédito temporales';

COMMENT ON COLUMN "public"."tmp_notas_credito_despachos_clientes"."tmp_nota_credito_despacho_cliente_id"
IS 'Identificación (PK) de la nota de crédito temporal';

COMMENT ON COLUMN "public"."tmp_notas_credito_despachos_clientes"."empresa_id"
IS 'Identificación (FK) de la empresa a la que pertenece la factura a la cual se le realiza la nota crédito temporal';

COMMENT ON COLUMN "public"."tmp_notas_credito_despachos_clientes"."factura_fiscal"
IS 'Número (FK) de la factura a la cual se le realiza la nota crédito temporal';

COMMENT ON COLUMN "public"."tmp_notas_credito_despachos_clientes"."prefijo"
IS 'Prefijo (FK) de la factura a la cual se le realiza la nota crédito temporal';


COMMENT ON COLUMN "public"."tmp_notas_credito_despachos_clientes"."empresa_id_devolucion"
IS 'Identificador de la empresa (FK)  de la factura a la cual se le realiza la nota crédito temporal';

COMMENT ON COLUMN "public"."tmp_notas_credito_despachos_clientes"."prefijo_devolucion"
IS 'Prefijo (FK) de la devolución de la factura a la cual se le realiza la nota crédito temporal';

COMMENT ON COLUMN "public"."tmp_notas_credito_despachos_clientes"."numero_devolucion"
IS 'Número (FK) de la devolución de la factura a la cual se le realiza la nota crédito temporal';


COMMENT ON COLUMN "public"."tmp_notas_credito_despachos_clientes"."tipo"
IS 'Tipo de la nota crédito ("VALOR" ó "DEVOLUCIÓN")';

COMMENT ON COLUMN "public"."tmp_notas_credito_despachos_clientes"."usuario_id"
IS 'Identificación del usuario que registra la nota crédito temporal';

COMMENT ON COLUMN "public"."tmp_notas_credito_despachos_clientes"."fecha_registro"
IS 'Fecha de registro de la nota crédito temporal';