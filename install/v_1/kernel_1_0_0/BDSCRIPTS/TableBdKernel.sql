CREATE TABLE adodb_logsql (
    created timestamp without time zone NOT NULL,
    sql0 character varying(250) NOT NULL,
    sql1 text NOT NULL,
    params text NOT NULL,
    tracer text NOT NULL,
    timer numeric(16,6) NOT NULL
);

--comentarios adodb_logsql

COMMENT ON TABLE adodb_logsql IS 'Tabla donde se almacena los monitoreos de las consultas de la aplicacion';

CREATE TABLE centros_utilidad (
    empresa_id character(2) NOT NULL,
    centro_utilidad character(2) NOT NULL,
    descripcion character varying(40),
    ubicacion character varying(255),
    telefono character varying(255),
    text1 character varying(255),
    text2 character varying(255),
    tipo_pais_id character varying(4),
    tipo_dpto_id character varying(4),
    tipo_mpio_id character varying(4)
);

--comentarios centros_utilidad

COMMENT ON TABLE centros_utilidad IS 'Almacena la clasificacion de las diferentes sucursales de la empresa ';
COMMENT ON COLUMN centros_utilidad.empresa_id IS 'FK ide de la empresa';
COMMENT ON COLUMN centros_utilidad.centro_utilidad IS 'PK id del centro';
COMMENT ON COLUMN centros_utilidad.descripcion IS 'nombre del centro';
COMMENT ON COLUMN centros_utilidad.ubicacion IS 'Campo de la ubicacion o direccion del centro de utilidad, aplica para el manejo de sedes etc.';
COMMENT ON COLUMN centros_utilidad.telefono IS 'Telefonos y/o extenciones del cento de utilidad';
COMMENT ON COLUMN centros_utilidad.text1 IS 'Datos adicionales para uso de la implementacion';
COMMENT ON COLUMN centros_utilidad.text2 IS 'Datos adicionales para uso de la implementacion';

CREATE TABLE departamentos (
    empresa_id character(2) NOT NULL,
    centro_utilidad character(2) NOT NULL,
    unidad_funcional character varying(4) NOT NULL,
    departamento character varying(6) NOT NULL,
    descripcion character varying(40) NOT NULL,
    sw_internacion character(1) DEFAULT 0 NOT NULL,
    servicio character varying(2) NOT NULL,
    codigo_alterno character varying(20),
    ubicacion character varying(255),
    telefono character varying(255),
    text1 character varying(255),
    text2 character varying(255),
    sw_control_placas character(1) DEFAULT '0'::bpchar,
    formato_cumplimiento character(1) DEFAULT 0,
    sw_maneja_vitros character(1) NOT NULL,
    CONSTRAINT "$3" CHECK (((servicio)::text <> '0'::text))
);

--comentarios departamentos

COMMENT ON TABLE departamentos IS 'Se registran los diferentes departamentos de las instituciones';
COMMENT ON COLUMN departamentos.empresa_id IS 'Identifica la empresa en la que se encuentra el departamento';
COMMENT ON COLUMN departamentos.centro_utilidad IS 'Identifica el centro de utilidad en donde esta ubicada la empresa';
COMMENT ON COLUMN departamentos.unidad_funcional IS 'Identifica las unidades funcionales de la empresa';
COMMENT ON COLUMN departamentos.departamento IS 'Identifica los diferentes departamentos de la empresa';
COMMENT ON COLUMN departamentos.descripcion IS 'Nombre del departamento de la empresa';
COMMENT ON COLUMN departamentos.sw_internacion IS 'Identifica si el departamento tiene o no internacion 1=Tiene y 0=No tiene';
COMMENT ON COLUMN departamentos.servicio IS 'Identifica el tipo de servicio que presta el departamento';
COMMENT ON COLUMN departamentos.codigo_alterno IS 'Codigo que identifica valores alternos para la integracion';
COMMENT ON COLUMN departamentos.ubicacion IS 'Campo de la ubicacion o direccion del departamento';
COMMENT ON COLUMN departamentos.telefono IS 'Telefonos y/o extenciones del departamento';
COMMENT ON COLUMN departamentos.text1 IS 'Datos adicionales para uso de la implementacion';
COMMENT ON COLUMN departamentos.text2 IS 'Datos adicionales para uso de la implementacion';
COMMENT ON COLUMN departamentos.sw_control_placas IS 'Maneja el permiso de controlar las placas de radiologia 0:no aplica 1:Controla placas';
COMMENT ON COLUMN departamentos.formato_cumplimiento IS 'Formato en que se reiniciara el consecutivo del cumplimiento.0: por dia, 1: por mes, 2: por año, 3: indefinido';
COMMENT ON COLUMN departamentos.sw_maneja_vitros IS '0:No maneja equipo Vitros, 1:Maneja equipo vitros';


