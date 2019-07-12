INSERT INTO system_hc_submodulos(
	submodulo, 
	descripcion, 
	version_numero, 
	version_info, 
	activo, 
	sexo_id,
	gestacion, 
	edad_max, 
	edad_min, 
	sw_submodulo_sistema, 
	sw_imprime, 
	sw_print_persist    
)
VALUES(
	'Contrareferencias', 
	'Efectuar Contrareferencias', 
	1.00, 
	'1', 
	'1', 
	NULL, 
	NULL, 
	NULL, 
	NULL, 
	'0', 
	'1', 
	'0'
);

INSERT INTO historias_clinicas_templates(
	hc_modulo, 
	submodulo, 
	paso, 
	secuencia, 
	hc_seccion_id, 
	sw_mostrar, 
	sw_siquiatria, 
	sw_obligatorio_cierre, 
	titulo_mostrar, 
	parametros, 
	sexo_id, 
	edad_max, 
	edad_min, 
	sw_omitir
) 
VALUES(
	'Hospitalizacion', 
	'Contrareferencias', 
	34, 
	0, 
	'1', 
	'1', 
	'0', 
	'0', 
	NULL, 
	NULL, 
	NULL, 
	NULL, 
	NULL, 
	'0'
);


--Tabla de las contrareferencias que tiene cada paciente
CREATE TABLE contrareferencias(
		contrareferencia_id serial NOT NULL, 
		paciente_id character varying(32) NOT NULL, 
		tipo_id_paciente character varying(3) NOT NULL, 
		fecha date NOT NULL,
		empr_trab text,
		estableci_id character varying(10) NOT NULL, 
		servicio_id character varying(2) NOT NULL, 
		resum_cuadr_clin text, 
		hallaz_relevan_exam text, 
		trat_proc_tera_reali text, 
		plan_trata_recom text, 
		sala character varying(4), 
		cama character varying(4), 
		cod_profes integer NOT NULL 
);

ALTER TABLE contrareferencias ADD FOREIGN KEY (paciente_id, tipo_id_paciente) REFERENCES pacientes (paciente_id, tipo_id_paciente);
ALTER TABLE contrareferencias ADD FOREIGN KEY (estableci_id) REFERENCES centros_remision (centro_remision);
ALTER TABLE contrareferencias ADD FOREIGN KEY (servicio_id) REFERENCES servicios (servicio);
ALTER TABLE contrareferencias ADD FOREIGN KEY (cod_profes) REFERENCES system_usuarios (usuario_id);

ALTER TABLE contrareferencias ADD PRIMARY KEY (contrareferencia_id);

COMMENT ON COLUMN contrareferencias.contrareferencia_id IS 'Codigo de la Contrareferencia';
COMMENT ON COLUMN contrareferencias.paciente_id IS 'Identificacion del Paciente';
COMMENT ON COLUMN contrareferencias.tipo_id_paciente IS 'Tipo de Identificacion del Paciente';
COMMENT ON COLUMN contrareferencias.fecha IS 'Fecha de la Contrareferencia';
COMMENT ON COLUMN contrareferencias.empr_trab IS 'Empresa donde Trabaja el Paciente';
COMMENT ON COLUMN contrareferencias.estableci_id IS 'Codigo del Establecimiento';
COMMENT ON COLUMN contrareferencias.servicio_id IS 'Codigo del Servicio';

COMMENT ON COLUMN contrareferencias.resum_cuadr_clin IS 'Resumen del Cuadro Clinico de la Contrareferencia';
COMMENT ON COLUMN contrareferencias.hallaz_relevan_exam IS 'Hallazgos Relevantes de Examenes y Procedimientos Diagnosticos de la Contrareferencia';
COMMENT ON COLUMN contrareferencias.trat_proc_tera_reali IS 'Tratamiento y Procedimientos Realizados de la Contrareferencia';
COMMENT ON COLUMN contrareferencias.plan_trata_recom IS 'Plan de Tratamiento Recomendado de la Contrareferencia';
COMMENT ON COLUMN contrareferencias.sala IS 'Sala de la Contrareferencia';
COMMENT ON COLUMN contrareferencias.cama IS 'Cama de la Contrareferencia';
COMMENT ON COLUMN contrareferencias.cod_profes IS 'Codigo del Profesional que tramita la Contrareferencia';


--Crear campo contrareferencia_id en la tabla hc_diagnosticos_ingreso
ALTER TABLE hc_diagnosticos_ingreso ADD COLUMN contrareferencia_id integer;
COMMENT ON COLUMN hc_diagnosticos_ingreso.tipo_diagnostico IS 'Switche del tipo - ID, CN, CR / Clase de Diagnostico que puede ser Presuntivo o Definitivo';



--Tabla con las referencias y sus diagnosticos
CREATE TABLE contrareferencia_evolu_diganost(
    contrareferencia_id integer NOT NULL, 
    evolucion_id integer NOT NULL,
    diagnostico_id character varying(6) NOT NULL,
    clase_diagnost_id character(1) NOT NULL
);

ALTER TABLE contrareferencia_evolu_diganost ADD FOREIGN KEY (contrareferencia_id) REFERENCES contrareferencias(contrareferencia_id);
ALTER TABLE contrareferencia_evolu_diganost ADD FOREIGN KEY (evolucion_id) REFERENCES hc_evoluciones(evolucion_id);
ALTER TABLE contrareferencia_evolu_diganost ADD FOREIGN KEY (diagnostico_id) REFERENCES diagnosticos(diagnostico_id);
ALTER TABLE contrareferencia_evolu_diganost ADD FOREIGN KEY (clase_diagnost_id) REFERENCES clase_diagnosticos(clase_diagnost_id);


ALTER TABLE contrareferencia_evolu_diganost ADD PRIMARY KEY (contrareferencia_id, evolucion_id, diagnostico_id);

COMMENT ON COLUMN contrareferencia_evolu_diganost.contrareferencia_id IS 'Codigo de la Contrareferencia';
COMMENT ON COLUMN contrareferencia_evolu_diganost.evolucion_id IS 'Codigo de la Evolucion';
COMMENT ON COLUMN contrareferencia_evolu_diganost.diagnostico_id IS 'Codigo del Diagnostico';
COMMENT ON COLUMN contrareferencia_evolu_diganost.clase_diagnost_id IS 'Codigo de la Clase de Diagnostico';

