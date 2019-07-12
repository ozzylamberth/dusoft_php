<?php
 /**
  * @package IPSOFT-SIIS
  * @version $Id: InformacionAfiliados.class.php,v 1.1 2009/09/02 13:08:12 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : InformacionAfiliados
  * Clase encargada de hacer las consultas
  *  
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class InformacionAfiliados extends ConexionBD
  {
    /**
    * Contructor de la clase
    */
    function InformacionAfiliados(){}
    /**
    * Obtiene la informacion de un afiliado determinado
    *
    * @param array $datos Vector con la informacion del tipo e identificacion
    *               del afiliado
    * @return array
    */
    function ObtenerDatosAfiliados($datos)
    {
      $sql  = "SELECT AD.afiliado_tipo_id AS tipo_id_paciente , ";
      $sql .= "       AD.afiliado_id AS paciente_id, ";
      $sql .= "       AD.primer_apellido    , ";
      $sql .= "       AD.segundo_apellido   , ";
      $sql .= "       AD.primer_nombre  , ";
      $sql .= "       AD.segundo_nombre     , ";
      $sql .= "       AD.fecha_nacimiento, ";
      $sql .= "       AD.tipo_sexo_id   , ";
      $sql .= "       AD.tipo_pais_id   , ";
      $sql .= "       AD.tipo_dpto_id   , ";
      $sql .= "       AD.tipo_mpio_id   , ";
      $sql .= "       AD.zona_residencia    , ";
      $sql .= "       AD.direccion_residencia   , ";
      $sql .= "       AD.telefono_residencia ";
      $sql .= "FROM   eps_afiliados_datos AD,";
      $sql .= "       eps_afiliados AF ";
      $sql .= "WHERE  AD.afiliado_tipo_id = '".$datos['tipo_id_paciente']."' ";
      $sql .= "AND    AD.afiliado_id = '".$datos['paciente_id']."' ";
      $sql .= "AND    AD.afiliado_tipo_id = AF.afiliado_tipo_id ";
      $sql .= "AND    AD.afiliado_id = AF.afiliado_id ";
      $sql .= "AND    AF.estado_afiliado_id IN ('AC') ";

      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

      $datos = array();
      while(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
  }
?>