CREATE TABLE qx_tipos_anestesia(
    qx_tipo_anestesia_id character varying(2) NOT NULL,
		descripcion  character varying(60) NOT NULL
);
ALTER TABLE ONLY qx_tipos_anestesia
 ADD CONSTRAINT qx_tipos_anestesia_pkey PRIMARY KEY (qx_tipo_anestesia_id);
INSERT INTO qx_tipos_anestesia VALUES ('01', 'LOCAL');
INSERT INTO qx_tipos_anestesia VALUES ('02', 'RAQUIDEA');
INSERT INTO qx_tipos_anestesia VALUES ('03', 'GENERAL');
INSERT INTO qx_tipos_anestesia VALUES ('04', 'BLOQUEO');
INSERT INTO qx_tipos_anestesia VALUES ('05', 'SEDACION');


--subido en pruebas y en la de producción por JORGE