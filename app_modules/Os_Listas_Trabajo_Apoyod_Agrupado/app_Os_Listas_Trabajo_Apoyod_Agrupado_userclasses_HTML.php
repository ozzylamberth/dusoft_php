<?php

/**
 * $Id: app_Os_Listas_Trabajo_Apoyod_Agrupado_userclasses_HTML.php,v 1.1 2009/07/17 20:01:42 johanna Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo de Listas de Trabajo de los Apoyos Diagnosticos (PHP).
 * Modulo para el manejo de listas de trabajo para los Apoyos Diagnosticos
 */

IncludeLib('historia_clinica');
class app_Os_Listas_Trabajo_Apoyod_Agrupado_userclasses_HTML extends app_Os_Listas_Trabajo_Apoyod_Agrupado_user
{
    //Constructor de la clase app_Os_ListaTrabajo_userclasses_HTML
    function app_Os_Listas_Trabajo_Apoyod_Agrupado_userclasses_HTML()
    {
            $this->salida='';
            $this->app_Os_Listas_Trabajo_Apoyod_Agrupado_user();
            return true;
    }


    function SetStyle($campo)
    {
            if ($this->frmError[$campo] || $campo=="MensajeError")
            {
                    if ($campo=="MensajeError")
                    {
                            $arreglo=array('numero'=>$numero,'prefijo'=>$prefijo);
                            return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
                    }
                    return ("label_error");
            }
            return ("label");
    }


    /*
    * Funcion donde se visualiza el encabezado de la empresa.
    * @return boolean
    */
    function Encabezado()
    {
            $this->salida .= "<table  border=\"0\" class=\"modulo_table_list\" width=\"80%\" align=\"center\" >";
            $this->salida .= " <tr class=\"modulo_table_title\">";
            $this->salida .= " <td>EMPRESA</td>";
            $this->salida .= " <td>CENTRO UTILIDAD</td>";
            $this->salida .= " <td>DEPARTAMENTO</td>";
            $this->salida .= " </tr>";
            $this->salida .= " <tr align=\"center\">";
            $this->salida .= " <td class=\"modulo_list_claro\" >".$_SESSION['LTRABAJOAPOYOD']['NOM_EMP']."</td>";
            $this->salida .= " <td class=\"modulo_list_claro\">".$_SESSION['LTRABAJOAPOYOD']['NOM_CENTRO']."</td>";
            $this->salida .= " <td class=\"modulo_list_claro\" >".$_SESSION['LTRABAJOAPOYOD']['NOM_DPTO']."</td>";
            $this->salida .= " </tr>";
            $this->salida .= " </table>";
            return true;
    }


    /**
    * Se utilizada listar en el combo los diferentes tipo de identificacion de los pacientes
    * @access private
    * @return void
    */
    function BuscarIdPaciente($tipo_id,$TipoId='')
    {
            foreach($tipo_id as $value=>$titulo)
            {
                    if($value==$TipoId)
                    {
                            $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
                    }
                    else
                    {
                            $this->salida .=" <option value=\"$value\">$titulo</option>";
                    }
            }
    }


