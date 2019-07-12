<?
/**
*		class app_EstacionEnfermeria_userclasses_HTML
*
*		Clase que maneja todas las funciones de vistas y consultas a la base de datos
*		relacionadas a la estaci&oacute;n de Enfermer&iacute;a
*		ubicacion => app_modules/EstacionEnfermeria/userclasses/app_EstacionEnfermeria_userclasses_HTML.php
*		fecha creaci&oacute;n => 04/05/2004 10:35 am
*
*		@Author Jairo Duvan Diaz Martinez ipsoft_sa.com
*		@version =>
*		@package SIIS
*/
class app_EstacionEnfermeriaControlA_userclasses_HTML extends app_EstacionEnfermeriaControlA_user
{

	/**
	*		app_EstacionEnfermeria_userclasses_HTML()
	*
	*		constructor
	*
	*		@Author jairo Duvan Diaz Martinez.
	*		@access Private
	*		@return boolean
	*/
		function app_EstacionEnfermeriaControlA_userclasses_HTML()
		{
			$this->app_EstacionEnfermeriaControlA_user(); //Constructor del padre 'modulo'
			$this->salida = "";
			return true;
		}

		function SetStyle($campo)
		{
					if ($this->frmError[$campo] || $campo=="MensajeError"){
						if ($campo=="MensajeError"){
							return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
						}
						return ("label_error");
					}
				return ("label");
		}


