ALTER TABLE "hc_notas_operatorias_cirugias" DROP COLUMN  "tipo_id_paciente";
ALTER TABLE "hc_notas_operatorias_cirugias" DROP COLUMN  "paciente_id";
ALTER TABLE "hc_notas_operatorias_cirugias" ADD COLUMN   "evolucion_id" integer;
ALTER TABLE "hc_notas_operatorias_cirugias" ALTER COLUMN "evolucion_id" SET NOT NULL;
ALTER TABLE "hc_notas_operatorias_cirugias" ADD FOREIGN KEY (evolucion_id) REFERENCES hc_evoluciones(evolucion_id) ON UPDATE CASCADE ON DELETE RESTRICT;


--CLAUDIA OK ENVIADO A BOGOTA 19/NOV/2004 - ENVIADO TULUA 22/NOV/2004

--DATOS A INSERTAR

INSERT INTO lab_tipos_plantillas (lab_plantilla_id, descripcion)
VALUES (5, 'Plantilla DATALAB');


INSERT INTO lab_examenes (lab_examen_id, lab_plantilla_id, nombre_examen)
VALUES (60000, '5', 'DATALAB');



--TABLAS DE LA INTERFACE

--TABLA 1

CREATE TABLE interface_datalab_bacteriologo (
    usuario_id integer NOT NULL,
    equivalencia character varying(10) NOT NULL
);


ALTER TABLE ONLY interface_datalab_bacteriologo
    ADD CONSTRAINT "$1" FOREIGN KEY (usuario_id) REFERENCES profesionales_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;


--TABLA 2


CREATE TABLE interface_datalab_control (
    interface_datalab_control_id serial NOT NULL,
    numero_orden_id integer NOT NULL,
    numero_cumplimiento smallint NOT NULL,
    fecha_cumplimiento date NOT NULL,
    departamento character varying(6) NOT NULL,
    tipo_id_paciente character varying(3) NOT NULL,
    paciente_id character varying(32) NOT NULL
);



ALTER TABLE ONLY interface_datalab_control
    ADD CONSTRAINT interface_datalab_control_pkey PRIMARY KEY (interface_datalab_control_id);



ALTER TABLE ONLY interface_datalab_control
    ADD CONSTRAINT "$1" FOREIGN KEY (numero_orden_id, numero_cumplimiento, fecha_cumplimiento, departamento) REFERENCES os_cumplimientos_detalle(numero_orden_id, numero_cumplimiento, fecha_cumplimiento, departamento) ON UPDATE CASCADE ON DELETE RESTRICT;


--TABLA 3

CREATE TABLE interface_datalab_medico (
    usuario_id integer NOT NULL,
    equivalencia character varying(60) NOT NULL
);


--TABLA 4

CREATE TABLE interface_datalab_pagador (
    plan_id integer NOT NULL,
    num_contrato character varying(20) NOT NULL,
    equivalencia character varying(60) NOT NULL
);


ALTER TABLE ONLY interface_datalab_pagador
    ADD CONSTRAINT "$1" FOREIGN KEY (plan_id) REFERENCES planes(plan_id) ON UPDATE CASCADE ON DELETE RESTRICT;


--TABLA 5

CREATE TABLE interface_datalab_resultados (
    numero_orden_id character varying(60),
    codigo_seccion integer,
    nombre_seccion character varying(60),
    codigo_examen character varying(15),
    codigo_datalab integer,
    abreviatura character varying(10),
    nombre_examen character varying(60),
    resultado character varying(15),
    unidades character varying(10),
    normal_minima character varying(10),
    normal_maxima character varying(10),
    patologico character(1),
    comentario text,
    muestra_microbiologia character varying(60),
    microorganismo character varying(60),
    antibiotico character varying(60),
    resultado_antibiotico character varying(15),
    bacteriologo character varying(10),
    fecha_resultado timestamp without time zone,
    interface_datalab_control_id integer
);


CREATE TRIGGER interface_datalab_trigger
    BEFORE INSERT OR DELETE OR UPDATE ON interface_datalab_resultados
    FOR EACH ROW
    EXECUTE PROCEDURE interface_datalab_trigger_resultado();


COMMENT ON COLUMN interface_datalab_resultados.numero_orden_id IS 'ORDEN6 en DataLab';


