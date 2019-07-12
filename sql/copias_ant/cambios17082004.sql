

--	quitar tildes de tablas para que funcionen los buscadores.
	update ocupaciones set ocupacion_descripcion=replace(ocupacion_descripcion,'á','a');
	update ocupaciones set ocupacion_descripcion=replace(ocupacion_descripcion,'é','e');
	update ocupaciones set ocupacion_descripcion=replace(ocupacion_descripcion,'í','i');
	update ocupaciones set ocupacion_descripcion=replace(ocupacion_descripcion,'ó','o');
	update ocupaciones set ocupacion_descripcion=replace(ocupacion_descripcion,'ú','u');
	update ocupaciones set ocupacion_descripcion=replace(ocupacion_descripcion,'Á','A');
	update ocupaciones set ocupacion_descripcion=replace(ocupacion_descripcion,'É','E');
	update ocupaciones set ocupacion_descripcion=replace(ocupacion_descripcion,'Í','I');
	update ocupaciones set ocupacion_descripcion=replace(ocupacion_descripcion,'Ó','O');
	update ocupaciones set ocupacion_descripcion=replace(ocupacion_descripcion,'Ú','U');
    update ocupaciones set ocupacion_descripcion=upper(ocupacion_descripcion);

--	quitar tildes de tablas para que funcionen los buscadores.	
	update  tarifarios_detalle set descripcion=replace(descripcion,'á','a');
	update  tarifarios_detalle set descripcion=replace(descripcion,'é','e');
	update  tarifarios_detalle set descripcion=replace(descripcion,'í','i');
	update  tarifarios_detalle set descripcion=replace(descripcion,'ó','o');
	update  tarifarios_detalle set descripcion=replace(descripcion,'ú','u');
	update  tarifarios_detalle set descripcion=replace(descripcion,'Á','A');
	update  tarifarios_detalle set descripcion=replace(descripcion,'É','E');
	update  tarifarios_detalle set descripcion=replace(descripcion,'Í','I');
	update  tarifarios_detalle set descripcion=replace(descripcion,'Ó','O');
	update  tarifarios_detalle set descripcion=replace(descripcion,'Ú','U');
    update  tarifarios_detalle set descripcion=upper(descripcion);	


-- SEPARAR MOD DE USUARIOS CON EL ADMINISTRATIVO
UPDATE "system_menus_items" SET "menu_item_id"='29', "menu_id"='15', "titulo"='ADMINISTRACION USUARIOS', "modulo_tipo"='system', "modulo"='Usuarios', "tipo"='admin', "metodo"='main', "descripcion"='"Modulo de administracion de los usuarios operativos"', "indice_de_orden"='2' WHERE "menu_item_id"='29'

UPDATE "system_menus_items" SET "menu_item_id"='41', "menu_id"='23', "titulo"='CONFIGURACION PERMISO MODULO', "modulo_tipo"='system', "modulo"='User_modulo_dpto', "tipo"='admin', "metodo"='main', "descripcion"='este modulo permite asignar modulos y departamentos segun olos permisos', "indice_de_orden"='0' WHERE "menu_item_id"='41'

UPDATE "system_menus_items" SET "menu_item_id"='36', "menu_id"='20', "titulo"='ADMINISTRACION EMPRESA', "modulo_tipo"='system', "modulo"='AdminEmpresa', "tipo"='admin', "metodo"='main', "descripcion"='Modulo de administracion de la emrpesa', "indice_de_orden"='0' WHERE "menu_item_id"='36'


--este borra el item del menu_id 15 q pertenece a los usuarios, 'usuarios'
-- ya que con esto separamos totalmente el modulo de usuarios con el administrativo
DELETE FROM system_menus_items 	WHERE menu_item_id=26 AND  menu_id=15;


INSERT INTO userpermisos_admin VALUES ('userpermisos_central', '{departamento,usuario_id}', '{departamentos,system_usuarios}', '{descripcion,usuario-nombre}', '{caso1,caso2}');
INSERT INTO userpermisos_admin VALUES ('estaciones_enfermeria_usuarios', '{estacion_id,usuario_id}', '{estaciones_enfermeria,system_usuarios}', '{descripcion,usuario-nombre}', '{caso7,caso2}');
INSERT INTO userpermisos_admin VALUES ('estaciones_enfermeria_admin_usuarios', '{estacion_id,usuario_id}', '{estaciones_enfermeria,system_usuarios}', '{descripcion,usuario-nombre}', '{caso7,caso2}');
INSERT INTO userpermisos_admin VALUES ('userpermisos_os_atencion', '{departamento,usuario_id}', '{departamentos,system_usuarios}', '{descripcion,usuario-nombre}', '{caso7,caso2}');
