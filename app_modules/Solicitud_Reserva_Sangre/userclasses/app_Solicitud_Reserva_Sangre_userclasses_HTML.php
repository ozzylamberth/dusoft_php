<?php

/**
*MODULO para el Manejo de Programacion de cirugias del sistema
*
* @author Lorena Aragon
*/

// ----------------------------------------------------------------------
// SIIS v 0.1
// Copyright (C) 2003 InterSoftware Ltda.
// Emai: intersof@telesat.com.co
// ----------------------------------------------------------------------

/**
*Contiene los metodos visuales para realizar la programacion de cirugias
*/
class app_Solicitud_Reserva_Sangre_userclasses_HTML extends app_Solicitud_Reserva_Sangre_user
{
	/**
	*Constructor de la clase app_ProgramacionQX_user_HTML
	*El constructor de la clase app_ProgramacionQX_user_HTML se encarga de llamar
	*a la clase app_ProgramacionQX_user quien se encarga de el tratamiento
	*de la base de datos.
	*/

  function app_Solicitud_Reserva_Sangre_user_HTML()
	{
		$this->salida='';
		$this->app_Solicitud_Reserva_Sangre_user();
		return true;
	}

/**
* Funcion que muestra las distintas opciones del menu para el usuario
* @return boolean
*/
	function MenuConsultas(){
	  unset($_SESSION['PACIENTES']);
		unset($_SESSION['SolicitudReserva']);
		unset($_SESSION['RESERVA_SANGRE']);
		unset($_SESSION['RESERVASANGRE']);
    $this->salida .= ThemeAbrirTabla('RESERVAS DE SANGRE');		//$this->salida .= "			      <br><br>";
    $action1=ModuloGetURL('system','Menu','user','main');
		$this->salida .= "    <form name=\"forma\" action=\"$action1\" method=\"post\">";
		$this->salida .= "			<table width=\"35%\" align=\"center\" class=\"modulo_table_list\">";
		$action1=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','IdentificacionPaciente');
		$action=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','LlamaReservasSangreDiarias');
		$action2=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','LlamaCompatibilidadSangre');
		$action3=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','LlamaConsultaCruzesSanguineos');
		$action4=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','LlamaEntregaExamen');
		$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" align=\"center\">MENU</td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$action1\" class=\"link\"><b>RESERVA DE UNIDADES SANGUINEAS</b></a></td></tr>";
    $this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$action\" class=\"link\"><b>CONSULTA RESERVAS DE UNIDADES</b></a></td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$action2\" class=\"link\"><b>CRUCE DE UNIDADES</b></a></td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$action3\" class=\"link\"><b>CONSULTA CRUCES DE UNIDADES</b></a></td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$action4\" class=\"link\"><b>ENTREGA RESULTADOS DE CRUCES</b></a></td></tr>";
    $this->salida .= "			     </table><BR>";
		$this->salida .= "     <table width=\"40%\" border=\"0\" align=\"center\"class=\"normal_10\" >";
    $this->salida .= "     <tr><td align=\"center\"><input type=\"submit\" class=\"input-submit\" name=\"salir\" value=\"SALIR\"></td></tr>";
    $this->salida .= "     </table>";
		$this->salida .= "		</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
  }

