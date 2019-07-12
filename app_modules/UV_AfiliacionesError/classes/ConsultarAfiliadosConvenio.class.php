<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ConsultarAfiliadosConvenio.class.php,v 1.1.1.1 2009/09/11 20:36:58 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  */
  /**
  * Clase: ConsultarAfiliadosConvenio
  * Clase donde se ejecutan las consultas de los afiliados que son de convenio
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1.1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F. Manrique
  */
  IncludeClass("Afiliaciones", "", "app","UV_Afiliaciones");
  class ConsultarAfiliadosConvenio extends Afiliaciones
  {
    /**
    * Constructor de la clase
    */
    function ConsultarAfiliadosConvenio(){}
    /**
    * Funcion donde se consulta la informacion correspondiente a un convenio
    *
    * @param array $datos Vector con los datos de las restricciones a aplicar en
    *         la busqueda
    * @return array
    */
    function ObtenerFechasConvenio($datos)
    {
      $sql  = "SELECT CC.eps_afiliacion_id, ";
      $sql .= "       TO_CHAR(CC.fecha_inicio_convenio,'DD/MM/YYYY') AS  fecha_inicio_convenio  , ";
      $sql .= "       TO_CHAR(CC.fecha_vencimiento_convenio,'DD/MM/YYYY') AS fecha_vencimiento_convenio, ";
      $sql .= "       CC.convenio_tipo_id_tercero, ";
      $sql .= "       CC.convenio_tercero_id, ";
      $sql .= "       AD.afiliado_tipo_id   , ";
      $sql .= "       AD.afiliado_id    , ";
      $sql .= "       AD.primer_apellido    , ";
      $sql .= "       AD.segundo_apellido   , ";
      $sql .= "       AD.primer_nombre  , ";
      $sql .= "       AD.segundo_nombre     , ";
      $sql .= "       TE.nombre_tercero ";
      $sql .= "FROM   eps_afiliados_cotizantes_convenios CC, ";
      $sql .= "       terceros_uv_convenios TC, ";
      $sql .= "       terceros TE, ";
      $sql .= "       eps_afiliados_datos AD ";
      $sql .= "WHERE  CC.afiliado_tipo_id = '".$datos['afiliado_tipo_id']."' ";
      $sql .= "AND    CC.afiliado_id = '".$datos['afiliado_id']."' ";
      $sql .= "AND    CC.eps_afiliacion_id = ".$datos['eps_afiliacion_id']." ";
      $sql .= "AND    AD.afiliado_tipo_id = CC.afiliado_tipo_id ";
      $sql .= "AND    AD.afiliado_id = CC.afiliado_id ";
      $sql .= "AND    CC.convenio_tipo_id_tercero = TC.tipo_id_tercero ";
      $sql .= "AND    CC.convenio_tercero_id    = TC.tercero_id ";
      $sql .= "AND    TC.tipo_id_tercero = TE.tipo_id_tercero ";
      $sql .= "AND    TC.tercero_id     = TE.tercero_id ";

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