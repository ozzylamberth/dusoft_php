DROP table interface_datalab_codigos;
DROP table interface_datalab_control_detalle;


CREATE TABLE interface_datalab_codigos (
    codigo_cups character varying(10) NOT NULL,
    codigo_datalab integer NOT NULL,
    sw_perfil character(1)
);

ALTER TABLE ONLY interface_datalab_codigos
    ADD CONSTRAINT interface_datalab_codigos_pkey PRIMARY KEY (codigo_cups, codigo_datalab);



CREATE TABLE interface_datalab_control_detalle (
    interface_datalab_control_id integer NOT NULL,
    numero_orden_id integer NOT NULL,
    codigo_datalab integer NOT NULL
);


ALTER TABLE ONLY interface_datalab_control_detalle
    ADD CONSTRAINT interface_datalab_control_detalle_pkey PRIMARY KEY (interface_datalab_control_id, numero_orden_id);

ALTER TABLE ONLY interface_datalab_control_detalle
    ADD FOREIGN KEY (interface_datalab_control_id) REFERENCES interface_datalab_control(interface_datalab_control_id) ON UPDATE CASCADE ON DELETE RESTRICT;


		