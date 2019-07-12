<?php
	/**
	* Funcion Xajax que permite mostrar los campos a dondes se ingresan los datos del familiar 
	*/
	function DatosFamiliar($idPaciente){
		$objResponse = new xajaxResponse();
		
		$mdl = AutoCarga::factory('IngresaFamiliarHTML','','hc1','FichaFamiliar');
		
		$html = $mdl->FormaDatosFamiliar($idPaciente);
		
		//$objResponse->alert($idPaciente);
		//$objResponse->assign("titulo","innerHTML","Ingreso Familiar");
		$objResponse->assign("ventana","innerHTML",$html);
		
		$objResponse->call("MostrarSpan");
		
		return $objResponse;
	}
	
	function DatosFamiliarEmbzd($idPaciente, $idFamiliar, $nomCompl){
		$objResponse = new xajaxResponse();
		
		$mdl = AutoCarga::factory('IngresaFamiliarHTML','','hc1','FichaFamiliar');
		
		$html = $mdl->FormaDatFamEmbzd($idPaciente, $idFamiliar, $nomCompl);
		
		$objResponse->assign("ventana","innerHTML",$html);
		$objResponse->call("MostrarSpan");
		
		return $objResponse;
	
	}
	
	function DatosFamiliarMortal($idPaciente){
		$objResponse = new xajaxResponse();
		
		$mdl = AutoCarga::factory('IngresaFamiliarHTML','','hc1','FichaFamiliar');
		
		$html = $mdl->FormaDatFamMortal($idPaciente);
		
		$objResponse->assign("ventana","innerHTML",$html);
		$objResponse->call("MostrarSpan");
		
		return $objResponse;
	}

	
	function InsDatFamili($campos0, $campos1, $idPaciente){
		$objResponse = new xajaxResponse();
		
		$cadDatos .= "idPaciente: ".$idPaciente."\n";
		$cadDatos .= "priApellFam: ".$campos0['priApellFam']."\n";
		$cadDatos .= "secApellFam: ".$campos0['secApellFam']."\n";
		$cadDatos .= "priNomFam: ".$campos0['priNomFam']."\n"; 
		$cadDatos .= "secNomFam: ".$campos0['secNomFam']."\n";
		$cadDatos .= "parentFam: ".$campos0['parentFam']."\n"; 
		$cadDatos .= "fechaNacim: ".$campos0['fechaNacim']."\n"; 
		$cadDatos .= "sexoFam: ".$campos0['sexoFam']."\n";
		$cadDatos .= "escolarFam: ".$campos0['escolarFam']."\n"; 
		$cadDatos .= "esqVacFam: ".$campos0['esqVacFam']."\n"; 
		$cadDatos .= "saludBucalFam: ".$campos0['saludBucalFam']."\n"; 
		$cadDatos .= "riesEnfDiscFam: ".$campos0['riesEnfDiscFam']."\n";
		$cadDatos .= "histClinFam: ".$campos0['histClinFam']."\n";
		$cadDatos .= "noIdentiFam: ".$campos0['noIdentiFam']."\n";
		$cadDatos .= "ocupaFam: ".$campos0['ocupacion_id']."\n";
		$cadDatos .= "embarazFam: ".$campos0['embarazFam']."\n";
		$cadDatos .= "causaFamMort: ".$campos0['causaFamMort']."\n";
		$cadDatos .= "difuntoFam: ".$campos0['difuntoFam']."\n";
		$cadDatos .= "tipoIdentiFam: ".$campos0['tipoIdentiFam']."\n";
		
		//$objResponse->alert($cadDatos);

// 		$html .= "		<td align=\"center\" colspan=\"1\" rowspan=\"1\" class=\"hc_table_submodulo_list_title\" > 1 - 4 AÑOS \n";
// 		$html .= "		</td> \n";
// 
// 		$html .= "		<td align=\"center\" rowspan=\"1\" class=\"hc_table_submodulo_list_title\" > 5 - 9 AÑOS \n";
// 		$html .= "		</td> \n";
// 
// 		$html .= "		<td align=\"center\" rowspan=\"1\" class=\"hc_table_submodulo_list_title\" > 10 - 19 AÑOS \n";
// 		$html .= "		</td> \n";
// 
// 		$html .= "		<td align=\"center\" rowspan=\"1\" class=\"hc_table_submodulo_list_title\" > 20 - 64 AÑOS \n";
// 		$html .= "		</td> \n";
// 
// 		$html .= "		<td align=\"center\" rowspan=\"1\" class=\"hc_table_submodulo_list_title\" > 65 AÑOS O MAS\n";
// 		$html .= "		</td> \n";
		
//		$html .= "</table> \n";
		
		$mdl = AutoCarga::factory('IngresaFamiliarHTML','','hc1','FichaFamiliar');
		$obCons = AutoCarga::factory('FichaFamiliarMetodos','','hc1','FichaFamiliar');
		
		//$strVal = 
		$obCons->InsertarFamiliar($campos0, $idPaciente);
		
		$html = $mdl->frmMiemFamiliar($obCons, $idPaciente);
		
		$objResponse->assign("SeccionDatFam_id", "innerHTML",$html);
		$objResponse->call("OcultarSpan");
		
		return $objResponse;
	}
	
	/**
	*	Funcion xajax que permite insertar todos los datos adicionales de la familiar
	*	embarazada
	*/
	function InsDatFamEmbzd($campos0, $campos1, $idPaciente){
		$objResponse = new xajaxResponse();
		
		
		$cadDatos .= "idFamiliar: ".$campos0['idFamiliar']." \n";
		$cadDatos .= "nombApellFamEmbzd: ".$campos0['nombApellFamEmbzd']." \n";
		$cadDatos .= "fechaUltMenstr: ".$campos0['fechaUltMenstr']." \n";
		$cadDatos .= "fechaProbParto: ".$campos0['fechaProbParto']." \n"; 
		$cadDatos .= "semGestac: ".$campos0['semGestac']." \n";
		$cadDatos .= "priDosis: ".$campos0['priDosis']." \n"; 
		$cadDatos .= "segDosis: ".$campos0['segDosis']." \n"; 
		$cadDatos .= "rfzDosis: ".$campos0['rfzDosis']." \n";
		$cadDatos .= "agoGestas: ".$campos0['agoGestas']." \n";
		$cadDatos .= "agoPartos: ".$campos0['agoPartos']." \n";
		$cadDatos .= "agoAbortos: ".$campos0['agoAbortos']." \n";
		$cadDatos .= "agoCesareas: ".$campos0['agoCesareas']." \n";
		$cadDatos .= "antPatObst: ".$campos0['antPatObst']." \n";
		
		$mdl = AutoCarga::factory('IngresaFamiliarHTML','','hc1','FichaFamiliar');
		$obCons = AutoCarga::factory('FichaFamiliarMetodos','','hc1','FichaFamiliar');
		
		if(!empty($idPaciente)){
			//$objResponse->alert($cadDatos);
			$obCons->InsertarFamiliarEmbzd($campos0);
			$obCons->ModificarFamiliEmbzd($campos0['idFamiliar'], "2");
		}
		else{
			//$objResponse->alert("xajax_idFamiliar: ".$campos0." \n");
			$obCons->ModificarFamiliEmbzd($campos0, "1");
		}
			
		$html = $mdl->frmEmbarazFamiliar($obCons);
		
		$objResponse->assign("SeccionDatFamEmbzd_id", "innerHTML", $html);
		$objResponse->call("OcultarSpan");
		
		return $objResponse;
	}
	
	function InsDatFamMort($campos0, $campos1, $idPaciente){
		$objResponse = new xajaxResponse();
		
		//$obCons = AutoCarga::factory('FichaFamiliarMetodos','','hc1','FichaFamiliar');
		
		//$obCons->InsertarFamiliar($campos0);
		$cadDatos .= "idPaciente: ".$idPaciente." \n";
		$cadDatos .= "priApellFamMort: ".$campos0['priApellFamMort']." \n";
		$cadDatos .= "secApellFamMort: ".$campos0['secApellFamMort']." \n";
		$cadDatos .= "priNomFamMort: ".$campos0['priNomFamMort']." \n"; 
		$cadDatos .= "secNomFamMort: ".$campos0['secNomFamMort']." \n";
		$cadDatos .= "parentFamMort: ".$campos0['parentFamMort']." \n"; 
		$cadDatos .= "edadFalleFamMort: ".$campos0['edadFalleFamMort']." \n"; 
		$cadDatos .= "causaFamMort: ".$campos0['causaFamMort']." \n";
		$cadDatos .= "difuntoFam: ".$campos0['difuntoFam']." \n";
		
		 
		//$objResponse->alert($cadDatos);
		
		$mdl = AutoCarga::factory('IngresaFamiliarHTML','','hc1','FichaFamiliar');
		$obCons = AutoCarga::factory('FichaFamiliarMetodos','','hc1','FichaFamiliar');
		
		$obCons->InsertarFamiliar($campos0, $idPaciente);
		
		$html = $mdl->frmMortalFamiliar($obCons, $idPaciente);
		
		$objResponse->assign("SeccionDatFamMort_id", "innerHTML", $html);
		$objResponse->call("OcultarSpan");
		
		return $objResponse;
	}
	
	
	function EvaluarDatos($forma, $objResp){
		//$objResponse = new xajaxResponse();

		return $objResp->alert("HOlA XAJAX");
	}
	
?>