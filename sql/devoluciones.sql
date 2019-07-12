

--ALTER TABLE ONLY public.rc_devoluciones DROP CONSTRAINT "$2";
--ALTER TABLE ONLY public.rc_devoluciones DROP CONSTRAINT rc_devoluciones_pkey;
--DROP TABLE public.rc_devoluciones;


CREATE TABLE rc_devoluciones (
    devolucion_id serial NOT NULL,
    empresa_id character(2) NOT NULL,
    centro_utilidad character(2) NOT NULL,
    numerodecuenta integer NOT NULL,
    total_devolucion numeric(12,2) DEFAULT 0 NOT NULL,
    estado character(1) DEFAULT 0 NOT NULL,
    fecha_registro timestamp without time zone NOT NULL,
    usuario_id integer NOT NULL,
    caja_id integer NOT NULL
);





ALTER TABLE ONLY rc_devoluciones
ADD CONSTRAINT rc_devoluciones_pkey PRIMARY KEY (devolucion_id);



ALTER TABLE ONLY rc_devoluciones
    ADD CONSTRAINT "$2" FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;



