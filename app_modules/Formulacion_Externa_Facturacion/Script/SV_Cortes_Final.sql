

/*  CABECERA DEL CORTE */

CREATE TABLE esm_corte_temporal
(
	corte_tmp_id     serial NOT NULL,
	fecha_inicio     Date not null,
	fecha_final      Date NOT NULL,
	empresa_id        CHARACTER(2)  NULL,
	usuario_id 	      INTEGER NOT NULL,
	fecha_registro    TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT NOW()
	 
);

ALTER TABLE esm_corte_temporal ADD PRIMARY KEY (corte_tmp_id);
ALTER TABLE esm_corte_temporal ADD FOREIGN KEY (empresa_id) REFERENCES empresas(empresa_id)ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE esm_corte_temporal ADD FOREIGN KEY (usuario_id)
REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;


COMMENT ON TABLE esm_corte_temporal IS ' Tabla donde se registra los cortes';
COMMENT ON COLUMN esm_corte_temporal.corte_tmp_id IS '(PK) Identificador de la Tabla';
COMMENT ON COLUMN esm_corte_temporal.fecha_inicio IS 'Fecha de Inicio del corte';
COMMENT ON COLUMN esm_corte_temporal.fecha_final IS 'Fecha Final del corte';
COMMENT ON COLUMN esm_corte_temporal.empresa_id IS '(FK) Identificador de la empresa';
COMMENT ON COLUMN esm_corte_temporal.usuario_id IS '(FK) Identificador del usuario';
COMMENT ON COLUMN esm_corte_temporal.fecha_registro IS 'Fecha de registro';

GRANT ALL ON TABLE esm_corte_temporal TO siis;

/* DETALLE DEL CORTE PARA LOS TRASLADOS */
CREATE TABLE esm_corte_traslados_temporal
(
	empresa_id        CHARACTER(2) not  NULL,
	corte_tmp_id      integer  NOT NULL,
	empresa_tras_id 	character(2)  not  NULL,
	prefijo           	character varying(4) not  NULL,
 	numero 		integer not  NULL
	 
);
ALTER TABLE esm_corte_traslados_temporal ADD PRIMARY KEY (empresa_id,corte_tmp_id,empresa_tras_id,prefijo,numero);
ALTER TABLE esm_corte_traslados_temporal ADD FOREIGN KEY (empresa_id) REFERENCES empresas(empresa_id)ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE esm_corte_traslados_temporal ADD FOREIGN KEY (empresa_tras_id,prefijo,numero)
REFERENCES  inv_bodegas_movimiento_traslados_esm(empresa_id,prefijo,numero) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE esm_corte_traslados_temporal ADD FOREIGN KEY (corte_tmp_id) REFERENCES esm_corte_temporal(corte_tmp_id)ON UPDATE CASCADE ON DELETE CASCADE;





alter table  inv_bodegas_movimiento_traslados_esm  ADD sw_corte CHARACTER(2) not  NULL  DEFAULT '0';

/*COMMENT ON TABLE esm_corte_traslados_temporal IS ' Tabla donde se registra los cortes';
COMMENT ON COLUMN esm_corte_traslados_temporal.empresa_id IS '(PK) Identificador de la Tabla';
COMMENT ON COLUMN esm_corte_traslados_temporal.corte_tmp_id IS 'Fecha de Inicio del corte';
COMMENT ON COLUMN esm_corte_traslados_temporal.empresa_tras_id IS 'Fecha Final del corte';
COMMENT ON COLUMN esm_corte_traslados_temporal.empresa_id IS '(FK) Identificador de la empresa';
COMMENT ON COLUMN esm_corte_traslados_temporal.usuario_id IS '(FK) Identificador del usuario';
COMMENT ON COLUMN esm_corte_traslados_temporal.fecha_registro IS 'Fecha de registro';
*/
GRANT ALL ON TABLE esm_corte_traslados_temporal TO siis;

