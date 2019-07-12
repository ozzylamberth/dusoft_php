
CREATE TABLE userpermisos_InfoProductosDisponiblePpl
(
    empresa_id character(2) NOT NULL,
    usuario_id integer NOT NULL
   );
COMMENT ON TABLE userpermisos_InfoProductosDisponiblePpl  IS 'Permisos para que los usuarios puedan consultar productos disponibles y/o pendientes ';

COMMENT ON COLUMN userpermisos_InfoProductosDisponiblePpl.empresa_id IS 'Id de la  Empresa';
COMMENT ON COLUMN userpermisos_InfoProductosDisponiblePpl.usuario_id IS 'Id  del Usuario';

ALTER TABLE userpermisos_InfoProductosDisponiblePpl
ADD CONSTRAINT userpermisos_InfoProductosDisponiblePpl_pkey PRIMARY KEY (empresa_id, usuario_id);

ALTER TABLE ONLY  userpermisos_InfoProductosDisponiblePpl
ADD CONSTRAINT userpermisos_InfoProductosDisponiblePpl_empresa_id_fkey FOREIGN KEY (empresa_id) REFERENCES empresas(empresa_id);

ALTER TABLE ONLY  userpermisos_InfoProductosDisponiblePpl
ADD CONSTRAINT  userpermisos_InfoProductosDisponiblePpl_usuario_id_fkey FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id);

