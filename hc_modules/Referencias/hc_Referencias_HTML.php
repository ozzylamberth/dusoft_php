<?php
	/**************************************************************************************
	* $Id: hc_Referencias_HTML.php,v 1.3 2009/03/09 21:38:55 gerardo Exp $
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* $Revision: 1.3 $ 	
	* @author Gerardo Amador Vidal
	*
	***************************************************************************************/
IncludeClass("ClaseHTML");

class Referencias_HTML extends Referencias{
	
	var $valDescrip;
	
	function Referencias_HTML(){
		$this->Referencias();
		return true;
	}

	function GetForma(){

				$pfj = $this->frmPrefijo;
        $evento = $_REQUEST['accion'.$pfj]; 
        
        $impr = AutoCarga::factory('FormasReferenciasHTML','views','hc1','Referencias');
        
        $datPaciente = $this->datosPaciente;
        
        $request = $_REQUEST;   

        $bls = AutoCarga::factory('ReferenciasMetodos','','hc1','Referencias');         
        
        $vNoRefer = $bls->ObtenNumReferen();
        $referId = $vNoRefer[0]['setval'] + 1;                

        if($evento == 'IngReferencia'){
            $action['volver'] = ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array());      
            $html .= ThemeAbrirTablaSubModulo("REFERENCIAS_HEEEEEEYY");                         
            $html .= $impr->frmInfoReferen($datPaciente['paciente_id'], $datPaciente['tipo_id_paciente'], $datPaciente['edad_paciente']['anos'], $datPaciente['sexo_id'], $referId, $bls, $action); 
            $html .= "<br>";
            $html .= $impr->frmDiagnosticos($datPaciente['paciente_id'], $datPaciente['tipo_id_paciente'], $referId, $action);
                        
            $html .= ThemeCerrarTablaSubModulo();
            //$html .= "<pre>".print_r($request, true)."</pre> \n";
                        
//             $bls->InsReferencia($datPaciente['paciente_id'], $datPaciente['tipo_id_paciente'], $resCuaClin, $hallRelExam, $planTratReali);

            //$prSQL = $bls->InsReferencia($datPaciente['paciente_id'], $datPaciente['tipo_id_paciente'], "hey0", "hey1", "hey2");
        
            return $this->salida = $html; 
        }
        
        if($evento == 'CrearRef'){
                           
            $action['ProcesRef'] = ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$pfj=>"ProcesRef")) ;

            $this->SetXajax(array("crearReferen", "crearDiagnRefer", "pintarDiagn"), "hc_modules/Referencias/RemoteXajax/FuncionesReferencias.php"); 
            
            
            $html .= ThemeAbrirTablaSubModulo("REFERENCIAS");
            $html .= $impr->frmInfoReferen($datPaciente['paciente_id'], $datPaciente['tipo_id_paciente'], $datPaciente['edad_paciente']['anos'], $datPaciente['sexo_id'], $referId, $bls, $action);
            
    //        //$html .= "<pre> pacientId: ".$datPaciente['paciente_id'].", tipoPacientId: ".$datPaciente['tipo_id_paciente']." </pre>";
    //        //$html .= "<pre> pacientId: ".$pacientId.", tipoPacientId: ".$tipoPacientId." </pre>";                       
            $html .= "<br>";
            
            $html .= $impr->frmDiagnosticos($datPaciente['paciente_id'], $datPaciente['tipo_id_paciente'], $referId, $action);
            $html .= ThemeCerrarTablaSubModulo();
            
            //$html .= "<pre>".print_r($request, true)."</pre> \n";
            
            //$html .= "<pre> Posicion 1: ".$action['ProcesRef']."</pre> \n";
            //$html .= "<pre> Posicion 1: ".print_r($request, true)."</pre> \n";
                                          
            return $this->salida = $html;
        }
        
    if($evento == 'ProcesRef'){ 
		
				$bls = AutoCarga::factory('ReferenciasMetodos','','hc1','Referencias');
				
				$valInsRef = $bls->InsReferencia($datPaciente['paciente_id'], $datPaciente['tipo_id_paciente'], $request['empTrabaTxt'], $request['estableci'], $request['servicio'], $request['motiReferTxt'], $request['resCuadClinTxt'], $request['hallRevExamTxt'], $request['planTratRealiTxt'], $request['salaTxt'], $request['camaTxt'], $request['profesCod']); 
			
			if(!$valInsRef == false){
				$mensaje = "LA REFERENCIA HA SIDO INGRESADA EXITOSAMENTE !";
		
				if($request['validGuar'] != ""){
					$strDiagnost = explode(";",$request['validGuar']);
					$j = 0;
					
					foreach($strDiagnost as $key => $strDiagno){
						$strDiag = explode("_",$strDiagno);
						//$mensaje .= $strDiag[1]."?".$strDiag[2]."_".$j++; 
						$bls->InsReferEvoDiagnost($request['noReferenId'], $request['evolucion'], $strDiag[1], $strDiag[2]);
					}
					
					$mensaje .= "SE HAN INGRESADO DIAGNOSTICOS! \n"; 
				}
				else
					$mensaje .= "NO HA INGRESADO DIAGNOSTICOS!";
			}
			else{
				$mensaje .= "ERROR EN EL INGRESO DE LA REFERENCIA!";
			}

				//$obCons->InsDiagnostRefer($userId, $tipoDiagId, $evolucId, $tipDiagnost, $referId);
				
				///$bls->InsReferEvoDiagnost($referId, $evolucId, $tipoDiagId, $tipDiagnost)
      
        $action['volver'] = ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array());
				
				$html .= $impr->fmrMsjIngrReferencia($action, $mensaje);
      //$html .= ThemeCerrarTablaSubModulo();
          
      //$html .= "<pre> Posicion 2: ".print_r($request, true)." \n ".$valInsRef." \n</pre>";         
        return $this->salida = $html;
    }        
              
        $action['CrearRef'] = ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$pfj=>"CrearRef")); 
        
        $html .= ThemeAbrirTablaSubModulo("REFERENCIAS");
        $html .= $impr->frmNuevaReferencia($action);
        $html .= ThemeCerrarTablaSubModulo();
        //$html .= "<pre> Posicion 0: ".print_r($request, true)."</pre> \n";
        
        return $this->salida = $html;     
	}
}

?>