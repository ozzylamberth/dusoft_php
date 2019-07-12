


--- dependiendo como este la estructura asi mismo sirve tirar el script de una vez
--- los errores que puedan tener son.
-- que pida que se elimine en cascada --- solucion: borrarla desde pgadmin
-- que no encuentre algun constraint -- solucion : deshabilitar el alter
-- no encuentra la relacion o la tabla -- solucion: quitar los alter y el drop table.




--hc_solicitudes_dietas
ALTER TABLE ONLY public.hc_solicitudes_dietas DROP CONSTRAINT "$4";
ALTER TABLE ONLY public.hc_solicitudes_dietas DROP CONSTRAINT "$3";
ALTER TABLE ONLY public.hc_solicitudes_dietas DROP CONSTRAINT "$2";
ALTER TABLE ONLY public.hc_solicitudes_dietas DROP CONSTRAINT "$1";
ALTER TABLE ONLY public.hc_solicitudes_dietas DROP CONSTRAINT hc_solicitudes_dietas_pkey;
DROP TABLE public.hc_solicitudes_dietas;


CREATE TABLE hc_solicitudes_dietas (
    ingreso integer NOT NULL,
    hc_dieta_id integer NOT NULL,
    observaciones character varying(255),
    usuario_id integer NOT NULL,
    fecha_registro timestamp without time zone NOT NULL,
    evolucion_id integer NOT NULL
);


ALTER TABLE ONLY hc_solicitudes_dietas
    ADD CONSTRAINT hc_solicitudes_dietas_pkey PRIMARY KEY (ingreso, evolucion_id, hc_dieta_id);

