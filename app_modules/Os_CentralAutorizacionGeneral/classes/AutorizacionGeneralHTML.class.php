<?php
	/**************************************************************************************
	* $Id: AutorizacionGeneralHTML.class.php,v 1.2 2007/04/23 20:19:49 hugo Exp $
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	*
	* $Revision: 1.2 $
	* @author Hugo F. Manrique Arango
	***************************************************************************************/
	IncludeClass('ClaseUtil');
	class AutorizacionGeneralHTML extends ClaseUtil
	{
		function AutorizacionGeneralHTML(){}
		/********************************************************************************** 
		* 
		* @return boolean
		***********************************************************************************/
		function FormaBuscador($action,$datos,$offset)
		{
			IncludeClass('ConsultaAutorizaciones','','app','NCAutorizaciones');
			
			$caut = new ConsultaAutorizaciones();
			
			$html  = $this->AcceptNum();
			$html .= $this->LimpiarCampos();
			$html .= "<form name=\"formabuscador\" action=\"".$action['buscar']."\" method=\"post\">\n";
			$html .= "	<table width=\"80%\" align=\"center\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td>\n";
			$html .= "				<fieldset><legend class=\"normal_10AN\">BUSCAR PACIENTE</legend>\n";
			$html .= "					<table width=\"100%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
			$html .= "						<tr class=\"modulo_table_list_title\">\n";
			$html .= "							<td width=\"20%\" style=\"text-indent:8pt;text-align:left\">Nº INGRESO</td>\n";
			$html .= "							<td width=\"%\" colspan=\"3\" style=\"text-align:left\" class=\"modulo_list_claro\">\n";
			$html .= "								<input class=\"input-text\" type=\"text\" name=\"buscador[ingreso]\" style=\"width:20%\" onkeypress=\"return acceptNum(event)\" value=\"".$datos['ingreso']."\">\n";
			$html .= "							</td>\n";
			$html .= "						</tr>\n";
			$html .= "						<tr class=\"modulo_table_list_title\">\n";
			$html .= "							<td style=\"text-align:left;text-indent:8pt\">TIPO DOCUMENTO: </td>\n";
			$html .= "							<td align=\"left\" class=\"modulo_list_claro\" width=\"30%\">\n";
			$html .= "								<select name=\"buscador[tipodocumento]\" class=\"select\">\n";
			$html .= "										<option value=\"0\">---Seleccionar---</option>\n";
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
			$html .= "							<td align=\"left\" class=\"modulo_list_claro\" width=\"25%\">\n";
			$html .= "								<input type=\"text\" class=\"input-text\" name=\"buscador[documento]\" value=\"".$datos['documento']."\" style=\"width:60%\" >\n";
			$html .= "							</td>\n";
			$html .= "						</tr>\n";
			$html .= "						<tr class=\"modulo_table_list_title\">\n";
			$html .= "							<td style=\"text-indent:8pt;text-align:left\">NOMBRES</td>\n";
			$html .= "							<td style=\"text-align:left\" class=\"modulo_list_claro\">\n";
			$html .= "								<input class=\"input-text\" type=\"text\" name=\"buscador[nombres]\" style=\"width:100%\" value=\"".$datos['nombres']."\">\n";
			$html .= "							</td>\n";
			$html .= "							<td style=\"text-indent:8pt;text-align:left\">APELLIDOS</td>\n";
			$html .= "							<td style=\"text-align:left\" class=\"modulo_list_claro\">\n";
			$html .= "								<input class=\"input-text\" type=\"text\" name=\"buscador[apellidos]\" style=\"width:100%\" value=\"".$datos['apellidos']."\">\n";
			$html .= "							</td>\n";
			$html .= "						</tr>\n";
			$html .= "					</table>\n";
			$html .= "					<table width=\"50%\" align=\"center\" height=\"25\">\n";
			$html .= "						<tr>\n";
			$html .= "							<td align=\"center\" >\n";
			$html .= "								<input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"Buscar\">\n";
			$html .= "							</td>\n";
			$html .= "							<td align=\"center\" >\n";
			$html .= "								<input class=\"input-submit\" type=\"button\" name=\"limpiar\" value=\"Limpiar Campos\" onclick=\"LimpiarCampos(document.formabuscador)\">\n";
			$html .= "							</td>\n";
			$html .= "						</form>\n";
			$html .= "						</tr>\n";
			$html .= "					</table>\n";
			$html .= "				</fieldset>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "</form>\n";
			
			if($datos['ingreso'] || $datos['documento'] || $datos['nombres'] || $datos['apellidos'])
			{
				IncludeClass('AutorizacionGeneralOs','','app','Os_CentralAutorizacionGeneral');
				$agos = new AutorizacionGeneralOs();
				
				$paciente = $agos->ObtenerDatosPaciente($datos,$offset);
				if(empty($paciente))
				{
					$html .= "<center>\n";
					$html .= "	<label class=\"label_error\">LA BÚSQUEDA NO ARROJO RESULTADOS</label>\n";
					$html .= "</center>\n";
				}
				else
				{
					$html .= "<br>\n";
					$html .= "<table width=\"80%\" class=\"modulo_table_list\" align=\"center\" cellpading=\"10\">\n";
					$html .= "	<tr class=\"modulo_table_list_title\">\n";
					$html .= "		<td width=\"20%\">IDENTIFICACION</td>\n";
					$html .= "		<td>PACIENTE</td>\n";
					$html .= "		<td colspan=\"2\" width=\"25%\"></td>\n";
					$html .= "	</tr>\n";
					foreach($paciente as $key => $personal)
					{
						($est == "modulo_list_claro")? $est = "modulo_list_oscuro": $est = "modulo_list_claro";
						
						$url = $action['autorizar'].UrlRequest(array("paciente"=>$personal));
						
						$html .= "	<tr class=\"$est\">\n";
						$html .= "		<td >".$personal['tipo_id_paciente']." ".$personal['paciente_id']."</td>\n";
						$html .= "		<td >".$personal['nombre']." ".$personal['apellido']."</td>\n";
						$html .= "		<td align=\"center\">\n";
						$html .= "			<a class=\"label_error\" href=\"".$url."\">\n";
						$html .= "				<img border=\"0\" src=\"".GetThemePath()."/images/auditoria.png\">AUTORIZAR\n";
						$html .= "			</a>\n";
						$html .= "		</td>\n";
						
						$url = $action['ordenes'].UrlRequest(array("paciente"=>$personal));

						$html .= "		<td align=\"center\">\n";
						$html .= "			<a class=\"label_error\" href=\"".$url."\">\n";
						$html .= "				<img border=\"0\" src=\"".GetThemePath()."/images/pcargos.png\">ORDENES\n";
						$html .= "			</a>\n";
						$html .= "		</td>\n";
						
						$html .= "	</tr>\n";
					}
					$html .= "</table>\n";
					
					IncludeClass('ClaseHTML');
					$Pg = new ClaseHTML();
					$url = $action['buscar'].UrlRequest(array("buscador"=>$datos));
					$html .= "								".$Pg->ObtenerPaginado($agos->conteo,$agos->paginaActual,$url);
				}
			}
			return $html;
		}
		/********************************************************************************** 
		* 
		* @return boolean
		***********************************************************************************/
		function FormaSolicitudManual($action,$permisos)
		{
			IncludeClass('ConsultaAutorizaciones','','app','NCAutorizaciones');
			$caut = new ConsultaAutorizaciones();
			
			if($permisos['sw_todos_planes'] == '1')
				$planes = $caut->ObtenerPlanes();
			else
				$planes = $caut->ObtenerPlanesRestringido($permisos);
			
			if(empty($planes))
			{
				$html  = "<table width=\"70%\" align=\"center\" class=\"modulo_table_list\">\n";
				$html .= "	<tr>\n";
				$html .= "		<td class=\"normal_11N\" align=\"justify\">\n";
				$html .= "			SU USAURIO POSEE PERMISOS PARA TRABAJAR EN ESTE MODULO, PERO NO TIENE PLANES ASOCIADOS, PARA LA CREACION DE SOLICITUDES MANUALES\n";
				$html .= "		</td>\n";
				$html .= "	</tr>\n";
				$html .= "</table>";
				return $html;
			}
			
			$html  = "<script>\n";
			$html .= "	function EvaluarDatos(objeto)\n";
			$html .= "	{\n";
			$html .= "		var tipo_id = objeto.tipo_id_paciente.value;\n";
			$html .= "		var paciente_id = objeto.paciente_id.value;\n";
			$html .= "		var plan = objeto.plan_id.value;\n;";
			$html .= "		if(tipo_id != 'AS' && tipo_id != 'MS')\n";
			$html .= "		{\n";
			$html .= "			if(paciente_id == '' || tipo_id == '' || plan == '-1')\n";
			$html .= "			{\n";
			$html .= "				document.getElementById('errorA').innerHTML = \"<b class='label_error'>PARA REALIZAR LA BUSQUEDA DEL PACIENTE SE DEBEN INGRESAR TODOS LOS DATOS SOLICITADOS</b>\"\n";
			$html .= "				return;\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "		else\n";
			$html .= "		{\n";
			$html .= "			if(plan == '-1')\n";
			$html .= "			{\n";
			$html .= "				document.getElementById('errorA').innerHTML = \"FAVOR SELECCIONAR EL PLAN\";\n";  
			$html .= "				return;\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "		objeto.action = '".$action['solicitud']."';\n";
			$html .= "		objeto.submit();\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			$html .= "<form name=\"formasolicitudmanual\" action=\"javascript:EvaluarDatos(document.formasolicitudmanual)\" method=\"post\">";
			$html .= "	<center>\n";
			$html .= "		<div id=\"errorA\" class=\"label_error\"><br></div>\n";
			$html .= "	<center>\n";
			$html .= "	<table width=\"80%\" align=\"center\" border=\"0\"  class=\"modulo_table_list\">\n";
      $html .= "		<tr class=\"modulo_table_list_title\" >\n";
			$html .= "			<td align=\"left\" style=\"text-indent:5pt\">PLAN:</td>\n";
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "				<select name=\"plan_id\" class=\"select\">\n";
			$html .= "					<option value = '-1'>---Seleccionar---</option>\n";
			$csk = "";
			
			foreach($planes as $key => $plan)
				$html .= "					<option value=\"".$key."\">".$plan['plan_descripcion']."</option>\n";
			
			$html .= "				</select>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_table_list_title\" >\n";
			$html .= "			<td align=\"left\" style=\"text-indent:5pt\">TIPO DOCUMENTO:</td>\n";
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "				<select name=\"tipo_id_paciente\" class=\"select\">\n";
			$html .= "					<option value=\"0\">---Seleccionar---</option>\n";
			
			$tipoId = $caut->ObtenerTipoIdPaciente();
			foreach($tipoId as $value => $dat)
				$html .="					<option value=\"".$dat['tipo_id_paciente']."\">".$dat['descripcion']."</option>\n";
			
			$html .= "				</select>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td align=\"left\" style=\"text-indent:5pt\">DOCUMENTO:</td>\n";
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\"><input type=\"text\" class=\"input-text\" name=\"paciente_id\" maxlength=\"32\" value=".$this->request['documentom']."></td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_list_claro\">\n";
			$html .= "			<td align='center' colspan=\"2\">\n";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\">\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "</form>\n";
			
			return $html;
		}
		/**************************************************************************************
		* Funcion donde se crea el contenido html de los cargos a autorizar de una solicitud
		* de orden de servicio
		* @params	object $obj-> Objeto de clase principal, necesario para poner en pantalla 
		*					una capa.
		* @params array	 $action-> arreglo de links, donde se indican las acciones a seguir,
		*					indices = 'volver','autorizar'
		* @params array $datos Datos del paciente para realizar la busqueda de la orden, 
		*					indices = 'tipo_id_paciente'->Tipo de identificacion del paciente,
		*					'paciente_id'->Numero de identificacion del paciente,
		* @return String $html Html de los cargos
		***************************************************************************************/
		function FormaCargosAutorizar(&$obj,$action,$datos)
		{
			IncludeClass('AtencionOS','','app','Os_CentralAtencion');
			IncludeClass('AutorizacionGeneralOS','','app','Os_CentralAutorizacionGeneral');

			$ats = new AtencionOS();
			$aos = new AutorizacionGeneralOS();
			
			$html  = $this->IsNumeric();
			$html .= "<script>\n";
			$html .= "	var flagI = true;\n";
			$html .= "	function MostrarAutorizacion(datos,objeto)\n";
			$html .= "	{\n";
			$html .= "		objeto.action = \"".$action['autorizar']."\"+datos;\n";
			$html .= "		objeto.submit();\n";
			$html .= "	}\n";
			$html .= "	function acceptNum(evt)\n";
			$html .= "	{\n";
			$html .= "		var nav4 = window.Event ? true : false;\n";
			$html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "		return (key <= 13 ||(key >= 48 && key <= 57));\n";
			$html .= "	}\n";
			$html .= "	function EvaluarDatos(objeto,indice)\n";
			$html .= "	{\n";
			$html .= "		datos = '';\n";
			$html .= "		flagI = true;\n";
			$html .= "		ctd = objeto.equivalentes;\n";
			$html .= "		limite =1 \n";
			$html .= "		if (ctd.length != undefined) limite = ctd.length;\n";
			$html .= "		for(i=0; i<limite; i++)\n";
			$html .= "		{\n";
			$html .= "			cargo = '';\n";
			$html .= "			plan = '';\n";
			$html .= "			solicitud = '';\n";
			$html .= "			limiteI = 1;\n";
			$html .= "			try\n";
			$html .= "			{\n";
			$html .= "				plan = objeto.plan_num[i].value;\n";
			$html .= "				cargo = objeto.nombre_cargo[i].value;\n";
			$html .= "				solicitud = objeto.solicitud_nu[i].value;\n";
			$html .= "				limiteI = ctd[i].value;\n";
			$html .= "			}\n";
			$html .= "			catch(error)\n";
			$html .= "			{\n";
			$html .= "				plan = objeto.plan_num.value;\n";
			$html .= "				cargo = objeto.nombre_cargo.value;\n";
			$html .= "				solicitud = objeto.solicitud_nu.value;\n";
			$html .= "				limiteI = 1;\n";
			$html .= "			}\n";
			$html .= "			for(j =0; j<limiteI ;j++)\n";
			$html .= "			{\n";
			$html .= "				try\n";
			$html .= "				{\n";
			$html .= "					eleI = document.getElementById('cargoc'+indice+'_'+i+'_'+cargo+'_'+j);\n";
			$html .= "					eleX = document.getElementById('cargot'+indice+'_'+i+'_'+cargo+'_'+j);\n";
			$html .= "					eleY = document.getElementById('cargox'+indice+'_'+i+'_'+cargo+'_'+j);\n";
			$html .= "					if(eleI.checked == true)\n";
			$html .= "					{\n";
			$html .= "						if(!IsNumeric(eleX.value))\n";
			$html .= "						{\n";
			$html .= "							mensaje = 'EL CARGO '+cargo+' TIENE UN VALOR DE CANTIDAD INCORRECTO';\n";
			$html .= "							CrearMensaje(mensaje);\n";
			$html .= "							flagI = false;\n";
			$html .= "							return;\n";
			$html .= "						}\n";
			$html .= "						datos += '&cargos['+plan+']['+solicitud+']['+cargo+']['+eleY.value+']='+eleX.value;\n";
			$html .= "					}\n";
			$html .= "				}catch(error){}\n";
			$html .= "			}\n";
			$html .= "		} \n";
			$html .= "		if(flagI && datos != '')MostrarAutorizacion(datos,objeto);\n";
			$html .= "		else CrearMensaje('NO HAY CARGOS SELECCIONADOS PARA AUTORIZAR');\n";
			$html .= "	}\n";
			$html .= "	function MarcarTodos(objeto,indice,flag)\n";
			$html .= "	{\n";
			$html .= "		ctd = objeto.equivalentes;\n";
			$html .= "		adic = 0;\n";
			$html .= "		nombrec = '';\n";
			$html .= "		limite =1 \n";
			$html .= "		if (ctd.length != undefined) limite = ctd.length;\n";
			$html .= "		for(i=0; i<limite; i++)\n";
			$html .= "		{\n";
			$html .= "			cargo = '';\n";
			$html .= "			limiteI = 1;\n";
			$html .= "			try\n";
			$html .= "			{\n";
			$html .= "				cargo = objeto.nombre_cargo[i].value;\n";
			$html .= "				limiteI = ctd[i].value;\n";
			$html .= "			}\n";
			$html .= "			catch(error)\n";
			$html .= "			{\n";
			$html .= "				cargo = objeto.nombre_cargo.value;\n";
			$html .= "				limiteI = 1;\n";
			$html .= "			}\n";
			$html .= "			if(limiteI == '1')\n";
			$html .= "			{\n";
			$html .= "				ele = document.getElementById('cargoc'+indice+'_'+i+'_'+cargo+'_0');\n";
			$html .= "				ele.checked = flag;\n";
			$html .= "			}\n";
			$html .= "			else\n";
			$html .= "			{\n";
			$html .= "				if(flag)\n";
			$html .= "				{\n";
			$html .= "					adic++;\n";
			$html .= "					nombrec += cargo +', ';\n";
			$html .= "				}\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "		if(adic > 0 && flag)\n";
			$html .= "		{\n";
			$html .= "			mensaje = 'LOS CARGOS: '+nombrec + 'NO SE HAN SELECCIONADO PORQUE POSEEN MAS DE UNA EQUIVALENCIA';\n";
			$html .= "			CrearMensaje(mensaje);\n";
			$html .= "		}\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			
			$hsSolicitudes = $aos->ObtenerSolicitudes($datos['tipo_id_paciente'],$datos['paciente_id']);
			//echo "<pre>".print_r($hsSolicitudes,true)."</pre>";

			if(!empty($hsSolicitudes))
			{
				$l = 0;
				$est = $clase = "";
				$hsSolcargos = $aos->ObtenerCargosSolicitudes($datos['tipo_id_paciente'],$datos['paciente_id']);
				foreach($hsSolicitudes as $key0 => $planes)
				{
					$i = 0;
					$html .= "<form name=\"autorizar_$l\" method=\"post\">\n";
					$html .= "	<table border=\"0\" width=\"100%\" align=\"center\" id=\"clase\">\n";
					$html .= "		<tr>\n";
					$html .= "			<td>\n";				
					$html .= "				<table width=\"100%\">\n";
					$html .= "					<tr>\n";
					$html .= "						<td>\n";
					$html .= "							<fieldset ><legend align=\"center\" class=\"normal_11N\">".$key0."</legend>\n";
					$html .= "								<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
					$html .= "									<tr class=\"modulo_table_list_title\">\n";
					$html .= "										<td width=\"5%\">TIPO</td>\n";
					$html .= "										<td width=\"7%\">Nº SOL.</td>\n";
					$html .= "										<td width=\"9%\">FECHA</td>\n";
					$html .= "										<td width=\"9%\">CUPS</td>\n";
					$html .= "										<td >DESCRIPCION</td>\n";
					$html .= "										<td width=\"3%\"><input type=\"checkbox\" onclick=\"MarcarTodos(document.autorizar_$l,'$l',this.checked)\" name=\"todos\"></td>\n";
					$html .= "										<td width=\"2%\"></td>\n";
					$html .= "									</tr>\n";	
					
					foreach($planes as $keyS => $servicios)
					{
						$html .= "									<tr class=\"formulacion_table_list\">\n";
						$html .= "										<td style=\"text-indent:8pt;text-align:left\" colspan=\"8\">\n";
						$html .= "											SERVICIO: <b>".$keyS."</b>\n";
						$html .= "										</td>\n";
						$html .= "									</tr>\n";
						foreach($servicios as $keyI => $tipoSolicitudes)
						{
							foreach($tipoSolicitudes as $keyII => $Solicitudes)
							{
								foreach($Solicitudes as $keyIII => $Cargos)
								{
									($est == "modulo_list_claro")? $est = "modulo_list_oscuro": $est = "modulo_list_claro";
									
									($keyI == "H")? $clase = " class=\"formulacion_table_list\" ":$clase = " class=\"modulo_table_list_title\" ";
																		
									$html .= "					<tr  class=\"$est\">\n";
									$html .= "						<td $clase ><b style=\"font-size:15px\">".$keyI."</b></td>\n";
									$html .= "						<td >".$Cargos['hc_os_solicitud_id']."</td>\n";
									$html .= "						<td align=\"center\">".$Cargos['fecha']."</td>\n";
									$html .= "						<td class=\"normal_10AN\" >".$Cargos['cargo']."</td>\n";
									$html .= "						<td class=\"normal_10AN\" colspan=\"2\">\n";
									$html .= "							".$Cargos['cups']."<br>\n";
								
									if(sizeof($hsSolcargos[$Cargos['plan_id']][$Cargos['cargo']]) >= 0)
									{
										//$dis = "disabled";
										$tam = sizeof($hsSolcargos[$Cargos['plan_id']][$Cargos['cargo']]);
										if($tam > 0)
										{
											$html .= "							<input type=\"hidden\" name=\"equivalentes\" value=\"".$tam."\">\n";
											$html .= "							<input type=\"hidden\" name=\"nombre_cargo\" value=\"".$Cargos['cargo']."\">\n";
											$html .= "							<input type=\"hidden\" name=\"solicitud_nu\" value=\"".$Cargos['hc_os_solicitud_id']."\">\n";
											$html .= "							<input type=\"hidden\" name=\"plan_num\" value=\"".$Cargos['plan_id']."\">\n";
											$html .= "							<table width=\"100%\" class=\"modulo_table_list\" style=\"background:#FFFFFF\">\n";
											$html .= "								<tr class=\"modulo_table_list_title\">\n";
											$html .= "									<td width=\"25%\" colspan=\"2\">TARIFARIO - CARGO</td>\n";
											$html .= "									<td width=\"%\">DECRIPCION</td>\n";
											$html .= "									<td width=\"10%\">CANT.</td>\n";
											$html .= "									<td width=\"3%\"></td>\n";
											$html .= "								</tr>\n";
											$j=0;
											foreach($hsSolcargos[$Cargos['plan_id']][$Cargos['cargo']] as $keyIV => $equival)
											{
												$vector = "cargos1[$keyII][".$equival['cargo']."]";
												
												$html .= "								<tr class=\"$est\">\n";
												$html .= "									<td title=\"".$equival['desc_tarifario']."\" width=\"15%\" >\n";
												$html .= "										<label class=\"label_mark\" style=\"cursor:help\">".$equival['tarifario_id']."</label>\n";
												$html .= "									</td>\n";
												$html .= "									<td width=\"15%\">".$equival['cargo']."</td>\n";
												$html .= "									<td>".$equival['tarifario']."</td>\n";
												$contrato = $ats->ObtenerValidacionContrato($Cargos['cargo'],$Cargos['plan_id']);
												if($contrato > 0)
												{
													$html .= "									<td align=\"right\">\n";
													$html .= "										<input type=\"text\" id=\"cargot".$l."_$i"."_".$Cargos['cargo']."_$j\" style=\"width:90%\" onkeypress=\"return acceptNum(event)\" value=\"".$Cargos['cantidad']."\">\n";
													$html .= "									</td>\n";
													$html .= "									<td align=\"center\">\n";
													$html .= "										<input type=\"hidden\" id=\"cargox".$l."_$i"."_".$Cargos['cargo']."_$j\" value=\"".$equival['cargo']."\">\n";
													$html .= "										<input type=\"checkbox\" id=\"cargoc".$l."_$i"."_".$Cargos['cargo']."_$j\" value=\"".$equival['cargo']."\" >\n";
													$html .= "									</td>\n";
												}
												else
												{
													$html .= "									<td align=\"center\" colspan=\"2\">\n";
													$html .= "										<b  style=\"color: #9C0003\">CARGO NO CONTRATADO</b>\n";
													$html .= "									</td>\n";									
												}
												
												$html .= "								</tr>\n";
												$j++;
											}
											$html .= "						</table>\n";
											$i++;
										}
										else
										{
											$html .= "<center>\n";
											$html .= "	<label class=\"label_error\">EL CARGO NO ESTA CONTRATADO O NO POSEE EQUIVALENCIAS</label>\n";
											$html .= "</center>\n";
										}
									}
									else
									{
										foreach($hsSolcargos[$Cargos['cargo']] as $keyIV => $equival)
										{
											$html .= "						<input type=\"hidden\" id=\"tarifario".$Cargos['cargo']."0\" value=\"".$equival['tarifario_id']."\">\n";
											$html .= "						<input type=\"hidden\" id=\"cargoc".$Cargos['cargo']."0\" value=\"".$equival['cargo']."\">\n";
										}
									}
									$urlI = $action['anular'].UrlRequest(array("hc_os_solicitud"=>$Cargos['hc_os_solicitud_id'],"cargo_cups"=>$Cargos['cargo'],"forma"=>'autorizar'));
									$html .= "						</td>\n";
									$html .= "						<td >\n";
									$html .= "							<a href=\"".$urlI."\"  target=\"anular\" onclick=\"window.open('".$urlI."','anular','toolbar=no,width=600,height=400,resizable=no,scrollbars=yes').focus(); return false;\" title=\"ANULAR CARGO\">\n";
									$html .= "								<img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\">\n";
									$html .= "							</a>\n";
									$html .= "						</td>\n";
									$html .= "					</tr>\n";
								}
							}
						}
					}
					$html .= "											</table>\n";

					$html .= "							<table align=\"right\" width=\"100%\">\n";
					$html .= "								<tr>\n";
					$html .= "									<td align=\"center\">\n";
					$html .= "										<div id=\"error\" style=\"text-transform:uppercase\"></div>\n";
					$html .= "									</td>\n";
					$html .= "									<td align=\"center\" width=\"7%\">\n";
					$html .= "										<a href=\"javascript:EvaluarDatos(document.autorizar_$l,'$l')\" class=\"label_error\">\n";
					$html .= "											<img src=\"".GetThemePath() ."/images/autorizadores.png\" border=\"0\"><br>\n";
					$html .= "											AUTORIZAR\n";
					$html .= "										</a>\n";
					$html .= "									</td>\n";
					$html .= "								</tr>\n";
					$html .= "							</table>\n";

					$html .= "							</fieldset>\n";
					$html .= "						</td>\n";
					$html .= "					</tr>\n";
					$html .= "				</table>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";
					$html .= "	</table>\n";
					$html .= "</form>\n";
					$l++;
				}			
			}
			else
			{
				$html .= "<center>\n";
				$html .= "	<label class=\"normal_11N\">NO HAY CARGOS PENDIENTES POR AUTORIZAR</label>\n";
				$html .= "</center>\n";
			}
			
			$html .= "<form name=\"volver\" action =\"".$action['volver']."\" method=\"post\">\n";			
			$html .= "	<table align=\"center\" width=\"100%\">\n";			
			$html .= "		<tr>\n";
			$html .= "			<td align=\"center\">\n";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";	
			$html .= "</form>\n";	
			$html .= $this->CrearCapaVentana(&$obj);
			$html .= "<form name=\"recarga\" action =\"".$action['recarga']."\" method=\"post\"><form>\n";			
			return $html;
		}
		/**************************************************************************************
		* Funcion donde se crea el contenido html de los cargos autorizados que se incluiran 
		* en orden de servicio
		* @params	object $obj-> Objeto de clase principal, necesario para poner en pantalla 
		*					una capa.
		* @params array	 $action-> arreglo de links, donde se indican las acciones a seguir,
		*					indices = 'volver','autorizar'
		*	@params array	 $datEmpresa-> arreglo de datos para el encabezado de la empresa,
		*					indices = 'emp'->nombre de la empresa,
		*					'centro'->Descripcion del centro de utilidad,
		*					'dpto'->Descripcion del deparatamento	
		*					'departamento'->Identificacion del deparatamento	
		* @params array $datos Datos del paciente para realizar la busqueda de la orden, 
		*					indices = 'tipoid'->Tipo de identificacion del paciente,
		*					'idp'->Numero de identificacion del paciente,
		*					'ingreso'->Numero de ingreso al cual pertenece la orden (NULL),
		*					'plan_id'->Identificador del plan al cual pertenece el paciente 
		* @return String $html Html de los cargos
		***************************************************************************************/
		function FormaCargosAutorizados(&$obj,$action,$datos,$seleccion)
		{
			IncludeClass('LiquidacionCargos');
			IncludeClass('AutorizacionGeneralOS','','app','Os_CentralAutorizacionGeneral');

			$file = 'app_modules/Os_CentralAutorizacionGeneral/RemoteXajax/AutorizacionGeneral.php';
			$obj->SetXajax(array("reqActualizarCargos","reqSetValoresCargo","reqCombinarDatos"),$file);
			
			$aos = new AutorizacionGeneralOS();
			$hsSolcargos = $aos->ObtenerCargosSolicitudes($datos['tipo_id_paciente'],$datos['paciente_id'],$datos['plan_id'],$datos['numero_autorizacion'],'0');
			$hsSolicitudes = $aos->ObtenerSolicitudesAutorizadas($datos['tipo_id_paciente'],$datos['paciente_id'],$datos['plan_id'],$datos['numero_autorizacion'],'0');
						
			$html  = $this->CrearCapaVentana(&$obj);
			$html .= "<script>\n";
			$html .= "	xajax_reqSetValoresCargo('".$datos['tipo_id_paciente']."','".$datos['paciente_id']."','".$datos['plan_id']."','".$datos['numero_autorizacion']."');\n";
			$html .= "</script>\n";
			$html .= "<script>\n";
			$html .= "	var flagI = true;\n";
			$html .= "	function ContinuarOrdenServicio()\n";
			$html .= "	{\n";
			$html .= "		document.ordenarcargos.action = \"".$action['aceptar']."\";\n";
			$html .= "		document.ordenarcargos.submit();\n";
			$html .= "	}\n";
			
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
			
			$html .= "	function EvaluarDatos(objeto)\n";
			$html .= "	{\n";
			$html .= "		k = 0;\n";
			$html .= "		datos = '';\n";
			$html .= "		inputs = document.getElementsByTagName('input');\n";
			$html .= "  	for(i =0; i< inputs.length; i++)\n";
			$html .= "  	{\n";
			$html .= "  		switch(inputs[i].type)\n";
			$html .= "  		{\n";
			$html .= "  			case 'checkbox':\n";
			$html .= "					if(inputs[i].name != 'todos')\n";
			$html .= "					{\n";
			$html .= "						if(inputs[i].checked)\n";
			$html .= "						{\n";
			$html .= "							id = document.getElementById(inputs[i].name+'cantidad_sol');\n";
			$html .= "							if(!IsNumeric(id.value))\n";
			$html .= "							{\n";
			$html .= " 								CrearMensaje('LA CANTIDAD PARA EL CARGO:'+inputs[i].value+', POSSE UN FORMATO INCORRECTO');\n";
			$html .= " 								return;\n";
			$html .= "							}\n";
			$html .= "							if(datos == '')\n";
			$html .= "								datos += inputs[i].value+':'+id.value;\n";
			$html .= "							else\n";
			$html .= "								datos += ';'+inputs[i].value+':'+id.value;\n";
			$html .= "							k++;\n";
			$html .= "						}\n";
			$html .= "					}\n";
			$html .= "				break;\n";
			$html .= "  		}\n";
			$html .= "  	}\n";
			$html .= "		if(k > 0) xajax_reqCombinarDatos(datos);\n";
			$html .= "		else CrearMensaje('NO HAY CARGOS SELECCIONADOS PARA CONTINAUAR CON LA CREACION DE LA ORDEN DE SERVICIO');\n";
			$html .= "	}\n";
			
			$html .= "		function acceptNum(evt)\n";
			$html .= "		{\n";
			$html .= "			var nav4 = window.Event ? true : false;\n";
			$html .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "			return (key <= 13 || (key >= 48 && key <= 57));\n";
			$html .= "		}\n";
			
			$html .= "	function MarcarTodos(objeto,indice,flag,l)\n";
			$html .= "	{\n";
			$html .= "		cto = objeto.tipoorden;\n";
			$html .= "		ctd = objeto.equivalentes;\n";
			$html .= "		adic = 0;\n";
			$html .= "		nombrec = '';\n";
			$html .= "		limite =1; \n";
			$html .= " 		try\n";
			$html .= " 		{\n";
			$html .= " 			limite = objeto.cantidadsol[l].value;\n";
			$html .= " 		}\n";
			$html .= "		catch(error)\n";
			$html .= " 		{	limite = objeto.cantidadsol.value;} \n";
			$html .= " 		\n";
			$html .= "		for(i=0; i<limite; i++)\n";
			$html .= "		{\n";
			$html .= "			cargo = '';\n";
			$html .= "			limiteI = 1;\n";
			$html .= "			try\n";
			$html .= "			{\n";
			$html .= "				c = document.getElementsByName('nombre_cargo_'+l); \n";
			$html .= "				cargo = c[i].value;\n";
			$html .= "				li = document.getElementsByName('equivalentes_'+l);\n";
			$html .= "				limiteI = li[i].value;\n";
			$html .= "			}\n";
			$html .= "			catch(error)\n";
			$html .= "			{\n";
			$html .= "				cargo = objeto.nombre_cargo.value;\n";
			$html .= "				limiteI = 1;\n";
			$html .= "			}\n";
			$html .= "			if(limiteI == '1')\n";
			$html .= "			{\n";
			$html .= "				try\n";
			$html .= "				{\n";
			$html .= "					ele = document.getElementById('cargoc'+indice+'_'+i+'_'+cargo+'_0');\n";
			$html .= "					ele.checked = flag;\n";
			$html .= "				}\n";
			$html .= "				catch(error){}\n";
			$html .= "			}\n";
			$html .= "			else\n";
			$html .= "			{\n";
			$html .= "				if(flag)\n";
			$html .= "				{\n";
			$html .= "					adic++;\n";
			$html .= "					nombrec += cargo +', ';\n";
			$html .= "				}\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "		if(adic > 0 && flag)\n";
			$html .= "		{\n";
			$html .= "			mensaje = 'LOS CARGOS: '+nombrec + 'NO SE HAN SELECCIONADO PORQUE POSEEN MAS DE UNA EQUIVALENCIA';\n";
			$html .= "			CrearMensaje(mensaje);\n";
			$html .= "		}\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			$html .= "<form name=\"ordenarcargos\" action=\"javascript:EvaluarDatos(document.ordenarcargos)\" method=\"post\">\n";
		
			if(!empty($hsSolicitudes))
			{
				$html .= "	<div id=\"seleccionados\">\n";
				$html .= $this->CrearTablas(&$aos,$datos,$hsSolicitudes,$hsSolcargos,$seleccion);
				$html .= "	</div>\n";
			}
			
			$html .= "	<table align=\"center\" width=\"60%\">\n";			
			$html .= "		<tr>\n";
			$html .= "				<td align=\"center\">\n";
			$html .= "					<input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">\n";
			$html .= "				</td>\n";
			$html .= "			</form>\n";
			$html .= "			<form name=\"volver\" action =\"".$action['volver']."\" method=\"post\">\n";			
			$html .= "				<td align=\"center\">\n";
			$html .= "					<input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"Cancelar\">\n";
			$html .= "				</td>\n";
			$html .= "			</form>\n";	
			$html .= "		</tr>\n";
			$html .= "	</table>\n";	
			return $html;
		}
		/*************************************************************************************
		*
		**************************************************************************************/
		function FormaMensaje($titulo,$mensaje,$align,$action1,$action2 = null,$width = "50%")
		{
			$html .= ThemeAbrirTabla($titulo);
			$html .= "	<script>\n";
			$html .= "		function CerrarVentana(num_ingreso)\n";
			$html .= "		{\n";
			$html .= "			window.opener.document.formabuscar.ingreso.value = num_ingreso;\n";
			$html .= "			window.opener.document.formabuscar.submit();\n";
			$html .= "			window.close();\n";
			$html .= "		}\n";
			$html .= "	</script>\n";
			$html .= "	<form name=\"formaInformacion\" action=\"".$action1."\" method=\"post\">\n";
			$html .= "		<table align=\"center\" width=\"$width\" class=\"modulo_table_list\">\n";
			$html .= "			<tr><td class=\"label\" align=\"".$align."\" colspan=\"3\"><br>";
			$html .= "				".$mensaje."<br>\n";
			$html .= "			</td></tr>\n";
			$html .= "		</table>\n";
			$html .= "		<table align=\"center\" width=\"60%\">\n";
			$html .= "			<tr><td align=\"center\">\n";
			$html .= "				<input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">\n";
			$html .= "			</td></form><br>\n";
			
			if($action2)
			{
				$html .= "		<form name=\"cancelar\" action=\"".$this->action2."\" method=\"post\">\n";
				$html .= "			<td align=\"center\">\n";
				$html .= "				<input type=\"submit\" class=\"input-submit\" value=\"Cancelar\">\n";
				$html .= "			</td></form>\n";
				
			}
			$html .= "		</tr></table>\n";
			$html .= "	\n";
			$html .= ThemeCerrarTabla();
			return $html;
		}
		/*************************************************************************************
		*
		**************************************************************************************/
		function CrearTablas(&$aos,$datos,$hsSolicitudes,$hsSolcargos,$seleccion)
		{
			$html = "";
			$check = "";
			$lqc = new LiquidacionCargos();
			$lqc->SetDatosPlan(array('plan_id'=>$datos['plan_id']));
			$l = 0;
			
			foreach($hsSolicitudes as $key => $departamento)
			{	
				$i = 0;		
				if($key == '0') 
				{
					$proveedor = " (VARIOS) ";
				}
				else
				{
					$deptno = $aos->ObtenerDepartamentoPrestadorServicio(null, $key);
					$proveedor = $deptno[0]['descripcion'];
				}
				$html .= "<table width=\"100%\">\n";
				$html .= "	<tr>\n";
				$html .= "		<td>\n";
				$html .= "			<fieldset style=\"padding:3pt\"><legend class=\"normal_10AN\">PROVEEDOR: ".$proveedor."</legend>\n";
				
				foreach($departamento as $key0 => $tipoorden)
				{
					($key0 != 'AMBULATORIA')? $ind = '1': $ind = '2'; 
					
					$html .= "			<table border=\"0\" width=\"100%\" align=\"center\">\n";
					$html .= "				<tr>\n";
					$html .= "					<td>\n";
					$html .= "						<fieldset class=\"fieldset\"><legend>TIPO ORDEN: ".$key0."</legend>\n";
					$html .= "							<table border=\"0\" width=\"100%\" align=\"center\" id=\"clase\">\n";
					$html .= "								<tr>\n";
					$html .= "									<td>\n";
					$html .= "										<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
					$html .= "											<tr class=\"modulo_table_list_title\">\n";
					$html .= "												<td width=\"7%\">Nº SOL.</td>\n";
					$html .= "												<td width=\"9%\">FECHA</td>\n";
					$html .= "												<td width=\"9%\">SRVICIO</td>\n";
					$html .= "												<td width=\"9%\">CUPS</td>\n";
					$html .= "												<td width=\"63%\" colspan=\"3\">DESCRIPCION</td>\n";
					$html .= "												<td width=\"3%\" >\n";
					if($key != '0')
					{
						$html .= "												<input type=\"hidden\" name=\"tipoorden\" value = \"".$l."\">\n";
						$html .= "												<input type=\"checkbox\" onclick=\"MarcarTodos(document.ordenarcargos,'".$key."_".$ind."',this.checked,'".$l++."')\" name=\"todos\">\n";
					}
					
					$html .= "												</td>\n";
					$html .= "												<td width=\"%\" ></td>\n";
					$html .= "											</tr>\n";				
					foreach($tipoorden as $keyI => $solicitud)
					{
						$span = 1;

						if($key != '0')
							$html .= "												<input type=\"hidden\" name=\"cantidadsol\" value = \"".sizeof($tipoorden)."\">\n";

						foreach($solicitud as $keyII => $Cargos)
						{
							($key == '0')? $span = '1': $span = '3';
							$vector = "ordenes[$keyII][cargo_cup][".$Cargos['cargo']."]";
							$fechaj = explode(" ",$Cargos['fecha']);
							
							$html .= "							<input type=\"hidden\" name=\"ordenes[$keyII][servicio]\" value=\"".$Cargos['servicio']."\">\n";
							$html .= "							<input type=\"hidden\" name=\"".$vector."[fecha]\" value=\"".$fechaj[0]."\">\n";
							$html .= "							<input type=\"hidden\" name=\"".$vector."[cantidad]\" value=\"".$Cargos['cantidad']."\">\n";
							$html .= "							<input type=\"hidden\" name=\"".$vector."[hc_soilicitud_id]\" value=\"".$Cargos['hc_os_solicitud_id']."\">\n";
							$html .= "							<tr class=\"modulo_list_claro\" >\n";
							$html .= "								<td rowspan=\"2\">".$Cargos['hc_os_solicitud_id']."</td>\n";
							$html .= "								<td rowspan=\"2\" align=\"center\">".$Cargos['fecha']."</td>\n";
							$html .= "								<td rowspan=\"2\" class=\"normal_10AN\" >".$Cargos['des_servicio']."</td>\n";
							$html .= "								<td rowspan=\"2\" class=\"normal_10AN\" >".$Cargos['cargo']."</td>\n";
							$html .= "								<td colspan=\"$span\" width=\"32%\" class=\"normal_10AN\" >\n";
							$html .= "									".$Cargos['cups']."\n";
							$html .= "								</td>\n";
							if($key == '0')
							{
								$deptno = $aos->ObtenerDepartamentoPrestadorServicio($keyII,null);
								$html .= "								<td class=\"modulo_table_list_title\">PROVEEDOR:</td>\n";
								$html .= "								<td>\n";
								$html .= "									<select name=\"proveedor\" class=\"select\" onChange=\"if(this.value != '0') xajax_reqActualizarCargos('".$key."','".$key0."','".$keyI."','".$keyII."',this.value)\">\n";
								$html .= "										<option value=\"0\">---Seleccionar---</option>\n";
								
								foreach($deptno as $value => $dat)
									$html .="										<option value=\"".$dat['departamento']."\">".$dat['descripcion']."</option>\n";

								$html .= "									</select>\n";
								$html .= "								</td>\n";

							}
							$html .= "								<td></td>\n";
							$html .= "								<td rowspan=\"2\">\n";
							if($key != '0' && $Cargos['departamento'] == '0')
							{
								$html .= "									<a title=\"DESASOCIAR DEPARTAMENTO\" href=\"#\" onclick=\"xajax_reqActualizarCargos('".$key."','".$key0."','".$keyI."','".$keyII."','0')\">\n";
								$html .= "										<img src=\"".GetThemePath()."/images/pincumplimiento_citas.png\" border=\"0\">\n";
								$html .= "									</a>\n";
							}
							$html .= "								</td>\n";
							$html .= "							</tr>\n";
							$html .= "							<tr class=\"modulo_list_claro\" width=\"68%\">\n";
							$html .= "								<td class=\"normal_10AN\" colspan=\"4\">\n";
							$dis = "";
							if(sizeof($hsSolcargos[$Cargos['cargo']]) >= 0)
							{
								if($key != '0')
								{
									$tam = sizeof($hsSolcargos[$Cargos['cargo']])+1;
									$html .= "								<input type=\"hidden\" name=\"equivalentes_".($l-1)."\" value=\"".$tam."\">\n";
									$html .= "								<input type=\"hidden\" name=\"nombre_cargo_".($l-1)."\" value=\"".$Cargos['cargo']."\">\n";
									$html .= "								<input type=\"hidden\" name=\"solicitud_nu_".($l-1)."\" value=\"".$Cargos['hc_os_solicitud_id']."\">\n";
									$html .= "								<input type=\"hidden\" name=\"servicio_nu_".($l-1)."\" value=\"".$Cargos['servicio']."\">\n";
									$html .= "								<input type=\"hidden\" name=\"departamento_nu_".($l-1)."\" value=\"".$Cargos['departamento']."\">\n";
								}
								
								$html .= "							<table width=\"100%\" class=\"modulo_table_list\" style=\"background:#FFFFFF\">\n";
								$html .= "								<tr class=\"modulo_table_list_title\">\n";
								$html .= "									<td width=\"21%\" colspan=\"2\">TARIFAR - CARGO</td>\n";
								$html .= "									<td width=\"%\">DECRIPCION</td>\n";
								$html .= "									<td width=\"10%\">CANT.</td>\n";
								$html .= "									<td width=\"10%\">PRECIO</td>\n";
								$html .= "									<td width=\"9%\">% COB.</td>\n";
								if($key != '0') 
									$html .= "									<td width=\"3%\"></td>\n";
								$html .= "								</tr>\n";
								$j=0;
								
								foreach($hsSolcargos[$Cargos['plan_id']][$Cargos['cargo']] as $keyIV => $equival)
								{
									$vector = "ordenes[$keyII][cargo_cup][".$Cargos['cargo']."][cargo]";
									if($seleccion['cargos1'][$Cargos['plan_id']][$Cargos['hc_os_solicitud_id']][$Cargos['cargo']][$equival['cargo']] )
										$cantidad = $seleccion['cargos1'][$Cargos['plan_id']][$Cargos['hc_os_solicitud_id']][$Cargos['cargo']][$equival['cargo']];
									else
										$cantidad = $Cargos['cantidad'];
									
									$precio = $lqc->GetPreciosPlanTarifario($equival['tarifario_id'],$equival['cargo']);
									$html .= "								<tr class=\"modulo_list_claro\">\n";
									$html .= "									<td title=\"".$equival['desc_tarifario']."\" width=\"10%\" ><label class=\"label_mark\" style=\"cursor:help\">".$equival['tarifario_id']."</label></td>\n";
									$html .= "									<td width=\"10%\">".$equival['cargo']."</td>\n";
									$html .= "									<td>".$equival['tarifario']."</td>\n";
									$html .= "									<td align=\"right\" class=\"label\">\n";
									if($equival['sw_cantidad'] == '0')
										$html .= "										<input type=\"hidden\" name=\"cantidad_".($l-1)."\" id=\"cargoc_".($l-1)."cantidad_sol\" value=\"".$cantidad."\">".$cantidad."\n";
									else
										$html .= "										<input type=\"text\" name=\"cantidad_".($l-1)."\" class=\"input-text\" style=\"width:100%\" value=\"".$cantidad."\" onkeypress=\"return acceptNum(event)\" id=\"cargoc_".($l-1)."cantidad_sol\">\n";
									
									$html .= "									</td>\n";
									$html .= "									<td align=\"right\" class=\"label\">$".FormatoValor($precio['precio'])."</td>\n";
									$html .= "									<td align=\"right\" class=\"label\">".($precio['por_cobertura']*100/100)."%</td>\n";
									if($key != '0')
									{
										if($seleccion['cargos1'][$Cargos['plan_id']][$Cargos['hc_os_solicitud_id']][$Cargos['cargo']][$equival['cargo']] )
											$checked = "checked";
										else
											$checked = "";
																			
										$html .= "									<td align=\"center\">\n";
										$html .= "										<input	type=\"hidden\"		name = \"tarifario_".($l-1)."\" value=\"".$equival['tarifario_id']."\">\n";
										$html .= "										<input	type=\"checkbox\"	name = \"cargoc_".($l-1)."\" id=\"cargoc".$key."_".$ind."_$i"."_".$Cargos['cargo']."_$j\" value=\"".$equival['cargo']."\" $checked>\n";
										$html .= "									</td>\n";
									}
									
									$html .= "								</tr>\n";
									$j++;
								}
								$html .= "						</table>\n";
								$i++;
							}
							else
							{
								foreach($hsSolcargos[$Cargos['cargo']] as $keyIV => $equival)
								{
									$html .= "						<input type=\"hidden\" id=\"tarifario".$Cargos['cargo']."0\" value=\"".$equival['tarifario_id']."\">\n";
									$html .= "						<input type=\"hidden\" id=\"cargoc".$Cargos['cargo']."0\" value=\"".$equival['cargo']."\">\n";
								}
							}
							$html .= "						</td>\n";
							$html .= "					</tr>\n";
						}
					}
					$html .= "											</table>\n";
					$html .= "										</td>\n";
					$html .= "									</tr>\n";
					$html .= "								</table>\n";
					$html .= "							</fieldset>\n";
					$html .= "						</td>\n";
					$html .= "					</tr>\n";
					$html .= "				</table>\n";
				}
				$html .= "				</fieldset>\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				$html .= "	</table><br>\n";
			}
			return $html;
		}
	}
?>