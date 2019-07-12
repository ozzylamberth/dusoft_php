

INSERT INTO system_hc_submodulos(
	submodulo, 
	descripcion, 
	version_numero, 
	version_info, 
	activo, 
	sexo_id,
	gestacion, 
	edad_max, 
	edad_min, 
	sw_submodulo_sistema, 
	sw_imprime, 
	sw_print_persist    
)
VALUES(
	'FichaFamiliar', 
	'Datos de la Historia Familiar', 
	1.00, 
	'1', 
	'1', 
	NULL, 
	NULL, 
	NULL, 
	NULL, 
	'0', 
	'1', 
	'0'
);


SELECT paciente_id, tipo_id_paciente, primer_apellido, segundo_apellido, 
primer_nombre, segundo_nombre, fecha_nacimiento, residencia_direccion, 
residencia_telefono, zona_residencia, sexo_id, tipo_estado_civil_id, 
tipo_pais_id, tipo_dpto_id, tipo_mpio_id 
FROM pacientes
WHERE tipo_id_paciente = 'CC' AND paciente_id = '29500379';


SELECT paciente_id, tipo_id_paciente, primer_apellido AS Cato 
FROM pacientes


SELECT NEXTVAL('miembro_familiar_familiar_id_seq') AS sq


INSERT INTO miembro_familiar(
	familiar_id, 
	paciente_id, 
	primer_apellido, 
	segundo_apellido,
	primer_nombre,
	segundo_nombre,
	parentesco,
	fecha_nacim,
	sexo,
	escolaridad, 
	esquema_vacunas,
	salud_bucal,
	rie_enf_disca,  
	hist_clinica,
	no_identi_fam,
	ocupacion, 
	embarazada,
	edad_fallece,
	causa, 
	difunto
)
VALUES(
	(SELECT NEXTVAL('miembro_familiar_familiar_id_seq') AS sq), 
	'5465464', 
	'ahola', 
	'lpww', 
	'vbeq', 
	'qwzxa', 
	'Primo de Tercer Grado', 
	'12/04/1991', 
	'1', 
	1, 
	'1', 
	'1', 
	'Descripcion de esto', 
	'3545487',
	'874641',
	'estilista',
	'1',
	NULL,
	NULL,
	NULL
);


INSERT INTO miembro_familiar(
	familiar_id, 
	paciente_id, 
	primer_apellido, 
	segundo_apellido,
	primer_nombre,
	segundo_nombre,
	parentesco,
	fecha_nacim,
	sexo,
	escolaridad, 
	esquema_vacunas,
	salud_bucal,
	rie_enf_disca,  
	hist_clinica,
	no_identi_fam,
	ocupacion, 
	embarazada,
	edad_fallece,
	causa, 
	difunto
)
VALUES(
	(SELECT NEXTVAL('miembro_familiar_familiar_id_seq') AS sq), 
	'151515', 
	'aa', 
	'bb', 
	'cc', 
	'dd', 
	'Primo de Tercer Grado', 
	'12/04/1991', 
	'1', 
	1, 
	'1', 
	'1', 
	'Descripcion de esto', 
	'3545487',
	'874641',
	'estilista',
	'1',
	NULL,
	NULL,
	NULL
);


DELETE FROM miembro_familiar 
WHERE familiar_id ILIKE '%%';

DELETE FROM miem_fam_embarazadas 
WHERE embarazada_id ILIKE '%%';

DELETE FROM ficha_familar 
WHERE num_ficha_fam ILIKE '%%';


SELECT familiar_id, paciente_id, primer_apellido, segundo_apellido, 
	primer_nombre, segundo_nombre,  parentesco, fecha_nacim, sexo, 
	escolaridad, esquema_vacunas,  salud_bucal, rie_enf_disca, 
	hist_clinica, no_identi_fam, ocupacion, embarazada 
FROM miembro_familiar
WHERE paciente_id = '5465464';


