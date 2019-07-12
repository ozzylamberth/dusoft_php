

CREATE TABLE UV_Trabajos_Anteriores
(
    trabajo_id integer  NOT NULL,
    tipo_id_paciente character varying(3) NOT NULL,
    paciente_id character varying(32) NOT NULL,
    empleador character varying(60) NOT NULL,
    tipo_pais_id character varying(4) NOT NULL,
    tipo_dpto_id character varying(4) NOT NULL,
    tipo_mpio_id character varying(4) NOT NULL,
    cargo text NOT NULL,
    fecha_ini date NOT NULL,
    fecha_fin date,
    dias_por_semana integer NOT NULL,
    horas_dia integer NOT NULL,
    intensidad char(1) NOT NULL,
    empresa_elemetos_protectores char(1) NOT NULL,
    uso_elemetos_protectores char(1) NOT NULL,
    usuario_registro integer NOT NULL,
    fecha_registro date NOT NULL    

);
CREATE SEQUENCE UV_Trabajos_Anteriores_trabajo_id_seq
INCREMENT BY 1
NO MAXVALUE
NO MINVALUE
CACHE 1;

ALTER TABLE UV_Trabajos_Anteriores ALTER COLUMN trabajo_id SET DEFAULT nextval('UV_Trabajos_Anteriores_trabajo_id_seq'::regclass);
ALTER TABLE ONLY UV_Trabajos_Anteriores ADD CONSTRAINT UV_Trabajos_Anteriores_pkey PRIMARY KEY (trabajo_id,tipo_id_paciente,paciente_id,empleador,cargo);
ALTER TABLE ONLY UV_Trabajos_Anteriores ADD CONSTRAINT tipo_mpio_id_fkey FOREIGN KEY (tipo_pais_id, tipo_dpto_id, tipo_mpio_id) REFERENCES tipo_mpios(tipo_pais_id, tipo_dpto_id, tipo_mpio_id) ON UPDATE CASCADE ON DELETE RESTRICT;



CREATE TABLE UV_paciente_trabajos_anteriores_riesgos
(
    tipo_id_paciente character varying(3) NOT NULL,
    paciente_id character varying(32) NOT NULL,
    agente_riesgo_id integer NOT NULL,
    tipo_riesgo_id integer NOT NULL,
    trabajo_id integer NOT NULL,
    usuario_registro integer NOT NULL,
    fecha_registro date NOT NULL    

);

ALTER TABLE ONLY UV_paciente_trabajos_anteriores_riesgos ADD CONSTRAINT UV_paciente_trabajos_anteriores_riesgos_pkey PRIMARY KEY (tipo_id_paciente,paciente_id,agente_riesgo_id,trabajo_id);
ALTER TABLE ONLY UV_paciente_trabajos_anteriores_riesgos ADD CONSTRAINT agentes_riego_fkey FOREIGN KEY (agente_riesgo_id,tipo_riesgo_id) REFERENCES UV_agentes_de_riesgos(agente_riesgo_id,tipo_riesgo_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY UV_paciente_trabajos_anteriores_riesgos ADD CONSTRAINT trabajo_id_fkey FOREIGN KEY (trabajo_id,tipo_id_paciente,paciente_id) REFERENCES UV_Trabajos_Anteriores(trabajo_id,tipo_id_paciente,paciente_id) ON UPDATE CASCADE ON DELETE RESTRICT;


CREATE TABLE UV_paciente_enfermedades_Y_accidentes_profesionales
(
    registro_id integer  NOT NULL,
    tipo_id_paciente character varying(3) NOT NULL,
    paciente_id character varying(32) NOT NULL,
    enfermedad_profesional char DEFAULT 'N' NOT NULL,
    descripcion_enfermedad text,
    accidente_laboral char DEFAULT 'N' NOT NULL,
    descripcion_accidente text,
    usuario_registro integer NOT NULL,
    fecha_registro date NOT NULL    
);

CREATE SEQUENCE UV_paciente_enfermedades_Y_accidentes_profesionales_registro_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

ALTER TABLE UV_paciente_enfermedades_Y_accidentes_profesionales ALTER COLUMN registro_id SET DEFAULT nextval('UV_paciente_enfermedades_Y_accidentes_profesionales_registro_id_seq'::regclass);
ALTER TABLE ONLY UV_paciente_enfermedades_Y_accidentes_profesionales ADD CONSTRAINT UV_paciente_enfermedades_Y_accidentes_profesionales_pkey PRIMARY KEY (tipo_id_paciente,paciente_id,registro_id);





CREATE TABLE UV_paciente_EPS_Anterior
(
    registro_eps_id integer  NOT NULL,
    tipo_id_paciente character varying(3) NOT NULL,
    paciente_id character varying(32) NOT NULL,
    nombre_arp_anterior character varying(32) NOT NULL,
    nombre_eps_anterior character varying(32) NOT NULL,
    nombre_pensiones_anterior character varying(32),
    fecha_ingreso date NOT NULL,
    fecha_retiro date NOT NULL,
    usuario_registro integer NOT NULL,
    fecha_registro date NOT NULL    
);

CREATE SEQUENCE UV_paciente_EPS_Anterior_registro_eps_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

ALTER TABLE UV_paciente_EPS_Anterior ALTER COLUMN registro_eps_id SET DEFAULT nextval('UV_paciente_EPS_Anterior_registro_eps_id_seq'::regclass);
ALTER TABLE ONLY UV_paciente_EPS_Anterior ADD CONSTRAINT UV_paciente_EPS_Anterior_pkey PRIMARY KEY (tipo_id_paciente,paciente_id,registro_eps_id);



