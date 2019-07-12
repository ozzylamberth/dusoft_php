CREATE TABLE public.userpermisos_notasfacturas_despachos (
  empresa_id         char(2) NOT NULL,
  indice_automatico  serial NOT NULL,
  usuario_id         integer NOT NULL,
  /* Keys */
  CONSTRAINT userpermisos_notasfacturas_despachos_pkey
    PRIMARY KEY (empresa_id, usuario_id), 
  CONSTRAINT userpermisos_notasfacturas_despachos_indice_automatico_key
    UNIQUE (indice_automatico)
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.userpermisos_notasfacturas_despachos
  OWNER TO siis;

COMMENT ON TABLE public.userpermisos_notasfacturas_despachos
  IS 'Permisos modulo de notas a facturas de despachos';

COMMENT ON COLUMN public.userpermisos_notasfacturas_despachos.empresa_id
  IS 'Empresa a la que pertenece el usuario';

COMMENT ON COLUMN public.userpermisos_notasfacturas_despachos.indice_automatico
  IS 'Campo Serial';

COMMENT ON COLUMN public.userpermisos_notasfacturas_despachos.usuario_id
  IS 'usuario del Módulo';

  
  CREATE TABLE public.inv_notas_facturas_despacho_tmp (
  doc_nota_tmp_id  integer NOT NULL DEFAULT 0,
  prefijo          varchar(4) NOT NULL,
  numero           integer NOT NULL,
  usuario_id       integer NOT NULL,
  tipo_id_tercero  varchar(3) NOT NULL,
  tercero_id       integer NOT NULL,
  fecha_registro   timestamp WITHOUT TIME ZONE NOT NULL,
  empresa_id       varchar(2) NOT NULL,
  documento_id     integer,
  /* Keys */
  CONSTRAINT inv_notas_facturas_despacho_tmp_pkey
    PRIMARY KEY (doc_nota_tmp_id)
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.inv_notas_facturas_despacho_tmp
  OWNER TO siis;

COMMENT ON TABLE public.inv_notas_facturas_despacho_tmp
  IS 'Antes de crear una nota, esta debe crearse primero en un documento temporal';

COMMENT ON COLUMN public.inv_notas_facturas_despacho_tmp.doc_nota_tmp_id
  IS 'Es el Id temporal del Documento a crear';

COMMENT ON COLUMN public.inv_notas_facturas_despacho_tmp.prefijo
  IS 'Es el PREFIJO de la factura a la que hacemos referencia la Nota DEBITO/CREDITO.';

COMMENT ON COLUMN public.inv_notas_facturas_despacho_tmp.numero
  IS 'Es el numero de la factura a la que hacemos referencia la Nota DEBITO/CREDITO.';

COMMENT ON COLUMN public.inv_notas_facturas_despacho_tmp.usuario_id
  IS 'Usuario que realiza la nota y quien tiene permiso para hacer algo con la nota';

COMMENT ON COLUMN public.inv_notas_facturas_despacho_tmp.tipo_id_tercero
  IS 'Tipo de Identificacion del Tercero (NIT, CC, RC etc...)';

COMMENT ON COLUMN public.inv_notas_facturas_despacho_tmp.tercero_id
  IS 'Es el numero de identificacion del tercero';

COMMENT ON COLUMN public.inv_notas_facturas_despacho_tmp.fecha_registro
  IS 'La fecha de creacion del documento';

COMMENT ON COLUMN public.inv_notas_facturas_despacho_tmp.empresa_id
  IS 'Empresa Dueña del Documento temporal';

COMMENT ON COLUMN public.inv_notas_facturas_despacho_tmp.documento_id
  IS 'Documento al que se va a utilizar en la creacion de notas debito/credito';

  
  CREATE TABLE public.inv_notas_facturas_despacho_d_tmp (
  doc_nota_tmp_id  integer NOT NULL,
  item_id          serial NOT NULL,
  empresa_id       varchar(2) NOT NULL,
  concepto         text NOT NULL,
  codigo_producto  varchar(50),
  valor_concepto   numeric(14,2) NOT NULL,
  lote             varchar(30),
  /* Keys */
  CONSTRAINT inv_notas_facturas_despacho_d_tmp_tmp_pkey
    PRIMARY KEY (item_id)
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.inv_notas_facturas_despacho_d_tmp
  OWNER TO siis;

COMMENT ON TABLE public.inv_notas_facturas_despacho_d_tmp
  IS 'Es el detalle de la nota de la factura Proveedor';

COMMENT ON COLUMN public.inv_notas_facturas_despacho_d_tmp.doc_nota_tmp_id
  IS 'Relaciona con la cabecera del documento temporal con el detalle';

COMMENT ON COLUMN public.inv_notas_facturas_despacho_d_tmp.item_id
  IS 'Campo serial donde va almacenando los Items de las notas DEBITO/CREDITO';

COMMENT ON COLUMN public.inv_notas_facturas_despacho_d_tmp.empresa_id
  IS 'Empresa dueña del documento';

COMMENT ON COLUMN public.inv_notas_facturas_despacho_d_tmp.concepto
  IS 'descripcion del concepto de la nota CREDITO/DEBITO';

COMMENT ON COLUMN public.inv_notas_facturas_despacho_d_tmp.codigo_producto
  IS 'Si la nota tiene asociada un producto';

COMMENT ON COLUMN public.inv_notas_facturas_despacho_d_tmp.valor_concepto
  IS 'Registra el valor del concepto de la nota DEBITO/CREDITO';

COMMENT ON COLUMN public.inv_notas_facturas_despacho_d_tmp.lote
  IS 'Lote Del Producto para la nota';
  
  CREATE TABLE public.inv_notas_facturas_despacho (
  empresa_id             varchar(2) NOT NULL,
  numero                 integer NOT NULL,
  prefijo                varchar(4) NOT NULL,
  documento_id           integer NOT NULL,
  prefijo_factura        varchar(4) NOT NULL,
  numero_factura         integer NOT NULL,
  usuario_id             integer NOT NULL,
  tipo_id_tercero        varchar(3) NOT NULL,
  tercero_id             integer NOT NULL,
  fecha_registro         date NOT NULL,
  valor_nota             numeric(14,2) NOT NULL,
  sw_anulado             varchar(1) NOT NULL DEFAULT 0,
  observacion_anulacion  text,
  usuario_id_anulador    integer,
  fecha_anulacion        timestamp(1) WITHOUT TIME ZONE,
  /* Keys */
  CONSTRAINT inv_notas_facturas_despacho_pkey
    PRIMARY KEY (empresa_id, numero, prefijo)
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.inv_notas_facturas_despacho
  OWNER TO siis;

COMMENT ON TABLE public.inv_notas_facturas_despacho
  IS 'Tabla donde se guardaran las notas a las facturas de un proveedor';

COMMENT ON COLUMN public.inv_notas_facturas_despacho.empresa_id
  IS 'Empresa a la que realiza la nota (debito/credito)';

COMMENT ON COLUMN public.inv_notas_facturas_despacho.numero
  IS 'Numero del Documento';

COMMENT ON COLUMN public.inv_notas_facturas_despacho.prefijo
  IS 'Prefijo del documento de Notas (Debito/Credito)';

COMMENT ON COLUMN public.inv_notas_facturas_despacho.documento_id
  IS 'Id del Documento Parametrizado en la tabla Documentos';

COMMENT ON COLUMN public.inv_notas_facturas_despacho.usuario_id
  IS 'Usuario que realiza la nota a la factura';

COMMENT ON COLUMN public.inv_notas_facturas_despacho.tipo_id_tercero
  IS 'Tipo de Identificacion del tercero';

COMMENT ON COLUMN public.inv_notas_facturas_despacho.tercero_id
  IS 'Identificacion del Tercero';

COMMENT ON COLUMN public.inv_notas_facturas_despacho.fecha_registro
  IS 'Fecha de realizada la nota';

COMMENT ON COLUMN public.inv_notas_facturas_despacho.valor_nota
  IS 'Es el valor de la Nota (Sea Debito/Credito)';

COMMENT ON COLUMN public.inv_notas_facturas_despacho.sw_anulado
  IS 'Dice si una nota (Debito/Credito) ha sido Anulada (''1'') o Activa(''0'')';

COMMENT ON COLUMN public.inv_notas_facturas_despacho.observacion_anulacion
  IS 'En Caso de Anular una nota, se debe registrar una jusificacion';

COMMENT ON COLUMN public.inv_notas_facturas_despacho.usuario_id_anulador
  IS 'Usuario que anula la nota a la factura';

COMMENT ON COLUMN public.inv_notas_facturas_despacho.fecha_anulacion
  IS 'Fecha Anulacion de la Nota';

  CREATE TABLE public.inv_notas_facturas_despacho_d (
  item_id          serial NOT NULL,
  empresa_id       varchar(2) NOT NULL,
  prefijo          varchar(4) NOT NULL,
  numero           integer NOT NULL,
  concepto         varchar(100) NOT NULL,
  codigo_producto  varchar(50),
  valor_concepto   numeric(14,2) NOT NULL,
  lote             varchar(30),
  /* Keys */
  CONSTRAINT inv_notas_facturas_despacho_d_pkey
    PRIMARY KEY (item_id)
) WITH (
    OIDS = FALSE
  );

ALTER TABLE public.inv_notas_facturas_despacho_d
  OWNER TO siis;

COMMENT ON TABLE public.inv_notas_facturas_despacho_d
  IS 'Tabla donde se guardaran el Detalle de una Nota (debito/credito) de una factura del proveedor';

COMMENT ON COLUMN public.inv_notas_facturas_despacho_d.empresa_id
  IS 'Empresa q realiza la nota (débito/credito)';

COMMENT ON COLUMN public.inv_notas_facturas_despacho_d.prefijo
  IS 'Prefijo del Documento nota (Debito/Credito)';

COMMENT ON COLUMN public.inv_notas_facturas_despacho_d.numero
  IS 'Numero del documento (Debito/Credito)';

COMMENT ON COLUMN public.inv_notas_facturas_despacho_d.concepto
  IS 'Concepto de la Nota(Debito/Credito)';

COMMENT ON COLUMN public.inv_notas_facturas_despacho_d.codigo_producto
  IS 'Producto al que puede estar asociado a la nota';

COMMENT ON COLUMN public.inv_notas_facturas_despacho_d.valor_concepto
  IS 'Valor del concepto de la nota (Debito/Credito)';

COMMENT ON COLUMN public.inv_notas_facturas_despacho_d.lote
  IS 'Lote del producto asociado';