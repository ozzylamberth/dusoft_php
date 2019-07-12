

/*  DETALLE  DEL SUMINISTRO POR PRODUCTO */


CREATE TABLE esm_orden_requisicion_d_pacientes
(
  orden_requisicion_id       integer  null,
  codigo_producto            character varying(50) 	NOT NULL,
  tipo_id_paciente 	CHARACTER VARYING(3) NOT NULL,	
  paciente_id CHARACTER VARYING(32) NOT NULL,
  cantidad        integer not null
  
);




ALTER TABLE esm_orden_requisicion_d_pacientes ADD PRIMARY KEY(orden_requisicion_id,codigo_producto,tipo_id_paciente,paciente_id);

ALTER TABLE esm_orden_requisicion_d_pacientes ADD FOREIGN KEY (orden_requisicion_id) 
REFERENCES esm_orden_requisicion(orden_requisicion_id) ON UPDATE CASCADE ON DELETE CASCADE;



ALTER TABLE esm_orden_requisicion_d_pacientes ADD FOREIGN KEY (codigo_producto) 
REFERENCES inventarios_productos(codigo_producto) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE esm_orden_requisicion_d_pacientes ADD FOREIGN KEY (tipo_id_paciente,paciente_id) 
REFERENCES eps_afiliados_datos(afiliado_tipo_id,afiliado_id) ON UPDATE CASCADE ON DELETE RESTRICT;

/* productos temp */

CREATE TABLE esm_orden_requisicion_tmp_d_pacientes_tmp
(
  orden_requisicion_tmp_id_tmp     integer not  null,
  codigo_producto            character varying(50) 	NOT NULL,
  tipo_id_paciente 	CHARACTER VARYING(3) NOT NULL,	
  paciente_id CHARACTER VARYING(32) NOT NULL,
  cantidad        integer not null
  
);

ALTER TABLE esm_orden_requisicion_tmp_d_pacientes_tmp ADD PRIMARY KEY(orden_requisicion_tmp_id_tmp,codigo_producto,tipo_id_paciente,paciente_id);

ALTER TABLE esm_orden_requisicion_tmp_d_pacientes_tmp ADD FOREIGN KEY (codigo_producto) 
REFERENCES inventarios_productos(codigo_producto) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE esm_orden_requisicion_tmp_d_pacientes_tmp ADD FOREIGN KEY (tipo_id_paciente,paciente_id) 
REFERENCES eps_afiliados_datos(afiliado_tipo_id,afiliado_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE esm_orden_requisicion_tmp_d_pacientes_tmp ADD FOREIGN KEY (orden_requisicion_tmp_id_tmp) 
REFERENCES esm_orden_requisicion_tmp(orden_requisicion_tmp_id) ON UPDATE CASCADE ON DELETE CASCADE;
