<?php



/**
* Modulo de Creacion Agenda
*
* Modulo para crear la agenda de los profesionales, para poder realizar la asignacion de citas
* @author Jaime Andres Valencia Salazar <salazarvaljandresv@yahoo.es>
* @version 1.0
* @package SIIS
*/


/**
* CreacionAgenda_HTML
*
* Clase para realizar la presentacion html de las pantallas de la creacion de la agenda.
*
*/


class app_CreacionAgenda_userclasses_HTML extends app_CreacionAgenda_user
{



/**
* Esta funcion Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/


	function app_CreacionAgenda_user_HTML()
	{
	    $this->app_CreacionAgenda_user(); //Constructor del padre 'modulo'
		$this->salida='';
		return true;
	}





/**
* Esta funcion es la que llama la funcion para mostrar las acciones que puede realizar el usuario
*
* @access public
* @return boolean Para identificar que se realizo.
*/

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
		$accion=ModuloGetURL('app','CreacionAgenda','user','CreacionAgenda');
		$this->salida .='<a href="'.$accion.'">Creación Agenda Médica</a>';
		$this->salida .='</td>';
		$this->salida .='</tr>';
		$this->salida .='<tr class="modulo_list_oscuro">';
		$this->salida .='<td align="center">';
		$accion=ModuloGetURL('app','CreacionAgenda','user','CambiarBorrarAgenda');
		$this->salida .='<a href="'.$accion.'">Cambiar y Borrar Agenda Médica</a>';
		$this->salida .='</td>';
		$this->salida .='</tr>';
		$this->salida .='</table>';
		$this->salida .='<br>';
		$this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
		$this->salida .='<tr>';
		$this->salida .='<td align="center">';
		$accion=ModuloGetURL('system','Menu','user','main');
		$this->salida .='<a href="'.$accion.'">Volver</a>';
		$this->salida .='</td>';
		$this->salida .='</tr>';
		$this->salida .='</table>';
		$this->salida .='<br>';
		$this->salida .= ThemeCerrarTabla();
		unset($_SESSION['CreacionAgenda']);
		return true;
	}





