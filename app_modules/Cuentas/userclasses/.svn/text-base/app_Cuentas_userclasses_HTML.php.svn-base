<?php
	/*********************************************************************************************
	* $Id: app_Cuentas_userclasses_HTML.php,v 1.12 2011/07/13 13:30:27 hugo Exp $
	*
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* $Revision: 1.12 $
	*
	* @autor Hugo F. Manrique
	***********************************************************************************************/
	IncludeClass('ClaseHTML');
	class app_Cuentas_userclasses_HTML extends app_Cuentas_user
	{
		function app_Cuentas_userclasses_HTML(){}
		/********************************************************************************** 
		* 
		* @return boolean
		***********************************************************************************/
		function FormaMain()
		{
			SessionDelVar("CensoEmpresaId");
			$this->Main();
			$url[0] = 'app';
			$url[1] = 'Cuentas';
			$url[2] = 'user';
			$url[3] = 'FormaMenu';
			$url[4] = 'Cuenta';
			$arreglo[0] = 'EMPRESA';
			$arreglo[1] = 'DOCUMENTOS';
			SessionDelVar("Cuenta_documento_id");
			$this->salida = gui_theme_menu_acceso('CUENTAS',$arreglo,$this->documentos,$url,ModuloGetURL('system','Menu'));
			return true;
		}
		/********************************************************************************** 
		* 
		* @return boolean
		***********************************************************************************/
		function FormaBuscarCuentas()
		{
			$this->BuscarCuentas();

			SessionDelVar("sw_cuentas");
      SessionDelVar("ValidacionExistencias");
			$this->salida .= ThemeAbrirTabla("BUSCAR CUENTAS");
			$this->salida .= "<script>\n";
			$this->salida .= "	function acceptNum(evt)\n";
			$this->salida .= "  {\n";
			$this->salida .= "    var nav4 = window.Event ? true : false;\n";
			$this->salida .= "    var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "    return (key <= 13 || (key >= 48 && key <= 57) || key == 45);\n";
			$this->salida .= "  }\n";
			$this->salida .= "	function LimpiarCampos(frm)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		for(i=0; i<frm.length; i++)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			switch(frm[i].type)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				case 'text': frm[i].value = ''; break;\n";
			$this->salida .= "				case 'select-one': frm[i].selectedIndex = 0; break;\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function mOvr(src,clrOver)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		src.style.background = clrOver;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function mOut(src,clrIn)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		src.style.background = clrIn;\n";
			$this->salida .= "	}\n";
			$this->salida .= "</script>\n";
			$this->salida .= "<form name=\"formabuscar\" action=\"".$this->action['buscar']."\" method=\"post\">\n";
			$this->salida .= "	<center>\n";
			$this->salida .= "		<fieldset style=\"width:80%\" class=\"fieldset\"><legend class=\"label\">CRITERIOS DE BUSQUEDA</legend>\n";
			$this->salida .= "    	<table border=\"0\" width=\"100%\" align=\"center\">\n";
			$this->salida .= "      	<tr>\n";
			$this->salida .= "        	<td class=\"normal_10AN\">No. CUENTA: </td>\n";
			$this->salida .= "          <td>\n";
			$this->salida .= "          	<input type=\"text\" class=\"input-text\" onkeypress=\"return acceptNum(event)\" name=\"buscador[Cuenta]\" maxlength=\"32\" value=\"".$this->rqst['Cuenta']."\">\n";
			$this->salida .= "          </td>\n";
			$this->salida .= "          <td class=\"normal_10AN\">No. INGRESO: </td>\n";
			$this->salida .= "          <td>\n";
			$this->salida .= "           	<input type=\"text\" class=\"input-text\" onkeypress=\"return acceptNum(event)\" name=\"buscador[Ingreso]\" maxlength=\"32\" value=\"".$this->rqst['Ingreso']."\">\n";
			$this->salida .= "          </td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "        <tr>\n";
			$this->salida .= "        	<td class=\"normal_10AN\" width=\"18%\">TIPO DOCUMENTO: </td>\n";
			$this->salida .= "          <td width=\"32%\">\n";
			$this->salida .= "          	<select name=\"buscador[TipoDocumento]\" class=\"select\">\n";
			$this->salida .= "            	<option value=\"\">-------SELECCIONE-------</option>";
			$slt = "";
			foreach($this->Id as $key=> $ids)
			{
				($this->rqst['TipoDocumento'] == $ids['tipo_id_paciente'])? $slt= "selected":$slt = "";
				$this->salida .= "            	<option value=\"".$ids['tipo_id_paciente']."\" $slt>".$ids['descripcion']."</option>";
			}
			$this->salida .= "            </select>\n";
			$this->salida .= "          </td>\n";
			$this->salida .= "          <td width=\"18%\" class=\"normal_10AN\">DOCUMENTO: </td>\n";
			$this->salida .= "          <td>\n";
			$this->salida .= "          	<input type=\"text\" class=\"input-text\" name=\"buscador[Documento]\" maxlength=\"32\" value=\"".$this->rqst['Documento']."\">\n";
			$this->salida .= "          </td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "        <tr>\n";
			$this->salida .= "        	<td class=\"normal_10AN\">NOMBRES:</td>\n";
			$this->salida .= "          <td>\n";
			$this->salida .= "          	<input type=\"text\" class=\"input-text\" name=\"buscador[Nombres]\" style=\"width:94%\" maxlength=\"64\" value=\"".$this->rqst['Nombres']."\">\n";
			$this->salida .= "          </td>\n";
			$this->salida .= "          <td class=\"normal_10AN\">APELLIDOS:</td>\n";
			$this->salida .= "          <td>\n";
			$this->salida .= "          	<input type=\"text\" class=\"input-text\" name=\"buscador[Apellidos]\" style=\"width:94%\" maxlength=\"64\" value=\"".$this->rqst['Apellidos']."\">\n";
			$this->salida .= "          </td>\n";
			$this->salida .= "        </tr>\n";
			$this->salida .= "        <tr>\n";
			$this->salida .= "         	<td colspan = '4' align=\"center\" >\n";
			$this->salida .= "          	<table width=\"70%\">\n";
			$this->salida .= "             	<tr align=\"center\">\n";
			$this->salida .= "               	<td >\n";
			$this->salida .= "                 	<input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\">\n";
			$this->salida .= "                </td>\n";
			$this->salida .= "                <td>\n";
			$this->salida .= "                 	<input class=\"input-submit\" type=\"button\" onclick=\"LimpiarCampos(document.formabuscar)\" name=\"Limpiar\" value=\"Limpiar Campos\">\n";
			$this->salida .= "                </td>\n";
			$this->salida .= "            	</tr>\n";
			$this->salida .= "           	</table>\n";
			$this->salida .= "          </td>\n";
			$this->salida .= "        </tr>\n";
			$this->salida .= "    	</table>\n";
			$this->salida .= "		</fieldset>\n";
			$this->salida .= "	</center>\n";
			$this->salida .= "</form>\n";
			$this->salida .= "<div id=\"error\" style=\"text-align:center\" class=\"label_error\">\n";
			
			if(!empty($this->cuentas))
			{
				$this->salida .= "<br>\n";
				$this->salida .= "	<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "		<tr align=\"center\" class=\"modulo_table_list_title\">\n";
				$this->salida .= "			<td width=\"6%\" >CUENTA</td>\n";
				$this->salida .= "			<td width=\"32%\" >PACIENTE</td>\n";
				$this->salida .= "			<td width=\"30%\">PLAN CUENTA</td>\n";
				$this->salida .= "			<td width=\"8%\" title=\"FECHA HORA REGISTRO\">FECHA</td>\n";
				$this->salida .= "			<td width=\"8%\" title=\"VALOR NO CUBIRTO\">V. NO CUBIRTO</td>\n";
				$this->salida .= "			<td width=\"8%\" title=\"TOTAL CUENTA\">TOTAL</td>\n";
				$this->salida .= "			<td width=\"4%\" title=\"ESTADO\">EST.</td>\n";
				$this->salida .= "			<td width=\"4%\" colspan=\"2\">OPC.</td>\n";
				$this->salida .= "		</tr>\n";
				
				$est = "modulo_list_oscuro";
				$bac = "#CCCCCC";
				foreach($this->cuentas as $key => $cuentas)
				{
					($bac == "#CCCCCC")? $bac = "#DDDDDD": $bac = "#CCCCCC";
					($est == "modulo_list_oscuro")? $est = "modulo_list_claro": $est = "modulo_list_oscuro";
					$img = "pinactivo.png";
					$this->salida .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bac."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
					$this->salida .= "			<td class=\"normal_10AN\">".$cuentas['numerodecuenta']."</td>\n";
					$this->salida .= "			<td width=\"15%\">".$cuentas['tipo_id_paciente']."-".$cuentas['paciente_id']." <label class=\"label\">".$cuentas['nombre']."</label></td>\n";
					$this->salida .= "			<td >".$cuentas['plan_descripcion']."</td>\n";
					$this->salida .= "			<td align=\"center\"	title=\"FECHA HORA REGISTRO\">".$cuentas['fecha']."</td>\n";
					$this->salida .= "			<td align=\"right\"		title=\"VALOR NO CUBIERTO\" class=\"normal_10AN\">$".formatoValor($cuentas['valor_nocubierto'])."</td>\n";
					$this->salida .= "			<td align=\"right\"		title=\"TOTAL CUENTA\" class=\"normal_10AN\">$".formatoValor($cuentas['total_cuenta'])."</td>\n";
					$this->salida .= "			<td style=\"cursor:help\" title=\"".$cuentas['desc_estado']."\" align=\"center\">";
					
					if($cuentas['estado'] == '1') $img = "pactivo.png";
					$this->salida .= "				<img src=\"".GetThemePath()."/images/".$img."\" border=\"0\">\n";
					$this->salida .= "			</td>\n";					
					$this->salida .= "			<td align=\"center\">";
					$this->salida .= "				<a title=\"VER CUENTA\" href=\"".$this->action['ver_cn'].UrlRequest(array("Cuenta"=>$cuentas['numerodecuenta'],"sw_cuentas"=>"1"))."\">\n";
					$this->salida .= "					<img src=\"".GetThemePath()."/images/pconsultar.png\" border=\"0\">\n";
					$this->salida .= "				</a>\n";
					$this->salida .= "			</td>\n";

					$this->salida .= "		</tr>\n";
				}
				$this->salida .= "</table>\n";

				$Paginador = new ClaseHTML();
				$this->salida .= "      <br>\n";
				$this->salida .= "      ".$Paginador->ObtenerPaginado($this->conteo,$this->pagina,$this->action['pagina']);
			}
			else if(!empty($this->request['buscador']))
			{
				$this->salida .= "		LA BUSQUEDA NO ARROJO RESULTADOS";
			}
			
			$this->salida .= "</div>\n";
			
			$this->salida .= "<form name=\"volver\" action=\"".$this->action['volver']."\" method=\"post\">\n";
			$this->salida .= "	<table width=\"50%\" align=\"center\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td align=\"center\" id='lll'>\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "</form>\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/********************************************************************************** 
		* 
		* @return boolean
		***********************************************************************************/
		function FormaMostrarCuenta(&$obj,$numerocuenta,$mensaje)
		{
			$this->SetJavaScripts('PagosPaciente');
			$this->MostrarCuenta($numerocuenta);
			if(is_object($obj))
			{
				$obj->IncludeJS("TabPaneLayout");
				$obj->IncludeJS("TabPaneApi");
				$obj->IncludeJS("TabPane");
        $obj->IncludeJS("CrossBrowser");
			}
			else
			{
				$this->IncludeJS("TabPaneLayout");
				$this->IncludeJS("TabPaneApi");
				$this->IncludeJS("TabPane");
        $this->IncludeJS("CrossBrowser");
			}
			IncludeClass('CuentaHTML','','app','Cuentas');
			$cnth = new CuentaHTML();
			//Includes para adicionar cargos - insumos y/o medicamentos
			IncludeFile ('app_modules/Cuentas/RemoteXajax/datosbusquedaCargos.php');
			//$this->SetXajax(array("reqBuscarDatosCargos","reqSeleccionarCargo","reqEliminarDatosCargos","reqCambiarCargoPlan","reqCambiarAbonoPlan","reqCambiarCargoPlanTotalPage"));
			//$this->SetXajax(array("reqBuscarDatosCargos","reqSeleccionarCargo","reqEliminarDatosCargos","reqSeleccionarCargoIYM","reqBuscarDatosIyM","reqEliminarCargoIYM"));
			$this->SetXajax(array("reqBuscarDatosCargos","reqSeleccionarCargo","reqEliminarDatosCargos","InsertarCantidadCosto","reqSeleccionarCargoIYM","reqBuscarDatosIyM","reqEliminarCargoIYM","reqCambiarCargoPlan","reqCambiarAbonoPlan","reqCambiarCargoPlanTotalPage","InsertarCantidadCosto","SinExistencia"),null,"ISO-8859-1");
			$this->salida = ThemeAbrirTabla("CUENTA Nº ".$this->cuentas['numerodecuenta']." - ".$this->cuentas['nombre'].'  '.$this->cuentas[tipo_id_paciente].' '.$this->cuentas[paciente_id]);
			$this->salida .= "<center><label class=\"label_error\">$mensaje</label></center><br>\n";
			$this->salida .= "<table width=\"90%\" align=\"center\" class=\"modulo_table_list\" border=\"0\">\n";
			$this->salida .= "	<tr class=\"normal_10AN\">\n";
			$this->salida .= "		<td colspan=\"8\" align=\"center\">DATOS ADICIONALES</td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "	<tr align=\"center\" class=\"normal_10AN\">\n";
			$this->salida .= "		<td align=\"right\" class=\"modulo_table_list_title\" width=\"9%\">Responsable:</td><td class=\"modulo_table_list\" colspan=\"3\" align=\"left\" width=\"35%\">".$this->cuentas[nombre_tercero].' '.$this->cuentas[tipo_id_tercero].' '.$this->cuentas[tercero_id]."</td><td align=\"right\" class=\"modulo_table_list_title\" width=\"5%\">Plan:</td><td align=\"left\" colspan=\"3\" class=\"modulo_table_list\">".$this->cuentas['plan_descripcion']."</td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "	<tr align=\"center\" class=\"normal_10AN\">\n";
			$this->salida .= "		<td align=\"right\" class=\"modulo_table_list_title\" width=\"9%\">Tipo Afiliado:</td><td align=\"left\" width=\"15%\" class=\"modulo_table_list\">".$this->cuentas['tipo_afiliado_nombre']."</td><td align=\"right\" class=\"modulo_table_list_title\" width=\"5%\">Rango:</td><td align=\"left\" class=\"modulo_table_list\">".$this->cuentas['rango']."</td><td align=\"right\" class=\"modulo_table_list_title\" width=\"6%\">Ingreso:</td><td align=\"left\" class=\"modulo_table_list\" width=\"10%\">".$this->cuentas['ingreso']."</td><td align=\"right\" class=\"modulo_table_list_title\" width=\"14%\">Fecha/Hora Apertura:</td><td align=\"left\" class=\"modulo_table_list\">".$this->cuentas['fecha'].' '.$this->cuentas['hora']."</td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "</table>\n";

			$this->salida .= $cnth->ComponentePrincipal($this->request,$this->cuentas,$this->action,$this->caja,&$this);
			$this->salida .= "<form name=\"volver\" action=\"".$this->action['volver']."\" method=\"post\">\n";
			$this->salida .= "	<table width=\"50%\" align=\"center\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "</form>\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/********************************************************************************** 
		* 
		* @return boolean
		***********************************************************************************/
		function FormaCambiarValores()
		{
			$this->CambiarValores();
			IncludeClass('CuentaDetalleHTM','','app','Cuentas');
			$cntd = new CuentaDetalleHTM();
			
			$titulo = "";
			switch($this->request['tipo_cambio'])
			{
				case '1': $titulo = "DEL COPAGO";	break;
				case '2': $titulo = "DE LA CUOTA MODERADORA"; break;
			}
			
			$this->salida .= ThemeAbrirTabla("CAMBIAR VALOR ".$titulo);
			$this->salida .= $cntd->FormaModificarCuota($this->request,$this->action);
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/********************************************************************************** 
		* 
		* @return boolean
		***********************************************************************************/
		function FormaIngresarCambioValores()
		{
			$this->IngresarCambioValores();
			IncludeClass('CuentaHTML','','app','Cuentas');
			$cnt = new CuentaHTML();

			$this->script  = "<script>";
			$this->script .= "	lngtd = window.opener.document.getElementsByName('".$this->request['id_campo']."').length;\n";
			$this->script .= "	cp0 = window.opener.document.getElementsByName('".$this->request['id_campo']."');\n";
			$this->script .= "	cpI	= window.opener.document.getElementsByName('".$this->request['id_campo']."');";
			$this->script .= "	cpL =	window.opener.document.getElementsByName('h_".$this->request['id_campo']."');";
			$this->script .= "	pct = window.opener.document.getElementsByName('paciente');";
			$this->script .= "	sld =	window.opener.document.getElementsByName('saldo');";
			$this->script .= "	for(i =0; i< lngtd; i++)\n";
			$this->script .= "	{\n";
			$this->script .= "		cp0[i].className='label_error';\n";
			$this->script .= "		cpI[i].innerHTML='$".formatoValor($this->request['valor'])."';";
			$this->script .= "		cpL[i].value= '".$this->request['valor']."';";
			$this->script .= "		pct[i].innerHTML= '".Formatovalor($this->cuentas['valor_total_paciente'])."';";
			$this->script .= "		sld[i].innerHTML= '".Formatovalor($this->cuentas['saldo'])."';";
			$this->script .= "	}\n";
			$this->script .= "</script>";
			
			$this->salida .= $cnt->FormaMensaje("MENSAJE","center",$this->action,$this->frmError['MensajeError'],$this->script);
			return true;
		}
		/********************************************************************************** 
		* 
		* @return boolean
		***********************************************************************************/
		function FormaPagarCuenta()
		{
			IncludeClass('CajaHTML','','app','Cuentas');
			$cnt = new CajaHTML();
			
			$this->PagarCuenta();
			
			$this->salida .= ThemeAbrirTabla("REGISTRO DE PAGOS");
			
			switch($this->request['opcion'])
			{
				case "E": $this->salida .= $cnt->FormaPagoEfectivo($this->request);	break;
				case "C": $this->salida .= $cnt->FormaPagoCheque($this->request,$this->action);	break;
				case "R": $this->salida .= $cnt->FormaPagoTarjetaCredito($this->request,$this->action);	break;
				case "D": $this->salida .= $cnt->FormaPagoTarjetaDebito($this->request,$this->action);	break;
				case "B": $this->salida .= $cnt->FormaPagoBonos($this->request);	break;
			}
			$this->salida .= ThemeCerrarTabla(); 
			return true;
		}
		/********************************************************************************** 
		* 
		* @return boolean
		***********************************************************************************/
		function FormaIngresarPagos()
		{
			$rst = $this->IngresarPagos();
			if ($rst)
			{
				$this->salida .= "<script>\n";
				$this->salida .= "		total = window.opener.document.getElementById('total'); ";
				$this->salida .= "		pago = window.opener.document.getElementById('".$this->request['label']."'); ";
				$this->salida .= "		pago.innerHTML = (pago.innerHTML*1 + ".$this->request['valor'].");\n";
				$this->salida .= "		total.innerHTML = (total.innerHTML*1 + ".$this->request['valor'].");\n";
				$this->salida .= "		window.opener.document.getElementById('h_total').value = total.innerHTML*1;\n";
				$this->salida .= "		window.close();\n";
				$this->salida .= "</script>\n";
			}
			else
			{
				$this->salida .= ThemeAbrirTabla("ERROR");
				$this->salida .= "<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "	<tr>\n";
				$this->salida .= "		<td class=\"label_error\">\n";
				$this->salida .= "			".$this->frmError['MensajeError']."\n";
				$this->salida .= "		</td>\n";
				$this->salida .= "	</tr>\n";
				$this->salida .= "</table>\n";
				$this->salida .= ThemeCerrarTabla(); 
			}
			return true;
		}
		/********************************************************************************** 
		* 
		* @return boolean
		***********************************************************************************/
		function FormaCrearFactura()
		{
			$rst = $this->CrearFactura();

			
			IncludeClass('CajaHTML','','app','Cuentas');
			$cjhtml = new CajaHTML();
			
			if($rst === false)
			{
				$html  = ThemeAbrirTabla("ERROR");
				$html .= "<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$html .= "	<tr>\n";
				$html .= "		<td class=\"label_error\">\n";
				$html .= "			".$this->frmError['MensajeError']."\n";
				$html .= "		</td>\n";
				$html .= "	</tr>\n";
				$html .= "</table>\n";
				$html .= "<form name=\"cancelar\" action=\"".$this->action['volver']."\" method=\"post\">\n";
				$html .= "	<table align=\"center\" width=\"50%\">\n";
				$html .= "		<tr>\n";
				$html .= "			<td align=\"center\">\n";
				$html .= "				<input type=\"submit\" class=\"input-submit\" name=\"cancela\" value=\"Cancelar\" >\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				$html .= "	</table>\n";
				$html .= "</form>\n";
				$html .= ThemeCerrarTabla();	
				return true;
			}
			
			if($rst > 0)
			{
				$this->salida .= ThemeAbrirTabla("REGISTRO DE PAGOS DE LA CUENTA Nº ".$this->request['numerodecuenta']);
				$this->salida .= $cjhtml->FormaPagos($this->cuenta,$this->action);
				$this->salida .= ThemeCerrarTabla();
			}
			else
			{
				if(empty($this->retorno))
					$this->salida .= $cjhtml->FormaMensajeFinal($this->action);
				else
					$this->salida .= $cjhtml->FormaImpresionFacturas($this->action,$this->retorno,$this->request['numerodecuenta'],$this->caja['empresa_id'],sizeof($this->retorno['credito']));
			}
			return true;
		}
		/********************************************************************************** 
		* 
		* @return boolean
		***********************************************************************************/
		function FormaReportesPos()
		{
			$rst = $this->ReportesPos();
			
			$html  = ThemeAbrirTabla("MENSAJE FINAL");
			$html .= "<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "	<tr>\n";
			$html .= "		<td class=\"label_error\">\n";
			$html .= "			".$this->frmError['MensajeError']."\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
			$html .= "<form name=\"cancelar\" action=\"javascript:window.close()\" method=\"post\">\n";
			$html .= "	<table align=\"center\" width=\"50%\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td align=\"center\">\n";
			$html .= "				<input type=\"submit\" class=\"input-submit\" name=\"cancela\" value=\"Cancelar\" >\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "</form>\n";
			$html .= ThemeCerrarTabla();	
			
			return true;
		}
    
    /***********************************************************************
    * Forma para modificar un cargos de una cuenta
    *
    * @access private
    * @return boolean
    *************************************************************************/
    
    function FormaModificarCargo($Transaccion,$TipoId,$PacienteId,$Cuenta,$Nivel,$PlanId,$Fecha,$Ingreso,$D,$mensaje,$Apoyo){

      $caja = SessionGetVar("DatosCaja"); 
      if(!empty($_REQUEST['EmpresaId']) and !empty($_REQUEST['CentroUtilidad']))
      {
        $caja['empresa_id'] = $_REQUEST['EmpresaId'];
        $caja['centro_utilidad'] = $_REQUEST['CentroUtilidad'];
      }
      else
      {
       $caja['empresa_id'] =  $_REQUEST[Datos][empresa_id];
       $caja['centro_utilidad'] = $_REQUEST[Datos][centro_utilidad];
      }
      //$caja['empresa_id']='01';
      //$caja['centro_utilidad']='01';
      IncludeLib("tarifario");   
      IncludeClass('ModificacionCargoHTML','','app','Cuentas');
      $obj = new ModificacionCargoHTML();
      $this->action['cancelar'] = ModuloGetURL('app','Cuentas','user','FormaMostrarCuenta',array("Cuenta"=>$Cuenta));            
      $this->action['GuardarModificacionCargo'] = ModuloGetURL('app','Cuentas','user','ValidarModificarCargo',array('Datos'=>$D,'Transaccion'=>$Transaccion,'Consecutivo'=>$Consecutivo,'Cons'=>$Apoyo,'codigo'=>$_REQUEST['codigo'],'consecutivo'=>$consecutivo,'doc'=>$_REQUEST['doc'],'numeracion'=>$_REQUEST['numeracion'],'des'=>$des,'noFacturado'=>$noFacturado['facturado'],
      'Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));     
      $this->salida .= $obj->CrearFormaModificacionCargo($Transaccion,$TipoId,$PacienteId,$Cuenta,$Nivel,$PlanId,$Fecha,$Ingreso,$D,$mensaje,$this->action['cancelar'],$this->action['GuardarModificacionCargo'],$caja['empresa_id'],$caja['centro_utilidad']);
      return true;
    }
    
    /***********************************************************************
    * Forma para eliminar un cargos de una cuenta
    *
    * @access private
    * @return boolean
    *************************************************************************/
    
    
    function FormaEliminarCargo($transaccion,$Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Fecha,$Ingreso,$Datos,$des,$codigo,$doc,$numeracion,$noFacturado,$mensaje){
      
      $this->action['cancelar'] = ModuloGetURL('app','Cuentas','user','FormaMostrarCuenta',array("Cuenta"=>$Cuenta));            
      $this->action['EliminarCargoCuenta']=ModuloGetURL('app','Cuentas','user','ValidarEliminarCargo',array('Transaccion'=>$transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'codigo'=>$codigo,'consecutivo'=>$consecutivo,'doc'=>$doc,'numeracion'=>$numeracion,'des'=>$des,'noFacturado'=>$noFacturado['facturado']));
      IncludeClass('EliminaCargoHTML','','app','Cuentas');
      $html = new EliminaCargoHTML();            
      $this->salida .= $html->CrearFormaEliminaCargo($Cuenta,$this->action['EliminarCargoCuenta'],$this->action['cancelar'],$mensaje);
      return true;
    }
    
    /***********************************************************************
    * Forma para devolver un medicamento o insumo de la cuenta
    *
    * @access private
    * @return boolean
    *************************************************************************/
    
    
    function FormaDevolverIYMCta($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Fecha,$Ingreso,$mensaje){            
      $this->IncludeJS("CrossBrowser");
      $this->IncludeJS("CrossBrowserDrag");
      $this->IncludeJS("CrossBrowserEvent");
      IncludeClass('DevolucionCargosIyMCtaHTML','','app','Cuentas');      
      $this->action['DevolucionIYM']=ModuloGetURL('app','Cuentas','user','RealizarVevolucionMedicamentos',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
      $this->action['cancelar'] = ModuloGetURL('app','Cuentas','user','FormaMostrarCuenta',array("Cuenta"=>$Cuenta));            
      $html = new DevolucionCargosIyMCtaHTML();                 
      $this->salida .= $html->CrearFormaDevolucionCargosCta($Cuenta,$TipoId,$PacienteId,$this->action['DevolucionIYM'],$this->action['cancelar'],$this,$mensaje);
      return true;
    }

		function FormaMenu($opcion)
		{	
			SessionDelVar("Cuentas_ListatoPacinetesConSAlida");
			SessionDelVar("EmpresaIdListaPacientesConSalida");
			
			if($_REQUEST[Cuenta][empresa_id])
			{SessionSetVar("Cuenta_empresa_id",$_REQUEST[Cuenta][empresa_id]);}
			else
			{$_REQUEST[Cuenta][empresa_id] = SessionGetVar("Cuenta_empresa_id");}
			
			if($_REQUEST[Cuenta][centro_utilidad])
			{SessionSetVar("Cuenta_centro_utilidad",$_REQUEST[Cuenta][centro_utilidad]);}
			else
			{$_REQUEST[Cuenta][centro_utilidad] = SessionGetVar("Cuenta_centro_utilidad");}
			
			if($_REQUEST[Cuenta][documento_id])
			{SessionSetVar("Cuenta_documento_id",$_REQUEST[Cuenta][documento_id]);}
			else
			{$_REQUEST[Cuenta][Cuenta_documento_id] = SessionGetVar("Cuenta_documento_id");}
			
			SessionSetVar("Cuenta_documento_id",str_replace("''","'",SessionGetVar("Cuenta_documento_id")));
			
			if($_REQUEST[Cuenta][punto_facturacion_id])
			{SessionSetVar("Punto_facturacion_id",$_REQUEST[Cuenta][punto_facturacion_id]);}
			else
			{$_REQUEST[Cuenta][punto_facturacion_id] = SessionGetVar("Punto_facturacion_id");}

			if($_REQUEST['Cuenta']['sw_unificar'])
        SessionSetVar("PermisoUnificacionCuenta",$_REQUEST['Cuenta']['sw_unificar']);
			else
        $_REQUEST['Cuenta']['sw_unificar'] = SessionGetVar("PermisoUnificacionCuenta");
			
			$this->SetXajax(array("GetEstadoPlanes"),"app_modules/Cuentas/RemoteXajax/CuentasPlanes.php");
			$script = "<script>\n";
			$script .= "	function mOvr(src,clrOver)\n";
			$script .= "	{\n";
			$script .= "		src.style.background = clrOver;\n";
			$script .= "	}\n";
			$script .= "	function mOut(src,clrIn)\n";
			$script .= "	{\n";
			$script .= "		src.style.background = clrIn;\n";
			$script .= "	}\n";
			$script .= "</script>\n";
			$this->salida = $script;
			$this->salida .= ThemeAbrirTabla('MENUS CUENTAS');
			$this->salida .= "            <br>";
			$this->salida .= "<form name=\"formamenu\" method=\"post\">\n";
			$this->salida .= "            <table width=\"40%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
			$this->salida .= "               <tr>";
			$this->salida .= "                  <td align=\"center\" class=\"modulo_table_list_title\">MENU CUENTAS</td>";
			$this->salida .= "               </tr>";
			$bac = '#DDDDDD';
			$est = 'modulo_list_claro';
			$this->salida .= "               <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bac."\"); onmouseover=mOvr(this,'#FFFFFF');>";
			$accion=ModuloGetURL('app','Cuentas','user','FormaBuscarCuentas',array('SWCUENTAS'=>'Cuentas','EmpresaId' =>$_REQUEST[Cuenta][empresa_id],'CentroUtilidadId' =>$_REQUEST[Cuenta][centro_utilidad]));
			$this->salida .= "                  <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accion\">Cuentas (Activas - Inactivas)</a></td>";
			$this->salida .= "               </tr>";        
			$this->salida .= "           </table>";
			//OPCIONES - LISTADO DE PACIENTES CON SALIDA - REPORTES
			IncludeClass('ListadoPacientesconSalidaHTML','','app','Cuentas');
			$html = new ListadoPacientesconSalidaHTML();
			$this->salida .= $html->CrearEnlaceslistados($_REQUEST[Cuenta][empresa_id]);
			//FIN OPCIONES - LISTADO DE PACIENTES CON SALIDA - REPORTES
			$this->salida .= "</form>\n";
			$accion = ModuloGetURL('app','Cuentas','user','FormaMain');
			$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
			$this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"></p>";
			$this->salida .= "</form>";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
    /**
    *
    */
    function ImprimirRecibocaja()
    {
      
      IncludeClass('ImprimirSQL','','app','Cuentas');
      //IncludeClass('ImprimirHTML','','app','Cuentas');
      $cnt = new ImprimirSQL();
      //$html = new ImprimirHTML();
      $PlanId=$_REQUEST['PlanId'];
      $Recibo=$_REQUEST['Recibo'];
      $Prefijo=$_REQUEST['prefijo'];
      $Empresa=$_REQUEST['empresa'];
      $CenU=$_REQUEST['cu'];
      $TipoId=$_REQUEST['TipoId'];
      $PacienteId=$_REQUEST['PacienteId'];
      $caja_id=$_REQUEST['cajaid'];
     
			$datos = $cnt->BuscarDatos($Recibo,$Prefijo,$Empresa,$CenU,$TipoId,$PacienteId,$PlanId, $caja_id);
      IncludeLib("reportes/recibo_caja"); //car
      GenerarReciboCaja($datos);
      
      $url = GetBaseURL() . "cache/Recibo".UserGetUID().".pdf";
      $this->salida .= "<script>\n";
      $this->salida .= "  location.href=\"".$url."\"; ";
      $this->salida .= "</script>\n";
      return true;
    }    
	}
?>