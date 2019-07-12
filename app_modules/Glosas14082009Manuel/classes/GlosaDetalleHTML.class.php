<?php
  /******************************************************************************
  * $Id: GlosaDetalleHTML.class.php,v 1.2 2009/03/19 20:07:27 cahenao Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.2 $ 
	* 
	* @autor Hugo F  Manrique 
  ********************************************************************************/
	class GlosaDetalleHTML
	{
		function GlosaDetalleHTML(){}
		/******************************************************************************
		* 
		* @return array datos de las cuentas
		********************************************************************************/
		function FormaListarCuentas($datos,$action)
		{
			IncludeClass('ClaseHTML');
			IncludeClass('GlosaDetalle','','app','Glosas');
			$gld = new GlosaDetalle();
			$cuentas = $gld->ObtenerInformacionDetalleCuentas($datos,25);

			$html  = "	<script>\n";
			$html .= "		function mOvr(src,clrOver)\n";
			$html .= "		{\n";
			$html .= "			src.style.background = clrOver;\n";
			$html .= "		}\n";
			$html .= "		function mOut(src,clrIn)\n";
			$html .= "		{\n";
			$html .= "			src.style.background = clrIn;\n";
			$html .= "		}\n";
			$html .= "	</script>\n";
			$html .= "	<table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td class=\"modulo_table_list_title\" width=\"10%\">Nº CUENTA</td>\n";
			$html .= "			<td class=\"modulo_table_list_title\" width=\"10%\">INGRESO</td>\n";
			$html .= "			<td class=\"modulo_table_list_title\" width=\"35%\">PACIENTE</td>\n";
			$html .= "			<td class=\"modulo_table_list_title\" width=\"35%\">PLAN</td>\n";
			$html .= "			<td class=\"modulo_table_list_title\" width=\"10%\">OPCIONES</td>\n";
			$html .= "		</tr>\n";
			
			$background = "#DDDDDD";
			foreach($cuentas as $key => $detalle)
			{
				if($background == "#DDDDDD")
				{
				  $estilo='modulo_list_oscuro'; $background = "#CCCCCC";
				}
				else
				{
				  $estilo='modulo_list_claro'; $background = "#DDDDDD";
				}
				
				$datos['numerodecuenta'] = $key;
				
				$html .= "		<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
				$html .= "			<td align=\"center\">".$key."</td>\n";
				$html .= "			<td align=\"center\">".$detalle['ingreso']."</td>\n";
				$html .= "			<td >".$detalle['identificacion']." ".$detalle['paciente']."</td>\n";
				$html .= "			<td >".$detalle['plan_descripcion']."</td>\n";
				$html .= "			<td >\n";
				$html .= "				<a href=\"".$action['glosar'].URLRequest(array("datos_glosa"=>$datos))."\" title=\"GLOSAR CUENTA\" class=\"label_error\">\n";
				$html .= "					<img src=\"".GetThemePath()."/images/editar.png\" border=\"0\">GLOSAR\n";
				$html .= "				</a>\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
			}
			$html .= "	</table>\n";
			$html .= "	<br>\n";
			$html .= "		".ClaseHTML::ObtenerPaginado($gld->conteo,$gld->paginaActual,$action['paginador']);
			$html .= "	<br>\n";		
			$html .= "	<table width=\"90%\" align=\"center\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td align=\"center\">\n";
			$html .= "				<form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "					<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$html .= "				</form>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			
			return $html;
		}
		/******************************************************************************
		* 
		* @return array datos de las cuentas
		********************************************************************************/
		function FormaDetalleCuenta($datos,$action,$responder)
		{
			IncludeClass('ClaseUtil');
			IncludeClass('GlosaDetalle','','app','Glosas');
			IncludeClass('Glosas','','app','Glosas');
			
			$gl = new Glosas();
			$gld = new GlosaDetalle();
			$cuenta = $gld->ObtenerInformacionDetalleCuentas($datos);
			$motivos = $gld->ObtenerMotivosGlosas();
			$cargos = $gld->ObtenerCargosCuentas($datos);
			$insumos = $gld->ObtenerInsumosCuenta($datos);
//print_r($action);
			$action['glosar'] .= "&detalle_cuenta=".$cuenta[$datos['numerodecuenta']]['glosa_detalle_cuenta_id'];
			
			$estilo = "class=\"modulo_table_list_title\" style=\"text-align:left;text-indent: 6pt\" ";
			$html .= ClaseUtil::IsNumeric();
			$html .= ClaseUtil::CrearCapaVentana();
			$html .= "<script>\n";
			$html .= "	var hiZ = 2;\n";
			$html .= "	function acceptNum(evt)\n";
			$html .= "	{\n";
			$html .= "		var nav4 = window.Event ? true : false;\n";
			$html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "		return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$html .= "	}\n";
			$html .= "	function Volver(frm)\n";
			$html .= "	{\n";
			$html .= "		frm.action = \"".$action['volver']."\";\n";
			$html .= "		frm.submit();\n";
			$html .= "	}\n";
			$html .= "	function CambiarEstadoCheck(valor,identificador)\n";
			$html .= "	{\n";
			$html .= "		document.getElementById('inp_'+identificador).disabled = !valor;\n";
			$html .= "	}\n";			
			
			$html .= "	function CambiarValor(identificador)\n";
			$html .= "	{\n";
			$html .= "		try\n";
			$html .= "		{\n";
			$html .= "			if(!document.getElementById('chk_'+identificador).disabled)\n";
			$html .= "			{\n";
			$html .= "				valor = document.getElementById('vlr_'+identificador).value;\n";
			$html .= "				document.getElementById('inp_'+identificador).value = valor;\n";
			$html .= "				document.getElementById('chk_'+identificador).checked = true;\n";
			$html .= "				CambiarEstadoCheck(true,identificador)\n";	
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "		catch(error){}\n";
			$html .= "	}\n";	
			$html .= "	var codigo;\n";	
			
			$html .= "	function IngresarObservacion(frm,seccion)\n";
			$html .= "	{\n";
			$html .= "		valor = frm.observacion.value;\n";
			$html .= "		OcultarSpanObservacion('ContenedorObservacion');\n";
			$html .= "		if(valor != '')\n";
			$html .= "			xajax_IngresarObservacion(codigo,valor);\n";
			$html .= "	}\n";
			$html .= "	function ObtenerObservacion(titulo,identificador)\n";
			$html .= "	{\n";
			$html .= "		codigo = identificador;\n";
			$html .= "		try\n";
			$html .= "		{\n";
			$html .= "			if(!document.getElementById('chk_'+identificador).disabled &&";
			$html .= "					document.getElementById('chk_'+identificador).checked)\n";
			$html .= "			{\n";
			$html .= "				IniciarObservacion(titulo);\n";
			$html .= "				MostrarSpanObservacion('ContenedorObservacion');\n";
			$html .= "				xajax_ObtenerObservacion(identificador);\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "		catch(error){}\n";
			$html .= "	}\n";
			
			$html .= "	function EvaluarDatos(frm)\n";
			$html .= "	{\n";
			$html .= "		flag = false;\n";
			$html .= "		nombre = '';\n";
			$html .= "		for(i=0; i<frm.length; i++)\n";
			$html .= "		{\n";
			$html .= "			switch(frm[i].type)\n";
			$html .= "			{\n";
			$html .= "				case 'checkbox': \n";
			$html .= "					try \n";
			$html .= "					{ \n";
			$html .= "						if(frm[i].checked && frm[i].disabled == false) \n";
			$html .= "						{\n";
			$html .= "							flag = true;\n";
			$html .= "							titulo = 'INFORMACION';\n";
			$html .= "							nombre = frm[i].id; \n";
			$html .= "							valor = document.getElementById(nombre.replace('chk','inp')).value; \n";
			$html .= "							cubierto = document.getElementById(nombre.replace('chk','vlr')).value; \n";
			$html .= "							if(!IsNumeric(valor) || (valor*1) <= 0) \n";
			$html .= "							{\n";
			$html .= "								CrearMensaje('Favor Digitar Un Valor Correcto o Mayor a Cero Para el cargo o insumo '+nombre.replace('chk_insumo_','')) \n";
			$html .= "								return;\n";
			$html .= "							}\n";
			$html .= "							else if((valor*1) > (cubierto*1) ) \n";
			$html .= "							{\n";
			$html .= "								CrearMensaje('El valor de la glosa para el cargo o insumo '+nombre.replace('chk_insumo_','')+', no debe ser mayor a '+cubierto) \n";
			$html .= "								return;\n";
			$html .= "							}\n";
			$html .= "							//else if(document.getElementById(nombre.replace('chk','sel')).value == '') \n";
			$html .= "							//{\n";
			$html .= "							//	CrearMensaje('No se ha seleccionado Motivo de Glosa Para el cargo o insumo '+nombre.replace('chk_insumo_','')) \n";
			$html .= "							//	return;\n";
			$html .= "							//}\n";
			$html .= "						}\n";
			$html .= "					}catch(error){}\n";
			$html .= "				break;\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "		if(document.getElementById('auditor').value != 'S')\n";
			$html .= "		{\n";
			$html .= "			flag = true;\n";
			$html .= "		}\n";
			$html .= "		if(!flag)\n";
			$html .= "		{\n";
			$html .= "			CrearMensaje('Favor Seleccionar Algun Item a glosar y/o Auditor'); \n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		frm.action = \"".$action['glosar']."\";\n";
			$html .= "		frm.submit();\n";
			$html .= "	}\n";
		
			$html .= "	function EvaluarDatosCuenta(frm)\n";
			$html .= "	{\n";
			$html .= "		if(!frm.glosa_cuenta.checked && !frm.glosa_copago.checked && !frm.glosa_cuota_moderadora.checked)\n";
			$html .= "		{\n";
			$html .= "			CrearMensaje('Favor Seleccionar Algun Item a glosar'); \n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		nombre = '';\n";
			$html .= "		for(i=0; i<frm.length; i++)\n";
			$html .= "		{\n";
			$html .= "			switch(frm[i].type)\n";
			$html .= "			{\n";
			$html .= "				case 'checkbox': \n";
			$html .= "					if(frm[i].checked && !frm[i].disabled) \n";
			$html .= "					{\n";
			$html .= "						try\n";
			$html .= "						{\n";
			$html .= "							nombre = frm[i].id; \n";
			$html .= "							valor = document.getElementById(nombre.replace('chk','inp')).value; \n";
			$html .= "							cubierto = document.getElementById(nombre.replace('chk','vlr')).value; \n";
			$html .= "							if(!IsNumeric(valor)) \n";
			$html .= "							{\n";
			$html .= "								CrearMensaje('Favor Digitar Un Valor Correcto o Mayor a Cero Para el copago o la cuota moderadora') \n";
			$html .= "								return;\n";
			$html .= "							}\n";
			$html .= "							else if((valor*1) > (cubierto*1)) \n";
			$html .= "							{\n";
			$html .= "								CrearMensaje('El valor de la glosa para el copago o la cuota moderadora, no debe ser mayor a '+cubierto) \n";
			$html .= "								return;\n";
			$html .= "							}\n";
			$html .= "						}\n";
			$html .= "						catch(error){}\n";
			$html .= "					}\n";
			$html .= "				break;\n";
			$html .= "				case 'select-one':\n";
			$html .= "					if(frm[i].value == '')\n";
			$html .= "					{\n";
			$html .= "						CrearMensaje('No se ha seleccionado Motivo de Glosa Para la cuenta o el copago o la cuota moderadora') \n";
			$html .= "						return;\n";
			$html .= "					}\n";
			$html .= "				break;\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "		if(document.cargos_insumos.mayor_valor.value!= '')\n";
			$html .= "		{\n";
			$html .= "			if(!IsNumeric(document.cargos_insumos.mayor_valor.value))\n";
			$html .= "			{\n";
			$html .= "				CrearMensaje('Favor Digitar Un Valor Correcto o Mayor a Cero Para el mayor valor glosado') \n";
			$html .= "				return;\n";
			$html .= "			}\n";
			$html .= "			frm.mayor_valor.value = document.cargos_insumos.mayor_valor.value;\n";
			$html .= "		}\n";
			$html .= "		if(document.cargos_insumos.menor_valor.value != '')\n";
			$html .= "		{\n";
			$html .= "			if(!IsNumeric(document.cargos_insumos.menor_valor.value))\n";
			$html .= "			{\n";
			$html .= "				CrearMensaje('Favor Digitar Un Valor Correcto o Mayor a Cero Para el menor valor glosado') \n";
			$html .= "				return;\n";
			$html .= "			}\n";
			$html .= "			frm.menor_valor.value = document.cargos_insumos.menor_valor.value;\n";
			$html .= "		}\n";
			$html .= "		if(document.cargos_insumos.sw_responder.checked)\n";
			$html .= "			frm.sw_responder.value = document.cargos_insumos.sw_responder.value;\n";
			$html .= "		frm.action = \"".$action['glosar']."&glcuenta=1\";\n";
			$html .= "		frm.submit();\n";
			$html .= "	}\n";
			
			$html .= "	function DisableCheck(frm,valor)\n";
			$html .= "	{\n";
			$html .= "		for(i=0; i<frm.length; i++)\n";
			$html .= "		{\n";
			$html .= "			switch(frm[i].type)\n";
			$html .= "			{\n";
			$html .= "				case 'checkbox':\n";
			$html .= "					frm[i].disabled = valor;\n";
			$html .= "				break;\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "		if(valor) document.getElementById('aceptarCargos').style.display='none';\n";
			$html .= "		else document.getElementById('aceptarCargos').style.display='block';\n";
			$html .= "	}\n";
			
			$html .= "	function mOvr(src,clrOver)\n";
			$html .= "	{\n";
			$html .= "		src.style.background = clrOver;\n";
			$html .= "	}\n";
			$html .= "	function mOut(src,clrIn)\n";
			$html .= "	{\n";
			$html .= "		src.style.background = clrIn;\n";
			$html .= "	}\n";

			$html .= "	function OcultarSpanObservacion(Seccion)\n";
			$html .= "	{\n";
			$html .= "		xGetElementById(Seccion).style.display = \"none\";\n";
			$html .= "		xGetElementById('observacion').value = '';\n";
			$html .= "	}\n";
			
			$html .= "	function MostrarSpanObservacion(Seccion)\n";
			$html .= "	{\n";
			$html .= "		xGetElementById(Seccion).style.display = \"\";\n";
			$html .= "	}\n";
			
			$html .= "	function IniciarObservacion(titulo)\n";
			$html .= "	{\n";
			$html .= "		ele = xGetElementById('ContenedorObservacion');\n";
			$html .= "	  xResizeTo(ele,400, 'auto');\n";
			$html .= "	  xMoveTo(ele, xClientWidth()/4, xScrollTop()+24);\n";
			$html .= "		ele = xGetElementById('tituloObservacon');\n";
			$html .= "		ele.innerHTML = titulo;\n";
			$html .= "	  xResizeTo(ele,380, 20);\n";
			$html .= "		xMoveTo(ele, 0, 0);\n";
			$html .= "	  xEnableDrag(ele, myOnDrag1Start1, myOnDrag1, myOnDrag1End);\n";
			$html .= "		ele = xGetElementById('cerrarObservacon');\n";
			$html .= "	  xResizeTo(ele,20, 20);\n";
			$html .= "		xMoveTo(ele, 380, 0);\n";
			$html .= "	}\n";
			$html .= "	function myOnDrag1Start1(ele, mx, my)\n";
			$html .= "	{\n";
			$html .= "	  window.status = '';\n";
			$html .= "	  if (ele.id == 'tituloObservacon') xZIndex('ContenedorObservacion', hiZ++);\n";
			$html .= "	  else xZIndex(ele, hiZ++);\n";
			$html .= "	  ele.myTotalMX = 0;\n";
			$html .= "	  ele.myTotalMY = 0;\n";
			$html .= "	}\n";
			$html .= "	function myOnDrag1(ele, mdx, mdy)\n";
			$html .= "	{\n";
			$html .= "	  if (ele.id == 'tituloObservacon') {\n";
			$html .= "	    xMoveTo('ContenedorObservacion', xLeft('ContenedorObservacion') + mdx, xTop('ContenedorObservacion') + mdy);\n";
			$html .= "	  }\n";
			$html .= "	  else {\n";
			$html .= "	    xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
			$html .= "	  }  \n";
			$html .= "	  ele.myTotalMX += mdx;\n";
			$html .= "	  ele.myTotalMY += mdy;\n";
			$html .= "	}\n";
			$html .= "	function myOnDrag1End(ele, mx, my)\n";
			$html .= "	{\n";
			$html .= "	}\n";

			$html .= "	function IniciarAnulacion()\n";
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
			
			$html .= "	function EvaluarDatosAnulacion(frm)\n";
			$html .= "	{\n";
			$html .= "		if(frm.observacion.value == '')\n";
			$html .= "		{\n";
			$html .= "			xGetElementById('error').innerHTML = 'SE DEBE INGRESAR EL MOTIVO POR EL CUAL SE ESTA ANULADO LA GLOSA SOBRE LA CUENTA'; \n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		xajax_AnularGlosaCuenta(frm.observacion.value,'".$cuenta[$datos['numerodecuenta']]['glosa_detalle_cuenta_id']."','".$datos['glosa_id']."')\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			
			$html .= "<div id='ContenedorI' class='d2Container' style=\"display:none;z-index:4\">\n";
			$html .= "	<div id='tituloI' class='draggable' style=\"	text-transform: uppercase;text-align:center\">ANULAR GLOSA CUENTA</div>\n";
			$html .= "	<div id='cerrarI' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('ContenedorI')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div>\n";
			$html .= "	<div id='ContenidoI' class='d2Content' style=\"background:#EFEFEF\"><br><br>\n";
			$html .= "		<form name=\"anulacion\" action=\"javascript:EvaluarDatosAnulacion(document.anulacion)\" method=\"post\">\n";
			$html .= "			<center>\n";
			$html .= "				<div id=\"error\" class=\"label_error\"></div>\n";
			$html .= "			</center>\n";
			$html .= "			<table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "				<tr class=\"modulo_table_list_title\" >\n";
			$html .= "					<td>MOTIVO DE LA ANULACIÓN DE LA GLOSA</td>\n";
			$html .= "				</tr>\n";
			$html .= "				<tr>\n";
			$html .= "					<td align=\"center\">\n";
			$html .= "						<textarea id=\"observacion\" name=\"observacion\" style=\"width:100%\" class=\"textarea\" rows=\"3\"></textarea>\n";
			$html .= "					</td>\n";
			$html .= "				</tr>\n";
			$html .= "			</table><br>\n";
			$html .= "			<center>\n";
			$html .= "				<input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">&nbsp;&nbsp;&nbsp;&nbsp;\n";
			$html .= "				<input type=\"button\" class=\"input-submit\" value=\"Cancelar\" onclick=\"OcultarSpan('ContenedorI')\">\n";
			$html .= "			</center><br>\n";
			$html .= "		</form>\n";
			$html .= "	</div>\n";
			$html .= "</div>\n";
			
			$html .= "<table align=\"center\" cellpading=\"0\"  width=\"70%\" border=\"0\" class=\"modulo_table_list\">\n";
			$html .= "	<tr $estilo>\n";
			$html .= "		<td width=\"15%\">Nº FACTURA</td>\n";
			$html .= "		<td class=\"modulo_list_claro\" width=\"10%\">\n";
			$html .= "			".$datos['prefijo']." ".$datos['factura_fiscal']."\n";
			$html .= "		</td>\n";
			$html .= "		<td width=\"15%\">Nº CUENTA</td>\n";
			$html .= "		<td class=\"modulo_list_claro\"  align=\"right\" width=\"10%\">\n";
			$html .= "			".$datos['numerodecuenta']."\n";
			$html .= "		</td>\n";
			$html .= "		<td width=\"10%\">PLAN</td>\n";
			$html .= "		<td class=\"modulo_list_claro\" colspan=\"3\" width=\"40%\">\n";
			$html .= "			".$cuenta[$datos['numerodecuenta']]['plan_descripcion']."\n";
			$html .= "		</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr $estilo>\n";
			$html .= "			<td>Nº INGRESO</td>\n";
			$html .= "			<td class=\"modulo_list_claro\" align=\"right\">\n";
			$html .= "				".$cuenta[$datos['numerodecuenta']]['ingreso']."\n";
			$html .= "			</td>\n";
			$html .= "			<td width=\"15%\">PACIENTE</td>\n";
			$html .= "			<td class=\"modulo_list_claro\" colspan=\"2\" width=\"25%\">\n";
			$html .= "				".$cuenta[$datos['numerodecuenta']]['identificacion']." \n";
			$html .= "			</td>\n";
			$html .= "			<td class=\"modulo_list_claro\" colspan=\"3\" width=\"40%\">\n";
			$html .= "				".$cuenta[$datos['numerodecuenta']]['paciente']."\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr $estilo>\n";
			$html .= "			<td >COPAGO</td>\n";
			$html .= "			<td class=\"modulo_list_claro\" align=\"right\">\n";
			$html .= "				".formatoValor($cuenta[$datos['numerodecuenta']]['valor_cuota_paciente'])."\n";
			$html .= "			</td>\n";
			$html .= "			<td colspan=\"2\">CUOTA MODERADORA</td>\n";
			$html .= "			<td class=\"modulo_list_claro\" align=\"right\" >\n";
			$html .= "				".formatoValor($cuenta[$datos['numerodecuenta']]['valor_cuota_moderadora'])."\n";
			$html .= "			</td>\n";
			$html .= "			<td colspan=\"2\" >TOTAL EMPRESA</td>\n";
			$html .= "			<td class=\"modulo_list_claro\" align=\"right\" width=\"15%\">\n";
			$html .= "				".formatoValor($cuenta[$datos['numerodecuenta']]['valor_total_empresa'])."\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table><br>\n";
			
			$chk1 = $chk2 = $chk3 = $dsbl = $dsb2 = $dsb3 = "";
			$html .= "<form name=\"cuenta\" method=\"post\" action=\"javascript:EvaluarDatosCuenta(document.cuenta)\">\n";
			$html .= "	<table align=\"center\" cellpading=\"0\"  width=\"70%\" border=\"0\" class=\"modulo_table_list\">\n";
			$html .= "		<tr class=\"formulacion_table_list\">\n";
			$html .= "			<td colspan=\"4\">\n";
			$html .= "				GLOSA SOBRE LA CUENTA\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			
			$display = "block";
			if($datos['cantidad'] == 1)
			{	
				if($cuenta[$datos['numerodecuenta']]['sw_glosa_total_cuenta'] == "1")
				{
					$chk1 = "checked";
					$chk2 = $chk3 = "disabled";
					$dsbl = "disabled=\"false\"";
					$display = "none";
				}
				
				$html .= "		<tr class=\"modulo_table_list_title\">\n";
				$html .= "			<td colspan=\"4\" align=\"left\">\n";
				$html .= "				<input type=\"checkbox\" value=\"1\" name=\"glosa_cuenta\" $chk1 onClick=\"DisableCheck(document.cargos_insumos,this.checked)\">\n";
				$html .= "				GLOSA SOBRE EL VALOR DE LA CUENTA\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
			}
			else
			{
				$html .= "		<input type=\"hidden\" name=\"glosa_cuenta\">\n";
			}
			$dsb2 = $dsb3 = "disabled =\"true\"";
			$dsb4 = $dsb5 = "";
				
			if($cuenta[$datos['numerodecuenta']]['valor_cuota_paciente'] > 0) 
			{
				if($cuenta[$datos['numerodecuenta']]['valor_glosa_copago'] > 0)
				{
					$dsb2 = "disabled =\"false\"";
				}
			}
			else
			{
				$cuenta[$datos['numerodecuenta']]['valor_glosa_copago'] = "";
				$dsb4 = "disabled=\"true\"";
			}
				
			if($cuenta[$datos['numerodecuenta']]['valor_cuota_moderadora'] > 0)
			{
				if($cuenta[$datos['numerodecuenta']]['valor_glosa_cuota_moderadora'] > 0)
				{
					$dsb3 = "disabled =\"false\"";
				}
			}
			else
			{
				$cuenta[$datos['numerodecuenta']]['valor_glosa_cuota_moderadora'] = "";
				$dsb5 = "disabled =\"true\"";
			}
			
			$html .= "		<tr class=\"modulo_list_claro\" >\n";
			$html .= "			<td class=\"modulo_table_list_title\" style=\"text-align:left\" >\n";
			$html .= "				<input type=\"checkbox\" value=\"1\" id=\"chk_copago\" name=\"glosa_copago\" $dsb4 $chk2 onclick=\"CambiarEstadoCheck(this.checked,'copago')\">\n";
			$html .= "				GLOSA SOBRE EL VALOR DEL COPAGO\n";
			$html .= "			</td>\n";
			$html .= "			<td class=\"normal_10AN\" width=\"20%\" align=\"right\">\n";
			$html .= "				$".formatoValor($cuenta[$datos['numerodecuenta']]['valor_cuota_paciente'])."\n";
			$html .= "			</td>\n";
			$html .= "			<td class=\"normal_10AN\" width=\"1%\">\n";
			$html .= "				<img src=\"".GetThemePath()."/images/siguiente.png\" style=\"cursor:pointer;\" border=\"0\" onclick=\"CambiarValor('copago')\">\n";
			$html .= "				<input type=\"hidden\" id=\"vlr_copago\" value=\"".$cuenta[$datos['numerodecuenta']]['valor_cuota_paciente']."\">\n";
			$html .= "			</td>\n";
			$html .= "			<td class=\"normal_10AN\" width=\"20%\">\n";
			$html .= "				$<input type=\"text\" id=\"inp_copago\" name=\"cantidad_copago\" class=\"input-text\" style=\"width:80%\" onkeypress=\"return acceptNum(event)\" value=\"".$cuenta[$datos['numerodecuenta']]['valor_glosa_copago']."\" maxlength =\"15\" $dsb2>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_list_claro\" >\n";
			$html .= "			<td class=\"modulo_table_list_title\" style=\"text-align:left\" >\n";
			$html .= "				<input type=\"checkbox\" value=\"1\" id=\"chk_cuota_moderadora\" name=\"glosa_cuota_moderadora\" $dsb5 $chk3 onclick=\"CambiarEstadoCheck(this.checked,'cuota_moderadora')\">\n";
			$html .= "				GLOSA SOBRE EL VALOR DE LA CUOTA MODERADORA\n";
			$html .= "			</td>\n";
			$html .= "			<td class=\"normal_10AN\" width=\"20%\" align=\"right\">\n";
			$html .= "				$".formatoValor($cuenta[$datos['numerodecuenta']]['valor_cuota_moderadora'])."\n";
			$html .= "			</td>\n";
			$html .= "			<td class=\"normal_10AN\" width=\"1%\">\n";
			$html .= "				<img src=\"".GetThemePath()."/images/siguiente.png\" style=\"cursor:pointer;\" border=\"0\" onclick=\"CambiarValor('cuota_moderadora')\">\n";
			$html .= "				<input type=\"hidden\" id=\"vlr_cuota_moderadora\" value=\"".$cuenta[$datos['numerodecuenta']]['valor_cuota_moderadora']."\">\n";
			$html .= "			</td>\n";
			$html .= "			<td class=\"normal_10AN\" width=\"20%\">\n";
			$html .= "				$<input type=\"text\" id=\"inp_cuota_moderadora\" name=\"cantidad_cuota_moderadora\" class=\"input-text\" style=\"width:80%\" onkeypress=\"return acceptNum(event)\" value=\"".$cuenta[$datos['numerodecuenta']]['valor_glosa_cuota_moderadora']."\" maxlength =\"15\" $dsb3>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			if($cuenta[$datos['numerodecuenta']]['motivo_glosa_id'] != "" AND $cuenta[$datos['numerodecuenta']]['motivo_glosa_id'] != 'NINGUNA')
			{
				$html .= "		<tr class=\"modulo_table_list_title\">\n";
				$html .= "			<td>MOTIVO DE GLOSA</td>\n";
				$html .= "			<td align=\"left\" class=\"modulo_list_claro\" colspan=\"3\">\n";
				$html .= "				<select name=\"motivos_glosa\" class=\"select\">\n";
				$html .= "					<option value=\"\">-------SELECCIONAR-------</option>\n";			
				$sel = $html1 = "";
				foreach($motivos as $key => $detalle)
				{
					$sl = "";
					if($cuenta[$datos['numerodecuenta']]['motivo_glosa_id'] == $detalle['motivo_glosa_id']) $sl= "selected";
					
					$html .= "					<option value='".$detalle['motivo_glosa_id']."' title=\"".$detalle['motivo_glosa_descripcion']."\" $sl >".((strlen($detalle['motivo_glosa_descripcion']) > 30 )? substr($detalle['motivo_glosa_descripcion'],0,30)."...": $detalle['motivo_glosa_descripcion'])."</option>\n";
					$html1 .= "					<option value='".$detalle['motivo_glosa_id']."' title=\"".$detalle['motivo_glosa_descripcion']."\">".((strlen($detalle['motivo_glosa_descripcion']) > 30 )? substr($detalle['motivo_glosa_descripcion'],0,30)."...": $detalle['motivo_glosa_descripcion'])."</option>\n";
				}
				$html .= "				</select>\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
			}
			//CONCEPTO GENERAL
			$js = "<script>\n";
			$js .= " function GetConceptos(valor,vector){\n";
			$js .= " var vect;\n";
			$js .= " for(i=0; i<vector.length; i++){\n";
			$js .= "	switch(vector[i].type)\n";
			$js .= "	{\n";
			$js .= "		case 'radio':  \n";
			$js .= "			vect = vector[i].value.split('||//')[0];\n";
			$js .= "			document.getElementById('concepto'+vect).style.display = 'none';\n";
			$js .= "		break;\n";
			$js .= "	}\n";
			$js .= " \n";
			$js .= " }\n";
			$js .= " identificador = 'concepto'+valor;\n";
			$js .= " if(document.getElementById(identificador).style.display == 'none'){\n";
			$js .= "  document.getElementById(identificador).style.display = 'block';\n";
			$js .= "  }else{ \n";
			$js .= "   document.getElementById(identificador).style.display = 'none';\n";
			$js .= "  } ;\n";
			$js .="  }\n";
			$js .= " function GetConceptosCargos(valor,vector,transaccion){\n";
			$js .= " var vect;\n";
			$js .= " for(i=0; i<vector.length; i++){\n";
			$js .= "	switch(vector[i].type)\n";
			$js .= "	{\n";
			$js .= "		case 'radio':  \n";
			$js .= "			vect = vector[i].value.split('||//')[0];\n";
			$js .= "			document.getElementById('conceptocargos_'+vect+'_'+transaccion).style.display = 'none';\n";
			$js .= "		break;\n";
			$js .= "	}\n";
			$js .= " \n";
			$js .= " }\n";
			$js .= " identificador = 'conceptocargos_'+valor+'_'+transaccion;\n";
			$js .= " if(document.getElementById(identificador).style.display == 'none'){\n";
			$js .= "  document.getElementById(identificador).style.display = 'block';\n";
			$js .= "  }else{ \n";
			$js .= "   document.getElementById(identificador).style.display = 'none';\n";
			$js .= "  } ;\n";
			$js .="  }\n";
			$js .= " function GetConceptosInsumos(valor,vector,transaccion){\n";
			$js .= " var vect;\n";
			$js .= " for(i=0; i<vector.length; i++){\n";
			$js .= "	switch(vector[i].type)\n";
			$js .= "	{\n";
			$js .= "		case 'radio':  \n";
			$js .= "			vect = vector[i].value.split('||//')[0];\n";
			$js .= "			document.getElementById('conceptoinsumos_'+vect+'_'+transaccion).style.display = 'none';\n";
			$js .= "		break;\n";
			$js .= "	}\n";
			$js .= " \n";
			$js .= " }\n";
			$js .= " identificador = 'conceptoinsumos_'+valor+'_'+transaccion;\n";
			$js .= " if(document.getElementById(identificador).style.display == 'none'){\n";
			$js .= "  document.getElementById(identificador).style.display = 'block';\n";
			$js .= "  }else{ \n";
			$js .= "   document.getElementById(identificador).style.display = 'none';\n";
			$js .= "  } ;\n";
			$js .="  }\n";
			$js .= "</script>\n";
			$html .= "$js";
			$ConceptosGenerales = $gl->ObtenerConceptosGenerales();
			
			$html .= "			<tr class=\"modulo_table_list_title\">\n";
			$html .= "				<td style=\"text-align:center;text-indent:8pt\"><b>CONCEPTO GENERAL</b></td>\n";
			$html .= "				<td align=\"right\" class=\"modulo_list_claro\" colspan=\"2\">\n";
			$html .= "					<select name=\"concepto_general\" id=\"concepto_general\" class=\"select\" onchange=\"GetConceptos(this.value,document.cuenta);\">\n";
			$html .= "						<option value='V' selected>-------SELECCIONAR-------</option>\n";			
			for($i=0; $i<sizeof($ConceptosGenerales); )
			{
 				($this->Concepto == $ConceptosGenerales[$i]['codigo_concepto_general'])? $sel = "selected": $sel = "";
 				//('-1' == $ConceptosGenerales[$i]['codigo_concepto_general'])? $sel = "selected": $sel = "";
				$k = $i;
				while($ConceptosGenerales[$i]['codigo_concepto_general'] == $ConceptosGenerales[$k]['codigo_concepto_general'])
				{
					$k++;
				}
				$sl = "";
				if($cuenta[$datos['numerodecuenta']]['codigo_concepto_general'] == $ConceptosGenerales[$i-1]['codigo_concepto_general']) $sl= "selected";
				$i = $k;
				$html .= "					<option value='".$ConceptosGenerales[$i-1]['codigo_concepto_general']."' $sl>".$ConceptosGenerales[$i-1]['descripcion_concepto_general']."</option>\n";
			}
			
			$html .= "					</select>\n";
			$html .= "				</td>\n";
			$html .= "			</tr>\n";

			$html .= "			<tr class=\"modulo_table_list\">\n";
			$html .= "				<td style=\"text-align:left;text-indent:8pt\" colspan=\"3\">\n";
			for($i=0; $i<sizeof($ConceptosGenerales);)
			{
				$html .= "<div id='concepto".$ConceptosGenerales[$i][codigo_concepto_general]."' style=\"display:none\">";
				$html .= "				<table align=\"center\" cellpading=\"0\"  width=\"60%\" border=\"0\" class=\"modulo_table_list\">\n";
				$html .= "					<tr class=\"modulo_table_list_title\">\n";
				$html .= "						<td style=\"text-align:left;text-indent:8pt\"><b>SEL</b>\n";
				$html .= "						</td>\n";
				$html .= "						<td style=\"text-align:left;text-indent:8pt\"><b>ID</b>\n";
				$html .= "						</td>\n";
				$html .= "						<td style=\"text-align:left;text-indent:8pt\"><b>DESCRIP.</b>\n";
				$html .= "						</td>\n";
				$html .= "					</tr>\n";
				$k = $i;
				while ($ConceptosGenerales[$i][codigo_concepto_general] == $ConceptosGenerales[$k][codigo_concepto_general])
				{
					$html .= "				<tr class=\"modulo_table_list\">\n";
					$html .= "					<td style=\"text-align:left;text-indent:8pt\"><input type=\"radio\" name=\"concepto_especifico\" value=\"".$ConceptosGenerales[$k][codigo_concepto_general]."||//".$ConceptosGenerales[$k][codigo_concepto_especifico]."\">\n";
					$html .= "					</td>\n";
					$html .= "					<td style=\"text-align:left;text-indent:8pt\"><b>".$ConceptosGenerales[$k][codigo_concepto_especifico]."</b>\n";
					$html .= "					</td>\n";
					$html .= "					<td style=\"text-align:left;text-indent:8pt\"><b>".$ConceptosGenerales[$k][descripcion_concepto_especifico]."</b>\n";
					$html .= "					</td>\n";
					$html .= "				</tr>\n";
					$k++;
				}
				$i = $k;
				$html .= "				</table><br>\n";
				$html .= "</div>";
			}
			$html .= "				</td>\n";
			$html .= "			</tr>\n";
			//FIN CONCEPTO GENERAL
			
			$html .= "		<tr class=\"modulo_table_list_title\" >\n";
			$html .= "			<td colspan=\"4\">OBSERVACIÓN</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_list_claro\"  >\n";
			$html .= "			<td colspan=\"4\">";
			$html .= "				<textarea class=\"textarea\" name=\"observacion_glosa\" style=\"width:100%\" rows=\"2\">".$cuenta[$datos['numerodecuenta']]['observacion']."</textarea>";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "	<input type=\"hidden\" name=\"mayor_valor\" value=\"\">\n";
			$html .= "	<input type=\"hidden\" name=\"menor_valor\" value=\"\">\n";
			$html .= "	<input type=\"hidden\" name=\"auditor\" value=\"\">\n";
			$html .= "	<input type=\"hidden\" name=\"sw_responder\" value=\"\">\n";
			$html .= "</form><br>\n";

			$html .= "<form name=\"cargos_insumos\" method=\"post\" action=\"javascript:EvaluarDatos(document.cargos_insumos)\">\n";
			$html .= "	<table align=\"center\" cellpading=\"0\"  width=\"70%\" border=\"0\" class=\"modulo_table_list\">\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td style=\"text-align:left;text-indent:8pt\" width=\"50%\">ASIGNAR AUDITOR</td>\n";
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\" >\n";
			$html .= "				<select id=\"auditor\" name=\"auditor\" class=\"select\">\n";
			$html .= "					<option value='S'>-------SELECCIONAR-------</option>\n";			

			$Auditores = $gld->ObtenerAuditoresInternos();
			foreach($Auditores as $key => $dtl)
			{
				($cuenta[$datos['numerodecuenta']]['auditor_id']==$dtl['usuario_id'])?$sel = "selected":$sel = "";
				$html .= "					<option value='".$dtl['usuario_id']."' $sel>".$dtl['nombre']."</option>\n";
			}			
			$html .= "				</select>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td>MAYOR VALOR GLOSADO</td>\n";
			$html .= "			<td>MENOR VALOR GLOSADO</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_list_claro\">\n";
			$html .= "			<td align=\"center\">\n";
			$html .= "				$<input type=\"text\" id=\"mayor_valor\" name=\"mayor_valor\" class=\"input-text\" style=\"width:60%\" onkeypress=\"return acceptNum(event)\" value=\"".$cuenta[$datos['numerodecuenta']]['mayor_valor']."\" maxlength =\"15\">\n";
			if($cuenta[$datos['numerodecuenta']]['mayor_valor'])
			{
				$html .= "			<input type=\"button\" value=\"Modificar\" class=\"input-submit\" onclick=\"xajax_ModificarValores('".$cuenta[$datos['numerodecuenta']]['glosa_detalle_cuenta_id']."',document.cargos_insumos.mayor_valor.value,'mayor_valor')\">\n";
			}
			$html .= "			</td>\n";
			$html .= "			<td align=\"center\">\n";
			$html .= "				$<input type=\"text\" id=\"menor_valor\" name=\"menor_valor\" class=\"input-text\" style=\"width:60%\" onkeypress=\"return acceptNum(event)\" value=\"".$cuenta[$datos['numerodecuenta']]['menor_valor']."\" maxlength =\"15\">\n";
			if($cuenta[$datos['numerodecuenta']]['menor_valor'])
			{
				$html .= "			<input type=\"button\" value=\"Modificar\" class=\"input-submit\" onclick=\"xajax_ModificarValores('".$cuenta[$datos['numerodecuenta']]['glosa_detalle_cuenta_id']."',document.cargos_insumos.menor_valor.value,'menor_valor')\">\n";
			}
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table><br>\n";
			
			$html .= "<fieldset class=\"fieldset\">\n";
			$html .= "	<legend class=\"normsl_10AN\">GLOSA SOBRE CARGOS Y/O INSUMOS DE LA CUENTA</legend>\n";
			
			if(sizeof($cargos) > 0)
			{
				$Agrupado = "";
				$background = "#CCCCCC";
				
				$html .= "	<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";		
				$html .= "		<tr class=\"modulo_table_list_title\" height=\"17\">\n";
				$html .= "			<td width=\"8%\" >FECHA</b></td>\n";
				$html .= "			<td width=\"14%\" colspan=\"2\">CARGO - TARIFA</td>\n";
				$html .= "			<td width=\"30%\">DESCRIPCIÓN</b></td>\n";
				$html .= "			<td width=\"8%\">CUBIERTO</td>\n";
				$html .= "			<td width=\"1%\" ></td>\n";
				$html .= "			<td width=\"10%\">V. OBJETADO</td>\n";
				$html .= "			<td width=\"%\" colspan=\"2\">MOTIVO - OBSERVACION</td>\n";
				$html .= "			<td width=\"1%\"></td>\n";
				$html .= "		</tr>\n";
				foreach($cargos as $key => $detalle)
				{
					$marca = "";
					if($background == "#DDDDDD")
					{
						$estilo='modulo_list_oscuro'; $background = "#CCCCCC";
					}
					else
					{
					 	$estilo='modulo_list_claro'; 	$background = "#DDDDDD";
					}
					
					if(strlen($detalle['descripcion']) > 40)
					{
						$marca = " title =\"".$detalle['descripcion']."\" ";
						$detalle['descripcion'] = substr($detalle['descripcion'],0,40)."...";
					}
					
					if($detalle['agrupado'] != $Agrupado)
					{
						$ActoQx = $gld->ObtenerActoQuirurgico($detalle['transaccion'],$detalle['agrupado']);
						if($ActoQx)
						{
							if($detalle['agrupado']) $ActoQx = "PROCEDIMIENTO QUIRÚRGICO: ".$ActoQx;
							
							$estilo1 = "class=\"hc_table_submodulo_title\" style=\"text-align:center;font-size:10px;text-indent: 0pt\"";
	
							$html .= "		<tr $estilo1 height=\"17\">\n";
							$html .= "			<td colspan=\"10\">$ActoQx</td>\n";
							$html .= "		</tr>\n";
						}
					}
					$Agrupado = $detalle['agrupado'];
					
					$valoraceptado = 0;
					if($detalle['glosa_detalle_cargo_id'])
						$valoraceptado = $gld->ObtenerCargoConNC($datos['glosa_id'],$detalle['transaccion'],$datos['numerodecuenta'],'0');

					$dsbl1 = $selec = "";
					$dsbl2 = "disabled=\"true\"";
					
					if($dsbl != "")
						$dsbl1 = $dsbl2 = $dsbl;
					else if($detalle['glosa_detalle_cargo_id'])
					{
						$dsbl2 = "readonly";$selec = "disabled=\"true\"";
					}
					
					$html .= "		<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
					$html .= "			<td align=\"center\">".$detalle['registro']."</td>\n";
					$html .= "			<td width=\"7%\" align=\"center\">".$detalle['cargo_cups']."</td>\n";
					$html .= "			<td width=\"7%\" align=\"center\">".$detalle['tarifario_id']."</td>\n";
					$html .= "			<td class=\"normal_10AN\" align=\"justify\" $marca>".$detalle['descripcion']."</td>\n";
					$html .= "			<td align=\"right\" >$".formatoValor($detalle['valor_cubierto'])."&nbsp;\n";
					$html .= "				<input type=\"hidden\" id=\"vlr_cargo_$key\" value=\"".($detalle['valor_cubierto'] - $valoraceptado)."\" >\n";
					$html .= "				<input type=\"hidden\" id=\"vcp_cargo_$key\" value=\"".$detalle['valor_cargo']."\">\n";
					$html .= "			</td>\n";
					$html .= "			<td>\n";
					$html .= "				<img src=\"".GetThemePath()."/images/siguiente.png\" style=\"cursor:pointer;\" border=\"0\" onclick=\"CambiarValor('cargo_$key')\">\n";
					$html .= "			</td>\n";
					$html .= "			<td>\n";
					$html .= "				$<input type=\"text\" id=\"inp_cargo_$key\" $dsbl2 name=\"cargos[$key][".$detalle['cargo_cups']."][valor_glosa]\" class=\"input-text\" style=\"width:90%\" onkeypress=\"return acceptNum(event)\" value=\"".$detalle['valor_glosa']."\"\>\n";
					$html .= "			</td>\n";
/*					$html .= "			<td class=\"normal_10AN\">\n";
					$html .= "				<select $selec id=\"sel_cargo_$key\" name=\"cargos[$key][".$detalle['cargo_cups']."][motivo_glosa]\" class=\"select\" disabled>\n";
					$html .= "					<option value=\"\">-------SELECCIONAR-------</option>\n";			
					$html .= "					".$html1;
					$html .= "				</select>\n";
					$html .= "			</td>\n";*/
					$disableb ="";
					if($detalle['glosa_detalle_cargo_id'])
					{$disableb = "disabled";}
						$html .= "		<td  class=\"normal_10AN\" >\n";
						$html .= "			<select name=\"concepto_general\" id=\"concepto_general\" class=\"select\" onchange=\"GetConceptosCargos(this.value,document.cuenta,'".$detalle['transaccion']."');\" $disableb>\n";
						$html .= "				<option value='V' >-------SELECCIONAR-------</option>\n";			
			
						for($i=0; $i<sizeof($ConceptosGenerales); )
						{
							//($this->Concepto == $ConceptosGenerales[$i]['codigo_concepto_general'])? $sel = "selected": $sel = "";
							$k = $i;
							while($ConceptosGenerales[$i]['codigo_concepto_general'] == $ConceptosGenerales[$k]['codigo_concepto_general'])
							{
								$k++;
							}
							$i = $k;
							$sl = "";
							if(trim($detalle['codigo_concepto_general']) == trim($ConceptosGenerales[$i-1]['codigo_concepto_general'])) $sl= "selected";
							$html .= "			<option value='".$ConceptosGenerales[$i-1]['codigo_concepto_general']."' $sl >".$ConceptosGenerales[$i-1]['descripcion_concepto_general']."</option>\n";
						}
					$html .= "					</select>\n";
					if($detalle['motivo_glosa_descripcion'])
					$html .= "<img src=\"".GetThemePath()."/images/pcargos.png\" style=\"cursor:pointer;\" border=\"0\" title=\"MOTIVO GLOSA: ".$detalle['motivo_glosa_descripcion']."\" >\n";
					$html .= "			</td>\n";
					
					$html .= "			<td>\n";
					$html .= "				<img src=\"".GetThemePath()."/images/auditoria.png\" style=\"cursor:pointer;\" border=\"0\" onclick=\"ObtenerObservacion('INGRESAR OBSERVACION PARA EL CARGO: ".$detalle['cargo_cups']."','cargo_$key')\">\n";
					$html .= "			</td>\n";
					$html .= "			<td>\n";
					if($detalle['glosa_detalle_cargo_id'])
					{
						$url = $action['modificar'].URLRequest(array("tipom"=>"C","glosa_detalle_cargo_id"=>$detalle['glosa_detalle_cargo_id']));
						if($detalle['sw_estado'] == "1")
						{
							$html .= "				<a href=\"".$url."\" title=\"EDITAR CARGO\">\n";
							$html .= "					<img src=\"".GetThemePath()."/images/edita.png\" border=\"0\">\n";
							$html .= "				</a>\n";
						}
					}
					else
					{
						$html .= "				<input type=\"checkbox\" value=\"1\" $dsbl1 id=\"chk_cargo_$key\" name=\"cargos[$key][".$detalle['cargo_cups']."][checbox]\" onclick=\"CambiarEstadoCheck(this.checked,'cargo_$key')\">\n";
						if($valoraceptado > 0)
						{
							$inf = "ESTE CARGO POSEE UNA \n NOTA CREDITO POR $".formatoValor($valoraceptado);
							$html .= "				<img src=\"".GetThemePath()."/images/informacion.png\" border=\"0\" title=\"".$inf."\">\n";
						}
					}
					$html .= "			</td>\n";
					$html .= "		</tr>\n";
					//
					$html .= "		<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
					$html .= "				<td style=\"text-align:right;text-indent:8pt\" colspan=\"4\"><b>CONCEPTO GENERAL / ESPECIFICO</b></td>\n";
					if($detalle['glosa_detalle_cargo_id'])
					{
						$html .= "				<td align=\"center\" class=\"modulo_list_claro\" colspan=\"7\">\n";
						$html .= "				<label class=\"label_mark\"><B>".$detalle['descripcion_concepto_general']." / ".$detalle['descripcion_concepto_especifico']."</b></label></td>\n";
					}
					else
					{
						$html .= "				<td align=\"center\" class=\"modulo_list_claro\" colspan=\"7\">\n";
						$html .= "					<div id=\"cargos_".$detalle['transaccion']."\"></div>\n";
						$html .= "				</td>\n";
					}
/*					else
					{
						$html .= "				<td align=\"right\" class=\"modulo_list_claro\" colspan=\"3\">\n";
						$html .= "					<select name=\"concepto_general\" id=\"concepto_general\" class=\"select\" onchange=\"GetConceptosCargos(this.value,document.cuenta,'".$detalle['transaccion']."');\">\n";
						$html .= "						<option value='V' >-------SELECCIONAR-------</option>\n";			
			
						for($i=0; $i<sizeof($ConceptosGenerales); )
						{
							//($this->Concepto == $ConceptosGenerales[$i]['codigo_concepto_general'])? $sel = "selected": $sel = "";
							$k = $i;
							while($ConceptosGenerales[$i]['codigo_concepto_general'] == $ConceptosGenerales[$k]['codigo_concepto_general'])
							{
								$k++;
							}
							$i = $k;
							$sl = "";
							if(trim($detalle['codigo_concepto_general']) == trim($ConceptosGenerales[$i-1]['codigo_concepto_general'])) $sl= "selected";
							$html .= "					<option value='".$ConceptosGenerales[$i-1]['codigo_concepto_general']."' $sl >".$ConceptosGenerales[$i-1]['descripcion_concepto_general']."</option>\n";
						}
					}*/
					$html .= "					</select>\n";
					$html .= "				</td>\n";
					$html .= "			</tr>\n";
		
					$html .= "			<tr class=\"".$estilo."\">\n";
					$html .= "				<td style=\"text-align:left;text-indent:8pt\" colspan=\"9\">\n";
					for($i=0; $i<sizeof($ConceptosGenerales);)
					{
						$html .= "<div id='conceptocargos_".$ConceptosGenerales[$i][codigo_concepto_general]."_".$detalle['transaccion']."' style=\"display:none\">";
						$html .= "				<table align=\"center\" cellpading=\"0\"  width=\"60%\" border=\"0\" class=\"modulo_table_list\">\n";
						$html .= "					<tr class=\"modulo_table_list_title\">\n";
						$html .= "						<td style=\"text-align:left;text-indent:8pt\"><b>SEL</b>\n";
						$html .= "						</td>\n";
						$html .= "						<td style=\"text-align:left;text-indent:8pt\"><b>ID</b>\n";
						$html .= "						</td>\n";
						$html .= "						<td style=\"text-align:left;text-indent:8pt\"><b>DESCRIP.</b>\n";
						$html .= "						</td>\n";
						$html .= "					</tr>\n";
						$k = $i;
						while ($ConceptosGenerales[$i][codigo_concepto_general] == $ConceptosGenerales[$k][codigo_concepto_general])
						{
							$html .= "				<tr class=\"modulo_table_list\">\n";
							$html .= "					<td style=\"text-align:left;text-indent:8pt\"><input type=\"radio\" name=\"cargos[".$detalle['transaccion']."][".$detalle['cargo_cups']."][conceptos]\" value=\"".$ConceptosGenerales[$k][codigo_concepto_general]."||//".$ConceptosGenerales[$k][codigo_concepto_especifico]."\" onclick=\"xajax_AsignarConceptos('C','".$detalle['transaccion']."','".$ConceptosGenerales[$k][descripcion_concepto_general]."','".$ConceptosGenerales[$k][descripcion_concepto_especifico]."','".$ConceptosGenerales[$i][codigo_concepto_general]."');\">\n";
							$html .= "					</td>\n";
							$html .= "					<td style=\"text-align:left;text-indent:8pt\"><b>".$ConceptosGenerales[$k][codigo_concepto_especifico]."</b>\n";
							$html .= "					</td>\n";
							$html .= "					<td style=\"text-align:left;text-indent:8pt\"><b>".$ConceptosGenerales[$k][descripcion_concepto_especifico]."</b>\n";
							$html .= "					</td>\n";
							$html .= "				</tr>\n";
							$k++;
						}
						$i = $k;
						$html .= "				</table><br>\n";
						$html .= "</div>";
					}
					$html .= "				</td>\n";
					$html .= "		</tr>\n";
					//
				}
				$html .= "	</table><br>\n";		
			}
			
			if(sizeof($insumos) > 0)
			{
				$html .= "	<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";		
				$html .= "		<tr class=\"modulo_table_list_title\" height=\"17\">\n";
				$html .= "			<td width=\"12%\">CODIGO</td>\n";
				$html .= "			<td width=\"11%\">CANTIDAD</td>\n";
				$html .= "			<td width=\"30%\">DESCRIPCIÓN</b></td>\n";
				$html .= "			<td width=\"8%\">CUBIERTO</td>\n";
				$html .= "			<td width=\"1%\" ></td>\n";
				$html .= "			<td width=\"10%\">V. OBJETADO</td>\n";
				$html .= "			<td width=\"%\" colspan=\"2\">MOTIVO - OBSERVACION</td>\n";
				$html .= "			<td width=\"1%\"></td>\n";
				$html .= "		</tr>";
//print_r($insumos);
				foreach($insumos as $key => $detalle)
				{
					$marca = "";
					if($background == "#DDDDDD")
					{
						$estilo='modulo_list_oscuro'; $background = "#CCCCCC";
					}
					else
					{
					 	$estilo='modulo_list_claro'; 	$background = "#DDDDDD";
					}
					
					if(strlen($detalle['descripcion']) > 48)
					{
						$marca = " title =\"".$detalle['descripcion']."\" ";
						$detalle['descripcion'] = substr($detalle['descripcion'],0,48)."...";
					}
					
					$valoraceptado = 0;
					if($detalle['glosa_detalle_inventario_id'])
						$valoraceptado = $gld->ObtenerCargoConNC($datos['glosa_id'],$detalle['codigo_producto'],$datos['numerodecuenta'],'1');

					$selec = "";
					$dsbl1 = "";
					$dsbl2 = "disabled=\"true\"";
					
					if($dsbl != "")
						$dsbl1 = $dsbl2 = $dsbl;
					else if($detalle['glosa_detalle_inventario_id'])
					{
						$dsbl2 = "readonly";$selec = "disabled=\"true\"";
					}	
					$html .= "		<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
					$html .= "			<td align=\"center\" >".$key."</td>\n";
					$html .= "			<td align=\"center\" >".$detalle['cantidad']."</td>\n";
					$html .= "			<td align=\"justify\" class=\"normal_10AN\" >".$detalle['descripcion']."</td>\n";
					$html .= "			<td align=\"right\" >$".formatoValor($detalle['valor_cubierto'])."\n";
					$html .= "				<input type=\"hidden\" id=\"vlr_insumo_$key\" value=\"".($detalle['valor_cubierto'] - $valoraceptado)."\" >\n";
					$html .= "				<input type=\"hidden\" id=\"vcp_insumo_$key\" value=\"".$detalle['valor_cargo']."\">\n";
					$html .= "			</td>\n";
					$html .= "			<td>\n";
					$html .= "				<img src=\"".GetThemePath()."/images/siguiente.png\" style=\"cursor:pointer;\" border=\"0\" onclick=\"CambiarValor('insumo_$key')\">\n";
					$html .= "			</td>\n";
					$html .= "			<td>\n";
					$html .= "				$<input type=\"text\" id=\"inp_insumo_$key\" $dsbl2 name=\"insumos[$key][valor_glosa]\" class=\"input-text\" style=\"width:90%\" onkeypress=\"return acceptNum(event)\" value=\"".$detalle['valor_glosa']."\"\>\n";
					$html .= "			</td>\n";
/*					$html .= "			<td class=\"normal_10AN\">\n";
					$html .= "				<select $selec id=\"sel_insumo_$key\" name=\"insumos[$key][motivo_glosa]\" class=\"select\" disabled>\n";
					$html .= "					<option value=\"\">-------SELECCIONAR-------</option>\n";			
					$html .= "					".$html1;
					$html .= "				</select>\n";
					$html .= "			</td>\n";*/
					$disableb ="";
					if($detalle['glosa_detalle_inventario_id'])
					{$disableb = "disabled";}
					$html .= "			<td align=\"left\" class=\"modulo_list_claro\" colspan=\"1\">\n";
					$html .= "				<select name=\"concepto_general\" id=\"concepto_general\" class=\"select\" onchange=\"GetConceptosInsumos(this.value,document.cuenta,'".$detalle['transaccion']."');\" $disableb>\n";
					$html .= "					<option value='V' >-------SELECCIONAR-------</option>\n";			
						for($i=0; $i<sizeof($ConceptosGenerales); )
						{
							//($this->Concepto == $ConceptosGenerales[$i]['codigo_concepto_general'])? $sel = "selected": $sel = "";
							$k = $i;
							while($ConceptosGenerales[$i]['codigo_concepto_general'] == $ConceptosGenerales[$k]['codigo_concepto_general'])
							{
								$k++;
							}
							$i = $k;
							$sl = "";
							if(trim($detalle['codigo_concepto_general']) == trim($ConceptosGenerales[$i-1]['codigo_concepto_general'])) $sl= "selected";
							$html .= "			<option value='".$ConceptosGenerales[$i-1]['codigo_concepto_general']."' $sl >".$ConceptosGenerales[$i-1]['descripcion_concepto_general']."</option>\n";
						}
					$html .= "				</select>\n";
					if($detalle['motivo_glosa_descripcion'])
					$html .= "<img src=\"".GetThemePath()."/images/pcargos.png\" style=\"cursor:pointer;\" border=\"0\" title=\"MOTIVO GLOSA: ".$detalle['motivo_glosa_descripcion']."\" >\n";

					$html .= "			</td>\n";
					$html .= "			<td>\n";
					$html .= "				<img src=\"".GetThemePath()."/images/auditoria.png\" style=\"cursor:pointer;\" border=\"0\" onclick=\"ObtenerObservacion('INGRESAR OBSERVACION PARA EL INSUMO: $key','insumo_$key')\">\n";
					$html .= "			</td>\n";
					$html .= "			<td>\n";
					if($detalle['glosa_detalle_inventario_id'])
					{
						$url = $action['modificar'].URLRequest(array("tipom"=>"I","glosa_detalle_inventario_id"=>$detalle['glosa_detalle_inventario_id']));
						if($detalle['sw_estado'] == "1")
						{
							$html .= "				<a href=\"".$url."\" title=\"EDITAR CARGO\">\n";
							$html .= "					<img src=\"".GetThemePath()."/images/edita.png\" border=\"0\">\n";
							$html .= "				</a>\n";
						}
					}
					else
					{
						$html .= "				<input type=\"checkbox\" value=\"1\" $dsbl1 id=\"chk_insumo_$key\" name=\"insumos[$key][checbox]\" onclick=\"CambiarEstadoCheck(this.checked,'insumo_$key')\">\n";
						if($valoraceptado > 0)
						{
							$inf = "ESTE INSUMO Y/O MEDICAMENTO POSEE UNA \n NOTA CREDITO POR $".formatoValor($valoraceptado);
							$html .= "				<img src=\"".GetThemePath()."/images/informacion.png\" border=\"0\" title=\"".$inf."\">\n";
						}
					}
					$html .= "				</td>\n";
					$html .= "			</tr>\n";
					//
					$html .= "		<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
					$html .= "				<td style=\"text-align:right;text-indent:8pt\" colspan=\"5\"><b>CONCEPTO GENERAL / ESPECIFICO</b></td>\n";
					if($detalle['glosa_detalle_inventario_id'])
					{
						$html .= "				<td align=\"center\" class=\"modulo_list_claro\" colspan=\"4\">\n";
						$html .= "				<label class=\"label_mark\"><B>".$detalle['descripcion_concepto_general']." / ".$detalle['descripcion_concepto_especifico']."</b></label></td>\n";
					}
					else
					{
						$html .= "			<td style=\"text-align:left;text-indent:8pt\" colspan=\"4\">\n";
						$html .= "			<div id=\"insumos_".$detalle['transaccion']."\"></div></td>\n";
					}
/*					else
					{
					$html .= "				<td style=\"text-align:right;text-indent:8pt\" colspan=\"6\"><b>CONCEPTO GENERAL / ESPECIFICO</b></td>\n";
					$html .= "				<td align=\"right\" class=\"modulo_list_claro\" colspan=\"1\">\n";
					$html .= "					<select name=\"concepto_general\" id=\"concepto_general\" class=\"select\" onchange=\"GetConceptosInsumos(this.value,document.cuenta,'".$detalle['transaccion']."');\">\n";
					$html .= "						<option value='V' >-------SELECCIONAR-------</option>\n";			
						for($i=0; $i<sizeof($ConceptosGenerales); )
						{
							//($this->Concepto == $ConceptosGenerales[$i]['codigo_concepto_general'])? $sel = "selected": $sel = "";
							$k = $i;
							while($ConceptosGenerales[$i]['codigo_concepto_general'] == $ConceptosGenerales[$k]['codigo_concepto_general'])
							{
								$k++;
							}
							$i = $k;
							$html .= "					<option value='".$ConceptosGenerales[$i-1]['codigo_concepto_general']."' $sl >".$ConceptosGenerales[$i-1]['descripcion_concepto_general']."</option>\n";
						}
					$html .= "					</select>\n";
					$html .= "				</td>\n";
					}*/
					$html .= "			</tr>\n";
		
					$html .= "			<tr class=\"".$estilo."\">\n";
					$html .= "				<td style=\"text-align:left;text-indent:8pt\" colspan=\"9\">\n";
					for($i=0; $i<sizeof($ConceptosGenerales);)
					{
						$html .= "<div id='conceptoinsumos_".$ConceptosGenerales[$i][codigo_concepto_general]."_".$detalle['transaccion']."' style=\"display:none\">";
						$html .= "				<table align=\"center\" cellpading=\"0\"  width=\"60%\" border=\"0\" class=\"modulo_table_list\">\n";
						$html .= "					<tr class=\"modulo_table_list_title\">\n";
						$html .= "						<td style=\"text-align:left;text-indent:8pt\"><b>SEL</b>\n";
						$html .= "						</td>\n";
						$html .= "						<td style=\"text-align:left;text-indent:8pt\"><b>ID</b>\n";
						$html .= "						</td>\n";
						$html .= "						<td style=\"text-align:left;text-indent:8pt\"><b>DESCRIP.</b>\n";
						$html .= "						</td>\n";
						$html .= "					</tr>\n";
						$k = $i;
						while ($ConceptosGenerales[$i][codigo_concepto_general] == $ConceptosGenerales[$k][codigo_concepto_general])
						{
							$html .= "				<tr class=\"modulo_table_list\">\n";
							$html .= "					<td style=\"text-align:left;text-indent:8pt\"><input type=\"radio\" name=\"insumos[".$key."][conceptos]\" value=\"".$ConceptosGenerales[$k][codigo_concepto_general]."||//".$ConceptosGenerales[$k][codigo_concepto_especifico]."\" onclick=\"xajax_AsignarConceptos('I','".$detalle['transaccion']."','".$ConceptosGenerales[$k][descripcion_concepto_general]."','".$ConceptosGenerales[$k][descripcion_concepto_especifico]."','".$ConceptosGenerales[$i][codigo_concepto_general]."');\"\n";
							$html .= "					</td>\n";
							$html .= "					<td style=\"text-align:left;text-indent:8pt\"><b>".$ConceptosGenerales[$k][codigo_concepto_especifico]."</b>\n";
							$html .= "					</td>\n";
							$html .= "					<td style=\"text-align:left;text-indent:8pt\"><b>".$ConceptosGenerales[$k][descripcion_concepto_especifico]."</b>\n";
							$html .= "					</td>\n";
							$html .= "				</tr>\n";
							$k++;
						}
						$i = $k;
						$html .= "				</table><br>\n";
						$html .= "</div>";
					}
					$html .= "				</td>\n";
					$html .= "		</tr>\n";
					//
				}
				$html .= "		</table><br>\n";		
			}
			
			if($responder)
			{
				$html .= "	<table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";		
				$html .= "		<tr class=\"formulacion_table_list\" height=\"17\">\n";
				$html .= "			<td width=\"1%\">\n";
				$html .= "				<input type=\"checkbox\" name=\"sw_responder\" value=\"1\">\n";
				$html .= "			</td>\n";
				$html .= "			<td>CREAR LA RESPUESTA DE GLOSA AL ACEPTAR LA GLOSA SOBRE LA CUENTA O LOS CARGOS Y/O INSUMOS</td>\n";
				$html .= "		</tr>\n";
				$html .= "	</table>\n";
			}
			
			$html .= "		<table width=\"100%\" align=\"center\">\n";
			$html .= "			<tr>\n";
			$html .= "				<td align=\"center\">\n";			
			$html .= "					<input type=\"button\" class=\"input-submit\" value=\"Aceptar Glosa Cuenta\" name=\"glcuenta\" onclick=\"document.cuenta.submit()\">\n";
			$html .= "				</td>\n";
			$html .= "				<td align=\"center\">\n";
			$html .= "					<div id=\"aceptarCargos\" style=\"display:$display\">\n";
			$html .= "						<input type=\"submit\" class=\"input-submit\" value=\"Aceptar Glosa Detalle\" name=\"detalle\">\n";
			$html .= "					</div>\n";
			$html .= "				</td>\n";
			if($cuenta[$datos['numerodecuenta']]['glosa_detalle_cuenta_id'])
			{
				$html .= "				<td align=\"center\">\n";
				$html .= "					<input type=\"button\" class=\"input-submit\" value=\"Anular Glosa Cuenta\" onclick=\"IniciarAnulacion();MostrarSpan('ContenedorI')\">\n";
				$html .= "				</td>\n";
				if($responder)
				{
					$html .= "				<td align=\"center\">\n";
					$html .= "					<input type=\"button\" class=\"input-submit\" value=\"Responder\" onclick=\"document.responder.submit()\">\n";
					$html .= "				</td>\n";
				}
			}
			$html .= "				<td align=\"center\">\n";
			$html .= "					<input type=\"button\" class=\"input-submit\" value=\"Volver\" onclick=\"Volver(document.cargos_insumos)\">\n";
			$html .= "				</td>\n";
			$html .= "			</tr>\n";
			$html .= "		</table>\n";
			$html .= "	</fieldset>\n";
			$html .= "</form>\n";
			
			$html .= "<form name=\"responder\" action=\"".$action['responder']."\" method=\"post\"></form>\n";
			$html .= "<form name=\"recargar\" action=\"".$action['actualizar']."\" method=\"post\"></form>\n";
			$html .= "<div id='ContenedorObservacion' class='d2Container' style=\"display:none;z-index:3\">\n";
			$html .= "	<div id='tituloObservacon' class='draggable' style=\"	text-transform: uppercase;text-align:center\">grupo de clasificación</div>\n";
			$html .= "	<div id='cerrarObservacon' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpanObservacion('ContenedorObservacion')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
			$html .= "	<div id='ContenidoObservacon' class='d2Content' style=\"background:#EFEFEF\">\n";
			$html .= "		<form name=\"observaciones\" action=\"javascript:IngresarObservacion(document.observaciones,'d2Container')\" method=\"post\">\n";
			$html .= "			<table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "				<tr class=\"modulo_table_list_title\" >\n";
			$html .= "					<td>OBSERVACION</td>\n";
			$html .= "				</tr>\n";
			$html .= "				<tr>\n";
			$html .= "					<td align=\"center\">\n";
			$html .= "						<textarea id=\"observacion_i\" name=\"observacion\" style=\"width:100%\" class=\"textarea\" rows=\"3\"></textarea>\n";
			$html .= "					</td>\n";
			$html .= "				</tr>\n";
			$html .= "			</table><br>\n";
			$html .= "			<center>\n";
			$html .= "				<input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">&nbsp;&nbsp;&nbsp;&nbsp;\n";
			$html .= "				<input type=\"button\" class=\"input-submit\" value=\"Cancelar\" onclick=\"OcultarSpanObservacion('ContenedorObservacion')\">\n";
			$html .= "			</center><br>\n";
			$html .= "		</form>\n";
			$html .= "	</div>\n";
			$html .= "</div>\n";
			return $html;
		}
		/******************************************************************************
		* 
		* @return array datos de las cuentas
		********************************************************************************/
		function FormaModificarCargosInsumos($datos,$action)
		{
			IncludeClass('ClaseUtil');
			IncludeClass('GlosaDetalle','','app','Glosas');
			IncludeClass('Glosas','','app','Glosas');
			$gl = new Glosas();
			$gld = new GlosaDetalle();
			$cuenta = $gld->ObtenerInformacionDetalleCuentas($datos['datos_glosa']);
			$motivos = $gld->ObtenerMotivosGlosas();
			
			$valoraceptado = 0;
			$detalle = array();
			$id = "";
			switch($datos['tipom'])
			{
				case 'C':
					$detalle = $gld->ObtenerCargosCuentas($datos['datos_glosa'],$datos['glosa_detalle_cargo_id']);
					$key = key($detalle);
					$detalle = $detalle[$key];
					$id = $datos['glosa_detalle_cargo_id'];
					$valoraceptado = $gld->ObtenerCargoConNC($datos['datos_glosa']['glosa_id'],$detalle['transaccion'],$datos['datos_glosa']['numerodecuenta'],'1');
				break;
				case 'I':
					$detalle = $gld->ObtenerInsumosCuenta($datos['datos_glosa'],$datos['glosa_detalle_inventario_id']);
					$key = key($detalle);
					$detalle = $detalle[$key];
					$id = $datos['glosa_detalle_inventario_id'];
					$valoraceptado = $gld->ObtenerCargoConNC($datos['datos_glosa']['glosa_id'],$detalle['codigo_producto'],$datos['datos_glosa']['numerodecuenta'],'1');
				break;
			}
			
			$auditores = $gld->ObtenerAuditoresInternos();
			$estilo = "class=\"modulo_table_list_title\" style=\"text-align:left;text-indent: 6pt\" ";
			$html .= ClaseUtil::IsNumeric();
			$html .= ClaseUtil::CrearCapaVentana();
			$html .= "	<script>\n";
			$html .= "		function acceptNum(evt)\n";
			$html .= "		{\n";
			$html .= "			var nav4 = window.Event ? true : false;\n";
			$html .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "			return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$html .= "		}\n";	
			$html .= "		function pasarValor(objeto){\n";
			$html .= "			objeto.valor_cubierto.value = objeto.valor.value;\n";
			$html .= "		}\n";
			
			$html .= "	function EvaluarDatosCuenta(frm)\n";
			$html .= "	{\n";
			$html .= "		cubierto = frm.valor.value; \n";
			$html .= "		if(!IsNumeric(cubierto)) \n";
			$html .= "		{\n";
			$html .= "			CrearMensaje('Favor Digitar Un Valor Correcto o Mayor a Cero') \n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		else if((valor*1) > (cubierto*1)) \n";
			$html .= "		{\n";
			$html .= "			CrearMensaje('El valor de la glosa, no debe ser mayor a '+cubierto) \n";
			$html .= "			return;\n";
			$html .= "		}\n";
