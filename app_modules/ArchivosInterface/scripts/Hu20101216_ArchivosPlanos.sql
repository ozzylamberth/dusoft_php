--DROP TABLE interfaces_planes.despacho_formulas;
--DROP TABLE interfaces_planes.equivalencias_productos;
--DROP TABLE interfaces_planes.medicos_externos_tmp;
--DROP TABLE medicos_externos;

CREATE TABLE interfaces_planes.medicos_externos_tmp
(
  medicos_externos_tmp_id SERIAL NOT NULL,
  tipo_id_tercero character(2) NOT NULL,
  tercero_id character varying(16) NOT NULL,
  especialidad CHARACTER VARYING(4),
  nombre_profesional character varying(100) NOT NULL,
  apellido_profesional character varying(100)
);

ALTER TABLE ONLY interfaces_planes.medicos_externos_tmp
ADD PRIMARY KEY (medicos_externos_tmp_id);

ALTER TABLE ONLY interfaces_planes.medicos_externos_tmp ADD FOREIGN KEY (tipo_id_tercero) 
REFERENCES public.tipo_id_terceros(tipo_id_tercero) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE interfaces_planes.medicos_externos_tmp ADD FOREIGN KEY (especialidad) 
REFERENCES especialidades(especialidad) ON UPDATE CASCADE ON DELETE CASCADE;

COMMENT ON TABLE interfaces_planes.medicos_externos_tmp IS 'Tabla donde se guaradan los profesionales externos';
COMMENT ON COLUMN interfaces_planes.medicos_externos_tmp.medicos_externos_tmp_id IS '(PK) Identificador del temporal';
COMMENT ON COLUMN interfaces_planes.medicos_externos_tmp.tipo_id_tercero IS 'Tipo de identificacion del tercero';
COMMENT ON COLUMN interfaces_planes.medicos_externos_tmp.tercero_id IS 'Identificacion del tercero';
COMMENT ON COLUMN interfaces_planes.medicos_externos_tmp.nombre_profesional IS 'Nombres del profesional';
COMMENT ON COLUMN interfaces_planes.medicos_externos_tmp.apellido_profesional IS 'Apellidos del profesional';
COMMENT ON COLUMN interfaces_planes.medicos_externos_tmp.especialidad IS '(FK) Identificador de la especialidad';

GRANT ALL ON TABLE interfaces_planes.medicos_externos_tmp TO siis;

CREATE TABLE medicos_externos
(
  tipo_id_tercero character(2) NOT NULL,
  tercero_id character varying(16) NOT NULL,
  especialidad CHARACTER VARYING(4),
  nombre_profesional character varying(100) NOT NULL,
  apellido_profesional character varying(100) NOT NULL,
  fecha_registro TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT NOW(),
  usuario_id INTEGER NOT NULL
);

ALTER TABLE ONLY medicos_externos
ADD PRIMARY KEY (tipo_id_tercero, tercero_id);

ALTER TABLE ONLY medicos_externos ADD FOREIGN KEY (tipo_id_tercero) 
REFERENCES public.tipo_id_terceros(tipo_id_tercero) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE ONLY medicos_externos ADD FOREIGN KEY (usuario_id) 
REFERENCES public.system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE medicos_externos ADD FOREIGN KEY (especialidad) 
REFERENCES especialidades(especialidad) ON UPDATE CASCADE ON DELETE CASCADE;

COMMENT ON TABLE medicos_externos IS 'Tabla donde se guaradan los profesionales externos';
COMMENT ON COLUMN medicos_externos.tipo_id_tercero IS '(PK - FK)Tipo de identificacion del tercero';
COMMENT ON COLUMN medicos_externos.tercero_id IS '(PK) Identificacion del tercero';
COMMENT ON COLUMN medicos_externos.especialidad IS '(FK) Identificador de la especialidad';
COMMENT ON COLUMN medicos_externos.nombre_profesional IS 'Nombres del profesional';
COMMENT ON COLUMN medicos_externos.apellido_profesional IS 'Apellidos del profesional';
COMMENT ON COLUMN medicos_externos.fecha_registro IS 'Fecha de registro del profesional';
COMMENT ON COLUMN medicos_externos.usuario_id IS '(FK) Identificador del usuario que registra';

