CREATE FUNCTION ExistenciasMenores () RETURNS trigger AS
'
BEGIN
IF TG_OP=INSERT THEN
  IF NEW.existencia<0 THEN
	  return OLD;
  ELSE
    return NEW;
  END IF;
END IF;
IF TG_OP =UPDATE THEN
  IF NEW.existencia<0 THEN
   return OLD;
  ELSE
    return NEW;
  END IF;
END IF;
END;
'
LANGUAGE plpgsql
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER;

CREATE TRIGGER ExistenciasMenores BEFORE
INSERT OR UPDATE ON existencias_bodegas
FOR EACH ROW EXECUTE PROCEDURE ExistenciasMenores();

ALTER TABLE qx_paquetes_contiene_insumos ADD COLUMN cantidad numeric(9,2);
