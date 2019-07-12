ALTER TABLE "informacion_bd" ADD COLUMN "dbhost1" character varying(15);
update informacion_bd set dbhost1=dbhost;
ALTER TABLE "informacion_bd" ALTER COLUMN "dbhost1" SET NOT NULL;
ALTER TABLE "informacion_bd" DROP COLUMN "dbhost";
ALTER TABLE "informacion_bd" RENAME COLUMN "dbhost1" TO "dbhost";
ALTER TABLE "informacion_bd" ADD COLUMN "dbname1" character varying(40);
update informacion_bd set dbname1=dbname;
ALTER TABLE "informacion_bd" ALTER COLUMN "dbname1" SET NOT NULL;
ALTER TABLE "informacion_bd" DROP COLUMN "dbname";
ALTER TABLE "informacion_bd" RENAME COLUMN "dbname1" TO "dbname";

------------------------creacion autorizaciones_electronicas_sos------------------------
CREATE TABLE autorizaciones_electronicas_sos (
  "autorizacion_electronica_sos_id" SERIAL,
  "autorizacion" INTEGER NOT NULL,
  "validez" DATE NOT NULL,
  "codigo_autorizacion" VARCHAR(40) DEFAULT ''::character varying NOT NULL,
  "observaciones" TEXT DEFAULT ''::text NOT NULL,
  PRIMARY KEY("autorizacion_electronica_sos_id", "autorizacion"),
  FOREIGN KEY ("autorizacion")
    REFERENCES "public"."autorizaciones"("autorizacion")
    ON DELETE CASCADE
    ON UPDATE CASCADE
    NOT DEFERRABLE
);
COMMENT ON TABLE "public"."autorizaciones_electronicas"
IS 'Autorizaci� que se realiza por medio del sistema de informacion de la SOS.';

-------------------------------cambio departamentos--------------------------------------
ALTER TABLE departamentos ADD COLUMN codigo_alterno VARCHAR(20);
COMMENT ON COLUMN "public"."departamentos"."codigo_alterno"
IS 'Codigo que identifica valores alternos para la integracion';

-------------------------------system_hc_modulos-------------------------------------
INSERT INTO "public"."system_hc_modulos" ("hc_modulo", "descripcion", "activo", "tipo_finalidad_id", "rips_tipo_id")
VALUES ('Odontologia','Odontologia','1','10','AC');

-------------------------------system_hc_submodulos-------------------------------------
INSERT INTO "public"."system_hc_submodulos" ("submodulo", "descripcion", "version_numero", "version_info", "activo", "sexo_id", "gestacion", "edad_max", "edad_min")
VALUES ('SignosVitalesOdontologia','Signos Vitales',1,'','1',NULL,NULL,NULL,NULL);
INSERT INTO "public"."system_hc_submodulos" ("submodulo", "descripcion", "version_numero", "version_info", "activo", "sexo_id", "gestacion", "edad_max", "edad_min")
VALUES ('MotivoConsultaBasico','Motivo de la Consulta',1,'','1',NULL,NULL,NULL,NULL);

-------------------------------historias_clinicas_templates------------------------------
INSERT INTO "public"."historias_clinicas_templates" ("hc_modulo", "submodulo", "paso", "secuencia", "hc_seccion_id", "sw_mostrar", "sw_siquiatria")
VALUES ('Odontologia','MotivoConsultaBasico',0,0,'1','1','0');

