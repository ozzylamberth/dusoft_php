<?php
  class EvolucionRiesgoFamiliarSQL extends ConexionBD
  { 
     /**
    * Funcion que consulta la informacion de los riesgos familiares de un paciente
    * @return mixed $datos, contiene los datos de la consulta realizada
    */
    function ConsultarRFPaciente($datos_paciente, $pg_siguiente)
    {
      //$this->debug=true;
      $sql  = "SELECT   fecha_registro, rf_paciente_id, fecha_calificacion, "; 
      $sql .= "         calificacion_total, responsable, paciente_id, tipo_id_paciente ";
      $whr  = "FROM     hc_rf_pacientes ";
      $whr .= "WHERE    paciente_id = ".$datos_paciente['paciente_id']." AND ";
      $whr .= "         tipo_id_paciente = '".$datos_paciente['tipo_id_paciente']."' ";
      $whr .= "ORDER BY fecha_registro, rf_paciente_id, fecha_calificacion, ";
      $whr .= "         calificacion_total, responsable, paciente_id, tipo_id_paciente ";
      
      $whr1  = "FROM     hc_rf_pacientes ";
      $whr1 .= "WHERE    paciente_id = ".$datos_paciente['paciente_id']." AND ";
      $whr1 .= "         tipo_id_paciente = '".$datos_paciente['tipo_id_paciente']."' "; 
      
      if(!$this->ProcesarSqlConteo("SELECT COUNT(*) $whr1",$pg_siguiente,null,20))
        return false;
      
      $whr .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql.$whr)) return false;
      
      $datos = array();
      while (!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    
    /**
    * Funcion que agrupa y cuenta los datos existentes para el campo grupo_riesgo_id
    * @return mixed $datos, contiene los datos de la consulta realizada
    */
    function ContarComponentes()
    {
      //$this->debug = true;   
      $sql  = "SELECT     count(gr.grupo_riesgo_id) as cantidad, gr.grupo_riesgo_id, gr.descripcion as desc_grup ";
      $whr  = "FROM       componentes_riesgos as cr, grupos_riesgos as gr "; 
      $whr .= "WHERE      cr.grupo_riesgo_id = gr.grupo_riesgo_id ";
      $whr .= "GROUP BY   gr.grupo_riesgo_id, gr.descripcion ";
      $whr .= "ORDER BY   gr.grupo_riesgo_id, gr.descripcion";
      
      if(!$rst = $this->ConexionBaseDatos($sql.$whr)) return false;
      
      $datos = array();
      while (!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    
    /**
    * Funcion que consulta la informacion de los componentes de riesgos para los
    * grupos de riesgos 
    * @return mixed $datos, contiene los datos de la consulta realizada
    */
    function ConsultarRiesgosGC()
    {
      $sql  = "SELECT   gr.grupo_riesgo_id, gr.descripcion as desc_grup, ";         
      $sql .= "         cr.comp_riesgo_id, cr.descripcion as desc_comp, cr.estado "; 
      $whr  = "FROM     componentes_riesgos as cr, grupos_riesgos as gr ";
      $whr .= "WHERE    cr.grupo_riesgo_id = gr.grupo_riesgo_id ";
      $whr .= "GROUP BY gr.grupo_riesgo_id, gr.descripcion, cr.comp_riesgo_id, ";
      $whr .= "         cr.descripcion, cr.estado ";
      $whr .= "ORDER BY gr.grupo_riesgo_id, gr.descripcion, cr.comp_riesgo_id, "; 
      $whr .= "         cr.descripcion, cr.estado;";
      
      if(!$rst = $this->ConexionBaseDatos($sql.$whr)) return false;
      
      $datos = array();
      while (!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    
    /**
    * Funcion que consulta la informacion de los grupos de riesgos 
    * @return mixed $datos, contiene los datos de la consulta realizada
    */
    function ConsultarGruposRiesgos()
    {
      $sql  = "SELECT   gr.grupo_riesgo_id, gr.descripcion ";         
      $whr  = "FROM     grupos_riesgos as gr ";
      $whr .= "GROUP BY gr.grupo_riesgo_id, gr.descripcion ";
      $whr .= "ORDER BY gr.grupo_riesgo_id, gr.descripcion "; 
      
      if(!$rst = $this->ConexionBaseDatos($sql.$whr)) return false;
      
      $datos = array();
      while (!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    
    /**
    * Funcion que consulta la informacion del detalle los riesgos familiares de un paciente
    * @return mixed $datos, contiene los datos de la consulta realizada
    */
    function ConsultarRFDetalle($request, $datos_paciente)
    {
      $sql  = "SELECT   * ";
      $whr  = "FROM     hc_rf_pacientes as rp, hc_rf_pacientes_d as rpd "; 
      $whr .= "WHERE    rp.rf_paciente_id = rpd.rf_paciente_id ";
      $whr .= "         AND rp.rf_paciente_id = ".$request['rf_paciente_id']." ";
      $whr .= "         AND rp.paciente_id = ".$request['paciente_id']." ";
      $whr .= "         AND rp.tipo_id_paciente = '".$datos_paciente['tipo_id_paciente']."';";
      if(!$rst = $this->ConexionBaseDatos($sql.$whr)) return false;
      
      $datos = array();
      while (!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion que consulta la informacion de las actividades asignadas a un paciente
    * @return mixed $datos, contiene los datos de la consulta realizada
    */
    function ConsultarActPaciente($request)
    {
      //$this->debug = true;   
      $sql  = "SELECT   act_paciente_id, fecha_analisis, compromiso_familia, ";
      $sql .= "         compromiso_equipo ";
      $whr  = "FROM     rf_actividades_pacientes ";
      $whr .= "WHERE    rf_paciente_d_id = ".$request['rf_paciente_d_id']." ";
      $whr .= "         AND rf_paciente_id = ".$request['rf_paciente_id'].";";
            
      if(!$rst = $this->ConexionBaseDatos($sql.$whr)) return false;
      
      $datos = array();
      while (!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion que ingresa para un paciente la informacion de las actividades asignadas
    * @param mixed $request, contiene los valores que ingreso el usuario
    * @return int $indice['sq'], es el identificador que se va a ingresar a la tabla rf_actividades_pacientes 
    */
    function IngresoActividadesPaciente($request)
    { 
      //$this->debug = true;   
      $indice = array();
      
      $this->ConexionTransaccion();
      
      $sql = "SELECT NEXTVAL('rf_actividades_pacientes_act_paciente_id_seq'::regclass) AS sq ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      if(!$rst->EOF)
      {
        $indice = $rst->GetRowAssoc($ToUpper=false);
        $rst->MoveNext();
      }
      
      $rst->Close();
      
      $sqlerror = "SELECT setval('rf_actividades_pacientes_act_paciente_id_seq', ".($indice['sq']-1).") ";
      
      $sql  = "INSERT INTO rf_actividades_pacientes( ";
      $sql .= "       act_paciente_id, ";
      $sql .= "       fecha_analisis, ";
      $sql .= "       compromiso_familia, ";
      $sql .= "       compromiso_equipo, ";
      $sql .= "       rf_paciente_d_id, "; 
      $sql .= "       rf_paciente_id) ";
      $sql .= "VALUES( ";
      $sql .= "       ".$indice['sq'].", ";
      $sql .= "       '".$request['fechaAnalisis']."', ";
      $sql .= "       '".$request['compromisoFamilia']."', ";
      $sql .= "       '".$request['compromisoEquipo']."', ";
      $sql .= "       ".$request['rf_paciente_d_id'].", ";
      $sql .= "       ".$request['rf_paciente_id'].");";
      
      if(!$rst = $this->ConexionTransaccion($sql))
      {
        if(!$rst = $this->ConexionTransaccion($sqlerror)) return false;
        return false;
      }
      
      $this->Commit();
      
      return $indice['sq'];
    }
    /**
    * Funcion que consulta la informacion de las evaluaciones realizadas a las 
    * actividades asignadas a un paciente
    * @return mixed $datos, contiene los datos de la consulta realizada
    */
    function ConsultarEvalPaciente($datos, $pg_siguiente)
    {
      $sql  = "SELECT   fecha_evaluacion, cumplimiento, causa_observacion ";
      $whr  = "FROM     rf_evaluacion_cumplimientos ";
      $whr .= "WHERE    act_paciente_id = ".$datos[0]['act_paciente_id']." ";
      
      if(!$this->ProcesarSqlConteo("SELECT COUNT(*) $whr",$pg_siguiente,null,20))
        return false;
      
      $whr .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
                  
      if(!$rst = $this->ConexionBaseDatos($sql.$whr)) return false;
      
      $datos = array();
      while (!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion que ingresa para un paciente la informacion de las evaluaciones realizadas a las actividades
    * @param mixed $request, contiene los valores que ingreso el usuario
    * @param mixed $usuario_id, contiene la identificacion del usuario
    * @param mixed $datos, contiene el valor de identificacion de la actividad asociada
    * @return int $indice['sq'], es el identificador que se va a ingresar a la tabla rf_evaluacion_cumplimientos 
    */
    function IngresoEvaluacionCumplimiento($request, $usuario_id, $datos)
    {
      //$this->debug = true;   
      $indice = array();
      
      $this->ConexionTransaccion();
      
      $sql = "SELECT NEXTVAL('rf_evaluacion_cumplimientos_eval_cumplimiento_id_seq'::regclass) AS sq ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      if(!$rst->EOF)
      {
        $indice = $rst->GetRowAssoc($ToUpper=false);
        $rst->MoveNext();
      }
      
      $rst->Close();
      
      $sqlerror = "SELECT setval('rf_evaluacion_cumplimientos_eval_cumplimiento_id_seq', ".($indice['sq']-1).") ";
      
      $sql  = "INSERT INTO rf_evaluacion_cumplimientos( ";
      $sql .= "       eval_cumplimiento_id, ";
      $sql .= "       fecha_evaluacion, ";
      $sql .= "       cumplimiento, ";
      $sql .= "       causa_observacion, ";
      $sql .= "       usuario_id, ";
      $sql .= "       act_paciente_id)";
      $sql .= "VALUES( ";
      $sql .= "       ".$indice['sq'].", ";
      $sql .= "       '".$request['fechaEvaluacion']."', ";
      $sql .= "       '".$request['cumplimiento']."', ";
      $sql .= "       '".$request['causaIncumplimiento']."', ";
      $sql .= "       ".$usuario_id.", ";
      $sql .= "       ".$datos[0]['act_paciente_id'].");";

      if(!$rst = $this->ConexionTransaccion($sql))
      {
        if(!$rst = $this->ConexionTransaccion($sqlerror)) return false;
        return false;
      }
      
      $this->Commit();
      
      return $indice['sq'];
    }
    /**
    * Funcion que consulta la informacion del detalle de la calificacion de los riesgos 
    * para un paciente
    * @return mixed $datos, contiene los datos de la consulta realizada
    */
    function ConsultarCalificacionRF($request)
    {
      //$this->debug = true;
      $sql  = "SELECT    gr.grupo_riesgo_id, gr.descripcion as descgrup, ";
      $sql .= "          cr.comp_riesgo_id,  cr.descripcion as desccomp, rp.responsable, ";
      $sql .= "          rp.calificacion_total, rp.fecha_calificacion, rpd.calificacion, ";
      $sql .= "          rp.rf_paciente_id, rpd.rf_paciente_d_id ";
      $whr  = "FROM      grupos_riesgos gr, componentes_riesgos cr, hc_rf_pacientes rp, "; $whr .= "          hc_rf_pacientes_d rpd ";
      $whr .= "WHERE     rp.rf_paciente_id = rpd.rf_paciente_id ";
      $whr .= "          AND rp.rf_paciente_id = ".$request['rf_paciente_id']." ";
      $whr .= "          AND rpd.comp_riesgo_id = cr.comp_riesgo_id ";
      $whr .= "          AND rpd.grupo_riesgo_id = cr.grupo_riesgo_id ";
      $whr .= "          AND cr.grupo_riesgo_id = gr.grupo_riesgo_id ";
      $whr .= "ORDER BY  gr.grupo_riesgo_id, gr.descripcion, cr.comp_riesgo_id, "; 
      $whr .= "          cr.descripcion, rp.responsable, rp.calificacion_total, "; 
      $whr .= "          rp.fecha_calificacion, rpd.calificacion, rp.rf_paciente_id, ";
      $whr .= "          rpd.rf_paciente_d_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql.$whr)) return false;
      
      $datos = array();
      while (!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion que agrupa y cuenta la cantidad de componentes de riesgo por grupos de riesgo
    * @return mixed $datos, contiene los datos de la consulta realizada
    */
    function ConsultarCantGR($request)
    {
      //$this->debug = true;
      $sql  = "SELECT    gr.grupo_riesgo_id, count(*) as cant ";
      $whr  = "FROM      grupos_riesgos gr, componentes_riesgos cr, hc_rf_pacientes rp, "; $whr .= "          hc_rf_pacientes_d rpd ";
      $whr .= "WHERE     rp.rf_paciente_id = rpd.rf_paciente_id ";
      $whr .= "          AND rp.rf_paciente_id = ".$request['rf_paciente_id']." ";
      $whr .= "          AND rpd.comp_riesgo_id = cr.comp_riesgo_id ";
      $whr .= "          AND rpd.grupo_riesgo_id = cr.grupo_riesgo_id ";
      $whr .= "          AND cr.grupo_riesgo_id = gr.grupo_riesgo_id ";
      $whr .= "GROUP BY  gr.grupo_riesgo_id ";
      $whr .= "ORDER BY  gr.grupo_riesgo_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql.$whr)) return false;
      
      $datos = array();
      while (!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
  }
?> 
