<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: Afiliados.class.php,v 1.2 2009/12/07 18:01:00 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase: Afiliados
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class Afiliados extends ConexionBD
  {
    /**
    * Constructor
    */
    function Afiliados(){}
    /**
    * Funcion donde se obtiene la informacion de los afiliados, correspondiente
    * al rango, tipo de afiliado y punto de atencion
    *
    * @param array $datos Arreglo de datos con los filtros de busqueda
    *
    * @return mixed
    */
    function ObtenerInformacionAfiliado($datos)
    {
      $sql  = "SELECT   EA.plan_atencion, ";
      $sql .= "         PR.tipo_afiliado_nombre AS tipo_afiliado_atencion, ";
      $sql .= "         EA.rango_afiliado_atencion, ";
      $sql .= "         EA.eps_punto_atencion_id, ";
      $sql .= "         EP.eps_punto_atencion_nombre, ";
      $sql .= "         PL.plan_descripcion as plan_atencion ";
   
      $sql .= "FROM     eps_afiliados EA, ";
      $sql .= "         eps_puntos_atencion EP, ";
      $sql .= "         tipos_afiliado PR, ";
      $sql .= "         planes PL ";
      $sql .= "WHERE    EA.eps_punto_atencion_id = EP.eps_punto_atencion_id ";
      $sql .= "AND      EA.afiliado_tipo_id = '".$datos['tipo_id_paciente']."' ";
      $sql .= "AND      EA.afiliado_id = '".$datos['paciente_id']."' ";
      $sql .= "AND      EA.estado_afiliado_id NOT IN ('RE') ";
      $sql .= "AND      PR.tipo_afiliado_id = EA.tipo_afiliado_atencion ";
      $sql .= "AND      EA.plan_atencion = PL.plan_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

      $datos = array();
      while (!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      
      return $datos;
    }
		
		function ObtenerCotizanteAfiliado($datos)
    {
      $sql  = " select id, tipo_id from ";
			$sql .= " ( select cotizante_id as id ,  cotizante_tipo_id as tipo_id ";
			$sql .= " from eps_afiliados_beneficiarios where afiliado_id = '".$datos['paciente_id']."' and afiliado_tipo_id = '".$datos['tipo_id_paciente']."' ";
			$sql .= " union ";
			$sql .= " select afiliado_id as id , afiliado_tipo_id as tipo_id  from eps_afiliados_cotizantes  where ";
			$sql .= " afiliado_id = '".$datos['paciente_id']."' and afiliado_tipo_id = '".$datos['tipo_id_paciente']."' ) as a limit 1 ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

      $datos = array();
      while (!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      
      return $datos;
    }
    /**
    * Funcion donde se obtienen los planes activos
    *
    * @return array 
    */
    function ObtenerPlanes()
    {
      $sql  = "SELECT plan_id, ";
      $sql .= "       plan_descripcion ";
      $sql .= "FROM   planes ";
      $sql .= "WHERE  fecha_final >= NOW() ";
      $sql .= "AND    fecha_inicio <= NOW() ";
      $sql .= "AND    estado = '1' ";
      $sql .= "AND    sw_afiliados = '1' ";
      $sql .= "ORDER BY plan_descripcion ";		
      
      $datos = array();
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      while(!$rst->EOF)
      {
        $datos[$rst->fields[0]] =  $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      
      return $datos;
    }
  }
?>