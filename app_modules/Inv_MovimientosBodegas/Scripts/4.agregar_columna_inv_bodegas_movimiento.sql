ALTER TABLE inv_bodegas_movimiento ADD COLUMN porcentaje_cree numeric(9,4) DEFAULT 0.0;

COMMENT ON COLUMN "inv_bodegas_movimiento"."porcentaje_cree" 
	IS 'Porcentaje Impto CREE';