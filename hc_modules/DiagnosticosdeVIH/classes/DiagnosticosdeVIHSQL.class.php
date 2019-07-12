<?php
 /**
  * @package IPSOFT-SIIS
  * @version $Id: DiagnosticosdeVIHSQL.class.php,v 1.6 2009/11/06 14:51:34 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : DiagnosticosdeVIHSQL
  *  
  * @package IPSOFT-SIIS
  * @version $Revision: 1.6 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Manuel Ruiz
  */
  class DiagnosticosdeVIHSQL extends ConexionBD
  {
    /**
    * Constructor de la clase
    */
    function DiagnosticosdeVIHSQL() {}
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
    * Funcion para consultar la informacion almacenada en la tabla perinatal
    * @return array $datos vector que contiene los datos de la consulta
    */
    function ConsultarPerinatal()
    {
      //$this->debug = true;
      $sql  = "SELECT     perinatal_id, descripcion ";
      $sql .= "FROM       perinatal ";
      $sql .= "WHERE      sw_activo='1' ";
      $sql .= "ORDER BY   perinatal_id, descripcion ";
      
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
    * Funcion para consultar la informacion almacenada en la tabla parenteral
    * @return array $datos vector que contiene la informacion de la consulta
    */
    function ConsultarParenteral()
    {
      //$this->debug=true;
      $sql  = "SELECT     parenteral_id, descripcion ";
      $sql .= "FROM       parenteral ";
      $sql .= "WHERE      sw_activo='1' ";
      $sql .= "ORDER BY   parenteral_id, descripcion ";
      
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
    * Funcion para consultar los datos almacenados en la tabla transmision_otros
    * @return array $datos vector que contiene los datos de la consulta
    */
    function ConsultarTransmisionOtros()
    {
      //$this->debug = true;
      $sql  = "SELECT     transmision_id, descripcion ";
      $sql .= "FROM       transmision_otros ";
      $sql .= "WHERE      sw_activo='1' ";
      $sql .= "ORDER BY   transmision_id, descripcion ";
      
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
    * Funcion para consultar los datos almacenados en la tabla tipos_prueba
    * @return array $datos vector que contiene los datos de la consulta
    */
    function ConsultarTiposPrueba()
    {
      //$this->debug = true;
      $sql  = "SELECT     tipo_prueba_id, descripcion ";  
      $sql .= "FROM       tipos_prueba ";
      $sql .= "GROUP BY   tipo_prueba_id, descripcion ";
      
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
    * Funcion para consultar los datos almacenados en la tabla estados_clinicos
    * @return array $datos vector que contiene los datos de la consulta
    */
    function ConsultarEstadosClinicos()
    {
      //$this->debug=true;
      $sql  = "SELECT     estado_clinico_id, descripcion ";
      $sql .= "FROM       estados_clinicos ";
      $sql .= "ORDER BY   estado_clinico_id, descripcion";
      
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
    * Funcion para consultar los datos almacenados en la tabla enfermedades_asociadas
    * @return array $datos vector que contiene los datos de la consulta
    */
    function ConsultarEnfermedades()
    {
      //$this->debug=true;
      $sql  = "SELECT     enfermedad_id, descripcion ";
      $sql .= "FROM       enfermedades_asociadas ";
      $sql .= "WHERE      sw_activo='1' ";
      $sql .= "ORDER BY   enfermedad_id ";
      
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
    * Funcion para consultar los datos almacenados en la tabla areas_procedencia
    * @return array $datos vector que contiene la informacion de la consulta
    */
    function ConsultarAreasProcedencia($area)
    {      
      $sql  = "SELECT area_procedencia_id, descripcion ";
      $sql .= "FROM   areas_procedencia ";
      $sql .= "WHERE  sw_activo='1' ";
      
      if($area != "")
        $sql .= "AND   area_procedencia_id = ".$area." ";
        
      $sql .= "ORDER BY   area_procedencia_id, descripcion ";
      
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
    * Funcion en la que se consultan los datos almacenados en la tabla tipos_regimen_salud
    * @return array $datos vector que contiene los datos de la consulta
    */
    function ConsultarTiposRegimen()
    {
      //$this->debug=true;
      
      $sql  = "SELECT     tipo_regimen_id, descripcion ";
      $sql .= "FROM       tipos_regimen_salud ";
      $sql .= "WHERE      sw_activo='1' ";
      $sql .= "ORDER BY   tipo_regimen_id, descripcion ";
      
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
    * Funcion en la que se consulta la informacion almacenada en la tabla 
    * pertenencias_etnicas
    * @return array $datos vector con los datos de la consulta
    */
    function ConsultarPertenenciasEtnicas()
    {
      //$this->debug=true;
      
      $sql  = "SELECT     pert_etnica_id, descripcion ";
      $sql .= "FROM       pertenencias_etnicas ";
      $sql .= "WHERE      sw_activo='1' ";
      $sql .= "ORDER BY   pert_etnica_id, descripcion ";
      
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
    * Funcion en la que se consultan los datos almacenados en la tabla grupos_poblacionales
    * @return array $datos vector que contiene los datos de la consulta
    */
    function ConsultarGruposPoblacionales()
    {
      //$this->debug=true;
      
      $sql  = "SELECT     grupo_poblacional_id, descripcion ";
      $sql .= "FROM       grupos_poblacionales ";
      $sql .= "WHERE      sw_activo='1' ";
      $sql .= "ORDER BY   grupo_poblacional_id, descripcion ";
      
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
    * Funcion en la que se consultan los datos almacenados en la tabla 
    * clasificaciones_caso_sintoma
    * @return array $datos vector que contiene los datos de la consulta
    */
    function ConsultarClasiSintomas()
    {
      //$this->debug=true;
      
      $sql  = "SELECT     caso_sintoma_id, descripcion ";
      $sql .= "FROM       clasificaciones_caso_sintoma ";
      $sql .= "WHERE      sw_activo='1' ";
      $sql .= "ORDER BY   caso_sintoma_id, descripcion ";
      
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
    * Funcion donde se obtiene la informacion del diagnostico
    *
    * @param string $diagnostico Identificador del diagnostico
    *
    * @return mixed
    */
    function ConsultarDiagnostico($diagnostico)
  	{
      $sql  = "SELECT diagnostico_id, ";
      $sql .= "       diagnostico_nombre ";
      $sql .= "FROM   diagnosticos  ";
      $sql .= "WHERE  diagnostico_id = '".$diagnostico."' ";

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
    * Funcion donde se obtiene la informacion de la empresa
    *
    * @param string $empresa identificador de la empresa
    *
    * @return mixed
    */
    function ConsultarEmpresa($empresa_id)
  	{
      // $this->debug = true;
      $sql  = "SELECT EM.razon_social, ";
      $sql .= "       EM.codigo_sgsss, ";
      $sql .= "       D1.departamento, ";
      $sql .= "       M1.municipio, ";
      $sql .= "       M1.tipo_pais_id, ";
      $sql .= "       M1.tipo_dpto_id, ";
      $sql .= "       M1.tipo_mpio_id ";
      $sql .= "FROM   empresas EM,  ";
      $sql .= "       tipo_dptos D1, ";
      $sql .= "       tipo_mpios M1 ";
      $sql .= "WHERE  EM.empresa_id = '".$empresa_id."' ";
      $sql .= "AND    EM.tipo_pais_id = D1.tipo_pais_id ";
      $sql .= "AND    EM.tipo_dpto_id = D1.tipo_dpto_id ";      
      $sql .= "AND    EM.tipo_pais_id = M1.tipo_pais_id ";
      $sql .= "AND    EM.tipo_dpto_id = M1.tipo_dpto_id ";
      $sql .= "AND    EM.tipo_mpio_id = M1.tipo_mpio_id ";
      
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
    * Funcion donde se realiza el ingreso de la informacion de las fichas de notificacion 
    * (datos basicos) en la tabla hc_fichas_notificacion
    * @return array $indice['sq'] retorna el campo que identifica a la tabla 
    * hc_fichas_notificacion 
    */
    function IngresarFichasNotificacion($request, $datos_paciente, $evolucion)
    {
      //$this->debug = true;
      
      $indice = array();
      
      $this->ConexionTransaccion();
      
      $sql = "SELECT NEXTVAL('hc_fichas_notificacion_ficha_notificacion_id_seq'::regclass) AS sq";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      if(!$rst->EOF)
      {
        $indice = $rst->GetRowAssoc($ToUpper=false);
        $rst->MoveNext();
      }
      
      $rst->Close();
      
      $sqlerror = "SELECT setval('hc_fichas_notificacion_ficha_notificacion_id_seq', ".($indice['sq']-1).") ";
      
      $sql  = "INSERT INTO hc_fichas_notificacion( ";
      $sql .= "       ficha_notificacion_id, ";
      $sql .= "       nombre_evento, ";
      $sql .= "       cod_evento, ";
      $sql .= "       fecha_notificacion, ";
      $sql .= "       semana, ";
      $sql .= "       anyo, ";
      $sql .= "       notif_tipo_pais_id, ";
      $sql .= "       notif_tipo_dpto_id, ";
      $sql .= "       notif_tipo_mpio_id, ";
      $sql .= "       razon_social, ";
      $sql .= "       codigo_sgsss, ";
      $sql .= "       proce_tipo_pais_id, ";
      $sql .= "       proce_tipo_dpto_id, ";
      $sql .= "       proce_tipo_mpio_id, ";
      $sql .= "       proce_barrio, ";
      $sql .= "       area_procedencia_id, ";
      $sql .= "       tipo_regimen_id, ";
      $sql .= "       pert_etnica_id, ";
      $sql .= "       grupo_poblacional_id, ";
      $sql .= "       nombre_admin_serv, ";
      $sql .= "       cod_admin_serv, ";
      $sql .= "       fecha_consulta, ";
      $sql .= "       fecha_inicio_sintomas, ";
      $sql .= "       caso_sintoma_id, ";
      $sql .= "       estado_hospi, ";
      $sql .= "       fecha_hospi, ";
      $sql .= "       condicion_final, ";
      $sql .= "       fecha_defuncion, ";
      $sql .= "       certificado_defuncion, ";
      $sql .= "       causa_muerte, ";
      $sql .= "       codigo_cie10, ";
      $sql .= "       paciente_id, ";
      $sql .= "       tipo_id_paciente, ";
      $sql .= "       ocupacion_id, ";
      $sql .= "       residencia_direccion ,";
      $sql .= "       residencia_telefono ,";
      $sql .= "       tipo_pais_id ,";
      $sql .= "       tipo_dpto_id ,";
      $sql .= "       tipo_mpio_id ,";
      $sql .= "       edad, ";
      $sql .= "       evolucion_id, ";
      $sql .= "       usuario_id ";
      $sql .= ")VALUES( ";
      $sql .= "       ".$indice['sq'].", ";
      $sql .= "       '".$request['evento']."', ";
      $sql .= "       '".$request['codEvento']."', ";
      $sql .= "       '".$request['fechaNotificacion']."', ";
      $sql .= "       ".$request['semanaEvento'].", ";
      $sql .= "       ".$request['anyoEvento'].", ";
      $sql .= "       '".$request['pais']."', ";
      $sql .= "       '".$request['dpto']."', ";
      $sql .= "       '".$request['mpio']."', ";
      $sql .= "       '".$request['razonSocial']."', ";
      $sql .= "       '".$request['codigosgss']."', ";
      $sql .= "       '".$request['paisM3']."', ";
      $sql .= "       '".$request['dptoM3']."', ";
      $sql .= "       '".$request['mpioM3']."', ";
      $sql .= "       '".$request['barrio']."', ";
      $sql .= "       ".$request['areaProcedencia'].", ";
      $sql .= "       ".$request['tipoRegimen'].", ";
      $sql .= "       ".$request['pertenenciaEtnica'].", ";
      $sql .= "       ".$request['grupoPoblacional'].", ";
      $sql .= "       '".$request['adminServicios']."', ";
      $sql .= "       '".$request['codAdmin']."', ";
      $sql .= "       '".$request['fechaConsulta']."', ";
      $sql .= "       '".$request['fechaSintomas']."', ";
      $sql .= "       ".$request['clasiCaso'].", ";
      $sql .= "       '".$request['hospitalizado']."', ";
      
      if($request['fechaHospitalizacion']!="" && $request['hospitalizado'] == "SI")
        $sql .= "     '".$request['fechaHospitalizacion']."', ";
      else
        $sql .= "     NULL, ";
      $sql .= "       '".$request['condicionFinal']."', ";
      
      if($request['fechaDefuncion']!="" && $request['condicionFinal'] == "Muerto")
        $sql .= "     '".$request['fechaDefuncion']."', ";
      else
        $sql .= "     NULL, ";
        
      if($request['noCertificado']!="" && $request['condicionFinal'] == "Muerto")
        $sql .= "     ".$request['noCertificado'].", ";
      else
        $sql .= "     NULL, ";
        
      if(!$request['condicionFinal'] == "Muerto") 
      {
        $request['causaMuerte'] = "";
        $request['causaMuerteCIE10'] = "";
      }
      
      $sql .= "       '".$request['causaMuerte']."', ";
      $sql .= "       '".$request['causaMuerteCIE10']."', ";
      $sql .= "       '".$datos_paciente['paciente_id']."', ";
      $sql .= "       '".$datos_paciente['tipo_id_paciente']."', ";
      $sql .= "       '".$request['ocupacion_id']."', ";
      $sql .= "       '".$datos_paciente['residencia_direccion']."', ";
      $sql .= "       '".$datos_paciente['residencia_telefono']."', ";
      $sql .= "       '".$datos_paciente['tipo_pais_id']."', ";
      $sql .= "       '".$datos_paciente['tipo_dpto_id']."', ";
      $sql .= "       '".$datos_paciente['tipo_mpio_id']."', ";
      $sql .= "       edad_completa('".$datos_paciente['fecha_nacimiento']."'), ";
      $sql .= "       ".$evolucion.", ";
      $sql .= "       ".UserGetUID().") ";
      
      if(!$rst = $this->ConexionTransaccion($sql))
      {
        if(!$rst = $this->ConexionTransaccion($sqlerror)) return false;
        return false;
      }
      
      $this->Commit();
      return $indice['sq'];
    }
    /**
    * Funcion donde se realiza el ingreso de la informacion de las fichas de notificacion 
    * (datos complementarios) en la tabla hc_ficha_notificacion_det, de igual manera se  
    * realiza el ingreso de los datos en las tablas con las cuales esta relacionada 
    * (det_gf_tendencias, det_gf_perinatal, det_gf_parenteral, det_gf_transmision, 
    * det_gf_tipos_prueba, det_gf_estados_clinicos, det_gf_enfermedades_asociadas)
    * @return array $indice['sq'] retorna el campo de identificacion de la tabla 
    * hc_ficha_notificacion_det
    */
    function IngresarFichasVIH($request,$datos_paciente)
    {
      $indice = array();
      
      $this->ConexionTransaccion();
      
      $sql = "SELECT NEXTVAL('hc_ficha_notificacion_det_ficha_notif_det_id_seq'::regclass) AS sq";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      if(!$rst->EOF)
      {
        $indice = $rst->GetRowAssoc($ToUpper=false);
        $rst->MoveNext();
      }
      
      $rst->Close();
      
      $sqlerror = "SELECT SETVAL('hc_ficha_notificacion_det_ficha_notif_det_id_seq', ".($indice['sq']-1).") ";
      
      $sql  = "INSERT INTO hc_ficha_notificacion_det( ";
      $sql .= "       ficha_notif_det_id, ";
      $sql .= "       fecha_resultado, ";
      $sql .= "       val_carga_viral, ";
      $sql .= "       no_hijos_menores, ";
      $sql .= "       no_hijas_menores, ";
      $sql .= "       s_embarazo, ";
      $sql .= "       no_sem_embarazo, ";
      $sql .= "       ficha_notificacion_id ";
      $sql .= ")VALUES( ";
      $sql .= "       ".$indice['sq'].", ";
      $sql .= "       '".$request['fechaResultado']."', ";
      if($request['cargaViral']!="")
        $sql .= "     ".$request['cargaViral'].", ";
      else
        $sql .= "     NULL, ";
      if($request['noHombres']!="")
        $sql .= "     ".$request['noHombres'].", ";
      else
        $sql .= "     0, ";
      if($request['noMujeres']!="")
        $sql .= "     ".$request['noMujeres'].", ";
      else
        $sql .= "     0, ";
      $sql .= "       '".$request['embarazo']."', ";
      
      if($request['noSemanas']!="" && $request['embarazo'] == "SI")
        $sql .= "     ".$request['noSemanas'].", ";
      else
        $sql .= "     NULL, ";
      $sql .= "       ".$request['cod_ficha_noti'].")";
      
      if(!$rst = $this->ConexionTransaccion($sql))
      {
        if(!$rst = $this->ConexionTransaccion($sqlerror)) return false;
        return false;
      }
      
      if($request['dTendencia']!="-1")
      { 
        $sql1  = "INSERT INTO det_gf_tendencias( ";
        $sql1 .= "       grupo_ficha_id, ";
        $sql1 .= "       tendencia_id, ";
        $sql1 .= "       ficha_notif_det_id ";
        $sql1 .= ")VALUES( ";
        $sql1 .= "       ".$request['grupo_ficha_id'].", ";
        $sql1 .= "       ".$request['dTendencia'].", ";
        $sql1 .= "       ".$indice['sq'].") ";

        if(!$rst1 = $this->ConexionTransaccion($sql1))
        {
          return false;
        }
      }
      
      if($request['dPerinatal']!="-1")
      {
        $this->ConexionTransaccion();
        
        $sql2  = "INSERT INTO det_gf_perinatal( ";
        $sql2 .= "       grupo_ficha_id, ";
        $sql2 .= "       perinatal_id, ";
        $sql2 .= "       ficha_notif_det_id ";
        $sql2 .= ")VALUES( ";
        $sql2 .= "       ".$request['grupo_ficha_id'].", "; 
        $sql2 .= "       ".$request['dPerinatal'].", ";
        $sql2 .= "       ".$indice['sq'].") ";
        
        if(!$rst2 = $this->ConexionTransaccion($sql2))
          return false;
      }
      
      if($request['dParenteral']!="-1")
      {
        $sql3  = "INSERT INTO det_gf_parenteral( ";
        $sql3 .= "       grupo_ficha_id, ";
        $sql3 .= "       parenteral_id, ";
        $sql3 .= "       ficha_notif_det_id ";
        $sql3 .= ")VALUES( ";
        $sql3 .= "       ".$request['grupo_ficha_id'].", ";
        $sql3 .= "       ".$request['dParenteral'].", ";
        $sql3 .= "       ".$indice['sq'].") ";
        
        if(!$rst3 = $this->ConexionTransaccion($sql3))
          return false;
      }
      
      if($request['dOtros']!="-1")
      {
        $sql4  = "INSERT INTO det_gf_transmision( ";
        $sql4 .= "       grupo_ficha_id, ";
        $sql4 .= "       transmision_id, ";
        $sql4 .= "       ficha_notif_det_id ";
        $sql4 .= ")VALUES( ";
        $sql4 .= "       ".$request['grupo_ficha_id'].", ";
        $sql4 .= "       ".$request['dOtros'].", ";
        $sql4 .= "       ".$indice['sq'].") ";
        
        if(!$rst4 = $this->ConexionTransaccion($sql4))
          return false;
      }
      
      if($request['dTiposPrueba']!="-1")
      {       
        $sql4  = "INSERT INTO det_gf_tipos_prueba( ";
        $sql4 .= "       grupo_ficha_id, ";
        $sql4 .= "       tipo_prueba_id, ";
        $sql4 .= "       ficha_notif_det_id ";
        $sql4 .= ")VALUES( ";
        $sql4 .= "       ".$request['grupo_ficha_id'].", ";
        $sql4 .= "       ".$request['dTiposPrueba'].", ";
        $sql4 .= "       ".$indice['sq'].") ";
        
        if(!$rst4 = $this->ConexionTransaccion($sql4))
          return false;
      }
      
      if($request['dEstadoClinico']!="-1")
      {       
        $sql5  = "INSERT INTO det_gf_estados_clinicos( ";
        $sql5 .= "       grupo_ficha_id, ";
        $sql5 .= "       estado_clinico_id, ";
        $sql5 .= "       ficha_notif_det_id ";
        $sql5 .= ")VALUES( ";
        $sql5 .= "       ".$request['grupo_ficha_id'].", ";
        $sql5 .= "       ".$request['dEstadoClinico'].", ";
        $sql5 .= "       ".$indice['sq'].") ";
        
        if(!$rst5 = $this->ConexionTransaccion($sql5))
          return false;
      }
      
      for($i=0;$i<$request['cantCheck'];$i++)
      {
        if($request['check'.$i]!="")
        {          
          $sql6  = "INSERT INTO det_gf_enfermedades_asociadas( ";
          $sql6 .= "       grupo_ficha_id, ";
          $sql6 .= "       enfermedad_id, ";
          $sql6 .= "       ficha_notif_det_id ";
          $sql6 .= ")VALUES( ";
          $sql6 .= "       ".$request['grupo_ficha_id'].", ";
          $sql6 .= "       ".$request['check'.$i].", ";
          $sql6 .= "       ".$indice['sq'].") ";
          
          if(!$rst6 = $this->ConexionTransaccion($sql6))
          {
            return false;
          }
          
          $rst6->Close();
        }
      }
      
      $sql7  = "UPDATE    ".$request['tabla']." ";
      $sql7 .= "SET       sw_ficha_llena='2' ";
      $sql7 .= "WHERE     tipo_diagnostico_id='".$request['tipo_diagnostico_id']."' AND ";
      $sql7 .= "          evolucion_id=".$request['evolucion_id']." ";
      
      if(!$rst7 = $this->ConexionTransaccion($sql7))
        return false;
      
      $sql  = "UPDATE  pacientes ";
      $sql .= "SET     sw_ficha = '1' ";
      $sql .= "WHERE   paciente_id = '".$datos_paciente['paciente_id']."' ";
      $sql .= "AND     tipo_id_paciente = '".$datos_paciente['tipo_id_paciente']."' ";      
      
      if(!$rst = $this->ConexionTransaccion($sql))
        return false;
      
      $this->Commit();
      return $indice['sq'];
    }
    /**
    *
    */
    function ObtenerDescripcionGenero($genero)
    {
      $sql  = "SELECT	descripcion ";
      $sql .= "FROM   tipo_sexo ";
      $sql .= "WHERE  sexo_id = '".$genero."' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos['descripcion'];
    }    
    /**
    *
    */
    function ObtenerDescripcionOcupacion($ocupacion)
    {
      $sql  = "SELECT	ocupacion_descripcion ";
      $sql .= "FROM   ocupaciones ";
      $sql .= "WHERE  ocupacion_id = '".$ocupacion."' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos['ocupacion_descripcion'];
    }    
    /**
    *
    */
    function ObtenerDescripcionRegimen($regimen)
    {
      $sql  = "SELECT	descripcion ";
      $sql .= "FROM   tipos_regimen_salud ";
      $sql .= "WHERE  tipo_regimen_id = ".$regimen." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos['descripcion'];
    }    
    /**
    *
    */
    function ObtenerDescripcionEtnia($etnia)
    {
      $sql  = "SELECT descripcion ";
      $sql .= "FROM   pertenencias_etnicas ";
      $sql .= "WHERE  pert_etnica_id = ".$etnia." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos['descripcion'];
    }
    /**
    *
    */
    function ObtenerDescripcionGrupoPoblacional($grupo)
    {
      $sql  = "SELECT descripcion ";
      $sql .= "FROM   grupos_poblacionales ";
      $sql .= "WHERE  grupo_poblacional_id = ".$grupo." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos['descripcion'];
    }    
    /**
    *
    */
    function ObtenerDescripcionCaso($caso)
    {
      $sql  = "SELECT descripcion ";
      $sql .= "FROM   clasificaciones_caso_sintoma ";
      $sql .= "WHERE  caso_sintoma_id = ".$caso." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos['descripcion'];
    }    
    /**
    *
    */
    function ObtenerDescripcionTipoPrueba($ficha_notif_det_id)
    {
      $sql  = "SELECT TP.descripcion ";
      $sql .= "FROM   det_gf_tipos_prueba DP, ";
      $sql .= "       tipos_prueba TP ";
      $sql .= "WHERE  DP.tipo_prueba_id =  TP.tipo_prueba_id ";
      $sql .= "AND    DP.ficha_notif_det_id = ".$ficha_notif_det_id." ";
        
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos['descripcion'];
    }    
    /**
    *
    */
    function ObtenerDescripcionEC($ficha_notif_det_id)
    {
      $sql  = "SELECT TP.descripcion ";
      $sql .= "FROM   det_gf_estados_clinicos DP, ";
      $sql .= "       estados_clinicos TP ";
      $sql .= "WHERE  DP.estado_clinico_id =  TP.estado_clinico_id ";
      $sql .= "AND    DP.ficha_notif_det_id = ".$ficha_notif_det_id." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos['descripcion'];
    }    
    /**
    *
    */
    function ObtenerEnfermedadesAsociadas($ficha_notif_det_id)
    {
      $sql  = "SELECT enfermedad_id ";
      $sql .= "FROM   det_gf_enfermedades_asociadas ";
      $sql .= "WHERE  ficha_notif_det_id = ".$ficha_notif_det_id." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    /**
    *
    */
    function ObtenerFichaNotificacion($paciente_id, $tipo_id_paciente)
    {
      $sql .= "SELECT HN.nombre_evento, ";
      $sql .= "       HN.cod_evento, ";
      $sql .= "       HN.semana, ";
      $sql .= "       HN.anyo, ";
      $sql .= "       HN.razon_social, ";
      $sql .= "       HN.codigo_sgsss, ";
      $sql .= "       HN.residencia_telefono, ";
      $sql .= "       HN.residencia_direccion, ";
      $sql .= "       HN.edad, ";
      $sql .= "       HN.proce_tipo_dpto_id, ";
      $sql .= "       HN.proce_tipo_mpio_id, ";
      $sql .= "       HN.proce_barrio, ";
      $sql .= "       HN.area_procedencia_id, ";
      $sql .= "       HN.ocupacion_id, ";
      $sql .= "       HN.tipo_regimen_id, ";
      $sql .= "       HN.pert_etnica_id, ";
      $sql .= "       HN.grupo_poblacional_id, ";
      $sql .= "       HN.nombre_admin_serv, ";
      $sql .= "       HN.cod_admin_serv, ";
      $sql .= "       HN.tipo_dpto_id, ";
      $sql .= "       HN.tipo_mpio_id, ";
      $sql .= "       HN.caso_sintoma_id, ";
      $sql .= "       HN.estado_hospi, ";
      $sql .= "       HN.condicion_final, ";
      $sql .= "       HN.causa_muerte, ";
      $sql .= "       HN.certificado_defuncion, ";
      $sql .= "       HN.codigo_cie10, ";
      $sql .= "       HN.ficha_notificacion_id, ";
      $sql .= "       TO_CHAR(HN.fecha_notificacion,'DDMMYYYY') AS fecha_notificacion, ";
      $sql .= "       TO_CHAR(HN.fecha_consulta,'DDMMYYYY') AS fecha_consulta, ";
      $sql .= "       TO_CHAR(HN.fecha_inicio_sintomas,'DDMMYYYY') AS fecha_inicio_sintomas, ";
      $sql .= "       TO_CHAR(HN.fecha_hospi,'DDMMYYYY') AS fecha_hospitalizacion, ";
      $sql .= "       TO_CHAR(HN.fecha_defuncion,'DDMMYYYY') AS fecha_defuncion, ";
      $sql .= "       D1.departamento AS departamento_notifica, ";
      $sql .= "       M1.municipio AS municipio_notifica, ";
      $sql .= "       P2.pais AS pais_procedencia, ";
      $sql .= "       D2.departamento AS departamento_procedencia, ";
      $sql .= "       M2.municipio AS municipio_procedencia, ";
      $sql .= "       D3.departamento AS departamento_residencia, ";
      $sql .= "       M3.municipio AS municipio_residencia, ";
      $sql .= "       TP.descripcion AS tipo_de_documento, ";
      $sql .= "       evolucion_id ";
      $sql .= "FROM   hc_fichas_notificacion HN, ";
      $sql .= "       tipo_dptos D1, ";
      $sql .= "       tipo_mpios M1, ";      
      $sql .= "       tipo_pais P2, ";
      $sql .= "       tipo_dptos D2, ";
      $sql .= "       tipo_mpios M2, ";      
      $sql .= "       tipo_dptos D3, ";
      $sql .= "       tipo_mpios M3, ";
      $sql .= "       tipos_id_pacientes TP ";
      $sql .= "WHERE  HN.paciente_id = '".$paciente_id."' ";
      $sql .= "AND    HN.tipo_id_paciente = '".$tipo_id_paciente."' ";
      $sql .= "AND    HN.notif_tipo_pais_id = D1.tipo_pais_id ";
      $sql .= "AND    HN.notif_tipo_dpto_id = D1.tipo_dpto_id ";      
      $sql .= "AND    HN.notif_tipo_pais_id = M1.tipo_pais_id ";
      $sql .= "AND    HN.notif_tipo_dpto_id = M1.tipo_dpto_id ";
      $sql .= "AND    HN.notif_tipo_mpio_id = M1.tipo_mpio_id ";      
      $sql .= "AND    HN.proce_tipo_pais_id = P2.tipo_pais_id ";
      $sql .= "AND    HN.proce_tipo_pais_id = D2.tipo_pais_id ";
      $sql .= "AND    HN.proce_tipo_dpto_id = D2.tipo_dpto_id ";      
      $sql .= "AND    HN.proce_tipo_pais_id = M2.tipo_pais_id ";
      $sql .= "AND    HN.proce_tipo_dpto_id = M2.tipo_dpto_id ";
      $sql .= "AND    HN.proce_tipo_mpio_id = M2.tipo_mpio_id ";
      $sql .= "AND    HN.tipo_pais_id = D3.tipo_pais_id ";
      $sql .= "AND    HN.tipo_dpto_id = D3.tipo_dpto_id ";      
      $sql .= "AND    HN.tipo_pais_id = M3.tipo_pais_id ";
      $sql .= "AND    HN.tipo_dpto_id = M3.tipo_dpto_id ";
      $sql .= "AND    HN.tipo_mpio_id = M3.tipo_mpio_id ";
      $sql .= "AND    HN.tipo_id_paciente = TP.tipo_id_paciente ";
      
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
    function ObtenerFichaVIH($ficha_notificacion_id)
    {
      $sql .= "SELECT ficha_notif_det_id, ";
      $sql .= "       TO_CHAR(fecha_resultado,'DDMMYYYY') AS fecha_resultado, ";
      $sql .= "       val_carga_viral, ";
      $sql .= "       no_hijos_menores, ";
      $sql .= "       no_hijas_menores, ";
      $sql .= "       s_embarazo, ";
      $sql .= "       no_sem_embarazo ";
      $sql .= "FROM   hc_ficha_notificacion_det ";
      $sql .= "WHERE  ficha_notificacion_id = ".$ficha_notificacion_id." ";
      
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
    function ObtenerMecanismo($ficha_notif_det_id)
    {
      $sql  = "SELECT 'sexual' AS mecanismo, ";
      $sql .= "       descripcion ";
      $sql .= "FROM   tendencias_sexuales TD, ";
      $sql .= "       det_gf_tendencias DT ";
      $sql .= "WHERE  TD.tendencia_id = DT.tendencia_id ";
      $sql .= "AND    DT.ficha_notif_det_id = ".$ficha_notif_det_id." ";
      $sql .= "UNION ALL ";
      $sql .= "SELECT 'perinatal' AS mecanismo, ";
      $sql .= "       descripcion ";
      $sql .= "FROM   perinatal TD, ";
      $sql .= "       det_gf_perinatal DT ";
      $sql .= "WHERE  TD.perinatal_id = DT.perinatal_id ";
      $sql .= "AND    DT.ficha_notif_det_id = ".$ficha_notif_det_id." ";
      $sql .= "UNION ALL ";
      $sql .= "SELECT 'parenteral' AS mecanismo, ";
      $sql .= "       descripcion ";
      $sql .= "FROM   parenteral TD, ";
      $sql .= "       det_gf_parenteral DT ";
      $sql .= "WHERE  TD.parenteral_id = DT.parenteral_id ";
      $sql .= "AND    DT.ficha_notif_det_id = ".$ficha_notif_det_id." ";
      $sql .= "UNION ALL ";
      $sql .= "SELECT 'otros' AS mecanismo, ";
      $sql .= "       descripcion ";
      $sql .= "FROM   transmision_otros TD, ";
      $sql .= "       det_gf_transmision DT ";
      $sql .= "WHERE  TD.transmision_id = DT.transmision_id ";
      $sql .= "AND    DT.ficha_notif_det_id = ".$ficha_notif_det_id." ";
       
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
  }
 ?>