CREATE TABLE empresas (
    empresa_id character(2) NOT NULL,
    tipo_id_tercero character varying(3) NOT NULL,
    id character varying(20) NOT NULL,
    razon_social character varying(60) DEFAULT ''::character varying NOT NULL,
    representante_legal character varying(60) DEFAULT ''::character varying NOT NULL,
    codigo_sgsss character varying(10) DEFAULT ''::character varying NOT NULL,
    tipo_pais_id character varying(4) NOT NULL,
    tipo_dpto_id character varying(4) NOT NULL,
    tipo_mpio_id character varying(4) NOT NULL,
    direccion character varying(60) DEFAULT ''::character varying NOT NULL,
    telefonos character varying(30) DEFAULT ''::character varying NOT NULL,
    fax character varying(30) DEFAULT ''::character varying NOT NULL,
    codigo_postal character varying(30) DEFAULT ''::character varying NOT NULL,
    website character varying(60) DEFAULT ''::character varying NOT NULL,
    email character varying(40) DEFAULT ''::character varying NOT NULL,
    sw_activa character(1) DEFAULT 1 NOT NULL,
    sw_usuarios_multiempresa character(1) DEFAULT 0 NOT NULL,
    nivel_atencion character(2),
    sw_filtrar_nivel character varying(1)
);

--comentarios empresas

COMMENT ON TABLE empresas IS 'Catalogo de empresas creadas en el sistama (Multiempresas)';
COMMENT ON COLUMN empresas.empresa_id IS 'Codigo asignado a la empresas';
COMMENT ON COLUMN empresas.tipo_id_tercero IS 'Tipo de Identificacion FKEY tipo_id_terceros (tipo_id)';
COMMENT ON COLUMN empresas.id IS 'Numero de Identificacion';
COMMENT ON COLUMN empresas.razon_social IS 'Razon Social de la empresas';
COMMENT ON COLUMN empresas.representante_legal IS 'Nombre y Apellidos del Representante Legal de la empresas';
COMMENT ON COLUMN empresas.codigo_sgsss IS 'Codigo SGSSS de la empresas';
COMMENT ON COLUMN empresas.tipo_pais_id IS 'Codigo de Pais donde esta la empresas FKEY tipo_mpios(tipo_pais_id)';
COMMENT ON COLUMN empresas.tipo_dpto_id IS 'Codigo de Departamento/Estado donde esta la empresas FKEY tipo_mpios(tipo_dpto_id)';
COMMENT ON COLUMN empresas.tipo_mpio_id IS 'Codigo de Municipio/Ciudad donde esta la empresas FKEY tipo_mpios(tipo_mpio_id)';
COMMENT ON COLUMN empresas.direccion IS 'Direccion de la empresas';
COMMENT ON COLUMN empresas.telefonos IS 'Telefonos de la empresas';
COMMENT ON COLUMN empresas.fax IS 'Numero del Fax de la empresas';
COMMENT ON COLUMN empresas.codigo_postal IS 'Codigo Postal/Apartado Aereo de la empresas';
COMMENT ON COLUMN empresas.website IS 'Pagina Web de la empresas';
COMMENT ON COLUMN empresas.email IS 'Direccion de Correo Electronico de la empresas';
COMMENT ON COLUMN empresas.sw_activa IS 'Estado de la empresas: 1=Activa, 0=Bloqueada';
COMMENT ON COLUMN empresas.sw_filtrar_nivel IS '1 debe filtar 0 no filtra';

