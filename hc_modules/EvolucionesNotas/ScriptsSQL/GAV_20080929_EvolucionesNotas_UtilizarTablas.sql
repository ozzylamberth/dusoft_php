

INSERT INTO notas_evolucion(
	id_nota_evol,
	fecha, 
	txt_nota_evol
) 
VALUES(
	(SELECT NEXTVAL('notas_evolucion_id_nota_evol_seq') AS sq), 
	NOW(),
	'hsjhsenfs fsifseoifsaefsefsa ofesaiofaspon'
);

INSERT INTO notas_evolucion(
	id_nota_evol,
	fecha, 
	txt_nota_evol, 
	empresa_id,  
	usuario_id,
	evolucion_id,
	paciente_id,
	tipo_id_paciente  
) 
VALUES(
	(SELECT NEXTVAL('notas_evolucion_id_nota_evol_seq') AS sq), 
	NOW(), 
	'fgjkhjsnfsf sfjksahfsan', 
	'01', 
	1942,
	1691614,
	'1130626932 ',
	'CC' 
);

 
DROP TABLE notas_evolucion CASCADE


SELECT id_nota_evol, fecha, txt_nota_evol
FROM notas_evolucion;


SELECT id_nota_evol, fecha, txt_nota_evol
					FROM notas_evolucion; 