
/************** EMPRESAS QUE SON FARMACIAS*****/

CREATE TABLE userpermisos_ParametrizacionFarmacia
(
    
    empresa_id character(2) NOT NULL,
    usuario_id integer NOT NULL
);

ALTER TABLE userpermisos_ParametrizacionFarmacia
ADD CONSTRAINT userpermisos_ParametrizacionFarmacia_pkey PRIMARY KEY (empresa_id,usuario_id);

ALTER TABLE ONLY userpermisos_ParametrizacionFarmacia
ADD CONSTRAINT userpermisos_ParametrizacionFarmacia_empresa_id_fkey FOREIGN KEY (empresa_id) REFERENCES empresas(empresa_id);

ALTER TABLE ONLY userpermisos_ParametrizacionFarmacia
ADD CONSTRAINT  userpermisos_ParametrizacionFarmacia_usuario_id_fkey FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id);

COMMENT ON TABLE userpermisos_ParametrizacionFarmacia IS 'Tabla de permiso para  que los usuarios puedan acceder a la parametrizacion de las farmacias ';
COMMENT ON COLUMN userpermisos_ParametrizacionFarmacia.empresa_id IS '(FK ) de la tabla Empresas';
COMMENT ON COLUMN userpermisos_ParametrizacionFarmacia.usuario_id IS '(FK) Id del usuario';
GRANT ALL ON TABLE userpermisos_ParametrizacionFarmacia TO siis;

