ALTER TABLE public.userpermisos_infoproductosdisponibleppl
  ADD COLUMN centro_utilidad char(2) NOT NULL;

COMMENT ON COLUMN public.userpermisos_infoproductosdisponibleppl.centro_utilidad
  IS 'Centro de Utilidad de la Farmacia';

  ALTER TABLE public.userpermisos_infoproductosdisponibleppl
  DROP CONSTRAINT userpermisos_infoproductosdisponibleppl_pkey;

ALTER TABLE public.userpermisos_infoproductosdisponibleppl
  ADD CONSTRAINT userpermisos_infoproductosdisponibleppl_pkey
  PRIMARY KEY (empresa_id, usuario_id, centro_utilidad)
    WITH (FILLFACTOR = 100);

	ALTER TABLE public.userpermisos_infoproductosdisponibleppl
  DROP CONSTRAINT userpermisos_infoproductosdisponibleppl_empresa_id_fkey;

ALTER TABLE public.userpermisos_infoproductosdisponibleppl
  ADD CONSTRAINT userpermisos_infoproductosdisponibleppl_empresa_id_fkey
  FOREIGN KEY (empresa_id, centro_utilidad)
    REFERENCES public.centros_utilidad(empresa_id, centro_utilidad)
    ON DELETE RESTRICT
    ON UPDATE CASCADE;

	ALTER TABLE public.userpermisos_infoproductosdisponibleppl
  ADD CONSTRAINT userpermisos_infoproductosdisponibleppl_index01
  PRIMARY KEY (empresa_id, usuario_id, centro_utilidad);
