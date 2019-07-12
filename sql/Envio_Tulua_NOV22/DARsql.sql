
DROP TABLE userpermisos_centro_autorizacion_criticos;

CREATE TABLE userpermisos_centro_autorizacion_criticos (
    centro_critico_id integer NOT NULL,
    usuario_id integer NOT NULL,
    empresa_id character(2) NOT NULL,
    plan_id integer NOT NULL,
    sw_todos_planes character(1) NOT NULL,
    nivel_autorizador_id character(1) NOT NULL
);


ALTER TABLE ONLY userpermisos_centro_autorizacion_criticos
    ADD CONSTRAINT userpermisos_centro_autorizacion_criticos_pkey PRIMARY KEY (usuario_id, centro_critico_id);


ALTER TABLE ONLY userpermisos_centro_autorizacion_criticos
    ADD CONSTRAINT "$1" FOREIGN KEY (empresa_id) REFERENCES empresas(empresa_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY userpermisos_centro_autorizacion_criticos
    ADD  FOREIGN KEY (plan_id) REFERENCES planes(plan_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY userpermisos_centro_autorizacion_criticos
    ADD  FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;


		ALTER TABLE ONLY userpermisos_centro_autorizacion_criticos
    ADD  FOREIGN KEY (nivel_autorizador_id) REFERENCES autorizaciones_niveles_autorizador(nivel_autorizador_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY userpermisos_centro_autorizacion_criticos
    ADD  FOREIGN KEY (centro_critico_id) REFERENCES autorizaciones_centros_criticos(centro_critico_id) ON UPDATE CASCADE ON DELETE RESTRICT;




drop table audit_cuentas_detalle;
CREATE TABLE audit_cuentas_detalle (
    audit_cuenta_detalle_id serial NOT NULL,
    transaccion integer NOT NULL,
    empresa_id character(2) NOT NULL,
    centro_utilidad character(2) NOT NULL,
    numerodecuenta integer NOT NULL,
    departamento character varying(6) NOT NULL,
    tarifario_id character varying(4) NOT NULL,
    cargo character varying(10) NOT NULL,
    cantidad numeric(9,2) DEFAULT 0 NOT NULL,
    precio numeric(12,2) DEFAULT 0 NOT NULL,
    porcentaje_descuento_empresa numeric(9,2) DEFAULT 0 NOT NULL,
    valor_cargo numeric(12,2) DEFAULT 0 NOT NULL,
    valor_nocubierto numeric(12,2) DEFAULT 0 NOT NULL,
    valor_cubierto numeric(12,2) DEFAULT 0 NOT NULL,
    facturado character(1) DEFAULT 1 NOT NULL,
    fecha_cargo timestamp without time zone NOT NULL,
    usuario_id integer NOT NULL,
    fecha_registro timestamp without time zone NOT NULL,
    sw_liq_manual character(1) DEFAULT 0 NOT NULL,
    valor_descuento_empresa numeric(9,2) DEFAULT 0,
    valor_descuento_paciente numeric(9,2) DEFAULT 0,
    porcentaje_descuento_paciente numeric(9,2) DEFAULT 0,
    servicio_cargo character varying(2) NOT NULL,
    autorizacion_int integer,
    autorizacion_ext integer,
    porcentaje_gravamen numeric(9,2) DEFAULT 0 NOT NULL,
    sw_cuota_paciente character(1) DEFAULT 0 NOT NULL,
    sw_cuota_moderadora character(1) DEFAULT 0 NOT NULL,
    codigo_agrupamiento_id integer,
    consecutivo integer,
    usuario_id_act integer NOT NULL,
    fecha_registro_act timestamp without time zone NOT NULL,
    sw_actualizacion integer,
    CONSTRAINT "$7" CHECK (((servicio_cargo)::text <> '0'::text))
);

ALTER TABLE ONLY audit_cuentas_detalle
    ADD CONSTRAINT audit_cuentas_detalle_pkey PRIMARY KEY (audit_cuenta_detalle_id);

ALTER TABLE ONLY audit_cuentas_detalle
    ADD FOREIGN KEY (numerodecuenta) REFERENCES cuentas(numerodecuenta) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY audit_cuentas_detalle
    ADD  FOREIGN KEY (tarifario_id, cargo) REFERENCES tarifarios_detalle(tarifario_id, cargo) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY audit_cuentas_detalle
    ADD  FOREIGN KEY (departamento) REFERENCES departamentos(departamento) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY audit_cuentas_detalle
    ADD FOREIGN KEY (servicio_cargo) REFERENCES servicios(servicio) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY audit_cuentas_detalle
    ADD  FOREIGN KEY (autorizacion_int) REFERENCES autorizaciones(autorizacion) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY audit_cuentas_detalle
    ADD CONSTRAINT "$9" FOREIGN KEY (autorizacion_ext) REFERENCES autorizaciones(autorizacion) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY audit_cuentas_detalle
    ADD  FOREIGN KEY (codigo_agrupamiento_id) REFERENCES cuentas_codigos_agrupamiento(codigo_agrupamiento_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY audit_cuentas_detalle
    ADD  FOREIGN KEY (consecutivo) REFERENCES bodegas_documentos_d(consecutivo) ON UPDATE CASCADE ON DELETE RESTRICT;


DROP TABLE "cambio_responsable_detalle_actual";

CREATE TABLE cambio_responsable_detalle_actual (
		cambio_responsable_detalle_actual_id  serial  NOT NULL,
		cambio_responsable_id 	integer 	NOT NULL,
    transaccion integer NOT NULL,
    empresa_id character(2) NOT NULL,
    centro_utilidad character(2) NOT NULL,
    numerodecuenta integer NOT NULL,
    departamento character varying(6) NOT NULL,
    tarifario_id character varying(4) NOT NULL,
    cargo character varying(10) NOT NULL,
    cantidad numeric(9,2) DEFAULT 0 NOT NULL,
    precio numeric(12,2) DEFAULT 0 NOT NULL,
    porcentaje_descuento_empresa numeric(9,2) DEFAULT 0 NOT NULL,
    valor_cargo numeric(12,2) DEFAULT 0 NOT NULL,
    valor_nocubierto numeric(12,2) DEFAULT 0 NOT NULL,
    valor_cubierto numeric(12,2) DEFAULT 0 NOT NULL,
    facturado character(1) DEFAULT 1 NOT NULL,
    fecha_cargo timestamp without time zone NOT NULL,
    usuario_id integer NOT NULL,
    fecha_registro timestamp without time zone NOT NULL,
    sw_liq_manual character(1) DEFAULT 0 NOT NULL,
    valor_descuento_empresa numeric(9,2) DEFAULT 0,
    valor_descuento_paciente numeric(9,2) DEFAULT 0,
    porcentaje_descuento_paciente numeric(9,2) DEFAULT 0,
    servicio_cargo character varying(2) NOT NULL,
    autorizacion_int integer,
    autorizacion_ext integer,
    porcentaje_gravamen numeric(9,2) DEFAULT 0 NOT NULL,
    sw_cuota_paciente character(1) DEFAULT 0 NOT NULL,
    sw_cuota_moderadora character(1) DEFAULT 0 NOT NULL,
    codigo_agrupamiento_id integer,
    consecutivo integer,
    cargo_cups character varying(10),
    CONSTRAINT "$7" CHECK (((servicio_cargo)::text <> '0'::text))
);

ALTER TABLE ONLY cambio_responsable_detalle_actual
    ADD CONSTRAINT cambio_responsable_detalle_actual_pkey PRIMARY KEY (cambio_responsable_detalle_actual_id);


ALTER TABLE ONLY cambio_responsable_detalle_actual
    ADD FOREIGN KEY (cambio_responsable_id) REFERENCES cambio_responsable(cambio_responsable_id) ON UPDATE CASCADE ON DELETE CASCADE;



ALTER TABLE ONLY cambio_responsable_detalle_actual
    ADD FOREIGN KEY (transaccion)  REFERENCES cuentas_detalle(transaccion) ON UPDATE CASCADE ON DELETE CASCADE;


ALTER TABLE ONLY cambio_responsable_detalle_actual
    ADD FOREIGN KEY (numerodecuenta) REFERENCES cuentas(numerodecuenta) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY cambio_responsable_detalle_actual
    ADD FOREIGN KEY (tarifario_id, cargo) REFERENCES tarifarios_detalle(tarifario_id, cargo) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY cambio_responsable_detalle_actual
    ADD FOREIGN KEY (departamento) REFERENCES departamentos(departamento) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY cambio_responsable_detalle_actual
    ADD FOREIGN KEY (servicio_cargo) REFERENCES servicios(servicio) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY cambio_responsable_detalle_actual
    ADD FOREIGN KEY (autorizacion_int) REFERENCES autorizaciones(autorizacion) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY cambio_responsable_detalle_actual
    ADD FOREIGN KEY (autorizacion_ext) REFERENCES autorizaciones(autorizacion) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY cambio_responsable_detalle_actual
    ADD FOREIGN KEY (codigo_agrupamiento_id) REFERENCES cuentas_codigos_agrupamiento(codigo_agrupamiento_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY cambio_responsable_detalle_actual
    ADD FOREIGN KEY (consecutivo) REFERENCES bodegas_documentos_d(consecutivo) ON UPDATE CASCADE ON DELETE RESTRICT;


COMMENT ON TABLE cambio_responsable_detalle_actual IS 'Almacena el datalle de una cuenta';

COMMENT ON COLUMN cambio_responsable_detalle_actual.transaccion IS 'PK llava primaria identifica un transaccion que se realice en la cuenta';

COMMENT ON COLUMN cambio_responsable_detalle_actual.numerodecuenta IS 'FK llave foranea numero de la cuenta del paciente';


COMMENT ON COLUMN cambio_responsable_detalle_actual.tarifario_id IS 'FK llave foranea tarifario del cargo';


COMMENT ON COLUMN cambio_responsable_detalle_actual.cargo IS 'FK llave foranea codigo del cargo';


COMMENT ON COLUMN cambio_responsable_detalle_actual.cantidad IS 'Cantidad del cargo';


COMMENT ON COLUMN cambio_responsable_detalle_actual.precio IS 'Precio del cargo o en el caso que sea un cargo agrupado la suma de sus cargos';


COMMENT ON COLUMN cambio_responsable_detalle_actual.porcentaje_descuento_empresa IS 'Descuento del cargo';


COMMENT ON COLUMN cambio_responsable_detalle_actual.valor_cargo IS 'Valor del cargo por la cantidad';


COMMENT ON COLUMN cambio_responsable_detalle_actual.valor_nocubierto IS 'Valor no cubierto por la EPS';


COMMENT ON COLUMN cambio_responsable_detalle_actual.valor_cubierto IS 'Valor cubierto por la EPS';


COMMENT ON COLUMN cambio_responsable_detalle_actual.facturado IS 'Indica si el cargo se suma al total de la cuenta o no es facturado';


COMMENT ON COLUMN cambio_responsable_detalle_actual.fecha_cargo IS 'Fecha en que se realizo el cargue de cargo';


DROP TABLE "cambio_responsable_detalle_nuevo";


CREATE TABLE cambio_responsable_detalle_nuevo (
    cambio_responsable_detalle_nuevo_id serial NOT NULL,
    cambio_responsable_detalle_actual_id integer NOT NULL,
    transaccion integer NOT NULL,
    empresa_id character(2) NOT NULL,
    centro_utilidad character(2) NOT NULL,
    numerodecuenta integer NOT NULL,
    departamento character varying(6) NOT NULL,
    tarifario_id character varying(4) NOT NULL,
    cargo character varying(10) NOT NULL,
    cantidad numeric(9,2) DEFAULT 0 NOT NULL,
    precio numeric(12,2) DEFAULT 0 NOT NULL,
    porcentaje_descuento_empresa numeric(9,2) DEFAULT 0 NOT NULL,
    valor_cargo numeric(12,2) DEFAULT 0 NOT NULL,
    valor_nocubierto numeric(12,2) DEFAULT 0 NOT NULL,
    valor_cubierto numeric(12,2) DEFAULT 0 NOT NULL,
    facturado character(1) DEFAULT 1 NOT NULL,
    fecha_cargo timestamp without time zone NOT NULL,
    usuario_id integer NOT NULL,
    fecha_registro timestamp without time zone NOT NULL,
    sw_liq_manual character(1) DEFAULT 0 NOT NULL,
    valor_descuento_empresa numeric(9,2) DEFAULT 0,
    valor_descuento_paciente numeric(9,2) DEFAULT 0,
    porcentaje_descuento_paciente numeric(9,2) DEFAULT 0,
    servicio_cargo character varying(2) NOT NULL,
    autorizacion_int integer,
    autorizacion_ext integer,
    porcentaje_gravamen numeric(9,2) DEFAULT 0 NOT NULL,
    sw_cuota_paciente character(1) DEFAULT 0 NOT NULL,
    sw_cuota_moderadora character(1) DEFAULT 0 NOT NULL,
    codigo_agrupamiento_id integer,
    consecutivo integer,
    cargo_cups character varying(10),
    CONSTRAINT "$7" CHECK (((servicio_cargo)::text <> '0'::text))
);


ALTER TABLE ONLY cambio_responsable_detalle_nuevo
    ADD CONSTRAINT cambio_responsable_detalle_nuevo_pkey PRIMARY KEY (cambio_responsable_detalle_nuevo_id);



ALTER TABLE ONLY cambio_responsable_detalle_nuevo
    ADD FOREIGN KEY (cambio_responsable_detalle_actual_id) REFERENCES cambio_responsable_detalle_actual(cambio_responsable_detalle_actual_id) ON UPDATE CASCADE ON DELETE CASCADE;



ALTER TABLE ONLY cambio_responsable_detalle_nuevo
    ADD FOREIGN KEY (transaccion)  REFERENCES cuentas_detalle(transaccion) ON UPDATE CASCADE ON DELETE CASCADE;


ALTER TABLE ONLY cambio_responsable_detalle_nuevo
    ADD FOREIGN KEY (numerodecuenta) REFERENCES cuentas(numerodecuenta) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY cambio_responsable_detalle_nuevo
    ADD FOREIGN KEY (tarifario_id, cargo) REFERENCES tarifarios_detalle(tarifario_id, cargo) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY cambio_responsable_detalle_nuevo
    ADD FOREIGN KEY (departamento) REFERENCES departamentos(departamento) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY cambio_responsable_detalle_nuevo
    ADD FOREIGN KEY (servicio_cargo) REFERENCES servicios(servicio) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY cambio_responsable_detalle_nuevo
    ADD FOREIGN KEY (autorizacion_int) REFERENCES autorizaciones(autorizacion) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY cambio_responsable_detalle_nuevo
    ADD FOREIGN KEY (autorizacion_ext) REFERENCES autorizaciones(autorizacion) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY cambio_responsable_detalle_nuevo
    ADD FOREIGN KEY (codigo_agrupamiento_id) REFERENCES cuentas_codigos_agrupamiento(codigo_agrupamiento_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY cambio_responsable_detalle_nuevo
    ADD FOREIGN KEY (consecutivo) REFERENCES bodegas_documentos_d(consecutivo) ON UPDATE CASCADE ON DELETE RESTRICT;


COMMENT ON TABLE cambio_responsable_detalle_nuevo IS 'Almacena el datalle de una cuenta';

COMMENT ON COLUMN cambio_responsable_detalle_nuevo.transaccion IS 'PK llava primaria identifica un transaccion que se realice en la cuenta';

COMMENT ON COLUMN cambio_responsable_detalle_nuevo.numerodecuenta IS 'FK llave foranea numero de la cuenta del paciente';


COMMENT ON COLUMN cambio_responsable_detalle_nuevo.tarifario_id IS 'FK llave foranea tarifario del cargo';


COMMENT ON COLUMN cambio_responsable_detalle_nuevo.cargo IS 'FK llave foranea codigo del cargo';


COMMENT ON COLUMN cambio_responsable_detalle_nuevo.cantidad IS 'Cantidad del cargo';


COMMENT ON COLUMN cambio_responsable_detalle_nuevo.precio IS 'Precio del cargo o en el caso que sea un cargo agrupado la suma de sus cargos';


COMMENT ON COLUMN cambio_responsable_detalle_nuevo.porcentaje_descuento_empresa IS 'Descuento del cargo';


COMMENT ON COLUMN cambio_responsable_detalle_nuevo.valor_cargo IS 'Valor del cargo por la cantidad';


COMMENT ON COLUMN cambio_responsable_detalle_nuevo.valor_nocubierto IS 'Valor no cubierto por la EPS';


COMMENT ON COLUMN cambio_responsable_detalle_nuevo.valor_cubierto IS 'Valor cubierto por la EPS';


COMMENT ON COLUMN cambio_responsable_detalle_nuevo.facturado IS 'Indica si el cargo se suma al total de la cuenta o no es facturado';


COMMENT ON COLUMN cambio_responsable_detalle_nuevo.fecha_cargo IS 'Fecha en que se realizo el cargue de cargo';


DROP TABLE "tmp_division_cuenta";

CREATE TABLE tmp_division_cuenta (
		tmp_division_cuenta_id  serial  NOT NULL,
    transaccion integer NOT NULL,
    empresa_id character(2) NOT NULL,
    centro_utilidad character(2) NOT NULL,
    numerodecuenta integer NOT NULL,
    departamento character varying(6) NOT NULL,
    tarifario_id character varying(4) NOT NULL,
    cargo character varying(10) NOT NULL,
    cantidad numeric(9,2) DEFAULT 0 NOT NULL,
    precio numeric(12,2) DEFAULT 0 NOT NULL,
    porcentaje_descuento_empresa numeric(9,2) DEFAULT 0 NOT NULL,
    valor_cargo numeric(12,2) DEFAULT 0 NOT NULL,
    valor_nocubierto numeric(12,2) DEFAULT 0 NOT NULL,
    valor_cubierto numeric(12,2) DEFAULT 0 NOT NULL,
    facturado character(1) DEFAULT 1 NOT NULL,
    fecha_cargo timestamp without time zone NOT NULL,
    usuario_id integer NOT NULL,
    fecha_registro timestamp without time zone NOT NULL,
    sw_liq_manual character(1) DEFAULT 0 NOT NULL,
    valor_descuento_empresa numeric(9,2) DEFAULT 0,
    valor_descuento_paciente numeric(9,2) DEFAULT 0,
    porcentaje_descuento_paciente numeric(9,2) DEFAULT 0,
    servicio_cargo character varying(2) NOT NULL,
    autorizacion_int integer,
    autorizacion_ext integer,
    porcentaje_gravamen numeric(9,2) DEFAULT 0 NOT NULL,
    sw_cuota_paciente character(1) DEFAULT 0 NOT NULL,
    sw_cuota_moderadora character(1) DEFAULT 0 NOT NULL,
    codigo_agrupamiento_id integer,
    consecutivo integer,
    cargo_cups character varying(10),
    CONSTRAINT "$7" CHECK (((servicio_cargo)::text <> '0'::text))
);

ALTER TABLE ONLY tmp_division_cuenta
    ADD CONSTRAINT tmp_division_cuenta_pkey PRIMARY KEY (tmp_division_cuenta_id);


ALTER TABLE ONLY tmp_division_cuenta
    ADD FOREIGN KEY (numerodecuenta) REFERENCES cuentas(numerodecuenta) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY tmp_division_cuenta
    ADD FOREIGN KEY (tarifario_id, cargo) REFERENCES tarifarios_detalle(tarifario_id, cargo) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY tmp_division_cuenta
    ADD FOREIGN KEY (departamento) REFERENCES departamentos(departamento) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY tmp_division_cuenta
    ADD FOREIGN KEY (servicio_cargo) REFERENCES servicios(servicio) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY cambio_responsable_detalle_actual
    ADD FOREIGN KEY (autorizacion_int) REFERENCES autorizaciones(autorizacion) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY tmp_division_cuenta
    ADD FOREIGN KEY (autorizacion_ext) REFERENCES autorizaciones(autorizacion) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY tmp_division_cuenta
    ADD FOREIGN KEY (codigo_agrupamiento_id) REFERENCES cuentas_codigos_agrupamiento(codigo_agrupamiento_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY tmp_division_cuenta
    ADD FOREIGN KEY (consecutivo) REFERENCES bodegas_documentos_d(consecutivo) ON UPDATE CASCADE ON DELETE RESTRICT;


COMMENT ON TABLE tmp_division_cuenta IS 'Almacena el datalle de una cuenta';

COMMENT ON COLUMN tmp_division_cuenta.transaccion IS 'PK llava primaria identifica un transaccion que se realice en la cuenta';

COMMENT ON COLUMN tmp_division_cuenta.numerodecuenta IS 'FK llave foranea numero de la cuenta del paciente';


COMMENT ON COLUMN tmp_division_cuenta.tarifario_id IS 'FK llave foranea tarifario del cargo';


COMMENT ON COLUMN tmp_division_cuenta.cargo IS 'FK llave foranea codigo del cargo';


COMMENT ON COLUMN tmp_division_cuenta.cantidad IS 'Cantidad del cargo';


COMMENT ON COLUMN tmp_division_cuenta.precio IS 'Precio del cargo o en el caso que sea un cargo agrupado la suma de sus cargos';


COMMENT ON COLUMN tmp_division_cuenta.porcentaje_descuento_empresa IS 'Descuento del cargo';


COMMENT ON COLUMN tmp_division_cuenta.valor_cargo IS 'Valor del cargo por la cantidad';


COMMENT ON COLUMN tmp_division_cuenta.valor_nocubierto IS 'Valor no cubierto por la EPS';


COMMENT ON COLUMN tmp_division_cuenta.valor_cubierto IS 'Valor cubierto por la EPS';


COMMENT ON COLUMN tmp_division_cuenta.facturado IS 'Indica si el cargo se suma al total de la cuenta o no es facturado';


COMMENT ON COLUMN tmp_division_cuenta.fecha_cargo IS 'Fecha en que se realizo el cargue de cargo';

 ALTER TABLE "tmp_division_cuenta" ADD COLUMN cuenta integer;




--para que este pedaciot funcione hay que ejecutra primero el de lorena
DROP TABLE tmp_cuentas_detalle;

CREATE TABLE tmp_cuentas_detalle (
    transaccion serial NOT NULL,
    empresa_id character(2) NOT NULL,
    centro_utilidad character(2) NOT NULL,
    numerodecuenta integer NOT NULL,
    departamento character varying(6) NOT NULL,
    tarifario_id character varying(4) NOT NULL,
    cargo character varying(10) NOT NULL,
    cantidad numeric(9,2) DEFAULT 0 NOT NULL,
    precio numeric(12,2) DEFAULT 0 NOT NULL,
    porcentaje_descuento_empresa numeric(9,2) DEFAULT 0 NOT NULL,
    valor_cargo numeric(12,2) DEFAULT 0 NOT NULL,
    valor_nocubierto numeric(12,2) DEFAULT 0 NOT NULL,
    valor_cubierto numeric(12,2) DEFAULT 0 NOT NULL,
    facturado character(1) DEFAULT 1 NOT NULL,
    fecha_cargo timestamp without time zone NOT NULL,
    usuario_id integer NOT NULL,
    fecha_registro timestamp without time zone NOT NULL,
    sw_liq_manual character(1) DEFAULT 0 NOT NULL,
    valor_descuento_empresa numeric(9,2) DEFAULT 0,
    valor_descuento_paciente numeric(9,2) DEFAULT 0,
    porcentaje_descuento_paciente numeric(9,2) DEFAULT 0,
    servicio_cargo character varying(2) NOT NULL,
    autorizacion_int integer,
    autorizacion_ext integer,
    porcentaje_gravamen numeric(9,2) DEFAULT 0 NOT NULL,
    sw_cuota_paciente character(1) DEFAULT 0 NOT NULL,
    sw_cuota_moderadora character(1) DEFAULT 0 NOT NULL,
    codigo_agrupamiento_id character varying(10),
    secuencia_agrupamiento integer,
    CONSTRAINT "$7" CHECK (((servicio_cargo)::text <> '0'::text))
);

ALTER TABLE ONLY tmp_cuentas_detalle
    ADD CONSTRAINT tmp_cuentas_detalle_pkey PRIMARY KEY (transaccion);


ALTER TABLE ONLY tmp_cuentas_detalle
    ADD FOREIGN KEY (numerodecuenta) REFERENCES cuentas(numerodecuenta) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY tmp_cuentas_detalle
    ADD FOREIGN KEY (tarifario_id, cargo) REFERENCES tarifarios_detalle(tarifario_id, cargo) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY tmp_cuentas_detalle
    ADD FOREIGN KEY (departamento) REFERENCES departamentos(departamento) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY tmp_cuentas_detalle
    ADD FOREIGN KEY (servicio_cargo) REFERENCES servicios(servicio) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY tmp_cuentas_detalle
    ADD FOREIGN KEY (autorizacion_int) REFERENCES autorizaciones(autorizacion) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY tmp_cuentas_detalle
    ADD FOREIGN KEY (autorizacion_ext) REFERENCES autorizaciones(autorizacion) ON UPDATE CASCADE ON DELETE RESTRICT;

 ALTER TABLE "tmp_cuentas_detalle" ADD COLUMN "consecutivo" integer;
 ALTER TABLE "tmp_cuentas_detalle" ADD COLUMN "cargo_cups" varchar(10);
 ALTER TABLE "tmp_cuentas_detalle" ADD FOREIGN KEY (codigo_agrupamiento_id) REFERENCES cuentas_codigos_agrupamiento(codigo_agrupamiento_id) ON UPDATE CASCADE ON DELETE RESTRICT;
 ALTER TABLE "tmp_cuentas_detalle" ADD FOREIGN KEY (consecutivo) REFERENCES bodegas_documentos_d(consecutivo) ON UPDATE CASCADE ON DELETE RESTRICT;



----------------------------------------------------------------------


ALTER TABLE "autorizaciones_puntos" DROP COLUMN "departamento";
ALTER TABLE "autorizaciones_puntos" ADD COLUMN "departamento" varchar(6);
ALTER TABLE "autorizaciones_puntos" ADD FOREIGN KEY (departamento) REFERENCES departamentos(departamento) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE "grupos_tipos_cargo" DROP COLUMN "cargo_agrupamiento_sistema";

ALTER TABLE "grupos_tipos_cargo" ADD COLUMN "codigo_agrupamiento_id" integer;
ALTER TABLE "grupos_tipos_cargo" ADD FOREIGN KEY ("codigo_agrupamiento_id") REFERENCES "public"."cuentas_codigos_agrupamiento"("codigo_agrupamiento_id")  ON UPDATE CASCADE ON DELETE RESTRICT;

COMMENT ON COLUMN fac_facturas_cuentas.sw_tipo IS '0 es paciente 1 es cliente 2 es particular';

 ALTER TABLE "cuentas_detalle" DROP COLUMN "codigo_agrupamiento_id";
 ALTER TABLE "cuentas_detalle" DROP COLUMN "secuencia_agrupamiento";
 ALTER TABLE "cuentas_detalle" ADD COLUMN "codigo_agrupamiento_id" integer;
 ALTER TABLE "cuentas_detalle" ADD COLUMN "consecutivo" integer;
 ALTER TABLE "cuentas_detalle" ADD COLUMN "cargo_cups" varchar(10);

CREATE TABLE cuentas_codigos_agrupamiento (
    codigo_agrupamiento_id serial NOT NULL,
    descripcion character varying(255) NOT NULL,
    bodegas_doc_id integer,
    numeracion integer
);

ALTER TABLE ONLY cuentas_codigos_agrupamiento  ADD PRIMARY KEY (codigo_agrupamiento_id);
ALTER TABLE ONLY cuentas_codigos_agrupamiento  ADD FOREIGN KEY (bodegas_doc_id, numeracion) REFERENCES bodegas_documentos(bodegas_doc_id, numeracion) ON UPDATE CASCADE ON DELETE RESTRICT;


 ALTER TABLE "cuentas_detalle" ADD FOREIGN KEY (codigo_agrupamiento_id) REFERENCES cuentas_codigos_agrupamiento(codigo_agrupamiento_id) ON UPDATE CASCADE ON DELETE RESTRICT;
 ALTER TABLE "cuentas_detalle" ADD FOREIGN KEY (consecutivo) REFERENCES bodegas_documentos_d(consecutivo) ON UPDATE CASCADE ON DELETE RESTRICT;



ALTER TABLE "autorizaciones_puntos" DROP COLUMN "departamento";
ALTER TABLE "autorizaciones_puntos" ADD COLUMN "departamento" varchar(6);
ALTER TABLE "autorizaciones_puntos" ADD FOREIGN KEY (departamento) REFERENCES departamentos(departamento) ON UPDATE CASCADE ON DELETE RESTRICT;

COMMENT ON COLUMN fac_facturas_cuentas.sw_tipo IS '0 es paciente 1 es cliente 2 es particular';


CREATE TABLE auditoria_inactivar_cuentas (
    auditoria_inactivar_cuenta_id serial NOT NULL,
    numerodecuenta integer NOT NULL,
    fecha_registro timestamp without time zone NOT NULL,
    usuario_id integer NOT NULL
);


ALTER TABLE ONLY auditoria_inactivar_cuentas
    ADD CONSTRAINT auditoria_inactivar_cuentas_pkey PRIMARY KEY (auditoria_inactivar_cuenta_id);

ALTER TABLE ONLY auditoria_inactivar_cuentas
    ADD FOREIGN KEY (numerodecuenta) REFERENCES cuentas(numerodecuenta) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY auditoria_inactivar_cuentas
    ADD FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE "autorizaciones_os_solicitudes_requerimientos" ADD COLUMN "sw_telefonica" character(1);
update autorizaciones_os_solicitudes_requerimientos set sw_telefonica=0;
ALTER TABLE "autorizaciones_os_solicitudes_requerimientos" ALTER COLUMN "sw_telefonica" SET NOT NULL;
ALTER TABLE "autorizaciones_os_solicitudes_requerimientos" ALTER COLUMN "sw_telefonica" SET DEFAULT 0;
COMMENT ON COLUMN "autorizaciones_os_solicitudes_requerimientos"."sw_telefonica" IS '1 es telefonica 0 no es telefonica';



CREATE TABLE auditoria_cambio_proveedor (
		auditoria_cambio_proveedor_id serial,
    numero_orden_id integer NOT NULL,
		usuario_id integer NOT NULL,
    fecha_registro timestamp without time zone NOT NULL,
    tipo_id_tercero_ant character varying(3)  NULL,
    tercero_id_ant character varying(32)  NULL,
    plan_proveedor_id_ant integer  NULL,
		departamento_ant character varying(6) NULL,

    tipo_id_tercero_act character varying(3)  NULL,
    tercero_id_act character varying(32)  NULL,
    plan_proveedor_id_act integer  NULL,
		departamento_act character varying(6) NULL
);



ALTER TABLE ONLY auditoria_cambio_proveedor
    ADD CONSTRAINT auditoria_cambio_proveedor_pkey PRIMARY KEY (auditoria_cambio_proveedor_id);

ALTER TABLE ONLY auditoria_cambio_proveedor
    ADD FOREIGN KEY (numero_orden_id) REFERENCES os_maestro(numero_orden_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY auditoria_cambio_proveedor
    ADD  FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY auditoria_cambio_proveedor
    ADD  FOREIGN KEY (plan_proveedor_id_ant) REFERENCES planes_proveedores(plan_proveedor_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY auditoria_cambio_proveedor
    ADD  FOREIGN KEY (plan_proveedor_id_act) REFERENCES planes_proveedores(plan_proveedor_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY auditoria_cambio_proveedor
    ADD  FOREIGN KEY (tipo_id_tercero_ant, tercero_id_ant) REFERENCES terceros(tipo_id_tercero, tercero_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY auditoria_cambio_proveedor
    ADD  FOREIGN KEY (tipo_id_tercero_act, tercero_id_act) REFERENCES terceros(tipo_id_tercero, tercero_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY auditoria_cambio_proveedor
    ADD  FOREIGN KEY (departamento_ant) REFERENCES departamentos(departamento) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY auditoria_cambio_proveedor
    ADD  FOREIGN KEY (departamento_act) REFERENCES departamentos(departamento) ON UPDATE CASCADE ON DELETE RESTRICT;




CREATE TABLE auditoria_anular_solicitudes (
    hc_os_solicitud_id integer NOT NULL,
		usuario_id integer NOT NULL,
    fecha_registro timestamp without time zone NOT NULL,
		observacion text NOT NULL
);

ALTER TABLE ONLY auditoria_anular_solicitudes
    ADD CONSTRAINT auditoria_anular_solicitudes_pkey PRIMARY KEY (hc_os_solicitud_id);

ALTER TABLE ONLY auditoria_anular_solicitudes
    ADD  FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;



COMMENT ON COLUMN "hc_os_solicitudes"."sw_estado" IS '1 activa 0 paso pro proceso de autorizacion 2 anulada';


ALTER TABLE "centros_remision" ADD COLUMN "direccion" character varying(60);
ALTER TABLE "centros_remision" ADD COLUMN "telefono" character varying(30);

CREATE TABLE pacientes_remitidos (
    paciente_remitido_id serial NOT NULL,
    paciente_id character varying(32) NOT NULL,
    tipo_id_paciente character varying(3) NOT NULL,
    centro_remision character varying(10) NOT NULL,
    numero_remision character varying(10),
    diagnostico_id character varying(6),
    fecha_remision date NOT NULL,
    hora_remision time without time zone,
    observacion text,
    fecha_registro timestamp without time zone NOT NULL,
    usuario_id integer NOT NULL,
    triage_id integer,
    ingreso integer
);


ALTER TABLE ONLY pacientes_remitidos
    ADD CONSTRAINT pacientes_remitidos_pkey PRIMARY KEY (paciente_remitido_id);

ALTER TABLE ONLY pacientes_remitidos
    ADD FOREIGN KEY (paciente_id, tipo_id_paciente) REFERENCES pacientes(paciente_id, tipo_id_paciente) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY pacientes_remitidos
    ADD FOREIGN KEY (centro_remision) REFERENCES centros_remision(centro_remision) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY pacientes_remitidos
    ADD FOREIGN KEY (diagnostico_id) REFERENCES diagnosticos(diagnostico_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY pacientes_remitidos
    ADD  FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY pacientes_remitidos
    ADD FOREIGN KEY (triage_id) REFERENCES triages(triage_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY pacientes_remitidos
    ADD FOREIGN KEY (ingreso) REFERENCES ingresos(ingreso) ON UPDATE CASCADE ON DELETE RESTRICT;

COMMENT ON TABLE pacientes_remitidos IS 'datos de los pacientes que llegan remitidos a la institucion';

COMMENT ON COLUMN pacientes_remitidos.paciente_remitido_id IS 'llave primaria';


CREATE TABLE triages_pendientes_admitir (
    triage_pendiente_admitir_id serial NOT NULL,
    tipo_id_paciente character varying(3) NOT NULL,
    paciente_id character varying(32) NOT NULL,
    via_ingreso_id character varying(2) NOT NULL,
    comentario text,
    departamento character varying(6) NOT NULL,
    empresa_id character(2) NOT NULL,
    centro_utilidad character(2) NOT NULL,
    plan_id integer NOT NULL,
    tipo_afiliado_id character varying(2) NOT NULL,
    rango character varying(2) NOT NULL,
    semanas_cotizadas smallint DEFAULT 0 NOT NULL,
    autorizacion_int integer,
    autorizacion_ext integer,
    fecha_registro timestamp without time zone NOT NULL,
    usuario_id integer NOT NULL,
    causa_externa_id character varying(2),
    estacion_id character varying(4) NOT NULL,
    evento integer,
    triage_id integer NOT NULL
);


ALTER TABLE ONLY triages_pendientes_admitir
    ADD CONSTRAINT triages_pendientes_admitir_pkey PRIMARY KEY (triage_pendiente_admitir_id);

ALTER TABLE ONLY triages_pendientes_admitir
    ADD FOREIGN KEY (departamento) REFERENCES departamentos(departamento) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY triages_pendientes_admitir
    ADD FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY triages_pendientes_admitir
    ADD  FOREIGN KEY (paciente_id, tipo_id_paciente) REFERENCES pacientes(paciente_id, tipo_id_paciente) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY triages_pendientes_admitir
    ADD FOREIGN KEY (via_ingreso_id) REFERENCES vias_ingreso(via_ingreso_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY triages_pendientes_admitir
    ADD FOREIGN KEY (plan_id) REFERENCES planes(plan_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY triages_pendientes_admitir
    ADD FOREIGN KEY (empresa_id, centro_utilidad) REFERENCES centros_utilidad(empresa_id, centro_utilidad) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY triages_pendientes_admitir
    ADD FOREIGN KEY (plan_id, rango, tipo_afiliado_id) REFERENCES planes_rangos(plan_id, rango, tipo_afiliado_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY triages_pendientes_admitir
    ADD FOREIGN KEY (autorizacion_int) REFERENCES autorizaciones(autorizacion) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY triages_pendientes_admitir
    ADD FOREIGN KEY (autorizacion_ext) REFERENCES autorizaciones(autorizacion) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY triages_pendientes_admitir
    ADD FOREIGN KEY (causa_externa_id) REFERENCES causas_externas(causa_externa_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY triages_pendientes_admitir
    ADD FOREIGN KEY (estacion_id) REFERENCES estaciones_enfermeria(estacion_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY triages_pendientes_admitir
    ADD FOREIGN KEY (evento) REFERENCES soat_eventos(evento) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY triages_pendientes_admitir
    ADD FOREIGN KEY (triage_id) REFERENCES triages(triage_id) ON UPDATE CASCADE ON DELETE RESTRICT;


	ALTER TABLE triages ADD COLUMN ingreso integer;



INSERT INTO system_modulos VALUES ('Remisiones', 'app', 'Para el tramite de remisiones', 1.00, '', '1', '1', '1');

ALTER TABLE "triages" ADD COLUMN "diagnostico_id" character varying(6);


ALTER TABLE ONLY triages
    ADD FOREIGN KEY (diagnostico_id) REFERENCES diagnosticos(diagnostico_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY triages
    ADD FOREIGN KEY (ingreso) REFERENCES ingresos(ingreso) ON UPDATE CASCADE ON DELETE RESTRICT;



CREATE TABLE tipos_estratos (
    tipo_estrato_id character(2) NOT NULL,
    descripcion character varying(30) DEFAULT ''::character varying NOT NULL
);
ALTER TABLE ONLY tipos_estratos
    ADD CONSTRAINT tipos_estratos_pkey PRIMARY KEY (tipo_estrato_id);

	INSERT INTO tipos_estratos VALUES('1','ESTRATO 1');
	INSERT INTO tipos_estratos VALUES('2','ESTRATO 2');
	INSERT INTO tipos_estratos VALUES('3','ESTRATO 3');
	INSERT INTO tipos_estratos VALUES('4','ESTRATO 4');
	INSERT INTO tipos_estratos VALUES('5','ESTRATO 5');
	INSERT INTO tipos_estratos VALUES('6','ESTRATO 6');

CREATE TABLE tipo_comunas (
    tipo_pais_id character varying(4) NOT NULL,
    tipo_dpto_id character varying(4) NOT NULL,
    tipo_mpio_id character varying(4) NOT NULL,
    tipo_comuna_id character varying(4) NOT NULL,
    comuna character varying(30) DEFAULT ''::character varying NOT NULL
);

ALTER TABLE ONLY tipo_comunas
    ADD CONSTRAINT tipo_comunas_pkey PRIMARY KEY (tipo_pais_id, tipo_dpto_id, tipo_mpio_id, tipo_comuna_id);


ALTER TABLE ONLY tipo_comunas
    ADD FOREIGN KEY (tipo_pais_id, tipo_dpto_id, tipo_mpio_id) REFERENCES tipo_mpios(tipo_pais_id, tipo_dpto_id, tipo_mpio_id) ON UPDATE CASCADE ON DELETE RESTRICT;


COMMENT ON TABLE tipo_comunas IS 'Catalogo de Comunas de un Municipio';
COMMENT ON COLUMN tipo_comunas.tipo_pais_id IS 'Codigo de Pais FKEY tipo_dptos (tipo_pais_id)';
COMMENT ON COLUMN tipo_comunas.tipo_dpto_id IS 'Codigo de Dpto FKEY tipo_dptos (tipo_dpto_id)';
COMMENT ON COLUMN tipo_comunas.tipo_mpio_id IS 'Codigo del Municipio/Ciudad';
COMMENT ON COLUMN tipo_comunas.comuna IS 'Nombre de la Comuna';



CREATE TABLE tipo_barrios (
    tipo_pais_id character varying(4) NOT NULL,
    tipo_dpto_id character varying(4) NOT NULL,
    tipo_mpio_id character varying(4) NOT NULL,
    tipo_comuna_id character varying(4) NOT NULL,
    tipo_barrio_id character varying(4) NOT NULL,
    barrio character varying(30) DEFAULT ''::character varying NOT NULL,
    tipo_estrato_id character(2)
);

ALTER TABLE ONLY tipo_barrios
    ADD CONSTRAINT tipo_barrios_pkey PRIMARY KEY (tipo_pais_id, tipo_dpto_id, tipo_mpio_id, tipo_comuna_id, tipo_barrio_id);


ALTER TABLE ONLY tipo_barrios
    ADD FOREIGN KEY (tipo_pais_id, tipo_dpto_id, tipo_mpio_id, tipo_comuna_id) REFERENCES tipo_comunas(tipo_pais_id, tipo_dpto_id, tipo_mpio_id, tipo_comuna_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY tipo_barrios
    ADD FOREIGN KEY (tipo_estrato_id) REFERENCES tipos_estratos(tipo_estrato_id) ON UPDATE CASCADE ON DELETE RESTRICT;


COMMENT ON TABLE tipo_barrios IS 'Catalogo de los barrios de una comuna';
COMMENT ON COLUMN tipo_barrios.tipo_pais_id IS 'Codigo de Pais FKEY tipo_dptos (tipo_pais_id)';
COMMENT ON COLUMN tipo_barrios.tipo_dpto_id IS 'Codigo de Dpto FKEY tipo_dptos (tipo_dpto_id)';
COMMENT ON COLUMN tipo_barrios.tipo_mpio_id IS 'Codigo del Municipio/Ciudad';
COMMENT ON COLUMN tipo_barrios.barrio IS 'Nombre del barrio';


ALTER TABLE pacientes ADD COLUMN tipo_comuna_id character varying(4);
ALTER TABLE pacientes ADD COLUMN tipo_barrio_id character varying(4);
--como hago las referencias
ALTER TABLE pacientes ADD COLUMN tipo_estrato_id character varying(2);
ALTER TABLE ONLY pacientes
    ADD FOREIGN KEY (tipo_estrato_id) REFERENCES tipos_estratos(tipo_estrato_id) ON UPDATE CASCADE ON DELETE RESTRICT;



INSERT INTO system_modulos VALUES ('SolicitudManualAmbulatoria', 'app', 'Para la solicitud manual ambulatoria', 1.00, '', '1', '1', '1');

INSERT INTO "system_menus" ("menu_id", "menu_nombre", "descripcion", "sw_system") VALUES (58, 'SOLICITUD MANUAL AMBULATORIA', ''::character varying, 0);
INSERT INTO "system_menus_items" ("menu_item_id", "menu_id", "titulo", "modulo_tipo", "modulo", "tipo", "metodo", "descripcion", "indice_de_orden") VALUES (88, '58', 'SOLICITUD MANUAL AMBULATORIA', 'app', 'SolicitudManualAmbulatoria', 'user', 'main', 'Solicitud de apoyos ambulatorios'::character varying, 0);

INSERT INTO system_usuarios_menus VALUES (58, 2);


CREATE TABLE puntos_solicitud_manual (
    punto_solicitud_manual_id serial NOT NULL,
    descripcion character varying(40) NOT NULL,
    departamento character varying(6) NOT NULL
);


ALTER TABLE ONLY puntos_solicitud_manual
    ADD CONSTRAINT puntos_solicitud_manual_pkey PRIMARY KEY (punto_solicitud_manual_id);

ALTER TABLE ONLY puntos_solicitud_manual
    ADD FOREIGN KEY (departamento) REFERENCES departamentos(departamento) ON UPDATE CASCADE ON DELETE RESTRICT;


CREATE TABLE userpermisos_solicitud_manual (
    punto_solicitud_manual_id integer NOT NULL,
    usuario_id integer NOT NULL,
    plan_id integer NOT NULL,
    sw_todos_planes character(1) NOT NULL,
    nivel_autorizador_id character(1) NOT NULL
);


ALTER TABLE ONLY userpermisos_solicitud_manual
    ADD CONSTRAINT userpermisos_solicitud_manual_pkey PRIMARY KEY (punto_solicitud_manual_id,usuario_id);

ALTER TABLE ONLY userpermisos_solicitud_manual
    ADD FOREIGN KEY (punto_solicitud_manual_id) REFERENCES puntos_solicitud_manual(punto_solicitud_manual_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY userpermisos_solicitud_manual
    ADD FOREIGN KEY (plan_id) REFERENCES planes(plan_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY userpermisos_solicitud_manual
    ADD FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY userpermisos_solicitud_manual
    ADD  FOREIGN KEY (nivel_autorizador_id) REFERENCES autorizaciones_niveles_autorizador(nivel_autorizador_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE "signos_vitales_triages" ADD COLUMN "evaluacion_dolor" smallint;


CREATE TABLE remisiones_pacientes(
		remision_paciente_id serial NOT NULL,
    triage_id integer NOT NULL,
    tipo_id_paciente character varying(3) NOT NULL,
    paciente_id character varying(32) NOT NULL,
		motivo_consulta character varying(200) NOT NULL,
		observacion_medico character varying(200),
		observacion_remision character varying(200),
    fecha_registro timestamp without time zone DEFAULT now() NOT NULL,
    usuario_id integer NOT NULL
);

ALTER TABLE ONLY remisiones_pacientes
    ADD CONSTRAINT remisiones_pacientes_pkey PRIMARY KEY (remision_paciente_id);

ALTER TABLE ONLY remisiones_pacientes
    ADD FOREIGN KEY (paciente_id, tipo_id_paciente) REFERENCES pacientes(paciente_id, tipo_id_paciente) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY remisiones_pacientes
    ADD FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY remisiones_pacientes
    ADD  FOREIGN KEY (triage_id) REFERENCES triages(triage_id) ON UPDATE CASCADE ON DELETE RESTRICT;




CREATE TABLE remisiones_pacientes_diagnosticos(
		remision_paciente_diagnostico_id serial NOT NULL,
		remision_paciente_id integer NOT NULL,
		diagnostico_id  	character varying(6) NOT NULL
);
ALTER TABLE ONLY remisiones_pacientes_diagnosticos
    ADD CONSTRAINT remisiones_pacientes_diagnosticos_pkey PRIMARY KEY (remision_paciente_id,diagnostico_id);

ALTER TABLE ONLY remisiones_pacientes_diagnosticos
    ADD FOREIGN KEY (diagnostico_id) REFERENCES diagnosticos(diagnostico_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY remisiones_pacientes_diagnosticos
    ADD FOREIGN KEY (remision_paciente_id) REFERENCES remisiones_pacientes(remision_paciente_id) ON UPDATE CASCADE ON DELETE RESTRICT;




CREATE TABLE remisiones_pacientes_centros(
		remision_paciente_diagnostico_id serial NOT NULL,
		remision_paciente_id integer NOT NULL,
		centro_remision  	character varying(10) NOT NULL
);

ALTER TABLE ONLY remisiones_pacientes_centros
    ADD CONSTRAINT remisiones_pacientes_centros_pkey PRIMARY KEY (remision_paciente_id,centro_remision);

ALTER TABLE ONLY remisiones_pacientes_centros
    ADD FOREIGN KEY (centro_remision) REFERENCES centros_remision(centro_remision) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY remisiones_pacientes_centros
    ADD FOREIGN KEY (remision_paciente_id) REFERENCES remisiones_pacientes(remision_paciente_id) ON UPDATE CASCADE ON DELETE RESTRICT;



CREATE TABLE tmp_solicitud_manual(
		tmp_solicitud_manual_id serial NOT NULL,
		codigo integer,
    tipo_id_paciente character varying(3) NOT NULL,
    paciente_id character varying(32) NOT NULL,
		apoyod_tipo_id  character varying(3) NOT NULL,
		cargo_cups  character varying(10) NOT NULL,
    fecha_registro timestamp without time zone DEFAULT now() NOT NULL,
    usuario_id integer NOT NULL
);

ALTER TABLE ONLY tmp_solicitud_manual
    ADD CONSTRAINT tmp_solicitud_manual_pkey PRIMARY KEY (tmp_solicitud_manual_id);

ALTER TABLE ONLY tmp_solicitud_manual
    ADD FOREIGN KEY (paciente_id, tipo_id_paciente) REFERENCES pacientes(paciente_id, tipo_id_paciente) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY tmp_solicitud_manual
    ADD FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY tmp_solicitud_manual
    ADD FOREIGN KEY (apoyod_tipo_id) REFERENCES grupos_noqx_apoyod(grupo_tipo_cargo) ON UPDATE CASCADE ON DELETE RESTRICT;

CREATE TABLE tmp_solicitud_manual_detalle(
		tmp_solicitud_manual_detalle_id serial NOT NULL,
		tmp_solicitud_manual_id integer NOT NULL,
		tarifario_id character varying(4) NOT NULL,
		cargo  character varying(10) NOT NULL,
		cantidad  numeric(9,2)  NOT NULL,
		descripcion  character varying(600) NOT NULL
);

ALTER TABLE ONLY tmp_solicitud_manual_detalle
    ADD CONSTRAINT tmp_solicitud_manual_detalle_pkey PRIMARY KEY (tmp_solicitud_manual_detalle_id,tmp_solicitud_manual_id);

ALTER TABLE ONLY tmp_solicitud_manual_detalle
    ADD FOREIGN KEY (tmp_solicitud_manual_id) REFERENCES tmp_solicitud_manual(tmp_solicitud_manual_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY tmp_solicitud_manual_detalle
    ADD  FOREIGN KEY (tarifario_id, cargo) REFERENCES tarifarios_detalle(tarifario_id, cargo) ON UPDATE CASCADE ON DELETE RESTRICT;



ALTER TABLE triages DROP COLUMN diagnostico_id;


CREATE TABLE triages_diagnosticos (
    triage_id integer NOT NULL,
    diagnostico_id character varying(6) NOT NULL
);


ALTER TABLE ONLY triages_diagnosticos
    ADD CONSTRAINT triages_diagnosticos_pkey PRIMARY KEY (triage_id, diagnostico_id);


ALTER TABLE ONLY triages_diagnosticos
    ADD FOREIGN KEY (diagnostico_id) REFERENCES diagnosticos(diagnostico_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE signos_vitales_triages ADD COLUMN respuesta_motora_id  	character varying(2);
ALTER TABLE signos_vitales_triages ADD COLUMN respuesta_verbal_id  	character varying(2);
ALTER TABLE signos_vitales_triages ADD COLUMN apertura_ocular_id  	character varying(2);
ALTER TABLE signos_vitales_triages ADD COLUMN tipo_glasgow 	character varying(2);

COMMENT ON COLUMN signos_vitales_triages.tipo_glasgow IS '0 adulto 1 medico';



CREATE TABLE envios_despacho (
    envio_id integer NOT NULL,
		guia integer,
		empresa_mensajeria character varying(255),
		responsable character varying(100),
    observacion text,
    usuario_id integer NOT NULL,
    fecha_registro timestamp without time zone NOT NULL,
    sw_estado character(1) DEFAULT 0 NOT NULL
);

ALTER TABLE ONLY envios_despacho
    ADD CONSTRAINT envios_despacho_pkey PRIMARY KEY (envio_id);


ALTER TABLE ONLY envios_despacho
    ADD FOREIGN KEY (envio_id) REFERENCES envios(envio_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY envios_despacho
    ADD FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;


INSERT INTO system_modulos_variables VALUES ('Pacientes', 'app', 'NombreComuna', 'LOCALIDAD');
--INSERT INTO system_modulos_variables VALUES ('Pacientes', 'app', 'BarrioObligatorio', '1');


INSERT INTO system_modulos VALUES ('CentralImpresionHospitalizacion', 'app', 'Para la impresion de hospitalizacion', 1.00, '1', '1', '1', '1');

INSERT INTO "system_menus" ("menu_id", "menu_nombre", "descripcion", "sw_system") VALUES (62, 'IMPRESION HOSPITALIZACION', '', 0);
INSERT INTO "system_menus_items" ("menu_item_id", "menu_id", "titulo", "modulo_tipo", "modulo", "tipo", "metodo", "descripcion", "indice_de_orden") VALUES (93, '62', 'IMPRESION HOSPITALIZACION', 'app', 'CentralImpresionHospitalizacion', 'user', 'main', ''::character varying, 0);

INSERT INTO system_usuarios_menus VALUES (62, 2);


CREATE TABLE userpermisos_impresion_hospitalaria (
    estacion_id character varying(4) NOT NULL,
    usuario_id integer NOT NULL
);



ALTER TABLE ONLY userpermisos_impresion_hospitalaria
    ADD CONSTRAINT userpermisos_impresion_hospitalaria_pkey PRIMARY KEY (estacion_id,usuario_id);

CREATE TABLE pacientes_campos_obligatorios (
    campo character varying(50) NOT NULL,
    sw_mostrar character(1) NOT NULL,
		sw_obligatorio character(1) NOT NULL
);

ALTER TABLE ONLY pacientes_campos_obligatorios
    ADD CONSTRAINT pacientes_campos_obligatorios_pkey PRIMARY KEY (campo);


INSERT INTO pacientes_campos_obligatorios VALUES ('tipo_comuna_id', '1', '0');
INSERT INTO pacientes_campos_obligatorios VALUES ('tipo_estrato_id', '1', '0');
INSERT INTO pacientes_campos_obligatorios VALUES ('fecha_nacimiento_es_calculada', '1', '0');
INSERT INTO pacientes_campos_obligatorios VALUES ('historia_numero', '1', '0');
INSERT INTO pacientes_campos_obligatorios VALUES ('historia_prefijo', '1', '0');
INSERT INTO pacientes_campos_obligatorios VALUES ('residencia_direccion', '1', '0');
INSERT INTO pacientes_campos_obligatorios VALUES ('tipo_barrio_id', '1', '0');
INSERT INTO pacientes_campos_obligatorios VALUES ('residencia_telefono', '1', '0');


ALTER TABLE "pacientes_urgencias" ADD COLUMN "sw_estado" character(1);

UPDATE "pacientes_urgencias" SET sw_estado=1;

ALTER TABLE "pacientes_urgencias" ALTER COLUMN "sw_estado" SET DEFAULT 1;

COMMENT ON COLUMN pacientes_urgencias.sw_estado IS '1 cuando se crea 0 cuando es atendido';


CREATE TABLE puntos_impresion_hospitalaria (
    punto_impresion_hospitalaria_id serial NOT NULL,
    descripcion character varying(50) NOT NULL,
    empresa_id character(2) NOT NULL
);



ALTER TABLE ONLY puntos_impresion_hospitalaria
    ADD CONSTRAINT puntos_impresion_hospitalaria_pkey PRIMARY KEY (punto_impresion_hospitalaria_id);

ALTER TABLE ONLY puntos_impresion_hospitalaria
    ADD  FOREIGN KEY (empresa_id) REFERENCES empresas(empresa_id) ON UPDATE CASCADE ON DELETE RESTRICT;


CREATE TABLE puntos_impresion_hospitalaria_estaciones (
    punto_impresion_hospitalaria_id integer NOT NULL,
    estacion_id character varying(4) NOT NULL
);


ALTER TABLE ONLY puntos_impresion_hospitalaria_estaciones
    ADD CONSTRAINT puntos_impresion_hospitalaria_estaciones_pkey PRIMARY KEY (punto_impresion_hospitalaria_id,estacion_id);

ALTER TABLE ONLY puntos_impresion_hospitalaria_estaciones
    ADD FOREIGN KEY (estacion_id) REFERENCES estaciones_enfermeria(estacion_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY puntos_impresion_hospitalaria_estaciones
    ADD FOREIGN KEY (punto_impresion_hospitalaria_id) REFERENCES puntos_impresion_hospitalaria(punto_impresion_hospitalaria_id) ON UPDATE CASCADE ON DELETE RESTRICT;



DROP TABLE userpermisos_impresion_hospitalaria;

CREATE TABLE userpermisos_impresion_hospitalaria (
    punto_impresion_hospitalaria_id integer NOT NULL,
    usuario_id integer NOT NULL
);


ALTER TABLE ONLY userpermisos_impresion_hospitalaria
    ADD CONSTRAINT userpermisos_impresion_hospitalaria_pkey PRIMARY KEY (punto_impresion_hospitalaria_id,usuario_id);

ALTER TABLE ONLY userpermisos_impresion_hospitalaria
    ADD FOREIGN KEY (punto_impresion_hospitalaria_id) REFERENCES puntos_impresion_hospitalaria(punto_impresion_hospitalaria_id) ON UPDATE CASCADE ON DELETE RESTRICT;



INSERT INTO system_modulos VALUES ('Admisiones', 'app', 'Admisiones', 1.00, '1', '1', '1', '1');

CREATE TABLE hc_os_solicitudes_manuales_datos_adicionales (
    orden_servicio_id integer NOT NULL,
		departamento character varying(60),
		cama  character varying(20)

);

ALTER TABLE ONLY hc_os_solicitudes_manuales_datos_adicionales
    ADD PRIMARY KEY (orden_servicio_id);

ALTER TABLE ONLY hc_os_solicitudes_manuales_datos_adicionales
    ADD FOREIGN KEY (orden_servicio_id) REFERENCES os_ordenes_servicios(orden_servicio_id) ON UPDATE CASCADE ON DELETE RESTRICT;


CREATE TABLE egresos_no_atencion(
		egresos_no_atencion_id serial NOT NULL,
    tipo_id_paciente character varying(3) NOT NULL,
    paciente_id character varying(32) NOT NULL,
		observacion  text NOT NULL,
		ingreso integer,
		triage_id integer,
    fecha_registro timestamp without time zone NOT NULL,
    usuario_id integer NOT NULL
);

ALTER TABLE ONLY egresos_no_atencion
    ADD PRIMARY KEY (egresos_no_atencion_id);

ALTER TABLE ONLY egresos_no_atencion
    ADD FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY egresos_no_atencion
    ADD FOREIGN KEY (paciente_id, tipo_id_paciente) REFERENCES pacientes(paciente_id, tipo_id_paciente) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY egresos_no_atencion
    ADD FOREIGN KEY (ingreso) REFERENCES ingresos(ingreso) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY egresos_no_atencion
    ADD FOREIGN KEY (triage_id) REFERENCES triages(triage_id) ON UPDATE CASCADE ON DELETE RESTRICT;



--------DESPUES DEL 22 DE NOVIEMBRE---


CREATE TABLE puntos_salidas_pacientes (
    punto_salida_paciente_id serial NOT NULL,
    descripcion character varying(40) NOT NULL,
    empresa_id character(2) NOT NULL,
    centro_utilidad character(2) NOT NULL,
    sw_todos_cu character(1) NOT NULL
);


ALTER TABLE ONLY puntos_salidas_pacientes
    ADD CONSTRAINT puntos_salidas_pacientes_pkey PRIMARY KEY (punto_salida_paciente_id);

ALTER TABLE ONLY puntos_salidas_pacientes
    ADD FOREIGN KEY (empresa_id, centro_utilidad) REFERENCES centros_utilidad(empresa_id, centro_utilidad) ON UPDATE CASCADE ON DELETE RESTRICT;



CREATE TABLE userpermisos_salidas_pacientes (
    usuario_id integer NOT NULL,
    punto_salida_paciente_id integer NOT NULL
);


ALTER TABLE ONLY userpermisos_salidas_pacientes
    ADD CONSTRAINT userpermisos_salidas_pacientes_pkey PRIMARY KEY (usuario_id, punto_salida_paciente_id);

ALTER TABLE ONLY userpermisos_salidas_pacientes
    ADD FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY userpermisos_salidas_pacientes
    ADD FOREIGN KEY (punto_salida_paciente_id) REFERENCES puntos_salidas_pacientes(punto_salida_paciente_id) ON UPDATE CASCADE ON DELETE RESTRICT;


INSERT INTO system_modulos VALUES ('SalidaPacientes', 'app', 'Para la salida de pacientes', 1.00, '', '1', '1', '1');

INSERT INTO "system_menus" ("menu_id", "menu_nombre", "descripcion", "sw_system") VALUES (64, 'SALIDA PACIENTES', ''::character varying, 0);
INSERT INTO "system_menus_items" ("menu_item_id", "menu_id", "titulo", "modulo_tipo", "modulo", "tipo", "metodo", "descripcion", "indice_de_orden") VALUES (96, 64, 'SALIDA PACIENTE', 'app', 'SalidaPacientes', 'user', 'main', 'Salida de pacientes'::character varying, 0);

INSERT INTO system_usuarios_menus VALUES (64, 2);


