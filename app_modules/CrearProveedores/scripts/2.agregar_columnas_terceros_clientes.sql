ALTER TABLE terceros_clientes ADD COLUMN sw_cree character(1) DEFAULT 0, ADD COLUMN porcentaje_cree numeric(9,4) DEFAULT 0.0;


COMMENT ON COLUMN "terceros_clientes"."sw_cree" 
	IS 'Define si el Proveedor le es aplicado el porcentaje del Impto CREE';

COMMENT ON COLUMN "terceros_clientes"."porcentaje_cree" 
IS 'Porcentaje Impto CREE';