<?php
	
	function VouchersFactura($empresa,$prefijo,$numero,$fact)
	{
		$objResponse=new xajaxResponse();
	
		$objClass=new app_OrdenesdePagos_user;
		
		$vouchers=$objClass->GetVoucher($empresa,$prefijo,$numero);
		$salida=GenerarHTML($vouchers,$fact);
		
		$objResponse->assign("titulo","innerHTML","<center>DOCUMENTO: $prefijo - $numero &nbsp;&nbsp; FACTURA #: ".$fact."<center>");
		$objResponse->assign("d2Contents","innerHTML",$salida);
		
		return $objResponse;
	}
	
	function GenerarHTML($vouchers,$fact)
	{
		$salida = "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";    
		$salida .= "    <tr class=\"modulo_table_list_title\" align=\"center\">";
		$salida .= "    	<td width=\"10%\">DOCUMENTO</td>";
		$salida .= "    	<td width=\"10%\">FACTURA</td>";
		$salida .= "    	<td width=\"35%\">CARGO</td>";
		$salida .= "    	<td width=\"15%\">VALOR NC</td>";
		$salida .= "    	<td width=\"15%\">VALOR ND</td>";
		$salida .= "			<td width=\"15%\">VALOR HONORARIO</td>";
		$salida .= "    </tr>";
		$k=0;
		foreach($vouchers as $key=>$valor)
		{
			if($k%2==0)
			{
				$estilo="modulo_list_oscuro";
			}
			else
			{
				$estilo="modulo_list_claro";
			}
			$salida .= "    <tr class=\"$estilo\">";
			$salida .= "    	<td align=\"center\">".$valor['prefijo']."-".$valor['numero']."</td>";
			$salida .= "    	<td align=\"center\"> $fact </td>";
			$salida .= "    	<td align=\"left\">".$valor['descripcion']."</td>";
			$salida .= "    	<td align=\"right\"> $ ".FormatoValor($valor['valor_nc'])."</td>";
			$salida .= "    	<td align=\"right\"> $ ".FormatoValor($valor['valor_nd'])."</td>";
			$salida .= "    	<td align=\"right\"> $ ".FormatoValor($valor['valor_real'])."</td>";  
			$salida .= "    </tr>";
			$k++;
		}
		$salida .= "	</table><br>";
		
		return $salida;
	}
	
	function ObtenerPlan($tercero)
	{
		$objResponse=new xajaxResponse();
		
		$objClass=new app_OrdenesdePagos_user;
		
		list($TipoTerc,$Terc)=explode("||//",$tercero);
		
		if(!$TipoTerc AND !$Terc)
			$planes=$objClass->GetPlanes();
		else
			$planes=$objClass->GetPlanes($TipoTerc,$Terc);
		
		$salida.="				<option value=\"\">--SELECCIONE PLAN--</option>";
		foreach($planes as $plan)
			$salida.="<option value=\"".$plan['plan_id']."\">".$plan['plan_descripcion']."</option>";

		$objResponse->assign("plan","innerHTML",$salida);

		return $objResponse;
	
	}
	
	function Cancelar($ordenes,$capa)
	{
		$objResponse=new xajaxResponse();
		
		$objClass=new app_OrdenesdePagos_user;
		
		$pk_orden=explode("__",$ordenes);
		$ban=0;
		foreach($pk_orden as $orden)
		{
			list($empresa,$prefijo,$numero,$capa)=explode("-",$orden);
			
			if(!$objClass->CancelacionOrdenesPago($empresa,$prefijo,$numero))
			{
				$objResponse->assign("error","innerHTML","ERROR AL CANCELAR LA ORDEN: ".$prefijo."-".$numero);
				$ban=1;
				break;
			}
			$objResponse->assign($capa,"style.display","none");
		}
		if(!$ban)
		{
			$objResponse->assign("error","innerHTML","ORDENES CANCELADAS EXISTOSAMENTE");
		}
		return $objResponse;
	}
	
	
	function Noseleccionar($ordenes,$empresa_des,$prefnumero)
	{
	
		$objResponse=new xajaxResponse();
		
		$pk_orden=explode("__",$ordenes);
		list($prefijo_des,$numero_des)=explode("-",$prefnumero);
		
		$cancelacion="";
		foreach($pk_orden as $orden)
		{
			list($empresa,$prefijo,$numero,$capa)=explode("-",$orden);

			if($empresa!=$empresa_des OR $prefijo!=$prefijo_des OR $numero!=$numero_des)
			{
				if(!$cancelacion)
					$cancelacion.=$empresa."-".$prefijo."-".$numero."-".$capa;
				else
					$cancelacion.="__".$empresa."-".$prefijo."-".$numero."-".$capa;
			}
		}
		
		$objResponse->assign("ordenpago","value",$cancelacion);
		$objResponse->call("ReemOrden");
		
		return $objResponse;
	}

?>