CREATE TABLE userpermisos_contabilidadpara (
    empresa_id character(2) NOT NULL,
    usuario_id integer NOT NULL
);

REVOKE ALL ON TABLE userpermisos_contabilidadpara FROM PUBLIC;
GRANT SELECT ON TABLE userpermisos_contabilidadpara TO siis_consulta;
GRANT INSERT,SELECT,UPDATE,DELETE ON TABLE userpermisos_contabilidadpara TO siis;

INSERT INTO userpermisos_contabilidadpara VALUES ('01', 2);

ALTER TABLE ONLY userpermisos_contabilidadpara
    ADD CONSTRAINT userpermisos_contabilidadpara_pkey PRIMARY KEY (empresa_id, usuario_id);

ALTER TABLE ONLY userpermisos_contabilidadpara
    ADD CONSTRAINT "$1" FOREIGN KEY (empresa_id) REFERENCES empresas(empresa_id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY userpermisos_contabilidadpara
    ADD CONSTRAINT "$2" FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE CASCADE;

COMMENT ON TABLE userpermisos_contabilidadpara IS 'Usuarios con autorización al modulo Contabilidad Parametros.';

INSERT INTO cg_plan_de_cuentas VALUES ('01', '1', 1, 'ACTIVO', '0', 'D', '0', '1');
INSERT INTO cg_plan_de_cuentas VALUES ('01', '11', 2, 'CAJA', '0', 'D', '0', '1');
INSERT INTO cg_plan_de_cuentas VALUES ('01', '2', 1, 'PASIVO', '0', 'D', '0', '1');
INSERT INTO cg_plan_de_cuentas VALUES ('01', '3', 1, 'PATRIMONIO', '0', 'D', '0', '1');
INSERT INTO cg_plan_de_cuentas VALUES ('01', '4', 1, 'INGRESOS', '0', 'D', '0', '1');

CREATE TABLE qx_procedimientos_pediatricos (
    procedimiento character varying(10) NOT NULL
);
ALTER TABLE ONLY qx_procedimientos_pediatricos
ADD CONSTRAINT "$1" FOREIGN KEY (procedimiento) REFERENCES cups(cargo) ON UPDATE CASCADE ON DELETE RESTRICT;

CREATE TABLE qx_procedimientos_programacion_pediatricos(
    procedimiento_qx character varying(10) NOT NULL,
		programacion_id  integer NOT NULL,
		tipo_id_pediatra character varying(3) NOT NULL,
    pediatra_id 	   character varying(32) NOT NULL
);
ALTER TABLE ONLY qx_procedimientos_programacion_pediatricos
ADD CONSTRAINT "$1" FOREIGN KEY (procedimiento_qx,programacion_id)
REFERENCES qx_procedimientos_programacion(procedimiento_qx,programacion_id)
ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY qx_procedimientos_programacion_pediatricos
ADD CONSTRAINT "$2" FOREIGN KEY (tipo_id_pediatra,pediatra_id)
REFERENCES profesionales(tipo_id_tercero,tercero_id)
ON UPDATE CASCADE ON DELETE RESTRICT;


--subido en pruebas y en la de producción por JORGE