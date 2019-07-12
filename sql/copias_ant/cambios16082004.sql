
ALTER TABLE ONLY os_maestro
    ADD CONSTRAINT os_maestro_pkey PRIMARY KEY (numero_orden_id);
    

ALTER TABLE ONLY os_laboratorios
    ADD CONSTRAINT "$1" FOREIGN KEY (numero_orden_id) REFERENCES os_maestro(numero_orden_id) ON UPDATE CASCADE ON DELETE RESTRICT;



ALTER TABLE ONLY os_internas
    ADD CONSTRAINT "$3" FOREIGN KEY (numero_orden_id) REFERENCES os_maestro(numero_orden_id) ON UPDATE CASCADE ON DELETE RESTRICT;



ALTER TABLE ONLY os_externas
    ADD CONSTRAINT "$3" FOREIGN KEY (numero_orden_id) REFERENCES os_maestro(numero_orden_id) ON UPDATE CASCADE ON DELETE RESTRICT;



ALTER TABLE ONLY hc_resultados_manuales
    ADD CONSTRAINT "$2" FOREIGN KEY (numero_orden_id) REFERENCES os_maestro(numero_orden_id) ON UPDATE CASCADE ON DELETE RESTRICT;



ALTER TABLE ONLY hc_resultados_sistema
    ADD CONSTRAINT "$2" FOREIGN KEY (numero_orden_id) REFERENCES os_maestro(numero_orden_id) ON UPDATE CASCADE ON DELETE RESTRICT;



ALTER TABLE ONLY qx_programaciones_ordenes
    ADD CONSTRAINT "$2" FOREIGN KEY (numero_orden_id) REFERENCES os_maestro(numero_orden_id) ON UPDATE CASCADE ON DELETE RESTRICT;



ALTER TABLE ONLY os_cumplimientos_detalle
    ADD CONSTRAINT "$2" FOREIGN KEY (numero_orden_id) REFERENCES os_maestro(numero_orden_id) ON UPDATE CASCADE ON DELETE RESTRICT;



ALTER TABLE ONLY os_anuladas
    ADD CONSTRAINT "$3" FOREIGN KEY (numero_orden_id) REFERENCES os_maestro(numero_orden_id) ON UPDATE CASCADE ON DELETE RESTRICT;



ALTER TABLE ONLY os_maestro_cargos
    ADD CONSTRAINT "$1" FOREIGN KEY (numero_orden_id) REFERENCES os_maestro(numero_orden_id) ON UPDATE CASCADE ON DELETE RESTRICT;



ALTER TABLE ONLY os_cruce_citas
    ADD CONSTRAINT "$1" FOREIGN KEY (numero_orden_id) REFERENCES os_maestro(numero_orden_id) ON UPDATE CASCADE ON DELETE RESTRICT;



ALTER TABLE ONLY os_imagenes_citas
    ADD CONSTRAINT "$2" FOREIGN KEY (numero_orden_id) REFERENCES os_maestro(numero_orden_id) ON UPDATE CASCADE ON DELETE RESTRICT;


CREATE TABLE os_imagenes_citas (
    os_imagen_cita_id serial NOT NULL,
    numero_orden_id integer NOT NULL,
    tipo_profesional_id character varying(3) NOT NULL,
    profesional_id character varying(32) NOT NULL,
    equipo_imagen_id integer NOT NULL,
    fecha_hora_cita timestamp without time zone NOT NULL,
    duracion time without time zone NOT NULL,
    estado character varying(1) DEFAULT 0 NOT NULL,
    usuario_id integer NOT NULL,
    fecha_registro timestamp without time zone NOT NULL
);


ALTER TABLE ONLY os_imagenes_citas
ADD CONSTRAINT os_imagenes_citas_pkey PRIMARY KEY (os_imagen_cita_id);

