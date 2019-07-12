CREATE TABLE soat_atencion_medica (
    evento integer NOT NULL,
    nombres_declara character varying(40) NOT NULL,
    apellidos_declara character varying(40) NOT NULL,
    tipo_id_paciente character varying(3) NOT NULL,
    declara_id character varying(32) NOT NULL,
    extipo_pais_id character varying(4),
    extipo_dpto_id character varying(4),
    extipo_mpio_id character varying(4),
    fecha_ingreso timestamp without time zone NOT NULL,
    datos1_ta character varying(7),
    datos2_fc character varying(7),
    datos3_fr character varying(7),
    datos4_te character varying(7),
    datos5_conciencia character varying(1),
    datos6_glasgow character varying(7),
    estado_embriaguez character varying(1),
    diagnostico1 text,
    diagnostico2 text,
    diagnostico3 text,
    diagnostico4 text,
    diagnostico5 text,
    diagnostico6 text,
    diagnostico7 text,
    diagnostico8 text,
    diagnostico9 text,
    diagnostico_def text,
    fecha_registro timestamp without time zone NOT NULL,
    usuario_id integer NOT NULL,
    tipo_id_tercero character varying(3),
    tercero_id character varying(32)
);
REVOKE ALL ON TABLE soat_atencion_medica FROM PUBLIC;
GRANT SELECT ON TABLE soat_atencion_medica TO siis_consulta;
GRANT INSERT,SELECT,UPDATE,DELETE ON TABLE soat_atencion_medica TO siis;
ALTER TABLE ONLY soat_atencion_medica
    ADD CONSTRAINT soat_atencion_medica_pkey PRIMARY KEY (evento);
ALTER TABLE ONLY soat_atencion_medica
    ADD CONSTRAINT "$1" FOREIGN KEY (evento) REFERENCES soat_eventos(evento) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY soat_atencion_medica
    ADD CONSTRAINT "$2" FOREIGN KEY (tipo_id_paciente) REFERENCES tipos_id_pacientes(tipo_id_paciente) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY soat_atencion_medica
    ADD CONSTRAINT "$3" FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY soat_atencion_medica
    ADD CONSTRAINT "$4" FOREIGN KEY (tipo_id_tercero, tercero_id) REFERENCES profesionales(tipo_id_tercero, tercero_id) ON UPDATE CASCADE ON DELETE RESTRICT;
COMMENT ON TABLE soat_atencion_medica IS 'Información de la atención medica por urgencias del paciente.';

ALTER TABLE soat_eventos DROP CONSTRAINT "$4";

DROP TABLE soat_polizas;

CREATE TABLE soat_polizas (
    poliza character varying(20) NOT NULL,
    vigencia_desde date,
    vigencia_hasta date,
    tipo_id_tercero character varying(3) NOT NULL,
    tercero_id character varying(32) NOT NULL,
    sucursal character varying(30),
    placa_vehiculo character varying(8),
    marca_vehiculo character varying(30),
    tipo_vehiculo character varying(30)
);
REVOKE ALL ON TABLE soat_polizas FROM PUBLIC;
GRANT SELECT ON TABLE soat_polizas TO siis_consulta;
GRANT INSERT,SELECT,UPDATE,DELETE ON TABLE soat_polizas TO siis;
ALTER TABLE ONLY soat_polizas
    ADD CONSTRAINT soat_polizas_pkey PRIMARY KEY (poliza);
ALTER TABLE ONLY soat_polizas
    ADD CONSTRAINT "$1" FOREIGN KEY (tipo_id_tercero, tercero_id) REFERENCES terceros_soat(tipo_id_tercero, tercero_id) ON UPDATE CASCADE ON DELETE RESTRICT;
COMMENT ON TABLE soat_polizas IS 'Polizas que amparan los gastos ecónomicos de un evento Soat.';
COMMENT ON COLUMN soat_polizas.poliza IS 'PK que identifica el número de la poliza del Soat.';
COMMENT ON COLUMN soat_polizas.vigencia_desde IS 'Inicio de la vigencia de la poliza.';
COMMENT ON COLUMN soat_polizas.vigencia_hasta IS 'Fin de la vigencia de la poliza.';
COMMENT ON COLUMN soat_polizas.sucursal IS 'Sucursal de la entidad que expide la poliza.';

ALTER TABLE ONLY soat_eventos
    ADD CONSTRAINT "$4" FOREIGN KEY (poliza) REFERENCES soat_polizas(poliza) ON UPDATE CASCADE ON DELETE RESTRICT;

DROP TABLE soat_vehiculo_propietario;

