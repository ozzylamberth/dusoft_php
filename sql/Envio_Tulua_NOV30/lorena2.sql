CREATE TABLE banco_sangre_cantidad_cruzes(
  codigo_cantidad_cruces character varying(2),
	descripcion character(10)
);
ALTER TABLE ONLY banco_sangre_cantidad_cruzes ADD PRIMARY KEY (codigo_cantidad_cruces);
INSERT INTO banco_sangre_cantidad_cruzes(codigo_cantidad_cruces,descripcion)VALUES('0','0');
INSERT INTO banco_sangre_cantidad_cruzes(codigo_cantidad_cruces,descripcion)VALUES('1','+');
INSERT INTO banco_sangre_cantidad_cruzes(codigo_cantidad_cruces,descripcion)VALUES('2','++');
INSERT INTO banco_sangre_cantidad_cruzes(codigo_cantidad_cruces,descripcion)VALUES('3','+++');
INSERT INTO banco_sangre_cantidad_cruzes(codigo_cantidad_cruces,descripcion)VALUES('4','++++');

CREATE TABLE banco_sangre_motivos_insercion_bolsas(
  codigo_motivo character(1),
	descripcion character(20)
);
ALTER TABLE ONLY banco_sangre_motivos_insercion_bolsas ADD PRIMARY KEY(codigo_motivo);
INSERT INTO banco_sangre_motivos_insercion_bolsas(codigo_motivo,descripcion)VALUES('C','ADQUISICION');
INSERT INTO banco_sangre_motivos_insercion_bolsas(codigo_motivo,descripcion)VALUES('D','DEVOLUCION');
INSERT INTO banco_sangre_motivos_insercion_bolsas(codigo_motivo,descripcion)VALUES('P','PRESTAMO');
INSERT INTO banco_sangre_motivos_insercion_bolsas(codigo_motivo,descripcion)VALUES('I','PEDIDO');
ALTER TABLE "banco_sangre_bolsas" ADD FOREIGN KEY ("motivo_insercion") REFERENCES "public"."banco_sangre_motivos_insercion_bolsas"("codigo_motivo")  ON UPDATE RESTRICT ON DELETE CASCADE;
ALTER TABLE "banco_sangre_bolsas" ADD COLUMN albaran character varying(20);

ALTER TABLE ONLY banco_sangre_cruzes_sanguineos ADD COLUMN estado character(1);
UPDATE banco_sangre_cruzes_sanguineos SET estado='1';
ALTER TABLE ONLY banco_sangre_cruzes_sanguineos ALTER COLUMN estado SET NOT NULL;
CREATE TABLE banco_sangre_cruzes_correcciones(
  cruze_sanguineo_id integer,
  cruze_corrige integer
);
ALTER TABLE ONLY banco_sangre_cruzes_correcciones ADD PRIMARY KEY (cruze_sanguineo_id,cruze_corrige);
ALTER TABLE ONLY banco_sangre_cruzes_correcciones ADD FOREIGN KEY (cruze_sanguineo_id) REFERENCES banco_sangre_cruzes_sanguineos(cruze_sanguineo_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY banco_sangre_cruzes_correcciones ADD FOREIGN KEY (cruze_corrige) REFERENCES banco_sangre_cruzes_sanguineos(cruze_sanguineo_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE "banco_sangre_cruzes_sanguineos" DROP CONSTRAINT "banco_sangre_cruzes_sanguineos_ingreso_bolsa_id_key";
ALTER TABLE ONLY "banco_sangre_cruzes_sanguineos" ADD UNIQUE (cruze_sanguineo_id,ingreso_bolsa_id,solicitud_reserva_sangre_id);

ALTER TABLE "banco_sangre_bolsas_incineradas" ADD COLUMN "persona_devuelve" character varying(50);
ALTER TABLE "banco_sangre_bolsas_incineradas" ADD COLUMN "hora_devolucion" timestamp without time zone;