			function GetFrmFechaProgramacion($ingreso,$estacion,$datoscenso)
			{
				$_SESSION['CONTROLA']['ESTACIONX']=$estacion;
			 	$dats=$this->GetFechaProgramacion($ingreso);

       if($dats)
			 {
						$salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
						$salida.="<tr class=\"modulo_table_list_title\">";
						$salida.="  <td></td>";
						$salida.="  <td>FECHA</td>";
						$salida.="  <td>HORA</td>";
						$salida.="  <td><SUB>AYUNO</SUB></td>";
						$salida.="  <td>ACTIVIDAD</td>";
						$salida.="  <td></td>";
						$salida.="</tr>";
						$img="<img src=\"".GetThemePath()."/images/siguiente.png\" width=10 heigth=10>&nbsp;";

						for($i=0;$i<sizeof($dats);$i++)
						{
							$desc=$dats[$i][observacion];
							$fecha1=$dats[$i][fecha];
							if( $i % 2){ $estilo='modulo_list_claro';}
							else {$estilo='modulo_list_oscuro';}
							$salida.="<tr class=\"$estilo\" align=\"center\">";
							$fecha=explode(":",$fecha1);
							$fecha_completa=explode(" ",$fecha1);
							//echo $fecha_completa[0];
							if(strtotime($fecha[0]) < strtotime(date("Y-m-d H")))
							{
								$salida.="  <td width='5%'><img src=\"".GetThemePath()."/images/alarma.png\"></td>";


								if($fecha_completa[0] == date("Y-m-d")) {
									$fecha2=explode(" ",$fecha1);
									$fecha2[0] = "HOY";
								}
								elseif($fecha_completa[0] == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y")))){
									$fecha2=explode(" ",$fecha1);
									$fecha2[0] = "AYER ";
								}
								elseif($fecha_completa[0] == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")+1), date("Y")))){
									$fecha2=explode(" ",$fecha1);
									$fecha2[0] = "MAÑANA ";
								}
								else
								{
									$fecha2=explode(" ",$fecha1);
								}
								$salida.="  <td width='7%'><font color='#C04237'>$fecha2[0]</font></td>";
								$salida.="  <td width='7%'><font color='#C04237'>$fecha2[1]</font></td>";
							}
							if(strtotime($fecha[0]) == strtotime(date("Y-m-d H")))
							{

								if($fecha_completa[0] == date("Y-m-d")) {
									$fecha2=explode(" ",$fecha1);
									$fecha2[0] = "HOY";
								}
								elseif($fecha_completa[0] == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y")))){
									$fecha2=explode(" ",$fecha1);
									$fecha2[0] = "AYER ";
								}
								elseif($fecha_completa[0] == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")+1), date("Y")))){
									$fecha2=explode(" ",$fecha1);
									$fecha2[0] = "MAÑANA ";
								}
								else
								{
									$fecha2=explode(" ",$fecha1);
								}

								$salida.="  <td width='5%'><img src=\"".GetThemePath()."/images/alarma.png\"></td>";
								$salida.="  <td width='7%'><font color='#36C014'>$fecha2[0]</font></td>";
								$salida.="  <td width='7%'><font color='#36C014'>$fecha2[1]</font></td>";
							}
							if(strtotime($fecha[0]) > strtotime(date("Y-m-d H")))
							{

								if($fecha_completa[0] == date("Y-m-d")) {
									$fecha2=explode(" ",$fecha1);
									$fecha2[0] = "HOY";
								}
								elseif($fecha_completa[0] == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y")))){
									$fecha2=explode(" ",$fecha1);
									$fecha2[0] = "AYER ";
								}
								elseif($fecha_completa[0] == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")+1), date("Y")))){
									$fecha2=explode(" ",$fecha1);
									$fecha2[0] = "MAÑANA ";
								}
								else
								{
									$fecha2=explode(" ",$fecha1);
								}

								$salida.="  <td width='5%'><img src=\"".GetThemePath()."/images/fecha_fin.png\"></td>";
								$salida.="  <td width='7%'><font color='#002575'>$fecha2[0]</font></td>";
								$salida.="  <td width='7%'><font color='#002575'>$fecha2[1]</font></td>";
							}
							//$fila=str_replace(strtoupper($val),"<b>".strtoupper($val)."</b>",$fila);


							$count=$this->RevisarAyunoProgramacion($ingreso,$dats[$i][fecha]);
							if($count >0 AND $dats[$i][sw_ayuno]==1){$ayuno='checkS.gif';}else{$ayuno='checkN.gif';}
							$salida.="  <td width='2%' align=\"center\" ><img src=\"".GetThemePath()."/images/$ayuno\"></td>";



							$desc=str_replace("Observacion:","&nbsp;--&nbsp;Observacion :",$desc);
							$desc=str_replace("Actividad:","&nbsp;--&nbsp;Actividad:",$desc);
							$desc=str_replace("Actividad Glucometria:","$img<label class=label_mark>Actividad Glucometria :</label>",$desc);
							$desc=str_replace("Actividad Neurologico:","$img<label class=label_mark>Actividad Neurologico :</label>",$desc);


							$salida.="  <td width='90%' align=\"left\" >$desc</td>";
							if(UserGetUID()==0)
							{
								$salida.="  <td>Cumplir</td>";
							}
							else
							{
								$salida.="  <td><a href=".ModuloGetURL('app','EstacionEnfermeriaControlA','user','CumplirProgramacion',array("ingreso"=>$ingreso,"id"=>$dats[$i][hc_control_pend_id],'datos_estacion'=>$datoscenso)).">Cumplir</a></td>";
							}

							$salida.="</tr>";
						}
							$salida.="</table>";
				}
				return $salida;
			}





