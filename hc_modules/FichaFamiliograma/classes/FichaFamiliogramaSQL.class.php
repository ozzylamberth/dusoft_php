<?php
  class FichaFamiliogramaSQL extends ConexionBD
  {
    function FichaFamiliogramaSQL() {}
    /**
    * Funcion que consulta todos los campos de la tabla hc_registros_contaminacion
    * @param mixed $pg_siguiente, contiene el valor de la pagina
    * @return mixed $datos, contiene los datos de la consulta realizada
    */
    function ConsultarContaminacion($pg_siguiente, $datos_paciente)
    {
      $sql  = "SELECT   * ";
      $whr  = "FROM     hc_registros_contaminacion ";
      $whr .= "WHERE    paciente_id='".$datos_paciente['paciente_id']."'";
      
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
    * Funcion que ingresa la contaminacion relacionada a un paciente
    * @param mixed $request, contiene los valores que ingreso el usuario
    * @param mixed $datos_paciente, contiene los datos del paciente
    * @return int $indice['sq'], es el identificador de la contaminacion que se va a insertar
    */
    function IngresarContaminacion($request, $datos_paciente, $evolucion){
      //$this->debug = true;
      
      $indice = array();
      $sql = "SELECT NEXTVAL('hc_registros_contaminacion_contaminante_id_seq'::regclass) AS sq ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      if(!$rst->EOF)
      {
        $indice = $rst->GetRowAssoc($ToUpper=false);
        $rst->MoveNext();
      }
      
      $rst->Close();
      
      $sqlerror = "SELECT setval('hc_registros_contaminacion_contaminante_id_seq', ".($indice['sq']-1).") ";
      
      $this->ConexionTransaccion();
      
      $sql  = "INSERT INTO hc_registros_contaminacion(";
      $sql .= "   contaminante_id, ";
      $sql .= "   fecha_informe, ";
      $sql .= "   tipo_contaminante, ";
      $sql .= "   descripcion, ";
      $sql .= "   causa, ";
      $sql .= "   tratamiento,";
      $sql .= "   fecha_registro,";
      $sql .= "   paciente_id,";
      $sql .= "   tipo_id_paciente,";
      $sql .= "   evolucion_id,";
      $sql .= "   usuario_id) ";
      $sql .= "VALUES(";
      $sql .= "   ".$indice['sq'].", ";
      $sql .= "   '".$request['fechaIngreso']."', ";
      $sql .= "   '".$request['tipoContaminante']."', ";
      $sql .= "   '".$request['descContaminante']."', ";
      $sql .= "   '".$request['causanteContaminacion']."', ";
      $sql .= "   '".$request['tratamiento']."', ";
      $sql .= "   NOW(), ";
      $sql .= "   '".$datos_paciente['paciente_id']."', ";
      $sql .= "   '".$datos_paciente['tipo_id_paciente']."', ";
      $sql .= "   ".$evolucion.", ";
      $sql .= "   ".UserGetUID().");";
      
      if(!$rst = $this->ConexionTransaccion($sql))
      {
        if(!$rst = $this->ConexionTransaccion($sqlerror)) return false;
        return false;
      }
            
      $this->Commit();
      
      return $indice['sq'];
    }
    
    /**
    * Funcion que consulta todos los campos de la tabla hc_registros_contaminacion 
    * para un paciente indicando el contaminante_id
    * @param mixed $request, los datos a consultar
    * @return mixed $datos, contiene los datos de la consulta realizada
    */
    function ConsultarContaminante($request)
    {
      //$this->debug = true;
      
      $sql  = "SELECT   * ";
      $whr  = "FROM     hc_registros_contaminacion ";
      $whr .= "WHERE    paciente_id='".$request['paciente_id']."' ";
      $whr .= "         AND contaminante_id=".$request['contaminante_id'].";";
      
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
    * Funcion que permite actualizar algunos campos de la tabla hc_registros_contaminacion 
    * @param mixed $datos, contiene la informacion que se va a actualizar
    * @return boolean
    */
    
    function ModificarContaminacion($datos)
    {
      $this->ConexionTransaccion();
     
      $sql .= "UPDATE hc_registros_contaminacion SET 
                    fecha_informe = '".$datos['fechaIngreso']."',
                    tipo_contaminante = '".$datos['tipoContaminante']."', 
                    descripcion = '".$datos['descContaminante']."',
                    causa = '".$datos['causanteContaminacion']."',
                    tratamiento = '".$datos['tratamiento']."',
                    fecha_registro = NOW(),
                    usuario_id = ".UserGetUID()."
                    WHERE contaminante_id = ".$datos['contaminante_id'].";";
      
      if (!$rst = $this->ConexionTransaccion($sql))
      {
        return false;
      }              
      
      $this->Commit();
      return true;
    }
  }
?> 
