
/*Campos no oblligatorios para formulacion externa*/
ALTER TABLE public.esm_formula_externa_tmp
ALTER COLUMN tipo_formula
DROP NOT NULL;


ALTER TABLE public.esm_formula_externa_tmp
ALTER COLUMN esm_tipo_id_tercero
DROP NOT NULL;

ALTER TABLE public.esm_formula_externa_tmp
ALTER COLUMN esm_tercero_id
DROP NOT NULL;


ALTER TABLE public.esm_formula_externa_tmp
ALTER COLUMN tipo_formulacion
DROP NOT NULL;

COMMENT ON COLUMN esm_formula_externa_tmp.tipo_formula IS 'Tipo de Formula';


ALTER TABLE public.esm_formula_externa_tmp
DROP CONSTRAINT esm_formula_externa_tmp_tipo_id_tercero_fkey3;
	
ALTER TABLE public.esm_formula_externa_tmp
DROP CONSTRAINT esm_formula_externa_tmp_tipo_id_tercero_fkey1;
	
ALTER TABLE public.esm_formula_externa_tmp
DROP CONSTRAINT esm_formula_externa_tmp_tipo_id_tercero_fkey;

ALTER TABLE public.esm_formula_externa_tmp
DROP CONSTRAINT esm_formula_externa_tmp_tipo_fuerza_id_fkey2;
  
ALTER TABLE public.esm_formula_externa_tmp
DROP CONSTRAINT esm_formula_externa_tmp_tipo_id_tercero_fkey2;
  
ALTER TABLE public.esm_formula_externa_tmp
DROP CONSTRAINT esm_formula_externa_tmp_tipo_id_tercero_fkey2;
  
ALTER TABLE public.esm_formula_externa
ALTER COLUMN tipo_formula
DROP NOT NULL;

ALTER TABLE public.esm_formula_externa
ALTER COLUMN esm_tipo_id_tercero
DROP NOT NULL;

ALTER TABLE public.esm_formula_externa
ALTER COLUMN esm_tercero_id
DROP NOT NULL;

ALTER TABLE public.esm_formula_externa
DROP CONSTRAINT esm_formula_externa_tipo_id_tercero_fkey1;

ALTER TABLE public.esm_formula_externa
DROP CONSTRAINT esm_formula_externa_tipo_id_tercero_fkey;

ALTER TABLE public.esm_formula_externa
DROP CONSTRAINT esm_formula_externa_tipo_id_tercero_fkey2;

ALTER TABLE public.esm_formula_externa
ADD COLUMN observacion text  NULL;

COMMENT ON COLUMN esm_formula_externa.observacion IS 'Observacion (anular formula)';

ALTER TABLE public.esm_formula_externa
ADD COLUMN  usuario_modifica_id integer  NULL;

COMMENT ON COLUMN esm_formula_externa.observacion IS 'Usuario que modifica el estado a la Formula';

ALTER TABLE public.esm_formula_externa
ADD COLUMN  fecha_modificacion date  NULL;

COMMENT ON COLUMN esm_formula_externa.fecha_modificacion IS 'Fecha de modificacion';








 