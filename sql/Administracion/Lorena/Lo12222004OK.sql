
DELETE FROM banco_sangre_cruzes_correcciones;
DROP TABLE banco_sangre_cruzes_sanguineos CASCADE;
CREATE TABLE banco_sangre_cruzes_sanguineos (
    cruze_sanguineo_id serial NOT NULL,
    ingreso_bolsa_id integer NOT NULL,
    solicitud_reserva_sangre_id integer NOT NULL,
    hemoclasificacion_manual_anti_a character(1),
    hemoclasificacion_manual_anti_b character(1),
    hemoclasificacion_manual_anti_ab character(1),
    hemoclasificacion_manual_anti_d character(1),
    interpretacion_grupo_manual character varying(2) NOT NULL,
    interpretacion_rh_manual character(1) NOT NULL,
    tipo_id_profesional_manual character varying(3) NOT NULL,
    profesional_manual_id character varying(32) NOT NULL,
    hemoclasificacion_gel_anti_a character(1),
    hemoclasificacion_gel_anti_b character(1),
    hemoclasificacion_gel_anti_ab character(1),
    hemoclasificacion_gel_anti_d character(1),
    celulas_a character(1),
    celulas_b character(1),
    celulas_0 character(1),
		interpretacion_grupo_gel character varying(2) NOT NULL,
    interpretacion_rh_gel character(1) NOT NULL,
    tipo_id_profesional_gel character varying(3) NOT NULL,
    profesional_gel_id character varying(32) NOT NULL,
    reaccion_cruzada_visual character(1) NOT NULL,
    fase_coobms character(1) DEFAULT 0 NOT NULL,
    enzimas character(1) DEFAULT 0 NOT NULL,
    compatibilidad character(1) DEFAULT 1 NOT NULL,
    rai_cel1 character(1),
    rai_cel2 character(1),
		rai_auto character(1),
    rai_otros character(1),
    lectina character varying(1),
    cde character varying(4),
    fecha_prueba timestamp without time zone NOT NULL,
    observaciones text,
    tipo_id_profesional_entrega character varying(3) NOT NULL,
    profesional_entrega_id character varying(32) NOT NULL,
    tipo_id_profesional_recibe character varying(3) NOT NULL,
    profesional_recibe_id character varying(32) NOT NULL,
		fecha_recibe timestamp without time zone NOT NULL,
    usuario_id integer NOT NULL,
    fecha_registro timestamp without time zone NOT NULL,
    estado character(1) NOT NULL
);

