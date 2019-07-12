ALTER TABLE public.userpermisos_esm_ordenes_requisicion
  ADD COLUMN centro_utilidad char(2);

COMMENT ON COLUMN public.userpermisos_esm_ordenes_requisicion.centro_utilidad
  IS 'Centro de Utilidad a la que puede pertenecer el usuario';

  ALTER TABLE public.userpermisos_esm_ordenes_requisicion
  DROP CONSTRAINT foreign_key01;

ALTER TABLE public.userpermisos_esm_ordenes_requisicion
  ADD CONSTRAINT foreign_key01
  FOREIGN KEY (empresa_id, centro_utilidad)
    REFERENCES public.centros_utilidad(empresa_id, centro_utilidad)
    ON DELETE RESTRICT
    ON UPDATE CASCADE;

	ALTER TABLE public.userpermisos_esm_ordenes_requisicion
  DROP CONSTRAINT userpermisos_esm_ordenes_requisicion_pkey;

ALTER TABLE public.userpermisos_esm_ordenes_requisicion
  ADD CONSTRAINT userpermisos_esm_ordenes_requisicion_pkey
  PRIMARY KEY (empresa_id, usuario_id, centro_utilidad)
    WITH (FILLFACTOR = 100);
