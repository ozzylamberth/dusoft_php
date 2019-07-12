<?
/**
*		class app_EstacionEnfermeria_userclasses_HTML
*
*		Clase que maneja todas las funciones de vistas y consultas a la base de datos
*		relacionadas a la estaci&oacute;n de Enfermer&iacute;a
*		ubicacion => app_modules/EstacionEnfermeria/userclasses/app_EstacionEnfermeria_userclasses_HTML.php
*		fecha creaci&oacute;n => 04/05/2004 10:35 am
*
*		@Author jairo Duvan Diaz Martinez
*		@version =>
*		@package SIIS
*/
class app_EstacionEnfermeriaPlantilla_userclasses_HTML extends app_EstacionEnfermeriaPlantilla_user
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
	function app_EEstacionEnfermeriaPlantilla_userclasses_HTML()
	{
	  $this->app_EstacionEnfermeriaPlantilla_user(); //Constructor del padre 'modulo'
		$this->salida = "";
		return true;
	}


	/**
	*		Menu
	*
	*		@Author Jairo Duvan Diaz Martinez
	*		@access Private
	*		@param array
	*		@return string
	*/
	function Menu($datos)
	{
     	$refresh = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array('estacion'=>$datos));
			$this->salida="<script language=javascript>\n";
			$this->salida.="function load_page()\n";
			$this->salida.="{\n";
			$this->salida.="location.reload();\n";
			$this->salida.="}\n";
			$this->salida.="</script>\n";


			$this->salida.="<body onload=compt=setTimeout('load_page();',300000)>\n";
			unset($_SESSION['ESTACION']['VECTOR_SOL']);//var de session que tiene el vector de solicitud de medicamentos.
			unset($_SESSION['ESTACION']['VECTOR_DESP']);//var de session que tiene el vector de despacho de medicamentos.
			unset($_SESSION['ESTACION']['VECTOR_DEV']); //var de session q tiene el vector de devoluciones de medicamentos.
			//unset($_SESSION['HISTORIACLINICA']['DATOS']['ESTACION']);//destruir los datos de la estacion q estan en session..

			if(!$_SESSION['HISTORIACLINICA']['DATOS']['ESTACION'])
			{
				$_SESSION['PLAN_ESTACION_ENFERMERIA']['NOM']=$datos['descripcion5'];
				$_SESSION['PLAN_ESTACION_ENFERMERIA']['EMP']=$datos['descripcion1'];
				$_SESSION['ESTACION_ENFERMERIA']['hc_modulo_enfermera']=$datos['hc_modulo_enfermera'];
			}
			else
			{
					$datos=$_SESSION['HISTORIACLINICA']['DATOS']['ESTACION'];
			}
				$this->salida .= ThemeAbrirTabla("MEN&Uacute; ESTACI&Oacute;N DE ENFERMERIA - [ ".$datos['descripcion5']." ]");
				$this->salida .= "<center>\n";
				$this->salida .= "				<table class='modulo_table_title' border='0' width='100%'>\n";
				$this->salida .= "					<tr class='modulo_table_title'>\n";
				$this->salida .= "						<td>Empresa</td>\n";
				$this->salida .= "						<td>Centro Utilidad</td>\n";
				$this->salida .= "						<td>Unidad Funcional</td>\n";
				$this->salida .= "						<td>Departamento</td>\n";
				$this->salida .= "					</tr>\n";
				$this->salida .= "					<tr class='modulo_list_oscuro'>\n";
				$this->salida .= "						<td>".$datos['descripcion1']."</td>\n";
				$this->salida .= "						<td>".$datos['descripcion2']."</td>\n";
				$this->salida .= "						<td>".$datos['descripcion3']."</td>\n";
				$this->salida .= "						<td>".$datos['descripcion4']."</td>\n";
				$this->salida .= "					</tr>\n";
				$this->salida .= "				</table>\n";




		$this->salida .= "<table align='center' border='0' width='100%'>\n";
		$this->salida .= "<tr><td>\n";
		$this->ListRevisionPorSistemas($datos);
		$this->salida .= "</td></tr>\n";
		$this->salida .= "</table>\n";

		$refresh = ModuloGetURL('app','EstacionEnfermeriaPlantilla','user','CallMenu',array('estacion'=>$datos));
		$this->salida .= "<div class='normal_10' align='center'><br>\n";
		$this->salida .= "	<a href='$refresh'><< REFRESCAR >></a><br>\n";
		$this->salida .= "\n";
		unset($_SESSION['ESTACION_CONTROL']['INGRESO']);//FrmFrecuenciaControlesP 1017 (EstacionE_ControlPacientes).
		unset($_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']); //unseteamos esta var de session
		//q se activa para ver los controles del paciente y los apoyos desde la atencion de urgencias.
		$this->salida .= themeCerrarTabla();
		return true;
	}



	/*funcion que debe estar en el mod estacione_controlpaciente*/
		/*
		*
		*
		*		@Author Arley Velasquez Castillo
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

			//AQUI ES PARA COMUNICARSE CON LA CENTRA DE IMPRESION DE ORDENES DE DAR.
			$_SESSION['CENTRALHOSP']['RETORNO']['modulo']='EstacionEnfermeriaPlantilla';
			$_SESSION['CENTRALHOSP']['RETORNO']['metodo']='CallMenu';
			$_SESSION['CENTRALHOSP']['RETORNO']['tipo']='user';
			$_SESSION['CENTRALHOSP']['RETORNO']['contenedor']='app';
			$_SESSION['CENTRALHOSP']['RETORNO']['argumentos']=array('estacion'=>$estacion);
			unset($_SESSION['ESTACION']['VECT']);
			//$datoscenso = $this->CallMetodoExterno('app','Censo','user','GetCensoTipo1',array('estacion'=>$estacion['estacion_id']));
				$datoscenso=$this->GetPacientesControles($estacion['estacion_id']);

			if($datoscenso=== "ShowMensaje")
			{
				$datoscenso='';//esto es para que entre al if
			}
			if(!empty($datoscenso))
			{
				//$this->salida .= ThemeAbrirTabla("NOTAS DE ENFERMERIA - [ ".$estacion['descripcion5']." ]");
				$w=$x=0;
				foreach($datoscenso as $key => $value)
				{//echo "<br>".$key;//
					if($key == "hospitalizacion")
					{
						$this->salida .= "<br><table align=\"center\" width=\"100%\"  border=\"0\" >\n";
						$this->salida .= "	<tr class=\"modulo_table_title\"><td colspan='9' height='30'>PACIENTES EN HOSPITALIZACI&Oacute;N</td></tr>\n";
						$this->salida .= "	<tr class=\"modulo_table_title\">\n";
						$this->salida .= "		<td></td>\n";
						$this->salida .= "		<td><sub>HAB.</sub></td>\n";
						$this->salida .= "		<td><sub>CAMA</sub></td>\n";
						$this->salida .= "		<td><sub>TIEMPO<BR>HOSP.</sub></td>\n";
						$this->salida .= "		<td><sub>PACIENTE DE ESTACIÓN</sub></td>\n";
						$this->salida .= "		<td><sub>MED.<BR>PACIENTES</sub></td>\n";
						$this->salida .= "		<td><sub>CTRL<BR>PROGRAMADOS</sub></td>\n";
						$this->salida .= "		<td><sub>PROGRAMA.<BR>APOYO</sub></td>\n";
						$this->salida .= "		<td><sub>ORDENES<BR>SERVICIOS</sub></td>\n";
						$this->salida .= "	</tr>\n";

						//mostramos los pacientes pendientes por ingresar .. si hay
						if($w==0)
						{
							$pacientes = $this->GetPacientesPendientesXHospitalizar($estacion);
							$this->Pacientes_X_Ingresar($estacion,$reporte,$pacientes);
							$w=1;
						}


						foreach($value as $A => $B)
						{

						  				$info=$this->RevisarSi_Es_Egresado($B[ingreso_dpto_id]);
																		//print_r($B);
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
											$linkVerDatos = ModuloGetURL('app','EstacionE_Pacientes','user','CallMostrarDatosIngreso',array("ingresoID"=>$B['ingreso'],"retorno"=>"CallMenu","modulito"=>'EstacionEnfermeriaPlantilla',"datos_estacion"=>$estacion));

											$this->salida .= "	<td><a href='$linkVerDatos'>".$B[primer_nombre]." ".$B[segundo_nombre]." ".$B[primer_apellido]." ".$B[segundo_apellido]."</a></td>\n";



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
											//echo "<br>".$conteop.$B['ingreso'];
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


											$this->salida .= "</tr>\n";

					}//fin for


						$this->salida .= "</table><br>\n";



					//$this->salida .= "<div class=\"label\" align=\"center\">TOTAL PACIENTES HOSPITALIZACION = ".sizeof($datoscenso[hospitalizacion])."<br>\n";
					}//fin formato hospitalizacio
				}//fin foreach


	unset($ItemBusqueda);

			}
			else //es por que no hay hospitalizados pero todavia podemos revisar los pendientes x ingresar.
			{
						$pacientes = $this->GetPacientesPendientesXHospitalizar($estacion);

						if(is_array($pacientes) OR is_array($pac_consulta))
						{
								$this->salida .= "<br><table align=\"center\" width=\"100%\"  border=\"0\" >\n";
								$this->salida .= "	<tr class=\"modulo_table_title\"><td colspan='9' height='30'>PACIENTES EN HOSPITALIZACI&Oacute;N</td></tr>\n";
								$this->salida .= "	<tr class=\"modulo_table_title\">\n";
								$this->salida .= "		<td></td>\n";
								$this->salida .= "		<td><sub>HAB.</sub></td>\n";
								$this->salida .= "		<td><sub>CAMA</sub></td>\n";
								$this->salida .= "		<td><sub>TIEMPO<BR>HOSP.</sub></td>\n";
								$this->salida .= "		<td><sub>PACIENTE DE ESTACIÓN</sub></td>\n";
								$this->salida .= "		<td><sub>MED.<BR>PACIENTES</sub></td>\n";
								$this->salida .= "		<td><sub>CTRL<BR>PROGRAMADOS</sub></td>\n";
								$this->salida .= "		<td><sub>PROGRAMA.<BR>APOYO</sub></td>\n";
								$this->salida .= "		<td><sub>ORDENES<BR>SERVICIOS</sub></td>\n";
								$this->salida .= "	</tr>\n";

								//mostramos los pacientes pendientes por ingresar .. si hay
								if(is_array($pacientes))
								{$this->Pacientes_X_Ingresar($estacion,$reporte,$pacientes);}

								$this->salida .= "</table><br>\n";

					}
					else
					{
								$mensaje = "LA ESTACI&Oacute;N [ ".$estacion['descripcion5']." ] NO CUENTA CON PACIENTES.";
								$titulo = "ALERTA DEL SISTEMA";
								$boton = "SELECCIONAR ESTACION";
								$controles=$this->GetControles($datoscenso['hospitalizacion'][$i]['ingreso']);
								//$href=ModuloGetURL('app','EstacionEnfermeria','user','');
								$this->FormaMensaje($mensaje,$titulo,$href,$boton);
								return true;
					}
			}
			return true;
		}
/*funcion que debe estar en el mod estacione_controlpaciente*/