//Creación de Agenda Medica
/**
* Esta funcion es la que muestra:
* el listado de profesionales que se le pueden generar agenda.
* muestra las agendas que tenga el profesional activas
* muestra la forma para elegir las condiciones de la nueva agenda
* muestra la agenda con la informacion
*
* @access public
* @return boolean Para identificar que se realizo.
*/

	function CreacionAgenda()
	{
		if($_REQUEST['volver1']=='true')
		{
			unset($_SESSION['CreacionAgenda']);
		}
		if((empty($_SESSION['CreacionAgenda']['Cita']) or $_REQUEST['Citas']['tipo_consulta_id']!=$_SESSION['CreacionAgenda']['Cita']) and !empty($_REQUEST['Citas']['tipo_consulta_id']))
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
			$url[1]='CreacionAgenda';
			$url[2]='user';
			$url[3]='CreacionAgenda';
			$url[4]='Citas';
			$Cita=$this->CitaConsulta($url);
			if($Cita==false)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "NO EXISTEN PROFESIONALES PARA ESA EMPRESA.";
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
					$this->salida  = ThemeAbrirTabla('CREACIÓN AGENDA MÉDICA');
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
						$this->salida .= '<a href="'.ModuloGetURL('app','CreacionAgenda','user','CreacionAgenda',array('tercero'=>$profesionales[1][$i],'tipoid'=>$profesionales[0][$i],'nombrep'=>$profesionales[2][$i])).'">Ir</a>';
						$this->salida .= '</td>';
						$this->salida .= '</tr>';
						$i++;
					}
					$this->salida .= '</table>';
					$this->salida .= '<br>';
					$this->salida .= '<table align="center" width="70%" border="0">';
					$this->salida .= '<tr>';
					$this->salida .= '<td align="center">';
					$accion=ModuloGetURL('app','CreacionAgenda','user','CreacionAgenda',array('volver1'=>'true'));
					$this->salida .= '<form name="Volver" method="post" action="'.$accion.'">';
					$this->salida .= '<input type="submit" name="volver" value="VOLVER" class="input-submit">';
					$this->salida .= '</form>';
					$this->salida .= '</td>';
					$this->salida .= '</tr>';
					$this->salida .= '</table>';
					$this->salida .= ThemeCerrarTabla();
				}
				else
				{
					$this->salida  = ThemeAbrirTabla('CREACIÓN AGENDA MÉDICA');
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
					$this->salida .= '<table align="center" width="70%" border="0">';
					$this->salida .= '<tr>';
					$this->salida .= '<td align="center">';
					$this->salida .= '<label class="label_error">NO EXISTEN PROFESIONALES CON ESTE TIPO DE CONSULTA.</label>';
					$this->salida .= '</td>';
					$this->salida .= '</tr>';
					$this->salida .= '<tr>';
					$this->salida .= '<td align="center">';
					$accion=ModuloGetURL('app','CreacionAgenda','user','CreacionAgenda',array('volver1'=>'true'));
					$this->salida .= '<form name="Volver" method="post" action="'.$accion.'">';
					$this->salida .= '<input type="submit" name="volver" value="VOLVER" class="input-submit">';
					$this->salida .= '</form>';
					$this->salida .= '</td>';
					$this->salida .= '</tr>';
					$this->salida .= '</table>';
					$this->salida .= ThemeCerrarTabla();
					return true;
				}
			}
			else
			{
				if(empty($_REQUEST['accion']))
				{
          //INICIO MODI
          $this->clear();
          //FIN MODI
					$this->salida  = ThemeAbrirTabla('CREACIÓN AGENDA MÉDICA');
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
					$accion=ModuloGetURL('app','CreacionAgenda','user','CreacionAgenda',array('accion'=>'add'));
					$this->salida .='<form name="volver" action="'.$accion.'" method="post">';
					$this->salida .='<input type="submit" name="crear" value="CREAR AGENDA" class="input-submit">';
					$this->salida .='</form>';
					$this->salida .= '</td>';
					$this->salida .= '<td align="left" width="50%">';
					$accion=ModuloGetURL('app','CreacionAgenda','user','CreacionAgenda',array('volver2'=>'true'));
					$this->salida .='<form name="volver" action="'.$accion.'" method="post">';
					$this->salida .='<input type="submit" name="volver" value="VOLVER" class="input-submit">';
					$this->salida .='</form>';
					$this->salida .= '</td>';
					$this->salida .= '</tr>';
					$turnos=$this->TurnosProgramados();
	/*
	* Cambiamos el formato timestamp a un formato de fecha legible para el usuario
	*/
// 	function FormateoFechaLocal($fecha)
// 	{
// 
// 			if(!empty($fecha))
// 			{
// 					$f=explode(".",$fecha);
// 					$fecha_arreglo=explode(" ",$f[0]);
// 					$fecha_real=explode("-",$fecha_arreglo[0]);
// 					return strftime("%A, %d de %B de %Y",strtotime($fecha_arreglo[0]));
// 
// 			}
// 			else
// 			{
// 				return "-----";
// 			}
// 
// 			return true;
// 	}
					if($turnos==0)
					{
						$this->salida .= '<tr align="center">';
						$this->salida .= '<td class="label_error" colspan="2">';
						$this->salida .='<br>';
						$this->salida .= 'NO EXISTE AGENDA PARA ESTE PROFESIONAL.';
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
						$accion=ModuloGetURL('app','CreacionAgenda','user','CreacionAgenda',$vec);
						$this->salida .= '<a href='.$accion.'><font size=1>VER TURNOS</font></a>';
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
                $this->salida.=$this->FormateoFechaMes($turnos[2][$i]);
								//$this->salida.=$k[1];
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
                  $this->salida.=$this->FormateoFechaMes($turnos[2][$i]);
									//$this->salida.=$k[1];
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
                $this->salida.=$this->FormateoFechaDia($turnos[2][$i]);
								//$this->salida.=$k[2];
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
                  $this->salida.=$this->FormateoFechaDia($turnos[2][$i]);
									//$this->salida.=$k[2];
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
							$this->salida  = ThemeAbrirTabla('CREACIÓN AGENDA MÉDICA');
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
							$this->salida .= '<table width="50%" align="center" class="modulo_table_list" border="0">';
							$this->salida .= '<tr>';
							$this->salida .= '<td align="center">';
							if($a<>date("Y"))
							{
								$this->salida .= '<a href="'.ModuloGetURL('app','CreacionAgenda','user','CreacionAgenda',array('accion'=>'add','a'=>'0')).'">actual</a>';
							}
							$this->salida .= '<a href="'.ModuloGetURL('app','CreacionAgenda','user','CreacionAgenda',array('accion'=>'add','a'=>($_REQUEST['a']-1))).'"><<</a> '.$a.' <a href="'.ModuloGetURL('app','CreacionAgenda','user','CreacionAgenda',array('accion'=>'add','a'=>($_REQUEST['a']+1))).'">>></a>';
							$this->salida .= '</td>';
							$this->salida .= '</tr>';
							$this->salida .= '</table>';
							$this->salida .= '<br>';
							$this->salida .= '<form name="cosa" action="'.ModuloGetURL('app','CreacionAgenda','user','CreacionAgenda',array('accion'=>'add','a'=>$_REQUEST['a'])).'" method="post">';
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
              //INICIO MODI
							$this->salida .= '<input type="checkbox" name="mes'.$mes.'" value="'.$mes.'"';
              if ($_SESSION['mes']['1'] == $mes){
							if($i<=0)
							{
								$this->salida .= 'checked="true">';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true">';
								$i--;
							}
              }else{
							if($i<=0)
							{
								$this->salida .= '>';
							}
							else
							{
								$this->salida .= ' disabled="true">';
								$i--;
							}
              }
							$mes++;
							$this->salida .= '</td>';
							$this->salida .= '<td>';
							$this->salida .= 'Enero';
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
              if ($_SESSION['dias']['1'] == $dias){
							if($s<=0)
							{
								$this->salida .= 'checked="true"><br>1';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true"><br>1';
								$s--;
							}
              }else{
							if($s<=0)
							{
								$this->salida .= '><br>1';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>1';
								$s--;
							}
              }
							$dias++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
              if ($_SESSION['dias']['2'] == $dias){
							if($s<=0)
							{
								$this->salida .= 'checked="true"><br>2';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true"><br>2';
								$s--;
							}
              }else{
							if($s<=0)
							{
								$this->salida .= '><br>2';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>2';
								$s--;
							}
              }
							$dias++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
              if ($_SESSION['dias']['3'] == $dias){
							if($s<=0)
							{
								$this->salida .= 'checked="true"><br>3';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true"><br>3';
								$s--;
							}
              }else{
							if($s<=0)
							{
								$this->salida .= '><br>3';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>3';
								$s--;
							}
              }
							$dias++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
              if ($_SESSION['dias']['4'] == $dias){
							if($s<=0)
							{
								$this->salida .= 'checked="true"><br>4';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true"><br>4';
								$s--;
							}
              }else{
 							if($s<=0)
							{
								$this->salida .= '><br>4';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>4';
								$s--;
							}
              }
              $dias++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
              if ($_SESSION['dias']['5'] == $dias){
							if($s<=0)
							{
								$this->salida .= 'checked="true"><br>5';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true"><br>5';
								$s--;
							}
              }else{
							if($s<=0)
							{
								$this->salida .= '><br>5';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>5';
								$s--;
							}
              }
							$dias++;							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
              if ($_SESSION['dias']['6'] == $dias){
							if($s<=0)
							{
								$this->salida .= 'checked="true"><br>6';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true"><br>6';
								$s--;
							}
              }else{
							if($s<=0)
							{
								$this->salida .= '><br>6';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>6';
								$s--;
							}
              }
							$dias++;
              $this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
              if ($_SESSION['dias']['7'] == $dias){
							if($s<=0)
							{
								$this->salida .= 'checked="true"><br>7';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true"><br>7';
								$s--;
							}
              }else{
							if($s<=0)
							{
								$this->salida .= '><br>7';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>7';
								$s--;
							}
              }
							$dias++;
              $this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
              if ($_SESSION['dias']['8'] == $dias){
							if($s<=0)
							{
								$this->salida .= 'checked="true"><br>8';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true"><br>8';
								$s--;
							}
              }else{
							if($s<=0)
							{
								$this->salida .= '><br>8';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>8';
								$s--;
							}
              }
							$dias++;
              $this->salida .= '</td>';
							$this->salida .= '<td colspan="4">';
              if (!empty($_SESSION['semana']['todos']))
						    $this->salida .= '<input type="checkbox" name="todos" onclick="mesesdias(this.form)" checked="true">Meses y Días';
              else
						    $this->salida .= '<input type="checkbox" name="todos" onclick="mesesdias(this.form)">Meses y Días';
							$this->salida .= '</td>';
							$this->salida .= '<td colspan="2">';
              if (!empty($_SESSION['semana']['todosd']))
  							$this->salida .= '<input type="checkbox" name="todosd" onclick="dias(this.form)" checked="true">Días';
              else
  							$this->salida .= '<input type="checkbox" name="todosd" onclick="dias(this.form)">Días';
							$this->salida .= '</td>';
							$this->salida .= '<td>';
              if (!empty($_SESSION['semana']['nosabados']))
                $this->salida .= '<input type="checkbox" name="nosabados" onclick="sabados(this.form)" checked="true">';
              else
							  $this->salida .= '<input type="checkbox" name="nosabados" onclick="sabados(this.form)">';
							$this->salida .= '</td>';
							$this->salida .= '<td>';
							$this->salida .= 'Sab';
							$this->salida .= '</td>';
							$this->salida .= '<td>';
              if (!empty($_SESSION['semana']['nodomingos']))
							  $this->salida .= '<input type="checkbox" name="nodomingos" onclick="domingos(this.form)" checked="true">';
              else
							  $this->salida .= '<input type="checkbox" name="nodomingos" onclick="domingos(this.form)">';
							$this->salida .= '</td>';
							$this->salida .= '<td>';
							$this->salida .= 'Dom';
							$this->salida .= '</td>';
							$this->salida .= '<td>';
              if (!empty($_SESSION['semana']['nofestivos']))
  							$this->salida .= '<input type="checkbox" name="nofestivos" checked="true">';
              else
  							$this->salida .= '<input type="checkbox" name="nofestivos">';
							$this->salida .= '</td>';
							$this->salida .= '<td>';
							$this->salida .= 'Fes';
							$this->salida .= '</td>';
							$this->salida .= '</tr>';
							$this->salida .= '<tr>';
							$this->salida .= '<td>';
							$this->salida .= '<input type="checkbox" name="mes'.$mes.'" value="'.$mes.'"';
              if ($_SESSION['mes']['2'] == $mes){
							if($i<=0)
							{
								$this->salida .= 'checked="true">';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true">';
								$i--;
							}
              }else{
							if($i<=0)
							{
								$this->salida .= '>';
							}
							else
							{
								$this->salida .= ' disabled="true">';
								$i--;
							}
              }
							$mes++;
							$this->salida .= '</td>';
							$this->salida .= '<td>';
							$this->salida .= 'Febrero';
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
              if ($_SESSION['dias']['9'] == $dias){
							if($s<=0)
							{
								$this->salida .= 'checked="true"><br>9';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true"><br>9';
								$s--;
							}
              }else{
							if($s<=0)
							{
								$this->salida .= '><br>9';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>9';
								$s--;
							}
              }
							$dias++;
              $this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
              if ($_SESSION['dias']['10'] == $dias){
							if($s<=0)
							{
								$this->salida .= 'checked="true"><br>10';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true"><br>10';
								$s--;
							}
              }else{
							if($s<=0)
							{
								$this->salida .= '><br>10';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>10';
								$s--;
							}
              }
							$dias++;
              $this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
              if ($_SESSION['dias']['11'] == $dias){
							if($s<=0)
							{
								$this->salida .= 'checked="true"><br>11';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true"><br>11';
								$s--;
							}
              }else{
							if($s<=0)
							{
								$this->salida .= '><br>11';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>11';
								$s--;
							}
              }
							$dias++;
              $this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
              if ($_SESSION['dias']['12'] == $dias){
							if($s<=0)
							{
								$this->salida .= 'checked="true"><br>12';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true"><br>12';
								$s--;
							}
              }else{
							if($s<=0)
							{
								$this->salida .= '><br>12';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>12';
								$s--;
              }
              }
							$dias++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
              if ($_SESSION['dias']['13'] == $dias){
							if($s<=0)
							{
								$this->salida .= 'checked="true"><br>13';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true"><br>13';
								$s--;
							}
              }else{
							if($s<=0)
							{
								$this->salida .= '><br>13';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>13';
								$s--;
							}
              }
							$dias++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
              if ($_SESSION['dias']['14'] == $dias){
							if($s<=0)
							{
								$this->salida .= 'checked="true"><br>14';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true"><br>14';
								$s--;
							}
              }else{
							if($s<=0)
							{
								$this->salida .= '><br>14';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>14';
								$s--;
							}
              }
							$dias++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
              if ($_SESSION['dias']['15'] == $dias){
							if($s<=0)
							{
								$this->salida .= 'checked="true"><br>15';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true"><br>15';
								$s--;
							}
              }else{
							if($s<=0)
							{
								$this->salida .= '><br>15';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>15';
								$s--;
							}
              }
							$dias++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
              if ($_SESSION['dias']['16'] == $dias){
							if($s<=0)
							{
								$this->salida .= 'checked="true"><br>16';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true"><br>16';
								$s--;
							}
              }else{
							if($s<=0)
							{
								$this->salida .= '><br>16';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>16';
								$s--;
							}
              }
							$dias++;
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
              if ($_SESSION['mes']['3'] == $mes){
							if($i<=0)
							{
								$this->salida .= 'checked="true">';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true">';
								$i--;
							}
              }else{
							if($i<=0)
							{
								$this->salida .= '>';
							}
							else
							{
								$this->salida .= ' disabled="true">';
								$i--;
							}
              }
							$mes++;
							$this->salida .= '</td>';
							$this->salida .= '<td>';
							$this->salida .= 'Marzo';
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
             if ($_SESSION['dias']['17'] == $dias){
							if($s<=0)
							{
								$this->salida .= 'checked="true"><br>17';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true"><br>17';
								$s--;
							}
              }else{
							if($s<=0)
							{
								$this->salida .= '><br>17';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>17';
								$s--;
							}
              }
  						$dias++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
             if ($_SESSION['dias']['18'] == $dias){
							if($s<=0)
							{
								$this->salida .= 'checked="true"><br>18';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true"><br>18';
								$s--;
							}
              }else{
							if($s<=0)
							{
								$this->salida .= '><br>18';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>18';
								$s--;
							}
              }
 							$dias++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
             if ($_SESSION['dias']['19'] == $dias){
							if($s<=0)
							{
								$this->salida .= 'checked="true"><br>19';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true"><br>19';
								$s--;
							}
              }else{
							if($s<=0)
							{
								$this->salida .= '><br>19';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>19';
								$s--;
							}
              }
							$dias++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
              if ($_SESSION['dias']['20'] == $dias){
              if($s<=0)
							{
								$this->salida .= 'checked="true"><br>20';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true"><br>20';
								$s--;
							}
              }else{
              if($s<=0)
							{
								$this->salida .= '><br>20';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>20';
								$s--;
							}
              }
							$dias++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
              if ($_SESSION['dias']['21'] == $dias){
							if($s<=0)
							{
								$this->salida .= 'checked="true"><br>21';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true"><br>21';
								$s--;
							}
              }else{
							if($s<=0)
							{
								$this->salida .= '><br>21';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>21';
								$s--;
							}
              }
							$dias++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
              if ($_SESSION['dias']['22'] == $dias){
							if($s<=0)
							{
								$this->salida .= 'checked="true"><br>22';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true"><br>22';
								$s--;
							}
              }else{
							if($s<=0)
							{
								$this->salida .= '><br>22';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>22';
								$s--;
							}
              }
							$dias++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
              if ($_SESSION['dias']['23'] == $dias){
							if($s<=0)
							{
								$this->salida .= 'checked="true"><br>23';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true"><br>23';
								$s--;
							}
              }else{
							if($s<=0)
							{
								$this->salida .= '><br>23';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>23';
								$s--;
							}
              }
							$dias++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
              if ($_SESSION['dias']['24'] == $dias){
							if($s<=0)
							{
								$this->salida .= 'checked="true"><br>24';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true"><br>24';
								$s--;
							}
              }else{
							if($s<=0)
							{
								$this->salida .= '><br>24';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>24';
								$s--;
							}
              }
 							$dias++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              //INI MODI
              if ($_SESSION['ini']['hora']==='0' and $horaini==0 )
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" checked="true"><br>0';
              else
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'"><br>0';
              //FIN MODI
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['ini']['hora']==$horaini)
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" checked="true"><br>1';
              else
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" ><br>1';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['ini']['hora']==$horaini)
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" checked="true"><br>2';
              else
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" ><br>2';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['ini']['hora']==$horaini)
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" checked="true"><br>3';
              else
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" ><br>3';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['ini']['hora']==$horaini)
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" checked="true"><br>4';
              else
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" ><br>4';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['ini']['hora']==$horaini)
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" checked="true"><br>5';
              else
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" ><br>5';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['fin']['hora']==='0' and $horafin==0)
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" checked="true"><br>0';
              else
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" ><br>0';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['fin']['hora']==$horafin)
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" checked="true"><br>1';
              else
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" ><br>1';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['fin']['hora']==$horafin)
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" checked="true"><br>2';
              else
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" ><br>2';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['fin']['hora']==$horafin)
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" checked="true"><br>3';
              else
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" ><br>3';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['fin']['hora']==$horafin)
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" checked="true"><br>4';
              else
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" ><br>4';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['fin']['hora']==$horafin)
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" checked="true"><br>5';
              else
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" ><br>5';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '</tr>';
							$this->salida .= '<tr>';
							$this->salida .= '<td>';
							$this->salida .= '<input type="checkbox" name="mes'.$mes.'" value="'.$mes.'"';
              if ($_SESSION['mes']['4'] == $mes){
							if($i<=0)
							{
								$this->salida .= 'checked="true">';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true">';
								$i--;
							}
              }else{
							if($i<=0)
							{
								$this->salida .= '>';
							}
							else
							{
								$this->salida .= ' disabled="true">';
								$i--;
							}
              }
							$mes++;
							$this->salida .= '</td>';
							$this->salida .= '<td>';
							$this->salida .= 'Abril';
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
              if ($_SESSION['dias']['25'] == $dias){
							if($s<=0)
							{
								$this->salida .= 'checked="true"><br>25';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true"><br>25';
								$s--;
							}
              }else{
							if($s<=0)
							{
								$this->salida .= '><br>25';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>25';
								$s--;
							}
              }
							$dias++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
              if ($_SESSION['dias']['26'] == $dias){
							if($s<=0)
							{
								$this->salida .= 'checked="true"><br>26';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true"><br>26';
								$s--;
							}
              }else{
							if($s<=0)
							{
								$this->salida .= '><br>26';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>26';
								$s--;
							}
              }
							$dias++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
              if ($_SESSION['dias']['27'] == $dias){
							if($s<=0)
							{
								$this->salida .= 'checked="true"><br>27';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true"><br>27';
								$s--;
							}
              }else{
							if($s<=0)
							{
								$this->salida .= '><br>27';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>27';
								$s--;
							}
              }
							$dias++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
              if ($_SESSION['dias']['28'] == $dias){
							if($s<=0)
							{
								$this->salida .= 'checked="true"><br>28';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true"><br>28';
								$s--;
							}
              }else{
							if($s<=0)
							{
								$this->salida .= '><br>28';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>28';
								$s--;
							}
              }
							$dias++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
              if ($_SESSION['dias']['29'] == $dias){
							if($s<=0)
							{
								$this->salida .= 'checked="true"><br>29';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true"><br>29';
								$s--;
							}
              }else{
							if($s<=0)
							{
								$this->salida .= '><br>29';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>29';
								$s--;
							}
              }
							$dias++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
              if ($_SESSION['dias']['30'] == $dias){
							if($s<=0)
							{
								$this->salida .= 'checked="true"><br>30';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true"><br>30';
								$s--;
							}
              }else{
							if($s<=0)
							{
								$this->salida .= '><br>30';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>30';
								$s--;
							}
              }
              $dias++;
							$this->salida .= '</td>';
              $this->salida .= '<td colspan="2" align="center">';
							$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
             if ($_SESSION['dias']['31'] == $dias){
							if($s<=0)
							{
								$this->salida .= 'checked="true"><br>31';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true"><br>31';
								$s--;
							}
              }else{
							if($s<=0)
							{
								$this->salida .= '><br>31';
							}
							else
							{
								$this->salida .= ' disabled="true"><br>31';
								$s--;
							}
              }
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['ini']['hora']==$horaini)
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" checked="true"><br>6';
              else
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" ><br>6';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['ini']['hora']==$horaini)
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" checked="true"><br>7';
              else
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" ><br>7';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['ini']['hora']==$horaini)
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" checked="true"><br>8';
              else
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" ><br>8';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['ini']['hora']==$horaini)
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" checked="true"><br>9';
              else
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" ><br>9';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['ini']['hora']==$horaini)
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" checked="true"><br>10';
              else
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" ><br>10';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['ini']['hora']==$horaini)
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" checked="true"><br>11';
              else
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" ><br>11';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['fin']['hora']==$horafin)
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" checked="true"><br>6';
              else
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" ><br>6';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['fin']['hora']==$horafin)
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" checked="true"><br>7';
              else
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" ><br>7';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['fin']['hora']==$horafin)
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" checked="true"><br>8';
              else
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" ><br>8';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['fin']['hora']==$horafin)
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" checked="true"><br>9';
              else
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" ><br>9';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['fin']['hora']==$horafin)
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" checked="true"><br>10';
              else
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" ><br>10';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['fin']['hora']==$horafin)
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" checked="true"><br>11';
              else
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" ><br>11';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '</tr>';
							$this->salida .= '<tr>';
							$this->salida .= '<td>';
							$this->salida .= '<input type="checkbox" name="mes'.$mes.'" value="'.$mes.'"';
              if ($_SESSION['mes']['5'] == $mes){
							if($i<=0)
							{
								$this->salida .= 'checked="true">';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true">';
								$i--;
							}
              }else{
							if($i<=0)
							{
								$this->salida .= '>';
							}
							else
							{
								$this->salida .= ' disabled="true">';
								$i--;
							}
              }
							$mes++;
							$this->salida .= '</td>';
							$this->salida .= '<td>';
							$this->salida .= 'Mayo';
							$this->salida .= '</td>';
							$this->salida .= '<td colspan="8">';
							$this->salida .= 'Dias de la Semana';
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['ini']['hora']==$horaini)
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" checked="true"><br>12';
              else
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" ><br>12';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['ini']['hora']==$horaini)
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" checked="true"><br>13';
              else
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" ><br>13';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['ini']['hora']==$horaini)
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" checked="true"><br>14';
              else
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" ><br>14';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['ini']['hora']==$horaini)
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" checked="true"><br>15';
              else
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" ><br>15';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['ini']['hora']==$horaini)
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" checked="true"><br>16';
              else
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" ><br>16';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['ini']['hora']==$horaini)
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" checked="true"><br>17';
              else
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" ><br>17';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['fin']['hora']==$horafin)
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" checked="true"><br>12';
              else
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" ><br>12';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['fin']['hora']==$horafin)
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" checked="true"><br>13';
              else
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" ><br>13';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['fin']['hora']==$horafin)
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" checked="true"><br>14';
              else
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" ><br>14';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['fin']['hora']==$horafin)
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" checked="true"><br>15';
              else
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" ><br>15';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['fin']['hora']==$horafin)
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" checked="true"><br>16';
              else
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" ><br>16';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['fin']['hora']==$horafin)
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" checked="true"><br>17';
              else
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" ><br>17';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '</tr>';
							$this->salida .= '<tr>';
							$this->salida .= '<td>';
							$this->salida .= '<input type="checkbox" name="mes'.$mes.'" value="'.$mes.'"';
              if ($_SESSION['mes']['6'] == $mes){
							if($i<=0)
							{
								$this->salida .= 'checked="true">';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true">';
								$i--;
							}
              }else{
							if($i<=0)
							{
								$this->salida .= '>';
							}
							else
							{
								$this->salida .= ' disabled="true">';
								$i--;
							}
              }
							$mes++;
							$this->salida .= '</td>';
							$this->salida .= '<td>';
							$this->salida .= 'Junio';
							$this->salida .= '</td>';
							$this->salida .= '<td>';
              if($_SESSION['semana']['0']==="lun")
							  $this->salida .= '<input type="checkbox" name="semana'.$semana.'" value="lun" checked="true">';
              else
							  $this->salida .= '<input type="checkbox" name="semana'.$semana.'" value="lun">';
							$semana++;
							$this->salida .= '</td>';
							$this->salida .= '<td colspan="7">';
							$this->salida .= 'Lunes';
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['ini']['hora']==$horaini)
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" checked="true"><br>18';
              else
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" ><br>18';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['ini']['hora']==$horaini)
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" checked="true"><br>19';
              else
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" ><br>19';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['ini']['hora']==$horaini)
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" checked="true"><br>20';
              else
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" ><br>20';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['ini']['hora']==$horaini)
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" checked="true"><br>21';
              else
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" ><br>21';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['ini']['hora']==$horaini)
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" checked="true"><br>22';
              else
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" ><br>22';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['ini']['hora']==$horaini)
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" checked="true"><br>23';
              else
							  $this->salida .= '<input type="radio" name="inihora" value="'.$horaini.'" ><br>23';
							$horaini++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['fin']['hora']==$horafin)
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" checked="true"><br>18';
              else
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" ><br>18';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['fin']['hora']==$horafin)
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" checked="true"><br>19';
              else
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" ><br>19';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['fin']['hora']==$horafin)
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" checked="true"><br>20';
              else
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" ><br>20';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['fin']['hora']==$horafin)
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" checked="true"><br>21';
              else
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" ><br>21';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['fin']['hora']==$horafin)
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" checked="true"><br>22';
              else
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" ><br>22';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['fin']['hora']==$horafin)
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" checked="true"><br>23';
              else
							  $this->salida .= '<input type="radio" name="finhora" value="'.$horafin.'" ><br>23';
							$horafin++;
							$this->salida .= '</td>';
							$this->salida .= '</tr>';
							$this->salida .= '<tr>';
							$this->salida .= '<td>';
							$this->salida .= '<input type="checkbox" name="mes'.$mes.'" value="'.$mes.'"';
              if ($_SESSION['mes']['7']==$mes){
							if($i<=0)
							{
								$this->salida .= 'checked="true">';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true">';
								$i--;
							}
              }else{
							if($i<=0)
							{
								$this->salida .= '>';
							}
							else
							{
								$this->salida .= ' disabled="true">';
								$i--;
							}
              }
							$mes++;
              $this->salida .= '</td>';
							$this->salida .= '<td>';
							$this->salida .= 'Julio';
							$this->salida .= '</td>';
							$this->salida .= '<td>';
              if($_SESSION['semana']['1']=="mar")
							  $this->salida .= '<input type="checkbox" name="semana'.$semana.'" value="mar" checked="true">';
              else
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
              if ($_SESSION['mes']['8']==$mes){
							if($i<=0)
							{
								$this->salida .= 'checked="true">';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true">';
								$i--;
							}
              }else{
							if($i<=0)
							{
								$this->salida .= '>';
							}
							else
							{
								$this->salida .= ' disabled="true">';
								$i--;
							}
              }
							$mes++;
              $this->salida .= '</td>';
							$this->salida .= '<td>';
							$this->salida .= 'Agosto';
							$this->salida .= '</td>';
							$this->salida .= '<td>';
              if($_SESSION['semana']['2']=="mié")
							  $this->salida .= '<input type="checkbox" name="semana'.$semana.'" value="mié" checked="true">';
              else
  							$this->salida .= '<input type="checkbox" name="semana'.$semana.'" value="mié">';
							$semana++;
							$this->salida .= '</td>';
							$this->salida .= '<td colspan="7">';
							$this->salida .= 'Miercoles';
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              //inimodi
              if ($_SESSION['ini']['minutos']==='0' and $minutosini==0)
							  $this->salida .= '<input type="radio" name="iniminutos" value="'.$minutosini.'" checked="true"><br>0';
              else
							  $this->salida .= '<input type="radio" name="iniminutos" value="'.$minutosini.'" ><br>0';
							$minutosini=$minutosini+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['ini']['minutos']==$minutosini)
							  $this->salida .= '<input type="radio" name="iniminutos" value="'.$minutosini.'" checked="true"><br>5';
              else
							  $this->salida .= '<input type="radio" name="iniminutos" value="'.$minutosini.'" ><br>5';
							$minutosini=$minutosini+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['ini']['minutos']==$minutosini)
							  $this->salida .= '<input type="radio" name="iniminutos" value="'.$minutosini.'" checked="true"><br>10';
              else
							  $this->salida .= '<input type="radio" name="iniminutos" value="'.$minutosini.'" ><br>10';
							$minutosini=$minutosini+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['ini']['minutos']==$minutosini)
							  $this->salida .= '<input type="radio" name="iniminutos" value="'.$minutosini.'" checked="true"><br>15';
              else
							  $this->salida .= '<input type="radio" name="iniminutos" value="'.$minutosini.'" ><br>15';
							$minutosini=$minutosini+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['ini']['minutos']==$minutosini)
							  $this->salida .= '<input type="radio" name="iniminutos" value="'.$minutosini.'" checked="true"><br>20';
              else
							  $this->salida .= '<input type="radio" name="iniminutos" value="'.$minutosini.'" ><br>20';
							$minutosini=$minutosini+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['ini']['minutos']==$minutosini)
							  $this->salida .= '<input type="radio" name="iniminutos" value="'.$minutosini.'" checked="true"><br>25';
              else
							  $this->salida .= '<input type="radio" name="iniminutos" value="'.$minutosini.'" ><br>25';
							$minutosini=$minutosini+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['fin']['minutos']==='0' and $minutosfin==0)
							  $this->salida .= '<input type="radio" name="finminutos" value="'.$minutosfin.'" checked="true"><br>0';
              else
							  $this->salida .= '<input type="radio" name="finminutos" value="'.$minutosfin.'" ><br>0';
							$minutosfin=$minutosfin+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['fin']['minutos']==$minutosfin)
							  $this->salida .= '<input type="radio" name="finminutos" value="'.$minutosfin.'" checked="true"><br>5';
              else
							  $this->salida .= '<input type="radio" name="finminutos" value="'.$minutosfin.'" ><br>5';
							$minutosfin=$minutosfin+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['fin']['minutos']==$minutosfin)
							  $this->salida .= '<input type="radio" name="finminutos" value="'.$minutosfin.'" checked="true"><br>10';
              else
							  $this->salida .= '<input type="radio" name="finminutos" value="'.$minutosfin.'" ><br>10';
							$minutosfin=$minutosfin+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['fin']['minutos']==$minutosfin)
							  $this->salida .= '<input type="radio" name="finminutos" value="'.$minutosfin.'" checked="true"><br>15';
              else
							  $this->salida .= '<input type="radio" name="finminutos" value="'.$minutosfin.'" ><br>15';
							$minutosfin=$minutosfin+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['fin']['minutos']==$minutosfin)
							  $this->salida .= '<input type="radio" name="finminutos" value="'.$minutosfin.'" checked="true"><br>20';
              else
							  $this->salida .= '<input type="radio" name="finminutos" value="'.$minutosfin.'" ><br>20';
							$minutosfin=$minutosfin+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['fin']['minutos']==$minutosfin)
							  $this->salida .= '<input type="radio" name="finminutos" value="'.$minutosfin.'" checked="true"><br>25';
              else
							  $this->salida .= '<input type="radio" name="finminutos" value="'.$minutosfin.'" ><br>25';
							$minutosfin=$minutosfin+5;
							$this->salida .= '</td>';
							$this->salida .= '</tr>';
							$this->salida .= '<tr>';
							$this->salida .= '<td>';
							$this->salida .= '<input type="checkbox" name="mes'.$mes.'" value="'.$mes.'"';
              if ($_SESSION['mes']['9']==$mes){
							if($i<=0)
							{
								$this->salida .= 'checked="true">';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true">';
								$i--;
							}
              }else{
							if($i<=0)
							{
								$this->salida .= '>';
							}
							else
							{
								$this->salida .= ' disabled="true">';
								$i--;
							}
              }
              $mes++;
							$this->salida .= '</td>';
							$this->salida .= '<td>';
							$this->salida .= 'Septiembre';
							$this->salida .= '</td>';
							$this->salida .= '<td>';
              if($_SESSION['semana']['3']=="jue")
							  $this->salida .= '<input type="checkbox" name="semana'.$semana.'" value="jue" checked="true">';
              else
							  $this->salida .= '<input type="checkbox" name="semana'.$semana.'" value="jue">';
							$semana++;
							$this->salida .= '</td>';
							$this->salida .= '<td colspan="7">';
							$this->salida .= 'Jueves';
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['ini']['minutos']==$minutosini)
							  $this->salida .= '<input type="radio" name="iniminutos" value="'.$minutosini.'" checked="true"><br>30';
              else
							  $this->salida .= '<input type="radio" name="iniminutos" value="'.$minutosini.'" ><br>30';
							$minutosini=$minutosini+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['ini']['minutos']==$minutosini)
							  $this->salida .= '<input type="radio" name="iniminutos" value="'.$minutosini.'" checked="true"><br>35';
              else
							  $this->salida .= '<input type="radio" name="iniminutos" value="'.$minutosini.'" ><br>35';
							$minutosini=$minutosini+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['ini']['minutos']==$minutosini)
							  $this->salida .= '<input type="radio" name="iniminutos" value="'.$minutosini.'" checked="true"><br>40';
              else
							  $this->salida .= '<input type="radio" name="iniminutos" value="'.$minutosini.'" ><br>40';
							$minutosini=$minutosini+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['ini']['minutos']==$minutosini)
							  $this->salida .= '<input type="radio" name="iniminutos" value="'.$minutosini.'" checked="true"><br>45';
              else
							  $this->salida .= '<input type="radio" name="iniminutos" value="'.$minutosini.'" ><br>45';
							$minutosini=$minutosini+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['ini']['minutos']==$minutosini)
							  $this->salida .= '<input type="radio" name="iniminutos" value="'.$minutosini.'" checked="true"><br>50';
              else
							  $this->salida .= '<input type="radio" name="iniminutos" value="'.$minutosini.'" ><br>50';
							$minutosini=$minutosini+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['ini']['minutos']==$minutosini)
							  $this->salida .= '<input type="radio" name="iniminutos" value="'.$minutosini.'" checked="true"><br>55';
              else
							  $this->salida .= '<input type="radio" name="iniminutos" value="'.$minutosini.'" ><br>55';
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['fin']['minutos']==$minutosfin)
							  $this->salida .= '<input type="radio" name="finminutos" value="'.$minutosfin.'" checked="true"><br>30';
              else
							  $this->salida .= '<input type="radio" name="finminutos" value="'.$minutosfin.'" ><br>30';
							$minutosfin=$minutosfin+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['fin']['minutos']==$minutosfin)
							  $this->salida .= '<input type="radio" name="finminutos" value="'.$minutosfin.'" checked="true"><br>35';
              else
							  $this->salida .= '<input type="radio" name="finminutos" value="'.$minutosfin.'" ><br>35';
							$minutosfin=$minutosfin+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['fin']['minutos']==$minutosfin)
							  $this->salida .= '<input type="radio" name="finminutos" value="'.$minutosfin.'" checked="true"><br>40';
              else
							  $this->salida .= '<input type="radio" name="finminutos" value="'.$minutosfin.'" ><br>40';
							$minutosfin=$minutosfin+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['fin']['minutos']==$minutosfin)
							  $this->salida .= '<input type="radio" name="finminutos" value="'.$minutosfin.'" checked="true"><br>45';
              else
							  $this->salida .= '<input type="radio" name="finminutos" value="'.$minutosfin.'" ><br>45';
							$minutosfin=$minutosfin+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['fin']['minutos']==$minutosfin)
							  $this->salida .= '<input type="radio" name="finminutos" value="'.$minutosfin.'" checked="true"><br>50';
              else
							  $this->salida .= '<input type="radio" name="finminutos" value="'.$minutosfin.'" ><br>50';
							$minutosfin=$minutosfin+5;
							$this->salida .= '</td>';
							$this->salida .= '<td align="center">';
              if ($_SESSION['fin']['minutos']==$minutosfin)
							  $this->salida .= '<input type="radio" name="finminutos" value="'.$minutosfin.'" checked="true"><br>55';
              else
							  $this->salida .= '<input type="radio" name="finminutos" value="'.$minutosfin.'" ><br>55';
							$this->salida .= '</td>';
							$this->salida .= '</tr>';
							$this->salida .= '<tr>';
							$this->salida .= '<td>';
							$this->salida .= '<input type="checkbox" name="mes'.$mes.'" value="'.$mes.'"';
              if ($_SESSION['mes']['10']==$mes){
							if($i<=0)
							{
								$this->salida .= 'checked="true">';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true">';
								$i--;
							}
              }else{
							if($i<=0)
							{
								$this->salida .= '>';
							}
							else
							{
								$this->salida .= ' disabled="true">';
								$i--;
							}
              }
							$mes++;
							$this->salida .= '</td>';
							$this->salida .= '<td>';
							$this->salida .= 'Octubre';
							$this->salida .= '</td>';
							$this->salida .= '<td>';
              if($_SESSION['semana']['4']=="vie")
							  $this->salida .= '<input type="checkbox" name="semana'.$semana.'" value="vie" checked="true">';
              else
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
              if ($_SESSION['mes']['11']==$mes){
							if($i<=0)
							{
								$this->salida .= 'checked="true">';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true">';
								$i--;
							}
              }else{
							if($i<=0)
							{
								$this->salida .= '>';
							}
							else
							{
								$this->salida .= ' disabled="true">';
								$i--;
							}
              }
							$mes++;
							$this->salida .= '</td>';
							$this->salida .= '<td>';
							$this->salida .= 'Noviembre';
							$this->salida .= '</td>';
							$this->salida .= '<td>';
              if($_SESSION['semana']['5']=="sáb")
							  $this->salida .= '<input type="checkbox" name="semana'.$semana.'" value="sáb" checked="true">';
              else
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
              if ($_SESSION['mes']['12']==$mes){
							if($i<=0)
							{
								$this->salida .= 'checked="true">';
							}
							else
							{
								$this->salida .= ' disabled="true" checked="true">';
								$i--;
							}
              }else{
							if($i<=0)
							{
								$this->salida .= '>';
							}
							else
							{
								$this->salida .= ' disabled="true">';
								$i--;
							}
              }
							$this->salida .= '</td>';
							$this->salida .= '<td>';
							$this->salida .= 'Diciembre';
							$this->salida .= '</td>';
							$this->salida .= '<td>';
              if($_SESSION['semana']['6']=="dom")
							  $this->salida .= '<input type="checkbox" name="semana'.$semana.'" value="dom" checked="true">';
              else
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
							$this->salida .= '<input type="submit" name="Enviar" value="ENVIAR" class="input-submit">';
							$this->salida .= '</form>';
							$this->salida .= '</td>';
							$this->salida .= '<td align="left">';
							$accion=ModuloGetURL('app', 'CreacionAgenda', 'user', 'CreacionAgenda');
							$this->salida .= '<form name="volver" action="'.$accion.'" method="post">';
							$this->salida .= '<input type="submit" name="volver" value="VOLVER" class="input-submit">';
							$this->salida .= '</td>';
							$this->salida .= '</form>';
							$this->salida .= '</tr>';
							$this->salida .= '</table>';
							$this->salida .= '<br>';
							$this->salida .= '<br>';
							$this->salida .= ThemeCerrarTabla();
              //MODI INI
              $this->clear();
              //MODI FIN
						}
						else
						{
              //MODI INI
              $this->Asignar();
              //MODI FIN
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
									$this->salida  = ThemeAbrirTabla('CREACIÓN AGENDA MÉDICA');
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
                          //INICIO MODI
                          $this->Asignar();
                          //FIN MODI
													$this->salida.='<table align="center" class="modulo_table_list">';
													$this->salida.='<tr>';
													$this->salida.='<td align="center">';
													$this->salida.='No se eligio ninguna fecha para realizar agenda.';
													$this->salida.='</td>';
													$this->salida.='</tr>';
													$this->salida.='<tr>';
													$this->salida.='<td align="center">';
													$accion=ModuloGetURL('app','CreacionAgenda','user','CreacionAgenda');
													$this->salida.='<form method="post" action="">';
													$this->salida.='<input type="submit" name="volver" value="VOLVER" class="input-submit">';
													$this->salida.='</form>';
													$this->salida.='</td>';
													$this->salida.='</tr>';
													$this->salida.='</table>';
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
														$horainic=($_REQUEST['inihora']*60)+$_REQUEST['iniminutos'];
														$horafina=($_REQUEST['finhora']*60)+$_REQUEST['finminutos'];
														if($horainic<$horafina AND ($horainic+$_REQUEST['interval'])<=$horafina)
														/*(($_REQUEST['iniminutos']<=$_REQUEST['finminutos'] AND $_REQUEST['inihora']<=$_REQUEST['finhora'])
														OR ($_REQUEST['inihora']==$_REQUEST['finhora'] AND $_REQUEST['iniminutos']<$_REQUEST['finminutos'])
														OR ($_REQUEST['inihora']<$_REQUEST['finhora'] AND $_REQUEST['iniminutos']>$_REQUEST['finminutos']))*/
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
																	while(date("m-d H:i",mktime($_REQUEST['inihora'],$s,0,$a[1],$a[2],$a[0]))<date("m-d H:i",mktime($_REQUEST['finhora'],$_REQUEST['finminutos'],0,$a[1],$a[2],$a[0])))
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
																		if(date("G",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]))>=0 and date("G",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]))<6 and $hora[0]==0)
																		{
																			$citasmes[$l]=$fechastotal[$i];
																			$l++;
																			$hora[0]=1;
																		}
																		if(date("G",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]))>=6 and date("G",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]))<12 and $hora[1]==0)
																		{
																			$citasmes[$l]=$fechastotal[$i];
																			$l++;
																			$hora[1]=1;
																		}
																		if(date("G",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]))>=12 and date("G",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]))<18 and $hora[2]==0)
																		{
																			$citasmes[$l]=$fechastotal[$i];
																			$l++;
																			$hora[2]=1;
																		}
																		if(date("G",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]))>=18 and date("G",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]))<23 and $hora[3]==0)
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
															/*$this->salida.='<script>';
															$this->salida.='function vol(){'."\n";
															$this->salida.='window.history.back();'."\n";
															$this->salida.='}'."\n";
															$this->salida.='</script>';*/
                              //MODI INICIO
                              $this->Asignar();
                              //MODI FIN
															$this->salida.='<table align="center" class="modulo_table_list">';
															$this->salida.='<tr>';
															$this->salida.='<td align="center">';
															$this->salida.='La hora inicial es igual a la final y los minutos finales son mayores a los iniciales.';
															$this->salida.='</td>';
															$this->salida.='</tr>';
															$this->salida.='<tr>';
															$this->salida.='<td align="center">';
															$accion=ModuloGetURL('app','CreacionAgenda','user','CreacionAgenda');
															$this->salida.='<form method="post" action="">';//onclick="vol()"
															$this->salida.='<input type="submit" name="volver" value="VOLVER" class="input-submit">';
															$this->salida.='</form>';
															$this->salida.='</td>';
															$this->salida.='</tr>';
															$this->salida.='</table>';
														}
													}
													else
													{
// 														$this->salida.='<script>';
// 														$this->salida.='function vol(){';
// 														$this->salida.='window.history.go(-2);';
// 														$this->salida.='}';
// 														$this->salida.='</script>';
                            //MODI INICIO
                            $this->Asignar();
                            //MODI FIN
														$this->salida.='<table align="center" class="modulo_table_list">';
														$this->salida.='<tr>';
														$this->salida.='<td align="center">';
														$this->salida.='La hora inicial es menor a la final.';
														$this->salida.='</td>';
														$this->salida.='</tr>';
														$this->salida.='<tr>';
														$this->salida.='<td align="center">';
														$accion=ModuloGetURL('app','CreacionAgenda','user','CreacionAgenda');
														$this->salida.='<form method="post" action="">';//onclick="vol()"
														$this->salida.='<input type="submit" name="volver" value="VOLVER" class="input-submit">';
														$this->salida.='</form>';
														$this->salida.='</td>';
														$this->salida.='</tr>';
														$this->salida.='</table>';
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
											/*$this->salida.='<script>';
											$this->salida.='function vol(){';
											$this->salida.='window.history.go(-1);';
											$this->salida.='}';
											$this->salida.='</script>';*/
                      //**************INICIO MODI*****************
                      $this->Asignar();
                      //***************FIN MODI***************
											$this->salida.='<table align="center" class="modulo_table_list">';
											$this->salida.='<tr>';
											$this->salida.='<td align="center">';
											$this->salida.='No se coloco ningún intervalo de turno o una hora inicial o una hora final o un minuto inicial o un minuto final o una cantidad de pacientes.';
											$this->salida.='</td>';
											$this->salida.='</tr>';
											$this->salida.='<tr>';
											$this->salida.='<td align="center">';
											$accion=ModuloGetURL('app','CreacionAgenda','user','CreacionAgenda');
											$this->salida.='<form method="post" action="">';//onclick="vol()"
											$this->salida.='<input type="submit" name="volver" value="VOLVER" class="input-submit">';
											$this->salida.='</form>';
											$this->salida.='</td>';
											$this->salida.='</tr>';
											$this->salida.='</table>';
										}
									}
									else
									{
                  //*********MODI INI*******
         //          if(!empty($_REQUEST['interval']) and $_REQUEST['inihora']!='' and $_REQUEST['finhora']!='' and $_REQUEST['iniminutos']!='' and $_REQUEST['finminutos']!='' and $_REQUEST['pacientes']!='' and(
         //          $_REQUEST['semana0']!='' || $_REQUEST['semana1']!='' || $_REQUEST['semana2']!='' || $_REQUEST['semana3']!='' || $_REQUEST['semana4']!='' || $_REQUEST['semana5']!='' || $_REQUEST['semana6']!='') and(
         //          $_REQUEST['mes1']!='' || $_REQUEST['mes2']!='' || $_REQUEST['mes3']!='' || $_REQUEST['mes4']!='' || $_REQUEST['mes5']!='' || $_REQUEST['mes6']!='' || $_REQUEST['mes7']!=''
         //          || $_REQUEST['mes8']!='' || $_REQUEST['mes9']!='' || $_REQUEST['mes10']!='' || $_REQUEST['mes11']!='' || $_REQUEST['mes12']!='') and ($_REQUEST['inihora']<=$_REQUEST['finhora']) and $horainic<$horafina
         //          AND (($horainic+$_REQUEST['interval'])<=$horafina))
                   if(!empty($_REQUEST['interval']) and $_REQUEST['inihora']!='' and $_REQUEST['finhora']!='' and $_REQUEST['iniminutos']!='' and $_REQUEST['finminutos']!='' and $_REQUEST['pacientes']!='' and(
                   $_REQUEST['semana0']!='' || $_REQUEST['semana1']!='' || $_REQUEST['semana2']!='' || $_REQUEST['semana3']!='' || $_REQUEST['semana4']!='' || $_REQUEST['semana5']!='' || $_REQUEST['semana6']!='') and(
                   $_REQUEST['mes1']!='' || $_REQUEST['mes2']!='' || $_REQUEST['mes3']!='' || $_REQUEST['mes4']!='' || $_REQUEST['mes5']!='' || $_REQUEST['mes6']!='' || $_REQUEST['mes7']!=''
                   || $_REQUEST['mes8']!='' || $_REQUEST['mes9']!='' || $_REQUEST['mes10']!='' || $_REQUEST['mes11']!='' || $_REQUEST['mes12']!=''))
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
												$accion=ModuloGetURL('app','CreacionAgenda','user','CreacionAgenda',$vec1);
												$this->salida.='Creación del mes de: <a href="'.$accion.'">'.strftime("%B",mktime(0,0,0,$v1,1,0)).'</a><br>';
											}
											if($v=='mes2')
											{
												$vec1=$vec;
												unset($vec1['2mes']);
												$vec1[$v]=$v1;
												$accion=ModuloGetURL('app','CreacionAgenda','user','CreacionAgenda',$vec1);
												$this->salida.='Creación del mes de: <a href="'.$accion.'">'.strftime("%B",mktime(0,0,0,$v1,1,0)).'</a><br>';
											}
											if($v=='mes3')
											{
												$vec1=$vec;
												unset($vec1['3mes']);
												$vec1[$v]=$v1;
												$accion=ModuloGetURL('app','CreacionAgenda','user','CreacionAgenda',$vec1);
												$this->salida.='Creación del mes de: <a href="'.$accion.'">'.strftime("%B",mktime(0,0,0,$v1,1,0)).'</a><br>';
											}
											if($v=='mes4')
											{
												$vec1=$vec;
												unset($vec1['4mes']);
												$vec1[$v]=$v1;
												$accion=ModuloGetURL('app','CreacionAgenda','user','CreacionAgenda',$vec1);
												$this->salida.='Creación del mes de: <a href="'.$accion.'">'.strftime("%B",mktime(0,0,0,$v1,1,0)).'</a><br>';
											}
											if($v=='mes5')
											{
												$vec1=$vec;
												unset($vec1['5mes']);
												$vec1[$v]=$v1;
												$accion=ModuloGetURL('app','CreacionAgenda','user','CreacionAgenda',$vec1);
												$this->salida.='Creación del mes de: <a href="'.$accion.'">'.strftime("%B",mktime(0,0,0,$v1,1,0)).'</a><br>';
											}
											if($v=='mes6')
											{
												$vec1=$vec;
												unset($vec1['6mes']);
												$vec1[$v]=$v1;
												$accion=ModuloGetURL('app','CreacionAgenda','user','CreacionAgenda',$vec1);
												$this->salida.='Creación del mes de: <a href="'.$accion.'">'.strftime("%B",mktime(0,0,0,$v1,1,0)).'</a><br>';
											}
											if($v=='mes7')
											{
												$vec1=$vec;
												unset($vec1['7mes']);
												$vec1[$v]=$v1;
												$accion=ModuloGetURL('app','CreacionAgenda','user','CreacionAgenda',$vec1);
												$this->salida.='Creación del mes de: <a href="'.$accion.'">'.strftime("%B",mktime(0,0,0,$v1,1,0)).'</a><br>';
											}
											if($v=='mes8')
											{
												$vec1=$vec;
												unset($vec1['8mes']);
												$vec1[$v]=$v1;
												$accion=ModuloGetURL('app','CreacionAgenda','user','CreacionAgenda',$vec1);
												$this->salida.='Creación del mes de: <a href="'.$accion.'">'.strftime("%B",mktime(0,0,0,$v1,1,0)).'</a><br>';
											}
											if($v=='mes9')
											{
												$vec1=$vec;
												unset($vec1['9mes']);
												$vec1[$v]=$v1;
												$accion=ModuloGetURL('app','CreacionAgenda','user','CreacionAgenda',$vec1);
												$this->salida.='Creación del mes de: <a href="'.$accion.'">'.strftime("%B",mktime(0,0,0,$v1,1,0)).'</a><br>';
											}
											if($v=='mes10')
											{
												$vec1=$vec;
												unset($vec1['10mes']);
												$vec1[$v]=$v1;
												$accion=ModuloGetURL('app','CreacionAgenda','user','CreacionAgenda',$vec1);
												$this->salida.='Creación del mes de: <a href="'.$accion.'">'.strftime("%B",mktime(0,0,0,$v1,1,0)).'</a><br>';
											}
											if($v=='mes11')
											{
												$vec1=$vec;
												unset($vec1['11mes']);
												$vec1[$v]=$v1;
												$accion=ModuloGetURL('app','CreacionAgenda','user','CreacionAgenda',$vec1);
												$this->salida.='Creación del mes de: <a href="'.$accion.'">'.strftime("%B",mktime(0,0,0,$v1,1,0)).'</a><br>';
											}
											if($v=='mes12')
											{
												$vec1=$vec;
												unset($vec1['12mes']);
												$vec1[$v]=$v1;
												$accion=ModuloGetURL('app','CreacionAgenda','user','CreacionAgenda',$vec1);
												$this->salida.='Creación del mes de: <a href="'.$accion.'">'.strftime("%B",mktime(0,0,0,$v1,1,0)).'</a><br>';
											}
										}
                    }else{
                      $this->Asignar();
											$this->salida.='<table align="center" class="modulo_table_list">';
											$this->salida.='<tr>';
											$this->salida.='<td align="center">';
											$this->salida.='No se coloco ningún intervalo de turno o una hora inicial o una hora final o un minuto inicial o un minuto final o una cantidad de pacientes.';
											$this->salida.='</td>';
											$this->salida.='</tr>';
											$this->salida.='<tr>';
											$this->salida.='<td align="center">';
											$accion=ModuloGetURL('app','CreacionAgenda','user','CreacionAgenda');
											$this->salida.='<form method="post" action="">';//onclick="vol()"
											$this->salida.='<input type="submit" name="volver" value="VOLVER" class="input-submit">';
											$this->salida.='</form>';
											$this->salida.='</td>';
											$this->salida.='</tr>';
											$this->salida.='</table>';
                    }
                   //********FIN MODI**********
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
									$accion=ModuloGetURL('app','CreacionAgenda','user','',$vec);
									$this->salida  = ThemeAbrirTabla('CREACIÓN AGENDA MÉDICA');
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
									$this->salida .= '<input type="submit" name="volver" value="VOLVER" class="input-submit">';
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





