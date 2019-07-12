<?php

class app_QX_notas_operatorias_userclasses_HTML extends app_QX_notas_operatorias_user
{

	function app_QX_notas_operatorias_user_HTML()
	{
	  $this->app_QX_notas_operatorias_user(); //Constructor del padre 'modulo'
		$this->salida='';
		return true;
	}

/**
* Function que muestra al usuario la diferentes bodegas, la empresa y el centro de utilidad
* al que pertenecen y en las que el usuario tiene permiso de trabajar
* @return boolean
*/
	function FrmLogueoCirugias(){
    $Empresas=$this->LogueoCirugias();
		if(sizeof($Empresas)>0){
			$url[0]='app';
			$url[1]='QX_notas_operatorias';
			$url[2]='user';
			$url[3]='consultaLogueo';
			$url[4]='datos_query';
			$this->salida .= gui_theme_menu_acceso("NOTAS OPERATORIAS",$Empresas[0],$Empresas[1],$url);
		}else{
      $mensaje = "EL USUARIO NO TIENE PERMISOS PARA ACCESAR AL MENU PRESUPUESTO DE CIRUGIAS.";
			$titulo = "INVENTARIO GENERAL";
			$boton = "";//REGRESAR
			$accion="";
			$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
			return true;
		}
		return true;
	}

	function Encabezado(){
    $this->salida .= "    <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr><td class=\"modulo_table_list_title\" align=\"center\"><b>EMPRESA</b></td>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" align=\"center\"><b>CENTRO DE UTILIDAD</b></td>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" align=\"center\"><b>DEPARTAMENTO</b></td></tr>";
		$this->salida .= "      <tr><td class=\"modulo_list_claro\" align=\"center\"><b>".$_SESSION['LocalCirugias']['NombreEmp']."</b></td>";
    $this->salida .= "      <td class=\"modulo_list_claro\" align=\"center\"><b>".$_SESSION['LocalCirugias']['NombreCU']."</b></td>";
		$this->salida .= "      <td class=\"modulo_list_claro\" align=\"center\"><b>".$_SESSION['LocalCirugias']['NombreDpto']."</b></td></tr>";
    $this->salida .= "		</table><BR>";
		return true;
	}

