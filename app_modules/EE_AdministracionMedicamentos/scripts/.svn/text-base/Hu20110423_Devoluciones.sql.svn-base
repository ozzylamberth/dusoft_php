ALTER TABLE inv_solicitudes_devolucion_d ADD COLUMN  codigo_producto_sol character varying(40);
ALTER TABLE inv_solicitudes_devolucion_d ADD FOREIGN KEY (codigo_producto_sol) 
REFERENCES inventarios_productos(codigo_producto) ON UPDATE CASCADE ON DELETE RESTRICT;
COMMENT ON COLUMN inv_solicitudes_devolucion_d.codigo_producto_sol IS 'Codigo del producto solicitado, sobre el cual se esta devolviendo';

CREATE OR REPLACE FUNCTION bodega_paciente_control_bodegas() RETURNS TRIGGER AS $$
DECLARE
  SOLICITUD RECORD;
  BODEGA_PACIENTE_RECORD RECORD;
  REGISTROS RECORD;
  CANTIDAD_SOLICITADA NUMERIC;
  NUM_INGRESO INTEGER;
  CANTIDAD_PACIENTE NUMERIC;
  CODIGO_EVAL CHARACTER VARYING(40);
BEGIN
  ----------------------------------------------------------------------------------------------------------------------
  -- SOLICITUDES
  ----------------------------------------------------------------------------------------------------------------------
  IF TG_RELNAME = 'hc_solicitudes_medicamentos_d' THEN

    SELECT INTO SOLICITUD * FROM hc_solicitudes_medicamentos WHERE solicitud_id = NEW.solicitud_id;

    IF NOT FOUND THEN
         RAISE EXCEPTION 'ERROR TRIGGER, NUMERO DE SOLICITUD [%] NO ESTA EN LA TABLA [hc_solicitudes_medicamentos]',NEW.solicitud_id;
    END IF;

    IF SOLICITUD.sw_estado = '0'  THEN

         IF TG_OP = 'INSERT' THEN

              SELECT INTO BODEGA_PACIENTE_RECORD * FROM bodega_paciente WHERE ingreso=SOLICITUD.ingreso AND codigo_producto=NEW.medicamento_id;

              IF NOT FOUND THEN
                   INSERT INTO bodega_paciente(ingreso,codigo_producto,sw_tipo_producto,cantidad_en_solicitud,total_solicitado)
                   VALUES( SOLICITUD.ingreso,NEW.medicamento_id,'M',NEW.cant_solicitada,NEW.cant_solicitada);
              ELSE
                   UPDATE bodega_paciente
                   SET cantidad_en_solicitud = cantidad_en_solicitud + NEW.cant_solicitada,
                   total_solicitado = total_solicitado + NEW.cant_solicitada
                   WHERE ingreso=BODEGA_PACIENTE_RECORD.ingreso AND codigo_producto=BODEGA_PACIENTE_RECORD.codigo_producto;
              END IF;

              RETURN NULL;

         ELSE
              RAISE EXCEPTION 'NO SE PUEDE ACTUALIZAR O ELIMINAR DETALLES DE UNA SOLICITUD.';
         END IF;

    ELSIF SOLICITUD.sw_estado = '3' THEN

         IF TG_OP = 'INSERT' THEN

              SELECT INTO BODEGA_PACIENTE_RECORD * FROM bodega_paciente WHERE ingreso=SOLICITUD.ingreso AND codigo_producto=NEW.medicamento_id;

              IF NOT FOUND THEN
                   RAISE EXCEPTION 'NO SE PUEDE INSERTAR REGISTROS EN UNA SOLICITUD CANCELADA, SI NO ESTABAN REGISTRADOS EN bodega_paciente.';
              ELSE
                   UPDATE bodega_paciente
                   SET total_cancelado_por_la_bodega = total_cancelado_por_la_bodega + NEW.cant_solicitada
                   WHERE ingreso=BODEGA_PACIENTE_RECORD.ingreso AND codigo_producto=BODEGA_PACIENTE_RECORD.codigo_producto;
              END IF;

              RETURN NULL;

         ELSE
              RAISE EXCEPTION 'NO SE PUEDE ACTUALIZAR O ELIMINAR DETALLES DE UNA SOLICITUD.';
         END IF;

    ELSIF SOLICITUD.sw_estado = '4' THEN

         IF TG_OP = 'INSERT' THEN
              SELECT INTO BODEGA_PACIENTE_RECORD * FROM bodega_paciente WHERE ingreso=SOLICITUD.ingreso AND codigo_producto=NEW.medicamento_id;

              IF NOT FOUND THEN
                   INSERT INTO bodega_paciente(ingreso,codigo_producto,sw_tipo_producto,total_consumo_directo,cantidad_en_solicitud)
                   VALUES( SOLICITUD.ingreso,NEW.medicamento_id,'M',NEW.cant_solicitada,NEW.cant_solicitada);
              ELSE
                   UPDATE bodega_paciente
                   SET total_consumo_directo = total_consumo_directo + NEW.cant_solicitada,
                   cantidad_en_solicitud = cantidad_en_solicitud + NEW.cant_solicitada
                   WHERE ingreso=BODEGA_PACIENTE_RECORD.ingreso AND codigo_producto=BODEGA_PACIENTE_RECORD.codigo_producto;
              END IF;

              RETURN NULL;

         ELSE
              RAISE EXCEPTION 'NO SE PUEDE ACTUALIZAR O ELIMINAR DETALLES DE UNA SOLICITUD.';
         END IF;
    
    ELSE
         RAISE EXCEPTION 'NO SE PUEDE MODIFICAR EL DETALLE DE UNA SOLICITUD CUANDO ESTA ESTA EN UN ESTADO DISTINTO A SOLICITUD[0]';
    END IF;

    RAISE EXCEPTION 'ESTADO NO VALIDO EN LA LOGICA -  DISPARADOR DEL EVENTO [%], OPCION [%]',TG_RELNAME,TG_OP;
