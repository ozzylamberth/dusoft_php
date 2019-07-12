
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

INSERT INTO historias_clinicas_tipos_cierres_submodulos VALUES (4, 'Evolucion', 0);

--ACTUALIZACIONES HISTORIAS CLINICAS TEMPLATES

UPDATE system_hc_submodulos SET descripcion = 'Ingreso de Personas Gestantes' WHERE submodulo = 'IngresoGestacion';

INSERT INTO system_hc_submodulos VALUES ('NotasEnfermeria', 'NOTAS DE ENFERMERIA', 1.00, '', '1', NULL, NULL, NULL, NULL, '0');
INSERT INTO system_hc_submodulos VALUES ('SignosVitalesHospitalizacion', 'Signos Vitales Generales', 1.00, '1', '1', NULL, NULL, NULL, NULL, '0');
INSERT INTO system_hc_submodulos VALUES ('Asistencia_Ventilatoria', 'Asistencia Ventilatoria UCI', 1.00, '1', '1', NULL, NULL, NULL, NULL, '0');
INSERT INTO system_hc_submodulos VALUES ('NotasOperatorias', 'Notas operatorias de los cirujanos', 1.00, '', '1', NULL, NULL, NULL, NULL, '0');
INSERT INTO system_hc_submodulos VALUES ('Control_Neurologico', 'Control Neurologico', 1.00, '1', '1', NULL, NULL, NULL, NULL, '0');
INSERT INTO system_hc_submodulos VALUES ('Consulta_Triage', 'Reporte Triage', 1.00, '', '1', NULL, NULL, NULL, NULL, '0');
INSERT INTO system_hc_submodulos VALUES ('ObservacionUrgencias', 'ObservacionUrgencias', 1.00, '', '1', NULL, NULL, NULL, NULL, '1');
INSERT INTO system_hc_submodulos VALUES ('Remisiones', 'Remisiones', 1.00, '', '1', NULL, NULL, NULL, NULL, '1');
INSERT INTO system_hc_submodulos VALUES ('Certificado_Defuncion', 'Ceritificado de Defuncion', 1.00, '', '1', NULL, NULL, NULL, NULL, '1');
INSERT INTO system_hc_submodulos VALUES ('ConfirmarCierreHC', 'Confirma el cierre de una HC', 1.00, '', '1', NULL, NULL, NULL, NULL, '1');
INSERT INTO system_hc_submodulos VALUES ('AltaConsultaUrgencias', 'Metodo dar de alta en consulta Urgencias', 1.00, '', '1', NULL, NULL, NULL, NULL, '1');

INSERT INTO historia_clinica_secciones VALUES ('3', 'EVOLUCION');
UPDATE historia_clinica_secciones SET descripcion = 'HISTORIA CLINICA' WHERE hc_seccion_id = '1';
UPDATE historia_clinica_secciones SET descripcion = 'FORMULACION' WHERE hc_seccion_id = '2';

