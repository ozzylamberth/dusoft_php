<?php
        /**************************************************************************************
        * $Id: hc_Contrareferencias_HTML.php,v 1.2 2009/03/09 21:40:27 gerardo Exp $
        * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
        * @package IPSOFT-SIIS
        * 
        * $Revision: 1.2 $ 	
        * @author Gerardo Amador Vidal
        *
        ***************************************************************************************/
IncludeClass("ClaseHTML");

class Contrareferencias_HTML extends Contrareferencias{


	function Contrareferencias_HTML(){
		$this->Contrareferencias();
		return true;
	}
    
	function GetForma(){
		$pfj = $this->frmPrefijo;
		$evento = $_REQUEST['accion'.$pfj]; 
		
		$impr = AutoCarga::factory('FormasContrareferenciasHTML','views','hc1','Contrareferencias');
		
		$datPaciente = $this->datosPaciente;
		
		$request = $_REQUEST;
		
		$bls = AutoCarga::factory('ContrareferenciasMetodos','','hc1','Contrareferencias');         
		
		$vNoContraRefer = $bls->ObtenNumContraReferen();
		$contraReferId = $vNoContraRefer[0]['setval'] + 1;
		
		//$bls = null;
		
	if($evento == 'IngContraRef'){
	
	}
	
	if($evento == 'CrearContraref'){
		$action['ProcesContraref'] = ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$pfj=>"ProcesContraref")) ;
		
		$html .= ThemeAbrirTablaSubModulo("CONTRAREFERENCIAS");
		$html .= $impr->frmInfoContrarefer($datPaciente['paciente_id'], $datPaciente['tipo_id_paciente'], $datPaciente['edad_paciente']['anos'], $datPaciente['sexo_id'], $contraReferId, $bls, $action);
		
		$html .= "<br>";
		
		$html .= $impr->frmDiagnosticos($datPaciente['paciente_id'], $datPaciente['tipo_id_paciente'], $action);
		
		$html .= ThemeCerrarTablaSubModulo();
		
		return $this->salida = $html;	
	}
	
	if($evento == 'ProcesContraref'){
		//$valInsRef = $bls->InsReferencia($datPaciente['paciente_id'], $datPaciente['tipo_id_paciente'], $request['empTrabaTxt'], $request['estableci'], $request['servicio'], $request['motiReferTxt'], $request['resCuadClinTxt'], $request['hallRevExamTxt'], $request['planTratRealiTxt'], $request['salaTxt'], $request['camaTxt'], $request['profesCod']); 	
		
		//$valInsContraRef = $bls->InsContrareferencia($pacientId, $tipoPacientId, $emprtrab, $estableci, $servicio, $resCuaClin, $hallRelExam, $planTratReali, $planTratRecom)
		
		$bls = AutoCarga::factory('ContrareferenciasMetodos','','hc1','Contrareferencias');

		$valInsContraRef = $bls->InsContrareferencia($datPaciente['paciente_id'], $datPaciente['tipo_id_paciente'], $request['empTrabaTxt'], $request['estableci'], $request['servicio'], $request['resCuadClinTxt'], $request['hallRevExamTxt'], $request['tratProcTeraRealiTxt'], $request['planTratRecomTxt'], $request['salaTxt'], $request['camaTxt'], $request['profesCod']);
		
		//$valInsContraRef = true;
		if(!$valInsContraRef == false){
				$mensaje = "LA CONTRAREFERENCIA HA SIDO INGRESADA EXITOSAMENTE! \n";
				
			if($request['validGuar'] != ""){
				$strDiagnost = explode(";",$request['validGuar']);
				$j = 0;
			
				foreach($strDiagnost as $key => $strDiagno){     
					$strDiag = explode("_",$strDiagno);  
					//$mensaje .= $strDiag[1]."?".$strDiag[2]."_".$j++;
					$bls->InsContraReferEvoDiagnost($request['noContraReferenId'], $request['evolucion'], $strDiag[1], $strDiag[2]);   
				}
				
				$mensaje .= "SE HAN INGRESADO DIAGNOSTICOS! \n";
			}else
					$mensaje .= "NO HA INGRESADO DIAGNOSTICOS! \n";
		
		}else{
			$mensaje .= "ERROR EN EL INGRESO DE LA CONTRAREFERENCIA! \n";
		}
		
		
		$action['volver'] = ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array());
	
		$html .= $impr->fmrMsjIngrContraReferen($action, $mensaje);
	
		//$html .= "<pre> Posicion 2: ".print_r($request, true)." \n ".$valInsContraRef." </pre> \n";
		
		return $this->salida = $html;
	}
	
	$action['CrearContraref'] = ModuloHCGetURL($this->evolucion,$this->paso,0,'',false, array("accion".$pfj=>"CrearContraref"));
	
	$html .= ThemeAbrirTablaSubModulo("CONTRAREFERENCIAS");
	$html .= $impr->frmNuevaContraReferen($action);
	$html .= ThemeCerrarTablaSubModulo();
	//$html .= "<pre> Posicion 0: ".print_r($request, true)."</pre> \n";
	return $this->salida = $html;
	}
        
}

?>