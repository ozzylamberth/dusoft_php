
/* EXPLICACION DE LA PARAMETRIZACION DEL DOCUMENTO DE FARMACIA */

/* ------------INICIO DEL DOCUMENTO DE INGRESO A LA FARMACIA---------------------------*/


------****  CREAR LOS TIPOS GENERALES  **************-------------------------
----->TIPO GENERAL DE INGRESO EN EL CASO QUE NO EXISTA  EN LA TABLA  tipos_doc_generales 
ALTER TABLE tipos_doc_generales DISABLE TRIGGER tgg_tipos_doc_generales_msg_control;
INSERT INTO tipos_doc_generales
VALUES ('I008', 'INGRESO DE PRODUCTOS A BODEGA FARMACIA',NULL,'I',NULL,1);
ALTER TABLE tipos_doc_generales ENABLE TRIGGER tgg_tipos_doc_generales_msg_control;

----------> DOCUMENTO DE INGRESO -- CREAR LOS DOCUMENTOS ID DEL ANTERIOR TIPO GENERAL ES DECIR EL  I008 */
 EMPRESA --> ES EL ID DE LA EMPRESA O FARMACIA QUE VA A UTILIZAR EL DOCUMENTO
 EL PREFIJO --> ES EL QUE VA A UTILIZAR LA EMPRESA POR EJEMPLO IF04 

ALTER TABLE documentos DISABLE TRIGGER trigger_documentos_control;
INSERT INTO documentos
VALUES (DEFAULT, EMPRESA,'I008',PREFIJO,DEFAULT,DEFAULT,DEFAULT,'INGRESO DE PRODUCTOS A LA FARMACIA','INGRESO DE PRODUCTOS A LA FARMACIA','INGRESO DE PRODUCTOS A LA FARMACIA','INGRESO DE PRODUCTOS A LA FARMACIA','VENTA DE PRODUCTOS DIRECTOS','1');
ALTER TABLE documentos ENABLE TRIGGER trigger_documentos_control;

----/**** BUSCO EN LA TABLA DOCUMENTOS Y MIRO EL ID DEL DOCUMENTO ANTERIOMENTE CREADO  PARA ESTE CASO SE CREO EL DOCUMENTO ID NUMERO 39 ***

--/**  CREAR  VARIABLE DE MODULO---------------------------*/
--> VARIABLE DE MODULO */ 
documento_ingreso_farmacia_IDEMPRESA
POR EJEMPLO IDEMPRESA= 04 ENTONCES LA VARIABLE DE MODULO ES documento_ingreso_farmacia_04
ID_DOCUMENTO = ES EL ID DEL DOCUMENTO QUE SE CREO POR EJEMPPLO 39

ENTONCES LA VARIABLE DE MODULO QUEDA 
INSERT INTO system_modulos_variables  VALUES('AdminFarmacia','app','documento_ingreso_farmacia_ID_EMPRESA','ID_DOCUMENTO ','documento_id para el ingreso de productos a la Farmacia');
POR EJEMPLO 
INSERT INTO system_modulos_variables  VALUES('AdminFarmacia','app','documento_ingreso_farmacia_04','39','documento_id para el ingreso de productos a la Farmacia');

----FIN VARIABLE DE MODULO
 /*    EN LA SIGUIENTES TABLAS  SE DA EL PERMISO AL USUARIO DE VER EL DOCUMENTO ANTERIORMENTE CREADO **/
documento_id=> Documento id Creado anteriormente 
empresa_id=> Id de la Farmacia
centro_utilidad => Id del centro de utilidad de la Farmacia  	
bodega => Id de la Bodega del centro de utilidad de la Farmacia 	
usuario_id=> Id del Usuario autorizado

INSERT INTO inv_bodegas_userpermisos VALUES(documento_id,'empresa_id','centro_utilidad','bodega',usuario_id);

INSERT INTO inv_bodegas_documentos VALUES(documento_id,'empresa_id','centro_utilidad','bodega',DEFAULT,'1',NULL,'0');

/* FIN DE LOS PERMISOS*/

