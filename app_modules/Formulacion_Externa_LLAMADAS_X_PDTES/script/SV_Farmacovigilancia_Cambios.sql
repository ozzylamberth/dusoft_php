




CREATE TABLE esm_farmaco_vigilancia
(
  esm_farmaco_id    serial not null,
  esm_tipo_id_tercero CHARACTER VARYING(3) NULL,
  esm_tercero_id CHARACTER VARYING(32) NULL,
  fecha_notificacion DATE NOT NULL,
  formula_papel CHARACTER VARYING(32) NULL,
  tipo_id_paciente 	CHARACTER VARYING(3) NOT NULL,	
  paciente_id CHARACTER VARYING(32) NOT NULL,
  fecha_sospecha DATE NULL,
  observacion text null,
  tipo_id_tercero CHARACTER VARYING(3)NULL,
  tercero_id CHARACTER VARYING(32)  NULL,
  diagnostico TEXT  NULL,
  reaccion_adversa TEXT  NULL,
  usuario_id INTEGER NOT NULL,
  fecha_registro TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT NOW()
  
);


ALTER TABLE esm_farmaco_vigilancia ADD PRIMARY KEY(esm_farmaco_id);
ALTER TABLE esm_farmaco_vigilancia  ADD FOREIGN KEY(tipo_id_tercero,tercero_id)
REFERENCES profesionales(tipo_id_tercero,tercero_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE esm_farmaco_vigilancia  ADD FOREIGN KEY (usuario_id)
REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE esm_farmaco_vigilancia ADD FOREIGN KEY(tipo_id_paciente,paciente_id)
REFERENCES pacientes(tipo_id_paciente,paciente_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE esm_farmaco_vigilancia ADD FOREIGN KEY (esm_tipo_id_tercero,esm_tercero_id) 
REFERENCES esm_empresas(tipo_id_tercero,tercero_id) ON UPDATE CASCADE ON DELETE RESTRICT;


COMMENT ON TABLE esm_farmaco_vigilancia IS 'Tabla donde se registra la farmacovigilacia para el paciente';
COMMENT ON COLUMN esm_farmaco_vigilancia.esm_farmaco_id IS '(PK) Identificador de la tabla';
COMMENT ON COLUMN esm_farmaco_vigilancia.esm_tipo_id_tercero IS '(FK) Tipo de Identificacion de la entidad';
COMMENT ON COLUMN esm_formula_externa.esm_tercero_id IS '(FK) Identificador de la entidad';
COMMENT ON COLUMN esm_farmaco_vigilancia.fecha_notificacion IS 'Fecha de notificacion';
COMMENT ON COLUMN esm_farmaco_vigilancia.formula_papel IS 'Numero  de la formula';
COMMENT ON COLUMN esm_farmaco_vigilancia.tipo_id_paciente IS '(FK) Tipo de identifificacion del paciente';
COMMENT ON COLUMN esm_farmaco_vigilancia.paciente_id IS '(FK) Identifificacion del paciente';
COMMENT ON COLUMN esm_farmaco_vigilancia.fecha_sospecha IS 'Fecha de Sospecha';
COMMENT ON COLUMN esm_farmaco_vigilancia.observacion IS 'observacion';
COMMENT ON COLUMN esm_farmaco_vigilancia.tipo_id_tercero IS '(FK) Tipo de identificacion del profesional';
COMMENT ON COLUMN esm_farmaco_vigilancia.tercero_id IS '(FK) Identifificacion del profesional';
COMMENT ON COLUMN esm_farmaco_vigilancia.diagnostico IS 'Diagnostico ingresado';

COMMENT ON COLUMN esm_farmaco_vigilancia.reaccion_adversa IS 'reacciones adversas';
COMMENT ON COLUMN esm_farmaco_vigilancia.usuario_id IS '(FK) Identificador del usuario que digitaliza la formula';
COMMENT ON COLUMN esm_farmaco_vigilancia.fecha_registro IS 'Fecha de registro de la formula digitalizada';

GRANT ALL ON TABLE esm_farmaco_vigilancia TO siis;


 ALTER TABLE public.esm_farmaco_vigilancia
 ADD COLUMN empresa_id 	character(2)  NULL;

COMMENT ON COLUMN public.esm_farmaco_vigilancia.empresa_id
 IS 'Farmacia donde se realiza la evaluacion';
 
 
 

CREATE TABLE esm_farmaco_vigilancia_d
(
  esm_farmaco_d_id  serial not null,
  esm_farmaco_id    integer not null,
  codigo_medicamento 	CHARACTER VARYING(50) NOT NULL,
  indicacion_motivo     text null,
  fecha_inicio DATE not  NULL,
  fecha_finalizacion DATE  NULL,
  fecha_registro TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT NOW()
);



ALTER TABLE esm_farmaco_vigilancia_d ADD PRIMARY KEY(esm_farmaco_d_id);
ALTER TABLE esm_farmaco_vigilancia_d  ADD FOREIGN KEY(esm_farmaco_id)
REFERENCES esm_farmaco_vigilancia(esm_farmaco_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE esm_farmaco_vigilancia_d  ADD FOREIGN KEY(codigo_medicamento)
REFERENCES inventarios_productos(codigo_producto) ON UPDATE CASCADE ON DELETE RESTRICT;



COMMENT ON TABLE esm_farmaco_vigilancia_d IS 'Tabla donde se registra el detalle de la farmocovigilancia';
COMMENT ON COLUMN esm_farmaco_vigilancia_d.esm_farmaco_d_id IS '(PK) Identificador de la tabla';
COMMENT ON COLUMN esm_farmaco_vigilancia_d.esm_farmaco_id IS '(FK) Farmacovigilancia';
COMMENT ON COLUMN esm_farmaco_vigilancia_d.codigo_medicamento IS '(FK) Identificador del medicamento';
COMMENT ON COLUMN esm_farmaco_vigilancia_d.indicacion_motivo IS 'Indicacion o motivo de la prescripcion';
COMMENT ON COLUMN esm_farmaco_vigilancia_d.fecha_inicio IS 'Fecha de Inicio de la sospecha';
COMMENT ON COLUMN esm_farmaco_vigilancia_d.fecha_finalizacion IS 'fecha final de la sospecha';
COMMENT ON COLUMN esm_farmaco_vigilancia_d.fecha_registro IS 'Fecha de registro de la formula digitalizada';

GRANT ALL ON TABLE esm_farmaco_vigilancia_d TO siis;
