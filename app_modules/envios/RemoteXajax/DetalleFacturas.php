<?php
	/**************************************************************************************  
	* $Id: DetalleFacturas.php,v 1.2 2007/07/03 21:03:39 hugo Exp $ 
	* 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	* 
	* $Revision: 1.2 $ 
	* 
	* @autor Hugo F  Manrique 
	***************************************************************************************/
	IncludeClass('CarteraDetalle','','app','Cartera');
	function InformacionFactura($prefijo,$factura_fiscal,$empresa)
	{
		$objResponse = new xajaxResponse();
		$ctd = new CarteraDetalle();
		$cuentas = $ctd->ObtenerCuentasFactura($prefijo,$factura_fiscal,$empresa);
		
		if(sizeof($cuentas) > 1)
		{
			$html = ListaCuentasFactura($prefijo,$factura_fiscal,$cuentas,$empresa);
			$objResponse->assign("ContenidoB","innerHTML",$html);
			$objResponse->call("MostrarDetalle");
		}
		else
			$objResponse = InformacionCuenta(key($cuentas),$empresa,true);
		
		return $objResponse;
	}
	/****
	 *
	 */
	function InformacionFacturaExterna($prefijo,$factura_fiscal,$empresa)
	{
		$ctd = new CarteraDetalle();
		$datos = $ctd->ObtenerInformacionFacturaExterna($prefijo,$factura_fiscal,$empresa);
		
		$style = "style=\"text-align:left;text-indent:6pt\" ";
		$html  = "<br>\n";
		$html .= "<center>\n";
		$html .= "	<fieldset class=\"fieldset\" style=\"width:96%\">\n";
		$html .= "		<legend class=\"normal_10AN\">INFORMACION DE LA FACTURA Nº ".$prefijo." ".$factura_fiscal."</legend>\n";
		$html .= "		<table align=\"center\" width=\"80%\" border=\"0\" class=\"modulo_table_list\">\n";
		$html .= "			<tr class=\"modulo_table_list_title\">\n";
		$html .= "				<td width=\"25%\" $style >FACTURA Nº</td>\n";
		$html .= "				<td width=\"25%\" align=\"left\" class=\"modulo_list_claro\" >". $datos['prefijo']." ".$datos['factura_fiscal']."</td>\n";
		$html .= "				<td width=\"25%\" $style >FECHA</td>\n";
		$html .= "				<td width=\"25%\" align=\"left\" class=\"modulo_list_claro\" >".$datos['registro']."</td>\n";		
		$html .= "			</tr>\n";
		$html .= "			<tr class=\"modulo_table_list_title\">\n";			
		$html .= "				<td $style>TOTAL</td>\n";
		$html .= "				<td align=\"right\" class=\"modulo_list_claro\">$".formatovalor($datos['total_factura']);
		$html .= "				<td $style>SALDO</td>\n";
		$html .= "				<td align=\"right\" class=\"modulo_list_claro\">$".formatovalor($datos['saldo']);
		$html .= "			</tr>\n";
		if($datos['numero_envio'])
		{
			$html .= "			<tr class=\"modulo_table_list_title\">\n";
			$html .= "				<td $style>ENVIO Nº</td>\n";
			$html .= "				<td align=\"left\" class=\"modulo_list_claro\">".$datos['numero_envio']."</td>\n";
			$html .= "				<td $style >F. VENCIMIENTO</td>\n";
			$html .= "				<td align=\"left\" class=\"modulo_list_claro\">".$datos['vencimiento']."</td>\n";
			$html .= "			</tr>\n";
		}
		if($datos['concepto'])
		{
			$html .= "			<tr class=\"modulo_table_list_title\">\n";
			$html .= "				<td colspan=\"4\" >CONCEPTO</td>\n";
			$html .= "			</tr>\n";
			$html .= "			<tr class=\"modulo_table_list_title\">\n";
			$html .= "				<td align=\"left\" class=\"modulo_list_claro\" colspan=\"4\">".$datos['concepto']."</td>\n";
			$html .= "			</tr>\n";
		}
		$html .= "		</table><br>\n";
		$html .= "	</fieldset><br>\n";
		$html .= "	<a href=\"javascript:OcultarSpan('Facturas')\" class=\"label_error\">CERRAR</a>\n";
		$html .= "</center>\n";
		$html .= "<br>\n";
		
		$html = utf8_encode( $html );

		$objResponse = new xajaxResponse();
		$objResponse->assign("ContenidoB","innerHTML",$html);
		$objResponse->call("MostrarDetalle");
		return $objResponse;
	}
	/****
	 *
	 */
	function InformacionCuenta($numerodecuenta,$empresa,$flag = false)
	{
		$objResponse = new xajaxResponse();
		$ctd = new CarteraDetalle();
		$cuenta = $ctd->ObtenerInformacionDetalleCuentas($empresa,$numerodecuenta);
		$Cargos = $ctd->ObtenerCargosCuentas($numerodecuenta,$empresa);
		$Insumos = $ctd->ObtenerInsumosCuentas($numerodecuenta,$empresa);

		$estilo = "class=\"modulo_table_list_title\" style=\"text-indent:4pt;text-align:left\"";
		$html .= "<br>\n";
		$html .= "<center>\n";
		$html .= "	<fieldset class=\"fieldset\" style=\"width:98%\">\n";
		$html .= "		<legend class=\"normal_10AN\">INFORMACION DE LA CUENTA Nº ".$numerodecuenta."</legend>\n";
		$html .= "		<table align=\"center\" cellpading=\"0\"  width=\"98%\" border=\"0\" class=\"modulo_table_list\">\n";
		$html .= "			<tr $estilo>\n";
		$html .= "				<td width=\"20%\" >\n";
		$html .= "					<b>PLAN</b>\n";
		$html .= "				</td>\n";
		$html .= "				<td class=\"modulo_list_claro\" colspan=\"3\" width=\"55%\" >\n";
		$html .= "					".$cuenta['plan_descripcion']."\n";
		$html .= "				</td>\n";
		$html .= "				<td width=\"15%\" >\n";
		$html .= "					<b>INGRESO</b>\n";
		$html .= "				</td>\n";
		$html .= "				<td width=\"10%\" class=\"modulo_list_claro\" align=\"right\">\n";
		$html .= "					".$cuenta['ingreso']."\n";
		$html .= "				</td>\n";
		$html .= "			</tr>\n";
		$html .= "			<tr $estilo>\n";
		$html .= "				<td >\n";
		$html .= "					<b>PACIENTE</b>\n";
		$html .= "				</td>\n";
		$html .= "				<td class=\"modulo_list_claro\" colspan=\"5\">\n";
		$html .= "					".$cuenta['tipo_id_paciente']." ".$cuenta['paciente_id']." - \n";
		$html .= "					".$cuenta['paciente']."\n";
		$html .= "				</td>\n";
		$html .= "			</tr>\n";
		$html .= "			<tr $estilo>\n";
		$html .= "				<td >\n";
		$html .= "					<b>COPAGO</b>\n";
		$html .= "				</td>\n";
		$html .= "				<td class=\"modulo_list_claro\" align=\"right\" width=\"16%\" >\n";
		$html .= "					$".formatoValor($cuenta['valor_cuota_paciente'])."\n";
		$html .= "				</td>\n";
		$html .= "				<td width=\"23%\" >\n";
		$html .= "					<b>C. MODERADORA</b>\n";
		$html .= "				</td >\n";
		$html .= "				<td class=\"modulo_list_claro\" align=\"right\" width=\"16%\">\n";
		$html .= "					$".formatoValor($cuenta['valor_cuota_moderadora'])."\n";
		$html .= "				</td>\n";
		$html .= "				<td >\n";
		$html .= "					<b>T. EMPRESA</b>\n";
		$html .= "				</td>\n";
		$html .= "				<td class=\"modulo_list_claro\" align=\"right\">\n";
		$html .= "					$".formatoValor($cuenta['valor_total_empresa'])."\n";
		$html .= "				</td>\n";
		$html .= "			</tr>\n";
		$html .= "		</table><br>\n";
		
		if(sizeof($Cargos) > 0)
		{
			$html .= "	<table width=\"100%\" align=\"center\">\n";
			$html .= "		<tr><td>\n";
			$html .= "			<fieldset class=\"fieldset\" ><legend class=\"normal_10AN\">CARGOS</legend>\n";
			$html .= "				<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "					<tr class=\"modulo_table_list_title\">\n";
			$html .= "						<td width=\"9%\">FECHA</td>\n";
			$html .= "						<td width=\"9%\">TRANS.</td>\n";
			$html .= "						<td width=\"16%\" colspan=\"2\">CARGO - TARIF.</td>\n";
			$html .= "						<td width=\"46%\">DESCRIPCIÓN</td>\n";
			$html .= "						<td width=\"10%\">V. CUBIE.</td>\n";
			$html .= "						<td width=\"10%\">V. CARGO</td>\n";
			$html .= "					</tr>";
			
			$Agrupado = "";
			foreach($Cargos as $key => $cargodetalle)
			{			
				if($cargodetalle['agrupado'] != $Agrupado)
				{
					$ActoQx = $ctd->ObtenerActoQuirurgico($cargodetalle['transaccion'],$cargodetalle['agrupado']);
					if($ActoQx)
					{
						if($cargodetalle['agrupado']) $ActoQx = "PROCEDIMIENTO QUIRÚRGICO: ".$ActoQx;
						
						$html .= "		<tr class=\"formulacion_table_list\" height=\"17\">\n";
						$html .= "			<td colspan=\"10\">$ActoQx</td>\n";
						$html .= "		</tr>\n";
					}
				}

				$html .= "					<tr height=\"17\" class=\"modulo_list_claro\">\n";
				$html .= "						<td align=\"center\" >".$cargodetalle['fecha']."</td>\n";
				$html .= "						<td align=\"center\" >".$cargodetalle['transaccion']."</td>\n";
				$html .= "						<td align=\"center\" width=\"8%\">".$cargodetalle['cargo_cups']."</td>\n";
				$html .= "						<td align=\"center\" width=\"8%\">".$cargodetalle['tarifario_id']."</td>\n";
				$html .= "						<td align=\"justify\">".$cargodetalle['descripcion']."</td>\n";
				$html .= "						<td align=\"right\" >$".formatoValor($cargodetalle['valor_cubiert'])."</td>\n";
				$html .= "						<td align=\"right\" >$".formatoValor($cargodetalle['valor_cargo'])."</td>\n";
				$html .= "					</tr>\n";
			}

			$html .= "				</table>\n";
			$html .= "			</fieldset>\n";
			$html .= "		</td></tr>\n";
			$html .= "	</table>\n";
		}
						
		if(sizeof($Insumos) > 0)
		{
			$html .= "	<table width=\"100%\" align=\"center\">\n";
			$html .= "		<tr><td>\n";
			$html .= "			<fieldset class=\"fieldset\"><legend class=\"normal_10AN\">INSUMOS</legend>\n";
			$html .= "				<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";		
			$html .= "					<tr class=\"modulo_table_list_title\">\n";
			$html .= "						<td width=\"19%\"><b>CODIGO PRODUCTO</b></td>\n";
			$html .= "						<td width=\"16%\"><b>CANTIDAD</b></td>\n";
			$html .= "						<td width=\"46%\"><b>DESCRIPCION</b></td>\n";
			$html .= "						<td width=\"10%\"><b>V. CUBIE.</b></td>\n";
			$html .= "						<td width=\"10%\"><b>V. CARGO</b></td>\n";
			$html .= "					</tr>";
			
			foreach($Insumos as $key => $insumo)
			{						
				$html .= "					<tr class=\"modulo_list_oscuro\">\n";
				$html .= "						<td align=\"center\" >".$insumo['codigo_producto']."</td>\n";
				$html .= "						<td align=\"center\" >".$insumo['cantidad']."</td>\n";
				$html .= "						<td align=\"justify\" >".$insumo['descripcion']."</td>\n";
				$html .= "						<td align=\"right\"  >$".formatoValor($insumo['valor_cubierto'])."</td>\n";
				$html .= "						<td align=\"right\"  >$".formatoValor($insumo['valor_cargo'])."</td>\n";
				$html .= "					</tr>\n";
			}
			$html .= "				</table>\n";
			$html .= "			</fieldset>\n";
			$html .= "		</td></tr>\n";
			$html .= "	</table>\n";
		}
		
		$html .= "	</fieldset><br>\n";
		if($flag === false)
			$html .= "	<a href=\"javascript:MostrarDetalleFactura('".$cuenta['prefijo']."','".$cuenta['factura_fiscal']."','".$empresa."')\" class=\"label_error\">VOLVER</a>&nbsp;&nbsp;&nbsp;\n";
		
		$html .= "	<a href=\"javascript:OcultarSpan('Facturas')\" class=\"label_error\">CERRAR</a>\n";
		$html .= "</center>\n";
		$html .= "<br>\n";
		
		$html = utf8_encode( $html );
		$objResponse->assign("ContenidoB","innerHTML",$html);
		$objResponse->call("MostrarDetalle");
		
		return $objResponse;
	}
	/****
	 *
	 */
	function ListaCuentasFactura($prefijo,$factura_fiscal,$cuentas,$empresa)
	{
		$html .= "<br>\n";
		$html .= "<center>\n";
		$html .= "	<fieldset class=\"fieldset\" style=\"width:96%\">\n";
		$html .= "		<legend class=\"normal_10AN\">LISTADO DE CUENTAS DE LA FACTURA Nº ".$prefijo." ".$factura_fiscal."</legend>\n";
		$html .= "		<table width=\"98%\" align=\"center\" class=\"modulo_table_list\" >\n";
		$html .= "			<tr class=\"modulo_table_list_title\" >\n";
		$html .= "				<td width=\"1%\"></td>\n";
		$html .= "				<td width=\"15%\">Nº CUENTA</td>\n";
		$html .= "				<td width=\"15%\">FECHA</td>\n";
		$html .= "				<td width=\"15%\">TOTAL</td>\n";
		$html .= "				<td width=\"%\">PLAN</td>\n";
		$html .= "			</tr>\n";

		foreach($cuentas as $key => $cuenta)
		{
			$html .= "			<tr class=\"modulo_list_claro\">\n";
			$html .= "				<td align=\"center\">\n";
			$html .= "					<a href=\"javascript:MostrarInformacionCuenta('".$key."','".$empresa."')\" title=\"INFORMACIÓN CUENTAS\">\n";
			$html .= "						<img src=\"".GetThemePath()."/images/Listado.png\" border=\"0\">\n";
			$html .= "					</a>\n";
			$html .= "				</td>\n";
			$html .= "				<td class=\"normal_10AN\" >".$cuenta['numerodecuenta']."</td>\n";
			$html .= "				<td align=\"center\">".$cuenta['fecha']."</td>\n";
			$html .= "				<td align=\"right\" class=\"label\">$".formatoValor($cuenta['total_cuenta'])."</td>\n";
			$html .= "				<td class=\"normal_10AN\">".$cuenta['plan_descripcion']."</td>\n";
			$html .= "			</tr>\n";
		}
		$html .= "		</table><br>\n";
		$html .= "	</fieldset><br>\n";
		$html .= "	<a href=\"javascript:OcultarSpan('Facturas')\" class=\"label_error\">CERRAR</a>\n";
		$html .= "</center>\n";
		$html .= "<br>\n";
		
		$html = utf8_encode( $html );

		return $html;
	}
	/****
	 *
	 */
	function InformacionGlosa($prefijo,$factura_fiscal,$empresa,$sistema)
	{
		$objResponse = new xajaxResponse();
		$ctd = new CarteraDetalle();
		$glosas = $ctd->ObtenerGlosasFactura($prefijo,$factura_fiscal,$empresa);
		
		if(sizeof($glosas) > 1)
		{
			$html = ListaGlosas($glosas,$prefijo,$factura_fiscal,$empresa,$sistema);
			$objResponse->assign("ContenidoB","innerHTML",$html);
			$objResponse->call("MostrarDetalle");
		}
		else
			$objResponse = InformacionGlosaDetalle($empresa,key($glosas),$sistema,true);
		
		return $objResponse;
	}
	/****
	 *
	 */
	function InformacionRecibo($prefijo,$factura_fiscal,$empresa)
	{
		$objResponse = new xajaxResponse();
		$ctd = new CarteraDetalle();
		$datos = $ctd->ObtenerRecibos($prefijo,$factura_fiscal,$empresa);
		
		$html = ListaRecibos($datos,$prefijo,$factura_fiscal,$empresa,$sistema);
		$objResponse->assign("ContenidoB","innerHTML",$html);
		$objResponse->call("MostrarDetalle");
		
		return $objResponse;
	}
	/****
	 *
	 */
	function InformacionNota($prefijo,$factura_fiscal,$empresa)
	{
		$objResponse = new xajaxResponse();
		$ctd = new CarteraDetalle();
		$datos = $ctd->ObtenerNotas($prefijo,$factura_fiscal,$empresa);
		
		$html = ListaNotas($datos,$prefijo,$factura_fiscal,$empresa,$sistema);
		$objResponse->assign("ContenidoB","innerHTML",$html);
		$objResponse->call("MostrarDetalle");
		
		return $objResponse;
	}
	/****
	 *
	 */
	function InformacionGlosaDetalle($empresa,$glosaid,$sistema,$flag = false)
	{
		$objResponse = new xajaxResponse();
		$ctd = new CarteraDetalle();
		$glosa = $ctd->ObtenerInformacionGlosa($empresa,$glosaid,$sistema);
	
		$estilo = "class=\"modulo_table_list_title\" style=\"text-indent:4pt;text-align:left\"";
		$html .= "<br>\n";
		$html .= "<center>\n";
		$html .= "	<fieldset class=\"fieldset\" style=\"width:96%\">\n";
		$html .= "		<legend class=\"normal_10AN\">INFORMACION DE LA GLOSA Nº ".$glosaid."</legend>\n";
		$html .= "		<table align=\"center\" cellpading=\"0\" width=\"80%\" border=\"0\" class=\"modulo_table_list\">\n";
		$html .= "			<tr $estilo>\n";
		$html .= "				<td width=\"25%\"><b>FACTURA</b></td>\n";
		$html .= "				<td width=\"25%\" class=\"modulo_list_claro\">".$glosa['prefijo']." ".$glosa['factura_fiscal']."</td>\n";
		$html .= "				<td width=\"25%\"><b>TOTAL FACTURA</b></td>\n";
		$html .= "				<td width=\"25%\" class=\"modulo_list_claro\">\n";
		$html .= "						$".formatoValor($glosa['total_factura'])."\n";
		$html .= "				</td>\n";
		$html .= "			</tr>\n";
		$html .= "			<tr $estilo>\n";
		$html .= "				<td >F. REGISTRO</td>\n";
		$html .= "				<td class=\"modulo_list_claro\">".$glosa['fecha_glosa']."</td>\n";
		$html .= "				<td>ESTADO ACTUAL</b></td>\n";
		$html .= "				<td class=\"modulo_list_claro\">\n";
		$html .= "					<label class=\"normal_10AN\">".$glosa['estado_glosa']."</label>\n";
		$html .= "				</td>\n";
		$html .= "			</tr>\n";
		$html .= "			<tr $estilo>\n";
		$html .= "				<td >V. GLOSA</td>\n";
		$html .= "				<td class=\"modulo_list_claro\" align=\"right\">\n";
		$html .= "					$".formatoValor($glosa['valor_glosa'])."\n";
		$html .= "				</td>\n";
		$html .= "				<td >V. ACEPTADO</td>\n";
		$html .= "				<td class=\"modulo_list_claro\" align=\"right\">\n";
		$html .= "					$".formatoValor($glosa['valor_aceptado'])."\n";
		$html .= "				</td>\n";
		$html .= "			</tr>\n";
		$html .= "			<tr $estilo>\n";
		$html .= "				<td >V. NO ACEPTADO</td>\n";
		$html .= "				<td class=\"modulo_list_claro\" align=\"right\">\n";
		$html .= "					$".formatoValor($glosa['valor_no_aceptado'])."\n";
		$html .= "				</td>\n";
		$html .= "				<td><b>V. PENDIENTE</td>\n";
		$html .= "				<td class=\"modulo_list_claro\" align=\"right\">\n";
		$html .= "					$".formatoValor($glosa['valor_pendiente'])."\n";
		$html .= "				</td>\n";
		$html .= "			</tr>\n";
		$html .= "			<tr $estilo>\n";
		$html .= "				<td ><b>RESPONSABLE</b></td>\n";
		$html .= "				<td colspan=\"3\" class=\"modulo_list_claro\" >\n";
		$html .= "					".$glosa['nombre']."\n";
		$html .= "				</td>\n";
		$html .= "			</tr>\n";
		
		if($glosa['clasificacion'] != "")
		{
			$html .= "			<tr $estilo>\n";
			$html .= "				<td >CLASIFICACIÓN</td>\n";
			$html .= "				<td colspan=\"3\" class=\"modulo_list_claro\">\n";
			$html .= "					".$glosa['clasificacion']."\n";
			$html .= "				</td>\n";
			$html .= "			</tr>\n";
		}
		if($glosa['auditor'] != "")
		{
			$html .= "			<tr $estilo>\n";
			$html .= "				<td ><b>AUDITOR</b></td>\n";
			$html .= "				<td colspan=\"3\" class=\"modulo_list_claro\">\n";
			$html .= "					".$glosa['auditor']."\n";
			$html .= "				</td>\n";
			$html .= "			</tr>\n";
		}

		if($glosa['documento_interno_cliente_id'] != "")
		{
			$html .= "			<tr $estilo>\n";
			$html .= "				<td ><b>DOCUMENTO INTERNO DEL CLIENTE Nº</b></td>\n";
			$html .= "				<td colspan=\"3\" class=\"modulo_list_claro\">\n";
			$html .= "					".$glosa['documento_interno_cliente_id']."\n";
			$html .= "				</td>\n";
			$html .= "			</tr>\n";
		}
				
		if($glosa['motivo_glosa_descripcion'] != "")
		{
			$html .= "			<tr class=\"modulo_table_list_title\">\n";
			$html .= "				<td colspan=\"4\">MOTIVO DE LA GLOSA</td>\n";
			$html .= "			</tr>\n";
			$html .= "			<tr>\n";
			$html .= "				<td class=\"modulo_list_claro\" colspan= \"4\">";
			$html .= "					<b>".$glosa['motivo_glosa_descripcion']."</b>\n";
			$html .= "				</td>\n";
			$html .= "			</tr>\n";
		}
		if($glosa['observacion'] != "")
		{
			$html .= "			<tr class=\"modulo_table_list_title\">\n";
			$html .= "				<td colspan= \"4\"><b>OBSERVACIÓN</b></td>\n";
			$html .= "			</tr>\n";
			$html .= "			<tr>\n";
			$html .= "				<td class=\"modulo_list_claro\" colspan= \"4\">";
			$html .= "					<b>".$glosa['observacion']."</b>\n";
			$html .= "				</td>\n";
			$html .= "			</tr>\n";
		}
		$html .= "		</table><br>\n";
		
		if($glosa['sw_glosa_total_factura'] == '0')
		{ 				
			$cargos = $ctd->ObtenerInformacionGlosaCargos($glosaid);	
			$html .= "		<table align=\"center\" cellpading=\"0\" width=\"100%\" class=\"modulo_table_list\">\n";
			foreach($cargos as $key => $cuentas)
			{
				if($key == 'DC' || $key == 'DI')
				{
					$html .= "			<tr class=\"formulacion_table_list\">\n";
					$html .= "				<td colspan=\"10\" align=\"center\" >\n";
					($key == 'DC') ? $html .= "CARGOS":$html .= "INSUMOS Y MEDICAMENTOS";

					$html .= "				</td>\n";
					$html .= "			</tr>\n";
				}
								
				foreach($cuentas as $keyI => $detalle)
				{
					$Motivo = "";
					foreach($detalle as $keyX => $item)
					{
						switch($key)
						{
							case 'DT':
								$html .= "			<tr class=\"modulo_table_list_title\">\n";
								$html .= "				<td width=\"10%\" ><b>Nº CUENTA: </b></td>\n";
								$html .= "				<td width=\"17%\" class=\"modulo_list_claro\" align=\"center\">".$item['numerodecuenta']."</td>\n";
								$html .= "				<td width=\"10%\" ><b>V. GLOSA</b></td>\n";
								$html .= "				<td width=\"10%\" class=\"modulo_list_claro\" align=\"right\">".formatoValor($item['valor_glosa'])."&nbsp;&nbsp;</td>\n";
								$html .= "				<td width=\"11%\" ><b>V. ACEPTADO</b></td>\n";
								$html .= "				<td width=\"10%\" class=\"modulo_list_claro\" align=\"right\">".formatoValor($item['valor_aceptado'])."&nbsp;&nbsp;</td>\n";
								$html .= "				<td width=\"12%\" ><b>V. NO ACEPTADO</b></td>\n";
								$html .= "				<td width=\"10%\" class=\"modulo_list_claro\" align=\"right\">".formatoValor($item['valor_no_aceptado'])."&nbsp;&nbsp;</td>\n";
								$html .= "			</tr>\n";
								$html .= "			<tr>\n";
								$html .= "				<td class=\"modulo_table_list_title\" colspan=\"2\"><b $estilo>MOTIVO DE GLOSA</b></td>\n";
								$html .= "				<td class=\"modulo_list_claro\" colspan=\"6\" width=\"80%\">".$item['motivo_glosa_descripcion']."</td>\n";
								$html .= "			</tr>\n";
							break;
								
							case 'DA':
								$html .= "			<tr>\n";
								$html .= "				<td $estilo width=\"10%\">Nº CUENTA:</td>\n";
								$html .= "				<td class=\"modulo_list_claro\" width=\"17%\" align=\"center\">".$item['numerodecuenta']."</td>\n";
								if($item['valor_glosa'] > 0)
								{
									$html .= "				<td width=\"10%\" class=\"modulo_table_list_title\" align=\"center\"><b>V. GLOSA</b></td>\n";
									$html .= "				<td width=\"10%\" class=\"modulo_list_claro\" align=\"right\">".formatoValor($item['valor_glosa'])."&nbsp;&nbsp;</td>\n";
									$html .= "				<td width=\"11%\" class=\"modulo_table_list_title\" align=\"center\"><b>V. ACEPTADO</b></td>\n";
									$html .= "				<td width=\"10%\" class=\"modulo_list_claro\" align=\"right\">".formatoValor($item['valor_aceptado'])."&nbsp;&nbsp;</td>\n";
									$html .= "				<td width=\"12%\" class=\"modulo_table_list_title\" align=\"center\"><b>V. NO ACEPTADO</b></td>\n";
									$html .= "				<td width=\"10%\" class=\"modulo_list_claro\" align=\"right\">".formatoValor($item['valor_no_aceptado'])."&nbsp;&nbsp;</td>\n";
								}
								else
									$html .= "				<td class=\"modulo_list_claro\" colspan=\"6\" width=\"50%\"></td>\n";
																
								$html .= "			</tr>\n";
								if($item['motivo_glosa_descripcion'] != "")
								{
									$html .= "			<tr>\n";
									$html .= "				<td class=\"modulo_table_list_title\" colspan=\"2\" align=\"center\"><b>MOTIVO DE GLOSA:</b></td>\n";
									$html .= "				<td class=\"modulo_list_claro\" colspan=\"6\" width=\"80%\">".$item['motivo_glosa_descripcion']."</td>\n";
									$html .= "			</tr>\n";
								}
							break;
							case 'DC':
								
								if($Motivo != $item['motivo_glosa_descripcion'])
								{
									$html .= "			<tr>\n";
									$html .= "				<td  $estilo colspan=\"2\" align=\"center\">MOTIVO DE GLOSA</td>\n";
									$html .= "				<td  class=\"modulo_list_claro\" colspan=\"6\" width=\"80%\">".$item['motivo_glosa_descripcion']."</td>\n";
									$html .= "			</tr>\n";
									$Motivo = $item['motivo_glosa_descripcion'];
								}

								$html .= "			<tr>\n";
								$html .= "				<td width=\"10%\" $estilo >CARGO</td>\n";
								$html .= "				<td width=\"17%\" class=\"modulo_list_claro\" align=\"center\"   >".$item['cargo']."</td>\n";
								$html .= "				<td width=\"10%\" class=\"modulo_table_list_title\" >V. GLOSA</td>\n";
								$html .= "				<td width=\"10%\" class=\"modulo_list_claro\" align=\"right\">".formatoValor($item['valor_glosa'])."&nbsp;&nbsp;</td>\n";
								$html .= "				<td width=\"11%\" class=\"modulo_table_list_title\" ><b>V. ACEPTADO</b></td>\n";
								$html .= "				<td width=\"10%\" class=\"modulo_list_claro\" align=\"right\">".formatoValor($item['valor_aceptado'])."&nbsp;&nbsp;</td>\n";
								$html .= "				<td width=\"12%\" class=\"modulo_table_list_title\" ><b>V. NO ACEPT.</b></td>\n";
								$html .= "				<td width=\"10%\" class=\"modulo_list_claro\" align=\"right\">".formatoValor($item['valor_no_aceptado'])."&nbsp;&nbsp;</td>\n";
								$html .= "			</tr>\n";
							break;
							case 'DI':
								if($Motivo != $item['motivo_glosa_descripcion'])
								{
									$Motivo = $item['motivo_glosa_descripcion'];
									$html .= "			<tr>\n";
									$html .= "				<td $estilo colspan=\"2\" align=\"center\">MOTIVO DE GLOSA</td>\n";
									$html .= "				<td class=\"modulo_list_claro\" colspan=\"6\" width=\"80%\">".$item['motivo_glosa_descripcion']."</td>\n";
									$html .= "			</tr>\n";
								}
								
								$html .= "			<tr>\n";
								$html .= "				<td $estilo width=\"10%\" align=\"center\">PRODUCTO</td>\n";
								$html .= "				<td width=\"17%\" class=\"modulo_list_claro\" align=\"center\">".$item['cargo']."</td>\n";
								$html .= "				<td width=\"10%\" class=\"modulo_table_list_title\" align=\"center\">V. GLOSA</td>\n";
								$html .= "				<td width=\"10%\" class=\"modulo_list_claro\" align=\"right\">".formatoValor($item['valor_glosa'])."&nbsp;&nbsp;</td>\n";
								$html .= "				<td width=\"11%\" class=\"modulo_table_list_title\" align=\"center\"><b>V. ACEPTADO</b></td>\n";
								$html .= "				<td width=\"10%\" class=\"modulo_list_claro\" align=\"right\">".formatoValor($item['valor_aceptado'])."&nbsp;&nbsp;</td>\n";
								$html .= "				<td width=\"12%\" class=\"modulo_table_list_title\" align=\"center\"><b>V. NO ACEPT.</b></td>\n";
								$html .= "				<td width=\"10%\" class=\"modulo_list_claro\" align=\"right\">".formatoValor($item['valor_no_aceptado'])."&nbsp;&nbsp;</td>\n";
								$html .= "			</tr>\n";
							break;
						}
					}
				}
			}
			$html .= "		</table><br>\n";
		}
		$html .= "	</fieldset><br>\n";
		if($flag === false)
			$html .= "	<a href=\"javascript:MostrarDetalleGlosa('".$glosa['prefijo']."','".$glosa['factura_fiscal']."','".$empresa."','".$sistema."')\" class=\"label_error\">VOLVER</a>&nbsp;&nbsp;&nbsp;\n";
		
		$html .= "	<a href=\"javascript:OcultarSpan('Facturas')\" class=\"label_error\">CERRAR</a>\n";
		$html .= "</center>\n";
		$html .= "<br>\n";
		
		$html = utf8_encode( $html );
		$objResponse->assign("ContenidoB","innerHTML",$html);
		$objResponse->call("MostrarDetalle");
		
		return $objResponse;
	}
	/****
	 *
	 */	
	function ListaGlosas($glosas,$prefijo,$factura_fiscal,$empresa,$sistema)
	{
		$html .= "<br>\n";
		$html .= "<center>\n";
		$html .= "	<fieldset class=\"fieldset\" style=\"width:96%\">\n";
		$html .= "		<legend class=\"normal_10AN\">LISTADO DE CUENTAS DE LA FACTURA Nº ".$prefijo." ".$factura_fiscal."</legend>\n";
		$html .= "		<table width=\"98%\" align=\"center\" class=\"modulo_table_list\" >\n";
		$html .= "			<tr class=\"modulo_table_list_title\" height=\"18\">\n";
		$html .= "				<td width=\"1%\"></td>\n";
		$html .= "				<td width=\"10%\">Nº GLOSA</td>\n";
		$html .= "				<td width=\"14%\">FECHA GLOSA</td>\n";
		$html .= "				<td width=\"14%\">V. GLOSA</td>\n";
		$html .= "				<td width=\"15%\">V. ACEPTADO</td>\n";
		$html .= "				<td width=\"17%\">V. NO ACEPTADO</td>\n";
		$html .= "				<td width=\"20%\">ESTADO</td>\n";
		$html .= "			</tr>\n";
		
		foreach($glosas as $key => $glosa)
		{			
			$html .= "			<tr class=\"modulo_list_claro\">\n";			
			$html .= "				<td align=\"center\" >\n";
			$html .= "					<a href=\"javascript:MostrarGlosa('".$key."','".$empresa."','".$sistema."')\" title=\"INFORMACION DE LA GLOSA\">\n";
			$html .= "						<img src=\"".GetThemePath()."/images/pconsultar.png\" border=\"0\">\n";
			$html .= "					</a>\n";
			$html .= "				</td>\n";			
			$html .= "				<td align=\"right\" ><b>".$glosa['glosa_id']."</b></td>\n";
			$html .= "				<td align=\"center\">".$glosa['fecha']."</td>\n";
			$html .= "				<td align=\"right\" >$".formatoValor($glosa['valor_glosa'])."</td>\n";
			$html .= "				<td align=\"right\" >$".formatoValor($glosa['valor_aceptado'])."</td>\n";
			$html .= "				<td align=\"right\" >$".formatoValor($glosa['valor_no_aceptado'])."</td>\n";
			$html .= "				<td align=\"center\" class=\"normal_10AN\">\n";
			$html .= "					".$glosa['estado']."\n";
			$html .= "				</td>\n";
			$html .= "			</tr>\n";
		}
		$html .= "		</table><br>\n";
		$html .= "	</fieldset><br>\n";
		$html .= "	<a href=\"javascript:OcultarSpan('Facturas')\" class=\"label_error\">CERRAR</a>\n";
		$html .= "</center>\n";
		$html .= "<br>\n";

		$html = utf8_encode( $html );
		
		return $html;
	}
	/**
	*
	*/
	function ListaRecibos($datos,$prefijo,$factura_fiscal)
	{
		$html .= "<br>\n";
		$html .= "<center>\n";
		$html .= "	<fieldset class=\"fieldset\" style=\"width:96%\">\n";
		$html .= "		<legend class=\"normal_10AN\">LISTADO DE RECIBOS DE LA FACTURA Nº ".$prefijo." ".$factura_fiscal."</legend>\n";

		$html .= "<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
		$html .= "	<tr class=\"formulacion_table_list\">\n";
		$html .= "		<td width=\"15%\" ><b>Nº DOC</b></td>\n";
		$html .= "		<td width=\"15%\" ><b>FECHA</b></td>\n";
		$html .= "		<td width=\"20%\" ><b>TIPO PAGO</b></td>\n";
		$html .= "		<td width=\"20%\" ><b>V. ABONADO</b></td>\n";
		$html .= "		<td width=\"20%\" ><b>V. DOCUMENTO</b></td>\n";
		$html .= "	</tr>\n";
		
		foreach($datos as $key => $recibo)
		{
			$html .= "			<tr class=\"modulo_list_claro\">\n";
			$html .= "				<td class=\"label\">".$recibo['prefijo']." ".$recibo['recibo_caja']."</td>\n";
			$html .= "				<td class=\"label\" align=\"center\">".$recibo['fecha_registro']."</td>\n";
			$html .= "				<td class=\"normal_10AN\">".$recibo['forma_pago']."</td>\n";
			$html .= "				<td class=\"label\" align=\"right\">".formatoValor($recibo['abono'])."</td>\n";
			$html .= "				<td class=\"label\" align=\"right\">".formatoValor($recibo['valor']+$recibo['total_abono'])."</td>\n";
			$html .= "			</tr>\n";
		}
		$html .= "		</table>\n";
		$html .= "	</fieldset><br>\n";
		$html .= "	<a href=\"javascript:OcultarSpan('Facturas')\" class=\"label_error\">CERRAR</a>\n";
		$html .= "</center>\n";
		$html .= "<br>\n";
		$html = utf8_encode( $html );
		return $html;
	}
		/**
	*
	*/
	function ListaNotas($datos,$prefijo,$factura_fiscal)
	{
		$html .= "<br>\n";
		$html .= "<center>\n";
		$html .= "	<fieldset class=\"fieldset\" style=\"width:96%\">\n";
		$html .= "		<legend class=\"normal_10AN\">LISTADO DE NOTAs DE LA FACTURA Nº ".$prefijo." ".$factura_fiscal."</legend>\n";

		$html .= "<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
		$html .= "	<tr class=\"formulacion_table_list\">\n";
		$html .= "		<td width=\"10%\" ><b>Nº DOC</b></td>\n";
		$html .= "		<td width=\"10%\" ><b>FECHA</b></td>\n";
		$html .= "		<td width=\"10%\" ><b>TIPO NOTA</b></td>\n";
		$html .= "		<td width=\"%\" ><b>CONCEPTO</b></td>\n";
		$html .= "		<td width=\"20%\" ><b>V. NOTA</b></td>\n";
		$html .= "	</tr>\n";
		
		foreach($datos as $key => $notas)
		{
			foreach($notas as $keyI => $recibo)
			{
				$html .= "			<tr class=\"modulo_list_claro\">\n";
				$html .= "				<td class=\"label\">".$recibo['prefijo']." ".$recibo['numero']."</td>\n";
				$html .= "				<td class=\"label\" align=\"center\">".$recibo['fecha']."</td>\n";
				$html .= "				<td class=\"normal_10AN\">".$recibo['tipo']."</td>\n";
				$html .= "				<td class=\"normal_10AN\">".$recibo['descripcion']."</td>\n";
				$html .= "				<td class=\"label\" align=\"right\">".formatoValor($recibo['valor'])."</td>\n";
				$html .= "			</tr>\n";
			}
		}
		$html .= "		</table>\n";
		$html .= "	</fieldset><br>\n";
		$html .= "	<a href=\"javascript:OcultarSpan('Facturas')\" class=\"label_error\">CERRAR</a>\n";
		$html .= "</center>\n";
		$html .= "<br>\n";
		$html = utf8_encode( $html );
		return $html;
	}
?>