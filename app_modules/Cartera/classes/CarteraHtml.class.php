<?php
  /**
  * $Id: CarteraHtml.class.php,v 1.4 2009/06/26 13:53:16 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.4 $ 
	* 
	* @autor Hugo F  Manrique 
  */
	class CarteraHtml
	{
		function CarteraHtml(){}
		/*********************************************************************************
		* Funcion donde se realiza la forma del buscador de terceros 
		* 
		* @return string forma del buscador 
		*********************************************************************************/
		function FormaBuscadorTerceros($datos,$tiposTerceros,$action)
		{
			$html  = "<form name=\"buscador\" action=\"".$action['buscador']."\" method=\"post\">\n";
			$html .= "	<script>\n";
			$html .= "		function limpiarCampos(objeto)\n";
			$html .= "		{\n";
			$html .= "			objeto.nombre_tercero.selectedIndex='0';\n";
			$html .= "			objeto.periodo.selectedIndex='X';\n";
			$html .= "		}\n";
			$html .= "	</script>\n";
			$html .= "	<fieldset class=\"fieldset\"><legend>BUSCADOR AVANZADO</legend>\n";
			$html .= "		<table width=\"100%\">\n";
			$html .= "			<tr><td class=\"normal_10AN\">NOMBRE CLIENTE:</td>\n";
			$html .= "				<td>\n";
			$html .= "					<select name=\"nombre_tercero\" class=\"select\">\n";
			$html .= "						<option value='0'>-----SELECCIONAR-----</option>\n";
			
			foreach($tiposTerceros as $key => $opciones)
			{
				($datos['nombre_tercero'] == $opciones['tipo_id_tercero']."/".$opciones['tercero_id'])? $selected = " selected ":$selected = "";
				$html .= "						<option value='".$opciones['tipo_id_tercero']."/".$opciones['tercero_id']."' $selected >".$opciones['nombre_tercero']."</option>\n";			
			}
			
			$html .= "					</select>\n";
			$html .= "				</td>\n";
			$html .= "			</tr>\n";
			$selec[$datos['periodo']] = "selected";
			
			$html .= "			<tr><td class=\"normal_10AN\">PERIODO:</td>\n";
			$html .= "				<td>\n";
			$html .= "					<select name=\"periodo\" class=\"select\">\n";
			$html .= "						<option value='X'>-----SELECCIONAR-----</option>\n";			
			$html .= "						<option value='0' ".$selec[0].">Corriente</option>\n";						
			$html .= "						<option value='1' ".$selec[1].">1 a 30</option>\n";			
			$html .= "						<option value='2' ".$selec[2].">31 a 60</option>\n";			
			$html .= "						<option value='3' ".$selec[3].">61 a 90</option>\n";			
			$html .= "						<option value='4' ".$selec[4].">91 a 120</option>\n";			
			$html .= "						<option value='5' ".$selec[5].">121 a 150</option>\n";			
			$html .= "						<option value='6' ".$selec[6].">151 a 180</option>\n";			
			$html .= "						<option value='7' ".$selec[7].">181 a 360</option>\n";			
			$html .= "						<option value='13'".$selec[13].">Mas de 360</option>\n";			
			$html .= "					</select>\n";
			$html .= "				</td>\n";
			$html .= "			</tr>\n";
			
			$chk1 = ""; 
			$chk2 = "checked";
			if($datos['ordenar_por'] == '1')
			{
				$chk2 = ""; 
				$chk1 = "checked";
			}
			$html .= "			<tr class=\"normal_10AN\">\n";
			$html .= "				<td colspan=\"2\">\n";
			$html .= "					<input type=\"radio\" name=\"ordenar_por\" value =\"1\" $chk1>ORDENADA POR VALOR\n";
			$html .= "				</td>\n";
			$html .= "			</tr>\n";
			$html .= "			<tr class=\"normal_10AN\">\n";
			$html .= "				<td colspan=\"2\">\n";
			$html .= "					<input type=\"radio\" name=\"ordenar_por\" value =\"2\" $chk2 >ORDENADA ALFABETICAMENTE\n";
			$html .= "				</td>\n";
			$html .= "			</tr>\n";
			$html .= "			<tr>\n";
			$html .= "				<td class=\"label\" align=\"center\" colspan=\"2\"><br>\n";
			$html .= "					<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
			$html .= "					<input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"limpiarCampos(document.buscador)\">\n";
			$html .= "				</td>\n";
			$html .= "			</tr>\n";
			$html .= "		</table>\n";
			$html .= "	</fieldset>\n";
			$html .= "</form>\n";
			
			return $html;  
		}
		/*********************************************************************************
		* 
		* @return string
		*********************************************************************************/
		function FormaEnviosCliente($datos,$datos_cliente,$action)
		{
			IncludeClass('CarteraDetalle','','app','Cartera');
			$cdt = new CarteraDetalle();
		
			$total = 0;
			$sistemas = $cdt->ObtenerEnviosCliente($datos,$datos['empresa_id']);
			
			$html  = ThemeAbrirTabla("CARTERA DEL CLIENTE ".$datos['nombre_tercero']." - POR ENVIOS");;
			$html .= "<table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
			foreach($sistemas as $key => $sistema)
			{
				$html .= "	<tr class=\"modulo_table_list_title\">\n";
				$html .= "		<td colspan=\"6\">".$key."</td>\n";
				$html .= "	</tr>\n";
				$html .= "	<tr class=\"modulo_table_list_title\">\n";
				$html .= "		<td width=\"1%\"></td>\n";
				$html .= "		<td>N? ENVIO</td>\n";
				$html .= "		<td>F. RADICACION</td>\n";
				$html .= "		<td>F. REGISTRO</td>\n";
				$html .= "		<td>C. FACTURAS</td>\n";
				$html .= "		<td>VALOR ENVIO</td>\n";
				$html .= "	</tr>\n";
				foreach($sistema as $keyI => $envio)
				{
					$datos_cliente['envio_id'] = $envio['envio_id'];
					$datos_cliente['sistema'] = $envio['sistema'];
					
					$html .= "	<tr class=\"modulo_list_claro\">\n";
					$html .= "		<td>\n";
					$html .= "			<a href=\"".$action['cartera'].URLRequest(array("datos_cliente"=>$datos_cliente))."\" title=\"VER FACTURAS DEL ENVIO N?: ".$envio['envio_id']."\">\n";
					$html .= "				<img src=\"".GetThemePath()."/images/auditoria.png\" border=\"0\">\n";
					$html .= "			</a>\n";
					$html .= "		</td>\n";
					$html .= "		<td class=\"label\">".$envio['envio_id']."</td>\n";
					$html .= "		<td class=\"label\" align=\"center\">".$envio['fecha_radicacion']."</td>\n";
					$html .= "		<td class=\"label\" align=\"center\">".$envio['registro']."</td>\n";
					$html .= "		<td class=\"label\" align=\"right\">".$envio['cantidad_facturas']."</td>\n";
					$html .= "		<td class=\"normal_10AN\" align=\"right\">$".formatoValor($envio['valor_envio'])."</td>\n";
					$html .= "	</tr>\n";
					$total += $envio['valor_envio'];
				}
			}
			$html .= "	<tr class=\"modulo_table_list_title\">\n";
			$html .= "		<td colspan=\"5\">TOTALES</td>\n";
			$html .= "		<td align=\"right\">$".formatoValor($total)."</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
			$html .= "<table align=\"center\">\n";
			$html .= "	<tr>\n";
			$html .= "		<td>";
			$html .= "			<form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "				<input type=\"submit\" name=\"volver\" value=\"Volver\" class=\"input-submit\">\n";
			$html .= "			</form>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
			$html .= ThemeCerrarTabla();
			return $html;
		}
		/**
		* 
		* @return string
		*/
		function FormaBuscadorFacturas($rqst,$action,$empresa)
		{
			IncludeClass('CarteraDetalle','','app','Cartera');
			$ctd = new CarteraDetalle();
			$prefijos = $ctd->ObtenerPrefijos($empresa);
			
			$html  = "<script>\n";	
			$html .= "	function acceptNum(evt)\n";
			$html .= "	{\n";
			$html .= "		var nav4 = window.Event ? true : false;\n";
			$html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "		return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$html .= "	}\n";	
			$html .= "</script>\n";	
			$html .= "<form name=\"buscadorfacturas\" action=\"".$action['buscador']."\" method=\"post\">\n";
			$html .= "	<table class=\"modulo_table_list\" width=\"60%\" align=\"center\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td class=\"modulo_table_list_title\">BUSCADOR RAPIDO DE FACTURAS:&nbsp;</td>\n";
			$html .= "			<td>\n";
			$html .= "				<select name=\"prefijo\" class=\"select\">\n";
			
			$sel = "";
			foreach($prefijos as $key => $prf)
			{
				($rqst['prefijo'] == $key)? $sel = "selected":$sel="";
				$html .= "				<option value='".$key."' $sel>".$key."</option>\n";
			}
			
			$html .= "				</select>\n";
			$html .= "			</td>\n";
			$html .= "			<td>\n";
			$html .= "				<input type=\"text\" class=\"input-text\" name=\"factura_f\" size=\"25\" onkeypress=\"return acceptNum(event)\" value=\"".$rqst['factura_f']."\">\n";
			$html .= "			</td>\n";
			$html .= "			<td>\n";
			$html .= "				<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
			$html .= "		</td></tr>\n";
			$html .= "	</table>\n";
			$html .= "</form>\n"; 
			return $html;
		}
		/*********************************************************************************
		* 
		* @return string
		*********************************************************************************/
		function FormaBuscadorTercerosRecibos($rqst,$action,$empresa)
		{
			IncludeClass('CarteraDetalle','','app','Cartera');
			$ctd = new CarteraDetalle();
			$terceros = $ctd->ObtenerTipoIdTerceros();
			
			$html  = "<form name=\"buscador\" action=\"".$action['buscador']."\" method=\"post\">\n";
			$html .= "	<script>\n";
			$html .= "	function LimpiarCampos(frm)\n";
			$html .= "	{\n";
			$html .= "		for(i=0; i<frm.length; i++)\n";
			$html .= "		{\n";
			$html .= "			switch(frm[i].type)\n";
			$html .= "			{\n";
			$html .= "				case 'text': frm[i].value = ''; break;\n";
			$html .= "				case 'select-one': frm[i].selectedIndex = 0; break;\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "	}\n";
			$html .= "	</script>\n";
			$html .= "<center>\n";
			$html .= "	<fieldset class=\"fieldset\" style=\"width:55%\"><legend>BUSCADOR AVANZADO</legend>\n";
			$html .= "		<table width=\"100%\" align=\"center\">\n";
			$html .= "			<tr>\n";
			$html .= "				<td class=\"label\">TIPO DOCUMENTO CLIENTE</td>\n";
			$html .= "				<td >\n";
			$html .= "					<select name=\"tipo_id_tercero\" class=\"select\">\n";
			$html .= "						<option value='0'>-----SELECCIONAR-----</option>\n";
			
			foreach($terceros as $key => $opciones)
			{
				($rqst['tipo_id_tercero'] == $opciones['tipo_id_tercero'])? $sel = "selected":$sel = "";
				
				$html .= "						<option value='".$opciones['tipo_id_tercero']."' $sel>".ucwords(strtolower($opciones['descripcion']))."</option>\n";			
			}
			
			$html .= "					</select>\n";
			$html .= "				</td>\n";
			$html .= "			</tr>\n";
			$html .= "			<tr>\n";
			$html .= "				<td class=\"label\">DOCUMENTO</td>\n";
			$html .= "				<td >\n";
			$html .= "					<input type=\"text\" class=\"input-text\" name=\"documento_id\" size=\"25\" maxlength=\"32\"  value=\"".$rqst['documento_id']."\">\n";
			$html .= "				</td>\n";
			$html .= "			</tr>\n";
			$html .= "			<tr><td class=\"label\">NOMBRE CLIENTE:</td>\n";
			$html .= "				<td>\n";
			$html .= "					<input type=\"text\" class=\"input-text\" name=\"nombre_tercero\" size=\"25\" value=\"".$rqst['nombre_tercero']."\">\n";
			$html .= "				</td>\n";
			$html .= "			</tr>\n";
			$html .= "			<tr>\n";
			$html .= "				<td class=\"label\" align=\"center\" colspan=\"2\"><br>\n";
			$html .= "					<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
			$html .= "					<input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"LimpiarCampos(document.buscador)\">\n";
			$html .= "				</td>\n";
			$html .= "			</tr>\n";
			$html .= "		</table>\n";
			$html .= "	</fieldset>\n";
			$html .= "</center>\n";
			$html .= "</form>\n";
			
			return $html;  
		}
    /**
    *
    */
    function FormaSubmenu($action)
    {
      $html = ThemeAbrirTabla('MENU - MOVIMIENTOS DE CARTERA');
			$html.= "<table border=\"0\" width=\"50%\" align=\"center\">\n";
			$html.= "	<tr>\n";
			$html.= "		<td>\n";
			$html.= "			<table border=\"0\" width=\"100%\" align=\"center\" class=\"formulacion_table_list\">\n";
			$html.= "				<tr>\n";
			$html.= "					<td align=\"center\" class=\"formulacion_table_list\" ><b style=\"color:#ffffff\">MOVIMIENTOS</td>\n";
			$html.= "				</tr>\n";
			$html.= "				<tr><td class=\"modulo_list_claro\"></td></tr>\n";
			$html.= "				<tr>\n";
			$html.= "					<td class=\"modulo_list_claro\" align=\"center\">\n";
			$html.= "						<a href=\"".$action['facturas']."\"><b>BUSCAR FACTURAS</b></a>\n";
			$html.= "					</td>\n";
			$html.= "				</tr>\n";
			$html.= "				<tr><td class=\"modulo_list_claro\" ></td></tr>\n";
			$html.= "				<tr>\n";
			$html.= "					<td class=\"modulo_list_claro\" align=\"center\">\n";
			$html.= "						<a href=\"".$action['recibos']."\"><b>BUSCAR RECIBOS CAJA</b></a>\n";
			$html.= "					</td>\n";
			$html.= "				</tr>\n";
			$html.= "				<tr><td class=\"modulo_list_claro\"></td></tr>\n";
			$html.= "				<tr>\n";
			$html.= "					<td class=\"modulo_list_claro\" align=\"center\">\n";
			$html.= "						<a href=\"".$action['notas']."\"><b>BUSCAR NOTAS DE AJUSTE</b></a>\n";
			$html.= "					</td>\n";
			$html.= "				</tr>\n";			
      $html.= "				<tr><td class=\"modulo_list_claro\"></td></tr>\n";
			$html.= "				<tr>\n";
			$html.= "					<td class=\"modulo_list_claro\" align=\"center\">\n";
			$html.= "						<a href=\"".$action['pagares']."\"><b>BUSCAR PAGARES</b></a>\n";
			$html.= "					</td>\n";
			$html.= "				</tr>\n";
      $html.= "				<tr><td class=\"modulo_list_claro\"></td></tr>\n";
			$html.= "				<tr>\n";
			$html.= "					<td class=\"modulo_list_claro\" align=\"center\">\n";
			$html.= "						<a href=\"".$action['todas']."\"><b>MOSTRAR TODAS LAS FACTURAS</b></a>\n";
			$html.= "					</td>\n";
			$html.= "				</tr>\n";
			$html.= "				<tr><td class=\"modulo_list_claro\"></td></tr>\n";
			$html.= "			</table>\n";
			$html.= "		</td></tr>\n";
			$html.= "	<tr>\n";
			$html.= "		<td align=\"center\"><br>\n";
			$html.= "			<form name=\"form\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html.= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
			$html.= "			</form>\n";
			$html.= "		</td>\n";
			$html.= "	</tr>\n";
			$html.= "</table>\n";
			$html.= ThemeCerrarTabla();
      
      return $html;
    }
	}
?>