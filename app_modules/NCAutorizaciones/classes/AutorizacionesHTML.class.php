<?php
	/**************************************************************************************
	* $Id: AutorizacionesHTML.class.php,v 1.7 2007/09/20 18:46:01 hugo Exp $
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* $Revision: 1.7 $ 	
	* @author Hugo Freddy Manrique Arango
	*
	* Manejar la forma HTML del ingreso de autorizaciones
	***************************************************************************************/
	class AutorizacionesHTML
	{
		function AutorizacionesHTML(){}
		/**********************************************************************************
		*@acess private 
		***********************************************************************************/
		function FormaDatosAfiliado($cotizante,$plan,$aut,$unico = false)
	  {
			$sel = "";
			$tipos = $aut->ObtenerTiposAfiliados($plan,$cotizante['tipo_afiliado']);
			$rangos = $aut->ObtenerRangosNiveles($plan);
			$semanas = $cotizante['semanas_cotizadas'];
			
			if($cotizante['rango'] === 0) $cotizante['rango'] = "0";
			
			if(!$semanas) $semanas = 0;
			
			$html .= "<table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "	<tr class=\"modulo_table_list_title\">\n";
			$html .= "		<td width=\"20%\" style=\"text-indent:8pt;text-align:left\" >TIPO AFILIADO</td>\n";
			$html .= "		<td style=\"text-align:left\" width=\"20%\" class=\"modulo_list_claro\">";
			if(sizeof($tipos) > 1 && $unico === false)
			{
				$html .= "			<select name=\"tipoafiliado\" class=\"select\">\n";
				$html .= "				<option value=\"-1\">-- Seleccionar --</option>\n";
				foreach($tipos as $key => $valor)
				{
					($cotizante['tipo_afiliado'] == $valor['tipo_afiliado_id'])? $sel = "selected":$sel = "";
					$html .= "				<option value=\"".$valor['tipo_afiliado_id']."\" $sel>".$valor['tipo_afiliado_nombre']."</option>\n";
				}
				$html .= "			</select>\n";
			}
			else
			{
				$html .= "			".$tipos[0]['tipo_afiliado_nombre']."\n";
				$html .= "			<input type=\"hidden\" name=\"tipoafiliado\" value=\"".$tipos[0]['tipo_afiliado_id']."\">\n";
			}
			$html .= "		</td>\n";
			$html .= "		<td style=\"text-indent:8pt;text-align:left\" >RANGO</td>\n";
			$html .= "		<td style=\"text-align:left\" width=\"10%\" class=\"modulo_list_claro\">";
			
			if(sizeof($rangos) > 1 && $unico === false)
			{
				$html .= "			<select name=\"rango\" class=\"select\">\n";
				$html .= "				<option value=\"\">-- Selecc --</option>\n";
				foreach($rangos as $key => $valor)
				{
					($cotizante['rango'] == $valor['rango'])? $sel = "selected":$sel = "";
					$html .= "				<option value=\"".$valor['rango']."\" $sel>".$valor['rango']."</option>\n";
				}
				$html .= "			</select>\n";
			}
			else
			{
				if(!$cotizante['rango']) $cotizante['rango'] = $rangos[0]['rango'];
				$html .= "			".$cotizante['rango']."\n";
				$html .= "			<input type=\"hidden\" name=\"rango\" value=\"".$cotizante['rango']."\">\n";
			}

			$html .= "		</td>\n";
			$html .= "		<td style=\"text-indent:8pt;text-align:left\">SEMANAS COTIZADAS</td>\n";
			$html .= "		<td style=\"text-align:left\" width=\"10%\" class=\"modulo_list_claro\">\n";

			if($unico === false)
				$html .= "			<input class=\"input-text\" type=\"text\" name=\"Semanas\" style=\"width:100%\" maxlength=\"8\" onkeypress=\"return acceptNum(event)\" value=\"".$semanas."\">\n";
			else
				$html .= "			<input  type=\"hidden\" name=\"Semanas\"  value=\"".$semanas."\"><label class=\"normal_10AN\">".$semanas."</label>\n";

			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
			
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
		function FormaAutorizar($ingreso,$plan,$idp,$tid,$action)
		{
			IncludeClass("ClaseHTML");
			IncludeClass('Autorizaciones','','app','NCAutorizaciones');
			
			$request = $_REQUEST;
			$aut = new Autorizaciones();
			$tipos = $aut->TiposAutorizacion();
			$autin = $aut->ObtenerUsuariosAutorizacion($plan);
			if($ingreso)
			{
				$OsAuto = $aut->ObtenerAutizacionesOS($ingreso,$request['offset'],$idp,$tid);
				$datos = $aut->GetDatosPaciente($ingreso);
			}
			
			$Afiliado = $datos['ingreso'];
			$estado ="";
			switch($Afiliado['estado'])
			{
				case '1': $estado = "ACTIVO"; break;
				default: $estado = "INACTIVO"; break;
			}
			
			$html .= "	<br>\n";
			$html .= "	<script>\n";
			$html .= "		var tam = ".sizeof($autin).";\n";
			$html .= "		var tam = ".sizeof($autin).";\n";
			$html .= "		var capas = new Array();\n";
			$html .= "		capas[0] = '-';\n";
			$html .= "		capas[1] = 'telefonica';\n";
			$html .= "		capas[2] = 'escrita_electro';\n";
			$html .= "		capas[3] = '-';\n";
			$html .= "		capas[4] = 'interna';\n";
			$html .= "		capas[5] = 'escrita_electro';\n";
			$html .= "		capas[6] = 'certificado';\n";
			$html .= "		function acceptDate(evt)\n";
			$html .= "		{\n";
			$html .= "			var nav4 = window.Event ? true : false;\n";
			$html .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "			return (key <= 13 ||(key >= 47 && key <= 57));\n";
			$html .= "		}\n";
			$html .= "		function acceptNum(evt)\n";
			$html .= "		{\n";
			$html .= "			var nav4 = window.Event ? true : false;\n";
			$html .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "			return (key <= 13 ||(key >= 48 && key <= 57));\n";
			$html .= "		}\n";
			$html .= "		function MostrarOpciones(id)\n";
			$html .= "		{\n";
			$html .= "			var ele = document.getElementById(capas[id]);\n";
			$html .= "			if(id == 2) id = 5;\n";
			$html .= "			for(i=0; i<capas.length; i++)\n";
			$html .= "			{\n";
			$html .= "				try\n";
			$html .= "				{\n";
			$html .= "					if(i == id)\n";
			$html .= "						ele.style.display = 'block';\n";
			$html .= "					else\n";
			$html .= "						document.getElementById(capas[i]).style.display = 'none';\n";
			$html .= "				}\n";
			$html .= "				catch(error){}\n";
			$html .= "			}\n";
			$html .= "			var desplegar = 'block';\n";
			$html .= "			if((tam == 0 && id == 4)|| id == 0) desplegar = 'none';\n";
			$html .= "			document.getElementById('observacion').style.display = desplegar;\n";
			$html .= "		}\n";
			$html .= "	</script>\n";			
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
			$html .= "		<td style=\"text-indent:8pt;text-align:left\">ESTADO</td>\n";
			$html .= "		<td align=\"left\" colspan=\"3\" class=\"modulo_list_claro\">".$estado."</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr class=\"modulo_table_list_title\">\n";
			$html .= "		<td width=\"20%\" style=\"text-indent:8pt;text-align:left\">\n";
			$html .= "			RESPONSABLE\n";
			$html .= "		</td>\n";
			$html .= "		<td align=\"left\" colspan=\"5\" class=\"modulo_list_claro\">".$Afiliado['nombre_tercero']."</td >\n";
			$html .= "	</tr>\n";
			$html .= "	<tr class=\"modulo_table_list_title\">\n";
			$html .= "		<td style=\"text-indent:8pt;text-align:left\">PLAN</td>\n";
			$html .= "		<td align=\"left\" colspan=\"5\" class=\"modulo_list_claro\">".$Afiliado['plan_descripcion']."</td>\n";
			$html .= "	</tr>\n";
			
			foreach($datos['cuentas'] as $key => $cuenta)
			{
				if($cuenta['estado'] == '1')
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
				}
			}
			$html .= "</table><br>\n";
			
			$html .= "	<table width=\"100%\" align=\"center\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td>\n";
			$html .= "				<table width=\"100%\" align=\"center\">\n";
			$html .= "					<tr>\n";
			$html .= "						<td>\n";
			$html .= "							<div class=\"tab-pane\" id=\"grupos\">\n";
			$html .= "								<script>	tabPane = new WebFXTabPane( document.getElementById( \"grupos\" ), false); </script>\n";
			$html .= "								<div class=\"tab-page\" id=\"autorizar\">\n";
			$html .= "									<h2 class=\"tab\">INGRESAR NUEVA AUTORIZACIÓN</h2>\n";
			$html .= "									<script>	tabPane.addTabPage( document.getElementById(\"autorizar\")); </script>\n";
			$html .= "									<div id=\"error\" ></div>\n";
			$html .= "									<table width=\"80%\" align=\"center\">\n";
			$html .= "										<tr>\n";
			$html .= "											<td colspan=\"2\">\n";
			$html .= "												<table width=\"100%\" class=\"modulo_table_list\" align=\"center\">\n";
			$html .= "													<tr class=\"modulo_table_list_title\">\n";
			$html .= "														<td width=\"28%\">TIPO DE AUTORIZACIÓN</td>\n";
			$html .= "														<td class=\"modulo_list_claro\" colspan=\"4\" align=\"left\">\n";
			$html .= "															<select name=\"tipoautoriza\" class=\"select\" onChange=\"MostrarOpciones(this.value)\">\n";
			$html .= "																<option value=\"0\">---SELECCIONAR---</option>\n";
			
			foreach($tipos as $key => $tipo)
				$html .= "																<option value=\"".$tipo['tipo_autorizacion']."\">".$tipo['descripcion']."</option>\n";
			
			$html .= "															</select>\n";
			$html .= "														</td>\n";
			$html .= "													</tr>\n";
			$html .= "													<tr class=\"modulo_table_list_title\">\n";
			$html .= "														<td style=\"text-indent:8pt;text-align:left\">FECHA AUTORIZACIÓN:</td>\n";
			$html .= "														<td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "															<input type=\"text\" class=\"input-text\" name=\"fecha\" maxlength=\"10\" size=\"11\" value=\"".date("d/m/Y")."\" onkeypress=\"return acceptDate(event)\">\n";
			$html .= "														</td >\n";
			$html .= "														<td width=\"20%\" class=\"modulo_list_claro\">\n";
			$html .= "															<b>".ReturnOpenCalendario('ingresar_auto','fecha','/')."</b>\n";
			$html .= "														</td>\n";
			$html .= "														<td width=\"26%\" style=\"text-indent:8pt;text-align:left\">HORA AUTORIZACIÓN:</td>\n";
			$html .= "														<td colspan=\"2\" align=\"center\" class=\"modulo_list_claro\">\n";
			$html .= "															<input type=\"text\" maxlength=\"2\" name=\"hora\" class=\"input-text\" style=\"width:40%\" onkeypress=\"return acceptNum(event)\" value=\"".date("H")."\"> :\n";
			$html .= "															<input type=\"text\" maxlength=\"2\" name=\"minuto\" class=\"input-text\" style=\"width:40%\"  onkeypress=\"return acceptNum(event)\" value=\"".date("i")."\">\n";
			$html .= "														</td>\n";
			$html .= "													</tr>\n";
			
			$html .= "												</table>\n";
			$html .= "											</td>\n";
			$html .= "										</tr>\n";
			$html .= "										<tr>\n";
			$html .= "											<td colspan=\"2\">\n";
			$html .= "												<form name=\"ingresar_auto\" action=\"\" method=\"post\">\n";
			$html .= "													<div id=\"interna\" style=\"display:none\">\n";
			if(sizeof($autin) > 0)
			{
				$html .= "														<table width=\"100%\" class=\"modulo_table_list\">\n";
				$html .= "															<tr>\n";
				$html .= "																<td colspan=\"4\" class=\"modulo_table_list_title\">AUDITORES INTERNOS</td>\n";
				$html .= "															</tr>\n";
				for($i=0; $i<sizeof($autin); $i++)
				{
					$html .= "															<tr class=\"modulo_list_claro\">\n";
					$html .= "																<td width=\"2%\"><input type=\"radio\" name=\"auditor_interno\" value=\"".$autin[$i]['usuario_id']."\"></td>\n";
					$html .= "																<td width=\"48%\" class=\"normal_10AN\">".$autin[$i++]['nombre']."</td>\n";
					if($autin[$i]['nombre'])
					{
						$html .= "																<td width=\"2%\"><input type=\"radio\" name=\"auditor_interno\" value=\"".$autin[$i]['usuario_id']."\"></td>\n";
						$html .= "																<td class=\"normal_10AN\">".$autin[$i]['nombre']."</td>\n";
					}
					else
						$html .= "																<td colspan=\"2\">&nbsp;</td>\n";
					$html .= "															</tr>\n";
				}
				$html .= "														</table>\n";
			}
			else
			{
				$html .= "														<table width=\"100%\" class=\"modulo_table_list\">\n";
				$html .= "															<tr class=\"label_error\" align=\"center\">\n";
				$html .= "																<td>NO EXISTEN AUDITORES INTERNOS PARA ESTE PLAN</td>\n";
				$html .= "															</tr>\n";
				$html .= "														</table>\n";
			}
			$html .= "													</div>\n";
			$html .= "													<div id=\"telefonica\" style=\"display:none\">\n";
			$html .= "														<table width=\"100%\" class=\"modulo_table_list\">\n";
			$html .= "															<tr class=\"modulo_table_list_title\">\n";
			$html .= "																<td width=\"28%\" style=\"text-indent:8pt;text-align:left\">CODIGO AUTORIZACIÓN:</td>\n";
			$html .= "																<td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "																	<input type=\"text\" name=\"codigoau\" class=\"input-text\" style=\"width:47%\">\n";
			$html .= "																</td>\n";
			$html .= "															</tr>\n";
			$html .= "															<tr class=\"modulo_table_list_title\">\n";
			$html .= "																<td style=\"text-indent:8pt;text-align:left\">RESPONSABLE</td>\n";
			$html .= "																<td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "																	<input type=\"text\" name=\"responsable\" class=\"input-text\" style=\"width:47%\">\n";
			$html .= "																</td>\n";
			$html .= "															</tr>\n";
			$html .= "														</table>\n";
			$html .= "													</div>\n";
			$html .= "													<div id=\"escrita_electro\" style=\"display:none\">\n";
			$html .= "														<table width=\"100%\" class=\"modulo_table_list\">\n";
			$html .= "															<tr class=\"modulo_table_list_title\">\n";
			$html .= "																<td width=\"28%\" style=\"text-indent:8pt;text-align:left\">CODIGO AUTORIZACIÓN:</td>\n";
			$html .= "																<td width=\"20%\" colspan=\"2\" align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "																	<input type=\"text\" name=\"codigoau\" class=\"input-text\" style=\"width:100%\">\n";
			$html .= "																</td>\n";
			$html .= "																<td width=\"14%\" style=\"text-indent:8pt;text-align:left\">VALIDEZ:</td>\n";
			$html .= "																<td width=\"%\" class=\"modulo_list_claro\" align=\"right\">\n";
			$html .= "																	<input type=\"text\" class=\"input-text\" name=\"fecha\" maxlength=\"10\" size=\"11\" value=\"".$this->Fecha."\" onkeypress=\"return acceptDate(event)\">\n";
			$html .= "																</td >\n";
			$html .= "																<td width=\"20%\" class=\"modulo_list_claro\">\n";
			$html .= "																	<b>".ReturnOpenCalendario('ingresar_auto','fecha','/')."</b>\n";
			$html .= "																</td>\n";			
			$html .= "															</tr>\n";
			$html .= "														</table>\n";
			$html .= "													</div>\n";
			
			$html .= "													<div id=\"certificado\" style=\"display:none\">\n";
			$html .= "														<table width=\"100%\" class=\"modulo_table_list\">\n";
			$html .= "															<tr class=\"modulo_table_list_title\">\n";
			$html .= "																<td width=\"28%\" style=\"text-indent:8pt;text-align:left\">CODIGO AUTORIZACIÓN:</td>\n";
			$html .= "																<td width=\"20%\" align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "																	<input type=\"text\" name=\"codigoau\" class=\"input-text\" style=\"width:100%\">\n";
			$html .= "																</td>\n";
			$html .= "																<td width=\"14%\" style=\"text-indent:8pt;text-align:left\">VALIDEZ:</td>\n";
			$html .= "																<td align=\"right\" class=\"modulo_list_claro\">\n";
			$html .= "																	<input type=\"text\" class=\"input-text\" name=\"fechac\" maxlength=\"10\" size=\"11\" value=\"".$this->Fecha."\" onkeypress=\"return acceptDate(event)\">\n";
			$html .= "																</td >\n";
			$html .= "																<td width=\"20%\" class=\"modulo_list_claro\">\n";
			$html .= "																	<b>".ReturnOpenCalendario('ingresar_auto','fechac','/')."</b>\n";
			$html .= "																</td>\n";			
			$html .= "															</tr>\n";
			$html .= "															<tr class=\"modulo_table_list_title\">\n";
			$html .= "																<td style=\"text-indent:8pt;text-align:left\">RESPONSABLE</td>\n";
			$html .= "																<td align=\"left\" class=\"modulo_list_claro\" colspan=\"4\">\n";
			$html .= "																	<input type=\"text\" name=\"responsable\" class=\"input-text\" style=\"width:72%\">\n";
			$html .= "																</td>\n";
			$html .= "															</tr>\n";
			$html .= "														</table>\n";
			$html .= "													</div>\n";
			$html .= "													<div id=\"observacion\" style=\"display:none\">\n";
			$html .= "														<table width=\"100%\" class=\"modulo_table_list\">\n";
			$html .= "															<tr class=\"modulo_table_list_title\">\n";
			$html .= "																<td >OBSERVACION</td>\n";
			$html .= "															</tr>\n";
			$html .= "															<tr class=\"modulo_table_list_title\">\n";
			$html .= "																<td class=\"modulo_list_claro\">\n";
			$html .= "																	<textarea name=\"observacion\" class=\"textarea\" style=\"width:100%\" rows=\"3\" ></textarea>\n";
			$html .= "																</td>\n";
			$html .= "															</tr>\n";
			$html .= "														</table>\n";
			$html .= "													</div>\n";
			$html .= "												</form>\n";
			$html .= "											</td>\n";
			$html .= "										</tr>\n";
			$html .= "									</table>\n";
			$html .= "								</div>\n";
			$html .= "								<div class=\"tab-page\" id=\"anteriores\">\n";
			$html .= "									<h2 class=\"tab\">AUTORIZACIONES REALIZADAS</h2>\n";
			$html .= "									<script>	tabPane.addTabPage( document.getElementById(\"anteriores\")); </script>\n";
			
			$html .= "									<div class=\"tab-pane\" id=\"gautorizaciones\">\n";
			$html .= "										<div class=\"tab-page\">\n";
			$html .= "											<h2 class=\"tab\">SERVICIOS AUTORIZADOS</h2>\n";
			if(!empty($Auto))
			{
				$html .= "												<table width=\"100%\" class=\"modulo_table_list\">\n";
				$html .= "													<tr class=\"modulo_table_list_title\">\n";
				$html .= "														<td width=\"12%\">Nº AUTORIZA</td>\n";
				$html .= "														<td width=\"17%\">FECHA REGISTRO</td>\n";
				$html .= "														<td>FUNCIONARIO CLINICA</td>\n";
				$html .= "														<td>TIPO AUTORIZACION</td>\n";
				$html .= "														<td width=\"30%\">OBSERVACIONES</td>\n";
				$html .= "													</tr>\n";
				foreach($Auto as $key => $autoriza)
				{
					$html .= "													<tr class=\"modulo_list_claro\">\n";
					$html .= "														<td>".$autoriza['autorizacion']."</td>\n";
					$html .= "														<td align=\"center\">".$autoriza['fecha']."</td>\n";
					$html .= "														<td>".$autoriza['funcionario']."</td>\n";
					$html .= "														<td class=\"label_mark\">".$autoriza['tipo']."</td>\n";
					$html .= "														<td>".str_replace("|","<br>",$autoriza['observacionesi'])."</td>\n";
					$html .= "													</tr>\n";
				}
				$html .= "												</table>\n";
			}
			else
			{
				$html .= "												<center><label class=\"label_error\">NO HAY AUTIZACIONES ANTERIORES PARA MOSTRAR</label></center>\n";
			}
			$html .= "										</div>\n";
			
			$html .= "										<div class=\"tab-page\">\n";
			$html .= "											<h2 class=\"tab\">ORDENES DE SERVICIO</h2>\n";
			if(!empty($OsAuto))
			{				
				$html .= "												<table width=\"100%\" class=\"modulo_table_list\">\n";
				$html .= "													<tr class=\"modulo_table_list_title\">\n";
				$html .= "														<td width=\"10%\">Nº AUTORIZA</td>\n";
				$html .= "														<td width=\"12%\">FECHA</td>\n";
				$html .= "														<td width=\"10%\">Nº ORDEN</td>\n";
				$html .= "														<td width=\"10%\">ESTADO ORDEN</td>\n";
				$html .= "														<td width=\"20%\">DEPARTAMENTO</td>\n";
				$html .= "														<td width=\"20%\">FUNCIONARIO CLINICA</td>\n";
				$html .= "														<td>OBSERVACION</td>\n";
				$html .= "													</tr>\n";
				foreach($OsAuto as $key => $autoriza)
				{
					$html .= "													<tr class=\"modulo_list_claro\">\n";
					$html .= "														<td>".$autoriza['autorizacion']."</td>\n";
					$html .= "														<td align=\"center\">".$autoriza['fecha_autorizacion']."</td>\n";
					$html .= "														<td >".$autoriza['orden_servicio_id']."</td>\n";
					$html .= "														<td >".$autoriza['sw_estado']."</td>\n";
					$html .= "														<td class=\"normal_10AN\">".$autoriza['deptno_descripcion']."</td>\n";
					$html .= "														<td >".$autoriza['funcionario_registra']."</td>\n";
					$html .= "														<td >".$autoriza['observaciones']."</td>\n";
					$html .= "													</tr>\n";
				}
				$html .= "												</table>\n";
				$Paginador = new ClaseHTML();
				$html .= "		".$Paginador->ObtenerPaginado($aut->conteo,$aut->pagina,$action['buscarOs']."&grupo=1",25);
			}
			else
			{
				$html .= "												<center><label class=\"label_error\">NO HAY AUTIZACIONES ANTERIORES PARA MOSTRAR</label></center>\n";
			}
			$html .= "										</div>\n";
			$html .= "									</div>\n";
			$html .= "								</div>\n";
			$html .= "							</div>\n";
			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "				</table>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "</table>\n";
			$html .= "<table align=\"center\" width=\"60%\">\n";			
			$html .= "	<tr>\n";
			$html .= "		<td align=\"center\">\n";
			$html .= "			<input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Autorizar\">\n";
			$html .= "		</td>\n";
			$html .= "		<td align=\"center\">\n";
			$html .= "			<input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Autorizar Y Cumplir\">\n";
			$html .= "		</td>\n";
			$html .= "		<form name=\"cancelar\" action=\"".$action['cancelar']."\">\n";
			$html .= "			<td align=\"center\">\n";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"Cancelar\">\n";
			$html .= "			</td>\n";
			$html .= "		</form>\n";
			$html .= "	</tr>\n";
			$html .= "</table><br>\n";
			$grupo = 0;
			if($request['grupo']) $grupo = $request['grupo'];
			$html .= "<script type=\"text/javascript\">\n";
			$html .= "	setupAllTabs();\n";
			$html .= "	tabPane.setSelectedIndex(".$grupo.");";
			$html .= "</script>\n";
			return $html;		
		}
		/*************************************************************************************
		*
		* @params $plan int Numero del plan al cual pertenece el paciente registrado en las os
		* @params $idp char Identificacion del paciente
		* @params $tid char Tipo de Identificacion del paciente
		* @params $action array Arreglo que contiene los datos de los action que se necesitan  
		*					en la forma
		* @returns $html string cadena que contiene el html de la forma de autorizaciones
		***************************************************************************************/
		function FormaAutorizarPaciente($plan,$idp,$tid,$action,$rango = array(),$externa = array())
		{
			IncludeClass("ClaseHTML");
			IncludeClass('Autorizaciones','','app','NCAutorizaciones');
			$aut = new Autorizaciones();
			$tipos = $aut->TiposAutorizacion();
			$autin = $aut->ObtenerUsuariosAutorizacion($plan);
			
			$unico = false;
			$planes = $aut->ObtenerDatosPlan($plan);
			$paciente = $aut->OtenerDatosPacienteXId($tid,$idp);
			$style = " style=\"text-indent:8pt;text-align:left\" ";
			
			$html .= "	<br>\n";
			$html .= "	<script>\n";
			$html .= "		var autorizar;\n";
			$html .= "		function ContinuarAutorizacion()\n";
			$html .= "		{\n";
			$html .= "			objeto = document.ingresar_auto;\n";
			$html .= "			objeto.action = \"".$action['aceptar']."\"+'&autorizar='+autorizar; \n";
			$html .= "			objeto.submit();\n";
			$html .= "		}\n";
			$html .= "		function acceptDate(evt)\n";
			$html .= "		{\n";
			$html .= "			var nav4 = window.Event ? true : false;\n";
			$html .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "			return (key <= 13 ||(key >= 47 && key <= 57));\n";
			$html .= "		}\n";
			$html .= "		function Evaluar(objeto,estado)\n";
			$html .= "		{\n";
			$html .= "			autorizar = estado;\n";
			$html .= "			datos = new Array();\n";
			$html .= "			if(objeto.tipo_autorizacion[0].checked)\n";
			$html .= "			{\n";
			$html .= "				datos[0] = objeto.tipo_autorizacion[0].value;\n";
			$html .= "				datos[1] = objeto.tipoautoriza_interna.value;\n";
			$html .= "			}\n";
			$html .= "			else\n";
			$html .= "			{\n";
			$html .= "				datos[0] = objeto.tipo_autorizacion[1].value;\n";
			$html .= "				datos[1] = objeto.tipoautoriza_externa.value;\n";
			$html .= "			}\n";
			$html .= "			datos[2] = objeto.fecha.value\n";
			$html .= "			datos[3] = objeto.hora.value+':'+objeto.minuto.value;\n";
			$html .= "			datos[4] = objeto.tipoafiliado.value;\n";
			$html .= "			datos[5] = objeto.rango.value;\n";
			$html .= "			datos[6] = objeto.fecha_validez.value;\n";
			
			$html .= "			xajax_reqEvaluarAutorizacion(datos[0],datos[1],datos[2],datos[3],datos[4],datos[5],datos[6]);\n";
			$html .= "		}\n";
			$html .= "		function acceptNum(evt)\n";
			$html .= "		{\n";
			$html .= "			var nav4 = window.Event ? true : false;\n";
			$html .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "			return (key <= 13 ||(key >= 48 && key <= 57));\n";
			$html .= "		}\n";
			$html .= "		function noAcceptSpace(evt)\n";
			$html .= "		{\n";
			$html .= "			var nav4 = window.Event ? true : false;\n";
			$html .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "			return (key != 32);\n";
			$html .= "		}\n";
			$html .= "		function CerrarVentana(obj)\n";
			$html .= "		{\n";
			$html .= "			window.close();\n";
			$html .= "		}\n";
			$html .= "		function ActivarSelect(id)\n";
			$html .= "		{\n";
			$html .= "			objeto = document.ingresar_auto;\n";
			$html .= "			switch(id)\n";
			$html .= "			{\n";
			$html .= "				case 'I':\n";
			$html .= "					objeto.tipoautoriza_interna.disabled = false;\n";
			$html .= "					objeto.tipoautoriza_interna.style.background = '#FFFFFF';\n";
			$html .= "					objeto.tipoautoriza_externa.disabled = true;\n";
			$html .= "					objeto.tipoautoriza_externa.selectedIndex = 0;\n";
			$html .= "					objeto.tipoautoriza_externa.style.background = '#EFEFEF';\n";			
			$html .= "				break;\n";
			$html .= "				case 'E':\n";
			$html .= "					objeto.tipoautoriza_externa.disabled = false;\n";
			$html .= "					objeto.tipoautoriza_externa.style.background = '#FFFFFF';\n";
			$html .= "					objeto.tipoautoriza_interna.disabled = true;\n";
			$html .= "					objeto.tipoautoriza_interna.style.background = '#EFEFEF';\n";
			$html .= "					objeto.tipoautoriza_interna.selectedIndex = 0;\n";
			$html .= "				break;\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "	</script>\n";

			if($rango)
			{
				$html .= "  <center class=\"label_error\">\n";
				$html .= "		".RetornarWinOpenDatosBD($paciente['tipo_id_paciente'],$paciente['paciente_id'],$plan)."\n";
				$html .= "	</center><br>";
			}
			else if(!empty($externa))
			{
				$unico = true;
				$rango = $externa;
			}
				
			$html .= "<table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "	<tr class=\"modulo_table_list_title\">\n";
			$html .= "		<td width=\"15%\" style=\"text-indent:8pt;text-align:left\">IDENTIFICACIÓN</td>\n";
			$html .= "		<td align=\"left\" class=\"modulo_list_claro\">".$paciente['tipo_id_paciente']." ".$paciente['paciente_id']."</td>\n";
			$html .= "		<td style=\"text-indent:8pt;text-align:left\">PACIENTE</td>\n";
			$html .= "		<td align=\"left\" colspan=\"3\" class=\"modulo_list_claro\">".$paciente['nombre']." ".$paciente['apellido']."</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr class=\"modulo_table_list_title\">\n";
			$html .= "		<td width=\"20%\" style=\"text-indent:8pt;text-align:left\">\n";
			$html .= "			ENTIDAD\n";
			$html .= "		</td>\n";
			$html .= "		<td align=\"left\" colspan=\"5\" class=\"modulo_list_claro\">".$planes['nombre_tercero']."</td >\n";
			$html .= "	</tr>\n";
			$html .= "	<tr class=\"modulo_table_list_title\">\n";
			$html .= "		<td style=\"text-indent:8pt;text-align:left\">PLAN</td>\n";
			$html .= "		<td align=\"left\" colspan=\"5\" class=\"modulo_list_claro\">".$planes['plan_descripcion']."</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table><br>\n";
			$html .= "<form name=\"ingresar_auto\" action=\"\" method=\"post\">\n";
			
			$html .= $this->FormaDatosAfiliado($rango,$plan,$aut,$unico);
			
			$html .= "	<table width=\"82%\" align=\"center\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td align=\"center\">\n";
			$html .= "				<div id=\"error\" style=\"width:80%\" class=\"label_error\"><br></div>\n";
			$html .= "			<td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr>\n";
			$html .= "			<td>\n";
			$html .= "				<table width=\"100%\" align=\"center\">\n";
			$html .= "					<tr>\n";
			$html .= "						<td>\n";
			$html .= "							<fieldset><legend class=\"normal_10AN\">INGRESAR NUEVA AUTORIZACIÓN</legend>\n";
			$html .= "									<table width=\"100%\" align=\"center\">\n";
			$html .= "										<tr>\n";
			$html .= "											<td colspan=\"2\">\n";
			$html .= "												<table width=\"98%\" class=\"modulo_table_list\" align=\"center\">\n";
			$html .= "													<tr class=\"modulo_table_list_title\">\n";
			$html .= "														<td colspan=\"5\">TIPO DE AUTORIZACIÓN</td>\n";
			$html .= "													</tr>\n";
			$html .= "													<tr class=\"modulo_table_list_title\">\n";
			$html .= "														<td $style >\n";
			$html .= "															<input type=\"radio\" name=\"tipo_autorizacion\" onclick=\"ActivarSelect(this.value)\" value=\"I\">INTERNA\n";
			$html .= "														</td>\n";
			$html .= "														<td class=\"modulo_list_claro\" colspan=\"4\" align=\"left\">\n";
			$html .= "															<select name=\"tipoautoriza_interna\" class=\"select\" style=\"background:#EFEFEF;font-weight:bold;\" disabled >\n";
			$html .= "																<option value=\"0\">---SELECCIONAR---</option>\n";
			
			foreach($tipos['1'] as $key => $tipo)
				$html .= "																<option value=\"".$tipo['tipo_autorizacion']."\">".$tipo['descripcion']."</option>\n";
			
			$html .= "															</select>\n";
			$html .= "														</td>\n";
			$html .= "													</tr>\n";
			
			$html .= "													<tr class=\"modulo_table_list_title\">\n";
			$html .= "														<td $style >\n";
			$html .= "															<input type=\"radio\" name=\"tipo_autorizacion\" value=\"E\" onclick=\"ActivarSelect(this.value)\">EXTERNA\n";
			$html .= "														</td>\n";
			$html .= "														<td class=\"modulo_list_claro\" colspan=\"4\" align=\"left\">\n";
			$html .= "															<select name=\"tipoautoriza_externa\" class=\"select\" style=\"background:#EFEFEF;font-weight:bold;\" disabled>\n";
			$html .= "																<option value=\"0\">---SELECCIONAR---</option>\n";
			
			foreach($tipos['0'] as $key => $tipo)
				$html .= "																<option value=\"".$tipo['tipo_autorizacion']."\">".$tipo['descripcion']."</option>\n";
			
			$html .= "															</select>\n";
			$html .= "														</td>\n";
			$html .= "													</tr>\n";
			$html .= "													<tr class=\"modulo_table_list_title\">\n";
			$html .= "														<td width=\"33%\" style=\"text-indent:8pt;text-align:left\">FECHA AUTORIZACIÓN:</td>\n";
			$html .= "														<td width=\"15%\" align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "															<input type=\"text\" class=\"input-text\" name=\"fecha\" maxlength=\"10\" style=\"width:100%\" value=\"".date("d/m/Y")."\" onkeypress=\"return acceptDate(event)\">\n";
			$html .= "														</td >\n";
			$html .= "														<td width=\"20%\" class=\"modulo_list_claro\" align=\"left\">\n";
			$html .= "															<b>".ReturnOpenCalendario('ingresar_auto','fecha','/')."</b>\n";
			$html .= "														</td>\n";
			$html .= "														<td width=\"16%\" style=\"text-indent:8pt;text-align:left\">HORA:</td>\n";
			$html .= "														<td align=\"center\" class=\"modulo_list_claro\">\n";
			$html .= "															<input type=\"text\" maxlength=\"2\" name=\"hora\" class=\"input-text\" style=\"width:40%\" onkeypress=\"return acceptNum(event)\" value=\"".date("H")."\"> :\n";
			$html .= "															<input type=\"text\" maxlength=\"2\" name=\"minuto\" class=\"input-text\" style=\"width:40%\"  onkeypress=\"return acceptNum(event)\" value=\"".date("i")."\">\n";
			$html .= "														</td>\n";
			$html .= "													</tr>\n";
			$html .= "													<tr class=\"modulo_table_list_title\">\n";
			$html .= "														<td style=\"text-indent:8pt;text-align:left\">CODIGO AUTORIZACIÓN:</td>\n";
			$html .= "														<td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "															<input type=\"text\" name=\"codigoau\" class=\"input-text\" style=\"width:100%\" onkeyPress=\"return noAcceptSpace(event)\">\n";
			$html .= "														</td>\n";
			$html .= "														<td style=\"text-indent:8pt;text-align:left\">VALIDEZ:</td>\n";
			$html .= "														<td class=\"modulo_list_claro\" align=\"left\" colspan=\"2\">\n";
			$html .= "															<input type=\"text\" class=\"input-text\" name=\"fecha_validez\" maxlength=\"10\" size=\"11\" value=\"".$this->Fecha."\" onkeypress=\"return acceptDate(event)\">\n";
			$html .= "															<b>".ReturnOpenCalendario('ingresar_auto','fecha_validez','/')."</b>\n";
			$html .= "														</td>\n";			
			$html .= "													</tr>\n";
			$html .= "													<tr class=\"modulo_table_list_title\">\n";
			$html .= "														<td $style>\n";
			$html .= "															RESPONSABLE / TIPO DOCUMENTO\n";
			$html .= "														</td>\n";
			$html .= "														<td class=\"modulo_list_claro\" colspan=\"4\" align=\"left\">\n";
			$html .= "															<input type=\"text\" name=\"codigo_generador\" class=\"input-text\" style=\"width:100%\">\n";
			$html .= "														</td>\n";
			$html .= "													</tr>\n";
			$html .= "													<tr class=\"modulo_table_list_title\">\n";
			$html .= "														<td colspan=\"5\" >OBSERVACIONES A LA AUTORIZACIÓN</td>\n";
			$html .= "													</tr>\n";
			$html .= "													<tr class=\"modulo_table_list_title\">\n";
			$html .= "														<td class=\"modulo_list_claro\" colspan=\"5\">\n";
			$html .= "															<textarea name=\"observacion\" class=\"textarea\" style=\"width:100%\" rows=\"2\" ></textarea>\n";
			$html .= "														</td>\n";
			$html .= "													</tr>\n";
			$html .= "													<tr class=\"modulo_table_list_title\">\n";
			$html .= "														<td colspan=\"5\" >OBSERVACIONES GENERALES</td>\n";
			$html .= "													</tr>\n";
			$html .= "													<tr class=\"modulo_table_list_title\">\n";
			$html .= "														<td class=\"modulo_list_claro\" colspan=\"5\">\n";
			$html .= "															<textarea name=\"observacion_general\" class=\"textarea\" style=\"width:100%\" rows=\"2\" ></textarea>\n";
			$html .= "														</td>\n";
			$html .= "													</tr>\n";			
			$html .= "												</table>\n";
			$html .= "											</td>\n";
			$html .= "										</tr>\n";
			$html .= "									</table>\n";
			$html .= "							</fieldset>\n";
			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "				</table>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "	<table align=\"center\" width=\"60%\">\n";			
			$html .= "		<tr>\n";
			$html .= "			<td align=\"center\">\n";
			$html .= "				<input class=\"input-submit\" type=\"button\" onclick=\"Evaluar(document.ingresar_auto,'1')\" name=\"aceptar\" value=\"Autorizar\" >\n";
			$html .= "			</td>\n";
			$html .= "		</form>\n";
			if(!empty($action['cancelar']))
			{
				$html .= "		<form name=\"cancelar\" action=\"".$action['cancelar']."\" method=\"post\">\n";
				$html .= "			<td align=\"center\">\n";
				$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"Cancelar\">\n";
				$html .= "			</td>\n";
				$html .= "		</form>\n";
			}
			else
			{
				$html .= "		<form name=\"cancelar\" action=\"".$action['cerrar']."\" method=\"post\">\n";
				$html .= "			<td align=\"center\">\n";
				$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"cerrar\" value=\"Cerrar / Cancelar\">\n";
				$html .= "			</td>\n";
				$html .= "		</form>\n";
			}
			
			if(!empty($action['volver']))
			{
				$html .= "		<form name=\"cancelar\" action=\"".$action['volver']."\" method=\"post\">\n";
				$html .= "			<td align=\"center\">\n";
				$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"Cancelar\">\n";
				$html .= "			</td>\n";
				$html .= "		</form>\n";
			}
			$html .= "	</tr>\n";
			$html .= "</table><br>\n";
			return $html;		
		}
	}
?>