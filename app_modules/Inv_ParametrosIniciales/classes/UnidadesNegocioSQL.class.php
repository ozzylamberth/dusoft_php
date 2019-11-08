<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: ConsultasNovedadesDevolucion.class.php,
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina S
  */
  /**
  * Clase: UnidadesNegocioSQL
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.3 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina S
  */ 
  class UnidadesNegocioSQL extends ConexionBD
  {
    /**
    * Contructor
    */
    function UnidadesNegocioSQL(){}
		/**
		* Funcion donde se obtiene el listado de productos sin movimiento
		*
		* @param string $empresa Identificador de la empresa
		* @param array $filtros Arreglo con los filtros para la busqueda de la nota
		*
		* @return mixed
		*/
		function Listado_UnidadesNegocio($filtro,$offset)
		{
		//$this->debug=true;
		//print_r($filtros);
		if($filtro['codigo_unidad_negocio']!="")
		$where = " AND codigo_unidad_negocio = '".$filtro['codigo_unidad_negocio']."' ";
		
		$sql  = "
		SELECT
		a.codigo_unidad_negocio,
		a.descripcion,
		a.estado,
		a.usuario_id,
		a.empresa_id,
		a.fecha_registro,
		a.imagen
		FROM
		unidades_negocio as a   
		WHERE
		a.estado IN ('1','0') ";
		$sql .= " AND a.descripcion ILIKE '%".$filtro['descripcion']."%' ";
		$sql .= $where;
				
		$cont  = "SELECT COUNT(*) FROM (".$sql.") A  ";
		$this->ProcesarSqlConteo($cont,$offset);
		$sql .= "ORDER BY a.codigo_unidad_negocio  ASC ";
		$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;

		// print_r($filtros);
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
		
		function Insertar_UnidadNegocio($Datos)
		{
		/*$this->debug=true;*/
		$this->ConexionTransaccion();
		$sql  = "INSERT INTO unidades_negocio( ";
		$sql .= "       codigo_unidad_negocio , ";
		$sql .= "       descripcion, ";
		$sql .= "       imagen, ";
		$sql .= "       usuario_id ";
		$sql .= ")VALUES( ";
		$sql .= "       '".$Datos['codigo_unidad_negocio']."', ";
		$sql .= "       '".$Datos['descripcion']."', ";
		$sql .= "       '".$Datos['imagen']."', ";
		$sql .= "       '".UserGetUID()."' ";
		$sql .= "       ); ";
		
		if(!$rst = $this->ConexionBaseDatos($sql))
		return false;
		$this->Commit();
		$rst->Close();
		return true;
		}
		
		function Modificar_UnidadNegocio($datos)
		{
		    $this->ConexionTransaccion();
			
		    $sql  = " UPDATE unidades_negocio ";
		    $sql .= " SET ";
		    $sql .= " codigo_unidad_negocio = '".$datos['codigo_unidad_negocio']."', ";
		    $sql .= " descripcion = '".$datos['descripcion']."', ";
		    $sql .= " imagen = '".$datos['imagen']."', ";
		    $sql .= " usuario_id = ".UserGetUID().", ";
		    $sql .= " fecha_registro = NOW() ";
		    $sql .= " WHERE ";
		    $sql .= " codigo_unidad_negocio = '".$datos['codigo_unidad_negocio_old']."'; ";

			if(!$rst1 = $this->ConexionTransaccion($sql))
		    {
		    return false;
		    }
			$this->Commit();
			return true;
      }
	  
	  function Inactivar_UnidadNegocio($datos)
		{
		    $this->ConexionTransaccion();

		    $sql  = " UPDATE unidades_negocio ";
		    $sql .= " SET ";
		    $sql .= " estado = '".$datos['cambiar_estado']."' ";
		    $sql .= " WHERE ";
		    $sql .= " codigo_unidad_negocio = '".$datos['codigo_unidad_negocio']."' ";
			if(!$rst1 = $this->ConexionTransaccion($sql))
		    {
		    return false;
		    }
			$this->Commit();
			return true;
      }
  }
?>