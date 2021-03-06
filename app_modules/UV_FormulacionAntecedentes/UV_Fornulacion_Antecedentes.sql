

CREATE TABLE hc_formulacion_antecedentes (
    tipo_id_paciente character varying(3) NOT NULL,
    paciente_id character varying(32) NOT NULL,
    codigo_medicamento character varying(60) NOT NULL,
    fecha_registro date,
    fecha_finalizacion date,
    medico_id integer,
    dosis numeric(7,2),
    unidad_dosificacion character varying(50) NOT NULL,
    frecuencia text,
    sw_permanente character(1) DEFAULT '0'::bpchar,
    sw_formulado character(1) DEFAULT '0'::bpchar,
    tiempo_total character varying(50),
    perioricidad_entrega character varying(50),
    descripcion text,
    evolucion_id integer NOT NULL
);


ALTER TABLE ONLY hc_formulacion_antecedentes ADD CONSTRAINT hc_formulacion_antecedentes_pkey PRIMARY KEY (tipo_id_paciente,paciente_id,codigo_medicamento,evolucion_id);

INSERT INTO system_hc_submodulos VALUES ('UV_Formulacion_Antecedentes', 'Antecedentes Farmacologicos', 1.00, '', '1', NULL, NULL, NULL, NULL, '0', '1', '0');

aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
vitamina dipironoac mk 25 ml de laboratrios de oocidente 