----esto no es de historia clinica
ALTER TABLE "public"."tipos_id_pacientes" ADD COLUMN "codigo_alterno" varchar(30);
update "tipos_id_pacientes" set "codigo_alterno"='1' where tipo_id_paciente='CC';
update "tipos_id_pacientes" set "codigo_alterno"='2' where tipo_id_paciente='TI';
update "tipos_id_pacientes" set "codigo_alterno"='3' where tipo_id_paciente='CE';
update "tipos_id_pacientes" set "codigo_alterno"='4' where tipo_id_paciente='PA';
update "tipos_id_pacientes" set "codigo_alterno"='5' where tipo_id_paciente='RC';
update "tipos_id_pacientes" set "codigo_alterno"='6' where tipo_id_paciente='NU';
update "tipos_id_pacientes" set "codigo_alterno"='7' where tipo_id_paciente='MS';
update "tipos_id_pacientes" set "codigo_alterno"='8' where tipo_id_paciente='AS';


--------------------------------------------hc_evoluciones--------------------------------------------------------
COMMENT ON TABLE hc_evoluciones IS 'Mantiene las diferentes atenciones que se realizen a los pacientes en la institucion.';
COMMENT ON COLUMN hc_evoluciones.evolucion_id IS 'Pk Autonumerico que identifica las diferentes atenciones';
COMMENT ON COLUMN hc_evoluciones.ingreso IS 'Fk Numero de ingreso al que pertene la atencion';
COMMENT ON COLUMN hc_evoluciones.fecha IS 'Fecha de Atencion';
COMMENT ON COLUMN hc_evoluciones.usuario_id IS 'Fk Usuario que realiza la atencion del paciente';
COMMENT ON COLUMN hc_evoluciones.departamento IS 'Fk Identificacion del departamento por donde se atendio al paciente';
COMMENT ON COLUMN hc_evoluciones.estado IS 'Estado de la evolucion 1=Activa, 0=Cerrada y 2=Alterna';
COMMENT ON COLUMN hc_evoluciones.hc_modulo IS 'Fk Identifica el modulo de la historia clinica con la cual se abrio la atencion';
COMMENT ON COLUMN hc_evoluciones.sw_edicion IS 'Campo para revisar si se borra';
COMMENT ON COLUMN hc_evoluciones.fecha_cierre IS 'Fecha en la que se cierra la atencion';
COMMENT ON COLUMN hc_evoluciones.numerodecuenta IS 'Fk identifica la cuenta en la cual se esta realizando la atencion';

-----------------------------------------ingresos--------------------------------
COMMENT ON TABLE ingresos IS 'Almacena los ingresos realizados a los pacientes de la institucion';
COMMENT ON COLUMN ingresos.ingreso IS 'Pk identifica el numero de ingreso del paciente';
COMMENT ON COLUMN ingresos.tipo_id_paciente IS 'Fk tipo de identificacion del paciente';
COMMENT ON COLUMN ingresos.paciente_id IS 'Fk numero de identificacion del paciente';
COMMENT ON COLUMN ingresos.fecha_ingreso IS 'Fecha de ingreso del paciente';
COMMENT ON COLUMN ingresos.causa_externa_id IS 'Fk Causa por la que el paciente ingresa a la institucion';
COMMENT ON COLUMN ingresos.via_ingreso_id IS 'Fk Via por donde ingresa el paciente a la institucion';
COMMENT ON COLUMN ingresos.comentario IS 'Comentario inicial para el ingreso';
COMMENT ON COLUMN ingresos.departamento IS 'Fk Departamento por donde ingreso el paciente';
COMMENT ON COLUMN ingresos.estado IS 'estado del ingreso ';
COMMENT ON COLUMN ingresos.departamento_actual IS 'Fk Departamento en donde se encuentra el paciente';
COMMENT ON COLUMN ingresos.fecha_registro IS 'Fecha en la que se registro el ingreso';
COMMENT ON COLUMN ingresos.usuario_id IS 'Usuario que registro el ingreso del paciente';

--------------------------------------tipo_id_pacientes----------------------------------------
COMMENT ON TABLE tipos_id_pacientes IS 'Catalogo de Tipos de Identificacion de los pacientes';
COMMENT ON COLUMN tipos_id_pacientes.tipo_id_paciente IS 'Tipo de Id pacientes';
COMMENT ON COLUMN tipos_id_pacientes.descripcion IS 'Descripcion del Tipo de Id';
COMMENT ON COLUMN tipos_id_pacientes.indice_de_orden IS 'Indice utilizado para ordenar la consulta de esta tabla (Util en la interface de usuario)';
COMMENT ON COLUMN tipos_id_pacientes.codigo_alterno IS 'codigo que sirve para la interconexion con otros sistemas';

