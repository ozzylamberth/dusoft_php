<?

 /**
 * $Id: app_InsumosMedicamentosCirugia_userclasses_HTML.php,v 1.10 2006/08/28 23:22:59 lorena Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo de Insumos y Medicamentos requeridos para los pacientes de la cirugia
 */



/**
*		class app_InsumosMedicamentosCirugia_userclasses_HTML
*
*		Clase que maneja todas las funciones de vistas y consultas a la base de datos
*		relacionadas a la estaci&oacute;n de Enfermer&iacute;a de Cirugia
*		ubicacion => app_modules/InsumosMedicamentosCirugia/userclasses/app_InsumosMedicamentosCirugia_userclasses_HTML.php
*		fecha creaci&oacute;n => 04/05/2004 10:35 am
*
*		@Author Lorena Aragón G.
*		@version =>
*		@package SIIS
*/
IncludeClass("ClaseHTML");
class app_InsumosMedicamentosCirugia_userclasses_HTML extends app_InsumosMedicamentosCirugia_user
{

	/**
	*		app_InsumosMedicamentosCirugia_userclasses_HTML()
	*
	*		constructor
	*
	*		@Author Lorena Aragón G.
	*		@access Private
	*		@return boolean
	*/
	function app_InsumosMedicamentosCirugia_userclasses_HTML(){
	  $this->app_InsumosMedicamentosCirugia_user(); //Constructor del padre 'modulo'
		$this->salida = "";
		return true;
	}

	/**
	*		FrmLogueoEstacion
	*
  *		Funcion que despliega la seleccion de la empresa y el departamento donde va a trabajar el usuario
	*		@Author Lorena Aragón G.
	*		@access Private
	*		@return boolean
	*/
	function FrmLogueoEstacionQX(){
		$Datos=$this->GetLogueoEstacion();
		if (!is_array($Datos)){
			return false;
		}
		$this->salida .= gui_theme_menu_acceso("SELECCION DEL DEPARTAMENTO",$Datos[0],$Datos[1],$Datos[2],ModuloGetURL('system','Menu'));
		return true;
	}

  /**
	*		Encabezado
	*
  *		Esta función despliega la empresa y departamento donde esta trabajando el usuario.
	*		@Author Lorena Aragón G.
	*		@access Private
	*		@return boolean
	*/

  function Encabezado(){
    $this->salida .= "				<table class='modulo_table_title' border='0' width='100%'>\n";
    $this->salida .= "					<tr class='modulo_table_title'>\n";
    $this->salida .= "						<td>Empresa</td>\n";
    $this->salida .= "						<td>Centro Utilidad</td>\n";
    $this->salida .= "						<td>Unidad Funcional</td>\n";
    $this->salida .= "						<td>Departamento</td>\n";
    $this->salida .= "						<td>Bodega</td>\n";
    $this->salida .= "					</tr>\n";
    $this->salida .= "					<tr class='modulo_list_oscuro'>\n";
    $this->salida .= "						<td>".$_SESSION['IYM_PROGRAMACIONES_QX']['NombreEmp']."</td>\n";
    $this->salida .= "						<td>".$_SESSION['IYM_PROGRAMACIONES_QX']['NombreCU']."</td>\n";
    $this->salida .= "						<td>".$_SESSION['IYM_PROGRAMACIONES_QX']['NombreFunc']."</td>\n";
    $this->salida .= "						<td>".$_SESSION['IYM_PROGRAMACIONES_QX']['NombreDpto']."</td>\n";
    $this->salida .= "						<td>".$_SESSION['IYM_PROGRAMACIONES_QX']['NombreBod']."</td>\n";
    $this->salida .= "					</tr>\n";
    $this->salida .= "				</table>\n";
		return true;
	}

	/**
	*		Menu
	*
  *   Funcion que despliega el menu para que el usuario seleccione la opcion
	*		@Author Lorena Aragón G.
	*		@access Private
	*		@return boolean
	*/

	function Menu(){
    unset($_SESSION['IYM_PROGRAMACIONES_QX']['PRODUCTOS_IYM']);
    unset($_SESSION['IYM_PROGRAMACIONES_QX']['No_Programacion']);
    unset($_SESSION['IYM_PROGRAMACIONES_QX']['PRODUCTOS_IYM_CANTIDADES_DES']);
    unset($_SESSION['IYM_PROGRAMACIONES_QX']['PRODUCTOS_IYM_CANTIDADES_DEV']);

    $this->salida .= ThemeAbrirTabla('DESPACHO DE CANASTAS PARA LAS CIRUGIAS');
    $mostrar ="\n<script language='javascript'>\n";
    $mostrar.="function mOvr(src,clrOver) {;\n";
    $mostrar.="src.style.background = clrOver;\n";
    $mostrar.="}\n";
    $mostrar.="function mOut(src,clrIn) {\n";
    $mostrar.="src.style.background = clrIn;\n";
    $mostrar.="}\n";
    $mostrar.="</script>\n";
    $this->salida .="$mostrar";
    $this->Encabezado();
    $this->FormaPacientesEstacionQX();
    //$this->PacientesxIngresar();
    /*$this->salida .="   <table align=\"center\" width=\"100%\"  border=\"0\" >\n";
    $this->salida .="	  <tr class=\"modulo_table_title\"><td>PROGRAMACIONES QUIRURGICAS DEL DEPARTAMENTO</td></tr>\n";
    $programaciones=$this->ProgramacionesQXDepartamento();
    if(sizeof($programaciones)>0){
      $action=ModuloGetURL('app','EstacionEnfermeria_QX','user','LlamaFormaListadoProgramaciones',array("LlenarQuirofanos"=>1));
      $this->salida .="	    <tr class=\"modulo_list_claro\"><td><a href=\"$action\"><img src=\"".GetThemePath()."/images/infor.png\" border=0 width=12 heigth=12>&nbsp&nbsp;Listado Programaciones Activas</a></td></tr>\n";
    }else{
      $this->salida .="	    <tr class=\"modulo_list_claro\"><td><img src=\"".GetThemePath()."/images/infor.png\" border=0 width=12 heigth=12>&nbsp&nbsp;Listado Programaciones Activas</td></tr>\n";
    }
    $this->salida .="   </table>";*/
    $refresh = ModuloGetURL('app','InsumosMedicamentosCirugia','user','Menu');
		$href = ModuloGetURL('app','InsumosMedicamentosCirugia','user','main');
		$this->salida .= "<div class='normal_10' align='center'><br>\n";
		$this->salida .= "	<a href='".$href."'>Regresar</a>&nbsp;&nbsp;-&nbsp;&nbsp;\n";
		$this->salida .= "	<a href='$refresh'>Refrescar</a><br>\n";
		$this->salida .= ThemeCerrarTabla();
    return true;
	}

  /**
	*		FormaPacientesEstacionQX
	*
  *   Funcion que despliega el nombre y la informacion de los pacientes de la estacion de Cirugia
	*		@Author Lorena Aragón G.
	*		@access Private
	*		@return boolean
	*/

