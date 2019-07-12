ALTER TABLE userpermisos_os_entrega_apoyod ADD COLUMN sw_modo_impresion character (1);
COMMENT ON COLUMN userpermisos_os_entrega_apoyod.sw_modo_impresion IS 'si esta en 1 tiene solo acceso a la impresion';

update userpermisos_os_entrega_apoyod set sw_modo_impresion = '0' where sw_modo_impresion is NULL
ALTER TABLE userpermisos_os_entrega_apoyod ALTER COLUMN sw_modo_impresion SET NOT NULL;
ALTER TABLE userpermisos_os_entrega_apoyod ALTER COLUMN sw_modo_impresion SET DEFAULT 0;