	function listadoCirugiasNotas(){
    $this->salida .= ThemeAbrirTabla('LISTADO DE CIRUGIAS');
    $this->Encabezado();
		//$accion=ModuloGetURL('app','QX_notas_operatorias','user','BusquedaPacientePlan');
		$this->salida .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "<table class=\"normal_10\" border=\"0\" width=\"70%\" align=\"center\">";
		$cirugias=$this->CirugiasporNotas();
		if($cirugias){
			$this->salida .= "<tr><td colspan=\"3\" class=\"modulo_table_list_title\" align=\"center\">CIRUGIAS CUMPLIDAS</td></tr>";
			$this->salida .= "<tr class=\"modulo_table_list_title\">";
			$this->salida .= "<td>ID PACIENTE</td>";
			$this->salida .= "<td>NOMBRE PACIENTE</td>";
			$this->salida .= "<td>&nbsp;</td>";
			$this->salida .= "</tr>";
			$y=0;
			for($i=0;$i<sizeof($cirugias);$i++){
			  if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
			  $this->salida .= "<tr class=\"$estilo\">";
				$this->salida .= "<td>".$cirugias[$i]['tipo_tercero_id']." ".$cirugias[$i]['tercero_id']."</td>";
				$this->salida .= "<td>".$cirugias[$i]['nombre']."</td>";
        $accion=ModuloHCGetURL(0,'','3847','QX_notas_operatorias','NotasOperatorias');
				$this->salida .= "<td><a href=\"$accion\" class=\"link\"><b>MODIFICAR HISTORIA CLINICA</b></a></td>";
				$this->salida .= "</tr>";
				$y++;
			}
		}else{
        $this->salida .= "<tr><td align=\"center\" class=\"label_error\">NO TIENE CIRUGIAS PENDIENTES</td></tr>";
		}
    $cirugiasSinCumplir=$this->CirugiasSincumplir();
		if($cirugiasSinCumplir){
			$this->salida .= "<tr><td colspan=\"3\" class=\"modulo_table_list_title\" align=\"center\">CIRUGIAS SIN CUMPLIR</td></tr>";
			$this->salida .= "<tr class=\"modulo_table_list_title\">";
			$this->salida .= "<td>ID PACIENTE</td>";
			$this->salida .= "<td>NOMBRE PACIENTE</td>";
			$this->salida .= "<td>&nbsp;</td>";
			$this->salida .= "</tr>";
			$y=0;
			for($i=0;$i<sizeof($cirugiasSinCumplir);$i++){
			  if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
			  $this->salida .= "<tr class=\"$estilo\">";
				$this->salida .= "<td>".$cirugiasSinCumplir[$i]['tipo_tercero_id']." ".$cirugias[$i]['tercero_id']."</td>";
				$this->salida .= "<td>".$cirugiasSinCumplir[$i]['nombre']."</td>";
				$this->salida .= "<td><a href=\"$accion\" class=\"link\"><b>CONSULTAR HISTORIA CLINICA</b></a></td>";
				$this->salida .= "</tr>";
				$y++;
			}
		}
    $this->salida .= "</table>";
    $this->salida .= "</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}















	/*function PantallaInicial()
	{
		$this->ReturnMetodoExterno('app', 'EstacionEnfermeria', 'user', '',array('jaime_modulo'=>'AtencionInterconsulta','jaime_metodo'=>'ListadoPaciente'));
		return true;
	}

	function ListadoPacienteUrgencias()
	{
		$modulo=$this->TipoModulo();
		if($modulo==false)
		{
			return false;
		}
		$DatosEstacion=$this->BuscarPacientesEstacion();
		$prueba=$this->ReconocerProfesional();
		if($prueba==1 or $prueba==2)
		{
			if($DatosEstacion)
			{
    		$this->SetJavaScripts('DatosPaciente');
				$this->salida .= "<BR>";
				$this->salida .= '<table width="80%" align="center" border="0" class="modulo_table">';
				$this->salida .= '<tr align="center" class="modulo_table_title">';
				$this->salida .= '<td>';
				$this->salida .= "Paciente en Urgencias";
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
				$this->salida .= '<tr align="center">';
				$this->salida .= '<td align="center">';
				$this->salida .= '<table width="100%" align="center" class="modulo_table_list" border="1">';
				$this->salida .= '<tr align="center" class="modulo_table_list_title">';
				$this->salida .= '<td align="center" width="35%">';
				$this->salida .= "Pacientes";
				$this->salida .= "</td>";
				$this->salida .= '<td align="center">';
				$this->salida .= "Tiempo en Espera";
				$this->salida .= "</td>";
				$this->salida .= '<td align="center">';
				$this->salida .= "Fecha Evolucion";
				$this->salida .= "</td>";
				$this->salida .= '<td align="center">';
				$this->salida .= "Profesional";
				$this->salida .= "</td>";
				$this->salida .= '<td align="center">';
				$this->salida .= "Acción";
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
				$spy=0;
				foreach($DatosEstacion as $k=>$v)
				{
					foreach($v as $t=>$r)
					{
						foreach($r as $p=>$h)
						{
							$s=0;
							$prof=0;
							foreach($h as $i=>$j)
							{
								if($s==0)
								{
									if(!empty($j[0]))
									{
										$this->salida.='<tr align="center" class="'.$j[2].'">';
										$dato='<tr align="center" class="'.$j[2].'">';
									}
									else
									{
										if(empty($j[0]) or $j[0]==1)
										{
											if($spy==0)
											{
												$this->salida.='<tr align="center" class="modulo_list_claro">';
												$dato='<tr align="center" class="modulo_list_claro">';
												$spy=1;
											}
											else
											{
												$this->salida.='<tr align="center" class="modulo_list_oscuro">';
												$dato='<tr align="center" class="modulo_list_oscuro">';
												$spy=0;
											}
										}
									}
									$this->salida .= "<td>";
									$open=RetornarWinOpenDatosPaciente($t,$k,$p);
									$this->salida .=$open;
									$this->salida .= "</td>";
									$this->salida .= "<td>";
									if($j[0]==1)
									{
										$this->salida .="<label class=\"label_error\">";
									}
									$this->salida .=$j[1];
									if($j[0]==1)
									{
										$this->salida .="</label>";
									}
									$this->salida .= "</td>";
									$s=1;
								}
								$salida1 .= '<table width="100%" align="center" border="0">';
								$salida1.=$dato;
								$salida1 .= "<td>";
								$salida1 .=$j[5];
								$salida1 .= "</td>";
								$salida1 .= "</tr>";
								$salida1 .= '</table>';
								$salida2 .= '<table width="100%" align="center" border="0">';
								$salida2.=$dato;
								$salida2 .= "<td>";
								$salida2 .=$j[7];
								$salida2 .= "</td>";
								$salida2 .= "</tr>";
								$salida2 .= '</table>';
								$salida3 .= '<table width="100%" align="center" border="0">';
								$salida3.=$dato;
								$salida3 .= "<td>";
								if(empty($j[6]))
								{
									if($j[9]==='0')
									{
										$accion=ModuloGetURL('app','AtencionUrgenciasHospitalizacion','user','ClasificarTriage', array('tipo_id_paciente'=>$t, 'paciente_id'=>$k, 'plan_id'=>$j[10], 'triage_id'=>$j[11], 'punto_triage_id'=>$j[12], 'punto_admision_id'=>$j[13], 'sw_no_atender'=>$j[14], 'ingreso'=>$j[3], 'moduloh'=>$modulo, 'estacion_id'=>$j[8]));
									}
									else
									{
										$accion=ModuloHCGetURL(0,'',$j[15],$modulo,$modulo,array('estacion'=>$j[8]));
									}
									$salida3 .="<a href='$accion'>Atender</a>";
									$prof=1;
								}
								else
								{
									if($j[6]==$_SESSION['SYSTEM_USUARIO_ID'])
									{
										$accion=ModuloHCGetURL($j[4],'',0,$modulo,$modulo,array('estacion'=>$j[8]));
										$salida3 .="<a href='$accion'>Continuar Atencion</a>";
										$prof=1;
									}
									else
									{
										$salida3 .="Otro Profesional";
									}
								}
								$salida3 .= "</td>";
								$salida3 .= "</tr>";
								$salida3 .= '</table>';
							}
							$this->salida .= "<td valign='top'>";
							$this->salida.=$salida1;
							$this->salida .= "</td>";
							$this->salida .= "<td valign='top'>";
							$this->salida.=$salida2;
							$this->salida .= "</td>";
							$this->salida .= "<td valign='top'>";
							if($prof==0)
							{
								$salida3 .='<table width="100%" align="center" border="0">';
								$salida3.=$dato;
								$salida3 .= "<td>";
								$accion=ModuloHCGetURL(0,'',$j[15],$modulo,$modulo,array('estacion'=>$j[8]));
								$salida3.='<a href="'.$accion.'">Nueva Atencion</a>';
								$salida3 .= "</td>";
								$salida3 .= "</tr>";
								$salida3 .= '</table>';
							}
							$this->salida.=$salida3;
							$this->salida .= "</td>";
							$this->salida .= "</tr>";
							$salida='';
							$salida1='';
							$salida2='';
							$salida3='';
						}
					}
				}
				$this->salida .= '</table>';
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
				$this->salida .= '</table>';
			}
			else
			{
				$this->salida .= '<table width="80%" align="center">';
				$this->salida .= '<tr align="center">';
				$this->salida .= '<td align="center">';
				$this->salida .= '<label class="label_error">NO HAY PACIENTES PARA ATENDER EN URGENCIAS</label>';
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
				$this->salida .= "</table>";
			}
			$this->salida .= "<BR>";
		}
		return true;
	}

	function ContinuarHistoria()
	{
		$this->salida.="<script>\n";
		$this->salida.="location.href=\"".ModuloHCGetURL(0,'',$_SESSION['Atencion']['ingreso'],$_SESSION['Atencion']['modulo'],$_SESSION['Atencion']['modulo'],array('estacion'=>$_SESSION['Atencion']['estacion_id']))."\";\n";
		$this->salida.="</script>\n";
		return true;
	}

	function ListadoPacientesClasificar()
	{
		$pacientestriage=$this->PacientesClasificacionTriage();
		if($pacientestriage)
		{
			$this->SetJavaScripts('DatosPaciente');
			$this->salida .= '<table width="80%" align="center" border="0" class="modulo_table">';
			$this->salida .= '<tr align="center" class="modulo_table_title">';
			$this->salida .= '<td>';
			$this->salida .= "Paciente Para Clasificación Triage";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= '<tr align="center">';
			$this->salida .= '<td align="center">';
			$this->salida .= '<table width="100%" align="center" class="modulo_table_list" border="1">';
			$this->salida .= '<tr align="center" class="modulo_table_list_title">';
			$this->salida .= '<td align="center" width="70%">';
			$this->salida .= "Pacientes";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "Acción";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$spy=0;
			foreach($pacientestriage as $k=>$v)
			{
				foreach($v as $t=>$s)
				{
					if($spy==0)
					{
						$this->salida.='<tr align="center" class="modulo_list_claro">';
						$spy=1;
					}
					else
					{
						$this->salida.='<tr align="center" class="modulo_list_oscuro">';
						$spy=0;
					}
					$this->salida .= '<td align="center">';
					$this->salida.=RetornarWinOpenDatosPaciente($s['tipo_id_paciente'],$s['paciente_id'],$s['nombre']);
					$this->salida .= "</td>";
					$this->salida .= '<td align="center">';
					$accion=ModuloGetURL('app','AtencionUrgenciasHospitalizacion','user','ClasificarTriage',array('paciente_id'=>$s['paciente_id'], 'tipo_id_paciente'=>$s['tipo_id_paciente'], 'plan_id'=>$s['plan_id'], 'triage_id'=>$s['triage_id'], 'punto_triage_id'=>$s['punto_triage_id'], 'punto_admision_id'=>$s['punto_admision_id'], 'sw_no_atender'=>$s['sw_no_atender']));
					$this->salida.='<a href="'.$accion.'">Clasificar</a>';
					$this->salida .= "</td>";
					$this->salida .= "</tr>";
				}
			}
			$this->salida .= "</table>";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= '</table>';
		}
		return true;
	}


	function ListadoPaciente()
	{
		$_SESSION['HISTORIACLINICA']['RETORNO']['modulo']='AtencionInterconsulta';
		$_SESSION['HISTORIACLINICA']['RETORNO']['metodo']='ListadoPaciente';
		$_SESSION['HISTORIACLINICA']['RETORNO']['tipo']='user';
		$_SESSION['HISTORIACLINICA']['RETORNO']['contenedor']='app';
		if(!empty($_REQUEST['AtencionUrgencias']['empresa_id']))
		{
			$_SESSION['AtencionUrgencias']=$_REQUEST['AtencionUrgencias'];
			$_SESSION['url_origen']=$_REQUEST['url_origen'];
		}
		$prueba=$this->ReconocerProfesional();
		$hospitaesta1=$this->BuscarPacienteHosptalizados();
		$hospitaesta=$hospitaesta1[0];
		$DatosHospitalizacion=$hospitaesta1[1];
		$this->salida = ThemeAbrirTabla('PACIENTE PARA ATENDER');
		$this->salida .= "<BR>";
		$this->salida .= '<table width="80%" align="center" border="0" class="modulo_table_list">';
		$this->salida .= '<tr align="center" class="modulo_table_list_title">';
		$this->salida .= '<td align="center">';
		$this->salida .= "Empresa";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Departamento";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Estación de Enfermeria";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= '<tr class="modulo_list_oscuro">';
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['AtencionUrgencias']['descripcion1'];
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['AtencionUrgencias']['descripcion2'];
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['AtencionUrgencias']['descripcion3'];
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .= "<BR>";
		if($DatosHospitalizacion)
		{
			$this->SetJavaScripts('DatosPaciente');
			$this->salida .= "<BR>";
			$this->salida .= "<BR>";
			$this->salida .= '<table width="80%" align="center" border="0" class="modulo_table">';
			$this->salida .= '<tr align="center" class="modulo_table_title">';
			$this->salida .= '<td>';
			$this->salida .= "Paciente en Hospitalización";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= '<tr align="center">';
			$this->salida .= '<td align="center">';
			$this->salida .= '<table width="100%" align="center" class="modulo_table_list" border="1">';
			$this->salida .= '<tr align="center" class="modulo_table_list_title">';
			$this->salida .= '<td align="center" width="35%">';
			$this->salida .= "Pacientes";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center" width="10">';
			$this->salida .= "Pieza - Cama";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "Fecha Evolución";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "Nombre";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "Especialidad";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$i=$spy=0;
			//print_r($DatosHospitalizacion);
			foreach($DatosHospitalizacion as $k=>$v)
			{
				foreach($v as $t=>$r)
				{
					foreach($r as $p=>$q)
					{
						if($spy==0)
						{
							$this->salida.='<tr align="center" class="modulo_list_claro">';
							$dato='<tr align="center" class="modulo_list_claro">';
							$spy=1;
						}
						else
						{
							$this->salida.='<tr align="center" class="modulo_list_oscuro">';
							$dato='<tr align="center" class="modulo_list_oscuro">';
							$spy=0;
						}
						$this->salida .= '<td align="center">';
						$open=RetornarWinOpenDatosPaciente($t,$k,$p);
						$this->salida .=$open;
						$this->salida .= "</td>";
						$t=0;
						$prof=0;
						foreach($q as $h=>$j)
						{
							if($t==0)
							{
								$this->salida .= '<td align="center">';
								$this->salida .=$j['cama'];
								$this->salida .= "</td>";
								$t=1;
							}
							$salida1 .= '<table width="100%" align="center" border="0">';
							$salida1.=$dato;
							$salida1 .= "<td>";
							$salida1 .=$j['fecha'];
							$salida1 .= "</td>";
							$salida1 .= "</tr>";
							$salida1 .= '</table>';
							$salida2 .= '<table width="100%" align="center" border="0">';
							$salida2.=$dato;
							$salida2 .= "<td>";
							$salida2 .=$j['nombre'];
							$salida2 .= "</td>";
							$salida2 .= "</tr>";
							$salida2 .= '</table>';
							$salida .= '<table width="100%" align="center" border="0">';
							$salida.=$dato;
							$especialidad='';
							$arr=$this->RevisarInterConsultas($j['ingreso']);
              if(!empty($arr))
							{
						//	echo "<br>". print_R($arr)."<br>";
									for($x=0;$x<sizeof($arr);$x++)
									{
											if(empty($j['evolucion_id']))
											{
													$accion=ModuloHCGetURL(0,'',$j['numerodecuenta'],'AtencionInterconsulta','ControlPrenatal',array('estacion'=>$_SESSION['AtencionUrgencias']['estacion_id']));
													$prof=1;
											}
											else
											{
													if($j['usuario_id']==UserGetUID())
													$accion=ModuloHCGetURL($j['evolucion_id'],'',0,'AtencionInterconsulta','ControlPrenatal',array('estacion'=>$_SESSION['AtencionUrgencias']['estacion_id']));
											}
											if($prof==0)
											{
														$accion=ModuloHCGetURL(0,'',$j['ingreso'],'AtencionInterconsulta','	ControlPrenatal',array('estacion'=>$_SESSION['AtencionUrgencias']['estacion_id']));

											}
												$especialidad .="<a href='$accion'>".$arr[$x][descripcion]."</a><br>";
									}
									$salida .= "<td>$especialidad";
									$especialidad='';

								$salida .= "</td>";
							}else
							{
								$salida .= "<td><label class='label_mark'>No tiene Interconsulta</label></td>";
								//$especialidad='';

							}
							$salida .= "</tr>";
							$salida .= '</table>';
						}
						$this->salida .= '<td align="center" valign="top">';
						$this->salida .=$salida1;
						$this->salida .= "</td>";
						$this->salida .= '<td align="center" valign="top">';
						$this->salida .=$salida2;
						$this->salida .= "</td>";
						$this->salida .= '<td align="center" valign="top">';
						$this->salida .=$salida;
						$this->salida .= "</td>";
						$this->salida .= '</tr>';
						$salida='';
						$salida1='';
						$salida2='';
					}
				}
			}
			$this->salida .= '</table>';
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= '</table>';
		}
		else
		{
			$this->salida .= '<table width="80%" align="center">';
			$this->salida .= '<tr align="center">';
			$this->salida .= '<td align="center">';
			$this->salida .= '<label class="label_error">NO HAY PACIENTES PARA ATENDER EN HOSPITALIZACIÓN</label>';
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= "</table>";
		}
		$this->salida .= "<BR>";
		//$this->ListadoPacienteUrgencias();
		//$this->ListadoPacientesClasificar();
		$this->salida .='<table border="0" align="center" width="50%">';
		$this->salida .='<tr>';
		$this->salida .='<td align="center">';
		if(!empty($_SESSION['url_origen']))
		{
			$accion=ModuloGetURL($_SESSION['url_origen']['contenedor'],$_SESSION['url_origen']['modulo'],$_SESSION['url_origen']['tipo'],$_SESSION['url_origen']['metodo'],array('estacion'=>$_SESSION['AtencionUrgencias']));
		}
		else
		{
			$accion=ModuloGetURL('app','AtencionInterconsulta','','');
		}
		$this->salida .='<form name="volver" method="post" action="'.$accion.'">';
		$this->salida .='<input type="submit" name="VOLVER" value="VOLVER" class="input-submit">';
		$this->salida .='</form>';
		$this->salida .='</td>';
		$this->salida .='<td align="center">';
		$accion=ModuloGetURL('app','AtencionInterconsulta','','ListadoPaciente');
		$this->salida .='<form name="volver" method="post" action="'.$accion.'">';
		$this->salida .='<input type="submit" name="REFRESCAR" value="REFRESCAR" class="input-submit">';
		$this->salida .='</form>';
		$this->salida .='</td>';
		$this->salida .='</tr>';
		$this->salida .='</table>';
		$this->salida .= "<BR>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}*/

}
?>
