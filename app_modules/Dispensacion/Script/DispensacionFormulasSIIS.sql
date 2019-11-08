
DROP TABLE userpermisos_Dispensacion;
DROP TABLE  medicamento_farmacia_tmp;
DROP TABLE  hc_formulacion_despachos_medicamentos;
DROP TABLE  Pendiente_x_Dispensacion;


CREATE TABLE userpermisos_Dispensacion
(
  empresa_id CHARACTER(2) NOT NULL,
  centro_utilidad 	 character(2) NOT NULL,
  bodega    character(2) NOT NULL,
  usuario_id INTEGER NOT NULL,
  sw_activo CHARACTER(1) NOT NULL DEFAULT '1',
  sw_privilegios  CHARACTER(1) NOT NULL DEFAULT '0'
);

ALTER TABLE userpermisos_Dispensacion ADD PRIMARY KEY (empresa_id, centro_utilidad, bodega,usuario_id);
ALTER TABLE ONLY  userpermisos_Dispensacion ADD   FOREIGN KEY (empresa_id, centro_utilidad,bodega) REFERENCES bodegas(empresa_id, centro_utilidad,bodega) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE userpermisos_Dispensacion ADD FOREIGN KEY(usuario_id)
REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
COMMENT ON TABLE userpermisos_Dispensacion IS 'Tabla donde se registra el permiso sobre el modulo de dispensacion de formulas';
COMMENT ON COLUMN userpermisos_Dispensacion.empresa_id IS '(PK - FK) Identificador de la empresa a la que tiene permiso el usuario';
COMMENT ON COLUMN userpermisos_Dispensacion.usuario_id IS '(PK - FK) Identificador del usuario';
COMMENT ON COLUMN userpermisos_Dispensacion.sw_activo IS 'Identifica si el permiso sigue activo';
COMMENT ON COLUMN userpermisos_Dispensacion.sw_privilegios IS '0=>No tiene privilegios, 1=>Privilegios Basicos';
COMMENT ON COLUMN userpermisos_Dispensacion.centro_utilidad IS 'Centro utilidad de la Empresa';
COMMENT ON COLUMN userpermisos_Dispensacion.bodega IS 'Bodega de la empresa ';
GRANT ALL ON TABLE userpermisos_Dispensacion TO siis;

/* PROCESO DE DISPENSACION DE MEDICAMENTOS*/

 CREATE TABLE hc_formulacion_despachos_medicamentos
  (
    hc_formulacion_despacho_id SERIAL NOT NULL,
	evolucion_id                 integer not NULL,
	bodegas_doc_id               integer not null,
	numeracion                   integer not null,
	sw_estado                    CHARACTER VARYING(1) NOT NULL  DEFAULT '1'
  );
	/*ALTER TABLE hc_formulacion_despachos_medicamentos ADD FOREIGN KEY(evolucion_id)
REFERENCES hc_evoluciones(evolucion_id) ON UPDATE CASCADE ON DELETE RESTRICT;*/
ALTER TABLE hc_formulacion_despachos_medicamentos ADD PRIMARY KEY(hc_formulacion_despacho_id);

ALTER TABLE hc_formulacion_despachos_medicamentos ADD FOREIGN KEY(bodegas_doc_id,numeracion)
REFERENCES bodegas_documentos(bodegas_doc_id,numeracion) ON UPDATE CASCADE ON DELETE RESTRICT;
	
COMMENT ON TABLE hc_formulacion_despachos_medicamentos IS 'Tabla donde se registran la formula dispensada';
COMMENT ON COLUMN hc_formulacion_despachos_medicamentos.hc_formulacion_despacho_id IS '(PK) Identificacion de la tabla';
COMMENT ON COLUMN hc_formulacion_despachos_medicamentos.evolucion_id IS '(FK) Identificacion de la Evolucion';
COMMENT ON COLUMN hc_formulacion_despachos_medicamentos.bodegas_doc_id IS '(FK) Identificacion del Movimiento';
COMMENT ON COLUMN hc_formulacion_despachos_medicamentos.numeracion IS '(FK) Secuencia del Movimiento';
COMMENT ON COLUMN hc_formulacion_despachos_medicamentos.sw_estado IS '0=>Inactiva, 1=>Activa-Dispensando , 2=>Anulada';

GRANT ALL ON TABLE hc_formulacion_despachos_medicamentos TO siis;