CREATE TABLE soat_vehiculo_propietario (
    evento integer NOT NULL,
    apellidos_propietario character varying(40) NOT NULL,
    nombres_propietario character varying(40) NOT NULL,
    tipo_id_propietario character varying(3) NOT NULL,
    propietario_id character varying(32) NOT NULL,
    extipo_pais_id character varying(4) NOT NULL,
    extipo_dpto_id character varying(4) NOT NULL,
    extipo_mpio_id character varying(4) NOT NULL,
    direccion_propietario character varying(40),
    telefono_propietario character varying(10),
    tipo_pais_id character varying(4) NOT NULL,
    tipo_dpto_id character varying(4) NOT NULL,
    tipo_mpio_id character varying(4) NOT NULL,
    fecha_registro timestamp without time zone NOT NULL,
    usuario_id integer NOT NULL
);
REVOKE ALL ON TABLE soat_vehiculo_propietario FROM PUBLIC;
GRANT SELECT ON TABLE soat_vehiculo_propietario TO siis_consulta;
GRANT INSERT,SELECT,UPDATE,DELETE ON TABLE soat_vehiculo_propietario TO siis;
ALTER TABLE ONLY soat_vehiculo_propietario
    ADD CONSTRAINT soat_vehiculo_propietario_pkey PRIMARY KEY (evento);
ALTER TABLE ONLY soat_vehiculo_propietario
    ADD CONSTRAINT "$1" FOREIGN KEY (evento) REFERENCES soat_eventos(evento) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY soat_vehiculo_propietario
    ADD CONSTRAINT "$2" FOREIGN KEY (tipo_id_propietario) REFERENCES tipos_id_pacientes(tipo_id_paciente) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY soat_vehiculo_propietario
    ADD CONSTRAINT "$3" FOREIGN KEY (extipo_pais_id, extipo_dpto_id, extipo_mpio_id) REFERENCES tipo_mpios(tipo_pais_id, tipo_dpto_id, tipo_mpio_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY soat_vehiculo_propietario
    ADD CONSTRAINT "$4" FOREIGN KEY (tipo_pais_id, tipo_dpto_id, tipo_mpio_id) REFERENCES tipo_mpios(tipo_pais_id, tipo_dpto_id, tipo_mpio_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY soat_vehiculo_propietario
    ADD CONSTRAINT "$5" FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE empresas ADD COLUMN nivel_atencion character (2);
ALTER TABLE empresas ADD COLUMN sw_filtrar_nivel character varying (1);

ALTER TABLE "cg_parametros_cuentas" DROP CONSTRAINT "$1";
ALTER TABLE "cg_parametros_cuentas" DROP CONSTRAINT "$3";
ALTER TABLE "cg_parametros_cuentas" DROP CONSTRAINT "cg_parametros_cuentas_pkey";
ALTER TABLE "cg_parametros_cuentas" DROP COLUMN "grupo_tarifario_id";
ALTER TABLE "cg_parametros_cuentas" DROP COLUMN "subgrupo_tarifario_id";
ALTER TABLE "cg_parametros_cuentas" DROP COLUMN "cuenta";

ALTER TABLE "cg_parametros_cuentas" ADD COLUMN "grupo_tipo_cargo" character varying(3);
ALTER TABLE "cg_parametros_cuentas" ADD COLUMN "tipo_cargo" character varying(3);
ALTER TABLE "cg_parametros_cuentas" ADD COLUMN "cuenta" character varying(32);

ALTER TABLE "cg_parametros_cuentas" ALTER COLUMN "grupo_tipo_cargo" SET NOT NULL;
ALTER TABLE "cg_parametros_cuentas" ALTER COLUMN "tipo_cargo" SET NOT NULL;
ALTER TABLE "cg_parametros_cuentas" ALTER COLUMN "cuenta" SET NOT NULL;

ALTER TABLE "cg_parametros_cuentas" ADD PRIMARY KEY (empresa_id, departamento, grupo_tipo_cargo, tipo_cargo);
ALTER TABLE "cg_parametros_cuentas" ADD FOREIGN KEY (empresa_id, cuenta) REFERENCES cg_plan_de_cuentas (empresa_id, cuenta) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE "cg_parametros_cuentas" ADD FOREIGN KEY (grupo_tipo_cargo, tipo_cargo) REFERENCES tipos_cargos (grupo_tipo_cargo, tipo_cargo) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE "cg_excepciones_parametros_cuentas" DROP CONSTRAINT "$3";
ALTER TABLE "cg_excepciones_parametros_cuentas" DROP CONSTRAINT "cg_excepciones_parametros_cuentas_pkey";
ALTER TABLE "cg_excepciones_parametros_cuentas" DROP COLUMN "tarifario_id";

ALTER TABLE "cg_excepciones_parametros_cuentas" ADD PRIMARY KEY (empresa_id, departamento, cargo);
ALTER TABLE "cg_excepciones_parametros_cuentas" ADD FOREIGN KEY (cargo) REFERENCES cups (cargo) ON UPDATE CASCADE ON DELETE RESTRICT;

DROP TABLE planes_honorarios;
DROP TABLE excepciones_honorarios;

DROP FUNCTION borrar_excepciones_honorarios();
DROP FUNCTION borrar_excepciones_honorarios_mod(text, text, text, text);
DROP FUNCTION excepciones_honorario(text, text, text);