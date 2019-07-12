
/*  DISPENSACION DE MEDICAMENTOS ESM */

CREATE TABLE userpermisos_DispensacionESM
(
  empresa_id CHARACTER(2) NOT NULL,
  centro_utilidad 	 character(2) NOT NULL,
  bodega    character(2) NOT NULL,
  usuario_id INTEGER NOT NULL,
  sw_activo CHARACTER(1) NOT NULL DEFAULT '1',
  sw_privilegios  CHARACTER(1) NOT NULL DEFAULT '0'
);

ALTER TABLE userpermisos_DispensacionESM ADD PRIMARY KEY (empresa_id, centro_utilidad, bodega,usuario_id);
ALTER TABLE ONLY  userpermisos_DispensacionESM ADD   FOREIGN KEY (empresa_id, centro_utilidad,bodega) REFERENCES bodegas(empresa_id, centro_utilidad,bodega) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE userpermisos_DispensacionESM ADD FOREIGN KEY(usuario_id)
REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
COMMENT ON TABLE userpermisos_DispensacionESM IS 'Tabla donde se registra el permiso sobre el modulo de digitalizacion de formulas';
COMMENT ON COLUMN userpermisos_DispensacionESM.empresa_id IS '(PK - FK) Identificador de la empresa a la que tiene permiso el usuario';
COMMENT ON COLUMN userpermisos_DispensacionESM.usuario_id IS '(PK - FK) Identificador del usuario';
COMMENT ON COLUMN userpermisos_DispensacionESM.sw_activo IS 'Identifica si el permiso sigue activo';
COMMENT ON COLUMN userpermisos_DispensacionESM.sw_privilegios IS '0=>No tiene privilegios, 1=>Privilegios Basicos, 2=>privilegios especiales';
COMMENT ON COLUMN userpermisos_DispensacionESM.centro_utilidad IS 'Centro utilidad de la Empresa';
COMMENT ON COLUMN userpermisos_DispensacionESM.bodega IS 'Bodega de la empresa ';
GRANT ALL ON TABLE userpermisos_DispensacionESM TO siis;



