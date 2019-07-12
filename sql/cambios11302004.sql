DELETE FROM puntos_salidas_pacientes;
DELETE FROM userpermisos_salidas_pacientes;

ALTER TABLE "puntos_salidas_pacientes" ADD COLUMN prefijo_fac_credito character varying(4);
ALTER TABLE "puntos_salidas_pacientes" ALTER COLUMN "prefijo_fac_credito" SET NOT NULL;

ALTER TABLE "puntos_salidas_pacientes" ADD COLUMN prefijo_fac_contado character varying(4);
ALTER TABLE "puntos_salidas_pacientes" ALTER COLUMN "prefijo_fac_contado" SET NOT NULL;

ALTER TABLE ONLY puntos_salidas_pacientes ADD FOREIGN KEY (empresa_id, prefijo_fac_credito) REFERENCES fac_tipos_facturas(empresa_id, prefijo) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY puntos_salidas_pacientes
    ADD FOREIGN KEY (empresa_id, prefijo_fac_contado) REFERENCES fac_tipos_facturas(empresa_id, prefijo) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY banco_sangre_cruzes_sanguineos ADD COLUMN estado character(1);
UPDATE banco_sangre_cruzes_sanguineos SET estado='1';
ALTER TABLE ONLY banco_sangre_cruzes_sanguineos ALTER COLUMN estado SET NOT NULL;
ALTER TABLE "banco_sangre_bolsas_incineradas" ADD COLUMN "persona_devuelve" character varying(50);
ALTER TABLE "banco_sangre_bolsas_incineradas" ADD COLUMN "hora_devolucion" timestamp without time zone;


CREATE TABLE banco_sangre_cruzes_correcciones(
  cruze_sanguineo_id integer,
  cruze_corrige integer
);
ALTER TABLE ONLY banco_sangre_cruzes_correcciones ADD PRIMARY KEY (cruze_sanguineo_id,cruze_corrige);
ALTER TABLE ONLY banco_sangre_cruzes_correcciones ADD FOREIGN KEY (cruze_sanguineo_id) REFERENCES banco_sangre_cruzes_sanguineos(cruze_sanguineo_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY banco_sangre_cruzes_correcciones ADD FOREIGN KEY (cruze_corrige) REFERENCES banco_sangre_cruzes_sanguineos(cruze_sanguineo_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE "banco_sangre_cruzes_sanguineos" DROP CONSTRAINT "banco_sangre_cruzes_sanguineos_ingreso_bolsa_id_key";
ALTER TABLE ONLY "banco_sangre_cruzes_sanguineos" ADD UNIQUE (cruze_sanguineo_id,ingreso_bolsa_id,solicitud_reserva_sangre_id);
