CREATE TABLE public.userpermisos_contratacion_listaprecios (
  empresa_id  char(2) NOT NULL,
  usuario_id  integer NOT NULL,
  PRIMARY KEY (empresa_id, usuario_id),
  /* Foreign keys */
  CONSTRAINT foreign_key01
    FOREIGN KEY (empresa_id)
    REFERENCES public.empresas(empresa_id)
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

ALTER TABLE public.userpermisos_contratacion_listaprecios
  OWNER TO siis;

COMMENT ON TABLE public.userpermisos_contratacion_listaprecios
  IS 'UserPermisos del módulo Contratacion Lista Precios';

COMMENT ON COLUMN public.userpermisos_contratacion_listaprecios.empresa_id
  IS 'Empresa a la que pertenece el usuario';

COMMENT ON COLUMN public.userpermisos_contratacion_listaprecios.usuario_id
  IS 'usuario del módulo';

  
  