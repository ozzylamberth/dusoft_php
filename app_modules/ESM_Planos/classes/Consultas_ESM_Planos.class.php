<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: Consultas_TipoEvento.class.php,
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.3 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */ 
  
  
  
  class Consultas_ESM_Planos extends ConexionBD
  {
    /**
    * Contructor
    */
    
	function Consultas_ESM_Planos(){}
 
   


		function Consulta_Formulacion($filtros)
		{
      // $this->debug=true;
      // print_r($filtros);
       $sql = "
                  select
                  a.formula_papel,
                  a.formula_id,
                  to_char(a.fecha_formula,'".$filtros['formato_fecha']."') as fecha_formula,
                  a.tipo_formula,
                  e.descripcion_tipo_formula,
                  a.tipo_evento_id,
                  d.descripcion_tipo_evento,
                  b.tipo_id_paciente,
                  b.paciente_id,
                  b.primer_nombre ||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre_paciente,
                  g.tipo_fuerza_id,
                  g.descripcion,
                  h.tipo_afiliado_id,
                  h.tipo_afiliado_nombre,
                  j.tercero_id,
                  k.nombre_tercero,
                  COALESCE(fc_codigo_mindefensa(n.codigo_producto),'') as codigo_producto,
                  CASE WHEN n.sw_pactado = '0'
                  THEN
                  fc_descripcion_producto_alterno(n.codigo_producto)
                  ELSE
                  fc_descripcion_producto_molecula(n.codigo_producto)
                  END as producto,
                  n.cantidad,
                  (n.total_costo/n.cantidad) as valor_unitario,
                  n.total_costo,
                  n.lote,
                  to_char(n.fecha_vencimiento,'".$filtros['formato_fecha']."')as fecha_vencimiento,
                  /*q.cantidad as dosis,
                  q.tiempo_tratamiento,
                  CASE WHEN unidad_tiempo_tratamiento = '4'
                  THEN
                  'dias' END as unidad_tiempo,*/
                  CEIL(COALESCE((q.tiempo_tratamiento/q.cantidad),0)) as dosis_dia,
                  a.esm_tercero_id as codigo_esm,
                  p.diagnostico_id,
                  p.diagnostico_nombre,
                  a.tipo_id_tercero as tipo_id_profesional,
                  a.tercero_id as id_profesional,
                  m.usuario_id as id_usuario_despachador,
                  a.usuario_id as id_usuario_digitador_formula
                  from 
                  esm_formula_externa as a
                  JOIN pacientes as b ON (a.tipo_id_paciente = b.tipo_id_paciente)
                  and (a.paciente_id = b.paciente_id)
                  JOIN system_usuarios as c ON (a.usuario_id = c.usuario_id)
                  JOIN esm_tipos_eventos as d ON (a.tipo_evento_id = d.tipo_evento_id)
                  JOIN esm_tipos_formulas as e ON (a.tipo_formula = e.tipo_formula_id)
                  JOIN esm_pacientes_fuerzas as f ON (a.tipo_id_paciente = f.tipo_id_paciente)
                  and(a.paciente_id = f.paciente_id)
                  JOIN esm_tipos_fuerzas as g ON(f.tipo_fuerza_id = g.tipo_fuerza_id)
                  LEFT JOIN tipos_afiliado as h ON (a.tipo_afiliado_id = h.tipo_afiliado_id)
                  JOIN esm_pacientes as i ON (a.tipo_id_paciente = i.tipo_id_paciente)
                  and (a.paciente_id = i.paciente_id)
                  JOIN esm_empresas as j ON (i.tipo_id_tercero = j.tipo_id_tercero)
                  and (i.tercero_id = j.tercero_id)
                  JOIN terceros as k ON (j.tipo_id_tercero = k.tipo_id_tercero)
                  and (j.tercero_id = k.tercero_id)
                  JOIN esm_formulacion_despachos_medicamentos as l ON (a.formula_id = l.formula_id)
                  LEFT JOIN bodegas_documentos as m ON (l.bodegas_doc_id = m.bodegas_doc_id)
                  and (l.numeracion = m.numeracion)
                  LEFT JOIN bodegas_documentos_d as n ON (m.bodegas_doc_id = n.bodegas_doc_id)
                  and (m.numeracion = n.numeracion)
                  JOIN esm_formula_externa_diagnosticos as o ON(a.formula_id = o.formula_id)
                  JOIN diagnosticos as p ON (o.diagnostico_id = p.diagnostico_id)
                  LEFT JOIN esm_formula_externa_medicamentos as q ON(a.formula_id = q.formula_id)
                    and(n.codigo_producto = q.codigo_producto)
                  where
                  a.sw_estado IN ('1','0')";
				
				
         $sql .= "AND   m.fecha_registro::date >= '".$this->DividirFecha($filtros['fecha_inicio'])."'::date ";
         $sql .= "AND   m.fecha_registro::date <= '".$this->DividirFecha($filtros['fecha_final'])."'::date ";
         
           $sql .= "
                  UNION
                  select
                  a.formula_papel,
                  a.formula_id,
                  to_char(a.fecha_formula,'".$filtros['formato_fecha']."') as fecha_formula,
                  a.tipo_formula,
                  e.descripcion_tipo_formula,
                  a.tipo_evento_id,
                  d.descripcion_tipo_evento,
                  b.tipo_id_paciente,
                  b.paciente_id,
                  b.primer_nombre ||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre_paciente,
                  g.tipo_fuerza_id,
                  g.descripcion,
                  h.tipo_afiliado_id,
                  h.tipo_afiliado_nombre,
                  j.tercero_id,
                  k.nombre_tercero,
                  COALESCE(fc_codigo_mindefensa(n.codigo_producto),'') as codigo_producto,
                  CASE WHEN n.sw_pactado = '0'
                  THEN
                  fc_descripcion_producto_alterno(n.codigo_producto)
                  ELSE
                  fc_descripcion_producto_molecula(n.codigo_producto)
                  END as producto,
                  n.cantidad,
                  (n.total_costo/n.cantidad) as valor_unitario,
                  n.total_costo,
                  n.lote,
                  to_char(n.fecha_vencimiento,'".$filtros['formato_fecha']."')as fecha_vencimiento,
                  /*q.cantidad as dosis,
                  q.tiempo_tratamiento,
                  CASE WHEN unidad_tiempo_tratamiento = '4'
                  THEN
                  'dias' END as unidad_tiempo,*/
                  CEIL(COALESCE((q.tiempo_tratamiento/q.cantidad),0)) as dosis_dia,
                  a.esm_tercero_id as codigo_esm,
                  p.diagnostico_id,
                  p.diagnostico_nombre,
                  a.tipo_id_tercero as tipo_id_profesional,
                  a.tercero_id as id_profesional,
                  m.usuario_id as id_usuario_despachador,
                  a.usuario_id as id_usuario_digitador_formula
                  from 
                  esm_formula_externa as a
                  JOIN pacientes as b ON (a.tipo_id_paciente = b.tipo_id_paciente)
                  and (a.paciente_id = b.paciente_id)
                  JOIN system_usuarios as c ON (a.usuario_id = c.usuario_id)
                  JOIN esm_tipos_eventos as d ON (a.tipo_evento_id = d.tipo_evento_id)
                  JOIN esm_tipos_formulas as e ON (a.tipo_formula = e.tipo_formula_id)
                  JOIN esm_pacientes_fuerzas as f ON (a.tipo_id_paciente = f.tipo_id_paciente)
                  and(a.paciente_id = f.paciente_id)
                  JOIN esm_tipos_fuerzas as g ON(f.tipo_fuerza_id = g.tipo_fuerza_id)
                  LEFT JOIN tipos_afiliado as h ON (a.tipo_afiliado_id = h.tipo_afiliado_id)
                  JOIN esm_pacientes as i ON (a.tipo_id_paciente = i.tipo_id_paciente)
                  and (a.paciente_id = i.paciente_id)
                  JOIN esm_empresas as j ON (i.tipo_id_tercero = j.tipo_id_tercero)
                  and (i.tercero_id = j.tercero_id)
                  JOIN terceros as k ON (j.tipo_id_tercero = k.tipo_id_tercero)
                  and (j.tercero_id = k.tercero_id)
                  JOIN esm_formulacion_despachos_medicamentos_pendientes as l ON (a.formula_id = l.formula_id)
                  LEFT JOIN bodegas_documentos as m ON (l.bodegas_doc_id = m.bodegas_doc_id)
                  and (l.numeracion = m.numeracion)
                  LEFT JOIN bodegas_documentos_d as n ON (m.bodegas_doc_id = n.bodegas_doc_id)
                  and (m.numeracion = n.numeracion)
                  JOIN esm_formula_externa_diagnosticos as o ON(a.formula_id = o.formula_id)
                  JOIN diagnosticos as p ON (o.diagnostico_id = p.diagnostico_id)
                  LEFT JOIN esm_formula_externa_medicamentos as q ON(a.formula_id = q.formula_id)
                    and(n.codigo_producto = q.codigo_producto)
                  where
                  a.sw_estado IN ('1','0')";
         $sql .= "AND   m.fecha_registro::date >= '".$this->DividirFecha($filtros['fecha_inicio'])."'::date ";
         $sql .= "AND   m.fecha_registro::date <= '".$this->DividirFecha($filtros['fecha_final'])."'::date ";
         
         
							if(!$rst = $this->ConexionBaseDatos($sql))
              return false;
							//$rst->Close();
              return $rst;
		}
    
	
	}
	
?>