/**
* Esta funcion es la que muestra los diferentes dias del mes con la agenda que se desea crear al usuario
*
* @access public
* @return boolean Para identificar que se realizo.
*/



	function AgendaHtml()
	{
		$this->salida.="\n".'<script>'."\n";
		$this->salida.='function year1(t)'."\n";
		$this->salida.='{'."\n";
		$this->salida.='window.location.href="Contenido.php?modulo=CreacionAgenda&year="+t.elements[1].value+"&meses="+t.elements[2].value+"';
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
    $this->Asignar();
		$accion=ModuloGetURL('app', 'CreacionAgenda', 'user', 'CreacionAgenda', $vec1);
		$this->salida .= '<form name="atras" action="'.$accion.'" method="post">';
		$this->salida .= '<br>';
		$this->salida .= '<table align="center">';
		$this->salida .= '<tr>';
		$this->salida .= '<td>';
		$this->salida .= '<input type="submit" name="Volver" value="VOLVER" class="input-submit">';
		$this->salida .= '</td>';
		$this->salida .= '<td>';
		$this->salida .= '</form>';
		$accion=ModuloGetURL('app', 'CreacionAgenda', 'user', 'GuardarDatos', $vec1);
		$this->salida .= '<form name="atras" action="'.$accion.'" method="post">';
		$this->salida .= '<input type="submit" name="guardar" value="GUARDAR" class="input-submit">';
		$this->salida .= '</form>';
		$this->salida .= '</td>';
		$this->salida .= '</tr>';
		$this->salida .= '</table>';
		$this->salida .= '<br>';
	}





