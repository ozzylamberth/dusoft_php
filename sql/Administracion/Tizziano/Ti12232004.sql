ALTER TABLE "hc_tipos_antecedentes_detf" DROP CONSTRAINT "$1";
DROP TABLE hc_tipos_antecedentes_detf;

ALTER TABLE "hc_antecedentes_personales" RENAME COLUMN "hc_tipo_antecedente_per_id" TO "hc_tipo_antecedente_personal_id";

ALTER TABLE "hc_antecedentes_personales" RENAME COLUMN "hc_tipo_antecedente_det_id" TO "hc_tipo_antecedente_detalle_personal_id";


