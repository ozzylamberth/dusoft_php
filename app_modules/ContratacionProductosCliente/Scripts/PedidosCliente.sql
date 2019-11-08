CREATE TABLE public.ventas_ordenes_pedidos_tmp (
  pedido_cliente_id_tmp  serial NOT NULL,
  empresa_id             varchar(2) NOT NULL,
  tipo_id_tercero        varchar(3) NOT NULL,
  tercero_id             varchar(32) NOT NULL,
  fecha_registro         date NOT NULL,
  usuario_id             integer NOT NULL,
  fecha_envio            date,
  /* Keys */
  CONSTRAINT ventas_ordenes_pedidos_tmp_pkey
    PRIMARY KEY (pedido_cliente_id_tmp)
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.ventas_ordenes_pedidos_tmp
  OWNER TO siis;

COMMENT ON TABLE public.ventas_ordenes_pedidos_tmp
  IS 'Es la tabla que permite crear Pedidos de Clientes de Manera Temporal... Esto Permite Que Mientras no sea el Documento de Pedido Real Puede ser Modificado';

COMMENT ON COLUMN public.ventas_ordenes_pedidos_tmp.pedido_cliente_id_tmp
  IS 'Serial ID del Documento Temporal. Llave primaria';

COMMENT ON COLUMN public.ventas_ordenes_pedidos_tmp.empresa_id
  IS 'Empresa que genera el Pedido del Cliente';

COMMENT ON COLUMN public.ventas_ordenes_pedidos_tmp.tipo_id_tercero
  IS 'Tipo de Identificacion de un tercero';

COMMENT ON COLUMN public.ventas_ordenes_pedidos_tmp.tercero_id
  IS 'Identificacion del Tercero';

COMMENT ON COLUMN public.ventas_ordenes_pedidos_tmp.fecha_registro
  IS 'Fecha que hacen el documento';

COMMENT ON COLUMN public.ventas_ordenes_pedidos_tmp.usuario_id
  IS 'Usuario Que realiza el Documento';

COMMENT ON COLUMN public.ventas_ordenes_pedidos_tmp.fecha_envio
  IS 'Fecha en la que el Cliente ha solictado el envío';




CREATE TABLE public.ventas_ordenes_pedidos_d_tmp (
  item_id                serial NOT NULL,
  pedido_cliente_id_tmp  integer NOT NULL,
  codigo_producto        varchar(50) NOT NULL,
  porc_iva               numeric(5,3) NOT NULL,
  numero_unidades        integer NOT NULL,
  valor_unitario         numeric(15,2) NOT NULL,
  fecha_registro         timestamp(1) WITHOUT TIME ZONE NOT NULL DEFAULT now(),
  usuario_id             integer NOT NULL,
  /* Keys */
  CONSTRAINT ventas_ordenes_pedidos_d_tmp_pkey
    PRIMARY KEY (item_id)
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.ventas_ordenes_pedidos_d_tmp
  OWNER TO siis;

COMMENT ON TABLE public.ventas_ordenes_pedidos_d_tmp
  IS 'Es el detalle de los pedidos temporales creados';

COMMENT ON COLUMN public.ventas_ordenes_pedidos_d_tmp.item_id
  IS 'Es la llave primaria del item de un pedido';

COMMENT ON COLUMN public.ventas_ordenes_pedidos_d_tmp.pedido_cliente_id_tmp
  IS 'Es el Pedido Temporal (foranea)';

COMMENT ON COLUMN public.ventas_ordenes_pedidos_d_tmp.codigo_producto
  IS 'Es el Producto inscrito en el pedido de un cliente';

COMMENT ON COLUMN public.ventas_ordenes_pedidos_d_tmp.porc_iva
  IS 'Es el Iva del Producto';

COMMENT ON COLUMN public.ventas_ordenes_pedidos_d_tmp.numero_unidades
  IS 'Numero de Unidades Solicitadas por el Cliente';

COMMENT ON COLUMN public.ventas_ordenes_pedidos_d_tmp.valor_unitario
  IS 'Es el valor unitario del producto, calculado segun el contrato inscrito en el sistema';

COMMENT ON COLUMN public.ventas_ordenes_pedidos_d_tmp.fecha_registro
  IS 'Fecha de Registro';

COMMENT ON COLUMN public.ventas_ordenes_pedidos_d_tmp.usuario_id
  IS 'Usuario que registra el Item';

  
CREATE TABLE public.ventas_ordenes_pedidos (
  pedido_cliente_id  serial NOT NULL,
  empresa_id         varchar(2) NOT NULL,
  tipo_id_tercero    varchar(3) NOT NULL,
  tercero_id         varchar(32) NOT NULL,
  fecha_registro     date NOT NULL,
  usuario_id         integer NOT NULL,
  fecha_envio        date,
  /* Keys */
  CONSTRAINT ventas_ordenes_pedidos_pkey
    PRIMARY KEY (pedido_cliente_id)
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.ventas_ordenes_pedidos
  OWNER TO siis;

COMMENT ON TABLE public.ventas_ordenes_pedidos
  IS 'Es la tabla que permite crear Pedidos de Clientes';

COMMENT ON COLUMN public.ventas_ordenes_pedidos.pedido_cliente_id
  IS 'Serial ID del Documento. Llave primaria';

COMMENT ON COLUMN public.ventas_ordenes_pedidos.empresa_id
  IS 'Empresa que genera el Pedido del Cliente';

COMMENT ON COLUMN public.ventas_ordenes_pedidos.tipo_id_tercero
  IS 'Tipo de Identificacion de un tercero';

COMMENT ON COLUMN public.ventas_ordenes_pedidos.tercero_id
  IS 'Identificacion del Tercero';

COMMENT ON COLUMN public.ventas_ordenes_pedidos.fecha_registro
  IS 'Fecha que hacen el documento';

COMMENT ON COLUMN public.ventas_ordenes_pedidos.usuario_id
  IS 'Usuario Que realiza el Documento';

COMMENT ON COLUMN public.ventas_ordenes_pedidos.fecha_envio
  IS 'Fecha en la que el Cliente ha solictado el envío';



CREATE TABLE public.ventas_ordenes_pedidos_d (
  item_id            serial NOT NULL,
  pedido_cliente_id  integer NOT NULL,
  codigo_producto    varchar(50) NOT NULL,
  porc_iva           numeric(5,3) NOT NULL,
  numero_unidades    integer NOT NULL,
  valor_unitario     numeric(15,2) NOT NULL,
  fecha_registro     timestamp(1) WITHOUT TIME ZONE NOT NULL DEFAULT now(),
  usuario_id         integer NOT NULL,
  /* Keys */
  CONSTRAINT ventas_ordenes_pedidos_d_pkey
    PRIMARY KEY (item_id)
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.ventas_ordenes_pedidos_d
  OWNER TO siis;

COMMENT ON TABLE public.ventas_ordenes_pedidos_d
  IS 'Es el detalle de los pedidos creados';

COMMENT ON COLUMN public.ventas_ordenes_pedidos_d.item_id
  IS 'Es la llave primaria del item de un pedido';

COMMENT ON COLUMN public.ventas_ordenes_pedidos_d.pedido_cliente_id
  IS 'Es el Pedido (foranea)';

COMMENT ON COLUMN public.ventas_ordenes_pedidos_d.codigo_producto
  IS 'Es el Producto inscrito en el pedido de un cliente';

COMMENT ON COLUMN public.ventas_ordenes_pedidos_d.porc_iva
  IS 'Es el Iva del Producto';

COMMENT ON COLUMN public.ventas_ordenes_pedidos_d.numero_unidades
  IS 'Numero de Unidades Solicitadas por el Cliente';

COMMENT ON COLUMN public.ventas_ordenes_pedidos_d.valor_unitario
  IS 'Es el valor unitario del producto, calculado segun el contrato inscrito en el sistema';

COMMENT ON COLUMN public.ventas_ordenes_pedidos_d.fecha_registro
  IS 'Fecha de Registro';

COMMENT ON COLUMN public.ventas_ordenes_pedidos_d.usuario_id
  IS 'Usuario que registra el Item';  