CREATE TABLE servicios (
    servicio character varying(2) NOT NULL,
    descripcion character varying(40) NOT NULL,
    sw_asistencial character(1) DEFAULT 1 NOT NULL,
    sw_prioridad character(1) DEFAULT '0'::bpchar NOT NULL,
    sw_cargo_multidpto character(1),
    ambito_rips_id integer
);

--comentarios servicios

COMMENT ON TABLE servicios IS 'Servicios que ofrece la institución.';
COMMENT ON COLUMN servicios.servicio IS 'PK de los servicios de atención médica.';
COMMENT ON COLUMN servicios.descripcion IS 'Descripción de los servicios de atención.';

CREATE TABLE system_caducidad_passwd (
    caducidad_id smallint NOT NULL,
    descripcion character varying(40) NOT NULL,
    indice_orden smallint DEFAULT 0 NOT NULL
);

--comentairos system_caducidad_passwd

COMMENT ON TABLE system_caducidad_passwd IS 'Tabla donde se almacena el tiempo de caducidad de las contraseñas de los usuarios de la aplicacion';

CREATE TABLE system_garbage_day (
    garbage_day_id serial NOT NULL,
    descripcion character varying(255) NOT NULL,
    "function" character varying(255) NOT NULL,
    hora character(5) DEFAULT '00:00'::bpchar NOT NULL,
    ultima_ejecucion date
);

--comentarios system_garbage_day

COMMENT ON TABLE system_garbage_day IS 'Tabla donde se parametriza los procesos que se deben ejecutar periodicamente';

-- secuencia system_garbage_day

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('system_garbage_day', 'garbage_day_id'), 1, false);

CREATE TABLE system_host (
    hostname character varying(255) DEFAULT ''::character varying NOT NULL,
    ubicacion character varying(255) DEFAULT ''::character varying NOT NULL,
    descripcion character varying(255) DEFAULT ''::character varying NOT NULL,
    styleframes smallint DEFAULT 2 NOT NULL,
    sw_bloqueo character(1) DEFAULT '0'::bpchar NOT NULL,
    ip character varying(20) NOT NULL,
    indice_automatico serial NOT NULL
);

--comentarios system_host

COMMENT ON TABLE system_host IS 'Tabla para almacenar informacion de los equipos que acceden al sistema';
COMMENT ON COLUMN system_host.hostname IS 'Nombre de Host del equipo que accede al Sistema';
COMMENT ON COLUMN system_host.ubicacion IS 'Ubicacion del equipo que accede al Sistema';
COMMENT ON COLUMN system_host.descripcion IS 'Descripcion del equipo que accede al Sistema';
COMMENT ON COLUMN system_host.styleframes IS 'Configuracion del tipo de FrameWork para el equipo que accede al Sistema 0=NoFrames 1=Frames 2=Predeterminado del Sistema';
COMMENT ON COLUMN system_host.sw_bloqueo IS 'Estado para bloquear direcciones ip 0=activa 1=Bloqueada';

--secuencia system_host

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('system_host', 'indice_automatico'), 4, true);

CREATE TABLE system_menus (
    menu_id serial NOT NULL,
    menu_nombre character varying(32) NOT NULL,
    descripcion character varying(256) DEFAULT ''::character varying NOT NULL,
    sw_system character(1) DEFAULT 0 NOT NULL
);

--comentarios system_menus

