ALTER TABLE public.hc_tipos_antecedentes_det ADD COLUMN sw_defecto char(1);
ALTER TABLE public.hc_tipos_antecedentes_det ALTER COLUMN sw_defecto SET STORAGE EXTENDED;
ALTER TABLE public.hc_tipos_antecedentes_det ALTER COLUMN sw_defecto SET DEFAULT '0'::bpchar;
COMMENT ON COLUMN public.hc_tipos_antecedentes_det.sw_defecto IS 'tiene como funcion mostrar los antecedentes por defecto si no se encuntra nada en la union de modulos';

CREATE TABLE public.hc_tipos_antecedentes_personales_detalle_modulos
(
  hc_tipo_antecedente_det_id int4 NOT NULL,
  hc_modulo varchar(64) NOT NULL,
  hc_tipo_antecedente_per_id int4 NOT NULL,
  CONSTRAINT hc_tipos_antecedentes_personales_detalle_modulos_pkey PRIMARY KEY (hc_tipo_antecedente_det_id, hc_modulo, hc_tipo_antecedente_per_id),
  CONSTRAINT "$1" FOREIGN KEY (hc_tipo_antecedente_det_id, hc_tipo_antecedente_per_id) REFERENCES public.hc_tipos_antecedentes_det (hc_tipo_antecedente_det_id, hc_tipo_antecedente_per_id) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT "$2" FOREIGN KEY (hc_modulo) REFERENCES public.system_hc_modulos (hc_modulo) ON UPDATE CASCADE ON DELETE RESTRICT
) WITH OIDS;
COMMENT ON TABLE public.hc_tipos_antecedentes_personales_detalle_modulos IS 'convinacion de tabla entre antecedentes personales y modulos';
COMMENT ON COLUMN public.hc_tipos_antecedentes_personales_detalle_modulos.hc_tipo_antecedente_det_id IS 'PK llave que identifica el tipo de antecedente personales';
COMMENT ON COLUMN public.hc_tipos_antecedentes_personales_detalle_modulos.hc_modulo IS 'PK Nombre modulo de historia clinica';
COMMENT ON COLUMN public.hc_tipos_antecedentes_personales_detalle_modulos.hc_tipo_antecedente_per_id IS 'FK Llave foranea que identifica el maestro del antecedente personal';