<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: ConsultasNovedadesDevolucion.class.php,
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina S
  */
  /**
  * Clase: TransportadorasSQL
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.3 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina S
  */ 
  class TransportadorasSQL extends ConexionBD
  {
    /**
    * Contructor
    */
    function TransportadorasSQL(){}
		/**
		* Funcion donde se obtiene el listado de productos sin movimiento
		*
		* @param string $empresa Identificador de la empresa
		* @param array $filtros Arreglo con los filtros para la busqueda de la nota
		*
		* @return mixed
		*/
		function Listado_Transportadoras($filtro,$offset)
		{
		/*$this->debug=true;*/
		//print_r($filtros);
		if($filtro['transportadora_id']!="")
		$where = " AND transportadora_id = '".$filtro['transportadora_id']."' ";
		
		$sql  = "
		SELECT
		a.transportadora_id,
		a.descripcion,
		a.sw_carropropio,
		a.estado,
		a.usuario_id,
		CASE WHEN  sw_carropropio = '1'
		THEN '<K><B>SI</B></K>'
		ELSE '<K><B>NO</B></K>' END AS carro_propio
		FROM
		inv_transportadoras as a
		WHERE
		a.estado IN ('1','0') ";
		$sql .= " AND a.descripcion ILIKE '%".$filtro['descripcion']."%' ";
		$sql .= $where;
				
		$cont  = "SELECT COUNT(*) FROM (".$sql.") A  ";
		$this->ProcesarSqlConteo($cont,$offset);
		$sql .= "ORDER BY a.transportadora_id  ASC ";
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
		
		function Insertar_Transportadora($Datos)
		{
		/*$this->debug=true;*/
		$this->ConexionTransaccion();
		$sql  = "INSERT INTO inv_transportadoras( ";
		$sql .= "       transportadora_id , ";
		$sql .= "       descripcion, ";
		$sql .= "       sw_carropropio, ";
		$sql .= "       usuario_id ";
		$sql .= ")VALUES( ";
		$sql .= "       DEFAULT, ";
		$sql .= "       '".$Datos['descripcion']."', ";
		$sql .= "       '".$Datos['sw_carropropio']."', ";
		$sql .= "       '".UserGetUID()."' ";
		$sql .= "       ); ";
		
		if(!$rst = $this->ConexionBaseDatos($sql))
		return false;
		$this->Commit();
		$rst->Close();
		return true;
		}
		
		function Modificar_Transportadora($datos)
		{
		    $this->ConexionTransaccion();
			
		    $sql  = " UPDATE inv_transportadoras ";
		    $sql .= " SET ";
		    $sql .= " descripcion = '".$datos['descripcion']."', ";
		    $sql .= " sw_carropropio = '".$datos['sw_carropropio']."', ";
		    $sql .= " usuario_id = ".UserGetUID().", ";
		    $sql .= " fecha_registro = NOW() ";
		    $sql .= " WHERE ";
		    $sql .= " transportadora_id = '".$datos['transportadora_id_old']."'; ";
			/*print_r($sql);*/
			if(!$rst1 = $this->ConexionTransaccion($sql))
		    {
		    return false;
		    }
			$this->Commit();
			return true;
      }
	  
	  function Inactivar_Transportadoras($datos)
		{
		    $this->ConexionTransaccion();

		    $sql  = " UPDATE inv_transportadoras ";
		    $sql .= " SET ";
		    $sql .= " estado = '".$datos['cambiar_estado']."' ";
		    $sql .= " WHERE ";
		    $sql .= " transportadora_id = '".$datos['transportadora_id']."' ";
			if(!$rst1 = $this->ConexionTransaccion($sql))
		    {
		    return false;
		    }
			$this->Commit();
			return true;
      }
  }
?>