<?php
	/**************************************************************************************
	* $Id: ConsultaAutorizacionesHTML.class.php,v 1.3 2009/11/13 12:05:25 hugo Exp $
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	*
	* $Revision: 1.3 $
	* @author Hugo Freddy Manrique Arango
	***************************************************************************************/
	class ConsultaAutorizacionesHTML
	{
		var $frmError = array();
		
		function ConsultaAutorizacionesHTML(){}
		/**********************************************************************************
		*@acess public
		***********************************************************************************/
		function FormaConsultarAutorizaciones($datos,$action,$buscador,$obj)
		{
			IncludeClass("ClaseHTML");
			IncludeClass('ConsultaAutorizaciones','','app','NCAutorizaciones');
			 
			$file = 'app_modules/NCAutorizaciones/RemoteXajax/autorizaciones.php';
			$obj->SetXajax(array("reqMostrarAutorizacion","reqMostrarLista"),$file);

			$caut = new ConsultaAutorizaciones();

			$html  = "<script>\n";
			$html .= "	function acceptNum(evt)\n";
			$html .= "	{\n";
			$html .= "		var nav4 = window.Event ? true : false;\n";
			$html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "		return (key <= 13 ||(key >= 48 && key <= 57));\n";
			$html .= "	}\n";

			$html .= "	function Autorizaciones(numingreso,funcion)\n";
			$html .= "	{\n";
			$html .= "		xajax_reqMostrarAutorizacion(numingreso,funcion);\n";
			$html .= "	}\n";

			$html .= "	function LimpiarCampos(objeto)\n";
			$html .= "	{\n";
			$html .= "		objeto.cuenta.value = '';\n";
			$html .= "		objeto.ingreso.value = '';\n";
			$html .= "		objeto.nombres.value = '';\n";
			$html .= "		objeto.apellidos.value = '';\n";
			$html .= "		objeto.documento.value = '';\n";
			$html .= "		objeto.tipodocumento.selectedIndex = 0;\n";
			$html .= "	}\n";
			$html .= "	function VerLista()\n";
			$html .= "	{\n";
			$html .= "		xajax_reqMostrarLista();\n";
			$html .= "	}\n";

			$html .= "	function CrearAutorizaciones(datos)\n";
			$html .= "	{\n";
			$html .= "		var url=\"".$action['crear']."\"+datos;\n";
			$html .= "		window.open(url,'','width=900,height=550,X=200,Y=0,resizable=no,status=no,scrollbars=yes,location=no');\n";
			$html .= "	}\n";
			$html .= "	function Tabs()\n";
			$html .= "	{\n";
			$html .= "		tabPane = new WebFXTabPane( document.getElementById( \"anteriores\" ), false);";
			$html .= "		tabPane.addTabPage( document.getElementById(\"servicios\"));";
			$html .= "		tabPane.addTabPage( document.getElementById(\"ordenes\"));";
			$html .= "		setupAllTabs();\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			if($buscador)
			{
				$html .= "<form name=\"formabuscar\" action=\"".$action['buscar']."\" method=\"post\" >\n";
				$html .= "	<table width=\"80%\" align=\"center\">\n";
				$html .= "		<tr>\n";
				$html .= "			<td>\n";
				$html .= "				<fieldset><legend class=\"normal_10AN\">BUSCAR AUTORIZACIONES</legend>\n";
				$html .= "					<table width=\"90%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
				$html .= "						<tr class=\"modulo_table_list_title\">\n";
				$html .= "							<td width=\"20%\" style=\"text-indent:8pt;text-align:left\">Nº INGRESO</td>\n";
				$html .= "							<td width=\"30%\" style=\"text-align:left\" class=\"modulo_list_claro\">\n";
				$html .= "								<input class=\"input-text\" type=\"text\" name=\"ingreso\" style=\"width:50%\" onkeypress=\"return acceptNum(event)\" value=\"".$datos['ingreso']."\">\n";
				$html .= "							</td>\n";
				$html .= "							<td width=\"20%\" style=\"text-indent:8pt;text-align:left\">Nº CUENTA</td>\n";
				$html .= "							<td style=\"text-align:left\" class=\"modulo_list_claro\">\n";
				$html .= "								<input class=\"input-text\" type=\"text\" name=\"cuenta\" style=\"width:50%\" onkeypress=\"return acceptNum(event)\" value=\"".$datos['cuenta']."\">\n";
				$html .= "							</td>\n";
				$html .= "						</tr>\n";
				$html .= "						<tr class=\"modulo_table_list_title\">\n";
				$html .= "							<td style=\"text-align:left;text-indent:8pt\">TIPO DOCUMENTO: </td>\n";
				$html .= "							<td align=\"left\" class=\"modulo_list_claro\">\n";
				$html .= "								<select name=\"tipodocumento\" class=\"select\">\n";
				$html .="										<option value=\"0\">---Seleccionar---</option>\n";
				$sel="";
				$tipoId = $caut->ObtenerTipoIdPaciente();
				foreach($tipoId as $value => $dat)
				{
					($datos['tipodocumento'] == $dat['tipo_id_paciente'])? $sel = "selected":$sel = "";
					$html .="										<option value=\"".$dat['tipo_id_paciente']."\" $sel>".$dat['descripcion']."</option>\n";
				}
				$html .= "								</select>\n";
				$html .= "							</td>\n";
				$html .= "							<td style=\"text-align:left;text-indent:8pt\" >DOCUMENTO: </td>\n";
				$html .= "							<td align=\"left\" class=\"modulo_list_claro\">\n";
				$html .= "								<input type=\"text\" class=\"input-text\" name=\"documento\" value=\"".$datos['documento']."\" style=\"width:50%\" >\n";
				$html .= "							</td>\n";
				$html .= "						</tr>\n";
				$html .= "						<tr class=\"modulo_table_list_title\">\n";
				$html .= "							<td style=\"text-indent:8pt;text-align:left\">NOMBRES</td>\n";
				$html .= "							<td style=\"text-align:left\" class=\"modulo_list_claro\">\n";
				$html .= "								<input class=\"input-text\" type=\"text\" name=\"nombres\" style=\"width:100%\" value=\"".$datos['nombres']."\">\n";
				$html .= "							</td>\n";
				$html .= "							<td style=\"text-indent:8pt;text-align:left\">APELLIDOS</td>\n";
				$html .= "							<td style=\"text-align:left\" class=\"modulo_list_claro\">\n";
				$html .= "								<input class=\"input-text\" type=\"text\" name=\"apellidos\" style=\"width:100%\" value=\"".$datos['apellidos']."\">\n";
				$html .= "							</td>\n";
				$html .= "						</tr>\n";
				$html .= "					</table>\n";
				$html .= "					<table width=\"50%\" align=\"center\" height=\"25\">\n";
				$html .= "						<tr>\n";
				$html .= "							<td align=\"center\" >\n";
				$html .= "								<input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"Buscar\">\n";
				$html .= "							</td>\n";
				
				$html .= "							<td align=\"center\" >\n";
				$html .= "								<input class=\"input-submit\" type=\"button\" name=\"limpiar\" value=\"Limpiar Campos\" onclick=\"LimpiarCampos(document.formabuscar)\">\n";
				$html .= "							</td>\n";
				
				$html .= "						</form>\n";
				$html .= "						</tr>\n";
				$html .= "					</table>\n";
				$html .= "				</fieldset>\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				$html .= "	</table>\n";
			}
			else
			{
				$html .= "<input type=\"hidden\" name=\"ingreso\" value=\"".$datos['ingreso']."\">\n";
			}
			//print_r($datos);
			if(!$datos['ingreso'] && !$datos['cuenta'] && !$datos['documento'] && !$datos['nombres'] && !$datos['apellidos'])
			{
				$html .= "<table width=\"50%\" align=\"center\">\n";
				$html .= "	<tr>\n";
				$html .= "		<td align=\"center\" class=\"label_error\">\n";
				$html .= "			NO HAY DATOS PARA MOSTRAR\n";
				$html .= "		</td>\n";
				$html .= "	</tr>\n";
				$html .= "</table>\n";
			}
			else
			{
				$autorizaciones = array();
				if($datos['cuenta']) $datos['ingreso'] = $caut->ObtenerIngresoCuenta($datos['ingreso']);
				
				if($datos['ingreso'])
				{
					$autorizaciones = $caut->ObtenerAutorizaciones($datos);
					
					if(!empty($autorizaciones))
					{
						if(sizeof($autorizaciones) == 1)
						{
							if(!$datos['ingreso'])
							{
								foreach($autorizaciones as $key => $var)
									$datos['ingreso'] = $key;
							}
							$paciente = $caut->ObtenerDatosPaciente($datos['ingreso']);
							$OsAuto   = $caut->ObtenerAutizacionesOS($datos['ingreso'],$paciente['ingreso']);
							$autoriza = $caut->ObtenerAutorizaciones($datos,"'OS'");
						
							$vista = $this->FormaCrearVistaAutorizacion($autoriza,$paciente,$OsAuto );
							$url = UrlRequest($this->envio);
														
							$rpt = new GetReports();
							$mstr = $rpt->GetJavaReport('app','NCAutorizaciones','hojautorizacion',
																					array("ingreso"=>$datos['ingreso']),
																					array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
							$fnc = $rpt->GetJavaFunction();
							$html .= "				".$mstr."\n";
							$html .= "<table align=\"center\" width=\"40%\">\n";
							$html .= "	<tr>\n";
							if($buscador)
							{
								$html .= "		<td>\n";
								//$html .= "			<a href=\"javascript:CrearAutorizaciones('".$url."')\" class=\"label_error\"> CREAR NUEVA AUTORIZACION</a>\n";
								$html .= "			<a href=\"".$action['crear']."".$url."\" target=\"anular\" onclick=\"window.open('".$action['crear']."".$url."','anular','toolbar=no,width=900,height=550,X=200,Y=0,resizable=no,scrollbars=yes').focus(); return false;\" class=\"label_error\"> CREAR NUEVA AUTORIZACION</a>\n";
								$html .= "		</td>\n";
							}
							$html .= "		<td>\n";
							$html .= "			<a href=\"javascript:$fnc\" class=\"label_error\">REPORTE</a>\n";
							$html .= "		</td>\n";
							$html .= "	</tr>\n";
							$html .= "</table><br>\n";
							
							$html .= $vista;
							
						}
						else if(sizeof($autorizaciones) > 0)
						{
							
							$rpt = new GetReports();
							$mstr = $rpt->GetJavaReport('app','NCAutorizaciones','hojautorizacion',
																					array("ingreso"=>''),
																					array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
							$fnc = $rpt->GetJavaFunction();
							$html .= "				".$mstr."\n";
							$html .= "<br>\n";
							$html .= "<div id=\"lista_autorizaciones\" style=\"display:block\">\n";
							$html .= $this->FormaCrearLista($autorizaciones,$action['buscar'],$fnc)."<br>";
							$html .= "		".ClaseHTML::ObtenerPaginado($caut->conteo,$caut->paginaActual,$action['buscar']." ".UrlRequest($datos));
							$html .= "</div>\n";
							$html .= "<div id =\"autorizacion\" style=\"display:none\">HOLA</div>\n";
						}
					}
				}
				else
					{										 $datos[ver_todas] = false;
					if($caut->ObtenerAutorizacionesListaUsaurio())															{
					  $datos[ver_todas] = true;
					}
		 	 			$autorizaciones = $caut->ObtenerAutorizacionesLista($datos);
						if(!empty($autorizaciones))
						{
							$rpt = new GetReports();
							$mstr = $rpt->GetJavaReport('app','NCAutorizaciones','hojautorizacion',
																					array("ingreso"=>''),
																					array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
							$fnc = $rpt->GetJavaFunction();
							$html .= "				".$mstr."\n";
							$html .= "<br>\n";
							$html .= "<div id=\"lista_autorizaciones\" style=\"display:block\">\n";
							$html .= $this->FormaCrearLista($autorizaciones,$action['buscar'],$fnc)."<br>";
							$html .= "		".ClaseHTML::ObtenerPaginado($caut->conteo,$caut->paginaActual,$action['buscar'].UrlRequest($datos));
							$html .= "</div>\n";
							$html .= "<div id =\"autorizacion\" style=\"display:none\">HOLA</div>\n";
						}
					}
				
				if(empty($autorizaciones))
				{
					$html .= "<table width=\"50%\" align=\"center\">\n";
					$html .= "	<tr>\n";
					$html .= "		<td align=\"center\" class=\"label_error\">\n";
					$html .= "			LA BUSQUEDA NO ARROJ0 NINGUN RESULTADO\n";
					$html .= "		</td>\n";
					$html .= "	</tr>\n";
					$html .= "</table>\n";
				}
			}
			
			$html .= "<form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "	<table align=\"center\" >\n";
			$html .= "		<tr>\n";
			$html .= "			<td align=\"center\" >\n";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"Volver\">\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "</form>\n";
			return $html;
		}
		/*************************************************************************************
		* Funcion donde se crea el html para las autorizaciones, pintando los datos del 
		* ingreso y la cuenta, siempre y cuando el uingreso este activo y tenga una cuenta 
		* activa, los adtos de las autorizaciones tanto las comunes, somo las realizadas para
		* las ordenes de servicio
		*
		* @params $ingreso int Numero del ingreso del paciente
		* @params $plan int Numero del plan al cual pertenece el paciente registrado en las os
		* @params $idp char Identificacion del paciente
		* @params $tid char Tipo de Identificacion del paciente
		* @params $action array Arreglo que contiene los datos de los action que se necesitan  
		*					en la forma
		* @returns $html string cadena que contiene el html de la forma de autorizaciones
		***************************************************************************************/
		function FormaCrearVistaAutorizacion($datos,$paciente,$OsAuto)
		{
			IncludeClass("ClaseHTML");
			IncludeClass('Autorizaciones','','app','NCAutorizaciones');
			
			$Afiliado = $paciente['ingreso'];
			$estado ="";
			switch($Afiliado['estado'])
			{
				case '1': $estado = "ACTIVO"; break;
				default: $estado = "INACTIVO"; break;
			}
			
			$this->envio['autorizar']['idp'] = $Afiliado['paciente_id'];
			$this->envio['autorizar']['tipoid'] = $Afiliado['tipo_id_paciente'];
			$this->envio['autorizar']['plan_id'] = $Afiliado['plan_id'];
			$this->envio['autorizar']['ingreso'] = $Afiliado['ingreso'];
			
			$html .= "<table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "	<tr class=\"modulo_table_list_title\">\n";
			$html .= "		<td style=\"text-indent:8pt;text-align:left\">IDENTIFICACIÓN</td>\n";
			$html .= "		<td align=\"left\" class=\"modulo_list_claro\">".$Afiliado['tipo_id_paciente']." ".$Afiliado['paciente_id']."</td>\n";
			$html .= "		<td style=\"text-indent:8pt;text-align:left\">PACIENTE</td>\n";
			$html .= "		<td align=\"left\" colspan=\"3\" class=\"modulo_list_claro\">".$Afiliado['nombre']." ".$Afiliado['apellido']."</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr class=\"modulo_table_list_title\">\n";
			$html .= "		<td style=\"text-indent:8pt;text-align:left\">Nº INGRESO</td>\n";
			$html .= "		<td align=\"left\" class=\"modulo_list_claro\">".$Afiliado['ingreso']."</td>\n";
			$html .= "		<td style=\"text-indent:8pt;text-align:left\">F. INGRESO</td>\n";
			$html .= "		<td align=\"left\" class=\"modulo_list_claro\">".$Afiliado['fecha_ingreso']."</td>\n";
			$html .= "		<td style=\"text-indent:8pt;text-align:left\">ESTADO</td>\n";
			$html .= "		<td align=\"left\" colspan=\"3\" class=\"modulo_list_claro\">".$estado."</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr class=\"modulo_table_list_title\">\n";
			$html .= "		<td style=\"text-indent:8pt;text-align:left\">PLAN</td>\n";
			$html .= "		<td align=\"left\" colspan=\"5\" class=\"modulo_list_claro\">".$Afiliado['plan_descripcion']."</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr class=\"modulo_table_list_title\">\n";
			$html .= "		<td width=\"20%\" style=\"text-indent:8pt;text-align:left\">\n";
			$html .= "			ENTIDAD\n";
			$html .= "		</td>\n";
			$html .= "		<td align=\"left\" colspan=\"5\" class=\"modulo_list_claro\">".$Afiliado['nombre_tercero']."</td >\n";
			$html .= "	</tr>\n";
			
			foreach($paciente['cuentas'] as $key => $cuenta)
			{
				if($cuenta['cuentaestado'] == '1')
				{
					$html .= "	<tr class=\"modulo_table_list_title\">\n";
					$html .= "		<td style=\"text-indent:8pt;text-align:left\">CUENTA Nº</td>\n";
					$html .= "		<td align=\"left\" colspan=\"5\" class=\"modulo_list_claro\">".$cuenta['numerodecuenta']."</td>\n";
					$html .= "	</tr>\n";
					
					if($cuenta['plan_id'] != $Afiliado['plan_id'])
					{
						$html .= "	<tr class=\"modulo_table_list_title\">\n";
						$html .= "		<td style=\"text-indent:8pt;text-align:left\">PLAN CUENTA</td>\n";
						$html .= "		<td align=\"left\" colspan=\"5\" class=\"modulo_list_claro\">".$cuenta['plan_descripcion']."</td>\n";
						$html .= "	</tr>\n";
					}
					
					$html .= "	<tr class=\"modulo_table_list_title\">\n";
					$html .= "		<td style=\"text-indent:8pt;text-align:left\" >TIPO AFILIADO</td>\n";
					$html .= "		<td style=\"text-align:left\" width=\"20%\" class=\"modulo_list_claro\">".$cuenta['tipo_afiliado_nombre']."</td>\n";
					$html .= "		<td style=\"text-indent:8pt;text-align:left\" >RANGO</td>\n";
					$html .= "		<td style=\"text-align:left\" width=\"10%\" class=\"modulo_list_claro\">".$cuenta['rango']."</td>\n";
					$html .= "		<td style=\"text-indent:8pt;text-align:left\">SEMANAS COTIZADAS</td>\n";
					$html .= "		<td style=\"text-align:left\" width=\"10%\" class=\"modulo_list_claro\">".$cuenta['semanas_cotizadas']."</td>\n";
					$html .= "	</tr>\n";
					
					$this->envio['autorizar']['externo']['rango'] = $cuenta['rango'];
					$this->envio['autorizar']['externo']['tipo_afiliado'] = $cuenta['tipo_afiliado_id'];
					$this->envio['autorizar']['externo']['semanas_cotizadas'] = $cuenta['semanas_cotizadas'];
				}
			}
			if(empty($this->envio['autorizar']['externo']))
			{
				$this->envio['autorizar']['externo']['rango'] = $cuenta['rango'];
				$this->envio['autorizar']['externo']['tipo_afiliado'] = $cuenta['tipo_afiliado_id'];
				$this->envio['autorizar']['externo']['semanas_cotizadas'] = $cuenta['semanas_cotizadas'];
			}
			$html .= "</table><br>\n";
			$html .= "			<div class=\"tab-pane\" id=\"anteriores\">\n";
			$html .= "			<script>tabPane = new WebFXTabPane( document.getElementById( \"anteriores\" ), false);</script>\n";
			$html .= "				<div class=\"tab-page\" id=\"servicios\">\n";
			$html .= "					<h2 class=\"tab\">SERVICIOS AUTORIZADOS</h2>\n";
			$html .= "					<script>tabPane.addTabPage( document.getElementById(\"servicios\"));</script>\n";

			if(!empty($datos))
			{
				$html .= "					<table width=\"100%\" class=\"modulo_table_list\">\n";
				$html .= "						<tr class=\"modulo_table_list_title\">\n";
				$html .= "							<td width=\"6%\">Nº</td>\n";
				$html .= "							<td width=\"11%\">F. REGISTRO</td>\n";
				$html .= "							<td width=\"15%\">FUNCIONARIO CLINICA</td>\n";
				$html .= "							<td width=\"17%\">TIPO AUTORIZACION</td>\n";
				$html .= "							<td width=\"16%\">RESPONSABLE / TIPO DOCUMENTO</td>\n";
				$html .= "							<td width=\"12%\">CODIGO AUTORIZACIÓN</td>\n";
				$html .= "							<td width=\"23%\">OBSERVACIONES</td>\n";
				$html .= "						</tr>\n";
				
				$observa = "";
				foreach($datos as $key => $autorizar)
				{
					foreach($autorizar as $keyI => $auto)
					{
						$html .= "						<tr class=\"modulo_table_list_title\">\n";
						$html .= "							<td colspan=\"7\">$keyI</td>\n";
						$html .= "						</tr>\n";
						foreach($auto as $keyII => $autoriza)
						{
							($autoriza['tipo_autorizador'] == 'I')? $tipo_auto = "INTERNA": $tipo_auto = "EXTERNA";
							$html .= "						<tr class=\"modulo_list_claro\">\n";
							$html .= "							<td>".$autoriza['autorizacion']."</td>\n";
							$html .= "							<td align=\"center\">".$autoriza['fecha']."</td>\n";
							$html .= "							<td>".$autoriza['responsable']."</td>\n";
							$html .= "							<td class=\"label_mark\">".$tipo_auto." - ".$autoriza['tipo_autorizacion']."</td>\n";
							$html .= "							<td>".$autoriza['codigo_autorizacion_generador']."</td>\n";
							$html .= "							<td>".$autoriza['codigo_autorizacion']."</td>\n";
							$html .= "							<td>".$autoriza['descripcion_autorizacion']."</td>\n";
							$html .= "						</tr>\n";
							if($autoriza['observaciones'] != "")
								$observa .= "	<li>".$autoriza['observaciones']."</li>\n";
						}
					}
				}
				$html .= "					</table>\n";
				if($observa != "")
				{
					$html .= "					<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
					$html .= "						<tr class=\"modulo_table_list_title\">\n";
					$html .= "							<td >\n";
					$html .= "								OBSERVACIONES GENERALES\n";
					$html .= "							</td>\n";
					$html .= "						</tr>\n";
					$html .= "						<tr class=\"modulo_list_claro\">\n";
					$html .= "							<td >\n";
					$html .= "								<ul>".$observa."</ul>\n";
					$html .= "							</td>\n";
					$html .= "						</tr>\n";
					$html .= "					</table>\n";
				}

			}
			else
			{
				$html .= "					<center><label class=\"label_error\">NO HAY AUTIZACIONES ANTERIORES PARA MOSTRAR</label></center>\n";
			}
			$html .= "					</div>\n";
			
			$html .= "					<div class=\"tab-page\" id=\"ordenes\">\n";
			$html .= "						<h2 class=\"tab\" >ORDENES DE SERVICIO</h2>\n";
			$html .= "						<script>tabPane.addTabPage( document.getElementById(\"ordenes\"));</script>\n";
			
			if(!empty($OsAuto))
			{				
				$html .= "					<table width=\"100%\" class=\"modulo_table_list\">\n";
				$html .= "						<tr class=\"modulo_table_list_title\">\n";
				$html .= "							<td width=\"6%\">Nº</td>\n";
				$html .= "							<td width=\"11%\">F. REGISTRO</td>\n";
				$html .= "							<td width=\"15%\">FUNCIONARIO CLINICA</td>\n";
				$html .= "							<td width=\"17%\">TIPO AUTORIZACION</td>\n";
				$html .= "							<td width=\"16%\">RESPONSABLE / TIPO DOCUMENTO</td>\n";
				$html .= "							<td width=\"12%\">CODIGO AUTORIZACIÓN</td>\n";
				$html .= "							<td width=\"23%\">OBSERVACIONES</td>\n";
				$html .= "						</tr>\n";
				
				$observa = "";
				foreach($OsAuto as $key => $autorizar)
				{
					foreach($autorizar as $keyI => $auto)
					{
						$html .= "						<tr class=\"modulo_table_list_title\">\n";
						$html .= "							<td colspan=\"7\">$keyI</td>\n";
						$html .= "						</tr>\n";

						foreach($auto as $keyI => $autoriza)
						{
							($autoriza['tipo_autorizador'] == 'I')? $tipo_auto = "INTERNA": $tipo_auto = "EXTERNA";
							$html .= "						<tr class=\"modulo_list_claro\">\n";
							$html .= "							<td>".$autoriza['autorizacion']."</td>\n";
							$html .= "							<td align=\"center\">".$autoriza['fecha']."</td>\n";
							$html .= "							<td>".$autoriza['responsable']."</td>\n";
							$html .= "							<td class=\"label_mark\">".$tipo_auto." - ".$autoriza['tipo_autorizacion']."</td>\n";
							$html .= "							<td>".$autoriza['codigo_autorizacion_generador']."</td>\n";
							$html .= "							<td>".$autoriza['codigo_autorizacion']."</td>\n";
							$html .= "							<td>".$autoriza['descripcion_autorizacion']."</td>\n";
							$html .= "						</tr>\n";
							if($autoriza['observaciones'] != "")
								$observa = "	<li>".$autoriza['observaciones']."</li>\n";
						}
					}
				}
				$html .= "					</table>\n";
				if($observa != "")
				{
					$html .= "					<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
					$html .= "						<tr class=\"modulo_table_list_title\">\n";
					$html .= "							<td >\n";
					$html .= "								OBSERVACIONES GENERALES\n";
					$html .= "							</td>\n";
					$html .= "						</tr>\n";
					$html .= "						<tr class=\"modulo_list_claro\">\n";
					$html .= "							<td >\n";
					$html .= "								<ul>".$observa."</ul>\n";
					$html .= "							</td>\n";
					$html .= "						</tr>\n";
					$html .= "					</table>\n";
				}
			}
			else
			{
				$html .= "						<center><label class=\"label_error\">NO HAY AUTIZACIONES ANTERIORES PARA MOSTRAR</label></center>\n";
			}
			$html .= "					</div>\n";
			$html .= "				</div>\n";
			return $html;		
		}
		/**
		* @acess private
		*/
		function FormaCrearLista($datos,$action,$fnc)
		{
			$html .= "	<table width=\"85%\" class=\"modulo_table_list\" align=\"center\">\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td width=\"8%\">INGRESO</td>\n";
			$html .= "			<td width=\"12%\">FECHA</td>\n";
			$html .= "			<td width=\"40%\" colspan=\"2\">PACIENTE</td>\n";
			$html .= "			<td width=\"28%\">PLAN</td>\n";
			$html .= "			<td ></td>\n";
			$html .= "		</tr>\n";
			$tipo_auto = "";
			foreach($datos as $key => $autorizacion)
			{
				if($autorizacion['estado']=='ACTIVO')
				{
					$font = 'blue';
				}
				elseif($autorizacion['estado']=='CERRADO')
				{
					$font = 'red';
				}
				elseif($autorizacion['estado']=='SALIDA')
				{
					$font = 'yellow';
				}
									
					($autorizacion['tipo_autorizador'] == 'E')? $tipo_auto = "EXTERNA":$tipo_auto = "INTERNA";

					$html .= "		<tr class=\"modulo_list_claro\">\n";
					$html .= "			<td class=\"normal_10AN\"><font color='$font'>".$autorizacion['ingreso']."</font></td>\n";
					$html .= "			<td class=\"label\" align=\"center\">".$autorizacion['fecha']."</td>\n";
					$html .= "			<td >".$autorizacion['tipo_id_paciente']." ".$autorizacion['paciente_id']."</td>\n";
					$html .= "			<td >".$autorizacion['nombres']." ".$autorizacion['apellidos']."</td>\n";
					$html .= "			<td >".$autorizacion['plan_descripcion']."</td>\n";
					$html .= "			<td class=\"normal_10AN\">\n";
					//$html .= "				<a title=\"Mostrar Autorizaciones del Ingreso ".$autorizacion['ingreso']."\" href=\"".$action."&ingreso=".$autorizacion['ingreso']."\">\n";
					$html .= "				<a title=\"Mostrar Autorizaciones del Ingreso ".$autorizacion['ingreso']."&nbsp;".$autorizacion['estado']."\" href=\"javascript:Autorizaciones(".$autorizacion['ingreso'].",'".$fnc."')\">\n";
					$html .= "					<img src=\"".GetThemePath()."/images/pcopiar.png\" border=\"0\">VER AUTO\n";
					$html .= "				</a>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>";
				
			}	
			$html .= "	</table>\n";
			return $html;
		}
		/*************************************************************************************
		* Funcion donde se crea el html para las autorizaciones, pintando los datos del 
		* ingreso y la cuenta, siempre y cuando el uingreso este activo y tenga una cuenta 
		* activa, los adtos de las autorizaciones tanto las comunes, somo las realizadas para
		* las ordenes de servicio
		*
		* @params $ingreso int Numero del ingreso del paciente
		* @params $plan int Numero del plan al cual pertenece el paciente registrado en las os
		* @params $idp char Identificacion del paciente
		* @params $tid char Tipo de Identificacion del paciente
		* @params $action array Arreglo que contiene los datos de los action que se necesitan  
		*					en la forma
		* @returns $html string cadena que contiene el html de la forma de autorizaciones
		***************************************************************************************/
		function FormaCrearListaCuentas($ingreso,$action,$datosp)
		{
			IncludeClass('ConsultaAutorizaciones','','app','NCAutorizaciones');
			$ctl = new ConsultaAutorizaciones();
			$paciente = $ctl->ObtenerDatosPaciente($ingreso);	
			
			$Afiliado = $paciente['ingreso'];
			
			$html  = "<script>\n";
			$html .= "	function CerrarVentana(datos)\n";
			$html .= "	{\n";
			$html .= "		window.close();\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			$html .= "<table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "	<tr class=\"modulo_table_list_title\">\n";
			$html .= "		<td style=\"text-indent:8pt;text-align:left\">IDENTIFICACIÓN</td>\n";
			$html .= "		<td align=\"left\" class=\"modulo_list_claro\">".$Afiliado['tipo_id_paciente']." ".$Afiliado['paciente_id']."</td>\n";
			$html .= "		<td style=\"text-indent:8pt;text-align:left\">PACIENTE</td>\n";
			$html .= "		<td align=\"left\" colspan=\"3\" class=\"modulo_list_claro\">".$Afiliado['nombre']." ".$Afiliado['apellido']."</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr class=\"modulo_table_list_title\">\n";
			$html .= "		<td style=\"text-indent:8pt;text-align:left\">Nº INGRESO</td>\n";
			$html .= "		<td align=\"left\" class=\"modulo_list_claro\">".$Afiliado['ingreso']."</td>\n";
			$html .= "		<td style=\"text-indent:8pt;text-align:left\">FECHA REGISTRO</td>\n";
			$html .= "		<td align=\"left\" colspan=\"3\" class=\"modulo_list_claro\">".$Afiliado['fecha_registro']."</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr class=\"modulo_table_list_title\">\n";
			$html .= "		<td style=\"text-indent:8pt;text-align:left\">PLAN</td>\n";
			$html .= "		<td align=\"left\" colspan=\"5\" class=\"modulo_list_claro\">".$Afiliado['plan_descripcion']."</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr class=\"modulo_table_list_title\">\n";
			$html .= "		<td width=\"20%\" style=\"text-indent:8pt;text-align:left\">\n";
			$html .= "			ENTIDAD\n";
			$html .= "		</td>\n";
			$html .= "		<td align=\"left\" colspan=\"5\" class=\"modulo_list_claro\">".$Afiliado['nombre_tercero']."</td >\n";
			$html .= "	</tr>\n";
			$html .= "</table><br>\n";
			
			
			$html .= "<table width=\"90%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "			<fieldset ><legend class=\"normal_10AN\"> SELECCIONAR CUENTA PARA REALIZAR LA AUTORIZACION</legend>\n";
			$html .= "				<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "					<tr class=\"modulo_table_list_title\">\n";
			$html .= "						<td width=\"2%\"></td>\n";
			$html .= "						<td width=\"10%\">CUENTA</td>\n";
			$html .= "						<td>PLAN</td>\n";
			$html .= "						<td width=\"13%\">ESTADO</td>\n";
			$html .= "						<td width=\"14%\">TIPO AFILIADO</td>\n";
			$html .= "						<td width=\"10%\">RANGO</td>\n";
			$html .= "						<td width=\"10%\">SEMANAS</td>\n";
			$html .= "					</tr>\n";
			foreach($paciente['cuentas'] as $key => $cuenta)
			{
				$datosp['plan_id'] = $cuenta['plan_id'];
				$datosp['externo']['rango'] = $cuenta['rango'];
				$datosp['externo']['tipo_afiliado'] = $cuenta['tipo_afiliado_id'];
				$datosp['externo']['semanas_cotizadas'] = $cuenta['semanas_cotizadas'];
				
				$html .= "					<tr class=\"modulo_list_claro\">\n";
				$html .= "						<td >\n";
				$html .= "							<a title=\"Seleccionar Cuenta\" href=\"".$action['aceptar'].UrlRequest($datosp)."\">\n";
				$html .= "								<img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\" width=\"16\" height=\"16\">\n";
				$html .= "							</a>\n";
				$html .= "						</td>\n";
				$html .= "						<td >".$cuenta['numerodecuenta']."</td>\n";
				$html .= "						<td >".$cuenta['plan_descripcion']."</td>\n";
				$html .= "						<td >".$cuenta['desc_estado']."</td>\n";
				$html .= "						<td >".$cuenta['tipo_afiliado_nombre']."</td>\n";
				$html .= "						<td >".$cuenta['rango']."</td>\n";
				$html .= "						<td align=\"right\">".$cuenta['semanas_cotizadas']."</td>\n";
				$html .= "					</tr>\n";					
			}
			$html .= "				</table>\n";
			$html .= "			</fieldset>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table><br>\n";
			
			
			$html .= "<form name=\"volver\" action=\"".$action['cancelar']."\" method=\"post\">\n";
			$html .= "	<table width=\"50%\" align=\"center\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td align=\"center\" >\n";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"cerrar\" value=\"Cerrar\">\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "</form>\n";
			return $html;		
		}
		/*************************************************************************************
		* @access private
		*************************************************************************************/
		function VerificarAutorizacion($datos)
		{
			IncludeClass('ConsultaAutorizaciones','','app','NCAutorizaciones');
			$cnt = new  ConsultaAutorizaciones();
			$planes = $cnt->ObtenerPlanes($datos['plan_id']);
			//$datos['plan_id'] = 247;
			if($planes[$datos['plan_id']]['sw_afiliacion'] == 1)
			{
				if (!IncludeFile("classes/BDAfiliados/BDAfiliados.class.php"))
				{
					$this->frmError['MensajeError']  = "NO SE PUDO INCLUIR : classes/notas_enfermeria/revision_sistemas.class.php";
					return false;
				}
				
				if(!class_exists('BDAfiliados'))
				{
					$this->frmError['MensajeError']  = "NO EXISTE BD AFILIADOS";
					return false;
				}
				
				$class= new BDAfiliados($datos['tipo_id_paciente'],$datos['paciente_id'],$datos['plan_id']);
				$class->GetDatosAfiliado();
				
				if($class->GetDatosAfiliado() == false)
				{
					$this->frmError["MensajeError"] = $class->mensajeDeError;
					//return false;
				}
					
				if(empty($class->salida))
				{
					if($planes[$datos['plan_id']]['sw_autoriza_sin_bd'] == '1')
					{
						$this->frmError["MensajeError"] = "EL PACIENTE NO SE ENCUENTRA REGISTRADO EN LA BASE DE DATOS, NECESITA UNA AUTORIZACIÓN.";
						return true;
					}
					else
					{
						$this->frmError["MensajeError"] = "EL PACIENTE NO SE ENCUENTRA REGISTRADO EN LA BASE DE DATOS DE LA ENTIDAD. NO PUEDE SER AUTORIZADO.";
						return false;
					}
				}
			}
			return true;
		}
    /**
    * Funcion donde se visualiza la informacion de la autorizacion anterior
    *
		* 
		*/
		function FormaInformacionautorizacion($action,$datos)
		{
			$html  = ThemeAbrirTabla("DATOS DE LA AUTORIZACIÓN");
      $html .= "<center>\n";
      $html .= "  <div class=\"normal_10AN\">YA EXISTE AUTORIZACIÓN PARA LA ORDEN DEL PACIENTE, POR FAVOR VERIFIQUE LA INFORMACIÓN</div>\n";
      $html .= "</center>\n";
      $html .= "	<table width=\"70%\" class=\"modulo_table_list\" align=\"center\">\n";
			$html .= "		<tr class=\"formulacion_table_list\">\n";
			$html .= "			<td align=\"left\" width=\"25%\">Nº AUTORIZACIÓN</td>\n";
			$html .= "			<td class=\"modulo_list_claro\" align=\"left\">".$datos['autorizacion']."</td>\n";
			$html .= "			<td align=\"left\" width=\"25%\">FECHA REGISTRO</td>\n";
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\">".$datos['fecha']."</td>\n";
			$html .= "		</tr>\n";     
      $html .= "		<tr class=\"formulacion_table_list\">\n";
			$html .= "			<td align=\"left\">TIPO DE AUTORIZACIÓN</td>\n";
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\">".(($datos['tipo_autorizador'] == "E")? "EXTERNA":"INTERNA")."</td>\n";
			$html .= "			<td align=\"left\">CODIGO AUTOTIZACIÓN</td>\n";
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\">".$datos['codigo_autorizacion_generador']."</td>\n";
			$html .= "		</tr>\n";      
      $html .= "		<tr class=\"formulacion_table_list\">\n";
			$html .= "			<td align=\"left\">PLAN</td>\n";
			$html .= "			<td align=\"left\" colspan=\"3\" class=\"modulo_list_claro\">".$datos['plan_descripcion']."</td>\n";
			$html .= "		</tr>\n";      
      $html .= "		<tr class=\"formulacion_table_list\">\n";
			$html .= "			<td colspan=\"4\">OBSERVACIONES</td>\n";
			$html .= "		</tr>\n";      
      $html .= "		<tr class=\"modulo_list_claro\">\n";
			$html .= "			<td align=\"left\" colspan=\"4\">".$datos['observaciones']."&nbsp;</td>\n";
			$html .= "		</tr>\n";
			$html .= "  </table><br>\n";
      
			$html .= "<table width=\"50%\" align=\"center\">\n";
			$html .= "  <tr>\n";
			$html .= "	  <td align=\"center\" >\n";
      $html .= "      <form name=\"aceptar\" action=\"".$action['aceptar']."\" method=\"post\">\n";
			$html .= "			  <input class=\"input-submit\" type=\"submit\" name=\"nueva\" value=\"Nueva Autorización\">\n";
			$html .= "      </form>\n";
			$html .= "	  </td>\n";			
      $html .= "	  <td align=\"center\" >\n";
      $html .= "      <form name=\"continuar\" action=\"".$action['continuar']."\" method=\"post\">\n";
			$html .= "			  <input class=\"input-submit\" type=\"submit\" name=\"continuar\" value=\"Continuar\">\n";
			$html .= "      </form>\n";
			$html .= "	  </td>\n";     
      $html .= "	  <td align=\"center\" >\n";
      $html .= "      <form name=\"volver\" action=\"".$action['cancelar']."\" method=\"post\">\n";
			$html .= "			  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"Cancelar\">\n";
			$html .= "      </form>\n";
			$html .= "	  </td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
      $html .= ThemeCerrarTabla();
			return $html;
		}
	}
?>
