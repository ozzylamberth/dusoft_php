<?php
  /******************************************************************************
  * $Id: CuentaDetalleHTM.class.php,v 1.7 2010/11/29 14:05:53 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.7 $ 
	* 
	* @autor Hugo F  Manrique 
  ********************************************************************************/
	class CuentaDetalleHTM
	{
		function CuentaDetalleHTM(){}
		/**********************************************************************************
		*@acess public 
		***********************************************************************************/
		function InformacionTotales($cuenta,$action)
		{
    
   
			IncludeClass('CuentaDetalle','','app','Cuentas');
			$cntd = new CuentaDetalle();
			$estado = $cntd->ObtenerEstadoCuenta($cuenta[numerodecuenta]);
   
			$html = "";
			$cl1 = $cl2 = "";
			if($cuenta['modifica_cuota']) 
				$cl1 = "class=\"label_error\"";
			
			if($cuenta['modifica_copago'])
				$cl2 = "class=\"label_error\"";
			
			$datos = array();
			$datos['numerodecuenta'] = $cuenta['numerodecuenta'];
			
			$html .= "<table width=\"100%\" class=\"modulo_table_list\">\n"; 
			$html .= "	<tr class=\"modulo_table_list_title\" height= \"21\">\n"; 
			$html .= "		<td width=\"12%\">T. CUENTA</td>\n"; 
			$html .= "		<td width=\"10%\">T. PAG. PACIENTE</td>\n"; 
			$html .= "		<td width=\"11%\">T. PACIENTE</td>\n"; 
			$html .= "		<td width=\"11%\">T. EMPRESA</td>\n"; 
			$html .= "		<td width=\"11%\" colspan=\"2\">COPAGO</td>\n"; 
			$html .= "		<td width=\"11%\" colspan=\"2\">C. MODERADORA</td>\n"; 
			$html .= "		<td width=\"12%\">T. CUBIERTO</td>\n"; 
			$html .= "		<td width=\"12%\">T. NO CUBIERTO</td>\n"; 
			$html .= "		<td width=\"10%\">SALDO</td>\n"; 
			$html .= "	</tr>\n"; 
			$html .= "	<tr align=\"right\" class=\"label\">\n"; 
			$html .= "		<td >$".formatoValor($cuenta['total_cuenta'])."</td>\n"; 
			$pagos = "";
			if($cuenta['valor_total_pagado_paciente'] > 0)
      {
      
      $pagos = RetornarWinOpenPagos($cuenta['numerodecuenta'],"<img src=\"".GetThemePath()."/images/pcopiar.png\" border=\"0\" title=\"PAGOS DEL PACIENTE\">",$class); };
			$html .= "		<td >$".formatoValor($cuenta['valor_total_pagado_paciente'])."&nbsp;&nbsp;&nbsp;".$pagos."</td>\n"; 
			$html .= "		<td >$<label name=\"paciente\" id=\"paciente\">".formatoValor($cuenta['valor_total_paciente'])."</label></td>\n"; 
			$html .= "		<td >$".formatoValor($cuenta['valor_total_empresa'])."</td>\n"; 
			
			$datos['tipo_cambio'] = "1";
			$datos['id_campo'] = "copago";
			
			$html .= "		<td ><label $cl2 name=\"copago\" id=\"copago\">$".formatoValor($cuenta['valor_cuota_paciente'])."</label></td>\n"; 
			$html .= "		<td width=\"2%\">\n";
			$html .= "			<input type=\"hidden\" name=\"h_copago\" id=\"h_copago\" value=\"".$cuenta['valor_cuota_paciente']."\">\n";
			if($estado <> '0')
				$html .= "			<a title=\"MODIFICAR VALOR COPAGO\" href=\"".$action['cambiar'].UrlRequest($datos)."\" target=\"cambiar\" onclick=\"window.open('".$action['cambiar'].UrlRequest($datos)."','cambiar','toolbar=no,width=400,height=350,resizable=no,scrollbars=yes').focus(); return false;\" class=\"label_error\">\n";
			else
				$html .= "			<a title=\"NO SE PUEDE MODIFICAR, CUENTA FACTURADA\" class=\"label_error\">\n";
			$html .= "				<img src=\"".GetThemePath()."/images/pcopiar.png\" border=\"0\">\n";
			$html .= "			</a>\n";
			$html .= "		</td>\n"; 

			$datos['tipo_cambio'] = "2";
			$datos['id_campo'] = "cuota";

			$html .= "		<td ><label $cl1 name=\"cuota\" id=\"cuota\">$".formatoValor($cuenta['valor_cuota_moderadora'])."</label></td>\n"; 
			$html .= "		<td width=\"2%\">\n";
			$html .= "			<input type=\"hidden\" name=\"h_cuota\" id=\"h_cuota\" value=\"".$cuenta['valor_cuota_moderadora']."\">\n";
			if($estado <> '0')
				$html .= "			<a title=\"MODIFICAR VALOR CUOTA MODERADORA\" href=\"".$action['cambiar'].UrlRequest($datos)."\" target=\"cambiar\" onclick=\"window.open('".$action['cambiar'].UrlRequest($datos)."','cambiar','toolbar=no,width=400,height=350,resizable=no,scrollbars=yes').focus(); return false;\" class=\"label_error\">\n";
			else
				$html .= "			<a title=\"NO SE PUEDE MODIFICAR, CUENTA FACTURADA\" class=\"label_error\">\n";
			$html .= "				<img src=\"".GetThemePath()."/images/pcopiar.png\" border=\"0\">\n";
			$html .= "			</a>\n";
			$html .= "		</td>\n"; 
			$html .= "		<td >$".formatoValor($cuenta['valor_cubierto'])."</td>\n"; 
			$html .= "		<td >$".formatoValor($cuenta['valor_nocubierto'])."</td>\n"; 
			$saldo=SaldoCuentaPaciente($cuenta['numerodecuenta']);
			$cuenta['saldo'] = $saldo;
			$html .= "		<td >$<label name=\"saldo\" id=\"saldo\">".formatoValor($cuenta['saldo'])."</label></td>\n"; 
			$html .= "	</tr>\n"; 
			$html .= "</table>\n"; 
			return $html;
		}
		/**********************************************************************************
		*@acess public
		***********************************************************************************/
		function FormaModificarCuota($datos,$action)
		{
			IncludeClass('Cuenta','','app','Cuentas');
			$cnt = new Cuenta();
			$motivos = array();
			if($datos['tipo_cambio'] == '1')
				$motivos = $cnt->ObtenerMotivosCambioCopago();
			else if($datos['tipo_cambio'] == '2')
				$motivos = $cnt->ObtenerMotivosCambioCuota();
			
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
			$html .= "	function EvaluarDatos(frm) \n";
			$html .= "	{\n";
			$html .= "		ele = document.getElementById('error');\n";
			$html .= "		if(!IsNumeric(frm.valor.value))\n";
			$html .= "		{\n";
			$html .= "			ele.innerHTML = 'EL VALOR A CAMBIAR NO ES NUMERICO O POSEE UN FORMATO INCORRECTO'\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(frm.motivo_id.value == '-1')\n";
			$html .= "		{\n";
			$html .= "			ele.innerHTML = 'SE DEBE INDICAR EL MOTIVO POR EL CUAL SE ESTA CAMBIANDO EL VALOR'\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		frm.action = \"".$action['aceptar']."\";\n";
			$html .= "		frm.submit();\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			//$html .= "<pre>".print_r($datos,true)."</pre>";
			$html .= "<div id=\"error\" style=\"text-align:center\" class=\"label_error\"></div>\n";
			$html .= "<form name=\"cambiar\" action=\"javascript:EvaluarDatos(document.cambiar)\" method=\"post\">\n";
			$html .= "	<table width=\"100%\" align=\"center\" class=\"fieldset\" cellpadding=\"2\">\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td align=\"left\" width=\"25%\" >VALOR</td>\n";
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\" width=\"80%\">\n";
			$html .= "				<input type=\"text\" name=\"valor\" class=\"input-text\" value=\"\" onkeypress=\"return acceptNum(event)\">\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td align=\"left\" >MOTIVO</td>\n";
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "				<select name=\"motivo_id\" class=\"select\" >\n";
			$html .= "					<option value=\"-1\" >----Seleccionar----</option>\n";
			
			foreach($motivos as $key => $mtv)
				$html .= "					<option value=\"".$mtv['motivo_id']."\" >".$mtv['descripcion']."</option>\n";
			
			$html .= "				</select>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td colspan=\"2\">OBSERVACIONES</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_list_claro\">\n";
			$html .= "			<td colspan=\"2\">\n";
			$html .= "				<textarea name=\"observacion\" style=\"width:100%\" class=\"textarea\"></textarea>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "	<table align=\"center\" width=\"50%\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td>\n";
			$html .= "				<input type=\"submit\" class=\"input-bottom\" name=\"aceptar\" value=\"Aceptar\">\n";
			$html .= "			</td>\n";
			$html .= "		</form>\n";
			$html .= "		<form name=\"cancelar\" action=\"".$action['cancelar']."\" method = \"post\">\n";
			$html .= "			<td>\n";
			$html .= "				<input type=\"submit\" class=\"input-bottom\" name=\"cancelar\" value=\"Cerrar\">\n";
			$html .= "			</td>\n";
			$html .= "		</form>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
			$html .= "<script>\n";
			$html .= "	val = window.opener.document.getElementById('h_".$datos['id_campo']."').value;\n";
			$html .= "	document.cambiar.valor.value = val;\n";
			$html .= "</script>\n";
			return $html;
		}
	}
?>