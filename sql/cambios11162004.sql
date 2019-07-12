



CREATE TABLE egresos_no_atencion(
		egresos_no_atencion_id serial NOT NULL,
    tipo_id_paciente character varying(3) NOT NULL,
    paciente_id character varying(32) NOT NULL,
		observacion  text NOT NULL,
		ingreso integer,
		triage_id integer,
    fecha_registro timestamp without time zone NOT NULL,
    usuario_id integer NOT NULL
);

ALTER TABLE ONLY egresos_no_atencion
    ADD PRIMARY KEY (egresos_no_atencion_id);

ALTER TABLE ONLY egresos_no_atencion
    ADD FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY egresos_no_atencion
    ADD FOREIGN KEY (paciente_id, tipo_id_paciente) REFERENCES pacientes(paciente_id, tipo_id_paciente) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY egresos_no_atencion
    ADD FOREIGN KEY (ingreso) REFERENCES ingresos(ingreso) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY egresos_no_atencion
    ADD FOREIGN KEY (triage_id) REFERENCES triages(triage_id) ON UPDATE CASCADE ON DELETE RESTRICT;