/* DETALLE DEL CORTE PARA LOS DESPACHOS DE CAMPANIA */
CREATE TABLE esm_corte_despacho_campania_temporal
(
	empresa_id        CHARACTER(2) not  NULL,
	corte_tmp_id      integer  NOT NULL,
	empresa_des_id 	character(2)  not  NULL,
	prefijo           	character varying(4) not  NULL,
 	numero 		integer not  NULL
	 
);

ALTER TABLE esm_corte_despacho_campania_temporal ADD PRIMARY KEY (empresa_id,corte_tmp_id,empresa_des_id,prefijo,numero);
ALTER TABLE esm_corte_despacho_campania_temporal ADD FOREIGN KEY (empresa_id) REFERENCES empresas(empresa_id)ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE esm_corte_despacho_campania_temporal ADD FOREIGN KEY (empresa_des_id,prefijo,numero)
REFERENCES  inv_bodegas_movimiento_despacho_campania(empresa_id,prefijo,numero) ON UPDATE CASCADE ON DELETE RESTRICT;

GRANT ALL ON TABLE esm_corte_despacho_campania_temporal TO siis;

alter table  inv_bodegas_movimiento_despacho_campania  ADD sw_corte CHARACTER(2) not  NULL  DEFAULT '0';

ALTER TABLE esm_corte_despacho_campania_temporal ADD FOREIGN KEY (corte_tmp_id) REFERENCES esm_corte_temporal(corte_tmp_id)ON UPDATE CASCADE ON DELETE CASCADE;



/* DETALLE DEL CORTE PARA LOS DOCUMENTOS DE DISPENSACION */

CREATE TABLE esm_corte_despacho_medicamentos_temporal
(
	empresa_id        CHARACTER(2) not  NULL,
	corte_tmp_id      integer  NOT NULL,
	esm_formulacion_despacho_id 	integer not  NULL
	 
);

ALTER TABLE esm_corte_despacho_medicamentos_temporal ADD PRIMARY KEY (empresa_id,corte_tmp_id,esm_formulacion_despacho_id);
ALTER TABLE esm_corte_despacho_medicamentos_temporal ADD FOREIGN KEY (empresa_id) REFERENCES empresas(empresa_id)ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE esm_corte_despacho_medicamentos_temporal ADD FOREIGN KEY (esm_formulacion_despacho_id)
REFERENCES  esm_formulacion_despachos_medicamentos(esm_formulacion_despacho_id) ON UPDATE CASCADE ON DELETE RESTRICT;

GRANT ALL ON TABLE esm_corte_despacho_medicamentos_temporal TO siis;

alter table  esm_formulacion_despachos_medicamentos  ADD sw_corte CHARACTER(2) not  NULL  DEFAULT '0';
alter table  esm_formulacion_despachos_medicamentos_pendientes  ADD sw_corte CHARACTER(2) not  NULL  DEFAULT '0';

ALTER TABLE esm_corte_despacho_medicamentos_temporal ADD FOREIGN KEY (corte_tmp_id) REFERENCES esm_corte_temporal(corte_tmp_id)ON UPDATE CASCADE ON DELETE CASCADE;


/* DETALLE DEL CORTE PARA LOS DOCUMENTOS PENDIENTES DISPENSADOS */

CREATE TABLE esm_corte_despacho_medicamentos_pendientes_temporal
(
	empresa_id        CHARACTER(2) not  NULL,
	corte_tmp_id      integer  NOT NULL,
	bodegas_doc_id 	integer not  NULL,
	numeracion     integer not  NULL
	 
);

ALTER TABLE esm_corte_despacho_medicamentos_pendientes_temporal ADD PRIMARY KEY (empresa_id,corte_tmp_id,bodegas_doc_id,numeracion);
ALTER TABLE esm_corte_despacho_medicamentos_pendientes_temporal ADD FOREIGN KEY (empresa_id) REFERENCES empresas(empresa_id)ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE esm_corte_despacho_medicamentos_pendientes_temporal ADD FOREIGN KEY (bodegas_doc_id,numeracion)
REFERENCES  esm_formulacion_despachos_medicamentos_pendientes(bodegas_doc_id,numeracion) ON UPDATE CASCADE ON DELETE RESTRICT;

