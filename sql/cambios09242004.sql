 ALTER TABLE "cuentas_detalle" DROP COLUMN "codigo_agrupamiento_id";
 ALTER TABLE "cuentas_detalle" DROP COLUMN "secuencia_agrupamiento";
 ALTER TABLE "cuentas_detalle" ADD COLUMN "codigo_agrupamiento_id" integer;
 ALTER TABLE "cuentas_detalle" ADD COLUMN "consecutivo" integer;
 ALTER TABLE "cuentas_detalle" ADD COLUMN "cargo_cups" varchar(10);

CREATE TABLE cuentas_codigos_agrupamiento (
    codigo_agrupamiento_id serial NOT NULL,
    descripcion character varying(255) NOT NULL,
    bodegas_doc_id integer,
    numeracion integer
);

ALTER TABLE ONLY cuentas_codigos_agrupamiento  ADD PRIMARY KEY (codigo_agrupamiento_id);
ALTER TABLE ONLY cuentas_codigos_agrupamiento  ADD FOREIGN KEY (bodegas_doc_id, numeracion) REFERENCES bodegas_documentos(bodegas_doc_id, numeracion) ON UPDATE CASCADE ON DELETE RESTRICT;


 ALTER TABLE "cuentas_detalle" ADD FOREIGN KEY (codigo_agrupamiento_id) REFERENCES cuentas_codigos_agrupamiento(codigo_agrupamiento_id) ON UPDATE CASCADE ON DELETE RESTRICT;
 ALTER TABLE "cuentas_detalle" ADD FOREIGN KEY (consecutivo) REFERENCES bodegas_documentos_d(consecutivo) ON UPDATE CASCADE ON DELETE RESTRICT;


 ALTER TABLE "tmp_cuentas_detalle" ADD COLUMN "consecutivo" integer;
 ALTER TABLE "tmp_cuentas_detalle" ADD COLUMN "cargo_cups" varchar(10);
 ALTER TABLE "tmp_cuentas_detalle" ADD FOREIGN KEY (codigo_agrupamiento_id) REFERENCES cuentas_codigos_agrupamiento(codigo_agrupamiento_id) ON UPDATE CASCADE ON DELETE RESTRICT;
 ALTER TABLE "tmp_cuentas_detalle" ADD FOREIGN KEY (consecutivo) REFERENCES bodegas_documentos_d(consecutivo) ON UPDATE CASCADE ON DELETE RESTRICT;
