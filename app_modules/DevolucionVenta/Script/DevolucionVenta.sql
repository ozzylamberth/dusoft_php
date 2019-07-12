
CREATE TABLE userpermisos_Devolucion_por_venta_farmacia
(
  empresa_id character(2) NOT NULL,
  centro_utilidad character(2) NOT NULL,
  usuario_id integer NOT NULL
);

ALTER TABLE userpermisos_Devolucion_por_venta_farmacia ADD PRIMARY KEY (empresa_id, usuario_id,centro_utilidad);

ALTER TABLE userpermisos_Devolucion_por_venta_farmacia ADD FOREIGN KEY (empresa_id) 
REFERENCES empresas(empresa_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE userpermisos_Devolucion_por_venta_farmacia ADD FOREIGN KEY (empresa_id,centro_utilidad) 
REFERENCES centros_utilidad(empresa_id,centro_utilidad) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE userpermisos_Devolucion_por_venta_farmacia ADD FOREIGN KEY (usuario_id) 
REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;

COMMENT ON TABLE userpermisos_Devolucion_por_venta_farmacia IS 'Tabla donde se registran los usuarios con permisos para la Devolucion por venta en farmacias';
COMMENT ON COLUMN userpermisos_Devolucion_por_venta_farmacia.empresa_id IS '(PK - FK) Id de la  Empresa que se parametrizo como Farmacia';
COMMENT ON COLUMN userpermisos_Devolucion_por_venta_farmacia.centro_utilidad IS '(PK - FK) Id del  centro de utilidad de la farmacia';
COMMENT ON COLUMN userpermisos_Devolucion_por_venta_farmacia.usuario_id IS '(PK - FK) Id  del Usuario Autorizado';

GRANT ALL ON TABLE userpermisos_Devolucion_por_venta_farmacia TO siis;



CREATE TABLE rc_devoluciones_ventas
(
  devolucionv_id    serial NOT NULL,
  prefijo           character varying(4) NOT NULL,
  empresa_id        character(2) NOT NULL,
  centro_utilidad   character(2) NOT NULL,
  prefijo_factura 	character varying(4) NOT NULL,
  factura_fiscal 	  integer NOT NULL,
  total_devolucion 	numeric(12,2) NOT NULL,
  estado 	          character(1) 	NOT NULL,	
  fecha_registro 	  TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
  bodegas_doc_id  	integer NOT NULL,
  bodegas_numeracion 	integer  NOT NULL,
	usuario_id integer NOT NULL 
);



ALTER TABLE rc_devoluciones_ventas ADD PRIMARY KEY (devolucionv_id);
ALTER TABLE rc_devoluciones_ventas ADD FOREIGN KEY (usuario_id) 
REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE rc_devoluciones_ventas ADD FOREIGN KEY (empresa_id, factura_fiscal, prefijo_factura) REFERENCES fac_facturas_contado(empresa_id,factura_fiscal,prefijo);
ALTER TABLE rc_devoluciones_ventas ADD FOREIGN KEY (bodegas_doc_id, bodegas_numeracion) REFERENCES bodegas_documentos(bodegas_doc_id, numeracion);

COMMENT ON TABLE rc_devoluciones_ventas IS 'Tabla donde se registran la devolucion parcial de las facturas de ventas ';
COMMENT ON COLUMN rc_devoluciones_ventas.devolucionv_id IS '(PK)';
COMMENT ON COLUMN rc_devoluciones_ventas.prefijo IS 'Prefijo de devolucion por devolucion parcial de la factura';
COMMENT ON COLUMN rc_devoluciones_ventas.empresa_id IS '(FK) Id de la empresa';
COMMENT ON COLUMN rc_devoluciones_ventas.centro_utilidad IS '(FK) Id del centro de utilidad';
COMMENT ON COLUMN rc_devoluciones_ventas.prefijo_factura IS 'Prefijo de la Factura de venta';
COMMENT ON COLUMN rc_devoluciones_ventas.factura_fiscal IS 'factura fiscal de la fectura de venta';
COMMENT ON COLUMN rc_devoluciones_ventas.total_devolucion IS 'total de la devolucion de la factura';
COMMENT ON COLUMN rc_devoluciones_ventas.estado IS 'estado de la devolucion';
COMMENT ON COLUMN rc_devoluciones_ventas.fecha_registro IS 'fecha de registro de la factura';
COMMENT ON COLUMN rc_devoluciones_ventas.bodegas_doc_id IS 'id del documento de bodega';
COMMENT ON COLUMN rc_devoluciones_ventas.bodegas_numeracion IS 'numero del documento de bodega';
COMMENT ON COLUMN rc_devoluciones_ventas.usuario_id IS '(FK) Id  del Usuario Autorizado';



alter table  notas_contado_conceptos add sw_devolucion_venta_D character(1) default '0' NOT NULL;

En caso de que la tabla notas_contado_conceptos no este aqui esta el script

CREATE TABLE notas_contado_conceptos (
    nota_contado_concepto_id integer NOT NULL,
    empresa_id character(2) NOT NULL,
    descripcion character varying(80) NOT NULL,
    sw_naturaleza character varying(1) NOT NULL,
    docuemnto_nota_id character varying(10),
    sw_centro_costo character varying(1) DEFAULT '0'::character varying,
    sw_activo character varying(1) DEFAULT '1'::character varying,
    sw_tercero character varying(1) DEFAULT '0'::character varying,
    CONSTRAINT notas_contado_conceptos_sw_naturaleza_check CHECK ((((sw_naturaleza)::text = 'C'::text) OR ((sw_naturaleza)::text = 'D'::text)))
);
ALTER TABLE public.notas_contado_conceptos OWNER TO "admin";
COMMENT ON TABLE notas_contado_conceptos IS 'Almacena los conceptos de las notas credito / debito de contado';
COMMENT ON COLUMN notas_contado_conceptos.nota_contado_concepto_id IS '(PK) Codigo del concepto';
COMMENT ON COLUMN notas_contado_conceptos.descripcion IS 'Descripcion del concepto';
COMMENT ON COLUMN notas_contado_conceptos.sw_naturaleza IS 'Naturaleza del concepto(D=Debito, C=Credito)';
COMMENT ON COLUMN notas_contado_conceptos.docuemnto_nota_id IS 'Identificador de la empresa para los conceptos';
COMMENT ON COLUMN notas_contado_conceptos.sw_centro_costo IS 'Permite saber si el concepto necesita estar asociado a un departamento o no';
COMMENT ON COLUMN notas_contado_conceptos.sw_activo IS 'Indica si el concepto esta activo (1) o no (0)';
COMMENT ON COLUMN notas_contado_conceptos.sw_tercero IS 'Indica si para el concepto se pide tercero (1) o no (0)';

CREATE SEQUENCE notas_contado_conceptos_nota_contado_concepto_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.notas_contado_conceptos_nota_contado_concepto_id_seq OWNER TO "admin";
ALTER SEQUENCE notas_contado_conceptos_nota_contado_concepto_id_seq OWNED BY notas_contado_conceptos.nota_contado_concepto_id;
ALTER TABLE notas_contado_conceptos ALTER COLUMN nota_contado_concepto_id SET DEFAULT nextval('notas_contado_conceptos_nota_contado_concepto_id_seq'::regclass);
ALTER TABLE ONLY notas_contado_conceptos
    ADD CONSTRAINT notas_contado_conceptos_docuemnto_nota_id_key UNIQUE (docuemnto_nota_id, empresa_id);

ALTER TABLE ONLY notas_contado_conceptos
    ADD CONSTRAINT notas_contado_conceptos_pkey PRIMARY KEY (nota_contado_concepto_id);


ALTER TABLE ONLY notas_contado_conceptos
    ADD CONSTRAINT notas_contado_conceptos_empresa_id_fkey FOREIGN KEY (empresa_id) REFERENCES empresas(empresa_id) ON UPDATE CASCADE ON DELETE RESTRICT;



insert into notas_contado_conceptos (nota_contado_concepto_id,
                                     empresa_id,
                                     descripcion,
                                     sw_naturaleza,
                                     sw_centro_costo,
                                     sw_activo,
                                     sw_tercero ,
                                     sw_devolucion_venta_d
                                    )
                                    values(
                                    nextval('notas_contado_conceptos_nota_contado_concepto_id_seq'),
                                    '04',
                                    'DEVOLUCION POR VENTA DE PRODUCTOS FARMACIA',
                                    'C',
                                    '0',
                                    '1',
                                    '1',
                                    '1'
                                    );
    
/*------PARAMETRIZACION DEL DOCUMENTO DE INGRESO POR DEVOLUCION DE VENTA DIRECTA */
-PARAMETRIZAR EL DOCUMENTO EN LA TABLA  tipos_doc_bodega
PARA ESTE CASO  PUEDE SER : I100 INGRESO DEVOLUCION COMPRA DIRECTA
 insert into tipos_doc_bodega values('I100','INGRESO DEVOLUCION COMPRA DIRECTA');



- LUEGO SE PARAMETRIZA LA TABLA bodegas_doc_numeraciones
PARA ESTE CASO PUEDE SER  TENIENDO EN CUENTA LA IDENTIFICACION DE LA EMPRESA,IDENTIFICACION DEL CENTRO DE UTILIDAD Y LA BODEGA DE LA EMPRESA : 
    INSERT INTO bodegas_doc_numeraciones (bodegas_doc_id,
                                          empresa_id,
                                          centro_utilidad,
                                          bodega,
                                          tipo_doc_bodega_id,
                                          prefijo,
                                          descripcion,	
                                          numeracion,
                                          sw_estado,
                                          tipo_movimiento,
                                          sw_ajuste,
                                          sw_traslado, 
                                          sw_compras,
                                          numero_digitos,
                                          sw_transaccion_medicamentos,	
                                          text1, 
                                          text2,
                                          text3,
                                          sw_contenedor_docs_cirugias
                                          ) 
                                          VALUES
                                          (nextval('bodegas_doc_numeraciones_bodegas_doC_id_seq'),
                                          '04',
                                          '04',
                                          '04',
                                          'I100',
                                          'DVF',
                                          'ING DEVOLUCION VENTA',
                                          '1',
                                          '1',
                                          'I',
                                          '0',
                                          '0',
                                          '0',
                                          '10',
                                          '0',
                                          NULL,
                                          NULL,
                                          NULL,
                                          NULL
                                         );

CON EL ANTERIOR TENGO EL bodegas_doc_id PARA CREAR LA VARIABLE DE MODULO
-EN EL CASO ANTERIOR EL bodegas_doc_id ES 31 ENTONCES LA VARIABLE DE MODULO SERIA:
Ingreso_devolucion_por_venta_farmacia_02 con valor 31

   INSERT INTO system_modulos_variables (   modulo,
                                              modulo_tipo,
                                              variable,
                                              valor,
                                              descripcion)
                                              VALUES(
                                              'DevolucionVenta',
                                              'app',
                                              'Ingreso_devolucion_por_venta_farmacia_04',
                                              '31',
                                              'ID DE LA FACTURAS ANULADAS POR VENTA' );



-EN LA TABLA motivos_anulacion_facturas SE DEBE PARAMETRIZAR EL MOTIVO DE ANULACION DE LAS FACTURAS POR VENTA DIRECTA
INSERT INTO motivos_anulacion_facturas (motivo_id,
                                        motivo_descripcion,
                                        sw_activo
                                      ) 
                                        VALUES
                                      (
                                      nextval('motivos_anulacion_facturas_motivo_id_seq'),
                                      'DEVOLUCION POR VENTA DIRECTA DE PRODUCTOS',
                                      '1');

-PARA LAS FACTURAS QUE VAN HACER ANULADAS
 SE DEBE PARAMETRIZAR LO SIGUIENTE
   -> CREAR EL TIPO GENERAL EN EL CASO DE QUE NO EXISTA EN LA TABLA tipos_doc_generales 
      SI NO EXISTE ENTONCES SE DEBE CREAR ASI:
      
      ALTER TABLE tipos_doc_generales DISABLE TRIGGER tgg_tipos_doc_generales_msg_control;
      INSERT INTO tipos_doc_generales
      VALUES ('FA01', '	NOTAS CREDITO ANULACION FACTURAS',NULL,NULL,NULL,1);
      ALTER TABLE tipos_doc_generales ENABLE TRIGGER tgg_tipos_doc_generales_msg_control;
      
   -> CREAR EL DOCUMENTO (ESTE DOCUMENTO SE NECESITA PARA CREAR LA NOTAS CREDITOS DE LA ANULACION DE LA FACTURA)( TENER PRESENTE PARA QUE EMPRESA SE VA A CREAR EL DOCUMENTO EN ESTE CASO SERIA PARA LA EMPRESA 02)
      ALTER TABLE documentos DISABLE TRIGGER trigger_documentos_control;
      INSERT INTO documentos 
      VALUES (DEFAULT, '04','FA01','FAV',DEFAULT,DEFAULT,DEFAULT,NULL,NULL,NULL,NULL,'FACTURAS ANULADAS POR VENTA','1');
      ALTER TABLE documentos ENABLE TRIGGER trigger_documentos_control;
  
   ->CREO LA VARIABLE DE MODULO PARA EL DOCUMENTO DE LA FACTURA ANULADO POR VENTA (rc_documento_facturas_anuladas_venta_04 el 04 representa el id de la farmacia si existe otra se bede crear por ejemplo rc_documento_facturas_anuladas_venta_04 es decir siempre con el id de la empresa o farmacia)
     SE DEBE TENER ENCUENTA EL DOCUMENTO_ID PARA LAS FACTURAS ANULADAS POR VENTA (FAV) EN ESTE CASO SE CREO EL 73
     INSERT INTO system_modulos_variables (   modulo,
                                              modulo_tipo,
                                              variable,
                                              valor,
                                              descripcion)
                                              VALUES(
                                              'DevolucionVenta',
                                              'app',
                                              'documento_facturas_anuladas_venta_04',
                                              '76',
                                              'ID DE LA FACTURAS ANULADAS POR VENTA' );
   
-PARA LAS FACTURAS QUE NO VAN HACER ANULADAS
  SE DEBE PARAMETRIZAR LO SIGUIENTE
    -> CREAR EL TIPO GENERAL EN EL CASO DE QUE NO EXISTA EN LA TABLA tipos_doc_generales 
      SI NO EXISTE ENTONCES SE DEBE CREAR ASI:
      
      ALTER TABLE tipos_doc_generales DISABLE TRIGGER tgg_tipos_doc_generales_msg_control;
      INSERT INTO tipos_doc_generales
      VALUES ('NC01', 'NOTAS CREDITO','NC',NULL,NULL,1);
      ALTER TABLE tipos_doc_generales ENABLE TRIGGER tgg_tipos_doc_generales_msg_control;
      
      
    ->CREAR EL DOCUMENTO (ESTE DOCUMENTO SE NECESITA PARA CREAR LA NOTAS CREDITOS DE LA ANULACION DE LA FACTURA)( TENER PRESENTE PARA QUE EMPRESA SE VA A CREAR EL DOCUMENTO EN ESTE CASO SERIA PARA LA EMPRESA 02)
      
      ALTER TABLE documentos DISABLE TRIGGER trigger_documentos_control;
      INSERT INTO documentos 
      VALUES (DEFAULT, '04','NC01','FNC',DEFAULT,DEFAULT,DEFAULT,NULL,NULL,NULL,NULL,'NOTAS CREDITO DE LA FARMACIA','1');
      ALTER TABLE documentos ENABLE TRIGGER trigger_documentos_control;
      
    ->CREO LA VARIABLE DE MODULO PARA EL DOCUMENTO DE LA FACTURA ANULADO POR VENTA (rc_documento_facturas_anuladas_venta_02 el 04 representa el id de la farmacia si existe otra se bede crear por ejemplo rc_documento_facturas_anuladas_venta_04 es decir siempre con el id de la empresa o farmacia)
     SE DEBE TENER ENCUENTA EL DOCUMENTO_ID PARA LAS FACTURAS ANULADAS POR VENTA (FAV) EN ESTE CASO SE CREO EL 77
     INSERT INTO system_modulos_variables (   modulo,
                                              modulo_tipo,
                                              variable,
                                              valor,
                                              descripcion)
                                              VALUES(
                                              'DevolucionVenta',
                                              'app',
                                              'documento_ncredito_devolucion_venta_farmacia_04',
                                              '77',
                                              'ID DEl DOCUMENTO DE NOTA CREDITO FARMACIA' );
   
 



-> PARA AMBAS FACTURAS , PARA LAS FACTURAS ANULADAS Y LAS NO ANULADAS SE UTILIZARA UN PREFIJO PARA LA DEVOLUCION POR RECIBO DE ACUERDO AL ID QUE LA FARMACIA TIENE 
   RECUERDE QUE SI SON VARIAS FARMACIAS QUE PUEDEN HACER DEVOLUCION LA VARIABLE DE MODULO DEBE SER CREADA CON EL ID DE LA FARMACIA 
   ->SE CREA LA VARIABLE DE MODULO PARA AMBAS FACTURAS ESTE PREFJO ES PARA LA DEVOLUCION POR RECIBO C
     INSERT INTO system_modulos_variables (  modulo,
                                             modulo_tipo,
                                             variable,
                                             valor,
                                             descripcion)
                                             VALUES(
                                             'DevolucionVenta',
                                             'app',
                                             'rc_prefijo_devolucion_farmacia_04',
                                             'DVE',
                                             'ID DE LA FACTURAS ANULADAS POR VENTA' );


                      
                               




	 	 	










