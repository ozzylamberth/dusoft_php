<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ListaReportes.class.php,v 1.1 2010/04/08 20:36:35 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase: ListaReportes
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class ListaReportes extends ConexionBD
  {
    /**
    * Constructor de la clase
    */
    function ListaReportes(){}
    /**
    * Funcion donde se obtiene el listado de documentos de bodega
    *
    * @param string $empresa Identificador de la empresa
    * @param integer $documento Identificador del documento
    * @param integer $offset Pagina actual
    *
    * @return mixed
    */
    function ObtenerListadoDocumentos($empresa,$documento,$fecha1,$fecha2)
    {
      $sql  = "SELECT  SUM(ID.total_costo) AS total_costo,";
      $sql .= "        ID.prefijo,";
      $sql .= "        ID.numero,";
      $sql .= "        EM.empresa_id,";
      $sql .= "        EM.razon_social,  ";
      $sql .= "        IM.observacion ";
      $sql .= "FROM    inv_bodegas_movimiento IM ";
      $sql .= "        LEFT JOIN empresas EM ";
      $sql .= "        ON (IM.empresa_destino = EM.empresa_id),";
      $sql .= "        inv_bodegas_movimiento_d ID, ";
      $sql .= "        bodegas G ";
      $sql .= "WHERE   IM.documento_id = ".$documento." ";
      $sql .= "AND     IM.empresa_id = '".$empresa."' ";
      $sql .= "AND     IM.empresa_id = G.empresa_id ";
      $sql .= "AND     IM.bodega = G.bodega ";
      $sql .= "AND     IM.centro_utilidad = G.centro_utilidad ";
      $sql .= "AND     IM.empresa_id = ID.empresa_id ";
      $sql .= "AND     IM.prefijo = ID.prefijo ";
      $sql .= "AND     IM.numero = ID.numero ";
      
      if(!empty($fecha1))
        $sql .= "AND    IM.fecha_registro::date >= '".$this->DividirFecha($fecha1)."'::date ";
      
      if(!empty($fecha2))
        $sql .= "AND    IM.fecha_registro::date <= '".$this->DividirFecha($fecha2)."'::date ";
              
      $sql .= "GROUP BY ID.prefijo,ID.numero, G.descripcion , EM.empresa_id,EM.razon_social,IM.observacion ";
      $sql .= "ORDER BY EM.empresa_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$datos = array();
			while (!$rst->EOF)
			{
				$datos[$rst->fields[3]][] = $rst->GetRowAssoc($ToUpper = false);
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
  }
?>