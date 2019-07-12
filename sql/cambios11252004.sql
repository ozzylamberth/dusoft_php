ALTER TABLE "hc_notas_operatorias_procedimientos" DROP CONSTRAINT "hc_notas_operatorias_procedimientos_pkey";
ALTER TABLE "hc_notas_operatorias_procedimientos" ADD PRIMARY KEY (hc_nota_operatoria_cirugia_id,tipo_id_cirujano,cirujano_id,procedimiento_qx);
ALTER TABLE "hc_notas_operatorias_procedimientos" ALTER COLUMN tipo_id_ayudante DROP NOT NULL;