		function ControlesPacientes($estacion,$descripcion,$datoscenso)
		{

								$fecha=date("Y-m-d H:i:s");
								$this->salida .= ThemeAbrirTabla($descripcion." - [ ".$estacion['descripcion5']." ]");
								if(!empty($datoscenso))
								{
										//print_r($datoscenso);
										//$this->salida .= "<center>\n";
										$get_examen=$this->GetExamenes($datoscenso['ingreso']);
										$fech_pro=$this->GetFechaProgramacion($datoscenso['ingreso']);
												$url= ModuloGetURL('app','EstacionEnfermeriaControlA','user',"CallFrmSolicitudE",array("paciente"=>$datoscenso,"estacion"=>$estacion,"obs"=>$get_examen[$l]['descripcion']."Actividad:"));
												$this->salida .= "				<table class='modulo_table_title' border='0' width='100%'>\n";
												$this->salida .= "					<tr class='modulo_table_title'>\n";
												$this->salida .= "						<td>Paciente</td>\n";
												$this->salida .= "						<td>Habitacion</td>\n";
												$this->salida .= "						<td>Cama</td>\n";
												$this->salida .= "						<td>Estación</td>\n";
												//$this->salida .= "						<td>Acción</td>\n";
												$this->salida .= "					</tr>\n";
												$this->salida .= "					<tr class='modulo_list_oscuro'>\n";
												$this->salida .= "						<td>".$datoscenso['tipo_id_paciente']." ".$datoscenso['paciente_id']."&nbsp;&nbsp;&nbsp;".$datoscenso['NombrePaciente']."</td>\n";
												$this->salida .= "						<td>".$datoscenso['pieza']."</td>\n";
												$this->salida .= "						<td>".$datoscenso['cama']."</td>\n";
												$this->salida .= "						<td>".$estacion['descripcion5']."</td>\n";
												//$this->salida .= "						<td><a href='$url'>Programar</a></td>\n";
												$this->salida .= "					</tr>\n";
												$this->salida .= "				</table>\n";

												if(sizeof($get_examen) > 0 || sizeof($fech_pro) > 0)
												{
													$this->salida .= "<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
													$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$estacion));



													if($_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO'])
													{
														$href1 = ModuloGetURL($_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['contenedor'],
														$_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['modulo'],
														$_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['tipo'],
														$_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['metodo'],
														$_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['argumentos']);
														$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href1."'>Volver al Menu</a><br><br>\n";
													}
													else
													{

															if(UserGetUID()==0)
															{
																$href1 = ModuloGetURL('app','EstacionEnfermeriaPlantilla','user','CallMenu',array("estacion"=>$estacion));
																$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href1."'>Volver al Menu</a><br><br>\n";
															}
															else
															{
																$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu</a><br><br>\n";
															}
													}
													$this->salida .= "</table>\n";
												}

									$img = "<img src=\"".GetThemePath()."/images/EstacionEnfermeria/alarma.png\" border=0 width=12 heigth=12>";
									$this->salida .= "<table align='center' width=\"100%\"  border=\"0\" class=\"modulo_table_list\">\n";

                  if(UserGetUID()==0)
									{
										$this->salida .= "					<tr class=\"modulo_list_claro\" align=\"center\"><td>$img PROGRAMAR ACTIVIDAD\n";
									}
									else
									{
										$this->salida .= "					<tr class=\"modulo_list_claro\" align=\"center\"><td>$img <a href='$url'>PROGRAMAR ACTIVIDAD</a>\n";
									}
									$this->salida .= "					</td></tr>\n";
									$this->salida .= "</table>\n";

									$this->salida .= "<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list\">\n";
									$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
								//	$this->salida .= "		<td width='5%'>HABITACION</td>\n";
								//	$this->salida .= "		<td width='5%'>CAMA</td>\n";
								//	$this->salida .= "		<td width='30%'>PACIENTE</td>\n";
									//$this->salida .= "		<td colspan='4' width='60%'>ACCION</td>\n";
									$this->salida .= "	</tr>\n";
									//print_r($datoscenso['hospitalizacion']);
										$get_examen=$this->GetExamenes($datoscenso['ingreso']);
										$this->salida .= "<tr ".$this->Lista($i).">\n";
									//	$this->salida .= "	<td ";
									/*	if(!empty($get_examen))
										{
											//$this->salida .= "rowspan='2'";
										}*/
									//	$this->salida .= " align=\"center\">".$datoscenso['pieza']."</td>\n";
										//$this->salida .= "<td ";
										/*if(!empty($get_examen))
										{
											//$this->salida .= "rowspan='2'";
										}*/
									//	$this->salida .= " align=\"center\">".$datoscenso['cama']."</td>\n";
								//		$this->salida .= "<td ";
									/*	if(!empty($get_examen))
										{
											//$this->salida .= "rowspan='2'";
										}*/
										$url= ModuloGetURL('app','EstacionEnfermeriaControlA','user',"CallFrmSolicitudE",array("paciente"=>$datoscenso,"estacion"=>$estacion,"obs"=>$get_examen[$l]['descripcion']."Actividad:"));
								//		$this->salida .= "align=\"center\"><label class=label_mark> ".$datoscenso['tipo_id_paciente']." ".$datoscenso['paciente_id']."</label><br>".$datoscenso['NombrePaciente']."<br>"."<a href='$url'>Programar</a></td>\n";
         						$i_label='<label class=label_mark>';
										$f_label='</label>';

										if(!empty($get_examen))
										{
												$var_add="";
												$var_add1="";
												for($l=0; $l<sizeof($get_examen); $l++)
												{
														$var_add .= "<tr ".$this->Lista($l)." >";
														$var_add .= "<td>";
														$var_adde="<td>";
														$var_ex="<td>";
														$var_aut="<td>";
														$arr=explode(".",$get_examen[$l]['fecha']);
														$fecha_hora=explode(" ",$arr[0]);
														$var_add.=$fecha_hora[0]."&nbsp;".$fecha_hora[1];
														$var_adde.=$get_examen[$l]['descripcion'];
														//$var_ex.=ucwords(strtolower(substr($get_examen[$l]['des'],0,83)))."...";
														$var_ex.=ucwords(strtolower($get_examen[$l]['des']));
														if($get_examen[$l]['sw_estado']=='0')
														{
															$var_aut.=$i_label .'Autorizado'.$f_label;
														}
														else
														{
															$var_aut.='No Autorizado';
														}

														$var_add .= "</td>";
														$var_adde.= "</td>";
														$var_ex.= "</td>";
														$var_aut.="</td>";
														$var_add.=$var_adde;
														$var_add.=$var_ex;
														$var_add.=$var_aut;
														$var_add.="</tr>";
												}
												$var_add1 .= "<table border=\"1\" width='100%'>";
												$var_add1.="<tr class='modulo_table_title'>";
												$var_add1.="<td>";
												$var_add1.="Fecha";
												$var_add1.="</td>";
												$var_add1.="<td>";
												$var_add1.="Solicitud";
												$var_add1.="</td>";
												$var_add1.="<td align='center'>";
												$var_add1.="Examen";
												$var_add1.="</td>";
												$var_add1.="<td>";
												$var_add1.="Autorizacion";
												$var_add1.="</td>";
												$var_add1.="</tr>";
												$var_add1.=$var_add;
												$var_add1 .= "</table>";
												$this->salida.="<td colspan='4'>".$this->GetFrmFechaProgramacion($datoscenso['ingreso'],$estacion,$datoscenso)."</td></tr><tr ".$this->Lista($i).">";
												$this->salida .= "<td align=\"left\" colspan='4'>$var_add1</td>\n";
												unset($var_add);
												unset($var_adde);
												unset($var_aut);
												unset($f_label);
												unset($i_label);
												unset($var_ex);

										}
										else
										{		//aqui mostramos solamente si hay una programacion...
												$this->salida.="<td colspan='4'>".$this->GetFrmFechaProgramacion($datoscenso['ingreso'],$estacion,$datoscenso)."</td></tr><tr ".$this->Lista($i).">";
												//$this->salida .= "<td align=\"left\" colspan='7'></td>\n";
										}

										/***************************/
										$this->salida .= "</td></tr>\n";
									//End for
									$this->salida .= "</table><br>\n";
									//$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$estacion));

					if($_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO'])
					{
						$href = ModuloGetURL($_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['contenedor'],
						$_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['modulo'],
						$_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['tipo'],
						$_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['metodo'],
						$_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['argumentos']);
					}
					else
					{
									if(UserGetUID()==0)
									{
										$href = ModuloGetURL('app','EstacionEnfermeriaPlantilla','user','CallMenu',array("estacion"=>$estacion));
									}
									else
									{
										$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$estacion));
									}
					}
									$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu</a><br>\n";
									$this->salida .= themeCerrarTabla();
									return true;
								}
								else {
									$mensaje = "LA ESTACI&Oacute;N [ ".$estacion['descripcion5']." ] NO CUENTA CON PACIENTES.";
									$titulo = "MENSAJE";
									$boton = "REGRESAR";

									if($_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO'])
									{
										$href = ModuloGetURL($_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['contenedor'],
										$_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['modulo'],
										$_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['tipo'],
										$_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['metodo'],
										$_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['argumentos']);
									}
									else
									{

											if(UserGetUID()==0)
											{
												$href = ModuloGetURL('app','EstacionEnfermeriaPlantilla','user','CallMenu',array("estacion"=>$estacion));
											}
											else
											{
												$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$estacion));
											}
									}
									$this->FormaMensaje($mensaje,$titulo,$href,$boton);
									return true;
							}
		}



		function FrmSolicitudE($paciente,$estacion)
		{

			//print_r($paciente);
			$mostrar ="\n<script language='javascript'>\n";
			$mostrar.="  function borrado(nom){\n";
			$mostrar.="  document.formabuscar.obs.value=''\n";
   		$mostrar.="  };\n";
			$mostrar .="\n</script>\n";
			$this->salida.= ThemeAbrirTabla('SOLICITUD DE EXAMENES');
			$accionT = ModuloGetURL('app','EstacionEnfermeriaControlA','user','InsertarControlE',array("control_descripcion"=>"CONTROLES DE APOYOS DIAGNOSTICOS PENDIENTES","estacion"=>$estacion,"paciente"=>$paciente));
			$this->salida.=$mostrar;
			$this->salida .= "           <form name=\"formabuscar\" action=\"$accionT\" method=\"post\">";
			$this->salida .= "<br><table  border=\"0\" class=modulo_table_title width=\"80%\" align=\"center\" >";
			$this->salida .= " <tr class=\"modulo_table_title\">";
			$this->salida .= " <td>PACIENTE</td>";
			$this->salida .= " <td>HABITACION</td>";
			$this->salida .= " <td>CAMA</td>";
			$this->salida .= " </tr>";
			$this->salida .= " <tr align=\"center\">";
			$this->salida .= " <td class=\"modulo_list_claro\" >".$paciente['NombrePaciente']."</td>";
			$this->salida .= " <td class=\"modulo_list_claro\">".$paciente['pieza']."</td>";
			$this->salida .= " <td class=\"modulo_list_claro\" >".$paciente['cama']."</td>";
			$this->salida .= " </tr>";
			$this->salida .= " </table>";
			$this->salida .= "<table  width=\"80%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= " </table>";

			if(empty($_REQUEST['fech'])){$_REQUEST['fech']=date("d-m-Y");}
			$this->salida .= "<br><table  border=\"1\" class=\"modulo_table_list\" width=\"80%\" align=\"center\" >";
			$this->salida .= "				       <tr  class=\"modulo_list_claro\"><td align=\"left\" class=\"".$this->SetStyle("fechac")."\">FECHA: </td><td align=\"left\"><input type=\"text\" class=\"input-text\" name=\"fech\" size='11' maxlength=\"10\" value=\"".$_REQUEST['fech']."\">".ReturnOpenCalendario('formabuscar','fech','-')."</td>";
			$this->salida .= " <td>HORA/MINUTO</td>";
			$this->salida .= " <td><select name=\"hora\" class=\"select\">";
			for($i=0;$i<24;$i++)
			{
        if($i<10){$a=0;}else{$a='';}
				if($a.$i==$_REQUEST['hora'])
				{
					$this->salida .=" <option value=$a$i selected>$a$i</option>";
				}
				else
				{
					$this->salida .=" <option value=$a$i>$a$i</option>";
				}
			}
			$this->salida .= "  </select>";
			$this->salida .= " &nbsp;&nbsp;<select name=\"min\" class=\"select\">";
			for($i=0;$i<60;$i++)
			{
				if($i<10){$a=0;}else{$a='';}
				if($a.$i==$_REQUEST['min'])
				{
					$this->salida .=" <option value=$a$i selected>$a$i</option>";
				}
				else
				{
					$this->salida .=" <option value=$a$i>$a$i</option>";
				}
			}
			$this->salida .= "  </select></td>";

			if($_REQUEST['ayuno']==on)
			{	$check='checked';	}else{$check='';	}

			$this->salida .= " <td align='center'><label class='label_mark'>AYUNO</label>&nbsp;<input type='checkbox' name='ayuno' $check></td>";

			$this->salida .= " </tr>";
			$this->salida .= " <tr>";
			$this->salida .= " <td colspan='4'>ACTIVIDAD:&nbsp;<textarea style=width:100% name=obs cols='30' rows='20'>".$_REQUEST['obs']."</textarea></td>";
			$this->salida .= " <td align='center'><input type='submit'class=\"input-submit\" name='guarda' value='Guardar' ></form>&nbsp;&nbsp;<input type='button'class=\"input-submit\" name='borrar' value='Borrar' onclick=borrado(this.name)></td>";
			$this->salida .= " </tr>";
			$this->salida .= " </table>";

			$href = ModuloGetURL('app','EstacionEnfermeriaControlA','user','CallControlesPacientes',array("datos_estacion"=>$paciente,"estacion"=>$estacion));
			$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Regresar</a><br>\n";
			$this->salida.= ThemeCerrarTabla();
			return true;
		}


			/*
		*		Lista($numero)
		*		$numero es el numero para imprimir la clase de la lista, si el numero es par imprime la clase list_claro
		*		de lo contrario imprime list_oscuro
		*		retorna la cadena con la clase a utilizar
		*
		*		@Author Arley Velasquez
		*		@access Private
		*		@param integer
		*/
		function Lista($numero)
		{
			if ($numero%2)
				return ("class='modulo_list_oscuro'");
			return ("class='modulo_list_claro'");
		}//End lISTA


