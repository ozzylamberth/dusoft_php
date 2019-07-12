
ALTER TABLE esm_orden_requisicion_tmp_d ADD cantidad_solicitada_inicial 	numeric(18,0) null;
COMMENT ON COLUMN esm_orden_requisicion_tmp_d.cantidad_solicitada_inicial IS 'es la cantidad solicitada inicial no cambia ';


ALTER TABLE esm_orden_requisicion_d ADD cantidad_solicitada_inicial 	numeric(18,0) null;
COMMENT ON COLUMN esm_orden_requisicion_d.cantidad_solicitada_inicial IS 'es la cantidad solicitada inicial no cambia ';

