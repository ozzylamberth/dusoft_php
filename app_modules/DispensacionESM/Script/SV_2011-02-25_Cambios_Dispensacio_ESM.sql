
/* TABLA PARA PERMITIR VER EL MENU DE ESM/*/


CREATE TABLE esm_menu_dispensacion
( 
  usuario_id                  integer not null,
  menu_formulacion_externa    CHARACTER(1) NOT NULL DEFAULT '1',
  menu_dispensacion_esm       CHARACTER(1) NOT NULL DEFAULT '1',
  sw_activo                   CHARACTER(1) NOT NULL DEFAULT '1'
);

ALTER TABLE esm_menu_dispensacion ADD PRIMARY KEY(usuario_id);
ALTER TABLE esm_menu_dispensacion ADD FOREIGN KEY(usuario_id)
REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;

COMMENT ON TABLE esm_menu_dispensacion IS 'Tabla donde se registra el permiso sobre el modulo de digitalizacion de formulas';
COMMENT ON COLUMN esm_menu_dispensacion.menu_formulacion_externa IS '1=> Muestra el menu 0=> Inactiva el menu (FORMULACION EXTERNA)';
COMMENT ON COLUMN esm_menu_dispensacion.menu_dispensacion_esm IS '1=> Muestra el menu 0=> Inactiva el menu (DISPENSACION)';
COMMENT ON COLUMN esm_menu_dispensacion.usuario_id IS '(PK - FK) Identificador del usuario';
COMMENT ON COLUMN esm_menu_dispensacion.sw_activo IS 'Identifica 1=>Activo 0=>inactivo';
GRANT ALL ON TABLE esm_menu_dispensacion TO siis;


