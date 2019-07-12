<?php

class BuscadorDiagnosticosSQL{

    var $offset;
    var $pagina;
    var $conteo;
    var $limit;
    
    var $error;
    var $mensajeDeError;
    var $dbconn;
    
    
    function BuscadorDiagnosticosSQL(){
    
    }
    
    //Consulta los diagnosticos
    function ObtenDiagnosticos($nomDiag, $off){
    
        $sql .= "SELECT diagnostico_id, diagnostico_nombre 
                FROM diagnosticos 
                WHERE diagnostico_nombre LIKE '%".$nomDiag."%' "; 
                        
        $sqlCont = "SELECT COUNT(*) FROM(".$sql.") AS A ";
        
        
        $this->ProcesarSqlConteo($sqlCont, $off,null,5);
        
        
        $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
        
        if(!$rst = $this->ConexionBaseDatos($sql)) return false;
        
        $datos = array();
        while(!$rst->EOF)
        {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        
        return $datos; 

    }
    
    //Funcion para consultar los tipos de diagnosticos
    function ConsClaseDiagnos(){
    
        $sql = "SELECT clase_diagnost_id, descripcion 
        FROM clase_diagnosticos";
        
        if(!$rst = $this->ConexionBaseDatos($sql)) return false;
        
        $datos = array();
        while(!$rst->EOF)
        {
                $datos[] = $rst->GetRowAssoc($ToUpper = false);
                $rst->MoveNext();
        }
        $rst->Close();
        
        return $datos;             
    
    }
    
    
    //Funcion para consultar los diagnosticos aue tiene cada paciente
    function ObtenDiagnPacien($pacienteId, $tipoIdPaciente){
    
             
        $sql = "SELECT diagnostico_id, paciente_id, tipo_id_paciente 
                FROM diagnostico_paciente 
                WHERE paciente_id = '".$pacienteId."' AND tipo_id_paciente = '".$tipoIdPaciente."';";    
    
        if(!$rst = $this->ConexionBaseDatos($sql)) return false;
        
        $datos = array();
        while(!$rst->EOF)
        {
                $datos[] = $rst->GetRowAssoc($ToUpper = false);
                $rst->MoveNext();
        }
        $rst->Close();
        
        return $datos; 
    }    

/*    
    //Ingreso de un diagnostico por paciente
    function IngrDiagnostico($diagnosId, $claseDiagnostId, $pacientId, $tipoPacientId){
    
        $indice = array();
        $sql = "SELECT NEXTVAL('diagnostico_paciente_diagnos_pacien_id_seq') AS sq ";
        
        if(!$rst = $this->ConexionBaseDatos($sql))  return false;
        
        if(!$rst->EOF)
        {
            $indice = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();     
        }
        
        $rst->Close(); 

        $sqlerror = "SELECT SETVAL('diagnostico_paciente_diagnos_pacien_id_seq', ".($indice['sq']-1).") ";  

        $this->ConexionTransaccion();

        $sql = "INSERT INTO diagnostico_paciente(

                    diagnostico_id, 
                    paciente_id, 
                    tipo_id_paciente,
                    clase_diagnost_id 
                ) 
                VALUES(
                    ".$indice['sq'].", 
                    '".$diagnosId."', 
                    '".$pacientId."',
                    '".$tipoPacientId."',
                    '".$claseDiagnostId."'
                ); "; 

        if(!$rst = $this->ConexionTransaccion($sql))
        {
            if(!$rst = $this->ConexionTransaccion($sqlerror)) return false;
            
            return false;      
        }

        $this->Commit();

        return true;
    }
*/

    //Ingreso de un diagnostico por paciente
    function IngrDiagnostico($diagnosId, $claseDiagnostId, $pacientId, $tipoPacientId, $referenId){
    
        $this->ConexionTransaccion();

        $sql = "INSERT INTO diagnostico_paciente(
                    diagnostico_id, 
                    paciente_id, 
                    tipo_id_paciente,
                    referencia_id,
                    clase_diagnost_id 
                ) 
                VALUES(
                    '".$diagnosId."', 
                    '".$pacientId."',
                    '".$tipoPacientId."',
                    ".$referenId.",
                    '".$claseDiagnostId."'
                ); "; 

        if(!$rst = $this->ConexionTransaccion($sql))
        {         
            return false;      
        }

        $this->Commit();

        return true;
    }    
            
    /********************************************************************************
    * Funcion que permite realizar la conexion a la base de datos y ejecutar la 
    * consulta sql 
    * 
    * @param string sentencia sql a ejecutar 
    * @return rst 
    *********************************************************************************/
    function ConexionBaseDatos($sql){
        list($dbconn)=GetDBConn();
        //$dbconn->debug=true;
        $rst = $dbconn->Execute($sql);
                
        if ($dbconn->ErrorNo() != 0)
        {
            $this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
            return false;
        }
        return $rst;
    }

    function ProcesarSqlConteo($sql,$pg_siguiente = 0,$num_reg = 0,$limite = 0)
    {
        $this->offset = 0;
        $this->pagina = 1;
        if($limite === 0)
        {
            $this->limit = GetLimitBrowser();
            if(!$this->limit) $this->limit = 20;
        }
        else
        {
                $this->limit = $limite;
        }

        if($pg_siguiente)
        {
            $this->pagina = intval($pg_siguiente);
            if($this->pagina > 1)
                    $this->offset = ($this->pagina - 1) * ($this->limit);
        }

        if(!$num_reg)
        {
                if(!$rst = $this->ConexionBaseDatos($sql)) return false;

                if(!$rst->EOF)
                {
                        $this->conteo = $rst->fields[0];
                        $rst->MoveNext();
                }
                $rst->Close();
        }
        else
        {
                $this->conteo = $num_reg;
        }
        return true;
    }

    function Commit()
    {
        $this->dbconn->CommitTrans();
    }
    
    function ConexionTransaccion($sql,$asoc = false)
    {
        GLOBAL $ADODB_FETCH_MODE;

        if(!$sql)
        {
            list($this->dbconn) = GetDBconn();
            $this->dbconn->debug = $this->debug;
            $this->dbconn->BeginTrans();
        }
        else
        {
            if($asoc) $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
    
            $rst = $this->dbconn->Execute($sql);
    
            if($asoc) $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
    
            if ($this->dbconn->ErrorNo() != 0)
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = $this->dbconn->ErrorMsg()."<br>".$sql;
                $this->dbconn->RollbackTrans();
                                
                return false;
            }
            return $rst;
        }
    }    
    
    
}
?>