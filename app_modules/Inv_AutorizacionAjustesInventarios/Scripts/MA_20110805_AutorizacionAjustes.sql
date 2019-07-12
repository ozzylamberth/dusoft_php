ALTER TABLE public.inv_bodegas_movimiento_d
  ADD COLUMN cantidad_sistema numeric(14,4) NOT NULL DEFAULT 0;

COMMENT ON COLUMN public.inv_bodegas_movimiento_d.cantidad_sistema
  IS 'CANTIDAD QUE HABÍA EN EL SISTEMA(UN LOTE), AL MOMENTO DE HACER EL AJUSTE SEA POR INGRESO (I003) O POR EGRESO (E003)';

  ALTER TABLE public.inv_bodegas_movimiento_tmp_d
  ADD COLUMN cantidad_sistema numeric(14,4) NOT NULL DEFAULT 0;

COMMENT ON COLUMN public.inv_bodegas_movimiento_tmp_d.cantidad_sistema
  IS 'CANTIDAD QUE HABÍA EN EL SISTEMA(UN LOTE), AL MOMENTO DE HACER EL AJUSTE SEA POR INGRESO (I003) O POR EGRESO (E003)';


ALTER TABLE public.inv_bodegas_movimiento_ajustes
  ADD COLUMN usuario_control_interno integer;

COMMENT ON COLUMN public.inv_bodegas_movimiento_ajustes.usuario_control_interno
  IS 'USUARIO DE CONTROL INTERNO QUE HACE LA AUTORIZACION DE UN AJUSTE';

  ALTER TABLE public.inv_bodegas_movimiento_ajustes
  ADD COLUMN usuario_jefe_bodega integer;

COMMENT ON COLUMN public.inv_bodegas_movimiento_ajustes.usuario_jefe_bodega
  IS 'USUARIO DE JEFE DE BODEGA QUE HACE LA AUTORIZACION DE UN AJUSTE';

  
  ALTER TABLE public.inv_bodegas_movimiento_tmp_ajustes
  ADD COLUMN usuario_control_interno integer;

COMMENT ON COLUMN public.inv_bodegas_movimiento_tmp_ajustes.usuario_control_interno
  IS 'USUARIO DE CONTROL INTERNO QUE HACE LA AUTORIZACION DE UN AJUSTE';
  
  ALTER TABLE public.inv_bodegas_movimiento_tmp_ajustes
  ADD COLUMN usuario_jefe_bodega integer;

COMMENT ON COLUMN public.inv_bodegas_movimiento_tmp_ajustes.usuario_jefe_bodega
  IS 'USUARIO DE JEFE DE BODEGA QUE HACE LA AUTORIZACION DE UN AJUSTE';

ALTER TABLE public.inv_bodegas_movimiento_tmp_ajustes
  ADD CONSTRAINT foreign_key02
  FOREIGN KEY (usuario_control_interno)
    REFERENCES public.system_usuarios(usuario_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE;

	ALTER TABLE public.inv_bodegas_movimiento_tmp_ajustes
  ADD CONSTRAINT foreign_key03
  FOREIGN KEY (usuario_jefe_bodega)
    REFERENCES public.system_usuarios(usuario_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE;

  
  ALTER TABLE public.inv_bodegas_movimiento_ajustes
  ADD CONSTRAINT foreign_key02
  FOREIGN KEY (usuario_control_interno)
    REFERENCES public.system_usuarios(usuario_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE;

	ALTER TABLE public.inv_bodegas_movimiento_ajustes
  ADD CONSTRAINT foreign_key03
  FOREIGN KEY (usuario_jefe_bodega)
    REFERENCES public.system_usuarios(usuario_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE;

	CREATE TABLE public.userpermisos_autorizacion_ajustes_inventarios (
  empresa_id       char(2) NOT NULL,
  centro_utilidad  char(2) NOT NULL,
  usuario_id       integer NOT NULL,
  PRIMARY KEY (empresa_id, centro_utilidad, usuario_id),
  /* Foreign keys */
  CONSTRAINT foreign_key01
    FOREIGN KEY (empresa_id, centro_utilidad)
    REFERENCES public.centros_utilidad(empresa_id, centro_utilidad)
    ON DELETE RESTRICT
    ON UPDATE CASCADE, 
  CONSTRAINT foreign_key02
    FOREIGN KEY (usuario_id)
    REFERENCES public.system_usuarios(usuario_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.userpermisos_autorizacion_ajustes_inventarios
  OWNER TO siis;

COMMENT ON TABLE public.userpermisos_autorizacion_ajustes_inventarios
  IS 'Tabla que permite parametrizar los permisos al modulo para los ajustes a inventarios y autorizar los documentos';

COMMENT ON COLUMN public.userpermisos_autorizacion_ajustes_inventarios.empresa_id
  IS 'Empresa que Pertenece el usuario';

COMMENT ON COLUMN public.userpermisos_autorizacion_ajustes_inventarios.centro_utilidad
  IS 'Centro de Utilidad Donde se tiene permiso el usuario';

COMMENT ON COLUMN public.userpermisos_autorizacion_ajustes_inventarios.usuario_id
  IS 'Llave primaria del usuario';

  
  ALTER TABLE public.userpermisos_autorizacion_ajustes_inventarios
  ADD COLUMN tipo_usuario char NOT NULL DEFAULT 0;

COMMENT ON COLUMN public.userpermisos_autorizacion_ajustes_inventarios.tipo_usuario
  IS 'Define el tipo de usuario que Ingresa al Modulo: (0)=ControlInterno (1)=JefeDeBodega';

  ALTER TABLE public.inv_bodegas_movimiento_tmp_ajustes
  ADD COLUMN toma_fisica_id integer;

COMMENT ON COLUMN public.inv_bodegas_movimiento_tmp_ajustes.toma_fisica_id
  IS 'Identifica cuales han sido los documentos que fueron usados para ajustes en tomas fisicas';

  ALTER TABLE public.inv_bodegas_movimiento_tmp_ajustes
  ADD CONSTRAINT foreign_key04
  FOREIGN KEY (toma_fisica_id)
    REFERENCES public.inv_toma_fisica(toma_fisica_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE;

	ALTER TABLE public.inv_bodegas_movimiento_ajustes
  ADD COLUMN toma_fisica_id integer;

COMMENT ON COLUMN public.inv_bodegas_movimiento_ajustes.toma_fisica_id
  IS 'Identifica cuales han sido los documentos que fueron usados para ajustes en tomas fisicas';

  ALTER TABLE public.inv_bodegas_movimiento_ajustes
  ADD CONSTRAINT foreign_key05
  FOREIGN KEY (toma_fisica_id)
    REFERENCES public.inv_toma_fisica(toma_fisica_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE;

	