ALTER TABLE public.userpermisos_reportes_gral
  ADD COLUMN centro_utilidad char(2);

COMMENT ON COLUMN public.userpermisos_reportes_gral.centro_utilidad
  IS 'Centro Utilidad del Usuario';

  ALTER TABLE public.userpermisos_reportes_gral
  DROP CONSTRAINT userpermisos_reportes_gral_empresa_id_fkey;

ALTER TABLE public.userpermisos_reportes_gral
  ADD CONSTRAINT userpermisos_reportes_gral_empresa_id_fkey
  FOREIGN KEY (empresa_id, centro_utilidad)
    REFERENCES public.centros_utilidad(empresa_id, centro_utilidad)
    ON DELETE CASCADE
    ON UPDATE CASCADE;