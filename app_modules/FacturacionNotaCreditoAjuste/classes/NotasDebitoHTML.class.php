<?php
  /**
  * $Id: NotasDebitoHTML.class.php,v 1.2 2010/03/12 18:41:36 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.2 $ 
	* 
	* @autor Hugo F  Manrique 
  */
	class NotasDebitoHTML
	{
		function NotasDebitoHTML(){}
		/**********************************************************************************
		* Funcion donde se buscan las Notas De Ajuste temporales 
		* 
		* @return array 
		***********************************************************************************/
		function CrearListadoNotasCredito($Notas,$funcion = "FormaCrearCuerpoNotas",$jsf = "Debito")
		{			
			$path = SessionGetVar("rutaimag");
			$html  = "<br><center><b class=\"label_error\">NO SE ENCONTRO NOTAS CREADAS</b></center><br><br>\n";
			
			if(sizeof($Notas) > 0)
			{
				$html  = "	<table border=\"0\" width=\"80%\" align=\"center\">\n";
				$html .= "		<tr>\n";
				$html .= "			<td>\n";
				$html .= "				<fieldset><legend class=\"field\">NOTAS ABIERTAS:</legend>\n";
				$html .= "					<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$html .= "						<tr class=\"formulacion_table_list\">\n";
				$html .= "							<td style=\"text-align:center\" width=\"15%\">REGISTRO</td>\n";
				$html .= "							<td style=\"text-align:center\" width=\"15%\">FACTURA</td>\n";
				if($jsf != "Debito")
					$html .= "							<td style=\"text-align:center\" width=\"15%\">T. FACTURA</td>\n";
				
				$html .= "							<td style=\"text-align:center\" width=\"15%\">T. CONCEPTOS</td>\n";
				$html .= "							<td style=\"text-align:center\" width=\"%\" colspan= \"3\">OPCIONES</td>\n";
				$html .= "						</tr>\n";
				
				$i = 0;
				$tx = "18%";
				foreach($Notas as $key => $Nota)
				{
					if($i % 2 == 0)
					{
					  $estilo='modulo_list_oscuro'; $background = "#CCCCCC";
					}
					else
					{
					  $estilo='modulo_list_claro';  $background = "#DDDDDD";
					}
					$i++;
									
					$action1 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user',$funcion,array("tmp_id"=>$key,"saldo"=>$Nota['saldo']));
					$action1 = str_replace("app_modules/FacturacionNotaCreditoAjuste/ScriptRemoto/","",$action1);
					
					$opcion1  = "	<a class=\"label_error\" href=\"".$action1."\" title=\"ADICIONAR CONCEPTOS - CRUZAR FACURAS - INGRESAR OBSERVACIÓN\">\n";
					$opcion1 .= "		<img src=\"".$path."/images/pcopiar.png\" border=\"0\"><b>DETALLE NA</b>";
					$opcion1 .= "	</a>\n";					
					
          $anulacion = ($Nota['conceptos'] == $Nota['saldo'] && $jsf != "Debito")? "1":"2";
					$opcion2  = "	<a class=\"label_error\" href=\"javascript:ConfirmarCerrar(new Array('".$key."','".$anulacion."'))\" title=\"CERRAR NOTA DE AJUSTE\">\n";
					$opcion2 .= "		<img src=\"".$path."/images/pguardar.png\" border=\"0\"><b>CERRAR NA</b>";
					$opcion2 .= "	</a>\n";
					
					$opcion3  = "	<a class=\"label_error\" href=\"javascript:ConfirmarEliminarNota$jsf(new Array('$key','D'))\" title=\"ELIMINAR NOTA DEBITO\">\n";
					$opcion3 .= "		<img src=\"".$path."/images/elimina.png\" border=\"0\"><b>ELIMINAR</b>";
					$opcion3 .= "	</a>\n";

					$html .= "						<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
					$html .= "							<td align=\"center\">".$Nota['fecha']."</td>\n";
					$html .= "							<td align=\"center\" class=\"normal_10AN\">".$Nota['prefijo_factura']." ".$Nota['factura_fiscal']."</td>\n";
					
					$mostrar = true;
					if($Nota['saldo'])
					{
						$tx = "13%";
						$html .= "							<td align=\"right\" >$".formatoValor($Nota['saldo'])."</td>\n";
						if($Nota['conceptos'] > $Nota['saldo']) $mostrar = false;
					}
					
					$html .= "							<td align=\"right\" >$".formatoValor($Nota['conceptos'])."</td>\n";
					$html .= "							<td width=\"$tx\">$opcion1</td>\n";
					
					if($Nota['conceptos'] > 0 && $mostrar)
						$html .= "							<td >$opcion2</td>\n";
					else
						$html .= "							<td ></td>\n";
					
					
					$html .= "							<td width=\"$tx\">$opcion3</td>\n";
					$html .= "						</tr>\n";
				}
				$html .= "					</table>\n";
				$html .= "				</fieldset>\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				$html .= "	</table>\n";
			}
			return $html;
		}
		/**********************************************************************************
		* Funcion donde se buscan las Notas De Ajuste temporales 
		* 
		* @return array 
		***********************************************************************************/
		function CrearListadoNotasAjuste($Notas)
		{			
			$html  = "<br><center><b class=\"label_error\">NO SE ENCONTRO NOTAS CREADAS</b></center><br><br>\n";
			
			if(sizeof($Notas) > 0)
			{
				$html  = "	<table border=\"0\" width=\"100%\" align=\"center\">\n";
				$html .= "		<tr>\n";
				$html .= "			<td>\n";
				$html .= "				<fieldset class=\"fieldset\"><legend>NOTAS ABIERTAS:</legend>\n";
				$html .= "					<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$html .= "						<tr class=\"formulacion_table_list\">\n";
				$html .= "							<td style=\"text-align:center\" width=\"9%\">REGISTRO</td>\n";
				$html .= "							<td style=\"text-align:center\" width=\"8%\">FACTURA</td>\n";
				$html .= "							<td style=\"text-align:center\" width=\"8%\">T. FACTURA</td>\n";
				$html .= "							<td style=\"text-align:center\" width=\"8%\">SALDO</td>\n";
				$html .= "							<td style=\"text-align:center\" width=\"8%\">CONCEPTOS</td>\n";
				$html .= "							<td style=\"text-align:center\" width=\"%\">TERCERO</td>\n";
				$html .= "							<td style=\"text-align:center\" width=\"24%\" colspan= \"3\">OPCIONES</td>\n";
				$html .= "						</tr>\n";
				
				$i = 0;
				foreach($Notas as $key => $Nota)
				{
					if($i % 2 == 0)
					{
					  $estilo='modulo_list_oscuro'; $background = "#CCCCCC";
					}
					else
					{
					  $estilo='modulo_list_claro';  $background = "#DDDDDD";
					}
					$i++;
					
					$opcion1  = "	<a class=\"label_error\" href=\"javascript:AdicionarConceptos('$key','".$Nota['saldo']."')\" title=\"ADICIONAR CONCEPTOS - CRUZAR FACURAS - INGRESAR OBSERVACIÓN\">\n";
					$opcion1 .= "		<img src=\"".GetThemePath()."/images/pcopiar.png\" border=\"0\"><b>DETALLE</b>";
					$opcion1 .= "	</a>\n";					
					
					$opcion2  = "	<a class=\"label_error\" href=\"javascript:ConfirmarCerrar('$key','".$Nota['prefijo_factura']." ".$Nota['factura_fiscal']."')\" title=\"CERRAR NOTA DE AJUSTE\">\n";
					$opcion2 .= "		<img src=\"".GetThemePath()."/images/pguardar.png\" border=\"0\"><b>CERRAR</b>";
					$opcion2 .= "	</a>\n";
					
					$opcion3  = "	<a class=\"label_error\" href=\"javascript:ConfirmarEliminarNota('$key','".$Nota['prefijo_factura']." ".$Nota['factura_fiscal']."')\" title=\"ELIMINAR NOTA DEBITO\">\n";
					$opcion3 .= "		<img src=\"".GetThemePath()."/images/elimina.png\" border=\"0\"><b>ELIM.</b>";
					$opcion3 .= "	</a>\n";

					$html .= "						<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
					$html .= "							<td align=\"center\">".$Nota['fecha']."</td>\n";
					$html .= "							<td align=\"center\" class=\"normal_10AN\">".$Nota['prefijo_factura']." ".$Nota['factura_fiscal']."</td>\n";
					$html .= "							<td align=\"right\" >$".FormatoValor($Nota['total_factura'])."</td>\n";
					$html .= "							<td align=\"right\" >$".FormatoValor($Nota['saldo'])."</td>\n";
					$html .= "							<td align=\"right\" >$".FormatoValor($Nota['creditos'])."</td>\n";
					$html .= "							<td align=\"left\" >".$Nota['nombre_tercero']."</td>\n";
					$html .= "							<td width=\"8%\">$opcion1</td>\n";
					$html .= "							<td >\n";
					if($Nota['creditos'] > 0 ) $html .= "$opcion2";
					
					$html .= "							</td>\n";
					$html .= "							<td width=\"8%\">$opcion3</td>\n";
					$html .= "						</tr>\n";
				}
				$html .= "					</table>\n";
				$html .= "				</fieldset>\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				$html .= "	</table>\n";
			}
			return $html;
		}
		/***********************************************************************
		*
		************************************************************************/
		function CrearListaConceptos($Conceptos)
		{
			$path = SessionGetVar("rutaimag");
			if(sizeof($Conceptos) > 0 )
			{	
				$html .= "	<table width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";		
				$html .= "		<tr class=\"formulacion_table_list\">\n";
				$html .= "			<td align=\"center\" width=\"2%\"><b>X</b></td>\n";
				$html .= "			<td align=\"center\" width=\"45%\"><b>CONCEPTO</b></td>\n";
				$html .= "			<td align=\"center\" width=\"%\"><b>DEPARTAMENTO / TERCERO</b></td>\n";
				$html .= "			<td align=\"center\" width=\"8%\"><b>VALOR</b></td>\n";
				$html .= "		</tr>\n";
				
				$suma = 0;
				foreach($Conceptos as $key => $Concep)
				{					
					$opcion  = "	<a href=\"javascript:EliminarConceptos(new Array('$key','".$Concep['concepto_id']."'))\" >\n";
					$opcion .= "		<img src=\"".$path."/images/checkS.gif\" title=\"ELIMINAR CONCEPTO\" border=\"0\">";
					$opcion .= "	</a>\n";
					
					$html .= "		<tr class=\"modulo_list_claro\">\n";
					$html .= "			<td class=\"modulo_list_claro\" align=\"center\">$opcion</td>\n";
					$html .= "			<td class=\"normal_10AN\" ><b>".$Concep['descripcion']."</b></td>\n";
					$html .= "			<td class=\"normal_10AN\" ><b>".$Concep['departamento']."</b></td>\n";
					$html .= "			<td class=\"modulo_list_claro\" align=\"right\"><b>$".formatoValor($Concep['valor'])."</b></td>\n";
					$html .= "		</tr>\n";
					
					$suma += $Concep['valor']; 
				}
				
				$html .= "		<tr class=\"formulacion_table_list\">\n";
				$html .= "			<td colspan=\"3\" style=\"text-indent:14pt;text-align:left\">TOTAL CONCEPTOS</td>\n";
				$html .= "			<td class=\"modulo_table_list_title\" style=\"text-align:right\">$".formatoValor($suma)."</td>\n";
				$html .= "		</tr>\n";
				$html .= "	</table><br>\n";
				$html .= "	<input type=\"hidden\" id=\"totalConceptos\" value =\"".$suma."\">\n";
			}
			return $html;
		}
		/***********************************************************************
		*
		************************************************************************/
		function CrearListaConceptosExternos($Conceptos)
		{
			$html  = "<script>\n";
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
			if(sizeof($Conceptos) > 0 )
			{	
				$html .= "	<table width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";		
				$html .= "		<tr class=\"formulacion_table_list\">\n";
				$html .= "			<td align=\"center\" width=\"2%\"><b>X</b></td>\n";
				$html .= "			<td align=\"center\" width=\"45%\"><b>CONCEPTO</b></td>\n";
				$html .= "			<td align=\"center\" width=\"%\"><b>DEPARTAMENTO / TERCERO</b></td>\n";
				$html .= "			<td align=\"center\" width=\"8%\"><b>VALOR</b></td>\n";
				$html .= "		</tr>\n";
				
				$suma = 0;
				foreach($Conceptos as $key => $Concep)
				{					
					$opcion  = "	<a href=\"javascript:EliminarConceptos(new Array('$key','".$Concep['concepto_id']."','".$Concep['tmp_nota_ajuste_id']."'))\" >\n";
					$opcion .= "		<img src=\"".GetThemePath()."/images/checkS.gif\" title=\"ELIMINAR CONCEPTO\" border=\"0\">";
					$opcion .= "	</a>\n";
					
					$html .= "		<tr class=\"modulo_list_claro\">\n";
					$html .= "			<td class=\"modulo_list_claro\" align=\"center\">$opcion</td>\n";
					$html .= "			<td class=\"normal_10AN\" ><b>".$Concep['descripcion']."</b></td>\n";
					$html .= "			<td class=\"normal_10AN\" ><b>".$Concep['departamento']."</b></td>\n";
					$html .= "			<td class=\"modulo_list_claro\" align=\"right\"><b>$".formatoValor($Concep['valor'])."</b></td>\n";
					$html .= "		</tr>\n";
					
					$suma += $Concep['valor']; 
				}
				
				$html .= "		<tr class=\"formulacion_table_list\">\n";
				$html .= "			<td colspan=\"3\" style=\"text-indent:14pt;text-align:left\">TOTAL CONCEPTOS</td>\n";
				$html .= "			<td class=\"modulo_table_list_title\" style=\"text-align:right\">$".formatoValor($suma)."</td>\n";
				$html .= "		</tr>\n";
				$html .= "	</table><br>\n";
				$html .= "	<input type=\"hidden\" id=\"totalConceptos\" value =\"".$suma."\">\n";
			}
			return $html;
		}
		/***********************************************************************
		*
		************************************************************************/
		function CrearCapaVentana()
		{
			$html .= "<script>\n";
			$html .= "	var contenedor = 'Contenedor';\n";
			$html .= "	var titulo = 'titulo';\n";
			$html .= "	var hiZ = 2;\n";
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
			$html .= "	function Iniciar()\n";
			$html .= "	{\n";
			$html .= "		contenedor = 'Contenedor';\n";
			$html .= "		titulo = 'titulo';\n";
			$html .= "		ele = xGetElementById(contenedor);\n";
			$html .= "	  xResizeTo(ele,350, 'auto');\n";
			$html .= "	  xMoveTo(ele, xClientWidth()/3, xScrollTop()+100);\n";
			$html .= "		ele = xGetElementById(titulo);\n";
			$html .= "	  xResizeTo(ele,330, 20);\n";
			$html .= "		xMoveTo(ele, 0, 0);\n";
			$html .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$html .= "		ele = xGetElementById('cerrar');\n";
			$html .= "	  xResizeTo(ele,20, 20);\n";
			$html .= "		xMoveTo(ele, 330, 0);\n";
			$html .= "	}\n";			
      
      $html .= "	function IniciarI()\n";
			$html .= "	{\n";
			$html .= "		contenedor = 'ContenedorI';\n";
			$html .= "		titulo = 'tituloI';\n";
			$html .= "		ele = xGetElementById(contenedor);\n";
			$html .= "	  xResizeTo(ele,350, 'auto');\n";
			$html .= "	  xMoveTo(ele, xClientWidth()/3, xScrollTop()+100);\n";
			$html .= "		ele = xGetElementById(titulo);\n";
			$html .= "	  xResizeTo(ele,330, 20);\n";
			$html .= "		xMoveTo(ele, 0, 0);\n";
			$html .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$html .= "		ele = xGetElementById('cerrarI');\n";
			$html .= "	  xResizeTo(ele,20, 20);\n";
			$html .= "		xMoveTo(ele, 330, 0);\n";
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
			$html .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:4\">\n";
			$html .= "	<div id='titulo' class='draggable' style=\"	text-transform: uppercase;text-align:center\">CONFIRMACIÓN</div>\n";
			$html .= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('Contenedor')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
			$html .= "	<div id='Contenido' class='d2Content'>\n";
			$html .= "		<form name=\"oculta\" action=\"\" method=\"post\">\n";
			$html .= "			<table width=\"100%\" align=\"center\">\n";
			$html .= "				<tr>\n";
			$html .= "					<td align=\"center\" class=\"label\" id=\"confirmacion\">\n";
			$html .= "					</td>\n";
			$html .= "				</tr>\n";			
			$html .= "				<tr>\n";
			$html .= "					<td colspan=\"3\" align=\"center\">\n";
			$html .= "						<input type=\"submit\" class=\"input-submit\"name=\"aceptar\" value=\"Aceptar\">&nbsp;&nbsp;&nbsp;&nbsp;\n";
			$html .= "						<input type=\"button\" class=\"input-submit\"name=\"cancelar\" value=\"Cancelar\" onclick=\"OcultarSpan('Contenedor')\">\n";
			$html .= "					</td>\n";
			$html .= "				</tr>\n";
			$html .= "			</table>\n";
			$html .= "		</form>\n";
			$html .= "	</div>\n";
			$html .= "</div>\n";
      
      $html .= "<div id='ContenedorI' class='d2Container' style=\"display:none;z-index:4\">\n";
			$html .= "	<div id='tituloI' class='draggable' style=\"	text-transform: uppercase;text-align:center\">CONFIRMACIÓN</div>\n";
			$html .= "	<div id='cerrarI' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('ContenedorI')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
			$html .= "	<div id='ContenidoI' class='d2Content'>\n";
			$html .= "		<form name=\"ocultaI\" action=\"\" method=\"post\">\n";
			$html .= "			<table width=\"100%\" align=\"center\">\n";
			$html .= "				<tr>\n";
			$html .= "					<td align=\"center\" class=\"label\" id=\"confirmacionI\">\n";
			$html .= "					</td>\n";
			$html .= "				</tr>\n";			
			$html .= "				<tr>\n";
			$html .= "					<td colspan=\"3\" align=\"center\">\n";
			$html .= "						<input type=\"button\" class=\"input-submit\"name=\"activa\" value=\"Dejar Cuenta Activa\" onclick=\"EnviarNota('1')\">&nbsp;&nbsp;&nbsp;&nbsp;\n";
			$html .= "						<input type=\"button\" class=\"input-submit\"name=\"anulada\" value=\"Anular Cuenta\" onclick=\"EnviarNota('5')\">&nbsp;&nbsp;&nbsp;&nbsp;\n";
			$html .= "						<input type=\"button\" class=\"input-submit\"name=\"cancelar\" value=\"Cancelar\" onclick=\"OcultarSpan('ContenedorI')\">\n";
			$html .= "					</td>\n";
			$html .= "				</tr>\n";
			$html .= "			</table>\n";
			$html .= "		</form>\n";
			$html .= "	</div>\n";
			$html .= "</div>\n";
			return $html;
		}
		/***********************************************************************
		*
		************************************************************************/
		function CrearCapaBuscador($Prefijos)
		{
			$html .= "<script>\n";
			$html .= "	function IniciarB()\n";
			$html .= "	{\n";
			$html .= "		contenedor = 'FacturasB';\n";
			$html .= "		titulo = 'titulob';\n";
			$html .= "		ele = xGetElementById('ContenidoB');\n";
			$html .= "	  xResizeTo(ele,550,360);\n";
			$html .= "		ele = xGetElementById(contenedor);\n";
			$html .= "	  xResizeTo(ele,550, 'auto');\n";
			$html .= "	  xMoveTo(ele, xClientWidth()/4, xScrollTop()+10);\n";
			$html .= "		ele = xGetElementById(titulo);\n";
			$html .= "	  xResizeTo(ele,530, 20);\n";
			$html .= "		xMoveTo(ele, 0, 0);\n";
			$html .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$html .= "		ele = xGetElementById('cerrarb');\n";
			$html .= "	  xResizeTo(ele,20, 20);\n";
			$html .= "		xMoveTo(ele, 530, 0);\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			$html .= "<div id='FacturasB' class='d2Container' style=\"display:none\">\n";
			$html .= "	<div id='titulob' class='draggable' style=\"	text-transform: uppercase;text-align:center\">BUSCADOR DE FACTURAS</div>\n";
			$html .= "	<div id='cerrarb' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('FacturasB')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
			$html .= "	<div id='ContenidoB' class='d2Content' style=\"background:#EFEFEF\"><br>\n";
			$html .= "		<form name=\"buscadorfacturas\" action=\"javascript:CrearVariables(document.buscadorfacturas,'1')\" >\n";
			$html .= "			<table width=\"100%\" align=\"center\">\n";
			$html .= "				<tr>\n";
			$html .= "					<td align=\"center\" class=\"label\">\n";
			$html .= "						<table class=\"modulo_table_list\" width=\"70%\">\n";
			$html .= "							<tr class=\"modulo_table_list_title\">\n";
			$html .= "								<td style=\"text-indent:8pt;text-align:left\">BUSCADOR DE FACTURAS:</td>\n";
			$html .= "								<td class=\"modulo_list_claro\">\n";
			$html .= "									<select name=\"prefijo\" class=\"select\">\n";
			
			foreach($Prefijos as $key =>$Filas)
				$html .= "										<option value='".$Filas['prefijo']."' >".$Filas['prefijo']."</option>\n";
			
			$html .= "									</select>\n";
			$html .= "								</td>\n";
			$html .= "								<td class=\"modulo_list_claro\">\n";
			$html .= "									<input type=\"text\" class=\"input-text\" name=\"factura\" size=\"10\" maxlength=\"10\" onkeypress=\"return acceptNum(event)\" value=\"".$this->FacturaFiscal."\">\n";
			$html .= "								</td>\n";
			$html .= "								<td class=\"modulo_list_claro\">\n";
			$html .= "									<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
			$html .= "								</td>\n";
			$html .= "							</tr>\n";
			$html .= "						</table>\n";
			$html .= "					</td>\n";
			$html .= "				</tr>\n";
			$html .= "				<tr>\n";
			$html .= "					<td>\n";
			$html .= "						<div id=\"resultado\">\n";
			$html .= "							<table align=\"center\">\n";
			$html .= "								<tr><td height=\"25\"><a href=\"javascript:OcultarSpan('FacturasB')\" class=\"label_error\">CERRAR</a></td></tr>\n";
			$html .= "							</table>\n";
			$html .= "						</div>\n";
			$html .= "					</td>\n";
			$html .= "				</tr>\n";
			$html .= "			</table>\n";
			$html .= "		</form>\n";
			$html .= "	</div>\n";
			$html .= "</div>\n";
			return $html;
		}
	}
?>