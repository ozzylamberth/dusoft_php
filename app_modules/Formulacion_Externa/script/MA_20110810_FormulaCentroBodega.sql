ALTER TABLE public.esm_formula_externa
  ADD CONSTRAINT foreign_key04
  FOREIGN KEY (plan_id)
    REFERENCES public.planes(plan_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE;


ALTER TABLE public.esm_formula_externa
  ADD COLUMN centro_utilidad char(2);

COMMENT ON COLUMN public.esm_formula_externa.centro_utilidad
  IS 'Centro de Utilidad donde se hizo la dispensacion';

  ALTER TABLE public.esm_formula_externa
  ADD COLUMN bodega char(2);

COMMENT ON COLUMN public.esm_formula_externa.bodega
  IS 'Bodega donde se Realiza la dispensacion de la formula';

  ALTER TABLE public.esm_formula_externa
  ADD CONSTRAINT foreign_key03
  FOREIGN KEY (bodega, centro_utilidad, empresa_id)
    REFERENCES public.bodegas(bodega, centro_utilidad, empresa_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE;

	
	/*
	
	SELECT 
a.codigo_medicamento,
SUM(a.cantidad) as cantidad,
c.paciente_id
FROM 
esm_pendientes_por_dispensar AS a
JOIN inventarios_productos as b ON (a.codigo_medicamento = b.codigo_producto)
LEFT JOIN esm_formula_externa as c ON (a.formula_id = c.formula_id)
WHERE TRUE
AND a.sw_estado = '0'
AND c.sw_estado <> '2'
AND tratamiento_id IS NOT NULL
AND

GROUP BY a.codigo_medicamento,c.paciente_id
	*/