--ALTER TABLE terceros_sgsss ADD COLUMN codigo_sgsss2 Character Varying(10);
--UPDATE terceros_sgsss SET codigo_sgsss2=codigo_sgsss;
--ALTER TABLE terceros_sgsss DROP COLUMN codigo_sgsss;
--ALTER TABLE terceros_sgsss RENAME COLUMN codigo_sgsss2 TO codigo_sgsss;

DROP TABLE soat_vehiculo_conductor;
CREATE TABLE soat_vehiculo_conductor (
    evento integer NOT NULL,
    apellidos_conductor character varying(60) NOT NULL,
    nombres_conductor character varying(40) NOT NULL,
    tipo_id_conductor character varying(3) NOT NULL,
    conductor_id character varying(32) NOT NULL,
    extipo_pais_id character varying(4) NOT NULL,
    extipo_dpto_id character varying(4) NOT NULL,
    extipo_mpio_id character varying(4) NOT NULL,
    direccion_conductor character varying(40) NOT NULL,
    telefono_conductor character varying(10) NOT NULL,
    tipo_pais_id character varying(4) NOT NULL,
    tipo_dpto_id character varying(4) NOT NULL,
    tipo_mpio_id character varying(4) NOT NULL,
    fecha_registro timestamp without time zone NOT NULL,
    usuario_id integer NOT NULL
);
ALTER TABLE ONLY soat_vehiculo_conductor
    ADD CONSTRAINT soat_vehiculo_conductor_pkey PRIMARY KEY (evento);
ALTER TABLE ONLY soat_vehiculo_conductor
    ADD CONSTRAINT "$1" FOREIGN KEY (evento) REFERENCES soat_eventos(evento) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY soat_vehiculo_conductor
    ADD CONSTRAINT "$2" FOREIGN KEY (tipo_id_conductor) REFERENCES tipos_id_pacientes(tipo_id_paciente) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY soat_vehiculo_conductor
    ADD CONSTRAINT "$3" FOREIGN KEY (extipo_pais_id, extipo_dpto_id, extipo_mpio_id) REFERENCES tipo_mpios(tipo_pais_id, tipo_dpto_id, tipo_mpio_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY soat_vehiculo_conductor
    ADD CONSTRAINT "$4" FOREIGN KEY (tipo_pais_id, tipo_dpto_id, tipo_mpio_id) REFERENCES tipo_mpios(tipo_pais_id, tipo_dpto_id, tipo_mpio_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY soat_vehiculo_conductor
    ADD CONSTRAINT "$5" FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;


DROP TABLE soat_vehiculo_propietario;
CREATE TABLE soat_vehiculo_propietario (
    evento integer NOT NULL,
    apellidos_propietario character varying(60) NOT NULL,
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
