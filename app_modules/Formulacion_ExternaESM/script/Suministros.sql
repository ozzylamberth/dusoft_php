

-- Table: userpermisos_esm_suministros

-- DROP TABLE userpermisos_esm_suministros;

CREATE TABLE userpermisos_esm_suministros
(
  empresa_id character(2) NOT NULL, -- Identificador de la empresa
  centro_utilidad character(2) NOT NULL, -- Id del centro de utilidad
  bodega character varying(2) NOT NULL, -- Id de la Bodega satelite
  usuario_id integer NOT NULL, -- Id del Usuario
  CONSTRAINT userpermisos_esm_suministros_pkey PRIMARY KEY (empresa_id, centro_utilidad, bodega, usuario_id),
  CONSTRAINT userpermisos_esm_suministros_empresa_id_fkey FOREIGN KEY (empresa_id, centro_utilidad, bodega)
      REFERENCES bodegas (empresa_id, centro_utilidad, bodega) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT userpermisos_esm_suministros_usuario_id_fkey FOREIGN KEY (usuario_id)
      REFERENCES system_usuarios (usuario_id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
) 
WITHOUT OIDS;
ALTER TABLE userpermisos_esm_suministros OWNER TO postgres;
COMMENT ON TABLE userpermisos_esm_suministros IS 'Permiso a usuarios para el menu de suministros';
COMMENT ON COLUMN userpermisos_esm_suministros.empresa_id IS 'Identificador de la empresa';
COMMENT ON COLUMN userpermisos_esm_suministros.centro_utilidad IS 'Id del centro de utilidad';
COMMENT ON COLUMN userpermisos_esm_suministros.bodega IS 'Id de la Bodega satelite';
COMMENT ON COLUMN userpermisos_esm_suministros.usuario_id IS 'Id del Usuario';



CREATE TABLE public.esm_Formula_suministro_tmp (
  formula_suministro_id_tmp    serial NOT NULL,
  tipo_id_tercero         varchar(3) NOT NULL,
  tercero_id              varchar(32) NOT NULL,
  usuario_id			  integer NOT NULL,
  fecha_registro          timestamp(1) WITHOUT TIME ZONE NOT NULL DEFAULT now(),
  empresa_id              char(2),
  bodega                 character varying(2),
  centro_utilidad         char(2),
  observacion             text,
  /* Keys */
  CONSTRAINT esm_Formula_suministro_tmp_pkey
    PRIMARY KEY (formula_suministro_id_tmp),
  /* Foreign keys */
  CONSTRAINT foreign_key01
    FOREIGN KEY (tipo_id_tercero, tercero_id)
    REFERENCES public.esm_empresas(tipo_id_tercero, tercero_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE, 
  CONSTRAINT foreign_key02
    FOREIGN KEY (usuario_id)
    REFERENCES public.system_usuarios(usuario_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
 
 ) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.esm_Formula_suministro_tmp
  OWNER TO siis;

COMMENT ON TABLE public.esm_Formula_suministro_tmp
  IS 'Tabla Cabecera, que permite Identificar el suministro';

COMMENT ON COLUMN public.esm_Formula_suministro_tmp.formula_suministro_id_tmp
  IS 'Llave primaria que identifica el suministro';

COMMENT ON COLUMN public.esm_Formula_suministro_tmp.tipo_id_tercero
  IS 'Tipo de identificacion de la ESM';

COMMENT ON COLUMN public.esm_Formula_suministro_tmp.tercero_id
  IS 'numero de identificacion del ESM';

COMMENT ON COLUMN public.esm_Formula_suministro_tmp.usuario_id
  IS 'Usuario que Registra el suministro';

COMMENT ON COLUMN public.esm_Formula_suministro_tmp.fecha_registro
  IS 'Fecha de creacion';

COMMENT ON COLUMN public.esm_Formula_suministro_tmp.empresa_id
  IS 'Empresa donde es registrada el suministro';

COMMENT ON COLUMN public.esm_Formula_suministro_tmp.bodega
  IS 'Bodega satelite que hace el suministro';

COMMENT ON COLUMN public.esm_Formula_suministro_tmp.centro_utilidad
  IS 'Centro de Utilidad que registra el suministro';

COMMENT ON COLUMN public.esm_Formula_suministro_tmp.observacion
  IS 'Observacion que se tenga al suministro';
  
  ALTER TABLE esm_Formula_suministro_tmp ADD FOREIGN KEY (empresa_id, centro_utilidad, bodega) REFERENCES bodegas(empresa_id, centro_utilidad, bodega);

  

CREATE TABLE public.esm_Formula_suministro_pacientes_tmp (
  formula_suministro_paciente_id_tmp    serial NOT NULL,
  formula_suministro_id_tmp    integer NOT NULL,
  tipo_id_paciente 	CHARACTER VARYING(3) NOT NULL,	
  paciente_id CHARACTER VARYING(32) NOT NULL,
  codigo_producto            character varying(50) 	NOT NULL,
  cantidad        integer not null,
 
  /* Keys */
  CONSTRAINT esm_Formula_suministro_pacientes_tmp_pkey
    PRIMARY KEY (formula_suministro_paciente_id_tmp),
  /* Foreign keys */
   CONSTRAINT foreign_key01
    FOREIGN KEY (formula_suministro_id_tmp)
    REFERENCES public.esm_Formula_suministro_tmp(formula_suministro_id_tmp)
    ON DELETE CASCADE
    ON UPDATE CASCADE, 
  CONSTRAINT foreign_key02
    FOREIGN KEY (paciente_id,tipo_id_paciente)
    REFERENCES public.pacientes(paciente_id,tipo_id_paciente)
    ON DELETE CASCADE
    ON UPDATE CASCADE, 
	 CONSTRAINT foreign_key03
    FOREIGN KEY (codigo_producto)
    REFERENCES public.inventarios_productos(codigo_producto)
    ON DELETE CASCADE
    ON UPDATE CASCADE
 ) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.esm_Formula_suministro_pacientes_tmp
  OWNER TO siis;

COMMENT ON TABLE public.esm_Formula_suministro_pacientes_tmp
  IS 'Tabla detalle, que permite Identificar el suministro para cada paciente';

COMMENT ON COLUMN public.esm_Formula_suministro_pacientes_tmp.formula_suministro_paciente_id_tmp
  IS 'Llave primaria que identifica el suministro al paciente';
  
  COMMENT ON COLUMN public.esm_Formula_suministro_pacientes_tmp.formula_suministro_id_tmp
  IS 'identifica el suministro';

COMMENT ON COLUMN public.esm_Formula_suministro_pacientes_tmp.tipo_id_paciente
  IS 'Tipo de identificacion del Paciente';

COMMENT ON COLUMN public.esm_Formula_suministro_pacientes_tmp.paciente_id
  IS 'numero de identificacion del Paciente';

COMMENT ON COLUMN public.esm_Formula_suministro_pacientes_tmp.codigo_producto
  IS 'Identificacion del producto suministrado';

COMMENT ON COLUMN public.esm_Formula_suministro_pacientes_tmp.cantidad
  IS 'Cantidad suministrada';
    
  ALTER TABLE esm_Formula_suministro_pacientes_tmp ADD fecha_vencimiento date null;
  ALTER TABLE esm_Formula_suministro_pacientes_tmp ADD lote character varying(255) null;
  
  
  CREATE TABLE public.esm_Formula_suministro (
 bodega_doc_id 	integer NOT NULL,
 numeracion 	integer NOT NULL,
  tipo_id_tercero         varchar(3) NOT NULL,
  tercero_id              varchar(32) NOT NULL,
  usuario_id			  integer NOT NULL,
  fecha_registro          timestamp(1) WITHOUT TIME ZONE NOT NULL DEFAULT now(),
  empresa_id              char(2),
  bodega                 character varying(2),
  centro_utilidad         char(2),
  observacion             text,
  /* Keys */
  CONSTRAINT esm_Formula_suministro_pkey
    PRIMARY KEY (bodega_doc_id,numeracion),
  /* Foreign keys */
  CONSTRAINT foreign_key01
    FOREIGN KEY (tipo_id_tercero, tercero_id)
    REFERENCES public.esm_empresas(tipo_id_tercero, tercero_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE, 
  CONSTRAINT foreign_key02
    FOREIGN KEY (usuario_id)
    REFERENCES public.system_usuarios(usuario_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE, 
  CONSTRAINT foreign_key03
    FOREIGN KEY (empresa_id, centro_utilidad, bodega)
    REFERENCES public.bodegas(empresa_id, centro_utilidad, bodega)
    ON DELETE RESTRICT
    ON UPDATE CASCADE 
 ) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.esm_Formula_suministro
  OWNER TO siis;

COMMENT ON TABLE public.esm_Formula_suministro
  IS 'Tabla Cabecera, que permite Identificar el suministro';

COMMENT ON COLUMN public.esm_Formula_suministro.bodega_doc_id
  IS 'Llave primaria ';
COMMENT ON COLUMN public.esm_Formula_suministro.numeracion
  IS 'Llave primaria ';


COMMENT ON COLUMN public.esm_Formula_suministro.tipo_id_tercero
  IS 'Tipo de identificacion de la ESM';

COMMENT ON COLUMN public.esm_Formula_suministro.tercero_id
  IS 'numero de identificacion del ESM';

COMMENT ON COLUMN public.esm_Formula_suministro.usuario_id
  IS 'Usuario que Registra el suministro';

COMMENT ON COLUMN public.esm_Formula_suministro.fecha_registro
  IS 'Fecha de creacion';

COMMENT ON COLUMN public.esm_Formula_suministro.empresa_id
  IS 'Empresa donde es registrada el suministro';

COMMENT ON COLUMN public.esm_Formula_suministro.bodega
  IS 'Bodega satelite que hace el suministro';

COMMENT ON COLUMN public.esm_Formula_suministro.centro_utilidad
  IS 'Centro de Utilidad que registra el suministro';

COMMENT ON COLUMN public.esm_Formula_suministro.observacion
  IS 'Observacion que se tenga al suministro';
  

 ALTER TABLE esm_Formula_suministro ADD FOREIGN KEY (bodega_doc_id, numeracion) REFERENCES bodegas_documentos(bodegas_doc_id,numeracion);

  
  

CREATE TABLE public.esm_Formula_suministro_pacientes (
  formula_suministro_paciente_id   serial NOT NULL,
 bodega_doc_id 	integer NOT NULL,
 numeracion 	integer NOT NULL,
  tipo_id_paciente 	CHARACTER VARYING(3) NOT NULL,	
  paciente_id CHARACTER VARYING(32) NOT NULL,
  codigo_producto            character varying(50) 	NOT NULL,
  cantidad        integer not null,
 
  /* Keys */
  CONSTRAINT esm_Formula_suministro_pacientes_pkey
    PRIMARY KEY (formula_suministro_paciente_id),
  /* Foreign keys */
 
  CONSTRAINT foreign_key02
    FOREIGN KEY (paciente_id,tipo_id_paciente)
    REFERENCES public.pacientes(paciente_id,tipo_id_paciente)
    ON DELETE CASCADE
    ON UPDATE CASCADE, 
	 CONSTRAINT foreign_key03
    FOREIGN KEY (codigo_producto)
    REFERENCES public.inventarios_productos(codigo_producto)
    ON DELETE CASCADE
    ON UPDATE CASCADE
 ) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.esm_Formula_suministro_pacientes
  OWNER TO siis;

COMMENT ON TABLE public.esm_Formula_suministro_pacientes
  IS 'Tabla detalle, que permite Identificar el suministro para cada paciente';

COMMENT ON COLUMN public.esm_Formula_suministro_pacientes.formula_suministro_paciente_id
  IS 'Llave primaria que identifica el suministro al paciente';

  

  
COMMENT ON COLUMN public.esm_Formula_suministro_pacientes.tipo_id_paciente
  IS 'Tipo de identificacion del Paciente';

COMMENT ON COLUMN public.esm_Formula_suministro_pacientes.paciente_id
  IS 'numero de identificacion del Paciente';

COMMENT ON COLUMN public.esm_Formula_suministro_pacientes.codigo_producto
  IS 'Identificacion del producto suministrado';

COMMENT ON COLUMN public.esm_Formula_suministro_pacientes.cantidad
  IS 'Cantidad suministrada';

  ALTER TABLE esm_Formula_suministro_pacientes ADD fecha_vencimiento date null;
  ALTER TABLE esm_Formula_suministro_pacientes ADD lote character varying(255) null;

 ALTER TABLE esm_Formula_suministro_pacientes ADD FOREIGN KEY (bodega_doc_id, numeracion) REFERENCES esm_Formula_suministro(bodega_doc_id,numeracion);

  
  
  
  
  
  









	