/*
			$html .= "		else if(frm.motivos.value == '')\n";
			$html .= "		{\n";
			$html .= "			CrearMensaje('No se ha seleccionado Motivo de Glosa') \n";
			$html .= "			return;\n";
			$html .= "		}\n";
*/
			$html .= "		//flag = false;\n";
			$html .= "		//nombre = '';\n";
			$html .= "		for(i=0; i<frm.length; i++)\n";
			$html .= "		{\n";
			$html .= "			switch(frm[i].type)\n";
			$html .= "			{\n";
			$html .= "				case 'radio': \n";
			$html .= "					try \n";
			$html .= "					{ \n";
			$html .= "						if(frm[i].checked) \n";
			$html .= "						{\n";
			$html .= "							//flag = true;\n";
			$html .= "							//titulo = 'INFORMACION';\n";
			$html .= "							//nombre = frm[i].id; \n";
			$html .= "							//valor = document.getElementById('concepto_especifico').value; \n";
			$html .= "							valor = frm[i].value; \n";
			$html .= "						}\n";
			$html .= "					}catch(error){}\n";
			$html .= "				break;\n";
			$html .= "			}\n";
			$html .= "		}\n";
			
			$html .= "		xajax_ActualizarDetalleGlosa('-1',frm.observacion.value,frm.valor_cubierto.value,frm.auditor.value,'".$id."','".$datos['datos_glosa']['glosa_id']."','".$datos['tipom']."',valor);\n";
			//$html .= "		xajax_ActualizarDetalleGlosa(frm.motivos.value,frm.observacion.value,frm.valor_cubierto.value,frm.auditor.value,'".$id."','".$datos['datos_glosa']['glosa_id']."','".$datos['tipom']."',valor);\n";
			$html .= "	}\n";
			$html .= "	function EvaluarDatosAnulacion(frm)\n";
			$html .= "	{\n";
			$html .= "		if(frm.observacion.value == '')\n";
			$html .= "		{\n";
			$html .= "			xGetElementById('error').innerHTML = 'SE DEBE INGRESAR EL MOTIVO POR EL CUAL SE ESTA ANULADO LA GLOSA SOBRE EL CARGO O INSUMO'; \n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		xajax_AnularDetalleGlosa(frm.observacion.value,'".$detalle['glosa_detalle_cuenta_id']."','".$id."','".$datos['datos_glosa']['glosa_id']."','".$datos['tipom']."')\n";
			$html .= "	}\n";
			$html .= "	function IniciarAnulacion()\n";
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
			$html .= "function GetConceptos(valor,vector){\n";
			$html .= " var vect;\n";
			$html .= " for(i=0; i<vector.length; i++){\n";
			$html .= "	switch(vector[i].type)\n";
			$html .= "	{\n";
			$html .= "		case 'radio':  \n";
			$html .= "			vect = vector[i].value.split('||//')[0];\n";
			$html .= "			document.getElementById('concepto'+vect).style.display = 'none';\n";
			$html .= "		break;\n";
			$html .= "	}\n";
			$html .= " \n";
			$html .= " }\n";
			$html .= "identificador = 'concepto'+valor;\n";
			$html .= "if(document.getElementById(identificador).style.display == 'none'){\n";
			$html .= "  document.getElementById(identificador).style.display = 'block';\n";
			$html .= "} \n";
			$html .= "else{ \n";
			$html .= "   document.getElementById(identificador).style.display = 'none';\n";
			//$html .= "   document.getElementById(identificador).style.display = 'none';\n";
			$html .= " } ;\n";
			$html .="}\n";
			
			$html .= "	</script>\n";
			
			$html .= "<div id='ContenedorI' class='d2Container' style=\"display:none;z-index:4\">\n";
			$html .= "	<div id='tituloI' class='draggable' style=\"	text-transform: uppercase;text-align:center\">ANULAR GLOSA CARGO O INSUMO</div>\n";
			$html .= "	<div id='cerrarI' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('ContenedorI')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div>\n";
			$html .= "	<div id='ContenidoI' class='d2Content' style=\"background:#EFEFEF\"><br><br>\n";
			$html .= "		<form name=\"anulacion\" action=\"javascript:EvaluarDatosAnulacion(document.anulacion)\" method=\"post\">\n";
			$html .= "			<center>\n";
			$html .= "				<div id=\"error\" class=\"label_error\"></div>\n";
			$html .= "			</center>\n";
			$html .= "			<table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "				<tr class=\"modulo_table_list_title\" >\n";
			$html .= "					<td>MOTIVO DE LA ANULACIÓN DE LA GLOSA</td>\n";
			$html .= "				</tr>\n";
			$html .= "				<tr>\n";
			$html .= "					<td align=\"center\">\n";
			$html .= "						<textarea id=\"observacion\" name=\"observacion\" style=\"width:100%\" class=\"textarea\" rows=\"3\"></textarea>\n";
			$html .= "					</td>\n";
			$html .= "				</tr>\n";
			$html .= "			</table><br>\n";
			$html .= "			<center>\n";
			$html .= "				<input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">&nbsp;&nbsp;&nbsp;&nbsp;\n";
			$html .= "				<input type=\"button\" class=\"input-submit\" value=\"Cancelar\" onclick=\"OcultarSpan('ContenedorI')\">\n";
			$html .= "			</center><br>\n";
			$html .= "		</form>\n";
			$html .= "	</div>\n";
			$html .= "</div>\n";
			
			if($valoraceptado > 0)
			{
				$html .= "		<table align=\"center\" width=\"70%\">\n";
				$html .= "			<tr>\n";
				$html .= "				<td>\n";
				$html .= "					<fieldset><legend class=\"normal_10AN\">NOTA</legend>\n";
				$html .= "						<b class=\"field\">ESTE CARGO POSEE UNA O VARIAS NOTAS CREDITO PARCIALES, POR UN MONTO DE: $".formatoValor($this->Valor)."</b>\n";
				$html .= "					</fieldset>\n";
				$html .= "				</td>\n";
				$html .= "			</tr>\n";
				$html .= "		</table>\n";
			}
			$html .= "<center>\n";
			$html .= "	<div id=\"mensaje\" class=\"normal_10AN\"></div>\n";
			$html .= "</center><br>\n";
			$html .= "<table align=\"center\" cellpading=\"0\"  width=\"70%\" border=\"0\" class=\"modulo_table_list\">\n";
			$html .= "	<tr $estilo>\n";
			$html .= "		<td width=\"15%\">Nº FACTURA</td>\n";
			$html .= "		<td class=\"modulo_list_claro\" width=\"10%\">\n";
			$html .= "			".$datos['datos_glosa']['prefijo']." ".$datos['datos_glosa']['factura_fiscal']."\n";
			$html .= "		</td>\n";
			$html .= "		<td width=\"15%\">Nº CUENTA</td>\n";
			$html .= "		<td class=\"modulo_list_claro\"  align=\"right\" width=\"10%\">\n";
			$html .= "			".$datos['datos_glosa']['numerodecuenta']."\n";
			$html .= "		</td>\n";
			$html .= "		<td width=\"10%\">PLAN</td>\n";
			$html .= "		<td class=\"modulo_list_claro\" colspan=\"3\" width=\"40%\">\n";
			$html .= "			".$cuenta[$datos['datos_glosa']['numerodecuenta']]['plan_descripcion']."\n";
			$html .= "		</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr $estilo>\n";
			$html .= "			<td>Nº INGRESO</td>\n";
			$html .= "			<td class=\"modulo_list_claro\" align=\"right\">\n";
			$html .= "				".$cuenta[$datos['datos_glosa']['numerodecuenta']]['ingreso']."\n";
			$html .= "			</td>\n";
			$html .= "			<td width=\"15%\">PACIENTE</td>\n";
			$html .= "			<td class=\"modulo_list_claro\" colspan=\"2\" width=\"25%\">\n";
			$html .= "				".$cuenta[$datos['datos_glosa']['numerodecuenta']]['identificacion']." \n";
			$html .= "			</td>\n";
			$html .= "			<td class=\"modulo_list_claro\" colspan=\"3\" width=\"40%\">\n";
			$html .= "				".$cuenta[$datos['datos_glosa']['numerodecuenta']]['paciente']."\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr $estilo>\n";
			$html .= "			<td >COPAGO</td>\n";
			$html .= "			<td class=\"modulo_list_claro\" align=\"right\">\n";
			$html .= "				".formatoValor($cuenta[$datos['datos_glosa']['numerodecuenta']]['valor_cuota_paciente'])."\n";
			$html .= "			</td>\n";
			$html .= "			<td colspan=\"2\">CUOTA MODERADORA</td>\n";
			$html .= "			<td class=\"modulo_list_claro\" align=\"right\" >\n";
			$html .= "				".formatoValor($cuenta[$datos['datos_glosa']['numerodecuenta']]['valor_cuota_moderadora'])."\n";
			$html .= "			</td>\n";
			$html .= "			<td colspan=\"2\" >TOTAL EMPRESA</td>\n";
			$html .= "			<td class=\"modulo_list_claro\" align=\"right\" width=\"15%\">\n";
			$html .= "				".formatoValor($cuenta[$datos['datos_glosa']['numerodecuenta']]['valor_total_empresa'])."\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table><br>\n";
			
			$html .= "<form name=\"modificarglosa\" action=\"javascript:EvaluarDatosCuenta(document.modificarglosa)\" method=\"post\">\n";			
			$html .= "	<table width=\"70%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "		<tr $estilo>\n";
			
			switch($datos['tipom'])
			{
				case 'C':
					$html .= "			<td width=\"25%\" >TRANSACCION</td>\n";
					$html .= "			<td class=\"modulo_list_claro\"  width=\"10%\" align=\"right\">".$detalle['transaccion']."</td>\n";
					$html .= "			<td width=\"20%\" >CARGO CUPS</td>\n";
					$html .= "			<td class=\"modulo_list_claro\"  width=\"15%\" align=\"right\">".$detalle['cargo_cups']."</td>\n";
					$html .= "			<td width=\"20%\" >TARIFARIO</td>\n";
					$html .= "			<td class=\"modulo_list_claro\"  width=\"20%\" align=\"right\">".$detalle['tarifario_id']."</td>\n";
				break;
				case 'I':
					$html .= "			<td width=\"25%\" >CODIGO PRODUCTO</td>\n";
					$html .= "			<td class=\"modulo_list_claro\"  width=\"25%\" colspan=\"2\">".$detalle['codigo_producto']."</td>\n";
					$html .= "			<td width=\"25%\" >CANTIDAD</td>\n";
					$html .= "			<td class=\"modulo_list_claro\"  width=\"25%\" colspan=\"2\" >".$detalle['cantidad']."</td>\n";
				break;
			}
			
			$html .= "		</tr>\n";
			$html .= "		<tr $estilo>\n";
			$html .= "			<td width=\"25%\">DESCRIPCION</td>\n";
			$html .= "			<td class=\"modulo_list_claro\"  width=\"75%\" colspan=\"5\" align=\"justify\" >".$detalle['descripcion']."</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td width=\"50%\" colspan=\"3\">VALOR CUBIERTO</td>\n";
			$html .= "			<td width=\"50%\" colspan=\"3\">VALOR OBJETADO</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td class=\"modulo_list_claro\" colspan=\"3\" align=\"center\">\n";
			$html .= "				".formatoValor($detalle['valor_cargo'])."\n";
			$html .= "				<input type=\"hidden\" id=\"valor\" value=\"".($detalle['valor_cubierto'] - $valoraceptado)."\" >\n";
			$html .= "			</td>\n";
			$html .= "			<td class=\"modulo_list_claro\" colspan=\"3\" align=\"center\">\n";
			$html .= "				<img style=\"cursor:pointer\" src=\"".GetThemePath()."/images/siguiente.png\" alt=\"PASAR EL VALOR CUBIERTO\" onclick=\"pasarValor(document.modificarglosa)\">\n";
			$html .= "				<b>$</b><input type=\"text\" name=\"valor_cubierto\" class=\"input-text\" size=\"30\" value=\"".$detalle['valor_glosa']."\" onKeypress=\"return  acceptNum(event)\">\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			
			$html .= "		<tr $estilo >\n";
			$html .= "			<td width=\"25%\">\n";
			$html .= "				<b>AUDITOR</b>\n";
			$html .= "			</td>\n";
			$html .= "			<td class=\"modulo_list_claro\" width=\"75%\" colspan=\"5\">\n";
			$html .= "				<select name=\"auditor\" class=\"select\">\n";
			$html .= "					<option value = ''>-------SELECCIONAR-------</option>\n";			
			
			$sel = "";
			foreach($auditores as $key => $audi)
			{
				($detalle['auditor_id'] == $audi['usuario_id'])? $sel = "selected": $sel = "";
				$html .= "						<option value='".$audi['usuario_id']."' $sel>".$audi['nombre']."</option>\n";
			}
			
			$html .= "				</select>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";			
