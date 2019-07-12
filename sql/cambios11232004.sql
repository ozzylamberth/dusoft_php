
--jaime -ok enviado atulua nov/22

ALTER TABLE "hc_tipos_antecedentes_det" DROP COLUMN "sw_defecto";

ALTER TABLE public.userpermisos_busqueda_agenda ADD COLUMN sw_mostrar_historia char(1);
update userpermisos_busqueda_agenda set sw_mostrar_historia='0';
ALTER TABLE public.userpermisos_busqueda_agenda ALTER COLUMN sw_mostrar_historia SET NOT NULL;
ALTER TABLE public.userpermisos_busqueda_agenda ALTER COLUMN sw_mostrar_historia DEFAULT '0'::bpchar;

--ALTER TABLE soat_eventos ADD COLUMN edad_atencion numeric (4);
--ALTER TABLE soat_eventos ALTER COLUMN edad_atencion SET NOT NULL;

--ALTER TABLE soat_eventos ADD COLUMN edad_unidad character (1);
--ALTER TABLE soat_eventos ALTER COLUMN edad_unidad SET NOT NULL;

CREATE TABLE banco_sangre_cantidad_cruzes(
  codigo_cantidad_cruces character varying(2),
	descripcion character(10)
);
ALTER TABLE ONLY banco_sangre_cantidad_cruzes ADD PRIMARY KEY (codigo_cantidad_cruces);
INSERT INTO banco_sangre_cantidad_cruzes(codigo_cantidad_cruces,descripcion)VALUES('0','0');
INSERT INTO banco_sangre_cantidad_cruzes(codigo_cantidad_cruces,descripcion)VALUES('1','+');
INSERT INTO banco_sangre_cantidad_cruzes(codigo_cantidad_cruces,descripcion)VALUES('2','++');
INSERT INTO banco_sangre_cantidad_cruzes(codigo_cantidad_cruces,descripcion)VALUES('3','+++');
INSERT INTO banco_sangre_cantidad_cruzes(codigo_cantidad_cruces,descripcion)VALUES('4','++++');

