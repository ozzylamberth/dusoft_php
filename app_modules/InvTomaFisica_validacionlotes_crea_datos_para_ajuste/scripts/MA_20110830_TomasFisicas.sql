-- DROP FUNCTION public.inv_toma_fisica_update_f(integer, integer, integer, numeric, char, char, varchar, varchar, numeric, date, varchar);
ALTER TABLE public.existencias_bodegas_lote_fv
  ADD COLUMN fecha_registro timestamp(1) WITHOUT TIME ZONE NOT NULL DEFAULT NOW();

COMMENT ON COLUMN public.existencias_bodegas_lote_fv.fecha_registro
  IS 'fecha registro';


DROP FUNCTION public.inv_toma_fisica_update_f(integer, integer, integer, numeric, char, char, varchar, varchar, numeric);

CREATE OR REPLACE FUNCTION public.inv_toma_fisica_update_f
(
   integer,
   integer,
   integer,
   numeric,
   char,
   char,
   varchar,
   varchar,
   numeric,
   date,
   varchar
)
RETURNS void AS
$$
DECLARE
    VarTomaFisica ALIAS FOR $1;
    VarEtiqueta ALIAS FOR $2;
    VarNumConteo ALIAS FOR $3;
    VarConteo ALIAS FOR $4;
    VarEmpresa_id ALIAS FOR $5;
    VarCentroUtilidad ALIAS FOR $6;
    VarBodega ALIAS FOR $7;
    VarCodigoProducto ALIAS FOR $8;
    VarExistencia ALIAS FOR $9;
    VarFechaVencimiento ALIAS FOR $10;
    VarLote ALIAS FOR $11;
    VarCosto NUMERIC(12,2);
BEGIN
  VarCosto := (        SELECT        costo
                              FROM         inv_toma_fisica_detalle_inicial
                              WHERE       toma_fisica_id = VarTomaFisica
                              AND            empresa_id = VarEmpresa_id
                              AND            centro_utilidad = VarCentroUtilidad
                              AND            bodega = VarBodega
                              AND            codigo_producto = VarCodigoProducto
                              AND            fecha_vencimiento = VarFechaVencimiento
                              AND            lote = VarLote
               );

    INSERT INTO inv_toma_fisica_update
    (
        toma_fisica_id,
        etiqueta,
        num_conteo,
        sw_manual,
        empresa_id,
        centro_utilidad,
        bodega,
        codigo_producto,
        existencia,
        nueva_existencia,
        costo,
        sw_actualizado,
        fecha_vencimiento,
        lote
    )
    VALUES
    (
        VarTomaFisica,
        VarEtiqueta,
        VarNumConteo,
        '0',
        VarEmpresa_id,
        VarCentroUtilidad,
        VarBodega,
        VarCodigoProducto,
        VarExistencia,
        VarConteo,
         VarCosto,
        NULL,
        VarFechaVencimiento,
        VarLote
    );

    RETURN;

END;
$$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER;

ALTER FUNCTION public.inv_toma_fisica_update_f(integer, integer, integer, numeric, char, char, varchar, varchar, numeric, date, varchar)
  OWNER TO "admin";

-- DROP TABLE public.datos_tomas_2;

