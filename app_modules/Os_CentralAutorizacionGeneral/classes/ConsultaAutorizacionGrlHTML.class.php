<?php
	/**************************************************************************************
	* $Id: ConsultaAutorizacionGrlHTML.class.php,v 1.1 2007/04/16 20:46:41 hugo Exp $
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* $Revision: 1.1 $
	*
	* @autor Hugo F  Manrique
	***************************************************************************************/
	class ConsultaAutorizacionGrlHTML
	{
		function ConsultaAutorizacionGrlHTML(){}
		/********************************************************************************** 
		* Función principal del módulo 
		* 
		* @return boolean
		***********************************************************************************/
		function FormaListaOrdenes($datos,$action)
    {
			IncludeClass('ConsultaAutorizacionGrl','','app','Os_CentralAutorizacionGeneral');
			$cns = new ConsultaAutorizacionGrl();
			
			$ordenes = $cns->ObtenerListadoOrdenes($datos['tipo_id_paciente'],$datos['paciente_id']);
			$refrendar = $cns->ObtenerListadoOrdenesXRefrendar($datos['tipo_id_paciente'],$datos['paciente_id']);
			$vencidas = $cns->ObtenerListadoOrdenesVencidas($datos['tipo_id_paciente'],$datos['paciente_id'],"uno");
			
			$html .= "<table width=\"55%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "	<tr class=\"modulo_table_list_title\">\n";
			$html .= "		<td width=\"40%\">IDENTIFICACION</td>\n";
			$html .= "		<td width=\"60%\">PACIENTE</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr class=\"normal_10AN\" style=\"text-align:center\">\n";
			$html .= "		<td >".$datos['tipo_id_paciente']." ".$datos['paciente_id']."</td>\n";
			$html .= "		<td >".$datos['nombre']." ".$datos['apellido']."</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table><br>\n";
			
			if($vencidas[0] > 0)
			{
				$html .= "<center>\n";
				$html .= "	<a href=\"".$action['listado']."\" target=\"lista\" onclick=\"window.open('".$action['listado']."','lista','toolbar=no,width=700,height=550,resizable=no,scrollbars=yes').focus(); return false;\" class=\"label_error\">\n";
				$html .= "		LISTA ORDENES VENCIDAS\n";
				$html .= "	</a>\n";
				$html .= "</center>\n";
			}
			
			if(!empty($ordenes))
			{
				$oscargos = $cns->ObtenerListadoCargosOrdenes($datos['tipo_id_paciente'],$datos['paciente_id']);
				
				$mdl = "modulo_list_oscuro";
				foreach($ordenes as $key0 =>  $plan)
				{
					$html .= "<table width=\"95%\" align=\"center\">\n";
					$html .= "	<tr>\n";
					$html .= "		<td>\n";
					$html .= "			<fieldset class=\"fieldset\"><legend class=\"normal_10AN\">".$key0."</legend>\n";
					$html .= "				<table border=\"0\" width=\"100%\" align=\"center\" cellpadding=\"3pt\">\n";

					foreach($plan as $keyS => $sw_interna)
					{
						
						$flag = true;
						foreach($sw_interna as $key => $ordeninterna)
						{						
							$fechas = array();
							$ttl = "EXTERNA";
							$est = "modulo_table_title ";
							if($keyS == '1') 
							{
								$ttl = "INTERNA";
								$est = "formulacion_table_list";
							}	
							
							($mdl == "modulo_list_claro")? $mdl = "modulo_list_oscuro": $mdl = "modulo_list_claro";
							
							$html .= "					<tr>\n";
							$html .= "						<td >\n";
							$html .= "							<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
							$html .= "								<tr class=\"$est\">\n";
							$html .= "									<td rowspan=\"2\" style=\"font-size:12;text-indent:0pt;text-align:center\" class=\"$est\" width=\"10%\">$ttl</td>\n";
							$html .= "									<td style=\"text-indent:8pt;text-align:left\" width=\"16%\">Nº ORDEN:</td>\n";
							$html .= "									<td class=\"$mdl\" style=\"text-indent:8pt;text-align:left\" width=\"10%\" >".$ordeninterna['orden_servicio_id']."</td>\n";
							$html .= "									<td style=\"text-indent:8pt;text-align:left\" width=\"18%\">SERVICIO:</td>\n";
							$html .= "									<td class=\"$mdl\" style=\"text-indent:8pt;text-align:left\" colspan=\"3\">".$ordeninterna['servicio']."</td>\n";
							
							$fechas['orden_servicio_id'] = $ordeninterna['orden_servicio_id'];
							$url1 = $action['anular'].UrlRequest($fechas);
							
							$fechas['refrendar'] = $ordeninterna['refrendar'];
							$fechas['activacion'] = $ordeninterna['activacion'];
							$fechas['vencimiento'] = $ordeninterna['vencimiento'];
							
							$url = $action['fechas'].UrlRequest($fechas);
							
							$html .= "									<td rowspan=\"2\" class=\"$mdl\" width=\"4%\" style=\"font-size:12;text-indent:0pt;text-align:center\">\n";
							$html .= "										<a href=\"".$action['fechas']."\" target=\"fechas\" onclick=\"window.open('".$url."','fechas','toolbar=no,width=500,height=350,resizable=no,scrollbars=yes').focus(); return false;\" title=\"CAMBIAR FECHAS\">\n";
							$html .= "											<img src=\"".GetThemePath()."/images/fecha_inicio.png\" border=\"0\">\n";
							$html .= "										</a>\n";
							$html .= "									</td>\n";
							$html .= "									<td rowspan=\"2\" class=\"$mdl\" width=\"4%\" style=\"font-size:12;text-indent:0pt;text-align:center\">\n";
							$html .= "										<a href=\"".$action['anular']."\" target=\"anular\" onclick=\"window.open('".$url1."','anular','toolbar=no,width=600,height=410,resizable=no,scrollbars=yes').focus(); return false;\" title=\"ANULAR ORDEN DE SERVICIO\">\n";
							$html .= "											<img src=\"".GetThemePath()."/images/pincumplimiento_citas.png\" border=\"0\">\n";
							$html .= "										</a>\n";
							$html .= "									</td>\n";
							$html .= "								</tr>\n";
							$html .= "								<tr class=\"$est\">\n";
							$html .= "									<td style=\"text-indent:8pt;text-align:left\">FECHA ACTIVACION:</td>\n";
							$html .= "									<td style=\"text-indent:8pt;text-align:left\" class=\"$mdl\">".$ordeninterna['activacion']."</td>\n";
							$html .= "									<td style=\"text-indent:8pt;text-align:left\">FECHA VENCIMIENTO:</td>\n";
							$html .= "									<td style=\"text-indent:8pt;text-align:left\" class=\"$mdl\">".$ordeninterna['vencimiento']."</td>\n";
							$html .= "									<td style=\"text-indent:8pt;text-align:left\">REFRENDAR:</td>\n";
							$html .= "									<td style=\"text-indent:8pt;text-align:left\" class=\"$mdl\">".$ordeninterna['refrendar']."</td>\n";
							$html .= "								</tr>\n";
							if($ordeninterna['observacion'])
							{
								$html .= "								<tr class=\"$est\">\n";
								$html .= "									<td>OBSERVACION:</td>\n";
								$html .= "									<td colspan=\"8\" class=\"$mdl\">".$ordeninterna['observacion']."</td>\n";
								$html .= "								</tr>\n";					
							}
							
							$html .= "								<tr>\n";
							$html .= "									<td colspan=\"9\">\n";
							$html .= "										<table width=\"100%\" class=\"modulo_table_list\">\n";
							$html .= "											<tr class=\"modulo_table_list_title\" >\n";
							$html .= "												<td valign=\"top\" width=\"9%\">Nº SOL</td>\n";
							$html .= "												<td valign=\"top\" width=\"9%\">CUPS</td>\n";
							$html .= "												<td valign=\"top\" width=\"18%\" colspan=\"2\">TARIFARIO - CARGO</td>\n";
							$html .= "												<td valign=\"top\">DESCRIPCION</td>\n";
							$html .= "											</tr>\n";
							$i = 0;
							//$html .= "<pre>".print_r($cargos[$ordeninterna['plan_id']],true)."</pre>";
							foreach($oscargos[$ordeninterna['plan_id']][$key] as $keyC => $cargos)
							{
								$html .= "											<tr class=\"$mdl\">\n";
								$html .= "												<td align=\"center\" >".$cargos['hc_os_solicitud_id']."</td>\n";
								$html .= "												<td class=\"normal_10AN\" style=\"text-align:center; cursor:pointer\" title=\"".$cargos['descripcion_cups']."\">".$cargos['cargo_cups']."</td>\n";
								$html .= "												<td width=\"9%\" align=\"center\">".$cargos['tarifario_id']."</td>\n";
								$html .= "												<td width=\"9%\" align=\"center\">".$cargos['cargo']."</td>\n";
								$html .= "												<td>".$cargos['descripcion_cargo']."</td>\n";
								$html .= "											</tr>\n";
								$i++;
							}
							
							$html .= "										</table>\n";
							$html .= "									</td>\n";
							$html .= "								</tr>\n";
							$html .= "							</table>\n";
							$html .= "						</td>\n";
							$html .= "					</tr>\n";
						}
					}
					$html .= "				</table>\n";					
					$html .= "			</fieldset>\n";
					$html .= "		</td>\n";
					$html .= "	</tr>\n";
					$html .= "</table>\n";
				}
			}
			
			if(!empty($refrendar))
			{
				$oscargosr = $cns->ObtenerListadoCargosOrdenesXRefrendar($datos['tipo_id_paciente'],$datos['paciente_id']);
				$html .= "<br>\n";
				$html .= "<table width=\"100%\">\n";
				$html .= "	<tr>\n";
				$html .= "		<td>\n";
				$html .= "			<fieldset class=\"fieldset_error\"><center><legend>ORDENES VENCIDAS POR REFRENDAR</legend></center>\n";

				$mdl = "modulo_list_oscuro";
				foreach($refrendar as $key0 =>  $plan)
				{
					$html .= "<table width=\"100%\">\n";
					$html .= "	<tr>\n";
					$html .= "		<td>\n";
					$html .= "			<fieldset class=\"fieldset\"><legend class=\"normal_10AN\">".$key0."</legend>\n";
					$html .= "				<table border=\"0\" width=\"100%\" align=\"center\" cellpadding=\"3pt\">\n";

					foreach($plan as $keyS => $sw_interna)
					{
						
						$flag = true;
						foreach($sw_interna as $key => $ordeninterna)
						{						
							$fechas = array();
							$ttl = "EXTERNA";
							$est = "formulacion_table_list_suspendido ";
							if($keyS == '1') $ttl = "INTERNA";
							
							($mdl == "modulo_list_claro")? $mdl = "modulo_list_oscuro": $mdl = "modulo_list_claro";
							
							$html .= "					<tr>\n";
							$html .= "						<td >\n";
							$html .= "							<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
							$html .= "								<tr class=\"$est\">\n";
							$html .= "									<td rowspan=\"2\" style=\"font-size:12;text-indent:0pt;text-align:center\" class=\"$est\" width=\"13%\">$ttl</td>\n";
							$html .= "									<td style=\"text-indent:8pt;text-align:left\" width=\"16%\">Nº ORDEN:</td>\n";
							$html .= "									<td class=\"$mdl\" style=\"text-indent:8pt;text-align:left\" width=\"10%\" >".$ordeninterna['orden_servicio_id']."</td>\n";
							$html .= "									<td style=\"text-indent:8pt;text-align:left\" width=\"16%\">SERVICIO:</td>\n";
							$html .= "									<td class=\"$mdl\" style=\"text-indent:8pt;text-align:left\" colspan=\"3\">".$ordeninterna['servicio']."</td>\n";
							
							$fechas['orden_servicio_id'] = $ordeninterna['orden_servicio_id'];
							$url1 = $action['anular'].UrlRequest($fechas);
							
							$fechas['refrendar'] = $ordeninterna['refrendar'];
							$fechas['activacion'] = $ordeninterna['activacion'];
							$fechas['vencimiento'] = $ordeninterna['vencimiento'];
							
							$url = $action['fechas'].UrlRequest($fechas);
							
							$html .= "									<td rowspan=\"2\" class=\"$mdl\" width=\"5%\" style=\"font-size:12;text-indent:0pt;text-align:center\">\n";
							$html .= "										<a href=\"".$action['fechas']."\" target=\"fechas\" onclick=\"window.open('".$url."','fechas','toolbar=no,width=500,height=350,resizable=no,scrollbars=yes').focus(); return false;\" title=\"CAMBIAR FECHAS\">\n";
							$html .= "											<img src=\"".GetThemePath()."/images/fecha_inicio.png\" border=\"0\">\n";
							$html .= "										</a>\n";
							$html .= "									</td>\n";
							$html .= "									<td rowspan=\"2\" class=\"$mdl\" width=\"5%\" style=\"font-size:12;text-indent:0pt;text-align:center\">\n";
							$html .= "										<a href=\"".$action['anular']."\" target=\"anular\" onclick=\"window.open('".$url1."','anular','toolbar=no,width=600,height=410,resizable=no,scrollbars=yes').focus(); return false;\" title=\"ANULAR ORDEN DE SERVICIO\">\n";
							$html .= "											<img src=\"".GetThemePath()."/images/pincumplimiento_citas.png\" border=\"0\">\n";
							$html .= "										</a>\n";
							$html .= "									</td>\n";
							$html .= "								</tr>\n";
							$html .= "								<tr class=\"$est\">\n";
							$html .= "									<td style=\"text-indent:8pt;text-align:left\">FECHA ACTIVACION:</td>\n";
							$html .= "									<td style=\"text-indent:8pt;text-align:left\" class=\"$mdl\">".$ordeninterna['activacion']."</td>\n";
							$html .= "									<td style=\"text-indent:8pt;text-align:left\">FECHA VENCIMIENTO:</td>\n";
							$html .= "									<td style=\"text-indent:8pt;text-align:left\" class=\"$mdl\">".$ordeninterna['vencimiento']."</td>\n";
							$html .= "									<td style=\"text-indent:8pt;text-align:left\">REFRENDAR:</td>\n";
							$html .= "									<td style=\"text-indent:8pt;text-align:left\" class=\"$mdl\">".$ordeninterna['refrendar']."</td>\n";
							$html .= "								</tr>\n";
							if($ordeninterna['observacion'])
							{
								$html .= "								<tr class=\"$est\">\n";
								$html .= "									<td>OBSERVACION:</td>\n";
								$html .= "									<td colspan=\"8\" class=\"$mdl\">".$ordeninterna['observacion']."</td>\n";
								$html .= "								</tr>\n";					
							}
							
							$html .= "								<tr>\n";
							$html .= "									<td colspan=\"9\">\n";
							$html .= "										<table width=\"100%\" class=\"modulo_table_list\">\n";
							$html .= "											<tr class=\"$est\" >\n";
							$html .= "												<td valign=\"top\" width=\"8%\">Nº SOL</td>\n";
							$html .= "												<td valign=\"top\" width=\"8%\">CUPS</td>\n";
							$html .= "												<td valign=\"top\" width=\"14%\" colspan=\"2\">TARIFARIO - CARGO</td>\n";
							$html .= "												<td valign=\"top\">DESCRIPCION</td>\n";
							$html .= "												<td width=\"4%\">FAC<br>".$facturar."</td>\n";
							$html .= "												<td width=\"4%\">CUM<br>".$cumplir."</td>\n";
							$html .= "											</tr>\n";
							$i = 0;
							//$html .= "<pre>".print_r($cargos,true)."</pre>";
							foreach($oscargosr[$ordeninterna['plan_id']][$key] as $keyC => $cargos)
							{
								$html .= "											<tr class=\"$mdl\">\n";
								$html .= "												<td align=\"center\" >".$cargos['hc_os_solicitud_id']."</td>\n";
								$html .= "												<td class=\"normal_10AN\" style=\"text-align:center; cursor:pointer\" title=\"".$cargos['descripcion_cups']."\">".$cargos['cargo_cups']."</td>\n";
								$html .= "												<td width=\"7%\" align=\"center\">".$cargos['tarifario_id']."</td>\n";
								$html .= "												<td width=\"7%\" align=\"center\">".$cargos['cargo']."</td>\n";
								$html .= "												<td>".$cargos['descripcion_cargo']."</td>\n";
								$html .= "												<td align=\"center\">$facturar</td>\n";
								$html .= "												<td align=\"center\">$cumplir</td>\n";
								$html .= "											</tr>\n";
								$i++;
							}
							
							$html .= "										</table>\n";
							$html .= "									</td>\n";
							$html .= "								</tr>\n";
							$html .= "							</table>\n";
							$html .= "						</td>\n";
							$html .= "					</tr>\n";
						}
						

					}
					$html .= "				</table>\n";					
					$html .= "			</fieldset>\n";
					$html .= "		</td>\n";
					$html .= "	</tr>\n";
					$html .= "</table>\n";
				}
				$html .= "			</fieldset>\n";
				$html .= "		</td>\n";
				$html .= "	</tr>\n";
				$html .= "</table>\n";
			}
			
			$html .= "<form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">";
			$html .= "	<table width=\"100%\" align=\"center\">\n";
			$html .= "		</tr>\n";
			$html .= "			<td align=\"center\">\n";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Volver\">\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "</form>\n";
			$html .= "<form name=\"recarga\" action =\"".$action['recarga']."\" method=\"post\"><form>\n";			
			return $html;
		}
		/********************************************************************************** 
		* Función principal del módulo 
		* 
		* @return boolean
		***********************************************************************************/
		function FormaAnularOrden($datos,$action)
		{
			IncludeClass('ConsultaAutorizacionGrl','','app','Os_CentralAutorizacionGeneral');
			$cns = new ConsultaAutorizacionGrl();
			$conceptos = $cns->ObtenerConceptosAnulacionOS();
			
			$html  = "<script>\n";
			$html .= "	function EvaluarDatos(frm)\n";
			$html .= "	{\n";
			$html .= "		mensaje = '';\n";
			$html .= "		if(frm.concepto.value == '-1')\n";
			$html .= "			mensaje = 'SE DEBE SELECCIONAR EL CONCEPTO POR EL CUAL SE ANULARA LA ORDEN';\n";
			$html .= "		else if(!frm.anular_liberar[0].checked && !frm.anular_liberar[1].checked)\n";
			$html .= "			mensaje = 'SE DEBE SELECCIONAR LA ACCION A SEGUIR CON LAS SOLICITUDES PERTENCIENTES A LA ORDEN DE SERVICIO';\n";
			$html .= "		document.getElementById('error').innerHTML = mensaje;\n";
			$html .= "		if(mensaje == '') frm.submit();\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			$html .= "<center>\n";
			$html .= "	<div id =\"error\" style=\"text-align:center;width:70%;\" class=\"label_error\"></div>\n";
			$html .= "</center>\n";
			
			$html .= "<form name=\"formabuscar\" action=\"".$action['aceptar']."\" method=\"post\">\n";   
			$html .= "	<table width=\"60%\" border=\"0\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td width=\"20%\">CONCEPTO:</td>\n";
			$html .= "			<td class=\"modulo_list_calro\">\n";
			$html .= "				<select name=\"concepto\" class=\"select\">\n";
			$html .= "					<option value=\"-1\">----------SELECCIONE----------</option>\n";
			
			foreach($conceptos as $key => $just)
				$html .= "					<option value=\"".$just['oaj_id']."\">".$just['descripcion']."</option>\n";
			
			$html .= "				</select>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td colspan=\"2\">OBSERVACION</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_list_claro\">\n";
			$html .= "			<td colspan=\"2\">\n";
			$html .= "				<textarea name=\"observacion\" rows=\"3\" style=\"width:100%\" class=\"textarea\"></textarea>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td colspan=\"2\">¿QUE DESEA HACER CON LAS SOLICITUDES DE ESTA ORDEN?</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_list_claro\">\n";
			$html .= "			<td colspan=\"2\" >\n";
			$html .= "				<table width=\"100%\" align=\"center\" class=\"normal_10AN\">\n";
			$html .= "					<tr>\n";
			$html .= "						<td width=\"50%\">\n";
			$html .= "							<input type=\"radio\" name=\"anular_liberar\" value=\"2\">ANULAR SOLICITUD(ES)\n";
			$html .= "						</td>\n";
			$html .= "						<td>\n";
			$html .= "							<input type=\"radio\" name=\"anular_liberar\" value=\"1\">LIBERAR SOLICITUD(ES)\n";
			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "				</table>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table><br>\n";
			$html .= "	<table width=\"60%\" border=\"0\" align=\"center\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td align=\"center\">\n";
			$html .= "				<input class=\"input-submit\" type=\"button\" name=\"aceptar\" value=\"Aceptar\" onclick=\"EvaluarDatos(document.formabuscar)\">\n";
			$html .= "			</td>\n";
			$html .= "			<td align=\"center\">\n";
			$html .= "				<input class=\"input-submit\" name=\"Cancelar\" type=\"button\" onclick=\"window.close()\" value=\"Cerrar\">\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "</form>\n";
			return $html;
		}
		/********************************************************************************** 
		* Función principal del módulo 
		* 
		* @return boolean
		***********************************************************************************/
		function FormaListaOrdenesVencidas($datos,$action)
    {
			IncludeClass('ConsultaAutorizacionGrl','','app','Os_CentralAutorizacionGeneral');
			$cns = new ConsultaAutorizacionGrl();
			
			$vencidas = $cns->ObtenerListadoOrdenesVencidas($datos['tipo_id_paciente'],$datos['paciente_id']);
			$oscargosr = $cns->ObtenerListadoCargosOrdenesVencidas($datos['tipo_id_paciente'],$datos['paciente_id']);
			
			$background = "#CCCCCC";
			$mdl = "modulo_list_oscuro";
			$est = "formulacion_table_list_suspendido ";			
			$capas = ""; 
			$html .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\" cellpadding=\"0\">\n";
			$html .= "	<tr height=\"17\" class=\"modulo_table_list_title\" >\n";
			$html .= "		<td width=\"%\">&nbsp;</td>\n";
			$html .= "		<td width=\"20%\">ORDEN Nº:</td>\n";
			$html .= "		<td width=\"20%\">TIPO ORDEN:</td>\n";
			$html .= "		<td width=\"60%\">PLAN</td>\n";
			$html .= "	</tr>\n";
			
			foreach($vencidas as $key0 =>  $plan)
			{
				foreach($plan as $keyS => $sw_interna)
				{
					foreach($sw_interna as $key => $ordeninterna)
					{
						
						if($background == "#DDDDDD")
						  $background = "#CCCCCC";
						else
							$background = "#DDDDDD";
						
						$ttl = "EXTERNA";
						if($keyS == '1') $ttl = "INTERNA";
						
						($mdl == "modulo_list_claro")? $mdl = "modulo_list_oscuro": $mdl = "modulo_list_claro";
						($capas == "")? $capas .= "'t_".$ordeninterna['orden_servicio_id']."'": $capas .= ",'t_".$ordeninterna['orden_servicio_id']."'";
						
						$html .= "	<tr height=\"17\" class=\"$mdl\" style=\"cursor:pointer\" onclick=\"MostrarSpan('t_".$ordeninterna['orden_servicio_id']."')\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
						$html .= "		<td>\n";
						$html .= "			<img name=\"Img$i\" height=\"14\" width=\"16\" src=\"".GetThemePath()."/images/abajo.png\" border=\"0\" >\n";
						$html .= "		</td>\n";
						$html .= "		<td class=\"normal_10AN\">".$ordeninterna['orden_servicio_id']."</td>\n";
						$html .= "		<td class=\"normal_10AN\">".$ttl."</td>\n";
						$html .= "		<td class=\"normal_10AN\">".$key0."</td>\n";
						$html .= "	</tr>\n";
						$html .= "	<tr>\n";
						$html .= "		<td colspan=\"4\">\n";
						$html .= "			<div id=\"t_".$ordeninterna['orden_servicio_id']."\" style=\"display:none\">\n";
						$html .= "				<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
						$html .= "					<tr class=\"$est\">\n";
						$html .= "						<td style=\"text-indent:2pt;text-align:left\" width=\"16%\">SERVICIO:</td>\n";
						$html .= "						<td class=\"$mdl\" style=\"text-indent:2pt;text-align:left\">".$ordeninterna['servicio']."</td>\n";
						$html .= "						<td style=\"text-indent:2pt;text-align:left\">F. ACTIVACION:</td>\n";
						$html .= "						<td style=\"text-indent:2pt;text-align:left\" class=\"$mdl\">".$ordeninterna['activacion']."</td>\n";
						$html .= "						<td style=\"text-indent:2pt;text-align:left\">F. VENCIMIENTO:</td>\n";
						$html .= "						<td style=\"text-indent:2pt;text-align:left\" class=\"$mdl\">".$ordeninterna['vencimiento']."</td>\n";
						$html .= "					</tr>\n";
						if($ordeninterna['observacion'])
						{
							$html .= "					<tr class=\"$est\">\n";
							$html .= "						<td>OBSERVACION:</td>\n";
							$html .= "						<td colspan=\"7\" class=\"$mdl\">".$ordeninterna['observacion']."</td>\n";
							$html .= "					</tr>\n";					
						}
						
						if(!empty($oscargosr[$ordeninterna['plan_id']][$key]))
						{
							$html .= "					<tr>\n";
							$html .= "						<td colspan=\"8\">\n";
							$html .= "							<table width=\"100%\" class=\"modulo_table_list\">\n";
							$html .= "								<tr class=\"$est\" >\n";
							$html .= "									<td valign=\"top\" width=\"8%\">Nº SOL</td>\n";
							$html .= "									<td valign=\"top\" width=\"8%\">CUPS</td>\n";
							$html .= "									<td valign=\"top\" width=\"14%\" colspan=\"2\">TAR - CGO</td>\n";
							$html .= "									<td valign=\"top\">DESCRIPCION</td>\n";
							$html .= "								</tr>\n";
							$i = 0;
							//$html .= "<pre>".print_r($cargos,true)."</pre>";
							foreach($oscargosr[$ordeninterna['plan_id']][$key] as $keyC => $cargos)
							{
								$html .= "								<tr class=\"$mdl\">\n";
								$html .= "									<td align=\"center\" >".$cargos['hc_os_solicitud_id']."</td>\n";
								$html .= "									<td class=\"normal_10AN\" style=\"text-align:center; cursor:pointer\" title=\"".$cargos['descripcion_cups']."\">".$cargos['cargo_cups']."</td>\n";
								$html .= "									<td width=\"7%\" align=\"center\">".$cargos['tarifario_id']."</td>\n";
								$html .= "									<td width=\"7%\" align=\"center\">".$cargos['cargo']."</td>\n";
								$html .= "									<td>".$cargos['descripcion_cargo']."</td>\n";
								$html .= "								</tr>\n";
								$i++;
							}
							
							$html .= "							</table>\n";
							$html .= "						</td>\n";
							$html .= "					</tr>\n";
						}
						$html .= "				</table><br>\n";
						$html .= "			</div>\n";
						$html .= "		</td>\n";
						$html .= "	</tr>\n";
					}
				}
			}	
			$html .= "</table>\n";					
			$html .= "<script>\n";
			$html .= "	var capas = new Array(".$capas.")\n";
			$html .= "	function MostrarSpan(id)\n";
			$html .= "	{\n";
			$html .= "		ele = document.getElementById(id);\n";
			$html .= "		if(ele.style.display == 'block')\n";
			$html .= "			ele.style.display = 'none';\n";
			$html .= "		else\n";
			$html .= "			ele.style.display = 'block';\n";
			$html .= "	}\n";
			$html .= "	function mOvr(src,clrOver)\n";
			$html .= "	{\n";
			$html .= "		src.style.background = clrOver;\n";
			$html .= "	}\n";
			$html .= "	function mOut(src,clrIn)\n";
			$html .= "	{\n";
			$html .= "		src.style.background = clrIn;\n";
			$html .= "	}\n";
			$html .= "</script><br>\n";
			$html .= "<form name=\"volver\" action=\"".$action['aceptar']."\" method=\"post\">";
			$html .= "	<table width=\"100%\" align=\"center\">\n";
			$html .= "		</tr>\n";
			$html .= "			<td align=\"center\">\n";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Aceptar\">\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "</form>\n";
			return $html;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function FormaMostrarEventosSoat($action,$paciente,$datos)
		{			
			IncludeClass('ConsultaAutorizacionGrl','','app','Os_CentralAutorizacionGeneral');
			$cns = new ConsultaAutorizacionGrl();
			
			$html  = ThemeAbrirTabla('EVENTOS SOAT RELACIONADOS CON EL PACIENTE');
			$html .= "	<script language=\"javascript\">\n";
			$html .= "		function mOvr(src,clrOver)\n";
			$html .= "		{\n";
			$html .= "			src.style.background = clrOver;\n";
			$html .= "		}\n";
			$html .= "		function mOut(src,clrIn)\n";
			$html .= "		{\n";
			$html .= "			src.style.background = clrIn;\n";
			$html .= "		}\n";
			$html .= "		function EvaluarEvento()\n";
			$html .= "		{\n";
			$html .= "			var radios = document.getElementsByName('evento_soat');\n";
			$html .= "			for(i=0; i< radios.length; i++)\n";
			$html .= "			{\n";
			$html .= "				if(radios[i].checked == true)\n";
			$html .= "				{\n";
			$html .= "					document.eventos_soat.action = \"".$action['aceptar']."\";\n";
			$html .= "					document.eventos_soat.submit();\n";
			$html .= "					return;\n";
			$html .= "				}\n";
			$html .= "			}\n";
			$html .= "			document.getElementById('error').innerHTML = 'NO SE HA SELECCIONADO NINGUN EVENTO PARA EL PACIENTE'\n";
			$html .= "		}\n";
			$html .= "	</script>\n";
			
			$html .= "<table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "	<tr class =\"modulo_table_list_title\">\n";
			$html .= "  	<td class=\"modulo_table_list_title\" width=\"15%\">DOCUMENTO</td>\n";
			$html .= "    <td class=\"modulo_table_list_title\" width=\"10%\">NOMBRE</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr class=\"normal_10AN\">\n";
			$html .= "    <td width=\"25%\" align=\"center\">\n";
			$html .= "     	".$paciente['tipo_id_paciente']." ".$paciente['paciente_id']."</b>\n";
			$html .= "    </td>\n";
			$html .= "    <td width=\"75%\" align=\"center\">\n";
			$html .= "     	".$paciente['primer_nombre']." ".$paciente['segundo_nombre']." ".$paciente['primer_apellido']." ".$paciente['segundo_apellido']."</b>\n";
			$html .= "    </td>\n";
			$html .= "	</tr>\n";
			$html .= "</table><br>\n";
			$html .= "<center>\n";
			$html .= "	<div id=\"error\" class=\"label_error\"></div>\n";
			$html .= "</center>\n";
			
			$evensoat = $cns->ObtenerEventoSoatPaciente($paciente['tipo_id_paciente'],$paciente['paciente_id'],$datos['plan_id']);
			
			if(!empty($evensoat))
			{			
				$html .= "<form name=\"eventos_soat\" action=\"".$action['cancelar']."\" method=\"post\">\n";
				$html .= "	<center>\n";
				$html .= "		<fieldset class=\"fieldset\" style=\"width:90%\">\n";
				$html .= "			<legend>EVENTOS SOAT DEL PACIENTE</legend>\n";
				$html .= "    	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$html .= "    		<tr class=\"modulo_table_list_title\">\n";
				$html .= "    			<td width=\"%\" ></td>\n";
				$html .= "     			<td width=\"9%\">No.</td>\n";
				$html .= "     			<td width=\"9%\" >FECHA</td>\n";
				$html .= "     			<td width=\"9%\" >HORA</td>\n";
				$html .= "     			<td width=\"20%\">POLIZA</td>\n";
				$html .= "     			<td width=\"40%\">ASEGURADORA</td>\n";
				$html .= "     			<td width=\"11%\">SALDO</td>\n";
				$html .= "     		</tr>\n";
	
				$k=1;				
				$estilo = $background = "";
				
				foreach($evensoat as $key => $evento)
				{
					($estilo == "modulo_list_claro")? $estilo = "modulo_list_oscuro":$estilo = "modulo_list_claro";
					($background == "#CCCCCC")?  $background = "#DDDDDD":  $background = "#CCCCCC";
										
					$html .= "      	<tr class=\"".$estilo."\" height=\"21\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF'); align=\"center\">\n";
					$html .= "					<td>\n";
					$html .= "						<input type=\"radio\" name=\"evento_soat\" value=\"".$evento['evento']."\">\n";
					$html .= "					</td>\n";					
					$html .= "					<td >".$evento['evento']."</td>\n";
					$html .= "					<td >".$evento['fecha_accidente']."</td>\n";
					$html .= "					<td >".$evento['hora_accidente']."</td>\n";
					$html .= "					<td >".$evento['poliza']."</td>\n";
					$html .= "					<td >".$evento['nombre_tercero']."</td>\n";
					$html .= "					<td >".number_format(($evento['saldo']), 2, ',', '.')."</td>\n";
					$html .= "				</tr>\n";
				}
				$html .= "			</table>\n";
				$html .= "		</fieldset>\n";
				$html .= "	</center><br>\n";
				$html .= "	<table width=\"50%\" align=\"center\" >\n";
				$html .= "		<tr>\n";
				$html .= "			<td align=\"center\">\n";
				$html .= "				<input class=\"input-submit\" type=\"button\" name=\"Aceptar\" value=\"Aceptar\" onclick=\"EvaluarEvento()\">\n";
				$html .= "			</td>\n";
				$html .= "			<td align=\"center\">\n";
				$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Cancelar\">\n";
				$html .= "			</td>";
				$html .= "  	</tr>\n";
				$html .= "	</table>\n";
				$html .= "</form>\n";
			}
			else
			{
				$html .= "<form name=\"cancelar\" action=\"".$action['cancelar']."\" method=\"post\">\n";
				$html .= "	<table border=\"0\" width=\"90%\" align=\"center\" >\n";
				$html .= "  	<tr>\n";
				$html .= "			<td align=\"center\">\n";
				$html .= "				<b class=\"label_error\">EL PACIENTE NO POSEE UN EVENTO SOAT RELACIONADO, SE DEBE CREAR UN ENVENRTO PARA CONTINUAR</b>\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				$html .= "  </table>\n";
				$html .= "	<table width=\"50%\" align=\"center\" >\n";
				$html .= "		<tr>\n";
				$html .= "			<td align=\"center\">\n";
				$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
				$html .= "			</td>\n";
				$html .= "  	</tr>\n";
				$html .= "	</table>\n";
				$html .= "</form>\n";
			}
			$html .= ThemeCerrarTabla();
			return $html;
		}
	}
?>