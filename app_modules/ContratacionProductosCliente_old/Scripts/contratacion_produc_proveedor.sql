ALTER TABLE public.contratacion_produc_proveedor
  ALTER COLUMN codigo_proveedor_id
  DROP NOT NULL;

  ALTER TABLE public.contratacion_produc_proveedor
  ADD COLUMN porcentaje_genericos numeric(9,2);

COMMENT ON COLUMN public.contratacion_produc_proveedor.porcentaje_genericos
  IS 'Es el porcentaje de ganancia que se le dá a los productos Genericos cuando se va a realizar una venta.';

  ALTER TABLE public.contratacion_produc_proveedor
 ADD COLUMN sw_cliente char DEFAULT 0;

COMMENT ON COLUMN public.contratacion_produc_proveedor.sw_cliente
  IS 'Para Diferenciar del contrato de un cliente a un proveedor';