INSERT INTO historias_clinicas_templates VALUES ('EstacionEnfermeria', 'NotasEnfermeria', 0, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('EstacionEnfermeria', 'PlanTerapeutico', 1, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('EstacionEnfermeria', 'Asistencia_Ventilatoria', 2, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('EstacionEnfermeria', 'SignosVitalesHospitalizacion', 3, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('EstacionEnfermeria', 'Control_Neurologico', 4, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('EstacionEnfermeria', 'CargosDirectos', 5, 0, '1', '1', '0', '0');

INSERT INTO historias_clinicas_templates VALUES ('UrgenciasConsulta', 'SignosVitalesHospitalizacion', 0, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('UrgenciasConsulta', 'MotivoConsulta', 1, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('UrgenciasConsulta', 'Atencion', 2, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('UrgenciasConsulta', 'AntecedentesFamiliares', 3, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('UrgenciasConsulta', 'AntecedentesPersonales', 4, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('UrgenciasConsulta', 'AntecedentesGinecoObstetricos', 5, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('UrgenciasConsulta', 'ExamenFisico', 7, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('UrgenciasConsulta', 'DiagnosticoI', 8, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('UrgenciasConsulta', 'PlanTerapeutico', 9, 0, '2', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('UrgenciasConsulta', 'OrdenesMedicas', 10, 0, '2', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('UrgenciasConsulta', 'PlanTerapeuticoTexto', 11, 0, '2', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('UrgenciasConsulta', 'Interconsulta', 12, 0, '2', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('UrgenciasConsulta', 'Apoyos_Diagnosticos_Solicitud', 13, 0, '2', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('UrgenciasConsulta', 'Solicitud_De_Procedimientos_Qx', 14, 0, '2', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('UrgenciasConsulta', 'Solicitud_De_Procedimientos_NO_Qx', 15, 0, '2', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('UrgenciasConsulta', 'ReservaSangre', 16, 0, '2', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('UrgenciasConsulta', 'Generacion_Incapacidades', 17, 0, '2', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('UrgenciasConsulta', 'Apoyos_Diagnosticos', 18, 0, '3', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('UrgenciasConsulta', 'Evolucion', 19, 0, '3', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('UrgenciasConsulta', 'DiagnosticoE', 20, 0, '3', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('UrgenciasConsulta', 'Consulta_Triage', 21, 0, '1', '1', '0', '0');

INSERT INTO historias_clinicas_templates VALUES ('UrgenciasPediatria', 'MotivoConsulta', 1, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('UrgenciasPediatria', 'RevisionxSistemas', 2, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('UrgenciasPediatria', 'AntecedentesFamiliares', 3, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('UrgenciasPediatria', 'AntecedentesPersonales', 4, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('UrgenciasPediatria', 'AntecedentesPerinatales', 5, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('UrgenciasPediatria', 'Vacunacion', 6, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('UrgenciasPediatria', 'SignosVitalesHospitalizacion', 7, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('UrgenciasPediatria', 'ExamenFisico', 8, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('UrgenciasPediatria', 'DiagnosticoI', 9, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('UrgenciasPediatria', 'OrdenesMedicas', 10, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('UrgenciasPediatria', 'PlanTerapeuticoTexto', 11, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('UrgenciasPediatria', 'Apoyos_Diagnosticos_Solicitud', 12, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('UrgenciasPediatria', 'Interconsulta', 13, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('UrgenciasPediatria', 'ReservaSangre', 14, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('UrgenciasPediatria', 'Solicitud_De_Procedimientos_NO_Qx', 15, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('UrgenciasPediatria', 'Solicitud_De_Procedimientos_Qx', 16, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('UrgenciasPediatria', 'Apoyos_Diagnosticos', 17, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('UrgenciasPediatria', 'Evolucion', 18, 0, '1', '1', '0', '0');

INSERT INTO historias_clinicas_templates VALUES ('AtencionInterconsulta', 'MotivoConsulta', 1, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('AtencionInterconsulta', 'AntecedentesPersonales', 2, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('AtencionInterconsulta', 'AntecedentesFamiliares', 3, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('AtencionInterconsulta', 'AntecedentesGinecoObstetricos', 4, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('AtencionInterconsulta', 'RevisionxSistemas', 5, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('AtencionInterconsulta', 'ExamenFisico', 6, 1, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('AtencionInterconsulta', 'SignosVitales', 6, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('AtencionInterconsulta', 'Apoyos_Diagnosticos', 7, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('AtencionInterconsulta', 'DiagnosticoI', 8, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('AtencionInterconsulta', 'Atencion', 9, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('AtencionInterconsulta', 'DatosEmbarazo', 10, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('AtencionInterconsulta', 'Apoyos_Diagnosticos_Solicitud', 11, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('AtencionInterconsulta', 'Interconsulta', 12, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('AtencionInterconsulta', 'PlanTerapeutico', 13, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('AtencionInterconsulta', 'PlanTerapeuticoTexto', 14, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('AtencionInterconsulta', 'Generacion_Incapacidades', 15, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('AtencionInterconsulta', 'Solicitud_De_Procedimientos_Qx', 16, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('AtencionInterconsulta', 'Evolucion', 17, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('AtencionInterconsulta', 'Finalidad', 18, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('AtencionInterconsulta', 'ReservaSangre', 19, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('AtencionInterconsulta', 'Cronicos', 20, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('AtencionInterconsulta', 'ProtocolosMedicos', 21, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('AtencionInterconsulta', 'ControlPrenatal', 22, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('AtencionInterconsulta', 'Solicitud_De_Procedimientos_NO_Qx', 23, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('AtencionInterconsulta', 'NotasEnfermeria', 24, 0, '1', '1', '0', '0');

INSERT INTO historias_clinicas_templates VALUES ('QX_notas_operatorias', 'NotasOperatorias', 0, 0, '1', '1', '0', '0');
INSERT INTO historias_clinicas_templates VALUES ('QX_notas_operatorias', 'Evolucion', 1, 0, '1', '1', '0', '0');


INSERT INTO system_modulos_variables VALUES ('', '', 'max_edad_lactante', '2');


-- INSERT 5

INSERT INTO historias_clinicas_tipos_cierres_submodulos VALUES (3, 'ObservacionUrgencias', 0);
INSERT INTO historias_clinicas_tipos_cierres_submodulos VALUES (9, 'Remisiones', 0);
INSERT INTO historias_clinicas_tipos_cierres_submodulos VALUES (8, 'Certificado_Defuncion', 0);
INSERT INTO historias_clinicas_tipos_cierres_submodulos VALUES (1, 'ConfirmarCierreHC', 0);
INSERT INTO historias_clinicas_tipos_cierres_submodulos VALUES (2, 'ConfirmarCierreHC', 0);

-- UPDATE

UPDATE historias_clinicas_tipos_cierres_submodulos SET submodulo = 'AltaConsultaUrgencias' WHERE historia_clinica_tipo_cierre_id = 4;


--

--ALTER TABLE "hc_tipos_antecedentes_det" DROP COLUMN "sw_defecto";

