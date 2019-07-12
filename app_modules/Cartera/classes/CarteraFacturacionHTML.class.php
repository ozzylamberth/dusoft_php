<?php
  /**
  * $Id: CarteraFacturacionHTML.class.php,v 1.4 2009/06/26 13:53:16 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.4 $ 
	* 
	* @autor Hugo F  Manrique 
  */
	class CarteraFacturacionHTML
	{
		function CarteraFacturacionHTML(){}
		/*********************************************************************************
		*
		* @return boolean
		*********************************************************************************/
		function FormaFacturasEnvio($datos,$request,$action)
		{
			IncludeClass('CarteraDetalle','','app','Cartera');
			$ctd = new CarteraDetalle();
			$periodos = $ctd->ObtenerFacturasEnviadas($datos);

			$clase = "";
			$est = "modulo_list_oscuro";
			$total = $saldo =	$glosa = $notas = $nc = $nd = $recibos = $aceptado = $pendiente = $noaceptado = 0;			
			
			$html  = ThemeAbrirTabla("CARTERA DEL CLIENTE ".$datos['nombre_tercero']." - ENVIO Nº:".$request['envio_id']);;
			$html .= "	<table border=\"0\" width=\"500\" align=\"center\">\n";
			$html .= "		<tr class=\"modulo_table_list_title\" height=\"22\">\n";
			$html .= "			<td class=\"formulacion_table_list\"  width=\"33%\"><b>FACTURAS VENCIDAS</b></td>\n";
			$html .= "			<td class=\"modulo_table_list_title\" width=\"34%\"><b>FACTURAS DE ESTE MES</b></td>\n";
			$html .= "			<td class=\"modulo_table_title\" style=\"text-align:center\" width=\"33%\"><b>FACTURAS POR VENCER</b></td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table><br>\n";
			$html .= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "		<tr class=\"modulo_table_list_title\" height=\"18\">\n";
			$html .= "			<td width=\"7%\">FACT</td>\n";
			$html .= "			<td width=\"8%\">TOTAL</td>\n";
			$html .= "			<td width=\"8%\">RTF</td>\n";
			$html .= "			<td width=\"8%\">SALDO</td>\n";
			$html .= "			<td width=\"7%\">V. GLOSAS</td>\n";
			$html .= "			<td width=\"9%\">V. NG</td>\n";
			$html .= "			<td width=\"7%\">V. NA</td>\n";
			$html .= "			<td width=\"7%\">V. NC</td>\n";
			$html .= "			<td width=\"7%\">V. ND</td>\n";
			$html .= "			<td width=\"7%\">V. RC</td>\n";
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
					$detalle_fc .= "			<td align=\"right\" >".formatoValor($detalle['total_factura'])."</td>\n";
					$detalle_fc .= "			<td align=\"right\" >".formatoValor($detalle['retencion'])."</td>\n";
					$detalle_fc .= "			<td align=\"right\" >".formatoValor($detalle['saldo'])."</td>\n";
					$detalle_fc .= "			<td align=\"right\" >".formatoValor($detalle['valor_glosa'])."</td>\n";
					$detalle_fc .= "			<td align=\"right\" >".formatoValor($detalle['total_nota_glosa'])."</td>\n";
					$detalle_fc .= "			<td align=\"right\" >".formatoValor($detalle['total_nota_ajuste'])."</td>\n";
					$detalle_fc .= "			<td align=\"right\" >".formatoValor($detalle['total_nota_credito'])."</td>\n";
					$detalle_fc .= "			<td align=\"right\" >".formatoValor($detalle['total_nota_debito'])."</td>\n";
					$detalle_fc .= "			<td align=\"right\" >".formatoValor($detalle['total_recibo'])."</td>\n";

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
					
					$nc += $detalle['total_nota_credito'];
					$nd += $detalle['total_nota_debito'];
					$na += $detalle['total_nota_ajuste'];
					$ng += $detalle['total_nota_glosa'];
					$rtf += $detalle['retencion'];
					$total += $detalle['total_factura'];
					$saldo += $detalle['saldo'];
					$glosa += $detalle['valor_glosa'];
					$recibos += $detalle['total_recibo'];
					
					if($detalle['intervalo'] == 0)
						$clase = "modulo_table_list_title";
					else
						$clase = "formulacion_table_list";
				}
				$html .= "		<tr class=\"".$clase."\">\n";
				$html .= "			<td colspan=\"13\" align=\"center\">\n";
				$html .= "				".$key."\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				$html .= $detalle_fc;
			}

			$Pnc = number_format(($nc/$total)*100,3,',','.');
			$Pnd = number_format(($nd/$total)*100,3,',','.');
			$Pna = number_format(($na/$total)*100,3,',','.');
			$Png = number_format(($ng/$total)*100,3,',','.');
			$Prtf = number_format(($rtf/$total)*100,3,',','.');
			$Pglosa = number_format(($glosa/$total)*100,3,',','.');
			$Psaldo = number_format(($saldo/$total)*100,3,',','.');
			$Precibos = number_format(($recibos/$total)*100,3,',','.');
			
			$html .= "		<tr class=\"modulo_table_list_title\" height=\"21\" >\n";
			$html .= "			<td >TOTAL</td>\n";
			$html .= "			<td align=\"right\">".formatoValor($total)."</td>\n";
			$html .= "			<td align=\"right\">".formatoValor($rtf)."</td>\n";
			$html .= "			<td align=\"right\">".formatoValor($saldo)."</td>\n";
			$html .= "			<td align=\"right\">".formatoValor($glosa)."</td>\n";
			$html .= "			<td align=\"right\">".formatoValor($ng)."</td>\n";
			$html .= "			<td align=\"right\">".formatoValor($na)."</td>\n";
			$html .= "			<td align=\"right\">".formatoValor($nc)."</td>\n";
			$html .= "			<td align=\"right\">".formatoValor($nd)."</td>\n";
			$html .= "			<td align=\"right\">".formatoValor($recibos)."</td>\n";
			$html .= "			<td colspan=\"2\"></td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_table_list_title\" height=\"23\" >\n";
			$html .= "			<td >PORCEN</td>\n";
			$html .= "			<td align=\"right\">100 %</td>\n";
			$html .= "			<td align=\"right\">".$Prtf." %</td>\n";
			$html .= "			<td align=\"right\">".$Psaldo." %</td>\n";
			$html .= "			<td align=\"right\">".$Pglosa." %</td>\n";
			$html .= "			<td align=\"right\">".$Png." %</td>\n";
			$html .= "			<td align=\"right\">".$Pna." %</td>\n";
			$html .= "			<td align=\"right\">".$Pnc." %</td>\n";
			$html .= "			<td align=\"right\">".$Pnd." %</td>\n";
			$html .= "			<td align=\"right\">".$Precibos." %</td>\n";
			$html .= "			<td colspan=\"2\"></td>\n";
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
		/**
		* Funcion donde se crea la forma para mostrar las facturas pertenecientes
    * a un intervalo determinado
    *
    * @param array $registros Arreglo de datos de las facturas
    * @param array $datos Arreglo de datos del request
    * @param array $totales Arreglo de datos con los totales
    * @param array $action Arreglo de datos de los links
    * 
		* @return boolean
		*/
		function FormaMostrarFacturasPorRango($registros,$datos,$totales,$action)
		{
      $est = $bck =  "";
			
			$titulo = "CARTERA DEL CLIENTE ".$datos['nombre_tercero'];
			if($datos['plan_descripcion'] != "")
				$titulo = "CARTERA DEL PLAN ".$datos['plan_descripcion'];
			
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
        
      foreach($registros as $k1 => $rangos_facturas)
      {
  			$html .= ThemeAbrirTabla($titulo." - ".$k1);
  			$html .= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\" height=\"21\">\n";
  			$html .= "		<tr class=\"modulo_table_list_title\" height=\"18\">\n";
  			$html .= "			<td width=\"10%\">FACTURA</td>\n";
  			$html .= "			<td width=\"9%\">TOTAL</td>\n";
  			$html .= "			<td width=\"9%\">RTF</td>\n";
  			$html .= "			<td width=\"9%\">SALDO</td>\n";
  			$html .= "			<td width=\"9%\">V. GLOSA</td>\n";
  			$html .= "			<td width=\"9%\">V. NG</td>\n";
  			$html .= "			<td width=\"9%\">V. NC</td>\n";
  			$html .= "			<td width=\"9%\">V. ND</td>\n";
  			$html .= "			<td width=\"9%\">V. NA</td>\n";
  			$html .= "			<td width=\"9%\">V. RC</td>\n";
  			$html .= "			<td width=\"9%\" colspan=\"2\">OPCIONES</td>\n";
  			$html .= "		</tr>\n";
  						
  			foreach($rangos_facturas as $key => $facturas)
  			{
  				($est == "modulo_list_oscuro")? $est = "modulo_list_claro": $est = "modulo_list_oscuro";
  				($bck == "#CCCCCC")? $bck = "#DDDDDD":$bck = "#CCCCCC";
  				
          $total += $facturas['total_factura'];
          $saldo += $facturas['saldo'];
          $rtf += $facturas['retencion'];
          $glosa += $facturas['valor_glosa'];
          $ng += $facturas['total_nota_glosa'];
          $nc += $facturas['total_nota_credito'];
          $nd += $facturas['total_nota_debito'];
          $na += $facturas['total_nota_ajuste'];
          $recibos += $facturas['total_recibo'];
          
  				$html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFF'); height=\"21\">\n";			
  				$html .= "			<td >".$facturas['prefijo']." ".$facturas['factura_fiscal']."</td>\n";
  				$html .= "			<td align=\"right\" >".formatoValor($facturas['total_factura'])."</td>\n";
  				$html .= "			<td align=\"right\" >".formatoValor($facturas['retencion'])."</td>\n";
  				$html .= "			<td align=\"right\" >".formatoValor($facturas['saldo'])."</td>\n";
  				$html .= "			<td align=\"right\" >".formatoValor($facturas['valor_glosa'])."</td>\n";
  				$html .= "			<td align=\"right\" >".formatoValor($facturas['total_nota_glosa'])."</td>\n";
  				$html .= "			<td align=\"right\" >".formatoValor($facturas['total_nota_credito'])."</td>\n";
  				$html .= "			<td align=\"right\" >".formatoValor($facturas['total_nota_debito'])."</td>\n";
  				$html .= "			<td align=\"right\" >".formatoValor($facturas['total_nota_ajuste'])."</td>\n";
  				$html .= "			<td align=\"right\" >".formatoValor($facturas['total_recibo'])."</td>\n";
  				
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
  			$html .= "		<tr class=\"modulo_table_list_title\" height=\"21\">\n";			
  			$html .= "			<td width=\"9%\"><b>TOTALES</b></td>\n";
  			$html .= "			<td align=\"right\">".formatoValor($total)."</td>\n";
  			$html .= "			<td align=\"right\">".formatoValor($rtf)."</td>\n";
  			$html .= "			<td align=\"right\">".formatoValor($saldo)."</td>\n";
  			$html .= "			<td align=\"right\">".formatoValor($glosa)."</td>\n";
  			$html .= "			<td align=\"right\">".formatoValor($ng)."</td>\n";
  			$html .= "			<td align=\"right\">".formatoValor($nc)."</td>\n";
  			$html .= "			<td align=\"right\">".formatoValor($nd)."</td>\n";
  			$html .= "			<td align=\"right\">".formatoValor($na)."</td>\n";
  			$html .= "			<td align=\"right\">".formatoValor($recibos)."</td>\n";
  			$html .= "			<td colspan=\"2\">&nbsp;</td>\n";
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
			}
			return $html;
		}
		/*********************************************************************************
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
		/*********************************************************************************
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