/*			$html .= "		<tr $estilo >\n";
			$html .= "			<td width=\"25%\">MOTIVOS</td>\n";
			$html .= "			<td class=\"modulo_list_claro\"  width=\"75%\" colspan=\"8\" >\n";
			$html .= "				<select name=\"motivos\" class=\"select\">\n";
			$html .= "					<option value=''>-------SELECCIONAR-------</option>\n";			
			$sel = "";
			foreach($motivos as $key => $dtl)
			{
				($detalle['motivo_glosa_id'] == $dtl['motivo_glosa_id'])? $sel= "selected":$sel = "";;
				$html .= "					<option value='".$dtl['motivo_glosa_id']."' title=\"".$dtl['motivo_glosa_descripcion']."\" $sel >".$dtl['motivo_glosa_descripcion']."</option>\n";
			}
			
			$html .= "			</select></td>\n";
			$html .= "		</tr>\n";*/
			$html .= "		<tr class=\"modulo_table_list_title\" >\n";
			$html .= "			<td colspan=\"6\" width=\"100%\">\n";
			$html .= "				<b>OBSERVACIÓN DEL MOTIVO</b>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_table_list_title\" >\n";
			$html .= "			<td colspan=\"6\" width=\"100%\">";
			$html .= "				<textarea class=\"textarea\" name=\"observacion\" cols=\"123\" rows=\"3\">".$detalle['observacion']."</textarea>";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