/*
* funcion que revisa los pacientes que estan por ingresar
*/
function Pacientes_X_Ingresar($estacion,&$reporte,$pacientes)
{
			if(is_array($pacientes))
		{
			for($i=0; $i<sizeof($pacientes); $i++)
			{
				$viaIngreso = $this->GetViaIngresoPaciente($pacientes[$i][4]);//le envio el ingreso
				$backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');

				if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
				$this->salida .= "<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>\n";
				$linkVerDatos = ModuloGetURL('app','EstacionE_Pacientes','user','CallMostrarDatosIngreso',array("ingresoID"=>$pacientes[$i][4],"retorno"=>"CallMenu","modulito"=>'EstacionEnfermeriaPlantilla',"datos_estacion"=>$estacion));


				$linker=ModuloGetURL('app','EstacionE_Pacientes','user','CallListPacientesPorIngresar',array("datos_estacion"=>$estacion,"paciente_id"=>$pacientes[$i][2],"tipo_id_paciente"=>$pacientes[$i][3]));

				$this->salida .= "	<td  align=\"center\"><img src=\"". GetThemePath() ."/images/ingresar.png\" border='0'></td>\n";
				$this->salida .= "	<td  colspan='3' align=\"center\"><label class='label_mark'>Pendiente Asignación de cama</label></td>\n";
				//$this->salida .= "	<td  align=\"center\">info</td>\n";
				//$this->salida .= "	<td  align=\"center\">info</td>\n";
				//$this->salida .= "	<td  align=\"center\">".$viaIngreso[via_ingreso_nombre]."&nbsp;</td>\n";
				//$this->salida .= "	<td align=\"center\">".$pacientes[$i][9]."&nbsp;</td>\n";
				$this->salida .= "	<td nowrap><a href=\"$linkVerDatos\">".$pacientes[$i][0]." ".$pacientes[$i][1]."</a></td>\n";



				$nombre=$pacientes[$i][0]." ".$pacientes[$i][1];
				//SIGNOS VITALES


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
				//echo "<br>".$conteop.$B['ingreso'];
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

				//$this->salida .= "	<td align=\"center\">".$pacientes[$i][3]." ".$pacientes[$i][2]."</td>\n";
					$this->salida .= "</tr>\n";
			}

		}//pacientes por ingresar
return true;
}

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
		$this->salida .= ThemeAbrirTabla($titulo,"50%")."<br>";
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


}//fin class
?>
