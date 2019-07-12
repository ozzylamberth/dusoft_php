 -------------TABLA Parametrizacion usuarios jefe de bodega y jefe auditoria interna---------------------------------------------------------------------------------------------------------------------------------------------
CREATE TABLE parame_usuariosjefebodcon
(
   id_parame_usuariosjefebodcon serial NOT NULL,
   usuario_id integer NOT NULL,
   activo character(1) default('1'),
   fecha_registro timestamp without time zone
)

ALTER TABLE parame_usuariosjefebodcon ADD PRIMARY KEY (id_parame_usuariosjefebodcon);
ALTER TABLE parame_usuariosjefebodcon ADD FOREIGN KEY(usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;

-------------TABLA salidas_productos_tmp---------------------------------------------------------------------------------------------------------------------------------------------
CREATE TABLE salidas_productos_tmp
(
   doc_tmp_id integer NOT NULL,
   empresa_id character varying(2) ,
   usuario_registro integer NOT NULL,
   fecha_registro timestamp without time zone
)

ALTER TABLE salidas_productos_tmp ADD PRIMARY KEY (doc_tmp_id);

-------------TABLA sarchivo_docuE---------------------------------------------------------------------------------------------------------------------------------------------
CREATE TABLE archivo_docuE
(
   codigo_archivo serial NOT NULL,
   empresa_id character varying(2),
   tipo_doc_bodega_id character varying(4),
   documento_id integer NOT NULL,
   numero integer NOT NULL,
   prefijo character varying(4) NOT NULL,
   archivo_nombre character varying(255) ,
   archivo_peso character varying(50),
   archivo_tipo character varying(20),
   archivo_bytea bytea,
   usuario_registro integer NOT NULL,
   fecha_registro timestamp without time zone
)

ALTER TABLE archivo_docuE ADD PRIMARY KEY (codigo_archivo);
-------------TABLA VALIDACION_JEFETOMAF---------------------------------------------------------------------------------------------------------------------------------------------
CREATE TABLE vald_jefedoctmp
(
   id_vald_jefedoctmp serial NOT NULL,
   doc_tmp_id integer NOT NULL,
   sw_jefebodega character(1) default('0'),
   sw_jefecontroli character(1) default('0'),
   empresa_id character varying(2) NOT NULL,
   usuario_registro integer NOT NULL,
   fecha_registro timestamp without time zone
)

ALTER TABLE vald_jefedoctmp ADD PRIMARY KEY (id_vald_jefedoctmp,doc_tmp_id);