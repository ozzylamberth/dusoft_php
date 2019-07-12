--/*************** INFORMATIVO DE DEVOLUCION POR ROTACION DE  FARMACIA **********************----

CREATE TABLE Userpermisos_Devolutivo_RotacionFarmacia(
				farmacia_id            CHARACTER(2) not null,
				usuario_id             INTEGER NOT NULL,
				sw_activo              character(1) DEFAULT '1'::bpchar NOT NULL
       );

ALTER TABLE Userpermisos_Devolutivo_RotacionFarmacia ADD PRIMARY KEY (farmacia_id,usuario_id);
ALTER TABLE Userpermisos_Devolutivo_RotacionFarmacia ADD FOREIGN KEY (farmacia_id) REFERENCES empresas(empresa_id)ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE Userpermisos_Devolutivo_RotacionFarmacia ADD FOREIGN KEY (usuario_id)REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;


COMMENT ON COLUMN Userpermisos_Devolutivo_RotacionFarmacia.farmacia_id IS 'Id Farmacia (PK) ';
COMMENT ON COLUMN Userpermisos_Devolutivo_RotacionFarmacia.usuario_id IS 'Id Usuario (PK)(FK)';
COMMENT ON COLUMN Userpermisos_Devolutivo_RotacionFarmacia.sw_activo IS '1=>Activo,0=>Inactivo  ';
