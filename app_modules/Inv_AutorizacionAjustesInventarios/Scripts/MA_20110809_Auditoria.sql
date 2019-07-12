ALTER TABLE public.inv_bodegas_movimiento_ajustes
  ADD COLUMN auditor integer;

COMMENT ON COLUMN public.inv_bodegas_movimiento_ajustes.auditor
  IS 'Auditor que revisa el documento creado de ajuste';

  ALTER TABLE public.inv_bodegas_movimiento_ajustes
  ADD COLUMN fecha_auditoria timestamp(1) WITHOUT TIME ZONE;

COMMENT ON COLUMN public.inv_bodegas_movimiento_ajustes.fecha_auditoria
  IS 'Fecha en la cual se hizo la auditorìa';

  ALTER TABLE public.inv_bodegas_movimiento_ajustes
  ADD COLUMN observacion_auditoria text;

COMMENT ON COLUMN public.inv_bodegas_movimiento_ajustes.observacion_auditoria
  IS 'Observacion que Diligencia el auditor del documento';

  ALTER TABLE public.inv_bodegas_movimiento_ajustes
  ADD CONSTRAINT foreign_key04
  FOREIGN KEY (auditor)
    REFERENCES public.system_usuarios(usuario_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE;

	ALTER TABLE public.userpermisos_autorizacion_ajustes_inventarios
  ADD COLUMN autorizador char NOT NULL DEFAULT 0;

COMMENT ON COLUMN public.userpermisos_autorizacion_ajustes_inventarios.autorizador
  IS 'Especifica si (0)=> Si el Usuario Solo Autoriza, (1)=> Si el Usuario Solo Audita un Ajuste y (2)=> Si el usuario puede hacer ambas tareas';
  
  COMMENT ON COLUMN public.userpermisos_autorizacion_ajustes_inventarios.autorizador
  IS 'Especifica si (0)=> Si el Usuario Solo Autoriza, (1)=> Si el Usuario Solo Audita un Ajuste, (2)=> Si el Usuario puede Autorizar Despachos a Clientes Y Farmacias y (3)=> Si el usuario puede hacer ambas tareas';

  