GRANT ALL ON TABLE medicos_externos TO siis;

CREATE TABLE interfaces_planes.equivalencia_farmacia
(
  farmacia_id CHARACTER VARYING(10) NOT NULL,
  empresa_id CHARACTER(2) NOT NULL,
  centro_utilidad CHARACTER(2) NOT NULL,
  bodega CHARACTER VARYING(2) NOT NULL,
  lote_default CHARACTER VARYING(255) NOT NULL,
  fecha_vencimiento_default DATE NOT NULL
);

ALTER TABLE interfaces_planes.equivalencia_farmacia ADD PRIMARY KEY(farmacia_id,empresa_id);
ALTER TABLE interfaces_planes.equivalencia_farmacia ADD FOREIGN KEY (empresa_id, centro_utilidad, bodega) 
REFERENCES bodegas(empresa_id, centro_utilidad, bodega) ON UPDATE CASCADE ON DELETE RESTRICT;

COMMENT ON TABLE interfaces_planes.equivalencia_farmacia IS 'Tabla donde se registra la bodega que movera la farmacia para los despachos';
COMMENT ON COLUMN interfaces_planes.equivalencia_farmacia.farmacia_id IS '(PK - FK) Identificador de la farmacia, codigo externo';
COMMENT ON COLUMN interfaces_planes.equivalencia_farmacia.empresa_id IS '(PK - FK) Identificador de la empresa';
COMMENT ON COLUMN interfaces_planes.equivalencia_farmacia.centro_utilidad IS '(FK) Identificador del centro de utilidad de la empresa';
COMMENT ON COLUMN interfaces_planes.equivalencia_farmacia.bodega IS '(FK) Identificador de la bodega';
COMMENT ON COLUMN interfaces_planes.equivalencia_farmacia.lote_default IS 'Lote por defecto para los productos que no lo traen';
COMMENT ON COLUMN interfaces_planes.equivalencia_farmacia.fecha_vencimiento_default IS 'Fecha de vencimiento por defecto para los productos que no lo traen';

GRANT ALL ON TABLE interfaces_planes.equivalencia_farmacia TO siis;

CREATE TABLE interfaces_planes.equivalencias_productos 
(
  empresa_id CHARACTER(2) NOT NULL,
  codigo_producto_ext CHARACTER VARYING(50) NOT NULL,
  codigo_producto CHARACTER VARYING(40) NOT NULL
);

COMMENT ON TABLE interfaces_planes.equivalencias_productos IS 'Tabla donde se guardan las equivalencias de los codigos externos con los manejados por siis';
COMMENT ON COLUMN interfaces_planes.equivalencias_productos.empresa_id IS '(PK - FK) Referenvia al producto del inventario de la empresa';
COMMENT ON COLUMN interfaces_planes.equivalencias_productos.codigo_producto_ext IS '(PK - FK) Codigo del producto externo';
COMMENT ON COLUMN interfaces_planes.equivalencias_productos.codigo_producto IS '(FK) Referenvia al producto del inventario de la empresa';

ALTER TABLE interfaces_planes.equivalencias_productos ADD PRIMARY KEY (empresa_id,codigo_producto_ext);
ALTER TABLE interfaces_planes.equivalencias_productos ADD UNIQUE (empresa_id,codigo_producto_ext,codigo_producto);

ALTER TABLE interfaces_planes.equivalencias_productos ADD FOREIGN KEY (empresa_id, codigo_producto) 
REFERENCES inventarios(empresa_id, codigo_producto) ON UPDATE RESTRICT ON DELETE RESTRICT;

GRANT ALL ON TABLE interfaces_planes.equivalencias_productos TO siis;

