ALTER TABLE ONLY banco_sangre_entrega_bolsas DROP COLUMN tipo_id_paciente;
ALTER TABLE ONLY banco_sangre_entrega_bolsas DROP COLUMN paciente_id;
ALTER TABLE ONLY banco_sangre_entrega_bolsas ADD COLUMN tipo_id_paciente character varying(3);
ALTER TABLE ONLY banco_sangre_entrega_bolsas ALTER COLUMN tipo_id_paciente SET NOT NULL;
ALTER TABLE ONLY banco_sangre_entrega_bolsas ADD COLUMN paciente_id character varying(32);
ALTER TABLE ONLY banco_sangre_entrega_bolsas ALTER COLUMN paciente_id SET NOT NULL;
INSERT INTO banco_sangre_estados_bolsas_alicuotas(estado,descripcion)VALUES('5','ENTREGADA SIN TRANSFUNDIR');
