<?php
	/*********************************************************************************************
	* $Id: CajaHTML.class.php,v 1.7 2010/11/29 14:05:53 hugo Exp $
	*
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* $Revision: 1.7 $
	*
	* @autor Hugo F. Manrique
	***********************************************************************************************/
	class CajaHTML
	{
		function CajaHTML(){}
		/**********************************************************************************
		* Funcion que muesta la interface, para la realizacion de los pagos
		* @params array $cuentas Arreglo de datos de las cuentas
		* @params array $action Arerglo con los links de los botones y/o imagenes
		*
		* @return String $html Html de la forma 
		************************************************************************************/
		function FormaPagos($cuenta,$action)
		{
			$html = "";
			$est = "style=\"text-align:left;text-indent:4pt\"";
			$html .= "<script>\n";
			$html .= "	function EvaluarDatos(frm)\n";
			$html .= "	{\n";
			$html .= "		v_total = document.getElementById('total').innerHTML;\n";
			$html .= "		if(v_total*1 < ".$cuenta['valor_total_paciente'].")\n";
			$html .= "		{\n";
			$html .= "			document.getElementById('error').innerHTML = 'SE DEBE CUBRIR EL VALOR TOTAL A PAGAR ';\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		frm.action = '".$action['aceptar']."';\n";
			$html .= "		frm.submit();\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			$html .= "<center>\n";
			$html .= "	<form name=\"realizar_pagos\" action=\"javascript:EvaluarDatos(document.realizar_pagos)\" method=\"post\">\n";
			$html .= "		<fieldset class=\"fieldset\" style=\"width:50%;padding:8pt\"><legend class=\"normal_10AN\">REGISTRO DE PAGOS</legend>\n";
			$html .= "			<div id=\"error\" class=\"label_error\"></div>\n";
			$html .= "			<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "				<tr height=\"19\" class=\"modulo_table_list_title\">\n";
			$html .= "					<td colspan=\"3\">VALOR TOTAL A PAGAR: $".formatoValor($cuenta['valor_total_paciente'])."</td>\n";
			$html .= "				</tr>\n";
			$html .= "				<tr class=\"modulo_table_list_title\">\n";
			$html .= "					<td $est width=\"50%\" >PAGO EN EFECTIVO</td>\n";
			$html .= "					<td style=\"text-align:right\" class=\"modulo_list_claro\">\n";
			//$html .= "						$<label class=\"normal_10AN\" id=\"efectivo\">0</label>\n";
			$html .= "						$<label class=\"normal_10AN\" id=\"efectivo\">".$cuenta['valor_total_paciente']."</label>\n";
			//$html .= "						<input type=\"hidden\"  id=\"h_efectivo\" name=\"h_efectivo\" value=\"0\">\n";
			$html .= "						<input type=\"hidden\"  id=\"h_efectivo\" name=\"h_efectivo\" value=\"".$cuenta['valor_total_paciente']."\">\n";
			$html .= "					</td>\n";
			$html .= "					<td width=\"1%\" class=\"modulo_list_claro\">\n";
			$html .= "						<a title=\"REALIZAR PAGOS EN EFECTIVO\" href=\"".$action['pagar'].UrlRequest(array("opcion"=>"E","label"=>"efectivo",'valor_total_paciente'=>$cuenta['valor_total_paciente']))."\" target=\"pagar\" onclick=\"window.open('".$action['pagar'].UrlRequest(array("opcion"=>"E","label"=>"efectivo",'valor_total_paciente'=>$cuenta['valor_total_paciente']))."','pagar','toolbar=no,width=600,height=350,resizable=no,scrollbars=yes').focus(); return false;\" class=\"label_error\">\n";
			$html .= "							<img src=\"".GetThemePath()."/images/plata.png\" border=\"0\">\n";
			$html .= "						</a>\n";
			$html .= "					</td>\n";
			$html .= "				</tr>\n";
			$html .= "				<tr class=\"modulo_table_list_title\">\n";
			$html .= "					<td $est >PAGO CON CHEQUE</td>\n";
			$html .= "					<td style=\"text-align:right\" class=\"modulo_list_claro\">\n";
			$html .= "						$<label class=\"normal_10AN\" id=\"cheque\">0</label>\n";
			$html .= "					</td>\n";
			$html .= "					<td class=\"modulo_list_claro\">\n";
			$html .= "						<a title=\"REALIZAR PAGOS CON CHEQUE\" href=\"".$action['pagar'].UrlRequest(array("opcion"=>"C","label"=>"cheque"))."\" target=\"pagar\" onclick=\"window.open('".$action['pagar'].UrlRequest(array("opcion"=>"C","label"=>"cheque"))."','pagar','toolbar=no,width=700,height=350,resizable=no,scrollbars=yes').focus(); return false;\" class=\"label_error\">\n";
			$html .= "							<img src=\"".GetThemePath()."/images/plata.png\" border=\"0\">\n";
			$html .= "						</a>\n";			
			$html .= "					</td>\n";			
			$html .= "				</tr>\n";
			$html .= "				<tr class=\"modulo_table_list_title\">\n";
			$html .= "					<td $est>PAGO CON TARJETA CREDITO</td>\n";
			$html .= "					<td style=\"text-align:right\" class=\"modulo_list_claro\">\n";
			$html .= "						$<label class=\"normal_10AN\" id=\"credito\">0</label>\n";
			$html .= "					</td>\n";
			$html .= "					<td class=\"modulo_list_claro\">\n";
			$html .= "						<a title=\"REALIZAR PAGOS CON TARJETA CREDITO\" href=\"".$action['pagar'].UrlRequest(array("opcion"=>"R","label"=>"credito"))."\" target=\"pagar\" onclick=\"window.open('".$action['pagar'].UrlRequest(array("opcion"=>"R","label"=>"credito"))."','pagar','toolbar=no,width=700,height=350,resizable=no,scrollbars=yes').focus(); return false;\" class=\"label_error\">\n";
			$html .= "							<img src=\"".GetThemePath()."/images/plata.png\" border=\"0\">\n";
			$html .= "						</a>\n";			
			$html .= "					</td>\n";			
			$html .= "				</tr>\n";
			$html .= "				<tr class=\"modulo_table_list_title\">\n";
			$html .= "					<td $est>PAGO EN TARJETA DEBITO</td>\n";
			$html .= "					<td style=\"text-align:right\" class=\"modulo_list_claro\">\n";
			$html .= "						$<label class=\"normal_10AN\" id=\"debito\">0</label>\n";
			$html .= "					</td>\n";
			$html .= "					<td class=\"modulo_list_claro\">\n";
			$html .= "						<a title=\"REALIZAR PAGOS CON TARJETA DEBITO\" href=\"".$action['pagar'].UrlRequest(array("opcion"=>"D","label"=>"debito"))."\" target=\"pagar\" onclick=\"window.open('".$action['pagar'].UrlRequest(array("opcion"=>"D","label"=>"debito"))."','pagar','toolbar=no,width=700,height=350,resizable=no,scrollbars=yes').focus(); return false;\" class=\"label_error\">\n";
			$html .= "							<img src=\"".GetThemePath()."/images/plata.png\" border=\"0\">\n";
			$html .= "						</a>\n";			
			$html .= "					</td>\n";			
			$html .= "				</tr>\n";
			$html .= "				<tr class=\"modulo_table_list_title\">\n";
			$html .= "					<td $est>PAGO CON BONOS</td>\n";
			$html .= "					<td style=\"text-align:right\" class=\"modulo_list_claro\">\n";
			$html .= "						$<label class=\"normal_10AN\" id=\"bono\">0</label>\n";
			$html .= "						<input type=\"hidden\"  id=\"h_bono\" name=\"h_bono\" value=\"0\">\n";
			$html .= "					</td>\n";
			$html .= "					<td class=\"modulo_list_claro\">\n";
			$html .= "						<a title=\"REALIZAR PAGOS CON BONOS\" href=\"".$action['pagar'].UrlRequest(array("opcion"=>"B","label"=>"bono"))."\" target=\"pagar\" onclick=\"window.open('".$action['pagar'].UrlRequest(array("opcion"=>"B","label"=>"bono"))."','pagar','toolbar=no,width=700,height=350,resizable=no,scrollbars=yes').focus(); return false;\" class=\"label_error\">\n";
			$html .= "							<img src=\"".GetThemePath()."/images/plata.png\" border=\"0\">\n";
			$html .= "						</a>\n";			
			$html .= "					</td>\n";			
			$html .= "				</tr>\n";
			$html .= "				<tr height=\"19\" class=\"modulo_table_list_title\">\n";
			$html .= "					<td $est>TOTAL</td>\n";
			$html .= "					<td style=\"text-align:right\" class=\"modulo_list_claro\">\n";
			//$html .= "						$<label class=\"normal_10AN\" id=\"total\">0</label>\n";
			$html .= "						$<label class=\"normal_10AN\" id=\"total\">".$cuenta['valor_total_paciente']."</label>\n";
			//$html .= "						<input type=\"hidden\"  id=\"h_total\" name=\"h_total\" value=\"0\">\n";
			$html .= "						<input type=\"hidden\"  id=\"h_total\" name=\"h_total\" value=\"".$cuenta['valor_total_paciente']."\">\n";
			$html .= "					</td>\n";
			$html .= "					<td class=\"modulo_list_claro\" ></td>\n";
			$html .= "				</tr>\n";
			$html .= "			</table>\n";
			$html .= "		</fieldset>\n";
			$html .= "		<table align=\"center\" width=\"50%\">\n";
			$html .= "			<tr>\n";
			$html .= "				<td align=\"center\">\n";
			$html .= "						<input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">\n";
			$html .= "				</td>\n";
			$html .= "				</form>\n";
			$html .= "				<form name=\"cancelar\" action=\"".$action['cancelar']."\" method=\"post\">\n";
			$html .= "				<td align=\"center\">\n";
			$html .= "						<input type=\"submit\" class=\"input-submit\" name=\"cancela\" value=\"Cancelar\" >\n";
			$html .= "				</td>\n";
			$html .= "			</tr>\n";
			$html .= "		</table>\n";
			$html .= "	</form>\n";
			$html .= "</center>\n";
			return $html;
		}
		/**********************************************************************************
		*
		* @return 
		************************************************************************************/
		function FormaPagoEfectivo($datos)
		{
			$html = "";
			$html .= "<script>\n";
			$html .= "	function acceptNum(evt)\n";
			$html .= "  {\n";
			$html .= "    var nav4 = window.Event ? true : false;\n";
			$html .= "    var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "    return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$html .= "  }\n";
			$html .= "	function IsNumeric(valor)\n";
			$html .= "	{\n";
			$html .= "		var log = valor.length; \n";
			$html .= "		var sw='S';\n";
			$html .= "		var puntos = 0;\n";
			$html .= "		for (x=0; x<log; x++)\n";
			$html .= "		{ \n";
			$html .= "			v1 = valor.substr(x,1);\n";
			$html .= "			v2 = parseInt(v1);\n";
			$html .= "			//Compruebo si es un valor numérico\n";
			$html .= "			if(v1 == '.')\n";
			$html .= "			{\n";
			$html .= "				puntos ++;\n";
			$html .= "			}\n";
			$html .= "			else if (isNaN(v2)) \n";
			$html .= "			{ \n";
			$html .= "				sw= 'N';\n";
			$html .= "				break;\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "		if(log == 0) sw = 'N';\n";
			$html .= "		if(puntos > 1) sw = 'N';\n";
			$html .= "		if(sw=='S')\n"; 
			$html .= "			return true;\n";
			$html .= "		return false;\n";
			$html .= "	} \n";
			$html .= "  function EvaluarDatos(frm)\n";
			$html .= "	{\n";
			$html .= "		pago = frm.valor.value;\n";
			$html .= "		mensaje = document.getElementById('error');\n";
			$html .= "		mensaje.innerHTML = '';\n";
			$html .= "		if(!IsNumeric(pago))\n";
			$html .= "		{\n";
			$html .= "			mensaje.innerHTML = 'EL VALOR A PAGAR POSSE UN FORMATO DE NUMERO INCORRECTO'\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		v_paciente = ".$datos['valor_pago'].";\n";
			$html .= "		if(v_paciente*1 < pago*1)\n";
			$html .= "		{\n";
			$html .= "			mensaje.innerHTML = 'EL VALOR A PAGAR ES MAYOR AL TOTAL PACIENTE: $'+v_paciente;\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		total = window.opener.document.getElementById('total'); ";
			$html .= "		efect = window.opener.document.getElementById('".$datos['label']."');\n";
			$html .= "		saldo = total.innerHTML*1 - efect.innerHTML*1\n";
			$html .= "		efect.innerHTML = pago\n";
			$html .= "		total.innerHTML = saldo*1 + pago*1\n";
			$html .= "		window.opener.document.getElementById('h_".$datos['label']."').value = pago*1;\n";
			$html .= "		window.opener.document.getElementById('h_total').value = saldo*1 + pago*1;\n";
			$html .= "		window.close();\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			$html .= "<form name=\"efectivo\" action=\"javascript:EvaluarDatos(document.efectivo)\" method=\"post\">\n";
			$html .= "<table width=\"100%\" align=\"center\">\n";
			$html .= "	<tr>\n";
			$html .= "		<td align=\"center\">\n";
			$html .= "			<fieldset style=\"width:80%\" class=\"fieldset\">\n";
			$html .= "				<legend>PAGO EN EFECTIVO</legend>\n";
			$html .= "				<div id=\"error\" class=\"label_error\"></div>\n";
			$html .= "				<table width=\"100%\" align=\"center\">\n";
			$html .= "					<tr class=\"normal_10AN\">\n";
			$html .= "						<td width=\"25%\">VALOR A PAGAR:</td>\n";
			$html .= "						<td>\n";
			$html .= "							$<input type=\"text\" class=\"input-text\" name=\"valor\" style=\"width:60%\" onkeypress=\"return acceptNum(event)\">\n";
			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "				</table>\n";
			$html .= "				<table align=\"center\" width=\"100%\">\n";
			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";
			$html .= "							<input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">\n";
			$html .= "						</td>\n";
			$html .= "						<td align=\"center\">\n";
			$html .= "							<input type=\"button\" class=\"input-submit\" value=\"Cancelar\" onclick=\"window.close()\">\n";
			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "				</table>\n";
			$html .= "			</fieldset>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
			$html .= "</form>\n";
			$html .= "<script>\n";
			$html .= "	pg = window.opener.document.getElementById('h_".$datos['label']."').value;\n";
			$html .= "	document.efectivo.valor.value = pg;\n";
			$html .= "</script>\n";
			return $html;
		}
		/**********************************************************************************
		*
		* @return 
		************************************************************************************/
		function FormaPagoCheque($datos,$action)
		{
			IncludeClass('Caja','','app','Cuentas');
			$cj = new Caja();
			
			$i = 0;
			$html = "";
			$est  = "style=\"text-align:left;padding:4pt\"";
			
			$confirman = $cj->ObtenerEntidadesConfirma();
			$bancos = $cj->ObtenerBancos();
			
			$html .= "<script>\n";
			$html .= "	function acceptNum(evt)\n";
			$html .= "  {\n";
			$html .= "    var nav4 = window.Event ? true : false;\n";
			$html .= "    var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "    return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$html .= "  }\n";
			$html .= "	function acceptDate(evt)\n";
			$html .= "	{\n";
			$html .= "		var nav4 = window.Event ? true : false;\n";
			$html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "		return (key <= 13 ||(key >= 47 && key <= 57));\n";
			$html .= "	}\n";
			$html .= "	function IsNumeric(valor)\n";
			$html .= "	{\n";
			$html .= "		var log = valor.length; \n";
			$html .= "		var sw='S';\n";
			$html .= "		var puntos = 0;\n";
			$html .= "		for (x=0; x<log; x++)\n";
			$html .= "		{ \n";
			$html .= "			v1 = valor.substr(x,1);\n";
			$html .= "			v2 = parseInt(v1);\n";
			$html .= "			//Compruebo si es un valor numérico\n";
			$html .= "			if(v1 == '.')\n";
			$html .= "			{\n";
			$html .= "				puntos ++;\n";
			$html .= "			}\n";
			$html .= "			else if (isNaN(v2)) \n";
			$html .= "			{ \n";
			$html .= "				sw= 'N';\n";
			$html .= "				break;\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "		if(log == 0) sw = 'N';\n";
			$html .= "		if(puntos > 1) sw = 'N';\n";
			$html .= "		if(sw=='S')\n"; 
			$html .= "			return true;\n";
			$html .= "		return false;\n";
			$html .= "	} \n";
			$html .= "	function finMes(nMes)\n";
			$html .= "	{\n";
			$html .= "		var nRes = 0;\n";
			$html .= "		switch (nMes)\n";
			$html .= "		{\n";
			$html .= "			case '01': nRes = 31; break;\n";
			$html .= "			case '02': nRes = 29; break;\n";
			$html .= "			case '03': nRes = 31; break;\n";
			$html .= "			case '04': nRes = 30; break;\n";
			$html .= "			case '05': nRes = 31; break;\n";
			$html .= "			case '06': nRes = 30; break;\n";
			$html .= "			case '07': nRes = 31; break;\n";
			$html .= "			case '08': nRes = 31; break;\n";
			$html .= "			case '09': nRes = 30; break;\n";
			$html .= "			case '10': nRes = 31; break;\n";
			$html .= "			case '11': nRes = 30; break;\n";
			$html .= "			case '12': nRes = 31; break;\n";
			$html .= "		}\n";
			$html .= "		return nRes;\n";
			$html .= "	}\n";
			$html .= "	function IsDate(fecha)\n";
			$html .= "	{\n";
			$html .= "		var bol = true;\n";
			$html .= "		var arr = fecha.split('/');\n";
			$html .= "		if(arr.length > 3)\n";
			$html .= "			return false;\n";
			$html .= "		else\n";
			$html .= "		{\n";
			$html .= "			bol = bol && (IsNumeric(arr[0]));\n";
			$html .= "			bol = bol && (IsNumeric(arr[1]));\n";
			$html .= "			bol = bol && (IsNumeric(arr[2]));\n";
			$html .= "			bol = bol && ((arr[1] >= 1) && (arr[1] <= 12));\n";
			$html .= "			bol = bol && (arr[0] <= finMes(arr[1]));\n";
			$html .= "			return bol;\n";
			$html .= "		}\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			
			$html .= "<form name=\"cheques\" action=\"javascript:EvaluarDatos(document.cheques)\" method=\"post\">\n";
			$html .= "<table width=\"100%\">\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "			<div id=\"error\" style=\"text-align:center\" class=\"label_error\"></div>\n";
			$html .= "			<fieldset class=\"fieldset\">\n";
			$html .= "				<legend>PAGO CON CHEQUE</legend>\n";
			$html .= "				<table width=\"100%\" class=\"modulo_table_list\" >\n";
			$html .= "					<tr class=\"modulo_table_list_title\">\n";
			$html .= "						<td colspan=\"4\">DATOS AUTORIZACION CHEQUE</td>\n";
			$html .= "					</tr>\n";
			$html .= "					<tr $est class=\"modulo_table_list_title\">\n";
			$html	.= "						<td width=\"19%\">ENTIDAD CONFIRMA:</td>\n";
			$html .= "						<td width=\"30%\" class=\"modulo_list_claro\">\n";
			$html .= "							<select name=\"entidad\" class=\"select\">\n";
			$html .= "								<option value='-1'>---SELECCIONAR---</option>\n";
			
			foreach($confirman as $key => $entidades)
				$html .="								<option value=".$entidades['entidad_confirma'].">".$entidades['descripcion']."</option>\n";
			
			$html .= "							</select>\n";
			$vld  = "	obs[".($i++)."] = new Array(objeto.entidad.value,'ENTIDAD CONFIRMA','select');\n";				

			$html .= "						</td>\n";
			$html .= "   					<td width=\"20%\">F. CONFIRMA</td>\n";
			$html .= "						<td class=\"modulo_list_claro\">\n";
			$html .= "							<input type=\"text\" class=\"input-text\" name=\"fecha_confirma\" style=\"width:35%\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".date("d/m/Y")."\">".ReturnOpenCalendario('cheques','fecha_confirma','/')."\n";
			$vld .= "	obs[".($i++)."] = new Array(objeto.fecha_confirma.value,'FECHA CONFIRMACION','date');\n";				

			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "					<tr $est class=\"modulo_table_list_title\">\n";
			$html .= "						<td>FUNCIONARIO CONFIRMA</td>\n";
			$html .= "						<td class=\"modulo_list_claro\">\n";
			$html .= "							<input name=\"funcionario\" class=\"input-text\" type=\"text\" style=\"width:90%\" maxlength=\"40\">\n";
			$html .= "						</td>\n";
			$html .= "						<td>Nº CONFIRMACIÓN</td>\n";
			$html .= "						<td class=\"modulo_list_claro\">\n";
			$html .= "							<input name=\"numero\" class=\"input-text\" type=\"text\" style=\"width:90%\" maxlength=\"15\">\n";
			$vld .= "	obs[".($i++)."] = new Array(objeto.numero.value,'NUMERO DE CONFIRMACION','text');\n";				

			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "				</table><br>\n";
			$html .= "				<table width=\"100%\" class=\"modulo_table_list\" >\n";
			$html .= "					<tr $est class=\"modulo_table_list_title\">\n";
			$html .= "						<td >Nº CHEQUE:</td>\n";
			$html .= "						<td class=\"modulo_list_claro\" colspan=\"3\">\n";
			$html .= "							<input type=\"text\" name=\"numero_cheque\" class=\"input-text\" size=\"20\" maxlength=\"10\" value=\"\"\>\n";
			$vld .= "	obs[".($i++)."] = new Array(objeto.numero_cheque.value,'NUMERO DE CHEQUE','text');\n";				

			$html .= "						</td>\n";
			$html .= "					</tr>\n";			
			$html .= "					<tr $est class=\"modulo_table_list_title\" >\n";
			$html .= "						<td >BANCO</td>\n";
			$html .= "						<td class=\"modulo_list_claro\" colspan=\"3\">\n";
			$html .= "							<select name=\"banco\" class=\"select\">\n";
			$html .= "								<option value='-1'>--------SELECCIONAR--------</option>\n";

			foreach($bancos as $key => $banco)
				$html .= "								<option value='".$banco['banco']."' >".$banco['descripcion']."</option>\n";
			
			$html .= "							</select>\n";
			$vld .= "	obs[".($i++)."] = new Array(objeto.banco.value,'BANCO','select');\n";				

			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "					<tr $est class=\"modulo_table_list_title\">\n";
			$html .= "						<td width=\"19%\">Nº CTA. CORRIENTE:</td>\n";
			$html .= "						<td width=\"30%\" class=\"modulo_list_claro\" >\n";
			$html .= "							<input type=\"text\" name=\"numero_cuenta\" class=\"input-text\" style=\"width:90%\" maxlength=\"40\" value=\"\"\>\n";
			$vld .= "	obs[".($i++)."] = new Array(objeto.numero_cuenta.value,'NUMERO DE CUENTA CORRIENTE','text');\n";				

			$html .= "						</td>\n";
			$html .= "						<td width=\"20%\" align=\"left\">GIRADOR</td>\n";
			$html .= "						<td width=\"31%\" class=\"modulo_list_claro\">\n";
			$html .= "							<input type=\"text\" name=\"girador\" class=\"input-text\" style=\"width:90%\" maxlength=\"30\" value=\"\"\>\n";
			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "					<tr $est class=\"modulo_table_list_title\">\n";
			$html .= "						<td title=\"FECHA CHEQUE\">F. CHEQUE:</td>\n";
			$html .= "						<td class=\"modulo_list_claro\" >\n";
			$html .= "							<input type=\"text\" class=\"input-text\" name=\"fecha_cheque\" style=\"width:35%\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"\">\n";
			$html .= "							".ReturnOpenCalendario('cheques','fecha_cheque','/')."\n";			
			$vld .= "	obs[".($i++)."] = new Array(objeto.fecha_cheque.value,'FECHA CHEQUE','date');\n";				

			$html .= "						</td>\n";
			$html .= "						<td>F. TRANSACCION:</td>\n";
			$html .= "						<td class=\"modulo_list_claro\" >\n";
			$html .= "							<input type=\"text\" class=\"input-text\" name=\"fecha_transaccion\" style=\"width:35%\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".date("d/m/Y")."\">\n";
			$html .= "							".ReturnOpenCalendario('cheques','fecha_transaccion','/')."\n";			
			$vld .= "	obs[".($i++)."] = new Array(objeto.fecha_transaccion.value,'FECHA DE TRANSACCION','date');\n";				

			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "					<tr $est class=\"modulo_table_list_title\">\n";
			$html .= "						<td>TOTAL:</td>\n";
			$html .= "						<td class=\"modulo_list_claro\" colspan=\"3\">\n";
			$html .= "							<input type=\"text\" class=\"input-text\" name=\"valor\" style=\"width:35%\" onkeypress=\"return acceptNum(event)\">\n";
			$vld .= "	obs[".($i++)."] = new Array(objeto.valor.value,'TOTAL','numeric');\n";				

			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "				</table>\n";
			$html .= "			</fieldset>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
			$html .= "<table align=\"center\" width=\"100%\">\n";
			$html .= "	<tr>\n";
			$html .= "		<td align=\"center\">\n";
			$html .= "			<input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">\n";
			$html .= "		</td>\n";
			$html .= "		<td align=\"center\">\n";
			$html .= "			<input type=\"button\" class=\"input-submit\" value=\"Cancelar\" onclick=\"window.close()\">\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
			$html .= "</form>\n";
			
			$html .= "<script>\n";
			$html .= "	function EvaluarDatos(objeto)\n";
			$html .= "	{\n";
			$html .= "		error = document.getElementById('error');\n";
			$html .= "		obs = new Array();\n";
			$html .= "		".$vld."\n";

			$html .= "		for(i=0; i<obs.length; i++)\n";
			$html .= "		{\n";
			$html .= "			switch(obs[i][2])\n";
			$html .= "			{\n";
			$html .= "				case 'numeric':\n";
			$html .= "					if(!IsNumeric(obs[i][0]))\n";
			$html .= "					{\n";
			$html .= "						error.innerHTML = 'EL VALOR DEL CAMPO '+obs[i][1]+', POSSE UN VALOR O UN FORMATO DE NUMERO INCORRECTO'\n";
			$html .= "						return;\n";
			$html .= "					}\n";
			$html .= "				break;\n";
			$html .= "				case 'text':\n";
			$html .= "					if(obs[i][0] == '')\n";
			$html .= "					{\n";
			$html .= "						error.innerHTML = 'EL CAMPO '+obs[i][1]+' ES OBLIGATORIO'\n";
			$html .= "						return;\n";
			$html .= "					}\n";
			$html .= "				break;\n";
			$html .= "				case 'date':\n";
			$html .= "					if(!IsDate(obs[i][0]))\n";
			$html .= "					{\n";
			$html .= "						error.innerHTML = 'EL VALOR DEL CAMPO '+obs[i][1]+', POSSE UN FORMATO DE FECHA INCORRECTO'\n";
			$html .= "						return;\n";
			$html .= "					}\n";
			$html .= "				break;\n";
			$html .= "				case 'select':\n";
			$html .= "					if(obs[i][0] == '-1')\n";
			$html .= "					{\n";
			$html .= "						error.innerHTML = 'EL CAMPO '+obs[i][1]+' ES OBLIGATORIO'\n";
			$html .= "						return;\n";
			$html .= "					}\n";
			$html .= "				break;\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "		pago = objeto.valor.value;\n";
			$html .= "		total = window.opener.document.getElementById('total').innerHTML;\n";
			$html .= "		v_paciente = ".$datos['valor_pago'].";\n";
			$html .= "		if(v_paciente*1 < (pago*1 + total*1))\n";
			$html .= "		{\n";
			$html .= "			error.innerHTML = 'EL VALOR A PAGAR ES MAYOR AL TOTAL A PAGAR: $'+(v_paciente*1 - total*1);\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		error.innerHTML = '';\n";
			$html .= "		objeto.action = '".$action['aceptar']."'\n";
			$html .= "		objeto.submit();\n";

			$html .= "	}\n";	
			$html .= "</script>\n";	
			return $html;
		}
		/**********************************************************************************
		*
		* @return 
		************************************************************************************/
		function FormaPagoTarjetaCredito($datos,$action)
		{
			IncludeClass('Caja','','app','Cuentas');
			$cj = new Caja();

			$i = 0;
			$html = "";
			$est  = "style=\"text-align:left;padding:4pt\"";

			$confirman = $cj->ObtenerEntidadesConfirma();
			$tarjetas = $cj->ObtenerTarjetas();
			
			$html .= "<script>\n";
			$html .= "	function acceptNum(evt)\n";
			$html .= "  {\n";
			$html .= "    var nav4 = window.Event ? true : false;\n";
			$html .= "    var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "    return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$html .= "  }\n";
			$html .= "	function acceptDate(evt)\n";
			$html .= "	{\n";
			$html .= "		var nav4 = window.Event ? true : false;\n";
			$html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "		return (key <= 13 ||(key >= 47 && key <= 57));\n";
			$html .= "	}\n";
			$html .= "	function IsNumeric(valor)\n";
			$html .= "	{\n";
			$html .= "		var log = valor.length; \n";
			$html .= "		var sw='S';\n";
			$html .= "		var puntos = 0;\n";
			$html .= "		for (x=0; x<log; x++)\n";
			$html .= "		{ \n";
			$html .= "			v1 = valor.substr(x,1);\n";
			$html .= "			v2 = parseInt(v1);\n";
			$html .= "			//Compruebo si es un valor numérico\n";
			$html .= "			if(v1 == '.')\n";
			$html .= "			{\n";
			$html .= "				puntos ++;\n";
			$html .= "			}\n";
			$html .= "			else if (isNaN(v2)) \n";
			$html .= "			{ \n";
			$html .= "				sw= 'N';\n";
			$html .= "				break;\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "		if(log == 0) sw = 'N';\n";
			$html .= "		if(puntos > 1) sw = 'N';\n";
			$html .= "		if(sw=='S')\n"; 
			$html .= "			return true;\n";
			$html .= "		return false;\n";
			$html .= "	} \n";
			$html .= "	function finMes(nMes)\n";
			$html .= "	{\n";
			$html .= "		var nRes = 0;\n";
			$html .= "		switch (nMes)\n";
			$html .= "		{\n";
			$html .= "			case '01': nRes = 31; break;\n";
			$html .= "			case '02': nRes = 29; break;\n";
			$html .= "			case '03': nRes = 31; break;\n";
			$html .= "			case '04': nRes = 30; break;\n";
			$html .= "			case '05': nRes = 31; break;\n";
			$html .= "			case '06': nRes = 30; break;\n";
			$html .= "			case '07': nRes = 31; break;\n";
			$html .= "			case '08': nRes = 31; break;\n";
			$html .= "			case '09': nRes = 30; break;\n";
			$html .= "			case '10': nRes = 31; break;\n";
			$html .= "			case '11': nRes = 30; break;\n";
			$html .= "			case '12': nRes = 31; break;\n";
			$html .= "		}\n";
			$html .= "		return nRes;\n";
			$html .= "	}\n";
			$html .= "	function IsDate(fecha)\n";
			$html .= "	{\n";
			$html .= "		var bol = true;\n";
			$html .= "		var arr = fecha.split('/');\n";
			$html .= "		if(arr.length > 3)\n";
			$html .= "			return false;\n";
			$html .= "		else\n";
			$html .= "		{\n";
			$html .= "			bol = bol && (IsNumeric(arr[0]));\n";
			$html .= "			bol = bol && (IsNumeric(arr[1]));\n";
			$html .= "			bol = bol && (IsNumeric(arr[2]));\n";
			$html .= "			bol = bol && ((arr[1] >= 1) && (arr[1] <= 12));\n";
			$html .= "			bol = bol && (arr[0] <= finMes(arr[1]));\n";
			$html .= "			return bol;\n";
			$html .= "		}\n";
			$html .= "	}\n";

			$html .= "</script>\n";
			$html .= "<form name=\"tarjeta\" action=\"javascript:EvaluarDatos(document.tarjeta)\" method=\"post\">\n";
			$html .= "<table width=\"100%\">\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "			<div id=\"error\" style=\"text-align:center\" class=\"label_error\"></div>\n";
			$html .= "			<fieldset class=\"fieldset\">\n";
			$html .= "				<legend>PAGO CON TARJETA CREDITO</legend>\n";
			$html .= "				<table width=\"100%\" class=\"modulo_table_list\" >\n";
			$html .= "					<tr class=\"modulo_table_list_title\">\n";
			$html .= "						<td colspan=\"4\">DATOS AUTORIZACION TARJETA</td>\n";
			$html .= "					</tr>\n";
			$html .= "					<tr $est class=\"modulo_table_list_title\">\n";
			$html	.= "						<td width=\"19%\">ENTIDAD CONFIRMA:</td>\n";
			$html .= "						<td width=\"30%\" class=\"modulo_list_claro\">\n";
			$html .= "							<select name=\"entidad\" class=\"select\">\n";
			$html .= "								<option value='-1'>------SELECCIONAR------</option>\n";

			foreach($confirman as $key => $entidades)
				$html .="								<option value=".$entidades['entidad_confirma'].">".$entidades['descripcion']."</option>\n";

			$html .= "							</select>\n";
			$vld  = "	obs[".($i++)."] = new Array(objeto.entidad.value,'ENTIDAD CONFIRMA','select');\n";				

			$html .= "						</td>\n";
			$html .= "   					<td width=\"20%\">FECHA</td>\n";
			$html .= "						<td class=\"modulo_list_claro\">\n";
			$html .= "							<input type=\"text\" class=\"input-text\" name=\"fecha_confirma\" style=\"width:35%\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".date("d/m/Y")."\">".ReturnOpenCalendario('tarjeta','fecha_confirma','/')."\n";
			$html .= "						</td>\n";
			$vld  .= "	obs[".($i++)."] = new Array(objeto.fecha_confirma.value,'FECHA DE CONFIRMACION','date');\n";				

			$html .= "					</tr>\n";
			$html .= "					<tr $est class=\"modulo_table_list_title\">\n";
			$html .= "						<td>FUNCIONARIO CONFIRMA</td>\n";
			$html .= "						<td class=\"modulo_list_claro\">\n";
			$html .= "							<input name=\"funcionario\" class=\"input-text\" type=\"text\" style=\"width:90%\" maxlength=\"40\">\n";
			$html .= "						</td>\n";
			$html .= "						<td>Nº CONFIRMACIÓN</td>\n";
			$html .= "						<td class=\"modulo_list_claro\">\n";
			$html .= "							<input name=\"numero\" class=\"input-text\" type=\"text\" style=\"width:90%\" maxlength=\"15\">\n";
			$vld  .= "	obs[".($i++)."] = new Array(objeto.numero.value,'NUMERO DE CONFIRMACION','text');\n";				

			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "				</table><br>\n";
			
			$html .= "				<table width=\"100%\" class=\"modulo_table_list\" >\n";
			$html .= "					<tr $est class=\"modulo_table_list_title\">\n";
			$html .= "						<td width=\"19%\">TARJETA</td>\n";
			$html .= "						<td colspan=\"3\" class=\"modulo_list_claro\" >\n";
			$html .= "							<select name=\"tarjeta\" class=\"select\" >\n";
			$html .= "								<option value='-1'>-SELECCIONAR-</option>\n";
			
			foreach($tarjetas['C'] as $key => $tarjeta)
				$html .= "								<option value='".$tarjeta['tarjeta']."' >".$tarjeta['descripcion']."</option>\n";
			
			$html .= "							</select>\n";
			$vld  .= "	obs[".($i++)."] = new Array(objeto.tarjeta.value,'TARJETA','select');\n";				

			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "					<tr $est class=\"modulo_table_list_title\">\n";
			$html .= "						<td width=\"20%\">Nº TARJETA</td>\n";
			$html .= "						<td width=\"30%\" class=\"modulo_list_claro\" >\n";
			$html .= "							<input type=\"text\" class=\"input-text\" name=\"num_tarjeta\" style=\"width:90%\" maxlength=\"20\">\n";
			$vld  .= "	obs[".($i++)."] = new Array(objeto.num_tarjeta.value,'NUMERO DE TARJETA','text');\n";				

			$html .= "						</td>\n";
			$html .= "						<td width=\"20%\">SOCIO</td>\n";
			$html .= "						<td class=\"modulo_list_claro\" >\n";
			$html .= "							<input type=\"text\" class=\"input-text\" name=\"socio\" style=\"width:90%\" maxlength=\"40\" value=\"".$this->Socio."\">\n";
			$vld  .= "	obs[".($i++)."] = new Array(objeto.socio.value,'SOCIO','text');\n";				
			
			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "					<tr $est class=\"modulo_table_list_title\">\n";
			$html .= "						<td>F. EXPIRACIÓN:</td>\n";
			$html .= "						<td class=\"modulo_list_claro\" >\n";
			$html .= "							<input type=\"text\" class=\"input-text\" name=\"fecha_expiracion\" style=\"width:35%\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"\">\n";
			$html .= "							".ReturnOpenCalendario('tarjeta','fecha_expiracion','/')."\n";			
			$vld  .= "	obs[".($i++)."] = new Array(objeto.fecha_expiracion.value,'FECHA DE EXPIRACION','date');\n";				

			$html .= "						</td>\n";
			$html .= "						<td>F. TRANSACCION:</td>\n";
			$html .= "						<td class=\"modulo_list_claro\" >\n";
			$html .= "							<input type=\"text\" class=\"input-text\" name=\"fecha_transaccion\" style=\"width:35%\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".date("d/m/Y")."\">\n";
			$html .= "							".ReturnOpenCalendario('tarjeta','fecha_transaccion','/')."\n";			
			$vld  .= "	obs[".($i++)."] = new Array(objeto.fecha_transaccion.value,'FECHA DE TRANSACCION','date');\n";				

			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "					<tr $est class=\"modulo_table_list_title\">\n";
			$html .= "						<td>TOTAL:</td>\n";
			$html .= "						<td class=\"modulo_list_claro\" colspan=\"3\">\n";
			$html .= "							<input type=\"text\" class=\"input-text\" name=\"valor\" style=\"width:35%\" onkeypress=\"return acceptNum(event)\">\n";
			$vld  .= "	obs[".($i++)."] = new Array(objeto.valor.value,'TOTAL','numeric');\n";				

			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "				</table>\n";
			$html .= "			</fieldset>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
			$html .= "<table align=\"center\" width=\"100%\">\n";
			$html .= "	<tr>\n";
			$html .= "		<td align=\"center\">\n";
			$html .= "			<input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">\n";
			$html .= "		</td>\n";
			$html .= "		<td align=\"center\">\n";
			$html .= "			<input type=\"button\" class=\"input-submit\" value=\"Cancelar\" onclick=\"window.close()\">\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
			$html .= "</form>\n";
			
			$html .= "<script>\n";
			$html .= "	function EvaluarDatos(objeto)\n";
			$html .= "	{\n";
			$html .= "		error = document.getElementById('error');\n";
			$html .= "		obs = new Array();\n";
			$html .= "		".$vld."\n";

			$html .= "		for(i=0; i<obs.length; i++)\n";
			$html .= "		{\n";
			$html .= "			switch(obs[i][2])\n";
			$html .= "			{\n";
			$html .= "				case 'numeric':\n";
			$html .= "					if(!IsNumeric(obs[i][0]))\n";
			$html .= "					{\n";
			$html .= "						error.innerHTML = 'EL VALOR DEL CAMPO '+obs[i][1]+', POSSE UN VALOR O UN FORMATO DE NUMERO INCORRECTO'\n";
			$html .= "						return;\n";
			$html .= "					}\n";
			$html .= "				break;\n";
			$html .= "				case 'text':\n";
			$html .= "					if(obs[i][0] == '')\n";
			$html .= "					{\n";
			$html .= "						error.innerHTML = 'EL CAMPO '+obs[i][1]+' ES OBLIGATORIO'\n";
			$html .= "						return;\n";
			$html .= "					}\n";
			$html .= "				break;\n";
			$html .= "				case 'date':\n";
			$html .= "					if(!IsDate(obs[i][0]))\n";
			$html .= "					{\n";
			$html .= "						error.innerHTML = 'EL VALOR DEL CAMPO '+obs[i][1]+', POSSE UN FORMATO DE FECHA INCORRECTO'\n";
			$html .= "						return;\n";
			$html .= "					}\n";
			$html .= "				break;\n";
			$html .= "				case 'select':\n";
			$html .= "					if(obs[i][0] == '-1')\n";
			$html .= "					{\n";
			$html .= "						error.innerHTML = 'EL CAMPO '+obs[i][1]+' ES OBLIGATORIO'\n";
			$html .= "						return;\n";
			$html .= "					}\n";
			$html .= "				break;\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "		pago = objeto.valor.value;\n";
			$html .= "		total = window.opener.document.getElementById('total').innerHTML;\n";
			$html .= "		v_paciente = ".$datos['valor_pago'].";\n";
			$html .= "		if(v_paciente*1 < (pago*1 + total*1))\n";
			$html .= "		{\n";
			$html .= "			error.innerHTML = 'EL VALOR A PAGAR ES MAYOR AL TOTAL A PAGAR: $'+(v_paciente*1 - total*1);\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		error.innerHTML = '';\n";
			$html .= "		objeto.action = '".$action['aceptar']."'\n";
			$html .= "		objeto.submit();\n";
			$html .= "	}\n";	
			$html .= "</script>\n";	

			return $html;
		}
		/**********************************************************************************
		*
		* @return 
		************************************************************************************/
		function FormaPagoTarjetaDebito($datos,$action)
		{
			IncludeClass('Caja','','app','Cuentas');
			$cj = new Caja();

			$i = 0;
			$tarjetas = $cj->ObtenerTarjetas();

			$html = "";
			$est  = "style=\"text-align:left;padding:4pt\"";
			
			$html .= "<script>\n";
			$html .= "	function acceptNum(evt)\n";
			$html .= "  {\n";
			$html .= "    var nav4 = window.Event ? true : false;\n";
			$html .= "    var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "    return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$html .= "  }\n";
			$html .= "	function acceptDate(evt)\n";
			$html .= "	{\n";
			$html .= "		var nav4 = window.Event ? true : false;\n";
			$html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "		return (key <= 13 ||(key >= 47 && key <= 57));\n";
			$html .= "	}\n";
			$html .= "	function IsNumeric(valor)\n";
			$html .= "	{\n";
			$html .= "		var log = valor.length; \n";
			$html .= "		var sw='S';\n";
			$html .= "		var puntos = 0;\n";
			$html .= "		for (x=0; x<log; x++)\n";
			$html .= "		{ \n";
			$html .= "			v1 = valor.substr(x,1);\n";
			$html .= "			v2 = parseInt(v1);\n";
			$html .= "			//Compruebo si es un valor numérico\n";
			$html .= "			if(v1 == '.')\n";
			$html .= "			{\n";
			$html .= "				puntos ++;\n";
			$html .= "			}\n";
			$html .= "			else if (isNaN(v2)) \n";
			$html .= "			{ \n";
			$html .= "				sw= 'N';\n";
			$html .= "				break;\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "		if(log == 0) sw = 'N';\n";
			$html .= "		if(puntos > 1) sw = 'N';\n";
			$html .= "		if(sw=='S')\n"; 
			$html .= "			return true;\n";
			$html .= "		return false;\n";
			$html .= "	} \n";

			$html .= "</script>\n";
			$html .= "<form name=\"tarjetas\" action=\"javascript:EvaluarDatos(document.tarjetas)\" method=\"post\">\n";
			$html .= "<table width=\"100%\">\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "			<div id=\"error\" style=\"text-align:center\" class=\"label_error\"></div>\n";
			$html .= "			<fieldset class=\"fieldset\">\n";
			$html .= "				<legend>PAGO CON TARJETA DEBITO</legend>\n";
			$html .= "				<table width=\"100%\" class=\"modulo_table_list\" >\n";
			$html .= "					<tr $est class=\"modulo_table_list_title\">\n";
			$html .= "						<td >TARJETA</td>\n";
			$html .= "						<td class=\"modulo_list_claro\" width=\"27%\">\n";
			$html .= "							<select name=\"tarjeta\" class=\"select\" >\n";
			$html .= "								<option value='-1'>-SELECCIONAR-</option>\n";
			
			foreach($tarjetas['D'] as $key => $tarjeta)
				$html .= "								<option value='".$tarjeta['tarjeta']."' >".$tarjeta['descripcion']."</option>\n";

			$html .= "							</select>\n";
			$vld   = "	obs[".($i++)."] = new Array(objeto.tarjeta.value,'TARJETA','select');\n";				

			$html .= "						</td>\n";
			$html .= "						<td>Nº TARJETA</td>\n";
			$html .= "						<td class=\"modulo_list_claro\" width=\"27%\">\n";
			$html .= "							<input type=\"text\" class=\"input-text\" name=\"num_tarjeta\" style=\"width:90%\" maxlength=\"20\" value=\"\">\n";
			$vld  .= "	obs[".($i++)."] = new Array(objeto.num_tarjeta.value,'NUMERO DE TARJETA','text');\n";				

			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "					<tr $est class=\"modulo_table_list_title\">\n";
			$html .= "						<td >Nº AUTORIZACIÓN</td>\n";
			$html .= "						<td class=\"modulo_list_claro\" >\n";
			$html .= "								<input type=\"text\" class=\"input-text\" name=\"num_autorizacion\" style=\"width:90%\" maxlength=\"15\" value=\"\">\n";
			$vld  .= "	obs[".($i++)."] = new Array(objeto.num_autorizacion.value,'NUMERO DE AUTORIZACION','text');\n";				
			
			$html .= "						</td>\n";
			$html .= "						<td>TOTAL:</td>\n";
			$html .= "						<td class=\"modulo_list_claro\">\n";
			$html .= "							<input type=\"text\" class=\"input-text\" name=\"valor\" style=\"width:90%\" onkeypress=\"return acceptNum(event)\">\n";
			$vld  .= "	obs[".($i++)."] = new Array(objeto.valor.value,'TOTAL','numeric');\n";				

			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "				</table>\n";
			$html .= "			</fieldset>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
			$html .= "<table align=\"center\" width=\"100%\">\n";
			$html .= "	<tr>\n";
			$html .= "		<td align=\"center\">\n";
			$html .= "			<input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">\n";
			$html .= "		</td>\n";
			$html .= "		<td align=\"center\">\n";
			$html .= "			<input type=\"button\" class=\"input-submit\" value=\"Cancelar\" onclick=\"window.close()\">\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
			$html .= "</form>\n";
			
			$html .= "<script>\n";
			$html .= "	function EvaluarDatos(objeto)\n";
			$html .= "	{\n";
			$html .= "		error = document.getElementById('error');\n";
			$html .= "		obs = new Array();\n";
			$html .= "		".$vld."\n";

			$html .= "		for(i=0; i<obs.length; i++)\n";
			$html .= "		{\n";
			$html .= "			switch(obs[i][2])\n";
			$html .= "			{\n";
			$html .= "				case 'numeric':\n";
			$html .= "					if(!IsNumeric(obs[i][0]))\n";
			$html .= "					{\n";
			$html .= "						error.innerHTML = 'EL VALOR DEL CAMPO '+obs[i][1]+', POSSE UN VALOR O UN FORMATO DE NUMERO INCORRECTO'\n";
			$html .= "						return;\n";
			$html .= "					}\n";
			$html .= "				break;\n";
			$html .= "				case 'text':\n";
			$html .= "					if(obs[i][0] == '')\n";
			$html .= "					{\n";
			$html .= "						error.innerHTML = 'EL CAMPO '+obs[i][1]+' ES OBLIGATORIO'\n";
			$html .= "						return;\n";
			$html .= "					}\n";
			$html .= "				break;\n";
			$html .= "				case 'date':\n";
			$html .= "					if(!IsDate(obs[i][0]))\n";
			$html .= "					{\n";
			$html .= "						error.innerHTML = 'EL VALOR DEL CAMPO '+obs[i][1]+', POSSE UN FORMATO DE FECHA INCORRECTO'\n";
			$html .= "						return;\n";
			$html .= "					}\n";
			$html .= "				break;\n";
			$html .= "				case 'select':\n";
			$html .= "					if(obs[i][0] == '-1')\n";
			$html .= "					{\n";
			$html .= "						error.innerHTML = 'EL CAMPO '+obs[i][1]+' ES OBLIGATORIO'\n";
			$html .= "						return;\n";
			$html .= "					}\n";
			$html .= "				break;\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "		pago = objeto.valor.value;\n";
			$html .= "		total = window.opener.document.getElementById('total').innerHTML;\n";
			$html .= "		v_paciente = ".$datos['valor_pago'].";\n";
			$html .= "		if(v_paciente*1 < (pago*1 + total*1))\n";
			$html .= "		{\n";
			$html .= "			error.innerHTML = 'EL VALOR A PAGAR ES MAYOR AL TOTAL A PAGAR: $'+(v_paciente*1 - total*1);\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		error.innerHTML = '';\n";
			$html .= "		objeto.action = '".$action['aceptar']."'\n";
			$html .= "		objeto.submit();\n";
			$html .= "	}\n";	
			$html .= "</script>\n";	
			
			return $html;
		}
		/**********************************************************************************
		*
		* @return 
		************************************************************************************/
		function FormaPagoBonos($datos)
		{
			$html = "";
			$html .= "<script>\n";
			$html .= "	function acceptNum(evt)\n";
			$html .= "  {\n";
			$html .= "    var nav4 = window.Event ? true : false;\n";
			$html .= "    var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "    return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$html .= "  }\n";
			$html .= "	function IsNumeric(valor)\n";
			$html .= "	{\n";
			$html .= "		var log = valor.length; \n";
			$html .= "		var sw='S';\n";
			$html .= "		var puntos = 0;\n";
			$html .= "		for (x=0; x<log; x++)\n";
			$html .= "		{ \n";
			$html .= "			v1 = valor.substr(x,1);\n";
			$html .= "			v2 = parseInt(v1);\n";
			$html .= "			//Compruebo si es un valor numérico\n";
			$html .= "			if(v1 == '.')\n";
			$html .= "			{\n";
			$html .= "				puntos ++;\n";
			$html .= "			}\n";
			$html .= "			else if (isNaN(v2)) \n";
			$html .= "			{ \n";
			$html .= "				sw= 'N';\n";
			$html .= "				break;\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "		if(log == 0) sw = 'N';\n";
			$html .= "		if(puntos > 1) sw = 'N';\n";
			$html .= "		if(sw=='S')\n"; 
			$html .= "			return true;\n";
			$html .= "		return false;\n";
			$html .= "	} \n";
			$html .= "  function EvaluarDatos(frm)\n";
			$html .= "	{\n";
			$html .= "		pago = frm.valor.value;\n";
			$html .= "		mensaje = document.getElementById('error');\n";
			$html .= "		mensaje.innerHTML = '';\n";
			$html .= "		if(!IsNumeric(pago))\n";
			$html .= "		{\n";
			$html .= "			mensaje.innerHTML = 'EL VALOR A PAGAR POSSE UN FORMATO DE NUMERO INCORRECTO'\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		v_paciente = ".$datos['valor_pago'].";\n";
			$html .= "		if(v_paciente*1 < pago*1)\n";
			$html .= "		{\n";
			$html .= "			mensaje.innerHTML = 'EL VALOR A PAGAR ES MAYOR AL TOTAL PACIENTE: $'+v_paciente;\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		total = window.opener.document.getElementById('total'); ";
			$html .= "		bono  = window.opener.document.getElementById('".$datos['label']."');\n";
			$html .= "		saldo = total.innerHTML*1 - bono.innerHTML*1\n";
			$html .= "		bono.innerHTML = pago\n";
			$html .= "		total.innerHTML = saldo*1 + pago*1\n";
			$html .= "		window.opener.document.getElementById('h_".$datos['label']."').value = pago*1;\n";
			$html .= "		window.opener.document.getElementById('h_total').value = saldo*1 + pago*1;\n";
			$html .= "		window.close();\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			$html .= "<form name=\"bonos\" action=\"javascript:EvaluarDatos(document.bonos)\" method=\"post\">\n";
			$html .= "<table width=\"100%\" align=\"center\">\n";
			$html .= "	<tr>\n";
			$html .= "		<td align=\"center\">\n";
			$html .= "			<fieldset style=\"width:80%\" class=\"fieldset\">\n";
			$html .= "				<legend>PAGO CON BONOS</legend>\n";
			$html .= "				<div id=\"error\" class=\"label_error\"></div>\n";
			$html .= "				<table width=\"100%\" align=\"center\">\n";
			$html .= "					<tr class=\"normal_10AN\">\n";
			$html .= "						<td width=\"25%\">VALOR A PAGAR:</td>\n";
			$html .= "						<td>\n";
			$html .= "							$<input type=\"text\" class=\"input-text\" name=\"valor\" style=\"width:60%\" onkeypress=\"return acceptNum(event)\">\n";
			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "				</table>\n";
			$html .= "				<table align=\"center\" width=\"100%\">\n";
			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";
			$html .= "							<input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">\n";
			$html .= "						</td>\n";
			$html .= "						<td align=\"center\">\n";
			$html .= "							<input type=\"button\" class=\"input-submit\" value=\"Cancelar\" onclick=\"window.close()\">\n";
			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "				</table>\n";
			$html .= "			</fieldset>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
			$html .= "</form>\n";
			$html .= "<script>\n";
			$html .= "	pg = window.opener.document.getElementById('h_".$datos['label']."').value;\n";
			$html .= "	document.bonos.valor.value = pg;\n";
			$html .= "</script>\n";
			return $html;
		}
		/**********************************************************************************
		*
		* @return 
		************************************************************************************/
		function FormaMensajeFinal($action)
		{
			$html  = ThemeAbrirTabla("MENSAJE");
			$html .= "<table width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "	<tr>\n";
			$html .= "		<td class=\"label_error\">\n";
			$html .= "			LA CUENTA, SE HA CERRADO CORRECTAMENTE Y NO SE GENERARON FACTURAS CREDITO NI DEBITO\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
			$html .= "<form name=\"cancelar\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "	<table align=\"center\" width=\"50%\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td align=\"center\">\n";
			$html .= "				<input type=\"submit\" class=\"input-submit\" name=\"cancela\" value=\"Cancelar\" >\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "</form>\n";
			$html .= ThemeCerrarTabla();			
			return $html;
		}
		/**********************************************************************************
		*
		* @return 
		************************************************************************************/
		function FormaImpresionFacturas($action,$facturas,$cuenta,$empresa,$sw)
		{
			$html  = ThemeAbrirTabla("MENSAJE");
			$html .= "<script>\n";
			$html .= "  function FacturaCliente()\n";
			$html .= "  {\n";
			$html .= "    var str =\"screen.height,screen.width,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
			$html .= "    var url = \"".$_ROOT ."cache/factura".$cuenta.".pdf\";\n";
			$html .= "    window.open(url,'Reporte', str);\n";
			$html .= "	}\n";
			$html .= "</script>\n";			
			$html .= "<table width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "	<tr>\n";
			$html .= "		<td class=\"normal_10AN\" align=\"center\" colspan=\"3\">\n";
			//$html .= "			LA CUENTA, SE CERRO CORRECTAMENTE, LA(S) SIGUIENTE(S) FACTURA(S), HAN SIDO CREDAS:\n";
			$html .= "			PAGOS REALIZADOS CORRECTAMENTE, RECIDO GENERADO:\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
			$html .= "		<td align=\"center\" width=\"33%\">\n";
			$html .= "			<a href=\"".$action['impresion_pos']."\" target=\"pagar\" onclick=\"window.open('".$action['impresion_pos']."','impresion','toolbar=no,width=350,height=350,resizable=no,scrollbars=yes').focus(); return false;\" class=\"label_error\">\n";
			$html .= "				<img src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\">IMPRIMIR POS\n";
			$html .= "			</a>\n";
			$html .= "		</td>\n";

			if(!empty($facturas['contado']))
			{
				if($sw >= 1) $sw = 1;
				$reporte = new GetReports();
				$html .= $reporte->GetJavaReport('app','CajaGeneral','Factura',array('cuenta'=>$cuenta,'switche_emp'=>$sw),array('rpt_dir'=>'cache','rpt_name'=>'recibo'.$cuenta,'rpt_rewrite'=>FALSE));
				$funcion = $reporte->GetJavaFunction();
				
				$html .= "		<td align=\"center\" width=\"34%\">\n";
				$html .= "			<a href=\"javascript:".$funcion."\" class=\"label_error\">\n";
				$html .= "				<img src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\">IMPRIMIR PDF\n";
				$html .= "			</a>\n";
				$html .= "		</td>\n";
			}

			if(!empty($facturas['credito']))
			{
				IncludeClass('Caja','','app','Cuentas');
				IncludeLib("reportes/factura");
				
				$cj = new Caja();
				
				$f_paciente = $cj->ObtenerDatosFacturaCliente($cuenta,$empresa,$facturas['credito']['prefijo'],$facturas['credito']['numeracion']);
				GenerarFactura($f_paciente);
				
				$html .= "		<td align=\"center\" width=\"33%\">\n";
				$html .= "			<a href=\"javascript:FacturaCliente()\" class=\"label_error\">\n";
				$html .= "				<img src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\">FACTURA CLIENTE\n";
				$html .= "			</a>\n";
				$html .= "		</td>\n";
			}
			
			$html .= "	</tr>\n";
			$html .= "</table>\n";
			$html .= "<form name=\"cancelar\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "	<table align=\"center\" width=\"50%\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td align=\"center\">\n";
			$html .= "				<input type=\"submit\" class=\"input-submit\" name=\"Aceptar\" value=\"Aceptar\" >\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "</form>\n";
			$html .= ThemeCerrarTabla();			
			return $html;
		}
	}
?>