ALTER TABLE ONLY banco_sangre_cruzes_sanguineos ADD PRIMARY KEY (cruze_sanguineo_id);
ALTER TABLE ONLY banco_sangre_cruzes_sanguineos ADD UNIQUE (cruze_sanguineo_id, ingreso_bolsa_id, solicitud_reserva_sangre_id);
ALTER TABLE ONLY banco_sangre_cruzes_sanguineos ADD FOREIGN KEY (ingreso_bolsa_id) REFERENCES banco_sangre_bolsas(ingreso_bolsa_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY banco_sangre_cruzes_sanguineos ADD FOREIGN KEY (solicitud_reserva_sangre_id) REFERENCES banco_sangre_reserva(solicitud_reserva_sangre_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY banco_sangre_cruzes_sanguineos ADD FOREIGN KEY (interpretacion_grupo_manual,interpretacion_rh_manual) REFERENCES hc_tipos_sanguineos(grupo_sanguineo, rh) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY banco_sangre_cruzes_sanguineos ADD FOREIGN KEY (tipo_id_profesional_manual, profesional_manual_id) REFERENCES profesionales(tipo_id_tercero, tercero_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY banco_sangre_cruzes_sanguineos ADD FOREIGN KEY (interpretacion_grupo_gel, interpretacion_rh_gel) REFERENCES hc_tipos_sanguineos(grupo_sanguineo, rh) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY banco_sangre_cruzes_sanguineos ADD FOREIGN KEY (tipo_id_profesional_gel, profesional_gel_id) REFERENCES profesionales(tipo_id_tercero, tercero_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY banco_sangre_cruzes_sanguineos ADD FOREIGN KEY (tipo_id_profesional_entrega, profesional_entrega_id) REFERENCES profesionales(tipo_id_tercero, tercero_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY banco_sangre_cruzes_sanguineos ADD FOREIGN KEY (tipo_id_profesional_recibe, profesional_recibe_id) REFERENCES profesionales(tipo_id_tercero, tercero_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY banco_sangre_cruzes_sanguineos ADD FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY banco_sangre_cruzes_correcciones ADD FOREIGN KEY (cruze_sanguineo_id) REFERENCES banco_sangre_cruzes_sanguineos(cruze_sanguineo_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY banco_sangre_cruzes_correcciones ADD FOREIGN KEY (cruze_corrige) REFERENCES banco_sangre_cruzes_sanguineos(cruze_sanguineo_id) ON UPDATE CASCADE ON DELETE RESTRICT;

CREATE TABLE banco_sangre_cruzes_sanguineos_entregados(
  cruze_sanguineo_id integer,
  tipo_id_profesional_entrega character varying(3) NOT NULL,
	profesional_entrega_id character varying(32) NOT NULL,
	tipo_id_profesional_recibe character varying(3) NOT NULL,
	profesional_recibe_id character varying(32) NOT NULL,
	fecha_recibe timestamp without time zone NOT NULL,
	usuario_id integer NOT NULL,
	fecha_registro timestamp without time zone NOT NULL
);
ALTER TABLE banco_sangre_cruzes_sanguineos_entregados ADD PRIMARY KEY (cruze_sanguineo_id);
ALTER TABLE banco_sangre_cruzes_sanguineos_entregados ADD FOREIGN KEY (cruze_sanguineo_id) REFERENCES banco_sangre_cruzes_sanguineos(cruze_sanguineo_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE banco_sangre_cruzes_sanguineos_entregados ADD FOREIGN KEY (tipo_id_profesional_entrega, profesional_entrega_id) REFERENCES profesionales(tipo_id_tercero, tercero_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE banco_sangre_cruzes_sanguineos_entregados ADD FOREIGN KEY (tipo_id_profesional_recibe, profesional_recibe_id) REFERENCES profesionales(tipo_id_tercero, tercero_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE banco_sangre_cruzes_sanguineos_entregados ADD FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE banco_sangre_cruzes_sanguineos RENAME COLUMN tipo_id_profesional_entrega TO tipo_id_profesional_responsable;
ALTER TABLE banco_sangre_cruzes_sanguineos RENAME COLUMN profesional_entrega_id TO profesional_responsable_id;
ALTER TABLE banco_sangre_cruzes_sanguineos DROP COLUMN tipo_id_profesional_recibe;
ALTER TABLE banco_sangre_cruzes_sanguineos DROP COLUMN profesional_recibe_id;
ALTER TABLE banco_sangre_cruzes_sanguineos DROP COLUMN fecha_recibe;

CREATE TABLE banco_sangre_albaranes(
   registro_albaran_id SERIAL,
	 albaran character varying(20) NOT NULL,
	 entidad_origen character varying(6) NOT NULL
);
ALTER TABLE banco_sangre_albaranes ADD PRIMARY KEY(registro_albaran_id);
ALTER TABLE banco_sangre_albaranes ADD UNIQUE (albaran,entidad_origen);
ALTER TABLE banco_sangre_albaranes ADD FOREIGN KEY (entidad_origen) REFERENCES terceros_sgsss(codigo_sgsss) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE banco_sangre_bolsas ADD COLUMN registro_albaran_id INTEGER;
ALTER TABLE banco_sangre_bolsas ADD FOREIGN KEY (registro_albaran_id) REFERENCES banco_sangre_albaranes(registro_albaran_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE banco_sangre_bolsas DROP COLUMN entidad_origen;
ALTER TABLE banco_sangre_bolsas DROP COLUMN albaran;

ALTER TABLE banco_sangre_reserva ALTER COLUMN grupo_sanguineo DROP NOT NULL;
ALTER TABLE banco_sangre_reserva ALTER COLUMN rh DROP NOT NULL;

ALTER TABLE banco_sangre_entrega_bolsas ADD COLUMN numero_alicuota smallint;
UPDATE banco_sangre_entrega_bolsas SET numero_alicuota='0';
ALTER TABLE banco_sangre_entrega_bolsas ALTER COLUMN numero_alicuota SET NOT NULL;
ALTER TABLE banco_sangre_entrega_bolsas ADD FOREIGN KEY (ingreso_bolsa_id,numero_alicuota) REFERENCES banco_sangre_bolsas_alicuotas(ingreso_bolsa_id,numero_alicuota) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE banco_sangre_entrega_bolsas ADD PRIMARY KEY (ingreso_bolsa_id,numero_alicuota);

CREATE TABLE banco_sangre_entrega_bolsas_enrega_confirmacion(
  ingreso_bolsa_id integer,
  numero_alicuota smallint,
	usuario_id integer
);
ALTER TABLE banco_sangre_entrega_bolsas_enrega_confirmacion ADD FOREIGN KEY (ingreso_bolsa_id,numero_alicuota) REFERENCES banco_sangre_entrega_bolsas(ingreso_bolsa_id,numero_alicuota) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE banco_sangre_entrega_bolsas_enrega_confirmacion ADD FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;





