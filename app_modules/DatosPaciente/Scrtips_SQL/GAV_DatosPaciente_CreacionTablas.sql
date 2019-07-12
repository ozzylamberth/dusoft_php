

CREATE TABLE buscador_localizacion(
	tipo_pais_id character varying(4) NOT NULL,
	equiv_municipio text,
	equiv_departamento text,
	equiv_comuna text
);

ALTER TABLE buscador_localizacion ADD PRIMARY KEY (tipo_pais_id);
ALTER TABLE buscador_localizacion ADD FOREIGN KEY (tipo_pais_id) REFERENCES tipo_pais(tipo_pais_id);

COMMENT ON COLUMN buscador_localizacion.tipo_pais_id IS 'Codigo del Pais';
COMMENT ON COLUMN buscador_localizacion.equiv_municipio IS 'Equivalencia del Municipio';
COMMENT ON COLUMN buscador_localizacion.equiv_departamento IS 'Equivalencia del departamento';
COMMENT ON COLUMN buscador_localizacion.equiv_comuna IS 'Equivalencia de la Comuna';


/****Tabla para Repartos****/
CREATE TABLE tipo_repartos(
  reparto_id serial NOT NULL,
  nombre text NOT NULL, 
  pais_id  NOT NULL,
  provincia_id character varying(4) NOT NULL,
  canton_id character varying(4) NOT NULL,
  parroquia_id character varying(4) NOT NULL,
  direccion character varying(100) NOT NULL,
  telefono integer NOT NULL
);

/* Esto no es necesario
ALTER TABLE tipo_repartos ADD FOREIGN KEY (pais_id) REFERENCES tipo_pais(tipo_pais_id);
ALTER TABLE tipo_repartos ADD FOREIGN KEY (pais_id, provincia_id) REFERENCES tipo_dptos(tipo_pais_id, tipo_dpto_id);
ALTER TABLE tipo_repartos ADD FOREIGN KEY (pais_id, provincia_id, canton_id) REFERENCES tipo_mpios(tipo_pais_id, tipo_dpto_id, tipo_mpio_id);*/

ALTER TABLE tipo_repartos ADD FOREIGN KEY (pais_id, provincia_id, canton_id, parroquia_id) REFERENCES tipo_comunas(tipo_pais_id, tipo_dpto_id, tipo_mpio_id, tipo_comuna_id);

ALTER TABLE tipo_repartos ADD PRIMARY KEY (reparto_id);

COMMENT ON COLUMN tipo_repartos.reparto_id IS 'Codigo del Reparto';
COMMENT ON COLUMN tipo_repartos.nombre IS 'Nombre del Reparto';
COMMENT ON COLUMN tipo_repartos.pais_id IS 'Pais del Reparto';
COMMENT ON COLUMN tipo_repartos.provincia_id IS 'Provincia del Reparto';
COMMENT ON COLUMN tipo_repartos.canton_id IS 'Canton del Reparto';
COMMENT ON COLUMN tipo_repartos.parroquia_id IS 'Parroquia del Reparto';
COMMENT ON COLUMN tipo_repartos.direccion IS 'Direccion del Reparto';
COMMENT ON COLUMN tipo_repartos.telefono IS 'Telefono del Reparto';

DROP TABLE tipo_repartos



/***Tabla para Fuerzas***/
CREATE TABLE tipo_fuerzas(
  fuerza_id serial NOT NULL,  
  descripcion text NOT NULL
);

ALTER TABLE tipo_fuerzas ADD PRIMARY KEY (fuerza_id);

COMMENT ON COLUMN tipo_fuerzas.fuerza_id IS 'Codigo de Fuerza';
COMMENT ON COLUMN tipo_fuerzas.descripcion IS 'Descripcion de Fuerza';


/****Tabla para Grados****/
CREATE TABLE tipo_grados(
  grado_id serial NOT NULL,
  descripcion text NOT NULL
);

ALTER TABLE tipo_grados ADD PRIMARY KEY (grado_id);

COMMENT ON COLUMN tipo_grados.grado_id IS 'Codigo de Grado';
COMMENT ON COLUMN tipo_grados.descripcion IS 'Descripcion de Grado';


/****Tabla para Religion****/
CREATE TABLE tipo_religiones(
  religion_id serial NOT NULL,
  descripcion text NOT NULL
);

ALTER TABLE tipo_religiones ADD PRIMARY KEY (religion_id);

COMMENT ON COLUMN tipo_religiones.religion_id IS 'Codigo de Religion';
COMMENT ON COLUMN tipo_religiones.descripcion IS 'Descripcion de Religion';


/****Tabla para Instruccion****/
CREATE TABLE tipo_instruccion(
  instruccion_id serial NOT NULL,
  descripcion text NOT NULL
);

ALTER TABLE tipo_instruccion ADD PRIMARY KEY (instruccion_id);

