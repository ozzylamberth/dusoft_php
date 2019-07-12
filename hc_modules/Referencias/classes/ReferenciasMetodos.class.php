<?php

class ReferenciasMetodos extends ConexionBD{
    
    function ReferenciasMetodos(){
    
    }
    
    //Funcion para consultar los diagnosticos que tiene cada paciente
    function ObtenDiagnPacien($pacienteId, $tipoIdPaciente){
    
/*        $sql = "SELECT diagnostico_id, paciente_id, tipo_id_paciente, clase_diagnost_id 
                FROM diagnostico_paciente 
                WHERE paciente_id = '".$pacienteId."' AND tipo_id_paciente = '".$tipoIdPaciente."';";*/   
                                
        $sql = "SELECT di.diagnostico_id AS diagnostico_id, dp.paciente_id, dp.tipo_id_paciente, 
        cd.clase_diagnost_id AS clase_diagnost_id, diagnostico_nombre, cd.descripcion AS descripcion 
        FROM diagnostico_paciente dp, clase_diagnosticos cd, diagnosticos di 
        WHERE di.diagnostico_id = dp.diagnostico_id AND dp.clase_diagnost_id = cd.clase_diagnost_id 
        AND dp.paciente_id = '".$pacienteId."' AND dp.tipo_id_paciente = '".$tipoIdPaciente."';";            
    
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
    
    //Funcion para Consultar los Centros de Remision
    function ObtenEstablecimientos(){
    
        $sql = "SELECT centro_remision, descripcion 
                FROM centros_remision;";    
    
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
    
    //Funcion para Consultar los Servicios
    function ObtenServicios(){
    
        $sql = "SELECT servicio, descripcion 
                FROM servicios;";    
    
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

    //Funcion para Consultar el Estado Civil del Paciente
    function ObtenEstCivil($pacientId, $tipoPacientId){
    
        $sql = "SELECT tipo_estado_civil_id, paciente_id, tipo_id_paciente 
                FROM pacientes 
				WHERE paciente_id='".$pacientId."' AND tipo_id_paciente='".$tipoPacientId."'; ";
    
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
                
	/**
	*	Funcion para obtener el nombre de un usuario, apartir de su ID
	*/
	function ObtenUsuario(){
		
		$sql = "SELECT usuario_id, nombre 
				FROM system_usuarios 
				WHERE usuario_id = ".UserGetUID()."; ";
				
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

     
    //Funcion para insertar una Rereferencia
    function InsReferencia($pacientId, $tipoPacientId, $emprtrab, $estableci, $servicio, $motiRefer, $resCuaClin, $hallRelExam, $planTratReali, $sala, $cama, $profesCod){
    
        $indice = array();
        $sql = "SELECT NEXTVAL('referencias_referencia_id_seq') AS sq ";
        
        $ahahdhs .= $sql;
        
        if(!$rst = $this->ConexionBaseDatos($sql))  return false;
            //return $ahahdhs;
        
        if(!$rst->EOF)
        {
            $indice = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();     
        }
        
        $rst->Close();

        $sqlerror = "SELECT SETVAL('referencias_referencia_id_seq', ".($indice['sq']-1).") ";  

        $this->ConexionTransaccion();                
                                
        $sql = "INSERT INTO referencias(
                    referencia_id,
                    paciente_id,
                    tipo_id_paciente,
                    fecha, 
                    empr_trab, 
                    estableci_id, 
                    servicio_id, 
                    moti_refer, 
                    resum_cuadr_clin, 
                    hallaz_relevan_exam, 
                    plan_trata_reali,
                    sala, 
                    cama,
                    cod_profes
                )VALUES(
                    ".$indice['sq'].",
                    '".$pacientId."',
                    '".$tipoPacientId."',
                    NOW(),
                    '".$emprtrab."',
                    '".$estableci."',
                    '".$servicio."',
                    '".$motiRefer."',
                    '".$resCuaClin."',
                    '".$hallRelExam."',
                    '".$planTratReali."',
                    '".$sala."',
                    '".$cama."',
                    ".$profesCod."
                ); ";        
                        
         //var_dump($sql);
         //var_dump($sql);
         
         $ahahdhs .= $sql;                
          
        //$this->debug(true);                         
        
        if(!$rst = $this->ConexionTransaccion($sql))
        {
            if(!$rst = $this->ConexionTransaccion($sqlerror))
	    	      //return $ahahdhs;
              return false;
		          //return $sql;
            
            //return $ahahdhs;  
            return false;
            //return $sql;      
        }

        $this->Commit();
        
        //return true;
        return $ahahdhs;
        
        //return $sql;    
    }
    
    function InsDiagnostRefer($userId, $tipoDiagId, $evolucId, $tipDiagnost, $referId){
    
        $this->ConexionTransaccion();     

            
        $sqlPr .= "INSERT INTO hc_diagnosticos_egreso(
                    usuario_id, 
                    tipo_diagnostico_id, 
                    evolucion_id,
                    sw_principal,
                    tipo_diagnostico,
                    sw_ficha_llena, 
                    referencia_id
                ) 
                VALUES(
                    1942, 
                    'T200',
                    1691659,
                    '0',
                    '0',
                    '0',
                    2
                );";
                
        $sqlPr1 .= "INSERT INTO hc_diagnosticos_egreso(
                    usuario_id, 
                    tipo_diagnostico_id, 
                    evolucion_id,
                    sw_principal,
                    tipo_diagnostico,
                    sw_ficha_llena, 
                    referencia_id
                ) 
                VALUES(
                    ".$userId.", 
                    ".$tipoDiagId.", 
                    ".$evolucId.",
                    '0',
                    ".$tipDiagnost.",
                    '1', 
                    ".$referId."
                );";                
                                    
        if(!$rst = $this->ConexionTransaccion($sql))
        {
            if(!$rst = $this->ConexionTransaccion($sqlerror))
              return false;
              
            return false;      
        }

        $this->Commit();                     
        return true;
    }      
		
    function InsReferEvoDiagnost($referId, $evolucId, $tipoDiagId, $tipDiagnost){
		
				$this->ConexionTransaccion();
				
        $sql  = "INSERT INTO referencia_evolu_diganost(
                    referencia_id, 
                    evolucion_id,
                    diagnostico_id,
                    clase_diagnost_id
                ) 
                VALUES(
                    ".$referId.", 
                    ".$evolucId.",
                    '".$tipoDiagId."',
                    '".$tipDiagnost."'
                ); ";
         
				
        if(!$rst = $this->ConexionTransaccion($sql))
        {
            if(!$rst = $this->ConexionTransaccion($sqlerror))
              return false;
              
            return false;      
        }
				
				$this->Commit();                     
				return true;
    } 
		    
	/**
	*	Funcion para obtener el numero de la Referencia
	*/
	function ObtenNumReferen(){
	
		$sql = "SELECT SETVAL('referencias_referencia_id_seq', ((SELECT NEXTVAL('referencias_referencia_id_seq') AS sq)-1));";
		
		//$sql = "SELECT (SETVAL('ficha_familar_num_ficha_fam_seq', ((SELECT NEXTVAL('ficha_familar_num_ficha_fam_seq') AS sq)-1))+1) AS n_fi_fam;";
		
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
  
}
?>