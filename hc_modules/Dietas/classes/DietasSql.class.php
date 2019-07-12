<?php
 /**
  * @package IPSOFT-SIIS
  * @version $Id: DietasSql.class.php,v 1.1 2009/02/02 16:32:31 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : DietasSql
  * Manejo de los querys invocados por el submodulo de Dietas
  *  
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class DietasSql extends ConexionBD
  {
    /**
    * Constructor de la clase
    */
    function DietasSql(){}
    /**
    * Funcion para consultar la informacion almacenada en la tabla tendencias_sexuales
    * @return array $datos vector que contiene la informacion de la consulta
    */
    function ConsultarTendenciasSexuales()
    {
      //$this->debug=true;
      $sql  = "SELECT     tendencia_id, descripcion ";
      $sql .= "FROM       tendencias_sexuales ";
      $sql .= "WHERE      sw_activo='1' ";
      $sql .= "ORDER BY   tendencia_id, descripcion ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    /**
    *
    */
    function ObtenerControlDietas()
		{
			$sql  = "SELECT * ";
      $sql .= "FROM   hc_tipos_dieta ";
      $sql .= "WHERE  abreviatura != 'NVO' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
		}
    /**
    *
    */
    function ObtenerInformacionAyuno($ingreso)
		{
			$sql  = "SELECT motivo,";
      $sql .= "       hora_fin_ayuno,";
      $sql .= "       hora_inicio_ayuno ";
      $sql .= "FROM   hc_solicitudes_dietas_ayunos ";
      $sql .= "WHERE  ingreso=".$ingreso." ";
      $sql .= "AND    fecha::date = '".date("Y-m-d")."'::date ";
                        
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
		}
    /**
    *
    */
		function ObtenerNadaViaOral()
		{
			$sql  = "SELECT hc_dieta_id ";
      $sql .= "FROM   hc_tipos_dieta ";
      $sql .= "WHERE  abreviatura = 'NVO' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      $datos = array();
      while(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
		}
    /**
    *
    */
    function ObtenerDietasCaracteristicasI($valor)
    {
      $sql  = "SELECT A.hc_dieta_id, ";
      $sql .= "       A.caracteristica_id, ";
      $sql .= "       B.descripcion ";
      $sql .= "FROM   hc_tipos_dieta_caracteristicas  A, ";
      $sql .= "       hc_solicitudes_dietas_caracteristicas  B ";
      $sql .= "WHERE  A.caracteristica_id = B.caracteristica_id ";
      $sql .= "AND    B.sw_generica = '0' ";
      $sql .= "AND    B.sw_activo='1' ";
      $sql .= "AND    A.hc_dieta_id=".$valor." ";
      $sql .= "ORDER BY B.indice_orden ASC ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    /**
    *
    */
    function ObtenerDietasCaracteristicasII()
    {
      $sql  = "SELECT caracteristica_id	,";
      $sql .= "       descripcion	,";
      $sql .= "       codigo_agrupamiento,";
      $sql .= "       descripcion_agrupamiento ,";
      $sql .= "       indice_orden ";
      $sql .= "FROM   hc_solicitudes_dietas_caracteristicas ";
      $sql .= "WHERE  sw_generica = '1' ";
      $sql .= "AND    sw_activo = '1' ";
      $sql .= "ORDER BY indice_orden,descripcion ASC ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    /**
    *
    */
    function IngresarDieta($ingreso,$evolucion,$usuario,$datos)
    {
      $sql  = "INSERT INTO hc_controles_paciente";
      $sql .= "   ( ";
      $sql .= "     ingreso,";
      $sql .= "     control_id,";
      $sql .= "     evolucion_id";
      $sql .= "   )";
      $sql .= "VALUES ";
      $sql .= "   (";
      $sql .= "     ".$ingreso.",";
      $sql .= "     '25',";
      $sql .= "     ".$evolucion." ";
      $sql .= "   )";

      $this->ConexionTransaccion();
      if(!$rst = $this->ConexionTransaccion($sql)) return false;
    
      if(!$datos['tipodieta']) $datos['tipodieta'] = $datos['nada_oral'];
      if(!$datos['fraccionada']) $datos['fraccionada'] = "0";
      if(!$datos['ctlAyuno']) $datos['ctlAyuno'] = "0";
      
      $sql  = "INSERT INTO hc_solicitudes_dietas ";
      $sql .= "   (";
      $sql .= "     ingreso,";
      $sql .= "     evolucion_id,";
      $sql .= "     usuario_id,";
      $sql .= "     hc_dieta_id,";
      $sql .= "     sw_fraccionada,";
      $sql .= "     sw_ayuno,";
      $sql .= "     observaciones,";
      $sql .= "     fecha_registro";
      $sql .= "   )";
      $sql .= "VALUES";
      $sql .= "   (";
      $sql .= "     ".$ingreso.",";
      $sql .= "     ".$evolucion.",";
      $sql .= "     ".$usuario.",";
      $sql .= "     ".$datos['tipodieta'].", ";
      $sql .= "    '".$datos['fraccionada']."', ";
      $sql .= "    '".$datos['ctlAyuno']."', ";
      $sql .= "    '".$datos['ctlDietasObs']."', ";
      $sql .= "     NOW() ";
      $sql .= "   )";
      
      if(!$rst = $this->ConexionTransaccion($sql)) return false;
    
      foreach($datos['caracteristica_dieta'] as $k1 => $dtl)
      {
        $sql  = "INSERT INTO hc_solicitudes_dietas_detalle ";
        $sql .= "   (";
        $sql .= "     ingreso,";
        $sql .= "     evolucion_id, ";
        $sql .= "     caracteristica_id ";
        $sql .= "   ) ";
        $sql .= "VALUES ";
        $sql .= "   ( ";
        $sql .= "     ".$ingreso.",";
        $sql .= "     ".$evolucion.",";
        $sql .= "     ".$dtl." ";
        $sql .= "   ) ";
        
        if(!$rst = $this->ConexionTransaccion($sql)) return false;
      }
      
      $this->Commit();
      return true;
    }    
    /**
    *
    */
    function ActualizarDieta($ingreso,$evolucion,$usuario,$datos)
    {
      $sql  = "UPDATE hc_controles_paciente ";
      $sql .= "SET    evolucion_id = ".$evolucion." ";
      $sql .= "WHERE  ingreso = ".$ingreso." ";
      $sql .= "AND    control_id = '25' ";

      $this->ConexionTransaccion();
      if(!$rst = $this->ConexionTransaccion($sql)) return false;
    
      if(!$datos['tipodieta']) $datos['tipodieta'] = $datos['nada_oral'];
      if(!$datos['fraccionada']) $datos['fraccionada'] = "0";
      if(!$datos['ctlAyuno']) $datos['ctlAyuno'] = "0";
      
      $sql  = "UPDATE hc_solicitudes_dietas ";
      $sql .= "SET    hc_dieta_id = ".$datos['tipodieta'].",";
      $sql .= "       sw_fraccionada = '".$datos['fraccionada']."',";
      $sql .= "       sw_ayuno = '".$datos['ctlAyuno']."', ";
      $sql .= "       observaciones = '".$datos['ctlDietasObs']."', ";
      $sql .= "       usuario_id = ".$usuario.", ";
      $sql .= "       fecha_registro = NOW() ";
      $sql .= "WHERE  ingreso = ".$ingreso." ";
      $sql .= "AND    evolucion_id = ".$evolucion." ";
      
      if(!$rst = $this->ConexionTransaccion($sql)) return false;
    
      $sql  = "DELETE FROM hc_solicitudes_dietas_detalle ";
      $sql .= "WHERE  ingreso = ".$ingreso." ";
      $sql .= "AND    evolucion_id = ".$evolucion." ";
      
      if(!$rst = $this->ConexionTransaccion($sql)) return false;
    
      foreach($datos['caracteristica_dieta'] as $k1 => $dtl)
      {
        $sql  = "INSERT INTO hc_solicitudes_dietas_detalle ";
        $sql .= "   (";
        $sql .= "     ingreso,";
        $sql .= "     evolucion_id, ";
        $sql .= "     caracteristica_id ";
        $sql .= "   ) ";
        $sql .= "VALUES ";
        $sql .= "   ( ";
        $sql .= "     ".$ingreso.",";
        $sql .= "     ".$evolucion.",";
        $sql .= "     ".$dtl." ";
        $sql .= "   ) ";
        
        if(!$rst = $this->ConexionTransaccion($sql)) return false;
      }
      
      $this->Commit();
      return true;
    }
    /**
    *
    */
		function ObtenerDietasPaciente($evolucion)
		{
			$sql  = "SELECT SU.nombre,";
      $sql .= " 	    HT.descripcion AS tipo_dieta,";
      $sql .= " 	    HT.abreviatura,";
      $sql .= " 	    HD.hc_dieta_id,";
      $sql .= " 	    HD.sw_fraccionada,";
      $sql .= " 	    HD.sw_ayuno,";
      $sql .= " 	    HD.observaciones,";
      $sql .= " 	    HD.evolucion_id,";
      $sql .= " 	    TO_CHAR(HD.fecha_registro, 'DD/MM/YYYY') AS fecha_registro ";
      $sql .= "FROM   hc_solicitudes_dietas HD, ";
      $sql .= "       hc_tipos_dieta HT, ";
      $sql .= "       system_usuarios SU ";
      $sql .= "WHERE  HD.evolucion_id = ".$evolucion." ";
      $sql .= "AND    HT.hc_dieta_id = HD.hc_dieta_id ";
      $sql .= "AND    SU.usuario_id = HD.usuario_id ";
      
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
		}
    /**
    *
    */
		function ObtenerDietaspacienteDetalle($evolucion)
		{
			$sql  = "SELECT HT.descripcion AS tipo_dieta, ";
      $sql .= "       HC.caracteristica_id, ";
      $sql .= "       HC.descripcion, ";
      $sql .= " 	    HC.codigo_agrupamiento, ";
      $sql .= " 	    HC.descripcion_agrupamiento ";
			$sql .= "FROM   hc_solicitudes_dietas HS,";
      $sql .= "       hc_tipos_dieta HT, ";
      $sql .= "       hc_solicitudes_dietas_caracteristicas HC, ";
      $sql .= "       hc_solicitudes_dietas_detalle HD ";
			$sql .= "WHERE  HS.evolucion_id = ".$evolucion." ";
      $sql .= "AND    HT.hc_dieta_id = HS.hc_dieta_id ";
      $sql .= "AND    HD.evolucion_id = ".$evolucion." ";
      $sql .= "AND    HC.caracteristica_id = HD.caracteristica_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[$rst->fields[1]][$rst->fields[0]] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
		}
    /**
    *
    */
		function EliminarDieta($ingreso,$evolucion)
		{
			$sql  = "DELETE FROM hc_controles_paciente ";
      $sql .= "WHERE  ingreso = ".$ingreso." ";
      $sql .= "AND    evolucion_id = ".$evolucion." ";
      $sql .= "AND    control_id = '25' ";
      
      $this->ConexionTransaccion();
      if(!$rst = $this->ConexionTransaccion($sql)) return false;
			
      $sql  = "DELETE FROM hc_solicitudes_dietas_detalle ";
      $sql .= "WHERE  ingreso = ".$ingreso." ";
      $sql .= "AND    evolucion_id = ".$evolucion." ";      
      
      $this->ConexionTransaccion();
      if(!$rst = $this->ConexionTransaccion($sql)) return false;
      
      $sql  = "DELETE FROM hc_solicitudes_dietas ";
      $sql .= "WHERE  ingreso = ".$ingreso." ";
      $sql .= "AND    evolucion_id = ".$evolucion." ";
      
      $this->ConexionTransaccion();
      if(!$rst = $this->ConexionTransaccion($sql)) return false;
      
      $this->Commit();
      return true;
		}
  }
 ?>