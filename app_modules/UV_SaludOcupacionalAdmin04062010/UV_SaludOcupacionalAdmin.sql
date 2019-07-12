CREATE TABLE UV_tipos_de_riesgos
(
    tipo_riesgo_id integer NOT NULL,
    descripcion character varying(40) UNIQUE NOT NULL,
    color character varying(10) UNIQUE NOT NULL,
    sw_estado char NOT NULL,
    fecha_registro date NOT NULL,
    usuario_registro integer NOT NULL
);


CREATE SEQUENCE UV_tipos_de_riesgos_tipo_riesgo_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

ALTER TABLE UV_tipos_de_riesgos ALTER COLUMN tipo_riesgo_id SET DEFAULT nextval('UV_tipos_de_riesgos_tipo_riesgo_id_seq'::regclass);
ALTER TABLE ONLY UV_tipos_de_riesgos ADD CONSTRAINT UV_tipos_de_riesgos_pkey PRIMARY KEY (tipo_riesgo_id);



CREATE TABLE UV_agentes_de_riesgos
(
    agente_riesgo_id integer NOT NULL,
    tipo_riesgo_id integer NOT NULL,
    descripcion character varying(40) NOT NULL,
    usuario_registro integer NOT NULL,
    fecha_registro date NOT NULL,
    sw_estado char NOT NULL
);

CREATE SEQUENCE UV_agentes_de_riesgos_agente_riesgo_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

ALTER TABLE UV_agentes_de_riesgos ALTER COLUMN agente_riesgo_id SET DEFAULT nextval('UV_agentes_de_riesgos_agente_riesgo_id_seq'::regclass);
ALTER TABLE ONLY UV_agentes_de_riesgos ADD CONSTRAINT UV_agentes_de_riesgos_pkey PRIMARY KEY (agente_riesgo_id,tipo_riesgo_id);
ALTER TABLE ONLY UV_agentes_de_riesgos ADD CONSTRAINT UV_agentes_de_riesgos_fkey FOREIGN KEY (tipo_riesgo_id) REFERENCES UV_tipos_de_riesgos(tipo_riesgo_id) ON UPDATE CASCADE ON DELETE RESTRICT;



CREATE TABLE UV_tipos_de_espacios
(
    tipo_espacio_id integer NOT NULL,
    descripcion character varying(40) UNIQUE NOT NULL,
    usuario_registro integer NOT NULL,
    fecha_registro date NOT NULL,
    sw_estado char DEFAULT '1' NOT NULL
);

CREATE SEQUENCE UV_tipos_de_espacios_tipo_espacio_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

ALTER TABLE UV_tipos_de_espacios ALTER COLUMN tipo_espacio_id SET DEFAULT nextval('UV_tipos_de_espacios_tipo_espacio_id_seq'::regclass);
ALTER TABLE ONLY UV_tipos_de_espacios ADD CONSTRAINT UV_tipos_de_espacios_pkey PRIMARY KEY (tipo_espacio_id);



CREATE TABLE UV_agentes_de_riesgo_por_tipos_de_espacios
(
    tipo_espacio_id integer NOT NULL,
    tipo_riesgo_id integer NOT NULL,
    agente_riesgo_id integer NOT NULL,
    usuario_registro integer NOT NULL,
    fecha_registro date NOT NULL
);

ALTER TABLE ONLY UV_agentes_de_riesgo_por_tipos_de_espacios ADD CONSTRAINT UV_agentes_de_riesgo_por_tipos_de_espacios_pkey PRIMARY KEY (tipo_espacio_id,agente_riesgo_id);
ALTER TABLE ONLY UV_agentes_de_riesgo_por_tipos_de_espacios ADD CONSTRAINT UV_agentes_de_riesgo_fkey FOREIGN KEY (agente_riesgo_id,tipo_riesgo_id) REFERENCES UV_agentes_de_riesgos(agente_riesgo_id,tipo_riesgo_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY UV_agentes_de_riesgo_por_tipos_de_espacios ADD CONSTRAINT UV_tipos_de_espacios_fkey FOREIGN KEY (tipo_espacio_id) REFERENCES UV_tipos_de_espacios(tipo_espacio_id) ON UPDATE CASCADE ON DELETE RESTRICT;


CREATE TABLE UV_ocupaciones_SD
(
    ocupacion_id integer NOT NULL,
    descripcion character varying(40) UNIQUE NOT NULL,
    usuario_registro integer NOT NULL,
    fecha_registro date NOT NULL,
    sw_estado char DEFAULT '1' NOT NULL
);

CREATE SEQUENCE UV_ocupaciones_SD_ocupacion_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

ALTER TABLE UV_ocupaciones_SD ALTER COLUMN ocupacion_id SET DEFAULT nextval('UV_ocupaciones_SD_ocupacion_id_seq'::regclass);
ALTER TABLE ONLY UV_ocupaciones_SD ADD CONSTRAINT UV_ocupaciones_SD_pkey PRIMARY KEY (ocupacion_id);


CREATE TABLE UV_cargos_por_ocupaciones
(
    ocupacion_id integer NOT NULL,
    cargo_ocupacion_id integer NOT NULL,
    descripcion character varying(40) UNIQUE NOT NULL,
    usuario_registro integer NOT NULL,
    fecha_registro date NOT NULL,
    sw_estado char DEFAULT '1' NOT NULL
);

CREATE SEQUENCE UV_cargos_por_ocupaciones_cargo_ocupacion_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

ALTER TABLE UV_cargos_por_ocupaciones ALTER COLUMN cargo_ocupacion_id SET DEFAULT nextval('UV_cargos_por_ocupaciones_cargo_ocupacion_id_seq'::regclass);
ALTER TABLE ONLY UV_cargos_por_ocupaciones ADD CONSTRAINT UV_cargos_por_ocupaciones_pkey PRIMARY KEY (ocupacion_id,cargo_ocupacion_id);
ALTER TABLE ONLY UV_cargos_por_ocupaciones ADD CONSTRAINT UV_ocupaciones_SD_fkey FOREIGN KEY (ocupacion_id) REFERENCES UV_ocupaciones_SD(ocupacion_id) ON UPDATE CASCADE ON DELETE RESTRICT;



CREATE TABLE UV_agentes_de_riesgo_por_ocupacion
(
    ocupacion_id integer NOT NULL,
    tipo_riesgo_id integer NOT NULL,
    agente_riesgo_id integer NOT NULL,
    usuario_registro integer NOT NULL,
    fecha_registro date NOT NULL
    
);

ALTER TABLE ONLY UV_agentes_de_riesgo_por_ocupacion ADD CONSTRAINT UV_agentes_de_riesgo_por_ocupacion_pkey PRIMARY KEY (ocupacion_id,agente_riesgo_id);
ALTER TABLE ONLY UV_agentes_de_riesgo_por_ocupacion ADD CONSTRAINT UV_agentes_de_riesgos_fkey FOREIGN KEY (agente_riesgo_id,tipo_riesgo_id) REFERENCES UV_agentes_de_riesgos(agente_riesgo_id,tipo_riesgo_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY UV_agentes_de_riesgo_por_ocupacion ADD CONSTRAINT UV_ocupaciones_SD_fkey FOREIGN KEY (ocupacion_id) REFERENCES UV_ocupaciones_SD(ocupacion_id) ON UPDATE CASCADE ON DELETE RESTRICT;