ALTER TABLE hc_formulacion_despachos_medicamentos ADD fecha_entrega date null;
ALTER TABLE hc_formulacion_despachos_medicamentos ADD proxima_fecha_entrega date null;
ALTER TABLE hc_formulacion_despachos_medicamentos ADD persona_reclama character varying(60)  NULL ;
ALTER TABLE hc_formulacion_despachos_medicamentos ADD persona_reclama_tipo_id character varying(3) NULL ;
ALTER TABLE hc_formulacion_despachos_medicamentos ADD persona_reclama_id character varying(32) NULL ;


/* PENDIENTES DE LA DISPENSACION */
	CREATE TABLE hc_pendientes_por_dispensar
    (
      hc_pendiente_dispensacion_id serial not null,
	  evolucion_id                   integer not NULL,
	  codigo_medicamento 	character varying(60) not null,
	  cantidad integer not null,
	  bodegas_doc_id               integer  null,
	  numeracion                   integer  null,
	  sw_estado                    CHARACTER VARYING(1) NOT NULL  DEFAULT '0',
	  usuario_id integer NOT NULL,
      fecha_registro date not null
	);
	
	/*ALTER TABLE hc_pendientes_por_dispensar ADD FOREIGN KEY(evolucion_id)
	REFERENCES hc_evoluciones(evolucion_id) ON UPDATE CASCADE ON DELETE RESTRICT;*/
	
	ALTER TABLE hc_pendientes_por_dispensar ADD PRIMARY KEY(hc_pendiente_dispensacion_id);
	
	ALTER TABLE hc_pendientes_por_dispensar  ADD  FOREIGN KEY (codigo_medicamento) REFERENCES inventarios_productos(codigo_producto);
	ALTER TABLE hc_pendientes_por_dispensar  ADD  FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id);
	ALTER TABLE hc_pendientes_por_dispensar ADD FOREIGN KEY(bodegas_doc_id,numeracion)
	REFERENCES bodegas_documentos(bodegas_doc_id,numeracion) ON UPDATE CASCADE ON DELETE RESTRICT;

	COMMENT ON TABLE hc_pendientes_por_dispensar IS 'Tabla para registrar los pendientes  despues de relizar una dispensacion';
	COMMENT ON COLUMN hc_pendientes_por_dispensar.hc_pendiente_dispensacion_id IS '(PK ) Id de la Tabla';
	COMMENT ON COLUMN hc_pendientes_por_dispensar.evolucion_id IS '(FK)Identificacion de la Evolucion del paciente';
	COMMENT ON COLUMN hc_pendientes_por_dispensar.codigo_medicamento IS 'codigo_medicamento del medicamento formulado';
	COMMENT ON COLUMN hc_pendientes_por_dispensar.cantidad IS 'Cantidad  pendiente';
	COMMENT ON COLUMN hc_pendientes_por_dispensar.bodegas_doc_id IS '(FK) Identificacion del Movimiento';
	COMMENT ON COLUMN hc_pendientes_por_dispensar.numeracion IS '(FK) Secuencia del Movimiento';
	COMMENT ON COLUMN hc_pendientes_por_dispensar.sw_estado IS '0=>pendiente,1=>Entregado,2=>No reclama ';
	COMMENT ON COLUMN hc_pendientes_por_dispensar.usuario_id IS 'Id usuario';
	COMMENT ON COLUMN hc_pendientes_por_dispensar.fecha_registro IS 'fecha de registro';

	GRANT ALL ON TABLE hc_pendientes_por_dispensar TO siis;

	CREATE TABLE hc_dispensacion_medicamentos_tmp
	(
		hc_dispen_tmp_id SERIAL NOT NULL,
		evolucion_id                   integer not NULL,
		empresa_id CHARACTER(2) NOT NULL,
		centro_utilidad 	 character(2) NOT NULL,
		bodega    character(2) NOT NULL,
		codigo_producto 	character varying(60) not null,
		cantidad_despachada integer not null,
		fecha_vencimiento 	date 	not null,
		lote 	character varying(30) 	not null
	);
	
	ALTER TABLE hc_dispensacion_medicamentos_tmp ADD PRIMARY KEY(hc_dispen_tmp_id);
	ALTER TABLE hc_dispensacion_medicamentos_tmp ADD FOREIGN KEY(evolucion_id)
	REFERENCES  hc_evoluciones(evolucion_id) ON UPDATE CASCADE ON DELETE RESTRICT;
	ALTER TABLE hc_dispensacion_medicamentos_tmp  ADD  FOREIGN KEY (codigo_producto) REFERENCES inventarios_productos(codigo_producto);
	ALTER TABLE hc_dispensacion_medicamentos_tmp ADD codigo_formulado character varying(60) not null ;
	ALTER TABLE hc_dispensacion_medicamentos_tmp ADD usuario_id integer  null ;
	ALTER TABLE hc_dispensacion_medicamentos_tmp  ADD  FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id);
	ALTER TABLE hc_dispensacion_medicamentos_tmp ADD sw_entregado_off character (1) not null  default '0';

	COMMENT ON TABLE hc_dispensacion_medicamentos_tmp IS 'Tabla para registrar los temporales de los medicamentos dispensados';
	COMMENT ON COLUMN hc_dispensacion_medicamentos_tmp.hc_dispen_tmp_id IS '(PK ) Id de la Tabla';
	COMMENT ON COLUMN hc_dispensacion_medicamentos_tmp.evolucion_id IS '(FK)Identificacion de la Evolucion del paciente';
	COMMENT ON COLUMN hc_dispensacion_medicamentos_tmp.empresa_id IS 'Id de la empresa';
	COMMENT ON COLUMN hc_dispensacion_medicamentos_tmp.centro_utilidad IS 'Id del centro de utilidad';
	COMMENT ON COLUMN hc_dispensacion_medicamentos_tmp.bodega IS 'Id de la Bodega';
	COMMENT ON COLUMN hc_dispensacion_medicamentos_tmp.codigo_producto IS 'Codigo del producto';
	COMMENT ON COLUMN hc_dispensacion_medicamentos_tmp.cantidad_despachada IS 'Cantidad despachada';

	
	/*CONSTRAINT foreign_key02
	FOREIGN KEY (evolucion_id)
	REFERENCES public.hc_evoluciones(evolucion_id)
	ON DELETE RESTRICT
	ON UPDATE CASCADE*/

	CREATE TABLE  hc_formulacion_despachos_medicamentos_pendientes (
	bodegas_doc_id      integer NOT NULL,
	numeracion          integer NOT NULL,
	evolucion_id          integer NOT NULL,
	PRIMARY KEY (bodegas_doc_id, numeracion),
	CONSTRAINT foreign_key01
	FOREIGN KEY (bodegas_doc_id, numeracion)
	REFERENCES public.bodegas_documentos(bodegas_doc_id, numeracion)
	ON DELETE RESTRICT
	ON UPDATE CASCADE
	
		) WITH (
		OIDS = FALSE
		);

		ALTER TABLE public.hc_formulacion_despachos_medicamentos_pendientes
		OWNER TO siis;

		COMMENT ON TABLE public.hc_formulacion_despachos_medicamentos_pendientes
		IS 'Tabla Auxiliar de Documentos de Bodega, donde se registra el documento que fue utilizado para despachar pendientes de una formula';

		COMMENT ON COLUMN public.hc_formulacion_despachos_medicamentos_pendientes.bodegas_doc_id
		IS 'Campo que referencia un documento de bodega';

		COMMENT ON COLUMN public.hc_formulacion_despachos_medicamentos_pendientes.numeracion
		IS 'Numero consecutivo del documento creado';

		COMMENT ON COLUMN public.hc_formulacion_despachos_medicamentos_pendientes.evolucion_id
		IS 'Numero de Evolucion';

	

