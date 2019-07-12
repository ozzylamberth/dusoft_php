CREATE TABLE interface_datalab_departamentos (
    departamento character varying(6) NOT NULL
);

INSERT INTO interface_datalab_departamentos VALUES ('010601');

ALTER TABLE ONLY interface_datalab_departamentos
    ADD CONSTRAINT interface_datalab_departamentos_pkey PRIMARY KEY (departamento);

ALTER TABLE ONLY interface_datalab_departamentos
    ADD CONSTRAINT "$1" FOREIGN KEY (departamento) REFERENCES departamentos(departamento) ON UPDATE CASCADE ON DELETE RESTRICT;

COMMENT ON TABLE interface_datalab_departamentos IS 'Departamentos donde se interface con DATLAB.';

--NELLY ESTO ES LO DE LA COPIA AUTOMATICA, SI SACA ERROR DE QUE NO EXISTE, NO HAY PROBLEMA
--DROP TRIGGER copia_cups ON cups;
--DROP FUNCTION copiar_cups();
