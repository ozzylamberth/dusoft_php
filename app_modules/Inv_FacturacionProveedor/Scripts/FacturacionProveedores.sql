CREATE TABLE public.inv_facturas_proveedores (
  codigo_proveedor_id        integer NOT NULL,
  numero_factura             varchar(40) NOT NULL,
  fecha_registro             timestamp(1) WITHOUT TIME ZONE NOT NULL DEFAULT now(),
  observaciones              text,
  empresa_id                 varchar(2),
  centro_utilidad            varchar(2) NOT NULL,
  bodega                     varchar(2) NOT NULL,
  valor_descuento            numeric(18,2) DEFAULT 0,
  porc_rtf                   numeric(3,2) DEFAULT 0,
  porc_ica                   numeric(3,2) DEFAULT 0,
  usuario_id                 integer NOT NULL,
  sw_verificado              char DEFAULT 0,
  observacion_verificacion   text,
  calificacion_verificacion  char DEFAULT 0,
  usuario_id_verificador     integer,
  fecha_verificacion         timestamp(1) WITHOUT TIME ZONE,
  /* Keys */
  CONSTRAINT inv_facturas_proveedores_pkey
    PRIMARY KEY (numero_factura)
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.inv_facturas_proveedores
  OWNER TO siis;

COMMENT ON TABLE public.inv_facturas_proveedores
  IS 'Tabla donde se guardaran las facturas de venta de los terceros proveedores para poderlas relacionarlas en un Ingreso a Bodega';

COMMENT ON COLUMN public.inv_facturas_proveedores.codigo_proveedor_id
  IS 'codigo del Tercero Proveedor (tabla terceros_proveedores)';

COMMENT ON COLUMN public.inv_facturas_proveedores.numero_factura
  IS 'Numero de la factura de venta del proveedor!!!';

COMMENT ON COLUMN public.inv_facturas_proveedores.fecha_registro
  IS 'Fecha de registro de la Factura';

COMMENT ON COLUMN public.inv_facturas_proveedores.observaciones
  IS 'Observaciones en la factura de venta';

COMMENT ON COLUMN public.inv_facturas_proveedores.empresa_id
  IS 'Empresa que registra la factura';

COMMENT ON COLUMN public.inv_facturas_proveedores.centro_utilidad
  IS 'centro_utilidad de la empresa que registra la factura';

COMMENT ON COLUMN public.inv_facturas_proveedores.bodega
  IS 'Bodega perteneciente al centro e utilidad y empresa que registrará la factura y se realizará el ingreso de los productos por compra';

COMMENT ON COLUMN public.inv_facturas_proveedores.valor_descuento
  IS 'Valor Del Descuento En la Factura';

COMMENT ON COLUMN public.inv_facturas_proveedores.porc_rtf
  IS 'Porcentaje de RTF que se aplicará a la Factura.';

COMMENT ON COLUMN public.inv_facturas_proveedores.porc_ica
  IS 'Porcentaje de ICA aplicado a La Factura';

COMMENT ON COLUMN public.inv_facturas_proveedores.usuario_id
  IS 'Usuario Que Realiza La Factura';

COMMENT ON COLUMN public.inv_facturas_proveedores.sw_verificado
  IS 'ES UN CAMPO PARA GUARDAR EL ESTADO SI ESTA HA SIDO VISTA POR CONTROL INTERNO';

COMMENT ON COLUMN public.inv_facturas_proveedores.observacion_verificacion
  IS 'Son los Comentarios a la verificacion Realizada... por Control Interno';

COMMENT ON COLUMN public.inv_facturas_proveedores.calificacion_verificacion
  IS 'Calificacion que Tuvo la Verificacion de Control Interno!';

COMMENT ON COLUMN public.inv_facturas_proveedores.usuario_id_verificador
  IS 'Usuario que hace la verificacion';

COMMENT ON COLUMN public.inv_facturas_proveedores.fecha_verificacion
  IS 'Fecha en la que Control Interno hizo la Verificacion';

GRANT SELECT, INSERT, UPDATE, DELETE
 ON public.inv_facturas_proveedores
TO siis;

GRANT SELECT, INSERT, UPDATE, DELETE, REFERENCES, TRIGGER
 ON public.inv_facturas_proveedores
TO "admin";

CREATE TABLE public.inv_facturas_proveedores_d (
  codigo_producto       varchar(50) NOT NULL,
  porc_iva              numeric(3,2) NOT NULL,
  recepcion_parcial_id  integer NOT NULL,
  cantidad              numeric(18) NOT NULL,
  valor                 numeric(22) NOT NULL,
  lote                  varchar(255) NOT NULL,
  fecha_vencimiento     date NOT NULL,
  numero_factura        varchar(40) NOT NULL,
  item_id               serial NOT NULL,
  /* Keys */
  CONSTRAINT inv_facturas_proveedores_d_pkey
    PRIMARY KEY (item_id)
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.inv_facturas_proveedores_d
  OWNER TO siis;

COMMENT ON TABLE public.inv_facturas_proveedores_d
  IS 'Detalle de una Factura de Un proveeedor';

COMMENT ON COLUMN public.inv_facturas_proveedores_d.codigo_producto
  IS 'Codigo del Productos Asociado a la Factura';

COMMENT ON COLUMN public.inv_facturas_proveedores_d.porc_iva
  IS 'IVa del Producto';

COMMENT ON COLUMN public.inv_facturas_proveedores_d.recepcion_parcial_id
  IS 'Recepcion Asociada al detalle de la factura';

COMMENT ON COLUMN public.inv_facturas_proveedores_d.cantidad
  IS 'Cantidad de Productos Facturados';

COMMENT ON COLUMN public.inv_facturas_proveedores_d.valor
  IS 'Valor Unitario Del producto';

COMMENT ON COLUMN public.inv_facturas_proveedores_d.lote
  IS 'Lote Del Producto';

COMMENT ON COLUMN public.inv_facturas_proveedores_d.fecha_vencimiento
  IS 'Fecha de Vencimiento del Producto';

COMMENT ON COLUMN public.inv_facturas_proveedores_d.numero_factura
  IS 'Asociado a la cabecera de la factura';

COMMENT ON COLUMN public.inv_facturas_proveedores_d.item_id
  IS 'Item de la factura';
  
  
  CREATE TABLE public.inv_recepciones_parciales (
  recepcion_parcial_id  serial NOT NULL,
  empresa_id            varchar(2) NOT NULL,
  centro_utilidad       varchar(2) NOT NULL,
  bodega                varchar(2) NOT NULL,
  orden_pedido_id       integer NOT NULL,
  documento_id          integer,
  prefijo               varchar(4),
  numero                integer,
  sw_facturado          char NOT NULL DEFAULT 0,
  fecha_registro        timestamp(1) WITHOUT TIME ZONE DEFAULT now(),
  usuario_id            integer NOT NULL,
  /* Keys */
  CONSTRAINT inv_recepciones_parciales_pkey
    PRIMARY KEY (recepcion_parcial_id)
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.inv_recepciones_parciales
  OWNER TO siis;

COMMENT ON TABLE public.inv_recepciones_parciales
  IS 'Donde se guardaran las Recepciones Parciales de un Ingreso por Orden de Compra';

COMMENT ON COLUMN public.inv_recepciones_parciales.recepcion_parcial_id
  IS 'Es la llave Primaria de la recepcion Parcial';

COMMENT ON COLUMN public.inv_recepciones_parciales.empresa_id
  IS 'Empresa que Hizo el Ingreso';

COMMENT ON COLUMN public.inv_recepciones_parciales.centro_utilidad
  IS 'Centro de Utilidad a la que le hicieron el ingreso';

COMMENT ON COLUMN public.inv_recepciones_parciales.bodega
  IS 'Bodega donde se ha realizado el Ingreso';

COMMENT ON COLUMN public.inv_recepciones_parciales.orden_pedido_id
  IS 'Orden de COmpra Relacionada en la Recepcion Parcial';

COMMENT ON COLUMN public.inv_recepciones_parciales.documento_id
  IS 'Documento con el que se generó la recepcion parcial.';

COMMENT ON COLUMN public.inv_recepciones_parciales.prefijo
  IS 'Prefijo del Documento Creado';

COMMENT ON COLUMN public.inv_recepciones_parciales.numero
  IS 'Numero del Docuemtno Creado';

COMMENT ON COLUMN public.inv_recepciones_parciales.sw_facturado
  IS 'Es un switch que menciona si una recepcion ya fue facturada o no';

COMMENT ON COLUMN public.inv_recepciones_parciales.fecha_registro
  IS 'Fecha de Registro de la Recepcion Parcial
';

COMMENT ON COLUMN public.inv_recepciones_parciales.usuario_id
  IS 'Usuario que Hace el Ingreso';
  
  CREATE TABLE public.inv_recepciones_parciales_d (
  recepcion_parcial_id  integer NOT NULL,
  item_id               serial NOT NULL,
  codigo_producto       varchar(50) NOT NULL,
  cantidad              numeric(18) NOT NULL,
  valor                 numeric(18,2) NOT NULL,
  porc_iva              numeric(3,2) NOT NULL,
  lote                  varchar(255) NOT NULL,
  fecha_vencimiento     date,
  /* Keys */
  CONSTRAINT inv_recepciones_parciales_d_pkey
    PRIMARY KEY (item_id)
) WITH (
    OIDS = FALSE
  );

CREATE TRIGGER alertas_recepcion_productos
  AFTER INSERT OR UPDATE
  ON public.inv_recepciones_parciales_d
  FOR EACH ROW
  EXECUTE PROCEDURE public.inv_alertas_recepcion_productos();

ALTER TABLE public.inv_recepciones_parciales_d
  OWNER TO siis;

COMMENT ON TABLE public.inv_recepciones_parciales_d
  IS 'Detalle de la recepcion Parcial Para Facturar';

COMMENT ON COLUMN public.inv_recepciones_parciales_d.recepcion_parcial_id
  IS 'Campo que nos relaciona con la cabecera de la recepcion parcial';

COMMENT ON COLUMN public.inv_recepciones_parciales_d.item_id
  IS 'Id del Item';

COMMENT ON COLUMN public.inv_recepciones_parciales_d.codigo_producto
  IS 'Codigo Producto Asociado En la Recepcion Parcial';

COMMENT ON COLUMN public.inv_recepciones_parciales_d.cantidad
  IS 'Cantidad de Productos Recibidos';

COMMENT ON COLUMN public.inv_recepciones_parciales_d.valor
  IS 'Valor unitario Del Producto Comprado';

COMMENT ON COLUMN public.inv_recepciones_parciales_d.porc_iva
  IS 'Porcentaje de Iva del producto';

COMMENT ON COLUMN public.inv_recepciones_parciales_d.lote
  IS 'Lote del Producto';

COMMENT ON COLUMN public.inv_recepciones_parciales_d.fecha_vencimiento
  IS 'Fecha de vencimiento del producto';

COMMENT ON TRIGGER alertas_recepcion_productos
  ON public.inv_recepciones_parciales_d
  IS 'Alerta a los usuarios de Compras sobre la recepcion de Productos en la Bodega';