    /**
    * Esta funcion realiza la busqueda de apoyos diagnosticos
    * según filtros como depto, listas de trabajo,
    * tipo de docuemnto, documento, nombre, item de la orden, etc.
    * @return boolean
    */
    function FormaMetodoBuscar($arr)
		{
			//print_r($_REQUEST); echo "<br>---------------<br>";
			//print_r($_SESSION); echo "<br>---------------<br>";
			//print_r($arr); echo "<br>---------------<br>";

			unset($_SESSION['APOYO']);
			//$_SESSION['VECTOR DE BUSQUEDA'] = $arr;
			unset($_SESSION['DATOS_APD']);
			unset ($_SESSION['CONSULTANDO_APD']);
			/*
			!!!! CUIDADO ¡¡¡¡
			El calendario mostrado en este formulario genera cada dia del mes
			en un link. Pero, ese link se crea concatenando cada uno de los
			resultados del request. El request de los resultados, que son
			editados a traves del FCKeditor es un html, que termina por
			verse en el calendario.
			VERIFICAR RESULTADO, con estos dos for o conun un set de todo
			el request
			*/
			for($i=0;$i<=9;$i++)
			{
					//if(isset($_REQUEST['resultado0'.$i]))
					if(!empty($_REQUEST['resultado0'.$i]))
							unset($_REQUEST['resultado0'.$i]);
					else
							break;
			}
			for($i=10;$i<=99;$i++)
			{
					//if(isset($_REQUEST['resultado'.$i]))
					if(!empty($_REQUEST['resultado'.$i]))
							unset($_REQUEST['resultado'.$i]);
					else
							break;
			}
			for($i=0;$i<=10;$i++)
			{
					for($j=0;$j<=10;$j++)
					{
							unset($_REQUEST['resultado'.$i.$j]);
					}
			}

			$x = $_REQUEST['op'];
			
			$s1 = "";
			if($x) $s1 = "none";
			
			if($_SESSION['LTRABAJOAPOYOD']['PROFESIONAL'] == 'es profesional')
				$actionM = ModuloGetURL('app','Os_Listas_Trabajo_Apoyod_Agrupado','user','main_pro');
			else
				$actionM = ModuloGetURL('app','Os_Listas_Trabajo_Apoyod_Agrupado','user','main');
			
			$this->salida .= ThemeAbrirTabla('LISTAS DE TRABAJO GRUPALES PARA APOYOS DIAGNOSTICOS');
			$accion=ModuloGetURL('app','Os_Listas_Trabajo_Apoyod_Agrupado','user','BuscarOrden');
			$this->Encabezado();
			$this->salida .= "<br>\n";
			$this->salida .= "<script>\n";
			$this->salida .= "		function chequeoTotal(frm,x)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			try\n";
			$this->salida .= "			{\n";
			$this->salida .= "				for(i=0; i< frm.opcionesl.length; i++)\n";
			$this->salida .= "				{\n";
			$this->salida .= "					frm.opcionesl[i].checked = x\n";
			$this->salida .= "				}\n";
			$this->salida .= "			}\n";
			$this->salida .= "			catch(error)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				frm.opcionesl.checked = x\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "</script>\n";
			$this->salida .= "<div id=\"seleccionar\" style=\"display:$s1\">\n";
			$this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\">\n";
			$this->salida .= "	<tr>\n";
			$this->salida .= "		<td>\n";
			$this->salida .= "			<fieldset><legend class=\"normal_10AN\">LISTAS DE TRABAJO ASIGNADAS</legend>\n";
			$this->salida .= "				<form name=\"formabuscar\" action=\"$accion\" method=\"post\">\n";
			$this->salida .= "					<table width=\"100%\" class=\"modulo_table_list\">\n";
			$this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "							<td align = \"center\" width=\"10%\">NUMERO DE LISTA</td>\n";
			$this->salida .= "							<td align = \"center\" width=\"%\">LISTA DE TRABAJO</td>\n";
			$this->salida .= "							<td align = \"center\" width=\"3%\" title=\"SELECCIONAR TODOS\">\n";
			$this->salida .= "								<input type = checkbox name= 'AllListas' onclick=chequeoTotal(this.form,this.checked)>\n";
			$this->salida .= "							</td>\n";
			$this->salida .= "						</tr>\n";
			
			$chk = "";
			for ($i=0; $i<sizeof($_SESSION['LTRABAJOAPOYOD']['LISTAS']);$i++)
			{
				if ($_SESSION['LTRABAJOAPOYOD']['LISTAS'][$i]['departamento'] == $_SESSION['LTRABAJOAPOYOD']['DPTO'])
				{
					($x[$i] == $_SESSION['LTRABAJOAPOYOD']['LISTAS'][$i]['tipo_os_lista_id'])? $chk = "checked": $chk="";
					$this->salida .= "						<tr class=\"modulo_list_claro\" >\n";
					$this->salida .= "							<td align = \"center\">".$_SESSION['LTRABAJOAPOYOD']['LISTAS'][$i]['tipo_os_lista_id']."</td>\n";
					$this->salida .= "							<td align = \"left\">".$_SESSION['LTRABAJOAPOYOD']['LISTAS'][$i]['nombre_lista']."</td>\n";
					$this->salida .= "							<td align=\"center\"><input type = checkbox id=\"opcionesl\" name= 'op[$i]' value = ".$_SESSION['LTRABAJOAPOYOD']['LISTAS'][$i]['tipo_os_lista_id']." $chk></td>\n";
					$this->salida .= "						</tr>\n";
				}
			}
			$this->salida .= "					</table><br>\n";
			$this->salida .= "					<div id=\"seleccionados\" style=\"display:$s1\">\n";
			$this->salida .= "						<table width=\"70%\" align=\"center\">\n";
			$this->salida .= "							<tr>\n";
			$this->salida .= "								<td align=\"center\">\n";
			$this->salida .= "									<input class=\"input-submit\" type=\"submit\" name=\"Buscar_Cargar_Session\" value=\"Aceptar\">\n";
			$this->salida .= "								</td>\n";
			$this->salida .= "								<td align=\"center\">\n";
			$this->salida .= "									<input class=\"input-submit\" type=\"button\" name=\"volver\" value=\"Volver\" onclick=\"document.location.href='$actionM'\">\n";
			$this->salida .= "								</td>\n";
			$this->salida .= "							</tr>\n";
			$this->salida .= "						</table>\n";
			$this->salida .= "					</div>\n";
			if(empty($x)) $this->salida .= "		</form>\n";
			$this->salida .= "			</fieldset>\n";
			$this->salida .= "		</td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "</table>\n";
			$this->salida .= "</div>\n";
			if(!$x)
			{
				$this->salida .= "<table border=\"0\" align=\"center\"  width=\"80%\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "</table>";
			}
			
			if($x)
			{
				$this->salida .= "	<script>";
				$this->salida .= "		function Revisar(frm,x){";
				$this->salida .= "  		if(x==true){";
				$this->salida .= "				frm.Fecha.value='TODAS LAS FECHAS'";
				$this->salida .= "  		}";
				$this->salida .= "			else{";
				$this->salida .= "				frm.Fecha.value=''";
				$this->salida .= "			}";
				$this->salida .= "		}";
				$this->salida .= "		function MostrarLista(capa,label)\n";
				$this->salida .= "		{\n";
				$this->salida .= "			cap = document.getElementById(capa);\n";
				$this->salida .= "			lab = document.getElementById(label);\n";
				$this->salida .= "  		if(cap.style.display == \"\")\n";
				$this->salida .= "			{\n";
				$this->salida .= "				cap.style.display = \"none\";\n";
				$this->salida .= "				lab.innerHTML = \"VER LISTAS SELECCIONADAS\";\n";
				$this->salida .= "  		}\n";
				$this->salida .= "			else\n";
				$this->salida .= "			{\n";
				$this->salida .= "				cap.style.display = \"\";\n";
				$this->salida .= "				lab.innerHTML = \"OCULTAR LISTAS SELECCIONADAS\";\n";
				$this->salida .= "			}";
				$this->salida .= "		}";
				$this->salida .= "		function AsignarFecha(valor)";
				$this->salida .= "  	{";
				$this->salida .= "			document.formabuscar.Fecha.value = valor;\n";
				$this->salida .= "		}";
				$this->salida .= "	</script>";
			
				$this->salida .= "	<table width=\"80%\" align=\"center\">\n";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td>\n";
				$this->salida .= "			<fieldset><legend class=\"normal_10AN\">CUMPLIMIENTOS</legend>\n";
				$this->salida .= "				<table class=\"modulo_table_list\" border=\"0\" width=\"100%\" align=\"center\">\n";
				$this->salida .= "					<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "						<td align = \"left\" >CRITERIOS DE BUSQUEDA:</td>\n";
				$this->salida .= "					</tr>\n";
				$this->salida .= "					<tr class=\"modulo_list_claro\" >\n";
				$this->salida .= "						<td>\n";
				$this->salida .= "							<table width=\"90%\" align=\"center\" border=\"0\">\n";
				$this->salida .= "								<tr>\n";
				$this->salida .= "									<td class=\"label\">TIPO DOCUMENTO: </td>\n";
				$this->salida .= "									<td colspan=\"3\">\n";
				$this->salida .= "										<select name=\"TipoDocumento\" class=\"select\">";
				$this->salida .= "											<option value= -1 selected>Todos</option>";
				$tipo_id = $this->tipo_id_paciente();
				$this->BuscarIdPaciente($tipo_id,$_REQUEST['TipoDocumento']);
				$this->salida .= "										</select>\n";
				$this->salida .= "									</td>\n";
				$this->salida .= "									<td>\n";
				$this->salida .= "										<a href=\"javascript:MostrarLista('seleccionar','listas')\" class=\"label_error\">\n";
				$this->salida .= "											<img src=\"". GetThemePath() ."/images/auditoria.png\" border=\"0\"><label id ='listas' style=\"cursor:pointer\">VER LISTAS SELECCIONADAS</label>\n";
				$this->salida .= "										</a>\n";
				$this->salida .= "									</td>\n";
				$this->salida .= "								</tr>\n";
				$this->salida .= "								<tr>\n";
				$this->salida .= "									<td class=\"label\">DOCUMENTO: </td>\n";
				$this->salida .= "									<td colspan=\"4\"><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" value = \"".$_REQUEST['Documento']."\"></td>\n";

				$this->salida .= "								</tr>\n";
				$this->salida .= "								<tr>\n";
				$this->salida .= "									<td class=\"label\">NOMBRES</td>\n";
				$this->salida .= "									<td colspan=\"3\"><input type=\"text\" class=\"input-text\" name=\"Nombres\" maxlength=\"64\" size = 30 value = \"".$_REQUEST['Nombres']."\"></td>\n";
				$this->salida .= "								</tr>\n";
				$this->salida .= "								<tr>\n";
				$this->salida .= "									<td class=\"label\">APELLIDOS</td>\n";
				$this->salida .= "									<td colspan=\"4\"><input type=\"text\" class=\"input-text\" name=\"Apellidos\" maxlength=\"64\" size = 30 value = \"".$_REQUEST['Apellidos']."\"></td>\n";
				$this->salida .= "								</tr>\n";
				//buscar por orden
				$this->salida .= "								<tr>\n";
				$this->salida .= "									<td class=\"label\">ITEM DE LA ORDEN</td>\n";
				$this->salida .= "									<td colspan=\"4\"><input type=\"text\" class=\"input-text\" name=\"Numero_Orden\" value = \"".$_REQUEST['Numero_Orden']."\"></td>\n";
				$this->salida .= "								</tr>\n";
				//SUSPENDIDOS TEMPORALMENTE.
				//$this->salida .= "<tr><td class=\"label\">PREFIJO HC</td><td><input type=\"text\" class=\"input-text\" name=\"Historia_Prefijo\" maxlength=\"4\" size = 4 value = \"".$_REQUEST['Historia_Prefijo']."\"></td></tr>";
				//$this->salida .= "<tr><td class=\"label\">NUMERO HC</td><td><input type=\"text\" class=\"input-text\" name=\"Historia_Numero\" maxlength=\"50\" size = 20 value = \"".$_REQUEST['Historia_Numero']."\"></td></tr>";

				$this->salida .= "								<tr>\n";
				$this->salida .= "									<td class=\"label\">No. CUMPLIMIENTO</td><td><input type=\"text\" class=\"input-text\" name=\"Cumplimiento\" maxlength=\"50\" size = 20 value = \"".$_REQUEST['Cumplimiento']."\"></td>\n";
				$this->salida .= "								</tr>\n";
				$this->salida .= "								<tr>\n";
				$this->salida .= "									<td class=\"label\">FECHA</td>\n";
				$this->salida .= "									<td>\n";
				$this->salida .= "										<input type=\"text\" readonly class=\"input-text\" name=\"Fecha\" value = \"".$_REQUEST['Fecha']."\">\n";
				$this->salida .= "									</td>\n";
				$this->salida .= "									<td>\n";
				$this->salida .= "										<a href=\"javascript:AsignarFecha('".date("d-m-Y")."')\" class=\"label_error\">[HOY]</a>\n";
				$this->salida .= "									</td>\n";
				$this->salida .= "									<td>\n";
				$this->salida .= "										<a href=\"javascript:AsignarFecha('')\" class=\"label_error\">[CUALQUIER FECHA]</a>\n";
				$this->salida .= "									</td>\n";
				$this->salida .= "									<td class=\"modulo_list_claro\">\n";
				$this->salida .= "										<b>".ReturnOpenCalendario('formabuscar','Fecha','-')."</b>\n";
				$this->salida .= "									</td>\n";
				$this->salida .= "								</tr>";
				//filtro de pacientes
				$this->salida .= "								<tr>\n";
				$this->salida .= "									<td class=\"label\">EXAMENES: </td>\n";
				$this->salida .= "									<td colspan=\"4\">\n";
				$this->salida .= "										<select name=\"opcion_examenes\" class=\"select\">\n";

				$select = "";
				if ($_SESSION['LTRABAJOAPOYOD']['PROFESIONAL']=='es profesional')
				{
					($_REQUEST['opcion_examenes']==1)? $select = "selected": $select = "";
					$this->salida .= "											<option value= 2 selected>Examenes Transcritos sin Firmar</option>\n";
					$this->salida .= "											<option value= 1 $select>Examenes sin Transcribir</option>\n";
				}
				else
				{
					($_REQUEST['opcion_examenes']==2)? $select = "selected": $select = "";
					$this->salida .= "											<option value= 1 selected>Examenes sin Transcribir</option>\n";
					$this->salida .= "											<option value= 2 $select>Examenes Transcritos sin Firmar</option>\n";
				}

				($_REQUEST['opcion_examenes']==3)? $select = "selected": $select = "";
				$this->salida .= "											<option value= 3 $select>Examenes Transcritos Firmados</option>\n";
				
				($_REQUEST['opcion_examenes']==4)? $select = "selected": $select = "";
				$this->salida .= "											<option value= 4 $select>Todos los Examenes</option>\n";			
				$this->salida .= "										</select>\n";
				$this->salida .= "									</td>\n";
				$this->salida .= "								</tr>\n";
				$this->salida .= "								<tr>\n";
				$this->salida .= "									<td colspan = \"5\" align=\"center\" >\n";
				$this->salida .= "										<table>";
				$this->salida .= "											<tr>\n";
				$this->salida .= "												<td align=\"right\" >\n";
				$this->salida .= "													<input class=\"input-submit\" type=\"submit\" name=\"Buscar_Cargar_Session\" value=\"BUSCAR\">\n";
				$this->salida .= "												</td>\n";
				$this->salida .= "												</form>\n";
				$this->salida .= "												<form name=\"formabuscar1\" action=\"$actionM\" method=\"post\">";
				$this->salida .= "												<td align=\"center\">\n";
				$this->salida .= "													<input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\">\n";
				$this->salida .= "												</td>\n";
				$this->salida .= "												</form>\n";
				$this->salida .= "											</tr>";
				$this->salida .= "										</table>\n";
				$this->salida .= "									</td>\n";
				$this->salida .= "								</tr>\n";
				$this->salida .= "							</table>";
				$this->salida .= "						</td>\n";
				$this->salida .= "					</tr>\n";
				$this->salida .= "				</table>\n";
				$this->salida .= "			</fieldset>\n";
				$this->salida .= "		</td>\n";
				$this->salida .= "	</tr>\n";
				$this->salida .= "</table>\n";

				if(!empty($arr))
				{
					if($this->frmError["MensajeError"] != "")
					{
						$this->salida .= "<table border=\"0\" align=\"center\"  width=\"80%\">";
						$this->salida .= "	".$this->SetStyle("MensajeError");
						$this->salida .= "</table>";
					}

					$this->salida .= "<table border=\"0\" align=\"center\"  width=\"80%\">";
					$this->salida .= "<tr align=\"center\" class=\"modulo_table_title\">";
					$this->salida .= "<td colspan=\"10\" align=\"center\">PROGRAMACION DE LAS LISTAS DE TRABAJO</td>";
					$this->salida .= "</tr><br>";

								//codigo para pintar en el resultado de la busqueda el filtro utilizado.
								$texto = '';
								if ($_SESSION['BUSQUEDA']['opcion_examenes'] == 1)
								{
												$texto = 'EXAMENES SIN TRANSCRIBIR';
								}
								if ($_SESSION['BUSQUEDA']['opcion_examenes'] == 2)
								{
												$texto = 'EXAMENES TRANSCRITOS SIN FIRMAR';
								}
								if ($_SESSION['BUSQUEDA']['opcion_examenes'] == 3)
								{
												$texto = 'EXAMENES TRANSCRITOS FIRMADOS';
								}
								if ($_SESSION['BUSQUEDA']['opcion_examenes'] == 4)
								{
												$texto = 'TODOS LOS EXAMENES';
								}

								if ($texto != '')
								{
												$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
												$this->salida.="<td colspan=\"10\" align=\"center\">FILTRO DE BUSQUEDA: ".$texto."</td>";
												$this->salida.="</tr><br>";
								}
								//fin del pintado del filtro
								$this->salida.="</table>";
								$rep= new GetReports();

								$this->salida.="<table border=\"0\" align=\"center\"  width=\"80%\">";

								if ($_SESSION['LTRABAJOAPOYOD']['TIPO_PRESENTACION'] == '2' and $_SESSION['BUSQUEDA']['opcion_examenes'] == 1)
								//if ($_SESSION['LTRABAJOAPOYOD']['TIPO_PRESENTACION'] == '2')
								{
									
												$this->salida.= "<tr align=\"center\" class=\"modulo_table_list_title\">";
												$this->salida.= "<td width=\"10%\">HISTORIA CLINICA</td>";
												$this->salida.= "<td width=\"10%\">IDENTIFICACION</td>";
												$this->salida.= "<td width=\"25%\">NOMBRE DEL PACIENTE</td>";
												$this->salida.= "<td width=\"35%\" colspan=\"2\">SERVICIO</td>";
												$this->salida.= "</tr>";
												$bandera=0;
												foreach($arr as $idPaciente=>$vector1)
												{
														foreach($vector1 as $servicio=>$vector2)
														{
																foreach($vector2 as $cumplimiento=>$datos)
																{
																		$cumplimiento=$this->ConvierteCumplimiento($datos[fecha_cumplimiento],$datos[numero_cumplimiento],$_SESSION['LTRABAJOAPOYOD']['DPTO']);
																		if( $i % 2){ $estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
																		if($idPaciente!= $idPacienteAnt)
																		{
																						if($bandera==1)
																						{
																										$this->salida.="</table>";
																										$this->salida.="</td>";
																										$this->salida.="</tr>";
																						}
																						$servicioAnt='';
																						$this->salida.="<tr class='$estilo' align=\"center\"'>";
																						$this->salida.="<td width=\"10%\" rowspan=\"".sizeof($vector1)."\">".$datos[historia_prefijo]." - ".$datos[historia_numero]."</td>";
																						$this->salida.="<td width=\"10%\" rowspan=\"".sizeof($vector1)."\">".$datos[tipo_id_paciente]." - ".$datos[paciente_id]."</a></td>";

																						//consulta por paciente
																						$accion=ModuloGetURL('app','Os_Listas_Trabajo_Apoyod_Agrupado','user','GetForma',array('accion'=>'capturar_resultados', 'tipo_id_paciente' =>$datos[tipo_id_paciente], 'paciente_id' =>$datos[paciente_id], 'nombre' =>$datos[nombre]));
																						$this->salida.="<td width=\"25%\" rowspan=\"".sizeof($vector1)."\"><a href=".$accion.">".$datos[nombre]."</a></td>";

																						//consulta por servicio
																						$accion=ModuloGetURL('app','Os_Listas_Trabajo_Apoyod_Agrupado','user','GetForma',array('accion'=>'capturar_resultados', 'tipo_id_paciente' =>$datos[tipo_id_paciente], 'paciente_id' =>$datos[paciente_id], 'nombre' =>$datos[nombre], 'servicio' =>$datos[servicio]));
																						$this->salida.="<td width=\"25%\"><a href=".$accion.">".$datos[servicio_descripcion]."</a></td>";

																						$this->salida.="<td width=\"25%\">";
																						$this->salida.="  <table border=\"0\" align=\"center\"  width=\"80%\">";
																						$this->salida.="  <tr class='$estilo' align=\"center\"'>";

																						//mauroB
																						//consulta por cumplimiento
																						$accion=ModuloGetURL('app','Os_Listas_Trabajo_Apoyod_Agrupado','user','GetForma',array('accion'=>'capturar_resultados', 'tipo_id_paciente' =>$datos[tipo_id_paciente], 'paciente_id' =>$datos[paciente_id], 'nombre' =>$datos[nombre], 'numero_cumplimiento' =>$datos[numero_cumplimiento], 'fecha_cumplimiento' =>$datos[fecha_cumplimiento]));
/*                                              $this->salida.="  <td width=\"30%\"><font color='#4D6EAB'>".$cumplimiento." </font></td>";
																						$this->salida.="  <td width=\"70%\"><a href=".$accion.">".$this->FechaStampNombreMes($datos[fecha_cumplimiento])."</a></td>";*/
																						$this->salida.="  <td width=\"70%\" title= ".$this->FechaStampNombreMes($datos[fecha_cumplimiento])." ><a href=".$accion."><font color='#4D6EAB'>".$cumplimiento."</font></a></td>";

																						//fin mauroB

																						$this->salida.="  </tr>";
																						$abre=1;
																						$idPacienteAnt=$idPaciente;
																						$bandera=1;
																						$servicioAnt=$servicio;
																		}
																		else
																		{
																						if($servicio!=$servicioAnt)
																						{
																										if($abre==1)
																										{
																														$this->salida.="</table>";
																														$this->salida.="</td>";
																														$this->salida.="</tr>";
																										}
																										$this->salida.="<tr class='$estilo' align=\"center\"'>";

																										//consulta por servicio
																										$accion=ModuloGetURL('app','Os_Listas_Trabajo_Apoyod_Agrupado','user','GetForma',array('accion'=>'capturar_resultados', 'tipo_id_paciente' =>$datos[tipo_id_paciente], 'paciente_id' =>$datos[paciente_id], 'nombre' =>$datos[nombre], 'servicio' =>$datos[servicio]));
																										$this->salida.="<td width=\"25%\"><a href=".$accion.">".$datos[servicio_descripcion]."</a></td>";

																										$this->salida.="<td width=\"25%\">";
																										$this->salida.="  <table border=\"0\" align=\"center\"  width=\"80%\">";
																										$this->salida.="  <tr class='$estilo' align=\"center\"'>";
																										//mauroB
																										//consulta por cumplimiento
																										$accion=ModuloGetURL('app','Os_Listas_Trabajo_Apoyod_Agrupado','user','GetForma',array('accion'=>'capturar_resultados', 'tipo_id_paciente' =>$datos[tipo_id_paciente], 'paciente_id' =>$datos[paciente_id], 'nombre' =>$datos[nombre], 'numero_cumplimiento' =>$datos[numero_cumplimiento], 'fecha_cumplimiento' =>$datos[fecha_cumplimiento]));
																										//$this->salida.="  <td width=\"70%\"><a href=".$accion.">".$this->FechaStampNombreMes($datos[fecha_cumplimiento])."</a></td>";
																										$this->salida.="  <td width=\"70%\" title= ".$this->FechaStampNombreMes($datos[fecha_cumplimiento])." ><a href=".$accion."><font color='#4D6EAB'>".$cumplimiento."</font></a></td>";
																										//fin mauroB
																										$this->salida.="  </tr>";
																										$abre=1;
																										$servicioAnt=$servicio;
																						}
																						else
																						{
																										$this->salida.="<tr class='$estilo' align=\"center\"'>";
																										//mauroB
																										//consulta por cumplimiento
																										$accion=ModuloGetURL('app','Os_Listas_Trabajo_Apoyod_Agrupado','user','GetForma',array('accion'=>'capturar_resultados', 'tipo_id_paciente' =>$datos[tipo_id_paciente], 'paciente_id' =>$datos[paciente_id], 'nombre' =>$datos[nombre], 'numero_cumplimiento' =>$datos[numero_cumplimiento], 'fecha_cumplimiento' =>$datos[fecha_cumplimiento]));
																										//$this->salida.="<td width=\"70%\"><a href=".$accion.">".$this->FechaStampNombreMes($datos[fecha_cumplimiento])."</a></td>";
																										$this->salida.="  <td width=\"70%\" title= ".$this->FechaStampNombreMes($datos[fecha_cumplimiento])." ><a href=".$accion."><font color='#4D6EAB'>".$cumplimiento."</font></a></td>";
																										//fin mauroB
																										$this->salida.="</tr>";
																						}
																		}
																}
														}
												}
												$this->salida.="</table>";
												$this->salida.="</td>";
												$this->salida.="</tr>";
												
								}
								else
								{
									for($i=0;$i<sizeof($arr);$i++)
									{
										$cumplimiento=$this->ConvierteCumplimiento($arr[$i][fecha_cumplimiento],$arr[$i][numero_cumplimiento],$_SESSION['LTRABAJOAPOYOD']['DPTO']);
										if( $i % 2){ $estilo='modulo_list_claro';}
										else {$estilo='modulo_list_oscuro';}
										if($i== 0)
										{
														$pintar_titulo = 1;
										}
										else
										{
												if($arr[$i][nombre_lista] != $arr[$i-1][nombre_lista])
														{
																		$pintar_titulo = 1;
														}
										}
										if ($pintar_titulo == 1)
										{
														$this->salida .= "<tr align=\"center\" class=\"modulo_table_title\">";
														$this->salida .= "<td colspan=\"9\" align=\"center\">".$arr[$i][nombre_lista]."</td>";
														$this->salida .= "</tr>";
														$this->salida .= "<tr align=\"center\" class=\"modulo_table_list_title\">";
														//MauroB
														$this->salida .= "<td colspan=\"1\" width=\"7%\">No. CUMPLIMIENTO</td>";
//                                          $this->salida .= "<td width=\"3%\">No.</td>";
//                                          $this->salida .= "<td width=\"4%\">FECHA DEL CUMPLIMIENTO</td>";
														//Fin MauroB
														$this->salida .= "<td width=\"4%\">ITEM DE LA ORDEN</td>";
														$this->salida .= "<td width=\"4%\">SERVICIO</td>";
														$this->salida .= "<td width=\"4%\">HISTORIA CLINICA</td>";
														$this->salida .= "<td width=\"4%\">IDENTIFICACION</td>";
														$this->salida .= "<td width=\"21%\">NOMBRE DEL PACIENTE</td>";
														$this->salida .= "<td width=\"5%\">CARGO</td>";
														$this->salida .= "<td width=\"25%\">DESCRIPCION</td>";
														$this->salida .= "<td width=\"10%\">OPCION</td>";
														$this->salida .= "</tr>";
										}

										$this->salida.="<tr class=\"$estilo\" align=\"center\">";
										//MauroB
										$this->salida.="  <td colspan=\"1\">".$cumplimiento."</td>";
//                                  $this->salida.="  <td ><font color=\"#4D6EAB\">".$arr[$i][numero_cumplimiento]." </font></td>";
//                                  $this->salida.="  <td >".$arr[$i][fecha_cumplimiento]."</td>";
										//MauroB
										$this->salida.="  <td >".$arr[$i][numero_orden_id]."</td>";
										$this->salida.="  <td >".$arr[$i][servicio_descripcion]."</td>";
										$this->salida.="  <td >".$arr[$i][historia_prefijo]." - ".$arr[$i][historia_numero]."</td>";
										$this->salida.="  <td >".$arr[$i][tipo_id_paciente]." - ".$arr[$i][paciente_id]."</td>";
										$this->salida.="  <td >".$arr[$i][nombre]."</td>";
										$this->salida.="  <td >".$arr[$i][cargo]."</td>";
										$this->salida.="  <td >".$arr[$i][descripcion]."</td>";

										if ($arr[$i][resultado_id])
										{
			//OPCION PARA MODIFICAR
														if(!$arr[$i][usuario_id_profesional_autoriza])//variable que me indica si el examen esta o no firmado
														{
																		$accion=ModuloGetURL('app','Os_Listas_Trabajo_Apoyod_Agrupado','user','GetForma',array('accion'=>'modificacion_resultados','resultado_id' => $arr[$i][resultado_id], 'evolucion_id'=>$arr[$i][evolucion_id], 'hc_os_solicitud_id'=>$arr[$i][hc_os_solicitud_id], 'nombre'=>$arr[$i][nombre], 'fecha_cumplimiento'=>$arr[$i][fecha_cumplimiento], 'numero_cumplimiento'=>$arr[$i][numero_cumplimiento] ));
																		$this->salida .= "<td width=\"10%\" valign=\"center\"><a href=".$accion."><img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>&nbsp;&nbsp;MODIFICAR RESULTADO</a>";
																		//lo de alex
																		//$reporte= new GetReports();
																		$mostrar=$rep->GetJavaReport('app','Os_Listas_Trabajo_Apoyod_Agrupado','examenes_html',array('resultado_id'=>$arr[$i][resultado_id],'sw_firma'=>false),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
																		$nombre_funcion=$rep->GetJavaFunction();
																		$this->salida .=$mostrar;
																		//$this->salida .= "    <td><br><input class=\"input-submit\" name=\"Cancelar\" type=\"button\" value=\"IMPRIMIR\" onclick=\"javascript:$nombre_funcion\"></td>";
																		$this->salida.="<br><a href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR</a></td>";
																		//fin de alex
														}
														else  //OPCION PARA CONSULTAR
														{
																		$this->salida .= "<td width=\"10%\" valign=\"center\">";
																		$this->salida .= "<table>";
																		$this->salida.="<tr class='$estilo' align='center'>";
																		$accion=ModuloGetURL('app','Os_Listas_Trabajo_Apoyod_Agrupado','user','GetForma',array('accion'=>'modificacion_resultados', 'resultado_id' => $arr[$i][resultado_id], 'evolucion_id'=>$arr[$i][evolucion_id], 'hc_os_solicitud_id'=>$arr[$i][hc_os_solicitud_id], 'consultando'=>'1', 'usuario_profesional'=>$arr[$i][usuario_id_profesional], 'nombre'=>$arr[$i][nombre], 'fecha_cumplimiento'=>$arr[$i][fecha_cumplimiento], 'numero_cumplimiento'=>$arr[$i][numero_cumplimiento], 'usuario_profesional_autoriza'=>$arr[$i][usuario_id_profesional_autoriza]));
																		$this->salida .= "<td width=\"10%\" valign=\"center\"><a href=".$accion."><img src=\"". GetThemePath() ."/images/flecha_der.gif\" width=\"10\" height=\"10\" valign=\"center\"><font color='#4D6EAB'>&nbsp;&nbsp;REVISADO POR PROFESIONAL</font></a></td>";
																		$this->salida .= "</tr>";
																		$this->salida.="<tr class=\"$estilo\" align=\"center\">";

																		/*$accion=ModuloGetURL('app','Os_Listas_Trabajo_Apoyod_Agrupado','user','ImprimirApoyoDiagnostico',array('resultado_id'=>$arr[$i][resultado_id]));
																		$this->salida.="  <td width=\"10%\" valign=\"center\"><a href='$accion'><img src=\"".GetThemePath()."/images/imprimir.png\" border='0' width='15' height='15'>IMPRIMIR</a></td>";*/

																		//lo de alex
																		//$reporte= new GetReports();
																		$mostrar=$rep->GetJavaReport('app','Os_Listas_Trabajo_Apoyod_Agrupado','examenes_html',array('resultado_id'=>$arr[$i][resultado_id],'sw_firma'=>true),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
																		$nombre_funcion=$rep->GetJavaFunction();
																		$this->salida .=$mostrar;
																		//$this->salida .= "    <td><br><input class=\"input-submit\" name=\"Cancelar\" type=\"button\" value=\"IMPRIMIR\" onclick=\"javascript:$nombre_funcion\"></td>";
																		$this->salida.="<td width=\"10%\" valign=\"center\"><a href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR</a></td>";
																		//fin de alex

																		$this->salida .= "</tr>";
																		$this->salida .= "</table>";
																		$this->salida .= "</td>";
														}
										}
										else //OPCION PARA INSERTAR UN RESULTADO
										{
														$accion=ModuloGetURL('app','Os_Listas_Trabajo_Apoyod_Agrupado','user','Plantillas_Examenes',array('tipo_id_paciente'=>$arr[$i][tipo_id_paciente],'cargo'=>$arr[$i][cargo],'paciente_id'=>$arr[$i][paciente_id],'numero_orden_id'=>$arr[$i][numero_orden_id], 'hc_os_solicitud_id'=>$arr[$i][hc_os_solicitud_id], 'nombre'=>$arr[$i][nombre], 'evolucion_id'=>$arr[$i][evolucion_id], 'titulo'=>$arr[$i][descripcion]));

														//la historia clinica antes de ingresar el resultado
														//OJO CLAUDIA YO CONSIDERO QUE ESTE SIGUE IGUAL Y NO HAY PROBLEMA CONFIRMAR.
														if($_SESSION['LTRABAJOAPOYOD']['PROFESIONAL']=='es profesional')
														{
																		$this->salida .="<td width=\"10%\" valign=\"center\">";
																		$this->salida .="<table>";
																		$this->salida .="<tr class='$estilo' align=\"center\">";
																		$this->salida .="<td width=\"10%\" valign=\"center\"><a href=".$accion."><img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10' valign=\"center\">&nbsp;RESULTADO</a></td>";
																		$this->salida .="</tr>";

																		if ($arr[$i][evolucion_id]!= NULL)
																		{
																						$_SESSION['HISTORIACLINICA']['RETORNO']['modulo']='Os_Listas_Trabajo_Apoyod_Agrupado';
																						$_SESSION['HISTORIACLINICA']['RETORNO']['metodo']='BuscarOrden';
																						$_SESSION['HISTORIACLINICA']['RETORNO']['tipo']='user';
																						$_SESSION['HISTORIACLINICA']['RETORNO']['contenedor']='app';
																						$accion1=ModuloHCGetURL($arr[$i][evolucion_id],'','','','',array());
																						$this->salida .="<tr class='$estilo' align=\"center\">";
																						$this->salida .="<td colspan = \"4\" width=\"80%\" valign=\"center\"><a href=".$accion1."><img src=\"". GetThemePath()."/images/pconsultar.png\" width='15' height='15' valign=\"center\">&nbsp;VER HC</a></td>";
																						$this->salida .="</tr>";
																		}
																		$this->salida .="</table>";
																		$this->salida .= "</td>";
														}
														else
														{
																		$this->salida .= "<td width=\"10%\" valign=\"center\"><a href=".$accion."><img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10' valign=\"center\">&nbsp;RESULTADO</a></td>";
														}
														//fin de la hc
										}
										$this->salida.="</tr>";
										$pintar_titulo = 0;
									}//fin del for
								}//fin del else
								$this->salida.="</table>";
								$this->salida .=$this->RetornarBarra();
				}
				else
				{
								$this->salida.="<table border=\"0\" align=\"center\"  width=\"80%\">";
								$this->salida .= $this->SetStyle("MensajeError");
								$this->salida.="</table><br>";
				}
			}
			$this->salida .= ThemeCerrarTabla();
			return true;
		}


  /*
    * Esta funcion le permite al usuario seleccionar el tipo de
    * tecnica que usara para la transcripcion del examen
    * @return boolean
    */
    function frmSeleccion_Tecnica($multitecnica)
    {
            $this->salida= ThemeAbrirTabla('SELECCION DE TECNICA');
            $action=ModuloGetURL('app','Os_Listas_Trabajo_Apoyod_Agrupado','user','GetForma',array('accion'=>'crear_forma_examen'));
            $this->salida .= "<form name=\"formades\" action=\"$action\" method=\"post\">";

            $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
            $this->salida.="<tr class=\"modulo_table_title\">";
            $this->salida.="  <td align=\"center\">ID DEL PACIENTE</td>";
            $this->salida.="  <td align=\"center\">NOMBRE DEL PACIENTE</td>";
            $this->salida.="</tr>";
            $this->salida.="<tr class=\"modulo_table_title\">";
            $this->salida.="  <td align=\"center\">".$_SESSION['LISTA']['APOYO']['tipo_id_paciente'].": ".$_SESSION['LISTA']['APOYO']['paciente_id']."</td>";
            $this->salida.="  <td align=\"center\">".$_SESSION['LISTA']['APOYO']['nombre']."</td>";
            $this->salida.="</tr>";
            $this->salida.="</table>";

            $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
            $this->salida.="<tr class=\"modulo_table_title\">";
            $this->salida.="  <td align=\"center\" colspan=\"1\">".$_SESSION['LISTA']['APOYO']['titulo']."</td>";
            $this->salida.="</tr><br>";
            $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
            $this->salida.="  <td align=\"center\" colspan=\"1\">SELECCIONE LA TECNICA PARA EL EXAMEN</td>";
            $this->salida.="</tr>";
            $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";

            $this->salida.="<td width=\"35%\" align = \"center\" >";
            $this->salida.="<select size = \"1\" name = \"selector_multitecnica\"  class =\"select\">";
            if (empty($_SESSION['LISTA']['APOYO']['tecnica_id']))
            {
                    for($i=0;$i<sizeof($multitecnica);$i++)
                    {
                            if ($multitecnica[$i][sw_predeterminado] != '1')
                            {
                                    $this->salida.="<option value = ".$multitecnica[$i][tecnica_id].">".$multitecnica[$i][nombre_tecnica]."</option>";
                            }
                            else
                            {
                                    $this->salida.="<option value = ".$multitecnica[$i][tecnica_id]." selected >".$multitecnica[$i][nombre_tecnica]."</option>";
                            }
                    }
            }
            else
            {
                    for($i=0;$i<sizeof($multitecnica);$i++)
                    {
                            if ($_SESSION['LISTA']['APOYO']['tecnica_id'] != $multitecnica[$i][tecnica_id])
                            {
                                    $this->salida.="<option value = ".$multitecnica[$i][tecnica_id].">".$multitecnica[$i][nombre_tecnica]."</option>";
                            }
                            else
                            {
                                    $this->salida.="<option value = ".$multitecnica[$i][tecnica_id]." selected >".$multitecnica[$i][nombre_tecnica]."</option>";
                            }
                    }
            }
            $this->salida.="</select>";
            $this->salida.="</td>";
            $this->salida.="</tr>";
            $this->salida.= "<tr>";
            $this->salida.= "<td  align=\"center\"><br><input class=\"input-submit\" name=\"siguiente\" type=\"submit\" value=\"SIGUIENTE\"></td>";
            $this->salida.="</tr>";
            $this->salida.="</table>";
            $this->salida.="</form>";

            $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
            $this->salida .= "<tr>";
            $accion2=ModuloGetURL('app','Os_Listas_Trabajo_Apoyod_Agrupado','user','BuscarOrden');
            $this->salida .= "<form name=\"forma\" action=\"$accion2\" method=\"post\">";
            $this->salida .= "<td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"VOLVER\"></form></td>";
            $this->salida.="</tr>";
            $this->salida.="</table>";
            $this->salida .= ThemeCerrarTablaSubModulo();
            return true;
    }


  /*
    * Esta funcion le permite al usuario realizar la captura de un resultado de
    * forma individual
    * @return boolean
    */
    function frmCrearFormaE()
    {//echo "<br>------------<br>";print_r($_SESSION);

            $this->salida= ThemeAbrirTablaSubModulo('CAPTURA DE RESULTADOS INDIVIDUALES');
      $sexo_paciente = $this->GetSexo($_SESSION['LISTA']['APOYO']['tipo_id_paciente'], $_SESSION['LISTA']['APOYO']['paciente_id']);
            $edad_paciente = $this->Obtener_Edad($_SESSION['LISTA']['APOYO']['tipo_id_paciente'], $_SESSION['LISTA']['APOYO']['paciente_id']);
      $k = 0;
            $this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
            $this->salida.="<tr class=\"modulo_table_title\">";
            $this->salida.="  <td align=\"center\">ID DEL PACIENTE</td>";
            $this->salida.="  <td align=\"center\">NOMBRE DEL PACIENTE</td>";
      $this->salida.="  <td align=\"center\">EDAD DEL PACIENTE</td>";
            $this->salida.="</tr>";
            $this->salida.="<tr class=\"modulo_table_title\">";
            $this->salida.="  <td align=\"center\">".$_SESSION['LISTA']['APOYO']['tipo_id_paciente'].": ".$_SESSION['LISTA']['APOYO']['paciente_id']."</td>";
            $this->salida.="  <td align=\"center\">".$_SESSION['LISTA']['APOYO']['nombre']."</td>";
            $this->salida.="  <td align=\"center\">".$edad_paciente[edad_aprox]."</td>";
            $this->salida.="</tr>";
            $this->salida.="</table><br>";

            $action=ModuloGetURL('app','Os_Listas_Trabajo_Apoyod_Agrupado','user','GetForma',array('accion'=>'insertar'));
            $this->salida .= "<form name=\"formades\" action=\"$action\" method=\"post\">";
            $this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida.="</table>";

            $this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
            $this->salida.="<tr class=\"modulo_table_title\">";
            $this->salida.="<td align=\"center\" width=\"5%\">CARGO</td>";
            $this->salida.="<td align=\"center\" width=\"60%\">EXAMEN</td>";
            $this->salida.="<td align=\"center\" width=\"12%\" colspan=\"3\">OPCIONES</td>";
            $this->salida.="<td align=\"center\" width=\"23%\" class=\"".$this->SetStyle("fecha_realizado$k")."\">FECHA</td>";
            $this->salida.="</tr>";

            $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
            $this->salida.="<td align=\"center\" width=\"5%\">".$_SESSION['LISTA']['APOYO']['cargo']."</td>";
            $this->salida.="<td align=\"center\" width=\"60%\">".strtoupper($_SESSION['LISTA']['APOYO']['titulo'])."</td>";

            if($_SESSION['LISTA']['APOYO']['informacion']=='')
            {
                    $this->salida.="<td align=\"center\" width=\"3%\"><img src=\"".GetThemePath()."/images/Vacio.gif\" title=\"sin Informacion \"  border=\"0\"></td>";
            }
            else
            {
                    $this->salida.="<td align=\"center\" width=\"3%\"><img src=\"'".GetThemePath()."/images/EstacionEnfermeria/info.png'\" title=\"Informacion: ".$_SESSION['LISTA']['APOYO']['informacion']."\"  border=\"0\"></td>";
            }

            //opcion que muestra el link de la historia clinica si es un profesional
            if (!empty($_SESSION['LTRABAJOAPOYOD']['PROFESIONAL']))
            {
                    if ($_SESSION['LISTA']['APOYO']['evolucion_id']!= NULL)
                    {
                            $_SESSION['HISTORIACLINICA']['RETORNO']['modulo']='Os_Listas_Trabajo_Apoyod_Agrupado';
                            //$_SESSION['HISTORIACLINICA']['RETORNO']['metodo']='Capturar_Resultados';
                            $_SESSION['HISTORIACLINICA']['RETORNO']['metodo']='frmCrearFormaE';
                            $_SESSION['HISTORIACLINICA']['RETORNO']['tipo']='user';
                            $_SESSION['HISTORIACLINICA']['RETORNO']['contenedor']='app';
                            $accion=ModuloHCGetURL($_SESSION['LISTA']['APOYO']['evolucion_id'],'','','','',array());
                            $this->salida.="<td align=\"center\" width=\"3%\"><a href=".$accion."><img src=\"".GetThemePath()."/images/honorarios.png\" border='0' title=\"ver HC\"></a></td>";
                    }
                    else
                    {
                            $this->salida.="<td align=\"center\" width=\"3%\"><img src=\"".GetThemePath()."/images/pincumplimiento_citas.png\" border='0' title=\"Solicitado fuera de HC\"></td>";
                    }
            }
            else
            {
                    $this->salida.="<td align=\"center\" width=\"3%\"><img src=\"".GetThemePath()."/images/honorarios.png\" border=\"0\" title=\"sin acceso a HC\"></td>";
            }

            $this->SetJavaScripts('DatosSolicitudApoyo');
            $this->salida.="<td align=\"center\" width=\"3%\"><a href=\"javascript:DatosSolicitudApoyo(".$_SESSION['LISTA']['APOYO']['hc_os_solicitud_id'].", '".$_SESSION['LISTA']['APOYO']['tipo_id_paciente']."', '".$_SESSION['LISTA']['APOYO']['paciente_id']."', '".$_SESSION['LISTA']['APOYO']['nombre']."', '".$_SESSION['LISTA']['APOYO']['cargo']."', '".$_SESSION['LISTA']['APOYO']['titulo']."')\"><img src=\"".GetThemePath()."/images/resumen.gif\" border=\"0\" title=\"Ver Datos Solicitud\">   </a></td>";

            if (empty($_REQUEST['fecha_realizado']))
            {
                    $_REQUEST['fecha_realizado'] = date('d-m-Y');
            }
            $this->salida .="<td align=\"center\" width=\"23%\"><input type=\"text\" readonly class=\"input-text\" size=\"10\" maxlength=\"10\" value = \"".$_REQUEST['fecha_realizado']."\" name=\"fecha_realizado\" onFocus=\"this.select();\"  onBlur=\"IsValidDate(this,'YYYY-MM-DD')\" onKeyUp=\"setDate(this,'YYYY-MM-DD','es')\">".ReturnOpenCalendario('formades','fecha_realizado','-')."</td>" ;

            $this->salida.="</tr>";
            $this->salida.="</table>";

            //llama a la funcion que consulta los examenes que pertenecen a ese titulo examen
            $vector=$this->ConsultaComponentesExamen($_SESSION['LISTA']['APOYO']['cargo'], $_SESSION['LISTA']['APOYO']['tecnica_id'], $sexo_paciente, $edad_paciente[anos], '', '', '');
            if(!$vector)
            {
                    if($this->CrearGenerico($_SESSION['LISTA']['APOYO']['cargo'], $_SESSION['LISTA']['APOYO']['titulo'])==true)
                    {
                            $_SESSION['LISTA']['APOYO']['tecnica_id'] = 1;
                            $vector=$this->ConsultaComponentesExamen($_SESSION['LISTA']['APOYO']['cargo'], $_SESSION['LISTA']['APOYO']['tecnica_id'], $sexo_paciente, $edad_paciente[anos], '', '', '');
                    }
            }
            if($vector)
            {
                    $this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
                    $indmin=1;
                    $e=0;
                    $k=0;
                    for($i=0;$i<sizeof($vector);$i++)
                    {
                            if( $i % 2)
                            {$estilo='modulo_list_claro';}
                            else
                            {$estilo='modulo_list_oscuro';}
                            switch ($vector[$i][lab_plantilla_id])
                            {
                                    case "1": {//echo "<br>Entro al 1";
                                                                $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                                                                $this->salida.="<td width=\"35%\" align=\"center\">SUBEXAMEN</td>";
                                                                $this->salida.="<td width=\"30%\" align=\"center\">RESULTADO</td>";
                                                                $this->salida.="<td width=\"10%\" align=\"center\">V.MIN</td>";
                                                                $this->salida.="<td width=\"10%\" align=\"center\">V.MAX</td>";
                                                                $this->salida.="<td width=\"10%\" align=\"center\">UND</td>";
                                                                $this->salida.="<td width=\"5%\"  align=\"center\">PAT.</td>";
                                                                $this->salida.="</tr>";

                                                                if(is_null($vector[$i]['rango_min']) || $vector[$i]['rango_min'] == '0')
                                                                {
                                                                        $vector[$i]['rango_min'] = 0;
                                                                }
                                                                $this->salida.="<tr class=\"$estilo\">";
                                                                $this->salida.="<td width=\"35%\" align=\"left\"  class=\"".$this->SetStyle("resultado$k$e")."\">".strtoupper($vector[$i]['nombre_examen'])."</td>";
                                                                $this->salida.="<td width=\"30%\" align=\"center\"><input type=\"text\" name = \"resultado$k$e\" value =\"".$_REQUEST['resultado'.$k.$e]."\">&nbsp;".$vector[$i]['unidades_1']."</td>";
                                                                $this->salida.="<td width=\"10%\" align=\"center\"><input type=\"text\" name=\"rmin$k$e\" class=\"input-text-center\" size=\"10\"   value=\"".$vector[$i]['rango_min']."\"></td>";
                                                                $this->salida.="<td width=\"10%\" align=\"center\"><input type=\"text\" name=\"rmax$k$e\" class=\"input-text-center\" size=\"10\"   value=\"".$vector[$i]['rango_max']."\"></td>";
                                                                $this->salida.="<td width=\"10%\" align=\"center\"><input type=\"text\" name=\"unidades$k$e\" class=\"input-text-center\" size=\"10\"   value=\"".$vector[$i]['unidades_1']."\"></td>";
                                                                if ($_REQUEST['sw_patologico'.$k.$e] == '1')
                                                                {
                                                                        $this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" checked name=\"sw_patologico$k$e\" value=\"1\"></td>";
                                                                }
                                                                else
                                                                {
                                                                        $this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" name=\"sw_patologico$k$e\" value=\"1\"></td>";
                                                                }
                                                                $this->salida.="</tr>";

                                                                $this->salida.="  <input type=\"hidden\" name = \"lab_examen$k$e\"  value=\"".$vector[$i]['lab_examen_id']."\">";
                                                                $e++;
                                                                break;
                                                        }

                                    case "2": {//echo "<br>Entro al 2";
                                                                if ($indmin == 1)
                                                                {
                                                                        $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                                                                        $this->salida.="<td width=\"35%\" align=\"center\">SUBEXAMEN</td>";
                                                                        $this->salida.="<td width=\"40%\" align=\"center\" colspan = \"2\">RESULTADO</td>";
                                                                        $this->salida.="<td width=\"20%\" align=\"center\" colspan = \"2\">UND</td>";
                                                                        $this->salida.="<td width=\"5%\" align=\"center\">PAT.</td>";
                                                                        $this->salida.="</tr>";
                                                                        $this->salida.="<tr class=\"$estilo\">";
                                                                        $this->salida.="<td align=\"left\" width=\"35%\" class=".$this->SetStyle("resultado$k$e").">".strtoupper($vector[$i]['nombre_examen'])."</td>";
                                                                        $this->salida.="<td align=\"center\" width=\"40%\" colspan = \"2\">";

                                                                        $this->salida.="<select size = \"1\" name = \"resultado$k$e\"  class =\"select\">";
                                                                        $this->salida.="<option value = \"-1\" >--Seleccione--</option>";
                                                                        if($_REQUEST['resultado'.$k.$e]==$vector[$i]['opcion'])
                                                                        {
                                                                                $this->salida.="<option value = \"".$vector[$i]['opcion']."\" selected>".$vector[$i]['opcion']."</option>";
                                                                        }
                                                                        else
                                                                        {
                                                                                $this->salida.="<option value = \"".$vector[$i]['opcion']."\" >".$vector[$i]['opcion']."</option>";
                                                                        }
                                                                        $indmin++;
                                                                }
                                                                else
                                                                {
                                                                        if($_REQUEST['resultado'.$k.$e]==$vector[$i]['opcion'])
                                                                        {
                                                                                $this->salida.="<option value = \"".$vector[$i]['opcion']."\" selected>".$vector[$i]['opcion']."</option>";
                                                                        }
                                                                        else
                                                                        {
                                                                                $this->salida.="<option value = \"".$vector[$i]['opcion']."\" >".$vector[$i]['opcion']."</option>";
                                                                        }
                                                                }
                                                                if($vector[$i]['lab_examen_id']!=$vector[$i+1]['lab_examen_id'])
                                                                {
                                                                        $this->salida.="</select>";
                                                                        $this->salida.="</td>";
                                                                        $this->salida.="<td width=\"20%\" align=\"center\" colspan = \"2\"><input type=\"text\" class=\"input-text-center\" name=\"unidades$k$e\"  size=\"10\"   value=\"".$vector[$i]['unidades_2']."\"></td>";
                                                                        if ($_REQUEST['sw_patologico'.$k.$e] == '1')
                                                                        {
                                                                                $this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" checked name=\"sw_patologico$k$e\" value=\"1\"></td>";
                                                                        }
                                                                        else
                                                                        {
                                                                                $this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" name=\"sw_patologico$k$e\" value=\"1\"></td>";
                                                                        }
                                    $this->salida.="<input type=\"hidden\" name = \"lab_examen$k$e\"  value=\"".$vector[$i]['lab_examen_id']."\">";
                                                                        $this->salida.="</tr>";
                                                                        $indmin=1;
                                                                        $e++;
                                                                }
                                                                break;
                                                        }

                                    case "3": {
                                                                //echo "<br>2 Entro al 3";
                                                                //nombre,cargo,desc_cargo,des_tecnica
                                                                $convenciones=$this->CargaConvenciones($_SESSION['LISTA']['APOYO']['nombre'],$_SESSION['LISTA']['APOYO']['cargo'],strtoupper($_SESSION['LISTA']['APOYO']['titulo']));

                                                                if($_REQUEST['resultado'.$k.$e]==='' OR !empty($_REQUEST['resultado'.$k.$e]))
                                                                {
                                                                    foreach($convenciones as $campo => $valor){
                                                                        $_REQUEST['resultado'.$k.$e]=str_replace($campo,$valor,$_REQUEST['resultado'.$k.$e]);
                                                                    }
                                                                }else{
                                                                    foreach($convenciones as $campo => $valor){
                                                                        $vector[$i]['detalle']=str_replace($campo,$valor,$vector[$i]['detalle']);
                                                                    }
                                                                }

                                                                $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                                                                $this->salida.="<td width=\"35%\" align=\"center\">SUBEXAMEN</td>";
                                                                $this->salida.="<td width=\"60%\" align=\"center\" colspan = \"4\" class=".$this->SetStyle("resultado$k$e").">".strtoupper($vector[$i]['nombre_examen'])."</td>";
                                                                $this->salida.="<td width=\"5%\" align=\"center\">PAT.</td>";
                                                                $this->salida.="</tr>";

                                                                $this->salida.="<tr class=\"$estilo\">";
                                                                if($_REQUEST['resultado'.$k.$e]==='' OR !empty($_REQUEST['resultado'.$k.$e]))
                                                                {//echo "<br>Entro X request";
                                                                        //$this->salida.="<td colspan = \"5\" align=\"center\" width=\"95%\"><textarea style = \"width:90%\" class=\"textarea\" name = \"resultado$k$e\" cols = \"150\" rows = \"25\">".$_REQUEST['resultado'.$k.$e]."</textarea></td>";
                                                                        $this->salida .= "<td colspan = \"5\" align=\"center\" width=\"60%\">";
                                                                        $this->salida .= getFckeditor("resultado$k$e",'200',"100%",$_REQUEST['resultado'.$k.$e]);
                                                                        $this->salida .= "</td>";
                                                                }
                                                                else
                                                                {//echo "<br>Entro X vector";
                                                                        //$this->salida.="<td colspan = \"5\" align=\"center\" width=\"95%\"><textarea style = \"width:90%\" class=\"textarea\" name = \"resultado$k$e\" cols = \"150\" rows = \"25\">".$vector[$i]['detalle']."</textarea></td>";
                                                                        $this->salida .= "<td colspan = \"5\" align=\"center\" width=\"60%\">";
                                                                        $this->salida .= getFckeditor("resultado$k$e",'200',"100%",$vector[$i]['detalle']);
                                                                        $this->salida .= "</td>";
                                                                }
                                                                if($_REQUEST['sw_patologico'.$k.$e]=='1')
                                                                {
                                                                        $this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" checked name=\"sw_patologico$k$e\" value=\"1\"></td>";
                                                                }
                                                                else
                                                                {
                                                                        $this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" name=\"sw_patologico$k$e\" value=\"1\"></td>";
                                                                }

                                                                $this->salida.="<input type=\"hidden\" name = \"lab_examen$k$e\"  value=\"".$vector[$i]['lab_examen_id']."\">";
                                                                $this->salida.="</tr>";
                                                                $e++;
                                                                break;
                                                        }

                                    case "0": {//echo "<br>Entro al 0";
                                                                $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                                                                $this->salida.="<td width=\"35%\" align=\"center\">SUBEXAMEN</td>";
                                                                $this->salida.="<td width=\"60%\" align=\"center\" colspan = \"4\">RESULTADO</td>";
                                                                $this->salida.="<td width=\"5%\" align=\"center\">PAT.</td>";
                                                                $this->salida.="</tr>";

                                                                $this->salida.="<tr class=\"$estilo\">";
                                                                $this->salida.="<td width=\"35%\" align=\"center\" class=\"".$this->SetStyle("resultado$k$e")."\">".strtoupper($vector[$i]['nombre_examen'])."</td>";
                                                                //$this->salida.="<td width=\"60%\" align=\"center\" colspan = \"4\"><textarea style = \"width:80%\" class=\"textarea\" name = \"resultado$k$e\" cols = \"60\" rows = \"10\">".$_REQUEST['resultado'.$k.$e]."</textarea></td>";
                                                                $this->salida .= "<td colspan = \"4\" align=\"center\" width=\"60%\">";
                                                                $this->salida .= getFckeditor("resultado$k$e",'200',"100%",$_REQUEST['resultado'.$k.$e]);
                                                                $this->salida .= "</td>";
                                                                if ($_REQUEST['sw_patologico'.$k.$e] == '1')
                                                                {
                                                                        $this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" checked name=\"sw_patologico$k$e\" value=\"1\"></td>";
                                                                }
                                                                else
                                                                {
                                                                        $this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" name=\"sw_patologico$k$e\" value=\"1\"></td>";
                                                                }
                                                                $this->salida.="<input type=\"hidden\" name = \"lab_examen$k$e\"  value=\"".$vector[$i]['lab_examen_id']."\">";
                                                                $this->salida.="</tr>";
                                                                $e++;
                                                                break;
                                                        }
                            }//cierra el switche
                    }//cierra el for
                    $this->salida.="</table>";
                    $items = $e;
                    $this->salida.="  <input type=\"hidden\" name = \"items$k\"  value=\"$items\">";

                    $this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"60%\">";
                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida.="<td colspan = \"2\" align=\"center\" width=\"60%\">OBSERVACION DEL PRESTADOR DEL SERVICIO</td>";
                    $this->salida.="</tr>";
                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="<td colspan = \"2\" align=\"center\" width=\"60%\"><textarea style = \"width:80%\" class=\"textarea\" name = \"observacion\" cols=\"60\" rows=\"5\">".$_REQUEST['observacion']."</textarea></td>" ;
                    $this->salida.="</tr>";

                    //if especial para cuando se pagan honorarios, pinta el nombre del profesional al cual se le esta cargando el examen
                    $nombre_profesional_honorario = $this->ConsultaNombreProfesionalHonorario($_SESSION['LISTA']['APOYO']['numero_orden_id']);
                    if($nombre_profesional_honorario[nombre_tercero]!='')
                    {
                            $this->salida.="<tr class=\"$estilo\">";
                            $this->salida.="<td align=\"left\" colspan = \"1\" class=\"hc_table_submodulo_list_title\" width=\"30%\">EXAMEN CARGADO A LOS HONORARIOS DE </td>";
                            $this->salida.="<td align=\"center\" class = \"label_error\"  colspan = \"1\">".$nombre_profesional_honorario[nombre_tercero]."</td>";
                            $this->salida.="</tr>";
                    }
                    //fin del caso especial

                    //lo de mauricio y lorena - Cambio Responsable apoyo
                    $this->salida.="<tr>";
                    $this->salida.="<td colspan = \"1\" width=\"20%\" align=\"left\" class=\"hc_table_submodulo_list_title\">RESPONSABLE DIAGNOSTICO</td>";
                    $this->salida.="<td colspan = \"1\" width=\"40%\" align=\"center\" class=\"$estilo\">";
                    $this->salida.="<select size = \"1\" name = \"responsable\" class =\"select\">";
                    $opciones=$this->ProfesionalesDepartamento();

                    if(empty($_SESSION['LTRABAJOAPOYOD']['PROFESIONAL']))//SI ES TRANSCRIPTOR
                    {
                        $identificacion=$this->BuscaProfesionalResultado($_SESSION['LISTA']['APOYO']['numero_orden_id']);
                        if(sizeof($identificacion)<1)
                        {
                            $identificacion=$this->BuscaProfesionalCumplimiento($_SESSION['LISTA']['APOYO']['numero_orden_id']);
                        }
                        $this->salida.="<option value ='-1' >--Seleccione-- </option>";
                        for($j=0;$j<sizeof($opciones);$j++)
                        {
                            if($identificacion['usuario_id']==$opciones[$j][usuario_id])
                            {
                                $this->salida.="<option value = ".$opciones[$j][usuario_id]." selected>".$opciones[$j][nombre]."</option>";
                            }
                            else
                            {
                                $this->salida.="<option value = ".$opciones[$j][usuario_id]." >".$opciones[$j][nombre]."</option>";
                            }
                        }
                    }
                    else
                    {
                        $identificacion=$this->BuscaProfesionalResultado($_SESSION['LISTA']['APOYO']['numero_orden_id']);
                        if(sizeof($identificacion)>0)
                        {
                            for($j=0;$j<sizeof($opciones);$j++)
                            {
                                if($identificacion['usuario_id']==$opciones[$j][usuario_id])
                                {
                                    $this->salida.="<option value = ".$opciones[$j][usuario_id]." selected>".$opciones[$j][nombre]."</option>";
                                }
                            }
                        }
                        else
                        {
                            $identificacion=$this->BuscaProfesionalCumplimiento($_SESSION['LISTA']['APOYO']['numero_orden_id']);
                            if(sizeof($identificacion)<1)
                            {
                                $identificacion['usuario_id']=UserGetUID();
                            }
                            $this->salida.="<option value ='-1' >--Seleccione-- </option>";
                            for($j=0;$j<sizeof($opciones);$j++)
                            {
                                if($identificacion['usuario_id']==$opciones[$j][usuario_id])
                                {
                                    $this->salida.="<option value = ".$opciones[$j][usuario_id]." selected>".$opciones[$j][nombre]."</option>";
                                }
                                else
                                {
                                    $this->salida.="<option value = ".$opciones[$j][usuario_id]." >".$opciones[$j][nombre]."</option>";
                                }
                            }
                        }
                    }
                    $this->salida.="</select>";
                    $this->salida.="</td>";
                    $this->salida.="</tr>";
                    //Fin Cambio

                    if (!empty($_SESSION['LTRABAJOAPOYOD']['PROFESIONAL']))
                    {
                            $this->salida.="<tr>";
                            $this->salida.="<td colspan = \"1\" width=\"20%\" align=\"left\" class=\"hc_table_submodulo_list_title\">PROFESIONAL</td>";
                            if ($_REQUEST['firma']=='1')
                            {
                                    $this->salida.="<td colspan = \"1\" width=\"40%\" align=\"center\" class=\"$estilo\"><input type = \"checkbox\" name= \"firma\" value = \"1\" checked >APROBACION DEL RESULTADO AQUI REGISTRADO</td>";
                            }
                            else
                            {
                                    $this->salida.="<td colspan = \"1\" width=\"40%\" align=\"center\" class=\"$estilo\"><input type = \"checkbox\" name= \"firma\" value = \"1\">APROBACION DEL RESULTADO AQUI REGISTRADO</td>";
                            }
                            $this->salida.="</tr>";
                    }
                    $this->salida.="</table><br>";


                    $this->salida.="<table align=\"center\" width='30%' border=\"0\">";
                    $this->salida .= "<tr>";
                    $this->salida .= "<td  colspan = \"2\" align=\"center\"><br><input class=\"input-submit\" name=\"Insertar\" type=\"submit\" value=\"INSERTAR\"></td>";
                    $this->salida .= "</form>";
                    $this->salida .= "</tr>";
                    $this->salida .= "<tr>";

                    $accion=ModuloGetURL('app','Os_Listas_Trabajo_Apoyod_Agrupado','user','Plantillas_Examenes', array('retorno' =>'1'));

                    $this->salida .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
                    $this->salida .= "<td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"VOLVER A LA TECNICA\"></form></td>";

                    $accion2=ModuloGetURL('app','Os_Listas_Trabajo_Apoyod_Agrupado','user','BuscarOrden');
                    $this->salida .= "<form name=\"forma\" action=\"$accion2\" method=\"post\">";
                    $this->salida .= "<td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"VOLVER A LAS LISTAS\"></form></td>";
                    $this->salida .= "</tr>";
                    $this->salida .=  "</table><br>";
                    $this->salida .= ThemeCerrarTablaSubModulo();
                    return true;
            }
    }

  /*
    * Esta funcion le permite al usuario realizar la modificacion y la consulta
    * de un resultado de forma individual
    * @return boolean
    */
    function frmModificacion_Resultados($resultado_id, $evolucion_id, $hc_os_solicitud_id, $usuario_profesional, $nombre, $numero_cumplimiento ,$fecha_cumplimiento, $usuario_profesional_autoriza)
    {
			if(!$_SESSION['DATOS_APD'])
			{//echo ENTRO;
				$_SESSION['DATOS_APD']['resultado_id']=$resultado_id;
				$_SESSION['DATOS_APD']['evolucion_id']=$evolucion_id;
				$_SESSION['DATOS_APD']['hc_os_solicitud_id']=$hc_os_solicitud_id;
				$_SESSION['DATOS_APD']['usuario_profesional']=$usuario_profesional;
				$_SESSION['DATOS_APD']['usuario_profesional_autoriza']=$usuario_profesional_autoriza;
				$_SESSION['DATOS_APD']['nombre']=$nombre;
			}

			$resultado_id = $_SESSION['DATOS_APD']['resultado_id'];
			$evolucion_id = $_SESSION['DATOS_APD']['evolucion_id'];
			$hc_os_solicitud_id = $_SESSION['DATOS_APD']['hc_os_solicitud_id'];
			$usuario_profesional = $_SESSION['DATOS_APD']['usuario_profesional'];
			$usuario_profesional_autoriza = $_SESSION['DATOS_APD']['usuario_profesional_autoriza'];
			$nombre = $_SESSION['DATOS_APD']['nombre'];

			if ($_SESSION['CONSULTANDO_APD'] =='1')
			{
				$this->salida= ThemeAbrirTablaSubModulo('CONSULTA DEL EXAMEN CLINICO');
			}
			else
			{
				$this->salida= ThemeAbrirTablaSubModulo('MODIFICACION DEL EXAMEN CLINICO');
			}

			$examenes = $this->ConsultaExamenesPaciente($resultado_id);

			$action=ModuloGetURL('app','Os_Listas_Trabajo_Apoyod_Agrupado','user','GetForma',array('accion'=>'modificar','resultado_id' => $resultado_id, 'cargo' => $examenes[cargo], 'tecnica_id' => $examenes[tecnica_id]));
			$this->salida .= "<form name=\"formades\" action=\"$action\" method=\"post\">";
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida.="</table>";

			$sexo_paciente = $this->GetSexo($examenes[tipo_id_paciente], $examenes[paciente_id]);
			$edad_paciente = $this->Obtener_Edad($examenes[tipo_id_paciente], $examenes[paciente_id]);
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\">ID DEL PACIENTE</td>";
			$this->salida.="  <td align=\"center\">NOMBRE DEL PACIENTE</td>";
			$this->salida.="  <td align=\"center\">EDAD DEL PACIENTE</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\">".$examenes[tipo_id_paciente].": ".$examenes[paciente_id]."</td>";
			$this->salida.="  <td align=\"center\">".$nombre."</td>";
			$this->salida.="  <td align=\"center\">".$edad_paciente[edad_aprox]."</td>";
			$this->salida.="</tr>";
			$this->salida.="</table><br>";

			//MauroB
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td align=\"left\" width=\"20%\">FECHA DE CUMPLIMIENTO: </td>";
			$this->salida.="  <td align=\"center\" width=\"10%\">".$fecha_cumplimiento."</td>";
			$this->salida.="  <td align=\"left\" width=\"20%\">NUMERO DE CUMPLIMIENTO: </td>";
			$cumplimiento=$this->ConvierteCumplimiento($fecha_cumplimiento,$numero_cumplimiento,$_SESSION['LTRABAJOAPOYOD']['DPTO']);
			$this->salida.="  <td align=\"center\" width=\"10%\">".$cumplimiento."</td>";
			$this->salida.="</tr>";
			$this->salida.="</table>";
			//fin MauroB


			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="<td align=\"center\" width=\"5%\">CARGO</td>";
			$this->salida.="<td align=\"center\" width=\"45%\">EXAMEN</td>";
			$this->salida.="<td align=\"center\" width=\"12%\" colspan=\"3\">OPCIONES</td>";
			$this->salida.="<td align=\"center\" width=\"8%\">FECHA</td>";
			$this->salida.="<td align=\"center\" width=\"15%\">LABORATORIO</td>";
			$this->salida.="<td align=\"center\" width=\"15%\">TRANS.</td>";
			$this->salida.="</tr>";

			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td align=\"center\" width=\"5%\">".$examenes[cargo]."</td>";
			$this->salida.="<td align=\"center\" width=\"45%\">".strtoupper($examenes[titulo])."</td>";

			if($examenes[informacion]=='')
			{
				$this->salida.="<td align=\"center\" width=\"3%\"><img src=\"".GetThemePath()."/images/Vacio.gif\" title=\"sin Informacion \"  border=\"0\"></td>";
			}
			else
			{
				$this->salida.="<td align=\"center\" width=\"3%\"><img src=\"'".GetThemePath()."/images/EstacionEnfermeria/info.png'\" title=\"Informacion: ".$examenes[informacion]."\"  border=\"0\"></td>";
			}

			//opcion que muestra el link de la historia clinica si es un profesional
			if (!empty($_SESSION['LTRABAJOAPOYOD']['PROFESIONAL']))
			{
				if ($evolucion_id != NULL)
				{
								$_SESSION['HISTORIACLINICA']['RETORNO']['modulo']='Os_Listas_Trabajo_Apoyod_Agrupado';
								$_SESSION['HISTORIACLINICA']['RETORNO']['metodo']='frmModificacion_Resultados';
								$_SESSION['HISTORIACLINICA']['RETORNO']['tipo']='user';
								$_SESSION['HISTORIACLINICA']['RETORNO']['contenedor']='app';
								$accion=ModuloHCGetURL($evolucion_id,'','','','',array());
								$this->salida.="<td align=\"center\" width=\"3%\"><a href=".$accion."><img src=\"".GetThemePath()."/images/honorarios.png\" border='0' title=\"ver HC\"></a</td>";
				}
				else
				{
								$this->salida.="<td align=\"center\" width=\"3%\"><img src=\"".GetThemePath()."/images/pincumplimiento_citas.png\" border='0' title=\"Solicitado fuera de HC\"></td>";
				}
			}
			else
			{
				$this->salida.="<td align=\"center\" width=\"3%\"><img src=\"".GetThemePath()."/images/honorarios.png\" border=\"0\" title=\"sin acceso a HC\"></td>";
			}

			$this->SetJavaScripts('DatosSolicitudApoyo');
			$this->salida.="<td align=\"center\" width=\"3%\"><a href=\"javascript:DatosSolicitudApoyo(".$hc_os_solicitud_id.", '".$examenes[tipo_id_paciente]."', '".$examenes[paciente_id]."', '".$nombre."', '".$examenes[cargo]."', '".$examenes[titulo]."')\"><img src=\"".GetThemePath()."/images/resumen.gif\" border=\"0\" title=\"Ver Datos Solicitud\">   </a></td>";


			$this->salida.="<td align=\"center\" width=\"8%\">".$examenes['fecha_realizado']."</td>";
			$this->salida.="<td align=\"center\" width=\"15%\">".$examenes['laboratorio']."</td>";
			$this->salida.="<td align=\"center\" width=\"15%\">".$examenes['transcriptor']."</td>";
			$this->salida.="</tr>";
			$this->salida.="</table>";

			$vector = $this->ConsultaDetalle($resultado_id, $examenes[cargo], $examenes[tecnica_id]);
			if($vector)
			{
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
				$e=0;
				$k=0;
				$control_datalab = 0;
				for($i=0;$i<sizeof($vector);$i++)
				{
								if( $i % 2)
								{$estilo='modulo_list_claro';}
								else
								{$estilo='modulo_list_oscuro';}
								switch ($vector[$i][lab_plantilla_id])
								{
										case "1": {
																								$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
																								$this->salida.="<td width=\"35%\" align=\"center\">SUBEXAMEN</td>";
																								$this->salida.="<td width=\"30%\" align=\"center\">RESULTADO</td>";
																								$this->salida.="<td width=\"10%\" align=\"center\">V.MIN</td>";
																								$this->salida.="<td width=\"10%\" align=\"center\">V.MAX</td>";
																								$this->salida.="<td width=\"10%\" align=\"center\">UND</td>";
																								$this->salida.="<td width=\"5%\"  align=\"center\">PAT.</td>";
																								$this->salida.="</tr>";
																								if(is_null($vector[$i][rango_min]) || $vector[$i][rango_min] == '0')
																								{
																												$vector[$i][rango_min] = 0;
																								}
																								$this->salida.="<tr class=\"$estilo\">";
																								$this->salida.="<td width=\"35%\" align=\"left\"  class=\"".$this->SetStyle("resultado$k$e")."\">".strtoupper($vector[$i]['nombre_examen'])."</td>";

									if ($_SESSION['CONSULTANDO_APD'] =='1')
																								{
																												if ($vector[$i][sw_alerta] == '1')
																												{
																																$this->salida.="<td width=\"30%\" align=\"center\" class=\"label_error\">".$vector[$i]['resultado']." &nbsp; ".$vector[$i]['unidades']."</td>";
																																$this->salida.="<td width=\"10%\" align=\"center\">".$vector[$i]['rango_min']."</td>";
																																$this->salida.="<td width=\"10%\" align=\"center\">".$vector[$i]['rango_max']."</td>";
																																$this->salida.="<td width=\"10%\" align=\"center\">".$vector[$i]['unidades']."</td>";
																																//$this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" disabled checked name=\"sw_patologico$k$e\" value=\"1\"></td>";
																																$this->salida.="<td width=\"5%\"  align=\"center\"><img src=\"'".GetThemePath()."/images/checksi.png'\" width=\"11\" height=\"11\" border=\"0\"></td>";
																												}
																												else
																												{
																																$this->salida.="<td width=\"30%\" align=\"center\">".$vector[$i]['resultado']." &nbsp; ".$vector[$i]['unidades']."</td>";
																																$this->salida.="<td width=\"10%\" align=\"center\">".$vector[$i]['rango_min']."</td>";
																																$this->salida.="<td width=\"10%\" align=\"center\">".$vector[$i]['rango_max']."</td>";
																																$this->salida.="<td width=\"10%\" align=\"center\">".$vector[$i]['unidades']."</td>";
																																//$this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" disabled name=\"sw_patologico$k$e\" value=\"1\"></td>";
																																$this->salida.="<td width=\"5%\"  align=\"center\"><img src=\"'".GetThemePath()."/images/checkno.png'\" width=\"11\" height=\"11\" border=\"0\"></td>";
																												}
									}
																								else
																								{
																												if (!$_REQUEST['resultado'.$k.$e])
																												{$_REQUEST['resultado'.$k.$e]= $vector[$i]['resultado'];}

																												if (!$_REQUEST['unidades'.$k.$e])
																												{$_REQUEST['unidades'.$k.$e] = $vector[$i]['unidades'];}

																												if (!$_REQUEST['rmin'.$k.$e])
																												{$_REQUEST['rmin'.$k.$e] = $vector[$i]['rango_min'];}

																												if (!$_REQUEST['rmax'.$k.$e])
																												{$_REQUEST['rmax'.$k.$e] = $vector[$i]['rango_max'];}

																												if (!$_REQUEST['sw_patologico'.$k.$e])
																												{$_REQUEST['sw_patologico'.$k.$e] = $vector[$i][sw_alerta];}

																												if ($_REQUEST['sw_patologico'.$k.$e] == '1')
																												{
																																$this->salida.="<td width=\"30%\" align=\"center\" class=\"label_error\"><input type=\"text\" class=\"input-text\" name = \"resultado$k$e\" value =\"".$_REQUEST['resultado'.$k.$e]."\">&nbsp;".$_REQUEST['unidades'.$k.$e]."</td>";
																												}
																												else
																												{
																																$this->salida.="<td width=\"30%\" align=\"center\"><input type=\"text\" class=\"input-text\" name = \"resultado$k$e\" value =\"".$_REQUEST['resultado'.$k.$e]."\">&nbsp;".$_REQUEST['unidades'.$k.$e]."</td>";
																												}

																												$this->salida.="<td width=\"10%\" align=\"center\"><input type=\"text\" name=\"rmin$k$e\" class=\"input-text-center\" size=\"10\"   value=\"".$_REQUEST['rmin'.$k.$e]."\"></td>";
																												$this->salida.="<td width=\"10%\" align=\"center\"><input type=\"text\" name=\"rmax$k$e\" class=\"input-text-center\" size=\"10\"   value=\"".$_REQUEST['rmax'.$k.$e]."\"></td>";
																												$this->salida.="<td width=\"10%\" align=\"center\"><input type=\"text\" name=\"unidades$k$e\" class=\"input-text-center\" size=\"10\"   value=\"".$_REQUEST['unidades'.$k.$e]."\"></td>";

																												if($_REQUEST['sw_patologico'.$k.$e] == '1')
																												{
																																$this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" checked name=\"sw_patologico$k$e\" value=\"1\"></td>";
																												}
																												else
																												{
																																$this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" name=\"sw_patologico$k$e\" value=\"1\"></td>";
																												}
																								}
																								$this->salida.="  <input type=\"hidden\" name = \"lab_examen$k$e\"  value=\"".$vector[$i]['lab_examen_id']."\">";
																								$this->salida.="</tr>";
																								$e++;
																								break;
																				}

										case "2": {
																								$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
																								$this->salida.="<td width=\"35%\" align=\"center\">SUBEXAMEN</td>";
																								$this->salida.="<td width=\"40%\" align=\"center\" colspan = \"2\">RESULTADO</td>";
																								$this->salida.="<td width=\"20%\" align=\"center\" colspan = \"2\">UND</td>";
																								$this->salida.="<td width=\"5%\"  align=\"center\">PAT.</td>";
																								$this->salida.="</tr>";

																								$this->salida.="<tr class=\"$estilo\">";
																								$this->salida.="<td align=\"left\" width=\"35%\" class=".$this->SetStyle("resultado$k$e").">".strtoupper($vector[$i]['nombre_examen'])."</td>";
																								if ($_SESSION['CONSULTANDO_APD'] =='1')
																								{
											if ($vector[$i][sw_alerta] == '1')
																												{
																														$this->salida.="<td align=\"center\" width=\"40%\" colspan = \"2\" class=\"label_error\">".$vector[$i][resultado]."</td>";
																																$this->salida.="<td align=\"center\" width=\"20%\" colspan = \"2\">".$vector[$i]['unidades']."</td>";
																																//$this->salida.="<td align=\"center\" width=\"5%\"><input type=\"checkbox\" disabled checked name=\"sw_patologico$k$e\" value=\"1\"></td>";
																																$this->salida.="<td width=\"5%\"  align=\"center\"><img src=\"'".GetThemePath()."/images/checksi.png'\" width=\"11\" height=\"11\" border=\"0\"></td>";
																												}
																												else
																												{
																																$this->salida.="<td align=\"center\" width=\"40%\" colspan = \"2\">".$vector[$i]['resultado']."</td>";
																																$this->salida.="<td align=\"center\" width=\"20%\" colspan = \"2\">".$vector[$i]['unidades']."</td>";
																																//$this->salida.="<td align=\"center\" width=\"5%\"><input type=\"checkbox\" disabled name=\"sw_patologico$k$e\" value=\"1\"></td>";
																																$this->salida.="<td width=\"5%\"  align=\"center\"><img src=\"'".GetThemePath()."/images/checkno.png'\" width=\"11\" height=\"11\" border=\"0\"></td>";
																												}
																								}
																								else
																								{
											if (!$_REQUEST['resultado'.$k.$e])
																												{$_REQUEST['resultado'.$k.$e]= $vector[$i]['resultado'];}

																												if (!$_REQUEST['unidades'.$k.$e])
																												{$_REQUEST['unidades'.$k.$e] = $vector[$i]['unidades'];}

																												if (!$_REQUEST['sw_patologico'.$k.$e])
																												{$_REQUEST['sw_patologico'.$k.$e] = $vector[$i][sw_alerta];}

																												$this->salida.="<td align=\"center\" width=\"40%\" colspan = \"2\">";
																												$this->salida.="<select size = \"1\" name = \"resultado$k$e\"  class =\"select\">";
																												$this->salida.="<option value = \"-1\" >--Seleccione--</option>";
																												$opciones=$this->Consultar_Opciones($vector[$i][lab_examen_id], $vector[$i][cargo], $vector[$i][tecnica_id]);
																												for($l=0;$l<sizeof($opciones);$l++)
																												{
																																if($_REQUEST['resultado'.$k.$e]==$opciones[$l]['opcion'])
																																{
																																				$this->salida.="<option value = \"".$opciones[$l]['opcion']."\" selected>".$opciones[$l]['opcion']."</option>";
																																}
																																else
																																{
																																				$this->salida.="<option value = \"".$opciones[$l]['opcion']."\" >".$opciones[$l]['opcion']."</option>";
																																}
																												}
																												$this->salida.="</select>";
																												$this->salida.="</td>";
																												$this->salida.="<td width=\"20%\" align=\"center\" colspan = \"2\"><input type=\"text\" class=\"input-text-center\" name=\"unidades$k$e\"  size=\"10\" value=\"".$_REQUEST['unidades'.$k.$e]."\"></td>";
																												if($_REQUEST['sw_patologico'.$k.$e] == '1')
																												{
																																$this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" checked name=\"sw_patologico$k$e\" value=\"1\"></td>";
																												}
																												else
																												{
																																$this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" name=\"sw_patologico$k$e\" value=\"1\"></td>";
																												}
																								}
																								$this->salida.="  <input type=\"hidden\" name = \"lab_examen$k$e\"  value=\"".$vector[$i]['lab_examen_id']."\">";
																								$this->salida.="</tr>";
																								$e++;
																								break;
																				}
										case "3": {//echo "3 Caso 3";
																								$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
																								$this->salida.="<td width=\"35%\" align=\"center\">SUBEXAMEN</td>";
																								$this->salida.="<td width=\"60%\" align=\"center\" colspan = \"4\" class=".$this->SetStyle("resultado$k$e").">".strtoupper($vector[$i]['nombre_examen'])."</td>";
																								$this->salida.="<td width=\"5%\" align=\"center\">PAT.</td>";
																								$this->salida.="</tr>";

																								$this->salida.="<tr class=\"$estilo\">";
																								if ($_SESSION['CONSULTANDO_APD'] =='1')
																								{
																												//$this->salida.="<td colspan = \"5\" align=\"center\" width=\"95%\"><textarea readonly style = \"width:90%\" class=\"textarea\" name = \"resultado$k$e\" cols = \"150\" rows = \"25\">".$vector[$i]['resultado']."</textarea></td>";
																												$this->salida.="<td colspan = \"5\"  width=\"95%\">".$vector[$i]['resultado']."</td>";
			//                                                                  $this->salida .= "<td colspan = \"5\" align=\"center\" width=\"60%\">";
			//                                                                  $this->salida .= getFckeditor("resultado$k$e",'200',"100%",$vector[$i]['resultado']);
			//                                                                  $this->salida .= "</td>";
																												if ($vector[$i][sw_alerta] == '1')
																												{
																																//$this->salida.="<td align=\"center\" width=\"5%\"><input type=\"checkbox\" disabled checked name=\"sw_patologico$k$e\" value=\"1\"></td>";
																																$this->salida.="<td width=\"5%\"  align=\"center\"><img src=\"'".GetThemePath()."/images/checksi.png'\" width=\"11\" height=\"11\" border=\"0\"></td>";
																												}
																												else
																												{
																																//$this->salida.="<td align=\"center\" width=\"5%\"><input type=\"checkbox\" disabled name=\"sw_patologico$k$e\" value=\"1\"></td>";
																																$this->salida.="<td width=\"5%\"  align=\"center\"><img src=\"'".GetThemePath()."/images/checkno.png'\" width=\"11\" height=\"11\" border=\"0\"></td>";
																												}
																								}
																								else
																								{
											if (!$_REQUEST['resultado'.$k.$e])
																												{$_REQUEST['resultado'.$k.$e]= $vector[$i]['resultado'];}

																												if (!$_REQUEST['sw_patologico'.$k.$e])
																												{$_REQUEST['sw_patologico'.$k.$e] = $vector[$i][sw_alerta];}

																												//$this->salida.="<td colspan = \"5\" align=\"center\" width=\"95%\"><textarea style = \"width:90%\" class=\"textarea\" name = \"resultado$k$e\" cols = \"150\" rows = \"25\">".$_REQUEST['resultado'.$k.$e]."</textarea></td>";
																												$this->salida .= "<td colspan = \"5\" align=\"center\" width=\"60%\">";
																												$this->salida .= getFckeditor("resultado$k$e",'200',"100%",$_REQUEST['resultado'.$k.$e]);
																												$this->salida .= "</td>";
																												if($_REQUEST['sw_patologico'.$k.$e]=='1')
																												{
																																$this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" checked name=\"sw_patologico$k$e\" value=\"1\"></td>";
																												}
																												else
																												{
																																$this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" name=\"sw_patologico$k$e\" value=\"1\"></td>";
																												}
																								}
																								$this->salida.="<input type=\"hidden\" name = \"lab_examen$k$e\"  value=\"".$vector[$i]['lab_examen_id']."\">";
																								$this->salida.="</tr>";
																								$e++;
																								break;
																				}

										case "0": {
																								$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
																								$this->salida.="<td width=\"35%\" align=\"center\">SUBEXAMEN</td>";
																								$this->salida.="<td width=\"60%\" align=\"center\" colspan = \"4\">RESULTADO</td>";
																								$this->salida.="<td width=\"5%\" align=\"center\">PAT.</td>";
																								$this->salida.="</tr>";

																								$this->salida.="<tr class=\"$estilo\">";
																								$this->salida.="<td width=\"35%\" align=\"center\" class=\"".$this->SetStyle("resultado$k$e")."\">".strtoupper($vector[$i]['nombre_examen'])."</td>";
																								if ($_SESSION['CONSULTANDO_APD'] =='1')
																								{
																												//$this->salida.="<td width=\"60%\" align=\"center\" colspan = \"4\"><textarea readonly style = \"width:80%\" class=\"textarea\" name = \"resultado$k$e\" cols = \"60\" rows = \"10\">".$vector[$i]['resultado']."</textarea></td>";
																												$this->salida.="<td width=\"60%\" colspan = \"4\">".$vector[$i]['resultado']."</td>";
																												if ($vector[$i][sw_alerta] == '1')
																												{
																																//$this->salida.="<td align=\"center\" width=\"5%\"><input type=\"checkbox\" disabled checked name=\"sw_patologico$k$e\" value=\"1\"></td>";
																																$this->salida.="<td width=\"5%\"  align=\"center\"><img src=\"'".GetThemePath()."/images/checksi.png'\" width=\"11\" height=\"11\" border=\"0\"></td>";
																												}
																												else
																												{
																																//$this->salida.="<td align=\"center\" width=\"5%\"><input type=\"checkbox\" disabled name=\"sw_patologico$k$e\" value=\"1\"></td>";
																																$this->salida.="<td width=\"5%\"  align=\"center\"><img src=\"'".GetThemePath()."/images/checkno.png'\" width=\"11\" height=\"11\" border=\"0\"></td>";
																												}
																								}
																								else
																								{
																												if (!$_REQUEST['resultado'.$k.$e])
																												{$_REQUEST['resultado'.$k.$e]= $vector[$i]['resultado'];}

																												if (!$_REQUEST['sw_patologico'.$k.$e])
																												{$_REQUEST['sw_patologico'.$k.$e] = $vector[$i][sw_alerta];}

																												//$this->salida.="<td width=\"60%\" align=\"center\" colspan = \"4\"><textarea style = \"width:80%\" class=\"textarea\" name = \"resultado$k$e\" cols = \"60\" rows = \"10\">".$_REQUEST['resultado'.$k.$e]."</textarea></td>";
																												$this->salida .= "<td colspan = \"4\" align=\"center\" width=\"60%\">";
																												$this->salida .= getFckeditor("resultado$k$e",'200',"100%",$_REQUEST['resultado'.$k.$e]);
																												$this->salida .= "</td>";
																												if ($_REQUEST['sw_patologico'.$k.$e] == '1')
																												{
																																$this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" checked name=\"sw_patologico$k$e\" value=\"1\"></td>";
																												}
																												else
																												{
																																$this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" name=\"sw_patologico$k$e\" value=\"1\"></td>";
																												}
																								}
																								$this->salida.="<input type=\"hidden\" name = \"lab_examen$k$e\"  value=\"".$vector[$i]['lab_examen_id']."\">";
																								$this->salida.="</tr>";
																								$e++;
																								break;
																				}

										case "5": {
																								//caso exclusivo para datalab  -- al migrar se copio identica a la plantilla 1
																								$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
																								$this->salida.="<td width=\"35%\" align=\"center\">SUBEXAMEN</td>";
																								$this->salida.="<td width=\"30%\" align=\"center\">RESULTADO</td>";
																								$this->salida.="<td width=\"10%\" align=\"center\">V.MIN</td>";
																								$this->salida.="<td width=\"10%\" align=\"center\">V.MAX</td>";
																								$this->salida.="<td width=\"10%\" align=\"center\">UND</td>";
																								$this->salida.="<td width=\"5%\"  align=\"center\">PAT.</td>";
																								$this->salida.="</tr>";
																								if(is_null($vector[$i][rango_min]) || $vector[$i][rango_min] == '0')
																								{
																												$vector[$i][rango_min] = 0;
																								}
																								$this->salida.="<tr class=\"$estilo\">";
																								$this->salida.="<td width=\"35%\" align=\"left\"  class=\"".$this->SetStyle("resultado$k$e")."\">".strtoupper($vector[$i]['nombre_examen'])."</td>";

									if ($_SESSION['CONSULTANDO_APD'] =='1')
																								{
																												if ($vector[$i][sw_alerta] == '1')
																												{
																																$this->salida.="<td width=\"30%\" align=\"center\" class=\"label_error\">".$vector[$i]['resultado']." &nbsp; ".$vector[$i]['unidades']."</td>";
																																$this->salida.="<td width=\"10%\" align=\"center\">".$vector[$i]['rango_min']."</td>";
																																$this->salida.="<td width=\"10%\" align=\"center\">".$vector[$i]['rango_max']."</td>";
																																$this->salida.="<td width=\"10%\" align=\"center\">".$vector[$i]['unidades']."</td>";
																																//$this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" disabled checked name=\"sw_patologico$k$e\" value=\"1\"></td>";
																																$this->salida.="<td width=\"5%\"  align=\"center\"><img src=\"'".GetThemePath()."/images/checksi.png'\" width=\"11\" height=\"11\" border=\"0\"></td>";
																												}
																												else
																												{
																																$this->salida.="<td width=\"30%\" align=\"center\">".$vector[$i]['resultado']." &nbsp; ".$vector[$i]['unidades']."</td>";
																																$this->salida.="<td width=\"10%\" align=\"center\">".$vector[$i]['rango_min']."</td>";
																																$this->salida.="<td width=\"10%\" align=\"center\">".$vector[$i]['rango_max']."</td>";
																																$this->salida.="<td width=\"10%\" align=\"center\">".$vector[$i]['unidades']."</td>";
																																//$this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" disabled name=\"sw_patologico$k$e\" value=\"1\"></td>";
																																$this->salida.="<td width=\"5%\"  align=\"center\"><img src=\"'".GetThemePath()."/images/checkno.png'\" width=\"11\" height=\"11\" border=\"0\"></td>";
																												}
									}
																								else
																								{
																												if (!$_REQUEST['resultado'.$k.$e])
																												{$_REQUEST['resultado'.$k.$e]= $vector[$i]['resultado'];}

																												if (!$_REQUEST['unidades'.$k.$e])
																												{$_REQUEST['unidades'.$k.$e] = $vector[$i]['unidades'];}

																												if (!$_REQUEST['rmin'.$k.$e])
																												{$_REQUEST['rmin'.$k.$e] = $vector[$i]['rango_min'];}

																												if (!$_REQUEST['rmax'.$k.$e])
																												{$_REQUEST['rmax'.$k.$e] = $vector[$i]['rango_max'];}

																												if (!$_REQUEST['sw_patologico'.$k.$e])
																												{$_REQUEST['sw_patologico'.$k.$e] = $vector[$i][sw_alerta];}

																												if ($_REQUEST['sw_patologico'.$k.$e] == '1')
																												{
																																$this->salida.="<td width=\"30%\" align=\"center\" class=\"label_error\"><input type=\"text\" class=\"input-text\" name = \"resultado$k$e\" value =\"".$_REQUEST['resultado'.$k.$e]."\">&nbsp;".$_REQUEST['unidades'.$k.$e]."</td>";
																												}
																												else
																												{
																																$this->salida.="<td width=\"30%\" align=\"center\"><input type=\"text\" class=\"input-text\" name = \"resultado$k$e\" value =\"".$_REQUEST['resultado'.$k.$e]."\">&nbsp;".$_REQUEST['unidades'.$k.$e]."</td>";
																												}

																												$this->salida.="<td width=\"10%\" align=\"center\"><input type=\"text\" name=\"rmin$k$e\" class=\"input-text-center\" size=\"10\"   value=\"".$_REQUEST['rmin'.$k.$e]."\"></td>";
																												$this->salida.="<td width=\"10%\" align=\"center\"><input type=\"text\" name=\"rmax$k$e\" class=\"input-text-center\" size=\"10\"   value=\"".$_REQUEST['rmax'.$k.$e]."\"></td>";
																												$this->salida.="<td width=\"10%\" align=\"center\"><input type=\"text\" name=\"unidades$k$e\" class=\"input-text-center\" size=\"10\"   value=\"".$_REQUEST['unidades'.$k.$e]."\"></td>";

																												if($_REQUEST['sw_patologico'.$k.$e] == '1')
																												{
																																$this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" checked name=\"sw_patologico$k$e\" value=\"1\"></td>";
																												}
																												else
																												{
																																$this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" name=\"sw_patologico$k$e\" value=\"1\"></td>";
																												}
																								}
																								$this->salida.="  <input type=\"hidden\" name = \"lab_examen$k$e\"  value=\"".$vector[$i]['lab_examen_id']."\">";
																								$this->salida.="</tr>";
																								$e++;
																								$control_datalab++;
																								break;
																				}
								}//cierra el switche
				}//cierra el for
			if($control_datalab > 0)
				{
								$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
								$this->salida.="<td width=\"100%\" align=\"right\" colspan = \"6\" class=\"label_error\">GENERADO POR DATALAB</td>";
								$this->salida.="</tr>";
				}

				$this->salida.="</table>";
				$items = $e;
				$this->salida.="<input type=\"hidden\" name = \"items$k\" value=\"$items\">";

				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
				if ($_SESSION['CONSULTANDO_APD'] =='1')
				{
								$this->salida.="<tr>";
								$this->salida.="<td align=\"left\" colspan = \"2\" width=\"30%\" class=\"hc_table_submodulo_list_title\">OBSERVACION DEL PRESTADOR DEL SERVICIO</td>";
								$this->salida.="<td align=\"center\" colspan = \"2\" width=\"70%\" class=\"$estilo\">".$examenes[observacion_prestacion_servicio]."</td>";
								$this->salida.="</tr>";
				}
				else
				{
			if (!$_REQUEST['observacion'])
								{$_REQUEST['observacion']= $examenes[observacion_prestacion_servicio];}
								$this->salida.="<tr>";
								$this->salida.="<td align=\"left\" colspan = \"2\" width=\"30%\" class=\"hc_table_submodulo_list_title\">OBSERVACION DEL PRESTADOR DEL SERVICIO</td>";
								$this->salida.="<td align=\"center\" colspan = \"2\" width=\"70%\" class=\"$estilo\"><textarea class=\"textarea\" name=\"observacion\" rows=\"5\" cols=\"40\" style = \"width:80%\">".$_REQUEST['observacion']."</textarea></td>";
								$this->salida.="</tr>";
				}
			//print_r($_SESSION);
				if ($_SESSION['CONSULTANDO_APD'] =='1')
				{
								$nombre_profesional = $this->ConsultaNombreProfesional($usuario_profesional_autoriza);
								if($nombre_profesional[nombre_tercero]!='')
								{
												$this->salida.="<tr>";
												$this->salida.="<td align=\"left\" colspan = \"2\" width=\"30%\" class=\"hc_table_submodulo_list_title\">PROFESIONAL QUE FIRMò EL RESULTADO</td>";
												$this->salida.="<td align=\"center\" colspan = \"2\" width=\"70%\" class=\"$estilo\" >".$nombre_profesional[nombre_tercero]."</td>";
												$this->salida.="</tr>";
								}
				}
				else
				{
			//opcion que muestra el enlace para la firma si es un profesional
								if (!empty($_SESSION['LTRABAJOAPOYOD']['PROFESIONAL']))
								{
												$this->salida.="<tr>";
												$this->salida.="<td align=\"left\" colspan = \"2\"  width=\"30%\" class=\"hc_table_submodulo_list_title\">PROFESIONAL</td>";
												$this->salida.="<td align=\"center\" colspan = \"2\" width=\"70%\" class=\"$estilo\"><input type = \"checkbox\" name= \"firma\" value = \"1\">APROBACION DEL RESULTADO AQUI REGISTRADO(FIRMA)</td>";
												$this->salida.="</tr>";
								}
				}

				//caso especial que pinta el nombre del profesional al cual se le esta cargando el examen
				//cuando el departamento maneja honorarios.
				$nombre_profesional_honorario = $this->ConsultaNombreProfesionalHonorario($examenes[numero_orden_id]);
				if($nombre_profesional_honorario[nombre_tercero]!='')
				{
								$this->salida.="<tr class=\"$estilo\">";
								$this->salida.="<td align=\"left\" colspan = \"2\" width=\"30%\" class=\"hc_table_submodulo_list_title\">EXAMEN CARGADO A LOS HONORARIOS DE </td>";
								$this->salida.="<td align=\"center\" colspan = \"2\" width=\"70%\" class = \"label_error\">".$nombre_profesional_honorario[nombre_tercero]."</td>";
								$this->salida.="</tr>";
				}
				//fin del caso especial

			//lorena - mauricio
			if ($_SESSION['CONSULTANDO_APD'] =='1')
			{
				$nombre_profesional = $this->ConsultaNombreProfesional($usuario_profesional);
				if($nombre_profesional[nombre_tercero]!='')
				{
								$this->salida.="<tr>";
								$this->salida.="<td align='left' colspan = 2 class=\"hc_table_submodulo_list_title\"width='20%'>PROFESIONAL RESPONSABLE DEL RESULTADO</td>";
								$this->salida.="<td align=\"center\" class=\"modulo_list_claro\" colspan = 2>".$nombre_profesional[nombre_tercero]."</td>";
								$this->salida.="</tr>";
				}
			}else{
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td colspan = 2 align='left' class=".$this->SetStyle("responsable")." width='30%'>RESPONSABLE DIAGNOSTICO</td>";
				$this->salida.="<td colspan = 2 class=\"$estilo\" align=\"left\">";
				$this->salida.="<select size = 1 name = 'responsable' class =\"select\">";
				$opciones=$this->ProfesionalesDepartamento();
				if(empty($_SESSION['LTRABAJOAPOYOD']['PROFESIONAL']))
				{
						$identificacion=$this->BuscaProfesionalResultado($examenes[numero_orden_id]);
						if(sizeof($identificacion)<1){
			$identificacion=$this->BuscaProfesionalCumplimiento($examenes[numero_orden_id]);
						}
						$this->salida.="<option value ='-1' >--Seleccione-- </option>";
						for($j=0;$j<sizeof($opciones);$j++){
								if($identificacion['usuario_id']==$opciones[$j][usuario_id]){
										$this->salida.="<option value = ".$opciones[$j][usuario_id]." selected>".$opciones[$j][nombre]."</option>";
								}else{
										$this->salida.="<option value = ".$opciones[$j][usuario_id]." >".$opciones[$j][nombre]."</option>";
								}
						}
				}else{
			$identificacion=$this->BuscaProfesionalResultado($examenes[numero_orden_id]);
						if(sizeof($identificacion)>0){
			for($j=0;$j<sizeof($opciones);$j++){
										if($identificacion['usuario_id']==$opciones[$j][usuario_id]){
												$this->salida.="<option value = ".$opciones[$j][usuario_id]." selected>".$opciones[$j][nombre]."</option>";
										}
								}
						}else{
			$identificacion=$this->BuscaProfesionalCumplimiento($examenes[numero_orden_id]);
								if(sizeof($identificacion)<1){
			$identificacion['usuario_id']=UserGetUID();
								}
								$this->salida.="<option value ='-1' >--Seleccione-- </option>";
								for($j=0;$j<sizeof($opciones);$j++){
										if($identificacion['usuario_id']==$opciones[$j][usuario_id]){
												$this->salida.="<option value = ".$opciones[$j][usuario_id]." selected>".$opciones[$j][nombre]."</option>";
										}else{
												$this->salida.="<option value = ".$opciones[$j][usuario_id]." >".$opciones[$j][nombre]."</option>";
										}
								}
						}
				}
				$this->salida.="</select>";
				$this->salida.="</td>";
				$this->salida.="</tr>";
			}


			//fin

				$this->salida.="</table>";

				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
				$this->salida.="<tr>";
				if ($_SESSION['CONSULTANDO_APD'] !='1')
				{
								$this->salida.= "<td align=\"center\" colspan=\"2\"><input class=\"input-submit\" name=\"guardar\" type=\"submit\" value=\"GUARDAR\"></td>";
				}
				$this->salida.= "</form>";

				//BOTON DEVOLVER
				$accionV=ModuloGetURL('app','Os_Listas_Trabajo_Apoyod_Agrupado','user','BuscarOrden');
				$this->salida.="<form name=\"forma\" action=\"$accionV\" method=\"post\">";
				$this->salida.="<td align=\"center\" colspan = \"2\"><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"VOLVER\"></form></td>";
				$this->salida.="</tr>";
				$this->salida.="</table><br>";

				if(sizeof($examenes[observaciones_adicionales])>=1)
				{
								$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
								$this->salida.="<tr class=\"modulo_table_title\">";
								$this->salida.="<td align=\"left\" colspan=\"4\">OBSERVACIONES ADICIONALES REALIZADAS AL RESULTADO:</td>";
								$this->salida.="</tr>";

								$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
								$this->salida.="<td align=\"left\" width=\"5%\">No.</td>";
								$this->salida.="<td align=\"left\" width=\"10%\">FECHA DE REGISTRO</td>";
								$this->salida.="<td align=\"left\" width=\"20%\">USUARIO QUE REALIZA LA OBSERVACION</td>";
								$this->salida.="<td align=\"left\" width=\"45%\">OBSERVACION ADICIONAL AL RESULTADO</td>";
								$this->salida.="</tr>";

								for($i=0;$i<sizeof($examenes[observaciones_adicionales]);$i++)
								{
												$this->salida.="<tr class=\"modulo_list_claro\">";
												$this->salida.="<td align=\"center\">".($i+1)."</td>";
												$this->salida.="<td align=\"center\">".$this->FechaStamp($examenes[observaciones_adicionales][$i][fecha_registro_observacion])." - ".$this->HoraStamp($examenes[observaciones_adicionales][$i][fecha_registro_observacion])."</td>";
												$this->salida.="<td align=\"center\">".$examenes[observaciones_adicionales][$i][usuario_observacion]."</td>";
												$this->salida.="<td align=\"left\">".$examenes[observaciones_adicionales][$i][observacion_adicional]."</td>";
												$this->salida.="</tr>";
								}
								$this->salida.="</table><br>";
				}

				if (!empty($_SESSION['LTRABAJOAPOYOD']['PROFESIONAL']))
				{
								if ($_SESSION['CONSULTANDO_APD'] =='1' AND sizeof($examenes[lecturas])<1)
								{
												//observaciones realizadas al resultado luego de ser firmado y antes de ser leido
												$accionObs=ModuloGetURL('app','Os_Listas_Trabajo_Apoyod_Agrupado','user','GetForma', array('accion'=>'insertar_observacion_adicional','resultado_id' => $resultado_id));
												$this->salida.= "<form name=\"forma\" action=\"$accionObs\" method=\"post\">";
												$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
												$this->salida.="<tr class=\"modulo_table_title\">";
												$this->salida.="  <td align=\"center\" colspan=\"2\" >OBSERVACION ADICIONAL AL RESULTADO</td>";
												$this->salida.="</tr>";

												$this->salida.="<tr>";
												$this->salida.="<td align=\"left\" width=\"30%\" class=\"hc_table_submodulo_list_title\">FECHA DE REGISTRO</td>";
												$this->salida.="<td align=\"center\" width=\"70%\" class=\"modulo_list_claro\" >".date('Y-m-d')."</td>";
												$this->salida.="</tr>";

												$this->salida.="<tr>";
												$this->salida.="<td align=\"left\"  width=\"30%\" class=\"hc_table_submodulo_list_title\">OBSERVACION ADICIONAL AL RESULTADO</td>";
												$this->salida.="<td align=\"center\" width=\"70%\" class=\"modulo_list_claro\" ><textarea style = \"width:80%\" class=\"textarea\" name = \"observacion_adicional\" cols = \"60\" rows = \"5\">".$_REQUEST['observacion_adicional']."</textarea></td>";
												$this->salida.="</tr>";

												$this->salida.="<tr>";
												$this->salida .= "<td  colspan = \"2\" align=\"center\" class=\"modulo_list_claro\"><input class=\"input-submit\" name=\"guardar_observacion\" type=\"submit\" value=\"GUARDAR OBSERVACION\"></td>";
												$this->salida.="</tr>";
												$this->salida.="</table>";
												$this->salida.= "</form>";
								}
				}
				$this->salida.= ThemeCerrarTablaSubModulo();
				return true;
			}
    }


    /*
    * Esta funcion calcula el numero de pasos que saldran en la barra de navegación.
    * @return boolean
    */
    function CalcularNumeroPasos($conteo)
    {
            $numpaso=ceil($conteo/$this->limit);
            return $numpaso;
    }


    /*
    * Esta funcion calcula la barra de navegación.
    * @return boolean
    */
    function CalcularBarra($paso)
    {
            $barra=floor($paso/10)*10;
            if(($paso%10)==0)
            {
                    $barra=$barra-10;
            }
            return $barra;
    }


    /*
    * Esta funcion calcula los segmentos en que se desplaza el apuntador de los registros
    * de la base de datos.
    * @return boolean
    */
    function CalcularOffset($paso)
    {
            $offset=($paso*$this->limit)-$this->limit;
            return $offset;
    }


    /*
    * Esta funcion integra (CalcularNumeroPasos,CalcularOffset,CalcularBarra), para asi
    * crear una barra de navegacion, para los registros.
    * @return boolean
    */
    function RetornarBarra()
    {
            if($this->limit>=$this->conteo)
            {
                    return '';
            }
            $paso=$_REQUEST['paso'];
            if(is_null($paso))
            {
                $paso=1;
            }
            $vec='';
            foreach($_REQUEST as $v=>$v1)
            {
                if($v!='modulo' and $v!='metodo' and $v!='SIIS_SID' and  $v!='Of')
                {   $vec[$v]=$v1;   }
            }
            $accion=ModuloGetURL('app','Os_Listas_Trabajo_Apoyod_Agrupado','user','BuscarOrden',$vec);
            $barra=$this->CalcularBarra($paso);
            $numpasos=$this->CalcularNumeroPasos($this->conteo);
            $colspan=1;

            $this->salida .= "<br><table border=\"1\" align=\"center\" style=\"border:1px solid #D3DCE3\" cellpadding=\"4\"><tr><td class=\"label\" bgcolor=\"#D3DCE3\">Paginas :</td>";
            if($paso > 1)
            {
                    $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
                    $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
                    $colspan+=1;
            }
            else
            {
                    // $this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
                    //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
            }
            $barra ++;
            if(($barra+10)<=$numpasos)
            {
                    for($i=($barra);$i<($barra+10);$i++)
                    {
                            if($paso==$i)
                            {
                                    $this->salida .= "<td bgcolor=\"#D3DCE3\"><b class=\"label\" style=\"font-size:12px\">$i</b></td>";
                            }
                            else
                            {
                                    $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' class=\"label_error\" style=\"font-size:12px\">$i</a></td>";
                            }
                            $colspan++;
                    }
                    $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
                    $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
                    $colspan+=2;
            }
            else
            {
                    $diferencia=$numpasos-9;
                    if($diferencia<=0){$diferencia=1;}//cambiar en todas las barra
                    for($i=($diferencia);$i<=$numpasos;$i++)
                    {
                            if($paso==$i)
                            {
                                    $this->salida .= "<td bgcolor=\"#DDDDDD\" class=\"label\" style=\"font-size:12px\">$i</td>";
                            }
                            else
                            {
                                    $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' class=\"label_error\" style=\"font-size:12px\">$i</a></td>";
                            }
                            $colspan++;
                    }
                    if($paso!=$numpasos)
                    {
                            $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' class=\"label_error\" style=\"font-size:12px\">&gt;&gt;</a></td>";
                            $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos' class=\"label_error\" style=\"font-size:12px\">&gt;</a></td>";
                            $colspan++;
                    }
                    else
                    {
                            // $this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
                            //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
                    }
            }
            if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
            {
                    if($numpasos>10)
                    {
                        $valor=10+3;
                    }
                    else
                    {
                        $valor=$numpasos+3;
                    }
                    $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
            }
            else
            {
                    if($numpasos>10)
                    {
                            $valor=10+5;
                    }
                    else
                    {
                            $valor=$numpasos+5;
                    }
                    $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
            }
    }


    //FUNCIONES QUE ACOMPAÑAN AL CALENDARIO
    /**
    * Funcion que Saca los anos para el calendario a partir del año actual
    * @return array
    */
    function AnosAgenda($Seleccionado='False',$ano)
    {
            $anoActual=date("Y");
            //$ano = $anoActual;
            $anoActual1=$anoActual-10;
            for($i=0;$i<=20;$i++)
            {
                    $vars[$i]=$anoActual1;
                    $anoActual1=$anoActual1+1;
            }
            switch($Seleccionado)
            {
                    case 'False':   {
                                                        foreach($vars as $value=>$titulo)
                                                        {
                                                                if($titulo==$ano)
                                                                {
                                                                        $this->salida .=" <option value=\"$titulo\" selected>$titulo</option>";
                                                                }
                                                                else
                                                                {
                                                                        $this->salida .=" <option value=\"$titulo\">$titulo</option>";
                                                                }
                                                        }
                                                        break;
                                }
                    case 'True':    {
                                                        foreach($vars as $value=>$titulo)
                                                        {
                                                                if($titulo==$ano)
                                                                {
                                                                        $this->salida .=" <option value=\"$titulo\" selected>$titulo</option>";
                                                                }
                                                                else
                                                                {
                                                                        $this->salida .=" <option value=\"$titulo\">$titulo</option>";
                                                                }
                                                        }
                                                        break;
                                                }
            }
    }

  /**
    * Funcion que Saca los meses para el calendario a partir del año actual
    * @return array
    */
    function MesesAgenda($Seleccionado='False',$Año,$Defecto)
    {
            $anoActual=date("Y");
            $vars[1]='ENERO';
            $vars[2]='FEBRERO';
            $vars[3]='MARZO';
            $vars[4]='ABRIL';
            $vars[5]='MAYO';
            $vars[6]='JUNIO';
            $vars[7]='JULIO';
            $vars[8]='AGOSTO';
            $vars[9]='SEPTIEMBRE';
            $vars[10]='OCTUBRE';
            $vars[11]='NOVIEMBRE';
            $vars[12]='DICIEMBRE';
            //$mesActual=date("m");
            switch($Seleccionado)
            {
                    case 'False':
                    {
                            if($anoActual==$Año)
                            {
                                    foreach($vars as $value=>$titulo)
                                    {
                                        if($value>=$mesActual)
                                        {
                                            if($value==$Defecto)
                                            {
                                                    $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
                                            }
                                            else
                                            {
                                                    $this->salida .=" <option value=\"$value\">$titulo</option>";
                                            }
                                        }
                                    }
                            }
                            else
                            {
                                    foreach($vars as $value=>$titulo)
                                    {
                                            if($value==$Defecto)
                                            {
                                                    $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
                                            }
                                            else
                                            {
                                                    $this->salida .=" <option value=\"$value\">$titulo</option>";
                                            }
                                    }
                            }
                            break;
                    }
                    case 'True':
                    {
                            if($anoActual==$Año)
                            {
                                    foreach($vars as $value=>$titulo)
                                    {
                                            if($value>=$mesActual)
                                            {
                                                    if($value==$Defecto)
                                                    {
                                                            $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
                                                    }
                                                    else
                                                    {
                                                            $this->salida .=" <option value=\"$value\">$titulo</option>";
                                                    }
                                            }
                                    }
                            }
                            else
                            {
                                    foreach($vars as $value=>$titulo)
                                    {
                                            if($value==$Defecto)
                                            {
                                                    $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
                                            }
                                            else
                                            {
                                                    $this->salida .=" <option value=\"$value\">$titulo</option>";
                                            }
                                    }
                            }
                            break;
                    }
            }
    }


    //*****************************CLAUDIA ------------nueva forma de capturar los apoyos
    //nuevas funciones - version alfa

    /**
    * Funcion que permite la captura de los resultados de forma grupal
    * @return boolean
    */
    function Capturar_Resultados($tipo_id_paciente, $paciente_id, $nombre, $servicio, $numero_cumplimiento, $fecha_cumplimiento)
    {
      /*
			!!!! CUIDADO ¡¡¡¡
			El calendario mostrado en este formulario genera cada dia del mes
			en un link. Pero, ese link se crea concatenando cada uno de los
			resultados del request. El request de los resultados, que son
			editados a traves del FCKeditor es un html, que termina por
			verse en el calendario.
			VERIFICAR RESULTADO, con estos dos for o conun unset de todo
			el request
			*/
			for($i=0;$i<=9;$i++)
			{
				if(isset($_REQUEST['resultado0'.$i]))
					unset($_REQUEST['resultado0'.$i]);
				else
					break;
			}
			for($i=10;$i<=99;$i++)
			{
				if(isset($_REQUEST['resultado'.$i]))
					unset($_REQUEST['resultado'.$i]);
				else
					break;
			}
        //comparacion para permitir el retorno desde Hc.
      if(!$_SESSION['DATOS_APD']['CONSULTA_RESULT'])
			{//echo ENTRO;
				$_SESSION['DATOS_APD']['CONSULTA_RESULT']['tipo_id_paciente'] =$tipo_id_paciente;
				$_SESSION['DATOS_APD']['CONSULTA_RESULT']['paciente_id']=$paciente_id;
				$_SESSION['DATOS_APD']['CONSULTA_RESULT']['nombre']=$nombre;
				$_SESSION['DATOS_APD']['CONSULTA_RESULT']['servicio']=$servicio;
				$_SESSION['DATOS_APD']['CONSULTA_RESULT']['numero_cumplimiento']=$numero_cumplimiento;
				$_SESSION['DATOS_APD']['CONSULTA_RESULT']['fecha_cumplimiento']=$fecha_cumplimiento;
			}

			$tipo_id_paciente = $_SESSION['DATOS_APD']['CONSULTA_RESULT']['tipo_id_paciente'];
			$paciente_id = $_SESSION['DATOS_APD']['CONSULTA_RESULT']['paciente_id'];
			$nombre = $_SESSION['DATOS_APD']['CONSULTA_RESULT']['nombre'];
			$servicio = $_SESSION['DATOS_APD']['CONSULTA_RESULT']['servicio'];
			$numero_cumplimiento=$_SESSION['DATOS_APD']['CONSULTA_RESULT']['numero_cumplimiento'];
			$fecha_cumplimiento=$_SESSION['DATOS_APD']['CONSULTA_RESULT']['fecha_cumplimiento'];
      
			$sexo_paciente = $this->GetSexo($tipo_id_paciente, $paciente_id);
			$edad_paciente = $this->Obtener_Edad($tipo_id_paciente, $paciente_id);

			$this->salida  = ThemeAbrirTablaSubModulo('CAPTURA DE RESULTADOS EN GRUPO');
			$this->salida .= "</script>";
			//MauroB
			//Se realiza modificacion para que funcione el BOOKMARK
			$this->salida .= "<script language=\"JavaScript\">;";
			$this->salida .= "function ConsultaComponentes(frm,indice)";
			$this->salida .= "{";
			$this->salida .= "  frm.opcion.value='cambio_tecnica';";
			$this->salida .= "  frm.posicion.value=indice;";
			$this->salida .= "  frm.action=frm.action+'#'+indice;";
			$this->salida .= "  frm.submit();";
			$this->salida .= '}'."\n";
			$this->salida .= "</script>";
			//MauroB
			$this->salida .= "<script language=\"JavaScript\">;";
			$this->salida .= "function creacion_indice(frm,valor)";
			$this->salida .= "{";
			$this->salida .= "  frm.opcion.value='capturar_observacion';";
			$this->salida .= "  frm.posicion.value=valor;";
			$this->salida .= "  frm.submit();";
			$this->salida .= '}'."\n";
			$this->salida .= "</script>";
			$this->salida .= "<table  align=\"center\" border=\"0\"  width=\"100%\">";
			$this->salida .= "<tr class=\"modulo_table_title\">";
			$this->salida .= "  <td align=\"center\">ID DEL PACIENTE</td>";
			$this->salida .= "  <td align=\"center\">NOMBRE DEL PACIENTE</td>";
			$this->salida .= "  <td align=\"center\">EDAD DEL PACIENTE</td>";
			$this->salida .= "</tr>";
			$this->salida .= "<tr class=\"modulo_table_title\">";
			$this->salida .= "  <td align=\"center\">".$tipo_id_paciente.": ".$paciente_id."</td>";
			$this->salida .= "  <td align=\"center\">".$nombre."</td>";
			$this->salida .= "  <td align=\"center\">".$edad_paciente[edad_aprox]."</td>";
			$this->salida .= "</tr>";
			$this->salida .= "</table>";

			$action=ModuloGetURL('app','Os_Listas_Trabajo_Apoyod_Agrupado','user','GetForma',array('accion'=>'insertar_resultado', 'paciente_id'=>$paciente_id, 'tipo_id_paciente'=>$tipo_id_paciente, 'nombre'=>$nombre,'servicio'=>$servicio, 'numero_cumplimiento' => $numero_cumplimiento, 'fecha_cumplimiento' =>$fecha_cumplimiento));

			$this->salida .= "<form name=\"formacaptura\" action=\"$action\" method=\"post\">";
			$this->salida .= "<table  align=\"center\" border=\"0\"  width=\"90%\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "</table>";

			//OBTENIENDO TODOS LOS APOYOS QUE LE FUERON ENVIADOS AL PACIENTE SEGUN EL FILTRO ESCOGIDO
			$datos = '';
			$datos = $this->ConsultaOrdenesPaciente($paciente_id, $tipo_id_paciente, $servicio, $numero_cumplimiento, $fecha_cumplimiento, $sexo_paciente);
			
						//echo "datos-> ";print "<pre>";print_r($datos);
            /*if(!$datos)
            {
        //habria que hacer la prueba para ver si al enviar un examen que no exista que pasa.
                //si los otros salen o no
                //continuar aqui en agosto para ver como se mete el generico.
            }
            $datos = $this->ConsultaOrdenesPaciente($paciente_id, $tipo_id_paciente, $servicio, $numero_cumplimiento, $fecha_cumplimiento, $sexo_paciente);*/
            if ($datos)
            {
                    for($k=0;$k<sizeof($datos);$k++)
                    {
                            if($datos[$k][servicio]!= $datos[$k-1][servicio])
                            {
                                    $this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
                                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                                    $this->salida.="  <td align=\"left\" width=\"80%\">SERVICIO: ".$datos[$k][servicio_descripcion]."</td>";
                                    $this->salida.="</tr>";
                                    $this->salida.="</table>";
                            }
                            if(($datos[$k][fecha_cumplimiento]!= $datos[$k-1][fecha_cumplimiento]) AND ($datos[$k][numero_cumplimiento]!= $datos[$k-1][numero_cumplimiento]))
                            {
                                    $this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
                                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                                    $this->salida.="  <td align=\"left\" width=\"20%\">FECHA DE CUMPLIMIENTO: </td>";
                                    $this->salida.="  <td align=\"center\" width=\"10%\">".$datos[$k][fecha_cumplimiento]."</td>";
                                    $this->salida.="  <td align=\"left\" width=\"20%\">NUMERO DE CUMPLIMIENTO: </td>";
                                    //MauroB
                                    $cumplimiento=$this->ConvierteCumplimiento($datos[$k][fecha_cumplimiento],$datos[$k][numero_cumplimiento],$_SESSION['LTRABAJOAPOYOD']['DPTO']);
                                    //$this->salida.="  <td align=\"center\" width=\"10%\">".$datos[$k][numero_cumplimiento]."</td>";
                                    $this->salida.="  <td align=\"center\" width=\"10%\">".$cumplimiento."</td>";
                                    //fin MauroB
                                    $this->salida.="</tr>";
                                    $this->salida.="</table>";
                            }
                            if($datos[$k][nombre_lista]!= $datos[$k-1][nombre_lista])
                            {
                                    $this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
                                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                                    $this->salida.="  <td align=\"left\" width=\"100%\">".$datos[$k][nombre_lista]."</td>";
                                    $this->salida.="</tr>";
                                    $this->salida.="</table>";
                            }

                            $this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
                            $this->salida.="<tr class=\"modulo_table_title\">";
                            $this->salida.="<td align=\"center\" width=\"5%\">CARGO</td>";
                            $this->salida.="<td align=\"center\" width=\"35%\">EXAMEN</td>";
                            $this->salida.="<td align=\"center\" width=\"12%\">TECNICA</td>";
                            $this->salida.="<td align=\"center\" width=\"12%\" colspan=\"4\">OPCIONES</td>";
                            $this->salida.="<td align=\"center\" width=\"26%\" class=\"".$this->SetStyle("fecha_realizado$k")."\">FECHA</td>";
                            if (!empty($_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['profesional_honorario']))
                            {
                                    $this->salida.="<td align=\"center\" width=\"5%\">HONO.</td>";
                            }
                            if (!empty($_SESSION['LTRABAJOAPOYOD']['PROFESIONAL']))
                            {
                                    $this->salida.="<td align=\"center\" width=\"5%\">FIRMAR</td>";
                            }
                            $this->salida.="</tr>";

                            $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                            $this->salida.="<td align=\"center\" width=\"5%\">".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['cargo']."</td>";
                            $this->salida.="<td align=\"center\" width=\"35%\">".strtoupper($_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['titulo'])."</td>";

                            $this->salida.="<td align=\"center\" width=\"12%\">";
                            if (!empty($_REQUEST['posicion']) OR ($_REQUEST['posicion']== '0'))
                            {
                                if ($_REQUEST['posicion']==$k)
                                {
                                    $_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']=$_REQUEST['tecnica'.$k.$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['cargo']];
                                }
                            }
                            $this->salida.="<select id=\"$k\" name=\"tecnica".$k."".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['cargo']."\" class=\"select\" onChange=\"ConsultaComponentes(this.form,$k)\">";
                            for($j=0;$j<sizeof($_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica']);$j++)
                            {
                                    if($_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']==$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica'][$j]['tecnica_id'])
                                    {
                                            $this->salida.="<option value = ".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica'][$j]['tecnica_id']." selected >".substr($_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica'][$j]['nombre_tecnica'],0,50)."...</option>";
                                    }
                                    else
                                    {
                                            $this->salida.="<option value = ".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica'][$j]['tecnica_id']." >".substr($_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica'][$j]['nombre_tecnica'],0,50)."...</option>";
                                    }
                            }
                            $this->salida.="</select>";
                            $this->salida.="</td>";

                            if($_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['informacion']=='')
                            {
                                    $this->salida.="<td align=\"center\" width=\"3%\"><img src=\"".GetThemePath()."/images/Vacio.gif\" title=\"sin Informacion \"  border=\"0\"></td>";
                            }
                            else
                            {
                                    $this->salida.="<td align=\"center\" width=\"3%\"><img src=\"'".GetThemePath()."/images/EstacionEnfermeria/info.png'\" title=\"Informacion: ".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['informacion']."\"  border=\"0\"></td>";
                            }

                            //opcion que muestra el link de la historia clinica si es un profesional
                            if (!empty($_SESSION['LTRABAJOAPOYOD']['PROFESIONAL']))
                            {
                                    if ($_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['evolucion_id']!= NULL)
                                    {
                                            $_SESSION['HISTORIACLINICA']['RETORNO']['modulo']='Os_Listas_Trabajo_Apoyod_Agrupado';
                                            $_SESSION['HISTORIACLINICA']['RETORNO']['metodo']='Capturar_Resultados';
                                            $_SESSION['HISTORIACLINICA']['RETORNO']['tipo']='user';
                                            $_SESSION['HISTORIACLINICA']['RETORNO']['contenedor']='app';
                                            $_SESSION['HISTORIACLINICA']['RETORNO']['argumentos']=array('BOOKMARK'=>$k);
                                            $accion=ModuloHCGetURL($_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['evolucion_id'],'','','','',array());
                                            $this->salida.="<td align=\"center\" width=\"3%\"><a href=".$accion."><img src=\"".GetThemePath()."/images/honorarios.png\" border='0' title=\"ver HC\"></a</td>";
                                    }
                                    else
                                    {
                                            $this->salida.="<td align=\"center\" width=\"3%\"><img src=\"".GetThemePath()."/images/pincumplimiento_citas.png\" border='0' title=\"Solicitado fuera de HC\"></td>";
                                    }
                            }
                            else
                            {
                                    $this->salida.="<td align=\"center\" width=\"3%\"><img src=\"".GetThemePath()."/images/pincumplimiento_citas.png\" border=\"0\" title=\"sin acceso a HC\"></td>";
                            }

                            $this->SetJavaScripts('DatosSolicitudApoyo');
                            $this->salida.="<td align=\"center\" width=\"3%\"><a href=\"javascript:DatosSolicitudApoyo(".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['hc_os_solicitud_id'].", '".$tipo_id_paciente."', '".$paciente_id."', '".$nombre."', '".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['cargo']."', '".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['titulo']."')\"><img src=\"".GetThemePath()."/images/resumen.gif\" border=\"0\" title=\"Ver Datos Solicitud\"> </a></td>";

                            if (!empty($_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['observacion']))
                            {
                                    $this->salida.="<td align=\"center\" width=\"3%\"><a href=\"javascript:creacion_indice(document.formacaptura,'$k')\">   <img src=\"".GetThemePath()."/images/modificar.gif\" border=\"0\" title=\"Modificar Observacion\">  </a></td>";
                            }
                            else
                            {
                                    $this->salida.="<td align=\"center\" width=\"3%\"><a href=\"javascript:creacion_indice(document.formacaptura,'$k')\">   <img src=\"".GetThemePath()."/images/modificar.gif\" border=\"0\" title=\"Agregar Observacion\">    </a></td>";
                            }

                            if ($_SESSION['CONSTRUCTOR_REQUEST']==1)
                            {
                                    $_REQUEST['fecha_realizado'.$k] = $_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['fecha_realizado'];
                            }
                            else
                            {
                                    if (empty($_REQUEST['fecha_realizado'.$k]))
                                    {
                                            $_REQUEST['fecha_realizado'.$k] = date('d-m-Y');
                                    }
                            }
                            $this->salida.="<td align=\"center\" width=\"26%\"><input type=\"text\" readonly class=\"input-text\" size=\"10\" maxlength=\"10\" value = \"".$_REQUEST['fecha_realizado'.$k]."\" name=\"fecha_realizado$k\" onFocus=\"this.select();\"  onBlur=\"IsValidDate(this,'YYYY-MM-DD')\" onKeyUp=\"setDate(this,'YYYY-MM-DD','es')\">".ReturnOpenCalendario('formacaptura',"fecha_realizado$k",'-')."</td>";

                            //caso especial que pinta el nombre del profesional al cual se le esta cargando el examen
                            //exclusivo para clinica de occidente en imagenes
                            if (!empty($_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['profesional_honorario']))
                            {
                                    $this->salida.="<td align=\"center\" width=\"5%\"><img src=\"".GetThemePath()."/images/honorarios.png\" border=\"0\" title=\"EXAMEN CARGADO A LOS HONORARIOS DE: ".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['profesional_honorario']."\"></td>";
                            }
                            //fin del caso especial


                            if (!empty($_SESSION['LTRABAJOAPOYOD']['PROFESIONAL']))
                            {
                                    if ($_SESSION['CONSTRUCTOR_REQUEST']==1)
                                    {
                                            $_REQUEST['firma'.$k] = $_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['firma'];
                                    }
                                    if ($_REQUEST['firma'.$k]=='1')
                                    {
                                            $this->salida.="<td align=\"center\" width=\"5%\"><input type=\"checkbox\" name=\"firma$k\" value=\"1\" checked >APROBAR RESULTADO</td>";
                                    }
                                    else
                                    {
                                            $this->salida.="<td align=\"center\" width=\"5%\"><input type=\"checkbox\" name=\"firma$k\" value=\"1\">APROBAR RESULTADO</td>";
                                    }
                            }
                            $this->salida.="</tr>";
                            $this->salida.="</table>";

                            //llama a la funcion que consulta los subexamens de cada apoyo solicitado al paciente
                            unset($vector);
                            $vector=$this->ConsultaComponentesExamen($_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['cargo'], $_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada'] ,$sexo_paciente, $edad_paciente[anos], $k, $tipo_id_paciente, $paciente_id, $_REQUEST['indice']);
        //                  if(!$vector)
        //                  {
        //                          if($this->CrearGenerico($datos[$k][cargo], $_SESSION['LISTA']['APOYO']['titulo'], $a)==false)
        //                          {
        //                              $this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
        //                              $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
        //                              $this->salida.="  <td>REPORTE DEL SISTEMA</td>";
        //                              $this->salida.="</tr>";
        //                              $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
        //                              $this->salida.="  <td>ESTE APOYO DIAGNOSTICO NO TIENE EXAMENES RELACIONADOS - LA INFORMACION DE ESTE APOYO ESTA SIENDO CARGADA</td>";
        //                              $this->salida.="</tr>";
        //                              $this->salida.="</form>";
        //                              $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
        //                              //reemplaze forma metodo buscar
        //                              $accionV=ModuloGetURL('app','Os_Listas_Trabajo_Apoyod_Agrupado','user','BuscarOrden');
        //                              $this->salida .= "<form name=\"forma\" action=\"$accionV\" method=\"post\">";
        //                              $this->salida .= "<td  align=\"center\"><br><input class=\"input-submit\" name=\"Apoyod$pfj\" type=\"submit\" value=\"LISTA DE APOYO DIAG.\"></form></td>";
        //                              $this->salida.="</tr>";
        //                              $this->salida.="</table>";
        //                              $this->salida .= ThemeCerrarTablaSubModulo();
        //                              return true;
        //                          }
        //                          else
        //                          {
        //                              $vector=$this->ConsultaComponentesExamen_antiguo($datos[$k][cargo]);
        //                          }
        //                  }

                            if($vector)
                            {
                                    $this->salida.="<input type=\"hidden\" name = \"vector$k\"  value=\"".sizeof($vector)."\">";
                                    $this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
                                    $indmin=1;
                                    $e=0;
                                    for($i=0;$i<sizeof($vector);$i++)
                                    {
                                            if( $i % 2)
                                            {$estilo='modulo_list_claro';}
                                            else
                                            {$estilo='modulo_list_oscuro';}

                                            if ($_SESSION['CONSTRUCTOR_REQUEST']==1)
                                            {
                                                    $_REQUEST['resultado'.$k.$e] = $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['resultado'];
                                                    $_REQUEST['sw_patologico'.$k.$e] = $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['sw_patologico'];
                                            }

                                            switch ($_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['lab_plantilla_id'])
                                            {
                                                    case "1": { //echo "<br> caso1";
                                                                                $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                                                                                $this->salida.="<td width=\"35%\" align=\"center\">SUBEXAMEN</td>";
                                                                                $this->salida.="<td width=\"30%\" align=\"center\">RESULTADO</td>";
                                                                                $this->salida.="<td width=\"10%\" align=\"center\">V.MIN</td>";
                                                                                $this->salida.="<td width=\"10%\" align=\"center\">V.MAX</td>";
                                                                                $this->salida.="<td width=\"10%\" align=\"center\">UND</td>";
                                                                                $this->salida.="<td width=\"5%\"  align=\"center\">PAT.</td>";
                                                                                $this->salida.="</tr>";

                                                                                if(is_null($_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['rango_min']) || $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['rango_min'] == '0')
                                                                                {
                                                                                        $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['rango_min'] = 0;
                                                                                }

                                                                                if ($_REQUEST['rmin'.$k.$e])
                                                                                {$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['rango_min']= $_REQUEST['rmin'.$k.$e];}

                                                                                if ($_REQUEST['rmax'.$k.$e])
                                                                                {$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['rango_max']= $_REQUEST['rmax'.$k.$e];}

                                                                                if ($_REQUEST['unidades'.$k.$e])
                                                                                {$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['unidades_1']= $_REQUEST['unidades'.$k.$e];}

                                                                                $this->salida.="<tr class=\"$estilo\">";
                                                                                $this->salida.="<td width=\"35%\" align=\"left\"  class=\"".$this->SetStyle("resultado$k$e")."\">".strtoupper($_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['nombre_examen'])."</td>";
                                                                                $this->salida.="<td width=\"30%\" align=\"center\"><input type=\"text\" name = \"resultado$k$e\" value =\"".$_REQUEST['resultado'.$k.$e]."\">&nbsp;".$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['unidades_1']."</td>";
                                                                                $this->salida.="<td width=\"10%\" align=\"center\"><input type=\"text\" name=\"rmin$k$e\" class=\"input-text-center\" size=\"10\"   value=\"".$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['rango_min']."\"></td>";
                                                                                $this->salida.="<td width=\"10%\" align=\"center\"><input type=\"text\" name=\"rmax$k$e\" class=\"input-text-center\" size=\"10\"   value=\"".$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['rango_max']."\"></td>";
                                                                                $this->salida.="<td width=\"10%\" align=\"center\"><input type=\"text\" name=\"unidades$k$e\" class=\"input-text-center\" size=\"10\"   value=\"".$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['unidades_1']."\"></td>";

                                                                                if ($_REQUEST['sw_patologico'.$k.$e] == '1')
                                                                                {
                                                                                        $this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" checked name=\"sw_patologico$k$e\" value=\"1\"></td>";
                                                                                }
                                                                                else
                                                                                {
                                                                                        $this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" name=\"sw_patologico$k$e\" value=\"1\"></td>";
                                                                                }
                                                                                $this->salida.="</tr>";

                                                                                $this->salida.="  <input type=\"hidden\" name = \"lab_examen$k$e\"  value=\"".$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['lab_examen_id']."\">";
                                                                                $e++;
                                                                                break;
                                                                        }

                                                    case "2": {//echo "<br> caso2";
                                                                                if ($indmin == 1)
                                                                                {
                                                                                        $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                                                                                        $this->salida.="<td width=\"35%\" align=\"center\">SUBEXAMEN</td>";
                                                                                        $this->salida.="<td width=\"40%\" align=\"center\" colspan = \"2\">RESULTADO</td>";
                                                                                        $this->salida.="<td width=\"20%\" align=\"center\" colspan = \"2\">UND</td>";
                                                                                        $this->salida.="<td width=\"5%\" align=\"center\">PAT.</td>";
                                                                                        $this->salida.="</tr>";
                                                                                        $this->salida.="<tr class=\"$estilo\">";
                                                                                        $this->salida.="<td align=\"left\" width=\"40%\" class=".$this->SetStyle("resultado$k$e").">".strtoupper($_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['nombre_examen'])."</td>";
                                                                                        $this->salida.="<td align=\"center\" width=\"45%\" colspan = \"2\">";
                                                                                        $this->salida.="<select size = \"1\" name = \"resultado$k$e\"  class =\"select\">";
                                                                                        $this->salida.="<option value = \"-1\" >--Seleccione--</option>";
                                                                                        if($_REQUEST['resultado'.$k.$e]==$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['opcion'])
                                                                                        {
                                                                                                $this->salida.="<option value = \"".$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['opcion']."\" selected>".$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['opcion']."</option>";
                                                                                        }
                                                                                        else
                                                                                        {
                                                                                                $this->salida.="<option value = \"".$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['opcion']."\" >".$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['opcion']."</option>";
                                                                                        }
                                                                                        $indmin++;
                                                                                }
                                                                                else
                                                                                {
                                                                                        if($_REQUEST['resultado'.$k.$e]==$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['opcion'])
                                                                                        {
                                                                                            $this->salida.="<option value = \"".$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['opcion']."\" selected>".$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['opcion']."</option>";
                                                                                        }
                                                                                        else
                                                                                        {
                                                                                            $this->salida.="<option value = \"".$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['opcion']."\" >".$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['opcion']."</option>";
                                                                                        }
                                                                                }
                                                                                if($_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['lab_examen_id']!=$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i+1]['lab_examen_id'])
                                                                                {
                                                                                        $this->salida.="</select>";
                                                                                        $this->salida.="</td>";

                                                                                        if ($_REQUEST['unidades'.$k.$e])
                                                                                        {$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['unidades_2']= $_REQUEST['unidades'.$k.$e];}

                                                                                        $this->salida.="<td width=\"20%\" align=\"center\" colspan = \"2\"><input type=\"text\" class=\"input-text-center\" name=\"unidades$k$e\"  size=\"10\"   value=\"".$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['unidades_2']."\"></td>";

                                                                                        if ($_REQUEST['sw_patologico'.$k.$e] == '1')
                                                                                        {
                                                                                                $this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" checked name=\"sw_patologico$k$e\" value=\"1\"></td>";
                                                                                        }
                                                                                        else
                                                                                        {
                                                                                                $this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" name=\"sw_patologico$k$e\" value=\"1\"></td>";
                                                                                        }
                                                                                        $this->salida.="<input type=\"hidden\" name = \"lab_examen$k$e\"  value=\"".$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['lab_examen_id']."\">";
                                                                                        $this->salida.="</tr>";
                                                                                        $indmin=1;
                                                                                        $e++;
                                                                                }
                                                                                break;
                                                                        }

                                                    case "3": {//echo "<br>1 caso3";
                                                                                $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                                  												$this->salida.="<td width=\"35%\" align=\"center\">SUBEXAMEN</td>";                                              
                                                                                $this->salida.="<td width=\"60%\" align=\"center\" colspan = \"4\">RESULTADO</td>";
                                                                                $this->salida.="<td width=\"5%\" align=\"center\">PAT.</td>";
                                                                                $this->salida.="</tr>";

                                                                                $this->salida.="<tr class=\"$estilo\">";
                                                                                //$this->salida.="  <td  align=\"center\" width=\"5%\" class=".$this->SetStyle("resultado$k$e").">".strtoupper($_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']][$i]['nombre_examen'])."</td>";//
                                                                                if($_REQUEST['resultado'.$k.$e]==='' OR !empty($_REQUEST['resultado'.$k.$e]))
                                                                                {
                                                                                        //$this->salida.="<td colspan = \"4\" align=\"center\" width=\"60%\"><textarea style = \"width:100%\" class=\"textarea\" name = \"resultado$k$e\" cols = \"150\" rows = \"30\">".$_REQUEST['resultado'.$k.$e]."</textarea></td>";
                                                                                        $this->salida .= "<td colspan = \"5\" align=\"center\" width=\"60%\">";
                                                                                        $this->salida .= getFckeditor("resultado$k$e",'200',"100%",$_REQUEST['resultado'.$k.$e]);
                                                                                        $this->salida .= "</td>";
                                                                                }
                                                                                else
                                                                                {
                                                                                        //$this->salida.="<td colspan = \"4\" align=\"center\" width=\"60%\"><textarea style = \"width:100%\" class=\"textarea\" name = \"resultado$k$e\" cols = \"150\" rows = \"30\">".$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['detalle']."</textarea></td>";
                                                                                        $this->salida .= "<td colspan = \"5\" align=\"center\" width=\"60%\">";
                                                                                        $this->salida .= getFckeditor("resultado$k$e",'200',"100%",$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['detalle']);
                                                                                        $this->salida .= "</td>";
                                                                                }

                                                                                if ($_REQUEST['sw_patologico'.$k.$e] == '1')
                                                                                {
                                                                                        $this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" checked name=\"sw_patologico$k$e\" value=\"1\"></td>";
                                                                                }
                                                                                else
                                                                                {
                                                                                        $this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" name=\"sw_patologico$k$e\" value=\"1\"></td>";
                                                                                }
                                                                                $this->salida.="<input type=\"hidden\" name = \"lab_examen$k$e\"  value=\"".$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['lab_examen_id']."\">";
                                                                                $this->salida.="</tr>";
                                                                                $e++;
                                                                                break;
                                                                        }

                                                    case "0": {//echo "<br> caso0";
                                                                                $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                                                                                $this->salida.="<td width=\"35%\" align=\"center\">SUBEXAMEN</td>";
                                                                                $this->salida.="<td width=\"60%\" align=\"center\" colspan = \"4\">RESULTADO</td>";
                                                                                $this->salida.="<td width=\"5%\"  align=\"center\">PAT.</td>";
                                                                                $this->salida.="</tr>";

                                                                                $this->salida.="<tr class=\"$estilo\">";
                                                                                $this->salida.="<td width=\"35%\" align=\"center\" class=\"".$this->SetStyle("resultado$k$e")."\">".strtoupper($_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['nombre_examen'])."</td>";
                                                                                //$this->salida.="<td width=\"60%\" align=\"center\" colspan = \"4\"><textarea style = \"width:80%\" class=\"textarea\" name = \"resultado$k$e\" cols = \"60\" rows = \"10\">".$_REQUEST['resultado'.$k.$e]."</textarea></td>";
                                                                                $this->salida .= "<td colspan = \"4\" align=\"center\" width=\"60%\">";
                                                                                $fckeditor=getFckeditor("resultado$k$e",'200',"100%",$_REQUEST['resultado'.$k.$e]);
                                                                                        //$this->salida .= getFckeditor("resultado$k$e",'200',"100%",$_REQUEST['resultado'.$k.$e]);
                                                                                $this->salida .= $fckeditor;
                                                                                $this->salida .= "</td>";
                                                                                if ($_REQUEST['sw_patologico'.$k.$e] == '1')
                                                                                {
                                                                                        $this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" checked name=\"sw_patologico$k$e\" value=\"1\"></td>";
                                                                                }
                                                                                else
                                                                                {
                                                                                        $this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" name=\"sw_patologico$k$e\" value=\"1\"></td>";
                                                                                }
                                                                                $this->salida.="<input type=\"hidden\" name = \"lab_examen$k$e\"  value=\"".$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['lab_examen_id']."\">";
                                                                                $this->salida.="</tr>";
                                                                                $e++;
                                                                                break;
                                                                        }
                                            }//cierra el switche

//                                          //MauroB
//                                          $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
//                                          $this->salida.="<td colspan = 1 align='left' class=".$this->SetStyle("responsable")." width='30%'>RESPONSABLE DIAGNOSTICO</td>";
//                                          $this->salida.="<td colspan = 5 class=\"$estilo\" align=\"left\">";
//                                          $this->salida.="<select size = 1 name = 'responsable".$k.($e-1)."' class =\"select\">";
//                                          $opciones=$this->ProfesionalesDepartamento();
//
//                                          if(empty($_SESSION['LTRABAJOAPOYOD']['PROFESIONAL']))
//                                          {
//                                              $identificacion=$this->BuscaProfesionalResultado($examenes[numero_orden_id]);
//                                              if(sizeof($identificacion)<1){
//                                                  $identificacion=$this->BuscaProfesionalCumplimiento($examenes[numero_orden_id]);
//                                              }
//                                              $this->salida.="<option value ='-1' >--Seleccione-- </option>";
//                                              for($j=0;$j<sizeof($opciones);$j++){
//                                                  if($identificacion['usuario_id']==$opciones[$j][usuario_id]){
//                                                      $this->salida.="<option value = ".$opciones[$j][usuario_id]." selected>".$opciones[$j][nombre]."</option>";
//                                                  }else{
//                                                      $this->salida.="<option value = ".$opciones[$j][usuario_id]." >".$opciones[$j][nombre]."</option>";
//                                                  }
//                                              }
//                                          }else{
//                                              $identificacion=$this->BuscaProfesionalResultado($examenes[numero_orden_id]);
//                                              if(!empty($identificacion)){
//                                                  for($j=0;$j<sizeof($opciones);$j++){
//                                                      if($identificacion['usuario_id']==$opciones[$j][usuario_id]){
//                                                          $this->salida.="<option value = ".$opciones[$j][usuario_id]." selected>".$opciones[$j][nombre]."</option>";
//                                                      }
//                                                  }
//                                              }else{
//                                                  $identificacion=$this->BuscaProfesionalCumplimiento($examenes[numero_orden_id]);
//                                                  if(empty($identificacion)){
//                                                      $identificacion['usuario_id']=UserGetUID();
//                                                  }
//                                                  $this->salida.="<option value ='-1' >--Seleccione-- </option>";
//                                                  for($j=0;$j<sizeof($opciones);$j++){
//                                                      //if($identificacion['usuario_id']==$opciones[$j][usuario_id]){
//                                                          $this->salida.="<option value = ".$opciones[$j][usuario_id]." >".$opciones[$j][nombre]."</option>";
//                                                      //}
//      //                                              else{
//      //                                                  $this->salida.="<option value = ".$opciones[$j][usuario_id]." >".$opciones[$j][nombre]."</option>";
//      //                                              }
//                                                  }
//                                              }
//                                          }
//                                          $this->salida.="</select>";
//                                          $this->salida.="</td>";
//                                          $this->salida.="</tr>";
//                                          //Fin MauroB
//


                                    }//cierra el for

                                        //MauroB
                                            $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                                            $this->salida.="<td colspan = 1 align='left' class=".$this->SetStyle("responsable")." width='30%'>RESPONSABLE DIAGNOSTICO</td>";
                                            $this->salida.="<td colspan = 5 class=\"$estilo\" align=\"left\">";
                                            $this->salida.="<select size = 1 name = 'responsable".$k."' class =\"select\">";
                                            $opciones=$this->ProfesionalesDepartamento();

                                            if(empty($_SESSION['LTRABAJOAPOYOD']['PROFESIONAL']))
                                            {
                                                $identificacion=$this->BuscaProfesionalResultado($examenes[numero_orden_id]);
                                                if(sizeof($identificacion)<1){
                                                    $identificacion=$this->BuscaProfesionalCumplimiento($examenes[numero_orden_id]);
                                                }
                                                $this->salida.="<option value ='-1' >--Seleccione-- </option>";
                                                for($j=0;$j<sizeof($opciones);$j++){
                                                    if($identificacion['usuario_id']==$opciones[$j][usuario_id]){
                                                        $this->salida.="<option value = ".$opciones[$j][usuario_id]." selected>".$opciones[$j][nombre]."</option>";
                                                    }else{
                                                        $this->salida.="<option value = ".$opciones[$j][usuario_id]." >".$opciones[$j][nombre]."</option>";
                                                    }
                                                }
                                            }else{
                                                $identificacion=$this->BuscaProfesionalResultado($examenes[numero_orden_id]);
                                                if(!empty($identificacion)){
                                                    for($j=0;$j<sizeof($opciones);$j++){
                                                        if($identificacion['usuario_id']==$opciones[$j][usuario_id]){
                                                            $this->salida.="<option value = ".$opciones[$j][usuario_id]." selected>".$opciones[$j][nombre]."</option>";
                                                        }
                                                    }
                                                }else{
                                                    $identificacion=$this->BuscaProfesionalCumplimiento($examenes[numero_orden_id]);
                                                    if(empty($identificacion)){
                                                        $identificacion['usuario_id']=UserGetUID();
                                                    }
                                                    $this->salida.="<option value ='-1' >--Seleccione-- </option>";
                                                    for($j=0;$j<sizeof($opciones);$j++){
                                                        //if($identificacion['usuario_id']==$opciones[$j][usuario_id]){
                                                            $this->salida.="<option value = ".$opciones[$j][usuario_id]." >".$opciones[$j][nombre]."</option>";
                                                        //}
        //                                              else{
        //                                                  $this->salida.="<option value = ".$opciones[$j][usuario_id]." >".$opciones[$j][nombre]."</option>";
        //                                              }
                                                    }
                                                }
                                            }
                                            $this->salida.="</select>";
                                            $this->salida.="</td>";
                                            $this->salida.="</tr>";
                                            //Fin MauroB

                                    if (!empty($_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['observacion']))
                                    {
                                            $this->salida.="<tr>";
                                            $this->salida.="<td class=\"hc_table_submodulo_list_title\" colspan = \"1\" align=\"center\" width=\"35%\">OBSERVACION DEL PRESTADOR DEL SERVICIO</td>";
                                            $this->salida.="<td class=\"$estilo\" colspan = \"5\" align=\"center\" width=\"65%\"><textarea readonly style = \"width:82%\" class=\"textarea\" name = \"observacion\" cols=\"60\" rows=\"3\">".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['observacion']."</textarea></td>" ;
                                            $this->salida.="</tr>";
                                    }


                                    $this->salida.="</table>";
                                    $items = $e;
                                    $this->salida.="<input type=\"hidden\" name = \"items$k\"  value=\"$items\">";

                                    $this->salida.="<table align=\"center\" width=\"100%\" border=\"0\">";
                                    $this->salida.="<tr><td align=\"right\"><input class=\"input-submit\" name=\"insertar_resultado$k\" type=\"submit\" value=\"INSERTAR$k\"></td></tr>";
                                    $this->salida.="</table>";
                            }//fin del if que verifica si el examen tiene componentes.
                    }//fin del for de los apoyos
            }

            $_SESSION['DATOS_APD']['cantidad_datos']= $k;
      unset ($_SESSION['CONSTRUCTOR_REQUEST']);
            $this->salida.="<input type=\"hidden\" name = \"posicion\" id= \"posicion\">";
            $this->salida.="<input type=\"hidden\" name = \"opcion\" id= \"opcion\">";

            $this->salida.="<table align=\"center\" width=\"100%\" border=\"0\">";
            $this->salida.="<tr><td  align=\"center\"><input class=\"input-submit\" name=\"insertar_todos\" type=\"submit\" value=\"INSERTAR TODOS\"></td></tr>";
            $this->salida.="</table>";
            $this->salida.="</form>";
/*
            $this->salida.="<script language=\"JavaScript\">";
            $this->salida.="alert(\"aqui toy\");";
            $this->salida.="  document.getElementById('$_REQUEST[posicion]').focus();";


            $this->salida.="</script>";*/



            $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
            $this->salida.="<tr>";

            //BOTON DE VOLVER
            $accionV=ModuloGetURL('app','Os_Listas_Trabajo_Apoyod_Agrupado','user','BuscarOrden');
            $this->salida .= "<form name=\"forma\" action=\"$accionV\" method=\"post\">";
            $this->salida .= "<td  colspan = \"2\" align=\"center\"><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"VOLVER\"></form></td>";
            $this->salida .="</tr>";
            $this->salida .="</table>";
            $this->salida .= ThemeCerrarTabla();
            return true;
    }

