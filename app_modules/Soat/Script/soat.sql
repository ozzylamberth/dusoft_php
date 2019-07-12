
alter table terceros_soat add digito_Verificacion character(2) null;

COMMENT ON COLUMN terceros_soat.digito_Verificacion IS '0=>No tiene digito de verificacion, 1=>si tiene digito de verificacion';



