ALTER TABLE hc_notas_operatorias_cirugias ADD COLUMN tipo_id_cirujano character varying(3);

ALTER TABLE hc_notas_operatorias_cirugias ADD COLUMN cirujano_id character varying(32);


ALTER TABLE hc_notas_operatorias_cirugias
ADD CONSTRAINT hc_notas_operatorias_cirugias_fk FOREIGN KEY (tipo_id_cirujano, cirujano_id) REFERENCES terceros(tipo_id_tercero, tercero_id) ON UPDATE CASCADE ON DELETE RESTRICT;