COMMENT ON COLUMN tipo_instruccion.instruccion_id IS 'Codigo de Instruccion';
COMMENT ON COLUMN tipo_instruccion.descripcion IS 'Descripcion de Instruccion';


/****Tabla para Nacionalidad****/
CREATE TABLE tipo_nacionalidad(
  nacionalidad_id serial NOT NULL,
  descripcion text NOT NULL         
);

ALTER TABLE tipo_nacionalidad ADD PRIMARY KEY (nacionalidad_id);

ALTER TABLE tipo_nacionalidad ALTER COLUMN nacionalidad_id TYPE character varying(4);

ALTER TABLE tipo_nacionalidad ADD FOREIGN KEY (nacionalidad_id) REFERENCES tipo_pais(tipo_pais_id);


COMMENT ON COLUMN tipo_nacionalidad.nacionalidad_id IS 'Codigo de Nacionalidad';
COMMENT ON COLUMN tipo_nacionalidad.descripcion IS 'Descripcion de Nacionalidad';


/****Tabla de Clasificaciones Financieros****/
CREATE TABLE clasificaciones_financieros(
  clasifi_finaci_id integer NOT NULL,
  descripcion text NOT NULL
);

ALTER TABLE clasificaciones_financieros ADD PRIMARY KEY (clasifi_finaci_id);

COMMENT ON COLUMN clasificaciones_financieros.clasifi_finaci_id IS 'Codigo de la Clasificicacion Financiera';
COMMENT ON COLUMN clasificaciones_financieros.descripcion IS 'Descripcion de la Clasificicacion Financiera';


/****Tabla de Equivalencia Clasificacion Financiera****/
CREATE TABLE equivalencias_clasificacion_finaciera(
  clasifi_finaci_id integer NOT NULL,
  estado_fuerza_id character varying(2) NOT NULL,
  grado_id integer 
);

ALTER TABLE equivalencias_clasificacion_finaciera ADD FOREIGN KEY (clasifi_finaci_id) REFERENCES clasificaciones_financieros(clasifi_finaci_id);
ALTER TABLE equivalencias_clasificacion_finaciera ADD FOREIGN KEY (estado_fuerza_id) REFERENCES estado_fuerza(estado_fuerza_id);
ALTER TABLE equivalencias_clasificacion_finaciera ADD FOREIGN KEY (grado_id) REFERENCES tipo_grados(grado_id);

ALTER TABLE equivalencias_clasificacion_finaciera ADD PRIMARY KEY (clasifi_finaci_id, estado_fuerza_id, grado_id);


ALTER TABLE equivalencias_clasificacion_finaciera RENAME fuerza_id TO estado_fuerza_id;
ALTER TABLE equivalencias_clasificacion_finaciera ALTER COLUMN estado_fuerza_id TYPE character varying(2);


DROP TABLE equivalencias_clasificacion_finaciera



/****Tabla Estado Fuerza****/
CREATE TABLE estado_fuerza(
  estado_fuerza_id integer NOT NULL,
  categoria character varying(20) NOT NULL          
);

ALTER TABLE estado_fuerza ADD PRIMARY KEY (estado_fuerza_id);
ALTER TABLE estado_fuerza ALTER COLUMN estado_fuerza_id TYPE character varying(2);

COMMENT ON COLUMN estado_fuerza.estado_fuerza_id IS 'Codigo del Estado Fuerza';
COMMENT ON COLUMN estado_fuerza.categoria IS 'Categoria del Estado Fuerza';


/**Tabla Paciente ISSFA**/
CREATE TABLE paciente_issfa(
	paciente_id character varying(32), 
	tipo_id_paciente character varying(3), 
	cod_int_issfa integer, 
	cod_issfa integer, 
	fuerza_id integer, 
	estado_fuerza_id character varying(2), 
	grado_id integer, 
	clasifi_finaci_id integer, 
	nacionalidad_id character varying(4), 
	reparto_id integer, 
	instruccion_id integer 
);

ALTER TABLE paciente_issfa ADD FOREIGN KEY (paciente_id, tipo_id_paciente) REFERENCES pacientes(paciente_id, tipo_id_paciente);

ALTER TABLE paciente_issfa ADD FOREIGN KEY (nacionalidad_id) REFERENCES   tipo_nacionalidad(nacionalidad_id);

ALTER TABLE paciente_issfa ADD FOREIGN KEY (reparto_id) REFERENCES tipo_repartos(reparto_id);

ALTER TABLE paciente_issfa ADD FOREIGN KEY (instruccion_id) REFERENCES   tipo_instruccion(instruccion_id);

ALTER TABLE paciente_issfa ADD FOREIGN KEY (clasifi_finaci_id, estado_fuerza_id, grado_id) REFERENCES   equivalencias_clasificacion_finaciera(clasifi_finaci_id, estado_fuerza_id, grado_id);