	function FormaPacientesEstacionQX(){
    $pacientes=$this->PacientesIngresadosEstacionQX();
    if($pacientes){
    $this->salida .="   <BR><table align=\"center\" width=\"100%\"  border=\"0\" >\n";
    $this->salida .="	  <tr class=\"modulo_table_title\"><td colspan='8'>PACIENTES PARA CIRUGIA</td></tr>\n";
    $this->salida .=" 	<tr class=\"modulo_table_title\">\n";
    $this->salida .=" 	<td><sub>QUIROFANO</sub></td>\n";
    $this->salida .=" 	<td><sub>FECHA</sub></td>\n";
    $this->salida .=" 	<td><sub>HORA INICIO</sub></td>\n";
    $this->salida .=" 	<td><sub>DURACION(HH:mm)</sub></td>\n";
    $this->salida .=" 	<td><sub>NOMBRE PACIENTE</sub></td>\n";
    $this->salida .=" 	<td><sub>CIRUJANO</sub></td>\n";
    $this->salida .=" 	<td><sub>ESTADO</sub></td>\n";
    $this->salida .=" 	<td><sub>&nbsp;</sub></td>\n";
    $this->salida .="  </tr>\n";
    for($i=0;$i<sizeof($pacientes);$i++){
      $backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
      if($p++ % 2){$estilo = "modulo_list_claro";}else{$estilo = "modulo_list_oscuro";}
      $this->salida .= "<tr class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>\n";
      $this->salida .= "	<td width=\"15%\">";
      $this->salida .= "	".$pacientes[$i]['nom_quirofano']."";
      if($pacientes[$i]['programacion_id']){
        $this->salida .= " &nbsp&nbsp&nbsp;";
        $actionVar=ModuloGetURL('app','InsumosMedicamentosCirugia','user','Menu');
        $action=ModuloGetURL('app','InsumosMedicamentosCirugia','user','ConsultaProgramacionQX',array("programacionId"=>$pacientes[$i]['programacion_id'],"action"=>$actionVar));
        $this->salida .= " <a href=\"$action\"><img title=\"Ver Programacion\" border=\"0\" src=\"".GetThemePath()."/images/fecha_fin.png\" height='15'>&nbsp;".$pacientes[$i]['programacion_id']."</a>";
      }
      $this->salida .= " </td>";
      if($pacientes[$i]['hora_inicio'] && $pacientes[$i]['hora_fin']){
        (list($fecha,$hora)=explode(' ',$pacientes[$i]['hora_inicio']));
        (list($ano,$mes,$dia)=explode('-',$fecha));
        (list($hh,$mm)=explode(':',$hora));
        (list($fechaFn,$horaFn)=explode(' ',$pacientes[$i]['hora_fin']));
        (list($anoFn,$mesFn,$diaFn)=explode('-',$fechaFn));
        (list($hhFn,$mmFn)=explode(':',$horaFn));
        $segundos=(mktime($hhFn,$mmFn+1,0,$mesFn,$diaFn,$anoFn)-mktime($hh,$mm,0,$mes,$dia,$ano))/60;
        $horasDur=(int)($segundos/60);
        $minutosDur=$segundos%60;
        if($fecha==date("Y-m-d")){
          $this->salida .= "	<td width=\"10%\" align=\"center\">".$ValorfechaInicia='Dia de Hoy'."</td>";
          $this->salida .= "	<td width=\"10%\" align=\"center\">".$ValorhoraInicia=str_pad($hh,2,0, STR_PAD_LEFT).":".str_pad($mm,2,0, STR_PAD_LEFT)."</td>";
          $this->salida .= "	<td width=\"10%\" align=\"center\">".$ValorhoraFin=str_pad($horasDur,2,0, STR_PAD_LEFT).":".str_pad($minutosDur,2,0, STR_PAD_LEFT)."</td>";
        }else{
          $this->salida .= "	<td width=\"10%\" align=\"center\">".$ValorfechaInicia=ucfirst(strftime("%b %d de %Y",mktime(0,0,0,$mes,$dia,$ano)))."</td>\n";
          $this->salida .= "	<td width=\"10%\" align=\"center\">".$ValorhoraInicia=str_pad($hh,2,0, STR_PAD_LEFT).":".str_pad($mm,2,0, STR_PAD_LEFT)."</td>";
          $this->salida .= "	<td width=\"10%\" align=\"center\">".$ValorhoraFin=str_pad($horasDur,2,0, STR_PAD_LEFT).":".str_pad($minutosDur,2,0, STR_PAD_LEFT)."</td>";
        }
      }else{
        $this->salida .= "	 <td align=\"center\">&nbsp;</td>";
        $this->salida .= "	 <td align=\"center\">&nbsp;</td>";
        $this->salida .= "	 <td align=\"center\">&nbsp;</td>";
      }
      $linkVerDatos = ModuloGetURL('app','EE_PanelEnfermeria','user','CallMostrarDatosIngreso',array("ingresoID"=>$pacientes[$i]['ingreso'],"retorno"=>"Menu","modulito"=>'InsumosMedicamentosCirugia'));
	    $this->salida .= "	<td><a href='$linkVerDatos'>".$pacientes[$i]['nombrepac']."</a></td>\n";
      $this->salida .= "	<td>".$pacientes[$i]['profesional']."</td>";
      $this->salida .= "	<td width=\"10%\">&nbsp;</td>";
      $actionDes = ModuloGetURL('app','InsumosMedicamentosCirugia','user','DespachoCanastasCirugia',array("programacionId"=>$pacientes[$i]['programacion_id'],"profesional"=>$pacientes[$i]['profesional'],"nombrepac"=>$pacientes[$i]['nombrepac'],"ValorfechaInicia"=>$ValorfechaInicia,"ValorhoraInicia"=>$ValorhoraInicia,"ValorhoraFin"=>$ValorhoraFin));
      $this->salida .= "	<td align=\"center\"><a href=\"$actionDes\"><img title=\"Despachos y Devoluciones de Insumos para Cirugia\" border=\"0\" src=\"".GetThemePath()."/images/pparamed.png\" height='15'></a></td>";
      $this->salida .="  </tr>\n";
    }
    $this->salida .="	  <tr class=\"modulo_table_title\"><td colspan='8' height='30'>&nbsp;</td></tr>\n";
    $this->salida .="  </table><BR>";
    }else{
    $this->salida .="   <BR><table align=\"center\" width=\"100%\"  border=\"0\" >\n";
    $this->salida .="	  <tr class=\"label_error\"><td align=\"center\">NO SE ENCONTRARON PACIENTES EN LA ESTACION DE CIRUGIA</td></tr>\n";
    $this->salida .="  </table><BR>";
    }
    return true;
	}


  /**
	*		FormaListadoProgramaciones
	*
  *   Funcion que se encarga de visualizar un error en un campo
	*		@Author Lorena Aragón G.
	*		@access Private
	*		@return boolean
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
	*		AnosAgenda
	*
  *   Funcion que Saca los años para el calendario a partir del año actual
	*		@Author Lorena Aragón G.
	*		@access Private
	*		@return boolean
	*/