/**********************esta va para estacionE_ControlPaciente******************************/
	/**
	*		FormaMensaje => muestra mensajes al usuario
	*
	*		@Author Rosa Maria Angel
	*		@access Private
	*		@param string => mensaje a mostrar
	*		@param string => titulo de la tabla
	*		@param string => action del form
	*		@param string => value del input-submit
	*		@return boolean
	*/
	function FormaMensaje($mensaje,$titulo,$accion,$boton)
	{
		$this->salida .= ThemeAbrirTabla($titulo)."<br>";
		$this->salida .= "<table width=\"60%\" align=\"center\" class=\"normal_10\" border='0'>\n";
		$this->salida .= "	<form name=\"formaMensaje\" action=\"$accion\" method=\"post\">\n";
		$this->salida .= "		<tr><td colspan=\"2\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>\n";
		if(!empty($boton)){
			$this->salida .= "	<tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"$boton\"></td></tr>\n";
		}
		else{
			$this->salida .= "	<tr><td colspan=\"2\" align=\"center\">&nbsp;</td></tr>\n";
		}
		$this->salida .= "	</form>\n";
		$this->salida .= "</table>\n";
		$this->salida .= themeCerrarTabla();
		return true;
	}//fin FormaMensaje
//----------------------------------------------------------------------------------

}//fin class
?>
