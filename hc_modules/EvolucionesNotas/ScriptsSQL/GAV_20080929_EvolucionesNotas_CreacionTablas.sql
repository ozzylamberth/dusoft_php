

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
	'EvolucionesNotas', 
	'Notas de la Evolucion', 
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


INSERT INTO historias_clinicas_templates(
	hc_modulo, 
	submodulo, 
	paso, 
	secuencia, 
	hc_seccion_id, 
	sw_mostrar, 
	sw_siquiatria, 
	sw_obligatorio_cierre, 
	titulo_mostrar, 
	parametros, 
	sexo_id, 
	edad_max, 
	edad_min, 
	sw_omitir
) 
VALUES(
	'Hospitalizacion', 
	'EvolucionesNotas', 
	32, 
	0, 
	'1', 
	'1', 
	'0', 
	'0', 
	NULL, 
	NULL, 
	NULL, 
	NULL, 
	NULL, 
	'0'
);


CREATE TABLE notas_evolucion(
	id_nota_evol serial NOT NULL,
	fecha timestamp without time zone,
	txt_nota_evol text
);

ALTER TABLE notas_evolucion ADD PRIMARY KEY (id_nota_evol);
ALTER TABLE notas_evolucion ADD COLUMN empresa_id character(2);
ALTER TABLE notas_evolucion ADD COLUMN usuario_id integer;
ALTER TABLE notas_evolucion ADD COLUMN evolucion_id integer; 
ALTER TABLE notas_evolucion ADD COLUMN paciente_id character varying(32);
ALTER TABLE notas_evolucion ADD COLUMN tipo_id_paciente character varying(3); 
ALTER TABLE notas_evolucion ADD COLUMN ingreso integer; 

ALTER TABLE notas_evolucion ADD FOREIGN KEY (empresa_id) REFERENCES empresas(empresa_id);
ALTER TABLE notas_evolucion ADD FOREIGN KEY (usuario_id) REFERENCES system_usuarios(usuario_id);
ALTER TABLE notas_evolucion ADD FOREIGN KEY (evolucion_id) REFERENCES hc_evoluciones(evolucion_id);

ALTER TABLE notas_evolucion ADD FOREIGN KEY (ingreso) REFERENCES ingresos(ingreso);

--ALTER TABLE notas_evolucion ADD FOREIGN KEY (paciente_id, tipo_id_paciente) REFERENCES pacientes(paciente_id, tipo_id_paciente);

ALTER TABLE notas_evolucion ADD FOREIGN KEY (paciente_id, tipo_id_paciente) REFERENCES pacientes(paciente_id, tipo_id_paciente);


COMMENT ON COLUMN notas_evolucion.id_nota_evol IS 'Codigo Nota Evolucion';
COMMENT ON COLUMN notas_evolucion.fecha IS 'Fecha de Nota Evolucion';
COMMENT ON COLUMN notas_evolucion.txt_nota_evol IS 'Texto de la Nota Evolucion';
COMMENT ON COLUMN notas_evolucion.empresa_id IS 'Codigo de la Entidad';
COMMENT ON COLUMN notas_evolucion.usuario_id IS 'Codgio del Usuario que hace la Nota Evolucion';
COMMENT ON COLUMN notas_evolucion.evolucion_id IS 'Codgio de la Evolucion a la que hace referencia la Nota de Evolucion';
COMMENT ON COLUMN notas_evolucion.paciente_id IS 'Identificacion del Paciente';
COMMENT ON COLUMN notas_evolucion.tipo_id_paciente IS 'Tipo de Identificacion del paciente';
COMMENT ON COLUMN notas_evolucion.ingreso IS 'Numero del Ingreso';
