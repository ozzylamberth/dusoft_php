

/* Modulo de Dispensacion*/

SE DEBE DAR PERMISO EN LA TABLA userpermisos_Dispensacion POR CADA USUARIO
ES NECESARIO INGRESAR EN ESTA BLA EL USUARIO, LA EMPRESA, EL CENTRO DE UTILIDAD Y LA BODEGA 

SE DEBE PARAMETRIZAR EL DOCUMENTO DE EGRESO POR DISPENSACION 
ESTE MODULO UTILIZA LAS SIGUIENTE TABLAS PARA LOS DOCUMENTOS:
bodegas_documentos
bodegas_documentos_d 

SE DEBE CREAR EL DOCUMENTO DE BODEGA DE LA SIGUIENTE FORMA

TABLA: 
tipos_doc_bodega
POR EJEMPLO EL E011 DISPENSACION DE PRODUCTOS A PACIENTES

TABLA: 
bodegas_doc_numeracion
SE DEBE HACER EL INGRESO  A LA TABLA ANTERIOR TENIENDO EN CUENTA QUE ES UN DOCUMENDO DE EGRESO Y  A QUIEN LE PERTENECE EL DOCUMENTO 
ES DECIR QUIEN VA HACER EL EGRESO EN ESTE CASO LE PERTENECE A LA EMPRESA, EL CENTRO DE UTILIDAD  Y LA BODEGA A LA CUAL SE LE DIO EL 
PERMISO EN EL MODULO
AQUI DEVUELVE UN NUMERO  QUE ES EL IDENTIFICADOR DEL REGISTRO QUE ACABE DE INGRESAR
ES DECIR EL CAMPO    bodegas_doc_id  DE LA TABLA bodegas_doc_numeracion  
ESTE NUMERO SE DEBE TENER EN CUENTA PARA CREAR LA VARIABLE DE MODULO

EJEMPLO 
EN EL CASO ANTERIOR SI EL bodegas_doc_id ARROJA EL NUMERO 29
ENTONCES 
CREO LA VARIABLE DE MODULO DE LA SIGUIENTE FORMA:

INSERT INTO system_modulos_variables  VALUES('Dispensacion','app','documento_dispensacion_$farmacia_$bodega','29','documento para el egreso por dispensacion');

donde 
$farmacia= la farmacia o empresa que es dueña del documento creo en la tabla bodegas_doc_numeracion es decir la misma con la que damos el permiso al modulo
$bodega= bodega que pertenece a la farmacia dueña del documento
por ejemplo

INSERT INTO system_modulos_variables  VALUES('Dispensacion','app','documento_dispensacion_01_01','29','documento para el egreso por dispensacion');









 
