-------------------DIC 1--------------------------------------

ALTER TABLE "hc_conducta_remision" ADD COLUMN "sw_remision" character(1);
UPDATE hc_conducta_remision SET sw_remision=0;
ALTER TABLE "hc_conducta_remision" ALTER COLUMN "sw_remision" SET NOT NULL;
ALTER TABLE "hc_conducta_remision" ALTER COLUMN "sw_remision" SET DEFAULT 0;

COMMENT ON COLUMN hc_conducta_remision.sw_remision IS '0 la hizo el medico 1 la admon la termino';
 