COMMENT ON TABLE system_menus IS 'Tabla de menus de la aplicacion';
COMMENT ON COLUMN system_menus.menu_id IS 'Id del menu PK';
COMMENT ON COLUMN system_menus.menu_nombre IS 'Nombre del Menu - UNIQUE';
COMMENT ON COLUMN system_menus.descripcion IS 'Descripcion del menu';

CREATE TABLE system_menus_items (
    menu_item_id serial NOT NULL,
    menu_id integer NOT NULL,
    titulo character varying(32),
    modulo_tipo character varying(20),
    modulo character varying(64),
    tipo character varying(64),
    metodo character varying(64),
    descripcion character varying(256) DEFAULT ''::character varying NOT NULL,
    indice_de_orden smallint DEFAULT 0 NOT NULL
);

--comentarios system_menus_items

COMMENT ON TABLE system_menus_items IS 'Tabla de los items de los menus de la aplicacion';

--secuencias system_menus_items

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('system_menus_items', 'menu_item_id'), 1, false);
SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('system_menus', 'menu_id'), 1, false);

CREATE TABLE system_modulos (
    modulo character varying(64) NOT NULL,
    modulo_tipo character varying(20) DEFAULT ''::character varying NOT NULL,
    descripcion character varying(255) DEFAULT ''::character varying NOT NULL,
    version_numero numeric(4,2) DEFAULT 1.00 NOT NULL,
    version_info character varying(255) DEFAULT ''::character varying NOT NULL,
    activo character(1) DEFAULT '1'::bpchar NOT NULL,
    sw_user character(1) DEFAULT '1'::bpchar NOT NULL,
    sw_admin character(1) DEFAULT '1'::bpchar NOT NULL
);

--comentarios system_modulos

COMMENT ON TABLE system_modulos IS 'Modulos del systema';
COMMENT ON COLUMN system_modulos.modulo IS 'Nombre del Modulo';
COMMENT ON COLUMN system_modulos.modulo_tipo IS 'Tipo de Modulo';
COMMENT ON COLUMN system_modulos.descripcion IS 'Descripcion del Modulo';
COMMENT ON COLUMN system_modulos.version_numero IS 'Numero de version del Modulo';
COMMENT ON COLUMN system_modulos.version_info IS 'Informacion de la version del Modulo autor,fecha,comentarios etc';
COMMENT ON COLUMN system_modulos.activo IS 'Disponibilidad del modulo 0=Inactivo 1=Activo';

CREATE TABLE system_modulos_default (
    ip_host character varying(20) DEFAULT ''::character varying NOT NULL,
    usuario_id integer NOT NULL,
    modulo character varying(64) NOT NULL,
    modulo_tipo character varying(20) DEFAULT 'app'::character varying NOT NULL,
    parametros character varying(255),
    activo character(1) DEFAULT '1'::bpchar NOT NULL
);

--comentarios system_modulos_default

COMMENT ON TABLE system_modulos_default IS 'Tabla donde se almacena los modulos por defecto para cada usuario';

CREATE TABLE system_modulos_variables (
    modulo character varying(64) NOT NULL,
    modulo_tipo character varying(20) NOT NULL,
    variable character varying(64) NOT NULL,
    valor text DEFAULT ''::text NOT NULL,
    descripcion text DEFAULT ''::text
);

--comentarios system_modulos_variables

COMMENT ON TABLE system_modulos_variables IS 'Tabla para almacenar variables para los Modulos';
COMMENT ON COLUMN system_modulos_variables.modulo IS 'Nombre del Modulo FKEY system_modulos (modulo)';
COMMENT ON COLUMN system_modulos_variables.modulo_tipo IS 'Tipo de Modulo FKEY system_modulos (modulo_tipo)';
COMMENT ON COLUMN system_modulos_variables.variable IS 'Nombre de la Variable de Modulo';
COMMENT ON COLUMN system_modulos_variables.valor IS 'Valor de la Variable';
COMMENT ON COLUMN system_modulos_variables.descripcion IS 'Descripcion de la variable del modulo';