INSERT INTO miem_fam_embarazadas(
	embarazada_id,
	familiar_id,
	fecha_ult_menstruacion,
	fecha_prob_parto,
	semanas_gesta,
	pri_dosis,
	seg_dosis,
	refuerzo_dosis,
	gestas,
	partos,
	abortos,
	cesareas,
	ante_pato_obstre
)
VALUES(
	(SELECT NEXTVAL('miem_fam_embarazadas_embarazada_id_seq') AS sq),
	26,
	'03/02/2008',
	'09/12/2008',
	4,
	'30/05/2008',
	'15/06/2008',
	'07/12/2008',
	2,
	3,
	1,
	4,
	'Antecedentes Patologicos Obstreticos'
);


SELECT embarazada_id, familiar_id, fecha_ult_menstruacion, 
	fecha_prob_parto, semanas_gesta, pri_dosis, seg_dosis, 
	refuerzo_dosis, gestas, partos, abortos, cesareas, 
	ante_pato_obstre
FROM miem_fam_embarazadas 


SELECT primer_apellido, segundo_apellido, primer_nombre, embarazada_id, miembro_familiar.familiar_id AS familiar_id, fecha_ult_menstruacion, 
	fecha_prob_parto, semanas_gesta, pri_dosis, seg_dosis, 
	refuerzo_dosis, gestas, partos, abortos, cesareas, 
	ante_pato_obstre
FROM miem_fam_embarazadas, miembro_familiar 
WHERE miem_fam_embarazadas.familiar_id = miembro_familiar.familiar_id 





SELECT edad('1978-11-28');


SELECT familiar_id, paciente_id, primer_apellido, segundo_apellido, 
	primer_nombre, segundo_nombre,  parentesco, fecha_nacim,  
	(SELECT edad(fecha_nacim)) as edad, sexo, 
	escolaridad, esquema_vacunas,  salud_bucal, rie_enf_disca, 
	hist_clinica, no_identi_fam, ocupacion, embarazada 
FROM miembro_familiar;


SELECT escolaridad, 
	(CASE WHEN escolaridad = 1 THEN 'NINGUNA' 
		WHEN escolaridad = 2 THEN 'PRIMARIA' 
		WHEN escolaridad = 3 THEN 'SECUNDARIA' 
		WHEN escolaridad = 4 THEN 'SUPERIOR'
		ELSE 'OTRA' 
	END) As valor  
FROM miembro_familiar;


SELECT escolaridad val, 
	(CASE WHEN val = 1 THEN 'NINGUNA' 
		WHEN val = 2 THEN 'PRIMARIA' 
		WHEN val = 3 THEN 'SECUNDARIA' 
		WHEN val = 4 THEN 'SUPERIOR'
		ELSE 'OTRA' 
	END) As valor  
FROM miembro_familiar;




SELECT  
	(CASE WHEN (SELECT edad(fecha_nacim)) < 5 THEN 'Menos de 5' 
		WHEN (SELECT edad(fecha_nacim)) < 15 THEN 'Menos de 15' 
		WHEN (SELECT edad(fecha_nacim)) < 20 THEN 'Menos de 20' 
		WHEN (SELECT edad(fecha_nacim)) < 25 THEN 'Menos de 25' 
		WHEN (SELECT edad(fecha_nacim)) < 30 THEN 'Menos de 30' 
		WHEN (SELECT edad(fecha_nacim)) < 35 THEN 'Menos de 35' 
		ELSE 'OTRA' 
	END) As rango, (SELECT edad(fecha_nacim)) as edad  
FROM miembro_familiar
GROUP BY edad; 


SELECT  
	(CASE WHEN (SELECT edad(fecha_nacim)) < 1 THEN 'MENOR 1 AÑO' 
		WHEN (SELECT edad(fecha_nacim)) >= 1 AND (SELECT edad(fecha_nacim)) <= 4 THEN '1 - 4 AÑOS' 
		WHEN (SELECT edad(fecha_nacim)) >= 5 AND (SELECT edad(fecha_nacim)) <= 9 THEN '5 - 9 AÑOS' 
		WHEN (SELECT edad(fecha_nacim)) >= 10 AND (SELECT edad(fecha_nacim)) <= 19 THEN '10 - 19 AÑOS' 
		WHEN (SELECT edad(fecha_nacim)) >= 20 AND (SELECT edad(fecha_nacim)) <= 64 THEN '20 - 64 AÑOS' 
		WHEN (SELECT edad(fecha_nacim)) <= 65 THEN '65 AÑOS o MAS' 
		ELSE 'OTRA' 
	END) As rango, (SELECT edad(fecha_nacim)) as edad  
