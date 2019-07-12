---/*********************************************************
---/*                      ADMINISTRACION DE FARMACIA                                    */
---/*********************************************************/

--/******* PERMISO AL USUARIO ****/
----> dar permiso a  los usuarios donde las empresas tengan el sw_tipo empresa = 1;

CREATE TABLE userpermisos_AdminisFarmacia
(
    empresa_id character(2) NOT NULL,
    centro_utilidad 	 character(2) NOT NULL,
    usuario_id integer NOT NULL
);

ALTER TABLE userpermisos_AdminisFarmacia add PRIMARY KEY (empresa_id, usuario_id,centro_utilidad);

ALTER TABLE  userpermisos_AdminisFarmacia  ADD  FOREIGN KEY (empresa_id) REFERENCES empresas(empresa_id)
ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE userpermisos_AdminisFarmacia
ADD FOREIGN KEY (empresa_id,centro_utilidad) REFERENCES centros_utilidad(empresa_id,centro_utilidad)
ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE   userpermisos_AdminisFarmacia
ADD  FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id)
ON UPDATE CASCADE ON DELETE RESTRICT;


COMMENT ON COLUMN userpermisos_AdminisFarmacia.empresa_id IS 'Id de la  Empresa que se parametrizo como Farmacia';
COMMENT ON COLUMN userpermisos_AdminisFarmacia.centro_utilidad IS 'Id del  centro de utilidad de la farmacia';
COMMENT ON COLUMN userpermisos_AdminisFarmacia.usuario_id IS 'Id  del Usuario Autorizado';

------****  CREAR LOS TIPOS GENERALES  **************-------------------------
---***TIPO GENERAL DE INGRESO**/
ALTER TABLE tipos_doc_generales DISABLE TRIGGER tgg_tipos_doc_generales_msg_control;
INSERT INTO tipos_doc_generales
VALUES ('I008', 'INGRESO DE PRODUCTOS A BODEGA FARMACIA',NULL,'I',NULL,1);
ALTER TABLE tipos_doc_generales ENABLE TRIGGER tgg_tipos_doc_generales_msg_control;

---***TIPO GENERAL DE  EGRESO**/
ALTER TABLE tipos_doc_generales DISABLE TRIGGER tgg_tipos_doc_generales_msg_control;
INSERT INTO tipos_doc_generales
VALUES ('E009', 'EGRESO DEVOLUCION DE FARMACIA',NULL,'E',NULL,1);
ALTER TABLE tipos_doc_generales ENABLE TRIGGER tgg_tipos_doc_generales_msg_control;

-------------------------------------------------------------------------------------------------------------------------------------------------------------
--/* DOCUMENTO DE EGRESO POR VENCIMIENTO DE PRODUCTOS- - CREAR LOS DOCUMENTOS ID DEL ANTERIOR TIPO GENERAL E009 */

ALTER TABLE documentos DISABLE TRIGGER trigger_documentos_control;
INSERT INTO documentos
VALUES (DEFAULT, '04','E009','EF04',DEFAULT,DEFAULT,DEFAULT,'EGRESO POR DEVOLUCION DE FARMACIA','EGRESO POR DEVOLUCION DE FARMACIA','EGRESO POR DEVOLUCION DE FARMACIA','EGRESO POR DEVOLUCION DE FARMACIA','EGRESO POR DEVOLUCION DE FARMACIA','1');
ALTER TABLE documentos ENABLE TRIGGER trigger_documentos_control;

--/*BUSCO EN LA TABLA DOCUMENTOS Y MIRO EL ID DEL DOCUMENTO ANTERIOMENTE CREADO  PARA ESTE CASO SE CREO EL DOCUMENTO ID NUMERO  41
---/**** EN LA SIGUIENTES TABLA SE DA EL PERMISO AL USUARIO DE VER EL DOCUMENTO ANTERIORMENTE CREADO **/
documento_id=> Documento id Creado anteriormente 
empresa_id=> Id de la Farmacia
centro_utilidad => Id del centro de utilidad de la Farmacia  	
bodega => Id de la Bodega del centro de utilidad de la Farmacia 	
usuario_id=> Id del Usuario autorizado

