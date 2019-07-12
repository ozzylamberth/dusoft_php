CREATE TABLE userpermisos_Informacion_Buzon_DocDespacho
(
    
    empresa_id character(2) NOT NULL,
    centro_utilidad 	 character(2) NOT NULL,
    usuario_id integer NOT NULL
);

ALTER TABLE userpermisos_Informacion_Buzon_DocDespacho
ADD CONSTRAINT userpermisos_Informacion_Buzon_DocDespacho_pkey PRIMARY KEY (empresa_id,centro_utilidad, usuario_id);

ALTER TABLE ONLY userpermisos_Informacion_Buzon_DocDespacho
ADD CONSTRAINT userpermisos_Informacion_Buzon_DocDespacho_empresa_id_fkey FOREIGN KEY (empresa_id) REFERENCES empresas(empresa_id);

ALTER TABLE ONLY userpermisos_Informacion_Buzon_DocDespacho
ADD CONSTRAINT  userpermisos_Informacion_Buzon_DocDespacho_usuario_id_fkey FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id);

COMMENT ON TABLE userpermisos_Informacion_Buzon_DocDespacho IS 'Tabla de permiso para  que los usuarios puedan acceder al modulo de Buzon ';
COMMENT ON COLUMN userpermisos_Informacion_Buzon_DocDespacho.empresa_id IS '(FK ) de la tabla Empresas';
COMMENT ON COLUMN userpermisos_Informacion_Buzon_DocDespacho.centro_utilidad IS 'centro de utilidad de la Empresa';
COMMENT ON COLUMN userpermisos_Informacion_Buzon_DocDespacho.usuario_id IS '(FK) Identifica al usuario que realizo el documento';
GRANT ALL ON TABLE userpermisos_Informacion_Buzon_DocDespacho TO siis;

CREATE TABLE usuarios_consulta_docdespachos
(
  	consulta_docdesp_id  SERIAL NOT NULL,
    empresa_id          CHARACTER VARYING(2) NOT NULL,
    prefijo 	        CHARACTER VARYING(4) NOT NULL,
    numero              INTEGER NOT NULL,
    farmacia_id         CHARACTER VARYING(2) NOT NULL,
    usuario_id          INTEGER NOT NULL, 	
	fecha_consulta      TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW()
   
);
ALTER TABLE usuarios_consulta_docdespachos ADD PRIMARY KEY(consulta_docdesp_id);
ALTER TABLE usuarios_consulta_docdespachos ADD FOREIGN KEY (empresa_id, prefijo, numero) REFERENCES inv_bodegas_movimiento_despachos_farmacias(empresa_id, prefijo, numero)
 ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE usuarios_consulta_docdespachos ADD FOREIGN KEY (farmacia_id) REFERENCES empresas(empresa_id) 
ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE usuarios_consulta_docdespachos ADD FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) 
ON UPDATE CASCADE ON DELETE RESTRICT;



