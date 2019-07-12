CREATE TABLE public.userpermisos_busqueda_agenda
(
  departamento varchar(6) NOT NULL,
  usuario_id int4 NOT NULL,
  CONSTRAINT userpermisos_busqueda_agenda_pkey PRIMARY KEY (departamento, usuario_id),
  CONSTRAINT "$1" FOREIGN KEY (departamento) REFERENCES public.departamentos (departamento) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT "$2" FOREIGN KEY (usuario_id) REFERENCES public.system_usuarios (usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT
) WITH OIDS;
GRANT ALL ON TABLE public.userpermisos_busqueda_agenda TO admin WITH GRANT OPTION;
GRANT SELECT ON TABLE public.userpermisos_busqueda_agenda TO siis_consulta;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE public.userpermisos_busqueda_agenda TO siis; 


INSERT INTO system_modulos VALUES ('AgendaBusqueda', 'app', 'BUSQUEDA DE AGENDA', 1.00, '', '1', '1', '1');

INSERT INTO system_menus VALUES (49, 'BUSQUEDA DE AGENDA', 'BUSQUEDA DE AGENDA', '0');

INSERT INTO system_menus_items VALUES (78, 49, 'BUSQUEDA DE AGENDA', 'app', 'AgendaBusqueda', 'user', 'main', 'BUSQUEDA DE AGENDA', 0);