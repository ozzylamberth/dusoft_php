<?php
	/*
	$Id: FacturacionRecepcion.php,v 1.4 2007/06/26 23:29:14 carlos Exp $
	*/
	//*****************************************
	//METODOS PARA ADICIONAR DESCRIPCION A LA FACTURA CREDITO AL RECIBIRLA
	//*****************************************

	function AdicionarFacturaRecepcion($EmpresaId,$prefijo,$numero,$tama?o,$indicetabla,$indicetr,$agrupada)
	{//echo $numero;
		$objResponse = new xajaxResponse();
		$ventana = CrearVentanaRecepcionFacturaCredito($EmpresaId,$prefijo,$numero,$tama?o,$indicetabla,$indicetr,$agrupada);   

		$objResponse->assign("d2Contents","innerHTML",$ventana);
		$objResponse->call('Iniciar');
		$objResponse->call('MostrarVentana');
		return $objResponse;
	}


	function CrearVentanaRecepcionFacturaCredito($EmpresaId,$prefijo,$numero,$tama?o,$indicetabla,$indicetr,$agrupada)
	{
		$ventana = "  <form name=\"formaRecepcionFacturaCredito\" action=\"$action\" method=\"post\">";            
		$ventana .= "  <table align=\"center\" width=\"80%\" >";
		$ventana .= "    <tr align=\"center\">";
		$ventana .= "    <td align=\"center\" class=\"Menu\" colspan=\"6\"><b>RECIBIR FACTURA : $prefijo $numero ?</b></td>";
		$ventana .= "    </tr>";
		$ventana .= "    <tr class=\"modulo_list_claro\">\n";
		$ventana .= "      <td colspan=\"4\">OBSERVACION:&nbsp;<textarea class=\"textarea\" name=\"observacion\" rows=\"3\" cols=\"40\" >...</textarea></td>\n";
		$ventana .= "    </tr>";
		$ventana .= "    <tr class=\"modulo_list_claro\">\n";
		//$ventana .= "      <td class=\"label\">&nbsp;</td>\n";
		//$ventana .= "      <td align=\"center\">SI<input id=\"recepcion1\" type=\"radio\" name=\"recepcion\" value=\"1\"></td>\n";
		//$ventana .= "      <td class=\"label\">&nbsp;</td>\n";
		//$ventana .= "      <td align=\"center\">NO<input id=\"recepcion0\" type=\"radio\" name=\"recepcion\" value=\"0\"></td>\n";
		$ventana .= "    </tr>";
		$ventana .= "    <tr><td></td></tr>\n";
		$ventana .= "    <tr><td colspan=\"6\" align=\"center\">\n";
		$ventana .= "    <input type=\"button\" class=\"input-submit\" name=\"insertar\" value=\"ACEPTAR\" onclick=\"xajax_ActualizarRecepcionFacturaCredito('$EmpresaId','$prefijo','$numero','$tama?o','$indicetabla','$indicetr','$agrupada',document.formaRecepcionFacturaCredito.observacion.value)\"></td></tr>\n";    
		$ventana .= "  </table><BR>"; 
		//$ventana .= MostrarFechasVencimiento($codigo_producto,$valor,$cantidad);    
		$ventana .= "  </form>";
		return $ventana;
	}
	
	function ActualizarRecepcionFacturaCredito($EmpresaId,$prefijo,$numero,$tama?o,$indicetabla,$indicetr,$agrupada,$observacion)
	{
		$objResponse = new xajaxResponse();
		$html = "<img src=\"".GetThemePath()."/images/auditoria.png\" border=\"0\" width=\"15\" height=\"15\" title=\"$observacion\">";
		//for($i=0; $i<$tama?o; $i++)
		//{
			//if($i==$indicetr)
			//{
				//$objResponse->create("tablafacturasrecibidas$indicetabla", "tr", "facturasrecibidas$indicetr".$EmpresaId.'//||'.$prefijo.'//||'.$numero);
				//$objResponse->create("facturasrecibidas$indicetr".$EmpresaId.'//||'.$prefijo.'//||'.$numero, "td", "facturasrecibidas$indicetr".$EmpresaId.'//||'.$prefijo.'//||'.$numero);
				//$objResponse->assign("facturasrecibidas$indicetr".$EmpresaId.'//||'.$prefijo.'//||'.$numero,"innerHTML",$html);
				if($agrupada == '1')
				{
					$objResponse->assign("td_agrupadas$indicetabla$indicetr","innerHTML",$html);
				}
				else
				{
					$objResponse->assign("td$indicetabla$indicetr","innerHTML",$html);
				}
				$value = $EmpresaId."//||".$prefijo."//||".$numero."//||".$observacion;
				$objResponse->assign("check".$EmpresaId."//||".$prefijo."//||".$numero,"value",$value);
			//}
		//}
		if($_SESSION['FACTURACION_RECEPCION']['OBSERVACION'])
		{
			array_push($_SESSION['FACTURACION_RECEPCION']['OBSERVACION'], array('EmpresaId'=>$EmpresaId,'Prefijo'=>$prefijo,'Numero'=>$numero,'Observacion'=>$observacion));
		}
		else
		{
			$_SESSION['FACTURACION_RECEPCION']['OBSERVACION']=array(array('EmpresaId'=>$EmpresaId,'Prefijo'=>$prefijo,'Numero'=>$numero,'Observacion'=>$observacion));
		}
		$objResponse->assign("d2Container","style.display","none");
		return $objResponse;
	}
?>