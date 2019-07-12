CREATE TABLE cuentas_descuentos_grupos
(
  numerodecuenta INTEGER NOT NULL,
  codigo_agrupamiento_id INTEGER NOT NULL,
  por_descuento_empresa NUMERIC(12,2) NOT NULL DEFAULT 0,
  por_descuento_paciente NUMERIC(12,2) NOT NULL DEFAULT 0
);

ALTER TABLE cuentas_descuentos_grupos ADD PRIMARY KEY(numerodecuenta,codigo_agrupamiento_id);
ALTER TABLE cuentas_descuentos_grupos ADD FOREIGN KEY(numerodecuenta)
REFERENCES cuentas(numerodecuenta) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE cuentas_descuentos_grupos ADD FOREIGN KEY(codigo_agrupamiento_id)
REFERENCES cuentas_codigos_agrupamiento(codigo_agrupamiento_id) ON UPDATE CASCADE ON DELETE RESTRICT;

COMMENT ON TABLE cuentas_descuentos_grupos IS 'Tabla donde se registrs el porcentaje de descuento, aplicado sobre cada grupo de la cuenta';
COMMENT ON COLUMN cuentas_descuentos_grupos.numerodecuenta IS '(PK - FK) Identificador de la cuenta';
COMMENT ON COLUMN cuentas_descuentos_grupos.codigo_agrupamiento_id IS '(PK - FK) Identificador del codigo de agrupamiento';
COMMENT ON COLUMN cuentas_descuentos_grupos.por_descuento_empresa IS 'Porcentaje de descuento de la empresa sobre el grupo';
COMMENT ON COLUMN cuentas_descuentos_grupos.por_descuento_paciente IS 'Porcentaje de descuento del paciente sobre el grupo';