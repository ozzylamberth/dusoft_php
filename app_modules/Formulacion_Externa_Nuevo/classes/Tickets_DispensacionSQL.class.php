<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: Tickets_DispensacionSQL.class.php,v 1.2 2010/04/09 19:48:52 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Sandra Viviana Pantoja Torres 
  */
  /**
  * Clase: Tickets_DispensacionSQL
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Sandra Viviana Pantoja Torres 
  */
  class Tickets_DispensacionSQL extends ConexionBD
  {
    /**
    * Constructor de la clase
    */
    function Tickets_DispensacionSQL(){}
   /**
    * Funcion donde se obtiene todas las formulas
    *
    * @param string $empresa Identificador de la empresa
    * @param array $filtros Arreglo con los filtros para la busqueda de la nota
    *
    * @return mixed
    */
    function Obtener_Reporte($filtros,$offset,$opcion = 1)
    {
  
     
      if($filtros['nombre_paciente']!="")
        {
        $filtro .= " and b.primer_nombre ||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido ILIKE '%".$filtros['nombre_paciente']."%' ";
        }
      if($filtros['formula_papel']!="")
        {
        $filtro .= " and a.formula_papel = '".$filtros['formula_papel']."' ";
        }
      if($filtros['tipo_id_paciente']!="")
        {
        $filtro .= " and b.tipo_id_paciente = '".$filtros['tipo_id_paciente']."' ";
        }
      if($filtros['paciente_id']!="")
        {
        $filtro .= " and b.paciente_id = '".$filtros['paciente_id']."' ";
        }
      
      $sql  = "
                        select
                        a.formula_id,
                        a.formula_papel,
                        a.fecha_formula,
                        a.sw_estado,
                        a.plan_id,
                        a.tipo_formula,
                        b.tipo_id_paciente,
                        b.paciente_id,
                        b.primer_nombre ||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre_paciente
                        from 
                        esm_formula_externa as a
                        JOIN pacientes as b ON (a.tipo_id_paciente = b.tipo_id_paciente)
                        and (a.paciente_id = b.paciente_id)
                        JOIN system_usuarios as c ON (a.usuario_id = c.usuario_id)
                        where   a.esm_tercero_id IS NULL
                         and  a.sw_estado IN ('0')  ";
                      $sql .= $filtro;
      
                  $cont  = "SELECT COUNT(*) FROM (".$sql.") A  ";
                  $this->ProcesarSqlConteo($cont,$offset);
                  $sql .= "ORDER BY a.fecha_formula  DESC ";
                  $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
                
      
                  if(!$rst = $this->ConexionBaseDatos($sql)) return false;

                  $datos = array();
                  while (!$rst->EOF)
                  {
                    $datos[] = $rst->GetRowAssoc($ToUpper = false);
                    $rst->MoveNext();
                  }
                  $rst->Close();
                  
                return $datos;
    }
    
  
    /**
		* Funcion donde se obtiene el nombre de un usuario
		*
    * @param int $usuario Identificacion del usuario
		*
    * @return mixed
    */
		function ObtenerInformacionUsuario($usuario)
		{
			$sql .= "SELECT	nombre ";
			$sql .= "FROM		system_usuarios "; 
			$sql .= "WHERE	usuario_id = ".$usuario." ";		
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
			if(!$rst->EOF)
			{
				$datos =  $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			return $datos;
		}
    
    /**
		* Funcion donde se obtiene el nombre de un usuario
		*
    * @param int $usuario Identificacion del usuario
		*
    * @return mixed
    */
		function Tipos_Ids()
		{
			$sql .= "SELECT	* ";
			$sql .= "FROM		tipos_id_pacientes "; 
			 //print_r($sql);
      if(!$rst = $this->ConexionBaseDatos($sql))
      return false;
      $datos = array();
      while(!$rst->EOF)
      {
      $datos[] = $rst->GetRowAssoc($ToUpper);
      $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
		}
    
      
      function Medicamentos_Dispensados_Esm_x_lote($formula_id)
      { 

      $fecha_hoy=date('Y-m-d');
      
      $sql = " select
      dd.codigo_producto,
      dd.cantidad as numero_unidades,
      dd.fecha_vencimiento ,
      dd.lote,
      fc_descripcion_producto_alterno(dd.codigo_producto) as descripcion_prod,
      fc_descripcion_producto_molecula(dd.codigo_producto) as molecula,
      d.usuario_id,
      sys.nombre,
      sys.descripcion,
      fc_codigo_mindefensa(dd.codigo_producto) as codigo_producto_mini,
      dd.sw_pactado,
      dd.total_costo
      FROM
      esm_formulacion_despachos_medicamentos as dc,
      bodegas_documentos as d,
      bodegas_documentos_d AS dd,
      system_usuarios  sys
      WHERE
      dc.bodegas_doc_id = d.bodegas_doc_id
      and        dc.numeracion = d.numeracion
      and        dc.formula_id = ".$formula_id."
      and        d.bodegas_doc_id = dd.bodegas_doc_id
      and        d.numeracion = dd.numeracion
      and       d.usuario_id=sys.usuario_id
      ";
      //print_r($sql);
      if(!$rst = $this->ConexionBaseDatos($sql))
      return false;
      $datos = array();
      while(!$rst->EOF)
      {
      $datos[] = $rst->GetRowAssoc($ToUpper);
      $rst->MoveNext();
      }
      $rst->Close();
      return $datos;

      }

  }
?>