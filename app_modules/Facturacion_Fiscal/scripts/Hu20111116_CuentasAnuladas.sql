CREATE TABLE cuentas_facturas_anuladas 
(
  cuenta_anulada_id SERIAL NOT NULL,
  empresa_id CHARACTER(2) NOT NULL,
  prefijo CHARACTER VARYING(4) NOT NULL,
  factura_fiscal INTEGER NOT NULL,
  centro_utilidad character(2) NOT NULL,
  numerodecuenta integer NOT NULL,
  ingreso integer NOT NULL,
  plan_id integer NOT NULL,
  fecha_cierre timestamp without time zone,
  total_cuenta numeric(12,2) DEFAULT 0 NOT NULL,
  abono_efectivo numeric(12,2) DEFAULT 0 NOT NULL,
  abono_cheque numeric(12,2) DEFAULT 0 NOT NULL,
  abono_tarjetas numeric(12,2) DEFAULT 0 NOT NULL,
  abono_chequespf numeric(12,2) DEFAULT 0 NOT NULL,
  abono_letras numeric(12,2) DEFAULT 0 NOT NULL,
  gravamen_valor_cubierto numeric(12,2) DEFAULT 0 NOT NULL,
  valor_cuota_paciente numeric(12,2) DEFAULT 0 NOT NULL,
  valor_nocubierto numeric(12,2) DEFAULT 0 NOT NULL,
  valor_cubierto numeric(12,2) DEFAULT 0 NOT NULL,
  porcentaje_descuento_empresa numeric(7,4) DEFAULT 0 NOT NULL,
  usuario_id integer NOT NULL,
  fecha_registro timestamp without time zone NOT NULL,
  gravamen_valor_nocubierto numeric(12,2) DEFAULT 0 NOT NULL,
  valor_descuento_empresa numeric(12,2) DEFAULT 0 NOT NULL,
  valor_descuento_paciente numeric(12,2) DEFAULT 0 NOT NULL,
  valor_cuota_moderadora numeric(12,2) DEFAULT 0 NOT NULL,
  tipo_afiliado_id character varying(2) NOT NULL,
  porcentaje_descuento_paciente numeric(7,4) DEFAULT 0 NOT NULL,
  semanas_cotizadas smallint DEFAULT 0 NOT NULL,
  abono_bonos numeric(12,2) DEFAULT 0 NOT NULL,
  autorizacion_int integer,
  autorizacion_ext integer,
  sw_estado_paciente character(1) DEFAULT 1 NOT NULL,
  usuario_cierre integer,
  valor_total_paciente numeric(12,2) DEFAULT 0 NOT NULL,
  valor_total_empresa numeric(12,2) DEFAULT 0 NOT NULL,
  valor_descuento_cuota_paciente numeric(12,2) DEFAULT 0 NOT NULL,
  valor_descuento_cuota_moderadora numeric(12,2) DEFAULT 0 NOT NULL,
  valor_total_cargos numeric(12,2) DEFAULT 0 NOT NULL,
  rango character varying(40) NOT NULL,
  sw_liquidacion_manual_habitaciones character(1) DEFAULT 0 NOT NULL,
  sw_corte character(1) DEFAULT 0 NOT NULL,
  departamento character varying(6)
);


COMMENT ON TABLE cuentas_facturas_anuladas IS 'Tabla donde se ingresa la informacion de las cuentas que pertencen a facturas anuladas';

