<?php
  /******************************************************************************
  * $Id: CarteraFacturacionHTML.class.php,v 1.1 2007/05/15 19:06:32 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.1 $ 
	* 
	* @autor Hugo F  Manrique 
  ********************************************************************************/
	class CarteraFacturacionHTML
	{
		function CarteraFacturacionHTML(){}
		/********************************************************************************
		*
		* @return boolean
		*********************************************************************************/
		function FormaFacturasEnvio($datos,$request,$action)
		{
			IncludeClass('CarteraDetalle','','app','Cartera');
			$ctd = new CarteraDetalle();
			$periodos = $ctd->ObtenerFactiurasEnvio($datos);

			$clase = "";
			$est = "modulo_list_oscuro";
			$total = $saldo =	$glosa = $notas = $nc = $nd = $recibos = $aceptado = $pendiente = $noaceptado = 0;			
			
			$html  = ThemeAbrirTabla("CARTERA DEL CLIENTE ".$datos['nombre_tercero'].", ENVIO Nº:".$request['envio_id']);;
			$html .= "	<table border=\"0\" width=\"500\" align=\"center\">\n";
			$html .= "		<tr class=\"modulo_table_list_title\" height=\"22\">\n";
			$html .= "			<td class=\"formulacion_table_list\"  width=\"33%\"><b>FACTURAS VENCIDAS</b></td>\n";
			$html .= "			<td class=\"modulo_table_list_title\" width=\"34%\"><b>FACTURAS DE ESTE MES</b></td>\n";
			$html .= "			<td class=\"modulo_table_title\" style=\"text-align:center\" width=\"33%\"><b>FACTURAS POR VENCER</b></td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table><br>\n";
			$html .= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "		<tr class=\"modulo_table_list_title\" height=\"18\">\n";
			$html .= "			<td width=\"7%\">FACTURA</td>\n";
			$html .= "			<td width=\"8%\">TOTAL</td>\n";
			$html .= "			<td width=\"8%\">SALDO</td>\n";
			$html .= "			<td width=\"7%\">GLOSAS</td>\n";
			$html .= "			<td width=\"9%\">V. ACEPTADO</td>\n";
			$html .= "			<td width=\"10%\">V. NO ACEPTADO</td>\n";
			$html .= "			<td width=\"9%\">V. PENDIENTE</td>\n";
			$html .= "			<td width=\"7%\">RECIBOS</td>\n";
			$html .= "			<td width=\"7%\">N. AJUSTE</td>\n";
			$html .= "			<td width=\"7%\">N. CREDITO</td>\n";
			$html .= "			<td width=\"7%\">N. DEBITO</td>\n";
			$html .= "			<td width=\"4%\" colspan=\"2\" width=\"%\">OP</td>\n";
			$html .= "		</tr>\n";
						
			foreach($periodos as $key => $facturas)
			{
				$detalle_fc = "";
				foreach($facturas as $keyI => $detalle)
				{
					($est == "modulo_list_oscuro")? $est = "modulo_list_claro": $est = "modulo_list_oscuro";
					
					$detalle_fc .= "		<tr class=\"".$est."\">\n";
					$detalle_fc .= "			<td  class=\"normal_10AN\" >&nbsp;".$detalle['prefijo']." ".$detalle['factura_fiscal']."</td>\n";
					$detalle_fc .= "			<td align=\"right\" >".formatoValor($detalle['total'])."</td>\n";
					$detalle_fc .= "			<td align=\"right\" >".formatoValor($detalle['saldo'])."</td>\n";
					$detalle_fc .= "			<td align=\"right\" >".formatoValor($detalle['valor_glosa'])."</td>\n";
					$detalle_fc .= "			<td align=\"right\" >".formatoValor($detalle['valor_aceptado'])."</td>\n";
					$detalle_fc .= "			<td align=\"right\" >".formatoValor($detalle['valor_no_aceptado'])."</td>\n";
					$detalle_fc .= "			<td align=\"right\" >".formatoValor($detalle['valor_pendiente'])."</td>\n";
					$detalle_fc .= "			<td align=\"right\" >".formatoValor($detalle['valor_abonado_rc'])."</td>\n";
					$detalle_fc .= "			<td align=\"right\" >".formatoValor($detalle['valor_abonado_na'])."</td>\n";
					$detalle_fc .= "			<td align=\"right\" >".formatoValor($detalle['valor_nota_credito'])."</td>\n";
					$detalle_fc .= "			<td align=\"right\" >".formatoValor($detalle['valor_nota_debito'])."</td>\n";
					$detalle_fc .= "			<td align=\"center\" width=\"2%\">\n";
					if($detalle['sistema'] == 'EXT')
						$detalle_fc .= "				<a href=\"javascript:MostrarInformacionFacturaExterna('".$detalle['prefijo']."','".$detalle['factura_fiscal']."','".$datos['empresa_id']."')\" title=\"INFORMACION DE LA FACTURA\">\n";
					else
						$detalle_fc .= "				<a href=\"javascript:MostrarDetalleFactura('".$detalle['prefijo']."','".$detalle['factura_fiscal']."','".$datos['empresa_id']."')\" title=\"INFORMACION DE LA FACTURA\">\n";
					
					$detalle_fc .= "					<img src=\"".GetThemePath()."/images/pconsultar.png\" border=\"0\">\n";
					$detalle_fc .= "				</a>\n";
					$detalle_fc .= "			</td>\n";
					$detalle_fc .= "			<td align=\"center\" width=\"2%\" >\n";
					if($detalle['valor_glosa'] > 0)
					{
						$detalle_fc .= "				<a href=\"javascript:MostrarDetalleGlosa('".$detalle['prefijo']."','".$detalle['factura_fiscal']."','".$datos['empresa_id']."','".$detalle['sistema']."')\" title=\"INFORMACIÓN GLOSAS FACTURA\">\n";
						$detalle_fc .= "					<img src=\"".GetThemePath()."/images/Listado.png\" border=\"0\">\n";
						$detalle_fc .= "				</a>\n";
					}
					$detalle_rc .= "			</td>\n";
					$detalle_fc .= "		</tr>\n";
					
					$nc += $detalle['valor_nota_credito'];
					$nd += $detalle['valor_nota_debito'];
					$total += $detalle['total'];
					$saldo += $detalle['saldo'];
					$glosa += $detalle['valor_glosa'];
					$notas += $detalle['valor_abonado_na'];
					$recibos += $detalle['valor_abonado_rc'];
					$aceptado += $detalle['valor_aceptado'];
					$pendiente += $detalle['valor_pendiente'];
					$noaceptado += $detalle['valor_no_aceptado'];
					
					if($detalle['intervalo'] == 0)
						$clase = "modulo_table_list_title";
					else if($detalle['intervalo'] < 0)
						$clase = "formulacion_table_list";
					else
						$clase = "modulo_table_title";
				}
				$html .= "		<tr class=\"".$clase."\">\n";
				$html .= "			<td colspan=\"13\" align=\"center\">\n";
				$html .= "				".$key."\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				$html .= $detalle_fc;
			}
			$html .= "	</table><br>\n";

			$Pnc = number_format(($nc/$total)*100,3,',','.');
			$Pnd = number_format(($nd/$total)*100,3,',','.');
			$Pglosa = number_format(($glosa/$total)*100,3,',','.');
			$Pnotas = number_format(($notas/$total)*100,3,',','.');
			$Psaldo = number_format(($saldo/$total)*100,3,',','.');
			$Precibos = number_format(($recibos/$total)*100,3,',','.');
			$Paceptado = number_format(($aceptado/$total)*100,3,',','.');
			$Ppendiente = number_format(($pendiente/$total)*100,3,',','.');
			$Pnoaceptado = number_format(($noaceptado/$total)*100,3,',','.');
			
			$html .= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\" >\n";
			$html .= "		<tr class=\"modulo_table_list_title\" height=\"21\" >\n";
			$html .= "			<td width=\"7%\">TOTAL</td>\n";
			$html .= "			<td width=\"8%\" align=\"right\">".formatoValor($total)."</td>\n";
			$html .= "			<td width=\"8%\" align=\"right\">".formatoValor($saldo)."</td>\n";
			$html .= "			<td width=\"7%\" align=\"right\">".formatoValor($glosa)."</td>\n";
			$html .= "			<td width=\"9%\" align=\"right\">".formatoValor($aceptado)."</td>\n";
			$html .= "			<td width=\"10%\" align=\"right\">".formatoValor($noaceptado)."</td>\n";
			$html .= "			<td width=\"9%\" align=\"right\">".formatoValor($pendiente)."</td>\n";
			$html .= "			<td width=\"7%\" align=\"right\">".formatoValor($recibos)."</td>\n";
			$html .= "			<td width=\"7%\" align=\"right\">".formatoValor($notas)."</td>\n";
			$html .= "			<td width=\"7%\" align=\"right\">".formatoValor($nc)."</td>\n";
			$html .= "			<td width=\"7%\" align=\"right\">".formatoValor($nd)."</td>\n";
			$html .= "			<td width=\"4%\" align=\"right\"></td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_table_list_title\" height=\"23\" >\n";
			$html .= "			<td >PORCEN</td>\n";
			$html .= "			<td align=\"right\">100 %</td>\n";
			$html .= "			<td align=\"right\">".$Psaldo." %</td>\n";
			$html .= "			<td align=\"right\">".$Pglosa." %</td>\n";
			$html .= "			<td align=\"right\">".$Paceptado." %</td>\n";
			$html .= "			<td align=\"right\">".$Pnoaceptado." %</td>\n";
			$html .= "			<td align=\"right\">".$Ppendiente." %</td>\n";
			$html .= "			<td align=\"right\">".$Precibos." %</td>\n";
			$html .= "			<td align=\"right\">".$Pnotas." %</td>\n";
			$html .= "			<td align=\"right\">".$Pnc." %</td>\n";
			$html .= "			<td align=\"right\">".$Pnd." %</td>\n";
			$html .= "			<td align=\"right\"></td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table><br>\n";
			$html .= "<table align=\"center\">\n";
			$html .= "	<tr>\n";
			$html .= "		<td>";
			$html .= "			<form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "				<input type=\"submit\" name=\"volver\" value=\"Volver\" class=\"input-submit\">\n";
			$html .= "			</form>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
			
			$html .= "<script>\n";
			$html .= "	function MostrarDetalleFactura(prefijo,factura,empresa)\n";
			$html .= "	{\n";
			$html .= "		xajax_InformacionFactura(prefijo,factura,empresa);\n";
			$html .= "	}\n";
			
			$html .= "	function MostrarInformacionFacturaExterna(prefijo,factura,empresa)\n";
			$html .= "	{\n";
			$html .= "		xajax_InformacionFacturaExterna(prefijo,factura,empresa);\n";
			$html .= "	}\n";
			
			$html .= "	function MostrarInformacionCuenta(cuenta,empresa)\n";
			$html .= "	{\n";
			$html .= "		xajax_InformacionCuenta(cuenta,empresa);\n";
			$html .= "	}\n";
			
			$html .= "	function MostrarDetalleGlosa(prefijo,factura,empresa,sistema)\n";
			$html .= "	{\n";
			$html .= "		xajax_InformacionGlosa(prefijo,factura,empresa,sistema);\n";
			$html .= "	}\n";
			
			$html .= "	function MostrarGlosa(glosa,empresa,sistema)\n";
			$html .= "	{\n";
			$html .= "		xajax_InformacionGlosaDetalle(empresa,glosa,sistema);\n";
			$html .= "	}\n";
			
			$html .= "	function MostrarDetalle()\n";
			$html .= "	{\n";
			$html .= "		Iniciar();\n";
			$html .= "		MostrarSpan('Facturas');\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			$html .= $this->VentanaDetalle();
			$html .= ThemeCerrarTabla();
			return $html;
		}
		/********************************************************************************
		*
		* @return boolean
		*********************************************************************************/
		function FormaMostrarFacturasPorRango($rangos_facturas,$datos,$totales,$action)
		{
			$est = $bck =  "";
			IncludeClass('CarteraDetalle','','app','Cartera');
			
			$titulo = "CARTERA DEL CLIENTE ".$datos['nombre_tercero'];
			if($datos['plan_descripcion'] != "")
				$titulo = "CARTERA DEL PLAN ".$datos['plan_descripcion'];
				
			$html  = ThemeAbrirTabla($titulo." - ".$datos['rango']);
			$html .= "<script language=\"javascript\">\n";
			$html .= "	function mOvr(src,clrOver)\n";
			$html .= "	{\n";
			$html .= "		src.style.background = clrOver;\n";
			$html .= "	}\n";
			$html .= "	function mOut(src,clrIn)\n";
			$html .= "	{\n";
			$html .= "		src.style.background = clrIn;\n";
			$html .= "	}\n";
			$html .= "	function MostrarDetalleFactura(prefijo,factura,empresa)\n";
			$html .= "	{\n";
			$html .= "		xajax_InformacionFactura(prefijo,factura,empresa);\n";
			$html .= "	}\n";
			
			$html .= "	function MostrarInformacionFacturaExterna(prefijo,factura,empresa)\n";
			$html .= "	{\n";
			$html .= "		xajax_InformacionFacturaExterna(prefijo,factura,empresa);\n";
			$html .= "	}\n";
			
			$html .= "	function MostrarInformacionCuenta(cuenta,empresa)\n";
			$html .= "	{\n";
			$html .= "		xajax_InformacionCuenta(cuenta,empresa);\n";
			$html .= "	}\n";
			
			$html .= "	function MostrarDetalleGlosa(prefijo,factura,empresa,sistema)\n";
			$html .= "	{\n";
			$html .= "		xajax_InformacionGlosa(prefijo,factura,empresa,sistema);\n";
			$html .= "	}\n";
			
			$html .= "	function MostrarGlosa(glosa,empresa,sistema)\n";
			$html .= "	{\n";
			$html .= "		xajax_InformacionGlosaDetalle(empresa,glosa,sistema);\n";
			$html .= "	}\n";
			
			$html .= "	function MostrarDetalle()\n";
			$html .= "	{\n";
			$html .= "		Iniciar();\n";
			$html .= "		MostrarSpan('Facturas');\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			$html .= $this->VentanaDetalle();

			$html .= "	<table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\" height=\"21\">\n";
			$html .= "		<tr class=\"modulo_table_list_title\" height=\"18\">\n";
			$html .= "			<td width=\"10%\">FACTURA</td>\n";
			$html .= "			<td width=\"11%\">TOTAL</td>\n";
			$html .= "			<td width=\"15%\">SALDO</td>\n";
			$html .= "			<td width=\"15%\">V. GLOSA</td>\n";
			$html .= "			<td width=\"15%\">V. ACEPTADO</td>\n";
			$html .= "			<td width=\"15%\">V. NO ACEPTADO</td>\n";
			$html .= "			<td width=\"15%\">V. PENDIENTE</td>\n";
			$html .= "			<td width=\"4%\" colspan=\"2\">OPCIONES</td>\n";
			$html .= "		</tr>\n";
						
			foreach($rangos_facturas as $key => $facturas)
			{
				($est == "modulo_list_oscuro")? $est = "modulo_list_claro": $est = "modulo_list_oscuro";
				($bck == "#CCCCCC")? $bck = "#DDDDDD":$bck = "#CCCCCC";
				
				$html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFF'); height=\"21\">\n";			
				$html .= "			<td >".$facturas['prefijo']." ".$facturas['factura_fiscal']."</td>\n";
				$html .= "			<td align=\"right\" >".formatoValor($facturas['total'])."</td>\n";
				$html .= "			<td align=\"right\" >".formatoValor($facturas['saldo'])."</td>\n";
				$html .= "			<td align=\"right\" >".formatoValor($facturas['valor_glosa'])."</td>\n";
				$html .= "			<td align=\"right\" >".formatoValor($facturas['valor_aceptado'])."</td>\n";
				$html .= "			<td align=\"right\" >".formatoValor($facturas['valor_no_aceptado'])."</td>\n";
				$html .= "			<td align=\"right\" >".formatoValor($facturas['valor_pendiente'])."</td>\n";
				
				$html .= "			<td align=\"center\" width=\"2%\">\n";
				$html .= "				<a href=\"javascript:MostrarDetalleFactura('".$facturas['prefijo']."','".$facturas['factura_fiscal']."','".$datos['empresa_id']."')\" title=\"INFORMACION DE LA FACTURA\">\n";
				$html .= "					<img src=\"".GetThemePath()."/images/pconsultar.png\" border=\"0\">\n";
				$html .= "				</a>\n";
				$html .= "			</td>\n";
				$html .= "			<td align=\"center\" width=\"2%\" >\n";
				if($facturas['valor_glosa'] > 0)
				{
					$html .= "				<a href=\"javascript:MostrarDetalleGlosa('".$facturas['prefijo']."','".$facturas['factura_fiscal']."','".$datos['empresa_id']."','SIIS')\" title=\"INFORMACIÓN GLOSAS FACTURA\">\n";
					$html .= "					<img src=\"".GetThemePath()."/images/Listado.png\" border=\"0\">\n";
					$html .= "				</a>\n";
				}
				$html .= "			</td>\n";	
				$html .= "		</tr>\n";
			}
			$html .= "</table><br>";
			$html .= "	<table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\" height=\"21\">\n";
			$html .= "		<tr class=\"modulo_table_list_title\" height=\"21\">\n";			
			$html .= "			<td width=\"9%\"><b>TOTALES</b></td>\n";
			$html .= "			<td align=\"right\" width=\"11%\">".formatoValor($totales[0])."</td>\n";
			$html .= "			<td align=\"right\" width=\"14%\">".formatoValor($totales[1])."</td>\n";
			$html .= "			<td align=\"right\" width=\"15%\">".formatoValor($totales[2])."</td>\n";
			$html .= "			<td align=\"right\" width=\"14%\">".formatoValor($totales[3])."</td>\n";
			$html .= "			<td align=\"right\" width=\"14%\">".formatoValor($totales[4])."</td>\n";
			$html .= "			<td align=\"right\" width=\"14%\">".formatoValor($totales[5])."</td>\n";
			$html .= "			<td width=\"%\">&nbsp;</td>\n";
			$html .= "		</tr>\n";
			$html .= "</table><br>";
			$html .= "<table align=\"center\">\n";
			$html .= "	<tr>\n";
			$html .= "		<td align=\"center\"><br>\n";
			$html .= "			<form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
			$html .= "			</form>";
			$html .= "		</td>";
			$html .= "	</tr>";
			$html .= "</table>";
			$html .= ThemeCerrarTabla();
			
			return $html;
		}
		/********************************************************************************
		*
		* @return boolean
		*********************************************************************************/
		function VentanaDetalle()
		{
			$html .= "<script>\n";
			$html .= "	var contenedor = 'Contenedor';\n";
			$html .= "	var titulo = 'titulo';\n";
			$html .= "	var hiZ = 2;\n";
			
			$html .= "	function Iniciar()\n";
			$html .= "	{\n";
			$html .= "		contenedor = 'Facturas';\n";
			$html .= "		titulo = 'titulob';\n";
			$html .= "		ele = xGetElementById('ContenidoB');\n";
			$html .= "	  xResizeTo(ele,750,360);\n";
			$html .= "		ele = xGetElementById(contenedor);\n";
			$html .= "	  xResizeTo(ele,750, 'auto');\n";
			$html .= "	  xMoveTo(ele, xClientWidth()/8, xScrollTop()+10);\n";
			$html .= "		ele = xGetElementById(titulo);\n";
			$html .= "	  xResizeTo(ele,730, 20);\n";
			$html .= "		xMoveTo(ele, 0, 0);\n";
			$html .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$html .= "		ele = xGetElementById('cerrarb');\n";
			$html .= "	  xResizeTo(ele,20, 20);\n";
			$html .= "		xMoveTo(ele, 730, 0);\n";
			$html .= "	}\n";

			$html .= "	function OcultarSpan(Seccion)\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
			$html .= "			e = xGetElementById(Seccion);\n";
			$html .= "			e.style.display = \"none\";\n";
			$html .= "		}\n";
			$html .= "		catch(error){}\n";
			$html .= "	}\n";
			$html .= "	function MostrarTitle(Seccion)\n";
			$html .= "	{\n";
			$html .= "		xShow(Seccion);\n";
			$html .= "	}\n";
			$html .= "	function OcultarTitle(Seccion)\n";
			$html .= "	{\n";
			$html .= "		xHide(Seccion);\n";
			$html .= "	}\n";
			$html .= "	function MostrarSpan(Seccion)\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
			$html .= "			e = xGetElementById(Seccion);\n";
			$html .= "			e.style.display = \"\";\n";
			$html .= "		}\n";
			$html .= "		catch(error){alert(error)}\n";
			$html .= "	}\n";
			$html .= "	function myOnDragStart(ele, mx, my)\n";
			$html .= "	{\n";
			$html .= "	  window.status = '';\n";
			$html .= "	  if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
			$html .= "	  else xZIndex(ele, hiZ++);\n";
			$html .= "	  ele.myTotalMX = 0;\n";
			$html .= "	  ele.myTotalMY = 0;\n";
			$html .= "	}\n";
			$html .= "	function myOnDrag(ele, mdx, mdy)\n";
			$html .= "	{\n";
			$html .= "	  if (ele.id == titulo) {\n";
			$html .= "	    xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
			$html .= "	  }\n";
			$html .= "	  else {\n";
			$html .= "	    xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
			$html .= "	  }  \n";
			$html .= "	  ele.myTotalMX += mdx;\n";
			$html .= "	  ele.myTotalMY += mdy;\n";
			$html .= "	}\n";
			$html .= "	function myOnDragEnd(ele, mx, my)\n";
			$html .= "	{\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			$html .= "<div id='Facturas' class='d2Container' style=\"display:none\">\n";
			$html .= "	<div id='titulob' class='draggable' style=\"	text-transform: uppercase;text-align:center\">INFORMACION DE LA FACTURA</div>\n";
			$html .= "	<div id='cerrarb' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('Facturas')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
			$html .= "	<div id='ContenidoB' class='d2Content' style=\"background:#FEFEFE\"><br>\n";
			$html .= "	</div>\n";
			$html .= "</div>\n";
			return $html;
		}
		/********************************************************************************
		*
		* @return boolean
		*********************************************************************************/
		function FormaMostrarTodasFacturas($request,$action,$facturas,$envios,$meses,$pag,$prefijos)
		{
			$s1 = $s2 = $s3 = $s4 = "";
			if($request['facturacion'] == '1')
				$s1 = "selected";
			else if($request['facturacion'] == '2')
				$s2 = "selected";
				else if($request['facturacion'] == '3')
					$s3 = "selected";
					else if($request['facturacion'] == '4')
						$s4 = "selected";			
			$html .= ThemeAbrirTabla("MOSTRAR TODAS LAS FACTURAS");
			$html .= "<script>\n";
			$html .= "	function Validar(frm)\n";
			$html .= "	{\n";
			$html .= "		if(frm.facturacion.value == '0')\n";
			$html .= "		{\n";
			$html .= "			document.getElementById('error').innerHTML = 'SE DEBE SELECCIONAR EL TIPO DE FACTURAS QUE SE DESEA VER';\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(frm.meses.value == '0' && frm.factura_f.value == '' && frm.envio.value == '')\n";
			$html .= "		{\n";
			$html .= "			document.getElementById('error').innerHTML = 'PARA REALIZAR LA BUSQUEDA, SE DEBE SELECCIONAR EL MES O INGRESAR UNA FACTURA O UN NUMERO DE ENVIO';\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		frm.action = \"".$action['buscar']."\";\n";
			$html .= "		frm.submit();\n";
			$html .= "	}\n";
			$html .= "	function acceptNum(evt)\n";
			$html .= "	{\n";
			$html .= "		var nav4 = window.Event ? true : false;\n";
			$html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "		return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$html .= "	}\n";	
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
			$html .= "</script>\n";
			$html .= "<form name=\"buscar_facturas\" action=\"javascript:Validar(document.buscar_facturas)\" method=\"post\">";
			$html .= "	<center>\n";
			$html .= "		<div id =\"error\" class=\"label_error\"></div>\n";
			$html .= "	</center>\n";
			$html .= "	<table align=\"center\" class=\"modulo_table_list\"width=\"55%\">\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td style=\"text-indent:8pt\" align=\"left\" width=\"33%\">TIPO DE FACTURACION</td>\n";
			$html .= "			<td align=\"justify\" class=\"modulo_list_claro\" colspan=\"3\" >\n";
			$html .= "				<select name=\"facturacion\" class=\"select\" >\n";
			$html .= "					<option value='0'>--SELECCIONAR--</option>\n";
			$html .= "					<option value='4' $s4 >FACTURACION DEL SISTEMA</option>\n";			
			$html .= "					<option value='1' $s1 >FACTURACION DEL SISTEMA - RADICADA</option>\n";			
			$html .= "					<option value='2' $s2 >FACTURACION DEL SISTEMA - SIN RADICAR</option>\n";			
			$html .= "					<option value='3' $s3 >FACTURACION EXTERNA</option>\n";			
			$html .= "				</select>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td style=\"text-indent:8pt\" align=\"left\" width=\"33%\">MES</td>\n";
			$html .= "			<td align=\"justify\" class=\"modulo_list_claro\" >\n";
			$html .= "				<select name=\"meses\" class=\"select\" >\n";
			$html .= "					<option value='0'>--SELECCIONAR--</option>\n";

			foreach($meses as $key => $mes)
			{
				($key == $request['meses'])? $sel = " selected ":$sel = "";
				$html .= "					<option value='".$key."' $sel >".$mes."</option>\n";			
			}
		
			$html .= "				</select>\n";
			$html .= "			</td>\n";
			$html .= "			<td style=\"text-indent:8pt\" align=\"left\" width=\"15%\">AÑO</td>\n";
			$html .= "			<td align=\"justify\" class=\"modulo_list_claro\" >\n";
			$html .= "				<select name=\"anyo\" class=\"select\" >\n";

			for( $i = date("Y") ; $i > 2005; $i--)
			{
				($i == $request['anyo'])? $sel = " selected ":$sel = "";
				$html .= "					<option value='".$i."' $sel >".$i."</option>\n";			
			}
		
			$html .= "				</select>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td align=\"left\" style=\"text-indent:8pt\" width=\"33%\">FACTURA:&nbsp;</td>\n";
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\" colspan=\"3\">\n";
			$html .= "				<select name=\"prefijo\" class=\"select\">\n";
					
			$sel = "";
			foreach($prefijos as $key => $prf)
			{
				($request['prefijo'] == $key)? $sel = "selected":$sel="";
				$html .= "				<option value='".$key."' $sel>".$key."</option>\n";
			}
					
			$html .= "				</select>\n";
			$html .= "				<input type=\"text\" class=\"input-text\" name=\"factura_f\" style=\"width:30%\" onkeypress=\"return acceptNum(event)\" value=\"".$request['factura_f']."\">\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td align=\"left\" style=\"text-indent:8pt\" >ENVIO</td>\n";
			$html .= "			<td align=\"left\" colspan=\"3\"  class=\"modulo_list_claro\">\n";
			$html .= "				<input type=\"text\" class=\"input-text\" name=\"envio\" style=\"width:46%\" onkeypress=\"return acceptNum(event)\" value=\"".$request['envio']."\">\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr>\n";
			$html .= "			<td align=\"center\" colspan=\"4\">\n";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"Buscar\">\n";
			$html .= "				<input class=\"input-submit\" type=\"button\" name=\"limpiar\" value=\"Limpiar Campos\" onclick=\"LimpiarCampos(document.buscar_facturas)\">\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "</form>\n";
			if(!empty($facturas))
			{
				$es = " style=\"color:#FFFFFF;text-decoration: none;\" ";
				$html .= "<table class=\"modulo_table_list\" align=\"center\" width=\"100%\">\n";
				$html .= "	<tr class=\"modulo_table_list_title\">\n";
				$html .= "		<td width=\"7%\" class=\"formulacion_table_list\">\n";
				$html .= "			<a href=\"".$action['paginador'].URLRequest(array("offset"=>$pag['pagina_actual'],"orden"=>"1"))."\" $es>\n";
				$html .= "				FACTURA\n";
				$html .= "			</a>\n";
				$html .= "		</td>\n";
				$html .= "		<td width=\"%8\">FECHA</td>\n";
				$html .= "		<td width=\"38%\" colspan=\"2\">PACIENTE</td>\n";
				$html .= "		<td width=\"33%\" >PLAN</td>\n";
				$html .= "		<td width=\"7%\" class=\"formulacion_table_list\">";
				$html .= "			<a href=\"".$action['paginador'].URLRequest(array("offset"=>$pag['pagina_actual'],"orden"=>"2"))."\" $es>\n";
				$html .= "				ENVIO\n";
				$html .= "			</a>\n";
				$html .= "		</td>\n";
				$html .= "		<td width=\"%8\">F. ENVIO</td>\n";
				$html .= "		<td width=\"%9\">RADICACION</td>\n";
				$html .= "	</tr>\n";
				
				$est = "modulo_list_claro";
				foreach($facturas as $key => $detalle)
				{
					($est == "modulo_list_claro")? $est = "modulo_list_oscuro":$est = "modulo_list_claro";
					
					$html .= "	<tr class=\"$est\" height=\"16\">\n";
					$html .= "		<td>".$detalle['prefijo']." ".$detalle['factura_fiscal']."</td>\n";
					$html .= "		<td align=\"center\">".$detalle['fecha_registro']."</td>\n";
					$html .= "		<td width=\"14%\">".$detalle['identificacion']."</td>\n";
					$html .= "		<td>".$detalle['nombre']." ".$detalle['apellido']."</td>\n";
					$html .= "		<td class=\"label\">".$detalle['plan_descripcion']."</td>\n";
					if(empty($envios))
					{
						$html .= "		<td>".$detalle['envio_id']."</td>\n";
						$html .= "		<td align=\"center\">".$detalle['fecha_envio']."</td>\n";
						$html .= "		<td align=\"center\">".$detalle['fecha_radicacion']."</td>\n";
					}
					else
					{
						$html .= "		<td>".$envios[$detalle['prefijo']][$detalle['factura_fiscal']]['envio_id']."</td>\n";
						$html .= "		<td align=\"center\">".$envios[$detalle['prefijo']][$detalle['factura_fiscal']]['fecha_envio']."</td>\n";
						$html .= "		<td align=\"center\">".$envios[$detalle['prefijo']][$detalle['factura_fiscal']]['fecha_radicacion']."</td>\n";
					}
					$html .= "	</tr>\n";
				}
				
				$html .= "</table>\n";
				IncludeClass('ClaseHTML');
				$pg = new ClaseHTML();
				$html .= "		<br>\n";
				$html .= "		".$pg->ObtenerPaginado($pag['cantidad'],$pag['pagina_actual'],$action['paginador']);
				$html .= "		<br>\n";
			}
			else if($request['facturacion'])
			{
				$html .= "<center>\n";
				$html .= "	<div class=\"label_error\">LA BUSQUEDAD NO ARROJO RESULTADO</div>\n";
				$html .= "</center>\n";
			}
			

			$html .= "<table align=\"center\">\n";
			$html .= "	<tr>\n";
			$html .= "		<td align=\"center\"><br>\n";
			$html .= "			<form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
			$html .= "			</form>";
			$html .= "		</td>";
			$html .= "	</tr>";
			$html .= "</table>";
			$html .= ThemeCerrarTabla();
			return $html;
		}
	}
?>