

/* FORMULACION TEMPORAL FINAL  CABECERA  todo bien  */

CREATE TABLE esm_formula_externa_tmp
(
  tmp_formula_id SERIAL NOT NULL,
  tmp_empresa_id CHARACTER(2) NOT NULL,
  tmp_formula_papel CHARACTER VARYING(30) NOT NULL,
  fecha_formula DATE NOT NULL,
  hora_formula CHARACTER(5) NOT NULL DEFAULT '00:00',
  tipo_formula integer NOT null,
  tipo_evento_id     INTEGER NULL,
  tipo_fuerza_id    INTEGER NULL,
  tipo_id_tercero CHARACTER VARYING(3)  NOT NULL,
  tercero_id CHARACTER VARYING(32)  NOT  NULL,
  tipo_id_paciente 	CHARACTER VARYING(3) NOT NULL,	
  paciente_id CHARACTER VARYING(32) NOT NULL,
  plan_id INTEGER NOT NULL,
  rango CHARACTER VARYING(40) NULL,
  tipo_afiliado_id CHARACTER VARYING(2) NULL,
  semanas_cotizadas INTEGER NULL, 
  esm_tipo_id_tercero CHARACTER VARYING(3) NOT NULL,
  esm_tercero_id CHARACTER VARYING(32) NOT NULL,
  esm_autoriza_tipo_id_tercero CHARACTER VARYING(3)  NULL,
  esm_autoriza_tercero_id CHARACTER VARYING(32)  NULL,
  ips_tipo_id_tercero CHARACTER VARYING(3)  NULL,
  ips_tercero_id CHARACTER VARYING(32) NULL,
  ips_profesional_tipo_id_tercero CHARACTER VARYING(3)  NULL,
  ips_profesional_tercero_id CHARACTER VARYING(32) NULL,
  costo_formula       NUMERIC(18,4) NULL,
  usuario_id INTEGER NOT NULL,
  fecha_registro TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT NOW(),
  tipo_formulacion CHARACTER(2)   not null 
);


ALTER TABLE esm_formula_externa_tmp ADD PRIMARY KEY(tmp_formula_id);