/**
	* Muestra los aÃ±os en los que se puede buscar la agenda medica
	* @access private
	* @param boolean si ya esta seleccionado
	* @param string aÃ±o seÃ±alado
	*/

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




/**
	* Muestra los meses para realizar la consulta de la agenda
	* @access private
	* @param boolean si ya esta seleccionado
	* @param string aÃ±o seÃ±alado
	* @param string mes por defecto
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

/**
	* Muestra los permisos que tiene el usuario para realizar cambio y borrado de agenda
	* @access private
	* @return boolean Para identificar que se realizo.
	*/




	function CambiarBorrarAgenda()
	{
		unset($_SESSION['BorrarAgenda']);
		$url[0]='app';
		$url[1]='CreacionAgenda';
		$url[2]='user';
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




/**
* Muestra los profesionales que tiene agenda activa para el dia de hoy y hacia adelante
*
* @access private
* @return boolean Para identificar que se realizo.
*/



	function ListarProfesionales()
	{
		unset($_SESSION['BorrarAgenda']['DatosProf']);
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
				$this->salida .= '<a href="'.ModuloGetURL('app','CreacionAgenda','user','ListadoAgendaMesTurnos',array('tercero'=>$profesionales[1][$i],'tipoid'=>$profesionales[0][$i],'nombrep'=>$profesionales[2][$i])).'">Ir</a>';
				$this->salida .= '</td>';
				$this->salida .= '</tr>';
				$i++;
			}
			$this->salida .= '</table>';
			$this->salida .= '<br>';
			$this->salida .= '<table align="center" width="70%" border="0">';
			$this->salida .= '<tr>';
			$this->salida .= '<td align="center">';
			$accion=ModuloGetURL('app','CreacionAgenda','user','CambiarBorrarAgenda');
			$this->salida .= '<form name="volver" method="post" action="'.$accion.'">';
			$this->salida .= '<input type="submit" name="volver" value="VOLVER" class="input-submit">';
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
			$this->salida .= 'NO EXISTEN DATOS DE PROFESIONALES';
			$this->salida .= '</td>';
			$this->salida .= '</tr>';
			$this->salida .= '</table>';
			$this->salida .= '<br>';
			$this->salida .= '<table align="center" width="70%" border="0">';
			$this->salida .= '<tr>';
			$this->salida .= '<td align="center">';
			$accion=ModuloGetURL('app','CreacionAgenda','user','CambiarBorrarAgenda');
			$this->salida .= '<form name="volver" method="post" action="'.$accion.'">';
			$this->salida .= '<input type="submit" name="volver" value="VOLVER" class="input-submit">';
			$this->salida .= '</form>';
			$this->salida .= '</td>';
			$this->salida .= '</tr>';
			$this->salida .= '</table>';
			$this->salida .= '<br>';
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
	}



