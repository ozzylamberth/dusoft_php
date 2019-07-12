CREATE OR REPLACE FUNCTION public.inv_alertas_recepcion_productos()
RETURNS trigger AS
$$
DECLARE

mensaje_ varchar;

asunto_ varchar;

datos_ RECORD;

BEGIN


  SELECT 
  ivp.descripcion || ' ' ||  ivp.contenido_unidad_venta || ' ' || unid.descripcion || '-' || cla.descripcion as producto,
  exb.cantidad,
  ex.empresa_id,
  exb.lote,
  exb.fecha_vencimiento,
  emp.razon_social,
  cent.descripcion as centro,
  bod.descripcion as bodega
  
  INTO datos_
  FROM   
	  inv_recepciones_parciales ex,
	  inv_recepciones_parciales_d exb,
	  inventarios_productos ivp,
	  unidades unid,
	  inv_subclases_inventarios sub,
	  inv_clases_inventarios cla,
	  inv_grupos_inventarios grp,
	  empresas emp,
	  centros_utilidad cent,
	  bodegas bod
  WHERE  
  exb.recepcion_parcial_id = NEW.recepcion_parcial_id
  and
  exb.codigo_producto = NEW.codigo_producto
  and
  exb.codigo_producto = ivp.codigo_producto
  and
  ivp.unidad_id = unid.unidad_id
  and
  ivp.subclase_id = sub.subclase_id
	and
	ivp.clase_id = sub.clase_id
	and
	ivp.grupo_id = sub.grupo_id
	and
	sub.clase_id = cla.clase_id
	and
	sub.grupo_id = cla.grupo_id
	and
	cla.grupo_id = grp.grupo_id
	and
	exb.recepcion_parcial_id = ex.recepcion_parcial_id
	and
	ex.empresa_id = bod.empresa_id
	and
	ex.centro_utilidad = bod.centro_utilidad
	and
	ex.bodega = bod.bodega
	and
	bod.empresa_id = cent.empresa_id
	and
	bod.centro_utilidad = cent.centro_utilidad
	and
	cent.empresa_id = emp.empresa_id;
	

IF datos_.cantidad > 0 THEN

 mensaje_ :=' Ha llegado el Producto ' || datos_.producto || ', Lote :' || datos_.lote || ',Fecha Vencimiento:' || datos_.fecha_vencimiento || ', A la Empresa: ' || datos_.razon_social || ', Centro de Utilidad: ' || datos_.centro || ' y Bodega ' || datos_.bodega || '. Cantidad: '  || datos_.cantidad || '.';
 asunto_ := ' Ha Llegado El Producto :' || datos_.producto;
 
 INSERT INTO inv_buzon_compras
    (
      asunto,
	  empresa_id,
	  fecha_mensaje,
	  mensaje
    )
    VALUES
    (
     asunto_,
	 datos_.empresa_id,
	 NOW(),
	 mensaje_
    ); 
 

END IF;

return NEW;


END;
$$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER;

ALTER FUNCTION public.inv_alertas_recepcion_productos()
  OWNER TO "admin";

GRANT EXECUTE
 ON FUNCTION public.inv_alertas_recepcion_productos()
TO PUBLIC;

GRANT EXECUTE
 ON FUNCTION public.inv_alertas_recepcion_productos()
TO "admin";

GRANT EXECUTE
 ON FUNCTION public.inv_alertas_recepcion_productos()
TO siis_consulta;

GRANT EXECUTE
 ON FUNCTION public.inv_alertas_recepcion_productos()
TO siis;

CREATE TRIGGER alertas_recepcion_productos
  AFTER INSERT OR UPDATE
  ON public.inv_recepciones_parciales_d
  FOR EACH ROW
  EXECUTE PROCEDURE public.inv_alertas_recepcion_productos();

COMMENT ON TRIGGER alertas_recepcion_productos
  ON public.inv_recepciones_parciales_d
  IS 'Alerta a los usuarios de Compras sobre la recepcion de Productos en la Bodega';
