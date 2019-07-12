ALTER TABLE public.eps_planes_parametros
  ADD COLUMN tabla_afiliados varchar(100);

COMMENT ON COLUMN public.eps_planes_parametros.tabla_afiliados
  IS 'Campo Opcional, donde se registra la tabla que se encuentran los afiliados del plan.';
