

CREATE TABLE pacientes_alfanumerico
(
  empresa_id CHARACTER(2) NOT NULL,
  centro_utilidad 	 character(2) NOT NULL,
  sw_alfanumerico  CHARACTER(1) NOT NULL
);

ALTER TABLE   pacientes_alfanumerico ADD PRIMARY KEY (empresa_id, centro_utilidad);
ALTER TABLE   pacientes_alfanumerico ADD   FOREIGN KEY (empresa_id, centro_utilidad) REFERENCES centros_utilidad(empresa_id,centro_utilidad)	ON UPDATE CASCADE ON DELETE CASCADE;

COMMENT ON TABLE pacientes_alfanumerico IS 'Tabla donde se registra si al ingresar  el paciente su numero de identificacion es alfanumerico o numerico';
COMMENT ON COLUMN pacientes_alfanumerico.empresa_id IS '(PK - FK) Identificador de la empresa';
COMMENT ON COLUMN pacientes_alfanumerico.centro_utilidad IS '(PK - FK) Centro utilidad de la Empresa';
COMMENT ON COLUMN pacientes_alfanumerico.sw_alfanumerico IS '0=>numerico, 1=>Alfanumerico';
GRANT ALL ON TABLE pacientes_alfanumerico TO siis;
