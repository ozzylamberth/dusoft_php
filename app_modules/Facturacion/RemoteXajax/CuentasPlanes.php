<?php
	
	  
  function GetEstadoPlanes($estado)
	{
		$objResponse = new xajaxResponse();     
		
		$objClass=new app_Facturacion_user;
		
		$planes=$objClass->GetPlanes($estado);
		
		$salida = "				<select name=\"planes\" class=\"select\">";
		$salida .= "					<option value=\"\">--PLAN--</option>\n";
		
		
		foreach($planes as $plan)
		{
			$desc_estado="";
			if($estado==3)
			{
				if($plan['estado']=='1')
					$desc_estado="_(ACTIVO)";
				else
					$desc_estado="_(INACTIVO)";
			}

			$salida .= "					<option value=\"".$plan['plan_id']."\" $sel>".$plan['plan_descripcion']."_".$plan['plan_id'].$desc_estado."</option>\n";
		}
		$salida .= "				</select>\n";
		
		$salida=$objResponse->SetTildes($salida);
		
		$objResponse->assign("capa_plan","innerHTML",$salida); 
    
		return $objResponse;
  }

?>