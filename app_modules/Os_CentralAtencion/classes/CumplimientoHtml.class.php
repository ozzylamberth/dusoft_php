<?php
	/**************************************************************************************
	* $Id: CumplimientoHtml.class.php,v 1.1 2010/01/20 20:58:30 hugo Exp $
	*
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* $Revision: 1.1 $
	*
	* @autor Hugo F  Manrique
	***************************************************************************************/
	class CumplimientoHtml
	{
		var $ASIGNO1;
		var $cnt_citas_asignar1;
		var $citas_asignadas;
		
		function CumplimientoHtml($A="",$B="",$C="")
		{
			$this->ASIGNO1=$A;
			$this->cnt_citas_asignar1=$B;
			$this->citas_asignadas=$C;
		}
		/**************************************************************************************
		*
		* @return String $html Html de los cargos
		***************************************************************************************/
		function FormaCargosAutorizar()
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
			$html .= "				limiteI = ctd[i].value;\n";
			$html .= "			}\n";
			$html .= "			catch(error)\n";
			$html .= "			{\n";
			$html .= "				cargo = objeto.nombre_cargo.value;\n";
			$html .= "				solicitud = objeto.solicitud_nu.value;\n";
			$html .= "				limiteI = 1;\n";
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
			$html .= "					limiteI = ctd[i].value;\n";
			$html .= "				}\n";
			$html .= "				catch(error)\n";
			$html .= "				{\n";
			$html .= "					cargo = objeto.nombre_cargo.value;\n";
			$html .= "					limiteI = 1;\n";
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
			$html .= "					limiteI = ctd[i].value;\n";
			$html .= "				}\n";
			$html .= "				catch(error)\n";
			$html .= "				{\n";
			$html .= "					cargo = objeto.nombre_cargo.value;\n";
			$html .= "					limiteI = 1;\n";
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
				//echo "<pre>".print_r($hsSolicitudes,true)."</pre>";
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
		/*************************************************************************************
		* Funcion donde se crea un aforma para los mensajes
		* @params String 	$titulo Titulo para la forma
		* @params String 	$align	Alineacion del titulo left, right, center
		* @params array		$action Arreglo de links
		* @params String	$mensaje Mensaje a desplegar en la forma
		* @access public
		* 
		* @return String
		*************************************************************************************/
		function FormaMensaje($titulo,$align,$action,$mensaje)
		{
			$html .= ThemeAbrirTabla($titulo);
			$html .= "<script>\n";
			$html .= "	function Aceptar()\n";
			$html .= "	{\n";
			$html .= "		document.forma.action = \"".$action['aceptar']."\";\n";
			$html .= "		document.forma.submit();\n";
			$html .= "	}\n";
			$html .= "	function Cerrar()\n";
			$html .= "	{\n";
			$html .= "		window.opener.document.actualizar.submit(); window.close();\n";
			$html .= "	}\n";
			$html .= "	function Cancelar()\n";
			$html .= "	{\n";
			$html .= "		document.forma.action = \"".$action['cancelar']."\";\n";
			$html .= "		document.forma.submit();\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			$html .= "<form name=\"forma\" action=\"".$action['cerrar']."\" method=\"post\">\n";
			$html .= "<table align=\"center\" width=\"90%\" class=\"modulo_table_list\">\n";
			$html .= "	<tr>\n";
			$html .= "		<td class=\"label\" align=\"".$align."\" colspan=\"3\">\n";
			$html .= "			<br>".$mensaje."<br>\n";
			$html .= "		</td>\n";
			if($this->MensajeCitasAsignadas())
			{
			//jab--Actualizacion Error calculo cita auto
			//echo $this->ASIGNO1." ".$this->cnt_citas_asignar1;
			
				//if($this->ASIGNO1 == ($this->cnt_citas_asignar1 - 1) || ($this->ASIGNO1 - ($this->cnt_citas_asignar1 - 1)) == 0)
				//{
				$to= $this->ASIGNO1;
				$to2= ($this->cnt_citas_asignar1 - 1) - $this->ASIGNO1;
				//echo "Asigno: ".$this->ASIGNO1." citas a asignar: ".$this->cnt_citas_asignar1." to: ".$to." to2: ".$to2;
				
				//exit();
				//	$jab=(12+29)/21;
				//	echo "este es el metodo: ".$jab;
				//}
				//else
				/*{
				$to=$this->ASIGNO1-1;
				$to2=$this->cnt_citas_asignar1 - $this->ASIGNO1;
				echo $to;
				echo $to2;
				//exit();
				}*/
				
				$html .= "	<tr>\n";
				$html .= "		<td class=\"label\" align=\"".$align."\" colspan=\"3\">\n";
				$html .= "			<br>LAS CITAS QUE SE ASIGNARON AUTOMATICAMENTE FUERON: ".$to."<br>\n";
				$html .= "		</td>\n";
				$html .= "	</tr>\n";
				if(!empty($this->citas_asignadas))
				{
					$html .= "	<tr>\n";
					$html .= "	<td class=\"label\" align=\"".$align."\" colspan=\"3\">\n";
					
					$html .= "<table cellspacing=\"1\"  cellpadding=\"0\" border=\"0\" width=\"99%\" align=\"center\" >";
					$html .= "<tr class=\"modulo_table_list_title\" align=\"center\">";
					$html .= "<td >CITA Nº</td>";
					$html .= "<td >FECHA CITA</td>";
					$html .= "<td >DURACION</td>";
					$html .= "<td >PROFESIONAL</td>";
					$html .= "<td >CONSULTORIO</td></tr>";
					
					for($k=0;$k<$this->ASIGNO1;$k++)
					{
						$html .= "<tr height='25' class=\"modulo_list_oscuro\"  align=\"center\">";	
						$html .= "<td>".$this->citas_asignadas[$k][0]."</td>";
						$html .= "<td>".$this->citas_asignadas[$k][1]."</td>";
						$html .= "<td>".$this->citas_asignadas[$k][2]."</td>";
						$html .= "<td>".$this->citas_asignadas[$k][3]."</td>";
						$html .= "<td>".$this->citas_asignadas[$k][4]."</td>";
						$html .= "</tr>";
					}
					$html .= "</table>";
					$html .= "</td>\n";
					$html .= "</tr>\n";
				}
				
				$html .= "	<tr>\n";
				$html .= "		<td class=\"label\" align=\"".$align."\" colspan=\"3\">\n";
				$html .= "			<br>CANTIDAD DE CITAS POR ASIGNAR: ".$to2."<br>\n";
				$html .= "		</td>\n";
				$html .= "	</tr>\n";
				//jab--fin
			}
			$html .= "	</tr>\n";
			$html .= "</table>\n";
			$html .= "<table align=\"center\" width=\"60%\">\n";
			$html .= "	<tr>\n";
			$html .= "		<td align=\"center\">\n";
			$html .= "			<input type=\"submit\" class=\"input-submit\" value=\"Aceptar\" >\n";
			$html .= "		</td>\n";
						
			if($action['cancelar'])
			{
				$html .= "	<td align=\"center\">\n";
				$html .= "		<input type=\"button\" class=\"input-submit\" value=\"Cancelar\" onclick=\"Cancelar()\">\n";
				$html .= "	</td>\n";
				
			}
			$html .= "	</tr>\n";
			$html .= "</table>\n";
			$html .= "</form>\n";
			$html .= ThemeCerrarTabla();
			return $html;
		}
		
		function MensajeCitasAsignadas()
		{
			//echo '<br>paso:'.$PASO;
			/*if($CORTO)
			echo '<br>paso:'.$CORTO;*/
			if($this->ASIGNO1 > 0)
			{
				return true;
			}
			else
			{
				return false;
			}	
		}
		
		/*************************************************************************************
		* 
		* @return String
		*************************************************************************************/
		function CumplirOrdenes($departamento,$datos,$action,$cumplidos)
		{
			IncludeClass('Cumplimiento','','app','Os_CentralAtencion');
			$cmp = new Cumplimiento();
			//PRINT_R($action);
			$aos = new AtencionOs();			
			$cargos = $aos->ObtenerCargosOrdenesC($departamento,$datos['paciente_id'],$datos['tipo_id'],null,null,null,$datos['orden_id']);
			$profesionales = $cmp->ComboProfesionales($departamento);

			$cargosA = array();
			foreach($datos['cargos'] as $keyt => $os)
				$cargosA[$os] = $os;
			
			$opciones = "";
			foreach($profesionales as $key => $usuario)
				$opciones .= "				<option value=\"".$usuario['usuario_id']."\">".$usuario['nombre']."</option>\n";
			
			$html  = ThemeAbrirTabla("CARGOS A CUMPLIR DE LA ORDEN N ".$datos['orden_id']);
			$html .= "<form name=\"forma\" action=\"javascript:EvaluarDatos(document.forma)\" method=\"post\">\n";
			$html .= "<center><div style=\"width:90%\" id=\"error\" class=\"label_error\"></div><center>\n";
			$html .= "<table width=\"100%\" class=\"modulo_table_list\">\n";
			$html .= "	<tr class=\"modulo_table_list_title\">\n";
			$html .= "		<td valign=\"top\" width=\"20%\" colspan=\"2\">TARIF. - CARGO</td>\n";
			$html .= "		<td valign=\"top\" width=\"40%\">DESCRIPCION</td>\n";
			$html .= "		<td valign=\"top\" width=\"5%\" title=\"CANTIDAD A CUMPLIR\">CNT</td>\n";
			$html .= "		<td valign=\"top\">PROFESIONAL</td>\n";
			$html .= "	</tr>\n";
	 
			$i=0;			
			$k=0;			
			foreach($cargos[$datos['plan_id']] as $key => $valores)
			{
				foreach($valores as $keyC => $cups)
				{
					$dcargo = "";
					foreach($cups as $keyCC => $cargos)
					{
						if(!empty($cargosA[$cargos['os_maestro_cargos_id']]))
						{
							$html .= "	<tr class=\"modulo_list_claro\">\n";
							$html .= "		<td width=\"10%\" align=\"center\">\n";
							$html .= "			".$cargos['tarifario_id']."\n";
							$html .= "		</td>\n";
							$html .= "		<td width=\"10%\" align=\"center\">\n";
							$html .= "			".$cargos['cargo']."\n";
							$html .= "		</td>\n";
							
							$mrk = "";
							$des = $cargos['descripcion_cargo'];
							if(strlen($cargos['descripcion_cargo']) > 30)
							{
								$des = substr($cargos['descripcion_cargo'],0,30)."...";
								$mrk = "title=\"".$cargos['descripcion_cargo']."\" ";
							}
							$html .= "		<td style=\"text-align:justify\" $mrk >".$des." </td>\n";
							
							$html .= "		<td align=\"center\">\n";
							if($cumplidos[$cargos['os_maestro_cargos_id']]['sw_cumplimiento_parcial'] == '1' && $cargos['cantidad_pendiente'] > 1)
							{
								//jab -- desabilita el select ya q no es necesario mostrar la cantidad de cargos porq es la primera cita
								$variable = ModuloGetVar('app','Os_CentralAtencion','ActivaSelectCargos');
								if($variable=='1')
								{
								$html .= "			<select id=\"cantidad\" name=\"cantidad[".$cargos['os_maestro_cargos_id']."]\" class=\"select\" disabled>\n";
								for($j = 1; $j <=$cargos['cantidad_pendiente']; $j++ )
									$html .= "				<option value=\"".$j."\">".$j."</option>\n";
								}
								else
								{
								$html .= "			<select id=\"cantidad\" name=\"cantidad[".$cargos['os_maestro_cargos_id']."]\" class=\"select\">\n";
								$html .= "				<option value=\"-1\">--</option>\n";
								for($j = 1; $j <=$cargos['cantidad_pendiente']; $j++ )
									$html .= "				<option value=\"".$j."\">".$j."</option>\n";
								}
								$html .= "			</select>\n";
								//jab
							}
							else
							{
								$html .= "			<input type=\"hidden\" id=\"cantidad\" name=\"cantidad[".$cargos['os_maestro_cargos_id']."]\" value=\"".$cargos['cantidad_pendiente']."\">\n";
								$html .= "			<label class=\"normal_10AN\">".$cargos['cantidad_pendiente']."</label>\n";
							}
							$html .= "		</td>\n";
							$html .= "		<td>\n";
							if($cumplidos[$cargos['os_maestro_cargos_id']]['sw_liquidar_honario'] == '1')
							{
								$html .= "			<select id=\"profesional\" name=\"profesional[".$cargos['os_maestro_cargos_id']."]\" class=\"select\">\n";
								$html .= "				<option value=\"-1\">---SELECCIONAR---</option>\n";
								$html .= $opciones;
								$html .= "			</select>\n";
								$i++;
							}
							$html .= "		</td>\n";
							$html .= "	</tr>\n";
							$k++;
						}
					}
				}
			}
			
			$html .= "</table>\n";
			$html .= "	<table align=\"center\" width=\"60%\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td align=\"center\">\n";
			$html .= "				<input type=\"submit\" class=\"input-submit\" value=\"Aceptar\" >\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "</form>\n";
			$html .= "<script>\n";
			$html .= "	function EvaluarDatos(frm)\n";
			$html .= "	{\n";
			$html .= "		val = ".$i.";\n";
			$html .= "		cnt = ".$k.";\n";
			$html .= "		error = document.getElementById('error');\n";
			$html .= "		if(val == 1)\n";
			$html .= "		{\n";
			$html .= "			if(frm.profesional.value == '-1')\n";
			$html .= "			{\n";
			$html .= "				mensaje = \"SE DEBE SELECCIONAR EL PROFESIONAL ASOCIADO AL CARGO\";\n";
			$html .= "				error.innerHTML = mensaje;\n";
			$html .= "				return;\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "		else\n";
			$html .= "		{\n";
			$html .= "			for(i = 0; i<val ; i++ )\n";
			$html .= "			{\n";
			$html .= "				if(frm.profesional[i].value == '-1')\n";
			$html .= "				{\n";
			$html .= "					mensaje = \"EXISTEN CARGOS, A LOS QUE NO SE LES HA ASIGNADO UN PROFESIONAL\";\n";
			$html .= "					error.innerHTML = mensaje;\n";
			$html .= "					return;\n";
			$html .= "				}\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "		if(cnt == 1)\n";
			$html .= "		{\n";
			$html .= "			if(frm.cantidad.value == '-1')\n";
			$html .= "			{\n";
			$html .= "				mensaje = \"SE DEBE ESPECIFICAR LA CANTIDAD A CUMPLIR DEL CARGO\";\n";
			$html .= "				error.innerHTML = mensaje;\n";
			$html .= "				return;\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "		else\n";
			$html .= "		{\n";
			$html .= "			for(i = 0; i<cnt ; i++ )\n";
			$html .= "			{\n";
			$html .= "				if(frm.cantidad[i].value == '-1')\n";
			$html .= "				{\n";
			$html .= "					mensaje = \"EXISTEN CARGOS, A LOS QUE NO SE LES HA ESPECIFICADO UNA CANTIDAD A CUMPLIR\";\n";
			$html .= "					error.innerHTML = mensaje;\n";
			$html .= "					return;\n";
			$html .= "				}\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "		error.innerHTML = '';\n";
			$html .= "		frm.action = \"".$action['aceptar']."\";\n";
			$html .= "		frm.submit();\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			$html .= ThemeCerrarTabla();
			return $html;
		}
	}
?>