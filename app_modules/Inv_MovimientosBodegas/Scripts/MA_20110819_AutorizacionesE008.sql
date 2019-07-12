CREATE TABLE public.inv_bodegas_movimiento_tmp_autorizaciones_despachos (
  doc_tmp_id              integer NOT NULL,
  usuario_id              integer NOT NULL,
  empresa_id              char(2) NOT NULL,
  centro_utilidad         char(2) NOT NULL,
  bodega                  char(2) NOT NULL,
  codigo_producto         varchar(50) NOT NULL,
  lote                    varchar(100) NOT NULL,
  fecha_vencimiento       date NOT NULL,
  cantidad                numeric(14,4) NOT NULL DEFAULT 0,
  porcentaje_gravamen     numeric(9,2) NOT NULL DEFAULT 0,
  total_costo             double precision NOT NULL DEFAULT 0,
  usuario_id_autorizador  integer,
  observacion             text,
  fecha_registro          timestamp(1) WITHOUT TIME ZONE NOT NULL DEFAULT now(),
  fecha_autorizacion      timestamp(1) WITHOUT TIME ZONE,
  sw_autorizado           char NOT NULL DEFAULT 0,
  /* Keys */
  CONSTRAINT inv_bodegas_movimiento_tmp_autorizaciones_despachos_pkey
    PRIMARY KEY (doc_tmp_id, usuario_id, empresa_id, centro_utilidad, bodega, codigo_producto, lote, fecha_vencimiento),
  /* Foreign keys */
  CONSTRAINT foreign_key01
    FOREIGN KEY (doc_tmp_id, usuario_id)
    REFERENCES public.inv_bodegas_movimiento_tmp(doc_tmp_id, usuario_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE, 
  CONSTRAINT foreign_key02
    FOREIGN KEY (empresa_id, centro_utilidad, bodega, codigo_producto)
    REFERENCES public.existencias_bodegas(empresa_id, centro_utilidad, bodega, codigo_producto)
    ON DELETE RESTRICT
    ON UPDATE CASCADE, 
  CONSTRAINT foreign_key03
    FOREIGN KEY (usuario_id_autorizador)
    REFERENCES public.system_usuarios(usuario_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE, 
  CONSTRAINT foreign_key04
    FOREIGN KEY (empresa_id, centro_utilidad, bodega)
    REFERENCES public.bodegas(empresa_id, centro_utilidad, bodega)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.inv_bodegas_movimiento_tmp_autorizaciones_despachos
  OWNER TO siis;

COMMENT ON TABLE public.inv_bodegas_movimiento_tmp_autorizaciones_despachos
  IS 'Tabla que permite registrar las autorizaciones Solicitadas y Otorgadas de Medicamentos que la requieren para el despacho';

COMMENT ON COLUMN public.inv_bodegas_movimiento_tmp_autorizaciones_despachos.doc_tmp_id
  IS 'Numero del Documento Temporal Donde fue solicitada la autorizacion';

COMMENT ON COLUMN public.inv_bodegas_movimiento_tmp_autorizaciones_despachos.usuario_id
  IS 'Usuario que Solicita la Autorizacion';

COMMENT ON COLUMN public.inv_bodegas_movimiento_tmp_autorizaciones_despachos.empresa_id
  IS 'Empresa donde se Intenta despachar el producto';

COMMENT ON COLUMN public.inv_bodegas_movimiento_tmp_autorizaciones_despachos.centro_utilidad
  IS 'Centro de Utilidad donde se Intenta despachar un producto';

COMMENT ON COLUMN public.inv_bodegas_movimiento_tmp_autorizaciones_despachos.bodega
  IS 'Bodega de Donde se Intenta despachar un producto';

COMMENT ON COLUMN public.inv_bodegas_movimiento_tmp_autorizaciones_despachos.codigo_producto
  IS 'Producto que se Intenta despachar';

COMMENT ON COLUMN public.inv_bodegas_movimiento_tmp_autorizaciones_despachos.lote
  IS 'Lote de Un producto que se Intenta despachar';

COMMENT ON COLUMN public.inv_bodegas_movimiento_tmp_autorizaciones_despachos.fecha_vencimiento
  IS 'Fecha de vencimiento del producto que se intenta despachar';

COMMENT ON COLUMN public.inv_bodegas_movimiento_tmp_autorizaciones_despachos.cantidad
  IS 'Cantidad de Productos q se intentan despachar';

COMMENT ON COLUMN public.inv_bodegas_movimiento_tmp_autorizaciones_despachos.porcentaje_gravamen
  IS 'Porcentaje de Gravamen del Producto que se intenta despachar';

COMMENT ON COLUMN public.inv_bodegas_movimiento_tmp_autorizaciones_despachos.total_costo
  IS 'Costo Total del Producto que se intenta despachar';

COMMENT ON COLUMN public.inv_bodegas_movimiento_tmp_autorizaciones_despachos.usuario_id_autorizador
  IS 'Usuario que Autoriza el despacho';

COMMENT ON COLUMN public.inv_bodegas_movimiento_tmp_autorizaciones_despachos.observacion
  IS 'Observacion de la autorizacion';

COMMENT ON COLUMN public.inv_bodegas_movimiento_tmp_autorizaciones_despachos.fecha_registro
  IS 'Fecha de Registro de la solicitud de autorizacion';

COMMENT ON COLUMN public.inv_bodegas_movimiento_tmp_autorizaciones_despachos.fecha_autorizacion
  IS 'Fecha de la autorizacion';

COMMENT ON COLUMN public.inv_bodegas_movimiento_tmp_autorizaciones_despachos.sw_autorizado
  IS '(1) Autorizado, (0) Aun No Autorizado';

  /*
  TRIGGER QUE INGRESA EL PRODUCTO AL TEMPORAL DESPUES DE SER AUTORIZADO
  */
CREATE OR REPLACE FUNCTION public.aut_productos_autorizados()
RETURNS trigger AS
$$
BEGIN

	IF OLD.sw_autorizado = '0' AND NEW.sw_autorizado = '1' THEN
		INSERT INTO inv_bodegas_movimiento_tmp_d	(
		item_id,
		usuario_id, 
		doc_tmp_id, 	
		empresa_id, 	
		centro_utilidad, 	
		bodega, 	
		codigo_producto, 	
		cantidad, 	
		porcentaje_gravamen, 	
		total_costo, 	
		fecha_vencimiento, 	
		lote)
		VALUES(
					DEFAULT,
					NEW.usuario_id,
					NEW.doc_tmp_id,
					NEW.empresa_id,
					NEW.centro_utilidad,
					NEW.bodega,
					NEW.codigo_producto,
					NEW.cantidad,
					NEW.porcentaje_gravamen,
					NEW.total_costo,
					NEW.fecha_vencimiento,
					NEW.lote
					);
	
	END IF;

	return NEW;

END;
$$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER;

ALTER FUNCTION public.aut_productos_autorizados()
OWNER TO "admin";

CREATE TRIGGER aut_productos_autorizados
  AFTER UPDATE
  ON public.inv_bodegas_movimiento_tmp_autorizaciones_despachos
  FOR EACH ROW
  EXECUTE PROCEDURE public.aut_productos_autorizados();

COMMENT ON TRIGGER aut_productos_autorizados
  ON public.inv_bodegas_movimiento_tmp_autorizaciones_despachos
  IS 'trigger que permite Ingresar el producto AL DOCUMENTO TEMPORAL, despues de ser autorizado';


  CREATE TABLE public.inv_bodegas_movimiento_autorizaciones_despachos (
  autorizacion_id         serial NOT NULL PRIMARY KEY,
  empresa_id              char(2) NOT NULL,
  prefijo                 varchar(4) NOT NULL,
  numero                  integer NOT NULL,
  centro_utilidad         char(2) NOT NULL,
  bodega                  char(2) NOT NULL,
  codigo_producto         varchar(50) NOT NULL,
  lote                    char(100) NOT NULL,
  fecha_vencimiento       date NOT NULL,
  cantidad                numeric(14,4) NOT NULL DEFAULT 0,
  porcentaje_gravamen     numeric(9,2) NOT NULL DEFAULT 0,
  total_costo             double precision NOT NULL DEFAULT 0,
  fecha_registro          timestamp(1) WITHOUT TIME ZONE NOT NULL,
  usuario_id_autorizador  integer NOT NULL,
  observacion             text NOT NULL,
  fecha_autorizacion      timestamp(1) WITHOUT TIME ZONE NOT NULL,
  /* Foreign keys */
  CONSTRAINT foreign_key01
    FOREIGN KEY (empresa_id, prefijo, numero)
    REFERENCES public.inv_bodegas_movimiento(empresa_id, prefijo, numero)
    ON DELETE RESTRICT
    ON UPDATE CASCADE, 
  CONSTRAINT foreign_key02
    FOREIGN KEY (empresa_id, centro_utilidad, bodega)
    REFERENCES public.bodegas(empresa_id, centro_utilidad, bodega)
    ON DELETE RESTRICT
    ON UPDATE CASCADE, 
  CONSTRAINT foreign_key03
    FOREIGN KEY (empresa_id, centro_utilidad, bodega, codigo_producto)
    REFERENCES public.existencias_bodegas(empresa_id, centro_utilidad, bodega, codigo_producto)
    ON DELETE RESTRICT
    ON UPDATE CASCADE, 
  CONSTRAINT foreign_key04
    FOREIGN KEY (usuario_id_autorizador)
    REFERENCES public.system_usuarios(usuario_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.inv_bodegas_movimiento_autorizaciones_despachos
  OWNER TO siis;

COMMENT ON TABLE public.inv_bodegas_movimiento_autorizaciones_despachos
  IS 'Tabla que permite Guardar las autorizaciones de Despachos de Productos';

COMMENT ON COLUMN public.inv_bodegas_movimiento_autorizaciones_despachos.autorizacion_id
  IS 'Consecutivod e Autorizaciones';

COMMENT ON COLUMN public.inv_bodegas_movimiento_autorizaciones_despachos.empresa_id
  IS 'Empresa que Generò el documento';

COMMENT ON COLUMN public.inv_bodegas_movimiento_autorizaciones_despachos.prefijo
  IS 'prefijo del documento creado';

COMMENT ON COLUMN public.inv_bodegas_movimiento_autorizaciones_despachos.numero
  IS 'Numero del documento creado en bodega Asociado a las autorizaciones';

COMMENT ON COLUMN public.inv_bodegas_movimiento_autorizaciones_despachos.centro_utilidad
  IS 'Centro de Utilidad donde fue autorizado/despachado un producto';

COMMENT ON COLUMN public.inv_bodegas_movimiento_autorizaciones_despachos.bodega
  IS 'Bodega donde se hace Autorizacion/despacho de items';

COMMENT ON COLUMN public.inv_bodegas_movimiento_autorizaciones_despachos.codigo_producto
  IS 'Codigo del Producto Autorizado/Despachado';

COMMENT ON COLUMN public.inv_bodegas_movimiento_autorizaciones_despachos.lote
  IS 'Lote del producto Despachado/Autorizado';

COMMENT ON COLUMN public.inv_bodegas_movimiento_autorizaciones_despachos.fecha_vencimiento
  IS 'Fecha de Vencimiento del producto Autorizado/Despachado';

COMMENT ON COLUMN public.inv_bodegas_movimiento_autorizaciones_despachos.cantidad
  IS 'Cantidad del producto Autorizado/Despachado';

COMMENT ON COLUMN public.inv_bodegas_movimiento_autorizaciones_despachos.porcentaje_gravamen
  IS 'Porcentaje Iva Producto Autorizado/Despachado';

COMMENT ON COLUMN public.inv_bodegas_movimiento_autorizaciones_despachos.total_costo
  IS 'Costo total del Producto Autorizado/Despachado';

COMMENT ON COLUMN public.inv_bodegas_movimiento_autorizaciones_despachos.fecha_registro
  IS 'Fecha de Solicitud de Autorizacion del Producto para Despacho';

COMMENT ON COLUMN public.inv_bodegas_movimiento_autorizaciones_despachos.usuario_id_autorizador
  IS 'Usuario que hizo la autorizacion';

COMMENT ON COLUMN public.inv_bodegas_movimiento_autorizaciones_despachos.observacion
  IS 'Texto de la observacion dada por el usuario autorizador';

COMMENT ON COLUMN public.inv_bodegas_movimiento_autorizaciones_despachos.fecha_autorizacion
  IS 'Fecha y Hora de la autorizacion Del Despacho.';

  ALTER TABLE public.inv_bodegas_movimiento_tmp_despachos_farmacias
  ADD COLUMN automatico char NOT NULL DEFAULT 0;

COMMENT ON COLUMN public.inv_bodegas_movimiento_tmp_despachos_farmacias.automatico
  IS 'Define si el Documento fue creado de manera Automantica (1) Si (0) No';
