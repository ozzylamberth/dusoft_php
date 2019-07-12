

CREATE TABLE UV_info_paciente_espacio
(
    tipo_id_paciente character varying(3) NOT NULL,
    paciente_id character varying(32) NOT NULL,
    tipo_espacio_id integer NOT NULL,
    usuario_registro integer NOT NULL,
    fecha_registro date NOT NULL    

);

ALTER TABLE ONLY UV_info_paciente_espacio ADD CONSTRAINT UV_info_paciente_espacio_pkey PRIMARY KEY (tipo_id_paciente,paciente_id,tipo_espacio_id);
ALTER TABLE ONLY UV_info_paciente_espacio ADD CONSTRAINT tipo_espacio_id_fkey FOREIGN KEY (tipo_espacio_id) REFERENCES UV_tipos_de_espacios(tipo_espacio_id) ON UPDATE CASCADE ON DELETE RESTRICT;



CREATE TABLE UV_info_paciente_ocupacion
(
    tipo_id_paciente character varying(3) NOT NULL,
    paciente_id character varying(32) NOT NULL,
    ocupacion_id integer NOT NULL,
    usuario_registro integer NOT NULL,
    fecha_registro date NOT NULL    

);

ALTER TABLE ONLY UV_info_paciente_ocupacion ADD CONSTRAINT UV_info_paciente_ocupacion_pkey PRIMARY KEY (tipo_id_paciente,paciente_id,ocupacion_id);
ALTER TABLE ONLY UV_info_paciente_ocupacion ADD CONSTRAINT ocupacion_id_fkey FOREIGN KEY (ocupacion_id) REFERENCES UV_ocupaciones_SD(ocupacion_id) ON UPDATE CASCADE ON DELETE RESTRICT;



SELECT 
    b.tipo_espacio_id,
    b.descripcion as nombre_espacio,
    d.agente_riesgo_id,
    d.descripcion as nombre_agente,
    e.tipo_riesgo_id,
    e.descripcion as nombre_tipo_riesgo,
    e.color    

FROM
    UV_info_paciente_espacio as a,
    UV_tipos_de_espacios as b,
    uv_agentes_de_riesgo_por_tipos_de_espacios as c,
    uv_agentes_de_riesgos as d,
    uv_tipos_de_riesgos as e
WHERE

    a.tipo_id_paciente = 'CC'
    AND a.paciente_id = '31265700'
    AND a.tipo_espacio_id=b.tipo_espacio_id
    AND b.tipo_espacio_id=c.tipo_espacio_id
    AND c.agente_riesgo_id=d.agente_riesgo_id
    AND d.agente_riesgo_id=e.agente_riesgo_id










