CREATE TABLE public.userpermisos_autorizacionesingresoproductos (
  usuario_id         integer NOT NULL,
  indice_automatico  serial NOT NULL PRIMARY KEY,
  empresa_id         char(2) NOT NULL
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.userpermisos_autorizacionesingresoproductos
  OWNER TO siis;

COMMENT ON COLUMN public.userpermisos_autorizacionesingresoproductos.usuario_id
  IS 'Usuario con permisos';

 CREATE TABLE public.compras_ordenes_pedidos_productosfoc (
  orden_pedido_id           integer NOT NULL,
  codigo_producto           varchar(50) NOT NULL,
  usuario_id                integer NOT NULL,
  justificacion_ingreso     text NOT NULL,
  fecha_ingreso             date NOT NULL,
  sw_autorizado             varchar(1) NOT NULL DEFAULT 0,
  usuario_id_autorizador    integer,
  observacion_autorizacion  text,
  doc_tmp_id                integer NOT NULL,
  empresa_id                varchar(3) NOT NULL,
  centro_utilidad           varchar(3) NOT NULL,
  bodega                    varchar(2) NOT NULL,
  cantidad                  numeric(14),
  lote                      varchar(30) NOT NULL,
  fecha_vencimiento         date NOT NULL,
  porcentaje_gravamen       numeric(9,2),
  total_costo               numeric(12,2) NOT NULL,
  local_prod                varchar(50) NOT NULL,
  item_id                   integer,
  usuario_id_autorizador_2  integer,
  valor_unitario_compra     numeric(18,2),
  valor_unitario_factura    numeric(18,2),
  /* Keys */
  CONSTRAINT compras_ordenes_pedidos_productosfoc_pkey
    PRIMARY KEY (orden_pedido_id, codigo_producto, empresa_id, centro_utilidad, bodega, lote),
  /* Foreign keys */
  CONSTRAINT foreign_key01
    FOREIGN KEY (codigo_producto, empresa_id)
    REFERENCES public.inventarios(codigo_producto, empresa_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE, 
  CONSTRAINT foreign_key02
    FOREIGN KEY (bodega, centro_utilidad, empresa_id)
    REFERENCES public.bodegas(bodega, centro_utilidad, empresa_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE, 
  CONSTRAINT foreign_key03
    FOREIGN KEY (orden_pedido_id)
    REFERENCES public.compras_ordenes_pedidos(orden_pedido_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE, 
  CONSTRAINT foreign_key04
    FOREIGN KEY (doc_tmp_id, usuario_id)
    REFERENCES public.inv_bodegas_movimiento_tmp(doc_tmp_id, usuario_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.compras_ordenes_pedidos_productosfoc
  OWNER TO siis;

COMMENT ON TABLE public.compras_ordenes_pedidos_productosfoc
  IS 'Tabla que permitirá relacionar productos que no estan relacionados en una orden de compra y desea que sean ingresados al inventario. Tambien, guardan la autorizacion de ingreso de los mismos';

COMMENT ON COLUMN public.compras_ordenes_pedidos_productosfoc.orden_pedido_id
  IS 'Orden de Pedido Original';

COMMENT ON COLUMN public.compras_ordenes_pedidos_productosfoc.codigo_producto
  IS 'Producto fuera de la orden de compra';

COMMENT ON COLUMN public.compras_ordenes_pedidos_productosfoc.usuario_id
  IS 'Usuario Encargado del ingreso a Bodega';

COMMENT ON COLUMN public.compras_ordenes_pedidos_productosfoc.justificacion_ingreso
  IS 'Texto de justificacion del ingreso y Observaciones Adicionales!!!';

COMMENT ON COLUMN public.compras_ordenes_pedidos_productosfoc.fecha_ingreso
  IS 'Fecha y hora del Ingreso.';

COMMENT ON COLUMN public.compras_ordenes_pedidos_productosfoc.sw_autorizado
  IS 'Estado de la autorizacion 1- si 0- no';

COMMENT ON COLUMN public.compras_ordenes_pedidos_productosfoc.usuario_id_autorizador
  IS 'usuario q autoriza el ingreso del producto';

COMMENT ON COLUMN public.compras_ordenes_pedidos_productosfoc.observacion_autorizacion
  IS 'texto del autorizador';

COMMENT ON COLUMN public.compras_ordenes_pedidos_productosfoc.doc_tmp_id
  IS 'Es el Documento Temporal, donde se relacionará el producto a ingresar..';

COMMENT ON COLUMN public.compras_ordenes_pedidos_productosfoc.empresa_id
  IS 'Identificador de la empresa que realiza la transaccion';

COMMENT ON COLUMN public.compras_ordenes_pedidos_productosfoc.lote
  IS 'Lote del Producto donde que pide autorizacion para el ingreso';

COMMENT ON COLUMN public.compras_ordenes_pedidos_productosfoc.fecha_vencimiento
  IS 'Fecha de vencimiento del producto a ingresar';

COMMENT ON COLUMN public.compras_ordenes_pedidos_productosfoc.porcentaje_gravamen
  IS 'porcentaje del producto a ingresar';

COMMENT ON COLUMN public.compras_ordenes_pedidos_productosfoc.total_costo
  IS 'Costo del producto que será Ingresado!!!';

COMMENT ON COLUMN public.compras_ordenes_pedidos_productosfoc.local_prod
  IS 'Localizacion del Producto en Bodega';

COMMENT ON COLUMN public.compras_ordenes_pedidos_productosfoc.item_id
  IS 'Es un campo, en caso de que el producto que esté en una orden de compra, solicite el ingreso al documento temporal. Generalmente se presenta si un producto requiere un ingreso por fecha de vencimiento';

COMMENT ON COLUMN public.compras_ordenes_pedidos_productosfoc.valor_unitario_compra
  IS 'Es el valor unitario del producto que se está ingresando';

COMMENT ON COLUMN public.compras_ordenes_pedidos_productosfoc.valor_unitario_factura
  IS 'Es el valor unitario con el que viene en la factura de venta del proveedor';
