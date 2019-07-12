
CREATE TABLE banco_sangre_usuarios_distribuyen(
    usuario_id integer
);
ALTER TABLE ONLY banco_sangre_usuarios_distribuyen ADD FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
INSERT INTO banco_sangre_usuarios_distribuyen(usuario_id)VALUES('2');
CREATE TABLE banco_sangre_entrega_bolsas(
  ingreso_bolsa_id integer NOT NULL,
	paciente_id character varying(3) NOT NULL,
  tipo_id_paciente character varying(32) NOT NULL,
  solicitud_reserva_sangre_id integer,
	tipo_componente_id integer,
	usuario_id integer,
	fecha_registro timestamp without time zone
);
ALTER TABLE ONLY banco_sangre_entrega_bolsas ADD FOREIGN KEY (ingreso_bolsa_id) REFERENCES banco_sangre_bolsas(ingreso_bolsa_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY banco_sangre_entrega_bolsas ADD FOREIGN KEY (paciente_id,tipo_id_paciente) REFERENCES pacientes(paciente_id,tipo_id_paciente) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY banco_sangre_entrega_bolsas ADD FOREIGN KEY (solicitud_reserva_sangre_id,tipo_componente_id) REFERENCES banco_sangre_reserva_detalle(solicitud_reserva_sangre_id,tipo_componente_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY banco_sangre_entrega_bolsas ADD FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY banco_sangre_entrega_bolsas ADD COLUMN observaciones text;
ALTER TABLE ONLY banco_sangre_entrega_bolsas ADD COLUMN a_quien_entrega character varying(50);
 
