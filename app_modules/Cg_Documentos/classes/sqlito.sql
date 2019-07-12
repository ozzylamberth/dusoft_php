

INSERT INTO cg_mov_contable
  SELECT a.documento_contable_id, 
  to_char(a.fecha_documento,'YYYY-MM') as lapso, 
  a.fecha_documento, 
  b.empresa_id,
  b.prefijo,
  b.numero,
  a.documento_id,
  a.sw_estado,
  '00' as tipo_bloqueo_id,
  0 as total_debitos,
  0 as total_creditos,
  a.tipo_id_tercero,
  a.tercero_id,
  a.fecha_registro,
  a.usuario_id
  
  FROM
  cg_movimientos_contables AS a,
  cg_movimientos_contables_facturas AS b
  
  WHERE a.documento_contable_id = b.documento_contable_id
  and to_char(a.fecha_documento,'YYYY-MM')  IN('2006-08','2006-09','2006-10')
  
  INSERT INTO cg_mov_contable_d(documento_contable_id,
                                empresa_id,
                                cuenta,
                                tipo_id_tercero,
                                tercero_id,
                                debito,
                                credito,
                                detalle,
                                departamento,
                                base_rtf,
                                porcentaje_rtf)
  SELECT a.documento_contable_id,a.empresa_id,a.cuenta,a.tipo_id_tercero,a.tercero_id,a.debito,a.credito,a.detalle,a.departamento,a.base_rtf,a.porcentaje_rtf
  
  FROM cg_movimientos_contables_facturas_d AS a, cg_mov_contable AS b
  WHERE a.documento_contable_id = b.documento_contable_id
  