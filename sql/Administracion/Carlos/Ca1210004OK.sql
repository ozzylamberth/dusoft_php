DROP TABLE pagare_topes;
DROP TABLE pagare_cuenta_blanco;
DROP TABLE pagare_autorizacion;
DROP TABLE pagare_cuenta;
DROP TABLE pagare_estado;
DROP TABLE pagare_tipos_cancelacion;
DROP TABLE pagare_autorizador;
DROP TABLE userpermisos_credito_paciente;
DROP TABLE puntos_credito_paciente;

CREATE TABLE pagare_estado (
    descripcion character varying(100) NOT NULL,
    estado character(1) NOT NULL
);
ALTER TABLE ONLY pagare_estado
    ADD CONSTRAINT pagare_estado_pkey PRIMARY KEY (estado);
COMMENT ON TABLE pagare_estado IS 'Contiene los posibles estados de un pagaré.';

CREATE TABLE pagare_tipos_cancelacion (
    cancelacion_id serial NOT NULL,
    descripcion character varying(120) NOT NULL
);
ALTER TABLE ONLY pagare_tipos_cancelacion
    ADD CONSTRAINT pagare_tipos_cancelacion_pkey PRIMARY KEY (cancelacion_id);
COMMENT ON TABLE pagare_tipos_cancelacion IS 'Tipos de cancelación que se le puede asociar a un pagaré.';

CREATE TABLE pagare_topes (
    usuario_id integer NOT NULL,
    valor_maximo numeric(12,2) NOT NULL,
    valor_minimo numeric(12,2) NOT NULL
);
ALTER TABLE ONLY pagare_topes
    ADD CONSTRAINT pagare_topes_pkey PRIMARY KEY (usuario_id);
ALTER TABLE ONLY pagare_topes
    ADD CONSTRAINT "$1" FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
COMMENT ON TABLE pagare_topes IS 'Topes maximo y minimo para los pagarés.';

CREATE TABLE pagare_cuenta (
    pagare_id serial NOT NULL,
    numerodecuenta integer NOT NULL,
    tipo_id_paciente character varying(3) NOT NULL,
    paciente_id character varying(32) NOT NULL,
    tipo_id_tercero character varying(2),
    garante_id character varying(32),
    ingreso integer,
    fecha_elaboracion date NOT NULL,
    fecha_vencimiento date NOT NULL,
    valor numeric(12,2),
    formas_pago_id character varying(2) NOT NULL,
    cancelacion_id character varying(2),
    estado character(1) NOT NULL,
    usuario_id integer NOT NULL,
    fecha_registro timestamp without time zone NOT NULL,
    abono numeric(12,2),
    observacion character varying(256)
);
ALTER TABLE ONLY pagare_cuenta
    ADD CONSTRAINT pagare_cuenta_pkey PRIMARY KEY (pagare_id);
