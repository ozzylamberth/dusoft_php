ALTER TABLE interface_datalab_pagador ADD COLUMN rango CHARACTER VARYING(40);
COMMENT ON COLUMN interface_datalab_pagador.rango IS 'Identificador del rango';

ALTER TABLE interface_datalab_pagador DROP CONSTRAINT interface_datalab_pagador_pkey;
ALTER TABLE interface_datalab_pagador DROP COLUMN indice_automatico;

ALTER TABLE interface_datalab_pagador ADD COLUMN indice_automatico SERIAL NOT NULL;
ALTER TABLE interface_datalab_pagador ADD PRIMARY KEY (indice_automatico);
COMMENT ON COLUMN interface_datalab_pagador.indice_automatico IS '(PK) identificador de la tabla';

CREATE TABLE departamentos_punto_tomado
(
  departamento CHARACTER VARYING(6) NOT NULL,
  departamento_pt CHARACTER VARYING(6) NOT NULL,
  sw_defecto CHARACTER(1) NOT NULL DEFAULT '0'
);

ALTER TABLE departamentos_punto_tomado ADD PRIMARY KEY(departamento,departamento_pt);
ALTER TABLE departamentos_punto_tomado ADD FOREIGN KEY(departamento)
REFERENCES departamentos(departamento) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE departamentos_punto_tomado ADD FOREIGN KEY(departamento_pt)
REFERENCES departamentos(departamento) ON UPDATE CASCADE ON DELETE CASCADE;

COMMENT ON TABLE departamentos_punto_tomado IS 'Tabla donde se parametrizan los departamentos para los puntso de tomado';
COMMENT ON COLUMN departamentos_punto_tomado.departamento IS '(PK - FK) Identifica el departamento de donde viene la orden de servicio';
COMMENT ON COLUMN departamentos_punto_tomado.departamento_pt IS '(Pk - FK) Identifica el departamento donde se realizara el tomado';
COMMENT ON COLUMN departamentos_punto_tomado.sw_defecto IS 'Indica si ese departamento es el que aprecera por defecto';

ALTER TABLE os_ordenes_servicios ADD COLUMN departamento_pt CHARACTER VARYING(6);
ALTER TABLE os_ordenes_servicios ADD FOREIGN KEY(departamento_pt)
REFERENCES departamentos(departamento) ON UPDATE CASCADE ON DELETE RESTRICT;
COMMENT ON COLUMN os_ordenes_servicios.departamento_pt IS 'Identificador del departamento del punto de tomado';