ALTER TABLE public.hc_formulacion_antecedentes
ADD COLUMN sw_autorizado  CHARACTER(1) NOT NULL DEFAULT '0';


COMMENT ON COLUMN public.hc_formulacion_antecedentes.sw_autorizado
IS 'Define si el producto Esta autorizado para despachar por medio de autorizacion (0)sin autorizacion (1)autorizado';


ALTER TABLE public.hc_formulacion_antecedentes
ADD COLUMN usuario_autoriza_id  integer  NULL;

COMMENT ON COLUMN public.hc_formulacion_antecedentes.usuario_autoriza_id
IS 'usuario que autorizo el despacho';


ALTER TABLE public.hc_formulacion_antecedentes
ADD COLUMN observacion_autorizacion  text  NULL;

COMMENT ON COLUMN public.hc_formulacion_antecedentes.observacion_autorizacion
IS 'observacion de la autorizacion';

ALTER TABLE public.hc_formulacion_antecedentes
ADD COLUMN fecha_registro_autorizacion  TIMESTAMP WITHOUT TIME ZONE  NULL ;

COMMENT ON COLUMN public.hc_formulacion_antecedentes.fecha_registro_autorizacion
IS 'Fecha de la Autorizacion';


ALTER TABLE public.hc_formulacion_antecedentes
ADD COLUMN sw_estado   CHARACTER VARYING(1) NOT NULL  DEFAULT '1';

