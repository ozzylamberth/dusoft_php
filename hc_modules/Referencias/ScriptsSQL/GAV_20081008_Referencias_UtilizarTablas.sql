
SELECT diagnostico_id, diagnostico_nombre 
FROM diagnosticos

SELECT apoyod_tipo_id, descripcion 
FROM apoyod_tipos

SELECT clase_diagnost_id, descripcion 
FROM clase_diagnosticos

SELECT servicio, descripcion 
FROM servicios 

SELECT centro_remision, descripcion 
FROM centros_remision 


INSERT INTO clase_diagnosticos(
    clase_diagnost_id,
    descripcion
)VALUES(
    '0',
    'PRESUNTIVO'
);


INSERT INTO clase_diagnosticos(
    clase_diagnost_id,
    descripcion
)VALUES(
    '1',
    'DEFINITIVO'
);



AND a.grupo_tipo_cargo = ''
AND a.cargo LIKE '%'
AND a.descripcion LIKE ''
AND a.descripcion LIKE '%%'

AND a.departamento = ''
AND a.especialidad IN ()


--

SELECT count(*) 
	FROM apoyod_solicitud_frecuencia a, cups b, 
		apoyod_tipos c 
	WHERE a.cargo = b.cargo 
	AND b.sw_estado = '1' 
	AND b.grupo_tipo_cargo = c.apoyod_tipo_id 

SELECT count(*) 
	FROM cups a, apoyod_tipos b 
	WHERE a.grupo_tipo_cargo = b.apoyod_tipo_id 
	AND a.sw_estado = '1' 

SELECT DISTINCT a.cargo, b.descripcion, c.apoyod_tipo_id, 
	c.descripcion as tipo 
	FROM apoyod_solicitud_frecuencia a, cups b, 
	apoyod_tipos c 
	WHERE a.cargo = b.cargo 
	AND b.sw_estado = '1' 
	AND b.grupo_tipo_cargo = c.apoyod_tipo_id 

SELECT a.cargo, a.descripcion, b.apoyod_tipo_id, 
	b.descripcion as tipo 
	FROM cups a, apoyod_tipos b 
	WHERE a.grupo_tipo_cargo = b.apoyod_tipo_id 
	AND a.sw_estado = '1' 
	
--SELECT de Pruebas para buscador	
SELECT diagnostico_id, diagnostico_nombre 
				FROM diagnosticos 
				WHERE diagnostico_nombre LIKE '%".$nomDiag."%';
	
SELECT COUNT(*) FROM(".$sql.") AS A 

"LIMIT ".$this->limit." OFFSET ".$this->offset;


--

SELECT diagnostico_id, diagnostico_nombre 
FROM diagnosticos 
WHERE diagnostico_nombre LIKE '%MAR%';
				
SELECT COUNT(*) FROM(
	SELECT diagnostico_id, diagnostico_nombre 
	FROM diagnosticos 
	WHERE diagnostico_nombre LIKE '%MAR%'
) AS A 
				
				
SELECT diagnostico_id, diagnostico_nombre 
FROM diagnosticos 
WHERE diagnostico_nombre LIKE '%MAR%'  
LIMIT 20;


SELECT diagnostico_id, diagnostico_nombre 
FROM diagnosticos 
WHERE diagnostico_nombre LIKE '%MAR%'  
LIMIT 5 OFFSET 0 ;

SELECT diagnostico_id, diagnostico_nombre 
FROM diagnosticos 
WHERE diagnostico_nombre LIKE '%MAR%'  
LIMIT 5 OFFSET 5 ;

SELECT diagnostico_id, diagnostico_nombre 
FROM diagnosticos 
WHERE diagnostico_nombre LIKE '%MAR%'  
LIMIT 5 OFFSET 10 ;



DROP TABLE clase_diagnosticos

DROP TABLE notas_evolucion CASCADE


SELECT diagnostico_id, paciente_id, tipo_id_paciente 
FROM diagnostico_paciente 
WHERE paciente_id = '' AND tipo_id_paciente = '';


SELECT di.diagnostico_id AS diagnostico_id, dp.paciente_id, dp.tipo_id_paciente, 
cd.clase_diagnost_id AS clase_diagnost_id, diagnostico_nombre, cd.descripcion AS descripcion 
FROM diagnostico_paciente dp, clase_diagnosticos cd, diagnosticos di 
WHERE di.diagnostico_id = dp.diagnostico_id AND dp.clase_diagnost_id = cd.clase_diagnost_id 
AND dp.paciente_id = '38467208' AND dp.tipo_id_paciente = 'CC';


DROP TABLE diagnostico_paciente CASCADE;

CREATE TABLE clase_diagnosticos(
	clase_diagnost_id character(1) NOT NULL,
	descripcion character varying(20) NOT NULL
);


