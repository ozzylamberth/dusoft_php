--claudia - MODULO DE ENTREGA DE RESULTADOS - 22-10

INSERT INTO system_modulos VALUES ('Os_Entrega_Apoyod', 'app', 'Entrega de Resultados de Apoyos', 1.00, '1', '1', '1', '1');
INSERT INTO "system_menus" ("menu_id", "menu_nombre", "descripcion", "sw_system") VALUES (60, 'ENTREGA DE RESULTADOS APOYOD', 'Entrega de Resultados de Apoyos Diagnosticos', 0);
INSERT INTO "system_menus_items" ("menu_item_id", "menu_id", "titulo", "modulo_tipo", "modulo", "tipo", "metodo", "descripcion", "indice_de_orden") VALUES (91, '60', 'ENTREGA DE RESULTADOS APOYOD', 'app', 'Os_Entrega_Apoyod', 'user', 'main', 'Entrega de Resultados de Apoyos', 0);
INSERT INTO system_usuarios_menus VALUES (60, 2);




CREATE TABLE apoyod_entrega_resultados (
    apoyod_entrega_id serial NOT NULL,
    tipo_parentesco_id character varying(2),
    nombre character varying(80),
    telefono character varying(30),
    observacion text,
    fecha_entrega timestamp without time zone NOT NULL,
    sw_tipo_persona character(1)
);


ALTER TABLE ONLY apoyod_entrega_resultados
    ADD CONSTRAINT apoyod_entrega_resultados_pkey PRIMARY KEY (apoyod_entrega_id);



ALTER TABLE ONLY apoyod_entrega_resultados
    ADD CONSTRAINT "$2" FOREIGN KEY (tipo_parentesco_id) REFERENCES tipos_parentescos(tipo_parentesco_id) ON UPDATE CASCADE ON DELETE RESTRICT;



CREATE TABLE apoyod_entrega_resultados_detalle (
    apoyod_entrega_id integer NOT NULL,
    resultado_id integer NOT NULL
);


ALTER TABLE ONLY apoyod_entrega_resultados_detalle
    ADD CONSTRAINT apoyod_entrega_resultados_detalle_pkey PRIMARY KEY (apoyod_entrega_id, resultado_id);


ALTER TABLE ONLY apoyod_entrega_resultados_detalle
    ADD CONSTRAINT "$1" FOREIGN KEY (apoyod_entrega_id) REFERENCES apoyod_entrega_resultados(apoyod_entrega_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY apoyod_entrega_resultados_detalle
    ADD CONSTRAINT "$2" FOREIGN KEY (resultado_id) REFERENCES hc_resultados_sistema(resultado_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE "hc_resultados_sistema" ADD COLUMN "apoyod_entrega_id" integer;

ALTER TABLE "hc_resultados_sistema" ADD FOREIGN KEY ("apoyod_entrega_id") REFERENCES "public"."apoyod_entrega_resultados"("apoyod_entrega_id")  ON UPDATE CASCADE ON DELETE RESTRICT;



CREATE TABLE userpermisos_os_entrega_apoyod (
    usuario_id integer NOT NULL,
    departamento character varying(6) NOT NULL
);


ALTER TABLE ONLY userpermisos_os_entrega_apoyod
    ADD CONSTRAINT userpermisos_os_entrega_apoyod_pkey PRIMARY KEY (usuario_id, departamento);


ALTER TABLE ONLY userpermisos_os_entrega_apoyod
    ADD CONSTRAINT "$1" FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY userpermisos_os_entrega_apoyod
    ADD CONSTRAINT "$2" FOREIGN KEY (departamento) REFERENCES departamentos(departamento) ON UPDATE CASCADE ON DELETE RESTRICT;


-- claudia 28-10

ALTER TABLE "apoyod_entrega_resultados" ADD COLUMN "tipo_id_paciente" character varying(3);
ALTER TABLE "apoyod_entrega_resultados" ALTER COLUMN "tipo_id_paciente" SET NOT NULL;
ALTER TABLE "apoyod_entrega_resultados" ADD COLUMN "paciente_id" character varying(32);
ALTER TABLE "apoyod_entrega_resultados" ALTER COLUMN "paciente_id" SET NOT NULL;



--claudia 02-11
CREATE TABLE apoyod_reentrega_resultados (
    apoyod_reentrega_id serial NOT NULL,
    apoyod_entrega_id integer NOT NULL,
    resultado_id integer NOT NULL,
    fecha_entrega timestamp without time zone NOT NULL,
    observacion text
);


ALTER TABLE ONLY apoyod_reentrega_resultados
    ADD CONSTRAINT apoyod_reentrega_resultados_pkey PRIMARY KEY (apoyod_reentrega_id);


ALTER TABLE ONLY apoyod_reentrega_resultados
    ADD CONSTRAINT "$1" FOREIGN KEY (apoyod_entrega_id, resultado_id) REFERENCES apoyod_entrega_resultados_detalle(apoyod_entrega_id, resultado_id) ON UPDATE CASCADE ON DELETE RESTRICT;

