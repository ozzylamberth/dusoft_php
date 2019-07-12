<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ListaNotas.class.php,v 1.1 2010/03/09 13:40:54 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase: ListaNotas
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class ListaNotas extends ConexionBD
  {
    /**
    * Constructor de la clase
    */
    function ListaNotas(){}
    /**
		* Funcion donde se obtienen los los prefijos de las facturas
		*
    * @param string $empresa Identificador de la empresa 
    *
		* @return mixed
		*/
		function ObtenerPrefijos($empresa)
		{
			$sql  = "SELECT DISTINCT prefijo_factura ";
			$sql .= "FROM 	notas_contado_credito  ";
			$sql .= "WHERE 	estado = '1' ";
			$sql .= "UNION DISTINCT ";
			$sql .= "SELECT DISTINCT prefijo_factura ";
			$sql .= "FROM 	notas_contado_debito  ";
			$sql .= "WHERE 	estado = '1' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
      
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
    * funcion donde se obtiene la informacion de la nota credito o debito
    *
    * @param string $empresa Identificador de la empresa
    * @param array $filtros Arreglo con los filtros para la busqueda de la nota
    * @param integer $offset Pagina actual
    *
    * @return mixed
    */
    function ObtenerNotas($empresa,$filtros,$offset)
    {
      $sql .= "SELECT NC.prefijo_factura ,";
			$sql .= "				NC.factura_fiscal ,";
			$sql .= "				NC.valor_nota ,";
			$sql .= "				NC.prefijo ,";
			$sql .= "				NC.numero ,";
			$sql .= "				NC.observacion ,";
			$sql .= "				TO_CHAR(NC.fecha_registro,'DD/MM/YYYY') AS fecha_registro ,";
			$sql .= "				TO_CHAR(FF.fecha_registro,'DD/MM/YYYY') AS fecha_factura ,";
			$sql .= "				FF.total_factura, ";
			$sql .= "				FF.saldo, ";
			$sql .= "				SU.nombre, ";
 			$sql .= "   		TE.tercero_id, ";
			$sql .= "   		TE.tipo_id_tercero, ";
			$sql .= "				TE.nombre_tercero ";
      $sql .= "FROM   notas_contado_".$filtros['tipo_nota']." NC , ";
      $sql .= "       system_usuarios SU, ";
      $sql .= "       fac_facturas FF, ";
      $sql .= "       terceros TE  ";
			$sql .= "WHERE  NC.empresa_id = '".$empresa."' ";
			$sql .= "AND 		NC.usuario_id = SU.usuario_id ";
			$sql .= "AND 		FF.prefijo = NC.prefijo_factura ";
			$sql .= "AND 		FF.factura_fiscal = NC.factura_fiscal ";
			$sql .= "AND		TE.tercero_id = FF.tercero_id ";
			$sql .= "AND		TE.tipo_id_tercero = FF.tipo_id_tercero ";
      if($filtros['numero'])
        $sql .= "AND 		NC.numero = ".$filtros['numero']." ";
       
      if($filtros['prefijo_factura'] != '-1')
        $sql .= "AND 		NC.prefijo_factura = '".$filtros['prefijo_factura']."' ";
      
      if($filtros['factura_fiscal'])
        $sql .= "AND 		NC.factura_fiscal = ".$filtros['factura_fiscal']." ";
        
      $cont  = "SELECT COUNT(*) FROM (".$sql.") A  ";
      
      $this->ProcesarSqlConteo($cont,$offset);
				
			$sql .= "ORDER BY NC.fecha_registro,NC.prefijo,NC.numero  ";
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
  }
?>