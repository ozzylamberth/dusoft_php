-- CAMBIOS REALIZADOS PARA FUNCIONAMIENTO DE SUBMODULOS
-- RESPONSABLE TIZZIANO PEREA O.


--
-- SUBMODULO INGRESO DE GESTACION
-- ACTUALIZACIONES REALIZADAS A LA BASE DE DATOS
-- CAMBIOS DEL DIA 09-29-2004
--



ALTER TABLE "gestacion" ADD COLUMN "fecha_registro" timestamp without time zone;
ALTER TABLE "gestacion" ALTER COLUMN "fecha_registro" SET NOT NULL;
ALTER TABLE "gestacion" ALTER COLUMN "fecha_registro" SET DEFAULT now();
ALTER TABLE "gestacion" ADD COLUMN "evolucion_id" integer;
ALTER TABLE "gestacion" ADD FOREIGN KEY ("evolucion_id") REFERENCES "public"."hc_evoluciones"("evolucion_id")  ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE "gestacion" ADD COLUMN "usuario_id" integer;
ALTER TABLE "gestacion" ALTER COLUMN "usuario_id" SET NOT NULL;
ALTER TABLE "gestacion" ADD FOREIGN KEY ("usuario_id") REFERENCES "public"."system_usuarios"("usuario_id")  ON UPDATE CASCADE ON DELETE RESTRICT;

-- INSERT INTO "system_hc_submodulos" ("submodulo", "descripcion", "version_numero", "version_info", "activo", "sexo_id", "gestacion", "edad_max", "edad_min") VALUES ('IngresoGestacion', 'INGRESO DE PERSONAS GESTANTES', 1.00, ''::character varying, '1'::bpchar, NULL, NULL, NULL, NULL);
INSERT INTO historias_clinicas_templates VALUES ('ConsultaExterna', 'IngresoGestacion', 23, 0, '1', '1', '0');


--
-- SUBMODULO SIGNOS VITALES GENERALES
-- ACTUALIZACIONES REALIZADAS A LA BASE DE DATOS
-- CAMBIOS DEL DIA 10-15-2004
--

DROP TABLE hc_signos_vitales;
DROP TABLE hc_signos_vitales_sitios;

CREATE TABLE hc_signos_vitales_sitios (
    sitio_id character varying(2) NOT NULL,
    descripcion character varying(50) NOT NULL,
    indice_orden integer
);

INSERT INTO hc_signos_vitales_sitios VALUES ('1', 'Miembro Superior Derecho(NINV)', 0);
INSERT INTO hc_signos_vitales_sitios VALUES ('2', 'Miembro Superior Izquierdo(NINV)', 1);
INSERT INTO hc_signos_vitales_sitios VALUES ('3', 'Miembro Inferior Derecho(NINV)', 2);
INSERT INTO hc_signos_vitales_sitios VALUES ('4', 'Miembro Inferior Izquierdo(NINV)', 3);
INSERT INTO hc_signos_vitales_sitios VALUES ('5', 'Arterial Radial Derecha(INV)', 4);
INSERT INTO hc_signos_vitales_sitios VALUES ('6', 'Arterial Cubital Derecha(INV)', 5);
INSERT INTO hc_signos_vitales_sitios VALUES ('7', 'Arterial Pedia Derecha(INV)', 6);
INSERT INTO hc_signos_vitales_sitios VALUES ('8', 'Arterial Pedia Izquierda(INV)', 7);
INSERT INTO hc_signos_vitales_sitios VALUES ('9', 'Arterial Cubital Izquierda(INV)', 8);
INSERT INTO hc_signos_vitales_sitios VALUES ('10', 'Arterial Radial Izquierda(INV)', 9);
INSERT INTO hc_signos_vitales_sitios VALUES ('11', 'Arterial Umbilical(INV)', 10);


ALTER TABLE hc_signos_vitales_sitios
    ADD PRIMARY KEY (sitio_id);


COMMENT ON TABLE hc_signos_vitales_sitios IS 'Maestro donde se guardan los diferentes sitios donde se toman los signos vitales del paciente';

COMMENT ON COLUMN hc_signos_vitales_sitios.descripcion IS 'Nombre del sitio donde se toman los signos vitales';

--
-- SUBMODULO SIGNOS VITALES GENERALES
-- ACTUALIZACIONES REALIZADAS A LA BASE DE DATOS
-- CAMBIOS DEL DIA 10-15-2004
--

