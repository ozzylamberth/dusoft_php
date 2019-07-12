ALTER TABLE "banco_sangre_cruzes_sanguineos" ADD COLUMN "rai_otros" character(1);

ALTER TABLE terceros_sgsss ADD COLUMN tipo_cliente character varying(2);

ALTER TABLE terceros_sgsss ALTER COLUMN tipo_cliente SET NOT NULL;

ALTER TABLE ONLY terceros_sgsss ADD FOREIGN KEY (tipo_cliente)
REFERENCES tipos_cliente(tipo_cliente) ON UPDATE CASCADE ON DELETE RESTRICT;
