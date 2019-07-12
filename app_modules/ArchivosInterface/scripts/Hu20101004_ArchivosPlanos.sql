CREATE SCHEMA interfaces_planes;
GRANT ALL ON SCHEMA interfaces_planes TO siis;

CREATE TABLE userpermisos_subir_planos
(
  empresa_id CHARACTER(2) NOT NULL,
  usuario_id INTEGER NOT NULL
);

ALTER TABLE userpermisos_subir_planos ADD PRIMARY KEY(empresa_id,usuario_id);
ALTER TABLE userpermisos_subir_planos ADD FOREIGN KEY (empresa_id) 
REFERENCES empresas(empresa_id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE userpermisos_subir_planos ADD FOREIGN KEY (usuario_id) 
REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE CASCADE;

COMMENT ON TABLE userpermisos_subir_planos IS 'Tabla donde se registra el usuario que tiene permisos para usar el modulo de ArchivosCapitados';
COMMENT ON COLUMN userpermisos_subir_planos.empresa_id IS '(PK - FK) Identificador de la empresa';
COMMENT ON COLUMN userpermisos_subir_planos.usuario_id IS '(PK - FK) Identificador del usuario';

GRANT ALL ON TABLE userpermisos_subir_planos TO siis;

CREATE TABLE archivos_cargados
(
  archivo_cargado_id SERIAL NOT NULL,
  usuario_id INTEGER NOT NULL,
  fecha TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT NOW(), 
  descripcion TEXT NOT NULL
);

ALTER TABLE archivos_cargados ADD PRIMARY KEY(archivo_cargado_id);
ALTER TABLE archivos_cargados ADD FOREIGN KEY(usuario_id)
REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;

COMMENT ON TABLE archivos_cargados IS 'Tabla donde se registra la audtoria de los archivos que se han subido a las tablas de capitacion';
COMMENT ON COLUMN archivos_cargados.archivo_cargado_id IS '(PK) Identificador de la tabla';
COMMENT ON COLUMN archivos_cargados.usuario_id IS '(FK) Identificador del usuario';
COMMENT ON COLUMN archivos_cargados.fecha IS 'Fecha de registro';
COMMENT ON COLUMN archivos_cargados.descripcion IS 'Nombre del archivo cargado al sistema';

GRANT ALL ON TABLE archivos_cargados TO siis;
GRANT ALL ON SEQUENCE archivos_cargados_archivo_cargado_id_seq TO siis;


CREATE TABLE interfaces_planes.equivalencia_tipos_identificacion
(
  equivalencia_id SERIAL NOT NULL,
  plan_id INTEGER ,
  afiliado_tipo_id CHARACTER VARYING(3),
  afiliado_tipo_id_archivo CHARACTER VARYING(255)
);

ALTER TABLE interfaces_planes.equivalencia_tipos_identificacion ADD PRIMARY KEY(equivalencia_id);
ALTER TABLE interfaces_planes.equivalencia_tipos_identificacion ADD FOREIGN KEY (afiliado_tipo_id) 
REFERENCES tipos_id_pacientes(tipo_id_paciente) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE interfaces_planes.equivalencia_tipos_identificacion ADD FOREIGN KEY (plan_id) 
REFERENCES planes(plan_id) ON UPDATE CASCADE ON DELETE CASCADE;

COMMENT ON TABLE interfaces_planes.equivalencia_tipos_identificacion IS 'Tabla donde se hacen las equivalencoias de los tipos de identificacion contenidos en los archivos';
COMMENT ON COLUMN interfaces_planes.equivalencia_tipos_identificacion.equivalencia_id IS '(PK) Lllave asignada a la tabla';
COMMENT ON COLUMN interfaces_planes.equivalencia_tipos_identificacion.plan_id IS '(FK) Identificador del plan';
COMMENT ON COLUMN interfaces_planes.equivalencia_tipos_identificacion.afiliado_tipo_id IS '(FK) Tipo de identificacion del afiliado';
COMMENT ON COLUMN interfaces_planes.equivalencia_tipos_identificacion.afiliado_tipo_id_archivo IS 'Tipo de identificacion del afiliado del archivo';

GRANT ALL ON TABLE interfaces_planes.equivalencia_tipos_identificacion TO siis;

INSERT INTO interfaces_planes.equivalencia_tipos_identificacion VALUES (DEFAULT, NULL, 'TI', '3');
INSERT INTO interfaces_planes.equivalencia_tipos_identificacion VALUES (DEFAULT, NULL, 'NU', 'N');
INSERT INTO interfaces_planes.equivalencia_tipos_identificacion VALUES (DEFAULT, NULL, 'CC', '4');
INSERT INTO interfaces_planes.equivalencia_tipos_identificacion VALUES (DEFAULT, NULL, 'RC', '2');
INSERT INTO interfaces_planes.equivalencia_tipos_identificacion VALUES (DEFAULT, NULL, 'CE', '5');
INSERT INTO interfaces_planes.equivalencia_tipos_identificacion VALUES (DEFAULT, NULL, 'PA', 'P');

CREATE TABLE interfaces_planes.equivalencia_tipos_afiliados
(
  equivalencia_tipo_afiliado_id SERIAL NOT NULL,
  plan_id INTEGER,
  tipo_afiliado_id CHARACTER VARYING(2),
  tipo_afiliado_archivo CHARACTER VARYING(255)
);

ALTER TABLE interfaces_planes.equivalencia_tipos_afiliados ADD PRIMARY KEY(equivalencia_tipo_afiliado_id);
ALTER TABLE interfaces_planes.equivalencia_tipos_afiliados ADD FOREIGN KEY (tipo_afiliado_id) 
REFERENCES tipos_afiliado(tipo_afiliado_id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE interfaces_planes.equivalencia_tipos_afiliados ADD FOREIGN KEY (plan_id) 
REFERENCES planes(plan_id) ON UPDATE CASCADE ON DELETE CASCADE;

COMMENT ON TABLE interfaces_planes.equivalencia_tipos_afiliados IS 'Tabla donde se hacen las equivalencoias de los tipos de afiliado segun este en el archivo plano';
COMMENT ON COLUMN interfaces_planes.equivalencia_tipos_afiliados.equivalencia_tipo_afiliado_id IS '(PK) Identificador de los registros de la tabla';
COMMENT ON COLUMN interfaces_planes.equivalencia_tipos_afiliados.plan_id IS '(FK) Identificador del plan plan';
COMMENT ON COLUMN interfaces_planes.equivalencia_tipos_afiliados.tipo_afiliado_id IS '(FK) Identificador del tipo de afiliado';
COMMENT ON COLUMN interfaces_planes.equivalencia_tipos_afiliados.tipo_afiliado_archivo IS 'Tipo de afiliado del archivo';

GRANT ALL ON TABLE interfaces_planes.equivalencia_tipos_afiliados TO siis;

CREATE TABLE interfaces_planes.afiliados_externos
(
  afiliado_tipo_id character(2) NOT NULL,
  afiliado_id character varying(16) NOT NULL,
  primer_apellido character varying(20) NOT NULL,
  segundo_apellido character varying(30),
  primer_nombre character varying(20) NOT NULL,
  segundo_nombre character varying(30),
  tipo_afiliado_id CHARACTER VARYING(2),
  fecha_nacimiento date NOT NULL,
  sexo_id character(1) NOT NULL,
  tipo_pais_id character varying(4),
  tipo_dpto_id character varying(4),
  tipo_mpio_id character varying(4),
  zona_residencia character(1),
  direccion_residencia character varying(60),
  telefono_residencia character varying(30),
  telefono_movil character varying(30),
  tipo_estrato_id character(2),
  tipo_estado_civil_id character varying(6)
);

ALTER TABLE ONLY interfaces_planes.afiliados_externos
ADD PRIMARY KEY (afiliado_tipo_id, afiliado_id);

ALTER TABLE ONLY interfaces_planes.afiliados_externos ADD FOREIGN KEY (tipo_estrato_id) 
REFERENCES public.tipos_estratos(tipo_estrato_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY interfaces_planes.afiliados_externos ADD FOREIGN KEY (afiliado_tipo_id) 
REFERENCES public.tipos_id_pacientes(tipo_id_paciente) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY interfaces_planes.afiliados_externos ADD FOREIGN KEY (tipo_estado_civil_id) 
REFERENCES public.tipo_estado_civil(tipo_estado_civil_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY interfaces_planes.afiliados_externos ADD FOREIGN KEY (tipo_pais_id, tipo_dpto_id, tipo_mpio_id) 
REFERENCES public.tipo_mpios(tipo_pais_id, tipo_dpto_id, tipo_mpio_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY interfaces_planes.afiliados_externos ADD FOREIGN KEY (sexo_id) 
REFERENCES public.tipo_sexo(sexo_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY interfaces_planes.afiliados_externos ADD FOREIGN KEY (zona_residencia) 
REFERENCES public.zonas_residencia(zona_residencia) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE interfaces_planes.afiliados_externos ADD FOREIGN KEY (tipo_afiliado_id) 
REFERENCES tipos_afiliado(tipo_afiliado_id) ON UPDATE CASCADE ON DELETE RESTRICT;

COMMENT ON TABLE interfaces_planes.afiliados_externos IS 'Tabla donde se guaradan los pacientres subidos por archivos planos';
COMMENT ON COLUMN interfaces_planes.afiliados_externos.tipo_afiliado_id IS 'Tipo de afiliado';
COMMENT ON COLUMN interfaces_planes.afiliados_externos.afiliado_tipo_id IS 'Tipo de identificacion del afiliado';
COMMENT ON COLUMN interfaces_planes.afiliados_externos.afiliado_id IS 'Identificacion del afiliado';
COMMENT ON COLUMN interfaces_planes.afiliados_externos.primer_apellido IS 'Primer apellido del afiliado';
COMMENT ON COLUMN interfaces_planes.afiliados_externos.segundo_apellido IS 'Segundo apellido del afiliado';
COMMENT ON COLUMN interfaces_planes.afiliados_externos.primer_nombre IS 'Primer nombre del afiliado';
COMMENT ON COLUMN interfaces_planes.afiliados_externos.segundo_nombre IS 'Segundo nombre del afiliado';
COMMENT ON COLUMN interfaces_planes.afiliados_externos.fecha_nacimiento IS 'Fecha de nacimiento del afiliado';
COMMENT ON COLUMN interfaces_planes.afiliados_externos.sexo_id IS 'Identificador del tipo sexo del paciente';
COMMENT ON COLUMN interfaces_planes.afiliados_externos.tipo_pais_id IS 'Identificador del pais de residencia';
COMMENT ON COLUMN interfaces_planes.afiliados_externos.tipo_dpto_id IS 'Identificador del departamento de residencia';
COMMENT ON COLUMN interfaces_planes.afiliados_externos.tipo_mpio_id IS 'Identificador del municipio de residencia';
COMMENT ON COLUMN interfaces_planes.afiliados_externos.zona_residencia IS 'Identificador de la zona de residencia';
COMMENT ON COLUMN interfaces_planes.afiliados_externos.direccion_residencia IS 'Direccion de la residencia';
COMMENT ON COLUMN interfaces_planes.afiliados_externos.telefono_residencia IS 'Telefono residencia';
COMMENT ON COLUMN interfaces_planes.afiliados_externos.telefono_movil IS 'Telefono movil';
COMMENT ON COLUMN interfaces_planes.afiliados_externos.tipo_estrato_id IS 'Identificador del estrato';
COMMENT ON COLUMN interfaces_planes.afiliados_externos.tipo_estado_civil_id IS 'Identificador del estado civil';

GRANT ALL ON TABLE interfaces_planes.afiliados_externos TO siis;

CREATE TABLE interfaces_planes.equivalencia_servicios
(
  equivalencia_servicio_id SERIAL NOT NULL,
  plan_id INTEGER,
  servicio CHARACTER VARYING(2),
  servicio_archivo CHARACTER VARYING(255)
);

ALTER TABLE interfaces_planes.equivalencia_servicios ADD PRIMARY KEY(equivalencia_servicio_id);
ALTER TABLE interfaces_planes.equivalencia_servicios ADD FOREIGN KEY (servicio) 
REFERENCES servicios(servicio) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE interfaces_planes.equivalencia_servicios ADD FOREIGN KEY (plan_id) 
REFERENCES planes(plan_id) ON UPDATE CASCADE ON DELETE CASCADE;

COMMENT ON TABLE interfaces_planes.equivalencia_servicios IS 'Tabla donde se hacen las equivalencoias de los tipos de atencion segun este en el archivo plano';
COMMENT ON COLUMN interfaces_planes.equivalencia_servicios.equivalencia_servicio_id IS '(PK) Identificador de los registros de la tabla';
COMMENT ON COLUMN interfaces_planes.equivalencia_servicios.plan_id IS '(FK) Identificador del plan';
COMMENT ON COLUMN interfaces_planes.equivalencia_servicios.servicio IS '(FK) Identificador del servicio segun la base de datos';
COMMENT ON COLUMN interfaces_planes.equivalencia_servicios.servicio_archivo IS 'Tipo de atecnion relacionado en el archivo plano';

GRANT ALL ON TABLE interfaces_planes.equivalencia_servicios TO siis;

CREATE TABLE interfaces_planes.equivalencia_especialidades
(
  equivalencia_especialidad_id SERIAL NOT NULL,
  plan_id INTEGER,
  especialidad CHARACTER VARYING(4),
  especialidad_archivo CHARACTER VARYING(255)
);

ALTER TABLE interfaces_planes.equivalencia_especialidades ADD PRIMARY KEY(equivalencia_especialidad_id);
ALTER TABLE interfaces_planes.equivalencia_especialidades ADD FOREIGN KEY (especialidad) 
REFERENCES especialidades(especialidad) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE interfaces_planes.equivalencia_especialidades ADD FOREIGN KEY (plan_id) 
REFERENCES planes(plan_id) ON UPDATE CASCADE ON DELETE CASCADE;

COMMENT ON TABLE interfaces_planes.equivalencia_especialidades IS 'Tabla donde se hacen las equivalencoias de las especialides segun este en el archivo plano';
COMMENT ON COLUMN interfaces_planes.equivalencia_especialidades.equivalencia_especialidad_id IS '(PK) Identificador de los registros de la tabla';
COMMENT ON COLUMN interfaces_planes.equivalencia_especialidades.plan_id IS '(FK) Identificador del plan';
COMMENT ON COLUMN interfaces_planes.equivalencia_especialidades.especialidad IS '(FK) Identificador de la especialidad segun la base de datos';
COMMENT ON COLUMN interfaces_planes.equivalencia_especialidades.especialidad_archivo IS 'Especialidad relacionada en el archivo plano';

GRANT ALL ON TABLE interfaces_planes.equivalencia_especialidades TO siis;

CREATE TABLE interfaces_planes.equivalencia_usuarios
(
  equivalencia_usuario_id SERIAL NOT NULL,
  plan_id INTEGER,
  usuario_id INTEGER,
  usuario_archivo CHARACTER VARYING(255)
);

ALTER TABLE interfaces_planes.equivalencia_usuarios ADD PRIMARY KEY(equivalencia_usuario_id);
ALTER TABLE interfaces_planes.equivalencia_usuarios ADD FOREIGN KEY (usuario_id) 
REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE interfaces_planes.equivalencia_usuarios ADD FOREIGN KEY (plan_id) 
REFERENCES planes(plan_id) ON UPDATE CASCADE ON DELETE CASCADE;

COMMENT ON TABLE interfaces_planes.equivalencia_usuarios IS 'Tabla donde se hacen las equivalencoias de las especialides segun este en el archivo plano';
COMMENT ON COLUMN interfaces_planes.equivalencia_usuarios.equivalencia_usuario_id IS '(PK) Identificador de los registros de la tabla';
COMMENT ON COLUMN interfaces_planes.equivalencia_usuarios.plan_id IS '(FK) Identificador del plan';
COMMENT ON COLUMN interfaces_planes.equivalencia_usuarios.usuario_id IS '(FK) Identificador del usuario segun la base de datos';
COMMENT ON COLUMN interfaces_planes.equivalencia_usuarios.usuario_archivo IS 'Usuario relacionado en el archivo plano';

GRANT ALL ON TABLE interfaces_planes.equivalencia_usuarios TO siis;

CREATE TABLE interfaces_planes.equivalencia_moleculas
(
  equivalencia_molecula_id SERIAL NOT NULL,
  plan_id INTEGER,
  molecula_id	CHARACTER VARYING(10),
  molecula_archivo CHARACTER VARYING(255)
);

ALTER TABLE interfaces_planes.equivalencia_moleculas ADD PRIMARY KEY(equivalencia_molecula_id);
ALTER TABLE interfaces_planes.equivalencia_moleculas ADD FOREIGN KEY (molecula_id) 
REFERENCES inv_moleculas(molecula_id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE interfaces_planes.equivalencia_moleculas ADD FOREIGN KEY (plan_id) 
REFERENCES planes(plan_id) ON UPDATE CASCADE ON DELETE CASCADE;

COMMENT ON TABLE interfaces_planes.equivalencia_moleculas IS 'Tabla donde se hacen las equivalencoias de las moleculas segun este en el archivo plano';
COMMENT ON COLUMN interfaces_planes.equivalencia_moleculas.equivalencia_molecula_id IS '(PK) Identificador de los registros de la tabla';
COMMENT ON COLUMN interfaces_planes.equivalencia_moleculas.plan_id IS '(FK) Identificador del plan';
COMMENT ON COLUMN interfaces_planes.equivalencia_moleculas.molecula_id IS '(FK) Identificador de la molecula segun la base de datos';
COMMENT ON COLUMN interfaces_planes.equivalencia_moleculas.molecula_archivo IS 'Molecula relacionada en el archivo plano';

CREATE TABLE interfaces_planes.equivalencia_laboratorios
(
  equivalencia_laboratorio_id SERIAL NOT NULL,
  plan_id INTEGER,
  laboratorio_id	CHARACTER VARYING(10),
  laboratorio_archivo CHARACTER VARYING(255)
);

ALTER TABLE interfaces_planes.equivalencia_laboratorios ADD PRIMARY KEY(equivalencia_laboratorio_id);
ALTER TABLE interfaces_planes.equivalencia_laboratorios ADD FOREIGN KEY (laboratorio_id) 
REFERENCES inv_laboratorios(laboratorio_id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE interfaces_planes.equivalencia_laboratorios ADD FOREIGN KEY (plan_id) 
REFERENCES planes(plan_id) ON UPDATE CASCADE ON DELETE CASCADE;

COMMENT ON TABLE interfaces_planes.equivalencia_laboratorios IS 'Tabla donde se hacen las equivalencoias de los laboratorios segun este en el archivo plano';
COMMENT ON COLUMN interfaces_planes.equivalencia_laboratorios.equivalencia_laboratorio_id IS '(PK) Identificador de los registros de la tabla';
COMMENT ON COLUMN interfaces_planes.equivalencia_laboratorios.plan_id IS '(FK) Identificador del plan';
COMMENT ON COLUMN interfaces_planes.equivalencia_laboratorios.laboratorio_id IS '(FK) Identificador del laboratorio segun la base de datos';
COMMENT ON COLUMN interfaces_planes.equivalencia_laboratorios.laboratorio_archivo IS 'Laboratorio relacionado en el archivo plano';