COMMENT ON COLUMN interface_datalab_resultados.nombre_seccion IS 'Titulo del Examen';


COMMENT ON COLUMN interface_datalab_resultados.codigo_examen IS 'Codigo del examen cups';


COMMENT ON COLUMN interface_datalab_resultados.codigo_datalab IS 'Codigo de DataLab';


COMMENT ON COLUMN interface_datalab_resultados.nombre_examen IS 'Nombre del Examen';


COMMENT ON COLUMN interface_datalab_resultados.unidades IS 'unidades';


COMMENT ON COLUMN interface_datalab_resultados.normal_minima IS 'rango minimo';


COMMENT ON COLUMN interface_datalab_resultados.normal_maxima IS 'rango maximo';


COMMENT ON COLUMN interface_datalab_resultados.comentario IS 'Texto de ';


COMMENT ON COLUMN interface_datalab_resultados.bacteriologo IS 'Bateriologo';


COMMENT ON COLUMN interface_datalab_resultados.fecha_resultado IS 'Fecha del resultado';


--TABLA 6

CREATE TABLE interface_datalab_servicio (
    servicio character varying(2) NOT NULL,
    equivalencia character varying(60) NOT NULL
);


ALTER TABLE ONLY interface_datalab_servicio
    ADD CONSTRAINT "$1" FOREIGN KEY (servicio) REFERENCES servicios(servicio) ON UPDATE CASCADE ON DELETE RESTRICT;


--TABLA 7


CREATE TABLE interface_datalab_sexo (
    sexo_id character(1) NOT NULL,
    equivalencia character(1) NOT NULL
);


ALTER TABLE ONLY interface_datalab_sexo
    ADD CONSTRAINT "$1" FOREIGN KEY (sexo_id) REFERENCES tipo_sexo(sexo_id) ON UPDATE CASCADE ON DELETE RESTRICT;


--TABLA 8


CREATE TABLE interface_datalab_solicitudes (
    hc character varying(20),
    apellido character varying(50),
    nombre character varying(50),
    sexo character(1),
    fecha_nacimiento date,
    hc1 character varying(60),
    hc2 character varying(60),
    hc3 character varying(60),
    hc4 character varying(60),
    hc5 character varying(60),
    hc6 character varying(60),
    hc7 character varying(60),
    hc8 character varying(60),
    hc9 character varying(60),
    hc10 character varying(60),
    fecha_hora_envio timestamp without time zone,
    orden1 character varying(60),
    orden2 character varying(60),
    orden3 character varying(60),
    orden4 character varying(60),
    orden5 character varying(60),
    orden6 character varying(60),
    orden7 character varying(60),
    orden8 character varying(60),
    orden9 character varying(60),
    orden10 character varying(60),
    tipo_orden character(1),
    ordcomentario character varying(60),
    codigo_examen character varying(15),
    medico_solicitante character varying(60)
);


COMMENT ON COLUMN interface_datalab_solicitudes.hc IS 'No. Historia Clinica';


COMMENT ON COLUMN interface_datalab_solicitudes.sexo IS '1= hombre - 2=mujer';


COMMENT ON COLUMN interface_datalab_solicitudes.fecha_nacimiento IS 'DD/MM/AAAA';


COMMENT ON COLUMN interface_datalab_solicitudes.hc1 IS 'Telefono (texto Libre)';


COMMENT ON COLUMN interface_datalab_solicitudes.hc2 IS 'Tarifa (Código de acuerdo a DataLab)';


COMMENT ON COLUMN interface_datalab_solicitudes.hc3 IS 'Nada';


COMMENT ON COLUMN interface_datalab_solicitudes.hc4 IS 'Nada';


COMMENT ON COLUMN interface_datalab_solicitudes.hc5 IS 'Nada';


COMMENT ON COLUMN interface_datalab_solicitudes.hc6 IS 'Nada';


COMMENT ON COLUMN interface_datalab_solicitudes.hc7 IS 'Nada';


COMMENT ON COLUMN interface_datalab_solicitudes.hc8 IS 'Nada';


COMMENT ON COLUMN interface_datalab_solicitudes.hc9 IS 'Nada';


COMMENT ON COLUMN interface_datalab_solicitudes.hc10 IS 'Nada';


COMMENT ON COLUMN interface_datalab_solicitudes.fecha_hora_envio IS 'DD/MM/AAAA HH:MM';


