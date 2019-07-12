<?php

class app_HistoriaClinicaPapel_userclasses_HTML extends app_HistoriaClinicaPapel_user
{

	function app_HistoriaClinicaPapel_user_HTML()
	{
	  $this->app_HistoriaClinicaPapel_user(); //Constructor del padre 'modulo'
		$this->salida='';
		return true;
	}


	function PantallaInicial()
	{
		$this->salida = ThemeAbrirTabla('MANEJO HISTORIA CLINICA');
		$this->salida .= "<br>";
		$this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
		$this->salida .= "<tr>";
		$this->salida .= "<td align=\"center\" class=\"modulo_table_list_title\">MENU DE MANEJO HISTORIA CLINICA</td>";
		$this->salida .= "</tr>";
		if($spy==0)
		{
			$this->salida .='<tr class="modulo_list_claro">';
			$spy=1;
		}
		else
		{
			$this->salida .='<tr class="modulo_list_oscuro">';
			$spy=0;
		}
		$this->salida .='<td align="center">';
		$accion=ModuloGetURL('app','HistoriaClinicaPapel','user','BusquedaHistoriaParaHoyUser');
		$this->salida .='<a href="'.$accion.'">BUSQUEDA HISTORIAS PARA LA ATENCION</a>';
		$this->salida .='</td>';
		$this->salida .='</tr>';
		$this->salida .='</table>';
		$this->salida .='<br>';
		$this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
		$this->salida .='<tr>';
		$this->salida .='<td align="center">';
		$accion=ModuloGetURL('system','Menu','user','main');
		$this->salida .='<form name="cosa" action="'.$accion.'" method="post">';
		$this->salida .='<input type="submit" name="VOLVER" value="VOLVER" class="input-submit">';
		$this->salida.='</form>';
		$this->salida .='</td>';
		$this->salida .='</tr>';
		$this->salida .='</table>';
		$this->salida .='<br>';
		$this->salida .= ThemeCerrarTabla();
		return true;
	}


