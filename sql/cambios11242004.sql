CREATE TABLE banco_sangre_motivos_insercion_bolsas(
  codigo_motivo character(1),
	descripcion character(20)
);
ALTER TABLE ONLY banco_sangre_motivos_insercion_bolsas ADD PRIMARY KEY(codigo_motivo);
INSERT INTO banco_sangre_motivos_insercion_bolsas(codigo_motivo,descripcion)VALUES('C','ADQUISICION');
INSERT INTO banco_sangre_motivos_insercion_bolsas(codigo_motivo,descripcion)VALUES('D','DEVOLUCION');
INSERT INTO banco_sangre_motivos_insercion_bolsas(codigo_motivo,descripcion)VALUES('P','PRESTAMO');
INSERT INTO banco_sangre_motivos_insercion_bolsas(codigo_motivo,descripcion)VALUES('I','PEDIDO');
ALTER TABLE "banco_sangre_bolsas" ADD FOREIGN KEY ("motivo_insercion") REFERENCES "public"."banco_sangre_motivos_insercion_bolsas"("codigo_motivo")  ON UPDATE RESTRICT ON DELETE CASCADE;
ALTER TABLE "banco_sangre_bolsas" ADD COLUMN albaran character varying(20);


--claudia ok enviado a tulua 01/dic/2004
--ok enviado a bogota 14/dic/2004 5pm.
ALTER TABLE "apoyod_entrega_resultados" ADD COLUMN "tipo_id_funcionario" character varying(3);
ALTER TABLE "apoyod_entrega_resultados" ADD COLUMN "funcionario_id" character varying(32);
