<?php

class ContrareferenciasMetodos extends ConexionBD{

    function ContrareferenciasMetodos(){
    
    }
    
    //Consulta los diagnosticos
    function ObtenDiagnosticos($nomDiag, $off){
    
        $sql .= "SELECT diagnostico_id, diagnostico_nombre 
                        FROM diagnosticos 
                        WHERE diagnostico_nombre LIKE '%".$nomDiag."%' "; 
                        
        $sqlCont = "SELECT COUNT(*) FROM(".$sql.") AS A ";
        
        //$this->ProcesarSqlConteo($cont, $off,null,5);
        //$this->ProcesarSqlConteo($sqlCont, $this->offset,null,5);
        $this->ProcesarSqlConteo($sqlCont, $off,null,5);
        
        
        $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
        //$sql .= "LIMIT 1 OFFSET 10 ";
        
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
    
    //Ingreso de un diagnostico por paciente
    function IngrDiagnostico($diagnosId, $pacientId, $tipoPacientId){
    
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
                    diagnos_pacien_id,
                    diagnostico_id, 
                    paciente_id, 
                    tipo_id_paciente 
                ) 
                VALUES(
                    ".$indice['sq'].", 
                    '".$diagnosId."', 
                    '".$pacientId."',
                    '".$tipoPacientId."'
                ); "; 

        if(!$rst = $this->ConexionTransaccion($sql))
        {
            if(!$rst = $this->ConexionTransaccion($sqlerror)) return false;
            
            return false;      
        }

        $this->Commit();

        return true;
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
    
    
    //Funcion para insertar una contrareferencia
    function InsContrareferencia($pacientId, $tipoPacientId, $emprtrab, $estableci, $servicio, $resCuaClin, $hallRelExam, $tratProcTeraRealiTxt, $planTratRecom, $sala, $cama, $profesCod){
	
			$indice = array();
			$sql = "SELECT NEXTVAL('contrareferencias_contrareferencia_id_seq') AS sq ";
			
			$ahahdhs .= $sql;
			
			if(!$rst = $this->ConexionBaseDatos($sql))  return false;
			
			if(!$rst->EOF)
			{
					$indice = $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();     
			}
			
			$rst->Close(); 
	
			$sqlerror = "SELECT SETVAL('contrareferencias_contrareferencia_id_seq', ".($indice['sq']-1).") ";  
	
			$this->ConexionTransaccion();             
							
							
			$sql = "INSERT INTO contrareferencias(
								contrareferencia_id,
								paciente_id,
								tipo_id_paciente,
								fecha, 
								empr_trab, 
								estableci_id, 
								servicio_id, 
								resum_cuadr_clin, 
								hallaz_relevan_exam, 
								trat_proc_tera_reali, 
								plan_trata_recom,
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
								'".$resCuaClin."',
								'".$hallRelExam."',
								'".$tratProcTeraRealiTxt."',
								'".$planTratRecom."',
								'".$sala."',
								'".$cama."',
								".$profesCod."
							); ";
							
			$ahahdhs .= $sql;
							
			if(!$rst = $this->ConexionTransaccion($sql))
			{
				if(!$rst = $this->ConexionTransaccion($sqlerror)) return false;
				
				return false;      
			}
			
			$this->Commit();
			return $ahahdhs;
		}

    function InsContraReferEvoDiagnost($contrareferId, $evolucId, $tipoDiagId, $tipDiagnost){
		
			$this->ConexionTransaccion();
			
			$sql  = "INSERT INTO contrareferencia_evolu_diganost(
									contrareferencia_id, 
									evolucion_id,
									diagnostico_id,
									clase_diagnost_id
							) 
							VALUES(
									".$contrareferId.", 
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
	function ObtenNumContraReferen(){
	
		$sql = "SELECT SETVAL('contrareferencias_contrareferencia_id_seq', ((SELECT NEXTVAL('contrareferencias_contrareferencia_id_seq') AS sq)-1));";
		
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