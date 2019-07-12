
CREATE TABLE hc_medicamentos_recetados_hosp (
    codigo_producto character varying(10) NOT NULL,
    evolucion_id integer NOT NULL,
    ingreso integer NOT NULL,
    cantidad numeric(5,2) NOT NULL,
    observacion text,
    sw_paciente_no_pos character(1) DEFAULT 0 NOT NULL,
    via_administracion_id character varying(2),
    dosis numeric(5,2),
    unidad_dosificacion character varying(50) NOT NULL,
    tipo_opcion_posologia_id smallint,
    sw_estado character(1) DEFAULT 1
);


ALTER TABLE ONLY hc_medicamentos_recetados_hosp
    ADD CONSTRAINT hc_medicamentos_recetados_hosp_pkey PRIMARY KEY (codigo_producto, evolucion_id);



ALTER TABLE ONLY hc_medicamentos_recetados_hosp
    ADD CONSTRAINT "$1" FOREIGN KEY (codigo_producto) REFERENCES inventarios_productos(codigo_producto) ON UPDATE CASCADE ON DELETE RESTRICT;



ALTER TABLE ONLY hc_medicamentos_recetados_hosp
    ADD CONSTRAINT "$2" FOREIGN KEY (evolucion_id) REFERENCES hc_evoluciones(evolucion_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY hc_medicamentos_recetados_hosp
    ADD CONSTRAINT "$3" FOREIGN KEY (unidad_dosificacion) REFERENCES hc_unidades_dosificacion(unidad_dosificacion) ON UPDATE CASCADE ON DELETE RESTRICT;



ALTER TABLE ONLY hc_medicamentos_recetados_hosp
    ADD CONSTRAINT "$4" FOREIGN KEY (ingreso) REFERENCES ingresos(ingreso) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY hc_medicamentos_recetados_hosp
    ADD CONSTRAINT "$5" FOREIGN KEY (via_administracion_id) REFERENCES hc_vias_administracion(via_administracion_id) ON UPDATE CASCADE ON DELETE RESTRICT;



CREATE TABLE hc_justificaciones_no_pos_hosp (
    hc_justificaciones_no_pos_hosp serial NOT NULL,
    evolucion_id integer NOT NULL,
    codigo_producto character varying(10) NOT NULL,
    usuario_id_autoriza integer,
    duracion character varying(256) NOT NULL,
    dosis_dia character varying(40),
    justificacion text,
    ventajas_medicamento text,
    ventajas_tratamiento text,
    precauciones text,
    controles_evaluacion_efectividad text,
    tiempo_respuesta_esperado character varying(50),
    riesgo_inminente text,
    sw_riesgo_inminente character(1),
    sw_agotadas_posibilidades_existentes character(1),
    sw_comercializacion_pais character(1),
    sw_homologo_pos character(1),
    descripcion_caso_clinico text,
    sw_existe_alternativa_pos character(1)
);


ALTER TABLE  hc_justificaciones_no_pos_hosp
    ADD  PRIMARY KEY (hc_justificaciones_no_pos_hosp);


ALTER TABLE  hc_justificaciones_no_pos_hosp
    ADD  FOREIGN KEY (usuario_id_autoriza) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE  hc_justificaciones_no_pos_hosp
    ADD  FOREIGN KEY (codigo_producto, evolucion_id) REFERENCES hc_medicamentos_recetados_hosp(codigo_producto, evolucion_id) ON UPDATE CASCADE ON DELETE CASCADE;



CREATE TABLE hc_posologia_horario_op1_hosp (
    codigo_producto character varying(10) NOT NULL,
    evolucion_id integer NOT NULL,
    periocidad_id smallint NOT NULL,
    tiempo character varying(15) NOT NULL
);

ALTER TABLE  hc_posologia_horario_op1_hosp
    ADD  PRIMARY KEY (codigo_producto, evolucion_id);



ALTER TABLE  hc_posologia_horario_op1_hosp
    ADD FOREIGN KEY (codigo_producto, evolucion_id) REFERENCES hc_medicamentos_recetados_hosp(codigo_producto, evolucion_id) ON UPDATE CASCADE ON DELETE RESTRICT;



ALTER TABLE  hc_posologia_horario_op1_hosp
    ADD FOREIGN KEY (periocidad_id) REFERENCES hc_periocidad(periocidad_id) ON UPDATE CASCADE ON DELETE RESTRICT;



		CREATE TABLE hc_posologia_horario_op2_hosp (
    codigo_producto character varying(10) NOT NULL,
    evolucion_id integer NOT NULL,
    duracion_id character(2) NOT NULL
);




ALTER TABLE  hc_posologia_horario_op2_hosp
    ADD  PRIMARY KEY (codigo_producto, evolucion_id);



ALTER TABLE  hc_posologia_horario_op2_hosp
    ADD  FOREIGN KEY (codigo_producto, evolucion_id) REFERENCES hc_medicamentos_recetados_hosp(codigo_producto, evolucion_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE  hc_posologia_horario_op2_hosp
    ADD  FOREIGN KEY (duracion_id) REFERENCES hc_horario(duracion_id) ON UPDATE CASCADE ON DELETE RESTRICT;




CREATE TABLE hc_posologia_horario_op3_hosp (
    codigo_producto character varying(10) NOT NULL,
    evolucion_id integer NOT NULL,
    sw_estado_momento character(1) NOT NULL,
    sw_estado_desayuno character(1) DEFAULT 0 NOT NULL,
    sw_estado_almuerzo character(1) DEFAULT 0 NOT NULL,
    sw_estado_cena character(1) DEFAULT 0 NOT NULL
);


ALTER TABLE  hc_posologia_horario_op3_hosp
    ADD  PRIMARY KEY (codigo_producto, evolucion_id);


ALTER TABLE  hc_posologia_horario_op3_hosp
    ADD  FOREIGN KEY (codigo_producto, evolucion_id) REFERENCES hc_medicamentos_recetados_hosp(codigo_producto, evolucion_id) ON UPDATE CASCADE ON DELETE RESTRICT;





CREATE TABLE hc_posologia_horario_op4_hosp (
    codigo_producto character varying(10) NOT NULL,
    evolucion_id integer NOT NULL,
    hora_especifica character varying(5) NOT NULL
);



ALTER TABLE  hc_posologia_horario_op4_hosp
    ADD  PRIMARY KEY (codigo_producto, evolucion_id, hora_especifica);



ALTER TABLE  hc_posologia_horario_op4_hosp
    ADD  FOREIGN KEY (codigo_producto, evolucion_id) REFERENCES hc_medicamentos_recetados_hosp(codigo_producto, evolucion_id) ON UPDATE CASCADE ON DELETE RESTRICT;





CREATE TABLE hc_posologia_horario_op5_hosp (
    codigo_producto character varying(10) NOT NULL,
    evolucion_id integer NOT NULL,
    frecuencia_suministro text NOT NULL
);



ALTER TABLE  hc_posologia_horario_op5_hosp
    ADD  PRIMARY KEY (codigo_producto, evolucion_id);


ALTER TABLE  hc_posologia_horario_op5_hosp
    ADD  FOREIGN KEY (codigo_producto, evolucion_id) REFERENCES hc_medicamentos_recetados_hosp(codigo_producto, evolucion_id) ON UPDATE CASCADE ON DELETE RESTRICT;






CREATE TABLE hc_justificaciones_no_pos_hosp_diagnostico (
    hc_justificaciones_no_pos_hosp integer NOT NULL,
    diagnostico_id character varying(10) NOT NULL
);



ALTER TABLE  hc_justificaciones_no_pos_hosp_diagnostico
    ADD PRIMARY KEY (hc_justificaciones_no_pos_hosp, diagnostico_id);


ALTER TABLE  hc_justificaciones_no_pos_hosp_diagnostico
    ADD  FOREIGN KEY (diagnostico_id) REFERENCES diagnosticos(diagnostico_id) ON UPDATE CASCADE ON DELETE RESTRICT;



ALTER TABLE  hc_justificaciones_no_pos_hosp_diagnostico
    ADD  FOREIGN KEY (hc_justificaciones_no_pos_hosp) REFERENCES hc_justificaciones_no_pos_hosp(hc_justificaciones_no_pos_hosp) ON UPDATE CASCADE ON DELETE CASCADE;




CREATE TABLE hc_justificaciones_no_pos_hosp_respuestas_pos (
    alternativa_pos_id serial NOT NULL,
    medicamento_pos character varying(60),
    principio_activo character varying(60),
    dosis_dia_pos character varying(20),
    duracion_pos character varying(20),
    sw_no_mejoria character(1),
    sw_reaccion_secundaria character(1),
    reaccion_secundaria character varying(256),
    sw_contraindicacion character(1),
    contraindicacion character varying(256),
    otras character varying(256),
    hc_justificaciones_no_pos_hosp integer
);



ALTER TABLE ONLY hc_justificaciones_no_pos_hosp_respuestas_pos
    ADD CONSTRAINT hc_justificaciones_no_pos_hosp_respuestas_pos_pkey PRIMARY KEY (alternativa_pos_id);



ALTER TABLE ONLY hc_justificaciones_no_pos_hosp_respuestas_pos
    ADD CONSTRAINT "$1" FOREIGN KEY (hc_justificaciones_no_pos_hosp) REFERENCES hc_justificaciones_no_pos_hosp(hc_justificaciones_no_pos_hosp) ON UPDATE CASCADE ON DELETE CASCADE;


--nov/03/2004

CREATE TABLE hc_control_suministro_medicamentos (
    hc_control_suministro_id serial NOT NULL,
    codigo_producto character varying(10) NOT NULL,
    evolucion_id integer NOT NULL,
    usuario_id_control integer NOT NULL,
    fecha_realizado timestamp without time zone NOT NULL,
    fecha_registro_control timestamp without time zone NOT NULL,
    cantidad_suministrada numeric(5,2) NOT NULL,
    observacion text
);


ALTER TABLE ONLY hc_control_suministro_medicamentos
    ADD CONSTRAINT hc_control_suministro_medicamentos_pkey PRIMARY KEY (hc_control_suministro_id);


ALTER TABLE ONLY hc_control_suministro_medicamentos
    ADD CONSTRAINT "$1" FOREIGN KEY (codigo_producto, evolucion_id) REFERENCES hc_medicamentos_recetados_hosp(codigo_producto, evolucion_id) ON UPDATE CASCADE ON DELETE RESTRICT;


--se comentario en el envio a tulua del 22/nov porque en tulua no existia.
drop table hc_notas_suministro_medicamentos;

CREATE TABLE hc_notas_suministro_medicamentos (
    hc_nota_suministro_id serial NOT NULL,
    codigo_producto character varying(10) NOT NULL,
    evolucion_id integer NOT NULL,
    observacion text NOT NULL,
    tipo_observacion character(1) DEFAULT 0 NOT NULL,
    usuario_id_nota integer NOT NULL,
    fecha_registro_nota timestamp without time zone NOT NULL
);



ALTER TABLE ONLY hc_notas_suministro_medicamentos
    ADD CONSTRAINT hc_notas_suministro_medicamentos_pkey PRIMARY KEY (hc_nota_suministro_id, codigo_producto, evolucion_id);



ALTER TABLE ONLY hc_notas_suministro_medicamentos
    ADD CONSTRAINT "$1" FOREIGN KEY (codigo_producto, evolucion_id) REFERENCES hc_medicamentos_recetados_hosp(codigo_producto, evolucion_id) ON UPDATE CASCADE ON DELETE RESTRICT;

