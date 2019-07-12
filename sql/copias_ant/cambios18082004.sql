CREATE TABLE userpermisos_centro_impresion (
    usuario_id integer NOT NULL,
    empresa_id character(2) NOT NULL,
    plan_id integer NOT NULL,
    sw_todos_planes character(1) NOT NULL
);


ALTER TABLE ONLY userpermisos_centro_impresion
    ADD CONSTRAINT userpermisos_centro_impresion_pkey PRIMARY KEY (usuario_id, empresa_id, plan_id);


ALTER TABLE ONLY userpermisos_centro_impresion
    ADD CONSTRAINT "$1" FOREIGN KEY (empresa_id) REFERENCES empresas(empresa_id) ON UPDATE CASCADE ON DELETE RESTRICT;



ALTER TABLE ONLY userpermisos_centro_impresion
    ADD CONSTRAINT "$2" FOREIGN KEY (plan_id) REFERENCES planes(plan_id) ON UPDATE CASCADE ON DELETE RESTRICT;



ALTER TABLE ONLY userpermisos_centro_impresion
    ADD CONSTRAINT "$3" FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;

--sb1


--cambio de duvan a la tabla userpermisos_os_atencion, faltaba la llave primaria.
ALTER TABLE "userpermisos_os_atencion" ADD PRIMARY KEY ("usuario_id","departamento");

INSERT INTO system_modulos VALUES ('CentroImpresion', 'app', 'Modulo para la impresion de las ordenes y solicitudes', 1.00, '', '1', '1', '1');

INSERT INTO system_menus VALUES (46, 'CENTRO IMPRESION', '', '0');

INSERT INTO system_menus_items VALUES (75, 46, 'CENTRO IMPRESION', 'app', 'CentroImpresion', 'user', 'main', '', 0);


CREATE FUNCTION "userpermisos_asignacion" (integer) RETURNS bigint AS '
select count(*) from userpermisos_tipos_consulta as a, tipos_consulta as b, departamentos as c, empresas as d, tipos_servicios_ambulatorios as e where a.tipo_consulta_id=b.tipo_consulta_id and a.usuario_id= $1 and b.departamento=c.departamento and c.empresa_id=d.empresa_id and a.tipo_consulta_id=e.tipo_servicio_amb_id;
' LANGUAGE "sql"
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER


CREATE FUNCTION "userpermisos_cumplimiento" (integer) RETURNS bigint AS '
select count(*) from userpermisos_consultas_cumplimientos as a, tipos_consulta as b, departamentos as c, empresas as d, tipos_servicios_ambulatorios as e where a.tipo_consulta_id=b.tipo_consulta_id and a.usuario_id= $1 and b.departamento=c.departamento and c.empresa_id=d.empresa_id and b.tipo_consulta_id=e.tipo_servicio_amb_id;
' LANGUAGE "sql"
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER


CREATE FUNCTION "userpermisos_atencion_citas" (integer) RETURNS bigint AS '
select count(*) from profesionales_usuarios as a, agenda_turnos as b, empresas as c, tipos_consulta as d, departamentos as e, profesionales as f, tipos_servicios_ambulatorios as g, terceros as h where a.usuario_id = $1 and a.tipo_tercero_id=b.tipo_id_profesional and a.tercero_id=b.profesional_id and a.tipo_tercero_id=h.tipo_id_tercero and a.tercero_id=h.tercero_id and b.empresa_id=c.empresa_id and b.tipo_consulta_id=d.tipo_consulta_id and d.departamento=e.departamento and b.profesional_id=f.tercero_id and b.tipo_id_profesional=f.tipo_id_tercero and b.fecha_turno>=date(now()) and d.tipo_consulta_id=g.tipo_servicio_amb_id;
' LANGUAGE "sql"
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER

--sb2

--darling

ALTER TABLE "hc_os_solicitudes_manuales" ADD COLUMN "tipo_afiliado_id" character varying(2);
ALTER TABLE "hc_os_solicitudes_manuales" ADD COLUMN "rango" character varying(2);
ALTER TABLE "hc_os_solicitudes_manuales" ADD COLUMN "semanas_cotizadas" smallint;

--sb3

--ALTER TABLE "hc_os_solicitudes_manuales" ALTER COLUMN "semanas_cotizadas" DEFAULT 0;


CREATE TABLE "tipos_consulta_consultorios" ("tipo_consulta_id" integer NOT NULL, "consultorio" character varying(5) NOT NULL);
ALTER TABLE public.tipos_consulta_consultorios
  ADD CONSTRAINT tipos_consulta_consultorios_pkey PRIMARY KEY(tipo_consulta_id, consultorio);
ALTER TABLE public.tipos_consulta_consultorios
  ADD CONSTRAINT "$1" FOREIGN KEY (consultorio) REFERENCES public.consultorios (consultorio) ON UPDATE CASCADE ON DELETE CASCADE;
	ALTER TABLE public.tipos_consulta_consultorios
  ADD CONSTRAINT "$2" FOREIGN KEY (tipo_consulta_id) REFERENCES public.tipos_consulta (tipo_consulta_id) ON UPDATE CASCADE ON DELETE CASCADE;
GRANT ALL ON TABLE public.tipos_consulta_consultorios TO admin WITH GRANT OPTION;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE public.tipos_consulta_consultorios TO siis;
GRANT SELECT ON TABLE public.tipos_consulta_consultorios TO siis_consulta;

ALTER TABLE public.consultorios DROP COLUMN tipo_consulta_id;

--sb4