-----------------------------------------pacientes---------------------------------
COMMENT ON TABLE pacientes IS 'Almacena los datos de los pacientes.';
COMMENT ON COLUMN pacientes.paciente_id IS 'Numero de Identificacion del pacientes';
COMMENT ON COLUMN pacientes.tipo_id_paciente IS 'Tipo de Id del pacientes FKEY tipo_id_paciente';
COMMENT ON COLUMN pacientes.primer_apellido IS 'Primer Apellido del pacientes';
COMMENT ON COLUMN pacientes.segundo_apellido IS 'Segundo Apellido del pacientes';
COMMENT ON COLUMN pacientes.primer_nombre IS 'Primer Nombre del pacientes';
COMMENT ON COLUMN pacientes.segundo_nombre IS 'Segundo  Nombre del pacientes';
COMMENT ON COLUMN pacientes.fecha_nacimiento IS 'Fecha de nacimiento del pacientes';
COMMENT ON COLUMN pacientes.fecha_nacimiento_es_calculada IS 'Indica si la fecha del nacimiento del pacientes fue calculada o es la real 0=Real, 1=Calculada';
COMMENT ON COLUMN pacientes.residencia_direccion IS 'Direccion de Residencia del pacientes';
COMMENT ON COLUMN pacientes.residencia_telefono IS 'Telefono de la Residencia del pacientes';
COMMENT ON COLUMN pacientes.zona_residencia IS 'Zona de la residencia R=Rural  U=Urbana';
COMMENT ON COLUMN pacientes.ocupacion_id IS 'Ocupacion del pacientes';
COMMENT ON COLUMN pacientes.fecha_registro IS 'Fecha y hora en que se registro el pacientes';
COMMENT ON COLUMN pacientes.sexo_id IS 'Sexo del pacientes FKEY tipo_sexo';
COMMENT ON COLUMN pacientes.tipo_estado_civil_id IS 'Estado Civil del pacientes tipo_estado_civil (tipo_estado_civil_id)';
COMMENT ON COLUMN pacientes.foto IS 'Foto del paciente';
COMMENT ON COLUMN pacientes.tipo_pais_id IS 'Pais de Residencia del pacientes FKEY tipo_mpios(tipo_pais_id)';
COMMENT ON COLUMN pacientes.tipo_dpto_id IS 'Dpto/Estado/Provincia  de Residencia del pacientes FKEY tipo_mpios(tipo_dpto_id)';
COMMENT ON COLUMN pacientes.tipo_mpio_id IS 'Mpio/Ciudad de Residencia del pacientes FKEY tipo_mpios(tipo_mpio_id)';
COMMENT ON COLUMN pacientes.paciente_fallecido IS 'Identifica el fallecimiento del paciente 1=Fallecido y 0=Vivo';
COMMENT ON COLUMN pacientes.usuario_id IS 'FK usuario_id del Usuario del sistema';
COMMENT ON COLUMN pacientes.nombre_madre IS 'Identifica la madre del paciente';
COMMENT ON COLUMN pacientes.observaciones IS 'Observaciones generales del paciente';

--------------------------------departamentos--------------------------------
COMMENT ON TABLE departamentos IS 'Se registran los diferentes departamentos de las instituciones';
COMMENT ON COLUMN departamentos.empresa_id IS 'Identifica la empresa en la que se encuentra el departamento';
COMMENT ON COLUMN departamentos.centro_utilidad IS 'Identifica el centro de utilidad en donde esta ubicada la empresa';
COMMENT ON COLUMN departamentos.unidad_funcional IS 'Identifica las unidades funcionales de la empresa';
COMMENT ON COLUMN departamentos.departamento IS 'Identifica los diferentes departamentos de la empresa';
COMMENT ON COLUMN departamentos.descripcion IS 'Nombre del departamento de la empresa';
COMMENT ON COLUMN departamentos.sw_internacion IS 'Identifica si el departamento tiene o no internacion 1=Tiene y 0=No tiene';
COMMENT ON COLUMN departamentos.servicio IS 'Identifica el tipo de servicio que presta el departamento';

-----------------------------------------system_hc_modulos-----------------------------
COMMENT ON TABLE system_hc_modulos IS 'Determina el tipo de Historia Clinica que se esta manejando';
COMMENT ON COLUMN system_hc_modulos.hc_modulo IS 'PK Nombre modulo de historia clinica';
COMMENT ON COLUMN system_hc_modulos.descripcion IS 'Descripcion del Modulo de HC';
COMMENT ON COLUMN system_hc_modulos.activo IS 'Disponibilidad de la Historia Clinica 0=Inactivo 1=Activo';
COMMENT ON COLUMN system_hc_modulos.tipo_finalidad_id IS 'Identifica el tipo de finalidad general de la historia clinica';
COMMENT ON COLUMN system_hc_modulos.rips_tipo_id IS 'Identifica el tipo de rips que debe generar la historia clinica';

---------------------------------------system_usuarios--------------------------------
--no es de historia clinica
COMMENT ON TABLE system_usuarios IS 'Usuarios del Sistema';
COMMENT ON COLUMN system_usuarios.usuario_id IS 'UID del Usuario del sistema';
COMMENT ON COLUMN system_usuarios.usuario IS 'Login del Usuario';
COMMENT ON COLUMN system_usuarios.nombre IS 'Nombre Completo del Usuario';
COMMENT ON COLUMN system_usuarios.descripcion IS 'Descripcion del Usuario';
COMMENT ON COLUMN system_usuarios.passwd IS 'Contrase� del Usuario';
COMMENT ON COLUMN system_usuarios.sw_admin IS 'Define si el usuario es administrador del sistema o usuario normal 1=Admin 0=Normal';
COMMENT ON COLUMN system_usuarios.activo IS 'Estado del Usuario 1=Activo 0=Bloqueado';
COMMENT ON COLUMN system_usuarios.fecha_caducidad_contrasena IS 'Fecha en la que la contrase� debe ser cambiada';
COMMENT ON COLUMN system_usuarios.fecha_caducidad_cuenta IS 'Fecha en la que la cuenta caduca';
COMMENT ON COLUMN system_usuarios.caducidad_contrasena IS 'identifica si la cuenta caduca o no 1=Caduca 0=No caduca';
ALTER TABLE "public"."system_usuarios" ADD COLUMN "codigo_alterno" VARCHAR(30);
COMMENT ON COLUMN system_usuarios.codigo_alterno IS 'codigo que sirve para la interconexion con otros sistemas';