-------------------------------planes_ocultar-----------------------------------------------
CREATE TABLE "public"."planes_ocultar" (
  "plan_id" INTEGER NOT NULL,
  "sw_ocultar" CHAR(1) DEFAULT '1'::bpchar NOT NULL,
  CONSTRAINT "planes_ocultar_pkey" PRIMARY KEY("plan_id"),
  FOREIGN KEY ("plan_id")
    REFERENCES "public"."planes"("plan_id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);

COMMENT ON TABLE "public"."planes_ocultar" IS 'tabla que sirve para ocultar de algunas pantallas la informacion no necesaria';
COMMENT ON COLUMN "public"."planes_ocultar"."plan_id" IS 'llave identificadora del plan';
COMMENT ON COLUMN "public"."planes_ocultar"."sw_ocultar" IS 'switche para identificar si se muestra o no se muestra';


---------------------------hc_incapacidades-------------------------------------------------------
COMMENT ON TABLE hc_incapacidades IS 'se registra las diferentes incapacidades de los pacientes en las atenciones';
COMMENT ON COLUMN hc_incapacidades.evolucion_id IS 'Pk:Fk Identificador de la atencion del paciente';
COMMENT ON COLUMN hc_incapacidades.observacion_incapacidad IS 'descripcion de la incapacidad del paciente';
COMMENT ON COLUMN hc_incapacidades.tipo_incapacidad_id IS 'tipo de incapacidad del paciente';
COMMENT ON COLUMN hc_incapacidades.dias_de_incapacidad IS 'dias que se conceden de incapacidad al paciente';

-----------------------------hc_medicamentos_recetados---------------------------------------------
COMMENT ON TABLE hc_medicamentos_recetados IS 'Define los diferentes medicamentos que se le recetan al paciente en cada evolucion';
COMMENT ON COLUMN hc_medicamentos_recetados.medicamento_id IS 'PK:FK Identificaci� del medicamento';
COMMENT ON COLUMN hc_medicamentos_recetados.cantidad_total IS 'Cantidad total a suministrar del medicamento, de acuerdo al horario y la cantidad del medicamento';
COMMENT ON COLUMN hc_medicamentos_recetados.via_administracion_id IS 'FK via de administraci� del medicamento';
COMMENT ON COLUMN hc_medicamentos_recetados.cantidad IS 'Cantidad a suministrar del medicamento';
COMMENT ON COLUMN hc_medicamentos_recetados.horario IS 'Horario en el cual se suministra el medicamento (cada 4,8,12,24 horas, o en algunos casos minutos))';
COMMENT ON COLUMN hc_medicamentos_recetados.desayuno IS 'Si se le aplica con el desayuno';
COMMENT ON COLUMN hc_medicamentos_recetados.almuerzo IS 'Si se le aplica con el almuerzo';
COMMENT ON COLUMN hc_medicamentos_recetados.comida IS 'Si se le aplica con la comida';
COMMENT ON COLUMN hc_medicamentos_recetados.sw_rango IS 'Determina si el medicamento se da (0=>Antes,1=>Durante,2=>Despues) de una o todas las comidas (Desayuno,Almuerzo,Comida)';
COMMENT ON COLUMN hc_medicamentos_recetados.observaciones IS 'Observaci� por parte del medico';
COMMENT ON COLUMN hc_medicamentos_recetados.indicacion_suministro IS 'Nota aclaratoria sobre el suministro del medicamento';
COMMENT ON COLUMN hc_medicamentos_recetados.fecha IS 'la fecha en la cual se ordena el medicamento por parte del medico';
COMMENT ON COLUMN hc_medicamentos_recetados.evolucion_id IS 'PK:FK numero de la evoluci� actual';
COMMENT ON COLUMN hc_medicamentos_recetados.sw_estado IS 'Estado del medicamento (Suspender=0,Vigente=1,Finalizar=2)';
COMMENT ON COLUMN hc_medicamentos_recetados.sw_pos IS 'FK identificacion de la empresa';
COMMENT ON COLUMN hc_medicamentos_recetados.justificacion_no_pos_id IS 'FK Justificacion de los medicamentos NO P.O.S que se recetan';
COMMENT ON COLUMN hc_medicamentos_recetados.usuario_id IS 'FK id del usuario actual del sistema';
COMMENT ON COLUMN hc_medicamentos_recetados.empresa_id IS 'FK identificacion de la empresa';
COMMENT ON COLUMN hc_medicamentos_recetados.centro_utilidad IS 'FK identificacion del centro de utilidad';
COMMENT ON COLUMN hc_medicamentos_recetados.bodega IS 'FK identificacion de la bodega donde se encuentra el medicamento';

------------------------------hc_justificaciones_no_pos_amb_diagnostico----------------------------------
COMMENT ON TABLE hc_justificaciones_no_pos_amb_diagnostico IS 'Mantiene los diferentes diagnosticos de la justificacion de medicamentos no pos';
COMMENT ON COLUMN hc_justificaciones_no_pos_amb_diagnostico.hc_justificaciones_no_pos_amb IS 'identificacion de la justificacion del medicamento';
COMMENT ON COLUMN hc_justificaciones_no_pos_amb_diagnostico.diagnostico_id IS 'llave foranea que identifica el diagnostico';

--------------------------hc_justificacion_no_pos_amb-------------------------------------------------
COMMENT ON TABLE hc_justificaciones_no_pos_amb IS 'guarda la justificacion del medicamento no pos solicitado al paciente.';
COMMENT ON COLUMN hc_justificaciones_no_pos_amb.hc_justificaciones_no_pos_amb IS 'llave que identifica la justificacion del medicamento';
COMMENT ON COLUMN hc_justificaciones_no_pos_amb.evolucion_id IS 'identificacion de la atencion al paciente';
COMMENT ON COLUMN hc_justificaciones_no_pos_amb.codigo_producto IS 'codigo que identifica el mediamento que se receto al paciente';
COMMENT ON COLUMN hc_justificaciones_no_pos_amb.usuario_id_autoriza IS 'usuario que diligencia la justificacion del medicamento';
COMMENT ON COLUMN hc_justificaciones_no_pos_amb.duracion IS 'duracion del tratamiento con el medicamento no pos';
COMMENT ON COLUMN hc_justificaciones_no_pos_amb.dosis_dia IS 'dosis que debe ingerir el paciente por toma';
COMMENT ON COLUMN hc_justificaciones_no_pos_amb.justificacion IS 'texto donde se encuentra la justificacion del medicamento';
COMMENT ON COLUMN hc_justificaciones_no_pos_amb.ventajas_medicamento IS 'ventaja del medicamento no pos';
COMMENT ON COLUMN hc_justificaciones_no_pos_amb.ventajas_tratamiento IS 'ventajas de tratamiento no pos';
COMMENT ON COLUMN hc_justificaciones_no_pos_amb.precauciones IS 'precauciones que se deben tener con el medicamento no pos';
COMMENT ON COLUMN hc_justificaciones_no_pos_amb.controles_evaluacion_efectividad IS 'controles que se debe tener con el medicamento no pos';
COMMENT ON COLUMN hc_justificaciones_no_pos_amb.tiempo_respuesta_esperado IS 'tiempo que se demora en tener una repuesta del medicamento no pos';
COMMENT ON COLUMN hc_justificaciones_no_pos_amb.riesgo_inminente IS 'posible riesgo que pueda tener el medicamneto no pos';
COMMENT ON COLUMN hc_justificaciones_no_pos_amb.sw_riesgo_inminente IS 'switche para identificar el riesgo inminente';
COMMENT ON COLUMN hc_justificaciones_no_pos_amb.sw_agotadas_posibilidades_existentes IS 'switche para identificar que ya se agotaron todas las posibilidades';
COMMENT ON COLUMN hc_justificaciones_no_pos_amb.sw_comercializacion_pais IS 'switche para identificar que se comercializa o no en el pais';
COMMENT ON COLUMN hc_justificaciones_no_pos_amb.sw_homologo_pos IS 'switche para identificar si existe medicamento homologo en el pos';
COMMENT ON COLUMN hc_justificaciones_no_pos_amb.descripcion_caso_clinico IS 'descripcion del caso clinico del paciente de manera resumida';
COMMENT ON COLUMN hc_justificaciones_no_pos_amb.sw_existe_alternativa_pos IS 'switche para determinar que existe alternativa en el pos';

-----------------------------------hc_justificaciones_no pos_respuestas_pos------------------------------------------
COMMENT ON TABLE hc_justificaciones_no_pos_respuestas_pos IS 'describe las posibilidades pos con las que se ha tratado al paciente';
COMMENT ON COLUMN hc_justificaciones_no_pos_respuestas_pos.alternativa_pos_id IS 'identificacion de las alternativas pos utilizadas';
COMMENT ON COLUMN hc_justificaciones_no_pos_respuestas_pos.medicamento_pos IS 'nombre del medicamento pos utilizado';
COMMENT ON COLUMN hc_justificaciones_no_pos_respuestas_pos.principio_activo IS 'principio activo del medicamento pos con el cual se ha tratado al paciente';
COMMENT ON COLUMN hc_justificaciones_no_pos_respuestas_pos.dosis_dia_pos IS 'dosis por dia del medicamento pos con el cual se ha tratado el paciente';
COMMENT ON COLUMN hc_justificaciones_no_pos_respuestas_pos.duracion_pos IS 'duracion del tratamiento pos con el cual se ha tratado al paciente';
COMMENT ON COLUMN hc_justificaciones_no_pos_respuestas_pos.sw_no_mejoria IS 'switche para identificar se no existe mejoria en el paciente';
COMMENT ON COLUMN hc_justificaciones_no_pos_respuestas_pos.sw_reaccion_secundaria IS 'switche para identificar la reaccion secundaria en el paciente con el tratamiento pos';
COMMENT ON COLUMN hc_justificaciones_no_pos_respuestas_pos.reaccion_secundaria IS 'descripcion de la reaccion secundaria del paciente con el medicamento pos';
COMMENT ON COLUMN hc_justificaciones_no_pos_respuestas_pos.sw_contraindicacion IS 'switche para indicar las contraindicaciones del medicamento pos con el que se ha tratado al paciente';
COMMENT ON COLUMN hc_justificaciones_no_pos_respuestas_pos.contraindicacion IS 'descripcion de la contraindicacion del medicamento no pos que se ha suministrado al paciente';
COMMENT ON COLUMN hc_justificaciones_no_pos_respuestas_pos.otras IS 'otras indicacion del medicamento pos suministrado al paciente';
COMMENT ON COLUMN hc_justificaciones_no_pos_respuestas_pos.hc_justificaciones_no_pos_amb IS 'identificacion de la justificacion del medicamento';

----------------------------------hc_posologia_horario_op1------------------------------------------------------------
COMMENT ON TABLE hc_posologia_horario_op1 IS 'registra la posologia del medicamento solicitado al paciente';
COMMENT ON COLUMN hc_posologia_horario_op1.codigo_producto IS 'codigo del producto solicitado al paciente';
COMMENT ON COLUMN hc_posologia_horario_op1.evolucion_id IS 'identificacion de la atencion del paciente';
COMMENT ON COLUMN hc_posologia_horario_op1.periocidad_id IS 'la periocidad con la que se debe toma el medicamento el paciente';
COMMENT ON COLUMN hc_posologia_horario_op1.tiempo IS 'tiempo para determinar la periocidad del medicamento';

----------------------------------hc_posologia_horario_op2------------------------------------------------------------
COMMENT ON TABLE hc_posologia_horario_op2 IS 'registra la posologia del medicamento solicitado al paciente';
COMMENT ON COLUMN hc_posologia_horario_op2.codigo_producto IS 'codigo del producto solicitado al paciente';
COMMENT ON COLUMN hc_posologia_horario_op2.evolucion_id IS 'identificacion de la atencion del paciente';
COMMENT ON COLUMN hc_posologia_horario_op2.duracion_id IS 'llave foranea que determina la duracion con la que debe ingerir el medicamento el paciente';

----------------------------------hc_posologia_horario_op3------------------------------------------------------------
COMMENT ON TABLE hc_posologia_horario_op3 IS 'registra la posologia del medicamento solicitado al paciente';
COMMENT ON COLUMN hc_posologia_horario_op3.codigo_producto IS 'codigo del producto solicitado al paciente';
COMMENT ON COLUMN hc_posologia_horario_op3.evolucion_id IS 'identificacion de la atencion del paciente';
COMMENT ON COLUMN hc_posologia_horario_op3.sw_estado_momento IS 'momento de la comida que se debe tomar el medicamento';
COMMENT ON COLUMN hc_posologia_horario_op3.sw_estado_desayuno IS 'horario de comida';
COMMENT ON COLUMN hc_posologia_horario_op3.sw_estado_almuerzo IS 'horario de comida';
COMMENT ON COLUMN hc_posologia_horario_op3.sw_estado_cena IS 'horario de comida';

----------------------------------hc_posologia_horario_op4------------------------------------------------------------
COMMENT ON TABLE hc_posologia_horario_op4 IS 'registra la posologia del medicamento solicitado al paciente';
COMMENT ON COLUMN hc_posologia_horario_op4.codigo_producto IS 'codigo del producto solicitado al paciente';
COMMENT ON COLUMN hc_posologia_horario_op4.evolucion_id IS 'identificacion de la atencion del paciente';
COMMENT ON COLUMN hc_posologia_horario_op4.hora_especifica IS 'identifica una hora especifica a la cual se debe tomar el medicamento';

----------------------------------hc_posologia_horario_op5------------------------------------------------------------
COMMENT ON TABLE hc_posologia_horario_op5 IS 'registra la posologia del medicamento solicitado al paciente';
COMMENT ON COLUMN hc_posologia_horario_op5.codigo_producto IS 'codigo del producto solicitado al paciente';
COMMENT ON COLUMN hc_posologia_horario_op5.evolucion_id IS 'identificacion de la atencion del paciente';
COMMENT ON COLUMN hc_posologia_horario_op5.frecuencia_suministro IS 'describe en un texto la forma como el paciente debe consumir el documento';

----------------------------------hc_periocidad-----------------------------------------------------------------------
COMMENT ON TABLE hc_periocidad IS 'mantiene la periocidad con la que se debe tomar el paciente el medicamento';
COMMENT ON COLUMN hc_periocidad.periocidad_id IS 'periocidad con la que el paciente se debe tomar el medicamento';
COMMENT ON COLUMN hc_periocidad.periocidad_indice_orden IS 'indice de orden para mostrar la periocidad';

---------------------------------------hc_horario----------------------------------------------------------------------
COMMENT ON TABLE hc_horario IS 'datos para realizar la presentacion de los diferentes horarios que existen';
COMMENT ON COLUMN hc_horario.duracion_id IS 'llave para identificar los diferentes horarios';
COMMENT ON COLUMN hc_horario.descripcion IS 'descripcion de los diferentes horarios';

------------------------------------hc_os_solicitudes----------------------------------------------------------------
COMMENT ON TABLE hc_os_solicitudes IS 'mantiene todo tipo de solicitudes que se realizan a un paciente en la historia clinica o por fuera de ella.';
COMMENT ON COLUMN hc_os_solicitudes.hc_os_solicitud_id IS 'llave que identifica las solicitudes que se le han realizado al paciente';
COMMENT ON COLUMN hc_os_solicitudes.cargo IS 'cargo que se ha solicitado al paciente';
COMMENT ON COLUMN hc_os_solicitudes.evolucion_id IS 'identificacion de la atencion del paciente, puede ser nulo ya que la solicitud puede ser hecha por fuera d ela historia';
COMMENT ON COLUMN hc_os_solicitudes.plan_id IS 'plan con el cual se esta realizando la solicitud';
COMMENT ON COLUMN hc_os_solicitudes.os_tipo_solicitud_id IS 'identifica el tipo de solicitud que se realizo al paciente';
COMMENT ON COLUMN hc_os_solicitudes.sw_estado IS 'identifica el estado de la solicitud';
COMMENT ON COLUMN hc_os_solicitudes.cantidad IS 'cantidad de ese cargo que se solicito';

--------------------------------------hc_os_solicitudes_apoyod---------------------------------------------------------
COMMENT ON TABLE hc_os_solicitudes_apoyod IS 'identifica la observacion y el tipo de solicitud realizada';
COMMENT ON COLUMN hc_os_solicitudes_apoyod.hc_os_solicitud_id IS 'llave forane para identificar la solicitud';
COMMENT ON COLUMN hc_os_solicitudes_apoyod.observacion IS 'documenta la observacion de la solicitud de apoyo diagnostico';
COMMENT ON COLUMN hc_os_solicitudes_apoyod.apoyod_tipo_id IS 'llave foranea que identifica el tipo de apoyo diagnostico solicitado';

--------------------------------------apoyod_tipos-----------------------------------------------------------------------
COMMENT ON TABLE apoyod_tipos IS 'identifica los grupos tipos cargos que se utilizan en los apoyos diagnosticos';
COMMENT ON COLUMN apoyod_tipos.apoyod_tipo_id IS 'llave foranea de grupos tipo cargos';
COMMENT ON COLUMN apoyod_tipos.descripcion IS 'nueva descripcion de grupos tipos cargos';
COMMENT ON COLUMN apoyod_tipos.sw_lectura IS 'switche para identificar si los apoyos diagnosticos necesitan lectura o no';

-------------------------------------hc_os_solicitudes_diagnosticos-------------------------------------------------------
COMMENT ON TABLE hc_os_solicitudes_diagnosticos IS 'guarda los diferentes diagnosticos para la solicitud realizada al paciente.';
COMMENT ON COLUMN hc_os_solicitudes_diagnosticos.hc_os_solicitud_id IS 'llave foranea para identificar la solicitud al paciente';
COMMENT ON COLUMN hc_os_solicitudes_diagnosticos.diagnostico_id IS 'llave foranea que identifica el diagnostico de la solicitud';

--------------------------------------diagnosticos----------------------------------------------------------------------
COMMENT ON TABLE diagnosticos IS 'tabla maestra de diagnosticos medicos CIE10';
COMMENT ON COLUMN diagnosticos.diagnostico_id IS 'codigo del diagnostico';
COMMENT ON COLUMN diagnosticos.diagnostico_nombre IS 'Nombre del diagnostico medico';
COMMENT ON COLUMN diagnosticos.nivel IS 'nivel en el que se encuentra el diagnostico';

--------------------------------------hc_os_solicitudes_interconsultas------------------------------------------------------
COMMENT ON TABLE hc_os_solicitudes_interconsultas IS 'guarda las caracteristicas especiales de la solicitud de interconsulta.';
COMMENT ON COLUMN hc_os_solicitudes_interconsultas.hc_os_solicitud_id IS 'llave foranea que identifica la solicitud';
COMMENT ON COLUMN hc_os_solicitudes_interconsultas.especialidad IS 'llave foranea que identifica la especialidad de interconsulta';
COMMENT ON COLUMN hc_os_solicitudes_interconsultas.observacion IS 'aclaraciones especiales sobre la interconsutla';

-------------------------------------especialidades------------------------------------------------------------------------
COMMENT ON TABLE especialidades IS 'Tabla en donde se encuentran las diferentes especialidades existentes';
COMMENT ON COLUMN especialidades.especialidad IS 'PK Llave para identificar las especialides existentes';
COMMENT ON COLUMN especialidades.descripcion IS 'Descripci� de la especialidad.';
COMMENT ON COLUMN especialidades.sw_anestesiologo IS 'switche para identificar si la especialidad es relacionada con anestesiologia';
COMMENT ON COLUMN especialidades.sw_pediatra IS 'switche para identificar si la especialidad es relacionada con pediatria';
COMMENT ON COLUMN especialidades.sw_circulante IS 'switche para identificar si la especialidad es relacionada con circulante';
COMMENT ON COLUMN especialidades.sw_instrumentista IS 'switche para identificar si la especialidad es relacionada con instrumentista';
COMMENT ON COLUMN especialidades.sw_cirujano IS 'switche para identificar si la especialidad es relacionada con cirujano';

--------------------------------------hc_os_autorizaciones-------------------------------------------------------------
COMMENT ON TABLE hc_os_autorizaciones IS 'cada solicitud realizada a un paciente debe pasar por un sistema de autorizacion, esta autorizacion se relaciona a la solicitud en esta tabla.';
COMMENT ON COLUMN hc_os_autorizaciones.hc_os_solicitud_id IS 'llave foranea que identifica la solicitud';
COMMENT ON COLUMN hc_os_autorizaciones.autorizacion_int IS 'autorizacion interna realizada a la solicitud';
COMMENT ON COLUMN hc_os_autorizaciones.autorizacion_ext IS 'autorizacion externa realizada a la solicitud';

--------------------------------------autorizaciones--------------------------------------------------------------------
COMMENT ON TABLE autorizaciones IS 'Relaciona la autorizaci� realizada a un paciente con su ingreso y el tipo de autorizaci� que se utiliz�';
COMMENT ON COLUMN autorizaciones.autorizacion IS 'PK Llave principal';
COMMENT ON COLUMN autorizaciones.fecha_autorizacion IS 'Fecha en que se realiza la autorizacion';
COMMENT ON COLUMN autorizaciones.observaciones IS 'Observaciones de la autorizacion';
COMMENT ON COLUMN autorizaciones.usuario_id IS 'usuario que realiza la autorizacion';
COMMENT ON COLUMN autorizaciones.fecha_registro IS 'fecha en la que se registro la autorizacion';
COMMENT ON COLUMN autorizaciones.sw_estado IS 'Estado de la autorizacion';
COMMENT ON COLUMN autorizaciones.ingreso IS 'ingreso en el cual se realiza la autorizacion';
COMMENT ON COLUMN autorizaciones.observacion_ingreso IS 'observacion del ingreso donde se realiza la autorizacion';

-----------------------------------------cups--------------------------------------------------------------------------
COMMENT ON TABLE cups IS 'tabla en donde se encuentra el tarifario base con el que se trabaja en la aplicacion.';
COMMENT ON COLUMN cups.cargo IS 'identificacion del cargo que utiliza como base';
COMMENT ON COLUMN cups.descripcion IS 'descripcion del cargo que se utiliza como base';
COMMENT ON COLUMN cups.grupo_tarifario_id IS 'grupo de contratacion en el cual se encuentra el cargo';
COMMENT ON COLUMN cups.subgrupo_tarifario_id IS 'subgrupo de contratacion en el cual se encutra el cargo';
COMMENT ON COLUMN cups.grupo_tipo_cargo IS 'clasificacion de grupo para la aplicacion en el cual se encuentra el cargo';
COMMENT ON COLUMN cups.tipo_cargo IS 'clasificacion de tipo para la aplicacion en el cual se encuentra el cargo';
COMMENT ON COLUMN cups.nivel IS 'nivel de complejidad en el cual se encuentra el cargo';
COMMENT ON COLUMN cups.concepto_rips IS 'catalogacion del cargo con los concepto rips';
COMMENT ON COLUMN cups.precio IS 'precio estandard del cargo';
COMMENT ON COLUMN cups.gravamen IS 'gravamen estandard del cargo';
COMMENT ON COLUMN cups.sw_cantidad IS 'cantidad estandard del cargo';
COMMENT ON COLUMN cups.sw_honorarios IS 'switche para identificar si se debe realizar liquidacion de honorarios para el cargo';
COMMENT ON COLUMN cups.sw_uvrs IS 'switche para identificar si el cargo maneja uvr o no';
COMMENT ON COLUMN cups.nivel_autorizador_id IS 'switche para identificar el nivel de autorizador que necesita el cargo';
COMMENT ON COLUMN cups.sw_pos IS 'switche para identificar si el cargo debe manejar liquidacion pos o no';
COMMENT ON COLUMN cups.grupos_mapipos IS 'grupo para catalogar el cargo dentro del mapipos';

------------------------------os_ordenes_servicios------------------------------------------
COMMENT ON TABLE "public"."os_ordenes_servicios" IS 'guarda las ordenes de servicio de los pacientes para la atencion';
COMMENT ON COLUMN "public"."os_ordenes_servicios"."orden_servicio_id" IS 'identifica las ordenes de los pacientes';
COMMENT ON COLUMN "public"."os_ordenes_servicios"."autorizacion_int" IS 'Numero que identifica la autorizacion interna de la orden';
COMMENT ON COLUMN "public"."os_ordenes_servicios"."autorizacion_ext" IS 'Numero que identifica la autorizacion externa de la orden';
COMMENT ON COLUMN "public"."os_ordenes_servicios"."plan_id" IS 'Identifica el plan con el cual se realizo la orden';
COMMENT ON COLUMN "public"."os_ordenes_servicios"."tipo_afiliado_id" IS 'identifica el tipo de afiliado con el cual se encuentra el paciente';
COMMENT ON COLUMN "public"."os_ordenes_servicios"."rango" IS 'identifica el rango del afiliado con el cual se encuentra el paciente';
COMMENT ON COLUMN "public"."os_ordenes_servicios"."semanas_cotizadas" IS 'identifica las semanas con las que se encuentra el paciente en la atencion';
COMMENT ON COLUMN "public"."os_ordenes_servicios"."servicio" IS 'identifica el servicio por donde se atiende al paciente';
COMMENT ON COLUMN "public"."os_ordenes_servicios"."tipo_id_paciente" IS 'identifica el tipo de identificacion del paciente';
COMMENT ON COLUMN "public"."os_ordenes_servicios"."paciente_id" IS 'identifica el numero de identificacion del paciente';
COMMENT ON COLUMN "public"."os_ordenes_servicios"."usuario_id" IS 'identifica el usuario que realizo la atencion';
COMMENT ON COLUMN "public"."os_ordenes_servicios"."fecha_registro" IS 'es la fecha en la cual se realizo la orden de servicio';
COMMENT ON COLUMN "public"."os_ordenes_servicios"."observacion" IS 'observacion que se debe tener en cuenta en la orden de servicio';

-----------------------------os_maestro-------------------------------------------------
COMMENT ON TABLE "public"."os_maestro" IS 'Registro hist�ico de los estados por los que ha pasado un  formulario de empleadores';
COMMENT ON COLUMN "public"."os_maestro"."numero_orden_id" IS 'numero con el que se identifca la orden maestra';
COMMENT ON COLUMN "public"."os_maestro"."sw_estado" IS 'switche con el que se identifica el estado de la orden maestra
9= anulada
8= anulada por vencimiento
7= transcripcion
6= no utilizado
5= revisado los derechos
4= lectura
3= para atencion
2= pagada
1= activa
0= no utilizado';
COMMENT ON COLUMN "public"."os_maestro"."orden_servicio_id" IS 'numero de identificacion de la orden de servicio';
COMMENT ON COLUMN "public"."os_maestro"."fecha_vencimiento" IS 'fecha en la que se vence la orden maestra';
COMMENT ON COLUMN "public"."os_maestro"."cantidad" IS 'cantidad de ordenes que se aprobaron';
COMMENT ON COLUMN "public"."os_maestro"."hc_os_solicitud_id" IS 'identificacion de la solicitud de servicio';
COMMENT ON COLUMN "public"."os_maestro"."fecha_activacion" IS 'fecha en la que se activa la orden';
COMMENT ON COLUMN "public"."os_maestro"."cargo_cups" IS 'cargo cups que se autorizo en la orden maestra';
COMMENT ON COLUMN "public"."os_maestro"."fecha_refrendar" IS 'fecha en la cual se debe refrendar la orden maestra';
COMMENT ON COLUMN "public"."os_maestro"."numerodecuenta" IS 'numero de cuenta con la que esta asociada la orden maestra';

----------------------------os_externas-------------------------------------------------
COMMENT ON TABLE "public"."os_externas" IS 'contiene los campos que referencia las ordenes maestras con elementos externos a la institucion';
COMMENT ON COLUMN "public"."os_externas"."numero_orden_id" IS 'identifica la orden maestra';
COMMENT ON COLUMN "public"."os_externas"."empresa_id" IS 'empresa que prestara el servicio externo';
COMMENT ON COLUMN "public"."os_externas"."tipo_id_tercero" IS 'tipo de identificacion del tercero';
COMMENT ON COLUMN "public"."os_externas"."tercero_id" IS 'identifcacion del tercero';
COMMENT ON COLUMN "public"."os_externas"."cargo" IS 'identifica el cargo cups que debe atender el tercero';
COMMENT ON COLUMN "public"."os_externas"."plan_proveedor_id" IS 'plan que tiene el proveedor con la institucion';

----------------------------os_internas-------------------------------------------------
COMMENT ON TABLE "public"."os_internas" IS 'Indica los tipos de valores que pueden presentarse en los detalles de los documentos soporte';
COMMENT ON COLUMN "public"."os_internas"."numero_orden_id" IS 'identifica la orden maestra';
COMMENT ON COLUMN "public"."os_internas"."cargo" IS 'identifica el cargo cups que debe atender el prestador de servicio';
COMMENT ON COLUMN "public"."os_internas"."departamento" IS 'identifica el departamento interno que presta el servicio';

-----------------------------os_maestro_cargos-------------------------------------------
COMMENT ON TABLE "public"."os_maestro_cargos" IS 'identifica los diferentes cargos reales que se van ha realizar al paciente';
COMMENT ON COLUMN "public"."os_maestro_cargos"."os_maestro_cargos_id" IS 'identifica los diferentes registros de los cargos que se van ha realizar a los pacientes';
COMMENT ON COLUMN "public"."os_maestro_cargos"."numero_orden_id" IS 'numero de la orden maestro al cual va relacionado';
COMMENT ON COLUMN "public"."os_maestro_cargos"."tarifario_id" IS 'identificacion del tarifario en donde se encuentra el cargo';
COMMENT ON COLUMN "public"."os_maestro_cargos"."cargo" IS 'identifica el cargo del tarifario que se le presta al usuario';
COMMENT ON COLUMN "public"."os_maestro_cargos"."transaccion" IS 'identifica la transaccion de la cuenta en donde se encuentra cargado el cargo';

--------------------------------hc_tipos_atencion-----------------------------------------------
COMMENT ON TABLE hc_tipos_atencion IS 'Catalogo de nombres del rips sobre la atencion';
COMMENT ON COLUMN hc_tipos_atencion.tipo_atencion_id IS 'PK Llave que identifica los datos de la tabla, son solamente 15 tipos';
COMMENT ON COLUMN hc_tipos_atencion.detalle IS 'Descripcion de los diferentes tipos';

---------------------------------hc_atecion------------------------------------------------------
COMMENT ON TABLE hc_atencion IS 'Relacion de los tipos de atencion con la evoluci�';
COMMENT ON COLUMN hc_atencion.tipo_atencion_id IS 'FK:PK Relacion con el tipo de atenci�';
COMMENT ON COLUMN hc_atencion.evolucion_id IS 'FK:PK Relacion con la evolucion del paciente';

--------------------------------hc_evolucion_descripcion------------------------------
COMMENT ON TABLE hc_evolucion_descripcion IS 'Registro de la evolucion del paciente en la atencion';
COMMENT ON COLUMN hc_evolucion_descripcion.hc_evolucion_descripcion_id IS 'Identificador de la descripcion de la evolucion';
COMMENT ON COLUMN hc_evolucion_descripcion.descripcion IS 'Texto en donde se realiza la descripcion de la evolucion';
COMMENT ON COLUMN hc_evolucion_descripcion.evolucion_id IS 'Identificador de la atencion del paciente';

