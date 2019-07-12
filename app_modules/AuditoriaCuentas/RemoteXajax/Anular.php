<?php
	function NoAcepatarGlosa($glosa)
	{
		IncludeClass('AuditoriaGlosas','','app','AuditoriaCuentas');
		$objResponse = new xajaxResponse();
		
		$empresa = $_SESSION['Auditoria']['empresa'];
		$adc = new AuditoriaGlosas();
		$rst = $adc->ActualizarGlosa($empresa,$glosa);
		
		$html  = "<table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
		$html .= "	<tr>\n";
		$html .= "		<td class=\"normal_10AN\" align=\"center\">\n";
		if($rst)
			$html .= "			LA GLOSA Nº ".$glosa." HA SIDO CERRADA\n";
		else
			$html .= "			".$adc->frmError['MensajeError']."\n";
			
		$html .= "		</td>\n";
		$html .= "	</tr>\n";
		$html .= "</table>\n";
		$html = utf8_encode($html);
		
		$objResponse->assign("mensaje","innerHTML",$html);
		$objResponse->assign("boton","style.display","none");
		$objResponse->assign("volver","value","Aceptar");
		$objResponse->call("CambiarAccion");
		
		return $objResponse;
	}
?>