	function ConsultaReservasSangreDiarias($TipoDocumento,$Documento,$grupoSanguineo,$Fecha,$estado){

    $this->salida.= ThemeAbrirTabla('CONSULTA RESERVAS DE UNIDADES');
		$this->salida .="<script language='javascript'>";
		$this->salida .= 'function mOvr(src,clrOver){';
		$this->salida .= '  src.style.background = clrOver;';
		$this->salida .= '}';
		$this->salida .= 'function mOut(src,clrIn){';
		$this->salida .= '  src.style.background = clrIn;';
		$this->salida .= '}';
		$this->salida .= '</script>';
		$accion=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','LlamaReservasSangreDiarias',array("DiaEspe"=>$_REQUEST['DiaEspe']));
		$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
		$this->salida .= "<br><table class=\"modulo_table_list\" border=\"0\" width=\"80%\" align=\"center\">";
		$this->salida .= "<tr class=\"modulo_table_title\">";
		$this->salida .= "<td align = center colspan = 2 >CONSULTA RESERVA DE SANGRE</td>";
		$this->salida .= "</tr>";
		$this->salida .= "<tr class=\"modulo_table_list_title\">";
		$this->salida .= "<td align = left >CRITERIOS DE BUSQUEDA:</td>";
		$this->salida .= "<td align = left >SELECCIONE LA FECHA:</td>";
		$this->salida .= "</tr>";
		$this->salida .= "<tr class=\"modulo_list_claro\" >";
		$this->salida .= "<td width=\"40%\" >";
		$this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\">";
		$this->salida .= "<tr><td>";
		$this->salida .= "<table width=\"80%\" align=\"center\" border=\"0\">";
		$this->salida .= "<SCRIPT>";
		$this->salida .= "function Revisar(frm,x){";
		$this->salida .= "  if(x==true){";
		$this->salida .= "frm.Fecha.value='TODAS LAS FECHAS'";
		$this->salida .= "  }";
		$this->salida .= "else{";
		$this->salida .= "frm.Fecha.value=''";
		$this->salida .= "}";
		$this->salida .= "}";
		$this->salida .= "</SCRIPT>";
		$this->salida .= "<tr><td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"TipoDocumento\" class=\"select\">";
		$tipo_id=$this->tipo_id_paciente();
		$this->BuscarIdPaciente($tipo_id,'');
		$this->salida .= "</select></td></tr>";
		$this->salida .= "<tr><td class=\"label\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" value=\"".$_REQUEST['Documento']."\" maxlength=\"32\"></td></tr>";
		$this->salida .= "<tr><td class=\"label\">ABO / Rh </td><td><select name=\"grupoSanguineo\" class=\"select\">";
		$facts=$this->ConsultaFactorRh();
		$this->GrupoSanguineo($facts,'False',$_REQUEST['grupoSanguineo']);
		$this->salida .= "</select></td></tr>";
		//buscra por orden
		if($_REQUEST['allfecha']){
      $valorFecha='TODAS LAS FECHAS';
		}else{
		  if(!empty($_REQUEST['DiaEspe'])){
			  $valorFecha=$_REQUEST['DiaEspe'];
			}else{
        $valorFecha=$Fecha;
			}
		}
		$this->salida .= "<tr><td class=\"label\">FECHA</td><td><input type=\"text\" readonly class=\"input-text\" name=\"Fecha\" value = \"$valorFecha\"></td></tr>";
		$this->salida .= "<tr class=\"label\">";
		$this->salida .= "<td align = left >TODAS LAS FECHAS</td>";
		if($_REQUEST['allfecha']){
      $chec='checked';
		}
		$this->salida.="  <td align=\"left\"><input type = checkbox name=\"allfecha\" onclick=\"Revisar(this.form,this.checked)\" $chec></td>";
		$this->salida .= "</tr>";
    $this->salida .= "<tr>";
		$this->salida .= "<td valign=\"top\" class=\"label\">ESTADO</td>";
    $this->salida .= "<td class=\"label\">";
    $this->salida .= "<table>";
		if($estado==2){
      $var2='checked';
		}elseif($estado==3){
      $var3='checked';
		}else{
      $var1='checked';
		}
		$this->salida .= "<tr><td class=\"label\">ACTIVA</td><td><input type=\"radio\" name=\"estado\" value=\"1\" $var1></td></tr>";
		$this->salida .= "<tr><td class=\"label\">CANCELADA</td><td><input type=\"radio\" name=\"estado\" value=\"3\" $var3></td></tr>";
		$this->salida .= "<tr><td class=\"label\">VENCIDAS</td><td><input type=\"radio\" name=\"estado\" value=\"2\" $var2></td></tr>";
		$this->salida .= "</table>";
    $this->salida .= "</td>";
		$this->salida .= "</tr>";
    $this->salida .= "<tr><td colspan = 2 align=\"center\" ><table>";
		$this->salida .= "<tr><td align=\"right\" ><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar_Reserva\" value=\"BUSCAR\"></td>";
		$this->salida .= "</form>";
		$actionM=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','MenuConsultas');
		$this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
		$this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"><br></td></form>";
		$this->salida .= "</tr>";
		$this->salida .= "</table></td></tr>";
		$this->salida .= "</td></tr></table>";
		$this->salida .= "</table>";
		$this->salida .= "</td>";
		$this->salida .= "<td>";
		$this->salida .= "<BR><table border=\"0\" width=\"80%\" align=\"center\">";
    //aqui inserte lo de lorena
		$this->salida .= "<tr><td>";
		//$_REQUEST['DiaEspe'];
		$this->salida.="\n".'<script>'."\n";
		$this->salida.='function year1(t)'."\n";
		$this->salida.='{'."\n";
		$this->salida.='window.location.href="Contenido.php?year="+t.elements[0].value+"&meses="+t.elements[1].value+"';
		foreach($_REQUEST as $v=>$v1){
			if($v!='year' and $v!='meses' and $v!='DiaEspe'){
				if (is_array($v1)){
					foreach($v1 as $k2=>$v2){
						if (is_array($v2)){
							foreach($v2 as $k3=>$v3){
								if(is_array($v3)){
									foreach($v3 as $k4=>$v4){
										$this->salida .= "&$v" . "[$k2][$k3][$k4]=$v4";
									}
								}else{
									$this->salida .= "&$v" . "[$k2][$k3]=$v3";
								}
							}
						}else{
							$this->salida .= "&$v" . "[$k2]=$v2";
						}
					}
				}else{
    			$this->salida .= "&$v=$v1";
				}
		  }
		}
		$this->salida.='";'."\n";
		$this->salida.='}'."\n";
		$this->salida.='</script>';

		$this->salida .='<form name="cosa">';
		$this->salida .="<table border=\"0\" width=\"80%\" align=\"center\">";
		$this->salida .='<tr align="center">';
		$this->salida .="<td class=\"label\">AÑO</td><td><select name=\"year\" onchange=\"year1(this.form)\" class=\"select\">";
		if(empty($_REQUEST['year'])){
				$_REQUEST['year']=date("Y");
				$this->AnosAgenda(True,$_REQUEST['year']);
		}else{
			$this->AnosAgenda(true,$_REQUEST['year']);
			$year=$_REQUEST['year'];
		}
		$this->salida .= "</select></td>";
		$this->salida .="<td class=\"label\">MES</td><td><select name=\"mes\" onchange=\"year1(this.form)\" class=\"select\">";
		if(empty($_REQUEST['meses'])){
			$mes=$_REQUEST['meses']=date("m");
			$this->MesesAgenda(True,$year,$mes);
		}else{
			$this->MesesAgenda(True,$year,$_REQUEST['meses']);
			$mes=$_REQUEST['meses'];
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
		$this->salida .= "</table>";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table><br>";
		$reservas=$this->ReservasSangreDiarias($TipoDocumento,$Documento,$grupoSanguineo,$Fecha,$estado);
		if($reservas){
			$this->salida .= "	   <table width=\"98%\" border=\"0\" class=\"modulo_table_list\" align=\"center\">";
			$this->salida .= "	   <tr class=\"modulo_table_list_title\">";
			$this->salida .= "	   <td>No. RESERVA</td>";
			$this->salida .= "	   <td>Id. PACIENTE</td>";
			$this->salida .= "	   <td>PACIENTE</td>";
			$this->salida .= "	   <td>FECHA RESERVA</td>";
			$this->salida .= "	   <td>DEPARTAMENTO</td>";
			$this->salida .= "	   <td>UBICACION</td>";
			$this->salida .= "	   <td>ABO</td>";
			$this->salida .= "	   <td>Rh</td>";
			$this->salida .= "	   <td>&nbsp;</td>";
			$this->salida .= "	   <td>&nbsp;</td>";
			$this->salida .= "	   <td>&nbsp;</td>";
			$y=0;
			$backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
			for($i=0;$i<sizeof($reservas);$i++)
			{
				if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
				$this->salida.="<tr  class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>";
				$this->salida .= "    <td>".$reservas[$i]['solicitud_reserva_sangre_id']."</td>";
				$this->salida .= "	   <td>".$reservas[$i]['tipo_id_paciente']." ".$reservas[$i]['paciente_id']."</td>";
				$this->salida .= "	   <td>".$reservas[$i]['nombre']."</td>";
				if($reservas[$i]['sw_estado']==2){
				  $action=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','CambioFechaReserva',array("reservaId"=>$reservas[$i]['solicitud_reserva_sangre_id'],"tipoId"=>$reservas[$i]['tipo_id_paciente'],"pacienteId"=>$reservas[$i]['paciente_id'],
					"nombrePac"=>$reservas[$i]['nombre'],"fechaReserva"=>$reservas[$i]['fecha_hora_reserva'],"departamento"=>$reservas[$i]['dpto'],"Ubicacion"=>$reservas[$i]['ubicacion_paciente'],"grupo"=>$reservas[$i]['grupo_sanguineo'],"rh"=>$reservas[$i]['rh'],
					"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"grupoSanguineo"=>$grupoSanguineo,"Fecha"=>$Fecha,"estado"=>$estado));
				  $this->salida .= "	   <td align=\"center\"><a href=\"$action\"><b>".$reservas[$i]['fecha_hora_reserva']."</b></a></td>";
				}else{
          $this->salida .= "	   <td>".$reservas[$i]['fecha_hora_reserva']."</td>";
				}
				$this->salida .= "	   <td>".$reservas[$i]['dpto']."</td>";
				$this->salida .= "	   <td>".$reservas[$i]['ubicacion_paciente']."</td>";
				$this->salida .= "	   <td>".$reservas[$i]['grupo_sanguineo']."</td>";
				if($reservas[$i]['rh']=='+'){
				$this->salida .= "	   <td>POSITIVO</td>";
				}elseif($reservas[$i]['rh']=='-'){
        $this->salida .= "	   <td class=\"label_error\">NEGATIVO</td>";
				}else{
        $this->salida .= "	   <td>&nbsp;</td>";
				}
				if($reservas[$i]['sw_estado']==1){
					$vector=array("reservaId"=>$reservas[$i]['solicitud_reserva_sangre_id'],"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"grupoSanguineo"=>$grupoSanguineo,"Fecha"=>$Fecha,"estado"=>$estado);
					$action=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','LlamaConfirmarAccion',array("Titulo"=>'CANCELAR RESERVA UNIDAD SANGUINEA',"mensaje"=>'Esta Seguro que desea Cancelar la Reserva de unidades',"boton1"=>'ACEPTAR',"boton2"=>'CANCELAR',"arreglo"=>$vector,"c"=>'app',"m"=>'Solicitud_Reserva_Sangre',"me"=>'CancelarReservaSangreTotal',"me2"=>'LlamaReservasSangreDiarias'));
					$this->salida .= "	   <td align=\"center\"><a href=\"$action\"><img border=\"0\" src=\"".GetThemePath()."/images/delete.gif\" title=\"Cancelar Reserva\"><a></td>";
				}else{
          $this->salida .= "	   <td align=\"center\">&nbsp;</td>";
				}
				$action=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','LlamaDetalleReservaSangrePac',array("reservaId"=>$reservas[$i]['solicitud_reserva_sangre_id'],"tipoId"=>$reservas[$i]['tipo_id_paciente'],"pacienteId"=>$reservas[$i]['paciente_id'],
				"nombrePac"=>$reservas[$i]['nombre'],"fechaReserva"=>$reservas[$i]['fecha_hora_reserva'],"departamento"=>$reservas[$i]['dpto'],"Ubicacion"=>$reservas[$i]['ubicacion_paciente'],"grupo"=>$reservas[$i]['grupo_sanguineo'],"rh"=>$reservas[$i]['rh'],
				"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"grupoSanguineo"=>$grupoSanguineo,"Fecha"=>$Fecha,"estado"=>$estado));
				$this->salida .= "	      <td align=\"center\"><a href=\"$action\"><img border=\"0\" src=\"".GetThemePath()."/images/pconsultar.png\" title=\"Consultar Reserva\"></a></td>";
				$confirmarMed=$this->ConfirmacionMedico();
				//if($confirmarMed==1){
					$action=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','LlamaDetalleReservaSangrePac',array("reservaId"=>$reservas[$i]['solicitud_reserva_sangre_id'],"tipoId"=>$reservas[$i]['tipo_id_paciente'],"pacienteId"=>$reservas[$i]['paciente_id'],
					"nombrePac"=>$reservas[$i]['nombre'],"fechaReserva"=>$reservas[$i]['fecha_hora_reserva'],"departamento"=>$reservas[$i]['dpto'],"Ubicacion"=>$reservas[$i]['ubicacion_paciente'],"grupo"=>$reservas[$i]['grupo_sanguineo'],"rh"=>$reservas[$i]['rh'],
					"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"grupoSanguineo"=>$grupoSanguineo,"Fecha"=>$Fecha,"estado"=>$estado,"origen"=>1));
				  $this->salida .= "	      <td align=\"center\"><a href=\"$action\"><img border=\"0\" src=\"".GetThemePath()."/images/endturn.png\" title=\"Confirmar Unidades\"></a></td>";
				//}else{
          //$this->salida .= "	      <td align=\"center\">&nbsp;</td>";
				//}
				$this->salida .= "    </tr>";
				$y++;
			}
			$this->salida .= "		    </table>";
			$this->salida .=$this->RetornarBarra(1);
		}
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function DetalleReservaSangrePac($reservaId,$tipoId,$pacienteId,$nombrePac,$fechaReserva,$departamento,$Ubicacion,$grupo,$rh,$origen,$destino){
		$this->salida.= ThemeAbrirTabla('DETALLE DE LA RESERVA DE UNIDADES Y OTROS SERVICIOS');
		$accion=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','LlamaReservasSangreDiarias',array("TipoDocumento"=>$_REQUEST['TipoDocumento'],"Documento"=>$_REQUEST['Documento'],"grupoSanguineo"=>$_REQUEST['grupoSanguineo'],"Fecha"=>$_REQUEST['Fecha'],"estado"=>$_REQUEST['estado'],"origen"=>$origen,"destino"=>$destino));
		$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
		$this->salida .= "	  <table width=\"65%\" border=\"0\" class=\"normal_10\" align=\"center\">";
		$this->salida .= "    <tr><td>";
		$this->salida .= "    <fieldset><legend class=\"field\">DATOS PRINCIPALES DE LA RESERVA</legend>";
		$this->salida .= "    <BR><table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
		$this->salida .= "		<tr><td width=\"25%\"></tr></td>";
		$this->salida .= "		<tr class=\"modulo_list_oscuro\">";
		$this->salida .= "		<td class=\"label\">No. RESERVA</td>";
		$this->salida .= "		<td>$reservaId</td>";
		$this->salida .= "		<td class=\"label\">FECHA RESERVA</td>";
		$this->salida .= "		<td>$fechaReserva</td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td class=\"label\">ID PACIENTE</td>";
		$this->salida .= "		<td>$tipoId $pacienteId</td>";
		$this->salida .= "		<td class=\"label\">PACIENTE</td>";
		$this->salida .= "		<td>$nombrePac</td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td class=\"label\">DEPARTAMENTO</td>";
		$this->salida .= "		<td>$departamento</td>";
		$this->salida .= "		<td class=\"label\">UBICACION PACIENTE</td>";
		$this->salida .= "		<td>$Ubicacion</td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr class=\"modulo_list_oscuro\">";
		$this->salida .= "		<td class=\"label\">ABO</td>";
		$this->salida .= "		<td>$grupo</td>";
		$this->salida .= "		<td class=\"label\">Rh</td>";
		if($rh=='+'){
    $this->salida .= "		<td>POSITIVO</td>";
		}elseif($rh=='-'){
    $this->salida .= "		<td class=\"label_error\">NEGATIVO</td>";
		}else{
    $this->salida .= "		<td>&nbsp;</td>";
		}
		$this->salida .= "		</tr>";
		$this->salida .= "		</table><BR>";
		$this->salida .= "		</fieldset></td></tr>";
		$this->salida .= "		</table><br>";
		$reservaDetalle=$this->ReservasSangreDiariasDetalle($reservaId);
		if($reservaDetalle){
			$this->salida .= "	   <table width=\"50%\" border=\"0\" class=\"normal_10\" align=\"center\">";
			$this->salida .= "	   <tr class=\"modulo_table_list_title\">";
			$this->salida .= "	   <td>TIPO COMPONENTE</td>";
			$this->salida .= "	   <td>CANTIDAD</td>";
      if($origen==1){
			$this->salida .= "	   <td>CONFIRMACION</td>";
      }else{
      $this->salida .= "	   <td>CONFIRMADO</td>";
			}
			$this->salida .= "		</tr>";
			//$this->salida .= "	   <td>CANCELAR</td>";
			$y=0;
			for($i=0;$i<sizeof($reservaDetalle);$i++){
				if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
				$this->salida .= "	   <tr class=\"$estilo\">";
				$this->salida .= "	   <td>".$reservaDetalle[$i]['componente']."</td>";
				$this->salida .= "	   <td>".$reservaDetalle[$i]['cantidad_componente']."</td>";
				$vector=array("reservaId"=>$reservaId,"tipoId"=>$tipoId,"pacienteId"=>$pacienteId,
				"nombrePac"=>$nombrePac,"fechaReserva"=>$fechaReserva,"departamento"=>$departamento,"Ubicacion"=>$Ubicacion,"grupo"=>$grupo,"rh"=>$rh,"tipoComponente"=>$reservaDetalle[$i]['tipo_componente_id']);
				$action=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','LlamaConfirmarAccion',array("Titulo"=>'CANCELAR RESERVA UNIDAD SANGUINEA',"mensaje"=>'Esta Seguro que desea Eliminar este Reserva de unidades',"boton1"=>'ACEPTAR',"boton2"=>'CANCELAR',"arreglo"=>$vector,"c"=>'app',"m"=>'Solicitud_Reserva_Sangre',"me"=>'CancelarReservaSangre',"me2"=>'LlamaDetalleReservaSangrePac'));
				//$this->salida .= "	   <td align=\"center\"><a href=\"$action\"><img border=\"0\" src=\"".GetThemePath()."/images/delete.gif\"><a></td>";
				if($origen==1){
				  if($reservaDetalle[$i]['sw_estado']!='2'){
					$action=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','ConfirmarComponentesSangre',array("reservaId"=>$reservaId,"tipoId"=>$tipoId,"pacienteId"=>$pacienteId,
				  "nombrePac"=>$nombrePac,"fechaReserva"=>$fechaReserva,"departamento"=>$departamento,"Ubicacion"=>$Ubicacion,"grupo"=>$grupo,"rh"=>$rh,"tipoComponente"=>$reservaDetalle[$i]['tipo_componente_id'],"destino"=>$destino));
					$this->salida .= "	   <td align=\"center\"><a href=\"$action\"><img border=\"0\" src=\"".GetThemePath()."/images/endturn.png\" title=\"Confirmar Unidad\"><a></td>";
					}else{
						$this->salida .= "	   <td align=\"center\">Si</td>";
					}
				}else{
          if($reservaDetalle[$i]['sw_estado']!='2'){
					  $this->salida .= "	   <td align=\"center\">No</td>";
					}else{
            $this->salida .= "	   <td align=\"center\">Si</td>";
					}
				}
				$this->salida .= "	   </tr>";
				$y++;
			}
			$this->salida .= "	   </table><BR>";
		}

		$servicios=$this->OtrosServiciosReservaInsertados($reservaId);
		if($servicios){
			$this->salida .= "	   <table width=\"70%\" border=\"0\" class=\"normal_10\" align=\"center\">";
			$this->salida .= "	   <tr class=\"modulo_table_list_title\">";
			$this->salida .= "	   <td>SERVICIOS</td>";
      $this->salida .= "	   </tr>";
			for($i=0;$i<sizeof($servicios);$i++){
				if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
				$this->salida .= "	   <tr class=\"$estilo\">";
				$this->salida .= "	   <td>".$servicios[$i]['descripcion']."</td>";
				$this->salida .= "	   </tr>";
				$y++;
			}
			$this->salida .= "	   </table><BR>";
		}
		$this->salida .= "	   <table width=\"50%\" border=\"0\" class=\"normal_10\" align=\"center\">";
		$this->salida .= "	   <tr><td align=\"center\"><input type=\"submit\" value=\"REGRESAR\" name=\"regresar\" class=\"input-submit\"></td></tr>";
		$this->salida .= "	   </table>";
		$this->salida .= "	   </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	/**
	* Se utilizada listar en el combo los diferentes tipo de identifiacion de los pacientes
	* @access private
	* @return void
	*/
	function BuscarIdPaciente($tipo_id,$TipoId='')
	{
		foreach($tipo_id as $value=>$titulo)
		{
			if($value==$TipoId){
				$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
			}else{
				$this->salida .=" <option value=\"$value\">$titulo</option>";
			}
		}
	}

/**
* Funcion que se encarga de listar los nombres de los tipos de origen de una cirugia
* @return array
* @param array codigos y valores de los tipos de origen de la base de datos
* @param boolean indicador de selecion de un elemento en el objeto donde se imprimen los tipos de origen
* @param string elemento seleccionado en el objeto donde se imprimen los tipo de origen
*/
	function GrupoSanguineo($arreglo,$Seleccionado='False',$valor=''){

		switch($Seleccionado){
			case 'False':{
        $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
				for($i=0;$i<sizeof($arreglo);$i++){
          $value=$arreglo[$i]['grupo_sanguineo'].'/'.$arreglo[$i]['rh'];
					if($value==$valor){
					$this->salida .=" <option value=\"$value\" selected>".$arreglo[$i]['grupo_sanguineo']."   ".$arreglo[$i]['descripcion']."</option>";
				  }else{
            $this->salida .=" <option value=\"$value\">".$arreglo[$i]['grupo_sanguineo']."   ".$arreglo[$i]['descripcion']."</option>";
				  }
			  }
			  break;
		  }case 'True':{
			  for($i=0;$i<sizeof($arreglo);$i++){
				  $value=$arreglo[$i]['grupo_sanguineo'].'/'.$arreglo[$i]['rh'];
				  if($value==$valor){
				    $this->salida .=" <option value=\"$value\" selected>".$arreglo[$i]['grupo_sanguineo']."   ".$arreglo[$i]['descripcion']."</option>";
				  }
				  $this->salida .=" <option value=\"$value\">".$arreglo[$i]['grupo_sanguineo']."   ".$arreglo[$i]['descripcion']."</option>";
			  }
			  break;
		  }
	  }
	}


/**
* Funcion que se encarga de visualizar la forma para capturar los datos principales para la insercion de una programacion
* @return boolean
* @param string tipo de documento del paciente
* @param string numero del documento del paciente
* @param string sitio a donde se dirige la funcion
* @param string sitio desde donde se llama la funcion
*/
	function IdentificacionPaciente($TipoId,$Documento){
    $this->salida .= ThemeAbrirTabla('IDENTIFICACION DEL PACIENTE');
    $action=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','PedirDatosPaciente');
		$this->salida .= "<form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->salida .= "<table width=\"80%\" align=\"center\" border=\"0\">\n";
		$this->salida .= "<tr><td colspan=\"4\" align=\"center\">";
		$this->salida .=  $this->SetStyle("MensajeError");
		$this->salida .= "</td></tr>";
		$this->salida .= "<tr><td class=\"modulo_list_oscuro\">";
		$this->salida .= "   <BR><table width=\"90%\" border=\"0\" align=\"center\" class=\"normal_10\" >";
    $this->salida .= "		<tr class=\"modulo_table_list_title\"><td colspan=\"4\" class=\"modulo_table_list_title\">DATOS DEL PACIENTE</td></tr>";
		$this->salida .= "		<tr class=\"modulo_list_claro\"><td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"TipoDocumento\" class=\"select\">";
		$tipo_id=$this->tipo_id_paciente();
		$this->Mostrar($tipo_id,'False',$TipoId);
		$this->salida .= "    </select></td>";
		$this->salida .= "		 <td class=\"".$this->SetStyle("Documento")."\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" value=\"$Documento\"></td>";
		$this->salida .= "		 </tr>";
		$this->salida .= "		<tr class=\"modulo_list_claro\"><td class=\"label\">DEPARTAMENTO: </td><td><select name=\"departamento\" class=\"select\">";
		$departamentos=$this->tiposdepartamentos();
		$this->MostrasSelect($departamentos,'False',$departamento);
		$this->salida .= "    </select></td>";
    $this->salida .= "		 <td class=\"".$this->SetStyle("ubicacionPaciente")."\">UBICACION PACIENTE: </td><td><input type=\"text\" class=\"input-text\" name=\"ubicacionPaciente\" maxlength=\"32\" value=\"$ubicacionPaciente\"></td>";
    $this->salida .= "		 </tr>";
		$this->salida .= "		 <tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("responsableSolicitud")."\">RESPONSABLE SOLICITUD: </td><td colspan=\"3\"><input type=\"text\" class=\"input-text\" name=\"responsableSolicitud\" size=\"40\" value=\"$responsableSolicitud\"></td></tr>";
    $this->salida .= "    </table><BR>";
    $this->salida .= "</td></tr>";
		$this->salida .= "</table>";
		$this->salida .= "   <table width=\"90%\" border=\"0\" align=\"center\"class=\"normal_10\" >";
    $this->salida .= "		<tr><td align=\"right\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ACEPTAR\"></td>";
		$this->salida .= "</form>";
		$action1=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','MenuConsultas');
		$this->salida .= "  <form name=\"forma\" action=\"$action1\" method=\"post\">";
		//$this->salida .= "   <table width=\"90%\" border=\"0\" align=\"center\"class=\"normal_10\" >";
		$this->salida .= "	  <td align=\"left\"><input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\"></td></tr>";
    $this->salida .= "   </table>";
		$this->salida .= "</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
/**
* Funcion que se encarga de visualizar un error en un campo
* @return string
*/
	function SetStyle($campo){
		if ($this->frmError[$campo] || $campo=="MensajeError"){
		  if ($campo=="MensajeError"){
				return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
			}
			return ("label_error");
		}
		return ("label");
	}
/**
* Funcion que se encarga de listar los elementos pasados por parametros
* @return array
* @param array codigos y valores que vienen en el arreglo
* @param boolean indicador de selecion de un elemento en el objeto donde se imprimen los valores del arreglo
* @param string elemento seleccionado en el objeto donde se imprimen los valores
*/
	function Mostrar($arreglo,$Seleccionado='False',$Defecto=''){

	  switch($Seleccionado){
			case 'False':{
			  foreach($arreglo as $value=>$titulo){
					if($value==$Defecto){
						$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
					}else{
						$this->salida .=" <option value=\"$value\">$titulo</option>";
					}
				}
				break;
		  }
			case 'True':{
				foreach($arreglo as $value=>$titulo){
					if($value==$Defecto){
						$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
					}else{
						$this->salida .=" <option value=\"$value\">$titulo</option>";
					}
				}
				break;
			}
		}
	}

	function ListadoReservasPacientes($TipoId,$PacienteId,$departamento,$ubicacionPaciente,$responsableSolicitud){
    $this->salida .= ThemeAbrirTabla('RESERVAS DEL PACIENTE');
    $action=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','IdentificacionPaciente');
		$this->salida .= "<form name=\"forma\" action=\"$action\" method=\"post\">";
		$Nombres=$this->BuscarNombresPaciente($TipoId,$PacienteId);
		$FechaNacimiento=$this->Edad($TipoId,$PacienteId);
		$EdadArr=CalcularEdad($FechaNacimiento,$FechaFin);
		$departamentonom=$this->nombredpto($departamento);
		$this->salida .= "	   <table width=\"65%\" border=\"0\" class=\"normal_10\" align=\"center\">";
		$this->salida .= "    <tr><td>";
		$this->salida .= "    <fieldset><legend class=\"field\">DATOS PRINCIPALES DE LA RESERVA</legend>";
		$this->salida .= "    <BR><table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
    $this->salida .= "		<tr><td width=\"25%\"></tr></td>";
		$this->salida .= "		<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td class=\"label\">ID PACIENTE</td>";
		$this->salida .= "		<td>$TipoId $PacienteId</td>";
		$this->salida .= "		<td class=\"label\">PACIENTE</td>";
		$this->salida .= "		<td>$Nombres</td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr class=\"modulo_list_oscuro\">";
		$this->salida .= "		<td class=\"label\">EDAD</td>";
		$this->salida .= "		<td colspan=\"3\">".$EdadArr['edad_aprox']."</td>";
    $this->salida .= "		</tr>";
		$this->salida .= "		<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td class=\"label\">DEPARTAMENTO</td>";
		$this->salida .= "		<td>".$departamentonom['descripcion']."</td>";
		$this->salida .= "		<td class=\"label\">UBICACION PACIENTE</td>";
		$this->salida .= "		<td>$ubicacionPaciente</td>";
    $this->salida .= "		</tr>";
		$this->salida .= "		<tr class=\"modulo_list_oscuro\">";
		$this->salida .= "		<td class=\"label\">RESPONSABLE SOLICITUD</td>";
		$this->salida .= "		<td colspan=\"3\">$responsableSolicitud</td>";
    $this->salida .= "		</tr>";
		$this->salida .= "		</table><BR>";
		$this->salida .= "		</fieldset></td></tr>";
    $this->salida .= "		</table><br>";
		$reservasAnt=$this->ReservasAnterioresPaciente($TipoId,$PacienteId);
		if($reservasAnt){
      $this->salida .= "	   <table width=\"90%\" border=\"0\" class=\"modulo_table_list\" align=\"center\">";
      $this->salida .= "	   <tr class=\"modulo_table_list_title\">";
			$this->salida .= "	   <td>FECHA</td>";
			$this->salida .= "	   <td>DEPARTAMENTO</td>";
			$comp =$this->ConsultaComponente();
			$i=0;
			while($i<sizeof($comp)){
        $this->salida .= "	   <td>".$comp[$i]['componente']."</td>";
			  $i++;
			}
			$this->salida .= "	   </tr>";
			$y=0;
			for($i=0;$i<sizeof($reservasAnt);$i++){
			  if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
        $this->salida .= "	   <tr class=\"$estilo\">";
				$this->salida .= "	   <td>".$reservasAnt[$i]['fecha_hora_reserva']."</td>";
				$this->salida .= "	   <td>".$reservasAnt[$i]['descripcion']."</td>";
        $componentes=$this->ComponentesSangre($reservasAnt[$i]['solicitud_reserva_sangre_id']);
				$j=0;
				while($j<sizeof($comp)){
				  $l=0;
				  for($x=0;$x<sizeof($componentes);$x++){
						if($comp[$j]['hc_tipo_componente']==$componentes[$x]['tipo_componente_id']){
							$this->salida .= "	   <td>".$componentes[$x]['cantidad_componente']."</td>";
							$l=1;
						}
					}
					$j++;
					if($l!=1){
            $this->salida .= "	   <td>0</td>";
					}
				}
				$this->salida .= "	   </tr>";
				$y++;
			}
      $this->salida .= "	   </table>";
		}
		$this->salida .= "<table width=\"90%\" align=\"center\" border=\"0\">\n";
		$actionAdicion=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','LlamaReservaSangre',array("TipoId"=>$TipoId,"PacienteId"=>$PacienteId,"departamento"=>$_REQUEST['departamento'],"ubicacionPaciente"=>$_REQUEST['ubicacionPaciente'],"responsableSolicitud"=>$_REQUEST['responsableSolicitud']));
    $this->salida .= "<tr><td align=\"right\"><a href=\"$actionAdicion\" class=\"link\"><b>Nueva Reserva</b></a></td></tr>";
		$this->salida .= "<tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Salir\" value=\"SALIR\"></td></tr>";
    $this->salida .= "</table>";
    $this->salida .= "</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}


	function LlamaReserva_Sangre_qx($TipoId,$PacienteId,$departamento,$ubicacionPaciente,$responsableSolicitud){

		$ProgramacionId=$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO'];
		$this->salida .= ThemeAbrirTabla('RESERVA DE SANGRE y/o CRUZADA');
    $this->salida.="<SCRIPT>";
    $this->salida.="function ponerFecha(frm,valor,valorFecha){";
    $this->salida.="  if(valor!=1){";
		$this->salida.="    frm.fecha_reserva.value=' ';";
		$this->salida.="  }else{";
    $this->salida.="    frm.fecha_reserva.value=valorFecha;";
		$this->salida.="  }";
		$this->salida.="}";
		$this->salida.="</SCRIPT>";
		$accion=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','GuardarReservaSangre',array("TipoId"=>$TipoId,"PacienteId"=>$PacienteId,"departamento"=>$departamento,"ubicacionPaciente"=>$ubicacionPaciente,"responsableSolicitud"=>$responsableSolicitud));
		$this->salida.="<form name=\"forma\" action=\"$accion\" method=\"post\">";
		$Nombres=$this->BuscarNombresPaciente($TipoId,$PacienteId);
		$FechaNacimiento=$this->Edad($TipoId,$PacienteId);
		$EdadArr=CalcularEdad($FechaNacimiento,$FechaFin);
		$departamentonom=$this->nombredpto($departamento);
		$this->salida .= "	   <table width=\"65%\" border=\"0\" class=\"normal_10\" align=\"center\">";
		$this->salida .= "    <tr><td>";
		$this->salida .= "    <fieldset><legend class=\"field\">DATOS PRINCIPALES DE LA RESERVA</legend>";
		$this->salida .= "    <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\">";
    $this->salida .= "		<tr><td width=\"25%\"></tr></td>";
		$this->salida .= "		<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td class=\"label\">ID PACIENTE</td>";
		$this->salida .= "		<td>$TipoId $PacienteId</td>";
		$this->salida .= "		<td class=\"label\">PACIENTE</td>";
		$this->salida .= "		<td>$Nombres</td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr class=\"modulo_list_oscuro\">";
		$this->salida .= "		<td class=\"label\">EDAD</td>";
		$this->salida .= "		<td colspan=\"3\">".$EdadArr['edad_aprox']."</td>";
    $this->salida .= "		</tr>";
		$this->salida .= "		<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td class=\"label\">DEPARTAMENTO</td>";
		$this->salida .= "		<td>".$departamentonom['descripcion']."</td>";
		$this->salida .= "		<td class=\"label\">UBICACION PACIENTE</td>";
		$this->salida .= "		<td>$ubicacionPaciente</td>";
    $this->salida .= "		</tr>";
		$this->salida .= "		<tr class=\"modulo_list_oscuro\">";
		$this->salida .= "		<td class=\"label\">RESPONSABLE SOLICITUD</td>";
		$this->salida .= "		<td colspan=\"3\">$responsableSolicitud</td>";
    $this->salida .= "		</tr>";
		$this->salida .= "		</table>";
		$this->salida .= "		</fieldset></td></tr>";
    $this->salida .= "		</table>";
    $this->salida.="<table width=\"85%\" border=\"0\" align=\"center\">";
		$this->salida .= "<tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "</td><tr>";
		$this->salida.="<tr class=\"modulo_table_list_title\">";
		$this->salida.="<td align=\"center\">SOLICITUD DE RESERVA DE SANGRE</td>";
		$this->salida.="</tr>";
    $this->salida.="<tr class=\"modulo_list_oscuro\"><td>";
		$this->salida.="       <table width=\"95%\" border=\"0\" align=\"center\">";
		$this->salida.="        <tr class=\"modulo_table_list_title\">";
		$this->salida.="        <td align=\"left\" colspan =\"4\">GRUPO SANGUINEO DEL PACIENTE</td>";
		$this->salida.="        </tr>";
		$this->salida.="        <tr class = modulo_list_claro>";
		$this->salida.="        <td class=\"".$this->SetStyle("grupo_sanguineo")."\">FACTOR </td>";
		$factorPaciente=$this->FactorPaciente($TipoId,$PacienteId);
		if($factorPaciente){
		  if($factorPaciente['rh']=='-'){
        $clase='label_error';
				$this->salida.="        <td class=\"".$this->SetStyle("grupo_sanguineo")."\">".$factorPaciente['grupo_sanguineo']." / <label class=\"$clase\">NEGATIVO</label></td>";
			}elseif($factorPaciente['rh']=='+'){
        $clase='label';
				$this->salida.="        <td class=\"".$this->SetStyle("grupo_sanguineo")."\">".$factorPaciente['grupo_sanguineo']." / <label class=\"$clase\">POSITIVO</label></td>";
			}else{
        $this->salida.="        <td class=\"".$this->SetStyle("grupo_sanguineo")."\">&nbsp;</td>";
			}
			//$actionFactor=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','FactorSanguineoPaciente',array("TipoId"=>$TipoId,"PacienteId"=>$PacienteId,"cambio"=>1));
			//<a href=\"$actionFactor\" class=\"link\"><b>Modificar Factor</b></a>
			$this->salida.="        <td colspan=\"2\"><input type=\"submit\" value=\"Modificar Factor\" name=\"ModificarFactor\" class=\"input-submit\"></td>";
		}else{
      $this->salida.="        <td>SIN REGISTRO</td>";
			//$actionFactor=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','FactorSanguineoPaciente',array("TipoId"=>$TipoId,"PacienteId"=>$PacienteId));
			//$this->salida.="        <td colspan=\"2\"><a href=\"$actionFactor\" class=\"link\"><b>Seleccionar Factor</b></a></td>";
			$this->salida.="        <td colspan=\"2\"><input type=\"submit\" value=\"Seleccionar Factor\" name=\"SeleccionFactor\" class=\"input-submit\"></td>";
		}
		/*$this->salida .= "      <td><select name=\"grupo_sanguineo\" class=\"select\" $desabilitado>";
		$facts=$this->ConsultaFactor();
		$this->MostrasSelect($facts,'False',$_REQUEST['grupo_sanguineo']);
		$this->salida .= "      </select></td>";
		$this->salida.="        <td align=\"left\" class=\"".$this->SetStyle("rh")."\">Rh </td>";
    $this->salida.="        <td align=\"left\" >";
		$this->salida.="        <select size=\"1\" name =\"rh\" class =\"select\" $desabilitado>";
		if($_REQUEST['rh']=='+'){
      $checkeado='selected';
		}else{
      $checkeado1='selected';
		}
		$this->salida.="        <option value = -1>-Seleccione-</option>";
    $this->salida.="        <option value=\"+\" $checkeado> Positivo </option>";
		$this->salida.="        <option value=\"-\" $checkeado1> Negativo </option>";
    $this->salida.="        </select>";
		$this->salida.="        </td>";
		*/
		$this->salida.="        <input type=\"hidden\" name=\"grupo_sanguineo\" value=\"".$factorPaciente['grupo_sanguineo']."\">";
		$this->salida.="        <input type=\"hidden\" name=\"rh\" value=\"".$factorPaciente['rh']."\">";
		$this->salida.="        </tr>";

		$this->salida.="        <tr class=\"modulo_table_list_title\">";
		$this->salida.="        <td width=\"25%\" colspan=\"2\" align=\"left\">NIVEL DE URGENCIA</td>";
		if ($_REQUEST['sw_urgencia']!= '1'){
			$this->salida.="      <td width=\"25%\" colspan=\"1\" align=\"left\">URGENTE<input type=\"radio\"  name=\"sw_urgencia\" value=\"1\" $desabilitado onclick=\"ponerFecha(this.form,this.value,'".date('d-m-Y')."')\"></td>";
			$this->salida.="      <td width=\"20%\" colspan=\"1\" align=\"left\">RESERVAR<input type=\"radio\"  name=\"sw_urgencia\" value=\"2\" checked $desabilitado onclick=\"ponerFecha(this.form,this.value,'".date('d-m-Y')."')\"></td>";
		}else{
			$this->salida.="      <td width=\"25%\" colspan=\"1\" align=\"left\">URGENTE<input type=\"radio\"  name=\"sw_urgencia\" value=\"1\" checked $desabilitado onclick=\"ponerFecha(this.form,this.value,'".date('d-m-Y')."'></td>";
			$this->salida.="      <td width=\"20% \" colspan=\"1\" align=\"left\">RESERVAR<input type=\"radio\"  name=\"sw_urgencia\" value=\"2\" $desabilitado onclick=\"ponerFecha(this.form,this.value,'".date('d-m-Y')."')\"></td>";
		}
		$this->salida.="        </tr>";
    $this->salida.="        <tr class=\"modulo_table_list_title\" ><br>";
		$this->salida.="        <td align=\"left\" colspan=\"4\">COMPONENTES A RESERVAR</td>";
    $this->salida.="        </tr>";
		$comp =$this->ConsultaComponente();
    $i=0;
		while($i<sizeof($comp)){
		  $this->salida.="      <tr class =\"modulo_list_claro\">";
		  $this->salida.="      <td align=\"left\" class=\"label\">".$comp[$i][componente]."</td>";
  		$this->salida.="      <td align=\"left\" colspan=\"3\">";
      $this->salida.="      <input type=\"text\" class=\"text-input\" name=\"Cantidad".$comp[$i]['hc_tipo_componente']."\" value=\"".$_REQUEST['Cantidad'.$comp[$i]['hc_tipo_componente']]."\" size=\"2\">";
			$this->salida.="      <label class=\"label\">Und</label>";
		  /*$this->salida.="      <select size = \"1\" name = \"Cantidad".$comp[$i][hc_tipo_componente]."\" class =\"select\">";
		  $this->salida.="      <option value = -1>-Seleccione-</option>";
		  for ($j=1;$j<=20; $j++){
				if($j==$_REQUEST['Cantidad'.$comp[$i]['hc_tipo_componente']]){
					$this->salida.="  <option value =\"".$j."\" selected>";
					$this->salida.= $j;
					$this->salida.="  </option>";
				}else{
					$this->salida.="  <option value =\"".$j."\" >";
					$this->salida.= $j;
					$this->salida.="  </option>";
				}
      }
			$this->salida.="      </select>";
			*/
			$this->salida.="      </td>";
			$this->salida.="      </tr>";
			$i++;
		}
		$this->salida .="       <tr class = \"modulo_list_claro\">";
		if(!$_REQUEST['fecha_reserva']){
      $_REQUEST['fecha_reserva']=date('d-m-Y');
		}
		$this->salida .="       <td class=\"".$this->SetStyle("fecha_reserva")."\" align=\"left\">FECHA DE LA RESERVA</td>";
		$this->salida .="       <td align=\"left\"><input type=\"text\" readonly class=\"input-text\" size=\"10\" maxlength=\"10\" value=\"".$_REQUEST['fecha_reserva']."\" name=\"fecha_reserva\" onBlur=\"IsValidDate(this,'YYYY-MM-DD')\" onKeyUp=\"setDate(this,'YYYY-MM-DD','es')\">
		".ReturnOpenCalendario('forma','fecha_reserva','-')."</td>" ;
		$this->salida.="        <td align=\"left\" colspan =\"2\">";
    $this->salida.="        <table>";
		$this->salida.="          <td class=".$this->SetStyle("hora")." align=\"left\">HORA DE LA RESERVA</td>";
	  $this->salida.="          <td>";
		$this->salida.="          <select size=\"1\" name=\"hora\" class=\"select\" $desabilitado>";
		$this->salida.="          <option value = -1>Seleccione Hora </option>";
		if(!$_REQUEST['hora']){
      $_REQUEST['hora']=date('H');
		}
	  for ($j=0;$j<=23; $j++){
      if (($j >= 0) AND ($j<= 9)){
				$hora = '0'.$j;
				if($_REQUEST['hora']==$hora){
				  $this->salida.="    <option selected value = \"$hora\">0$j</option>";
				}else{
				  $this->salida.="    <option value = \"$hora\">0$j</option>";
				}
			}else{
			  if($_REQUEST['hora']==$j){
					$this->salida.="    <option selected value = $j>$j</option>";
				}else{
					$this->salida.="    <option value = $j>$j</option>";
				}
			}
    }
    $this->salida.="          </select>";
    $this->salida.="          </td>";
		$this->salida.="          <td>";
    $this->salida.="          <select size=\"1\"  name=\"minutos\" class=\"select\" $desabilitado>";
	  $this->salida.="          <option value = -1>Seleccione Minutos</option>";
		if(!$_REQUEST['minutos']){
      $_REQUEST['minutos']=date('i');
		}
		for ($j=0;$j<=59; $j++){
			if(($j >= 0) AND ($j<= 9)){
				$min = '0'.$j;
				if($_REQUEST['minutos']==$min){
					$this->salida.="    <option selected value = \"$min\" >0$j</option>";
				}else{
					$this->salida.="    <option value=\"$min\">0$j</option>";
				}
			}else{
			  if($_REQUEST['minutos']==$j){
					$this->salida.="    <option selected value=$j>$j</option>";
				}else{
					$this->salida.="    <option value=$j>$j</option>";
				}
			}
    }
    $this->salida.="          </select>";
    $this->salida.="          </td>";
    $this->salida.="        </tr>";
		$this->salida.="        </table>";
    $this->salida.="        </tr>";
		/*$this->salida.="        <tr class = \"modulo_list_claro\">";
		$this->salida.="        <td align=\"left\" class=\"label\" colspan = 1>TRANSFUSIONES ANTERIORES</td>";
		//$transf_ant = $this->ConsultaTransfuciones();
    if($transfuciones_ant=='0'){
	    $this->salida.="      <td align = left colspan =\"1\">Si<input type=\"radio\"  name =\"transfuciones_ant\" value=\"1\" $desabilitado></td>";
			$this->salida.="      <td align = left colspan =\"2\">No<input type=\"radio\"  name =\"transfuciones_ant\" value=\"0\" checked $desabilitado></td>";
    }else{
      $this->salida.="      <td align = left colspan=\"1\">Si<input type = radio  name =\"transfuciones_ant\" value=\"1\" checked $desabilitado></td>";
			$this->salida.="      <td align = left colspan=\"2\">No<input type = radio  name =\"transfuciones_ant\" value=\"0\" $desabilitado></td>";
    }
    $this->salida.="        </tr>";
*/
		/*$this->salida.="        <tr class=\"modulo_list_claro\">";
		$this->salida.="        <td align=\"left\" class=\"label\" colspan=\"1\">REACCIONES ADVERSAS</td>";
		$this->salida.="        <td align=\"left\" colspan=\"3\">";
		$this->salida.="        <table>";
		$this->salida.="        <tr class=\"modulo_list_claro\">";

    /*$p=0;
    $i=0;
		$cad ='';
		while($i<sizeof($transf_ant)){
      if($transf_ant[$i][reaccion_adversa] != ''){
	      $cad .= $transf_ant[$i][reaccion_adversa].' ';
				$p = 1;
			}
      $i++;
		}
    if($reacciones_adv==1){
			$this->salida.="        <td align=\"left\" colspan=\"1\">Si<input type=\"radio\"  name=\"reacciones_adv\" value=\"1\" checked $desabilitado></td>";
			$this->salida.="        <td align=\"left\" colspan=\"1\">No<input type=\"radio\"  name=\"reacciones_adv\" value=\"0\" $desabilitado></td>";
    }else{
      $this->salida.="        <td align=\"left\" colspan=\"1\">Si<input type=\"radio\"  name=\"reacciones_adv\" value=\"1\" $desabilitado></td>";
			$this->salida.="        <td align=\"left\" colspan=\"1\">No<input type=\"radio\"  name=\"reacciones_adv\" value=\"0\" checked $desabilitado></td>";
   	}
		$this->salida.="          <td align=\"left\" colspan=\"1\"><textarea style = \"width:100%\" name = \"descripcion_reac\" class =\"textarea\" rows =\"3\" cols =\"60\" $desabilitado1>$descripcion_reac</textarea></td>";
		$this->salida.="          </tr>";

		$this->salida.="        </table>";
		$this->salida.="        </td>";
    $this->salida.="        </tr>";
		*/
		if($this->sexoPaciente($TipoId,$PacienteId) == 'F'){
      $this->salida.="      <tr class = \"modulo_table_list_title\">";
		  $this->salida.="      <td align = \"left\" colspan = \"4\">GESTACIONES</td>";
      $this->salida.="      </tr>";
      $this->salida.="      <tr class = \"modulo_list_claro\">";
		  $this->salida.="      <td align = \"left\" class=\"label\" colspan = \"1\">EMBARAZOS PREVIOS</td>";
      //$gesta =$this->ConsultaGestacion();
		  if($_REQUEST['embarazos_previos']){
        $this->salida.="    <td align=\"left\" colspan=\"1\">Si<input type=\"radio\"  name=\"embarazos_previos\" value=\"1\" checked $desabilitado></td>";
        $this->salida.="    <td align=\"left\" colspan=\"2\">No<input type=\"radio\"  name=\"embarazos_previos\" value=\"0\" $desabilitado></td>";
      }else{
        $this->salida.="    <td align=\"left\" colspan=\"1\">Si<input type=\"radio\"  name=\"embarazos_previos\" value=\"1\" $desabilitado></td>";
        $this->salida.="    <td align=\"left\" colspan=\"2\">No<input type=\"radio\"  name=\"embarazos_previos\" value=\"0\" checked $desabilitado></td>";
		  }
			$this->salida.="     <tr class = \"modulo_list_claro\">";
			$this->salida.="     <td align = \"left\" class=\"label\">FECHA ULTIMO EMBARAZO (dd-mm-aaa)</td>";
			$this->salida.="     <td align = \"left\" colspan = \"3\"><input type = \"text\" value = \"".$_REQUEST['fecha_ultimo_embarazo']."\" name = \"fecha_ultimo_embarazo\" class =\"input-text\" $desabilitado1></td>";
			$this->salida.="     </tr>";

      $this->salida.="      </tr>";
			$this->salida.="      <tr class = \"modulo_list_claro\">";
			$this->salida.="      <td align = \"left\" colspan = \"1\" class=\"label\">EN GESTACION</td>";
      if($_REQUEST['estado_gestacion'] == '1'){
        $this->salida.="    <td align = left colspan =\"1\">Si<input type = \"radio\"  name = \"estado_gestacion\" value = \"1\" checked $desabilitado></td>";
        $this->salida.="    <td align = left colspan =\"2\">No<input type = \"radio\"  name = \"estado_gestacion\" value = \"0\" $desabilitado></td>";
			}else{
        $this->salida.="    <td align = \"left\" colspan = \"1\">Si<input type = \"radio\"  name = \"estado_gestacion\" value = \"1\" $desabilitado></td>";
        $this->salida.="    <td align = \"left\" colspan = \"2\">No<input type = \"radio\"  name = \"estado_gestacion\" value = \"0\" checked $desabilitado></td>";
		  }
       $this->salida.="     </tr>";
		}
		$this->salida.="        <tr class=\"modulo_list_claro\">";
		$this->salida.="        <td align=\"left\" class=\"label\">MOTIVO DE LA RESERVA</td>";
    $this->salida.="        <td align=\"left\" colspan=\"3\"><textarea style=\"width:100%\" name=\"motivo_reserva\" class=\"textarea\" rows=\"3\" cols=\"60\" $desabilitado1>".$_REQUEST['motivo_reserva']."</textarea></td>";
    $this->salida.="        </tr>";
		$this->salida.="        <tr>";
    $this->salida.="        <tr class=\"modulo_list_claro\">";
		$this->salida.="        <td align=\"left\" class=\"label\">CONFIRMAR RESERVA COMPONENTES</td>";
		if($_REQUEST['confirmarR']){
		  $check='checked';
		}
		$this->salida.="        <td align=\"left\" colspan=\"3\"><input type=\"checkbox\" name=\"confirmarR\" value=\"1\" $desabilitado $check></td>";
    $this->salida.="        </tr>";
		$servicios=$this->OtrosServiciosSolicitud();
		if($servicios){
		$this->salida.="       <tr class = \"modulo_table_list_title\">";
		$this->salida.="       <td align = \"left\" colspan = \"4\">OTROS SERVICIOS</td>";
		$this->salida.="       </tr>";
		$this->salida.="       <tr class=\"modulo_list_claro\">";
    $this->salida.="       <td align = \"center\" colspan = \"4\">";
    $this->salida.="       <table width=\"95%\" border=\"0\" align=\"center\">";
		for($i=0;$i<sizeof($servicios);$i++){
    $this->salida.="       <tr class=\"modulo_list_oscuro\">";
		$che='';
		if(in_array($servicios[$i]['cargo'],$_REQUEST['seleccion'])){
      $che='checked';
		}
    $this->salida.="       <td><input $che type=\"checkbox\" name=\"seleccion[]\" value=\"".$servicios[$i]['cargo']."\"></td>";
		$this->salida.="       <td>".$servicios[$i]['descripcion']."</td>";
		$this->salida.="       </tr>";
		}
    $this->salida.="       </table>";
		$this->salida.="       </td>";
		$this->salida.="       </tr>";
		}
		$this->salida.="        <td colspan=\"5\" align=\"center\"><input type=\"submit\" value=\"GUARDAR\" name=\"Guardar\" class=\"input-submit\">";
		$this->salida.="        <input type=\"submit\" value=\"SALIR\" name=\"Salir\" class=\"input-submit\"></td>";
		$this->salida.="        </tr>";
    $this->salida.="        </table>";
		$this->salida.="</td></tr>";
		$this->salida.="</table>";
    $this->salida.="	  </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

/**
* Funcion que se encarga de listar los nombres de los tipos de origen de una cirugia
* @return array
* @param array codigos y valores de los tipos de origen de la base de datos
* @param boolean indicador de selecion de un elemento en el objeto donde se imprimen los tipos de origen
* @param string elemento seleccionado en el objeto donde se imprimen los tipo de origen
*/
	function MostrasSelect($arreglo,$Seleccionado='False',$valor=''){
		switch($Seleccionado){
			case 'False':{
        $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
				foreach($arreglo as $value=>$titulo){
					if($value==$valor){
					$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				  }else{
            $this->salida .=" <option value=\"$value\">$titulo</option>";
				  }
			  }
			  break;
		  }case 'True':{
			  foreach($arreglo as $value=>$titulo){
				  if($value==$valor){
				    $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				  }
				  $this->salida .=" <option value=\"$value\">$titulo</option>";
			  }
			  break;
		  }
	  }
	}

	/**
	* La funcion FormaMensaje se encarga de retornar un mensaje para el usuario
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

		/**
* Funcion que Saca los años para el calendario a partir del año actual
* @return array
*/
 function AnosAgenda($Seleccionado='False',$ano)
	{

		$anoActual=date("Y")-10;
		$anoActual1=$anoActual;
    for($i=0;$i<=20;$i++)
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
				    //if($value>=$mesActual)
						//{
						  if($value==$Defecto)
							{
								$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
							}else{
								$this->salida .=" <option value=\"$value\">$titulo</option>";
							}
						//}
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
					  //if($value>=$mesActual)
						//{

						  if($value==$Defecto)
							{
								$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
							}else
							{
								$this->salida .=" <option value=\"$value\">$titulo</option>";
							}
						//}
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

  function CompatibilidadSangre($TipoDocumento,$Documento,$grupoSanguineo,$fechaReserva,$departamento){
	  unset($_SESSION['SolicitudReserva']['Bandera']);
		unset($_SESSION['RESERVA_SANGRE']['RETORNO']);
		if(!$TipoDocumento && $_SESSION['RESERVASANGRE']['TIPDOCUMENTO']){$TipoDocumento=$_SESSION['RESERVASANGRE']['TIPDOCUMENTO'];}
		if(!$Documento && $_SESSION['RESERVASANGRE']['DOCUMENTO']){$Documento=$_SESSION['RESERVASANGRE']['DOCUMENTO'];}
		if(!$grupoSanguineo && $_SESSION['RESERVASANGRE']['GRUPO']){$grupoSanguineo=$_SESSION['RESERVASANGRE']['GRUPO'];}
		if(!$fechaReserva && $_SESSION['RESERVASANGRE']['FECHRESERVA']){$fechaReserva=$_SESSION['RESERVASANGRE']['FECHRESERVA'];}

    $this->salida .= ThemeAbrirTabla('PACIENTE CON RESERVA DE UNIDADES SANGUINEAS PARA CRUZAR');
		$this->salida .="<script language='javascript'>";
		$this->salida .= 'function mOvr(src,clrOver){';
		$this->salida .= '  src.style.background = clrOver;';
		$this->salida .= '}';
		$this->salida .= 'function mOut(src,clrIn){';
		$this->salida .= '  src.style.background = clrIn;';
		$this->salida .= '}';
		$this->salida .= '</script>';
		$accion=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','FiltrarBusquedaReservas');
		$this->salida .= "        <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "        <table border=\"0\" width=\"50%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "        <tr><td></td></tr>";
		$this->salida .= "        <tr><td><fieldset><legend class=\"field\">FILTRO DE BUSQUEDA</legend>";
    $this->salida .= "        <br><table width=\"90%\" border=\"0\" align=\"center\">";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td class=\"label\">TIPO DOCUMENTO </td><td><select name=\"TipoDocumento\" class=\"select\">";
		$tipo_id=$this->tipo_id_paciente();
		$this->BuscarIdPaciente($tipo_id,$TipoDocumento);
		$this->salida .= "        </select></td>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td class=\"label\">DOCUMENTO </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" value=\"$Documento\" maxlength=\"32\"></td>";
		$this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td class=\"label\">ABO / Rh </td><td><select name=\"grupoSanguineo\" class=\"select\">";
		$facts=$this->ConsultaFactorRh();
		$this->GrupoSanguineo($facts,'False',$grupoSanguineo);
		$this->salida .= "        </select></td>";
		$this->salida .= "        </tr>";
    $this->salida .= "		    <tr class=\"modulo_list_claro\"><td class=\"label\">DEPARTAMENTO </td><td><select name=\"departamento\" class=\"select\">";
		$departamentos=$this->tiposdepartamentos();
		$this->MostrasSelect($departamentos,'False',$departamento);
		$this->salida .= "        </select>";
		$this->salida .= "        </td>";
    $this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .="         <td class=\"".$this->SetStyle("fechaReserva")."\" align=\"left\">FECHA DE LA RESERVA</td>";
		$this->salida .= "	  	  <td><input size=\"10\" maxlength=\"10\" type=\"text\" name=\"fechaReserva\" value=\"$fechaReserva\" class=\"input-text\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\">";
		$this->salida .= "	  	  ".ReturnOpenCalendario('forma','fechaReserva','/')."</td>";
		$this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
    $this->salida .= "        <td align=\"center\" colspan=\"2\">";
		$this->salida .= "        <input class=\"input-submit\" type=\"submit\" name=\"filtrar\" value=\"FILTRAR\">";
		$this->salida .= "        <input class=\"input-submit\" type=\"submit\" name=\"menu\" value=\"MENU\">";
		$this->salida .= "        </td>";
		$this->salida .= "        </tr>";
		$this->salida .= "        </table><br>";
		$this->salida .= "	      </fieldset></td></tr></table><BR>";
    $this->salida .= "        </form>";
		$this->salida .= "        <form name=\"formac\" action=\"$accion\" method=\"post\">";
		$datosReservasTot=$this->ReservasConGlobulos($TipoDocumento,$Documento,$grupoSanguineo,$fechaReserva,$departamento);
		if($datosReservasTot){
			$this->salida .= "        <table width=\"100%\" border=\"0\" align=\"center\">";
			$this->salida .= "        <tr class=\"modulo_table_list_title\">";
			$this->salida .= "        <td>No. RESERVA</td>";
			$this->salida .= "        <td>PACIENTE</td>";
			$this->salida .= "        <td>FECHA RESERVA</td>";
			$this->salida .= "        <td>COMPONENTE</td>";
			$this->salida .= "        <td>AOB / Rh</td>";
			$this->salida .= "        <td align=\"center\">CANT.</td>";
			$this->salida .= "        <td>CRUZAR</td>";
			$this->salida .= "        <td>RESULTADOS</td>";
			$this->salida .= "        <td colspan=\"2\">CONFIRMACION<br>Y ENTREGA</td>";
			$this->salida .= "        </tr>";
			$y=0;
			$solicitudAnt=-1;
			$backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
			foreach($datosReservasTot as $NOReserva=>$Vector){
			  foreach($Vector as $NOBolsaCruze=>$datosReservas){
					if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
					if($y % 2){$estilo1='modulo_list_oscuro';}else{$estilo1='modulo_list_claro';}
					if($solicitudAnt!=$datosReservas['solicitud_reserva_sangre_id']){
					  $this->salida.="<tr  class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>";
						$this->salida .= "        <td rowspan=\"".sizeof($Vector)."\">".$NOReserva."</td>";
						$this->salida .= "        <td rowspan=\"".sizeof($Vector)."\">".$datosReservas['tipo_id_paciente']." ".$datosReservas['paciente_id']." - ".$datosReservas['nombre']."</td>";
						$this->salida .= "        <td rowspan=\"".sizeof($Vector)."\">".$datosReservas['fecha_hora_reserva']."</td>";
						$this->salida .= "        <td rowspan=\"".sizeof($Vector)."\">".$datosReservas['componente']."</td>";
						if(!$datosReservas['grupo_sanguineo'] && !$datosReservas['rh']){
            $this->salida .= "        <td rowspan=\"".sizeof($Vector)."\">SIN REGISTRO</td>";
						}else{
						  if($datosReservas['rh']=='+'){
                $this->salida .= "        <td rowspan=\"".sizeof($Vector)."\">".$datosReservas['grupo_sanguineo']." /&nbsp&nbsp&nbsp;POSTIVO</td>";
							}elseif($datosReservas['rh']=='-'){
                $this->salida .= "        <td rowspan=\"".sizeof($Vector)."\">".$datosReservas['grupo_sanguineo']." /&nbsp&nbsp&nbsp;<label class=\"label_error\">NEGATIVO</label></td>";
							}else{
                $this->salida .= "        <td rowspan=\"".sizeof($Vector)."\">".$datosReservas['grupo_sanguineo']."</td>";
							}
						}
						$this->salida .= "        <td rowspan=\"".sizeof($Vector)."\">".$datosReservas['cantidad_componente']."</td>";
						$action=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','LlamaFormaCruzarSangre',array("tipoId"=>$datosReservas['tipo_id_paciente'],"paciente"=>$datosReservas['paciente_id'],"nombre"=>$datosReservas['nombre'],"fecha"=>$datosReservas['fecha_hora_reserva'],"grupo"=>$datosReservas['grupo_sanguineo'],"rh"=>$datosReservas['rh'],"reservaId"=>$NOReserva));
						$this->salida .= "        <td align=\"center\" rowspan=\"".sizeof($Vector)."\"><a href=\"$action\" class=\"link\"><b>CRUZAR</b></a></td>";
						$this->salida .= "        <td width=\"10%\">";
						$this->salida .= "        <table width=\"100%\" border=\"0\" align=\"center\">";
						$this->salida .= "        <tr class=\"$estilo1\">";
						if($datosReservas['sw_reserva_levantada']=='1'){
            $this->salida .= "        <td align=\"center\" class=\"label\">LEVANTADA</td>";
						}
						$action1=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','ConsultaCruzeSangre',array("cruzeid"=>$datosReservas['cruze_sanguineo_id'],"origen"=>1));
						//$action1=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','ConsultaCruzeSangre',array("cruzeid"=>$datosReservas['cruze_sanguineo_id'],"destino"=>1,"bandera"=>1));
						if($datosReservas['compatibilidad']!=1){
						  $class='TurnoActivo';
						}else{
						  $class='link';
						}
						$this->salida .= "        <td align=\"center\"><a href=\"$action1\" class=\"$class\"><b>".$datosReservas['bolsa_id']."</b></a></td>";
						if($datosReservas['bolsa_id'] && empty($datosReservas['confirma'])){
						  if($datosReservas['correccion']==1){
              $action=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','ConsultaCruzeSangre',array("cruzeid"=>$datosReservas['cruze_sanguineo_id'],"destino"=>1,"bandera"=>1));
				      $this->salida .= "	      <td width=\"5%\" nowrap align=\"center\"><a href=\"$action\"><img title=\"Corregir Cruce\" border=\"0\" src=\"".GetThemePath()."/images/modificar.png\"><a></td>";
							}
              $actionEntrega=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','EntregaExamenCruce',array("cruce"=>$datosReservas['cruze_sanguineo_id'],"bolsa"=>$datosReservas['ingreso_bolsa_id'],"reserva"=>$datosReservas['solicitud_reserva_sangre_id'],
							"NumBolsa"=>$datosReservas['bolsa_id'],"sello"=>$datosReservas['sellobolsa'],"componente"=>$datosReservas['componente'],"grupoBolsa"=>$datosReservas['grupobolsa'],"rhbolsa"=>$datosReservas['rhbolsa'],
							"tipoId"=>$datosReservas['tipo_id_paciente'],"paciente_id"=>$datosReservas['paciente_id'],"nombrePac"=>$datosReservas['nombre'],"rh"=>$datosReservas['rh'],"grupo"=>$datosReservas['grupo_sanguineo'],"indica"=>1));
						  $this->salida .= "           <td width=\"35%\" nowrap><a href=\"$actionEntrega\" class=\"$class\"><b> ENTREGAR</b></a></td>";
						}elseif(!empty($datosReservas['confirma'])){
              $this->salida .= "           <td width=\"35%\" nowrap><b> ENTREGADO</b></td>";
						}
						$this->salida .= "        </tr>";
						$this->salida .= "		    </table>";
						$this->salida .= "        </td>";

						$action=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','LlamaDetalleReservaSangrePac',array("reservaId"=>$datosReservas['solicitud_reserva_sangre_id'],"tipoId"=>$datosReservas['tipo_id_paciente'],"pacienteId"=>$datosReservas['paciente_id'],
						"nombrePac"=>$datosReservas['nombre'],"fechaReserva"=>$datosReservas['fecha_hora_reserva'],"departamento"=>$reservas[$i]['dpto'],"Ubicacion"=>$reservas[$i]['ubicacion_paciente'],"grupo"=>$datosReservas['grupo_sanguineo'],"rh"=>$datosReservas['rh'],
						"origen"=>1,"destino"=>1));
						$this->salida .= "	       <td align=\"center\"><a href=\"$action\"><img border=\"0\" src=\"".GetThemePath()."/images/endturn.png\" title=\"Confirmar Unidades\"></a></td>";

						$actionEn=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','LlamaModuloEntregaComponentes',array("TipoDocumento"=>$datosReservas['tipo_id_paciente'],"Documento"=>$datosReservas['paciente_id'],"reservaId"=>$datosReservas['solicitud_reserva_sangre_id']));
				    $this->salida .= "         <td align=\"center\"><a href=\"$actionEn\" class=\"link\"><img border=\"0\" src=\"".GetThemePath()."/images/entregabolsa.png\" title=\"Entrega Unidades\"></a></td>";
						$this->salida .= "        </tr>";
						$y++;
						$solicitudAnt=$datosReservas['solicitud_reserva_sangre_id'];
					}else{
            $this->salida .= "        <tr class=\"$estilo1\">";
					  $this->salida .= "        <td>";
						$this->salida .= "        <table width=\"100%\" border=\"0\" align=\"center\">";
						$this->salida .= "        <tr class=\"$estilo1\">";
						if($datosReservas['sw_reserva_levantada']=='1'){
            $this->salida .= "        <td align=\"center\">LEVANTADA</td>";
						}
						$action1=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','ConsultaCruzeSangre',array("cruzeid"=>$datosReservas['cruze_sanguineo_id'],"origen"=>1));
						if($datosReservas['compatibilidad']!=1){
						  $class='TurnoActivo';
						}else{
						  $class='link';
						}
						$this->salida .= "        <td align=\"center\"><a href=\"$action1\" class=\"$class\"><b>".$datosReservas['bolsa_id']."</b></a></td>";
            if($datosReservas['bolsa_id'] && empty($datosReservas['confirma'])){
              if($datosReservas['correccion']==1){
								$action=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','ConsultaCruzeSangre',array("cruzeid"=>$datosReservas['cruze_sanguineo_id'],"destino"=>1,"bandera"=>1));
								$this->salida .= "	      <td width=\"5%\" nowrap align=\"center\"><a href=\"$action\"><img title=\"Corregir Cruce\" border=\"0\" src=\"".GetThemePath()."/images/modificar.png\"><a></td>";
							}
						  $actionEntrega=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','EntregaExamenCruce',array("cruce"=>$datosReservas['cruze_sanguineo_id'],"bolsa"=>$datosReservas['ingreso_bolsa_id'],"reserva"=>$datosReservas['solicitud_reserva_sangre_id'],
							"NumBolsa"=>$datosReservas['bolsa_id'],"sello"=>$datosReservas['sellobolsa'],"componente"=>$datosReservas['componente'],"grupoBolsa"=>$datosReservas['grupobolsa'],"rhbolsa"=>$datosReservas['rhbolsa'],
							"tipoId"=>$datosReservas['tipo_id_paciente'],"paciente_id"=>$datosReservas['paciente_id'],"nombrePac"=>$datosReservas['nombre'],"rh"=>$datosReservas['rh'],"grupo"=>$datosReservas['grupo_sanguineo'],"indica"=>1));
						  $this->salida .= "       <td width=\"35%\" nowrap><a href=\"$actionEntrega\" class=\"$class\"><b> ENTREGAR</b></a></td>";
						}elseif(!empty($datosReservas['confirma'])){
              $this->salida .= "           <td width=\"35%\" nowrap><b> ENTREGADO</b></td>";
						}
						$this->salida .= "        </tr>";
						$this->salida .= "		    </table>";
						$this->salida .= "        </td>";
						$action=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','LlamaDetalleReservaSangrePac',array("reservaId"=>$datosReservas['solicitud_reserva_sangre_id'],"tipoId"=>$datosReservas['tipo_id_paciente'],"pacienteId"=>$datosReservas['paciente_id'],
						"nombrePac"=>$datosReservas['nombre'],"fechaReserva"=>$datosReservas['fecha_hora_reserva'],"departamento"=>$reservas[$i]['dpto'],"Ubicacion"=>$reservas[$i]['ubicacion_paciente'],"grupo"=>$datosReservas['grupo_sanguineo'],"rh"=>$datosReservas['rh'],"origen"=>1,
						"destino"=>1));
						$this->salida .= "	       <td align=\"center\"><a href=\"$action\"><img border=\"0\" src=\"".GetThemePath()."/images/endturn.png\" title=\"Confirmar Unidades\"></a></td>";
						$actionEn=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','LlamaModuloEntregaComponentes',array("TipoDocumento"=>$datosReservas['tipo_id_paciente'],"Documento"=>$datosReservas['paciente_id'],"reservaId"=>$datosReservas['solicitud_reserva_sangre_id']));
				    $this->salida .= "         <td align=\"center\"><a href=\"$actionEn\" class=\"link\"><img border=\"0\" src=\"".GetThemePath()."/images/entregabolsa.png\" title=\"Entrega Unidades\"></a></td>";
						$this->salida .= "        </tr>";
					}
				}
			}
			$this->salida .= "		    </table>";
			$this->salida .=$this->RetornarBarra(4);
		}else{
      $this->salida .= "        <table width=\"80%\" border=\"0\" align=\"center\">";
      $this->salida .= "        <tr><td align=\"center\" class=\"label_error\">NO HAY REGISTROS CON ESTOS PARAMETROS</td></tr>";
			$this->salida .= "		    </table>";
		}
    $this->salida .= "       </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function FormaCruzarSangre($tipoId,$paciente,$nombre,$fecha,$responsable,$grupo,$rh,$reservaId,$bolsaBusqueda,$grupo_sanguineoBusqueda,$fechaBusqueda){
	  $this->salida .= ThemeAbrirTabla('SELECCION UNIDAD SANGUINEA');
		$accion=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','LlamaFormaCruzarSangre',array("tipoId"=>$tipoId,"paciente"=>$paciente,"nombre"=>$nombre,"fecha"=>$fecha,"responsable"=>$responsable,"grupo"=>$grupo,"rh"=>$rh,"reservaId"=>$reservaId));
		$this->salida .= " <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= " <table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= " <tr><td width=\"55%\">";
		$this->salida .= "        <table border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "        <tr><td></td></tr>";
		$this->salida .= "        <tr><td><fieldset><legend class=\"field\">DATOS DEL PACIENTE DE LA RESERVA</legend>";
    $this->salida .= "        <table width=\"95%\" border=\"0\" align=\"center\">";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td width=\"15%\" class=\"label\">PACIENTE</td>";
    $this->salida .= "        <td>".$tipoId." ".$paciente." ".$nombre."</td>";
    $this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td width=\"15%\" class=\"label\">FECHA</td>";
    $this->salida .= "        <td>".$fecha."</td>";
		$this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td width=\"15%\" class=\"label\">RESPONSABLE</td>";
    $this->salida .= "        <td>".$responsable."</td>";
    $this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td width=\"15%\" class=\"label\">ABO / Rh</td>";
		$aob_rh=$this->HallarRhRegitradoPaciente($tipoId,$paciente);
		if(!$aob_rh){
		$this->salida .= "        <td>SIN REGISTRO</td>";
		}else{
    if($aob_rh['rh']=='+'){
		$this->salida .= "        <td><b>".$aob_rh['grupo_sanguineo']." /&nbsp&nbsp&nbsp;POSITIVO</b></td>";
		}elseif($aob_rh['rh']=='-'){
    $this->salida .= "        <td><b>".$aob_rh['grupo_sanguineo']." /&nbsp&nbsp&nbsp;<label class=\"label_error\">NEGATIVO</label></b></td>";
		}else{
    $this->salida .= "        <td><b>".$aob_rh['grupo_sanguineo']."</td>";
		}
		}
    $this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td colspan=\"2\">&nbsp;</td>";
    $this->salida .= "        </tr>";
		$this->salida .= "        </table>";
		$this->salida .= "	      </fieldset></td></tr></table>";
    $this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "        <table border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "        <tr><td></td></tr>";
		$this->salida .= "        <tr><td><fieldset><legend class=\"field\">FILTRO DE BUSQUEDA DE LAS UNIDADES</legend>";
    $this->salida .= "        <table width=\"95%\" border=\"0\" align=\"center\">";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td class=\"label\">No. BOLSA</td>";
		$this->salida .= "        <td colspan=\"2\"><input type=\"text\" size=\"32\" name=\"bolsaBusqueda\" value=\"".$_REQUEST['bolsaBusqueda']."\"></td>";
    $this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td class=\"label\">AOB / Rh</td>";
		$this->salida .= "        <td colspan=\"2\"><select name=\"grupo_sanguineoBusqueda\" class=\"select\">";
		$facts=$this->ConsultaFactorRh();
		$this->GrupoSanguineo($facts,'False',$_REQUEST['grupo_sanguineoBusqueda']);
		$this->salida .= "        </select></td>";
    $this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .="         <td class=\"".$this->SetStyle("fechaBusqueda")."\" align=\"left\">FECHA VENCE</td>";
		$this->salida .= "	  	  <td><input size=\"10\" maxlength=\"10\" type=\"text\" name=\"fechaBusqueda\" value=\"$fechaBusqueda\" class=\"input-text\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\">";
		$this->salida .= "	  	  ".ReturnOpenCalendario('forma','fechaBusqueda','/')."</td>";
		$this->salida .= "        <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"filtrar\" value=\"FILTRAR\"></td>";
		$this->salida .= "        </tr>";
		$this->salida .= "        </table>";
		$this->salida .= "	      </fieldset></td></tr></table>";
    $this->salida .= "  </td></tr>";
    $this->salida .= "  </table><BR>";
		$unidadesDisponibles=$this->UnidadesSanguineasDisponibles($reservaId,$bolsaBusqueda,$grupo_sanguineoBusqueda,$fechaBusqueda);
		if($unidadesDisponibles){
			$this->salida .= "        <table width=\"80%\" border=\"0\" align=\"center\">";
			$this->salida .= "        <tr class=\"modulo_table_title\">";
			$this->salida .= "        <td>BOLSA</td>";
			$this->salida .= "        <td>SELLO</td>";
			$this->salida .= "        <td>ALICUOTAS</td>";
			$this->salida .= "        <td>FECHA VENCIMIENTO</td>";
			$this->salida .= "        <td>AOB / Rh</td>";
			$this->salida .= "        <td>PROCEDENCIA</td>";
			$this->salida .= "        <td>&nbsp;</td>";
			$this->salida .= "        </tr>";
			$y=0;
			for($i=0;$i<sizeof($unidadesDisponibles);$i++){
				if($y % 2){$estilo='modulo_list_claro';$estilo1='modulo_list_oscuro';}else{$estilo='modulo_list_oscuro';$estilo1='modulo_list_claro';}
				$this->salida .= "        <tr class=\"$estilo\">";
				$this->salida .= "        <td>".$unidadesDisponibles[$i]['bolsa_id']."</td>";
				$this->salida .= "        <td>".$unidadesDisponibles[$i]['sello_calidad']."</td>";
				$this->salida .= "        <td>";
        $alicuotas=$this->AlicuotasBolsa($unidadesDisponibles[$i]['ingreso_bolsa_id']);
				if($alicuotas){
        $this->salida .= "        <table width=\"98%\" border=\"0\" align=\"center\">";
				for($j=0;$j<sizeof($alicuotas);$j++){
          $this->salida .= "        <tr class=\"$estilo1\">";
					if($alicuotas[$j]['numero_alicuota']==0){
					$this->salida .= "        <td><b>PRINCIPAL</b></td>";
					}else{
          $this->salida .= "        <td>".$alicuotas[$j]['numero_alicuota']."</td>";
					}
					$this->salida .= "        <td>".$alicuotas[$j]['cantidad']." <b>ml.</b></td>";
					$this->salida .= "        </tr>";
				}
        $this->salida .= "        </table>";
				}
				$this->salida .= "        </td>";
				$this->salida .= "        <td>".$unidadesDisponibles[$i]['fecha_vencimiento']."</td>";
				if($unidadesDisponibles[$i]['rh']=='+'){
        $this->salida .= "        <td>".$unidadesDisponibles[$i]['grupo_sanguineo']." /&nbsp&nbsp&nbsp;POSITIVO</td>";
				}elseif($unidadesDisponibles[$i]['rh']=='-'){
        $this->salida .= "        <td>".$unidadesDisponibles[$i]['grupo_sanguineo']." /&nbsp&nbsp&nbsp;<label class=\"label_error\">NEGATIVO</label></td>";
				}else{
        $this->salida .= "        <td>".$unidadesDisponibles[$i]['grupo_sanguineo']."</td>";
				}
				$this->salida .= "        <td>".$unidadesDisponibles[$i]['nombre_tercero']."</td>";

				$action=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','RegistroResultadosCruze',array("bolsa"=>$unidadesDisponibles[$i]['ingreso_bolsa_id'],
				"tipoId"=>$tipoId,"paciente"=>$paciente,"nombre"=>$nombre,"fechaReserva"=>$fecha,"responsable"=>$responsable,"grupo"=>$grupo,"rh"=>$rh,"reservaId"=>$reservaId,
				"bolsaNum"=>$unidadesDisponibles[$i]['bolsa_id'],"sello"=>$unidadesDisponibles[$i]['sello_calidad'],"fechaVence"=>$unidadesDisponibles[$i]['fecha_vencimiento'],
				"grupoBolsa"=>$unidadesDisponibles[$i]['grupo_sanguineo'],"rhBolsa"=>$unidadesDisponibles[$i]['rh'],"nomTercero"=>$unidadesDisponibles[$i]['nombre_tercero'],"fechaExtraccion"=>$unidadesDisponibles[$i]['fecha_extraccion']));
				$this->salida .= "        <td align=\"center\"><a href=\"$action\" class=\"link\"><b>SELECCION</b></a></td>";
				$this->salida .= "        </tr>";
			}
			$this->salida .= "		    </table>";
			$this->salida .=$this->RetornarBarra(2);
		}else{
      $this->salida .= "        <BR><table width=\"80%\" border=\"0\" align=\"center\">";
			$this->salida .= "        <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON REGISTROS</td></tr>";
			$this->salida .= "		    </table>";
		}
		$this->salida .= "        <BR><table width=\"80%\" border=\"0\" align=\"center\">";
		$this->salida .= "        <tr><td align=\"center\"><input type=\"submit\" class=\"input-submit\" value=\"SALIR\" name=\"salir\"></td></tr>";
		$this->salida .= "		    </table>";
    $this->salida .= "       </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function FormaResultadosCruze($bolsa,$tipoId,$paciente,$nombre,$fechaReserva,$responsable,$grupo,$rh,$reservaId,
		$bolsaNum,$sello,$fechaVence,$grupoBolsa,$rhBolsa,$nomTercero,$fechaExtraccion,$consulta,$destino){
    if($consulta==1){
      $desabilitar='disabled';
		}
		$this->salida .= ThemeAbrirTabla('RESULTADO DE COMPATIBLIDAD');
		$this->salida .= "            <script>";
		//var p=frm.grupo.options[frm.grupo.options.selectedIndex].value;
    $this->salida .= "            function ValidaContraGrupo(frm,valor){";
    $this->salida .= "              if(valor!=frm.grupoRegister.value){";
    $this->salida .= "                alert('Usted ha seleccionado una Hemoclasificacion Diferente a la Registrada en la Base de Datos, Verifique por Favor');";
		$this->salida .= "              }";
    $this->salida .= "            }";
    $this->salida .= "            </script>";
		if(!$destino){
		$accion=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','GuardarCruzeSangre',array("bolsa"=>$bolsa,"tipoId"=>$tipoId,"paciente"=>$paciente,"nombre"=>$nombre,"fechaReserva"=>$fechaReserva,"responsable"=>$responsable,"grupo"=>$grupo,"rh"=>$rh,"reservaId"=>$reservaId,
		"bolsaNum"=>$bolsaNum,"sello"=>$sello,"fechaVence"=>$fechaVence,"grupoBolsa"=>$grupoBolsa,"rhBolsa"=>$rhBolsa,"nomTercero"=>$nomTercero,"fechaExtraccion"=>$fechaExtraccion,"origen"=>$_REQUEST['origen']));
		}else{
    $accion=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','GuardarCorrecionCruzeSangre',array("bolsa"=>$bolsa,"tipoId"=>$tipoId,"paciente"=>$paciente,"nombre"=>$nombre,"fechaReserva"=>$fechaReserva,"responsable"=>$responsable,"grupo"=>$grupo,"rh"=>$rh,"reservaId"=>$reservaId,
		"bolsaNum"=>$bolsaNum,"sello"=>$sello,"fechaVence"=>$fechaVence,"grupoBolsa"=>$grupoBolsa,"rhBolsa"=>$rhBolsa,"nomTercero"=>$nomTercero,"fechaExtraccion"=>$fechaExtraccion,"origen"=>$_REQUEST['origen'],"cruzeid"=>$_REQUEST['cruzeid']));
		}
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "  <tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "  </td><tr>";
		$this->salida .= "  <tr><td></td></tr>";
		$this->salida .= "  <tr><td><fieldset><legend class=\"field\">DATOS PRINCIPALES</legend>";
		$this->salida .= "  <table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "  <tr><td width=\"50%\">";
    $this->salida .= "        <table width=\"100%\" border=\"0\" align=\"center\">";
		$this->salida .= "        <tr class=\"modulo_table_title\"><td colspan=\"4\">DATOS DEL PACIENTE</td></tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td width=\"25%\" class=\"label\">PACIENTE</td>";
    $this->salida .= "        <td colspan=\"3\">".$tipoId." ".$paciente." ".$nombre."</td>";
    $this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td width=\"25%\" class=\"label\">FECHA RESERVA</td>";
    $this->salida .= "        <td>".$fechaReserva."</td>";
		$this->salida .= "        <td width=\"25%\" class=\"label\">ABO / Rh</td>";
		$aob_rh=$this->HallarRhRegitradoPaciente($tipoId,$paciente);
		if(!$aob_rh){
    $this->salida .= "        <td>SIN REGISTRO</td>";
		}else{
			if($aob_rh['rh']=='+'){
			$this->salida .= "        <td><b>".$aob_rh['grupo_sanguineo']." /&nbsp&nbsp&nbsp;POSITIVO</b></td>";
			}elseif($aob_rh['rh']=='-'){
			$this->salida .= "        <td><b>".$aob_rh['grupo_sanguineo']." /&nbsp&nbsp&nbsp;<label class=\"label_error\">NEGATIVO</label></b></td>";
			}else{
      $this->salida .= "        <td><b>".$aob_rh['grupo_sanguineo']."</b></td>";
			}
		}
		$this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td width=\"25%\" class=\"label\">RESPONSABLE</td>";
    $this->salida .= "        <td colspan=\"3\">".$responsable."</td>";
    $this->salida .= "        </tr>";
		$this->salida .= "        </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td width=\"50%\">";
		$this->salida .= "        <table width=\"100%\" border=\"0\" align=\"center\">";
		$this->salida .= "        <tr class=\"modulo_table_title\"><td colspan=\"4\">DATOS DE LA UNIDAD SANGUINEA</td></tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td class=\"label\">NUMERO BOLSA</td>";
    $this->salida .= "        <td>".$bolsaNum."</td>";
		$this->salida .= "        <td class=\"label\">SELLO</td>";
    $this->salida .= "        <td>".$sello."</td>";
    $this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td class=\"label\">ABO / Rh</td>";
		if($rhBolsa=='+'){
    $this->salida .= "        <td>".$grupoBolsa." /&nbsp&nbsp&nbsp;POSITIVO</td>";
		}elseif($rhBolsa=='-'){
    $this->salida .= "        <td>".$grupoBolsa." /&nbsp&nbsp&nbsp;<label class=\"label_error\">NEGATIVO</label></td>";
		}else{
    $this->salida .= "        <td>".$grupoBolsa."</td>";
		}
		$this->salida .= "        <td  class=\"label\">FECHA VENCIMIENTO</td>";
    $this->salida .= "        <td>".$fechaVence."</td>";
    $this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td class=\"label\">PROCEDENCIA</td>";
    $this->salida .= "        <td colspan=\"3\">".$nomTercero."</td>";
		$this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td class=\"label\">FECHA EXTRACCION</td>";
    $this->salida .= "        <td colspan=\"3\">".$fechaExtraccion."</td>";
		$this->salida .= "        </tr>";
		$this->salida .= "        </table>";
		$this->salida .= "	      </fieldset></td></tr></table>";
    $this->salida .= "  </td></tr>";
		$this->salida .= "  </table><BR>";
    $crucesMarcas=$this->ObtenerCantidadCruces();
		$this->salida .= "    <table width=\"85%\" border=\"0\" align=\"center\">";
		$this->salida .= "    <tr><td class=\"modulo_table_title\">DATOS DEL RESULTADO DEL CRUCE</td></tr>";
    $this->salida .= "    <tr><td class=\"modulo_list_oscuro\">";
		$this->salida .= "        <BR><table width=\"95%\" border=\"0\" align=\"center\">";
    $this->salida .= "        <tr class=\"modulo_table_list_title\"><td colspan=\"2\">HEMOCLASIFICACION DEL PACIENTE</td></tr>";
		if($_REQUEST['profesionalinter']){
			//$this->salida .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
			$this->salida .= "      <tr class=\"modulo_list_claro\"><td colspan=\"2\" align=\"center\">PROFESIONAL QUE REALIZO LA ANTERIOR HEMOCLASIFICACION <label class=\"label\">".$_REQUEST['profesionalinter']."</label></td></tr>";
			//$this->salida .= "      </table>";
		}
    $this->salida .= "        <tr class=\"modulo_list_claro\"><td valign=\"top\">";
    $this->salida .= "            <table width=\"95%\" border=\"0\" align=\"center\">";
		$this->salida .= "            <tr class=\"modulo_table_list_title\"><td colspan=\"6\">MANUAL</td></tr>";
    $this->salida .= "            <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "            <td class=\"label\">ANTI A</td>";
		foreach($crucesMarcas as $valor=>$descripcion){
		  $var='';
			if(!$_REQUEST['hemoclasifyManualA'] && $valor=='0'){
        $var='checked';
			}elseif($_REQUEST['hemoclasifyManualA']==$valor){
        $var='checked';
			}
		  $this->salida .= "          <td class=\"label\"><input type=\"radio\" name=\"hemoclasifyManualA\" value=\"$valor\" $var $desabilitar> ".$descripcion['descripcion']." </td>";
		}
		$this->salida .= "            </tr>";
		$this->salida .= "            <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "            <td class=\"label\">ANTI B</td>";
		foreach($crucesMarcas as $valor=>$descripcion){
		  $var='';
			if(!$_REQUEST['hemoclasifyManualB'] && $valor=='0'){
        $var='checked';
			}elseif($_REQUEST['hemoclasifyManualB']==$valor){
        $var='checked';
			}
		  $this->salida .= "            <td class=\"label\"><input type=\"radio\" name=\"hemoclasifyManualB\" value=\"$valor\" $var $desabilitar> ".$descripcion['descripcion']." </td>";
		}
    $this->salida .= "            </tr>";
		$this->salida .= "            <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "            <td class=\"label\">ANTI AB</td>";
		foreach($crucesMarcas as $valor=>$descripcion){
		  $var='';
			if(!$_REQUEST['hemoclasifyManualAB'] && $valor=='0'){
        $var='checked';
			}elseif($_REQUEST['hemoclasifyManualAB']==$valor){
        $var='checked';
			}
		  $this->salida .= "            <td class=\"label\"><input type=\"radio\" name=\"hemoclasifyManualAB\" value=\"$valor\" $var $desabilitar> ".$descripcion['descripcion']." </td>";
		}
    $this->salida .= "            </tr>";
		$this->salida .= "            <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "            <td class=\"label\">ANTI D</td>";
    foreach($crucesMarcas as $valor=>$descripcion){
		  $var='';
			if(!$_REQUEST['hemoclasifyManualD'] && $valor=='0'){
        $var='checked';
			}elseif($_REQUEST['hemoclasifyManualD']==$valor){
        $var='checked';
			}
		  $this->salida .= "            <td class=\"label\"><input type=\"radio\" name=\"hemoclasifyManualD\" value=\"$valor\" $var $desabilitar> ".$descripcion['descripcion']." </td>";
		}
    $this->salida .= "            </tr>";
		$this->salida .= "            <input type=\"hidden\" name=\"grupoRegister\" value=\"".$aob_rh['grupo_sanguineo']."/".$aob_rh['rh']."\">";
    $this->salida .= "            <tr class=\"modulo_list_claro\"><td></td></tr>";
    $this->salida .= "            <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "            <td colspan=\"6\"><label class=\"".$this->SetStyle("grupoManual")."\">INTERPRETACION&nbsp&nbsp&nbsp;</label>";
		$this->salida .= "            <select name=\"grupoManual\" class=\"select\" $desabilitar>";
		if(!$_REQUEST['grupoManual']){
      $_REQUEST['grupoManual']=$grupo.'/'.$rh;
		}
		$facts=$this->ConsultaFactorRh();
		$this->GrupoSanguineo($facts,'False',$_REQUEST['grupoManual']);
		$this->salida .= "            </select></td>";
    $this->salida .= "            </tr>";
		$this->salida .= "            <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "            <td colspan=\"6\"><label class=\"".$this->SetStyle("bacteriologoManual")."\">PROFESIONAL&nbsp&nbsp&nbsp;</label>";
		$this->salida .= "            <select name=\"bacteriologoManual\" class=\"select\" $desabilitar>";
		$bacteriologos=$this->TotalBacteriologos();
		$this->BuscarProfesionlesEspecialistas($bacteriologos,'False',$_REQUEST['bacteriologoManual']);
		$this->salida .= "            </select></td>";
    $this->salida .= "            </tr>";
    $this->salida .= "            </table><BR>";
    $this->salida .= "        </td><td>";
    $this->salida .= "            <table width=\"90%\" border=\"0\" align=\"center\">";
		$this->salida .= "            <tr class=\"modulo_table_list_title\"><td colspan=\"6\">CON GEL</td></tr>";
    $this->salida .= "            <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "            <td class=\"label\">ANTI A</td>";
		foreach($crucesMarcas as $valor=>$descripcion){
		  $var='';
			if(!$_REQUEST['hemoclasifyGelA'] && $valor=='0'){
        $var='checked';
			}elseif($_REQUEST['hemoclasifyGelA']==$valor){
        $var='checked';
			}
		  $this->salida .= "            <td class=\"label\"><input type=\"radio\" name=\"hemoclasifyGelA\" value=\"$valor\" $var $desabilitar> ".$descripcion['descripcion']." </td>";
		}
    $this->salida .= "            </tr>";
		$this->salida .= "            <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "            <td class=\"label\">ANTI B</td>";
		foreach($crucesMarcas as $valor=>$descripcion){
		  $var='';
			if(!$_REQUEST['hemoclasifyGelB'] && $valor=='0'){
        $var='checked';
			}elseif($_REQUEST['hemoclasifyGelB']==$valor){
        $var='checked';
			}
		  $this->salida .= "            <td class=\"label\"><input type=\"radio\" name=\"hemoclasifyGelB\" value=\"$valor\" $var $desabilitar> ".$descripcion['descripcion']." </td>";
		}
    $this->salida .= "            </tr>";
		$this->salida .= "            <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "            <td class=\"label\">ANTI AB</td>";
		foreach($crucesMarcas as $valor=>$descripcion){
		  $var='';
			if(!$_REQUEST['hemoclasifyGelAB'] && $valor=='0'){
        $var='checked';
			}elseif($_REQUEST['hemoclasifyGelAB']==$valor){
        $var='checked';
			}
		  $this->salida .= "            <td class=\"label\"><input type=\"radio\" name=\"hemoclasifyGelAB\" value=\"$valor\" $var $desabilitar> ".$descripcion['descripcion']." </td>";
		}
    $this->salida .= "            </tr>";
		$this->salida .= "            <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "            <td class=\"label\">ANTI D</td>";
		foreach($crucesMarcas as $valor=>$descripcion){
		  $var='';
			if(!$_REQUEST['hemoclasifyGelD'] && $valor=='0'){
        $var='checked';
			}elseif($_REQUEST['hemoclasifyGelD']==$valor){
        $var='checked';
			}
		  $this->salida .= "            <td class=\"label\"><input type=\"radio\" name=\"hemoclasifyGelD\" value=\"$valor\" $var $desabilitar> ".$descripcion['descripcion']." </td>";
		}
    $this->salida .= "            </tr>";
		$this->salida .= "            <tr class=\"modulo_list_claro\"><td></td></tr>";
		$this->salida .= "        <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "        <td class=\"label\">CELULAS A</td>";
		foreach($crucesMarcas as $valor=>$descripcion){
		  $var='';
			if(!$_REQUEST['celulasA'] && $valor=='0'){
        $var='checked';
			}elseif($_REQUEST['celulasA']==$valor){
        $var='checked';
			}
		  $this->salida .= "            <td class=\"label\"><input type=\"radio\" name=\"celulasA\" value=\"$valor\" $var $desabilitar> ".$descripcion['descripcion']." </td>";
		}
    $this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "        <td class=\"label\">CELULAS B</td>";
		foreach($crucesMarcas as $valor=>$descripcion){
		  $var='';
			if(!$_REQUEST['celulasB'] && $valor=='0'){
        $var='checked';
			}elseif($_REQUEST['celulasB']==$valor){
        $var='checked';
			}
		  $this->salida .= "            <td class=\"label\"><input type=\"radio\" name=\"celulasB\" value=\"$valor\" $var $desabilitar> ".$descripcion['descripcion']." </td>";
		}
    $this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "        <td class=\"label\">CELULAS O</td>";
    foreach($crucesMarcas as $valor=>$descripcion){
		  $var='';
			if(!$_REQUEST['celulas0'] && $valor=='0'){
        $var='checked';
			}elseif($_REQUEST['celulas0']==$valor){
        $var='checked';
			}
		  $this->salida .= "            <td class=\"label\"><input type=\"radio\" name=\"celulas0\" value=\"$valor\" $var $desabilitar> ".$descripcion['descripcion']." </td>";
		}
    $this->salida .= "        </tr>";
		$this->salida .= "            <tr class=\"modulo_list_claro\"><td></td></tr>";
    $this->salida .= "            <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "            <td colspan=\"6\"><label class=\"".$this->SetStyle("grupoGel")."\">INTERPRETACION&nbsp&nbsp&nbsp;</label>";
		if(!$_REQUEST['grupoGel']){
      $_REQUEST['grupoGel']=$grupo.'/'.$rh;
		}
		$this->salida .= "            <select name=\"grupoGel\" class=\"select\" $desabilitar>";
		$facts=$this->ConsultaFactorRh();
		$this->GrupoSanguineo($facts,'False',$_REQUEST['grupoGel']);
		$this->salida .= "            </select></td>";
    $this->salida .= "            </tr>";
		$this->salida .= "            <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "            <td colspan=\"6\"><label class=\"".$this->SetStyle("bacteriologoGel")."\">PROFESIONAL&nbsp&nbsp&nbsp;</label>";
		$this->salida .= "            <select name=\"bacteriologoGel\" class=\"select\" $desabilitar>";
		$bacteriologos=$this->TotalBacteriologos();
		$this->BuscarProfesionlesEspecialistas($bacteriologos,'False',$_REQUEST['bacteriologoGel']);
		$this->salida .= "            </select></td>";
    $this->salida .= "            </tr>";
    $this->salida .= "            </table><BR>";
    $this->salida .= "        </td></tr>";
    $this->salida .= "        </table><BR>";
    $this->salida .= "      <table width=\"95%\" border=\"0\" align=\"center\">";
    $this->salida .= "      <tr class=\"modulo_list_claro\"><td width=\"50%\">";
		$this->salida .= "        <BR><table width=\"95%\" border=\"0\" align=\"center\">";
		$this->salida .= "        <tr class=\"modulo_table_list_title\">";
		$this->salida .= "        <td colspan=\"6\">PRUEBA CRUZADA O DE COMPATIBILIDAD</td></tr>";
		$this->salida .= "        <tr class=\"modulo_table_list_title\">";
		$var1='';$var2='';
		if($_REQUEST['formaResultadoCruze']==2){$var2='checked';}else{$var1='checked';}
		$this->salida .= "        <td colspan=\"6\"><input type=\"radio\" name=\"formaResultadoCruze\" value=\"1\" $var1 $desabilitar>VISUAL&nbsp&nbsp&nbsp&nbsp;";
		$this->salida .= "        <input type=\"radio\" name=\"formaResultadoCruze\" value=\"2\" $var2 $desabilitar>AUTOMATICA";
    $this->salida .= "        </td></tr>";

		$this->salida .= "        <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "        <td class=\"label\">FASE COOMBS</td>";
		foreach($crucesMarcas as $valor=>$descripcion){
		  $var='';
			if(!$_REQUEST['cDirecto'] && $valor=='0'){
        $var='checked';
			}elseif($_REQUEST['cDirecto']==$valor){
        $var='checked';
			}
		  $this->salida .= "       <td class=\"label\"><input type=\"radio\" name=\"cDirecto\" value=\"$valor\" $var $desabilitar> ".$descripcion['descripcion']." </td>";
		}
    $this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "        <td class=\"label\">ENZIMAS</td>";


		foreach($crucesMarcas as $valor=>$descripcion){
		  $var='';
			if(!$_REQUEST['enz'] && $valor=='0'){
        $var='checked';
			}elseif($_REQUEST['enz']==$valor){
        $var='checked';
			}
		  $this->salida .= "            <td class=\"label\"><input type=\"radio\" name=\"enz\" value=\"$valor\" $var $desabilitar> ".$descripcion['descripcion']." </td>";
		}
    $this->salida .= "         </tr>";
		$var1='';$var2='';
		if($_REQUEST['compatibilidad']==2){$var2='checked';}
		else{$var1='checked';}
		$this->salida .= "          <tr class=\"modulo_table_list_title\">";
		$this->salida .= "          <td>COMPATIBILIDAD</td>";
		$this->salida .= "          <td><input type=\"radio\" name=\"compatibilidad\" value=\"1\" $var1 $desabilitar>Si</td>";
		$this->salida .= "          <td><input type=\"radio\" name=\"compatibilidad\" value=\"2\" $var2 $desabilitar>No</td>";
		$this->salida .= "          <td colspan=\"3\">&nbsp;</td>";
    $this->salida .= "          </tr>";
		/*$this->salida .= "        <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "        <td class=\"label\">LECTINA</td>";
		foreach($crucesMarcas as $valor=>$descripcion){
		  $var='';
			if(!$_REQUEST['lectina'] && $valor=='0'){
        $var='checked';
			}elseif($_REQUEST['lectina']==$valor){
        $var='checked';
			}
		  $this->salida .= "            <td class=\"label\"><input type=\"radio\" name=\"lectina\" value=\"$valor\" $var $desabilitar> ".$descripcion['descripcion']." </td>";
		}
    $this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "        <td class=\"label\">CDE</td>";
		foreach($crucesMarcas as $valor=>$descripcion){
		  $var='';
			if(!$_REQUEST['cde'] && $valor=='0'){
        $var='checked';
			}elseif($_REQUEST['cde']==$valor){
        $var='checked';
			}
		  $this->salida .= "            <td class=\"label\"><input type=\"radio\" name=\"cde\" value=\"$valor\" $var $desabilitar> ".$descripcion['descripcion']." </td>";
		}
    $this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "        <td colspan=\"6\"><label class=\"".$this->SetStyle("grupoCruze")."\">INTERPRETACION&nbsp&nbsp&nbsp;</label>";
		$this->salida .= "        <select name=\"grupoCruze\" class=\"select\" $desabilitar>";
		$facts=$this->ConsultaFactorRh();
		$this->GrupoSanguineo($facts,'False',$_REQUEST['grupoCruze']);
		$this->salida .= "        </select></td>";
    $this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "        <td colspan=\"6\"><label class=\"".$this->SetStyle("bacteriologoCruze")."\">PROFESIONAL&nbsp&nbsp&nbsp;</label>";
		$this->salida .= "        <select name=\"bacteriologoCruze\" class=\"select\" $desabilitar>";
		$bacteriologos=$this->TotalBacteriologos();
		$this->BuscarProfesionlesEspecialistas($bacteriologos,'False',$_REQUEST['bacteriologoCruze']);
		$this->salida .= "        </select></td>";
    $this->salida .= "        </tr>";*/
    $this->salida .= "        </table><BR>";
    $this->salida .= "      </td>";
    $this->salida .= "      <td valign=\"top\">";
		$this->salida .= "          <BR><table width=\"95%\" border=\"0\" align=\"center\">";
		$this->salida .= "          <tr class=\"modulo_table_list_title\">";
		$this->salida .= "          <td colspan=\"6\">RASTREO DE ANTICUERPOS(RAI)</td>";
    $this->salida .= "          </tr>";
		$this->salida .= "          <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "          <td class=\"label\">Cel I</td>";
		foreach($crucesMarcas as $valor=>$descripcion){
		  $var='';
			if(!$_REQUEST['CelI'] && $valor=='0'){
        $var='checked';
			}elseif($_REQUEST['CelI']==$valor){
        $var='checked';
			}
		  $this->salida .= "            <td class=\"label\"><input type=\"radio\" name=\"CelI\" value=\"$valor\" $var $desabilitar> ".$descripcion['descripcion']." </td>";
		}
    $this->salida .= "          </tr>";
		$this->salida .= "          <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "          <td class=\"label\">Cel II</td>";
		foreach($crucesMarcas as $valor=>$descripcion){
		  $var='';
			if(!$_REQUEST['CelII'] && $valor=='0'){
        $var='checked';
			}elseif($_REQUEST['CelII']==$valor){
        $var='checked';
			}
		  $this->salida .= "            <td class=\"label\"><input type=\"radio\" name=\"CelII\" value=\"$valor\" $var $desabilitar> ".$descripcion['descripcion']." </td>";
		}
    $this->salida .= "          </tr>";
		$this->salida .= "          <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "          <td class=\"label\">AUTO</td>";
		foreach($crucesMarcas as $valor=>$descripcion){
		  $var='';
			if(!$_REQUEST['Auto'] && $valor=='0'){
        $var='checked';
			}elseif($_REQUEST['Auto']==$valor){
        $var='checked';
			}
		  $this->salida .= "            <td class=\"label\"><input type=\"radio\" name=\"Auto\" value=\"$valor\" $var $desabilitar> ".$descripcion['descripcion']." </td>";
		}
		$this->salida .= "          </tr>";
		$this->salida .= "          <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "          <td class=\"label\">OTROS</td>";
		foreach($crucesMarcas as $valor=>$descripcion){
		  $var='';
			if(!$_REQUEST['OtrosRai'] && $valor=='0'){
        $var='checked';
			}elseif($_REQUEST['OtrosRai']==$valor){
        $var='checked';
			}
		  $this->salida .= "            <td class=\"label\"><input type=\"radio\" name=\"OtrosRai\" value=\"$valor\" $var $desabilitar> ".$descripcion['descripcion']." </td>";
		}
    $this->salida .= "          </tr>";
    $this->salida .= "          </table>";
		$this->salida .= "       </td></tr>";
    $this->salida .= "       </table><BR>";

		$this->salida .= "        <table width=\"95%\" border=\"0\" align=\"center\">";
		$this->salida .= "        <tr class=\"modulo_table_list_title\">";
		$this->salida .= "        <td colspan=\"6\">COMPLEMENTARIAS</td></tr>";
    $this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td class=\"label\">LECTINA</td>";
		foreach($crucesMarcas as $valor=>$descripcion){
		  $var='';
			if(!$_REQUEST['lectina'] && $valor=='0'){
        $var='checked';
			}elseif($_REQUEST['lectina']==$valor){
        $var='checked';
			}
		  $this->salida .= "            <td class=\"label\"><input type=\"radio\" name=\"lectina\" value=\"$valor\" $var $desabilitar> ".$descripcion['descripcion']." </td>";
		}
    $this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td class=\"label\">CDE</td>";
		foreach($crucesMarcas as $valor=>$descripcion){
		  $var='';
			if(!$_REQUEST['cde'] && $valor=='0'){
        $var='checked';
			}elseif($_REQUEST['cde']==$valor){
        $var='checked';
			}
		  $this->salida .= "            <td class=\"label\"><input type=\"radio\" name=\"cde\" value=\"$valor\" $var $desabilitar> ".$descripcion['descripcion']." </td>";
		}
    $this->salida .= "        </tr>";
    $this->salida .= "        </table><BR>";
		$this->salida .= "          <table width=\"95%\" border=\"0\" align=\"center\">";
    $this->salida .= "          <tr class=\"modulo_list_claro\">";
		$this->salida .="           <td width=\"25%\" class=\"".$this->SetStyle("fechaPrueba")."\" align=\"left\">FECHA PRUEBA</td>";
		if(!$_REQUEST['fechaPrueba']){
      $_REQUEST['fechaPrueba']=date("d-m-Y");
		}
		$this->salida .="           <td align=\"left\"><input type=\"text\" readonly class=\"input-text\" size=\"10\" maxlength=\"10\" value=\"".$_REQUEST['fechaPrueba']."\" name=\"fechaPrueba\" onBlur=\"IsValidDate(this,'YYYY-MM-DD')\" onKeyUp=\"setDate(this,'YYYY-MM-DD','es')\">";
		if($consulta!=1){
		  $this->salida .= "          ".ReturnOpenCalendario('forma','fechaPrueba','-')."";
    }
		$this->salida .= "          </td>" ;
		$this->salida .= "          </tr>";
    $this->salida .= "          <tr class=\"modulo_list_claro\">";
		$this->salida.="            <td width=\"25%\" class=\"".$this->SetStyle("horaPrueba")."\" align=\"left\">HORA PRUEBA</td>";
	  $this->salida.="            <td><select size=\"1\" name=\"horaPrueba\" class=\"select\" $desabilitar>";
		$this->salida.="            <option value = -1>Seleccione Hora </option>";
	  for ($j=0;$j<=23; $j++){
      if(($j >= 0) AND ($j<= 9)){
				$hora = '0'.$j;
				if($_REQUEST['horaPrueba']==$hora){
				  $this->salida.="      <option selected value = \"$hora\">0$j</option>";
				}else{
				  $this->salida.="      <option value = \"$hora\">0$j</option>";
				}
			}else{
			  if($_REQUEST['horaPrueba']==$j){
					$this->salida.="      <option selected value = $j>$j</option>";
				}else{
					$this->salida.="      <option value = $j>$j</option>";
				}
			}
    }
    $this->salida.="            </select>&nbsp;";
		$this->salida.="            <select size=\"1\"  name=\"minutosPrueba\" class=\"select\" $desabilitar>";
	  $this->salida.="            <option value = -1>Seleccione Minutos</option>";
		for ($j=0;$j<=59; $j++){
			if(($j >= 0) AND ($j<= 9)){
				$min = '0'.$j;
				if($_REQUEST['minutosPrueba']==$min){
					$this->salida.="      <option selected value = \"$min\" >0$j</option>";
				}else{
					$this->salida.="      <option value=\"$min\">0$j</option>";
				}
			}else{
			  if($_REQUEST['minutosPrueba']==$j){
					$this->salida.="      <option selected value=$j>$j</option>";
				}else{
					$this->salida.="      <option value=$j>$j</option>";
				}
			}
    }
    $this->salida.="            </select>";
    $this->salida.="            </td>";
    $this->salida.="            </tr>";
		$this->salida .= "          <tr class=\"modulo_list_claro\">";
		$this->salida.="            <td width=\"25%\" class=\"label\" colspan=\"2\">OBSERVACIONES<BR><textarea name=\"observaciones\" class =\"textarea\" rows =\"3\" cols =\"80\">".$_REQUEST['observaciones']."</textarea></td>";
		$this->salida .= "          </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td width=\"25%\"><label class=\"".$this->SetStyle("bacteriologoEntrega")."\">PROFESIONAL RESPONSABLE</label></td>";
		$this->salida .= "        <td><select name=\"bacteriologoEntrega\" class=\"select\" $desabilitar>";
		$bacteriologos=$this->TotalBacteriologos();
		$this->BuscarProfesionlesEspecialistas($bacteriologos,'False',$_REQUEST['bacteriologoEntrega']);
		$this->salida .= "        </select></td>";
    $this->salida .= "        </tr>";
    $this->salida .= "        </table><BR>";
		/*$this->salida .= "        <table width=\"95%\" border=\"0\" align=\"center\">";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td width=\"40%\" class=\"".$this->SetStyle("quienRecibe")."\">RECIBE</td>";
		$this->salida .= "        <td><select name=\"quienRecibe\" class=\"select\" $desabilitar>";
		$auxiliares=$this->TotalAuxiliares();
		$this->BuscarProfesionlesEspecialistas($auxiliares,'False',$_REQUEST['quienRecibe']);
		$this->salida .= "        </select></td>";
		$this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .="         <td class=\"".$this->SetStyle("fechaRecibe")."\" align=\"left\">FECHA RECIBE</td>";
		$this->salida .="         <td align=\"left\"><input type=\"text\" readonly class=\"input-text\" size=\"10\" maxlength=\"10\" value=\"".$_REQUEST['fechaRecibe']."\" name=\"fechaRecibe\" onBlur=\"IsValidDate(this,'YYYY-MM-DD')\" onKeyUp=\"setDate(this,'YYYY-MM-DD','es')\">";
		if($consulta!=1){
		  $this->salida .= "        ".ReturnOpenCalendario('forma','fechaRecibe','-')."";
		}
		$this->salida .= "        </td>" ;
		$this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida.="          <td class=".$this->SetStyle("horaRecibe")." align=\"left\">HORA RECIBE</td>";
	  $this->salida.="          <td><select size=\"1\" name=\"horaRecibe\" class=\"select\" $desabilitar>";
		$this->salida.="          <option value = -1>Seleccione Hora </option>";
	  for ($j=0;$j<=23; $j++){
      if(($j >= 0) AND ($j<= 9)){
				$hora = '0'.$j;
				if($_REQUEST['horaRecibe']==$hora){
				  $this->salida.="    <option selected value = \"$hora\">0$j</option>";
				}else{
				  $this->salida.="    <option value = \"$hora\">0$j</option>";
				}
			}else{
			  if($_REQUEST['horaRecibe']==$j){
					$this->salida.="    <option selected value = $j>$j</option>";
				}else{
					$this->salida.="    <option value = $j>$j</option>";
				}
			}
    }
    $this->salida.="          </select>&nbsp;";
		$this->salida.="          <select size=\"1\"  name=\"minutosRecibe\" class=\"select\" $desabilitar>";
	  $this->salida.="          <option value = -1>Seleccione Minutos</option>";
		for ($j=0;$j<=59; $j++){
			if(($j >= 0) AND ($j<= 9)){
				$min = '0'.$j;
				if($_REQUEST['minutosRecibe']==$min){
					$this->salida.="    <option selected value = \"$min\" >0$j</option>";
				}else{
					$this->salida.="    <option value=\"$min\">0$j</option>";
				}
			}else{
			  if($_REQUEST['minutosRecibe']==$j){
					$this->salida.="    <option selected value=$j>$j</option>";
				}else{
					$this->salida.="    <option value=$j>$j</option>";
				}
			}
    }
    $this->salida.="          </select>";
    $this->salida.="          </td>";
    $this->salida.="          </tr>";
    $this->salida .= "        </table><br>";
	  $this->salida .= "    </td></tr>";
		$this->salida .= "    </table>";*/
    $this->salida .= "    <table width=\"90%\" border=\"0\" align=\"center\">";
    $this->salida .= "    <tr><td align=\"center\">";
		if($consulta!=1){
		  $this->salida .= "    <input type=\"submit\" class=\"input-submit\" value=\"INSERTAR\" name=\"insertar\">";
		  $this->salida .= "    <input type=\"submit\" class=\"input-submit\" value=\"CANCELAR\" name=\"cancelar\">";
		}else{
      $this->salida .= "    <input type=\"submit\" class=\"input-submit\" value=\"SALIR\" name=\"salir\">";
		}
		$this->salida .= "    </td></tr>";
		$this->salida .= "    </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table>";
		$this->salida .= "    </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

