CREATE TABLE public.detalles_despachos (
  detalle_despacho_id  serial NOT NULL,
  /*venta_orden_pedido_id          integer NOT NULL,
  planilla_despacho_id					integer NULL,
  transportadora_id          			integer NULL,*/
  despacho_id	          				integer NULL,
  cantidad_cajas          				integer NOT NULL,
  cantidad_neveras          		    integer NOT NULL,
  temperatura_neveras                   integer NOT NULL,
  peso									integer NOT NULL,
  /*placa_vehiculo           			    character(6) NOT NULL,
  numero_guia							bigint NOT NULL,
  nombre_conductor						character varying(60) NOT NULL,*/
  usuario_id            			    integer NOT NULL,
  fecha_registro        			    timestamp WITHOUT TIME ZONE DEFAULT now(),
  
  /* Keys */
  CONSTRAINT detalles_despachos_pkey
    PRIMARY KEY (detalle_despacho_id),
  /* Foreign keys */
  /*CONSTRAINT detalles_despachos_venta_orden_pedido_id_fkey
    FOREIGN KEY (venta_orden_pedido_id)
    REFERENCES public.ventas_ordenes_pedidos(pedido_cliente_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
	
  CONSTRAINT planillas_despachos_detalles_despachos_fkey
	FOREIGN KEY (planilla_despacho_id)
	REFERENCES planillas_despachos (planilla_despacho_id)
	ON DELETE RESTRICT
	ON UPDATE CASCADE,
	
  CONSTRAINT detalles_despachos_transportadora_id_fkey
    FOREIGN KEY (transportadora_id)
    REFERENCES public.inv_transportadoras(transportadora_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE, */
  CONSTRAINT detalles_despachos_despacho_id_fkey
    FOREIGN KEY (despacho_id)
    REFERENCES public.despachos(despacho_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT detalles_despachos_usuario_id_fkey
    FOREIGN KEY (usuario_id)
    REFERENCES public.system_usuarios(usuario_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.detalles_despachos
  OWNER TO "admin";

COMMENT ON TABLE public.detalles_despachos
  IS 'Tabla que almacena la información detallada del despacho';
  
COMMENT ON COLUMN public.detalles_despachos.detalle_despacho_id
  IS 'PK tabla, número único del detalle del despacho del pedido';
  
COMMENT ON COLUMN public.detalles_despachos.despacho_id
  IS 'Id del despacho al que pertenece el detalle de despacho';

/*COMMENT ON COLUMN public.detalles_despachos.venta_orden_pedido_id
  IS 'Id del pedido de cliente al que pertenece el detalle de despacho';

COMMENT ON COLUMN public.detalles_despachos.planilla_despacho_id
  IS 'Id de la planilla (de despacho) relacionada al (detalle de) despacho';
  
COMMENT ON COLUMN public.detalles_despachos.transportadora_id
  IS 'Id de la transportadora con la cual se hace el despacho';*/

COMMENT ON COLUMN public.detalles_despachos.cantidad_cajas
  IS 'Cantidad de cajas enviadas en el despacho';

COMMENT ON COLUMN public.detalles_despachos.cantidad_neveras
  IS 'Cantidad de neveras enviadas en el despacho';

COMMENT ON COLUMN public.detalles_despachos.temperatura_neveras
  IS 'Temperatura (en grados centigrados) de las neveras enviadas en el despacho';

COMMENT ON COLUMN public.detalles_despachos.peso
  IS 'Peso total de todas las cajas y neveras enviadas en el despcacho';
  
/*COMMENT ON COLUMN public.detalles_despachos.placa_vehiculo
  IS 'Placa del vehículo con el cual se envía el despacho';
  
COMMENT ON COLUMN public.detalles_despachos.numero_guia
  IS 'Número de guía con la cual se envía el despacho';
  
COMMENT ON COLUMN public.detalles_despachos.nombre_conductor
  IS 'Nombre del conductor que maneja el vehículo en el cual se hace el despacho';*/
  
COMMENT ON COLUMN public.detalles_despachos.usuario_id
  IS 'Usuario que registra';

COMMENT ON COLUMN public.detalles_despachos.fecha_registro
  IS 'Fecha de registro';