ALTER TABLE userpermisos_cuentas ADD COLUMN sw_unificar CHARACTER(1) NOT NULL DEFAULT '0';
COMMENT ON COLUMN userpermisos_cuentas.sw_unificar IS 'Indica si el usuario puede realizar unificacion de cuentas (1) o NO (0)';
