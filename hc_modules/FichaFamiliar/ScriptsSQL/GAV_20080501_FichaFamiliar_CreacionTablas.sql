

SELECT NEXTVAL('miembro_familiar_familiar_id_seq') AS sq


/****Tabla para miembro_familiar****/
CREATE TABLE miembro_familiar(
  familiar_id serial NOT NULL,
  paciente_id character varying(32) NOT NULL,
  primer_apellido character varying(30) NOT NULL, 
  segundo_apellido character varying(30),
  primer_nombre character varying(20) NOT NULL,
  segundo_nombre character varying(20),
  parentesco character varying(32) NOT NULL,
  fecha_nacim date ,
  sexo character(1) ,
  escolaridad integer , 
  esquema_vacunas character(1) ,
  salud_vocal character(1) ,
  rie_enf_disca text,  
  hist_clinica character varying(50),
  no_identi_fam character varying(32) 
);


ALTER TABLE miembro_familiar ALTER COLUMN fecha_nacim DROP NOT NULL;
ALTER TABLE miembro_familiar ALTER COLUMN sexo DROP NOT NULL;
ALTER TABLE miembro_familiar ALTER COLUMN escolaridad DROP NOT NULL;
ALTER TABLE miembro_familiar ALTER COLUMN esquema_vacunas DROP NOT NULL;
ALTER TABLE miembro_familiar ALTER COLUMN salud_vocal DROP NOT NULL;
ALTER TABLE miembro_familiar ALTER COLUMN rie_enf_disca DROP NOT NULL;
ALTER TABLE miembro_familiar ALTER COLUMN hist_clinica DROP NOT NULL;
ALTER TABLE miembro_familiar ALTER COLUMN no_identi_fam DROP NOT NULL;

ALTER TABLE miembro_familiar ADD COLUMN embarazada character(1);
ALTER TABLE miembro_familiar ADD COLUMN ocupacion character varying(32);
ALTER TABLE miembro_familiar ADD COLUMN edad_fallece integer;
ALTER TABLE miembro_familiar ADD COLUMN causa character varying(32);
ALTER TABLE miembro_familiar ADD COLUMN difunto character(1);
ALTER TABLE miembro_familiar RENAME salud_vocal TO salud_bucal;
ALTER TABLE miembro_familiar ALTER COLUMN ocupacion TYPE character varying(4);
ALTER TABLE miembro_familiar ADD COLUMN difunto character(1);
ALTER TABLE miembro_familiar ADD COLUMN tipo_identi_fam character varying(3);
ALTER TABLE miembro_familiar ALTER COLUMN parentesco TYPE character varying(2);

ALTER TABLE miembro_familiar ADD FOREIGN KEY (parentesco) REFERENCES tipos_parentescos(tipo_parentesco_id);
ALTER TABLE miembro_familiar ADD FOREIGN KEY (escolaridad) REFERENCES tipo_instruccion(instruccion_id);
ALTER TABLE miembro_familiar ADD FOREIGN KEY (ocupacion) REFERENCES ocupaciones(ocupacion_id);


COMMENT ON COLUMN miembro_familiar.familiar_id IS 'Codigo del Familiar';
COMMENT ON COLUMN miembro_familiar.paciente_id IS 'Codigo del Paciente del cual es pariente el familiar';
COMMENT ON COLUMN miembro_familiar.primer_apellido IS 'Primer Apellido del Familiar';
COMMENT ON COLUMN miembro_familiar.segundo_apellido IS 'Segundo Apellido del Familiar';
COMMENT ON COLUMN miembro_familiar.primer_nombre IS 'Primer Nombre del Familiar';
COMMENT ON COLUMN miembro_familiar.segundo_nombre IS 'Segundo Nombre del Familiar';
COMMENT ON COLUMN miembro_familiar.parentesco IS 'Parentesco con el Familiar';
COMMENT ON COLUMN miembro_familiar.fecha_nacim IS 'Fecha de Nacimiento del Familiar';
COMMENT ON COLUMN miembro_familiar.sexo IS 'Sexo del Familiar';
COMMENT ON COLUMN miembro_familiar.escolaridad IS 'Escolaridad el Familiar';
COMMENT ON COLUMN miembro_familiar.esquema_vacunas IS 'Esquema de Vacunas del Familiar';
COMMENT ON COLUMN miembro_familiar.salud_bucal IS 'Salud Bucal del Familiar';
COMMENT ON COLUMN miembro_familiar.rie_enf_disca IS 'Sexo del Familiar';
COMMENT ON COLUMN miembro_familiar.hist_clinica IS 'Historia Clinica del Familiar';
COMMENT ON COLUMN miembro_familiar.no_identi_fam IS 'Documento de Identificacion del Familiar';
COMMENT ON COLUMN miembro_familiar.ocupacion IS 'Ocupacion del Familiar';
COMMENT ON COLUMN miembro_familiar.embarazada IS 'Si la familiar esta emarazada';
COMMENT ON COLUMN miembro_familiar.edad_fallece IS 'Edad del Fallecimiento';
COMMENT ON COLUMN miembro_familiar.causa IS 'Causa del Fallecimiento';
COMMENT ON COLUMN miembro_familiar.tipo_identi_fam IS 'Tipo identificacion del Familiar';


ALTER TABLE miembro_familiar ADD PRIMARY KEY (familiar_id);


