ALTER TABLE "pacientes_urgencias" ADD COLUMN "historia_clinica_tipo_cierre_id" integer;
ALTER TABLE ONLY pacientes_urgencias
    ADD FOREIGN KEY (historia_clinica_tipo_cierre_id) REFERENCES historias_clinicas_tipos_cierres(historia_clinica_tipo_cierre_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE "hc_notas_operatorias_procedimientos" DROP CONSTRAINT "$1";


ALTER TABLE "hc_conducta_remision" ADD COLUMN "observacion_remision" text;


--CLAUDIA DATALAB ok enviado tulua 01/dic/2004

--borrado y creacion de la tabla de resultados
DROP TABLE "interface_datalab_solicitudes";

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
    orden1_char character varying(60),
    orden2_char character varying(60),
    orden3_char character varying(60),
    orden4_char character varying(60)
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



--borrado y creacion de la tabla de resultados
DROP TABLE "interface_datalab_resultados";

CREATE TABLE interface_datalab_resultados (
    numero_orden_id integer NOT NULL,
    codigo_seccion integer,
    nombre_seccion character varying(60),
    codigo_examen character varying(15),
    codigo_datalab integer,
    codigo_perfil integer,
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
    bacteriologo_nombre character varying(60),
    fecha_resultado timestamp without time zone
);


COMMENT ON COLUMN interface_datalab_resultados.numero_orden_id IS 'Codigo SIIS enviado en la solicitud en el campo orden6 LLAVE PRIMARIA para SIIS';

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


--alteracion de la tabla de control
ALTER TABLE "interface_datalab_control" DROP COLUMN "numero_orden_id";

--creacion de la tabla de control_detalle.

CREATE TABLE interface_datalab_control_detalle (
    interface_datalab_control_id integer NOT NULL,
    numero_orden_id integer NOT NULL,
    codigo_examen character varying(15) NOT NULL
);


ALTER TABLE ONLY interface_datalab_control_detalle
    ADD CONSTRAINT interface_datalab_control_detalle_pkey PRIMARY KEY (interface_datalab_control_id, numero_orden_id);

ALTER TABLE ONLY interface_datalab_control_detalle
    ADD FOREIGN KEY (interface_datalab_control_id) REFERENCES interface_datalab_control(interface_datalab_control_id) ON UPDATE CASCADE ON DELETE RESTRICT;


--JORGE
DROP TABLE planes_honorarios;
DROP TABLE excepciones_honorarios;

DROP FUNCTION borrar_excepciones_honorarios();
DROP FUNCTION borrar_excepciones_honorarios_mod(text, text, text, text);
DROP FUNCTION excepciones_honorario(text, text, text);
--FIN JORGE