COMMENT ON COLUMN cuentas_facturas_anuladas.cuenta_anulada_id IS '(PK) Identificador de la cuenta anulada';
COMMENT ON COLUMN cuentas_facturas_anuladas.empresa_id IS '(FK) Identificador de la empresa';
COMMENT ON COLUMN cuentas_facturas_anuladas.prefijo IS '(FK) Identificador del prefijo de la factura';
COMMENT ON COLUMN cuentas_facturas_anuladas.factura_fiscal IS '(FK) Identificador del prefijo de la factura';
COMMENT ON COLUMN cuentas_facturas_anuladas.centro_utilidad IS 'Identificador del centro de utilidad';
COMMENT ON COLUMN cuentas_facturas_anuladas.numerodecuenta IS 'Identificador del numero de cuenta';
COMMENT ON COLUMN cuentas_facturas_anuladas.ingreso IS 'FK llave foranea numero de ingreso del paciente';
COMMENT ON COLUMN cuentas_facturas_anuladas.plan_id IS 'FK llave foranea plan del paciente';
COMMENT ON COLUMN cuentas_facturas_anuladas.fecha_cierre IS 'Fecha en que se cierra la cuenta';
COMMENT ON COLUMN cuentas_facturas_anuladas.total_cuenta IS 'Total de la cuenta (suma de todos los valores)';
COMMENT ON COLUMN cuentas_facturas_anuladas.abono_efectivo IS 'Abonos realizados en efectivo a la cuenta';
COMMENT ON COLUMN cuentas_facturas_anuladas.abono_cheque IS 'Abonos realizados en cheque a la cuenta';
COMMENT ON COLUMN cuentas_facturas_anuladas.abono_tarjetas IS 'Abonos realizados con tarjeta a la cuenta';
COMMENT ON COLUMN cuentas_facturas_anuladas.abono_chequespf IS 'Abonos realizados con cheque postfechados a la cuenta';
COMMENT ON COLUMN cuentas_facturas_anuladas.abono_letras IS 'Abonos de letras a la cuenta';
COMMENT ON COLUMN cuentas_facturas_anuladas.gravamen_valor_cubierto IS 'Valor del gravamen pagado por la empresa';
COMMENT ON COLUMN cuentas_facturas_anuladas.valor_cuota_paciente IS 'Valor del copago del paciente';
COMMENT ON COLUMN cuentas_facturas_anuladas.valor_nocubierto IS 'Valor no cubierto por la EPS';
COMMENT ON COLUMN cuentas_facturas_anuladas.valor_cubierto IS 'Valor cubierto por la EPS';
COMMENT ON COLUMN cuentas_facturas_anuladas.porcentaje_descuento_empresa IS 'FK llave foranea descuento del cargo';
COMMENT ON COLUMN cuentas_facturas_anuladas.gravamen_valor_nocubierto IS 'Valor del gravamen pagado por el paciente';
COMMENT ON COLUMN cuentas_facturas_anuladas.sw_liquidacion_manual_habitaciones IS '0=>no es manual 1=>es manual';
COMMENT ON COLUMN cuentas_facturas_anuladas.departamento IS 'Departamento asociado a la cuenta cuando se crea desde una orden de servicio';

ALTER TABLE cuentas_facturas_anuladas ADD PRIMARY KEY (cuenta_anulada_id);

