CREATE TABLE public.userpermisos_notasfacturas_proveedor (
  empresa_id           char(2) NOT NULL,
  "indice_automático"  serial NOT NULL UNIQUE,
  usuario_id           integer NOT NULL,
  PRIMARY KEY (empresa_id, usuario_id)
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.userpermisos_notasfacturas_proveedor
  OWNER TO siis;

COMMENT ON TABLE public.userpermisos_notasfacturas_proveedor
  IS 'Permisos modulo de notas a facturas de proveedor';

COMMENT ON COLUMN public.userpermisos_notasfacturas_proveedor.empresa_id
  IS 'Empresa a la que pertenece el usuario';

COMMENT ON COLUMN public.userpermisos_notasfacturas_proveedor."indice_automático"
  IS 'Campo Serial';

COMMENT ON COLUMN public.userpermisos_notasfacturas_proveedor.usuario_id
  IS 'usuario del Módulo';
