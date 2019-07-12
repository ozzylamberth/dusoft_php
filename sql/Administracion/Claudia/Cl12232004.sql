
CREATE TABLE hc_resultados_manuales_parametros (
    empresa_id character(2) NOT NULL,
    sw_ingreso_manual character(1) DEFAULT 0 NOT NULL
);


ALTER TABLE ONLY hc_resultados_manuales_parametros
    ADD CONSTRAINT hc_resultados_manuales_parametros_pkey PRIMARY KEY (empresa_id);

ALTER TABLE ONLY hc_resultados_manuales_parametros
    ADD CONSTRAINT "$1" FOREIGN KEY (empresa_id) REFERENCES empresas(empresa_id) ON UPDATE CASCADE ON DELETE RESTRICT;



