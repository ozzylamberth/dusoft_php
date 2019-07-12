ALTER TABLE qx_acto ADD diagnostico_id character varying(6);
ALTER TABLE qx_acto ADD complicacion_id character varying(6);
ALTER TABLE qx_acto ADD tipo_anestesia character varying(2);
ALTER TABLE qx_acto ADD fecha_inicio_anestesia timestamp without time zone;
ALTER TABLE qx_acto ADD fecha_fin_anestesia timestamp without time zone;
ALTER TABLE qx_acto ADD fecha_ingreso_recuperacion timestamp without time zone;
ALTER TABLE qx_acto ADD fecha_egreso_recuperacion timestamp without time zone;
ALTER TABLE qx_acto ADD sw_estado_salida character varying(1);

ALTER TABLE ONLY qx_acto
ADD CONSTRAINT "$12" FOREIGN KEY (diagnostico_id)
REFERENCES diagnosticos(diagnostico_id)
ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY qx_acto
ADD CONSTRAINT "$13" FOREIGN KEY (complicacion_id)
REFERENCES diagnosticos(diagnostico_id)
ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY qx_acto
ADD CONSTRAINT "$14" FOREIGN KEY (tipo_anestesia)
REFERENCES qx_tipos_anestesia(qx_tipo_anestesia_id)
ON UPDATE CASCADE ON DELETE RESTRICT;


--subido en pruebas y en la de producción por JORGE