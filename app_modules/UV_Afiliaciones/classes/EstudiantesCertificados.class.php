<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: EstudiantesCertificados.class.php,v 1.1.1.1 2009/09/11 20:36:58 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase: EstudiantesCertificados
  * Clase encargada de hacer las consultas y las actualizaciones para los periodos de
  * cobertura de los afiliados  
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1.1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  IncludeClass("Afiliaciones", "", "app","UV_Afiliaciones");
  class EstudiantesCertificados extends Afiliaciones
  {
    /**
    * Constructor de la clase
    */
    function EstudiantesCertificados(){}
    /**
    * Funcion donde se obtiene una informacion basica de los afiliados
    * a los que se puede poner periodos de cobertura
    *
    * @param string $tipo_documento_id Tipo de documento del afiliado
    * @param string $documento_id Numero de documento del afiliado
    *
    * @return array
    */
    function ObtenerInformacionBaseAfiliado($tipo_documento_id,$documento_id)
    {
      $sql  = "SELECT AF.afiliado_tipo_id   , ";
      $sql .= "       AF.afiliado_id, ";
      $sql .= "       AA.marca, ";
      $sql .= "       AD.primer_apellido||' '||AD.segundo_apellido AS apellido, ";
      $sql .= "       AD.primer_nombre||' '||AD.segundo_nombre AS nombre, ";
      $sql .= "       DATE_PART('year',AGE(NOW(),AD.fecha_nacimiento)) AS edad, ";
      $sql .= "       AU.descripcion_subestado, ";
      $sql .= "       AE.descripcion_estado ";
      $sql .= "FROM   eps_afiliados AF, ";
      $sql .= "       eps_afiliados_datos AD, ";
      $sql .= "       eps_afiliados_estados AE,";
      $sql .= "       eps_afiliados_subestados AU, ";
      $sql .= "       ( ";
      $sql .= "         SELECT  EA.afiliado_tipo_id, ";
      $sql .= "                 EA.afiliado_id, ";
      $sql .= "                 EA.eps_afiliacion_id, ";
      $sql .= "                 '1' AS marca ";
      $sql .= "         FROM    eps_afiliados_cotizantes EA, ";
      $sql .= "                 eps_estamentos EC  ";
      $sql .= "         WHERE   EA.parentesco_id != 'D'  ";
      $sql .= "         AND     EA.estamento_id = EC.estamento_id  ";
      $sql .= "         AND     EC.estamento_siis = 'S'  ";
      $sql .= "         AND     EA.afiliado_tipo_id = '".$tipo_documento_id."' ";
      $sql .= "         AND     EA.afiliado_id = '".$documento_id."' ";
      $sql .= "         UNION ALL  ";
      $sql .= "         SELECT  afiliado_tipo_id,  ";
      $sql .= "                 afiliado_id,  ";
      $sql .= "                 eps_afiliacion_id,  ";
      $sql .= "                 '2' AS marca  ";
      $sql .= "         FROM    eps_afiliados_beneficiarios  ";
      $sql .= "         WHERE   parentesco_id != 'D'";
      $sql .= "         AND     afiliado_tipo_id = '".$tipo_documento_id."' ";
      $sql .= "         AND     afiliado_id = '".$documento_id."' ";
      $sql .= "        ) AS AA ";
      $sql .= "WHERE  AF.afiliado_id = AA.afiliado_id ";
      $sql .= "AND    AF.afiliado_tipo_id = AA.afiliado_tipo_id ";
      $sql .= "AND    AF.afiliado_id = AD.afiliado_id ";
      $sql .= "AND    AF.afiliado_tipo_id = AD.afiliado_tipo_id ";
      $sql .= "AND    AD.afiliado_tipo_id = '".$tipo_documento_id."' ";
      $sql .= "AND    AD.afiliado_id = '".$documento_id."' ";
      $sql .= "AND    AF.estado_afiliado_id NOT IN ('RE','AF') ";
      $sql .= "AND    AU.estado_afiliado_id = AF.estado_afiliado_id ";
      $sql .= "AND    AU.estado_afiliado_id = AE.estado_afiliado_id ";
      $sql .= "AND    AU.subestado_afiliado_id = AF.subestado_afiliado_id ";
      
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
    * Funcion donde se obtienen los periodos de cobertura registrados
    *
    * @return array
    */
    function ObtenerPeriodosCobertura()
    {
      $sql  = "SELECT periodo_descripcion,"; 	
      $sql .= "       TO_CHAR(periodo_fecha_inicio,'DD/MM/YYYY') AS inicio ,"; 	
      $sql .= "       TO_CHAR(periodo_fecha_fin,'DD/MM/YYYY') AS fin ";
      $sql .= "FROM   eps_afiliados_periodos_atencion ";
      $sql .= "WHERE  sw_estado = '1' ";
      $sql .= "ORDER BY eps_afiliados_periodo_atencion_id DESC ";
      
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
    /**
    * Funcion donde se obtiene el ultimo periodo de cobertura registrado para 
    * un afiliado
    *
    * @param string $tipo_documento_id Tipo de documento del afiliado
    * @param string $documento_id Numero de documento del afiliado
    *
    * @return array
    */
    function ObtenerUltimoPeriodoCobertura($tipo_documento_id,$documento_id)
    { 	
      $sql  = "SELECT TO_CHAR(cobertura_fecha_inicio,'DD/MM/YYYY') AS inicio, ";  	
      $sql .= "       TO_CHAR(cobertura_fecha_fin,'DD/MM/YYYY') AS fin, ";
	  $sql .= "       institucion ";	  
      $sql .= "FROM   eps_afiliados_cobertura_estudiantes ";
      $sql .= "WHERE  afiliado_tipo_id = '".$tipo_documento_id."' ";
      $sql .= "AND    afiliado_id = '".$documento_id."' ";
      $sql .= "AND    eps_afiliados_atencion_estudiante_id = (";
      $sql .= "       SELECT MAX(eps_afiliados_atencion_estudiante_id) ";
      $sql .= "       FROM   eps_afiliados_cobertura_estudiantes ";
      $sql .= "       WHERE  afiliado_tipo_id = '".$tipo_documento_id."' ";
      $sql .= "       AND    afiliado_id = '".$documento_id."') ";
      
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
    * Funcion donde se obtinen datos basicos del contizante, para un beneficiario
    * dado
    *
    * @param array $datos Vector con los datos de la identificacion del beneficiario
    *
    * @return array
    */
    function ObtenerBeneficiariosCotizante($datos)
    {
      $sql  = "SELECT AD.afiliado_tipo_id   , ";
      $sql .= "       AD.afiliado_id, ";
      $sql .= "       AD.primer_apellido    , ";
      $sql .= "       AD.segundo_apellido   , ";
      $sql .= "       AD.primer_nombre  , ";
      $sql .= "       AD.segundo_nombre ";
      $sql .= "FROM   eps_afiliados_datos AD,";
      $sql .= "       eps_afiliados AF,";
      $sql .= "       eps_afiliados_beneficiarios AB ";
      $sql .= "WHERE  AB.afiliado_tipo_id = '".$datos['afiliado_tipo_id']."' ";
      $sql .= "AND    AB.afiliado_id = '".$datos['afiliado_id']."' ";
      $sql .= "AND    AB.cotizante_tipo_id = AF.afiliado_tipo_id ";
      $sql .= "AND    AB.cotizante_id = AF.afiliado_id ";
      $sql .= "AND    AB.eps_afiliacion_id = AF.eps_afiliacion_id ";
      $sql .= "AND    AD.afiliado_tipo_id = AF.afiliado_tipo_id ";
      $sql .= "AND    AD.afiliado_id = AF.afiliado_id ";
      $sql .= "AND    AF.estado_afiliado_id NOT IN ('RE','AF') ";
      
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
    * Funcion donde se ingresa el periodo de cobertura del afiliado
    * 
    * @param array $datos Vector con los datos del afiliado y el periodo de cobertura
    *
    * @return boolean
    */
    function IngresarPeriodoCobertura($datos)
    { 
      $sql  = "UPDATE eps_afiliados_cobertura_estudiantes ";
      $sql .= "SET    sw_estado = '2', ";
      $sql .= "       usuario_ultima_actualizacion = ".UserGetUID().", ";
      $sql .= "       fecha_ultima_actualizacion = NOW() ";
      $sql .= "WHERE  afiliado_tipo_id = '".$datos['afiliado_tipo_id']."' ";
      $sql .= "AND    afiliado_id = '".$datos['afiliado_id']."' ";
      $sql .= "AND    sw_estado != '2'; ";
      
      $sql .= "INSERT INTO eps_afiliados_cobertura_estudiantes( ";
      $sql .= "     afiliado_tipo_id, ";
      $sql .= "     afiliado_id, ";
      $sql .= "     cobertura_fecha_inicio, ";
      $sql .= "     cobertura_fecha_fin, ";
      $sql .= "     institucion, ";
      $sql .= "     observaciones, ";
      $sql .= "     usuario_registro, ";
      $sql .= "     fecha_registro ";
      $sql .= ") ";
      $sql .= "VALUES (";
      $sql .= "     '".$datos['afiliado_tipo_id']."',";
      $sql .= "     '".$datos['afiliado_id']."',";
      $sql .= "     '".$this->DividirFecha($datos['fecha_inicio'])."',";
      $sql .= "     '".$this->DividirFecha($datos['fecha_fin'])."',";
      $sql .= "     '".$datos['institucion']."',";
      $sql .= "     '".$datos['observacion']."',";
      $sql .= "      ".UserGetUID().",";
      $sql .= "      NOW() ";
      $sql .= "); ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      return true;
    }
    /**
    * Obtiene la lista de los periodos de cobertura y aquienes pertenece
    *
    * @param array $datos Vector con los datos del buscador
    * @param int   $pg_siguiente Numero de pagina
    *
    * @return array
    */
    function ObtenerListaPeriodos($datos,$pg_siguiente)
    {
      $sql  = "SELECT AF.afiliado_tipo_id   , ";
      $sql .= "       AF.afiliado_id, ";
      $sql .= "       AC.eps_afiliados_atencion_estudiante_id, ";
      $sql .= "       AD.primer_apellido||' '||AD.segundo_apellido AS apellidos, ";
      $sql .= "       AD.primer_nombre||' '||AD.segundo_nombre AS nombres, ";
      $sql .= "       DATE_PART('year',AGE(NOW(),AD.fecha_nacimiento)) AS edad, ";
      $sql .= "       AU.descripcion_subestado, ";
      $sql .= "       AE.descripcion_estado, ";
      $sql .= "       AC.observaciones, ";
      $sql .= "       SU.nombre, ";
      $sql .= "       TO_CHAR(AC.cobertura_fecha_inicio,'DD/MM/YYYY') AS inicio, ";  	
      $sql .= "       TO_CHAR(AC.cobertura_fecha_fin,'DD/MM/YYYY') AS fin "; 
      $whr  = "FROM   eps_afiliados AF, ";
      $whr .= "       eps_afiliados_datos AD, ";
      $whr .= "       eps_afiliados_estados AE,";
      $whr .= "       eps_afiliados_subestados AU, ";
      $whr .= "       eps_afiliados_cobertura_estudiantes AC, ";
      $whr .= "       system_usuarios SU ";
      $whr .= "WHERE  AF.afiliado_id = AD.afiliado_id ";
      $whr .= "AND    AF.afiliado_tipo_id = AD.afiliado_tipo_id ";
      $whr .= "AND    AC.afiliado_tipo_id = AD.afiliado_tipo_id ";
      $whr .= "AND    AC.afiliado_id = AD.afiliado_id ";     
      $whr .= "AND    AC.usuario_registro = SU.usuario_id ";     
      $whr .= "AND    AC.sw_estado = '1' ";     
      $whr .= "AND    AF.estado_afiliado_id NOT IN ('RE','AF') ";
      $whr .= "AND    AU.estado_afiliado_id = AF.estado_afiliado_id ";
      $whr .= "AND    AU.estado_afiliado_id = AE.estado_afiliado_id ";
      $whr .= "AND    AU.subestado_afiliado_id = AF.subestado_afiliado_id ";
      
      if(!empty($datos) && $datos['tipo_documento_id'] != '-1')
      {
        $whr .= "AND    AD.afiliado_tipo_id = '".$datos['afiliado_tipo_id']."' ";
        $whr .= "AND    AD.afiliado_id = '".$datos['afiliado_id']."' ";
      }
      
      if(!$this->ProcesarSqlConteo("SELECT COUNT(*) $whr",$pg_siguiente))
        return false;
      
      $whr .= "ORDER BY apellidos,nombres ";
      $whr .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql.$whr)) return false;
      
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
    * Funcion donde se ejecuta la setencia para hacer la anulacion del periodo 
    * de cobertura seleccionado
    * 
    * @param array $datos Vector con los datos del periodo de cobertura 
    *
    * @return boolean
    */
    function AnularPeriodo($datos)
    {
      $sql  = "UPDATE eps_afiliados_cobertura_estudiantes ";
      $sql .= "SET    sw_estado = '0', ";
      $sql .= "       usuario_ultima_actualizacion = ".UserGetUID().", ";
      $sql .= "       fecha_ultima_actualizacion = NOW() ";
      $sql .= "WHERE  eps_afiliados_atencion_estudiante_id = ".$datos['eps_afiliados_atencion_estudiante_id']." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      return true;
    }
  }
?>