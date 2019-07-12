  ALTER TABLE public.esm_empresas_topes
  ALTER COLUMN tipo_id_tercero
  DROP NOT NULL;

  ALTER TABLE public.esm_empresas_topes
  ALTER COLUMN tercero_id
  DROP NOT NULL;
  
ALTER TABLE public.centros_utilidad
  ADD COLUMN sw_topes char NOT NULL DEFAULT 0;

COMMENT ON COLUMN public.centros_utilidad.sw_topes
  IS 'Define si el Centro Utilidad usa Topes de Dispnesacion ''1'' o nò Usa Topes de Dispensacion ''0''';
  