CREATE TABLE hc_signos_vitales (
    sitio_id character varying(2),
    fecha timestamp without time zone NOT NULL,
    fc numeric(5,0),
    pvc numeric(5,0),
    ta_alta numeric(5,0),
    media numeric(5,0),
    temp_piel numeric(5,2),
    servo numeric(5,2),
    manual numeric(5,2),
    presion_intracraneana numeric(5,2),
    ingreso integer NOT NULL,
    usuario_id integer NOT NULL,
    peso numeric(6,2),
    ta_baja numeric(5,0),
    evolucion_id integer,
    fecha_registro timestamp without time zone,
    observacion character varying(256),
    sato2 numeric(5,2),
    evaluacion_dolor smallint,
    fr numeric(5,0)
);


ALTER TABLE  hc_signos_vitales
    ADD PRIMARY KEY (fecha, ingreso);


ALTER TABLE  hc_signos_vitales
    ADD  FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE  hc_signos_vitales
    ADD  FOREIGN KEY (ingreso) REFERENCES ingresos(ingreso) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE  hc_signos_vitales
    ADD  FOREIGN KEY (sitio_id) REFERENCES hc_signos_vitales_sitios(sitio_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE  hc_signos_vitales
    ADD  FOREIGN KEY (evolucion_id) REFERENCES hc_evoluciones(evolucion_id) ON UPDATE CASCADE ON DELETE RESTRICT;


COMMENT ON TABLE hc_signos_vitales IS 'Tabla donde se guardan los signos vitales de cada paciente de una estacion';

COMMENT ON COLUMN hc_signos_vitales.evaluacion_dolor IS 'Escala de evaluacion del dolor';

COMMENT ON COLUMN hc_signos_vitales.fr IS 'Retiene valores de frecuencia respiratoria';


--
-- SUBMODULO NOTAS DE ENFERMERIA
-- ACTUALIZACIONES REALIZADAS A LA BASE DE DATOS
-- CAMBIOS DEL DIA 10-20-2004
--

CREATE TABLE hc_notas_enfermeria_descripcion (
    hc_notas_enfermeria_descripcion_id serial NOT NULL,
    descripcion text NOT NULL,
    evolucion_id integer,
    usuario_id integer NOT NULL,
    fecha_registro timestamp without time zone,
    ingreso integer NOT NULL
);


ALTER TABLE hc_notas_enfermeria_descripcion
    ADD PRIMARY KEY (hc_notas_enfermeria_descripcion_id);


ALTER TABLE hc_notas_enfermeria_descripcion
    ADD FOREIGN KEY (evolucion_id) REFERENCES hc_evoluciones(evolucion_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE hc_notas_enfermeria_descripcion
    ADD FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE hc_notas_enfermeria_descripcion
    ADD FOREIGN KEY (ingreso) REFERENCES ingresos(ingreso) ON UPDATE CASCADE ON DELETE RESTRICT;

COMMENT ON COLUMN hc_notas_enfermeria_descripcion.evolucion_id IS 'Registro de Evoluciones';

COMMENT ON COLUMN hc_notas_enfermeria_descripcion.fecha_registro IS 'Fecha de registro de datos';

COMMENT ON COLUMN hc_notas_enfermeria_descripcion.ingreso IS 'Codigo de Ingreso';


--
-- SUBMODULO EVOLUCIONES
-- ACTUALIZACIONES REALIZADAS A LA BASE DE DATOS
-- CAMBIOS DEL DIA 10-25-2004
--

ALTER TABLE "hc_evolucion_descripcion" ADD COLUMN "ingreso" integer;
ALTER TABLE "hc_evolucion_descripcion" ADD FOREIGN KEY ("ingreso") REFERENCES "public"."ingresos"("ingreso")  ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE "hc_evolucion_descripcion" ADD COLUMN "usuario_id" integer;
ALTER TABLE "hc_evolucion_descripcion" ADD FOREIGN KEY ("usuario_id") REFERENCES "public"."system_usuarios"("usuario_id")  ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE "hc_evolucion_descripcion" ADD COLUMN "fecha_registro" timestamp without time zone;

--
-- SUBMODULO ASISTENCIA VENTILATORIA
-- ACTUALIZACIONES REALIZADAS A LA BASE DE DATOS
-- CAMBIOS DEL DIA 10-30-2004
--

DROP TABLE hc_asistencia_ventilatoria;
DROP TABLE hc_asistencia_ventilatoria_modos;

CREATE TABLE hc_asistencia_ventilatoria_modos (
    modo_id character varying(2) NOT NULL,
    descripcion character varying(50) NOT NULL,
    sw_neonatos integer,
    indice_orden integer
);


INSERT INTO hc_asistencia_ventilatoria_modos VALUES ('1', 'Volumen Control AC', 0, 0);
INSERT INTO hc_asistencia_ventilatoria_modos VALUES ('4', 'Presión Asistida PA', 0, 1);
INSERT INTO hc_asistencia_ventilatoria_modos VALUES ('3', 'Presión Control PC', 1, 2);
INSERT INTO hc_asistencia_ventilatoria_modos VALUES ('5', 'SIMV+PA', 0, 3);
INSERT INTO hc_asistencia_ventilatoria_modos VALUES ('2', 'SIMV', 1, 4);
INSERT INTO hc_asistencia_ventilatoria_modos VALUES ('6', 'CPAP', 1, 5);

ALTER TABLE hc_asistencia_ventilatoria_modos
    ADD PRIMARY KEY (modo_id);

COMMENT ON TABLE hc_asistencia_ventilatoria_modos IS 'Maestro donde se guardan cada uno de los modos de la asistencia ventilatoria';

COMMENT ON COLUMN hc_asistencia_ventilatoria_modos.modo_id IS 'PK Identificación del modo';

COMMENT ON COLUMN hc_asistencia_ventilatoria_modos.descripcion IS 'Descrpcion del modo';

COMMENT ON COLUMN hc_asistencia_ventilatoria_modos.sw_neonatos IS 'Selector de propiedades';

COMMENT ON COLUMN hc_asistencia_ventilatoria_modos.indice_orden IS 'Ordenador';



CREATE TABLE hc_asistencia_ventilatoria (
    fecha timestamp without time zone NOT NULL,
    modo_id character varying(2) NOT NULL,
    f102_id character varying(2),
    fr_respiratoria numeric(5,0),
    fr_ventilatoria numeric(5,2),
    sens numeric(5,0),
    ti numeric(5,2),
    i_e character varying(10),
    peep numeric(5,0),
    paw numeric(5,2),
    t_via_a numeric(5,2),
    pp numeric(6,1),
    pm numeric(6,1),
    etco2 numeric(6,1),
    ingreso integer NOT NULL,
    usuario_id integer NOT NULL,
    evolucion_id integer,
    fecha_registro timestamp without time zone,
    expontanea numeric(5,0),
    volumen numeric(5,0),
    p_insp numeric(5,0),
    pip numeric(5,2)
);


ALTER TABLE hc_asistencia_ventilatoria
    ADD PRIMARY KEY (fecha, ingreso);


ALTER TABLE hc_asistencia_ventilatoria
    ADD FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY hc_asistencia_ventilatoria
    ADD FOREIGN KEY (ingreso) REFERENCES ingresos(ingreso) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY hc_asistencia_ventilatoria
    ADD FOREIGN KEY (modo_id) REFERENCES hc_asistencia_ventilatoria_modos(modo_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY hc_asistencia_ventilatoria
    ADD FOREIGN KEY (f102_id) REFERENCES hc_tipos_concentracion_oxigenoterapia(concentracion_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY hc_asistencia_ventilatoria
    ADD FOREIGN KEY (evolucion_id) REFERENCES hc_evoluciones(evolucion_id) ON UPDATE CASCADE ON DELETE RESTRICT;

COMMENT ON TABLE hc_asistencia_ventilatoria IS 'Contiene los valores obtenidos al realizar este control al paciente';

COMMENT ON COLUMN hc_asistencia_ventilatoria.fecha IS 'PK: fecha en que se realiza el control';

COMMENT ON COLUMN hc_asistencia_ventilatoria.modo_id IS 'FK: modo de la asistencia ventilatoria';

COMMENT ON COLUMN hc_asistencia_ventilatoria.f102_id IS 'FK: tipos de concentracion de oxigenoterapia';

COMMENT ON COLUMN hc_asistencia_ventilatoria.fr_respiratoria IS 'valor de la fr_respiratoria';

COMMENT ON COLUMN hc_asistencia_ventilatoria.fr_ventilatoria IS 'valor de la fr_ventilatoria';

COMMENT ON COLUMN hc_asistencia_ventilatoria.sens IS 'valor del sens';

COMMENT ON COLUMN hc_asistencia_ventilatoria.ti IS 'valor de ti';

COMMENT ON COLUMN hc_asistencia_ventilatoria.i_e IS 'valor de i_e';

COMMENT ON COLUMN hc_asistencia_ventilatoria.peep IS 'valor de peep';

COMMENT ON COLUMN hc_asistencia_ventilatoria.paw IS 'valor de paw';

COMMENT ON COLUMN hc_asistencia_ventilatoria.t_via_a IS 'valor de t_via_a';

COMMENT ON COLUMN hc_asistencia_ventilatoria.pp IS 'valor de pp';

COMMENT ON COLUMN hc_asistencia_ventilatoria.pm IS 'valor de pm';

COMMENT ON COLUMN hc_asistencia_ventilatoria.etco2 IS 'valor de etco2';

COMMENT ON COLUMN hc_asistencia_ventilatoria.ingreso IS 'PK:FK ingreso del paciente';

COMMENT ON COLUMN hc_asistencia_ventilatoria.usuario_id IS 'FK: usuario que realiza el registro';

COMMENT ON COLUMN hc_asistencia_ventilatoria.evolucion_id IS 'Campo de registro de evoluciones';

COMMENT ON COLUMN hc_asistencia_ventilatoria.fecha_registro IS 'Registro de fechas de inserciones en tabla';

COMMENT ON COLUMN hc_asistencia_ventilatoria.expontanea IS 'valor de la expontanea';

COMMENT ON COLUMN hc_asistencia_ventilatoria.volumen IS 'valor del volumen';

COMMENT ON COLUMN hc_asistencia_ventilatoria.p_insp IS 'valor de p_insp';

COMMENT ON COLUMN hc_asistencia_ventilatoria.pip IS 'valor de pip';


--
-- SUBMODULO CONTROLES NEUROLOGICOS
-- ACTUALIZACIONES REALIZADAS A LA BASE DE DATOS
-- FECHA ACTUALIZACION 4 - 11 - 2004
-- ACTUALIZACION DE TABLA hc_tipos_respuesta_motora
-- EJECUTAR QUERY


ALTER TABLE "hc_tipos_respuesta_motora" ADD COLUMN "descripcion_lactante" character varying(40);

UPDATE hc_tipos_respuesta_motora SET descripcion_lactante = 'Ninguna' WHERE respuesta_motora_id = '1';
UPDATE hc_tipos_respuesta_motora SET descripcion_lactante = 'Extensión Anormal' WHERE respuesta_motora_id = '2';
UPDATE hc_tipos_respuesta_motora SET descripcion_lactante = 'Movimiento Expontaneo' WHERE respuesta_motora_id = '6';
UPDATE hc_tipos_respuesta_motora SET descripcion_lactante = 'Flexion Anormal' WHERE respuesta_motora_id = '3';
UPDATE hc_tipos_respuesta_motora SET descripcion_lactante = 'Retira a Estimulo' WHERE respuesta_motora_id = '4';
UPDATE hc_tipos_respuesta_motora SET descripcion_lactante = 'Retira al Tacto' WHERE respuesta_motora_id = '5';
UPDATE hc_tipos_respuesta_motora SET descripcion = 'Flexion' WHERE respuesta_motora_id = '3';

ALTER TABLE "hc_tipos_respuesta_motora" ALTER COLUMN "descripcion_lactante" SET NOT NULL;

--
-- SUBMODULO CONTROLES NEUROLOGICOS
-- ACTUALIZACIONES REALIZADAS A LA BASE DE DATOS
-- FECHA ACTUALIZACION 4 - 11 - 2004
-- ACTUALIZACION DE TABLA hc_tipos_respuesta_verbal
-- EJECUTAR QUERY


ALTER TABLE "hc_tipos_respuesta_verbal" ADD COLUMN "descripcion_lactante" character varying(40);

UPDATE hc_tipos_respuesta_verbal SET descripcion_lactante = 'Ninguna' WHERE respuesta_verbal_id = '1';
UPDATE hc_tipos_respuesta_verbal SET descripcion_lactante = 'Gime Respuesta a Dolor' WHERE respuesta_verbal_id = '2';
UPDATE hc_tipos_respuesta_verbal SET descripcion_lactante = 'Llora Respuesta a dolor' WHERE respuesta_verbal_id = '3';
UPDATE hc_tipos_respuesta_verbal SET descripcion_lactante = 'Irritable' WHERE respuesta_verbal_id = '4';
UPDATE hc_tipos_respuesta_verbal SET descripcion_lactante = 'Balbucea' WHERE respuesta_verbal_id = '5';

ALTER TABLE "hc_tipos_respuesta_verbal" ALTER COLUMN "descripcion_lactante" SET NOT NULL;


-- CREACION DE TABLA hc_controles_neurologia
-- CAMBIOS VALIDOS PARA EL SUBMODULO DE CONTROLES NEUROLOGICOS


CREATE TABLE hc_controles_neurologia (
    control_neurologico_id serial NOT NULL,
    fecha timestamp without time zone NOT NULL,
    pupila_talla_d character varying(2),
    pupila_talla_i character varying(2),
    pupila_reaccion_d character varying(2) NOT NULL,
    pupila_reaccion_i character varying(2) NOT NULL,
    tipo_nivel_consciencia_id character varying(2),
    fuerza_brazo_d character varying(2) NOT NULL,
    fuerza_brazo_i character varying(2) NOT NULL,
    fuerza_pierna_d character varying(2) NOT NULL,
    fuerza_pierna_i character varying(2) NOT NULL,
    tipo_apertura_ocular_id character varying(2),
    tipo_respuesta_verbal_id character varying(2),
    tipo_respuesta_motora_id character varying(2),
    usuario_id integer NOT NULL,
    ingreso integer NOT NULL,
	evolucion_id integer,
	fecha_registro timestamp without time zone NOT NULL
);


ALTER TABLE hc_controles_neurologia
    ADD PRIMARY KEY (control_neurologico_id);


ALTER TABLE hc_controles_neurologia
    ADD FOREIGN KEY (pupila_talla_d) REFERENCES hc_tipos_talla_pupila(talla_pupila_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE hc_controles_neurologia
    ADD FOREIGN KEY (pupila_talla_i) REFERENCES hc_tipos_talla_pupila(talla_pupila_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE hc_controles_neurologia
    ADD FOREIGN KEY (pupila_reaccion_d) REFERENCES hc_tipos_reaccion_pupila(reaccion_pupila_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE hc_controles_neurologia
    ADD FOREIGN KEY (pupila_reaccion_i) REFERENCES hc_tipos_reaccion_pupila(reaccion_pupila_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE hc_controles_neurologia
    ADD FOREIGN KEY (tipo_nivel_consciencia_id) REFERENCES hc_tipos_nivel_consciencia(nivel_consciencia_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE hc_controles_neurologia
    ADD FOREIGN KEY (fuerza_brazo_d) REFERENCES hc_tipos_fuerza(fuerza_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE hc_controles_neurologia
    ADD FOREIGN KEY (fuerza_brazo_i) REFERENCES hc_tipos_fuerza(fuerza_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE hc_controles_neurologia
    ADD FOREIGN KEY (fuerza_pierna_d) REFERENCES hc_tipos_fuerza(fuerza_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE hc_controles_neurologia
    ADD FOREIGN KEY (fuerza_pierna_i) REFERENCES hc_tipos_fuerza(fuerza_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE hc_controles_neurologia
    ADD FOREIGN KEY (tipo_apertura_ocular_id) REFERENCES hc_tipos_apertura_ocular(apertura_ocular_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE hc_controles_neurologia
    ADD FOREIGN KEY (tipo_respuesta_verbal_id) REFERENCES hc_tipos_respuesta_verbal(respuesta_verbal_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE hc_controles_neurologia
    ADD FOREIGN KEY (tipo_respuesta_motora_id) REFERENCES hc_tipos_respuesta_motora(respuesta_motora_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE hc_controles_neurologia
    ADD FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE hc_controles_neurologia
    ADD FOREIGN KEY (ingreso) REFERENCES ingresos(ingreso) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE hc_controles_neurologia
    ADD FOREIGN KEY (evolucion_id) REFERENCES hc_evoluciones(evolucion_id) ON UPDATE CASCADE ON DELETE RESTRICT;


COMMENT ON TABLE hc_controles_neurologia IS 'tabla para el control del sistema neurológico';


COMMENT ON COLUMN hc_controles_neurologia.control_neurologico_id IS 'PK Id del sistema neurologico';


COMMENT ON COLUMN hc_controles_neurologia.fecha IS 'Fecha del control del sitema neurologico';


COMMENT ON COLUMN hc_controles_neurologia.pupila_talla_d IS 'FK talla de la pupila derecha';


COMMENT ON COLUMN hc_controles_neurologia.pupila_talla_i IS 'FK talla pupila izquierda';


COMMENT ON COLUMN hc_controles_neurologia.pupila_reaccion_d IS 'FK reaccion de la pupila derecha';


COMMENT ON COLUMN hc_controles_neurologia.pupila_reaccion_i IS 'FK reacción pupila izquierda';


COMMENT ON COLUMN hc_controles_neurologia.usuario_id IS 'FK Id del system_usuarios que utiliza el sistema';


COMMENT ON COLUMN hc_controles_neurologia.ingreso IS 'FK ingreso del paciente';


--
-- CREACION DE TABLA hc_tipo_remision
-- VALIDO PARA EL MODULO DE REMISIONES
-- TIZZIANO PEREA
--


CREATE TABLE hc_tipo_remision (
    tipo_remision_id character varying(2) NOT NULL,
    descripcion character varying(40) NOT NULL,
    indice_orden smallint DEFAULT 0 NOT NULL
);

ALTER TABLE hc_tipo_remision
    ADD PRIMARY KEY (tipo_remision_id);

COMMENT ON TABLE hc_tipo_remision IS 'Tabla que contiene los tipos de remision';


--
-- CREACION DE TABLA hc_conducta_remision
-- VALIDO PARA EL MODULO DE REMISIONES
-- TIZZIANO PEREA
--

CREATE TABLE hc_conducta_remision (
    ingreso integer NOT NULL,
    evolucion_id integer NOT NULL,
    descripcion_otro_motivo character varying(256),
    observaciones text,
    tipo_remision character varying(3) NOT NULL,
    traslado_ambulancia character(1) DEFAULT 0 NOT NULL,
    nivel_centro_remision character varying(1)
);


ALTER TABLE hc_conducta_remision
    ADD PRIMARY KEY (ingreso, evolucion_id);

ALTER TABLE hc_conducta_remision
    ADD FOREIGN KEY (ingreso) REFERENCES ingresos(ingreso) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE hc_conducta_remision
    ADD FOREIGN KEY (evolucion_id) REFERENCES hc_evoluciones(evolucion_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY hc_conducta_remision
    ADD FOREIGN KEY (tipo_remision) REFERENCES hc_tipo_remision(tipo_remision_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY hc_conducta_remision
    ADD FOREIGN KEY (nivel_centro_remision) REFERENCES niveles_atencion(nivel) ON UPDATE CASCADE ON DELETE RESTRICT;

COMMENT ON COLUMN hc_conducta_remision.descripcion_otro_motivo IS 'Insertar Motivo adicional';

COMMENT ON COLUMN hc_conducta_remision.observaciones IS 'Observaciones medicas';

COMMENT ON COLUMN hc_conducta_remision.tipo_remision IS 'Tipos de remisiones';

COMMENT ON COLUMN hc_conducta_remision.traslado_ambulancia IS 'Si se debe realizar traslado en ambulacia';

COMMENT ON COLUMN hc_conducta_remision.nivel_centro_remision IS 'Nivel del centro de atencion al que se remite el paciente';



--
-- CREACION DE TABLA hc_conducta_remision_centros
-- VALIDO PARA EL MODULO DE REMISIONES
-- TIZZIANO PEREA
--

CREATE TABLE hc_conducta_remision_centros (
    ingreso integer NOT NULL,
    evolucion_id integer NOT NULL,
    centro_remision character varying(10)
);


ALTER TABLE hc_conducta_remision_centros
    ADD PRIMARY KEY (ingreso, evolucion_id);

ALTER TABLE hc_conducta_remision_centros
    ADD FOREIGN KEY (ingreso, evolucion_id) REFERENCES hc_conducta_remision(ingreso, evolucion_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE hc_conducta_remision_centros
    ADD FOREIGN KEY (centro_remision) REFERENCES centros_remision(centro_remision) ON UPDATE CASCADE ON DELETE RESTRICT;

COMMENT ON TABLE hc_conducta_remision_centros IS 'Se guardan los centros donde son remitidos los pacientes';

--
-- CREACION DE TABLA hc_motivos_remision
-- VALIDO PARA EL MODULO DE REMISIONES
-- TIZZIANO PEREA
--

CREATE TABLE hc_motivos_remision (
    motivo_remision_id character varying(2) NOT NULL,
    descripcion character varying(40) NOT NULL
);

ALTER TABLE hc_motivos_remision
    ADD PRIMARY KEY (motivo_remision_id);


--
-- CREACION DE TABLA hc_conducta_remision_motivos
-- VALIDO PARA EL MODULO DE REMISIONES
-- TIZZIANO PEREA
--

CREATE TABLE hc_conducta_remision_motivos (
    ingreso integer NOT NULL,
    evolucion_id integer NOT NULL,
    motivo_remision_id character varying(2) NOT NULL
);


ALTER TABLE hc_conducta_remision_motivos
    ADD PRIMARY KEY (ingreso, evolucion_id, motivo_remision_id);

ALTER TABLE hc_conducta_remision_motivos
    ADD FOREIGN KEY (motivo_remision_id) REFERENCES hc_motivos_remision(motivo_remision_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE hc_conducta_remision_motivos
    ADD FOREIGN KEY (ingreso, evolucion_id) REFERENCES hc_conducta_remision(ingreso, evolucion_id) ON UPDATE CASCADE ON DELETE CASCADE;



-- INSERT DE hc_tipo_remision
INSERT INTO hc_tipo_remision VALUES ('0', 'AMBULATORIA', 0);
INSERT INTO hc_tipo_remision VALUES ('1', 'TRASLADO URGENTE', 1);


-- INSERT DE hc_motivos_remision


INSERT INTO hc_motivos_remision VALUES ('1', 'Servicio no ofertado');
INSERT INTO hc_motivos_remision VALUES ('2', 'Ausencia del Profesional');
INSERT INTO hc_motivos_remision VALUES ('3', 'Falta de Insumos');
INSERT INTO hc_motivos_remision VALUES ('4', 'Falta de Equipos');
INSERT INTO hc_motivos_remision VALUES ('5', 'Falta de Camas');
INSERT INTO hc_motivos_remision VALUES ('6', 'Cese de Actividades');
INSERT INTO hc_motivos_remision VALUES ('7', 'Emergencia Sanitaria');


--
-- QUERYS REQUERIDOS PARA EL MODULO CERTIFICADO DE DEFUNCION
-- TIZZIANO PEREA O.
-- CREACION TABLA hc_motivo_defuncion
--

CREATE TABLE hc_motivo_defuncion (
    motivo_defuncion_id character varying(2) NOT NULL,
    descripcion character varying(40) NOT NULL
);


INSERT INTO hc_motivo_defuncion VALUES ('1', 'En la Institucion');
INSERT INTO hc_motivo_defuncion VALUES ('2', 'Centro / Puesto de Salud');
INSERT INTO hc_motivo_defuncion VALUES ('3', 'Casa / Domicilio');
INSERT INTO hc_motivo_defuncion VALUES ('4', 'Lugar de Trabajo');
INSERT INTO hc_motivo_defuncion VALUES ('5', 'Via Publica');
INSERT INTO hc_motivo_defuncion VALUES ('6', 'Sin Informacion');

ALTER TABLE hc_motivo_defuncion
    ADD PRIMARY KEY (motivo_defuncion_id);


COMMENT ON TABLE hc_motivo_defuncion IS 'Tabla que guarda los motivos de las defunciones';

COMMENT ON COLUMN hc_motivo_defuncion.motivo_defuncion_id IS 'Llave';

COMMENT ON COLUMN hc_motivo_defuncion.descripcion IS 'Descripcion de la defuncion';


--
-- QUERYS REQUERIDOS PARA EL MODULO CERTIFICADO DE DEFUNCION
-- TIZZIANO PEREA O.
-- CREACION TABLA hc_tipo_certificado
--

CREATE TABLE hc_tipo_certificado (
    tipo_certificado_id character varying(2) NOT NULL,
    descripcion character varying(40) NOT NULL,
    indice_orden smallint DEFAULT 0 NOT NULL
);


INSERT INTO hc_tipo_certificado VALUES ('1', 'Medico Tratante', 0);
INSERT INTO hc_tipo_certificado VALUES ('2', 'Medico no Tratante', 1);

ALTER TABLE hc_tipo_certificado
    ADD PRIMARY KEY (tipo_certificado_id);

COMMENT ON TABLE hc_tipo_certificado IS 'Tabla que contiene los tipos de certificados de defuncion';


--
-- QUERYS REQUERIDOS PARA EL MODULO CERTIFICADO DE DEFUNCION
-- TIZZIANO PEREA O.
-- CREACION TABLA hc_conducta_diagnosticos_defuncion
--

CREATE TABLE hc_conducta_diagnosticos_defuncion (
    ingreso integer NOT NULL,
    evolucion_id integer NOT NULL,
    diagnostico_defuncion_id character varying(6) NOT NULL,
    sw_principal character varying(1),
    diagnostico_muerte character varying(256)
);


ALTER TABLE hc_conducta_diagnosticos_defuncion
    ADD PRIMARY KEY (ingreso, evolucion_id, diagnostico_defuncion_id);

ALTER TABLE hc_conducta_diagnosticos_defuncion
    ADD FOREIGN KEY (ingreso) REFERENCES ingresos(ingreso) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE hc_conducta_diagnosticos_defuncion
    ADD FOREIGN KEY (evolucion_id) REFERENCES hc_evoluciones(evolucion_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE hc_conducta_diagnosticos_defuncion
    ADD FOREIGN KEY (diagnostico_defuncion_id) REFERENCES diagnosticos(diagnostico_id) ON UPDATE CASCADE ON DELETE RESTRICT;

COMMENT ON TABLE hc_conducta_diagnosticos_defuncion IS 'Guarda los diagnosticos causales de muerte';

COMMENT ON COLUMN hc_conducta_diagnosticos_defuncion.sw_principal IS 'Elige el diagnostico principal';

COMMENT ON COLUMN hc_conducta_diagnosticos_defuncion.diagnostico_muerte IS 'Muestra el tiempo entre la aparicion del diagnostico y la muerte';


--
-- QUERYS REQUERIDOS PARA EL MODULO CERTIFICADO DE DEFUNCION
-- TIZZIANO PEREA O.
-- CREACION TABLA hc_conducta_defuncion
--

CREATE TABLE hc_conducta_defuncion (
    ingreso integer NOT NULL,
    evolucion_id integer NOT NULL,
    fecha timestamp without time zone NOT NULL,
    tipo_certificado_id character varying(2)
);


ALTER TABLE hc_conducta_defuncion
    ADD PRIMARY KEY (ingreso, evolucion_id);


ALTER TABLE hc_conducta_defuncion
    ADD FOREIGN KEY (ingreso) REFERENCES ingresos(ingreso) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE hc_conducta_defuncion
    ADD FOREIGN KEY (evolucion_id) REFERENCES hc_evoluciones(evolucion_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE hc_conducta_defuncion
    ADD FOREIGN KEY (tipo_certificado_id) REFERENCES hc_tipo_certificado(tipo_certificado_id) ON UPDATE CASCADE ON DELETE RESTRICT;

COMMENT ON TABLE hc_conducta_defuncion IS 'conductas de defunciones';


--
-- QUERYS REQUERIDOS PARA EL MODULO CERTIFICADO DE DEFUNCION
-- TIZZIANO PEREA O.
-- CREACION TABLA hc_conducta_defuncion_motivo
--

CREATE TABLE hc_conducta_defuncion_motivo (
    ingreso integer NOT NULL,
    evolucion_id integer NOT NULL,
    motivo_defuncion_id character varying(2) NOT NULL
);

ALTER TABLE hc_conducta_defuncion_motivo
    ADD PRIMARY KEY (ingreso, evolucion_id, motivo_defuncion_id);

ALTER TABLE hc_conducta_defuncion_motivo
    ADD FOREIGN KEY (motivo_defuncion_id) REFERENCES hc_motivo_defuncion(motivo_defuncion_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE hc_conducta_defuncion_motivo
    ADD FOREIGN KEY (ingreso, evolucion_id) REFERENCES hc_conducta_defuncion(ingreso, evolucion_id) ON UPDATE CASCADE ON DELETE RESTRICT;

COMMENT ON TABLE hc_conducta_defuncion_motivo IS 'Motivos de defuncion';


--
-- QUERYS REQUERIDOS PARA EL MODULO CERTIFICADO DE DEFUNCION
-- TIZZIANO PEREA O.
-- CREACION TABLA hc_conducta_defuncion_mujeres
--

CREATE TABLE hc_conducta_defuncion_mujeres (
    ingreso integer NOT NULL,
    evolucion_id integer NOT NULL,
    sw_embarazada character varying(1),
    sw_semanas_embarazo character varying(1),
    sw_meses_embarazo character varying(1)
);

ALTER TABLE hc_conducta_defuncion_mujeres
    ADD PRIMARY KEY (ingreso, evolucion_id);

ALTER TABLE hc_conducta_defuncion_mujeres
    ADD FOREIGN KEY (ingreso, evolucion_id) REFERENCES hc_conducta_defuncion(ingreso, evolucion_id) ON UPDATE CASCADE ON DELETE RESTRICT;

COMMENT ON TABLE hc_conducta_defuncion_mujeres IS 'Informacion de estado de la mujer al momento de defuncion';

