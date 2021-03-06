CREATE TABLE UV_ParteCuerpoAfectado
(
    parte_cuerpo_id character varying(6) NOT NULL,
    descripcion character varying(120) UNIQUE NOT NULL,
    sw_estado char NOT NULL,
    fecha_registro date NOT NULL,
    usuario_registro integer NOT NULL
);
ALTER TABLE ONLY UV_ParteCuerpoAfectado ADD CONSTRAINT UV_ParteCuerpoAfectado_pkey PRIMARY KEY (parte_cuerpo_id);


CREATE TABLE UV_TiposLesion
(
    tipo_lesion_id character varying(6) NOT NULL,
    descripcion character varying(120) UNIQUE NOT NULL,
    sw_estado char NOT NULL,
    fecha_registro date NOT NULL,
    usuario_registro integer NOT NULL
);
ALTER TABLE ONLY UV_TiposLesion ADD CONSTRAINT UV_TiposLesion_pkey PRIMARY KEY (tipo_lesion_id);


CREATE TABLE UV_AgentesAccidente
(
    agente_accidente_id character varying(6) NOT NULL,
    descripcion character varying(120) UNIQUE NOT NULL,
    sw_estado char NOT NULL,
    fecha_registro date NOT NULL,
    usuario_registro integer NOT NULL
);
ALTER TABLE ONLY UV_AgentesAccidente ADD CONSTRAINT UV_AgentesAccidente_pkey PRIMARY KEY (agente_accidente_id);

CREATE TABLE UV_FormaAccidente
(
    forma_accidente_id character varying(6) NOT NULL,
    descripcion character varying(120) UNIQUE NOT NULL,
    sw_estado char NOT NULL,
    fecha_registro date NOT NULL,
    usuario_registro integer NOT NULL
);
ALTER TABLE ONLY UV_FormaAccidente ADD CONSTRAINT UV_FormaAccidente_pkey PRIMARY KEY (forma_accidente_id);

CREATE TABLE UV_Sitio_Accidente
(
    sitio_accidente_id character varying(6) NOT NULL,
    descripcion character varying(120) UNIQUE NOT NULL,
    sw_estado char NOT NULL,
    fecha_registro date NOT NULL,
    usuario_registro integer NOT NULL
);

ALTER TABLE ONLY UV_Sitio_Accidente ADD CONSTRAINT UV_Sitio_Accidente_pkey PRIMARY KEY (sitio_accidente_id);

CREATE TABLE UV_tipo_Accidente
(
    tipo_accidente_id character varying(6) NOT NULL,
    descripcion character varying(120) NOT NULL,
    sw_estado char NOT NULL,
    fecha_registro date NOT NULL,
    usuario_registro integer NOT NULL
);

ALTER TABLE ONLY UV_tipo_Accidente ADD CONSTRAINT UV_tipo_Accidente_pkey PRIMARY KEY (tipo_accidente_id);




--nueva--


CREATE TABLE UV_accidentes_trabajo
(
    accidente_id integer NOT NULL,
    tipo_id_trabajador character varying(3) NOT NULL,
    trabajador_id  character varying(32) NOT NULL,
    tipo_accidente_id character varying(6) NOT NULL,
    fecha_accidente date NOT NULL,
    hora_accidente character varying(9) NOT NULL,
    jornada_accidente character NOT NULL,
    realizando_trabajo_habitual character NOT NULL,
    trabajo_no_habitual character varying(90),
    tipo_pais_id character varying(4) NOT NULL,
    tipo_dpto_id character varying(4) NOT NULL,
    tipo_mpio_id character varying(4) NOT NULL,
    zona_residencial character NOT NULL,
    sw_accidente_dentro_empresa character NOT NULL,
    sitio_accidente_id character varying(6) NOT NULL,
    descripcion_accindente text NOT NULL,
    sw_personas_presenciaron_accidente character NOT NULL,
    fecha_registro date NOT NULL,
    usuario_registro integer NOT NULL
);

CREATE SEQUENCE UV_accidentes_trabajo_accidente_id_seq
INCREMENT BY 1
NO MAXVALUE
NO MINVALUE
CACHE 1;

ALTER TABLE UV_accidentes_trabajo ALTER COLUMN accidente_id SET DEFAULT nextval('UV_accidentes_trabajo_accidente_id_seq'::regclass);