GRANT ALL ON TABLE esm_corte_despacho_medicamentos_pendientes_temporal TO siis;

alter table  esm_formulacion_despachos_medicamentos_pendientes  ADD sw_corte CHARACTER(2) not  NULL  DEFAULT '0';

ALTER TABLE esm_corte_despacho_medicamentos_pendientes_temporal ADD FOREIGN KEY (corte_tmp_id) REFERENCES esm_corte_temporal(corte_tmp_id)ON UPDATE CASCADE ON DELETE CASCADE;

/* REALES
*/
CREATE TABLE esm_corte
(
	corte_id     serial NOT NULL,
	fecha_inicio     Date not null,
	fecha_final      Date NOT NULL,
	empresa_id        CHARACTER(2)  NULL,
	usuario_id 	      INTEGER NOT NULL,
	fecha_registro    TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT NOW()
	 
);

ALTER TABLE esm_corte ADD PRIMARY KEY (corte_id);
ALTER TABLE esm_corte ADD FOREIGN KEY (empresa_id) REFERENCES empresas(empresa_id)ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE esm_corte ADD FOREIGN KEY (usuario_id)
REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;


COMMENT ON TABLE esm_corte IS ' Tabla donde se registra los cortes';
COMMENT ON COLUMN esm_corte.corte_id IS '(PK) Identificador de la Tabla';
COMMENT ON COLUMN esm_corte.fecha_inicio IS 'Fecha de Inicio del corte';
COMMENT ON COLUMN esm_corte.fecha_final IS 'Fecha Final del corte';
COMMENT ON COLUMN esm_corte.empresa_id IS '(FK) Identificador de la empresa';
COMMENT ON COLUMN esm_corte.usuario_id IS '(FK) Identificador del usuario';
COMMENT ON COLUMN esm_corte.fecha_registro IS 'Fecha de registro';
GRANT ALL ON TABLE esm_corte TO siis;


/* DETALLE DEL CORTE PARA LOS TRASLADOS */
CREATE TABLE esm_corte_traslados
(
	empresa_id        CHARACTER(2) not  NULL,
	corte_id      integer  NOT NULL,
	empresa_tras_id 	character(2)  not  NULL,
	prefijo           	character varying(4) not  NULL,
 	numero 		integer not  NULL
	 
);
ALTER TABLE esm_corte_traslados ADD PRIMARY KEY (empresa_id,corte_id,empresa_tras_id,prefijo,numero);
ALTER TABLE esm_corte_traslados ADD FOREIGN KEY (empresa_id) REFERENCES empresas(empresa_id)ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE esm_corte_traslados ADD FOREIGN KEY (empresa_tras_id,prefijo,numero)
REFERENCES  inv_bodegas_movimiento_traslados_esm(empresa_id,prefijo,numero) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE esm_corte_traslados ADD FOREIGN KEY (corte_id) REFERENCES esm_corte(corte_id)ON UPDATE CASCADE ON DELETE CASCADE;
GRANT ALL ON TABLE esm_corte_traslados TO siis;

/* DETALLE DEL CORTE PARA LOS DESPACHOS DE CAMPANIA */
CREATE TABLE esm_corte_despacho_campania
(
	empresa_id        CHARACTER(2) not  NULL,
	corte_id      integer  NOT NULL,
	empresa_des_id 	character(2)  not  NULL,
	prefijo           	character varying(4) not  NULL,
 	numero 		integer not  NULL
	 
);