COMMENT ON COLUMN public.hc_formulacion_antecedentes.sw_estado
IS 'Estado de la Formula';


CREATE TABLE hc_despacho_medicamentos_eventos
  (
		hc_despacho_evento            serial not null,
		paciente_id   	character varying(32)  NOT NULL,
		tipo_id_paciente 	character varying(3) 	not null,
		evolucion_id                integer not null,
		observacion                TEXT,
		Fecha_evento             DATE  NOT NULL,
		Fecha_Registro            TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
		usuario_id                 INTEGER NOT NULL,
		sw_estado        character varying(1) 	default ('1') not null
	
	);
	

    ALTER TABLE hc_despacho_medicamentos_eventos ADD PRIMARY KEY(hc_despacho_evento);
    ALTER TABLE hc_despacho_medicamentos_eventos ADD FOREIGN KEY (evolucion_id) REFERENCES hc_evoluciones(evolucion_id);
    ALTER TABLE hc_despacho_medicamentos_eventos  ADD  FOREIGN KEY (paciente_id,tipo_id_paciente) REFERENCES pacientes(paciente_id,tipo_id_paciente);
	ALTER TABLE  hc_despacho_medicamentos_eventos  ADD  FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id);
  		
	
	COMMENT ON TABLE hc_despacho_medicamentos_eventos IS 'Tabla para registrar Los eventos que ha tenido un paciente para el despacho ';
    COMMENT ON COLUMN hc_despacho_medicamentos_eventos.hc_despacho_evento IS '(PK ) Id de la Tabla';
    COMMENT ON COLUMN hc_despacho_medicamentos_eventos.paciente_id IS 'Id del paciente';
    COMMENT ON COLUMN hc_despacho_medicamentos_eventos.tipo_id_paciente IS 'Tipo Id del paciente';
    COMMENT ON COLUMN hc_despacho_medicamentos_eventos.evolucion_id IS 'Evolucion del Paciente';
    COMMENT ON COLUMN hc_despacho_medicamentos_eventos.observacion IS 'Observacion del evento';
    COMMENT ON COLUMN hc_despacho_medicamentos_eventos.Fecha_evento IS ' Fecha del evento  ';
	COMMENT ON COLUMN hc_despacho_medicamentos_eventos.fecha_registro IS 'fecha de registro';
    COMMENT ON COLUMN hc_despacho_medicamentos_eventos.usuario_id IS 'Id usuario';
	COMMENT ON COLUMN hc_despacho_medicamentos_eventos.sw_estado IS '1=>Activo, 0=>cerrado';
    GRANT ALL ON TABLE hc_despacho_medicamentos_eventos TO siis;
		

	CREATE TABLE hc_despacho_medicamentos_eventos_d
   (
		hc_despacho_evento_d          serial not null,
		hc_despacho_evento            integer not null,
	    codigo_medicamento 	          character varying(60) not null,
        cantidad                      integer not null
	);
		
		ALTER TABLE hc_despacho_medicamentos_eventos_d ADD PRIMARY KEY(hc_despacho_evento_d);
		ALTER TABLE ONLY hc_despacho_medicamentos_eventos_d  ADD  FOREIGN KEY (codigo_medicamento) REFERENCES inventarios_productos(codigo_producto);
		ALTER TABLE ONLY hc_despacho_medicamentos_eventos_d  ADD  FOREIGN KEY (hc_despacho_evento) REFERENCES hc_despacho_medicamentos_eventos(hc_despacho_evento);


		COMMENT ON TABLE hc_despacho_medicamentos_eventos_d IS 'Tabla para registrar El detalle del evento ';
		COMMENT ON COLUMN hc_despacho_medicamentos_eventos_d.hc_despacho_evento_d IS '(PK ) Id de la Tabla';
		COMMENT ON COLUMN hc_despacho_medicamentos_eventos_d.hc_despacho_evento IS '(FK) Id de hc_despacho_medicamentos_eventos';
		COMMENT ON COLUMN hc_despacho_medicamentos_eventos_d.codigo_medicamento IS 'codigo_medicamento del medicamento formulado';
		COMMENT ON COLUMN hc_despacho_medicamentos_eventos_d.cantidad IS 'Cantidad  a entregar';
		GRANT ALL ON TABLE hc_despacho_medicamentos_eventos_d TO siis;
    
    
   /* COLUMNAS NUEVAS */
    ALTER TABLE   bodegas_documentos_d  ADD fecha_vencimiento  DATE NULL;
    COMMENT ON COLUMN  bodegas_documentos_d.fecha_vencimiento IS 'Fecha de vencimiento del medicamento';
    ALTER TABLE bodegas_documentos_d  ADD lote  CHARACTER VARYING(30) NULL;
    COMMENT ON COLUMN  bodegas_documentos_d.lote IS 'lote del medicamento ';
    
    
    /* FUNCION ENVIADA POR HUGO */

    CREATE OR REPLACE FUNCTION inv_bodegas_movimiento_update_existencias() RETURNS TRIGGER AS $$
    DECLARE

        DOCUMENTO RECORD;
        VAREXISTENCIA NUMERIC;
        VAR_E INTEGER;
        INV RECORD;
        BODEGA_DESTINO RECORD;
        CONCEPTOS_EGRESOS RECORD;
        N_INV_EXISTENCIA NUMERIC;
        N_INV_COSTO NUMERIC;
        N_INV_COSTO_ANTERIOR NUMERIC;
        N_INV_COSTO_ULTIMA_COMPRA NUMERIC;
        N_INV_COSTO_PENULTIMA NUMERIC;
        INV_OPCION INTEGER;
    BEGIN
        INV_OPCION := 0;
        IF TG_OP = 'INSERT' THEN
        SELECT INTO DOCUMENTO
                a.*,
                c.inv_tipo_movimiento as tipo_movimiento,
                c.tipo_doc_general_id as tipo_doc_bodega_id
        FROM
                inv_bodegas_movimiento as a,
                documentos as b,
                tipos_doc_generales as c
        WHERE   a.empresa_id  = NEW.empresa_id
                AND a.prefijo = NEW.prefijo
                AND a.numero  = NEW.numero
                AND b.documento_id = a.documento_id
                AND b.empresa_id = a.empresa_id
                AND c.tipo_doc_general_id = b.tipo_doc_general_id;
        IF NOT FOUND THEN
            RAISE EXCEPTION 'DOCUMENTO NO ENCONTRADO EN [inv_bodegas_movimiento].';
        END IF;
        SELECT INTO INV
            a.existencia as ext_bodega,
            b.existencia as ext_inv,
            b.costo_anterior,
            b.costo,
            b.costo_penultima_compra,
            b.costo_ultima_compra,
            ef.lote
        FROM
            existencias_bodegas as a
            LEFT JOIN existencias_bodegas_lote_fv ef
            ON( a.empresa_id = ef.empresa_id
            AND a.centro_utilidad  = ef.centro_utilidad
            AND a.bodega = ef.bodega
            AND a.codigo_producto = ef.codigo_producto
            AND ef.fecha_vencimiento = NEW.fecha_vencimiento
            AND ef.lote = NEW.lote ),
            inventarios as b
        WHERE
            a.empresa_id = NEW.empresa_id
            AND a.centro_utilidad  = NEW.centro_utilidad
            AND a.bodega = NEW.bodega
            AND a.codigo_producto = NEW.codigo_producto
            AND b.empresa_id = a.empresa_id
            AND b.codigo_producto = a.codigo_producto;
        IF NOT FOUND THEN
            RAISE EXCEPTION 'EL PRODUCTO [%] NO ESTA REGISTRADO EN LA BODEGA[%] C.U[%] EMPRESA[%].', NEW.codigo_producto, NEW.bodega, NEW.centro_utilidad, NEW.empresa_id;
        END IF;
        IF DOCUMENTO.tipo_movimiento = 'I' THEN
            INV_OPCION := 1;
            IF DOCUMENTO.sw_estado != '1' THEN
                RETURN NEW;
            END IF;
            IF DOCUMENTO.tipo_doc_bodega_id IN ('I003','I005') THEN
                NEW.total_costo := (NEW.cantidad * INV.costo);
                NEW.porcentaje_gravamen := 0;
                VAREXISTENCIA := (INV.ext_bodega + NEW.cantidad);
                NEW.existencia_bodega := INV.ext_bodega;
                NEW.existencia_inventario := INV.ext_inv;
                NEW.costo_inventario := INV.costo;
                UPDATE existencias_bodegas
                SET existencia = VAREXISTENCIA
                WHERE
                    empresa_id = NEW.empresa_id
                    AND centro_utilidad  = NEW.centro_utilidad
                    AND bodega = NEW.bodega
                    AND codigo_producto = NEW.codigo_producto;
            ELSE

                N_INV_EXISTENCIA := INV.ext_bodega + NEW.cantidad;
                N_INV_COSTO_ANTERIOR := INV.costo;
                N_INV_COSTO_ULTIMA_COMPRA := (NEW.total_costo / NEW.cantidad);
                N_INV_COSTO_PENULTIMA := INV.costo_ultima_compra;
                N_INV_COSTO := (((INV.ext_inv * INV.costo) + NEW.total_costo) / (INV.ext_inv + NEW.cantidad));
                VAREXISTENCIA := (INV.ext_bodega + NEW.cantidad);

                NEW.existencia_bodega := INV.ext_bodega;
                NEW.existencia_inventario := INV.ext_inv;
                NEW.costo_inventario := INV.costo;
                UPDATE inventarios
                SET
                    costo = N_INV_COSTO,
                    costo_anterior = N_INV_COSTO_ANTERIOR,
                    costo_ultima_compra = N_INV_COSTO_ULTIMA_COMPRA,
                    costo_penultima_compra = N_INV_COSTO_PENULTIMA
                WHERE
                    empresa_id = NEW.empresa_id
                    AND codigo_producto = NEW.codigo_producto;

                UPDATE existencias_bodegas
                SET existencia = VAREXISTENCIA
                WHERE
                    empresa_id = NEW.empresa_id
                    AND centro_utilidad  = NEW.centro_utilidad
                    AND bodega = NEW.bodega
                    AND codigo_producto = NEW.codigo_producto;

            END IF;

        ELSIF DOCUMENTO.tipo_movimiento = 'E' THEN
            INV_OPCION := 2;
            IF DOCUMENTO.sw_estado != '1' THEN
                RETURN NEW;
            END IF;

            IF DOCUMENTO.tipo_doc_bodega_id = 'E007' THEN

                SELECT INTO CONCEPTOS_EGRESOS *
                FROM inv_bodegas_movimiento_conceptos_egresos
                WHERE   empresa_id  = NEW.empresa_id
                        AND prefijo = NEW.prefijo
                        AND numero  = NEW.numero;

                IF NOT FOUND THEN
                    RAISE EXCEPTION 'NO EXISTEN DATOS DEL CONCEPTO DE EGRESO. ';
                END IF;

                IF CONCEPTOS_EGRESOS.sw_costo_manual = '0' THEN
                    NEW.total_costo := (NEW.cantidad * INV.costo);
                END IF;

            ELSE
                NEW.total_costo := (NEW.cantidad * INV.costo);
            END IF;

            NEW.porcentaje_gravamen := 0;
            NEW.existencia_bodega := INV.ext_bodega;
            NEW.existencia_inventario := INV.ext_inv;
            NEW.costo_inventario := INV.costo;

            VAREXISTENCIA := (INV.ext_bodega - NEW.cantidad);

            IF VAREXISTENCIA < 0 THEN
                RAISE EXCEPTION 'LA EXISTENCIA EN LA BODEGA NO PUEDE QUEDAR POR DEBAJO DE CERO. [%] ', NEW.codigo_producto;
            END IF;

            UPDATE existencias_bodegas
            SET existencia = VAREXISTENCIA
            WHERE
                empresa_id = NEW.empresa_id
                AND centro_utilidad  = NEW.centro_utilidad
                AND bodega = NEW.bodega
                AND codigo_producto = NEW.codigo_producto;


        ELSIF DOCUMENTO.tipo_movimiento = 'T' THEN
            INV_OPCION := 3;
            IF DOCUMENTO.sw_estado != '1' THEN
                RETURN NEW;
            END IF;


            SELECT INTO BODEGA_DESTINO *
            FROM inv_bodegas_movimiento_traslados
            WHERE   empresa_id  = NEW.empresa_id
                    AND prefijo = NEW.prefijo
                    AND numero  = NEW.numero;

            IF NOT FOUND THEN
                RAISE EXCEPTION 'NO EXISTEN DATOS DEL TRASLADO. ';
            END IF;

            VAR_E := (SELECT COUNT(*) FROM  existencias_bodegas
                        WHERE
                            empresa_id = BODEGA_DESTINO.empresa_id
                            AND centro_utilidad  = BODEGA_DESTINO.centro_utilidad_destino
                            AND bodega = BODEGA_DESTINO.bodega_destino
                            AND codigo_producto = NEW.codigo_producto);

            IF VAR_E < 0 THEN
                RAISE EXCEPTION 'NO EXISTE EL PRODUCTO EN LA BODEGA DESTINO.';
            END IF;

            NEW.total_costo := (NEW.cantidad * INV.costo);
            NEW.porcentaje_gravamen := 0;
            NEW.existencia_bodega := INV.ext_bodega;
            NEW.existencia_inventario := INV.ext_inv;
            NEW.costo_inventario := INV.costo;

            VAREXISTENCIA := (INV.ext_bodega - NEW.cantidad);

            IF VAREXISTENCIA < 0 THEN
                RAISE EXCEPTION 'LA EXISTENCIA EN LA BODEGA NO PUEDE QUEDAR POR DEBAJO DE CERO. [%]', NEW.codigo_producto;
            END IF;

            UPDATE existencias_bodegas
            SET existencia = VAREXISTENCIA
            WHERE
                empresa_id = NEW.empresa_id
                AND centro_utilidad  = NEW.centro_utilidad
                AND bodega = NEW.bodega
                AND codigo_producto = NEW.codigo_producto;


            UPDATE existencias_bodegas
            SET existencia = (existencia + NEW.cantidad)
            WHERE
                empresa_id = BODEGA_DESTINO.empresa_id
                AND centro_utilidad  = BODEGA_DESTINO.centro_utilidad_destino
                AND bodega = BODEGA_DESTINO.bodega_destino
                AND codigo_producto = NEW.codigo_producto;
        
        ELSIF DOCUMENTO.tipo_movimiento = 'C' THEN
            RETURN NEW;
        ELSIF DOCUMENTO.tipo_movimiento = 'D' THEN
            RETURN NEW;
        ELSE
            RAISE EXCEPTION 'EL TIPO DE MOVIMIENTO DEL DOCUMENTO NO ES VALIDO CAMPO[tipos_doc_generales.inv_tipo_movimiento = %] PARA EL [tipo_doc_general_id = %]', DOCUMENTO.tipo_movimiento, DOCUMENTO.tipo_doc_bodega_id;
        END IF;
        IF INV.lote IS NULL THEN
          INSERT INTO existencias_bodegas_lote_fv 
          VALUES 
          (
            NEW.empresa_id,
            NEW.centro_utilidad,
            NEW.codigo_producto,
            NEW.bodega,
            NEW.fecha_vencimiento,
            NEW.lote,
            NEW.cantidad,
            NEW.cantidad
          );
        ELSE 
          IF INV_OPCION = 1 THEN
            UPDATE existencias_bodegas_lote_fv
            SET existencia_inicial =  existencia_inicial + NEW.cantidad,
                existencia_actual =  existencia_actual + NEW.cantidad
            WHERE empresa_id = NEW.empresa_id
            AND centro_utilidad = NEW.centro_utilidad
            AND bodega = NEW.bodega
            AND codigo_producto = NEW.codigo_producto
            AND fecha_vencimiento = NEW.fecha_vencimiento
            AND lote = NEW.lote; 

          ELSIF INV_OPCION = 2 THEN
            UPDATE existencias_bodegas_lote_fv
            SET existencia_actual =  existencia_actual - NEW.cantidad
            WHERE empresa_id = NEW.empresa_id
            AND centro_utilidad = NEW.centro_utilidad
            AND bodega = NEW.bodega
            AND codigo_producto = NEW.codigo_producto
            AND fecha_vencimiento = NEW.fecha_vencimiento
            AND lote = NEW.lote; 
          END IF;
        END IF; 
        RETURN NEW;
    END IF;

END;
$$ LANGUAGE plpgsql SECURITY DEFINER;