ALTER TABLE ONLY hc_solicitudes_dietas
    ADD CONSTRAINT "$1" FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY hc_solicitudes_dietas
    ADD CONSTRAINT "$2" FOREIGN KEY (ingreso) REFERENCES ingresos(ingreso) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY hc_solicitudes_dietas
    ADD CONSTRAINT "$3" FOREIGN KEY (hc_dieta_id) REFERENCES hc_tipos_dieta(hc_dieta_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY hc_solicitudes_dietas
    ADD CONSTRAINT "$4" FOREIGN KEY (evolucion_id) REFERENCES hc_evoluciones(evolucion_id) ON UPDATE CASCADE ON DELETE RESTRICT;



COMMENT ON TABLE hc_solicitudes_dietas IS 'Contiene las solicitudes de dietas a los pacientes de la estación';


COMMENT ON COLUMN hc_solicitudes_dietas.ingreso IS 'FK: ingreso del paciente';
COMMENT ON COLUMN hc_solicitudes_dietas.hc_dieta_id IS 'FK: tipo de dieta';
COMMENT ON COLUMN hc_solicitudes_dietas.observaciones IS 'Observaciones realizadas';
COMMENT ON COLUMN hc_solicitudes_dietas.usuario_id IS 'FK: usuario que realiza la solicitud';
--hc_solicitudes_dietas


--hc_solicitudes_dietas_ayunos
ALTER TABLE ONLY public.hc_solicitudes_dietas_ayunos DROP CONSTRAINT "$2";
ALTER TABLE ONLY public.hc_solicitudes_dietas_ayunos DROP CONSTRAINT "$1";
ALTER TABLE ONLY public.hc_solicitudes_dietas_ayunos DROP CONSTRAINT hc_solicitudes_dietas_ayunos_pkey;
DROP TABLE public.hc_solicitudes_dietas_ayunos;

CREATE TABLE hc_solicitudes_dietas_ayunos (
    ingreso integer NOT NULL,
    fecha date NOT NULL,
    motivo character varying(255) NOT NULL,
    usuario_id integer NOT NULL,
    fecha_registro timestamp without time zone NOT NULL,
    hora_fin_ayuno character varying(5) NOT NULL
);



ALTER TABLE ONLY hc_solicitudes_dietas_ayunos
    ADD CONSTRAINT hc_solicitudes_dietas_ayunos_pkey PRIMARY KEY (ingreso, fecha);

ALTER TABLE ONLY hc_solicitudes_dietas_ayunos
    ADD CONSTRAINT "$1" FOREIGN KEY (ingreso) REFERENCES ingresos(ingreso) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY hc_solicitudes_dietas_ayunos
    ADD CONSTRAINT "$2" FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;

--hc_solicitudes_dietas_ayunos



--hc_solicitudes_dietas_enfermeria
ALTER TABLE ONLY public.hc_solicitudes_dietas_enfermeria DROP CONSTRAINT "$2";
ALTER TABLE ONLY public.hc_solicitudes_dietas_enfermeria DROP CONSTRAINT "$1";
ALTER TABLE ONLY public.hc_solicitudes_dietas_enfermeria DROP CONSTRAINT hc_solicitudes_dietas_enfermeria_pkey;
DROP TABLE public.hc_solicitudes_dietas_enfermeria;


CREATE TABLE hc_solicitudes_dietas_enfermeria (
    ingreso integer NOT NULL,
    fecha date NOT NULL,
    hc_dieta_id integer NOT NULL,
    observacion character varying NOT NULL,
    usuario_id integer NOT NULL,
    fecha_registro timestamp without time zone NOT NULL
);


ALTER TABLE ONLY hc_solicitudes_dietas_enfermeria
    ADD CONSTRAINT hc_solicitudes_dietas_enfermeria_pkey PRIMARY KEY (ingreso, fecha, hc_dieta_id);

ALTER TABLE ONLY hc_solicitudes_dietas_enfermeria
    ADD CONSTRAINT "$1" FOREIGN KEY (ingreso) REFERENCES ingresos(ingreso) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY hc_solicitudes_dietas_enfermeria
    ADD CONSTRAINT "$2" FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;
--hc_solicitudes_dietas_enfermeria




--hc_tipo_liquidos_administrados
ALTER TABLE ONLY public.hc_tipo_liquidos_administrados DROP CONSTRAINT hc_tipo_liquidos_administrados_pkey;
DROP TABLE public.hc_tipo_liquidos_administrados;


CREATE TABLE hc_tipo_liquidos_administrados (
    tipo_liquido_administrado_id serial NOT NULL,
    descripcion character varying(30) NOT NULL
);



INSERT INTO hc_tipo_liquidos_administrados VALUES (1, 'NUTRICION ENTERAL');
INSERT INTO hc_tipo_liquidos_administrados VALUES (2, 'NUTRICION PARENTERAL');
INSERT INTO hc_tipo_liquidos_administrados VALUES (3, 'V.O');
INSERT INTO hc_tipo_liquidos_administrados VALUES (4, 'SOLUCION ENDOVENOSA');
INSERT INTO hc_tipo_liquidos_administrados VALUES (0, 'SANGRE I/O DERIVADOS');

ALTER TABLE ONLY hc_tipo_liquidos_administrados
    ADD CONSTRAINT hc_tipo_liquidos_administrados_pkey PRIMARY KEY (tipo_liquido_administrado_id);


SELECT pg_catalog.setval('hc_tipo_liquidos_administrados_tipo_liquido_administrado_id_seq', 1, false);


COMMENT ON TABLE hc_tipo_liquidos_administrados IS 'Maestro de liquidos que pueden ser administrados a los pacientes';


COMMENT ON COLUMN hc_tipo_liquidos_administrados.tipo_liquido_administrado_id IS 'PK id del registro';


COMMENT ON COLUMN hc_tipo_liquidos_administrados.descripcion IS 'descripcion del liquido';
--hc_tipo_liquidos_administrados






--hc_tipo_liquidos_eliminados
ALTER TABLE ONLY public.hc_tipo_liquidos_eliminados DROP CONSTRAINT hc_tipo_liquidos_eliminados_pkey;
DROP TABLE public.hc_tipo_liquidos_eliminados;

CREATE TABLE hc_tipo_liquidos_eliminados (
    tipo_liquido_eliminado_id serial NOT NULL,
    descripcion character varying(30) NOT NULL,
    deposicion character(1)
);




INSERT INTO hc_tipo_liquidos_eliminados VALUES (1, 'SNG/SOG', NULL);
INSERT INTO hc_tipo_liquidos_eliminados VALUES (2, 'DEPOSICIÓN', '1');
INSERT INTO hc_tipo_liquidos_eliminados VALUES (0, 'ELIMINACION URINARIA', NULL);


ALTER TABLE ONLY hc_tipo_liquidos_eliminados
    ADD CONSTRAINT hc_tipo_liquidos_eliminados_pkey PRIMARY KEY (tipo_liquido_eliminado_id);


SELECT pg_catalog.setval('hc_tipo_liquidos_eliminados_tipo_liquido_eliminado_id_seq', 1, false);



COMMENT ON TABLE hc_tipo_liquidos_eliminados IS 'Maestro de liquidos que pueden ser eliminados por los pacientes';


COMMENT ON COLUMN hc_tipo_liquidos_eliminados.tipo_liquido_eliminado_id IS 'PK id del registro';


COMMENT ON COLUMN hc_tipo_liquidos_eliminados.descripcion IS 'descripcion del liquido eliminado';

COMMENT ON COLUMN hc_tipo_liquidos_eliminados.deposicion IS '0=>No es materia fecal 1=>cuando es materia fecal';
--hc_tipo_liquidos_eliminados



--hc_control_liquidos_administrados
--ALTER TABLE ONLY public.hc_control_liquidos_administrados DROP CONSTRAINT "$3";
ALTER TABLE ONLY public.hc_control_liquidos_administrados DROP CONSTRAINT "$2";
ALTER TABLE ONLY public.hc_control_liquidos_administrados DROP CONSTRAINT "$1";
ALTER TABLE ONLY public.hc_control_liquidos_administrados DROP CONSTRAINT hc_control_liquidos_administrados_pkey;
DROP TABLE public.hc_control_liquidos_administrados;

CREATE TABLE hc_control_liquidos_administrados (
    hc_control_id serial NOT NULL,
    ingreso integer NOT NULL,
    fecha timestamp without time zone NOT NULL,
    tipo_liquido_administrado_id integer NOT NULL,
    cantidad numeric(9,2) NOT NULL,
    usuario_id integer,
    fecha_registro timestamp without time zone
);


ALTER TABLE ONLY hc_control_liquidos_administrados
    ADD CONSTRAINT hc_control_liquidos_administrados_pkey PRIMARY KEY (hc_control_id, ingreso, fecha);

ALTER TABLE ONLY hc_control_liquidos_administrados
    ADD CONSTRAINT "$1" FOREIGN KEY (ingreso) REFERENCES ingresos(ingreso) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY hc_control_liquidos_administrados
    ADD CONSTRAINT "$2" FOREIGN KEY (tipo_liquido_administrado_id) REFERENCES hc_tipo_liquidos_administrados(tipo_liquido_administrado_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY hc_control_liquidos_administrados
    ADD CONSTRAINT "$3" FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;


COMMENT ON TABLE hc_control_liquidos_administrados IS 'Contiene los datos de los liquidos que se administran al paciente';

COMMENT ON COLUMN hc_control_liquidos_administrados.ingreso IS 'FK: ingreso del paciente';


COMMENT ON COLUMN hc_control_liquidos_administrados.fecha IS 'fecha de administracion del liquido';


COMMENT ON COLUMN hc_control_liquidos_administrados.tipo_liquido_administrado_id IS 'FK: liquido que se administra';
--hc_control_liquidos_administrados


--hc_control_liquidos_eliminados
--ALTER TABLE ONLY public.hc_control_liquidos_eliminados DROP CONSTRAINT "$3";
ALTER TABLE ONLY public.hc_control_liquidos_eliminados DROP CONSTRAINT "$2";
ALTER TABLE ONLY public.hc_control_liquidos_eliminados DROP CONSTRAINT "$1";
ALTER TABLE ONLY public.hc_control_liquidos_eliminados DROP CONSTRAINT hc_control_liquidos_eliminados_pkey;
DROP TABLE public.hc_control_liquidos_eliminados;

CREATE TABLE hc_control_liquidos_eliminados (
    hc_controle_id serial NOT NULL,
    ingreso integer NOT NULL,
    fecha timestamp without time zone NOT NULL,
    tipo_liquido_eliminado_id integer NOT NULL,
    cantidad numeric(9,2) NOT NULL,
    deposicion character(2),
    usuario_id integer,
    fecha_registro timestamp without time zone
);



ALTER TABLE ONLY hc_control_liquidos_eliminados
    ADD CONSTRAINT hc_control_liquidos_eliminados_pkey PRIMARY KEY (hc_controle_id, ingreso, fecha);

ALTER TABLE ONLY hc_control_liquidos_eliminados
    ADD CONSTRAINT "$1" FOREIGN KEY (ingreso) REFERENCES ingresos(ingreso) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY hc_control_liquidos_eliminados
    ADD CONSTRAINT "$2" FOREIGN KEY (tipo_liquido_eliminado_id) REFERENCES hc_tipo_liquidos_eliminados(tipo_liquido_eliminado_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY hc_control_liquidos_eliminados
    ADD CONSTRAINT "$3" FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;


COMMENT ON TABLE hc_control_liquidos_eliminados IS 'Contiene los datos de los liquidos que elimina el paciente';

COMMENT ON COLUMN hc_control_liquidos_eliminados.ingreso IS 'FK: ingreso del paciente';

COMMENT ON COLUMN hc_control_liquidos_eliminados.fecha IS 'fecha en que se elimina el liquido';

COMMENT ON COLUMN hc_control_liquidos_eliminados.tipo_liquido_eliminado_id IS 'FK: liquido que elimina';

COMMENT ON COLUMN hc_control_liquidos_eliminados.cantidad IS 'cantidad eliminada';


COMMENT ON COLUMN hc_control_liquidos_eliminados.deposicion IS 'los posibles valores son NO ó SI';
--hc_control_liquidos_eliminados




--hc_tipos_componentes
ALTER TABLE ONLY public.hc_tipos_componentes DROP CONSTRAINT hc_tipos_componentes_pkey;
DROP TABLE public.hc_tipos_componentes;

CREATE TABLE hc_tipos_componentes (
    hc_tipo_componente serial NOT NULL,
    componente character varying(50) DEFAULT ''::character varying NOT NULL,
    dias_previos_vencimiento smallint,
    sw_cruze character(1) DEFAULT 0 NOT NULL
);



INSERT INTO hc_tipos_componentes VALUES (1, 'Globulos Rojos', 10, '1');
INSERT INTO hc_tipos_componentes VALUES (2, 'Plaquetas', 5, '0');
INSERT INTO hc_tipos_componentes VALUES (3, 'Plasma', 10, '0');
INSERT INTO hc_tipos_componentes VALUES (4, 'Crioprecipitadas', 15, '0');


ALTER TABLE ONLY hc_tipos_componentes
    ADD CONSTRAINT hc_tipos_componentes_pkey PRIMARY KEY (hc_tipo_componente);

SELECT pg_catalog.setval('hc_tipos_componentes_hc_tipo_componente_seq', 5, true);
--hc_tipos_componentes



--hc_solicitudes_medicamentos
ALTER TABLE ONLY public.hc_solicitudes_medicamentos DROP CONSTRAINT "$5";
ALTER TABLE ONLY public.hc_solicitudes_medicamentos DROP CONSTRAINT "$6";
ALTER TABLE ONLY public.hc_solicitudes_medicamentos DROP CONSTRAINT "$4";
ALTER TABLE ONLY public.hc_solicitudes_medicamentos DROP CONSTRAINT "$3";
ALTER TABLE ONLY public.hc_solicitudes_medicamentos DROP CONSTRAINT "$2";
ALTER TABLE ONLY public.hc_solicitudes_medicamentos DROP CONSTRAINT "$1";
ALTER TABLE ONLY public.hc_solicitudes_medicamentos DROP CONSTRAINT hc_solicitudes_medicamentos_pkey;
DROP TABLE public.hc_solicitudes_medicamentos;

CREATE TABLE hc_solicitudes_medicamentos (
    solicitud_id serial NOT NULL,
    ingreso integer NOT NULL,
    bodega character varying(2) NOT NULL,
    empresa_id character(2) NOT NULL,
    centro_utilidad character(2) NOT NULL,
    usuario_id integer NOT NULL,
    sw_estado character(1) DEFAULT '0'::bpchar NOT NULL,
    fecha_solicitud timestamp without time zone NOT NULL,
    estacion_id character varying(4) NOT NULL,
    tipo_solicitud character(1) NOT NULL,
    documento_despacho integer,
    bodegas_doc_id integer,
    numeracion integer
);




ALTER TABLE ONLY hc_solicitudes_medicamentos
    ADD CONSTRAINT hc_solicitudes_medicamentos_pkey PRIMARY KEY (solicitud_id);

ALTER TABLE ONLY hc_solicitudes_medicamentos
    ADD CONSTRAINT "$1" FOREIGN KEY (ingreso) REFERENCES ingresos(ingreso);


ALTER TABLE ONLY hc_solicitudes_medicamentos
    ADD CONSTRAINT "$2" FOREIGN KEY (empresa_id, centro_utilidad, bodega) REFERENCES bodegas(empresa_id, centro_utilidad, bodega) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY hc_solicitudes_medicamentos
    ADD CONSTRAINT "$3" FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY hc_solicitudes_medicamentos
    ADD CONSTRAINT "$4" FOREIGN KEY (tipo_solicitud) REFERENCES inv_tipos_solicitud(tipo_solicitud) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY hc_solicitudes_medicamentos
    ADD CONSTRAINT "$6" FOREIGN KEY (bodegas_doc_id, numeracion) REFERENCES bodegas_documentos(bodegas_doc_id, numeracion) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY hc_solicitudes_medicamentos
    ADD CONSTRAINT "$5" FOREIGN KEY (documento_despacho) REFERENCES bodegas_documento_despacho_med(documento_despacho_id) ON UPDATE CASCADE ON DELETE RESTRICT;


COMMENT ON TABLE hc_solicitudes_medicamentos IS 'Maestro de solicitudes de medicamentos a bodegas';


COMMENT ON COLUMN hc_solicitudes_medicamentos.solicitud_id IS 'PK id de las solicitudes';


COMMENT ON COLUMN hc_solicitudes_medicamentos.ingreso IS 'FK id del ingreso del paciente';


COMMENT ON COLUMN hc_solicitudes_medicamentos.bodega IS 'FK bodega a la cual se solicita';


COMMENT ON COLUMN hc_solicitudes_medicamentos.empresa_id IS 'FK empresa';


COMMENT ON COLUMN hc_solicitudes_medicamentos.centro_utilidad IS 'FK centro de utilidad';


COMMENT ON COLUMN hc_solicitudes_medicamentos.usuario_id IS 'FK usuario que ingresa la info';



COMMENT ON COLUMN hc_solicitudes_medicamentos.sw_estado IS '0=>''Sin despacho'', 1=>''Despachado'', 2=>''Recibido'', 3=>''Cancelado''';


COMMENT ON COLUMN hc_solicitudes_medicamentos.fecha_solicitud IS 'fecha en la que se hace la solicitud';



COMMENT ON COLUMN hc_solicitudes_medicamentos.estacion_id IS 'FK estación que realiza la solicitud';


COMMENT ON COLUMN hc_solicitudes_medicamentos.tipo_solicitud IS 'FK tipo de solicitud M=>medicamentos Z=>Mezclas I=>insumos';



COMMENT ON COLUMN hc_solicitudes_medicamentos.documento_despacho IS 'numero de despacho para confirmar de la tabla tmp_bodegas_documentos';
--hc_solicitudes_medicamentos



--hc_solicitudes_medicamentos_d
ALTER TABLE ONLY public.hc_solicitudes_medicamentos_d DROP CONSTRAINT "$1";
ALTER TABLE ONLY public.hc_solicitudes_medicamentos_d DROP CONSTRAINT "$3";
ALTER TABLE ONLY public.hc_solicitudes_medicamentos_d DROP CONSTRAINT "$2";
ALTER TABLE ONLY public.hc_solicitudes_medicamentos_d DROP CONSTRAINT hc_solicitudes_medicamentos_d_pkey;
DROP TABLE public.hc_solicitudes_medicamentos_d;

CREATE TABLE hc_solicitudes_medicamentos_d (
    consecutivo_d serial NOT NULL,
    solicitud_id integer NOT NULL,
    medicamento_id character varying(18) NOT NULL,
    evolucion_id integer NOT NULL,
    cant_solicitada integer NOT NULL,
    mezcla_recetada_id integer
);




ALTER TABLE ONLY hc_solicitudes_medicamentos_d
    ADD CONSTRAINT hc_solicitudes_medicamentos_d_pkey PRIMARY KEY (consecutivo_d);

ALTER TABLE ONLY hc_solicitudes_medicamentos_d
    ADD CONSTRAINT "$2" FOREIGN KEY (solicitud_id) REFERENCES hc_solicitudes_medicamentos(solicitud_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY hc_solicitudes_medicamentos_d
    ADD CONSTRAINT "$3" FOREIGN KEY (mezcla_recetada_id) REFERENCES hc_mezclas_recetadas(mezcla_recetada_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY hc_solicitudes_medicamentos_d
    ADD CONSTRAINT "$1" FOREIGN KEY (medicamento_id, evolucion_id) REFERENCES hc_medicamentos_recetados_hosp(codigo_producto, evolucion_id) ON UPDATE CASCADE ON DELETE RESTRICT;


COMMENT ON TABLE hc_solicitudes_medicamentos_d IS 'Detalle de solicitudes de medicamentos a bodegas ';
--hc_solicitudes_medicamentos_d




--hc_control_transfusiones
ALTER TABLE ONLY public.hc_control_transfusiones DROP CONSTRAINT "$5";
ALTER TABLE ONLY public.hc_control_transfusiones DROP CONSTRAINT "$4";
ALTER TABLE ONLY public.hc_control_transfusiones DROP CONSTRAINT "$3";
ALTER TABLE ONLY public.hc_control_transfusiones DROP CONSTRAINT "$2";
ALTER TABLE ONLY public.hc_control_transfusiones DROP CONSTRAINT "$1";
ALTER TABLE ONLY public.hc_control_transfusiones DROP CONSTRAINT hc_control_transfusiones_pkey;
DROP TABLE public.hc_control_transfusiones;

CREATE TABLE hc_control_transfusiones (
    ingreso integer NOT NULL,
    fecha timestamp without time zone NOT NULL,
    numero_sello_calidad character varying(32) NOT NULL,
    fecha_vencimiento timestamp without time zone NOT NULL,
    grupo_sanguineo character varying(2) NOT NULL,
    rh character(1) NOT NULL,
    fecha_final timestamp without time zone,
    usuario integer NOT NULL,
    numero_bolsas character varying(20) NOT NULL,
    hc_tipo_componente character varying(50) NOT NULL,
    evolucion_id integer
);



ALTER TABLE ONLY hc_control_transfusiones
    ADD CONSTRAINT hc_control_transfusiones_pkey PRIMARY KEY (ingreso, fecha);


ALTER TABLE ONLY hc_control_transfusiones
    ADD CONSTRAINT "$1" FOREIGN KEY (ingreso) REFERENCES ingresos(ingreso) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY hc_control_transfusiones
    ADD CONSTRAINT "$2" FOREIGN KEY (grupo_sanguineo, rh) REFERENCES hc_tipos_sanguineos(grupo_sanguineo, rh) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY hc_control_transfusiones
    ADD CONSTRAINT "$3" FOREIGN KEY (usuario) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;


ALTER TABLE ONLY hc_control_transfusiones
    ADD CONSTRAINT "$4" FOREIGN KEY (hc_tipo_componente) REFERENCES hc_tipos_componentes(hc_tipo_componente) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY hc_control_transfusiones
    ADD CONSTRAINT "$5" FOREIGN KEY (evolucion_id) REFERENCES hc_evoluciones(evolucion_id) ON UPDATE CASCADE ON DELETE RESTRICT;


COMMENT ON TABLE hc_control_transfusiones IS 'Contiene los valores obtenidos al realizar las transfusiones al paciente';


COMMENT ON COLUMN hc_control_transfusiones.ingreso IS 'FK: ingreso del paciente';


COMMENT ON COLUMN hc_control_transfusiones.fecha IS 'fecha en que se inicia la transfusion';


COMMENT ON COLUMN hc_control_transfusiones.numero_sello_calidad IS 'numero de sello de calidad de la bolsa';


COMMENT ON COLUMN hc_control_transfusiones.fecha_vencimiento IS 'fecha de vencimiento de la bolsa';

COMMENT ON COLUMN hc_control_transfusiones.grupo_sanguineo IS 'FK: grupo sanguineo de la bolsa';

COMMENT ON COLUMN hc_control_transfusiones.rh IS 'FK: rh de la bolsa';


COMMENT ON COLUMN hc_control_transfusiones.fecha_final IS 'fecha en que se finaliza la transfusion';


COMMENT ON COLUMN hc_control_transfusiones.usuario IS 'usuario que realiza el registro';

COMMENT ON COLUMN hc_control_transfusiones.evolucion_id IS 'numero de evolucion del paciente';
--hc_control_transfusiones




--hc_control_transfusiones_notas_reaccion_adversas
ALTER TABLE ONLY public.hc_control_transfusiones_notas_reaccion_adversas DROP CONSTRAINT "$1";
ALTER TABLE ONLY public.hc_control_transfusiones_notas_reaccion_adversas DROP CONSTRAINT hc_control_transfusiones_notas_reaccion_adversas_pkey;
DROP TABLE public.hc_control_transfusiones_notas_reaccion_adversas;


CREATE TABLE hc_control_transfusiones_notas_reaccion_adversas (
    hc_trasfucion_id serial NOT NULL,
    ingreso integer NOT NULL,
    fecha timestamp without time zone NOT NULL,
    observacion character varying(255),
    usuario_id integer NOT NULL,
    fecha_registro timestamp without time zone NOT NULL,
    sw_reaccion character(1)
);




ALTER TABLE ONLY hc_control_transfusiones_notas_reaccion_adversas
    ADD CONSTRAINT hc_control_transfusiones_notas_reaccion_adversas_pkey PRIMARY KEY (hc_trasfucion_id, ingreso, fecha);

ALTER TABLE ONLY hc_control_transfusiones_notas_reaccion_adversas
    ADD CONSTRAINT "$1" FOREIGN KEY (ingreso, fecha) REFERENCES hc_control_transfusiones(ingreso, fecha) ON UPDATE CASCADE ON DELETE RESTRICT;



COMMENT ON TABLE hc_control_transfusiones_notas_reaccion_adversas IS 'las reacciones adversas q puede tener un paciente despues de una transfucion de sangre';


COMMENT ON COLUMN hc_control_transfusiones_notas_reaccion_adversas.sw_reaccion IS 'este campo no determinara por medio de una imagen  las reacciones de las personas despues de una tranfusiones.  1->BIEN ,3->NEUTRO,2->NEGATIVO';

--hc_control_transfusiones_notas_reaccion_adversas




--hc_auditoria_solicitudes_medicamentos
ALTER TABLE ONLY public.hc_auditoria_solicitudes_medicamentos DROP CONSTRAINT "$2";
ALTER TABLE ONLY public.hc_auditoria_solicitudes_medicamentos DROP CONSTRAINT "$1";
ALTER TABLE ONLY public.hc_auditoria_solicitudes_medicamentos DROP CONSTRAINT hc_auditoria_solicitudes_medicamentos_pkey;
DROP TABLE public.hc_auditoria_solicitudes_medicamentos;


CREATE TABLE hc_auditoria_solicitudes_medicamentos (
    hc_cancel_id serial NOT NULL,
    usuario_id integer NOT NULL,
    fecha_registro timestamp without time zone NOT NULL,
    observacion character varying(256),
    solicitud_id integer NOT NULL
);



ALTER TABLE ONLY hc_auditoria_solicitudes_medicamentos
    ADD CONSTRAINT hc_auditoria_solicitudes_medicamentos_pkey PRIMARY KEY (hc_cancel_id);



ALTER TABLE ONLY hc_auditoria_solicitudes_medicamentos
    ADD CONSTRAINT "$1" FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;



ALTER TABLE ONLY hc_auditoria_solicitudes_medicamentos
    ADD CONSTRAINT "$2" FOREIGN KEY (solicitud_id) REFERENCES hc_solicitudes_medicamentos(solicitud_id) ON UPDATE CASCADE ON DELETE RESTRICT;







-- hc_medicamento_bodega_paciente
DROP TABLE public.hc_medicamento_bodega_paciente;

CREATE TABLE hc_medicamento_bodega_paciente (
    hc_bodega_paciente_id serial NOT NULL,
    ingreso integer NOT NULL,
    medicamento_id character varying(18),
    fecha_registro timestamp without time zone,
    cantidad numeric(14,2)
);
-- hc_medicamento_bodega_paciente

--alteramos la tabla estacion de enfermeria
ALTER TABLE "estaciones_enfermeria" ADD COLUMN "hc_modulo_consulta_urgencias" character varying(64)

UPDATE  estaciones_enfermeria SET
hc_modulo_consulta_urgencias='UrgenciasConsulta'; 



