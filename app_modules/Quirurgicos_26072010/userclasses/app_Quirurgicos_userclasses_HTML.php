<?php

/**
 * $Id: app_Quirurgicos_userclasses_HTML.php,v 1.39 2007/07/12 18:52:24 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * MODULO para el Manejo de Programacion de cirugias del sistema
 */

/**
*Contiene los metodos visuales para realizar la programacion de cirugias
*/
IncludeClass("ClaseHTML");
class app_Quirurgicos_userclasses_HTML extends app_Quirurgicos_user
{
	/**
	*Constructor de la clase app_ProgramacionQX_user_HTML
	*El constructor de la clase app_ProgramacionQX_user_HTML se encarga de llamar
	*a la clase app_ProgramacionQX_user quien se encarga de el tratamiento
	*de la base de datos.
	*/

  function app_Quirurgicos_user_HTML()
	{
		$this->salida='';
		$this->app_Quirurgicos_user();
		return true;
	}
	/**
	* Function que muestra al usuario la diferentes departamentos, la empresa y el centro de utilidad

	* al que pertenecen y en las que el usuario tiene permiso de trabajar
	* @return boolean
	*/
	function FrmLogueoCirugias(){

    $Empresas=$this->LogueoCirugias();
		if(sizeof($Empresas)>0){
			$url[0]='app';
			$url[1]='Quirurgicos';
			$url[2]='user';
			$url[3]='menu1A';
			$url[4]='datos_query';
			$this->salida .= gui_theme_menu_acceso("SELECCION DEL DEPARTAMENTOS",$Empresas[0],$Empresas[1],$url);
		}else{
      $mensaje = "EL USUARIO NO TIENE PERMISOS PARA ACCESAR A UN INVENTARIO.";
			$titulo = "INVENTARIO GENERAL";
			$boton = "";//REGRESAR
			$accion="";
			$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
			return true;
		}
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
* Funcion que muestra las distintas opciones del menu para el usuario
* @return boolean
*/
	function MenuQuirurjicos(){
          $this->salida .= ThemeAbrirTabla('MENU CIRUGIAS');
          $this->Encabezado();
          $accion=ModuloGetURL('app','Quirurgicos','user','FrmLogueoCirugias');
          $this->salida .= "    <form name=\"forma\" action=\"$accion\" method=\"post\">";
          $this->salida .= "			<table width=\"35%\" align=\"center\" class=\"modulo_table_list\">";
          $actionProgram=ModuloGetURL('app','Quirurgicos','user','LlamaProgramacionQxs');
          $actionOrdenes=ModuloGetURL('app','Quirurgicos','user','LlamaCapturaOrdenesServicio');
          $ConsultaProgram1=ModuloGetURL('app','Quirurgicos','user','ConsultadeProgramaciones',array("consulta"=>'1'));
          $ConsultaProgram=ModuloGetURL('app','Quirurgicos','user','ConsultadeProgramaciones');
          $actionReporte=ModuloGetURL('app','Quirurgicos','user','FiltroCirugiaReporte');
		
		$actionReserva=ModuloGetURL('app','Quirurgicos','user','RealizarReservaQuirofano');
		$actionTurnos=ModuloGetURL('app','Quirurgicos','user','TurnosInstrumentadores');
		$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" align=\"center\">MENU</td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$actionProgram\" class=\"link\"><b>PROGRAMACIONES CIRUGIAS</b></a></td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$actionOrdenes\" class=\"link\"><b>ATENCION ORDENES DE SERVICIO</b></a></td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$ConsultaProgram1\" class=\"link\"><b>CONSULTA PROGRAMACIONES CIRUGIAS</b></a></td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$ConsultaProgram\" class=\"link\"><b>MODIFICACION PROGRAMACIONES CIRUGIAS</b></a></td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$actionReserva\" class=\"link\"><b>RESERVA DEL QUIROFANO</b></a></td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$actionReporte\" class=\"link\"><b>REPORTE CIRUGIA</b></a></td></tr>";
		$this->salida .= "			     </table><BR>";
		$this->salida .= "     <table width=\"40%\" border=\"0\" align=\"center\"class=\"normal_10\" >";
          $this->salida .= "     <tr><td align=\"center\"><input type=\"submit\" class=\"input-submit\" name=\"salir\" value=\"VOLVER\"></td></tr>";
          $this->salida .= "     </table>";
		$this->salida .= "	</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
/**
* Funcion que muestra el encabezado de la forma
* @return boolean
*/
	function Encabezado(){
		$this->salida .= "    <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr><td class=\"modulo_table_title\" align=\"center\"><b>EMPRESA</b></td>";
		$this->salida .= "      <td class=\"modulo_table_title\" align=\"center\"><b>CENTRO DE UTILIDAD</b></td>";
		$this->salida .= "      <td class=\"modulo_table_title\" align=\"center\"><b>DEPARTAMENTO</b></td></tr>";
		$this->salida .= "      <tr><td class=\"modulo_list_claro\" align=\"center\"><b>".$_SESSION['LocalCirugias']['NombreEmp']."</b></td>";
		$this->salida .= "      <td class=\"modulo_list_claro\" align=\"center\"><b>".$_SESSION['LocalCirugias']['NombreCU']."</b></td>";
		$this->salida .= "      <td class=\"modulo_list_claro\" align=\"center\"><b>".$_SESSION['LocalCirugias']['NombreDpto']."</b></td></tr>";
		$this->salida .= "		 </table><BR>";
		return true;
	}
/**
* Funcion que visulaiza la forma donde se muestran los datos introducidos en una programacion Quirurjica
* @return boolean
*/
	function FormaProgramacionesQuirurgicas($consulta,$mayorFecha){
	  $ProgramacionId=$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO'];
		if(empty($ProgramacionId)){
      $desabilitado='disabled';
		}
		$RUTA = $_ROOT ."cache/sticker$ProgramacionId.pdf";
		$mostrar ="\n<script language='javascript'>\n";
		$mostrar.="var rem=\"\";\n";
		$mostrar.="function abreVentana(){\n";
		$mostrar.="    var nombre=\"\"\n";
		$mostrar.="    var url2=\"\"\n";
		$mostrar.="    var str=\"\"\n";
		$mostrar.="    var ALTO=screen.height\n";
		$mostrar.="    var ANCHO=screen.width\n";
		$mostrar.="    var nombre=\"REPORTE\";\n";
		$mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
		$mostrar.="    var url2 ='$RUTA';\n";
		$mostrar.="    rem = window.open(url2, nombre, str)};\n";
		$mostrar.="</script>\n";
		$this->salida = "$mostrar";    
		$this->salida .= ThemeAbrirTabla('PROGRAMACION CIRUGIA No. '.' '.$ProgramacionId);     
		$accion=ModuloGetURL('app','Quirurgicos','user','SeleccionProgramacionQX');    
		$this->salida .= "    <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->Encabezado();
		$this->salida .= "<table border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "<tr><td class=\"modulo_list_oscuro\">";
    $this->salida .= "      <br><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
		if($ProgramacionId){
			$datosPaciente=$this->SacaDatosPacienteProgramQX($ProgramacionId);
			$TipoId=$datosPaciente['tipo_id_paciente'];
      $PacienteId=$datosPaciente['paciente_id'];
			$nombreCir=$this->NombreProfesional($datosPaciente['cirujano_id'],$datosPaciente['tipo_id_cirujano']);
			$Nombres=$this->BuscarNombresPaciente($datosPaciente['tipo_id_paciente'],$datosPaciente['paciente_id']);
			$Apellidos=$this->BuscarApellidosPaciente($datosPaciente['tipo_id_paciente'],$datosPaciente['paciente_id']);
			$FechaNacimiento=$this->Edad($datosPaciente['tipo_id_paciente'],$datosPaciente['paciente_id']);
			$EdadArr=CalcularEdad($FechaNacimiento,$FechaFin);
			$NombreResponsable=$this->Responsable($datosPaciente['plan_id']);
			$NombrePlan=$this->PlanNombre($datosPaciente['plan_id']);
			$diagnostico=$datosPaciente['diagnostico_nombre'];
		}
		$this->salida .= "		    <tr>";
		$this->salida .= "		    <td class=\"label\"></td>";
		$this->salida .= "		    </tr>";
		if(empty($consulta) && $ProgramacionId){
		$this->salida .= "		    <tr class=\"modulo_table_list_title\">";
		$this->salida .= "		    <td align=\"right\" colspan=\"4\">CANCELAR PROGRAMACION&nbsp&nbsp&nbsp;";
		$actionCancel=ModuloGetURL('app','Quirurgicos','user','ProcesoCancelarLaProgramacion',array('mayorFecha'=>$mayorFecha));
		$this->salida .= "		    <a href=\"$actionCancel\"><img border=\"0\" src=\"".GetThemePath()."/images/checksi.png\"><a></td>";
		$this->salida .= "		    </tr>";
		}
    $this->salida .= "		    <tr class=\"modulo_table_list_title\">";
		$this->salida .= "		    <td colspan=\"4\">DATOS PACIENTE</td>";
		$this->salida .= "		    </tr>";
		$this->salida .= "		    <tr class=\"modulo_list_claro\">";
		$this->salida .= "		    <td width=\"15%\" class=\"label\">CIRUJANO PRINCIPAL</td>";
		$this->salida .= "		    <td colspan=\"3\">".$nombreCir['nombre']."</td>";
		$this->salida .= "		    </tr>";
		$this->salida .= "		    <tr class=\"modulo_list_claro\">";
		$this->salida .= "		    <td  width=\"30%\" class=\"label\">PACIENTE</td>";
		$this->salida .= "		    <td colspan=\"3\">$Nombres $Apellidos</td>";
    $this->salida .= "		    </tr>";
		$this->salida .= "		    <tr class=\"modulo_list_claro\">";
		$this->salida .= "		    <td class=\"label\">TIPO ID.</td>";
		$this->salida .= "		    <td width=\"30%\">$TipoId</td>";
		$this->salida .= "		    <td width=\"20%\" class=\"label\">No. IDENTIFICACION</td>";
		$this->salida .= "		    <td>$PacienteId</td>";
		$this->salida .= "		    </tr>";
		$this->salida .= "		    <tr class=\"modulo_list_claro\">";
		$this->salida .= "		    <td class=\"label\">RESPONSABLE PLAN</td>";
		$this->salida .= "		    <td>$NombreResponsable</td>";
		$this->salida .= "		    <td class=\"label\">PLAN</td>";
		$this->salida .= "		    <td>$NombrePlan</td>";
		$this->salida .= "		    </tr>";
		$this->salida .= "		    <tr class=\"modulo_list_claro\">";
		$this->salida .= "		    <td class=\"label\">EDAD</td>";
		$this->salida .= "		    <td colspan=\"3\">".$EdadArr['edad_aprox']."</td>";
		$this->salida .= "		    </tr>";
		$this->salida .= "		    <tr class=\"modulo_list_claro\">";
		$this->salida .= "		    <td class=\"label\">DIAGNOSTICO PRINCIPAL</td>";
		$this->salida .= "		    <td colspan=\"3\">$diagnostico</td>";
		$this->salida .= "		    </tr>";
		$this->salida .= "		    <tr><td></td></tr>";
		$this->salida .= "		</table><br>";
		$this->salida .= "</td>";
    if(empty($consulta) && $mayorFecha==1){
		$this->salida .= "<td valign=\"bottom\" class=\"modulo_list_oscuro\">";
    $this->salida .= "       <input type=\"submit\" name=\"seleccionPac\" value=\"SELECCION\" class=\"input-submit\">";
    $this->salida .= "</td>";
		}
		$this->salida .= "</tr>";
		$this->salida .= "<tr><td class=\"modulo_list_claro\">";
		if($ProgramacionId){
			$datos=$this->obtenerDatosProgramacionQX($ProgramacionId);
			if($datos){
			$Sala=$datos[0]['quirofano_id'];
			$sala=$this->DescripcionQuirofano($Sala);
			$decripsala=$sala['abreviatura'].' / '.$sala['descripcion'];
			$FechaProgramIni=$datos[0]['hora_inicio'];
			$FechaProgramFin=$datos[0]['hora_fin'];
			$Fecha=$this->FechaStamp($FechaProgramIni);
			$infoCadena = explode ('/', $Fecha);
			$diaIni=$infoCadena[0];
			$mesIni=$infoCadena[1];
			$anoIni=$infoCadena[2];
			$HoraDef=$this->HoraStamp($FechaProgramIni);
			$infoCadena = explode (':',$HoraDef);
			$HoraIni=$infoCadena[0];
			$MinutosIni=$infoCadena[1];
			$Fecha=$this->FechaStamp($FechaProgramFin);
			$infoCadena = explode ('/', $Fecha);
			$diaFin=$infoCadena[0];
			$mesFin=$infoCadena[1];
			$anoFin=$infoCadena[2];
			$HoraDef=$this->HoraStamp($FechaProgramFin);
			$infoCadena = explode (':',$HoraDef);
			$HoraFin=$infoCadena[0];
			$MinutosFin=$infoCadena[1];
      $DuracionMin=(mktime($HoraFin,$MinutosFin+1,0,$mesFin,$diaFin,$anoFin)-mktime($HoraIni,$MinutosIni,0,$mesIni,$diaIni,$anoIni))/60;
			$HorasDura=(int)($DuracionMin/60);
			$HorasDura=str_pad($HorasDura,2,0,STR_PAD_LEFT);
      $MinutosDura=($DuracionMin%60);
			$MinutosDura=str_pad($MinutosDura,2,0,STR_PAD_LEFT);
			$Duracion=$HorasDura.':'.$MinutosDura;
			$diaIni=str_pad($diaIni,2,0, STR_PAD_LEFT);
			$mesIni=str_pad($mesIni,2,0, STR_PAD_LEFT);
			$anoIni=str_pad($anoIni,2,0, STR_PAD_LEFT);
			$FechaProgramacion=$diaIni.'/'.$mesIni.'/'.$anoIni;
			$datosEquipos=$this->SeleccionEquiposProgramacion($datos[0]['qx_quirofano_programacion_id']);
			}
		}
		$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\" cellpadding=\"3\">";
		$this->salida .= "		    <tr>";
		$this->salida .= "		    <td class=\"label\">&nbsp;</td>";
		$this->salida .= "		    </tr>";
    $this->salida .= "		    <tr class=\"modulo_table_list_title\">";
		$this->salida .= "		    <td colspan=\"6\">DATOS QUIROFANO</td>";
		$this->salida .= "		    </tr>";
		$this->salida .= "		    <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "		    <td width=\"25%\" class=\"label\">QUIROFANO</td>";
		$this->salida .= "		    <td colspan=\"5\">$decripsala</td>";
    $this->salida .= "		    </tr>";
		$this->salida .= "		    <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "		    <td width=\"25%\" nowrap class=\"label\">FECHA PROGRAMACION</td>";
		$this->salida .= "		    <td width=\"25%\" nowrap>$FechaProgramacion</td>";
		$this->salida .= "		    <td width=\"10%\" nowrap class=\"label\">HORA INICIO</td>";
    $this->salida .= "		    <td>$HoraIni:$MinutosIni <b>(H:mm)</b></td>";
		$this->salida .= "		    <td width=\"10%\" nowrap class=\"label\">DURACION</td>";
		$this->salida .= "		    <td>$Duracion <b>(H:mm)</b></td>";
		$this->salida .= "		    </tr>";
		$this->salida .= "		    <tr colspan=\"6\" class=\"modulo_list_oscuro\">";
    $this->salida .= "		    <td width=\"25%\" class=\"label\">EQUIPOS UTILIZADOS</td>";
		$this->salida .= "		    <td colspan=\"5\">";
		if($datosEquipos){
		$this->salida .= "       <table border=\"0\" width=\"95%\" align=\"center\">";
		for($i=0;$i<sizeof($datosEquipos);$i++){
      $this->salida .= "		    <tr class=\"modulo_list_claro\"><td>".$datosEquipos[$i]['descripcion']."</td></tr>";
		}
    $this->salida .= "		    </table>";
		}else{
    $this->salida .= "       <table border=\"0\" width=\"95%\" align=\"center\">";
    $this->salida .= "		    <tr class=\"modulo_list_claro\"><td>&nbsp;</td></tr>";
    $this->salida .= "		    </table>";
		}
		$this->salida .= "		    </td>";
		$this->salida .= "		    </tr>";
    $this->salida .= "		    <tr><td></td></tr>";
		$this->salida .= "		</table><BR>";
		if(empty($consulta) && $mayorFecha==1){
    $this->salida .= "</td>";
		$this->salida .= "<td valign=\"bottom\" class=\"modulo_list_claro\">";
    $this->salida .= "       <input type=\"submit\" name=\"reservar\" value=\"RESERVAR\" class=\"input-submit\" $desabilitado>";
    $this->salida .= "</td>";
		}
		$this->salida .= "</tr>";
		$this->salida .= "<tr><td class=\"modulo_list_oscuro\">";
		$this->salida .= "      <br><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
		if($ProgramacionId){
			$datos=$this->obtenerDatosAnestesiologoQX($ProgramacionId);
			$datosAneste=$this->obtenerDatosCitaAnestesiologia($ProgramacionId);
      $nombreAn=$this->NombreProfesional($datos['tercero_id'],$datos['tipo_id_tercero']);
			$nombreIn=$this->NombreProfesional($datos['instrumentista_id'],$datos['tipo_id_instrumentista']);
			$nombreCi=$this->NombreProfesional($datos['circulante_id'],$datos['tipo_id_circulante']);
      $nombreAy=$this->NombreProfesional($datos['ayudante_id'],$datos['tipo_id_ayudante']);
		}
		$this->salida .= "		    <tr>";
		$this->salida .= "		    <td class=\"label\"></td>";
		$this->salida .= "		    </tr>";
		$this->salida .= "		    <tr class=\"modulo_table_list_title\">";
		$this->salida .= "		    <td colspan=\"4\">INSTRUMENTADOR(A) Y CIRCULANTE</td>";
		$this->salida .= "		    </tr>";
		$this->salida .= "		    <tr class=\"modulo_list_claro\">";
		$this->salida .= "		    <td width=\"17%\" class=\"label\">INSTRUMENTADOR(A)</td>";
		$this->salida .= "		    <td>".$nombreIn['nombre']."</td>";
		$this->salida .= "		    <td width=\"17%\" class=\"label\">CIRCULANTE</td>";
		$this->salida .= "		    <td>".$nombreCi['nombre']."</td>";
		$this->salida .= "		    </tr>";
		$this->salida .= "		    <tr>";
		$this->salida .= "		    <td class=\"label\">&nbsp;</td>";
		$this->salida .= "		    </tr>";
    $this->salida .= "		    <tr class=\"modulo_table_list_title\">";
		$this->salida .= "		    <td colspan=\"4\">ANESTESIOLOGO Y AYUDANTE</td>";
		$this->salida .= "		    </tr>";
		$this->salida .= "		    <tr class=\"modulo_list_claro\">";
		$this->salida .= "		    <td width=\"17%\" class=\"label\">ANESTESIOLOGO</td>";
		$this->salida .= "		    <td>".$nombreAn['nombre']."</td>";
    $this->salida .= "		    <td width=\"17%\" class=\"label\">AYUDANTE</td>";
		$this->salida .= "		    <td>".$nombreAy['nombre']."</td>";
		$this->salida .= "		    </tr>";
		$this->salida .= "		    <tr>";
		$this->salida .= "		    <td colspan=\"2\">&nbsp;</td>";
		$this->salida .= "		    </tr>";
    $this->salida .= "		    <tr class=\"modulo_table_list_title\">";
		$this->salida .= "		    <td colspan=\"4\">CITA PREANESTESICA</td>";
		$this->salida .= "		    </tr>";
    $this->salida .= "		    <tr class=\"modulo_list_claro\">";
		$this->salida .= "		    <td width=\"15%\" class=\"label\">FECHA Y HORA CITA</td>";
		$this->salida .= "		    <td colspan=\"3\">".$datosAneste['fecha_turno']."&nbsp&nbsp&nbsp&nbsp;".$datosAneste['hora']."</td>";
		$this->salida .= "		    </tr>";
		$this->salida .= "		    <tr class=\"modulo_list_claro\">";
		$this->salida .= "		    <td width=\"15%\" class=\"label\">PROFESIONAL ASIGNADO</td>";
		$nombrePr=$this->NombreProfesional($datosAneste['profesional_id'],$datosAneste['tipo_id_profesional']);
		$this->salida .= "		    <td colspan=\"3\">".$nombrePr['nombre']."</td>";
		$this->salida .= "		    </tr>";
		$this->salida .= "		    <tr class=\"modulo_list_claro\">";
		$this->salida .= "		    <td width=\"15%\" class=\"label\">CONSULTORIO</td>";
		$this->salida .= "		    <td colspan=\"3\">".$datosAneste['consultorio_id']."</td>";
		$this->salida .= "		    </tr>";
		$this->salida .= "		    <tr><td></td></tr>";
		$this->salida .= "		    </tr>";
		$this->salida .= "		</table><br>";
    $this->salida .= "</td>";
		if(empty($consulta) && $mayorFecha==1){
		$this->salida .= "<td valign=\"bottom\" class=\"modulo_list_oscuro\">";
    $this->salida .= "       <input type=\"submit\" name=\"Selprofesionales\" value=\"SELECCION\" class=\"input-submit\" $desabilitado>";
    $this->salida .= "</td>";
		}
		$this->salida .= "</tr>";
		$this->salida .= "<tr><td class=\"modulo_list_claro\">";
		if($ProgramacionId){
		  $procedimientos=$this->BusquedaProcedimientosProgram($ProgramacionId);
			//$datosQX=$this->DatosProgramacionQX($ProgramacionId);
		}
		$this->salida .= "    <br><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
		/*$this->salida .= "		 <tr class=\"modulo_table_list_title\">";
    $this->salida .= "		 <td>DATOS CIRUGIA</td>";
    $this->salida .= "		 </tr>";
		$this->salida .= "		 <tr class=\"modulo_list_oscuro\">";
    $this->salida .= "		 <td>";
		$this->salida .= "        <BR><table border=\"0\" width=\"92%\" align=\"center\" class=\"normal_10\">";
    $this->salida .= "		     <tr class=\"modulo_list_claro\">";
    $this->salida .= "		     <td width=\"20%\" class=\"label\">VIA ACCESO</td>";
    $this->salida .= "		      <td>".$datosQX['viacceso']."</td>";
    $this->salida .= "		     </tr>";
		$this->salida .= "		      <tr class=\"modulo_list_claro\">";
    $this->salida .= "		      <td width=\"20%\" class=\"label\">TIPO CIRUGIA</td>";
		$this->salida .= "		      <td>".$datosQX['tipocirugia']."</td>";
    $this->salida .= "		      </tr>";
		$this->salida .= "		      <tr class=\"modulo_list_claro\">";
    $this->salida .= "		      <td width=\"20%\" class=\"label\">AMBITO CIRUGIA</td>";
		$this->salida .= "		      <td>".$datosQX['ambito']."</td>";
    $this->salida .= "		      </tr>";
    $this->salida .= "		      <tr class=\"modulo_list_claro\">";
    $this->salida .= "		      <td width=\"20%\" class=\"label\">FINALIDAD CIRUGIA</td>";
		$this->salida .= "		      <td>".$datosQX['finalidad']."</td>";
    $this->salida .= "		      </tr>";
    $this->salida .= "         </table><BR>";
		$this->salida .= "		  </td>";
    $this->salida .= "		 </tr>";*/
		$this->salida .= "		 <tr class=\"modulo_table_list_title\">";
    $this->salida .= "		 <td>PROCEDIMIENTOS</td>";
    $this->salida .= "		 </tr>";
		for($i=0;$i<sizeof($procedimientos);$i++){
    $this->salida .= "		 <tr class=\"modulo_list_oscuro\">";
    $this->salida .= "		 <td>";
		$this->salida .= "        <BR><table border=\"0\" width=\"92%\" align=\"center\" class=\"normal_10\">";
    $this->salida .= "		     <tr class=\"modulo_list_claro\">";
    $this->salida .= "		     <td width=\"15%\" class=\"label\">CODIGO</td>";
    $procedimientoDes=$this->DescripcionProcedimiento($procedimientos[$i]['procedimiento_qx']);
    $this->salida .= "		     <td colspan=\"3\">".$procedimientos[$i]['procedimiento_qx']."&nbsp&nbsp&nbsp;".$procedimientoDes['descripcion']."</td>";
    $this->salida .= "		     </tr>";
    
    $this->salida .= "         <tr class=\"modulo_list_claro\"><td colspan=\"4\">";
    $procedimientosOpc=$this->BuscarProcedimientosInsertados($ProgramacionId,$procedimientos[$i]['procedimiento_qx']);
    if($procedimientosOpc){
      $this->salida.="<table border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10\">";
      $this->salida.="<tr class=\"modulo_table_list_title\">";
      $this->salida.="<td width=\"10%\">CODIGO</td>";
      $this->salida.="<td>PROCEDIMIENTO</td>";      
      $this->salida.="</tr>";        
      for($m=0;$m<sizeof($procedimientosOpc);$m++){
        $this->salida.="<tr class=\"modulo_list_oscuro\">";
        $this->salida.="<td width=\"20%\">".$procedimientosOpc[$m]['procedimiento_opcion']."</td>";
        $this->salida.="<td>".$procedimientosOpc[$m]['descripcion']."</td>";        
        $this->salida.="</tr>";
      }        
      $this->salida.="</table>";
    }
    $this->salida .= "         </td></tr>";        
    
		$this->salida .= "		      <tr class=\"modulo_list_claro\">";
    $this->salida .= "		      <td width=\"15%\" class=\"label\">PLAN</td>";
		$NombreResponsable=$this->Responsable($procedimientos[$i]['plan_id']);
		$NombrePlan=$this->PlanNombre($procedimientos[$i]['plan_id']);
		$this->salida .= "		      <td>$NombreResponsable  $NombrePlan</td>";
		$this->salida .= "		      <td width=\"15%\" class=\"label\">No. ORDEN</td>";
		$this->salida .= "		      <td>".$procedimientos[$i]['numero_orden_id']."</td>";
    $this->salida .= "		      </tr>";
		$this->salida .= "		      <tr class=\"modulo_list_claro\">";
		$nombreAn=$this->NombreProfesional($procedimientos[$i]['cirujano_id'],$procedimientos[$i]['tipo_id_cirujano']);
    $this->salida .= "		      <td width=\"15%\" class=\"label\">CIRUJANO</td>";
		$this->salida .= "		      <td colspan=\"3\">".$nombreAn['nombre']."</td>";
    $this->salida .= "		      </tr>";
		/*if($procedimientos[$i]['pediatra_id'] && $procedimientos[$i]['tipo_id_pediatra']){
			$this->salida .= "		      <tr class=\"modulo_list_claro\">";
			$nombrePe=$this->NombreProfesional($procedimientos[$i]['pediatra_id'],$procedimientos[$i]['tipo_id_pediatra']);
			$this->salida .= "		      <td width=\"15%\" class=\"label\">PEDIATRA</td>";
			$this->salida .= "		      <td colspan=\"3\">".$nombrePe['nombre']."</td>";
			$this->salida .= "		      </tr>";
		}*/
		if($procedimientos[$i]['via_procedimiento_bilateral']){
			$this->salida .= "		      <tr class=\"modulo_list_claro\">";
			$this->salida .= "		      <td width=\"15%\" class=\"label\">VIA ACCESO</td>";
			$this->salida .= "		      <td colspan=\"3\">".$procedimientos[$i]['nomvia']."</td>";
			$this->salida .= "		      </tr>";
		}
    if($procedimientos[$i]['observaciones']){
			$this->salida .= "		      <tr class=\"modulo_list_claro\">";
			$this->salida .= "		      <td width=\"15%\" class=\"label\">OBSERVACIONES</td>";
			$this->salida .= "		      <td colspan=\"3\">".$procedimientos[$i]['observaciones']."</td>";
			$this->salida .= "		      </tr>";
		}
		if($procedimientos[$i]['numero_orden_id']){
		  $datosOrdenes=$this->DatosOrdenesCirugia($procedimientos[$i]['numero_orden_id']);
      $this->salida .= "		    <tr><td width=\"100%\" colspan=\"4\">";
      $this->salida .= "        <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= "		      <tr class=\"modulo_table_title\"><td colspan=\"4\" align=\"center\">DATOS DE LA SOLICITUD</td></tr>";
      $this->salida .= "		      <tr class=\"modulo_list_claro\">";
			$this->salida .= "		      <td width=\"20%\" class=\"label\">TIPO CIRUGIA</td>";
			$this->salida .= "		      <td>".$datosOrdenes['tipocirugia']."</td>";
			$this->salida .= "		      <td width=\"20%\" class=\"label\">AMBITO CIRUGIA</td>";
			$this->salida .= "		      <td>".$datosOrdenes['ambitocirugia']."</td>";
			$this->salida .= "		      </tr>";
			$this->salida .= "		      <tr class=\"modulo_list_claro\">";
			$this->salida .= "		      <td width=\"20%\" class=\"label\">FINALIDAD CIRUGIA</td>";
			$this->salida .= "		      <td colspan=\"3\">".$datosOrdenes['finalidadpro']."</td>";
			$this->salida .= "		      </tr>";
      $this->salida .= "		      </table>";
			$this->salida .= "		    </td><tr>";
		}
		$this->salida .= "         </table><BR>";
		$this->salida .= "		  </td>";
    $this->salida .= "		  </tr>";
		}
    $this->salida .= "		</table><br>";
		$this->salida .= "</td>";
		if(empty($consulta) && $mayorFecha==1){
		$this->salida .= "<td valign=\"bottom\" class=\"modulo_list_oscuro\">";
    $this->salida .= "       <input type=\"submit\" name=\"procedimientosSelec\" value=\"SELECCION\" class=\"input-submit\" $desabilitado>";
    $this->salida .= "</td>";
		}
		$this->salida .= "</td></tr>";
		$this->salida .= "<tr><td class=\"modulo_list_oscuro\">";
		$this->salida .= "      <br><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "		  <tr>";
		$this->salida .= "		  <td class=\"label\"></td>";
		$this->salida .= "		  </tr>";
		$this->salida .= "		  <tr class=\"modulo_table_list_title\">";
		$this->salida .= "		  <td>PAQUETES DE INSUMOS REQUERIDOS</td>";
		$this->salida .= "		  </tr>";
		$this->salida .= "			<tr class=\"modulo_list_claro\">";
		$this->salida .= "			<td>";
		$paquetesIns=$this->paquetesInsertadosRequeridos();
		if($paquetesIns){
			$this->salida .= "        <BR><table border=\"0\" width=\"92%\" align=\"center\" class=\"normal_10\">";
			$this->salida .= "		    <tr class=\"modulo_table_list_title\">";
			$this->salida .= "		    <td align=\"center\" width=\"15%\">PAQUETE</td>";
			$this->salida .= "		    <td width=\"2%\" align=\"center\" width=\"15%\">CANTIDAD</td>";
			$this->salida .= "		    </tr>";
			for($i=0;$i<sizeof($paquetesIns);$i++){
				$this->salida .= "		    <tr class=\"modulo_list_oscuro\">";
				$this->salida .= "		    <td align=\"left\">".$paquetesIns[$i]['descripcion']."</td>";
				$this->salida .= "		    <td width=\"3%\" align=\"center\">".$paquetesIns[$i]['cantidad']."</td>";
				$this->salida .= "		    </tr>";
			}
			$this->salida .= "		    </table><BR>";
		}
		$this->salida .= "		  </td>";
		$this->salida .= "		  </tr>";
		$this->salida .= "		  </table><BR>";
		$this->salida .= "</td>";
		if(empty($consulta) && $mayorFecha==1){
		$this->salida .= "<td valign=\"bottom\" class=\"modulo_list_oscuro\">";
    $this->salida .= "       <input type=\"submit\" name=\"paquetes_insumos\" value=\"SELECCION\" class=\"input-submit\" $desabilitado>";
    $this->salida .= "</td>";
		}
		$this->salida .= "</tr>";
		$this->salida .= "<tr><td class=\"modulo_list_claro\">";
		$this->salida .= "      <br><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "		  <tr>";
		$this->salida .= "		  <td class=\"label\"></td>";
		$this->salida .= "		  </tr>";
		$this->salida .= "		  <tr class=\"modulo_table_list_title\">";
		$this->salida .= "		  <td>INSUMOS Y MATERIAL DE OSTEOSINTESIS REQUERIDOS</td>";
		$this->salida .= "		  </tr>";
		$this->salida .= "			<tr class=\"modulo_list_oscuro\">";
		$this->salida .= "			<td>";
		$Insumos=$this->InsumosInsertadosRequeridos();
		if($Insumos){
			$this->salida .= "        <BR><table border=\"0\" width=\"92%\" align=\"center\" class=\"normal_10\">";
			$this->salida .= "		    <tr class=\"modulo_table_list_title\">";
			$this->salida .= "		    <td align=\"center\" width=\"15%\">INSUMO</td>";
			$this->salida .= "		    <td width=\"2%\" align=\"center\" width=\"15%\">CANTIDAD</td>";
			$this->salida .= "		    </tr>";
			for($i=0;$i<sizeof($Insumos);$i++){
				$this->salida .= "		    <tr class=\"modulo_list_claro\">";
				$this->salida .= "		    <td align=\"left\">".$Insumos[$i]['descripcion']."</td>";
				$this->salida .= "		    <td width=\"3%\" align=\"center\">".$Insumos[$i]['cantidad']."</td>";
				$this->salida .= "		    </tr>";
			}
			$this->salida .= "		    </table><BR>";
		}
		$this->salida .= "		  </td>";
		$this->salida .= "		  </tr>";
		$this->salida .= "		  </table><BR>";
		$this->salida .= "</td>";
		if(empty($consulta) && $mayorFecha==1){
		$this->salida .= "<td valign=\"bottom\" class=\"modulo_list_claro\">";
    $this->salida .= "       <input type=\"submit\" name=\"insumos\" value=\"SELECCION\" class=\"input-submit\" $desabilitado>";
    $this->salida .= "</td>";
		}
		$this->salida .= "<tr><td class=\"modulo_list_oscuro\">";
		$this->salida .= "      <br><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "		  <tr>";
		$this->salida .= "		  <td class=\"label\"></td>";
		$this->salida .= "		  </tr>";
		$this->salida .= "		  <tr class=\"modulo_table_list_title\">";
		$this->salida .= "		  <td>RESERVA DE SANGRE y/o CRUZADA</td>";
		$this->salida .= "		  </tr>";
    $this->salida .= "		  <tr class=\"modulo_list_claro\">";
		$this->salida .= "		  <td>";
		$datosReserva=$this->DatosdelaReservaSangre($datosPaciente['tipo_id_paciente'],$datosPaciente['paciente_id']);
		if($datosReserva){
			$this->salida .= "    <table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
			$this->salida .= "    <tr class=\"modulo_table_list_title\">";
			$this->salida .= "    <td>No. RESERVA</td>";
			$this->salida .= "    <td>FECHA</td>";
			$this->salida .= "    <td>COMPONENTE</td>";
			$this->salida .= "    <td>CANTIDAD</td>";
			$this->salida .= "    <td>CONFIRMADA</td>";
			$this->salida .= "    </tr>";
			foreach($datosReserva as $solicitud=>$vector){
				foreach($vector as $componente=>$vector1){
				  foreach($vector1 as $ingreso=>$datos){
            if($solicitud!=$solicitudAnt){
							if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
							$this->salida .= "    <tr class=\"modulo_list_oscuro\">";
							$this->salida .= "    <td rowspan=\"".sizeof($vector)."\">".$datos['solicitud_reserva_sangre_id']."</td>";
							$this->salida .= "    <td rowspan=\"".sizeof($vector)."\">".$datos['fecha']."</td>";
							if($componente!=$componenteAnt){
								$this->salida .= "    <td>".$datos['componente']."</td>";
								$this->salida .= "    <td>".$datos['cantidad_componente']."</td>";
								if($datos['sw_estado']=='1'){$var='No';}elseif($datos['sw_estado']=='2'){$var='Si';	}
								$this->salida .= "    <td>$var</td>";
								$this->salida .= "    </tr>";
								$componenteAnt=$componente;
							}
							$solicitudAnt=$solicitud;
            }else{
						  if($componente!=$componenteAnt){
								$this->salida .= "    <tr class=\"modulo_list_oscuro\">";
								$this->salida .= "    <td>".$datos['componente']."</td>";
								$this->salida .= "    <td>".$datos['cantidad_componente']."</td>";
								if($datos['sw_estado']=='1'){$var='No';}elseif($datos['sw_estado']=='2'){$var='Si';	}
								$this->salida .= "    <td>$var</td>";
								$this->salida .= "    </tr>";
								$componenteAnt=$componente;
							}
						}
					}
				}
				$y++;
		  }
			$this->salida .= "    </table>";
		}
		$this->salida .= "		  </td>";
		$this->salida .= "		  </tr>";
		$this->salida .= "		  </table><BR>";
		$this->salida .= "</td>";
    $this->salida .= "      <input type=\"hidden\" name=\"tipoIdPac\" value=\"".$datosPaciente['tipo_id_paciente']."\">";
		$this->salida .= "      <input type=\"hidden\" name=\"PacienteId\" value=\"".$datosPaciente['paciente_id']."\">";
		if(empty($consulta) && $mayorFecha==1){
		$this->salida .= "<td valign=\"bottom\" class=\"modulo_list_oscuro\">";
    $this->salida .= "       <input type=\"submit\" name=\"sangre\" value=\"SELECCION\" class=\"input-submit\" $desabilitado>";
    $this->salida .= "</td>";
		}
		$this->salida .= "</tr>";
		/*$this->salida .= "<tr><td class=\"modulo_list_claro\">";
		$this->salida .= "      <br><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "		  <tr>";
		$this->salida .= "		  <td class=\"label\"></td>";
		$this->salida .= "		  </tr>";
		$this->salida .= "		  <tr class=\"modulo_table_list_title\">";
		$this->salida .= "		  <td colspan=\"6\">RESERVA DE LA CAMA PARA EL PACIENTE</td>";
		$this->salida .= "		  </tr>";
		$camas=$this->ReservasCamasQX();
		if($camas){
      $this->salida .= "		  <tr class=\"modulo_table_list_title\">";
			$this->salida .= "		  <td>FECHA</td>";
			$this->salida .= "		  <td>ESTACION</td>";
			$this->salida .= "		  <td>PIEZA</td>";
			$this->salida .= "		  <td>No. CAMA</td>";
			$this->salida .= "		  <td>DESCRIPCION CAMA</td>";
			$this->salida .= "		  <td>UBICACION</td>";
			$this->salida .= "		  </tr>";
			for($i=0;$i<sizeof($camas);$i++){
        $this->salida .= "		  <tr class=\"modulo_list_oscuro\">";
				$this->salida .= "		  <td>".$camas[$i]['fecha_reserva']."</td>";
				$this->salida .= "		  <td>".$camas[$i]['estacion']."</td>";
				$this->salida .= "		  <td>".$camas[$i]['nombrepieza']."</td>";
				$this->salida .= "		  <td>".$camas[$i]['cama']."</td>";
				$this->salida .= "		  <td>".$camas[$i]['nombrecama']."</td>";
				$this->salida .= "		  <td>".$camas[$i]['ubicacioncama']."</td>";
				$this->salida .= "		  </tr>";
			}
		}
		$this->salida .= "		  </table><BR>";
    $this->salida .= "</td>";
		if(empty($consulta) && $mayorFecha==1){
		$this->salida .= "<td valign=\"bottom\" class=\"modulo_list_claro\">";
		$this->salida .= "       <input type=\"hidden\" name=\"FechaProgramFin\" value=\"$FechaProgramFin\">";
    $this->salida .= "       <input type=\"submit\" name=\"reservaCama\" value=\"SELECCION\" class=\"input-submit\" $desabilitado>";
    $this->salida .= "</td>";
		}
    */
    $this->salida .= "<tr><td class=\"modulo_list_oscuro\">";
		$this->salida .= "      <br><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "		  <tr>";
		$this->salida .= "		  <td class=\"label\"></td>";
		$this->salida .= "		  </tr>";
		$this->salida .= "		  <tr class=\"modulo_table_list_title\">";
		$this->salida .= "		  <td colspan=\"4\">CONFIRMACION DEL CONSENTIMIENTO DE LA CIRUGIA</td>";
		$this->salida .= "		  </tr>";
		$consentimientos=$this->ConsentimientosdelPacienteQX();
		if($consentimientos){
		$this->salida .= "		  <tr class=\"modulo_table_list_title\">";
		$this->salida .= "		  <td>TIPO CONSENTIMIENTO</td>";
		$this->salida .= "		  <td>RESPONSABLE</td>";
		$this->salida .= "		  <td>NOMBRE RESPONSABLE</td>";
		$this->salida .= "		  <td>PARENTESCO</td>";
		$this->salida .= "		  </tr>";
		for($i=0;$i<sizeof($consentimientos);$i++){
      $this->salida .= "		  <tr class=\"modulo_list_claro\">";
			$this->salida .= "		  <td>".$consentimientos[$i]['consentimiento']."</td>";
			if($consentimientos[$i]['tipo_id_otroresponsable'] && $consentimientos[$i]['otroresponsable_id']){
			$this->salida .= "		  <td>".$consentimientos[$i]['tipo_id_otroresponsable']." ".$consentimientos[$i]['otroresponsable_id']."</td>";
			$this->salida .= "		  <td>".$consentimientos[$i]['nombre']."</td>";
			$this->salida .= "		  <td>".$consentimientos[$i]['parentesco']."</td>";
			}else{
      $this->salida .= "		  <td>".$datosPaciente['tipo_id_paciente']." ".$datosPaciente['paciente_id']."</td>";
			$this->salida .= "		  <td>$Nombres $Apellidos</td>";
			$this->salida .= "		  <td>PACIENTE</td>";
			}
			$this->salida .= "		  </tr>";
		}
		}
    $this->salida .= "		  </table><BR>";
    $this->salida .= "</td>";
    if(empty($consulta) && $mayorFecha==1){
		$this->salida .= "<td valign=\"bottom\" class=\"modulo_list_oscuro\">";
    $this->salida .= "       <input type=\"submit\" name=\"consentimiento\" value=\"SELECCION\" class=\"input-submit\" $desabilitado>";
    $this->salida .= "</td>";
		}
		$this->salida .= "</tr>";
		$this->salida .= "<tr><td align=\"center\">";
		$this->salida .= "       <input type=\"submit\" name=\"Salir\" value=\"VOLVER\" class=\"input-submit\">&nbsp&nbsp&nbsp;";
		$rep= new GetReports();
		$mostrar=$rep->GetJavaReport('app','Quirurgicos','programacionQX_html',array('programacion'=>$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
		$nombre_funcion=$rep->GetJavaFunction();
		$this->salida .=$mostrar;
    $this->salida .= "       <input type=\"button\" name=\"Imprimir\" value=\"IMPRIMIR\" onclick=\"javascript:$nombre_funcion\" class=\"input-submit\">&nbsp&nbsp&nbsp;";
		//$mostrar1=$rep->GetJavaReport('app','Quirurgicos','StickerQX_html',array('programacion'=>$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
		//$nombre_funcion1=$rep->GetJavaFunction();
		//$this->salida .=$mostrar1;
    //$this->salida .= "       <input type=\"button\" name=\"Imprimir1\" value=\"STICKER\" onclick=\"javascript:$nombre_funcion1\" class=\"input-submit\">";
//STICKER
		if(!IncludeFile("app_modules/Quirurgicos/reports/fpdf/reporte_sticker.inc.php"))
		{
				$this->error = "No se pudo inicializar el archivo reporte_sticker.inc.php";
				$this->mensajeDeError = "No se pudo Incluir el archivo : app_modules/Quirurgicos/reports/fpdf/reporte_sticker.inc.php";
				return false;
		}
		$sticker=Generar_reporte_sticker($_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']);
		$this->salida .= "      <input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"STICKER\" onclick=\"javascript:abreVentana()\">";
//FIN STICKER
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table><br>";
		$this->salida .= "</form>";
		unset($_SESSION['PACIENTES']);
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function Reserva_Paquetes_Insumos_qx($codigoPaquete,$Descripcion){
    $this->paginaActual = 1;
    $this->offset = 0;
    if($_REQUEST['offset']){
      $this->paginaActual = intval($_REQUEST['offset']);
      if($this->paginaActual > 1){
        $this->offset = ($this->paginaActual - 1) * ($this->limit);
      }
    }
		$ProgramacionId=$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO'];
		$this->salida .= ThemeAbrirTabla('INSUMOS REQUERIDOS CIRUGIA');
		$accion=ModuloGetURL('app','Quirurgicos','user','InsertarReqPaqutes',array("offset"=>$this->paginaActual));
		$this->Encabezado();
		$this->salida .= "    <form name=\"forma\" action=\"$accion\" method=\"post\">";
    $this->salida .= "    <table border=\"0\" width=\"85%\" align=\"center\" class=\"modulo_table_list\">";
    $this->salida .= "		<tr class=\"modulo_table_list_title\">";
    $this->salida .= "		<td colspan=\"4\">BUSCADOR DE PAQUETES</td>";
    $this->salida .= "		</tr>";
    $this->salida .= "    <tr class=\"modulo_list_claro\">";
    $this->salida .= "    <td class=\"label\">CODIGO</td>";
    $this->salida .= "    <td align=\"center\"><input size=\"20\" type=\"text\" class=\"input-text\" name=\"codigoPaquete\" value=\"$codigoPaquete\"></td>";
    $this->salida .= "    <td class=\"label\">DESCRIPCION</td>";
    $this->salida .= "    <td align=\"center\"><input size=\"80\" type=\"text\" class=\"input-text\" name=\"Descripcion\" value=\"$Descripcion\"></td>";
    $this->salida .= "    </tr>";
    $this->salida .= "    <tr class=\"modulo_list_claro\"><td align=\"center\" colspan=\"4\">";
    $this->salida .= "    <input type=\"submit\" class=\"input-submit\" value=\"BUSCAR\" name=\"buscar\">";
    $this->salida .= "    <input type=\"submit\" class=\"input-submit\" value=\"VOLVER\" name=\"Salir\">";
    $this->salida .= "    </td></tr>";
    $this->salida .= "    </table>";
    $paquetesNuv=$this->buscarPaquetes($codigoPaquete,$Descripcion);
    if($paquetesNuv){
      $this->salida .= "    <BR><table border=\"0\" width=\"85%\" align=\"center\" class=\"modulo_table_list\">";
      $this->salida .= "		<tr class=\"modulo_table_list_title\">";
      $this->salida .= "		<td colspan=\"5\">PAQUETES DE INSUMOS ENCONTRADOS</td>";
      $this->salida .= "		</tr>";
      $this->salida .= "		<tr class=\"modulo_table_list_title\">";
      $this->salida .= "		<td width=\"15%\">CODIGO</td>";
      $this->salida .= "		<td>PAQUETE</td>";
      $this->salida .= "		<td width=\"5%\"></td>";
      $this->salida .= "		<td width=\"15%\">CANT. REQUERIDA</td>";
      $this->salida .= "		<td width=\"5%\">&nbsp;</td>";
      $this->salida .= "		</tr>";
      for($i=0;$i<sizeof($paquetesNuv);$i++){
        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
        if($y % 2){$estilo1='modulo_list_oscuro';}else{$estilo1='modulo_list_claro';}
        $this->salida .= "		<tr class=\"$estilo\">";
        $this->salida .= "		<td>".$paquetesNuv[$i]['paquete_insumos_id']."</td>";
        $this->salida .= "		<td>".$paquetesNuv[$i]['descripcion']."</td>";
        $actionConsul=ModuloGetURL('app','Quirurgicos','user','ConsultaInsumosPaquetes',array("paqueteId"=>$paquetesNuv[$i]['paquete_insumos_id'],"NomPaquete"=>$paquetesNuv[$i]['descripcion'],"codigoPaquete"=>$codigoPaquete,"Descripcion"=>$Descripcion));
        $this->salida .= "		<td align=\"center\"><a href=\"$actionConsul\"><img title=\"Ver Insumos del Paquete\" border=\"0\" src=\"".GetThemePath()."/images/pconsultar.png\"><a></td>";
        $chequeado='';
        $valCant='';
        if($_SESSION['CIRUGIAS']['PROGRAMACION']['PAQUETES'][$paquetesNuv[$i]['paquete_insumos_id']]){
          $chequeado='checked';
          $valCant=$_SESSION['CIRUGIAS']['PROGRAMACION']['PAQUETES'][$paquetesNuv[$i]['paquete_insumos_id']];
        }else{
          $valCant=1;
        }
        $this->salida .= "		<td align=\"center\"><input type=\"text\" class=\"input-text\" size=\"5\" name=\"Cantidad[".$paquetesNuv[$i]['paquete_insumos_id']."]\" value=\"$valCant\"></td>";
        $this->salida .= "		<td align=\"center\"><input type=\"checkbox\" name=\"Seleccion[".$paquetesNuv[$i]['paquete_insumos_id']."]\" value=\"1\" $chequeado></td>";
        if($chequeado){
          $this->salida .= "        <input type=\"hidden\" name=\"SeleccionActual[".$paquetesNuv[$i]['paquete_insumos_id']."]\" value=\"1\"></td>";
        }
        $this->salida .= "		</tr>";
        $y++;
      }
      $this->salida .= "    </table><BR>";
      $Paginador = new ClaseHTML();
      $this->actionPaginador=ModuloGetURL('app','Quirurgicos','user','BusquedaNuevoPaqueteInsumos',array("codigoPaquete"=>$codigoPaquete,"Descripcion"=>$Descripcion,"offset"=>$this->paginaActual));
			$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->actionPaginador);
    }
    $this->salida .= "    <table border=\"0\" width=\"85%\" align=\"center\" class=\"normal_10\">";
    $this->salida .= "    <tr>";
    $this->salida .= "    <td align=\"right\">";
    $this->salida .= "    <input type=\"submit\" class=\"input-submit\" name=\"Seleccionar\" value=\"GUARDAR\">";
    $this->salida .= "    </td></tr>";
    $this->salida .= "    </table>";
		//$paquetesIns=$this->paquetesInsertadosRequeridos();
		$this->salida .= "		</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

/**
* Funcion que se encarga de visualizar la forma para capturar los datos principales para la insercion de una programacion
* @return boolean
* @param string tipo de documento del paciente
* @param string numero del documento del paciente
* @param string sitio a donde se dirige la funcion
* @param string sitio desde donde se llama la funcion
*/
	function FormaBuscarPacientePresupuesto($TipoId,$Documento,$action,$Responsable,$bandera,$cirujano,$codigo,$cargo)
	{

	  if($bandera==1){$palabra='SELECCION DATOS PACIENTE Y CIRUJANO EN LA PROGRAMACION DE CIRUGIA';}else{$palabra='SELECCION DATOS PACIENTE PARA EL PRESUPUESTO DE CIRUGIA';}
    $this->salida .= ThemeAbrirTabla($palabra);
    $this->salida .="<script>\n";
		$this->salida .= "  function seleccionDiagnostico(valor,frm,x){";
    $this->salida .= "  if(x==true){";
		$this->salida .= "    cadena=valor.split('/');";
		$this->salida .= "    diagnostico=cadena[0];";
		$this->salida .= "    descripcion=cadena[1];";
    $this->salida .= "    frm.codigo.value=diagnostico;";
		$this->salida .= "    frm.cargo.value=descripcion;";
		$this->salida .= "  }";
		$this->salida .= " }";
		$this->salida .="</script>\n";
		$this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->Encabezado();
		$ProgramacionId=$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO'];
		if($ProgramacionId){
		  $datosPaciente=$this->SacaDatosPacienteProgramQX($ProgramacionId);
		  $cirujano=$datosPaciente['cirujano_id'].'/'.$datosPaciente['tipo_id_cirujano'];
			if(empty($cargo) && empty($codigo)){
				$cargo=$datosPaciente['diagnostico_nombre'];
				$codigo=$datosPaciente['diagnostico_id'];
			}
			$TipoId=$datosPaciente['tipo_id_paciente'];
      $Documento=$datosPaciente['paciente_id'];
			$Responsable=$datosPaciente['plan_id'];
		}
		$this->salida .= "<table width=\"85%\" align=\"center\" border=\"0\">\n";
		$this->salida .= "      <tr><td colspan=\"4\" align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "      </td></tr>";
		$this->salida .= "<tr><td class=\"modulo_list_oscuro\">";
		$this->salida .= "   <BR><table width=\"90%\" border=\"0\" align=\"center\" class=\"normal_10\" >";
    $this->salida .= "		<tr class=\"modulo_table_list_title\"><td colspan=\"2\" class=\"modulo_table_list_title\">DATOS DEL PACIENTE</td></tr>";
		$this->salida .= "		<tr class=\"modulo_list_claro\"><td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"TipoDocumento\" class=\"select\">";
		$tipo_id=$this->tipo_id_paciente();
		$this->Mostrar($tipo_id,'False',$TipoId);
		$this->salida .= "    </select></td></tr>";
		$this->salida .= "		<tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("Documento")."\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" value=\"$Documento\"></td></tr>";
		if($bandera!=1){
			$this->salida .= "  <tr class=\"modulo_list_claro\"><td class=\"label\" width=\"30%\">GUARDAR PRESUPUESTO</td>";
			$this->salida .= "  <td class=\"label\" width=\"30%\">SI";
			$this->salida .= "  <input type=\"radio\" name=\"GuardarPre\" checked value=\"1\">NO<input type=\"radio\" name=\"GuardarPre\" value=\"2\"></td></tr>";
		}
		$this->salida .= "		<tr class=\"modulo_list_claro\" height=\"20\"><td class=\"".$this->SetStyle("Responsable")."\">PLAN: </td><td><select name=\"Responsable\"  class=\"select\">";
		$responsables=$this->CallMetodoExterno('app','Triage','user','responsables');
		$this->MostrarResponsable($responsables,$Responsable);
		$this->salida .= "    </select></td></tr>";
		$this->salida .= "		 </table><BR>";
		$this->salida .= "</td></tr>";
		if($bandera==1){
		  $this->salida .= "<tr><td class=\"modulo_list_claro\">\n";
		  $this->salida .= "<BR><table width=\"90%\" border=\"0\" align=\"center\" class=\"normal_10\">\n";
			$this->salida .= "<tr><td class=\"modulo_table_list_title\">DATOS DEL PROFESIONAL</td></tr>\n";
      $this->salida .= "<tr><td class=\"modulo_list_oscuro\">";
			$this->salida .= "<table width=\"100%\" border=\"0\" align=\"center\" class=\"normal_10\">\n";
			$this->salida .= "<tr>";
			$this->salida .= "<td width=\"10%\" nowrap class=\"".$this->SetStyle("cirujano")."\">CIRUJANO</td>";
			$this->salida .= "<td><select name=\"cirujano\" class=\"select\">\n";
			$profesionales=$this->profesionalesEspecialista();
			$this->BuscarProfesionlesEspecialistas($profesionales,'False',$cirujano);
			$this->salida .= "</select>&nbsp&nbsp&nbsp;<input class=\"input-submit\" name=\"AdicionProfe\" type=\"submit\" value=\"ADICIONAR PROFESIONAL\"></td>\n";
			$this->salida .= "</tr>";
			$this->salida .= "</table>";
			$this->salida .= "</td></tr>";
			$this->salida .= "<tr><td class=\"modulo_list_oscuro\">";
			$this->salida .= "<table width=\"100%\" border=\"0\" align=\"center\" class=\"normal_10\">\n";
			$this->salida .= "<tr>";
			$this->salida.= "<td colspan=\"2\"><label class=\"".$this->SetStyle("cargo")."\">DIAGNOSTICO PRINCIPAL</label></td>\n";
			$this->salida .= "</tr>";
			$this->salida .= "</table>";
			$this->salida .= "</td></tr>";
			$this->salida .= "<tr><td class=\"modulo_list_oscuro\">";
			$this->salida .= "<table width=\"100%\" border=\"0\" align=\"center\" class=\"normal_10\">\n";
			$this->salida .= "<tr>";
      $this->salida.= "<td><input type=\"text\" name=\"codigo\" maxlength=\"6\" size=\"6\" class=\"input-text\" value=\"$codigo\" READONLY>&nbsp&nbsp&nbsp;";
			$this->salida.= "<input type=\"text\" name=\"cargo\" maxlength=\"256\" size=\"80\" class=\"input-text\" value=\"$cargo\" READONLY>&nbsp&nbsp&nbsp;";
			$this->salida.= "<input type=\"submit\" name=\"buscar\" value=\"BUSCAR\" class=\"input-submit\"></td>";
			$this->salida .= "</tr>";
			if(!$codigo && !$cargo){
        $Diagnosticos=$this->BusquedaDiagnosticosSolicitud($ProgramacionId);
				if($Diagnosticos){
				  $this->salida .= "<tr><td></td></tr>";
				  $this->salida .= "<tr><td>";
				  $this->salida .= "<table width=\"90%\" border=\"0\" align=\"center\" class=\"normal_10\">\n";
          $this->salida .= "<tr><td align=\"center\" colspan=\"3\" class=\"modulo_table_title\">DIAGNOSTICOS SOLICITUD</td></tr>";
          for($i=0;$i<sizeof($Diagnosticos);$i++){
            $this->salida .= "<tr>";
						$this->salida .= "<td class=\"modulo_list_claro\"><input type=\"radio\" name=\"diagnostico\" value=\"".$Diagnosticos[$i]['diagnostico_id']."/".$Diagnosticos[$i]['diagnostico_nombre']."\" onclick=\"seleccionDiagnostico(this.value,this.form,this.checked)\"></td><td class=\"modulo_list_claro\">".$Diagnosticos[$i]['diagnostico_id']."</td><td class=\"modulo_list_claro\">".$Diagnosticos[$i]['diagnostico_nombre']."</td>";
						$this->salida .= "<tr>";
					}
					$this->salida .= "</table>";
					$this->salida .= "</td></tr>";
				}
			}
			$this->salida .= "</table><BR>";
			$this->salida .= "</td></tr>";
      $this->salida .= "</td></tr>";
      $this->salida .= "</table><BR>\n";
			$this->salida .= "</td></tr>\n";
		}
    $this->salida .= "</table>";
		$this->salida .= "   <table width=\"90%\" border=\"0\" align=\"center\"class=\"normal_10\" >";
    $this->salida .= "		<tr><td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"GUARDAR\">";
		if($bandera==1){
		  $this->salida .= "	 <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
		}
		$this->salida .= "		</td></tr>";
    $this->salida .= "</table>";
		$this->salida .= "</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
/**
* Funcion que se encarga de listar los tipos de responsables para mostrarlos por pantalla
* @return array
* @param array codigos y valores de los tipos de responsables de la base de datos
* @param string elemento seleccionado en el objeto donde se imprimen los tipo de responsables
*/
	function MostrarResponsable($responsables,$Responsable){
		$this->salida .=" <option value=\"-1\">-------SELECCIONE-------</option>";
		for($i=0; $i<sizeof($responsables); $i++){
			if($responsables[$i][plan_id]==$Responsable){
					$this->salida .=" <option value=\"".$responsables[$i][plan_id]."\" selected>".$responsables[$i][plan_descripcion]."</option>";
			}else{
					$this->salida .=" <option value=\"".$responsables[$i][plan_id]."\">".$responsables[$i][plan_descripcion]."</option>";
			}
		}
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
/**
* Funcion que visualiza los datos requeridos del anestesiologo
* @return boolean
*/
	function SeleccionProfesionalesPx(){
    $ProgramacionId=$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO'];
		$this->salida .= ThemeAbrirTabla('DATOS Y REQUERIMIENTOS DE PROFESIONALES');
		$accion=ModuloGetURL('app','Quirurgicos','user','ValidacionProfesionalesQx');
		$this->salida .= "    <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->Encabezado();
    $this->salida .= "   <table border=\"0\" width=\"60%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "     <tr><td></td></tr>";
		$this->salida .= "      <tr><td><fieldset><legend class=\"field\">DATOS PROFESIONALES</legend>";
		$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
		if($ProgramacionId){
			$datos=$this->obtenerDatosAnestesiologoQX($ProgramacionId);
      $anestesista=$datos['tercero_id'].'/'.$datos['tipo_id_tercero'];
			$instrumentista=$datos['instrumentista_id'].'/'.$datos['tipo_id_instrumentista'];
			$circulante=$datos['circulante_id'].'/'.$datos['tipo_id_circulante'];
      $ayudante=$datos['ayudante_id'].'/'.$datos['tipo_id_ayudante'];
		}
		$this->salida .= "      <tr><td colspan=\"4\" align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "      </td></tr>";
    $this->salida .= "			  <tr><td class=\"".$this->SetStyle("anestesista")."\">ANESTESIOLOGO:</td><td><select name=\"anestesista\" class=\"select\">";
	  $profesionales=$this->profesionalesEspecialistaAnestecistas();
	  $this->BuscarProfesionlesEspecialistas($profesionales,'False',$anestesista);
	  //$profesionales=$this->profesionalesAyudantes();
	  //$this->BuscarProfesionlesAyudantes($profesionales,'False',$ayudante);
	  $this->salida .= "       </select></td></tr>";
    $this->salida .= "				<tr><td class=\"".$this->SetStyle("ayudante")."\">AYUDANTE:</td><td><select name=\"ayudante\" class=\"select\">";
	  $profesionales=$this->profesionalesAyudantes();
	  $this->BuscarProfesionlesEspecialistas($profesionales,'False',$ayudante);
	  $this->salida .= "        </select></td></tr>";
		$this->salida .= "			  <tr><td class=\"".$this->SetStyle("instrumentista")."\">INSTRUMENTADOR(A):</td><td><select name=\"instrumentista\" class=\"select\">";
	  $instrumentistas=$this->profesionalesEspecialistaInstrumentistas();
	  $this->BuscarProfesionlesEspecialistas($instrumentistas,'False',$instrumentista);
	  //$profesionales=$this->profesionalesAyudantes();
	  //$this->BuscarProfesionlesAyudantes($profesionales,'False',$ayudante);
	  $this->salida .= "       </select></td></tr>";

		$this->salida .= "			  <tr><td class=\"".$this->SetStyle("circulante")."\">CIRCULANTE:</td><td><select name=\"circulante\" class=\"select\">";
	  $ciruculantes=$this->profesionalesEspecialistaCiculantes();
	  $this->BuscarProfesionlesEspecialistas($ciruculantes,'False',$circulante);
	  //$profesionales=$this->profesionalesAyudantes();
	  //$this->BuscarProfesionlesAyudantes($profesionales,'False',$ayudante);
	  $this->salida .= "       </select></td></tr>";
		$this->salida .= "     <tr><td></td></tr>";
		$this->salida .= "   </table>";
		$this->salida .= "		</fieldset></td></tr></table><BR>";
		$this->salida .= "   <table border=\"0\" width=\"60%\" align=\"center\" class=\"normal_10\">";
    $this->salida .= "    <tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" class=\"input-submit\" name=\"Aceptar\" value=\"GUARDAR\">";
		$this->salida .= "    <input type=\"submit\" class=\"input-submit\" name=\"Cancelar\" value=\"CANCELAR\"></td></tr>";
    $this->salida .= "   </table>";
		$this->salida .= "		</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
/**
* Funcion que se encarga de visualizar la forma para capturar los datos de los procedimientos que se van a llevar a cabo en la cirugia
* @return boolean
* @param string codigo unico que identifica el procedimiento por defecto
* @param string descripcion del procedimiento por defecto
* @param string codigo que identifica el cirujano por defecto
* @param string codigo que identifica el ayudante por defecto
* @param string codigo que identifica el origen del llamado de la funcion
* @param string codigo que identifica la via de aaceso por defecto de la cirujia
* @param string codigo que identifica el tpo de la cirujia por defecto
* @param string codigo que identifica el ambito del acirujia por defecto
*/
	function ProcedimientosQuirurgicos($codigos,$procedimiento,$cirujano,$ayudante,$OrigenFunc,$viaAcceso,$tipoCirugia,$ambitoCirugia,$Responsable,$numerorden,$pediatrico,$pediatra,$bilateral){
    //if(!$cirujano,$ayudantedefecto)
    $ProgramacionId=$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO'];
    include 'app_modules/Quirurgicos/RemoteXajax/OpcionesProcedimientos.php';
    $this->SetXajax(array("reqInsertarVentanaOpciones","reqEliminarOpcionesProc"));        
    $opcionesProcedimientos=$this->BuscarOpcionesProcedimientos($codigos);    
        
		
    $_ROOT='';
		GLOBAL $ADODB_FETCH_MODE;
		$vectorCargo=array(); 
    $this->IncludeJS("CrossBrowser");
    $this->IncludeJS("CrossBrowserDrag");
    $this->IncludeJS("CrossBrowserEvent");   
		$RUTA = $_ROOT ."classes/classbuscador/buscador.php?forma=formaProgracionQx&tipo=";
		$this->salida.="<script language='javascript'>\n";
		$this->salida.="  var rem=\"\";\n";
		$this->salida.="  function abrirVentana(nom,frm){\n";
		$this->salida.="    var nombre=\"PROCEDIMIENTOS QUIRURGICOS\";\n";
    $this->salida.="    var valortipo=frm.tipoProcedimiento.value;";
		$this->salida.="    if(nom=='buscar2'){\n";
		$this->salida.="      var tipo=\"procedimientosQX\";\n";
		$this->salida.="      var alias=\"codigos\";\n";
		$this->salida.="    }\n";
		$this->salida.="    var str =\"width=450,height=250,resizable=no,status=no,scrollbars=yes\";\n";
		$this->salida.="    var url2 ='$RUTA'+tipo+'&alias='+alias+'&key=cargo,descripcion'+'&tipoProcedimiento='+valortipo;\n";
		$this->salida.="    rem = window.open(url2, nombre, str);}\n";		
    $this->salida .= "  function Iniciar()\n";
    $this->salida .= "  {\n";        
    $this->salida .= "    document.getElementById('titulo').innerHTML = '<center>OPCIONES PROCEDIMIENTOS</center>';\n";
    $this->salida .= "    document.getElementById('error').innerHTML = '';\n";                
    $this->salida .= "    contenedor = 'd2Container';\n";
    $this->salida .= "    titulo = 'titulo';\n";
    $this->salida .= "    ele = xGetElementById('d2Container');\n";
    $this->salida .= "    xResizeTo(ele,500, 'auto');\n";
    $this->salida .= "    xMoveTo(ele, xClientWidth()/3, xScrollTop()+24);\n";
    $this->salida .= "    ele = xGetElementById('titulo');\n";
    $this->salida .= "    xResizeTo(ele,480, 20);\n";
    $this->salida .= "    xMoveTo(ele, 0, 0);\n";
    $this->salida .= "    xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
    $this->salida .= "    ele = xGetElementById('cerrar');\n";
    $this->salida .= "    xResizeTo(ele,20, 20);\n";
    $this->salida .= "    xMoveTo(ele, 480, 0);\n";
    $this->salida .= "  }\n";
    $this->salida .= "  function myOnDragStart(ele, mx, my)\n";
    $this->salida .= "  {\n";
    $this->salida .= "    window.status = '';\n";
    $this->salida .= "    if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
    $this->salida .= "    else xZIndex(ele, hiZ++);\n";
    $this->salida .= "    ele.myTotalMX = 0;\n";
    $this->salida .= "    ele.myTotalMY = 0;\n";
    $this->salida .= "  }\n";
    $this->salida .= "  function myOnDrag(ele, mdx, mdy)\n";
    $this->salida .= "  {\n";
    $this->salida .= "    if (ele.id == titulo) {\n";
    $this->salida .= "      xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
    $this->salida .= "    }\n";
    $this->salida .= "    else {\n";
    $this->salida .= "      xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
    $this->salida .= "    }  \n";
    $this->salida .= "    ele.myTotalMX += mdx;\n";
    $this->salida .= "    ele.myTotalMY += mdy;\n";
    $this->salida .= "  }\n";
    $this->salida .= "  function myOnDragEnd(ele, mx, my)\n";
    $this->salida .= "  {\n";
    $this->salida .= "  }\n";
    $this->salida .= "  function MostrarSpan(Seccion)\n";
    $this->salida .= "  { \n";
    $this->salida .= "    e = xGetElementById(Seccion);\n";
    $this->salida .= "    e.style.display = \"\";\n";
    $this->salida .= "  }\n";
    $this->salida .= "  function Cerrar()\n";
    $this->salida .= "  { \n";
    $this->salida .= "    e = xGetElementById('d2Container');\n";
    $this->salida .= "    e.style.display = \"none\";\n";
    $this->salida .= "  }\n";
    $this->salida .= "  function MostrarVentana(Seccion)\n";
    $this->salida .= "  { \n";
    $this->salida .= "    e = xGetElementById(Seccion);\n";
    $this->salida .= "    e.style.display = \"block\";\n";
    $this->salida .= "  }\n";  
    $this->salida .= "  function OrdenarVector(objeto,cargo)\n";
    $this->salida .= "  { \n";    
    $this->salida .= "    var cadena;\n";
    $this->salida .= "    var cadenafin;\n";
    $cont=sizeof($opcionesProcedimientos);              
    for($i=0;$i<$cont;$i++){     
      $this->salida .= "      if(objeto.seleccion".$opcionesProcedimientos[$i]['procedimiento_opcion'].".checked==true){;";    
      $this->salida .= "        cadena=objeto.seleccion".$opcionesProcedimientos[$i]['procedimiento_opcion'].".value+'||//';";                      
      $this->salida .= "        cadenafin=cadena+cadenafin;";                      
      $this->salida .= "      }\n";            
    }    
    $this->salida .= "        xajax_reqInsertarVentanaOpciones(cadenafin,cargo,'$ProgramacionId');";       
    $this->salida .= "  }\n";      
    $this->salida.="</script>\n";
		$this->salida .= ThemeAbrirTabla('PROCEDIMIENTOS PROGRAMACION CIRUGIA');
    
    //ELABORACION DE LA VENTANA DE GASES ANESTESICOS                
    $ventana.= "  <div id='d2Container' class='d2Container' style=\"display:none\">\n";
    $ventana.= "  <div id='titulo' class='draggable' style=\" text-transform: uppercase;\"></div>\n";
    $ventana.= "  <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('d2Container')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
    $ventana.= "  <div id='error' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
    $ventana.= "  <div id='d2Contents'>\n";
    $ventana.= "      <form name=\"formaVentana\" action=\"$action\" method=\"post\">";                     
    if($opcionesProcedimientos){
      $ventana.= "    <table align=\"center\" width=\"98%\">";  
      $ventana.="       <tr class=\"modulo_table_list_title\"><td colspan=\"3\">$procedimiento</td></tr>";
      $ventana.="       <tr class=\"modulo_table_list_title\">";
      $ventana.="       <td width=\"5%\">&nbsp;</td>";
      $ventana.="       <td width=\"20%\">CODIGO</td>";
      $ventana.="       <td>PROCEDIMIENTO</td>";
      $ventana.="       </tr>";
      $cont=sizeof($opcionesProcedimientos);              
      for($i=0;$i<$cont;$i++){          
        $ventana.="   <tr class=\"modulo_list_claro\">";
        $ventana.="   <td width=\"5%\"><input type=\"checkbox\" name=\"seleccion[]\" align=\"center\" value=\"".$opcionesProcedimientos[$i]['procedimiento_opcion']."\" id=\"seleccion".$opcionesProcedimientos[$i]['procedimiento_opcion']."\"></td>";
        $ventana.="   <td width=\"20%\">".$opcionesProcedimientos[$i]['procedimiento_opcion']."</td>";      
        $ventana.="   <td>".$opcionesProcedimientos[$i]['descripcion']."</td>";      
        $ventana.="   </tr>";                                        
      }      
      $ventana.="     <tr><td align=\"center\" class=\"input-submit\" colspan=\"3\"><input type=\"button\" name=\"insertar\" value=\"INSERTAR\" onclick=\"OrdenarVector(document.formaVentana,'$codigos');\"></td></tr>";         
      $ventana.="     </table>";      
    }
    $ventana.="       </form>";
    $ventana.="     </div>";
    $ventana.="</div>";        
    $this->salida.=$ventana;
    
    
		$this->salida .= "			<br><br>";
		if($ProgramacionId){
		  $procedimientos=$this->BusquedaProcedimientosProgram($ProgramacionId);
			//$datosQX=$this->DatosProgramacionQX($ProgramacionId);
		}
		$this->Encabezado();		
		/*$action=ModuloGetURL('app','Quirurgicos','user','InsercionDatosProgramCirugias');
		$this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->salida.= "     <input type=\"hidden\" name=\"codigos\" value=\"$codigos\" READONLY>";
		$this->salida.= "     <input type=\"hidden\" name=\"procedimiento\" value=\"$procedimiento\" READONLY>";
		$this->salida.= "     <input type=\"hidden\" name=\"cirujano\" value=\"$cirujano\" READONLY>";
		$this->salida.= "     <input type=\"hidden\" name=\"ayudante\" value=\"$ayudante\" READONLY>";		
		$this->salida .= "    <br><table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "     <tr><td colspan=\"4\" align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "     </td></tr>";
		$this->salida .= "		 <tr class=\"modulo_table_list_title\">";
    $this->salida .= "		 <td>DATOS CIRUGIA</td>";
    $this->salida .= "		 </tr>";
		$this->salida .= "		 <tr class=\"modulo_list_oscuro\">";
    $this->salida .= "		 <td>";
		$this->salida .= "      <BR><table border=\"0\" width=\"92%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "			  <tr class=\"modulo_list_claro\"><td width=\"20%\" class=\"".$this->SetStyle("viaAcceso")."\">VIA ACCESO:</td><td><select name=\"viaAcceso\" class=\"select\">";
		$viaAcceso=$datosQX['via_acceso'];
	  $accesos=$this->ViaAccesosCirugia();
	  $this->MostrasSelect($accesos,'False',$viaAcceso);
	  $this->salida .= "        </select></td>";
		$this->salida.= "         </tr>";
		$this->salida .= "			  <tr class=\"modulo_list_claro\"><td width=\"20%\" class=\"".$this->SetStyle("tipoCirugia")."\">TIPO CIRUGIA:</td><td><select name=\"tipoCirugia\" class=\"select\">";
		$tipoCirugia=$datosQX['tipo_cirugia'];
	  $tiposCirugias=$this->TiposdeCirugia();
	  $this->MostrasSelect($tiposCirugias,'False',$tipoCirugia);
	  $this->salida .= "        </select></td>";
		$this->salida.= "         </tr>";
		$this->salida .= "			  <tr class=\"modulo_list_claro\"><td width=\"20%\" class=\"".$this->SetStyle("ambitoCirugia")."\">AMBITO CIRUGIA:</td><td><select name=\"ambitoCirugia\" class=\"select\">";
		$ambitoCirugia=$datosQX['ambito_cirugia'];
	  $tiposAmbitos=$this->TiposdeAmbitosdeCirugia();
	  $this->MostrasSelect($tiposAmbitos,'False',$ambitoCirugia);
	  $this->salida .= "        </select></td>";
    $this->salida .= "			  <tr class=\"modulo_list_claro\"><td width=\"20%\" class=\"".$this->SetStyle("finalidadCirugia")."\">FINALIDAD CIRUGIA:</td><td><select name=\"finalidadCirugia\" class=\"select\">";
		$finalidadCirugia=$datosQX['finalidad_procedimiento_id'];
	  $finalidades=$this->finalidadeCirugia();
	  $this->MostrasSelect($finalidades,'False',$finalidadCirugia);
	  $this->salida .= "        </select>&nbsp&nbsp&nbsp&nbsp;<input class=\"input-submit\" type=\"submit\" name=\"Seleccionar\" value=\"GUARDAR\"></td>";
		$this->salida.= "         </tr>";
    $this->salida .= "         </table><BR>";
		$this->salida .= "		  </td>";
    $this->salida .= "		  </tr>";
		$this->salida .= "    </table>";
    $this->salida .= "    </form>";*/
		$accion=ModuloGetURL('app','Quirurgicos','user','InsertarProcedimientosQururgicos');
		$this->salida .= "    <form name=\"formaProgracionQx\" action=\"$accion\" method=\"post\">";
		$this->salida.= "     <input type=\"hidden\" name=\"viaAcceso\" value=\"$viaAcceso\" READONLY>";
		$this->salida.= "     <input type=\"hidden\" name=\"tipoCirugia\" value=\"$tipoCirugia\" READONLY>";
		$this->salida.= "     <input type=\"hidden\" name=\"ambitoCirugia\" value=\"$ambitoCirugia\" READONLY>";
		$this->salida.= "     <input type=\"hidden\" name=\"codigos1\" value=\"$codigos\" READONLY>";
		$this->salida .= "    <table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "		 <tr class=\"modulo_table_list_title\">";
    $this->salida .= "		 <td>PROCEDIMIENTOS</td>";
    $this->salida .= "		 </tr>";
		for($i=0;$i<sizeof($procedimientos);$i++){
    $this->salida .= "		 <tr class=\"modulo_list_oscuro\">";
    $this->salida .= "		 <td>";
		$this->salida .= "        <BR><table border=\"0\" width=\"92%\" align=\"center\" class=\"normal_10\">";
    $this->salida .= "		     <tr class=\"modulo_list_claro\">";
    $this->salida .= "		     <td width=\"15%\" class=\"label\">CODIGO</td>";
		$procedimientoDes=$this->DescripcionProcedimiento($procedimientos[$i]['procedimiento_qx']);
    $this->salida .= "		      <td colspan=\"3\">".$procedimientos[$i]['procedimiento_qx']."&nbsp&nbsp&nbsp;".$procedimientoDes['descripcion']."</td>";
    $this->salida .= "		     </tr>";
    $this->salida .= "         <tr class=\"modulo_list_claro\"><td colspan=\"4\">";
    $procedimientosOpc=$this->BuscarProcedimientosInsertados($ProgramacionId,$procedimientos[$i]['procedimiento_qx']);
    if($procedimientosOpc){
      $this->salida.="<table border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10\">";
      $this->salida.="<tr class=\"modulo_table_list_title\">";
      $this->salida.="<td width=\"10%\">CODIGO</td>";
      $this->salida.="<td>PROCEDIMIENTO</td>";      
      $this->salida.="</tr>";        
      for($m=0;$m<sizeof($procedimientosOpc);$m++){
        $this->salida.="<tr class=\"modulo_list_oscuro\">";
        $this->salida.="<td width=\"20%\">".$procedimientosOpc[$m]['procedimiento_opcion']."</td>";
        $this->salida.="<td>".$procedimientosOpc[$m]['descripcion']."</td>";        
        $this->salida.="</tr>";
      }        
      $this->salida.="</table>";
    }
    $this->salida .= "         </td></tr>";    
		$this->salida .= "		      <tr class=\"modulo_list_claro\">";
    $this->salida .= "		      <td width=\"15%\" class=\"label\">PLAN</td>";
		$NombreResponsable=$this->Responsable($procedimientos[$i]['plan_id']);
		$NombrePlan=$this->PlanNombre($procedimientos[$i]['plan_id']);
		$this->salida .= "		      <td>$NombreResponsable  $NombrePlan</td>";
		$this->salida .= "		      <td width=\"15%\" class=\"label\">No. ORDEN</td>";
		$this->salida .= "		      <td>".$procedimientos[$i]['numero_orden_id']."</td>";
    $this->salida .= "		      </tr>";
		$this->salida .= "		      <tr class=\"modulo_list_claro\">";
		$nombreAn=$this->NombreProfesional($procedimientos[$i]['cirujano_id'],$procedimientos[$i]['tipo_id_cirujano']);
    $this->salida .= "		      <td width=\"15%\" class=\"label\">CIRUJANO</td>";
		$this->salida .= "		      <td colspan=\"3\">".$nombreAn['nombre']."</td>";
    $this->salida .= "		      </tr>";
    /*if($procedimientos[$i]['pediatra_id'] && $procedimientos[$i]['tipo_id_pediatra']){
			$this->salida .= "		      <tr class=\"modulo_list_claro\">";
			$nombrePe=$this->NombreProfesional($procedimientos[$i]['pediatra_id'],$procedimientos[$i]['tipo_id_pediatra']);
			$this->salida .= "		      <td width=\"15%\" class=\"label\">PEDIATRA</td>";
			$this->salida .= "		      <td colspan=\"3\">".$nombrePe['nombre']."</td>";
			$this->salida .= "		      </tr>";
		}*/
		if($procedimientos[$i]['via_procedimiento_bilateral']){
			$this->salida .= "		      <tr class=\"modulo_list_claro\">";
			$this->salida .= "		      <td width=\"15%\" class=\"label\">VIA ACCESO</td>";
			$this->salida .= "		      <td colspan=\"3\">".$procedimientos[$i]['nomvia']."</td>";
			$this->salida .= "		      </tr>";
		}
    if($procedimientos[$i]['observaciones']){
      $this->salida .= "		      <tr class=\"modulo_list_claro\">";
      $this->salida .= "		      <td width=\"15%\" class=\"label\">OBSERVACIONES</td>";
      $this->salida .= "		      <td colspan=\"3\">".$procedimientos[$i]['observaciones']."</td>";
      $this->salida .= "		      </tr>";
    }
		$this->salida .= "		      <tr class=\"modulo_list_claro\">";
		$actionElim=ModuloGetURL('app','Quirurgicos','user','LlamaConfirmarAccion',array("Titulo"=>'ELIMINAR PROCEDIMIENTO',"mensaje"=>'Esta Seguro de Eliminar este Procedimiento',"boton1"=>'ACEPTAR',"boton2"=>'CANCELAR',"arreglo"=>array('Procedimiento'=>$procedimientos[$i]['procedimiento_qx']),"c"=>'app',"m"=>'Quirurgicos',"me"=>'EliminarProcedimientoQX',"me2"=>'ProcedimientosQuirurgicos'));
		$actionModidy=ModuloGetURL('app','Quirurgicos','user','ModificarProcedimientoQX',array('Procedimiento'=>$procedimientos[$i]['procedimiento_qx']));
    $this->salida .= "		      <td align=\"right\" colspan=\"4\"><a href=\"$actionElim\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"><a>&nbsp&nbsp&nbsp;<a href=\"$actionModidy\"><img border=\"0\" src=\"".GetThemePath()."/images/modificar.png\"><a></td>";
    $this->salida .= "		      </tr>";
    $this->salida .= "		  </td></tr>";
		if($procedimientos[$i]['numero_orden_id']){
		  $datosOrdenes=$this->DatosOrdenesCirugia($procedimientos[$i]['numero_orden_id']);
      $this->salida .= "		    <tr><td width=\"100%\" colspan=\"4\">";
      $this->salida .= "        <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= "		      <tr class=\"modulo_table_title\"><td colspan=\"4\" align=\"center\">DATOS DE LA SOLICITUD</td></tr>";
      $this->salida .= "		      <tr class=\"modulo_list_claro\">";
			$this->salida .= "		      <td width=\"20%\" class=\"label\">TIPO CIRUGIA</td>";
			$this->salida .= "		      <td>".$datosOrdenes['tipocirugia']."</td>";
			$this->salida .= "		      <td width=\"20%\" class=\"label\">AMBITO CIRUGIA</td>";
			$this->salida .= "		      <td>".$datosOrdenes['ambitocirugia']."</td>";
			$this->salida .= "		      </tr>";
			$this->salida .= "		      <tr class=\"modulo_list_claro\">";
			$this->salida .= "		      <td width=\"20%\" class=\"label\">FINALIDAD CIRUGIA</td>";
			$this->salida .= "		      <td colspan=\"3\">".$datosOrdenes['finalidadpro']."</td>";
			$this->salida .= "		      </tr>";
      $this->salida .= "		      </table>";
			$this->salida .= "		    </td><tr>";
		}
		$this->salida .= "         </table><BR>";
		$TipoAYUult=$procedimientos[$i]['cirujano_id'];
		$AyudanteUlt=$procedimientos[$i]['tipo_id_cirujano'];
		$PlanUlt=$procedimientos[$i]['plan_id'];
		}
		
		if(empty($TipoAYUult) || empty($AyudanteUlt)){
			$CiruPrincipal=$this->BuscarCirujanoPrincipalQX($ProgramacionId);
			if(!$cirujano || $cirujano==-1){
			$cirujano=$CiruPrincipal['cirujano_id'].'/'.$CiruPrincipal['tipo_id_cirujano'];
			}
		}else{
			$cirujano=$TipoAYUult.'/'.$AyudanteUlt;
		}
		if(empty($PlanUlt)){
			$PlanUlt=$CiruPrincipal['plan_id'];
		}		
    $this->salida .= "      </table><BR>";
    $Ordenes=$this->OrdenesPendientesPaciente($ProgramacionId);
    if($Ordenes){
		  $y=0;
			$this->salida .= "    <BR><table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
      $this->salida .= "    <tr><td class=\"modulo_list_claro\">";
			$this->salida .= "    <br><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
      $this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"4\">ORDENES PENDIENTES</td></tr>";
      $this->salida .= "    <tr class=\"modulo_table_list_title\">";
      $this->salida .= "    <td>No. ORDEN</td>";
			$this->salida .= "    <td>CODIGO</td>";
			$this->salida .= "    <td>DESCRIPCION</td>";
			$this->salida .= "    <td>&nbsp;</td>";
			$this->salida .= "    </tr>";
			for($i=0;$i<sizeof($Ordenes);$i++){
        $this->salida .= "    <tr class=\"modulo_list_oscuro\">";
				$this->salida .= "    <td>".$Ordenes[$i]['numero_orden_id']."</td>";
				$this->salida .= "    <td>".$Ordenes[$i]['cargo_cups']."</td>";
				$nombreProc=$this->DescripcionProcedimiento($Ordenes[$i]['cargo_cups']);
				$this->salida .= "    <td>".$nombreProc['descripcion']."</td>";
				$actionProgramar=ModuloGetURL('app','Quirurgicos','user','ProcedimientoAProgramacion',array("ordenId"=>$Ordenes[$i]['numero_orden_id'],"cargo"=>$Ordenes[$i]['cargo_cups'],"cirujano"=>$Ordenes[$i]['tercero_id'].'/'.$Ordenes[$i]['tipo_tercero_id']));
				$this->salida .= "    <td width=\"10%\"><a href=\"$actionProgramar\" class=\"link\"><img title=\"Seleccionar Procedimiento\" border=\"0\" src=\"".GetThemePath()."/images/pguardar.png\"></a></td>";
				$this->salida .= "    </tr>";
				$y++;
			}
			$this->salida .= "		 </table><BR>";
			$this->salida .= "    </td></tr>";
			$this->salida .= "		 </table><br>";
		}
    $this->salida .= "    <table border=\"0\" width=\"85%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "       <tr><td><fieldset><legend class=\"field\">DATOS NUEVO PROCEDIMIENTO</legend>";
		$this->salida .= "       <table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
		/*$this->salida .= "			  <tr><td class=\"".$this->SetStyle("tipoProcedimiento")."\">TIPOS PROCEDIMIENTOS:</td><td><select name=\"tipoProcedimiento\" class=\"select\">";
	  $tiposProcedimientos=$this->tiposdeProcedimientos();
	  $this->MostrartiposdeProcedimientos($tiposProcedimientos,'False',$tipoProcedimiento);
	  $this->salida .= "        </select>&nbsp&nbsp&nbsp;";
		$this->salida.= "         </tr>";*/
		$this->salida.= "<tr>";
		$this->salida.= "<td>";
		$this->salida.= "<label class=\"".$this->SetStyle("procedimiento")."\">PROCEDIMIENTO</label>";
		$this->salida.= "</td>";
		$this->salida.= "<td>";
    $this->salida.= "<input type=\"text\" name=\"codigos\" maxlength=\"6\" size=\"6\" class=\"input-text\" value=\"$codigos\" READONLY>&nbsp&nbsp;";
    $this->salida.= "<input type=\"text\" name=\"procedimiento\" maxlength=\"600\" size=\"70\" class=\"input-text\" value=\"$procedimiento\" READONLY>&nbsp&nbsp;";
		$this->salida.= "<input type=\"submit\" name=\"BuscarProcedimiento\" value=\"BUSCAR\" class=\"input-submit\">";
    $opcProcedimientos=$this->ComprobarOpcionesProcedimientosCups();		
    if($opcProcedimientos==1){
      $this->salida.= "&nbsp;&nbsp;&nbsp;<a href=\"javascript:Iniciar();MostrarVentana('d2Container')\"><img border=\"0\" src=\"".GetThemePath()."/images/pcargos.png\" title=\"Procedimientos Opciones\"></a>";            
    }
		$this->salida.= "</td>";
    
    $this->salida.="<tr>";
    $this->salida.="<td>&nbsp;</td>";
    $this->salida.="<td colspan=\"3\" id=\"MostrarProcedimientoOpc\">";
    $procedimientos=$this->BuscarProcedimientosInsertados($ProgramacionId,$codigos);
    if($procedimientos){
      $this->salida.="<table border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10\">";
      $this->salida.="<tr class=\"modulo_table_list_title\">";
      $this->salida.="<td width=\"10%\">CODIGO</td>";
      $this->salida.="<td>PROCEDIMIENTO</td>";
      $this->salida.="<td width=\"5%\">&nbsp;</td>";          
      $this->salida.="</tr>";    
      for($i=0;$i<sizeof($procedimientos);$i++){
        $this->salida.="<tr class=\"modulo_list_claro\">";
        $this->salida.="<td width=\"20%\">".$procedimientos[$i]['procedimiento_opcion']."</td>";
        $this->salida.="<td>".$procedimientos[$i]['descripcion']."</td>";
        $this->salida.="<td align=\"center\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\" onclick=\"javascript:xajax_reqEliminarOpcionesProc('$ProgramacionId','$codigos','".$procedimientos[$i]['procedimiento_opcion']."');\"></td>";                  
        $this->salida.="</tr>";
      }        
      $this->salida.="</table><BR>";
    }
    $this->salida.= "</td></tr>";
    
		$this->salida .= "		<tr height=\"20\"><td class=\"".$this->SetStyle("Responsable")."\">PLAN </td><td><select name=\"Responsable\"  class=\"select\">";
		$responsables=$this->CallMetodoExterno('app','Triage','user','responsables');
		$this->MostrarResponsable($responsables,$PlanUlt);
		$this->salida .= "    </select></td></tr>";
		$this->salida .= "			  <tr><td class=\"".$this->SetStyle("cirujano")."\">CIRUJANO:</td><td><select name=\"cirujano\" class=\"select\">";
	  $profesionales=$this->profesionalesEspecialista();
	  $this->BuscarProfesionlesEspecialistas($profesionales,'False',$cirujano);
	  $this->salida .= "        </select></td></tr>";
		/*if($pediatrico){
		  $this->salida .= "			  <input type=\"hidden\" name=\"pediatrico\" value=\"$pediatrico\">";
			$this->salida .= "			  <tr><td class=\"".$this->SetStyle("pediatra")."\">PEDIATRA</td><td><select name=\"pediatra\" class=\"select\">";
			$profesionales=$this->profesionalesEspecialistaPediatria();
			$this->BuscarProfesionlesEspecialistas($profesionales,'False',$pediatra);
			$this->salida .= "        </select></td></tr>";
		}*/
		/*if($bilateral){
		  $this->salida .= "			  <input type=\"hidden\" name=\"bilateral\" value=\"$bilateral\">";
			$this->salida .= "			  <tr><td class=\"".$this->SetStyle("viabilateral")."\">TIPO VIA</td><td><select name=\"viabilateral\" class=\"select\">";
			$vias=$this->tiposViaBilaterales();
			$this->MostrasSelect($vias,'False',$viabilateral);
			$this->salida .= "        </select></td></tr>";
		}*/
    $this->salida .= "       <tr><td class=\"".$this->SetStyle("observacion")."\">OBSERVACIONES</td><td><textarea name=\"observacion\" cols=\"65\" rows=\"3\" class=\"textarea\">".$_REQUEST['observacion']."</textarea></td></tr>";
		$this->salida .= "       <tr><td align=\"center\" colspan=\"4\"><BR>";
		$this->salida .= "       <input type=\"hidden\" name=\"NumerOrden\" value=\"$numerorden\">";
		if($OrigenFunc==1){
		  $this->salida .= "       <input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"CANCELAR\">";
      $this->salida .= "       <input class=\"input-submit\" type=\"submit\" name=\"Modificar\" value=\"MODIFICAR PROCEDIMIENTO\">";
		}else{
      $this->salida .= "       <input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"GUARDAR\">";
		}
		$this->salida .= "       </td></tr>";
		$this->salida .= "		    </fieldset></td></tr></table>";
		$this->salida .= "			  </table>";
		$this->salida .= "       <table border=\"0\" width=\"85%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "       <tr><td  align=\"center\"><BR><input class=\"input-submit\" type=\"submit\" name=\"Salir\" value=\"VOLVER\">";
		$this->salida .= "       </td></tr>";
		$this->salida .= "			  </table>";
		$this->salida .= "		</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}


  function BuscadorProcedimientos($tipoProcedimiento,$codigos,$procedimiento){

    $this->salida .= ThemeAbrirTabla('BUSCADOR DE PROCEDIMIENTOS');
		$this->salida .= "		<br><br>";
		$action=ModuloGetURL('app','Quirurgicos','user','LlamaBuscadorProcedimientos');
		$this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
    $this->salida .= "    <table border=\"0\" width=\"85%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "		  <tr class=\"modulo_table_list_title\"><td colspan=\"2\">PARAMETROS DE BUSQUEDA</td></tr>";
    $this->salida .= "		  <tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("tipoProcedimiento")."\">TIPOS PROCEDIMIENTOS:</td>";
		$this->salida .= "		  <td><select name=\"tipoProcedimiento\" class=\"select\">";
	  $tiposProcedimientos=$this->tiposdeProcedimientos();
	  $this->MostrartiposdeProcedimientos($tiposProcedimientos,'False',$tipoProcedimiento);
	  $this->salida .= "      </select>&nbsp&nbsp&nbsp;";
		$this->salida.= "       </tr>";
		$this->salida.= "       <tr class=\"modulo_list_claro\">";
		$this->salida.= "       <td class=\"".$this->SetStyle("procedimiento")."\">PROCEDIMIENTO:</td>";
		$this->salida.= "         <td>";
    $this->salida.= "         <input type=\"text\" name=\"codigos\" maxlength=\"6\" size=\"6\" class=\"input-text\" value=\"$codigos\">&nbsp&nbsp;";
    $this->salida.= "         <input type=\"text\" name=\"procedimiento\" maxlength=\"600\" size=\"70\" class=\"input-text\" value=\"$procedimiento\">&nbsp&nbsp;";
		$this->salida.= "         <input type=\"submit\" name=\"BuscarProcedimiento\" value=\"FILTRAR\" class=\"input-submit\">";
		$this->salida.= "         </td>";
		$this->salida.= "       </tr>";
    $this->salida .= "		   </table><BR>";
    $procedimientos=$this->BusquedaProcedimientosQX($tipoProcedimiento,$codigos,$procedimiento);
    if($procedimientos){
      $this->salida .= "    <table border=\"0\" width=\"85%\" align=\"center\" class=\"normal_10\">";
      $this->salida .= "    <tr class=\"modulo_table_list_title\">";
      $this->salida .= "    <td>TIPO PROCEDIMIENTO</td>";
			$this->salida .= "    <td>PROCEDIMIENTO</td>";
			$this->salida .= "    <td>DESCRIPCION</td>";
			$this->salida .= "    <td>&nbsp;</td>";
			$this->salida .= "    </tr>";
			for($i=0;$i<sizeof($procedimientos);$i++){
        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
        $this->salida .= "  <tr class=\"$estilo\">";
				$this->salida .= "  <td width=\"20%\">".$procedimientos[$i]['grupo_tipo_cargo']."</td>";
				$this->salida .= "  <td width=\"15%\">".$procedimientos[$i]['cargo']."</td>";
				$this->salida .= "  <td width=\"60%\">".$procedimientos[$i]['descripcion']."</td>";
				$actionSelect=ModuloGetURL('app','Quirurgicos','user','SeleccionProcedimiento',array("cargo"=>$procedimientos[$i]['cargo'],"descripcion"=>$procedimientos[$i]['descripcion']));
				$this->salida .= "  <td align=\"center\" width=\"5%\"><a href=\"$actionSelect\"><img border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"><a></td>";
				$this->salida .= "  </tr>";
				$y++;
			}
			$this->salida .= "    </table>";
			$this->salida .=$this->RetornarBarra(3);
		}
		$this->salida .= "		</form>";
		$action1=ModuloGetURL('app','Quirurgicos','user','ProcedimientosQuirurgicos');
		$this->salida .= "    <form name=\"forma\" action=\"$action1\" method=\"post\">";
		$this->salida .= "    <BR><table border=\"0\" width=\"85%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "    <tr><td align=\"center\"><input type=\"submit\" name=\"Volver\" value=\"VOLVER\" class=\"input-submit\"></td></tr>";
		$this->salida .= "    </table>";
		$this->salida .= "		</form>";
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
/**
* Funcion que muestra los quirofanos seleccionados y la disponibilidad de ellos
* @return string
*/
	function ReserveEquiposQuirofanos(){
	  $this->salida .= ThemeAbrirTabla('SELECCION QUIROFANO PROGRAMACION CIRUGIA');
		$this->salida .= "<SCRIPT>";
		$this->salida .= "function chequeoTotal(frm,x,valor){";
    $this->salida .= "  if(x==true){";
    $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
    $this->salida .= "      cadena=frm.elements[i].value;";
		$this->salida .= "      vector=cadena.split('/');";
		$this->salida .= "      val=vector[0];";
    $this->salida .= "      if(frm.elements[i].type=='checkbox' && val==valor){";
    $this->salida .= "        frm.elements[i].checked=true";
		$this->salida .= "      }";
		$this->salida .= "    }";
    $this->salida .= "  }else{";
    $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
		$this->salida .= "      cadena=frm.elements[i].value;";
		$this->salida .= "      vector=cadena.split('/');";
		$this->salida .= "      val=vector[0];";
    $this->salida .= "      if(frm.elements[i].type=='checkbox' && val==valor){";
    $this->salida .= "        frm.elements[i].checked=false";
		$this->salida .= "      }";
		$this->salida .= "    }";
		$this->salida .= "  }";
    $this->salida .= "}";
		$this->salida .= "</SCRIPT>";
		$accion=ModuloGetURL('app','Quirurgicos','user','ValidacionSeleccionEquipos');
		$this->salida .= "    <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->Encabezado();
    $this->salida .= "   <table border=\"0\" cellspacing=\"3\" cellpadding=\"3\" width=\"90%\" align=\"center\" class=\"normal_10\">";
		$ProgramacionId=$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO'];
		if($ProgramacionId){
      $quirofanoReservado=$this->consultaReservaQX($ProgramacionId);
      $EquiposMovilReservados=$this->consultaReservaQXEqipos($quirofanoReservado['qx_quirofano_programacion_id']);
			for($l=0;$l<sizeof($EquiposMovilReservados);$l++){
			  $_SESSION['CIRUGIAS']['EQUIPOS'][$EquiposMovilReservados[$l]['tipo_equipo_id']]=1;
			}
		}
    $this->salida .= "   <tr><td width=\"45%\" valign=\"top\">";
		$quirofanos=$this->SeleccionQuirofanosDpto();
		if($quirofanos){
		  $y=0;
      $this->salida .= "   <table border=\"0\" cellspacing=\"3\" cellpadding=\"3\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
			if($quirofanoReservado){
				$this->salida .= "		    <tr class=\"modulo_table_list_title\">";
				$this->salida .= "		    <td align=\"right\" colspan=\"3\">CANCELAR RESERVA DEL QUIROFANO&nbsp&nbsp&nbsp;";
				$actionCancel=ModuloGetURL('app','Quirurgicos','user','CancelarReservaQuirofano',array("reservaQuirofano"=>$quirofanoReservado['qx_quirofano_programacion_id']));
				$this->salida .= "		    <a href=\"$actionCancel\"><img border=\"0\" src=\"".GetThemePath()."/images/checksi.png\"><a></td>";
				$this->salida .= "		    </tr>";
			}
      $this->salida .= "   <tr class=\"modulo_table_list_title\">";
			$this->salida .= "   <td><input name=\"selectodos\" type=\"checkbox\" value=\"1\" onclick=\"chequeoTotal(this.form,this.checked,this.value)\"></td>";
			$this->salida .= "   <td>SALAS QX</td>";
			$this->salida .= "   <td>EQUIPOS DE LA SALA</td>";
			$this->salida .= "   </tr>";
			for($i=0;$i<sizeof($quirofanos);$i++){
			  if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
				$this->salida .= "   <tr>";
				  $quirofanoReservado['quirofano_id'];
					$quirofanos[$i]['quirofano'];
				if($quirofanoReservado['quirofano_id']==$quirofanos[$i]['quirofano']){
          $checkquirofano='checked';
				}else{
				  $checkquirofano='';
				}
				$this->salida .= "   <td class=\"$estilo\" width=\"5%\"><input type=\"checkbox\" name=\"salasCirugia[]\" value=\"1/".$quirofanos[$i]['quirofano']."/".$quirofanos[$i]['abreviatura']."\" $checkquirofano></td>";
				$this->salida .= "   <td class=\"$estilo\" width=\"20%\">".$quirofanos[$i]['abreviatura']."</td>";
				$this->salida .= "   <td class=\"$estilo\">";
				$this->salida .= "   <table border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\" align=\"center\" class=\"normal_10\">";
				$Equipos=$this->EquiposFijosQuirofanos($quirofanos[$i]['quirofano']);
				if($Equipos){
					for($j=0;$j<sizeof($Equipos);$j++){
						$this->salida .= "   <tr class=\"$estilo\"><td>".$Equipos[$j]['descripcion']."</td></tr>";
					}
				}else{
					$this->salida .= "   <tr class=\"$estilo\"><td>&nbsp;</td></tr>";
				}
				$this->salida .= "   </table>";
				$this->salida .= "   </td>";
				$this->salida .= "   </tr>";
				$y++;
			}
		  $this->salida .= "   </table><BR>";
		}
    $this->salida .= "   </td>";
		$this->salida .= "   <td valign=\"top\" width=\"45%\">";
    $EquiposMoviles=$this->EquiposMovilesDpto();
    if($EquiposMoviles){
		$y=0;
		$this->salida .= "   <table border=\"0\" cellspacing=\"3\" cellpadding=\"3\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "   <tr class=\"modulo_table_list_title\">";
		$this->salida .= "   <td><input name=\"selectodosMov\" type=\"checkbox\" value=\"2\" onclick=\"chequeoTotal(this.form,this.checked,this.value)\"></td>";
		$this->salida .= "   <td>EQUIPOS MOVILES</td>";
		$this->salida .= "   <td>DEPARTAMENTO</td>";
		$this->salida .= "   </tr>";
		for($i=0;$i<sizeof($EquiposMoviles);$i++){
      $this->salida .= "   <tr class=\"modulo_table_list_title\">";
			if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
			$this->salida .= "   <tr>";
			if($_SESSION['CIRUGIAS']['EQUIPOS'][$EquiposMoviles[$i]['tipo_equipo_id']]==1){
			  $equipocheckeado='checked';
			}else{
        $equipocheckeado='';
			}
			$this->salida .= "   <td class=\"$estilo\" width=\"5%\"><input type=\"checkbox\" name=\"equiposMobiles[]\" value=\"2/".$EquiposMoviles[$i]['tipo_equipo_id']."\" $equipocheckeado></td>";
			$this->salida .= "   <td class=\"$estilo\">".$EquiposMoviles[$i]['descripcion']."</td>";
			$this->salida .= "   <td class=\"$estilo\">".$EquiposMoviles[$i]['departamento']."</td>";
      $this->salida .= "   </tr>";
		}
		unset($_SESSION['CIRUGIAS']['EQUIPOS']);
    $this->salida .= "   </table>";
		}
		$this->salida .= "   </td></tr>";
		$this->salida .= "   <tr><td colspan=\"2\">";
    $rangoInicio=ModuloGetVar('app', 'Quirurgicos','RangoInicioTurnoQuirofano');
		$rangoDuracion=ModuloGetVar('app', 'Quirurgicos','RangoDuracionTurnoQuirofano');
    $cadena=explode(':',$rangoInicio);
		$HoraIni=$cadena[0];
		$MinutosIni=$cadena[1];
		$RangoFinal=date('H:i',mktime(($HoraIni+$rangoDuracion),$MinutosIni,0,date('m'),date('d'),date('Y')));
		$this->salida .= "   <table border=\"0\" cellspacing=\"3\" cellpadding=\"3\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
    $this->salida .= "   <tr class=\"modulo_table_list_title\"><td colspan=\"2\" align=\"center\">HORARIO QUIROFANOS</td><td>INICIO</td><td>FIN</td></tr>";
		$this->salida .= "   <tr class=\"modulo_list_claro\"><td><input type=\"radio\" name=\"tipoHorario\" value=\"Normal\" checked></td><td>NORMAL</td><td>$rangoInicio</td><td>$RangoFinal</td></tr>";
		$this->salida .= "   <tr class=\"modulo_list_oscuro\"><td><input type=\"radio\" name=\"tipoHorario\" value=\"Completo\"></td><td>COMPLETO</td><td>00:00</td><td>23:00</td></tr>";
    $this->salida .= "   </table>";
		$this->salida .= "   </td></tr>";
    $this->salida .= "   </table><BR>";
		$this->salida .= "   <table border=\"0\" width=\"60%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "   <tr><td align=\"center\"><input type=\"submit\" class=\"input-submit\" name=\"Cancelar\" value=\"VOLVER\">";
		$this->salida .= "   <input type=\"submit\" class=\"input-submit\" name=\"Aceptar\" value=\"SELECCIONAR\"></td></tr>";
		$this->salida .= "   </table>";
		$this->salida .= "		</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
/**
* Funcion que muestra los quirofanos y la disponibilidad de ellos y muestra la posibilidad de cambiar el dia para var los quirofanos
* @return string
*/
	function EleccionFechaReservaQx($SalasCirugia,$EquiposMoviles,$tipoHorario)
     {
		if($SalasCirugia){$_REQUEST['SalasCirugia']=$SalasCirugia;}else{$SalasCirugia=$_REQUEST['SalasCirugia'];}
		if($EquiposMoviles){$_REQUEST['EquiposMoviles']=$EquiposMoviles;}else{$EquiposMoviles=$_REQUEST['EquiposMoviles'];}
		if($tipoHorario){$_REQUEST['tipoHorario']=$tipoHorario;}else{$tipoHorario=$_REQUEST['tipoHorario'];}
		if($_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO'] && empty($_REQUEST['DiaEspe'])){
			$datos=$this->obtenerDatosProgramacionQX($_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']);
			if($datos){
               	$FechaProgramIni=$datos[0]['hora_inicio'];
				$FechaProgramFin=$datos[0]['hora_fin'];
				$Sala=$datos[0]['quirofano_id'];
				$Fecha=$this->FechaStamp($FechaProgramIni);
				$infoCadena = explode ('/', $Fecha);
				$diaIni=$infoCadena[0];
				$mesIni=$infoCadena[1];
				$anoIni=$infoCadena[2];
				$_REQUEST['DiaEspe']=$anoIni.'-'.$mesIni.'-'.$diaIni;
			}
		}
		if($_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']){
			$datosLiberaReserva=$this->traeDatosLiberarReserva($_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']);
			if($datosLiberaReserva['plan_id']){$plan=$datosLiberaReserva['plan_id'];}else{$plan='0';}
				$empresa=$_SESSION['LocalCirugias']['empresa'];
			if($datosLiberaReserva['tipo_tercero_id']){$IdTercero=$datosLiberaReserva['tipo_tercero_id'];}else{$IdTercero='0';}
               if($datosLiberaReserva['tercero_id']){$TerceroId=$datosLiberaReserva['tercero_id'];}else{$TerceroId='0';}
          }else{
               $plan='0';
               $empresa='0';
               $IdTercero='0';
               $TerceroId='0';
          }
		$this->salida .= ThemeAbrirTabla('RESERVAS DEL QUIROFANO Y EQUIPOS PARA LA CIRUGIA');
		$this->salida .= "<SCRIPT>";
		$this->salida .= "function IntervalosCheck(frm,valor,interval){";
		$this->salida .= "  ArrayElements= new Array();";
		$this->salida .= "  ArrayValores= new Array();";
		$this->salida .= "  var j=0;";
		$this->salida .= "  var numElements=0;";
		$this->salida .= "  vector=valor.split('/');";
		$this->salida .= "  quirovalor=vector[0];";
		$this->salida .= "  fechavalor=vector[1];";
		$this->salida .= "  for(i=0;i<frm.elements.length;i++){";
		$this->salida .= "    cadena=frm.elements[i].value;";
		$this->salida .= "    vector=cadena.split('/');";
		$this->salida .= "    quiro=vector[0];";
		$this->salida .= "    fecha=vector[1];";
		$this->salida .= "    if(quiro!=-1){";
		$this->salida .= "    if(quirovalor==quiro){";
		$this->salida .= "      if(frm.elements[i].checked){";
		$this->salida .= "        numElements=numElements+1;";
		$this->salida .= "        ArrayElements[j]=i;";
          $this->salida .= "        ArrayValores[j]=frm.elements[i].value;";
          $this->salida .= "        j++;";
          $this->salida .= "      }";
		$this->salida .= "    }else{";
		$this->salida .= "      frm.elements[i].checked=false";
		$this->salida .= "    }";
          $this->salida .= "    }";
          $this->salida .= "  }";
          $this->salida .= "  var fecha=ArrayValores[0];";
		$this->salida .= "  vector=fecha.split(' ');";
		$this->salida .= "  fechaTot=vector[0];";
		$this->salida .= "  HoraTot=vector[1];";
		$this->salida .= "  vector=HoraTot.split(':');";
		$this->salida .= "  HoraCom=vector[0];";
		$this->salida .= "  MinutosCom=vector[1];";
          $this->salida .= "  for(i=ArrayElements[0];i<=ArrayElements[j-1];i++){";
          $this->salida .= "    cadena=frm.elements[i].value;";
		$this->salida .= "    vector=cadena.split('/');";
		$this->salida .= "    quiro=vector[0];";
		$this->salida .= "    fecha=vector[1];";
		$this->salida .= "    if(quiro==quirovalor){";
		$this->salida .= "      vector=fecha.split(' ');";
		$this->salida .= "      fechaTot=vector[0];";
		$this->salida .= "      HoraTot=vector[1];";
		$this->salida .= "      vector=HoraTot.split(':');";
		$this->salida .= "      HoraAct=vector[0];";
		$this->salida .= "      MinutosAct=vector[1];";
		$this->salida .= "      if(HoraAct == HoraCom && MinutosAct == MinutosCom){";
		$this->salida .= "        frm.elements[i].checked=true;";
		$this->salida .= "      }else{";
		$this->salida .= "        alert ('no es Posible Seleccionar este Intervalo');";
		$this->salida .= "        for(i=0;i<frm.elements.length;i++){";
		$this->salida .= "          cadena=frm.elements[i].value;";
		$this->salida .= "          vector=cadena.split('/');";
		$this->salida .= "          quiro=vector[0];";
          $this->salida .= "          if(quiro!=-1){";
          $this->salida .= "          frm.elements[i].checked=false;";
		$this->salida .= "          }";
		$this->salida .= "        }";
		$this->salida .= "      }";
		$this->salida .= "      MinutosCom=Number(MinutosCom)+Number(interval);";
		$this->salida .= "      if(MinutosCom==60){";
		$this->salida .= "        HoraCom=Number(HoraCom)+Number(1);";
          $this->salida .= "        if(HoraCom==24){";
          $this->salida .= "          HoraCom=00;";
		$this->salida .= "        }";
          $this->salida .= "        MinutosCom=00;";
          $this->salida .= "      }";
		$this->salida .= "    }";
          $this->salida .= "  }";
          $this->salida .= "}";
		$this->salida .= "function LimpiaCheck(frm,x,valorQX,interval){";
          $this->salida .= "  var bandera=1;";
          $this->salida .= "  var HoraCom=0;";
		$this->salida .= "  var MinutosCom=0;";
          $this->salida .= "  if(x==true){";
          $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
          $this->salida .= "      if(frm.elements[i].type=='checkbox'){";
          $this->salida .= "        cadena=frm.elements[i].value;";
		$this->salida .= "        vector=cadena.split('/');";
		$this->salida .= "        quiro=vector[0];";
		$this->salida .= "        fecha=vector[1];";
		$this->salida .= "        if(quiro!=-1){";
		$this->salida .= "        if(quiro==valorQX){";
          $this->salida .= "          if(bandera==1){";
          $this->salida .= "            if(fecha!=undefined){";
		$this->salida .= "              vector=fecha.split(' ');";
		$this->salida .= "              fechaTot=vector[0];";
		$this->salida .= "              HoraTot=vector[1];";
		$this->salida .= "              vectorTmp=HoraTot.split(':');";
		$this->salida .= "              HoraCom=vectorTmp[0];";
		$this->salida .= "              MinutosCom=vectorTmp[1];";
          $this->salida .= "              bandera=0;";
          $this->salida .= "            }";
		$this->salida .= "          }";
		$this->salida .= "          if(fecha!=undefined){";
		$this->salida .= "            vector=fecha.split(' ');";
		$this->salida .= "            fechaTot=vector[0];";
		$this->salida .= "            HoraTot=vector[1];";
		$this->salida .= "            vector=HoraTot.split(':');";
		$this->salida .= "            HoraAct=vector[0];";
		$this->salida .= "            MinutosAct=vector[1];";
		$this->salida .= "            if(HoraAct == HoraCom && MinutosAct == MinutosCom){";
          $this->salida .= "              frm.elements[i].checked=true;";
          $this->salida .= "            }else{";
		$this->salida .= "              alert ('no es Posible Seleccionar este Intervalo');";
		$this->salida .= "              for(i=0;i<frm.elements.length;i++){";
		$this->salida .= "                cadena=frm.elements[i].value;";
		$this->salida .= "                vector=cadena.split('/');";
		$this->salida .= "                quiro=vector[0];";
          $this->salida .= "                if(quiro!=-1){";
          $this->salida .= "                frm.elements[i].checked=false;";
          $this->salida .= "                }";
		$this->salida .= "              }";
		$this->salida .= "            }";
		$this->salida .= "            MinutosCom=Number(MinutosCom)+Number(interval);";
		$this->salida .= "            if(MinutosCom==60){";
		$this->salida .= "              HoraCom=Number(HoraCom)+Number(1);";
          $this->salida .= "              if(HoraCom==24){";
          $this->salida .= "                HoraCom=00;";
		$this->salida .= "              }";
          $this->salida .= "              MinutosCom=00;";
          $this->salida .= "            }";
		$this->salida .= "          }";
		$this->salida .= "        }else{";
		$this->salida .= "          frm.elements[i].checked=false;";
		$this->salida .= "        }";
		$this->salida .= "        }";
		$this->salida .= "      }";
		$this->salida .= "    }";
          $this->salida .= "  }else{";
          $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
          $this->salida .= "      if(frm.elements[i].type=='checkbox'){";
		$this->salida .= "        cadena=frm.elements[i].value;";
		$this->salida .= "        vector=cadena.split('/');";
		$this->salida .= "        quiro=vector[0];";
		$this->salida .= "        fecha=vector[1];";
		$this->salida .= "        if(quiro==valorQX){";
		$this->salida .= "          frm.elements[i].checked=false";
		$this->salida .= "        }";
		$this->salida .= "      }";
		$this->salida .= "    }";
		$this->salida .= "  }";
		$this->salida .= "}";
		$this->salida .= "</SCRIPT>";
		$this->salida .= "   <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr><td class=\"modulo_table_title\" align=\"center\"><b>EMPRESA</b></td>";
		$this->salida .= "      <td class=\"modulo_table_title\" align=\"center\"><b>CENTRO DE UTILIDAD</b></td>";
		$this->salida .= "      <td class=\"modulo_table_title\" align=\"center\"><b>DEPARTAMENTO</b></td></tr>";
		$this->salida .= "      <tr><td class=\"modulo_list_claro\" align=\"center\"><b>".$_SESSION['LocalCirugias']['NombreEmp']."</b></td>";
		$this->salida .= "      <td class=\"modulo_list_claro\" align=\"center\"><b>".$_SESSION['LocalCirugias']['NombreCU']."</b></td>";
		$this->salida .= "      <td class=\"modulo_list_claro\" align=\"center\"><b>".$_SESSION['LocalCirugias']['NombreDpto']."</b></td></tr>";
          $this->salida .= "		</table><BR>";
          $this->salida .= "   <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
          $this->salida .= "   <tr><td>";
		$_REQUEST['DiaEspe'];
		$this->salida.="\n".'<script>'."\n";
		$this->salida.='function year1(t)'."\n";
		$this->salida.='{'."\n";
		$this->salida.='window.location.href="Contenido.php?year="+t.elements[0].value+"&meses="+t.elements[1].value+"';
		foreach($_REQUEST as $v=>$v1)
		{
			if($v!='year' and $v!='meses' and $v!='DiaEspe')
			{
                    if (is_array($v1)) {
                              foreach($v1 as $k2=>$v2) {
                                   if (is_array($v2)) {
                                        foreach($v2 as $k3=>$v3) {
                                             if (is_array($v3)) {
                                                  foreach($v3 as $k4=>$v4) {
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
	                    } else {
	                         $this->salida .= "&$v=$v1";
                    }
			}
		}
		$this->salida.='";'."\n";
		$this->salida.='}'."\n";
		$this->salida.='</script>';
		$this->salida .='<form name="cosa">';
		$this->salida .= "<table border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .='<tr align="center">';
		$this->salida .="<td class=\"label\">AÑO</td><td><select name=\"year\" onchange=\"year1(this.form)\" class=\"select\">";
		if(empty($_REQUEST['year']))
		{
			$a=explode("-",$_SESSION['CITASMES'][0]);
			$year=$_REQUEST['year']=$a[0];
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
			$a=explode("-",$_SESSION['CITASMES'][0]);
			if(empty($a[0]))
			{
				$mes=$_REQUEST['meses']=date("m");
				$year=date("Y");
			}
			else
			{
				$mes=$_REQUEST['meses']=$a[1];
			}
			$this->MesesAgenda(True,$year,$mes);
		}
		else
		{
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
		$this->salida .= "   </table><BR><BR>";
		$rango=ModuloGetVar('app', 'Quirurgicos','RangoTurnosQuirofano');
		$accion=ModuloGetURL('app','Quirurgicos','user','ValidacionReservasQXyEquipo',array("EquiposMoviles"=>$EquiposMoviles,"rango"=>$rango,"SalasCirugia"=>$SalasCirugia,"tipoHorario"=>$tipoHorario));
		$this->salida .= "   <form name=\"forma\" action=\"$accion\" method=\"post\">";
		if(empty($_REQUEST['DiaEspe'])){
          	$FechaEspe=date('Y-m-d');
		}else{
          	$cadena=explode('-',$_REQUEST['DiaEspe']);
			$anoP=$cadena[0];
			$mesP=$cadena[1];
			$diaP=$cadena[2];
          if(date("Y-m-d",mktime(0,0,0,$mesP,$diaP,$anoP))<date("Y-m-d",mktime(0,0,0,date("mes"),date("d"),date("Y")))){
			$FechaEspe=date('Y-m-d');
          }else{
			$FechaEspe=$_REQUEST['DiaEspe'];
			}
		}
		$cadena=explode('-',$FechaEspe);
		$anoProgram=$cadena[0];
		$mesProgram=$cadena[1];
		$diaProgram=$cadena[2];
		$FechaConver=mktime(0,0,0,$mesProgram,$diaProgram,$anoProgram);
		if($SalasCirugia){
               $this->salida .= "   <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
               $colspan=sizeof($SalasCirugia)*2;
		     $this->salida .= "   <tr><td class=\"modulo_table_list_title\" align=\"center\" colspan=\"$colspan\">PROGRAMACIONES DE CIRUGIAS PARA EL DIA&nbsp;&nbsp;".strftime("%A %d de  %B de %Y",$FechaConver)."</td></tr>";
               $this->salida .= "   <tr><td class=\"modulo_table_list_title\" align=\"center\" colspan=\"$colspan\">SALAS QUIRURGICAS</td></tr>";
               $this->salida .= "   <tr>";
		for($i=0;$i<sizeof($SalasCirugia);$i++){
			$cadena=explode('/',$SalasCirugia[$i]);
			$Quiro=$cadena[1];
			$abreviatura=$cadena[2];
			$this->salida .= "   <td align=\"center\" class=\"modulo_table_list_title\">$abreviatura</td>";
			$this->salida .= "   <td width=\"5%\" align=\"center\" class=\"modulo_table_list_title\"><input type=\"checkbox\" name=\"$Quiro\" value=\"$Quiro\" onclick=\"LimpiaCheck(this.form,this.checked,this.value,'$rango')\"></td>";
		}
		$this->salida .= "   </tr>";
		if($tipoHorario=='Completo'){
               $HoraInincio='0';
               $MinutosInicio='0';
			$FechaUni=$this->FechaStamp($FechaEspe);
			$infoCadena = explode ('/', $FechaUni);
			$dia=$infoCadena[0];
			$mes=$infoCadena[1];
			$ano=$infoCadena[2];
			$SumaInicio=date("Y-m-d H:i:s",mktime($HoraInincio,$MinutosInicio,0,$mes,$dia,$ano));
			$SumaFinal=date('Y-m-d H:i:s',mktime(($HoraInincio+24),$MinutosInicio,0,$mes,$dia,$ano));
			$SumaHora=$SumaInicio;
		}else{
			$rangoInicio=ModuloGetVar('app', 'Quirurgicos','RangoInicioTurnoQuirofano');
			$rangoDuracion=ModuloGetVar('app', 'Quirurgicos','RangoDuracionTurnoQuirofano');
			$cadena=explode(':',$rangoInicio);
			$HoraInincio=$cadena[0];
			$MinutosInicio=$cadena[1];
			$FechaUni=$this->FechaStamp($FechaEspe);
			$infoCadena = explode ('/', $FechaUni);
			$dia=$infoCadena[0];
			$mes=$infoCadena[1];
			$ano=$infoCadena[2];
			$SumaInicio=date("Y-m-d H:i:s",mktime($HoraInincio,$MinutosInicio,0,$mes,$dia,$ano));
			$SumaFinal=date('Y-m-d H:i:s',mktime(($HoraInincio+$rangoDuracion),$MinutosInicio,0,$mes,$dia,$ano));
			$SumaHora=$SumaInicio;
		}
		while($SumaHora<$SumaFinal){
          	if($y % 2){$y++;}
			$HoraMosDef=$this->HoraStamp($SumaHora);
			$infoCadena = explode (':',$HoraMosDef);
			$HoraMos=$infoCadena[0];
			$MinutosMos=$infoCadena[1];
			$this->salida .= "   <tr>";
               for($i=0;$i<sizeof($SalasCirugia);$i++){
                    if($y % 2){$estilo='hc_table_submodulo';}else{$estilo='modulo_list_claro';}
                         $cadena=explode('/',$SalasCirugia[$i]);
                         $Quiro=$cadena[1];
                         $abreviatura=$cadena[2];
                         $comprobacion=$this->ComprobarExisReserva($Quiro,$SumaHora,$rango,$plan,$empresa,$IdTercero,$TerceroId);
                         if($_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']){
                              if($Quiro==$Sala){
                                   $comprobacionReserv=$this->ComprobarExisReservaProgram($Quiro,$SumaHora,$rango,$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']);
                              }else{
                                   $comprobacionReserv=0;
                              }
                         }else{
                              $comprobacionReserv=0;
                         }
                         //Horario ocupado.
                         if($comprobacionReserv==1){
                              $this->salida .= " <td align=\"left\" class=\"$estilo\">$HoraMos : $MinutosMos</td>\n";
                              $this->salida .= " <td width=\"5%\" align=\"center\" class=\"$estilo\"><input type=\"checkbox\" name=\"seleccionReserv[]\" checked value=\"$Quiro/$SumaHora\" onclick=\"IntervalosCheck(this.form,this.value,'$rango')\"></td>\n";
                         //Horario por comprobar.
                         }elseif($comprobacion==1){
                              $this->salida .= " <td align=\"left\" class=\"modulo_list_oscuro\">$HoraMos : $MinutosMos</td>\n";
                              $this->salida .= " <td width=\"5%\" align=\"center\" class=\"modulo_list_oscuro\">&nbsp;</td>\n";
                         //Seleccione horario.
                         }else{
                              $this->salida .= " <td align=\"left\" class=\"$estilo\">$HoraMos : $MinutosMos</td>\n";
                              $this->salida .= " <td width=\"5%\" align=\"center\" class=\"$estilo\"><input type=\"checkbox\" name=\"seleccionReserv[]\" value=\"$Quiro/$SumaHora\" onclick=\"IntervalosCheck(this.form,this.value,'$rango')\"></td>\n";
                         }
                         $y++;
                    }
                    $this->salida .= "   </tr>";
                    $Fecha=$this->FechaStamp($SumaHora);
                    $infoCadena = explode ('/', $Fecha);
                    $dia=$infoCadena[0];
                    $mes=$infoCadena[1];
                    $ano=$infoCadena[2];
                    $HoraDef=$this->HoraStamp($SumaHora);
                    $infoCadena = explode (':',$HoraDef);
                    $Hora=$infoCadena[0];
                    $Minutos=$infoCadena[1];
                    $SumaHora=date('Y-m-d H:i:s',mktime($Hora,($Minutos+$rango),0,$mes,$dia,$ano));
               }
               $this->salida .= "   </table><BR>";
          }
          if($EquiposMoviles){
               $this->salida .= "   <BR><table border=\"0\" width=\"85%\" align=\"center\" class=\"modulo_table_list\">";
               //$this->salida .= "   <tr><td class=\"modulo_table_list_title\" align=\"center\" colspan=\"".sizeof($EquiposMoviles)."\">PROGRAMACION DE EQUIPOS QX PARA EL DIA&nbsp;&nbsp;".strftime("%A %d de  %B de %Y",$FechaConver)."</td></tr>";
               $this->salida .= "   <tr>";
               for($i=0;$i<sizeof($EquiposMoviles);$i++){
                    $cadena=explode('/',$EquiposMoviles[$i]);
                    $nombreTipoE=$this->HallarNombreTipoEquipo($cadena[1]);
                    $totalEquiposMoviles=$this->HallartotalEquiposMoviles($cadena[1]);
                    $this->salida .= "   <td align=\"center\" colspan=\"".(sizeof($totalEquiposMoviles)*2)."\" class=\"modulo_table_list_title\">".$nombreTipoE['descripcion']."</td>";
          	}
               $this->salida .= "   </tr>";
               $this->salida .= "   <tr>";
               for($i=0;$i<sizeof($EquiposMoviles);$i++){
                    $cadena=explode('/',$EquiposMoviles[$i]);
                    $totalEquiposMoviles=$this->HallartotalEquiposMoviles($cadena[1]);
                    for($j=0;$j<sizeof($totalEquiposMoviles);$j++){
                         $this->salida .= "   <td align=\"center\" class=\"modulo_table_list_title\">".$totalEquiposMoviles[$j]['equipo_id']." ".$totalEquiposMoviles[$j]['descripcion']." - ".$totalEquiposMoviles[$j]['departamento']."</td>";
                         $this->salida .= "   <td align=\"center\" class=\"modulo_table_list_title\"><input type=\"checkbox\" name=\"equipos[]\" value=\"-1/".$totalEquiposMoviles[$j]['equipo_id']."\"></td>";
                    }
               }
               $this->salida .= "   </tr>";
               $SumaHora=$SumaInicio;
               while($SumaHora<$SumaFinal){
                    if($y % 2){$y++;}
                    $HoraMosDef=$this->HoraStamp($SumaHora);
                    $infoCadena = explode (':',$HoraMosDef);
                    $HoraMos=$infoCadena[0];
                    $MinutosMos=$infoCadena[1];
                    $this->salida .= "   <tr>";
                    for($i=0;$i<sizeof($EquiposMoviles);$i++){
                         $cadena=explode('/',$EquiposMoviles[$i]);
                         $totalEquiposMoviles=$this->HallartotalEquiposMoviles($cadena[1]);
                         for($j=0;$j<sizeof($totalEquiposMoviles);$j++){
                              if($y % 2){$estilo='hc_table_submodulo';}else{$estilo='modulo_list_claro';}
                              $cadena=explode('/',$EquiposMoviles[$i]);
                              $Equipo=$cadena[1];
                              $comprobar=$this->ComprobarExisReservaEquipo($totalEquiposMoviles[$j]['equipo_id'],$SumaHora,$rango);
                              if($comprobar==1){
                                   $this->salida .= " <td align=\"left\" class=\"DiaHoy\">$HoraMos : $MinutosMos</td>\n";
                                   $this->salida .= " <td align=\"left\" class=\"DiaHoy\">&nbsp;</td>\n";
                              }else{
                                   $this->salida .= " <td align=\"left\"  class=\"$estilo\">$HoraMos : $MinutosMos</td>\n";
                                   $this->salida .= " <td align=\"left\"  class=\"$estilo\">&nbsp;</td>\n";
                              }
                              $y++;
                         }
                    }
                    $this->salida .= "   </tr>";
                    $Fecha=$this->FechaStamp($SumaHora);
                    $infoCadena = explode ('/', $Fecha);
                    $dia=$infoCadena[0];
                    $mes=$infoCadena[1];
                    $ano=$infoCadena[2];
                    $HoraDef=$this->HoraStamp($SumaHora);
                    $infoCadena = explode (':',$HoraDef);
                    $Hora=$infoCadena[0];
                    $Minutos=$infoCadena[1];
                    $SumaHora=date('Y-m-d H:i:s',mktime($Hora,($Minutos+$rango),0,$mes,$dia,$ano));
               }
               $this->salida .= " </table><BR>";
          }
		$this->salida .= "   <table border=\"0\" width=\"60%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "   <tr><td align=\"center\">";
		$this->salida .= "   <input type=\"submit\" class=\"input-submit\" name=\"Cancelar\" value=\"CANCELAR\">";
		$this->salida .= "   <input type=\"submit\" class=\"input-submit\" name=\"Aceptar\" value=\"CREAR RESERVA\">";
		$this->salida .= "   </td></tr>";
		$this->salida .= "   </table>";
		$this->salida .= "		</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
     
	/**
	* Funcion que se encarga de separar la fecha del formato timestamp
	* @return array
	*/
	function FechaStamp($fecha){
		if($fecha){
			$fech = strtok ($fecha,"-");
			for($l=0;$l<3;$l++)
			{
				$date[$l]=$fech;
				$fech = strtok ("-");
			}
			return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
		}
	}
	
	
	function FechaStamp2($fecha)
	{
		if($fecha)
		{
			$fech = strtok ($fecha,"-");
			for($l=0;$l<3;$l++)
			{
				$date[$l]=$fech;
				$fech = strtok ("-");
			}
			
			return  ceil($date[2])."-".str_pad(ceil($date[1]),2,0,STR_PAD_LEFT)."-".str_pad(ceil($date[0]),2,0,STR_PAD_LEFT);
		}
	}
	
	
	/**
	* Funcion que se encarga de separar la hora del formato timestamp
	* @return array
	*/
     function HoraStamp($hora){
          
          $hor = strtok ($hora," ");
          for($l=0;$l<4;$l++)
          {
               $time[$l]=$hor;
               $hor = strtok (":");
          }
          return  $time[1].":".$time[2].":".$time[3];
	}
/**
* Funcion que Saca los años para el calendario a partir del año actual
* @return array
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
* Funcion que se encarga de listar los nombres de los profesionales especialistas y visualizarlos
* @return array
* @param array codigos y valores de los profesionales de la base de datos
* @param boolean indicador de selecion de un elemento en el objeto donde se imprimen los profesionales
* @param string elemento seleccionado en el objeto donde se imprimen los profesionales
*/
	function MostrartiposdeProcedimientos($tiposProcedimientos,$Seleccionado='False',$tipoProcedimiento=''){

		switch($Seleccionado){
			case 'False':{
        $this->salida .=" <option value=\"-1\">----------Todos-------</option>";
				for($i=0;$i<sizeof($tiposProcedimientos);$i++){
				  $value=$tiposProcedimientos[$i]['tipo_cargo'].'/'.$tiposProcedimientos[$i]['grupo_tipo_cargo'];
					$titulo=$tiposProcedimientos[$i]['descripcion'];
					if($value==$tipoProcedimiento){
					$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				  }else{
            $this->salida .=" <option value=\"$value\">$titulo</option>";
				  }
			  }
			  break;
		  }case 'True':{
			  for($i=0;$i<sizeof($tiposProcedimientos);$i++){
				  $value=$tiposProcedimientos[$i]['tipo_cargo'].'/'.$tiposProcedimientos[$i]['grupo_tipo_cargo'];
					$titulo=$tiposProcedimientos[$i]['descripcion'];
				  if($value==$tipoProcedimiento){
				    $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				  }
				  $this->salida .=" <option value=\"$value\">$titulo</option>";
			  }
			  break;
		  }
	  }
	}
/**
* Funcion que selecciona las distintas opciones para realizar el filtro de busqueda en la consulta de programaciones
* @return boolean
*/
	function FormaBuscadorProgramCirugias(){
		SessionDelVar("TipoBusqueda");
		$this->salida .= ThemeAbrirTabla('BUSQUEDA PARA LA CONSULTA DE PROGRAMACIONES QUIRURGICAS');
		$this->salida .= "<script>";
		$this->salida .= "  function chequearadio(frm,valor){";
		$this->salida .= "    if(valor==true){";
		$this->salida .= "      frm.elements[0].checked=true;";
		$this->salida .= "    }";
		$this->salida .= "  }";
		$this->salida .= "  function chequearadioUno(frm,valor){";
		$this->salida .= "    if(valor==true){";
		$this->salida .= "      frm.elements[10].checked=true;";
		$this->salida .= "    }";
		$this->salida .= "  }";
		$this->salida .= "</script>";
		$accion=ModuloGetURL('app','Quirurgicos','user','seleccionCriteriosConsultaProgram');
		$this->salida .= "    <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->Encabezado();
		$this->salida .= "<table border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "<tr><td colspan=\"4\" align=\"center\">";
		$this->salida .=  $this->SetStyle("MensajeError");
		$this->salida .= "</td></tr>";
		$this->salida .= "<tr class=\"modulo_list_oscuro\"><td>";
		$this->salida .= "   <BR><BR><table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10N\">";
		$this->salida .= "   <tr><td class=\"modulo_table_list_title\">";
		$this->salida .= "   <input type=\"radio\" name=\"tipoBusqueda\" value=\"1\"></td><td colspan=\"4\" class=\"modulo_table_list_title\">FILTROS DE BUSQUEDA</td></tr>";
		$this->salida .= "   <tr>";
		$this->salida .= "   <td width=\"8%\" class=\"modulo_list_claro\"></td><td width=\"100%\" nowrap colspan=\"2\" class=\"modulo_table_list_title\">DATOS CIRUGIA</td>";
		$this->salida .= "   <td width=\"\" nowrap colspan=\"2\" class=\"modulo_table_list_title\">DATOS PACIENTE</td>";
          $this->salida .= "   </tr>";
          $this->salida .= "   <tr class=\"modulo_list_claro\">";
          $this->salida .= "   <td width=\"8%\"></td><td align=\"center\"><input type=\"checkbox\" onclick=\"chequearadio(this.form,this.checked)\" name=\"cirujanoQX\"></td><td>CIRUJANO</td>";
          $this->salida .= "   <td align=\"center\"><input type=\"checkbox\" onclick=\"chequearadio(this.form,this.checked)\" name=\"pacienteid\"></td><td>IDENTIFICACION</td>";
          $this->salida .= "   </tr>";
		$this->salida .= "   <tr class=\"modulo_list_claro\">";
          $this->salida .= "   <td width=\"8%\"></td><td align=\"center\"><input type=\"checkbox\" onclick=\"chequearadio(this.form,this.checked)\" name=\"ayudanteQX\"></td><td>AYUDANTE</td>";
          $this->salida .= "   <td align=\"center\"><input type=\"checkbox\" onclick=\"chequearadio(this.form,this.checked)\" name=\"nompacientes\"></td><td>NOMBRES Y APELLIDOS</td>";
          $this->salida .= "   </tr>";
		$this->salida .= "   <tr class=\"modulo_list_claro\">";
          $this->salida .= "   <td width=\"8%\"></td><td align=\"center\"><input type=\"checkbox\" onclick=\"chequearadio(this.form,this.checked)\" name=\"quirofanoQX\"></td><td>QUIROFANO</td>";
          $this->salida .= "   <td>&nbsp;</td><td>&nbsp;</td>";
          $this->salida .= "   </tr>";
          $this->salida .= "   <tr class=\"modulo_list_claro\">";
          $this->salida .= "   <td width=\"8%\"></td><td align=\"center\"><input type=\"checkbox\" onclick=\"chequearadio(this.form,this.checked)\" name=\"fecha\"></td><td>PERIODO</td>";
          $this->salida .= "   <td>&nbsp;</td><td>&nbsp;</td>";
          $this->salida .= "   </tr>";
		$this->salida .= "   <tr class=\"modulo_list_claro\">";
          $this->salida .= "   <td width=\"8%\"></td><td align=\"center\"><input type=\"checkbox\" onclick=\"chequearadio(this.form,this.checked)\" name=\"procedimientoQX\"></td><td>PROCEDIMIENTO</td>";
          $this->salida .= "   <td>&nbsp;</td><td>&nbsp;</td>";
          $this->salida .= "   </tr>";
          $this->salida .= "   <tr class=\"modulo_list_claro\">";
          $this->salida .= "   <td width=\"8%\"></td><td align=\"center\"><input type=\"checkbox\" onclick=\"chequearadio(this.form,this.checked)\" name=\"anestesiologoQX\"></td><td>ANESTESIOLOGO</td>";
          $this->salida .= "   <td>&nbsp;</td><td>&nbsp;</td>";
          $this->salida .= "   </tr>";
		$this->salida .= "	 </table>";
		$this->salida .= "   <table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10N\">";
		$this->salida .= "   <tr><td width=\"8%\" class=\"modulo_table_list_title\">";
		$this->salida .= "   <input type=\"radio\" name=\"tipoBusqueda\" value=\"2\" checked></td><td class=\"modulo_table_list_title\">PROGRAMACIONES DIARIAS</td></tr>";
		$this->salida .= "   <tr class=\"modulo_list_claro\"><td colspan=\"2\">&nbsp</td></tr>";
		$this->salida .= "	 </table>";
		$this->salida .= "   <table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10N\">";
		$this->salida .= "   <tr><td width=\"8%\" class=\"modulo_table_list_title\">";
		$this->salida .= "   <input type=\"radio\" name=\"tipoBusqueda\" value=\"3\"></td><td class=\"modulo_table_list_title\">TODAS LAS PROGRAMACIONES</td></tr>";
		$this->salida .= "   <tr class=\"modulo_list_claro\"><td colspan=\"2\">&nbsp</td></tr>";
		$this->salida .= "   <tr><td width=\"8%\" class=\"modulo_table_list_title\">";
		$this->salida .= "   <input type=\"radio\" name=\"tipoBusqueda\" value=\"4\"></td><td class=\"modulo_table_list_title\">DISPONIBILIDAD QUIROFANO</td></tr>";
		$this->salida .= "   <tr class=\"modulo_list_claro\"><td align=\"center\" colspan=\"2\"><input type=\"radio\" name=\"tipoTiempo\" value=\"2\" onclick=\"chequearadioUno(this.form,this.checked)\" checked>&nbsp&nbsp;TIEMPO NORMAL&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp;";
		$this->salida .= "   <input type=\"radio\" name=\"tipoTiempo\" value=\"1\" onclick=\"chequearadioUno(this.form,this.value)\">&nbsp&nbsp;TIEMPO COMPLETO</td></tr>";
		$this->salida .= "	 </table><BR><BR>";
		$this->salida .= "</td></tr>";
		if($_SESSION['QUIRURGICOS']['consulta']==1){
               $this->salida .= "<tr class=\"modulo_list_claro\"><td>";
               $this->salida .= "   <BR><BR><table border=\"0\" width=\"75%\" align=\"center\" class=\"normal_10N\">";
               $this->salida .= "   <tr><td colspan=\"6\" width=\"8%\" class=\"modulo_table_list_title\">ESTADO DE LAS PROGRAMACIONES</td></tr>";
               $this->salida .= "   <tr class=\"modulo_list_oscuro\">";
               $this->salida .= "   <td width=\"8%\" align=\"center\"><input type=\"checkbox\" name=\"activa\" checked></td><td>ACTIVA</td>";
               $this->salida .= "   <td width=\"8%\" align=\"center\"><input type=\"checkbox\" name=\"ejecutada\"></td><td>EJECUTADA</td>";
               $this->salida .= "   <td width=\"8%\" align=\"center\"><input type=\"checkbox\" name=\"cancelada\"></td><td>CANCELADA</td></tr>";
               $this->salida .= "	  </table><BR>";
               $this->salida .= "</td></tr>";
		}
		$this->salida .= "</table><BR>";
		$this->salida .= "   <table border=\"0\" width=\"60%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "   <tr><td align=\"center\">";
		$this->salida .= "   <input type=\"submit\" class=\"input-submit\" name=\"salir\" value=\"VOLVER\">";
		$this->salida .= "   <input type=\"submit\" class=\"input-submit\" name=\"seleccionar\" value=\"SELECCIONAR\">";
		$this->salida .= "   </td></tr>";
		$this->salida .= "   </table>";
		$this->salida .= "	</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
/**
* Funcion que visualiza la forma donde se piden los datos de la programacion del usuario segun el filtro de busqueda
* @return boolean
*/
	function FormaDatosConsultaPrograma($cirujanoQX,$pacienteid,$ayudanteQX,$nompacientes,
	  $quirofanoQX,$fecha,$procedimientoQX,$cancelada,$ejecutada,$activa,$tipoBusqueda,$cirujano,$TipoDocumento,
		$Documento,$quirofano,$FechaInicial,$FechaFinal,$codigos,$anestesiologoQX,$anestesiologo){
    $this->salida .= ThemeAbrirTabla('BUSQUEDA PARA LA CONSULTA DE PROGRAMACIONES QUIRURGICAS');
		$RUTA = $_ROOT ."classes/classbuscador/buscador.php?forma=forma&tipo=";
		$this->salida.="<script language='javascript'>\n";
		$this->salida.="  function abrirVentana(nom,frm){\n";
		$this->salida.="    var nombre=\"PROCEDIMIENTOS QUIRURGICOS\";\n";
    $this->salida.="    var valortipo=frm.tipoProcedimiento.value;";
		$this->salida.="    if(nom=='buscar2'){\n";
		$this->salida.="      var tipo=\"procedimientosQX\";\n";
		$this->salida.="      var alias=\"codigos\";\n";
		$this->salida.="    }\n";
		$this->salida.="    var str =\"width=450,height=250,resizable=no,status=no,scrollbars=yes\";\n";
		$this->salida.="    var url2 ='$RUTA'+tipo+'&alias='+alias+'&key=cargo,descripcion'+'&tipoProcedimiento='+valortipo;\n";
		$this->salida.="    rem = window.open(url2, nombre, str);}\n";
		$this->salida.="</script>\n";
		$accion=ModuloGetURL('app','Quirurgicos','user','DatosCriteriosConsultaProgram');
		$this->salida .= "    <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->Encabezado();
		$this->salida .= "<input type=\"hidden\" name=\"activa\" value=\"$activa\">";
		$this->salida .= "<input type=\"hidden\" name=\"cancelada\" value=\"$cancelada\">";
		$this->salida .= "<input type=\"hidden\" name=\"ejecutada\" value=\"$ejecutada\">";
		$this->salida .= "<input type=\"hidden\" name=\"tipoBusqueda\" value=\"$tipoBusqueda\">";
		$this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_list_oscuro\">";
		$this->salida .= "<tr><td colspan=\"4\" class=\"modulo_table_list_title\">PARAMETROS DE BUSQUEDA</td></tr>";
		$this->salida .= "<tr><td colspan=\"4\" align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "</td></tr>";
    $this->salida .= "<tr><td colspan=\"4\">";
    $this->salida .= "   <BR><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\" cellpadding=\"3\" cellspacing=\"3\">";
		if($cirujanoQX){
      $this->salida .= "  <tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("cirujano")."\">CIRUJANO</td>";
			$this->salida .= "  <td colspan=\"3\"><select name=\"cirujano\" class=\"select\">\n";
			$profesionales=$this->profesionalesEspecialista();
			$this->BuscarProfesionlesEspecialistas($profesionales,'False',$cirujano);
			$this->salida .= "  </select></td>\n";
			$this->salida .= "  </tr>";
      $this->salida .= "  <input type=\"hidden\" name=\"cirujanoQX\" value=\"$cirujanoQX\">";
		}
    if($anestesiologoQX){
      $this->salida .= "  <tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("anestesiologo")."\">ANESTESIOLOGO</td>";
      $this->salida .= "  <td colspan=\"3\"><select name=\"anestesiologo\" class=\"select\">\n";
      $profesionales=$this->profesionalesEspecialistaAnestecistas();
      $this->BuscarProfesionlesEspecialistas($profesionales,'False',$anestesiologo);
      $this->salida .= "  </select></td>\n";
      $this->salida .= "  </tr>";
      $this->salida .= "  <input type=\"hidden\" name=\"anestesiologoQX\" value=\"$anestesiologoQX\">";
    }
		if($pacienteid){
      $this->salida .= "  <tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("TipoDocumento")."\">TIPO DOCUMENTO: </td><td><select name=\"TipoDocumento\" class=\"select\">";
			$tipo_id=$this->tipo_id_paciente();
			$this->Mostrar($tipo_id,'False',$TipoId);
			$this->salida .= "  </select></td>";
			$this->salida .= "	<td class=\"".$this->SetStyle("Documento")."\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" value=\"$Documento\"></td></tr>";
			$this->salida .= "  <input type=\"hidden\" name=\"pacienteid\" value=\"$pacienteid\">";
		}
		if($ayudanteQX){
      $this->salida .= "				<tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("ayudante")."\">AYUDANTE:</td><td colspan=\"3\"><select name=\"ayudante\" class=\"select\">";
			$profesionales=$this->profesionalesAyudantes();
			$this->BuscarProfesionlesEspecialistas($profesionales,'False',$ayudante);
			$this->salida .= "        </select></td></tr>";
			$this->salida .= "  <input type=\"hidden\" name=\"ayudanteQX\" value=\"$ayudanteQX\">";
		}
		if($quirofanoQX){
      $this->salida .= "	 <tr class=\"modulo_list_claro\">";
      $this->salida .= "	 <td class=\"".$this->SetStyle("quirofano")."\">No. QUIROFANO: </td><td colspan=\"3\"><select name=\"quirofano\" class=\"select\">";
			$quirofanos=$this->TotalQuirofanos();
	    $this->MostrasSelect($quirofanos,'False',$quirofano);
			$this->salida .= "   </select></td></tr>";
			$this->salida .= "  <input type=\"hidden\" name=\"quirofanoQX\" value=\"$quirofanoQX\">";
		}
		if($nompacientes){
      $this->salida .= "		<tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("nombres")."\">NOMBRES</td><td><input type=\"text\" class=\"input-text\" name=\"nombres\" size=\"32\" maxlength=\"32\"></td>";
      $this->salida .= "		<td class=\"".$this->SetStyle("apellidos")."\">APELLIDOS</td><td><input type=\"text\" class=\"input-text\" name=\"apellidos\" size=\"32\" maxlength=\"32\"></td></tr>";
			$this->salida .= "  <input type=\"hidden\" name=\"nompacientes\" value=\"$nompacientes\">";
		}
		if($fecha){
      $this->salida .= "		<tr class=\"modulo_list_claro\">";
		  $this->salida .= "	  <td class=\"".$this->SetStyle("FechaInicial")."\">FECHA INICIAL: </td>";
			$this->salida .= "	  <td><input type=\"text\" name=\"FechaInicial\" value=\"$FechaInicial\" class=\"input-text\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\"><input type=\"hidden\" value=\"$FechaNacimientoCalculada\" name=\"FechaNacimientoCalculada\">";
			$this->salida .= "	  &nbsp&nbsp&nbsp;".ReturnOpenCalendario('forma','FechaInicial','/')."</td>";
		  $this->salida .= "	  <td class=\"".$this->SetStyle("FechaFinal")."\">FECHA FINAL: </td>";
      $this->salida .= "	  <td><input type=\"text\" name=\"FechaFinal\" value=\"$FechaFinal\" class=\"input-text\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\"><input type=\"hidden\" value=\"$FechaNacimientoCalculada\" name=\"FechaNacimientoCalculada\">";
			$this->salida .= "	  &nbsp&nbsp&nbsp;".ReturnOpenCalendario('forma','FechaFinal','/')."</td>";
		  $this->salida .= "		</tr>";
			$this->salida .= "  <input type=\"hidden\" name=\"fecha\" value=\"$fecha\">";
		}
		if($procedimientoQX){
		  $this->salida .= "   <tr class=\"modulo_list_claro\"><td colspan=\"4\">";
		  $this->salida .= "      <table width=\"100%\" border=\"0\" align=\"center\">";
		  $this->salida .="       <tr><td class=\"".$this->SetStyle("tipoProcedimiento")."\">TIPO PROCEDIMIENTOS:</td><td colspan=\"3\"><select name=\"tipoProcedimiento\" class=\"select\">";
			$tiposProcedimientos=$this->tiposdeProcedimientos();
			$this->MostrartiposdeProcedimientos($tiposProcedimientos,'False',$tipoProcedimiento);
			$this->salida .="       </select>&nbsp&nbsp&nbsp;";
			$this->salida.= "       <input type=\"button\" name=\"buscar2\" value=\"BUSCAR\" onclick=abrirVentana(this.name,this.form) class=\"input-submit\"></td>";
			$this->salida.= "       </tr>";
      $this->salida.= "       <tr class=\"modulo_list_claro\">";
			$this->salida.= "       <td>";
			$this->salida.= "       <label class=\"".$this->SetStyle("procedimiento")."\">PROCEDIMIENTO</label>";
			$this->salida.= "       </td>";
			$this->salida.= "       <td colspan=\"3\">";
			$this->salida.= "       <input type=\"text\" name=\"codigos\" maxlength=\"6\" size=\"6\" class=\"input-text\" value=\"$codigos\" READONLY>&nbsp&nbsp;";
			$this->salida.= "       <input type=\"text\" name=\"procedimiento\" maxlength=\"600\" size=\"70\" class=\"input-text\" value=\"$procedimiento\" READONLY>";
			$this->salida.= "       </td>";
			$this->salida.= "       </tr>";
			$this->salida .= "      </table>";
			$this->salida .= "      </td></tr>";
			$this->salida .= "      <input type=\"hidden\" name=\"procedimientoQX\" value=\"$procedimientoQX\">";
		}
		$this->salida .= "    </table><BR>";
		$this->salida.= " </td></tr>";
    $this->salida .= "</table><BR>";
		$this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
    $this->salida .= "<tr><td align=\"center\">";
		$this->salida .= "<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"BUSCAR\">";
		$this->salida .= "<input type=\"submit\" class=\"input-submit\" name=\"regresar\" value=\"VOLVER\">";
		$this->salida .= "</td></tr>";
    $this->salida .= "</table>";
		$this->salida .= "</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
/**
* Funcion que muestra los datos de las programaciones que pertenezcan al dia seleccionado
* @return boolean
*/
	function FormaReportesProgramaciones($query,$OrigenFun,$cancelada,$ejecutada,$activa){
	  if($OrigenFun!=1){
	    if($query){$_SESSION['QUIRURGICOS']['query']=$query;}else{$query=$_SESSION['QUIRURGICOS']['query'];}
		}
		if(!$_REQUEST['DiaEspe']){
      if($_SESSION['QUIRURGICOS']['DiaEspe']){
        $_REQUEST['DiaEspe']=$_SESSION['QUIRURGICOS']['DiaEspe'];
			}else{
        $_REQUEST['DiaEspe']=date("Y-m-d");
        $_SESSION['QUIRURGICOS']['DiaEspe']=$_REQUEST['DiaEspe'];
			}
		}else{
      $_SESSION['QUIRURGICOS']['DiaEspe']=$_REQUEST['DiaEspe'];
		}
	  $this->salida .= ThemeAbrirTabla('REPORTE DE PROGRAMACIONES');
		$this->Encabezado();
    if($OrigenFun==1){
      $this->salida .= "   <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "   <tr><td>";
			$_REQUEST['DiaEspe'];
			$this->salida.="\n".'<script>'."\n";
			$this->salida.='function year1(t)'."\n";
			$this->salida.='{'."\n";
			$this->salida.='window.location.href="Contenido.php?year="+t.elements[0].value+"&meses="+t.elements[1].value+"';
			foreach($_REQUEST as $v=>$v1)
			{
				if($v!='year' and $v!='meses' and $v!='DiaEspe')
				{
					if (is_array($v1)) {
							foreach($v1 as $k2=>$v2) {
								if (is_array($v2)) {
									foreach($v2 as $k3=>$v3) {
										if (is_array($v3)) {
											foreach($v3 as $k4=>$v4) {
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
						} else {
							$this->salida .= "&$v=$v1";
						}
				}
			}
			$this->salida.='";'."\n";
			$this->salida.='}'."\n";
			$this->salida.='</script>';
			$this->salida .='<form name="cosa">';
			$this->salida .= "<table border=\"0\" width=\"60%\" align=\"center\">";
			$this->salida .='<tr align="center">';
			$this->salida .="<td class=\"label\">AÑO</td><td><select name=\"year\" onchange=\"year1(this.form)\" class=\"select\">";
			if(empty($_REQUEST['year'])){
				$a=explode("-",$_SESSION['CITASMES'][0]);
				$year=$_REQUEST['year']=$a[0];
				$this->AnosAgenda(True,$_REQUEST['year']);
			}else{
				$this->AnosAgenda(true,$_REQUEST['year']);
				$year=$_REQUEST['year'];
			}
			$this->salida .= "</select></td>";
			$this->salida .="<td class=\"label\">MES</td><td><select name=\"mes\" onchange=\"year1(this.form)\" class=\"select\">";
			if(empty($_REQUEST['meses'])){
				$a=explode("-",$_SESSION['CITASMES'][0]);
				if(empty($a[0])){
					$mes=$_REQUEST['meses']=date("m");
					$year=date("Y");
				}else{
					$mes=$_REQUEST['meses']=$a[1];
				}
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
			//$this->salida .= CalendarioTodos();
			$this->salida .= "   </td></tr>";
			$this->salida .= "   </table><BR><BR>";
		}
		$accion=ModuloGetURL('app','Quirurgicos','user','ConsultadeProgramaciones');
		$this->salida .= "    <form name=\"forma\" action=\"$accion\" method=\"post\">";

		if($OrigenFun!=1){
		  $programaciones=$this->barraEstadoParaProgramaciones($query);
		}else{
			$_SESSION['QUIRURGICOS']['cancelada']=$cancelada;
			$_SESSION['QUIRURGICOS']['ejecutada']=$ejecutada;
			$_SESSION['QUIRURGICOS']['activa']=$activa;
      $programaciones=$this->ConsultaProgramacionesDiarias($_REQUEST['DiaEspe'],$cancelada,$ejecutada,$activa);
		}
		if($programaciones){
		  $this->salida .= "    <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
			$cadena=explode('-',$_REQUEST['DiaEspe']);
			$anoProgram=$cadena[0];
			$mesProgram=$cadena[1];
			$diaProgram=$cadena[2];
			$FechaConver=mktime(0,0,0,$mesProgram,$diaProgram,$anoProgram);
			$this->salida .= "    <tr class=\"modulo_table_list_title\" align=\"center\"><td colspan=\"7\">PROGRAMACION DE CIRUGIAS EN EL DIA&nbsp;&nbsp;".strftime("%A %d de  %B de %Y",$FechaConver)."</td></tr>";
      $this->salida .= "    <tr class=\"modulo_table_list_title\" align=\"center\">";
		  $this->salida .= "		  <td>PROGRAMACION</td>";
			$this->salida .= "		  <td>FECHA</td>";
			$this->salida .= "		  <td>DURACION</td>";
      $this->salida .= "	  	<td>CIRUJANO</td>";
		  $this->salida .= "     <td>PACIENTE</td>";
		  $this->salida .= "     <td>QUIROFANO</td>";
			$this->salida .= "     <td>VER DETALLE</td>";
		  $this->salida .= "    </tr>";
			for($i=0;$i<sizeof($programaciones);$i++){
			  if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
			  $this->salida .= "		  <tr class=\"$estilo\">";
        $this->salida .= "		  <td>".$programaciones[$i]['programacion_id']."</td>";
				$this->salida .= "		  <td>".$programaciones[$i]['hora_inicio']."</td>";
				$Fecha=$this->FechaStamp($programaciones[$i]['hora_inicio']);
				$infoCadena = explode ('/',$Fecha);$diaIni=$infoCadena[0];$mesIni=$infoCadena[1];$anoIni=$infoCadena[2];
				$intervalo=$this->HoraStamp($programaciones[$i]['hora_inicio']);
				$infoCadena = explode (':', $intervalo);$HoraIni=$infoCadena[0];$MinutosIni=$infoCadena[1];
        $Fecha=$this->FechaStamp($programaciones[$i]['hora_fin']);
				$infoCadena = explode ('/',$Fecha);$diaFin=$infoCadena[0];$mesFin=$infoCadena[1];$anoFin=$infoCadena[2];
				$intervalo=$this->HoraStamp($programaciones[$i]['hora_fin']);
				$infoCadena = explode(':', $intervalo);$HoraFin=$infoCadena[0];$MinutosFin=$infoCadena[1];
				$DuracionMin=(mktime($HoraFin,$MinutosFin,0,$mesFin,$diaFin,$anoFin)-mktime($HoraIni,$MinutosIni,0,$mesIni,$diaIni,$anoIni))/60;
				$HorasDura=(int)($DuracionMin/60);
				$HorasDura=str_pad($HorasDura,2,0,STR_PAD_LEFT);
				$MinutosDura=($DuracionMin%60);
				$MinutosDura=str_pad($MinutosDura,2,0,STR_PAD_LEFT);
				$Duracion=$HorasDura.':'.$MinutosDura;
				$this->salida .= "	  	<td>$Duracion</td>";
				$nombreCir=$this->NombreProfesional($programaciones[$i]['cirujano_id'],$programaciones[$i]['tipo_id_cirujano']);
				$this->salida .= "	  	<td>".$nombreCir['nombre']."</td>";
				$Nombres=$this->BuscarNombresPaciente($programaciones[$i]['tipo_id_paciente'],$programaciones[$i]['paciente_id']);
			  $Apellidos=$this->BuscarApellidosPaciente($programaciones[$i]['tipo_id_paciente'],$programaciones[$i]['paciente_id']);
				$this->salida .= "     <td>$Nombres $Apellidos</td>";
				$sala=$this->DescripcionQuirofano($programaciones[$i]['quirofano_id']);
				$this->salida .= "     <td>".$sala['descripcion']."</td>";
        if((empty($programaciones[$i]['hora_inicio']))||($programaciones[$i]['hora_inicio']>=date("Y-m-d"))){
          $mayorFecha=1;
				}else{
          $mayorFecha=0;
				}
				$actionVer=ModuloGetURL('app','Quirurgicos','user','consultarDetallePrograma',array("ProgramacionId"=>$programaciones[$i]['programacion_id'],"mayorfecha"=>$mayorFecha));
				$this->salida .= "     <td align=\"center\"><a href=\"$actionVer\"><img border=\"0\" src=\"".GetThemePath()."/images/pconsultar.png\"></a></td>";
				$this->salida .= "		  </tr>";
			}
			$this->salida .= "    </table>";
			$this->salida .=$this->RetornarBarra();
		}else{
      $this->salida .= "    <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
			$this->salida .= "    <tr><td align=\"center\" class=\"label_error\">NO EXISTEN PROGRAMACIONES CON ESTOS PARAMETROS</td></tr>";
      $this->salida .= "    </table>";
		}
    $this->salida .= "    <BR><table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "    <tr><td align=\"center\"><input type=\"submit\" class=\"input-submit\" name=\"Menu\" value=\"MENU\">";
		$this->salida .= "    <input type=\"submit\" class=\"input-submit\" name=\"Regresar\" value=\"VOLVER\"></td></tr>";
		$this->salida .= "    </table>";
		$this->salida .= "    </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
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
      $accion=ModuloGetURL('app','Quirurgicos','user','LlamaReserva_Insumos_qx',array('conteo'=>$this->conteo,"paso"=>$_REQUEST['paso'],"Of"=>$_REQUEST['Of'],"grupo"=>$_REQUEST['grupo'],"clasePr"=>$_REQUEST['clasePr'],"subclase"=>$_REQUEST['subclase'],"codigoPro"=>$_REQUEST['codigoPro'],"descripcionPro"=>$_REQUEST['descripcionPro']));
		}elseif($origen==2){
      $accion=ModuloGetURL('app','Quirurgicos','user','SeleccionDiagnostico',array('conteo'=>$this->conteo,"paso"=>$_REQUEST['paso'],"Of"=>$_REQUEST['Of'],
			"TipoDocumento"=>$_REQUEST['TipoDocumento'],"Documento"=>$_REQUEST['Documento'],"Responsable"=>$_REQUEST['Responsable'],"cirujano"=>$_REQUEST['cirujano'],"codigo"=>$_REQUEST['codigo'],"cargo"=>$_REQUEST['cargo'],"procedimientoBus"=>$_REQUEST['procedimientoBus'],"codigoBus"=>$_REQUEST['codigoBus']));
		}elseif($origen==3){
      $accion=ModuloGetURL('app','Quirurgicos','user','LlamaBuscadorProcedimientos',array('conteo'=>$this->conteo,"paso"=>$_REQUEST['paso'],"Of"=>$_REQUEST['Of'],"tipoProcedimiento"=>$_REQUEST['tipoProcedimiento'],"codigos"=>$_REQUEST['codigos'],"procedimiento"=>$_REQUEST['procedimiento']));
		}else{
		  $accion=ModuloGetURL('app','Quirurgicos','user','FormaReportesProgramaciones',array('conteo'=>$this->conteo));
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
/**
* Funcion que pide el tipo y numero que identifica al profesional
* @return boolean
*/
	function IdentificacionNuevoProfesional(){

		$this->salida .= ThemeAbrirTabla('DATOS DEL PROFESIONAL');
		$this->Encabezado();
		$accion=ModuloGetURL('app','Quirurgicos','user','LlamaAdicionProfesional');
		$this->salida .= "    <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "	   <table width=\"40%\" border=\"0\" class=\"normal_10\" align=\"center\">";
		$this->salida .= "    <tr><td>";
		$this->salida .= "    <fieldset><legend class=\"field\">IDENTIFICACION</legend>";
		$this->salida .= "    <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
    $this->salida .= "		<tr><td></tr></td>";
		$this->salida .= "		<tr><td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"TipoDocumento\" class=\"select\">";
		$tipo_id=$this->tipo_id_terceros();
		$this->Mostrar($tipo_id,'False',$TipoId);
		$this->salida .= "    </select></td></tr>";
		$this->salida .= "		<tr><td class=\"".$this->SetStyle("Documento")."\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" value=\"$Documento\"></td></tr>";
		$this->salida .= "		<tr><td></tr></td>";
		$this->salida .= "		<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" class=\"input-submit\" name=\"Aceptar\" value=\"ACEPTAR\"></td></tr>";
		$this->salida .= "		</table>";
		$this->salida .= "		</fieldset></td></tr>";
		$this->salida .= "    </table>";
    $this->salida .= "    <form>";
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
		/*
	* Esta funcion realiza la busqueda de las ordenes de servicio según filtros como numero de orden
	* documento y plan
	* @return boolean
	*/
	function FormaMetodoBuscar($Busqueda,$arr,$f)
	{
			$this->salida.= ThemeAbrirTabla('ORDEN DE SERVICIOS MEDICOS');
			$this->Encabezado();
			if(!$Busqueda){ $Busqueda=1; }
			$accion=ModuloGetURL('app','Quirurgicos','user','BuscarOrden');
			$this->salida .= "  <table border=\"0\" width=\"98%\" align=\"center\">";
			$this->salida .= "		<tr>";
			$this->salida .= "		   <td width=\"62%\" >";
			$this->salida .= "      <br><table border=\"0\" width=\"90%\" align=\"center\">";
			$this->salida .= "          <tr><td><fieldset><legend class=\"field\">BUSCAR CUENTA</legend>";
			$this->salida .= "			      <table width=\"95%\" align=\"center\" border=\"0\">";
			$this->salida .= "             <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
			if($Busqueda=='1'){
				$this->salida .= "				        <tr><td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"TipoDocumento\" class=\"select\">";
				$tipo_id=$this->tipo_id_paciente();
				$this->BuscarIdPaciente($tipo_id,'');
				$this->salida .= "                  </select></td></tr>";
				$this->salida .= "				        <tr><td class=\"label\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\"></td></tr>";
				$this->salida .= "	  	            <input type=\"hidden\" name=\"TipoBuscar\" value=\"$Busqueda\">";
				$this->salida .= "                 <input type=\"hidden\" name=\"LinkCargo\" value=\"$LinkCargo\">";
			}
			if($Busqueda=='2'){
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "				        <tr><td class=\"label\">NOMBRES</td><td><input type=\"text\" class=\"input-text\" name=\"nombres\" maxlength=\"32\"></td></tr>";
				$this->salida .= "				        <tr><td class=\"label\">APELLIDOS</td><td><input type=\"text\" class=\"input-text\" name=\"apellidos\" maxlength=\"32\"></td></tr>";
				$this->salida .= "	  	           <input type=\"hidden\" name=\"TipoBuscar\" value=\"$Busqueda\">";
				$this->salida .= "                 <input type=\"hidden\" name=\"LinkCargo\" value=\"$LinkCargo\">";
			}
			if($Busqueda=='3'){
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "				        <tr><td colspan=\"2\">&nbsp;</td></tr>";
				$this->salida .= "				       <tr><td class=\"".$this->SetStyle("Responsable")."\">PLAN: </td><td><select name=\"Responsable\" class=\"select\">";
        $responsables=$this->CallMetodoExterno('app','Triage','user','responsables');
				$this->MostrarResponsable($responsables,$Responsable);
				$this->salida .= "              </select></td></tr>";
				$this->salida .= "                <input type=\"hidden\" name=\"TipoBuscar\" value=\"$Busqueda\">";
				$this->salida .= "                 <input type=\"hidden\" name=\"LinkCargo\" value=\"$LinkCargo\">";
			}
			if($Busqueda=='4'){
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "				        <tr><td colspan=\"2\">&nbsp;</td></tr>";
				$this->salida .= "				        <tr><td class=\"".$this->SetStyle("IngresoId")."\">No. ORDEN</td><td><input type=\"text\" class=\"input-text\" name=\"NumIngreso\" maxlength=\"32\"></td></tr>";
				$this->salida .= "                <input type=\"hidden\" name=\"TipoBuscar\" value=\"$Busqueda\">";
				$this->salida .= "                 <input type=\"hidden\" name=\"LinkCargo\" value=\"$LinkCargo\">";
			}
			$this->salida .= "               <tr><td align=\"$ali\" colspan=\"$col\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\"></td>";
			$this->salida .= "			      </form>";
			$actionM=ModuloGetURL('app','Quirurgicos','user','MenuQuirurjicos');
			$this->salida .= "             <form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
			$this->salida .= "				       <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"><br></td></form>";
			$this->salida .= "				       </tr>";
			$this->salida .= "		  </fieldset></td></tr></table>";
			$this->salida .= "	</table>";
			$this->salida .= "		   </td>";
			$this->salida .= "		   <td>";
			$this->salida .= "      <BR><table border=\"0\" width=\"92%\" align=\"center\">";
			$this->salida .= "             <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
			$this->salida .= "          <tr><td><fieldset><legend class=\"field\">BUSQUEDA AVANZADA</legend>";
			$this->salida .= "			      <table width=\"90%\" align=\"center\" border=\"0\">";
			$this->salida .= "				        <tr><td colspan=\"2\">&nbsp;</td></tr>";
			$this->salida .= "				       <tr><td class=\"label\">TIPO BUSQUEDA: </td><td><select name=\"TipoBusqueda\" class=\"select\">";
			$this->salida .= "                   <option value=\"1\" selected>DOCUMENTO</option>";
			//$this->salida .= "                   <option value=\"2\">NOMBRE</option>";
			$this->salida .= "                   <option value=\"3\">PLAN</option>";
			$this->salida .= "                   <option value=\"4\">No.ORDEN</option>";
			$this->salida .= "              </select></td></tr>";
			$this->salida .= "				       <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Busc\" value=\"BUSCAR\"></td></tr>";
			$this->salida .= "                 <input type=\"hidden\" name=\"LinkCargo\" value=\"$LinkCargo\">";
			$this->salida .= "			      </form>";
			$this->salida .= "				        <tr><td colspan=\"2\">&nbsp;</td></tr>";
			$this->salida .= "			         </table>";
			$this->salida .= "		  </fieldset></td></tr></table>";
			$this->salida .= "		   </td>";
			$this->salida .= "		</tr>";
			$this->salida .= "	</table>";
			if($mensaje){
					$accionT=ModuloGetURL('app','Facturacion','user','main',array('TipoCuenta'=>$TipoCuenta));
					$this->salida .= "			<p class=\"label_error\" align=\"center\">$mensaje</p>";
					$this->salida .= "           <form name=\"formabuscar\" action=\"$accionT\" method=\"post\">";
			}
			if(!$arr)
			{
					$this->BusquedaCompleta();
					$arr=$this->BusquedaCompleta();
					//$_SESSION['SPY']=$this->RecordSearch($Caja,$TipoCuenta,$Departamento);
			}
			$this->salida.="<table border=\"0\" align=\"center\"  width=\"100%\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida.="</table><br>";
			if(!empty($arr) AND !empty($f))
			{
					$this->salida .= "		<br><table width=\"70%\" border=\"0\" cellspacing=\"3\" cellpadding=\"3\" align=\"center\" >";
					$this->salida .= "			<tr align=\"center\" class=\"modulo_table_list_title\">";
 					$this->salida .= "				<td width=\"20%\">IDENTIFICACION</td>";
					$this->salida .= "				<td width=\"40%\">DATOS DEL PACIENTE</td>";
					///$this->salida .= "				<td width=\"15%\">RESPONSABLE</td>";
					//$this->salida .= "				<td width=\"15%\">TIPO PLAN</td>";
					$this->salida .= "				<td width=\"10%\"></td>";
					$this->salida .= "			</tr>";

					for($i=0;$i<sizeof($arr);$i++)
					{
								if( $i % 2){ $estilo='modulo_list_claro';}
								else {$estilo='modulo_list_oscuro';}

                $this->salida.="<tr class='$estilo' align='center'>";
								$this->salida.="  <td ><font color='#4D6EAB'>".$arr[$i][tipo_id_paciente]." &nbsp; - &nbsp;".$arr[$i][paciente_id]."</font></td>";
								$this->salida.="  <td >".$arr[$i][nombre]."</td>";
								//$this->salida.="  <td >".$arr[$i][descripcion]."</td>";
								//$this->salida.="  <td >".$arr[$i][plan_descripcion]."</td>";
								$this->salida .= "<td width=\"10%\"><a href=".ModuloGetURL('app','Quirurgicos','user','FrmOrdenar',array('tipoid'=>$arr[$i]['tipo_id_paciente'],'idp'=>$arr[$i]['paciente_id'],'nombre'=>urlencode($arr[$i]['nombre'])))."><img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>&nbsp;&nbsp;VER</a></td>";
								$this->salida.="</tr>";
   				 }
					$this->salida.="</table>";
					$this->conteo=$_SESSION['SPY'];
					$this->salida .=$this->RetornarBarra();
			}
			$this->salida .= ThemeCerrarTabla();
    	return true;
	}
	/*
	* Esta funcion te muestra en detalle las ordenes de servicio
	* filtrados por(tipo_afiliado_id,rango,orden_servicio_id),y separarados por plan.
	* @return boolean
	*/
	 function FrmOrdenar($nom,$tipo,$id){
    
		if(!$nom){
				$nom=urldecode($_REQUEST['nombre']);
				$tipo=$_REQUEST['tipoid'];
				$id=$_REQUEST['idp'];
		}
		$this->salida.= ThemeAbrirTabla('ORDEN DE SERVICIOS MEDICOS');
		$this->Encabezado();
		$this->salida .= "              <BR><table  cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"80%\" align=\"center\" >";
		$this->salida .="".$this->SetStyle("MensajeError")."";
		$this->salida .= "				       <tr><td class=\"modulo_table_title\"  width=\"20%\">NOMBRE PACIENTE: </td><td class=\"modulo_list_claro\" align=\"left\">".$nom."</td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_table_title\"  class=\"label\" width=\"40%\" align=\"left\">IDENTIFICACION: </td><td class=\"modulo_list_claro\" align=\"left\">".$tipo."&nbsp;".$id."</td></tr>";
		$this->salida .= "</table><BR>";
		$vector=$this->TraerOrdenesServicio($tipo,$id);//sacamos las ordenes de sevicio que desea pagar.
    
		//falta $i++
		$this->salida .= "           <form name=\"formo\" action=\"".ModuloGetURL('app','Quirurgicos','user','RealizarProgramaciondeOrden',array('id_tipo'=>$tipo,'nom'=>urlencode($nom),'id'=>$id,'plan_id'=>$vector[$i][plan_id]))."\" method=\"post\">";
		for($i=0;$i<sizeof($vector);){
			$k=$i;
			if($vector[$i][plan_id]==$vector[$k][plan_id]
				AND $vector[$i][tipo_afiliado_id]==$vector[$k][tipo_afiliado_id]
				AND $vector[$i][rango]==$vector[$k][rango]
				AND $vector[$i][orden_servicio_id]==$vector[$k][orden_servicio_id]){
				$this->salida.="<BR><table  align=\"center\" border=\"0\" width=\"99%\">";
				$this->salida.="<tr class=\"modulo_table_list_title\">";
				$this->salida.="  <td align=\"left\" colspan=\"8\">PLAN&nbsp;&nbsp;".$vector[$i][descripcion]."&nbsp;&nbsp;".
				$vector[$i][plan_descripcion]."</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="  <td width=\"7%\">ORDEN</td>";
				$this->salida.="  <td width=\"8%\" align=\"left\" colspan=\"7\">ITEM</td>";
				//$this->salida.="  <td width=\"9%\" nowrap>CANTIDAD</td>";
				//$this->salida.="  <td width=\"10%\" nowrap>CARGO BASE</td>";
				//$this->salida.="  <td width=\"40%\">DESCRIPCION</td>";
				//$this->salida.="  <td width=\"20%\">VENCIMIENTO</td>";
				//$this->salida.="  <td width=\"5%	\"></td>";
				//$this->salida.="  <td width=\"10%\">Todos&nbsp;<input type=checkbox name=sel onclick=chequeoTotal(this.form,this.checked)></td>";
				//form
				$this->salida.="</tr>";
		  }
			while($vector[$i][plan_id]==$vector[$k][plan_id]
			AND $vector[$i][tipo_afiliado_id]==$vector[$k][tipo_afiliado_id]
			AND $vector[$i][rango]==$vector[$k][rango]
			AND $vector[$i][servicio]==$vector[$k][servicio])
			{
				$this->salida.="<tr class='modulo_list_claro'>";
				$this->salida.="  <td  class=\"hc_table_submodulo_list_title\" width=\"7%\">".$vector[$k][orden_servicio_id]."</td>";
				$this->salida.="  <td colspan=\"7\">";
				$this->salida.="  <table align=\"center\" border=\"1\" width=\"100%\">";
				$l=$k;
				while($vector[$k][orden_servicio_id]==$vector[$l][orden_servicio_id]
					AND $vector[$k][plan_id]==$vector[$l][plan_id]
					AND $vector[$k][tipo_afiliado_id]==$vector[$l][tipo_afiliado_id]
					AND $vector[$k][rango]==$vector[$l][rango]
					AND $vector[$k][servicio]==$vector[$l][servicio]){
					$vecimiento=$vector[$l][fecha_vencimiento];
					$arr_fecha=explode(" ",$vecimiento);
					$activacion=$vector[$l][fecha_activacion];
					$arr_fechaact=explode(" ",$activacion);
					if( $l % 2){$estilo='modulo_list_oscuro';$estilo1='modulo_list_claro';}
					else {$estilo='modulo_list_claro';$estilo1='modulo_list_oscuro';}
					$this->salida.="<tr align='center'>";
					$this->salida.="  <td align='center' class=$estilo width=\"8%\"><font color='#4D6EAB'>".$vector[$l][numero_orden_id]."</font></td>";
					$this->salida.="  <td class=\"$estilo\">";
					$this->salida.="  <table class=\"normal_10\" align=\"center\" border=\"0\" width=\"100%\">";
					$m=$l;
					while($vector[$l][numero_orden_id]==$vector[$m][numero_orden_id]
					AND $vector[$l][orden_servicio_id]==$vector[$m][orden_servicio_id]
					AND $vector[$l][plan_id]==$vector[$m][plan_id]
					AND $vector[$l][tipo_afiliado_id]==$vector[$m][tipo_afiliado_id]
					AND $vector[$l][rango]==$vector[$m][rango]
					AND $vector[$l][servicio]==$vector[$m][servicio]){
						$this->salida.="<tr>";
            $this->salida.="<td width=\"15%\" nowrap class=\"$estilo1\"><b>CARGO</b></td><td class=\"$estilo1\" nowrap align=\"left\" width=\"15%\">".$vector[$m][cargoi]."</td>";
						$this->salida.="<td width=\"15%\" nowrap class=\"$estilo1\"><b>DESCRIPCION</b></td><td class=\"$estilo1\" align=\"left\"  colspan=\"4\">".$vector[$m][des1]."</td>";
						$this->salida.="</tr>";
						$this->salida.="<tr class=$estilo1>";
						$this->salida.="<td class=\"$estilo1\"><b>CANTIDAD</b></td><td nowrap width=\"10%\" align=\"left\" >".$vector[$m][cantidad]."</td>";
            $this->salida.="<td class=\"$estilo1\"><b>VENCIMIENTO</b></td>";
            //if(strtotime($vector[$m][fecha_vencimiento]) > strtotime(date("Y-m-d"))){
						$this->salida.="<td align=\"left\" align=\"center\" >$arr_fecha[0]</td>";
						//}else{
							//$this->salida.="  <td align=\"center\" ><font color='#4D6EAB'>VENCIDO</font>&nbsp&nbsp&nbsp;";
							//$this->salida.="  <img src=\"".GetThemePath()."/images/delete.gif\"></td>";
						//}
						$this->salida.="<td class=\"$estilo1\"><b>FECHA ACTIVACION</b></td>";
						$this->salida.="<td colspan=\"2\" align=\"left\" align=\"center\" >".$arr_fechaact[0]."</td>";
            $this->salida.="</tr>";
						//if($vector[$l][observacion]){
						$this->salida.="<tr>";
						$this->salida.="<td class=\"$estilo1\"><b>TIPO CIRUGIA</b></td>";
						$this->salida.="<td class=$estilo1>".$vector[$l]['tipocirugia']."&nbsp;</td>";
						$this->salida.="<td class=\"$estilo1\"><b>FINALIDAD CIRUGIA</b></td>";
            $this->salida.="<td class=$estilo1>".$vector[$l]['ambitocirugia']."&nbsp;</td>";
						$this->salida.="<td class=\"$estilo1\"><b>AMBITO CIRUGIA</b></td>";
            $this->salida.="<td colspan=\"2\" class=$estilo1>".$vector[$l][finalidadprocedimiento]."&nbsp;</td>";
						$this->salida.="</tr>";
						$this->salida.="<tr>";
						$this->salida.="<td class=\"$estilo1\"><b>OBSERVACIONES</b></td>";
            $this->salida.="<td colspan=\"5\" class=$estilo1>".$vector[$l][observacion]."&nbsp;</td>";
						if($arr_fechaact[0]==date("Y-m-d")){
							$this->salida.="<td class=\"$estilo1\" align=\"center\"><input type=checkbox name=op[$m] value=".$vector[$m][numero_orden_id].",".$vector[$m][cargo_cups].",".$vector[$m][tarifario_id].",".$vector[$m][autorizacion_ext].",".$vector[$m][autorizacion_int].",".$vector[$m][cantidad].",".urlencode($vector[$m][descargo]).",".$vector[$m][servicio].",".$vector[$m][serv_des].",".$vector[$k][orden_servicio_id].",".$vector[$k][plan_id]."></td>";
						}else{
							$this->salida.="<td class=\"$estilo1\" align=\"center\">&nbsp;</td>";
						}
						$this->salida.="</tr>";
						//}
						$m++;
					}
					$this->salida.="</table>";
					$this->salida.="</td>";
					$this->salida.="</tr>";
					$l=$m;
				}
	      //parte de alex.
				$this->salida.="<tr><td colspan='8' align=\"center\">";
				$this->salida.="<table width='100%' border='0' cellpadding='2' align=\"center\">";
				$this->salida.="<tr><td width='20%' class=\"hc_table_submodulo_list_title\" >OBSERVACION</td><td class='modulo_list_claro'>".$vector[$k][observacion]."</td></tr>";
				$this->salida.="<tr><td width='20%' class=\"hc_table_submodulo_list_title\" >SERVICIO</td><td width='80%' class='modulo_list_oscuro'>".$vector[$k][serv_des]."</td></tr>";
				$this->salida.="<tr><td width='20%' class=\"hc_table_submodulo_list_title\" >AUT. INT.</td><td width='80%' class='modulo_list_claro'>".$vector[$k][autorizacion_int]."</td></tr>";
				$this->salida.="<tr><td width='20%' class=\"hc_table_submodulo_list_title\" >AUT. EXT.</td><td width='80%' class='modulo_list_oscuro'>".$vector[$k][autorizacion_ext]."</td></tr>";
				$this->salida.="<tr><td width='20%' class=\"hc_table_submodulo_list_title\" >AFILIACION</td><td width='80%' class='modulo_list_claro'>".$vector[$k][tipo_afiliado_nombre]."</td></tr>";
				$this->salida.="</table>";
				$this->salida.="</td></tr>";
				//parte de alex.
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";
				$k=$l;
		  }
			$this->salida.="</table>";
			$i=$k;
	  }
		$this->salida.="<table align='center' width='99%'>";
		$this->salida.="<tr align='right' class=\"modulo_table_button\">";
		$this->salida.="<td><input class=\"input-submit\" type=submit name=ProgramacionQX value=ProgramacionQX></form></td>";
		//tenia mandar$l
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.="<br><br><table align=\"center\" width='20%' border=\"0\">";
		$action2=ModuloGetURL('app','Quirurgicos','user','FormaMetodoBuscar',array("uid"=>$uid,'nombre'=>urldecode($NombreUsuario),'usuario'=>$Usuario,"empID"=>$empresa,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of']));
		$this->salida .= "           <form name=\"formados\" action=\"$action2\" method=\"post\">";
		$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
		$this->salida .= "</tr>";
		$this->salida.="</table><br>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
/**
* Funcion que consulta de la base de datos las programaciones de cirugias de una fecha
* @return boolean
*/
	function ConsultaAgendaQuirofano($tipoTiempo){
          if(!$_REQUEST['DiaEspe']){
               if($_SESSION['QUIRURGICOS']['DiaEspe']){
					$_REQUEST['DiaEspe']=$_SESSION['QUIRURGICOS']['DiaEspe'];
                         $tipoTiempo=$_SESSION['QUIRURGICOS']['tipoTiempo'];
               }else{
				$_REQUEST['DiaEspe']=date("Y-m-d");
               	$_SESSION['QUIRURGICOS']['DiaEspe']=$_REQUEST['DiaEspe'];
				$_SESSION['QUIRURGICOS']['tipoTiempo']=$tipoTiempo;
			}
		}else{
			$_SESSION['QUIRURGICOS']['DiaEspe']=$_REQUEST['DiaEspe'];
			$_SESSION['QUIRURGICOS']['tipoTiempo']=$tipoTiempo;
		}
          
          //Funcionalidades de Capas
          $this->IncludeJS("CrossBrowser");
          $this->IncludeJS("CrossBrowserDrag");
          $this->IncludeJS("CrossBrowserEvent");
          //Funcionalidades de Capas

          include_once "app_modules/Quirurgicos/RemoteXajax/DetalleReservas.php";
          $this->SetXajax(array("Busqueda"));      

		$this->salida.= ThemeAbrirTabla('RESERVA DEL QUIROFANO');
		$this->salida .= "<SCRIPT>";
          
          $javaC .= "   var contenedor\n";
          
          $javaC .= "   function CargarContenedor(Elemento)\n";
          $javaC .= "   {\n";
          $javaC .= "        contenedor = Elemento;\n";
          $javaC .= "   }\n";

          $javaC .= "   var titulo = 'titulo';\n";
          $javaC .= "   var hiZ = 2;\n";
          
          $javaC .= "   function Iniciar(tit, suministro_id, Elemento)\n";
          $javaC .= "   {\n";
          $javaC .= "       Capa = xGetElementById(Elemento);\n";
          $javaC .= "       xMoveTo(Capa, xClientWidth()/3, xScrollTop()+120);\n";
          
          $javaC .= "       document.getElementById('tituloAnul').innerHTML = '<center>'+tit+'</center>';\n";
          $javaC .= "       document.getElementById('errorAnul').innerHTML = '';\n";
          
          $javaC .= "       ele = xGetElementById('tituloAnul');\n";
          $javaC .= "       xResizeTo(ele, 280, 20);\n";
          $javaC .= "       xMoveTo(ele, 0, 0);\n";
          $javaC .= "       ele = xGetElementById('cerrarAnul');\n";
          $javaC .= "       xResizeTo(ele,20, 20);\n";
          $javaC .= "       xMoveTo(ele, 280, 0);\n";
          $javaC .= "   }\n";
          
          $javaC .= "   function myOnDragStart(ele, mx, my)\n";
          $javaC .= "   {\n";
          $javaC .= "     window.status = '';\n";
          $javaC .= "     if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
          $javaC .= "     else xZIndex(ele, hiZ++);\n";
          $javaC .= "     ele.myTotalMX = 0;\n";
          $javaC .= "     ele.myTotalMY = 0;\n";
          $javaC .= "   }\n";
          
          $javaC .= "   function myOnDrag(ele, mdx, mdy)\n";
          $javaC .= "   {\n";
          $javaC .= "     if (ele.id == titulo) {\n";
          $javaC .= "       xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
          $javaC .= "     }\n";
          $javaC .= "     else {\n";
          $javaC .= "       xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
          $javaC .= "     }  \n";
          $javaC .= "     ele.myTotalMX += mdx;\n";
          $javaC .= "     ele.myTotalMY += mdy;\n";
          $javaC .= "   }\n";
               
          $javaC .= "   function myOnDragEnd(ele, mx, my)\n";
          $javaC .= "   {\n";
          $javaC .= "   }\n";
          
          $javaC.= "    function MostrarCapa(Elemento)\n";
          $javaC.= "    {\n;";
          $javaC.= "    		capita = xGetElementById(Elemento);\n";
          $javaC.= "    		capita.style.display = \"\";\n";
          $javaC.= "    }\n";
          
          $javaC.="		function BusquedaDetalle(Quiro, Hora, DiaEspe, SelCapa)\n";
          $javaC.="		{\n";
          $javaC.="			xajax_Busqueda(Quiro, Hora, DiaEspe, SelCapa);\n";
          $javaC.="		}\n";

          $javaC.= "	function Cerrar(Elemento)\n";
          $javaC.= "	{\n";
          $javaC.= "    		capita = xGetElementById(Elemento);\n";          
          $javaC.= "    		capita.style.display = \"none\";\n";          
          $javaC.= "	}\n";                    
          
          $javaC.= "</script>\n";
          $this->salida.= $javaC;
		
		$this->salida.= ThemeAbrirTabla('CONSULTA PROGRAMACIONES');
		$this->Encabezado();
		$this->salida .= "   <br>";
		$this->salida .= "   <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "   <tr><td>";
		
          $this->salida.="\n".'<script>'."\n";
		$this->salida.='function year1(t)'."\n";
		$this->salida.='{'."\n";
		$this->salida.='window.location.href="Contenido.php?year="+t.elements[0].value+"&meses="+t.elements[1].value+"';
		foreach($_REQUEST as $v=>$v1)
		{
			if($v!='year' and $v!='meses' and $v!='DiaEspe')
			{
				if (is_array($v1)) {
                         foreach($v1 as $k2=>$v2) {
                              if (is_array($v2)) {
                                   foreach($v2 as $k3=>$v3) {
                                        if (is_array($v3)) {
                                             foreach($v3 as $k4=>$v4) {
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
		$this->salida .= "<table border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .='<tr align="center">';
		$this->salida .="<td class=\"label\">AÑO</td><td><select name=\"year\" onchange=\"year1(this.form)\" class=\"select\">";
		if(empty($_REQUEST['year'])){ 
			$a=explode("-",$_SESSION['CITASMES'][0]);
			$year=$_REQUEST['year']=$a[0];
			$this->AnosAgenda(True,$_REQUEST['year']);
		}else{
			$this->AnosAgenda(true,$_REQUEST['year']);
			$year=$_REQUEST['year'];
		}
		$this->salida .= "</select></td>";
		$this->salida .="<td class=\"label\">MES</td><td><select name=\"mes\" onchange=\"year1(this.form)\" class=\"select\">";
		if(empty($_REQUEST['meses'])){
			$a=explode("-",$_SESSION['CITASMES'][0]);
			if(empty($a[0])){
				$mes=$_REQUEST['meses']=date("m");
				$year=date("Y");
			}else{
				$mes=$_REQUEST['meses']=$a[1];
			}
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
		$this->salida .= "   </table><BR><BR>";
		$rango=ModuloGetVar('app', 'Quirurgicos','RangoTurnosQuirofano');
		$accion=ModuloGetURL('app','Quirurgicos','user','LlamaFormaBuscadorProgramCirugias');
		$this->salida .= "   <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$SalasCirugia=$this->SeleccionQuirofanosDpto();
		$cadena=explode('-',$_REQUEST['DiaEspe']);
		$anoP=$cadena[0];
		$mesP=$cadena[1];
		$diaP=$cadena[2];
		if(date("Y-m-d",mktime(0,0,0,$mesP,$diaP,$anoP))<date("Y-m-d",mktime(0,0,0,date("mes"),date("d"),date("Y")))){
			$FechaEspe=date('Y-m-d');
		}else{
			$FechaEspe=$_REQUEST['DiaEspe'];
		}
		$cadena=explode('-',$FechaEspe);
		$anoProgram=$cadena[0];
		$mesProgram=$cadena[1];
		$diaProgram=$cadena[2];
		$FechaConver=mktime(0,0,0,$mesProgram,$diaProgram,$anoProgram);
		if($SalasCirugia){
			$this->salida .= "   <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
			$colspan=sizeof($SalasCirugia);
			$this->salida .= "   <tr><td class=\"modulo_table_list_title\" align=\"center\" colspan=\"$colspan\">PROGRAMACIONES DE CIRUGIAS PARA EL DIA&nbsp;&nbsp;".strftime("%A %d de  %B de %Y",$FechaConver)."</td></tr>";
			$this->salida .= "   <tr>";
               for($i=0;$i<sizeof($SalasCirugia);$i++){
                    $this->salida .= "   <td align=\"center\" class=\"modulo_table_list_title\">".$SalasCirugia[$i]['abreviatura']."</td>";
               }
               $this->salida .= "   </tr>";
               if($tipoTiempo=='1'){
                    $HoraInincio='0';
                    $MinutosInicio='0';
                    $FechaUni=$this->FechaStamp($FechaEspe);
                    $infoCadena = explode ('/', $FechaUni);
                    $dia=$infoCadena[0];
                    $mes=$infoCadena[1];
                    $ano=$infoCadena[2];
                    $SumaInicio=date("Y-m-d H:i:s",mktime($HoraInincio,$MinutosInicio,0,$mes,$dia,$ano));
                    $SumaFinal=date('Y-m-d H:i:s',mktime(($HoraInincio+24),$MinutosInicio,0,$mes,$dia,$ano));
                    $SumaHora=$SumaInicio;
               }else{
                    $rangoInicio=ModuloGetVar('app', 'Quirurgicos','RangoInicioTurnoQuirofano');
                    $rangoDuracion=ModuloGetVar('app', 'Quirurgicos','RangoDuracionTurnoQuirofano');
                    $cadena=explode(':',$rangoInicio);
                    $HoraInincio=$cadena[0];
                    $MinutosInicio=$cadena[1];
                    $FechaUni=$this->FechaStamp($FechaEspe);
                    $infoCadena = explode ('/', $FechaUni);
                    $dia=$infoCadena[0];
                    $mes=$infoCadena[1];
                    $ano=$infoCadena[2];
                    $SumaInicio=date("Y-m-d H:i:s",mktime($HoraInincio,$MinutosInicio,0,$mes,$dia,$ano));
                    $SumaFinal=date('Y-m-d H:i:s',mktime(($HoraInincio+$rangoDuracion),$MinutosInicio,0,$mes,$dia,$ano));
                    $SumaHora=$SumaInicio;
               }
               while($SumaHora<$SumaFinal){
                    if($y % 2){$y++;}
                         $HoraMosDef=$this->HoraStamp($SumaHora);
                         $infoCadena = explode (':',$HoraMosDef);
                         $HoraMos=$infoCadena[0];
                         $MinutosMos=$infoCadena[1];
                         $this->salida .= "   <tr>";
                         for($i=0;$i<sizeof($SalasCirugia);$i++){
                              if($y % 2){$estilo='hc_table_submodulo';}else{$estilo='modulo_list_claro';}
                                   $Quiro=$SalasCirugia[$i]['quirofano'];
                                   $abreviatura=$SalasCirugia[$i]['abreviatura'];
                                   $comprobacion=$this->consultaProgramacion($Quiro,$SumaHora,$rango);
                              if($comprobacion!=0){
                                   $accion=ModuloGetURL('app','Quirurgicos','user','LlamaProgramacionQX',array("programacion"=>$comprobacion));
                                   $javaInfoProg = "javascript:MostrarCapa('ContenedorCapaProg');Iniciar('INFORMACION DE LA PROGRAMACION','".$Quiro."','ContenedorCapaProg');CargarContenedor('ContenedorCapaProg');BusquedaDetalle('".$Quiro."','".$SumaHora."','".$rango."','2')";
                                   $this->salida .= " <td align=\"left\" class=\"modulo_list_oscuro\"><label><a href=\"$accion\">$HoraMos : $MinutosMos<a></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label><a href=\"$javaInfoProg\"><img border=\"0\" src=\"".GetThemePath()."/images/infor.png\" title=\"Ver detalle de la programación\"></a></label></td>\n";
                                   //<img border=\"0\" src=\"".GetThemePath()."/images/checksi.png\">
                              }else{
                              $this->salida .= " <td align=\"left\" class=\"$estilo\">$HoraMos : $MinutosMos</td>\n";
                         }
                         $y++;
                    }
                    $Fecha=$this->FechaStamp($SumaHora);
                    $infoCadena = explode ('/', $Fecha);
                    $dia=$infoCadena[0];
                    $mes=$infoCadena[1];
                    $ano=$infoCadena[2];
                    $HoraDef=$this->HoraStamp($SumaHora);
                    $infoCadena = explode (':',$HoraDef);
                    $Hora=$infoCadena[0];
                    $Minutos=$infoCadena[1];
                    $SumaHora=date('Y-m-d H:i:s',mktime($Hora,($Minutos+$rango),0,$mes,$dia,$ano));
               }
               
               //Capa de Informacion consulta de programaciones
               $this->salida.="<div id='ContenedorCapaProg' class='d2Container' style=\"display:none\">";
               $this->salida .= "    <div id='tituloAnul' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
               $this->salida .= "    <div id='cerrarAnul' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorCapaProg');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
               $this->salida .= "    <div id='errorAnul' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
               $this->salida .= "    <div id='ContenidoCapaProg'>\n";
               $this->salida .= "    </div>\n";     
               $this->salida.="</div>";
               //Capa de Informacion consulta de programaciones

               $this->salida .= "   </table><BR>";
          }
          $this->salida .= "   <table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
          $this->salida .= "   <tr><td align=\"center\"><input type=\"submit\" class=\"input-submit\" value=\"MENU\" name=\"Menu\">";
          $this->salida .= "   <input type=\"submit\" class=\"input-submit\" value=\"VOLVER\" name=\"Regresar\"></td></tr>";
          $this->salida .= "   </table>";
          $this->salida .= "   </form>";
		$this->salida .= ThemeCerrarTabla();
          $this->salida .= ThemeCerrarTabla();
		return true;
	}
/**
* Funcion que visualiza los motivos de una cancelacion de una reserva
* @return boolean
*/
	function FormaLlamaMotivosCancelacionReserva($reservaQuirofano,$motivoCancel,$observacion){
    $this->salida.= ThemeAbrirTabla('CANCELACION RESERVA QUIROFANO.   PROGRAMACION No.  '.$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']);
		$this->Encabezado();
		$accion=ModuloGetURL('app','Quirurgicos','user','InsertarCancelarReservaProgramacion',array("reservaQuirofano"=>$reservaQuirofano));
		$this->salida .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"65%\" align=\"center\" class=\"normal_10\">";
    $this->salida .= "  <tr><td width=\"100%\">";
		$this->salida .= "  <fieldset><legend class=\"field\">MOTIVO CANCELACION RESERVA</legend>";
		$this->salida .= "  <br><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "  <tr><td colspan=\"4\" align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "  </td></tr>";
		$this->salida .= "	 <tr><td class=\"".$this->SetStyle("motivoCancel")."\">MOTIVO CANCELACION: </td><td><select name=\"motivoCancel\" class=\"select\">";
		$Motivos=$this->MotivosCancelacionReserva();
		$this->MostrasSelect($Motivos,'False',$motivoCancel);
		$this->salida .= "   </select></td></tr>";
		$this->salida .= "	 <tr><td class=\"label\" colspan=\"2\">OBSERVACIONES</td></tr><tr><td colspan=\"2\"><textarea name=\"observacion\" cols=\"65\" rows=\"3\" class=\"textarea\">$observacion</textarea></td></tr>";
    $this->salida .= "  </table><br>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
    $this->salida .= "  <tr><td align=\"center\">";
    $this->salida .= "  <BR><input type=\"submit\" class=\"input-submit\" value=\"VOLVER\" name=\"regresar\">&nbsp&nbsp&nbsp;<input type=\"submit\" class=\"input-submit\" value=\"ACEPTAR\" name=\"aceptar\">";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table>";
    $this->salida .= "<br>";
		$this->salida .= "</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
/**
* Funcion que muestra la disponibilidad de un quirofano para realizar una reserva
* @return boolean
*/
	function FormaRealizaReservasQuirofano($DiaEspecial){
     	if(!$_REQUEST['DiaEspe']){
			$_REQUEST['DiaEspe']=$DiaEspecial;
		}
          
          //Funcionalidades de Capas
          $this->IncludeJS("CrossBrowser");
          $this->IncludeJS("CrossBrowserDrag");
          $this->IncludeJS("CrossBrowserEvent");
          //Funcionalidades de Capas
          
          include_once "app_modules/Quirurgicos/RemoteXajax/DetalleReservas.php";
          $this->SetXajax(array("Busqueda"));

		$this->salida.= ThemeAbrirTabla('RESERVA DEL QUIROFANO');
		$this->salida .= "<SCRIPT>";
          
          $javaC .= "   var contenedor\n";
          
          $javaC .= "   function CargarContenedor(Elemento)\n";
          $javaC .= "   {\n";
          $javaC .= "        contenedor = Elemento;\n";
          $javaC .= "   }\n";

          $javaC .= "   var titulo = 'titulo';\n";
          $javaC .= "   var hiZ = 2;\n";
          
          $javaC .= "   function Iniciar(tit, suministro_id, Elemento)\n";
          $javaC .= "   {\n";
          $javaC .= "       Capa = xGetElementById(Elemento);\n";
          $javaC .= "       xMoveTo(Capa, xClientWidth()/3, xScrollTop()+120);\n";
          
          $javaC .= "       document.getElementById('tituloAnul').innerHTML = '<center>'+tit+'</center>';\n";
          $javaC .= "       document.getElementById('errorAnul').innerHTML = '';\n";
          
          $javaC .= "       ele = xGetElementById('tituloAnul');\n";
          $javaC .= "       xResizeTo(ele, 280, 20);\n";
          $javaC .= "       xMoveTo(ele, 0, 0);\n";
          $javaC .= "       ele = xGetElementById('cerrarAnul');\n";
          $javaC .= "       xResizeTo(ele,20, 20);\n";
          $javaC .= "       xMoveTo(ele, 280, 0);\n";
          $javaC .= "   }\n";
          
          $javaC .= "   function myOnDragStart(ele, mx, my)\n";
          $javaC .= "   {\n";
          $javaC .= "     window.status = '';\n";
          $javaC .= "     if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
          $javaC .= "     else xZIndex(ele, hiZ++);\n";
          $javaC .= "     ele.myTotalMX = 0;\n";
          $javaC .= "     ele.myTotalMY = 0;\n";
          $javaC .= "   }\n";
          
          $javaC .= "   function myOnDrag(ele, mdx, mdy)\n";
          $javaC .= "   {\n";
          $javaC .= "     if (ele.id == titulo) {\n";
          $javaC .= "       xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
          $javaC .= "     }\n";
          $javaC .= "     else {\n";
          $javaC .= "       xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
          $javaC .= "     }  \n";
          $javaC .= "     ele.myTotalMX += mdx;\n";
          $javaC .= "     ele.myTotalMY += mdy;\n";
          $javaC .= "   }\n";
               
          $javaC .= "   function myOnDragEnd(ele, mx, my)\n";
          $javaC .= "   {\n";
          $javaC .= "   }\n";
          
          $javaC.= "function MostrarCapa(Elemento)\n";
          $javaC.= "{\n;";
          $javaC.= "    capita = xGetElementById(Elemento);\n";
          $javaC.= "    capita.style.display = \"\";\n";
          $javaC.= "}\n";
          
          $javaC.="		function BusquedaDetalle(Quiro, Hora, DiaEspe, SelCapa)\n";
          $javaC.="		{\n";
          $javaC.="			xajax_Busqueda(Quiro, Hora, DiaEspe, SelCapa);\n";
          $javaC.="		}\n";

          $javaC.= "function Cerrar(Elemento)\n";
          $javaC.= "{\n";
          $javaC.= "    capita = xGetElementById(Elemento);\n";          
          $javaC.= "    capita.style.display = \"none\";\n";          
          $javaC.= "}\n";                    
          
          $javaC.= "function load_page()\n";
          $javaC.= "{\n";
          $javaC.= "    location.reload();\n";
          $javaC.= "}\n";
          
          $this->salida.= $javaC;
          $this->salida .= "function IntervalosCheck(frm,valor,interval){";
		$this->salida .= "  ArrayElements= new Array();";
		$this->salida .= "  ArrayValores= new Array();";
		$this->salida .= "  var j=0;";
		$this->salida .= "  var numElements=0;";
		$this->salida .= "  vector=valor.split('/');";
		$this->salida .= "  quirovalor=vector[0];";
		$this->salida .= "  fechavalor=vector[1];";
		$this->salida .= "  for(i=0;i<frm.elements.length;i++){";
		$this->salida .= "    cadena=frm.elements[i].value;";
		$this->salida .= "    vector=cadena.split('/');";
		$this->salida .= "    quiro=vector[0];";
		$this->salida .= "    fecha=vector[1];";
		$this->salida .= "    if(quirovalor==quiro){";
		$this->salida .= "      if(frm.elements[i].checked){";
		$this->salida .= "        numElements=numElements+1;";
		$this->salida .= "        ArrayElements[j]=i;";
          $this->salida .= "        ArrayValores[j]=frm.elements[i].value;";
          $this->salida .= "        j++;";
          $this->salida .= "      }";
		$this->salida .= "    }else{";
		$this->salida .= "      frm.elements[i].checked=false";
		$this->salida .= "    }";
          $this->salida .= "  }";
          $this->salida .= "  var fecha=ArrayValores[0];";
		$this->salida .= "  vector=fecha.split(' ');";
		$this->salida .= "  fechaTot=vector[0];";
		$this->salida .= "  HoraTot=vector[1];";
		$this->salida .= "  vector=HoraTot.split(':');";
		$this->salida .= "  HoraCom=vector[0];";
		$this->salida .= "  MinutosCom=vector[1];";
          $this->salida .= "  for(i=ArrayElements[0];i<=ArrayElements[j-1];i++){";
          $this->salida .= "    cadena=frm.elements[i].value;";
		$this->salida .= "    vector=cadena.split('/');";
		$this->salida .= "    quiro=vector[0];";
		$this->salida .= "    fecha=vector[1];";
		$this->salida .= "    if(quiro==quirovalor){";
		$this->salida .= "      vector=fecha.split(' ');";
		$this->salida .= "      fechaTot=vector[0];";
		$this->salida .= "      HoraTot=vector[1];";
		$this->salida .= "      vector=HoraTot.split(':');";
		$this->salida .= "      HoraAct=vector[0];";
		$this->salida .= "      MinutosAct=vector[1];";
		$this->salida .= "      if(HoraAct == HoraCom && MinutosAct == MinutosCom){";
		$this->salida .= "        frm.elements[i].checked=true;";
		$this->salida .= "      }else{";
		$this->salida .= "        alert ('no es Posible Seleccionar este Intervalo');";
		$this->salida .= "        for(i=0;i<frm.elements.length;i++){";
		$this->salida .= "          frm.elements[i].checked=false;";
		$this->salida .= "        }";
		$this->salida .= "      }";
		$this->salida .= "      MinutosCom=Number(MinutosCom)+Number(interval);";
		$this->salida .= "      if(MinutosCom==60){";
		$this->salida .= "        HoraCom=Number(HoraCom)+Number(1);";
          $this->salida .= "        if(HoraCom==24){";
          $this->salida .= "          HoraCom=00;";
		$this->salida .= "        }";
          $this->salida .= "        MinutosCom=00;";
          $this->salida .= "      }";
		$this->salida .= "    }";
          $this->salida .= "  }";
          $this->salida .= "}";
		$this->salida .= "function LimpiaCheck(frm,x,valorQX,interval){";
          $this->salida .= "  var bandera=1;";
          $this->salida .= "  var HoraCom=0;";
		$this->salida .= "  var MinutosCom=0;";
          $this->salida .= "  if(x==true){";
          $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
          $this->salida .= "      if(frm.elements[i].type=='checkbox'){";
          $this->salida .= "        cadena=frm.elements[i].value;";
		$this->salida .= "        vector=cadena.split('/');";
		$this->salida .= "        quiro=vector[0];";
		$this->salida .= "        fecha=vector[1];";
		$this->salida .= "        if(quiro==valorQX){";
          $this->salida .= "          if(bandera==1){";
          $this->salida .= "            if(fecha!=undefined){";
		$this->salida .= "              vector=fecha.split(' ');";
		$this->salida .= "              fechaTot=vector[0];";
		$this->salida .= "              HoraTot=vector[1];";
		$this->salida .= "              vectorTmp=HoraTot.split(':');";
		$this->salida .= "              HoraCom=vectorTmp[0];";
		$this->salida .= "              MinutosCom=vectorTmp[1];";
          $this->salida .= "              bandera=0;";
          $this->salida .= "            }";
		$this->salida .= "          }";
		$this->salida .= "          if(fecha!=undefined){";
		$this->salida .= "            vector=fecha.split(' ');";
		$this->salida .= "            fechaTot=vector[0];";
		$this->salida .= "            HoraTot=vector[1];";
		$this->salida .= "            vector=HoraTot.split(':');";
		$this->salida .= "            HoraAct=vector[0];";
		$this->salida .= "            MinutosAct=vector[1];";
		$this->salida .= "            if(HoraAct == HoraCom && MinutosAct == MinutosCom){";
          $this->salida .= "              frm.elements[i].checked=true;";
          $this->salida .= "            }else{";
		$this->salida .= "              alert ('no es Posible Seleccionar este Intervalo');";
		$this->salida .= "              for(i=0;i<frm.elements.length;i++){";
		$this->salida .= "                frm.elements[i].checked=false;";
		$this->salida .= "              }";
		$this->salida .= "            }";
		$this->salida .= "            MinutosCom=Number(MinutosCom)+Number(interval);";
		$this->salida .= "            if(MinutosCom==60){";
		$this->salida .= "              HoraCom=Number(HoraCom)+Number(1);";
          $this->salida .= "              if(HoraCom==24){";
          $this->salida .= "                HoraCom=00;";
		$this->salida .= "              }";
          $this->salida .= "              MinutosCom=00;";
          $this->salida .= "            }";
		$this->salida .= "          }";
		$this->salida .= "        }else{";
		$this->salida .= "          frm.elements[i].checked=false;";
		$this->salida .= "        }";
		$this->salida .= "      }";
		$this->salida .= "    }";
          $this->salida .= "  }else{";
          $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
          $this->salida .= "      if(frm.elements[i].type=='checkbox'){";
		$this->salida .= "        cadena=frm.elements[i].value;";
		$this->salida .= "        vector=cadena.split('/');";
		$this->salida .= "        quiro=vector[0];";
		$this->salida .= "        fecha=vector[1];";
		$this->salida .= "        if(quiro==valorQX){";
		$this->salida .= "          frm.elements[i].checked=false";
		$this->salida .= "        }";
		$this->salida .= "      }";
		$this->salida .= "    }";
		$this->salida .= "  }";
		$this->salida .= "}";
		$this->salida .= "</SCRIPT>";
		$this->Encabezado();
		$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "  <tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td align=\"center\">&nbsp;</td></tr>";
		$this->salida .= "  </table>";
		$this->salida .= "   <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "   <tr><td>";
		$_REQUEST['DiaEspe'];
		$this->salida.="\n".'<script>'."\n";
		$this->salida.='function year1(t)'."\n";
		$this->salida.='{'."\n";
		$this->salida.='window.location.href="Contenido.php?year="+t.elements[0].value+"&meses="+t.elements[1].value+"';
		foreach($_REQUEST as $v=>$v1)
		{
			if($v!='year' and $v!='meses' and $v!='DiaEspe')
			{
				if (is_array($v1)) {
						foreach($v1 as $k2=>$v2) {
							if (is_array($v2)) {
								foreach($v2 as $k3=>$v3) {
									if (is_array($v3)) {
										foreach($v3 as $k4=>$v4) {
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
		$this->salida .= "<table border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .='<tr align="center">';
		$this->salida .="<td class=\"label\">AÑO</td><td><select name=\"year\" onchange=\"year1(this.form)\" class=\"select\">";
		if(empty($_REQUEST['year']))
		{
			$a=explode("-",$_SESSION['CITASMES'][0]);
			$year=$_REQUEST['year']=$a[0];
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
			$a=explode("-",$_SESSION['CITASMES'][0]);
			if(empty($a[0]))
			{
				$mes=$_REQUEST['meses']=date("m");
				$year=date("Y");
			}
			else
			{
				$mes=$_REQUEST['meses']=$a[1];
			}
			$this->MesesAgenda(True,$year,$mes);
		}
		else
		{
			$this->MesesAgenda(True,$year,$_REQUEST['meses']);
			$mes=$_REQUEST['meses'];
		}
		$this->salida .= "</select>";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .='</form>';
		$_REQUEST['metodo']='RealizarReservaQuirofano';
		$_REQUEST['modulo']='Quirurgicos';
		$_REQUEST['contenedor']='app';
		$_REQUEST['tipo']='user';
		$this->ReturnMetodoExterno('app', 'Agenda', 'user', 'CalendarioEstandard');
		$this->salida .= "   </td></tr>";
		$this->salida .= "   </table><BR><BR>";
		$rango=ModuloGetVar('app', 'Quirurgicos','RangoTurnosQuirofano');
		$accion=ModuloGetURL('app','Quirurgicos','user','LlamaTipoReservaEspecial');
		$this->salida .= "   <form name=\"forma\" action=\"$accion\" method=\"post\">";
		if(empty($_REQUEST['DiaEspe'])){
		  $FechaEspe=date('Y-m-d');
		}else{
			$cadena=explode('-',$_REQUEST['DiaEspe']);
			$anoP=$cadena[0];
			$mesP=$cadena[1];
			$diaP=$cadena[2];
          if(date("Y-m-d",mktime(0,0,0,$mesP,$diaP,$anoP))<date("Y-m-d",mktime(0,0,0,date("mes"),date("d"),date("Y")))){
			$FechaEspe=date('Y-m-d');
          }else{
			$FechaEspe=$_REQUEST['DiaEspe'];
			}
		}
		$cadena=explode('-',$FechaEspe);
		$anoProgram=$cadena[0];
		$mesProgram=$cadena[1];
		$diaProgram=$cadena[2];
		$FechaConver=mktime(0,0,0,$mesProgram,$diaProgram,$anoProgram);
		$SalasCirugia=$this->SeleccionQuirofanosDpto();
		if($SalasCirugia){
               $this->salida .= "   <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
               $colspan=sizeof($SalasCirugia)*2;
               $this->salida .= "   <tr><td class=\"modulo_table_list_title\" align=\"center\" colspan=\"$colspan\">PROGRAMACIONES DE CIRUGIAS PARA EL DIA&nbsp;&nbsp;".strftime("%A %d de  %B de %Y",$FechaConver)."</td></tr>";
               $this->salida .= "   <tr>";
			for($i=0;$i<sizeof($SalasCirugia);$i++){
                    $Quiro=$SalasCirugia[$i]['quirofano'];
                    $abreviatura=$SalasCirugia[$i]['abreviatura'];
				$this->salida .= "   <td align=\"center\" class=\"modulo_table_list_title\">$abreviatura</td>";
          		$this->salida .= "   <td width=\"5%\" align=\"center\" class=\"modulo_table_list_title\"><input type=\"checkbox\" name=\"$Quiro\" value=\"$Quiro\" onclick=\"LimpiaCheck(this.form,this.checked,this.value,'$rango')\"></td>";
			}
			$this->salida .= "   </tr>";
          	if($tipoHorario=='Completo'){
			$HoraInincio='0';
			$MinutosInicio='0';
			$FechaUni=$this->FechaStamp($FechaEspe);
			$infoCadena = explode ('/', $FechaUni);
			$dia=$infoCadena[0];
			$mes=$infoCadena[1];
			$ano=$infoCadena[2];
			$SumaInicio=date("Y-m-d H:i:s",mktime($HoraInincio,$MinutosInicio,0,$mes,$dia,$ano));
			$SumaFinal=date('Y-m-d H:i:s',mktime(($HoraInincio+24),$MinutosInicio,0,$mes,$dia,$ano));
			$SumaHora=$SumaInicio;
		}else{
               $rangoInicio=ModuloGetVar('app', 'Quirurgicos','RangoInicioTurnoQuirofano');
			$rangoDuracion=ModuloGetVar('app', 'Quirurgicos','RangoDuracionTurnoQuirofano');
			$cadena=explode(':',$rangoInicio);
			$HoraInincio=$cadena[0];
			$MinutosInicio=$cadena[1];
			$FechaUni=$this->FechaStamp($FechaEspe);
			$infoCadena = explode ('/', $FechaUni);
			$dia=$infoCadena[0];
			$mes=$infoCadena[1];
			$ano=$infoCadena[2];
			$SumaInicio=date("Y-m-d H:i:s",mktime($HoraInincio,$MinutosInicio,0,$mes,$dia,$ano));
			$SumaFinal=date('Y-m-d H:i:s',mktime(($HoraInincio+$rangoDuracion),$MinutosInicio,0,$mes,$dia,$ano));
			$SumaHora=$SumaInicio;
		}
		while($SumaHora<$SumaFinal){
			if($y % 2){$y++;}
      		$HoraMosDef=$this->HoraStamp($SumaHora);
			$infoCadena = explode (':',$HoraMosDef);
			$HoraMos=$infoCadena[0];
			$MinutosMos=$infoCadena[1];
			$this->salida .= "   <tr>";
			for($i=0;$i<sizeof($SalasCirugia);$i++){
               	if($y % 2){$estilo='hc_table_submodulo';}else{$estilo='modulo_list_claro';}
					$Quiro=$SalasCirugia[$i]['quirofano'];
			  		$abreviatura=$SalasCirugia[$i]['abreviatura'];
					$comprobacion=$this->ComprobarExisReserva($Quiro,$SumaHora,$rango,'0','0','0','0');
               	if($comprobacion==1){
                    	$actionVer=ModuloGetURL('app','Quirurgicos','user','VerDetalleSobreReserva',array("Quiro"=>$Quiro,"SumaHora"=>$SumaHora,"DiaEspe"=>$_REQUEST['DiaEspe']));
               		$this->salida .= " <td align=\"left\" class=\"modulo_list_oscuro\"><a href=\"$actionVer\" class=\"link\">$HoraMos : $MinutosMos</a></td>\n";
                         $javaInfoQX = "javascript:MostrarCapa('ContenedorCapaInfo');Iniciar('INFORMACION DE RESERVA','".$Quiro."','ContenedorCapaInfo');CargarContenedor('ContenedorCapaInfo');BusquedaDetalle('".$Quiro."','".$SumaHora."','".$_REQUEST['DiaEspe']."','1')";
                         $this->salida .= " <td width=\"5%\" align=\"center\" class=\"modulo_list_oscuro\"><a href=\"$javaInfoQX\"><img border=\"0\" src=\"".GetThemePath()."/images/infor.png\" title=\"Ver detalle de la reserva\"></a></td>\n";
				}else{
					$this->salida .= " <td align=\"left\" class=\"$estilo\">$HoraMos : $MinutosMos</td>\n";
					$this->salida .= " <td width=\"5%\" align=\"center\" class=\"$estilo\"><input type=\"checkbox\" name=\"seleccionReserv[]\" value=\"$Quiro/$SumaHora\" onclick=\"IntervalosCheck(this.form,this.value,'$rango')\"></td>\n";
				}
				$y++;
			}
               $this->salida .= "   </tr>";
               $Fecha=$this->FechaStamp($SumaHora);
               $infoCadena = explode ('/', $Fecha);
               $dia=$infoCadena[0];
               $mes=$infoCadena[1];
               $ano=$infoCadena[2];
               $HoraDef=$this->HoraStamp($SumaHora);
               $infoCadena = explode (':',$HoraDef);
               $Hora=$infoCadena[0];
               $Minutos=$infoCadena[1];
			$SumaHora=date('Y-m-d H:i:s',mktime($Hora,($Minutos+$rango),0,$mes,$dia,$ano));
		}

          //Capa de Informacion reserva de quirofano
          $this->salida.="<div id='ContenedorCapaInfo' class='d2Container' style=\"display:none\">";
          $this->salida .= "    <div id='tituloAnul' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida .= "    <div id='cerrarAnul' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorCapaInfo');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
          $this->salida .= "    <div id='errorAnul' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida .= "    <div id='ContenidoCapaInfo'>\n";
          $this->salida .= "    </div>\n";     
          $this->salida.="</div>";
          //Capa de Informacion reserva de quirofano
          
		$this->salida .= "   </table><BR>";
		}
		$this->salida .= "   <input type=\"hidden\" name=\"rango\" value=\"$rango\">";
		$this->salida .= "   <table border=\"0\" width=\"100%\" align=\"center\">";		
		$rep= new GetReports();
		$mostrar=$rep->GetJavaReport('app','EstacionEnfermeria_QX','programacionesQX_html',array('fechaConsulta'=>$FechaEspe,"Empresa"=>$_SESSION['LocalCirugias']['NombreEmp'],"CentroUtilidad"=>$_SESSION['LocalCirugias']['NombreCU'],"dpto"=>$_SESSION['LocalCirugias']['NombreDpto'],"departamento"=>$_SESSION['LocalCirugias']['departamento']),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
		$nombre_funcion=$rep->GetJavaFunction();
		$this->salida .=$mostrar;
		$this->salida .= "   <tr><td align=\"right\">";
		$this->salida .= "	 		</BR><a class=\"Menu\" href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR</a>";
		$this->salida .= "	</td></tr>";
		$this->salida .= "   </table>";		
		$this->salida .= "   <table border=\"0\" width=\"60%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "   <tr><td align=\"center\">";
		$this->salida .= "   <input type=\"submit\" class=\"input-submit\" name=\"Cancelar\" value=\"VOLVER\">";
		$this->salida .= "   <input type=\"submit\" class=\"input-submit\" name=\"Aceptar\" value=\"CREAR RESERVA\">";
		$this->salida .= "   </td></tr>";
		$this->salida .= "   </table>";
		$this->salida .= "</form>";
          $this->salida .= ThemeCerrarTabla();
		return true;
	}
/**
* Funcion que muestra los tipos de reservas que se pueden relizar para un quirofano
* @return boolean
*/
	function FormaTiposdeReservaQuirofano($DiaEspe,$seleccionReserv,$rango){
  
	  $this->salida.= ThemeAbrirTabla('RESERVA QUIROFANO');
		$this->Encabezado();
		$accion=ModuloGetURL('app','Quirurgicos','user','InsertarReservaquirofano',array("DiaEspe"=>$DiaEspe,"seleccionReserv"=>$seleccionReserv,"rango"=>$rango));
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"65%\" align=\"center\" class=\"normal_10\">";
    $this->salida .= "  <tr><td width=\"100%\">";
		$this->salida .= "  <fieldset><legend class=\"field\">MOTIVO RESERVA</legend>";
		$this->salida .= "  <br><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "  <tr><td colspan=\"4\" align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "  </td></tr>";
		$this->salida .= "	 <tr><td class=\"".$this->SetStyle("motivoReserva")."\">MOTIVO RESERVA: </td><td><select name=\"motivoReserva\" class=\"select\">";
		$MotivosReservas=$this->MotivosReservaQuirofano();
		$this->MostrasSelect($MotivosReservas,'False',$motivoReserva);
		$this->salida .= "   </select></td></tr>";
    $this->salida .= "  </table><br>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
    $this->salida .= "  <tr><td align=\"center\">";
    $this->salida .= "  <BR><input type=\"submit\" class=\"input-submit\" value=\"VOLVER\" name=\"regresar\">&nbsp&nbsp&nbsp;<input type=\"submit\" class=\"input-submit\" value=\"ACEPTAR\" name=\"aceptar\">";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table>";
		$this->salida .= "  </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
/**
* Funcion que muestra los datos que se requieren para realizar una reserva de un cliente
* @return boolean
*/
	function ReservaQuirofanoCliente($DiaEspe,$seleccionReserv,$rango){
	  $this->salida.= ThemeAbrirTabla('RESERVA QUIROFANO PARA UN CLIENTE');
		$mostrar=ReturnClassBuscador('proveedores','','','forma','');
		$this->salida .=$mostrar;
		$this->salida .="</script>\n";
		$this->Encabezado();
		$accion=ModuloGetURL('app','Quirurgicos','user','InsertarReservaCliente',array("DiaEspe"=>$DiaEspe,"seleccionReserv"=>$seleccionReserv,"rango"=>$rango));
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\" class=\"normal_10\">";
    $this->salida .= "  <tr><td width=\"100%\">";
		$this->salida .= "  <fieldset><legend class=\"field\">SELECCION CLIENTE</legend>";
		$this->salida .= "  <br><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "  <tr><td colspan=\"4\" align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "  </td></tr>";
		$this->salida .= "    <tr><td width=\"30%\" class=\"".$this->SetStyle("tercero")."\">TIPO</td>";
		$this->salida .= "    <td width=\"15%\"><input type=\"text\" name=\"tipoTerceroId\" size=\"4\" class=\"input-text\" value=\"".$_POST['tipoTerceroId']."\" READONLY></td>";
		$this->salida .= "    <td class=\"".$this->SetStyle("tercero")."\">CÓDIGO</td>";
		$this->salida .= "    <td><input type=\"text\" name=\"codigo\" size=\"33\" class=\"input-text\" value=\"".$_POST['codigo']."\" READONLY></td>";
		$this->salida .= "    </tr>";
		$this->salida .= "    <tr><td width=\"30%\" class=\"".$this->SetStyle("tercero")."\">CLIENTE</td>";
		$this->salida .= "    <td width=\"30%\"><input type=\"text\" name=\"nombre\" size=\"48\" class=\"input-text\" value=\"".$_POST['nombre']."\" READONLY></td>";
		$this->salida .= "    <td align=\"center\"><input type=\"button\" name=\"proveedor\" value=\"BUSCAR\" onclick=abrirVentana() class=\"input-submit\"></td>";
		$this->salida .= "    </tr>";
		$this->salida .= "    <tr><td width=\"30%\"><BR><BR></td></tr>";
		$this->salida .= "	  <tr><td class=\"label\" colspan=\"2\">OBSERVACIONES</td></tr><tr><td colspan=\"2\"><textarea name=\"observacion\" cols=\"65\" rows=\"3\" class=\"textarea\">$observacion</textarea></td></tr>";
    $this->salida .= "  	</table><br>";
		$this->salida .= "  	</fieldset>";
		$this->salida .= "  </td></tr>";
    $this->salida .= "  <tr><td align=\"center\">";
    $this->salida .= "  <BR><input type=\"submit\" class=\"input-submit\" value=\"VOLVER\" name=\"regresar\">&nbsp&nbsp&nbsp;<input type=\"submit\" class=\"input-submit\" value=\"ACEPTAR\" name=\"aceptar\">";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table>";
		$this->salida .= "  </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
/**
* Funcion que muestra los datos que se requieren para realizar una reserva del quirofano de un plan
* @return boolean
*/
	function ReservaQuirofanoPlan($DiaEspe,$seleccionReserv,$rango){
    $this->salida.= ThemeAbrirTabla('RESERVA QUIROFANO PARA UN PLAN');
		$this->Encabezado();
		$accion=ModuloGetURL('app','Quirurgicos','user','InsertarReservaPlan',array("DiaEspe"=>$DiaEspe,"seleccionReserv"=>$seleccionReserv,"rango"=>$rango));
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"55%\" align=\"center\" class=\"normal_10\">";
    $this->salida .= "  <tr><td width=\"100%\">";
		$this->salida .= "  <fieldset><legend class=\"field\">SELECCION PLAN</legend>";
		$this->salida .= "  <br><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "  <tr><td colspan=\"4\" align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "  </td></tr>";
		$this->salida .= "	 <tr><td width=\"30%\" class=\"".$this->SetStyle("plan")."\">NOMBBRE DEL PLAN: </td><td><select name=\"plan\" class=\"select\">";
		$Planes=$this->CallMetodoExterno('app','Triage','user','responsables');
		$this->MostrarResponsable($Planes,'False',$plan);
		$this->salida .= "   </select></td></tr>";
		$this->salida .= "	 <tr><td class=\"label\" colspan=\"2\">OBSERVACIONES</td></tr><tr><td colspan=\"2\"><textarea name=\"observacion\" cols=\"65\" rows=\"3\" class=\"textarea\">$observacion</textarea></td></tr>";
    $this->salida .= "  </table><br>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
    $this->salida .= "  <tr><td align=\"center\">";
    $this->salida .= "  <BR><input type=\"submit\" class=\"input-submit\" value=\"VOLVER\" name=\"regresar\">&nbsp&nbsp&nbsp;<input type=\"submit\" class=\"input-submit\" value=\"ACEPTAR\" name=\"aceptar\">";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table>";
		$this->salida .= "  </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
/**
* Funcion que realiza una consulta de una reserva de un quirofano
* @return boolean
*/
	function FormaConsultaRangoReserva($programaciones,$horario,$quirofano,$DiaEspe){
    $this->salida.= ThemeAbrirTabla('RESERVA QUIROFANO PARA UN PLAN');
		$this->Encabezado();
		$accion=ModuloGetURL('app','Quirurgicos','user','accionConsultaReservas',array("DiaEspe"=>$DiaEspe));
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		if(sizeof($programaciones)){
		  $this->salida .= "          <table border=\"0\" width=\"90%\" align=\"center\">";
		  $this->salida .= "          <tr><td class=\"modulo_list_claro\">";
			$this->salida .= "          <BR><table border=\"0\" cellspacing=\"3\" cellpadding=\"3\" width=\"95%\" align=\"center\" class=\"normal_10\">";
			$this->salida .= "          <tr class=\"modulo_table_list_title\">";
			$quiro=$this->DescripcionQuirofano($quirofano);
			$this->salida .= "          <td colspan=\"4\">PROGRAMACIONES CREADAS EN EL RANGO DE TIEMPO $horario EN EL ".$quiro['descripcion']."</td>";
      $this->salida .= "          </tr>";
			for($i=0;$i<sizeof($programaciones);$i++){
			  $datosReserva=$this->DatosReservaGeneral($programaciones[$i]['qx_quirofano_programacion_id']);
        if(empty($programaciones[$i]['programacion_id'])){
          $this->salida .= "      <tr class=\"modulo_list_oscuro\"><td width=\"20%\" class=\"label\">TIPO DE RESERVA</td><td colspan=\"3\">".$datosReserva[$i]['descripcion']."</td></tr>";
					$this->salida .= "      <tr class=\"modulo_list_oscuro\"><td width=\"20%\" class=\"label\">HORA INICIO RESERVA</td><td>".$datosReserva[$i]['hora_inicio']."</td><td width=\"20%\" class=\"label\">HORA FIN RESERVA</td><td>".$datosReserva[$i]['hora_fin']."</td></tr>";
					if($datosReserva[$i]['qx_tipo_reserva_quirofano_id'] !=1 && $datosReserva[$i]['qx_tipo_reserva_quirofano_id']!=2){
            $actionCancel=ModuloGetURL('app','Quirurgicos','user','CancelarReservaGeneral',array("programacionQuiro"=>$programaciones[$i]['qx_quirofano_programacion_id'],"horario"=>$horario,"quirofano"=>$quirofano,"DiaEspe"=>$DiaEspe));
						$this->salida .="     <tr class=\"modulo_list_oscuro\"><td align=\"right\" colspan=\"4\" ><a href=\"$actionCancel\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"><a></td></tr>";
					}
					if($datosReserva[$i]['qx_tipo_reserva_quirofano_id']==1){
            $this->salida .= "    <tr class=\"modulo_list_oscuro\"><td colspan=\"4\">";
					  $this->salida .= "      <BR><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
            $this->salida .= "      <tr class=\"modulo_table_list_title\"><td colspan=\"2\">RESPONSABLE DE LA RESERVA</td></tr>";
						$tercero=$this->NombreTercero($datosReserva[$i]['tipo_id_tercero'],$datosReserva[$i]['tercero_id']);
            $this->salida .= "      <tr class=\"modulo_list_claro\"><td width=\"20%\" class=\"label\">CLIENTE</td><td>".$tercero['nombre_tercero']."</td></tr>";
						$this->salida .= "      <tr class=\"modulo_list_claro\"><td width=\"20%\" class=\"label\">OBSERVACIONES</td><td>".$datosReserva[$i]['observacion']."</td></tr>";
						$actionCancel=ModuloGetURL('app','Quirurgicos','user','CancelarReservaGeneral',array("programacionQuiro"=>$programaciones[$i]['qx_quirofano_programacion_id'],"horario"=>$horario,"quirofano"=>$quirofano,"DiaEspe"=>$DiaEspe));
						$this->salida .= "      <tr class=\"modulo_list_claro\"><td align=\"right\" colspan=\"2\" ><a href=\"$actionCancel\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"><a></td></tr>";
						$this->salida .= "      </table><BR>";
						$this->salida .= "    </td></tr>";
					}
					if($datosReserva[$i]['qx_tipo_reserva_quirofano_id']==2){
					  $this->salida .= "    <tr class=\"modulo_list_oscuro\"><td colspan=\"4\">";
					  $this->salida .= "      <BR><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
            $this->salida .= "      <tr class=\"modulo_table_list_title\"><td colspan=\"2\">RESPONSABLE DE LA RESERVA</td></tr>";
						$NombrePlan=$this->PlanNombre($datosReserva[$i]['plan_id']);
            $this->salida .= "      <tr class=\"modulo_list_claro\"><td class=\"label\" width=\"20%\">PLAN</td><td>$NombrePlan</td></tr>";
						$this->salida .= "      <tr class=\"modulo_list_claro\"><td class=\"label\" width=\"20%\">OBSERVACIONES</td><td>".$datosReserva[$i]['observacionplan']."</td></tr>";
						$actionCancel=ModuloGetURL('app','Quirurgicos','user','CancelarReservaGeneral',array("programacionQuiro"=>$programaciones[$i]['qx_quirofano_programacion_id'],"horario"=>$horario,"quirofano"=>$quirofano,"DiaEspe"=>$DiaEspe));
						$this->salida .= "      <tr class=\"modulo_list_claro\"><td align=\"right\" colspan=\"2\" ><a href=\"$actionCancel\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"><a></td></tr>";
						$this->salida .= "      </table><BR>";
						$this->salida .= "    </td></tr>";
					}
				}else{
          $datosReservaPac=$this->DatosReservaGeneralPaciente($programaciones[$i]['qx_quirofano_programacion_id']);
					$this->salida .= "      <tr class=\"modulo_list_oscuro\"><td colspan=\"4\">";
					$this->salida .= "        <BR><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
					$this->salida .= "        <tr class=\"modulo_table_list_title\"><td colspan=\"4\">PROGRAMACION DE LA RESERVA</td></tr>";
					$this->salida .= "        <tr class=\"modulo_list_claro\"><td width=\"20%\" class=\"label\">CODIGO PROGRAMACION</td><td colspan=\"3\">".$datosReservaPac['programacion_id']."</td></tr>";
					$this->salida .= "        <tr class=\"modulo_list_claro\"><td width=\"20%\" class=\"label\">HORA INICIO RESERVA</td><td>".$datosReservaPac['hora_inicio']."</td><td width=\"20%\" class=\"label\">HORA FIN RESERVA</td><td>".$datosReservaPac['hora_fin']."</td></tr>";
          $this->salida .= "        <tr class=\"modulo_list_claro\"><td width=\"20%\" class=\"label\">TIPO ID. PACIENTE</td><td>".$datosReservaPac['tipo_id_paciente']."</td><td width=\"20%\" class=\"label\">NUMERO ID. PACIENTE</td><td>".$datosReservaPac['paciente_id']."</td></tr>";
					$Nombres=$this->BuscarNombresPaciente($datosReservaPac['tipo_id_paciente'],$datosReservaPac['paciente_id']);
			    $Apellidos=$this->BuscarApellidosPaciente($datosReservaPac['tipo_id_paciente'],$datosReservaPac['paciente_id']);
					$this->salida .= "        <tr class=\"modulo_list_claro\"><td width=\"20%\" class=\"label\">NOMBRE DEL PACIENTE</td><td colspan=\"3\">$Nombres $Apellidos</td></tr>";
					$actionConsultaa=ModuloGetURL('app','Quirurgicos','user','ConsultaProgamacionPacQX',array("ProgramacionId"=>$datosReservaPac['programacion_id'],"DiaEspe"=>$_REQUEST['DiaEspe']));
					$this->salida .= "        <tr class=\"modulo_list_claro\"><td align=\"right\" colspan=\"4\" ><a href=\"$actionConsultaa\"><img border=\"0\" src=\"".GetThemePath()."/images/pconsultar.png\"><a></td></tr>";
					$this->salida .= "        </table><BR>";
					$this->salida .= "      </td></tr>";
				}
			}
			$this->salida .= "          </table><BR>";
			$this->salida .= "        </td></tr>";
      $this->salida .= "        </table><br>";
		}else{
      $this->salida .= "    <table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
			$this->salida .= "    <tr><td class=\"label_error\" align=\"center\">Se Eliminaron las Programaciones en este rango de tiempo, De Click en Regresar</td></tr>";
			$this->salida .= "    </table>";
		}
		$this->salida .= "    <table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
    $this->salida .= "    <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" value=\"VOLVER\" name=\"regresar\"></td></tr>";
    $this->salida .= "    </table>";
		$this->salida .= "  </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function DatosCancelacionProgramacion($mayorFecha){
	     $this->salida.= ThemeAbrirTabla('CANCELACION PROGRAMACION No. '.$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']);
          $this->Encabezado();
          $accion=ModuloGetURL('app','Quirurgicos','user','CancelacionProgramacionQX',array("mayorFecha"=>$mayorFecha));
          $this->salida .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
          $this->salida .= "  <table border=\"0\" width=\"65%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "  <tr><td width=\"100%\">";
          $this->salida .= "  <fieldset><legend class=\"field\">MOTIVO CANCELACION PROGRAMACION</legend>";
          $this->salida .= "  <br><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
          $this->salida .= "  <tr><td colspan=\"4\" align=\"center\">";
          $this->salida .= $this->SetStyle("MensajeError");
          $this->salida .= "  </td></tr>";
          $this->salida .= "	 <tr><td class=\"".$this->SetStyle("motivoCancel")."\">MOTIVO CANCELACION: </td><td><select name=\"motivoCancel\" class=\"select\">";
          $Motivos=$this->MotivosCancelacionProgramacion();
          $this->MostrasSelect($Motivos,'False',$motivoCancel);
          $this->salida .= "   </select></td></tr>";
          $this->salida .= "	 <tr><td class=\"label\" colspan=\"2\">OBSERVACIONES</td></tr><tr><td colspan=\"2\"><textarea name=\"observacion\" cols=\"65\" rows=\"3\" class=\"textarea\">$observacion</textarea></td></tr>";
		$this->salida .= "  </table><br>";
          $this->salida .= "  </fieldset>";
          $this->salida .= "  </td></tr>";
          $this->salida .= "  <tr><td align=\"center\">";
          $this->salida .= "  <BR><input type=\"submit\" class=\"input-submit\" value=\"VOLVER\" name=\"regresar\">&nbsp&nbsp&nbsp;<input type=\"submit\" class=\"input-submit\" value=\"ACEPTAR\" name=\"aceptar\">";
          $this->salida .= "  </td></tr>";
          $this->salida .= "  </table>";
		$this->salida .= "<br>";
          $this->salida .= "</form>";
          $this->salida .= ThemeCerrarTabla();
          return true;
	}

	function FommaReservaTurnosInstrumentadores(){
    $this->salida.= ThemeAbrirTabla('RESERVA TURNOS INSTRUMENTADORES EN LOS QUIROFANOS');
		$this->Encabezado();
		//$accion=ModuloGetURL('app','Quirurgicos','user','CancelacionProgramacionQX',array("mayorFecha"=>$mayorFecha));
		$this->salida .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
		$SalasCirugia=$this->SeleccionQuirofanosDpto();
    if($SalasCirugia){
			$this->salida .= "   <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
			$colspan=sizeof($SalasCirugia)*2;
			$this->salida .= "   <tr><td class=\"modulo_table_list_title\" align=\"center\" colspan=\"$colspan\">QUIROFANOS</td></tr>";
			$this->salida .= "   <tr>";
			for($i=0;$i<sizeof($SalasCirugia);$i++){
				$Quiro=$SalasCirugia[$i]['quirofano'];
				$abreviatura=$SalasCirugia[$i]['abreviatura'];
				$this->salida .= "   <td align=\"center\" class=\"modulo_table_list_title\">$abreviatura</td>";
				$this->salida .= "   <td width=\"5%\" align=\"center\" class=\"modulo_table_list_title\"><input type=\"checkbox\" name=\"$Quiro\" value=\"$Quiro\"></td>";
			}
			$this->salida .= "   </tr>";
			$this->salida .= "   </table>";
		}
		$this->salida .= "</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function Reserva_Insumos_qx($grupo,$clasePr,$subclase,$codigoPro,$descripcionPro){

		$ProgramacionId=$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO'];
		$this->salida .= ThemeAbrirTabla('INSUMOS Y MATERIAL DE OSTEOSINTESIS REQUERIDOS PARA LA CIRUGIA');
		$accion=ModuloGetURL('app','Quirurgicos','user','InsertarInsumosQX',array("conteo"=>$this->conteo,"paso"=>$_REQUEST['paso'],"Of"=>$_REQUEST['Of']));
		$this->Encabezado();
		$this->salida .= "    <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "         <table class=\"modulo_table_list\" border=\"0\" width=\"85%\" align=\"center\" >";
		$this->salida .= "         <tr><td colspan=\"2\" class=\"modulo_table_list_title\">FILTROS DE BUSQUEDA</td></tr>";
    $this->salida .= "         <tr><td class=\"modulo_list_claro\" width=\"60%\">";
		$this->salida .= "         <BR><table class=\"normal_10\" border=\"0\" width=\"90%\" align=\"center\" >";
    $this->salida .= "	  	   <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	       <td class=\"".$this->SetStyle("grupo")."\">GRUPO: </td><td><select name=\"grupo\" class=\"select\">";
		$grupos=$this->GruposProductos();
		$this->Mostrar($grupos,'False',$grupo);
		$this->salida .= "         </select></td>";
		$this->salida .= "	  	   </tr>";
		if(empty($grupo) || $grupo==-1){
      $vargru='disabled';
		}
		$this->salida .= "		     <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	       <td class=\"".$this->SetStyle("clasePr")."\">CLASE: </td><td><select name=\"clasePr\" class=\"select\" $vargru>";
		$clasesPr=$this->ClaseProductos($grupo);
		$this->MostrasSelect($clasesPr,'False',$clasePr);
		$this->salida .= "         </select></td>";
		$this->salida .= "		     </tr>";
		if(empty($clasePr) || $clasePr==-1){
      $varcla='disabled';
		}
		$this->salida .= "		     <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	       <td class=\"".$this->SetStyle("subclase")."\">SUBCLASE: </td><td><select name=\"subclase\" class=\"select\" $varcla>";
		$subclases=$this->SubClaseProductos($grupo,$clasePr);
		$this->MostrasSelect($subclases,'False',$subclase);
		$this->salida .= "         </select></td>";
		$this->salida .= "		     </tr>";
		$this->salida .= "		     </table><BR><BR>";
    $this->salida .= "		     </td>";
    $this->salida .= "		     <td valign=\"top\" class=\"modulo_list_claro\" width=\"40%\"> ";
		$this->salida .= "         <BR><table class=\"normal_10\" border=\"0\" width=\"90%\" align=\"center\" >";
    $this->salida .= "		     <tr class=\"modulo_list_oscuro\"><td class=\"label\">CODIGO</td><td><input type=\"text\" class=\"input-text\" name=\"codigoPro\" value=\"$codigoPro\"></td></tr>";
		$this->salida .= "		     <tr class=\"modulo_list_oscuro\"><td class=\"label\">DESCRIPCION</td><td><input type=\"text\" class=\"input-text\" name=\"descripcionPro\" value=\"$descripcionPro\"></td></tr>";
		$this->salida .= "		     </table><BR>";
		$this->salida .= "         </td></tr>";
		$this->salida .= "         <tr><td colspan=\"2\" align=\"center\" class=\"modulo_list_claro\"><input type=\"submit\" class=\"input-submit\" value=\"FILTRAR\" name=\"buscar\"></td></tr>";
    $this->salida .= "		     </table><BR>";
		$this->salida .= "         <table class=\"normal_10\"  border=\"0\" width=\"85%\" align=\"center\">";
		$this->salida .= "       <tr><td>&nbsp&nbsp;</td></tr>";
		$this->salida .= "       </td></tr>";
		$this->salida .= "			 </table><BR>";
		if(empty($grupo)){$cont=1;
			foreach($grupos as $gr=>$des){
        if($cont==1){$grupo=$gr;$cont=0;}
			}
		}
		$TotalInventario=$this->TotalInventarioProductosInv($grupo,$clasePr,$subclase,$codigoPro,$descripcionPro);
		if($TotalInventario){
		$this->salida .= "			 <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"85%\" align=\"center\" class=\"modulo_table_list\">";
    $this->salida .= "      <tr class=\"modulo_table_list_title\" align=\"center\">";
		$this->salida .= "			  <td>CODIGO</td>";
    $this->salida .= "        <td>DESCRIPCION</td>";
		$this->salida .= "        <td>&nbsp;</td>";
		$this->salida .= "        <td>&nbsp;</td>";
		$this->salida .= "       </tr>";
		$y=0;
		for($i=0;$i<sizeof($TotalInventario);$i++){
		  if($y % 2){
			  $estilo='modulo_list_claro';
			}else{
			  $estilo='modulo_list_oscuro';
			}
			$this->salida .= "			<tr class=\"$estilo\">";
			$this->salida .= "       <td>".$TotalInventario[$i]['codigo_producto']."</td>";
			$this->salida .= "				<td width=\"60%\">".$TotalInventario[$i]['descripcion']."</td>";
			$varchecked='';
			$cantidad='';
			if(empty($_REQUEST['paso'])){
				$_REQUEST['paso']=1;
			}
			if($_SESSION['ARREGLO']['INSUMOS'][$_REQUEST['paso']][$TotalInventario[$i]['codigo_producto']]==1){
        $cantidad=$_SESSION['ARREGLO']['INSUMOSUNO']['CATIDAD'][$_REQUEST['paso']][$TotalInventario[$i]['codigo_producto']];
        $varchecked='checked';
			}
			$this->salida .= "		    <td width=\"5%\"><input class=\"input-text\" type=\"text\" value=\"$cantidad\" name=\"cantidadInsumos[".$TotalInventario[$i]['codigo_producto']."]\" size=\"8\"></td>";
			$this->salida .= "		    <td width=\"5%\" align=\"center\"><input type=\"checkbox\" value=\"".$TotalInventario[$i]['codigo_producto']."\" name=\"seleccion[]\" $varchecked></td>";
			$this->salida .= "      </tr>";
			$y++;
		}
		$this->salida .="          </table>";
		$this->salida .= "        <table border=\"0\" width=\"85%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "        <tr><td align=\"right\">";
		$this->salida .= "        <input type=\"submit\" class=\"input-submit\" name=\"GuardarCant\" value=\"GUARDAR CANTIDAD\">";
		$this->salida .= "        </td></tr>";
		$this->salida .="         </table>";
		$this->salida .=$this->RetornarBarra(1);
		$this->salida .= "			   <BR>";
		}else{
      $this->salida .= "         <table class=\"normal_10N\"  border=\"0\" width=\"85%\" align=\"center\">";
		  $this->salida .= "         <tr><td  align=\"center\" class=\"label_error\">NO SE ENCONTRARON DATOS CON ESTOS PARAMETROS DE BUSQUEDA</td></tr>";
      $this->salida .= "			   </table><BR>";
		}
		$InsumosQX=$this->InsumosInsertadosRequeridos();
		if($InsumosQX){
			$this->salida .= "    <BR><table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">";
			$this->salida .= "		<td colspan=\"3\">INSUMOS REQUERIDOS INSERTADOS</td>";
			$this->salida .= "		</tr>";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">";
			$this->salida .= "		<td>INSUMO</td>";
			$this->salida .= "		<td width=\"5%\">CANTIDAD</td>";
			$this->salida .= "		<td width=\"5%\">&nbsp;</td>";
			$this->salida .= "		</tr>";
			for($i=0;$i<sizeof($InsumosQX);$i++){
				if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
				$this->salida .= "		<tr class=\"$estilo\">";
				$this->salida .= "		<td>".$InsumosQX[$i]['descripcion']."</td>";
				$this->salida .= "		<td>".$InsumosQX[$i]['cantidad']."</td>";
				$actionEliminar=ModuloGetURL('app','Quirurgicos','user','EliminarInsumoQXInsertado',array("insumo"=>$InsumosQX[$i]['codigo_producto'],"grupo"=>$grupo,"clasePr"=>$clasePr,"subclase"=>$subclase,"codigoPro"=>$codigoPro,"descripcionPro"=>$descripcionPro));
				$this->salida .= "		<td><a href=\"$actionEliminar\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"><a></td>";
				$this->salida .= "		</tr>";
			}
			$this->salida .= "    </table><BR>";
		}
		$this->salida .= "    <table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "    <tr>";
		$this->salida .= "    <td align=\"center\">";
		$this->salida .= "    <input type=\"submit\" class=\"input-submit\" name=\"Salir\" value=\"VOLVER\">";
		//$this->salida .= "    <input type=\"submit\" class=\"input-submit\" name=\"SalirGuardar\" value=\"SALIR\">";
		$this->salida .= "    </td></tr>";
		$this->salida .= "    </table>";
		$this->salida .= "		</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function LlamaReserva_Sangre_qx($tipoIdPac,$PacienteId){

		$this->salida .= ThemeAbrirTabla('RESERVA DE COMPONENTES SANGUINEOS');
		$accion=ModuloGetURL('app','Quirurgicos','user','LlamaReserva_Sangre_qxRegreso',array("tipoIdPac"=>$tipoIdPac,"PacienteId"=>$PacienteId));
		$this->salida .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
    $datosReserva=$this->DatosdelaReservaSangre($tipoIdPac,$PacienteId);
		if($datosReserva){
		$inicio=0;
		$y1=0;
		$ingresoAnt=-1;
		foreach($datosReserva as $solicitud=>$vector){
      foreach($vector as $componente=>$vector1){
			  foreach($vector1 as $ingreso=>$datos){
				  if($ingreso!=$ingresoAnt){
				  if($inicio==0){
					  $this->salida .= "    <table border=\"0\" width=\"70%\" align=\"center\" class=\"normal_10\">";
						$this->salida .= "    <tr><td></td></tr>";
						$this->salida .= "    <tr><td><fieldset><legend class=\"field\">DATOS PACIENTE Y RESERVA</legend>";
						$this->salida .= "    <table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
						$this->salida .= "    <tr class=\"modulo_list_claro\">";
						$this->salida .= "    <td class=\"label\">PACIENTE</td><td>$tipoIdPac $PacienteId ".$datos['nombre']."</td>";
						$EdadArr=CalcularEdad($datos['fecha_nacimiento'],$FechaFin);
						$this->salida .= "    <td class=\"label\">EDAD</td><td>".$EdadArr['edad_aprox']."</td>";
						$this->salida .= "    </tr>";
						$this->salida .= "    <tr class=\"modulo_list_claro\">";
						$this->salida .= "    <td class=\"label\">FACTOR RH</td><td>".$datos['grupo_sanguineo']." ".$datos['rh']."</td>";
						if($datos[0]['transfuciones_ant']){$var='Si';}else{$var='NINGUNA';}
						$this->salida .= "    <td class=\"label\">TRANSFUSIONES ANTERIORES</td><td>$var</td>";
						$this->salida .= "    </tr>";
						$this->salida .= "    </table>";
						$this->salida .= "		</fieldset></td></tr></table><BR>";
						$this->salida .= "    <table border=\"0\" width=\"70%\" align=\"center\" class=\"normal_10\">";
						$this->salida .= "    <tr class=\"modulo_table_title\"><td align=\"center\" colspan=\"6\">RESERVAS ACTIVAS DEL PACIENTE</td></tr>";
						$this->salida .= "    <tr class=\"modulo_table_title\">";
						$this->salida .= "    <td>No. RESERVA</td>";
						$this->salida .= "    <td>FECHA</td>";
						$this->salida .= "    <td>COMPONENTE</td>";
						$this->salida .= "    <td>CANTIDAD</td>";
						$this->salida .= "    <td>CONFIRMADA</td>";
						$this->salida .= "    <td>CRUCES</td>";
						$this->salida .= "    </tr>";
						$inicio=1;
					}
					if($y1 % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
					if($datos['solicitud_reserva_sangre_id']!=$solicitudAnt){
						$this->salida .= "    <tr class=\"$estilo\">";
						$mult=sizeof($vector)*sizeof($vector1);
						$this->salida .= "    <td rowspan=\"".$mult."\">".$datos['solicitud_reserva_sangre_id']."</td>";
						$this->salida .= "    <td rowspan=\"".$mult."\">".$datos['fecha']."</td>";
						if($datos['sw_cruze']==1){
							if($datos['componente']!=$componenteAnt){
								$this->salida .= "    <td rowspan=\"".sizeof($vector1)."\">".$datos['componente']."</td>";
								$this->salida .= "    <td rowspan=\"".sizeof($vector1)."\">".$datos['cantidad_componente']."</td>";
								if($datos['sw_estado']=='1'){$var='No';}elseif($datos['sw_estado']=='2'){$var='Si';	}
								$this->salida .= "    <td rowspan=\"".sizeof($vector1)."\">$var</td>";
								$this->salida .= "    <td>".$datos['bolsa_id']."</td>";
								$this->salida .= "    </tr>";
								$componenteAnt=$datos['componente'];
							}else{
								$this->salida .= "    <tr class=\"$estilo\">";
								$this->salida .= "    <td>".$datos['bolsa_id']."</td>";
								$this->salida .= "    </tr>";
							}
						}else{
							$this->salida .= "    <td>".$datos['componente']."</td>";
							$this->salida .= "    <td>".$datos['cantidad_componente']."</td>";
							if($datos['sw_estado']=='1'){$var='No';}elseif($datos['sw_estado']=='2'){$var='Si';	}
							$this->salida .= "    <td>$var</td>";
							$this->salida .= "    <td>&nbsp;</td>";
							$this->salida .= "    </tr>";
							$componenteAnt=$datos['componente'];
						}
            $solicitudAnt=$datos['solicitud_reserva_sangre_id'];
					}else{
					  if($datos['sw_cruze']==1){
							if($datos['componente']!=$componenteAnt){
								$this->salida .= "    <tr class=\"$estilo\">";
								$this->salida .= "    <td rowspan=\"".sizeof($vector1)."\">".$datos['componente']."</td>";
								$this->salida .= "    <td rowspan=\"".sizeof($vector1)."\">".$datos['cantidad_componente']."</td>";
								if($datos['sw_estado']=='1'){$var='No';}elseif($datos['sw_estado']=='2'){$var='Si';	}
								$this->salida .= "    <td rowspan=\"".sizeof($vector1)."\">$var</td>";
								$this->salida .= "    <td>".$datos['bolsa_id']."</td>";
								$this->salida .= "    </tr>";
								$componenteAnt=$datos['componente'];
							}else{
								$this->salida .= "    <tr class=\"$estilo\">";
								$this->salida .= "    <td>".$datos['bolsa_id']."</td>";
								$this->salida .= "    </tr>";
							}
						}else{
						  if($datos['componente']!=$componenteAnt){
								$this->salida .= "    <tr class=\"$estilo\">";
								$this->salida .= "    <td rowspan=\"".sizeof($vector1)."\">".$datos['componente']."</td>";
								$this->salida .= "    <td rowspan=\"".sizeof($vector1)."\">".$datos['cantidad_componente']."</td>";
								if($datos['sw_estado']=='1'){$var='No';}elseif($datos['sw_estado']=='2'){$var='Si';	}
								$this->salida .= "    <td rowspan=\"".sizeof($vector1)."\">$var</td>";
								$this->salida .= "    <td>&nbsp;</td>";
								$this->salida .= "    </tr>";
								$componenteAnt=$datos['componente'];
							}
						}
					}
					if(!empty($ingreso)){
					  $ingresoAnt=$ingreso;
					}else{
            $ingresoAnt=-1;
					}
			  }
				}
			}
			$y1++;
		}
		$this->salida .= "    </table>";
		}else{
      $this->salida .= "    <table border=\"0\" width=\"70%\" align=\"center\" class=\"normal_10\">";
			$this->salida .= "    <tr><td align=\"center\" class=\"label_error\">NO EXISTE RESERVAS DE SANGRE ACTIVOS PARA ESTE PACIENTE</td></tr>";
			$this->salida .= "    </table>";
		}
    $this->salida .= "    <table border=\"0\" width=\"70%\" align=\"center\" class=\"normal_10\">";
    $this->salida .= "    <tr><td align=\"center\"><input type=\"submit\" name=\"salir\" value=\"VOLVER\" class=\"input-submit\"></td></tr>";
    $this->salida .= "    </table>";
    $this->salida .= ThemeCerrarTabla();
		return true;
	}


  function LlamaTipoConsentimiento_qx(){
    $this->salida .= ThemeAbrirTabla('CONFIRMACION CONSENTIMIENTO CIRUGIA');
		$accion=ModuloGetURL('app','Quirurgicos','user','GuardarTipoConsentimientoQX');
		$this->salida.="<form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->Encabezado();
		$datosPaciente=$this->SacaDatosPacienteProgramQX($_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']);
		$Nombres=$this->BuscarNombresPaciente($datosPaciente['tipo_id_paciente'],$datosPaciente['paciente_id']);
		$this->salida .= "	   <table width=\"55%\" border=\"0\" class=\"normal_10\" align=\"center\">";
		$this->salida .= "    <tr><td>";
		$this->salida .= "    <fieldset><legend class=\"field\">DATOS DE LA PROGRAMACION</legend>";
		$this->salida .= "    <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
    $this->salida .= "		<tr><td width=\"25%\"></tr></td>";
		$this->salida .= "		<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td class=\"label\">NO. PROGRAMACION</td>";
		$this->salida .= "		<td>".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."</td>";
    $this->salida .= "		</tr>";
		$this->salida .= "		<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td width=\"20%\" class=\"label\">PACIENTE</td>";
		$this->salida .= "		<td>".$datosPaciente['tipo_id_paciente']." ".$datosPaciente['paciente_id']." $Nombres $Apellidos</td>";
    $this->salida .= "		</tr>";
		$this->salida .= "		</table>";
		$this->salida .= "		</fieldset></td></tr>";
    $this->salida .= "		</table><br>";
		$this->salida.="<table width=\"75%\" border=\"0\" align=\"center\">";
		$this->salida .= "  <tr><td colspan=\"4\" align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "  </td></tr>";
		$this->salida.="<tr class=\"modulo_table_list_title\">";
		$this->salida.="<td colspan=\"2\" align=\"center\">SELECCION TIPO DE CONSENTIMIENTO QX</td>";
		$this->salida.="</tr>";
    $this->salida.="        <tr class=\"modulo_list_claro\">";
		$this->salida.="        <td class=\"".$this->SetStyle("TipoConsentimiento")."\">TIPO CONSENTIMIENTO</td>";
		$this->salida .= "		  <td><select name=\"TipoConsentimiento\" class=\"select\">";
		$consentimientos=$this->TiposConsentimientosQX();
		$this->MostrasSelect($consentimientos,'False',$TipoConsentimiento);
		$this->salida .= "      </select></td>";
		$this->salida.="        <tr class=\"modulo_list_claro\"><td colspan=\"2\" align=\"center\"><input type=\"submit\" class=\"input-submit\" name=\"Cancelar\" value=\"CANCELAR\">";
		$this->salida.="        <input type=\"submit\" class=\"input-submit\" name=\"Aceptar\" value=\"ACEPTAR\"></td></tr>";
    $this->salida.="</tr>";
		$this->salida.="</table>";
    $this->salida.="</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function LlamaConsentimiento_qx($TipoConsentimiento,$numRadicacion,$responsable,$TipoDocumentoResponsable,$DocumentoResponsable,
		$nombreResponsable,$parentescoResponsable,$TipoDocumentoTestigoUno,$TipoDocumentoTestigoDos,$DocumentoTestigoUno,
		$DocumentoTestigoDos,$nombreTestigoUno,$nombreTestigoDos,$parentescoTestigoUno,$parentescoTestigoDos,$observaciones,
		$recibeConsentimiento){

    $this->salida .= ThemeAbrirTabla('CONFIRMACION CONSENTIMIENTO CIRUGIA');
		$accion=ModuloGetURL('app','Quirurgicos','user','GuardarConfiramacionQX');
		$this->salida.="<form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->Encabezado();
		$this->salida.="<script>";
		$this->salida.="function desabilitar(frm,valor){";
    $this->salida.="  if(valor==1){";
    $this->salida.="    frm.TipoDocumentoResponsable.disabled=true;";
		$this->salida.="    frm.DocumentoResponsable.disabled=true;";
		$this->salida.="    frm.parentescoResponsable.disabled=true;";
		$this->salida.="    frm.nombreResponsable.disabled=true;";
    $this->salida.="  }else{";
    $this->salida.="    frm.TipoDocumentoResponsable.disabled=false;";
		$this->salida.="    frm.DocumentoResponsable.disabled=false;";
		$this->salida.="    frm.parentescoResponsable.disabled=false;";
		$this->salida.="    frm.nombreResponsable.disabled=false;";
		$this->salida.="  }";
		$this->salida.="}";
		$this->salida.="</script>";
		$datosPaciente=$this->SacaDatosPacienteProgramQX($_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']);
		$Nombres=$this->BuscarNombresPaciente($datosPaciente['tipo_id_paciente'],$datosPaciente['paciente_id']);
		$this->salida .= "	   <table width=\"55%\" border=\"0\" class=\"normal_10\" align=\"center\">";
		$this->salida .= "    <tr><td>";
		$this->salida .= "    <fieldset><legend class=\"field\">DATOS DE LA PROGRAMACION</legend>";
		$this->salida .= "    <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
    $this->salida .= "		<tr><td width=\"25%\"></tr></td>";
		$this->salida .= "		<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td class=\"label\">NO. PROGRAMACION</td>";
		$this->salida .= "		<td>".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."</td>";
    $this->salida .= "		</tr>";
		$this->salida .= "		<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td width=\"20%\" class=\"label\">PACIENTE</td>";
		$this->salida .= "		<td>".$datosPaciente['tipo_id_paciente']." ".$datosPaciente['paciente_id']." $Nombres $Apellidos</td>";
    $this->salida .= "		</tr>";
		$this->salida .= "		</table>";
		$this->salida .= "		</fieldset></td></tr>";
    $this->salida .= "		</table><br>";
		$this->salida.="<table width=\"95%\" border=\"0\" align=\"center\">";
		$this->salida .= "  <tr><td colspan=\"4\" align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "  </td></tr>";
		$this->salida.="<tr class=\"modulo_table_list_title\">";
		$this->salida.="<td align=\"center\">SOLICITUD DE RESERVA DE SANGRE</td>";
		$this->salida.="</tr>";
    $this->salida.="<tr class=\"modulo_list_oscuro\"><td>";
		$this->salida.="        <BR><table width=\"95%\" border=\"0\" align=\"center\">";
    $this->salida.="        <input type=\"hidden\" name=\"TipoConsentimiento\" value=\"$TipoConsentimiento\">";
		$this->salida.="        <tr class=\"modulo_list_claro\">";
    $this->salida.="        <td class=\"label\">NUMERO RADICACION</td>";
    $this->salida.="        <td colspan=\"6\"><input type=\"text_input\" name=\"numRadicacion\" value=\"$numRadicacion\"></td>";
    $this->salida.="        </tr>";
		$this->salida.="        <tr class=\"modulo_list_claro\">";
    $this->salida.="        <td class=\"label\" valign=\"top\">RESPONSABLE CIRUGIA</td>";
		$this->salida.="        <td class=\"label\" valign=\"top\">PACIENTE</td>";
		if(!$responsable || $responsable==1){
		  $var='checked';
			$var1='disabled';
		}else{
      $che='checked';
		}
		$this->salida.="        <td valign=\"top\"><input type=\"radio\" name=\"responsable\" value=\"1\" onclick=\"desabilitar(this.form,this.value)\" $var></td>";
		$this->salida.="        <td class=\"label\" valign=\"top\">OTRO RESPONSABLE</td>";
		$this->salida.="        <td valign=\"top\"><input type=\"radio\" name=\"responsable\" value=\"2\" onclick=\"desabilitar(this.form,this.value)\" $che></td>";
    $this->salida.="        <td colspan=\"2\">";
		$this->salida.="            <BR><table width=\"90%\" border=\"0\" align=\"center\">";
		$this->salida.="            <tr class=\"modulo_table_list_title\">";
    $this->salida.="            <td colspan=\"2\">DATOS RESPONSABLE CIRUGIA</td>";
		$this->salida.="            </tr>";
		$this->salida.="            <tr class=\"modulo_list_oscuro\">";
    $this->salida.="            <td class=\"label\">TIPO DOCUMENTO</td>";
		$this->salida .= "		       <td><select name=\"TipoDocumentoResponsable\" class=\"select\" $var1>";
		$tipo_id=$this->tipo_id_paciente();
		$this->Mostrar($tipo_id,'False',$TipoDocumentoResponsable);
		$this->salida .= "          </select></td>";
    $this->salida .= "          </tr>";
		$this->salida.="            <tr class=\"modulo_list_oscuro\">";
    $this->salida.="            <td class=\"".$this->SetStyle("DocumentoResponsable")."\">DOCUMENTO</td>";
		$this->salida .= "		      <td><input type=\"text\" class=\"input-text\" name=\"DocumentoResponsable\" maxlength=\"32\" value=\"$DocumentoResponsable\" $var1></td>";
    $this->salida .= "          </tr>";
		$this->salida.="            <tr class=\"modulo_list_oscuro\">";
		$this->salida.="            <td class=\"".$this->SetStyle("nombreResponsable")."\">NOMBRE</td>";
    $this->salida .= "		       <td><input type=\"text\" class=\"input-text\" name=\"nombreResponsable\" maxlength=\"32\" value=\"$nombreResponsable\" $var1></td>";
		$this->salida .= "          </tr>";
		$this->salida.="            <tr class=\"modulo_list_oscuro\">";
    $this->salida.="            <td class=\"".$this->SetStyle("parentescoResponsable")."\">PARENTESCO</td>";
		$this->salida .= "		      <td><select name=\"parentescoResponsable\" class=\"select\" $var1>";
		$parentescos=$this->tiposParentescosPaciente();
		$this->MostrasSelect($parentescos,'False',$parentescoResponsable);
		$this->salida .= "          </select></td>";
    $this->salida.="            </tr>";
    $this->salida.="            </table><BR>";
    $this->salida.="        </td>";
		$this->salida.="        </tr>";
		$this->salida.="        <tr>";
    $this->salida.="        <td class=\"modulo_table_list_title\" colspan=\"4\">DATOS TESTIGO UNO</td>";
    $this->salida.="        <td class=\"modulo_list_claro\">&nbsp;</td>";
		$this->salida.="        <td class=\"modulo_table_list_title\" colspan=\"2\">DATOS TESTIGO DOS</td>";
    $this->salida.="        </tr>";

		$this->salida.="        <tr class=\"modulo_list_claro\">";
		$this->salida.="        <td class=\"label\">TIPO DOCUMENTO</td>";
		$this->salida .= "		   <td colspan=\"3\"><select name=\"TipoDocumentoTestigoUno\" class=\"select\">";
		$tipo_id=$this->tipo_id_paciente();
		$this->Mostrar($tipo_id,'False',$TipoDocumentoTestigoUno);
		$this->salida .= "      </select></td>";
    $this->salida .= "      <td>&nbsp;</td>";
		$this->salida.="        <td class=\"label\">TIPO DOCUMENTO</td>";
		$this->salida .= "		   <td><select name=\"TipoDocumentoTestigoDos\" class=\"select\">";
		$tipo_id=$this->tipo_id_paciente();
		$this->Mostrar($tipo_id,'False',$TipoDocumentoTestigoDos);
		$this->salida .= "      </select></td>";
    $this->salida .= "       </tr>";

		$this->salida.="       <tr class=\"modulo_list_claro\">";
    $this->salida.="       <td class=\"".$this->SetStyle("DocumentoTestigoUno")."\">DOCUMENTO</td>";
		$this->salida .= "		  <td colspan=\"3\"><input type=\"text\" class=\"input-text\" name=\"DocumentoTestigoUno\" maxlength=\"32\" value=\"$DocumentoTestigoUno\"></td>";
		$this->salida .= "     <td>&nbsp;</td>";
		$this->salida.="       <td class=\"".$this->SetStyle("DocumentoTestigoDos")."\">DOCUMENTO</td>";
		$this->salida .= "		  <td><input type=\"text\" class=\"input-text\" name=\"DocumentoTestigoDos\" maxlength=\"32\" value=\"$DocumentoTestigoDos\"></td>";
    $this->salida .= "      </tr>";

		$this->salida.="        <tr class=\"modulo_list_claro\">";
    $this->salida.="        <td class=\"".$this->SetStyle("nombreTestigoUno")."\">NOMBRE</td>";
    $this->salida .= "		   <td colspan=\"3\"><input type=\"text\" class=\"input-text\" name=\"nombreTestigoUno\" maxlength=\"32\" value=\"$nombreTestigoUno\"></td>";
		$this->salida .= "      <td>&nbsp;</td>";
		$this->salida.="        <td class=\"".$this->SetStyle("nombreTestigoDos")."\">NOMBRE</td>";
    $this->salida .= "		   <td><input type=\"text\" class=\"input-text\" name=\"nombreTestigoDos\" maxlength=\"32\" value=\"$nombreTestigoDos\"></td>";
    $this->salida .= "       </tr>";

		$this->salida.="        <tr class=\"modulo_list_claro\">";
		$this->salida.="        <td class=\"".$this->SetStyle("parentescoTestigoUno")."\">PARENTESCO</td>";
		$this->salida .= "		   <td colspan=\"3\"><select name=\"parentescoTestigoUno\" class=\"select\">";
		$parentescos=$this->tiposParentescosPaciente();
		$this->MostrasSelect($parentescos,'False',$parentescoTestigoUno);
		$this->salida .= "       </select></td>";
		$this->salida .= "      <td>&nbsp;</td>";
		$this->salida.="         <td class=\"".$this->SetStyle("parentescoTestigoDos")."\">PARENTESCO</td>";
		$this->salida .= "		   <td><select name=\"parentescoTestigoDos\" class=\"select\">";
		$parentescos=$this->tiposParentescosPaciente();
		$this->MostrasSelect($parentescos,'False',$parentescoTestigoDos);
		$this->salida .= "       </select></td>";
    $this->salida .= "       </tr>";
		$this->salida.="        <tr class=\"modulo_list_claro\">";
    $this->salida.="        <td colspan=\"7\" class=\"label\">OBSERVACIONES<br>";
    $this->salida.="        <textarea name=\"observaciones\" class=\"textarea\" rows=\"3\" cols=\"80\">$observaciones</textarea>";
		$this->salida.="        </td>";
    $this->salida.="        </tr>";
		$this->salida.="        <tr class=\"modulo_list_claro\">";
    $this->salida.="        <td align=\"center\" colspan=\"7\" class=\"label\"><BR>DAR CONSTANCIA DEL CONSENTIMIENTO FIRMADO POR LAS PERSONAS NOMBRADAS PARA ARCHIVAR JUNTO A LA HISTORIA CLINICA DEL PACIENTE:";
		if($recibeConsentimiento==1){$chec='checked';}
		$this->salida.="        <input type=\"checkbox\" name=\"recibeConsentimiento\" $chec></td>";
    $this->salida.="        </tr>";
		$this->salida.="        <tr class=\"modulo_list_oscuro\"><td colspan=\"7\" align=\"center\"><input type=\"submit\" class=\"input-submit\" value=\"VOLVER\" name=\"Salir\">";
		$this->salida.="        <input type=\"submit\" class=\"input-submit\" value=\"GUARDAR\" name=\"Guardar\"></td></tr>";
		$this->salida.="     </table><BR>";
		$this->salida.="</td></tr>";
		$this->salida.="</table>";
    $this->salida.="</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function ResercaCamaQX($FechaProgramFin){
	  $this->salida .= ThemeAbrirTabla('RESERVA CAMA CIRUGIA');
		$accion=ModuloGetURL('app','Quirurgicos','user','GuardarSeleccionEstacionE',array("FechaProgramFin"=>$FechaProgramFin));
		$this->salida.="<form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->Encabezado();
		$datosPaciente=$this->SacaDatosPacienteProgramQX($_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']);
		$Nombres=$this->BuscarNombresPaciente($datosPaciente['tipo_id_paciente'],$datosPaciente['paciente_id']);
		$this->salida .= "	   <table width=\"55%\" border=\"0\" class=\"normal_10\" align=\"center\">";
		$this->salida .= "    <tr><td>";
		$this->salida .= "    <fieldset><legend class=\"field\">DATOS DE LA PROGRAMACION</legend>";
		$this->salida .= "    <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
    $this->salida .= "		<tr><td width=\"25%\"></tr></td>";
		$this->salida .= "		<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td class=\"label\">NO. PROGRAMACION</td>";
		$this->salida .= "		<td>".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."</td>";
    $this->salida .= "		</tr>";
		$this->salida .= "		<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td width=\"20%\" class=\"label\">PACIENTE</td>";
		$this->salida .= "		<td>".$datosPaciente['tipo_id_paciente']." ".$datosPaciente['paciente_id']." $Nombres $Apellidos</td>";
    $this->salida .= "		</tr>";
		$this->salida .= "		</table>";
		$this->salida .= "		</fieldset></td></tr>";
    $this->salida .= "		</table><br>";
		$this->salida.="<table width=\"55%\" border=\"0\" class=\"normal_10\" align=\"center\">";
    $this->salida .= "  <tr><td colspan=\"4\" align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "  </td></tr>";
		$this->salida.="<tr class=\"modulo_table_list_title\">";
		$this->salida.="<td align=\"center\">SELECCION ESTACION ENFERMERIA</td>";
		$this->salida.="</tr>";
    $this->salida.="<tr class=\"modulo_list_oscuro\"><td>";
		$this->salida.="        <BR><table width=\"95%\" border=\"0\" align=\"center\">";
		$this->salida.="        <tr class=\"modulo_list_claro\">";
		$this->salida.="        <td class=\"".$this->SetStyle("estacionEnfermeria")."\">ESTACIONES DE ENFERMERIA</td>";
		$this->salida .= "		  <td><select name=\"estacionEnfermeria\" class=\"select\">";
		$estacionesEnfermeria=$this->EstacionesEnferemeria();
		$this->MostrasSelect($estacionesEnfermeria,'False',$estacionEnfermeria);
		$this->salida .= "      </select></td>";
    $this->salida.="        </tr>";
		$this->salida.="        </table><BR>";
		$this->salida.="</tr>";
		$this->salida.="<tr><td align=\"center\"><input type=\"submit\" class=\"input-submit\" name=\"Cancelar\" value=\"CANCELAR\">";
		$this->salida.="<input type=\"submit\" class=\"input-submit\" name=\"Aceptar\" value=\"ACEPTAR\"></td></tr>";
    $this->salida.="</table>";
    $this->salida.="</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function SeleccionCamaEstacion($estacionEnfermeria,$fechaReserv){
	  
    $this->salida .= ThemeAbrirTabla('RESERVA CAMA CIRUGIA');
		$accion=ModuloGetURL('app','Quirurgicos','user','GuardarSeleccionCamaQX',array("estacionEnfermeria"=>$estacionEnfermeria));
		$this->salida.="<form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->Encabezado();
		$datosPaciente=$this->SacaDatosPacienteProgramQX($_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']);
		$Nombres=$this->BuscarNombresPaciente($datosPaciente['tipo_id_paciente'],$datosPaciente['paciente_id']);
		$this->salida .= "	   <table width=\"55%\" border=\"0\" class=\"normal_10\" align=\"center\">";
		$this->salida .= "    <tr><td>";
		$this->salida .= "    <fieldset><legend class=\"field\">DATOS DE LA PROGRAMACION</legend>";
		$this->salida .= "    <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
    $this->salida .= "		<tr><td width=\"25%\"></tr></td>";
		$this->salida .= "		<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td class=\"label\">NO. PROGRAMACION</td>";
		$this->salida .= "		<td>".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."</td>";
    $this->salida .= "		</tr>";
		$this->salida .= "		<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td width=\"20%\" class=\"label\">PACIENTE</td>";
		$this->salida .= "		<td>".$datosPaciente['tipo_id_paciente']." ".$datosPaciente['paciente_id']." $Nombres $Apellidos</td>";
    $this->salida .= "		</tr>";
		$this->salida .= "		<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td width=\"20%\" class=\"label\">ESTACION</td>";
		$nombreEstacion=$this->nombreEstacionEnf($estacionEnfermeria);
		$this->salida .= "		<td>".$nombreEstacion['descripcion']."</td>";
    $this->salida .= "		</tr>";
		$this->salida .= "		<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td width=\"20%\" class=\"label\">FECHA RESERVA</td>";
		$Fecha=$this->FechaStamp($fechaReserv);
		$infoCadena = explode ('/', $Fecha);
		$dia=$infoCadena[0];
		$mes=$infoCadena[1];
		$ano=$infoCadena[2];
		$dia=str_pad($dia,2,0, STR_PAD_LEFT);
		$mes=str_pad($mes,2,0, STR_PAD_LEFT);
		$ano=str_pad($ano,2,0, STR_PAD_LEFT);
		$Fecha=$dia.'/'.$mes.'/'.$ano;
		$this->salida .= "		<td><input type=\"text\" class=\"input-text\" name=\"fechaReserv\" size=\"10\" maxlength=\"10\" value=\"$Fecha\"></td>";
    $this->salida .= "		</tr>";
		$this->salida .= "		</table>";
		$this->salida .= "		</fieldset></td></tr>";
    $this->salida .= "		</table><br>";
		$this->salida.="<table width=\"100%\" border=\"0\" class=\"normal_10\" align=\"center\">";
    $this->salida .= "  <tr><td colspan=\"4\" align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "  </td></tr>";
		$this->salida.="<tr class=\"modulo_table_list_title\">";
		$this->salida.="<td align=\"center\">SELECCION CAMA</td>";
		$this->salida.="</tr>";
		$piezas=$this->SeleccionPiezasEstacion($estacionEnfermeria);
		$this->salida.="<tr class=\"modulo_list_oscuro\"><td>";
		$this->salida.="        <BR><table width=\"95%\" border=\"0\" align=\"center\">";
		if($piezas){
      $this->salida.="        <tr class=\"modulo_table_list_title\">";
      $this->salida.="        <td width=\"7%\">No. PIEZA</td>";
			$this->salida.="        <td width=\"15%\">PIEZA</td>";
			$this->salida.="        <td width=\"15%\">UBICACION PIEZA</td>";
			$this->salida.="        <td width=\"7%\">No. CAMA</td>";
			$this->salida.="        <td width=\"27%\">CAMA</td>";
			$this->salida.="        <td width=\"26%\">UBICACION CAMA</td>";
			$this->salida.="        <td>&nbsp;</td>";
      $this->salida.="        </tr>";
			for($i=0;$i<sizeof($piezas);$i++){
				$this->salida.="        <tr class=\"modulo_list_claro\">";
				$this->salida.="        <td rowspan=\"$cont\" width=\"7%\">".$piezas[$i]['pieza']."</td>";
				$this->salida.="        <td rowspan=\"$cont\" width=\"15%\">".$piezas[$i]['nombrepieza']."</td>";
				$this->salida.="        <td rowspan=\"$cont\" width=\"15%\">".$piezas[$i]['ubicacionpieza']."</td>";
        $this->salida.="        <td colspan=\"4\">";
				$camas=$this->SeleccionCamasEstacion($piezas[$i]['pieza']);
				$cont=sizeof($camas);
				$this->salida.="          <table width=\"98%\" border=\"0\" align=\"center\">";
				for($j=0;$j<$cont;$j++){
				  $this->salida.="        <tr class=\"modulo_list_oscuro\">";
					$this->salida.="        <td width=\"10%\">".$camas[$j]['cama']."</td>";
					$this->salida.="        <td width=\"45%\">".$camas[$j]['nombrecama']."</td>";
					$this->salida.="        <td width=\"45%\">".$camas[$j]['ubicacioncama']."</td>";
					if($camas[$j]['indicador']){
            $indica='checked';
					}
					$this->salida.="        <td><input type=\"checkbox\" name=\"seleccion[]\" value=\"".$camas[$j]['cama']."\" $indica></td>";
					$this->salida.="        </tr>";
				}
        $this->salida.="          </table>";
				$this->salida.="        </td>";
				$this->salida.="        </tr>";
			}
			$this->salida.="        </table><BR>";
		  $this->salida.="</td></tr>";
		}
		$this->salida.="<tr><td align=\"center\"><input type=\"submit\" class=\"input-submit\" name=\"Cancelar\" value=\"VOLVER\">";
		$this->salida.="<input type=\"submit\" class=\"input-submit\" name=\"Aceptar\" value=\"GUARDAR\"></td></tr>";
    $this->salida.="</table>";
    $this->salida.="</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function FormaBuscadorDiagnostico($TipoDocumento,$Documento,$Responsable,$cirujano,$codigo,$cargo){

    $this->salida .= ThemeAbrirTabla('BUSCADOR DIAGNOSTICOS');
		$action=ModuloGetURL('app','Quirurgicos','user','SeleccionDiagnostico',array("TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"Responsable"=>$Responsable,"cirujano"=>$cirujano,"codigo"=>$codigo,"cargo"=>$cargo));
		$this->salida .= "<form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->salida .= "	  <table width=\"85%\" border=\"0\" class=\"normal_10\" align=\"center\">";
		$this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"5\">PARAMENTROS DE BUSQUEDA</td></tr>";
		$this->salida .= "    <tr class=\"modulo_list_claro\">";
    $this->salida .= "    <td class=\"label\">DIAGNOSTICO</td>";
    $this->salida .= "    <td><input size=\"80\" type=\"text\" name=\"procedimientoBus\" value=\"".$_REQUEST['procedimientoBus']."\" class=\"input-submit\"></td>";
    $this->salida .= "    <td class=\"label\">CODIGO</td>";
		$this->salida .= "    <td><input type=\"text\" size=\"10\" name=\"codigoBus\" value=\"".$_REQUEST['codigoBus']."\" class=\"input-submit\"></td>";
    $this->salida .= "    <td><input type=\"submit\" name=\"buscar\" value=\"BUSCAR\" class=\"input-submit\"></td>";
    $this->salida .= "    </tr>";
		$this->salida .= "	  </table><BR>";
		$diags=$this->HallarDiagnosticosPatologia($_REQUEST['codigoBus'],$_REQUEST['procedimientoBus']);
		if($diags){
      $this->salida .= "	   <table width=\"80%\" border=\"0\" class=\"modulo_table_list\" align=\"center\">";
			$this->salida .= "	   <tr class=\"modulo_table_list_title\">";
			$this->salida .= "	   <td>CODIGO</td>";
			$this->salida .= "	   <td>DESCRIPCION</td>";
			$this->salida .= "	   <td>&nbsp;</td>";
			$y=0;
			for($i=0;$i<sizeof($diags);$i++){
        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
				$this->salida .= "    <tr class=\"$estilo\">";
				$this->salida .= "    <td width=\"10%\" nowrap>".$diags[$i]['diagnostico_id']."</td>";
				$this->salida .= "    <td>".$diags[$i]['diagnostico_nombre']."</td>";
				$action=ModuloGetURL('app','Quirurgicos','user','SeleccionarDiagnosticoPQX',array("nombrediagnostico"=>$diags[$i]['diagnostico_nombre'],"diagnostico"=>$diags[$i]['diagnostico_id'],"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"Responsable"=>$Responsable,"cirujano"=>$cirujano,"codigo"=>$codigo,"cargo"=>$cargo));
				$this->salida .= "    <td width=\"5%\" nowrap align=\"center\"><a href=\"$action\"><img border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"><a></td>";
        $this->salida .= "    </tr>";
			}
      $this->salida .= "	   </table>";
			$this->salida .=$this->RetornarBarra(2);
		}else{
      $this->salida .= "	   <BR><table width=\"80%\" border=\"0\" align=\"center\">";
			$this->salida .= "     <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON REGISTROS CON ESTOS PARAMETROS</td></tr>";
			$this->salida .= "	   </table><br>";
		}
		$this->salida .= "	   <BR><table width=\"80%\" border=\"0\" align=\"center\">";
    $this->salida .= "     <tr><td align=\"center\"><input type=\"submit\" name=\"Salir\" value=\"VOLVER\" class=\"input-submit\"></td></tr>";
    $this->salida .= "	   </table><br>";
    $this->salida .= "</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

  function FormaConsultaInsumosPaquetes($paqueteId,$NomPaquete,$codigoPaquete,$Descripcion){
    $this->salida .= ThemeAbrirTabla('INSUMOS QUE FORMAN LOS PAQUETES');
		$action=ModuloGetURL('app','Quirurgicos','user','LlamaReserva_Paquetes_Insumos_qx',array("codigoPaquete"=>$codigoPaquete,"Descripcion"=>$Descripcion));
		$this->salida .= "<form name=\"forma\" action=\"$action\" method=\"post\">";
    $insumos=$this->BuscarInsumosPaquete($paqueteId);
    if($insumos){
      $this->salida .= "	   <table width=\"80%\" border=\"0\" class=\"modulo_table_list\" align=\"center\">";
      $this->salida .= "	   <tr class=\"modulo_table_list_title\"><td colspan=\"3\">$NomPaquete</td></tr>";
			$this->salida .= "	   <tr class=\"modulo_table_list_title\">";
			$this->salida .= "	   <td width=\"10%\">CODIGO</td>";
			$this->salida .= "	   <td>DESCRIPCION</td>";
			$this->salida .= "	   <td width=\"5%\">CANTIDAD</td>";
      $this->salida .= "    </tr>";
			$y=0;
			for($i=0;$i<sizeof($insumos);$i++){
        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
				$this->salida .= "    <tr class=\"$estilo\">";
        $this->salida .= "	   <td>".$insumos[$i]['codigo_producto']."</td>";
        $this->salida .= "	   <td>".$insumos[$i]['insumo']."</td>";
        $this->salida .= "	   <td>".$insumos[$i]['cantidad']."</td>";
        $this->salida .= "    </tr>";
      }
      $this->salida .= "	   </ table>";
    }
    $this->salida .= "	   <table width=\"80%\" border=\"0\" align=\"center\">";
    $this->salida .= "	   <tr><td align=\"center\"><input type=\"submit\" name=\"VOLVER\" value=\"VOLVER\" class=\"input-submit\"></td></tr>";
    $this->salida .= "	   </table>";
    $this->salida .= "</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
  }
	
	function FiltroCirugiaReporte()
	{
		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserDrag");
		$this->IncludeJS("CrossBrowserEvent");
		
		$this->salida .= ThemeAbrirTabla('REPORTE DE CIRUGIAS','100%');
		$accionP = ModuloGetURL('app','Quirurgicos','user','GenerarReporteCirugia');
		$salas=$this->GetSalas();
		$cirujanos=$this->profesionalesEspecialista();
		$anestesiologos=$this->profesionalesEspecialistaAnestecistas();
		$tipos_documentos=$this->TiposIdTerceros();
		
		$this->salida .= "<center><label class=\"label_error\">".$this->frmError["MensajeError"]."</label><center><br>";
		
		$this->SetXajax(array("Busqueda","SelectEspecialista"),"app_modules/Quirurgicos/RemoteXajax/QuirurgicosCirugias.php");
		
		$fecha_i=date("d-m-Y");
		$fecha_f=date("d-m-Y");
		
		if(!empty($_REQUEST['fecha_ini']) and !empty($_REQUEST['fecha_fin']))
		{
			$fecha_i=$_REQUEST['fecha_ini'];
			$fecha_f=$_REQUEST['fecha_fin'];
		}
		
		$this->salida .= "<form name=\"formafiltro\" action=\"$accionP\" method=\"post\">";
		$this->salida .= "	<table align=\"center\" width=\"50%\" border=\"0\" class=\"modulo_table_list\">";
		$this->salida .= "		<tr class=\"modulo_table_list_title\" align=\"center\">";
		$this->salida .= "			<td colspan=\"4\">CONSULTA CIRUGIAS</td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr>";
		$this->salida .= "			<td class=\"modulo_table_list_title\"> FECHAS </td>";
		$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\" colspan=\"3\"> DE <input type=\"input\" name=\"fecha_ini\" readonly class=\"input-text\" maxlength=\"10\" size=\"10\" value=\"".$fecha_i."\" ><sub>".ReturnOpenCalendario("formafiltro","fecha_ini","-")."</sub>";
		$this->salida .= "			A <input type=\"input\" name=\"fecha_fin\" class=\"input-text\" readonly maxlength=\"10\" size=\"10\" value=\"".$fecha_f."\"><sub>".ReturnOpenCalendario("formafiltro","fecha_fin","-")."</sub></td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr>";
		$this->salida .= "			<td class=\"modulo_table_list_title\"> SALA </td>";
		$this->salida .= "			<td align=\"left\" colspan=\"3\" class=\"modulo_list_claro\">";
		$this->salida .= "				<select name=\"sala\" class=\"select\">";
		$this->salida .= "					<option value=\"\">--SELECCIONE SALA--</option>";
		foreach($salas as $sala)
		{
			$sel="";
			if($sala['quirofano']==$_REQUEST['sala'])
				$sel="selected";
			$this->salida .= "					<option value=\"".$sala['quirofano']."\" $sel>".$sala['descripcion']."</option>";
		}
		$this->salida .= "				</select>";
		$this->salida .= "			</td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr>";
		$this->salida .= "			<td class=\"modulo_table_list_title\"> CIRUJANO </td>";
		$this->salida .= "			<td id=\"capaCiru\" align=\"left\" colspan=\"2\" class=\"modulo_list_claro\">";
		$this->salida .= "					<select name=\"cirujano\" class=\"select\">";
		$this->salida .= "						<option value=\"\">--SELECCIONE CIRUJANO--</option>";
		foreach($cirujanos as $cirujano)
		{
			$sel="";
			if($cirujano['tipo_id_tercero']."__".$cirujano['tercero_id']==$_REQUEST['cirujano'])
				$sel="selected";
			
			$this->salida .= "					<option value=\"".$cirujano['tipo_id_tercero']."__".$cirujano['tercero_id']."\" $sel>".$cirujano['nombre']."</option>";
		}
		$this->salida .= "					</select>";
		$this->salida .= "			</td>";
		$this->salida .= "			<td class=\"modulo_list_claro\"><a href=\"javascript:Cerrar('especialistas');Iniciar('BUSCAR CIRUJANO','1');MostrarSpan('d2Container');\" class=\"label\">BUSCAR</a></td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr>";
		$this->salida .= "			<td class=\"modulo_table_list_title\"> ANESTESIOLOGO </td>";
		$this->salida .= "			<td id=\"capaAnes\" align=\"left\" colspan=\"2\" class=\"modulo_list_claro\">";
		$this->salida .= "					<select name=\"anestesiologo\" class=\"select\">";
		$this->salida .= "						<option value=\"\">--SELECCIONE ANESTESIOLOGO--</option>";
		foreach($anestesiologos as $anestesiologo)
		{
			$sel="";
			if($cirujano['tipo_id_tercero']."__".$cirujano['tercero_id']==$_REQUEST['anestesiologo'])
				$sel="selected";
				
			$this->salida .= "					<option value=\"".$anestesiologo['tipo_id_tercero']."__".$anestesiologo['tercero_id']."\" $sel>".$anestesiologo['nombre']."</option>";
		}
		$this->salida .= "					</select>";
		$this->salida .= "			</td>";
		$this->salida .= "			<td class=\"modulo_list_claro\"><a href=\"javascript:Cerrar('especialistas');Iniciar('BUSCAR ANESTESIOLOGO','2');MostrarSpan('d2Container');\" class=\"label\">BUSCAR</a></td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr>";
		$this->salida .= "			<td colspan=\"4\" align=\"center\" class=\"modulo_list_claro\"> <input type=\"submit\" name=\"filtrar\" class=\"input-submit\" value=\"Aceptar\"> &nbsp;&nbsp;&nbsp; <input type=\"button\" name=\"limpiar\" class=\"input-submit\" value=\"Limpiar\" onclick=\"FuncionLimpiar(this.form)\"></td>";
		$this->salida .= "		</tr>";
		$this->salida .= "	</table>";
		$this->salida .= "</form>";
		
		$this->salida .= "<div id='d2Container' class='d2Container' style=\"display:none\">\n";
		$this->salida .= "	<div id='titulo' class='draggable' style=\"	text-transform: uppercase;\"></div>\n";
		$this->salida .= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('d2Container')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
		$this->salida .= "	<div id='error' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
		$this->salida .= "	<div id='d2Contents' class=\"d2Content\">\n";
		$this->salida .= "		<table align=\"center\" width=\"70%\" border=\"0\" class=\"modulo_table_list\">";
		$this->salida .= "		<tr>";
		$this->salida .= "			<td class=\"modulo_table_list_title\"> TIPO DE DOCUMENTO </td>";
		$this->salida .= "			<td class=\"modulo_list_claro\">";
		$this->salida .= "				<select name=\"tipo_id\" id=\"tipo_id\" class=\"select\">";
		foreach($tipos_documentos as $tipo_documento)
		{
			$sel="";
			if($tipo_documento['tipo_id_tercero']=='CC')
				$sel="selected";
			$this->salida .= "					<option value=\"".$tipo_documento['tipo_id_tercero']."\" $sel>".$tipo_documento['descripcion']."</option>";
		}
		$this->salida .= "				</select>";
		$this->salida .= "			</td>";
		$this->salida .= "			<td class=\"modulo_table_list_title\"> DOCUMENTO </td>";
		$this->salida .= "			<td class=\"modulo_list_claro\"> <input type=\"input\" name=\"id\" id=\"id\" class=\"input-text\" value=\"\"> </td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr>";
		$this->salida .= "			<td class=\"modulo_table_list_title\"> NOMBRE </td>";
		$this->salida .= "			<td class=\"modulo_list_claro\" colspan=\"3\"> <input type=\"input\" name=\"nombre\" id=\"nombre\" class=\"input-text\" size=\"50\"></td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr align=\"center\">";
		$this->salida .= "			<td class=\"modulo_list_claro\" colspan=\"4\"> <input type=\"button\" name=\"buscar\" class=\"input-submit\" value=\"BUSCAR\" onclick=\"xajax_Busqueda(document.getElementById('tipo_id').value,getElementById('id').value,getElementById('nombre').value,getElementById('tipo').value);\"></td>";
		$this->salida .= "		</tr>";
		$this->salida .= "	</table><br>";
		$this->salida .= "		<input type=\"hidden\" id=\"tipo\" value=\"\">";
		$this->salida .= "<div id=\"especialistas\">\n";
		$this->salida .= "</div><br>\n";
		
		$this->salida .= "	</div>\n";
		$this->salida .= "</div>\n";

		$this->salida .= "<script>\n";
		
		$this->salida .= "	var titulo='';\n";
		$this->salida .= "	var contenedor='';\n";
		$this->salida .= "	var g_tipo_id='';\n";
		$this->salida .= "	var g_id='';\n";
		
		$this->salida .= "	function Iniciar(tit,num)\n";
		$this->salida .= "	{\n";
		$this->salida .= "	  titulo = 'titulo';\n";
		$this->salida .= "	  contenedor = 'd2Container';\n";
		$this->salida .= "		document.getElementById(titulo).innerHTML = tit;\n";
		$this->salida .= "		document.getElementById('id').value = '';\n";
		$this->salida .= "		document.getElementById('nombre').value = '';\n";
		$this->salida .= "		document.getElementById('tipo').value = num;\n";
		$this->salida .= "		document.getElementById('error').innerHTML = '';\n";
		$this->salida .= "		ele = xGetElementById(contenedor);\n";
		$this->salida .= "	  xMoveTo(ele, xClientWidth()/4, xScrollTop()+30);\n";
		$this->salida .= "	  xResizeTo(ele,470, 'auto');\n";
		$this->salida .= "		ele = xGetElementById('d2Contents');\n";
		$this->salida .= "	  xMoveTo(ele, xClientWidth()/4, xScrollTop());\n";
		$this->salida .= "	  xResizeTo(ele,470,200);\n";
		$this->salida .= "		ele = xGetElementById(titulo);\n";
		$this->salida .= "	  xResizeTo(ele,450, 20);\n";
		$this->salida .= "		xMoveTo(ele, 0, 0);\n";
		$this->salida .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
		$this->salida .= "		ele = xGetElementById('cerrar');\n";
		$this->salida .= "	  xResizeTo(ele,20, 20);\n";
		$this->salida .= "		xMoveTo(ele, 450, 0);\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function myOnDragStart(ele, mx, my)\n";
		$this->salida .= "	{\n";
		$this->salida .= "	  window.status = '';\n";
		$this->salida .= "	  if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
		$this->salida .= "	  else xZIndex(ele, hiZ++);\n";
		$this->salida .= "	  ele.myTotalMX = 0;\n";
		$this->salida .= "	  ele.myTotalMY = 0;\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function myOnDrag(ele, mdx, mdy)\n";
		$this->salida .= "	{\n";
		$this->salida .= "	  if (ele.id == titulo) {\n";
		$this->salida .= "	    xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
		$this->salida .= "	  }\n";
		$this->salida .= "	  else {\n";
		$this->salida .= "	    xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
		$this->salida .= "	  }  \n";
		$this->salida .= "	  ele.myTotalMX += mdx;\n";
		$this->salida .= "	  ele.myTotalMY += mdy;\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function myOnDragEnd(ele, mx, my)\n";
		$this->salida .= "	{}\n";
		
		$this->salida .= "	function MostrarSpan(Seccion)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		e = xGetElementById(Seccion);\n";
		$this->salida .= "		e.style.display = \"\";\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function Cerrar(Seccion)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		e = xGetElementById(Seccion);\n";
		$this->salida .= "		e.style.display = \"none\";\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function Especialista(tipo)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		xajax_SelectEspecialista(g_tipo_id,g_id,tipo);\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function RadioSelecionado(tipo_id,id)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		g_tipo_id=tipo_id;\n";
		$this->salida .= "		g_id=id;\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function FuncionLimpiar(forma)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		forma.fecha_ini.value='".date("d-m-Y")."';\n";
		$this->salida .= "		forma.fecha_fin.value='".date("d-m-Y")."';\n";
		$this->salida .= "		forma.sala.value='';\n";
		$this->salida .= "		forma.cirujano.value='';\n";
		$this->salida .= "		forma.anestesiologo.value='';\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "</script>\n";
		
		$accionV = ModuloGetURL('app','Quirurgicos','user','MenuQuirurjicos');
		$this->salida .= "<form name=\"formavolver\" action=\"$accionV\" method=\"post\">";
		$this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\"></p>";
		$this->salida .= "</form>";
		
		$this->salida .= ThemeCerrarTabla();
		
		return true;
	}
	
	function GenerarReporteCirugia()
	{
		$fecha_ini=$this->FechaStamp2($_REQUEST['fecha_ini']);
		$fecha_fin=$this->FechaStamp2($_REQUEST['fecha_fin']);
		
		$accionV = ModuloGetURL('app','Quirurgicos','user','FiltroCirugiaReporte',array('fecha_ini'=>$_REQUEST['fecha_ini'],'fecha_fin'=>$_REQUEST['fecha_fin'],'sala'=>$_REQUEST['sala'],'cirujano'=>$_REQUEST['cirujano'],'anestesiologo'=>$_REQUEST['anestesiologo']));
		
		$cirugia=$this->GetReporteCirugia($fecha_ini,$fecha_fin,$_REQUEST['sala'],$_REQUEST['cirujano'],$_REQUEST['anestesiologo']);
		if($cirugia)
		{
			$this->salida .= ThemeAbrirTabla('REPORTE DE CIRUGIAS','100%');
			
			$this->salida .= "<table align=\"center\" width=\"100%\" border=\"0\" class=\"modulo_table_list\">\n";
			$j=0;
			$vector=array();
			$vector2=array();
			
			foreach($cirugia as $key=>$valor)
			{
				$this->salida .= "	<tr class=\"modulo_table_list_title\" align=\"center\">\n";
				$this->salida .= "		<td colspan=\"12\">$key</td>";
				$this->salida .= "	</tr>\n";
				$this->salida .= "	<tr class=\"modulo_table_list_title\" align=\"center\">\n";
				$this->salida .= "		<td width=\"5%\">HORA INICIO</td>";
				$this->salida .= "		<td width=\"5%\">TIEMPO CIRUGIA</td>";
				$this->salida .= "		<td width=\"15%\">PACIENTE</td>";
				$this->salida .= "		<td width=\"5%\">TELEFONO</td>";
				$this->salida .= "		<td width=\"15%\">PROCEDIMIENTO</td>";
				$this->salida .= "		<td width=\"10%\">CIRUJANO</td>";
				$this->salida .= "		<td width=\"10%\">ANESTESIOLOGO</td>";
				$this->salida .= "		<td width=\"10%\">AYUDANTE</td>";
				$this->salida .= "		<td width=\"10%\">T. CIRUGIA</td>";
				$this->salida .= "		<td width=\"5%\">EDAD</td>";
				$this->salida .= "		<td width=\"15%\">OBSERVACIONES</td>";
				$this->salida .= "		<td width=\"15%\">PLAN</td>";
				$this->salida .= "	</tr>\n";	
				$k=0;
				$dif_hora_sala=0;
				$dif_min_sala=0;
				$tmp=0;
				foreach($valor as $key1=>$valor1)
				{
					
					if($k%2==0)
					{
						$estilo="modulo_list_oscuro";
						$estilo1="modulo_list_claro";
					}
					else
					{
						$estilo="modulo_list_claro";
						$estilo1="modulo_list_oscuro";
					}
					
					$r=0;
					$table_proc="<table width=\"100%\" height=\"100%\">";
					foreach($valor1 as $key2=>$valor2)
					{
						$table_proc.="<tr class=\"$estilo1\">";
						$table_proc.="		<td>";
						$table_proc.="			".$valor2['descripcion_cups'];
						$table_proc.="		</td>";
						$table_proc.="</tr>";
						$r++;
					}
					$table_proc.="</table>";
					
					if($r<2)
						$table_proc=$valor1[$key2]['descripcion_cups'];
					
					$edad=CalcularEdad($valor1[$key2]['fecha_nacimiento']);
					
					$hora_i=explode(":",substr(substr($valor1[$key2]['hora_inicio'],10,18),0,6));
					$hora_f=explode(":",substr(substr($valor1[$key2]['hora_fin'],10,18),0,6));
		
					if($hora_f[0]==$hora_i[0])
					{
						$dif_hora=abs(($hora_f[1]-$hora_i[1]))." minuto(s)";
						$dif_min_sala+=abs(($hora_f[1]-$hora_i[1]));
					}
					elseif($hora_f[1]==$hora_i[1])
					{
						$dif_hora=abs(($hora_f[0]-$hora_i[0]))." hora(s)";
						$dif_hora_sala+=abs(($hora_f[0]-$hora_i[0]));
					}
					else
					{
						$dif_hora=abs(($hora_f[0]-$hora_i[0]))." hora(s) y ".abs(($hora_f[1]-$hora_i[1]))." minuto(s)";
						$dif_hora_sala+=abs(($hora_f[0]-$hora_i[0]));
						$dif_min_sala+=abs(($hora_f[1]-$hora_i[1]));
					}
					
					$cirugia[$key][$key1][$key2]['dif_hora']=$dif_hora;
					$cirugia[$key][$key1][$key2]['edad']=$edad['anos'];
					
					$this->salida .= "	<tr class=\"$estilo\" align=\"center\" rowspan=\"$rowspan\">\n";
					$this->salida .= "		<td>".substr(substr($valor1[$key2]['hora_inicio'],10,18),0,6)."</td>";
					$this->salida .= "		<td>".$dif_hora."</td>";
					$this->salida .= "		<td>".$valor1[$key2]['nombre_completo']."</td>";
					$this->salida .= "		<td>".str_replace("-","<br>",$valor1[$key2]['residencia_telefono'])."</td>";
					$this->salida .= "		<td height=\"100%\">".$table_proc."</td>";
					$this->salida .= "		<td>".$valor1[$key2]['nombre']."</td>";
					$this->salida .= "		<td>".$valor1[$key2]['nombre_anes']."</td>";
					$this->salida .= "		<td>".$valor1[$key2]['nombre_ayud']."</td>";
					$this->salida .= "		<td>".$valor1[$key2]['desc_ambito_cirugia']."</td>";
					$this->salida .= "		<td>".$edad['anos']." años</td>";
					$this->salida .= "		<td>".$valor1[$key2]['observaciones']."</td>";
					$this->salida .= "		<td>".$valor1[$key2]['plan_descripcion']."</td>";
					$this->salida .= "	</tr>\n";
					$k++;
				}
				$dif_hora_sala+=intval($dif_min_sala/60);
				$dif_min_sala=$dif_min_sala - intval($dif_min_sala/60)*60;

				$vector[$j]['total_prog_sala']=$k;
				$vector[$j]['total_hora_sala']=$dif_hora_sala;
				$vector[$j]['total_min_sala']=$dif_min_sala;
								
				$total_horas+=$dif_hora_sala;
				$totaltmp_min+=$dif_min_sala;
				$sum_total_pro+=$k;
				
				$this->salida .= "	<tr class=\"hc_table_submodulo_list_title\">\n";
				$this->salida .= "		<td colspan=\"12\" align=\"right\">TOTAL PROGRAMACIONES DE $key : &nbsp;&nbsp;&nbsp; <label class=\"label_error\">$k</label> &nbsp;&nbsp;&nbsp; TIEMPO :  &nbsp;&nbsp;&nbsp; <label class=\"label_error\">$dif_hora_sala hora(s) y $dif_min_sala minuto(s)</label></td>";
				$this->salida .= "	</tr>\n";	
				$j++;
			}
			
			$total_horas+=intval($totaltmp_min/60);
			$total_min=$totaltmp_min - intval($totaltmp_min/60)*60;
			
			$vector2['total_prog']=$sum_total_pro;
			$vector2['total_hora']=$total_horas;
			$vector2['total_min']=$total_min;
			
			$this->salida .= "	<tr class=\"modulo_list_oscuro\">\n";
			$this->salida .= "		<td colspan=\"12\" align=\"right\" class=\"label\">TOTAL PROGRAMACIONES : &nbsp;&nbsp;&nbsp; <label class=\"label_error\">$sum_total_pro</label> &nbsp;&nbsp;&nbsp; TIEMPO :  &nbsp;&nbsp;&nbsp; <label class=\"label_error\">$total_horas hora(s) y $total_min minuto(s)</label></td>";
			$this->salida .= "	</tr>\n";	
			$this->salida .= "</table>\n";
			
			$_SESSION['cirugia_report']=$cirugia;
			$_SESSION['vector']=$vector;
			$_SESSION['vector2']=$vector2;
			
			$direccion="app_modules/Quirurgicos/reports/html/ReporteCirugias.php?fecha_ini=".$fecha_ini."&fecha_fin=".$fecha_fin."";
			$this->salida.="		<br><center><img src=\"".GetThemePath()."/images/imprimir.png\"><label class=\"label\"><a href=\"javascript:reportecuentas('$direccion');\"> IMPRIMIR </a></label></center>";	

			$this->salida .= "<form name=\"formavolver\" action=\"$accionV\" method=\"post\">";
			$this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\"></p>";
			$this->salida .= "</form>";
			
			$this->salida .= ThemeCerrarTabla();
			
			$this->salida .= "<script>\n";
			$this->salida .= "	function reportecuentas(url)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		window.open(url,'REPORTE CIRUGIA','screen.width,screen.height,resizable=no,location=yes,toolbar=1,status=no,scrollbars=yes');\n";
			$this->salida .= "	}\n";
			$this->salida .= "</script>\n";
		}
		else
		{
			$this->salida .= ThemeAbrirTabla('REPORTE CIRUGIA','100%');
			$this->salida .= "<center><label class=\"label_error\">NO SE ENCONTRARON REGISTROS EN LA BUSQUEDA</label><center><br>";
			
			$this->salida .= "<form name=\"formavolver\" action=\"$accionV\" method=\"post\">";
			$this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\"></p>";
			$this->salida .= "</form>";
			
			$this->salida .= ThemeCerrarTabla();
		}

		return true;
	}
	
//pg_dump -u -s SIIS2 > /home/lorena/Desktop/alex.sql
}//fin clase user
?>