CREATE TABLE interfaces_planes.despacho_formulas
(
  despacho_formula_id SERIAL NOT NULL,
  archivo_cargado_id INTEGER NOT NULL,
  despacho_identificador INTEGER ,
  despacho_secuencia CHARACTER(1),
  numero_paciente_sisap INTEGER ,
  sw_encuentro_paciente CHARACTER(1),
  tipo_documento_formula CHARACTER(4),
  formula_id CHARACTER VARYING(10) ,
  fecha_formula TIMESTAMP WITHOUT TIME ZONE,
  formula_digital_id CHARACTER VARYING(10),
  tipo_id_paciente CHARACTER(2),
  paciente_id CHARACTER VARYING(32),
  tipo_id_tercero CHARACTER(2),
  tercero_id CHARACTER VARYING(32),
  grupo_especialidad CHARACTER VARYING(32),
  especialidad CHARACTER VARYING(4),
  fecha_radicacion TIMESTAMP WITHOUT TIME ZONE,
  sw_transcripcion CHARACTER(1),
  autorizacion TEXT,
  servicio CHARACTER VARYING(2),
  ips_ponal CHARACTER VARYING(50),
  usuario_id INTEGER, 
  diagnostico_id CHARACTER VARYING(6),
  producto_detalle CHARACTER VARYING(30),
  codigo_producto CHARACTER VARYING(50) NOT NULL,
  codigo_cssfmpn CHARACTER VARYING(50),
  molecula_id CHARACTER VARYING(10),
  laboratorio_id CHARACTER VARYING(4),
  codigo_estado CHARACTER VARYING(10),
  descripcion_estado CHARACTER VARYING(50),
  codigo_autorizacion CHARACTER VARYING(30), 
  accion_seguir CHARACTER VARYING(100),
  fecha_entrega TIMESTAMP WITHOUT TIME ZONE,
  cantidad_formula CHARACTER VARYING(20),
  cantidad_entregada CHARACTER VARYING(20),
  valor_unitario NUMERIC(12,4), 
  valor_total NUMERIC(12,4),
  usuario_id_despacho INTEGER,
  codigo_licitacion CHARACTER VARYING(50),
  descripcion_licitacion TEXT,
  empresa_id CHARACTER(2) NOT NULL,
  sw_tipo_mov CHARACTER(1) NOT NULL,
  farmacia_id CHARACTER VARYING(10) NOT NULL,
  centro_utilidad CHARACTER(2) NOT NULL,
  bodega CHARACTER VARYING(2) NOT NULL,
  lote 	CHARACTER VARYING(255) NOT NULL,
  fecha_vencimiento DATE NOT NULL
);

