ALTER TABLE estaciones_enfermeria_usuarios ADD COLUMN sw_eliminar_vistok character(1) default('0');
COMMENT ON COLUMN estaciones_enfermeria_usuarios.sw_eliminar_vistok IS 'Permiso para los usuarios que pueden eliminar el visto bueno, SI= 1, NO=0 '; 
