<?php
	/**************************************************************************************  
	* $Id: app_AnularCuentas_userclasses_HTML.php,v 1.2 2007/02/12 15:00:29 hugo Exp $ 
	* 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	* 
	* $Revision: 1.2 $ 
	* 
	* @autor Hugo F  Manrique 
	***************************************************************************************/
	IncludeClass("ClaseHTML");
	class app_AnularCuentas_userclasses_HTML extends app_AnularCuentas_user
	{
		function app_AnularCuentas_userclasses_HTML(){	}
		/********************************************************************************** 
		* Función principal del módulo 
		* 
		* @return boolean
		***********************************************************************************/
		function FormaMain()
		{
			$this->Main();
			if(sizeof($this->empresas) > 1)
			{
				$url[0] = 'app';										//contenedor 
				$url[1] = 'AnularCuentas';			//módulo 
				$url[2] = 'user';										//clase 
				$url[3] = 'FormaBuscarCuentas';	//método 
				$url[4] = 'permiso';								//indice del request
				$titulo[0] = 'EMPRESAS';
				$this->salida .= gui_theme_menu_acceso('ANULACIÓN DE FACTURAS',$titulo,$this->empresas,$url,$this->action['volver']);
			}
			else
			{
				$this->FormaBuscarCuentas();
			}
			return true;
		}
		/********************************************************************************** 
		* 
		* @return boolean
		***********************************************************************************/
		function FormaBuscarCuentas()
		{
			$this->BuscarCuentas();
			
			$this->salida .= ThemeAbrirTabla("BUSCAR ".$this->variable['descripcion']);
			$this->salida .= "<script>\n";
			$this->salida .= "	function acceptNum(evt)\n";
			$this->salida .= "  {\n";
			$this->salida .= "    var nav4 = window.Event ? true : false;\n";
			$this->salida .= "    var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "    return (key <= 13 || (key >= 48 && key <= 57) || key == 45);\n";
			$this->salida .= "  }\n";
			$this->salida .= "	function EvaluarDatos(frm)\n";
			$this->salida .= "	{\n";
			$this->salida .= " 		bool = false;\n";
			$this->salida .= "		for(i=0; i<frm.length; i++)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			switch(frm[i].type)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				case 'text':\n";
			$this->salida .= " 					if(frm[i].value != '')\n";
			$this->salida .= " 					{\n";
			$this->salida .= " 						bool = true;\n";
			$this->salida .= " 						break;\n";
			$this->salida .= " 					}\n";
			$this->salida .= " 				break;\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		if(bool == true)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			frm.action = '".$this->action['buscar']."';\n";
			$this->salida .= "			frm.submit();\n";
			$this->salida .= "		}\n";
			$this->salida .= "		else\n";
			$this->salida .= "		{\n";
			$this->salida .= "			document.getElementById('error').innerHTML = 'SE DEBE INGRESAR ALGUN CRITERIO DE BUSQUEDA'\n";
			$this->salida .= "		}\n";
			$this->salida .= "	}\n";
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
			$this->salida .= "</script>\n";
			$this->salida .= "<form name=\"formabuscar\" action=\"javascript:EvaluarDatos(document.formabuscar)\" method=\"post\">\n";
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
				$this->salida .= "			<td width=\"40%\" colspan=\"2\">PACIENTE</td>\n";
				$this->salida .= "			<td width=\"30%\">PLAN CUENTA</td>\n";
				$this->salida .= "			<td width=\"8%\" title=\"FECHA HORA REGISTRO\">FECHA</td>\n";
				$this->salida .= "			<td width=\"8%\" title=\"TOTAL CUENTA\">TOTAL</td>\n";
				$this->salida .= "			<td width=\"%\" colspan=\"2\">OPCIONES</td>\n";
				$this->salida .= "		</tr>\n";
				
				$est = "modulo_list_oscuro";
				foreach($this->cuentas as $key => $cuentas)
				{
					($est == "modulo_list_oscuro")? $est = "modulo_list_claro": $est = "modulo_list_oscuro";
					
					$this->salida .= "		<tr class=\"".$est."\">\n";
					$this->salida .= "			<td class=\"normal_10AN\">".$cuentas['numerodecuenta']."</td>\n";
					$this->salida .= "			<td width=\"15%\">".$cuentas['tipo_id_paciente']." ".$cuentas['paciente_id']."</td>\n";
					$this->salida .= "			<td width=\"25%\">".$cuentas['nombre']."</td>\n";
					$this->salida .= "			<td >".$cuentas['plan_descripcion']."</td>\n";
					$this->salida .= "			<td align=\"center\"	title=\"FECHA HORA REGISTRO\">".$cuentas['fecha']."</td>\n";
					$this->salida .= "			<td align=\"right\"		title=\"TOTAL CUENTA\" class=\"normal_10AN\">$".formatoValor($cuentas['total_cuenta'])."</td>\n";
					$this->salida .= "			<td align=\"center\">";
					if($cuentas['estado'] != "ANULADA" && $cuentas['estado'] != "FACTURADA")
					{
						$dat = array();
						$dat['forma'] = "formabuscar";
						$dat['ingreso'] = $cuentas['ingreso'];
						$dat['numerodecuenta'] = $cuentas['numerodecuenta'];
					
						$this->salida .= "				<a title=\"ANULAR CUENTA\" href=\"".$this->action['anular'].UrlRequest($dat)."\" target=\"lista\" onclick=\"window.open('".$this->action['anular'].UrlRequest($dat)."','lista','toolbar=no,width=400,height=300,resizable=no,scrollbars=yes').focus(); return false;\" class=\"label_error\">\n";
						$this->salida .= "					<img src=\"".GetThemePath()."/images/pincumplimiento_citas.png\" border=\"0\">\n";
						$this->salida .= "				</a>\n";
					}
					else
					{
						$this->salida .= "			<b class=\"normal_10AN\">".$cuentas['estado']."</b>\n";
					}
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
		function FormaAnularCuenta()
		{
			$this->AnularCuenta();
			$this->salida .= ThemeAbrirTabla("ANULAR CUENTA Nº ".$this->request['numerodecuenta']);
			$this->salida .= "<script>\n";
			$this->salida .= "	function EvaluarDatos(frm)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		mensaje = '';\n";
			$this->salida .= "		if(frm.observacion.value == '')\n";
			$this->salida .= "		{\n";
			$this->salida .= "			mensaje = 'SE DEBE INGRESAR UNA JUSTIFICACION DEL PORQUE SE ANULA LA CUENTA';\n";
			$this->salida .= "			document.getElementById('error').innerHTML = mensaje;\n";
			$this->salida .= "			return;\n";
			$this->salida .= "		}\n";
			$this->salida .= "		frm.submit();\n";
			$this->salida .= "	}\n";
			$this->salida .= "</script>\n";
			$this->salida .= "<center>\n";
			$this->salida .= "	<div id =\"error\" style=\"text-align:center;width:100%;\" class=\"label_error\"></div>\n";
			$this->salida .= "</center>\n";
			
			$this->salida .= "<form name=\"formabuscar\" action=\"".$this->action['anular']."\" method=\"post\">\n";   
			$this->salida .= "	<table width=\"100%\" border=\"0\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "			<td colspan=\"2\">JUSTIFICACION</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "			<td colspan=\"2\">\n";
			$this->salida .= "				<textarea name=\"observacion\" rows=\"3\" style=\"width:100%\" class=\"textarea\"></textarea>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table><br>\n";
			$this->salida .= "	<table width=\"60%\" border=\"0\" align=\"center\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<input class=\"input-submit\" type=\"button\" name=\"aceptar\" value=\"Aceptar\" onclick=\"EvaluarDatos(document.formabuscar)\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<input class=\"input-submit\" name=\"Cancelar\" type=\"button\" onclick=\"window.close()\" value=\"Cerrar\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "</form>\n";
			$this->salida .= ThemeCerrarTabla();
			return $this->salida;
		}
		/********************************************************************************** 
		* 
		* @return boolean
		***********************************************************************************/
		function FormaRegistrarAnularCuenta()
		{
			$this->RegistrarAnularCuenta();
			
			$this->salida .= ThemeAbrirTabla("MENSAJE");
			$this->salida .= "<script>\n";
			$this->salida .= "	function EvaluarDatos(frm)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		mensaje = '';\n";
			$this->salida .= "		if(frm.observacion.value == '')\n";
			$this->salida .= "		{\n";
			$this->salida .= "			mensaje = 'SE DEBE INGRESAR UNA JUSTIFICACION DEL PORQUE SE ANULA LA CUENTA';\n";
			$this->salida .= "			document.getElementById('error').innerHTML = mensaje;\n";
			$this->salida .= "			return;\n";
			$this->salida .= "		}\n";
			$this->salida .= "		frm.submit();\n";
			$this->salida .= "	}\n";
			$this->salida .= "</script>\n";
			$this->salida .= "<form name=\"formabuscar\" action=\"\" method=\"post\">\n";   
			$this->salida .= "	<table width=\"100%\" border=\"0\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "		<tr class=\"normal_10AN\">\n";
			$this->salida .= "			<td>\n";
			$this->salida .= "				".$this->frmError['MensajeError']."\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table><br>\n";
			$this->salida .= "	<table width=\"60%\" border=\"0\" align=\"center\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<input class=\"input-submit\" name=\"Cancelar\" type=\"button\" onclick=\"window.opener.document.".$this->request['forma'].".submit();window.close()\" value=\"Cerrar\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "</form>\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
	}
?>
