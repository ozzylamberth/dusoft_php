ALTER TABLE public.inv_bodegas_userpermisos_admin
 ADD COLUMN priv char NOT NULL DEFAULT 0;

COMMENT ON COLUMN public.inv_bodegas_userpermisos_admin.priv
 IS 'Si el Usuario tiene este valor en (''1'') es porque puede visualizar los costos, Si está en (''0''), No puede visualizarlos. Nombre: ''priv''->Privilegios';