<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: ConsultarBeneficiarios.class.php,v 1.2 2009/09/30 12:52:13 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase: ConsultarBeneficiarios
  * Clase encargada de ejecutar las consultas de los datos de los beneficiarios
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  IncludeClass("Afiliaciones", "", "app","UV_Afiliaciones");
  class ConsultarBeneficiarios extends Afiliaciones
  {
    /**
    * Constructor de la clase
    */
    function ConsultarBeneficiarios(){}
    /**
    * Funcion donde se consultan los datos de loa benficiarios que estan asociados
    * a un contizante
    *
    * @param array $datos Vector con los datos de la identificacion del cotizante
    *
    * @return array
    */
    function ObtenerBeneficiariosCotizante($datos,$op = 1)
    {
      $sql  = "SELECT AD.afiliado_tipo_id   , ";
      $sql .= "       AD.afiliado_id, ";
      $sql .= "       AD.primer_apellido    , ";
      $sql .= "       AD.segundo_apellido   , ";
      $sql .= "       AD.primer_nombre  , ";
      $sql .= "       AD.segundo_nombre     , ";
      $sql .= "       TO_CHAR(AD.fecha_nacimiento,'DD/MM/YYYY') AS fecha_nacimiento, ";
      $sql .= "       TO_CHAR(AD.fecha_afiliacion_sgss,'DD/MM/YYYY') AS fecha_afiliacion_sgss, ";
      $sql .= "       AD.tipo_sexo_id   , ";
      $sql .= "       AD.tipo_pais_id   , ";
      $sql .= "       AD.tipo_dpto_id   , ";
      $sql .= "       AD.tipo_mpio_id   , ";
      $sql .= "       AD.zona_residencia    , ";
      $sql .= "       AD.direccion_residencia   , ";
      $sql .= "       AD.telefono_residencia    , ";
      $sql .= "       AD.telefono_movil     , ";
      $sql .= "       AF.eps_afiliacion_id  , ";
      $sql .= "       AU.descripcion_subestado, ";
      $sql .= "       AT.descripcion_estado, ";
      $sql .= "       PB.descripcion_parentesco , ";
      $sql .= "       TP.pais ||'-'||TD.departamento||'-'||TM.municipio AS departamento_municipio ";
      $sql .= "FROM   eps_afiliados_datos AD,";
      $sql .= "       eps_afiliados AF,";
      $sql .= "       eps_afiliados_estados AT,";
      $sql .= "       eps_afiliados_subestados AU, ";
      $sql .= "       eps_afiliados_beneficiarios AB,";
      $sql .= "       eps_parentescos_beneficiarios PB,";
      $sql .= "       tipo_pais TP,";
      $sql .= "       tipo_dptos TD,";
      $sql .= "       tipo_mpios TM ";
      $sql .= "WHERE  AB.cotizante_tipo_id = '".$datos['afiliado_tipo_id']."' ";
      $sql .= "AND    AB.cotizante_id = '".$datos['afiliado_id']."' ";
      if($datos['eps_afiliacion_id'])
        $sql .= "AND    AB.eps_afiliacion_id = ".$datos['eps_afiliacion_id']." ";
        
      $sql .= "AND    AD.tipo_pais_id = TP.tipo_pais_id ";
      $sql .= "AND    AD.tipo_pais_id = TD.tipo_pais_id ";
      $sql .= "AND    AD.tipo_dpto_id = TD.tipo_dpto_id ";      
      $sql .= "AND    AD.tipo_pais_id = TM.tipo_pais_id ";
      $sql .= "AND    AD.tipo_dpto_id = TM.tipo_dpto_id ";
      $sql .= "AND    AD.tipo_mpio_id = TM.tipo_mpio_id ";
      $sql .= "AND    AD.afiliado_tipo_id = AF.afiliado_tipo_id ";
      $sql .= "AND    AD.afiliado_id = AF.afiliado_id ";
      $sql .= "AND    AB.eps_afiliacion_id = AF.eps_afiliacion_id ";
      $sql .= "AND    AB.afiliado_tipo_id = AF.afiliado_tipo_id ";
      $sql .= "AND    AB.afiliado_id = AF.afiliado_id ";
      $sql .= "AND    AB.parentesco_id = PB.parentesco_id ";
      $sql .= "AND    AF.estado_afiliado_id = AU.estado_afiliado_id ";
      $sql .= "AND    AF.subestado_afiliado_id = AU.subestado_afiliado_id ";
      $sql .= "AND    AU.estado_afiliado_id = AT.estado_afiliado_id ";
      
      if($op == 1)
        $sql .= "AND    AF.estado_afiliado_id != 'RE' ";
      $sql .= "ORDER BY AF.eps_afiliacion_id ASC ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

      $datos = array();
      while(!$rst->EOF)
      {
        $datos[$rst->fields[0]][$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
  }
?>