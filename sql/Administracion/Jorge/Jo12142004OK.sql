--Esto no se supo de quien era, así que cree el script
ALTER TABLE hc_tipos_antecedentes_det ADD COLUMN sw_defecto character(1);
ALTER TABLE hc_tipos_antecedentes_det ALTER COLUMN sw_defecto SET DEFAULT '0';
--ALTER TABLE planes_paragrafados_medicamentos ADD COLUMN departamento character varying(6);
--ALTER TABLE planes_paragrafados_medicamentos ALTER COLUMN departamento SET NOT NULL;
--ALTER TABLE planes_paragrafados_medicamentos ADD FOREIGN KEY
--(departamento) REFERENCES departamentos(departamento) ON UPDATE CASCADE ON DELETE RESTRICT;


CREATE TABLE tipos_paragrafados_imd (
    tipo_para_imd serial NOT NULL,
    descripcion character varying(100) NOT NULL
);
ALTER TABLE ONLY tipos_paragrafados_imd
    ADD CONSTRAINT tipos_paragrafados_imd_pkey PRIMARY KEY (tipo_para_imd);
COMMENT ON TABLE tipos_paragrafados_imd IS 'Los tipos de paragrafados estandar';


CREATE TABLE tipos_paragrafados_imd_detalle (
    tipo_para_imd integer NOT NULL,
    servicio character varying(2) NOT NULL,
    departamento character varying(6) NOT NULL,
    codigo_producto character varying(10) NOT NULL
);
ALTER TABLE ONLY tipos_paragrafados_imd_detalle
    ADD CONSTRAINT tipos_paragrafados_imd_detalle_pkey PRIMARY KEY (tipo_para_imd, servicio, departamento, codigo_producto);
ALTER TABLE ONLY tipos_paragrafados_imd_detalle
    ADD CONSTRAINT "$1" FOREIGN KEY (tipo_para_imd) REFERENCES tipos_paragrafados_imd(tipo_para_imd) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY tipos_paragrafados_imd_detalle
    ADD CONSTRAINT "$2" FOREIGN KEY (servicio) REFERENCES servicios(servicio) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY tipos_paragrafados_imd_detalle
    ADD CONSTRAINT "$3" FOREIGN KEY (departamento) REFERENCES departamentos(departamento) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY tipos_paragrafados_imd_detalle
    ADD CONSTRAINT "$4" FOREIGN KEY (codigo_producto) REFERENCES inventarios_productos(codigo_producto) ON UPDATE CASCADE ON DELETE RESTRICT;
COMMENT ON TABLE tipos_paragrafados_imd_detalle IS 'Los productos del inventario que son paragrafados dependiendo del servicio y del departamento.';


DROP TABLE planes_paragrafados_medicamentos;
CREATE TABLE planes_paragrafados_medicamentos (
    plan_id integer NOT NULL,
    servicio character varying(2) NOT NULL,
    departamento character varying(6) NOT NULL,
    codigo_producto character varying(10) NOT NULL,
    CONSTRAINT "$5" CHECK (((servicio)::text <> '0'::text))
);
ALTER TABLE ONLY planes_paragrafados_medicamentos
    ADD CONSTRAINT "$1" FOREIGN KEY (plan_id) REFERENCES planes(plan_id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY planes_paragrafados_medicamentos
    ADD CONSTRAINT "$2" FOREIGN KEY (servicio) REFERENCES servicios(servicio) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY planes_paragrafados_medicamentos
    ADD CONSTRAINT "$3" FOREIGN KEY (departamento) REFERENCES departamentos(departamento) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE ONLY planes_paragrafados_medicamentos
    ADD CONSTRAINT "$4" FOREIGN KEY (codigo_producto) REFERENCES inventarios_productos(codigo_producto) ON UPDATE CASCADE ON DELETE RESTRICT;
COMMENT ON TABLE planes_paragrafados_medicamentos IS 'Insumos y medicamentos que no se cobran en un plan, si está opción gue contratada.';


INSERT INTO tipos_paragrafados_imd VALUES (0, 'DEFINIDO EN EL PLAN');

ALTER TABLE planes ADD COLUMN tipo_para_imd integer;
UPDATE planes SET tipo_para_imd='0';
ALTER TABLE planes ALTER COLUMN tipo_para_imd SET DEFAULT 0;
ALTER TABLE planes ALTER COLUMN tipo_para_imd SET NOT NULL;
ALTER TABLE planes ADD FOREIGN KEY (tipo_para_imd)
REFERENCES tipos_paragrafados_imd(tipo_para_imd) ON UPDATE CASCADE ON DELETE RESTRICT;

DROP FUNCTION paragrafados_medicamentos(text,text,text);

CREATE FUNCTION paragrafados_medicamentos(text,text,text,text) RETURNS bigint AS
'
SELECT count(*)
FROM planes_paragrafados_medicamentos
WHERE plan_id = $1
AND servicio = $2
AND departamento = $3
AND codigo_producto = $4;
'
LANGUAGE sql
VOLATILE
RETURNS NULL ON NULL INPUT
SECURITY INVOKER;

ALTER TABLE planes DROP COLUMN sw_plan_pos;

INSERT INTO system_modulos VALUES ('Paragrafos', 'system', 'Tipos de paragrafados para Contratación', 1.00, '', '1', '1', '1');
INSERT INTO system_menus VALUES (68, 'TIPOS DE PARAGRAFADOS', 'Tipos de paragrafados para Contratación', '0');
INSERT INTO system_menus_items VALUES (101, 68, 'TIPOS DE PARAGRAFADOS', 'system', 'Paragrafos', 'user', 'main', '', 0);
INSERT INTO system_usuarios_menus VALUES (68, 2);