    /**
    * Funcion que le permite al prestador del servicio agregarle a un resultado una
    * observacion.  esta funcion es utilizada por la captura grupal.
    * @return boolean
    */
    function frmForma_Observacion_Prestador_Servicio($tipo_id_paciente, $paciente_id, $nombre, $servicio, $numero_cumplimiento, $fecha_cumplimiento, $k)
    {
            $this->salida= ThemeAbrirTablaSubModulo('OBSERVACION DEL PRESTADOR DEL SERVICIO');
            $accion=ModuloGetURL('app','Os_Listas_Trabajo_Apoyod_Agrupado','user','GetForma',array('accion'=>'insertar_observacion_prestador_servicio', 'indice'=>$k, 'paciente_id'=>$paciente_id, 'tipo_id_paciente'=>$tipo_id_paciente, 'nombre'=>$nombre, 'servicio'=>$servicio, 'numero_cumplimiento' => $numero_cumplimiento, 'fecha_cumplimiento' =>$fecha_cumplimiento));
            $this->salida .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";

            $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
            $this->salida.= $this->SetStyle("MensajeError");
            $this->salida.="</table>";

            $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
            $this->salida.="<tr class=\"modulo_table_title\">";
            $this->salida.="  <td align=\"center\">ID DEL PACIENTE</td>";
            $this->salida.="  <td align=\"center\">NOMBRE DEL PACIENTE</td>";
            $this->salida.="</tr>";
            $this->salida.="<tr class=\"modulo_table_title\">";
            $this->salida.="  <td align=\"center\">".$tipo_id_paciente.": ".$paciente_id."</td>";
            $this->salida.="  <td align=\"center\">".$nombre."</td>";
            $this->salida.="</tr>";
            $this->salida.="</table>";

            //MauroB
            $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
            $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
            $this->salida.="  <td align=\"left\" width=\"15%\">FECHA DE CUMPLIMIENTO: </td>";
            $this->salida.="  <td align=\"left\" width=\"20%\">".$fecha_cumplimiento."</td>";
            $this->salida.="  <td align=\"left\" width=\"15%\">NUMERO DE CUMPLIMIENTO: </td>";
            $cumplimiento=$this->ConvierteCumplimiento($fecha_cumplimiento,$numero_cumplimiento,$_SESSION['LTRABAJOAPOYOD']['DPTO']);
            $this->salida.="  <td align=\"left\" width=\"20%\">".$cumplimiento."</td>";
            $this->salida.="</tr>";
            $this->salida.="</table><br>";
            //fin MauroB

            if( $i % 2){ $estilo='modulo_list_claro';}
            else {$estilo='modulo_list_oscuro';}

            $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
            $this->salida.="<tr class=\"modulo_table_title\">";
            $this->salida.="<td align=\"center\" width=\"15%\">".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['cargo']."</td>";
            $this->salida.="<td align=\"center\" width=\"65%\">".strtoupper($_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['titulo'])."</td>";
            $this->salida.="</tr>";
            $this->salida.="<tr class=\"$estilo\">";
            $this->salida.="<td align=\"center\" width=\"15%\">OBSERVACION DEL PRESTADOR DEL SERVICIO</td>";
            $this->salida.="<td align=\"center\" width=\"65%\"><textarea style = \"width:80%\" class=\"textarea\" name = \"observacion\" cols=\"60\" rows=\"5\">".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['observacion']."</textarea></td>" ;
            $this->salida.="</tr>";
            $this->salida.="<tr class=\"$estilo\">";
            $this->salida.="<td align=\"center\" colspan=\"2\"><input class=\"input-submit\" name=\"guardar\" type=\"submit\" value=\"GUARDAR\"></td>";
            $this->salida.="</tr>";
            $this->salida.="</table>";
            $this->salida.="</form>";

            //BOTON DE VOLVER
            $this->salida.="<table align=\"center\" border=\"0\"  width=\"80%\">";
            $this->salida.="<tr>";
            $accionV=ModuloGetURL('app','Os_Listas_Trabajo_Apoyod_Agrupado','user','GetForma', array('accion' => 'capturar_resultados', 'paciente_id'=>$paciente_id, 'tipo_id_paciente'=>$tipo_id_paciente, 'nombre'=>$nombre, 'servicio'=>$servicio, 'numero_cumplimiento' => $numero_cumplimiento, 'fecha_cumplimiento' =>$fecha_cumplimiento));
            $this->salida.="<form name=\"forma\" action=\"$accionV\" method=\"post\">";
            $this->salida.="<td colspan = \"2\" align=\"center\"><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"VOLVER\"></form></td>";
            $this->salida.="</tr>";
            $this->salida.="</table>";
            $this->salida.= ThemeCerrarTablaSubModulo();
            return true;
    }
}//fin clase

?>