ALTER TABLE cuentas_facturas_anuladas
ADD FOREIGN KEY (plan_id) REFERENCES planes(plan_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE cuentas_facturas_anuladas
ADD FOREIGN KEY (ingreso) REFERENCES ingresos(ingreso) ON UPDATE CASCADE ON DELETE RESTRICT;
    
ALTER TABLE cuentas_facturas_anuladas
ADD FOREIGN KEY (empresa_id, centro_utilidad) REFERENCES centros_utilidad(empresa_id, centro_utilidad) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE cuentas_facturas_anuladas
ADD FOREIGN KEY (empresa_id, prefijo, factura_fiscal) REFERENCES fac_facturas(empresa_id, prefijo, factura_fiscal) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE cuentas_facturas_anuladas
ADD FOREIGN KEY (usuario_cierre) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE cuentas_facturas_anuladas
ADD FOREIGN KEY (departamento) REFERENCES departamentos(departamento) ON UPDATE CASCADE ON DELETE RESTRICT;

CREATE TABLE cuentas_detalle_anuladas 
(
  cuenta_anulada_id INTEGER NOT NULL,
  transaccion INTEGER NOT NULL,
  empresa_id character(2) NOT NULL,
  centro_utilidad character(2) NOT NULL,
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
  sw_cargue character(2) NOT NULL,
  departamento_al_cargar character varying(6) NOT NULL,
  paquete_codigo_id integer,
  sw_paquete_facturado switch
);


COMMENT ON TABLE cuentas_detalle_anuladas IS 'Tabla donde se registra el detalle de la cuenta asociada a una factura anulada';

COMMENT ON COLUMN cuentas_detalle_anuladas.cuenta_anulada_id IS '(PK - FK) Identificador de la cuenta anulada';
COMMENT ON COLUMN cuentas_detalle_anuladas.transaccion IS '(PK) Identificador de la transaccion del detalle de la cuenta';
COMMENT ON COLUMN cuentas_detalle_anuladas.tarifario_id IS 'FK llave foranea tarifario del cargo';
COMMENT ON COLUMN cuentas_detalle_anuladas.cargo IS 'FK llave foranea codigo del cargo';
COMMENT ON COLUMN cuentas_detalle_anuladas.cantidad IS 'Cantidad del cargo';
COMMENT ON COLUMN cuentas_detalle_anuladas.precio IS 'Precio del cargo o en el caso que sea un cargo agrupado la suma de sus cargos';
COMMENT ON COLUMN cuentas_detalle_anuladas.porcentaje_descuento_empresa IS 'Descuento del cargo';
COMMENT ON COLUMN cuentas_detalle_anuladas.valor_cargo IS 'Valor del cargo por la cantidad';
COMMENT ON COLUMN cuentas_detalle_anuladas.valor_nocubierto IS 'Valor no cubierto por la EPS';
COMMENT ON COLUMN cuentas_detalle_anuladas.valor_cubierto IS 'Valor cubierto por la EPS';
COMMENT ON COLUMN cuentas_detalle_anuladas.facturado IS 'Indica si el cargo se suma al total de la cuenta o no es facturado';
COMMENT ON COLUMN cuentas_detalle_anuladas.fecha_cargo IS 'Fecha en que se realizo el cargue de cargo';
COMMENT ON COLUMN cuentas_detalle_anuladas.paquete_codigo_id IS 'numero que indica el paquete al que pertenece el cargo';
COMMENT ON COLUMN cuentas_detalle_anuladas.sw_paquete_facturado IS 'estado que indica si el cargo dentro del paquete es facturado o no';

ALTER TABLE cuentas_detalle_anuladas ADD PRIMARY KEY (cuenta_anulada_id,transaccion);


ALTER TABLE cuentas_detalle_anuladas
ADD FOREIGN KEY (cuenta_anulada_id) REFERENCES cuentas_facturas_anuladas(cuenta_anulada_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE cuentas_detalle_anuladas
ADD FOREIGN KEY (sw_cargue) REFERENCES tipos_cargue(sw_cargue) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE cuentas_detalle_anuladas
ADD FOREIGN KEY (tarifario_id, cargo) REFERENCES tarifarios_detalle(tarifario_id, cargo) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE cuentas_detalle_anuladas
ADD FOREIGN KEY (departamento) REFERENCES departamentos(departamento) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE cuentas_detalle_anuladas
ADD FOREIGN KEY (servicio_cargo) REFERENCES servicios(servicio) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE cuentas_detalle_anuladas
ADD FOREIGN KEY (codigo_agrupamiento_id) REFERENCES cuentas_codigos_agrupamiento(codigo_agrupamiento_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE cuentas_detalle_anuladas
ADD FOREIGN KEY (consecutivo) REFERENCES bodegas_documentos_d(consecutivo) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE cuentas_detalle_anuladas
ADD FOREIGN KEY (departamento_al_cargar) REFERENCES departamentos(departamento) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY cuentas_detalle_anuladas
ADD FOREIGN KEY (empresa_id, centro_utilidad) REFERENCES centros_utilidad(empresa_id, centro_utilidad) ON UPDATE CASCADE ON DELETE RESTRICT;

SELECT copiar_detalle_anuladas('01',	'RB',1);
	

CREATE OR REPLACE FUNCTION copiar_detalle_anuladas(CHARACTER,CHARACTER VARYING,INTEGER) RETURNS BOOLEAN AS $$
DECLARE
  identificador INTEGER;
  cnt RECORD;
BEGIN
  FOR cnt IN  SELECT  * 
              FROM    fac_facturas_cuentas 
              WHERE   empresa_id = $1
              AND     prefijo = $2
              AND     factura_fiscal = $3
  LOOP
    identificador := (SELECT NEXTVAL('cuentas_facturas_anuladas_cuenta_anulada_id_seq'::regclass));
    INSERT INTO cuentas_facturas_anuladas 
      (
        cuenta_anulada_id,
        empresa_id ,
        prefijo ,
        factura_fiscal ,
        centro_utilidad,
        numerodecuenta,
        ingreso ,
        plan_id ,
        fecha_cierre ,
        total_cuenta ,
        abono_efectivo ,
        abono_cheque ,
        abono_tarjetas ,
        abono_chequespf ,
        abono_letras ,
        gravamen_valor_cubierto ,
        valor_cuota_paciente ,
        valor_nocubierto ,
        valor_cubierto ,
        porcentaje_descuento_empresa,
        usuario_id ,
        fecha_registro ,
        gravamen_valor_nocubierto ,
        valor_descuento_empresa ,
        valor_descuento_paciente ,
        valor_cuota_moderadora ,
        tipo_afiliado_id ,
        porcentaje_descuento_paciente ,
        semanas_cotizadas ,
        abono_bonos ,
        autorizacion_int ,
        autorizacion_ext ,
        sw_estado_paciente ,
        usuario_cierre ,
        valor_total_paciente ,
        valor_total_empresa ,
        valor_descuento_cuota_paciente ,
        valor_descuento_cuota_moderadora ,
        valor_total_cargos ,
        rango ,
        sw_liquidacion_manual_habitaciones ,
        sw_corte ,
        departamento
      )
    SELECT  identificador AS cuenta_anulada_id,
            $1 AS empresa_id,
            $2 AS prefijo,
            $3 AS factura_fiscal,
            centro_utilidad,
            numerodecuenta,
            ingreso ,
            plan_id ,
            fecha_cierre ,
            total_cuenta ,
            abono_efectivo ,
            abono_cheque ,
            abono_tarjetas ,
            abono_chequespf ,
            abono_letras ,
            gravamen_valor_cubierto ,
            valor_cuota_paciente ,
            valor_nocubierto ,
            valor_cubierto ,
            porcentaje_descuento_empresa,
            usuario_id ,
            fecha_registro ,
            gravamen_valor_nocubierto ,
            valor_descuento_empresa ,
            valor_descuento_paciente ,
            valor_cuota_moderadora ,
            tipo_afiliado_id ,
            porcentaje_descuento_paciente ,
            semanas_cotizadas ,
            abono_bonos ,
            autorizacion_int ,
            autorizacion_ext ,
            sw_estado_paciente ,
            usuario_cierre ,
            valor_total_paciente ,
            valor_total_empresa ,
            valor_descuento_cuota_paciente ,
            valor_descuento_cuota_moderadora ,
            valor_total_cargos ,
            rango ,
            sw_liquidacion_manual_habitaciones ,
            sw_corte ,
            departamento
    FROM    cuentas
    WHERE   numerodecuenta = cnt.numerodecuenta;
    
    INSERT INTO cuentas_detalle_anuladas 
    (
      cuenta_anulada_id ,
      transaccion ,
      empresa_id ,
      centro_utilidad ,
      departamento ,
      tarifario_id,
      cargo ,
      cantidad ,
      precio ,
      porcentaje_descuento_empresa ,
      valor_cargo ,
      valor_nocubierto ,
      valor_cubierto ,
      facturado ,
      fecha_cargo ,
      usuario_id ,
      fecha_registro ,
      sw_liq_manual ,
      valor_descuento_empresa ,
      valor_descuento_paciente ,
      porcentaje_descuento_paciente ,
      servicio_cargo ,
      autorizacion_int ,
      autorizacion_ext ,
      porcentaje_gravamen ,
      sw_cuota_paciente, 
      sw_cuota_moderadora ,
      codigo_agrupamiento_id ,
      consecutivo ,
      cargo_cups ,
      sw_cargue ,
      departamento_al_cargar ,
      paquete_codigo_id ,
      sw_paquete_facturado
    )
    SELECT  identificador AS cuenta_anulada_id,
            transaccion ,
            empresa_id ,
            centro_utilidad ,
            departamento ,
            tarifario_id,
            cargo ,
            cantidad ,
            precio ,
            porcentaje_descuento_empresa ,
            valor_cargo ,
            valor_nocubierto ,
            valor_cubierto ,
            facturado ,
            fecha_cargo ,
            usuario_id ,
            fecha_registro ,
            sw_liq_manual ,
            valor_descuento_empresa ,
            valor_descuento_paciente ,
            porcentaje_descuento_paciente ,
            servicio_cargo ,
            autorizacion_int ,
            autorizacion_ext ,
            porcentaje_gravamen ,
            sw_cuota_paciente ,
            sw_cuota_moderadora ,
            codigo_agrupamiento_id ,
            consecutivo ,
            cargo_cups ,
            sw_cargue ,
            departamento_al_cargar ,
            paquete_codigo_id ,
            sw_paquete_facturado
    FROM    cuentas_detalle
    WHERE   numerodecuenta = cnt.numerodecuenta;
  END LOOP;
  RETURN TRUE;
END;  
$$ LANGUAGE plpgsql SECURITY DEFINER;

CREATE OR REPLACE FUNCTION detalle_facturas_anuladas() RETURNS TRIGGER AS $$
DECLARE
  rst BOOLEAN;
BEGIN
  IF NEW.estado != OLD.estado AND (NEW.estado='2' OR NEW.estado='3') THEN 
    rst := (SELECT copiar_detalle_anuladas(OLD.empresa_id, OLD.prefijo, OLD.factura_fiscal));
  END IF ;
  RETURN NEW;
END;
$$ LANGUAGE plpgsql SECURITY DEFINER;

CREATE TRIGGER copia_detalle_factura_anulada AFTER UPDATE ON fac_facturas 
FOR EACH ROW EXECUTE PROCEDURE detalle_facturas_anuladas()