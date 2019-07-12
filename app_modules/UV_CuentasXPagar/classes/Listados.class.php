<?php
 /**
  * @package IPSOFT-SIIS
  * @version $Id: Listados.class.php,v 1.1 2009/01/14 22:22:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : RadicacionManual
  * Clase donde se hace el manejo del registro manual de la radicacion
  *  
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class Listados extends ConexionBD
  {
    /**
    * contructor de la clase
    */
    function Listados(){}
    /**
    * Funcion donde se obtiene la informacion de las radicaciones
    * 
    * @param string $empresa identificador de la empresa
    * @param array $filtro Arreglo de datos con los filtros para la consulta
    * @param integer $op Idica si se hara un conteo o no
    *
    * @return mixed
    */
    function ObtenerListadoRadicacion($empresa,$filtro,$op=1)
    {
      $sql  = "SELECT CR.cxp_radicacion_id,";
      $sql .= "       TO_CHAR(CR.fecha_radicacion,'DD/MM/YYYY') AS fecha_radicacion,";
      $sql .= "       TO_CHAR(CR.fecha_inicial,'DD/MM/YYYY') AS fecha_inicial	,";
      $sql .= "       TO_CHAR(CR.fecha_final,'DD/MM/YYYY') AS fecha_final , ";
      $sql .= "       TO_CHAR(CR.fecha_registro,'DD/MM/YYYY') AS fecha_registro, ";
      $sql .= "       CR.numero_cuentas , ";
      $sql .= "       CR.usuario_registro, ";
      $sql .= "       CR.descripcion_tercero_asociado, ";
      $sql .= "       CR.observacion, ";
      $sql .= "       CR.sw_rips, ";
      $sql .= "       CR.tipo_cxp, ";
      $sql .= "       CR.cxp_medio_pago_id 	, ";
      $sql .= "       CR.cxp_tipo_servicio_id 	, ";
      $sql .= "       CR.cxp_especialidad_id,";
      $sql .= "       CR.digitos_prefijo, ";
      $sql .= "       TE.tipo_id_tercero, ";
      $sql .= "       TE.tercero_id, ";
      $sql .= "       TE.nombre_tercero ";
      $sql .= "FROM   cxp_radicacion CR LEFT JOIN ";
      $sql .= "       ( SELECT  TP.codigo_proveedor_id, ";
      $sql .= "                 TR.tipo_id_tercero, ";
      $sql .= "                 TR.tercero_id, ";
      $sql .= "                 TR.nombre_tercero ";
      $sql .= "         FROM    terceros_proveedores TP ,";
      $sql .= "                 terceros TR ";
      $sql .= "         WHERE   TR.tercero_id = TP.tercero_id ";
      $sql .= "         AND     TR.tipo_id_tercero = TP.tipo_id_tercero ) AS TE ";
      $sql .= "       ON (TE.codigo_proveedor_id = CR.proveedor_id) ";
      $sql .= "WHERE  CR.empresa_id = '".$empresa."' ";
      
      if($filtro['cxp_radicacion_id'])
        $sql .= "AND    CR.cxp_radicacion_id = ".$filtro['cxp_radicacion_id']." ";
        
      if($filtro['tipo_id_tercero'] != "-1")
        $sql .= "AND    TE.tipo_id_tercero = '".$filtro['tipo_id_tercero']."' ";
        
      if($filtro['tercero_id'])
        $sql .= "AND    TE.tercero_id = '".$filtro['tercero_id']."' ";
        
      if($filtro['nombre_tercero'])
      {
        $sql .= "AND    ( TE.nombre_tercero ILIKE '%".$filtro['nombre_tercero']."%' OR ";
        $sql .= "       CR.descripcion_tercero_asociado ILIKE '%".$filtro['nombre_tercero']."%') ";
      }
      
      if($filtro['fecha_radicacion'])
        $sql .= "AND    CR.fecha_radicacion = '".$filtro['fecha_radicacion']."'::date ";
        
      if($filtro['fecha_registro']) 
        $sql .= "AND    CR.fecha_registro::date = '".$filtro['fecha_registro']."'::date ";
      
      if($op == 1)
      {
        $cont = "SELECT COUNT(*) FROM (".$sql.") AS A ";
        $this->ProcesarSqlConteo($cont,$filtro['offset']);
      
        $sql .= "ORDER BY CR.cxp_radicacion_id ASC ";
        $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
      }
      
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      
      $datos = array();
			while (!$rst->EOF)
			{
				$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
      return $datos;
    }    
    /**
    * Funcion donde se obtiene la informacion de las radicaciones para un reporte csv
    * 
    * @param string $empresa identificador de la empresa
    * @param array $filtro Arreglo de datos con los filtros para la consulta
    *
    * @return mixed
    */
    function ObtenerListadoRadicacionCsv($empresa,$filtro)
    {
      $sql  = "SELECT CR.cxp_radicacion_id AS cxp_radicacion_id,";
      $sql .= "       TO_CHAR(CR.fecha_radicacion,'DD/MM/YYYY') AS fecha_radicacion,";
      $sql .= "       TO_CHAR(CR.fecha_inicial,'DD/MM/YYYY') AS fecha_inicial	,";
      $sql .= "       TO_CHAR(CR.fecha_final,'DD/MM/YYYY') AS fecha_final , ";
      $sql .= "       TO_CHAR(CR.fecha_registro,'DD/MM/YYYY') AS fecha_registro, ";
      $sql .= "       CR.numero_cuentas AS numero_cuentas, ";
      $sql .= "       CR.usuario_registro AS usuario_registro, ";
      $sql .= "       CR.descripcion_tercero_asociado AS descripcion_tercero_asociado, ";
      $sql .= "       CR.observacion AS observacion, ";
      $sql .= "       CR.sw_rips AS sw_rips, ";
      $sql .= "       TE.tipo_id_tercero AS tipo_id_tercero, ";
      $sql .= "       TE.tercero_id AS tercero_id, ";
      $sql .= "       TE.nombre_tercero AS nombre_tercero ";
      $sql .= "FROM   cxp_radicacion CR LEFT JOIN ";
      $sql .= "       ( SELECT  TP.codigo_proveedor_id, ";
      $sql .= "                 TR.tipo_id_tercero, ";
      $sql .= "                 TR.tercero_id, ";
      $sql .= "                 TR.nombre_tercero ";
      $sql .= "         FROM    terceros_proveedores TP ,";
      $sql .= "                 terceros TR ";
      $sql .= "         WHERE   TR.tercero_id = TP.tercero_id ";
      $sql .= "         AND     TR.tipo_id_tercero = TP.tipo_id_tercero ) AS TE ";
      $sql .= "       ON (TE.codigo_proveedor_id = CR.proveedor_id) ";
      $sql .= "WHERE  CR.empresa_id = '".$empresa."' ";
      
      if($filtro['cxp_radicacion_id'])
        $sql .= "AND    CR.cxp_radicacion_id = ".$filtro['cxp_radicacion_id']." ";
        
      if($filtro['tipo_id_tercero'] != "-1")
        $sql .= "AND    TE.tipo_id_tercero = '".$filtro['tipo_id_tercero']."' ";
        
      if($filtro['tercero_id'])
        $sql .= "AND    TE.tercero_id = '".$filtro['tercero_id']."' ";
        
      if($filtro['nombre_tercero'])
      {
        $sql .= "AND    ( TE.nombre_tercero ILIKE '%".$filtro['nombre_tercero']."%' OR ";
        $sql .= "       CR.descripcion_tercero_asociado ILIKE '%".$filtro['nombre_tercero']."%') ";
      }
      
      if($filtro['fecha_radicacion'])
        $sql .= "AND    CR.fecha_radicacion = '".$filtro['fecha_radicacion']."'::date ";
        
      if($filtro['fecha_registro']) 
        $sql .= "AND    CR.fecha_registro::date = '".$filtro['fecha_registro']."'::date ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;

      return $rst;
    }
    /**
    * Funcion donde se obtiene la informacion de las facturas
    * en estado REVISADO POR MEDICO
    *
    * @return mixed
    */
    function ObtenerFacturas()
    {
      $sql  = "SELECT prefijo_factura, ";
      $sql .= "       numero_factura, ";
      $sql .= "       TO_CHAR(fecha_documento,YYYY-MM-DD) AS fecha_documento, ";
      $sql .= "       valor_total,";
      $sql .= "       valor_iva ";
      $sql .= "FROM   cxp_facturas ";
      $sql .= "WHERE  cxp_estado = 'RP'  ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      
      $retorno = array();
			if (!$rst->EOF)
			{
				$retorno = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
      
      return $retorno;
    }
  }
?>