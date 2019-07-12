
ALTER TABLE public.esm_formula_externa
 ADD COLUMN sw_autorizado  CHARACTER(1) NOT NULL DEFAULT '0';
 
COMMENT ON COLUMN public.esm_formula_externa.sw_autorizado
 IS 'Define si la formula aun vencida se puede despachar por medio de autorizacion (0)sin autorizacion (1)autorizado';
 
ALTER TABLE public.esm_formula_externa
 ADD COLUMN usuario_autoriza_id  integer  NULL;

COMMENT ON COLUMN public.esm_formula_externa.usuario_autoriza_id
 IS 'usuario que autorizo el despacho';
 
 ALTER TABLE public.esm_formula_externa
 ADD COLUMN observacion_autorizacion  text  NULL;
 
 COMMENT ON COLUMN public.esm_formula_externa.observacion_autorizacion
 IS 'observacion de la autorizacion';
 
 ALTER TABLE public.esm_formula_externa
 ADD COLUMN fecha_registro_autorizacion  TIMESTAMP WITHOUT TIME ZONE  NULL ;
 
 COMMENT ON COLUMN public.esm_formula_externa.fecha_registro_autorizacion
 IS 'Fecha de la Autorizacion';