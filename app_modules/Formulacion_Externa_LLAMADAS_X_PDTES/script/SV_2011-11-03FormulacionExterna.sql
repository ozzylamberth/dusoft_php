

ALTER TABLE public.esm_dispensacion_medicamentos_tmp
ALTER COLUMN formula_id
DROP NOT NULL;

ALTER TABLE public.esm_dispensacion_medicamentos_tmp
ADD COLUMN  formula_id_tmp  integer null;

COMMENT ON COLUMN esm_dispensacion_medicamentos_tmp.formula_id_tmp IS 'Id de la formula temporal';


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
            porcentaje_intermediacion char(4);
            porcentaje_inter numeric(5,3);
            porcentaje_logistica char(4);
            porcentaje_logis numeric(5,3);
BEGIN
    /*Obtener el Codigo de la Lista Precios*/
    lista_precios_ :=(    SELECT    
                        lista_precios
                      from
                        planes
                      where
                      plan_id = $1
                  );
    porcentaje_intermediacion :=(    SELECT    
                        valor
                      from
                        system_modulos_variables
                      where
                      variable = 'ESM_PorcentajeIntermediacion'
                  );
 
  porcentaje_logistica :=(    SELECT    
                        valor
                      from
                        system_modulos_variables
                      where
                      variable = 'ESM_PorcentajeLogistica'
                  );
                 
  porcentaje_inter :=((CAST(porcentaje_intermediacion AS NUMERIC(5,3))/100)+1);
  porcentaje_logis :=((CAST(porcentaje_logistica AS NUMERIC(5,3))/100)+1);
  precio_ := 0; 
  valor_ := 0;     
    IF lista_precios_ != '0000' THEN
       
        IF sw_bodega_mindefensa_ = '1' THEN
      
        precio_ :=(
                  select valor_inicial
                  from
                  listas_precios_detalle
                  where
                      codigo_lista = lista_precios_
                  and codigo_producto = $2
                  );
       valor_ := precio_+(precio_*porcentaje_logis);
       precio_ := valor_;
        ELSE
        precio_ :=(
                  select precio
                  from
                  listas_precios_detalle
                  where
                      codigo_lista = lista_precios_
                   and codigo_producto = $2
                  );
    
        END IF;
   
        IF precio_ ISNULL THEN
        precio_ :=(
                  select precio
                  from
                  listas_precios_base
                  where
                      codigo_lista = lista_precios_
                  and empresa_id = $3
                  and codigo_producto = $2
                  );
        valor_ := precio_+(precio_*porcentaje_inter);
        precio_ := valor_;
        END IF;
       
        IF precio_ ISNULL THEN
        precio_ :=(
                  select costo_ultima_compra
                  from
                  inventarios
                  where
                      empresa_id = $3
                  and codigo_producto = $2
                  );
         valor_ := precio_+(precio_*porcentaje_inter);
        precio_ := valor_;
        END IF;
       
        IF sw_pendiente_frango_ = '1' THEN
                    precio_ :=(
                              select precio
                              from
                              listas_precios_detalle
                              where
                                  codigo_lista = lista_precios_
                             
                              and codigo_producto = $2
                              );
                IF precio_ ISNULL THEN
                      precio_ :=(
                                select precio
                                from
                                listas_precios_base
                                where
                                    codigo_lista = lista_precios_
                             
                                and codigo_producto = $2
                                );
                END IF;
                IF precio_ ISNULL THEN
                      precio_ :=(
                                select costo_ultima_compra
                                from
                                inventarios
                                where
                                    empresa_id = $3
                                and codigo_producto = $2
                                );
        
                END IF;
        END IF;
    END IF;
     
    RETURN precio_;
END;
$$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER;

ALTER FUNCTION public.fc_precio_producto_plan(integer, varchar, varchar, char, char)
  OWNER TO "admin";


  
  ALTER TABLE public.esm_formula_externa_tmp
 ADD COLUMN sw_autorizado  CHARACTER(1) NOT NULL DEFAULT '0';
 

COMMENT ON COLUMN public.esm_formula_externa_tmp.sw_autorizado
 IS 'Define si la formula vencidan  es autorizada  para  ser despachada  (0)sin autorizacion (1)autorizado';
 

ALTER TABLE public.esm_formula_externa_tmp
 ADD COLUMN usuario_autoriza_id  integer  NULL;

COMMENT ON COLUMN public.esm_formula_externa_tmp.usuario_autoriza_id
 IS 'usuario que autorizo el despacho';
 
 
 ALTER TABLE public.esm_formula_externa_tmp
 ADD COLUMN observacion_autorizacion  text  NULL;
 
 COMMENT ON COLUMN public.esm_formula_externa_tmp.observacion_autorizacion
 IS 'observacion de la autorizacion';
 
 ALTER TABLE public.esm_formula_externa_tmp
 ADD COLUMN fecha_registro_autorizacion  TIMESTAMP WITHOUT TIME ZONE  NULL ;
 
  COMMENT ON COLUMN public.esm_formula_externa_tmp.fecha_registro_autorizacion
 IS 'Fecha de la Autorizacion';
 
ALTER TABLE public.userpermisos_formulacion_externa
ADD COLUMN  sw_privilegios  CHARACTER(1) NOT NULL DEFAULT '0';

COMMENT ON COLUMN userpermisos_formulacion_externa.sw_privilegios IS '0=>No tiene privilegios, 1=>Privilegios Basicos';



ALTER TABLE public.esm_formula_externa_medicamentos_tmp
 ADD COLUMN sw_autorizado  CHARACTER(1) NOT NULL DEFAULT '0';
 

COMMENT ON COLUMN public.esm_formula_externa_medicamentos_tmp.sw_autorizado
 IS 'Define si el producto Esta autorizado para despachar por medio de autorizacion (0)sin autorizacion (1)autorizado';
 

ALTER TABLE public.esm_formula_externa_medicamentos_tmp
 ADD COLUMN usuario_autoriza_id  integer  NULL;

COMMENT ON COLUMN public.esm_formula_externa_medicamentos_tmp.usuario_autoriza_id
 IS 'usuario que autorizo el despacho';
 
 
 ALTER TABLE public.esm_formula_externa_medicamentos_tmp
 ADD COLUMN observacion_autorizacion  text  NULL;
 
 COMMENT ON COLUMN public.esm_formula_externa_medicamentos_tmp.observacion_autorizacion
 IS 'observacion dela autorizacion';
 
 ALTER TABLE public.esm_formula_externa_medicamentos_tmp
 ADD COLUMN fecha_registro_autorizacion  TIMESTAMP WITHOUT TIME ZONE  NULL ;
 
  COMMENT ON COLUMN public.esm_formula_externa_medicamentos_tmp.fecha_registro_autorizacion
 IS 'Fecha de la Autorizacion';
 
  
  
  