//***********************************
			$ConceptosGenerales = $gl->ObtenerConceptosGenerales();
			
			$html .= "			<tr class=\"modulo_table_list_title\">\n";
			$html .= "				<td style=\"text-align:left;text-indent:8pt\" colspan=\"2\"><b>CONCEPTO GENERAL</b></td>\n";
			$html .= "				<td align=\"left\" class=\"modulo_list_claro\" colspan=\"4\">\n";
			$html .= "					<select name=\"concepto_general\" id=\"concepto_general\" class=\"select\" onchange=\"GetConceptos(this.value,document.modificarglosa);\">\n";
			$html .= "						<option value='V' selected>-------SELECCIONAR-------</option>\n";			

			for($i=0; $i<sizeof($ConceptosGenerales); )
			{
				$k = $i;
				while($ConceptosGenerales[$i]['codigo_concepto_general'] == $ConceptosGenerales[$k]['codigo_concepto_general'])
				{
					$k++;
				}
				$i = $k;
 				(trim($detalle['codigo_concepto_general']) == trim($ConceptosGenerales[$i-1]['codigo_concepto_general']))? $sel = "selected": $sel = "";
				$html .= "					<option value='".$ConceptosGenerales[$i-1]['codigo_concepto_general']."' $sel>".$ConceptosGenerales[$i-1]['descripcion_concepto_general']."</option>\n";
			}
			
			$html .= "					</select>\n";
			$html .= "				</td>\n";
			$html .= "			</tr>\n";

			$html .= "			<tr class=\"modulo_table_list\">\n";
			$html .= "				<td style=\"text-align:left;text-indent:8pt\" colspan=\"6\">\n";
			for($i=0; $i<sizeof($ConceptosGenerales);)
			{
			$none = 'none';
			if($ConceptosGenerales[$i][codigo_concepto_general] == $detalle['codigo_concepto_general'])
			{$none = 'block';}
				$html .= "<div id='concepto".$ConceptosGenerales[$i][codigo_concepto_general]."' style=\"display:$none\">";
				$html .= "				<table align=\"center\" cellpading=\"0\"  width=\"60%\" border=\"0\" class=\"modulo_table_list\">\n";
				$html .= "					<tr class=\"modulo_table_list_title\">\n";
				$html .= "						<td style=\"text-align:left;text-indent:8pt\"><b>SEL</b>\n";
				$html .= "						</td>\n";
				$html .= "						<td style=\"text-align:left;text-indent:8pt\"><b>ID</b>\n";
				$html .= "						</td>\n";
				$html .= "						<td style=\"text-align:left;text-indent:8pt\"><b>DESCRIP.</b>\n";
				$html .= "						</td>\n";
				$html .= "					</tr>\n";
				$k = $i;
				while ($ConceptosGenerales[$i][codigo_concepto_general] == $ConceptosGenerales[$k][codigo_concepto_general])
				{
					$checked = "";
					if(trim($detalle['codigo_concepto_especifico']) == trim($ConceptosGenerales[$k][codigo_concepto_especifico])
						AND $ConceptosGenerales[$k][codigo_concepto_general] == $detalle['codigo_concepto_general'])
					{$checked = "checked"; }
					$html .= "				<tr class=\"modulo_table_list\">\n";
					$html .= "					<td style=\"text-align:left;text-indent:8pt\"><input type=\"radio\" id=\"concepto_especifico\" name=\"concepto_especifico\" value=\"".$ConceptosGenerales[$k][codigo_concepto_general]."||//".$ConceptosGenerales[$k][codigo_concepto_especifico]."\" $checked>\n";
					$html .= "					</td>\n";
					$html .= "					<td style=\"text-align:left;text-indent:8pt\"><b>".$ConceptosGenerales[$k][codigo_concepto_especifico]."</b>\n";
					$html .= "					</td>\n";
					$html .= "					<td style=\"text-align:left;text-indent:8pt\"><b>".$ConceptosGenerales[$k][descripcion_concepto_especifico]."</b>\n";
					$html .= "					</td>\n";
					$html .= "				</tr>\n";
					$k++;
				}
				$i = $k;
				$html .= "				</table><br>\n";
				$html .= "</div>";
			}
			$html .= "				</td>\n";
			$html .= "			</tr>\n";
//***********************************

			$html .= "	</table><br>\n";
			$html .= "	<table align=\"center\" width=\"60%\">\n";
			$html .= "		<tr>\n";
			$html .= "				<td align=\"center\">\n";
			$html .= "				<input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">\n";
			$html .= "				</td>\n";
			$html .= "			</form>\n";
			$html .= "			<form name=\"anular\" action=\"javascript:IniciarAnulacion();MostrarSpan('ContenedorI')\" method=\"post\">\n";
			$html .= "				<td align=\"center\">\n";
			$html .= "					<input type=\"submit\" class=\"input-submit\" value=\"Anular\">\n";
			$html .= "				</td>\n";
			$html .= "			</form>\n";
			$html .= "			<form name=\"cancelar\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "				<td align=\"center\">\n";
			$html .= "					<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$html .= "				</td>\n";
			$html .= "			</form>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			
			return $html;
		}
	}
?>