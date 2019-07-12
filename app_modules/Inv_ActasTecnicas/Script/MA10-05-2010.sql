ALTER TABLE public.inv_notas_facturas_proveedor
  ADD COLUMN observacion_anulacion text;

COMMENT ON COLUMN public.inv_notas_facturas_proveedor.observacion_anulacion
  IS 'En Caso de Anular una nota, se debe registrar una jusificacion';

  ALTER TABLE public.inv_notas_facturas_proveedor
  ADD COLUMN usuario_id_anulador integer;

COMMENT ON COLUMN public.inv_notas_facturas_proveedor.usuario_id_anulador
  IS 'Usuario que anula la nota a la factura';

  ALTER TABLE public.inv_notas_facturas_proveedor
  ADD COLUMN fecha_anulacion timestamp(1) WITHOUT TIME ZONE;

COMMENT ON COLUMN public.inv_notas_facturas_proveedor.fecha_anulacion
  IS 'Fecha Anulacion de la Nota';
