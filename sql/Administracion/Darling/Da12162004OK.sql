
INSERT INTO pacientes_campos_obligatorios VALUES ('segundo_apellido', '1', '0');
INSERT INTO pacientes_campos_obligatorios VALUES ('segundo_nombre', '1', '0');
INSERT INTO pacientes_campos_obligatorios VALUES ('ocupacion_id', '1', '0');
INSERT INTO pacientes_campos_obligatorios VALUES ('tipo_estado_civil_id', '1', '0');
INSERT INTO pacientes_campos_obligatorios VALUES ('lugar_residencia', '1', '0');
INSERT INTO pacientes_campos_obligatorios VALUES ('nombre_madre', '1', '0');
INSERT INTO pacientes_campos_obligatorios VALUES ('observaciones', '1', '0');

ALTER TABLE "pacientes" ALTER COLUMN "tipo_pais_id" drop not null;
ALTER TABLE "pacientes" ALTER COLUMN "tipo_dpto_id" drop not null;
ALTER TABLE "pacientes" ALTER COLUMN "tipo_mpio_id" drop not null;