/**
* Muestra el listado de las agendas que tenga un profesional.
*
* @access private
* @return boolean Para identificar que se realizo.
*/


	function ListadoAgendaMesTurnos()
	{
		if(empty($_SESSION['BorrarAgenda']['DatosProf']['tercero']))
		{
			$_SESSION['BorrarAgenda']['DatosProf']['nombrep']=$_REQUEST['nombrep'];
			$_SESSION['BorrarAgenda']['DatosProf']['tercero']=$_REQUEST['tercero'];
			$_SESSION['BorrarAgenda']['DatosProf']['tipoid']=$_REQUEST['tipoid'];
		}
		unset($_SESSION['BorrarAgenda']['datos']);
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
			$this->salida .= $_SESSION['BorrarAgenda']['DatosProf']['nombrep'];
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
				$this->salida .= '<label class="label_error">NO SE PUEDE BORRAR LA AGENDA POR TENER CITAS ASIGNADOS</label>';
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
			$accion=ModuloGetURL('app','CreacionAgenda','user','BorrarAgenda',$vec);
			$this->salida.='<form name="siguiente" method="post" action="'.$accion.'">';
			$this->salida .= '<table width="70%" align="center" class="modulo_table_list">';
			$this->salida .= '<tr align="center" class="modulo_table_title">';
			$this->salida .= '<td align="center">';
			$this->salida .= "Fecha";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "Selección";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center" width="15%">';
			$this->salida .= "Cambio De Turnos";
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
					$accion=ModuloGetURL('app','CreacionAgenda','user','ListadoDiaAgenda',$vec);
					$this->salida .='<a href="'.$accion.'">'.$turnos[0][$i].'</a>';
					$this->salida .= "</td>";
					$this->salida .= '<td align="center">';
					$this->salida .= '<input type="checkbox" name="seleccion'.$i.'" value="'.$turnos[1][$i].'" class="input-submit">';
					$this->salida .= "</td>";
					$this->salida .= '<td align="center">';
					$accion=ModuloGetURL('app','CreacionAgenda','user','CambiarAgendaCompleta',array('turno'=>$turnos[1][$i]));
					$this->salida .= '<a href="'.$accion.'">Cambio Turno</a>';
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
					$accion=ModuloGetURL('app','CreacionAgenda','user','ListadoDiaAgenda',$vec);
					$this->salida .='<a href="'.$accion.'">'.$turnos[0][$i].'</a>';
					$this->salida .= "</td>";
					$this->salida .= '<td align="center">';
					$this->salida .= '<input type="checkbox" name="seleccion'.$i.'" value="'.$vec['TurnoAgenda'].'" class="input-submit">';
					$this->salida .= "</td>";
					$this->salida .= '<td align="center">';
					$accion=ModuloGetURL('app','CreacionAgenda','user','CambiarAgendaCompleta',array('turno'=>$vec['TurnoAgenda']));
					$this->salida .= '<a href="'.$accion.'">Cambio Turno</a>';
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
			$accion=ModuloGetURL('app','CreacionAgenda','user','CambiarAgenda',$vec);
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
			if(!is_array($vec))
			{
				$vec=array();
			}
			$accion=ModuloGetURL('app','CreacionAgenda','user','ListarProfesionales',$vec);
			$this->salida .= '<form name="volver" method="post" action="'.$accion.'">';
			$this->salida .= '<input type="submit" name="volver" value="VOLVER" class="input-submit">';
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
			$this->salida .= $_SESSION['BorrarAgenda']['DatosProf']['nombrep'];
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= "</table>";
			$this->salida .= '<br>';
			$this->salida .= '<table align="center" width="70%" border="0">';
			$this->salida .= '<tr>';
			$this->salida .= '<td align="center" class="label_error">';
			$this->salida .= 'NO EXISTEN DATOS PARA ESTE PROFESIONAL.';
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
			$accion=ModuloGetURL('app','CreacionAgenda','user','ListarProfesionales',$vec);
			$this->salida .= '<form name="volver" method="post" action="'.$accion.'">';
			$this->salida .= '<input type="submit" name="volver" value="VOLVER" class="input-submit">';
			$this->salida .= '</form>';
			$this->salida .= '</td>';
			$this->salida .= '</tr>';
			$this->salida .= '</table>';
			$this->salida .= '<br>';
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
	}