COMMENT ON COLUMN paciente_issfa.paciente_id IS 'Codigo del Paciente';
COMMENT ON COLUMN paciente_issfa.tipo_id_paciente IS 'Tido de Identificacion del Paciente';
COMMENT ON COLUMN paciente_issfa.cod_int_issfa IS 'Codigo de Interno ISSFA';
COMMENT ON COLUMN paciente_issfa.cod_issfa IS 'Codigo de ISSFA';
COMMENT ON COLUMN paciente_issfa.fuerza_id IS 'Codigo de Tipo de Fuerza';
COMMENT ON COLUMN paciente_issfa.estado_fuerza_id IS 'Codigo de Estado de Fuerza';
COMMENT ON COLUMN paciente_issfa.grado_id IS 'Codigo del Grado';
COMMENT ON COLUMN paciente_issfa.clasifi_finaci_id IS 'Codigo de Clasificacion financiera ';
COMMENT ON COLUMN paciente_issfa.nacionalidad_id IS 'Codigo de la Nacionalidad';
COMMENT ON COLUMN paciente_issfa.reparto_id IS 'Codigo del Reparto';
COMMENT ON COLUMN paciente_issfa.instruccion_id IS 'Codigo de la Instruccion';


/**Tabla del responsable Familiar**/
CREATE TABLE responsable_familiar(
	responsable_familiar_id serial NOT NULL, 
	paciente_id character varying(32) NOT NULL, 
	tipo_id_paciente character varying(3) NOT NULL, 
	no_identi_id character varying(10),
	pri_nombre character varying(20), 
	seg_nombre character varying(20), 
	pri_apellido character varying(20), 
	seg_apellido character varying(20), 
	reparto_id integer, 
	telefono integer, 
	direccion character varying(100), 
	pais_id character varying(4), 
	provincia_id character varying(4), 
	canton_id character varying(4), 
	parroquia_id character varying(4)  
);

ALTER TABLE responsable_familiar ADD PRIMARY KEY (responsable_familiar_id);

ALTER TABLE responsable_familiar ADD FOREIGN KEY (reparto_id) REFERENCES tipo_repartos(reparto_id);

ALTER TABLE responsable_familiar ADD FOREIGN KEY (pais_id, provincia_id, canton_id, parroquia_id)  REFERENCES tipo_comunas(tipo_pais_id, tipo_dpto_id, tipo_mpio_id, tipo_comuna_id);

ALTER TABLE responsable_familiar ADD COLUMN tipo_identi_id character varying(3);

ALTER TABLE responsable_familiar ADD COLUMN ingreso_id integer;

ALTER TABLE responsable_familiar ADD FOREIGN KEY (ingreso_id) REFERENCES ingresos(ingreso);


COMMENT ON COLUMN responsable_familiar.responsable_familiar_id IS 'Codigo del Familiar o Responsable';
COMMENT ON COLUMN responsable_familiar.paciente_id IS 'Codigo del Paciente';
COMMENT ON COLUMN responsable_familiar.tipo_id_paciente IS 'Tido de Identificacion del Paciente';
COMMENT ON COLUMN responsable_familiar.no_identi_id IS 'No de Identificacion del Familiar o Responsable';
COMMENT ON COLUMN responsable_familiar.pri_nombre IS 'Primer Nombre del Familiar o Responsable';
COMMENT ON COLUMN responsable_familiar.seg_nombre IS 'Segundo Nombre del Familiar o Responsable';
COMMENT ON COLUMN responsable_familiar.pri_apellido IS 'Primer Apellido del Familiar o Responsable';
COMMENT ON COLUMN responsable_familiar.seg_apellido IS 'Segundo Apellido del Familiar o Responsable';
COMMENT ON COLUMN responsable_familiar.reparto_id IS 'Codigo del Reparto';
COMMENT ON COLUMN responsable_familiar.telefono IS 'Telefono del Familiar o Responsable';
COMMENT ON COLUMN responsable_familiar.direccion IS 'Direccion del Familiar o Responsable';
COMMENT ON COLUMN responsable_familiar.pais_id IS 'Codigo de la Provincia';
COMMENT ON COLUMN responsable_familiar.provincia_id IS 'Codigo de la Provincia';
COMMENT ON COLUMN responsable_familiar.canton_id IS 'Codigo del Canton';
COMMENT ON COLUMN responsable_familiar.parroquia_id IS 'Codigo de la Parroquia';
COMMENT ON COLUMN responsable_familiar.tipo_identi_id IS 'Tipo de Identificacion del Familiar o Responsable';

ALTER TABLE responsable_familiar RENAME COLUMN telefono TO telefonoFam;
ALTER TABLE responsable_familiar RENAME COLUMN direccion TO direccionFam;
ALTER TABLE responsable_familiar RENAME COLUMN reparto_id TO repartofam_id;

ALTER TABLE pacientes RENAME sw_indigente TO tipo_paciente;



