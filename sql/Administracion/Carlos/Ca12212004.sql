CREATE TABLE soporte_solicitud (
    soporte_solicitud_id serial NOT NULL,
    tipo_id_tercero character varying(3) NOT NULL,
    id character varying(20) NOT NULL,
    fecha_solicitud timestamp without time zone NOT NULL,
    nombre_solicitante character varying(60) NOT NULL,
    "version" character varying(20)
);


ALTER TABLE ONLY soporte_solicitud
    ADD CONSTRAINT soporte_solicitud_pkey PRIMARY KEY (soporte_solicitud_id);

CREATE TABLE soporte_solicitud_detalle (
    soporte_solicitud_id integer NOT NULL,
    soporte_solicitud_detalle_id serial NOT NULL,
    modulo character varying(64) NOT NULL,
    observacion text NOT NULL,
    detalle_error text,
    ruta_acceso text,
    tipo_ajuste_id character(1) NOT NULL,
    tipo_prioridad_id character(1) NOT NULL
);

ALTER TABLE ONLY soporte_solicitud_detalle
    ADD CONSTRAINT soporte_solicitud_detalle_pkey PRIMARY KEY (soporte_solicitud_id, soporte_solicitud_detalle_id);

ALTER TABLE ONLY soporte_solicitud_detalle
    ADD CONSTRAINT "$1" FOREIGN KEY (soporte_solicitud_id) REFERENCES soporte_solicitud(soporte_solicitud_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY soporte_solicitud_detalle
    ADD CONSTRAINT "$2" FOREIGN KEY (tipo_prioridad_id) REFERENCES soporte_tipos_prioridades(tipo_prioridad_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY soporte_solicitud_detalle
    ADD CONSTRAINT "$3" FOREIGN KEY (tipo_ajuste_id) REFERENCES soporte_tipos_ajustes(tipo_ajuste_id) ON UPDATE CASCADE ON DELETE RESTRICT;
    
CREATE TABLE soporte_tipos_ajustes (
    descripcion character varying(40) NOT NULL,
    tipo_ajuste_id character(1) NOT NULL
);

ALTER TABLE ONLY soporte_tipos_ajustes
    ADD CONSTRAINT soporte_tipos_ajustes_pkey PRIMARY KEY (tipo_ajuste_id);

CREATE TABLE soporte_tipos_prioridades (
    descripcion character varying(20) NOT NULL,
    tipo_prioridad_id character(1) NOT NULL
);

ALTER TABLE ONLY soporte_tipos_prioridades
    ADD CONSTRAINT soporte_tipos_prioridades_pkey PRIMARY KEY (tipo_prioridad_id);

INSERT INTO soporte_tipos_prioridades VALUES ('ALTA', 'A');
INSERT INTO soporte_tipos_prioridades VALUES ('MEDIA', 'M');
INSERT INTO soporte_tipos_prioridades VALUES ('BAJA', 'B');

