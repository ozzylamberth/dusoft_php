CREATE TABLE estructura_planes
(
  plan_id SERIAL NOT NULL,
  nombre_tbl character varying(30) NOT NULL,
  descripcion character varying(40) NOT NULL,
  estado 	character varying(1) NOT NULL
);

ALTER TABLE estructura_planes ADD PRIMARY KEY(plan_id);

INSERT INTO estructura_planes VALUES (DEFAULT,'asoci_indig_cauca',	'ASOCIACION INDIGENA DEL CAUCA',	'1');
INSERT INTO estructura_planes VALUES (DEFAULT,'multimedicas_duana',	'MULTIMEDICAS DUANA',	'1');
INSERT INTO estructura_planes VALUES (DEFAULT,'ponal_dptal',	'POLICIA NACIONAL DEPARTAMENTAL',	'1');
INSERT INTO estructura_planes VALUES (DEFAULT,'proinsalud_duana',	'PROINSALUD DUANA',	'1');
