<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: ConsultasNovedadesDevolucion.class.php,
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina S
  */
  /**
  * Clase: TiposClienteSQL
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.3 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina S
  */ 
  class TiposClienteSQL extends ConexionBD
  {
    /**
    * Contructor
    */
    function TiposClienteSQL(){}
		/**
		* Funcion donde se obtiene el listado de productos sin movimiento
		*
		* @param string $empresa Identificador de la empresa
		* @param array $filtros Arreglo con los filtros para la busqueda de la nota
		*
		* @return mixed
		*/
		function Listado_TiposCliente($filtro,$offset)
		{
		//$this->debug=true;
		//print_r($filtros);
		if($filtro['tipo_cliente']!="")
		$where = " AND tipo_cliente = '".$filtro['tipo_cliente']."' ";
		
		$sql  = "
		SELECT
		a.tipo_cliente,
		a.descripcion,
		a.estado,
		a.usuario_id
		FROM
		tipos_clientes as a
		WHERE
		a.estado IN ('1','0') ";
		$sql .= " AND a.descripcion ILIKE '%".$filtro['descripcion']."%' ";
		$sql .= $where;
				
		$cont  = "SELECT COUNT(*) FROM (".$sql.") A  ";
		$this->ProcesarSqlConteo($cont,$offset);
		$sql .= "ORDER BY a.tipo_cliente  ASC ";
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
		
		function Insertar_TipoCliente($Datos)
		{
		/*$this->debug=true;*/
		$this->ConexionTransaccion();
		$sql  = "INSERT INTO tipos_clientes( ";
		$sql .= "       tipo_cliente , ";
		$sql .= "       descripcion, ";
		$sql .= "       usuario_id ";
		$sql .= ")VALUES( ";
		$sql .= "       '".$Datos['tipo_cliente']."', ";
		$sql .= "       '".$Datos['descripcion']."', ";
		$sql .= "       '".UserGetUID()."' ";
		$sql .= "       ); ";
		
		if(!$rst = $this->ConexionBaseDatos($sql))
		return false;
		$this->Commit();
		$rst->Close();
		return true;
		}
		
		function Modificar_TipoCliente($datos)
		{
		    $this->ConexionTransaccion();
			
		    $sql  = " UPDATE tipos_clientes ";
		    $sql .= " SET ";
		    $sql .= " tipo_cliente = '".$datos['tipo_cliente']."', ";
		    $sql .= " descripcion = '".$datos['descripcion']."', ";
		    $sql .= " usuario_id = ".UserGetUID().", ";
		    $sql .= " fecha_registro = NOW() ";
		    $sql .= " WHERE ";
		    $sql .= " tipo_cliente = '".$datos['tipo_cliente_old']."'; ";

			if(!$rst1 = $this->ConexionTransaccion($sql))
		    {
		    return false;
		    }
			$this->Commit();
			return true;
      }
	  
	  function Inactivar_TipoCliente($datos)
		{
		    $this->ConexionTransaccion();

		    $sql  = " UPDATE tipos_clientes ";
		    $sql .= " SET ";
		    $sql .= " estado = '".$datos['cambiar_estado']."' ";
		    $sql .= " WHERE ";
		    $sql .= " tipo_cliente = '".$datos['tipo_cliente']."' ";
			if(!$rst1 = $this->ConexionTransaccion($sql))
		    {
		    return false;
		    }
			$this->Commit();
			return true;
      }
  }
?>