CREATE TABLE public.datos_tomas_2 (
  etiqueta             integer,
  codigo_producto      varchar(40),
  empresa_id           char(2),
  centro_utilidad      char(2),
  bodega               varchar(2),
  costo                numeric(15,2),
  existencia           integer,
  fecha_vencimiento    date,
  lote                 varchar(255),
  conteo_1             numeric(9,2),
  conteo_2             numeric(9,2),
  diferencia_1         numeric,
  diferencia_2         numeric,
  diferencia_1con2     numeric,
  validacion_conteo_2  text
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.datos_tomas_2
  OWNER TO "admin";

-- DROP TABLE public.inv_toma_fisica_paramingreso;

CREATE TABLE public.inv_toma_fisica_paramingreso (
  empresa_id       char(2) NOT NULL,
  centro_utilidad  char(2) NOT NULL,
  conteo_2         integer NOT NULL DEFAULT 0,
  conteo_3         integer NOT NULL DEFAULT 0,
  usuario_id       integer NOT NULL,
  /* Keys */
  CONSTRAINT inv_toma_fisica_paramingreso_pkey
    PRIMARY KEY (empresa_id, centro_utilidad),
  /* Foreign keys */
  CONSTRAINT inv_toma_fisica_paramingreso_empresa_id_fkey
    FOREIGN KEY (empresa_id)
    REFERENCES public.empresas(empresa_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE, 
  CONSTRAINT inv_toma_fisica_paramingreso_empresa_id_fkey1
    FOREIGN KEY (empresa_id, centro_utilidad)
    REFERENCES public.centros_utilidad(empresa_id, centro_utilidad)
    ON DELETE RESTRICT
    ON UPDATE CASCADE, 
  CONSTRAINT inv_toma_fisica_paramingreso_empresa_id_fkey2
    FOREIGN KEY (empresa_id)
    REFERENCES public.empresas(empresa_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE, 
  CONSTRAINT inv_toma_fisica_paramingreso_empresa_id_fkey3
    FOREIGN KEY (empresa_id, centro_utilidad)
    REFERENCES public.centros_utilidad(empresa_id, centro_utilidad)
    ON DELETE RESTRICT
    ON UPDATE CASCADE, 
  CONSTRAINT inv_toma_fisica_paramingreso_usuario_id_fkey
    FOREIGN KEY (usuario_id)
    REFERENCES public.system_usuarios(usuario_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE, 
  CONSTRAINT inv_toma_fisica_paramingreso_usuario_id_fkey1
    FOREIGN KEY (usuario_id)
    REFERENCES public.system_usuarios(usuario_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.inv_toma_fisica_paramingreso
  OWNER TO "admin";

COMMENT ON TABLE public.inv_toma_fisica_paramingreso
  IS 'Tabla donde se parametriza las empresas y centros de utilidad que pueden ingresar lotes y F.V en el conteo 1 o 2 ';

COMMENT ON COLUMN public.inv_toma_fisica_paramingreso.empresa_id
  IS '(PK) (FK) Identificador de la empresa';

COMMENT ON COLUMN public.inv_toma_fisica_paramingreso.centro_utilidad
  IS '(PK)(FK) El centro de utilidad';

COMMENT ON COLUMN public.inv_toma_fisica_paramingreso.conteo_2
  IS ' sw donde se ingresa 0= que no se puede conteo2 1= Si';

COMMENT ON COLUMN public.inv_toma_fisica_paramingreso.conteo_3
  IS ' sw donde se ingresa 0= que no se puede conteo3 1= Si';

  
  ALTER TABLE public.inv_toma_fisica_d
  DROP COLUMN consecutivo;

ALTER TABLE public.inv_toma_fisica_d
  DROP COLUMN cantidad_sistema;

ALTER TABLE public.inv_toma_fisica_d
  DROP COLUMN cantidad_fisica;

  ALTER TABLE public.inv_toma_fisica_d
  ADD COLUMN etiqueta_x_producto integer;

COMMENT ON COLUMN public.inv_toma_fisica_d.etiqueta_x_producto
  IS 'Etiqueta q es asignada General del Producto que abarca varios lotes';

  ALTER TABLE public.inv_toma_fisica_update
  ADD COLUMN sw_cuadre char NOT NULL DEFAULT 0;

COMMENT ON COLUMN public.inv_toma_fisica_update.sw_cuadre
  IS 'Donde se ingresa el cuadre del producto si es 0= NO 1=1 ';

  ALTER TABLE public.inventarios_productos
  ADD COLUMN sw_ajusteautomatico integer NOT NULL DEFAULT 1;

COMMENT ON COLUMN public.inventarios_productos.sw_ajusteautomatico
  IS 'Sw donde se muestra los productos que tienen ajuste automatico 1= SI, 0= NO';

  ALTER TABLE public.inv_toma_fisica_d
  DROP CONSTRAINT inv_toma_fisica_d_toma_fisica_id_key;

ALTER TABLE public.inv_toma_fisica_d
  ADD CONSTRAINT inv_toma_fisica_d_toma_fisica_id_key
  UNIQUE (toma_fisica_id, empresa_id, centro_utilidad, bodega, codigo_producto, fecha_vencimiento, lote)
    WITH (FILLFACTOR = 100);

	ALTER TABLE public.inv_toma_fisica_detalle_inicial
  DROP CONSTRAINT inv_toma_fisica_detalle_inicial_pkey;

ALTER TABLE public.inv_toma_fisica_detalle_inicial
  ADD CONSTRAINT inv_toma_fisica_detalle_inicial_pkey
  PRIMARY KEY (toma_fisica_id, empresa_id, centro_utilidad, bodega, codigo_producto, fecha_vencimiento, lote)
    WITH (FILLFACTOR = 100);

	-- DROP FUNCTION public.etiqueta_producto_tf();

CREATE OR REPLACE FUNCTION public.etiqueta_producto_tf()
RETURNS trigger AS
$$
DECLARE producto RECORD;
              valor      INTEGER;
BEGIN
             SELECT INTO producto etiqueta_x_producto
             FROM            inv_toma_fisica_d
             WHERE          empresa_id = NEW.empresa_id
             AND               centro_utilidad = NEW.centro_utilidad
             AND               codigo_producto = NEW.codigo_producto
             AND               bodega = NEW.bodega
             AND               toma_fisica_id = NEW.toma_fisica_id;
  
   valor := (SELECT  MAX(etiqueta_x_producto)
                   FROM    inv_toma_fisica_d
                   WHERE  toma_fisica_id = NEW.toma_fisica_id);
  
    IF valor IS NULL THEN
      valor:= 0;
   END IF;

   IF producto.etiqueta_x_producto IS NULL THEN
      NEW.etiqueta_x_producto := valor+1;
   ELSE
       NEW.etiqueta_x_producto := producto.etiqueta_x_producto;                  
   END IF;
   RETURN NEW;
   END;
$$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER;

ALTER FUNCTION public.etiqueta_producto_tf()
  OWNER TO "admin";
	
	
	CREATE TRIGGER actualizar_etiqueta
  BEFORE INSERT
  ON public.inv_toma_fisica_d
  FOR EACH ROW
  EXECUTE PROCEDURE public.etiqueta_producto_tf();