ELSIF TG_RELNAME = 'hc_solicitudes_insumos_d' THEN

    SELECT INTO SOLICITUD * FROM hc_solicitudes_medicamentos WHERE solicitud_id = NEW.solicitud_id;

    IF NOT FOUND THEN
         RAISE EXCEPTION 'ERROR TRIGGER, NUMERO DE SOLICITUD [%] NO ESTA EN LA TABLA [hc_solicitudes_medicamentos]',NEW.solicitud_id;
    END IF;

    IF SOLICITUD.sw_estado = '0' THEN

         IF TG_OP = 'INSERT' THEN

              SELECT INTO BODEGA_PACIENTE_RECORD * FROM bodega_paciente WHERE ingreso=SOLICITUD.ingreso AND codigo_producto=NEW.codigo_producto;

              IF NOT FOUND THEN
                   INSERT INTO bodega_paciente(ingreso,codigo_producto,sw_tipo_producto,cantidad_en_solicitud,total_solicitado)
                   VALUES( SOLICITUD.ingreso,NEW.codigo_producto,'I',NEW.cantidad,NEW.cantidad);
              ELSE
                   UPDATE bodega_paciente
                   SET cantidad_en_solicitud = cantidad_en_solicitud + NEW.cantidad,
                   total_solicitado = total_solicitado + NEW.cantidad
                   WHERE ingreso=BODEGA_PACIENTE_RECORD.ingreso AND codigo_producto=BODEGA_PACIENTE_RECORD.codigo_producto;
              END IF;

              RETURN NULL;

         ELSE
              RAISE EXCEPTION 'NO SE PUEDE ACTUALIZAR O ELIMINAR DETALLES DE UNA SOLICITUD.';
         END IF;

    ELSIF SOLICITUD.sw_estado = '3' THEN

         IF TG_OP = 'INSERT' THEN

              SELECT INTO BODEGA_PACIENTE_RECORD * FROM bodega_paciente WHERE ingreso=SOLICITUD.ingreso AND codigo_producto=NEW.codigo_producto;

              IF NOT FOUND THEN
                   RAISE EXCEPTION 'NO SE PUEDE INSERTAR REGISTROS EN UNA SOLICITUD CANCELADA, SI NO ESTABAN REGISTRADOS EN bodega_paciente.';
              ELSE
                   UPDATE bodega_paciente
                   SET total_cancelado_por_la_bodega = total_cancelado_por_la_bodega + NEW.cantidad
                   WHERE ingreso=BODEGA_PACIENTE_RECORD.ingreso AND codigo_producto=BODEGA_PACIENTE_RECORD.codigo_producto;
              END IF;

              RETURN NULL;

         ELSE
              RAISE EXCEPTION 'NO SE PUEDE ACTUALIZAR O ELIMINAR DETALLES DE UNA SOLICITUD.';
         END IF;

    ELSIF SOLICITUD.sw_estado = '4' THEN

         IF TG_OP = 'INSERT' THEN
              SELECT INTO BODEGA_PACIENTE_RECORD * FROM bodega_paciente WHERE ingreso=SOLICITUD.ingreso AND codigo_producto=NEW.medicamento_id;

              IF NOT FOUND THEN
                   INSERT INTO bodega_paciente(ingreso,codigo_producto,sw_tipo_producto,total_consumo_directo,cantidad_en_solicitud)
                   VALUES( SOLICITUD.ingreso,NEW.medicamento_id,'I',NEW.cant_solicitada,NEW.cant_solicitada);
              ELSE
                   UPDATE bodega_paciente
                   SET total_consumo_directo = total_consumo_directo + NEW.cant_solicitada,
                   cantidad_en_solicitud = cantidad_en_solicitud + NEW.cant_solicitada
                   WHERE ingreso=BODEGA_PACIENTE_RECORD.ingreso AND codigo_producto=BODEGA_PACIENTE_RECORD.codigo_producto;
              END IF;

              RETURN NULL;

         ELSE
              RAISE EXCEPTION 'NO SE PUEDE ACTUALIZAR O ELIMINAR DETALLES DE UNA SOLICITUD.';
         END IF;
    ELSE
         RAISE EXCEPTION 'NO SE PUEDE MODIFICAR EL DETALLE DE UNA SOLICITUD CUANDO ESTA ESTA EN UN ESTADO DISTINTO A SOLICITUD[0]';
    END IF;

    RAISE EXCEPTION 'ESTADO NO VALIDO EN LA LOGICA -  DISPARADOR DEL EVENTO [%], OPCION [%]',TG_RELNAME,TG_OP;

  ELSIF TG_RELNAME = 'hc_solicitudes_medicamentos' THEN
    IF TG_OP = 'INSERT' THEN
      RETURN NULL;
    ELSIF TG_OP = 'UPDATE' THEN
      IF NEW.sw_estado != OLD.sw_estado THEN
        IF NEW.sw_estado = '3' THEN
          FOR REGISTROS IN ((SELECT medicamento_id as codigo_producto,cant_solicitada as cantidad FROM hc_solicitudes_medicamentos_d WHERE solicitud_id=NEW.solicitud_id)
                             UNION ALL
                          (SELECT codigo_producto,cantidad FROM hc_solicitudes_insumos_d WHERE solicitud_id=NEW.solicitud_id))
          LOOP

            SELECT INTO BODEGA_PACIENTE_RECORD * FROM bodega_paciente WHERE ingreso=NEW.ingreso AND codigo_producto=REGISTROS.codigo_producto;

            IF NOT FOUND THEN
              RAISE EXCEPTION 'EL PRODUCTO [%] EN EL INGRESO [%] NO SE ENCONTRABA REGISTRADO EN LA TABLA bodega_paciente.',REGISTROS.codigo_producto,NEW.ingreso;
            ELSE
              UPDATE bodega_paciente
              SET   cantidad_en_solicitud = cantidad_en_solicitud - REGISTROS.cantidad,
                    total_cancelado = total_cancelado + REGISTROS.cantidad
              WHERE ingreso=NEW.ingreso AND codigo_producto=REGISTROS.codigo_producto;
            END IF;
          END LOOP;
          RETURN NULL;
        ELSIF NEW.sw_estado = '1' THEN
          IF NEW.documento_despacho IS NULL THEN
            RAISE EXCEPTION 'ESTADO DESPACHADO ACTIVO Y NUMERO DEL DOCUMENTO DE DESPACHO NULO';
          END IF;
          FOR REGISTROS IN(
                            (
                              SELECT a.medicamento_id as codigo_producto, 
                                     COALESCE(b.cantidad,0) as cantidad_despachada, 
                                     (a.cant_solicitada - COALESCE(b.cantidad,0)) as cantidad_cancelada
                              FROM   hc_solicitudes_medicamentos_d as a
                                     LEFT JOIN  bodegas_documento_despacho_med_d as b
                                     ON (b.documento_despacho_id = NEW.documento_despacho AND a.consecutivo_d = b.consecutivo_solicitud)
                              WHERE  a.solicitud_id = NEW.solicitud_id
                            )
                            UNION ALL
                            (
                              SELECT a.codigo_producto, 
                                     COALESCE(b.cantidad,0) as cantidad_despachada, 
                                     (a.cantidad - COALESCE(b.cantidad,0)) as cantidad_cancelada
                              FROM   hc_solicitudes_insumos_d as a
                                     LEFT JOIN  bodegas_documento_despacho_ins_d as b
                                     ON (b.documento_despacho_id = NEW.documento_despacho AND a.consecutivo_d = b.consecutivo_solicitud)
                              WHERE  a.solicitud_id = NEW.solicitud_id
                            )
                          )
          LOOP
            SELECT INTO BODEGA_PACIENTE_RECORD * FROM bodega_paciente WHERE ingreso=NEW.ingreso AND codigo_producto=REGISTROS.codigo_producto;

            IF NOT FOUND THEN
                RAISE EXCEPTION 'EL PRODUCTO [%] EN EL INGRESO [%] NO SE ENCONTRABA REGISTRADO EN LA TABLA bodega_paciente.',REGISTROS.codigo_producto,NEW.ingreso;
            ELSE
                UPDATE bodega_paciente
                SET    total_despachado = total_despachado + REGISTROS.cantidad_despachada,
                       cantidad_pendiente_por_recibir = cantidad_pendiente_por_recibir + REGISTROS.cantidad_despachada,
                       cantidad_en_solicitud = cantidad_en_solicitud - REGISTROS.cantidad_cancelada
                WHERE ingreso=NEW.ingreso AND codigo_producto=REGISTROS.codigo_producto;
            END IF;
          END LOOP;
          RETURN NULL;
        ELSIF NEW.sw_estado = '6' THEN
          IF NEW.documento_despacho IS NULL THEN
            RAISE EXCEPTION 'ESTADO ANULACION DESPACHO ACTIVO Y NUMERO DEL DOCUMENTO DE DESPACHO NULO';
          END IF;

          FOR REGISTROS IN  ((SELECT codigo_producto,cantidad FROM bodegas_documento_despacho_med_d WHERE documento_despacho_id= NEW.documento_despacho)
                              UNION ALL
                             (SELECT codigo_producto,cantidad FROM bodegas_documento_despacho_ins_d WHERE documento_despacho_id= NEW.documento_despacho))
          LOOP
            SELECT INTO BODEGA_PACIENTE_RECORD *
            FROM  bodega_paciente A,
                  ( 
                    SELECT A.consecutivo_solicitud,
                           A.documento_despacho_id,
                           B.medicamento_id
                    FROM   bodegas_documento_despacho_med_d A,
                           hc_solicitudes_medicamentos_d B
                    WHERE  codigo_producto = REGISTROS.codigo_producto
                    AND    documento_despacho_id = NEW.documento_despacho
                    AND    A.consecutivo_solicitud = B.consecutivo_d
                    AND    B.ingreso = NEW.ingreso
                    UNION ALL
                    SELECT A.consecutivo_solicitud,
                           A.documento_despacho_id,
                           B.codigo_producto AS medicamento_id
                    FROM   bodegas_documento_despacho_ins_d A,
                           hc_solicitudes_insumos_d B
                    WHERE  A.codigo_producto = REGISTROS.codigo_producto
                    AND    A.documento_despacho_id = NEW.documento_despacho
                    AND    A.consecutivo_solicitud = B.consecutivo_d
                  ) B
            WHERE A.ingreso = NEW.ingreso
            AND   A.codigo_producto = B.medicamento_id;
            
            IF NOT FOUND THEN
              RAISE EXCEPTION 'EL PRODUCTO [%] EN EL INGRESO [%] NO SE ENCONTRABA REGISTRADO EN LA TABLA bodega_paciente.',REGISTROS.codigo_producto,NEW.ingreso;
            END IF; 
            
            UPDATE bodega_paciente
            SET    total_cancelado_antes_de_confirmar = total_cancelado_antes_de_confirmar + REGISTROS.cantidad,
                   cantidad_en_solicitud = cantidad_en_solicitud - REGISTROS.cantidad,
                   cantidad_pendiente_por_recibir = cantidad_pendiente_por_recibir - REGISTROS.cantidad
            WHERE ingreso=NEW.ingreso AND codigo_producto=REGISTROS.codigo_producto;
          END LOOP;
          RETURN NULL;
        ELSIF NEW.sw_estado = '2' OR NEW.sw_estado = '5' THEN
          IF NEW.documento_despacho IS NULL THEN
            RAISE EXCEPTION 'ESTADO RECIBIDO ACTIVO Y NUMERO DEL DOCUMENTO DE DESPACHO NULO';
          END IF;

          FOR REGISTROS IN ((SELECT codigo_producto,cantidad FROM bodegas_documento_despacho_med_d WHERE documento_despacho_id= NEW.documento_despacho)
                             UNION ALL
                            (SELECT codigo_producto,cantidad FROM bodegas_documento_despacho_ins_d WHERE documento_despacho_id= NEW.documento_despacho))
          LOOP
            SELECT INTO BODEGA_PACIENTE_RECORD *
            FROM  bodega_paciente A,
                  ( 
                    SELECT A.consecutivo_solicitud,
                           A.documento_despacho_id,
                           B.medicamento_id
                    FROM   bodegas_documento_despacho_med_d A,
                           hc_solicitudes_medicamentos_d B
                    WHERE  codigo_producto = REGISTROS.codigo_producto
                    AND    documento_despacho_id = NEW.documento_despacho
                    AND    A.consecutivo_solicitud = B.consecutivo_d
                    AND    B.ingreso = NEW.ingreso
                    UNION ALL
                    SELECT A.consecutivo_solicitud,
                           A.documento_despacho_id,
                           B.codigo_producto AS medicamento_id
                    FROM   bodegas_documento_despacho_ins_d A,
                           hc_solicitudes_insumos_d B
                    WHERE  A.codigo_producto = REGISTROS.codigo_producto
                    AND    A.documento_despacho_id = NEW.documento_despacho
                    AND    A.consecutivo_solicitud = B.consecutivo_d
                  ) B
            WHERE A.ingreso = NEW.ingreso
            AND   A.codigo_producto = B.medicamento_id;
            
            IF NOT FOUND THEN
              RAISE EXCEPTION 'EL PRODUCTO [%] EN EL INGRESO [%] NO SE ENCONTRABA REGISTRADO EN LA TABLA bodega_paciente.',REGISTROS.codigo_producto,NEW.ingreso;
            END IF; 
                          
            -- VALIDO PARA DESPACHOS DE PRODUCTOS EQUIVALENTES
            UPDATE  bodega_paciente
            SET     total_recibido = total_recibido + REGISTROS.cantidad,
                    stock = (stock_almacen + REGISTROS.cantidad) + stock_paciente,
                    stock_almacen = stock_almacen + REGISTROS.cantidad,
                    cantidad_en_solicitud = cantidad_en_solicitud - REGISTROS.cantidad,
                    cantidad_pendiente_por_recibir = cantidad_pendiente_por_recibir - REGISTROS.cantidad
            WHERE ingreso=NEW.ingreso AND codigo_producto=BODEGA_PACIENTE_RECORD.codigo_producto;                        
          END LOOP;
          RETURN NULL;
        ELSIF NEW.sw_estado = '4' THEN
          RAISE EXCEPTION 'NO SE PUEDE MODIFICAR UN REGISTRO AL ESTADO DE CONSUMO DIRECTO, EN ESTE ESTADO SOLO SE PUEDE INSERTAR.';
        ELSE
          RAISE EXCEPTION 'ESTADO [%] NO VALIDO PARA EL CAMPO hc_solicitudes_medicamentos.sw_estado', NEW.sw_estado;
        END IF;
      ELSIF NEW.sw_impreso != '0' THEN
        RETURN NULL;
      END IF;
    ELSIF TG_OP = 'DELETE' THEN
      RAISE EXCEPTION 'NO SE PUEDE ELIMINAR REGISTROS DE LA TABLA hc_solicitudes_medicamentos.';
    END IF;
    RAISE EXCEPTION 'ESTADO NO VALIDO EN LA LOGICA -  DISPARADOR DEL EVENTO [%], OPCION [%]',TG_RELNAME,TG_OP;
    ----------------------------------------------------------------------------------------------------------------------
    -- DEVOLUCIONES
    ----------------------------------------------------------------------------------------------------------------------
  ELSIF TG_RELNAME = 'inv_solicitudes_devolucion_d' THEN
    SELECT INTO SOLICITUD * FROM inv_solicitudes_devolucion WHERE documento = NEW.documento;

    IF NOT FOUND THEN
      RAISE EXCEPTION 'ERROR TRIGGER, NUMERO DE DEVOLUCION [%] NO ESTA EN LA TABLA [inv_solicitudes_devolucion]',NEW.documento;
    END IF;
    
    IF SOLICITUD.estado = '0' THEN
      IF NEW.codigo_producto_sol IS NOT NULL THEN
        SELECT INTO BODEGA_PACIENTE_RECORD * 
        FROM   bodega_paciente 
        WHERE  ingreso=SOLICITUD.ingreso 
        AND    codigo_producto = NEW.codigo_producto_sol;
      ELSE
        SELECT INTO BODEGA_PACIENTE_RECORD *
        FROM  bodega_paciente A,
              ( 
                SELECT A.consecutivo_solicitud,
                       A.documento_despacho_id,
                       B.medicamento_id
                FROM   bodegas_documento_despacho_med_d A,
                       hc_solicitudes_medicamentos_d B
                WHERE  codigo_producto = NEW.codigo_producto
                AND    A.consecutivo_solicitud = B.consecutivo_d
                AND    B.ingreso = SOLICITUD.ingreso
                AND    STRPOS(NEW.documentos_despachos,A.documento_despacho_id::text) > 0
                UNION ALL
                SELECT A.consecutivo_solicitud,
                       A.documento_despacho_id,
                       B.codigo_producto AS medicamento_id
                FROM   bodegas_documento_despacho_ins_d A,
                       hc_solicitudes_insumos_d B
                WHERE  A.codigo_producto = NEW.codigo_producto
                AND    A.consecutivo_solicitud = B.consecutivo_d
                AND    STRPOS(NEW.documentos_despachos,A.documento_despacho_id::text) > 0
              ) B
        WHERE A.ingreso = SOLICITUD.ingreso
        AND   A.codigo_producto = B.medicamento_id;
      END IF;     
      
      IF NOT FOUND THEN
        RAISE EXCEPTION 'EL PRODUCTO [%] SOLICITADO [%] EN EL INGRESO [%] NO SE ENCUENTRA REGISTRADO EN LA TABLA [bodega_paciente], POR LO TANTO NO SE PUEDE DEVOLVER.',NEW.codigo_producto,NEW.codigo_producto_sol,SOLICITUD.ingreso;
      END IF;                           
      -- VALIDO PARA DEVOLUCION DE PRODUCTOS EQUIVALENTES
         
      IF TG_OP = 'INSERT' THEN
        IF NEW.cantidad > (BODEGA_PACIENTE_RECORD.stock_almacen - BODEGA_PACIENTE_RECORD.cantidad_en_devolucion) THEN
          RAISE EXCEPTION 'LA CANTIDAD A DEVOLVER DEL PRODUCTO ES MAYOR QUE LA REGISTRADA EN LA BODEGA DEL PACIENTE EN EL RESULTADO DE LOS CAMPOS(stock_almacen - cantidad_en_devolucion), PRODUCTO[%], SOLICITADO[%], INGRESO[%]',NEW.codigo_producto,NEW.codigo_producto_sol,SOLICITUD.ingreso;
        END IF;

        IF NEW.estado != '0' THEN
          RAISE EXCEPTION 'NO SE PUEDEN INSERTAR DETALLES EN UNA DEVOLUCION CON ESTADO DISTINTO A CERO[0]';
        END IF;

        UPDATE bodega_paciente
        SET cantidad_en_devolucion = cantidad_en_devolucion + NEW.cantidad
        WHERE ingreso=SOLICITUD.ingreso AND codigo_producto=BODEGA_PACIENTE_RECORD.codigo_producto;
        RETURN NULL;
      ELSIF TG_OP = 'UPDATE' THEN
        IF OLD.estado != '0' OR (NEW.estado != '1' AND NEW.estado != '2') THEN
          RAISE EXCEPTION 'EL DETALLE DE UNA DEVOLUCION SOLO SE PUEDE CAMBIAR DE ESTADO [0] A ESTADO [1] O ESTADO [2]';
        END IF;

        IF BODEGA_PACIENTE_RECORD.cantidad_en_devolucion < NEW.cantidad THEN
          RAISE EXCEPTION 'EL REGISTRO DE LA DEVOLUCION QUE ESTA CANCELANDO NO SE ENCUENTRA EN LA TABLA [bodega_paciente]';
        END IF;

        IF NEW.estado = '1' THEN
          UPDATE bodega_paciente
          SET cantidad_en_devolucion = cantidad_en_devolucion - NEW.cantidad
          WHERE ingreso = SOLICITUD.ingreso AND codigo_producto = BODEGA_PACIENTE_RECORD.codigo_producto;
        ELSIF NEW.estado = '2' THEN
          UPDATE bodega_paciente
          SET cantidad_en_devolucion = cantidad_en_devolucion - NEW.cantidad,
              total_devuelto = total_devuelto + NEW.cantidad,
              stock = (stock_almacen - NEW.cantidad) + stock_paciente,
              stock_almacen = stock_almacen - NEW.cantidad
          WHERE ingreso = SOLICITUD.ingreso AND codigo_producto = BODEGA_PACIENTE_RECORD.codigo_producto;
        END IF;
        RETURN NULL;
      ELSE
        RAISE EXCEPTION 'NO SE PUEDEN ELIMINAR DETALLES DE UNA DEVOLUCION.';
      END IF;
    ELSE
      RAISE EXCEPTION 'NO SE PUEDE MODIFICAR EL DETALLE DE UNA DEVOLUCION CUANDO SE ENCUENTRA EN UN ESTADO DISTINTO A CERO[0]';
    END IF;
    RAISE EXCEPTION 'ESTADO NO VALIDO EN LA LOGICA -  DISPARADOR DEL EVENTO [%], OPCION [%]',TG_RELNAME,TG_OP;
  ELSIF TG_RELNAME = 'inv_solicitudes_devolucion' THEN
    IF TG_OP = 'INSERT' THEN
      IF NEW.estado != '0' THEN
        RAISE EXCEPTION 'NO SE PUEDEN INSERTAR DEVOLUCIONES CON ESTADO DISTINTO A CERO[0]';
      END IF;
      RETURN NULL;
    ELSIF TG_OP = 'DELETE' THEN
      RAISE EXCEPTION 'NO SE PUEDEN ELIMINAR DEVOLUCIONES';
    ELSIF TG_OP = 'UPDATE' THEN
      IF NEW.estado = '1' THEN
        -- Restriccion
        FOR REGISTROS IN (SELECT * FROM inv_solicitudes_devolucion_d WHERE estado='0' AND documento = NEW.documento)
        LOOP
          IF REGISTROS.codigo_producto_sol IS NOT NULL THEN
            SELECT INTO BODEGA_PACIENTE_RECORD * 
            FROM   bodega_paciente 
            WHERE  ingreso= NEW.ingreso 
            AND    codigo_producto = REGISTROS.codigo_producto_sol;
          ELSE
            SELECT INTO BODEGA_PACIENTE_RECORD *
            FROM  bodega_paciente A,
                  ( 
                    SELECT A.consecutivo_solicitud,
                           A.documento_despacho_id,
                           B.medicamento_id
                    FROM   bodegas_documento_despacho_med_d A,
                           hc_solicitudes_medicamentos_d B
                    WHERE  codigo_producto = REGISTROS.codigo_producto
                    AND    A.consecutivo_solicitud = B.consecutivo_d
                    AND    B.ingreso = NEW.ingreso
                    AND    STRPOS(REGISTROS.documentos_despachos,A.documento_despacho_id::text) > 0
                    UNION ALL
                    SELECT A.consecutivo_solicitud,
                           A.documento_despacho_id,
                           B.codigo_producto AS medicamento_id
                    FROM   bodegas_documento_despacho_ins_d A,
                           hc_solicitudes_insumos_d B
                    WHERE  A.codigo_producto = REGISTROS.codigo_producto
                    AND    A.consecutivo_solicitud = B.consecutivo_d
                    AND    STRPOS(REGISTROS.documentos_despachos,A.documento_despacho_id::text) > 0
                  ) B
            WHERE A.ingreso = NEW.ingreso
            AND   A.codigo_producto = B.medicamento_id;
          END IF;
          
          IF NOT FOUND THEN
            RAISE EXCEPTION 'EL PRODUCTO [%] EN EL INGRESO [%] NO SE ENCUENTRA REGISTRADO EN LA TABLA [bodega_paciente], POR LO TANTO NO SE PUEDE DEVOLVER.',NEW.codigo_producto,SOLICITUD.ingreso;
          END IF;
                     
          IF BODEGA_PACIENTE_RECORD.cantidad_en_devolucion < REGISTROS.cantidad THEN
            RAISE EXCEPTION 'EL REGISTRO DE LA DEVOLUCION QUE ESTA CANCELANDO NO SE ENCUENTRA EN LA TABLA [bodega_paciente]';
          END IF;

          IF BODEGA_PACIENTE_RECORD.stock_almacen < REGISTROS.cantidad THEN
            RAISE EXCEPTION 'ERROR DE DATOS LA CANTIDAD CONFIRMADA EN LA DEVOLUCION ES MAYOR QUE EL STOCK_ESTACION';
          END IF;

          UPDATE bodega_paciente
          SET cantidad_en_devolucion = cantidad_en_devolucion - REGISTROS.cantidad,
              total_devuelto = total_devuelto + REGISTROS.cantidad,
              stock = (stock_almacen - REGISTROS.cantidad) + stock_paciente,
              stock_almacen = stock_almacen - REGISTROS.cantidad
          WHERE ingreso=NEW.ingreso AND codigo_producto=BODEGA_PACIENTE_RECORD.codigo_producto;
        END LOOP;
        RETURN NULL;
      ELSIF NEW.estado = '2' THEN
        CANTIDAD_SOLICITADA := (SELECT COUNT(*) FROM inv_solicitudes_devolucion_d WHERE estado !='1' AND documento = NEW.documento);

        IF CANTIDAD_SOLICITADA > 0 THEN
          UPDATE inv_solicitudes_devolucion SET estado = '9' WHERE documento = NEW.documento;
        END IF;
        RETURN NULL;
      ELSIF NEW.estado = '9' THEN
        RETURN NULL;
      ELSE
        RAISE EXCEPTION 'ESTADO [%] NO VALIDO PARA ACTUALIZAR UNA DEVOLUCION', NEW.estado;
      END IF;
    END IF;
  END IF;
  RAISE EXCEPTION 'ESTADO NO VALIDO EN LA LOGICA -  DISPARADOR DEL EVENTO [%], OPCION [%]',TG_RELNAME,TG_OP;
END;
$$ LANGUAGE 'plpgsql' SECURITY DEFINER;