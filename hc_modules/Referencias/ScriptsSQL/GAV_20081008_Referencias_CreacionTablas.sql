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
	'Referencias', 
	'Efectuar Referencias', 
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
	'Referencias', 
	33, 
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


CREATE TABLE clase_diagnosticos(
	clase_diagnost_id character(1) NOT NULL,
	descripcion character varying(20) NOT NULL
);

ALTER TABLE clase_diagnosticos ADD PRIMARY KEY (clase_diagnost_id);

COMMENT ON COLUMN clase_diagnosticos.clase_diagnost_id IS 'Codigo de la Clase de Diagnostico';
COMMENT ON COLUMN clase_diagnosticos.descripcion IS 'Descripcion de la Clase de Diagnostico';


ALTER TABLE diagnosticos ADD COLUMN clase_diagnost_id character(1);
ALTER TABLE diagnosticos ADD FOREIGN KEY (clase_diagnost_id) REFERENCES clase_diagnosticos(clase_diagnost_id);
COMMENT ON COLUMN diagnosticos.clase_diagnost_id IS 'Codigo de la Clase de Diagnostico';


--Inserccion de las clases de diagnosticos
INSERT INTO clase_diagnosticos(
    clase_diagnost_id,
    descripcion
)VALUES(
    '0',
    'PRESUNTIVO'
);

INSERT INTO clase_diagnosticos(
    clase_diagnost_id,
    descripcion
)VALUES(
    '1',
    'DEFINITIVO'
);


--Tabla de las referencias que tiene cada paciente
CREATE TABLE referencias(
    referencia_id serial NOT NULL,
    paciente_id character varying(32) NOT NULL,
    tipo_id_paciente character varying(3) NOT NULL,
    fecha date NOT NULL, 
    empr_trab text,
    estableci_id character varying(10) NOT NULL, 
    servicio_id character varying(2) NOT NULL, 
    moti_refer text, 
    resum_cuadr_clin text, 
    hallaz_relevan_exam text, 
    plan_trata_reali text,
    sala character varying(4), 
    cama character varying(4),
    cod_profes integer NOT NULL
    
);

ALTER TABLE referencias ADD FOREIGN KEY (paciente_id, tipo_id_paciente) REFERENCES pacientes (paciente_id, tipo_id_paciente);
ALTER TABLE referencias ADD FOREIGN KEY (estableci_id) REFERENCES centros_remision (centro_remision);
ALTER TABLE referencias ADD FOREIGN KEY (servicio_id) REFERENCES servicios (servicio);
ALTER TABLE referencias ADD FOREIGN KEY (cod_profes) REFERENCES system_usuarios (usuario_id);

ALTER TABLE referencias ADD PRIMARY KEY (referencia_id);

COMMENT ON COLUMN referencias.referencia_id IS 'Codigo de la Referencia';
COMMENT ON COLUMN referencias.paciente_id IS 'Identificacion del Paciente';
COMMENT ON COLUMN referencias.tipo_id_paciente IS 'Tipo de Identificacion del Paciente';
COMMENT ON COLUMN referencias.fecha IS 'Fecha de la referencia';
COMMENT ON COLUMN referencias.empr_trab IS 'Empresa donde Trabaja el Paciente';
COMMENT ON COLUMN referencias.estableci_id IS 'Codigo del Establecimiento';
COMMENT ON COLUMN referencias.servicio_id IS 'Codigo del Servicio';
COMMENT ON COLUMN referencias.moti_refer IS 'Motivo de la Referencia';
COMMENT ON COLUMN referencias.resum_cuadr_clin IS 'Resumen del Cuadro Clinico de la Referencia';
COMMENT ON COLUMN referencias.hallaz_relevan_exam IS 'Hallazgos Relevantes de Examenes y Procedimientos Diagnosticos de la Referencia';
COMMENT ON COLUMN referencias.plan_trata_reali IS 'Plan de Tratamiento Realizado de la Referencia';
COMMENT ON COLUMN referencias.sala IS 'Sala de la Referencia';
COMMENT ON COLUMN referencias.cama IS 'Cama de la Referencia';
COMMENT ON COLUMN referencias.cod_profes IS 'Codigo del Profesional que tramita la Referencia';


--Crear campo referencia_id en la tabla hc_diagnosticos_egreso
ALTER TABLE hc_diagnosticos_egreso ADD COLUMN referencia_id integer;
COMMENT ON COLUMN hc_diagnosticos_egreso.tipo_diagnostico IS 'Switche del tipo - ID, CN, CR / Clase de Diagnostico que puede ser Presuntivo o Definitivo';


--Tabla con las referencias y sus diagnosticos
CREATE TABLE referencia_evolu_diganost(
    referencia_id integer NOT NULL, 
    evolucion_id integer NOT NULL,
    diagnostico_id character varying(6) NOT NULL,
    clase_diagnost_id character(1) NOT NULL
);

ALTER TABLE referencia_evolu_diganost ADD FOREIGN KEY (referencia_id) REFERENCES referencias(referencia_id);
ALTER TABLE referencia_evolu_diganost ADD FOREIGN KEY (evolucion_id) REFERENCES hc_evoluciones(evolucion_id);
ALTER TABLE referencia_evolu_diganost ADD FOREIGN KEY (diagnostico_id) REFERENCES diagnosticos(diagnostico_id);
ALTER TABLE referencia_evolu_diganost ADD FOREIGN KEY (clase_diagnost_id) REFERENCES clase_diagnosticos(clase_diagnost_id);


ALTER TABLE referencia_evolu_diganost ADD PRIMARY KEY (referencia_id, evolucion_id, diagnostico_id);

COMMENT ON COLUMN referencia_evolu_diganost.referencia_id IS 'Codigo de la Referencia';
COMMENT ON COLUMN referencia_evolu_diganost.evolucion_id IS 'Codigo de la Evolucion';
COMMENT ON COLUMN referencia_evolu_diganost.diagnostico_id IS 'Codigo del Diagnostico';
COMMENT ON COLUMN referencia_evolu_diganost.clase_diagnost_id IS 'Codigo de la Clase de Diagnostico';