ALTER TABLE ONLY os_imagenes_citas
ADD CONSTRAINT "$1" FOREIGN KEY (tipo_profesional_id, profesional_id) REFERENCES profesionales(tipo_id_tercero, tercero_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY os_imagenes_citas
ADD CONSTRAINT "$3" FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY os_imagenes_citas
ADD CONSTRAINT "$4" FOREIGN KEY (equipo_imagen_id) REFERENCES os_imagenes_equipos(equipo_imagen_id) ON UPDATE CASCADE ON DELETE RESTRICT;



CREATE TABLE os_imagenes_equipos (
    equipo_imagen_id serial NOT NULL,
    descripcion character varying(40) NOT NULL,
    tipo_equipo_imagen_id character varying(2) NOT NULL,
    estado character(1) DEFAULT 1 NOT NULL
);

ALTER TABLE ONLY os_imagenes_equipos
ADD CONSTRAINT os_imagenes_equipos_pkey PRIMARY KEY (equipo_imagen_id);


ALTER TABLE ONLY os_imagenes_equipos
ADD CONSTRAINT "$1" FOREIGN KEY (tipo_equipo_imagen_id) REFERENCES os_imagenes_tipo_equipos(tipo_equipo_imagen_id) ON UPDATE CASCADE ON DELETE RESTRICT;



CREATE TABLE os_imagenes_equipos_programacion (
    equipo_imagen_id integer NOT NULL,
    fechahora_inicio_turno timestamp without time zone NOT NULL,
    fechahora_fin_turno timestamp without time zone NOT NULL,
    estado character(1) DEFAULT 1 NOT NULL
);


ALTER TABLE ONLY os_imagenes_equipos_programacion
ADD CONSTRAINT os_imagenes_equipos_programacion_equipo_imagen_id_key UNIQUE (equipo_imagen_id, fechahora_inicio_turno, fechahora_fin_turno);


ALTER TABLE ONLY os_imagenes_equipos_programacion
ADD CONSTRAINT "$1" FOREIGN KEY (equipo_imagen_id) REFERENCES os_imagenes_equipos(equipo_imagen_id) ON UPDATE CASCADE ON DELETE RESTRICT;



CREATE TABLE os_imagenes_tipo_equipos (
    tipo_equipo_imagen_id character varying(2) NOT NULL,
    descripcion character varying(60) NOT NULL
);


ALTER TABLE ONLY os_imagenes_tipo_equipos
ADD CONSTRAINT os_imagenes_tipo_equipos_pkey PRIMARY KEY (tipo_equipo_imagen_id);


CREATE TABLE os_imagenes_turnos_profesionales (
    tipo_profesional_id character varying(3) NOT NULL,
    profesional_id character varying NOT NULL,
    fechahora_inicio_turno timestamp without time zone NOT NULL,
    fechahora_fin_turno timestamp without time zone NOT NULL,
    estado character(1) DEFAULT 1 NOT NULL
);

ALTER TABLE ONLY os_imagenes_turnos_profesionales
ADD CONSTRAINT os_imagenes_turnos_profesionales_tipo_profesional_id_key UNIQUE (tipo_profesional_id, profesional_id, fechahora_inicio_turno, fechahora_fin_turno);



ALTER TABLE ONLY os_imagenes_turnos_profesionales
ADD CONSTRAINT "$1" FOREIGN KEY (tipo_profesional_id, profesional_id) REFERENCES profesionales(tipo_id_tercero, tercero_id) ON UPDATE CASCADE ON DELETE RESTRICT;



INSERT INTO os_imagenes_citas VALUES (1, 435, 'CC', '122', 1, '2004-08-16 09:00:00', '02:00:00', '0', 45, '2004-08-12 14:34:12');
INSERT INTO os_imagenes_equipos VALUES (1, 'ECOGRAFO1', '01', '1');
INSERT INTO os_imagenes_equipos VALUES (5, 'MAMOGRAFO2', '02', '1');
INSERT INTO os_imagenes_equipos VALUES (6, 'ESCANOGRAFO1', '03', '1');
INSERT INTO os_imagenes_equipos VALUES (7, 'ESCANOGRAFO2', '03', '1');
INSERT INTO os_imagenes_equipos VALUES (4, 'MAMOGRAFO1', '02', '1');
INSERT INTO os_imagenes_equipos VALUES (8, 'TAC1', '03', '1');
INSERT INTO os_imagenes_equipos VALUES (9, 'TAC2', '03', '1');
INSERT INTO os_imagenes_equipos VALUES (10, 'RX1', '04', '1');
INSERT INTO os_imagenes_equipos VALUES (3, 'ECOGRAFO3', '01', '1');
INSERT INTO os_imagenes_equipos VALUES (2, 'ECOGRAFO2', '01', '1');
INSERT INTO os_imagenes_equipos_programacion VALUES (1, '2004-08-16 08:00:00', '2004-08-16 12:00:00', '1');
INSERT INTO os_imagenes_equipos_programacion VALUES (1, '2004-08-16 14:00:00', '2004-08-16 18:00:00', '1');
INSERT INTO os_imagenes_equipos_programacion VALUES (2, '2004-08-16 08:00:00', '2004-08-16 12:00:00', '1');
INSERT INTO os_imagenes_equipos_programacion VALUES (2, '2004-08-16 14:00:00', '2004-08-18 14:00:00', '1');
INSERT INTO os_imagenes_tipo_equipos VALUES ('01', 'ECOGRAFO');
INSERT INTO os_imagenes_tipo_equipos VALUES ('02', 'MAMOGRAFO');
INSERT INTO os_imagenes_tipo_equipos VALUES ('03', 'TAC');
INSERT INTO os_imagenes_tipo_equipos VALUES ('04', 'RX');
INSERT INTO system_modulos_variables VALUES ('QXEjecucion', 'app', 'InicioTurnoSalaImagen', '08:00');
INSERT INTO system_modulos_variables VALUES ('QXEjecucion', 'app', 'DuracionTurnoSalaImagen', '10');
INSERT INTO system_modulos_variables VALUES ('QXEjecucion', 'app', 'RangoTurnosEquiposImagen', '15');

CREATE OR REPLACE FUNCTION disponibilidad_equipo_imagen(int4, timestamp, time)
RETURNS bool AS
'DECLARE
var_fecha INTEGER;
BEGIN
var_fecha := (SELECT COUNT(*) FROM os_imagenes_equipos_programacion a
WHERE a.equipo_imagen_id=$1 AND a.estado = \'1\' AND
$2 >= a.fechahora_inicio_turno AND $2 < a.fechahora_fin_turno AND
($2 + $3) >= a.fechahora_inicio_turno AND ($2 + $3) <= a.fechahora_fin_turno);
IF var_fecha < 1 THEN
return false;
ELSE
return true;
END IF;
END;'
LANGUAGE 'plpgsql' VOLATILE;


CREATE OR REPLACE FUNCTION public.ocupacion_equipo_imagen(int4, timestamp, time)
RETURNS bool AS
'DECLARE
var_fecha INTEGER;
BEGIN
var_fecha := (SELECT COUNT(*) FROM os_imagenes_citas a
WHERE a.equipo_imagen_id=$1 AND a.estado<>\'2\' AND
$2 >= a.fecha_hora_cita AND $2 < ((a.fecha_hora_cita + a.duracion)+$3));
IF var_fecha < 1 THEN
return false;
ELSE
return true;
END IF;
END;'
LANGUAGE 'plpgsql' VOLATILE;

ALTER TABLE os_imagenes_citas ALTER COLUMN profesional_id DROP NOT NULL;
ALTER TABLE os_imagenes_citas ALTER COLUMN tipo_profesional_id DROP NOT NULL;


/*********************NOTA1********************************
		NOTA: agosto 11 de agosto 1: p.m.
    USUARIO: Claudia Zuñiga
		DETALLE: se altera el campo resultado en la tabla hc_apoyod_resultados_detalles para integrar la plantilla 3 de imagenes
		ESTADO: SIN EJECUTAR EN SIIS REAL
		en la tabla hc_apoyod_resultados_detalles modificar en el campo resultado el tipo de dato, ya no es
		character varying si no que queda tipo text.
		SCRIPT:
*/
ALTER TABLE hc_apoyod_resultados_detalles DROP COLUMN resultado;
ALTER TABLE hc_apoyod_resultados_detalles ADD COLUMN resultado text;

/*
********************NOTA2********************************
		NOTA: agosto 13 de agosto 8:51 a.m.
		USUARIO: Claudia Zuñiga
		DETALLE: creacion de una nueva tabla para la observacion adicional al resultado de los apoyos diagnosticos.
		ESTADO: SIN EJECUTAR EN SIIS REAL
		SCRIPT:
*/

		CREATE TABLE hc_resultados_observaciones_adicionales (
				observacion_resultado_id serial NOT NULL,
				resultado_id integer NOT NULL,
				usuario_id integer NOT NULL,
				observacion_adicional text NOT NULL,
				fecha_registro_observacion timestamp without time zone NOT NULL
		);


		ALTER TABLE ONLY hc_resultados_observaciones_adicionales
				ADD CONSTRAINT hc_resultados_observaciones_adicionales_pkey PRIMARY KEY (observacion_resultado_id);

		ALTER TABLE ONLY hc_resultados_observaciones_adicionales
				ADD CONSTRAINT "$1" FOREIGN KEY (resultado_id) REFERENCES hc_resultados(resultado_id) ON UPDATE CASCADE ON DELETE RESTRICT;

		ALTER TABLE ONLY hc_resultados_observaciones_adicionales
				ADD CONSTRAINT "$2" FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;


/*
********************NOTA3********************************
		NOTA: agosto 11 de agosto 2:57 p.m.
		USUARIO: Claudia Zuñiga
		DETALLE: se elimina una relacion que estaba repetida en la tabla user_permisos_os_listatra_apoyod_detalle_profesionales
		ESTADO: SIN EJECUTAR EN SIIS REAL
		en la tabla user_permisos_os_listatra_apoyod_detalle_profesionales eliminar la relacion que esta doble.
		SCRIPT:
*/
ALTER TABLE user_permisos_os_listatra_apoyod_detalle_profesionales DROP CONSTRAINT "$3";




ALTER TABLE public.profesionales_usuarios
  ADD CONSTRAINT profesionales_usuarios_usuario_id_key UNIQUE(usuario_id);