ALTER TABLE esm_corte_despacho_campania ADD PRIMARY KEY (empresa_id,corte_id,empresa_des_id,prefijo,numero);
ALTER TABLE esm_corte_despacho_campania ADD FOREIGN KEY (empresa_id) REFERENCES empresas(empresa_id)ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE esm_corte_despacho_campania ADD FOREIGN KEY (empresa_des_id,prefijo,numero)
REFERENCES  inv_bodegas_movimiento_despacho_campania(empresa_id,prefijo,numero) ON UPDATE CASCADE ON DELETE RESTRICT;
GRANT ALL ON TABLE esm_corte_despacho_campania TO siis;
ALTER TABLE esm_corte_despacho_campania ADD FOREIGN KEY (corte_id) REFERENCES esm_corte(corte_id)ON UPDATE CASCADE ON DELETE CASCADE;



/* DETALLE DEL CORTE PARA LOS DOCUMENTOS DE DISPENSACION */

CREATE TABLE esm_corte_despacho_medicamentos
(
	empresa_id        CHARACTER(2) not  NULL,
	corte_id      integer  NOT NULL,
	esm_formulacion_despacho_id 	integer not  NULL
	 
);

ALTER TABLE esm_corte_despacho_medicamentos ADD PRIMARY KEY (empresa_id,corte_id,esm_formulacion_despacho_id);
ALTER TABLE esm_corte_despacho_medicamentos ADD FOREIGN KEY (empresa_id) REFERENCES empresas(empresa_id)ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE esm_corte_despacho_medicamentos ADD FOREIGN KEY (esm_formulacion_despacho_id)
REFERENCES  esm_formulacion_despachos_medicamentos(esm_formulacion_despacho_id) ON UPDATE CASCADE ON DELETE RESTRICT;
GRANT ALL ON TABLE esm_corte_despacho_medicamentos TO siis;

	
 ALTER TABLE esm_corte_despacho_medicamentos ADD FOREIGN KEY (corte_id) REFERENCES esm_corte(corte_id) ON UPDATE CASCADE ON DELETE CASCADE
ALTER TABLE esm_formulacion_despachos_medicamentos ADD FOREIGN KEY (corte_id) REFERENCES esm_corte(corte_id)ON UPDATE CASCADE ON DELETE CASCADE;


/* DETALLE DEL CORTE PARA LOS DOCUMENTOS PENDIENTES DISPENSADOS */

CREATE TABLE esm_corte_despacho_medicamentos_pendientes
(
	empresa_id        CHARACTER(2) not  NULL,
	corte_id      integer  NOT NULL,
	bodegas_doc_id 	integer not  NULL,
	numeracion     integer not  NULL
	 
);

ALTER TABLE esm_corte_despacho_medicamentos_pendientes ADD PRIMARY KEY (empresa_id,corte_id,bodegas_doc_id,numeracion);
ALTER TABLE esm_corte_despacho_medicamentos_pendientes ADD FOREIGN KEY (empresa_id) REFERENCES empresas(empresa_id)ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE esm_corte_despacho_medicamentos_pendientes ADD FOREIGN KEY (bodegas_doc_id,numeracion)
REFERENCES  esm_formulacion_despachos_medicamentos_pendientes(bodegas_doc_id,numeracion) ON UPDATE CASCADE ON DELETE RESTRICT;

GRANT ALL ON TABLE esm_corte_despacho_medicamentos_pendientes TO siis;
ALTER TABLE esm_corte_despacho_medicamentos_pendientes ADD FOREIGN KEY (corte_id) REFERENCES esm_corte(corte_id)ON UPDATE CASCADE ON DELETE CASCADE;


