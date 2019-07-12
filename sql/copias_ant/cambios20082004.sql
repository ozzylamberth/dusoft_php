ALTER TABLE "cronicos" DROP CONSTRAINT "$1";

ALTER TABLE "cronicos" ADD FOREIGN KEY ("paciente_id","tipo_id_paciente") REFERENCES "public"."pacientes"("paciente_id","tipo_id_paciente")  ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE "gestacion" DROP CONSTRAINT "$1";

ALTER TABLE "gestacion" ADD FOREIGN KEY ("tipo_id_paciente","paciente_id") REFERENCES "public"."pacientes"("paciente_id","tipo_id_paciente")  ON UPDATE CASCADE ON DELETE RESTRICT;

--SIEMPRE SE DEBE INSERTAR LA AUTORIZACION 1
INSERT INTO autorizaciones VALUES (1, '2004-08-20 08:56:09.829021', 'AUTORIZACION DEL SISTEMA', 2, '2004-08-20 08:56:09.829021', '0', NULL, NULL);

--sb1

CREATE TABLE userpermisos_contabilidad (
    empresa_id character(2) NOT NULL,
    usuario_id integer NOT NULL
);

REVOKE ALL ON TABLE userpermisos_contabilidad FROM PUBLIC;
--GRANT SELECT ON TABLE userpermisos_contabilidad TO siis_consulta;
GRANT INSERT,SELECT,UPDATE,DELETE ON TABLE userpermisos_contabilidad TO siis;

ALTER TABLE ONLY userpermisos_contabilidad
    ADD CONSTRAINT userpermisos_contabilidad_pkey PRIMARY KEY (empresa_id, usuario_id);

ALTER TABLE ONLY userpermisos_contabilidad
    ADD CONSTRAINT "$1" FOREIGN KEY (empresa_id) REFERENCES empresas(empresa_id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY userpermisos_contabilidad
    ADD CONSTRAINT "$2" FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE CASCADE;

COMMENT ON TABLE userpermisos_contabilidad IS 'Usuarios con autorización al modulo Contabilidad.';

--sb2

CREATE TABLE userpermisos_contabilidad (
    empresa_id character(2) NOT NULL,
    usuario_id integer NOT NULL
);

REVOKE ALL ON TABLE userpermisos_contabilidad FROM PUBLIC;
--GRANT SELECT ON TABLE userpermisos_contabilidad TO siis_consulta;
GRANT INSERT,SELECT,UPDATE,DELETE ON TABLE userpermisos_contabilidad TO siis;

ALTER TABLE ONLY userpermisos_contabilidad
    ADD CONSTRAINT userpermisos_contabilidad_pkey PRIMARY KEY (empresa_id, usuario_id);

ALTER TABLE ONLY userpermisos_contabilidad
    ADD CONSTRAINT "$1" FOREIGN KEY (empresa_id) REFERENCES empresas(empresa_id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY userpermisos_contabilidad
    ADD CONSTRAINT "$2" FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE CASCADE;

COMMENT ON TABLE userpermisos_contabilidad IS 'Usuarios con autorización al modulo Contabilidad.';

--sb3

--farmacia
ALTER TABLE "userpermisos_central" ADD COLUMN "sw_farmacia" character(1);
--actualizamos para q no saque el error
UPDATE userpermisos_central  SET sw_farmacia='0';
ALTER TABLE "userpermisos_central" ALTER COLUMN "sw_farmacia" SET NOT NULL;
ALTER TABLE "userpermisos_central" ALTER COLUMN "sw_farmacia" SET DEFAULT 0;

--sb4

ALTER TABLE "hc_control_protocolos" DROP CONSTRAINT "$3";

ALTER TABLE "hc_control_protocolos" ADD FOREIGN KEY ("paciente_id","tipo_id_paciente") REFERENCES "public"."pacientes"("paciente_id","tipo_id_paciente")  ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE "hc_recomendaciones" DROP CONSTRAINT "$1";

ALTER TABLE "hc_recomendaciones" ADD FOREIGN KEY ("paciente_id","tipo_id_paciente") REFERENCES "public"."pacientes"("paciente_id","tipo_id_paciente")  ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE "hc_vacunas_cumplidas" DROP CONSTRAINT "$3";

ALTER TABLE "hc_vacunas_cumplidas" ADD FOREIGN KEY ("paciente_id","tipo_id_paciente") REFERENCES "public"."pacientes"("paciente_id","tipo_id_paciente")  ON UPDATE CASCADE ON DELETE RESTRICT;

--sb5

CREATE TABLE public.cg_parametros_cuentas
(
  empresa_id char(2) NOT NULL,
  departamento varchar(6) NOT NULL,
  servicio varchar(2) NOT NULL,
  grupo_tarifario_id varchar(2) NOT NULL,
  subgrupo_tarifario_id varchar(2) NOT NULL,
  cuentas varchar(32) NOT NULL,
  CONSTRAINT cg_parametros_cuentas_pkey PRIMARY KEY (empresa_id, departamento, servicio, grupo_tarifario_id, subgrupo_tarifario_id),
  CONSTRAINT "$1" FOREIGN KEY (empresa_id) REFERENCES public.empresas (empresa_id) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT "$2" FOREIGN KEY (departamento) REFERENCES public.departamentos (departamento) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT "$3" FOREIGN KEY (servicio) REFERENCES public.servicios (servicio) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT "$4" FOREIGN KEY (grupo_tarifario_id, subgrupo_tarifario_id) REFERENCES public.subgrupos_tarifarios (grupo_tarifario_id, subgrupo_tarifario_id) ON UPDATE CASCADE ON DELETE RESTRICT
) WITH OIDS;

GRANT ALL ON TABLE public.cg_parametros_cuentas TO admin WITH GRANT OPTION;
--RANT SELECT ON TABLE public.cg_parametros_cuentas TO siis_consulta;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE public.cg_parametros_cuentas TO siis;
COMMENT ON TABLE public.cg_parametros_cuentas IS 'Parametros para definir las cuentas de la contabilidad';

--sb6

INSERT INTO tarifarios_detalle VALUES ('SYS','00','00','INC_CITA','INCUMPLIMIENTO DE CITA MEDICA',0.00,'SYS','SYS',0,'1','1','0','01','0');

ALTER TABLE "planes_incumplimientos_citas" ADD COLUMN "tarifario_id" character varying(4);
ALTER TABLE "planes_incumplimientos_citas" ADD COLUMN "cargo" character varying(10);
update planes_incumplimientos_citas set tarifario_id='SYS', cargo='INC_CITA';
update planes_incumplimientos_citas set tarifario_id='SYS';
ALTER TABLE "planes_incumplimientos_citas" ALTER COLUMN "tarifario_id" SET NOT NULL;
ALTER TABLE "planes_incumplimientos_citas" ALTER COLUMN "tarifario_id" SET DEFAULT 'SYS';
ALTER TABLE "planes_incumplimientos_citas" ALTER COLUMN "cargo" SET NOT NULL;
ALTER TABLE "planes_incumplimientos_citas" ALTER COLUMN "cargo"  SET DEFAULT 'INC_CITA';

ALTER TABLE ONLY planes_incumplimientos_citas
    ADD CONSTRAINT "$3" FOREIGN KEY (tarifario_id,cargo) REFERENCES tarifarios_detalle(tarifario_id,cargo) ON UPDATE CASCADE ON DELETE RESTRICT;

--sb7