INSERT INTO inv_bodegas_userpermisos VALUES(documento_id,'empresa_id','centro_utilidad','bodega',usuario_id);

INSERT INTO inv_bodegas_documentos VALUES(documento_id,'empresa_id','centro_utilidad','bodega',DEFAULT,'1',NULL,'0');
--/***  INSERT INTO inv_bodegas_documentos VALUES(41,'04','04','04',DEFAULT,'1',NULL,'0');

---/ CREO LA VARIABLE DE MODULO PARA LOS DIAS DE ENCIMIENTO*****DIAS DE VENCIMIENTO PARA LA FARMACIA QUE SE ESTA PARAMETRIZANDO , EN ESTE CASO SE COLOCA EL 04  PUES ES EL ID DE LA FARMACIA QUE SE ESTA PARAMETRIZANDO  Y EL VALO 30 SERIAN LOS DIAS ANTES DE LA FECHA DE VENCIMIENTO
INSERT INTO system_modulos_variables  VALUES('AdminFarmacia','app','dias_vencimiento_product_bodega_farmacia_04','30','Dias antes de la fecha de vencimiento de un producto');


-----------**** DOCUMENTO DE INGRESO --/****** CREAR LOS DOCUMENTOS ID DEL ANTERIOR TIPO GENERAL I008 */

ALTER TABLE documentos DISABLE TRIGGER trigger_documentos_control;
INSERT INTO documentos
VALUES (DEFAULT, '02','I008','IF04',DEFAULT,DEFAULT,DEFAULT,'INGRESO DE PRODUCTOS A LA FARMACIA','INGRESO DE PRODUCTOS A LA FARMACIA','INGRESO DE PRODUCTOS A LA FARMACIA','INGRESO DE PRODUCTOS A LA FARMACIA','VENTA DE PRODUCTOS DIRECTOS','1');
ALTER TABLE documentos ENABLE TRIGGER trigger_documentos_control;


----/**** BUSCO EN LA TABLA DOCUMENTOS Y MIRO EL ID DEL DOCUMENTO ANTERIOMENTE CREADO  PARA ESTE CASO SE CREO EL DOCUMENTO ID NUMERO 39 ***
--/** VARIABLE DE MODULO---------------------------*/
INSERT INTO system_modulos_variables  VALUES('AdminFarmacia','app','documento_ingreso_farmacia_04','39','documento_id para el ingreso de productos a la Farmacia');

---/**** EN LA SIGUIENTES TABLA SE DA EL PERMISO AL USUARIO DE VER EL DOCUMENTO ANTERIORMENTE CREADO **/
documento_id=> Documento id Creado anteriormente 
empresa_id=> Id de la Farmacia
centro_utilidad => Id del centro de utilidad de la Farmacia  	
bodega => Id de la Bodega del centro de utilidad de la Farmacia 	
usuario_id=> Id del Usuario autorizado