----------------------------------centros_utilidades-------------------------------------
COMMENT ON TABLE centros_utilidad IS 'Almacena la clasificacion de las diferentes sucursales de la empresa ';
COMMENT ON COLUMN centros_utilidad.empresa_id IS 'FK ide de la empresa';
COMMENT ON COLUMN centros_utilidad.centro_utilidad IS 'PK id del centro';
COMMENT ON COLUMN centros_utilidad.descripcion IS 'nombre del centro';

----------------------------------empresas-----------------------------------------------
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

---------------------------------unidades_funcionales-----------------------------
COMMENT ON TABLE unidades_funcionales IS 'Se encuntran las diferentes unidades funcionales de las empresas inscritas.';
COMMENT ON COLUMN unidades_funcionales.empresa_id IS 'Empresa donde se encuentra la unidad funcional';
COMMENT ON COLUMN unidades_funcionales.centro_utilidad IS 'Centro de utilidad donde se encuntra la unidad funcional';
COMMENT ON COLUMN unidades_funcionales.unidad_funcional IS 'Codigo de Unidad funcional';
COMMENT ON COLUMN unidades_funcionales.descripcion IS 'Descripcion de unidad funcional';

------------------------------------hc_motivo_consulta----------------------------------
COMMENT ON TABLE hc_motivo_consulta IS 'Contiene los motivos de consulta y la enfermedad actual de una atencion del paciente';
COMMENT ON COLUMN hc_motivo_consulta.hc_motivo_consulta_id IS 'PK Serial que identifica los diferentes motivos de consulta de un paciente';
COMMENT ON COLUMN hc_motivo_consulta.evolucion_id IS 'Identificacion de la atencion del paciente';
COMMENT ON COLUMN hc_motivo_consulta.descripcion IS 'Descripcion del motivo de consulta';
COMMENT ON COLUMN hc_motivo_consulta.enfermedadactual IS 'Descripcion de la enfermedad actual';

-------------------------------------hc_diagnosticos_ingreso---------------------------
COMMENT ON TABLE hc_diagnosticos_ingreso IS 'Almacena los diagnosticos de ingreso de cada paciente en cada evolucion';
COMMENT ON COLUMN hc_diagnosticos_ingreso.usuario_id IS 'Usuario del sistema';
COMMENT ON COLUMN hc_diagnosticos_ingreso.tipo_diagnostico_id IS 'Identificacion del diagnostico';
COMMENT ON COLUMN hc_diagnosticos_ingreso.evolucion_id IS 'Identificacion de la atencion del paciente';
COMMENT ON COLUMN hc_diagnosticos_ingreso.sw_principal IS 'Identifica el diagnostico principal en la atencion 1=Principal y 0=Otro';
COMMENT ON COLUMN hc_diagnosticos_ingreso.descripcion IS 'Descripcion especial del diagnostico.';

--------------------------------hc_signos_vitales_consultas------------------------------
COMMENT ON TABLE hc_signos_vitales_consultas IS 'Contiene los signos vitales de las consultas externas';
COMMENT ON COLUMN hc_signos_vitales_consultas.signos_vitales_consulta_id IS 'PK Llave primaria de la tabla';
COMMENT ON COLUMN hc_signos_vitales_consultas.fc IS 'Datos de signos vitales';
COMMENT ON COLUMN hc_signos_vitales_consultas.fr IS 'Datos de signos vitales';
COMMENT ON COLUMN hc_signos_vitales_consultas.taalta IS 'Datos de signos vitales';
COMMENT ON COLUMN hc_signos_vitales_consultas.tabaja IS 'Datos de signos vitales';
COMMENT ON COLUMN hc_signos_vitales_consultas.talla IS 'Datos de signos vitales';
COMMENT ON COLUMN hc_signos_vitales_consultas.evolucion_id IS 'FK Numero de identificacion de la evolucion hc_evoluciones(evolucion_id)';
COMMENT ON COLUMN hc_signos_vitales_consultas.peso IS 'Datos de signos vitales';
COMMENT ON COLUMN hc_signos_vitales_consultas.temperatura IS 'Datos de signos vitales';

---------------------------------hc_tipos_antecedentes_personales------------------------
ALTER TABLE "hc_tipos_antecedentes_det" DROP CONSTRAINT "$1";
DROP TABLE "public"."hc_tipos_antecedentes_per";
DROP TABLE "public"."hc_tipos_antecedentes_modulos";
ALTER TABLE "hc_antecedentes_personales" DROP CONSTRAINT "$1";
DROP TABLE "public"."hc_tipos_antecedentes_det";

