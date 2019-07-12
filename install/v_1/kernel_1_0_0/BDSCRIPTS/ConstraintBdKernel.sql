
-- llave primaria centros_utilidad

ALTER TABLE ONLY centros_utilidad ADD CONSTRAINT centros_utilidad_pkey PRIMARY KEY (empresa_id, centro_utilidad);

-- llave primaria departamentos
    
ALTER TABLE ONLY departamentos ADD CONSTRAINT departamentos_pkey PRIMARY KEY (departamento);

-- llave primaria empresas

ALTER TABLE ONLY empresas ADD CONSTRAINT empresas_pkey PRIMARY KEY (empresa_id);

-- llave primaria servicios

ALTER TABLE ONLY servicios ADD CONSTRAINT servicios_pkey PRIMARY KEY (servicio);

-- llave primaria system_host

ALTER TABLE ONLY system_host ADD CONSTRAINT system_host_pkey PRIMARY KEY (indice_automatico);

-- llave primaria system_menus_items

ALTER TABLE ONLY system_menus_items ADD CONSTRAINT system_menus_items_pkey PRIMARY KEY (menu_item_id);

-- llave unica system_menus

ALTER TABLE ONLY system_menus ADD CONSTRAINT system_menus_menu_nombre_key UNIQUE (menu_nombre);

-- llave primaria system_menus_items

ALTER TABLE ONLY system_menus ADD CONSTRAINT system_menus_pkey PRIMARY KEY (menu_id);

-- llave primaria system_modulos_default

ALTER TABLE ONLY system_modulos_default ADD CONSTRAINT system_modulos_default_pkey PRIMARY KEY (ip_host, usuario_id);

-- llave primaria system_modulos

ALTER TABLE ONLY system_modulos ADD CONSTRAINT system_modulos_pkey PRIMARY KEY (modulo, modulo_tipo);

-- llave primaria system_modulos_variables

ALTER TABLE ONLY system_modulos_variables ADD CONSTRAINT system_modulos_variables_pkey PRIMARY KEY (modulo, modulo_tipo, variable);

-- llave primaria system_session

ALTER TABLE ONLY system_session ADD CONSTRAINT system_session_pkey PRIMARY KEY (session_id);

-- llave primaria system_tipos_modulos

ALTER TABLE ONLY system_tipos_modulos ADD CONSTRAINT system_tipos_modulos_pkey PRIMARY KEY (modulo_tipo);

-- llave primaria system_usuarios_administradores

ALTER TABLE ONLY system_usuarios_administradores ADD CONSTRAINT system_usuarios_administradores_pkey PRIMARY KEY (usuario_id, empresa_id);

-- llave primaria system_usuarios_departamentos

ALTER TABLE ONLY system_usuarios_departamentos ADD CONSTRAINT system_usuarios_departamentos_pkey PRIMARY KEY (usuario_id, departamento);

-- llave primaria system_usuarios_empresas

ALTER TABLE ONLY system_usuarios_empresas ADD CONSTRAINT system_usuarios_empresas_pkey PRIMARY KEY (usuario_id, empresa_id);

-- llave primaria system_usuarios_menus

ALTER TABLE ONLY system_usuarios_menus ADD CONSTRAINT system_usuarios_menus_pkey PRIMARY KEY (menu_id, usuario_id);

-- llave primaria system_usuarios

ALTER TABLE ONLY system_usuarios ADD CONSTRAINT system_usuarios_pkey PRIMARY KEY (usuario_id);

-- llave unica system_usuarios

ALTER TABLE ONLY system_usuarios ADD CONSTRAINT system_usuarios_usuario_key UNIQUE (usuario);

-- llave primaria system_usuarios_vars

ALTER TABLE ONLY system_usuarios_vars ADD CONSTRAINT system_usuarios_vars_pkey PRIMARY KEY (usuario_id, variable);

-- llave primaria tipo_dptos

