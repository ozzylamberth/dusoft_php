CREATE TABLE "public"."notas_debito_despachos_clientes" (
  "nota_debito_despacho_cliente_id" SERIAL NOT NULL, 
  "empresa_id" CHAR(2) NOT NULL, 
  "factura_fiscal" INTEGER NOT NULL, 
  "prefijo" VARCHAR(4) NOT NULL, 
  "valor" NUMERIC(12,4) NOT NULL, 
  --"tipo" VARCHAR(10) NOT NULL, 
  "usuario_id" INTEGER NOT NULL, 
  "fecha_registro" TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT now(), 
  
  CONSTRAINT "notas_debito_despachos_clientes_pkey" PRIMARY KEY("nota_debito_despacho_cliente_id"),
  
  CONSTRAINT "notas_debito_despachos_clientes_empresa_id_factura_fiscal_prefijo" FOREIGN KEY ("empresa_id", "factura_fiscal", "prefijo")
    REFERENCES "public"."inv_facturas_despacho"("empresa_id", "factura_fiscal", "prefijo")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
    NOT DEFERRABLE, 
	
  CONSTRAINT "notas_debito_despachos_clientes_usuario_id" FOREIGN KEY ("usuario_id")
    REFERENCES "public"."system_usuarios"("usuario_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
    NOT DEFERRABLE
) WITHOUT OIDS;

ALTER TABLE public.notas_debito_despachos_clientes
  OWNER TO "admin";

COMMENT ON TABLE public.notas_debito_despachos_clientes
  IS 'Tabla que almacena la información general (cabecera) de las notas de débito';

COMMENT ON COLUMN public.notas_debito_despachos_clientes.nota_debito_despacho_cliente_id
  IS 'Identificación (PK) de la nota de débito';

COMMENT ON COLUMN public.notas_debito_despachos_clientes.empresa_id
  IS 'Identificación (FK) de la empresa a la que pertenece la factura a la cual se le realiza la nota débito';

COMMENT ON COLUMN public.notas_debito_despachos_clientes.factura_fiscal
  IS 'Número (FK) de la factura a la cual se le realiza la nota débito';

COMMENT ON COLUMN public.notas_debito_despachos_clientes.prefijo
  IS 'Prefijo (FK) de la factura a la cual se le realiza la nota débito';

COMMENT ON COLUMN public.notas_debito_despachos_clientes.valor
  IS 'Valor total de la nota débito (ignorando impuestos)';

/*COMMENT ON COLUMN public.notas_debito_despachos_clientes.tipo
  IS 'Tipo de la nota débito ("VALOR" ó "DEVOLUCIÓN")';*/

COMMENT ON COLUMN public.notas_debito_despachos_clientes.usuario_id
  IS 'Identificación del usuario que registra la nota débito';

COMMENT ON COLUMN public.notas_debito_despachos_clientes.fecha_registro
  IS 'Fecha de registro de la nota débito';