ALTER TABLE userpermisos_cuentas ADD COLUMN sw_cambiofecha character(1) default('0');
COMMENT ON COLUMN userpermisos_cuentas.sw_cambiofecha IS 'Donde se parametriza el cambio de la fecha y hora de salida de un paciente 0= NO  1=SI  ';

ALTER TABLE ingresos_salidas ADD COLUMN usuario_cambiofech integer;
COMMENT ON COLUMN ingresos_salidas.usuario_cambiofech IS 'Donde se ingresa el usuario que cambio la fecha de salida de un paciente hospitalario ';