/**
* Muestra las agendas con la posibilidad de realizar el cambio de la misma
*
* @access private
* @return boolean Para identificar que se realizo.
*/

	function CambiarAgendaCompleta()
	{
		SessionDelVar('CITASMES');
		unset($_SESSION['BorrarAgenda']['DatosAgenda']);
		$a=explode(',',$_REQUEST['turno']);
		$profesionales=$this->BuscarProfesionales($this->BusquedaEspecialidad($a[0]));
		$this->salida=ThemeAbrirTabla('CAMBIAR AGENDA BUSQUEDA DE DIA');
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
		$this->salida .= $_SESSION['BorrarAgenda']['DatosProf']['nombrep'];
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .= '<br>';
		if(!empty($_REQUEST['DiaEspe']))
		{
			if($_REQUEST['DiaEspe']>=date("Y-m-d"))
			{
				$this->salida .= '<table width="70%" align="center">';
				$this->salida .= '<tr align="center">';
				$this->salida .= '<td align="center">';
				$this->salida .= '<label class="label_error">FECHA SELECCIONADA: '.$_REQUEST['DiaEspe'].'</label>';
				$salida ="<input type=\"hidden\" value=\"".$_REQUEST['DiaEspe']."\" name=\"DiaEspe\">";
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
				$this->salida .= "</table>";
			}
			else
			{
				$this->salida .= '<table width="70%" align="center">';
				$this->salida .= '<tr align="center">';
				$this->salida .= '<td align="center">';
				$this->salida .= '<label class="label_error">NO SE PUEDE CREAR AGENDA PARA EL DIA '.$_REQUEST['DiaEspe'].'</label>';
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
				$this->salida .= "</table>";
			}
		}


		$this->salida .= "<table border=\"0\" width=\"50%\" align=\"center\">";
//aqui inserte lo de lorena
		$this->salida .= "<tr><td>";
		$this->salida.="\n".'<script>'."\n";
		$this->salida.='function year1(t)'."\n";
		$this->salida.='{'."\n";
		$this->salida.='document.cosa.action="Contenido.php?year="+t.elements[0].value+"&meses="+t.elements[1].value+"';
		foreach($_REQUEST as $v=>$v1)
		{
			if($v=='Cambiar')
			{
				unset($_REQUEST[$v]);
			}
			if($v!='year' and $v!='meses' and $v!='DiaEspe' and $v!='Cambiar')
			{
				if (is_array($v1))
				{
					foreach($v1 as $k2=>$v2)
					{
						if (is_array($v2))
						{
							foreach($v2 as $k3=>$v3)
							{
								if (is_array($v3))
								{
									foreach($v3 as $k4=>$v4)
									{
										$this->salida .= "&$v" . "[$k2][$k3][$k4]=$v4";
									}
								}
								else
								{
									$this->salida .= "&$v" . "[$k2][$k3]=$v3";
								}
							}
						}
						else
						{
							$this->salida .= "&$v" . "[$k2]=$v2";
						}
					}
				}
				else
				{
					$this->salida .= "&$v=$v1";
				}
			}
		}
		$this->salida.='";'."\n";
		$this->salida.='document.cosa.submit();';
		$this->salida.='}'."\n";
		$this->salida.='</script>';

		$this->salida .='<form name="cosa" method="post">';
		$this->salida .="<table border=\"0\" width=\"80%\" align=\"center\">";
		$this->salida .='<tr align="center">';
		$this->salida .="<td class=\"label\">AÑO</td><td><select name=\"year\" onchange=\"year1(this.form)\" class=\"select\">";
		if(empty($_REQUEST['year']))
		{
			$year=date("Y");
			$this->AnosAgenda(True,$year);
		}
		else
		{
			$year=$_REQUEST['year'];
			$this->AnosAgenda(true,$year);
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
			$mes=$_REQUEST['meses'];
			$this->MesesAgenda(True,$year,$mes);
		}
		$this->salida .= "</select>";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .='</form>';
		$this->ReturnMetodoExterno('app', 'Agenda', 'user', 'CalendarioEstandard');
		$this->salida .= "   </td></tr>";

		$this->salida .= "<tr class=\"modulo_table_list_title\">";
		$this->salida .= "<td>";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		/**************************************/
		$this->salida .= "</table>";


		$accion=ModuloGetURL('app','CreacionAgenda','user','CambiarAgendaTurnoCompleto',array('turno'=>$_REQUEST['turno'],'year'=>$year,'meses'=>$meses));
		$this->salida .= '<form name="cambiar" method="post" action="'.$accion.'">';
		$this->salida .=$salida;
		$justificacion=$this->BusquedaTipoJustificacion();
		$this->salida .="<table border=\"0\" width=\"50%\" align=\"center\">";
		$this->salida .='<tr align="center">';
		$this->salida .="<td class=\"".$this->SetStyle("justificacion")."\">JUSTIFICACIÓN:</td>";
		$this->salida .="<td><select name=\"justificacion\" class=\"select\">";
		$this->salida .="<option value=\"-1\">--SELECCIONE--</option>";
		foreach($justificacion as $k=>$v)
		{
			if($_REQUEST['justificacion']==$k)
			{
				$this->salida .="<option value=\"$k\" selected>".$v['descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"$k\">".$v['descripcion']."</option>";
			}
		}
		$this->salida .="</select>";
		$this->salida .="</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .="<table border=\"0\" width=\"50%\" align=\"center\">";
		$this->salida .='<tr align="center">';
		$this->salida .="<td class=\"label\">PROFESIONALES:</td><td><select name=\"Profesional\" class=\"select\">";
		if(empty($_REQUEST['Profesional']))
		{
			$_REQUEST['Profesional']=$_SESSION['BorrarAgenda']['DatosProf']['tipoid'].','.$_SESSION['BorrarAgenda']['DatosProf']['tercero'];
		}
		$b=explode(',',$_REQUEST['Profesional']);
		foreach($profesionales as $k=>$v)
		{
			if($v['tipo_id_tercero']==$b[0] and $v['tercero_id']==$b[1])
			{
				$this->salida.="<option value=\"".$v['tipo_id_tercero'].','.$v['tercero_id'].','.$v['nombre']."\" selected>".$v['nombre']."</option>";
			}
			else
			{
				$this->salida.="<option value=\"".$v['tipo_id_tercero'].','.$v['tercero_id'].','.$v['nombre']."\">".$v['nombre']."</option>";
			}
		}
		$this->salida .= "</select></td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .= '<br>';


		$this->salida .= '<table align="center" width="70%" border="0">';
		$this->salida .= '<tr>';
		$this->salida .= '<td align="center">';
		$this->salida .= '<input type="submit" name="Cambiar" value="Cambiar" class="input-submit">';
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
		$accion=ModuloGetURL('app','CreacionAgenda','user','ListadoAgendaMesTurnos',$vec1);
		$this->salida .= '<form name="volver" method="post" action="'.$accion.'">';
		$this->salida .= '<input type="submit" name="volver" value="VOLVER" class="input-submit">';
		$this->salida .= '</form>';
		$this->salida .= '</td>';
		$this->salida .= '</tr>';
		$this->salida .= '</table>';
		$this->salida .= '<br>';
		$this->salida .= ThemeCerrarTabla();
		return true;
	}




/**
* Muestra la informacion final para relizar el cambio de la agenda
*
* @access private
* @return boolean Para identificar que se realizo.
*/

	function PantallaFinalCambioAgenda()
	{
		//print_r($_REQUEST);
		$agenda_cita=$this->BusquedaAgendasPantallaFinal();
		//print_r($agenda_cita);
		$this->salida=ThemeAbrirTabla('CONFIRMACION DATOS');
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
		$a=explode(',',$_REQUEST['Profesional']);
		$this->salida .= $a[2];
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .= '<br>';
		$this->salida .= '<table width="70%" align="center" class="modulo_table_list">';
		$this->salida .= '<tr align="center" class="modulo_table_title">';
		$this->salida .= '<td align="center">';
		$this->salida .= "Agenda";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Turno";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Fecha";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Hora";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Estado";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Identificación";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Nombre";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$i=0;
		foreach($agenda_cita as $k=>$v)
		{
			foreach($v as $t=>$m)
			{
				foreach($m as $s=>$q)
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
				$this->salida .=$q['agenda_turno_id'];
				$this->salida .= "</td>";
				$this->salida .= '<td align="center">';
				$this->salida .=$q['agenda_cita_id'];
				$this->salida .= "</td>";
				$this->salida .= '<td align="center">';
				$this->salida .=$_REQUEST['DiaEspe'];
				$this->salida .= "</td>";
				$this->salida .= '<td align="center">';
				$this->salida .=$q['hora'];
				$this->salida .= "</td>";
				$this->salida .= '<td align="center">';
				if($q['sw_estado']===NULL)
				{
					$this->salida .="No Asignada";
				}
				if($q['sw_estado']==1)
				{
					$this->salida .="Asignada";
				}
				if($q['sw_estado']==2)
				{
					$this->salida .="Paga";
				}
				$this->salida .= "</td>";
				$this->salida .= '<td align="center">';
				$this->salida .=$q['identificacion'];
				$this->salida .= "</td>";
				$this->salida .= '<td align="center">';
				$this->salida .=$q['nombre_completo'];
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
			}
		}
		$this->salida .= "</table>";
		$this->salida .= '<br>';
		$this->salida .= '<table align="center" width="70%" border="0">';
		$this->salida .= '<tr>';
		$this->salida .= '<td align="center">';
		foreach($_REQUEST as $v=>$datos)
		{
			if($v!='modulo' and $v!='metodo' and $v!='tipo' and $v!='SIIS_SID')
			{
				$vec[$v]=$datos;
			}
		}
		$_SESSION['BorrarAgenda']['DatosAgenda']=$agenda_cita;
		$accion=ModuloGetURL('app','CreacionAgenda','user','CambiarAgendaCompletaTotal',$vec);
		$this->salida.='<form name="siguiente" method="post" action="'.$accion.'">';
		$this->salida .= '<input type="submit" name="Cambiar" value="Cambiar" class="input-submit">';
		$this->salida .= '</form>';
		$this->salida .= '</td>';
		$this->salida .= '<td align="center">';
		foreach($_REQUEST as $v=>$datos)
		{
			if($v!='modulo' and $v!='tipo' and $v!='SIIS_SID' and $v!='metodo')
			{
				$vec1[$v]=$datos;
			}
		}
		$accion=ModuloGetURL('app','CreacionAgenda','user','CambiarAgendaCompleta',$vec1);
		$this->salida .= '<form name="volver" method="post" action="'.$accion.'">';
		$this->salida .= '<input type="submit" name="volver" value="VOLVER" class="input-submit">';
		$this->salida .= '</form>';
		$this->salida .= '</td>';
		$this->salida .= '</tr>';
		$this->salida .= '</table>';
		$this->salida .= '<br>';
		$this->salida .= ThemeCerrarTabla();
		return true;
	}





/**
* Muestra el listado de turnos que tenga un profesional en un dia de agenda
*
* @access private
* @return boolean Para identificar que se realizo.
*/

	function ListadoDiaAgenda()
	{
		$turnosdia=$this->ListadoTurnosDia();
		if($turnosdia)
		{
			$this->SetJavaScripts('DatosPaciente');
			$this->salida=ThemeAbrirTabla('CAMBIAR AGENDA');
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
			$this->salida .= $_SESSION['BorrarAgenda']['DatosProf']['nombrep'];
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
				$this->salida .= '<label class="label_error">NO SE PUEDE BORRAR LA AGENDA POR TENER CITAS ASIGNADAS</label>';
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
				$this->salida .= "</table>";
				$this->salida .= '<br>';
			}
			foreach($_REQUEST as $v=>$datos)
			{
				if($v!='modulo' and $v!='metodo' and $v!='tipo' and $v!='SIIS_SID' and substr_count ($v,'seleccion')!=1 and $v!='Borrar')
				{
					$vec[$v]=$datos;
				}
			}
			$accion=ModuloGetURL('app','CreacionAgenda','user','BorrarAgendaDia',$vec);
			$this->salida.='<form name="siguiente" method="post" action="'.$accion.'">';
			$this->salida .= '<table width="70%" align="center" class="modulo_table_list">';
			$this->salida .= '<tr align="center" class="modulo_table_title">';
			$this->salida .= '<td align="center">';
			$this->salida .= "Hora";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "Paciente";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "Telefono";
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
				$dato=RetornarWinOpenDatosPaciente($turnosdia[6][$i],$turnosdia[5][$i],$turnosdia[3][$i]);
				$this->salida .=$dato;
				$this->salida .= "</td>";
				$this->salida .= '<td align="center">';
				$this->salida .=$turnosdia[4][$i];
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
			$this->salida .= '</td>';
			$this->salida .= '<td align="center">';
			$this->salida .= '<input type="submit" name="Cancelar" value="Cancelar" class="input-submit">';
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
			$accion=ModuloGetURL('app','CreacionAgenda','user','ListadoAgendaMesTurnos',$vec1);
			$this->salida .= '<form name="volver" method="post" action="'.$accion.'">';
			$this->salida .= '<input type="submit" name="volver" value="VOLVER" class="input-submit">';
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



/**
* Muestra el listado de turnos para realizar el cambio de la agenda
*
* @access private
* @return boolean Para identificar que se realizo.
*/

	function CambiarAgenda()
	{
		unset($_SESSION['BorrarAgenda']['DatosCitas']);
		unset($_SESSION['BorrarAgenda']['DatosCitas1']);
		$agenda_cita=$this->BusquedaAgendas();
		if(empty($agenda_cita))
		{
			if($this->ListadoAgendaMesTurnos()==false)
			{
				return false;
			}
			return true;
		}
		$_SESSION['BorrarAgenda']['DatosCitas']=$agenda_cita;
		$this->salida=ThemeAbrirTabla('CAMBIAR AGENDA');
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
		$this->salida .= $_SESSION['BorrarAgenda']['DatosProf']['nombrep'];
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .= '<br>';
		$accion=ModuloGetURL('app','CreacionAgenda','user','CambiarAgendaDia');
		$this->salida.='<form name="siguiente" method="post" action="'.$accion.'">';
		$this->salida .= '<table width="70%" align="center" class="modulo_table_list">';
		$this->salida .= '<tr align="center" class="modulo_table_title">';
		$this->salida .= '<td align="center">';
		$this->salida .= "Agenda";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Turno";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Fecha";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Hora";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Selección";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$i=0;
		foreach($agenda_cita as $k=>$v)
		{
			foreach($v as $t=>$m)
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
				$this->salida .=$m['agenda_turno_id'];
				$this->salida .= "</td>";
				$this->salida .= '<td align="center">';
				$this->salida .=$m['agenda_cita_id'];
				$this->salida .= "</td>";
				$this->salida .= '<td align="center">';
				$this->salida .=$m['fecha_turno'];
				$this->salida .= "</td>";
				$this->salida .= '<td align="center">';
				$this->salida .=$m['hora'];
				$this->salida .= "</td>";
				$this->salida .= '<td align="center">';
				$this->salida .= '<input type="radio" name="citas" value="'.$m['agenda_turno_id'].','.$m['agenda_cita_id'].','.$m['hora'].'" class="input-submit">';
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
			}
		}
		$this->salida .= "</table>";
		$this->salida .= '<br>';
		$this->salida .= '<table align="center" width="70%" border="0">';
		$this->salida .= '<tr>';
		$this->salida .= '<td align="center">';
		$this->salida .= '<input type="submit" name="Cambiar" value="Cambiar" class="input-submit">';
		$this->salida .= '</form>';
		$this->salida .= '</td>';
		$this->salida .= '<td align="center">';
		foreach($_REQUEST as $v=>$datos)
		{
			if($v!='modulo' and $v!='tipo' and $v!='SIIS_SID' and $v!='metodo' and $v!='DiaEspe' and substr_count ($v,'citas')!=1)
			{
				$vec1[$v]=$datos;
			}
		}
		$accion=ModuloGetURL('app','CreacionAgenda','user','ListadoAgendaMesTurnos',$vec1);
		$this->salida .= '<form name="volver" method="post" action="'.$accion.'">';
		$this->salida .= '<input type="submit" name="volver" value="VOLVER" class="input-submit">';
		$this->salida .= '</form>';
		$this->salida .= '</td>';
		$this->salida .= '</tr>';
		$this->salida .= '</table>';
		$this->salida .= '<br>';
		$this->salida .= ThemeCerrarTabla();
		return true;
	}




/**
* Muestra el listado de citas que tenga la agenda de un dia
*
* @access private
* @return boolean Para identificar que se realizo.
* @param string mensaje para mostrar en el cambio de la agenda
*/


	function CambiarAgendaDia($mensaje)
	{
		if(empty($_REQUEST['citas']))
		{
			if($this->CambiarAgenda()==false)
			{
				return false;
			}
			return true;
		}
		$a=explode(',',$_REQUEST['citas']);
		$profesionales=$this->BuscarProfesionales($_SESSION['BorrarAgenda']['DatosCitas'][$a[0]][$a[1]]['especialidad']);
		$citas=$this->BusquedaDatosTurno($a[1]);
		$this->salida=ThemeAbrirTabla('CAMBIAR AGENDA BUSQUEDA DE DIA');
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
		$this->salida .= $_SESSION['BorrarAgenda']['DatosProf']['nombrep'];
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .= '<br>';
		if(!empty($citas))
		{
			$_SESSION['BorrarAgenda']['DatosCitas1']=$citas;
			$this->salida .= '<table width="70%" align="center" class="modulo_table_list">';
			$this->salida .= '<tr align="center" class="modulo_table_title">';
			$this->salida .= '<td align="center" colspan="2">';
			$this->salida .= 'PACIENTES';
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= '<tr align="center" class="modulo_table_title">';
			$this->salida .= '<td align="center">';
			$this->salida .= 'IDENTIFICACION';
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= 'NOMBRE';
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			foreach($citas as $k=>$v)
			{
				if($spy==0)
				{
					$this->salida .= '<tr align="center" class="modulo_list_oscuro">';
					$spy=1;
				}
				else
				{
					$this->salida .= '<tr align="center" class="modulo_list_claro">';
					$spy=0;
				}
				$this->salida .= '<td align="center">';
				$this->salida .= $v['tipo_id_paciente'].' - '.$v['paciente_id'];
				$this->salida .= "</td>";
				$this->salida .= '<td align="center">';
				$this->salida .= $v['nombre'];
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
			}
			$this->salida .= "</table>";
		}
		if(!empty($_REQUEST['DiaEspe']))
		{
			if($_REQUEST['DiaEspe']>=date("Y-m-d"))
			{
				$this->salida .= '<table width="70%" align="center">';
				$this->salida .= '<tr align="center">';
				$this->salida .= '<td align="center">';
				$this->salida .= '<label class="label_error">FECHA SELECCIONADA: '.$_REQUEST['DiaEspe'].'</label>';
				$salida ="<input type=\"hidden\" value=\"".$_REQUEST['DiaEspe']."\" name=\"DiaEspe\">";
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
				$this->salida .= "</table>";
			}
			else
			{
				$this->salida .= '<table width="70%" align="center">';
				$this->salida .= '<tr align="center">';
				$this->salida .= '<td align="center">';
				$this->salida .= '<label class="label_error">NO SE PUEDE CREAR AGENDA PARA EL DIA '.$_REQUEST['DiaEspe'].'</label>';
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
				$this->salida .= "</table>";
			}
		}
		else
		{
			if(!empty($mensaje))
			{
				$this->salida .= '<table width="70%" align="center">';
				$this->salida .= '<tr align="center">';
				$this->salida .= '<td align="center">';
				$this->salida .= '<label class="label_error">'.$mensaje.'</label>';
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
				$this->salida .= "</table>";
			}
		}
		$this->salida .= "<table border=\"0\" width=\"50%\" align=\"center\">";
//aqui inserte lo de lorena
		$this->salida .= "<tr><td>";
		$this->salida.="\n".'<script>'."\n";
		$this->salida.='function year1(t)'."\n";
		$this->salida.='{'."\n";
		$this->salida.='document.cosa.action="Contenido.php?year="+t.elements[0].value+"&meses="+t.elements[1].value+"';
		foreach($_REQUEST as $v=>$v1)
		{
			if($v=='Cambiar')
			{
				unset($_REQUEST[$v]);
			}
			if($v!='year' and $v!='meses' and $v!='DiaEspe' and $v!='Cambiar')
			{
				if (is_array($v1))
				{
					foreach($v1 as $k2=>$v2)
					{
						if (is_array($v2))
						{
							foreach($v2 as $k3=>$v3)
							{
								if (is_array($v3))
								{
									foreach($v3 as $k4=>$v4)
									{
										$this->salida .= "&$v" . "[$k2][$k3][$k4]=$v4";
									}
								}
								else
								{
									$this->salida .= "&$v" . "[$k2][$k3]=$v3";
								}
							}
						}
						else
						{
							$this->salida .= "&$v" . "[$k2]=$v2";
						}
					}
				}
				else
				{
					$this->salida .= "&$v=$v1";
				}
			}
		}
		$this->salida.='";'."\n";
		$this->salida.='document.cosa.submit();';
		$this->salida.='}'."\n";
		$this->salida.='</script>';

		$this->salida .='<form name="cosa" method="post">';
		$this->salida .="<table border=\"0\" width=\"80%\" align=\"center\">";
		$this->salida .='<tr align="center">';
		$this->salida .="<td class=\"label\">AÑO</td><td><select name=\"year\" onchange=\"year1(this.form)\" class=\"select\">";
		if(empty($_REQUEST['year']))
		{
			$year=date("Y");
			$this->AnosAgenda(True,$year);
		}
		else
		{
			$year=$_REQUEST['year'];
			$this->AnosAgenda(true,$year);
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
			$mes=$_REQUEST['meses'];
			$this->MesesAgenda(True,$year,$mes);
		}
		$this->salida .= "</select>";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .='</form>';
		$this->ReturnMetodoExterno('app', 'Agenda', 'user', 'CalendarioEstandard');
		$this->salida .= "   </td></tr>";

		$this->salida .= "<tr class=\"modulo_table_list_title\">";
		$this->salida .= "<td>";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		/**************************************/
		$this->salida .= "</table>";
		$this->salida .= '<br>';
		$accion=ModuloGetURL('app','CreacionAgenda','user','CambiarAgendaTurno',array('citas'=>$_REQUEST['citas'],'year'=>$year,'meses'=>$meses));
		$this->salida .= '<form name="cambiar" method="post" action="'.$accion.'">';
		$this->salida .=$salida;

		$this->salida .="<table border=\"0\" width=\"50%\" align=\"center\">";
		$this->salida .='<tr align="center">';
		if(empty($_REQUEST['hora']) and empty($_REQUEST['minutos']))
		{
			$a=explode(',',$_REQUEST['citas']);
			$b=explode(':',$a[2]);
		}
		else
		{
			$b[0]=$_REQUEST['hora'];
			$b[1]=$_REQUEST['minutos'];
		}
		$this->salida .="<td class=\"label\">HORA:</td><td><input type=\"text\" name=\"hora\" value=\"".$b[0]."\" size=\"2\" class=\"input-text\" maxlength=\"2\">:<input type=\"text\" name=\"minutos\" value=\"".$b[1]."\" size=\"2\" class=\"input-text\" maxlength=\"2\"></td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$justificacion=$this->BusquedaTipoJustificacion();
		$this->salida .="<table border=\"0\" width=\"50%\" align=\"center\">";
		$this->salida .='<tr align="center">';
		$this->salida .="<td class=\"".$this->SetStyle("justificacion")."\">JUSTIFICACION:</td>";
		$this->salida .="<td><select name=\"justificacion\" class=\"select\">";
		$this->salida .="<option value=\"-1\">--SELECCIONE--</option>";
		foreach($justificacion as $k=>$v)
		{
			if($_REQUEST['justificacion']==$k)
			{
				$this->salida .="<option value=\"$k\" selected>".$v['descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"$k\">".$v['descripcion']."</option>";
			}
		}
		$this->salida .="</select>";
		$this->salida .="</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";

		$this->salida .="<table border=\"0\" width=\"50%\" align=\"center\">";
		$this->salida .='<tr align="center">';
		$this->salida .="<td class=\"label\">PROFESIONALES:</td><td><select name=\"Profesional\" class=\"select\">";
		$a=explode(',',$_REQUEST['citas']);
		foreach($profesionales as $k=>$v)
		{
			if($v['tipo_id_tercero']==$_SESSION['BorrarAgenda']['DatosCitas'][$a[0]][$a[1]]['tipo_id_profesional'] and $v['tercero_id']==$_SESSION['BorrarAgenda']['DatosCitas'][$a[0]][$a[1]]['profesional_id'])
			{
				$this->salida.="<option value=\"".$v['tipo_id_tercero'].','.$v['tercero_id']."\" selected>".$v['nombre']."</option>";
			}
			else
			{
				$this->salida.="<option value=\"".$v['tipo_id_tercero'].','.$v['tercero_id']."\">".$v['nombre']."</option>";
			}
		}
		$this->salida .= "</select></td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .= '<br>';

		$this->salida .= '<table align="center" width="70%" border="0">';
		$this->salida .= '<tr>';
		$this->salida .= '<td align="center">';
		$this->salida .= '<input type="submit" name="Cambiar" value="Cambiar" class="input-submit">';
		$this->salida .= '</form>';
		$this->salida .= '</td>';
		$this->salida .= '<td align="center">';
		$accion=ModuloGetURL('app','CreacionAgenda','user','CambiarAgenda');
		$this->salida .= '<form name="volver" method="post" action="'.$accion.'">';
		$this->salida .= '<input type="submit" name="volver" value="VOLVER" class="input-submit">';
		$this->salida .= '</form>';
		$this->salida .= '</td>';
		$this->salida .= '</tr>';
		$this->salida .= '</table>';
		$this->salida .= '<br>';
		$this->salida .= ThemeCerrarTabla();
		return true;
	}


	
/**
	* esta funcion determina segun el vector de frmError si existe algun campo sin llenar
	* @return string
	* @access private
	* @param string identificacion del campo para seÃ±alar como no lleno
	*/

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
  function Asignar()
  {
    $_SESSION['ini']['hora']=$_REQUEST['inihora'];
    $_SESSION['ini']['minutos']=$_REQUEST['iniminutos'];
    $_SESSION['fin']['hora']=$_REQUEST['finhora'];
    $_SESSION['fin']['minutos']=$_REQUEST['finminutos'];
    $_SESSION['dias']['1']=$_REQUEST['dias1'];
    $_SESSION['dias']['2']=$_REQUEST['dias2'];
    $_SESSION['dias']['3']=$_REQUEST['dias3'];
    $_SESSION['dias']['4']=$_REQUEST['dias4'];
    $_SESSION['dias']['5']=$_REQUEST['dias5'];
    $_SESSION['dias']['6']=$_REQUEST['dias6'];
    $_SESSION['dias']['7']=$_REQUEST['dias7'];
    $_SESSION['dias']['8']=$_REQUEST['dias8'];
    $_SESSION['dias']['9']=$_REQUEST['dias9'];
    $_SESSION['dias']['10']=$_REQUEST['dias10'];
    $_SESSION['dias']['11']=$_REQUEST['dias11'];
    $_SESSION['dias']['12']=$_REQUEST['dias12'];
    $_SESSION['dias']['13']=$_REQUEST['dias13'];
    $_SESSION['dias']['14']=$_REQUEST['dias14'];
    $_SESSION['dias']['15']=$_REQUEST['dias15'];
    $_SESSION['dias']['16']=$_REQUEST['dias16'];
    $_SESSION['dias']['17']=$_REQUEST['dias17'];
    $_SESSION['dias']['18']=$_REQUEST['dias18'];
    $_SESSION['dias']['19']=$_REQUEST['dias19'];
    $_SESSION['dias']['20']=$_REQUEST['dias20'];
    $_SESSION['dias']['21']=$_REQUEST['dias21'];
    $_SESSION['dias']['22']=$_REQUEST['dias22'];
    $_SESSION['dias']['23']=$_REQUEST['dias23'];
    $_SESSION['dias']['24']=$_REQUEST['dias24'];
    $_SESSION['dias']['25']=$_REQUEST['dias25'];
    $_SESSION['dias']['26']=$_REQUEST['dias26'];
    $_SESSION['dias']['27']=$_REQUEST['dias27'];
    $_SESSION['dias']['28']=$_REQUEST['dias28'];
    $_SESSION['dias']['29']=$_REQUEST['dias29'];
    $_SESSION['dias']['30']=$_REQUEST['dias30'];
    $_SESSION['dias']['31']=$_REQUEST['dias31'];
    $_SESSION['mes']['1']=$_REQUEST['mes1'];
    $_SESSION['mes']['2']=$_REQUEST['mes2'];
    $_SESSION['mes']['3']=$_REQUEST['mes3'];
    $_SESSION['mes']['4']=$_REQUEST['mes4'];
    $_SESSION['mes']['5']=$_REQUEST['mes5'];
    $_SESSION['mes']['6']=$_REQUEST['mes6'];
    $_SESSION['mes']['7']=$_REQUEST['mes7'];
    $_SESSION['mes']['8']=$_REQUEST['mes8'];
    $_SESSION['mes']['9']=$_REQUEST['mes9'];
    $_SESSION['mes']['10']=$_REQUEST['mes10'];
    $_SESSION['mes']['11']=$_REQUEST['mes11'];
    $_SESSION['mes']['12']=$_REQUEST['mes12'];
    $_SESSION['semana']['0']=$_REQUEST['semana0'];
    $_SESSION['semana']['1']=$_REQUEST['semana1'];
    $_SESSION['semana']['2']=$_REQUEST['semana2'];
    $_SESSION['semana']['3']=$_REQUEST['semana3'];
    $_SESSION['semana']['4']=$_REQUEST['semana4'];
    $_SESSION['semana']['5']=$_REQUEST['semana5'];
    $_SESSION['semana']['6']=$_REQUEST['semana6'];
    $_SESSION['semana']['nosabados']=$_REQUEST['nosabados'];
    $_SESSION['semana']['nodomingos']=$_REQUEST['nodomingos'];
    $_SESSION['semana']['nofestivos']=$_REQUEST['nofestivos'];
    $_SESSION['semana']['todos']=$_REQUEST['todos'];
    $_SESSION['semana']['todosd']=$_REQUEST['todosd'];
    return true;
  }

  function clear()
  {
    UNSET($_SESSION['ini']);
    UNSET($_SESSION['fin']);
    UNSET($_SESSION['dias']);
    UNSET($_SESSION['semana']);
    UNSET($_SESSION['mes']);
    return true;
  }
}
?>
