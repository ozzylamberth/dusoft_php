CREATE OR REPLACE FUNCTION public.fc_precio_producto_plan
(
   integer,
   varchar,
   varchar,
   char,
   char
)
RETURNS numeric AS
$$
DECLARE
            plan_id_ ALIAS FOR $1;
            codigo_producto_ ALIAS FOR $2;
            empresa_id_ ALIAS FOR $3;
            sw_bodega_mindefensa_ ALIAS FOR $4;
            sw_pendiente_frango_ ALIAS FOR $5;
            precio_ NUMERIC(18,4);
            valor_ NUMERIC(18,4);
            lista_precios_ char(4);
            resultado RECORD;
            porcentaje_intermediacion char(4);
            porcentaje_inter numeric(5,3);
            porcentaje_logistica char(4);
            porcentaje_logis numeric(5,3);
BEGIN
    /*Obtener el Codigo de la Lista Precios*/
		precio_:=0;
		lista_precios_ :=0;
		
		lista_precios_ :=( SELECT   
		lista_precios
		from
		planes
		where
		plan_id = $1  );
   
		SELECT
		a.codigo_producto,
		CASE 
		WHEN c.codigo_producto IS NOT NULL AND c.sw_porcentaje = '1' 
		THEN b.costo/((100-c.porcentaje)/100)
		WHEN c.codigo_producto IS NOT NULL AND c.sw_porcentaje = '0'
		THEN c.precio
		WHEN c.codigo_producto IS NULL
		THEN b.costo
		END as precio
		INTO resultado
		FROM
		inventarios_productos as a 
		JOIN inventarios as b ON (a.codigo_producto = b.codigo_producto)
		LEFT JOIN (SELECT
				  x.codigo_producto,
				  x.precio,
				  x.sw_porcentaje,
				  x.porcentaje
				  FROM
				  listas_precios as w
				  JOIN listas_precios_detalle as x ON (w.codigo_lista = x.codigo_lista)
				  WHERE TRUE
				  AND w.codigo_lista = COALESCE(lista_precios_,'')
				  AND x.codigo_producto = $2
				  AND x.empresa_id = $3
				  )as c ON (b.codigo_producto = c.codigo_producto)
		WHERE TRUE
		AND a.codigo_producto = $2
		and b.empresa_id = $3;
   
   precio_ := resultado.precio::numeric;
   
    RETURN precio_;
END;
$$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER;

ALTER FUNCTION public.fc_precio_producto_plan(integer, varchar, varchar, char, char)
  OWNER TO "admin";

GRANT EXECUTE
 ON FUNCTION public.fc_precio_producto_plan(integer, varchar, varchar, char, char)
TO "admin";

GRANT EXECUTE
 ON FUNCTION public.fc_precio_producto_plan(integer, varchar, varchar, char, char)
TO PUBLIC;





ALTER TABLE public.bodegas_documentos_d
  ADD COLUMN total_venta numeric(13,2) NOT NULL DEFAULT 0;

COMMENT ON COLUMN public.bodegas_documentos_d.total_venta
  IS 'Campo Auxiliar para guardar el costo Venta (Campo Nuevo): Creado el Lunes 8 de Agosto 2011';
