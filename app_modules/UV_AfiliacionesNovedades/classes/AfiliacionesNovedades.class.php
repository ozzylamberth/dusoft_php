<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: AfiliacionesNovedades.class.php,v 1.2 2008/07/18 19:27:57 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase: AfiliacionesNovedades
  * Clase encargada de hacer las consultas y las actualizaciones para 
  * las novedades de los afiliados
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class AfiliacionesNovedades extends ConexionBD
  {
    /**
    * Constructor de la clase
    */
    function AfiliacionesNovedades(){}
    /**
    * Funcion donde se obtienen los permisos del usuario sobre el modulo
    *
    * @return boolean
    */
    function ObtenerPermisos()
    {
      $sql  = "SELECT usuario_id ";
      $sql .= "FROM   userpermisos_novedades ";
      $sql .= "WHERE  sw_activo = '1' ";
      $sql .= "AND    usuario_id = ".UserGetUID()." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

      $datos = array();
      while (!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      
      if(empty($datos)) return false;
      
      return true;
    }
    /**
    * Funcion donde se verifica que un afiliado esta registradoe en el 
    * sistema 
    *
    * @param string $tipo_documento_id Tipo de identificacion
    * @param string $documento_id Numero de identificacion
    *
    * @return boolean
    */
    function ExistenciaAfiliado($tipo_documento_id,$documento_id)
    {
      $sql  = "SELECT AD.afiliado_tipo_id   , ";
      $sql .= "       AD.afiliado_id ";
      $sql .= "FROM   eps_afiliados_datos AD ";
      $sql .= "WHERE  AD.afiliado_tipo_id = '".$tipo_documento_id."' ";
      $sql .= "AND    AD.afiliado_id = '".$documento_id."' ";
          
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

      $datos = array();
      while(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      
      if(empty($datos))
        return false;
      
      return true;
    }
    /**
    * Funcion domde se seleccionan los tipos de id de los terceros
    *
    * @return array datos de tipo_id_terceros
    */
    function ObtenerTiposIdentificacion()
    {
      $sql  = "SELECT tipo_id_paciente,";
      $sql .= "       descripcion ";
      $sql .= "FROM   tipos_id_pacientes ";

      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

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
    * Funcion donde se obtiene la informacion de un afiliado que
    * esta registrado en el sistema
    *
    * @param array $datos Vector con la informacion del tipo e identificacion
    *               del afiliado
    *
    * @return array
    */
    function ObtenerInformacionAfiliado($datos)
    {
      $sql  = "SELECT AD.afiliado_tipo_id , ";
      $sql .= "       AD.afiliado_id , ";
      $sql .= "       AD.primer_apellido , ";
      $sql .= "       AD.segundo_apellido , ";
      $sql .= "       AD.primer_nombre , ";
      $sql .= "       AD.segundo_nombre , ";
      $sql .= "       TO_CHAR(AD.fecha_nacimiento,'DD/MM/YYYY') AS fecha_nacimiento, ";
      $sql .= "       AD.tipo_sexo_id , ";
      $sql .= "       AD.tipo_pais_id , ";
      $sql .= "       AD.tipo_dpto_id , ";
      $sql .= "       AD.tipo_mpio_id , ";
      $sql .= "       AD.zona_residencia , ";
      $sql .= "       AD.direccion_residencia , ";
      $sql .= "       AD.telefono_residencia , ";
      $sql .= "       AD.telefono_movil , ";
      $sql .= "       TP.pais ||'-'||TD.departamento||'-'||TM.municipio AS departamento_municipio ";
      $sql .= "FROM   eps_afiliados_datos AD,";
      $sql .= "       tipo_pais TP,";
      $sql .= "       tipo_dptos TD,";
      $sql .= "       tipo_mpios TM ";
      $sql .= "WHERE  AD.afiliado_tipo_id = '".$datos['afiliado_tipo_id']."' ";
      $sql .= "AND    AD.afiliado_id = '".$datos['afiliado_id']."' ";
      $sql .= "AND    AD.tipo_pais_id = TP.tipo_pais_id ";
      $sql .= "AND    AD.tipo_dpto_id = TD.tipo_dpto_id ";
      $sql .= "AND    AD.tipo_mpio_id = TM.tipo_mpio_id ";
      $sql .= "AND    TD.tipo_pais_id = TP.tipo_pais_id ";
      $sql .= "AND    TM.tipo_pais_id = TD.tipo_pais_id ";
      $sql .= "AND    TM.tipo_dpto_id = TD.tipo_dpto_id ";

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
    /**
    * Funcion donde se hace el ingreso de la novedad generada manualmente
    *
    * @param array $datos Vector con la informacion del tipo e identificacion
    *               del afiliado
    * @param string $empresa identificador de la empresa, por defecto es 01 
    *
    * @return boolean
    */
    function IngresarNovedad($datos,$empresa = "01")
    {
      $sql  = "SELECT codigo_sgsss ";
      $sql .= "FROM   empresas ";
      $sql .= "WHERE  empresa_id = '".$empresa."'";
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

      $sgsss = array();

      if (!$rst->EOF)
      {
        $sgsss = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      
      $afi['afiliado_tipo_id'] = $datos['afiliado_tipo_id_v'];
      $afi['afiliado_id'] = $datos['afiliado_id_v'];
      
      $afiliado = $this->ObtenerInformacionAfiliado($afi);
      
      $sql  = "INSERT INTO eps_novedades_ingresos(";
      $sql .= "   codigo_sgss_entidad,"; 	
      $sql .= "   afiliado_tipo_id 	,";
      $sql .= "   afiliado_id ,";
      $sql .= "   primer_apellido ,";
      $sql .= "   segundo_apellido ,";
      $sql .= "   primer_nombre	,";
      $sql .= "   segundo_nombre ,";
      $sql .= "   fecha_nacimiento ,";
      $sql .= "   codigo_novedad ,";  	
      $sql .= "   fecha_inicio_novedad ,";
      $sql .= "   nuevo_valor_1 ,";
      $sql .= "   nuevo_valor_2 ,";
      $sql .= "   nuevo_valor_3 ,";
      $sql .= "   nuevo_valor_4 ,";
      $sql .= "   nuevo_valor_5 ,";
      $sql .= "   nuevo_valor_6 ,";
      $sql .= "   nuevo_valor_7 ,";
      $sql .= "   usuario_registro ,";
      $sql .= "   sw_interfazado ";
      $sql .= ") ";
      $sql .= "VALUES ( ";
      $sql .= "   '".$sgsss['codigo_sgsss']."', ";
      $sql .= "   '".$afiliado['afiliado_tipo_id']."', ";
      $sql .= "   '".$afiliado['afiliado_id']."', ";
      $sql .= "   '".$afiliado['primer_apellido']."', ";
      $sql .= "   '".$afiliado['segundo_apellido']."', ";
      $sql .= "   '".$afiliado['primer_nombre']."', ";
      $sql .= "   '".$afiliado['segundo_nombre']."', ";
      $sql .= "   '".$this->DividirFecha($afiliado['fecha_nacimiento'])."', ";
      $sql .= "   '".$datos['novedad']."', ";
      $sql .= "   NOW(), ";
      switch($datos['novedad'])
      {
        case 'N01':
          $sql .= "   '".$datos['afiliado_tipo_id']."', ";
          $sql .= "   '".$datos['afiliado_id']."', ";
          $sql .= "   '".$this->DividirFecha($datos['fecha_nacimiento'])."', ";
        break;
        case 'N02':
          $sql .= "   '".strtoupper(trim($datos['primer_nombre']))."', ";
          $sql .= "   '".strtoupper(trim($datos['segundo_nombre']))."', ";
          $sql .= "   NULL, ";
        break;
        case 'N03':
          $sql .= "   '".strtoupper(trim($datos['primer_apellido']))."', ";
          $sql .= "   '".strtoupper(trim($datos['segundo_apellido']))."', ";
          $sql .= "   NULL, ";
        break;
        case 'N17':
          $sql .= "   '".$datos['tipo_sexo']."', ";
          $sql .= "   NULL, ";
          $sql .= "   NULL, ";
        break;
      }
      
      $sql .= "   NULL, ";
      $sql .= "   NULL, ";
      $sql .= "   NULL, ";
      $sql .= "   NULL, ";
      $sql .= "   ".UserGetUID().", ";
      $sql .= "   '1' ";
      $sql .= "); ";

      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

      return true;
    }
  }
?>