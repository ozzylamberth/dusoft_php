<?php

/**
 * $Id: app_CreacionAgenda_userclasses_HTML.php,v 1.24 2006/07/07 21:21:43 lorena Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo para crear la agenda de los profesionales, para poder realizar la asignacion de  * citas
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

	function Menu(){
		$this->salida = ThemeAbrirTabla('CONSULTA EXTERNA');
		$this->salida .= "<br>";
		$this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
		$this->salida .= "<tr>";
		$this->salida .= "<td align=\"center\" class=\"modulo_table_list_title\">MENU</td>";
		$this->salida .= "</tr>";
		$this->salida .='<tr class="modulo_list_claro">';
		$this->salida .='<td align="center">';
		$accion=ModuloGetURL('app','CreacionAgenda','user','CreacionAgenda');
		$this->salida .='<a href="'.$accion.'">Creaci?n Agenda M?dica</a>';
		$this->salida .='</td>';
		$this->salida .='</tr>';
		$this->salida .='<tr class="modulo_list_oscuro">';
		$this->salida .='<td align="center">';
		$accion=ModuloGetURL('app','CreacionAgenda','user','CambiarBorrarAgenda');
		$this->salida .='<a href="'.$accion.'">Modificaci?n Agenda M?dica</a>';
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

	function Encabezado(){


		$this->salida .= '<table width="70%" align="center" class="modulo_table_list">';
		$this->salida .= '<tr>';
		$this->salida .= '  <td colspan="4" align="center" class="modulo_table_list_title">';
		$this->salida .=    'EMPRESA:&nbsp&nbsp&nbsp&nbsp;'.$_SESSION['CreacionAgenda']['nomemp'];
		$this->salida .= "  </td>";
		$this->salida .= "</tr>";
		$this->salida .= "<tr>";
		$this->salida .= '  <td align="center" class="modulo_table_list_title">';
		$this->salida .= "  Centro Utilidad";
		$this->salida .= "  </td>";
		$this->salida .= '  <td class="modulo_list_claro">';
		$this->salida .=    $_SESSION['CreacionAgenda']['nomcu'];
		$this->salida .= "  </td>";
		$this->salida .= '  <td align="center" class="modulo_table_list_title">';
		$this->salida .= "  Unidad Funcional";
		$this->salida .= "  </td>";
    $this->salida .= '  <td class="modulo_list_claro">';
		$this->salida .=    $_SESSION['CreacionAgenda']['nomuf'];
		$this->salida .= "  </td>";
		$this->salida .= "</tr>";
		$this->salida .= "<tr>";
		$this->salida .= '  <td align="center" class="modulo_table_list_title">';
		$this->salida .= "  Departamento";
		$this->salida .= "  </td>";
    $this->salida .= '  <td class="modulo_list_claro">';
		$this->salida .=    $_SESSION['CreacionAgenda']['nomdep'];
		$this->salida .= "  </td>";
		$this->salida .= '  <td class="modulo_table_list_title">';
		$this->salida .= "  Tipo Consulta";
		$this->salida .= "  </td>";
    $this->salida .= '  <td class="modulo_list_claro">';
		$this->salida .=    $_SESSION['CreacionAgenda']['nombre'];
		$this->salida .= "  </td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .= '<BR>';
		return true;
	}



//Creaci?n de Agenda Medica
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

	function CreacionAgenda(){

		if($_REQUEST['volver1']=='true'){
			unset($_SESSION['CreacionAgenda']);
		}
		if((empty($_SESSION['CreacionAgenda']['Cita']) or $_REQUEST['Citas']['tipo_consulta_id']!=$_SESSION['CreacionAgenda']['Cita']) and !empty($_REQUEST['Citas']['tipo_consulta_id'])){
			$_SESSION['CreacionAgenda']['Cita']=$_REQUEST['Citas']['tipo_consulta_id'];
			$_SESSION['CreacionAgenda']['nomemp']=$_REQUEST['Citas']['descripcion1'];
			$_SESSION['CreacionAgenda']['nomcu']=$_REQUEST['Citas']['descripcion2'];
			$_SESSION['CreacionAgenda']['nomuf']=$_REQUEST['Citas']['descripcion3'];
			$_SESSION['CreacionAgenda']['nomdep']=$_REQUEST['Citas']['descripcion4'];
			$_SESSION['CreacionAgenda']['nombre']=$_REQUEST['Citas']['descripcion5'];
			$_SESSION['CreacionAgenda']['empresa']=$_REQUEST['Citas']['empresa_id'];
			$_SESSION['CreacionAgenda']['departamento']=$_REQUEST['Citas']['departamento'];
		}
		if(empty($_SESSION['CreacionAgenda']['Cita'])){
			$url[0]='app';
			$url[1]='CreacionAgenda';
			$url[2]='user';
			$url[3]='CreacionAgenda';
			$url[4]='Citas';
			$Cita=$this->CitaConsulta($url);
			if($Cita==false){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "NO EXISTEN PROFESIONALES PARA ESA EMPRESA.";
				return false;
			}
		}else{
		  $this->salida  = ThemeAbrirTabla('CREACI?N AGENDA M?DICA');
		  $this->Encabezado();
      if($_REQUEST['volver2']=='true'){
				$_SESSION['CreacionAgenda']['tercero']='';
			}
			//if(empty($_SESSION['CreacionAgenda']['tercero'])){
			//	$_SESSION['CreacionAgenda']['tercero']=$_REQUEST['tercero'];
			//	$_SESSION['CreacionAgenda']['tipoid']=$_REQUEST['tipoid'];
			//	$_SESSION['CreacionAgenda']['nombrep']=$_REQUEST['nombrep'];
			//}
			$profesionales=$this->Profesionales();
			if($profesionales){
				$this->salida .="<script language='javascript'>";
				$this->salida .= 'function mOvr(src,clrOver){';
				$this->salida .= '  src.style.background = clrOver;';
				$this->salida .= '}';
				$this->salida .= 'function mOut(src,clrIn){';
				$this->salida .= '  src.style.background = clrIn;';
				$this->salida .= '}';
				$this->salida .= '</script>';
				$accion=ModuloGetURL('app','CreacionAgenda','user','CreacionAgenda',array('volver1'=>'true'));
				$this->salida .= '<form name="Volver" method="post" action="'.$accion.'">';
				$this->salida .= '<table align="center" width="70%" border="0" class="modulo_table_list">';
				$this->salida .= '<tr align="center" class="modulo_table_list_title">';
				$this->salida .= '<td>';
				$this->salida .= 'PROFESIONALES';
				$this->salida .= '</td>';
				$this->salida .= '<td>NUEVA</td>';
				$this->salida .= '<td>VER</td>';
				$this->salida .= '</tr>';
				$i=0;
				$backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
				while($i<sizeof($profesionales[0])){
					if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
					$this->salida.="<tr  class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>";
					$this->salida .= '<td>';
					$this->salida .= $profesionales[2][$i];
					$this->salida .= '</td>';
					$this->salida .= '<td align="center" width="5%">';
					$this->salida .= "<a href=".ModuloGetURL('app','CreacionAgenda','user','LlamaCreacionAgendaNueva',array('tercero'=>$profesionales[1][$i],'tipoid'=>$profesionales[0][$i],'nombrep'=>$profesionales[2][$i]))."><img title=\"Creaci?n Agenda\" border=\"0\" src=\"".GetThemePath()."/images/planblanco.png\"></a>";
					$this->salida .= '</td>';
					$turnos=$this->TurnosProgramadosProfesional($profesionales[0][$i],$profesionales[1][$i]);
					$this->salida .= '<td align="center" width="5%">';
					if($turnos==0){
						$this->salida .= '&nbsp;';
					}else{
						$accion=ModuloGetURL('app','CreacionAgenda','user','LlamaAgendaConsultaTurnos',array("tercero"=>$profesionales[1][$i],"tipoid"=>$profesionales[0][$i],"nombrep"=>$profesionales[2][$i]));
						$this->salida .= "<a href=".$accion."><img title=\"Consulta Agenda\" border=\"0\" src=\"".GetThemePath()."/images/pconsultar.png\"></a>";
					}
					$this->salida .= '</td>';
					$this->salida .= '</tr>';
					$i++;
					$y++;
				}
				$this->salida .= '</table>';
				$this->salida .= '<br>';
				$this->salida .= '<table align="center" width="70%" border="0">';
				$this->salida .= '<tr>';
				$this->salida .= '<td align="center">';
				$this->salida .= '<input type="submit" name="volver" value="VOLVER" class="input-submit">';
				$this->salida .= '</form>';
				$this->salida .= '</td>';
				$this->salida .= '</tr>';
				$this->salida .= '</table>';
			}else{
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
			}
			$this->salida .= ThemeCerrarTabla();
		}
    return true;
	}

	/* La funcion FormaMensaje se encarga de retornar un mensaje para el usuario
	* @return boolean
	* @param string mensaje a retornar para el usuario
	* @param string titulo de la ventana a mostrar
	* @param string lugar a donde debe retornar la ventana
	* @param boolean tipo boton de la ventana
	*/
	function FormaMensaje($mensaje,$titulo,$accion,$boton){

		$this->salida .= ThemeAbrirTabla($titulo);
		$this->salida .= "			      <table class=\"normal_10\" width=\"60%\" align=\"center\">";
		$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
		$this->salida .= "				       <tr><td colspan=\"2\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>";
		if($boton){
			$this->salida .= "				       <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"$boton\"></td></tr>";
		}
	  else{
			$this->salida .= "				       <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Aceptar\"></td></tr>";
	  }
		$this->salida .= "			     </form>";
		$this->salida .= "			     </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function AgendaConsultaTurnos($tercero,$tipoid,$nombrep){

    $this->salida  = ThemeAbrirTabla('CONSULTA AGENDA M?DICA');
		$this->Encabezado();
		$this->salida .= '<table width="70%" align="center" class="modulo_table_list">';
		$this->salida .= '<tr align="center" class="modulo_table_title">';
		$this->salida .= '<td align="center">';
		$this->salida .= "Profesional";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= '<tr class="modulo_list_oscuro">';
		$this->salida .= "<td align='center'>$nombrep</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table><BR>";
    $turnos=$this->TurnosProgramadosProfesional($tipoid,$tercero,1);
		if(sizeof($turnos)==0){
		  $this->salida .= '<table width="70%" align="center" class="modulo_table_list">';
			$this->salida .= '<tr align="center">';
			$this->salida .= '<td class="label_error" colspan="2">';
			$this->salida .= '<br>';
			$this->salida .= 'NO EXISTE AGENDA PARA ESTE PROFESIONAL.';
			$this->salida .= '</td>';
			$this->salida .= '</tr>';
      $this->salida .= '<table><BR>';
		}else{
			$i=0;
			//print_R($turnos[2]);
			$anoAnt=-1;
      $mesAnt=-1;
			$canMes=1;
      $this->salida .= '<table width="95%" align="center" border="0">';
			while($i<sizeof($turnos[2])){
			  (list($ano,$mes,$dia)=explode('-',$turnos[2][$i]));
				if($anoAnt!=$ano){
					$this->salida .= '<tr>';
					$this->salida .= "<td colspan=\"4\" align=\"center\" class=\"modulo_table_list_title\">$ano</td>";
					$this->salida .= '</tr>';
					$anoAnt=$ano;
				}
				if($mesAnt!=$mes){
					if($canMes==1){
					  $this->salida .= "<tr>";
					}
					$this->salida .= "<td valign=\"top\" class=\"modulo_list_claro\">";
          $this->salida .= '  <table width="100%" align="center" border="0">';
					$totalseg=mktime(0,0,0,$mes,$dia,$ano);
					$actionConsulta=ModuloGetURL('app','CreacionAgenda','user','LlamaConsultaAgendaMes',array("tipoid"=>$tipoid,"tercero"=>$tercero,"fecha"=>$turnos[2][$i],"nombrep"=>$nombrep,"filtroFecha"=>$turnos[1][$i]));
          $this->salida .= "  <tr class=\"modulo_table_list_title\"><td colspan=\"7\"><a class=\"MesAno\" href=\"$actionConsulta\">".ucwords(strftime("%B",$totalseg))."</a></td></tr>";
          $this->salida .= "  <tr class=\"modulo_table_list_title\">";
					$this->salida .= "  <td align=\"center\">Lun</td>";
					$this->salida .= "  <td align=\"center\">Mar</td>";
					$this->salida .= "  <td align=\"center\">Mie</td>";
					$this->salida .= "  <td align=\"center\">Jue</td>";
					$this->salida .= "  <td align=\"center\">Vie</td>";
					$this->salida .= "  <td align=\"center\">Sab</td>";
					$this->salida .= "  <td align=\"center\">Dom</td>";
					$this->salida .= "  </tr>";
					$totalsegPrimerDia=mktime(0,0,0,$mes,'01',$ano);
          $IniDiaMes=strftime("%w",$totalsegPrimerDia);
					if($IniDiaMes==0){
					  $IniDiaMes=7;
					}
					$IniFinMes=date('t',$totalsegPrimerDia);
					$j=0;
					$y=0;
					$bandera=1;
          while($j<$IniFinMes){
					$totalDiasSem=1;
					$this->salida .= "  <tr>";
					while($totalDiasSem<8 && $j<$IniFinMes){
					  if($totalDiasSem==$IniDiaMes && $bandera==1){
              $bandera=0;
							$j=1;
							if(in_array($ano.'-'.$mes.'-'.str_pad($j,2,0,STR_PAD_LEFT),$turnos[2])){$clas='modulo_list_claro';}else{$clas='modulo_list_oscuro';}
              $this->salida .= "  <td class=\"$clas\" align=\"center\">".str_pad($j,2,0,STR_PAD_LEFT)."</td>";
						}else{
						  if($j>0){
							  $j++;
								if(in_array($ano.'-'.$mes.'-'.str_pad($j,2,0,STR_PAD_LEFT),$turnos[2])){$clas='modulo_list_claro';}else{$clas='modulo_list_oscuro';}
                $this->salida .= "  <td class=\"$clas\" align=\"center\">".str_pad($j,2,0,STR_PAD_LEFT)."</td>";
							}else{
                $this->salida .= "  <td class=\"modulo_list_oscuro\" align=\"center\">&nbsp;</td>";
							}
						}
						$totalDiasSem++;
					}
					if($totalDiasSem==7){
					  $this->salida .= "  </tr>";
					}
					$ultimodia=$totalDiasSem;
					$totalDiasSem=0;
					$y++;
					}
					if($ultimodia<7){
						while($ultimodia<8){
							$this->salida .= "  <td class=\"modulo_list_oscuro\" align=\"center\">&nbsp;</td>";
							$ultimodia++;
						}
						$this->salida .= "  </tr>";
					}
          $this->salida .= "  </table>";
					$this->salida .= '</td>';
					if($canMes==4){
					  $this->salida .= "</tr>";
            $canMes=1;
					}else{
            $canMes++;
					}
					$mesAnt=$mes;
				}
				$i++;
			}
			$this->salida .= '</table>';


      /*$this->salida .= '<BR><table width="70%" align="center" class="modulo_table_list" border="0" cellpadding="4">';
			$this->salida .= '<tr align="center" class="modulo_table_title">';
			$this->salida .= '<td align="center" colspan="3">Fecha</td>';

			//$this->salida .= '<td align="center">Hora Inicio</td>';
			//$this->salida .= '<td align="center">Hora Fin</td>';
			$this->salida .= "</tr>";
			$i=0;
			$mesexplodeAnt=-1;
			while($i<sizeof($turnos[0])){
				$this->salida.='<tr>';
				$a=array_keys($turnos[0],$turnos[0][$i]);
				if($turnos[0][$i]!=$b){
					$c=0;
				}
				if(sizeof($a)!=1 and $c==0){
					$b=$turnos[0][$i];
					$this->salida.='<td rowspan="'.sizeof($a).'" class="modulo_list_oscuro" valign="top">';
					$this->salida.=$turnos[0][$i];
					$this->salida.='</td>';
					$c=1;
				}else{
					if(sizeof($a)==1){
						$c=0;
						$this->salida.='<td class="modulo_list_oscuro">';
						$this->salida.=$turnos[0][$i];
						$this->salida.='</td>';
					}
				}
				$d=array_keys($turnos[1],$turnos[1][$i]);
				if($turnos[1][$i]!=$e){
					$f=0;
				}
				$actionConsulta=ModuloGetURL('app','CreacionAgenda','user','LlamaConsultaAgendaMes',array("tipoid"=>$tipoid,"tercero"=>$tercero,"fecha"=>$turnos[2][$i],"nombrep"=>$nombrep,"filtroFecha"=>$turnos[1][$i]));
				if(sizeof($d)!=1 and $f==0){
					$e=$turnos[1][$i];
					$this->salida.="<td rowspan=".sizeof($d)." class=\"modulo_list_oscuro\" valign=\"top\">";
					$k=explode("-",$turnos[1][$i]);
					$this->salida.="<a href=\"$actionConsulta\"><img border=\"0\" src=\"".GetThemePath()."/images/pconsultar.png\"></a>&nbsp&nbsp&nbsp&nbsp&nbsp;".$this->FormateoFechaMes($turnos[2][$i]);
					//$this->salida.=$k[1];
					$this->salida.='</td>';
					$f=1;
				}else{
					if(sizeof($d)==1){
						$f=0;
						$this->salida.="<td class=\"modulo_list_oscuro\">";
						$k=explode("-",$turnos[1][$i]);
						$this->salida.="<a href=\"$actionConsulta\"><img border=\"0\" src=\"".GetThemePath()."/images/pconsultar.png\"></a>&nbsp&nbsp&nbsp&nbsp&nbsp;".$this->FormateoFechaMes($turnos[2][$i]);
						//$this->salida.=$k[1];
						$this->salida.='</td>';
					}
				}
				$g=array_keys($turnos[2],$turnos[2][$i]);
				if($turnos[2][$i]!=$h){
					$j=0;
				}
				if(sizeof($g)!=1 and $j==0){
				  if($spy2==0){$estilo='modulo_list_oscuro';$spy2=1;}else{$estilo='modulo_list_claro';$spy2=0;}
					$h=$turnos[2][$i];
					$this->salida.="<td rowspan=".sizeof($g)." class=\"$estilo\" valign='top'>";
					$k=explode("-",$turnos[2][$i]);
					$this->salida.=$this->FormateoFechaDia($turnos[2][$i]);
					//$this->salida.=$k[2];
					$this->salida.='</td>';
					$j=1;
				}else{
					if(sizeof($g)==1){
					  if($spy2==0){$estilo='modulo_list_oscuro';$spy2=1;}else{$estilo='modulo_list_claro';$spy2=0;}
						$j=0;
						$this->salida.="<td class=\"$estilo\">";
						$k=explode("-",$turnos[2][$i]);
						$this->salida.=$this->FormateoFechaDia($turnos[2][$i]);
						//$this->salida.=$k[2];
						$this->salida.='</td>';
					}
				}
				/*if($spy2==0){
					$this->salida.='<td class="modulo_list_claro" align="center">';
				}else{
					$this->salida.='<td class="modulo_list_oscuro" align="center">';
				}
				$this->salida .= $turnos[3][$i];
				$this->salida .= "</td>";
				if($spy2==0){
					$this->salida.='<td class="modulo_list_claro" align="center">';
					$spy2=1;
				}else{
					$this->salida.='<td class="modulo_list_oscuro" align="center">';
					$spy2=0;
				}
				$this->salida .= $turnos[4][$i];
				$this->salida .= "</td>";
        */
				//$i++;
			//}
			//$this->salida .= '</table>';
			//$this->salida .='<br>';
		}
		$this->salida .= '<table width="70%" align="center" border="0" cellpadding="4">';
		$this->salida .= '<tr><td align="center" width="100%">';
		$accion=ModuloGetURL('app','CreacionAgenda','user','CreacionAgenda',array('volver2'=>'true'));
		$this->salida .='<form name="volver" action="'.$accion.'" method="post">';
		$this->salida .='<input type="submit" name="volver" value="VOLVER" class="input-submit">';
		$this->salida .='</form>';
		$this->salida .= '</td></tr>';
		$this->salida .= '</table>';
		$this->salida .= '  <table width="95%" align="center" border="0" class="Normal_10">';
		$this->salida .= '  <tr><td width="5%" class="modulo_list_claro">&nbsp;</td><td>Existencia de Turnos de Agenda M?dica</td></tr>';
		$this->salida .= '  </table>';
    $this->salida .= ThemeCerrarTabla();
    return true;
	}

	function CreacionAgendaNueva($tercero,$tipoid,$nombrep){

		$this->salida  = ThemeAbrirTabla('CREACI?N AGENDA M?DICA');
		SessionDelVar('FECHAS');
		SessionDelVar('CITASMES');
		$this->salida .='<script LANGUAGE="JavaScript">';
		$this->salida .='function mesesdias(h){';
		$this->salida .='   if(h.elements[9].checked==true){';
		$this->salida .='     for(var i=0 ; i < h.elements.length ; i++){';
		$this->salida .='       if(h.elements[i].disabled==false){';
		$this->salida .='         if((i!=9) && (i!=11) && (i!=12) && (i!=13)){';
		$this->salida .='           if((i<32)){';
		$this->salida .='             h.elements[i].checked=true;';
		$this->salida .='           }';
		$this->salida .='           if(i>43 && i<52){';
		$this->salida .='             h.elements[i].checked=true;';
		$this->salida .='           }';
		$this->salida .='           if(i>63 && i<65){';
		$this->salida .='             h.elements[i].checked=true;';
		$this->salida .='           }';
		$this->salida .='           if(i>76 && i<79){';
		$this->salida .='             h.elements[i].checked=true;';
		$this->salida .='           }';
		$this->salida .='           if(i>90 && i<95){';
		$this->salida .='             h.elements[i].checked=true;';
		$this->salida .='           }';
		$this->salida .='           if(i>106 && i<109){';
		$this->salida .='             h.elements[i].checked=true;';
		$this->salida .='           }';
		$this->salida .='           if(i>120){';
		$this->salida .='             h.elements[i].checked=true;';
		$this->salida .='           }';
		$this->salida .='         }';
		$this->salida .='       }';
		$this->salida .='     }';
		$this->salida .='   }else{';
		$this->salida .='     for (var i=0 ; i < h.elements.length ; i++){';
		$this->salida .='       if((i!=9) && (i!=11) && (i!=12) && (i!=13)){';
		$this->salida .='         if((i<32)){';
		$this->salida .='           h.elements[i].checked=false;';
		$this->salida .='         }';
		$this->salida .='         if(i>43 && i<52){';
		$this->salida .='           h.elements[i].checked=false;';
		$this->salida .='         }';
		$this->salida .='         if(i>63 && i<65){';
		$this->salida .='           h.elements[i].checked=false;';
		$this->salida .='         }';
		$this->salida .='         if(i>76 && i<79){';
		$this->salida .='           h.elements[i].checked=false;';
		$this->salida .='         }';
		$this->salida .='         if(i>90 && i<95){';
		$this->salida .='           h.elements[i].checked=false;';
		$this->salida .='         }';
		$this->salida .='         if(i>106 && i<109){';
		$this->salida .='           h.elements[i].checked=false;';
		$this->salida .='         }';
		$this->salida .='         if(i>120){';
		$this->salida .='           h.elements[i].checked=false;';
		$this->salida .='         }';
		$this->salida .='       }';
		$this->salida .='     }';
		$this->salida .='   }';
		$this->salida .='}'."\n";
		$this->salida .='function dias(h){';
		$this->salida .='   if(h.elements[10].checked==true){';
		$this->salida .='     for(var i=0 ; i < h.elements.length ; i++){';
		$this->salida .='       if(h.elements[i].disabled==false){';
		$this->salida .='         if((i!=10) && (i!=9) && (i!=11) && (i!=12) && (i!=13) && (i<32) && (i!=0) && (i!=14) && (i!=23) && (i!=32)){';
		$this->salida .='           h.elements[i].checked=true;';
		$this->salida .='         }';
		$this->salida .='         if((i>44) && (i<52)){';
		$this->salida .='           h.elements[i].checked=true;';
		$this->salida .='         }';
		$this->salida .='       }';
		$this->salida .='     }';
		$this->salida .='   }else{';
		$this->salida .='     for (var i=0 ; i < h.elements.length ; i++){';
		$this->salida .='       if((i!=10) && (i!=9) && (i!=11) && (i!=12) && (i!=13) && (i<32) && (i!=0) && (i!=14) && (i!=23) && (i!=32)){';
		$this->salida .='         h.elements[i].checked=false;';
		$this->salida .='       }';
		$this->salida .='       if((i>44) && (i<52)){';
		$this->salida .='         h.elements[i].checked=false;';
		$this->salida .='       }';
		$this->salida .='   }';
		$this->salida .=' }';
		$this->salida .='}'."\n";
		$this->salida .='function sabados(h){';
		$this->salida .=' if(h.elements[11].checked==true){';
		$this->salida .='   h.elements[124].disabled=true;';
		$this->salida .='   h.elements[124].checked=false;';
		$this->salida .=' }else{';
		$this->salida .='   h.elements[124].disabled=false;';
		$this->salida .='   h.elements[124].checked=false;';
		$this->salida .=' }';
		$this->salida .='}'."\n";
		$this->salida .='function domingos(h){';
		$this->salida .=' if(h.elements[12].checked==true){';
		$this->salida .='   h.elements[128].disabled=true;';
		$this->salida .='   h.elements[128].checked=false;';
		$this->salida .=' }else{';
		$this->salida .='   h.elements[128].disabled=false;';
		$this->salida .='   h.elements[128].checked=false;';
		$this->salida .=' }';
		$this->salida .='}'."\n";
		$this->salida .='</script>';
		$this->salida .='<br>';
		if($_REQUEST['a']>0){
			$a=date("Y",mktime(0,0,0,1,1,(date("Y")+$_REQUEST['a'])));
			$i=0;
		}else{
			$a=date("Y");
			$_REQUEST['a']=0;
			$i=date("n");
			if($i==12){
				$s=date("j");
				if($s==31){
					$s=0;
					$a++;
					$i=0;
				}
				$s--;
				$i--;
			}else{
				$i--;
			}
		}
		$this->Encabezado();
		$this->salida .= '<table width="70%" align="center" class="modulo_table_list">';
		$this->salida .= '<tr align="center" class="modulo_table_title">';
		$this->salida .= '<td align="center">Profesional</td>';
		$this->salida .= "</tr>";
		$this->salida .= '<tr class="modulo_list_oscuro">';
		$this->salida .= "<td align='center'>".$_SESSION['CreacionAgenda']['nombrep']."</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .='<br>';
		$this->salida .= '<form name="cosa" action="'.ModuloGetURL('app','CreacionAgenda','user','VerficarDatosAgenda',array("tercero"=>$tercero,"tipoid"=>$tipoid,"nombrep"=>$nombrep,'a'=>$_REQUEST['a'])).'" method="post">';
		$this->salida .= '<table width="70%" align="center">';
		$this->salida .= " <tr><td align=\"center\" colspan='22'>";
		$this->salida .=   $this->SetStyle("MensajeError");
		$this->salida .= " </td></tr>";
		$this->salida .= '</table>';
		$this->salida .= '<table width="90%" align="center" border="1" class="modulo_table">';
		$this->salida .= '<tr class="modulo_table_title"><td align="center" colspan="22">A?O:&nbsp&nbsp&nbsp&nbsp&nbsp;';
		foreach($_REQUEST  as $v=>$v1){
      if($v!='metodo' and $v!='modulo' and $v!='tipo' and $v!='SIIS_SID'){
        $vect[$v]=$v1;
				if($v!='a'){
				  $vect1[$v]=$v1;
				}
        $vect1['a']=$_REQUEST['a']-1;
				if($v!='a'){
				  $vect2[$v]=$v1;
				}
        $vect2['a']=$_REQUEST['a']+1;
			}
		}
		if($a<>date("Y")){
			$this->salida .= "<a href=".ModuloGetURL('app','CreacionAgenda','user','CreacionAgendaNueva',$vect).">actual</a>";
		}
		$this->salida .= '<a alt="anterior" href="'.ModuloGetURL('app','CreacionAgenda','user','CreacionAgendaNueva',$vect1).'"><<&nbsp&nbsp&nbsp;</a> '.$a.' <a alt="siguiente" href="'.ModuloGetURL('app','CreacionAgenda','user','CreacionAgendaNueva',$vect2).'">&nbsp&nbsp&nbsp;>></a>';
		$this->salida .= '</td></tr>';
		$this->salida .= '<tr class="modulo_table_title">';
		$this->salida .= '<td colspan="2" align="center">Meses</td>';
		$this->salida .= '<td colspan="8">D?as del Mes</td>';
		$this->salida .= '<td align="center" colspan="6">Todos los d?as</td>';
		$this->salida .= '<td align="center" colspan="6">Exclusiones</td>';
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
		if($_SESSION['mes']['1'] == $mes){
			if($i<=0){
				$this->salida .= 'checked="true">';
			}else{
				$this->salida .= ' disabled="true" checked="true">';
				$i--;
			}
		}else{
			if($i<=0){
				$this->salida .= '>';
			}else{
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
		if($_SESSION['dias']['1'] == $dias){
			if($s<=0){
				$this->salida .= 'checked="true"><br>1';
			}else{
				$this->salida .= ' disabled="true" checked="true"><br>1';
				$s--;
			}
		}else{
			if($s<=0){
				$this->salida .= '><br>1';
			}else{
				$this->salida .= ' disabled="true"><br>1';
				$s--;
			}
		}
		$dias++;
		$this->salida .= '</td>';
		$this->salida .= '<td align="center">';
		$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
		if ($_SESSION['dias']['2'] == $dias){
			if($s<=0){
				$this->salida .= 'checked="true"><br>2';
			}else{
				$this->salida .= ' disabled="true" checked="true"><br>2';
				$s--;
			}
		}else{
			if($s<=0){
				$this->salida .= '><br>2';
			}else{
				$this->salida .= ' disabled="true"><br>2';
				$s--;
			}
		}
		$dias++;
		$this->salida .= '</td>';
		$this->salida .= '<td align="center">';
		$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
		if ($_SESSION['dias']['3'] == $dias){
			if($s<=0){
				$this->salida .= 'checked="true"><br>3';
			}else{
				$this->salida .= ' disabled="true" checked="true"><br>3';
				$s--;
			}
		}else{
			if($s<=0){
				$this->salida .= '><br>3';
			}else{
				$this->salida .= ' disabled="true"><br>3';
				$s--;
			}
		}
		$dias++;
		$this->salida .= '</td>';
		$this->salida .= '<td align="center">';
		$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
		if ($_SESSION['dias']['4'] == $dias){
			if($s<=0){
				$this->salida .= 'checked="true"><br>4';
			}else{
				$this->salida .= ' disabled="true" checked="true"><br>4';
				$s--;
			}
		}else{
			if($s<=0){
				$this->salida .= '><br>4';
			}else{
				$this->salida .= ' disabled="true"><br>4';
				$s--;
			}
		}
		$dias++;
		$this->salida .= '</td>';
		$this->salida .= '<td align="center">';
		$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
		if ($_SESSION['dias']['5'] == $dias){
			if($s<=0){
				$this->salida .= 'checked="true"><br>5';
			}else{
				$this->salida .= ' disabled="true" checked="true"><br>5';
				$s--;
			}
		}else{
			if($s<=0){
				$this->salida .= '><br>5';
			}else{
				$this->salida .= ' disabled="true"><br>5';
				$s--;
			}
		}
		$dias++;							$this->salida .= '</td>';
		$this->salida .= '<td align="center">';
		$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
		if($_SESSION['dias']['6'] == $dias){
			if($s<=0){
				$this->salida .= 'checked="true"><br>6';
			}else{
				$this->salida .= ' disabled="true" checked="true"><br>6';
				$s--;
			}
		}else{
			if($s<=0){
				$this->salida .= '><br>6';
			}else{
				$this->salida .= ' disabled="true"><br>6';
				$s--;
			}
		}
		$dias++;
		$this->salida .= '</td>';
		$this->salida .= '<td align="center">';
		$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
		if($_SESSION['dias']['7'] == $dias){
			if($s<=0){
				$this->salida .= 'checked="true"><br>7';
			}else{
				$this->salida .= ' disabled="true" checked="true"><br>7';
				$s--;
			}
		}else{
			if($s<=0){
				$this->salida .= '><br>7';
			}else{
				$this->salida .= ' disabled="true"><br>7';
				$s--;
			}
		}
		$dias++;
		$this->salida .= '</td>';
		$this->salida .= '<td align="center">';
		$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
		if ($_SESSION['dias']['8'] == $dias){
			if($s<=0){
				$this->salida .= 'checked="true"><br>8';
			}else{
				$this->salida .= ' disabled="true" checked="true"><br>8';
				$s--;
			}
    }else{
			if($s<=0){
				$this->salida .= '><br>8';
			}else{
				$this->salida .= ' disabled="true"><br>8';
				$s--;
			}
		}
		$dias++;
		$this->salida .= '</td>';
		$this->salida .= '<td colspan="4">';
		if(!empty($_SESSION['semana']['todos']))
			$this->salida .= '<input type="checkbox" name="todos" onclick="mesesdias(this.form)" checked="true">Meses y D?as';
		else
			$this->salida .= '<input type="checkbox" name="todos" onclick="mesesdias(this.form)">Meses y D?as';
		$this->salida .= '</td>';
		$this->salida .= '<td colspan="2">';
		if (!empty($_SESSION['semana']['todosd']))
			$this->salida .= '<input type="checkbox" name="todosd" onclick="dias(this.form)" checked="true">D?as';
		else
			$this->salida .= '<input type="checkbox" name="todosd" onclick="dias(this.form)">D?as';
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
			if($i<=0){
				$this->salida .= 'checked="true">';
			}else{
				$this->salida .= ' disabled="true" checked="true">';
				$i--;
			}
		}else{
			if($i<=0){
				$this->salida .= '>';
			}else{
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
			if($s<=0){
				$this->salida .= 'checked="true"><br>9';
			}else{
				$this->salida .= ' disabled="true" checked="true"><br>9';
				$s--;
			}
		}else{
			if($s<=0){
				$this->salida .= '><br>9';
			}else{
				$this->salida .= ' disabled="true"><br>9';
				$s--;
			}
		}
		$dias++;
		$this->salida .= '</td>';
		$this->salida .= '<td align="center">';
		$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
		if ($_SESSION['dias']['10'] == $dias){
			if($s<=0){
				$this->salida .= 'checked="true"><br>10';
			}else{
				$this->salida .= ' disabled="true" checked="true"><br>10';
				$s--;
			}
		}else{
		  if($s<=0){
			  $this->salida .= '><br>10';
			}else{
				$this->salida .= ' disabled="true"><br>10';
				$s--;
			}
    }
		$dias++;
		$this->salida .= '</td>';
		$this->salida .= '<td align="center">';
		$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
		if($_SESSION['dias']['11'] == $dias){
			if($s<=0){
				$this->salida .= 'checked="true"><br>11';
			}else{
				$this->salida .= ' disabled="true" checked="true"><br>11';
				$s--;
			}
		}else{
			if($s<=0){
				$this->salida .= '><br>11';
			}else{
				$this->salida .= ' disabled="true"><br>11';
				$s--;
			}
		}
		$dias++;
		$this->salida .= '</td>';
		$this->salida .= '<td align="center">';
		$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
		if($_SESSION['dias']['12'] == $dias){
			if($s<=0){
				$this->salida .= 'checked="true"><br>12';
			}else{
				$this->salida .= ' disabled="true" checked="true"><br>12';
				$s--;
			}
    }else{
			if($s<=0){
				$this->salida .= '><br>12';
			}else{
				$this->salida .= ' disabled="true"><br>12';
				$s--;
			}
		}
		$dias++;
		$this->salida .= '</td>';
		$this->salida .= '<td align="center">';
		$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
		if ($_SESSION['dias']['13'] == $dias){
			if($s<=0){
				$this->salida .= 'checked="true"><br>13';
			}else{
				$this->salida .= ' disabled="true" checked="true"><br>13';
				$s--;
			}
		}else{
			if($s<=0){
				$this->salida .= '><br>13';
			}else{
				$this->salida .= ' disabled="true"><br>13';
				$s--;
			}
		}
		$dias++;
		$this->salida .= '</td>';
		$this->salida .= '<td align="center">';
		$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
		if($_SESSION['dias']['14'] == $dias){
			if($s<=0){
				$this->salida .= 'checked="true"><br>14';
			}else{
				$this->salida .= ' disabled="true" checked="true"><br>14';
				$s--;
			}
		}else{
			if($s<=0){
				$this->salida .= '><br>14';
			}else{
				$this->salida .= ' disabled="true"><br>14';
				$s--;
			}
    }
		$dias++;
		$this->salida .= '</td>';
		$this->salida .= '<td align="center">';
		$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
    if ($_SESSION['dias']['15'] == $dias){
			if($s<=0){
				$this->salida .= 'checked="true"><br>15';
			}else{
				$this->salida .= ' disabled="true" checked="true"><br>15';
				$s--;
			}
    }else{
			if($s<=0){
				$this->salida .= '><br>15';
			}else{
				$this->salida .= ' disabled="true"><br>15';
				$s--;
			}
		}
		$dias++;
		$this->salida .= '</td>';
		$this->salida .= '<td align="center">';
		$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
		if ($_SESSION['dias']['16'] == $dias){
			if($s<=0){
				$this->salida .= 'checked="true"><br>16';
			}else{
				$this->salida .= ' disabled="true" checked="true"><br>16';
				$s--;
			}
    }else{
			if($s<=0){
				$this->salida .= '><br>16';
			}else{
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
			if($i<=0){
				$this->salida .= 'checked="true">';
			}else{
				$this->salida .= ' disabled="true" checked="true">';
				$i--;
			}
    }else{
			if($i<=0){
				$this->salida .= '>';
			}else{
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
		  if($s<=0){
				$this->salida .= 'checked="true"><br>17';
			}else{
				$this->salida .= ' disabled="true" checked="true"><br>17';
				$s--;
			}
	  }else{
			if($s<=0){
				$this->salida .= '><br>17';
			}else{
				$this->salida .= ' disabled="true"><br>17';
				$s--;
			}
		}
		$dias++;
		$this->salida .= '</td>';
		$this->salida .= '<td align="center">';
		$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
		if ($_SESSION['dias']['18'] == $dias){
			if($s<=0){
				$this->salida .= 'checked="true"><br>18';
			}else{
				$this->salida .= ' disabled="true" checked="true"><br>18';
				$s--;
			}
		}else{
			if($s<=0){
				$this->salida .= '><br>18';
			}else{
				$this->salida .= ' disabled="true"><br>18';
				$s--;
			}
    }
		$dias++;
		$this->salida .= '</td>';
		$this->salida .= '<td align="center">';
		$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
	  if ($_SESSION['dias']['19'] == $dias){
			if($s<=0){
				$this->salida .= 'checked="true"><br>19';
			}else{
				$this->salida .= ' disabled="true" checked="true"><br>19';
				$s--;
			}
		}else{
			if($s<=0){
				$this->salida .= '><br>19';
			}else{
				$this->salida .= ' disabled="true"><br>19';
				$s--;
			}
    }
		$dias++;
		$this->salida .= '</td>';
		$this->salida .= '<td align="center">';
		$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
		if($_SESSION['dias']['20'] == $dias){
			if($s<=0){
				$this->salida .= 'checked="true"><br>20';
			}else{
				$this->salida .= ' disabled="true" checked="true"><br>20';
				$s--;
			}
		}else{
			if($s<=0){
				$this->salida .= '><br>20';
			}else{
				$this->salida .= ' disabled="true"><br>20';
				$s--;
			}
		}
		$dias++;
		$this->salida .= '</td>';
		$this->salida .= '<td align="center">';
		$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
		if ($_SESSION['dias']['21'] == $dias){
			if($s<=0){
				$this->salida .= 'checked="true"><br>21';
			}else{
				$this->salida .= ' disabled="true" checked="true"><br>21';
				$s--;
			}
		}else{
			if($s<=0){
				$this->salida .= '><br>21';
			}else{
				$this->salida .= ' disabled="true"><br>21';
				$s--;
			}
		}
		$dias++;
		$this->salida .= '</td>';
		$this->salida .= '<td align="center">';
		$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
		if($_SESSION['dias']['22'] == $dias){
			if($s<=0){
				$this->salida .= 'checked="true"><br>22';
			}else{
				$this->salida .= ' disabled="true" checked="true"><br>22';
				$s--;
			}
		}else{
			if($s<=0){
				$this->salida .= '><br>22';
			}else{
				$this->salida .= ' disabled="true"><br>22';
				$s--;
			}
		}
		$dias++;
		$this->salida .= '</td>';
		$this->salida .= '<td align="center">';
		$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
		if($_SESSION['dias']['23'] == $dias){
			if($s<=0){
				$this->salida .= 'checked="true"><br>23';
			}else{
				$this->salida .= ' disabled="true" checked="true"><br>23';
				$s--;
			}
		}else{
			if($s<=0){
				$this->salida .= '><br>23';
			}else{
				$this->salida .= ' disabled="true"><br>23';
				$s--;
			}
    }
		$dias++;
		$this->salida .= '</td>';
		$this->salida .= '<td align="center">';
		$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
		if ($_SESSION['dias']['24'] == $dias){
			if($s<=0){
				$this->salida .= 'checked="true"><br>24';
			}else{
				$this->salida .= ' disabled="true" checked="true"><br>24';
				$s--;
			}
		}else{
			if($s<=0){
				$this->salida .= '><br>24';
			}else{
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
			if($i<=0){
				$this->salida .= 'checked="true">';
			}else{
				$this->salida .= ' disabled="true" checked="true">';
				$i--;
			}
		}else{
			if($i<=0){
				$this->salida .= '>';
			}else{
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
			if($s<=0){
				$this->salida .= 'checked="true"><br>25';
			}else{
				$this->salida .= ' disabled="true" checked="true"><br>25';
				$s--;
		  }
		}else{
			if($s<=0){
				$this->salida .= '><br>25';
			}else{
				$this->salida .= ' disabled="true"><br>25';
				$s--;
			}
		}
		$dias++;
		$this->salida .= '</td>';
		$this->salida .= '<td align="center">';
		$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
		if ($_SESSION['dias']['26'] == $dias){
			if($s<=0){
				$this->salida .= 'checked="true"><br>26';
			}else{
				$this->salida .= ' disabled="true" checked="true"><br>26';
				$s--;
			}
    }else{
			if($s<=0){
				$this->salida .= '><br>26';
			}else{
				$this->salida .= ' disabled="true"><br>26';
				$s--;
			}
    }
		$dias++;
		$this->salida .= '</td>';
		$this->salida .= '<td align="center">';
		$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
		if ($_SESSION['dias']['27'] == $dias){
			if($s<=0){
				$this->salida .= 'checked="true"><br>27';
			}else{
				$this->salida .= ' disabled="true" checked="true"><br>27';
				$s--;
			}
    }else{
			if($s<=0){
				$this->salida .= '><br>27';
			}else{
				$this->salida .= ' disabled="true"><br>27';
				$s--;
			}
    }
		$dias++;
		$this->salida .= '</td>';
		$this->salida .= '<td align="center">';
		$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
		if ($_SESSION['dias']['28'] == $dias){
			if($s<=0){
				$this->salida .= 'checked="true"><br>28';
			}else{
				$this->salida .= ' disabled="true" checked="true"><br>28';
				$s--;
			}
    }else{
			if($s<=0){
				$this->salida .= '><br>28';
			}else{
				$this->salida .= ' disabled="true"><br>28';
				$s--;
			}
		}
		$dias++;
		$this->salida .= '</td>';
		$this->salida .= '<td align="center">';
		$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
		if ($_SESSION['dias']['29'] == $dias){
			if($s<=0){
				$this->salida .= 'checked="true"><br>29';
			}else{
				$this->salida .= ' disabled="true" checked="true"><br>29';
				$s--;
			}
		}else{
			if($s<=0){
				$this->salida .= '><br>29';
			}else{
				$this->salida .= ' disabled="true"><br>29';
				$s--;
			}
		}
		$dias++;
		$this->salida .= '</td>';
		$this->salida .= '<td align="center">';
		$this->salida .= '<input type="checkbox" name="dias'.$dias.'" value="'.$dias.'"';
		if ($_SESSION['dias']['30'] == $dias){
			if($s<=0){
				$this->salida .= 'checked="true"><br>30';
			}else{
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
			if($s<=0){
				$this->salida .= 'checked="true"><br>31';
			}else{
				$this->salida .= ' disabled="true" checked="true"><br>31';
				$s--;
			}
		}else{
			if($s<=0){
				$this->salida .= '><br>31';
			}else{
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
			if($i<=0){
				$this->salida .= 'checked="true">';
			}else{
				$this->salida .= ' disabled="true" checked="true">';
				$i--;
			}
		}else{
			if($i<=0){
				$this->salida .= '>';
			}else{
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
		$this->salida .= 'D?as de la Semana';
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
			if($i<=0){
				$this->salida .= 'checked="true">';
			}else{
				$this->salida .= ' disabled="true" checked="true">';
				$i--;
			}
		}else{
			if($i<=0){
				$this->salida .= '>';
			}else{
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
			if($i<=0){
				$this->salida .= 'checked="true">';
			}else{
				$this->salida .= ' disabled="true" checked="true">';
				$i--;
			}
		}else{
			if($i<=0){
				$this->salida .= '>';
			}else{
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
			if($i<=0){
				$this->salida .= 'checked="true">';
			}else{
				$this->salida .= ' disabled="true" checked="true">';
				$i--;
			}
    }else{
			if($i<=0){
				$this->salida .= '>';
			}else{
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
		if($_SESSION['semana']['2']=="mi?")
			$this->salida .= '<input type="checkbox" name="semana'.$semana.'" value="mi?" checked="true">';
		else
			$this->salida .= '<input type="checkbox" name="semana'.$semana.'" value="mi?">';
		$semana++;
		$this->salida .= '</td>';
		$this->salida .= '<td colspan="7">';
		$this->salida .= 'Mi?rcoles';
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
			if($i<=0){
				$this->salida .= 'checked="true">';
			}else{
				$this->salida .= ' disabled="true" checked="true">';
				$i--;
			}
		}else{
			if($i<=0){
				$this->salida .= '>';
			}else{
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
			if($i<=0){
				$this->salida .= 'checked="true">';
			}else{
				$this->salida .= ' disabled="true" checked="true">';
				$i--;
			}
    }else{
			if($i<=0){
				$this->salida .= '>';
			}else{
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
			if($i<=0){
				$this->salida .= 'checked="true">';
			}else{
				$this->salida .= ' disabled="true" checked="true">';
				$i--;
			}
		}else{
			if($i<=0){
				$this->salida .= '>';
			}else{
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
		if($_SESSION['semana']['5']=="s?b")
			$this->salida .= '<input type="checkbox" name="semana'.$semana.'" value="s?b" checked="true">';
		else
			$this->salida .= '<input type="checkbox" name="semana'.$semana.'" value="s?b">';
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
		  if($tipocita[0][$j]==$_REQUEST['consultorio']){
		  $this->salida .='<option value='.$tipocita[0][$j].' selected>'.$tipocita[1][$j].'</option>';
			}else{
			$this->salida .='<option value='.$tipocita[0][$j].'>'.$tipocita[1][$j].'</option>';
			}
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
		  if($tipocita[0][$j]==$_REQUEST['tiporegistro']){
		  $this->salida .='<option value='.$tipocita[0][$j].' selected>'.$tipocita[1][$j].'</option>';
			}else{
			$this->salida .='<option value='.$tipocita[0][$j].'>'.$tipocita[1][$j].'</option>';
			}
			$j++;
		}
		$this->salida .='</select>';
		$this->salida .= '</td>';
		$this->salida .= '</tr>';
		$this->salida .= '<tr>';
		$this->salida .= '<td>';
		$this->salida .= '<input type="checkbox" name="mes'.$mes.'" value="'.$mes.'"';
		if ($_SESSION['mes']['12']==$mes){
			if($i<=0){
				$this->salida .= 'checked="true">';
			}else{
				$this->salida .= ' disabled="true" checked="true">';
				$i--;
			}
		}else{
			if($i<=0){
				$this->salida .= '>';
			}else{
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
		while($j<sizeof($intervalo)){
		  if($intervalo[$j]==$_REQUEST['interval']){
		  $this->salida .=' <option value='.$intervalo[$j].' selected>'.$intervalo[$j].'</option>';
			}else{
			$this->salida .='<option value='.$intervalo[$j].'>'.$intervalo[$j].'</option>';
			}
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
		while($j<sizeof($pacientes)){
		  if($pacientes[$j]==$_REQUEST['pacientes']){
        $this->salida .=' <option value='.$pacientes[$j].' selected>'.$pacientes[$j].'</option>';
			}else{
			  $this->salida .='<option value='.$pacientes[$j].'>'.$pacientes[$j].'</option>';
			}
			$j++;
		}
		$this->salida .='</select>';
		$this->salida .= '</td>';
		$this->salida .= '</tr>';
		$this->salida .= '</table>';
		$this->salida .= '<br>';
		$this->salida .= '<table width="90%" align="center" border="0">';
		$this->salida .= '<tr>';
		$this->salida .= '<td align="right">';
		$this->salida .= '<input type="submit" name="Enviar" value="ENVIAR" class="input-submit">';
		$this->salida .= '</form>';
		$this->salida .= '</td>';
		$this->salida .= '<td align="left">';
		$accion=ModuloGetURL('app', 'CreacionAgenda', 'user', 'CreacionAgenda',array('volver2'=>'true'));
		$this->salida .= '<form name="volver" action="'.$accion.'" method="post">';
		$this->salida .= '<input type="submit" name="volver" value="VOLVER" class="input-submit">';
		$this->salida .= '</td>';
		$this->salida .= '</form>';
		$this->salida .= '</tr>';
		$this->salida .= '</table>';
		$this->salida .= '<br>';
		$this->salida .= '<br>';
    //MODI INI
    $this->clear();
    //MODI FIN
    $this->salida .= ThemeCerrarTabla();
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

	  $this->salida  = ThemeAbrirTabla('CREACI?N AGENDA M?DICA');
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
		$this->Encabezado();
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
		$this->salida .= "        <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "        <tr><td><fieldset><legend class=\"field\">AGENDA MENSUAL</legend>";
		$this->salida .= "          <table border=\"0\" width=\"95%\" align=\"center\">";
		$this->salida .= "				  <tr class=\"modulo_list_claro\"><td class=\"label\">A?O</td><td><select name=\"year\" onchange=\"year1(this.form)\" class=\"select\">";
		if(empty($_REQUEST['year'])){
			$this->AnosAgenda(True,date("Y")+$_REQUEST['a']);
			$year=date("Y")+$_REQUEST['a'];
		}else{
			$this->AnosAgenda(True,$_REQUEST['year']);
			$year=$_REQUEST['year'];
		}
		$this->salida .= "</select></td></tr>";
		$this->salida .= "<tr class=\"modulo_list_claro\"><td class=\"label\">MES</td><td><select name=\"mes\" onchange=\"year1(this.form)\" class=\"select\">";
		if(empty($_REQUEST['meses'])){
			$i=1;
			while($i<13){
				$m='mes';
				$m.=$i;
				if(!empty($_REQUEST[$m])){
					break;
				}
				$i++;
			}
			$this->MesesAgenda(True,$year,$_REQUEST[$m]);
			$mes=$_REQUEST[$m];
		}else{
			$this->MesesAgenda(True,$year,$_REQUEST['meses']);
			$mes=$_REQUEST['meses'];
		}
		$this->salida .= "          </select></td></tr>";
		$this->salida .= "          <tr><td  align=\"center\" colspan=\"4\"></td></tr>";
		$this->salida .= "			     </table>";
		$this->salida .= "		     </fieldset></td></tr></table><BR>";
		$this->salida .= "      </form>";

		$this->ReturnMetodoExterno('app', 'Agenda', 'user', 'Calendario', array('year'=>$year,'meses'=>$mes));
		foreach($_REQUEST as $v=>$v1){
			if($v!='year' and $v!='meses' and $v!='Enviar' and $v!='metodo' and $v!='modulo' and $v!='tipo' and $v!='SIIS_SID'){
				$vec[$v]=$v1;
			}
		}
		foreach($_REQUEST AS $v=>$v1){
			if($v=='mes1'){
				$vec1=$vec;
				unset($vec1['1mes']);
			}
			if($v=='mes2'){
				$vec1=$vec;
				unset($vec1['2mes']);
			}
			if($v=='mes3'){
				$vec1=$vec;
				unset($vec1['3mes']);
			}
			if($v=='mes4'){
				$vec1=$vec;
				unset($vec1['4mes']);
			}
			if($v=='mes5'){
				$vec1=$vec;
				unset($vec1['5mes']);
			}
			if($v=='mes6'){
				$vec1=$vec;
				unset($vec1['6mes']);
			}
			if($v=='mes7'){
				$vec1=$vec;
				unset($vec1['7mes']);
			}
			if($v=='mes8'){
				$vec1=$vec;
				unset($vec1['8mes']);
			}
			if($v=='mes9'){
				$vec1=$vec;
				unset($vec1['9mes']);
			}
			if($v=='mes10'){
				$vec1=$vec;
				unset($vec1['10mes']);
			}
			if($v=='mes11'){
				$vec1=$vec;
				unset($vec1['11mes']);
			}
			if($v=='mes12'){
				$vec1=$vec;
				unset($vec1['12mes']);
			}
		}
    $this->Asignar();
		$accion=ModuloGetURL('app', 'CreacionAgenda', 'user', 'CreacionAgendaNueva', $vec1);
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
		$this->salida .= ThemeCerrarTabla();
	}

	function ConfirmarDatosAgenda(){

		$this->salida  = ThemeAbrirTabla('CREACI?N AGENDA M?DICA');
		$this->salida .= "<SCRIPT>";
		$this->salida .= "function chequeoTotal(frm,x,valor){";
    $this->salida .= "  if(x==true){";
    $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
    $this->salida .= "      if(frm.elements[i].type=='checkbox' && frm.elements[i].name=='seleccion[]'){";
    $this->salida .= "        frm.elements[i].checked=true";
		$this->salida .= "      }";
		$this->salida .= "    }";
    $this->salida .= "  }else{";
    $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
    $this->salida .= "      if(frm.elements[i].type=='checkbox' && frm.elements[i].name=='seleccion[]'){";
    $this->salida .= "        frm.elements[i].checked=false";
		$this->salida .= "      }";
		$this->salida .= "    }";
		$this->salida .= "  }";
    $this->salida .= "}";
		$this->salida .= "</SCRIPT>";
    $this->Encabezado();
		foreach($_REQUEST as $v=>$v1){
			if($v!='metodo' and $v!='modulo' and $v!='tipo' and $v!='SIIS_SID'){
				$vec[$v]=$v1;
			}
		}
		$accion=moduloGetURL('app','CreacionAgenda','user','GuardarDatos',$vec);
		$this->salida .= '<form name="forma" action="'.$accion.'" method="post">';
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
		$this->salida .= "</table><BR>";
    $fechas=$_SESSION['FECHAS'];
		for($i=0;$i<sizeof($fechas);$i++){
      (list($fecha,$hora)=explode(' ',$fechas[$i]));
      (list($ano,$mes,$dia)=explode('-',$fecha));
			$vector[$ano][$mes][$dia][$hora]=1;
		}
		//print_R($fechas);
		if($vector){
		  $this->salida .= '<table border="0" width="70%" align="center">';
			$this->salida .= " <tr><td align=\"center\">";
			$this->salida .=   $this->SetStyle("MensajeError");
			$this->salida .= " </td></tr>";
      $this->salida .= '<tr align="center" class="modulo_table_title">';
      $this->salida .= '<td width="16%" nowrap>A?O</td>';
			$this->salida .= '<td width="17%" nowrap>MES</td>';
			$this->salida .= '<td width="20%" nowrap>D?A</td>';
			$this->salida .= '<td width="17%" nowrap>HORA INICIO</td>';
			$this->salida .= '<td width="16%" nowrap>HORA FIN</td>';
			$this->salida .= "<td align=\"center\"><input type=\"checkbox\" name=\"todoselect\" onclick=\"chequeoTotal(this.form,this.checked,this.value)\"></td>";
      $this->salida .= '</tr>';
      $this->salida .= '<tr><td class="modulo_list_oscuro" width="100%" colspan="6">';
		  $this->salida .= '<table width="100%" align="center">';
			foreach($vector as $ano=>$vec1){
				$this->salida .= '<tr class="modulo_list_oscuro">';
				$this->salida .= "<td width=\"15%\" nowrap align='center'>$ano</td>";
        $this->salida .= "<td>";
				$this->salida .= '<table width="100%" align="center">';
				foreach($vec1 as $mes=>$vec2){
					$this->salida .= '<tr class="modulo_list_claro">';
					$this->salida .= "<td width=\"20%\" nowrap align='center'>$mes</td>";
					$this->salida .= "<td>";
					$this->salida .= '<table width="100%" align="center">';
          foreach($vec2 as $dia=>$vec3){
						$this->salida .= '<tr class="modulo_list_oscuro">';
						$this->salida .= "<td width=\"30%\"nowrap  align='center'>$dia</td>";
						$this->salida .= "<td>";
						$this->salida .= '<table width="95%" align="center">';
						$this->salida .= '<tr class="modulo_list_claro">';
						$i=0;
						$vectorFechas='';
            foreach($vec3 as $hora=>$valor){
						  if($i==0){
							  $this->salida .= "<td align='center'>$hora</td>";
								$horaIni=$hora;
								$vectorFechas=$ano.'-'.$mes.'-'.$dia.' '.$hora;
							}elseif(($i+1)==sizeof($vec3)){
                $this->salida .= "<td align='center'>$hora</td>";
							  $vectorFechas.='||//'.$ano.'-'.$mes.'-'.$dia.' '.$hora;
								$this->salida .= "<td align=\"center\"><input type=\"checkbox\" name=\"seleccion[]\" value=\"$vectorFechas\"></td>";
							}else{
                $vectorFechas.='||//'.$ano.'-'.$mes.'-'.$dia.' '.$hora;
							}
							$i++;
				    }
						$this->salida .= '</tr>';
						$this->salida .= '</table>';
						$this->salida .= "</td>";
					  $this->salida .= '</tr>';
				  }
					$this->salida .= '</table>';
					$this->salida .= "</td>";
					$this->salida .= '</tr>';
				}
				$this->salida .= '</table>';
				$this->salida .= "</td>";
				$this->salida .= '</tr>';
			}
			$this->salida .= "</table>";
			$this->salida .= '</td></tr>';
			$this->salida .= "</table>";
			$this->salida .= '<table border="0" align="center" width="70%">';
			$this->salida .= "<tr><td align=\"right\"><input type=\"submit\" name=\"Crear\" value=\"CREAR TURNOS\" class=\"input-submit\"></td></tr>";
			$this->salida .= "</table><BR>";
		}
		$this->salida .= '</form>';
    $accion=moduloGetURL('app','CreacionAgenda','user','CreacionAgendaNueva',$vec);
		$this->salida .= '<form name="forma" action="'.$accion.'" method="post">';
		$this->salida .= '<table width="95%" align="center">';
		$this->salida .= "<tr><td align=\"center\"><input type=\"submit\" name=\"Salir\" value=\"SALIR\" class=\"input-submit\"></td></tr>";
    $this->salida .= "</table>";
		$this->salida .= '</form>';
		$this->salida .= ThemeCerrarTabla();
	}

	function LlamaAgendaRetornoExterno($accion,$intervalo,$nombrep,$Fecha){

		$this->salida  = ThemeAbrirTabla('CONSULTA AGENDA M?DICA');
		$this->salida .= '<br>';
		$this->Encabezado();
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
		$this->salida .= '<BR><table width="80%" align="center">';
		(list($ano,$mes,$dia)=explode('-',$Fecha));
		$totFecha=mktime(0,0,0,$mes,$dia,$ano);
		$this->salida .= "<tr class=\"modulo_table_title\"><td>".strftime("%d de  %B de %Y",$totFecha)."</td></tr>";
		$this->salida .= "<tr class=\"modulo_list_claro\"><td>";
		$this->ReturnMetodoExterno('app', 'Agenda', 'user', 'Dia',array('intervalo'=>$intervalo,'opciones'=>1));
		$this->salida .= "</td></tr>";
		$this->salida .= "</table>";
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
		$this->salida .= '  <table width="80%" align="center" border="0" class="Normal_10">';
		$this->salida .= '  <tr><td width="5%" class="modulo_table_calen">&nbsp;</td><td>Turno de Agenda M?dica Creado</td></tr>';
		$this->salida .= '  </table>';
		$this->salida .= ThemeCerrarTabla();
		return true;
	}





/**
	* Muestra los años en los que se puede buscar la agenda medica
	* @access private
	* @param boolean si ya esta seleccionado
	* @param string año señalado
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
	* @param string año señalado
	* @param string mes por defecto
	*/



	function MesesAgenda($Seleccionado='False',$A?o,$Defecto)
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
			  if($anoActual==$A?o)
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
			  if($anoActual==$A?o)
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

	function EncabezadoModificacion(){


		$this->salida .= '<table width="70%" align="center" class="modulo_table_list">';
		$this->salida .= '<tr>';
		$this->salida .= '  <td colspan="4" align="center" class="modulo_table_list_title">';
		$this->salida .=    'EMPRESA:&nbsp&nbsp&nbsp&nbsp;'.$_SESSION['BorrarAgenda']['nomemp'];
		$this->salida .= "  </td>";
		$this->salida .= "</tr>";
		$this->salida .= "<tr>";
		$this->salida .= '  <td align="center" class="modulo_table_list_title">';
		$this->salida .= "  Centro Utilidad";
		$this->salida .= "  </td>";
		$this->salida .= '  <td class="modulo_list_claro">';
		$this->salida .=    $_SESSION['BorrarAgenda']['nomcu'];
		$this->salida .= "  </td>";
		$this->salida .= '  <td align="center" class="modulo_table_list_title">';
		$this->salida .= "  Unidad Funcional";
		$this->salida .= "  </td>";
    $this->salida .= '  <td class="modulo_list_claro">';
		$this->salida .=    $_SESSION['BorrarAgenda']['nomuf'];
		$this->salida .= "  </td>";
		$this->salida .= "</tr>";
		$this->salida .= "<tr>";
		$this->salida .= '  <td align="center" class="modulo_table_list_title">';
		$this->salida .= "  Departamento";
		$this->salida .= "  </td>";
    $this->salida .= '  <td class="modulo_list_claro">';
		$this->salida .=    $_SESSION['BorrarAgenda']['nomdep'];
		$this->salida .= "  </td>";
		$this->salida .= '  <td class="modulo_table_list_title">';
		$this->salida .= "  Tipo Consulta";
		$this->salida .= "  </td>";
    $this->salida .= '  <td class="modulo_list_claro">';
		$this->salida .=    $_SESSION['BorrarAgenda']['nombre'];
		$this->salida .= "  </td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .= '<BR>';
		return true;
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
			$_SESSION['BorrarAgenda']['nomcu']=$_REQUEST['Citas']['descripcion2'];
			$_SESSION['BorrarAgenda']['nomuf']=$_REQUEST['Citas']['descripcion3'];
			$_SESSION['BorrarAgenda']['nomdep']=$_REQUEST['Citas']['descripcion4'];
			$_SESSION['BorrarAgenda']['nombre']=$_REQUEST['Citas']['descripcion5'];
			$_SESSION['BorrarAgenda']['empresa']=$_REQUEST['Citas']['empresa_id'];
			$_SESSION['BorrarAgenda']['departamento']=$_REQUEST['Citas']['departamento'];
		}

		$this->salida=ThemeAbrirTabla('MODIFICACION AGENDA MEDICA');
		$this->EncabezadoModificacion();
		$profesionales=$this->Profesionales2();
		if($profesionales)
		{
			$this->salida .= '<table align="center" width="50%" border="0" class="modulo_table_list">';
			$this->salida .= '<tr align="center" class="modulo_table_title">';
			$this->salida .= '<td align="center">';
			$this->salida .= 'Profesionales';
			$this->salida .= '</td>';
			$this->salida .= '</tr>';
			$i=0;
			$backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
      $this->salida.="<tr  class='$estilo' align='center'  onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>";

			while($i<sizeof($profesionales[0]))
			{
				if($spy==0){$estilo='modulo_list_claro';$spy=1;}else{$estilo='modulo_list_oscuro';$spy=0;}
				$this->salida.="  <tr  class='$estilo' align='center'>";
				$this->salida .= '<td>';
				$this->salida .= "<a href=".ModuloGetURL('app','CreacionAgenda','user','ListadoAgendaMesTurnos',array('tercero'=>$profesionales[1][$i],'tipoid'=>$profesionales[0][$i],'nombrep'=>$profesionales[2][$i])).">".$profesionales[2][$i]."</a>";
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
		}
		$this->salida .= ThemeCerrarTabla();
		return true;
	}



/**
* Muestra el listado de las agendas que tenga un profesional.
*
* @access private
* @return boolean Para identificar que se realizo.
*/


	function ListadoAgendaMesTurnos()
	{
	  if(empty($_SESSION['BorrarAgenda']['DatosProf']['tercero'])){
			$_SESSION['BorrarAgenda']['DatosProf']['nombrep']=$_REQUEST['nombrep'];
			$_SESSION['BorrarAgenda']['DatosProf']['tercero']=$_REQUEST['tercero'];
			$_SESSION['BorrarAgenda']['DatosProf']['tipoid']=$_REQUEST['tipoid'];
		}
	  $this->salida=ThemeAbrirTabla('MODIFICACION AGENDA MEDICA');
		$this->salida .="<script language='javascript'>";
		$this->salida .="function mOvr(src,clrOver) {;";
		$this->salida .="src.style.background = clrOver;";
		$this->salida .="}\n";
		$this->salida .="function mOut(src,clrIn) {\n";
		$this->salida .="src.style.background = clrIn;\n";
		$this->salida .="}\n";
		$this->salida .="</script>\n";
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
		unset($_SESSION['BorrarAgenda']['datos']);
		$turnos=$this->ListadoTurnosMes();
		if($turnos){
			$s=0;
			foreach($_REQUEST as $v=>$datos){
				if(substr_count ($v,'seleccion')==1){
					$s=1;
					break;
				}
			}
			if($s==1){
				$this->salida .= '<table width="70%" align="center">';
				$this->salida .= '<tr align="center">';
				$this->salida .= '<td align="center">';
				$this->salida .= '<label class="label_error">NO SE PUEDE BORRAR LA AGENDA POR TENER CITAS ASIGNADOS</label>';
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
				$this->salida .= "</table>";
				$this->salida .= '<br>';
			}
			foreach($_REQUEST as $v=>$datos){
				if($v!='modulo' and $v!='metodo' and $v!='tipo' and $v!='SIIS_SID' and $v!='DiaEspe' and substr_count ($v,'seleccion')!=1){
					$vec[$v]=$datos;
				}
			}
			$accion=ModuloGetURL('app','CreacionAgenda','user','BorrarAgenda',$vec);
			$this->salida.='<form name="siguiente" method="post" action="'.$accion.'">';
			$this->salida .= '<table width="60%" align="center" class="modulo_table_list">';
			$this->salida .= '<tr align="center" class="modulo_table_title">';
			$this->salida .= '<td align="center">';
			$this->salida .= "Selecci?n";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "Fecha";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "&nbsp;";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center" width="15%">';
			$this->salida .= "&nbsp;";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$i=0;
			$backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
			while($i<sizeof($turnos[0])){
				$vec['TurnoAgenda']='';
				$a=array_keys($turnos[0],$turnos[0][$i]);
				if(sizeof($a)==1){
					if($spy==0){$estilo='modulo_list_claro';$spy=1;}else{$estilo='modulo_list_oscuro';$spy=0;}
				  $this->salida.="<tr class='$estilo' align='center'  onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>";
					$this->salida.="    <td width=\"5%\" align=\"center\">";
					$this->salida .='     <input type="checkbox" name="seleccion'.$i.'" value="'.$turnos[1][$i].'" class="input-submit">';
          $this->salida .='   </td>';
					$vec['DiaEspe']=$turnos[0][$i];
					$vec['TurnoAgenda']=$turnos[1][$i];
					$accion=ModuloGetURL('app','CreacionAgenda','user','ListadoDiaAgenda',$vec);
					$this->salida .= '  <td align="center">';
					$this->salida .='     <a href="'.$accion.'">'.$turnos[0][$i].'</a>';
					$this->salida .= "  </td>";
					unset($vec['seleccion'.($i-1)]);
					$vec['seleccion'.$i]=$turnos[1][$i];
					$accionBorrar=ModuloGetURL('app','CreacionAgenda','user','BorrarAgenda',$vec);
					$this->salida .= "  <td width=\"5%\" align=\"center\">";
          $this->salida .= "    <a href=".$accionBorrar."><img title=\"Borrar Agenda Completa\" border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a>";
					$this->salida .= "  </td>";
					$accion=ModuloGetURL('app','CreacionAgenda','user','CambiarAgendaCompleta',array('turno'=>$turnos[1][$i],"fecha"=>$vec['DiaEspe']));
					$this->salida .= "  <td width=\"5%\" align=\"center\">";
					$this->salida .= "    <a href=".$accion."><img title=\"Cambio Turno\" border=\"0\" src=\"".GetThemePath()."/images/equivalencia.png\"></a>";
					$this->salida .= "  </td>";
					$this->salida .= "</tr>";
				}else{
					$fecha=$turnos[0][$i];
					$vec['DiaEspe']=$turnos[0][$i];
					if($spy==0){$estilo='modulo_list_claro';$spy=1;}else{$estilo='modulo_list_oscuro';$spy=0;}
				  $this->salida.="  <tr  class='$estilo' align='center'  onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>";
					$this->salida .= '  <td width="5%" align="center">';
					$this->salida .= '    <input type="checkbox" name="seleccion'.$i.'" value="'.$vec['TurnoAgenda'].'" class="input-submit">';
          $this->salida .= '  </td>';
					while($fecha==$turnos[0][$i]){
						$vec['TurnoAgenda'].=$turnos[1][$i].',';
						$i++;
					}
					$i--;
					$accion=ModuloGetURL('app','CreacionAgenda','user','ListadoDiaAgenda',$vec);
					$this->salida .= '  <td align="center">';
					$this->salida .='     <a href="'.$accion.'">'.$turnos[0][$i].'</a>';
					$this->salida .= "  </td>";
					unset($vec['seleccion'.($i-1)]);
					$vec['seleccion'.$i]=$turnos[1][$i];
					$accionBorrar=ModuloGetURL('app','CreacionAgenda','user','BorrarAgenda',$vec);
					$this->salida .= '  <td width="5%" align="center">';
          $this->salida .= "    <a href=".$accionBorrar."><img title=\"Borrar Agenda Completa\" border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a>";
					$this->salida .= "  </td>";
					$accion=ModuloGetURL('app','CreacionAgenda','user','CambiarAgendaCompleta',array('turno'=>$vec['TurnoAgenda'],"fecha"=>$vec['DiaEspe']));
					$this->salida .= "  <td width=\"5%\" align=\"center\">";
					$this->salida .= "    <a href=".$accion."><img title=\"Cambio Turno\" border=\"0\" src=\"".GetThemePath()."/images/equivalencia.png\"></a>";
					$this->salida .= "  </td>";
					$this->salida .= "</tr>";
				}
				$i++;
			}
			$this->salida .= "</table>";
			$this->salida .= '<br>';
			$this->salida .= '<table align="center" width="60%" border="0">';
			$this->salida .= '<tr>';
			$this->salida .= '<td align="left">';
			$this->salida .= '<input type="submit" name="Borrar" value="ELIMINAR" class="input-submit">';
			$this->salida .= '</td>';
			$this->salida .= '</tr>';
			$this->salida .= '<tr>';
			//$this->salida .= '<td align="center">';
			$vec='';
			foreach($_REQUEST as $v=>$datos){
				if($v!='modulo' and $v!='metodo' and $v!='tipo' and $v!='SIIS_SID' and $v!='DiaEspe' and $v!='TurnoAgenda' and substr_count ($v,'seleccion')!=1){
					$vec[$v]=$datos;
				}
			}
			$accion=ModuloGetURL('app','CreacionAgenda','user','CambiarAgenda',$vec);
			//Este es el antiguo cambiar turno si sucede algun error debe consultarse para saber cual es su funcionmiento
			//$this->salida .= '<input type="submit" name="Cambiar" value="CAMBIAR" class="input-submit" onclick="form.action='."'".$accion."'".'">';
			$this->salida .= '</form>';
			//$this->salida .= '</td>';
			$this->salida .= '<td align="center">';
			$vec='';
			foreach($_REQUEST as $v=>$datos){
				if($v!='modulo' and $v!='tipo' and $v!='SIIS_SID' and $v!='metodo' and $v!='tercero' and $v!='tipoid' and $v!='nombrep' and substr_count ($v,'seleccion')!=1){
					$vec[$v]=$datos;
				}
			}
			if(!is_array($vec)){
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
		}else{
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
			foreach($_REQUEST as $v=>$datos){
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
		}
		$this->salida .= ThemeCerrarTabla();
		return true;
	}




/**
* Muestra las agendas con la posibilidad de realizar el cambio de la misma
*
* @access private
* @return boolean Para identificar que se realizo.
*/

	function CambiarAgendaCompleta()
	{

   if(empty($_REQUEST['DiaEspe'])){$_REQUEST['DiaEspe']=date("Y-m-d");}
		SessionDelVar('CITASMES');
		unset($_SESSION['BorrarAgenda']['DatosAgenda']);
		$a=explode(',',$_REQUEST['turno']);
		$profesionales=$this->BuscarProfesionales($this->BusquedaEspecialidad($a[0]));
		$this->salida=ThemeAbrirTabla('SELECCION DE FECHA PARA LA MODIFICACION DE LA AGENDA');
		$this->EncabezadoModificacion();
		$this->salida .= '<table width="70%" align="center" class="modulo_table_list">';
		$this->salida .= '<tr align="center" class="modulo_table_title">';
		$this->salida .= '<td align="center">Profesional</td>';
		$this->salida .= '<td align="center">D?a Agenda</td>';
		$this->salida .= "</tr>";
		$this->salida .= '<tr class="modulo_list_oscuro">';
		$this->salida .= "<td align=\"center\">".$_SESSION['BorrarAgenda']['DatosProf']['nombrep']."</td>";
		(list($ano,$mes,$dia)=explode('-',$_REQUEST['fecha']));
		$FechaConver=mktime(0,0,0,$mes,$dia,$ano);
		$this->salida .= "<td align=\"center\">".strftime("%A %d de  %B de %Y",$FechaConver)."</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .= '<br>';
		$this->salida .= "<table border=\"0\" width=\"50%\" align=\"center\">";
		$this->salida .= '<tr align="center" class="modulo_table_title">';
		(list($ano,$mes,$dia)=explode('-',$_REQUEST['DiaEspe']));
			$FechaConver=mktime(0,0,0,$mes,$dia,$ano);
		if($_REQUEST['DiaEspe']>=date("Y-m-d")){
			$mensaje="FECHA SELECCIONADA PARA EL CAMBIO: &nbsp&nbsp&nbsp&nbsp;".strftime("%A %d de  %B de %Y",$FechaConver)."";
			$this->salida .= "<td>$mensaje</td>";
		}else{
      $mensaje="NO SE PUEDE MODIFICAR LA AGENDA DEL D?A:";
			$this->salida .= "<td>$mensaje&nbsp&nbsp&nbsp&nbsp;<label class=\"label_error\">".strftime("%A %d de  %B de %Y",$FechaConver)."</label></td>";
		}
    $this->salida .= '<tr>';
//aqui inserte lo de lorena
		$this->salida.="   <tr class=\"modulo_list_claro\"><td>";
		$this->salida.="    \n".'<script>'."\n";
		$this->salida.='    function year1(t)'."\n";
		$this->salida.='    {'."\n";
		$this->salida.='    document.cosa.action="Contenido.php?year="+t.elements[0].value+"&meses="+t.elements[1].value+"';
		foreach($_REQUEST as $v=>$v1){
			if($v=='Cambiar'){
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
		$this->salida .=' <form name="cosa" method="post">';
		$this->salida .=" <table border=\"0\" width=\"98%\" align=\"center\">";
		$this->salida .=' <tr align="center">';
		$this->salida .=" <td class=\"label\">A?O</td><td><select name=\"year\" onchange=\"year1(this.form)\" class=\"select\">";
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
		$this->salida .= " </select></td>";
		$this->salida .="  <td class=\"label\">MES</td><td><select name=\"mes\" onchange=\"year1(this.form)\" class=\"select\">";
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
		$this->salida .= " </select>";
		$this->salida .= " </td>";
		$this->salida .= " </tr>";
		$this->salida .= " </table>";
		$this->salida .='  </form>';
		$this->salida .=" <table border=\"1\" width=\"98%\" align=\"center\">";
		$this->salida .=' <tr align="center"><td>';
		$this->ReturnMetodoExterno('app', 'Agenda', 'user', 'CalendarioEstandard');
    $this->salida .=' </td></tr>';
		$this->salida .= " </table><BR>";
		$this->salida .= "</td></tr>";
		/**************************************/
		$this->salida .= " </table>";

		$accion=ModuloGetURL('app','CreacionAgenda','user','CambiarAgendaTurnoCompleto',array('turno'=>$_REQUEST['turno'],'year'=>$year,'meses'=>$meses,"DiaEspe"=>$_REQUEST['DiaEspe'],"fecha"=>$_REQUEST['fecha']));
		$this->salida .= '<form name="cambiar" method="post" action="'.$accion.'">';
		$this->salida .=$salida;
		$justificacion=$this->BusquedaTipoJustificacion();
		$this->salida .="<table border=\"0\" width=\"50%\" align=\"center\">";
		$this->salida .='<tr align="center">';
		$this->salida .="<td class=\"".$this->SetStyle("justificacion")."\">JUSTIFICACI?N:</td>";
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
		$this->salida .= '<input type="submit" name="Cambiar" value="CAMBIAR" class="input-submit">';
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
		$agenda_cita=$this->BusquedaAgendasPantallaFinal();
		$this->salida=ThemeAbrirTabla('CAMBIOS EN LA ASIGNACION DE TURNOS');
    $this->salida .='<script>';
		$this->salida .= "function chequeoTotal(frm,x,valor){";
    $this->salida .= "  if(x==true){";
    $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
    $this->salida .= "      cadena=frm.elements[i].value;";
		$this->salida .= "      vector=cadena.split('/');";
		$this->salida .= "      val=vector[0];";
    $this->salida .= "      if(frm.elements[i].type=='checkbox' && val==valor && frm.elements[i].name=='selectUno[]'){";
    $this->salida .= "        frm.elements[i].checked=true";
		$this->salida .= "      }";
		$this->salida .= "    }";
    $this->salida .= "  }else{";
    $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
		$this->salida .= "      cadena=frm.elements[i].value;";
		$this->salida .= "      vector=cadena.split('/');";
		$this->salida .= "      val=vector[0];";
    $this->salida .= "      if(frm.elements[i].type=='checkbox' && val==valor && frm.elements[i].name=='selectUno[]'){";
    $this->salida .= "        frm.elements[i].checked=false";
		$this->salida .= "      }";
		$this->salida .= "    }";
		$this->salida .= "  }";
    $this->salida .= "}";
		$this->salida .= "function chequeoTotalAgendaDos(frm,x,valor){";
    $this->salida .= "  if(x==true){";
    $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
    $this->salida .= "      cadena=frm.elements[i].value;";
		$this->salida .= "      vector=cadena.split('/');";
		$this->salida .= "      val=vector[0];";
    $this->salida .= "      if(frm.elements[i].type=='checkbox' && val==valor && frm.elements[i].name=='selectUnoAgendaDos[]'){";
    $this->salida .= "        frm.elements[i].checked=true";
		$this->salida .= "      }";
		$this->salida .= "    }";
    $this->salida .= "  }else{";
    $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
		$this->salida .= "      cadena=frm.elements[i].value;";
		$this->salida .= "      vector=cadena.split('/');";
		$this->salida .= "      val=vector[0];";
    $this->salida .= "      if(frm.elements[i].type=='checkbox' && val==valor && frm.elements[i].name=='selectUnoAgendaDos[]'){";
    $this->salida .= "        frm.elements[i].checked=false";
		$this->salida .= "      }";
		$this->salida .= "    }";
		$this->salida .= "  }";
    $this->salida .= "}";
		$this->salida .= "function chequeoTotalPaso(frm,x){";
    $this->salida .= "  if(x==true){";
    $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
    $this->salida .= "      if(frm.elements[i].type=='checkbox' && frm.elements[i].name=='selectUnoPaso[]'){";
    $this->salida .= "        frm.elements[i].checked=true";
		$this->salida .= "      }";
		$this->salida .= "    }";
    $this->salida .= "  }else{";
    $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
    $this->salida .= "      if(frm.elements[i].type=='checkbox' && frm.elements[i].name=='selectUnoPaso[]'){";
    $this->salida .= "        frm.elements[i].checked=false";
		$this->salida .= "      }";
		$this->salida .= "    }";
		$this->salida .= "  }";
    $this->salida .= "}";
		$this->salida .="function abrirVentanaNuevoTurno(nombre, url, ancho, altura, x,frm){\n";
		$this->salida .=" f=frm;";
    $this->salida .=" var str = 'width=300,height=150,resizable=no,status=no,location=no,scrollbars=no,top=250,left=300';\n";
    $this->salida .=" var remd = window.open(url, nombre, str);\n";
		$this->salida .=" if (remd != null) {\n";
		$this->salida .="   if (remd.opener == null) {\n";
		$this->salida .="	    remd.opener = self;\n";
		$this->salida .="   }\n";
		$this->salida .=" }\n";
		$this->salida .="}\n";
		$this->salida .="function abrirVentanaAsignaTurno(nombre, url, ancho, altura, x,frm){\n";
		$this->salida .=" f=frm;";
    $this->salida .=" var str = 'width=650,height=560,resizable=no,status=no,location=no,scrollbars=no,top=250,left=300';\n";
    $this->salida .=" var remd = window.open(url, nombre, str);\n";
		$this->salida .=" if (remd != null){\n";
		$this->salida .="   if (remd.opener == null) {\n";
		$this->salida .="	    remd.opener = self;\n";
		$this->salida .="   }\n";
		$this->salida .=" }\n";
		$this->salida .="}\n";
		$this->salida .= "  function xxx(){";
		$this->salida .= "   document.location.reload();";
		//$this->salida .= "   document.location.reload();";
		//window.location.href
    $this->salida .= "  }";
		$this->salida .='</script>';
		$this->salida .='<br>';
		foreach($_REQUEST as $v=>$datos){
			if($v!='modulo' and $v!='SIIS_SID' and $v!='tipo' and $v!='selectUno' and $v!='selectUnoAgendaDos' and $v!='selectUnoPaso' and $v!='cancelarCitas' and $v!='cancelarCitasAgendaDos' and $v!='PasarCitas' and $v!='metodo' and $v!='saber')	{
				$vec[$v]=$datos;
			}
		}
    $accion=ModuloGetURL('app','CreacionAgenda','user','CambiarAgendaCompletaTotal',$vec);
		$this->salida.='<form name="siguiente" method="post" action="'.$accion.'">';
		$this->EncabezadoModificacion();
		$this->salida .= '<table width="100%" align="center" border="0">';
		$this->salida .= "      <tr><td colspan=\"4\" align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "      </td></tr>";
    $this->salida .= "<tr><td width=\"50%\" align=\"center\" valign=\"top\">";
		$i=0;
		$inicio=0;
		$agenda_turno_idAnt='-1';
		if($agenda_cita){
		$AgendaCitaIdPadreAnt=-1;
    $identificacionAnt=-1;
		foreach($agenda_cita as $k=>$v){
			foreach($v as $t=>$m){
				foreach($m as $s=>$q){
					if($inicio==0){
						$this->salida .= '<table width="100%" align="center" class="modulo_table_list">';
						$this->salida .= '<tr align="center" class="modulo_table_title">';
						$this->salida .= "<td align=\"center\">";
						$this->salida .= "PROFESIONAL";
						$this->salida .= "</td>";
						$this->salida .= "<td align=\"center\">";
						$this->salida .= "D?A AGENDA";
						$this->salida .= "</td>";
						$this->salida .= "</tr>";
						$this->salida .= '<tr class="modulo_list_oscuro">';
						$this->salida .= "<td align=\"center\" class=\"label\">".$q['nomprofesional']."</td>";
						(list($ano,$mes,$dia)=explode('-',$q['fecha_turno']));
		        $FechaConver=mktime(0,0,0,$mes,$dia,$ano);
						$this->salida .= "<td align=\"center\" class=\"label\">".strftime("%A %d de  %B de %Y",$FechaConver)."</td>";
						//$a=explode(',',$_REQUEST['Profesional']);
						$this->salida .= "</tr>";
						$this->salida .= "</table>";
						$this->salida .= '<br>';
						$this->salida .= '<table width="95%" align="center" class="modulo_table_list">';
						$this->salida .= '<tr align="center" class="modulo_table_title">';
						$this->salida .= "<td align=\"center\" nowrap width=\"5%\">&nbsp;</td>";
						$this->salida .= "<td align=\"center\">Asignada</td>";
						$this->salida .= "<td align=\"center\" nowrap width=\"10%\">Hora</td>";
						$this->salida .= "<td align=\"center\" nowrap width=\"10%\">&nbsp;</td>";
						$this->salida .= "<td width=\"15%\" nowrap align=\"center\"><input type=\"checkbox\" onclick=\"chequeoTotalPaso(this.form,this.checked)\" name=\"selectTotalPaso\" value=\"1\">&nbsp&nbsp&nbsp;Pasar&nbsp&nbsp;-></td>";
						$this->salida .= "</tr>";
						$inicio=1;
					}
					if($q['agenda_turno_id']!=$agenda_turno_idAnt){
            $this->salida .= "<tr align=\"center\" class=\"modulo_table_list_title\"><td align=\"center\"><input type=\"checkbox\" onclick=\"chequeoTotal(this.form,this.checked,this.value)\" name=\"selectTotal\" value=\"".$q['agenda_turno_id']."\"></td>";
						 $this->salida .= "<td align=\"center\" colspan=\"4\">No. Agenda: ".$q['agenda_turno_id']."</td></tr>";
						 $agenda_turno_idAnt=$q['agenda_turno_id'];
					}
					$AgendaCitaIdPadre=$q['agenda_cita_id_padre'];
					if($AgendaCitaIdPadreAnt != $AgendaCitaIdPadre || empty($AgendaCitaIdPadre) || ($AgendaCitaIdPadre==$AgendaCitaIdPadreAnt && !empty($AgendaCitaIdPadre) && $q['identificacion']!=$identificacionAnt)){
					 $conta=0;
           if(!empty($AgendaCitaIdPadre)){
              foreach($agenda_cita as $a=>$vector){
								foreach($vector as $b=>$vector1){
									foreach($vector1 as $c=>$DatosCit){
                    if($DatosCit['agenda_cita_id_padre']==$AgendaCitaIdPadre && $DatosCit['identificacion']==$q['identificacion']){
                      $conta++;
										}
									}
								}
							}
					  }

						if($q['sw_estado']!=9){
						  if($spy==0){$estilo='modulo_list_oscuro';$spy=1;}else	{$estilo='modulo_list_claro';$spy=0;}
							$this->salida .= "<tr class=\"$estilo\">";
							if($q['sw_estado']===NULL && empty($q['nombre_completo']) && empty($q['identificacion'])){
								$this->salida .= "<td align=\"center\" rowspan=\"$conta\"><input type=\"checkbox\" name=\"selectUno[]\" value=\"".$q['agenda_turno_id']."/".$q['agenda_cita_id']."\"></td>";
							}else{
								$this->salida .= "<td align=\"center\" rowspan=\"$conta\">&nbsp;</td>";
							}
							if($q['sw_estado']===NULL && empty($q['nombre_completo']) && empty($q['identificacion'])){
								$this->salida .= "<td rowspan=\"$conta\" align=\"center\">No</td>";
							}if($q['sw_estado']==1 || $q['sw_estado']==5 || (!empty($q['nombre_completo']) && !empty($q['identificacion']))){
								$this->salida .= "<td rowspan=\"$conta\" align=\"center\" class=\"label\">".$q['nombre_completo']."<BR>".$q['identificacion']."</td>";
							}if($q['sw_estado']==2){
								$this->salida .= "<td rowspan=\"$conta\" align=\"center\" class=\"label_error\">Paga</td>";
							}
							$this->salida .= '<td align="center">';
							$this->salida .=$q['hora'];
							$this->salida .= "</td>";
							if($q['sw_estado']===NULL){
							$this->salida .= "<td align=\"center\" rowspan=\"$conta\">&nbsp;</td>";
							$this->salida .= "<td align=\"center\" rowspan=\"$conta\">&nbsp;</td>";
							}else{
								$Profesional=urlencode($_REQUEST['Profesional']);
								$identificacion=urlencode($q['identificacion']);
								$nombre_completo=urlencode($q['nombre_completo']);
								$ruta1='app_modules/CreacionAgenda/AsignarTurnoAgenda.php?AgendaId='.$q['agenda_turno_id'].'&TunoId='.$q['agenda_cita_id'].'&profesional='.$Profesional.'&fecha='.$_REQUEST['DiaEspe'].'&identificacion='.$identificacion.'&nombrePaciente='.$nombre_completo.'&justificacion='.$_REQUEST['justificacion'].'&citaAsignada='.$q['agenda_cita_asignada_id'];
								$this->salida .= "<td rowspan=\"$conta\" align=\"center\"><a href=\"javascript:abrirVentanaAsignaTurno('TURNO','$ruta1',700,50,0,this.form)\"><img border=\"0\" src=\"".GetThemePath()."/images/checkno.png\" title=\"Cambio de Cita\"></a></td>";
								$verificar=$this->VerificarHoraVaciaAgenda($q['hora'],$_REQUEST['DiaEspe'],$_REQUEST['Profesional']);
								if($verificar==1  && $conta < 2){
									$this->salida .= "<td rowspan=\"$conta\" align=\"center\"><input type=\"checkbox\" name=\"selectUnoPaso[]\" value=\"".$q['agenda_cita_asignada_id']."||//".$q['agenda_cita_id']."||//".$q['agenda_turno_id']."||//".$_REQUEST['justificacion']."||//".$_REQUEST['DiaEspe']."||//".$_REQUEST['Profesional']."||//".$q['hora']."\"></td>";
								}else{
									$this->salida .= "<td rowspan=\"$conta\" align=\"center\">&nbsp;</td>";
								}
							}
							$this->salida .= "</tr>";
						}
					}else{
            if($q['sw_estado']!=9){
							$this->salida .= "<tr class=\"$estilo\">";
              $this->salida .= '<td align="center">';
							$this->salida .=$q['hora'];
							$this->salida .= "</td>";
              $this->salida .= "</tr>";
						}
					}
          if(!empty($AgendaCitaIdPadre)){
            $AgendaCitaIdPadreAnt = $AgendaCitaIdPadre;
					}
          $identificacionAnt=$q['identificacion'];

				}
			}
		}
		$this->salida .= "</table>";
    $this->salida .= "<table width=\"95%\" align=\"center\">";
    $this->salida .= "<tr><td><input type=\"submit\" name=\"cancelarCitas\" value=\"ELIMINAR CITAS\" class=\"input-submit\"></td></tr>";
    $this->salida .= "</table>";
		}else{
    $this->salida .= "<table width=\"95%\" align=\"center\">";
    $this->salida .= "<tr><td class=\"label_error\" align=\"center\">ELIMINADOS LOS TURNOS DEL PROFESIONAL</tr>";
    $this->salida .= "</table>";
		}
		$this->salida .= "</td>";
		$this->salida .= "<td valign=\"top\" width=\"5%\" align=\"center\">";
    $this->salida .= "<table width=\"95%\" align=\"center\">";
		$this->salida .= "<tr><td><BR>&nbsp;</td></tr>";
		$this->salida .= "<tr><td>&nbsp;</td></tr>";
    $this->salida .= "<tr><td align=\"center\">";
		$agenda_cita=$this->BusquedaAgendasPantallaFinalDestino($_REQUEST['DiaEspe'],$_REQUEST['Profesional']);
		if($agenda_cita){
      $this->salida .= "<input type=\"submit\" name=\"PasarCitas\" value=\">>\" class=\"input-submit\">";
		}else{
      $this->salida .= "&nbsp;";
		}
		$this->salida .= "</td></tr>";
    $this->salida .= "</table>";
    $this->salida .= "</td>";
    $this->salida .= "<td width=\"45%\" align=\"center\" valign=\"top\">";
    $i=0;
		$inicio=0;
		$prof=(explode(',',$_REQUEST['Profesional']));
		$this->salida .= '<table width="100%" align="center" class="modulo_table_list">';
		$this->salida .= '<tr align="center" class="modulo_table_title">';
		$this->salida .= "<td align=\"center\">";
		$this->salida .= "PROFESIONAL";
		$this->salida .= "</td>";
		$this->salida .= "<td align=\"center\">";
		$this->salida .= "D?A AGENDA";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= '<tr class="modulo_list_oscuro">';
		$this->salida .= "<td align=\"center\" class=\"label\">".$prof[2]."</td>";
		(list($ano,$mes,$dia)=explode('-',$_REQUEST['DiaEspe']));
		$FechaConver=mktime(0,0,0,$mes,$dia,$ano);
		$this->salida .= "<td align=\"center\" class=\"label\">".strftime("%A %d de  %B de %Y",$FechaConver)."</td>";
		//$a=explode(',',$_REQUEST['Profesional']);
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .= '<br>';
		$agenda_turno_idAnt='-1';
    $identificacionAnt=-1;
		if($agenda_cita){
		$AgendaCitaIdPadreAnt=-1;
		foreach($agenda_cita as $k=>$v)
		{
			foreach($v as $t=>$m)
			{
				foreach($m as $s=>$q){
					if($inicio==0){
						$this->salida .= '<table width="95%" align="center" class="modulo_table_list">';
						$this->salida .= '<tr align="center" class="modulo_table_title">';
						$this->salida .= "<td align=\"center\" nowrap width=\"10%\">Hora</td>";
						$this->salida .= "<td align=\"center\">Asignada</td>";
						$this->salida .= "<td align=\"center\" nowrap width=\"5%\">&nbsp;</td>";
						$this->salida .= "</tr>";
						$inicio=1;
					}
					if($q['agenda_turno_id']!=$agenda_turno_idAnt){
            $this->salida .= "<tr class=\"modulo_table_list_title\">";
						$this->salida .= "<td align=\"center\" colspan=\"2\">No. Agenda: ".$q['agenda_turno_id']."&nbsp&nbsp&nbsp&nbsp&nbsp;";
						$ruta='app_modules/CreacionAgenda/CrearNuevoTurnoAgenda.php?AgendaId='.$q['agenda_turno_id'].'&vec='.$vec;
						$this->salida .= "<a href=\"javascript:abrirVentanaNuevoTurno('TURNO','$ruta',700,50,0,this.form)\" ><b>CREAR NUEVO TURNO</b></a></td>";
            $this->salida .= "<td align=\"center\" nowrap width=\"5%\"><input type=\"checkbox\" onclick=\"chequeoTotalAgendaDos(this.form,this.checked,this.value)\" name=\"selectTotalAgendaDos\" value=\"".$q['agenda_turno_id']."\"></td>";
						$this->salida .= "</tr>";
						$agenda_turno_idAnt=$q['agenda_turno_id'];
					}
					$AgendaCitaIdPadre=$q['agenda_cita_id_padre'];
					if($AgendaCitaIdPadreAnt != $AgendaCitaIdPadre || empty($AgendaCitaIdPadre) || ($AgendaCitaIdPadre==$AgendaCitaIdPadreAnt && !empty($AgendaCitaIdPadre) && $q['identificacion']!=$identificacionAnt)){
					  $conta=0;
            if(!empty($AgendaCitaIdPadre)){
						  foreach($agenda_cita as $a=>$vector){
							  foreach($vector as $b=>$vector1){
								  foreach($vector1 as $c=>$DatosCit){
									  if($DatosCit['agenda_cita_id_padre']==$AgendaCitaIdPadre && $DatosCit['identificacion']==$q['identificacion']){
										  $conta++;
									  }
								  }
							  }
						  }
					  }

						if($q['sw_estado']!=9){
						  if($spy==0){$estilo='modulo_list_oscuro';$spy=1;}else	{$estilo='modulo_list_claro';$spy=0;}
							$this->salida .= "<tr class=\"$estilo\">";
							$this->salida .= '<td align="center">';
							$this->salida .=$q['hora'];
							$this->salida .= "</td>";
							if($q['sw_estado']===NULL  && empty($q['nombre_completo']) && empty($q['identificacion'])){
								$this->salida .= "<td rowspan=\"$conta\" align=\"center\">No</td>";
							}if($q['sw_estado']==1 || $q['sw_estado']==5  || (!empty($q['nombre_completo']) && !empty($q['identificacion']))){
								$this->salida .= "<td rowspan=\"$conta\" align=\"center\" class=\"label\">".$q['nombre_completo']."<BR>".$q['identificacion']."</td>";
							}if($q['sw_estado']==2){
								$this->salida .= "<td rowspan=\"$conta\" align=\"center\" class=\"label_error\">Paga</td>";
							}
							if($q['sw_estado']===NULL  && empty($q['nombre_completo']) && empty($q['identificacion'])){
							$this->salida .= "<td rowspan=\"$conta\"align=\"center\"><input type=\"checkbox\" name=\"selectUnoAgendaDos[]\" value=\"".$q['agenda_turno_id']."/".$q['agenda_cita_id']."\"></td>";
							}else{
							$this->salida .= "<td rowspan=\"$conta\" align=\"center\">&nbsp;</td>";
							}
							$this->salida .= "</tr>";
						}
					}else{
            if($q['sw_estado']!=9){
						  $this->salida .= "<tr class=\"$estilo\">";
              $this->salida .= '<td align="center">';
							$this->salida .=$q['hora'];
							$this->salida .= "</td>";
						  $this->salida .= "</tr>";
						}
					}
					if(!empty($AgendaCitaIdPadre)){
            $AgendaCitaIdPadreAnt = $AgendaCitaIdPadre;
					}
          $identificacionAnt=$q['identificacion'];
				}
			}
		}
		$this->salida .= "</table>";
		$this->salida .= "<table width=\"95%\" align=\"center\">";
    $this->salida .= "<tr><td align=\"right\"><input type=\"submit\" name=\"cancelarCitasAgendaDos\" value=\"ELIMINAR CITAS\" class=\"input-submit\"></td></tr>";
    $this->salida .= "</table>";
		}else{
      $this->salida .= "<table width=\"95%\" align=\"center\" class=\"Normal_10\">";
			$this->salida .= "<tr><td class=\"label_error\" align=\"CENTER\">NO EXISTE AGENDA CREADA PARA EL PROFESIONAL</td></tr>";
			//$this->salida .= "<tr><td align=\"CENTER\"><a href=\"$action\" class=\"link\"><b>CREAR NUEVA AGENDA&nbsp&nbsp&nbsp;</b><img border=\"0\" src=\"".GetThemePath()."/images/planblanco.png\"></a></td></tr>";
			$this->salida .= "</table>";
		}
    $this->salida .= "</td></tr>";
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
		//$accion=ModuloGetURL('app','CreacionAgenda','user','CambiarAgendaCompletaTotal',$vec);
		//$this->salida.='<form name="siguiente" method="post" action="'.$accion.'">';
		//$this->salida .= '<input type="submit" name="Cambiar" value="Cambiar" class="input-submit">';
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
			$this->EncabezadoModificacion();
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
				/*$this->salida .= '<table width="70%" align="center">';
				$this->salida .= '<tr align="center">';
				$this->salida .= '<td align="center">';
				$this->salida .= '<label class="label_error">NO SE PUEDE BORRAR LA AGENDA POR TENER CITAS ASIGNADAS</label>';
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
				$this->salida .= "</table>";
				$this->salida .= '<br>';
				*/
			}
			foreach($_REQUEST as $v=>$datos)
			{
				if($v!='modulo' and $v!='metodo' and $v!='tipo' and $v!='SIIS_SID' and substr_count ($v,'seleccion')!=1 and $v!='Borrar')
				{
					$vec[$v]=$datos;
				}
			}
			$accion=ModuloGetURL('app','CreacionAgenda','user','BorrarAgendaDia',$vec);
			$this->salida .= '<form name="siguiente" method="post" action="'.$accion.'">';
			$this->salida .= '<table width="70%" align="center" class="modulo_table_list">';
			$this->salida .= '<tr align="center" class="modulo_table_title">';
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "PROFESIONAL";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "D?A AGENDA";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= '<tr class="modulo_list_oscuro">';
			$this->salida .= "<td align=\"center\" class=\"label\">".$_SESSION['BorrarAgenda']['DatosProf']['nombrep']."</td>";
			(list($ano,$mes,$dia)=explode('-',$_REQUEST['DiaEspe']));
			$FechaConver=mktime(0,0,0,$mes,$dia,$ano);
			$this->salida .= "<td align=\"center\" class=\"label\">".strftime("%A %d de  %B de %Y",$FechaConver)."</td>";
			//$a=explode(',',$_REQUEST['Profesional']);
			$this->salida .= "</tr>";
			$this->salida .= "</table><br>";
      /*$horaAnterior=-1;
			$contMax=0;
      while($i<sizeof($turnosdia[0])){
        (list($hora,$minutos)=explode(':',$turnosdia[0][$i]));
				if($hora!=$horaAnterior){
          $horaAnterior=$hora;
					$contMax=$cont;
					$cont=1;
					if($contMax>$contMax){
            $contMax=$cont;
					}
				}else{
          $cont++;
				}
			  $i++;
			}
			//echo $contMax;
      $this->salida .= '<table width="80%" align="center">';
			$this->salida .= '<tr class="modulo_table_title">';
      $this->salida .= '<td>Hora</td>';
			$this->salida .= '<td colspan='.$contMax.'>Minutos</td>';
			$this->salida .= '</tr>';
			$i=0;
			$horaAnt='';
			$TipoIdAnt='';
      $PacienteAnt='';
			$horaAnterior=-1;
			$bandera=0;
			while($i<sizeof($turnosdia[0]))
			{
			  if($horaAnt!=$turnosdia[0][$i] || (($TipoIdAnt.' '.$PacienteAnt) != ($turnosdia[6][$i].' '.$turnosdia[5][$i]))){
					if($spy==0){$estilo='modulo_list_oscuro';	$spy=1;}else{$estilo='modulo_list_claro';$spy=0;}
					(list($hora,$minutos)=explode(':',$turnosdia[0][$i]));
					if($hora!=$horaAnterior){
					  if($bandera==1){
						  if($sumCajones<$contMax){
                while($sumCajones<$contMax){
                $this->salida .='<td>&nbsp;</td>';
								$sumCajones++;
								}
							}
              $this->salida .='</tr>';
						}
						$this->salida .="<tr class=\"$estilo\">";
						$this->salida .="<td width=\"10%\"align=\"center\">".$hora."</td>";
						$this->salida .="<td width=\"90%\" align=\"center\">";
						$this->salida .= "<table width=\"100%\" align=\"center\" border=\"0\">";
						$this->salida .= "<tr class=\"$estilo\">";
						$this->salida .= "  <td align=\"center\">".$minutos."</td>";
						$this->salida .= "  <td rowspan=\"3\" align=\"center\">";
						$this->salida .= '  <input type="checkbox" name="seleccion'.$i.'" value="'.$turnosdia[1][$i].','.$turnosdia[2][$i].'" class="input-submit">';
						$this->salida .= "  </td>";
						$this->salida .= "</tr>";
						if($turnosdia[6][$i] && $turnosdia[5][$i] && $turnosdia[3][$i]){
						$this->salida .= "<tr class=\"$estilo\"><td align=\"center\">";
						$dato=RetornarWinOpenDatosPaciente($turnosdia[6][$i],$turnosdia[5][$i],$turnosdia[3][$i]);
						$this->salida .=$dato;
						$this->salida .= "</td></tr>";
						if($turnosdia[4][$i]){
						$this->salida .="<tr class=\"$estilo\"><td align=\"center\">";
						$this->salida .=$turnosdia[4][$i];
						$this->salida .="</td></tr>";
						}
						}
						$this->salida .="</table>";
						$this->salida .="</td>";
						$horaAnterior=$hora;
						$bandera=1;
						$sumCajones=1;
					}else{
					  $this->salida .="<td width=\"90%\" align=\"center\">";
						$this->salida .= "<table width=\"100%\" align=\"center\" border=\"0\">";
						$this->salida .= "<tr class=\"$estilo\">";
						$this->salida .= "  <td width=\"5%\" align=\"center\">".$minutos."</td>";
						$this->salida .= "  <td width=\"5%\" rowspan=\"3\" align=\"center\">";
						$this->salida .= '  <input type="checkbox" name="seleccion'.$i.'" value="'.$turnosdia[1][$i].','.$turnosdia[2][$i].'" class="input-submit">';
						$this->salida .= "  </td>";
						$this->salida .= "</tr>";
						if($turnosdia[6][$i] && $turnosdia[5][$i] && $turnosdia[3][$i]){
						$this->salida .= "<tr class=\"$estilo\"><td align=\"center\">";
						$dato=RetornarWinOpenDatosPaciente($turnosdia[6][$i],$turnosdia[5][$i],$turnosdia[3][$i]);
						$this->salida .=$dato;
						$this->salida .= "</td></tr>";
						if($turnosdia[4][$i]){
						$this->salida .="<tr class=\"$estilo\"><td align=\"center\">";
						$this->salida .=$turnosdia[4][$i];
						$this->salida .="</td></tr>";
						}
						}
						$this->salida .="</table>";
						$this->salida .="</td>";
						$sumCajones++;
					}
				}
				$i++;
			}
			if($sumCajones<$contMax){
				while($sumCajones<$contMax){
				$this->salida .='<td>&nbsp;</td>';
				$sumCajones++;
				}
			}
			$this->salida .='</tr>';
			$this->salida .= "</table>";
      */
			$this->salida .= '<table width="70%" align="center" class="modulo_table_list">';
// 			$this->salida .= " <tr><td align=\"center\" colspan='22'>";
// 			$this->salida .=   $this->SetStyle("MensajeError");
// 			$this->salida .= " </td></tr>";
			$this->salida .= '<tr align="center" class="modulo_table_title">';
			$this->salida .= '<td align="center">';
			$this->salida .= "Hora";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "Paciente";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "Tel?fono";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "Selecci?n";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$i=0;
			$horaAnt='';
			$TipoIdAnt='';
      $PacienteAnt='';
			$AgendaCitaIdPadreAnt=-1;
			while($i<sizeof($turnosdia[0])){
			  if($horaAnt!=$turnosdia[0][$i] || (($TipoIdAnt.' '.$PacienteAnt) != ($turnosdia[6][$i].' '.$turnosdia[5][$i]))){

					$AgendaCitaIdPadre=$turnosdia[7][$i];
					if($AgendaCitaIdPadre!=$AgendaCitaIdPadreAnt || empty($AgendaCitaIdPadre) || ($AgendaCitaIdPadre==$AgendaCitaIdPadreAnt && !empty($AgendaCitaIdPadre) && $turnosdia[6][$i].' '.$turnosdia[5][$i]!=$TipoIdAnt.' '.$PacienteAnt)){
						$conta=0;
						if(!empty($AgendaCitaIdPadre)){
							foreach($turnosdia[7] as $indice=>$AgeCitaIdCmp){
								if($AgeCitaIdCmp==$AgendaCitaIdPadre && $turnosdia[6][$i].' '.$turnosdia[5][$i]==$turnosdia[6][$indice].' '.$turnosdia[5][$indice]){
									$conta++;
								}
							}
						}
						if($spy==0){$this->salida .= '<tr class="modulo_list_oscuro">';	$spy=1;}
						else{$this->salida .= '<tr class="modulo_list_claro">';$spy=0;}
						$this->salida .= '<td align="center">';
						$this->salida .=$turnosdia[0][$i];
						$this->salida .= "</td>";
						$this->salida .= "<td rowspan=\"$conta\" align=\"center\">";
						$dato=RetornarWinOpenDatosPaciente($turnosdia[6][$i],$turnosdia[5][$i],$turnosdia[3][$i]);
						$this->salida .=$dato;
						$this->salida .= "</td>";
						$this->salida .= "<td rowspan=\"$conta\" align=\"center\">";
						$this->salida .=$turnosdia[4][$i];
						$this->salida .= "</td>";
						$this->salida .= "<td rowspan=\"$conta\" align=\"center\">";
						$this->salida .= '<input type="checkbox" name="seleccion'.$i.'" value="'.$turnosdia[1][$i].','.$turnosdia[2][$i].'" class="input-submit">';
						$this->salida .= "</td>";
						$this->salida .= "</tr>";
					}else{
            if($spy==0){$this->salida .= '<tr class="modulo_list_oscuro">';	$spy=1;}
						else{$this->salida .= '<tr class="modulo_list_claro">';$spy=0;}
						$this->salida .= '<td align="center">';
						$this->salida .=$turnosdia[0][$i];
						$this->salida .= "</td>";
            $this->salida .= "</tr>";
					}
					$horaAnt=$turnosdia[0][$i];
					$TipoIdAnt=$turnosdia[6][$i];
					$PacienteAnt=$turnosdia[5][$i];
					if(!empty($AgendaCitaIdPadre)){
						$AgendaCitaIdPadreAnt=$AgendaCitaIdPadre;
					}
				}
				$i++;
			}
			$this->salida .= "</table>";
			$this->salida .= '<br>';
			$this->salida .= '<table align="center" width="70%" border="0">';
			$this->salida .= '<tr>';
			$this->salida .= '<td align="center">';
			//Elimina el Registro
			$this->salida .= '<input type="submit" name="Borrar" value="ELIMINAR TURNO" class="input-submit">';
			$this->salida .= '</td>';
			$this->salida .= '<td align="center">';
			//Coloca en el estado 3 el registro sin importar que haya registro en asignacion de citas
			//$this->salida .= '<input type="submit" name="Cancelar" value="CANCELAR TURNO" class="input-submit">';
			$this->salida .= '<input type="submit" name="Cancelar" value="ELIMINAR TURNO COMPLETO" class="input-submit">';
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
		$this->EncabezadoModificacion();
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
		$this->salida .= "Selecci?n";
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
		$this->salida=ThemeAbrirTabla('CAMBIAR AGENDA BUSQUEDA DE D?A');
		$this->EncabezadoModificacion();
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
				$this->salida .= '<label class="label_error">NO SE PUEDE CREAR AGENDA PARA EL D?A '.$_REQUEST['DiaEspe'].'</label>';
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
		$this->salida .="<td class=\"label\">A?O</td><td><select name=\"year\" onchange=\"year1(this.form)\" class=\"select\">";
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
		$this->salida .="<td class=\"".$this->SetStyle("justificacion")."\">JUSTIFICACI?N:</td>";
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
	* @param string identificacion del campo para señalar como no lleno
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

	function ConsultaAgendaMes($tipoid,$tercero,$fecha,$nombrep){

		$this->salida  = ThemeAbrirTabla('CONSULTA AGENDA M?DICA');
		$this->Encabezado();
		(list($ano,$mes,$dia)=explode('-',$fecha));
		$this->salida .= '<table width="70%" align="center" class="modulo_table_list">';
		$this->salida .= '<tr align="center" class="modulo_table_title">';
		$this->salida .= '<td align="center">';
		$this->salida .= "Profesional";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= '<tr class="modulo_list_oscuro">';
		$_SESSION['CreacionAgenda']['nombrep']=$nombrep;
		$this->salida .= "<td align='center'>".$_SESSION['CreacionAgenda']['nombrep']."</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table><BR>";
    $this->salida .= '<table align="center" width="80%" border="0">';
		$this->salida .= "<tr class=\"modulo_table_list_title\"><td>$ano&nbsp&nbsp&nbsp&nbsp;".ucwords(strftime('%B',mktime(0,0,0,$mes,$dia,$ano)))."</td></tr>";
		$this->salida .= "<tr class=\"modulo_list_claro\">";
		$this->salida .= "<td valign=\"top\" align=\"center\">";
		$this->ReturnMetodoExterno('app', 'Agenda', 'user', 'Calendario', array('year'=>$ano,'meses'=>$mes));
    $this->salida .= '</td>';
		$this->salida .= '</tr>';
		$this->salida .= '</table><BR>';
		$accion=ModuloGetURL('app','CreacionAgenda','user','LlamaAgendaConsultaTurnos',array("tercero"=>$tercero,"tipoid"=>$tipoid,"nombrep"=>$_SESSION['CreacionAgenda']['nombrep']));
		$this->salida .= '<form name="volver" method="post" action="'.$accion.'">';
    $this->salida .= '<table align="center" width="95%" border="0">';
		$this->salida .= "<tr><td align=\"center\"><input type=\"submit\" name=\"volver\" value=\"VOLVER\" class=\"input-submit\"></td></tr>";
    $this->salida .= '</table><BR>';
		$this->salida .= "<form>";
		$this->salida .= '<table width="80%" align="center" border="0">';
    $this->salida .= '<tr><td width="50%">';
		$this->salida .= '  <table width="100%" align="left" border="1" class="modulo_table">';
		$this->salida .= '  <tr>';
		$this->salida .= "    <td rowspan=\"2\" class=\"label\">D?a<BR>del<BR>Mes</td>";
		$this->salida .= '    <td align="center">';
    $this->salida .= '      <table width="95%" align="center" border="0" class="modulo_table">';
		$this->salida .= '      <tr align="center">';
		$this->salida .= '      <td align="center" colspan="2" class="label">INTERVALO 1</td>';
		$this->salida .= '      </tr>';
    $this->salida .= '      <tr align="center">';
    $this->salida .= '      <td align="center"><label class="label">Hora Inicio</label></td>';
		$this->salida .= '      <td align="center"><label class="label">Hora Fin</label></td>';
    $this->salida .= '      </tr>';
		$this->salida .= '      <tr>';
    $this->salida .= '      <td align="center">00:00</td>';
		$this->salida .= '      <td align="center">05:59</td>';
    $this->salida .= '      </tr>';
		$this->salida .= '      </table>';
		$this->salida .= '    </td>';
		$this->salida .= '    <td align="center">';
    $this->salida .= '      <table width="95%" align="center" border="0" class="modulo_table">';
    $this->salida .= '      <tr align="center">';
		$this->salida .= '      <td align="center" colspan="2" class="label">INTERVALO 2</td>';
		$this->salida .= '      </tr>';
		$this->salida .= '      <tr align="center">';
    $this->salida .= '      <td align="center"><label class="label">Hora Inicio</label></td>';
		$this->salida .= '      <td align="center"><label class="label">Hora Fin</label></td>';
    $this->salida .= '      </tr>';
		$this->salida .= '      <tr>';
    $this->salida .= '      <td align="center">06:00</td>';
		$this->salida .= '      <td align="center">11:59</td>';
    $this->salida .= '      </tr>';
		$this->salida .= '      </table>';
		$this->salida .= '    </td>';
		$this->salida .= '  </tr>';
		$this->salida .= '  <tr>';
    $this->salida .= '    <td align="center">';
    $this->salida .= '      <table width="95%" align="center" border="0" class="modulo_table">';
		$this->salida .= '      <tr align="center">';
		$this->salida .= '      <td align="center" colspan="2" class="label">INTERVALO 3</td>';
		$this->salida .= '      </tr>';
    $this->salida .= '      <tr align="center">';
    $this->salida .= '      <td align="center"><label class="label">Hora Inicio</label></td>';
		$this->salida .= '      <td align="center"><label class="label">Hora Fin</label></td>';
    $this->salida .= '      </tr>';
		$this->salida .= '      <tr>';
    $this->salida .= '      <td align="center">12:00</td>';
		$this->salida .= '      <td align="center">17:59</td>';
    $this->salida .= '      </tr>';
		$this->salida .= '      </table>';
		$this->salida .= '    </td>';
		$this->salida .= '    <td align="center">';
    $this->salida .= '      <table width="95%" align="center" border="0" class="modulo_table">';
		$this->salida .= '      <tr align="center">';
		$this->salida .= '      <td align="center" colspan="2" class="label">INTERVALO 4</td>';
		$this->salida .= '      </tr>';
    $this->salida .= '      <tr align="center">';
    $this->salida .= '      <td align="center"><label class="label">Hora Inicio</label></td>';
		$this->salida .= '      <td align="center"><label class="label">Hora Fin</label></td>';
    $this->salida .= '      </tr>';
		$this->salida .= '      <tr>';
    $this->salida .= '      <td align="center">18:00</td>';
		$this->salida .= '      <td align="center">23:59</td>';
    $this->salida .= '      </tr>';
		$this->salida .= '      </table>';
		$this->salida .= '    </td>';
		$this->salida .= '  </tr>';
		$this->salida .= '  </table>';
    $this->salida .= '</td>';
    $this->salida .= '<td>';
    $this->salida .= '  <table width="95%" align="center" border="0" class="Normal_10">';
    $this->salida .= '  <tr><td width="10%" class="modulo_table">&nbsp;</td><td>D?as h?biles del mes</td></tr>';
		$this->salida .= '  <tr><td width="10%" class="agendasab">&nbsp;</td><td>D?as S?bados</td></tr>';
		$this->salida .= '  <tr><td width="10%" class="agendadomfes">&nbsp;</td><td>D?as Domingos y Festivos</td></tr>';
		$this->salida .= '  <tr><td width="10%" class="modulo_table_calen">&nbsp;</td><td>Turnos de Agenda M?dica creados dentro del Intervalo</td></tr>';
    $this->salida .= '  </table>';
		$this->salida .= '</td></tr>';
    $this->salida .= '</table>';
    $this->salida .= ThemeCerrarTabla();
		return true;
	}


}
?>
