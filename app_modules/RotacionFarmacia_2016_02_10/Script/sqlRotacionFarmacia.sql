--/*************** ROTACION FARMACIA **********************----

CREATE TABLE Devolucion_Rotacion_Farmacia(
            DevolucionRF_id        SERIAL NOT NULL,
            empresa_id             CHARACTER(2) not null,
            centro_utilidad        CHARACTER(2) not null,
            bodega                 CHARACTER(2) not null,
            codigo_producto        character varying(60) not null,
            cantidad               numeric(14,0) not null,
            sw_devuelto            CHARACTER(2) not null, 
            usuario_id             INTEGER NOT NULL,
            usuario_devuelve       INTEGER  NULL,
            fecha_registro         TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW()
       );

ALTER TABLE Devolucion_Rotacion_Farmacia ADD PRIMARY KEY (DevolucionRF_id);
ALTER TABLE Devolucion_Rotacion_Farmacia ADD FOREIGN KEY (empresa_id,centro_utilidad,bodega) REFERENCES bodegas(empresa_id,centro_utilidad,bodega)ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE Devolucion_Rotacion_Farmacia ADD FOREIGN KEY (usuario_id)REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE Devolucion_Rotacion_Farmacia ADD FOREIGN KEY (codigo_producto)REFERENCES inventarios_productos(codigo_producto) ON UPDATE CASCADE ON DELETE RESTRICT;

	ALTER TABLE Devolucion_Rotacion_Farmacia ADD cantidad_dev  numeric(14,0) null;
ALTER TABLE solicitud_pro_a_bod_prpal_tmp ADD observacion  text null;


ALTER TABLE Solicitud_Productos_A_Bodega_principal_detalle ADD observacion  text null;
COMMENT ON COLUMN Solicitud_Productos_A_Bodega_principal_detalle.observacion IS 'Observacion registrada en la Rotacion';

COMMENT ON COLUMN Devolucion_Rotacion_Farmacia.DevolucionRF_id IS 'Id (PK) ';
COMMENT ON COLUMN Devolucion_Rotacion_Farmacia.empresa_id IS 'Id Empresa (FK) ';
COMMENT ON COLUMN Devolucion_Rotacion_Farmacia.centro_utilidad IS 'Id centro_utilidad(FK)  ';
COMMENT ON COLUMN Devolucion_Rotacion_Farmacia.bodega IS 'Id Bodega(FK)  ';
COMMENT ON COLUMN Devolucion_Rotacion_Farmacia.codigo_producto IS 'Id Codigo Producto (FK)';
COMMENT ON COLUMN Devolucion_Rotacion_Farmacia.cantidad IS 'Cantidad a Devolver  ';
COMMENT ON COLUMN Devolucion_Rotacion_Farmacia.sw_devuelto IS 'Estado de Devolucion 0=>No Devuelto 1=>Devuelto  ';
COMMENT ON COLUMN Devolucion_Rotacion_Farmacia.usuario_id IS 'Id Usuario Registra';
COMMENT ON COLUMN Devolucion_Rotacion_Farmacia.fecha_registro IS 'Fecha de registro  ';

/*--------------------------------------------------------------------------------------------*/









