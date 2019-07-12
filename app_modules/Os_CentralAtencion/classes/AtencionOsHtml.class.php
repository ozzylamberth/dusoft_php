<?php
	/**************************************************************************************
	* $Id: AtencionOsHtml.class.php,v 1.1 2010/01/20 20:58:30 hugo Exp $
	*
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* $Revision: 1.1 $
	*
	* @autor Hugo F  Manrique
	***************************************************************************************/
	class AtencionOsHtml
	{
		function AtencionOsHtml(){}
		/********************************************************************************** 
		* Funci� para Crear un Encabezado de la pagina
		* 
		* @params $datos array Arreglo de datos que contiene la informacion de la empresa
		* @return html
		***********************************************************************************/
		function Encabezado($datos)
    {
			$html = "";
			if($datos)
			{
				$html .= "<table class=\"modulo_table_list\" border=\"0\" width=\"80%\" align=\"center\">\n";
				$html .= "	<tr class=\"modulo_table_list_title\">\n";
				$html .= " 		<td>EMPRESA</td>\n";
				$html .= " 		<td>CENTRO UTILIDAD</td>\n";
				$html .= " 		<td>DEPARTAMENTO</td>\n";
				$html .= "	</tr>\n";
				$html .= "	<tr align=\"center\">\n";
				$html .= " 		<td class=\"normal_10AN\" >".$datos['emp']."</td>\n";
				$html .= " 		<td class=\"normal_10N\" >".$datos['centro']."</td>\n";
				$html .= " 		<td class=\"normal_10N\" >".$datos['dpto']."</td>\n";
				$html .= "	</tr>\n";
				$html .= "</table>\n";
			}
			return $html;
    }
		/**************************************************************************************
		* Funcion donde se crea el contenido html de los cargos a autorizar de una solicitud
		* de orden de servicio
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
		function FormaCargosAutorizar(&$obj,$action,$datEmpresa,$datos)
		{
			IncludeClass('AtencionOs','','app','Os_CentralAtencion');

			$aos = new AtencionOs();
			$hsSolicitudes = $aos->ObtenerSolicitues($datos['tipoid'],$datos['idp'],$datEmpresa['departamento'],$datos['ingreso']);
			$hsSolcargos = $aos->ObtenerCargosSolicitudes($datos['tipoid'],$datos['idp'],$datEmpresa['departamento'],$datos['ingreso'],$datos['plan_id']);

			$html  = $this->CrearCapaVentana(&$obj);
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
			$html .= "	function EvaluarDatos(objeto)\n";
			$html .= "	{\n";
			$html .= "		datos = '';\n";
			$html .= "		flagI = true;\n";
			$html .= "		ctd = objeto.equivalentes;\n";
			$html .= "		limite =1 \n";
			$html .= "		if (ctd.length != undefined) limite = ctd.length;\n";
			$html .= "		for(i=0; i<limite; i++)\n";
			$html .= "		{\n";
			$html .= "			cargo = '';\n";
			$html .= "			solicitud = '';\n";
			$html .= "			limiteI = 1;\n";
			$html .= "			try\n";
			$html .= "			{\n";
			$html .= "				cargo = objeto.nombre_cargo[i].value;\n";
			$html .= "				solicitud = objeto.solicitud_nu[i].value;\n";
			//$html .= "				limiteI = ctd[i].value;\n";
			$html .= "				limiteI = objeto.subcargo[i].value;\n";
			$html .= "			}\n";
			$html .= "			catch(error)\n";
			$html .= "			{\n";
			$html .= "				cargo = objeto.nombre_cargo.value;\n";
			$html .= "				solicitud = objeto.solicitud_nu.value;\n";
			$html .= "				limiteI = objeto.subcargo.value;\n";
			//$html .= "				limiteI = 1;\n";
			$html .= "			}\n";
			$html .= "			for(j =0; j<limiteI ;j++)\n";
			$html .= "			{\n";
			$html .= "				try\n";
			$html .= "				{\n";
			$html .= "					eleI = document.getElementById('cargoc'+i+'_'+cargo+'_'+j);\n";
			$html .= "					eleX = document.getElementById('cargot'+i+'_'+cargo+'_'+j);\n";
			$html .= "					eleY = document.getElementById('cargox'+i+'_'+cargo+'_'+j);\n";
			$html .= "					if(eleI.checked == true)\n";
			$html .= "					{\n";
			$html .= "						if(!IsNumeric(eleX.value))\n";
			$html .= "						{\n";
			$html .= "							mensaje = 'EL CARGO '+cargo+' TIENE UN VALOR DE CANTIDAD INCORRECTO';\n";
			$html .= "							CrearMensaje(mensaje);\n";
			$html .= "							flagI = false;\n";
			$html .= "							break;\n";
			$html .= "						}\n";
			$html .= "						datos += '&cargos['+solicitud+']['+cargo+']['+eleY.value+']='+eleX.value;\n";
			$html .= "					}\n";
			$html .= "				}catch(error){}\n";
			$html .= "			}\n";
			$html .= "		} \n";
			$html .= "		if(flagI && datos != '')MostrarAutorizacion(datos,objeto);\n";
			$html .= "		else CrearMensaje('NO HAY CARGOS SELECCIONADOS PARA AUTORIZAR');\n";
			$html .= "	}\n";
			
			$html .= "	function MarcarTodos(objeto)\n";
			$html .= "	{\n";
			$html .= "		ctd = objeto.equivalentes;\n";
			$html .= "		adic = 0;\n";
			$html .= "		nombrec = '';\n";
			$html .= "		limite =1 \n";
			$html .= "		if (ctd.length != undefined) limite = ctd.length;\n";
			$html .= "		if(objeto.todos.checked == true)\n";
			$html .= "		{\n";
			$html .= "			for(i=0; i<limite; i++)\n";
			$html .= "			{\n";
			$html .= "				cargo = '';\n";
			$html .= "				limiteI = 1;\n";
			$html .= "				try\n";
			$html .= "				{\n";
			$html .= "					cargo = objeto.nombre_cargo[i].value;\n";
			$html .= "					limiteI = objeto.subcargo[i].value;\n";
			$html .= "				}\n";
			$html .= "				catch(error)\n";
			$html .= "				{\n";
			$html .= "					cargo = objeto.nombre_cargo.value;\n";
			$html .= "					limiteI = objeto.subcargo.value;\n";
			$html .= "				}\n";
			$html .= "				if(limiteI == '1')\n";
			$html .= "				{\n";
			$html .= "					ele = document.getElementById('cargoc'+i+'_'+cargo+'_0');\n";
			$html .= "					ele.checked = true;\n";
			$html .= "				}\n";
			$html .= "				else\n";
			$html .= "				{\n";
			$html .= "					adic++;\n";
			$html .= "					nombrec += cargo +', ';\n";			
			$html .= "				}\n";
			$html .= "			}\n";
			$html .= "			if(adic > 0)\n";
			$html .= "			{\n";
			$html .= "				mensaje = 'LOS CARGOS: '+nombrec + 'NO SE HAN SELECCIONADO PORQUE POSEEN MAS DE UNA EQUIVALENCIA';\n";
			$html .= "				CrearMensaje(mensaje);\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "		else\n";
			$html .= "		{\n";
			$html .= "			for(i=0; i<limite; i++)\n";
			$html .= "			{\n";
			$html .= "				cargo = '';\n";
			$html .= "				limiteI = 1;\n";
			$html .= "				try\n";
			$html .= "				{\n";
			$html .= "					cargo = objeto.nombre_cargo[i].value;\n";
			$html .= "					limiteI = objeto.subcargo[i].value;\n";
			$html .= "				}\n";
			$html .= "				catch(error)\n";
			$html .= "				{\n";
			$html .= "					cargo = objeto.nombre_cargo.value;\n";
			$html .= "					limiteI = objeto.subcargo.value;\n";
			$html .= "				}\n";
			$html .= "				for(j = 0; j<limiteI ; j++)\n";
			$html .= "				{\n";
			$html .= "					ele = document.getElementById('cargoc'+i+'_'+cargo+'_'+j);\n";
			$html .= "					ele.checked = false;\n";
			$html .= "				}\n";
			$html .= "			}\n";
			$html .= "		}\n";
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
			$html .= "			//Compruebo si es un valor num�ico\n";
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
			$html .= $this->Encabezado($datEmpresa)."<br>";
		
			if(!empty($hsSolicitudes))
			{
				$html .= "<form name=\"autorizar\" method=\"post\">\n";
				$html .= "	<table border=\"0\" width=\"100%\" align=\"center\" id=\"clase\">\n";
				$html .= "		<tr>\n";
				$html .= "			<td>\n";				
				$i = 0;
				foreach($hsSolicitudes as $key0 => $servicios)
				{
					$html .= "				<table width=\"100%\">\n";
					$html .= "					<tr>\n";
					$html .= "						<td>\n";
					$html .= "							<fieldset><legend class=\"normal_10AN\">".$key0."</legend>\n";
					$html .= "							<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
					$html .= "								<tr class=\"modulo_table_list_title\">\n";
					$html .= "									<td width=\"5%\">TIPO</td>\n";
					$html .= "									<td width=\"7%\">N SOL.</td>\n";
					$html .= "									<td width=\"8%\">FECHA</td>\n";
					$html .= "									<td width=\"8%\">DPTO</td>\n";
					$html .= "									<td width=\"8%\">CUPS</td>\n";
					$html .= "									<td >DESCRIPCION</td>\n";
					$html .= "									<td width=\"3%\"><input type=\"checkbox\" onclick=\"MarcarTodos(document.autorizar)\" name=\"todos\"></td>\n";
					$html .= "								</tr>\n";	
					foreach($servicios as $keyI => $tipoSolicitudes)
					{
						foreach($tipoSolicitudes as $keyII => $Solicitudes)
						{
							foreach($Solicitudes as $keyIII => $Cargos)
							{
								$clase = " class=\"modulo_table_list_title\" ";
								if($keyI == "H") $clase = " class=\"formulacion_table_list\" ";
							
								$html .= "					<tr class=\"modulo_list_claro\" >\n";
								$html .= "						<td $clase ><b style=\"font-size:15px\">".$keyI."</b></td>\n";
								$html .= "						<td >".$Cargos['hc_os_solicitud_id']."</td>\n";
								$html .= "						<td align=\"center\">".$Cargos['fecha']."</td>\n";
								$html .= "						<td >".$Cargos['departamento']."</td>\n";
								$html .= "						<td class=\"normal_10AN\" >".$Cargos['cargo']."</td>\n";
								$html .= "						<td class=\"normal_10AN\" colspan=\"2\">\n";
								$html .= "							".$Cargos['cups']."<br>\n";
							
							$dis = "";
							if(sizeof($hsSolcargos[$Cargos['cargo']]) >= 0)
							{
								//$dis = "disabled";
								$tam = sizeof($hsSolcargos[$Cargos['cargo']]);
								
								$html .= "							<input type=\"hidden\" name=\"equivalentes\" value=\"".$tam."\">\n";
								$html .= "							<input type=\"hidden\" name=\"nombre_cargo\" value=\"".$Cargos['cargo']."\">\n";
								$html .= "							<input type=\"hidden\" name=\"subcargo\" value=\"".sizeof($hsSolcargos[$Cargos['cargo']])."\">\n";
								$html .= "							<input type=\"hidden\" name=\"solicitud_nu\" value=\"".$Cargos['hc_os_solicitud_id']."\">\n";
								$html .= "							<table width=\"100%\" class=\"modulo_table_list\" style=\"background:#FFFFFF\">\n";
								$html .= "								<tr class=\"modulo_table_list_title\">\n";
								$html .= "									<td width=\"30%\" colspan=\"2\">TARIFARIO - CARGO</td>\n";
								$html .= "									<td width=\"%\">DECRIPCION</td>\n";
								$html .= "									<td width=\"10%\">CANT.</td>\n";
								$html .= "									<td width=\"3%\"></td>\n";
								$html .= "								</tr>\n";
								$j=0;
								foreach($hsSolcargos[$Cargos['cargo']] as $keyIV => $equival)
								{
									$vector = "cargos1[$keyII][".$equival['cargo']."]";
									
									$html .= "								<tr class=\"modulo_list_claro\">\n";
									$html .= "									<td title=\"".$equival['desc_tarifario']."\" width=\"15%\" ><label class=\"label_mark\" style=\"cursor:help\">".$equival['tarifario_id']."</label></td>\n";
									$html .= "									<td width=\"15%\">".$equival['cargo']."</td>\n";
									$html .= "									<td>".$equival['tarifario']."</td>\n";
									$contrato = $aos->ObtenerValidacionContrato($Cargos['cargo'],$datos['plan_id']);
									if($contrato > 0)
									{
										$html .= "									<td align=\"right\">\n";
										$html .= "										<input type=\"text\" id=\"cargot$i"."_".$Cargos['cargo']."_$j\" style=\"width:90%\" onkeypress=\"return acceptNum(event)\" value=\"".$Cargos['cantidad']."\">\n";
										$html .= "									</td>\n";
										$html .= "									<td align=\"center\">\n";
										$html .= "										<input type=\"hidden\" id=\"cargox$i"."_".$Cargos['cargo']."_$j\" value=\"".$equival['cargo']."\">\n";
										$html .= "										<input type=\"checkbox\" id=\"cargoc$i"."_".$Cargos['cargo']."_$j\" value=\"".$equival['cargo']."\" >\n";
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
					}
					$html .= "							</table>\n";
					$html .= "							</fieldset>\n";
					$html .= "						</td>\n";
					$html .= "					</tr>\n";
					$html .= "				</table>\n";
				}
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				$html .= "		<tr>\n";
				$html .= "			<td>\n";
				$html .= "				<table align=\"right\" width=\"100%\">\n";
				$html .= "					<tr>\n";
				$html .= "						<td align=\"center\">\n";
				$html .= "							<div id=\"error\" style=\"text-transform:uppercase\"></div>\n";
				$html .= "						</td>\n";
				$html .= "						<td align=\"center\" width=\"7%\">\n";
				$html .= "							<a href=\"javascript:EvaluarDatos(document.autorizar)\" class=\"label_error\">\n";
				$html .= "								<img src=\"". GetThemePath() ."/images/autorizadores.png\" border=\"0\"><br>\n";
				$html .= "								AUTORIZAR\n";
				$html .= "							</a>\n";
				$html .= "						</td>\n";
				$html .= "					</tr>\n";
				$html .= "				</table>\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				$html .= "	</table>\n";
				$html .= "</form>\n";
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
		function FormaCargosAutorizados(&$obj,$action,$datEmpresa,$datos,$seleccion,$numero_orden_id)
		{
			IncludeClass('LiquidacionCargos');
			IncludeClass('AtencionOs','','app','Os_CentralAtencion');

			$aos = new AtencionOs();
			$hsSolicitudes = $aos->ObtenerSolicituesAutorizadas($datos['tipo_id_paciente'],$datos['paciente_id'],$datEmpresa['departamento'],$datos['numero_autorizacion'],$numero_orden_id);
			$hsSolcargos = $aos->ObtenerCargosSolicitudes($datos['tipo_id_paciente'],$datos['paciente_id'],$datEmpresa['departamento'],$datos['ingreso'],$datos['plan_id'],"0",$datos['numero_autorizacion']);

			$html  = $this->CrearCapaVentana(&$obj);
			$html .= "<script>\n";
			$html .= "	var flagI = true;\n";
			$html .= "	function ContinuarOrdenServicio(objeto)\n";
			$html .= "	{\n";
			$html .= "		objeto.action = \"".$action['aceptar']."\";\n";
			$html .= "		objeto.submit();\n";
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
			$html .= "			//Compruebo si es un valor num�ico\n";
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
			$html .= "		datos = '';\n";
			$html .= "		flagI = true;\n";
			$html .= "		ctd = objeto.equivalentes;\n";
			$html .= "		limite =1 \n";
			$html .= "		cntd = objeto.cantidad_sol;\n";
			$html .= "		if (ctd.length != undefined) limite = ctd.length;\n";
			$html .= "		for(i=0; i<limite; i++)\n";
			$html .= "		{\n";
			$html .= "			cargo = '';\n";
			$html .= "			solicitud = '';\n";
			$html .= "			limiteI = 1;\n";
			$html .= "			try\n";
			$html .= "			{\n";
			$html .= "				cargo = objeto.nombre_cargo[i].value;\n";
			$html .= "				solicitud = objeto.solicitud_nu[i].value;\n";
			$html .= "				limiteI = objeto.subcargo[i].value;\n";
			$html .= "			}\n";
			$html .= "			catch(error)\n";
			$html .= "			{\n";
			$html .= "				cargo = objeto.nombre_cargo.value;\n";
			$html .= "				solicitud = objeto.solicitud_nu.value;\n";
			$html .= "				limiteI = objeto.subcargo.value;\n";
			$html .= "			}\n";
			$html .= "			for(j =0; j<limiteI ;j++)\n";
			$html .= "			{\n";
			$html .= "				try\n";
			$html .= "				{\n";
			$html .= "					eleI = document.getElementById('cargoc'+i+'_'+cargo+'_'+j);\n";
			$html .= "					eleD = document.getElementById('proveedor'+i);\n";
			$html .= "					if(eleI.checked == true)\n";
			$html .= " 					{\n";
			$html .= " 						try{ valor = cntd[i].value;}\n";
			$html .= " 						catch(error){valor = cntd.value;}\n";
			$html .= " 						if(!IsNumeric(valor))\n";
			$html .= " 						{\n";
			$html .= " 							CrearMensaje('HAY VALORES NUMERICOS CON FORMATO INCORRECTO');\n";
			$html .= " 							return;\n";
			$html .= " 						}\n";
			$html .= "						datos = '1';\n";
			$html .= "					}\n";
			$html .= "				}catch(error){alert(error);}\n";
			$html .= "			}\n";
			$html .= "		}\n";
			
			$html .= "		if(flagI && datos != '')ContinuarOrdenServicio(objeto);\n";
			$html .= "		else CrearMensaje('NO HAY CARGOS SELECCIONADOS PARA CONTINAUAR CON LA CREACION DE LA ORDEN DE SERVICIO');\n";
			$html .= "	}\n";
			
			$html .= "	function MarcarTodos(objeto)\n";
			$html .= "	{\n";
			$html .= "		ctd = objeto.equivalentes;\n";
			$html .= "		adic = 0;\n";
			$html .= "		nombrec = '';\n";
			$html .= "		limite =1 \n";
			$html .= "		if (ctd.length != undefined) limite = ctd.length;\n";
			$html .= "		if(objeto.todos.checked == true)\n";
			$html .= "		{\n";
			$html .= "			for(i=0; i<limite; i++)\n";
			$html .= "			{\n";
			$html .= "				cargo = '';\n";
			$html .= "				limiteI = 1;\n";
			$html .= "				try\n";
			$html .= "				{\n";
			$html .= "					cargo = objeto.nombre_cargo[i].value;\n";
			$html .= "				limiteI = objeto.subcargo[i].value;\n";
			$html .= "				}\n";
			$html .= "				catch(error)\n";
			$html .= "				{\n";
			$html .= "					cargo = objeto.nombre_cargo.value;\n";
			$html .= "				limiteI = objeto.subcargo.value;\n";
			$html .= "				}\n";
			
			$html .= "				if(limiteI == 1)\n";
			$html .= "				{\n";
			$html .= "					ele = document.getElementById('cargoc'+i+'_'+cargo+'_0');\n";
			$html .= "					ele.checked = true;\n";
			$html .= "				}\n";
			$html .= "				else\n";
			$html .= "				{\n";
			$html .= "					adic++;\n";
			$html .= "					nombrec += cargo +', ';\n";			
			$html .= "				}\n";
			$html .= "			}\n";
			$html .= "			if(adic > 0)\n";
			$html .= "			{\n";
			$html .= "				mensaje = 'LOS CARGOS: '+nombrec + 'NO SE HAN SELECCIONADO PORQUE POSEEN MAS DE UNA EQUIVALENCIA';\n";
			$html .= "				CrearMensaje(mensaje);\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "		else\n";
			$html .= "		{\n";
			$html .= "			for(i=0; i<limite; i++)\n";
			$html .= "			{\n";
			$html .= "				cargo = '';\n";
			$html .= "				limiteI = 1;\n";
			$html .= "				try\n";
			$html .= "				{\n";
			$html .= "					cargo = objeto.nombre_cargo[i].value;\n";
			$html .= "				limiteI = objeto.subcargo[i].value;\n";
			$html .= "				}\n";
			$html .= "				catch(error)\n";
			$html .= "				{\n";
			$html .= "					cargo = objeto.nombre_cargo.value;\n";
			$html .= "				limiteI = objeto.subcargo.value;\n";
			$html .= "				}\n";
			$html .= "				for(j = 0; j<limiteI ; j++)\n";
			$html .= "				{\n";
			$html .= "					ele = document.getElementById('cargoc'+i+'_'+cargo+'_'+j);\n";
			$html .= "					ele.checked = false;\n";
			$html .= "				}\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "	}\n";
			
			$html .= "		function acceptNum(evt)\n";
			$html .= "		{\n";
			$html .= "			var nav4 = window.Event ? true : false;\n";
			$html .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "			return (key <= 13 || (key >= 48 && key <= 57));\n";
			$html .= "		}\n";
			
			$html .= "</script>\n";
			$html .= $this->Encabezado($datEmpresa)."<br>";
			
			if(!empty($hsSolicitudes))
			{
				$i = 0;
				$lqc = new LiquidacionCargos();
				$lqc->SetDatosPlan(array('plan_id'=>$datos['plan_id']));
				
				//echo '<pre>';
				//print_r($lqc);
				
				$html .= "<form name=\"ordenarcargos\" action=\"javascript:EvaluarDatos(document.ordenarcargos)\" method=\"post\">\n";
				$html .= "<input type=\"hidden\" name=\"proveedor\" value=\"".$datEmpresa['departamento']."\">\n";
				
				$check = "";
				foreach($hsSolicitudes as $keyII => $servicios)
				{
					$html .= "<table width=\"100%\">\n";
					$html .= "	<tr>\n";
					$html .= "		<td>\n";
					$html .= "			<fieldset><legend class=\"normal_10AN\">".$keyII."</legend>\n";
					$html .= "			<table border=\"0\" width=\"100%\" align=\"center\" id=\"clase\">\n";
					$html .= "				<tr>\n";
					$html .= "					<td>\n";
					$html .= "						<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
					$html .= "							<tr class=\"modulo_table_list_title\">\n";
					$html .= "								<td width=\"7%\">N SOL.</td>\n";
					$html .= "								<td width=\"8%\">FECHA</td>\n";
					$html .= "								<td width=\"8%\">DPTO</td>\n";
					$html .= "								<td width=\"8%\">CUPS</td>\n";
					$html .= "								<td width=\"66%\">DESCRIPCION</td>\n";
					//$html .= "								<td width=\"25%\">PROVEEDOR</td>\n";
					$html .= "								<td width=\"3%\"><input type=\"checkbox\" onclick=\"MarcarTodos(document.ordenarcargos)\" name=\"todos\"></td>\n";
					$html .= "							</tr>\n";
				
					foreach($servicios as $keyI => $tipoSolicitudes)
					{
						foreach($tipoSolicitudes as $keyIII => $Cargos)
						{
							$vector = "ordenes[$keyII][cargo_cup][".$Cargos['cargo']."]";
							$fechaj = explode(" ",$Cargos['fecha']);
							$html .= "							<input type=\"hidden\" name=\"ordenes[$keyII][servicio]\" value=\"".$Cargos['servicio']."\">\n";
							$html .= "							<input type=\"hidden\" name=\"".$vector."[fecha]\" value=\"".$fechaj[0]."\">\n";
							$html .= "							<input type=\"hidden\" name=\"".$vector."[cantidad]\" value=\"".$Cargos['cantidad']."\">\n";
							$html .= "							<input type=\"hidden\" name=\"".$vector."[hc_soilicitud_id]\" value=\"".$Cargos['hc_os_solicitud_id']."\">\n";
							$html .= "							<tr class=\"modulo_list_claro\" >\n";
							$html .= "								<td rowspan=\"2\">".$Cargos['hc_os_solicitud_id']."</td>\n";
							$html .= "								<td rowspan=\"2\" align=\"center\">".$Cargos['fecha']."</td>\n";
							$html .= "								<td rowspan=\"2\">".$Cargos['departamento']."</td>\n";
							$html .= "								<td rowspan=\"2\" class=\"normal_10AN\" >".$Cargos['cargo']."</td>\n";
							$html .= "								<td class=\"normal_10AN\" width=\"41%\">\n";
							$html .= "									".$Cargos['cups']."\n";
							$html .= "								</td>\n";
							$html .= "								<td></td>\n";
							$html .= "							</tr>\n";
							$html .= "							<tr class=\"modulo_list_claro\" >\n";
							$html .= "								<td class=\"normal_10AN\" colspan=\"4\">\n";
							$dis = "";
							if(sizeof($hsSolcargos[$Cargos['cargo']]) >= 0)
							{
								$dis = "disabled";
								$tam = sizeof($hsSolcargos[$Cargos['cargo']]);
								
								$html .= "							<input type=\"hidden\" name=\"nombre_cargo\" value=\"".$Cargos['cargo']."\">\n";
								$html .= "							<input type=\"hidden\" name=\"subcargo\" value=\"".sizeof($hsSolcargos[$Cargos['cargo']])."\">\n";
								$html .= "							<input type=\"hidden\" name=\"solicitud_nu\" value=\"".$Cargos['hc_os_solicitud_id']."\">\n";
								$html .= "							<table width=\"100%\" class=\"modulo_table_list\" style=\"background:#FFFFFF\">\n";
								$html .= "								<tr class=\"modulo_table_list_title\">\n";
								$html .= "									<td width=\"21%\" colspan=\"2\">TARIFAR - CARGO</td>\n";
								$html .= "									<td width=\"%\">DECRIPCION</td>\n";
								$html .= "									<td width=\"10%\">CANT.</td>\n";
								$html .= "									<td width=\"10%\">PRECIO</td>\n";
								$html .= "									<td width=\"9%\">% COB.</td>\n";
								$html .= "									<td width=\"3%\"></td>\n";
								$html .= "								</tr>\n";
								$j=0;
								
								//echo '<pre>';
								//print_r($hsSolcargos);
								
								foreach($hsSolcargos[$Cargos['cargo']] as $keyIV => $equival)
								{
									$vector = "ordenes[$keyII][cargo_cup][".$Cargos['cargo']."][cargo]";
									$checked = "checked";
									if($seleccion['cargos1'][$Cargos['hc_os_solicitud_id']][$Cargos['cargo']][$equival['cargo']])
									{
										$cantidad = $seleccion['cargos1'][$Cargos['hc_os_solicitud_id']][$Cargos['cargo']][$equival['cargo']];
									}
									else
									{
										//$checked = "";
										$cantidad = $Cargos['cantidad'];
									}
									
									$precio = $lqc->GetPreciosPlanTarifario($equival['tarifario_id'],$equival['cargo']);
									//jab--aqui se calcula el valor total contratado x clientes
									/*static $OBJ;
									if(!is_object($OBJ))
        								{
            									if(!IncludeClass("LiquidacionCargos"))
            									{
                									$this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                									$this->mensajeDeError = "No se pudo incluir la clase de Liquidacion de Cargos";
                									return false;
            									}
            									$OBJ = new LiquidacionCargos;
        								}*/
									$preciolqc = $lqc->LiquidarCargo($datos['plan_id'], $equival['tarifario_id'], $equival['cargo'], $Cargos['cantidad']);
									//print_r($preciolqc); 
									
									$html .= "								<tr class=\"modulo_list_claro\">\n";
									$html .= "									<td title=\"".$equival['desc_tarifario']."\" width=\"10%\" ><label class=\"label_mark\" style=\"cursor:help\">".$equival['tarifario_id']."</label></td>\n";
									$html .= "									<td width=\"10%\">".$equival['cargo']."</td>\n";
									//jab cargo 
									$html .= "									<td>".$equival['tarifario']."</td>\n";
									$html .= "									<td align=\"right\" class=\"label\">\n";
									if($equival['sw_cantidad'] == '0')
									{
										$html .= "										<input type=\"hidden\" name=\"".$vector."[".$equival['cargo']."][".$equival['tarifario_id']."][cantidad]\" value=\"".$cantidad."\" id=\"cantidad_sol\">\n";
										$html .= "										".$cantidad."\n";
									}
									else
									{
										$html .= "										<input type=\"text\" name=\"".$vector."[".$equival['cargo']."][".$equival['tarifario_id']."][cantidad]\" class=\"input-text\" style=\"width:100%\" value=\"".$cantidad."\" onkeypress=\"return acceptNum(event)\" id=\"cantidad_sol\">\n";
									}
									$html .= "</td>\n";
									//jab precio 
									/*$html .= "									<td align=\"right\" class=\"label\">$".FormatoValor($precio['precio'])."</td>\n";
									$html .= "									<td align=\"right\" class=\"label\">".($precio['por_cobertura']*100/100)."%</td>\n";*/
									$html .= "									<td align=\"right\" class=\"label\">$".FormatoValor($preciolqc['precio_plan'])."</td>\n";
									$html .= "									<td align=\"right\" class=\"label\">".($precio['por_cobertura']*100/100)."%</td>\n";
									$html .= "									<td align=\"center\">\n";
									
									$html .= "										<input type=\"hidden\" name=\"".$vector."[".$equival['cargo']."][".$equival['tarifario_id']."][tarifario]\" id=\"cargoc$i"."_".$Cargos['cargo']."$j\" value=\"".$equival['tarifario_id']."\">\n";
									$html .= "										<input type=\"checkbox\" name=\"".$vector."[".$equival['cargo']."][".$equival['tarifario_id']."][cargo]\" id=\"cargoc$i"."_".$Cargos['cargo']."_$j\" value=\"".$equival['cargo']."\" $checked>\n";
									$html .= "									</td>\n";
									$html .= "								</tr>\n";
									$j++;
								}
								$html .= "							<input type=\"hidden\" name=\"equivalentes\" value=\"".$j."\">\n";
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
					$html .= "				</table>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";
					$html .= "	</table>\n";
					
					$html .= "				</fieldset>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";
					$html .= "	</table>\n";
				}
				//$html .= "</form>\n";
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
		/***********************************************************************
		*
		************************************************************************/
		function CrearCapaVentana(&$obj)
		{
			$obj->IncludeJS("CrossBrowserEvent");
			$obj->IncludeJS("CrossBrowserDrag");
			$obj->IncludeJS("CrossBrowser");
			
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
			$html .= "	function CrearMensaje(mensaje)\n";
			$html .= "	{\n";
			$html .= "		xGetElementById('confirmacion').innerHTML = mensaje;\n";
			$html .= "		Iniciar();\n";
			$html .= "		MostrarSpan('Contenedor');\n";
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
			$html .= "	<div id='titulo' class='draggable' style=\"	text-transform: uppercase;text-align:center\">INFORMACI�</div>\n";
			$html .= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('Contenedor')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
			$html .= "	<div id='Contenido' class='d2Content' style=\"background:#FDFDFE\">\n";
			$html .= "		<form name=\"oculta\" action=\"javascript:OcultarSpan('Contenedor')\" method=\"post\">\n";
			$html .= "			<table width=\"100%\" align=\"center\">\n";
			$html .= "				<tr>\n";
			$html .= "					<td align=\"center\" class=\"label\">\n";
			$html .= "					<div style=\"text-transform: uppercase;\" id=\"confirmacion\" class=\"normal_10AN\"></div>\n";
			$html .= "					</td>\n";
			$html .= "				</tr>\n";			
			$html .= "				<tr>\n";
			$html .= "					<td colspan=\"3\" align=\"center\">\n";
			$html .= "						<input type=\"submit\" class=\"input-submit\"name=\"aceptar\" value=\"Aceptar\">\n";
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
		function FormaCitas($datos,$offset,$action,$departamento)
		{
			IncludeClass('AtencionCitas','','app','Os_CentralAtencion');
			$atc = new AtencionCitas();
			$html = "";
			
			if($datos['documento_id'])
			{
				$citas = $atc->ObtenerPacientes($datos['tipo_documento_id'],$datos['documento_id'],$departamento,$offset);
			
				if(empty($citas))
				{
					$html  = "<center>\n";
					$html .= "	<label class=\"label_error\">NO EXISTEN CITAS CREADAS PARA ESTE PACIENTE</labeL>\n";
					$html .= "</center>\n";	
				}
				else
					$html = $this->FormaMostrarCitas($citas,$atc,$datos,$action);
			}
			else
			{
				$pacientes = $atc->ObtenerDatosPacientes($datos,array(),$offset);
				if(empty($pacientes))
				{
					$msg = "LA BUSQUEDA NO ARROJO RESULTADOS";
					if($pacientes === false)
						$msg = "SE DEBE INGRESAR AL MENOS UN CRITERIO DE BUSQUEDA";
										
					$html  = "<center>\n";
					$html .= "	<label class=\"label_error\">".$msg."</labeL>\n";
					$html .= "</center>\n";	
				}
				else
					$html = $this->FormaListarPacientes($pacientes,$action,&$atc);
			}
			return $html;
		}
		/***********************************************************************
		*
		************************************************************************/
		function FormaMostrarCitas($citas,$obj,$datos,$action)
		{		
			$html = "";
			$fecha = date("d/m/Y");
			$hora = date("H");
			$mint = date("i");
			
			$dpaciente = $obj->ObtenerDatosPacientes(array(), $datos);
			$html .= "<table class=\"modulo_table_list\" border=\"0\" width=\"80%\" align=\"center\">\n";
			$html .= "	<tr class=\"normal_10AN\">\n";
			$html .= " 		<td class=\"label\">PACIENTE:</td>\n";
			$html .= " 		<td >".$dpaciente['tipo_id_paciente']." ".$dpaciente['paciente_id']."</td>\n";
			$html .= " 		<td >".$dpaciente['nombre']." ".$dpaciente['apellido']."</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
				
			$st= " style=\"text-align:left;text-indent:4pt\"";
			$html .= "<table width=\"90%\" align=\"center\" cellpadding=\"3\">\n"; 
			foreach($citas as $key => $turno)
			{
				$pct = array();
				$marca = "";
				$mdl = "modulo_table_list_title";
				
				$html .= "	<tr>\n"; 
				$html .= "		<td>\n";						
				$html .= "			<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				if($fecha == $turno['fecha']) 
				{	
					$hors = explode(":",$turno['hora']);
					$mdl = "formulacion_table_list";
					
					if($hora > trim($hors[0]) || ($hora == trim($hors[0]) && $mint > trim($hors[1])) )
						$marca = "&nbsp; <lable class=\"label_error\">VENCIDA</lable>";
						
					$html .= "	<tr class=\"$mdl\">\n"; 
					$html .= "		<td colspan=\"5\">\n"; 
					$html .= "			CITAS PARA EL DIA DE HOY ".$fecha."\n"; 
					$html .= "		</td>\n"; 
					$html .= "	</tr>\n"; 
					
					$pct = array('grupo'=>'3','tipoid'=>$dpaciente['tipo_id_paciente'],
								'idp'=>$dpaciente['paciente_id'],'plan_id'=>$turno['plan_id'],
								'orden_servicio_id'=>$turno['orden_servicio_id']);
					
				}
				
				$html .= "				<tr $st class=\"$mdl\">\n";
				$html .= "					<td width=\"20%\">PROFESIONAL</td>\n";
				$html .= "					<td width=\"40%\" class=\"modulo_list_claro\">".$turno['nombre']."</td>\n";
				$html .= "					<td width=\"10%\">HORA</td>\n";
				$html .= "					<td width=\"%\" class=\"modulo_list_claro\">".$turno['hora']." ".$marca."</td>\n";
/*			$action = ModuloGetURL('app','Os_CentralAtencion','user','FormaDatosPaciente',array("opcion"=>'1','tipo_id_paciente'=>$dpaciente['tipo_id_paciente'],
								'paciente_id'=>$dpaciente['paciente_id'],'plan_id'=>$turno['plan_id']));*/
/*			  $action = ModuloGetURL('app','Os_CentralAtencion','user','FormaCumplirOrdenes',
														array('tipo_id' => $dpaciente['tipo_id_paciente'], 'paciente_id' => $dpaciente['paciente_id'],
																	'plan_id' => $turno['plan_id'], 'numero_orden_id' => $turno[numero_orden_id], 'orden_id' => $turno[orden_servicio_id],
																	'sw_fecha_vencimiento'=>'0'));*/
				$action = ModuloGetURL('app','Os_CentralAtencion','user','FormaCargosOrdenesServicio',
							array("paciente_id"=>$dpaciente['paciente_id'],"tipo_id_paciente"=>$dpaciente['tipo_id_paciente'],"plan_id"=>$turno['plan_id'],"numero_orden_id"=>$turno[numero_orden_id]));
 /*					$action = ModuloGetURL('app','Os_CentralAtencion','user','FormaCrearSolicitud',
 							array("paciente_id"=>$dpaciente['paciente_id'],"tipo_id_paciente"=>$dpaciente['tipo_id_paciente'],"plan_id"=>$turno['plan_id']));*/
//print_r($turno);
				if(!empty($pct))
				{
					$html .= "					<td align=\"center\" rowspan=\"3\" width=\"7%\" class=\"modulo_list_claro\">\n";
					//$html .= " 						<a href=\"".$action['ordenar'].URLRequest($pct)."\">\n";
					$html .= " 						<a href=\"".$action."\">\n";
					$html .= " 							<img src=\"".GetThemePath()."/images/atencion_citas.png\" border=\"0\">VER OS\n";
					$html .= " 						</a>\n";
					$html .= "					</td>\n";
				}
				
				$html .= "				</tr>\n";
				$html .= "				<tr $st class=\"$mdl\">\n";
				$html .= "					<td>TIPO CONSULTA</td>\n";
				$html .= "					<td colspan=\"3\" class=\"modulo_list_claro\">".$turno['consulta']."</td>\n";
				$html .= "				</tr>\n";				
				$html .= "				<tr $st class=\"$mdl\">\n";
				$html .= "					<td width=\"20%\">CONSULTORIO</td>\n";
				$html .= "					<td colspan=\"3\" class=\"modulo_list_claro\">".$turno['consultorio']."</td>\n";
				$html .= "				</tr>\n";

				$html .= "			</table>\n";
				$html .= "		</td>\n"; 
				$html .= "	</tr>\n";			
			}
			$html .= "</table>\n"; 
			return $html;
		}
		/***********************************************************************
		*
		************************************************************************/
		function FormaListarPacientes($pacientes,$action,&$atc)
		{		
			$html = "";
			$mdl = "";
			IncludeClass("ClaseHTML");
			$html .= "<br>\n";
			$html .= "<div id=\"listado_pacientes\" style=\"display:'block'\">\n";
			$html .= "	<table class=\"modulo_table_list\" border=\"0\" width=\"80%\" align=\"center\">\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= " 			<td width=\"20%\">IDENTIFICACION</td>\n";
			$html .= " 			<td width=\"78%\">PACIENTE</td>\n";
			$html .= " 			<td></td>\n";
			$html .= "		</tr>\n";
				
			foreach($pacientes as $key => $datos)
			{
				($mdl == "modulo_list_oscuro")? $mdl = "modulo_list_claro": $mdl = "modulo_list_oscuro";
				
				$html .= "		<tr class=\"$mdl\">\n"; 
				$html .= " 			<td class=\"label\">".$datos['tipo_id_paciente']." ".$datos['paciente_id']."</td>\n";
				$html .= " 			<td class=\"normal_10AN\" >".$datos['nombre']." ".$datos['apellido']."</td>\n";
				$html .= " 			<td>\n";
				$html .= " 				<a href=\"javascript:CitasPaciente('".$datos['paciente_id']."','".$datos['tipo_id_paciente']."')\">\n";
				$html .= " 					<img src=\"".GetThemePath()."/images/auditoria.png\" border=\"0\">\n";
				$html .= " 				</a>\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";			
			}
			$html .= "	</table>\n"; 
			$html .= ClaseHTML::ObtenerPaginado($atc->conteo,$atc->paginaActual,$action['paginador']);
			$html .= "</div>\n"; 
			$html .= "<div id=\"cita_paciente\" style=\"display:'none'\" >\n";
			$html .= "</div>\n"; 
			$html .= "<script>\n"; 
			$html .= "	function CitasPaciente(paciente_id,tipo_id_paciente)\n"; 
			$html .= "	{\n"; 
			$html .= "		xajax_CitasPaciente(paciente_id,tipo_id_paciente)\n"; 
			$html .= "	}\n"; 
			$html .= "	function Cerrar()\n"; 
			$html .= "	{\n"; 
			$html .= "		xajax_Ocultar();\n";
			$html .= "	}\n"; 
			$html .= "</script>\n"; 
			return $html;
		}
	}
?>