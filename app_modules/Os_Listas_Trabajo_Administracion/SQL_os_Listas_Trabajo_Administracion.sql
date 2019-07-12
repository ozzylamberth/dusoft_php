CREATE TABLE userpermisos_os_listatra_administradores_apoyod 
(
    	usuario_id integer NOT NULL,
    	departamento character varying(6) NOT NULL,
	sw_estado character(1)
); 

COMMENT ON TABLE userpermisos_os_listatra_administradores_apoyod IS 'usuarios administradores de las listas para cada departamento';

COMMENT ON COLUMN userpermisos_os_listatra_administradores_apoyod.sw_estado IS ' 0:activo, 1:inactivo';

ALTER TABLE ONLY userpermisos_os_listatra_administradores_apoyod
	ADD CONSTRAINT userpermisos_os_listatra_administradores_apoyod_pkey PRIMARY KEY (usuario_id, departamento);

ALTER TABLE ONLY userpermisos_os_listatra_administradores_apoyod
	ADD CONSTRAINT "$1" FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;	
    
ALTER TABLE ONLY userpermisos_os_listatra_administradores_apoyod
	ADD CONSTRAINT "$2" FOREIGN KEY (departamento) REFERENCES departamentos(departamento) ON UPDATE CASCADE ON DELETE RESTRICT;
	

INSERT INTO userpermisos_os_listatra_administradores_apoyod VALUES (65,'020601','0');
INSERT INTO userpermisos_os_listatra_administradores_apoyod VALUES (2,'010601', '0');
INSERT INTO userpermisos_os_listatra_administradores_apoyod VALUES (2,'010602', '0');
--INSERT INTO userpermisos_os_listatra_administradores_apoyod VALUES (2,'010201', '0');
--INSERT INTO userpermisos_os_listatra_administradores_apoyod VALUES (2,'010301', '0');
--INSERT INTO userpermisos_os_listatra_administradores_apoyod VALUES (2,'010401', '0');
--INSERT INTO userpermisos_os_listatra_administradores_apoyod VALUES (2,'010402', '0');				
									
			
									
								


