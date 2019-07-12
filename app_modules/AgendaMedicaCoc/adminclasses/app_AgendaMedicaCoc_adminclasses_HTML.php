<?php

/**
 * $Id: app_AgendaMedicaCoc_adminclasses_HTML.php,v 1.1 2009/09/02 13:08:12 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_AgendaMedicaCoc_adminclasses_HTML extends app_AgendaMedicaCoc_admin
{

	function app_AgendaMedicaCoc_admin_HTML()
	{
	    $this->app_AgendaMedica_admin(); //Constructor del padre 'modulo'
		$this->salida='';
		return true;
	}


	function Menu()
	{
		$this->salida = ThemeAbrirTabla('CONSULTA EXTERNA');
		$this->salida .= "<br>";
		$this->salida .= "<table width=\"40%\" border=\"1\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
		$this->salida .= "<tr>";
		$this->salida .= "<td align=\"center\" class=\"modulo_table_list_title\">MENU ADMINISTRATIVO DE CONSULTA EXTERNA</td>";
		$this->salida .= "</tr>";
		$this->salida .='<tr class="modulo_list_claro">';
		$this->salida .='<td align="center">';
		$accion=ModuloGetURL('app','AgendaMedicaCoc','admin','CreacionAgenda');
		$this->salida .='<a href="'.$accion.'">Creación Agenda Medica</a>';
		$this->salida .='</td>';
		$this->salida .='</tr>';
		$this->salida .='<tr class="modulo_list_oscuro">';
		$this->salida .='<td align="center">';
		$accion=ModuloGetURL('app','AgendaMedicaCoc','admin','CambiarBorrarAgenda');
		$this->salida .='<a href="'.$accion.'">Cambiar y Borrar Agenda Medica</a>';
		$this->salida .='</td>';
		$this->salida .='</tr>';
		$this->salida .='<tr class="modulo_list_claro">';
		$this->salida .='<td align="center">';
		$accion=ModuloGetURL('app','AgendaMedicaCoc','admin','MantenimientoProfesionales');
		$this->salida .='<a href="'.$accion.'">Mantenimiento Profesionales</a>';
		$this->salida .='</td>';
		$this->salida .='</tr>';
		$this->salida .='</table>';
		$this->salida .='<br>';
		$this->salida .= ThemeCerrarTabla();
		unset($_SESSION['CreacionAgenda']);
		return true;
	}

//Creación de Agenda Medica

	function CreacionAgenda()
	{
		if($_REQUEST['volver1']=='true')
		{
			unset($_SESSION['CreacionAgenda']);
		}
		if(empty($_SESSION['CreacionAgenda']['Cita']))
		{
			$_SESSION['CreacionAgenda']['Cita']=$_REQUEST['Citas']['tipo_consulta_id'];
			$_SESSION['CreacionAgenda']['nomemp']=$_REQUEST['Citas']['descripcion1'];
			$_SESSION['CreacionAgenda']['nomdep']=$_REQUEST['Citas']['descripcion2'];
			$_SESSION['CreacionAgenda']['nombre']=$_REQUEST['Citas']['descripcion3'];
			$_SESSION['CreacionAgenda']['empresa']=$_REQUEST['Citas']['empresa_id'];
			$_SESSION['CreacionAgenda']['departamento']=$_REQUEST['Citas']['departamento'];
		}
		if(empty($_SESSION['CreacionAgenda']['Cita']))
		{
			$url[0]='app';
			$url[1]='AgendaMedicaCoc';
			$url[2]='admin';
			$url[3]='CreacionAgenda';
			$url[4]='Citas';
			$Cita=$this->CitaConsulta($url);
			if($Cita==false)
			{
				return false;
			}
		}
		else
		{
			if($_REQUEST['volver2']=='true')
			{
				$_SESSION['CreacionAgenda']['tercero']='';
			}
			if(empty($_SESSION['CreacionAgenda']['tercero']))
			{
				$_SESSION['CreacionAgenda']['tercero']=$_REQUEST['tercero'];
				$_SESSION['CreacionAgenda']['tipoid']=$_REQUEST['tipoid'];
				$_SESSION['CreacionAgenda']['nombrep']=$_REQUEST['nombrep'];
			}
			if(empty($_SESSION['CreacionAgenda']['tercero']))
			{
				$profesionales=$this->Profesionales();
				if($profesionales)
				{
					$this->salida  = ThemeAbrirTabla('CREACIÓN AGENDA MEDICA');
					$this->salida .='<br>';
					$this->salida .= '<table width="70%" align="center" class="modulo_table_list">';
					$this->salida .= '<tr align="center" class="modulo_table_title">';
					$this->salida .= '<td align="center">';
					$this->salida .= "Empresa";
					$this->salida .= "</td>";
					$this->salida .= '<td align="center">';
					$this->salida .= "Departamento";
					$this->salida .= "</td>";
					$this->salida .= '<td align="center">';
					$this->salida .= "Tipo de Cita";
					$this->salida .= "</td>";
					$this->salida .= "</tr>";
					$this->salida .= '<tr class="modulo_list_oscuro">';
					$this->salida .= '<td align="center">';
					$this->salida .= $_SESSION['CreacionAgenda']['nomemp'];
					$this->salida .= "</td>";
					$this->salida .= '<td align="center">';
					$this->salida .= $_SESSION['CreacionAgenda']['nomdep'];
					$this->salida .= "</td>";
					$this->salida .= '<td align="center">';
					$this->salida .= $_SESSION['CreacionAgenda']['nombre'];
					$this->salida .= "</td>";
					$this->salida .= "</tr>";
					$this->salida .= "</table>";
					$this->salida .= '<br>';
					$this->salida .= '<table align="center" width="70%" border="0" class="modulo_table_list">';
					$this->salida .= '<tr align="center" class="modulo_table_title">';
					$this->salida .= '<td>';
					$this->salida .= 'Profesionales';
					$this->salida .= '</td>';
					$this->salida .= '<td>';
					$this->salida .= '</td>';
					$this->salida .= '</tr>';
					$i=0;
					while($i<sizeof($profesionales[0]))
					{
						if($spy==0)
						{
							$this->salida .= '<tr class="modulo_list_claro">';
							$spy=1;
						}
						else
						{
							$this->salida .= '<tr class="modulo_list_oscuro">';
							$spy=0;
						}
						$this->salida .= '<td>';
						$this->salida .= $profesionales[2][$i];
						$this->salida .= '</td>';
						$this->salida .= '<td>';
						$this->salida .= '<a href="'.ModuloGetURL('app','AgendaMedicaCoc','admin','CreacionAgenda',array('tercero'=>$profesionales[1][$i],'tipoid'=>$profesionales[0][$i],'nombrep'=>$profesionales[2][$i])).'">Ir</a>';
						$this->salida .= '</td>';
						$this->salida .= '</tr>';
						$i++;
					}
					$this->salida .= '</table>';
					$this->salida .= '<br>';
					$this->salida .= '<table align="center" width="70%" border="0">';
					$this->salida .= '<tr>';
					$this->salida .= '<td align="center">';
					$accion=ModuloGetURL('app','AgendaMedicaCoc','admin','CreacionAgenda',array('volver1'=>'true'));
					$this->salida .= '<form name="Volver" method="post" action="'.$accion.'">';
					$this->salida .= '<input type="submit" name="volver" value="Volver" class="input-submit">';
					$this->salida .= '</form>';
					$this->salida .= '</td>';
					$this->salida .= '</tr>';
					$this->salida .= '</table>';
					$this->salida .= ThemeCerrarTabla();
				}
				else
				{
					return false;
				}
			}
			else
			{
				if(empty($_REQUEST['accion']))
				{
					$this->salida  = ThemeAbrirTabla('CREACIÓN AGENDA MEDICA');
					$this->salida .='<br>';
					$this->salida .= '<table width="70%" align="center" class="modulo_table_list">';
					$this->salida .= '<tr align="center" class="modulo_table_title">';
					$this->salida .= '<td align="center">';
					$this->salida .= "Empresa";
					$this->salida .= "</td>";
					$this->salida .= '<td align="center">';
					$this->salida .= "Departamento";
					$this->salida .= "</td>";
					$this->salida .= '<td align="center">';
					$this->salida .= "Tipo de Cita";
					$this->salida .= "</td>";
					$this->salida .= "</tr>";
					$this->salida .= '<tr class="modulo_list_oscuro">';
					$this->salida .= '<td align="center">';
					$this->salida .= $_SESSION['CreacionAgenda']['nomemp'];
					$this->salida .= "</td>";
					$this->salida .= '<td align="center">';
					$this->salida .= $_SESSION['CreacionAgenda']['nomdep'];
					$this->salida .= "</td>";
					$this->salida .= '<td align="center">';
					$this->salida .= $_SESSION['CreacionAgenda']['nombre'];
					$this->salida .= "</td>";
					$this->salida .= "</tr>";
					$this->salida .= "</table>";
					$this->salida .='<br>';
					$this->salida .= '<table width="70%" align="center" class="modulo_table_list">';
					$this->salida .= '<tr align="center" class="modulo_table_title">';
					$this->salida .= '<td align="center">';
					$this->salida .= "Profesional";
					$this->salida .= "</td>";
					$this->salida .= "</tr>";
					$this->salida .= '<tr class="modulo_list_oscuro">';
					$this->salida .= '<td align="center">';
					$this->salida .= $_SESSION['CreacionAgenda']['nombrep'];
					$this->salida .= "</td>";
					$this->salida .= "</tr>";
					$this->salida .= "</table>";
					$this->salida .='<br>';
					$this->salida .= '<table border="0" width="100%" align="center" class="modulo_table">';
					$this->salida .= '<tr align="center">';
					$this->salida .= '<td class="label_error" colspan="2">';
					$this->salida .='<br>';
					$this->salida .= '</td>';
					$this->salida .= '</tr>';
					$this->salida .= '<tr>';
					$this->salida .= '<td align="right" width="50%">';
					$accion=ModuloGetURL('app','AgendaMedicaCoc','admin','CreacionAgenda',array('volver2'=>'true'));
					$this->salida .='<form name="volver" action="'.$accion.'" method="post">';
					$this->salida .='<input type="submit" name="volver" value="volver" class="input-submit">';
					$this->salida .='</form>';
					$this->salida .= '</td>';
					$this->salida .= '<td align="left" width="50%">';
					$accion=ModuloGetURL('app','AgendaMedicaCoc','admin','CreacionAgenda',array('accion'=>'add'));
					$this->salida .='<form name="volver" action="'.$accion.'" method="post">';
					$this->salida .='<input type="submit" name="crear" value="Crear Agenda" class="input-submit">';
					$this->salida .='</form>';
					$this->salida .= '</td>';
					$this->salida .= '</tr>';
					$turnos=$this->TurnosProgramados();
					if($turnos==0)
					{
						$this->salida .= '<tr align="center">';
						$this->salida .= '<td class="label_error" colspan="2">';
						$this->salida .='<br>';
						$this->salida .= 'No existe agenda para este profesional';
						$this->salida .= '</td>';
						$this->salida .= '</tr>';
					}
					else
					{
						$this->salida .= '<tr align="center">';
						$this->salida .= '<td class="label" colspan="2">';
						$this->salida .='<br>';
						foreach($_REQUEST as $v=>$datos)
						{
							if($v!='modulo' and $v!='SIIS_SID' and $v!='tipo' and $v!='metodo' and $v!='saber')
							{
								$vec[$v]=$datos;
							}
						}
						$vec['saber']=1;
						$accion=ModuloGetURL('app','AgendaMedicaCoc','admin','CreacionAgenda',$vec);
						$this->salida .= '<a href="'.$accion.'">Ver Turnos</a>';
						$this->salida .= '</td>';
						$this->salida .= '</tr>';
					}
					$this->salida .= '</table>';
					$this->salida .='<br>';
					if($turnos and $_REQUEST['saber']==1)
					{
						$this->salida .= '<table width="70%" align="center" class="modulo_table_list" border="1" cellpadding="4">';
						$this->salida .= '<tr align="center" class="modulo_table_title">';
						$this->salida .= '<td align="center" colspan="3">';
						$this->salida .= 'Fecha';
						$this->salida .= "</td>";
						$this->salida .= '<td align="center">';
						$this->salida .= 'Hora Inicio';
						$this->salida .= "</td>";
						$this->salida .= '<td align="center">';
						$this->salida .= 'Hora Fin';
						$this->salida .= "</td>";
						$this->salida .= "</tr>";
						$i=0;
						while($i<sizeof($turnos[0]))
						{
							$this->salida.='<tr>';
							$a=array_keys($turnos[0],$turnos[0][$i]);
							if($turnos[0][$i]!=$b)
							{
								$c=0;
							}
							if(sizeof($a)!=1 and $c==0)
							{
								$b=$turnos[0][$i];
								$this->salida.='<td rowspan="'.sizeof($a).'" class="modulo_list_oscuro" valign="top">';
								$this->salida.=$turnos[0][$i];
								$this->salida.='</td>';
								$c=1;
							}
							else
							{
								if(sizeof($a)==1)
								{
									$c=0;
									$this->salida.='<td class="modulo_list_oscuro">';
									$this->salida.=$turnos[0][$i];
									$this->salida.='</td>';
								}
							}
							$d=array_keys($turnos[1],$turnos[1][$i]);
							if($turnos[1][$i]!=$e)
							{
								$f=0;
							}
							if(sizeof($d)!=1 and $f==0)
							{
								$e=$turnos[1][$i];
								$this->salida.='<td rowspan="'.sizeof($d).'" class="modulo_list_oscuro" valign="top">';
								$k=explode("-",$turnos[1][$i]);
								$this->salida.=$k[1];
								$this->salida.='</td>';
								$f=1;
							}
							else
							{
								if(sizeof($d)==1)
								{
									$f=0;
									$this->salida.='<td class="modulo_list_oscuro">';
									$k=explode("-",$turnos[1][$i]);
									$this->salida.=$k[1];
									$this->salida.='</td>';
								}
							}
							$g=array_keys($turnos[2],$turnos[2][$i]);
							if($turnos[2][$i]!=$h)
							{
								$j=0;
							}
							if(sizeof($g)!=1 and $j==0)
							{
								$h=$turnos[2][$i];
								$this->salida.='<td rowspan="'.sizeof($g).'" class="modulo_list_oscuro" valign="top">';
								$k=explode("-",$turnos[2][$i]);
								$this->salida.=$k[2];
								$this->salida.='</td>';
								$j=1;
							}
							else
							{
								if(sizeof($g)==1)
								{
									$j=0;
									$this->salida.='<td class="modulo_list_oscuro">';
									$k=explode("-",$turnos[2][$i]);
									$this->salida.=$k[2];
									$this->salida.='</td>';
								}
							}
							if($spy2==0)
							{
								$this->salida.='<td class="modulo_list_claro" align="center">';
							}
							else
							{
								$this->salida.='<td class="modulo_list_oscuro" align="center">';
							}
							$this->salida .= $turnos[3][$i];
							$this->salida .= "</td>";
							if($spy2==0)
							{
								$this->salida.='<td class="modulo_list_claro" align="center">';
								$spy2=1;
							}
							else
							{
								$this->salida.='<td class="modulo_list_oscuro" align="center">';
								$spy2=0;
							}
							$this->salida .= $turnos[4][$i];
							$this->salida .= "</td>";
							$i++;
						}
						$this->salida .= '</table>';
						$this->salida .='<br>';
					}
					$this->salida .= ThemeCerrarTabla();
				}
				else
				{
					if($_REQUEST['accion']=='add' and empty($_REQUEST['guardar']))
					{
						if(empty($_REQUEST['Enviar']))
						{
							SessionDelVar('FECHAS');
							SessionDelVar('CITASMES');
							$this->salida  = ThemeAbrirTabla('CREACIÓN AGENDA MEDICA');
							$this->salida .='<script LANGUAGE="JavaScript">';
							$this->salida .='function mesesdias(h)';
							$this->salida .='{';
								$this->salida .='if(h.elements[9].checked==true)';
								$this->salida .='{';
									$this->salida .='for (var i=0 ; i < h.elements.length ; i++)';
									$this->salida .='{';
										$this->salida .='if(h.elements[i].disabled==false)';
										$this->salida .='{';
											$this->salida .='if((i!=9) && (i!=11) && (i!=12) && (i!=13))';
											$this->salida .='{';
												$this->salida .='if((i<32))';
												$this->salida .='{';
													$this->salida .='h.elements[i].checked=true;';
												$this->salida .='}';
												$this->salida .='if(i>43 && i<52)';
												$this->salida .='{';
													$this->salida .='h.elements[i].checked=true;';
												$this->salida .='}';
												$this->salida .='if(i>63 && i<65)';
												$this->salida .='{';
													$this->salida .='h.elements[i].checked=true;';
												$this->salida .='}';
												$this->salida .='if(i>76 && i<79)';
												$this->salida .='{';
													$this->salida .='h.elements[i].checked=true;';
												$this->salida .='}';
												$this->salida .='if(i>90 && i<95)';
												$this->salida .='{';
													$this->salida .='h.elements[i].checked=true;';
												$this->salida .='}';
												$this->salida .='if(i>106 && i<109)';
												$this->salida .='{';
													$this->salida .='h.elements[i].checked=true;';
												$this->salida .='}';
												$this->salida .='if(i>120)';
												$this->salida .='{';
													$this->salida .='h.elements[i].checked=true;';
												$this->salida .='}';
											$this->salida .='}';
										$this->salida .='}';
									$this->salida .='}';
								$this->salida .='}';
								$this->salida .='else';
								$this->salida .='{';
									$this->salida .='for (var i=0 ; i < h.elements.length ; i++)';
									$this->salida .='{';
									$this->salida .='if((i!=9) && (i!=11) && (i!=12) && (i!=13))';
											$this->salida .='{';
												$this->salida .='if((i<32))';
												$this->salida .='{';
													$this->salida .='h.elements[i].checked=false;';
												$this->salida .='}';
												$this->salida .='if(i>43 && i<52)';
												$this->salida .='{';
													$this->salida .='h.elements[i].checked=false;';
												$this->salida .='}';
												$this->salida .='if(i>63 && i<65)';
												$this->salida .='{';
													$this->salida .='h.elements[i].checked=false;';
												$this->salida .='}';
												$this->salida .='if(i>76 && i<79)';
												$this->salida .='{';
													$this->salida .='h.elements[i].checked=false;';
												$this->salida .='}';
												$this->salida .='if(i>90 && i<95)';
												$this->salida .='{';
													$this->salida .='h.elements[i].checked=false;';
												$this->salida .='}';
												$this->salida .='if(i>106 && i<109)';
												$this->salida .='{';
													$this->salida .='h.elements[i].checked=false;';
												$this->salida .='}';
												$this->salida .='if(i>120)';
												$this->salida .='{';
													$this->salida .='h.elements[i].checked=false;';
												$this->salida .='}';
											$this->salida .='}';
									$this->salida .='}';
								$this->salida .='}';
							$this->salida .='}'."\n";
							$this->salida .='function dias(h)';
							$this->salida .='{';
								$this->salida .='if(h.elements[10].checked==true)';
								$this->salida .='{';
									$this->salida .='for (var i=0 ; i < h.elements.length ; i++)';
									$this->salida .='{';
										$this->salida .='if(h.elements[i].disabled==false)';
										$this->salida .='{';
											$this->salida .='if((i!=10) && (i!=9) && (i!=11) && (i!=12) && (i!=13) && (i<32) && (i!=0) && (i!=14) && (i!=23) && (i!=32))';
											$this->salida .='{';
													$this->salida .='h.elements[i].checked=true;';
											$this->salida .='}';
											$this->salida .='if((i>44) && (i<52))';
											$this->salida .='{';
											$this->salida .='h.elements[i].checked=true;';
											$this->salida .='}';
										$this->salida .='}';
									$this->salida .='}';
								$this->salida .='}';
								$this->salida .='else';
								$this->salida .='{';
									$this->salida .='for (var i=0 ; i < h.elements.length ; i++)';
									$this->salida .='{';
									$this->salida .='if((i!=10) && (i!=9) && (i!=11) && (i!=12) && (i!=13) && (i<32) && (i!=0) && (i!=14) && (i!=23) && (i!=32))';
											$this->salida .='{';
													$this->salida .='h.elements[i].checked=false;';
											$this->salida .='}';
											$this->salida .='if((i>44) && (i<52))';
											$this->salida .='{';
											$this->salida .='h.elements[i].checked=false;';
											$this->salida .='}';
									$this->salida .='}';
								$this->salida .='}';
							$this->salida .='}'."\n";
							$this->salida .='function sabados(h)';
							$this->salida .='{';
							$this->salida .='if(h.elements[11].checked==true)';
							$this->salida .='{';
							$this->salida .='h.elements[124].disabled=true;';
							$this->salida .='h.elements[124].checked=false;';
							$this->salida .='}';
							$this->salida .='else';
							$this->salida .='{';
							$this->salida .='h.elements[124].disabled=false;';
							$this->salida .='h.elements[124].checked=false;';
							$this->salida .='}';
							$this->salida .='}'."\n";
							$this->salida .='function domingos(h)';
							$this->salida .='{';
							$this->salida .='if(h.elements[12].checked==true)';
							$this->salida .='{';
							$this->salida .='h.elements[128].disabled=true;';
							$this->salida .='h.elements[128].checked=false;';
							$this->salida .='}';
							$this->salida .='else';
							$this->salida .='{';
							$this->salida .='h.elements[128].disabled=false;';
							$this->salida .='h.elements[128].checked=false;';
							$this->salida .='}';
							$this->salida .='}'."\n";
							$this->salida .='</script>';
							$this->salida .='<br>';
							if($_REQUEST['a']>0)
							{
								$a=date("Y",mktime(0,0,0,1,1,(date("Y")+$_REQUEST['a'])));
								$i=0;
							}
							else
							{
								$a=date("Y");
								$_REQUEST['a']=0;
								$i=date("n");
								if($i==12)
								{
									$s=date("j");
									if($s==31)
									{
										$s=0;
										$a++;
										$i=0;
									}
									$s--;
									$i--;
								}
								else
								{
									$i--;
								}
							}
							$this->salida .='<br>';
							$this->salida .= '<table width="70%" align="center" class="modulo_table_list">';
							$this->salida .= '<tr align="center" class="modulo_table_title">';
							$this->salida .= '<td align="center">';
							$this->salida .= "Empresa";
							$this->salida .= "</td>";
							$this->salida .= '<td align="center">';
							$this->salida .= "Departamento";
							$this->salida .= "</td>";
							$this->salida .= '<td align="center">';
							$this->salida .= "Tipo de Cita";
							$this->salida .= "</td>";
							$this->salida .= "</tr>";
							$this->salida .= '<tr class="modulo_list_oscuro">';
							$this->salida .= '<td align="center">';
							$this->salida .= $_SESSION['CreacionAgenda']['nomemp'];
							$this->salida .= "</td>";
							$this->salida .= '<td align="center">';
							$this->salida .= $_SESSION['CreacionAgenda']['nomdep'];
							$this->salida .= "</td>";
							$this->salida .= '<td align="center">';
							$this->salida .= $_SESSION['CreacionAgenda']['nombre'];
							$this->salida .= "</td>";
							$this->salida .= "</tr>";
							$this->salida .= "</table>";
							$this->salida .='<br>';
							$this->salida .= '<table width="70%" align="center" class="modulo_table_list">';
							$this->salida .= '<tr align="center" class="modulo_table_title">';
							$this->salida .= '<td align="center">';
							$this->salida .= "Profesional";
							$this->salida .= "</td>";
							$this->salida .= "</tr>";
							$this->salida .= '<tr class="modulo_list_oscuro">';
							$this->salida .= '<td align="center">';
							$this->salida .= $_SESSION['CreacionAgenda']['nombrep'];
							$this->salida .= "</td>";
							$this->salida .= "</tr>";
							$this->salida .= "</table>";
							$this->salida .='<br>';
							$this->salida .= '<table width="50%" align="center" class="modulo_table" border="0">';
							$this->salida .= '<tr>';
							$this->salida .= '<td align="center">';
							if($a<>date("Y"))
							{
								$this->salida .= '<a href="'.ModuloGetURL('app','AgendaMedicaCoc','admin','CreacionAgenda',array('accion'=>'add','a'=>'0')).'">actual</a>';
							}
							$this->salida .= '<a href="'.ModuloGetURL('app','AgendaMedicaCoc','admin','CreacionAgenda',array('accion'=>'add','a'=>($_REQUEST['a']-1))).'"><<</a> '.$a.' <a href="'.ModuloGetURL('app','AgendaMedicaCoc','admin','CreacionAgenda',array('accion'=>'add','a'=>($_REQUEST['a']+1))).'">>></a>';
							$this->salida .= '</td>';
							$this->salida .= '</tr>';
							$this->salida .= '</table>';
							$this->salida .= '<br>';
							$this->salida .= '<form name="cosa" action="'.ModuloGetURL('app','AgendaMedicaCoc','admin','CreacionAgenda',array('accion'=>'add','a'=>$_REQUEST['a'])).'" method="post">';
							$this->salida .= '<table width="90%" align="center" border="1" class="modulo_table">';
							$this->salida .= '<tr class="modulo_table_title">';
							$this->salida .= '<td colspan="2" align="center">';
							$this->salida .= 'Meses';
							$this->salida .= '</td>';
							$this->salida .= '<td colspan="8">';
							$this->salida .= 'Dias del Mes';
							$this->salida .= '</td>';
							$this->salida .= '<td align="center" colspan="6">';
							$this->salida .= 'Todos los días';
							$this->salida .= '</td>';
							$this->salida .= '<td align="center" colspan="6">';
							$this->salida .= 'Exclusiones';
							$this->salida .= '</td>';
							$this->salida .= '</tr>';
							$this->salida .= '<tr>';
							$this->salida .= '<td>';
							$mes=1;
							$dias=1;
							$semana=0;
							$horaini=0;
							$horafin=0;
							$minutosini=0;
							$minutosfin=0;
							$this->salida .= '<input type="checkbox" name="mes'.$mes.'" value="'.$mes.'"';
							$mes++;
							if($i<=0)
							{
								$this->salida .= '>';
							}
							else
							{
								$this->salida .= ' disabled="true">';
								$i--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td>';
							$this->salida .= 'Enero';
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
							$dias++;
							if($s<=0)
							{
								$this->salida .= '><br>1';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>1';
								$s--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
							$dias++;
							if($s<=0)
							{
								$this->salida .= '><br>2';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>2';
								$s--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
							$dias++;
							if($s<=0)
							{
								$this->salida .= '><br>3';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>3';
								$s--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
							$dias++;
							if($s<=0)
							{
								$this->salida .= '><br>4';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>4';
								$s--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
							$dias++;
							if($s<=0)
							{
								$this->salida .= '><br>5';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>5';
								$s--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
							$dias++;
							if($s<=0)
							{
								$this->salida .= '><br>6';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>6';
								$s--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
							$dias++;
							if($s<=0)
							{
								$this->salida .= '><br>7';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>7';
								$s--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
							$dias++;
							if($s<=0)
							{
								$this->salida .= '><br>8';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>8';
								$s--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td colspan="4">';
							$this->salida .= '<input type="checkbox" name="todos" onclick="mesesdias(this.form)">Meses y Días';
							$this->salida .= '</td>';
							$this->salida .= '<td colspan="2">';
							$this->salida .= '<input type="checkbox" name="todos" onclick="dias(this.form)">Días';
							$this->salida .= '</td>';
							$this->salida .= '<td>';
							$this->salida .= '<input type="checkbox" name="nosabados" onclick="sabados(this.form)">';
							$this->salida .= '</td>';
							$this->salida .= '<td>';
							$this->salida .= 'Sab';
							$this->salida .= '</td>';
							$this->salida .= '<td>';
							$this->salida .= '<input type="checkbox" name="nodomingos" onclick="domingos(this.form)">';
							$this->salida .= '</td>';
							$this->salida .= '<td>';
							$this->salida .= 'Dom';
							$this->salida .= '</td>';
							$this->salida .= '<td>';
							$this->salida .= '<input type="checkbox" name="nofestivos">';
							$this->salida .= '</td>';
							$this->salida .= '<td>';
							$this->salida .= 'Fes';
							$this->salida .= '</td>';
							$this->salida .= '</tr>';
							$this->salida .= '<tr>';
							$this->salida .= '<td>';
							$this->salida .= '<input type="checkbox" name="mes'.$mes.'" value="'.$mes.'"';
							$mes++;
							if($i<=0)
							{
								$this->salida .= '>';
							}
							else
							{
								$this->salida .= ' disabled="true">';
								$i--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td>';
							$this->salida .= 'Febrero';
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
							$dias++;
							if($s<=0)
							{
								$this->salida .= '><br>9';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>9';
								$s--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
							$dias++;
							if($s<=0)
							{
								$this->salida .= '><br>10';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>10';
								$s--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
							$dias++;
							if($s<=0)
							{
								$this->salida .= '><br>11';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>11';
								$s--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
							$dias++;
							if($s<=0)
							{
								$this->salida .= '><br>12';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>12';
								$s--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
							$dias++;
							if($s<=0)
							{
								$this->salida .= '><br>13';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>13';
								$s--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
							$dias++;
							if($s<=0)
							{
								$this->salida .= '><br>14';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>14';
								$s--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
							$dias++;
							if($s<=0)
							{
								$this->salida .= '><br>15';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>15';
								$s--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
							$dias++;
							if($s<=0)
							{
								$this->salida .= '><br>16';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>16';
								$s--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td colspan="6" class="modulo_table_title">';
							$this->salida .= 'Hora Comienzo Turno';
							$this->salida .= '</td>';
							$this->salida .= '<td colspan="6" class="modulo_table_title">';
							$this->salida .= 'Hora Fin Turno';
							$this->salida .= '</td>';
							$this->salida .= '</tr>';
							$this->salida .= '<tr>';
							$this->salida .= '<td>';
							$this->salida .= '<input type="checkbox" name="mes'.$mes.'" value="'.$mes.'"';
							$mes++;
							if($i<=0)
							{
								$this->salida .= '>';
							}
							else
							{
								$this->salida .= ' disabled="true">';
								$i--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td>';
							$this->salida .= 'Marzo';
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
							$dias++;
							if($s<=0)
							{
								$this->salida .= '><br>17';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>17';
								$s--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
							$dias++;
							if($s<=0)
							{
								$this->salida .= '><br>18';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>18';
								$s--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
							$dias++;
							if($s<=0)
							{
								$this->salida .= '><br>19';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>19';
								$s--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
							$dias++;
							if($s<=0)
							{
								$this->salida .= '><br>20';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>20';
								$s--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
							$dias++;
							if($s<=0)
							{
								$this->salida .= '><br>21';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>21';
								$s--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
							$dias++;
							if($s<=0)
							{
								$this->salida .= '><br>22';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>22';
								$s--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
							$dias++;
							if($s<=0)
							{
								$this->salida .= '><br>23';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>23';
								$s--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
							$dias++;
							if($s<=0)
							{
								$this->salida .= '><br>24';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>24';
								$s--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'"><br>0';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'"><br>1';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'"><br>2';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'"><br>3';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'"><br>4';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'"><br>5';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'"><br>0';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'"><br>1';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'"><br>2';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'"><br>3';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'"><br>4';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'"><br>5';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '</tr>';
							$this->salida .= '<tr>';
							$this->salida .= '<td>';
							$this->salida .= '<input type="checkbox" name="mes'.$mes.'" value="'.$mes.'"';
							$mes++;
							if($i<=0)
							{
								$this->salida .= '>';
							}
							else
							{
								$this->salida .= ' disabled="true">';
								$i--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td>';
							$this->salida .= 'Abril';
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
							$dias++;
							if($s<=0)
							{
								$this->salida .= '><br>25';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>25';
								$s--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
							$dias++;
							if($s<=0)
							{
								$this->salida .= '><br>26';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>26';
								$s--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
							$dias++;
							if($s<=0)
							{
								$this->salida .= '><br>27';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>27';
								$s--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
							$dias++;
							if($s<=0)
							{
								$this->salida .= '><br>28';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>28';
								$s--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
							$dias++;
							if($s<=0)
							{
								$this->salida .= '><br>29';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>29';
								$s--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
							$dias++;
							if($s<=0)
							{
								$this->salida .= '><br>30';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>30';
								$s--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td colspan="2" align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
							if($s<=0)
							{
								$this->salida .= '><br>31';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>31';
								$s--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'"><br>6';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'"><br>7';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'"><br>8';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'"><br>9';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'"><br>10';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'"><br>11';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'"><br>6';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'"><br>7';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'"><br>8';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'"><br>9';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'"><br>10';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'"><br>11';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '</tr>';
							$this->salida .= '<tr>';
							$this->salida .= '<td>';
							$this->salida .= '<input type="checkbox" name="mes'.$mes.'" value="'.$mes.'"';
							$mes++;
							if($i<=0)
							{
								$this->salida .= '>';
							}
							else
							{
								$this->salida .= ' disabled="true">';
								$i--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td>';
							$this->salida .= 'Mayo';
							$this->salida .= '</td>';
							$this->salida .= '<td colspan="8">';
							$this->salida .= 'Dias de la Semana';
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'"><br>12';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'"><br>13';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'"><br>14';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'"><br>15';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'"><br>16';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'"><br>17';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'"><br>12';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'"><br>13';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'"><br>14';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'"><br>15';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'"><br>16';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'"><br>17';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '</tr>';
							$this->salida .= '<tr>';
							$this->salida .= '<td>';
							$this->salida .= '<input type="checkbox" name="mes'.$mes.'" value="'.$mes.'"';
							$mes++;
							if($i<=0)
							{
								$this->salida .= '>';
							}
							else
							{
								$this->salida .= ' disabled="true">';
								$i--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td>';
							$this->salida .= 'Junio';
							$this->salida .= '</td>';
							$this->salida .= '<td>';
							$this->salida .= '<input type="checkbox" name="semana'.$semana.'" value="lun">';
							$semana++;
							$this->salida .= '</td>';
							$this->salida .= '<td colspan="7">';
							$this->salida .= 'Lunes';
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'"><br>18';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'"><br>19';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'"><br>20';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'"><br>21';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'"><br>22';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'"><br>23';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'"><br>18';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'"><br>19';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'"><br>20';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'"><br>21';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'"><br>22';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'"><br>23';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '</tr>';
							$this->salida .= '<tr>';
							$this->salida .= '<td>';
							$this->salida .= '<input type="checkbox" name="mes'.$mes.'" value="'.$mes.'"';
							$mes++;
							if($i<=0)
							{
								$this->salida .= '>';
							}
							else
							{
								$this->salida .= ' disabled="true">';
								$i--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td>';
							$this->salida .= 'Julio';
							$this->salida .= '</td>';
							$this->salida .= '<td>';
							$this->salida .= '<input type="checkbox" name="semana'.$semana.'" value="mar">';
							$semana++;
							$this->salida .= '</td>';
							$this->salida .= '<td colspan="7">';
							$this->salida .= 'Martes';
							$this->salida .= '</td>';
							$this->salida .= '<td colspan="6" class="modulo_table_title">';
							$this->salida .= 'Minutos';
							$this->salida .= '</td>';
							$this->salida .= '<td colspan="6" class="modulo_table_title">';
							$this->salida .= 'Minutos';
							$this->salida .= '</td>';
							$this->salida .= '</tr>';
							$this->salida .= '<tr>';
							$this->salida .= '<td>';
							$this->salida .= '<input type="checkbox" name="mes'.$mes.'" value="'.$mes.'"';
							$mes++;
							if($i<=0)
							{
								$this->salida .= '>';
							}
							else
							{
								$this->salida .= ' disabled="true">';
								$i--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td>';
							$this->salida .= 'Agosto';
							$this->salida .= '</td>';
							$this->salida .= '<td>';
							$this->salida .= '<input type="checkbox" name="semana'.$semana.'" value="mié">';
							$semana++;
							$this->salida .= '</td>';
							$this->salida .= '<td colspan="7">';
							$this->salida .= 'Miercoles';
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="iniminutos" value="'.$minutosini.'"><br>0';
							$minutosini=$minutosini+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="iniminutos" value="'.$minutosini.'"><br>5';
							$minutosini=$minutosini+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="iniminutos" value="'.$minutosini.'"><br>10';
							$minutosini=$minutosini+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="iniminutos" value="'.$minutosini.'"><br>15';
							$minutosini=$minutosini+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="iniminutos" value="'.$minutosini.'"><br>20';
							$minutosini=$minutosini+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="iniminutos" value="'.$minutosini.'"><br>25';
							$minutosini=$minutosini+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="finminutos" value="'.$minutosfin.'"><br>0';
							$minutosfin=$minutosfin+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="finminutos" value="'.$minutosfin.'"><br>5';
							$minutosfin=$minutosfin+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="finminutos" value="'.$minutosfin.'"><br>10';
							$minutosfin=$minutosfin+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="finminutos" value="'.$minutosfin.'"><br>15';
							$minutosfin=$minutosfin+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="finminutos" value="'.$minutosfin.'"><br>20';
							$minutosfin=$minutosfin+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="finminutos" value="'.$minutosfin.'"><br>25';
							$minutosfin=$minutosfin+5;
							$this->salida .= '</td>';
							$this->salida .= '</tr>';
							$this->salida .= '<tr>';
							$this->salida .= '<td>';
							$this->salida .= '<input type="checkbox" name="mes'.$mes.'" value="'.$mes.'"';
							$mes++;
							if($i<=0)
							{
								$this->salida .= '>';
							}
							else
							{
								$this->salida .= ' disabled="true">';
								$i--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td>';
							$this->salida .= 'Septiembre';
							$this->salida .= '</td>';
							$this->salida .= '<td>';
							$this->salida .= '<input type="checkbox" name="semana'.$semana.'" value="jue">';
							$semana++;
							$this->salida .= '</td>';
							$this->salida .= '<td colspan="7">';
							$this->salida .= 'Jueves';
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="iniminutos" value="'.$minutosini.'"><br>30';
							$minutosini=$minutosini+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="iniminutos" value="'.$minutosini.'"><br>35';
							$minutosini=$minutosini+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="iniminutos" value="'.$minutosini.'"><br>40';
							$minutosini=$minutosini+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="iniminutos" value="'.$minutosini.'"><br>45';
							$minutosini=$minutosini+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="iniminutos" value="'.$minutosini.'"><br>50';
							$minutosini=$minutosini+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="iniminutos" value="'.$minutosini.'"><br>55';
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="finminutos" value="'.$minutosfin.'"><br>30';
							$minutosfin=$minutosfin+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="finminutos" value="'.$minutosfin.'"><br>35';
							$minutosfin=$minutosfin+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="finminutos" value="'.$minutosfin.'"><br>40';
							$minutosfin=$minutosfin+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="finminutos" value="'.$minutosfin.'"><br>45';
							$minutosfin=$minutosfin+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="finminutos" value="'.$minutosfin.'"><br>50';
							$minutosfin=$minutosfin+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="radio" name="finminutos" value="'.$minutosfin.'"><br>55';
							$this->salida .= '</td>';
							$this->salida .= '</tr>';
							$this->salida .= '<tr>';
							$this->salida .= '<td>';
							$this->salida .= '<input type="checkbox" name="mes'.$mes.'" value="'.$mes.'"';
							$mes++;
							if($i<=0)
							{
								$this->salida .= '>';
							}
							else
							{
								$this->salida .= ' disabled="true">';
								$i--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td>';
							$this->salida .= 'Octubre';
							$this->salida .= '</td>';
							$this->salida .= '<td>';
							$this->salida .= '<input type="checkbox" name="semana'.$semana.'" value="vie">';
							$semana++;
							$this->salida .= '</td>';
							$this->salida .= '<td colspan="7">';
							$this->salida .= 'viernes';
							$this->salida .= '</td>';
							$this->salida .= '<td colspan="6" class="modulo_table_title">';
							$this->salida .= 'Consultorio';
							$this->salida .= '</td>';
							$this->salida .= '<td colspan="6" class="modulo_table_title">';
							$this->salida .= 'Tipo de Registro';
							$this->salida .= '</td>';
							$this->salida .= '</tr>';
							$this->salida .= '<tr>';
							$this->salida .= '<td>';
							$this->salida .= '<input type="checkbox" name="mes'.$mes.'" value="'.$mes.'"';
							$mes++;
							if($i<=0)
							{
								$this->salida .= '>';
							}
							else
							{
								$this->salida .= ' disabled="true">';
								$i--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td>';
							$this->salida .= 'Noviembre';
							$this->salida .= '</td>';
							$this->salida .= '<td>';
							$this->salida .= '<input type="checkbox" name="semana'.$semana.'" value="sáb">';
							$semana++;
							$this->salida .= '</td>';
							$this->salida .= '<td colspan="7">';
							$this->salida .= 'Sabado';
							$this->salida .= '</td>';
							$this->salida .= '<td colspan="6" align="center">';
							$this->salida .='<select name="consultorio" class="input-text">';
							$tipocita=$this->Consultorio();
							$this->salida .='<option value="">--Seleccione--</option>';
							$j=0;
							while($j<sizeof($tipocita[0]))
							{
								$this->salida .='<option value='.$tipocita[0][$j].'>'.$tipocita[1][$j].'</option>';
								$j++;
							}
							$this->salida .='</select>';
							$this->salida .= '</td>';
							$this->salida .= '<td colspan="6">';
							$this->salida .='<select name="tiporegistro" class="input-text">';
							$tipocita=$this->TipoRegistro();
							$j=0;
							while($j<sizeof($tipocita[0]))
							{
								$this->salida .='<option value='.$tipocita[0][$j].'>'.$tipocita[1][$j].'</option>';
								$j++;
							}
							$this->salida .='</select>';
							$this->salida .= '</td>';
							$this->salida .= '</tr>';
							$this->salida .= '<tr>';
							$this->salida .= '<td>';
							$this->salida .= '<input type="checkbox" name="mes'.$mes.'" value="'.$mes.'"';
							if($i<=0)
							{
								$this->salida .= '>';
							}
							else
							{
								$this->salida .= ' disabled="true">';
								$i--;
							}
							$this->salida .= '</td>';
							$this->salida .= '<td>';
							$this->salida .= 'Diciembre';
							$this->salida .= '</td>';
							$this->salida .= '<td>';
							$this->salida .= '<input type="checkbox" name="semana'.$semana.'" value="dom">';
							$this->salida .= '</td>';
							$this->salida .= '<td colspan="7">';
							$this->salida .= 'Domingo';
							$this->salida .= '</td>';
							$this->salida .= '<td colspan="4" class="modulo_table_title">';
							$this->salida .= 'Tam. Intervalo';
							$this->salida .= '</td>';
							$this->salida .= '<td colspan="2" align="center">';
							$this->salida .='<select name="interval" class="input-text">';
							$intervalo=$this->Intervalo();
							$j=0;
							while($j<sizeof($intervalo))
							{
								$this->salida .='<option value='.$intervalo[$j].'>'.$intervalo[$j].'</option>';
								$j++;
							}
							$this->salida .='</select>';
							$this->salida .= '</td>';
							$this->salida .= '<td colspan="4" class="modulo_table_title">';
							$this->salida .= 'Can. Pacientes';
							$this->salida .= '</td>';
							$this->salida .= '<td colspan="2" align="center">';
							$this->salida .='<select name="pacientes" class="input-text">';
							$pacientes=$this->Pacientes();
							$j=0;
							while($j<sizeof($pacientes))
							{
								$this->salida .='<option value='.$pacientes[$j].'>'.$pacientes[$j].'</option>';
								$j++;
							}
							$this->salida .='</select>';
							$this->salida .= '</td>';
							$this->salida .= '</tr>';
							$this->salida .= '</table>';
							$this->salida .= '<br>';
							$this->salida .= '<table width="90%" align="center" border="0" class="modulo_table">';
							$this->salida .= '<tr>';
							$this->salida .= '<td align="right">';
							$this->salida .= '<input type="submit" name="Enviar" value="Enviar" class="input-submit">';
							$this->salida .= '</form>';
							$this->salida .= '</td>';
							$this->salida .= '<td align="left">';
							$accion=ModuloGetURL('app', 'AgendaMedicaCoc', 'admin', 'CreacionAgenda');
							$this->salida .= '<form name="volver" action="'.$accion.'" method="post">';
							$this->salida .= '<input type="submit" name="volver" value="volver" class="input-submit">';
							$this->salida .= '</td>';
							$this->salida .= '</form>';
							$this->salida .= '</tr>';
							$this->salida .= '</table';
							$this->salida .= '<br>';
							$this->salida .= '<br>';
							$this->salida .= ThemeCerrarTabla();
						}
						else
						{
							if(empty($_REQUEST['guardar']))
							{
								if(empty($_REQUEST['DiaEspe']))
								{
									$i=0;
									$t=0;
									while($i<13)
									{
										$mes='mes'.$i;
										if(!empty($_REQUEST[$mes]))
										{
											$t++;
										}
										$i++;
									}
									$this->salida  = ThemeAbrirTabla('CREACIÓN AGENDA MEDICA');
									if($t<2)
									{
										if(!empty($_REQUEST['interval']) and $_REQUEST['inihora']!='' and $_REQUEST['finhora']!='' and $_REQUEST['iniminutos']!='' and $_REQUEST['finminutos']!='' and $_REQUEST['pacientes']!='')
										{
											if(empty($_SESSION['FECHAS']))
											{
												if($_REQUEST['a']>0)
												{
													$a=date("Y",mktime(0,0,0,1,1,(date("Y")+$_REQUEST['a'])));
													$i=0;
												}
												else
												{
													$a=date("Y");
													$_REQUEST['a']=0;
													$i=date("n");
													if($i==12)
													{
														$s=date("j");
														if($s==31)
														{
															$s=0;
															$a++;
															$i=0;
														}
													}
												}
												$r=1;
												$t=0;
												while($r<13)
												{
													$mes='mes'.$r;
													if(!empty($_REQUEST[$mes]))
													{
														$i=1;
														while($i<32)
														{
															$dias='dias'.$i;
															if(!empty($_REQUEST[$dias]))
															{
																if($_REQUEST[$mes]==date("m",mktime(0,0,0,$_REQUEST[$mes],$_REQUEST[$dias],$a)))
																{
																	if(empty($_REQUEST['nosabados']) and empty($_REQUEST['nodomingos']))
																	{
																		$fechas[$t]=date("Y-m-d",mktime(0,0,0,$_REQUEST[$mes],$_REQUEST[$dias],$a));
																		$t++;
																	}
																	elseif(!empty($_REQUEST['nosabados']) and !empty($_REQUEST['nodomingos']))
																	{
																		if(strftime("%a",mktime(0,0,0,$_REQUEST[$mes],$_REQUEST[$dias],$a))!='dom' and strftime("%a",mktime(0,0,0,$_REQUEST[$mes],$_REQUEST[$dias],$a))!='sáb')
																		{
																			$fechas[$t]=date("Y-m-d",mktime(0,0,0,$_REQUEST[$mes],$_REQUEST[$dias],$a));
																			$t++;
																		}
																	}
																	elseif(!empty($_REQUEST['nosabados']))
																	{
																		if(strftime("%a",mktime(0,0,0,$_REQUEST[$mes],$_REQUEST[$dias],$a))!='sáb')
																		{
																			$fechas[$t]=date("Y-m-d",mktime(0,0,0,$_REQUEST[$mes],$_REQUEST[$dias],$a));
																			$t++;
																		}
																	}
																	elseif(!empty($_REQUEST['nodomingos']))
																	{
																		if(strftime("%a",mktime(0,0,0,$_REQUEST[$mes],$_REQUEST[$dias],$a))!='dom')
																		{
																			$fechas[$t]=date("Y-m-d",mktime(0,0,0,$_REQUEST[$mes],$_REQUEST[$dias],$a));
																			$t++;
																		}
																	}
																}
															}
															$i++;
														}
														$i=0;
														while($i<7)
														{
															$semana='semana'.$i;
															if(!empty($_REQUEST[$semana]))
															{
																$s=1;
																while($s<32)
																{
																	if(strcasecmp($_REQUEST[$semana],chop(strftime("%a",mktime(0,0,0,$_REQUEST[$mes],$s,$a))))==0)
																	{
																		if($_REQUEST[$mes]==date("m",mktime(0,0,0,$_REQUEST[$mes],$s,$a)))
																		{
																			$k=0;
																			$m=$t;
																			while($k<$m)
																			{
																				if(strcasecmp($fechas[$k],date("Y-m-d",mktime(0,0,0,$_REQUEST[$mes],$s,$a)))==0)
																				{
																				break;
																				}
																				$k++;
																			}
																			if($k==$m)
																			{
																				$fechas[$t]=date("Y-m-d",mktime(0,0,0,$_REQUEST[$mes],$s,$a));
																				$t++;
																			}
																		}
																	}
																	$s++;
																}
															}
															$i++;
														}
													}
													$r++;
												}
												if(sizeof($fechas)==0)
												{
													$this->salida.='<script>';
													$this->salida.='function vol(){';
													$this->salida.='window.history.go(-2);';
													$this->salida.='}';
													$this->salida.='</script>';
													$this->salida.='No se eligio ninguna fecha para realizar agenda.';
													$this->salida.='<input type="button" name="volver" value="volver" onclick="vol()" class="input-submit">';
												}
												else
												{
													if(!empty($_REQUEST['nofestivos']))
													{
														$festivos=$this->Festivos($a);
														$i=0;
														while($i<sizeof($festivos))
														{
															$j=array_keys($fechas,$festivos[$i]);
															$t=$j[0];
															if(!empty($t) or $t===0)
															{
																$fechas[$t]='';
															}
															$i++;
														}
													}
													if($_REQUEST['inihora']<=$_REQUEST['finhora'])
													{
														if($_REQUEST['inihora']==$_REQUEST['finhora'] and $_REQUEST['iniminutos']<$_REQUEST['finminutos'] and ($_REQUEST['iniminutos']+$_REQUEST['interval'])<=$_REQUEST['finminutos'] or $_REQUEST['inihora']<$_REQUEST['finhora'])
														{
															$i=0;
															$r=0;
															$s=0;
															while($i<sizeof($fechas))
															{
																if(!empty($fechas[$i]))
																{
																	$a=explode("-",$fechas[$i]);
																	$fechastotal[$r]=date("Y-m-d H:i",mktime($_REQUEST['inihora'],$_REQUEST['iniminutos'],0,$a[1],$a[2],$a[0]));
																	$r++;
																	$s=$_REQUEST['iniminutos'];
																	$s=$s+$_REQUEST['interval'];
																	$k=0;
																	while(date("m-d H:i",mktime($_REQUEST['inihora'],$s,0,$a[1],$a[2],$a[0]))<=date("m-d H:i",mktime($_REQUEST['finhora'],$_REQUEST['finminutos'],0,$a[1],$a[2],$a[0])))
																	{
																		$fechastotal[$r]=date("Y-m-d H:i",mktime($_REQUEST['inihora'],$s,0,$a[1],$a[2],$a[0]));
																		$s=$s+$_REQUEST['interval'];
																		$r++;
																	}
																}
																$i++;
															}
															array_multisort($fechastotal);
															$i=0;
															$mes=1;
															$dia=1;
															$l=0;
															$hora[]=0;
															$hora[]=0;
															$hora[]=0;
															$hora[]=0;
															while($i<sizeof($fechastotal))
															{
																$a=explode("-",$fechastotal[$i]);
																$b=explode(" ",$a[2]);
																if($mes==date("m",mktime(0,0,0,$a[1],$b[0],$a[0])))
																{
																	if($dia==date("j",mktime(0,0,0,$a[1],$b[0],$a[0])))
																	{
																		$c=explode(":",$b[1]);
																		if(date("G",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]))>=0 and date("G",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]))<=6 and $hora[0]==0)
																		{
																			$citasmes[$l]=$fechastotal[$i];
																			$l++;
																			$hora[0]=1;
																		}
																		if(date("G",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]))>6 and date("G",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]))<=12 and $hora[1]==0)
																		{
																			$citasmes[$l]=$fechastotal[$i];
																			$l++;
																			$hora[1]=1;
																		}
																		if(date("G",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]))>12 and date("G",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]))<=18 and $hora[2]==0)
																		{
																			$citasmes[$l]=$fechastotal[$i];
																			$l++;
																			$hora[2]=1;
																		}
																		if(date("G",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]))>18 and date("G",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]))<=23 and $hora[3]==0)
																		{
																			$citasmes[$l]=$fechastotal[$i];
																			$l++;
																			$hora[3]=1;
																		}
																		$i++;
																	}
																	else
																	{
																		$dia++;
																		$hora[0]=$hora[1]=$hora[2]=$hora[3]=0;
																	}
																}
																else
																{
																	$mes++;
																	$dia=1;
																	$hora[0]=$hora[1]=$hora[2]=$hora[3]=0;
																}
															}
															SessionSetVar('FECHAS',$fechastotal);
															SessionSetVar('CITASMES',$citasmes);
															$this->AgendaHtml();
														}
														else
														{
															$this->salida.='<script>';
															$this->salida.='function vol(){'."\n";
															$this->salida.='window.history.back();'."\n";
															$this->salida.='}'."\n";
															$this->salida.='</script>';
															$this->salida.='La hora inicial es igual a la final y los minutos finales son mayores a los iniciales.';
															$this->salida.='<input type="button" name="volver" value="volver" onclick="vol()" class="input-submit">';
														}
													}
													else
													{
														$this->salida.='<script>';
														$this->salida.='function vol(){';
														$this->salida.='window.history.go(-2);';
														$this->salida.='}';
														$this->salida.='</script>';
														$this->salida.='La hora inicial es menor a la final.';
														$this->salida.='<input type="button" name="volver" value="volver" onclick="vol()" class="input-submit">';
													}
												}
											}
											else
											{
												SessionDelVar('CITASDIA');
												$this->AgendaHtml();
											}
										}
										else
										{
											$this->salida.='<script>';
											$this->salida.='function vol(){';
											$this->salida.='window.history.go(-2);';
											$this->salida.='}';
											$this->salida.='</script>';
											$this->salida.='No se coloco ningun intervalo de turno o una hora inicial o una hora final o un minuto inicial o un minuto final o una cantidad de pacientes.';
											$this->salida.='<input type="button" name="volver" value="volver" onclick="vol()" class="input-submit">';
										}
									}
									else
									{
										foreach($_REQUEST  as $v=>$v1)
										{
											if($v!='mes1' and $v!='mes2' and $v!='mes3' and $v!='mes4' and $v!='mes5' and $v!='mes6' and $v!='mes7' and $v!='mes8' and $v!='mes9' and $v!='mes10' and $v!='mes11' and $v!='mes12' and $v!='guardar' and $v!='modulo' and $v!='metodo' and $v!='tipo')
											{
												$vec[$v]=$v1;
											}
											else
											{
												if($v!='guardar' and $v!='modulo' and $v!='metodo' and $v!='tipo')
												{
													$dato=$v1.'mes';
													$vec[$dato]=$v1;
												}
												else
												{
													unset($_REQUEST[$v]);
												}
											}
										}
										foreach($_REQUEST as $v=>$v1)
										{
											if($v=='mes1')
											{
												$vec1=$vec;
												unset($vec1['1mes']);
												$vec1[$v]=$v1;
												$accion=ModuloGetURL('app','AgendaMedicaCoc','admin','CreacionAgenda',$vec1);
												$this->salida.='Creación del mes de: <a href="'.$accion.'">'.strftime("%B",mktime(0,0,0,$v1,1,0)).'</a><br>';
											}
											if($v=='mes2')
											{
												$vec1=$vec;
												unset($vec1['2mes']);
												$vec1[$v]=$v1;
												$accion=ModuloGetURL('app','AgendaMedicaCoc','admin','CreacionAgenda',$vec1);
												$this->salida.='Creación del mes de: <a href="'.$accion.'">'.strftime("%B",mktime(0,0,0,$v1,1,0)).'</a><br>';
											}
											if($v=='mes3')
											{
												$vec1=$vec;
												unset($vec1['3mes']);
												$vec1[$v]=$v1;
												$accion=ModuloGetURL('app','AgendaMedicaCoc','admin','CreacionAgenda',$vec1);
												$this->salida.='Creación del mes de: <a href="'.$accion.'">'.strftime("%B",mktime(0,0,0,$v1,1,0)).'</a><br>';
											}
											if($v=='mes4')
											{
												$vec1=$vec;
												unset($vec1['4mes']);
												$vec1[$v]=$v1;
												$accion=ModuloGetURL('app','AgendaMedicaCoc','admin','CreacionAgenda',$vec1);
												$this->salida.='Creación del mes de: <a href="'.$accion.'">'.strftime("%B",mktime(0,0,0,$v1,1,0)).'</a><br>';
											}
											if($v=='mes5')
											{
												$vec1=$vec;
												unset($vec1['5mes']);
												$vec1[$v]=$v1;
												$accion=ModuloGetURL('app','AgendaMedicaCoc','admin','CreacionAgenda',$vec1);
												$this->salida.='Creación del mes de: <a href="'.$accion.'">'.strftime("%B",mktime(0,0,0,$v1,1,0)).'</a><br>';
											}
											if($v=='mes6')
											{
												$vec1=$vec;
												unset($vec1['6mes']);
												$vec1[$v]=$v1;
												$accion=ModuloGetURL('app','AgendaMedicaCoc','admin','CreacionAgenda',$vec1);
												$this->salida.='Creación del mes de: <a href="'.$accion.'">'.strftime("%B",mktime(0,0,0,$v1,1,0)).'</a><br>';
											}
											if($v=='mes7')
											{
												$vec1=$vec;
												unset($vec1['7mes']);
												$vec1[$v]=$v1;
												$accion=ModuloGetURL('app','AgendaMedicaCoc','admin','CreacionAgenda',$vec1);
												$this->salida.='Creación del mes de: <a href="'.$accion.'">'.strftime("%B",mktime(0,0,0,$v1,1,0)).'</a><br>';
											}
											if($v=='mes8')
											{
												$vec1=$vec;
												unset($vec1['8mes']);
												$vec1[$v]=$v1;
												$accion=ModuloGetURL('app','AgendaMedicaCoc','admin','CreacionAgenda',$vec1);
												$this->salida.='Creación del mes de: <a href="'.$accion.'">'.strftime("%B",mktime(0,0,0,$v1,1,0)).'</a><br>';
											}
											if($v=='mes9')
											{
												$vec1=$vec;
												unset($vec1['9mes']);
												$vec1[$v]=$v1;
												$accion=ModuloGetURL('app','AgendaMedicaCoc','admin','CreacionAgenda',$vec1);
												$this->salida.='Creación del mes de: <a href="'.$accion.'">'.strftime("%B",mktime(0,0,0,$v1,1,0)).'</a><br>';
											}
											if($v=='mes10')
											{
												$vec1=$vec;
												unset($vec1['10mes']);
												$vec1[$v]=$v1;
												$accion=ModuloGetURL('app','AgendaMedicaCoc','admin','CreacionAgenda',$vec1);
												$this->salida.='Creación del mes de: <a href="'.$accion.'">'.strftime("%B",mktime(0,0,0,$v1,1,0)).'</a><br>';
											}
											if($v=='mes11')
											{
												$vec1=$vec;
												unset($vec1['11mes']);
												$vec1[$v]=$v1;
												$accion=ModuloGetURL('app','AgendaMedicaCoc','admin','CreacionAgenda',$vec1);
												$this->salida.='Creación del mes de: <a href="'.$accion.'">'.strftime("%B",mktime(0,0,0,$v1,1,0)).'</a><br>';
											}
											if($v=='mes12')
											{
												$vec1=$vec;
												unset($vec1['12mes']);
												$vec1[$v]=$v1;
												$accion=ModuloGetURL('app','AgendaMedicaCoc','admin','CreacionAgenda',$vec1);
												$this->salida.='Creación del mes de: <a href="'.$accion.'">'.strftime("%B",mktime(0,0,0,$v1,1,0)).'</a><br>';
											}
										}
									}
									$this->salida .= ThemeCerrarTabla();
								}
								else
								{
									$intervalo=$this->CitasDias(&$todo);
									SessionSetVar('CITASDIA',$todo);
									foreach($_REQUEST as $value=>$dato)
									{
										if($value!='modulo' and $value!='tipo' and $value!='DiaEspe')
										{
											$vec[$value]=$dato;
										}
									}
									$accion=ModuloGetURL('app','AgendaMedicaCoc','admin','',$vec);
									$this->salida  = ThemeAbrirTabla('CREACIÓN AGENDA MEDICA');
									$this->salida .= '<br>';
									$this->salida .= '<table width="70%" align="center" class="modulo_table_list">';
									$this->salida .= '<tr align="center" class="modulo_table_title">';
									$this->salida .= '<td align="center">';
									$this->salida .= "Empresa";
									$this->salida .= "</td>";
									$this->salida .= '<td align="center">';
									$this->salida .= "Departamento";
									$this->salida .= "</td>";
									$this->salida .= '<td align="center">';
									$this->salida .= "Tipo de Cita";
									$this->salida .= "</td>";
									$this->salida .= "</tr>";
									$this->salida .= '<tr class="modulo_list_oscuro">';
									$this->salida .= '<td align="center">';
									$this->salida .= $_SESSION['CreacionAgenda']['nomemp'];
									$this->salida .= "</td>";
									$this->salida .= '<td align="center">';
									$this->salida .= $_SESSION['CreacionAgenda']['nomdep'];
									$this->salida .= "</td>";
									$this->salida .= '<td align="center">';
									$this->salida .= $_SESSION['CreacionAgenda']['nombre'];
									$this->salida .= "</td>";
									$this->salida .= "</tr>";
									$this->salida .= "</table>";
									$this->salida .='<br>';
									$this->salida .= '<table width="70%" align="center" class="modulo_table_list">';
									$this->salida .= '<tr align="center" class="modulo_table_title">';
									$this->salida .= '<td align="center">';
									$this->salida .= "Profesional";
									$this->salida .= "</td>";
									$this->salida .= "</tr>";
									$this->salida .= '<tr class="modulo_list_oscuro">';
									$this->salida .= '<td align="center">';
									$this->salida .= $_SESSION['CreacionAgenda']['nombrep'];
									$this->salida .= "</td>";
									$this->salida .= "</tr>";
									$this->salida .= "</table>";
									$this->ReturnMetodoExterno('app', 'Agenda', 'user', 'Dia',array('intervalo'=>$intervalo,'opciones'=>2));
									$this->salida .= '<br>';
									$this->salida .= '<table align="center">';
									$this->salida .= '<tr>';
									$this->salida .= '<td>';
									$this->salida .= '<form name="atras" action="'.$accion.'" method="post">';
									$this->salida .= '<input type="submit" name="volver" value="volver" class="input-submit">';
									$this->salida .= '</form>';
									$this->salida .= '</tr>';
									$this->salida .= '</td>';
									$this->salida .= '</table>';
									$this->salida .= '<br>';
									$this->salida .= ThemeCerrarTabla();
								}
							}
						}
					}
				}
			}
		}
    return true;
	}

	function AgendaHtml()
	{
		$this->salida.="\n".'<script>'."\n";
		$this->salida.='function year1(t)'."\n";
		$this->salida.='{'."\n";
		$this->salida.='window.location.href="Contenido.php?modulo=AgendaMedicaCoc&year="+t.elements[1].value+"&meses="+t.elements[2].value+"';
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
		$this->salida .='<br>';
		$this->salida .= '<table width="70%" align="center" class="modulo_table_list">';
		$this->salida .= '<tr align="center" class="modulo_table_title">';
		$this->salida .= '<td align="center">';
		$this->salida .= "Empresa";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Departamento";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Tipo de Cita";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= '<tr class="modulo_list_oscuro">';
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['CreacionAgenda']['nomemp'];
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['CreacionAgenda']['nomdep'];
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['CreacionAgenda']['nombre'];
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .='<br>';
		$this->salida .= '<table width="70%" align="center" class="modulo_table_list">';
		$this->salida .= '<tr align="center" class="modulo_table_title">';
		$this->salida .= '<td align="center">';
		$this->salida .= "Profesional";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= '<tr class="modulo_list_oscuro">';
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['CreacionAgenda']['nombrep'];
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .='<br>';
		$this->salida .= "      <form name=\"forma\">";
		$this->salida .= "        <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table\">";
		$this->salida .= "        <tr><td><fieldset><legend class=\"field\">AGENDA MENSUAL</legend>";
		$this->salida .= "          <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table\">";
		$this->salida .= "				  <tr><td class=\"label\">AÑO</td><td><select name=\"year\" onchange=\"year1(this.form)\" class=\"select\">";
		if(empty($_REQUEST['year']))
		{
			$this->AnosAgenda(True,date("Y")+$_REQUEST['a']);
			$year=date("Y")+$_REQUEST['a'];
		}
		else
		{
			$this->AnosAgenda(True,$_REQUEST['year']);
			$year=$_REQUEST['year'];
		}
		$this->salida .= "</select></td></tr>";
		$this->salida .= "<tr><td class=\"label\">MES</td><td><select name=\"mes\" onchange=\"year1(this.form)\" class=\"select\">";
		if(empty($_REQUEST['meses']))
		{
			$i=1;
			while($i<13)
			{
				$m='mes';
				$m.=$i;
				if(!empty($_REQUEST[$m]))
				{
					break;
				}
				$i++;
			}
			$this->MesesAgenda(True,$year,$_REQUEST[$m]);
			$mes=$_REQUEST[$m];
		}
		else
		{
			$this->MesesAgenda(True,$year,$_REQUEST['meses']);
			$mes=$_REQUEST['meses'];
		}
		$this->salida .= "          </select></td></tr>";
		$this->salida .= "          <tr><td  align=\"center\" colspan=\"4\"></td></tr>";
		$this->salida .= "			     </table>";
		$this->salida .= "		     </fieldset></td></tr></table><BR>";
		$this->salida .= "      </form>";
		$this->ReturnMetodoExterno('app', 'Agenda', 'user', 'Calendario', array('year'=>$year, 'meses'=>$mes));
		foreach($_REQUEST as $v=>$v1)
		{
			if($v!='year' and $v!='meses' and $v!='Enviar' and $v!='metodo' and $v!='modulo' and $v!='tipo' and $v!='SIIS_SID')
			{
				$vec[$v]=$v1;
			}
		}
		foreach($_REQUEST AS $v=>$v1)
		{
			if($v=='mes1')
			{
				$vec1=$vec;
				unset($vec1['1mes']);
			}
			if($v=='mes2')
			{
				$vec1=$vec;
				unset($vec1['2mes']);
			}
			if($v=='mes3')
			{
				$vec1=$vec;
				unset($vec1['3mes']);
			}
			if($v=='mes4')
			{
				$vec1=$vec;
				unset($vec1['4mes']);
			}
			if($v=='mes5')
			{
				$vec1=$vec;
				unset($vec1['5mes']);
			}
			if($v=='mes6')
			{
				$vec1=$vec;
				unset($vec1['6mes']);
			}
			if($v=='mes7')
			{
				$vec1=$vec;
				unset($vec1['7mes']);
			}
			if($v=='mes8')
			{
				$vec1=$vec;
				unset($vec1['8mes']);
			}
			if($v=='mes9')
			{
				$vec1=$vec;
				unset($vec1['9mes']);
			}
			if($v=='mes10')
			{
				$vec1=$vec;
				unset($vec1['10mes']);
			}
			if($v=='mes11')
			{
				$vec1=$vec;
				unset($vec1['11mes']);
			}
			if($v=='mes12')
			{
				$vec1=$vec;
				unset($vec1['12mes']);
			}
		}
		$accion=ModuloGetURL('app', 'AgendaMedicaCoc', 'admin', 'CreacionAgenda', $vec1);
		$this->salida .= '<form name="atras" action="'.$accion.'" method="post">';
		$this->salida .= '<br>';
		$this->salida .= '<table align="center">';
		$this->salida .= '<tr>';
		$this->salida .= '<td>';
		$this->salida .= '<input type="submit" name="Volver" value="volver" class="input-submit">';
		$this->salida .= '</td>';
		$this->salida .= '<td>';
		$this->salida .= '</form>';
		$accion=ModuloGetURL('app', 'AgendaMedicaCoc', 'admin', 'GuardarDatos', $vec1);
		$this->salida .= '<form name="atras" action="'.$accion.'" method="post">';
		$this->salida .= '<input type="submit" name="guardar" value="guardar" class="input-submit">';
		$this->salida .= '</form>';
		$this->salida .= '</td>';
		$this->salida .= '</tr>';
		$this->salida .= '</table>';
		$this->salida .= '<br>';

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

	//Cambiar Borrar Agenda

	function CambiarBorrarAgenda()
	{
		$url[0]='app';
		$url[1]='AgendaMedicaCoc';
		$url[2]='admin';
		$url[3]='ListarProfesionales';
		$url[4]='Citas';
		$Cita=$this->CitaConsulta($url);
		if($Cita)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function ListarProfesionales()
	{
		if(empty($_SESSION['BorrarAgenda']['Cita']))
		{
			$_SESSION['BorrarAgenda']['Cita']=$_REQUEST['Citas']['tipo_consulta_id'];
			$_SESSION['BorrarAgenda']['nomemp']=$_REQUEST['Citas']['descripcion1'];
			$_SESSION['BorrarAgenda']['nomdep']=$_REQUEST['Citas']['descripcion2'];
			$_SESSION['BorrarAgenda']['nombre']=$_REQUEST['Citas']['descripcion3'];
			$_SESSION['BorrarAgenda']['empresa']=$_REQUEST['Citas']['empresa_id'];
			$_SESSION['BorrarAgenda']['departamento']=$_REQUEST['Citas']['departamento'];
		}
		$profesionales=$this->Profesionales2();
		if($profesionales)
		{
			$this->salida=ThemeAbrirTabla('BORRAR Y CAMBIAR AGENDA');
			$this->salida .='<br>';
			$this->salida .= '<table width="70%" align="center" class="modulo_table_list">';
			$this->salida .= '<tr align="center" class="modulo_table_title">';
			$this->salida .= '<td align="center">';
			$this->salida .= "Empresa";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "Departamento";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "Tipo de Cita";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= '<tr class="modulo_list_oscuro">';
			$this->salida .= '<td align="center">';
			$this->salida .= $_SESSION['BorrarAgenda']['nomemp'];
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= $_SESSION['BorrarAgenda']['nomdep'];
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= $_SESSION['BorrarAgenda']['nombre'];
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= "</table>";
			$this->salida .= '<br>';
			$this->salida .= '<table align="center" width="70%" border="0" class="modulo_table_list">';
			$this->salida .= '<tr align="center" class="modulo_table_title">';
			$this->salida .= '<td>';
			$this->salida .= 'Profesionales';
			$this->salida .= '</td>';
			$this->salida .= '<td>';
			$this->salida .= '</td>';
			$this->salida .= '</tr>';
			$i=0;
			while($i<sizeof($profesionales[0]))
			{
				if($spy==0)
				{
					$this->salida .= '<tr class="modulo_list_claro">';
					$spy=1;
				}
				else
				{
					$this->salida .= '<tr class="modulo_list_oscuro">';
					$spy=0;
				}
				$this->salida .= '<td>';
				$this->salida .= $profesionales[2][$i];
				$this->salida .= '</td>';
				$this->salida .= '<td>';
				$this->salida .= '<a href="'.ModuloGetURL('app','AgendaMedicaCoc','admin','ListadoAgendaMesTurnos',array('tercero'=>$profesionales[1][$i],'tipoid'=>$profesionales[0][$i],'nombrep'=>$profesionales[2][$i])).'">Ir</a>';
				$this->salida .= '</td>';
				$this->salida .= '</tr>';
				$i++;
			}
			$this->salida .= '</table>';
			$this->salida .= '<br>';
			$this->salida .= '<table align="center" width="70%" border="0">';
			$this->salida .= '<tr>';
			$this->salida .= '<td align="center">';
			$accion=ModuloGetURL('app','AgendaMedicaCoc','admin','CambiarBorrarAgenda');
			$this->salida .= '<form name="volver" method="post" action="'.$accion.'">';
			$this->salida .= '<input type="submit" name="volver" value="volver" class="input-submit">';
			$this->salida .= '</form>';
			$this->salida .= '</td>';
			$this->salida .= '</tr>';
			$this->salida .= '</table>';
			$this->salida .= '<br>';
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		else
		{
			$this->salida=ThemeAbrirTabla('BORRAR Y CAMBIAR AGENDA');
			$this->salida .='<br>';
			$this->salida .= '<table width="70%" align="center" class="modulo_table_list">';
			$this->salida .= '<tr align="center" class="modulo_table_title">';
			$this->salida .= '<td align="center">';
			$this->salida .= "Empresa";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "Departamento";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "Tipo de Cita";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= '<tr class="modulo_list_oscuro">';
			$this->salida .= '<td align="center">';
			$this->salida .= $_SESSION['BorrarAgenda']['nomemp'];
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= $_SESSION['BorrarAgenda']['nomdep'];
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= $_SESSION['BorrarAgenda']['nombre'];
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= "</table>";
			$this->salida .= '<br>';
			$this->salida .= '<table align="center" width="70%" border="0">';
			$this->salida .= '<tr>';
			$this->salida .= '<td align="center" class="label_error">';
			$this->salida .= 'No existen datos de profesionales';
			$this->salida .= '</td>';
			$this->salida .= '</tr>';
			$this->salida .= '</table>';
			$this->salida .= '<br>';
			$this->salida .= '<table align="center" width="70%" border="0">';
			$this->salida .= '<tr>';
			$this->salida .= '<td align="center">';
			$accion=ModuloGetURL('app','AgendaMedicaCoc','admin','CambiarBorrarAgenda');
			$this->salida .= '<form name="volver" method="post" action="'.$accion.'">';
			$this->salida .= '<input type="submit" name="volver" value="volver" class="input-submit">';
			$this->salida .= '</form>';
			$this->salida .= '</td>';
			$this->salida .= '</tr>';
			$this->salida .= '</table>';
			$this->salida .= '<br>';
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
	}

	function ListadoAgendaMesTurnos()
	{
		$_SESSION['BorrarAgenda']['nombrep']=$_REQUEST['nombrep'];
		$_SESSION['BorrarAgenda']['tercero']=$_REQUEST['tercero'];
		$_SESSION['BorrarAgenda']['tipoid']=$_REQUEST['tipoid'];
		$turnos=$this->ListadoTurnosMes();
		if($turnos)
		{
			$this->salida=ThemeAbrirTabla('BORRAR Y CAMBIAR AGENDA');
			$this->salida .='<br>';
			$this->salida .= '<table width="70%" align="center" class="modulo_table_list">';
			$this->salida .= '<tr align="center" class="modulo_table_title">';
			$this->salida .= '<td align="center">';
			$this->salida .= "Empresa";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "Departamento";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "Tipo de Cita";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= '<tr class="modulo_list_oscuro">';
			$this->salida .= '<td align="center">';
			$this->salida .= $_SESSION['BorrarAgenda']['nomemp'];
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= $_SESSION['BorrarAgenda']['nomdep'];
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= $_SESSION['BorrarAgenda']['nombre'];
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= "</table>";
			$this->salida .= '<br>';
			$this->salida .= '<table width="70%" align="center" class="modulo_table_list">';
			$this->salida .= '<tr align="center" class="modulo_table_title">';
			$this->salida .= '<td align="center">';
			$this->salida .= "Profesional";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= '<tr class="modulo_list_oscuro">';
			$this->salida .= '<td align="center">';
			$this->salida .= $_SESSION['BorrarAgenda']['nombrep'];
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= "</table>";
			$this->salida .= '<br>';
			$s=0;
			foreach($_REQUEST as $v=>$datos)
			{
				if(substr_count ($v,'seleccion')==1)
				{
					$s=1;
					break;
				}
			}
			if($s==1)
			{
				$this->salida .= '<table width="70%" align="center">';
				$this->salida .= '<tr align="center">';
				$this->salida .= '<td align="center">';
				$this->salida .= '<label class="label_error">No se puede borrar la agenda por tener citas asignadas</label>';
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
				$this->salida .= "</table>";
				$this->salida .= '<br>';
			}
			foreach($_REQUEST as $v=>$datos)
			{
				if($v!='modulo' and $v!='metodo' and $v!='tipo' and $v!='SIIS_SID' and $v!='DiaEspe' and substr_count ($v,'seleccion')!=1)
				{
					$vec[$v]=$datos;
				}
			}
			$accion=ModuloGetURL('app','AgendaMedicaCoc','admin','BorrarAgenda',$vec);
			$this->salida.='<form name="siguiente" method="post" action="'.$accion.'">';
			$this->salida .= '<table width="70%" align="center" class="modulo_table_list">';
			$this->salida .= '<tr align="center" class="modulo_table_title">';
			$this->salida .= '<td align="center">';
			$this->salida .= "Fecha";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "Selección";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$i=0;
			while($i<sizeof($turnos[0]))
			{
				$vec['TurnoAgenda']='';
				$a=array_keys($turnos[0],$turnos[0][$i]);
				if(sizeof($a)==1)
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
					$vec['DiaEspe']=$turnos[0][$i];
					$vec['TurnoAgenda']=$turnos[1][$i];
					$accion=ModuloGetURL('app','AgendaMedicaCoc','admin','ListadoDiaAgenda',$vec);
					$this->salida .='<a href="'.$accion.'">'.$turnos[0][$i].'</a>';
					$this->salida .= "</td>";
					$this->salida .= '<td align="center">';
					$this->salida .= '<input type="checkbox" name="seleccion'.$i.'" value="'.$turnos[1][$i].'" class="input-submit">';
					$this->salida .= "</td>";
					$this->salida .= "</tr>";
				}
				else
				{
					$fecha=$turnos[0][$i];
					$vec['DiaEspe']=$turnos[0][$i];
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
					while($fecha==$turnos[0][$i])
					{
						$vec['TurnoAgenda'].=$turnos[1][$i].',';
						$i++;
					}
					$i--;
					$accion=ModuloGetURL('app','AgendaMedicaCoc','admin','ListadoDiaAgenda',$vec);
					$this->salida .='<a href="'.$accion.'">'.$turnos[0][$i].'</a>';
					$this->salida .= "</td>";
					$this->salida .= '<td align="center">';
					$this->salida .= '<input type="checkbox" name="seleccion'.$i.'" value="'.$vec['TurnoAgenda'].'" class="input-submit">';
					$this->salida .= "</td>";
					$this->salida .= "</tr>";
				}
				$i++;
			}
			$this->salida .= "</table>";
			$this->salida .= '<br>';
			$this->salida .= '<table align="center" width="70%" border="0">';
			$this->salida .= '<tr>';
			$this->salida .= '<td align="center">';
			$this->salida .= '<input type="submit" name="Borrar" value="Borrar" class="input-submit">';
			$this->salida .= '</td>';
			$this->salida .= '<td align="center">';
			$vec='';
			foreach($_REQUEST as $v=>$datos)
			{
				if($v!='modulo' and $v!='metodo' and $v!='tipo' and $v!='SIIS_SID' and $v!='DiaEspe' and $v!='TurnoAgenda' and substr_count ($v,'seleccion')!=1)
				{
					$vec[$v]=$datos;
				}
			}
			$accion=ModuloGetURL('app','AgendaMedicaCoc','admin','CambiarAgenda',$vec);
			$this->salida .= '<input type="submit" name="Cambiar" value="Cambiar" class="input-submit" onclick="form.action='."'".$accion."'".'">';
			$this->salida .= '</form>';
			$this->salida .= '</td>';
			$this->salida .= '<td align="center">';
			$vec='';
			foreach($_REQUEST as $v=>$datos)
			{
				if($v!='modulo' and $v!='tipo' and $v!='SIIS_SID' and $v!='metodo' and $v!='tercero' and $v!='tipoid' and $v!='nombrep' and substr_count ($v,'seleccion')!=1)
				{
					$vec[$v]=$datos;
				}
			}
			$accion=ModuloGetURL('app','AgendaMedicaCoc','admin','ListarProfesionales',$vec);
			$this->salida .= '<form name="volver" method="post" action="'.$accion.'">';
			$this->salida .= '<input type="submit" name="volver" value="volver" class="input-submit">';
			$this->salida .= '</form>';
			$this->salida .= '</td>';
			$this->salida .= '</tr>';
			$this->salida .= '</table>';
			$this->salida .= '<br>';
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		else
		{
			$this->salida=ThemeAbrirTabla('BORRAR Y CAMBIAR AGENDA');
			$this->salida .='<br>';
			$this->salida .= '<table width="70%" align="center" class="modulo_table_list">';
			$this->salida .= '<tr align="center" class="modulo_table_title">';
			$this->salida .= '<td align="center">';
			$this->salida .= "Empresa";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "Departamento";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "Tipo de Cita";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= '<tr class="modulo_list_oscuro">';
			$this->salida .= '<td align="center">';
			$this->salida .= $_SESSION['BorrarAgenda']['nomemp'];
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= $_SESSION['BorrarAgenda']['nomdep'];
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= $_SESSION['BorrarAgenda']['nombre'];
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= "</table>";
			$this->salida .= '<br>';
			$this->salida .= '<table width="70%" align="center" class="modulo_table_list">';
			$this->salida .= '<tr align="center" class="modulo_table_title">';
			$this->salida .= '<td align="center">';
			$this->salida .= "Profesional";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= '<tr class="modulo_list_oscuro">';
			$this->salida .= '<td align="center">';
			$this->salida .= $_SESSION['BorrarAgenda']['nombrep'];
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= "</table>";
			$this->salida .= '<br>';
			$this->salida .= '<table align="center" width="70%" border="0">';
			$this->salida .= '<tr>';
			$this->salida .= '<td align="center" class="label_error">';
			$this->salida .= 'No existen datos para este profesional.';
			$this->salida .= '</td>';
			$this->salida .= '</tr>';
			$this->salida .= '</table>';
			$this->salida .= '<br>';
			$this->salida .= '<table align="center" width="70%" border="0">';
			$this->salida .= '<tr>';
			$this->salida .= '<td align="center">';
			$vec='';
			foreach($_REQUEST as $v=>$datos)
			{
				if($v!='modulo' and $v!='tipo' and $v!='SIIS_SID' and $v!='metodo' and $v!='tercero' and $v!='tipoid' and $v!='nombrep' and substr_count ($v,'seleccion')!=1)
				{
					$vec[$v]=$datos;
				}
			}
			$accion=ModuloGetURL('app','AgendaMedicaCoc','admin','ListarProfesionales',$vec);
			$this->salida .= '<form name="volver" method="post" action="'.$accion.'">';
			$this->salida .= '<input type="submit" name="volver" value="volver" class="input-submit">';
			$this->salida .= '</form>';
			$this->salida .= '</td>';
			$this->salida .= '</tr>';
			$this->salida .= '</table>';
			$this->salida .= '<br>';
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
	}

	function ListadoDiaAgenda()
	{
		$turnosdia=$this->ListadoTurnosDia();
		if($turnosdia)
		{
			$this->salida=ThemeAbrirTabla('BORRAR Y CAMBIAR AGENDA');
			$this->salida .='<br>';
			$this->salida .= '<table width="70%" align="center" class="modulo_table_list">';
			$this->salida .= '<tr align="center" class="modulo_table_title">';
			$this->salida .= '<td align="center">';
			$this->salida .= "Empresa";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "Departamento";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "Tipo de Cita";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= '<tr class="modulo_list_oscuro">';
			$this->salida .= '<td align="center">';
			$this->salida .= $_SESSION['BorrarAgenda']['nomemp'];
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= $_SESSION['BorrarAgenda']['nomdep'];
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= $_SESSION['BorrarAgenda']['nombre'];
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= "</table>";
			$this->salida .= '<br>';
			$this->salida .= '<table width="70%" align="center" class="modulo_table_list">';
			$this->salida .= '<tr align="center" class="modulo_table_title">';
			$this->salida .= '<td align="center">';
			$this->salida .= "Profesional";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "Fecha";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= '<tr class="modulo_list_oscuro">';
			$this->salida .= '<td align="center">';
			$this->salida .= $_SESSION['BorrarAgenda']['nombrep'];
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$a=explode("-",$_REQUEST['DiaEspe']);
			$this->salida .= strftime("%d de %B de %Y",mktime(0,0,0,$a[1],$a[2],$a[0]));
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= "</table>";
			$this->salida .= '<br>';
			$s=0;
			foreach($_REQUEST as $v=>$datos)
			{
				if(substr_count ($v,'seleccion')==1)
				{
					$s=1;
					break;
				}
			}
			if($s==1)
			{
				$this->salida .= '<table width="70%" align="center">';
				$this->salida .= '<tr align="center">';
				$this->salida .= '<td align="center">';
				$this->salida .= '<label class="label_error">No se puede borrar la agenda por tener citas asignadas</label>';
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
				$this->salida .= "</table>";
				$this->salida .= '<br>';
			}
			foreach($_REQUEST as $v=>$datos)
			{
				if($v!='modulo' and $v!='metodo' and $v!='tipo' and $v!='SIIS_SID' and substr_count ($v,'seleccion')!=1)
				{
					$vec[$v]=$datos;
				}
			}
			$accion=ModuloGetURL('app','AgendaMedicaCoc','admin','BorrarAgendaDia',$vec);
			$this->salida.='<form name="siguiente" method="post" action="'.$accion.'">';
			$this->salida .= '<table width="70%" align="center" class="modulo_table_list">';
			$this->salida .= '<tr align="center" class="modulo_table_title">';
			$this->salida .= '<td align="center">';
			$this->salida .= "Hora";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "Selección";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$i=0;
			while($i<sizeof($turnosdia[0]))
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
				$this->salida .=$turnosdia[0][$i];
				$this->salida .= "</td>";
				$this->salida .= '<td align="center">';
				$this->salida .= '<input type="checkbox" name="seleccion'.$i.'" value="'.$turnosdia[1][$i].','.$turnosdia[2][$i].'" class="input-submit">';
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
				$i++;
			}
			$this->salida .= "</table>";
			$this->salida .= '<br>';
			$this->salida .= '<table align="center" width="70%" border="0">';
			$this->salida .= '<tr>';
			$this->salida .= '<td align="center">';
			$this->salida .= '<input type="submit" name="Borrar" value="Borrar" class="input-submit">';
			$this->salida .= '</form>';
			$this->salida .= '</td>';
			$this->salida .= '<td align="center">';
			foreach($_REQUEST as $v=>$datos)
			{
				if($v!='modulo' and $v!='tipo' and $v!='SIIS_SID' and $v!='metodo' and $v!='DiaEspe' and substr_count ($v,'seleccion')!=1)
				{
					$vec1[$v]=$datos;
				}
			}
			$accion=ModuloGetURL('app','AgendaMedicaCoc','admin','ListadoAgendaMesTurnos',$vec1);
			$this->salida .= '<form name="volver" method="post" action="'.$accion.'">';
			$this->salida .= '<input type="submit" name="volver" value="volver" class="input-submit">';
			$this->salida .= '</form>';
			$this->salida .= '</td>';
			$this->salida .= '</tr>';
			$this->salida .= '</table>';
			$this->salida .= '<br>';
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		else
		{
			return false;
		}
	}

	function CambiarAgenda()
	{
		$this->salida=ThemeAbrirTabla('CAMBIAR AGENDA');
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

//Mantenimiento Profesionales

	function MantenimientoProfesionales()
	{
		$url[0]='app';
		$url[1]='AgendaMedicaCoc';
		$url[2]='admin';
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
		if(empty($_SESSION['ManProf']['departamento']))
		{
			$_SESSION['ManProf']['nomemp']=$_REQUEST['ManProf']['descripcion1'];
			$_SESSION['ManProf']['nomdep']=$_REQUEST['ManProf']['descripcion2'];
			$_SESSION['ManProf']['empresa']=$_REQUEST['ManProf']['empresa_id'];
			$_SESSION['ManProf']['departamento']=$_REQUEST['ManProf']['departamento'];
		}
		else
		{
			 if(!empty($_REQUEST['ManProf']['departamento']) and $_REQUEST['ManProf']['departamento']!=$_SESSION['ManProf']['departamento'])
			 {
			 	$_SESSION['ManProf']['nomemp']=$_REQUEST['ManProf']['descripcion1'];
				$_SESSION['ManProf']['nomdep']=$_REQUEST['ManProf']['descripcion2'];
				$_SESSION['ManProf']['empresa']=$_REQUEST['ManProf']['empresa_id'];
				$_SESSION['ManProf']['departamento']=$_REQUEST['ManProf']['departamento'];
			 }
		}
		unset($_SESSION['ManProf']['Profesional']);
		$profesionales=$this->ProfesionalesCompleto();
		$this->salida=ThemeAbrirTabla('LISTADO PROFESIONALES');
		$this->salida .='<br>';
		$this->salida .= '<table width="70%" align="center" class="modulo_table_list">';
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
		$this->salida .= "<BR>";
		if(!$_REQUEST['Busqueda'])
		{
			$Busqueda=1;
			$vec['Busqueda']=1;
		}
		else
		{
			$Busqueda=$_REQUEST['Busqueda'];
		}
  $this->salida .= "  <table border=\"0\" width=\"98%\" align=\"center\" class=\"modulo_table\">";
		$this->salida .= "		<tr>";
		$this->salida .= "		   <td width=\"60%\" >";
		$this->salida .= "      <BR><br><table border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "          <tr><td><fieldset><legend class=\"field\">BUSCAR DATOS ADMISION</legend>";
		$this->salida .= "			      <table width=\"90%\" align=\"center\">";
		$accion=ModuloGetURL('app','AgendaMedicaCoc','admin','BuscarPacientes',array('Busqueda'=>$_REQUEST['Busqueda']));
		$this->salida .= "             <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
		if($Busqueda=='1'){
			$this->salida .= "				        <tr><td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"TipoDocumento\" class=\"select\">";
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
			$this->salida .= "				        <tr><td class=\"".$this->SetStyle("Documento")."\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" value=\"".$_REQUEST['Documento']."\"></td></tr>";
		}
		if($Busqueda=='2'){
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "				        <tr><td class=\"label\">NOMBRE</td><td><input type=\"text\" class=\"input-text\" name=\"nombres\" maxlength=\"32\"></td></tr>";
		}

		$this->salida .= "               <tr><td align=\"right\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\"><br></form></td>";
		$this->salida .= "		  </fieldset></td></tr></table>";
		$this->salida .= "	</table>";
		$this->salida .= "		   </td>";
		$this->salida .= "		   <td>";
		$this->salida .= "      <BR><table border=\"0\" width=\"92%\" align=\"center\">";
		$accion=ModuloGetURL('app','AgendaMedicaCoc','admin','ListarProfe');
		$this->salida .= "             <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
		$this->salida .= "          <tr><td><fieldset><legend class=\"field\">BUSQUEDA AVANZADA</legend>";
		$this->salida .= "			      <table width=\"90%\" align=\"center\">";
		$this->salida .= "				       <tr><br><td class=\"label\">TIPO BUSQUEDA: </td><td><select name=\"Busqueda\" class=\"select\">";
		$this->salida .="                   <option value=\"1\" selected>DOCUMENTO</option>";
		$this->salida .="                   <option value=\"2\">NOMBRE</option>";
		$this->salida .= "              </select></td></tr>";
		$this->salida .= "				       <tr><td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Busc\" value=\"BUSCAR\"></td></tr>";
		$this->salida .= "			      </form>";
		$this->salida .= "			         </table>";
		$this->salida .= "		  </fieldset></td></tr></table>";
		$this->salida .= "		   </td>";
		$this->salida .= "		</tr>";
		$this->salida .= "	</table>";
		$this->salida .='<br>';
		if($mensaje){
						$this->salida .= "			<p class=\"label_error\" align=\"center\">$mensaje</p>";
				}
				$vec='';
				foreach($_REQUEST as $v=>$datos)
				{
					if($v!='SIIS_SID' and $v!='modulo' and $v!='metodo')
					{
						$vec[$v]=$datos;
					}
				}
		$this->salida .= '<br>';
		if($arr!=false)
		{
			$this->salida .= '<table align="center" width="70%" border="0" class="modulo_table_list">';
			$this->salida .= '<tr align="center" class="modulo_table_title">';
			$this->salida .= '<td>';
			$this->salida .= 'Profesionales';
			$this->salida .= '</td>';
			$this->salida .= '<td>';
			$this->salida .= 'Estado';
			$this->salida .= '</td>';
			$this->salida .= '<td>';
			$this->salida .= '</td>';
			$this->salida .= '</tr>';
			$i=0;
			while($i<sizeof($arr[0]))
			{
				if($spy==0)
				{
					$this->salida .= '<tr class="modulo_list_claro">';
					$spy=1;
				}
				else
				{
					$this->salida .= '<tr class="modulo_list_oscuro">';
					$spy=0;
				}
				$this->salida .= '<td>';
				$this->salida .= $arr[0][$i];
				$this->salida .= '</td>';
				$this->salida .= '<td>';
				if($arr[1][$i]==1)
				{
					$this->salida .= 'Activo';
				}
				else
				{
					$this->salida .= 'Inactivo';
				}
				$this->salida .= '</td>';
				$this->salida .= '<td>';
				$this->salida .= '<a href="'.ModuloGetURL('app','AgendaMedicaCoc','admin','PantallaProfesional',array('tercero'=>$arr[3][$i],'tipoid'=>$arr[2][$i],'nombrep'=>$arr[0][$i],'TipoProf'=>$arr[4][$i],'Sexo'=>$arr[5][$i],'TarjProf'=>$arr[6][$i],'estado'=>$arr[1][$i])).'">Ir</a>';
				$this->salida .= '</td>';
				$this->salida .= '</tr>';
				$i++;
			}
			$this->salida .= '</table>';
		}
		$this->salida .= '<br>';
		$this->salida .= '<table align="center" width="70%" border="0">';
		$this->salida .= '<tr>';
		$this->salida .= '<td align="center">';
		$accion=ModuloGetURL('app','AgendaMedicaCoc','admin','MantenimientoProfesionales');
		$this->salida .= '<form name="volver" method="post" action="'.$accion.'">';
		$this->salida .= '<input type="submit" name="volver" value="volver" class="input-submit">';
		$this->salida .= '</form>';
		$this->salida .= '</td>';
		if($arr==false and !empty($_REQUEST['Documento']))
		{
			$this->salida .= '<td align="center">';
			$accion=ModuloGetURL('app','AgendaMedicaCoc','admin','PantallaProfesional');
			$this->salida .= '<form name="Crear" method="post" action="'.$accion.'">';
			$this->salida .= '<input type="submit" name="Crear" value="Crear" class="input-submit">';
			$this->salida .= '</form>';
			$this->salida .= '</td>';
		}
		$this->salida .= '</tr>';
		$this->salida .= '</table>';
		$this->salida .= '<br>';
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function PantallaProfesional()
	{
		if(empty($_REQUEST['Crear']))
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
			}
		}
		else
		{
			
		}
		$this->salida=ThemeAbrirTabla('PROFESIONAL - '.$_SESSION['ManProf']['Profesional']['nombrep']);
		$this->salida .='<br>';
		$this->salida .= '<table width="70%" align="center" class="modulo_table_list">';
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
		$this->salida .= '<table width="70%" align="center" border="1" class="modulo_table_list">';
		$this->salida .= '<tr align="left" class="modulo_list_claro" width="30%">';
		$this->salida .= '<td align="left">';
		$this->salida .= 'IDENTIFICACIÓN:';
		$this->salida .= "</td>";
		$this->salida .= '<td align="left">';
		$this->salida .= $_SESSION['ManProf']['Profesional']['tipoid'].' - '.$_SESSION['ManProf']['Profesional']['tercero'];
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$accion=ModuloGetURL('app','AgendaMedicaCoc','admin','Desicion');
		$this->salida .= '<form name="volver" method="post" action="'.$accion.'">';
		$this->salida .= '<tr align="left" class="modulo_list_oscuro">';
		$this->salida .= '<td align="left">';
		$this->salida .= 'NOMBRE:';
		$this->salida .= "</td>";
		$this->salida .= '<td align="left">';
		$this->salida .= '<input type="text" name="nombrep"  size="45" maxlength=40 value="'.$_SESSION['ManProf']['Profesional']['nombrep'].'" class="input-text">';
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
		$this->salida .= '<input type="text" name="TarjProf" maxlength=20 value="'.$_SESSION['ManProf']['Profesional']['TarjProf'].'" class="input-text">';
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= '<tr align="left" class="modulo_list_oscuro">';
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
		$this->salida .= "</table>";
		$this->salida .='<br>';
		$tipo_especialidad=$this->TipoEspecialidades();
		$especialidades=$this->Especialidad();
		if($tipo_especialidad)
		{
			$this->salida .= '<table width="70%" align="center" border="1" class="modulo_table_list">';
			$this->salida .= '<tr align="center" class="modulo_table_title">';
			$this->salida .= '<td align="center">';
			$this->salida .= "Especialidades";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center" width="30%">';
			$this->salida .= "Acción";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			if($especialidades)
			{
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
					$this->salida .= $v;
					$this->salida .= "</td>";
					$this->salida .= '<td align="center">';
					$accion=ModuloGetURL('app','AgendaMedicaCoc','admin','EliminarEspecialidad',array('espe'=>$k));
					$this->salida .= '<a href="'.$accion.'">ELIMINAR</a>';
					$this->salida .= "</td>";
					$this->salida .= "</tr>";
				}
			}
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
			$this->salida .= '<select name="especialidad" class="select">';
			$this->salida .= '<option value="-1">--SELECCIONE--</option>';
			foreach($tipo_especialidad as $k=>$v)
			{
				$this->salida .= '<option value="'.$k.'">'.$v.'</option>';
			}
			$this->salida .= '</select>';
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= '<input type="submit" name="ADICIONAR" value="ADICIONAR" class="input-submit">';
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= "</table>";
			$this->salida .= '<br>';
		}
		$this->salida .= '<table align="center" width="70%" border="0">';
		$this->salida .= '<tr>';
		$this->salida .= '<td align="center">';
		$this->salida .= '<input type="submit" name="volver" value="volver" class="input-submit">';
		$this->salida .= '</td>';
		$this->salida .= '<td align="center">';
		$this->salida .= '<input type="submit" name="guardar" value="guardar" class="input-submit">';
		$this->salida .= '</form>';
		$this->salida .= '</td>';
		$this->salida .= '</tr>';
		$this->salida .= '</table>';
		$this->salida .= '<br>';
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
