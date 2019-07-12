
ALTER TABLE "gestacion" DROP CONSTRAINT "$1" CASCADE;

ALTER TABLE gestacion
ADD FOREIGN KEY (tipo_id_paciente, paciente_id) REFERENCES pacientes(tipo_id_paciente, paciente_id) ON UPDATE CASCADE ON DELETE RESTRICT;
