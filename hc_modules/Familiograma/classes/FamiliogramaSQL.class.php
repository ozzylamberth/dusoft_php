<?php 
  class FamiliogramaSQL extends ConexionBD
  {
    /**
    * Constructor de la clase
    */
    function FamiliogramaSQL() {}
    /**
    * Funcion para consultar los datos de la tabla tipos_persona
    * @return array $datos vector que contiene la informacion de la consulta
    */
    function ConsultarTiposPersona()
    {
      //$this->debug=true;
      $sql  = "SELECT     tipo_persona_id, descripcion ";
      $sql .= "FROM       tipos_persona ";
      
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
    * Funcion para consultar las simbologias deacuerdo al tipo de persona y el sexo 
    * seleccionado
    * @param array $form vector con la informacion para realizar la consulta
    * @return array $datos vector que contiene la informacion de la consulta
    */
    function ConsultarSimbologias($form)
    {
      //$this->debug=true;
      
      $sql  = "SELECT     s.simbolo, ds.simbologia_id, ds.tipo_persona_id, tp.descripcion ";
      $sql .= "FROM       simbologias s, det_simbologias ds, tipos_persona tp ";
      $sql .= "WHERE      s.simbologia_id = ds.simbologia_id AND ";
      $sql .= "           tp.tipo_persona_id = ds.tipo_persona_id AND ";
      $sql .= "           tp.tipo_persona_id = ".$form['tiposPersona']." AND ";
      $sql .= "           (s.sexo = '".$form['sexo']."' OR s.sexo = 'A') ";
      
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
    * Funcion para consultar las abreviaturas de acuerdo al tipo de persona y al sexo 
    * seleccionado 
    * @param array $form vector con la informacion para realizar la consulta
    * @return array $datos vector que contiene la informacion de la consulta
    */
    function ConsultarAbreviaturas($form)
    {
      //$this->debug = true;
    
      $sql  = "SELECT     a.abreviatura, a.descripcion as desc_abreviatura, ";
      $sql .= "           da.abreviatura_id, da.tipo_persona_id, ";
      $sql .= "           tp.descripcion as desc_tipo_persona ";
      $sql .= "FROM       abreviaturas a, det_abreviaturas da, tipos_persona tp ";
      $sql .= "WHERE      a.abreviatura_id = da.abreviatura_id AND ";
      $sql .= "           tp.tipo_persona_id = da.tipo_persona_id AND ";
      $sql .= "           tp.tipo_persona_id = ".$form['tiposPersona']." AND ";
      $sql .= "           (a.sexo = '".$form['sexo']."' OR a.sexo = 'A') ";
      
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
    * Funcion donde se realiza el ingreso de los datos correspondientes al familiograma en 
    * la tabla (hc_familiograma) y los datos correspondientes a las tablas asociadas 
    * (hc_familiograma_simbolos, hc_familiograma_abreviaturas)
    * @param array $request vector que contiene la informacion del request
    * @param array $datos_paciente vector que contiene la informacion del paciente
    * @param string $evolucion cadena que contiene el valor de la evolucion
    * @return array $indice['sq'] retorna el campo que identifica a la tabla hc_familiograma
    */
    function IngresarFamiliograma($request, $datos_paciente, $evolucion)
    {
      //$this->debug=true;
      
      $indice = array();
      
      $this->ConexionTransaccion();
      
      $sql = "SELECT NEXTVAL('hc_familiograma_familiograma_id_seq'::regclass) AS sq ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      if(!$rst->EOF)
      {
        $indice = $rst->GetRowAssoc($ToUpper=false);
        $rst->MoveNext();
      }
      
      $rst->Close();
      
      $sqlerror = "SELECT setval('hc_familiograma_familiograma_id_seq', ".($indice['sq']-1).") ";
      
      $sql  = "INSERT INTO hc_familiograma( ";
      $sql .= "       familiograma_id, ";
      $sql .= "       sexo, ";
      $sql .= "       fecha_registro, ";
      $sql .= "       tipo_persona_id, ";
      $sql .= "       evolucion_id, ";
      $sql .= "       paciente_id, ";
      $sql .= "       tipo_id_paciente, ";
      $sql .= "       usuario_id ";
      $sql .= ")VALUES( ";
      $sql .= "       ".$indice['sq'].", ";
      $sql .= "       '".$request['sexo']."', ";
      $sql .= "       NOW(), ";
      $sql .= "       ".$request['tiposPersona'].", ";
      $sql .= "       ".$evolucion.", ";
      $sql .= "       '".$datos_paciente['paciente_id']."', ";
      $sql .= "       '".$datos_paciente['tipo_id_paciente']."',";
      $sql .= "       ".UserGetUID().") ";
      
      if(!$rst = $this->ConexionTransaccion($sql))
      {
        if(!$rst = $this->ConexionTransaccion($sqlerror)) return false;
        return false;
      }
      
      //----------------------------
            
      for($i=0;$i<$request['cont_simb'];$i++)
      {
        if($request['checks'.$i]!="")
        {
          $this->ConexionTransaccion();
        
          $sql1  = "INSERT INTO hc_familiograma_simbolos( ";
          $sql1 .= "       familiograma_id, ";
          $sql1 .= "       simbologia_id, ";
          $sql1 .= "       tipo_persona_id ";
          $sql1 .= ")VALUES( ";
          $sql1 .= "       ".$indice['sq'].", ";
          $sql1 .= "       ".$request['checks'.$i].", ";
          $sql1 .= "       ".$request['tiposPersona'].") ";
          
          if(!$rst1 = $this->ConexionTransaccion($sql1))
          {
            return false;
          }
        }       
      }
      
      //----------------------------
      
      for($j=0;$j<$request['cont_abre'];$j++)
      {
        if($request['checka'.$j]!="")
        {
          $this->ConexionTransaccion();
        
          $sql2  = "INSERT INTO hc_familiograma_abreviaturas( ";
          $sql2 .= "       familiograma_id, ";
          $sql2 .= "       abreviatura_id, ";
          $sql2 .= "       tipo_persona_id ";
          $sql2 .= ")VALUES( ";
          $sql2 .= "       ".$indice['sq'].", ";
          $sql2 .= "       ".$request['checka'.$j].", ";
          $sql2 .= "       ".$request['tiposPersona'].") ";
          
          if(!$rst2 = $this->ConexionTransaccion($sql2))
          {
            return false;
          }
        }
      }
      
      $this->Commit();
      return $indice['sq'];
    }
    /**
    * Funcion donde se consulta el familiograma de un paciente
    * @param string $pg_siguiente
    * @param array $datos_paciente vector con la informacion del paciente
    * @return array $datos vector que contiene la informacion de la consulta
    */
    function ConsultarFamiliograma($pg_siguiente, $datos_paciente)
    {
      //$this->debug=true;
      
      $sql  = "SELECT     f.fecha_registro, f.familiograma_id, f.sexo, f.tipo_persona_id, ";
      $sql .= "           f.paciente_id, f.tipo_id_paciente, f.usuario_id, ";
      $sql .= "           f.evolucion_id, tp.descripcion ";
      $whr  = "FROM       hc_familiograma f, tipos_persona tp ";
      $whr .= "WHERE      f.tipo_persona_id=tp.tipo_persona_id AND ";
      $whr .= "           paciente_id='".$datos_paciente['paciente_id']."' AND ";
      $whr .= "           tipo_id_paciente='".$datos_paciente['tipo_id_paciente']."' ";
      $ord  = "ORDER BY   f.fecha_registro, f.familiograma_id, f.sexo, f.tipo_persona_id, ";
      $ord .= "           f.paciente_id, f.tipo_id_paciente, f.usuario_id, ";
      $ord .= "           f.evolucion_id, tp.descripcion ";
      
      if(!$this->ProcesarSqlConteo("SELECT COUNT(*) $whr",$pg_siguiente,null,50))
        return false;
      
      $ord .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
        
      if(!$rst = $this->ConexionBaseDatos($sql.$whr.$ord)) return false;
      
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
    * Funcion donde se consulta el detalle de los simbolos del familiograma para un paciente
    * @param array $request vector que contiene la informacion del request
    * @return array $datos vector que contiene la informacion de la consulta
    */
    function DetalleFamiliogramaSimb($request)
    {
      //$this->debug=true;
      
      $sql  = "SELECT     fs.familiograma_id, fs.simbologia_id, s.simbolo ";
      $sql .= "FROM       hc_familiograma_simbolos fs, hc_familiograma f, ";
      $sql .= "           det_simbologias ds, simbologias s, tipos_persona tp ";
      $sql .= "WHERE      f.familiograma_id=fs.familiograma_id AND ";
      $sql .= "           ds.simbologia_id=fs.simbologia_id AND ";
      $sql .= "           ds.tipo_persona_id=fs.tipo_persona_id AND";
      $sql .= "           s.simbologia_id=ds.simbologia_id AND ";
      $sql .= "           tp.tipo_persona_id=ds.tipo_persona_id AND ";
      $sql .= "           fs.familiograma_id=".$request['familiograma_id']." ";
            
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
    * Funcion donde se consulta el detalle de las abreviaturas del familiograma para un 
    * paciente
    * @param array $request vector que contiene la informacion del request
    * @return array $datos vector que contiene la informacion de la consulta
    */
    function DetalleFamiliogramaAbre($request)
    {
      //$this->debug=true;
      
      $sql  = "SELECT     fa.familiograma_id, fa.abreviatura_id, a.descripcion ";
      $sql .= "FROM       hc_familiograma f, hc_familiograma_abreviaturas fa, ";
      $sql .= "           det_abreviaturas da, abreviaturas a, tipos_persona tp ";
      $sql .= "WHERE      f.familiograma_id=fa.familiograma_id AND ";
      $sql .= "           da.abreviatura_id=fa.abreviatura_id AND ";
      $sql .= "           da.tipo_persona_id=fa.tipo_persona_id AND ";
      $sql .= "           a.abreviatura_id=da.abreviatura_id AND ";
      $sql .= "           tp.tipo_persona_id=da.tipo_persona_id AND ";
      $sql .= "           fa.familiograma_id=".$request['familiograma_id']." ";
      
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
  }
 ?>