ALTER TABLE ONLY tipo_dptos ADD CONSTRAINT tipo_dptos_pkey PRIMARY KEY (tipo_pais_id, tipo_dpto_id);

-- llave primaria tipo_id_terceros

ALTER TABLE ONLY tipo_id_terceros ADD CONSTRAINT tipo_id_terceros_pkey PRIMARY KEY (tipo_id_tercero);

-- llave primaria tipo_mpios

ALTER TABLE ONLY tipo_mpios ADD CONSTRAINT tipo_mpios_pkey PRIMARY KEY (tipo_pais_id, tipo_dpto_id, tipo_mpio_id);

-- llave primaria tipo_pais

ALTER TABLE ONLY tipo_pais ADD CONSTRAINT tipo_pais_pkey PRIMARY KEY (tipo_pais_id);

-- llave primaria unidades_funcionales

ALTER TABLE ONLY unidades_funcionales ADD CONSTRAINT unidades_funcionales_pkey PRIMARY KEY (empresa_id, centro_utilidad, unidad_funcional);

-- llave foranea system_modulos

ALTER TABLE ONLY system_modulos ADD CONSTRAINT "$1" FOREIGN KEY (modulo_tipo) REFERENCES system_tipos_modulos(modulo_tipo) ON UPDATE CASCADE ON DELETE RESTRICT;

-- llave foranea system_session
    
ALTER TABLE ONLY system_session ADD CONSTRAINT "$1" FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE CASCADE;

-- llave foranea tipo_dptos

ALTER TABLE ONLY tipo_dptos ADD CONSTRAINT "$1" FOREIGN KEY (tipo_pais_id) REFERENCES tipo_pais(tipo_pais_id) ON UPDATE CASCADE ON DELETE RESTRICT;

-- llave foranea tipo_mpios

ALTER TABLE ONLY tipo_mpios ADD CONSTRAINT "$1" FOREIGN KEY (tipo_pais_id, tipo_dpto_id) REFERENCES tipo_dptos(tipo_pais_id, tipo_dpto_id) ON UPDATE CASCADE ON DELETE RESTRICT;

-- llave foranea empresas

ALTER TABLE ONLY empresas ADD CONSTRAINT "$1" FOREIGN KEY (tipo_id_tercero) REFERENCES tipo_id_terceros(tipo_id_tercero) ON UPDATE CASCADE ON DELETE RESTRICT;

-- llave foranea centros_utilidad

ALTER TABLE ONLY centros_utilidad
    ADD CONSTRAINT "$1" FOREIGN KEY (empresa_id) REFERENCES empresas(empresa_id) ON UPDATE CASCADE ON DELETE RESTRICT;

-- llave foranea unidades_funcionales

ALTER TABLE ONLY unidades_funcionales
    ADD CONSTRAINT "$1" FOREIGN KEY (empresa_id, centro_utilidad) REFERENCES centros_utilidad(empresa_id, centro_utilidad) ON UPDATE CASCADE ON DELETE RESTRICT;

-- llave foranea departamentos

ALTER TABLE ONLY departamentos ADD CONSTRAINT "$1" FOREIGN KEY (empresa_id, centro_utilidad, unidad_funcional) REFERENCES unidades_funcionales(empresa_id, centro_utilidad, unidad_funcional) ON UPDATE CASCADE ON DELETE RESTRICT;

-- llave foranea system_modulos_default

ALTER TABLE ONLY system_modulos_default ADD CONSTRAINT "$1" FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE CASCADE;

-- llave foranea system_modulos_vars

ALTER TABLE ONLY system_usuarios_vars ADD CONSTRAINT "$1" FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE CASCADE;

-- llave foranea system_usuarios_empresas

ALTER TABLE ONLY system_usuarios_empresas ADD CONSTRAINT "$1" FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;

-- llave foranea system_usuarios_departamentos