/* PROCESO DE DISPENSACION DE MEDICAMENTOS ESM */

 CREATE TABLE esm_formulacion_despachos_medicamentos
  (
    esm_formulacion_despacho_id SERIAL NOT NULL,
	formula_id                   integer not NULL,
	bodegas_doc_id               integer not null,
	numeracion                   integer not null,
	sw_estado                    CHARACTER VARYING(1) NOT NULL  DEFAULT '1'
  );
	
    ALTER TABLE esm_formulacion_despachos_medicamentos ADD PRIMARY KEY(esm_formulacion_despacho_id);
	ALTER TABLE esm_formulacion_despachos_medicamentos ADD FOREIGN KEY(formula_id)
	REFERENCES esm_formula_externa(formula_id) ON UPDATE CASCADE ON DELETE RESTRICT;
	ALTER TABLE esm_formulacion_despachos_medicamentos ADD FOREIGN KEY(bodegas_doc_id,numeracion)
	REFERENCES bodegas_documentos(bodegas_doc_id,numeracion) ON UPDATE CASCADE ON DELETE RESTRICT;
			
	
    COMMENT ON TABLE esm_formulacion_despachos_medicamentos IS 'Tabla donde se registran la formula dispensada';
	COMMENT ON COLUMN esm_formulacion_despachos_medicamentos.esm_formulacion_despacho_id IS '(PK) Identificacion de la tabla';
    COMMENT ON COLUMN esm_formulacion_despachos_medicamentos.formula_id IS '(FK) Identificacion de la formula';
	COMMENT ON COLUMN esm_formulacion_despachos_medicamentos.bodegas_doc_id IS '(FK) Identificacion del Movimiento';
	COMMENT ON COLUMN esm_formulacion_despachos_medicamentos.numeracion IS '(FK) Secuencia del Movimiento';
	COMMENT ON COLUMN esm_formulacion_despachos_medicamentos.sw_estado IS '0=>Inactiva, 1=>Activa-Dispensando , 2=>Anulada';
	
	GRANT ALL ON TABLE esm_formulacion_despachos_medicamentos TO siis;
	
	ALTER TABLE esm_formula_externa_medicamentos ADD fecha_entrega date null;
	ALTER TABLE esm_formula_externa_medicamentos ADD proxima_fecha_entrega date null;
	
	ALTER TABLE bodegas_documentos_d ADD sw_pactado CHARACTER VARYING(1)  NULL ;
	COMMENT ON COLUMN bodegas_documentos_d.sw_pactado IS '0=>No pactado, 1=>pactado';
	
	ALTER TABLE esm_formulacion_despachos_medicamentos ADD persona_reclama character varying(60)  NULL ;
		
	ALTER TABLE esm_formulacion_despachos_medicamentos ADD persona_reclama_tipo_id character varying(3) NULL ;
		
	ALTER TABLE esm_formulacion_despachos_medicamentos ADD persona_reclama_id character varying(32) NULL ;
	
	
	/* PENDIENTES DE LA DISPENSACION */
	CREATE TABLE esm_pendientes_por_dispensar
    (
      	esm_pendiente_dispensacion_id serial not null,
	  formula_id                   integer not NULL,
	  codigo_medicamento 	character varying(60) not null,
	  cantidad integer not null,
	  bodegas_doc_id               integer  null,
	  numeracion                   integer  null,
	  sw_estado                    CHARACTER VARYING(1) NOT NULL  DEFAULT '0',
	  usuario_id integer NOT NULL,
      fecha_registro date not null
	);
	
	ALTER TABLE esm_pendientes_por_dispensar ADD PRIMARY KEY(esm_pendiente_dispensacion_id);
	ALTER TABLE esm_pendientes_por_dispensar ADD FOREIGN KEY(formula_id)
	REFERENCES esm_formula_externa(formula_id) ON UPDATE CASCADE ON DELETE RESTRICT;
	ALTER TABLE esm_pendientes_por_dispensar  ADD  FOREIGN KEY (codigo_medicamento) REFERENCES inventarios_productos(codigo_producto);
    ALTER TABLE esm_pendientes_por_dispensar  ADD  FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id);
	ALTER TABLE esm_pendientes_por_dispensar ADD FOREIGN KEY(bodegas_doc_id,numeracion)
	REFERENCES bodegas_documentos(bodegas_doc_id,numeracion) ON UPDATE CASCADE ON DELETE RESTRICT;
	
	ALTER TABLE esm_pendientes_por_dispensar ADD sw_dias CHARACTER VARYING(1)  null ;
	

    COMMENT ON TABLE esm_pendientes_por_dispensar IS 'Tabla para registrar los pendientes  despues de relizar una dispensacion  ESM';
    COMMENT ON COLUMN esm_pendientes_por_dispensar.esm_pendiente_dispensacion_id IS '(PK ) Id de la Tabla';
    COMMENT ON COLUMN esm_pendientes_por_dispensar.formula_id IS '(FK)Identificacion de la formula';
    COMMENT ON COLUMN esm_pendientes_por_dispensar.codigo_medicamento IS 'codigo_medicamento del medicamento formulado';
    COMMENT ON COLUMN esm_pendientes_por_dispensar.cantidad IS 'Cantidad  pendiente';
	COMMENT ON COLUMN esm_pendientes_por_dispensar.bodegas_doc_id IS '(FK) Identificacion del Movimiento';
	COMMENT ON COLUMN esm_pendientes_por_dispensar.numeracion IS '(FK) Secuencia del Movimiento';
	COMMENT ON COLUMN esm_pendientes_por_dispensar.sw_estado IS '0=>pendiente,1=>Entregado,2=>No reclama ';
	COMMENT ON COLUMN esm_pendientes_por_dispensar.usuario_id IS 'Id usuario';
    COMMENT ON COLUMN esm_pendientes_por_dispensar.fecha_registro IS 'fecha de registro';
	COMMENT ON COLUMN esm_pendientes_por_dispensar.sw_dias IS '0=>NO, 1=>paso los dias parametrizados';
	

	
	
    GRANT ALL ON TABLE esm_pendientes_por_dispensar TO siis;


/* tabla temporal */