ALTER TABLE interfaces_planes.despacho_formulas ADD FOREIGN KEY(archivo_cargado_id)
REFERENCES archivos_cargados (archivo_cargado_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE interfaces_planes.despacho_formulas ADD FOREIGN KEY(tipo_id_paciente,paciente_id)
REFERENCES pacientes (tipo_id_paciente,paciente_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE interfaces_planes.despacho_formulas ADD FOREIGN KEY(tipo_id_tercero,tercero_id)
REFERENCES medicos_externos (tipo_id_tercero,tercero_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE interfaces_planes.despacho_formulas ADD FOREIGN KEY(especialidad)
REFERENCES especialidades(especialidad) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE interfaces_planes.despacho_formulas ADD FOREIGN KEY(servicio)
REFERENCES servicios(servicio) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE interfaces_planes.despacho_formulas ADD FOREIGN KEY(empresa_id,codigo_producto)
REFERENCES inventarios(empresa_id,codigo_producto) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE interfaces_planes.despacho_formulas ADD FOREIGN KEY (laboratorio_id) 
REFERENCES inv_laboratorios(laboratorio_id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE interfaces_planes.despacho_formulas ADD FOREIGN KEY (molecula_id) 
REFERENCES inv_moleculas(molecula_id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE interfaces_planes.despacho_formulas ADD FOREIGN KEY (usuario_id) 
REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE interfaces_planes.despacho_formulas ADD FOREIGN KEY (empresa_id, centro_utilidad, bodega) 
REFERENCES bodegas(empresa_id, centro_utilidad, bodega) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE interfaces_planes.despacho_formulas ADD FOREIGN KEY (farmacia_id, empresa_id) 
REFERENCES interfaces_planes.equivalencia_farmacia(farmacia_id, empresa_id) ON UPDATE CASCADE ON DELETE CASCADE;

COMMENT ON TABLE interfaces_planes.despacho_formulas IS 'Tabla de interface donde se suben los archivos planos de los despachos';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.despacho_formula_id IS '(PK) Identificador de la tabla';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.archivo_cargado_id IS '(FK) Identificador del archivo que contiene los datos';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.despacho_identificador IS 'Valor único que identifica  al registro de despacho';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.despacho_secuencia IS 'Secuencia en el proceso de atención en SISAP';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.numero_paciente_sisap IS 'Número de paciente en SISAP';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.sw_encuentro_paciente IS 'Encuentro entre el paciente y el médico';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.tipo_documento_formula IS 'Tipo de documento (Fórmula)';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.formula_id IS 'Número de fórmula SISAP';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.fecha_formula IS 'Fecha de emisión de la fórmula';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.formula_digital_id IS 'Numero de la Formula si es digitalizada (Fórmula manual)';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.tipo_id_paciente IS '(FK) Tipo de documento del afiliado a sanidad (Paciente)';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.paciente_id IS '(FK) Número de documento del afiliado a sanidad (Paciente)';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.tipo_id_tercero IS '(FK) Tipo de documento del profesional que prescribe o formula';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.tercero_id IS '(FK) Numero de documento profesional que prescribe o formula';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.grupo_especialidad IS 'Código agrupador de especialidad Profesional que formula';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.especialidad IS '(FK) Código especialidad del Profesional que formula';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.sw_transcripcion IS 'Fórmula transcrita (Si=1, No= 0) ';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.autorizacion IS 'Fórmula requirió autorización Num. Autoriza Encabezado';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.servicio IS '(FK) Ámbito de atención en el cual se prescribió la fórmula';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.ips_ponal IS 'Código de la IPS PONAL (Cod. Establecimiento Sanidad)';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.usuario_id IS 'Código funcionario radicador farmacia';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.diagnostico_id IS 'Código Diagnostico de la atención. (CIE 10).';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.producto_detalle IS 'Identificador único detalle de la formula (por producto)';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.codigo_producto IS '(FK) Código del producto despachado';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.codigo_cssfmpn IS 'Código del Concejo Superior de Salud de las Fuerzas Militares y de la Policía nacional (Código CUA)';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.molecula_id IS '(FK) Código de la molécula despachada';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.laboratorio_id IS '(FK) Código laboratorio producto despachado';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.codigo_estado IS 'Código estado producto';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.descripcion_estado IS 'Nombre estado producto';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.codigo_autorizacion IS 'Código de la autorización del producto';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.accion_seguir IS 'Acción a seguir con el producto';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.fecha_entrega IS 'Fecha de entrega del producto';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.cantidad_formula IS 'Cantidad prescrita en la fórmula. (Inicialmente)';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.cantidad_entregada IS 'Cantidad entregada del producto.';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.valor_unitario IS 'Valor unitario del producto (Por unidad de despacho)';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.valor_total IS 'Valor total  del despacho por producto.';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.usuario_id_despacho IS 'Código del funcionario despachador.';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.codigo_licitacion IS 'Código licitación';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.descripcion_licitacion IS 'Nombre de la licitación';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.empresa_id IS '(FK) Código de la empresa';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.farmacia_id IS 'Código de la farmacia que realiza el despacho';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.sw_tipo_mov IS 'Tipo de Movimiento (Despacho = 0, Devolución = 1)';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.centro_utilidad IS '(FK) Identificador de la bodega de donde se hara el despacho';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.bodega IS '(FK) Identificador de la bodega de donde se hara el despacho';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.lote IS 'Identificador del lote relacionado en el archivo';
COMMENT ON COLUMN interfaces_planes.despacho_formulas.fecha_vencimiento IS 'Fecha de vencimiento relacionada en el archivo';

GRANT ALL ON TABLE interfaces_planes.despacho_formulas TO siis;
