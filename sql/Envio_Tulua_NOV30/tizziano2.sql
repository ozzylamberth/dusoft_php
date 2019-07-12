
ALTER TABLE "ingresos" ADD COLUMN "fecha_cierre" timestamp without time zone;


INSERT INTO system_modulos VALUES ('AtencionUrgencias', 'app', 'Modulo de Atencion de Urgencias', 1.00, '', '1', '1', '0');

UPDATE "system_menus_items" SET "menu_item_id"='17', "menu_id"='19', "titulo"='Atencion Urgencias', "modulo_tipo"='app', "modulo"='AtencionUrgencias', "tipo"='user', "metodo"='main', "descripcion"='', "indice_de_orden"='0' WHERE "menu_item_id"='17';
