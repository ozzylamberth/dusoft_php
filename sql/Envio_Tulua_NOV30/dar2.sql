-----------------------NOV 26------------------------------

ALTER TABLE "pacientes_urgencias" ADD COLUMN "historia_clinica_tipo_cierre_id" integer;
ALTER TABLE ONLY pacientes_urgencias
    ADD FOREIGN KEY (historia_clinica_tipo_cierre_id) REFERENCES historias_clinicas_tipos_cierres(historia_clinica_tipo_cierre_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE "hc_conducta_remision" ADD COLUMN "observacion_remision" text;

----------------------NOV 30--------------------------------------

DELETE FROM puntos_salidas_pacientes;
DELETE FROM userpermisos_salidas_pacientes;

ALTER TABLE "puntos_salidas_pacientes" ADD COLUMN prefijo_fac_credito character varying(4);
ALTER TABLE "puntos_salidas_pacientes" ALTER COLUMN "prefijo_fac_credito" SET NOT NULL;

ALTER TABLE "puntos_salidas_pacientes" ADD COLUMN prefijo_fac_contado character varying(4);
ALTER TABLE "puntos_salidas_pacientes" ALTER COLUMN "prefijo_fac_contado" SET NOT NULL;

ALTER TABLE ONLY puntos_salidas_pacientes
    ADD FOREIGN KEY (empresa_id, prefijo_fac_credito) REFERENCES fac_tipos_facturas(empresa_id, prefijo) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY puntos_salidas_pacientes
    ADD FOREIGN KEY (empresa_id, prefijo_fac_contado) REFERENCES fac_tipos_facturas(empresa_id, prefijo) ON UPDATE CASCADE ON DELETE RESTRICT;
