<?php
  class RiesgoFamiliarSQL extends ConexionBD
  {
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
    * Funcion que consulta la informacion del detalle los riesgos familiares de un paciente
    * @return mixed $datos, contiene los datos de la consulta realizada
    */
    function ConsultarRFDetalle($request)
    {
      $sql  = "SELECT   * ";
      $whr .= "FROM     hc_rf_pacientes as rp, hc_rf_pacientes_d as rpd "; 
      $whr .= "WHERE    rp.rf_paciente_id = rpd.rf_paciente_id ";
      $whr .= "         AND rp.rf_paciente_id = ".$request['rf_paciente_id']." ";
      $whr .= "         AND rp.paciente_id = ".$request['paciente_id']."; ";
            
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
    * Funcion que ingresa para un paciente la informacion de los riesgos familiares y su detalle respectivo
    * @param mixed $request, contiene los valores que ingreso el usuario
    * @param mixed $datos_paciente, contiene los datos del paciente
    * @param mixed $usuario_id, contiene la informacion del usuario de la aplicacion
    * @param mixed $evolucion, contiene el valor de la evolucion
    * @return int $indice['sq'], es el identificador que se va a ingresar de la tabla hc_rf_pacientes 
    */
    function IngresarRFPaciente($request, $datos_paciente, $usuario_id, $evolucion)
    {   
      //$this->debug = true;    
      $indice = array();
      
      $this->ConexionTransaccion();
      
      $sql = "SELECT NEXTVAL('hc_rf_pacientes_rf_pacientes_id_seq'::regclass) AS sq ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      if(!$rst->EOF)
      {
        $indice = $rst->GetRowAssoc($ToUpper=false);
        $rst->MoveNext();
      }
      
      $rst->Close();
      
      $sqlerror = "SELECT setval('hc_rf_pacientes_rf_pacientes_id_seq', ".($indice['sq']-1).") ";
      
      $sql  = "INSERT INTO hc_rf_pacientes( ";
      $sql .= "       rf_paciente_id, ";
      $sql .= "       fecha_calificacion, ";
      $sql .= "       fecha_registro, ";
      $sql .= "       calificacion_total, ";
      $sql .= "       responsable, ";
      $sql .= "       paciente_id, ";
      $sql .= "       tipo_id_paciente, ";
      $sql .= "       usuario_id, ";
      $sql .= "       evolucion_id) ";      
      $sql .= "VALUES( ";
      $sql .= "       ".$indice['sq'].", ";
      $sql .= "       '".$request['fechaCalificacion']."', ";
      $sql .= "       NOW(), ";
      $sql .= "       ".$request['total_oculto'].", ";      
      $sql .= "       '".$request['responsable']."', ";  
      $sql .= "       '".$datos_paciente['paciente_id']."', ";    
      $sql .= "       '".$datos_paciente['tipo_id_paciente']."', "; 
      $sql .= "       ".UserGetUID().", ";  
      $sql .= "       ".$evolucion.");";
      $fk = $indice['sq'];
      if(!$rst = $this->ConexionTransaccion($sql))
      {
        if(!$rst = $this->ConexionTransaccion($sqlerror)) return false;
        return false;
      }
      //---------------- 
           
      $this->ConexionTransaccion();
      
      foreach($request['calificacion'] as $grupo_id => $val_grupo)
      {
        foreach($val_grupo as $componente_id => $val_componente)
        {
          $indice2 = array();
          $sql2 = "SELECT NEXTVAL('hc_rf_pacientes_d_rf_paciente_d_id_seq'::regclass) AS sq2 ";
      
          if(!$rst2 = $this->ConexionBaseDatos($sql2)) return false;
          
          if(!$rst2->EOF) 
          {
            $indice2 = $rst2->GetRowAssoc($ToUpper=false);
            $rst2->MoveNext();
          }
          
          $rst2->Close();
          
          $sqlerror2 = "SELECT setval('hc_rf_pacientes_d_rf_paciente_d_id_seq', ".($indice2['sq2']-1).") ";
          
          $sql2  = "INSERT INTO hc_rf_pacientes_d( ";
          $sql2 .= "       rf_paciente_d_id, ";
          $sql2 .= "       calificacion, ";
          $sql2 .= "       rf_paciente_id, ";
          $sql2 .= "       grupo_riesgo_id, ";
          $sql2 .= "       comp_riesgo_id) ";
          $sql2 .= "VALUES( ";
          $sql2 .= "       ".$indice2['sq2'].", ";
          $sql2 .= "       ".$val_componente.", ";
          $sql2 .= "       ".$fk.", ";
          $sql2 .= "       '".$grupo_id."', ";
          $sql2 .= "       ".$componente_id."); "; 
          if(!$rst2 = $this->ConexionTransaccion($sql2))
          {
            if(!$rst2 = $this->ConexionTransaccion($sqlerror2)) return false;
            return false;
          }
        }
      }
                 
      $this->Commit();
      
      return $indice['sq'];
    }
    /**
    * Funcion que ingresa para un paciente la informacion del detalle de la calificacion de los riesgos familiares
    * @param mixed $request, contiene los valores que ingreso el usuario
    * @return int $indice['sq'], es el identificador que se va a ingresar de la tabla hc_rf_pacientes 
    */
    function IngresarRFPacienteD($request)
    {
      $indice = array();
      $sql = "SELECT NEXTVAL('hc_rf_pacientes_rf_pacientes_id_seq'::regclass) AS sq ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      if(!$rst->EOF)
      {
        $indice = $rst->GetRowAssoc($ToUpper=false);
        $rst->MoveNext();
      }
      
      $rst->Close();
      
      $sqlerror = "SELECT setval('hc_rf_pacientes_rf_pacientes_id_seq', ".($indice['sq']-1).") ";
      
      $this->ConexionTransaccion();
      
      $sql  = "INSERT INTO hc_fr_pacientes_d( ";
      $sql .= "       rf_paciente_d_id, ";
      $sql .= "       calificacion, ";
      $sql .= "       rf_paciente_id, ";
      $sql .= "       grupo_riesgo_id, ";
      $sql .= "       comp_riesgo_id) ";
      $sql .= "VALUES( ";
      $sql .= "       ".$indice['sq'].", ";
      $sql .= "       ".$request['calificacion'].", ";
      $sql .= "       ".$rf_paciente_id.", ";
      $sql .= "       '".$grupo_riesgo_id."', ";
      $sql .= "       ".$comp_riesgo_id."); "; 
      
      if(!$rst = $this->ConexionTransaccion($sql))
      {
        if(!$rst = $this->ConexionTransaccion($sqlerror)) return false;
        return false;
      }
            
      $this->Commit();
      
      return $indice['sq'];
    }
    
    function ConsultarCalificacionRF($request)
    {
      //$this->debug = true;
      $sql  = "SELECT    gr.grupo_riesgo_id, gr.descripcion as descgrup, ";
      $sql .= "          cr.comp_riesgo_id,  cr.descripcion as desccomp, rp.responsable, ";
      $sql .= "          rp.calificacion_total, rp.fecha_calificacion, rpd.calificacion ";
      $whr  = "FROM      grupos_riesgos gr, componentes_riesgos cr, hc_rf_pacientes rp, "; $whr .= "          hc_rf_pacientes_d rpd ";
      $whr .= "WHERE     rp.rf_paciente_id = rpd.rf_paciente_id ";
      $whr .= "          AND rp.rf_paciente_id = ".$request['rf_paciente_id']." ";
      $whr .= "          AND rpd.comp_riesgo_id = cr.comp_riesgo_id ";
      $whr .= "          AND rpd.grupo_riesgo_id = cr.grupo_riesgo_id ";
      $whr .= "          AND cr.grupo_riesgo_id = gr.grupo_riesgo_id ";
      $whr .= "ORDER BY  gr.grupo_riesgo_id, gr.descripcion, cr.comp_riesgo_id, "; 
      $whr .= "          cr.descripcion, rp.responsable, rp.calificacion_total, "; 
      $whr .= "          rp.fecha_calificacion, rpd.calificacion;";
      
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