COMMENT ON COLUMN interface_datalab_solicitudes.orden1 IS 'PAGADOR  (Código de acuerdo a DataLab)';


COMMENT ON COLUMN interface_datalab_solicitudes.orden2 IS 'TURNO  (Código de acuerdo a DataLab)';


COMMENT ON COLUMN interface_datalab_solicitudes.orden3 IS 'SERVICIO  (Código de acuerdo a DataLab)';


COMMENT ON COLUMN interface_datalab_solicitudes.orden4 IS 'MEDICO (Código de acuerdo a DataLab)';


COMMENT ON COLUMN interface_datalab_solicitudes.orden5 IS 'CAMA (Texto libre)';


COMMENT ON COLUMN interface_datalab_solicitudes.orden6 IS 'numero_orden_id';


COMMENT ON COLUMN interface_datalab_solicitudes.orden7 IS 'CONCEPTO';


COMMENT ON COLUMN interface_datalab_solicitudes.tipo_orden IS 'R=rutina    U=urgencias';


COMMENT ON COLUMN interface_datalab_solicitudes.ordcomentario IS 'Comentario a la orden';


COMMENT ON COLUMN interface_datalab_solicitudes.codigo_examen IS 'Codigo del examen a realizar cups';


--TABLA 9

CREATE TABLE interface_datalab_tarifario (
    tarifario_id character varying(4) NOT NULL,
    equivalencia character varying(60) NOT NULL
);


ALTER TABLE ONLY interface_datalab_tarifario
    ADD CONSTRAINT "$1" FOREIGN KEY (tarifario_id) REFERENCES tarifarios(tarifario_id) ON UPDATE CASCADE ON DELETE RESTRICT;


--TRIGGER alexander

create function "interface_datalab_trigger_resultado" () returns trigger as '

DECLARE
var_datos RECORD;
var_insertar INTEGER;
var_user_bacterilogo INTEGER;
var_resultado_id INTEGER;
BEGIN
IF TG_OP='UPDATE' OR TG_OP = 'DELETE'  THEN
 RAISE EXCEPTION 'No es permitido borrar o actualizar resultados ingresados';
END IF;
NEW.interface_datalab_control_id=to_number(New.numero_orden_id,'99999999999999999999');


SELECT INTO var_datos
interface_datalab_control_id, numero_orden_id,
numero_cumplimiento, fecha_cumplimiento,
departamento, tipo_id_paciente, paciente_id
FROM interface_datalab_control
WHERE interface_datalab_control_id=NEW.interface_datalab_control_id;


var_insertar := (SELECT COUNT(*) FROM hc_resultados_sistema WHERE numero_orden_id = var_datos.numero_orden_id);

IF var_insertar = 0 THEN
var_user_bacterilogo := (SELECT usuario_id FROM
                interface_datalab_bacteriologo  WHERE
                equivalencia = NEW.bacteriologo);
IF var_user_bacterilogo ISNULL OR NOT (var_user_bacterilogo > 0) THEN
var_user_bacterilogo:=2;
END IF;


var_resultado_id:=(SELECT nextval('hc_resultados_resultado_id_seq'));


INSERT INTO hc_resultados (resultado_id, fecha_registro, usuario_id, tipo_id_paciente, paciente_id,
cargo, fecha_realizado, os_tipo_resultado, observacion_prestacion_servicio)
VALUES(var_resultado_id, now(), var_user_bacterilogo, var_datos.tipo_id_paciente,
var_datos.paciente_id,NEW.codigo_examen,NEW.fecha_resultado,
                 'APD',NEW.comentario);

INSERT INTO hc_resultados_sistema
                                                        (resultado_id, numero_orden_id, usuario_id_profesional)
                                                        VALUES  (var_resultado_id,var_datos.numero_orden_id,
                                                         var_user_bacterilogo);

UPDATE os_maestro SET sw_estado = '4'
                                WHERE numero_orden_id = var_datos.numero_orden_id;

INSERT INTO hc_apoyod_resultados_detalles        (lab_examen_id, resultado_id, resultado, sw_alerta)
VALUES  (50000,var_resultado_id, NEW.numero_orden_id, NEW.patologico);


END IF;
RETURN NEW;
END;'

language "plpgsql"
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER




