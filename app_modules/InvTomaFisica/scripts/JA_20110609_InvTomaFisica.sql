ALTER TABLE existencias_bodegas_lote_fv ADD COLUMN fecha_registro timestamp without time zone default now();

REINDEX TABLE existencias_bodegas_lote_fv;
