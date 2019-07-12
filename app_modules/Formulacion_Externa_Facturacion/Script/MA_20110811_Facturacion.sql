CREATE TABLE public.userpermisos_Formulacion_Externa_Facturacion (
  empresa_id    char(2) NOT NULL,
  usuario_id    integer NOT NULL,
  sw_activo     char NOT NULL DEFAULT 1,
  documento_id  integer,
  /* Keys */
  CONSTRAINT userpermisos_Formulacion_Externa_Facturacion_pkey
    PRIMARY KEY (empresa_id, usuario_id),
  /* Foreign keys */
  CONSTRAINT foreign_key01
    FOREIGN KEY (empresa_id, documento_id)
    REFERENCES public.documentos(empresa_id, documento_id)
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

ALTER TABLE public.userpermisos_Formulacion_Externa_Facturacion
  OWNER TO siis;

COMMENT ON TABLE public.userpermisos_Formulacion_Externa_Facturacion
  IS 'Tabla de permisos para la facturacion de la ESM';

COMMENT ON COLUMN public.userpermisos_Formulacion_Externa_Facturacion.empresa_id
  IS 'Empresa que Realiza la Facturacion';

COMMENT ON COLUMN public.userpermisos_Formulacion_Externa_Facturacion.usuario_id
  IS 'Usuario que tiene permiso para ingresar al Modulo';

COMMENT ON COLUMN public.userpermisos_Formulacion_Externa_Facturacion.sw_activo
  IS 'Si el Permiso está activo';

COMMENT ON COLUMN public.userpermisos_Formulacion_Externa_Facturacion.documento_id
  IS 'Documento que se utilizará en la Facturacion';

GRANT SELECT, INSERT, UPDATE, DELETE, REFERENCES, TRIGGER
 ON public.userpermisos_Formulacion_Externa_Facturacion
TO siis;

CREATE TABLE public.Formulacion_Externa_Facturacion_temporal (
  empresa_id       char(2) NOT NULL,
  documento_id     integer NOT NULL,
  fecha_inicio     date NOT NULL,
  fecha_fin        date NOT NULL,
  usuario_id       integer NOT NULL,
  plan_id          integer NOT NULL,
  tipo_id_tercero  varchar(3) NOT NULL,
  tercero_id       varchar(32) NOT NULL,
  tipo_factura     char NOT NULL DEFAULT 2,
  fecha_registro   timestamp(1) WITHOUT TIME ZONE NOT NULL DEFAULT now(),
  /* Keys */
  CONSTRAINT Formulacion_Externa_Facturacion_temporal_pkey
    PRIMARY KEY (empresa_id, documento_id),
  /* Foreign keys */
  CONSTRAINT foreign_key01
    FOREIGN KEY (empresa_id, documento_id)
    REFERENCES public.documentos(empresa_id, documento_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE, 
  CONSTRAINT foreign_key02
    FOREIGN KEY (usuario_id)
    REFERENCES public.system_usuarios(usuario_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE, 
  CONSTRAINT foreign_key03
    FOREIGN KEY (plan_id)
    REFERENCES public.planes(plan_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE, 
  CONSTRAINT foreign_key04
    FOREIGN KEY (tipo_id_tercero, tercero_id)
    REFERENCES public.terceros(tipo_id_tercero, tercero_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.Formulacion_Externa_Facturacion_temporal
  OWNER TO siis;

COMMENT ON TABLE public.Formulacion_Externa_Facturacion_temporal
  IS 'Tabla para Guardar la Facturacion de manera Temporal (Antes de ser generada de manera Real)';

COMMENT ON COLUMN public.Formulacion_Externa_Facturacion_temporal.empresa_id
  IS 'Empresa que Genera el documento';

COMMENT ON COLUMN public.Formulacion_Externa_Facturacion_temporal.documento_id
  IS 'Documento de Factura';

COMMENT ON COLUMN public.Formulacion_Externa_Facturacion_temporal.fecha_inicio
  IS 'Fecha Inicio de la Generacion de la factura';

COMMENT ON COLUMN public.Formulacion_Externa_Facturacion_temporal.fecha_fin
  IS 'Fecha Final del Filtro de Generacion de Factura';

COMMENT ON COLUMN public.Formulacion_Externa_Facturacion_temporal.usuario_id
  IS 'Usuario que genera la factura';

COMMENT ON COLUMN public.Formulacion_Externa_Facturacion_temporal.plan_id
  IS '# del contrato';

COMMENT ON COLUMN public.Formulacion_Externa_Facturacion_temporal.tipo_id_tercero
  IS 'Tercer a quien se le genera la factura';

COMMENT ON COLUMN public.Formulacion_Externa_Facturacion_temporal.tercero_id
  IS 'Identificacion del Tercero a quien se le esta generando la factura';

COMMENT ON COLUMN public.Formulacion_Externa_Facturacion_temporal.tipo_factura
  IS '0 =>es paciente 1=> es cleinte 2 =>es particular 3=>agrupada capitacion 4=>agrupada no capitacion 5=>conceptos 6=>productos inventarios';

COMMENT ON COLUMN public.Formulacion_Externa_Facturacion_temporal.fecha_registro
  IS 'Fecha de creacion del temporal';

GRANT SELECT, INSERT, UPDATE, DELETE, REFERENCES, TRIGGER
 ON public.Formulacion_Externa_Facturacion_temporal
TO siis;


CREATE TABLE public.fe_facturacion_tmpl_despachados (
  empresa_id                 char(2) NOT NULL,
  documento_id               integer NOT NULL,
  codigo_producto            varchar(50) NOT NULL,
  cantidad                   numeric(18) NOT NULL,
  valor                      numeric(20,4) NOT NULL,
  valor_unitario             numeric(18,4) NOT NULL,
  porcentaje_intermediacion  numeric(9,2) NOT NULL,
  sw_bodegamindefensa        char NOT NULL DEFAULT 0,
  sw_entregado_off           char NOT NULL DEFAULT 0,
  /* Keys */
  CONSTRAINT formulacion_externa_facturacion_temporal_despachados_pkey
    PRIMARY KEY (empresa_id, documento_id, codigo_producto, sw_bodegamindefensa, sw_entregado_off),
  /* Foreign keys */
  CONSTRAINT foreign_key01
    FOREIGN KEY (empresa_id, documento_id)
    REFERENCES public.formulacion_externa_facturacion_temporal(empresa_id, documento_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE, 
  CONSTRAINT foreign_key02
    FOREIGN KEY (codigo_producto)
    REFERENCES public.inventarios_productos(codigo_producto)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.fe_facturacion_tmpl_despachados
  OWNER TO siis;

COMMENT ON TABLE public.fe_facturacion_tmpl_despachados
  IS 'Agrupacion de Productos en Despachos a las ESM';

COMMENT ON COLUMN public.fe_facturacion_tmpl_despachados.empresa_id
  IS 'Empresa que Genera la Factura';

COMMENT ON COLUMN public.fe_facturacion_tmpl_despachados.documento_id
  IS 'Documento Factura de la Empresa';

COMMENT ON COLUMN public.fe_facturacion_tmpl_despachados.codigo_producto
  IS 'Codigo del Producto';

COMMENT ON COLUMN public.fe_facturacion_tmpl_despachados.cantidad
  IS 'Cantidad de productos trasladados';

COMMENT ON COLUMN public.fe_facturacion_tmpl_despachados.valor
  IS 'Valor total del producto';

COMMENT ON COLUMN public.fe_facturacion_tmpl_despachados.valor_unitario
  IS 'Valor Unitario del producto';

COMMENT ON COLUMN public.fe_facturacion_tmpl_despachados.porcentaje_intermediacion
  IS 'Porcentaje de Intermediacion del Producto';

COMMENT ON COLUMN public.fe_facturacion_tmpl_despachados.sw_bodegamindefensa
  IS 'Define si el producto ingresado hace parte de una bodega de mindefensa';

COMMENT ON COLUMN public.fe_facturacion_tmpl_despachados.sw_entregado_off
  IS 'Define si el producto fue entregado a tiempo o no';

GRANT SELECT, INSERT, UPDATE, DELETE, REFERENCES, TRIGGER
 ON public.fe_facturacion_tmpl_despachados
TO siis;



CREATE TABLE public.fe_facturacion_tmpl_dispensados (
  empresa_id                 char(2) NOT NULL,
  documento_id               integer NOT NULL,
  codigo_producto            varchar(50) NOT NULL,
  cantidad                   numeric(18) NOT NULL,
  valor                      numeric(20,4) NOT NULL,
  valor_unitario             numeric(18,4) NOT NULL,
  porcentaje_intermediacion  numeric(9,2) NOT NULL,
  /* Keys */
  CONSTRAINT formulacion_externa_facturacion_temporal_dispensados_pkey
    PRIMARY KEY (empresa_id, documento_id, codigo_producto),
  /* Foreign keys */
  CONSTRAINT foreign_key01
    FOREIGN KEY (empresa_id, documento_id)
    REFERENCES public.formulacion_externa_facturacion_temporal(empresa_id, documento_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE, 
  CONSTRAINT foreign_key02
    FOREIGN KEY (codigo_producto)
    REFERENCES public.inventarios_productos(codigo_producto)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.fe_facturacion_tmpl_dispensados
  OWNER TO siis;

COMMENT ON TABLE public.fe_facturacion_tmpl_dispensados
  IS 'Agrupacion de Productos en Despachos a las ESM';

COMMENT ON COLUMN public.fe_facturacion_tmpl_dispensados.empresa_id
  IS 'Empresa que Genera la Factura';

COMMENT ON COLUMN public.fe_facturacion_tmpl_dispensados.documento_id
  IS 'Documento Factura de la Empresa';

COMMENT ON COLUMN public.fe_facturacion_tmpl_dispensados.codigo_producto
  IS 'Codigo del Producto';

COMMENT ON COLUMN public.fe_facturacion_tmpl_dispensados.cantidad
  IS 'Cantidad de productos trasladados';

COMMENT ON COLUMN public.fe_facturacion_tmpl_dispensados.valor
  IS 'Valor total del producto';

COMMENT ON COLUMN public.fe_facturacion_tmpl_dispensados.valor_unitario
  IS 'Valor Unitario del producto';

COMMENT ON COLUMN public.fe_facturacion_tmpl_dispensados.porcentaje_intermediacion
  IS 'Porcentaje de Intermediacion del Producto';

GRANT SELECT, INSERT, UPDATE, DELETE, REFERENCES, TRIGGER
 ON public.fe_facturacion_tmpl_dispensados
TO siis;


/*
CREATE TABLE public.fe_facturacion_tmpl_dispensacion_pendientes (
  empresa_id                 char(2) NOT NULL,
  documento_id               integer NOT NULL,
  codigo_producto            varchar(50) NOT NULL,
  cantidad                   numeric(18) NOT NULL,
  valor                      numeric(20,4) NOT NULL,
  valor_unitario             numeric(18,4) NOT NULL,
  porcentaje_intermediacion  numeric(9,2) NOT NULL,
 
  CONSTRAINT fe_facturacion_tmpl_dispensacion_pendientes_pkey
    PRIMARY KEY (empresa_id, documento_id, codigo_producto),
  
  CONSTRAINT fe_facturacion_tmpl_dispensacion_pendiente_codigo_producto_fkey
    FOREIGN KEY (codigo_producto)
    REFERENCES public.inventarios_productos(codigo_producto)
    ON DELETE RESTRICT
    ON UPDATE CASCADE, 
  CONSTRAINT fe_facturacion_tmpl_dispensacion_pendientes_empresa_id_fkey
    FOREIGN KEY (empresa_id, documento_id)
    REFERENCES public.formulacion_externa_facturacion_temporal(empresa_id, documento_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.fe_facturacion_tmpl_dispensacion_pendientes
  OWNER TO siis;

COMMENT ON TABLE public.fe_facturacion_tmpl_dispensacion_pendientes
  IS 'Agrupacion de Productos en Despachos a las ESM';

COMMENT ON COLUMN public.fe_facturacion_tmpl_dispensacion_pendientes.empresa_id
  IS 'Empresa que Genera la Factura';

COMMENT ON COLUMN public.fe_facturacion_tmpl_dispensacion_pendientes.documento_id
  IS 'Documento Factura de la Empresa';

COMMENT ON COLUMN public.fe_facturacion_tmpl_dispensacion_pendientes.codigo_producto
  IS 'Codigo del Producto';

COMMENT ON COLUMN public.fe_facturacion_tmpl_dispensacion_pendientes.cantidad
  IS 'Cantidad de productos trasladados';

COMMENT ON COLUMN public.fe_facturacion_tmpl_dispensacion_pendientes.valor
  IS 'Valor total del producto';

COMMENT ON COLUMN public.fe_facturacion_tmpl_dispensacion_pendientes.valor_unitario
  IS 'Valor unitario del Producto';

COMMENT ON COLUMN public.fe_facturacion_tmpl_dispensacion_pendientes.porcentaje_intermediacion
  IS 'Porcentaje de Intermediacion del contrato';

GRANT SELECT, INSERT, UPDATE, DELETE, REFERENCES, TRIGGER
 ON public.fe_facturacion_tmpl_dispensacion_pendientes
TO siis;

*/

CREATE TABLE public.fe_facturacion_tmpl_dispensacion_pendientes (
  empresa_id                 char(2) NOT NULL,
  documento_id               integer NOT NULL,
  codigo_producto            varchar(50) NOT NULL,
  cantidad                   numeric(18) NOT NULL,
  valor                      numeric(20,4) NOT NULL,
  valor_unitario             numeric(18,4) NOT NULL,
  porcentaje_intermediacion  numeric(9,2) NOT NULL
) WITH (
    OIDS = FALSE
  );

  /* Keys */
  
  ALTER TABLE fe_facturacion_tmpl_dispensacion_pendientes ADD  PRIMARY KEY (empresa_id, documento_id, codigo_producto);
  /* Foreign keys */
  ALTER TABLE fe_facturacion_tmpl_dispensacion_pendientes ADD FOREIGN KEY (empresa_id, documento_id)
    REFERENCES public.Formulacion_Externa_Facturacion_temporal(empresa_id, documento_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE;
  ALTER TABLE fe_facturacion_tmpl_dispensacion_pendientes ADD FOREIGN KEY (codigo_producto)
    REFERENCES public.inventarios_productos(codigo_producto)
    ON DELETE RESTRICT
    ON UPDATE CASCADE;
	
ALTER TABLE public.fe_facturacion_tmpl_dispensacion_pendientes
  OWNER TO siis;

COMMENT ON TABLE public.fe_facturacion_tmpl_dispensacion_pendientes
  IS 'Agrupacion de Productos en Despachos a las ESM';

COMMENT ON COLUMN public.fe_facturacion_tmpl_dispensacion_pendientes.empresa_id
  IS 'Empresa que Genera la Factura';

COMMENT ON COLUMN public.fe_facturacion_tmpl_dispensacion_pendientes.documento_id
  IS 'Documento Factura de la Empresa';

COMMENT ON COLUMN public.fe_facturacion_tmpl_dispensacion_pendientes.codigo_producto
  IS 'Codigo del Producto';

COMMENT ON COLUMN public.fe_facturacion_tmpl_dispensacion_pendientes.cantidad
  IS 'Cantidad de productos trasladados';

COMMENT ON COLUMN public.fe_facturacion_tmpl_dispensacion_pendientes.valor
  IS 'Valor total del producto';

COMMENT ON COLUMN public.fe_facturacion_tmpl_dispensacion_pendientes.valor_unitario
  IS 'Valor unitario del Producto';

COMMENT ON COLUMN public.fe_facturacion_tmpl_dispensacion_pendientes.porcentaje_intermediacion
  IS 'Porcentaje de Intermediacion del contrato';

GRANT SELECT, INSERT, UPDATE, DELETE, REFERENCES, TRIGGER
 ON public.fe_facturacion_tmpl_dispensacion_pendientes
TO siis;

ALTER TABLE public.fe_facturacion_tmpl_despachados
  RENAME TO fe_facturacion_tmpl_despachados;

  ALTER TABLE public.fe_facturacion_tmpl_dispensados
  RENAME TO fe_facturacion_tmpl_dispensados;

CREATE TABLE public.fe_facturacion_tmpl_traslados (
  empresa_id                 char(2) NOT NULL,
  documento_id               integer NOT NULL,
  codigo_producto            varchar(50) NOT NULL,
  cantidad                   numeric(18) NOT NULL,
  valor                      numeric(20,4) NOT NULL,
  valor_unitario             numeric(18,4) NOT NULL,
  porcentaje_intermediacion  numeric(9,2) NOT NULL,
  sw_bodegamindefensa        char NOT NULL,
  sw_entregado_off           char NOT NULL DEFAULT 0,
  /* Keys */
  CONSTRAINT fe_facturacion_tmpl_traslados_pkey
    PRIMARY KEY (empresa_id, documento_id, codigo_producto, sw_bodegamindefensa, sw_entregado_off),
  /* Foreign keys */
  CONSTRAINT foreign_key01
    FOREIGN KEY (empresa_id, documento_id)
    REFERENCES public.esm_facturacion_temporal(empresa_id, documento_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE, 
  CONSTRAINT foreign_key02
    FOREIGN KEY (codigo_producto)
    REFERENCES public.inventarios_productos(codigo_producto)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.fe_facturacion_tmpl_traslados
  OWNER TO siis;

COMMENT ON TABLE public.fe_facturacion_tmpl_traslados
  IS 'Agrupacion de Productos en traslados ESM';

COMMENT ON COLUMN public.fe_facturacion_tmpl_traslados.empresa_id
  IS 'Empresa que Genera la Factura';

COMMENT ON COLUMN public.fe_facturacion_tmpl_traslados.documento_id
  IS 'Documento Factura de la Empresa';

COMMENT ON COLUMN public.fe_facturacion_tmpl_traslados.codigo_producto
  IS 'Codigo del Producto';

COMMENT ON COLUMN public.fe_facturacion_tmpl_traslados.cantidad
  IS 'Cantidad de productos trasladados';

COMMENT ON COLUMN public.fe_facturacion_tmpl_traslados.valor
  IS 'Valor total del producto';

COMMENT ON COLUMN public.fe_facturacion_tmpl_traslados.valor_unitario
  IS 'Valor Unitario del Producto';

COMMENT ON COLUMN public.fe_facturacion_tmpl_traslados.porcentaje_intermediacion
  IS 'Porcentaje de Intermediacion en caso de que no sea pactado o lo requiera';

COMMENT ON COLUMN public.fe_facturacion_tmpl_traslados.sw_bodegamindefensa
  IS 'Define si El producto salió de la bodega de Mindefensa';

COMMENT ON COLUMN public.fe_facturacion_tmpl_traslados.sw_entregado_off
  IS 'Define si el producto fue entregado a tiempo o no';

GRANT SELECT, INSERT, UPDATE, DELETE, REFERENCES, TRIGGER
 ON public.fe_facturacion_tmpl_traslados
TO siis;

  