FROM miembro_familiar
GROUP BY edad; 


SELECT familiar_id, paciente_id, primer_apellido, segundo_apellido, 
					primer_nombre, segundo_nombre,  parentesco, fecha_nacim,  
					(SELECT edad(fecha_nacim)) as edad, sexo, 
					escolaridad, esquema_vacunas,  salud_bucal, rie_enf_disca, 
					hist_clinica, no_identi_fam, ocupacion, embarazada 
				FROM miembro_familiar
 


SELECT  
	(CASE 
		WHEN (SELECT edad(fecha_nacim)) < 1 
			THEN 'MENOR 1 AÑO' 
		WHEN (SELECT edad(fecha_nacim)) >= 1 
			AND (SELECT edad(fecha_nacim)) <= 4 
			THEN '1 - 4 AÑOS' 
		WHEN (SELECT edad(fecha_nacim)) >= 5 
			AND (SELECT edad(fecha_nacim)) <= 9 
			THEN '5 - 9 AÑOS' 
		WHEN (SELECT edad(fecha_nacim)) >= 10 
			AND (SELECT edad(fecha_nacim)) <= 19 
			THEN '10 - 19 AÑOS' 
		WHEN (SELECT edad(fecha_nacim)) >= 20 
			AND (SELECT edad(fecha_nacim)) <= 64 
			THEN '20 - 64 AÑOS' 
		WHEN (SELECT edad(fecha_nacim)) <= 65 
			THEN '65 AÑOS o MAS' 
		ELSE 'OTRA' 
	END) As rango, (SELECT edad(fecha_nacim)) as edad, familiar_id, paciente_id, 
			primer_apellido, segundo_apellido, primer_nombre, segundo_nombre,  
			parentesco, fecha_nacim, sexo, escolaridad, esquema_vacunas, 
			salud_bucal, rie_enf_disca, hist_clinica, no_identi_fam, ocupacion, 
			embarazada 
FROM miembro_familiar 
GROUP BY edad, familiar_id, paciente_id, 
			primer_apellido, segundo_apellido, primer_nombre, segundo_nombre,  
			parentesco, fecha_nacim, sexo, escolaridad, esquema_vacunas, 
			salud_bucal, rie_enf_disca, hist_clinica, no_identi_fam, ocupacion, 
			embarazada ; 


SELECT  
	(CASE 
		WHEN (SELECT edad(fecha_nacim)) < 1 
			THEN 'MENOR 1 AÑO' 
		WHEN (SELECT edad(fecha_nacim)) >= 1 
			AND (SELECT edad(fecha_nacim)) <= 4 
			THEN '1 - 4 AÑOS' 
		WHEN (SELECT edad(fecha_nacim)) >= 5 
			AND (SELECT edad(fecha_nacim)) <= 9 
			THEN '5 - 9 AÑOS' 
		WHEN (SELECT edad(fecha_nacim)) >= 10 
			AND (SELECT edad(fecha_nacim)) <= 19 
			THEN '10 - 19 AÑOS' 
		WHEN (SELECT edad(fecha_nacim)) >= 20 
			AND (SELECT edad(fecha_nacim)) <= 64 
			THEN '20 - 64 AÑOS' 
		WHEN (SELECT edad(fecha_nacim)) <= 65 
			THEN '65 AÑOS o MAS' 
		ELSE 'OTRA' 
	END) As rango, (SELECT edad(fecha_nacim)) as edad, familiar_id, paciente_id, 
			primer_apellido, segundo_apellido, primer_nombre, segundo_nombre,  
			parentesco, fecha_nacim, sexo, escolaridad, esquema_vacunas, 
			salud_bucal, rie_enf_disca, hist_clinica, no_identi_fam, ocupacion, 
			embarazada, edad_fallece, causa, tipo_identi_fam 