ALTER TABLE ONLY UV_accidentes_trabajo ADD CONSTRAINT UV_accidentes_trabajo_pkey PRIMARY KEY (accidente_id,tipo_id_trabajador,trabajador_id);
ALTER TABLE ONLY UV_accidentes_trabajo ADD CONSTRAINT UV_accidentes_trabajo_eps_afiliados_datos_fkey FOREIGN KEY (tipo_id_trabajador,trabajador_id) REFERENCES eps_afiliados_datos(afiliado_tipo_id, afiliado_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY UV_accidentes_trabajo ADD CONSTRAINT UV_accidentes_trabajo_tipo_mpios_fkey FOREIGN KEY (tipo_pais_id,tipo_dpto_id,tipo_mpio_id) REFERENCES tipo_mpios(tipo_pais_id,tipo_dpto_id,tipo_mpio_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY UV_accidentes_trabajo ADD CONSTRAINT UV_accidentes_trabajo_UV_tipo_Accidente_fkey FOREIGN KEY (tipo_accidente_id) REFERENCES UV_tipo_Accidente(tipo_accidente_id) ON UPDATE CASCADE ON DELETE RESTRICT;


CREATE TABLE UV_partes_afectadas_cuerpo_trabajador
(
    parte_cuerpo_id character varying(6) NOT NULL,
    accidente_id integer NOT NULL,
    tipo_id_trabajador character varying(3) NOT NULL,
    trabajador_id  character varying(32) NOT NULL,
    fecha_registro date NOT NULL,
    usuario_registro integer NOT NULL
);

ALTER TABLE ONLY UV_partes_afectadas_cuerpo_trabajador ADD CONSTRAINT UV_partes_afectadas_cuerpo_trabajador_pkey PRIMARY KEY (parte_cuerpo_id,accidente_id,tipo_id_trabajador,trabajador_id);
ALTER TABLE ONLY UV_partes_afectadas_cuerpo_trabajador ADD CONSTRAINT UV_partes_afectadas_cuerpo_trabajador_UV_accidentes_trabajo_fkey FOREIGN KEY (accidente_id,tipo_id_trabajador,trabajador_id) REFERENCES UV_accidentes_trabajo(accidente_id,tipo_id_trabajador,trabajador_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY UV_partes_afectadas_cuerpo_trabajador ADD CONSTRAINT UV_partes_afectadas_cuerpo_trabajador_eps_afiliados_datos_fkey FOREIGN KEY (tipo_id_trabajador,trabajador_id) REFERENCES eps_afiliados_datos(afiliado_tipo_id, afiliado_id) ON UPDATE CASCADE ON DELETE RESTRICT;



CREATE TABLE UV_TiposLesion_trabajador
(
    tipo_lesion_id character varying(6) NOT NULL,
    accidente_id integer NOT NULL,
    tipo_id_trabajador character varying(3) NOT NULL,
    trabajador_id  character varying(32) NOT NULL,
    fecha_registro date NOT NULL,
    usuario_registro integer NOT NULL
);

ALTER TABLE ONLY UV_TiposLesion_trabajador ADD CONSTRAINT UV_TiposLesion_trabajador_pkey PRIMARY KEY (tipo_lesion_id,accidente_id,tipo_id_trabajador,trabajador_id);
ALTER TABLE ONLY UV_TiposLesion_trabajador ADD CONSTRAINT UV_TiposLesion_trabajador_UV_accidentes_trabajo_fkey FOREIGN KEY (accidente_id,tipo_id_trabajador,trabajador_id) REFERENCES UV_accidentes_trabajo(accidente_id,tipo_id_trabajador,trabajador_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY UV_TiposLesion_trabajador ADD CONSTRAINT UV_TiposLesion_trabajador_eps_afiliados_datos_fkey FOREIGN KEY (tipo_id_trabajador,trabajador_id) REFERENCES eps_afiliados_datos(afiliado_tipo_id, afiliado_id) ON UPDATE CASCADE ON DELETE RESTRICT;


CREATE TABLE UV_AgentesAccidente_trabajador
(
    agente_accidente_id character varying(6) NOT NULL,
    accidente_id integer NOT NULL,
    tipo_id_trabajador character varying(3) NOT NULL,
    trabajador_id  character varying(32) NOT NULL,
    fecha_registro date NOT NULL,
    usuario_registro integer NOT NULL
);

ALTER TABLE ONLY UV_AgentesAccidente_trabajador ADD CONSTRAINT UV_AgentesAccidente_trabajador_pkey PRIMARY KEY (agente_accidente_id,accidente_id,tipo_id_trabajador,trabajador_id);
ALTER TABLE ONLY UV_AgentesAccidente_trabajador ADD CONSTRAINT UV_AgentesAccidente_trabajador_UV_accidentes_trabajo_fkey FOREIGN KEY (accidente_id,tipo_id_trabajador,trabajador_id) REFERENCES UV_accidentes_trabajo(accidente_id,tipo_id_trabajador,trabajador_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY UV_AgentesAccidente_trabajador ADD CONSTRAINT UV_AgentesAccidente_trabajador_eps_afiliados_datos_fkey FOREIGN KEY (tipo_id_trabajador,trabajador_id) REFERENCES eps_afiliados_datos(afiliado_tipo_id, afiliado_id) ON UPDATE CASCADE ON DELETE RESTRICT;




CREATE TABLE UV_FormaAccidente_trabajador
(
    forma_accidente_id character varying(6) NOT NULL,
    accidente_id integer NOT NULL,
    tipo_id_trabajador character varying(3) NOT NULL,
    trabajador_id character varying(32) NOT NULL,
    fecha_registro date NOT NULL,
    usuario_registro integer NOT NULL
);


ALTER TABLE ONLY UV_FormaAccidente_trabajador ADD CONSTRAINT UV_FormaAccidente_trabajador_pkey PRIMARY KEY (forma_accidente_id,accidente_id,tipo_id_trabajador,trabajador_id);
ALTER TABLE ONLY UV_FormaAccidente_trabajador ADD CONSTRAINT UV_FormaAccidente_trabajador_UV_accidentes_trabajo_fkey FOREIGN KEY (accidente_id,tipo_id_trabajador,trabajador_id) REFERENCES UV_accidentes_trabajo(accidente_id,tipo_id_trabajador,trabajador_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY UV_FormaAccidente_trabajador ADD CONSTRAINT UV_FormaAccidente_trabajador_eps_afiliados_datos_fkey FOREIGN KEY (tipo_id_trabajador,trabajador_id) REFERENCES eps_afiliados_datos(afiliado_tipo_id, afiliado_id) ON UPDATE CASCADE ON DELETE RESTRICT;




CREATE TABLE UV_personas_que_presensiaron_accidente
(
    tipo_id_tercero character varying(6) NOT NULL,
    tercero_id  character varying(32) NOT NULL,
    accidente_id integer NOT NULL,
    nombre character varying(80) NOT NULL,
    fecha_registro date NOT NULL,
    usuario_registro integer NOT NULL
);

ALTER TABLE ONLY UV_personas_que_presensiaron_accidente ADD CONSTRAINT UV_personas_que_presensiaron_accidente_pkey PRIMARY KEY (tipo_id_tercero,tercero_id,accidente_id);




CREATE TABLE UV_agentes_riesgo_espacios_Accidente
(
    tipo_espacio_id integer NOT NULL,
    agente_riesgo_id integer NOT NULL,
    tipo_riesgo_id integer NOT NULL,
    accidente_id integer NOT NULL,
    tipo_id_trabajador character varying(3) NOT NULL,
    trabajador_id  character varying(32) NOT NULL,
    fecha_registro date NOT NULL,
    usuario_registro integer NOT NULL
);

ALTER TABLE ONLY UV_agentes_riesgo_espacios_Accidente ADD CONSTRAINT UV_agentes_riesgo_espacios_Accidente_pkey PRIMARY KEY (tipo_espacio_id,agente_riesgo_id,tipo_riesgo_id,accidente_id,tipo_id_trabajador,trabajador_id);
ALTER TABLE ONLY UV_agentes_riesgo_espacios_Accidente ADD CONSTRAINT UV_agentes_riesgo_espacios_Accidente_UV_accidentes_trabajo_fkey FOREIGN KEY (accidente_id,tipo_id_trabajador,trabajador_id) REFERENCES UV_accidentes_trabajo(accidente_id,tipo_id_trabajador,trabajador_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY UV_agentes_riesgo_espacios_Accidente ADD CONSTRAINT UV_partes_afectadas_cuerpo_trabajador_eps_afiliados_datos_fkey FOREIGN KEY (tipo_id_trabajador,trabajador_id) REFERENCES eps_afiliados_datos(afiliado_tipo_id, afiliado_id) ON UPDATE CASCADE ON DELETE RESTRICT;




/////////partes del cuerpo///////////////

    
SELECT
a.descripcion as parte_del_cuerpo
FROM
UV_ParteCuerpoAfectado AS a,
UV_partes_afectadas_cuerpo_trabajador AS b
WHERE
a.parte_cuerpo_id=b.parte_cuerpo_id
AND accidente_id='".$accidente_id."'
AND tipo_id_trabajador='".$tipo_id_trabajador."'
AND trabajador_id='".$trabajador_id."'

/////tipos lesion/////
SELECT
a.descripcion as desc_tipo_lesion
FROM
UV_TiposLesion AS a,
UV_TiposLesion_trabajador AS b
WHERE
a.tipo_lesion_id=b.tipo_lesion_id
AND accidente_id='".$accidente_id."'
AND tipo_id_trabajador='".$tipo_id_trabajador."'
AND trabajador_id='".$trabajador_id."'

/////agente lesion/////////////////

SELECT
a.descripcion as desc_agente_accidente
FROM
UV_AgentesAccidente AS a,
UV_AgentesAccidente_trabajador AS b
WHERE
a.agente_accidente_id=b.agente_accidente_id
AND accidente_id='".$accidente_id."'
AND tipo_id_trabajador='".$tipo_id_trabajador."'
AND trabajador_id='".$trabajador_id."'
////firma accidente


SELECT
a.descripcion as desc_forma_accidente
FROM
UV_FormaAccidente AS a,
UV_FormaAccidente_trabajador AS b
WHERE
a.forma_accidente_id=b.forma_accidente_id
AND accidente_id='".$accidente_id."'
AND tipo_id_trabajador='".$tipo_id_trabajador."'
AND trabajador_id='".$trabajador_id."'

///EL PRIMERO LISTA DE ACCIDENTES/////


SELECT
    a.*,
    f.descripcion AS sitio_accidente,
    g.descripcion AS tipo_accidente,

FROM
    UV_accidentes_trabajo AS a,
    UV_Sitio_Accidente AS f,
    UV_tipo_Accidente AS g,
    tipo_pais AS h,
    tipo_dptos AS i,
    tipo_mpios AS j,
    UV_personas_que_presensiaron_accidente AS l,
    UV_agentes_riesgo_espacios_Accidente AS m
WHERE
    a.accidente_id='".$accidente_id."'
    AND a.tipo_id_trabajador='".$tipo_id_trabajador."'
    AND a.trabajador_id ='".$trabajador_id."'
    AND a.sitio_accidente_id=f.sitio_accidente_id
    AND a.tipo_accidente_id=g.tipo_accidente_id
    AND a.tipo_pais_id=h.tipo_pais_id
    AND a.tipo_pais_id=i.tipo_pais_id
    AND a.tipo_dpto_id=i.tipo_dpto_id
    AND a.tipo_pais_id=j.tipo_pais_id
    AND a.tipo_dpto_id=j.tipo_dpto_id
    AND a.tipo_mpio_id=j.tipo_mpio_id
    
    



SELECT
a.tipo_espacio_id,
a.agente_riesgo_id,
a.tipo_riesgo_id,
b.descripcion as desc_tipo_riesgo
c.descripcion as desc_agente_riesgo,
d.descripcion AS desc_tipo_espacio,
a.descripcion as desc_forma_accidente
FROM
 AS a,
uv_tipos_de_riesgos AS b,
uv_agentes_de_riesgos AS c,
uv_tipos_de_espacios AS d
WHERE
a.accidente_id='".$accidente_id."'
AND a.tipo_id_trabajador='".$tipo_id_trabajador."'
AND a.trabajador_id='".$trabajador_id."'
AND a.tipo_riesgo_id=b.tipo_riesgo_id
AND a.tipo_riesgo_id=c.tipo_riesgo_id
AND a.agente_riesgo_id=c.agente_riesgo_id
AND a.tipo_espacio_id=c.tipo_espacio_id





(
    tipo_id_tercero character varying(6) NOT NULL,
    tercero_id  character varying(32) NOT NULL,
    accidente_id integer NOT NULL,
    nombre character varying(80) NOT NULL,
    fecha_registro date NOT NULL,
    usuario_registro integer NOT NULL

















UV_personas_que_presensiaron_accidente AS l,
                    UV_agentes_riesgo_espacios_Accidente AS m


 parte_cuerpo_id character varying(6) NOT NULL,
    descripcion character varying(120) UNIQUE NOT NULL,
    sw_estado char NOT NULL,
    fecha_registro date NOT NULL,
    usuario_registro integer NOT NULL

accidente_id integer NOT NULL,
    tipo_id_trabajador character varying(3) NOT NULL,
    trabajador_id  character varying(32) NOT NULL,
    tipo_accidente_id character varying(6) NOT NULL,
    fecha_accidente date NOT NULL,
    hora_accidente character varying(9) NOT NULL,
    jornada_accidente character NOT NULL,
    realizando_trabajo_habitual character NOT NULL,
    trabajo_no_habitual character varying(90),
    tipo_pais_id character varying(4) NOT NULL,
    tipo_dpto_id character varying(4) NOT NULL,
    tipo_mpio_id character varying(4) NOT NULL,
    zona_residencial character NOT NULL,
    sw_accidente_dentro_empresa character NOT NULL,
    sitio_accidente_id character varying(6) NOT NULL,
    descripcion_accindente text NOT NULL,
    sw_personas_presenciaron_accidente character NOT NULL,
    fecha_registro date NOT NULL,
    usuario_registro integer NOT NULL


UV_partes_afectadas_cuerpo_trabajador AS h,
UV_TiposLesion_trabajador AS i,
UV_AgentesAccidente_trabajador AS j,
UV_FormaAccidente_trabajador AS k,


 