ALTER TABLE esm_formula_externa_tmp ADD FOREIGN KEY (tipo_formula) 
REFERENCES esm_tipos_formulas(tipo_formula_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE esm_formula_externa_tmp ADD FOREIGN KEY (tipo_evento_id) 
REFERENCES esm_tipos_eventos(tipo_evento_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE esm_formula_externa_tmp ADD FOREIGN KEY (tipo_fuerza_id) 
REFERENCES esm_tipos_fuerzas(tipo_fuerza_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE esm_formula_externa_tmp ADD FOREIGN KEY(tipo_id_tercero,tercero_id,esm_tipo_id_tercero,esm_tercero_id)
REFERENCES  esm_profesionales_empresas(tipo_id_tercero,tercero_id,tipo_id_tercero_esm, tercero_id_esm) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE esm_formula_externa_tmp ADD FOREIGN KEY(tipo_id_paciente,paciente_id)
REFERENCES pacientes(tipo_id_paciente,paciente_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE esm_formula_externa_tmp ADD FOREIGN KEY(plan_id)
REFERENCES planes(plan_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE esm_formula_externa_tmp ADD FOREIGN KEY(esm_autoriza_tipo_id_tercero,esm_autoriza_tercero_id,esm_tipo_id_tercero,esm_tercero_id)
REFERENCES  esm_profesionales_empresas(tipo_id_tercero,tercero_id,tipo_id_tercero_esm, tercero_id_esm) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE esm_formula_externa_tmp ADD FOREIGN KEY(ips_tipo_id_tercero,ips_tercero_id,ips_profesional_tipo_id_tercero,ips_profesional_tercero_id)
REFERENCES  esm_ips_profesionales(tipo_id_tercero_ips,tercero_id_ips,tipo_id_tercero, tercero_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE esm_formula_externa_tmp ADD FOREIGN KEY(usuario_id)
REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE esm_formula_externa_tmp ADD FOREIGN KEY(tmp_empresa_id)
REFERENCES empresas(empresa_id) ON UPDATE CASCADE ON DELETE RESTRICT;

 


COMMENT ON TABLE esm_formula_externa_tmp IS 'Tabla donde se registra la digitalizacion de una formula medica externa Tmp';
COMMENT ON COLUMN esm_formula_externa_tmp.tmp_formula_id IS '(PK) Identificador de la tabla';
COMMENT ON COLUMN esm_formula_externa_tmp.tmp_empresa_id IS '(FK) Identificador de la empresa donde se transcribe la formula';
COMMENT ON COLUMN esm_formula_externa_tmp.tmp_formula_papel IS 'Numero de la formula relacionada en el papel';
COMMENT ON COLUMN esm_formula_externa_tmp.fecha_formula IS 'Fecha de expedicion de la formula';
COMMENT ON COLUMN esm_formula_externa_tmp.hora_formula IS 'Hora de expedicion de la formula';
COMMENT ON COLUMN esm_formula_externa.tipo_formula IS 'Tipo de Formula  ';
COMMENT ON COLUMN esm_formula_externa_tmp.tipo_evento_id IS '(FK) Tipo de evento';
COMMENT ON COLUMN esm_formula_externa_tmp.tipo_fuerza_id IS '(FK) Tipo de fuerza militar';
COMMENT ON COLUMN esm_formula_externa_tmp.tipo_id_tercero IS '(FK) Tipo de identificacion del profesional';
COMMENT ON COLUMN esm_formula_externa_tmp.tercero_id IS '(FK) Identifificacion del profesional';
COMMENT ON COLUMN esm_formula_externa_tmp.tipo_id_paciente IS '(FK) Tipo de identifificacion del paciente';
COMMENT ON COLUMN esm_formula_externa_tmp.paciente_id IS '(FK) Identifificacion del paciente';
COMMENT ON COLUMN esm_formula_externa_tmp.plan_id IS '(FK) Plan del afiliado';
COMMENT ON COLUMN esm_formula_externa_tmp.tipo_afiliado_id IS '(FK) Tipo de afiliado';
COMMENT ON COLUMN esm_formula_externa_tmp.rango IS '(FK) Rango del afiliado';
COMMENT ON COLUMN esm_formula_externa_tmp.semanas_cotizadas IS 'Semanas de cotizacion';
COMMENT ON COLUMN esm_formula_externa_tmp.esm_tipo_id_tercero IS '(FK) Tipo de Identificacion de la entidad de la cual provien la formula';
COMMENT ON COLUMN esm_formula_externa_tmp.esm_tercero_id IS '(FK) Identificador de la entidad de la cual provien la formula';
COMMENT ON COLUMN esm_formula_externa_tmp.esm_autoriza_tipo_id_tercero IS '(FK) Tipo de identificacion del  profesional autoriza';
COMMENT ON COLUMN esm_formula_externa_tmp.esm_autoriza_tercero_id IS '(FK) Tipo de identificacion del  profesional  autoriza ';
COMMENT ON COLUMN esm_formula_externa_tmp.ips_tipo_id_tercero IS '(FK) Tipo de Identificacion de la entidad de la cual provien la formula';
COMMENT ON COLUMN esm_formula_externa_tmp.ips_tercero_id IS '(FK) Identificador de la entidad de la cual provien la formula';
COMMENT ON COLUMN esm_formula_externa_tmp.ips_profesional_tipo_id_tercero IS '(FK) Tipo de identificacion del  profesional  de la ips';
COMMENT ON COLUMN esm_formula_externa_tmp.ips_profesional_tercero_id IS '(FK) Tipo de identificacion del  profesional  de la ips ';
COMMENT ON COLUMN esm_formula_externa_tmp.costo_formula IS 'costo de la formula ';
COMMENT ON COLUMN esm_formula_externa_tmp.usuario_id IS '(FK) Identificador del usuario que digitaliza la formula';
COMMENT ON COLUMN esm_formula_externa_tmp.fecha_registro IS 'Fecha de registro de la formula digitalizada';
COMMENT ON COLUMN esm_formula_externa_tmp.tipo_formulacion IS '0=>Interna 1=>Externa';





/* DIAGNOSTICOS DE LA FORMULA TMp */

CREATE TABLE esm_formula_externa_diagnosticos_tmp
(
  tmp_formula_id     integer  null,
  usuario_id        integer not null,
  tipo_id_paciente 	CHARACTER VARYING(3) NOT NULL,	
  paciente_id CHARACTER VARYING(32) NOT NULL,
  diagnostico_id CHARACTER VARYING(6) NOT NULL
);

ALTER TABLE esm_formula_externa_diagnosticos_tmp ADD PRIMARY KEY(tipo_id_paciente,paciente_id,diagnostico_id);
ALTER TABLE esm_formula_externa_diagnosticos_tmp  ADD FOREIGN KEY(diagnostico_id)
REFERENCES diagnosticos(diagnostico_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE esm_formula_externa_diagnosticos_tmp  ADD FOREIGN KEY(tmp_formula_id)
REFERENCES esm_formula_externa_tmp(tmp_formula_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE esm_formula_externa_diagnosticos_tmp ADD FOREIGN KEY(usuario_id)
REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;

COMMENT ON TABLE esm_formula_externa_diagnosticos_tmp IS 'Tabla donde se registran los diagnosticos  tmp asociados a cada fomula digitalizada';
COMMENT ON COLUMN esm_formula_externa_diagnosticos_tmp.tmp_formula_id IS '(FK) Identificador de la formula digitalizada';
COMMENT ON COLUMN esm_formula_externa_diagnosticos_tmp.usuario_id IS '(FK) Identificador del usuario ';
COMMENT ON COLUMN esm_formula_externa_diagnosticos_tmp.tipo_id_paciente IS '(FK) Tipo de identifificacion del paciente';
COMMENT ON COLUMN esm_formula_externa_diagnosticos_tmp.paciente_id IS '(FK) Identifificacion del paciente';
COMMENT ON COLUMN esm_formula_externa_diagnosticos_tmp.diagnostico_id IS '(FK) Identificador del diagnostico asociado';





/*   FORMULACION DE MEDICAMENTOS */

CREATE TABLE esm_formula_externa_medicamentos_tmp
(
  fe_medicamento_id SERIAL NOT NULL,
  tmp_formula_id     integer not null,
  codigo_producto CHARACTER VARYING(40) NOT NULL,
  cantidad NUMERIC(7,2) NOT NULL,
  observacion TEXT,
  dosis NUMERIC(7,2)  NULL,
  unidad_dosificacion CHARACTER VARYING(50)  NULL,
  tiempo_tratamiento INTEGER NULL,
  unidad_tiempo_tratamiento CHARACTER(1) NULL,
  periodicidad_entrega INTEGER NULL,
  unidad_periodicidad_entrega CHARACTER(1) NULL,
  via_administracion_id  character varying(2) NULL,
  usuario_id  integer NULL,
  tipo_id_paciente CHARACTER VARYING(3) NULL,
  paciente_id CHARACTER VARYING(32) NULL
   
);
ALTER TABLE esm_formula_externa_medicamentos_tmp ADD PRIMARY KEY(fe_medicamento_id);

ALTER TABLE esm_formula_externa_medicamentos_tmp  ADD FOREIGN KEY(tmp_formula_id)
REFERENCES esm_formula_externa_tmp(tmp_formula_id) ON UPDATE CASCADE ON DELETE CASCADE;


ALTER TABLE esm_formula_externa_medicamentos_tmp ADD FOREIGN KEY(codigo_producto)
REFERENCES inventarios_productos(codigo_producto) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE esm_formula_externa_medicamentos_tmp ADD FOREIGN KEY(usuario_id)
REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE esm_formula_externa_medicamentos_tmp ADD FOREIGN KEY(unidad_dosificacion)
REFERENCES hc_unidades_dosificacion(unidad_dosificacion) ON UPDATE CASCADE ON DELETE RESTRICT;

COMMENT ON TABLE esm_formula_externa_medicamentos_tmp IS 'Tabla donde se registran los items de la formula digitalizada Tmp';
COMMENT ON COLUMN esm_formula_externa_medicamentos_tmp.fe_medicamento_id IS '(PK) Identificador de la tabla';
COMMENT ON COLUMN esm_formula_externa_medicamentos_tmp.tmp_formula_id IS '(FK) Identificador de la formula digitalizada';
COMMENT ON COLUMN esm_formula_externa_medicamentos_tmp.codigo_producto IS '(FK) Codigo del producto formulado';
COMMENT ON COLUMN esm_formula_externa_medicamentos_tmp.cantidad IS 'Cantidad del medicamento formulado';
COMMENT ON COLUMN esm_formula_externa_medicamentos_tmp.observacion IS 'Observacion a la formulacion del medicamento';
COMMENT ON COLUMN esm_formula_externa_medicamentos_tmp.dosis IS 'Dosis del medicamento';
COMMENT ON COLUMN esm_formula_externa_medicamentos_tmp.unidad_dosificacion IS '(FK) Unidad de dosificacion asociada al medicamento';
COMMENT ON COLUMN esm_formula_externa_medicamentos_tmp.tiempo_tratamiento IS 'Tiempo de tratamiento del medicamento';
COMMENT ON COLUMN esm_formula_externa_medicamentos_tmp.unidad_tiempo_tratamiento IS 'Unidad de tiempo 4->dias,3->semanas 2->mense 1->año';
COMMENT ON COLUMN esm_formula_externa_medicamentos_tmp.periodicidad_entrega IS 'Periodicidad de entrega';
COMMENT ON COLUMN esm_formula_externa_medicamentos_tmp.unidad_periodicidad_entrega IS 'Unidad de tiempo 4->dias,3->semanas 2->mense 1->año';

COMMENT ON COLUMN esm_formula_externa_medicamentos_tmp.tipo_id_paciente IS '(FK) Tipo de identifificacion del paciente';
COMMENT ON COLUMN esm_formula_externa_medicamentos_tmp.paciente_id IS '(FK) Identifificacion del paciente';


/* POSOLOGIA DE LA FORMULACION  tmp*/


CREATE TABLE esm_formula_externa_posologia_tmp
(
  fe_medicamento_id INTEGER NOT NULL,
  opcion CHARACTER(1) NOT NULL,
  hora_especifica TEXT,
  sw_estado_momento CHARACTER(1),
  sw_estado_desayuno CHARACTER(1),
  sw_estado_almuerzo CHARACTER(1),
  sw_estado_cena CHARACTER(1),
  duracion_id CHARACTER(2),
  periocidad_id SMALLINT, 	
  tiempo CHARACTER VARYING(15)
);

ALTER TABLE esm_formula_externa_posologia_tmp ADD PRIMARY KEY(fe_medicamento_id);
ALTER TABLE esm_formula_externa_posologia_tmp ADD FOREIGN KEY(fe_medicamento_id)
REFERENCES esm_formula_externa_medicamentos_tmp(fe_medicamento_id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE esm_formula_externa_posologia_tmp ADD FOREIGN KEY (duracion_id) REFERENCES hc_horario(duracion_id) ON UPDATE CASCADE ON DELETE RESTRICT;

COMMENT ON TABLE  esm_formula_externa_posologia_tmp IS 'Tabla donde se guarda la posologia o frecuencia del item de la formula digitalizada';
COMMENT ON COLUMN esm_formula_externa_posologia_tmp.fe_medicamento_id  IS '(PK - FK) Identificador del item de la formula digitalizada';
COMMENT ON COLUMN esm_formula_externa_posologia_tmp.opcion  IS 'Identificador de la opcion de la 1 a la 4';
COMMENT ON COLUMN esm_formula_externa_posologia_tmp.hora_especifica  IS 'Registros de la opcion 4';
COMMENT ON COLUMN esm_formula_externa_posologia_tmp.sw_estado_momento  IS 'Registros de la opcion 3';
COMMENT ON COLUMN esm_formula_externa_posologia_tmp.sw_estado_desayuno  IS 'Registros de la opcion 3';
COMMENT ON COLUMN esm_formula_externa_posologia_tmp.sw_estado_almuerzo  IS 'Registros de la opcion 3';
COMMENT ON COLUMN esm_formula_externa_posologia_tmp.sw_estado_cena  IS 'Registros de la opcion 3';
COMMENT ON COLUMN esm_formula_externa_posologia_tmp.duracion_id  IS 'Registros de la opcion 2';
COMMENT ON COLUMN esm_formula_externa_posologia_tmp.periocidad_id  IS 'Registros de la opcion 1';
COMMENT ON COLUMN esm_formula_externa_posologia_tmp.tiempo  IS 'Registros de la opcion 1';

GRANT ALL ON TABLE esm_formula_externa_posologia_tmp TO siis;

ALTER TABLE esm_formula_externa_posologia_tmp ADD sw_durante_tratamiento CHARACTER(1) DEFAULT '0';

COMMENT ON COLUMN esm_formula_externa_posologia_tmp.sw_durante_tratamiento  IS 'Registros de la opcion 3';


/*  FORMULA REAL  CABECERA real  */

CREATE TABLE esm_formula_externa
(
  formula_id SERIAL NOT NULL,
  empresa_id CHARACTER(2) NOT NULL,
  formula_papel CHARACTER VARYING(30) NOT NULL,
  tipo_dispensacion_id CHARACTER VARYING(5)  NULL,
  fecha_formula DATE NOT NULL,
  hora_formula CHARACTER(5) NOT NULL DEFAULT '00:00',
  tipo_formula integer NOT null,
  tipo_evento_id     INTEGER NULL,
  tipo_fuerza_id    INTEGER NULL,
  tipo_id_tercero CHARACTER VARYING(3)  NOT NULL,
  tercero_id CHARACTER VARYING(32)  NOT  NULL,
  tipo_id_paciente 	CHARACTER VARYING(3) NOT NULL,	
  paciente_id CHARACTER VARYING(32) NOT NULL,
  plan_id INTEGER NOT NULL,
  rango CHARACTER VARYING(40) NULL,
  tipo_afiliado_id CHARACTER VARYING(2) NULL,
  semanas_cotizadas INTEGER NULL, 
  esm_tipo_id_tercero CHARACTER VARYING(3) NOT NULL,
  esm_tercero_id CHARACTER VARYING(32) NOT NULL,
  esm_autoriza_tipo_id_tercero CHARACTER VARYING(3)  NULL,
  esm_autoriza_tercero_id CHARACTER VARYING(32)  NULL,
  ips_tipo_id_tercero CHARACTER VARYING(3)  NULL,
  ips_tercero_id CHARACTER VARYING(32) NULL,
  ips_profesional_tipo_id_tercero CHARACTER VARYING(3)  NULL,
  ips_profesional_tercero_id CHARACTER VARYING(32) NULL,
  costo_formula       NUMERIC(18,4) NULL,
  sw_estado           CHARACTER VARYING(1) NOT  NULL  DEFAULT '1',
  sw_corte            CHARACTER VARYING(1) NOT  NULL  DEFAULT '0',
  usuario_id INTEGER NOT NULL,
  fecha_registro TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT NOW()
    
);


ALTER TABLE esm_formula_externa ADD PRIMARY KEY(formula_id);
ALTER TABLE esm_formula_externa ADD FOREIGN KEY(empresa_id)
REFERENCES empresas(empresa_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE esm_formula_externa ADD FOREIGN KEY(tipo_dispensacion_id)
REFERENCES inv_farmacias_tiposdispensacion(tipo_dispensacion_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE esm_formula_externa ADD FOREIGN KEY (tipo_formula) 
REFERENCES esm_tipos_formulas(tipo_formula_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE esm_formula_externa ADD FOREIGN KEY (tipo_evento_id) 
REFERENCES esm_tipos_eventos(tipo_evento_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE esm_formula_externa ADD FOREIGN KEY (tipo_fuerza_id) 
REFERENCES esm_tipos_fuerzas(tipo_fuerza_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE esm_formula_externa ADD FOREIGN KEY(tipo_id_tercero,tercero_id,esm_tipo_id_tercero,esm_tercero_id)
REFERENCES  esm_profesionales_empresas(tipo_id_tercero,tercero_id,tipo_id_tercero_esm, tercero_id_esm) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE esm_formula_externa ADD FOREIGN KEY(tipo_id_paciente,paciente_id)
REFERENCES pacientes(tipo_id_paciente,paciente_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE esm_formula_externa ADD FOREIGN KEY (plan_id, rango, tipo_afiliado_id) 
REFERENCES planes_rangos(plan_id, rango, tipo_afiliado_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE esm_formula_externa ADD FOREIGN KEY(esm_autoriza_tipo_id_tercero,esm_autoriza_tercero_id,esm_tipo_id_tercero,esm_tercero_id)
REFERENCES  esm_profesionales_empresas(tipo_id_tercero,tercero_id,tipo_id_tercero_esm, tercero_id_esm) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE esm_formula_externa ADD FOREIGN KEY(ips_tipo_id_tercero,ips_tercero_id,ips_profesional_tipo_id_tercero,ips_profesional_tercero_id)
REFERENCES  esm_ips_profesionales(tipo_id_tercero_ips,tercero_id_ips,tipo_id_tercero, tercero_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE esm_formula_externa ADD FOREIGN KEY(usuario_id)
REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE esm_formula_externa ADD FOREIGN KEY(plan_id)
REFERENCES planes(plan_id) ON UPDATE CASCADE ON DELETE RESTRICT;
GRANT ALL ON TABLE esm_formula_externa TO siis;









COMMENT ON TABLE esm_formula_externa IS 'Tabla donde se registra la digitalizacion de una formula medica externa';
COMMENT ON COLUMN esm_formula_externa.formula_id IS '(PK) Identificador de la tabla';
COMMENT ON COLUMN esm_formula_externa.empresa_id IS '(FK) Identificador de la empresa donde se transcribe la formula';
COMMENT ON COLUMN esm_formula_externa.formula_papel IS 'Numero de la formula relacionada en el papel';
COMMENT ON COLUMN esm_formula_externa.tipo_dispensacion_id IS '(FK) Tipo de dispensacion asociado a la formula medica';
COMMENT ON COLUMN esm_formula_externa.fecha_formula IS 'Fecha de expedicion de la formula';
COMMENT ON COLUMN esm_formula_externa.hora_formula IS 'Hora de expedicion de la formula';
COMMENT ON COLUMN esm_formula_externa.tipo_formula IS 'Tipo de Formula  ';
COMMENT ON COLUMN esm_formula_externa.tipo_evento_id IS '(FK) Tipo de evento';
COMMENT ON COLUMN esm_formula_externa.tipo_fuerza_id IS '(FK) Tipo de fuerza militar';
COMMENT ON COLUMN esm_formula_externa.tipo_id_tercero IS '(FK) Tipo de identificacion del profesional';
COMMENT ON COLUMN esm_formula_externa.tercero_id IS '(FK) Identifificacion del profesional';
COMMENT ON COLUMN esm_formula_externa.tipo_id_paciente IS '(FK) Tipo de identifificacion del paciente';
COMMENT ON COLUMN esm_formula_externa.paciente_id IS '(FK) Identifificacion del paciente';
COMMENT ON COLUMN esm_formula_externa.plan_id IS '(FK) Plan del afiliado';
COMMENT ON COLUMN esm_formula_externa.tipo_afiliado_id IS '(FK) Tipo de afiliado';
COMMENT ON COLUMN esm_formula_externa.rango IS '(FK) Rango del afiliado';
COMMENT ON COLUMN esm_formula_externa.semanas_cotizadas IS 'Semanas de cotizacion';
COMMENT ON COLUMN esm_formula_externa.esm_tipo_id_tercero IS '(FK) Tipo de Identificacion de la entidad de la cual provien la formula';
COMMENT ON COLUMN esm_formula_externa.esm_tercero_id IS '(FK) Identificador de la entidad de la cual provien la formula';
COMMENT ON COLUMN esm_formula_externa.esm_autoriza_tipo_id_tercero IS '(FK) Tipo de identificacion del  profesional autoriza';
COMMENT ON COLUMN esm_formula_externa.esm_autoriza_tercero_id IS '(FK) Tipo de identificacion del  profesional  autoriza ';
COMMENT ON COLUMN esm_formula_externa_tmp.ips_tipo_id_tercero IS '(FK) Tipo de Identificacion de la entidad de la cual provien la formula';
COMMENT ON COLUMN esm_formula_externa_tmp.ips_tercero_id IS '(FK) Identificador de la entidad de la cual provien la formula';
COMMENT ON COLUMN esm_formula_externa_tmp.ips_profesional_tipo_id_tercero IS '(FK) Tipo de identificacion del  profesional  de la ips';
COMMENT ON COLUMN esm_formula_externa_tmp.ips_profesional_tercero_id IS '(FK) Tipo de identificacion del  profesional  de la ips ';
COMMENT ON COLUMN esm_formula_externa_tmp.costo_formula IS 'costo de la formula ';
COMMENT ON COLUMN esm_formula_externa.sw_estado IS 'estado de la Formula 0=>Inactiva, 1=>Activa, 2=>Anulada';
COMMENT ON COLUMN esm_formula_externa.sw_corte IS '0=>No pertenece a un corte 1=>pertenece a un corte';
COMMENT ON COLUMN esm_formula_externa.usuario_id IS '(FK) Identificador del usuario que digitaliza la formula';
COMMENT ON COLUMN esm_formula_externa.fecha_registro IS 'Fecha de registro de la formula digitalizada';

/* TABLA REAL PARA EL DIAGNOSTICO D ELA FORMULA */


CREATE TABLE esm_formula_externa_diagnosticos
(
  fe_diagnostico_id SERIAL NOT NULL,
  formula_id INTEGER NOT NULL,
  diagnostico_id CHARACTER VARYING(6) NOT NULL
);

ALTER TABLE esm_formula_externa_diagnosticos ADD PRIMARY KEY(fe_diagnostico_id);
ALTER TABLE esm_formula_externa_diagnosticos ADD FOREIGN KEY(formula_id)
REFERENCES esm_formula_externa(formula_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE esm_formula_externa_diagnosticos  ADD FOREIGN KEY(diagnostico_id)
REFERENCES diagnosticos(diagnostico_id) ON UPDATE CASCADE ON DELETE RESTRICT;

COMMENT ON TABLE esm_formula_externa_diagnosticos IS 'Tabla donde se registran los diagnosticos asociados a cada fomula digitalizada';
COMMENT ON COLUMN esm_formula_externa_diagnosticos.formula_id IS '(FK) Identificador de la formula digitalizada';
COMMENT ON COLUMN esm_formula_externa_diagnosticos.diagnostico_id IS '(FK) Identificador del diagnostico asociado';
COMMENT ON COLUMN esm_formula_externa_diagnosticos.fe_diagnostico_id IS '(PK) Identificador de la tabla';

GRANT ALL ON TABLE esm_formula_externa_diagnosticos TO siis;


/*  FORMULACION REAL DEL MEDICAMENT */



CREATE TABLE esm_formula_externa_medicamentos
(
  fe_medicamento_id SERIAL NOT NULL,
  formula_id INTEGER NOT NULL,
  codigo_producto CHARACTER VARYING(40) NOT NULL,
  cantidad NUMERIC(7,2) NOT NULL,
  observacion TEXT,
  dosis NUMERIC(7,2)  NULL,
  unidad_dosificacion CHARACTER VARYING(50)  NULL,
  tiempo_tratamiento INTEGER,
  unidad_tiempo_tratamiento CHARACTER(1),
  periodicidad_entrega INTEGER,
  unidad_periodicidad_entrega CHARACTER(1),
  via_administracion_id  character varying(2) NULL
);
ALTER TABLE esm_formula_externa_medicamentos ADD PRIMARY KEY(fe_medicamento_id);
ALTER TABLE esm_formula_externa_medicamentos ADD FOREIGN KEY(formula_id)
REFERENCES esm_formula_externa(formula_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE esm_formula_externa_medicamentos ADD FOREIGN KEY(codigo_producto)
REFERENCES inventarios_productos(codigo_producto) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE esm_formula_externa_medicamentos ADD FOREIGN KEY(unidad_dosificacion)
REFERENCES hc_unidades_dosificacion(unidad_dosificacion) ON UPDATE CASCADE ON DELETE RESTRICT;

COMMENT ON TABLE esm_formula_externa_medicamentos IS 'Tabla donde se registran los items de la formula digitalizada';
COMMENT ON COLUMN esm_formula_externa_medicamentos.fe_medicamento_id IS '(PK) Identificador de la tabla';
COMMENT ON COLUMN esm_formula_externa_medicamentos.formula_id IS '(FK) Identificador de la formula digitalizada';
COMMENT ON COLUMN esm_formula_externa_medicamentos.codigo_producto IS '(FK) Codigo del producto formulado';
COMMENT ON COLUMN esm_formula_externa_medicamentos.cantidad IS 'Cantidad del medicamento formulado';
COMMENT ON COLUMN esm_formula_externa_medicamentos.observacion IS 'Observacion a la formulacion del medicamento';
COMMENT ON COLUMN esm_formula_externa_medicamentos.dosis IS 'Dosis del medicamento';
COMMENT ON COLUMN esm_formula_externa_medicamentos.unidad_dosificacion IS '(FK) Unidad de dosificacion asociada al medicamento';
COMMENT ON COLUMN esm_formula_externa_medicamentos.tiempo_tratamiento IS 'Tiempo de tratamiento del medicamento';
COMMENT ON COLUMN esm_formula_externa_medicamentos.unidad_tiempo_tratamiento IS 'Unidad de tiempo 4->dias,3->semanas 2->mense 1->año';
COMMENT ON COLUMN esm_formula_externa_medicamentos.periodicidad_entrega IS 'Periodicidad de entrega';
COMMENT ON COLUMN esm_formula_externa_medicamentos.unidad_periodicidad_entrega IS 'Unidad de tiempo 4->dias,3->semanas 2->mense 1->año';
COMMENT ON COLUMN esm_formula_externa_medicamentos.via_administracion_id IS 'Via de Administracion del medicamento';

GRANT ALL ON TABLE esm_formula_externa_medicamentos TO siis;

ALTER TABLE public.esm_formula_externa_medicamentos
 ALTER COLUMN unidad_dosificacion
 DROP NOT NULL;
 ALTER TABLE public.esm_formula_externa_medicamentos
 ALTER COLUMN dosis
 DROP NOT NULL;
 
 
/*  TABLA REAL PARA LA POSOLOGIA */

CREATE TABLE esm_formula_externa_posologia
(
  fe_medicamento_id INTEGER NOT NULL,
  opcion CHARACTER(1) NOT NULL,
  hora_especifica TEXT,
  sw_estado_momento CHARACTER(1),
  sw_estado_desayuno CHARACTER(1),
  sw_estado_almuerzo CHARACTER(1),
  sw_estado_cena CHARACTER(1),
  duracion_id CHARACTER(2),
  periocidad_id SMALLINT, 	
  tiempo CHARACTER VARYING(15)
);

ALTER TABLE esm_formula_externa_posologia ADD PRIMARY KEY(fe_medicamento_id);
ALTER TABLE esm_formula_externa_posologia ADD FOREIGN KEY(fe_medicamento_id)
REFERENCES esm_formula_externa_medicamentos(fe_medicamento_id) ON UPDATE CASCADE ON DELETE RESTRICT;

COMMENT ON TABLE esm_formula_externa_posologia IS 'Tabla donde se guarda la posologia o frecuencia del item de la formula digitalizada';
COMMENT ON COLUMN esm_formula_externa_posologia.fe_medicamento_id  IS '(PK - FK) Identificador del item de la formula digitalizada';
COMMENT ON COLUMN esm_formula_externa_posologia.opcion  IS 'Identificador de la opcion de la 1 a la 4';
COMMENT ON COLUMN esm_formula_externa_posologia.hora_especifica  IS 'Registros de la opcion 4';
COMMENT ON COLUMN esm_formula_externa_posologia.sw_estado_momento  IS 'Registros de la opcion 3';
COMMENT ON COLUMN esm_formula_externa_posologia.sw_estado_desayuno  IS 'Registros de la opcion 3';
COMMENT ON COLUMN esm_formula_externa_posologia.sw_estado_almuerzo  IS 'Registros de la opcion 3';
COMMENT ON COLUMN esm_formula_externa_posologia.sw_estado_cena  IS 'Registros de la opcion 3';
COMMENT ON COLUMN esm_formula_externa_posologia.duracion_id  IS 'Registros de la opcion 2';
COMMENT ON COLUMN esm_formula_externa_posologia.periocidad_id  IS 'Registros de la opcion 1';
COMMENT ON COLUMN esm_formula_externa_posologia.tiempo  IS 'Registros de la opcion 1';

GRANT ALL ON TABLE esm_formula_externa_posologia TO siis;

ALTER TABLE esm_formula_externa_posologia ADD FOREIGN KEY (duracion_id) REFERENCES hc_horario(duracion_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE esm_formula_externa_posologia ADD cantidad_veces numeric(7,2)  NULL;
/* */

ALTER TABLE esm_formula_externa_posologia ADD sw_durante_tratamiento CHARACTER(1) DEFAULT '0';


COMMENT ON COLUMN esm_formula_externa_posologia.sw_durante_tratamiento  IS 'Registros de la opcion 3';


CREATE TRIGGER actualizar_cantidad_veces_dia
 BEFORE INSERT OR UPDATE
 ON public.esm_formula_externa_posologia
 FOR EACH ROW
 EXECUTE PROCEDURE public.calcular_veces_medicamento();

/* PERMISOS */

CREATE TABLE userpermisos_digitalizacion
(
  empresa_id CHARACTER(2) NOT NULL,
  usuario_id INTEGER NOT NULL,
  sw_activo CHARACTER(1) NOT NULL DEFAULT '1',
  sw_privilegios  CHARACTER(1) NOT NULL DEFAULT '0'
);

ALTER TABLE userpermisos_digitalizacion ADD PRIMARY KEY(empresa_id,usuario_id);
ALTER TABLE userpermisos_digitalizacion ADD FOREIGN KEY(empresa_id)
REFERENCES empresas(empresa_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE userpermisos_digitalizacion ADD FOREIGN KEY(usuario_id)
REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;

COMMENT ON TABLE userpermisos_digitalizacion IS 'Tabla donde se registra el permiso sobre el modulo de digitalizacion de formulas';
COMMENT ON COLUMN userpermisos_digitalizacion.empresa_id IS '(PK - FK) Identificador de la empresa a la que tiene permiso el usuario';
COMMENT ON COLUMN userpermisos_digitalizacion.usuario_id IS '(PK - FK) Identificador del usuario';
COMMENT ON COLUMN userpermisos_digitalizacion.sw_activo IS 'Identifica si el permiso sigue activo';
COMMENT ON COLUMN userpermisos_digitalizacion.sw_privilegios IS '0=>No tiene privilegios, 1=>Privilegios Basicos, 2=>privilegios especiales';

GRANT ALL ON TABLE userpermisos_digitalizacion TO siis;


CREATE TABLE userpermisos_Formulacion_Externa
(
	empresa_id     CHARACTER(2) NOT NULL,
	centro_utilidad   CHARACTER VARYING(2) NOT NULL,
	usuario_id     INTEGER   NOT NULL,
	sw_activo      CHARACTER(1) NOT NULL DEFAULT '1'
);

ALTER TABLE userpermisos_Formulacion_Externa ADD PRIMARY KEY (empresa_id,usuario_id);
ALTER TABLE userpermisos_Formulacion_Externa ADD FOREIGN KEY (empresa_id,centro_utilidad)
REFERENCES centros_utilidad(empresa_id, centro_utilidad) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE userpermisos_Formulacion_Externa ADD FOREIGN KEY (usuario_id)
REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;

COMMENT ON TABLE userpermisos_Formulacion_Externa IS ' Tabla donde se registra el permiso para el modulo de formulacion Externa';
COMMENT ON COLUMN userpermisos_Formulacion_Externa.empresa_id IS '(PK-FK) Identificador de la empresa a la que tiene permiso el usuario';
COMMENT ON COLUMN userpermisos_Formulacion_Externa.usuario_id IS '(PK-FK) Identificador del usuario';
COMMENT ON COLUMN userpermisos_Formulacion_Externa.sw_activo IS 'Identifica si el permiso sigue activo';

GRANT ALL ON TABLE userpermisos_Formulacion_Externa TO siis;

ALTER TABLE public.esm_formula_externa_medicamentos_tmp
 ALTER COLUMN unidad_dosificacion
 DROP NOT NULL;
 ALTER TABLE public.esm_formula_externa_medicamentos_tmp
 ALTER COLUMN dosis
 DROP NOT NULL;

ALTER TABLE public.esm_formula_externa_medicamentos_tmp
 ADD COLUMN sw_marcado char NOT NULL DEFAULT 0;

COMMENT ON COLUMN public.esm_formula_externa_medicamentos_tmp.sw_marcado
 IS 'Define si el producto esta marcado para no dispensar (1) y si no tiene inconvenientes (0)';
 
 
 ALTER TABLE public.esm_formula_externa_medicamentos
 ADD COLUMN sw_marcado char NOT NULL DEFAULT 0;

COMMENT ON COLUMN public.esm_formula_externa_medicamentos.sw_marcado
 IS 'Define si el producto esta marcado para no dispensar (1) y si no tiene inconvenientes (0)';
 
 
ALTER TABLE public.esm_pendientes_por_dispensar
DROP CONSTRAINT esm_pendientes_por_dispensar_codigo_medicamento_fkey;

ALTER TABLE public.esm_pendientes_por_dispensar
ADD CONSTRAINT esm_pendientes_por_dispensar_codigo_medicamento_fkey
FOREIGN KEY (codigo_medicamento)
REFERENCES public.inventarios_productos(codigo_producto)
ON DELETE RESTRICT
ON UPDATE CASCADE;
 
 
 
ALTER TABLE public.esm_dispensacion_medicamentos_tmp
DROP CONSTRAINT esm_dispensacion_medicamentos_tmp_codigo_producto_fkey;

ALTER TABLE public.esm_dispensacion_medicamentos_tmp
ADD CONSTRAINT esm_dispensacion_medicamentos_tmp_codigo_producto_fkey
FOREIGN KEY (codigo_producto)
REFERENCES public.inventarios_productos(codigo_producto)
ON DELETE RESTRICT
ON UPDATE CASCADE;



ALTER TABLE public.esm_formula_externa_medicamentos
 ADD COLUMN cantidad_periodo NUMERIC(7,2)  NULL;
/* PARA CONSULTAR CON HUGO*/
 /* ALTER TABLE public.pacientes_metricas
ADD CONSTRAINT foreign_key01
FOREIGN KEY (tipo_id_paciente, paciente_id)
REFERENCES public.pacientes(tipo_id_paciente, paciente_id)
ON DELETE RESTRICT
ON UPDATE CASCADE;*/

ALTER TABLE public.esm_formula_externa_medicamentos
 ADD COLUMN sw_autorizado  CHARACTER(1) NOT NULL DEFAULT '0';
 

COMMENT ON COLUMN public.esm_formula_externa_medicamentos.sw_autorizado
 IS 'Define si el producto Esta autorizado para despachar por medio de autorizacion (0)sin autorizacion (1)autorizado';
 

ALTER TABLE public.esm_formula_externa_medicamentos
 ADD COLUMN usuario_autoriza_id  integer  NULL;

COMMENT ON COLUMN public.esm_formula_externa_medicamentos.usuario_autoriza_id
 IS 'usuario que autorizo el despacho';
 
 
 ALTER TABLE public.esm_formula_externa_medicamentos
 ADD COLUMN observacion_autorizacion  text  NULL;
 
 COMMENT ON COLUMN public.esm_formula_externa_medicamentos.observacion_autorizacion
 IS 'observacion dela autorizacion';
 
 ALTER TABLE public.esm_formula_externa_medicamentos
 ADD COLUMN fecha_registro_autorizacion  TIMESTAMP WITHOUT TIME ZONE  NULL ;
 
  COMMENT ON COLUMN public.esm_formula_externa_medicamentos.fecha_registro_autorizacion
 IS 'Fecha de la Autorizacion';
 
 ALTER TABLE public.bodegas_documentos
ALTER COLUMN observacion
TYPE text;

 ALTER TABLE public.userpermisos_digitalizacion add 
 COLUMN bodega	character varying(2) 	
 NULL ;
 
  ALTER TABLE public.userpermisos_digitalizacion add 
 COLUMN centro_utilidad	character varying(2) 	
 NULL ;
 
 
 
  
ALTER TABLE public.userpermisos_digitalizacion
DROP CONSTRAINT userpermisos_digitalizacion_empresa_id_fkey;

ALTER TABLE  userpermisos_digitalizacion  ADD FOREIGN KEY (empresa_id,centro_utilidad,bodega)
REFERENCES bodegas(empresa_id, centro_utilidad,bodega) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE public.esm_empresas_topes
ADD COLUMN saldo_minimo numeric(18,4) NOT NULL DEFAULT 0;

COMMENT ON COLUMN public.esm_empresas_topes.saldo_minimo
IS 'Saldo minimo de un Tope';



 
 
 