FROM miembro_familiar 
WHERE difunto = '2' 
GROUP BY edad, familiar_id, paciente_id, 
			primer_apellido, segundo_apellido, primer_nombre, segundo_nombre,  
			parentesco, fecha_nacim, sexo, escolaridad, esquema_vacunas, 
			salud_bucal, rie_enf_disca, hist_clinica, no_identi_fam, ocupacion, 
			embarazada, edad_fallece, causa, tipo_identi_fam; 
			
SELECT tipo_parentesco_id, descripcion
FROM tipos_parentescos;
			
			
Datos para Ingresar

05/03/2008
12/08/2008

09/08/2009
09/09/2009
02/09/2010


INSERT INTO ficha_familar(
	num_ficha_fam,
	paciente_id,
	cod_respon,
	fecha_llenado,
	pri_apell_resp, 
	seg_apell_resp,
	pri_nomb_resp,
	seg_nomb_resp,
	num_carpeta 
)VALUES(
	(SELECT NEXTVAL('ficha_familar_num_ficha_fam_seq') AS sq),
	'484453',
	57841,
	NOW(),
	'Fabito',
	'Clavo',
	'Un',
	'Clavito',
	18
);


(SELECT NEXTVAL('miembro_familiar_familiar_id_seq') AS sq)
(SELECT SETVAL('miembro_familiar_familiar_id_seq', (41-1)));

SELECT SETVAL('miembro_familiar_familiar_id_seq', ((SELECT NEXTVAL('miembro_familiar_familiar_id_seq') AS sq)-1));


SELECT SETVAL('ficha_familar_num_ficha_fam_seq', ((SELECT NEXTVAL('ficha_familar_num_ficha_fam_seq') AS sq)-1));


UPDATE miembro_familiar SET 
embarazada = '2' 
WHERE familiar_id = 64 ;



instr_sist
unid_oper
cod_uo
area_no
cod_localiza

sector
manzana
no_familia

barrio
no_casa
comunidad
grupo_cult
nomb_apell_jef_fam

latitud
longitud
altitud


COMMENT ON COLUMN ficha_familar.instr_sist IS 'Instruccion del Sistema';
COMMENT ON COLUMN ficha_familar.unid_oper IS 'Unidad Operativa';
COMMENT ON COLUMN ficha_familar.cod_uo IS 'Instruccion del Sistema';
COMMENT ON COLUMN ficha_familar.area_no IS 'Unidad Operativa';
COMMENT ON COLUMN ficha_familar.cod_localiza IS 'Codigo Localizacion';

COMMENT ON COLUMN ficha_familar.sector IS 'Sector';
COMMENT ON COLUMN ficha_familar.manzana IS 'Manzana';
COMMENT ON COLUMN ficha_familar.no_familia IS 'No de Familia';
COMMENT ON COLUMN ficha_familar.barrio IS 'Barrio';
COMMENT ON COLUMN ficha_familar.comunidad IS 'Comunidad';
COMMENT ON COLUMN ficha_familar.grupo_cult IS 'Grupo Cultural';
COMMENT ON COLUMN ficha_familar.nomb_apell_jef_fam IS 'Nombre Apellido del Jefe Familiar';



ALTER TABLE miembro_familiar DROP COLUMN latitud;
ALTER TABLE miembro_familiar DROP COLUMN longitud;
ALTER TABLE miembro_familiar DROP COLUMN altitud;


SELECT usuario_id, nombre 
FROM system_usuarios 
WHERE usuario_id = 2;

ALTER TABLE ficha_familar DROP COLUMN pri_apell_resp;
ALTER TABLE ficha_familar DROP COLUMN seg_apell_resp;
ALTER TABLE ficha_familar DROP COLUMN pri_nomb_resp;
ALTER TABLE ficha_familar DROP COLUMN seg_nomb_resp;

DROP TABLE ficha_familar CASCADE


INSERT INTO tipo_comunidad(
	comunidad_id,
	descripcion
)VALUES(
	'001',
	'Kechua'
);

INSERT INTO tipo_comunidad(
	comunidad_id,
	descripcion
)VALUES(
	'002',
	'Tinsu'
);


INSERT INTO grupo_cultural(
	grup_cult_id,
	descripcion
)VALUES(
	'001',
	'Indigena'
);

INSERT INTO grupo_cultural(
	grup_cult_id,
	descripcion
)VALUES(
	'002',
	'Afroamericano'
);


 


