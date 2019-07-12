    CREATE TABLE departamentos_cargos_citas (
    departamento character varying(6) NOT NULL,
    cargo character varying(10) NOT NULL,
    tipo_equipo_imagen_id character varying(2) NOT NULL
    );

ALTER TABLE ONLY departamentos_cargos_citas
    ADD CONSTRAINT departamentos_cargos_citas_pkey PRIMARY KEY (departamento, cargo, tipo_equipo_imagen_id);

ALTER TABLE ONLY departamentos_cargos_citas
    ADD CONSTRAINT "$1" FOREIGN KEY (departamento, cargo) REFERENCES departamentos_cargos(departamento, cargo) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY departamentos_cargos_citas
    ADD CONSTRAINT "$2" FOREIGN KEY (tipo_equipo_imagen_id) REFERENCES os_imagenes_tipo_equipos(tipo_equipo_imagen_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE os_imagenes_tipo_equipos add column sw_cita character(1);

GRANT ALL ON TABLE public.departamentos_cargos_citas TO admin WITH GRANT OPTION;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE public.departamentos_cargos_citas TO siis;
GRANT SELECT ON TABLE public.departamentos_cargos_citas TO siis_consulta;

--sb1

--Las columnas email y telefono de la tabla terceros, deben ser modificadas por
--pgAdmin3, la columna email a varchar 60 y telefono a varchar 30

CREATE TABLE recepciones_ordenes(
    recepcion_solicitud_id serial NOT NULL,
    orden_servicio_id integer NOT NULL,
    nombre character varying(40) NULL,
    usuario_id integer NOT NULL,
    fecha_registro timestamp without time zone NOT NULL,
    sw_personalmente character(1)
);

ALTER TABLE ONLY recepciones_ordenes
    ADD CONSTRAINT recepciones_ordenes_pkey PRIMARY KEY (recepcion_solicitud_id);

ALTER TABLE ONLY recepciones_ordenes
    ADD CONSTRAINT "$1" FOREIGN KEY (orden_servicio_id) REFERENCES os_ordenes_servicios(orden_servicio_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY recepciones_ordenes
    ADD CONSTRAINT "$2" FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;

--sb2

ALTER TABLE "agenda_citas_asignadas" ADD COLUMN "sw_historia" character(1);
update agenda_citas_asignadas set sw_historia='0';
ALTER TABLE public.agenda_citas_asignadas ALTER COLUMN sw_historia SET DEFAULT '0';
ALTER TABLE public.agenda_citas_asignadas ALTER COLUMN sw_historia SET NOT NULL;
COMMENT ON COLUMN public.agenda_citas_asignadas.sw_historia IS 'Dato para pedir la historia clinica anterior en la cita medica';

create table qx_uvrs_valor_sala_mas_450(
tarifario_id character varying(4),
valor numeric(12,2)
);
ALTER TABLE ONLY qx_uvrs_valor_sala_mas_450
ADD CONSTRAINT "$1" FOREIGN KEY (tarifario_id) REFERENCES tarifarios(tarifario_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE qx_acto DROP COLUMN departamento;
ALTER TABLE qx_acto ADD COLUMN departamento character varying(6);

--sb3

ALTER TABLE qx_acto_procedimientos_realizados DROP COLUMN departamento;
ALTER TABLE qx_acto_procedimientos_realizados ADD COLUMN departamento character varying(6);

CREATE TABLE qx_tipos_liquidaciones(
    tipo_liquidacion_id character varying(4),
		descripcion  	character varying(40)
);

ALTER TABLE ONLY qx_tipos_liquidaciones
ADD CONSTRAINT qx_tipos_liquidaciones_pkey PRIMARY KEY (tipo_liquidacion_id);

--sb4

alter table userpermisos_os_lista_trabajo add column sw_mostrar_listas character(1);
alter table userpermisos_os_lista_trabajo alter column sw_mostrar_listas SET DEFAULT '0';
ALTER TABLE ONLY userpermisos_os_lista_trabajo ADD CONSTRAINT userpermisos_os_lista_trabajo_pkey PRIMARY KEY (usuario_id, departamento);

CREATE TABLE userpermisos_os_lista_trabajo_detalle (
    usuario_id integer NOT NULL,
    departamento character varying NOT NULL,
    tipo_os_lista_id integer NOT NULL
);

ALTER TABLE ONLY userpermisos_os_lista_trabajo_detalle
    ADD CONSTRAINT userpermisos_os_lista_trabajo_detalle_pkey PRIMARY KEY (usuario_id, departamento, tipo_os_lista_id);

ALTER TABLE ONLY userpermisos_os_lista_trabajo_detalle
    ADD CONSTRAINT "$1" FOREIGN KEY (usuario_id, departamento) REFERENCES userpermisos_os_lista_trabajo(usuario_id, departamento);

--sb5

INSERT INTO system_modulos VALUES ('ContabilidadPara', 'app', 'Parametros de la Contabilidad', 1.00, '', '1', '1', '1');
INSERT INTO system_modulos VALUES ('Contabilidad', 'app', 'Contabilidad', 1.00, '', '1', '1', '1');

INSERT INTO system_menus VALUES (47, 'CONTABILIDAD - PARAMETROS', 'Parametros de la Contabilidad', '0');
INSERT INTO system_menus VALUES (48, 'CONTABILIDAD', 'Contabilidad', '0');

INSERT INTO system_menus_items VALUES (76, 47, 'CONTABILIDAD - PARAMETROS', 'app', 'ContabilidadPara', 'user', 'main', 'Parametros de la Contabilidad', 0);
INSERT INTO system_menus_items VALUES (77, 48, 'CONTABILIDAD', 'app', 'Contabilidad', 'user', 'main', '', 0);

INSERT INTO system_usuarios_menus VALUES (47, 2);
INSERT INTO system_usuarios_menus VALUES (48, 2);

--sb6