CREATE TABLE "public"."hc_tipos_antecedentes_personales" (
  "hc_tipo_antecedente_personal_id" SERIAL,
  "descripcion" VARCHAR(40) NOT NULL,
  "edad_min" NUMERIC(3,0),
  "edad_max" NUMERIC(3,0),
  "sexo" CHAR(1),
  PRIMARY KEY("hc_tipo_antecedente_personal_id"),
  FOREIGN KEY ("sexo")
    REFERENCES "public"."tipo_sexo"("sexo_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
COMMENT ON TABLE hc_tipos_antecedentes_personales IS 'Tabla maestro para identificar los diferentes tipos de antecedentes personal';
COMMENT ON COLUMN hc_tipos_antecedentes_personales.hc_tipo_antecedente_personal_id IS 'PK Llave serial para identificar el tipo de antecedente personal';
COMMENT ON COLUMN hc_tipos_antecedentes_personales.descripcion IS 'Descripcion del tipo de antecedente personal';
COMMENT ON COLUMN hc_tipos_antecedentes_personales.edad_min IS 'Edad m�ima a la cual debe ser presentado el antecedente personal';
COMMENT ON COLUMN hc_tipos_antecedentes_personales.edad_max IS 'Edad m�ima a la cual debe ser presentado el antecedente personal';
COMMENT ON COLUMN hc_tipos_antecedentes_personales.sexo IS 'Sexo al cual debe ser presentado el antecedente personal';

INSERT INTO "public"."hc_tipos_antecedentes_personales" ("hc_tipo_antecedente_personal_id", "descripcion", "edad_min", "edad_max", "sexo")
VALUES
  ('1','PATOLOGICOS',NULL,NULL,NULL);

INSERT INTO "public"."hc_tipos_antecedentes_personales" ("hc_tipo_antecedente_personal_id", "descripcion", "edad_min", "edad_max", "sexo")
VALUES
  ('2','QUIRURGICOS',NULL,NULL,NULL);


--------------------------------hc_tipos_antecedentes_personales_detalle-----------------
CREATE TABLE "public"."hc_tipos_antecedentes_detalle_personales" (
  "hc_tipo_antecedente_detalle_personal_id" SERIAL,
  "nombre_tipo" VARCHAR(40),
  "riesgo" CHAR(1),
  "hc_tipo_antecedente_personal_id" INTEGER NOT NULL,
  PRIMARY KEY("hc_tipo_antecedente_detalle_personal_id", "hc_tipo_antecedente_personal_id"),
  FOREIGN KEY ("hc_tipo_antecedente_personal_id")
    REFERENCES "public"."hc_tipos_antecedentes_personales"("hc_tipo_antecedente_personal_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);

COMMENT ON TABLE "public"."hc_tipos_antecedentes_detalle_personales" IS 'Tabla maestro para identificar los diferentes tipos de detalle de los antecedentes personales';
COMMENT ON COLUMN "public"."hc_tipos_antecedentes_detalle_personales"."hc_tipo_antecedente_personal_id" IS 'FK Llave foranea que identifica el maestro del antecedente personal';
COMMENT ON COLUMN "public"."hc_tipos_antecedentes_detalle_personales"."hc_tipo_antecedente_detalle_personal_id" IS 'PK llave que identifica el tipo de antecedente personales';
COMMENT ON COLUMN "public"."hc_tipos_antecedentes_detalle_personales"."nombre_tipo" IS 'Nombre que identifica al tipo';
COMMENT ON COLUMN "public"."hc_tipos_antecedentes_detalle_personales"."riesgo" IS 'Valor de si(1) o no(0) para identificar el riesgo del antecedente personal';

INSERT INTO hc_tipos_antecedentes_detalle_personales VALUES (2, 'Diabetes', '1', 1);
INSERT INTO hc_tipos_antecedentes_detalle_personales VALUES (3, 'Cardiocerebrovascular', '1', 1);
INSERT INTO hc_tipos_antecedentes_detalle_personales VALUES (4, 'Enfermedad Infecciosa', '1', 1);
INSERT INTO hc_tipos_antecedentes_detalle_personales VALUES (5, 'Enfermedad Respiratoria', '1', 1);
INSERT INTO hc_tipos_antecedentes_detalle_personales VALUES (7, 'Alergias', '1', 1);
INSERT INTO hc_tipos_antecedentes_detalle_personales VALUES (8, 'Enfermedades Reumaticas', '1', 1);
INSERT INTO hc_tipos_antecedentes_detalle_personales VALUES (9, 'Cancer', '1', 1);
INSERT INTO hc_tipos_antecedentes_detalle_personales VALUES (10, 'Recibe Medicacion', '2', 1);
INSERT INTO hc_tipos_antecedentes_detalle_personales VALUES (12, 'Enfermedad Acido Peptica', '1', 1);
INSERT INTO hc_tipos_antecedentes_detalle_personales VALUES (13, 'Enfermedad Genitourinaria', '1', 1);
INSERT INTO hc_tipos_antecedentes_detalle_personales VALUES (15, 'Enfermedad Mental', '1', 1);
INSERT INTO hc_tipos_antecedentes_detalle_personales VALUES (16, 'Traumaticos', '1', 1);
INSERT INTO hc_tipos_antecedentes_detalle_personales VALUES (18, 'Consumo de Alcohol', '1', 1);
INSERT INTO hc_tipos_antecedentes_detalle_personales VALUES (19, 'Consumo de Cigarrillo', '1', 1);
INSERT INTO hc_tipos_antecedentes_detalle_personales VALUES (20, 'Consumo de Psicofarmacos', '1', 1);
INSERT INTO hc_tipos_antecedentes_detalle_personales VALUES (21, 'Otras Sustancias', '1', 1);
INSERT INTO hc_tipos_antecedentes_detalle_personales VALUES (14, 'Quirurgicos', '1', 2);
INSERT INTO hc_tipos_antecedentes_detalle_personales VALUES (1, 'Hipertension Arterial', '1', 1);
INSERT INTO hc_tipos_antecedentes_detalle_personales VALUES (22, 'Otros', '1', 1);
INSERT INTO hc_tipos_antecedentes_detalle_personales VALUES (6, 'Enfermedad de trnasmision sexual', '1', 1);
INSERT INTO hc_tipos_antecedentes_detalle_personales VALUES (23, 'Referencias Perinatales', '1', 1);
INSERT INTO hc_tipos_antecedentes_detalle_personales VALUES (24, 'Referencias Osteomusculares', '1', 1);
INSERT INTO hc_tipos_antecedentes_detalle_personales VALUES (11, 'Actividad Fisica', '1', 1);

--------------------------hc_tipos_antecedentes_personales_modulos-----------------------
CREATE TABLE "public"."hc_tipos_antecedentes_personales_modulos" (
  "hc_tipo_antecedente_personal_id" INTEGER NOT NULL,
  "hc_tipo_antecedente_detalle_personal_id" INTEGER NOT NULL,
  "hc_modulo" VARCHAR(64) NOT NULL,
  CONSTRAINT "hc_tipos_antecedentes_personales_modulos_pkey" PRIMARY KEY("hc_tipo_antecedente_personal_id", "hc_tipo_antecedente_detalle_personal_id", "hc_modulo"),
  FOREIGN KEY ("hc_tipo_antecedente_personal_id", "hc_tipo_antecedente_detalle_personal_id")
    REFERENCES "public"."hc_tipos_antecedentes_detalle_personales"("hc_tipo_antecedente_personal_id", "hc_tipo_antecedente_detalle_personal_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  FOREIGN KEY ("hc_modulo")
    REFERENCES "public"."system_hc_modulos"("hc_modulo")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);

COMMENT ON TABLE "public"."hc_tipos_antecedentes_personales_modulos" IS 'sirve para cruzar los diferentes antecedentes personales con los modulos de la atencion';
COMMENT ON COLUMN "public"."hc_tipos_antecedentes_personales_modulos"."hc_tipo_antecedente_personal_id" IS 'Fk identificador del tipo de antecedente personal';
COMMENT ON COLUMN "public"."hc_tipos_antecedentes_personales_modulos"."hc_tipo_antecedente_detalle_personal_id" IS 'Fk identificador del tipo de antecedente personal';
COMMENT ON COLUMN "public"."hc_tipos_antecedentes_personales_modulos"."hc_modulo" IS 'Fk Identificador del modulo de la historia clinica';

-------------------------------hc_tipos_antecedentes_familiares----------------------------
CREATE TABLE "public"."hc_tipos_antecedentes_familiares" (
  "hc_tipo_antecedente_familiar_id" SERIAL,
  "descripcion" VARCHAR(40) NOT NULL,
  "edad_min" NUMERIC(3,0),
  "edad_max" NUMERIC(3,0),
  "sexo" CHAR(1),
  CONSTRAINT "hc_tipos_antecedentes_familiares_pkey" PRIMARY KEY("hc_tipo_antecedente_familiar_id")
);

COMMENT ON TABLE "public"."hc_tipos_antecedentes_familiares" IS 'Tabla maestro para identificar los diferentes tipos de antecedentes familiares';
COMMENT ON COLUMN "public"."hc_tipos_antecedentes_familiares"."hc_tipo_antecedente_familiar_id" IS 'PK Llave serial para identificar el tipo de antecedente familiar';
COMMENT ON COLUMN "public"."hc_tipos_antecedentes_familiares"."descripcion" IS 'Descripcion del tipo de antecedente familiar';
COMMENT ON COLUMN "public"."hc_tipos_antecedentes_familiares"."edad_min" IS 'Edad mínima a la cual debe ser presentado el antecedente familiar';
COMMENT ON COLUMN "public"."hc_tipos_antecedentes_familiares"."edad_max" IS 'Edad máxima a la cual debe ser presentado el antecedente familiar';
COMMENT ON COLUMN "public"."hc_tipos_antecedentes_familiares"."sexo" IS 'Sexo al cual debe ser presentado el antecedente familiar';

INSERT INTO hc_tipos_antecedentes_familiares VALUES (1, 'FAMILIARES', NULL, NULL, NULL);

-------------------------------hc_tipos_antecedentes_familiares_detalle----------------------
CREATE TABLE "public"."hc_tipos_antecedentes_detalle_familiares" (
  "hc_tipo_antecedente_familiar_id" INTEGER NOT NULL,
  "hc_tipo_antecedente_detalle_familiar_id" SERIAL,
  "nombre_tipo" VARCHAR(40),
  "riesgo" CHAR(1),
  CONSTRAINT "hc_tipos_antecedentes_detalle_familiares_pkey" PRIMARY KEY("hc_tipo_antecedente_detalle_familiar_id", "hc_tipo_antecedente_familiar_id"),
  FOREIGN KEY ("hc_tipo_antecedente_familiar_id")
    REFERENCES "public"."hc_tipos_antecedentes_familiares"("hc_tipo_antecedente_familiar_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);

COMMENT ON TABLE hc_tipos_antecedentes_detalle_familiares IS 'Tabla maestro para identificar los diferentes tipos de detalle de los antecedentes familiares';
COMMENT ON COLUMN hc_tipos_antecedentes_detalle_familiares.hc_tipo_antecedente_detalle_familiar_id IS 'PK llave que identifica el tipo de antecedente familiares';
COMMENT ON COLUMN hc_tipos_antecedentes_detalle_familiares.nombre_tipo IS 'Nombre que identifica al tipo';
COMMENT ON COLUMN hc_tipos_antecedentes_detalle_familiares.riesgo IS 'Valor de si(1) o no(0) para identificar el riesgo del antecedente familiares';
COMMENT ON COLUMN hc_tipos_antecedentes_detalle_familiares.hc_tipo_antecedente_familiar_id IS 'FK Llave foranea que identifica el maestro del antecedente familiares';

INSERT INTO "public"."hc_tipos_antecedentes_detalle_familiares" ("hc_tipo_antecedente_familiar_id", "hc_tipo_antecedente_detalle_familiar_id", "nombre_tipo", "riesgo")
VALUES ('1','2','Obesidad','1');

INSERT INTO "public"."hc_tipos_antecedentes_detalle_familiares" ("hc_tipo_antecedente_familiar_id", "hc_tipo_antecedente_detalle_familiar_id", "nombre_tipo", "riesgo")
VALUES ('1','3','Enfermedad Cerebrovascular','1');

INSERT INTO "public"."hc_tipos_antecedentes_detalle_familiares" ("hc_tipo_antecedente_familiar_id", "hc_tipo_antecedente_detalle_familiar_id", "nombre_tipo", "riesgo")
VALUES ('1','4','Dislipidemias','1');

INSERT INTO "public"."hc_tipos_antecedentes_detalle_familiares" ("hc_tipo_antecedente_familiar_id", "hc_tipo_antecedente_detalle_familiar_id", "nombre_tipo", "riesgo")
VALUES ('1','5','Diabetes','1');

INSERT INTO "public"."hc_tipos_antecedentes_detalle_familiares" ("hc_tipo_antecedente_familiar_id", "hc_tipo_antecedente_detalle_familiar_id", "nombre_tipo", "riesgo")
VALUES ('1','6','Enfermedad Mental','1');

INSERT INTO "public"."hc_tipos_antecedentes_detalle_familiares" ("hc_tipo_antecedente_familiar_id", "hc_tipo_antecedente_detalle_familiar_id", "nombre_tipo", "riesgo")
VALUES ('1','7','Cancer','1');

INSERT INTO "public"."hc_tipos_antecedentes_detalle_familiares" ("hc_tipo_antecedente_familiar_id", "hc_tipo_antecedente_detalle_familiar_id", "nombre_tipo", "riesgo")
VALUES ('1','8','Alergias','1');

INSERT INTO "public"."hc_tipos_antecedentes_detalle_familiares" ("hc_tipo_antecedente_familiar_id", "hc_tipo_antecedente_detalle_familiar_id", "nombre_tipo", "riesgo")
VALUES ('1','9','Enfermedad Respiratorias','1');

INSERT INTO "public"."hc_tipos_antecedentes_detalle_familiares" ("hc_tipo_antecedente_familiar_id", "hc_tipo_antecedente_detalle_familiar_id", "nombre_tipo", "riesgo")
VALUES ('1','11','Otros','1');

INSERT INTO "public"."hc_tipos_antecedentes_detalle_familiares" ("hc_tipo_antecedente_familiar_id", "hc_tipo_antecedente_detalle_familiar_id", "nombre_tipo", "riesgo")
VALUES ('1','1','Hipertension','1');

INSERT INTO "public"."hc_tipos_antecedentes_detalle_familiares" ("hc_tipo_antecedente_familiar_id", "hc_tipo_antecedente_detalle_familiar_id", "nombre_tipo", "riesgo")
VALUES ('1','10','Artropatias','1');

INSERT INTO "public"."hc_tipos_antecedentes_detalle_familiares" ("hc_tipo_antecedente_familiar_id", "hc_tipo_antecedente_detalle_familiar_id", "nombre_tipo", "riesgo")
VALUES ('1','12','Enfermedad osteomuscular','2');

INSERT INTO "public"."hc_tipos_antecedentes_detalle_familiares" ("hc_tipo_antecedente_familiar_id", "hc_tipo_antecedente_detalle_familiar_id", "nombre_tipo", "riesgo")
VALUES ('1','13','Enfermedad peinatal','2');



ALTER TABLE "hc_antecedentes_familiares" RENAME COLUMN "hc_tipo_antecedente_fam_id" TO "hc_tipo_antecedente_familiar_id";

ALTER TABLE "hc_antecedentes_familiares" RENAME COLUMN "hc_tipo_antecedente_detf_id" TO "hc_tipo_antecedente_detalle_familiar_id";

ALTER TABLE "public"."hc_antecedentes_familiares"
  DROP CONSTRAINT "$2" RESTRICT;

ALTER TABLE "public"."hc_antecedentes_familiares"
  ADD CONSTRAINT "$2" FOREIGN KEY ("hc_tipo_antecedente_familiar_id", "hc_tipo_antecedente_detalle_familiar_id")
    REFERENCES "public"."hc_tipos_antecedentes_detalle_familiares"("hc_tipo_antecedente_familiar_id", "hc_tipo_antecedente_detalle_familiar_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE;


CREATE TABLE hc_tipos_antecedentes_familiares_modulos (
    hc_tipo_antecedente_familiar_id integer NOT NULL,
    hc_tipo_antecedente_detalle_familiar_id integer NOT NULL,
    hc_modulo character varying(64) NOT NULL
);
ALTER TABLE ONLY hc_tipos_antecedentes_familiares_modulos
    ADD PRIMARY KEY (hc_tipo_antecedente_familiar_id, hc_tipo_antecedente_detalle_familiar_id, hc_modulo);
ALTER TABLE ONLY hc_tipos_antecedentes_familiares_modulos
    ADD FOREIGN KEY (hc_tipo_antecedente_familiar_id, hc_tipo_antecedente_detalle_familiar_id) REFERENCES hc_tipos_antecedentes_detalle_familiares(hc_tipo_antecedente_familiar_id, hc_tipo_antecedente_detalle_familiar_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY hc_tipos_antecedentes_familiares_modulos
    ADD FOREIGN KEY (hc_modulo) REFERENCES system_hc_modulos(hc_modulo) ON UPDATE CASCADE ON DELETE RESTRICT;

-------------------------------hc_tipos_sistemas---------------------------------------------
ALTER TABLE "public"."hc_tipo_sistemas" RENAME TO "hc_tipos_sistemas";

ALTER TABLE "public"."hc_tipos_sistemas" ADD COLUMN "sw_mostrar_normal_si" CHAR(1);

ALTER TABLE "public"."hc_tipos_sistemas" ALTER COLUMN "sw_mostrar_normal_si" SET DEFAULT '1';

update hc_tipos_sistemas set sw_mostrar_normal_si='1';

ALTER TABLE "public"."hc_tipos_sistemas" ALTER COLUMN "sw_mostrar_normal_si" SET NOT NULL;

ALTER TABLE "hc_tipos_sistemas" ADD COLUMN "sw_defecto" character(1);



COMMENT ON TABLE hc_tipos_sistemas IS 'Maestro de Revison por Sistemas';
COMMENT ON COLUMN hc_tipos_sistemas.tipo_sistema_id IS 'PK Identificador de Revision por Sistemas';
COMMENT ON COLUMN hc_tipos_sistemas.nombre IS 'Descripcion del sistema';
COMMENT ON COLUMN "public"."hc_tipos_sistemas"."sw_mostrar_normal_si" IS 'identifica si se muestra un si o no o un normal o un anormal, si/no=1 normal/anormal=0';





------------------------------




--nueva tabla para tipos sistemas modulos
-------------------------hc_tipos_sistemas_modulos--------------------------------------------------
CREATE TABLE hc_tipos_sistemas_modulos (
    tipo_sistema_id integer NOT NULL,
    hc_modulo character varying(64) NOT NULL
);
ALTER TABLE ONLY hc_tipos_sistemas_modulos
    ADD PRIMARY KEY (tipo_sistema_id, hc_modulo);
ALTER TABLE ONLY hc_tipos_sistemas_modulos
    ADD FOREIGN KEY (tipo_sistema_id) REFERENCES hc_tipos_sistemas(tipo_sistema_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY hc_tipos_sistemas_modulos
    ADD FOREIGN KEY (hc_modulo) REFERENCES system_hc_modulos(hc_modulo) ON UPDATE CASCADE ON DELETE RESTRICT;
COMMENT ON TABLE hc_tipos_sistemas_modulos IS 'sirve para cruzar los sistemas que se mostrara con el modulo que se esta trabajando.';
COMMENT ON COLUMN hc_tipos_sistemas_modulos.tipo_sistema_id IS 'Fk de la tabla de los tipos de sistemas';
COMMENT ON COLUMN hc_tipos_sistemas_modulos.hc_modulo IS 'Fk Identificador del modulo de la historia clinica';


--	TABLA hc_tipo_cardiovascular
--	MODULO DE INDICE DE TRAUMA REVISADO


CREATE TABLE hc_tipo_cardiovascular (
    tipo_cardiovascular_id character varying(2) NOT NULL,
    descripcion character varying(60) NOT NULL
);

ALTER TABLE hc_tipo_cardiovascular
    ADD PRIMARY KEY (tipo_cardiovascular_id);

COMMENT ON TABLE hc_tipo_cardiovascular IS 'Tabla para capturar los rasngo de los valores Cardiovasculares';

COMMENT ON COLUMN hc_tipo_cardiovascular.tipo_cardiovascular_id IS 'Llave de la tabla';

COMMENT ON COLUMN hc_tipo_cardiovascular.descripcion IS 'Descripcion del Rango';


--	TABLA hc_tipo_region
--	MODULO DE INDICE DE TRAUMA REVISADO

CREATE TABLE hc_tipo_region (
    tipo_region_id character varying(2) NOT NULL,
    descripcion character varying(60) NOT NULL
);


ALTER TABLE hc_tipo_region
    ADD PRIMARY KEY (tipo_region_id);

COMMENT ON TABLE hc_tipo_region IS 'Tabla que contiene las regiones a evaluar';

COMMENT ON COLUMN hc_tipo_region.tipo_region_id IS 'Llave de la tabla';

COMMENT ON COLUMN hc_tipo_region.descripcion IS 'campo descriptivo de la tabla';



--	TABLA hc_tipo_respiratorio
--	MODULO DE INDICE DE TRAUMA REVISADO

CREATE TABLE hc_tipo_respiratorio (
    tipo_respiratorio_id character varying(2) NOT NULL,
    descripcion character varying(60) NOT NULL
);

ALTER TABLE hc_tipo_respiratorio
    ADD PRIMARY KEY (tipo_respiratorio_id);

COMMENT ON TABLE hc_tipo_respiratorio IS 'Tabla que contiene los valores de los rango de FR';

COMMENT ON COLUMN hc_tipo_respiratorio.tipo_respiratorio_id IS 'Llave de la Tabla';

COMMENT ON COLUMN hc_tipo_respiratorio.descripcion IS 'Descripcion de los valores de FR';



--	TABLA hc_tipo_snc
--	MODULO DE INDICE DE TRAUMA REVISADO

CREATE TABLE hc_tipo_snc (
    tipo_snc_id character varying(2) NOT NULL,
    descripcion character varying(60) NOT NULL
);

ALTER TABLE hc_tipo_snc
    ADD PRIMARY KEY (tipo_snc_id);

COMMENT ON TABLE hc_tipo_snc IS 'Tabla q contiene los valorativos del SNC';

COMMENT ON COLUMN hc_tipo_snc.tipo_snc_id IS 'Llave de la tabla';

COMMENT ON COLUMN hc_tipo_snc.descripcion IS 'Campo descriptivo del SNC';



--	TABLA hc_tipo_trauma
--	MODULO DE INDICE DE TRAUMA REVISADO

CREATE TABLE hc_tipo_trauma (
    tipo_trauma_id character varying(2) NOT NULL,
    descripcion character varying(60) NOT NULL
);

ALTER TABLE hc_tipo_trauma
    ADD PRIMARY KEY (tipo_trauma_id);

COMMENT ON TABLE hc_tipo_trauma IS 'Tabla que contiene los tipos de traumas en ITR';

COMMENT ON COLUMN hc_tipo_trauma.tipo_trauma_id IS 'Llave de la tabla';

COMMENT ON COLUMN hc_tipo_trauma.descripcion IS 'Campo descriptivo del trauma';


--	TABLA hc_indice_trauma_revisado
--	MODULO DE INDICE DE TRAUMA REVISADO

CREATE TABLE hc_indice_trauma_revisado (
    indice_trauma_revisado_id serial NOT NULL,
    ingreso integer NOT NULL,
    evolucion_id integer NOT NULL,
    fecha_registro timestamp without time zone NOT NULL,
    region character varying(2) NOT NULL,
    tipo_trauma character varying(2) NOT NULL,
    cardiovascular character varying(2) NOT NULL,
    respiratorio character varying(2) NOT NULL,
    snc character varying(2) NOT NULL,
    usuario_id integer NOT NULL
);


ALTER TABLE hc_indice_trauma_revisado
    ADD PRIMARY KEY (indice_trauma_revisado_id);

ALTER TABLE hc_indice_trauma_revisado
    ADD FOREIGN KEY (ingreso) REFERENCES ingresos(ingreso) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE hc_indice_trauma_revisado
    ADD FOREIGN KEY (evolucion_id) REFERENCES hc_evoluciones(evolucion_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE hc_indice_trauma_revisado
    ADD FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE hc_indice_trauma_revisado
    ADD FOREIGN KEY (region) REFERENCES hc_tipo_region(tipo_region_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE hc_indice_trauma_revisado
    ADD FOREIGN KEY (tipo_trauma) REFERENCES hc_tipo_trauma(tipo_trauma_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE hc_indice_trauma_revisado
    ADD FOREIGN KEY (snc) REFERENCES hc_tipo_snc(tipo_snc_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE hc_indice_trauma_revisado
    ADD FOREIGN KEY (cardiovascular) REFERENCES hc_tipo_cardiovascular(tipo_cardiovascular_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE hc_indice_trauma_revisado
    ADD FOREIGN KEY (respiratorio) REFERENCES hc_tipo_respiratorio(tipo_respiratorio_id) ON UPDATE CASCADE ON DELETE RESTRICT;

COMMENT ON TABLE hc_indice_trauma_revisado IS 'Contiene los Valorativos del ITR';

COMMENT ON COLUMN hc_indice_trauma_revisado.indice_trauma_revisado_id IS 'Llave de la Tabla';

COMMENT ON COLUMN hc_indice_trauma_revisado.ingreso IS 'Ingreso del Paciente';

COMMENT ON COLUMN hc_indice_trauma_revisado.evolucion_id IS 'Evolucion del Paciente';

COMMENT ON COLUMN hc_indice_trauma_revisado.fecha_registro IS 'Fecha de ingreso del Registro';

COMMENT ON COLUMN hc_indice_trauma_revisado.region IS 'Valor Tipo Region';

COMMENT ON COLUMN hc_indice_trauma_revisado.tipo_trauma IS 'Valor Tipo Trauma';

COMMENT ON COLUMN hc_indice_trauma_revisado.cardiovascular IS 'Valor Cardiovascular';

COMMENT ON COLUMN hc_indice_trauma_revisado.respiratorio IS 'Valor Respiratorio';

COMMENT ON COLUMN hc_indice_trauma_revisado.snc IS 'Valor del SNC';

COMMENT ON COLUMN hc_indice_trauma_revisado.usuario_id IS 'Codigo de Usuario';