INSERT INTO inv_bodegas_userpermisos VALUES(documento_id,'empresa_id','centro_utilidad','bodega',usuario_id);
INSERT INTO inv_bodegas_documentos VALUES(documento_id,'empresa_id','centro_utilidad','bodega',DEFAULT,'1',NULL,'0');
--/***  INSERT INTO inv_bodegas_documentos VALUES(39,'04','04','04',DEFAULT,'1',NULL,'0');

CREATE TABLE ProductosPendientesPorVerificar
(
  Pendiente_veri_id        CHARACTER VARYING(45) NOT NULL,
  empresa_id 	           CHARACTER(2) 	NOT NULL,
  prefijo 	               CHARACTER VARYING(4) NOT NULL,
  numero 	              INTEGER NOT NULL,
  farmacia_id              CHARACTER(2) 	NOT NULL,
  codigo_producto              CHARACTER VARYING(30) NOT NULL,
  fecha_vencimiento 	date,	
	lote 	character varying(30),
  usuario_id             INTEGER NOT NULL,
  fecha_registro          TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW()
);

ALTER TABLE ProductosPendientesPorVerificar ADD PRIMARY KEY(Pendiente_veri_id);
ALTER TABLE ProductosPendientesPorVerificar ADD FOREIGN KEY (empresa_id, prefijo, numero) 
REFERENCES inv_bodegas_movimiento(empresa_id, prefijo, numero) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ProductosPendientesPorVerificar ADD FOREIGN KEY(usuario_id) 
REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ProductosPendientesPorVerificar ADD FOREIGN KEY(farmacia_id) 
REFERENCES empresas(empresa_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ProductosPendientesPorVerificar ADD FOREIGN KEY(farmacia_id,codigo_producto) references
inventarios(empresa_id,codigo_producto) ON UPDATE CASCADE ON DELETE RESTRICT;


COMMENT ON TABLE ProductosPendientesPorVerificar IS 'Tabla donde se registran temporalmente los productos pendientes por verificar';
COMMENT ON COLUMN ProductosPendientesPorVerificar.Pendiente_veri_id IS '(PK ) ProductosPendientesPorVerificar';
COMMENT ON COLUMN ProductosPendientesPorVerificar.empresa_id IS '(PK -FK) Referencia a la tabla inv_bodegas_movimiento';
COMMENT ON COLUMN ProductosPendientesPorVerificar.prefijo IS '(PK -FK) Referencia a la tabla inv_bodegas_movimiento';
COMMENT ON COLUMN ProductosPendientesPorVerificar.numero IS '(PK -FK) Referencia a la tabla inv_bodegas_movimiento';
COMMENT ON COLUMN ProductosPendientesPorVerificar.farmacia_id IS '(FK) Referencia a la empresa que esta catalogada como farmacia';
COMMENT ON COLUMN ProductosPendientesPorVerificar.codigo_producto IS '(FK) Referencia de la tabla inventarios_productos';
COMMENT ON COLUMN ProductosPendientesPorVerificar.usuario_id IS '(FK) Identifica al usuario que realizo el documento';
COMMENT ON COLUMN ProductosPendientesPorVerificar.fecha_registro IS 'Fecha de registro del documento';
COMMENT ON COLUMN ProductosPendientesPorVerificar.fecha_vencimiento IS 'Fecha del Producto';
COMMENT ON COLUMN ProductosPendientesPorVerificar.lote IS 'Lote del Producto';
GRANT ALL ON TABLE ProductosPendientesPorVerificar TO siis;

CREATE TABLE Producto_Verificados_tmp(
				Prod_verif_id        character varying(40),
				empresa_id 	      CHARACTER(2) 	NOT NULL,
				prefijo 	      CHARACTER VARYING(4) NOT NULL,
				numero 	              INTEGER NOT NULL,
				farmacia_id           CHARACTER(2) 	NOT NULL,
				codigo_producto       CHARACTER VARYING(30) NOT NULL,
				cantidad              numeric(14)not null,       
				porcentaje_gravamen   numeric(9,2) 	NOT NULL,
				total_costo 	      numeric(14,2) nOT NULL,
				existencia_bodega     numeric(9,2) nOT NULL,
				existencia_inventario 	numeric(9,2) 	NOT NULL,
				costo_inventario 	numeric(15,2) 	NOT NULL,
				 fecha_vencimiento 	date,	
				lote 	character varying(30) 	
				  );

ALTER TABLE Producto_Verificados_tmp  ADD PRIMARY KEY (Prod_verif_id);

CREATE TABLE Doc_Devolucion_tmp(
            Prod_dev_id           character varying(80),
            farmacia_id 	      CHARACTER(2) 	NOT NULL,
            centro_utilidad       character(2) NOT NULL,
            bodega     	      character(2) NOT NULL,
            codigo_producto       CHARACTER VARYING(50) NOT NULL,
            cantidad              numeric(14)not null,    
            porcentaje_gravamen   numeric(9,2) 	NOT NULL,
            total_costo		numeric(14,2) NOT NULL,
            fecha_vencimiento 	date NOT NULL,	
            lote 	character varying(30) NOT NULL
            );

ALTER TABLE doc_devolucion_tmp  ADD PRIMARY KEY (Prod_dev_id);
ALTER TABLE doc_devolucion_tmp ADD FOREIGN KEY (codigo_producto) REFERENCES inventarios_productos(codigo_producto)
ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE doc_devolucion_tmp ADD usuario_id integer not null;
