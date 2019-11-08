CREATE TABLE public.contratacion_produc_proveedor_politicas (
  politica_producto_id         serial NOT NULL PRIMARY KEY,
  contrato_produc_prov_det_id  integer NOT NULL,
  politica                     text NOT NULL,
  usuario_id                   integer NOT NULL,
  fecha_registro               timestamp(1) WITHOUT TIME ZONE NOT NULL DEFAULT NOW(),
  /* Foreign keys */
  CONSTRAINT foreign_key01
    FOREIGN KEY (contrato_produc_prov_det_id)
    REFERENCES public.contratacion_produc_prov_detalle(contrato_produc_prov_det_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE, 
  CONSTRAINT foreign_key02
    FOREIGN KEY (usuario_id)
    REFERENCES public.system_usuarios(usuario_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.contratacion_produc_proveedor_politicas
  OWNER TO siis;

COMMENT ON TABLE public.contratacion_produc_proveedor_politicas
  IS 'Tabla donde se parametrizan las Politicas de Devolucion del Proveedor';

COMMENT ON COLUMN public.contratacion_produc_proveedor_politicas.politica_producto_id
  IS 'Consecutivo de la Politica del Proveedor
';

COMMENT ON COLUMN public.contratacion_produc_proveedor_politicas.contrato_produc_prov_det_id
  IS 'Producto Inscrito en el contrato';

COMMENT ON COLUMN public.contratacion_produc_proveedor_politicas.politica
  IS 'Politica de devolucion del proveedor';

COMMENT ON COLUMN public.contratacion_produc_proveedor_politicas.usuario_id
  IS 'Usuario que inscribe la Politica';

COMMENT ON COLUMN public.contratacion_produc_proveedor_politicas.fecha_registro
  IS 'Fecha de registro de la politica';
