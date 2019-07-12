<?php
	/**
	* @package IPSOFT-SIIS
	* @version $Id: CajaGeneralEmpresaHTML.class.php,v 1.0 
	* @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres
	*/

	IncludeClass("ClaseHTML");
  IncludeClass("ClaseUtil");

	class CajaGeneralEmpresaHTML
	{
	/**
		* Constructor de la clase
	*/

	function  CajaGeneralEmpresaHTML()
	{}
	 
	/*
		* Funcion donde se crea una Forma con una Ventana con capas para mostrar informacion
		* en pantalla
		* @param int $tmn Tamaño que tendra la ventana
		* @return string
    */
		function CrearVentana($tmn,$Titulo)
		{
			$html .= "<script>\n";
			$html .= "  var contenedor = 'Contenedor';\n";
			$html .= "  var titulo = 'titulo';\n";
			$html .= "  var hiZ = 4;\n";
			$html .= "  function OcultarSpan()\n";
			$html .= "  { \n";
			$html .= "    try\n";
			$html .= "    {\n";
			$html .= "      e = xGetElementById('Contenedor');\n";
			$html .= "      e.style.display = \"none\";\n";
			$html .= "    }\n";
			$html .= "    catch(error){}\n";
			$html .= "  }\n";
			$html .= "  function MostrarSpan()\n";
			$html .= "  { \n";
			$html .= "    try\n";
			$html .= "    {\n";
			$html .= "      e = xGetElementById('Contenedor');\n";
			$html .= "      e.style.display = \"\";\n";
			$html .= "      Iniciar();\n";
			$html .= "    }\n";
			$html .= "    catch(error){alert(error)}\n";
			$html .= "  }\n";
			$html .= "  function MostrarTitle(Seccion)\n";
			$html .= "  {\n";
			$html .= "    xShow(Seccion);\n";
			$html .= "  }\n";
			$html .= "  function OcultarTitle(Seccion)\n";
			$html .= "  {\n";
			$html .= "    xHide(Seccion);\n";
			$html .= "  }\n";
			$html .= "  function Iniciar()\n";
			$html .= "  {\n";
			$html .= "    contenedor = 'Contenedor';\n";
			$html .= "    titulo = 'titulo';\n";
			$html .= "    ele = xGetElementById('Contenido');\n";
			$html .= "    xResizeTo(ele,".$tmn.", 'auto');\n";
			$html .= "    ele = xGetElementById(contenedor);\n";
			$html .= "    xResizeTo(ele,".$tmn.", 'auto');\n";
			$html .= "    xMoveTo(ele, xClientWidth()/4, xScrollTop()+20);\n";
			$html .= "    ele = xGetElementById(titulo);\n";
			$html .= "    xResizeTo(ele,".($tmn - 20).", 20);\n";
			$html .= "    xMoveTo(ele, 0, 0);\n";
			$html .= "    xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$html .= "    ele = xGetElementById('cerrar');\n";
			$html .= "    xResizeTo(ele,20, 20);\n";
			$html .= "    xMoveTo(ele,".($tmn - 20).", 0);\n";
			$html .= "  }\n";
			$html .= "  function myOnDragStart(ele, mx, my)\n";
			$html .= "  {\n";
			$html .= "    window.status = '';\n";
			$html .= "    if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
			$html .= "    else xZIndex(ele, hiZ++);\n";
			$html .= "    ele.myTotalMX = 0;\n";
			$html .= "    ele.myTotalMY = 0;\n";
			$html .= "  }\n";
			$html .= "  function myOnDrag(ele, mdx, mdy)\n";
			$html .= "  {\n";
			$html .= "    if (ele.id == titulo) {\n";
			$html .= "      xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
			$html .= "    }\n";
			$html .= "    else {\n";
			$html .= "      xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
			$html .= "    }  \n";
			$html .= "    ele.myTotalMX += mdx;\n";
			$html .= "    ele.myTotalMY += mdy;\n";
			$html .= "  }\n";
			$html .= "  function myOnDragEnd(ele, mx, my)\n";
			$html .= "  {\n";
			$html .= "  }\n";
			$html.= "function Cerrar(Elemento)\n";
			$html.= "{\n";
			$html.= "    capita = xGetElementById(Elemento);\n";
			$html.= "    capita.style.display = \"none\";\n";
			$html.= "}\n";
			$html .= "</script>\n";
			$html .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:4\">\n";
			$html .= "  <div id='titulo' class='draggable' style=\" text-transform: uppercase;text-align:center;\">".$Titulo."</div>\n";
			$html .= "  <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
			$html .= "  <div id='Contenido' class='d2Content'>\n";
			$html .= "  </div>\n";
			$html .= "</div>\n";
			$html .= "</script>\n";
			$html .= "<div id='Contenedor2' class='d2Container' style=\"display:none;z-index:4\">\n";
			$html .= "  <div id='titulo2' class='draggable' style=\" text-transform: uppercase;text-align:center;\">".$Titulo."</div>\n";
			$html .= "  <div id='cerrar2' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
			$html .= "  <div id='Contenido2' class='d2Content'>\n";
			$html .= "  </div>\n";
			$html .= "</div>\n";
      return $html;
    } 
  /*
		* Funcion donde se crea la forma para el pago de los productos
		* @return string
    */
    
		function FormaOrdenesServicio($Tercero,$action,$datos,$Recibocaja_id,$cajafact_id)
		{
							
			    $html .= ThemeAbrirTabla('CAJA');
	        $html.=   "       <fieldset><legend class=\"field\" >CLIENTE</legend>";
	        $html.="         <table height=\"50\" border=\"1\" width=\"55%\" align=\"center\" cellspacing=\"1\">";
	        $html.= "               <tr><td class=\"modulo_table_title\" width=\"25%\">CLIENTE: </td><td class=\"modulo_list_claro\">".$Tercero[0]['nombre_tercero']."</td></tr>";
	        $html.="               <tr><td class=\"modulo_table_title\"width=\"25%\">IDENTIFICACION: </td><td class=\"modulo_list_claro\">".$Tercero[0]['tipo_id_tercero']." ".$Tercero[0]['tercero_id']."</td></tr>";
	        $html.= "                   </table>";
	        $html.= "              </fieldset>";
	        $html .= "<br>";
	        $html .= " <table width=\"95%\" class=\"modulo_table_list_title\" align=\"center\">";
	        $html.= "  <tr align=\" class=\"modulo_table_list_title\" >\n";
	        $html.= "   <td width=\"25%\">LABORATORIO</td>\n";
	        $html.= "   <td width=\"30%\">MOLECULA</td>\n";
	        $html.= "  <td width=\"15%\">CODIGO</td>\n";
	        $html.= "   <td width=\"55%\">NOMBRE PRODUCTO</td>\n";
	        $html.= "   <td width=\"10%\">CANTIDAD</td>\n";
	        $html.= "   <td width=\"10%\">V.TOTAL</td>\n";
	        $html.= "</tr>\n";
			    $est = "modulo_list_claro"; $back = "#DDDDDD";
	        $totalCosto=0;
			foreach($datos as $key => $dtl)
			{
	            $html.= " <tr class=\"modulo_list_claro\">\n";
	            $html.= "    <td   align=\"center\">".$dtl['laboratorio']."</td>\n";
	            $html.= "  <td   align=\"center\">".$dtl['molecula']."</td>\n";
	            $html.= "    <td   align=\"center\">".$dtl['codigo_producto']."</td>\n";
	            $html.= "    <td  align=\"left\">".$dtl['descripcion']." ".$dtl['unidad']." x ".$dtl['presen']."</td>\n";
	            $html.= "    <td align=\"left\">".$dtl['cantidad']."</td>\n";
	            $html.= "     <td align=\"left\">".$dtl['total_costo']."</td>\n";
	            $html.= "  </tr>\n";
				$totalCosto=$totalCosto + $dtl['total_costo'];
			}
			$html.= "	</table><br>\n";
			$html.=   "       <fieldset><legend class=\"field\" >TOTAL PAGAR</legend>";
			$html.="         <table height=\"10\" border=\"1\" width=\"35%\" align=\"right\">";
			$html.= "               <tr><td class=\"modulo_table_title\" width=\"25%\">TOTAL : </td><td class=\"modulo_list_claro\">$".$totalCosto."</td></tr>";
			$html.= "                   </table>";
			$html.= "              </fieldset>";
			$html.= "  <br><table border=\"0\" width=\"90%\" align=\"center\">";
			$html.= "  <tr><td><fieldset><legend class=\"field\">REGISTRAR PAGOS</legend>";
			$html .= "		<form name=\"formita\" id=\"formita\" action=\"".$action['buscador']."\" method=\"post\"     >";
			$html.= "   <table border=\"0\" width=\"55%\" align=\"center\" class=\"modulo_table_list\" >";
			$html.= "       <tr >";
			$html.= "<td class=modulo_table_title width=\"43%\" >VALOR PAGAR</td><td class=modulo_list_oscuro >";					
			$html.= " <input type=\"text\" name=\"efectivo\"class=\"input-text\" value=\"".$totalCosto."\"  size=\"15\" READONLY></td> ";
			$html.= "      </tr>";
			$html.= "  <tr class=modulo_list_claro >";
			$html.= " <td class=modulo_table_title>DESCUENTOS ($)</td> ";
			$html .=" <td><input type=\"text\" name=\"descuento\" class=\"input-text\" value=\"\"  size=\"15\"></td>";
			$html.= "       </tr>";
			$html.= "   </table> ";
			$html.= "   <table border=\"0\" width=\"55%\" align=\"center\" >";
			$html.= "  <tr >";
			$html .= "      <td align=\"center\">\n";
			$html .= "         <a href=\"#\" onclick=\"xajax_TrasInformaPago(document.formita.efectivo.value,document.formita.descuento.value,'".$Recibocaja_id."','".$cajafact_id."')\" class=\"label_error\">[ REALIZAR PAGO ]</a>\n";
			$html .= "      </td>\n";
			$html.= "       </tr>";
			$html.= "   </table> <BR>";
			$html .= "<table  width=\"75%\"   align=\"center\">\n";
			$html .= "  <tr >\n";
			$html .= "      <td colspan=\"25\"><div id=\"TiposPagos\"></div></td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .="</form>";
			$html.= "         </tr>";
		  $html.= " </fieldset></td></tr></table>";
			$html.= ThemeCerrarTabla();
			return $html;
    }
    /*
		* Funcion donde se crea la forma para el pago en cheque
		* @return string
    */   
   	function FormaCheques($action,$datos)
		{
		
		  $html .="<div align=\"center\" class=\"label_error\" id='error'></div> ";
			$html .="<script >\n";
			$html .= "  function ValidarDtos(frms)\n";
			$html .= "  {\n";
			$html .= " if(frms.entconfirma.selectedIndex==0)\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE SELECCIONAR LA ENTIDAD QUE CONFIRMA';\n";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    if(frms.funconfirma.value==\"\")\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR EL FUNCIONARIO QUE CONFIRMA';\n";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    if(frms.numconfirma.value==\"\")\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR EL NUMERO DE CONFIRMACION ';\n";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    if(frms.fechaconfirma.value==\"\")\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR LA FECHA DE CONFIRMACION ';\n";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    frms.submit();\n";
			$html .= "    }\n";   
			$html .="</script>\n";
			$html.= ThemeAbrirTabla('CONFIRMAR CHEQUE');
      $html.="<form name=\"forma2\" action=\"".$action['confi']."\" method=\"post\">";
      $html.="<table border=\"0\" align=\"center\" class=\"modulo_table_list_title\ width=\"40%\">";
      $html.=" <tr>";
      $html.="<td class=\"modulo_list_oscuro\"><b>ENTIDAD QUE CONFIRMA :</b></td>";
      $html.="<td class=\"modulo_list_oscuro\" ><select name='entconfirma' class='select'>";
      $html .= "             	<option value = '-1'>--  SELECCIONE --</option>\n";
      $csk = "";
				foreach($datos as $indice => $valor)
				{
					if($valor['entidad_confirma']==$request['entidad_confirma'])
					$sel = "selected";
					else   $sel = "";
					$html .= "  <option value=\"".$valor['entidad_confirma']."\" ".$sel.">".$valor['descripcion']."</option>\n";
				}
		  $html .= "                </select>\n";
			$html .= "						  </td>\n";
			$html.=" </tr>";
			$html.="<tr>";
			$html.="<td class=\"modulo_list_oscuro\" ><b>FUNCIONARIO CONFIRMA :</b></td>";
			$html.="<td class=\"modulo_list_oscuro\" ><input name=\"funconfirma\" class=\"input-text\" type=\"text\" maxlength=\"40\" value=".$_REQUEST['funconfirma']."></td>";
			$html.=" </tr>";
			$html.=" <tr>";
			$html.=" <td class=\"modulo_list_oscuro\" ><b>NUMERO DE CONFIRMACION:</b></td>";
			$html.="  <td class=\"modulo_list_oscuro\"><input name=\"numconfirma\" class=\"input-text\" type=\"text\" size='20' maxlength=\"15\" value=".$_REQUEST['numconfirma']."></td>";
			$html.=" </tr>";
			$html.=" <tr>";
			$html.="  <td class=\"modulo_list_oscuro\" ><b>FECHA :</b></td>";
			$html.="<td class=\"modulo_list_oscuro\" align=\"left\"><input type=\"text\" class=\"input-text\" name=\"fechaconfirma\" size='11' maxlength=\"10\" value=\"".$_REQUEST['fechaconfirma']."\">".ReturnOpenCalendario('forma2','fechaconfirma','-')."</td>";
			$html.=" </tr>";
			$html.=" </table>";
			$html.="<br><table border=\"0\" align=\"center\" width=\"40%\">";
			$html.=" <tr>";
			$html.=" <td  align=\"center\">";
			$html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"Confirmar\"  value=\"CONFIRMAR\" onclick=\"ValidarDtos(document.forma2);\" >\n";
      $html .= " </td>\n";
			$html.=" </tr>";
			$html.=" </table>";
			$html .= "<table align=\"center\" width=\"50%\">\n";
			$html .= "  <tr>\n";
			$html .= "    <td align=\"center\">\n";
			$html .= "      <a href=\"".$action['volver']." \" class=\"label_error\">\n";
			$html .= "        Volver\n";
			$html .= "      </a>\n";
			$html .= "    </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html.= ThemeCerrarTabla();
			return $html;
		}
  /*
		* Funcion donde se crea la forma para continuar con el pago en cheque
		* @return string
    */   
		function FormaChequesInformacion($action,$datos,$Tercero,$Cheque)
		{
		    $html.= ThemeAbrirTabla('CONFIRMAR CHEQUE');
			 $html .="<div align=\"center\" class=\"label_error\" id='error'></div> ";
			$html .="<script >\n";
			$html .= "  function ValidarDto(frms)\n";
			$html .= "  {\n";
			$html .= " if(frms.banco.selectedIndex==0)\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE SELECCIONAR LA ENTIDAD';\n";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    if(frms.ctac.value==\"\")\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR lA CUENTA CORRIENTE';\n";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    if(frms.girador.value==\"\")\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR EL NOMBRE DEL GIRADOR ';\n";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    if(frms.fechacheque.value==\"\")\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR LA FECHA DEL CHEQUE ';\n";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    if(frms.nocheque.value==\"\")\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR EL NUMERO DEL CHEQUE ';\n";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    if(frms.fech.value==\"\")\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR LA FECHA DE TRANSACCION ';\n";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    if(frms.fechacheque.value==\"\")\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR LA FECHA DEL CHEQUE ';\n";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    frms.submit();\n";
			$html .= "    }\n";   
			$html .="</script>\n";
			$html .= "<form name=\"forma2\"   action=\"".$action['guardar']."\" method=\"post\">";
			$html.=   "       <fieldset><legend class=\"field\" >CLIENTE</legend>";
			$html.="         <table height=\"50\" border=\"1\" width=\"55%\" align=\"center\"  cellspacing=\"1\">";
			$html.= "               <tr><td class=\"modulo_table_title\" width=\"25%\">CLIENTE: </td><td class=\"modulo_list_claro\">".$Tercero[0]['nombre_tercero']."</td></tr>";
			$html.="               <tr><td class=\"modulo_table_title\"width=\"25%\">IDENTIFICACION: </td><td class=\"modulo_list_claro\">".$Tercero[0]['tipo_id_tercero']." ".$Tercero[0]['tercero_id']."</td></tr>";
			$html.= "                   </table>";
			$html.= "              </fieldset>";
  		$html.= '<BR>';
			$html.= '<table align="center" width="65%" border="0" class=\"modulo_table_list_title\ >';
			$html.= '<tr>';
			$html.=" <td class=\"modulo_list_claro\" ><b>NUMERO DE CHEQUE:</b></td>";
			$html.= '<td  ><input name="nocheque" type="text" class="input-text"  maxlength=10 value='.$_REQUEST['nocheque'].'></td>';
			$html.= '<td   class=\"modulo_list_claro\" width="5%">&nbsp;</td>';
			$html.=" <td class=\"modulo_list_claro\" ><b>BANCO:</b></td>";
			$html.="<td   ><select name='banco' class='select'>";
			$html .= "             	<option value = '-1'>--  SELECCIONE --</option>\n";
			$csk = "";
				foreach($datos as $indice => $valor)
				{
					if($valor['entidad_confirma']==$request['entidad_confirma'])
					$sel = "selected";
					else   $sel = "";
					$html .= "  <option value=\"".$valor['entidad_confirma']."\" ".$sel.">".$valor['descripcion']."</option>\n";
				}
		  $html .= "                </select>\n";
			$html .= "						  </td>\n";
			$html.= '</tr>';
			$html.= '<tr>';
			$html.=" <td class=\"modulo_list_claro\" ><b>No CUENTA CORRIENTE:</b></td>";
			$html.= '<td class=\"modulo_list_oscuro\" ><input name="ctac" type="text" id="ctac" class="input-text" maxlength=40  value='.$_REQUEST['ctac'].'></td>';
			$html.= '<td class=\"modulo_list_oscuro\" >&nbsp;</td>';
			$html.=" <td class=\"modulo_list_claro\" ><b>GIRADOR:</b></td>";
			$html.= '<td class=\"modulo_list_oscuro\" ><input name="girador" type="text" class="input-text" maxlength=30  value='.$_REQUEST['girador'].'></td>';
			$html.= '</tr>';
			$html.= '<tr>';
			$html.=" <td class=\"modulo_list_claro\" ><b> FECHA CHEQUE:</b></td>";
		  $html.="<td class=\"modulo_list_claro\" align=\"left\"><input type=\"text\" class=\"input-text\"  name=\"fechacheque\"  id=\"fechacheque\"   size='11' maxlength=\"10\" value=\"".$_REQUEST['fechacheque']."\">".ReturnOpenCalendario('forma2','fechacheque','-')."</td>";
			$html.= '<td class=\"modulo_list_oscuro\" >&nbsp;</td>';
			$html.=" <td class=\"modulo_list_claro\" ><b>FECHA DE TRANSACCION:</b></td>";
			$html.="<td class=\"modulo_list_claro\" align=\"left\"><input type=\"text\" class=\"input-text\"  name=\"fech\"  id=\"fech\"   size='11' maxlength=\"10\" value=\"".$_REQUEST['fech']."\">".ReturnOpenCalendario('forma2','fech','-')."</td>";
			$html.=  '</tr>';
			$html.=  '<tr>';
			$html.=" <td class=\"modulo_list_claro\" ><b>TOTAL:</b></td>";
			$html.=  '<td class=\"modulo_list_oscuro\" ><input name="totalc" type="text" class="input-text"  value='.$Cheque.' READONLY></td>';
			$html.=  '<td>&nbsp;</td>';
			$html.=  '<td>&nbsp;</td>';
			$html.=  '<td>&nbsp;</td>';
			$html.=  '</tr>';
			$html.=  '</table>';
			$html.=  "<br><table align=\"center\" >";
			$html.=  "<tr>";
			$html.=" <td  align=\"center\">";
			$html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"Confirmar\"  value=\"GUARDAR\" onclick=\"ValidarDto(document.forma2);\" >\n";
      $html .= " </td>\n";
			$html.=  '<td>&nbsp;</td>';
			$html.=  "<td >";
			$html.= "</tr>";
			$html.=  "</table>";
			$html .= "<table align=\"center\" width=\"50%\">\n";
			$html .= "  <tr>\n";
			$html .= "    <td align=\"center\">\n";
			$html .= "      <a href=\"".$action['volver']." \" class=\"label_error\">\n";
			$html .= "        Volver\n";
			$html .= "      </a>\n";
			$html .= "    </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html.= ThemeCerrarTabla();
      return $html;
		}
  /*
		* Funcion donde se crea la forma para el pago en tarjeta debito
		* @return string
    */   
		function FormaTarjetaDebito($action,$Tercero,$datos,$tarjeta)
	  {
      $html.= ThemeAbrirTabla('PAGOS CON TARJETAS DEBITOS');			
			$html .="<div align=\"center\" class=\"label_error\" id='error'></div> ";
			$html .="<script >\n";
			$html .= "  function ValidarDato(frms)\n";
			$html .= "  {\n";
			$html .= " if(frms.tarjeta.selectedIndex==0)\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE SELECCIONAR EL TIPO DE TARJETA';\n";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    if(frms.numtarjeta.value==\"\")\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR EL NUMERO DE TARJETA';\n";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    if(frms.noautorizad.value==\"\")\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR EL NUMERO DE AUTORIZACION ';\n";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    frms.submit();\n";
			$html .= "    }\n";   
			$html .="</script>\n";
		  $html .= "<form name=\"forma2\"   action=\"".$action['guardar']."\" method=\"post\">";
      $html.=   "       <fieldset><legend class=\"field\" >CLIENTE</legend>";
			$html.="         <table height=\"50\" border=\"1\" width=\"55%\" align=\"center\"  cellspacing=\"1\">";
			$html.= "               <tr><td class=\"modulo_table_title\" width=\"25%\">CLIENTE: </td><td class=\"modulo_list_claro\">".$Tercero[0]['nombre_tercero']."</td></tr>";
			$html.="               <tr><td class=\"modulo_table_title\"width=\"25%\">IDENTIFICACION: </td><td class=\"modulo_list_claro\">".$Tercero[0]['tipo_id_tercero']." ".$Tercero[0]['tercero_id']."</td></tr>";
			$html.= "                   </table>";
			$html.= "              </fieldset>";
			$html.= '<BR>';
			$html.= '<table align="center" width="65%" border="0" class=\"modulo_table_list_title\ >';
			$html.= '<tr>';
			$html.=" <td class=\"modulo_list_claro\" ><b>TARJETA:</b></td>";
      $html.="<td   ><select name='tarjeta' class='select'>";
			$html .= "             	<option value = '-1'>--  SELECCIONE --</option>\n";
			$csk = "";
				foreach($datos as $indice => $valor)
				{
					if($valor['tarjeta']==$request['tarjeta'])
					$sel = "selected";
					else   $sel = "";
					$html .= "  <option value=\"".$valor['tarjeta']."\" ".$sel.">".$valor['descripcion']."</option>\n";
				}
		  $html .= "                </select>\n";
			$html .= "						  </td>\n";
      $html .= '<td>&nbsp;</td>';
      $html.=" <td class=\"modulo_list_claro\" ><b>NO. TARJETA:</b></td>";
      $html .= '<td><input name="numtarjeta" type="text" class="input-text" maxlength=20  value='.$_REQUEST['numtarjeta'].'></td>';
			$html .= '</tr>';
			$html .= '<tr>';
			$html.=" <td class=\"modulo_list_claro\" ><b>NO. DE AUTORIZACION:</b></td>";
			$html.='<td><input name="noautorizad" type="text" class="input-text" maxlength=15 value='.$_REQUEST['noautorizad'].'></td>';
      $html.='<td>&nbsp;</td>';
      $html.=" <td class=\"modulo_list_claro\" ><b>TOTAL:</b></td>";
      $html.='<td><input name="totald" type="text" id="totald" class="input-text" value='.$tarjeta.' READONLY></td>';
      $html.='</tr>';
      $html.='</table>';
			$html.=  "<br><table align=\"center\" >";
			$html.=  "<tr>";
			$html.=" <td  align=\"center\">";
			$html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"Confirmar\"  value=\"GUARDAR\" onclick=\"ValidarDato(document.forma2);\" >\n";
      $html .= " </td>\n";
			$html.=  '<td>&nbsp;</td>';
			$html.=  "<td >";
			$html.= "</tr>";
			$html.=  "</table>";
      $html .= "<table align=\"center\" width=\"50%\">\n";
			$html .= "  <tr>\n";
			$html .= "    <td align=\"center\">\n";
			$html .= "      <a href=\"".$action['volver']." \" class=\"label_error\">\n";
			$html .= "        Volver\n";
			$html .= "      </a>\n";
			$html .= "    </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
      $html.= ThemeCerrarTabla();
	    return $html;
	}
    /*
		* Funcion donde se crea la forma para el pago con tarjeta credito
		* @return string
    */   
	
    function  FormaTarjetaCredito($action,$Tercero,$datos,$tarjeta)
    {
		
      $html.=ThemeAbrirTabla('PAGOS CON TARJETAS DE CREDITOS');
      $html .="<div align=\"center\" class=\"label_error\" id='error'></div> ";
			$html .="<script >\n";
			$html .= "  function ValidarDato(frms)\n";
			$html .= "  {\n";
			$html .= " if(frms.tarjeta.selectedIndex==0)\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE SELECCIONAR EL TIPO DE TARJETA';\n";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    if(frms.numtarjeta.value==\"\")\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR EL NUMERO DE TARJETA';\n";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    if(frms.noautorizad.value==\"\")\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR EL NUMERO DE AUTORIZACION ';\n";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    if(frms.socio.value==\"\")\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR EL SOCIO';\n";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    if(frms.fechaexp.value==\"\")\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR LA FECHA DE EXPIRACION ';\n";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    if(frms.autoriza.value==\"\")\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR EL NOMBRE DE QUIEN AUTORIZA ';\n";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    if(frms.fecha.value==\"\")\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR LA FECHA  ';\n";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    frms.submit();\n";
			$html .= "    }\n";   
			$html .="</script>\n";
		  $html .= "<form name=\"forma2\"   action=\"".$action['guardar']."\" method=\"post\">";
      $html.=   "       <fieldset><legend class=\"field\" >CLIENTE</legend>";
			$html.="         <table height=\"50\" border=\"1\" width=\"55%\" align=\"center\"  cellspacing=\"1\">";
			$html.= "               <tr><td class=\"modulo_table_title\" width=\"25%\">CLIENTE: </td><td class=\"modulo_list_claro\">".$Tercero[0]['nombre_tercero']."</td></tr>";
			$html.="               <tr><td class=\"modulo_table_title\"width=\"25%\">IDENTIFICACION: </td><td class=\"modulo_list_claro\">".$Tercero[0]['tipo_id_tercero']." ".$Tercero[0]['tercero_id']."</td></tr>";
			$html.= "                   </table>";
			$html.= "              </fieldset>";
			$html.= '<BR>';
			$html.= '<table align="center" width="65%" border="0" class=\"modulo_table_list_title\ >';
			$html.= '<tr>';
			$html.=" <td class=\"modulo_list_claro\" ><b>TARJETA:</b></td>";
      $html.="<td   ><select name='tarjeta' class='select'>";
			$html .= "             	<option value = '-1'>--  SELECCIONE --</option>\n";
			$csk = "";
				foreach($datos as $indice => $valor)
				{
					if($valor['tarjeta']==$request['tarjeta'])
					$sel = "selected";
					else   $sel = "";
					$html .= "  <option value=\"".$valor['tarjeta']."\" ".$sel.">".$valor['descripcion']."</option>\n";
				}
      $html .= "                </select>\n";
      $html .= "						  </td>\n";
      $html .= '<td>&nbsp;</td>';
      $html.=" <td class=\"modulo_list_claro\" ><b>NO. TARJETA:</b></td>";
      $html .= '<td><input name="numtarjeta" type="text" class="input-text" maxlength=20  value='.$_REQUEST['numtarjeta'].'></td>';
      $html .= '</tr>';
      $html .= '<tr>';
      $html.=" <td class=\"modulo_list_claro\" ><b>NO. DE AUTORIZACION:</b></td>";
      $html.='<td><input name="noautorizad" type="text" class="input-text" maxlength=15 value='.$_REQUEST['noautorizad'].'></td>';
      $html.='<td>&nbsp;</td>';
      $html.=" <td class=\"modulo_list_claro\" ><b>SOCIO:</b></td>";
      $html.='<td><input name="socio" type="text" class="input-text" maxlength=15 value='.$_REQUEST['socio'].'></td>';
      $html .= '</tr>';
      $html.=" <td class=\"modulo_list_claro\" ><b>FECHA DE EXPIRACION:</b></td>";
      $html.="<td class=\"modulo_list_claro\" align=\"left\"><input type=\"text\" class=\"input-text\"  name=\"fechaexp\"  id=\"fechaexp\"   size='11' maxlength=\"10\" value=\"".$_REQUEST['fechaexp']."\">".ReturnOpenCalendario('forma2','fechaexp','-')."</td>";
      $html.='<td>&nbsp;</td>';
      $html.=" <td class=\"modulo_list_claro\" ><b>AUTORIZADO POR:</b></td>";
      $html.='<td><input name="autoriza" type="text" class="input-text" value='.$_REQUEST['autoriza'].'></td>';
      $html.='</tr>';
      $html.='<tr>';
      $html.=" <td class=\"modulo_list_claro\" ><b>FECHA DE TRANSACCION:</b></td>";
      $html.="<td class=\"modulo_list_claro\" align=\"left\"><input type=\"text\" class=\"input-text\"  name=\"fecha\"  id=\"fecha\"   size='11' maxlength=\"10\" value=\"".$_REQUEST['fecha']."\">".ReturnOpenCalendario('forma2','fecha','-')."</td>";
      $html.='<td>&nbsp;</td>';
      $html.=" <td class=\"modulo_list_claro\" ><b>TOTAL:</b></td>";
      $html.='<td><input name="totald" type="text" id="totald" class="input-text" value='.$tarjeta.' READONLY></td>';
      $html.='</tr>';
      $html.='</table>';
      $html.=  "<br><table align=\"center\" >";
      $html.=  "<tr>";
      $html.=" <td  align=\"center\">";
      $html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"Confirmar\"  value=\"GUARDAR\" onclick=\"ValidarDato(document.forma2);\" >\n";
      $html .= " </td>\n";
      $html.=  '<td>&nbsp;</td>';
      $html.=  "<td >";
      $html.= "</tr>";
      $html.=  "</table>";
      $html .= "<table align=\"center\" width=\"50%\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <a href=\"".$action['volver']." \" class=\"label_error\">\n";
      $html .= "        Volver\n";
      $html .= "      </a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html.= ThemeCerrarTabla();
	    return $html;          
    }
  /*
		* Funcion donde se crea la forma si se ha generado la venta 
		* @return string
    */   
		function FormaMensaje($prefijoF,$numeroF,$farmacia,$prefijodoc,$numeracion,$recibid,$centro,$tipoid,$id,$bodega)
		{
				$html  = ThemeAbrirTabla("MENSAJE");
				$html .= "<table border=\"1\" width=\"50%\" align=\"center\" >\n";
				$html .= "	<tr>\n";
				$html .= "		<td>\n";
				$html .= "		  <table width=\"100%\" class=\"modulo_table_list\">\n";
				$html .= "		    <tr class=\"normal_10AN\">\n";
				$html .= "		      <td align=\"center\">SE GENERARON LA VENTA   </td>\n";
				$html .= "		    </tr>\n";
				$html .= "		  </table>\n";
				$html .= "		</td>\n";
				$html .= "	</tr>\n";
				$html .= "</table>";
				$html .= "<br>";
				$reporte = new GetReports();
				
				$mostrar = $reporte->GetJavaReport('app','CajaGeneralEmpresa','FacturaConcepto',
																						array("prefijoF"=>$prefijoF,"numeroF"=>$numeroF,"farmacia_id"=>$farmacia,"prefijodoc"=>$prefijodoc,"numeracion"=>$numeracion,"recibid"=>$recibid,"centro"=>$centro,"tipoid"=>$tipoid,"id"=>$id,"bodega"=>$bodega),
																						array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
								
				$funcion = $reporte->GetJavaFunction();
				 $html .= "<table align=\"center\" width=\"50%\">\n";
				$html .= "  <tr>\n";
				$html .= "				<td align=\"center\" >\n";
				$html .= "				".$mostrar."\n";
				$html .= "					<a href=\"javascript:$funcion\" class=\"label_error\"  title=\"REPORTE DEL INGRESO\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0' >\n";
				$html .= "					[ IMPRIMIR FACTURA]</a></center>\n";
				$html .= "			</td>\n";	
				$html .= "  </tr>\n";
				$html .= "</table>\n";
				$html .= ThemeCerrarTabla();
				return $html;
		}
	          
	}
?>