/**
* Funcion que se encarga de listar los nombres de los profesionales especialistas y visualizarlos
* @return array
* @param array codigos y valores de los profesionales de la base de datos
* @param boolean indicador de selecion de un elemento en el objeto donde se imprimen los profesionales
* @param string elemento seleccionado en el objeto donde se imprimen los profesionales
*/
	function BuscarProfesionlesEspecialistas($profesionales,$Seleccionado='False',$Profesionales=''){
    
		switch($Seleccionado){
			case 'False':{
        $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
				for($i=0;$i<sizeof($profesionales);$i++){
				  $value=$profesionales[$i]['tercero_id'].'/'.$profesionales[$i]['tipo_id_tercero'];
					$titulo=$profesionales[$i]['nombre'];
					if($value==$Profesionales){
					$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				  }else{
            $this->salida .=" <option value=\"$value\">$titulo</option>";
				  }
			  }
			  break;
		  }case 'True':{
			  for($i=0;$i<sizeof($profesionales);$i++){
			    $value=$profesionales[$i]['tercero_id'].'/'.$profesionales[$i]['tipo_id_tercero'];
					$titulo=$profesionales[$i]['nombre'];
				  if($value==$Profesionales){
				    $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				  }
				  $this->salida .=" <option value=\"$value\">$titulo</option>";
			  }
			  break;
		  }
	  }
	}

	function CalcularNumeroPasos($conteo){
		$numpaso=ceil($conteo/$this->limit);
		return $numpaso;
	}

	function CalcularBarra($paso){
		$barra=floor($paso/10)*10;
		if(($paso%10)==0){
			$barra=$barra-10;
		}
		return $barra;
	}

	function CalcularOffset($paso){
		$offset=($paso*$this->limit)-$this->limit;
		return $offset;
	}

	 function RetornarBarra($origen){

		if($this->limit>=$this->conteo){
			return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso)){
			$paso=1;
		}
		if($origen==1){
      $accion=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','LlamaReservasSangreDiarias',array('conteo'=>$this->conteo,"paso"=>$_REQUEST['paso'],"Of"=>$_REQUEST['Of'],"TipoDocumento"=>$_REQUEST['TipoDocumento'],"Documento"=>$_REQUEST['Documento'],"grupoSanguineo"=>$_REQUEST['grupoSanguineo'],"Fecha"=>$_REQUEST['Fecha']));
		}elseif($origen==2){
      $accion=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','LlamaFormaCruzarSangre',array('conteo'=>$this->conteo,"paso"=>$_REQUEST['paso'],"Of"=>$_REQUEST['Of'],"tipoId"=>$_REQUEST['tipoId'],"paciente"=>$_REQUEST['paciente'],"nombre"=>$_REQUEST['nombre'],"fecha"=>$_REQUEST['fecha'],"responsable"=>$_REQUEST['responsable'],"grupo"=>$_REQUEST['grupo'],"rh"=>$_REQUEST['rh'],"reservaId"=>$_REQUEST['reservaId'],
		  "bolsaBusqueda"=>$_REQUEST['bolsaBusqueda'],"grupo_sanguineoBusqueda"=>$_REQUEST['grupo_sanguineoBusqueda'],"fechaBusqueda"=>$_REQUEST['fechaBusqueda']));
		}elseif($origen==3){
      $accion=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','LlamaConsultaCruzesSanguineos',array('conteo'=>$this->conteo,"paso"=>$_REQUEST['paso'],"Of"=>$_REQUEST['Of'],"bolsaBusqueda"=>$_REQUEST['bolsaBusqueda'],
			"numReserva"=>$_REQUEST['numReserva'],"bolsaBusqueda"=>$_REQUEST['bolsaBusqueda'],"TipoDocumento"=>$_REQUEST['TipoDocumento'],"Documento"=>$_REQUEST['Documento'],"fechaPrueba"=>$_REQUEST['fechaPrueba'],"correccion"=>$_REQUEST['correccion']));
		}elseif($origen==4){
		  $accion=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','LlamaCompatibilidadSangre',array('conteo'=>$this->conteo,"paso"=>$_REQUEST['paso'],"Of"=>$_REQUEST['Of'],
			"Documento"=>$_REQUEST['Documento'],"TipoDocumento"=>$_REQUEST['TipoDocumento'],"grupoSanguineo"=>$_REQUEST['grupoSanguineo'],"fechaReserva"=>$_REQUEST['fechaReserva']));
		}
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;

		$this->salida .= "<br><table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1){
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}else{
     // $this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
      //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
    }
		$barra ++;
		if(($barra+10)<=$numpasos){
			for($i=($barra);$i<($barra+10);$i++){
				if($paso==$i){
						$this->salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}else{
						$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}else{
      $diferencia=$numpasos-9;
			if($diferencia<=0){$diferencia=1;}
			for($i=($diferencia);$i<=$numpasos;$i++){
				if($paso==$i){
					$this->salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}else{
					$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos){
  			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}else{
        //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
        //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
      }
		}
		$this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan='15' align='center'>Página $paso de $numpasos</td><tr></table>";
	}

	function ConsultaCruzesSanguineos($bolsaBusqueda,$numReserva,$TipoDocumento,$Documento,$fechaPrueba,$correccion){

		$this->salida .= ThemeAbrirTabla('CONSULTA DE CRUCES SANGUINEOS');
		$accion=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','LlamaConsultaCruzesSanguineos');
		$this->salida .="<script language='javascript'>";
		$this->salida .= 'function mOvr(src,clrOver){';
		$this->salida .= '  src.style.background = clrOver;';
		$this->salida .= '}';
		$this->salida .= 'function mOut(src,clrIn){';
		$this->salida .= '  src.style.background = clrIn;';
		$this->salida .= '}';
		$this->salida .= '</script>';
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"50%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "  <tr><td align=\"center\">";
		$this->salida .=    $this->SetStyle("MensajeError");
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td><fieldset><legend class=\"field\">FILTRO DE BUSQUEDA</legend>";
    $this->salida .= "      <table width=\"98%\" border=\"0\" align=\"center\">";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\">No. BOLSA</td>";
		$this->salida .= "      <td><input type=\"text\" size=\"32\" name=\"bolsaBusqueda\" value=\"".$bolsaBusqueda."\"></td>";
    $this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\">No. RESERVA</td>";
		$this->salida .= "      <td><input type=\"text\" size=\"32\" name=\"numReserva\" value=\"".$numReserva."\"></td>";
    $this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\">TIPO DOCUMENTO </td><td><select name=\"TipoDocumento\" class=\"select\">";
		$tipo_id=$this->tipo_id_paciente();
		$this->BuscarIdPaciente($tipo_id,$TipoDocumento);
		$this->salida .= "      </select></td>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\">DOCUMENTO </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" value=\"".$Documento."\" maxlength=\"32\"></td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .="       <td class=\"".$this->SetStyle("fechaPrueba")."\" align=\"left\">FECHA PRUEBA</td>";
		$this->salida .= "	  	 <td><input size=\"10\" maxlength=\"10\" type=\"text\" name=\"fechaPrueba\" value=\"".$fechaPrueba."\" class=\"input-text\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\">";
		$this->salida .= "	  	 ".ReturnOpenCalendario('forma','fechaPrueba','/')."</td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		if($correccion){
      $var='checked';
		}
		$this->salida .= "      <td class=\"label\">ANULADOS</td><td><input type=\"checkbox\" name=\"correccion\" value=\"1\" $var></td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr colspan=\"2\" class=\"modulo_list_claro\">";
		$this->salida .= "      <td colspan=\"2\" align=\"center\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"filtrar\" value=\"FILTRAR\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"menu\" value=\"MENU\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "	    </fieldset>";
    $this->salida .= "  </td></tr>";
    $this->salida .= "  </table><BR>";

		$crucesSangre=$this->ConsultaCrucesSanguineos($bolsaBusqueda,$numReserva,$TipoDocumento,$Documento,$fechaPrueba,$correccion);
		if($crucesSangre){
      $this->salida .= "        <table width=\"100%\" border=\"0\" align=\"center\">";
			$this->salida .= "        <tr class=\"modulo_table_list_title\">";
			$this->salida .= "        <td>PACIENTE</td>";
			$this->salida .= "        <td>FECHA PRUEBA</td>";
			$this->salida .= "        <td>BOLSA</td>";
			$this->salida .= "        <td>AOB / Rh</td>";
			$this->salida .= "        <td>PROFESIONAL CRUCE</td>";
			$this->salida .= "        <td>AOB / Rh CRUCE</td>";
			$this->salida .= "        <td>COMPATIBLE</td>";
			$this->salida .= "        <td width=\"3%\">&nbsp;</td>";
			$this->salida .= "        <td width=\"3%\">&nbsp;</td>";
			$this->salida .= "        </tr>";
			$y=0;
			$backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
			for($i=0;$i<sizeof($crucesSangre);$i++){
				if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
				$this->salida.="          <tr  class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>";
				$this->salida .= "        <td>".$crucesSangre[$i]['tipo_id_paciente']." ".$crucesSangre[$i]['paciente_id']." - ".$crucesSangre[$i]['nombre']."</td>";
				(list($fecha,$hora)=explode(' ',$crucesSangre[$i]['fecha_prueba']));
        (list($h,$mm)=explode(':',$hora));
				$this->salida .= "        <td>".$fecha." ".$h.":".$mm."</td>";
				$this->salida .= "        <td>".$crucesSangre[$i]['bolsa_id']."</td>";
				if($crucesSangre[$i]['rh']=='+'){
				$this->salida .= "        <td>".$crucesSangre[$i]['grupo_sanguineo']." /&nbsp&nbsp; POSITIVO</td>";
				}elseif($crucesSangre[$i]['rh']=='-'){
        $this->salida .= "        <td>".$crucesSangre[$i]['grupo_sanguineo']." /&nbsp&nbsp;<label class=\"label_error\">NEGATIVO</label></td>";
				}else{
        $this->salida .= "        <td>&nbsp;</td>";
				}
				$this->salida .= "        <td>".$crucesSangre[$i]['profesional']."</td>";

				if($crucesSangre[$i]['interpretacion_rh_cruze']=='+'){
				$this->salida .= "        <td>".$crucesSangre[$i]['interpretacion_grupo_cruze']." /  &nbsp&nbsp&nbsp;POSITIVO</td>";
				}elseif($crucesSangre[$i]['interpretacion_rh_cruze']=='-'){
        $this->salida .= "        <td>".$crucesSangre[$i]['interpretacion_grupo_cruze']." / &nbsp&nbsp&nbsp;<label class=\"label_error\">NEGATIVO</label></td>";
				}else{
        $this->salida .= "        <td>".$crucesSangre[$i]['interpretacion_grupo_cruze']."</td>";
				}

				if($crucesSangre[$i]['compatibilidad']==1){
					$pal='Si';
					$clase='label';
				}else{
					$pal='No';
					$clase='label_error';
				}
				$this->salida .= "        <td class=\"$clase\">$pal</td>";
				$action=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','ConsultaCruzeSangre',array("cruzeid"=>$crucesSangre[$i]['cruze_sanguineo_id']));
				$this->salida .= "	      <td align=\"center\"><a href=\"$action\"><img border=\"0\" title=\"Consultar\" src=\"".GetThemePath()."/images/pconsultar.png\"><a></td>";
				if($crucesSangre[$i]['correccion']==1){
				$action=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','ConsultaCruzeSangre',array("cruzeid"=>$crucesSangre[$i]['cruze_sanguineo_id'],"destino"=>1));
				$this->salida .= "	      <td align=\"center\"><a href=\"$action\"><img title=\"Modificar\" border=\"0\" src=\"".GetThemePath()."/images/modificar.png\"><a></td>";
				}else{
        $this->salida .= "	      <td align=\"center\">&nbsp;</td>";
				}
				$this->salida .= "        </tr>";
			}
			$this->salida .= "		    </table>";
			$this->salida .=$this->RetornarBarra(3);
		}else{
			$this->salida .= "        <table width=\"80%\" border=\"0\" align=\"center\">";
			$this->salida .= "        <tr><td align=\"center\" class=\"label_error\">NO HAY REGISTROS CON ESTOS PARAMETROS</td></tr>";
			$this->salida .= "		    </table>";
		}
		$this->salida .= "  </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function FormaCambioFechaReserva(){
    $this->salida.= ThemeAbrirTabla('CAMBIO DE FECHA PARA LA RESERVA');
		$this->salida .= "	  <table width=\"65%\" border=\"0\" class=\"normal_10\" align=\"center\">";
		$this->salida .= "    <tr><td>";
		$this->salida .= "    <fieldset><legend class=\"field\">DATOS PRINCIPALES DE LA RESERVA</legend>";
		$this->salida .= "    <BR><table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
		$this->salida .= "		<tr><td width=\"25%\"></tr></td>";
		$this->salida .= "		<tr class=\"modulo_list_oscuro\">";
		$this->salida .= "		<td class=\"label\">No. RESERVA</td>";
		$this->salida .= "		<td>".$_REQUEST['reservaId']."</td>";
		$this->salida .= "		<td class=\"label\">FECHA RESERVA</td>";
		$this->salida .= "		<td>".$_REQUEST['fechaReserva']."</td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td class=\"label\">ID PACIENTE</td>";
		$this->salida .= "		<td>".$_REQUEST['tipoId']." ".$_REQUEST['pacienteId']."</td>";
		$this->salida .= "		<td class=\"label\">PACIENTE</td>";
		$this->salida .= "		<td>".$_REQUEST['nombrePac']."</td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td class=\"label\">DEPARTAMENTO</td>";
		$this->salida .= "		<td>".$_REQUEST['departamento']."</td>";
		$this->salida .= "		<td class=\"label\">UBICACION PACIENTE</td>";
		$this->salida .= "		<td>".$_REQUEST['Ubicacion']."</td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr class=\"modulo_list_oscuro\">";
		$this->salida .= "		<td class=\"label\">ABO</td>";
		$this->salida .= "		<td>".$_REQUEST['grupo']."</td>";
		$this->salida .= "		<td class=\"label\">Rh</td>";
		$this->salida .= "		<td>".$_REQUEST['rh']."</td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		</table><BR>";
		$this->salida .= "		</fieldset></td></tr>";
		$this->salida .= "		</table><br>";
		$this->salida .= "	  <table width=\"80%\" border=\"0\" class=\"normal_10\" align=\"center\">";
		$this->salida .= "  <tr><td align=\"center\">";
		$this->salida .=    $this->SetStyle("MensajeError");
		$this->salida .= "  </td></tr>";
		$this->salida .= "    <tr><td>";
		$this->salida .= "    <fieldset><legend class=\"field\">SELECCION FECHA</legend>";
		$this->salida .= "<table border=\"0\" width=\"98%\" align=\"center\">";
    //aqui inserte lo de lorena
		$this->salida .= "<tr class=\"modulo_list_oscuro\"><td width=\"60%\">";
		//$_REQUEST['DiaEspe'];
		$this->salida.="\n".'<script>'."\n";
		$this->salida.='function year1(t)'."\n";
		$this->salida.='{'."\n";
		$this->salida.='window.location.href="Contenido.php?year="+t.elements[0].value+"&meses="+t.elements[1].value+"';
		foreach($_REQUEST as $v=>$v1){
			if($v!='year' and $v!='meses' and $v!='DiaEspe'){
				if (is_array($v1)){
					foreach($v1 as $k2=>$v2){
						if (is_array($v2)){
							foreach($v2 as $k3=>$v3){
								if(is_array($v3)){
									foreach($v3 as $k4=>$v4){
										$this->salida .= "&$v" . "[$k2][$k3][$k4]=$v4";
									}
								}else{
									$this->salida .= "&$v" . "[$k2][$k3]=$v3";
								}
							}
						}else{
							$this->salida .= "&$v" . "[$k2]=$v2";
						}
					}
				}else{
    			$this->salida .= "&$v=$v1";
				}
		  }
		}
		$this->salida.='";'."\n";
		$this->salida.='}'."\n";
		$this->salida.='</script>';

		$this->salida .='<form name="cosa">';
		$this->salida .="<table border=\"0\" width=\"80%\" align=\"center\">";
		$this->salida .='<tr align="center">';
		$this->salida .="<td class=\"label\">AÑO</td><td><select name=\"year\" onchange=\"year1(this.form)\" class=\"select\">";
		if(empty($_REQUEST['year'])){
				$_REQUEST['year']=date("Y");
				$this->AnosAgenda(True,$_REQUEST['year']);
		}else{
			$this->AnosAgenda(true,$_REQUEST['year']);
			$year=$_REQUEST['year'];
		}
		$this->salida .= "</select></td>";
		$this->salida .="<td class=\"label\">MES</td><td><select name=\"mes\" onchange=\"year1(this.form)\" class=\"select\">";
		if(empty($_REQUEST['meses'])){
			$mes=$_REQUEST['meses']=date("m");
			$this->MesesAgenda(True,$year,$mes);
		}else{
			$this->MesesAgenda(True,$year,$_REQUEST['meses']);
			$mes=$_REQUEST['meses'];
		}
		$this->salida .= "</select>";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .='</form>';
		$this->ReturnMetodoExterno('app', 'Agenda', 'user', 'CalendarioEstandard');
		$this->salida .= "   </td>";
		$this->salida .= "<td valign=\"top\" class=\"modulo_list_claro\">";
		$accion=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','ActualizarFechaReserva',array("reservaId"=>$_REQUEST['reservaId'],"tipoId"=>$_REQUEST['tipoId'],"pacienteId"=>$_REQUEST['pacienteId'],
		"nombrePac"=>$_REQUEST['nombrePac'],"fechaReserva"=>$_REQUEST['fechaReserva'],"departamento"=>$_REQUEST['departamento'],"Ubicacion"=>$_REQUEST['Ubicacion'],"grupo"=>$_REQUEST['grupo'],"rh"=>$_REQUEST['rh'],
		"TipoDocumento"=>$_REQUEST['TipoDocumento'],"Documento"=>$_REQUEST['Documento'],"grupoSanguineo"=>$_REQUEST['grupoSanguineo'],"Fecha"=>$_REQUEST['Fecha'],"estado"=>$_REQUEST['estado']));
    $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
    $this->salida .="<table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "<tr>";
		$this->salida .= "<td class=\"label\">NUEVA FECHA RESERVA</td>";
		if(!$_REQUEST['DiaEspe']){
      $fecha=date('d-m-Y');
		}else{
      (list($ano,$mes,$dia)=explode('-',$_REQUEST['DiaEspe']));
      $fecha=$dia.'-'.$mes.'-'.$ano;
		}
		$this->salida .= "<td><input class=\"input-text\" type=\"text\" size=\"10\" name=\"nuevaFecha\" readonly value=\"".$fecha."\"></td>";
		$this->salida .= "</tr>";
		$this->salida .= "<tr>";
		$this->salida .= "<td class=".$this->SetStyle("hora")." align=\"left\">HORA DE LA RESERVA</td>";
	  $this->salida .= "<td>";
		$this->salida .= "          <select size=\"1\" name=\"hora\" class=\"select\" $desabilitado>";
		$this->salida .= "          <option value = -1>Seleccione Hora </option>";
	  for ($j=0;$j<=23; $j++){
      if (($j >= 0) AND ($j<= 9)){
				$hora = '0'.$j;
				if($_REQUEST['hora']==$hora){
				  $this->salida.="    <option selected value = \"$hora\">0$j</option>";
				}else{
				  $this->salida.="    <option value = \"$hora\">0$j</option>";
				}
			}else{
			  if($_REQUEST['hora']==$j){
					$this->salida.="    <option selected value = $j>$j</option>";
				}else{
					$this->salida.="    <option value = $j>$j</option>";
				}
			}
    }
    $this->salida .= "        </select>";
    $this->salida .= "</td>";
    $this->salida .= "</tr>";
		$this->salida .= "<tr>";
		$this->salida .= "<td class=".$this->SetStyle("minutos")." align=\"left\">MINUTOS DE LA RESERVA</td>";
		$this->salida .= "<td>";
    $this->salida .= "  <select size=\"1\"  name=\"minutos\" class=\"select\" $desabilitado>";
	  $this->salida .= "  <option value = -1>Seleccione Minutos</option>";
		for ($j=0;$j<=59; $j++){
			if(($j >= 0) AND ($j<= 9)){
				$min = '0'.$j;
				if($_REQUEST['minutos']==$min){
					$this->salida.="    <option selected value = \"$min\" >0$j</option>";
				}else{
					$this->salida.="    <option value=\"$min\">0$j</option>";
				}
			}else{
			  if($_REQUEST['minutos']==$j){
					$this->salida.="    <option selected value=$j>$j</option>";
				}else{
					$this->salida.="    <option value=$j>$j</option>";
				}
			}
    }
    $this->salida .= "  </select>";
    $this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "<tr><td colspan=\"2\" align=\"center\"><BR></td></tr>";
		$this->salida .= "<tr><td colspan=\"2\" align=\"center\">";
		$this->salida .= "<input class=\"input-submit\" type=\"submit\" name=\"Menu\" value=\"MENU\">";
		$this->salida .= "<input class=\"input-submit\" type=\"submit\" name=\"Guardar\" value=\"GUARDAR\">";
		$this->salida .= "</td></tr>";
    $this->salida .= "</table>";
    $this->salida .= "</td></tr>";
		$this->salida .= "</table>";
		$this->salida .= "</fieldset></td></tr>";
		$this->salida .= "</table><br>";
		$this->salida .= "</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function FormaResultadosCruzeResumen($bolsa,$tipoId,$paciente,$nombre,$fechaReserva,$responsable,$grupo,$rh,$reservaId,
		$bolsaNum,$sello,$fechaVence,$grupoBolsa,$rhBolsa,$nomTercero,$fechaExtraccion,$consulta){
    if($consulta==1){
      $desabilitar='disabled';
		}
		$this->salida .= ThemeAbrirTabla('REGISTRO RESULTADO COMPATIBLIDAD');
		$accion=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','GuardarCruzeSangre',array("bolsa"=>$bolsa,"tipoId"=>$tipoId,"paciente"=>$paciente,"nombre"=>$nombre,"fechaReserva"=>$fechaReserva,"responsable"=>$responsable,"grupo"=>$grupo,"rh"=>$rh,"reservaId"=>$reservaId,
		"bolsaNum"=>$bolsaNum,"sello"=>$sello,"fechaVence"=>$fechaVence,"grupoBolsa"=>$grupoBolsa,"rhBolsa"=>$rhBolsa,"nomTercero"=>$nomTercero,"fechaExtraccion"=>$fechaExtraccion,"origen"=>$_REQUEST['origen']));
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "<tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "</td><tr>";
		$this->salida .= "  <tr><td></td></tr>";
		$this->salida .= "  <tr><td><fieldset><legend class=\"field\">DATOS PRINCIPALES</legend>";
		$this->salida .= "  <table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "  <tr><td width=\"50%\">";
    $this->salida .= "        <table width=\"100%\" border=\"0\" align=\"center\">";
		$this->salida .= "        <tr class=\"modulo_table_title\"><td colspan=\"4\">DATOS DEL PACIENTE</td></tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td width=\"25%\" class=\"label\">PACIENTE</td>";
    $this->salida .= "        <td colspan=\"3\">".$tipoId." ".$paciente." ".$nombre."</td>";
    $this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td width=\"25%\" class=\"label\">FECHA RESERVA</td>";
    $this->salida .= "        <td>".$fechaReserva."</td>";
		$this->salida .= "        <td width=\"25%\" class=\"label\">ABO / Rh</td>";
		if($rh=='+'){
    $this->salida .= "        <td>".$grupo." /&nbsp&nbsp&nbsp;POSITIVO</td>";
		}elseif($rh=='-'){
    $this->salida .= "        <td>".$grupo." /&nbsp&nbsp&nbsp;<label class=\"label_error\">NEGATIVO</label></td>";
		}else{
    $this->salida .= "        <td>".$grupo."</td>";
		}
		$this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td width=\"25%\" class=\"label\">RESPONSABLE</td>";
    $this->salida .= "        <td colspan=\"3\">".$responsable."</td>";
    $this->salida .= "        </tr>";
		$this->salida .= "        </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td width=\"50%\">";
		$this->salida .= "        <table width=\"100%\" border=\"0\" align=\"center\">";
		$this->salida .= "        <tr class=\"modulo_table_title\"><td colspan=\"4\">DATOS DE LA UNIDAD SANGUINEA</td></tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td class=\"label\">NUMERO BOLSA</td>";
    $this->salida .= "        <td>".$bolsaNum."</td>";
		$this->salida .= "        <td class=\"label\">SELLO</td>";
    $this->salida .= "        <td>".$sello."</td>";
    $this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td class=\"label\">ABO / Rh</td>";
		if($rhBolsa=='+'){
    $this->salida .= "        <td>".$grupoBolsa." /&nbsp&nbsp&nbsp;POSITIVO</td>";
		}elseif($rhBolsa=='-'){
    $this->salida .= "        <td>".$grupoBolsa." /&nbsp&nbsp&nbsp;<label class=\"label_error\">NEGATIVO</label></td>";
		}else{
    $this->salida .= "        <td>".$grupoBolsa."</td>";
		}
		$this->salida .= "        <td  class=\"label\">FECHA VENCIMIENTO</td>";
    $this->salida .= "        <td>".$fechaVence."</td>";
    $this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td class=\"label\">PROCEDENCIA</td>";
    $this->salida .= "        <td colspan=\"3\">".$nomTercero."</td>";
		$this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td class=\"label\">FECHA EXTRACCION</td>";
    $this->salida .= "        <td colspan=\"3\">".$fechaExtraccion."</td>";
		$this->salida .= "        </tr>";
		$this->salida .= "        </table>";
		$this->salida .= "	      </fieldset></td></tr></table>";
    $this->salida .= "  </td></tr>";
		$this->salida .= "  </table><BR>";
		$this->salida .= "    <table width=\"85%\" border=\"0\" align=\"center\">";
		(list($dia,$mes,$ano)=explode('/',$_REQUEST['fechaPrueba']));
		$FechaConver1=mktime($_REQUEST['horaPrueba'],$_REQUEST['minutosPrueba'],0,$mes,$dia,$ano);
		$this->salida .= "    <tr class=\"modulo_table_title\"><td align=\"center\">RESULTADO DEL CRUCE</td></tr><tr class=\"modulo_table_title\"><td align=\"center\">FECHA PRUEBA&nbsp&nbsp&nbsp&nbsp;".strftime("%A %d de  %B de %Y a las %H Horas con %M minutos",$FechaConver1)."</td></tr>";
    $this->salida .= "    <tr><td class=\"modulo_list_oscuro\">";
		$this->salida .= "        <BR><table width=\"95%\" border=\"0\" align=\"center\">";
    $this->salida .= "        <tr class=\"modulo_table_list_title\"><td colspan=\"2\">HEMOCLASIFICACION DEL PACIENTE</td></tr>";
    $this->salida .= "        <tr class=\"modulo_list_claro\"><td valign=\"top\">";
    $this->salida .= "            <table width=\"95%\" border=\"0\" align=\"center\">";
		$this->salida .= "            <tr class=\"modulo_table_list_title\"><td colspan=\"4\">MANUAL</td></tr>";
    $this->salida .= "            <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "            <td class=\"label\">ANTI A</td>";
		if($_REQUEST['hemoclasifyManualA']>0){$var='POSITIVO'.' '.$_REQUEST['hemoclasifyManualA_des'];}
		else{$var='NEGATIVO';}
		$this->salida .= "            <td>$var</td>";
		if($_REQUEST['hemoclasifyManualB']>0){$var='POSITIVO'.' '.$_REQUEST['hemoclasifyManualB_des'];}
		else{$var='NEGATIVO';}
		$this->salida .= "            <td class=\"label\">ANTI B</td>";
		$this->salida .= "            <td>$var</td>";
    $this->salida .= "            </tr>";
		if($_REQUEST['hemoclasifyManualAB']>0){$var='POSITIVO'.' '.$_REQUEST['hemoclasifyManualAB_des'];}
		else{$var='NEGATIVO';}
		$this->salida .= "            <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "            <td class=\"label\">ANTI AB</td>";
		$this->salida .= "            <td>$var</td>";
		if($_REQUEST['hemoclasifyManualD']>0){$var='POSITIVO'.' '.$_REQUEST['hemoclasifyManualD_des'];}
		else{$var='NEGATIVO';}
		$this->salida .= "            <td class=\"label\">ANTI D</td>";
		$this->salida .= "            <td>$var</td>";
    $this->salida .= "            </tr>";
		$this->salida .= "            <tr class=\"modulo_list_claro\"><td></td></tr>";
		$this->salida .= "            <tr class=\"modulo_list_oscuro\"><td class=\"label\" align=\"center\" colspan=\"4\">&nbsp;</td></tr>";
		(list($grupoManual,$rhManual)=explode('/',$_REQUEST['grupoManual']));
		if($rhManual=='-'){$class='label_error';}else{$class='';}
    $this->salida .= "            <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "            <td class=\"label\">ABO</td>";
		$this->salida .= "            <td>$grupoManual</td>";
		$this->salida .= "            <td class=\"label\">Rh</td>";
		$this->salida .= "            <td class=\"$class\">&nbsp&nbsp;$rhManual</td>";
    $this->salida .= "            </tr>";
		$this->salida .= "            <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "            <td colspan=\"4\"><label class=\"label\">PROFESIONAL&nbsp&nbsp&nbsp;</label>";
		$this->salida .= "            ".$_REQUEST['profesionalmanual']."</td>";
    $this->salida .= "            </tr>";
    $this->salida .= "            </table><BR>";
    $this->salida .= "        </td><td>";
    $this->salida .= "            <table width=\"90%\" border=\"0\" align=\"center\">";
		$this->salida .= "            <tr class=\"modulo_table_list_title\"><td colspan=\"4\">CON GEL</td></tr>";
    $this->salida .= "            <tr class=\"modulo_list_oscuro\">";
    if($_REQUEST['hemoclasifyGelA']>0){$var='POSITIVO'.' '.$_REQUEST['hemoclasifyGelA_des'];}
		else{$var='NEGATIVO';}
		$this->salida .= "            <td class=\"label\">ANTI A</td>";
		$this->salida .= "            <td>$var</td>";
		if($_REQUEST['hemoclasifyGelB']>0){$var='POSITIVO'.' '.$_REQUEST['hemoclasifyGelB_des'];}
		else{$var='NEGATIVO';}
		$this->salida .= "            <td class=\"label\">ANTI B</td>";
		$this->salida .= "            <td>$var</td>";
    $this->salida .= "            </tr>";
		if($_REQUEST['hemoclasifyGelAB']>0){$var='POSITIVO'.' '.$_REQUEST['hemoclasifyGelAB_des'];}
		else{$var='NEGATIVO';}
		$this->salida .= "            <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "            <td class=\"label\">ANTI AB</td>";
		$this->salida .= "            <td>$var</td>";
		if($_REQUEST['hemoclasifyGelD']>0){$var='POSITIVO'.' '.$_REQUEST['hemoclasifyGelD_des'];}
		else{$var='NEGATIVO';}
		$this->salida .= "            <td class=\"label\">ANTI D</td>";
		$this->salida .= "            <td>$var</td>";
    $this->salida .= "            </tr>";
    $this->salida .= "            <tr class=\"modulo_list_claro\"><td></td></tr>";
		if($_REQUEST['celulasA']>0){$var='POSITIVO'.' '.$_REQUEST['celulasA_des'];}
		else{$var='NEGATIVO';}
		$this->salida .= "        <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "        <td class=\"label\">CELULAS A</td>";
		$this->salida .= "        <td>$var</td>";
		if($_REQUEST['celulasB']>0){$var='POSITIVO'.' '.$_REQUEST['celulasB_des'];}
		else{$var='NEGATIVO';}
		$this->salida .= "        <td class=\"label\">CELULAS B</td>";
		$this->salida .= "        <td>$var</td>";
    $this->salida .= "        </tr>";
		if($_REQUEST['celulas0']>0){$var='POSITIVO'.' '.$_REQUEST['celulas0_des'];}
		else{$var='NEGATIVO';}
		$this->salida .= "        <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "        <td class=\"label\">CELULAS O</td>";
		$this->salida .= "        <td colspan=\"3\">$var</td>";
    $this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\"><td></td></tr>";
		$this->salida .= "            <tr class=\"modulo_list_oscuro\"><td class=\"label\" align=\"center\" colspan=\"4\">&nbsp;</td></tr>";
		(list($grupoGel,$rhGel)=explode('/',$_REQUEST['grupoGel']));
		if($rhGel=='-'){$class='label_error';}else{$class='';}
    $this->salida .= "            <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "            <td class=\"label\">ABO</td>";
		$this->salida .= "            <td>$grupoGel</td>";
		$this->salida .= "            <td class=\"label\">Rh</td>";
		$this->salida .= "            <td class=\"$class\">&nbsp&nbsp;$rhGel</td>";
    $this->salida .= "            </tr>";
		$this->salida .= "            <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "            <td colspan=\"4\"><label class=\"label\">PROFESIONAL&nbsp&nbsp&nbsp;</label>";
		$this->salida .= "            ".$_REQUEST['profesionalgel']."</td>";
    $this->salida .= "            </tr>";
    $this->salida .= "            </table><BR>";
    $this->salida .= "        </td></tr>";
    $this->salida .= "        </table><BR>";
    $this->salida .= "      <table width=\"95%\" border=\"0\" align=\"center\">";
    $this->salida .= "      <tr class=\"modulo_list_claro\"><td width=\"50%\">";
		$this->salida .= "        <BR><table width=\"95%\" border=\"0\" align=\"center\">";
		$this->salida .= "        <tr class=\"modulo_table_list_title\">";
		$this->salida .= "        <td colspan=\"4\">PRUEBA CRUZADA O DE COMPATIBILIDAD</td>";
		$this->salida .= "        <tr class=\"modulo_table_list_title\">";
		if($_REQUEST['formaResultadoCruze']==2){$var='AUTOMATICA';}else{$var='VISUAL';}
		$this->salida .= "        <td colspan=\"4\">$var</td></tr>";

		if($_REQUEST['cDirecto']>0){$var='POSITIVO'.' '.$_REQUEST['cDirecto_des'];$clase='label_error';}
		else{$var='NEGATIVO';$clase=='';}
		$this->salida .= "          <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "          <td width=\"25%\" class=\"label\">FASE COOMBS</td>";
		$this->salida .= "          <td>$var</td>";
		if($_REQUEST['enz']>0){$var='POSITIVO'.' '.$_REQUEST['enz_des'];}
		else{$var='NEGATIVO';}
		$this->salida .= "          <td width=\"25%\" class=\"label\">ENZIMAS</td>";
		$this->salida .= "          <td>$var</td>";
    $this->salida .= "          </tr>";
		if($_REQUEST['compatibilidad']==2){$var='No';$class='label_error';}
		else{$var='Si';$class='label';}
		$this->salida .= "         <tr class=\"modulo_table_list_title\">";
		$this->salida .= "         <td colspan=\"4\">COMPATIBLE</td>";
		$this->salida .= "         </tr>";
		$this->salida .= "         <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "         <td colspan=\"4\" align=\"center\" class=\"$class\">$var</td>";
    $this->salida .= "         </tr>";
		/*$this->salida .= "            <tr class=\"modulo_list_oscuro\"><td class=\"label\" align=\"center\" colspan=\"4\">&nbsp;</td></tr>";
		(list($grupoCruze,$rhCruze)=explode('/',$_REQUEST['grupoCruze']));
		if($rhCruze=='-'){$class='label_error';}else{$class='';}
    $this->salida .= "            <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "            <td class=\"label\">ABO</td>";
		$this->salida .= "            <td>$grupoCruze</td>";
		$this->salida .= "            <td class=\"label\">Rh</td>";
		$this->salida .= "            <td class=\"$class\">&nbsp&nbsp;$rhCruze</td>";
    $this->salida .= "            </tr>";
		$this->salida .= "        <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "            <td colspan=\"4\"><label class=\"label\">PROFESIONAL&nbsp&nbsp&nbsp;</label>";
		$this->salida .= "            ".$_REQUEST['profesionalcruze']."</td>";
    $this->salida .= "        </tr>";*/
    $this->salida .= "        </table><BR>";
    $this->salida .= "      </td>";
    $this->salida .= "      <td valign=\"top\">";
		$this->salida .= "          <BR><table width=\"95%\" border=\"0\" align=\"center\">";
		$this->salida .= "          <tr class=\"modulo_table_list_title\">";
		$this->salida .= "          <td colspan=\"4\">RASTREO DE ANTICUERPOS(RAI)</td>";
    $this->salida .= "          </tr>";
    if($_REQUEST['CelI']>0){$var='POSITIVO'.' '.$_REQUEST['CelI_des'];$clase='label_error';}
		else{$var='NEGATIVO';$clase='';}
		$this->salida .= "          <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "          <td class=\"label\">Cel I</td>";
		$this->salida .= "          <td class=\"$clase\">$var</td>";
		if($_REQUEST['CelII']>0){$var='POSITIVO'.' '.$_REQUEST['CelII_des'];$clase='label_error';}
		else{$var='NEGATIVO';$clase='';}
		$this->salida .= "          <td class=\"label\">Cel II</td>";
		$this->salida .= "          <td class=\"$clase\">$var</td>";
    $this->salida .= "          </tr>";
		if($_REQUEST['Auto']>0){$var='POSITIVO'.' '.$_REQUEST['Auto_des'];$clase='label_error';}
		else{$var='NEGATIVO';$clase='';}
		$this->salida .= "          <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "          <td class=\"label\">AUTO</td>";
		$this->salida .= "          <td class=\"$clase\">$var</td>";
		if($_REQUEST['OtrosRai']>0){$var='POSITIVO'.' '.$_REQUEST['OtrosRai_des'];$clase='label_error';}
		else{$var='NEGATIVO';$clase='';}
		$this->salida .= "          <td class=\"label\">OTROS</td>";
		$this->salida .= "          <td class=\"$clase\">$var</td>";
    $this->salida .= "          </tr>";
    $this->salida .= "          </table>";
		$this->salida .= "       </td></tr>";
    $this->salida .= "      </table><BR>";
		$this->salida .= "          <table width=\"95%\" border=\"0\" align=\"center\">";
		$this->salida .= "          <tr class=\"modulo_table_list_title\"><td colspan=\"4\">COMPLEMENTARIAS</td></tr>";
    if($_REQUEST['lectina']>0){$var='POSITIVO'.' '.$_REQUEST['lectina_des'];}
		else{$var='NEGATIVO';}
		$this->salida .= "          <tr class=\"modulo_list_claro\">";
		$this->salida .= "          <td width=\"10%\" class=\"label\">LECTINA</td>";
		$this->salida .= "          <td>$var</td>";
		if($_REQUEST['cde']>0){$var='POSITIVO'.' '.$_REQUEST['cde_des'];}
		else{$var='NEGATIVO';}
		$this->salida .= "          <td width=\"10%\" class=\"label\">CDE</td>";
		$this->salida .= "          <td>$var</td>";
    $this->salida .= "          </tr>";
    $this->salida .= "          </table><BR>";
		$this->salida .= "          <table width=\"95%\" border=\"0\" align=\"center\">";
		$this->salida .= "          <tr class=\"modulo_list_claro\">";
		$this->salida.="            <td width=\"25%\" class=\"label\">OBSERVACIONES</td>";
		$this->salida.="            <td>".$_REQUEST['observaciones']."</td>";
		$this->salida .= "          </tr>";
		$this->salida .= "          <tr class=\"modulo_list_claro\">";
		$this->salida .= "          <td><label class=\"label\">PROFESIONAL RESPONSABLE RESULTADOS</label></td>";
		$this->salida .= "          <td>".$_REQUEST['profesionalResponsable']."</td>";
    $this->salida .= "          </tr>";
		if($_REQUEST['profesionalentrega'] && $_REQUEST['profesionalrecibe'] && $_REQUEST['fechaRecibe'] && $_REQUEST['horaRecibe'] && $_REQUEST['minutosRecibe']){
		$this->salida .= "          <tr class=\"modulo_list_claro\">";
		$this->salida .= "          <td><label class=\"label\">PROFESIONAL QUE ENTREGO</label></td>";
		$this->salida .= "          <td>".$_REQUEST['profesionalentrega']."</td>";
    $this->salida .= "          </tr>";
		$this->salida .= "          <tr class=\"modulo_list_claro\">";
		$this->salida .= "          <td><label class=\"label\">PROFESIONAL QUE RECIBIO</label></td>";
		$this->salida .= "          <td>".$_REQUEST['profesionalrecibe']."</td>";
		$this->salida .= "          </tr>";
		$this->salida .= "          <tr class=\"modulo_list_claro\">";
		$this->salida.="            <td width=\"25%\" class=\"label\">FECHA RECIBIDO</td>";
    (list($dia,$mes,$ano)=explode('/',$_REQUEST['fechaRecibe']));
		$FechaConver=mktime($_REQUEST['horaRecibe'],$_REQUEST['minutosRecibe'],0,$mes,$dia,$ano);
    $this->salida.="            <td>".strftime("%A %d de  %B de %Y a las %H Horas con %M minutos",$FechaConver)."</td>";
		$this->salida .= "          </tr>";
		}else{
      $this->salida .= "          <tr class=\"modulo_list_claro\">";
		  $this->salida.="            <td width=\"25%\" colspan=\"2\"class=\"label_error\">RESULTADOS NO ENTREGADOS</td>";
			$this->salida .= "          </tr>";

		}
    $this->salida .= "          </table><BR>";
	  $this->salida .= "    </td></tr>";
		$this->salida .= "    </table>";
    $this->salida .= "    <table width=\"90%\" border=\"0\" align=\"center\">";
    $this->salida .= "    <tr><td align=\"center\">";
    $this->salida .= "    <input type=\"submit\" class=\"input-submit\" value=\"SALIR\" name=\"salir\">";
		$this->salida .= "    </td></tr>";
		$this->salida .= "    </table>";
		$this->salida .= "    </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	/**
	* La funcion FormaMensaje se encarga de retornar un mensaje para el usuario
	* @return boolean
	* @param string mensaje a retornar para el usuario
	* @param string titulo de la ventana a mostrar
	* @param string lugar a donde debe retornar la ventana
	* @param boolean tipo boton de la ventana
	*/
	function FormaConfirmacion($mensaje,$titulo,$accion,$accionAceptar,$accionCancelar){

		$this->salida .= ThemeAbrirTabla($titulo);
		$this->salida .= "			      <table class=\"normal_10\" width=\"60%\" align=\"center\">";
		$this->salida .= "		         <tr><td colspan=\"2\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>";
		$this->salida .= "			       <tr>";
		$this->salida .= "            <form name=\"forma\" action=\"$accionAceptar\" method=\"post\">";
		$this->salida .= "			       <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"></td>";
    $this->salida .= "			      </form>";
    $this->salida .= "            <form name=\"forma\" action=\"$accionCancelar\" method=\"post\">";
		$this->salida .= "			       <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"CANCELAR\"></td>";
    $this->salida .= "			      </form>";
		$this->salida .= "			     </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function RegistroFactorSanguineoPaciente($TipoId,$PacienteId,$cambio,$accion){

    $this->salida .= ThemeAbrirTabla('REGISTRO RESULTADO GRUPO SANGUINEO PACIENTE');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input type=\"hidden\" name=\"TipoId\" value=\"$TipoId\">";
		$this->salida .= "  <input type=\"hidden\" name=\"PacienteId\" value=\"$PacienteId\">";
		$this->salida .= "  <input type=\"hidden\" name=\"cambio\" value=\"$cambio\">";
		$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "  <tr><td align=\"center\">";
		$this->salida .=    $this->SetStyle("MensajeError");
		$this->salida .= "  </td><tr>";
    $this->salida.="    <tr class=\"modulo_table_list_title\">";
    $this->salida.="    <td colspan=\"4\">$TipoId&nbsp&nbsp;$PacienteId&nbsp&nbsp&nbsp&nbsp;".$nombre=$this->BuscarNombresPaciente($TipoId,$PacienteId)."</td>";
    $this->salida.="    </tr>";
    $this->salida.="    <tr class = \"modulo_list_claro\">";
		$this->salida.="     <td align=\"left\" class=\"".$this->SetStyle("grupo_sanguineo")."\">GRUPO SANGUINEO</td>";
		$this->salida .= "   <td><select name=\"grupo_sanguineo\" class=\"select\" $desabilitado>";
		$facts=$this->ConsultaFactor();
		$this->MostrasSelect($facts,'False',$_REQUEST['grupo_sanguineo']);
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
		$this->BuscarProfesionlesEspecialistas($bacteriologos,'False',$_REQUEST['bacteriologo']);
		$this->salida .= "    </select></td>";
    $this->salida.="     </tr>";
		$this->salida .="    <tr class = \"modulo_list_claro\">";
    $this->salida.="     <td align=\"left\" colspan=\"4\"><b>OBSERVACIONES</b><br><textarea style=\"width:100%\" name=\"observaciones\" class=\"textarea\" rows=\"3\" cols=\"60\"></textarea></td>";
    $this->salida.="     </tr>";
		$this->salida.="     </table>";
    $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\" class=\"normal_10\">";
    $this->salida.="     <tr><td align=\"center\">";
    $this->salida.="     <input type=\"submit\" name=\"Cancelar\" value=\"CANCELAR\" class=\"input-submit\">";
		$this->salida.="     <input type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\" class=\"input-submit\">";
		$this->salida.="     </td></tr>";
    $this->salida.="     </table>";
    $this->salida .= "   </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function FormaConfirmarGrupo($bolsa,$tipoId,$paciente,$nombre,$reservaId,$hemoclasifyManualA,
		$hemoclasifyManualB,$hemoclasifyManualAB,$hemoclasifyManualD,$grupoManual,$bacteriologoManual,
		$hemoclasifyGelA,$hemoclasifyGelB,$hemoclasifyGelAB,$hemoclasifyGelD,$grupoGel,$bacteriologoGel,
		$formaResultadoCruze,$CelI,$CelII,$Auto,$OtrosRai,$lectina,$cde,$celulasA,$celulasB,$celulas0,
		$fechaPrueba,$horaPrueba,$minutosPrueba,$observaciones,$enz,
		$cDirecto,$compatibilidad,$bacteriologoEntrega,$quienRecibe,$fechaRecibe,$horaRecibe,
		$minutosRecibe,$grupoRegister,$cruzeid,$bolsaBusqueda,$numReserva,$origen,
		$fechaReserva,$responsable,$grupo,$rh,$reservaId,
		$bolsaNum,$sello,$fechaVence,$grupoBolsa,$rhBolsa,
		$nomTercero,$fechaExtraccion){
    $this->salida .= ThemeAbrirTabla('CONFIRMAR CAMBIO DEL GRUPO SANGUINEO DEL PACIENTE');
		if($origen!=1){
    $accion=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','GuardarConfirmacionGrupo',array("bolsa"=>$bolsa,"tipoId"=>$tipoId,
		"paciente"=>$paciente,"nombre"=>$nombre,"reservaId"=>$reservaId,"hemoclasifyManualA"=>$hemoclasifyManualA,
		"hemoclasifyManualB"=>$hemoclasifyManualB,"hemoclasifyManualAB"=>$hemoclasifyManualAB,"hemoclasifyManualD"=>$hemoclasifyManualD,
		"grupoManual"=>$grupoManual,"bacteriologoManual"=>$bacteriologoManual,
		"hemoclasifyGelA"=>$hemoclasifyGelA,"hemoclasifyGelB"=>$hemoclasifyGelB,"hemoclasifyGelAB"=>$hemoclasifyGelAB,
		"hemoclasifyGelD"=>$hemoclasifyGelD,"grupoGel"=>$grupoGel,"bacteriologoGel"=>$bacteriologoGel,
		"formaResultadoCruze"=>$formaResultadoCruze,"CelI"=>$CelI,"CelII"=>$CelII,"Auto"=>$Auto,"OtrosRai"=>$OtrosRai,"lectina"=>$lectina,
		"cde"=>$cde,"celulasA"=>$celulasA,"celulasB"=>$celulasB,"celulas0"=>$celulas0,
		"fechaPrueba"=>$fechaPrueba,"horaPrueba"=>$horaPrueba,
		"minutosPrueba"=>$minutosPrueba,"observaciones"=>$observaciones,"enz"=>$enz,
		"cDirecto"=>$cDirecto,"compatibilidad"=>$compatibilidad,"bacteriologoEntrega"=>$bacteriologoEntrega,"quienRecibe"=>$quienRecibe,
		"fechaRecibe"=>$fechaRecibe,"horaRecibe"=>$horaRecibe,
		"minutosRecibe"=>$minutosRecibe,"grupoRegister"=>$grupoRegister,"fechaReserva"=>$fechaReserva,"responsable"=>$responsable,"grupo"=>$grupo,
		"rh"=>$rh,"reservaId"=>$reservaId,"bolsaNum"=>$bolsaNum,"sello"=>$sello,"fechaVence"=>$fechaVence,"grupoBolsa"=>$grupoBolsa,"rhBolsa"=>$rhBolsa,
		"nomTercero"=>$nomTercero,"fechaExtraccion"=>$fechaExtraccion));
		}else{
     $accion=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','GuardarCorreccionCruce',array("bolsa"=>$bolsa,"tipoId"=>$tipoId,
		"paciente"=>$paciente,"nombre"=>$nombre,"reservaId"=>$reservaId,"hemoclasifyManualA"=>$hemoclasifyManualA,
		"hemoclasifyManualB"=>$hemoclasifyManualB,"hemoclasifyManualAB"=>$hemoclasifyManualAB,"hemoclasifyManualD"=>$hemoclasifyManualD,
		"grupoManual"=>$grupoManual,"bacteriologoManual"=>$bacteriologoManual,
		"hemoclasifyGelA"=>$hemoclasifyGelA,"hemoclasifyGelB"=>$hemoclasifyGelB,"hemoclasifyGelAB"=>$hemoclasifyGelAB,
		"hemoclasifyGelD"=>$hemoclasifyGelD,"grupoGel"=>$grupoGel,"bacteriologoGel"=>$bacteriologoGel,
		"formaResultadoCruze"=>$formaResultadoCruze,"CelI"=>$CelI,"CelII"=>$CelII,"Auto"=>$Auto,"OtrosRai"=>$OtrosRai,"lectina"=>$lectina,
		"cde"=>$cde,"celulasA"=>$celulasA,"celulasB"=>$celulasB,"celulas0"=>$celulas0,
		"fechaPrueba"=>$fechaPrueba,"horaPrueba"=>$horaPrueba,
		"minutosPrueba"=>$minutosPrueba,"observaciones"=>$observaciones,"enz"=>$enz,
		"cDirecto"=>$cDirecto,"compatibilidad"=>$compatibilidad,"bacteriologoEntrega"=>$bacteriologoEntrega,"quienRecibe"=>$quienRecibe,
		"fechaRecibe"=>$fechaRecibe,"horaRecibe"=>$horaRecibe,
		"minutosRecibe"=>$minutosRecibe,"grupoRegister"=>$grupoRegister,"cruzeid"=>$cruzeid,"bolsaBusqueda"=>$bolsaBusqueda,"numReserva"=>$numReserva,"cambio"=>'1'));
		}
    $this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
		$this->salida .= "			      <table class=\"normal_10\" width=\"80%\" align=\"center\">";
    $this->salida .= "				    <tr><td colspan=\"2\" class=\"label_error\" align=\"center\">Usted ha Seleccionado una Hemoclasificacion del Paciente Diferente a la
		Registrada en la Base de Datos, Confirme la Seleccion y de click en Aceptar para Actualizarla o de lo contrario de click en Cancelar para Abortar el Proceso </td></tr>";
    $this->salida .= "			      </table><BR><BR>";
		$this->salida .= "			      <table class=\"normal_10\" width=\"60%\" align=\"center\">";
		$this->salida .= "  <tr><td align=\"center\">";
		$this->salida .=    $this->SetStyle("MensajeError");
		$this->salida .= "  </td><tr>";
    $this->salida.="              <tr class = \"modulo_table_list_title\"><td colspan=\"4\">NUEVA HEMOCLASIFICACION PACIENTE</td></tr>";
		$this->salida.="              <tr class = \"modulo_table_list_title\"><td colspan=\"4\">$tipoId $paciente&nbsp&nbsp&nbsp&nbsp;$nombre</td></tr>";
		$this->salida.="              <tr class = \"modulo_list_claro\">";
		$this->salida.="              <td align=\"left\" class=\"".$this->SetStyle("grupo_sanguineoNuevo")."\">GRUPO SANGUINEO</td>";
		//Cambie $grupoManual por $grupoGel
		(list($grupoDefault,$rhDefault)=explode('/',$grupoGel));
		if(!$_REQUEST['grupo_sanguineoNuevo']){
      $_REQUEST['grupo_sanguineoNuevo']=$grupoDefault;
		}
		$this->salida .= "            <td><select name=\"grupo_sanguineoNuevo\" class=\"select\" $desabilitado>";
		$facts=$this->ConsultaFactor();
		$this->MostrasSelect($facts,'False',$_REQUEST['grupo_sanguineoNuevo']);
		$this->salida .= "            </select></td>";
		$this->salida.="              <td align=\"left\" class=\"".$this->SetStyle("rhNuevo")."\">Rh </td>";
    $this->salida.="              <td align=\"left\" >";
		$this->salida.="              <select size=\"1\" name =\"rhNuevo\" class =\"select\" $desabilitado>";
		if(!$_REQUEST['rhNuevo']){
      $_REQUEST['rhNuevo']=$rhDefault;
		}
		if($_REQUEST['rhNuevo']=='+'){
      $checkeado='selected';
		}elseif($_REQUEST['rhNuevo']=='-'){
      $checkeado1='selected';
		}
		$this->salida.="              <option value = -1>-Seleccione-</option>";
    $this->salida.="              <option value=\"+\" $checkeado> Positivo </option>";
		$this->salida.="              <option value=\"-\" $checkeado1> Negativo </option>";
    $this->salida.="              </select>";
		$this->salida.="              </td>";
    $this->salida.="              </tr>";
		$this->salida.="              <tr class = \"modulo_list_claro\">";
    if(!$_REQUEST['bacteriologoCambio']){
      $_REQUEST['bacteriologoCambio']=$bacteriologoManual;
		}
		$this->salida.="              <td class=\"".$this->SetStyle("bacteriologoCambio")."\">PROFESIONAL</td>";
		$this->salida.="              <td colspan=\"3\"><select name=\"bacteriologoCambio\" class=\"select\">";
		$bacteriologos=$this->TotalBacteriologos();
		$this->BuscarProfesionlesEspecialistas($bacteriologos,'False',$_REQUEST['bacteriologoCambio']);
		$this->salida.="              </select></td>";
    $this->salida.="              </tr>";
		$this->salida.="              <tr class = \"modulo_list_claro\">";
    $this->salida.="              <td align=\"left\" colspan=\"4\"><b>OBSERVACIONES</b><br><textarea style=\"width:100%\" name=\"observacionesII\" class=\"textarea\" rows=\"3\" cols=\"60\"></textarea></td>";
    $this->salida.="              </tr>";
		$this->salida.= "				      <tr><td colspan=\"4\" align=\"center\">";
		$this->salida.= "				      <input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"CANCELAR\">";
		$this->salida.= "				      <input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\">";
		$this->salida.= "				      </td></tr>";
		$this->salida.= "			     </form>";
		$this->salida.= "			     </table>";
		$this->salida.= ThemeCerrarTabla();
		return true;
	}

	function RegistroEntregaExamen($TipoDocumento,$Documento,$grupoSanguineo,$fechaCruce){
    $this->salida .= ThemeAbrirTabla('LISTADO RESULTADOS CRUCES SIN ENTREGAR');
		$this->salida .="<script language='javascript'>";
		$this->salida .= 'function mOvr(src,clrOver){';
		$this->salida .= '  src.style.background = clrOver;';
		$this->salida .= '}';
		$this->salida .= 'function mOut(src,clrIn){';
		$this->salida .= '  src.style.background = clrIn;';
		$this->salida .= '}';
		$this->salida .= '</script>';
    $accion=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','LlamaRegistroEntregaExamen');
		$this->salida .= "        <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
		$this->salida .= "        <table border=\"0\" width=\"50%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "        <tr><td></td></tr>";
		$this->salida .= "        <tr><td><fieldset><legend class=\"field\">FILTRO DE BUSQUEDA</legend>";
    $this->salida .= "        <br><table width=\"90%\" border=\"0\" align=\"center\">";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"TipoDocumento\" class=\"select\">";
		$tipo_id=$this->tipo_id_paciente();
		$this->BuscarIdPaciente($tipo_id,$TipoDocumento);
		$this->salida .= "        </select></td>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td class=\"label\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" value=\"$Documento\" maxlength=\"32\"></td>";
		$this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td class=\"label\">ABO / Rh BOLSA</td><td><select name=\"grupoSanguineo\" class=\"select\">";
		$facts=$this->ConsultaFactorRh();
		$this->GrupoSanguineo($facts,'False',$grupoSanguineo);
		$this->salida .= "        </select></td>";
		$this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .="         <td class=\"".$this->SetStyle("fechaCruce")."\" align=\"left\">FECHA CRUCE</td>";
		$this->salida .= "	  	  <td><input size=\"10\" maxlength=\"10\" type=\"text\" name=\"fechaCruce\" value=\"$fechaCruce\" class=\"input-text\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\">";
		$this->salida .= "	  	  ".ReturnOpenCalendario('formabuscar','fechaCruce','/')."</td>";
		$this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
    $this->salida .= "        <td align=\"center\" colspan=\"2\">";
		$this->salida .= "        <input class=\"input-submit\" type=\"submit\" name=\"filtrar\" value=\"FILTRAR\">";
		$this->salida .= "        <input class=\"input-submit\" type=\"submit\" name=\"Salir\" value=\"MENU\">";
		$this->salida .= "        </td>";
		$this->salida .= "        </tr>";
		$this->salida .= "        </table><br>";
		$this->salida .= "	      </fieldset></td></tr></table><BR>";
		$listado=$this->ListadoCrucesSinEntregar($TipoDocumento,$Documento,$grupoSanguineo,$fechaCruce);
    if($listado){
		$this->salida .= "        <table width=\"90%\" border=\"0\" align=\"center\">";
		$this->salida .= "        <tr class=\"modulo_table_list_title\">";
		$this->salida .= "        <td>No. BOLSA</td>";
		$this->salida .= "        <td>SELLO</td>";
		$this->salida .= "        <td>COMPONENTE</td>";
		$this->salida .= "        <td>AOB / Rh BOLSA</td>";
		$this->salida .= "        <td>PACIENTE</td>";
		$this->salida .= "        <td>AOB / Rh PACIENTE</td>";
		$this->salida .= "        <td>FECHA CRUCE</td>";
		$this->salida .= "        <td>&nbsp;</td>";
		$this->salida .= "        </tr>";
		$y=0;
		$backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
    for($i=0;$i<sizeof($listado);$i++){
		  if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
			$this->salida.="<tr  class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>";
			$this->salida .= "        <td>".$listado[$i]['bolsa_id']."</td>";
			$this->salida .= "        <td>".$listado[$i]['sello_calidad']."</td>";
			$this->salida .= "        <td>".$listado[$i]['componente']."</td>";
			if($listado[$i]['rh_bolsa']=='+'){
			$this->salida .= "        <td>".$listado[$i]['grupo_sanguineo_bolsa']." /&nbsp&nbsp&nbsp;POSITIVO</td>";
			}elseif($listado[$i]['rh_bolsa']=='-'){
      $this->salida .= "        <td>".$listado[$i]['grupo_sanguineo_bolsa']." /&nbsp&nbsp&nbsp;<label class=\"label_error\">NEGATIVO</label></td>";
			}else{
      $this->salida .= "        <td>".$listado[$i]['grupo_sanguineo_bolsa']."</td>";
			}
			$this->salida .= "        <td>".$listado[$i]['tipo_id_paciente']." ".$listado[$i]['paciente_id']." ".$listado[$i]['nombrepac']."</td>";
			if($listado[$i]['rh']=='+'){
			$this->salida .= "        <td>".$listado[$i]['grupo_sanguineo']." /&nbsp&nbsp&nbsp;POSITIVO</td>";
			}elseif($listado[$i]['rh']=='-'){
      $this->salida .= "        <td>".$listado[$i]['grupo_sanguineo']." /&nbsp&nbsp&nbsp;<label class=\"label_error\">NEGATIVO</label></td>";
			}else{
      $this->salida .= "        <td>".$listado[$i]['grupo_sanguineo']."</td>";
			}
			$this->salida .= "        <td>".$listado[$i]['fecha_prueba']."</td>";
			$action1=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','EntregaExamenCruce',array("cruce"=>$listado[$i]['cruze_sanguineo_id'],"bolsa"=>$listado[$i]['ingreso_bolsa_id'],"reserva"=>$listado[$i]['solicitud_reserva_sangre_id'],
			"NumBolsa"=>$listado[$i]['bolsa_id'],"sello"=>$listado[$i]['sello_calidad'],"componente"=>$listado[$i]['componente'],"grupoBolsa"=>$listado[$i]['grupo_sanguineo_bolsa'],"rhbolsa"=>$listado[$i]['rh_bolsa'],
			"tipoId"=>$listado[$i]['tipo_id_paciente'],"paciente_id"=>$listado[$i]['paciente_id'],"nombrePac"=>$listado[$i]['nombrepac'],"rh"=>$listado[$i]['rh'],"grupo"=>$listado[$i]['grupo_sanguineo'],
			"TipoDocumentoBus"=>$TipoDocumento,"DocumentoBus"=>$Documento,"grupoSanguineoBus"=>$grupoSanguineo,"fechaCruceBus"=>$fechaCruce));
			$this->salida .= "        <td><a href=\"$action1\" class=\"link\"><b> ENTREGA</b></a></td>";
			$this->salida .= "        </tr>";
			$y++;
		}
    $this->salida .= "			      </table>";
		}else{
    $this->salida .= "        <table width=\"80%\" border=\"0\" align=\"center\">";
    $this->salida .= "        <tr><td class=\"label_error\" align=\"center\">NO SE ENCONTRARON REGISTROS</td></tr>";
    $this->salida .= "			   </table>";
		}
    $this->salida .= "			      </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function FormaEntregaExamen($cruce,$bolsa,$reserva,$NumBolsa,$sello,$componente,$grupoBolsa,$rhbolsa,
		$tipoId,$paciente_id,$nombrePac,$rh,$grupo,$origen,$TipoDocumentoBus,$DocumentoBus,$grupoSanguineoBus,$fechaCruceBus){

    $this->salida .= ThemeAbrirTabla('RESULTADO DEL CRUCE DE COMPATIBILIDAD');
		$accion=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','InsertarDatosEntrega',array("cruce"=>$cruce,"bolsa"=>$bolsa,"reserva"=>$reserva,
		"NumBolsa"=>$NumBolsa,"sello"=>$sello,"componente"=>$componente,"grupoBolsa"=>$grupoBolsa,"rhbolsa"=>$rhbolsa,
		"tipoId"=>$tipoId,"paciente_id"=>$paciente_id,"nombrePac"=>$nombrePac,"rh"=>$rh,"grupo"=>$grupo,"origen"=>$origen,
		"TipoDocumentoBus"=>$TipoDocumentoBus,"DocumentoBus"=>$DocumentoBus,"grupoSanguineoBus"=>$grupoSanguineoBus,"fechaCruceBus"=>$fechaCruceBus));
		$this->salida .= "  <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table width=\"65%\" border=\"0\" class=\"normal_10\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">DATOS DEL CRUCE</legend>";
		$this->salida .= "  <BR><table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
		$this->salida .= "  <tr><td width=\"25%\"></tr></td>";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
    $this->salida .= "  <td class=\"label\">BOLSA</td>";
		$this->salida .= "  <td>$NumBolsa</td>";
		$this->salida .= "  <td class=\"label\">SELLO</td>";
		$this->salida .= "  <td>$sello</td>";
    $this->salida .= "  </tr>";
    $this->salida .= "  <tr class=\"modulo_list_claro\">";
    $this->salida .= "  <td class=\"label\">COMPONENTE</td>";
		$this->salida .= "  <td>$componente</td>";
		$this->salida .= "  <td class=\"label\">ABO / Rh BOLSA</td>";
		if($rhbolsa=='+'){
		$this->salida .= "  <td>$grupoBolsa / POSITIVO</td>";
		}elseif($rhbolsa=='-'){
    $this->salida .= "  <td>$grupoBolsa /&nbsp&nbsp&nbsp;<label class=\"label_error\">NEGATIVO</label></td>";
		}else{
    $this->salida .= "  <td>$grupoBolsa</td>";
		}
    $this->salida .= "  </tr>";
    $this->salida .= "  <tr class=\"modulo_list_claro\">";
    $this->salida .= "  <td class=\"label\">PACIENTE</td>";
    $this->salida .= "  <td colspan=\"3\">$tipoId $paciente_id $nombrePac</td>";
    $this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
    $this->salida .= "  <td class=\"label\">AOB / Rh PACIENTE</td>";
		if($rh=='+'){
		$this->salida .= "  <td colspan=\"3\">$grupo / POSITIVO</td>";
		}elseif($rh=='-'){
    $this->salida .= "  <td colspan=\"3\">$grupo /&nbsp&nbsp&nbsp;<label class=\"label_error\">NEGATIVO</label></td>";
		}else{
    $this->salida .= "  <td colspan=\"3\">$grupo</td>";
		}
    $this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  </fieldset></td></tr></table><BR>";
    $this->salida .= "  <table width=\"60%\" border=\"0\" align=\"center\">";
		$this->salida .= "  <tr><td align=\"center\">";
		$this->salida .=    $this->SetStyle("MensajeError");
		$this->salida .= "  </td><tr>";
		$this->salida .= "  <tr class=\"modulo_table_list_title\"><td colspan=\"2\">DATOS DE LA ENTREGA DEL CRUCE</td></tr>";
    $this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td width=\"25%\"><label class=\"".$this->SetStyle("bacteriologoEntrega")."\">PROFESIONAL   QUE ENTREGA</label></td>";
		$this->salida .= "  <td><select name=\"bacteriologoEntrega\" class=\"select\" $desabilitar>";
		$bacteriologos=$this->TotalBacteriologos();
		$this->BuscarProfesionlesEspecialistas($bacteriologos,'False',$_REQUEST['bacteriologoEntrega']);
		$this->salida .= "  </select></td>";
    $this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td width=\"40%\" class=\"".$this->SetStyle("quienRecibe")."\">PERSONA QUE RECIBE</td>";
		$this->salida .= "  <td><select name=\"quienRecibe\" class=\"select\" $desabilitar>";
		$auxiliares=$this->TotalAuxiliares();
		$this->BuscarProfesionlesEspecialistas($auxiliares,'False',$_REQUEST['quienRecibe']);
		$this->salida .= "  </select></td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td class=\"".$this->SetStyle("fechaRecibe")."\" align=\"left\">FECHA RECIBE</td>";
		if(empty($_REQUEST['fechaRecibe'])){
      $_REQUEST['fechaRecibe']=date('d-m-Y');
		}
		$this->salida .= "  <td align=\"left\"><input type=\"text\" readonly class=\"input-text\" size=\"10\" maxlength=\"10\" value=\"".$_REQUEST['fechaRecibe']."\" name=\"fechaRecibe\" onBlur=\"IsValidDate(this,'YYYY-MM-DD')\" onKeyUp=\"setDate(this,'YYYY-MM-DD','es')\">";
		$this->salida .= "        ".ReturnOpenCalendario('formabuscar','fechaRecibe','-')."";
		$this->salida .= "  </td>" ;
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td class=".$this->SetStyle("horaRecibe")." align=\"left\">HORA RECIBE</td>";
	  $this->salida .= "  <td><select size=\"1\" name=\"horaRecibe\" class=\"select\">";
		$this->salida .= "  <option value = -1>Seleccione Hora </option>";
	  for ($j=0;$j<=23; $j++){
      if(($j >= 0) AND ($j<= 9)){
				$hora = '0'.$j;
				if($_REQUEST['horaRecibe']==$hora){
				  $this->salida.="  <option selected value = \"$hora\">0$j</option>";
				}else{
				  $this->salida.="  <option value = \"$hora\">0$j</option>";
				}
			}else{
			  if($_REQUEST['horaRecibe']==$j){
					$this->salida.="  <option selected value = $j>$j</option>";
				}else{
					$this->salida.="  <option value = $j>$j</option>";
				}
			}
    }
    $this->salida .= "  </select>&nbsp;";
		$this->salida .= "  <select size=\"1\"  name=\"minutosRecibe\" class=\"select\">";
	  $this->salida .= "  <option value = -1>Seleccione Minutos</option>";
		for ($j=0;$j<=59; $j++){
			if(($j >= 0) AND ($j<= 9)){
				$min = '0'.$j;
				if($_REQUEST['minutosRecibe']==$min){
					$this->salida .= "  <option selected value = \"$min\" >0$j</option>";
				}else{
					$this->salida .= "  <option value=\"$min\">0$j</option>";
				}
			}else{
			  if($_REQUEST['minutosRecibe']==$j){
					$this->salida .= "  <option selected value=$j>$j</option>";
				}else{
					$this->salida .= "  <option value=$j>$j</option>";
				}
			}
    }
    $this->salida .= "  </select>";
    $this->salida .= "  </td>";
    $this->salida .= "  </tr>";
    $this->salida .= "  </table>";
		$this->salida .= "  <br><table width=\"99%\" border=\"0\" align=\"center\">";
    $this->salida .= "  <tr><td align=\"center\">";
		$this->salida .= "  <input type=\"submit\" class=\"input-submit\" name=\"Guardar\" value=\"GUARDAR\">";
		$this->salida .= "  <input type=\"submit\" class=\"input-submit\" name=\"Salir\" value=\"REGRESAR\">";
		$this->salida .= "  </td></tr>";
    $this->salida .= "  </table>";
    $this->salida .= "  </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}


//pg_dump -u -s SIIS2 > /home/lorena/Desktop/alex.sql
}//fin clase user
?>

