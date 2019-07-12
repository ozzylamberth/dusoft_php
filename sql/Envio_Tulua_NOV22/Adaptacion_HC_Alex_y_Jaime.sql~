
ALTER TABLE public.historias_clinicas_templates ADD COLUMN sw_obligatorio_cierre char(1);
update historias_clinicas_templates set sw_obligatorio_cierre='0';
ALTER TABLE public.historias_clinicas_templates ALTER COLUMN sw_obligatorio_cierre SET NOT NULL;
ALTER TABLE public.historias_clinicas_templates ALTER COLUMN sw_obligatorio_cierre SET DEFAULT 0;


CREATE TABLE historias_clinicas_tipos_cierres (
    historia_clinica_tipo_cierre_id serial NOT NULL,
    descripcion character varying(255) NOT NULL,
    titulo_mostrar character varying(80) NOT NULL,
    sw_pedir_submodulo_obligatorios character(1) DEFAULT '0'::bpchar NOT NULL
);


ALTER TABLE ONLY historias_clinicas_tipos_cierres
    ADD PRIMARY KEY (historia_clinica_tipo_cierre_id);


CREATE TABLE historias_clinicas_tipos_cierres_submodulos (
    historia_clinica_tipo_cierre_id integer NOT NULL,
    submodulo character varying(64) NOT NULL,
    indice_orden smallint DEFAULT 0 NOT NULL
);



ALTER TABLE ONLY historias_clinicas_tipos_cierres_submodulos
    ADD PRIMARY KEY (historia_clinica_tipo_cierre_id, submodulo);


ALTER TABLE ONLY historias_clinicas_tipos_cierres_submodulos
    ADD FOREIGN KEY (historia_clinica_tipo_cierre_id) REFERENCES historias_clinicas_tipos_cierres(historia_clinica_tipo_cierre_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY historias_clinicas_tipos_cierres_submodulos
    ADD FOREIGN KEY (submodulo) REFERENCES system_hc_submodulos(submodulo) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE public.system_hc_submodulos ADD COLUMN sw_submodulo_sistema char(1);
update system_hc_submodulos set sw_submodulo_sistema='0';
ALTER TABLE public.system_hc_submodulos ALTER COLUMN sw_submodulo_sistema SET NOT NULL;
ALTER TABLE public.system_hc_submodulos ALTER COLUMN sw_submodulo_sistema SET DEFAULT '0'::bpchar;


CREATE TABLE historias_clinicas_cierres (
    historia_clinica_tipo_cierre_id integer NOT NULL,
    hc_modulo character varying(64) NOT NULL,
    indice_orden smallint DEFAULT 0 NOT NULL
);


ALTER TABLE ONLY historias_clinicas_cierres
    ADD PRIMARY KEY (historia_clinica_tipo_cierre_id, hc_modulo);


ALTER TABLE ONLY historias_clinicas_cierres
    ADD FOREIGN KEY (historia_clinica_tipo_cierre_id) REFERENCES historias_clinicas_tipos_cierres(historia_clinica_tipo_cierre_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY historias_clinicas_cierres
    ADD FOREIGN KEY (hc_modulo) REFERENCES system_hc_modulos(hc_modulo) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE public.hc_evoluciones ADD COLUMN historia_clinica_tipo_cierre_id int4;

-- INSERT 0

INSERT INTO system_hc_modulos VALUES ('AtencionInterconsulta', 'AtencionInterconsulta', '1', '10', 'AC');
INSERT INTO system_hc_modulos VALUES ('QX_notas_operatorias', 'notas operatorias de las cirugias', '1', '10', 'AC');
INSERT INTO system_hc_modulos VALUES ('UrgenciasConsulta', 'Consulta de Urgencias', '1', '10', 'AC');
INSERT INTO system_hc_modulos VALUES ('UrgenciasPediatria', 'Urgencias Pediatricas', '1', '10', 'AC');

-- INSERT 1
INSERT INTO historias_clinicas_tipos_cierres VALUES (1, 'CONTINUAR EN HOSPITALIZACION', 'Continuar en Hospitalización', '0');
INSERT INTO historias_clinicas_tipos_cierres VALUES (2, 'CONTINUAR EN OBSERVACION', 'Continuar en Observación', '0');
INSERT INTO historias_clinicas_tipos_cierres VALUES (3, 'OBSERVACION URGENCIAS', 'Observación de Urgencias', '0');
INSERT INTO historias_clinicas_tipos_cierres VALUES (6, 'HOSPITALIZAR', 'Hospitalizar', '0');
INSERT INTO historias_clinicas_tipos_cierres VALUES (7, 'REMITIR A CIRUGIA', 'Remitir a Cirugía', '0');
INSERT INTO historias_clinicas_tipos_cierres VALUES (10, 'TRASLADO DE DEPARTAMENTO', 'Traslado de Departamento', '0');
INSERT INTO historias_clinicas_tipos_cierres VALUES (4, 'DAR DE ALTA EN CONSULTA DE URGENCIAS', 'Dar de Alta de la Consulta de Urgencias', '1');
INSERT INTO historias_clinicas_tipos_cierres VALUES (5, 'DAR DE ALTA OBSERVACION DE URGENCIAS', 'Dar de Alta Observacion de Urgencias', '1');
INSERT INTO historias_clinicas_tipos_cierres VALUES (8, 'DEFUNCION DEL PACIENTE', 'Defuncion del Paciente', '1');
INSERT INTO historias_clinicas_tipos_cierres VALUES (9, 'REMISION EXTERNA', 'Remitir a otra Institución', '1');

-- INSERT 2
INSERT INTO historias_clinicas_cierres VALUES (4, 'UrgenciasConsulta', 1);
INSERT INTO historias_clinicas_cierres VALUES (3, 'UrgenciasConsulta', 2);
INSERT INTO historias_clinicas_cierres VALUES (9, 'UrgenciasConsulta', 3);
INSERT INTO historias_clinicas_cierres VALUES (6, 'UrgenciasConsulta', 4);
INSERT INTO historias_clinicas_cierres VALUES (7, 'UrgenciasConsulta', 5);
INSERT INTO historias_clinicas_cierres VALUES (8, 'UrgenciasConsulta', 6);

-- INSERT 3

--INSERT INTO historias_clinicas_tipos_cierres_submodulos VALUES (4, 'Evolucion', 0);
INSERT INTO historias_clinicas_tipos_cierres_submodulos VALUES (3, 'ObservacionUrgencias', 0);
INSERT INTO historias_clinicas_tipos_cierres_submodulos VALUES (9, 'Remisiones', 0);
INSERT INTO historias_clinicas_tipos_cierres_submodulos VALUES (8, 'Certificado_Defuncion', 0);
INSERT INTO historias_clinicas_tipos_cierres_submodulos VALUES (1, 'ConfirmarCierreHC', 0);
INSERT INTO historias_clinicas_tipos_cierres_submodulos VALUES (2, 'ConfirmarCierreHC', 0);
INSERT INTO historias_clinicas_tipos_cierres_submodulos VALUES (4, 'AltaConsultaUrgencias', 0);


--

ALTER TABLE "hc_tipos_antecedentes_det" DROP COLUMN "sw_defecto";

