<?php
  /******************************************************************************
  * $Id: Facturacion_RecepcionHTML.class.php,v 1.6 2007/06/26 23:29:14 carlos Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.6 $ 
	* 
  ********************************************************************************/
	IncludeClass('Facturacion_Recepcion','','app','Facturacion_Recepcion');
	IncludeClass("ClaseHTML");
	class Facturacion_RecepcionHTML
	{
		var $offset = 0;
		
		function Facturacion_RecepcionHTML(){}
		/**
		***
		**/
		function BuscarPermisosUser()
		{
	
				//list($dbconn) = GetDBconn();
				//GLOBAL $ADODB_FETCH_MODE;
	
				//$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
	
				$fact = new Facturacion_Recepcion();
	
				$datos = $fact->ObtenerPermisos(UserGetUID());
	
				$url[0]='app';
				$url[1]='Facturacion_Recepcion';
				$url[2]='user';
				$url[3]='LlamaMenuRecepcion';
				$url[4]='control';
	
				$arreglo[0]='EMPRESA';
				$arreglo[1]='CENTRO UTILIDAD';
				$arreglo[2]='CONTROL DE CIERRES';
	
				UNSET($_SESSION['FACTURACION_RECEPCION']);
				$html = gui_theme_menu_acceso('SELECCIONAR CENTRO DE UTILIDAD',$arreglo,$datos,$url,SessionGetVar("VolverPermisos"));
				return $html;
	
		}

		function Menu($EmpresaId, $CentroUtilidadId, $Empresa, $CentroUtilidad)
		{
			$mostrar ="\n<SCRIPT>\n";
			$mostrar.="function mOvr(src,clrOver) {;\n";
			$mostrar.="src.style.background = clrOver;\n";
			$mostrar.="}\n";
	
			$mostrar.="function mOut(src,clrIn) {\n";
			$mostrar.="src.style.background = clrIn;\n";
			$mostrar.="}\n";
			$mostrar.= "function SeleccionarTodos(frm,x,y_inicial,y_final)\n";
			$mostrar.= "{\n";
			$mostrar.= "  if(x==true)\n";
			$mostrar.= "  { \n";
			$mostrar.= "    for(i = 0; i < frm.elements.length; i++)\n";
			$mostrar.= "    {\n";
			$mostrar.= "      if(frm.elements[i].name != 'Todos')\n";
			$mostrar.= "      {\n";
			$mostrar.= "        for(j = 0; j < y_final; j++)\n";
			$mostrar.= "        {\n";
			$mostrar.= "          if(frm.elements[i].type=='checkbox' && frm.elements[i].name=='check'+y_inicial+j)\n";
			$mostrar.= "          {\n";
			$mostrar.= "            frm.elements[i].checked=true\n";
			$mostrar.= "          }\n";
			$mostrar.= "        }\n";
			$mostrar.= "      }\n";
			$mostrar.= "    }\n";
			$mostrar.= "  }\n";
			$mostrar.= "  else\n";
			$mostrar.= "  {\n";
			$mostrar.= "    for(i = 0; i < frm.elements.length; i++)\n";
			$mostrar.= "    {\n";
			$mostrar.= "      if(frm.elements[i].name != 'Todos')\n";
			$mostrar.= "      {\n";
			$mostrar.= "        for(j = 0; j <= y_final; j++)\n";
			$mostrar.= "        {\n";
			$mostrar.= "          if(frm.elements[i].type=='checkbox' && frm.elements[i].name=='check'+y_inicial+j)\n";
			$mostrar.= "          {\n";
			$mostrar.= "            frm.elements[i].checked=false\n";
			$mostrar.= "          }\n";
			$mostrar.= "        }\n";
			$mostrar.= "      }\n";
			$mostrar.= "    }\n";
			$mostrar.= "  }\n";
			$mostrar.= "}\n";
			$mostrar .= "  function Iniciar()\n";
			$mostrar .= "  {\n";   
			$mostrar .= "    document.getElementById('titulo').innerHTML = '<center>RECEPCIÓN FACTURA CREDITO</center>';\n";
			$mostrar .= "    document.getElementById('error').innerHTML = '';\n";                
			$mostrar .= "    contenedor = 'd2Container';\n";
			$mostrar .= "    titulo = 'titulo';\n";
			$mostrar .= "    ele = xGetElementById('d2Container');\n";
			$mostrar .= "    xResizeTo(ele,400, 'auto');\n";
			$mostrar .= "    xMoveTo(ele, xClientWidth()/5, xScrollTop()+24);\n";
			$mostrar .= "    ele = xGetElementById('titulo');\n";
			$mostrar .= "    xResizeTo(ele,380, 20);\n";
			$mostrar .= "    xMoveTo(ele, 0, 0);\n";
			$mostrar .= "    xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$mostrar .= "    ele = xGetElementById('cerrar');\n";
			$mostrar .= "    xResizeTo(ele,20,20);\n";
			$mostrar .= "    xMoveTo(ele,380, 0);\n";
			$mostrar .= "  }\n";
			$mostrar .= "  function myOnDragStart(ele, mx, my)\n";
			$mostrar .= "  {\n";
			$mostrar .= "    window.status = '';\n";
			$mostrar .= "    if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
			$mostrar .= "    else xZIndex(ele, hiZ++);\n";
			$mostrar .= "    ele.myTotalMX = 0;\n";
			$mostrar .= "    ele.myTotalMY = 0;\n";
			$mostrar .= "  }\n";
			$mostrar .= "  function myOnDrag(ele, mdx, mdy)\n";
			$mostrar .= "  {\n";
			$mostrar .= "    if (ele.id == titulo) {\n";
			$mostrar .= "      xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
			$mostrar .= "    }\n";
			$mostrar .= "    else {\n";
			$mostrar .= "      xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
			$mostrar .= "    }  \n";
			$mostrar .= "    ele.myTotalMX += mdx;\n";
			$mostrar .= "    ele.myTotalMY += mdy;\n";
			$mostrar .= "  }\n";
			$mostrar .= "  function myOnDragEnd(ele, mx, my)\n";
			$mostrar .= "  {\n";
			$mostrar .= "  }\n";
			$mostrar .= "  function MostrarSpan(Seccion)\n";
			$mostrar .= "  { \n";
			$mostrar .= "    e = xGetElementById(Seccion);\n";
			$mostrar .= "    e.style.display = \"\";\n";
			$mostrar .= "  }\n";
			$mostrar .= "  function Cerrar()\n";
			$mostrar .= "  { \n";
			$mostrar .= "    e = xGetElementById('d2Container');\n";
			$mostrar .= "    e.style.display = \"none\";\n";
			$mostrar .= "  }\n";
			$mostrar .= "  function MostrarVentana()\n";
			$mostrar .= "  { \n";
			$mostrar .= "    e = xGetElementById('d2Container');\n";
			$mostrar .= "    e.style.display = \"block\";\n";
			$mostrar .= "    ele = xGetElementById('d2Contents');\n";
			$mostrar .= "    ele.style.display = \"block\";\n";
			$mostrar .= "  }\n";
			$mostrar .= "  function LlamaAdicionarFacturaRecepcion(e,p,n,t,indicetabla,indicetr,agrupada)";
			$mostrar .= "  {";
			$mostrar .= "    xajax_AdicionarFacturaRecepcion(e,p,n,t,indicetabla,indicetr,agrupada);";
			$mostrar .= "  }";
			$mostrar .="</SCRIPT>\n";
			$html .="$mostrar";
			$backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
	
			$ventana = "  <div id='d2Container' class='d2Container' style=\"display:none\">\n";
			$ventana.= "  <div id='titulo' class='draggable' style=\" text-transform: uppercase;\"></div>\n";
			$ventana.= "  <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('d2Container')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
			$ventana.= "  <div id='error' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
			$ventana.= "  <div id='d2Contents'>\n";
			$ventana.= "  </div>";
			$ventana.= "  </div>";        
			$html   .=$ventana;

			//unset($_SESSION['FACTURACION_RECEPCION']['DATOS']);
			unset($_SESSION['FACTURACION_RECEPCION']['OBSERVACION']);
			$html.= ThemeAbrirTabla('RECEPCIÓN FACTURAS CREDITO','95%');
			$html.=$this->Encabezado($Empresa, $CentroUtilidad);
			//$accion=ModuloGetURL('app','Facturacion_Recepcion','user','LlamaFormaMovimientoFacturasCredito',array('EmpresaId'=>$EmpresaId,'CentroUtilidadId'=>$CentroUtilidadId));
/*			$html.= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
			$html.="<br><table border=\"0\"    align=\"center\"   width=\"95%\">";
			$html.="<tr>";
			$html.= "<td width=\"50%\" align=\"center\">";
			$html.= "fechaInicial<input type=\"text\" class=\"input-text\" name=\"fechaInicial\" size=\"12\" value=\"".$_REQUEST['fechaInicial']."\">";
			$html.= "&nbsp;&nbsp;".ReturnOpenCalendario('formabuscar','fechaInicial','/')."";
			$html.= "</td>";
			$html.= "<td width=\"50%\" align=\"center\">";
			$html.= "fechaFinal<input type=\"text\" class=\"input-text\" name=\"fechaFinal\" size=\"12\" value=\"".$_REQUEST['fechaFinal']."\">";
			$html.= "&nbsp;&nbsp;".ReturnOpenCalendario('formabuscar','fechaFinal','/')."";
			$html.= "</td>";
			$html.="</tr>";
			$html.="</table>";
			$html.="<br><table border=\"0\" align=\"center\"   width=\"95%\">";
			$html.="<tr>";
			$html.= "<td width=\"50%\" align=\"right\"><br><input class=\"input-submit\" name=\"ver\" type=\"submit\" value=\"Aceptar\">";
			$html.="</td>";
			$html.="</form>";*/
			$html.="<br><table border=\"0\" align=\"center\"   width=\"100%\">";
			$html.="<tr>";
			$html.="<td width=\"100%\" align=\"right\">";
			$action2=SessionGetVar("VolverMenu");
			$html .= " <form name=\"forma\" action=\"$action2\" method=\"post\">";
			$html .= " <a href=\"$action2\" title=\"VOLVER\"><img src=\"".GetThemePath()."/images/boton.png\" border=\"0\" width=\"15\" height=\"15\"></a></form>";
			$html .= "</td>";
			$html .= "</tr>";
			$accion=ModuloGetURL('app','Facturacion_Recepcion','user','LlamaActualizarMovimientoFacturasCredito',array('EmpresaId'=>$EmpresaId,'CentroUtilidadId'=>$CentroUtilidadId, 'Empresa'=>$Empresa,'CentroUtilidad'=> $CentroUtilidad));
			$html.= "<form name=\"formaagrupadas\" action=\"$accion\" method=\"post\">";
			$html.="</table><br>";
			//if($_REQUEST[fechaInicial] AND $_REQUEST[fechaFinal])
			//{
				$dat = new Facturacion_Recepcion();
				//FACTURAS AGRUPADAS
/*
				$agrupadas = $dat->ObtenerDatosFacturasCreditoAgrupadas($EmpresaId,$CentroUtilidadId);
				if(is_array($agrupadas) AND sizeof($agrupadas) > 0)
				{
					for($i=0; $i<sizeof($agrupadas);)
					{
						$total_usuario = 0;
						$estilo = 'modulo_list_oscuro'; $backgrounds = "#DDDDDD";
						$k = $i;
						$html.= "<table id=\"tablafacturasagrupadas$i\" border=\"0\" align=\"center\"   width=\"100%\">";
						$html.= "<tr class=\"$estilo\" onmouseout=mOut(this,\"".$backgrounds."\"); onmouseover=mOvr(this,'#FFFFFF');>";
						$html.= " <td width=\"100%\" colspan=\"6\" align=\"left\">Facturador: <b>".$agrupadas[$k][usuario_id].' '.$agrupadas[$k][nombre]."</b>";
						$html.= " </td>";
						$html.= "</tr>";
						$estilo='modulo_table_title';
						$html.= "<tr class=\"$estilo\">";
						$html.= " <td width=\"10%\" align=\"center\">FACTURA";
						$html.= " </td>";
						$html.= " <td width=\"30%\" align=\"center\">CLIENTE";
						$html.= " </td>";
						$html.= " <td width=\"40%\" align=\"center\">PLAN";
						$html.= " </td>";
						$html.= " <td width=\"10%\" align=\"center\">VALOR";
						$html.= " </td>";
						$tmp_html = "";
						$j= 0;
						while($agrupadas[$i][usuario_id]==$agrupadas[$k][usuario_id])
						{
							$estilo = 'modulo_list_oscuro'; $backgrounds = "#DDDDDD";
							if($k % 2 == 0)
							{
								$estilo = 'modulo_list_claro'; $backgrounds = "#CCCCCC";
							}
							if($var[$k][sw_estado] != 0 )
							{
								$cambia = '#7A99BB';
							}
							else
							{
								$cambia = '#FFFFFF';
							}

							$total_usuario += $agrupadas[$k][valor];
							$cambia = '#5efb6e';
							$tmp_html.= "<tr id='facturasrecibidasagrupadas$k".$agrupadas[$k][empresa_id]."//||".$agrupadas[$k][prefijo]."//||".$agrupadas[$k][factura_fiscal]."' class=\"$estilo\" onmouseout=mOut(this,\"".$backgrounds."\"); onmouseover=mOvr(this,'$cambia');>";
							$tmp_html.= " <td width=\"10%\" align=\"center\">".$agrupadas[$k][prefijo].' '.$agrupadas[$k][factura_fiscal]."";
							$tmp_html.= " </td>";
							$tmp_html.= " <td width=\"30%\" align=\"center\">".$agrupadas[$k][nombre_tercero]."";
							$tmp_html.= " </td>";
							$tmp_html.= " <td width=\"40%\" align=\"center\">".$agrupadas[$k][plan_descripcion]."";
							$tmp_html.= " </td>";
							$tmp_html.= " <td width=\"10%\" align=\"center\">".$agrupadas[$k][valor]."";
							$tmp_html.= " </td>";
							if($agrupadas[$k][sw_estado] == 0)
							{
								$tmp_html.= " <td width=\"2%\" align=\"center\"><input id=\"check".$agrupadas[$k][empresa_id]."//||".$agrupadas[$k][prefijo]."//||".$agrupadas[$k][factura_fiscal]."\" name=\"check$i$j\" type=\"checkbox\" value=\"".$agrupadas[$k][empresa_id]."//||".$agrupadas[$k][prefijo]."//||".$agrupadas[$k][factura_fiscal]."\">";
								$tmp_html.= " </td>";
								$tmp_html.= " <td id='td_agrupadas$i$k' width=\"8%\" align=\"center\"><a href=\"javascript:LlamaAdicionarFacturaRecepcion('".$agrupadas[$k][empresa_id]."','".$agrupadas[$k][prefijo]."','".$agrupadas[$k][factura_fiscal]."',".sizeof($agrupadas).",".$i.",".$k.",'1');\" title=\"\"><img src=\"".GetThemePath()."/images/auditoria_selec.png\" border=\"0\" width=\"15\" height=\"15\" title=\"SIN RECEPCIÓN\"></a>";
								$tmp_html.= " </td>";
											
							}
							elseif($agrupadas[$k][sw_estado] == 1)
							{
								if(!empty($agrupadas[$k][observacion_movimiento]))
								{
									$observacion_recepcion = $agrupadas[$k][fecha_registro].'/'.$agrupadas[$k][observacion_movimiento];
								}
								else
								{
									$observacion_recepcion = 'FACTURA RECIBIDA SIN OBSERVACIÓN';
								}
								$tmp_html.= " <td width=\"2%\" align=\"center\">&nbsp;";
								$tmp_html.= " </td>";
								$tmp_html.= " <td width=\"8%\" align=\"center\"><img src=\"".GetThemePath()."/images/auditoria.png\" border=\"0\" width=\"15\" height=\"15\" title=\"".$observacion_recepcion."\">";
								$tmp_html.= " </td>";
							}
							$tmp_html.= "</tr>";
							$k++;
							$j++;
						}
						$html.= " <td width=\"2%\" align=\"center\"><input name=\"Todos\" type=\"checkbox\" onclick=\"SeleccionarTodos(this.form,this.checked,$i,$j)\">";
						$html.= " </td>";
						$html.= " <td width=\"8%\" align=\"center\">&nbsp;";
						$html.= " </td>";
						$html.= "</tr>";

						$html.= $tmp_html;

						$html.= "<tr class=\"$estilo\" onmouseout=mOut(this,\"".$backgrounds."\"); onmouseover=mOvr(this,'#FFFFFF');>";
						$html.= " <td width=\"100%\" colspan=\"3\" align=\"right\"><b>TOTAL : </b>";
						$html.= " <td width=\"100%\" align=\"center\">$&nbsp;&nbsp;<font color=\"red\"><b>".FormatoValor($total_usuario)."</font></b>";
						$html.= " <td width=\"100%\" align=\"center\">&nbsp;";
						$html.= " <td width=\"100%\" align=\"center\">&nbsp;";
						$html.= " </td>";
						$html.= "</tr>";
						$i = $k;
					}
					$html.= "</table>";
				}
				else
				{
					$html.="<br><table border=\"0\" align=\"center\"   width=\"100%\">";
					$html.="<tr class='modulo_table_list'>";
					$html.="<td width=\"100%\" align=\"CENTER\"><label class=\"label_error\">No hay movimiento de facturas crédito agrupadas para recepción</label>";
					$html.="</td>";
					$html.="</tr>";
					$html.="</table>";
				}
*/
				$html.="<table border=\"0\" align=\"center\"   width=\"100%\">";
				$html.= "<form name=\"formaagrupadas\" action=\"$accion\" method=\"post\">";
				//$html.="<tr class='formulacion_table_list'>";
				//$html.="<tr class='modulo_table_title'>";
				$html.="<tr class='modulo_table_list_title'>";
				$html.="<td width=\"100%\" align=\"CENTER\">MOVIMIENTO FACTURAS CREDITO";
				$html.="</td>";
				$html.="</tr>";
				$html.="</table><br>";


				//FACTURAS NO AGRUPADAS // AGRUPADAS
				$var = $dat->ObtenerDatosFacturasCredito($EmpresaId,$CentroUtilidadId,$_REQUEST[fechaInicial],$_REQUEST[fechaFinal],$_REQUEST['offset']);
				$this->conteo = $dat->conteo;
				$this->paginaActual = $dat->paginaActual;
				if(is_array($var) AND sizeof($var) > 0)
				{
					for($i=0; $i<sizeof($var);)
					{
						$k = $i; 
						$estilo = 'modulo_list_oscuro'; $backgrounds = "#DDDDDD";
						$html.= "<table id=\"tablafacturasrecibidas$i\" border=\"0\" align=\"center\"   width=\"100%\">";
						$html.= "<tr class=\"$estilo\" onmouseout=mOut(this,\"".$backgrounds."\"); onmouseover=mOvr(this,'#FFFFFF');>";
						$html.= " <td width=\"100%\" colspan=\"6\" align=\"left\">Facturador: <b>".$var[$k][usuario_id].' '.$var[$k][nombre]."</b>";
						$html.= " </td>";
						$html.= "</tr>";

						$estilo='modulo_table_title';
						//$html.= "<br><table border=\"0\" align=\"center\"   width=\"100%\">";
						$html.= "<tr class=\"$estilo\">";
						$html.= " <td width=\"10%\" align=\"center\">FACTURA";
						$html.= " </td>";
						$html.= " <td width=\"30%\" align=\"center\">CLIENTE";
						$html.= " </td>";
						if($var[$i][tipo_factura] <> '3' AND $var[$i][tipo_factura] <> '4')
						{
							$label = 'PACIENTE';
						}
						else
						{
							$label = 'PLAN - AGRUPADO';
						}
						$html.= " <td width=\"40%\" align=\"center\">$label";
						$html.= " </td>";
						$html.= " <td width=\"10%\" align=\"center\">VALOR";
						$html.= " </td>";
//
						$total_usuario = $j= 0;
						$tmp_html = "";
						while($var[$i][usuario_id]==$var[$k][usuario_id])
						{
							$estilo = 'modulo_list_oscuro'; $backgrounds = "#DDDDDD";
							if($k % 2 == 0)
							{
								$estilo = 'modulo_list_claro'; $backgrounds = "#CCCCCC";
							}
							if($var[$k][sw_estado] != 0 )
							{
								$cambia = '#7A99BB';
							}
							else
							{
								$cambia = '#FFFFFF';
							}

							if($var[$k][tipo_factura] == '3' OR $var[$k][tipo_factura] == '4')
							{
								$cambia = '#5efb6e';
							}

							$total_usuario += $var[$k][valor];
							$tmp_html.= "<tr id='facturasrecibidas$k".$var[$k][empresa_id]."//||".$var[$k][prefijo]."//||".$var[$k][factura_fiscal]."' class=\"$estilo\" onmouseout=mOut(this,\"".$backgrounds."\"); onmouseover=mOvr(this,'$cambia');>";
							$tmp_html.= " <td width=\"10%\" align=\"center\">".$var[$k][prefijo].' '.$var[$k][factura_fiscal]."";
							$tmp_html.= " </td>";
							$tmp_html.= " <td width=\"30%\" align=\"center\">".$var[$k][cliente]."";
							$tmp_html.= " </td>";
							$tmp_html.= " <td width=\"40%\" align=\"center\">".$var[$k][paciente]."";
							$tmp_html.= " </td>";
							$tmp_html.= " <td width=\"10%\" align=\"center\">".$var[$k][valor]."";
							$tmp_html.= " </td>";
							if($var[$k][sw_estado] == 0)
							{
								$tmp_html.= " <td width=\"2%\" align=\"center\"><input id=\"check".$var[$k][empresa_id]."//||".$var[$k][prefijo]."//||".$var[$k][factura_fiscal]."\" name=\"check$i$j\" type=\"checkbox\" value=\"".$var[$k][empresa_id]."//||".$var[$k][prefijo]."//||".$var[$k][factura_fiscal]."\">";
								$tmp_html.= " </td>";
								$tmp_html.= " <td id='td$i$k' width=\"8%\" align=\"center\"><a href=\"javascript:LlamaAdicionarFacturaRecepcion('".$var[$k][empresa_id]."','".$var[$k][prefijo]."','".$var[$k][factura_fiscal]."',".sizeof($var).",".$i.",".$k.",'0');\" title=\"\"><img src=\"".GetThemePath()."/images/auditoria_selec.png\" border=\"0\" width=\"15\" height=\"15\" title=\"SIN RECEPCIÓN\"></a>";
								$tmp_html.= " </td>";
							}
							elseif($var[$k][sw_estado] == 1)
							{
								if(!empty($var[$k][observacion_movimiento]))
								{
									$observacion_recepcion = $var[$k][fecha_registro].'/'.$var[$k][observacion_movimiento];
								}
								else
								{
									$observacion_recepcion = 'FACTURA RECIBIDA SIN OBSERVACIÓN';
								}
								$tmp_html.= " <td width=\"2%\" align=\"center\">&nbsp;";
								$tmp_html.= " </td>";
								$tmp_html.= " <td width=\"8%\" align=\"center\"><img src=\"".GetThemePath()."/images/auditoria.png\" border=\"0\" width=\"15\" height=\"15\" title=\"".$observacion_recepcion."\">";
								$tmp_html.= " </td>";
							}
							$tmp_html.= "</tr>";
							$k++;
							$j++;
						}
//
						$html.= " <td width=\"2%\" align=\"center\"><input name=\"Todos\" type=\"checkbox\" onclick=\"SeleccionarTodos(this.form,this.checked,$i,$j)\">";
						$html.= " </td>";
						$html.= " <td width=\"8%\" align=\"center\">&nbsp;";
						$html.= " </td>";
						$html.= "</tr>";
						
						$html.= $tmp_html;
						
						$html.= "<tr class=\"$estilo\" onmouseout=mOut(this,\"".$backgrounds."\"); onmouseover=mOvr(this,'#FFFFFF');>";
						$html.= " <td width=\"100%\" colspan=\"3\" align=\"right\"><b>TOTAL : </b>";
						$html.= " <td width=\"100%\" align=\"center\">$&nbsp;&nbsp;<font color=\"red\"><b>".FormatoValor($total_usuario)."</font></b>";
						$html.= " <td width=\"100%\" align=\"center\">&nbsp;";
						$html.= " <td width=\"100%\" align=\"center\">&nbsp;";
						$html.= " </td>";
						$html.= "</tr>";
						$i = $k;
					}

					$html.= "<tr>";
					$html.= " <td align=\"center\" colspan = \"6\">&nbsp;</td>";
					$html.= "</tr>";
					$html.= "<tr>";
					$html.= "  <td align=\"center\" colspan = \"6\"><input type=\"submit\" value=\"Guardar\" class=\"input-submit\"></td>";
					$html.= "</tr>";
					$html.= "</table>";
					$Paginador = new ClaseHTML();
					$html .= "      <br>\n";
					$action = ModuloGetURL("app","Facturacion_Recepcion","user","LlamaMenuRecepcion",array('EmpresaId'=>$Empresa,'CentroUtilidadId'=>$CentroUtilidad));
					$html .= "      ".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$action);
				}
				else
				{
					$html .= "<br><center><b>NO HAY MOVIMIENTO</b></center>";
				}
				$html.="</form>";
				
			//}
			$html.= ThemeCerrarTabla();
			return $html;
		}

		function Encabezado($Empresa, $CentroUtilidad)
		{
			$html = "<br><table  border=\"0\" class=\"modulo_table_title\" width=\"80%\" align=\"center\" >";
			$html .= " <tr class=\"modulo_table_title\">";
			$html .= " <td>EMPRESA</td>";
			$html .= " <td>CENTRO UTILIDAD</td>";
			$html .= " <td>MODULO</td>";
			$html .= " </tr>";
			$html .= " <tr align=\"center\">";
			$html .= " <td class=\"modulo_list_claro\" >".$Empresa."</td>";
			$html .= " <td class=\"modulo_list_claro\">".$CentroUtilidad."</td>";
			$html .= " <td class=\"modulo_list_claro\" >CONTROL DE CIERRES</td>";
			$html .= " </tr>";
			$html .= " </table>";
			return $html;
		}
		
	}
?>