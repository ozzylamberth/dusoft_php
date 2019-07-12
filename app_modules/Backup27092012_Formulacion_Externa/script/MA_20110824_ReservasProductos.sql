CREATE OR REPLACE FUNCTION public.fc_eliminar_reservas_dispensacion(varchar)
RETURNS varchar AS
$$
DECLARE
    empresa_id	ALIAS  FOR $1;
	dias_reserva	RECORD;
	mensaje	varchar;
   

	BEGIN
		
		mensaje := 'NO HAY PARAMETROS PARA INACTIVAR LAS RESERVAS';
		
		SELECT valor INTO dias_reserva
		FROM
		system_modulos_variables
		WHERE TRUE
		AND modulo = 'Formulacion_Externa'
		AND modulo_tipo = 'app'
		AND variable = 'dias_reserva_productos_'||$1;
		
		IF dias_reserva.valor !='' THEN
			mensaje := 'HECHO! A: '||dias_reserva.valor::integer||' dias.';
			UPDATE esm_pendientes_por_dispensar as b
			SET sw_estado = '2'
			FROM
			esm_formula_externa as a
			WHERE TRUE
			AND b.formula_id = a.formula_id
			AND a.empresa_id = $1
			AND b.sw_estado = '0'
			AND (current_date - b.fecha_registro::date) > dias_reserva.valor::integer;
		END IF;
		
	
	
	RETURN mensaje;

  END ;
$$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER;

ALTER FUNCTION public.fc_eliminar_reservas_dispensacion(varchar)
  OWNER TO "admin";

GRANT EXECUTE
 ON FUNCTION public.fc_eliminar_reservas_dispensacion(varchar)
TO "admin";

GRANT EXECUTE
 ON FUNCTION public.fc_eliminar_reservas_dispensacion(varchar)
TO PUBLIC;

ALTER TABLE public.inventarios
  ADD COLUMN cantidad_max_formulacion integer;

COMMENT ON COLUMN public.inventarios.cantidad_max_formulacion
  IS 'Cantidad Maxima a Validar al momento de digitar una Formula Ambulatoria, con el fin de mostrar una alerta de Cantidades Superiores a las estandares de Formulacion';
