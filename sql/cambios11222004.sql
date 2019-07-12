
CREATE TABLE puntos_salidas_pacientes (
    punto_salida_paciente_id serial NOT NULL,
    descripcion character varying(40) NOT NULL,
    empresa_id character(2) NOT NULL,
    centro_utilidad character(2) NOT NULL,
    sw_todos_cu character(1) NOT NULL
);


ALTER TABLE ONLY puntos_salidas_pacientes
    ADD CONSTRAINT puntos_salidas_pacientes_pkey PRIMARY KEY (punto_salida_paciente_id);

ALTER TABLE ONLY puntos_salidas_pacientes
    ADD FOREIGN KEY (empresa_id, centro_utilidad) REFERENCES centros_utilidad(empresa_id, centro_utilidad) ON UPDATE CASCADE ON DELETE RESTRICT;



CREATE TABLE userpermisos_salidas_pacientes (
    usuario_id integer NOT NULL,
    punto_salida_paciente_id integer NOT NULL
);


ALTER TABLE ONLY userpermisos_salidas_pacientes
    ADD CONSTRAINT userpermisos_salidas_pacientes_pkey PRIMARY KEY (usuario_id, punto_salida_paciente_id);

ALTER TABLE ONLY userpermisos_salidas_pacientes
    ADD FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY userpermisos_salidas_pacientes
    ADD FOREIGN KEY (punto_salida_paciente_id) REFERENCES puntos_salidas_pacientes(punto_salida_paciente_id) ON UPDATE CASCADE ON DELETE RESTRICT;


INSERT INTO system_modulos VALUES ('SalidaPacientes', 'app', 'Para la salida de pacientes', 1.00, '', '1', '1', '1');

INSERT INTO "system_menus" ("menu_id", "menu_nombre", "descripcion", "sw_system") VALUES (64, 'SALIDA PACIENTES', ''::character varying, 0);
INSERT INTO "system_menus_items" ("menu_item_id", "menu_id", "titulo", "modulo_tipo", "modulo", "tipo", "metodo", "descripcion", "indice_de_orden") VALUES (96, 64, 'SALIDA PACIENTE', 'app', 'SalidaPacientes', 'user', 'main', 'Salida de pacientes'::character varying, 0);

INSERT INTO system_usuarios_menus VALUES (64, 2);

DROP TABLE tmp_cirugias_detalle CASCADE;
DROP TABLE tmp_cirugias_otros_cargos CASCADE;
DROP TABLE tmp_cirugias_quirofano CASCADE;
DROP TABLE tmp_cirugias CASCADE;


--claudia no se envia a tulua porque alla ese campo no se creo.
ALTER TABLE "hc_medicamentos_recetados_amb" DROP COLUMN "sw_estado"


