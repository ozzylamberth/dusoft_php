<?php
	function CitasPaciente($paciente_id,$tipo_id_paciente)
	{
		IncludeClass('AtencionOsHtml','','app','Os_CentralAtencion');
		$objResponse = new xajaxResponse();
		$datos = SessionGetVar("CentralAtecion");
		$aos = new AtencionOsHtml();
		$rqst = array();
		$rqst['tipo_documento_id'] = $tipo_id_paciente;
		$rqst['documento_id'] = $paciente_id;
		
		$html  = "	<center>\n";
		$html .= "		<a href=\"javascript:Cerrar()\" class=\"label_error\">\n";
		$html .= "			VOLVER A LA LISTA\n"; 
		$html .= "		</a>\n"; 
		$html .= "	</center><br>\n"; 
		$html .= $aos->FormaCitas($rqst,0);
		
		$html = utf8_encode( $html );
		$objResponse->assign("cita_paciente","innerHTML",$html);
		$objResponse->assign("cita_paciente","style.display","block");
 		$objResponse->assign("listado_pacientes","style.display","none");
		
		return $objResponse;
	}
	
	function Ocultar()
	{
		$objResponse = new xajaxResponse();
 		$objResponse->assign("listado_pacientes","style.display","block");
		$objResponse->assign("cita_paciente","style.display","none");
		
		return $objResponse;
	}
?>