/*---------------FIN DEL DOCUMENTO DE INGRESO ----------------*/


/*--------------DOCUMENTO DE DEVOLUCION -------------------------*/

---***TIPO GENERAL DE  EGRESO**/
----->TIPO GENERAL DE INGRESO EN EL CASO QUE NO EXISTA  EN LA TABLA  tipos_doc_generales 
ALTER TABLE tipos_doc_generales DISABLE TRIGGER tgg_tipos_doc_generales_msg_control;
INSERT INTO tipos_doc_generales
VALUES ('E009', 'EGRESO DEVOLUCION DE FARMACIA',NULL,'E',NULL,1);
ALTER TABLE tipos_doc_generales ENABLE TRIGGER tgg_tipos_doc_generales_msg_control;

--/* DOCUMENTO DE EGRESO POR VENCIMIENTO DE PRODUCTOS- - CREAR LOS DOCUMENTOS ID DEL ANTERIOR TIPO GENERAL E009 */

EMPRESA --> ES EL ID DE LA EMPRESA O FARMACIA QUE VA A UTILIZAR EL DOCUMENTO
EL PREFIJO --> ES EL QUE VA A UTILIZAR LA EMPRESA POR EJEMPLO EF04 


ALTER TABLE documentos DISABLE TRIGGER trigger_documentos_control;
INSERT INTO documentos
VALUES (DEFAULT, EMPRESA,'E009',PREFIJO,DEFAULT,DEFAULT,DEFAULT,'EGRESO POR DEVOLUCION DE FARMACIA','EGRESO POR DEVOLUCION DE FARMACIA','EGRESO POR DEVOLUCION DE FARMACIA','EGRESO POR DEVOLUCION DE FARMACIA','EGRESO POR DEVOLUCION DE FARMACIA','1');
ALTER TABLE documentos ENABLE TRIGGER trigger_documentos_control;

POR EJEMPLO 
ALTER TABLE documentos DISABLE TRIGGER trigger_documentos_control;
INSERT INTO documentos
VALUES (DEFAULT, '04','E009','EF04',DEFAULT,DEFAULT,DEFAULT,'EGRESO POR DEVOLUCION DE FARMACIA','EGRESO POR DEVOLUCION DE FARMACIA','EGRESO POR DEVOLUCION DE FARMACIA','EGRESO POR DEVOLUCION DE FARMACIA','EGRESO POR DEVOLUCION DE FARMACIA','1');
ALTER TABLE documentos ENABLE TRIGGER trigger_documentos_control;



---/ CREO LA VARIABLE DE MODULO PARA LOS DIAS DE ENCIMIENTO*****DIAS DE VENCIMIENTO PARA LA FARMACIA QUE SE ESTA PARAMETRIZANDO ,
INSERT INTO system_modulos_variables  VALUES('AdminFarmacia','app','dias_vencimiento_product_bodega_farmacia_IDEMPRESA',DIAS,'Dias antes de la fecha de vencimiento de un producto');


/*----EN LA SIGUIENTES TABLA SE DA EL PERMISO AL USUARIO DE VER EL DOCUMENTO ANTERIORMENTE CREADO **/
documento_id=> Documento id Creado anteriormente 
empresa_id=> Id de la Farmacia
centro_utilidad => Id del centro de utilidad de la Farmacia  	
bodega => Id de la Bodega del centro de utilidad de la Farmacia 	
usuario_id=> Id del Usuario autorizado

INSERT INTO inv_bodegas_userpermisos VALUES(documento_id,'empresa_id','centro_utilidad','bodega',usuario_id);

INSERT INTO inv_bodegas_documentos VALUES(documento_id,'empresa_id','centro_utilidad','bodega',DEFAULT,'1',NULL,'0');

/* EL DOCUMENTO DE INGRESO NO TIENE VARIABLE DE MODULO*/

-----FIN DEL DOCUMENTO DE EGRESO


/* DOY PERMISO A LA EMPRESA PARA USAR EL MODULO EN LA TABLA */
    userpermisos_AdminisFarmacia