CREATE TABLE system_session (
    session_id character varying(32) DEFAULT ''::character varying NOT NULL,
    ip_address character varying(20) DEFAULT ''::character varying NOT NULL,
    inicio_session numeric(11,0) DEFAULT 0 NOT NULL,
    ultimo_acceso_session numeric(11,0) DEFAULT 0 NOT NULL,
    usuario_id integer DEFAULT 0 NOT NULL,
    variables_de_session text DEFAULT ''::text NOT NULL
);

--comentarios system_session

COMMENT ON TABLE system_session IS 'Tabla para el control de Sesiones';
COMMENT ON COLUMN system_session.session_id IS 'Id de la Sesion';
COMMENT ON COLUMN system_session.ip_address IS 'IP desde donde se inicio la sesion';
COMMENT ON COLUMN system_session.inicio_session IS 'time() en que se inicio la sesion';
COMMENT ON COLUMN system_session.ultimo_acceso_session IS 'time() del ultimo acceso a la sesion';
COMMENT ON COLUMN system_session.usuario_id IS 'UID del usuario del sistema 0=Usuario no Loggedin FKEY system_users (uid)';
COMMENT ON COLUMN system_session.variables_de_session IS 'Campo para almacenar las variables de sesion';

CREATE TABLE system_tipos_modulos (
    modulo_tipo character varying(20) NOT NULL,
    descripcion character varying(255) DEFAULT ''::character varying NOT NULL
);

--comentarios system_tipos_modulos

COMMENT ON TABLE system_tipos_modulos IS 'Tipos de Modulos del systema';
COMMENT ON COLUMN system_tipos_modulos.modulo_tipo IS 'Nombre del Tipo de Modulo';
COMMENT ON COLUMN system_tipos_modulos.descripcion IS 'Descripcion del Tipo de Modulo';

CREATE TABLE system_usuarios (
    usuario_id serial NOT NULL,
    usuario character varying(25) DEFAULT ''::character varying NOT NULL,
    nombre character varying(60) DEFAULT ''::character varying NOT NULL,
    descripcion character varying(255) DEFAULT ''::character varying NOT NULL,
    passwd character varying(40) DEFAULT ''::character varying NOT NULL,
    sw_admin character(1) DEFAULT '0'::bpchar NOT NULL,
    activo character(1) DEFAULT '1'::bpchar NOT NULL,
    fecha_caducidad_contrasena timestamp without time zone,
    fecha_caducidad_cuenta timestamp without time zone,
    caducidad_contrasena smallint DEFAULT 0,
    codigo_alterno character varying(30)
);

--comentarios system_usuarios

COMMENT ON TABLE system_usuarios IS 'Usuarios del Sistema';
COMMENT ON COLUMN system_usuarios.usuario_id IS 'UID del Usuario del sistema';
COMMENT ON COLUMN system_usuarios.usuario IS 'Login del Usuario';
COMMENT ON COLUMN system_usuarios.nombre IS 'Nombre Completo del Usuario';
COMMENT ON COLUMN system_usuarios.descripcion IS 'Descripcion del Usuario';
COMMENT ON COLUMN system_usuarios.passwd IS 'Contraseï¿œ del Usuario';
COMMENT ON COLUMN system_usuarios.sw_admin IS 'Define si el usuario es administrador del sistema o usuario normal 1=Admin 0=Normal';
COMMENT ON COLUMN system_usuarios.activo IS 'Estado del Usuario 1=Activo 0=Bloqueado';
COMMENT ON COLUMN system_usuarios.fecha_caducidad_contrasena IS 'Fecha en la que la contraseï¿œ debe ser cambiada';
COMMENT ON COLUMN system_usuarios.fecha_caducidad_cuenta IS 'Fecha en la que la cuenta caduca';
COMMENT ON COLUMN system_usuarios.caducidad_contrasena IS 'identifica si la cuenta caduca o no 1=Caduca 0=No caduca';
COMMENT ON COLUMN system_usuarios.codigo_alterno IS 'codigo que sirve para la interconexion con otros sistemas';