CREATE TABLE public.esm_corte_dispensacion (
  formula_papel              varchar(30) NOT NULL,
  documento                  varchar(80) NOT NULL,
  ems_corte_id               integer NOT NULL,
  valor                      numeric(18,2) NOT NULL,
  descripcion                text NOT NULL,
  esm_corte_dispensacion_id  serial NOT NULL,
  /* Keys */
  CONSTRAINT esm_corte_dispensacion_pkey
    PRIMARY KEY (esm_corte_dispensacion_id),
  /* Foreign keys */
  CONSTRAINT foreign_key01
    FOREIGN KEY (ems_corte_id)
    REFERENCES public.esm_corte(corte_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.esm_corte_dispensacion
  OWNER TO siis;

COMMENT ON TABLE public.esm_corte_dispensacion
  IS 'Tabla que permite guardar los documentos que se ha utilizado para dispensar medicamentos';

COMMENT ON COLUMN public.esm_corte_dispensacion.formula_papel
  IS 'Numero de la formula en papel';

COMMENT ON COLUMN public.esm_corte_dispensacion.documento
  IS 'Es el documento de bodega usado, para dispensar la formula. (Prefijo-Numeracion)';

COMMENT ON COLUMN public.esm_corte_dispensacion.ems_corte_id
  IS 'Numero del Corte';

COMMENT ON COLUMN public.esm_corte_dispensacion.valor
  IS 'Valor del Documento';

COMMENT ON COLUMN public.esm_corte_dispensacion.descripcion
  IS 'Descripcion del documento de Bodega Usado para dispensar';


CREATE TABLE public.esm_corte_dispensacion_pendientes (
  formula_papel              varchar(30) NOT NULL,
  documento                  varchar(80) NOT NULL,
  ems_corte_id               integer NOT NULL,
  valor                      numeric(18,2) NOT NULL,
  descripcion                text NOT NULL,
  esm_corte_dispensacion_pendientes_id  serial NOT NULL,
  /* Keys */
  CONSTRAINT esm_corte_dispensacion_pendientes_pkey
    PRIMARY KEY (esm_corte_dispensacion_pendientes_id),
  /* Foreign keys */
  CONSTRAINT foreign_key01
    FOREIGN KEY (ems_corte_id)
    REFERENCES public.esm_corte(corte_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.esm_corte_dispensacion_pendientes
  OWNER TO siis;

COMMENT ON TABLE public.esm_corte_dispensacion_pendientes
  IS 'Tabla que permite guardar los documentos que se ha utilizado para dispensar medicamentos';

COMMENT ON COLUMN public.esm_corte_dispensacion_pendientes.formula_papel
  IS 'Numero de la formula en papel';

COMMENT ON COLUMN public.esm_corte_dispensacion_pendientes.documento
  IS 'Es el documento de bodega usado, para dispensar la formula. (Prefijo-Numeracion)';

COMMENT ON COLUMN public.esm_corte_dispensacion_pendientes.ems_corte_id
  IS 'Numero del Corte';

COMMENT ON COLUMN public.esm_corte_dispensacion_pendientes.valor
  IS 'Valor del Documento';

COMMENT ON COLUMN public.esm_corte_dispensacion_pendientes.descripcion
  IS 'Descripcion del documento de Bodega Usado para dispensar';



  ALTER TABLE public.esm_formulacion_despachos_medicamentos
  ADD COLUMN esm_corte_id integer;

COMMENT ON COLUMN public.esm_formulacion_despachos_medicamentos.esm_corte_id
  IS 'Si pertenece a un corte, este campo referencia al que hace parte';

  ALTER TABLE public.esm_formulacion_despachos_medicamentos
  ADD CONSTRAINT foreign_key02
  FOREIGN KEY (esm_corte_id)
    REFERENCES public.esm_corte(corte_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE;

ALTER TABLE public.esm_formulacion_despachos_medicamentos_pendientes
  ADD COLUMN esm_corte_id integer;

COMMENT ON COLUMN public.esm_formulacion_despachos_medicamentos_pendientes.esm_corte_id
  IS 'Si pertenece a un corte, este campo referencia al que hace parte';

  ALTER TABLE public.esm_formulacion_despachos_medicamentos_pendientes
  ADD CONSTRAINT foreign_key04
  FOREIGN KEY (esm_corte_id)
    REFERENCES public.esm_corte(corte_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE;
  
  
  
  
  
  
  
  
  
    FOREIGN KEY (corte_id) REFERENCES esm_corte(corte_id) ON UPDATE CASCADE ON DELETE CASCADE
  
  

  
  
  
  
  
  
  
  
  
  
  