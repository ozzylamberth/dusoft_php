CREATE TABLE userpermisos_eps_rips (
    usuario_id integer NOT NULL,
    sw_activo character(1) NOT NULL,
    CONSTRAINT userpermisos_eps_rips_sw_activo_check CHECK ((((sw_activo)::text = (1)::text) OR ((sw_activo)::text = (0)::text)))
);

COMMENT ON TABLE userpermisos_eps_rips IS 'Tabla de permisos para la generación de RIPS de la EPS';
COMMENT ON COLUMN userpermisos_eps_rips.usuario_id IS 'Usuario al que se le concede permisos de crear RIPS de EPS';
COMMENT ON COLUMN userpermisos_eps_rips.sw_activo IS 'Estado (1:Activo - 0:Inactivo)';

ALTER TABLE ONLY userpermisos_eps_rips ADD CONSTRAINT userpermisos_eps_rips_pkey PRIMARY KEY (usuario_id);
ALTER TABLE ONLY userpermisos_eps_rips ADD CONSTRAINT userpermisos_eps_rips_usuario_id_fkey FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;


CREATE TABLE rips_eps_control (
    rips_eps_id integer NOT NULL,
    fecha_inicial timestamp without time zone NOT NULL,
    fecha_final timestamp without time zone NOT NULL,
    usuario_id integer NOT NULL,
    fecha_registro timestamp without time zone DEFAULT now() NOT NULL,
    cxp_estados text,
    proveedor_id integer,
    resultado text
);

COMMENT ON TABLE rips_eps_control IS 'Registro de los envios de RIPS creados por la EPS';

CREATE SEQUENCE rips_eps_control_rips_eps_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

COMMENT ON TABLE rips_eps_control IS 'Registro de los envios de RIPS creados por la EPS';
COMMENT ON COLUMN rips_eps_control.rips_eps_id IS 'Numero de Envio';
COMMENT ON COLUMN rips_eps_control.fecha_inicial IS 'Fecha inicial del rango de generacion';
COMMENT ON COLUMN rips_eps_control.fecha_final IS 'Fecha final del rango de generacion';
COMMENT ON COLUMN rips_eps_control.usuario_id IS 'usuario que genero el envio';
COMMENT ON COLUMN rips_eps_control.fecha_registro IS 'Fecha y hora en la que se genero el registro';
COMMENT ON COLUMN rips_eps_control.cxp_estados IS 'filtros de estados de la cxp empleados en la generacion del registro';
COMMENT ON COLUMN rips_eps_control.proveedor_id IS 'opcional, filtro para generar el envio de las facturas de un solo proveedor';
COMMENT ON COLUMN rips_eps_control.resultado IS 'resultado de la operacion (OK + nombre del archivo generado ó mensaje de error)';


ALTER TABLE rips_eps_control ALTER COLUMN rips_eps_id SET DEFAULT nextval('rips_eps_control_rips_eps_id_seq'::regclass);
ALTER TABLE ONLY rips_eps_control ADD CONSTRAINT rips_eps_control_pkey PRIMARY KEY (rips_eps_id);
ALTER TABLE ONLY rips_eps_control ADD CONSTRAINT rips_eps_control_proveedor_id_fkey FOREIGN KEY (proveedor_id) REFERENCES terceros_proveedores(codigo_proveedor_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY rips_eps_control ADD CONSTRAINT rips_eps_control_usuario_id_fkey FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;