CREATE TABLE system_usuarios_administradores (
    usuario_id integer NOT NULL,
    empresa_id character(2) NOT NULL
);

--comentarios system_usuarios_administradores

COMMENT ON TABLE system_usuarios_administradores IS 'Tabla que indica los administradores de la aplicacion';

CREATE TABLE system_usuarios_departamentos (
    usuario_id integer NOT NULL,
    departamento character varying(6) NOT NULL
);

--comentarios system_usuarios_departamentos

COMMENT ON TABLE system_usuarios_departamentos IS 'Tabla de departamentos disponibles por usuario';
COMMENT ON COLUMN system_usuarios_departamentos.usuario_id IS 'Usuario FK system_usuarios system_usuarios_empresas (usuario_id,empresa_id) ';
COMMENT ON COLUMN system_usuarios_departamentos.departamento IS 'Departamento FK departamentos (departamento) ';

CREATE TABLE system_usuarios_empresas (
    usuario_id integer NOT NULL,
    empresa_id character(2) NOT NULL,
    sw_activo character(1) DEFAULT 1 NOT NULL
);

--comentarios system_usuarios_empresas

COMMENT ON TABLE system_usuarios_empresas IS 'Tabla de empresas disponibles por usuario';
COMMENT ON COLUMN system_usuarios_empresas.usuario_id IS 'Usuario FK system_usuarios (usuario_id)';
COMMENT ON COLUMN system_usuarios_empresas.empresa_id IS 'Empresa FK empresas (empresa_id)';

CREATE TABLE system_usuarios_menus (
    menu_id integer NOT NULL,
    usuario_id integer NOT NULL
);

--comentarios system_usuarios_menus

COMMENT ON TABLE system_usuarios_menus IS 'Tabla donde se relaciona los menus de la aplicacion con el usuario';

--secuencia system_usuarios_menus 

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('system_usuarios', 'usuario_id'), 1, false);

CREATE TABLE system_usuarios_vars (
    usuario_id integer NOT NULL,
    variable character varying(64) NOT NULL,
    valor text DEFAULT ''::text NOT NULL
);

--comentarios system_usuarios_vars

COMMENT ON TABLE system_usuarios_vars IS 'Variables de los Usuarios del Sistema';
COMMENT ON COLUMN system_usuarios_vars.usuario_id IS 'UID del Usuario del sistema FK';
COMMENT ON COLUMN system_usuarios_vars.variable IS 'Nombre de la variable del Usuario del sistema';
COMMENT ON COLUMN system_usuarios_vars.valor IS 'Valor de la variable';

CREATE TABLE tipo_dptos (
    tipo_dpto_id character varying(4) NOT NULL,
    tipo_pais_id character varying(4) NOT NULL,
    departamento character varying(30) DEFAULT ''::character varying NOT NULL
);

--comentarios tipo_dptos

COMMENT ON TABLE tipo_dptos IS 'Catalogo de Departamentos/Estados/Provincias';
COMMENT ON COLUMN tipo_dptos.tipo_dpto_id IS 'Codigo del Dpto/Estado/Provincia';
COMMENT ON COLUMN tipo_dptos.tipo_pais_id IS 'Codigo de Pais FKEY tipo_pais (tipo_pais_id))';
COMMENT ON COLUMN tipo_dptos.departamento IS 'Nombre del Dpto/Estado/Provincia';

CREATE TABLE tipo_id_terceros (
    tipo_id_tercero character varying(3) NOT NULL,
    descripcion character varying(30) DEFAULT ''::character varying NOT NULL,
    indice_de_orden smallint DEFAULT 0 NOT NULL,
    sw_personas_naturales character(1)
);