CREATE TABLE esm_dispensacion_medicamentos_tmp
  (
		esm_dispen_tmp_id SERIAL NOT NULL,
		formula_id                   integer not NULL,
		empresa_id CHARACTER(2) NOT NULL,
		centro_utilidad 	 character(2) NOT NULL,
		bodega    character(2) NOT NULL,
		codigo_producto 	character varying(60) not null,
		cantidad_despachada integer not null,
		fecha_vencimiento 	date 	not null,
		lote 	character varying(30) 	not null
  );
	
	ALTER TABLE esm_dispensacion_medicamentos_tmp ADD PRIMARY KEY(esm_dispen_tmp_id);
	ALTER TABLE esm_dispensacion_medicamentos_tmp ADD FOREIGN KEY(formula_id)
	REFERENCES esm_formula_externa(formula_id) ON UPDATE CASCADE ON DELETE RESTRICT;
	ALTER TABLE esm_dispensacion_medicamentos_tmp  ADD  FOREIGN KEY (codigo_producto) REFERENCES inventarios_productos(codigo_producto);

		ALTER TABLE esm_dispensacion_medicamentos_tmp ADD codigo_formulado character varying(60) not null ;
	
	
	
	COMMENT ON TABLE esm_dispensacion_medicamentos_tmp IS 'Tabla para registrar los temporales de los medicamentos dispensados';
	COMMENT ON COLUMN esm_dispensacion_medicamentos_tmp.esm_dispen_tmp_id IS '(PK ) Id de la Tabla';
	COMMENT ON COLUMN esm_dispensacion_medicamentos_tmp.formula_id IS '(FK)Identificacion de la formula';
	COMMENT ON COLUMN esm_dispensacion_medicamentos_tmp.empresa_id IS 'Id de la empresa';
	COMMENT ON COLUMN esm_dispensacion_medicamentos_tmp.centro_utilidad IS 'Id del centro de utilidad';
	COMMENT ON COLUMN esm_dispensacion_medicamentos_tmp.bodega IS 'Id de la Bodega';
	COMMENT ON COLUMN esm_dispensacion_medicamentos_tmp.codigo_producto IS 'Codigo del producto';
	COMMENT ON COLUMN esm_dispensacion_medicamentos_tmp.cantidad_despachada IS 'Cantidad despachada';
	
 CREATE TABLE esm_formulacion_despachos_medicamentos_pendientes (
  bodegas_doc_id      integer NOT NULL,
  numeracion          integer NOT NULL,
  formula_id          integer NOT NULL,
  empresa_id_factura  char(2),
  prefijo_factura     varchar(4),
  factura_fiscal      integer,
  PRIMARY KEY (bodegas_doc_id, numeracion),
  /* Foreign keys */
  CONSTRAINT foreign_key01
    FOREIGN KEY (bodegas_doc_id, numeracion)
    REFERENCES public.bodegas_documentos(bodegas_doc_id, numeracion)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT foreign_key02
    FOREIGN KEY (formula_id)
    REFERENCES public.esm_formula_externa(formula_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT foreign_key03
    FOREIGN KEY (empresa_id_factura, prefijo_factura, factura_fiscal)
    REFERENCES public.fac_facturas(empresa_id, prefijo, factura_fiscal)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.esm_formulacion_despachos_medicamentos_pendientes
  OWNER TO siis;

COMMENT ON TABLE public.esm_formulacion_despachos_medicamentos_pendientes
  IS 'Tabla Auxiliar de Documentos de Bodega, donde se registra el documento que fue utilizado para despachar pendientes de una formula';

COMMENT ON COLUMN public.esm_formulacion_despachos_medicamentos_pendientes.bodegas_doc_id
  IS 'Campo que referencia un documento de bodega';

COMMENT ON COLUMN public.esm_formulacion_despachos_medicamentos_pendientes.numeracion
  IS 'Numero consecutivo del documento creado';

COMMENT ON COLUMN public.esm_formulacion_despachos_medicamentos_pendientes.formula_id
  IS 'Formula que tenía productos pendientes';

COMMENT ON COLUMN public.esm_formulacion_despachos_medicamentos_pendientes.empresa_id_factura
  IS 'Empresa que genera la factura';

COMMENT ON COLUMN public.esm_formulacion_despachos_medicamentos_pendientes.prefijo_factura
  IS 'Prefijo de la Factura Generada';

COMMENT ON COLUMN public.esm_formulacion_despachos_medicamentos_pendientes.factura_fiscal
  IS 'Numero de Factura Fiscal';

			
			

/*   variable de modulo GLOBAL */
/*
INSERT INTO system_modulos_variables  VALUES('','','dias_pendientes',3,'dias para el pendiente desde el momento que se registra');


INSERT INTO system_modulos_variables  VALUES('','','dispensacion_dias_ultima_entrega',30,'dias validar la ultima entrega de los medicamentos');

INSERT INTO system_modulos_variables  VALUES('','','dispensacion_dias_vigencia_formula',3,'dias que se tienen para la vigencia de la formula antes de ser dispensada');

INSERT INTO system_modulos_variables  VALUES('AdminFarmacia','app','dias_vencimiento_product_bodega_farmacia_12','30','Dias antes de la fecha de vencimiento de un producto');


*/



