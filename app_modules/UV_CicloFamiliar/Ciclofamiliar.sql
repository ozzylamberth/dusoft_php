

CREATE TABLE ciclo_vital_individual (
    ciclo_vital_individual_id integer NOT NULL,
    descripcion character varying(30) NOT NULL,
    sw_mostrar character(1) NOT NULL,
    edad_min integer NOT NULL,
    edad_max integer NOT NULL
    
);

ALTER TABLE ONLY ciclo_vital_individual ADD CONSTRAINT ciclo_vital_individual_pkey PRIMARY KEY (ciclo_vital_individual_id);


CREATE TABLE ciclo_vital_factores_riesgo (
    factor_riesgo_id integer NOT NULL,
    descripcion character varying(30) NOT NULL,
    sw_mostrar character(1) NOT NULL,
    edad_min integer NOT NULL,
    edad_max integer NOT NULL
    
);

ALTER TABLE ONLY ciclo_vital_factores_riesgo ADD CONSTRAINT ciclo_vital_factores_riesgo_pkey PRIMARY KEY (factor_riesgo_id);



CREATE TABLE ciclo_vital_factores_riesgo_paciente (
    Ingreso integer NOT NULL,
    tipo_id_paciente character varying(3) NOT NULL,
    paciente_id character varying(32) NOT NULL,
    factor_riesgo_id integer NOT NULL,
     
);
ALTER TABLE ONLY ciclo_vital_factores_riesgo_paciente ADD CONSTRAINT ciclo_vital_factores_riesgo_paciente_pkey PRIMARY KEY (Ingreso,tipo_id_paciente,paciente_id);
ALTER TABLE ONLY ciclo_vital_factores_riesgo_paciente ADD CONSTRAINT ciclo_vital_factores_riesgo_paciente_factor_riesgo_id_fkey FOREIGN KEY (factor_riesgo_id) REFERENCES ciclo_vital_factores_riesgo(factor_riesgo_id) ON UPDATE CASCADE ON DELETE RESTRICT;









CREATE TABLE ciclo_vital_familiar (
    ciclo_vital_familiar_id integer NOT NULL,
    descripcion character varying(30) NOT NULL,
    sw_mostrar character(1) NOT NULL
);

ALTER TABLE ONLY ciclo_vital_familiar ADD CONSTRAINT ciclo_vital_familiar_pkey PRIMARY KEY (ciclo_vital_familiar_id);




CREATE TABLE ciclo_vital_individual_detalle (
    Ingreso integer NOT NULL,
    tipo_id_paciente character varying(3) NOT NULL,
    paciente_id character varying(32) NOT NULL,
    ciclo_vital_individual_id integer NOT NULL,
    Factores_de_riesgo text NULL
    
);

ALTER TABLE ONLY ciclo_vital_individual_detalle ADD CONSTRAINT ciclo_vital_individual_detalle_pkey PRIMARY KEY (Ingreso,tipo_id_paciente,paciente_id);
ALTER TABLE ONLY ciclo_vital_individual_detalle ADD CONSTRAINT ciclo_vital_individual_id_fkey FOREIGN KEY (ciclo_vital_individual_id) REFERENCES ciclo_vital_individual(ciclo_vital_individual_id) ON UPDATE CASCADE ON DELETE RESTRICT;



CREATE TABLE ciclo_vital_factores_riesgo_paciente (
    Ingreso integer NOT NULL,
    tipo_id_paciente character varying(3) NOT NULL,
    paciente_id character varying(32) NOT NULL,
    ciclo_vital_individual_id integer NOT NULL,
    Factores_de_riesgo text NULL
    
);

ALTER TABLE ONLY ciclo_vital_individual_detalle ADD CONSTRAINT ciclo_vital_individual_detalle_pkey PRIMARY KEY (Ingreso,tipo_id_paciente,paciente_id);
ALTER TABLE ONLY ciclo_vital_individual_detalle ADD CONSTRAINT ciclo_vital_individual_id_fkey FOREIGN KEY (ciclo_vital_individual_id) REFERENCES ciclo_vital_individual(ciclo_vital_individual_id) ON UPDATE CASCADE ON DELETE RESTRICT;









CREATE TABLE ciclo_vital_familiar_detalle
(
    Ingreso integer NOT NULL,
    tipo_id_paciente character varying(3) NOT NULL,
    paciente_id character varying(32) NOT NULL,
    ciclo_vital_familiar_id integer NOT NULL,
    Observaciones text NULL
);


ALTER TABLE ONLY ciclo_vital_familiar_detalle ADD CONSTRAINT ciclo_vital_familiar_detalle_pkey PRIMARY KEY (Ingreso,tipo_id_paciente,paciente_id,ciclo_vital_familiar_id);
ALTER TABLE ONLY ciclo_vital_familiar_detalle ADD CONSTRAINT ciclo_vital_familiar_id_fkey FOREIGN KEY (ciclo_vital_familiar_id) REFERENCES ciclo_vital_familiar(ciclo_vital_familiar_id) ON UPDATE CASCADE ON DELETE RESTRICT;



CREATE TABLE ciclo_familiar_observaciones
(
    Ingreso integer NOT NULL,
    tipo_id_paciente character varying(3) NOT NULL,
    paciente_id character varying(32) NOT NULL,
    Observaciones text NULL
);


ALTER TABLE ONLY ciclo_familiar_observaciones ADD CONSTRAINT ciclo_familiar_observaciones_pkey PRIMARY KEY (Ingreso,tipo_id_paciente,paciente_id,Observaciones);






INSERT INTO system_hc_submodulos VALUES ('UV_CicloFamiliar', 'Ciclo Vital Individual y Familiar', 1.00, '', '1', NULL, NULL, NULL, NULL, '0', '1', '0');