--comentarios tipo_id_terceros

COMMENT ON TABLE tipo_id_terceros IS 'Catalogo de Tipos de Identificacion de Terceros';
COMMENT ON COLUMN tipo_id_terceros.tipo_id_tercero IS 'Tipo de Id del Tercero';
COMMENT ON COLUMN tipo_id_terceros.descripcion IS 'Descripcion del Tipo de Id';
COMMENT ON COLUMN tipo_id_terceros.indice_de_orden IS 'Indice utilizado para ordenar la consulta de esta tabla (Util en la interface de usuario)';

CREATE TABLE tipo_mpios (
    tipo_pais_id character varying(4) NOT NULL,
    tipo_dpto_id character varying(4) NOT NULL,
    tipo_mpio_id character varying(4) NOT NULL,
    municipio character varying(30) DEFAULT ''::character varying NOT NULL
);

--comentarios tipo_mpios

COMMENT ON TABLE tipo_mpios IS 'Catalogo de Municipios/Ciudades';
COMMENT ON COLUMN tipo_mpios.tipo_pais_id IS 'Codigo de Pais FKEY tipo_dptos (tipo_pais_id)';
COMMENT ON COLUMN tipo_mpios.tipo_dpto_id IS 'Codigo de Dpto FKEY tipo_dptos (tipo_dpto_id)';
COMMENT ON COLUMN tipo_mpios.tipo_mpio_id IS 'Codigo del Municipio/Ciudad';
COMMENT ON COLUMN tipo_mpios.municipio IS 'Nombre del Municipio/Ciudad';


CREATE TABLE tipo_pais (
    tipo_pais_id character varying(4) NOT NULL,
    bloqueado_edicion character(1) DEFAULT 0 NOT NULL,
    pais character varying(60)
);

--comentarios tipo_pais

COMMENT ON TABLE tipo_pais IS 'Catalogo de Paises';
COMMENT ON COLUMN tipo_pais.tipo_pais_id IS 'Codigo de Pais';
COMMENT ON COLUMN tipo_pais.bloqueado_edicion IS 'Bloqueado para Editarlo (Agregar Estados/Departamentos y ciudades) 0SE=Sin Bloqueo, 1=Bloqueado';
COMMENT ON COLUMN tipo_pais.pais IS 'Descripción del pais';

CREATE TABLE unidades_funcionales (
    empresa_id character(2) NOT NULL,
    centro_utilidad character(2) NOT NULL,
    unidad_funcional character varying(4) NOT NULL,
    descripcion character varying(40),
    ubicacion character varying(255),
    telefono character varying(255),
    text1 character varying(255),
    text2 character varying(255)
);

--comentarios unidades_funcionales

COMMENT ON TABLE unidades_funcionales IS 'Se encuntran las diferentes unidades funcionales de las empresas inscritas.';
COMMENT ON COLUMN unidades_funcionales.empresa_id IS 'Empresa donde se encuentra la unidad funcional';
COMMENT ON COLUMN unidades_funcionales.centro_utilidad IS 'Centro de utilidad donde se encuntra la unidad funcional';
COMMENT ON COLUMN unidades_funcionales.unidad_funcional IS 'Codigo de Unidad funcional';
COMMENT ON COLUMN unidades_funcionales.descripcion IS 'Descripcion de unidad funcional';
COMMENT ON COLUMN unidades_funcionales.ubicacion IS 'Campo de la ubicacion o direccion de la unidad funcional';
COMMENT ON COLUMN unidades_funcionales.telefono IS 'Telefonos y/o extenciones de la unidad funcional';
COMMENT ON COLUMN unidades_funcionales.text1 IS 'Datos adicionales para uso de la implementacion';
COMMENT ON COLUMN unidades_funcionales.text2 IS 'Datos adicionales para uso de la implementacion';