ALTER TABLE ONLY pagare_cuenta
    ADD CONSTRAINT "$1" FOREIGN KEY (numerodecuenta) REFERENCES cuentas(numerodecuenta) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY pagare_cuenta
    ADD CONSTRAINT "$4" FOREIGN KEY (formas_pago_id) REFERENCES compras_formas_pago(formas_pago_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY pagare_cuenta
    ADD CONSTRAINT "$5" FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY pagare_cuenta
    ADD CONSTRAINT "$2" FOREIGN KEY (tipo_id_paciente, paciente_id) REFERENCES pacientes(tipo_id_paciente, paciente_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY pagare_cuenta
    ADD CONSTRAINT "$3" FOREIGN KEY (tipo_id_tercero, garante_id, ingreso) REFERENCES garantes(tipo_id_tercero, garante_id, ingreso) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY pagare_cuenta
    ADD CONSTRAINT "$7" FOREIGN KEY (estado) REFERENCES pagare_estado(estado) ON UPDATE CASCADE ON DELETE RESTRICT;
COMMENT ON TABLE pagare_cuenta IS 'Pagarés de los créditos a los pacientes.';
COMMENT ON COLUMN pagare_cuenta.abono IS 'Campo que guarda el abono a un pagaré pendiente de un paciente.';

CREATE TABLE pagare_cuenta_blanco (
    pagare_id integer NOT NULL,
    fecha_elaboracion date NOT NULL,
    fecha_vencimiento date NOT NULL,
    formas_pago_id character varying(2) NOT NULL,
    usuario_id integer NOT NULL
);
ALTER TABLE ONLY pagare_cuenta_blanco
    ADD CONSTRAINT pagare_cuenta_blanco_pkey PRIMARY KEY (pagare_id);
ALTER TABLE ONLY pagare_cuenta_blanco
    ADD CONSTRAINT "$1" FOREIGN KEY (pagare_id) REFERENCES pagare_cuenta(pagare_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY pagare_cuenta_blanco
    ADD CONSTRAINT "$2" FOREIGN KEY (formas_pago_id) REFERENCES compras_formas_pago(formas_pago_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY pagare_cuenta_blanco
    ADD CONSTRAINT "$3" FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
COMMENT ON TABLE pagare_cuenta_blanco IS 'Pagarés en blanco de las cuentas.';

CREATE TABLE pagare_autorizador (
    usuario_id integer NOT NULL,
    extension character varying(10) NOT NULL,
    telefonos character varying(30),
    celular character varying(30),
    estado character(1) NOT NULL
);
ALTER TABLE ONLY pagare_autorizador
    ADD CONSTRAINT pagare_autorizador_pkey PRIMARY KEY (usuario_id);
ALTER TABLE ONLY pagare_autorizador
    ADD CONSTRAINT "$1" FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
COMMENT ON TABLE pagare_autorizador IS 'Autorizadores de pagares.';

CREATE TABLE pagare_autorizacion (
    usuario_id_auto integer NOT NULL,
    pagare_id integer NOT NULL,
    obsevacion text NOT NULL,
    sw_tipo_autorizacion character(1) NOT NULL,
    usuario_id integer NOT NULL,
    fecha_registro timestamp without time zone NOT NULL
);
ALTER TABLE ONLY pagare_autorizacion
    ADD CONSTRAINT pagare_autorizacion_pkey PRIMARY KEY (pagare_id);
ALTER TABLE ONLY pagare_autorizacion
    ADD CONSTRAINT "$1" FOREIGN KEY (pagare_id) REFERENCES pagare_cuenta(pagare_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY pagare_autorizacion
    ADD CONSTRAINT "$2" FOREIGN KEY (usuario_id_auto) REFERENCES pagare_autorizador(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY pagare_autorizacion
    ADD CONSTRAINT "$3" FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
COMMENT ON TABLE pagare_autorizacion IS 'Detalle de la autorización del pagaré.';

CREATE TABLE puntos_credito_paciente (
    punto_credito_paciente_id serial NOT NULL,
    empresa_id character(2) NOT NULL,
    centro_utilidad character(2) NOT NULL,
    descripcion character varying(60) NOT NULL
);
ALTER TABLE ONLY puntos_credito_paciente
    ADD CONSTRAINT puntos_credito_paciente_pkey PRIMARY KEY (punto_credito_paciente_id);
ALTER TABLE ONLY puntos_credito_paciente
    ADD CONSTRAINT "$1" FOREIGN KEY (empresa_id, centro_utilidad) REFERENCES centros_utilidad(empresa_id, centro_utilidad) ON UPDATE CASCADE ON DELETE RESTRICT;
COMMENT ON TABLE puntos_credito_paciente IS 'Puntos desde donde se permite la elaboración de los pagarés, para los pacientes.';

CREATE TABLE userpermisos_credito_paciente (
    punto_credito_paciente_id integer NOT NULL,
    usuario_id integer NOT NULL
);
ALTER TABLE ONLY userpermisos_credito_paciente
    ADD CONSTRAINT userpermisos_credito_paciente_pkey PRIMARY KEY (punto_credito_paciente_id, usuario_id);
ALTER TABLE ONLY userpermisos_credito_paciente
    ADD CONSTRAINT "$1" FOREIGN KEY (punto_credito_paciente_id) REFERENCES puntos_credito_paciente(punto_credito_paciente_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY userpermisos_credito_paciente
    ADD CONSTRAINT "$2" FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
COMMENT ON TABLE userpermisos_credito_paciente IS 'Contiene los permisos de los usuarios que tienen acceso a credito paciente';

INSERT INTO puntos_credito_paciente VALUES (1, '01', '01', 'PAGARÉ');
INSERT INTO userpermisos_credito_paciente VALUES (1, 2);
INSERT INTO system_modulos VALUES ('Credito_Paciente', 'app', 'Modulo para la administración de los pagarés.', 1.00, '', '1', '1', '1');
INSERT INTO system_menus VALUES (65, 'CREDITO PACIENTE', 'Modulo para la elaboración de los pagarés', '0');
INSERT INTO system_menus_items VALUES (97, 65, 'CRÉDITO PARA PACIENTES', 'app', 'Credito_Paciente', 'user', 'main', 'Módulo para el Crédito a los Pacientes', 0);
INSERT INTO system_usuarios_menus VALUES (65, 1);
