CREATE SEQUENCE numero_remision minvalue 300 maxvalue 100000 NO CYCLE;


 ALTER TABLE "remisiones_pacientes" ADD COLUMN remision_id integer;