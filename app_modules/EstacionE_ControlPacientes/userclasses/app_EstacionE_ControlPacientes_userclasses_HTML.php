
<?php

/**
 * $Id: app_EstacionE_ControlPacientes_userclasses_HTML.php,v 1.46 2005/11/24 15:35:30 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo de Estacion de Enfermeria (parte de controles del paciente) 
 */



/**
* Modulo de EstacionE_Pacientes (PHP).
*
//*
*
* @author  <@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* app_EstacionE_Pacientes_userclasses_HTML.php
*
//*
**/

class app_EstacionE_ControlPacientes_userclasses_HTML extends app_EstacionE_ControlPacientes_user
{
	function app_EstacionE_ControlPacientes_HTML()
	{
		$this->app_EstacionE_ControlPacientes_user(); //Constructor del padre 'modulo'
		$this->salida='';
		return true;
	}

/*//Funci?n principal que da las opciones para tener acceso a los datos de SOAT
	function PrincipalCartera2()//Llama a todas las opciones posibles
	{
		UNSET($_SESSION['carter']);
		if($this->UsuariosCartera()==false)
		{
			return false;
		}
		return true;
	}*/


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

/**********************esta va para estacionE_ControlPaciente******************************/




   /*******************OJO QUE ESTO VA EN EL MODULO DE ESTACIONE_CONTROLPACIENTE******************/

		/*
		*		AgendaControlesXhoras
		*
		*		Muestra los diferentes controles que estan pendientes por tomar agrupados por horas
		*
		*		@Author Rosa Maria Angel
		*		@access Private
		*		@param array datos de la estacion
		*		@return bool
		*/
		function AgendaControlesXhoras($estacion)
		{
			$hora_inicio_turno=ModuloGetVar('app','EstacionEnfermeria','HoraInicioTurnoControles');
			$rango_turno=ModuloGetVar('app','EstacionEnfermeria','RangoVerTurnosControles');

			list($hh,$mm, $ss) = explode(" ",$hora_inicio_turno);
			$NextDay = date("Y-m-d H:i:s", mktime(($hh+($rango_turno)), ($mm-1), $ss, date("m"),(date("d")),date("Y")));
			$vectorAgenda = $this->GetAgendaPorHoras($estacion[estacion_id],date("Y-m-d $hora_inicio_turno"),$NextDay);

			if(!$vectorAgenda){
				return false;
			}
			elseif($vectorAgenda === "ShowMensaje")
			{
				$mensaje = "NO SE ENCONTRARON CONTROLES PENDIENTES";
				$titulo = "MENSAJE";
				$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$estacion));
				$boton = "REGRESAR";
				$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
				return true;
			}
			if($vectorAgenda)
			{
				$this->salida .= ThemeAbrirTabla("AGENDA CONTROLES");

				$this->salida .= " <table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= " <table>";

				$this->salida .= " <table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
				$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "		<td width='20%'>FECHA</td>\n";
				$this->salida .= "		<td>PACIENTE</td>\n";
				$this->salida .= "		<td>CONTROL</td>\n";
				$this->salida .= "	</tr>\n";
			}
			foreach($vectorAgenda as $key => $values)
			{
				$rowSpanFecha = sizeof($values);
				foreach ($values as $A => $B)
				{
					foreach($B as $X=>$Y)
					{
						$this->salida .= "	<tr ".$this->Lista($i).">\n";
						if($rowSpanFecha == sizeof($values))
						{
							list($date,$time) = explode (" ",$key);
							if($date == date("Y-m-d")) {
								$fecha = "HOY ".$time;
							}
							elseif($date == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")+1), date("Y")))){
								$fecha = "MA?ANA ".$time;
							}
							else {
								$fecha = $key;
							}
							$this->salida .= "		<td rowspan='".$rowSpanFecha."' align='center'>$fecha</td>\n";
						}
						$this->salida .= "		<td>".$Y[0][primer_nombre]." ".$Y[0][segundo_nombre]." ".$Y[0][primer_apellido]." ".$Y[0][segundo_apellido]."</td>\n";
						$datos_estacion['NombrePaciente'] = $Y[0][primer_nombre]." ".$Y[0][segundo_nombre]." ".$Y[0][primer_apellido]." ".$Y[0][segundo_apellido];
						$datos_estacion['ingreso'] = $Y[0][ingreso];
						$datos_estacion['Hora'] = $key;
						$datos_estacion['pieza'] = $Y[0][pieza];
						$datos_estacion['cama'] = $Y[0][cama];
						$datos_estacion['control_id'] = $Y[0][control_id];

						switch ($Y[0][control_id])
						{
							case 6: $href =  ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmIngresarDatosLiquidos',array("referer_parameters"=>array("estacion"=>$estacion),"referer_name"=>"AgendaControlesXhoras","estacion"=>$estacion,"datos_estacion"=>$datos_estacion));
							break;

							case 8: $href =  ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmIngresarDatosGlucometria',array("referer_parameters"=>array("estacion"=>$estacion),"referer_name"=>"AgendaControlesXhoras","estacion"=>$estacion,"datos_estacion"=>$datos_estacion));
							break;

							case 10: $href =  ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmControlNeurologico',array("referer_parameters"=>array("estacion"=>$estacion),"referer_name"=>"AgendaControlesXhoras","estacion"=>$estacion,"datos_estacion"=>$datos_estacion));
							break;

							default : $href = "";
							 //default:$href =  ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmIngresarDatosGlucometria',array("referer_parameters"=>array("estacion"=>$estacion),"referer_name"=>"AgendaControlesXhoras","estacion"=>$estacion,"datos_estacion"=>$datos_estacion));

							break;
						}
						$this->salida .= "		<td align='center'><a href=\"".$href."\">".$Y[0][descripcion]."</a></td>\n";
						$this->salida .= "	</tr>\n";
						$rowSpanFecha--;
					}
				}
				$i++;
			}
			$this->salida .= "</table>\n";
			$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$estacion));
			$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br><br>";
			$this->salida .= themeCerrarTabla();

			return true;
		}


/*******************OJO QUE ESTO VA EN EL MODULO DE ESTACIONE_CONTROLPACIENTE******************/


/*funcion que debe estar en el mod estacione_controlpaciente*/
		/*
		*
		*
		*		@Author Jairo Duvan Diaz Martinez
		*		@access Private
		*		@return bool
		*/
		function ListRevisionPorSistemas($estacion)
		{



			$mostrar ="\n<script language='javascript'>\n";
			$mostrar.="function mOvr(src,clrOver) {;\n";
			$mostrar.="src.style.background = clrOver;\n";
			$mostrar.="}\n";

			$mostrar.="function mOut(src,clrIn) {\n";
			$mostrar.="src.style.background = clrIn;\n";
			$mostrar.="}\n";
			$mostrar.="</script>\n";
			$this->salida .="$mostrar";

			unset($_SESSION['CONTEO']);

		  if(empty($estacion)){$estacion=$_REQUEST['estacion'];}
			$_SESSION['HISTORIACLINICA']['RETORNO']['modulo']='EstacionEnfermeria';
			$_SESSION['HISTORIACLINICA']['RETORNO']['metodo']='CallMenu';
			$_SESSION['HISTORIACLINICA']['RETORNO']['tipo']='user';
			$_SESSION['HISTORIACLINICA']['RETORNO']['contenedor']='app';
			$_SESSION['HISTORIACLINICA']['DATOS']['ESTACION']=$estacion;

			//AQUI ES PARA COMUNICARSE CON LA CENTRA DE IMPRESION DE ORDENES DE DAR.
			$_SESSION['CENTRALHOSP']['RETORNO']['modulo']='EstacionEnfermeria';
			$_SESSION['CENTRALHOSP']['RETORNO']['metodo']='CallMenu';
			$_SESSION['CENTRALHOSP']['RETORNO']['tipo']='user';
			$_SESSION['CENTRALHOSP']['RETORNO']['contenedor']='app';
			$_SESSION['CENTRALHOSP']['RETORNO']['argumentos']=array('estacion'=>$estacion);
			unset($_SESSION['ESTACION']['VECT']);
			//$datoscenso = $this->CallMetodoExterno('app','Censo','user','GetCensoTipo1',array('estacion'=>$estacion['estacion_id']));
				$datoscenso=$this->GetPacientesControles($estacion['estacion_id']);
   /*if($datoscenso === "ShowMensaje")
			{
				$mensaje = "LA ESTACI&Oacute;N [ ".$estacion['descripcion5']." ] NO CUENTA CON PACIENTES.";
				$titulo = "ALERTA DEL SISTEMA";
				$boton = "SELECCIONAR ESTACION";
				$controles=$this->GetControles($datoscenso['hospitalizacion'][$i]['ingreso']);
				$href=ModuloGetURL('app','EstacionEnfermeria','user','');
				$this->FormaMensaje($mensaje,$titulo,$href,$boton);
				return true;
			}*/
			if($datoscenso=== "ShowMensaje")
			{
				$datoscenso='';//esto es para que entre al if
			}
			$reporte = new GetReports(); //inicializamos el reporte de sv
			if(!empty($datoscenso))
			{
				//$this->salida .= ThemeAbrirTabla("NOTAS DE ENFERMERIA - [ ".$estacion['descripcion5']." ]");
				$w=$x=0;
				foreach($datoscenso as $key => $value)
				{
					if($key == "hospitalizacion")
					{
						$this->salida .= "<br><table align=\"center\" width=\"100%\"  border=\"0\" >\n";
						$this->salida .= "	<tr class=\"modulo_table_title\"><td colspan='13' height='30'>PACIENTES EN HOSPITALIZACI&Oacute;N</td></tr>\n";
						$this->salida .= "	<tr class=\"modulo_table_title\">\n";
						$this->salida .= "		<td></td>\n";
						$this->salida .= "		<td><sub>HAB.</sub></td>\n";
						$this->salida .= "		<td><sub>CAMA</sub></td>\n";
						$this->salida .= "		<td><sub>TIEMPO<BR>HOSP.</sub></td>\n";
						$this->salida .= "		<td><sub>PACIENTE DE ESTACI?N</sub></td>\n";
						$this->salida .= "		<td><sub>SIGNOS<BR>VITALES</sub></td>\n";
						$this->salida .= "		<td><sub>MED.<BR>PACIENTES</sub></td>\n";
						$this->salida .= "		<td><sub>CTRL<BR>PROGRAMADOS</sub></td>\n";
					//	$this->salida .= "		<td>TRANFUSIONES</td>\n";
						$this->salida .= "		<td><sub>PROGR.<BR>APOYO</sub></td>\n";

						//glucometria
						$this->salida .= "		<td><sub>GLUCO<BR>METRIA</sub></td>\n";
						//neurologico
						$this->salida .= "		<td><sub>NEURO<BR>LOGICO</sub></td>\n";


						$this->salida .= "		<td><sub>HISTORIA<BR> CLINICA</sub></td>\n";
						//$this->salida .= "		<td>NOTAS<BR> ENFERMERIA</td>\n";
						$this->salida .= "		<td><sub>ORDEN<BR>SERVICIOS</sub></td>\n";
						$this->salida .= "	</tr>\n";

						//mostramos los pacientes pendientes por ingresar .. si hay
						if($w==0)
						{
							$pacientes = $this->GetPacientesPendientesXHospitalizar($estacion);
							if(is_array($pacientes))
							{
								$this->Pacientes_X_Ingresar($estacion,$reporte,$pacientes);
							}
							$w=1;
						}


						foreach($value as $A => $B)
						{

						  				$info=$this->RevisarSi_Es_Egresado($B[ingreso_dpto_id]);
											$cirugia = $this->VerificacionPaciente_ECirugia($B[numerodecuenta]);

											$backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');

											if($p++ % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
											$this->salida .= "<tr class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>\n";
											//$this->salida .= "	<td  align=\"center\"><a href='$urla'><img src=\"". GetThemePath() ."/images/honorarios.png\" border='0'></a></td>\n";

											//info nos dice si el egreso es 1 o 2 o 0 para asi colocar el estado del egreso.
											$linker=ModuloGetURL('app','EstacionE_Pacientes','user','CallListPacientesPorEgresar',array("datos_estacion"=>$estacion,"paciente_id"=>$B['paciente_id'],"tipo_id_paciente"=>$B['tipo_id_paciente'],"cama"=>$B['cama']));
											if($info[1]==2)//si es 2 egreso efectuado
											{
												$_SESSION['ESTACION_ENF']['CONTEO']['EGRESO']=$_SESSION['ESTACION_ENF']['CONTEO']['EGRESO']+ 1;
												$this->salida .= "	<td  align=\"center\"><img src=\"". GetThemePath() ."/images/egresook.png\" border='0'></td>\n";
											}
											elseif($info[1]=='1' OR $info[1]=='0')//es 1 enfermera-0 medico
											{
												$_SESSION['ESTACION_ENF']['CONTEO']['EGRESO']=$_SESSION['ESTACION_ENF']['CONTEO']['EGRESO']+ 1;
												$this->salida .= "	<td  align=\"center\"><a href='$linker'><img src=\"". GetThemePath() ."/images/egreso.png\" border='0'></a></td>\n";
											}
											else
											{
												$_SESSION['ESTACION_ENF']['CONTEO']['HOSP']=$_SESSION['ESTACION_ENF']['CONTEO']['HOSP']+ 1;
												$this->salida .= "	<td  align=\"center\"><img src=\"". GetThemePath() ."/images/honorarios.png\" border='0'></td>\n";
											}
											//unset($info);
											$this->salida .= "	<td align=\"center\">".$B[pieza]."</td>\n";
											$this->salida .= "	<td align=\"center\">".$B[cama]."</td>\n";unset($diasHospitalizacion);
											$diasHospitalizacion = $this->GetDiasHospitalizacion($B[fecha_ingreso]);
											$this->salida .= "	<td align=\"center\">".$diasHospitalizacion."</td>\n";
											$linkVerDatos = ModuloGetURL('app','EstacionE_Pacientes','user','CallMostrarDatosIngreso',array("ingresoID"=>$B['ingreso'],"retorno"=>"CallMenu","modulito"=>'EstacionEnfermeria',"datos_estacion"=>$estacion));

											$this->salida .= "	<td><a href='$linkVerDatos'>".$B[primer_nombre]." ".$B[segundo_nombre]." ".$B[primer_apellido]." ".$B[segundo_apellido]."</a></td>\n";

											if(empty($cirugia))
											{
											//SIGNOS VITALES
											$this->salida .= $reporte->GetJavaReport('app','EstacionE_ControlPacientes','SignosVitales',
											array('estacion'=>$estacion,'datos_estacion'=>array("pieza"=>$B['pieza'],"cama"=>$B['cama'],"NombrePaciente"=>$B['primer_nombre']." ".$B['segundo_nombre']." ".$B['primer_apellido']." ".$B['segundo_apellido'],"paciente_id"=>$B['paciente_id'],"tipo_id_paciente"=>$B['tipo_id_paciente'],"Hora"=>$tiempo,"ingreso"=>$B['ingreso'],"control_id"=>15,"control_descripcion"=>"CONTROL DE SIGNOS VITALES")
											),array('rpt_dir'=>'cache','rpt_name'=>'signo'.$B['ingreso'],'rpt_rewrite'=>TRUE));
											$funcion=$reporte->GetJavaFunction();


											$tiempo=date("Y-m-d H:i:s",mktime(date("H"),date("i"),0,date("m"),date("d"),date("Y")));
											$urls = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmSignosVitales',array("estacion"=>$estacion,"datos_estacion"=>array("pieza"=>$B['pieza'],"cama"=>$B['cama'],"NombrePaciente"=>$B['primer_nombre']." ".$B['segundo_nombre']." ".$B['primer_apellido']." ".$B['segundo_apellido'],"paciente_id"=>$B['paciente_id'],"tipo_id_paciente"=>$B['tipo_id_paciente'],"Hora"=>$tiempo,"ingreso"=>$B['ingreso'],"control_id"=>15,"control_descripcion"=>"CONTROL DE SIGNOS VITALES")));
											$this->salida .= "	<td align=\"center\"><a href='$urls'><img src=\"". GetThemePath() ."/images/traslado.png\" border='0'></a><a href='javascript:$funcion'>&nbsp;PDF</a></td>\n";


											//Esta Informacion es para el reporte de plantilla de signos vitales.
											$_SESSION['ESTACION']['VECT'][]=array("pieza"=>$B['pieza'],"cama"=>$B['cama'],"NombrePaciente"=>$B['primer_nombre']." ".$B['segundo_nombre']." ".$B['primer_apellido']." ".$B['segundo_apellido'],"paciente_id"=>$B['paciente_id'],"tipo_id_paciente"=>$B['tipo_id_paciente'],"Hora"=>$tiempo,"ingreso"=>$B['ingreso'],"control_id"=>15,"control_descripcion"=>"CONTROL DE SIGNOS VITALES");



											//MEDICAMENTOS
											$medicamento=$this->GetPacMedicamentosPorSolicitar($B['ingreso']);
											if($medicamento==1)
											{$imgM="pparamedin.png";}else{$imgM="pparamed.png";}
											$urla = ModuloGetURL('app','EstacionE_Medicamentos','user','CallFrmMedicamentos',array("estacion"=>$estacion,"datos_estacion"=>array("pieza"=>$B['pieza'],"cama"=>$B['cama'],"NombrePaciente"=>$B['primer_nombre']." ".$B['segundo_nombre']." ".$B['primer_apellido']." ".$B['segundo_apellido'],"paciente_id"=>$B['paciente_id'],"tipo_id_paciente"=>$B['tipo_id_paciente'],"Hora"=>$tiempo,"ingreso"=>$B['ingreso'],"control_id"=>2,"control_descripcion"=>"CONTROL MEDICAMENTOS PACIENTE")));
											$this->salida .= "	<td align=\"center\"><a href='$urla'><img src=\"". GetThemePath() ."/images/$imgM\" border='0'>&nbsp;MP</a></td>\n";


											$conteop=$this->CountControles($B['ingreso']);
											//REVISAR CONTROLES PROGRAMADOS
											$nombre=$B[primer_nombre]." ".$B[segundo_nombre]." ".$B[primer_apellido]." ".$B[segundo_apellido];
											$url = ModuloGetURL('app','EstacionE_ControlPacientes','user','Listado_controles',array("estacion"=>$estacion,"ingreso"=>$B[ingreso],"nombre"=>urlencode($nombre),"pieza"=>$B['pieza'],"cama"=>$B['cama']));

											if($conteop==1)
											{
												$imgp="resultado.png";
												$this->salida .= "	<td align=\"center\"><a href='$url'><img src=\"". GetThemePath() ."/images/$imgp\" border='0'>&nbsp;CP</a></td>\n";
											}else
											{
												$imgp="prangos.png";
												$this->salida .= "	<td align=\"center\"><img src=\"". GetThemePath() ."/images/$imgp\" border='0'>&nbsp;CP</td>\n";
											}
												unset($conteop);
											//TRANSFUSIONES
										//	$urlt = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmTransfusiones',array("estacion"=>$estacion,"datos_estacion"=>array("pieza"=>$B['pieza'],"cama"=>$B['cama'],"NombrePaciente"=>$B['primer_nombre']." ".$B['segundo_nombre']." ".$B['primer_apellido']." ".$B['segundo_apellido'],"paciente_id"=>$B['paciente_id'],"tipo_id_paciente"=>$B['tipo_id_paciente'],"ingreso"=>$B['ingreso'],"control_id"=>24,"control_descripcion"=>'CONTROL DE TRANSFUSIONES')));
										//	$this->salida .= "	<td align=\"center\"><a href='$urlt'><img src=\"". GetThemePath() ."/images/pparamedin.png\" border='0'>&nbsp;TR</a></td>\n";

											$centinela=0;
											//Traemos las fechas de los apoyos diagnosticos pendientes.
											$fech_apoyo=$this->GetFechasHcApoyos($B['ingreso']);
											for($max=0;$max < sizeof($fech_apoyo);$max++)
											{
												if(strtotime($fech_apoyo[$max][fecha]) <= strtotime(date("y-m-d H:i:s")))
												{ $centinela=1; break;}
												$centinela=0;
											}

											if($centinela==1)
											{
													$urlAP = ModuloGetURL('app','EstacionEnfermeriaControlA','user','CallControlesPacientes',array("estacion"=>$estacion,"datos_estacion"=>array("pieza"=>$B['pieza'],"cama"=>$B['cama'],"NombrePaciente"=>$B['primer_nombre']." ".$B['segundo_nombre']." ".$B['primer_apellido']." ".$B['segundo_apellido'],"paciente_id"=>$B['paciente_id'],"tipo_id_paciente"=>$B['tipo_id_paciente'],"ingreso"=>$B['ingreso'],"control_descripcion"=>'CONTROLES DE APOYOS DIAGNOSTICOS PENDIENTES')));
													$img='alarma.png';
													$this->salida .= "	<td align=\"center\"><a href='$urlAP'><img src=\"". GetThemePath() ."/images/$img\" border='0'>&nbsp;AD</a></td>\n";
											}
											else
											{
													//PROGRAMACION DE APOYOS DIAGNOSTICOS PENDIENTES.....
													//$enlaceProgramacion = "<a href=\"".ModuloGetURL('app','EstacionEnfermeriaControlA','user','CallControlesPacientes',array("control_descripcion"=>"CONTROLES DE APOYOS DIAGNOSTICOS PENDIENTES","estacion"=>$datos)) ."\" target=\"Contenido\">Programaci&oacute;n y Apoyos Diagnostico Pendientes</a>\n";
													$urlAP = ModuloGetURL('app','EstacionEnfermeriaControlA','user','CallControlesPacientes',array("estacion"=>$estacion,"datos_estacion"=>array("pieza"=>$B['pieza'],"cama"=>$B['cama'],"NombrePaciente"=>$B['primer_nombre']." ".$B['segundo_nombre']." ".$B['primer_apellido']." ".$B['segundo_apellido'],"paciente_id"=>$B['paciente_id'],"tipo_id_paciente"=>$B['tipo_id_paciente'],"ingreso"=>$B['ingreso'],"control_descripcion"=>'CONTROLES DE APOYOS DIAGNOSTICOS PENDIENTES')));
													$conteo=$this->GetConteo_Hc_control_apoyod($B['ingreso']);
													if(empty($conteo)){$img='fecha_inicio.png';} else {$img='tabla_activa.png';}
													$this->salida .= "	<td align=\"center\"><a href='$urlAP'><img src=\"". GetThemePath() ."/images/$img\" border='0'>&nbsp;AD</a></td>\n";
												}

												//realizamos un conteo de neurologicos por cada ingreso.
												$conteo_neuro=$this->GetControles($B['ingreso'],10);
												//realizamos un conteo de glucometria por cada ingreso.
												$conteo_gluco=$this->GetControles($B['ingreso'],8);

												if($conteo_gluco >0)
												{
													$_SESSION['CONTEO']['GLUCO']=$_SESSION['CONTEO']['GLUCO']+1;
													$_SESSION['CONTEO']['GLUCO_VECT'][$B['ingreso']]=$B['ingreso'];
													$enlaceGlucometria = "".ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmFrecuenciaControlesP',array("control"=>8,"ingreso"=>$B['ingreso'],"descripcion"=>"CONTROL GLUCOMETR&Iacute;A","estacion"=>$estacion,"href_action_hora"=>"CallFrmIngresarDatosGlucometria","href_action_control"=>array(0=>"CallFrmResumenGlucometria"))) ."\n";
													$this->salida .= "	<td align=\"center\"><a href='$enlaceGlucometria'><img src=\"". GetThemePath() ."/images/glucometria.png\" border='0'>&nbsp;GM</a></td>\n";
												}
												else
												{
													$this->salida .= "	<td align=\"center\"><img src=\"". GetThemePath() ."/images/noglucometria.png\" border='0'>&nbsp;GM</td>\n";
												}

												if($conteo_neuro >0)
												{
													$_SESSION['CONTEO']['NEURO']=$_SESSION['CONTEO']['NEURO']+1;
													$_SESSION['CONTEO']['NEURO_VECT'][$B['ingreso']]=$B['ingreso'];
													$enlaceNeurologico = "".ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmFrecuenciaControlesP',array("control"=>10,"ingreso"=>$B['ingreso'],"descripcion"=>"CONTROL NEUROLOGICO","estacion"=>$estacion,"href_action_hora"=>"CallFrmControlNeurologico","href_action_control"=>array(0=>"CallFrmResumenHojaNeurologica"))) ."";
													$this->salida .= "	<td align=\"center\"><a href='$enlaceNeurologico'><img src=\"". GetThemePath() ."/images/neurologico.png\" border='0'>&nbsp;CN</a></td>\n";
												}
												else
												{
													$this->salida .= "	<td align=\"center\"><img src=\"". GetThemePath() ."/images/noneurologico.png\" border='0'>&nbsp;CN</td>\n";
												}



										/*	$dato_ev=$this->BuscarEvolucion($B['ingreso']);
											if($dato_ev == 'nada')//la posicion 0 es la evolucion
											{
												$url2=ModuloHCGetURL(0,'',$B['ingreso'],$_SESSION['ESTACION_ENFERMERIA']['hc_modulo_enfermera'],'NotasEnfermeria',array('estacion'=>$estacion['estacion_id']));
												$this->salida .= "	<td align=\"center\"><a href='$url2'><img src=\"". GetThemePath() ."/images/historial.png\" border='0'>&nbsp;Atender</a></td>\n";
											}
											else
											{
												$this->salida .= "	<td align=\"center\"></td>\n";
											}*/

												$salida='';
												$prof=0;
											  $arreglo_info= $this->Buscar_Evoluciones_Medicas($B['ingreso'],UserGetUID());
                        if(is_array($arreglo_info))
												{
														for($n=0;$n<sizeof($arreglo_info);$n++)
														{
															$fechas_evol.=$arreglo_info[$n]['fecha']."<BR>";
															if(!empty($arreglo_info[$n]['nombre']))
															{$medico.=$arreglo_info[$n]['nombre']."<BR>";}


																	if($arreglo_info[$n]['usuario_id']==UserGetUID())
																		{
																				$accion=ModuloHCGetURL($arreglo_info[$n]['evolucion_id'],'',0,$_SESSION['ESTACION_ENFERMERIA']['hc_modulo_enfermera'],'NotasEnfermeria',array('estacion'=>$estacion['estacion_id']));
																				$salida .="<a href='$accion'><sub>Continuar Atencion</sub></a><br>";
																				$prof=1;
																		}
																		else
																		{
																					$accion=ModuloHCGetURL(0,'',$B['ingreso'],$_SESSION['ESTACION_ENFERMERIA']['hc_modulo_enfermera'],'NotasEnfermeria',array('estacion'=>$estacion['estacion_id']));
																					$salida .="<a href='$accion'><sub>Nueva Atencion</sub></a><br>";

																		}

														}//fin for.

												}
												else
												{
													$accion=ModuloHCGetURL(0,'',$B['ingreso'],$_SESSION['ESTACION_ENFERMERIA']['hc_modulo_enfermera'],'NotasEnfermeria',array('estacion'=>$estacion['estacion_id']));
													$salida .="<a href='$accion'><sub>Nueva Atencion</sub></a><br>";
												}

													$this->salida .= "	<td align=\"center\">$salida</td>\n";






											$conteo_os=$this->ConteoOrdenesPaciente($B['ingreso']);
											if($conteo_os==1)
											{
												$href=ModuloGetURL('app','CentralImpresionHospitalizacion','user','BuscarPorEstacion',array("estacion"=>$estacion[estacion_id],
												"paciente_id"=>$B['paciente_id'],"tipo_id_paciente"=>$B['tipo_id_paciente'],"nombre_estacion"=>$estacion[descripcion4],"ingreso"=>$B['ingreso']));
												$this->salida .= "	<td align=\"center\"><a href=\"$href\"><img src=\"". GetThemePath() ."/images/pinactivo.png\" border='0'>&nbsp;OS</a></td>\n";
											}
											else
											{
												$this->salida .= "	<td align=\"center\"><img src=\"". GetThemePath() ."/images/editar.png\" border='0'>&nbsp;OS</td>\n";
											}
											}else
											{$this->salida.="<td colspan=\"8\" align=\"center\">EN CIRUGIA</td>\n";}


											$this->salida .= "</tr>\n";

					}//fin for

						if($x==0)
						{
								$pac_consulta=$this->BuscarPacientesConsulta_Urgencias($estacion);
								if(is_array($pac_consulta))
								{$this->Pacientes_X_Consulta_Urgencias($estacion,&$reporte,$pac_consulta);}
								$x=1;
						}

						///javafunction de sv all
						$this->salida .= $reporte->GetJavaReport('app','EstacionE_ControlPacientes','SignosVitalesAll',
						array('estacion'=>$estacion),array('rpt_dir'=>'cache','rpt_name'=>'Listado_Signos','rpt_rewrite'=>TRUE));
						$_SV=$reporte->GetJavaFunction();

						$this->salida .= "<tr class='modulo_table_title'>\n";
						$this->salida .= "	<td colspan='5' align=\"center\"></td>\n";
						$this->salida .= "	<td align=\"center\"><input type='button' class='input-bottom' name='imp' value=SV onclick=javascript:$_SV></td>\n";
						$this->salida .= "	<td align=\"center\"></td>\n";
						$this->salida .= "	<td align=\"center\"></td>\n";
						$this->salida .= "	<td class='modulo_table_title' colspan='5' align=\"center\"></td>\n";
						$this->salida .= "</tr>\n";
						$this->salida .= "</table><br>\n";



					//$this->salida .= "<div class=\"label\" align=\"center\">TOTAL PACIENTES HOSPITALIZACION = ".sizeof($datoscenso[hospitalizacion])."<br>\n";
					}//fin formato hospitalizacio
				}//fin foreach



			//	$href2 = ModuloGetURL('app','EstacionE_ControlPacientes','user','ListRevisionPorSistemas',array("estacion"=>$estacion));
			//	$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href2."'>Refrescar</a><br>";

			//	$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$estacion));
			//	$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";

				//$this->salida .= themeCerrarTabla();
				unset($ItemBusqueda);

			}
			else //es por que no hay hospitalizados pero todavia podemos revisar los pendientes x ingresar.
			{
						$pac_consulta=$this->BuscarPacientesConsulta_Urgencias($estacion);
						$pacientes = $this->GetPacientesPendientesXHospitalizar($estacion);

						if(is_array($pacientes) OR is_array($pac_consulta))
						{
								$this->salida .= "<br><table align=\"center\" width=\"100%\"  border=\"0\" >\n";
								$this->salida .= "	<tr class=\"modulo_table_title\"><td colspan='13' height='30'>PACIENTES EN HOSPITALIZACI&Oacute;N</td></tr>\n";
								$this->salida .= "	<tr class=\"modulo_table_title\">\n";
								$this->salida .= "		<td></td>\n";
								$this->salida .= "		<td><sub>HAB.</sub></td>\n";
								$this->salida .= "		<td><sub>CAMA</sub></td>\n";
								$this->salida .= "		<td><sub>TIEMPO<BR>HOSP.</sub></td>\n";
								$this->salida .= "		<td><sub>PACIENTE DE ESTACI?N</sub></td>\n";
								$this->salida .= "		<td><sub>SIGNOS<BR>VITALES</sub></td>\n";
								$this->salida .= "		<td><sub>MED.<BR>PACIENTES</sub></td>\n";
								$this->salida .= "		<td><sub>CTRL<BR>PROGRAMADOS</sub></td>\n";
							//	$this->salida .= "		<td>TRANFUSIONES</td>\n";
								$this->salida .= "		<td><sub>PROGR.<BR>APOYO</sub></td>\n";

							//glucometria
								$this->salida .= "		<td><sub>GLUCO<BR>METRIA</sub></td>\n";
								//neurologico
								$this->salida .= "		<td><sub>NEURO<BR>LOGICO</sub></td>\n";
								$this->salida .= "		<td><sub>HISTORIA<BR> CLINICA</sub></td>\n";
								//$this->salida .= "		<td>NOTAS<BR> ENFERMERIA</td>\n";
								$this->salida .= "		<td><sub>ORDEN<BR>SERVICIOS</sub></td>\n";
								$this->salida .= "	</tr>\n";

								//mostramos los pacientes pendientes por ingresar .. si hay
								if(is_array($pacientes))
								{$this->Pacientes_X_Ingresar($estacion,$reporte,$pacientes);}
								if(is_array($pac_consulta))
								{$this->Pacientes_X_Consulta_Urgencias($estacion,&$reporte,$pac_consulta);}


								$this->salida .= $reporte->GetJavaReport('app','EstacionE_ControlPacientes','SignosVitalesAll',
								array('estacion'=>$estacion),array('rpt_dir'=>'cache','rpt_name'=>'Listado_Signos','rpt_rewrite'=>TRUE));
								$_SV=$reporte->GetJavaFunction();

								$this->salida .= "<tr class='modulo_table_title'>\n";
								$this->salida .= "	<td colspan='5' align=\"center\"></td>\n";
								$this->salida .= "	<td align=\"center\"><input type='button' class='input-bottom' name='imp' value=SV onclick=javascript:$_SV></td>\n";
								$this->salida .= "	<td align=\"center\"></td>\n";
								$this->salida .= "	<td align=\"center\"></td>\n";
								$this->salida .= "	<td class='modulo_table_title' colspan='5' align=\"center\"></td>\n";
								$this->salida .= "</tr>\n";
								$this->salida .= "</table><br>\n";

					}
					else
					{
								$mensaje = "LA ESTACI&Oacute;N [ ".$estacion['descripcion5']." ] NO CUENTA CON PACIENTES.";
								$titulo = "ALERTA DEL SISTEMA";
								$boton = "SELECCIONAR ESTACION";
								$controles=$this->GetControles($datoscenso['hospitalizacion'][$i]['ingreso']);
								$href=ModuloGetURL('app','EstacionEnfermeria','user','');
								$this->FormaMensaje($mensaje,$titulo,$href,$boton);
								return true;
					}
			}
			return true;
		}
/*funcion que debe estar en el mod estacione_controlpaciente*/



/*
* funcion que revisa los pacientes que esta en consulta de urgencias..
*/
function Pacientes_X_Consulta_Urgencias($estacion,&$reporte,$pacientes)
{
		unset($_SESSION['ESTACION_ENF']['CONTEO']['CONSULTA']);
		if(is_array($pacientes))
		{
			$vector_ingresos=array();//reiniciamos el vector q va a comparar los ingresos.

							for($i=0; $i<sizeof($pacientes); $i++)
							{
								$cirugia2 = $this->VerificacionPaciente_ECirugia($pacientes[$i][6]);
								if(in_array($pacientes[$i][4], $vector_ingresos)==FALSE)
								{
									$viaIngreso = $this->GetViaIngresoPaciente($pacientes[$i][4]);//le envio el ingreso
									$backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');

									if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
									$this->salida .= "<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>\n";
									$linkVerDatos = ModuloGetURL('app','EstacionE_Pacientes','user','CallMostrarDatosIngreso',array("ingresoID"=>$pacientes[$i][4],"retorno"=>"CallMenu","modulito"=>'EstacionEnfermeria',"datos_estacion"=>$estacion));
									$linker=ModuloGetURL('app','EstacionE_Pacientes','user','CallListPacientesPorIngresar',array("datos_estacion"=>$estacion,"paciente_id"=>$pacientes[$i][2],"tipo_id_paciente"=>$pacientes[$i][3]));

									$nombre=$pacientes[$i][0]." ".$pacientes[$i][1];
									if($pacientes[$i][11]==1)
									{
											$this->salida .= "	<td  align=\"center\"><img src=\"". GetThemePath() ."/images/consulta_ur.png\" border='0' title='PACIENTE EN CONSULTA'></td>\n";
											$this->salida .= "	<td  colspan='3' align=\"center\"><label class=label_mark>CONSULTA</label></td>\n";
									}
									elseif($pacientes[$i][11]==7)
									{
											$ref=ModuloGetURL('app','EstacionE_Pacientes','user','SacarPacienteConsultaUrgencias',array("datos_estacion"=>$estacion,'nombre'=>$nombre,'ingreso'=>$pacientes[$i][4],"paciente_id"=>$pacientes[$i][2],"tipo_id_paciente"=>$pacientes[$i][3]));
											$this->salida .= "	<td  align=\"center\"><a href=\"$ref\"><img src=\"". GetThemePath() ."/images/egreso.png\" border='0' title='EGRESO ESTACION'></a></td>\n";
											$this->salida .= "	<td  colspan='3' align=\"center\"><label class=label_mark>CONSULTA - ATENCION ENFERMERIA</label></td>\n";
									}
									//$this->salida .= "	<td  align=\"center\">info</td>\n";
									//$this->salida .= "	<td  align=\"center\">info</td>\n";
									//$this->salida .= "	<td  align=\"center\">".$viaIngreso[via_ingreso_nombre]."&nbsp;</td>\n";
									//$this->salida .= "	<td align=\"center\">".$pacientes[$i][9]."&nbsp;</td>\n";
									$this->salida .= "	<td nowrap><a href=\"$linkVerDatos\">".$pacientes[$i][0]." ".$pacientes[$i][1]."</a></td>\n";

									//SIGNOS VITALES

					// 					//SIGNOS VITALES
					// 											$this->salida .= $reporte->GetJavaReport('app','EstacionE_ControlPacientes','SignosVitales',
					// 											array('estacion'=>$estacion,'datos_estacion'=>array("pieza"=>$B['pieza'],"cama"=>$B['cama'],"NombrePaciente"=>$B['primer_nombre']." ".$B['segundo_nombre']." ".$B['primer_apellido']." ".$B['segundo_apellido'],"paciente_id"=>$B['paciente_id'],"tipo_id_paciente"=>$B['tipo_id_paciente'],"Hora"=>$tiempo,"ingreso"=>$B['ingreso'],"control_id"=>15,"control_descripcion"=>"CONTROL DE SIGNOS VITALES")
					// 											),array('rpt_dir'=>'cache','rpt_name'=>'signo'.$B['ingreso'],'rpt_rewrite'=>TRUE));
					// 											$funcion=$reporte->GetJavaFunction();

								if(empty($cirugia2))
								{

									unset($funcion);
									$_SESSION['ESTACION']['VECT'][]=array("pieza"=>'No Ingresado',"cama"=>'No Ingresado',"NombrePaciente"=>$nombre,"paciente_id"=>$pacientes[$i][2],"tipo_id_paciente"=>$pacientes[$i][3],"Hora"=>$tiempo,"ingreso"=>$pacientes[$i][4],"control_id"=>15,"control_descripcion"=>"CONTROL DE SIGNOS VITALES");
										//SIGNOS VITALES
									$this->salida .= $reporte->GetJavaReport('app','EstacionE_ControlPacientes','SignosVitales',
									array('estacion'=>$estacion,'datos_estacion'=>array("pieza"=>'No Ingresado',"cama"=>'No Ingresado',"NombrePaciente"=>$nombre,"paciente_id"=>$pacientes[$i][2],"tipo_id_paciente"=>$pacientes[$i][3],"Hora"=>$tiempo,"ingreso"=>$pacientes[$i][4],"control_id"=>15,"control_descripcion"=>"CONTROL DE SIGNOS VITALES")
									),array('rpt_dir'=>'cache','rpt_name'=>'signo'.$pacientes[$i][4],'rpt_rewrite'=>TRUE));
									$funcion=$reporte->GetJavaFunction();


									$tiempo=date("Y-m-d H:i:s",mktime(date("H"),date("i"),0,date("m"),date("d"),date("Y")));
									$urls = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmSignosVitales',array("estacion"=>$estacion,"datos_estacion"=>array("pieza"=>'No Ingresado',"cama"=>'No Ingresado',"NombrePaciente"=>$nombre,"paciente_id"=>$pacientes[$i][2],"tipo_id_paciente"=>$pacientes[$i][3],"Hora"=>$tiempo,"ingreso"=>$pacientes[$i][4],"control_id"=>15,"control_descripcion"=>"CONTROL DE SIGNOS VITALES")));
									$this->salida .= "	<td align=\"center\"><a href='$urls'><img src=\"". GetThemePath() ."/images/traslado.png\" border='0'></a><a href='javascript:$funcion'>&nbsp;PDF</a></td>\n";

									//MEDICAMENTOS
									$medicamento=$this->GetPacMedicamentosPorSolicitar($pacientes[$i][4]);
									if($medicamento==1)
									{$imgM="pparamedin.png";}else{$imgM="pparamed.png";}
									$urla = ModuloGetURL('app','EstacionE_Medicamentos','user','CallFrmMedicamentos',array("estacion"=>$estacion,"datos_estacion"=>array("pieza"=>'No Ingresado',"cama"=>'No Ingresado',"NombrePaciente"=>$nombre,"paciente_id"=>$pacientes[$i][2],"tipo_id_paciente"=>$pacientes[$i][3],"Hora"=>$tiempo,"ingreso"=>$pacientes[$i][4],"control_id"=>2,"control_descripcion"=>"CONTROL MEDICAMENTOS PACIENTE")));
									$this->salida .= "	<td align=\"center\"><a href='$urla'><img src=\"". GetThemePath() ."/images/$imgM\" border='0'>&nbsp;MP</a></td>\n";

										$conteop=$this->CountControles($pacientes[$i][4]);
									//REVISAR CONTROLES PROGRAMADOS
									//$nombre=$B[primer_nombre]." ".$B[segundo_nombre]." ".$B[primer_apellido]." ".$B[segundo_apellido];
									$url = ModuloGetURL('app','EstacionE_ControlPacientes','user','Listado_controles',array("estacion"=>$estacion,"ingreso"=>$pacientes[$i][4],"nombre"=>urlencode($nombre),"pieza"=>'No Ingresado',"cama"=>'No Ingresado'));

									if($conteop==1)
									{
										$imgp="resultado.png";
										$this->salida .= "	<td align=\"center\"><a href='$url'><img src=\"". GetThemePath() ."/images/$imgp\" border='0'>&nbsp;CP</a></td>\n";
									}else
									{
										$imgp="prangos.png";
										$this->salida .= "	<td align=\"center\"><img src=\"". GetThemePath() ."/images/$imgp\" border='0'>&nbsp;CP</td>\n";
									}
										unset($conteop);

										$centinela=0;
												//Traemos las fechas de los apoyos diagnosticos pendientes.
												$fech_apoyo=$this->GetFechasHcApoyos($pacientes[$i][4]);
												for($max=0;$max < sizeof($fech_apoyo);$max++)
												{
													if(strtotime($fech_apoyo[$max][fecha]) <= strtotime(date("y-m-d H:i:s")))
													{ $centinela=1; break;}
													$centinela=0;
												}

												if($centinela==1)
												{
														$urlAP = ModuloGetURL('app','EstacionEnfermeriaControlA','user','CallControlesPacientes',array("estacion"=>$estacion,"datos_estacion"=>array("pieza"=>'No Ingresado',"cama"=>'No Ingresado',"NombrePaciente"=>$nombre,"paciente_id"=>$pacientes[$i][2],"tipo_id_paciente"=>$pacientes[$i][3],"ingreso"=>$pacientes[$i][4],"control_descripcion"=>'CONTROLES DE APOYOS DIAGNOSTICOS PENDIENTES')));
														$img='alarma.png';
														$this->salida .= "	<td align=\"center\"><a href='$urlAP'><img src=\"". GetThemePath() ."/images/$img\" border='0'>&nbsp;AD</a></td>\n";
												}
												else
												{
														//PROGRAMACION DE APOYOS DIAGNOSTICOS PENDIENTES.....
														//$enlaceProgramacion = "<a href=\"".ModuloGetURL('app','EstacionEnfermeriaControlA','user','CallControlesPacientes',array("control_descripcion"=>"CONTROLES DE APOYOS DIAGNOSTICOS PENDIENTES","estacion"=>$datos)) ."\" target=\"Contenido\">Programaci&oacute;n y Apoyos Diagnostico Pendientes</a>\n";
														$urlAP = ModuloGetURL('app','EstacionEnfermeriaControlA','user','CallControlesPacientes',array("estacion"=>$estacion,"datos_estacion"=>array("pieza"=>'No Ingresado',"cama"=>'No Ingresado',"NombrePaciente"=>$nombre,"paciente_id"=>$pacientes[$i][2],"tipo_id_paciente"=>$pacientes[$i][3],"ingreso"=>$pacientes[$i][4],"control_descripcion"=>'CONTROLES DE APOYOS DIAGNOSTICOS PENDIENTES')));
														if(empty($B[hc_control_apoyod])){$img='fecha_inicio.png';} else {$img='tabla_activa.png';}
														$this->salida .= "	<td align=\"center\"><a href='$urlAP'><img src=\"". GetThemePath() ."/images/$img\" border='0'>&nbsp;AD</a></td>\n";
													}


											//realizamos un conteo de neurologicos por cada ingreso.
												$conteo_neuro=$this->GetControles($pacientes[$i][4],10);
												//realizamos un conteo de glucometria por cada ingreso.
												$conteo_gluco=$this->GetControles($pacientes[$i][4],8);

												if($conteo_gluco >0)
												{
													$_SESSION['CONTEO']['GLUCO']=$_SESSION['CONTEO']['GLUCO']+1;
													$_SESSION['CONTEO']['GLUCO_VECT'][$pacientes[$i][4]]=$pacientes[$i][4];
													$enlaceGlucometria = "".ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmFrecuenciaControlesP',array("consulta"=>true,"control"=>8,"ingreso"=>$pacientes[$i][4],"descripcion"=>"CONTROL GLUCOMETR&Iacute;A","estacion"=>$estacion,"href_action_hora"=>"CallFrmIngresarDatosGlucometria","href_action_control"=>array(0=>"CallFrmResumenGlucometria"))) ."\n";
													$this->salida .= "	<td align=\"center\"><a href='$enlaceGlucometria'><img src=\"". GetThemePath() ."/images/glucometria.png\" border='0'>&nbsp;GM</a></td>\n";
												}
												else
												{
													$this->salida .= "	<td align=\"center\"><img src=\"". GetThemePath() ."/images/noglucometria.png\" border='0'>&nbsp;GM</td>\n";
												}

												if($conteo_neuro >0)
												{
													$_SESSION['CONTEO']['NEURO']=$_SESSION['CONTEO']['NEURO']+1;
													$_SESSION['CONTEO']['NEURO_VECT'][$pacientes[$i][4]]=$pacientes[$i][4];
													$enlaceNeurologico = "".ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmFrecuenciaControlesP',array("consulta"=>true,"control"=>10,"ingreso"=>$pacientes[$i][4],"descripcion"=>"CONTROL NEUROLOGICO","estacion"=>$estacion,"href_action_hora"=>"CallFrmControlNeurologico","href_action_control"=>array(0=>"CallFrmResumenHojaNeurologica"))) ."";
													$this->salida .= "	<td align=\"center\"><a href='$enlaceNeurologico'><img src=\"". GetThemePath() ."/images/neurologico.png\" border='0'>&nbsp;CN</a></td>\n";
												}
												else
												{
													$this->salida .= "	<td align=\"center\"><img src=\"". GetThemePath() ."/images/noneurologico.png\" border='0'>&nbsp;CN</td>\n";
												}




									/*			$dato_ev=$this->BuscarEvolucion($pacientes[$i][4]);

									if($dato_ev == 'nada')//la posicion 0 es la evolucion
									{
										$url2=ModuloHCGetURL(0,'',$pacientes[$i][4],$_SESSION['ESTACION_ENFERMERIA']['hc_modulo_enfermera'],'NotasEnfermeria',array('estacion'=>$estacion['estacion_id']));
										$this->salida .= "	<td align=\"center\"><a href='$url2'><img src=\"". GetThemePath() ."/images/historial.png\" border='0'>&nbsp;Atender</a></td>\n";
									}
									else
									{
										$this->salida .= "	<td align=\"center\"><img src=\"". GetThemePath() ."/images/historial.png\" border='0'>&nbsp;Atender</td>\n";

									}*/

												$salida='';
												$prof=0;
											  $arreglo_info= $this->Buscar_Evoluciones_Medicas($pacientes[$i][4],UserGetUID());
												if(is_array($arreglo_info))
												{
														for($n=0;$n<sizeof($arreglo_info);$n++)
														{
															$fechas_evol.=$arreglo_info[$n]['fecha']."<BR>";
															if(!empty($arreglo_info[$n]['nombre']))
															{$medico.=$arreglo_info[$n]['nombre']."<BR>";}


																	if($arreglo_info[$n]['usuario_id']==UserGetUID())
																		{
																				$accion=ModuloHCGetURL($arreglo_info[$n]['evolucion_id'],'',0,$_SESSION['ESTACION_ENFERMERIA']['hc_modulo_enfermera'],'NotasEnfermeria',array('estacion'=>$estacion['estacion_id']));
																				$salida .="<a href='$accion'><sub>Continuar Atencion</sub></a><br>";
																				$prof=1;
																		}
																		else
																		{
																					$accion=ModuloHCGetURL(0,'',$pacientes[$i][4],$_SESSION['ESTACION_ENFERMERIA']['hc_modulo_enfermera'],'NotasEnfermeria',array('estacion'=>$estacion['estacion_id']));
																					$salida .="<a href='$accion'><sub>Nueva Atencion</sub></a><br>";

																		}

														}//fin for.
												}
												else
												{
														$accion=ModuloHCGetURL(0,'',$pacientes[$i][4],$_SESSION['ESTACION_ENFERMERIA']['hc_modulo_enfermera'],'NotasEnfermeria',array('estacion'=>$estacion['estacion_id']));
														$salida .="<a href='$accion'><sub>Nueva Atencion</sub></a><br>";
												}

												$this->salida .= "	<td align=\"center\">&nbsp;$salida</td>\n";




										$conteo_os=$this->ConteoOrdenesPaciente($pacientes[$i][4]);
										if($conteo_os==1)
										{
											$href=ModuloGetURL('app','CentralImpresionHospitalizacion','user','BuscarPorEstacion',array("estacion"=>$estacion[estacion_id],
											"paciente_id"=>$pacientes[$i][2],"tipo_id_paciente"=>$pacientes[$i][3],"nombre_estacion"=>$estacion[descripcion4],"ingreso"=>$pacientes[$i][4]));
											$this->salida .= "	<td align=\"center\"><a href=\"$href\"><img src=\"". GetThemePath() ."/images/pinactivo.png\" border='0'>&nbsp;OS</a></td>\n";
										}
										else
										{
											$this->salida .= "	<td align=\"center\"><img src=\"". GetThemePath() ."/images/editar.png\" border='0'>&nbsp;OS</td>\n";
										}

									}else
									{$this->salida.="<td colspan=\"8\" align=\"center\">EN CIRUGIA</td>\n";}

									//$this->salida .= "	<td align=\"center\">".$pacientes[$i][3]." ".$pacientes[$i][2]."</td>\n";
										$this->salida .= "</tr>\n";
										//$_SESSION['ESTACION_ENF']['CONTEO']['INGRESO']=$_SESSION['ESTACION_ENF']['CONTEO']['INGRESO']+ 1;
										$vector_ingresos[$i]=$pacientes[$i][4];
										$_SESSION['ESTACION_ENF']['CONTEO']['CONSULTA']=$_SESSION['ESTACION_ENF']['CONTEO']['CONSULTA'] +1;
								}//fin de preguntar si el ingreso esta en el arreglo..//esto es para sacar el paciente si se repite.
								else
								{
									$vector_ingresos[$i]=$pacientes[$i][4];
								}
						}//fin for

		unset($vector_ingresos);//unseteamos el vector de ingresos.
		}//pacientes por ingresar
	return true;
}











/*
* funcion que revisa los pacientes que estan por ingresar
*/
function Pacientes_X_Ingresar($estacion,&$reporte,$pacientes)
{
			if(is_array($pacientes))
		{
			for($i=0; $i<sizeof($pacientes); $i++)
			{
				$cirugia3 = $this->VerificacionPaciente_ECirugia($pacientes[$i][6]);
				$viaIngreso = $this->GetViaIngresoPaciente($pacientes[$i][4]);//le envio el ingreso
				$backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');

				if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
				$this->salida .= "<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>\n";
				$linkVerDatos = ModuloGetURL('app','EstacionE_Pacientes','user','CallMostrarDatosIngreso',array("ingresoID"=>$pacientes[$i][4],"retorno"=>"CallMenu","modulito"=>'EstacionEnfermeria',"datos_estacion"=>$estacion));


				$linker=ModuloGetURL('app','EstacionE_Pacientes','user','CallListPacientesPorIngresar',array("datos_estacion"=>$estacion,"paciente_id"=>$pacientes[$i][2],"tipo_id_paciente"=>$pacientes[$i][3]));

				if(empty($cirugia3))
				{$this->salida .= "	<td  align=\"center\"><a href='$linker'><img src=\"". GetThemePath() ."/images/ingresar.png\" border='0'></a></td>\n";}
				else
				{$this->salida .= "	<td  align=\"center\"><img src=\"". GetThemePath() ."/images/ingresar.png\" border='0'></a></td>\n";}
				$this->salida .= "	<td  colspan='3' align=\"center\"><a href='$linker'>[ ASIGNAR CAMA ]</a></td>\n";
				//$this->salida .= "	<td  align=\"center\">info</td>\n";
				//$this->salida .= "	<td  align=\"center\">info</td>\n";
				//$this->salida .= "	<td  align=\"center\">".$viaIngreso[via_ingreso_nombre]."&nbsp;</td>\n";
				//$this->salida .= "	<td align=\"center\">".$pacientes[$i][9]."&nbsp;</td>\n";
				$this->salida .= "	<td nowrap><a href=\"$linkVerDatos\">".$pacientes[$i][0]." ".$pacientes[$i][1]."</a></td>\n";



				$nombre=$pacientes[$i][0]." ".$pacientes[$i][1];
				//SIGNOS VITALES

				if(empty($cirugia3))
				{

// 					//SIGNOS VITALES
// 											$this->salida .= $reporte->GetJavaReport('app','EstacionE_ControlPacientes','SignosVitales',
// 											array('estacion'=>$estacion,'datos_estacion'=>array("pieza"=>$B['pieza'],"cama"=>$B['cama'],"NombrePaciente"=>$B['primer_nombre']." ".$B['segundo_nombre']." ".$B['primer_apellido']." ".$B['segundo_apellido'],"paciente_id"=>$B['paciente_id'],"tipo_id_paciente"=>$B['tipo_id_paciente'],"Hora"=>$tiempo,"ingreso"=>$B['ingreso'],"control_id"=>15,"control_descripcion"=>"CONTROL DE SIGNOS VITALES")
// 											),array('rpt_dir'=>'cache','rpt_name'=>'signo'.$B['ingreso'],'rpt_rewrite'=>TRUE));
// 											$funcion=$reporte->GetJavaFunction();


				unset($funcion);
				$_SESSION['ESTACION']['VECT'][]=array("pieza"=>'No Ingresado',"cama"=>'No Ingresado',"NombrePaciente"=>$nombre,"paciente_id"=>$pacientes[$i][2],"tipo_id_paciente"=>$pacientes[$i][3],"Hora"=>$tiempo,"ingreso"=>$pacientes[$i][4],"control_id"=>15,"control_descripcion"=>"CONTROL DE SIGNOS VITALES");
					//SIGNOS VITALES
				$this->salida .= $reporte->GetJavaReport('app','EstacionE_ControlPacientes','SignosVitales',
				array('estacion'=>$estacion,'datos_estacion'=>array("pieza"=>'No Ingresado',"cama"=>'No Ingresado',"NombrePaciente"=>$nombre,"paciente_id"=>$pacientes[$i][2],"tipo_id_paciente"=>$pacientes[$i][3],"Hora"=>$tiempo,"ingreso"=>$pacientes[$i][4],"control_id"=>15,"control_descripcion"=>"CONTROL DE SIGNOS VITALES")
				),array('rpt_dir'=>'cache','rpt_name'=>'signo'.$pacientes[$i][4],'rpt_rewrite'=>TRUE));
				$funcion=$reporte->GetJavaFunction();


				$tiempo=date("Y-m-d H:i:s",mktime(date("H"),date("i"),0,date("m"),date("d"),date("Y")));
				$urls = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmSignosVitales',array("estacion"=>$estacion,"datos_estacion"=>array("pieza"=>'No Ingresado',"cama"=>'No Ingresado',"NombrePaciente"=>$nombre,"paciente_id"=>$pacientes[$i][2],"tipo_id_paciente"=>$pacientes[$i][3],"Hora"=>$tiempo,"ingreso"=>$pacientes[$i][4],"control_id"=>15,"control_descripcion"=>"CONTROL DE SIGNOS VITALES")));
				$this->salida .= "	<td align=\"center\"><a href='$urls'><img src=\"". GetThemePath() ."/images/traslado.png\" border='0'></a><a href='javascript:$funcion'>&nbsp;PDF</a></td>\n";

				//MEDICAMENTOS
				$medicamento=$this->GetPacMedicamentosPorSolicitar($pacientes[$i][4]);
				if($medicamento==1)
				{$imgM="pparamedin.png";}else{$imgM="pparamed.png";}
				$urla = ModuloGetURL('app','EstacionE_Medicamentos','user','CallFrmMedicamentos',array("estacion"=>$estacion,"datos_estacion"=>array("pieza"=>'No Ingresado',"cama"=>'No Ingresado',"NombrePaciente"=>$nombre,"paciente_id"=>$pacientes[$i][2],"tipo_id_paciente"=>$pacientes[$i][3],"Hora"=>$tiempo,"ingreso"=>$pacientes[$i][4],"control_id"=>2,"control_descripcion"=>"CONTROL MEDICAMENTOS PACIENTE")));
				$this->salida .= "	<td align=\"center\"><a href='$urla'><img src=\"". GetThemePath() ."/images/$imgM\" border='0'>&nbsp;MP</a></td>\n";

					$conteop=$this->CountControles($pacientes[$i][4]);
				//REVISAR CONTROLES PROGRAMADOS
				//$nombre=$B[primer_nombre]." ".$B[segundo_nombre]." ".$B[primer_apellido]." ".$B[segundo_apellido];
				$url = ModuloGetURL('app','EstacionE_ControlPacientes','user','Listado_controles',array("estacion"=>$estacion,"ingreso"=>$pacientes[$i][4],"nombre"=>urlencode($nombre),"pieza"=>'No Ingresado',"cama"=>'No Ingresado'));

				if($conteop==1)
				{
					$imgp="resultado.png";
					$this->salida .= "	<td align=\"center\"><a href='$url'><img src=\"". GetThemePath() ."/images/$imgp\" border='0'>&nbsp;CP</a></td>\n";
				}else
				{
					$imgp="prangos.png";
					$this->salida .= "	<td align=\"center\"><img src=\"". GetThemePath() ."/images/$imgp\" border='0'>&nbsp;CP</td>\n";
				}
					unset($conteop);

					$centinela=0;
							//Traemos las fechas de los apoyos diagnosticos pendientes.
							$fech_apoyo=$this->GetFechasHcApoyos($pacientes[$i][4]);
							for($max=0;$max < sizeof($fech_apoyo);$max++)
							{
								if(strtotime($fech_apoyo[$max][fecha]) <= strtotime(date("y-m-d H:i:s")))
								{ $centinela=1; break;}
								$centinela=0;
							}

							 if($centinela==1)
							 {
							 		$urlAP = ModuloGetURL('app','EstacionEnfermeriaControlA','user','CallControlesPacientes',array("estacion"=>$estacion,"datos_estacion"=>array("pieza"=>'No Ingresado',"cama"=>'No Ingresado',"NombrePaciente"=>$nombre,"paciente_id"=>$pacientes[$i][2],"tipo_id_paciente"=>$pacientes[$i][3],"ingreso"=>$pacientes[$i][4],"control_descripcion"=>'CONTROLES DE APOYOS DIAGNOSTICOS PENDIENTES')));
									$img='alarma.png';
									$this->salida .= "	<td align=\"center\"><a href='$urlAP'><img src=\"". GetThemePath() ."/images/$img\" border='0'>&nbsp;AD</a></td>\n";
							 }
							 else
							 {
									//PROGRAMACION DE APOYOS DIAGNOSTICOS PENDIENTES.....
									//$enlaceProgramacion = "<a href=\"".ModuloGetURL('app','EstacionEnfermeriaControlA','user','CallControlesPacientes',array("control_descripcion"=>"CONTROLES DE APOYOS DIAGNOSTICOS PENDIENTES","estacion"=>$datos)) ."\" target=\"Contenido\">Programaci&oacute;n y Apoyos Diagnostico Pendientes</a>\n";
									$urlAP = ModuloGetURL('app','EstacionEnfermeriaControlA','user','CallControlesPacientes',array("estacion"=>$estacion,"datos_estacion"=>array("pieza"=>'No Ingresado',"cama"=>'No Ingresado',"NombrePaciente"=>$nombre,"paciente_id"=>$pacientes[$i][2],"tipo_id_paciente"=>$pacientes[$i][3],"ingreso"=>$pacientes[$i][4],"control_descripcion"=>'CONTROLES DE APOYOS DIAGNOSTICOS PENDIENTES')));
									if(empty($B[hc_control_apoyod])){$img='fecha_inicio.png';} else {$img='tabla_activa.png';}
									$this->salida .= "	<td align=\"center\"><a href='$urlAP'><img src=\"". GetThemePath() ."/images/$img\" border='0'>&nbsp;AD</a></td>\n";
								}


									//realizamos un conteo de neurologicos por cada ingreso.
									$conteo_neuro=$this->GetControles($pacientes[$i][4],10);
									//realizamos un conteo de glucometria por cada ingreso.
									$conteo_gluco=$this->GetControles($pacientes[$i][4],8);

									if($conteo_gluco >0)
									{
										$_SESSION['CONTEO']['GLUCO']=$_SESSION['CONTEO']['GLUCO']+1;
										$_SESSION['CONTEO']['GLUCO_VECT'][$pacientes[$i][4]]=$pacientes[$i][4];
										$enlaceGlucometria = "".ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmFrecuenciaControlesP',array("control"=>8,"ingreso"=>$pacientes[$i][4],"descripcion"=>"CONTROL GLUCOMETR&Iacute;A","estacion"=>$estacion,"href_action_hora"=>"CallFrmIngresarDatosGlucometria","href_action_control"=>array(0=>"CallFrmResumenGlucometria"))) ."\n";
										$this->salida .= "	<td align=\"center\"><a href='$enlaceGlucometria'><img src=\"". GetThemePath() ."/images/glucometria.png\" border='0'>&nbsp;GM</a></td>\n";
									}
									else
									{
										$this->salida .= "	<td align=\"center\"><img src=\"". GetThemePath() ."/images/noglucometria.png\" border='0'>&nbsp;GM</td>\n";
									}

									if($conteo_neuro >0)
									{
										$_SESSION['CONTEO']['NEURO']=$_SESSION['CONTEO']['NEURO']+1;
										$_SESSION['CONTEO']['NEURO_VECT'][$pacientes[$i][4]]=$pacientes[$i][4];
										$enlaceNeurologico = "".ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmFrecuenciaControlesP',array("control"=>10,"ingreso"=>$pacientes[$i][4],"descripcion"=>"CONTROL NEUROLOGICO","estacion"=>$estacion,"href_action_hora"=>"CallFrmControlNeurologico","href_action_control"=>array(0=>"CallFrmResumenHojaNeurologica"))) ."";
										$this->salida .= "	<td align=\"center\"><a href='$enlaceNeurologico'><img src=\"". GetThemePath() ."/images/neurologico.png\" border='0'>&nbsp;CN</a></td>\n";
									}
									else
									{
										$this->salida .= "	<td align=\"center\"><img src=\"". GetThemePath() ."/images/noneurologico.png\" border='0'>&nbsp;CN</td>\n";
									}



				/*			$dato_ev=$this->BuscarEvolucion($pacientes[$i][4]);

				if($dato_ev == 'nada')//la posicion 0 es la evolucion
				{
					$url2=ModuloHCGetURL(0,'',$pacientes[$i][4],$_SESSION['ESTACION_ENFERMERIA']['hc_modulo_enfermera'],'NotasEnfermeria',array('estacion'=>$estacion['estacion_id']));
					$this->salida .= "	<td align=\"center\"><a href='$url2'><img src=\"". GetThemePath() ."/images/historial.png\" border='0'>&nbsp;Atender</a></td>\n";
				}*/

					$salida='';
					$prof=0;
					$arreglo_info= $this->Buscar_Evoluciones_Medicas($pacientes[$i][4],UserGetUID());

          if(is_array($arreglo_info))
					{
								for($n=0;$n<sizeof($arreglo_info);$n++)
								{
									$fechas_evol.=$arreglo_info[$n]['fecha']."<BR>";
									if(!empty($arreglo_info[$n]['nombre']))
									{$medico.=$arreglo_info[$n]['nombre']."<BR>";}


											if($arreglo_info[$n]['usuario_id']==UserGetUID())
												{
														$accion=ModuloHCGetURL($arreglo_info[$n]['evolucion_id'],'',0,$_SESSION['ESTACION_ENFERMERIA']['hc_modulo_enfermera'],'NotasEnfermeria',array('estacion'=>$estacion['estacion_id']));
														$salida .="<a href='$accion'><sub>Continuar Atencion</sub></a><br>";
														$prof=1;
												}
												else
												{
															$accion=ModuloHCGetURL(0,'',$pacientes[$i][4],$_SESSION['ESTACION_ENFERMERIA']['hc_modulo_enfermera'],'NotasEnfermeria',array('estacion'=>$estacion['estacion_id']));
															$salida .="<a href='$accion'><sub>Nueva Atencion</sub></a><br>";

												}

								}//fin for.
						}
						else
						{
										$accion=ModuloHCGetURL(0,'',$pacientes[$i][4],$_SESSION['ESTACION_ENFERMERIA']['hc_modulo_enfermera'],'NotasEnfermeria',array('estacion'=>$estacion['estacion_id']));
										$salida .="<a href='$accion'><sub>Nueva Atencion</sub></a><br>";
						}
						$this->salida .= "	<td align=\"center\">&nbsp;$salida</td>\n";





					$conteo_os=$this->ConteoOrdenesPaciente($pacientes[$i][4]);
					if($conteo_os==1)
					{
						$href=ModuloGetURL('app','CentralImpresionHospitalizacion','user','BuscarPorEstacion',array("estacion"=>$estacion[estacion_id],
						"paciente_id"=>$pacientes[$i][2],"tipo_id_paciente"=>$pacientes[$i][3],"nombre_estacion"=>$estacion[descripcion4],"ingreso"=>$pacientes[$i][4]));
						$this->salida .= "	<td align=\"center\"><a href=\"$href\"><img src=\"". GetThemePath() ."/images/pinactivo.png\" border='0'>&nbsp;OS</a></td>\n";
					}
					else
					{
						$this->salida .= "	<td align=\"center\"><img src=\"". GetThemePath() ."/images/editar.png\" border='0'>&nbsp;OS</td>\n";
					}
					
					}
					else
					{$this->salida.="<td colspan=\"8\" align=\"center\">EN CIRUGIA</td>\n";}

				//$this->salida .= "	<td align=\"center\">".$pacientes[$i][3]." ".$pacientes[$i][2]."</td>\n";
					$this->salida .= "</tr>\n";
					$_SESSION['ESTACION_ENF']['CONTEO']['INGRESO']=$_SESSION['ESTACION_ENF']['CONTEO']['INGRESO']+ 1;
			}

		}//pacientes por ingresar
return true;
}






     function Listado_Controles()
     {
		$estacion=$_REQUEST['estacion'];
		$pieza=$_REQUEST['pieza'];
		$cama=$_REQUEST['cama'];
		$nombre=urldecode($_REQUEST['nombre']);


		$controles=$this->GetControles($_REQUEST['ingreso']);
		if(!empty($controles)){
		$this->salida .= ThemeAbrirTabla("CONTROLES PROGRAMADOS - [ ".$estacion['descripcion5']." ]");
		$this->salida .= "<br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
		$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
		$this->salida .= "		<td>HABITACION</td>\n";
		$this->salida .= "		<td>CAMA</td>\n";
		$this->salida .= "		<td>PACIENTE</td>\n";
		$this->salida .= "	</tr><br>\n";


		$this->salida .= "	<tr ".$this->Lista($i).">\n";
		$this->salida .= "		<td align=\"center\"><b>".$pieza."</b></td>\n";
		$this->salida .= "		<td align=\"center\"><b>".$cama."</b></td>\n";
		$this->salida .= "		<td align=\"center\"><b>".$nombre."</b></td>\n";
		$this->salida .= "	</tr>\n";
		$this->salida .= "	<tr ".$this->Lista($i).">\n";
		$this->salida .= "		<td colspan='3'>&nbsp;\n";
		$this->salida .= "		</td></tr>\n";
		$this->salida .= "	<tr>\n";
		$this->salida .= "		<td colspan='3'>\n";
		for ($j=0;$j<sizeof($controles);$j++){
			$this->FrmControles(array("ingreso"=>$_REQUEST['ingreso'],"control_id"=>$controles[$j]['control_id']));
			$this->salida .= "<br>\n";
		}
  $this->salida .= "		</td></tr>\n";
  $this->salida .= "</table><br>\n";
	}
	else
	{
		$mensaje = "EL PACIENTE NO TIENE CONTROLES PROGRAMADOS";
		$titulo = "MENSAJE";
		$boton = "REGRESAR";
    if(UserGetUID()==0)
		{
				$href = ModuloGetURL('app','EstacionEnfermeriaPlantilla','user','CallMenu',array("estacion"=>$estacion));
		}
		else
		{
			$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$estacion));
		}
		$this->FormaMensaje($mensaje,$titulo,$href,$boton);
		return true;
	}


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
$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu</a><br>";
$this->salida .= themeCerrarTabla();
return true;
}


		/*funcion del mod estacione_controlpacientes*/
		/*
		*		FrmFrecuenciaControlesP
		*
		*		Aqui se manejan aquellos controles que son programados, frecuencias ??
		*
		*		@Author Jairo Duvan Diaz Martinez.
		*		@access Private
		*/
		function FrmFrecuenciaControlesP($control,$descripcion,$datos,$href_action_hora,$href_action_control,$ingreso_id)
		{
			$hora_inicio_turno=ModuloGetVar('app','EstacionEnfermeria','HoraInicioTurnoControles');
			$rango_turno=ModuloGetVar('app','EstacionEnfermeria','RangoVerTurnosControles');

			unset($_SESSION['ESTACION']['DIRECCION']['URL']);
			$_SESSION['ESTACION']['DIRECCION']['URL']=$href_action_hora;//url a donde se va a dirigir al tocar la fecha.

			if($ingreso_id OR $_SESSION['ESTACION_CONTROL']['INGRESO'])
			{
				$_SESSION['ESTACION_CONTROL']['INGRESO']=$ingreso_id;//colocamos el id para q filtre el pac con las fechas.
			}
			if(empty($href_action_hora))
			{
					$href_action_hora=$_SESSION['ESTACION_CONTROL']['URL_EXAMEN'];
			}
			else
			{
					$_SESSION['ESTACION_CONTROL']['URL_EXAMEN']=$href_action_hora;
			}
			$this->salida .= ThemeAbrirTabla("$descripcion - [ ".$datos['descripcion5']." ]");


               $datoscenso = $this->CallMetodoExterno('app','Censo','user','GetCensoTipo1',array('estacion'=>$datos['estacion_id']));
			if($datoscenso=="ShowMensaje"){unset($datoscenso);}
			$datoscenso = $this->GetPaciente_Consulta_Urgencia($datos['estacion_id'],$datoscenso);
			$datoscenso= $this->GetPacientesPendientesXHospitalizar_Plantilla($datos,1,$datoscenso,$ingreso_id);

			if(!empty($datoscenso['hospitalizacion']))
			{
				$this->salida .= "<br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
				$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "		<td>HABITACION</td>\n";
				$this->salida .= "		<td>CAMA</td>\n";
				$this->salida .= "		<td>PACIENTE</td>\n";
				$this->salida .= "		<td>HORARIO</td>\n";
				$this->salida .= "		<td>ACCI&Oacute;N</td>\n";
				$this->salida .= "	</tr>\n";

				
                    for($i=0; $i<sizeof($datoscenso['hospitalizacion']); $i++)
                    {
                         $INGRESAR = $datoscenso['hospitalizacion'][$i]['ingreso'];
                         if($datoscenso['hospitalizacion'][$i]['ingreso']==$_SESSION['GLOBAL']['VECTOR'][$INGRESAR])
                         {
                                   $vect_control=array();
                                   $controles=$this->GetControles($datoscenso['hospitalizacion'][$i]['ingreso']);
                                   $vect_control=$this->FindControles($controles,$control,$datoscenso['hospitalizacion'][$i]['ingreso']);
          
                                   if(is_array($controles) && $vect_control['ingreso']==$datoscenso['hospitalizacion'][$i]['ingreso']){
                                        $next_turno=array();
                                        $horas_no_cumplidas=array();
                                        $turno_prgdo=array();
                                        $turno_fecha_rango=array();
                                        $rango15=$rango30=0;
                                        $rango=1;
                                        $turno_hora="";
          
                                        $horas_no_cumplidas = $this->GetControlesProgramadosNoCumplidos($datos['estacion_id'],$datoscenso['hospitalizacion'][$i]['ingreso'],$control);
                                        if ($horas_no_cumplidas==="ShowMensaje") {
                                             return false;
                                        }
          
                                        $next_turno=$this->GetControlesProgramadosSiguientesTurnos($datos['estacion_id'],$datoscenso['hospitalizacion'][$i]['ingreso'],$control);
                                        if ($next_turno==="ShowMensaje") {
                                             return false;
                                        }
          
                                        $this->salida .= "<tr ".$this->Lista($i).">\n";
                                        if(empty($datoscenso['hospitalizacion'][$i]['pieza']) AND empty($datoscenso['hospitalizacion'][$i]['cama']))
                                        {
                                             $this->salida .= "	<td align=\"center\"><label class='label_mark'>No Ingresado</label></td>\n";
                                             $this->salida .= "	<td align=\"center\"><label class='label_mark'>No Ingresado</label></td>\n";
                                        }
                                        else
                                        {
                                             $this->salida .= "	<td align=\"center\">".$datoscenso['hospitalizacion'][$i]['pieza']."</td>\n";
                                             $this->salida .= "	<td align=\"center\">".$datoscenso['hospitalizacion'][$i]['cama']."</td>\n";
                                        }	
                                        $this->salida .= "	<td>".$datoscenso['hospitalizacion'][$i]['primer_nombre']." ".$datoscenso['hospitalizacion'][$i]['segundo_nombre']." ".$datoscenso['hospitalizacion'][$i]['primer_apellido']." ".$datoscenso['hospitalizacion'][$i]['segundo_apellido']."</td>\n";
                                        $this->salida .= "	<td align=\"center\">";
          
                                        if (empty($horas_no_cumplidas) && empty($next_turno)) {
                                                  $turno_hora.="--<br>";
                                        }
          
                                        for ($j=0;$j<sizeof($horas_no_cumplidas);$j++){
                                             $href = ModuloGetURL('app','EstacionE_ControlPacientes','user',$href_action_hora,array("referer_parameters"=>array("control"=>$control,"descripcion"=>$descripcion,"href_action_hora"=>$href_action_hora,"href_action_control"=>$href_action_control),"referer_name"=>"FrmFrecuenciaControlesP","estacion"=>$datos,"datos_estacion"=>array("pieza"=>$datoscenso['hospitalizacion'][$i]['pieza'],"cama"=>$datoscenso['hospitalizacion'][$i]['cama'],"NombrePaciente"=>$datoscenso['hospitalizacion'][$i]['primer_nombre']." ".$datoscenso['hospitalizacion'][$i]['segundo_nombre']." ".$datoscenso['hospitalizacion'][$i]['primer_apellido']." ".$datoscenso['hospitalizacion'][$i]['segundo_apellido'],"paciente_id"=>$datosPaciente['paciente_id'],"tipo_id_paciente"=>$datosPaciente['tipo_id_paciente'],"Hora"=>$horas_no_cumplidas[$j]['fecha'],"ingreso"=>$datoscenso['hospitalizacion'][$i]['ingreso'],"control_id"=>$control,"control_descripcion"=>$descripcion)));
                                             $turno_hora.=	"		<a class='TurnoInactivo' href=\"".$href."\">".$horas_no_cumplidas[$j]['fecha']."</a><br>";
                                        }//fin for
          
                                        for ($j=0;$j<sizeof($next_turno);$j++){
                                             if (!$j){
                                                  $href = ModuloGetURL('app','EstacionE_ControlPacientes','user',$href_action_hora,array("referer_parameters"=>array("control"=>$control,"descripcion"=>$descripcion,"href_action_hora"=>$href_action_hora,"href_action_control"=>$href_action_control),"referer_name"=>"FrmFrecuenciaControlesP","estacion"=>$datos,"datos_estacion"=>array("pieza"=>$datoscenso['hospitalizacion'][$i]['pieza'],"cama"=>$datoscenso['hospitalizacion'][$i]['cama'],"NombrePaciente"=>$datoscenso['hospitalizacion'][$i]['primer_nombre']." ".$datoscenso['hospitalizacion'][$i]['segundo_nombre']." ".$datoscenso['hospitalizacion'][$i]['primer_apellido']." ".$datoscenso['hospitalizacion'][$i]['segundo_apellido'],"paciente_id"=>$datosPaciente['paciente_id'],"tipo_id_paciente"=>$datosPaciente['tipo_id_paciente'],"Hora"=>$next_turno[$j]['fecha'],"ingreso"=>$datoscenso['hospitalizacion'][$i]['ingreso'],"control_id"=>$control,"control_descripcion"=>$descripcion)));
                                                  $turno_hora.=	"		<a class='TurnoActivo' href=\"".$href."\">".$next_turno[$j]['fecha']."</a><br>";
                                             }
                                             else{
                                                  list($fecha,$hora)=explode(" ",$next_turno[$j]['fecha']);
                                                  $turno_prgdo[]=$hora;
                                                  list($h,$m,$s)=explode(":",$hora);
                                                  if ($m==15 || $m==45){
                                                       $rango15=1;
                                                  }
                                                  elseif ($m==30) {
                                                       $rango30=1;
                                                  }
                                                  $turno_hora.=$next_turno[$j]['fecha']."<br>";
                                                  if ($j==1 || $j==(sizeof($next_turno))-1){
                                                       $turno_fecha_rango[]=$next_turno[$j]['fecha'];
                                                  }
                                             }
                                        }//fin for
                                        if ($rango15 && $rango30){
                                             $rango=15;
                                        }
                                        elseif ($rango30){
                                             $rango=30;
                                        }
                                        elseif ($rango15){
                                             $rango=15;
                                        }
                                        else{
                                             $rango=1;
                                        }
                                        $this->salida .= $turno_hora."</td>\n";
                                        if (count($turno_fecha_rango)==1){
                                             $turno_fecha_rango[]=$turno_fecha_rango[0];
                                        }
                                        elseif (empty($turno_fecha_rango) || (empty($next_turno) && empty($horas_no_cumplidas) && empty($turno_fecha_rango))){
                                             $turno_prgdo[]=date("H").":00:00";
                                             $turno_fecha_rango[]=$turno_fecha_rango[0]=date("Y-m-d H").":00:00";
                                        }
                                        $href = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmProgramarTurnos',array("href_action_hora"=>$href_action_hora,"href_action_control"=>$href_action_control,"rango"=>$rango,"turno_fecha_rango"=>$turno_fecha_rango,"turnos_prgmar"=>$turno_prgdo,"estacion"=>$datos,"datos_estacion"=>array("pieza"=>$datoscenso['hospitalizacion'][$i]['pieza'],"cama"=>$datoscenso['hospitalizacion'][$i]['cama'],"NombrePaciente"=>$datoscenso['hospitalizacion'][$i]['primer_nombre']." ".$datoscenso['hospitalizacion'][$i]['segundo_nombre']." ".$datoscenso['hospitalizacion'][$i]['primer_apellido']." ".$datoscenso['hospitalizacion'][$i]['segundo_apellido'],"paciente_id"=>$datosPaciente['paciente_id'],"tipo_id_paciente"=>$datosPaciente['tipo_id_paciente'],"ingreso"=>$datoscenso['hospitalizacion'][$i]['ingreso'],"control_id"=>$control,"control_descripcion"=>$descripcion)));
                                        $this->salida .= "	<td align=\"center\"><a href=\"".$href."\">PROGRAMAR</a>\n";
          
                                        switch ($control)
                                        {
                                             case 8://GLUCOMETR&Iacute;A
                                                       $liquidos_diario=array();
                                                       $liquidos_diario=$this->GetControlProgramadoGlucometria($datoscenso['hospitalizacion'][$i]['ingreso']);
                                                       if ($liquidos_diario==="ShowMensaje"){
                                                            return false;
                                                       }

                                                       if (!empty($liquidos_diario)){
                                                            $hrefResumen = ModuloGetURL('app','EstacionE_ControlPacientes','user',$href_action_control[0],array("paciente"=>$datoscenso['hospitalizacion'][$i],"estacion"=>$datos));
                                                            $this->salida .= "	<br><a href=\"".$hrefResumen."\">RESUMEN</a></td>\n";
                                                       }
                                                       else{
                                                            $this->salida .= "	<br>RESUMEN</td>\n";
                                                       }
                                             break;
                                             case 10://NEUROLOGICO
                                                       $liquidos_diario=array();
                                                       $liquidos_diario=$this->GetControlProgramadoHojaNeurologica($datoscenso['hospitalizacion'][$i]['ingreso']);
                                                       if ($liquidos_diario==="ShowMensaje"){
                                                            return false;
                                                       }

                                                       if (!empty($liquidos_diario)){
                                                            $hrefResumen = ModuloGetURL('app','EstacionE_ControlPacientes','user',$href_action_control[0],array("paciente"=>$datoscenso['hospitalizacion'][$i],"estacion"=>$datos));
                                                            $this->salida .= "	<br><a href=\"".$hrefResumen."\">RESUMEN</a></td>\n";
                                                       }
                                                       else{
                                                            $this->salida .= "	<br>RESUMEN</td>\n";
                                                       }
	                                        break;
                                   }
          
                                   $this->salida .= "</tr>\n";
                              }//End if
     	               }			
                                   
	               }//End for
				$this->salida .= "</table><br>\n";
				$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos));
				$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";
				$this->salida .= themeCerrarTabla();
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				return true;
			}
			else {
				$mensaje = "LA ESTACI?N [ ".$datos['descripcion5']." ] NO CUENTA CON PACIENTES.";
				$titulo = "MENSAJE";
				$boton = "REGRESAR";
				$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos));
				$this->FormaMensaje($mensaje,$titulo,$href,$boton);
				return true;
			}
		}//

		/*funcion del mod estacione_controlpacientes*/


		/*funcion del mod estacione_controlpacientes*/
		/*
		*		FondControles
		*
		*		@Author Arley Velasquez
		*		@access Private
		*/
		function FindControles($control,$valor,$ingreso)
		{
			foreach($control as $key =>$value)
			{


				if ($value['control_id']==$valor && $value['ingreso']==$ingreso)

					//$count=$this->GetControles($ingreso,$valor);
					//if($count > 0)
					{return $value;}//else{return false;}
			}
			return false;
		}
		/*funcion del mod estacione_controlpacientes*/


		 //funcion de estacion de enfermeria control de pacientes
	/*
	*		ListDietasSolicitadas
	*
	*		Muestra el listado de las dietas solicitadas
	*
	*		@Author Rosa Maria Angel
	*		@access Private
	*		@array datos de la estacion
	*		@return bool
	*/
	function ListDietasSolicitadas($datos_estacion)
	{
		$dietas = $this->GetDietasSolicitadas($datos_estacion);
		if(!$dietas){
			return false;
		}
		if($dietas === "ShowMensaje")
		{
			$mensaje = "NO SE ENCONTRARON REGISTROS DE DIETAS SOLICITADAS";
			$titulo = "MENSAJE";
			$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
			$boton = "VOLVER AL MEN&Uacute; ESTACION";
			$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
			return true;
		}
		else
		{
			//$action = ModuloGetURL('app','EstacionEnfermeria','user','InsertarPrescripcionDieta',array("datos_estacion"=>$datos_estacion));
			//$this->salida .= "<form name=\"FrmPrescripcionDietas\" method=\"POST\" action=\"$action\"><br>\n";
			$this->salida .= ThemeAbrirTabla('LISTADO DE DIETAS SOLICITADAS HOY - [ '.$datos_estacion['descripcion5'].' ]')."<BR>\n";
			$this->salida .= "	<table width='100%' border='0' cellspacing='2' cellpadding='2' class='modulo_table_list'>\n";
			$this->salida .= "		<tr class='modulo_table_list_title'>\n";
			$this->salida .= "			<td>HAB.</td>\n";
			$this->salida .= "			<td>CAMA</td>\n";
			$this->salida .= "			<td>PACIENTE</td>\n";
			//$this->salida .= "			<td>AYUNO</td>\n";
			//$this->salida .= "			<td>DIETA</td>\n";
			$this->salida .= "			<td>FECHA<br>REGISTRO</td>\n";
			$this->salida .= "			<td>OBSERVACIONES<br>SOLICITUD</td>\n";
			$this->salida .= "		</tr>\n";$i=o;
			foreach($dietas as $key => $value)
			{
				$paciente = $this->GetDatosClavePaciente($value['ingreso']);
				//list($fecha,$hora) = explode(" ",$value['fecha']);
				if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
				$this->salida .= "		<tr class='$estilo'>\n";
				$this->salida .= "			<td>".$paciente['pieza']."</td>\n";
				$this->salida .= "			<td>".$paciente['cama']."</td>\n";
				$this->salida .= "			<td>".$paciente['primer_nombre']." ".$paciente['segundo_nombre']." ".$paciente['primer_apellido']." ".$paciente['segundo_apellido']."</td>\n";
				//$this->salida .= "			<td>".$value['sw_ayuno']."&nbsp;</td>\n";
				//$this->salida .= "			<td>[ ".$value['abreviatura']." ] ".$value['descripcion']."</td>\n";
				$this->salida .= "			<td>".$value['fecha_registro']."</td>\n";
				$this->salida .= "			<td>".$value['observaciones']."&nbsp;</td>\n";
				$this->salida .= "		</tr>\n";
				$i++;
			}
			$this->salida .= "	</table>\n";
			$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
			$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
	}//ListDietasSolicitadas
  //funcion de estacion de enfermeria control de pacientes




//funcion del mod de estacion de enfermeria_controlpacientes
		/*
		*		ControlesPacientes
		*
		*		Aqui se manejan aquellos controles que no necesitan ser programados ??
		*
		*		@Author Arley Velasquez
		*		@access Private
		*/
		function ControlesPacientes($control, $estacion, $descripcion)
		{
			$hora_inicio_turno=ModuloGetVar('app','EstacionEnfermeria','HoraInicioTurnoControles');
			$rango_turno=ModuloGetVar('app','EstacionEnfermeria','RangoVerTurnosControles');

			switch ($control)
			{
				case 2 ://Caso Asistencia Ventilatoria
								$this->salida .= ThemeAbrirTabla($descripcion." - [ ".$estacion['descripcion5']." ]");
								$datoscenso = $this->CallMetodoExterno('app','Censo','user','GetCensoTipo1',array('estacion'=>$estacion['estacion_id']));
								$tiempo=0;

								if(!empty($datoscenso['hospitalizacion']))
								{
									$this->salida .= "<br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list\">\n";
									$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
									$this->salida .= "		<td>HABITACION</td>\n";
									$this->salida .= "		<td>CAMA</td>\n";
									$this->salida .= "		<td>PACIENTE</td>\n";
									$this->salida .= "		<td>ID</td>\n";
									$this->salida .= "		<td>ACCI&Oacute;N</td>\n";
									$this->salida .= "	</tr>\n";
									for($i=0; $i<sizeof($datoscenso['hospitalizacion']); $i++)
									{
										$this->salida .= "<tr ".$this->Lista($i).">\n";
										$this->salida .= "	<td align=\"center\">".$datoscenso['hospitalizacion'][$i]['pieza']."</td>\n";
										$this->salida .= "	<td align=\"center\">".$datoscenso['hospitalizacion'][$i]['cama']."</td>\n";
										$this->salida .= "	<td>".$datoscenso['hospitalizacion'][$i]['primer_nombre']." ".$datoscenso['hospitalizacion'][$i]['segundo_nombre']." ".$datoscenso['hospitalizacion'][$i]['primer_apellido']." ".$datoscenso['hospitalizacion'][$i]['segundo_apellido']."</td>\n";
										$this->salida .= "	<td align=\"center\">".$datoscenso['hospitalizacion'][$i]['tipo_id_paciente']." ".$datoscenso['hospitalizacion'][$i]['paciente_id']."</td>\n";
										$tiempo=date("H:i:s",mktime(date("H"),date("i"),0,date("m"),date("d"),date("Y")));
										$href = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmAsistenciaVentilatoria',array("estacion"=>$estacion,"datos_estacion"=>array("pieza"=>$datoscenso['hospitalizacion'][$i]['pieza'],"cama"=>$datoscenso['hospitalizacion'][$i]['cama'],"NombrePaciente"=>$datoscenso['hospitalizacion'][$i]['primer_nombre']." ".$datoscenso['hospitalizacion'][$i]['segundo_nombre']." ".$datoscenso['hospitalizacion'][$i]['primer_apellido']." ".$datoscenso['hospitalizacion'][$i]['segundo_apellido'],"paciente_id"=>$datosPaciente['paciente_id'],"tipo_id_paciente"=>$datosPaciente['tipo_id_paciente'],"Hora"=>$tiempo,"ingreso"=>$datoscenso['hospitalizacion'][$i]['ingreso'],"control_id"=>$control,"control_descripcion"=>$descripcion)));
										$this->salida .= "	<td align=\"center\"><a href='".$href."'>ADICIONAR</a></td>\n";
										$this->salida .= "</tr>\n";
									}//End for
									$this->salida .= "</table><br>\n";
									$this->salida .= "<div class=\"label\" align=\"center\">TOTAL PACIENTES = ".sizeof($datoscenso['hospitalizacion'])."\n";
									$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$estacion));
									$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";
									$this->salida .= themeCerrarTabla();
									return true;
								}
								else {
									$mensaje = "LA ESTACI?N [ ".$estacion['descripcion5']." ] NO CUENTA CON PACIENTES.";
									$titulo = "MENSAJE";
									$boton = "REGRESAR";
									$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$estacion));
									$this->FormaMensaje($mensaje,$titulo,$href,$boton);
									return true;
								}
				break;
				case 6 ://LIQUIDOS
				  			//$datoscenso = $this->GetCensoTipo($estacion['estacion_id']);
								$fecha=date("Y-m-d H:i:s");
								$this->salida .= ThemeAbrirTabla($descripcion." - [ ".$estacion['descripcion5']." ]");
								$datoscenso = $this->CallMetodoExterno('app','Censo','user','GetCensoTipo1',array('estacion'=>$estacion['estacion_id']));
								$datoscensoI=$this->GetPacientesPendientesXHospitalizar_Plantilla($estacion);

								$this->salida .= "<center>\n";
								$this->salida .= "				<table class='modulo_table_title' border='0' width='90%'>\n";
								$this->salida .= "					<tr class='modulo_table_title'>\n";
								$this->salida .= "						<td>Empresa</td>\n";
								$this->salida .= "						<td>Centro Utilidad</td>\n";
								$this->salida .= "						<td>Unidad Funcional</td>\n";
								$this->salida .= "						<td>Departamento</td>\n";
								$this->salida .= "					</tr>\n";
								$this->salida .= "					<tr class='modulo_list_oscuro'>\n";
								$this->salida .= "						<td>".$estacion['descripcion1']."</td>\n";
								$this->salida .= "						<td>".$estacion['descripcion2']."</td>\n";
								$this->salida .= "						<td>".$estacion['descripcion3']."</td>\n";
								$this->salida .= "						<td>".$estacion['descripcion4']."</td>\n";
								$this->salida .= "					</tr>\n";
								$this->salida .= "				</table><br>\n";


								if(is_array($datoscenso) OR is_array($datoscensoI))
								{
									$this->salida .= "<br><table align=\"center\" width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
									$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
									$this->salida .= "		<td></td>\n";
									$this->salida .= "		<td>HABITACION</td>\n";
									$this->salida .= "		<td>CAMA</td>\n";
									$this->salida .= "		<td>PACIENTE</td>\n";
									$this->salida .= "		<td colspan='6'>ACCI&Oacute;N</td>\n";
									$this->salida .= "	</tr>\n";


									$mostrar ="\n<script language='javascript'>\n";
									$mostrar.="function mOvr(src,clrOver) {;\n";
									$mostrar.="src.style.background = clrOver;\n";
									$mostrar.="}\n";

									$mostrar.="function mOut(src,clrIn) {\n";
									$mostrar.="src.style.background = clrIn;\n";
									$mostrar.="}\n";
									$mostrar.="</script>\n";
									$this->salida .="$mostrar";

									$backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');


								if(is_array($datoscensoI['ingresar']))
								{
										//for de pacientes pendientes por ingresar
									for($i=0; $i<sizeof($datoscensoI['ingresar']); $i++)
									{
										$liquidos_acumulados=array();
										$liquidos_diario=array();
										if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";

										$this->salida .= "<tr class=$estilo 	onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>\n";

										$this->salida .= "	<td  align=\"center\"><img src=\"". GetThemePath() ."/images/ingresar.png\" border='0' title='INGRESO ESTACION'></td>\n";
										$this->salida .= "	<td  colspan='2' align=\"center\"><label class=label_mark>PENDIENTE INGRESO</label></td>\n";


										$this->salida .= "	<td><div title='".$datoscensoI['ingresar'][$i]['tipo_id_paciente']." ".$datoscensoI['ingresar'][$i]['paciente_id']."'>".$datoscensoI['ingresar'][$i]['primer_nombre']." ".$datoscensoI['ingresar'][$i]['segundo_nombre']." ".$datoscensoI['ingresar'][$i]['primer_apellido']." ".$datoscensoI['ingresar'][$i]['segundo_apellido']."</div></td>\n";
										$href = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmIngresarDatosLiquidos',array("referer_name"=>"ControlesPacientes","referer_parameters"=>array("control_id"=>$control,"estacion"=>$estacion,"control_descripcion"=>$descripcion),"estacion"=>$estacion,"datos_estacion"=>array("pieza"=>'No Ingresado',"cama"=>'No Ingresado',"NombrePaciente"=>$datoscensoI['ingresar'][$i]['primer_nombre']." ".$datoscensoI['ingresar'][$i]['segundo_nombre']." ".$datoscensoI['ingresar'][$i]['primer_apellido']." ".$datoscensoI['ingresar'][$i]['segundo_apellido'],"paciente_id"=>$datosPaciente['paciente_id'],"tipo_id_paciente"=>$datosPaciente['tipo_id_paciente'],"Hora"=>$fecha,"ingreso"=>$datoscensoI['ingresar'][$i]['ingreso'],"control_id"=>$control,"control_descripcion"=>$descripcion)));
										$this->salida .= "		<td><img src=\"".GetThemePath()."/images/entregabolsa.png\" border=0 title='Administrar liquidos al paciente &nbsp;".$datoscensoI['ingresar'][$i]['primer_nombre']." ".$datoscensoI['ingresar'][$i]['segundo_nombre']."'  width=14 heigth=14></td>\n";
										$this->salida .= "	<td align=\"center\"><div title='Administrar liquidos al paciente &nbsp;".$datoscensoI['ingresar'][$i]['primer_nombre']." ".$datoscensoI['ingresar'][$i]['primer_apellido']."'><a href='".$href."'>ADICIONAR</a></div>\n";

										$liquidos_diario=$this->GetFechasLiquidos($datoscensoI['ingresar'][$i]['ingreso'],$hora_inicio_turno,$rango_turno,0);
										if ($liquidos_diario===false){
          					return false;
										}
										$this->salida .= "		<td><img src=\"".GetThemePath()."/images/fecha_inicio.png\" border=0 title='En esta opci?n se revisa el balance diario de liquidos'  width=14 heigth=14></td>\n";
										if (!empty($liquidos_diario))
										{
											$hrefBalanceDiario = ModuloGetURL('app','EstacionE_ControlPacientes','user',"CallFrmControlLiquidos",array("paciente"=>$datoscensoI['ingresar'][$i],"estacion"=>$estacion));
											$this->salida .= "	<td align=\"center\"><a href=\"".$hrefBalanceDiario."\">BALANCE DIARIO</a></td>\n";
										}
										else{
											$this->salida .= "	<td align=\"center\">BALANCE DIARIO</td>\n";//<br>BALANCE ACUMULADO
										}
										/**************************/
										$resultLD=$this->GetFechasLiquidos($datoscensoI['ingresar'][$i]['ingreso'],'','',1);
										if ($resultLD===false) {
												return false;
										}
										$this->salida .= "		<td><img src=\"".GetThemePath()."/images/fecha_fin.png\" border=0 title='En esta opci?n se revisa el balance acumulado de liquidos'  width=14 heigth=14></td>\n";
										if(!$resultLD->EOF)
										{
											$hrefBalanceXDias = ModuloGetURL('app','EstacionE_ControlPacientes','user',"CallFrmControlLiquidosXDias",array("control_id"=>$control,"paciente"=>$datoscensoI['ingresar'][$i],"estacion"=>$estacion));
											$this->salida .= "	<td align=\"center\"><a href=\"".$hrefBalanceXDias."\">BALANCE ACUMULADO</a></td>\n";
										}
										else
										{
											$this->salida .= "	<td align=\"center\">BALANCE ACUMULADO</td>\n";
										}
										/***************************/
										$this->salida .= "</tr>\n";
									}//End for pacientes pendientes por ingresar
								}





									//for de hospitalizados
									if(is_array($datoscenso['hospitalizacion']))
									{
													for($i=0; $i<sizeof($datoscenso['hospitalizacion']); $i++)
													{
														$liquidos_acumulados=array();
														$liquidos_diario=array();
														if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";

														$this->salida .= "<tr class=$estilo 	onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>\n";

														$traslado=$this->Revisar_Si_esta_trasladado($datoscenso['hospitalizacion'][$i]['ingreso']);
														$info=$this->RevisarSi_Es_Egresado($datoscenso['hospitalizacion'][$i]['ingreso_dpto_id']);

														if($info[1]==2)//si es 2 egreso efectuado
														{
															$this->salida .= "	<td  align=\"center\"><img src=\"". GetThemePath() ."/images/egresook.png\" border='0'></td>\n";
														}
														elseif($info[1]=='1' OR $info[1]=='0')//es 1 enfermera-0 medico
														{
															$this->salida .= "	<td  align=\"center\"><img src=\"". GetThemePath() ."/images/egreso.png\" border='0'></td>\n";
														}
														else
														{
															if($traslado >0)
															{
																$this->salida .= "	<td  align=\"center\"><img src=\"". GetThemePath() ."/images/uf.png\" border='0'></td>\n";
															}
															else
															{
																$this->salida .= "	<td  align=\"center\"><img src=\"". GetThemePath() ."/images/honorarios.png\" border='0'></td>\n";
															}
														}


														$this->salida .= "	<td align=\"center\">".$datoscenso['hospitalizacion'][$i]['pieza']."</td>\n";
														$this->salida .= "	<td align=\"center\">".$datoscenso['hospitalizacion'][$i]['cama']."</td>\n";
														$this->salida .= "	<td><div title='".$datoscenso['hospitalizacion'][$i]['tipo_id_paciente']." ".$datoscenso['hospitalizacion'][$i]['paciente_id']."'>".$datoscenso['hospitalizacion'][$i]['primer_nombre']." ".$datoscenso['hospitalizacion'][$i]['segundo_nombre']." ".$datoscenso['hospitalizacion'][$i]['primer_apellido']." ".$datoscenso['hospitalizacion'][$i]['segundo_apellido']."</div></td>\n";
														$href = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmIngresarDatosLiquidos',array("referer_name"=>"ControlesPacientes","referer_parameters"=>array("control_id"=>$control,"estacion"=>$estacion,"control_descripcion"=>$descripcion),"estacion"=>$estacion,"datos_estacion"=>array("pieza"=>$datoscenso['hospitalizacion'][$i]['pieza'],"cama"=>$datoscenso['hospitalizacion'][$i]['cama'],"NombrePaciente"=>$datoscenso['hospitalizacion'][$i]['primer_nombre']." ".$datoscenso['hospitalizacion'][$i]['segundo_nombre']." ".$datoscenso['hospitalizacion'][$i]['primer_apellido']." ".$datoscenso['hospitalizacion'][$i]['segundo_apellido'],"paciente_id"=>$datosPaciente['paciente_id'],"tipo_id_paciente"=>$datosPaciente['tipo_id_paciente'],"Hora"=>$fecha,"ingreso"=>$datoscenso['hospitalizacion'][$i]['ingreso'],"control_id"=>$control,"control_descripcion"=>$descripcion)));
														$this->salida .= "		<td><img src=\"".GetThemePath()."/images/entregabolsa.png\" border=0 title='Administrar liquidos al paciente &nbsp;".$datoscenso['hospitalizacion'][$i]['primer_nombre']." ".$datoscenso['hospitalizacion'][$i]['segundo_nombre']."'  width=14 heigth=14></td>\n";
														$this->salida .= "	<td align=\"center\"><div title='Administrar liquidos al paciente &nbsp;".$datoscenso['hospitalizacion'][$i]['primer_nombre']." ".$datoscenso['hospitalizacion'][$i]['primer_apellido']."'><a href='".$href."'>ADICIONAR</a></div>\n";

														$liquidos_diario=$this->GetFechasLiquidos($datoscenso['hospitalizacion'][$i]['ingreso'],$hora_inicio_turno,$rango_turno,0);
															/*if ($liquidos_diario===false){
															return false;
														}*/
														$this->salida .= "		<td><img src=\"".GetThemePath()."/images/fecha_inicio.png\" border=0 title='En esta opci?n se revisa el balance diario de liquidos'  width=14 heigth=14></td>\n";
														if (!empty($liquidos_diario))
														{
															$hrefBalanceDiario = ModuloGetURL('app','EstacionE_ControlPacientes','user',"CallFrmControlLiquidos",array("paciente"=>$datoscenso['hospitalizacion'][$i],"estacion"=>$estacion));
															$this->salida .= "	<td align=\"center\"><a href=\"".$hrefBalanceDiario."\">BALANCE DIARIO</a></td>\n";
														}
														else{
															$this->salida .= "	<td align=\"center\">BALANCE DIARIO</td>\n";//<br>BALANCE ACUMULADO
														}

														/**************************/
														$resultLD=$this->GetFechasLiquidos($datoscenso['hospitalizacion'][$i]['ingreso'],'','',1);
														/*if ($resultLD===false) {
															return false;
														}*/

														$this->salida .= "		<td><img src=\"".GetThemePath()."/images/fecha_fin.png\" border=0 title='En esta opci?n se revisa el balance acumulado de liquidos'  width=14 heigth=14></td>\n";
														if(!$resultLD->EOF)
														{
															$hrefBalanceXDias = ModuloGetURL('app','EstacionE_ControlPacientes','user',"CallFrmControlLiquidosXDias",array("control_id"=>$control,"paciente"=>$datoscenso['hospitalizacion'][$i],"estacion"=>$estacion));
															$this->salida .= "	<td align=\"center\"><a href=\"".$hrefBalanceXDias."\">BALANCE ACUMULADO</a></td>\n";
														}
														else
														{
															$this->salida .= "	<td align=\"center\">BALANCE ACUMULADO</td>\n";
														}
														/***************************/
														$this->salida .= "</tr>\n";

													}//End for hospitalizados
										}



										//for de pacientes en consulta
									if(is_array($datoscenso['urgencias']))
									{
												for($i=0; $i<sizeof($datoscenso['urgencias']); $i++)
												{
													$liquidos_acumulados=array();
													$liquidos_diario=array();
													if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";

													$this->salida .= "<tr class=$estilo 	onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>\n";

													if($datoscenso['urgencias'][$i]['sw_estado']==1)
													{
															$this->salida .= "	<td  align=\"center\"><img src=\"". GetThemePath() ."/images/consulta_ur.png\" border='0' title='PACIENTE EN CONSULTA'></td>\n";
															$this->salida .= "	<td  colspan='2' align=\"center\"><label class=label_mark>CONSULTA</label></td>\n";
													}
													elseif($datoscenso['urgencias'][$i]['sw_estado']==7)
													{
															$this->salida .= "	<td  align=\"center\"><img src=\"". GetThemePath() ."/images/egreso.png\" border='0' title='EGRESO ESTACION'></td>\n";
															$this->salida .= "	<td  colspan='2' align=\"center\"><label class=label_mark>CONSULTA - ATENCION ENFERMERIA</label></td>\n";
													}

													$this->salida .= "	<td><div title='".$datoscenso['urgencias'][$i]['tipo_id_paciente']." ".$datoscenso['urgencias'][$i]['paciente_id']."'>".$datoscenso['urgencias'][$i]['primer_nombre']." ".$datoscenso['urgencias'][$i]['segundo_nombre']." ".$datoscenso['urgencias'][$i]['primer_apellido']." ".$datoscenso['urgencias'][$i]['segundo_apellido']."</div></td>\n";
													$href = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmIngresarDatosLiquidos',array("referer_name"=>"ControlesPacientes","referer_parameters"=>array("control_id"=>$control,"estacion"=>$estacion,"control_descripcion"=>$descripcion),"estacion"=>$estacion,"datos_estacion"=>array("pieza"=>'No Ingresado',"cama"=>'No Ingresado',"NombrePaciente"=>$datoscenso['urgencias'][$i]['primer_nombre']." ".$datoscenso['urgencias'][$i]['segundo_nombre']." ".$datoscenso['urgencias'][$i]['primer_apellido']." ".$datoscenso['urgencias'][$i]['segundo_apellido'],"paciente_id"=>$datosPaciente['paciente_id'],"tipo_id_paciente"=>$datosPaciente['tipo_id_paciente'],"Hora"=>$fecha,"ingreso"=>$datoscenso['urgencias'][$i]['ingreso'],"control_id"=>$control,"control_descripcion"=>$descripcion)));
													$this->salida .= "		<td><img src=\"".GetThemePath()."/images/entregabolsa.png\" border=0 title='Administrar liquidos al paciente &nbsp;".$datoscenso['urgencias'][$i]['primer_nombre']." ".$datoscenso['urgencias'][$i]['segundo_nombre']."'  width=14 heigth=14></td>\n";
													$this->salida .= "	<td align=\"center\"><div title='Administrar liquidos al paciente &nbsp;".$datoscenso['urgencias'][$i]['primer_nombre']." ".$datoscenso['urgencias'][$i]['primer_apellido']."'><a href='".$href."'>ADICIONAR</a></div>\n";

													$liquidos_diario=$this->GetFechasLiquidos($datoscenso['urgencias'][$i]['ingreso'],$hora_inicio_turno,$rango_turno,0);
													/*if ($liquidos_diario===false){
														return false;
													}*/
													$this->salida .= "		<td><img src=\"".GetThemePath()."/images/fecha_inicio.png\" border=0 title='En esta opci?n se revisa el balance diario de liquidos'  width=14 heigth=14></td>\n";
													if (!empty($liquidos_diario))
													{
														$hrefBalanceDiario = ModuloGetURL('app','EstacionE_ControlPacientes','user',"CallFrmControlLiquidos",array("paciente"=>$datoscenso['urgencias'][$i],"estacion"=>$estacion));
														$this->salida .= "	<td align=\"center\"><a href=\"".$hrefBalanceDiario."\">BALANCE DIARIO</a></td>\n";
													}
													else{
														$this->salida .= "	<td align=\"center\">BALANCE DIARIO</td>\n";//<br>BALANCE ACUMULADO
													}
													/**************************/
													$resultLD=$this->GetFechasLiquidos($datoscenso['urgencias'][$i]['ingreso'],'','',1);
													/*if ($resultLD===false) {
														return false;
													}*/
													$this->salida .= "		<td><img src=\"".GetThemePath()."/images/fecha_fin.png\" border=0 title='En esta opci?n se revisa el balance acumulado de liquidos'  width=14 heigth=14></td>\n";
													if(!$resultLD->EOF)
													{
														$hrefBalanceXDias = ModuloGetURL('app','EstacionE_ControlPacientes','user',"CallFrmControlLiquidosXDias",array("control_id"=>$control,"paciente"=>$datoscenso['urgencias'][$i],"estacion"=>$estacion));
														$this->salida .= "	<td align=\"center\"><a href=\"".$hrefBalanceXDias."\">BALANCE ACUMULADO</a></td>\n";
													}
													else
													{
														$this->salida .= "	<td align=\"center\">BALANCE ACUMULADO</td>\n";
													}
													/***************************/
													$this->salida .= "</tr>\n";
												}//End for pacientes en consulta
									}

									$this->salida .= "</table><br>\n";
									$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$estacion));
									$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>\n";
									$this->salida .= themeCerrarTabla();
									return true;
								}
								else {
									$mensaje = "LA ESTACI&Oacute;N [ ".$estacion['descripcion5']." ] NO CUENTA CON PACIENTES.";
									$titulo = "MENSAJE";
									$boton = "REGRESAR";
									$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$estacion));
									$this->FormaMensaje($mensaje,$titulo,$href,$boton);
									$this->salida .= themeCerrarTabla();
									return true;
								}
				break;
				case 15 ://Caso signos vitales
								$this->salida .= ThemeAbrirTabla($descripcion." - [ ".$estacion['descripcion5']." ]");
								//$datoscenso = $this->GetCensoTipo($estacion['estacion_id']);
								$datoscenso = $this->CallMetodoExterno('app','Censo','user','GetCensoTipo1',array('estacion'=>$estacion['estacion_id']));
								$tiempo=0;
								if(!empty($datoscenso['hospitalizacion']))
								{
									$this->salida .= "<br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list\">\n";
									$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
									$this->salida .= "		<td>HABITACION</td>\n";
									$this->salida .= "		<td>CAMA</td>\n";
									$this->salida .= "		<td>PACIENTE</td>\n";
									$this->salida .= "		<td>ID</td>\n";
									$this->salida .= "		<td>ACCI&Oacute;N</td>\n";
									$this->salida .= "	</tr>\n";
									for($i=0; $i<sizeof($datoscenso['hospitalizacion']); $i++)
									{
										$this->salida .= "<tr ".$this->Lista($i).">\n";
										$this->salida .= "	<td align=\"center\">".$datoscenso['hospitalizacion'][$i]['pieza']."</td>\n";
										$this->salida .= "	<td align=\"center\">".$datoscenso['hospitalizacion'][$i]['cama']."</td>\n";
										$this->salida .= "	<td>".$datoscenso['hospitalizacion'][$i]['primer_nombre']." ".$datoscenso['hospitalizacion'][$i]['segundo_nombre']." ".$datoscenso['hospitalizacion'][$i]['primer_apellido']." ".$datoscenso['hospitalizacion'][$i]['segundo_apellido']."</td>\n";
										$this->salida .= "	<td align=\"center\">".$datoscenso['hospitalizacion'][$i]['tipo_id_paciente']." ".$datoscenso['hospitalizacion'][$i]['paciente_id']."</td>\n";
										$tiempo=date("Y-m-d H:i:s",mktime(date("H"),date("i"),0,date("m"),date("d"),date("Y")));
										$href = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmSignosVitales',array("estacion"=>$estacion,"datos_estacion"=>array("pieza"=>$datoscenso['hospitalizacion'][$i]['pieza'],"cama"=>$datoscenso['hospitalizacion'][$i]['cama'],"NombrePaciente"=>$datoscenso['hospitalizacion'][$i]['primer_nombre']." ".$datoscenso['hospitalizacion'][$i]['segundo_nombre']." ".$datoscenso['hospitalizacion'][$i]['primer_apellido']." ".$datoscenso['hospitalizacion'][$i]['segundo_apellido'],"paciente_id"=>$datosPaciente['paciente_id'],"tipo_id_paciente"=>$datosPaciente['tipo_id_paciente'],"Hora"=>$tiempo,"ingreso"=>$datoscenso['hospitalizacion'][$i]['ingreso'],"control_id"=>$control,"control_descripcion"=>$descripcion)));
										$this->salida .= "	<td align=\"center\"><a href='".$href."'>ADICIONAR</a></td>\n";
										$this->salida .= "</tr>\n";
									}//End for
									$this->salida .= "</table><br>\n";
									$this->salida .= "<div class=\"label\" align=\"center\">TOTAL PACIENTES = ".sizeof($datoscenso['hospitalizacion'])."\n";
									$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$estacion));
									$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";
									$this->salida .= themeCerrarTabla();
									return true;
								}
								else {
									$mensaje = "LA ESTACI&Oacute;N [ ".$estacion['descripcion5']." ] NO CUENTA CON PACIENTES.";
									$titulo = "MENSAJE";
									$boton = "REGRESAR";
									$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$estacion));
									$this->FormaMensaje($mensaje,$titulo,$href,$boton);
									return true;
								}
				break;
   			case 24://Caso TRANSFUSIONES
								$this->salida .= ThemeAbrirTabla($descripcion." - [ ".$estacion['descripcion5']." ]");
								//$datoscenso = $this->GetCensoTipo($estacion['estacion_id']);
								$Transfusiones = $this->GetPacientesControlTransfusiones($estacion['departamento'],$estacion['estacion_id'],24);
								$tiempo=0;

								if(!empty($Transfusiones) && $Transfusiones != "ShowMensaje")
								{
									$this->salida .= "<br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
									$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
									$this->salida .= "		<td>HABITACION</td>\n";
									$this->salida .= "		<td>CAMA</td>\n";
									$this->salida .= "		<td>PACIENTE</td>\n";
									$this->salida .= "		<td>ID</td>\n";
									$this->salida .= "		<td>ACCI&Oacute;N</td>\n";
									$this->salida .= "	</tr>\n";
									foreach($Transfusiones as $key => $value)
									{
										$datosPaciente = $this->GetDatosClavePaciente($value[ingreso]);
										$this->salida .= "<tr ".$this->Lista($i).">\n";
										$this->salida .= "	<td align=\"center\">".$datosPaciente['pieza']."</td>\n";
										$this->salida .= "	<td align=\"center\">".$datosPaciente['cama']."</td>\n";
										$this->salida .= "	<td>".$datosPaciente['primer_nombre']." ".$datosPaciente['segundo_nombre']." ".$datosPaciente['primer_apellido']." ".$datosPaciente['segundo_apellido']."</td>\n";
										$this->salida .= "	<td align=\"center\">".$datosPaciente['tipo_id_paciente']." ".$datosPaciente['paciente_id']."</td>\n";
										$tiempo=date("H:i:s",mktime(date("H"),date("i"),0,date("m"),date("d"),date("Y")));//"referer_name"=>,
										$href = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmTransfusiones',array("estacion"=>$estacion,"datos_estacion"=>array("pieza"=>$datosPaciente['pieza'],"cama"=>$datosPaciente['cama'],"NombrePaciente"=>$datosPaciente['primer_nombre']." ".$datosPaciente['segundo_nombre']." ".$datosPaciente['primer_apellido']." ".$datosPaciente['segundo_apellido'],"paciente_id"=>$datosPaciente['paciente_id'],"tipo_id_paciente"=>$datosPaciente['tipo_id_paciente'],"ingreso"=>$datosPaciente['ingreso'],"control_id"=>$control,"control_descripcion"=>$descripcion)));
										$this->salida .= "	<td align=\"center\"><a href='".$href."'>ADICIONAR</a></td>\n";
										$this->salida .= "</tr>\n";
									}//End for
									$this->salida .= "</table><br>\n";
									$this->salida .= "<div class=\"label\" align=\"center\">TOTAL PACIENTES CON CONTROL DE TRANSFUSIONES = ".sizeof($Transfusiones)."\n";
									$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$estacion));
									$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";
									$this->salida .= themeCerrarTabla();
									return true;
								}
								else
								{
									$mensaje = "LA ESTACI&Oacute;N [ ".$estacion['descripcion5']." ] NO CUENTA CON PACIENTES CON CONTROL DE TRANSFUSION.";
									$titulo = "MENSAJE";
									$boton = "REGRESAR";
									$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$estacion));
									$this->FormaMensaje($mensaje,$titulo,$href,$boton);
									return true;
								}
				break;
				default ://Caso Controles Generales
									$this->salida .= ThemeAbrirTabla($descripcion." - [ ".$estacion['descripcion5']." ]");
								//$datoscenso = $this->GetCensoTipo($estacion['estacion_id']);
								$datoscenso = $this->CallMetodoExterno('app','Censo','user','GetCensoTipo1',array('estacion'=>$estacion['estacion_id']));
								$controles=array();

								if(empty($datoscenso['hospitalizacion']) || $datoscenso === "ShowMensaje")
								{
									$mensaje = "LA ESTACI&Oacute;N [ ".$estacion['descripcion5']." ] NO CUENTA CON PACIENTES.";
									$titulo = "MENSAJE";
									$boton = "REGRESAR";
									$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$estacion));
									$this->FormaMensaje($mensaje,$titulo,$href,$boton);
									return true;
								}

								for($i=0; $i<sizeof($datoscenso['hospitalizacion']); $i++)
								{
									$controles=$this->GetControles($datoscenso['hospitalizacion'][$i]['ingreso']);
									
                                             if(!empty($controles)){
										$this->salida .= "<br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list\">\n";
										$this->salida .= "	<tr class=\"modulo_table_title\">\n";
										$this->salida .= "		<td>HABITACION</td>\n";
										$this->salida .= "		<td>CAMA</td>\n";
										$this->salida .= "		<td>PACIENTE</td>\n";
										$this->salida .= "	</tr>\n";

										$this->salida .= "	<tr ".$this->Lista($i).">\n";
										$this->salida .= "		<td align=\"center\"><b>".$datoscenso['hospitalizacion'][$i]['pieza']."</b></td>\n";
										$this->salida .= "		<td align=\"center\"><b>".$datoscenso['hospitalizacion'][$i]['cama']."</b></td>\n";
										$this->salida .= "		<td><b>".$datoscenso['hospitalizacion'][$i]['primer_nombre']." ".$datoscenso['hospitalizacion'][$i]['segundo_nombre']." ".$datoscenso['hospitalizacion'][$i]['primer_apellido']." ".$datoscenso['hospitalizacion'][$i]['segundo_apellido']."</b></td>\n";
										$this->salida .= "	</tr>\n";

										$this->salida .= "	<tr ".$this->Lista($i+1).">\n";
										$this->salida .= "		<td colspan='3'>\n";
										for ($j=0;$j<sizeof($controles);$j++){
											$this->FrmControles(array("ingreso"=>$datoscenso['hospitalizacion'][$i]['ingreso'],"control_id"=>$controles[$j]['control_id']));
										}
										$this->salida .= "		</td></tr>\n";
										$this->salida .= "</table><br>\n";
									}
								}
								$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$estacion));
								$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";
								$this->salida .= themeCerrarTabla();
				break;
			}
			return true;
		}//FIN fin ControlesPacientes



		/*
		*		FrmControlLiquidos
		*
		*		Muestra un listado con los totales de liquidos administrados y eliminados y balance diario
		*
		*		@Author Rosa Maria Angel
		*		@access private
		*		@param array datos del paciente
		*		@param array datos del ambiente
		*		@return bool
		*/
		function FrmControlLiquidos($paciente,$datos_estacion)
		{
			$hora_inicio_turno = ModuloGetVar('app','EstacionEnfermeria','HoraInicioTurnoControles');
			$rango_turno = ModuloGetVar('app','EstacionEnfermeria','RangoVerTurnosControles');

			list($hh,$mm, $ss) = explode(" ",$hora_inicio_turno);
			$NextDay = date("Y-m-d H:i:s", mktime(($hh+($rango_turno)), ($mm-1), $ss, date("m"),(date("d")),date("Y")));
			$vectorAdm = $this->GetTotalAdministrados($paciente[ingreso],date("Y-m-d $hora_inicio_turno"),$NextDay);
			$vectorElim = $this->GetTotalEliminados($paciente[ingreso],date("Y-m-d $hora_inicio_turno"),$NextDay);
			$Diuresis = $this->GetDiuresis($paciente[ingreso],date("Y-m-d $hora_inicio_turno"),$NextDay);

			$this->salida .= ThemeAbrirTabla("CONTROL DE LIQUIDOS ADMINISTRADOS Y ELIMINADOS")."<br>";
			$this->salida .= "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_title\">\n";
			$this->salida .= "		<tr class=\"modulo_table_title\">\n";
			$this->salida .= "			<td>HABITACION</td>\n";
			$this->salida .= "			<td>CAMA</td>\n";
			$this->salida .= "			<td>PACIENTE</td>\n";
			$this->salida .= "			<td>ID</td>\n";
			$this->salida .= "			<td>CUENTA</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
			$this->salida .= "			<td>".$paciente[pieza]."</td>\n";
			$this->salida .= "			<td>".$paciente[cama]."</td>\n";
			$this->salida .= "			<td>".$paciente[primer_nombre]." ".$paciente[segundo_nombre]." ".$paciente[primer_apellido]." ".$paciente[segundo_apellido]."</td>\n";
			$this->salida .= "			<td>".$paciente[tipo_id_paciente]." ".$paciente[paciente_id]."</td>\n";
			$this->salida .= "			<td>".$paciente[numerodecuenta]."</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table><br>\n";
			$this->salida .= " <table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list\">\n";
			$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "		<td>FECHA</td>\n";
			$this->salida .= "		<td>HORA</td>\n";
			$this->salida .= "		<td>TOTAL ADMINISTRADOS</td>\n";
			$this->salida .= "		<td>TOTAL ELIMINADOS</td>\n";
			$this->salida .= "		<td>BALANCE <br>HORARIO</td>\n";
			$this->salida .= "		<td>BALANCE <br>ACUMULADO</td>\n";
			$this->salida .= "	</tr>\n";

			$i = 0;
			$nextHora = $hora_inicio_turno;
			while($i < $rango_turno)
			{
				$nextHora = date("H:i:s",mktime(($hh+$i),0,0,date("m"),date("d"),date("Y")));

				$fecha = date("d-M-Y $nextHora",mktime(($hh+$i),0,0,date("m"),date("d"),date("Y")));
				$fechita=explode(" ",$fecha);
				$hour=explode(":",$fechita[1]);
				if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
				$this->salida .= "<tr align='center'>\n";

      			if($fechita[0]!=$fechota)
				{
					$this->salida .= "	<td class=modulo_list_claro  rowspan='1' align='center' width='15%' ><label class='label'>".$fechita[0]."</label>&nbsp;&nbsp;&nbsp;<p><img src='".GetThemePath()."/images/ultimo.png' width=14 height='14' border='0'></td>\n";
					$fechota=$fechita[0];
					$estilo="hc_table_submodulo_list_title";
				}
				else
				{
					$this->salida .= "	<td class=modulo_list_claro rowspan='1' align='center' width='15%'>&nbsp;</td>\n";
					$fechota=$fechita[0];

				}

				if($hour[0]==17 or $hour[0]==18 or $hour[0]==19 or $hour[0]==20 or $hour[0]==21 or $hour[0]==22 or $hour[0]==23
				or $hour[0]=='00' or $hour[0]=='01'  or $hour[0]=='02' or $hour[0]=='03' or $hour[0]=='04'  or $hour[0]=='05' )
				{
				  $t="<label class='label_error'>";
					$t1="</label>";
				}else{$t='';$t1='';}
				$this->salida .= "	<td class='$estilo' align='center' width='15%' nowrap='yes'>$t".$hour[0]."$t1</td>\n";


				if($vectorAdm[$nextHora][fila1]==0)
				{
					$this->salida .= "	<td class='$estilo' ></td>\n";
				}
				else
				{
					$this->salida .= "	<td class='$estilo' >".number_format($vectorAdm[$nextHora][fila1], 2, ',', '.')."</td>\n";
				}

				if($vectorElim[$nextHora][fila1]==0)
				{
					$this->salida .= "	<td class='$estilo'></td>\n";
				}
				else
				{
					$this->salida .= "	<td class='$estilo' >".number_format($vectorElim[$nextHora][fila1], 2, ',', '.')."</td>\n";
				}

				$this->salida .= "	<td class='$estilo' >".number_format(($vectorAdm[$nextHora][fila1]-$vectorElim[$nextHora][fila1]), 2, ',', '.')."</td>\n";
				$this->salida .= "	<td class='$estilo' >".number_format(($vectorAdm[$nextHora][total]-$vectorElim[$nextHora][total]), 2, ',', '.')."</td>\n";

				$this->salida .= "</tr>\n";
				if(($vectorAdm[$nextHora][total]-$vectorElim[$nextHora][total])!=0){
					$Balance = ($vectorAdm[$nextHora][total]-$vectorElim[$nextHora][total]);
				}
				if($vectorAdm[$nextHora][total]>0){
					$VerEnlaceAdmin = 1;
				}
				if($vectorElim[$nextHora][total]>0){
					$VerEnlaceElim = 1;
				}
				$i++;
			}
			//unset($vectorAdm);unset($VerEnlaceElim);
			$this->salida .= "</table><br><br>\n";


			$prevDay = date("Y-m-d H:i:s", mktime(($hh-($rango_turno)), $mm, $ss, date("m"),(date("d")),date("Y")));
			$toDay = date("Y-m-d H:i:s", mktime(($hh), ($mm-1), $ss, date("m"),(date("d")),date("Y")));
			$BalancePrevio = $this->GetBalancePrevio($paciente[ingreso],$toDay,$prevDay,$hora_inicio_turno,$rango_turno);

			$BalanceAcum = $BalancePrevio[balance] + $Balance;

			$this->salida .= "<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class='modulo_table_list'>\n";
			$this->salida .= "	<tr><td class='modulo_table_title'>RESUMEN</td></tr>\n";
			$this->salida .= "	<tr>\n";
			$this->salida .= "		<td>\n";
			$this->salida .= "			<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td>\n";
			if($VerEnlaceAdmin){
				$hrefADMIN = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmLiquidosAdministrados',array("paciente"=>$paciente,"estacion"=>$datos_estacion));
				$this->salida .= "					<div><a href='$hrefADMIN' class='label'>Ver Detalle de Liquidos Administrados</a>\n";
			}
			if($VerEnlaceElim){
				$hrefELIM = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmLiquidosEliminados',array("paciente"=>$paciente,"estacion"=>$datos_estacion));
				$this->salida .= "					<div><a href='$hrefELIM' class='label'>Ver Detalle de Liquidos Eliminados</a>\n";
			}
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td width='50%'>\n";
			$this->salida .= "						<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class='modulo_table_list'>\n";
			$this->salida .= "							<TR ".$this->Lista(0)."><td class='label'>BALANCE</td><td class='label' align='right'>".number_format($Balance, 2, ',', '.')." cc</td></TR>\n";
			$this->salida .= "							<TR ".$this->Lista(1)."><td class='label'>BALANCE PREVIO</td><td class='label' align='right'>".number_format($BalancePrevio[balance], 2, ',', '.')." cc</td></TR>\n";
			$this->salida .= "							<TR ".$this->Lista(2)."><td class='label'>BALANCE ACUM</td><td class='label' align='right'>".number_format($BalanceAcum, 2, ',', '.')." cc</td></TR>\n";
			$this->salida .= "						</table>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "					<td width='50%'>\n";
			if (!$this->GetPesoPaciente($paciente[ingreso]))
			{
				return false;
			}
			elseif ($this->GetPesoPaciente($paciente[ingreso])==-1)
			{
				$paciente['NombrePaciente']=$paciente[primer_nombre]." ".$paciente[segundo_nombre]." ".$paciente[primer_apellido]." ".$paciente[segundo_apellido];
				$paciente['Hora']=date("Y-m-d H:i:s");
				$href = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmSignosVitales',array("estacion"=>$datos_estacion,"datos_estacion"=>$paciente,"referer_name"=>"FrmControlLiquidos","referer_parameters"=>array("paciente"=>$paciente,"estacion"=>$datos_estacion)));
				$this->salida .= "					<div align='center' class='label'><br><a href='".$href."'>Ingresar Peso</a><br>";
			}
			else
			{
				$this->salida .= "					<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class='modulo_table_list'>\n";
				$this->salida .= "						<TR ".$this->Lista(0)."><td class='label'>DIURESIS</td><td class='label' align='right'>".$Diuresis." cc</td></TR>\n";
				$this->salida .= "						<TR ".$this->Lista(1)."><td class='label'>DIURESIS/HORA</td><td class='label' align='right'>".number_format(($Diuresis/24), 2, ',', '.')." cc</td></TR>\n";
				$this->salida .= "						<TR ".$this->Lista(2)."><td class='label'>DIURESIS/cc/Kg/H</td><td class='label' align='right'>".number_format((($Diuresis/24)/$this->GetPesoPaciente($paciente[ingreso])), 2, ',', '.')." cc</td></TR>\n";
				$this->salida .= "					</table>\n";
			}
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "			</table>\n";
			$this->salida .= "		</td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "</table>\n";

			$href = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallControlesPacientes',array("control_id"=>6,"estacion"=>$datos_estacion,"control_descripcion"=>"CONTROL LIQUIDOS"));
			//$href = ModuloGetURL('app','EstacionEnfermeria','user','CallFrmFrecuenciaControlesP',array("control"=>6,"descripcion"=>"CONTROL LIQUIDOS","estacion"=>$datos_estacion,"href_action_hora"=>"CallFrmIngresarDatosLiquidos","href_action_control"=>array(0=>"CallFrmControlLiquidos",1=>"CallFrmControlLiquidosXDias")));
			$this->salida .= "<div class='normal_10' align='center'><br>\n";
			$this->salida .= "	<a href='".$href."'>Volver a Control de Liquidos</a>&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;";
			$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
			$this->salida .= "<a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>\n";
			$this->salida .= themeCerrarTabla();
			return true;
		}


 //funcion de estacion de enfermeria control_pacientes
		/*
		*		FrmControles
		*
		*		@Author Arley Velasquez
		*		@access Private
		*/
		function FrmControles($datos)//CHANGE
		{

			$ctrlPosicion = array();
			$controles = $this->GetControles($datos['ingreso']);
			switch($datos['control_id'])
			{
				case 1:
								$ctrlPosicion=$this->FindControles($controles,$datos['control_id'],$datos['ingreso']);
								if (!$this->ControlPosicion($ctrlPosicion))
								{
									return false;
								}
				break;
				case 2:
								$ctrlOxig=$this->FindControles($controles,$datos['control_id'],$datos['ingreso']);
								if (!$this->ControlOxig($ctrlOxig))
									return false;
				break;
				case 3:
								$ctrlReposo=$this->FindControles($controles,$datos['control_id'],$datos['ingreso']);
								if (!$this->ControlReposo($ctrlReposo))
									return false;
				break;
				case 4:
								$ctrlTerResp=$this->FindControles($controles,$datos['control_id'],$datos['ingreso']);
								if (!$this->ControlTerResp($ctrlTerResp))
									return false;
				break;
				case 5:
								$ctrlCurTerm=$this->FindControles($controles,$datos['control_id'],$datos['ingreso']);
								if (!$this->ControlCurTerm($ctrlCurTerm))
									return false;
				break;
				case 6:
								$ctrlLiquidos=$this->FindControles($controles,$datos['control_id'],$datos['ingreso']);
								if (!$this->ControlLiquidos($ctrlLiquidos))
									return false;
				break;
				case 7:
								$ctrlTA=$this->FindControles($controles,$datos['control_id'],$datos['ingreso']);
								if (!$this->ControlTA($ctrlTA))
									return false;
				break;
				case 8:
								$ctrlGlucometria=$this->FindControles($controles,$datos['control_id'],$datos['ingreso']);

								if (!$this->ControlGlucometria($ctrlGlucometria))
								return false;
				break;
				case 9:
								$ctrlCuraciones=$this->FindControles($controles,$datos['control_id'],$datos['ingreso']);
								if (!$this->ControlCuraciones($ctrlCuraciones))
									return false;
				break;
				case 10:
								$ctrlNeurologico=$this->FindControles($controles,$datos['control_id'],$datos['ingreso']);
								if (!$this->ControlNeurologico($ctrlNeurologico))
									return false;
				break;
				case 11:
								if (!IncludeLib('datospaciente'))
								{
									$this->error = "Error al cargar la libreria [datospaciente].";
									$this->mensajeDeError = "datospaciente";
									return false;
								}//las 5 lineas anteriores son para poder llamar a GetDatosPaciente
								$datos_hc = GetDatosPaciente("","",$datos['ingreso'],"","");
								$query="SELECT * FROM gestacion WHERE tipo_id_paciente='".$datos_hc['tipoidpaciente']."' AND paciente_id='".$datos_hc['paciente_id']."' ";
								$resultado=$dbconn->Execute($query);
								if (!$resultado) {
									$this->error = "Error al ejecutar el query <br>".$query;
									$this->mensajeDeError = $query;
									return false;
								}
								if ($data->estado)
								{
									$ctrlParto=$this->FindControles($controles,$datos['control_id'],$datos['ingreso']);
									if (!$this->ControlParto($ctrlParto))
										return false;
								}
				break;
				case 12:
								$ctrlPerAbdominal=$this->FindControles($controles,$datos['control_id'],$datos['ingreso']);
								if (!$this->ControlPerAbdominal($ctrlPerAbdominal))
									return false;
				break;
				case 13:
								$ctrlPerCefalico=$this->FindControles($controles,$datos['control_id'],$datos['ingreso']);
								if (!$this->ControlPerCefalico($ctrlPerCefalico))
									return false;
				break;
				case 14:
								$ctrlPerExtremidades=$this->FindControles($controles,$datos['control_id'],$datos['ingreso']);
								if (!$this->ControlPerExtremidades($ctrlPerExtremidades))
									return false;
				break;

				case 25:
								$ctrlPresDieta=$this->FindControles($controles,$datos['control_id'],$datos['ingreso']);
								if (!$this->ControlPrescripcionDietas($ctrlPresDieta))
									return false;
				break;
			}
			//$href = ModuloGetURL('app','EstacionEnfermeria','user','CallControlesPacientes',array("caso"=>2,"estacion"=>$datos_estacion));
			//$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver a Listado de Controles</a><br>";
			return true;
		}



		//Funcion que muestra en detalle las prescripciones de dietas que ha realizado el medico.
		function ControlPrescripcionDietas($control)
		{

  				$dietas_d=$this->GetCControlDietasDetalle($control);
						if ($dietas_d===false || !is_array($dietas_d))
							return false;
						$this->salida .= "					<table width='100%' border='0' class='modulo_table_list'>\n";
						$this->salida .= "		<tr>\n";
						$this->salida .= "			<td width='100%' align='left' colspan='2' class='modulo_table_title'>PRESCRIPCION DE DIETAS</td>\n";
						$this->salida .= "		</tr>\n";

						if(sizeof($dietas_d)>1)
						{
									foreach ($dietas_d as $key => $value)
									{
										$datos.=$value['descripcion'].",";
									}

									$this->salida .= "						<tr ".$this->Lista($key)."'>\n";
									$this->salida .= "							<td width='20%'>Tipo de Dieta</td>\n";
									$this->salida .= "							<td width='80%'>$datos</td>\n";
									$this->salida .= "						</tr>\n";unset($datos);
						}
						else{
									foreach ($dietas_d as $key => $value)
									{
										$this->salida .= "						<tr ".$this->Lista($key)."'>\n";
										$this->salida .= "							<td width='20%'>Tipo de Dieta</td>\n";
										$this->salida .= "							<td width='80%'>".$value['descripcion']."</td>\n";
										$this->salida .= "						</tr>\n";
									}
						}
						$data=$this->GetCControlDietas($control);
						if ($data===false || !is_array($data))
							return false;
						if (!empty($data['observaciones'])) {
							$this->salida .= "						<tr ".$this->Lista(2)."'>\n";
							$this->salida .= "							<td width='20%'>Observaci?n</td>\n";
							$this->salida .= "							<td width='80%' align='justify'>".$data['observaciones']."</td>\n";
							$this->salida .= "						</tr>\n";
						}
						$this->salida .= "					</table>\n";
						return true;
	}


		//funciones de estacion de enfermeria_controlpacientes
		/*
		*		ControlPosicion
		*
		*		@Author Arley Velasquez
		*		@access Private
		*/
		function ControlPosicion($control)
		{
			if (!empty($control))
			{
				/*OJO ESTO LO CONVERTI EN LA FUNCION VerificaPosicionesPaciente
				$query="SELECT * FROM hc_posicion_paciente WHERE evolucion_id=".$control['evolucion_id'];
				$resultado=$dbconn->Execute($query);
				if (!$resultado)
				{
					$this->error = "Error al consultar las posiciones del paciente en \"hc_posicion_paciente\" con evolucion_id=".$control['evolucion_id'];
					$this->mensajeDeError = $query;
					return false;
				}
				$data=$resultado->FetchNextObject($toUpper=false);
				if (!$resultado->RecordCount())*/
				$data = $this->VerificaPosicionesPaciente($control['evolucion_id']);
				if(!$data){
					return false;
				}
				if(!is_array($data))
				{
					$this->error = "Error, el paciente no cuenta con registros en \"hc_posicion_paciente\" con evolucion_id=".$control['evolucion_id'];
					$this->mensajeDeError = $query;
					return false;
				}

				$controles=$this->GetControlPosicion($data[posicion_id],0);//$controles=$this->GetControlPosicion($data->posicion_id,0);

				$this->salida .= "	<table width='100%' align='justif1y' border='0' class='modulo_table_list'>";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td width='100%' align='left' colspan='2' class='modulo_table_title'>POSICION DEL PACIENTE</td>\n";
				$this->salida .= "		</tr>\n";
				if (!empty($data[posicion_id]))//if (!empty($data->posicion_id))
				{
					$this->salida .= "						<tr ".$this->Lista(1)."'>\n";
					$this->salida .= "							<td width='20%'>Posici&oacute;n</td>\n";
					$this->salida .= "							<td width='80%'>".$controles[0]['descripcion']."</td>\n";
					$this->salida .= "						</tr>\n";
					if (!empty($data[observaciones]))//if (!empty($data->observaciones))
					{
						$this->salida .= "						<tr ".$this->Lista(2)."'>\n";
						$this->salida .= "							<td width='20%'>Observaci&oacute;n</td>\n";
						$this->salida .= "							<td width='80%' align='justify'>".$data[observaciones]."</td>\n";
						//$this->salida .= "							<td width='80%' align='justify'>".$data->observaciones."</td>\n";
						$this->salida .= "						</tr>\n";
					}
				}
				$this->salida .= "	</table>\n";
			}
			return true;
		}


		/*
		*		FrmLiquidosEliminados
		*
		*		Muestra el detalle de los liquidos eliminados al paciente X en la fecha Y
		*
		*		@Author Rosa Maria Angel
		*		@access private
		*		@param array datos del paciente
		*		@param array datos de la estacion
		*		@param array otros datos necesarios
		*		@return bool
		*/
		function FrmLiquidosEliminados($paciente,$estacion,$datosAlternos)
		{
			$hora_inicio_turno = ModuloGetVar('app','EstacionEnfermeria','HoraInicioTurnoControles');
			$rango_turno = ModuloGetVar('app','EstacionEnfermeria','RangoVerTurnosControles');
			list($hh,$mms, $ss) = explode(" ",$hora_inicio_turno);

			if($datosAlternos)
			{//viene desde la forma de RESUMEN ACUMULADOS y se muestran los liquidos del turno de una fecha espec&iacute;fica
				list($yy,$mm,$dd) = explode("-",$datosAlternos[fecha]);
				$NextDay = date("Y-m-d H:i:s", mktime(date($hh), date($mms)-1, date($ss), date($mm),(date($dd)+1),date($yy)));
				$vLiquido = $this->GetLiquidosEliminados($paciente[ingreso],date("$datosAlternos[fecha] $hora_inicio_turno"),$NextDay);
			}
			else
			{//se muestran los liquidos del turno actual
				$NextDay = date("Y-m-d H:i:s", mktime(date($hh), date($mms)-1, date($ss), date("m"),(date("d")+1),date("Y")));
				$vLiquido = $this->GetLiquidosEliminados($paciente[ingreso],date("Y-m-d $hora_inicio_turno"),$NextDay);
			}

			$this->salida .= ThemeAbrirTabla("LIQUIDOS ELIMINADOS")."<br>";
			$this->salida .= "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_title\">\n";
			$this->salida .= "		<tr class=\"modulo_table_title\">\n";
			$this->salida .= "			<td>HABITACION</td>\n";
			$this->salida .= "			<td>CAMA</td>\n";
			$this->salida .= "			<td>PACIENTE</td>\n";
			$this->salida .= "			<td>ID</td>\n";
			$this->salida .= "			<td>CUENTA</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
			$this->salida .= "			<td>".$paciente[pieza]."</td>\n";
			$this->salida .= "			<td>".$paciente[cama]."</td>\n";
			$this->salida .= "			<td>".$paciente[primer_nombre]." ".$paciente[segundo_nombre]." ".$paciente[primer_apellido]." ".$paciente[segundo_apellido]."</td>\n";
			$this->salida .= "			<td>".$paciente[tipo_id_paciente]." ".$paciente[paciente_id]."</td>\n";
			$this->salida .= "			<td>".$paciente[numerodecuenta]."</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table><br>\n";

			$this->salida .= " <table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list\">\n";
			$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "		<td>FECHA</td>\n";
			$this->salida .= "		<td>LIQUIDO</td>\n";
			$this->salida .= "		<td>CANTIDAD</td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
			foreach($vLiquido as $key => $value)
			{
				$colspan = sizeof($value);
				foreach($value as $A => $B)
				{ 
					if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
					$this->salida .= "	<tr class=".$estilo.">\n";
					if($colspan == sizeof($value))
					{
						if($B[fechas] == date("Y-m-d")) {
							$fecha = "HOY";
						}
						elseif($B[fechas] == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y")))){
							$fecha = "AYER ";
						}
						elseif($B[fechas] == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")+1), date("Y")))){
							$fecha = "MA?ANA ";
						}
						else {
							$fecha = $B[fechas];
						}
						$this->salida .= "		<td align='center' rowspan=".$colspan." width='20%'>".$fecha." ".date("H:i:s",mktime($key,0,0,date("m"),date("d"),date("Y")))."</td>\n";
					}
					//ultimo arreglo,revisamos sila via de eliminaci?n urinaria(0) es 1 espontanea
					//2 sonda
					if($B[tipo_liquido_eliminado_id]==0)
					{
						if($B[via]==1)
						{$via='<label class=label_mark>&nbsp;&nbsp;&nbsp;VIA ESPONTANEA</label>';}else{$via="<label class=label_mark>&nbsp;&nbsp;&nbsp;VIA SONDA</label>";}
					}
					else
					{$via='';}
					$this->salida .= "		<td>".$B[descripcion]." $via</td>\n";

					if($B[sumas] != 0.00){//ojo, luego cambiar la condicion por if campo tipo_liquido_eliminado.deposicion == 0
						$this->salida .= "		<td align='center'>".$B[sumas]."</td>\n";
					}
					else{
						$this->salida .= "		<td align='center'>".$B[deposicion]."</td>\n";
					}
					$this->salida .= "	</tr>\n";
					$colspan--;
					$TotalElim+=$B[sumas];
				}
				$i++;
			}
			$this->salida .= "<tr class='modulo_table_title'><td colspan='2' align='center'>TOTAL LIQUIDOS ELIMINADOS</td><td align='center'>".number_format($TotalElim,2,',','.')."</td></tr>\n";
			$this->salida .= "<tr align='center' class='modulo_table_title'><td colspan='2' align='center'>TOTAL P&Eacute;RDIDA INSENSIBLE</td><td align='center'>".number_format(($TotalElim+(14*$this->GetPesoPaciente($paciente[ingreso]))),2,',','.')."</td></tr>\n";
			$this->salida .= "</table>\n";
   if(!$datosAlternos)
			{
				$href = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmControlLiquidos',array("paciente"=>$paciente,"estacion"=>$estacion));
				$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>REGRESAR</a><br>";
			}
			else
			{
				$href = ModuloGetURL('app','EstacionE_ControlPacientes','user',$datosAlternos[referer_name],$datosAlternos[referer_parameters]);
				$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>REGRESAR</a><br>";
			}
			$this->salida .= themeCerrarTabla();
			return true;
		}//fin FrmLiquidosEliminados



		/*
		*		FrmControlLiquidosXDias
		*
		*		Muestra los totales de liquidos administrados y eliminados desde la fecha de ingreso del paciente
		*
		*		@Author Rosa Maria Angel
		*		@access private
		*		@param array datos del paciente
		*		@param array datos de la estacion
		*		@return bool-array
		*/
		function FrmControlLiquidosXDias($paciente,$estacion)
		{
			$hora_inicio_turno=ModuloGetVar('app','EstacionEnfermeria','HoraInicioTurnoControles');
			$rango_turno=ModuloGetVar('app','EstacionEnfermeria','RangoVerTurnosControles');
			$this->salida .= ThemeAbrirTabla("CONTROL LIQUIDOS ACUMULADOS")."<br>";
			$this->salida .= "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_title\">\n";
			$this->salida .= "		<tr class=\"modulo_table_title\">\n";
			$this->salida .= "			<td>HABITACION</td>\n";
			$this->salida .= "			<td>CAMA</td>\n";
			$this->salida .= "			<td>PACIENTE</td>\n";
			$this->salida .= "			<td>ID</td>\n";
               if(!empty($paciente[numerodecuenta]))
               {
				$this->salida .= "			<td>CUENTA</td>\n";
               }
               else
               {
				$this->salida .= "			<td>INGRESO</td>\n";               
               }
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
			if(!empty($paciente[pieza]))
               {
               	$this->salida .= "			<td>".$paciente[pieza]."</td>\n";               
               }else
               {
               	$this->salida .= "			<td>No Ingresado</td>\n";               
               }
			
               if(!empty($paciente[cama]))
               {
               	$this->salida .= "			<td>".$paciente[cama]."</td>\n";               
               }else
               {
               	$this->salida .= "			<td>No Ingresado</td>\n";               
               }

			$this->salida .= "			<td>".$paciente[primer_nombre]." ".$paciente[segundo_nombre]." ".$paciente[primer_apellido]." ".$paciente[segundo_apellido]."</td>\n";
			$this->salida .= "			<td>".$paciente[tipo_id_paciente]." ".$paciente[paciente_id]."</td>\n";
               if(!empty($paciente[numerodecuenta]))
               {
				$this->salida .= "			<td>".$paciente[numerodecuenta]."</td>\n";
               }
               else
               {
				$this->salida .= "			<td>".$paciente[ingreso]."</td>\n";               
               }
			
               $this->salida .= "		</tr>\n";
			$this->salida .= "	</table><br>\n";
			$this->salida .= "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
			$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "		<td>FECHA</td>\n";
			$this->salida .= "		<td>TOTAL ADM</td>\n";
			$this->salida .= "		<td>LIQUIDOS ADM</td>\n";
			$this->salida .= "		<td>TOTAL ELIM</td>\n";
			$this->salida .= "		<td>LIQUIDOS ELIM</td>\n";
			$this->salida .= "		<td>BALANCE</td>\n";
			$this->salida .= "		<td>DIURESIS cc/Kg/H</td>\n";
			$this->salida .= "	</tr>\n";

			$fechaIngreso = $this->GetFechaIngreso($paciente[ingreso]);
			$OJO = $this->GetBalancesAcum($paciente[ingreso],$fechaIngreso,$hora_inicio_turno);
			$OJO = array_reverse ($OJO);//invierto el orden del vevtor para mostrar desde el registreo ma&aacute;s revciente al ma&aacute;s viejo
			foreach($OJO as $key=>$value)
			{
				if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
				if($value[0][totalAdmin] || $value[0][totalElim] || $value[0][balance] || $value[1])
				{
					$this->salida .= "	<tr class=".$estilo." align='center'>\n";

					if($key == date("Y-m-d")) {
						$fecha = "HOY";
					}
					elseif($key == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y")))){
						$fecha = "AYER";
					}
					else{
						$fecha = $key;
					}
					$this->salida .= "		<td>".$fecha."</td>\n";
					$this->salida .= "		<td>".number_format($value[0][totalAdmin], 2, ',', '.')."</td>\n";
					if($value[0][totalAdmin] > 0){
						$hrefADMIN = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmLiquidosAdministrados',array("paciente"=>$paciente,"estacion"=>$datos_estacion,"datosAlternos"=>array('fecha'=>$key,'referer_name'=>'CallFrmControlLiquidosXDias','referer_parameters'=>array('paciente'=>$paciente,'estacion'=>$estacion))));
						$this->salida .= "		<td><a href='$hrefADMIN'>Ver detalle</a></td>\n";
					}
					else{
						$this->salida .= "		<td>Ver detalle</td>\n";
					}

					if($VerEnlaceElim){
						$hrefELIM = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmLiquidosEliminados',array("paciente"=>$paciente,"estacion"=>$datos_estacion));
						$this->salida .= "			<div><a href='$hrefELIM' class='label'>Ver Detalle de Liquidos Eliminados</a>\n";
					}
					$this->salida .= "		<td>".number_format($value[0][totalElim], 2, ',', '.')."</td>\n";
					if($value[0][totalElim] > 0){
						$hrefELIM = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmLiquidosEliminados',array("paciente"=>$paciente,"estacion"=>$datos_estacion,"datosAlternos"=>array('fecha'=>$key,'referer_name'=>'CallFrmControlLiquidosXDias','referer_parameters'=>array('paciente'=>$paciente,'estacion'=>$estacion))));
						$this->salida .= "		<td><a href='$hrefELIM'>Ver detalle</a></td>\n";
					}
					else{
						$this->salida .= "		<td>Ver detalle</td>\n";
					}
					$this->salida .= "		<td>".number_format($value[0][balance], 2, ',', '.')."</td>\n";
					if (!$this->GetPesoPaciente($paciente[ingreso]))
					{
						return false;
					}
					elseif ($this->GetPesoPaciente($paciente[ingreso])==-1)
					{
						$paciente['NombrePaciente']=$paciente[primer_nombre]." ".$paciente[segundo_nombre]." ".$paciente[primer_apellido]." ".$paciente[segundo_apellido];
						$paciente['Hora']=date("Y-m-d H:i:s");
						$href = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmSignosVitales',array("estacion"=>$estacion,"datos_estacion"=>$paciente,"referer_name"=>"FrmControlLiquidosxdias","referer_parameters"=>array("paciente"=>$paciente,"estacion"=>$estacion)));
						$this->salida .= "<td align='center'><a href='".$href."'>Ingresar Peso</a></td>";
					}
					else
					{
						$this->salida .= "		<td>".number_format((($value[1]/24)/$this->GetPesoPaciente($paciente[ingreso])), 2, ',', '.')."</td>\n";
					}
					$this->salida .= "	</tr>\n";
					$i++;
				}
			}
			$this->salida .= "</table>\n";
			$href = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallControlesPacientes',array("control_id"=>6,"estacion"=>$estacion,"control_descripcion"=>"CONTROL LIQUIDOS"));
			$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>REGRESAR</a><br>";
			$this->salida .= themeCerrarTabla();
			return true;
		}//fin CallFrmControlLiquidosXDias()




		//estacion de enfermeria control_pacientes
		/*
		*		ControlOxig
		*
		*		@Author Arley Velasquez
		*		@access Private
		*/
		function ControlOxig($control)
		{
			$pfj=$this->frmPrefijo;
			list($dbconn) = GetDBconn();

			if (!empty($control))
			{
				/*OJO, CAMBIE ESTO POR LA FUNCION VerificaOxigenoterapiaPaciente
				$query="SELECT * FROM hc_oxigenoterapia WHERE evolucion_id=".$control['evolucion_id'];
				$resultado=$dbconn->Execute($query);
				if (!$resultado)
				{
					$this->error = "Error al consultar las posiciones del paciente en \"hc_oxigenoterapia\" con evolucion_id=".$control['evolucion_id'];
					$this->mensajeDeError = $query;
					return false;
				}
				$data=$resultado->FetchNextObject($toUpper=false);
				if (!$resultado->RecordCount())*/
				$data = $this->VerificaOxigenoterapiaPaciente($control['evolucion_id']);
				if(!$data){
					return false;
				}
				if(!is_array($data))
				{
					$this->error = "Error, el paciente no cuenta con registros en \"hc_oxigenoterapia\" con evolucion_id=".$control['evolucion_id'];
					$this->mensajeDeError = $query;
					return false;
				}

				$metodo=$this->GetControlOxiMetodo($data[metodo_id],0);//$metodo=$this->GetControlOxiMetodo($data->metodo_id,0);
				$concentracion=$this->GetControlOxiConcentraciones($data[concentracion_id],0);//$concentracion=$this->GetControlOxiConcentraciones($data->concentracion_id,0);
				$flujo=$this->GetControlOxiFlujo($data[flujo_id],0);//$flujo=$this->GetControlOxiFlujo($data->flujo_id,0);
				$contador=1;

				$this->salida .= "	<table width='100%' align='justify' border='0' class='modulo_table_list'>";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td width='100%' align='left' class='modulo_table_title' colspan='2'>OXIGENOTERAPIA</td>\n";
				$this->salida .= "		</tr>\n";
				if (!empty($data[metodo_id]))//if (!empty($data->metodo_id))
				{
					$this->salida .= "						<tr ".$this->Lista($contador)."'>\n";
					$this->salida .= "							<td width='20%'>M&eacute;todo</td>\n";
					$this->salida .= "							<td width='80%'>".$metodo[0]['descripcion']."</td>\n";
					$this->salida .= "						</tr>\n";
					$contador++;
				}
				if (!empty($data[concentracion_id]))//if (!empty($data->concentracion_id))
				{
					$this->salida .= "						<tr ".$this->Lista($contador)."'>\n";
					$this->salida .= "							<td width='20%'>Concentraci&oacute;n</td>\n";
					$this->salida .= "							<td width='80%'>".$concentracion[0]['descripcion']."</td>\n";
					$this->salida .= "						</tr>\n";
					$contador++;
				}
				if (!empty($data[flujo_id]))//if (!empty($data->flujo_id))
				{
					$this->salida .= "						<tr ".$this->Lista($contador)."'>\n";
					$this->salida .= "							<td width='20%'>Flujo</td>\n";
					$this->salida .= "							<td width='80%'>".$flujo[0]['descripcion']."</td>\n";
					$this->salida .= "						</tr>\n";
					$contador++;
				}
				if (!empty($data[observaciones]))//if (!empty($data->observaciones))
				{
					$this->salida .= "						<tr ".$this->Lista($contador)."'>\n";
					$this->salida .= "							<td width='20%'>Observaci&oacute;n</td>\n";
					$this->salida .= "							<td width='80%' align='justify'>".$data->observaciones."</td>\n";
					$this->salida .= "						</tr>\n";
				}
				$this->salida .= "		</tr>\n";
				$this->salida .= "	</table>\n";
			}
			return true;
		}

		//estacion de enfermeria control_pacientes
		/*
		*		ControlReposo
		*
		*
		*		@Author Arley Velasquez
		*		@access Private
		*/
		function ControlReposo($control)
		{
			$pfj=$this->frmPrefijo;
			list($dbconn) = GetDBconn();

			if (!empty($control))
			{
				$query="SELECT * FROM hc_reposo_paciente_detalle WHERE evolucion_id=".$control['evolucion_id'];
				$query2="SELECT * FROM hc_reposo_paciente WHERE evolucion_id=".$control['evolucion_id'];
				$resultado2=$dbconn->Execute($query2);
				$resultado=$dbconn->Execute($query);
				if (!$resultado2)
				{
					$this->error = "Error al consultar la tabla \"hc_reposo_paciente_detalle\" con evolucion_id=".$control['evolucion_id'];
					$this->mensajeDeError = $query;
					return false;
				}
				if (!$resultado)
				{
					$this->error = "Error al consultar la tabla \"hc_reposo_paciente_detalle\" con evolucion_id=".$control['evolucion_id'];
					$this->mensajeDeError = $query;
					return false;
				}
				if (!$resultado->RecordCount())
				{
					$this->error = "Error, el paciente no cuenta con registros en \"hc_reposo_paciente_detalle\" con evolucion_id=".$control['evolucion_id'];
					$this->mensajeDeError = $query;
					return false;
				}

				$this->salida .= "	<table width='100%' align='justify' border='0' class='modulo_table_list'>";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td width='100%' align='left' class='modulo_table_title' colspan='2'>REPOSO DEL PACIENTE</td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "		<tr>\n";
				while ($data=$resultado->FetchNextObject($toUpper=false))
				{
					$controles=$this->GetControlReposo($data->tipo_reposo_id,0);
					if (!empty($data->tipo_reposo_id))
					{
						$this->salida .= "						<tr ".$this->Lista(1)."'>\n";
						$this->salida .= "							<td width='20%'>Tipo de Reposo</td>\n";
						$this->salida .= "							<td width='80%'>".$controles[0]['descripcion']."</td>\n";
						$this->salida .= "						</tr>\n";
					}
				}
				$data=$resultado2->FetchNextObject($toUpper=false);
				if (!empty($data->observaciones)) {
					$this->salida .= "						<tr ".$this->Lista(2)."'>\n";
					$this->salida .= "							<td width='20%'>Observaci&oacute;n</td>\n";
					$this->salida .= "							<td width='80%' align='justify'>".$data->observaciones."</td>\n";
					$this->salida .= "						</tr>\n";
				}
				$this->salida .= "	</table>\n";
			}
			return true;
		}


		//estacion de enfermeria control_pacientes
		/*
		*		ControlTerResp
		*
		*		@Author Arley Velasquez
		*		@access Private
		*/
		function ControlTerResp($control)
		{
			$pfj=$this->frmPrefijo;
			list($dbconn) = GetDBconn();

			if (!empty($control))
			{
				/*OJO ESTO LO CONVERTI EN LA FUNCION VerificaTerapiasRespiratoriasPacientes
				$query="SELECT * FROM hc_terapias_respiratorias WHERE evolucion_id=".$control['evolucion_id'];
				$resultado=$dbconn->Execute($query);
				if (!$resultado)
				{
					$this->error = "Error al consultar la tabla \"hc_terapias_respiratorias\" con evolucion_id=".$control['evolucion_id'];
					$this->mensajeDeError = $query;
					return false;
				}
				$data=$resultado->FetchNextObject($toUpper=false);
				if (!$resultado->RecordCount())*/
				$data = $this->VerificaTerapiasRespiratoriasPacientes($control['evolucion_id']);
				if(!$data){
					return false;
				}
				if(!is_array($data))
				{
					$this->error = "Error, el paciente no cuenta con registros en \"hc_terapias_respiratorias\" con evolucion_id=".$control['evolucion_id'];
					$this->mensajeDeError = $query;
					return false;
				}

				$controles=$this->GetControlTerResp($data[frecuencia_id],0);//$controles=$this->GetControlTerResp($data->frecuencia_id,0);

				$this->salida .= "	<table width='100%' align='justify' border='0' class='modulo_table_list'>";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td width='100%' align='left' class='modulo_table_title' colspan='2'>TERAPIA RESPIRATORIA</td>\n";
				$this->salida .= "		</tr>\n";
				if (!empty($data[frecuencia_id]))//if (!empty($data->frecuencia_id))
				{
					$this->salida .= "						<tr ".$this->Lista(1)."'>\n";
					$this->salida .= "							<td width='20%'>Frecuencia</td>\n";
					$this->salida .= "							<td width='80%'>".$controles[0]['descripcion']."</td>\n";
					$this->salida .= "						</tr>\n";
				}
				if (!empty($data[observaciones]))//if (!empty($data->observaciones))
				{
					$this->salida .= "						<tr ".$this->Lista(2)."'>\n";
					$this->salida .= "							<td width='20%'>Observaci&oacute;n</td>\n";
					//$this->salida .= "							<td width='80%' align='justify'>".$data->observaciones."</td>\n";
					$this->salida .= "							<td width='80%' align='justify'>".$data[observaciones]."</td>\n";
					$this->salida .= "						</tr>\n";
				}
				$this->salida .= "	</table>\n";
			}
			return true;
		}


		//estacion de enfermeria control_pacientes
		/**
		*		ControlCurTerm
		*
		*		@Author Arley Velasquez
		*		@access Private
		*/
		function ControlCurTerm($control)
		{
			$pfj=$this->frmPrefijo;
			list($dbconn) = GetDBconn();

			if (!empty($control))
			{
				/*OJO, esto lo convert&iacute; en la funcion VerificaCurvasTermicasPaciente
				$query="SELECT * FROM hc_curvas_termicas WHERE evolucion_id=".$control['evolucion_id'];
				$resultado=$dbconn->Execute($query);

				if (!$resultado)
				{
					$this->error = "Error al consultar las posiciones del paciente en \"hc_posicion_paciente\" con evolucion_id=".$control['evolucion_id'];
					$this->mensajeDeError = $query;
					return false;
				}
				$data=$resultado->FetchNextObject($toUpper=false);
				if (!$resultado->RecordCount())*/
				$data = $this->VerificaCurvasTermicasPaciente($control['evolucion_id']);
				if(!$data){
					return false;
				}
				if(!is_array($data))
				{
					$this->error = "Error, el paciente no cuenta con registros en \"hc_posicion_paciente\" con evolucion_id=".$control['evolucion_id'];
					$this->mensajeDeError = $query;
					return false;
				}

				$controles=$this->GetControlCurTerm($data[frecuencia_id],0);

				$this->salida .= "	<table width='100%' align='justify' border='0' class='modulo_table_list'>";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td width='100%' align='left' class='modulo_table_title' colspan='2'>CURVA TERMICA</td>\n";
				$this->salida .= "		</tr>\n";
				if (!empty($data[frecuencia_id]))
				{
					$this->salida .= "						<tr ".$this->Lista(1)."'>\n";
					$this->salida .= "							<td width='20%'>Frecuencia</td>\n";
					$this->salida .= "							<td width='80%'>".$controles[0]['descripcion']."</td>\n";
					$this->salida .= "						</tr>\n";
				}
				if (!empty($data[observaciones])) {
					$this->salida .= "						<tr ".$this->Lista(2)."'>\n";
					$this->salida .= "							<td width='20%'>Observaci&oacute;n</td>\n";
					$this->salida .= "							<td width='80%' align='justify'>".$data[observaciones]."</td>\n";
					$this->salida .= "						</tr>\n";
				}
				$this->salida .= "	</table>\n";
			}
			return true;
		}


		//estacion de enfermeria control_pacientes
		/**
		*		ControlLiquidos
		*
		*		@Author Arley Velasquez
		*		@access Private
		*/
		function ControlLiquidos($control)
		{
			$pfj=$this->frmPrefijo;
			list($dbconn) = GetDBconn();

			if (!empty($control))
			{
				/*OJO, esto lo convert&iacute; en la funcion VerificaControlLiquidosPaciente
				$query="SELECT * FROM hc_control_liquidos WHERE evolucion_id=".$control['evolucion_id'];
				$resultado=$dbconn->Execute($query);
				if (!$resultado)
				{
					$this->error = "Error al consultar la tabla \"hc_control_liquidos\" con evolucion_id=".$control['evolucion_id'];
					$this->mensajeDeError = $query;
					return false;
				}
				$data=$resultado->FetchNextObject($toUpper=false);
				if (!$resultado->RecordCount())*/
				$data = $this->VerificaControlLiquidosPaciente($control['evolucion_id']);
				if(!$data){
					return false;
				}
				if(!is_array($data))
				{
					$this->error = "Error, el paciente no cuenta con registros en \"hc_control_liquidos\" con evolucion_id=".$control['evolucion_id'];
					$this->mensajeDeError = $query;
					return false;
				}

				$controles=$this->GetControlLiquidos($control['evolucion_id'],0);

				$this->salida .= "	<table width='100%' align='justify' border='0' class='modulo_table_list'>";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td width='100%' align='left' class='modulo_table_title' colspan='2'>CONTROL DE LIQUIDOS INGERIDOS Y ELIMINADOS</td>\n";
				$this->salida .= "		</tr>\n";
				if (!empty($controles[0]['observaciones']))
				{
					$this->salida .= "						<tr ".$this->Lista(1)."'>\n";
					$this->salida .= "							<td width='20%'>Descripci&oacute;n</td>\n";
					$this->salida .= "							<td width='80%'>".$controles[0]['observaciones']."</td>\n";
					$this->salida .= "						</tr>\n";
				}
				$this->salida .= "	</table>\n";
			}
			return true;
		}

		//estacion de enfermeria control_pacientes
		/*
		*		ControlTA
		*
		*		@Author Arley Velasquez
		*		@access Private
		*/
		function ControlTA($control)
		{
			$pfj=$this->frmPrefijo;
			list($dbconn) = GetDBconn();

			if (!empty($control))
			{
				/*OJO, esto lo convert&iacute; en la funcion verificaTensionArterialPaciente
				$query="SELECT * FROM hc_control_tension_arterial WHERE evolucion_id=".$control['evolucion_id'];
				$resultado=$dbconn->Execute($query);
				if (!$resultado)
				{
					$this->error = "Error al consultar la tabla \"hc_control_tension_arterial\" con evolucion_id=".$control['evolucion_id'];
					$this->mensajeDeError = $query;
					return false;
				}
				$data=$resultado->FetchNextObject($toUpper=false);
				if (!$resultado->RecordCount())*/
				$data = $this->verificaTensionArterialPaciente($control['evolucion_id']);
				if(!$data){
					return false;
				}
				if(!is_array($data))
				{
					$this->error = "Error, el paciente no cuenta con registros en \"hc_control_tension_arterial\" con evolucion_id=".$control['evolucion_id'];
					$this->mensajeDeError = $query;
					return false;
				}

				$controles=$this->GetControlTA($data[frecuencia_id],0);

				$this->salida .= "	<table width='100%' align='justify' border='0' class='modulo_table_list'>";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td width='100%' align='left' class='modulo_table_title' colspan='2'>TENSION ARTERIAL</td>\n";
				$this->salida .= "		</tr>\n";
				if (!empty($data[frecuencia_id]))
				{
					$this->salida .= "						<tr ".$this->Lista(1)."'>\n";
					$this->salida .= "							<td width='20%'>Frecuencia</td>\n";
					$this->salida .= "							<td width='80%'>".$controles[0]['descripcion']."</td>\n";
					$this->salida .= "						</tr>\n";
				}
				if (!empty($data[observaciones])) {
					$this->salida .= "						<tr ".$this->Lista(2)."'>\n";
					$this->salida .= "							<td width='20%'>Observaci&oacute;n</td>\n";
					$this->salida .= "							<td width='80%' align='justify'>".$data[observaciones]."</td>\n";
					$this->salida .= "						</tr>\n";
				}
				$this->salida .= "	</table>\n";
			}
			return true;
		}

		//estacion de enfermeria control_pacientes
		/*
		*		ControlGlucometria
		*
		*		@Author ArleyVelasquez
		*		@access Private
		*/
		function ControlGlucometria($control)
		{
			$pfj=$this->frmPrefijo;
			list($dbconn) = GetDBconn();

			if (!empty($control))
			{
				/*OJO, esto lo convert&iacute; en la funcion verificaGlucometriaPaciente
				$query="SELECT * FROM hc_control_glucometria WHERE evolucion_id=".$control['evolucion_id'];
				$resultado=$dbconn->Execute($query);
				if (!$resultado)
				{
					$this->error = "Error al consultar la tabla \"hc_control_glucometria\" con evolucion_id=".$control['evolucion_id'];
					$this->mensajeDeError = $query;
					return false;
				}
				$data=$resultado->FetchNextObject($toUpper=false);
				if (!$resultado->RecordCount())*/
				$data = $this->verificaGlucometriaPaciente($control['evolucion_id']);
				if(!$data){
					return false;
				}
				if(!is_array($data))
				{
					$this->error = "Error, el paciente no cuenta con registros en \"hc_control_glucometria\" con evolucion_id=".$control['evolucion_id'];
					$this->mensajeDeError = $query;
					return false;
				}

				$controles=$this->GetControlGlucometria($data[frecuencia_id],0);

				$this->salida .= "	<table width='100%' align='justify' border='0' class='modulo_table_list'>";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td width='100%' align='left' class='modulo_table_title' colspan='2'>GLUCOMETRIA</td>\n";
				$this->salida .= "		</tr>\n";
				if (!empty($data[frecuencia_id]))
				{
					$this->salida .= "						<tr ".$this->Lista(1)."'>\n";
					$this->salida .= "							<td width='20%'>Frecuencia</td>\n";
					$this->salida .= "							<td width='80%'>".$controles[0]['descripcion']."</td>\n";
					$this->salida .= "						</tr>\n";
				}
				if (!empty($data[observaciones])) {
					$this->salida .= "						<tr ".$this->Lista(2)."'>\n";
					$this->salida .= "							<td width='20%'>Observaci&oacute;n</td>\n";
					$this->salida .= "							<td width='80%' align='justify'>".$data[observaciones]."</td>\n";
					$this->salida .= "						</tr>\n";
				}
				$this->salida .= "	</table>\n";
			}
			return true;
		}

		//estacion de enfermeria control_pacientes
		/*
		*		ControlCUraciones
		*
		*		@Author Arley Velasquez
		*		@access Private
		*/
		function ControlCuraciones($control)
		{
			$pfj=$this->frmPrefijo;
			list($dbconn) = GetDBconn();

			if (!empty($control))
			{
				/*OJO, esto lo convert&iacute; en la funcion verificaControlCuracionesPaciente
				$query="SELECT * FROM hc_control_curaciones WHERE evolucion_id=".$control['evolucion_id'];
				$resultado=$dbconn->Execute($query);
				if (!$resultado)
				{
					$this->error = "Error al consultar la tabla \"hc_control_curaciones\" con evolucion_id=".$control['evolucion_id'];
					$this->mensajeDeError = $query;
					return false;
				}
				$data=$resultado->FetchNextObject($toUpper=false);
				if (!$resultado->RecordCount())*/
				$data = $this->verificaControlCuracionesPaciente($control['evolucion_id']);
				if(!$data){
					return false;
				}
				if(!is_array($data))
				{
					$this->error = "Error, el paciente no cuenta con registros en \"hc_control_curaciones\" con evolucion_id=".$control['evolucion_id'];
					$this->mensajeDeError = $query;
					return false;
				}

				$controles=$this->GetControlCuraciones($data[frecuencia_id],0);

				$this->salida .= "	<table width='100%' align='justify' border='0' class='modulo_table_list'>";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td width='100%' align='left' class='modulo_table_title' colspan='2'>CURACIONES</td>\n";
				$this->salida .= "		</tr>\n";
				if (!empty($data[frecuencia_id]))
				{
					$this->salida .= "						<tr ".$this->Lista(1)."'>\n";
					$this->salida .= "							<td width='20%'>Frecuencia</td>\n";
					$this->salida .= "							<td width='80%'>".$controles[0]['descripcion']."</td>\n";
					$this->salida .= "						</tr>\n";
				}
				if (!empty($data[observaciones])) {
					$this->salida .= "						<tr ".$this->Lista(2)."'>\n";
					$this->salida .= "							<td width='20%'>Observaci&oacute;n</td>\n";
					$this->salida .= "							<td width='80%' align='justify'>".$data[observaciones]."</td>\n";
					$this->salida .= "						</tr>\n";
				}
				$this->salida .= "	</table>\n";
			}
			return true;
		}

    //estacion de enfermeria control_pacientes
		/*
		*		ControlNeurologico
		*
		*
		*		@Author Arley Velasquez
		*		@access Private
		*/
		function ControlNeurologico($control)
		{
			$pfj=$this->frmPrefijo;
			list($dbconn) = GetDBconn();

			if (!empty($control))
			{
				/*OJO, esto lo convert&iacute; en la funcion verificaControlNeurologicoPaciente
				$query="SELECT * FROM hc_control_neurologico WHERE evolucion_id=".$control['evolucion_id'];
				$resultado=$dbconn->Execute($query);
				if (!$resultado)
				{
					$this->error = "Error al consultar la tabla \"hc_control_neurologico\" con evolucion_id=".$control['evolucion_id'];
					$this->mensajeDeError = $query;
					return false;
				}
				$data=$resultado->FetchNextObject($toUpper=false);
				if (!$resultado->RecordCount())*/
				$data = $this->verificaControlNeurologicoPaciente($control['evolucion_id']);
				if(!$data){
					return false;
				}
				if(!is_array($data))
				{
					$this->error = "Error, el paciente no cuenta con registros en \"hc_control_curaciones\" con evolucion_id=".$control['evolucion_id'];
					$this->mensajeDeError = $query;
					return false;
				}

				$controles=$this->GetControlNeurologico($data[frecuencia_id],0);

				$this->salida .= "	<table width='100%' align='justify' border='0' class='modulo_table_list'>";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td width='100%' align='left' class='modulo_table_title' colspan='2'>HOJA NEUROLOGICA</td>\n";
				$this->salida .= "		</tr>\n";
				if (!empty($data[frecuencia_id]))
				{
					$this->salida .= "						<tr ".$this->Lista(1)."'>\n";
					$this->salida .= "							<td width='20%'>Frecuencia</td>\n";
					$this->salida .= "							<td width='80%'>".$controles[0]['descripcion']."</td>\n";
					$this->salida .= "						</tr>\n";
				}
				if (!empty($data[observaciones])) {
					$this->salida .= "						<tr ".$this->Lista(2)."'>\n";
					$this->salida .= "							<td width='20%'>Observaci&oacute;n</td>\n";
					$this->salida .= "							<td width='80%' align='justify'>".$data[observaciones]."</td>\n";
					$this->salida .= "						</tr>\n";
				}
				$this->salida .= "	</table>\n";
			}
			return true;
		}

		//estacion de enfermeria control_pacientes
		/*
		*		ControlPerAbdominal
		*
		*		@Author Arley Velasquez
		*		@access Private
		*/
		function ControlPerAbdominal($control)
		{
			$pfj=$this->frmPrefijo;
			list($dbconn) = GetDBconn();

			if (!empty($control))
			{
				/*OJO, esto lo convert&iacute; en la funcion verificaPerimetroAbdominalPaciente
				$query="SELECT * FROM hc_control_perimetro_abdominal WHERE evolucion_id=".$control['evolucion_id'];
				$resultado=$dbconn->Execute($query);
				if (!$resultado)
				{
					$this->error = "Error al consultar la tabla \"hc_control_perimetro_abdominal\" con evolucion_id=".$control['evolucion_id'];
					$this->mensajeDeError = $query;
					return false;
				}
				$data=$resultado->FetchNextObject($toUpper=false);
				if (!$resultado->RecordCount())*/
				$data = $this->verificaPerimetroAbdominalPaciente($control['evolucion_id']);
				if(!$data){
					return false;
				}
				if(!is_array($data))
				{
					$this->error = "Error, el paciente no cuenta con registros en \"hc_control_perimetro_abdominal\" con evolucion_id=".$control['evolucion_id'];
					$this->mensajeDeError = $query;
					return false;
				}

				$controles=$this->GetControlPerAbdominal($control['evolucion_id'],0);

				$this->salida .= "	<table width='100%' align='justify' border='0' class='modulo_table_list'>";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td width='100%' align='left' class='modulo_table_title' colspan='2'>PERIMETRO ABDOMINAL</td>\n";
				$this->salida .= "		</tr>\n";
				if (!empty($controles[0]['observaciones']))
				{
					$this->salida .= "						<tr ".$this->Lista(1)."'>\n";
					$this->salida .= "							<td width='20%'>Descripci&oacute;n</td>\n";
					$this->salida .= "							<td width='80%'>".$controles[0]['observaciones']."</td>\n";
					$this->salida .= "						</tr>\n";
				}
				$this->salida .= "	</table>\n";
			}
			return true;
		}

		//estacion de enfermeria control_pacientes
		/*
		*		ControlPerCefalico
		*
		*		@Author Arley Velasquez
		*		@access Private
		*/
		function ControlPerCefalico($control)
		{
			$pfj=$this->frmPrefijo;
			list($dbconn) = GetDBconn();

			if (!empty($control))
			{
				/*OJO, esto lo convert&iacute; en la funcion verificaPerimetroCefalicoPaciente
				$query="SELECT * FROM hc_control_perimetro_cefalico WHERE evolucion_id=".$control['evolucion_id'];
				$resultado=$dbconn->Execute($query);
				if (!$resultado)
				{
					$this->error = "Error al consultar la tabla \"hc_control_perimetro_cefalico\" con evolucion_id=".$control['evolucion_id'];
					$this->mensajeDeError = $query;
					return false;
				}
				$data=$resultado->FetchNextObject($toUpper=false);
				if (!$resultado->RecordCount())*/
				$data = $this->verificaPerimetroCefalicoPaciente($control['evolucion_id']);
				if(!$data){
					return false;
				}
				if(!is_array($data))
				{
					$this->error = "Error, el paciente no cuenta con registros en \"hc_control_perimetro_cefalico\" con evolucion_id=".$control['evolucion_id'];
					$this->mensajeDeError = $query;
					return false;
				}

				$controles=$this->GetControlPerCefalico($control['evolucion_id'],0);

				$this->salida .= "	<table width='100%' align='justify' border='0' class='modulo_table_list'>";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td width='100%' align='left' class='modulo_table_title' colspan='2'>PERIMETRO CEFALICO</td>\n";
				$this->salida .= "		</tr>\n";
				if (!empty($controles[0]['observaciones']))
				{
					$this->salida .= "						<tr ".$this->Lista(1)."'>\n";
					$this->salida .= "							<td width='20%'>Descripci&oacute;n</td>\n";
					$this->salida .= "							<td width='80%'>".$controles[0]['observaciones']."</td>\n";
					$this->salida .= "						</tr>\n";
				}
				$this->salida .= "	</table>\n";
			}
			return true;
		}

		 //estacion de enfermeria control_pacientes
		/*
		*		ControlPerExtremidades
		*
		*		@Author Arley Velasquez
		*		@access Private
		*/
		function ControlPerExtremidades($control)
		{
			$pfj=$this->frmPrefijo;
			list($dbconn) = GetDBconn();

			if (!empty($control))
			{
				$query="SELECT * FROM hc_control_perimetro_extremidades_detalle WHERE evolucion_id=".$control['evolucion_id'];
				$query2="SELECT * FROM hc_control_perimetro_extremidades WHERE evolucion_id=".$control['evolucion_id'];
				$resultado2=$dbconn->Execute($query2);
				$resultado=$dbconn->Execute($query);
				if (!$resultado2)
				{
					$this->error = "Error al consultar la tabla \"hc_control_perimetro_extremidades\" con evolucion_id=".$control['evolucion_id'];
					$this->mensajeDeError = $query;
					return false;
				}
				if (!$resultado)
				{
					$this->error = "Error al consultar la tabla \"hc_control_perimetro_extremidades_detalle\" con evolucion_id=".$control['evolucion_id'];
					$this->mensajeDeError = $query;
					return false;
				}
				if (!$resultado->RecordCount())
				{
					$this->error = "Error, el paciente no cuenta con registros en \"hc_control_perimetro_extremidades_detalle\" con evolucion_id=".$control['evolucion_id'];
					$this->mensajeDeError = $query;
					return false;
				}

				$this->salida .= "	<table width='100%' align='justify' border='0' class='modulo_table_list'>";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td width='100%' align='left' class='modulo_table_title' colspan='2'>PERIMETRO DE EXTREMIDADES</td>\n";
				$this->salida .= "		</tr>\n";
				while ($data=$resultado->FetchNextObject($toUpper=false))
				{
					$controles=$this->GetControlPerExtremidades($data->tipo_extremidad_id,0);
					if (!empty($data->tipo_extremidad_id))
					{
						$this->salida .= "						<tr ".$this->Lista($i++)."'>\n";
						$this->salida .= "							<td width='20%'>Tipo de Perimetro de extremidad</td>\n";
						$this->salida .= "							<td width='80%'>".$controles[0]['descripcion']."</td>\n";
						$this->salida .= "						</tr>\n";
					}
				}
				$data=$resultado2->FetchNextObject($toUpper=false);
				if (!empty($data->observaciones)) {
					$this->salida .= "						<tr ".$this->Lista($i++)."'>\n";
					$this->salida .= "							<td width='20%'>Observaci&oacute;n</td>\n";
					$this->salida .= "							<td width='80%' align='justify'>".$data->observaciones."</td>\n";
					$this->salida .= "						</tr>\n";
				}
				$this->salida .= "	</table>\n";
			}
			return true;
		}

		//estacion de enfermeria control_pacientes
		/*
		*		ControlParto
		*
		*		@Author Arley Velasquez
		*		@access Private
		*/
		function ControlParto($control)
		{
			$pfj=$this->frmPrefijo;
			list($dbconn) = GetDBconn();

			if (!empty($control))
			{
				/*OJO, converti esto en la funcion verificaControlTrabajoPartoPaciente
				$query="SELECT * FROM hc_control_trabajo_parto WHERE evolucion_id=".$control['evolucion_id'];
				$resultado=$dbconn->Execute($query);
				if (!$resultado)
				{
					$this->error = "Error al consultar la tabla \"hc_control_trabajo_parto\" con evolucion_id=".$control['evolucion_id'];
					$this->mensajeDeError = $query;
					return false;
				}
				$data=$resultado->FetchNextObject($toUpper=false);
				if (!$resultado->RecordCount())*/
				$data = $this->verificaControlTrabajoPartoPaciente($control['evolucion_id']);
				if(!$data){
					return false;
				}
				if(!is_array($data))
				{
					$this->error = "Error, el paciente no cuenta con registros en \"hc_control_trabajo_parto\" con evolucion_id=".$control['evolucion_id'];
					$this->mensajeDeError = $query;
					return false;
				}

				$controles=$this->GetControlParto($control['evolucion_id'],0);

				$this->salida .= "	<table width='100%' align='justify' border='0' class='modulo_table_list'>";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td width='100%' align='left' class='modulo_table_title' colspan='2'>CONTROL DE TRABAJO DE PARTO</td>\n";
				$this->salida .= "		</tr>\n";
				if (!empty($controles[0]['observaciones']))
				{
					$this->salida .= "						<tr ".$this->Lista(1)."'>\n";
					$this->salida .= "							<td width='20%'>Descripci&oacute;n</td>\n";
					$this->salida .= "							<td width='80%'>".$controles[0]['observaciones']."</td>\n";
					$this->salida .= "						</tr>\n";
				}
				$this->salida .= "	</table>\n";
			}
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


		/*
		*
		*
		*/
		function ModificarDietaEnfermera()
		{
			$datos_estacion=$_REQUEST['datos_estacion'];
			$data=$_REQUEST['data'];
			$nombre=$_REQUEST['nombre'];
			$value=$_REQUEST['value'];
			$vect=$_REQUEST['vect'];
			$obsE=$_REQUEST['observaenf'];
			$this->salida .= ThemeAbrirTabla('MODIFICAR PRESCRIPCION DIETAS');

			$this->salida .= "              <br><table  cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"50%\" align=\"center\">";
			$this->salida .= "				       <tr><td class=\"modulo_table_title\" class=\"label\" width=\"40%\" align=\"left\">NOMBRE: </td><td class=\"modulo_list_claro\" align=\"left\">$nombre</td></tr>";

			$this->salida .= " </table>";

			$tiposDieta = $this->GetTiposDieta();
			$action = ModuloGetURL('app','EstacionE_ControlPacientes','user','InsertarPrescripcionDieta',array("value"=>$value,"datos_estacion"=>$datos_estacion));
			$this->salida .= "<form name=\"FrmPrescripcionDietas\" method=\"POST\" action=\"$action\"><br>\n";
			$this->salida .= "	<table width='100%' border='0' cellspacing='2' cellpadding='2'>\n";
   		$this->salida .= "		<tr class='modulo_table_title' align='center' >\n";

			$this->salida .= "			<td rowspan='2'>HAB.</td>\n";
			$this->salida .= "			<td rowspan='2'>CAMA</td>\n";
			$this->salida .= "			<td rowspan='2'>PACIENTE</td>\n";
			$this->salida .= "			<td colspan='".sizeof($tiposDieta)."' align='center'>PRESCRIPCION DIETA</td>\n";//- <font size='1' face='verdana'><a href=\"javascript:window.open('".$url."',x,'width=450,height=250,resizable=no,status=no,scrollbars=yes,left=200,top=200');\">Ver detalle</a></font>
			$this->salida .= "			<td rowspan='2'>OBSERVACIONES ENFERMERA</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr align='center' class='modulo_table_title'>\n";
			foreach ($tiposDieta as $kDieta => $valDieta){
				$this->salida .= "			<td width='5' align='center'>".trim($valDieta[abreviatura])."</td>\n";
			}
			$this->salida .= "		</tr>";

				$estilo = "modulo_list_claro";
				$this->salida .= "	<tr class='$estilo'>\n";
				$this->salida .= "		<td>".$value[pieza]."&nbsp;</td>\n";
				$this->salida .= "		<td>".$value[cama]."&nbsp;</td>\n";
				$dietaRecetada = $this->VerificaDietaRecetadaPaciente($value[ingreso]);

				if($dietaRecetada[sw_ayuno]<1)
				{$mark='class=label_mark';}else{$mark='';}
				$this->salida .= "		<td $mark>".$value[primer_nombre]." ".$value[segundo_nombre]." ".$value[primer_apellido]." ".$value[segundo_apellido]."&nbsp;</td>\n";
				$nombre="".$value[primer_nombre]."&nbsp;".$value[segundo_nombre]."&nbsp;".$value[primer_apellido]."&nbsp;".$value[segundo_apellido]."";
				$i=0;

				foreach ($tiposDieta as $kDieta => $valDieta)
				{

				  		if(in_array($valDieta[hc_dieta_id],$vect))
							{
									$checked = "checked='yes'";
							}
							else
							{
									$checked = "";
							}


					$this->salida .= "		<td align='center'><input type='checkbox'$checked  name='diet[]' value=".$valDieta[hc_dieta_id]."></td>\n";
					$i++;
				}

				//funcion q trae la informacion de los ayunos q el medico le ha recetado al paciente.
				$informacion=$this->GetInformacionAyunoPaciente($value[ingreso]);

				$this->salida .= "		<td align='center'><textarea \"style=width:100%\" cols='60' rows='5'  name='observaciones' class='textarea'>$obsE</textarea>\n"; unset($obsMedico);

				$this->salida .= "									<br><table width='100%' border='2' >\n";
				$this->salida .= "							<tr>";
				$this->salida .= "							<td width='50%' align='center'><label class='label'>Hora Inicial</label><select name='horai' class=\"select\" disabled>";
				for($i=6;$i<24;$i++)
				{

					if($i<10){$s=0;}else{$s='';}
					$a=$s.$i;
					$a.=":30";
					if($a!=$informacion[0]['hora_inicio_ayuno'])
					{
						$this->salida .= "<option value=\"$a\">$a</option>";
					}
					else
					{
						$this->salida .= "<option value=\"$a\" selected>$a</option>";
					}
					$a='';
				}
				$this->salida .= "</select></td>";

				$this->salida .= "							<td width='50%' align='center'><label class='label'>Hora Final</label><select name='hora' class=\"select\" disabled>";
				for($i=6;$i<24;$i++)
				{

					if($i<10){$s=0;}else{$s='';}
					$a=$s.$i;
					$a.=":30";
					if($a!=$informacion[0]['hora_fin_ayuno'])
					{
						$this->salida .= "<option value=\"$a\">$a</option>";
					}
					else
					{
						$this->salida .= "<option value=\"$a\" selected>$a</option>";
					}
					$a='';
				}
				$this->salida .= "</select></td></tr>\n";
				$this->salida .= "							<tr><td colspan='3' width='50%' align='center'>";
				$this->salida .= "<textarea class='textarea' name='motivo' cols='55' rows='6' READONLY>".$informacion[0]['motivo']."</textarea>";
				$this->salida .= "</td></tr>\n";
				$this->salida .= "					</table>\n";

				$this->salida .= "	</td></tr>\n";

				if(is_array($informacion))
				{
					$user=$this->GetDatosUsuarioSistema($informacion[0]['usuario_id']);
					$this->salida .= "<tr class='modulo_list_oscuro'><td colspan='14'><label class='label_mark'>MEDICO QUE SOLICITO AYUNO :</label> &nbsp;".$user[0][usuario]."&nbsp;- &nbsp;".$user[0][nombre]."</td>";
					$this->salida .= "<tr class='modulo_list_claro'><td colspan='14'><label class='label_mark'>FECHA DE SOLICITUD :</label>&nbsp;".$informacion[0][fecha]."</td>";
				}

				$this->salida.="</table>\n";


			$this->salida.="<br><table align=\"center\" width='40%' border=\"0\">";
			$action2=ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmPrescripcionDietas',
			array('datos_estacion'=>$datos_estacion));
			$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"guardar\" type=\"submit\" value=\"Guardar\"></form></td>";

			$this->salida .= "           <form name=\"forma\" action=\"$action2\" method=\"post\">";
			$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
			$this->salida .= "</tr>";

			$this->salida .= "</td></tr>";
			$this->salida.="</table><br>";
			$this->salida .= themeCerrarTabla();
			return true;
		}





			/*funcion del mod estacione-control_pacientes*/

	/*
	*		FrmPrescripcionDietas()
	*
	*		Formulario que permite realizar solicitudes de dietas
	*
	*		@Author Rosa Maria Angel
	*		@access Private
	*		@return bool
	*/
	function FrmPrescripcionDietas($datos_estacion)
	{
		$pacientes = $this->CallMetodoExterno('app','Censo','user','GetCensoTipo1',array('estacion'=>$datos_estacion['estacion_id']));
		$pacientesI	= $this->GetPacientesPendientesXHospitalizar_Plantilla($datos_estacion);
		$tiposDieta = $this->GetTiposDieta();

		if($tiposDieta === "ShowMensaje")
		{
			$mensaje = "NO SE ENCONTRARON REGISTROS EN LA TABLA MAESTRO DE DIETAS";
			$titulo = "MENSAJE";
			$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
			$boton = "VOLVER AL MEN&Uacute; ESTACION";
			$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
			return true;
		}

		if($pacientes === "ShowMensaje" AND $pacientesI=="")
		{
			$mensaje = "NO SE ENCONTRARON PACIENTES EN LA ESTACI&Oacute;N";
			$titulo = "MENSAJE";
			$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
			$boton = "VOLVER AL MEN&Uacute; ESTACION";
			$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
			return true;
		}

			$backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
			$action = ModuloGetURL('app','EstacionE_ControlPacientes','user','InsertarPrescripcionDieta',array("datos_estacion"=>$datos_estacion));
			$this->salida .= "<form name=\"FrmPrescripcionDietas\" method=\"POST\" action=\"$action\"><br>\n";

			$RUTA = $_ROOT ."app_modules/EstacionE_ControlPacientes/dietas.html";
			$mostrar ="\n<script language='javascript'>\n";
			$mostrar.="var rem=\"\";\n";
			$mostrar.="  function XVentana(){\n";
			$mostrar.="    var nombre=\"\"\n";
			$mostrar.="    var url2=\"\"\n";
			$mostrar.="    var str=\"\"\n";
			$mostrar.="    var nombre=\"REPORTE\";\n";
			$mostrar.="    var str =\"height=330,width=430,resizable=no,location=no, status=no,scrollbars=yes\";\n";
			$mostrar.="    var url2 ='$RUTA';\n";
			$mostrar.="    rem = window.open(url2, nombre, str)};\n";

			$mostrar.="function mOvr(src,clrOver) {;\n";
			$mostrar.="src.style.background = clrOver;\n";
			$mostrar.="}\n";

			$mostrar.="function mOut(src,clrIn) {\n";
			$mostrar.="src.style.background = clrIn;\n";
			$mostrar.="}\n";

			$mostrar.="</script>\n";
			$this->salida.="$mostrar";

			$this->salida .= ThemeAbrirTabla("PLANILLA DE PRESCRIPCION DIETAS")."\n";

			$this->salida .= "<center>\n";
			$this->salida .= "				<table class='modulo_table_title' border='0' width='100%'>\n";
			$this->salida .= "					<tr class='modulo_table_title'>\n";
			$this->salida .= "						<td>Empresa</td>\n";
			$this->salida .= "						<td>Centro Utilidad</td>\n";
			$this->salida .= "						<td>Unidad Funcional</td>\n";
			$this->salida .= "						<td>Departamento</td>\n";
			$this->salida .= "					</tr>\n";
			$this->salida .= "					<tr class='modulo_list_oscuro'>\n";
			$this->salida .= "						<td>".$datos_estacion['descripcion1']."</td>\n";
			$this->salida .= "						<td>".$datos_estacion['descripcion2']."</td>\n";
			$this->salida .= "						<td>".$datos_estacion['descripcion3']."</td>\n";
			$this->salida .= "						<td>".$datos_estacion['descripcion4']."</td>\n";
			$this->salida .= "					</tr>\n";
			$this->salida .= "				</table><br>\n";

			$this->salida .= "	<table width='100%' border='0' cellspacing='2' cellpadding='2'>\n";
			$this->salida .= "		<tr>\n";

			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class='modulo_table_title' align='center' >\n";

			$this->salida .= "			<td rowspan='2'></td>\n";
			$this->salida .= "			<td rowspan='2'>HAB.</td>\n";
			$this->salida .= "			<td rowspan='2'>CAMA</td>\n";
			$this->salida .= "			<td rowspan='2'>PACIENTE</td>\n";
			$this->salida .= "			<td >AYUNO</td>\n";
			$this->salida .= "			<td colspan='".sizeof($tiposDieta)."' align='center'>PRESCRIPCION DIETA</td>\n";//- <font size='1' face='verdana'><a href=\"javascript:window.open('".$url."',x,'width=450,height=250,resizable=no,status=no,scrollbars=yes,left=200,top=200');\">Ver detalle</a></font>
			$this->salida .= "			<td rowspan='2'>OBSERVACIONES MEDICO</td>\n";
			$this->salida .= "			<td rowspan='2'>Accion</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr align='center' class='modulo_table_title'>\n";
			$this->salida .= "			<td ><a href='javascript:XVentana()'><img src=\"". GetThemePath() ."/images/preguntainac.png\" width=20 height='18' border='0'></a></td>\n";

			foreach ($tiposDieta as $kDieta => $valDieta){
				$this->salida .= "			<td width='5' align='center'>".trim($valDieta[abreviatura])."</td>\n";
			}
			$this->salida .= "		</tr>";



						//revisar las de pendientes por ingresar

			foreach ($pacientesI['ingresar'] as $key => $value)
			{
				$estilo = "modulo_list_claro";
				$this->salida .= "	<tr class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>\n";

				$this->salida .= "	<td   align=\"center\"><img src=\"". GetThemePath() ."/images/ingresar.png\" border='0' title='Pendiente ingreso'></td>\n";
				$this->salida .= "	<td  colspan='2' align=\"center\"><label class=label_mark>PENDIENTE</label></td>\n";


				$dietaRecetada = $this->VerificaDietaRecetadaxMedico($value[ingreso]);
				$dietaRecetadaEnf = $this->VerificaDietaEnf($value[ingreso]);

				$nombre="".$value[primer_nombre]."&nbsp;".$value[segundo_nombre]."&nbsp;".$value[primer_apellido]."&nbsp;".$value[segundo_apellido]."";
				if($dietaRecetada!='show')
				{
                         $obsMedico=$dietaRecetada[0][observaciones];
                         if($dietaRecetada)
                         {$mark='class=label_mark';}else{$mark='';}

                         for($b=0;$b<sizeof($dietaRecetada);$b++)
                         {
                              $vect[$b] = $dietaRecetada[$b][hc_dieta_id];
                              $fechaComp = $dietaRecetada[$b][fecha_registro];
                         }
				}
				if($dietaRecetadaEnf!='show')
				{
                         if(sizeof($vect)>0)
                         {
                              for($z=0;$z<sizeof($dietaRecetadaEnf);$z++)
                              {
                                   if($fechaComp < $dietaRecetadaEnf[$z][fecha_registro])
                                   {
                                   	$b=$b+1;
                                        $vect[$b] = $dietaRecetadaEnf[$z][hc_dieta_id];
                                        if($dietaRecetadaEnf[0][observacion])
			                         { $obsMedico=$dietaRecetadaEnf[0][observacion];}
			                    }
                              }
                         }
                         else
                         {
                              for($x=0;$x<sizeof($dietaRecetadaEnf);$x++)
                              {
                                   if($dietaRecetadaEnf[0][observacion])
                                   { $obsMedico=$dietaRecetadaEnf[0][observacion];}
                                   $vect[$b]=$dietaRecetadaEnf[$x][hc_dieta_id];
                              }
                         }
				}
				$this->salida .= "		<td $mark>".$value[primer_nombre]." ".$value[segundo_nombre]." ".$value[primer_apellido]." ".$value[segundo_apellido]."&nbsp;</td>\n";
				unset($mark);


				$dato_ayuno=$this->VerificarAyunoPaciente($value[ingreso]);
                    if($dato_ayuno==1){$ayuno="checksi.png";}else{$ayuno="checkno.png";}
                    $imagen_ay="<img src=\"". GetThemePath() ."/images/$ayuno\" width=15 height='15' border='0'>";
				$this->salida .= "		<td align='center'>$imagen_ay</td>\n";
				$i=0;

				foreach ($tiposDieta as $kDieta => $valDieta)
				{
                         if(in_array($valDieta[hc_dieta_id],$vect))
                         {
                              $img="checkS.gif";
                         }
                         else
                         {
                              $img="checkN.gif";
                         }

					$imagen="<img src=\"". GetThemePath() ."/images/$img\"  border='0'>";
					$this->salida .= "	<td align='center'>$imagen</td>\n";unset($img);
					$i++;
				}
				//$this->salida .= "		<td><textarea \"style=width:100%\" cols='40' rows='3'  name='observaciones[".$value[ingreso]."]' class='textarea' READONLY>".$obsMedico."</textarea></td>\n"; unset($obsMedico);
				$this->salida .= "<td>$obsMedico</td>\n";unset($obsMedico);
				$accion=ModuloGetURL('app','EstacionE_ControlPacientes','user','ModificarDietaEnfermera',
				array("nombre"=>$nombre,"datos_estacion"=>$datos_estacion,"value"=>$value,"vect"=>$vect,'observaenf'=>$dietaRecetadaEnf[0][observacion]));
				$this->salida .= "	<td align='center'><a href='$accion'>MODIFICAR</a></td>\n";
				$this->salida .= "	</tr>\n";
				unset($vect);
			}
			//final de for de pacientes por ingresar


			foreach ($pacientes['hospitalizacion'] as $key => $value)
			{
				$estilo = "modulo_list_claro";
				$this->salida .= "	<tr class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>\n";
				$traslado=$this->Revisar_Si_esta_trasladado($value[ingreso]);
				$info=$this->RevisarSi_Es_Egresado($value[ingreso_dpto_id]);

				if($info[1]==2)//si es 2 egreso efectuado
				{
					$this->salida .= "	<td  align=\"center\"><img src=\"". GetThemePath() ."/images/egresook.png\" border='0'></td>\n";
				}
				elseif($info[1]=='1' OR $info[1]=='0')//es 1 enfermera-0 medico
				{
					$this->salida .= "	<td  align=\"center\"><img src=\"". GetThemePath() ."/images/egreso.png\" border='0'></td>\n";
				}
				else
				{
					if($traslado >0)
					{
						$this->salida .= "	<td  align=\"center\"><img src=\"". GetThemePath() ."/images/uf.png\" border='0'></td>\n";
					}
					else
					{
						$this->salida .= "	<td  align=\"center\"><img src=\"". GetThemePath() ."/images/honorarios.png\" border='0'></td>\n";
					}
				}
				$this->salida .= "		<td>".$value[pieza]."&nbsp;</td>\n";
				$this->salida .= "		<td>".$value[cama]."&nbsp;</td>\n";

				$dietaRecetada = $this->VerificaDietaRecetadaxMedico($value[ingreso]);
				$dietaRecetadaEnf =$this->VerificaDietaEnf($value[ingreso]);

			     $nombre="".$value[primer_nombre]."&nbsp;".$value[segundo_nombre]."&nbsp;".$value[primer_apellido]."&nbsp;".$value[segundo_apellido]."";
				if($dietaRecetada!='show')
				{
                         $obsMedico=$dietaRecetada[0][observaciones];
                         if($dietaRecetada)
					{$mark='class=label_mark';}else{$mark='';}

					for($b=0;$b<sizeof($dietaRecetada);$b++)
					{
                              $vect[$b] = $dietaRecetada[$b][hc_dieta_id];
                              $fechaComp = $dietaRecetada[$b][fecha_registro];
                         }
				}
				if($dietaRecetadaEnf!='show')
				{
                         if(sizeof($vect)>0)
                         { 
                         	$vect = '';
                              for($z=0;$z<sizeof($dietaRecetadaEnf);$z++)
                              {
                                   if($fechaComp < $dietaRecetadaEnf[$z][fecha_registro])
                                   {
                                   	//$b=$b+1;
                                        $vect[$b] = $dietaRecetadaEnf[$z][hc_dieta_id];
                                        if($dietaRecetadaEnf[0][observacion])
			                         { $obsMedico=$dietaRecetadaEnf[0][observacion];}
			                    }
                              }
                         }
                         else
                         {
                              for($x=0;$x<sizeof($dietaRecetadaEnf);$x++)
                              {
                                   if($dietaRecetadaEnf[0][observacion])
                                   { $obsMedico=$dietaRecetadaEnf[0][observacion];}
                                   $vect[$b]=$dietaRecetadaEnf[$x][hc_dieta_id];
                              }
                         }
               	}
				$this->salida .= "		<td $mark>".$value[primer_nombre]." ".$value[segundo_nombre]." ".$value[primer_apellido]." ".$value[segundo_apellido]."&nbsp;</td>\n";
				unset($mark);


				$dato_ayuno=$this->VerificarAyunoPaciente($value[ingreso]);
				if($dato_ayuno==1){$ayuno="checksi.png";}else{$ayuno="checkno.png";}
				$imagen_ay="<img src=\"". GetThemePath() ."/images/$ayuno\" width=15 height='15' border='0'>";
				$this->salida .= "		<td align='center'>$imagen_ay</td>\n";
				$i=0;

				foreach ($tiposDieta as $kDieta => $valDieta)
				{
                         if(in_array($valDieta[hc_dieta_id],$vect))
                         {
                                   $img="checkS.gif";
                         }
                         else
                         {
                                   $img="checkN.gif";
                         }

					$imagen="<img src=\"". GetThemePath() ."/images/$img\"  border='0'>";
					$this->salida .= "	<td align='center'>$imagen</td>\n";unset($img);
					$i++;
				}
				//$this->salida .= "		<td><textarea \"style=width:100%\" cols='40' rows='3'  name='observaciones[".$value[ingreso]."]' class='textarea' READONLY>".$obsMedico."</textarea></td>\n"; unset($obsMedico);
				$this->salida .= "<td>$obsMedico</td>\n";unset($obsMedico);
				$accion=ModuloGetURL('app','EstacionE_ControlPacientes','user','ModificarDietaEnfermera',
				array("nombre"=>$nombre,"datos_estacion"=>$datos_estacion,"value"=>$value,"vect"=>$vect,'observaenf'=>$dietaRecetadaEnf[0][observacion]));
				$this->salida .= "	<td align='center'><a href='$accion'>MODIFICAR</a></td>\n";
				$this->salida .= "	</tr>\n";
				unset($vect);
			}


			//revisar las de consulta

			foreach ($pacientes['urgencias'] as $key => $value)
			{
				$estilo = "modulo_list_claro";
				$this->salida .= "	<tr class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>\n";

				if($value[sw_estado]==1)
				{
                         $this->salida .= "	<td   align=\"center\"><img src=\"". GetThemePath() ."/images/consulta_ur.png\" border='0' title='PACIENTE EN CONSULTA'></td>\n";
                         $this->salida .= "	<td  colspan='2' align=\"center\"><label class=label_mark>CONSULTA</label></td>\n";
				}
				elseif($value[sw_estado]==7)
				{
                         $this->salida .= "	<td   align=\"center\"><img src=\"". GetThemePath() ."/images/egreso.png\" border='0' title='EGRESO ESTACION'></td>\n";
                         $this->salida .= "	<td  colspan='2' align=\"center\"><label class=label_mark>CONSULTA - ATENCION ENFERMERIA</label></td>\n";
				}
				//$this->salida .= "		<td>".$value[cama]."&nbsp;</td>\n";

				$dietaRecetada = $this->VerificaDietaRecetadaxMedico($value[ingreso]);
				$dietaRecetadaEnf =$this->VerificaDietaEnf($value[ingreso]);

				$nombre="".$value[primer_nombre]."&nbsp;".$value[segundo_nombre]."&nbsp;".$value[primer_apellido]."&nbsp;".$value[segundo_apellido]."";
				if($dietaRecetada!='show')
				{
                         $obsMedico=$dietaRecetada[0][observaciones];
                         if($dietaRecetada)
					{$mark='class=label_mark';}else{$mark='';}

					for($b=0;$b<sizeof($dietaRecetada);$b++)
					{
                              $vect[$b] = $dietaRecetada[$b][hc_dieta_id];
                              $fechaComp = $dietaRecetada[$b][fecha_registro];
					}
				}
				if($dietaRecetadaEnf!='show')
				{
                         if(sizeof($vect)>0)
                         {
                              for($z=0;$z<sizeof($dietaRecetadaEnf);$z++)
                              {
                                   if($fechaComp < $dietaRecetadaEnf[$z][fecha_registro])
                                   {
                                   	$b=$b+1;
                                        $vect[$b] = $dietaRecetadaEnf[$z][hc_dieta_id];
                                        if($dietaRecetadaEnf[0][observacion])
			                         { $obsMedico=$dietaRecetadaEnf[0][observacion];}
			                    }
                              }
                         }
                         else
                         {
                              for($x=0;$x<sizeof($dietaRecetadaEnf);$x++)
                              {
                                   if($dietaRecetadaEnf[0][observacion])
                                   { $obsMedico=$dietaRecetadaEnf[0][observacion];}
							$vect[$b]=$dietaRecetadaEnf[$x][hc_dieta_id];
                              }
                         }
				}
				$this->salida .= "		<td $mark>".$value[primer_nombre]." ".$value[segundo_nombre]." ".$value[primer_apellido]." ".$value[segundo_apellido]."&nbsp;</td>\n";
				unset($mark);


				$dato_ayuno=$this->VerificarAyunoPaciente($value[ingreso]);
                    if($dato_ayuno==1){$ayuno="checksi.png";}else{$ayuno="checkno.png";}
                    $imagen_ay="<img src=\"". GetThemePath() ."/images/$ayuno\" width=15 height='15' border='0'>";
				$this->salida .= "		<td align='center'>$imagen_ay</td>\n";
				$i=0;

				foreach ($tiposDieta as $kDieta => $valDieta)
				{
                         if(in_array($valDieta[hc_dieta_id],$vect))
                         {
                                   $img="checkS.gif";
                         }
                         else
                         {
                                   $img="checkN.gif";
                         }

					$imagen="<img src=\"". GetThemePath() ."/images/$img\"  border='0'>";
					$this->salida .= "	<td align='center'>$imagen</td>\n";unset($img);
					$i++;
				}
				//$this->salida .= "		<td><textarea \"style=width:100%\" cols='40' rows='3'  name='observaciones[".$value[ingreso]."]' class='textarea' READONLY>".$obsMedico."</textarea></td>\n"; unset($obsMedico);
				$this->salida .= "<td>$obsMedico</td>\n";unset($obsMedico);
				$accion=ModuloGetURL('app','EstacionE_ControlPacientes','user','ModificarDietaEnfermera',
				array("nombre"=>$nombre,"datos_estacion"=>$datos_estacion,"value"=>$value,"vect"=>$vect,'observaenf'=>$dietaRecetadaEnf[0][observacion]));
				$this->salida .= "	<td align='center'><a href='$accion'>MODIFICAR</a></td>\n";
				$this->salida .= "	</tr>\n";
				unset($vect);
			}



			$this->salida .= "	</table>\n";
			//$this->salida .= "<div class='normal_10' align='center'><br><input type='submit' class='input-submit' name='submit' value='SOLICITAR DIETAS'>\n";
			$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
			$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>\n";
			$this->salida .= themeCerrarTabla();
			$this->salida .= "</form>\n";

		return true;
	}//FrmPrescripcionDietas()






		/*
		*		FrmSignosVitales
		*
		*		Formulario que permite ingresar los signos vitales al paciente seleccioado
		*
		*		@Author Rosa Maria Angel
		*		@access Private
		*		@param array datos del paciente
		*		@param array datos de la estacion
		*		@param integer cantidad
		*		@param string nombre de la funcion que llama a esta funcion
		*		@param array paramentros de la funcionque llama a esta funcion
		*		@return boolean
		*/
		function FrmSignosVitales($estacion,$datos_estacion,$cantidad,$referer_name,$referer_parameters)
		{

   		$this->salida .= ThemeAbrirTabla($datos_estacion['control_descripcion']);
			$href = ModuloGetURL('app','EstacionE_ControlPacientes','user','InsertarSignosVitales',array("datos_estacion"=>$datos_estacion,"estacion"=>$estacion,"referer_name"=>$referer_name,"referer_parameters"=>$referer_parameters));
			$this->salida .= "<form name='signos_vitales' action='".$href."' method='POST'><br>\n";
			$this->salida .= "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list_title\">\n";
			$this->salida .= "		<tr class=\"modulo_table_title\">\n";
			$this->salida .= "			<td>PACIENTE</td>\n";
			//$this->salida .= "			<td>ID</td>\n";
			$this->salida .= "			<td>HABITACION</td>\n";
			$this->salida .= "			<td>CAMA</td>\n";
			$this->salida .= "			<td>FECHA CONTROL</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
			$this->salida .= "			<td>".$datos_estacion['NombrePaciente']."</td>\n";
			//$this->salida .= "			<td>".$datos_estacion[tipo_id_paciente]." ".$datos_paciente[paciente_id]."</td>\n";
			$this->salida .= "			<td>".$datos_estacion[pieza]."</td>\n";
			$this->salida .= "			<td>".$datos_estacion[cama]."</td>\n";
			$this->salida .= "			<td>\n";
			//$this->salida .= "				<input type='text' class='input-text' name='Hora' value='".$hora."' size='8' maxlength='8'>\n";

			//fecha de nacimiento del paciente para determinar si es de neonatos
			$hora_inicio_turno = ModuloGetVar('app','EstacionEnfermeria','HoraInicioTurnoControles');
			$rango_turno = ModuloGetVar('app','EstacionEnfermeria','RangoVerTurnosControles');
               
               $hora_inicio_turno = "00:00:00";			
               if(date("H:i:s") <= $hora_inicio_turno)
			{
				list($fecha_control,$hora_control)=explode(" ",date("Y-m-d H:i:s"));
				list($h,$m,$s)=explode(":",$hora_control);
			}
			else
			{//si la hora actual es menor que la de inicio turno, se debe poner la fecha anterior
				list($fecha_control,$hora_control)=explode(" ",date("Y-m-d H:i:s",mktime(date("h"),date("i"),date("s"),date("m"),(date("d")-1),date("Y"))));
				list($h,$m,$s)=explode(":",$hora_control);
			}

			$i=0;
			$this->salida .= "				<select name='selectHora' class='select'>\n";
			for($j=0; $j<$rango_turno; $j++)
			{
				list($anno, $mes, $dia)=explode("-",$fecha_control);
				if ($i==23)
				{
					list($h,$m,$s)=explode(":",$hora_inicio_turno);
					$i=date("H",mktime($h+$j,$m,$s,$mes,$dia,$anno));
					$fecha2=date("Y-m-d H:i:s",mktime(24,0,0,$mes,$dia,$anno));
					$fecha_control=date("Y-m-d",mktime(24,0,0,$mes,$dia,$anno));
				}
				else
				{
					list($h,$m,$s)=explode(":",$hora_inicio_turno);
					$i=date("H",mktime($h+$j,$m,$s,$mes,$dia,$anno));
					$fecha2=date("Y-m-d H:i:s",mktime($i,0,0,$mes,$dia,$anno));
					$fecha_control=date("Y-m-d",mktime($i,0,0,$mes,$dia,$anno));
				}
				if(empty($selectHora)){
					if($i == date("H")){ $selected = "selected='true'";} else { $selected = "";}
				}
				else
				{//viene de insertar pero no lo hizo, asi que pongo por defecto los valores enviados
					list($A,$B) = explode(" ",$selectHora);
					if($i == $B){ $selected = "selected='true'";} else { $selected = "";}
				}
				#################################################
				list($yy,$mm,$dd)=explode(" ",$fecha_control);//(date("m"),(date("d")),date("Y")));
				if($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")),date("Y"))))){
					$show = "Hoy a las";
				}
				elseif($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")+1),date("Y"))))){
					$show = "Ma?ana a las";
				}
				elseif($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")-1),date("Y"))))){
					$show = "Ayer a las";
				}
				else{
					$show = $fecha_control;
				}
				###########################
                    	
                    $this->salida .= "<option value='".date("Y-m-d")." ".$i."' $selected>".$i."</option>\n";

			}//fin for
               
               if(!empty($_REQUEST['selectHora']))
               {
               	$horas_R = explode(" ", $_REQUEST['selectHora']);
	 			$this->salida .= "<option value='".date("Y-m-d")." ".$horas_R[1]."' selected='true'>".$horas_R[1]."</option>\n";
               }
			$this->salida .= "				</select>:&nbsp;\n";
			$this->salida .= "				<select name='selectMinutos' class='select'>\n";

			for($j=0; $j<=59; $j++)
			{
				if(empty($selectMinutos)){
					if($j == date("i")){ $selected = "selected='true'";} else { $selected = "";}
				}
				else
				{//viene de insertar pero no lo hizo, asi que pongo por defecto los valores enviados
					list($A,$B) = explode(" ",$selectMinutos);
					if($j == $A){ $selected = "selected='true'";} else { $selected = "";}
				}
				if ($j<10){
					$this->salida .= "			<option value='0$j:00' $selected>0$j</option>\n";
				}
				else{
					$this->salida .= "			<option value='$j:00' $selected>$j</option>\n";
				}
			}
			$this->salida .= "				</select>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table><br><br>\n";

/*-------------------------------------------
				Segemento que imprime en pantalla
				los Signos Vitales que se tomaran al paciente.
			  -------------------------------------------
			*/

			$this->salida .= "<table align=\"center\" width=\"88%\" border=\"0\" class=\"modulo_table_list\">\n";
			$this->salida .= $this->SetStyle("MensajeError",11);
			$this->salida .= "<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "<td align=\"center\" >FREC. CARD.</td>\n";
			$this->salida .= "<td align=\"center\" >FREC. RESP.</td>\n";
			$this->salida .= "<td align=\"center\" >PVC</td>\n";
			$this->salida .= "<td align=\"center\" >PIC</td>\n";
			$this->salida .= "<td align=\"center\" >PESO</td>\n";
			$this->salida .= "<td align=\"center\">TEMP.</td>\n";
			$this->salida .= "<td align=\"center\">MANUAL</td>\n";
			$this->salida .= "<td  align=\"center\">T.INCUB</td>\n";
			$this->salida .= "<td  align=\"center\">SAT O<sub>2</sub></td>\n";
			$this->salida .= "</tr>\n";
			$this->salida .= "<tr ".$this->Lista(1).">\n";
			$this->salida .= "<td align=\"center\"><input type='text' class='input-text' name='fc' value='".$_REQUEST['fc']."' size='6' maxlength='5'> X min.</td>\n";
			$this->salida .= "<td align=\"center\"><input type='text' class='input-text' name='fr' value='".$_REQUEST['fr']."' size='6' maxlength='5'> X min.</td>\n";
			$this->salida .= "<td align=\"center\"><input type='text' class='input-text' name='pvc' value='".$_REQUEST['pvc']."' size='6' maxlength='6'> cmH<sub>2</sub>O</td>\n";
			$this->salida .= "<td align=\"center\"><input type='text' class='input-text' name='pic' value='".$_REQUEST['pic']."' size='6' maxlength='6'> cmH<sub>2</sub>O</td>\n";
			$this->salida .= "<td align=\"center\"><input type='text' class='input-text' name='peso' value='".$_REQUEST['peso']."' size='6' maxlength='6'> Kg.</td>\n";
			$this->salida .= "<td align='center'><input type='text' class='input-text' name='tpiel' value='".$_REQUEST['tpiel']."' size='6' maxlength='5'> ?C</td>\n";
			$this->salida .= "<td align='center'><input type='text' class='input-text' name='manual' value='".$_REQUEST['manual']."' size='6' maxlength='6'> ?C</td>\n";
			$this->salida .= "<td align='center'><input type='text' class='input-text' name='servo' value='".$_REQUEST['servo']."' size='6' maxlength='6'> ?C</td>\n";
			$this->salida .= "<td align='center'><input type='text' class='input-text' name='sato' value='".$_REQUEST['sato']."' size='6' maxlength='3'> %</td>\n";
			$this->salida .= "</tr>\n";
			$this->salida .= "</table>\n\n";

			/*-------------------------------------------
				Segemento que imprime en pantalla
				los Signos Vitales que se tomaran al paciente.
			  -------------------------------------------
			*/
      $sitios=$this->GetSignosVitalesSitios();
			$this->salida .= "<table colspan=\"2\" align=\"center\" width=\"88%\" border=\"0\" class=\"modulo_table_list\">\n";
			$this->salida .= "<tr align='center' class='modulo_table_list_title'>\n";//class=\"modulo_table_list_title\"
			$this->salida .= "<td width=\"50%\">TENSION ARTERIAL</td>\n";
			$this->salida .= "<td width=\"50%\">OBSERVACION</td>\n";
			$this->salida .= "</tr>\n";
			$this->salida .= "<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "<td width=\"50%\">";
			$this->salida .= "<label class=\"label\">&nbsp;T.A</label>&nbsp;&nbsp;<input type=\"text\" class='input-text' name=\"taa\" value='".$_REQUEST['taa']."' size='6' maxlength='5'>&nbsp;<b>/</b>&nbsp;
			<input type=\"text\" class='input-text' name=\"tab\" value='".$_REQUEST['tab']."' size='6' maxlength='5'>";//<br>";//TENSION ARTERIAL
			$this->salida .= "<label class=\"label\">&nbsp;&nbsp;&nbsp;SITIO</label>";

			if (!empty($sitios)) {
				$this->salida .="&nbsp;<select name=\"sitio\" class='select'>";//rowspan='3'
				$this->salida .="<option value=-1>- - - -</option>";
				$this->SetOptionsSignosVitalesSitios($sitios,$_REQUEST['sitio']);
				$this->salida .="</select>\n";
			}
			$this->salida .= "<table colspan=\"2\" align=\"center\" width=\"100%\" border=\"1\" class=\"modulo_table_list\">\n";
			$this->salida .="<tr align=\"center\"><td colspan=\"12\" class='modulo_table_list_title'>ESCALA VISUAL ANALOGA - EVA</td></tr>";
			$this->salida .="<tr align=\"center\">";
			$this->salida .="<td rowspan=\"2\">Menor Dolor</td>";
			$fecha_nac=$this->GetFechaNacPaciente($datos_estacion[ingreso]);
			$FechaFin = date("Y-m-d");
			$edad_paciente = CalcularEdad($fecha_nac,$FechaFin);
			if ($edad_paciente[anos] < ModuloGetVar('','','max_edad_pediatrica'))
			{
				$this->salida .="<td><img src=\"".GetThemePath()."/images/signovital/no_dolor.png\" border=0></td>";
				$this->salida .="<td><img src=\"".GetThemePath()."/images/signovital/leve.png\" border=0></td>";
				$this->salida .="<td><img src=\"".GetThemePath()."/images/signovital/moderado.png\" border=0></td>";
				$this->salida .="<td><img src=\"".GetThemePath()."/images/signovital/severopain.png\" border=0></td>";
				$this->salida .="<td><img src=\"".GetThemePath()."/images/signovital/muyseveropain.png\" border=0></td>";
				$this->salida .="<td rowspan=\"2\">Mayor Dolor</td>";
				$this->salida .="</tr>";
				$this->salida .="<tr>";

				if ($_REQUEST['eva'] != 0 )
				{
					$this->salida .="<td align=\"center\"><input type=\"radio\" name=\"eva\" value=\"0\"></td>";
				}
				else
				{
					$this->salida .="<td align=\"center\"><input type=\"radio\" checked name=\"eva\" value=\"0\"></td>";
				}
				if ($_REQUEST['eva'] != 1 )
				{
					$this->salida .="<td align=\"center\"><input type=\"radio\" name=\"eva\" value=\"1\"></td>";
				}
				else
				{
					$this->salida .="<td align=\"center\"><input type=\"radio\" checked name=\"eva\" value=\"1\"></td>";
				}
				if ($_REQUEST['eva'] != 2 )
				{
					$this->salida .="<td align=\"center\"><input type=\"radio\" name=\"eva\" value=\"2\"></td>";
				}
				else
				{
					$this->salida .="<td align=\"center\"><input type=\"radio\" checked name=\"eva\" value=\"2\"></td>";
				}

				if ($_REQUEST['eva'] != 3 )
				{
					$this->salida .="<td align=\"center\"><input type=\"radio\" name=\"eva\" value=\"3\"></td>";
				}
				else
				{
					$this->salida .="<td align=\"center\"><input type=\"radio\" checked name=\"eva\" value=\"3\"></td>";
				}

				if ($_REQUEST['eva'] != 4 )
				{
					$this->salida .="<td align=\"center\"><input type=\"radio\" name=\"eva\" value=\"4\"></td>";
				}
				else
				{
					$this->salida .="<td align=\"center\"><input type=\"radio\" checked name=\"eva\" value=\"4\"></td>";
				}
			}
			else
			{
				$this->salida .="<td>1</td>";
				$this->salida .="<td>2</td>";
				$this->salida .="<td>3</td>";
				$this->salida .="<td>4</td>";
				$this->salida .="<td>5</td>";
				$this->salida .="<td>6</td>";
				$this->salida .="<td>7</td>";
				$this->salida .="<td>8</td>";
				$this->salida .="<td>9</td>";
				$this->salida .="<td>10</td>";
				$this->salida .="<td rowspan=\"2\">Mayor Dolor</td>";

				$this->salida .="</tr>";
				$this->salida .="<tr>";
				if ($_REQUEST['eva'] != 1 )
				{
					$this->salida .="<td><input type=\"radio\" name=\"eva\" value=\"1\"></td>";
				}
				else
				{
					$this->salida .="<td><input type=\"radio\" checked name=\"eva\" value=\"1\"></td>";
				}
				if ($_REQUEST['eva'] != 2 )
				{
					$this->salida .="<td><input type=\"radio\" name=\"eva\" value=\"2\"></td>";
				}
				else
				{
					$this->salida .="<td><input type=\"radio\" checked name=\"eva\" value=\"2\"></td>";
				}
				if ($_REQUEST['eva'] != 3 )
				{
					$this->salida .="<td><input type=\"radio\" name=\"eva\" value=\"3\"></td>";
				}
				else
				{
					$this->salida .="<td><input type=\"radio\" checked name=\"eva\" value=\"3\"></td>";
				}

				if ($_REQUEST['eva'] != 4)
				{
					$this->salida .="<td><input type=\"radio\" name=\"eva\" value=\"4\"></td>";
				}
				else
				{
					$this->salida .="<td><input type=\"radio\" checked name=\"eva\" value=\"4\"></td>";
				}

				if ($_REQUEST['eva'] != 5 )
				{
					$this->salida .="<td><input type=\"radio\" name=\"eva\" value=\"5\"></td>";
				}
				else
				{
					$this->salida .="<td><input type=\"radio\" checked name=\"eva\" value=\"5\"></td>";
				}

				if ($_REQUEST['eva'] != 6 )
				{
					$this->salida .="<td><input type=\"radio\" name=\"eva\" value=\"6\"></td>";
				}
				else
				{
					$this->salida .="<td><input type=\"radio\" checked name=\"eva\" value=\"6\"></td>";
				}

				if ($_REQUEST['eva'] != 7 )
				{
					$this->salida .="<td><input type=\"radio\" name=\"eva\" value=\"7\"></td>";
				}
				else
				{
					$this->salida .="<td><input type=\"radio\" checked name=\"eva\" value=\"7\"></td>";
				}

				if ($_REQUEST['eva'] != 8 )
				{
					$this->salida .="<td><input type=\"radio\" name=\"eva\" value=\"8\"></td>";
				}
				else
				{
					$this->salida .="<td><input type=\"radio\" checked name=\"eva\" value=\"8\"></td>";
				}

				if ($_REQUEST['eva'] != 9 )
				{
					$this->salida .="<td><input type=\"radio\" name=\"eva\" value=\"9\"></td>";
				}
				else
				{
					$this->salida .="<td><input type=\"radio\" checked name=\"eva\" value=\"9\"></td>";
				}

				if ($_REQUEST['eva'] != 10 )
				{
					$this->salida .="<td><input type=\"radio\" name=\"eva\" value=\"10\"></td>";
				}
				else
				{
					$this->salida .="<td><input type=\"radio\" checked name=\"eva\" value=\"10\"></td>";
				}
			}
			$this->salida .="</tr></table>";
			$this->salida .= "</td>\n";

			$this->salida .= "<td width=\"50%\" align='center'>\n";
			$this->salida .= "<textarea name=\"observacion\" cols=\"50\" rows=\"4\" class=\"textarea\">".$_REQUEST['observacion']."</textarea>";//TENSION ARTERIAL
			$this->salida .= "<br><br><input type='submit' class='input-submit' name='Save$pfj' value='Insertar'>";
			$this->salida .= "</td>\n";
			$this->salida .= "</tr>\n";
			$this->salida .= "	</table><br><br>\n";/*
			$this->salida .= "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list\">\n";
			$this->salida .= $this->SetStyle("MensajeError",11);
			$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "			<td>FRECUENCIA CARDIACA (min)</td>\n";
			$this->salida .= "			<td>PVC</td>\n";
			$this->salida .= "			<td>PA NI</td>\n";
			$this->salida .= "			<td>PA I</td>\n";
			$this->salida .= "			<td>MEDIA</td>\n";
			$this->salida .= "			<td>SITIO</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr ".$this->Lista(1)."' align='center'>\n";
			list($fecha,$hora)=explode(" ",$datos_estacion['Hora']);
			$this->salida .= "			<td><input type='text' class='input-text' name='fc' value='".$_REQUEST['fc']."' size='5' maxlength='5'></td>\n";
			$this->salida .= "			<td><input type='text' class='input-text' name='pvc' value='".$_REQUEST['pvc']."' size='6' maxlength='6'></td>\n";
			$this->salida .= "			<td><input type='text' class='input-text' name='pani_alta' value='".$_REQUEST['pani_alta']."' size='6' maxlength='6'>&nbsp;<b>/</b>&nbsp;<input type='text' class='input-text' name='pani_baja' value='".$_REQUEST['pani_baja']."' size='6' maxlength='6'></td>\n";
			$this->salida .= "			<td><input type='text' class='input-text' name='pai_alta' value='".$_REQUEST['pai_alta']."' size='6' maxlength='6'>&nbsp;<b>/</b>&nbsp;<input type='text' class='input-text' name='pai_baja' value='".$_REQUEST['pai_baja']."' size='6' maxlength='6'></td>\n";
			$this->salida .= "			<td><input type='text' class='input-text' name='media' value='".$_REQUEST['media']."' size='6' maxlength='6'></td>\n";
			$sitios=$this->GetSignosVitalesSitios();
			if (!empty($sitios)) {
				$this->salida .= "			<td rowspan='3'><select name='sitio' class='select'>";
				$this->salida .= "				<option value='-1'>- - -</option>";
				$this->SetOptionsSignosVitalesSitios($sitios,'');
				$this->salida .= "			</select></td>\n";
			}
			else {
				$this->error = "Error al consultar la tabla \"hc_signos_vitales_sitios\"<br>";
				$this->mensajeDeError = "";
				return false;
			}
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "			<td>TEMPERATURA (?)</td>\n";
			$this->salida .= "			<td>SERVO</td>\n";
			$this->salida .= "			<td>MANUAL</td>\n";
			$this->salida .= "			<td>PIC (Cm)</td>\n";
			$this->salida .= "			<td>PESO (Kg)</td>\n";
			//$this->salida .= "			<td ".$this->Lista(1)."></td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr ".$this->Lista(1)."'align='center'>\n";
			$this->salida .= "			<td align='center'><input type='text' class='input-text' name='tpiel' value='".$_REQUEST['tpiel']."' size='6' maxlength='5'></td>\n";
			$this->salida .= "			<td align='center'><input type='text' class='input-text' name='servo' value='".$_REQUEST['servo']."' size='6' maxlength='6'></td>\n";
			$this->salida .= "			<td align='center'><input type='text' class='input-text' name='manual' value='".$_REQUEST['manual']."' size='6' maxlength='6'></td>\n";
			$this->salida .= "			<td align='center'><input type='text' class='input-text' name='pic' value='".$_REQUEST['pic']."' size='6' maxlength='6'></td>\n";
			$this->salida .= "			<td align='center'><input type='text' class='input-text' name='peso' value='".$_REQUEST['peso']."' size='7' maxlength='7'></td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n\n";*/

			$this->salida .= "	<input type='hidden' name='ingreso' value='".$datos_estacion['ingreso']."'>\n";
		//	$this->salida .= "<div class='normal_10' align='center'><br><input type='submit' class='input-submit' name='Save' value='GUARDAR'>";
			$this->salida .= "</form>\n";

			//$href = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallControlesPacientes',array("control_id"=>$datos_estacion['control_id'],"estacion"=>$estacion,"control_descripcion"=>$datos_estacion['control_descripcion']));
			$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("control_id"=>$datos_estacion['control_id'],"estacion"=>$estacion,"control_descripcion"=>$datos_estacion['control_descripcion']));
			$this->salida .= "<div class='normal_10' align='center'><BR><a href='".$href."'>Volver Menu</a><br>";

			/*if (!isset($_REQUEST['cantidad']))
				$this->ShowSignosVitales($estacion,$datos_estacion,1);
			else
				$this->ShowSignosVitales($estacion,$datos_estacion,0);*/
			$this->ShowSignosVitales($estacion,$datos_estacion,0);
			$this->salida .= themeCerrarTabla();
			return true;
		}

		/**
	*		SetStyle => Muestra mensajes
	*
	*		crea una fila para poner el mensaje de "Faltan campos por llenar" cambiando a color rojo
	*		el label del campo "obligatorio" sin llenar
	*
	*		@Author Alexander Giraldo
	*		@access Private
	*		@return string
	*		@param string => nombre del input y estilo que qued&oacute; vacio
	*/
	function SetStyle($campo,$colum)//CHANGE
	{
				if ($this->frmError[$campo] || $campo=="MensajeError"){
					if ($campo=="MensajeError"){
						return ("<tr><td class='label_error' colspan='$colum' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
					}
					return ("label_error");
				}
			return ("label");
	}

	/*
		*		SetOptionsSignosVitalesSitios
		*
		*
		*		@Author Arley Velasquez
		*		@access Private
		*/
		function SetOptionsSignosVitalesSitios($sitio,$valor)
		{
			for($i=0; $i<sizeof($sitio); $i++)
			{
				if ($sitio[$i]['sitio_id']==$valor)
					$this->salida .= "<option value='".$sitio[$i]['sitio_id']."' selected>".$sitio[$i]['descripcion']."</option>\n";
				else
					$this->salida .= "<option value='".$sitio[$i]['sitio_id']."'>".$sitio[$i]['descripcion']."</option>\n";
			}
			return true;
		}

		/*
		*		ShowSignosVitales
		*
		*		Muestra los signos vitales registrados del paciente seleccionado
		*
		*		@Author Rosa Maria Angel
		*		@access Private
		*		@param array datos del paciente
		*		@param array datos de la estacion
		*		@param integer numero de filas a mostrar
		*		@return boolean
		*/
		function ShowSignosVitales($estacion,$datos_estacion,$contador)
		{
			$vectorSignos = $this->GetSignosVitales($datos_estacion['ingreso']);  
			if(!$vectorSignos){
				return false;
			}
			elseif($vectorSignos != "ShowMensaje")
			{
				if (empty($contador)){
					$contador=sizeof($vectorSignos);
				}
				$this->salida .= "<br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
				$this->salida .= "<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "<td>FECHA</td>\n";
				$this->salida .= "<td>HORA</td>\n";
				$this->salida .= "<td>F.C.</td>\n";
				$this->salida .= "<td>F.R.</td>\n";
				$this->salida .= "<td>PVC</td>\n";
				$this->salida .= "<td>PIC</td>\n";
				$this->salida .= "<td>PESO (Kg)</td>\n";
				$this->salida .= "<td>T.A.</td>\n";
				$this->salida .= "<td>MEDIA</td>\n";
				$this->salida .= "<td>SITIO TOMA T.A</td>\n";
				$this->salida .= "<td>TEMP.</td>\n";
				$this->salida .= "<td>T. INC</td>\n";
				$this->salida .= "<td>MANUAL</td>\n";
				$this->salida .= "<td>EVA</td>\n";
				$this->salida .= "<td>SAT O2</td>\n";
				$this->salida .= "<td>USUARIO</td>\n";
				$this->salida .= "</tr>\n";

				$cont=1;
				//while ($cont<$contador && $data= $resultado->FetchNextObject($toUpper=false))
				while ($cont <= sizeof($vectorSignos) && $cont <= $contador)
				{
					list($fecha,$hora) = explode(" ",$vectorSignos[$cont-1][fecha]);//substr(,0,10);
					$this->salida .= "		<tr ".$this->Lista($cont)."' align='center'>\n";
					if($fecha == date("Y-m-d")) {
						$fecha = "HOY $hora";
					}
					elseif($fecha == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y")))){
						$fecha = "AYER $hora";
					}
					else {
						$fecha = $fecha;
					}
					//---------------Alerta de temperatura
					if (!IncludeLib('datospaciente')){
						$this->error = "Error al cargar la libreria [datospaciente].";
						$this->mensajeDeError = "datospaciente";
						return false;
					}
					$x = GetDatosPaciente("","",$datos_estacion['ingreso']);//funcion del api realizada por jaime
					$Edad = CalcularEdad($x[fecha_nacimiento],'');
					list($Edad,$k) = explode(" ",$Edad[edad_aprox]);
					//temperatura es 20;
					$k = $this->GetAlarmaRangoControl(20,$x[sexo_id],$Edad,$vectorSignos[$cont-1][temp_piel]);
					if($k === "Alarma"){$estilo = "class='alerta'";} else {$estilo = "";}
					//---------------fin Alerta de temperatura
					//------- valido si estan en ceros que pongan "--";
					if($vectorSignos[$cont-1][fc] == 0) $fc = "--"; else $fc = $vectorSignos[$cont-1][fc];
					if($vectorSignos[$cont-1][fr] == 0) $fr = "--"; else $fr = $vectorSignos[$cont-1][fr];
					$fecha_nac=$this->GetFechaNacPaciente($datos_estacion[ingreso]);
					$FechaFin = date("Y-m-d");
					$edad_paciente = CalcularEdad($fecha_nac,$FechaFin);
					if ($edad_paciente[anos] < ModuloGetVar('','','max_edad_pediatrica'))
					{
						if($vectorSignos[$cont-1][evaluacion_dolor] == 0) $eva = "0"; else $eva = $vectorSignos[$cont-1][evaluacion_dolor];
					}
					else
					{
						if($vectorSignos[$cont-1][evaluacion_dolor] == 0) $eva = "--"; else $eva = $vectorSignos[$cont-1][evaluacion_dolor];
					}
					if($vectorSignos[$cont-1][pvc] == 0.00) $pvc = "--"; else $pvc = $vectorSignos[$cont-1][pvc];

					if($vectorSignos[$cont-1][ta_alta] == 0.00)
					{$taa = "--";}
					else {$ta_alta = $vectorSignos[$cont-1][ta_alta];}

					if($vectorSignos[$cont-1][ta_baja] == 0.00)
					{$taa = "--";}
					else {$ta_baja = $vectorSignos[$cont-1][ta_baja];}

					if($ta_alta AND $ta_baja)
					{$taa=$ta_alta."/".$ta_baja;}

					if($vectorSignos[$cont-1][media] == 0) $media = "--"; else $media = $vectorSignos[$cont-1][media];
					if($vectorSignos[$cont-1][sato2] == 0) $sato = "--"; else $sato = $vectorSignos[$cont-1][sato2];
					if(empty($vectorSignos[$cont-1][descripcion])) $descripcion = "--"; else $descripcion = $vectorSignos[$cont-1][descripcion];
					if($vectorSignos[$cont-1][temp_piel] == 0) $temp = "--"; else $temp = $vectorSignos[$cont-1][temp_piel];
					if($vectorSignos[$cont-1][servo] == 0.00) $servo = "--"; else $servo = $vectorSignos[$cont-1][servo];
					if($vectorSignos[$cont-1][manual] == 0.00) $manual = "--"; else $manual = $vectorSignos[$cont-1][manual];
					if($vectorSignos[$cont-1][presion_intracraneana] == 0) $presion = "--"; else $presion = $vectorSignos[$cont-1][presion_intracraneana];
					if($vectorSignos[$cont-1][peso] == 0.000) $peso = "--"; else $peso = number_format($vectorSignos[$cont-1][peso],2,',','.');
					if($vectorSignos[$cont-1][sitio_id]=='' OR is_null($vectorSignos[$cont-1][sitio_id])){$sit='--';}else{$sit=$vectorSignos[$cont-1][sitio_id];}

//					if($vectorSignos[fc] == 0) $fc = "--"; else $fc = $data->fc;
					//-------fin valido si estan en ceros que pongan "--";
          			if($sit <> '' and $sit <> '--')
					{
						$sitio=$this->GetSignosVitalesSitios($sit);
					}
					unset($sit);
					//preguntamos si es invasiva=1 o no invasiva=0
					$this->salida .= "<td>".$fecha."</td>\n";
					$this->salida .= "<td>".$hora."</td>\n";
					$this->salida .= "<td>".$fc."</td>\n";
					$this->salida .= "<td>".$fr."</td>\n";
					$this->salida .= "<td>".$pvc."</td>\n";
					$this->salida .= "<td>".$presion."</td>\n";
					$this->salida .= "<td>".$peso."</td>\n";
					$this->salida .= "<td>".$taa."</td>\n";
					$this->salida .= "<td>".$media."</td>\n";
					$this->salida .= "<td>".$sitio[0][descripcion]."</td>\n";
					$this->salida .= "<td $estilo>".$temp."</td>\n";
					$this->salida .= "<td>".$servo."</td>\n";
					$this->salida .= "<td>".$manual."</td>\n";
					$this->salida .= "<td>".$eva."</td>\n";
					$this->salida .= "<td>".$sato."</td>\n";
					//$href = ModuloGetURL('app','EstacionE_ControlPacientes','user','ShowDatosUser',array("usuario"=>$vectorSignos[$cont-1][usuario_id],"estacion"=>$estacion));
					$min='15';
     				$DatosUser = $this->GetDatosUsuarioSistema($vectorSignos[$cont-1][usuario_id]);
					$fechita=explode(" ",$vectorSignos[$cont-1][fecha]);
          			if($vectorSignos[$cont-1][usuario_id]==UserGetUID()
					AND $fechita[0]==date("Y-m-d") AND $datos_estacion[ingreso]==$vectorSignos[$cont-1][ingreso])
					{
                         	list($fechaReh,$horaReg) = explode(" ",$vectorSignos[$cont-1][fecha_registro]);
						$new_hora=date("H:i:s",strtotime("+".$min." min",strtotime($horaReg)));

						if(strtotime($new_hora) > strtotime(date("H:i:s")))
						{
							$href = ModuloGetURL('app','EstacionE_ControlPacientes','user','BorradoSignosVitales',
							array("fecha"=>$vectorSignos[$cont-1][fecha],"estacion"=>$estacion,"datos_estacion"=>$datos_estacion,"contador"=>$contador));
							$nombre=$link_eliminar="&nbsp;<a href='$href'>[Eliminar]</a>";
						}
						else{$nombre=$DatosUser[0][usuario];}
					}
					else{$nombre=$DatosUser[0][usuario];}

					$this->salida .= "			<td>$nombre</td>\n";
					$this->salida .= "		</tr>\n";


           if($vectorSignos[$cont-1][observacion]!='' AND $vectorSignos[$cont-1][observacion] !='NULL')
					 {
							$observacion = $vectorSignos[$cont-1][observacion];
							$this->salida .= "<tr ".$this->Lista($cont)."'>\n";
							$this->salida .= "<td class=\"modulo_table_title\">OBSERVACION</td>\n";
							$this->salida .= "<td colspan=\"15\">".$observacion."</td>\n";
							$this->salida .= "</tr>\n";
					 }
					$cont++;
				}
				$this->salida .= "		</tr>\n";
				$this->salida .= "	</table>\n\n";
				if ($contador<sizeof($vectorSignos)) {
					$href = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmSignosVitales',array("estacion"=>$estacion,"datos_estacion"=>$datos_estacion,"cantidad"=>1));
					$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Ver M&aacute;s</a><br>";
				}
				return true;
			}
		}//ShowSignosVitales


/*
		*		FrmAsistenciaVentilatoria
		*
		*		Formulario que permite ingresar datos de la asistencia ventilatoria del paciente seleccionado
		*
		*		@Author Rosa Maria Angel
		*		@access Private
		*		@param array datos del paciente
		*		@param array datos de la estacion
		*		@return boolean
		*/
		function FrmAsistenciaVentilatoria($estacion,$datos_estacion)
		{
			$this->salida .= ThemeAbrirTabla($datos_estacion['control_descripcion']." - [ ".$estacion['descripcion5']." ]");
			$href = ModuloGetURL('app','EstacionE_ControlPacientes','user','InsertarAsistenciaVentilatoria',array("datos_estacion"=>$datos_estacion,"estacion"=>$estacion));
			$this->salida .= "<form name='signos_vitales' action='".$href."' method='POST'><br>\n";
			$this->salida .= "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_title\">\n";
			$this->salida .= "		<tr class=\"modulo_table_title\">\n";
			$this->salida .= "			<td>PACIENTE</td>\n";
			//$this->salida .= "			<td>ID</td>\n";
			$this->salida .= "			<td>HABITACION</td>\n";
			$this->salida .= "			<td>CAMA</td>\n";
			$this->salida .= "			<td>FECHA CONTROL</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
			$this->salida .= "			<td>".$datos_estacion['NombrePaciente']."</td>\n";
			//$this->salida .= "			<td>".$datos_estacion[tipo_id_paciente]." ".$datos_paciente[paciente_id]."</td>\n";
			$this->salida .= "			<td>".$datos_estacion[pieza]."</td>\n";
			$this->salida .= "			<td>".$datos_estacion[cama]."</td>\n";
			$this->salida .= "			<td>\n";
			//$this->salida .= "				<input type='text' class='input-text' name='Hora' value='".$datos_estacion['Hora']."' size='8' maxlength='8'>\n";
			$hora_inicio_turno = ModuloGetVar('app','EstacionEnfermeria','HoraInicioTurnoControles');
			$rango_turno = ModuloGetVar('app','EstacionEnfermeria','RangoVerTurnosControles');

			if(date("H:i:s") >= $hora_inicio_turno)
			{
				list($fecha_control,$hora_control)=explode(" ",date("Y-m-d H:i:s"));
				list($h,$m,$s)=explode(":",$hora_control);
			}
			else
			{//si la hora actual es menor que la de inicio turno, se debe poner la fecha anterior
				list($fecha_control,$hora_control)=explode(" ",date("Y-m-d H:i:s",mktime(date("h"),date("i"),date("s"),date("m"),(date("d")-1),date("Y"))));
				list($h,$m,$s)=explode(":",$hora_control);
			}

			//$this->salida .= "			 $fecha_control\n";
			$i=0;
			$this->salida .= "				<select name='selectHora' class='select'>\n";
			for($j=0; $j<$rango_turno; $j++)
			{
				list($anno, $mes, $dia)=explode("-",$fecha_control);
				if ($i==23)
				{
					list($h,$m,$s)=explode(":",$hora_inicio_turno);
					$i=date("H",mktime($h+$j,$m,$s,$mes,$dia,$anno));
					$fecha2=date("Y-m-d H:i:s",mktime(24,0,0,$mes,$dia,$anno));
					$fecha_control=date("Y-m-d",mktime(24,0,0,$mes,$dia,$anno));
				}
				else
				{
					list($h,$m,$s)=explode(":",$hora_inicio_turno);
					$i=date("H",mktime($h+$j,$m,$s,$mes,$dia,$anno));
					$fecha2=date("Y-m-d H:i:s",mktime($i,0,0,$mes,$dia,$anno));
					$fecha_control=date("Y-m-d",mktime($i,0,0,$mes,$dia,$anno));
				}
				if(empty($selectHora)){
					if($i == date("H")){ $selected = "selected='true'";} else { $selected = "";}
				}
				else
				{//viene de insertar pero no lo hizo, asi que pongo por defecto los valores enviados
					list($A,$B) = explode(" ",$selectHora);
					if($i == $B){ $selected = "selected='true'";} else { $selected = "";}
				}
				#################################################
				list($yy,$mm,$dd)=explode(" ",$fecha_control);//(date("m"),(date("d")),date("Y")));
				if($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")),date("Y"))))){
					$show = "Hoy a las";
				}
				elseif($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")+1),date("Y"))))){
					$show = "Ma?ana a las";
				}
				elseif($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")-1),date("Y"))))){
					$show = "Ayer a las";
				}
				else{
					$show = $fecha_control;
				}
				###########################
				$this->salida .= "				<option value='".$fecha_control." ".$i."' $selected>".$show." ".$i."</option>\n";
			}//fin for
			$this->salida .= "				</select>:&nbsp;\n";
			$this->salida .= "				<select name='selectMinutos' class='select'>\n";

			for($j=0; $j<=59; $j++)
			{
				if(empty($selectMinutos)){
					if($j == date("i")){ $selected = "selected='true'";} else { $selected = "";}
				}
				else
				{//viene de insertar pero no lo hizo, asi que pongo por defecto los valores enviados
					list($A,$B) = explode(" ",$selectMinutos);
					if($j == $A){ $selected = "selected='true'";} else { $selected = "";}
				}

				if ($j<10){
					$this->salida .= "			<option value='0$j:00' $selected>0$j</option>\n";
				}
				else{
					$this->salida .= "			<option value='$j:00' $selected>$j</option>\n";
				}
			}
			$this->salida .= "				</select>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table><br><br>\n";
			$this->salida .= "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
			$this->salida .= $this->SetStyle("MensajeError",10);
			$this->salida .= "		<tr class=\"modulo_table_list_title\" align='center'>\n";
			$this->salida .= "			<td>MODO</td>\n";
			$this->salida .= "			<td>FIO2</td>\n";
			$this->salida .= "			<td>F. RESP.</td>\n";
			$this->salida .= "			<td>F. VENT</td>\n";
			$this->salida .= "			<td>ESPONT</td>\n";
			$this->salida .= "			<td>VOL/MIN</td>\n";
			$this->salida .= "			<td>SENS</td>\n";
			$this->salida .= "			<td>P. INSP</td>\n";
			$this->salida .= "			<td colspan='2'>TI</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr ".$this->Lista(1)."' align='center'>\n";
			$modos=$this->GetAsistenciaVentilatoriaModos();
			if (!empty($modos)) {
				$this->salida .= "			<td><select name='modo' class='select'>";
				$this->SetOptionsAsistenciaVentilatoriaModos($modos,'');
				$this->salida .= "			</select></td>\n";
			}
			else {
				$this->error = "Error al consultar la tabla \"hc_asistencia_ventilatoria_modos\"<br>";
				$this->mensajeDeError = "";
				return false;
			}

			$f102=$this->GetControlOxiConcentraciones('',1);
			if (!empty($f102))
			{
				$this->salida .= "			<td align='center'>\n";
				$concentracion = $this->GetControlOxiConcentraciones('',1);
				$this->salida .= "				<select name='f102' class='select'>\n";
				$this->salida .= "					".$concentracion;
				$this->salida .= "				</select>\n";
				$this->salida .= "			</td>\n";
			}
			else {
				$this->error = "Error al consultar la tabla \"hc_asistencia_ventilatoria_f102\"<br>";
				$this->mensajeDeError = "";
				return false;
			}
			$this->salida .= "			<td><input type='text' class='input-text' name='fr_respiratoria' value='$_REQUEST[fr_respiratoria]' size='5' maxlength='5'></td>\n";
			$this->salida .= "			<td><input type='text' class='input-text' name='fr_ventilatoria' value='$_REQUEST[fr_ventilatoria]' size='6' maxlength='6'></td>\n";
			$this->salida .= "			<td><input type='text' class='input-text' name='expontanea' value='$_REQUEST[expontanea]' size='6' maxlength='6'></td>\n";
			$this->salida .= "			<td><input type='text' class='input-text' name='volumen' value='$_REQUEST[volumen]' size='6' maxlength='6'></td>\n";
			$this->salida .= "			<td><input type='text' class='input-text' name='sens' value='$_REQUEST[sens]' size='5' maxlength='5'></td>\n";
			$this->salida .= "			<td><input type='text' class='input-text' name='p_insp' value='$_REQUEST[p_insp]' size='6' maxlength='6'></td>\n";
			$this->salida .= "			<td colspan='2'><input type='text' class='input-text' name='ti' value='$_REQUEST[ti]' size='6' maxlength='6'></td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\" align='center'>\n";
			$this->salida .= "			<td>I:E</td>\n";
			$this->salida .= "			<td>PEEP</td>\n";
			$this->salida .= "			<td>PIP</td>\n";
			$this->salida .= "			<td>PAW</td>\n";
			$this->salida .= "			<td>To. VIA A</td>\n";
			$this->salida .= "			<td>CO2</td>\n";
			$this->salida .= "			<td>SAT02</td>\n";
			$this->salida .= "			<td>PP</td>\n";
			$this->salida .= "			<td>PM</td>\n";
			$this->salida .= "			<td>ETCO2</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr ".$this->Lista(0)."' align='center'>\n";
			$this->salida .= "			<td><input type='text' class='input-text' name='i_e' value='$_REQUEST[i_e]' size='10' maxlength='10'></td>\n";
			$this->salida .= "			<td><input type='text' class='input-text' name='peep' value='$_REQUEST[peep]' size='5' maxlength='5'></td>\n";
			$this->salida .= "			<td><input type='text' class='input-text' name='pip' value='$_REQUEST[pip]' size='5' maxlength='5'></td>\n";
			$this->salida .= "			<td><input type='text' class='input-text' name='paw' value='$_REQUEST[paw]' size='6' maxlength='6'></td>\n";
			$this->salida .= "			<td><input type='text' class='input-text' name='t_via_a' value='$_REQUEST[t_via_a]' size='6' maxlength='6'></td>\n";
			$this->salida .= "			<td><input type='text' class='input-text' name='co2' value='$_REQUEST[co2]' size='5' maxlength='5'></td>\n";
			$this->salida .= "			<td><input type='text' class='input-text' name='sat02' value='$_REQUEST[sat02]' size='5' maxlength='5'></td>\n";
			$this->salida .= "			<td><input type='text' class='input-text' name='pp' value='$_REQUEST[pp]' size='6' maxlength='7'></td>\n";
			$this->salida .= "			<td><input type='text' class='input-text' name='pm' value='$_REQUEST[pm]' size='6' maxlength='7'></td>\n";
			$this->salida .= "			<td><input type='text' class='input-text' name='etco2' value='$_REQUEST[etco2]' size='6' maxlength='7'></td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n\n";
			$this->salida .= "	<input type='hidden' name='ingreso' value='".$datos_estacion['ingreso']."'>\n";
			$this->salida .= "<div class='normal_10' align='center'><br><input type='submit' class='input-submit' name='Save' value='GUARDAR'>";
			$this->salida .= "</form>\n";
			//$href = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallControlesPacientes',array("control_id"=>$datos_estacion['control_id'],"estacion"=>$estacion,"control_descripcion"=>$datos_estacion['control_descripcion']));
			$href = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallListRevisionPorSistemas',array("control_id"=>24,"estacion"=>$estacion,"control_descripcion"=>'CONTROLES ASISTENCIA VENTILATORIA'));
			$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver Listado</a><br>";

			if (!isset($_REQUEST['cantidad']))
				$this->ShowAsistenciaVentilatoria($estacion,$datos_estacion,1);
			else
				$this->ShowAsistenciaVentilatoria($estacion,$datos_estacion,0);

			$this->salida .= themeCerrarTabla();
			return true;
		}

		/*
		*		SetOptionsAsistenciaVentilatoriaModos
		*
		*		@Author Arley Velasquez
		*		@access Private
		*/
		function SetOptionsAsistenciaVentilatoriaModos($modo,$valor)
		{
			for($i=0; $i<sizeof($modo); $i++) {
				if ($modo[$i]['modo_id']==$valor)
					$this->salida .= "<option value='".$modo[$i]['modo_id']."' selected>".$modo[$i]['descripcion']."</option>\n";
				else
					$this->salida .= "<option value='".$modo[$i]['modo_id']."'>".$modo[$i]['descripcion']."</option>\n";
			}
			return true;
		}


		/*
		*		ShowAsistenciaVentilatoria
		*
		*		Muestra los registros de asistencia ventilatoria del paciente x
		*
		*		@Author Arley Velasquez
		*		@access Private
		*/
		function ShowAsistenciaVentilatoria($estacion,$datos_estacion,$contador)
		{
			$vectorAsistencia = $this->GetAsistenciaVentilatoria($datos_estacion['ingreso']);
			if(!$vectorAsistencia){
				return false;
			}
			elseif($vectorAsistencia != "ShowMensaje")
			{
				if (empty($contador))
					$contador=sizeof($vectorAsistencia);
				$this->salida .= "<br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
				$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "			<td>FECHA</td>\n";
				$this->salida .= "			<td>HORA</td>\n";
				$this->salida .= "			<td>MODO</td>\n";
				$this->salida .= "			<td>FIO2</td>\n";
				$this->salida .= "			<td>F. RESP</td>\n";
				$this->salida .= "			<td>F. VENT</td>\n";
				$this->salida .= "			<td>ESPONT</td>\n";
				$this->salida .= "			<td>VOL/MIN</td>\n";
				$this->salida .= "			<td>SENS</td>\n";
				$this->salida .= "			<td>P. INSP</td>\n";
				$this->salida .= "			<td>TI</td>\n";
				$this->salida .= "			<td>I:E</td>\n";
				$this->salida .= "			<td>PEEP</td>\n";
				$this->salida .= "			<td>PIP</td>\n";
				$this->salida .= "			<td>PAW</td>\n";
				$this->salida .= "			<td>To. VIA A</td>\n";
				$this->salida .= "			<td>CO2</td>\n";
				$this->salida .= "			<td>SAT02</td>\n";
				$this->salida .= "			<td>PP</td>\n";
				$this->salida .= "			<td>PM</td>\n";
				$this->salida .= "			<td>ETCO2</td>\n";
				$this->salida .= "		</tr>\n";

				$cont=1;
				//while ($cont<$contador && $data= $resultado->FetchNextObject($toUpper=false))
				while ($cont <= sizeof($vectorAsistencia) && $cont <= $contador)
				{
					list($date,$time) = explode(" ",$vectorAsistencia[$cont-1][fecha]);
					$this->salida .= "		<tr ".$this->Lista($cont)."' align='center'>\n";
					if($date == date("Y-m-d")) {
						$fecha = "HOY";
					}
					elseif($date == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y")))){
						$fecha = "AYER ";
					}
					else {
						$fecha = $date;
					}
					//---- validar que si los datos esten vacios ponga "--";
					if($vectorAsistencia[$cont-1][fr_respiratoria] != 0) $fr = number_format($vectorAsistencia[$cont-1][fr_respiratoria],0,',','.'); else $fr = "--";
					if($vectorAsistencia[$cont-1][fr_ventilatoria] != 0) $fv = number_format($vectorAsistencia[$cont-1][fr_ventilatoria],1,',','.'); else $fv = "--";
					if(!empty($vectorAsistencia[$cont-1][descripcion_f]))  $fi02 = $vectorAsistencia[$cont-1][descripcion_f]; else $fi02 = "--";
					if($vectorAsistencia[$cont-1][expontanea] != 0) $ex = number_format($vectorAsistencia[$cont-1][expontanea],1,',','.'); else $ex = "--";
					if($vectorAsistencia[$cont-1][volumen] != 0) $vol = number_format($vectorAsistencia[$cont-1][volumen],1,',','.'); else $vol = "--";
					if($vectorAsistencia[$cont-1][sens] != 0) $sens = number_format($vectorAsistencia[$cont-1][sens],0,',','.'); else $sens = "--";
					if($vectorAsistencia[$cont-1][p_insp] != 0) $p_insp = number_format($vectorAsistencia[$cont-1][p_insp],1,',','.'); else $p_insp = "--";
					if($vectorAsistencia[$cont-1][ti] != 0) $ti = number_format($vectorAsistencia[$cont-1][ti],1,',','.'); else $ti = "--";
					if(!empty($vectorAsistencia[$cont-1][i_e])) $ie = $vectorAsistencia[$cont-1][i_e]; else $ie = "--";
					if($vectorAsistencia[$cont-1][peep] != 0) $peep = number_format($vectorAsistencia[$cont-1][peep],0,',','.'); else $peep = "--";
					if($vectorAsistencia[$cont-1][pip] != 0) $pip = number_format($vectorAsistencia[$cont-1][pip],0,',','.'); else $pip = "--";
					if($vectorAsistencia[$cont-1][paw] != 0) $paw = number_format($vectorAsistencia[$cont-1][paw],1,',','.'); else $paw = "--";
					if($vectorAsistencia[$cont-1][t_via_a] != 0) $t_via_a = number_format($vectorAsistencia[$cont-1][t_via_a],1,',','.'); else $t_via_a = "--";
					if($vectorAsistencia[$cont-1][co2] != 0) $co2 = number_format($vectorAsistencia[$cont-1][co2],0,',','.'); else $co2 = "--";
					if($vectorAsistencia[$cont-1][sat02] != 0) $sat02 = number_format($vectorAsistencia[$cont-1][sat02],0,',','.'); else $sat02 = "--";
					if($vectorAsistencia[$cont-1][pp] != 0) $pp = number_format($vectorAsistencia[$cont-1][pp],0,',','.'); else $pp = "--";
					if($vectorAsistencia[$cont-1][pm] != 0) $pm = number_format($vectorAsistencia[$cont-1][pm],0,',','.'); else $pm = "--";
					if($vectorAsistencia[$cont-1][etco2] != 0) $etco2 = number_format($vectorAsistencia[$cont-1][etco2],0,',','.'); else $etco2 = "--";

					$this->salida .= "			<td>".$fecha."</td>\n";
					$this->salida .= "			<td>".$time."</td>\n";
					$this->salida .= "			<td>".$vectorAsistencia[$cont-1][descripcion]."</td>\n";
					$this->salida .= "			<td>".$fi02."</td>\n";
					$this->salida .= "			<td>".$fr."</td>\n";
					$this->salida .= "			<td>".$fv."</td>\n";
					$this->salida .= "			<td>".$ex."</td>\n";
					$this->salida .= "			<td>".$vol."</td>\n";
					$this->salida .= "			<td>".$sens."</td>\n";
					$this->salida .= "			<td>".$p_insp."</td>\n";
					$this->salida .= "			<td>".$ti."</td>\n";
					$this->salida .= "			<td>".$ie."</td>\n";
					$this->salida .= "			<td>".$peep."</td>\n";
					$this->salida .= "			<td>".$pip."</td>\n";
					$this->salida .= "			<td>".$paw."</td>\n";
					$this->salida .= "			<td>".$t_via_a."</td>\n";
					$this->salida .= "			<td>".$co2."</td>\n";
					$this->salida .= "			<td>".$sat02."</td>\n";
					$this->salida .= "			<td>".$pp."</td>\n";
					$this->salida .= "			<td>".$pm."</td>\n";
					$this->salida .= "			<td>".$etco2."</td>\n";
					$this->salida .= "		</tr>\n";
					$cont++;
				}
				$this->salida .= "		</tr>\n";
				$this->salida .= "	</table>\n\n";
				$datos_estacion['estacion']=stripslashes($datos_estacion['estacion']);
				if($contador < sizeof($vectorAsistencia)) {
					$href = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmAsistenciaVentilatoria',array("estacion"=>$estacion,"datos_estacion"=>$datos_estacion,"cantidad"=>1));
					$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Ver M&aacute;s</a><br>";
				}
				return true;
			}
		}



		/**
		*  FrmIngresarDatosLiquidos
		*
		*		Formulario para capturar los datos de los liquidos de un paciente
		*
		*		@Author Rosa Maria Angel
		*		@access Private
		*		@param array parametros de la funcion que llama a esta funcion
		*		@param array nombre de la funcion que llama a esta funcion
		*		@param array datos del paciente
		*		@param array datos de la estacion
		*		@param integer cantidad administrada
		*		@param integer cantidad eliminada
		*		@param array datos de deposicion
		*		@param array con las horas
		*		@param array con los minutos
		*		@return boolean
		*/
		function FrmIngresarDatosLiquidos($referer_parameters,$referer_name,$paciente,$estacion,$cantAdmin,$cantElim,$selectElim,$selectHora,$selectMinutos)
		{
			$fecha = $paciente['Hora'];

			$hora_inicio_turno = ModuloGetVar('app','EstacionEnfermeria','HoraInicioTurnoControles');
			$rango_turno = ModuloGetVar('app','EstacionEnfermeria','RangoVerTurnosControles');
			$this->salida .= ThemeAbrirTabla("CONTROL DE LIQUIDOS");
			$action = ModuloGetURL('app','EstacionE_ControlPacientes','user','InsertarDatosLiquidos',array('datos_estacion'=>$paciente,"estacion"=>$estacion,"referer_parameters"=>$referer_parameters,"referer_name"=>$referer_name));
			$this->salida .= "<form name='InsertarLiquidosPacientes' method=\"POST\" action=\"$action\"><br>\n";
			if($tipoLiquidos = $this->GetTipoLiquidosAdministrados())
			{
				$this->salida .= " <table align=\"center\" width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_title\">\n";
				$this->salida .= "	<tr class=\"modulo_table_title\">\n";
				$this->salida .= "		<td>HABITACION</td>\n";
				$this->salida .= "		<td>CAMA</td>\n";
				$this->salida .= "		<td>PACIENTE</td>\n";
				$this->salida .= "		<td>FECHA CONTROL</td>\n";
				$this->salida .= "	</tr>\n";
				$this->salida .= "	<tr class='modulo_list_oscuro' ".$this->Lista($i)." align=\"center\">\n";
				$this->salida .= "		<td>".$paciente['pieza']."</td>\n";
				$this->salida .= "		<td>".$paciente['cama']."</td>\n";
				$this->salida .= "		<td>".$paciente['NombrePaciente']."</td>\n";

				if(date("H:i:s") >= $hora_inicio_turno)
				{
					list($fecha_control,$hora_control)=explode(" ",date("Y-m-d H:i:s"));
					list($h,$m,$s)=explode(":",$hora_control);
				}
				else
				{//si la hora actual es menor que la de inicio turno, se debe poner la fecha anterior
					list($fecha_control,$hora_control)=explode(" ",date("Y-m-d H:i:s",mktime(date("h"),date("i"),date("s"),date("m"),(date("d")-1),date("Y"))));
					list($h,$m,$s)=explode(":",$hora_control);
				}
				$this->salida .= "		<td>\n";
				$i=0;
				$this->salida .= "			<select name='selectHora' class='select'>\n";
				for($j=0; $j<$rango_turno; $j++)
				{
					list($anno, $mes, $dia)=explode("-",$fecha_control);
					if ($i==23)
					{
						list($h,$m,$s)=explode(":",$hora_inicio_turno);
						$i=date("H",mktime($h+$j,$m,$s,$mes,$dia,$anno));
						$fecha2=date("Y-m-d H:i:s",mktime(24,0,0,$mes,$dia,$anno));
						$fecha_control=date("Y-m-d",mktime(24,0,0,$mes,$dia,$anno));
					}
					else
					{
						list($h,$m,$s)=explode(":",$hora_inicio_turno);
						$i=date("H",mktime($h+$j,$m,$s,$mes,$dia,$anno));
						$fecha2=date("Y-m-d H:i:s",mktime($i,0,0,$mes,$dia,$anno));
						$fecha_control=date("Y-m-d",mktime($i,0,0,$mes,$dia,$anno));
					}
					if(empty($selectHora)){
						if($i == date("H")){ $selected = "selected='true'";} else { $selected = "";}
					}
					else
					{//viene de insertar pero no lo hizo, asi que pongo por defecto los valores enviados
						list($A,$B) = explode(" ",$selectHora);
						if($i == $B){ $selected = "selected='true'";} else { $selected = "";}
					}
					#################################################
					list($yy,$mm,$dd)=explode(" ",$fecha_control);//(date("m"),(date("d")),date("Y")));
					if($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")),date("Y"))))){
						$show = "Hoy a las";
					}
					elseif($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")+1),date("Y"))))){
						$show = "Ma?ana a las";
					}
					elseif($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")-1),date("Y"))))){
						$show = "Ayer a las";
					}
					else{
						$show = $fecha_control;
					}
					###########################
					$this->salida .= "				<option value='".$fecha_control." ".$i."' $selected>".$show." ".$i."</option>\n";
				}//fin for
				$this->salida .= "			</select>\n";
				$this->salida .= "			:&nbsp;<select name='selectMinutos' class='select'>\n";

				for($j=0; $j<=59; $j++)
				{
					if(empty($selectMinutos)){
						if($j == date("i")){ $selected = "selected='true'";} else { $selected = "";}
					}
					else
					{//viene de insertar pero no lo hizo, asi que pongo por defecto los valores enviados
						list($A,$B) = explode(" ",$selectMinutos);
						if($j == $A){ $selected = "selected='true'";} else { $selected = "";}
					}

					if ($j<10){
						$this->salida .= "				<option value='0$j:00' $selected>0$j</option>\n";
					}
					else{
						$this->salida .= "				<option value='$j:00' $selected>$j</option>\n";
					}
				}
				$this->salida .= "			</select>\n";

				$this->salida .= "		</td>\n";
				$this->salida .= "	</tr>\n";
				$this->salida .= "</table><br>\n";
				$this->salida .= " <table align=\"center\" width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list\">\n";
				$this->salida .= $this->SetStyle("MensajeError",2);
				$this->salida .= "	<tr class=\"modulo_table_list_title\"><td colspan='2'>ADMINISTRADOS</td>\n";
				$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "		<td width='45%'>LIQUIDO</td>\n";
				$this->salida .= "		<td align=\"left\"  width='45%'>CANTIDAD SUMINISTRADA               --               LIQUIDO</td>\n";
				$this->salida .= "	</tr>\n";

				for($i=0; $i<sizeof($tipoLiquidos); $i++)
				{
					if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
					$this->salida .= "	<tr class=\"$estilo\">\n";
					$this->salida .= "		<td width='50%'>".$tipoLiquidos[$i][descripcion]."</td>\n";
					$this->salida .= "		<td  width='50%' align='left'><input type='text' name='cantAdmin[".$tipoLiquidos[$i][tipo_liquido_administrado_id]."]' value='".$cantAdmin[$tipoLiquidos[$i][tipo_liquido_administrado_id]]."' class='input-text' size='9'><label class='label_mark'>&nbsp;CC</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class='label_mark'>DETALLE</label>";
					$this->salida .= "<input type='text' name='liquidoA".$tipoLiquidos[$i][tipo_liquido_administrado_id]."'  class='input-text'  maxlength='40' size='20'></td>\n";
					$this->salida .= "	</tr>\n";
				}
				$this->salida .= "</table><br>\n";
			}

			if($tipoLiquidosE = $this->GetTipoLiquidosEliminados())
			{
				$this->salida .= " <table align=\"center\" width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list\">\n";
				$this->salida .= "	<tr class=\"modulo_table_list_title\"><td colspan='2'>ELIMINADOS</td>\n";
				$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "		<td width='50%'>LIQUIDO</td>\n";
				$this->salida .= "		<td width='50%'>CANTIDAD</td>\n";
				$this->salida .= "	</tr>\n";
				$g=0;
				for($i=0; $i<sizeof($tipoLiquidosE); $i++)
				{
					if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
					$this->salida .= "	<tr class=\"$estilo\">\n";
					$this->salida .= "		<td width='50%'>".$tipoLiquidosE[$i][descripcion]."</td>\n";
					if($tipoLiquidosE[$i][deposicion])
					{
    				if($selectElim[$tipoLiquidosE[$i][tipo_liquido_eliminado_id]] == "NO"){$selectNo = "selected='true'"; } else{$selectNo = "selected=''"; }
						if($selectElim[$tipoLiquidosE[$i][tipo_liquido_eliminado_id]] == "SI"){$selectSi = "selected='true'"; } else{$selectSi = "selected=''"; }

						$this->salida .= "		<td width='50%' align='left'>\n";
						$this->salida .= "			<select name='selectElim[".$tipoLiquidosE[$i][tipo_liquido_eliminado_id]."]' class='select'>\n";
						$this->salida .= "				<option value='NO' $selectNo>NO</option><option value='SI' $selectSi>SI</option>\n";
						$this->salida .= "			</select>\n";
						$this->salida .= "		</td>\n";
					}
					else{

						if($g==0)
						{
							$salida = "			<select name='eliminacionu' class='select'>\n";
							$salida .= "				<option value='1'>ESPONTANEA</option><option value='2' >SONDA</option>\n";
							$salida .= "			</select>\n";
							$g=1;
						}
						else
						{
							$salida='';
						}
						$this->salida .= "		<td width='50%' align='left'><input type='text' name='cantElim[".$tipoLiquidosE[$i][tipo_liquido_eliminado_id]."]' value='".$cantElim[$tipoLiquidosE[$i][tipo_liquido_eliminado_id]]."' class='input-text' size='9'><label class='label_mark'>&nbsp;CC</label>$salida</td>\n";
					}
					$this->salida .= "	</tr>\n";
				}
				$this->salida .= "</table>\n";
			}
			if($tipoLiquidos ||$tipoLiquidosE){
				$this->salida .= "<br><div class='normal_10' align='center'><input type='submit' name='ACEPTAR' value='INGRESAR DATOS' class='input-submit'>&nbsp;<input type='reset' name='reset' value='REESTABLECER' class='input-submit'>\n";
			}
			$this->salida .= "</form>\n";
			$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$estacion));
			//$hrefLQ =ModuloGetURL('app','EstacionEnfermeria','user','CallFrmFrecuenciaControlesP',array("control"=>6,"descripcion"=>"CONTROL LIQUIDOS","estacion"=>$estacion,"href_action_hora"=>"CallFrmIngresarDatosLiquidos","href_action_control"=>array(0=>"CallFrmControlLiquidos",1=>"CallFrmControlLiquidosXDias")));
			$hrefLQ = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallControlesPacientes',array("control_id"=>6,"estacion"=>$estacion,"control_descripcion"=>"CONTROL LIQUIDOS")) ;

			$this->salida .= "<div class='normal_10' align='center'><br>\n";
			$this->salida .= "	<a href='".$hrefLQ."'>Volver a Control de l&iacute;quidos</a>&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;\n";
			$this->salida .= "	<a href='".$href."'>Volver al Menu Estaci&oacute;n</a>\n";
			$this->salida .= themeCerrarTabla();
			return true;
		}//fin FrmIngresarDatosLiquidos


/*



/*
		*		FrmLiquidosAdministrados
		*
		*		Muestra el detalle de los liquidos administrados al paciente X en la fecha Y
		*
		*		@Author Rosa Maria Angel
		*		@access private
		*		@param array datos del paciente
		*		@param array datos de la estacion
		*		@param array otros datos necesarios
		*		@return bool
		*/
		function FrmLiquidosAdministrados($paciente,$estacion,$datosAlternos)
		{
			$hora_inicio_turno = ModuloGetVar('app','EstacionEnfermeria','HoraInicioTurnoControles');
			$rango_turno = ModuloGetVar('app','EstacionEnfermeria','RangoVerTurnosControles');
			list($hh,$mm, $ss) = explode(" ",$hora_inicio_turno);

			if($datosAlternos)
			{//viene desde la forma de RESUMEN ACUMULADOS y se muestran los liquidos del turno de una fecha espec&iacute;fica
				list($yy,$mm,$dd) = explode("-",$datosAlternos[fecha]);
				//OJO!! si el turno empieza a las 2004-01-30 08:00:00 => nextDay = 2004-01-31 07:59:59'
				$NextDay = date("Y-m-d H:i:s", mktime(date(($hh)), date(($mm)-1), date(($ss)-1), date($mm),(date($dd)+1),date($yy)));
				$vLiquido = $this->GetLiquidosAdministrados($paciente[ingreso],date("$datosAlternos[fecha] $hora_inicio_turno"),$NextDay);
			}
			else
			{//se muestran los liquidos del turno actual
				//OJO!! si el turno empieza a las 2004-01-30 08:00:00 => nextDay = 2004-01-31 07:59:59'
				$NextDay = date("Y-m-d H:i:s", mktime(date(($hh)), date(($mm)), date(($ss)-1), date("m"),(date("d")+1),date("Y")));
				$vLiquido = $this->GetLiquidosAdministrados($paciente[ingreso],date("Y-m-d $hora_inicio_turno"),$NextDay);
			}

			$this->salida .= ThemeAbrirTabla("LIQUIDOS ADMINISTRADOS")."<br>";
			$this->salida .= "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_title\">\n";
			$this->salida .= "		<tr class=\"modulo_table_title\">\n";
			$this->salida .= "			<td>HABITACION</td>\n";
			$this->salida .= "			<td>CAMA</td>\n";
			$this->salida .= "			<td>PACIENTE</td>\n";
			$this->salida .= "			<td>ID</td>\n";
			$this->salida .= "			<td>CUENTA</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
			$this->salida .= "			<td>".$paciente[pieza]."</td>\n";
			$this->salida .= "			<td>".$paciente[cama]."</td>\n";
			$this->salida .= "			<td>".$paciente[primer_nombre]." ".$paciente[segundo_nombre]." ".$paciente[primer_apellido]." ".$paciente[segundo_apellido]."</td>\n";
			$this->salida .= "			<td>".$paciente[tipo_id_paciente]." ".$paciente[paciente_id]."</td>\n";
			$this->salida .= "			<td>".$paciente[numerodecuenta]."</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table><br>\n";

			$this->salida .= " <table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list\">\n";
			$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "		<td>FECHA</td>\n";
			$this->salida .= "		<td>LIQUIDO</td>\n";
			$this->salida .= "		<td>DETALLE</td>\n";
			$this->salida .= "		<td>CANTIDAD</td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
			foreach($vLiquido as $key => $value)
			{
				$colspan = sizeof($value);
				foreach($value as $A => $B)
				{

					if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
					$this->salida .= "	<tr class=".$estilo.">\n";
					if($colspan == sizeof($value))
					{
						if($B[fechas] == date("Y-m-d")) {
							$fecha = "HOY";
						}
						elseif($B[fechas] == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y")))){
							$fecha = "AYER ";
						}
						elseif($B[fechas] == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")+1), date("Y")))){
							$fecha = "MA?ANA ";
						}
						else {
							$fecha = $B[fechas];
						}

						$this->salida .= "		<td align='center' rowspan=".$colspan." width='20%'>".$fecha." ".date("H:i:s",mktime($key,0,0,date("m"),date("d"),date("Y")))."</td>\n";
					}
					$this->salida .= "		<td>".$B[descripcion]."</td>\n";
					if(!empty($B[detalle]))
					{
						$this->salida .= "		<td><label class='label_mark'>".strtoupper($B[detalle])."</label></td>\n";
					}
					else
					{
						$this->salida .= "		<td></td>\n";
					}	
					$this->salida .= "		<td align='center'>".$B[sumas]."</td>\n";
					$this->salida .= "	</tr>\n";
					$colspan--;
					$TotalAdmin +=$B[sumas] ;
				}
				$i++;
			}
			$this->salida .= "<tr align='center' class='modulo_table_title'><td colspan='3' align='center'>TOTAL LIQUIDOS ADMINISTRADOS</td><td align='center'>".number_format($TotalAdmin,2,',','.')."</td></tr>\n";
			$this->salida .= "<tr align='center' class='modulo_table_title'><td colspan='3' align='center'>TOTAL AGUA END&Oacute;GENA</td><td align='center'>".number_format(($TotalAdmin+(5*$this->GetPesoPaciente($paciente[ingreso]))),2,',','.')."</td></tr>\n";
			$this->salida .= "</table>\n";
			if(!$datosAlternos)
			{ //REVISAR ESTA PARTE DELLLLLLLLLLLLL $datosAlternos[referer_name]...PILAS.
				$href = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmControlLiquidos',array("paciente"=>$paciente,"estacion"=>$estacion));
				$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>REGRESAR</a><br>";
			}
			else
			{
				$href = ModuloGetURL('app','EstacionE_ControlPacientes','user',$datosAlternos[referer_name],$datosAlternos[referer_parameters]);
				$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>REGRESAR</a><br>";
			}
			$this->salida .= themeCerrarTabla();
			return true;
		}//fin FrmLiquidosAdministrados

		/*
		*		ShowDatosUser
		*
		*		Funcion muestra los datos del usuario dado
		*
		*		@Author Rosa Maria Angel
		*		@access Private
		*		@return bool
		*/
		function ShowDatosUser()
		{
			$usuario = $_REQUEST['usuario'];
			$estacion = $_REQUEST['estacion'];

			$DatosUser = $this->GetDatosUsuarioSistema($usuario);
			if(!$DatosUser){
				return false;
			}
			elseif($DatosUser === "ShowMensaje")
			{
				$mensaje = "NO SE ENCONTRARON LOS DATOS DEL USUARIO '$usuario'";
				$titulo = "MENSAJE";
				$accion = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallControlesPacientes',array("control_id"=>15,"estacion"=>$estacion,"control_descripcion"=>"CONTROL DE SIGNOS VITALES"));
				$boton = "VOLVER A SIGNOS VITALES";
				$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
				return true;
			}
			else
			{
				$mensaje = "USUARIO : ".$DatosUser[0][usuario]."<br>NOMBRE : ".$DatosUser[0][nombre];
				$titulo = "DATOS DEL USUARIO";
				//$accion = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallControlesPacientes',array("control_id"=>15,"estacion"=>$estacion,"control_descripcion"=>"CONTROL DE SIGNOS VITALES"));
				$accion = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallListRevisionPorSistemas',array("control_id"=>15,"estacion"=>$estacion,"control_descripcion"=>"CONTROL DE SIGNOS VITALES"));
				$boton = "VOLVER A SIGNOS VITALES";
				$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
				return true;
			}
		}//

/**
		*		FrmIngresarDatosGlucometr&iacute;a
		*
		*		Permite ingresar los datos de la glucometria a un paciente x
		*
		*		@Author Rosa Maria Angel
		*		@access Private
		*/
		function FrmIngresarDatosGlucometria($referer_parameters,$referer_name,$paciente,$estacion)//,$Glucometria,$selectInsulina,$textInsulina,$ViaInsulina
		{
			$ViasInsulina = $this->GetViasInsulina();
			$TiposInsulina = $this->GetTiposInsulina();
			if(!$ViasInsulina || !$TiposInsulina){
				return false;
			}
			elseif($ViasInsulina === "ShowMensaje" || $TiposInsulina === "ShowMensaje" ){
				$mensaje = "NO SE ENCONTRARON LOS TIPOS DE INSULINA O LAS V&Iacute;AS DE ADMINISTRACION";
				$titulo = "MENSAJE";
				$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$estacion));
				$boton = "VOLVER AL MEN&Uacute; ESTACI&Oacute;N";
				$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
				return true;
			}
			else
			{
				$fecha=$paciente['Hora'];
				$this->salida .= ThemeAbrirTabla("CONTROL DE PACIENTE DIABETICO");
				$action = ModuloGetURL('app','EstacionE_ControlPacientes','user','InsertarDatosGlucometria',array('fecha'=>$fecha,'datos_estacion'=>$paciente,"estacion"=>$estacion,"referer_parameters"=>$referer_parameters,"referer_name"=>$referer_name));
				$this->salida .= "<form name='IniciarGlucometriasPacientes' method=\"POST\" action=\"$action\"><br>\n";
				$this->salida .= "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_title\">\n";
				$this->salida .= "		<tr class=\"modulo_table_title\">\n";
				$this->salida .= "			<td>PACIENTE</td>\n";
				$this->salida .= "			<td>HABITACION</td>\n";
				$this->salida .= "			<td>CAMA</td>\n";
				$this->salida .= "			<td>FECHA CONTROL</td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
				$this->salida .= "			<td>".$paciente['NombrePaciente']."</td>\n";
				if(!empty($paciente[pieza]))
                    {
		               $this->salida .= "			<td>".$paciente[pieza]."</td>\n";	                    
                    }
                    else
                    {
		               $this->salida .= "			<td>No Ingresado</td>\n";	                    
                    }
				
                    if(!empty($paciente[cama]))
                    {
					$this->salida .= "			<td>".$paciente[cama]."</td>\n";
                    }
                    else
                    {
		               $this->salida .= "			<td>No Ingresado</td>\n";	                    
                    }

				$this->salida .= "			<td>".$fecha."</td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "	</table><br>\n";
				$this->salida .= "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
				$this->salida .= $this->SetStyle("MensajeError",1);
				$this->salida .= "	<tr>\n";
				$this->salida .= "		<td>\n";
				$this->salida .= "			<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list\">\n";
				$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "					<td rowspan='2' width='25%' class=\"".$this->SetStyle("Glucometria")."\">GLUCOMETRIA</td>\n";
				$this->salida .= "					<td colspan='3' class=\"".$this->SetStyle("Insulina")."\">INSULINA</td>\n";
				$this->salida .= "				</tr>\n";
				$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "					<td width='25%' class=\"".$this->SetStyle("SelectInsulina")."\">TIPO</td>\n";
				$this->salida .= "					<td width='25%' class=\"".$this->SetStyle("TextInsulina")."\">CANTIDAD</td>\n";
				$this->salida .= "					<td width='25%' class=\"".$this->SetStyle("ViaInsulina")."\">VIA</td>\n";
				$this->salida .= "				</tr>\n";
				$this->salida .= "				<tr class=\"modulo_list_claro\" align='center'>\n";
				$this->salida .= "					<td rowspan='2' width='25%'><input type='text' name='Glucometria' value='$Glucometria' size='6' maxlength='5' class='input-text'></td>\n";
				$this->salida .= "					<td width='25%' align='left'><br>&nbsp;&nbsp;&nbsp;&nbsp;\n";
				if(in_array("cristalina",$_REQUEST['checkInsulina'])){
					$checked = "checked='yes'";
				}
				else{
					$checked = "";
				}
				$this->salida .= "						<input type='checkbox' name='checkInsulina[]' value='cristalina' $checked>&nbsp;&nbsp;CRISTALINA&nbsp;&nbsp;&nbsp;\n";
				$this->salida .= "					</td>\n";
				$this->salida .= "					<td>\n";
				$this->salida .= "						<input type='text' name='textInsulina[cristalina]' value='".$_REQUEST['textInsulina']['cristalina']."' size='6' maxlength='5' class='input-text'><label class='label_mark'>&nbsp;Unidades</label>\n";
				$this->salida .= "					</td>\n";
				$this->salida .= "					<td>\n";
				$this->salida .= "						<select name='ViaInsulina[cristalina]' class='select'>\n";
				$this->salida .= "							<option value='-1' $selected>--</option>\n";
				foreach($ViasInsulina as $clave => $val)
				{
					if($val['tipo_via_insulina_id'] == $_REQUEST['ViaInsulina']['cristalina']) { $selected="selected='true'";} else {$selected = ""; }
					$this->salida .= "						<option value='".$val['tipo_via_insulina_id']."' $selected>".$val['descripcion']."</option>\n";
				}
				$this->salida .= "						</select>\n";
				$this->salida .= "					</td>\n";
				$this->salida .= "				</tr>\n";
				$this->salida .= "				<tr class=\"modulo_list_claro\" align='center'>\n";
				$this->salida .= "					<td width='25%' align='left'><br>&nbsp;&nbsp;&nbsp;&nbsp;\n";
				if(in_array("nph",$_REQUEST['checkInsulina'])){
					$checked = "checked='yes'";
				}
				else{
					$checked = "";
				}
				$this->salida .= "						<input type='checkbox' name='checkInsulina[]' value='nph' $checked>&nbsp;&nbsp;NPH&nbsp;&nbsp;&nbsp;\n";
				$this->salida .= "					</td>\n";
				$this->salida .= "					<td>\n";
				$this->salida .= "						<input type='text' name='textInsulina[nph]' value='".$_REQUEST['textInsulina']['nph']."' size='6' maxlength='5' class='input-text'><label class='label_mark'>&nbsp;Unidades</label>\n";
				$this->salida .= "					</td>\n";
				$this->salida .= "					<td>\n";
				$this->salida .= "						<select name='ViaInsulina[nph]' class='select'>\n";
				$this->salida .= "							<option value='-1' $selected>--</option>\n";
				foreach($ViasInsulina as $clave => $val)
				{
					if($val['tipo_via_insulina_id'] == $_REQUEST['ViaInsulina']['nph']) { $selected="selected='true'";} else {$selected = ""; }
					$this->salida .= "						<option value='".$val['tipo_via_insulina_id']."' $selected>".$val['descripcion']."</option>\n";
				}
				$this->salida .= "						</select>\n";
				$this->salida .= "					</td>\n";
				$this->salida .= "				</tr>\n";
				$this->salida .= "			</table><br>\n";
				$this->salida .= "			<br><br><div class='normal_10' align='center'><input type='submit' name='Submit' value='INGRESAR DATOS' class='input-submit'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='reset' name='Reset' value='REESTABLECER' class='input-submit'>\n";

				$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$estacion));
				$hrefDB =ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmFrecuenciaControlesP',array("control"=>8,"ingreso"=>$_SESSION['ESTACION_CONTROL']['INGRESO'],"descripcion"=>"CONTROL GLUCOMETR&Iacute;A","estacion"=>$estacion,"href_action_hora"=>"CallFrmIngresarDatosGlucometria","href_action_control"=>array(0=>"CallFrmResumenGlucometria")));
				$this->salida .= "			<div class='normal_10' align='center'><br>\n";
				$this->salida .= "				<a href='".$hrefDB."'>Volver a Control Glucometr&iacute;a</a>&nbsp;&nbsp;-&nbsp;&nbsp;\n";
				$this->salida .= "				<a href='".$href."'>Volver al Menu Estaci&oacute;n</a>\n";
				$this->salida .= "		</td>\n";
				$this->salida .= "	</tr>\n";
				$this->salida .= "</table>\n";
				$this->salida .= "</form>\n";
				$this->salida .= themeCerrarTabla();
			}
				return true;
		}//fin FrmIngresarDatosGlucometr&iacute;a


		/*
		*		FrmProgramarTurnos
		*
		*		@Author Arley Velasquez
		*		@access Private
		*/
		function FrmProgramarTurnos($rango,$estacion,$datos_estacion,$turnos_prgmar,$turno_fecha_rango,$href_action_hora,$href_action_control,$ingreso_id)
		{

			if (!ModuloIncludeLib("app","EstacionEnfermeria","funciones")){
				$this->error = "Error al cargar la libreria de Modulos.";
				$this->mensajeDeError = "funciones";
				return false;
			}

			if(empty($_SESSION['ESTACION']['DIRECCION']['CONTROL']))
			{
				$_SESSION['ESTACION']['DIRECCION']['CONTROL']=$href_action_control;
			}
			$controles=array();
			$turnos="";
			unset($_SESSION['ESTACION']['NOMBRE_CONTROL']);
			//en esta variable de session tenemos $_SESSION['ESTACION']['NOMBRE_CONTROL']
			//tenemos el nombre del control ya que cuando insertamos en hc_agenda_controles
			//tambien insertamos en hc_control_apoyosd_pendientes
			//para que quede registrado como una actividad.
			$_SESSION['ESTACION']['NOMBRE_CONTROL']=$datos_estacion['control_descripcion'];
			$this->salida .= ThemeAbrirTabla($datos_estacion['control_descripcion']." - [ ".$estacion['descripcion5']." ]");
			$href = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallInsertarAgendaTurnos',array("estacion"=>$estacion,"datos_estacion"=>$datos_estacion,"turnos_prgmar"=>$turnos_prgmar,"turno_fecha_rango"=>$turno_fecha_rango,"href_action_hora"=>$href_action_hora,"href_action_control"=>$href_action_control,"ingreso"=>$ingreso_id));
			$this->salida .= "<form name=\"formaCreaTurnos\" action=\"$href\" method=\"post\"><BR>";
			$this->salida .= "<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\"class=\"modulo_table_title\" >\n";
			$this->salida .= "	<tr class=\"modulo_table_title\">\n";
			$this->salida .= "		<td>HABITACION</td>\n";
			$this->salida .= "		<td>CAMA</td>\n";
			$this->salida .= "		<td>PACIENTE</td>\n";
			$this->salida .= "		<td>FECHA PROGRAMACI&Oacute;N</td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "	<tr class='modulo_list_oscuro' ".$this->Lista($i)." align='center'>\n";
			
			if(empty($datos_estacion['pieza']))
			{
				$this->salida .= "		<td>No Ingresado</td>\n";
				$this->salida .= "		<td>No Ingresado</td>\n";
			
			}
			else
			{
				$this->salida .= "		<td>".$datos_estacion['pieza']."</td>\n";
				$this->salida .= "		<td>".$datos_estacion['cama']."</td>\n";
			}	
			$this->salida .= "		<td>".$datos_estacion['NombrePaciente']."</td>\n";
			list($Fechita,$Horita) = explode(" ",$turno_fecha_rango[0]);
			$this->salida .= "		<td>".$Fechita."</td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "</table>\n";

			$horas=$this->GetTurnosEstacion($estacion['estacion_id']);
			if ($horas===false){
				return false;
			}

			$hora_inicio_turno=ModuloGetVar('app','EstacionEnfermeria','HoraInicioTurnoControles');
			$rango_turno=ModuloGetVar('app','EstacionEnfermeria','RangoVerTurnosControles');
			$href = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmProgramarTurnos',array("ingreso"=>$datos_estacion['ingreso'],"rango"=>$rango,"estacion"=>$estacion,"datos_estacion"=>$datos_estacion,"turnos_prgmar"=>$turnos_prgmar,"turno_fecha_rango"=>$turno_fecha_rango));

			$turnos=CrearTurnos($href,date("Y-m-d"),true,true,$turnos_prgmar[0],$rango,$rango_turno,$horas,true,$turnos_prgmar,$hora_inicio_turno,$datos_estacion['ingreso'],$datos_estacion['control_id']);

			$this->salida .= "<br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list\">\n";
			$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "		<td width='60%'>OBSERVACION</td>\n";
			$this->salida .= "		<td width='40%'>TURNOS</td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "	<tr ".$this->Lista(1).">\n";
			$this->salida .= "		<td valign='top'>\n";
			$this->FrmControles(array("ingreso"=>$datos_estacion['ingreso'],"control_id"=>$datos_estacion['control_id']));
			$this->salida .= "		</td>\n";
			$this->salida .= "		<td>$turnos</td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "</table><br>\n";
			$this->salida .= "<input type='hidden' name='estacion_id' value='".$estacion['estacion_id']."'>";
			$this->salida .= "<input type='hidden' name='ingreso_id' value='".$datos_estacion['ingreso']."'>";
			$this->salida .= "<input type='hidden' name='control_id' value='".$datos_estacion['control_id']."'>";
			$this->salida .= "<input type='hidden' name='fecha' value='".date("Y-m-d")."'>";
			$this->salida .= "<div class='normal_10' align='center'><br><br><input type='submit' class='input-submit' name='SaveTurnos' value='GUARDAR TURNOS'>";
			$this->salida .= "</form>\n";

			$href = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmFrecuenciaControlesP',array("control"=>$datos_estacion['control_id'],"ingreso"=>$_SESSION['ESTACION_CONTROL']['INGRESO'],"descripcion"=>$datos_estacion['control_descripcion'],"estacion"=>$estacion,"href_action_hora"=>$href_action_hora,"href_action_control"=>$href_action_control));
			$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver a Programaci&oacute;n Control</a><br>";

			$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$estacion));
			$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";

			$this->salida .= themeCerrarTabla();
			return true;
		}

/*
		*		FrmResumenGlucometria
		*
		*		Muestra los registros que tiene el paciente del control de Glucometria
		*
		*		@Author Rosa Maria Angel
		*		@access Private
		*		@return bool
		*/
		function FrmResumenGlucometria($datos_paciente,$estacion)
		{
			$Resumen = $this->GetResumenGlucometria($datos_paciente[ingreso]);

			if(!$Resumen){
				return false;
			}

			elseif($Resumen === "ShowMensaje")
			{
				$mensaje = "NO SE ENCONTRARON REGISTROS DE CONTROLES PARA PACIENTES DIABETICOS";
				$titulo = "MENSAJE";
				$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$estacion));
				$boton = "VOLVER AL MEN&Uacute; ESTACI&Oacute;N";
				$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
				return true;
			}
			else
			{

				$this->salida .= ThemeAbrirTabla("RESUMEN CONTROL DE GLUCOMETRIA");
				$this->salida .= "<table align=\"center\" width=\"95%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class='normal_10'>\n";
				$this->salida .= "	<tr>\n";
				$this->salida .= "		<td><br>\n";
				$this->salida .= "			<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_title\">\n";
				$this->salida .= "				<tr class=\"modulo_table_title\">\n";
				$this->salida .= "					<td>PACIENTE</td>\n";
				$this->salida .= "					<td>ID</td>\n";
				$this->salida .= "					<td>HABITACION</td>\n";
				$this->salida .= "					<td>CAMA</td>\n";
				$this->salida .= "				</tr>\n";
				$this->salida .= "				<tr align='center' class='modulo_list_oscuro'>\n";
				$this->salida .= "					<td>".$datos_paciente[primer_nombre]." ".$datos_paciente[segundo_nombre]." ".$datos_paciente[primer_apellido]." ".$datos_paciente[segundo_apellido]."</td>\n";
				$this->salida .= "					<td>".$datos_paciente[tipo_id_paciente]." ".$datos_paciente[paciente_id]."</td>\n";
				if(!empty($datos_paciente[pieza]))
                    {
		               $this->salida .= "			<td>".$datos_paciente[pieza]."</td>\n";	                    
                    }
                    else
                    {
		               $this->salida .= "			<td>No Ingresado</td>\n";	                    
                    }
				
                    if(!empty($datos_paciente[cama]))
                    {
		               $this->salida .= "			<td>".$datos_paciente[cama]."</td>\n";	                    
                    }
                    else
                    {
		               $this->salida .= "			<td>No Ingresado</td>\n";	                    
                    }
				$this->salida .= "				</tr>\n";
				$this->salida .= "			</table>\n";
				$this->salida .= "		</td>\n";
				$this->salida .= "	</tr>\n";
				$this->salida .= "	<tr><td>&nbsp;</td></tr>\n";
				$this->salida .= "	<tr>\n";
				$this->salida .= "		<td>\n";
				$this->salida .= "			<table width='100%' border='0'  align='center'>\n";
				$this->salida .= "				<tr class=\"modulo_table_list_title\" align='center'>\n";
				$this->salida .= "					<td rowspan='2'>FECHA</td>\n";
				$this->salida .= "					<td rowspan='2'>GLUCOMETRIA</td>\n";
				$this->salida .= "					<td colspan='2'>INSULINA CRISTALINA</td>\n";
				$this->salida .= "					<td colspan='2'>INSULINA NHP</td>\n";
				$this->salida .= "				</tr>\n";
				$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "					<td width='13%'>CANTIDAD</td>\n";
				$this->salida .= "					<td width='13%' >VIA</td>\n";
				$this->salida .= "					<td width='13%'>CANTIDAD</td>\n";
				$this->salida .= "					<td width='13%'>VIA</td>\n";
				$this->salida .= "				</tr>\n";
				/*$Rangos = $this->GetRangoControl(8);*/


				if (!IncludeLib('datospaciente')){
					$this->error = "Error al cargar la libreria [datospaciente].";
					$this->mensajeDeError = "datospaciente";
					return false;
				}


				$datos_hc=GetDatosPaciente("","",$datos_paciente[ingreso],"","");
				$paciente=array("edad"=>CalcularEdad($datos_hc["fecha_nacimiento"],date("Y-m-d")),"sexo"=>$datos_hc["sexo_id"]);

				$Rangos = $this->GetRangoControl(8,$paciente);
				if ($Rangos === false){
					return false;
				}
				
				foreach($Resumen as $key => $value)
				{
					if(!empty($value[0][glucometria]))			{ $gluco = number_format($value[0][glucometria], 0, ',', '.');} else { $gluco = "--"; }
					if(!empty($value[0][valor_cristalina]))	{ $valCristalina = number_format($value[0][valor_cristalina], 0, ',', '');} else { $valCristalina = "--"; }
					if(!empty($value[0][valor_nph]))				{ $valNPH = number_format($value[0][valor_nph], 0, ',', '');} else { $valNPH = "--"; }
					if(!empty($value[0][via_cristalina]))		{ $via_cristalina = $value[0][viacristalina];} else { $via_cristalina = "--"; }
					if(!empty($value[0][via_nph]))					{ $via_nph = $value[0][vianph];} else { $via_nph = "--"; }

					$this->salida .= "				<tr ".$this->Lista($cont)." align='center'>\n";
					list($date,$time) = explode (" ",$key);
					if($date == date("Y-m-d")) {
						$fecha = "HOY ".$time;
					}
					elseif($date == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y")))){
						$fecha = "AYER ".$time;
					}
					else{
						$fecha = $key;
					}
					$this->salida .= "					<td>".$fecha."</td>\n";//number_format($vc[$j][3], 2, ',', '.')
					if($gluco >= $Rangos[rango_max] || $gluco<= $Rangos[rango_min]){
						$estilo = "alerta";
					}
					else{
						$estilo = "";
					}
					$this->salida .= "					<td class='$estilo' >".$gluco."</td>\n";
					$this->salida .= "					<td>".$valCristalina."</td>\n";
					$this->salida .= "					<td>".$via_cristalina."</td>\n";
					$this->salida .= "					<td>".$valNPH."</td>\n";
					$this->salida .= "					<td>".$via_nph."</td>\n";
					$this->salida .= "				</tr>\n";
					$cont++;
				}
				$this->salida .= "		</td>\n";
				$this->salida .= "	</tr>\n";
				$this->salida .= "</table>\n";
				$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$estacion));
				$hrefCT =ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmFrecuenciaControlesP',array("control"=>8,"ingreso"=>$_SESSION['ESTACION_CONTROL']['INGRESO'],"descripcion"=>"CONTROL GLUCOMETR&Iacute;A","estacion"=>$estacion,"href_action_hora"=>"CallFrmIngresarDatosGlucometria","href_action_control"=>array(0=>"CallFrmResumenGlucometria")));
				$this->salida .= "<div class='normal_10' align='center'>";

				$this->salida .= "	<tr align='center'><td><br>\n";
				$this->salida .= "		<a href='".$hrefCT."'>Volver a Control Glucometr&iacute;a</a>&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;\n";
				$this->salida .= "		<a href='".$href."'>Volver al Menu Estaci&oacute;n</a>\n";
				$this->salida .= "	</td></tr>\n";
				$this->salida .= "</table>\n";
				$this->salida .= themeCerrarTabla();
			}
			return true;
		}//FrmResumenGlucometria

/*
		*		FrmControlNeurologico
		*
		*		Formulario que captura los datos del control neurologico del paciente X a la hora Y
		*
		*		@Author Rosa Maria Angel
		*		@access Private
		*		@return bool
		*/
		function FrmControlNeurologico($referer_parameters,$referer_name,$datos_estacion,$estacion,$TallasPupilaD,$TallasPupilaI,$ReaccionPupilaI,$ReaccionPupilaD,$NivelConciencia,$FuerzaBrazoD,$FuerzaPiernaD,$FuerzaBrazoI,$FuerzaPiernaI,$AperturaOcular,$RespVerbal,$RespMotora)
		{
			if(!$Tallas = $this->GetTallasPupilas($datos_estacion,$estacion)){
				return false;
			}
			if(!$Reaccion = $this->GetReaccionPupilas($datos_estacion,$estacion)){
				return false;
			}
			if(!$NivelesConciencia = $this->GetNivelesConciencia($datos_estacion,$estacion)){
				return false;
			}
			if(!$TiposFuerza = $this->GetTiposFuerza($datos_estacion,$estacion)){
				return false;
			}
			if(!$TipoAperturaOcular = $this->GetTipoAperturaOcular($datos_estacion,$estacion)){
				return false;
			}
			if(!$RespuestaVerbal = $this->GetRespuestaVerbal($datos_estacion,$estacion)){
				return false;
			}
			if(!$RespuestaMotora = $this->GetRespuestaMotora($datos_estacion,$estacion)){
				return false;
			}

			$this->salida .= ThemeAbrirTabla("CONTROL NEUROLOGICO - [ ".$estacion['descripcion5']." ]")."<br>";
			$this->salida .= "<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"normal_10\">\n";
			$this->salida .= "	<tr>\n";
			$this->salida .= "		<td>\n";
			$this->salida .= "			<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_title\">\n";
			$this->salida .= "				<tr class=\"modulo_table_title\">\n";
			$this->salida .= "					<td>HABITACION</td>\n";
			$this->salida .= "					<td>CAMA</td>\n";
			$this->salida .= "					<td>PACIENTE</td>\n";
			$this->salida .= "					<td>FECHA CONTROL</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr ".$this->Lista($i)." align=\"center\">\n";
          	if (!empty($datos_estacion['pieza']))
               {
               	$this->salida .= "					<td>".$datos_estacion['pieza']."</td>\n";               
               }
               else
               {
                    $this->salida .= "					<td>No Ingresado</td>\n";                         
               }

               if (!empty($datos_estacion['cama']))
               {
                    $this->salida .= "					<td>".$datos_estacion['cama']."</td>\n";
               }
               else
               {
                    $this->salida .= "					<td>No Ingresado</td>\n";          
               }
               $this->salida .= "					<td>".$datos_estacion['NombrePaciente']."</td>\n";
               $this->salida .= "					<td>".$datos_estacion['Hora']."</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "			</table>\n";
			$this->salida .= "		</td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "	<tr>\n";
			$this->salida .= "		<td align=\"center\"><br>\n";
			$action = ModuloGetURL('app','EstacionE_ControlPacientes','user','InsertHojaNeurologica',array('datos_estacion'=>$datos_estacion,"estacion"=>$estacion,"referer_parameters"=>$referer_parameters,"referer_name"=>$referer_name));
			$this->salida .= "			<form name='FormaNeurologica' action=\"".$action."\" method='post' onsubmit='return ValidaRadio(this)'><br>\n";
			$this->salida .= "			<table width='100%'  align='center'>\n";
			$this->salida .= "				<tr class=\"modulo_table_title\"><td colspan='5'>HOJA NEUROLOGICA</td></tr>\n";
			$this->salida .= $this->SetStyle("MensajeError",5);
			$this->salida .= "				<tr class='modulo_list_oscuro'>\n";
			$this->salida .= "					<td rowspan='4' class='label' align='center'>PUPILAS</td>\n";
			$this->salida .= "					<td colspan='3' class='label'>TALLA PUPILA D</td>\n";
			$this->salida .= "					<td>\n";
			$this->salida .= "						<select name='TallasPupilaD' class='select'>\n";
			foreach($Tallas as $key=> $value){
				if($TallasPupilaD == $value['talla_pupila_id']){
					$selected = "selected='true'";
				}
				else{
					$selected = "";
				}
				$this->salida .= "						<option value='".$value['talla_pupila_id']."' $selected>".$value['descripcion']."</option>\n";
			}
			$this->salida .= "						</select>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr class='modulo_list_oscuro'>\n";
			$this->salida .= "					<td colspan='3' class='label'>REACCION PUPILA D</td>\n";
			$this->salida .= "					<td>\n";
			$this->salida .= "						<select name='ReaccionPupilaD' class='select'>\n";
			foreach($Reaccion as $key=> $value){
				if($ReaccionPupilaD == $value['reaccion_pupila_id']){
					$selected = "selected='true'";
				}
				else{
					$selected = "";
				}
				$this->salida .= "						<option value='".$value['reaccion_pupila_id']."' $selected>".$value['descripcion']."</option>\n";
			}
			$this->salida .= "						</select>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr class='modulo_list_oscuro'>\n";
			$this->salida .= "					<td colspan='3' class='label'>TALLA PUPILA I</td>\n";
			$this->salida .= "					<td>\n";
			$this->salida .= "						<select name='TallasPupilaI' class='select'>\n";
			foreach($Tallas as $key=> $value){
				if($TallasPupilaI == $value['talla_pupila_id']){
					$selected = "selected='true'";
				}
				else{
					$selected = "";
				}
				$this->salida .= "						<option value='".$value['talla_pupila_id']."' $selected>".$value['descripcion']."</option>\n";
			}
			$this->salida .= "						</select>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= " 				</tr>\n";
			$this->salida .= "				<tr class='modulo_list_oscuro'>\n";
			$this->salida .= "					<td colspan='3' class='label'>REACCION PUPILA I</td>\n";
			$this->salida .= "					<td>\n";
			$this->salida .= "						<select name='ReaccionPupilaI' class='select'>\n";
			foreach($Reaccion as $key=> $value){
				if($ReaccionPupilaI == $value['reaccion_pupila_id']){
					$selected = "selected='true'";
				}
				else{
					$selected = "";
				}
				$this->salida .= "						<option value='".$value['reaccion_pupila_id']."' $selected>".$value['descripcion']."</option>\n";
			}
			$this->salida .= "						</select>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr class='modulo_list_claro'>\n";
			$this->salida .= "					<td rowspan='".sizeof($NivelesConciencia)."'  class=\"".$this->SetStyle("NivelConciencia")."\" align='center'>NIVELES DE CONCIENCIA</td>\n";
			$i=0;
			//nivel_consciencia_id,
			foreach($NivelesConciencia as $key => $value)
			{
				if($NivelConciencia == $value['nivel_consciencia_id']){
					$checked = "checked='true'";
				}
				else{
					$checked = "";
				}
				if(!$i)
				{
					$this->salida .= "			<td colspan='3' class='label'>".strtoupper($value['descripcion'])."</td>\n";
					$this->salida .= "			<td><input type='radio' name='NivelConciencia' value='".$value['nivel_consciencia_id']."' $checked></td>\n";
					$this->salida .= "		</tr>\n";
				}
				else
				{
					$this->salida .= "		<tr class='modulo_list_claro'>\n";
					$this->salida .= "			<td colspan='3' class='label'>".strtoupper($value['descripcion'])."</td>\n";
					$this->salida .= "			<td class='label'><input type='radio' name='NivelConciencia' value='".$value['nivel_consciencia_id']."' $checked></td>\n";
					$this->salida .= "		</tr>\n";
				}
				$i++;
			}
			$this->salida .= "				<tr class='modulo_list_oscuro'>\n";
			$this->salida .= "					<td rowspan='4' class='label' align='center'>FUERZA</td>\n";
			$this->salida .= "					<td colspan='3' class='label'>BRAZO DERECHO</td>\n";
			$this->salida .= "					<td>\n";
			$this->salida .= "						<select name='FuerzaBrazoD' class='select'>\n";
			foreach($TiposFuerza as $key=> $value){
				if($FuerzaBrazoD == $value['fuerza_id']){
					$selected = "selected='true'";
				}
				else{
					$selected = "";
				}
				$this->salida .= "						<option value='".$value['fuerza_id']."' $selected>".$value['descripcion']."</option>\n";
			}
			$this->salida .= "						</select>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr class='modulo_list_oscuro'>\n";
			$this->salida .= "					<td class='label' colspan='3'>PIERNA DERECHA</td>\n";
			$this->salida .= "					<td>\n";
			$this->salida .= "						<select name='FuerzaPiernaD' class='select'>\n";
			foreach($TiposFuerza as $key=> $value){
				if($FuerzaPiernaD == $value[fuerza_id]){
					$selected = "selected='true'";
				}
				else{
					$selected = "";
				}
				$this->salida .= "						<option value='".$value['fuerza_id']."' $selected>".$value['descripcion']."</option>\n";
			}
			$this->salida .= "						</select>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr class='modulo_list_oscuro'>\n";
			$this->salida .= "					<td class='label' colspan='3'>BRAZO IZQUIERDO</td>\n";
			$this->salida .= "					<td>\n";
			$this->salida .= "						<select name='FuerzaBrazoI' class='select'>\n";
			foreach($TiposFuerza as $key=> $value){
				if($FuerzaBrazoI == $value[fuerza_id]){
					$selected = "selected='true'";
				}
				else{
					$selected = "";
				}
				$this->salida .= "						<option value='".$value['fuerza_id']."' $selected>".$value['descripcion']."</option>\n";
			}
			$this->salida .= "						</select>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr class='modulo_list_oscuro'>\n";
			$this->salida .= "					<td class='label' colspan='3'>PIERNA IZQUIERDA</td>\n";
			$this->salida .= "					<td>\n";
			$this->salida .= "						<select name='FuerzaPiernaI' class='select'>\n";
			foreach($TiposFuerza as $key=> $value){
				if($FuerzaPiernaI == $value[fuerza_id]){
					$selected = "selected='true'";
				}
				else{
					$selected = "";
				}
				$this->salida .= "						<option value='".$value['fuerza_id']."' $selected>".$value['descripcion']."</option>\n";
			}
			$this->salida .= "						</select>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr class='modulo_list_claro'>\n";
			$this->salida .= "					<td rowspan='".(sizeof($TipoAperturaOcular)+sizeof($RespuestaVerbal)+sizeof($RespuestaMotora))."'  class='label' align='center'>ESCALA DE GLASGOW</td>\n";
			$this->salida .= "					<td rowspan='".sizeof($TipoAperturaOcular)."' class=\"".$this->SetStyle("AperturaOcular")."\" >APERTURA OCULAR</td>\n";
			$i=0;
			foreach($TipoAperturaOcular as $key => $value)
			{
				if($AperturaOcular == $value['apertura_ocular_id']){
					$checked = "checked='true'";
				}
				else{
					$checked = "";
				}
				if(!$i)
				{
					$this->salida .= "			<td class='label'>".strtoupper($value['descripcion'])."</td>\n";
					$this->salida .= "			<td class='label' align='center'>".$value['apertura_ocular_id']."</td>\n";
					$this->salida .= "			<td><input type='radio' name='AperturaOcular' value='".$value['apertura_ocular_id']."' $checked></td>\n";
					$this->salida .= "		</tr>\n";
				}
				else
				{
					$this->salida .= "		<tr class='modulo_list_claro'>\n";
					$this->salida .= "			<td class='label'>".strtoupper($value['descripcion'])."</td>\n";
					$this->salida .= "			<td class='label' align='center'>".$value['apertura_ocular_id']."</td>\n";
					$this->salida .= "			<td><input type='radio' name='AperturaOcular' value='".$value['apertura_ocular_id']."' $checked></td>\n";
					$this->salida .= "		</tr>\n";
				}
				$i++;
			}
			$this->salida .= "				<tr class='modulo_list_oscuro'>\n";
			$this->salida .= "					<td rowspan='".sizeof($RespuestaVerbal)."' class=\"".$this->SetStyle("RespuestaVerbal")."\" >RESPUESTA VERBAL</td>\n";
			$i=0;
			foreach($RespuestaVerbal as $key => $value)
			{
				if($RespVerbal == $value['respuesta_verbal_id']){
					$checked = "checked='true'";
				}
				else{
					$checked = "";
				}
				if(!$i)
				{
					$this->salida .= "			<td class='label'>".strtoupper($value['descripcion'])."</td>\n";
					$this->salida .= "			<td class='label' align='center'>".$value['respuesta_verbal_id']."</td>\n";
					$this->salida .= "			<td><input type='radio' name='RespuestaVerbal' value='".$value['respuesta_verbal_id']."' $checked></td>\n";
					$this->salida .= "		</tr>\n";
				}
				else
				{
					$this->salida .= "		<tr class='modulo_list_oscuro'>\n";
					$this->salida .= "			<td class='label'>".strtoupper($value['descripcion'])."</td>\n";
					$this->salida .= "			<td class='label' align='center'>".$value['respuesta_verbal_id']."</td>\n";
					$this->salida .= "			<td><input type='radio' name='RespuestaVerbal' value='".$value['respuesta_verbal_id']."' $checked></td>\n";
					$this->salida .= "		</tr>\n";
				}
				$i++;
			}
			$this->salida .= "				<tr class='modulo_list_claro'>\n";
			$this->salida .= "					<td rowspan='".sizeof($RespuestaMotora)."' class=\"".$this->SetStyle("RespuetaMotora")."\" >RESPUESTA MOTORA</td>\n";
			$i=0;
			foreach($RespuestaMotora as $key => $value)
			{
				if($RespMotora == $value['respuesta_motora_id']){
					$checked = "checked='true'";
				}
				else{
					$checked = "";
				}
				if(!$i)
				{
					$this->salida .= "			<td class='label'>".strtoupper($value['descripcion'])."</td>\n";
					$this->salida .= "			<td class='label' align='center'>".$value['respuesta_motora_id']."</td>\n";
					$this->salida .= "			<td><input type='radio' name='RespuestaMotora' value='".$value['respuesta_motora_id']."' $checked></td>\n";
					$this->salida .= "		</tr>\n";
				}
				else
				{
					$this->salida .= "		<tr class='modulo_list_claro'>\n";
					$this->salida .= "			<td class='label'>".strtoupper($value['descripcion'])."</td>\n";
					$this->salida .= "			<td class='label' align='center'>".$value['respuesta_motora_id']."</td>\n";
					$this->salida .= "			<td><input type='radio' name='RespuestaMotora' value='".$value['respuesta_motora_id']."' $checked></td>\n";
					$this->salida .= "		</tr>\n";
				}
				$i++;
			}
			$this->salida .= "			</table>\n";
			$this->salida .= "		</td>\n";
			$this->salida .= "	</tr>\n";
			$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$estacion));
			$hrefHN =ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmFrecuenciaControlesP',array("control"=>10,"ingreso"=>$_SESSION['ESTACION_CONTROL']['INGRESO'],"descripcion"=>"CONTROL NEUROL&Oacute;GICO","estacion"=>$estacion,"href_action_hora"=>"CallFrmControlNeurologico","href_action_control"=>array(0=>"CallFrmResumenHojaNeurologica")));
			$this->salida .= "	<tr><td align='center' colspan='4'><br><input type='submit' name='submit' value='GUARDAR DATOS' class='input-submit'><br><br>\n";
			$this->salida .= "				<a href='".$hrefHN."'>Volver a Control Neurol&oacute;gico</a>&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;\n";
			$this->salida .= "				<a href='".$href."'>Volver al Menu Estaci&oacute;n</a></td></tr>\n";
			$this->salida .= "</table><br>\n";
			$this->salida .= "</form>\n";
			$this->salida .= themeCerrarTabla();
			return true;
		}//fin FrmControlNeurologico

/*
		*		FrmResumenHojaNeurologica
		*
		*		Muestra los registros que se tienen hasta la fecha sobre el control neurologico
		*
		*		@Author Rosa Maria Angel
		*		@access Private
		*		@return bool
		*/
		function FrmResumenHojaNeurologica($datos_paciente,$estacion)//,$VerMasResultados,$limit,$offset
		{
			/*if(!$VerMasResultados)
			{*///se mete por primera vez para mostrar los primeros x resultados
				$limit = 4; $offset = 0;
				$Resumen = $this->GetResumenHojaNeurologica($datos_paciente[ingreso],$limit,$offset);//ojo, limit hasta 4 queda bien
			/*}
			else{
				$Resumen = $this->GetResumenHojaNeurologica($datos_paciente[ingreso],$limit,$offset);//ojo, limit hasta 4 queda bien
			}*/
			if(!$Resumen){
				return false;
			}
			elseif($Resumen === "ShowMensaje")
			{
				$mensaje = "NO SE ENCONTRARON REGISTROS DE CONTROLES NEUROLOGICOS DEL PACIENTE";
				$titulo = "MENSAJE";
				$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$estacion));
				$boton = "VOLVER AL MEN&Uacute; ESTACI&Oacute;N";
				$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
				return true;
			}
			else
			{

				$this->salida .= ThemeAbrirTabla("RESUMEN HOJA NEUROLOGICA");
				$this->salida .= "<table align=\"center\" width=\"95%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class='normal_10'>\n";
				$this->salida .= "	<tr>\n";
				$this->salida .= "		<td><br>\n";
				$this->salida .= "			<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_title\">\n";
				$this->salida .= "				<tr class=\"modulo_table_title\">\n";
				$this->salida .= "					<td>PACIENTE</td>\n";
				$this->salida .= "					<td>ID</td>\n";
				$this->salida .= "					<td>HABITACION</td>\n";
				$this->salida .= "					<td>CAMA</td>\n";
				$this->salida .= "				</tr>\n";
				$this->salida .= "				<tr align='center' class='modulo_list_oscuro'>\n";
				$this->salida .= "					<td>".$datos_paciente[primer_nombre]." ".$datos_paciente[segundo_nombre]." ".$datos_paciente[primer_apellido]." ".$datos_paciente[segundo_apellido]."</td>\n";
				$this->salida .= "					<td>".$datos_paciente[tipo_id_paciente]." ".$datos_paciente[paciente_id]."</td>\n";
				if(!empty($datos_paciente[pieza]))
                    {
                    	$this->salida .= "					<td>".$datos_paciente[pieza]."</td>\n";
                    }
                    else
                    {
                         $this->salida .= "					<td>No Ingresado</td>\n";
                    }
				if(!empty($datos_paciente[cama]))
                    {
                    	$this->salida .= "					<td>".$datos_paciente[cama]."</td>\n";
                    }
                    else
                    {
                         $this->salida .= "					<td>No Ingresado</td>\n";
                    }
				$this->salida .= "				</tr>\n";
				$this->salida .= "			</table>\n";
				/*$this->salida .= ThemeAbrirTabla("RESUMEN HOJA NEUROLOGICA");
				$this->salida .= "<table align=\"center\" width=\"95%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class='normal_10'>\n";
				$this->salida .= "	<tr>\n";
				$this->salida .= "		<td><br>\n";
				$this->salida .= "			<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_title\">\n";
				$this->salida .= "				<tr class=\"modulo_table_title\">\n";
				$this->salida .= "					<td>PACIENTE</td>\n";
				$this->salida .= "					<td>ID</td>\n";
				$this->salida .= "					<td>HABITACION</td>\n";
				$this->salida .= "					<td>CAMA</td>\n";
				$this->salida .= "				</tr>\n";
				$this->salida .= "				<tr align='center' class='modulo_list_oscuro'>\n";
				$this->salida .= "					<td>".$datos_paciente[primer_nombre]." ".$datos_paciente[segundo_nombre]." ".$datos_paciente[primer_apellido]." ".$datos_paciente[segundo_apellido]."</td>\n";
				$this->salida .= "					<td>".$datos_paciente[tipo_id_paciente]." ".$datos_paciente[paciente_id]."</td>\n";
				$this->salida .= "					<td>".$datos_paciente[pieza]."</td>\n";
				$this->salida .= "					<td>".$datos_paciente[cama]."</td>\n";
				$this->salida .= "				</tr>\n";
				$this->salida .= "			</table>\n";
				$this->salida .= "		</td>\n";
				$this->salida .= "	</tr>\n";
				$this->salida .= "	<tr><td>&nbsp;</td></tr>\n";
				$this->salida .= "	<tr>\n";
				$this->salida .= "		<td>\n";
				$this->salida .= "			<table width='100%' border='0'  align='center'>\n";
				$this->salida .= "				<tr class=\"modulo_table_list_title\" align='center'><td colspan='5'>RESUMEN HOJA NEUROLOGICA</td></tr>\n";
				$this->salida .= "				<tr class=\"modulo_table_list_title\" align='center'>\n";
				$this->salida .= "					<td colspan='2'>&nbsp;</td>\n";
				foreach ($Resumen as $key => $value)
				{
					list($date,$time) = explode (" ",$key);
					if($date == date("Y-m-d")) {
						$fecha = "HOY ".$time;
					}
					elseif($date == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y")))){
						$fecha = "AYER ".$time;
					}
					else{
						$fecha = $key;
					}
					$this->salida .= "				<td>".$fecha."\n";
				}
				$this->salida .= "				</tr>\n";
				$this->salida .= "				<tr class='modulo_list_oscuro'>\n";
				$this->salida .= "					<td rowspan='4' class='label' align='center'>PUPILAS</td>\n";
				$this->salida .= "					<td class='label'>TALLA PUPILA D</td>\n";
				foreach ($Resumen as $key => $value){
					$this->salida .= "				<td align='center'>".$value[0][tallapupilader]."\n";
				}
				$this->salida .= "				</tr>\n";
				$this->salida .= "				<tr class='modulo_list_oscuro'>\n";
				$this->salida .= "					<td class='label'>REACCION PUPILA D</td>\n";
				foreach ($Resumen as $key => $value){
					$this->salida .= "				<td align='center'>".$value[0][reaccionpupilader]."\n";
				}
				$this->salida .= "				</tr>\n";
				$this->salida .= "				<tr class='modulo_list_oscuro'>\n";
				$this->salida .= "					<td class='label'>TALLA PUPILA I</td>\n";
				foreach ($Resumen as $key => $value){
					$this->salida .= "				<td align='center'>".$value[0][tallapupilaizq]."\n";
				}
				$this->salida .= " 				</tr>\n";
				$this->salida .= "				<tr class='modulo_list_oscuro'>\n";
				$this->salida .= "					<td class='label'>REACCION PUPILA I</td>\n";
				foreach ($Resumen as $key => $value){
					$this->salida .= "				<td align='center'>".$value[0][reaccionpupilaizq]."</td>\n";
				}
				$this->salida .= "				</tr>\n";
				$this->salida .= "				<tr class='modulo_list_claro'>\n";
				$this->salida .= "					<td colspan='2'  class=\"".$this->SetStyle("NivelConciencia")."\" align='center'>NIVELES DE CONCIENCIA</td>\n";
				foreach ($Resumen as $key => $value){
					$this->salida .= "				<td align='center'>".$value[0][nivelconciencia]."</td>\n";
				}
				$this->salida .= "				</tr>\n";
				$this->salida .= "				<tr class='modulo_list_oscuro'>\n";
				$this->salida .= "					<td rowspan='4' class='label' align='center'>FUERZA</td>\n";
				$this->salida .= "					<td class='label'>BRAZO DERECHO</td>\n";
				foreach ($Resumen as $key => $value){
					$this->salida .= "					<td align='center'>".$value[0][fuerzabrazoder]."</td>\n";
				}
				$this->salida .= "				</tr>\n";
				$this->salida .= "				<tr class='modulo_list_oscuro'>\n";
				$this->salida .= "					<td class='label'>PIERNA DERECHA</td>\n";
				foreach ($Resumen as $key => $value){
					$this->salida .= "					<td align='center'>".$value[0][fuerzapiernader]."</td>\n";
				}
				$this->salida .= "				</tr>\n";
				$this->salida .= "				<tr class='modulo_list_oscuro'>\n";
				$this->salida .= "					<td class='label'>BRAZO IZQUIERDO</td>\n";
				foreach ($Resumen as $key => $value){
					$this->salida .= "				<td align='center'>".$value[0][fuerzabrazoizq]."</td>\n";
				}
				$this->salida .= "				</tr>\n";
				$this->salida .= "				<tr class='modulo_list_oscuro'>\n";
				$this->salida .= "					<td class='label'>PIERNA IZQUIERDA</td>\n";
				foreach ($Resumen as $key => $value){
					$this->salida .= "				<td align='center'>".$value[0][fuerzapiernaizq]."</td>\n";
				}
				$this->salida .= "				</tr>\n";
				$this->salida .= "				<tr class='modulo_list_claro'>\n";
				$this->salida .= "					<td rowspan='4'  class='label' align='center'>ESCALA DE GLASGOW</td>\n";
				$this->salida .= "					<td class='label'>APERTURA OCULAR</td>\n";
				foreach ($Resumen as $key => $value){
					$this->salida .= "					<td align='center'>".$value[0][aperturaocular]."</td>\n";
				}
				$this->salida .= "				</tr>\n";
				$this->salida .= "				<tr class='modulo_list_claro'>\n";
				$this->salida .= "					<td class='label'>RESPUESTA VERBAL</td>\n";
				foreach ($Resumen as $key => $value){
					$this->salida .= "				<td align='center'>".$value[0][respuestaverbal]."</td>\n";
				}
				$this->salida .= "				</tr>\n";
				$this->salida .= "				<tr class='modulo_list_claro'>\n";
				$this->salida .= "					<td class='label'>RESPUESTA MOTORA</td>\n";
				foreach ($Resumen as $key => $value){
					$this->salida .= "				<td align='center'>".$value[0][respuestamotora]."</td>\n";
				}
				$this->salida .= "				</tr>\n";
				$this->salida .= "				<tr class='modulo_list_oscuro'>\n";
				$this->salida .= "					<td class='label'>TOTAL GLASGOW</td>\n";
				foreach ($Resumen as $key => $value)
				{
					$totalGlasgow = ($value[0][tipo_apertura_ocular_id]+$value[0][tipo_respuesta_verbal_id]+$value[0][tipo_respuesta_motora_id]);
					if($totalGlasgow < 8){
						$estilo = "GlasgowBajo";//rojo
					}
					elseif($totalGlasgow >= 8 && $totalGlasgow < 12){
						$estilo = "GlasgowIntermedio";//naranja
					}
					else{
						$estilo = "GlasgowAlto";//amarillo
					}
					$this->salida .= "				<td align='center' class='$estilo'>".$totalGlasgow."</td>\n";
				}
				$this->salida .= "				</tr>\n";
				$this->salida .= "			</table><br>\n";

				$this->salida .= "		</td>\n";
				$this->salida .= "	</tr>\n";

				$hrefHN =ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmFrecuenciaControlesP',array("control"=>10,"descripcion"=>"CONTROL NEUROL&Oacute;GICO","estacion"=>$estacion,"href_action_hora"=>"CallFrmControlNeurologico","href_action_control"=>array(0=>"CallFrmResumenHojaNeurologica")));
				$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$estacion));
				$this->salida .= "	<tr align='center'><td><br>\n";
				$this->salida .= "		<a href='".$hrefHN."'>Volver a Control Neurol&oacute;gico</a>&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;\n";
				$this->salida .= "		<a href='".$href."'>Volver al Menu Estaci&oacute;n</a></td>\n";
				$this->salida .= "	</tr>\n";
				$this->salida .= "</table>\n";
				$this->salida .= themeCerrarTabla();
			}*/

			$this->ShowControl_Neurologico($datos_paciente,$estacion);
			$hrefHN =ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmFrecuenciaControlesP',array("control"=>10,"ingreso"=>$_SESSION['ESTACION_CONTROL']['INGRESO'],"descripcion"=>"CONTROL NEUROL&Oacute;GICO","estacion"=>$estacion,"href_action_hora"=>"CallFrmControlNeurologico","href_action_control"=>array(0=>"CallFrmResumenHojaNeurologica")));
			$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$estacion));
			$this->salida .= "	<tr align='center'><td><br>\n";
			$this->salida .= "		<a href='".$hrefHN."'>Volver a Control Neurol&oacute;gico</a>&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;\n";
			$this->salida .= "		<a href='".$href."'>Volver al Menu Estaci&oacute;n</a></td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "</table>\n";
			$this->salida .= themeCerrarTabla();
			return true;
		}
	}
  //FrmResumenHojaNeurologica




//funcion de tizziano para revisar las pasadas hojas neurologicas.
	function ShowControl_Neurologico($datos_paciente,$estacion)
	{
  	$VectorControl = $this->Listar_ControlesNeurologicos($datos_paciente[ingreso]);

		/*Insercion del buscador*/
		$accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Listar_ControlesNeurologicos'));
		$this->salida.= "<form name=\"neuro$pfj\" action=\"$accionI\" method=\"post\">";
		if(!$VectorControl)
		{
			return false;
		}
		elseif($VectorControl != "ShowMensaje")
		{
			if (empty($contador)){
				$contador=sizeof($VectorControl);
			}

			$this->salida .="<br><table align=\"center\" width=\"100%\" border='0'>";
			$this->salida .="<tr class=\"modulo_table_list_title\">";
			$this->salida .="<td rowspan='2'>FECHA</td>";
			$this->salida .="<td rowspan='2'>HORA</td>";
			$this->salida .="<td colspan='2'>PUPILA DERECHA</td>";
			$this->salida .="<td colspan='2'>PUPILA IZQUIDA.</td>";
			$this->salida .="<td rowspan='2'>CONCIENCIA</td>";
			$this->salida .="<td colspan='4'> FUERZA </td>";
			$this->salida .="<td colspan='4'> ESCALA DE GLASGOW </td>";
			$this->salida .="<td rowspan='2'>USUARIO</td>";
			$this->salida .="</tr>";
			$this->salida .="<tr class='hc_table_submodulo_list_title'>";
			$this->salida .="<td align=\"center\"> TALLA </td>";
			$this->salida .="<td align=\"center\"> REACCION</td>";
			$this->salida .="<td align=\"center\"> TALLA </td>";
			$this->salida .="<td align=\"center\"> REACCION </td>";
			$this->salida .="<td align=\"center\"> B. DER. </td>";
			$this->salida .="<td align=\"center\"> B. IZQ. </td>";
			$this->salida .="<td align=\"center\"> P. DER. </td>";
			$this->salida .="<td align=\"center\"> P. IZQ. </td>";
			$this->salida .="<td align=\"center\"> A. OCULAR </td>";
			$this->salida .="<td align=\"center\"> R. VERBAL </td>";
			$this->salida .="<td align=\"center\"> R. MOTORA </td>";
			$this->salida .="<td align=\"center\"> E.G. </td>";
			$this->salida .="</tr>";
			$cont=1;
			$spy=0;
			while ($cont <= sizeof($VectorControl) && $cont <= $contador)
			{
				list($fecha,$hora) = explode(" ",$VectorControl[$cont-1][fecha]);
				list($ano,$mes,$dia) = explode("-",$fecha);
				list($hora,$min) = explode(":",$hora);
				$hora=$hora.":".$min;
				//$this->salida .= "<tr align='center'>\n";
				if($fecha == date("Y-m-d"))
				{
					$fecha = "HOY";
				}
				elseif($fecha == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y"))))
				{
					$fecha = "AYER";
				}
				else
				{
					$fecha = $fecha;
				}

				if($spy==0)
				{
					$this->salida.="<tr class=\"modulo_list_oscuro\">";
					$spy=1;
				}
				else
				{
					$this->salida.="<tr class=\"modulo_list_claro\">";
					$spy=0;
				}

				if($VectorControl[$cont-1][pupila_talla_d] == 0) $ptallad = "--"; else $ptallad = $VectorControl[$cont-1][pupila_talla_d];
				if($VectorControl[$cont-1][pupila_reaccion_d] == ' ') $preacciond = "--"; else $preacciond = $VectorControl[$cont-1][pupila_reaccion_d];
				if($VectorControl[$cont-1][pupila_talla_i] == 0) $ptallai = "--"; else $ptallai = $VectorControl[$cont-1][pupila_talla_i];
				if($VectorControl[$cont-1][pupila_reaccion_i] == ' ') $preaccioni = "--"; else $preaccioni = $VectorControl[$cont-1][pupila_reaccion_i];
				if($VectorControl[$cont-1][descripcion] == ' ') $conciencia = "--"; else $conciencia = $VectorControl[$cont-1][descripcion];
				if($VectorControl[$cont-1][fuerza_brazo_d] == ' ') $brazod = "--"; else $brazod = $VectorControl[$cont-1][fuerza_brazo_d];
				if($VectorControl[$cont-1][fuerza_brazo_i] == ' ') $brazoi = "--"; else $brazoi = $VectorControl[$cont-1][fuerza_brazo_i];
				if($VectorControl[$cont-1][fuerza_pierna_d] == ' ') $piernad = "--"; else $piernad = $VectorControl[$cont-1][fuerza_pierna_d];
				if($VectorControl[$cont-1][fuerza_pierna_i] == ' ') $piernai = "--"; else $piernai = $VectorControl[$cont-1][fuerza_pierna_i];
				if($VectorControl[$cont-1][tipo_apertura_ocular_id] == 0 ) $AO = "--"; else $AO = $VectorControl[$cont-1][tipo_apertura_ocular_id];
				if($VectorControl[$cont-1][tipo_respuesta_verbal_id] == 0 ) $RV = "--"; else $RV = $VectorControl[$cont-1][tipo_respuesta_verbal_id];
				if($VectorControl[$cont-1][tipo_respuesta_motora_id] == 0 ) $RM = "--"; else $RM = $VectorControl[$cont-1][tipo_respuesta_motora_id];
				if($VectorControl[$cont-1][usuario] == ' ') $user = "--"; else $user = $VectorControl[$cont-1][usuario];
				$EG = $AO + $RV + $RM;
				if($EG == 0) $EG = "--"; else $EG = $EG;

					$this->salida .="<td align=\"center\">" .$fecha. "</td>";
					$this->salida .="<td align=\"center\">" .$hora. "</td>";
					$this->salida .="<td align=\"center\">" .$ptallad. "</td>";
					$this->salida .="<td align=\"center\">" .$preacciond. "</td>";
					$this->salida .="<td align=\"center\">" .$ptallai. "</td>";
					$this->salida .="<td align=\"center\">" .$preaccioni. "</td>";
					$this->salida .="<td align=\"center\">" .$conciencia. "</td>";
					$this->salida .="<td align=\"center\">" .$brazod. "</td>";
					$this->salida .="<td align=\"center\">" .$brazoi. "</td>";
					$this->salida .="<td align=\"center\">" .$piernad. "</td>";
					$this->salida .="<td align=\"center\">" .$piernai. "</td>";
					$this->salida .="<td align=\"center\">" .$AO. "</td>";
					$this->salida .="<td align=\"center\">" .$RV. "</td>";
					$this->salida .="<td align=\"center\">" .$RM. "</td>";
					if ($EG < 8)
					{
						$this->salida .="<td align=\"center\" class ='GlasgowBajo'>" .$EG. "</td>";
					}

					if ($EG >= 8 && $EG < 12)
					{
						$this->salida .="<td align=\"center\" class ='GlasgowIntermedio'>" .$EG. "</td>";
					}

					if ($EG >= 12)
					{
						$this->salida .="<td align=\"center\" class ='GlasgowAlto'>" .$EG. "</td>";
					}

					$fechareg =$VectorControl[$cont-1][fecha_registro];
					$user=$this->GetDatosUsuarioSistema($VectorControl[$cont-1][usuario_id]);
					if ($VectorControl[$cont-1][usuario_id] == UserGetUID() AND $VectorControl[$cont-1][evolucion_id] == $this->evolucion)
					{
						//$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'BorrarControlNeuro', 'fechar'.$pfj=>$fechareg));
						$action = ModuloGetURL('app','EstacionE_ControlPacientes','user','Borrar_ControlNeuro',array("fechar"=> $VectorControl[$cont-1][fecha_registro],"estacion"=>$estacion,"paciente"=>$datos_paciente));
						$this->salida .= "<td><a href='".$action."'>ELIMINAR</a></td>\n";
					}
					else
					{
						$this->salida .="<td align=\"center\">" .$user[0][usuario]. "</td>";
					}

					$this->salida .="</tr>";
					$cont++;
				}
				$this->salida .="</table>";
				//Mostrar Barra de Navegacion
			//	$VectorControl=$this->RetornarBarra_Paginadora();
				/*if($VectorControl)
				{
					$this->salida .= "  <br><table border=\"0\" width=\"60%\" align=\"center\">";
					$this->salida .= "  <tr>";
					$this->salida .= "  <td width=\"100%\" align=\"center\">";
					$this->salida .=$VectorControl;
					$this->salida .= "  </td>";
					$this->salida .= "  </tr>";
					$this->salida .= "  </table><br>";
				}*/
				$this->salida .= "</form>";
		}
		return true;
	}
//fin de la funcion de tizziano para revisar las pasadas hojas neurologicas.


		/*
		*		FrmIngresarHemoclasificacionPaciente
		*
		*		Permite definirle al paciente su grupo sanguineo y rh
		*
		*		@Author Rosa Maria Angel
		*		@access Private
		*		@param array datos del paciente
		*		@param array datos de la estacion
		*		@return bool
		*/
		function FrmIngresarHemoclasificacionPaciente($estacion,$datos_estacion)
		{
			$gruposSanguineos = $this->GetGruposSanguineos();
			if($gruposSanguineos === "ShowMensaje")
			{
				$mensaje = "NO SE ENCONTRARON GRUPOS SANGUINEOS DISPONIBLES";
				$titulo = "MENSAJE";
				$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
				$boton = "VOLVER AL MENU ESTACION";
				$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
				return true;
			}
			$action = ModuloGetURL('app','EstacionE_ControlPacientes','user','IngresarHemoclasificacion',array("estacion"=>$estacion,"datos_estacion"=>$datos_estacion));
			$this->salida .= "<form name=\"IngresarHemoclasificacion\" method=\"POST\" action=\"$action\"><br>\n";
			$this->salida .= ThemeAbrirTabla("EDICI&Oacute;N DE LA PLANTILLA")."<br>";
			$this->salida .= "	<table align=\"center\" width=\"70%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_title\">\n";
			$this->salida .= "		<tr class=\"modulo_table_title\">\n";
			$this->salida .= "			<td>PACIENTE</td>\n";
			$this->salida .= "			<td>ID</td>\n";
			$this->salida .= "			<td>HAB.</td>\n";
			$this->salida .= "			<td>CAMA</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr align='center' class='modulo_list_claro'>\n";
			$this->salida .= "			<td>".$datos_estacion['NombrePaciente']."</td>\n";
			$this->salida .= "			<td>".$datos_estacion['tipo_id_paciente']." ".$datos_estacion['paciente_id']."</td>\n";
			$this->salida .= "			<td>".$datos_estacion['pieza']."</td>\n";
			$this->salida .= "			<td>".$datos_estacion['cama']."</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
      $this->salida .= "  <BR><table border=\"0\" width=\"60%\" align=\"center\" class=\"normal_10\">";
			$this->salida .= "  <tr><td align=\"center\">";
			$this->salida .=    $this->SetStyle("MensajeError");
			$this->salida .= "  </td><tr>";
      $this->salida .= "	</table>\n";
			$this->salida .= "  <BR><table border=\"0\" width=\"60%\" align=\"center\" class=\"normal_10\">";
			$this->salida.="    <tr class=\"modulo_table_title\">";
			$this->salida.="    <td colspan=\"4\">DATOS DE LA HEMOCLASIFICACION</td>";
			$this->salida.="    </tr>";
			$this->salida.="    <tr class = \"modulo_list_claro\">";
			$this->salida.="     <td align=\"left\" class=\"".$this->SetStyle("grupo_sanguineo")."\">GRUPO SANGUINEO</td>";
			$this->salida .= "   <td><select name=\"grupo_sanguineo\" class=\"select\" $desabilitado>";
			$facts=$this->ConsultaFactor();
			$this->salida .=" <option value=\"-1\" selected>---Seleccione---</option>";
			for($i=0;$i<sizeof($facts);$i++){
			  if($facts[$i]['grupo_sanguineo']==$_REQUEST['grupo_sanguineo']){
				  $select='selected';
				}else{
          $select='';
				}
        $this->salida .=" <option $select value=\"".$facts[$i]['grupo_sanguineo']."\">".$facts[$i]['grupo_sanguineo']."</option>";
			}
			$this->salida .= "   </select></td>";
			$this->salida.="     <td align=\"left\" class=\"".$this->SetStyle("rh")."\">Rh </td>";
			$this->salida.="     <td align=\"left\" >";
			$this->salida.="     <select size=\"1\" name =\"rh\" class =\"select\" $desabilitado>";
			if($_REQUEST['rh']=='+'){
				$checkeado='selected';
			}elseif($_REQUEST['rh']=='-'){
				$checkeado1='selected';
			}
			$this->salida.="     <option value = -1>-Seleccione-</option>";
			$this->salida.="     <option value=\"+\" $checkeado> Positivo </option>";
			$this->salida.="     <option value=\"-\" $checkeado1> Negativo </option>";
			$this->salida.="     </select>";
			$this->salida.="     </td>";
			$this->salida.="     </tr>";
			$this->salida .="    <tr class = \"modulo_list_claro\">";
			if(!$_REQUEST['fecha_examen']){
				$_REQUEST['fecha_examen']=date('d-m-Y');
			}
			$this->salida .="    <td class=\"".$this->SetStyle("fecha_examen")."\" align=\"left\">FECHA DEL EXAMEN</td>";
			$this->salida .="    <td colspan=\"3\" align=\"left\"><input type=\"text\" readonly class=\"input-text\" size=\"10\" maxlength=\"10\" value=\"".$_REQUEST['fecha_examen']."\" name=\"fecha_examen\" onBlur=\"IsValidDate(this,'YYYY-MM-DD')\" onKeyUp=\"setDate(this,'YYYY-MM-DD','es')\">
			".ReturnOpenCalendario('forma','fecha_examen','-')."</td>" ;
			$this->salida.="     </tr>";
			$this->salida .="    <tr class = \"modulo_list_claro\">";
			$this->salida.="     <td class=\"".$this->SetStyle("laboratorio")."\" align=\"left\">LABORATORIO</td>";
			$this->salida.="     <td colspan=\"3\" align=\"left\"><input type=\"text\" name=\"laboratorio\" value=\"\" size=\"40\" class=\"input-submit\"></td>";
			$this->salida.="     </tr>";
			$this->salida .="    <tr class = \"modulo_list_claro\">";
			$this->salida .= "   <td class=\"".$this->SetStyle("bacteriologo")."\">PROFESIONAL</td>";
			$this->salida .= "   <td colspan=\"3\"><select name=\"bacteriologo\" class=\"select\">";
			$bacteriologos=$this->TotalBacteriologos();
			$this->salida .=" <option value=\"-1\" selected>---Seleccione---</option>";
			for($i=0;$i<sizeof($bacteriologos);$i++){
			  if($bacteriologos[$i]['tercero_id']."/".$bacteriologos[$i]['tipo_id_tercero']==$_REQUEST['bacteriologo']){
				  $select='selected';
				}else{
          $select='';
				}
        $this->salida .=" <option $select value=\"".$bacteriologos[$i]['tercero_id']."/".$bacteriologos[$i]['tipo_id_tercero']."\">".$bacteriologos[$i]['nombre']."</option>";
			}
			//$this->BuscarProfesionlesEspecialistas($bacteriologos,'False',$_REQUEST['bacteriologo']);
			$this->salida .= "    </select></td>";
			$this->salida.="     </tr>";
			$this->salida .="    <tr class = \"modulo_list_claro\">";
			$this->salida.="     <td align=\"left\" colspan=\"4\"><b>OBSERVACIONES</b><br><textarea style=\"width:100%\" name=\"observaciones\" class=\"textarea\" rows=\"3\" cols=\"60\"></textarea></td>";
			$this->salida.="     </tr>";
			$this->salida.="     </table>";

			$this->salida .= "<div class='normal_10' align='center'><br><input type='submit' class='input-submit' name='Save' value='GUARDAR'>";
			$this->salida .= "</form>\n";
			$href = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmTransfusiones',array("datos_estacion"=>$datos_estacion,"estacion"=>$estacion));
			$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al listado de transfusiones</a><br>\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}//FrmIngresarHemoclasificacionPaciente

/*
		*		FrmTransfusiones
		*
		*		Formulario que permite capturar los datos de las transfusiones sanguineas de un paciente
		*
		*		@Author Rosa Maria Angel
		*		@access Private
		*		@param array datos del paciente
		*		@param array datos de la estacion
		*		@return bool
		*/
		function FrmTransfusiones($estacion,$datos_estacion)
		{

			if($_REQUEST['origen']==1){
        $dis='disabled';
				$read='readonly';
			}
			$gruposSanguineos = $this->GetGruposSanguineos();
			$GS = $this->GetGrupoSanguineoPaciente($estacion,$datos_estacion);
			if(!$GS){
				return false;
			}
			if(!$gruposSanguineos){
				return false;
			}
			if($gruposSanguineos === "ShowMensaje")
			{
				$mensaje = "NO SE ENCONTRARON GRUPOS SANGUINEOS DISPONIBLES";
				$titulo = "MENSAJE";
				$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
				$boton = "VOLVER AL MENU ESTACION";
				$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
				return true;
			}
			$this->salida .= ThemeAbrirTabla($datos_estacion['control_descripcion']);

			$this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_title\">\n";
			$this->salida .= "		<tr class=\"modulo_table_title\">\n";
			$this->salida .= "			<td>PACIENTE</td>\n";
			$this->salida .= "			<td>ID</td>\n";
			$this->salida .= "			<td>HAB.</td>\n";
			$this->salida .= "			<td>CAMA</td>\n";
			$this->salida .= "			<td>GRUPO SANGUINEO</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
			$this->salida .= "			<td>".$datos_estacion['NombrePaciente']."</td>\n";
			$this->salida .= "			<td>".$datos_estacion[tipo_id_paciente]." ".$datos_estacion[paciente_id]."</td>\n";
			$this->salida .= "			<td>".$datos_estacion[pieza]."</td>\n";
			$this->salida .= "			<td>".$datos_estacion[cama]."</td>\n";
			$this->salida .= "			<td>\n";
			if($GS === "ShowMensaje"){
				$href = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmIngresarHemoclasificacionPaciente',array("datos_estacion"=>$datos_estacion,"estacion"=>$estacion));
				$this->salida .= "			<a href=\"$href\">ingresar G.S. y R.H.</a>\n";
			}
			else{
				$this->salida .= "			".$GS['grupo_sanguineo']." ".$GS['rh']."</td>\n";
			}
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table><br>\n";
      $this->ShowBolsasReservadas($estacion,$datos_estacion,0);
			$href = ModuloGetURL('app','EstacionE_ControlPacientes','user','InsertarTransfusion',array("datos_estacion"=>$datos_estacion,"estacion"=>$estacion,'origen'=>$_REQUEST['origen']));//,"referer_name"=>$referer_name,"referer_parameters"=>$referer_parameters
			$this->salida .= "<form name='frmTransfusiones' action='".$href."' method='POST'><br>\n";
			$this->salida .= "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"normal_10\">\n";
			$this->salida .= $this->SetStyle("MensajeError",1);
			$this->salida .= "	</table>\n";
			$this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class='modulo_table_list'>\n";
			$this->salida .= "		<tr class=\"modulo_table_title\">\n";
			$this->salida .= "			<td>DESCRIPCION</td>\n";
			$this->salida .= "			<td colspan='2'>NUMERO DE IDENTIFICACION UNIDADES TRANSFUNDIDAS</td>\n";
			$this->salida .= "		</tr>\n";

			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td class='label'>NUMERO DE SELLO NACIONAL DE CALIDAD:</td>\n";
			$this->salida .= "			<td> <input $read type='text' class='input-text' name='numSello' value='".$_REQUEST['numSello']."' size='20' maxlength='32'></td>\n";
			$this->salida .= "			<td>&nbsp;</td>\n";
			$this->salida .= "		</tr>\n";

			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td class='label'>NUMERO DE BOLSA:</td>\n";
			$this->salida .= "			<td>";
			$this->salida .= "			<input $read type='text' class='input-text' name='cantBolsas' value='".$_REQUEST['cantBolsas']."' size='20' maxlength='20'>";
			if($_REQUEST['origen']==1){
				$this->salida .= "			<label class=\"label\">No. ALICUOTA<label>";
				if(empty($_REQUEST['numeroAlicuota'])){$numeroAlicuotaDes='PRINCIPAL';}else{$numeroAlicuotaDes=$_REQUEST['numeroAlicuota'];}
				$this->salida .= "			<input $read type='text' class='input-text' name='numeroAlicuotaDes' value=\"$numeroAlicuotaDes\" size=\"9\" maxlength=\"9\">";
			}
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td>&nbsp;</td>\n";
			$this->salida .= "		  <input type=\"hidden\" name=\"IngresoBolsaId\" value=\"".$_REQUEST['IngresoBolsaId']."\">\n";
			$this->salida .= "		  <input type=\"hidden\" name=\"numeroAlicuota\" value=\"".$_REQUEST['numeroAlicuota']."\">\n";
			$this->salida .= "		  <input type=\"hidden\" name=\"numeroReserva\" value=\"".$_REQUEST['numeroReserva']."\">\n";
			$this->salida .= "		</tr>\n";

			$this->salida .= "		<tr>\n";
			$comp=$this->TraerComponentes();
			$this->salida .= "			<td class='label'>COMPONENTE SANGUINEO:</td>\n";
			$this->salida.="<td><select $dis name='componente' class='select'>";
      for($i=0;$i<sizeof($comp);$i++)
      {
			  if($_REQUEST['componente']==$comp[$i][hc_tipo_componente]){
          $select='selected';
				}else{
          $select='';
				}
        $this->salida.="<option $select value=".$comp[$i][hc_tipo_componente].">".$comp[$i][componente]."</option>";
      }
      $this->salida.="</select>";
      $this->salida.="</td></tr>";
			if($_REQUEST['origen']==1){
			  $this->salida .= "<input type=\"hidden\" name=\"componente\" value=\"".$_REQUEST['componente']."\">\n";
			}
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td class='label'>FECHA DE VENCIMIENTO:</td>\n";
			$this->salida .= "			<td><input type='text' class='input-text' name='fechaVencimiento' value='".$_REQUEST['fechaVencimiento']."' size='10' maxlength='10' readonly='yes'>";
			if($_REQUEST['origen']!=1){
			  $this->salida .= "			".ReturnOpenCalendario('frmTransfusiones','fechaVencimiento','-')."";
      }
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td>&nbsp;</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td class='label'>TIPO SANGUINEO:</td>\n";
			$this->salida .= "			<td>\n";
			$this->salida .= "				<select $dis name='tipoSanguineo' class='select'>\n";
			foreach($gruposSanguineos as $key => $value)
			{
				if($_REQUEST['tipoSanguineo'] == $value['grupo_sanguineo'].".-.".$value['rh']){
					$selected = "selected";
				}
				else { $selected = ""; }
				$this->salida .= "				<option value='".$value['grupo_sanguineo'].".-.".$value['rh']."' $selected>".$value[grupo_sanguineo]."  ".$value[rh]."</option>\n";
			}
			$this->salida .= "				</select>\n";
			$this->salida .= "			</td>\n";
			if($_REQUEST['origen']==1){
			  $this->salida .= "<input type=\"hidden\" name=\"tipoSanguineo\" value=\"".$_REQUEST['tipoSanguineo']."\">\n";
			}
			$this->salida .= "			<td>&nbsp;</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td class='label'>ENTIDAD ORIGEN COMPONENTE:</td>\n";
			$this->salida .= "			<td><input $read type=\"text\" size=\"45\" maxlength=\"250\" class=\"input-text\" name=\"origenComponente\" value=\"".$_REQUEST['origenComponente']."\"></td>\n";
      $this->salida .= "		</tr>\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td class='label'>FECHA Y HORA DE INICIO TRANSFUSION:</td>\n";
			if(empty($_REQUEST['fechaInicio'])){
        $_REQUEST['fechaInicio']=date("d-m-Y");
			}
			$this->salida .= "			<td>\n";
			$this->salida .= "				<input type='text' class='input-text' name='fechaInicio' value='".$_REQUEST['fechaInicio']."' size='10' maxlength='10' readonly='yes'>".ReturnOpenCalendario('frmTransfusiones','fechaInicio','-')."\n";
			$this->salida .= "				<select name='HoraInicio' class='select'>\n";
			for($i=0; $i<24; $i++)
			{
				$hora = date("H", mktime($i,0,0,date("m"),date("d"),date("Y")));
				if($hora == date("H")){
					$selected = 'selected="yes"';
				}
				else { $selected = ""; }
				$this->salida .= "				<option value='$hora' $selected>$hora</option>\n";
			}
			$this->salida .= "				</select><b>&nbsp;:&nbsp;</b>\n";
			$this->salida .= "				<select name='MinutoInicio' class='select'>\n";
			for($i=0; $i<60; $i++)
			{
				$min = date("i",mktime(date("H"),$i,date("s"),date("m"),date("d"),date("Y")));
				if(date("i") == $min){
					$selected = 'selected="yes"';
				}
				else { $selected = ""; }
				$this->salida .= "				<option value='".$min.":00' $selected>$min</option>\n";
			}
			$this->salida .= "				</select>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td>&nbsp;</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td colspan='3'>\n";
			$this->salida .= "				<input type='hidden' name='ingreso' value='".$datos_estacion['ingreso']."'>\n";
			$this->salida .= "				<div class='normal_10' align='center'><br><input type='submit' class='input-submit' name='Save' value='GUARDAR'>";
			$href = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallControlesPacientes',array("control_id"=>$datos_estacion['control_id'],"estacion"=>$estacion,"control_descripcion"=>$datos_estacion['control_descripcion']));
			//$href = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallListRevisionPorSistemas',array();
			$this->salida .= "				<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Listado de Transfusiones</a><br>";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n\n";
			$this->salida .= "</form>\n";

			/*if(!isset($_REQUEST['cantidad']))
				$this->ShowTransfusiones($estacion,$datos_estacion,1);
			else*/
			$this->ShowTransfusiones($estacion,$datos_estacion,0);
			$this->salida .= themeCerrarTabla();
			return true;
		}//FrmTransfusiones


    /*
		*		ShowTransfusiones
		*
		*		Muestra los registros de transfusiones que tiene el paciente y permite ingresar la fecha
		*		finalizacion de la trasnfusion y reacciones adversase que presente el paciente
		*
		*		@Author Rosa Maria Angel
		*		@access Private
		*		@param array datos del paciente
		*		@param array datos de la estacion
		*		@param array datos de la numero de registros
		*		@return bool
		*/
	function ShowBolsasReservadas($estacion,$datos_estacion,$contador){
		$reservasPaciente = $this->GetReservasPacientes($datos_estacion['tipo_id_paciente'],$datos_estacion['paciente_id']);
		if($reservasPaciente){
			$action = ModuloGetURL('app','EstacionE_ControlPacientes','user','InsertarFechaFinTransfusion',array("datos_estacion"=>$datos_estacion,"estacion"=>$estacion));
			$this->salida .= "<form name='frmShowTransfusiones' action='".$action."' method='POST'><br>\n";
			$this->salida .= "<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"1\" border=\"0\">\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "			<td colspan=\"10\">COMPONENTES RECIBIDOS PARA TRANSFUNDIR</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "			<td width=\"10%\">No. RESERVA</td>\n";
			$this->salida .= "			<td width=\"15%\">BOLSA<br>- ALICUOTA -</td>\n";
			$this->salida .= "			<td width=\"13%\"># SELLO<BR>CALIDAD</td>\n";
			$this->salida .= "			<td width=\"12%\">FECHA DE<br>VENCIMIENTO</td>\n";
			$this->salida .= "			<td width=\"10%\">COMPONENTE</td>\n";
			$this->salida .= "			<td width=\"5%\">G.S.</td>\n";
			$this->salida .= "			<td width=\"5%\">RH</td>\n";
			$this->salida .= "			<td width=\"10%\">DESPACHADA</td>\n";
			$this->salida .= "			<td width=\"15%\">FECHA RECIBIDA/<br>USUARIO</td>\n";
			$this->salida .="       <td width=\"5%\">&nbsp;</td>";
			$this->salida .= "		</tr>\n";
			for($i=0;$i<sizeof($reservasPaciente);$i++){
			if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
        $this->salida .="    <tr class = \"$estilo\">";
				if($reservasPaciente[$i]['numero_alicuota']=='0'){$alicuota='PRINCIPAL';}else{$alicuota=$reservasPaciente[$i]['numero_alicuota'];}
				if($reservasPaciente[$i]['solicitud_reserva_sangre_id']){
				$this->salida .="    <td>".$reservasPaciente[$i]['solicitud_reserva_sangre_id']."</td>";
				}else{
        $this->salida .="    <td>SIN RESERVA</td>";
				}
        $this->salida .="    <td>".$reservasPaciente[$i]['bolsa_id']."<BR>- ".$alicuota." -</td>";
				$this->salida .="    <td>".$reservasPaciente[$i]['sello_calidad']."</td>";
				(list($ano,$mes,$dia)=explode('-',$reservasPaciente[$i]['fecha_vencimiento']));
				$FechaConver=mktime(0,0,0,$mes,$dia,$ano);
				$this->salida .="    <td>".strftime("%b %d de %Y",$FechaConver)."</td>";
				$this->salida .="    <td>".$reservasPaciente[$i]['componente']."</td>";
        $this->salida .="    <td>".$reservasPaciente[$i]['grupo_sanguineo']."</td>";
				$this->salida .="    <td>".$reservasPaciente[$i]['rh']."</td>";
				if($reservasPaciente[$i]['despachada']=='1'){
        $this->salida .="    <td align=\"center\"><img title=\"Despachada\" border=\"0\" src=\"".GetThemePath()."/images/endturn.png\"></td>";
				}else{
        $this->salida .="    <td align=\"center\"><img title=\"Sin Despacho\" border=\"0\" src=\"".GetThemePath()."/images/delete.png\"></td>";
				}
        if($reservasPaciente[$i]['recibida_fecha'] && $reservasPaciente[$i]['recibida_usuaio']){
				(list($fecha,$hora)=explode(' ',$reservasPaciente[$i]['recibida_fecha']));
				(list($ano,$mes,$dia)=explode('-',$fecha));
				(list($hh,$mm)=explode(':',$hora));
				$FechaConver=mktime($hh,$mm,0,$mes,$dia,$ano);
				$this->salida .="    <td align=\"center\">".strftime("%b %d de %Y %H:%M",$FechaConver)."<BR>".$reservasPaciente[$i]['recibida_usuaio']."</td>";
				$fecha=explode('-',$reservasPaciente[$i]['fecha_vencimiento']);
				$fechaVence=$fecha[2].'-'.$fecha[1].'-'.$fecha[0];
				$tipoSanguineo=$reservasPaciente[$i]['grupo_sanguineo'].'.-.'.$reservasPaciente[$i]['rh'];
				$action=ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmTransfusiones',array("datos_estacion"=>$datos_estacion,"estacion"=>$estacion,'numSello'=>$reservasPaciente[$i]['sello_calidad'],'cantBolsas'=>$reservasPaciente[$i]['bolsa_id'],'IngresoBolsaId'=>$reservasPaciente[$i]['ingreso_bolsa_id'],'numeroAlicuota'=>$reservasPaciente[$i]['numero_alicuota'],
				'componente'=>$reservasPaciente[$i]['tipo_componente_id'],'fechaVencimiento'=>$fechaVence,'tipoSanguineo'=>$tipoSanguineo,"origen"=>1,'numeroReserva'=>$reservasPaciente[$i]['solicitud_reserva_sangre_id']));
				$this->salida .="    <td align=\"center\"><a href=\"$action\"><img title=\"Seleccionar Bolsa\" border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"></a></td>";
				}else{
        $this->salida .="    <td align=\"center\">SIN RECIBIR</td>";
				$action=ModuloGetURL('app','EstacionE_ControlPacientes','user','LlamaRegistroRecepcionBolsa',array("datos_estacion"=>$datos_estacion,"estacion"=>$estacion,"IngresoBolsaId"=>$reservasPaciente[$i]['ingreso_bolsa_id'],"numeroAlicuota"=>$reservasPaciente[$i]['numero_alicuota'],"BolsaId"=>$reservasPaciente[$i]['bolsa_id']));
				$this->salida .="    <td align=\"center\"><a href=\"$action\"><img title=\"Recibir Bolsa\" src=\"". GetThemePath() ."/images/entregabolsa.png\" border='0'></a></td>";
				}
        $this->salida .="    </tr>";
				$y++;
			}
			$this->salida .= "</table><BR>";
			$this->salida .= "</form>\n";
			return true;
		}
	}//fin ShowTransfusiones

	/*
		*		ShowTransfusiones
		*
		*		Muestra los registros de transfusiones que tiene el paciente y permite ingresar la fecha
		*		finalizacion de la trasnfusion y reacciones adversase que presente el paciente
		*
		*		@Lorena Aragon
		*		@access Private
		*		@param array datos del paciente
		*		@param array datos de la estacion
		*		@param array datos de la numero de registros
		*		@return bool
		*/

	function FormaRegistroRecepcionBolsa($datos_estacion,$estacion,$IngresoBolsaId,$numeroAlicuota,$BolsaId){

		$this->salida .= ThemeAbrirTabla('RECEPCION COMPONENTE SANGUINEO');
    $accion=ModuloGetURL('app','EstacionE_ControlPacientes','user','GuardarRegistroRecepcionBolsa',array("datos_estacion"=>$datos_estacion,"estacion"=>$estacion,"IngresoBolsaId"=>$IngresoBolsaId,"numeroAlicuota"=>$numeroAlicuota,"BolsaId"=>$BolsaId));
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
    $this->salida .= "  <table border=\"0\" width=\"50%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "  <tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "  </td><tr>";
		if($numeroAlicuota==0){$ali='PRINCIPAL';}else{$ali=$numeroAlicuota;	}
    $this->salida.="    <tr class=\"modulo_table_title\"><td align=\"center\">BOLSA No. $BolsaId <BR> ALICUOTA: $ali</td></tr>";
    $this->salida.="    <tr class = \"modulo_list_claro\"><td><label class=\"label\">OBSERVACIONES</label><BR>";
		$this->salida .= "  <textarea class =\"textarea\" rows =\"5\" cols =\"80\"  name=\"observaciones\"></textarea>";
		$this->salida.="    </td></tr>";
		$this->salida .= "  </table>";
    $this->salida .= "   <table border=\"0\" width=\"50%\" align=\"center\" class=\"normal_10\">";
    $this->salida.="     <tr><td align=\"center\">";
		$this->salida.="     <input type=\"submit\" name=\"guardarDatos\" value=\"GUARDAR\" class=\"input-submit\">";
		$this->salida.="     <input type=\"submit\" name=\"cancelarDatos\" value=\"VOLVER\" class=\"input-submit\">";
		$this->salida.="     </td></tr>";
    $this->salida.="     </table>";
    $this->salida.="     </form>";
		$this->salida .= themeCerrarTabla();
		return true;
	}

/*
		*		ShowTransfusiones
		*
		*		Muestra los registros de transfusiones que tiene el paciente y permite ingresar la fecha
		*		finalizacion de la trasnfusion y reacciones adversase que presente el paciente
		*
		*		@Author Rosa Maria Angel
		*		@access Private
		*		@param array datos del paciente
		*		@param array datos de la estacion
		*		@param array datos de la numero de registros
		*		@return bool
		*/
		function ShowTransfusiones($estacion,$datos_estacion,$contador)
		{

			$transfusionesPaciente = $this->GetTransfusiones($datos_estacion['ingreso']);
			if(!$transfusionesPaciente){
				return false;
			}
			elseif($transfusionesPaciente != "ShowMensaje")
			{
				if(empty($contador)){
					$contador = sizeof($transfusionesPaciente);
				}
        //$action = ModuloGetURL('app','EstacionE_ControlPacientes','user','InsertarFechaFinTransfusion',array("datos_estacion"=>$datos_estacion,"estacion"=>$estacion));
				//$this->salida .= "<form name='frmShowTransfusiones' action='".$action."' method='POST'><br>\n";
				$this->salida .= "<br><table align=\"center\" width=\"95%\" cellpadding=\"2\" cellspacing=\"1\" border=\"0\">\n";
				$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "			<td colspan=\"10\">BOLSAS TRANSFUNDIDAS</td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "			<td>FECHA</td>\n";
				$this->salida .= "			<td>BOLSA<br>- ALICUOTA -</td>\n";
				$this->salida .= "			<td># SELLO<BR>CALIDAD</td>\n";
				$this->salida .= "			<td>FECHA DE<br>VENCIMIENTO</td>\n";
				$this->salida .= "			<td>COMPONENTE</td>\n";
				$this->salida .= "			<td>G.S.</td>\n";
				$this->salida .= "			<td>RH</td>\n";
				$this->salida .= "			<td>FECHA FINAL<br>TRANSFUSION</td>\n";
				$this->salida .= "			<td>REACCIONES<BR>ADVERSAS</td>\n";
				$this->salida .= "			<td>USUARIO</td>\n";
				$this->salida .= "		</tr>\n";
				$cont=1;
        $indice=0;
				while ($cont <= sizeof($transfusionesPaciente) && $cont <= $contador)
				{

					list($fecha,$hora) = explode(" ",$transfusionesPaciente[$cont-1][fecha]);//substr(,0,10);
					$this->salida .= "		<tr ".$this->Lista($cont)."' align='center' valign='middle'>\n";
					if($fecha == date("Y-m-d")) {
						$fecha = "HOY $hora";
					}elseif($fecha == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y")))){
						$fecha = "AYER $hora";
					}else{
						(list($ano,$mes,$dia)=explode('-',$fecha));
						$FechaConver=mktime(0,0,0,$mes,$dia,$ano);
						$fecha = strftime("%b %d de %Y",$FechaConver);
					}
					$this->salida .= "			<td>".$fecha."</td>\n";
					if($transfusionesPaciente[$cont-1][numero_alicuota]==0){$alicuota='PRINCIPAL';}else{$alicuota=$transfusionesPaciente[$cont-1][numero_alicuota];	}
					$this->salida .= "			<td>".$transfusionesPaciente[$cont-1][numero_bolsas]."<br>- ".$alicuota." -</td>\n";
					$this->salida .= "			<td>".$transfusionesPaciente[$cont-1][numero_sello_calidad]."</td>\n";
					(list($fecha,$hora)=explode(' ',$transfusionesPaciente[$cont-1][fecha_vencimiento]));
					(list($ano,$mes,$dia)=explode('-',$fecha));
					$FechaConver=mktime(0,0,0,$mes,$dia,$ano);
					$this->salida .= "			<td>".strftime("%b %d de %Y",$FechaConver)."</td>\n";
					$this->salida .= "			<td>".$transfusionesPaciente[$cont-1][componente]."</td>\n";
					$this->salida .= "			<td>".$transfusionesPaciente[$cont-1][grupo_sanguineo]."</td>\n";
					$this->salida .= "			<td>".$transfusionesPaciente[$cont-1][rh]."</td>\n";
					$this->salida .= "			<td valign='middle'>\n";
					if(empty($transfusionesPaciente[$cont-1][fecha_final]))
					{
						$action = ModuloGetURL('app','EstacionE_ControlPacientes','user','InsertarFechaFinTransfusion',array("estacion"=>$estacion,"datos_estacion"=>$datos_estacion));
						$this->salida .= "			<form name=\"FormaFechaFin$indice\" action=\"$action\" method='post'>\n";
						$this->salida .= "				<input type=\"text\" class=\"input-text\" name=\"fechaFin$indice\" value=\"".$_REQUEST['fechaFin'.$indice]."\" size=\"10\" maxlength=\"10\" readonly=\"yes\">".ReturnOpenCalendario('FormaFechaFin'.$indice,'fechaFin'.$indice,'-')."\n";
						$this->salida .= "				<select name=\"Horas$indice\" class='select'>\n";
						for($i=0; $i<24; $i++)
						{
							$hora = date("H", mktime($i,0,0,date("m"),date("d"),date("Y")));
							if($hora == date("H")){
								$selected = 'selected="yes"';
							}
							else { $selected = ""; }
							$this->salida .= "				<option value='$hora' $selected>$hora</option>\n";
						}
						$this->salida .= "				</select>\n";
						$this->salida .= "				<select name=\"Minutos$indice\" class='select'>\n";
						for($i=0; $i<60; $i++)
						{
							$min = date("i",mktime(date("H"),$i,date("s"),date("m"),date("d"),date("Y")));
							if(date("i") == $min){
								$selected = 'selected="yes"';
							}
							else { $selected = ""; }
							$this->salida .= "				<option value='".$min.":".date("s")."' $selected>$min</option>\n";
						}
						$this->salida .= "				</select>\n";
            $this->salida .= "				<input type='hidden' name='indice' value='".$indice."'>\n";
						$this->salida .= "				<input type='hidden' name=\"ingreso$indice\" value=\"".$datos_estacion['ingreso']."\">\n";
						$this->salida .= "				<input type='hidden' name=\"fechaInicio$indice\" value=\"".$transfusionesPaciente[$cont-1][fecha]."\">\n";
						$this->salida .= "				<input type='image' name='submit' src='".GetThemePath()."/images/EstacionEnfermeria/guarda.png' border=0 alt='GUARDAR'>\n";//<input type='submit' name='submit' value='s'>
						$this->salida .= "			</form>\n";
					}
					else{
					  (list($fecha,$hora)=explode(' ',$transfusionesPaciente[$cont-1][fecha_final]));
						(list($ano,$mes,$dia)=explode('-',$fecha));
						(list($hh,$mm)=explode(':',$hora));
						$FechaConver=mktime($hh,$mm,0,$mes,$dia,$ano);
						$this->salida .=" ".strftime("%b %d de %Y %H:%M",$FechaConver)."\n";
					}
					$this->salida .= "			</td>\n";
					$this->salida .= "			<td>\n";
					//if(empty($transfusionesPaciente[$cont-1][reaccion_adversa])){
						$href = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmInsertarReaccionAdversa',array("ingreso"=>$datos_estacion['ingreso'],"datos"=>$transfusionesPaciente[$cont-1],"estacion"=>$estacion,"datos_estacion"=>$datos_estacion));
						$this->salida .= "			<a href=\"".$href."\">INSERTAR</a>\n";
					//}
					/*else{
						$this->salida .= "			".$transfusionesPaciente[$cont-1][reaccion_adversa]."\n";
					}*/
					$this->salida .= "			</td>\n";

					$this->salida .= "			<td>\n";
					//aqui colocamos lo del usuario...si es el mismo usuario y no ha pasado
					//el dia ..colocamos el link de modificar!
					$nom=$this->GetDatosUsuarioSistema($transfusionesPaciente[$cont-1][usuario]);
					$this->salida .= "			".$nom[0][usuario]."\n";
					$this->salida .= "			</td>\n";


					$this->salida .= "		</tr>\n";
					$cont++;
					$indice++;
				}
				$this->salida .= "		</tr>\n";
				$this->salida .= "	</table>\n\n";
        //$this->salida .= "</form>\n";
				if ($contador<sizeof($transfusionesPaciente)) {//FrmTransfusiones($estacion,$datos_estacion)
					$href = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmTransfusiones',array("estacion"=>$estacion,"datos_estacion"=>$datos_estacion,"cantidad"=>1));
					$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Ver M&aacute;s</a><br>";
				}
				return true;
			}
		}//fin ShowTransfusiones

	/*
		*		FormInserarReaccionAdversa
		*
		*		Formulario para el ingreso de la reaccion adversa del paciente en una treasnfusion x
		*
		*		@Author Rosa Maria Angel
		*		@access Private
		*		@param integer ingrso del paciente
		*		@param array datos de la transfusion
		*		@param array datos del paciente
		*		@param array datos de la estacion
		*		@return bool
		*/
		function FrmInsertarReaccionAdversa($ingreso,$datos,$estacion,$datos_estacion)
		{
			$GS = $this->GetGrupoSanguineoPaciente($estacion,$datos_estacion);
			if(!$GS){
				return false;
			}
			$action = ModuloGetURL('app','EstacionE_ControlPacientes','user','InsertarReaccionAdversa',array("ingreso"=>$ingreso,"datos"=>$datos,"estacion"=>$estacion,"datos_estacion"=>$datos_estacion));
			$this->salida .= "<form name=\"InsertarReaccionAdversa\" method=\"POST\" action=\"$action\"><br>\n";
			$this->salida .= ThemeAbrirTabla("EDICI&Oacute;N DE LA PLANTILLA")."<br>";
			$this->salida .= "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_title\">\n";
			$this->salida .= "		<tr class=\"modulo_table_title\">\n";
			$this->salida .= "			<td>PACIENTE</td>\n";
			$this->salida .= "			<td>ID</td>\n";
			$this->salida .= "			<td>HABITACION</td>\n";
			$this->salida .= "			<td>CAMA</td>\n";
			$this->salida .= "			<td>GRUPO SANGUINEO</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
			$this->salida .= "			<td>".$datos_estacion['NombrePaciente']."</td>\n";
			$this->salida .= "			<td>".$datos_estacion[tipo_id_paciente]." ".$datos_estacion[paciente_id]."</td>\n";
			$this->salida .= "			<td>".$datos_estacion[pieza]."</td>\n";
			$this->salida .= "			<td>".$datos_estacion[cama]."</td>\n";
			$this->salida .= "			<td>\n";
			if($GS === "ShowMensaje"){
				$href = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmIngresarHemoclasificacionPaciente',array("paciente_id"=>$datos_paciente[paciente_id],"tipo_id_paciente"=>$datos_paciente[tipo_id_paciente],"datos_estacion"=>$datos_estacion,"estacion"=>$estacion));
				$this->salida .= "			<a href=\"$href\">ingresar G.S. y R.H.</a>\n";
			}
			else{
				$this->salida .= "			".$GS['grupo_sanguineo']." ".$GS['rh']."</td>\n";
			}

			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table><br><br>\n";
			$this->salida .= "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"normal_10\">\n";
			$this->salida .= $this->SetStyle("MensajeError",1);
			$this->salida .= "	</table>\n";
			$this->salida .= "<br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\">FECHA</td>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\">BOLSAS</td>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\"># SELLO CALIDAD</td>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\">FECHA VENCIMIENTO</td>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\">REACCION ADVERSA</td>\n";
			$this->salida .= "		</tr>\n";
			list($fecha,$hora) = explode(" ",$datos[fecha]);//substr(,0,10);
			$this->salida .= "		<tr align='center'>\n";
			if($fecha == date("Y-m-d")) {
				$fecha = "HOY $hora";
			}
			elseif($fecha == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y")))){
				$fecha = "AYER $hora";
			}
			else {
				$fecha = $fecha;
			}
			$this->salida .= "			<td class=\"modulo_list_claro\">".$datos[fecha]."</td>\n";
			$this->salida .= "			<td class=\"modulo_list_claro\">".$datos[numero_bolsas]."</td>\n";
			$this->salida .= "			<td class=\"modulo_list_claro\">".$datos[numero_sello_calidad]."</td>\n";
			$this->salida .= "			<td class=\"modulo_list_claro\">".$datos[fecha_vencimiento]."</td>\n";
			$this->salida .= "			<td class=\"modulo_list_claro\">";

			$reaccion=$this->GetNotasReaccionAdversasPaciente($ingreso,$datos[fecha]);
			if($reaccion != 'ShowMensage')
			{
				//$this->salida .= "		<td><img src=\"".GetThemePath()."/images/flecha_der.gif\"  width='10' height='10'>&nbsp;<label class='label_mark'>".$estaciones[$i][1]."</label></td>\n";

				$this->salida .= "<br><table align=\"center\" width=\"90%\"  bordercolor='gray' border=\"1\">\n";
				$this->salida .= "		<tr class=\"modulo_table_title\">\n";
				$this->salida .= "			<td></td>\n";
				$this->salida .= "			<td>HORA</td>\n";
				$this->salida .= "			<td>OBSERVACION</td>\n";
				$this->salida .= "			<td>USUARIO</td>\n";
				$this->salida .= "		</tr>\n";
				for($j=0;$j<sizeof($reaccion);$j++)
				{
					if($j % 2)  $estilo = "modulo_list_oscuro";  else $estilo = "modulo_list_claro";
					$this->salida .= "		<tr class='$estilo'>\n";

					if($reaccion[$j][sw_reaccion]==1)
					{
						$img="activo.gif";
					}
					elseif($reaccion[$j][sw_reaccion]==2)
					{
						$img='inactivo.gif';
					}
					elseif($reaccion[$j][sw_reaccion]==3)
					{
						$img='inactivoip.gif';
					}
					$this->salida .= "			<td width=\"5%\" ><img src=\"".GetThemePath()."/images/$img\"  width='12' height='12'></td>\n";
					unset($img);
					//$fech=explode(" ",$reaccion[$j][fecha_registro]);
				//	$hora=explode(".",$fech[1]);
					$hora=explode(".",$reaccion[$j][fecha_registro]);
					$this->salida .= "			<td width=\"20%\"><label class='label_mark'>".$hora[0]."</label></td>\n";
					$this->salida .= "			<td width=\"70%\">".$reaccion[$j][observacion]."</td>\n";
					$this->salida .= "			<td width=\"10%\">\n";
					$nom=$this->GetDatosUsuarioSistema($reaccion[$j][usuario_id]);
					$this->salida .= "			".$nom[0][usuario]."\n";
					$this->salida .= "			</td>\n";
					$this->salida .= "		</tr>\n";
				}
				$this->salida .= "	</table><br>\n";

			//	$this->salida .= "			<textarea name='reaccionAdversa' class='textarea' cols='85' rows='5'></textarea>";

			}

					$this->salida .= "<textarea name='reaccionAdversa' class='textarea' cols='100' rows='5'></textarea>";
					$this->salida .= "			</td>\n";



			$this->salida .= "		</tr>\n";
			$this->salida .= "<br></table>\n";
			$this->salida .= "<table align=\"right\" width=\"30%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\">\n";
			$this->salida.="<tr class=\"modulo_table_title\" >";

			$this->salida.="<td colspan='6'>SELECCIONE REACCION</td></tr>";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class='modulo_list_claro'>\n";
			//$this->salida.="<td colspan='4'></td>";
			$this->salida.="<td>Positivo <img src=\"".GetThemePath()."/images/activo.gif\"  width='10' height='10'>&nbsp;</td><td width=2%><input type=radio name=sel value=1></td>";
			$this->salida.="<td>&nbsp;&nbsp;Neutral<img src=\"".GetThemePath()."/images/inactivoip.gif\"  width='10' height='10'>&nbsp;</td><td width=2%><input type=radio  name=sel value=3></td>";
			$this->salida.="<td>&nbsp;&nbsp;Negativo<img src=\"".GetThemePath()."/images/inactivo.gif\"  width='10' height='10'>&nbsp;</td><td width=2%><input type=radio  name=sel value=2></td>";
			$this->salida.="</tr>";
			$this->salida.="</table><br>";
			$this->salida .= "<div class='normal_10' align='center'><br><input type='submit' class='input-submit' name='Save' value='GUARDAR'>";
			$this->salida .= "</form>\n";
			$href = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmTransfusiones',array("datos_estacion"=>$datos_estacion,"estacion"=>$estacion));
			$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al listado de transfusiones</a><br>\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}//


}//fin de la clase
?>