ALTER TABLE ONLY system_usuarios_departamentos ADD CONSTRAINT "$1" FOREIGN KEY (departamento) REFERENCES departamentos(departamento) ON UPDATE CASCADE ON DELETE CASCADE;

-- llave foranea system_modulos_variables

ALTER TABLE ONLY system_modulos_variables ADD CONSTRAINT "$1" FOREIGN KEY (modulo, modulo_tipo) REFERENCES system_modulos(modulo, modulo_tipo) ON UPDATE CASCADE ON DELETE CASCADE;

-- llave foranea system_usuarios_menus

ALTER TABLE ONLY system_usuarios_menus ADD CONSTRAINT "$1" FOREIGN KEY (menu_id) REFERENCES system_menus(menu_id) ON UPDATE CASCADE ON DELETE CASCADE;

-- llave foranea system_menus_items

ALTER TABLE ONLY system_menus_items ADD CONSTRAINT "$1" FOREIGN KEY (menu_id) REFERENCES system_menus(menu_id) ON UPDATE CASCADE ON DELETE CASCADE;

-- llave foranea system_usuarios_administradores

ALTER TABLE ONLY system_usuarios_administradores ADD CONSTRAINT "$1" FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE CASCADE;

-- llave foranea 2 empresas

ALTER TABLE ONLY empresas ADD CONSTRAINT "$2" FOREIGN KEY (tipo_pais_id, tipo_dpto_id, tipo_mpio_id) REFERENCES tipo_mpios(tipo_pais_id, tipo_dpto_id, tipo_mpio_id) ON UPDATE CASCADE ON DELETE RESTRICT;

-- llave foranea 2 departamentos

ALTER TABLE ONLY departamentos ADD CONSTRAINT "$2" FOREIGN KEY (servicio) REFERENCES servicios(servicio) ON UPDATE CASCADE ON DELETE RESTRICT;

-- llave foranea 2 system_modulos_default

ALTER TABLE ONLY system_modulos_default ADD CONSTRAINT "$2" FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE CASCADE;

-- llave foranea 2 system_usuarios_empresas

ALTER TABLE ONLY system_usuarios_empresas ADD CONSTRAINT "$2" FOREIGN KEY (empresa_id) REFERENCES empresas(empresa_id) ON UPDATE CASCADE ON DELETE RESTRICT;

-- llave foranea 2 system_usuarios_departamentos

ALTER TABLE ONLY system_usuarios_departamentos ADD CONSTRAINT "$2" FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;

-- llave foranea 2 system_usuarios_menus

ALTER TABLE ONLY system_usuarios_menus ADD CONSTRAINT "$2" FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE CASCADE;

-- llave foranea 2 system_menus_items

ALTER TABLE ONLY system_menus_items ADD CONSTRAINT "$2" FOREIGN KEY (modulo, modulo_tipo) REFERENCES system_modulos(modulo, modulo_tipo) ON UPDATE CASCADE ON DELETE CASCADE;

-- llave foranea 2 system_usuarios_administradores

ALTER TABLE ONLY system_usuarios_administradores ADD CONSTRAINT "$2" FOREIGN KEY (empresa_id) REFERENCES empresas(empresa_id) ON UPDATE CASCADE ON DELETE CASCADE;

-- llave foranea 3 system_modulos_default

ALTER TABLE ONLY system_modulos_default ADD CONSTRAINT "$3" FOREIGN KEY (modulo, modulo_tipo) REFERENCES system_modulos(modulo, modulo_tipo) ON UPDATE CASCADE ON DELETE CASCADE;

-- llave foranea 3 centros_utilidad

ALTER TABLE ONLY centros_utilidad ADD CONSTRAINT centros_utilidad_tipo_pais_id_fkey FOREIGN KEY (tipo_pais_id, tipo_dpto_id, tipo_mpio_id) REFERENCES tipo_mpios(tipo_pais_id, tipo_dpto_id, tipo_mpio_id) ON UPDATE CASCADE ON DELETE RESTRICT;