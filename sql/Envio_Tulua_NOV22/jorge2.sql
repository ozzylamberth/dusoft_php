INSERT INTO system_modulos_variables VALUES ('Soat', 'app', 'nit_id_fidusalud', '830031511-6');
INSERT INTO system_modulos_variables VALUES ('Soat', 'app', 'nit_tipo_fidusalud', 'NIT');

ALTER TABLE "terceros_sgsss" DROP COLUMN "tipo_cliente";

DROP TABLE planes_honorarios;
DROP TABLE excepciones_honorarios;
DROP TABLE tarifarios_detalle2;

DROP FUNCTION borrar_excepciones_honorarios();
DROP FUNCTION borrar_excepciones_honorarios_mod(text, text, text, text);
DROP FUNCTION excepciones_honorario(text, text, text);