INSERT INTO clase_diagnosticos(
    clase_diagnost_id,
    descripcion
)VALUES(
    '0',
    'PRESUNTIVO'
);


INSERT INTO clase_diagnosticos(
    clase_diagnost_id,
    descripcion
)VALUES(
    '1',
    'DEFINITIVO'
);


--Tabla con los diagnosticos de cada paciente
CREATE TABLE diagnostico_paciente(
    diagnos_pacien_id serial NOT NULL,
    diagnostico_id character varying(6) NOT NULL,
    paciente_id character varying(32) NOT NULL,
    tipo_id_paciente character varying(3) NOT NULL,
    clase_diagnost_id character(1)
);


DROP TABLE referencias CASCADE;

DROP TABLE referencia_evolu_diganost CASCADE;


referencias_referencia_id_seq

INSERT INTO TABLE referencias(
    referencia_id serial NOT NULL,
    paciente_id character varying(32) NOT NULL,
    tipo_id_paciente character varying(3) NOT NULL,
    resum_cuadr_clin text,    
    hallaz_relevan_exam text,
    plan_trata_reali text
)VALUES(
    1,
    '',
    '',
    '',
    '',
    ''
);


INSERT INTO referencias(
    referencia_id,
    paciente_id,
    tipo_id_paciente,
    fecha,
    empr_trab, 
    estableci_id, 
    servicio_id, 
    moti_refer, 
    resum_cuadr_clin, 
    hallaz_relevan_exam, 
    plan_trata_reali,
    sala, 
    cama,
    cod_profes
)VALUES(
    default,
    '1130626932',
    'CC',
    NOW(),
    'nnsodfs sd saksojn',
    '05',
    '4',
    'moti_refer',
    'resum_cuadr_clin',
    'hallaz_relevan_exam',
    'plan_trata_reali',
    '789',
    '148',
    1942
);


INSERT INTO referencias(
                    referencia_id,
                    paciente_id,
                    tipo_id_paciente,
                    fecha, 
                    empr_trab, 
                    estableci_id, 
                    servicio_id, 
                    moti_refer, 
                    resum_cuadr_clin, 
                    hallaz_relevan_exam, 
                    plan_trata_reali,
                    sala, 
                    cama,
                    cod_profes
                )VALUES(
                    default,
                    '".$pacientId."',
                    '".$tipoPacientId."',
                    NOW(),
                    '".$emprtrab."',
                    '".$estableci."',
                    '".$servicio."',
                    '".$motiRefer."',
                    '".$resCuaClin."',
                    '".$hallRelExam."',
                    '".$planTratReali."',
                    '".$sala."',
                    '".$cama."',
                    1942
                ); 

SELECT NEXTVAL('referencias_referencia_id_seq') AS sq

SELECT SETVAL('referencias_referencia_id_seq', 1)

--SELECT SETVAL('referencias_referencia_id_seq', ".($indice['sq']-1).")
                                

INSERT INTO diagnostico_paciente(
    diagnostico_id, 
    paciente_id, 
    tipo_id_paciente,
    referencia_id,
    clase_diagnost_id 
) 
VALUES(
    'B330', 
    '38467208',
    'CC',
    2,
    '0'
); 


INSERT INTO hc_diagnosticos_egreso(
    usuario_id, 
    tipo_diagnostico_id, 
    evolucion_id,
    sw_principal,
    tipo_diagnostico,
    sw_ficha_llena, 
    referencia_id
) 
VALUES(
    1942, 
    'T200',
    1691659,
    '0',
    '0',
    '0',
    2
); 


INSERT INTO hc_diagnosticos_egreso(
    usuario_id, 
    tipo_diagnostico_id, 
    evolucion_id,
    sw_principal,
    tipo_diagnostico,
    sw_ficha_llena, 
    referencia_id
) 
VALUES(
    1942, 
    'T200',
    1691659,
    '0',
    '0',
    '1',
    2
);


INSERT INTO hc_diagnosticos_egreso(
    usuario_id,
    tipo_diagnostico_id,
)
values(
);


SELECT tipo_diagnostico_id, tipo_diagnostico, referencia_id, usuario_id 
FROM hc_diagnosticos_egreso 
WHERE referencia_id = ;

SELECT tipo_diagnostico_id, tipo_diagnostico, referencia_id, usuario_id 
FROM hc_diagnosticos_egreso 
WHERE tipo_diagnostico_id = 'T200';

SELECT tipo_diagnostico_id, tipo_diagnostico, referencia_id, usuario_id 
FROM hc_diagnosticos_egreso 
WHERE usuario_id = 1942;

INSERT INTO referencia_evolu_diganost(
    referencia_id, 
    evolucion_id,
    diagnostico_id,
    clase_diagnost_id
)
VALUES(
    2,
    1691659,
    'T200',
    '1'
);