  function AnosAgenda($Seleccionado='False',$ano){

		$anoActual=date("Y");
		$anoActual1=$anoActual;
    for($i=0;$i<=10;$i++)	{
      $vars[$i]=$anoActual1;
			$anoActual1=$anoActual1+1;
		}
		switch($Seleccionado){
			case 'False':{
				foreach($vars as $value=>$titulo){
          if($titulo==$ano){
					  $this->salida .=" <option value=\"$titulo\" selected>$titulo</option>";
				  }else{
            $this->salida .=" <option value=\"$titulo\">$titulo</option>";
				  }
				}
				break;
		  }case 'True':{
			  foreach($vars as $value=>$titulo){
					if($titulo==$ano){
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
	*		MesesAgenda
	*
  *   Funcion que Saca los meses para el calendario a partir del año actual
	*		@Author Lorena Aragón G.
	*		@access Private
	*		@return boolean
	*/

	function MesesAgenda($Seleccionado='False',$Año,$Defecto){
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
		switch($Seleccionado){
			case 'False':{
			  if($anoActual==$Año){
			    foreach($vars as $value=>$titulo){
				    if($value>=$mesActual){
						  if($value==$Defecto){
								$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
							}else{
								$this->salida .=" <option value=\"$value\">$titulo</option>";
							}
						}
					}
				}else{
          foreach($vars as $value=>$titulo){
						if($value==$Defecto){
							$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
						}else{
									$this->salida .=" <option value=\"$value\">$titulo</option>";
						}
					}
				}
				break;
			}
			case 'True':{
			  if($anoActual==$Año){
				  foreach($vars as $value=>$titulo){
					  if($value>=$mesActual){
						  if($value==$Defecto){
								$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
							}else{
								$this->salida .=" <option value=\"$value\">$titulo</option>";
							}
						}
					}
				}
				else{
          foreach($vars as $value=>$titulo){
						if($value==$Defecto){
							$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
						}else{
							$this->salida .=" <option value=\"$value\">$titulo</option>";
						}
					}
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
	*		FrmConsultaProgramacionQX
	*
  *   Funcion que muestra laconsulta de los datos de la programacion
	*		@Author Lorena Aragón G.
	*		@access Private
	*		@return boolean
	*/

  function FrmConsultaProgramacionQX($programacionId,$actionVar){

    $this->salida .= ThemeAbrirTabla('DATOS DE LA PROGRAMACION');
    $vector=$this->DatosProgramacionQX($programacionId);
    $this->Encabezado();
    if($vector[0]){
      $this->salida .= "          <BR><table border=\"0\" width=\"90%\" align=\"center\">";
		  $this->salida .= "          <tr><td class=\"modulo_list_claro\">";
			$this->salida .= "          <BR><table border=\"0\" cellspacing=\"3\" cellpadding=\"3\" width=\"95%\" align=\"center\" class=\"normal_10\">";
			$this->salida .= "          <tr class=\"modulo_table_list_title\">";
			$this->salida .= "          <td colspan=\"4\">".strtoupper(strftime("%A %d de  %B de %Y",mktime(0,0,0,date("m"),date("d"),date("Y"))))."</td>";
      $this->salida .= "          </tr>";
      $this->salida .= "          <tr class=\"modulo_list_oscuro\"><td colspan=\"4\">";
      $this->salida .= "            <BR><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
      $this->salida .= "            <tr class=\"modulo_table_list_title\"><td colspan=\"4\">PROGRAMACION DE LA RESERVA</td></tr>";
      $this->salida .= "            <tr class=\"modulo_list_claro\"><td width=\"20%\" class=\"label\">CODIGO PROGRAMACION</td><td>".$vector[0]['programacion_id']."</td><td width=\"20%\" class=\"label\">QUIROFANO</td><td>".$vector[0]['quirofano']."</td></tr>";
      (list($FechaProgramIni,$HoraProgramIni)=explode(' ',$vector[0]['hora_inicio']));
      (list($anoIni,$mesIni,$diaIni)=explode('-',$FechaProgramIni));
      (list($HoraIni,$MinutosIni)=explode(':',$HoraProgramIni));
      (list($FechaProgramFin,$HoraProgramFin)=explode(' ',$vector[0]['hora_fin']));
      (list($anoFin,$mesFin,$diaFin)=explode('-',$FechaProgramFin));
      (list($HoraFin,$MinutosFin)=explode(':',$HoraProgramFin));
      $DuracionMin=(mktime($HoraFin,$MinutosFin,0,$mesFin,$diaFin,$anoFin)-mktime($HoraIni,$MinutosIni,0,$mesIni,$diaIni,$anoIni))/60;
      $HorasDura=(int)($DuracionMin/60);
      $MinutosDura=($DuracionMin%60);
      $Duracion=str_pad($HorasDura,2,0,STR_PAD_LEFT).':'.str_pad($MinutosDura,2,0,STR_PAD_LEFT);
      $this->salida .= "            <tr class=\"modulo_list_claro\"><td width=\"20%\" class=\"label\">HORA INICIO (HH:mm)</td><td>$HoraIni:$MinutosIni</td><td width=\"20%\" class=\"label\">DURACION (HH:mm)</td><td>".$Duracion."</td></tr>";
      $this->salida .= "            <tr class=\"modulo_list_claro\"><td width=\"20%\" class=\"label\">PACIENTE</td><td colspan=\"3\">".$vector[0]['tipo_id_paciente']." ".$vector[0]['paciente_id']." - ".$vector[0]['nombre_pac']."</td></tr>";
      $this->salida .= "            <tr class=\"modulo_list_claro\"><td width=\"20%\" class=\"label\">CIRUJANO PRINCIPAL</td><td colspan=\"3\">".$vector[0]['cirujano']."</td></tr>";
      $this->salida .= "            <tr class=\"modulo_list_claro\"><td width=\"20%\" class=\"label\">DIAGNOSTICO PRINCIPAL</td><td colspan=\"3\">".$vector[0]['diagnostico_nombre']."</td></tr>";
      $this->salida .= "            <tr class=\"modulo_list_claro\"><td width=\"20%\" class=\"label\">PLAN</td><td>".$vector[0]['plan_descripcion']."</td><td width=\"20%\" class=\"label\">RESPONSABLE</td><td>".$vector[0]['tercero_plan']."</td></tr>";
      $this->salida .= "            </table><BR>";
      $datosEquipos=$this->DatosEquiposProgramacionCirugia($vector[0]['qx_quirofano_programacion_id']);
      if($datosEquipos){
        $this->salida .= "            <BR><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= "            <tr class=\"modulo_table_list_title\"><td colspan=\"2\">EQUIPOS RESERVADOS</td></tr>";
        for($i=0;$i<sizeof($datosEquipos);$i++){
          $this->salida .= "          <tr class=\"modulo_list_claro\"><td width=\"20%\" class=\"label\">NOMBRE EQUIPO</td><td>".$datosEquipos[$i]['nom_equipo']."</td></tr>";
        }
        $this->salida .= "            </table><BR>";
      }
      $datosCirugia=$this->DatosCirugia($vector[0]['programacion_id']);
      if($datosCirugia){
        $this->salida .= "            <BR><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= "            <tr class=\"modulo_table_list_title\"><td colspan=\"4\">DATOS DE LA PROGRAMACION</td></tr>";
        $this->salida .= "            <tr class=\"modulo_list_claro\"><td width=\"20%\" class=\"label\">VIA ACCESO</td><td>".$datosCirugia['via']."</td><td width=\"20%\" class=\"label\">TIPO CIRUGIA</td><td>".$datosCirugia['tipo']."</td></tr>";
        $this->salida .= "            <tr class=\"modulo_list_claro\"><td width=\"20%\" class=\"label\">AMBITO</td><td>".$datosCirugia['ambito']."</td><td width=\"20%\" class=\"label\">FINALIDAD</td><td>".$datosCirugia['finalidad']."</td></tr>";
        $this->salida .= "            </table><BR>";
      }
      $this->salida .= "            <BR><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
      $this->salida .= "            <tr class=\"modulo_table_list_title\"><td colspan=\"4\">PROFESIONALES ASIGNADOS</td></tr>";
      $this->salida .= "            <tr class=\"modulo_list_claro\">";
      $this->salida .= "            <td width=\"20%\" class=\"label\">ANESTESIOLOGO</td>";
      if($vector[0]['anestesiologo']){
      $this->salida .= "            <td>".$vector[0]['anestesiologo']."</td>";
      }else{
      $this->salida .= "            <td>SIN ASIGNAR</td>";
      }
      $this->salida .= "            <td width=\"20%\" class=\"label\">AYUDANTE</td>";
      if($vector[0]['ayudante']){
      $this->salida .= "            <td>".$vector[0]['ayudante']."</td></tr>";
      }else{
      $this->salida .= "            <td>SIN ASIGNAR</td>";
      }
      $this->salida .= "            <tr class=\"modulo_list_claro\">";
      $this->salida .= "            <td width=\"20%\" class=\"label\">INSTRUMENTADOR</td>";
      if($vector[0]['instrumentador']){
      $this->salida .= "            <td>".$vector[0]['instrumentador']."</td>";
      }else{
      $this->salida .= "            <td>SIN ASIGNAR</td>";
      }
      $this->salida .= "            <td width=\"20%\" class=\"label\">CIRCULANTE</td>";
      if($vector[0]['circulante']){
      $this->salida .= "            <td>".$vector[0]['circulante']."</td>";
      }else{
      $this->salida .= "            <td>SIN ASIGNAR</td>";
      }
      $this->salida .= "            </tr>";
      $this->salida .= "            </table><BR>";
      if($vector[1]){
        $this->salida .= "            <BR><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= "            <tr class=\"modulo_table_list_title\"><td colspan=\"2\">PROCEDIMIENTOS DE LA PROGRAMACION</td></tr>";
        $this->salida .= "            <tr class=\"modulo_table_list_title\">";
        $this->salida .= "            <td width=\"40%\">CIRUJANO</td>";
        $this->salida .= "            <td width=\"40%\">PROCEDIMIENTO</td>";
        $this->salida .= "            <td width=\"20%\">OBSERVACIONES</td>";
        $this->salida .= "            </tr>";
        for($i=0;$i<sizeof($vector[1]);$i++){
          $this->salida .= "            <tr class=\"modulo_list_claro\">";
          $this->salida .= "            <td>".$vector[1][$i]['cirujano']."</td>";
          $this->salida .= "            <td>".$vector[1][$i]['descripcion']."</td>";
          $this->salida .= "            <td>".$vector[1][$i]['observaciones']."</td>";
          $this->salida .= "            </tr>";
        }
        $this->salida .= "            </table><BR>";
      }
      $this->salida .= "          </td></tr>";
      $this->salida .= "          </table>";
      $this->salida .= "          </td></tr>";
      $this->salida .= "          </table>";
    }
    if($actionVar){
      $href=$actionVar;
    }else{
      $href = ModuloGetURL('app','EstacionEnfermeria_QX','user','LlamaFormaListadoProgramaciones',array("LlenarQuirofanos"=>1));
    }
		$this->salida .= "<div class='normal_10' align='center'><br>\n";
		$this->salida .= "	<a href='".$href."'>Regresar</a>\n";
    $this->salida .= ThemeCerrarTabla();
    return true;
  }

  /**
	*		FrmDespachoCanastasCirugia
	*
  *   Funcion que muestra laconsulta de los datos de la programacion
	*		@Author Lorena Aragón G.
	*		@access Private
	*		@return boolean
	*/

  function FrmDespachoCanastasCirugia($programacionId,$profesional,$nombrepac,$ValorfechaInicia,$ValorhoraInicia,$ValorhoraFin){
    $this->salida .= ThemeAbrirTabla('DESPACHOS Y DEVOLUCIONES DE INSUMOS Y MEDICAMENTOS DE LA CIRUGIA');
    $this->Encabezado();	
    
		$this->salida .= "  <table width=\"70%\" border=\"0\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">DATOS DE LA CIRUGIA</legend>";
		$this->salida .= "    <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"98%\" align=\"center\">";
		$this->salida .= "    <tr class=\"modulo_list_claro\">";
		$this->salida .= "	    <td width=\"25%\" class=\"label\">PROGRAMACION</td>";		 
		$this->salida .= "	   <td>".$programacionId."</td>";
		$this->salida .= "	    <td width=\"25%\" class=\"label\">FECHA</td>";
		$this->salida .= "	    <td>".$ValorfechaInicia."</td>";
		$this->salida .= "    </tr>";        
    $this->salida .= "    <tr class=\"modulo_list_claro\">";
		$this->salida .= "	    <td width=\"25%\" class=\"label\">PACIENTE</td>";
		$this->salida .= "	    <td colspan=\"3\">".$nombrepac."</td>";
		$this->salida .= "    </tr>";
		$this->salida .= "    <tr class=\"modulo_list_claro\">";
		$this->salida .= "	    <td width=\"25%\" class=\"label\">PROFESIONAL</td>";
		$this->salida .= "	    <td colspan=\"3\">".$profesional."</td>";
		$this->salida .= "    </tr>";
		$this->salida .= "    <tr class=\"modulo_list_claro\">";
		$this->salida .= "	    <td width=\"25%\" class=\"label\">HORA INICIO</td>";		 
		$this->salida .= "	   <td>".$ValorhoraInicia."</td>";
		$this->salida .= "	    <td width=\"25%\" class=\"label\">DURACION</td>";
		$this->salida .= "	    <td>".$ValorhoraFin."</td>";
		$this->salida .= "    </tr>";    
		$this->salida .= "	  </table>";
		$this->salida .= "	 </fieldset>";
		$this->salida .= "	 </td></tr>";
		$this->salida .= "	 </table>";
		
		
    $accion = ModuloGetURL('app','InsumosMedicamentosCirugia','user','GuardarProductosCirugiaPac',array("programacionId"=>$programacionId,"profesional"=>$profesional,"nombrepac"=>$nombrepac,"ValorfechaInicia"=>$ValorfechaInicia,"ValorhoraInicia"=>$ValorhoraInicia,"ValorhoraFin"=>$ValorhoraFin));
    $this->salida .= "    <form name=\"forma\" action=\"$accion\" method=\"post\">";
    $this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
    $this->salida .= "     <tr><td colspan=\"4\" align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "     </td></tr>";
    $this->salida .= "     </table>";
    if($_SESSION['IYM_PROGRAMACIONES_QX']['PRODUCTOS_IYM']){
      $this->salida .= "       <table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
      $this->salida .= "        <tr class=\"modulo_table_list_title\">";
      $this->salida .= "          <td width=\"10%\" nowrap>CODIGO</td>";
      $this->salida .= "          <td>PRODUCTO</td>";
	  $this->salida .= "          <td>LOTE</td>";
	  $this->salida .= "          <td>FEC VENCIMIENTO</td>";
      $this->salida .= "          <td width=\"10%\" nowrap>EXISTENCIAS</td>";
      $this->salida .= "          <td width=\"15%\" nowrap colspan=\"2\">CANTIDADES<BR>DESPACHADAS</td>";
      $this->salida .= "          <td width=\"10%\" nowrap>CANTIDAD A<br>DESPACHAR</td>";
      $this->salida .= "          <td width=\"10%\" nowrap>CANTIDADES<BR>DEVUELTAS</td>";
      $this->salida .= "          <td width=\"10%\" nowrap>CANTIDADES<BR>SUMINISTRADAS</td>";
      $this->salida .= "          <td width=\"10%\" nowrap>CANTIDAD A<br>DEVOLVER</td>";
      $this->salida .= "          <td width=\"10%\" nowrap>CANTIDAD A<br>FACTURAR</td>";
      $this->salida .= "        </tr>";
      $CantDespachadas=$_REQUEST['CantDespachadas'];      
      //uasort($_SESSION['IYM_PROGRAMACIONES_QX']['PRODUCTOS_IYM']);
	  
      foreach($_SESSION['IYM_PROGRAMACIONES_QX']['PRODUCTOS_IYM'] as $codigoProducto=>$vector2){
	    $cantidad_P=$this->NumeroVecesProducto($codigoProducto,$lote,$fecha_vencimiento);
		$a = 1;
		foreach($vector2 as $lote=>$vector1){
			foreach($vector1 as $fecha_vencimiento=>$vector){
				foreach($vector as $descripcion=>$existencias){
				
				  $limiteDevol=0;
				  $cadena=$codigoProducto.'||//'.$lote.'||//'.$fecha_vencimiento.'||//'.$descripcion;
				  $regs2=$this->ConsultaProgramacionSuministrosIyM($codigoProducto);               
				  $limiteDevol=$_SESSION['IYM_PROGRAMACIONES_QX']['PRODUCTOS_IYM_CANTIDADES_DES'][$codigoProducto][$lote][$fecha_vencimiento]-$_SESSION['IYM_PROGRAMACIONES_QX']['PRODUCTOS_IYM_CANTIDADES_DEV'][$codigoProducto][$lote][$fecha_vencimiento]-$regs2[cantidad_suministro];
				  if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
				  $this->salida .= "    <tr class=\"$estilo\">";
				  $this->salida .= "      <td>$codigoProducto</td>";
				  $this->salida .= "      <td>$descripcion</td>";
				  $this->salida .= "      <td>$lote</td>";
				  $this->salida .= "      <td>$fecha_vencimiento</td>";
				  $this->salida .= "      <td>$existencias</td>";
				  $divisor=(int)($_SESSION['IYM_PROGRAMACIONES_QX']['PRODUCTOS_IYM_CANTIDADES_DES'][$codigoProducto][$lote][$fecha_vencimiento]);
				  if($_SESSION['IYM_PROGRAMACIONES_QX']['PRODUCTOS_IYM_CANTIDADES_DES'][$codigoProducto][$lote][$fecha_vencimiento]%$divisor){
					$this->salida .= "      <td width=\"7%\">".$_SESSION['IYM_PROGRAMACIONES_QX']['PRODUCTOS_IYM_CANTIDADES_DES'][$codigoProducto][$lote][$fecha_vencimiento]."</td>";
				  }else{
					$this->salida .= "      <td width=\"7%\">".$divisor."</td>";
				  }
				  $registros=$this->ConsultaDespachosIyM($codigoProducto,$lote,$fecha_vencimiento);
				  if($registros){
					$href=ModuloGetURL('app','InsumosMedicamentosCirugia','user','ConsultarRegistrosDespachos',array("Producto"=>urlencode($cadena),"programacionId"=>$programacionId,"profesional"=>$profesional,"nombrepac"=>$nombrepac,"ValorfechaInicia"=>$ValorfechaInicia,"ValorhoraInicia"=>$ValorhoraInicia,"ValorhoraFin"=>$ValorhoraFin));
					$this->salida .= "      <td width=\"7%\" align=\"center\"><a href=\"".$href."\"><img border=\"0\" src=\"".GetThemePath()."/images/pconsultar.png\" title=\"Consultar Despachos\"></a></td>";
				  }else{
					$this->salida .= "      <td width=\"7%\" align=\"center\">&nbsp;</td>";
				  }
				  $this->salida .= "      <td align=\"center\">";
				  $defecto='';
				  if($CantDespachadas[urlencode($cadena)]){$defecto=$CantDespachadas[urlencode($cadena)];}
				  $this->salida .= "      <input type=\"text\" class=\"input-submit\" name=\"CantDespachadas[".urlencode($cadena)."]\" size=\"4\" value=\"$defecto\">&nbsp&nbsp&nbsp;";
				  $this->salida .= "      </td>";
				  $divisor=(int)($_SESSION['IYM_PROGRAMACIONES_QX']['PRODUCTOS_IYM_CANTIDADES_DEV'][$codigoProducto][$lote][$fecha_vencimiento]);
				  if($_SESSION['IYM_PROGRAMACIONES_QX']['PRODUCTOS_IYM_CANTIDADES_DEV'][$codigoProducto][$lote][$fecha_vencimiento]%$divisor){
					$this->salida .= "      <td>".$_SESSION['IYM_PROGRAMACIONES_QX']['PRODUCTOS_IYM_CANTIDADES_DEV'][$codigoProducto][$lote][$fecha_vencimiento]."</td>";
				  }else{
					$this->salida .= "      <td>".$divisor."</td>";
				  }
				  
				  if($a <= 1)
				  {
					$this->salida .= "      <td rowspan=\"".$cantidad_P['cantidad_producto']."\">".(int)($regs2[cantidad_suministro])."</td>";
				  }
				  if($limiteDevol>0){
					$this->salida .= "			<td><select name=\"SeleccionDev[".$codigoProducto."][".$lote."][".$fecha_vencimiento."]\" class=\"select\">";
					$this->salida .="       <option value=\"-1\">-Cantidad-</option>";
					for($l=1;$l<=$limiteDevol;$l++){
					  $this->salida .="     <option value=\"$l\">$l</option>";
					}
					$this->salida .= "      </select></td>";
				  }else{
					$this->salida .= "			<td>&nbsp;</td>";
				  }
				  $cantidadFacturar=$_SESSION['IYM_PROGRAMACIONES_QX']['PRODUCTOS_IYM_CANTIDADES_DES'][$codigoProducto][$lote][$fecha_vencimiento]-$_SESSION['IYM_PROGRAMACIONES_QX']['PRODUCTOS_IYM_CANTIDADES_DEV'][$codigoProducto][$lote][$fecha_vencimiento];
				  $this->salida .= "			<td align=\"right\" class=\"label\">$cantidadFacturar</td>";
				  $this->salida .= "    </tr>";
				  
				$a++;  
				}
			}
		}
      }
      $this->salida .= "      <tr class=\"$estilo\">";
      $this->salida .= "      <td colspan=\"8\" align=\"right\"><input type=\"submit\" class=\"input-submit\" name=\"Despachar\" value=\"DESPACHAR\"></td>";
      $this->salida .= "      <td colspan=\"3\" align=\"right\"><input type=\"submit\" class=\"input-submit\" name=\"Devolver\" value=\"DEVOLVER\"></td>";
      $this->salida .= "			<td>&nbsp;</td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      </table>";
    }else{
      $this->salida .= "      <BR><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
      $this->salida .= "      <tr><td align=\"center\" class=\"label_error\">";
      $this->salida .= "        NO SE HAN SELECCIONADO PRODUCTOS PARA LA CIRUGIA DEL PACIENTE";
      $this->salida .= "      </td></tr>";
      $this->salida .= "      </table>";
    }
    $this->salida .= "      <BR><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
    $this->salida .= "      <tr><td align=\"center\">";
    $this->salida .= "      <input type=\"submit\" name=\"SeleccionPaquete\" value=\"SELECCION PAQUETE\" class=\"input-submit\">";
    $this->salida .= "      <input type=\"submit\" name=\"SeleccionProducto\" value=\"SELECCION PRODUCTO\" class=\"input-submit\">";
    $this->salida .= "      </td></tr>";
    $this->salida .= "      </table>";
    $this->salida .= "    </form>";
    $href = ModuloGetURL('app','InsumosMedicamentosCirugia','user','Menu');
    $this->salida .= "<div class='normal_10' align='center'><br>\n";
		$this->salida .= "	<a href='".$href."'>Regresar</a>\n";
    $this->salida .= ThemeCerrarTabla();
    return true;
  }

  /**
	*		frmDespachoCantidades
	*
  *   Funcion que muestra la consulta de los productos en el inventario
	*		@Author Lorena Aragón G.
	*		@access Private
	*		@return boolean
	*/

  function frmDespachoCantidades($cantidades,$programacionId,$profesional,$nombrepac,$ValorfechaInicia,$ValorhoraInicia,$ValorhoraFin){
    $this->salida .= ThemeAbrirTabla('DESPACHOS DE INSUMOS Y MEDICAMENTOS DE LA CIRUGIA');
    $this->Encabezado();
    $accion = ModuloGetURL('app','InsumosMedicamentosCirugia','user','GuardarDespachoIyM',array("cantidades"=>$cantidades,"programacionId"=>$programacionId,"profesional"=>$profesional,"nombrepac"=>$nombrepac,"ValorfechaInicia"=>$ValorfechaInicia,"ValorhoraInicia"=>$ValorhoraInicia,"ValorhoraFin"=>$ValorhoraFin));
    $this->salida .= "    <form name=\"forma\" action=\"$accion\" method=\"post\">";
    $this->salida .= "      <BR><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
    $this->salida .= "     <tr><td colspan=\"4\" align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "     </td></tr>";
    $this->salida .= "     </table>";
    $this->salida .= "      <BR><table border=\"0\" width=\"60%\" align=\"center\" class=\"normal_10\">";
    $this->salida .= "      <tr class=\"modulo_table_list_title\">";
    $this->salida .= "      <td>PRODUCTO</td><td>LOTE</td><td>FEC VENCIMIENTO</td><td width=\"10%\">CANTIDAD</td>";
    $this->salida .= "      </tr>";
    foreach($cantidades as $producto=>$cantidad){
      $this->salida .= "      <tr class=\"modulo_list_oscuro\">";
      (list($codigoProducto,$lote,$fecha_vencimiento,$descripcion)=explode('||//',urldecode($producto)));
      $this->salida .= "      <td width=\"20%\">$codigoProducto&nbsp;&nbsp;&nbsp;$descripcion</td>";
	  $this->salida .= "      <td>$lote</td>";
      $this->salida .= "      <td>$fecha_vencimiento</td>";
	  $this->salida .= "      <td>$cantidad</td>";
      $this->salida .= "      </tr>";
    }
    $this->salida .= "      </table>";
    $this->salida .= "      <BR><table border=\"0\" width=\"60%\" align=\"center\" class=\"normal_10\">";
    $this->salida .= "      <tr class=\"modulo_table_list_title\"><td colspan=\"2\">DATOS DEL DESPACHO</td></tr>";
    $this->salida .= "			<tr class=\"modulo_list_claro\">";
    $this->salida .= "			<td class=\"".$this->SetStyle("circulante")."\">CIRCULANTE</td>";
    $this->salida .= "			<td><select name=\"circulante\" class=\"select\">";
	  $ciruculantes=$this->profesionalesEspecialistaCiculantes();
    $this->salida .="      <option value=\"-1\">---Seleccione---</option>";
    if($ciruculantes){
    for($i=0;$i<sizeof($ciruculantes);$i++){
      $value=$ciruculantes[$i]['tipo_id_tercero'].'/'.$ciruculantes[$i]['tercero_id'];
      $titulo=$ciruculantes[$i]['nombre'];
      if($value==$_REQUEST['circulante']){
        $this->salida .="   <option value=\"$value\" selected>$titulo</option>";
      }else{
        $this->salida .="   <option value=\"$value\">$titulo</option>";
      }
    }
    }
	  $this->salida .= "       </select></td>";
    $this->salida .= "			 </tr>";
    $this->salida .= "			 <tr class=\"modulo_list_claro\">";
    $this->salida .= "			 <td colspan=\"2\"><label class=\"label\">OBSERVACIONES</label><BR><textarea name=\"observacion\" cols=\"65\" rows=\"3\" class=\"textarea\">".$_REQUEST['observacion']."</textarea></td></td>";
    $this->salida .= "			 </tr>";
    $this->salida .= "			 <tr class=\"modulo_list_claro\">";
    $this->salida .= "			 <td align=\"center\" colspan=\"2\"><input type=\"submit\" class=\"input-submit\" name=\"Guardar\" value=\"DESPACHAR\"></td>";
    $this->salida .= "			 </tr>";
    $this->salida .= "      </table>";
    $this->salida .= "    </form>";
    $href = ModuloGetURL('app','InsumosMedicamentosCirugia','user','DespachoCanastasCirugia',array("programacionId"=>$programacionId,"profesional"=>$profesional,"nombrepac"=>$nombrepac,"ValorfechaInicia"=>$ValorfechaInicia,"ValorhoraInicia"=>$ValorhoraInicia,"ValorhoraFin"=>$ValorhoraFin));
    $this->salida .= "<div class='normal_10' align='center'><br>\n";
		$this->salida .= "	<a href='".$href."'>Regresar</a>\n";
    $this->salida .= ThemeCerrarTabla();
    return true;
  }

  /**
	*		BuscadorProductoInv
	*
  *   Funcion que muestra la consulta de los productos en el inventario
	*		@Author Lorena Aragón G.
	*		@access Private
	*		@return boolean
	*/

  function BuscadorProductoInv($codigoBus,$DescripcionBus,$programacionId,$profesional,$nombrepac,$ValorfechaInicia,$ValorhoraInicia,$ValorhoraFin){
    
    $this->salida .= ThemeAbrirTabla('BUSCADOR PRODUCTOS INVENTARIOS');
		$action=ModuloGetURL('app','InsumosMedicamentosCirugia','user','LlamaBuscadorProductoInv',array("programacionId"=>$programacionId,"profesional"=>$profesional,"nombrepac"=>$nombrepac,"ValorfechaInicia"=>$ValorfechaInicia,"ValorhoraInicia"=>$ValorhoraInicia,"ValorhoraFin"=>$ValorhoraFin));
		$this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->Encabezado();
    $this->salida .= "    <BR><table width=\"80%\" border=\"0\" align=\"center\">";
		$this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"4\">PARAMENTROS DE BUSQUEDA</td></tr>";
		$this->salida .= "    <tr class=\"modulo_list_claro\">";
    $this->salida .= "     <td class=\"label\">CODIGO</td>";
		$this->salida .= "		  <td><input type=\"text\" class=\"input-text\" name=\"codigoBus\" size=\"10\" value=\"$codigoBus\"></td>";
		$this->salida .= "     <td class=\"label\">DESCRIPCION</td>";
    $this->salida .= "     <td><input size=\"70\" type=\"text\" name=\"DescripcionBus\" value=\"".$DescripcionBus."\" class=\"input-submit\"></td>";
    $this->salida .= "    </tr>";
    $this->salida .= "    <tr class=\"modulo_list_claro\"><td align=\"center\" colspan=\"4\">";
		$this->salida .= "     <input class=\"input-submit\" type=\"submit\" name=\"Filtrar\" value=\"FILTRAR\" class=\"input-submit\">";
		$this->salida .= "     <input class=\"input-submit\" type=\"submit\" name=\"Volver\" value=\"VOLVER\" class=\"input-submit\">";
    $this->salida .= "    </td></tr>";
		$this->salida .= "	  </table><BR>";
		$ProductosBodega=$this->ProductosInventariosBodega($codigoBus,$DescripcionBus);
    if($ProductosBodega){
			$this->salida .= "    <table width=\"80%\" border=\"0\" align=\"center\">";
			$this->salida .= "    <tr class=\"modulo_table_list_title\">";
			$this->salida .= "    <td width=\"20%\">CODIGO</td>";
			$this->salida .= "    <td>DESCRIPCION</td>";
			$this->salida .= "    <td width=\"15%\">LOTE</td>";
			$this->salida .= "    <td width=\"15%\">FEC VENCIMIENTO</td>";
			$this->salida .= "    <td width=\"15%\">EXISTENCIAS</td>";
			$this->salida .= "    <td width=\"5%\">&nbsp;</td>";
			$this->salida .= "    </tr>";
			for($i=0;$i<sizeof($ProductosBodega);$i++){
        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
        $this->salida .= "    <tr class=\"$estilo\">";
        $this->salida .= "    <td>".$ProductosBodega[$i]['codigo_producto']."</td>";
				$this->salida .= "    <td>".$ProductosBodega[$i]['descripcion']."</td>";
				$this->salida .= "    <td>".$ProductosBodega[$i]['lote']."</td>";
				$this->salida .= "    <td>".$ProductosBodega[$i]['fecha_vencimiento']."</td>";
        $this->salida .= "    <td>".$ProductosBodega[$i]['existencia']."</td>";
        if($_SESSION['IYM_PROGRAMACIONES_QX']['PRODUCTOS_IYM'][$ProductosBodega[$i]['codigo_producto']]){
          $this->salida .= "    <td align=\"center\" width=\"5%\"><img title=\"Seleccionar Producto\" border=\"0\" src=\"".GetThemePath()."/images/checksi.png\"></td>";
        }else{
				  $actionSelect=ModuloGetURL('app','InsumosMedicamentosCirugia','user','SeleccionProductoInventariosQx',array("producto"=>$ProductosBodega[$i]['codigo_producto'],"lote"=>$ProductosBodega[$i]['lote'],"fecha_vencimiento"=>$ProductosBodega[$i]['fecha_vencimiento'],"descripcion"=>$ProductosBodega[$i]['descripcion'],"existencia"=>$ProductosBodega[$i]['existencia'],"programacionId"=>$programacionId,"profesional"=>$profesional,"nombrepac"=>$nombrepac,"ValorfechaInicia"=>$ValorfechaInicia,"ValorhoraInicia"=>$ValorhoraInicia,"ValorhoraFin"=>$ValorhoraFin,'codigoBus'=>$codigoBus,'DescripcionBus'=>$DescripcionBus));
				  $this->salida .= "    <td align=\"center\" width=\"5%\"><a href=\"$actionSelect\"><img title=\"Seleccionar Producto\" border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"></a></td>";
        }
				$this->salida .= "    </tr>";
				$y++;
			}
			$this->salida .= "	  </table><BR>";
			$Paginador = new ClaseHTML();
      $this->actionPaginador=ModuloGetURL('app','InsumosMedicamentosCirugia','user','LlamaBuscadorProductoInv',array("codigoBus"=>$codigoBus,"DescripcionBus"=>$DescripcionBus,"programacionId"=>$programacionId,"profesional"=>$profesional,"nombrepac"=>$nombrepac,"ValorfechaInicia"=>$ValorfechaInicia,"ValorhoraInicia"=>$ValorhoraInicia,"ValorhoraFin"=>$ValorhoraFin));
			$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->actionPaginador);
		}else{
      $this->salida .= "    <table width=\"60%\" border=\"0\" align=\"center\">";
      $this->salida .= "    <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON REGISTROS CON ESTOS PARAMETROS</td></tr>";
      $this->salida .= "	  </table><BR>";
		}
    $this->salida .= "		</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
  }

  /**
	*		FrmConsultarRegistrosDespachos
	*
  *   Funcion que muestra la consulta de los productos en el inventario
	*		@Author Lorena Aragón G.
	*		@access Private
	*		@return boolean
	*/

  function FrmConsultarRegistrosDespachos($Producto,$programacionId,$profesional,$nombrepac,$ValorfechaInicia,$ValorhoraInicia,$ValorhoraFin){
    (list($codigoProducto,$lote,$fecha_vencimiento,$descripcion)=explode('||//',$Producto));
    $this->salida .= ThemeAbrirTabla('CONSULTA DE DESPACHOS DE INSUMOS Y MEDICAMENTOS');
		$this->Encabezado();
		//$action=ModuloGetURL('app','InsumosMedicamentosCirugia','user','LlamaBuscadorProductoInv');
		$this->salida .= "  <table width=\"70%\" border=\"0\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">DATOS DE LA CIRUGIA</legend>";
		$this->salida .= "    <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"98%\" align=\"center\">";
		$this->salida .= "    <tr class=\"modulo_list_claro\">";
		$this->salida .= "	    <td width=\"25%\" class=\"label\">PROGRAMACION</td>";		 
		$this->salida .= "	   <td>".$programacionId."</td>";
		$this->salida .= "	    <td width=\"25%\" class=\"label\">FECHA</td>";
		$this->salida .= "	    <td>".$ValorfechaInicia."</td>";
		$this->salida .= "    </tr>";        
    $this->salida .= "    <tr class=\"modulo_list_claro\">";
		$this->salida .= "	    <td width=\"25%\" class=\"label\">PACIENTE</td>";
		$this->salida .= "	    <td colspan=\"3\">".$nombrepac."</td>";
		$this->salida .= "    </tr>";
		$this->salida .= "    <tr class=\"modulo_list_claro\">";
		$this->salida .= "	    <td width=\"25%\" class=\"label\">PROFESIONAL</td>";
		$this->salida .= "	    <td colspan=\"3\">".$profesional."</td>";
		$this->salida .= "    </tr>";
		$this->salida .= "    <tr class=\"modulo_list_claro\">";
		$this->salida .= "	    <td width=\"25%\" class=\"label\">HORA INICIO</td>";		 
		$this->salida .= "	   <td>".$ValorhoraInicia."</td>";
		$this->salida .= "	    <td width=\"25%\" class=\"label\">DURACION</td>";
		$this->salida .= "	    <td>".$ValorhoraFin."</td>";
		$this->salida .= "    </tr>";    
		$this->salida .= "	  </table>";
		$this->salida .= "	 </fieldset>";
		$this->salida .= "	 </td></tr>";
		$this->salida .= "	 </table>";	
		$this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
		
    $registros=$this->ConsultaDespachosIyM($codigoProducto,$lote,$fecha_vencimiento);
    if($registros){
      $this->salida .= "    <BR><table width=\"85%\" border=\"0\" align=\"center\">";
      $this->salida .= "    <tr class=\"modulo_table_list_title\">";
      $this->salida .= "    <td colspan=\"5\">$codigoProducto&nbsp&nbsp&nbsp&nbsp;$descripcion</td>";
      $this->salida .= "    </tr>";
	  $this->salida .= "    <tr class=\"modulo_table_list_title\">";
      $this->salida .= "    <td colspan=\"2\">LOTE: '".$lote."'</td>";
	  $this->salida .= "    <td colspan=\"3\">FEC VENCIMIENTO: '".$fecha_vencimiento."'</td>";
      $this->salida .= "    </tr>";
      $this->salida .= "    <tr class=\"modulo_table_list_title\">";
			$this->salida .= "    <td width=\"15%\" nowrap>CANTIDAD</td>";
			$this->salida .= "    <td width=\"25%\" nowrap>CIRCULANTE<br>ENTREGA</td>";
      $this->salida .= "    <td width=\"15%\" nowrap>FECHA REGISTRO</td>";
			$this->salida .= "    <td width=\"15%\" nowrap>USUARIO</td>";
      $this->salida .= "    <td>OBSERVACIONES</td>";
			$this->salida .= "    </tr>";
      for($i=0;$i<sizeof($registros);$i++){
        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
        $this->salida .= "    <tr class=\"$estilo\">";
        $divisor=(int)($registros[$i]['cantidad']);
        if($registros[$i]['cantidad']%$divisor){
          $this->salida .= "    <td>".$registros[$i]['cantidad']."</td>";
        }else{
          $this->salida .= "    <td>".$divisor."</td>";
        }
				$this->salida .= "     <td>".$registros[$i]['nombre_tercero']."</td>";
        (list($fecha,$HoraTot)=explode(' ',$registros[$i]['fecha_registro']));
        (list($ano,$mes,$dia)=explode('-',$fecha));
        (list($Hora,$Min)=explode(':',$HoraTot));
        $this->salida .= "	   <td>".ucfirst(strftime("%b %d de %Y %H:%M",mktime($Hora,$Min,0,$mes,$dia,$ano)))."</td>";
        $this->salida .= "    <td>".$registros[$i]['nombre_usuario']."</td>";
        $this->salida .= "    <td>".$registros[$i]['observaciones']."</td>";
				$this->salida .= "    </tr>";
				$y++;
      }
      $this->salida .= "    </table>";
    }
    $this->salida .= "		</form>";
    $href = ModuloGetURL('app','InsumosMedicamentosCirugia','user','DespachoCanastasCirugia',array("programacionId"=>$programacionId,"profesional"=>$profesional,"nombrepac"=>$nombrepac,"ValorfechaInicia"=>$ValorfechaInicia,"ValorhoraInicia"=>$ValorhoraInicia,"ValorhoraFin"=>$ValorhoraFin));
    $this->salida .= "<div class='normal_10' align='center'><br>\n";
		$this->salida .= "	<a href='".$href."'>Regresar</a>\n";
		$this->salida .= ThemeCerrarTabla();
		return true;
  }

  /**
	*		FrmConsultarRegistrosDespachos
	*
  *   Funcion que muestra la consulta de los productos en el inventario
	*		@Author Lorena Aragón G.
	*		@access Private
	*		@return boolean
	*/


  function BuscadorPaquetesInv($codigoBus,$DescripcionBus,$programacionId,$profesional,$nombrepac,$ValorfechaInicia,$ValorhoraInicia,$ValorhoraFin){
    $this->paginaActual = 1;
    $this->offset = 0;
    if($_REQUEST['offset']){
      $this->paginaActual = intval($_REQUEST['offset']);
      if($this->paginaActual > 1){
        $this->offset = ($this->paginaActual - 1) * ($this->limit);
      }
    }
    $this->salida .= ThemeAbrirTabla('BUSCADOR PAQUETES INVENTARIOS');
		$action=ModuloGetURL('app','InsumosMedicamentosCirugia','user','SeleccionPaquetesInventariosQx',array("offset"=>$this->paginaActual,"programacionId"=>$programacionId,"profesional"=>$profesional,"nombrepac"=>$nombrepac,"ValorfechaInicia"=>$ValorfechaInicia,"ValorhoraInicia"=>$ValorhoraInicia,"ValorhoraFin"=>$ValorhoraFin));
		$this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->Encabezado();
    $this->salida .= "    <BR><table width=\"80%\" border=\"0\" align=\"center\">";
		$this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"4\">PARAMENTROS DE BUSQUEDA</td></tr>";
		$this->salida .= "    <tr class=\"modulo_list_claro\">";
    $this->salida .= "     <td class=\"label\">CODIGO PAQUETE</td>";
		$this->salida .= "		  <td><input type=\"text\" class=\"input-text\" name=\"codigoBus\" size=\"10\" value=\"$codigoBus\"></td>";
		$this->salida .= "     <td class=\"label\">DESCRIPCION</td>";
    $this->salida .= "     <td><input size=\"70\" type=\"text\" name=\"DescripcionBus\" value=\"".$DescripcionBus."\" class=\"input-submit\"></td>";
    $this->salida .= "    </tr>";
    $this->salida .= "    <tr class=\"modulo_list_claro\"><td align=\"center\" colspan=\"4\">";
		$this->salida .= "     <input class=\"input-submit\" type=\"submit\" name=\"Filtrar\" value=\"FILTRAR\" class=\"input-submit\">";
		$this->salida .= "     <input class=\"input-submit\" type=\"submit\" name=\"Volver\" value=\"VOLVER\" class=\"input-submit\">";
    $this->salida .= "    </td></tr>";
		$this->salida .= "	  </table><BR>";
		$PaquetesBodega=$this->PaquetesInventariosBodega($codigoBus,$DescripcionBus);
    if($PaquetesBodega){
		  $this->salida .= "    <table width=\"80%\" border=\"0\" align=\"center\">";
      $this->salida .= "    <tr class=\"modulo_table_list_title\">";
			$this->salida .= "    <td width=\"20%\">CODIGO</td>";
			$this->salida .= "    <td>DESCRIPCION</td>";
      $this->salida .= "    <td width=\"5%\">&nbsp;</td>";
			$this->salida .= "    <td width=\"5%\">&nbsp;</td>";
			$this->salida .= "    </tr>";
			for($i=0;$i<sizeof($PaquetesBodega);$i++){
        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
        $this->salida .= "    <tr class=\"$estilo\">";
        $this->salida .= "    <td>".$PaquetesBodega[$i]['paquete_insumos_id']."</td>";
				$this->salida .= "    <td>".$PaquetesBodega[$i]['descripcion']."</td>";
        $actionSelect=ModuloGetURL('app','InsumosMedicamentosCirugia','user','ConsultaPaquetesInventariosQx',array("paqueteId"=>$PaquetesBodega[$i]['paquete_insumos_id'],"nomPaquete"=>$PaquetesBodega[$i]['descripcion'],"codigoBus"=>$codigoBus,"DescripcionBus"=>$DescripcionBus,"programacionId"=>$programacionId,"profesional"=>$profesional,"nombrepac"=>$nombrepac,"ValorfechaInicia"=>$ValorfechaInicia,"ValorhoraInicia"=>$ValorhoraInicia,"ValorhoraFin"=>$ValorhoraFin));
				$this->salida .= "    <td align=\"center\" width=\"5%\"><a href=\"$actionSelect\"><img title=\"Consultar Productos Paquete\" border=\"0\" src=\"".GetThemePath()."/images/pconsultar.png\"><a></td>";
        $actionPaq=ModuloGetURL('app','InsumosMedicamentosCirugia','user','SeleccionPtosPaqueteInv',array("paqueteId"=>$PaquetesBodega[$i]['paquete_insumos_id'],"programacionId"=>$programacionId,"profesional"=>$profesional,"nombrepac"=>$nombrepac,"ValorfechaInicia"=>$ValorfechaInicia,"ValorhoraInicia"=>$ValorhoraInicia,"ValorhoraFin"=>$ValorhoraFin));
				$this->salida .= "    <td align=\"center\" width=\"5%\"><a href=\"$actionPaq\"><img title=\"Seleccionar Paquete\" border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"></a></td>";
				$this->salida .= "    </tr>";
				$y++;
			}
			$this->salida .= "	  </table>";
			$Paginador = new ClaseHTML();
      $this->actionPaginador=ModuloGetURL('app','InsumosMedicamentosCirugia','user','SeleccionPaquetesInventariosQx',array("codigoBus"=>$codigoBus,"DescripcionBus"=>$DescripcionBus,"programacionId"=>$programacionId,"profesional"=>$profesional,"nombrepac"=>$nombrepac,"ValorfechaInicia"=>$ValorfechaInicia,"ValorhoraInicia"=>$ValorhoraInicia,"ValorhoraFin"=>$ValorhoraFin));
			$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->actionPaginador);
		}else{
      $this->salida .= "    <table width=\"60%\" border=\"0\" align=\"center\">";
      $this->salida .= "    <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON REGISTROS CON ESTOS PARAMETROS</td></tr>";
      $this->salida .= "	  </table><BR>";
		}
    $this->salida .= "		</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
  }

  /**
	*		LlamaConsultaPaquetesInventariosQx
	*
  *   Funcion que muestra la consulta de los productos en el inventario
	*		@Author Lorena Aragón G.
	*		@access Private
	*		@return boolean
	*/

  function LlamaConsultaPaquetesInventariosQx($paqueteId,$nomPaquete,$codigoBus,$DescripcionBus,$programacionId,$profesional,$nombrepac,$ValorfechaInicia,$ValorhoraInicia,$ValorhoraFin){

    $this->salida .= ThemeAbrirTabla('PRODUCTOS QUE CONTIENEN LOS PAQUETES');
		$action=ModuloGetURL('app','InsumosMedicamentosCirugia','user','SeleccionPaquetesInventariosQx',array("codigoBus"=>$codigoBus,"DescripcionBus"=>$DescripcionBus,"programacionId"=>$programacionId,"profesional"=>$profesional,"nombrepac"=>$nombrepac,"ValorfechaInicia"=>$ValorfechaInicia,"ValorhoraInicia"=>$ValorhoraInicia,"ValorhoraFin"=>$ValorhoraFin));
		$this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->Encabezado();
    $ProductosPaquetes=$this->ProductosPaquetesInventariosBodega($paqueteId);
    if($ProductosPaquetes){
		  $this->salida .= "    <BR><table width=\"80%\" border=\"0\" align=\"center\">";
      $this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"3\" align=\"3\">$nomPaquete</td></tr>";
      $this->salida .= "    <tr class=\"modulo_table_list_title\">";
			$this->salida .= "    <td width=\"20%\">CODIGO</td>";
			$this->salida .= "    <td>DESCRIPCION</td>";
      $this->salida .= "    <td width=\"10%\">CANTIDAD</td>";
			$this->salida .= "    </tr>";
			for($i=0;$i<sizeof($ProductosPaquetes);$i++){
        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
        $this->salida .= "    <tr class=\"$estilo\">";
        $this->salida .= "    <td>".$ProductosPaquetes[$i]['codigo_producto']."</td>";
				$this->salida .= "    <td>".$ProductosPaquetes[$i]['descripcion']."</td>";
        $this->salida .= "    <td>".$ProductosPaquetes[$i]['cantidad']."</td>";
				$this->salida .= "    </tr>";
				$y++;
			}
			$this->salida .= "	  </table>";
		}else{
      $this->salida .= "    <BR><table width=\"60%\" border=\"0\" align=\"center\">";
      $this->salida .= "    <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON REGISTROS CON ESTOS PARAMETROS</td></tr>";
      $this->salida .= "	  </table>";
		}
    $this->salida .= "    <table width=\"60%\" border=\"0\" align=\"center\">";
    $this->salida .= "    <tr><td align=\"center\"><input type=\"submit\" value=\"VOLVER\" class=\"input-submit\" name=\"volveer\"></td></tr>";
    $this->salida .= "	  </table>";
    $this->salida .= "		</form>";
    $this->salida .= ThemeCerrarTabla();
		return true;
  }






}//fin class
?>