	function BusquedaHistoriaParaHoy()
	{
		unset($_SESSION);
		$datos=$this->BusquedaHistoriasCitas();
		$this->SetJavaScripts('DatosPaciente');
		$this->salida = ThemeAbrirTabla('MANEJO HISTORIA CLINICA');
		$this->salida .= "<table  width=\"80%\" border=\"0\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
		$this->salida .= "<tr>";
		$this->salida .= "<td align=\"center\" class=\"label_mark\">CITAS CORRESPONDIENTES AL DIA: ".$_REQUEST['DiaEspe']."";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		if(!empty($datos))
		{
			$this->salida .='<br>';
			$this->salida .= "<table width=\"80%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
			$this->salida .= "<tr class=\"modulo_table_list_title\">";
			$this->salida .= "<td align=\"center\">IDENTIFICACION</td>";
			$this->salida .= "<td align=\"center\">NOMBRE PACIENTE</td>";
			$this->salida .= "<td align=\"center\">HORA CITA</td>";
			$this->salida .= "<td align=\"center\">NOMBRE PROFESIONAL</td>";
			$this->salida .= "<td align=\"center\">CONSULTORIO</td>";
			$this->salida .= "<td align=\"center\">ACCION</td>";
			$this->salida .= "</tr>";
			$spy=0;
			foreach($datos as $k=>$v)
			{
				foreach($v as $t=>$m)
				{
					if($spy==0)
					{
						$this->salida .='<tr class="modulo_list_claro">';
						$spy=1;
					}
					else
					{
						$this->salida .='<tr class="modulo_list_oscuro">';
						$spy=0;
					}
					$this->salida .= "<td align=\"center\" width=\"10%\">";
					$this->salida .= $m['tipo_id_paciente'].' - '.$m['paciente_id'];
					$this->salida .= "</td>";
					$dato=RetornarWinOpenDatosPaciente($m['tipo_id_paciente'],$m['paciente_id'],$m['nombre']);
					$this->salida .= "<td align=\"center\">";
					$this->salida .= $dato;
					$this->salida .= "</td>";
					$this->salida .= "<td align=\"center\">";
					$this->salida .= $m['hora'];
					$this->salida .= "</td>";
					$this->salida .= "<td align=\"center\">";
					$this->salida .= $m['nombre_tercero'];
					$this->salida .= "</td>";
					$this->salida .= "<td align=\"center\">";
					$this->salida .= $m['consultorio_id'];
					$this->salida .= "</td>";
					$this->salida .= "<td align=\"center\" width=\"10%\">";
					$url=ModuloGetURL('app','HistoriaClinicaPapel','user','LlamaInformehis',array('tipo_id_paciente'=>$m['tipo_id_paciente'],'paciente_id'=>$m['paciente_id'],'nombre'=>$m['nombre']));
					$this->salida .= "<A href=\"$url\"><img src=\"".GetThemePath()."/images/pplan.png\" border=\"0\"></A></br>";
					$this->salida .= "</td>";
					$this->salida .= "</tr>";
				}
			}
			$this->salida .='</table>';
		}
		else
		{
		  $this->salida .= "<table width=\"80%\" border=\"0\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
		  $this->salida .= "<tr>";
			$this->salida .= "<td align=\"center\"><label class=\"label_error\">NO EXISTEN SOLICITUDES DE HISTORIAS</label></td>";
			$this->salida .= "</tr>";
		  $this->salida .='</table>';
		}
		global $VISTA;
		$this->salida .='<br>';
		$this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
		$this->salida .='<tr>';
		$this->salida .='<td align="center" width="50%">';
		$accion=ModuloGetURL('app','HistoriaClinicaPapel','user','main');
		$this->salida .='<form name="cosa" action="'.$accion.'" method="post">';
		$this->salida .='<input type="submit" name="VOLVER" value="VOLVER" class="input-submit">';
		$this->salida.='</form>';
		$this->salida .='</td>';
		$this->salida .='<td align="center" width="50%">';
		$accion=ModuloGetURL('app','HistoriaClinicaPapel','user','BusquedaFecha');
		$this->salida .='<form name="cosa" action="'.$accion.'" method="post">';
		$this->salida .='<input type="submit" name="BUSCAR DIA" value="BUSCAR DIA" class="input-submit">';
		$this->salida.='</form>';
		$this->salida .='</td>';
		$this->salida .='<td align="center" width="50%">';
		$ruta=GetVarConfigAplication('DirSpool');
		$name=tempnam($ruta, "imprehc_");
		$this->salida .='<input type="submit" name="IMPRIMIR" value="IMPRIMIR" onclick="window.open(\''.$name.'\')" class="input-submit">';
		$this->salida .='</td>';
		$this->salida .='</tr>';
		$this->salida .='</table>';
		$this->salida .='<br>';
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function Informehis($infohistoria)
	{
	foreach($_REQUEST as $a=>$v)
	{
		if(substr_count ($a,'paciente_id')==1)
		{
			$_SESSION['HistoriaClinicaPapel'][$a]=$v;
		}
	}
	foreach($_REQUEST as $b=>$c)
	{
		if(substr_count ($b,'tipo_id_paciente')==1)
		{
			$_SESSION['HistoriaClinicaPapel'][$b]=$c;
		}
	}
		$this->salida = ThemeAbrirTabla('REFERENCIA DE HISTORIA CLINICA', '500');
		$this->salida .= "<table border=\"0\" width=\"100%\">";
		$this->salida .= "<tr><td colspan=\"2\">";
		$accion=ModuloGetURL('app','HistoriaClinicaPapel','user','LlamaEditarhistoria');
		$this->salida .= "<form name=\"historia\" action=\"$accion\" method=\"post\">";
		$this->salida .= "<fieldset><legend class=\"field\">DATOS HISTORICOS DEL PACIENTE</legend>";
		$this->salida .= "<table border=\"0\" width=\"100%\" class=\"modulo_table_list\" cellspacing=\"2\" cellpadding=\"4\">";
		$this->salida .= "<tr class=\"modulo_list_claro\">";
		$this->salida .= "<input type=\"hidden\" name=\"nombre\" value=\"".$_REQUEST['nombre']."\">";
		$this->salida .= "<input type=\"hidden\" name=\"paciente_id\" value=\"".$_REQUEST['paciente_id']."\">";
		$this->salida .= "<input type=\"hidden\" name=\"tipo_id_paciente\" value=\"".$_REQUEST['tipo_id_paciente']."\">";
		$this->salida .= "<td align=\"left\" width=\"50%\"><b> IDENTIFICACION: </b></td>";
		$this->salida .= "<td align=\"left\" width=\"50%\"> ".$_REQUEST['tipo_id_paciente']."".':  '."".$_REQUEST['paciente_id']."</td>";
		$this->salida .= "</tr>";
		$this->salida .= "<tr class=\"modulo_list_oscuro\">";
		$this->salida .= "<td align=\"left\" width=\"50%\"><b> No. DE HISTORIA CLINICA: </b></td>";
		$this->salida .= "<td align=\"left\" width=\"50%\">";
		$this->salida .= "<input type=\"text\" class= \"input text\" name=\"historia_numero\" value=\"".$_POST['historia_numero']."\" maxlength=\"50\" size=\"40\"></td>";
		$this->salida .= "</tr>";
		$this->salida .= "<tr class=\"modulo_list_claro\">";
		$this->salida .= "<td align=\"left\" width=\"50%\"><b> PREFIJO DE HISTORIA CLINICA: </b></td>";
		$this->salida .= "<td align=\"left\" width=\"50%\">";
		$this->salida .= "<input type=\"text\" class= \"input text\" name=\"historia_prefijo\" value=\"".$_POST['historia_prefijo']."\" maxlength=\"4\" size=\"10\"></td>";
		$this->salida .= "</tr>";
		$this->salida .= "<tr class=\"modulo_list_oscuro\">";
		$this->salida .= "<td align=\"left\" width=\"50%\"><b> NOMBRE DEL PACIENTE: </b></td>";
		$this->salida .= "<td align=\"left\" width=\"50%\"> ".$_REQUEST['nombre']." </td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .= "</td></tr>";
		$this->salida .= "<tr>";
		$this->salida .= "<td align=\"center\" width=\"50%\"><br>";
		$this->salida .="<input class=\"input-submit\" type=\"submit\" name=\"guardarhistoria\" value=\"GUARDAR\">";
		$this->salida .= "</form>";
		$accion=ModuloGetURL('app','HistoriaClinicaPapel','user','BusquedaHistoriaParaHoyUser');
		$this->salida .= "<td align=\"center\" width=\"50%\"><br>";
		$this->salida .= "<form name=\"editar\" action=\"$accion\" method=\"post\">";
		$this->salida .= "<input class=\"input-submit\" type=\"submit\" name=\"volverahis\" value=\"VOLVER\">";
		$this->salida .= "</form>";
		$this->salida .= "</td></tr></table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function BusquedaFecha()
	{
		$this->salida.="\n".'<script>'."\n";
		$this->salida.='function year1(t)'."\n";
		$this->salida.='{'."\n";
		$this->salida.='window.location.href="Contenido.php?year="+t.elements[0].value+"&meses="+t.elements[1].value+"';
		foreach($_REQUEST as $v=>$v1)
		{
			if($v!='year' and $v!='meses')
			{
				$this->salida.='&'.$v.'='.$v1;
			}
		}
		$this->salida.='";'."\n";
		$this->salida.='}'."\n";
		$this->salida.='</script>';
		$this->salida .= ThemeAbrirTabla('MANEJO HISTORIA CLINICA');
		$this->salida .='<form name="cosa">';
		$this->salida .= "<table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .='<tr align="center">';
		$this->salida .="<td class=\"label\">AÑO</td><td><select name=\"year\" onchange=\"year1(this.form)\" class=\"select\">";
		if(empty($_REQUEST['year']))
		{
			$year=$_REQUEST['year']=date("Y");
			$this->AnosAgenda(True,$_REQUEST['year']);
		}
		else
		{
			$this->AnosAgenda(true,$_REQUEST['year']);
			$year=$_REQUEST['year'];
		}
		$this->salida .= "</select></td>";
		$this->salida .="<td class=\"label\">MES</td><td><select name=\"mes\" onchange=\"year1(this.form)\" class=\"select\">";
		if(empty($_REQUEST['meses']))
		{
			$mes=date("m");
			$this->MesesAgenda(True,$year,$mes);
		}
		else
		{
			$this->MesesAgenda(True,$year,$_REQUEST['meses']);
			$mes=$_REQUEST['meses'];
		}
		$this->salida .= "</select>";
		$this->salida .= "</td>";
		if($_SESSION['Atencion']['DiaEspe']!=date("Y-m-d"))
		{
			foreach($_REQUEST as $v=>$datos)
			{
				if($v!='DiaEspe' and $v!='SIIS_SID' and $v!='modulo' and $v!='metodo')
				{
					$vec[$v]=$datos;
				}
			}
			$vec['DiaEspe']=date("Y-m-d");
		}
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .='</form>';
		$this->salida .= "<BR>";
		$this->salida .= '<table width="70%" align="center">';
		$this->salida .= '<tr align="center">';
		$this->salida .= '<td align="center">';
		$_REQUEST['metodo']='BusquedaHistoriaParaHoy';
		$this->ReturnMetodoExterno('app', 'Agenda', 'user', 'CalendarioEstandard',$_REQUEST);
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .= "<BR>";
		$this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
		$this->salida .='<tr>';
		$this->salida .='<td align="center" width="50%">';
		$accion=ModuloGetURL('app','HistoriaClinicaPapel','user','BusquedaHistoriaParaHoy');
		$this->salida .='<form name="cosa" action="'.$accion.'" method="post">';
		$this->salida .='<input type="submit" name="VOLVER" value="VOLVER" class="input-submit">';
		$this->salida.='</form>';
		$this->salida .='</td>';
		$this->salida .='</tr>';
		$this->salida .='</table>';
		$this->salida .='<br>';
		$this->salida .= ThemeCerrarTabla();
		return true;
	}


	function AnosAgenda($Seleccionado='False',$ano)
	{
		$anoActual=date("Y");
		$anoActual1=$anoActual;
    for($i=0;$i<=10;$i++)
		{
      $vars[$i]=$anoActual1;
			$anoActual1=$anoActual1+1;
		}
		switch($Seleccionado)
		{
			case 'False':
			{
				foreach($vars as $value=>$titulo)
				{
          if($titulo==$ano)
					{
					  $this->salida .=" <option value=\"$titulo\" selected>$titulo</option>";
				  }else{
            $this->salida .=" <option value=\"$titulo\">$titulo</option>";
				  }
				}
				break;
		  }case 'True':
			{
			  foreach($vars as $value=>$titulo)
				{
					if($titulo==$ano)
					{
				    $this->salida .=" <option value=\"$titulo\" selected>$titulo</option>";
				  }else{
				    $this->salida .=" <option value=\"$titulo\">$titulo</option>";
					}
				}
				break;
		  }
	  }
	}

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
		$mesActual=date("m");
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
							}else{
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
						}else{
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
							}else
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
						}else
						{
							$this->salida .=" <option value=\"$value\">$titulo</option>";
						}
					}
				}
				break;
			}
		}
	}



	function SetStyle($campo)
	{
				if ($this->frmError[$campo] || $campo=="MensajeError"){
					if ($campo=="MensajeError"){
						return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
					}
					else
					{
						return ("label_error");
					}
				}
			return ("label");
	}

}//fin de la clase
?>

