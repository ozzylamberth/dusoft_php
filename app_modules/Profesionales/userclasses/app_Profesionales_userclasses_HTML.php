<?php

/**
 * $Id: app_Profesionales_userclasses_HTML.php,v 1.2 2010/02/24 12:09:54 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_Profesionales_userclasses_HTML extends app_Profesionales_user
{

	function app_Profesionales_user_HTML()
	{
	    $this->app_Profesionales_user(); //Constructor del padre 'modulo'
		$this->salida='';
		return true;
	}


//Mantenimiento Profesionales

	function MantenimientoProfesionales()
	{
		unset($_SESSION['ManProf']);
		$url[0]='app';
		$url[1]='Profesionales';
		$url[2]='user';
		$url[3]='ListarProfe';
		$url[4]='ManProf';
		$Cita=$this->DepartamentoProfesionales($url);
		if($Cita)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function ListarProfe($mensaje,$arr)
	{
		if(empty($_SESSION['ManProf']['empresa']))
		{
			$_SESSION['ManProf']['nomemp']=$_REQUEST['ManProf']['descripcion1'];
			$_SESSION['ManProf']['empresa']=$_REQUEST['ManProf']['empresa_id'];
		}
		unset($_SESSION['ManProf']['Profesional']);

		$this->salida=ThemeAbrirTabla('LISTADO PROFESIONALES - '.$_SESSION['ManProf']['nomemp']);
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


		$this->salida .= "			      <table align=\"center\" width=\"65%\" border=\"0\"><tr><td><table width=\"60%\" border=\"0\" align=\"center\">";
		$accion=ModuloGetURL('app','Profesionales','user','BuscarPacientes',array('Busqueda'=>$_REQUEST['Busqueda']));
		$this->salida .= "             <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
			$this->salida .= "				        <tr><td class=modulo_table_title align=\"right\">TIPO DOCUMENTO: </td><td class=modulo_list_claro><select name=\"TipoDocumento\" class=\"select\">";
			$tipo_id=$this->tipo_id_terceros();
			foreach($tipo_id as $k=>$v)
			{
				if($_REQUEST['TipoDocumento']==$k)
				{
					$this->salida .=" <option value=\"$k\" selected>$v</option>";
				}
				else
				{
					$this->salida .=" <option value=\"$k\">$v</option>";
				}
			}
			$this->salida .= "                  </select></td></tr>";
			$this->salida .= "				        <tr><td class=modulo_table_title  align=\"right\" class=\"label\">DOCUMENTO: </td><td class=modulo_list_claro><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" value=\"".$_REQUEST['Documento']."\"></td></tr>";
			$this->salida .= "				        <tr><td class=modulo_table_title  align=\"right\" class=\"label\">NOMBRE</td><td class=modulo_list_claro><input type=\"text\" class=\"input-text\" name=\"nombres\" value=\"".$_REQUEST['nombres']."\" maxlength=\"32\"></td></tr>";

			$this->salida .= "				        <tr><td class=modulo_table_title align=\"right\">PROFESIONALES</td><td class=modulo_list_claro>";
		$prof=$this->TipoEspecialidades();
		if($prof==false)
		{
			return false;
		}
		$this->salida .= '<select name="TipoProfe" class="select">';
		$this->salida .= '<option value="-1" selected>--SELECCIONE--</option>';
		foreach($prof as $k=>$v)
		{
			if($k==$_REQUEST['TipoProfe'])
			{
				$this->salida .= '<option value="'.$k.'" selected>'.$v.'</option>';
			}
			else
			{
				$this->salida .= '<option value="'.$k.'">'.$v.'</option>';
			}
		}
		$this->salida .= '</select>';

		$this->salida .= "</td></tr>";

		$this->salida .= "               <tr class=modulo_list_oscuro><td colspan=\"2\"align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\"></form></td></tr>";
		$this->salida .= "	</table></td></tr></table>";
		$this->salida .='<br>';
		if($mensaje)
		{
			$this->salida .= "			<p class=\"label_error\" align=\"center\">$mensaje</p>";
		}
		if(!empty($_REQUEST['Documento']))
		{
			if(!empty($arr))
			{
				/*if($this->SaberProfesional($_REQUEST['TipoDocumento'],$_REQUEST['Documento'])==false)
				{
					$this->salida.="<p class=\"label_error\" align=\"center\">DESEA CREAR EL PROFESIONAL CON ".$_REQUEST['TipoDocumento']." No. ".$_REQUEST['Documento']."</p>";
					$this->salida .= '<table align="center" width="70%" border="0">';
					$this->salida .= '<tr>';
					$this->salida .= '<td align="center">';
					$accion=ModuloGetURL('app','Profesionales','user','CallTerceros',array('tercero'=>$_REQUEST['Documento'],'tipoid'=>$_REQUEST['TipoDocumento']));
					$this->salida .= '<form name="Crear" method="post" action="'.$accion.'">';
					$this->salida .= '<input type="submit" name="CREAR" value="CREAR" class="input-submit">';
					$this->salida .= '</form>';
					$this->salida .= '</td>';
					$this->salida .= '</tr>';
					$this->salida .= '</table>';
				}*/
			}
		}
		if(empty($arr) and empty($_REQUEST['Documento']))
		{
			$arr=$this->ProfesionalesCompleto();
		}
		$this->salida .= '<br>';
		if($arr!=false)
		{
			$this->salida .= '<table align="center" width="93%" border="0">';
			$this->salida .= '<tr align="center" class="modulo_table_title">';
			$this->salida .= '<td align="center" width="5%" cellpadding="0">';
			$this->salida .= 'ESTADO';
			$this->salida .= '</td>';
			$this->salida .= '<td width="10%">';
			$this->salida .= 'IDENTIFICACIÓN';
			$this->salida .= '</td>';
			$this->salida .= '<td  width="35%">';
			$this->salida .= 'PROFESIONALES';
			$this->salida .= '</td>';
			$this->salida .= '<td width="30%">';
			$this->salida .= 'ESPECIALIDADES';
			$this->salida .= '</td>';
			$this->salida .= '<td width="5%">';
			$this->salida .= '</td>';
			$this->salida .= '<td width="10%">&nbsp;&nbsp;';
			$this->salida .= '</td>';
			$this->salida .= '</tr>';
			$this->SetJavaScripts('DatosProfesional');
			$i=0;
			while($i<sizeof($arr[0]))
			{
				if($spy==0)
				{
					$estilo='modulo_list_claro';
					$this->salida .= "<tr class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>";
					$spy=1;
				}
				else
				{
					$estilo='modulo_list_oscuro';
					$this->salida .= "<tr class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>";
					$spy=0;
				}
				$this->salida .= '<td align="center">';
				if($arr[1][$i]==1)
				{
					unset($arr1);
					foreach($_REQUEST as $k=>$v)
					{
						if($k!='SIIS_ID' and $k!='modulo' and $k!='metodo' and $k!='tipo' and $k!='contenedor')
						{
							$arr1[$k]=$v;
						}
					}
					$arr1['estado']=0;
					$arr1['tipoterceroe']=$arr[2][$i];
					$arr1['terceroe']=$arr[3][$i];
					$this->salida .= "<a href=\"".ModuloGetURL('app','Profesionales','user','CambiarEstado',$arr1)."\"><img src=\"".$imagen=GetThemePath()."/images/activo.gif\" align=\"middle\" border=\"0\"></a>";
				}
				else
				{
					foreach($_REQUEST as $k=>$v)
					{
						if($k!='SIIS_ID' and $k!='modulo' and $k!='metodo' and $k!='tipo' and $k!='contenedor')
						{
							$arr1[$k]=$v;
						}
					}
					$arr1['estado']=1;
					$arr1['tipoterceroe']=$arr[2][$i];
					$arr1['terceroe']=$arr[3][$i];
					$this->salida .= "<a href=\"".ModuloGetURL('app','Profesionales','user','CambiarEstado',$arr1)."\"><img src=\"".$imagen=GetThemePath()."/images/inactivo.gif\" align=\"middle\" border=\"0\"></a>";
				}
				$this->salida .= '</td>';
				$this->salida .= '<td>';
				$this->salida .= $arr[2][$i].' - '.$arr[3][$i];
				$this->salida .= '</td>';
				$this->salida .= '<td>';
				$this->salida .= RetornarWinOpenDatosProfesional($arr[2][$i],$arr[3][$i],$arr[0][$i]);
				$this->salida .= '</td>';
				$this->salida .= '<td>';
				$this->salida .= $this->Especialidades($arr[2][$i],$arr[3][$i]);
				$this->salida .= '</td>';
				$this->salida .= '<td>';
				$this->salida .= '<a href="'.ModuloGetURL('app','Profesionales','user','PantallaProfesional',array('tercero'=>$arr[3][$i],'tipoid'=>$arr[2][$i],'nombrep'=>$arr[0][$i],'TipoProf'=>$arr[4][$i],'Sexo'=>$arr[5][$i],'TarjProf'=>$arr[6][$i],'estado'=>$arr[1][$i],'universidad'=>$arr[7][$i],'defuncion'=>$arr[8][$i],'registro_salud'=>$arr[9][$i],'observacion'=>$arr[10][$i],'circulante'=>$arr[11][$i])).'">DETALLE</a>';
				$this->salida .= '</td>';

				$this->salida .= '<td>';
				$this->salida .= '<a href="'.ModuloGetURL('app','Profesionales','user','Pantalla_Asignar_dpto_Profesional',array('tercero'=>$arr[3][$i],'tipoid'=>$arr[2][$i],'nombrep'=>$arr[0][$i],'TipoProf'=>$arr[4][$i],'Sexo'=>$arr[5][$i],'TarjProf'=>$arr[6][$i],'estado'=>$arr[1][$i],'universidad'=>$arr[7][$i],'defuncion'=>$arr[8][$i],'registro_salud'=>$arr[9][$i],'observacion'=>$arr[10][$i],'circulante'=>$arr[11][$i])).'">ASIGNAR DPTO</a>';
				$this->salida .= '</td>';

				$this->salida .= '</tr>';
				$i++;
			}
			$this->salida .= '</table>';
		}
		$this->salida .= '<br>';
		$this->salida .= '<br>';
		$var=$this->RetornarBarraProfesionales();
		if(!empty($var))
		{
			$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td width=\"100%\" align=\"center\">";
			$this->salida .=$var;
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  </table><br>";
		}

		//$this->salida.="<p class=\"label_error\" align=\"center\">DESEA CREAR EL PROFESIONAL CON ".$_REQUEST['TipoDocumento']." No. ".$_REQUEST['Documento']."</p>";
		$this->salida .= '<table align="center" width="70%" border="0">';
		$this->salida .= '<tr>';
		$this->salida .= '<td align="center">';

		if(empty($_REQUEST['Documento']))
		{unset($_REQUEST['Documento']);unset($_REQUEST['TipoDocumento']);}
		$accion=ModuloGetURL('app','Profesionales','user','CallTerceros',array('tercero'=>$_REQUEST['Documento'],'tipoid'=>$_REQUEST['TipoDocumento']));
		$this->salida .= '<form name="Crear" method="post" action="'.$accion.'">';
		$this->salida .= '<input type="submit" name="CREAR" value="CREAR PROFESIONAL" class="input-submit">';
		$this->salida .= '</form>';
		$this->salida .= '</td>';
		$this->salida .= '</tr>';
		$this->salida .= '</table>';


		$this->salida .= '<br>';
		$this->salida .= '<table align="center" width="70%" border="0">';
		$this->salida .= '<tr>';
		$this->salida .= '<td align="center">';
		$accion=ModuloGetURL('app','Profesionales','user','MantenimientoProfesionales');
		$this->salida .= '<form name="volver" method="post" action="'.$accion.'">';
		$this->salida .= '<input type="submit" name="VOLVER" value="VOLVER" class="input-submit">';
		$this->salida .= '</form>';
		$this->salida .= '</td>';
		/*if(!empty($mensaje) and !empty($_REQUEST['Documento']))
		{
			$this->salida .= '<td align="center">';
			 $accion=ModuloGetURL('app','Profesionales','user','CallTerceros',array('tercero'=>$_REQUEST['Documento'],'tipoid'=>$_REQUEST['TipoDocumento']));
			$this->salida .= '<form name="Crear" method="post" action="'.$accion.'">';
			$this->salida .= '<input type="submit" name="CREAR" value="CREAR" class="input-submit">';
			$this->salida .= '</form>';
			$this->salida .= '</td>';
		}*/
		$this->salida .= '</tr>';
		$this->salida .= '</table>';
		$this->salida .= '<br>';
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function RetornarBarraProfesionales()//Barra paginadora de los planes clientes
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso))
		{
			$paso=1;
		}
		foreach($_REQUEST as $v=>$v1)
		{
			if($v!='modulo' and $v!='metodo' and $v!='tipo' and $v!='contenedor' and $v!='SIIS_SID' and $v!='conteo' and $v!='paso')
			{
				if (is_array($v1)) {
					foreach($v1 as $k2=>$v2) {
						if (is_array($v2)) {
							foreach($v2 as $k3=>$v3) {
								if (is_array($v3)) {
									foreach($v3 as $k4=>$v4) {
										$arr[$v][$k2][$k3][$k4]=$v4;
									}
								}else{
									$arr[$v][$k2][$k3]=$v3;
								}
							}
						}else{
							$arr[$v][$k2]=$v2;
						}
					}
				} else {
					$arr[$v]=$v1;
				}
			}
		}
		$arr['conteo']=$this->conteo;
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			if(empty($_REQUEST['Documento']))
			{
				$accion=ModuloGetURL('app','Profesionales','user','ListarProfe',$arr);
			}
			else
			{
				$accion=ModuloGetURL('app','Profesionales','user','BuscarPacientes',$arr);
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					if(empty($_REQUEST['Documento']))
					{
						$accion=ModuloGetURL('app','Profesionales','user','ListarProfe',$arr);
					}
					else
					{
						$accion=ModuloGetURL('app','Profesionales','user','BuscarPacientes',$arr);
					}
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			if(empty($_REQUEST['Documento']))
			{
				$accion=ModuloGetURL('app','Profesionales','user','ListarProfe',$arr);
			}
			else
			{
				$accion=ModuloGetURL('app','Profesionales','user','BuscarPacientes',$arr);
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					if(empty($_REQUEST['Documento']))
					{
						$accion=ModuloGetURL('app','Profesionales','user','ListarProfe',$arr);
					}
					else
					{
						$accion=ModuloGetURL('app','Profesionales','user','BuscarPacientes',$arr);
					}
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				if(empty($_REQUEST['Documento']))
				{
					$accion=ModuloGetURL('app','Profesionales','user','ListarProfe',$arr);
				}
				else
				{
					$accion=ModuloGetURL('app','Profesionales','user','BuscarPacientes',$arr);
				}
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
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
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
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
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		return $salida;
	}

	function PantallaProfesional()
	{
		unset($_SESSION['PROF']['SUB_ESPECIALIDAD']);
		if(empty($_SESSION['ManProf']['DATOS']))
		{
			if(empty($_SESSION['ManProf']['Profesional']['tercero']) and (!empty($_REQUEST['tercero']) or ($_REQUEST['tercero']!=$_SESSION['ManProf']['Profesional']['tercero'])))
			{
				$_SESSION['ManProf']['Profesional']['tercero']=$_REQUEST['tercero'];
				$_SESSION['ManProf']['Profesional']['tipoid']=$_REQUEST['tipoid'];
				$_SESSION['ManProf']['Profesional']['nombrep']=$_REQUEST['nombrep'];
				$_SESSION['ManProf']['Profesional']['TipoProf']=$_REQUEST['TipoProf'];
				$_SESSION['ManProf']['Profesional']['Sexo']=$_REQUEST['Sexo'];
				$_SESSION['ManProf']['Profesional']['TarjProf']=$_REQUEST['TarjProf'];
				$_SESSION['ManProf']['Profesional']['estado']=$_REQUEST['estado'];
				$_SESSION['ManProf']['Profesional']['universidad']=$_REQUEST['universidad'];
				$_SESSION['ManProf']['Profesional']['defuncion']=$_REQUEST['defuncion'];
				$_SESSION['ManProf']['Profesional']['registro_salud']=$_REQUEST['registro_salud'];
				$_SESSION['ManProf']['Profesional']['observacion']=$_REQUEST['observacion'];
				$_SESSION['ManProf']['Profesional']['circulante']=$_REQUEST['circulante'];
				$_SESSION['ManProf']['Profesional']['Existe']=1;
			}
		}
		else
		{
			$_SESSION['ManProf']['Profesional']['tercero']=$_SESSION['ManProf']['DATOS']['tercero_id'];
			$_SESSION['ManProf']['Profesional']['tipoid']=$_SESSION['ManProf']['DATOS']['tipo_id_tercero'];
			$_SESSION['ManProf']['Profesional']['nombrep']=$_SESSION['ManProf']['DATOS']['nombre_tercero'];
			$dato=$this->ProfesionalesBusqueda();
			if(!empty($dato))
			{
				$_SESSION['ManProf']['Profesional']['TipoProf']=$dato['tipo_profesional'];
				$_SESSION['ManProf']['Profesional']['Sexo']=$dato['sexo_id'];
				$_SESSION['ManProf']['Profesional']['TarjProf']=$dato['tarjeta_profesional'];
				$_SESSION['ManProf']['Profesional']['estado']=$dato['estado'];
				$_SESSION['ManProf']['Profesional']['universidad']=$dato['universidad'];
				$_SESSION['ManProf']['Profesional']['defuncion']=$dato['sw_registro_defuncion'];
				$_SESSION['ManProf']['Profesional']['registro_salud']=$dato['registro_salud_departamental'];
				$_SESSION['ManProf']['Profesional']['observacion']=$dato['observacion'];
				$_SESSION['ManProf']['Profesional']['circulante']=$dato['circulante'];
				$_SESSION['ManProf']['Profesional']['Existe']=1;
			}
			else
			{
				$_SESSION['ManProf']['Profesional']['Existe']=0;
			}
		}
		$this->salida=ThemeAbrirTabla('PROFESIONAL - '.$_SESSION['ManProf']['Profesional']['nombrep']);
		//$this->salida .='<br>';

		$this->salida .= '<table width="85%" align="center" class="modulo_table_title">';
		$this->salida .= '<tr align="center" class="modulo_table_title">';
		$this->salida .= '<td align="center">';
		$this->salida .= "Empresa";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= '<tr class="modulo_list_oscuro">';
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['ManProf']['nomemp'];
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .= '<br>';
		$this->salida .= "<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= " </table>";

		$this->salida .= '<table width="85%" align="center" border="0">';
		$this->salida .= '<tr align="left" class="modulo_list_claro" width="30%">';
		$this->salida .= '<td align="left">';
		$this->salida .= 'IDENTIFICACIÓN:';
		$this->salida .= "</td>";
		$this->salida .= '<td align="left">';
		$this->salida .= $_SESSION['ManProf']['Profesional']['tipoid'].' - '.$_SESSION['ManProf']['Profesional']['tercero'];
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$accion=ModuloGetURL('app','Profesionales','user','Desicion');
		$this->salida .= '<form name="volver" method="post" action="'.$accion.'" enctype=\'multipart/form-data\'>';
		$this->salida .= '<tr align="left" class="modulo_list_oscuro">';
		$this->salida .= '<td align="left"  class="modulo_list_claro">';
		$this->salida .= 'NOMBRE:';
		$this->salida .= "</td>";
		$this->salida .= '<td align="left"  width="60%"   class=modulo_list_claro>';
		$this->salida .= '<input type="hidden" name="nombrep"  size="45" maxlength=40 readonly value="'.$_SESSION['ManProf']['Profesional']['nombrep'].'" class="input-text">';
		$this->salida .="".$_SESSION['ManProf']['Profesional']['nombrep']."";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= '<tr align="left" class="modulo_list_claro">';
		$this->salida .= '<td align="left">';
		$this->salida .= 'TIPO DE PROFESIONAL:';
		$this->salida .= "</td>";
		$this->salida .= '<td align="left">';
		$prof=$this->TipoProfesional();
		if($prof==false)
		{
			return false;
		}
		$this->salida .= '<select name="TipoProf" class="select">';
		foreach($prof as $k=>$v)
		{
			if($k==$_SESSION['ManProf']['Profesional']['TipoProf'])
			{
				$this->salida .= '<option value="'.$k.'" selected>'.$v.'</option>';
			}
			else
			{
				$this->salida .= '<option value="'.$k.'">'.$v.'</option>';
			}
		}
		$this->salida .= '</select>';
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= '<tr align="left" class="modulo_list_oscuro">';
		$this->salida .= '<td align="left">';
		$this->salida .= 'SEXO:';
		$this->salida .= "</td>";
		$this->salida .= '<td align="left">';
		$this->salida .= '<select name="Sexo" class="select">';
		$sexo=$this->Sexo();
		if($sexo==false)
		{
			return false;
		}
		foreach($sexo as $k=>$v)
		{
			if($k==$_SESSION['ManProf']['Profesional']['Sexo'])
			{
				$this->salida .= '<option value="'.$k.'" selected>'.$v.'</option>';
			}
			else
			{
				$this->salida .= '<option value="'.$k.'">'.$v.'</option>';
			}
		}
		$this->salida .= '</select>';
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= '<tr align="left" class="modulo_list_claro">';
		$this->salida .= '<td align="left">';
		$this->salida .= 'TARJETA PROFESIONAL:';
		$this->salida .= "</td>";
		$this->salida .= '<td align="left">';
		$this->salida .= '<input type="text" name="TarjProf" maxlength=20 size="60" value="'.$_SESSION['ManProf']['Profesional']['TarjProf'].'" class="input-text">';
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= '<tr align="left" class="modulo_list_oscuro">';
		$this->salida .= '<td align="left">';
		$this->salida .= 'UNIVERSIDAD:';
		$this->salida .= "</td>";
		$this->salida .= '<td align="left">';
		$this->salida .= '<input type="text" name="universidad" maxlength="60" size="60" value="'.$_SESSION['ManProf']['Profesional']['universidad'].'" class="input-text">';
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= '<tr align="left" class="modulo_list_claro">';
		$this->salida .= '<td align="left">';
		$this->salida .= 'OBSERVACION:';
		$this->salida .= "</td>";
		$this->salida .= '<td align="left">';
		$this->salida .= '<input type="text" name="observacion" maxlength="256" size="60" value="'.$_SESSION['ManProf']['Profesional']['observacion'].'" class="input-text">';
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= '<tr align="left" class="modulo_list_oscuro">';
		$this->salida .= '<td align="left">';
		$this->salida .= 'REGISTRO DE SALUD DEPARTAMENTAL:';
		$this->salida .= "</td>";
		$this->salida .= '<td align="left">';
		$this->salida .= '<input type="text" name="registro_salud" maxlength="60" size="60" value="'.$_SESSION['ManProf']['Profesional']['registro_salud'].'" class="input-text">';
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= '<tr align="left" class="modulo_list_claro">';
		$this->salida .= '<td align="left">';
		$this->salida .= 'ESTADO:';
		$this->salida .= "</td>";
		$this->salida .= '<td align="left">';
		$estado=$this->Estado();
		if($estado==false)
		{
			return false;
		}
		$this->salida .= '<select name="estado" class="select">';
		foreach($estado as $k=>$v)
		{
			if($k==$_SESSION['ManProf']['Profesional']['estado'])
			{
				$this->salida .= '<option value="'.$k.'" selected>'.$v.'</option>';
			}
			else
			{
				$this->salida .= '<option value="'.$k.'">'.$v.'</option>';
			}
		}
		$this->salida .= '</select>';
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= '<tr align="left" class="modulo_list_oscuro">';
		$this->salida .= '<td align="left">';
		$this->salida .= 'PUEDE FIRMAR REGISTRO DE DEFUNCIÓN:';
		$this->salida .= "</td>";
		$this->salida .= '<td align="left">';        
		$this->salida .= '<input type="checkbox" value="1" onClick="this.form.datafile.disabled = !this.checked;" name="defuncion"';
		if(1==$_SESSION['ManProf']['Profesional']['defuncion'])
		{
			$this->salida .= 'checked';
		}
		//$this->salida .= '>';
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
          $this->salida .= '<tr align="left" class="modulo_list_claro" name="imagen">';
		$this->salida .= '<td align="left">';
		$this->salida .= 'CARGAR IMAGEN FIRMA PROFESIONAL:';
		$this->salida .= "</td>";
          if(file_exists("images/firmas_profesionales/".$_SESSION['ManProf']['Profesional']['tipoid']."*".$_SESSION['ManProf']['Profesional']['tercero'].".jpg"))
          {
		$this->salida .= '<td align="left">';
		$this->salida .= '<input type="file" name="datafile" value="$target_pat" size="40" >';          
		$this->salida .= "</td>";
          $this->salida .= "</tr>";
          $this->salida .= '<tr align="left" class="modulo_list_oscuro" name="imagen">';
		$this->salida .= '<td align="left">';
		$this->salida .= '';
		$this->salida .= "</td>";
          $this->salida .= '<td align="left">';
		$this->salida .= 'Ya existe una imagen para este profesional';
		$this->salida .= "</td>";
          $this->salida .= "</tr>";
          }
          else{
          $this->salida .= '<td align="left">';
		$this->salida .= '<input type="file" name="datafile" >';
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		
          }
		
		//
		$this->salida .= '<tr align="left" class="modulo_list_claro">';
		/*$this->salida .= '<td align="left">';
		$this->salida .= 'CIRCULANTE:';
		$this->salida .= "</td>";*/
		/*$this->salida .= '<td align="left">';
		$this->salida .= '<input type="checkbox" value="1" name="circulante"';
		if(1==$_SESSION['ManProf']['Profesional']['circulante'])
		{
			$this->salida .= 'checked';
		}
		$this->salida .= '>';
		$this->salida .= "</td>";*/
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .='<br>';
		$tipo_especialidad=$this->TipoEspecialidades();
		$especialidades=$this->Especialidad();
		if($tipo_especialidad)
		{

			if($especialidades)
			{
				$this->salida .= '<table width="85%" align="center" border="0">';
				$this->salida .= '<tr align="center" class="modulo_table_title">';
				$this->salida .= '<td align="center" width="40%">';
				$this->salida .= "Especialidades";
				$this->salida .= "</td>";
				$this->salida .= '<td align="center" width="40%">';
				$this->salida .= "Sub-Especialidades";
				$this->salida .= "</td>";
				$this->salida .= '<td align="center"  width="35%">';
				$this->salida .= "Universidad";
				$this->salida .= "</td>";
				$this->salida .= '<td align="center" width="2%">';
				$this->salida .= "<sub>Modificar</sub>";
				$this->salida .= "</td>";
				$this->salida .= '<td align="center" width="2%">';
				$this->salida .= "<sub>Borrar</sub>";
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
				foreach($especialidades as $k=>$v)
				{
					if($spy==0)
					{
						$this->salida .= '<tr class="modulo_list_oscuro">';
						$spy=1;
					}
					else
					{
						$this->salida .= '<tr class="modulo_list_claro">';
						$spy=0;
					}
					$this->salida .= '<td align="center">';
					$this->salida .= $tipo_especialidad[$k];
					$this->salida .= "</td>";
					$sub=$this->TraerSubEspecialidad($k,$_SESSION['ManProf']['Profesional']['tipoid'],$_SESSION['ManProf']['Profesional']['tercero']);
					if(empty($sub))
					{	$sub=$tipo_especialidad[$k];}
					$this->salida .= '<td align="center">';
					$this->salida .= $sub;
					$this->salida .= "</td>";
					$this->salida .= '<td align="center">';
					$this->salida .= '<input type="text" name="universidade,'.$k.'" value="'.$v.'" maxlength="60" size="50" class="input-text">';
					$this->salida .= "</td>";
					$this->salida .= '<td align="center">';
					$this->salida .= '<input type="submit" name="MODIFICAR" value="M" class="input-submit">';
					$this->salida .= "</td>";
					$this->salida .= '<td align="center">';
					$accion=ModuloGetURL('app','Profesionales','user','EliminarEspecialidad',array('espe'=>$k,'TipoProf'=>$_SESSION['ManProf']['Profesional']['TipoProf']));
					$this->salida .= '<input type="button" name="ELIMINAR" value="X" onclick="location=\''.$accion.'\'" class="input-submit">';
					$this->salida .= "</td>";
					$this->salida .= "</tr>";
				}
					$this->salida .= "</table>";
			}
		/*	if($spy==0)
			{
				$this->salida .= '<tr class="modulo_list_oscuro">';
				$spy=1;
			}
			else
			{
				$this->salida .= '<tr class="modulo_list_claro">';
				$spy=0;
			}
			$this->salida .= '<td align="center">';
			$this->salida .= '<select name="especialidad" class="select">';
			$this->salida .= '<option value="-1">--SELECCIONE--</option>';
			foreach($tipo_especialidad as $k=>$v)
			{
				$this->salida .= '<option value="'.$k.'">'.$v.'</option>';
			}
			$this->salida .= '</select>';
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= '<input type="text" name="universidades" class="input-text">';
			$this->salida .= "</td>";
			$this->salida .= '<td align="center" colspan="2">';
			$this->salida .= '<input type="submit" name="ADICIONAR" value="ADICIONAR" class="input-submit">';
			$this->salida .= "</td>";
			$this->salida .= "</tr>";*/
			$ac=ModuloGetURL('app','Profesionales','user','frmAdicionarEspecialidad');
			$this->salida .= "<br><div align=center><a href='$ac'><sub><img src=\"". GetThemePath() ."/images/especialidad.png\" border='0'>&nbsp;CREAR NUEVA ESPECIALIDAD</sub></a></div>";
			$this->salida .= '<br>';
		}
		$this->salida .= '<table align="center" width="40%" border="0">';
		$this->salida .= '<tr>';
		$this->salida .= '<td align="center">';
		$this->salida .= '<input type="submit" name="GUARDAR" value="GUARDAR" class="input-submit">';
		$this->salida .= '</td>';
		$this->salida .= '<td align="center">';
		$this->salida .= '<input type="submit" name="VOLVER" value="VOLVER" class="input-submit">';
		$this->salida .= '</form>';
		$this->salida .= '</td>';
		$this->salida .= '</tr>';
		$this->salida .= '</table>';
		$this->salida .= '<br>';
		$this->salida .= ThemeCerrarTabla();
		return true;
	}




function frmAdicionarEspecialidad()
{
	$this->salida=ThemeAbrirTabla('CREAR NUEVA ESPECIALIDAD');

	$this->salida .= "				<table align=center class='modulo_table_title' border='0' width='70%'>\n";
	$this->salida .= "					<tr class='modulo_table_title'>\n";
	$this->salida .= "						<td>PROFESIONAL</td>\n";
	$this->salida .= "						<td  colspan='2'>IDENTIFICACION</td>\n";
	$this->salida .= "						<td>EMPRESA</td>\n";
	$this->salida .= "					</tr>\n";
	$this->salida .= "					<tr class='modulo_list_oscuro'>\n";
	$this->salida .= "						<td>".$_SESSION['ManProf']['Profesional']['nombrep']."</td>\n";
	$this->salida .= "						<td>".$_SESSION['ManProf']['Profesional']['tipoid']."</td>\n";
	$this->salida .= "						<td>".$_SESSION['ManProf']['Profesional']['tercero']."</td>\n";
	$this->salida .= "						<td>".$_SESSION['ManProf']['nomemp']."</td>\n";
	$this->salida .= "					</tr>\n";
	$this->salida .= "				</table>\n";

	$this->salida .= "<br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
	$this->salida .= $this->SetStyle("MensajeError");
	$this->salida .= " </table>";


		$accion=ModuloGetURL('app','Profesionales','user','InsertarEspecialidad');
		$this->salida .= '<form name="volver" method="post" action="'.$accion.'">';
		$tipo_especialidad=$this->TipoEspecialidades();
		$especialidades=$this->Especialidad();
		$arr_esp=$this->Sub_Especialidad($_REQUEST['especialidad']);

		$this->salida .= "			      <table width=\"70%\" border=\"0\" align=\"center\">";

		$refresh=ModuloGetURL('app','Profesionales','user','frmAdicionarEspecialidad');
		$cadena ="\n<script language='javascript'>\n";
		$cadena .= "	function CargarPagina(href,valor) {\n";
		$cadena .= "		var url=href;\n";
		$cadena .= "		location.href=url+'&especialidad='+valor;\n";
		$cadena .= "	}\n\n";
		$cadena.="</script>\n";
		$this->salida .=$cadena;

		$this->salida .= "            <tr><td>";
		$this->salida .= "              <fieldset><legend class=\"field\">Creación de Especialidad</legend>";
		$this->salida .= "              <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "				       <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("emp")."\" width=\"40%\" align=\"left\">ESPECIALIDAD: </td><td class=\"modulo_list_oscuro\" align=\"left\">";
		$this->salida .= "<select name=especialidad class=select onchange=\"CargarPagina('$refresh',this.options[selectedIndex].value);\">";
		$this->salida .= '<option value="-1">--SELECCIONE--</option>';
		foreach($tipo_especialidad as $k=>$v)
		{
		  if($k==$_REQUEST['especialidad'])
			{
				$this->salida .= '<option value="'.$k.'" selected>'.$v.'</option>';
				$esp_id=$k;
				$desc=$v;
				$message=$k;
			}
			else
			{
				$this->salida .= '<option value="'.$k.'">'.$v.'</option>';
				$message=$k;
				//$esp_id='';
				//$desc='';
			}
		}
		$this->salida .= '</select>';
		$this->salida .= "	</td></tr>";

	  $this->salida .= "				       <tr  class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("tema")."\">SUB-ESPECIALIDAD: </td>";


		if(empty($_SESSION['PROF']['SUB_ESPECIALIDAD']))
		{

					if(is_array($arr_esp))
					{
						$this->salida .= "<td><select name=\"sub\" class=\"select\">";
						foreach($arr_esp as $r=>$s)
						{
							$this->salida .= '<option value="'.$r.'">'.$s.'</option>';
						}
						$this->salida .= '</select>';
					}
					else
					{
							$this->salida .= "<td><label class='label_mark'><sub>NO HAY SUB-ESPECIALIDAD</sub></label>";
					}

					if(!empty($_REQUEST['especialidad']) && $_REQUEST['especialidad'] !='-1')
					{
						$a=ModuloGetURL('app','Profesionales','user','frmAdicionarSubEspecialidad',array("especialidad"=>$esp_id,"descripcion"=>$desc));
						$this->salida .= "&nbsp;&nbsp;&nbsp;<a href='$a'><sub><img src=\"". GetThemePath() ."/images/especialidad.png\" border='0'>&nbsp;NUEVA SUB-ESPECIALIDAD</sub></a>";
					}
					$this->salida .= "	</td>";
		}
		else
		{
				$this->salida .= "<td>".$_SESSION['PROF']['SUB_ESPECIALIDAD']."</td>";
		}

		$this->salida .= "	</tr>";
		$this->salida .= "				       <tr  class=\"modulo_list_claro\"><td align=\"left\" class=\"".$this->SetStyle("loginUsuario")."\">UNIVERSIDAD: </td><td align=\"left\"><input type=\"text\" class=\"input-text\" name=\"uni\" maxlength=60 size=40 value=".$_REQUEST['uni']."></td></tr>";

		$this->salida .= "</select>&nbsp;&nbsp;&nbsp;<font><b></b></font></td></tr>";
		$this->salida .= "				       </tr>";
    $this->salida .= "			         </table>";
		$this->salida .= "		           </fieldset></td></tr></table>";
		$this->salida .='<br>';



	$this->salida .= '<br><table align="center" width="40%" border="0">';
	$this->salida .= '<tr>';
	$this->salida .= '<td align="center">';
	$this->salida .= '<input type="submit" name="GUARDAR" value="GUARDAR" class="input-submit">';
	$this->salida .= '</form>';
	$this->salida .= '</td>';

	$o=ModuloGetURL('app','Profesionales','user','CancelarProceso');
	$this->salida .= '<form name="volver" method="post" action="'.$o.'">';


	$this->salida .= '<td align="center">';
	$this->salida .= '<input type="submit" name="CANCELAR" value="CANCELAR" class="input-submit">';
	$this->salida .= '</form>';
	$this->salida .= '</td>';
	$this->salida .= '</tr>';
	$this->salida .= '</table>';
	$this->salida .= '<br>';
	$ac=ModuloGetURL('app','Profesionales','user','PantallaProfesional');
	$this->salida .= "<br><div align=center><a href='$ac'><sub>VOLVER</sub></a></div>";

	$this->salida .= ThemeCerrarTabla();
	return true;

}



function frmAdicionarSubEspecialidad()
{
	$esp_id=$_REQUEST['especialidad'];
	$desc=$_REQUEST['descripcion'];
	$this->salida=ThemeAbrirTabla('CREAR NUEVA SUB-ESPECIALIDAD');
	$this->salida .= "				<table align=center class='modulo_table_title' border='0' width='70%'>\n";
	$this->salida .= "					<tr class='modulo_table_title'>\n";
	$this->salida .= "						<td>PROFESIONAL</td>\n";
	$this->salida .= "						<td  colspan='2'>IDENTIFICACION</td>\n";
	$this->salida .= "						<td>EMPRESA</td>\n";
	$this->salida .= "					</tr>\n";
	$this->salida .= "					<tr class='modulo_list_oscuro'>\n";
	$this->salida .= "						<td>".$_SESSION['ManProf']['Profesional']['nombrep']."</td>\n";
	$this->salida .= "						<td>".$_SESSION['ManProf']['Profesional']['tipoid']."</td>\n";
	$this->salida .= "						<td>".$_SESSION['ManProf']['Profesional']['tercero']."</td>\n";
	$this->salida .= "						<td>".$_SESSION['ManProf']['nomemp']."</td>\n";
	$this->salida .= "					</tr>\n";
	$this->salida .= "				</table><br>\n";

	$this->salida .= " <table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
	$this->salida .= $this->SetStyle("MensajeError");
	$this->salida .= " </table>";


	$accion=ModuloGetURL('app','Profesionales','user','CrearSubEspecialidad',array("especialidad"=>$esp_id,"descripcion"=>$desc));
	$this->salida .= '<form name="volver" method="post" action="'.$accion.'">';

	$this->salida .= "              <table cellspacing=\"2\"  cellpadding=\"3\"border=\"1\" width=\"60%\" align=\"center\">";

	$this->salida .= "				       <tr  class=\"modulo_list_claro\"><td align=\"left\" class=\"".$this->SetStyle("loginUsuario")."\">ESPECIALIDAD: </td><td align=\"left\"><label class='label_mark'>$desc</label></td></tr>";

	$this->salida .= "				       <tr  class=\"modulo_list_claro\"><td align=\"left\" class=\"".$this->SetStyle("loginUsuario")."\">SUB-ESPECIALIDAD: </td><td align=\"left\"><input type=\"text\" class=\"input-text\" name=\"sub\" maxlength=60 size=40 value=".$_REQUEST['sub']."></td></tr>";

	$this->salida .= "</select>&nbsp;&nbsp;&nbsp;<font><b></b></font></td></tr>";
	$this->salida .= "				       </tr>";
	$this->salida .= "			         </table>";



	$this->salida .= '<br><table align="center" width="40%" border="0">';
	$this->salida .= '<tr>';
	$this->salida .= '<td align="center">';
	$this->salida .= '<input type="submit" name="GUARDAR" value="GUARDAR" class="input-submit">';
	$this->salida .= '</form></td>';
	$this->salida .= '</tr>';
	$this->salida .= '</table>';


	$ac=ModuloGetURL('app','Profesionales','user','frmAdicionarEspecialidad');
	$this->salida .= "<br><div align=center><a href='$ac'><sub>VOLVER</sub></a></div>";


	$this->salida .= ThemeCerrarTabla();
	return true;
}







	function PantallaProfesionalDepartamento()
	{
		$dato=$this->ProfesionalesBusquedaDepartamentos();
		if(!empty($dato))
		{
			$_SESSION['ManProf']['Profesional']['nombrep']=$dato['nombre_tercero'];
			$_SESSION['ManProf']['Profesional']['estado']=$dato['estado'];
			$_SESSION['ManProf']['Profesional']['Existe']=$dato['existe'];
		}
		else
		{
			return true;
		}
		$this->salida=ThemeAbrirTabla('INSCRIPCION O CAMBIO DE ESTADO DEL PROFESIONAL EN EL DEPARTAMENTO');
		$this->salida .='<br>';
		$this->salida .= '<table width="50%" align="center" border="0" class="modulo_table_list">';
		$this->salida .= '<tr align="center" class="modulo_table_title">';
		$this->salida .= '<td align="center">';
		$this->salida .= "Empresa";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Departamento";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= '<tr class="modulo_list_oscuro">';
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['ManProf']['nomemp'];
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['ManProf']['nomdep'];
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .= '<br>';
		$this->salida .= '<table width="55%" align="center" border="0" class="modulo_table_list">';
		$this->salida .= '<tr align="left" class="modulo_list_claro" width="30%">';
		$this->salida .= '<td width="20%" class="label" align="left">';
		$this->salida .= 'IDENTIFICACIÓN';
		$this->salida .= "</td>";
		$this->salida .= '<td align="left">';
		$this->salida .= $_SESSION['ManProf']['Profesional']['tipoid'].' - '.$_SESSION['ManProf']['Profesional']['tercero'];
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$accion=ModuloGetURL('app','Profesionales','user','DesicionDepartamento');
		$this->salida .= '<form name="volver" method="post" action="'.$accion.'">';
		$this->salida .= '<tr align="left" class="modulo_list_oscuro">';
		$this->salida .= '<td width="20%" class="label" align="left">';
		$this->salida .= 'NOMBRE';
		$this->salida .= "</td>";
		$this->salida .= '<td align="left">';
		$this->salida .= $_SESSION['ManProf']['Profesional']['nombrep'];
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= '<tr align="left" class="modulo_list_claro">';
		$this->salida .= '<td width="20%" class="label" align="left">';
		$this->salida .= 'ESTADO';
		$this->salida .= "</td>";
		$this->salida .= '<td align="left">';
		if('1'==$_SESSION['ManProf']['Profesional']['estado'])
		{
			$arr1['estado']=0;
			$arr1['tipoterceroe']=$_SESSION['ManProf']['Profesional']['tipoid'];
			$arr1['terceroe']=$_SESSION['ManProf']['Profesional']['tercero'];
			$this->salida .= "<a href=\"".ModuloGetURL('app','Profesionales','user','CambiarEstadoDepartamento',$arr1)."\"><img src=\"".$imagen=GetThemePath()."/images/activo.gif\" align=\"middle\" border=\"0\"></a>";
		}
		else
		{
			$arr1['estado']=1;
			$arr1['tipoterceroe']=$_SESSION['ManProf']['Profesional']['tipoid'];
			$arr1['terceroe']=$_SESSION['ManProf']['Profesional']['tercero'];
			$this->salida .= "<a href=\"".ModuloGetURL('app','Profesionales','user','CambiarEstadoDepartamento',$arr1)."\"><img src=\"".$imagen=GetThemePath()."/images/inactivo.gif\" align=\"middle\" border=\"0\"></a>";
		}
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .='<br>';
		$this->salida .= '<table align="center" width="70%" border="0">';
		$this->salida .= '<tr>';
		$this->salida .= '<td align="center">';
		if($_SESSION['ManProf']['Profesional']['Existe']==0)
		{
			$this->salida .= '<input type="submit" name="GUARDAR" value="GUARDAR" class="input-submit">&nbsp&nbsp&nbsp&nbsp;';
		}
		$this->salida .= '<input type="submit" name="VOLVER" value="VOLVER" class="input-submit">';
		$this->salida .= '</form>';
		$this->salida .= '</td>';
		$this->salida .= '</tr>';
		$this->salida .= '</table>';
		$this->salida .= ThemeCerrarTabla();
		return true;
	}


	function Pantalla_Asignar_dpto_Profesional()
	{

		if(empty($_SESSION['ManProf']['DATOS']))
		{
			if(empty($_SESSION['ManProf']['Profesional']['tercero']) and (!empty($_REQUEST['tercero']) or ($_REQUEST['tercero']!=$_SESSION['ManProf']['Profesional']['tercero'])))
			{
				$_SESSION['ManProf']['Profesional']['tercero']=$_REQUEST['tercero'];
				$_SESSION['ManProf']['Profesional']['tipoid']=$_REQUEST['tipoid'];
				$_SESSION['ManProf']['Profesional']['nombrep']=$_REQUEST['nombrep'];
				$_SESSION['ManProf']['Profesional']['TipoProf']=$_REQUEST['TipoProf'];
				$_SESSION['ManProf']['Profesional']['Sexo']=$_REQUEST['Sexo'];
				$_SESSION['ManProf']['Profesional']['TarjProf']=$_REQUEST['TarjProf'];
				$_SESSION['ManProf']['Profesional']['estado']=$_REQUEST['estado'];
				$_SESSION['ManProf']['Profesional']['universidad']=$_REQUEST['universidad'];
				$_SESSION['ManProf']['Profesional']['defuncion']=$_REQUEST['defuncion'];
				$_SESSION['ManProf']['Profesional']['registro_salud']=$_REQUEST['registro_salud'];
				$_SESSION['ManProf']['Profesional']['observacion']=$_REQUEST['observacion'];
				$_SESSION['ManProf']['Profesional']['circulante']=$_REQUEST['circulante'];
				$_SESSION['ManProf']['Profesional']['Existe']=1;
			}
		}
		else
		{
			$_SESSION['ManProf']['Profesional']['tercero']=$_SESSION['ManProf']['DATOS']['tercero_id'];
			$_SESSION['ManProf']['Profesional']['tipoid']=$_SESSION['ManProf']['DATOS']['tipo_id_tercero'];
			$_SESSION['ManProf']['Profesional']['nombrep']=$_SESSION['ManProf']['DATOS']['nombre_tercero'];
			$dato=$this->ProfesionalesBusqueda();
			if(!empty($dato))
			{
				$_SESSION['ManProf']['Profesional']['TipoProf']=$dato['tipo_profesional'];
				$_SESSION['ManProf']['Profesional']['Sexo']=$dato['sexo_id'];
				$_SESSION['ManProf']['Profesional']['TarjProf']=$dato['tarjeta_profesional'];
				$_SESSION['ManProf']['Profesional']['estado']=$dato['estado'];
				$_SESSION['ManProf']['Profesional']['universidad']=$dato['universidad'];
				$_SESSION['ManProf']['Profesional']['defuncion']=$dato['sw_registro_defuncion'];
				$_SESSION['ManProf']['Profesional']['registro_salud']=$dato['registro_salud_departamental'];
				$_SESSION['ManProf']['Profesional']['observacion']=$dato['observacion'];
				$_SESSION['ManProf']['Profesional']['circulante']=$dato['circulante'];
				$_SESSION['ManProf']['Profesional']['Existe']=1;
			}
			else
			{
				$_SESSION['ManProf']['Profesional']['Existe']=0;
			}
		}
		$this->salida=ThemeAbrirTabla('ASIGNAR DEPARTAMENTO');



		$this->salida .= "				<table align='center' class='modulo_table_title' border='0' width='80%'>\n";
		$this->salida .= "					<tr class='modulo_table_title'>\n";
		$this->salida .= "						<td>PROFESIONAL</td>\n";
		$this->salida .= "						<td colspan='2'>IDENTIFICACION</td>\n";
		$this->salida .= "						<td>EMPRESA</td>\n";
		$this->salida .= "					</tr>\n";
		$this->salida .= "					<tr class='modulo_list_oscuro'>\n";
		$this->salida .= "						<td>".$_SESSION['ManProf']['Profesional']['nombrep']."</td>\n";
		$this->salida .= "						<td>".$_SESSION['ManProf']['Profesional']['tipoid']."</td>\n";
		$this->salida .= "						<td>".$_SESSION['ManProf']['Profesional']['tercero']."</td>\n";
		$this->salida .= "						<td>".$_SESSION['ManProf']['nomemp']."</td>\n";
		$this->salida .= "					</tr>\n";
		$this->salida .= "				</table><br>\n";

  	$this->salida .= " <table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= " <table>";

		$action3=ModuloGetURL('app','Profesionales','user','Insertar_Profesional_Departamento',array("uid"=>$uid,'NombreUsuario'=>urlencode($NombreUsuario),'usuario'=>$Usuario,"empID"=>$empresa,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of'],'busqueda'=>$_REQUEST['busqueda']));
		$this->salida .= "           <form name=\"formas\" action=\"$action3\" method=\"post\">";
		$this->salida .= "<SCRIPT>";
		$this->salida .= "function chequeoTotal(frm,x){";
		$this->salida .= "  if(x==true){";
		$this->salida .= "    for(i=0;i<frm.elements.length;i++){";
		$this->salida .= "      if(frm.elements[i].type=='checkbox'){";
		$this->salida .= "        frm.elements[i].checked=true";
		$this->salida .= "      }";
		$this->salida .= "    }";
		$this->salida .= "  }else{";
		$this->salida .= "    for(i=0;i<frm.elements.length;i++){";
		$this->salida .= "      if(frm.elements[i].type=='checkbox'){";
		$this->salida .= "        frm.elements[i].checked=false";
		$this->salida .= "      }";
		$this->salida .= "    }";
		$this->salida .= "  }";
		$this->salida .= "}";

		$this->salida .= "function Pintartd(clrIn,i,x){\n";
		$this->salida .= "  if(x==true){\n";
		$this->salida .= "document.getElementById(i).style.background = '#7A99BB';\n";
		$this->salida .= "    }\n";
		$this->salida .= "  else{\n";
		$this->salida .= "document.getElementById(i).style.background = clrIn;\n";

		$this->salida .= "  }\n";
		$this->salida .= "}\n";
		$this->salida .= "</SCRIPT>";
		$this->salida.="<table  align=\"center\" border=\"0\" class=\"modulo_table_list\" width=\"80%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"left\" colspan=\"4\">PERMISOS DEPARTAMENTOS</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		//$this->salida.="  <td width=\"20%\">Empresa</td>";
		$this->salida.="  <td width=\"20%\">Centro Utilidad</td>";
		$this->salida.="  <td width=\"20%\">Unidad Funcional</td>";
		$this->salida.="  <td width=\"20%\">Departamento</td>";
		$this->salida.="  <td width=\"6%\">Todos&nbsp;<input type=checkbox name=sel onclick=chequeoTotal(this.form,this.checked)></td>";
		$this->salida.="<input type='hidden' name='emp' value=".$_REQUEST['empresa'].">";
		$this->salida.="</tr>";

		$vector=$this->Traer_Informacion($_SESSION['ManProf']['empresa'],$_SESSION['ManProf']['Profesional']['tercero'],$_SESSION['ManProf']['Profesional']['tipoid']);
 		if( $e % 2){ $estilo='modulo_list_claro';}
		else {$estilo='modulo_list_oscuro';}
		$this->salida.="<tr align=\"center\">";
		//$this->salida.="  <td width=\"10%\" class=\"$estilo\">".$_SESSION['ManProf']['nomemp']."</td>";
		$this->salida.="  <td colspan=\"4\">";
		for($i=0; $i<sizeof($vector);)
		{
				if( $i % 2){$estilo='modulo_list_oscuro';}
				else { $estilo='modulo_list_claro';}
				$this->salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
				$this->salida.="  <tr>";
				$this->salida.="  <td  class=\"$estilo\" width=\"27%\" align=\"center\">".$vector[$i][centro]."</td>";
				$d=$i;

				$this->salida.="  <td>";
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";

				while($vector[$i][centro]==$vector[$d][centro])
				{
							if( $i % 2){$estilo1='modulo_list_oscuro';}
							else { $estilo1='modulo_list_claro';}

						$this->salida.="  <tr>";
						$this->salida.="  <td class=\"$estilo1\" align=\"center\" width=\"38%\">".$vector[$d][unidad]."</td>";
						$f=$d;
						$this->salida.="  <td class=\"$estilo1\">";
						$this->salida.="<table  align=\"center\" border=\"0\" width=\"100%\">";
						while($vector[$d][unidad]==$vector[$f][unidad])
						{
								$check=$vector[$f][tercero_id];
								if($check)
								{
								$chequeo='checked';
								}
								else
								{
								$chequeo='';
								}
								if( $f % 2){ $estilo='modulo_list_claro';$color='#DDDDDD';}
								else {$estilo='modulo_list_oscuro';$color='#CCCCCC';}
								$this->salida.="  <tr class=$estilo id='$f'>";
								$this->salida.="  <td  width=\"20\" align=\"center\">".$vector[$f][descripcion]."</td>";
								$this->salida.="  <td width=\"6%\" align=\"right\"><input type=checkbox name=op[$f] value=".$vector[$f][departamento]." $chequeo></td>";
								$this->salida.="  </tr>";
								$f++;
						}
						$this->salida.="  </table>";
						$this->salida.="  </td>";
						$this->salida.="  </tr>";
						$d=$f;
				}
				$this->salida.="  </table>";
				$i=$d;
				$this->salida.="  </tr>";
				$this->salida.="  </table>";
		}
				$this->salida.=" </td>";
				$this->salida.="</tr>";


		$this->salida.="</table>";
		$this->salida.="</table>";
		$this->salida.="<table align=\"center\" width='40%' border=\"0\">";
		$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"guardar\" type=\"submit\" value=\"Guardar\"></form></td>";
		$action2=ModuloGetURL('app','Profesionales','user','ListarProfe',array("uid"=>$uid,'nombre'=>urldecode($NombreUsuario),'usuario'=>$Usuario,"empID"=>$empresa,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of'],'busqueda'=>$_REQUEST['busqueda']));
		$this->salida .= "           <form name=\"forma\" action=\"$action2\" method=\"post\">";
		$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
		$this->salida .= "</tr>";
		$this->salida.="</table><br>";
		$this->salida .= ThemeCerrarTabla();
		return true;
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

	//fin de funciones creacion profesionales

}
?>