CREATE TABLE miem_fam_embarazadas(
	embarazada_id serial NOT NULL,
	familiar_id integer NOT NULL,
	fecha_ult_menstruacion date,
	fecha_prob_parto date,
	semanas_gesta integer,
	pri_dosis date,
	seg_dosis date,
	refuerzo_dosis date,
	gestas integer,
	partos integer,
	abortos integer,
	cesareas integer,
	ante_pato_obstre text
);

COMMENT ON COLUMN miem_fam_embarazadas.familiar_id IS 'Codigo del Familiar';
COMMENT ON COLUMN miem_fam_embarazadas.fecha_ult_menstruacion IS 'Fecha Ultima menstruacion';
COMMENT ON COLUMN miem_fam_embarazadas.fecha_prob_parto IS 'Fecha Probable del Parto';
COMMENT ON COLUMN miem_fam_embarazadas.semanas_gesta IS 'Semanas de Gestacion';
COMMENT ON COLUMN miem_fam_embarazadas.pri_dosis IS 'Primera Dosis de Vacunacion';
COMMENT ON COLUMN miem_fam_embarazadas.seg_dosis IS 'Segunda Dosis de Vacunacion';
COMMENT ON COLUMN miem_fam_embarazadas.refuerzo_dosis IS 'Refuerzo de Dosis de Vacunacion';
COMMENT ON COLUMN miem_fam_embarazadas.gestas IS 'Cantidad de Gestas';
COMMENT ON COLUMN miem_fam_embarazadas.partos IS 'Cantidad de Partos';
COMMENT ON COLUMN miem_fam_embarazadas.abortos IS 'Cantidad de Abortos';
COMMENT ON COLUMN miem_fam_embarazadas.cesareas IS 'Cantidad de Cesareas';
COMMENT ON COLUMN miem_fam_embarazadas.ante_pato_obstre IS 'Antecedentes Patologicos Obstreticos';

ALTER TABLE miem_fam_embarazadas ADD PRIMARY KEY (embarazada_id);
ALTER TABLE miem_fam_embarazadas ADD FOREIGN KEY (familiar_id) REFERENCES miembro_familiar (familiar_id);


CREATE TABLE ficha_familar(
	num_ficha_fam serial NOT NULL,
	paciente_id character varying(32) NOT NULL,
	cod_respon integer NOT NULL,
	fecha_llenado date NOT NULL,
	num_carpeta integer
);

ALTER TABLE ficha_familar ADD PRIMARY KEY (num_ficha_fam);
ALTER TABLE ficha_familar ADD FOREIGN KEY (cod_respon) REFERENCES system_usuarios(usuario_id);
ALTER TABLE ficha_familar ADD COLUMN latitud integer;
ALTER TABLE ficha_familar ADD COLUMN longitud integer;
ALTER TABLE ficha_familar ADD COLUMN altitud integer;

ALTER TABLE ficha_familar ADD COLUMN comunidad character varying(3);
ALTER TABLE ficha_familar ADD COLUMN grup_cultu character varying(3);
ALTER TABLE ficha_familar ADD COLUMN nom_comp_jefe_fam character varying(32);
ALTER TABLE ficha_familar ADD COLUMN num_familia integer;

ALTER TABLE ficha_familar ADD FOREIGN KEY (comunidad) REFERENCES tipo_comunidad(comunidad_id);
ALTER TABLE ficha_familar ADD FOREIGN KEY (grup_cultu) REFERENCES grupo_cultural(grup_cult_id);

COMMENT ON COLUMN ficha_familar.num_ficha_fam IS 'Numero de la Ficha Familiar';
COMMENT ON COLUMN ficha_familar.paciente_id IS 'Codigo del Paciente';
COMMENT ON COLUMN ficha_familar.cod_respon IS 'Codigo del Responsable de la Ficha Familiar';
COMMENT ON COLUMN ficha_familar.fecha_llenado IS 'Fecha de Llenado de la Ficha Familiar';
COMMENT ON COLUMN ficha_familar.num_carpeta IS 'Numero de Carpeta';
COMMENT ON COLUMN ficha_familar.latitud IS 'Georeferencia por Latitud';
COMMENT ON COLUMN ficha_familar.longitud IS 'Georeferencia por Longitud';
COMMENT ON COLUMN ficha_familar.altitud IS 'Georeferencia por Altitud';
COMMENT ON COLUMN ficha_familar.comunidad IS 'Codigo del Tipo de Comunidad';
COMMENT ON COLUMN ficha_familar.grup_cultu IS 'Codigo del Tipo de Grupo Cultural';
COMMENT ON COLUMN ficha_familar.nom_comp_jefe_fam IS 'Nombre Completo del Jefe Familiar';
COMMENT ON COLUMN ficha_familar.num_familia IS 'Numero de miembros en la familia';

CREATE TABLE tipo_comunidad(
	comunidad_id character varying(3) NOT NULL,
	descripcion character varying(32) NOT NULL
);

COMMENT ON COLUMN tipo_comunidad.comunidad_id IS 'Codigo del Tipo de Comunidad';
COMMENT ON COLUMN tipo_comunidad.descripcion IS 'Descripcion de la Comunidad';
ALTER TABLE tipo_comunidad ADD PRIMARY KEY (comunidad_id);

CREATE TABLE grupo_cultural(
	grup_cult_id character varying(3) NOT NULL,
	descripcion character varying(32) NOT NULL
);

COMMENT ON COLUMN grupo_cultural.grup_cult_id IS 'Codigo del Grupo Cultural';
COMMENT ON COLUMN grupo_cultural.descripcion IS 'Descripcion del Grupo cultural';
ALTER TABLE grupo_cultural ADD PRIMARY KEY (grup_cult_id);



