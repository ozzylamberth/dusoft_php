CREATE TABLE userpermisos_afiliacion_clientes_farmacia
(
  orden_id integer NOT NULL, 
  empresa_id character(2) NOT NULL, 
  usuario_id integer NOT NULL
 );
 
ALTER TABLE userpermisos_afiliacion_clientes_farmacia ADD PRIMARY KEY(orden_id);
ALTER TABLE userpermisos_afiliacion_clientes_farmacia ADD FOREIGN KEY(empresa_id)
REFERENCES empresas(empresa_id) ON UPDATE CASCADE ON DELETE RESTRICT;
   
ALTER TABLE userpermisos_afiliacion_clientes_farmacia ADD FOREIGN KEY(usuario_id)
REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
   
COMMENT ON TABLE userpermisos_afiliacion_clientes_farmacia IS 'Tabla donde se registran los usuarios que tienen permiso para hacer afiliación de clientes de farmacias';
COMMENT ON COLUMN userpermisos_afiliacion_clientes_farmacia.orden_id IS '(PK) Identificador de la tabla';
COMMENT ON COLUMN userpermisos_afiliacion_clientes_farmacia.empresa_id IS '(FK) Identificación de la empresa';
COMMENT ON COLUMN userpermisos_afiliacion_clientes_farmacia.usuario_id IS '(FK) Identificación del usuario';