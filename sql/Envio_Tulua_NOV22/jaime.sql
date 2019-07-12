ALTER TABLE public.userpermisos_busqueda_agenda ADD COLUMN sw_mostrar_historia char(1);
update userpermisos_busqueda_agenda set sw_mostrar_historia='0';
ALTER TABLE public.userpermisos_busqueda_agenda ALTER COLUMN sw_mostrar_historia SET NOT NULL;
ALTER TABLE public.userpermisos_busqueda_agenda ALTER COLUMN sw_mostrar_historia SET DEFAULT '0'::bpchar;

CREATE TABLE "hc_tipos_finalidad_detalle" ("tipo_finalidad_id" character varying(2) NOT NULL, "tipo_finalidad_detalle" SERIAL NOT NULL, "descripcion" character varying(20) NOT NULL);

ALTER TABLE "hc_tipos_finalidad_detalle" ADD PRIMARY KEY ("tipo_finalidad_id","tipo_finalidad_detalle");

ALTER TABLE "hc_tipos_finalidad_detalle" ADD FOREIGN KEY ("tipo_finalidad_id") REFERENCES "public"."hc_tipos_finalidad"("tipo_finalidad_id")  ON UPDATE CASCADE ON DELETE RESTRICT;

COMMENT ON TABLE "hc_tipos_finalidad_detalle" IS 'Identificacion de las diferentes razones por las que se realiza una atencion de PYP';
COMMENT ON COLUMN "hc_tipos_finalidad_detalle"."tipo_finalidad_id" IS 'PK:FK llave foranea para identificar el tipo de finalidad a la cual hace referencia';
COMMENT ON COLUMN "hc_tipos_finalidad_detalle"."tipo_finalidad_detalle" IS 'PK serial para identificar los campos de la tabla';
COMMENT ON COLUMN "hc_tipos_finalidad_detalle"."descripcion" IS 'descripcion para desplegar en la pantalla';


ALTER TABLE "hc_finalidad" DROP CONSTRAINT "hc_finalidad_pkey";

ALTER TABLE "hc_finalidad" ADD PRIMARY KEY ("evolucion_id");


CREATE TABLE hc_finalidad_detalle (
    evolucion_id integer NOT NULL,
    tipo_finalidad_detalle integer NOT NULL,
    tipo_finalidad_id character varying(2) NOT NULL
);

ALTER TABLE "hc_finalidad_detalle" ADD PRIMARY KEY ("evolucion_id","tipo_finalidad_detalle");

ALTER TABLE "hc_finalidad_detalle" ADD FOREIGN KEY ("evolucion_id") REFERENCES "public"."hc_finalidad"("evolucion_id")  ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE "hc_finalidad_detalle" ADD FOREIGN KEY ("tipo_finalidad_detalle","tipo_finalidad_id") REFERENCES "public"."hc_tipos_finalidad_detalle"("tipo_finalidad_detalle","tipo_finalidad_id")  ON UPDATE CASCADE ON DELETE RESTRICT;

COMMENT ON TABLE "hc_finalidad_detalle" IS 'Tabla en donde se guardan la informacion estadistica de las atenciones de PYP';
COMMENT ON COLUMN "hc_finalidad_detalle"."evolucion_id" IS 'PK:FK Llave foranea para identificar la evolucion del paciente';
COMMENT ON COLUMN "hc_finalidad_detalle"."tipo_finalidad_detalle" IS 'PK:FK llave foranea para identificar tipo de finalidad en detalle';
COMMENT ON COLUMN "hc_finalidad_detalle"."tipo_finalidad_id" IS 'PK:FK Llave foranea para identificar el tipo de finalidad';


