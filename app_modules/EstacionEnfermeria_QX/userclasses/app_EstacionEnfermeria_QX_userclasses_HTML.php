<?

 /**
 * $Id: app_EstacionEnfermeria_QX_userclasses_HTML.php,v 1.18 2006/06/29 21:13:06 lorena Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo de Estacion de Enfermeria de Cirugia para la atencion del paciente
 */



/**
*		class app_EstacionEnfermeria_QX_userclasses_HTML
*
*		Clase que maneja todas las funciones de vistas y consultas a la base de datos
*		relacionadas a la estaci&oacute;n de Enfermer&iacute;a de Cirugia
*		ubicacion => app_modules/EstacionEnfermeria_QX/userclasses/app_EstacionEnfermeria_QX_userclasses_HTML.php
*		fecha creaci&oacute;n => 04/05/2004 10:35 am
*
*		@Author Lorena Aragón G.
*		@version =>
*		@package SIIS
*/
class app_EstacionEnfermeria_QX_userclasses_HTML extends app_EstacionEnfermeria_QX_user
{

	/**
	*		app_EstacionEnfermeria_QX_userclasses_HTML()
	*
	*		constructor
	*
	*		@Author Lorena Aragón G.
	*		@access Private
	*		@return boolean
	*/
	function app_EstacionEnfermeria_QX_userclasses_HTML(){
	  $this->app_EstacionEnfermeria_QX_user(); //Constructor del padre 'modulo'
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
    unset($_SESSION['LocalCirugias']);
    unset($_SESSION['ESTACION_ENFERMERIA_QX']['ACCION']);
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
    $this->salida .= "					</tr>\n";
    $this->salida .= "					<tr class='modulo_list_oscuro'>\n";
    $this->salida .= "						<td>".$_SESSION['ESTACION_ENFERMERIA_QX']['NombreEmp']."</td>\n";
    $this->salida .= "						<td>".$_SESSION['ESTACION_ENFERMERIA_QX']['NombreCU']."</td>\n";
    $this->salida .= "						<td>".$_SESSION['ESTACION_ENFERMERIA_QX']['NombreFunc']."</td>\n";
    $this->salida .= "						<td>".$_SESSION['ESTACION_ENFERMERIA_QX']['NombreDpto']."</td>\n";
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
    $this->salida .= ThemeAbrirTabla('MENU ESTACION DE ENFERMERIA DE CIRUGIA');
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
    $this->salida .= "   <table border=\"0\" width=\"95%\" align=\"center\">";
    $this->salida .= "  <tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "  </td></tr>";
    $this->salida .= "  </table>";
    $this->FormaPacientesEstacionQX();
    $this->PacientesxIngresar();
    $this->salida .="   <table align=\"center\" width=\"100%\"  border=\"0\" >\n";
    $this->salida .="	  <tr class=\"modulo_table_title\"><td>PROGRAMACIONES QUIRURGICAS DEL DEPARTAMENTO</td></tr>\n";
    $programaciones=$this->ProgramacionesQXDepartamento();
    if(sizeof($programaciones)>0){
      $action=ModuloGetURL('app','EstacionEnfermeria_QX','user','LlamaFormaListadoProgramaciones',array("LlenarQuirofanos"=>1));
      $this->salida .="	    <tr class=\"modulo_list_claro\"><td><a href=\"$action\"><img src=\"".GetThemePath()."/images/infor.png\" border=0 width=12 heigth=12>&nbsp&nbsp;Listado Programaciones Activas</a></td></tr>\n";
    }else{
      $this->salida .="	    <tr class=\"modulo_list_claro\"><td><img src=\"".GetThemePath()."/images/infor.png\" border=0 width=12 heigth=12>&nbsp&nbsp;Listado Programaciones Activas</td></tr>\n";
    }
    $this->salida .="   </table>";
    $refresh = ModuloGetURL('app','EstacionEnfermeria_QX','user','Menu');
		$href = ModuloGetURL('app','EstacionEnfermeria_QX','user','main');
		$this->salida .= "<div class='normal_10' align='center'><br>\n";
		$this->salida .= "	<a href='".$href."'>Volver</a>&nbsp;&nbsp;-&nbsp;&nbsp;\n";
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
    $this->salida .="	  <tr class=\"modulo_table_title\"><td colspan='5'>PACIENTES EN LA ESTACION</td></tr>\n";
    $this->salida .=" 	<tr class=\"modulo_table_title\">\n";
    $this->salida .=" 	<td><sub>QUIROFANO</sub></td>\n";
    $this->salida .=" 	<td><sub>HORA / DURACION</sub></td>\n";
    $this->salida .=" 	<td><sub>NOMBRE PACIENTE</sub></td>\n";
    $this->salida .=" 	<td><sub>CIRUJANO</sub></td>\n";
    $this->salida .=" 	<td><sub>ESTADO</sub></td>\n";
    $this->salida .="  </tr>\n";
    for($i=0;$i<sizeof($pacientes);$i++){
      $backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
      if($p++ % 2){$estilo = "modulo_list_claro";}else{$estilo = "modulo_list_oscuro";}
      $this->salida .= "<tr class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>\n";
      $this->salida .= "	<td width=\"15%\">";
      $this->salida .= "	".$pacientes[$i]['nom_quirofano']."";
      if($pacientes[$i]['programacion_id']){
        $this->salida .= " &nbsp&nbsp&nbsp;";
        $actionVar=ModuloGetURL('app','EstacionEnfermeria_QX','user','Menu');
        $action=ModuloGetURL('app','EstacionEnfermeria_QX','user','ConsultaProgramacionQX',array("programacionId"=>$pacientes[$i]['programacion_id'],"actionVar"=>$actionVar));
        $this->salida .= " <a href=\"$action\"><img title=\"Ver Programacion\" border=\"0\" src=\"".GetThemePath()."/images/fecha_fin.png\" height='15'>&nbsp;".$pacientes[$i]['programacion_id']."</a>";
      }
      $this->salida .= " </td>";
      $this->salida .= "	<td align=\"center\" width=\"20%\">";
      if($pacientes[$i]['hora_inicio'] && $pacientes[$i]['hora_fin']){
        (list($fecha,$hora)=explode(' ',$pacientes[$i]['hora_inicio']));
        (list($ano,$mes,$dia)=explode('-',$fecha));
        (list($hh,$mm)=explode(':',$hora));
        (list($fechaFn,$horaFn)=explode(' ',$pacientes[$i]['hora_fin']));
        (list($anoFn,$mesFn,$diaFn)=explode('-',$fechaFn));
        (list($hhFn,$mmFn)=explode(':',$horaFn));
        $segundos=(mktime($hhFn,$mmFn,0,$mesFn,$diaFn,$anoFn)-mktime($hh,$mm,0,$mes,$dia,$ano))/60;
        $horasDur=(int)($segundos/60);
        $minutosDur=$segundos%60;
        if($fecha==date("Y-m-d")){
          $this->salida .="  </b>Hoy a las ";
        }else{
          $this->salida .= "	".ucfirst(strftime("%b %d de %Y",mktime(0,0,0,$mes,$dia,$ano)))."\n";
        }
        $this->salida .="   $hh:$mm / ";
        $this->salida .="   $horasDur:$minutosDur (HH:mm)</BR>";
      }else{
        $this->salida .= "	 &nbsp;";
      }
      $this->salida .= "	 </td>";
      $linkVerDatos = ModuloGetURL('app','EE_PanelEnfermeria','user','CallMostrarDatosIngreso',array("ingresoID"=>$pacientes[$i]['ingreso'],"retorno"=>"Menu","modulito"=>'EstacionEnfermeria_QX'));
	    $this->salida .= "	<td><a href='$linkVerDatos'>".$pacientes[$i]['nombrepac']."</a></td>\n";
      $this->salida .= "	<td>".$pacientes[$i]['profesional']."</td>";
      $this->salida .= "	<td width=\"10%\">&nbsp;</td>";
      $this->salida .="  </tr>\n";
    }
    $this->salida .="	  <tr class=\"modulo_table_title\"><td colspan='5' height='30'>&nbsp;</td></tr>\n";
    $this->salida .="  </table><BR>";
    }else{
    $this->salida .="   <BR><table align=\"center\" width=\"100%\"  border=\"0\" >\n";
    $this->salida .="	  <tr class=\"label_error\"><td align=\"center\">NO SE ENCONTRARON PACIENTES EN LA ESTACION DE CIRUGIA</td></tr>\n";
    $this->salida .="  </table><BR>";
    }
    return true;
	}

  /**
	*		PacientesxIngresar
	*
  *   Funcion que despliega el nombre y la informacion de los pacientes de la estacion de Cirugia pendientes por ingresar
	*		@Author Lorena Aragón G.
	*		@access Private
	*		@return boolean
	*/


  function PacientesxIngresar(){
    $pacientes=$this->PacientesPendientesXIngresar();    
    if($pacientes){
      $this->salida .="   <BR><table align=\"center\" width=\"100%\"  border=\"0\" >\n";
      $this->salida .="	  <tr class=\"modulo_table_title\"><td colspan='5'>PACIENTES PENDIENTES X INGRESAR</td></tr>\n";
      $this->salida .=" 	<tr class=\"modulo_table_title\">\n";
      $this->salida .=" 	<td width=\"5%\">&nbsp;</td>\n";
      $this->salida .=" 	<td><sub>NOMBRE PACIENTE</sub></td>\n";
      $this->salida .=" 	<td><sub>FECHA EN QUE SE REALIZARA EL INGRESO</sub></td>\n";
      $this->salida .="   <td width=\"5%\">&nbsp;</td>\n";      

      $this->salida .="  </tr>\n";
      for($i=0;$i<sizeof($pacientes);$i++){
        $backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
        if($p++ % 2){$estilo = "modulo_list_claro";}else{$estilo = "modulo_list_oscuro";}
        $this->salida .= "<tr class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>\n";
        if(empty($pacientes[$i]['programacion_id'])||empty($pacientes[$i]['qx_quirofano_programacion_id'])){
          $action=ModuloGetURL('app','EstacionEnfermeria_QX','user','ReservaQuirofanoProgramacion',array("numeroRegistro"=>$pacientes[$i]['numero_registro']));
          $this->salida .= "<td align=\"center\"><a href=\"$action\" title=\"Asignar Quirofano\"><img height='15' border=\"0\" src=\"".GetThemePath()."/images/encirugia.png\"></td>";
        }elseif(empty($pacientes[$i]['procedimientos'])){
          $_SESSION['LocalCirugias']['NombreEmp']=$_SESSION['ESTACION_ENFERMERIA_QX']['NombreEmp'];  
          $_SESSION['LocalCirugias']['NombreCU']=$_SESSION['ESTACION_ENFERMERIA_QX']['NombreCU'];          
          $_SESSION['LocalCirugias']['NombreDpto']=$_SESSION['ESTACION_ENFERMERIA_QX']['NombreDpto'];              
          $_SESSION['LocalCirugias']['empresa']=$_SESSION['ESTACION_ENFERMERIA_QX']['Empresa'];
          $_SESSION['LocalCirugias']['CentroUtili']=$_SESSION['ESTACION_ENFERMERIA_QX']['CentroUtili'];
          $_SESSION['LocalCirugias']['departamento']=$_SESSION['ESTACION_ENFERMERIA_QX']['Departamento'];
          
          $_SESSION['ESTACION_ENFERMERIA_QX']['ACCION']=1;          
          $action=ModuloGetURL('app','Quirurgicos','user','consultarDetallePrograma',array("ProgramacionId"=>$pacientes[$i]['programacion_id'],"actionVar"=>$actionVar,"mayorfecha"=>1));
          $this->salida .= "<td align=\"center\"><a href=\"$action\"><img title=\"Finalizar Programacion\" border=\"0\" src=\"".GetThemePath()."/images/pinactivo.png\" height='15'>&nbsp;".$pacientes[$i]['programacion_id']."</a></td>";          
        }else{
          $actionVar=ModuloGetURL('app','EstacionEnfermeria_QX','user','Menu');
          $action=ModuloGetURL('app','EstacionEnfermeria_QX','user','ConsultaProgramacionQX',array("programacionId"=>$pacientes[$i]['programacion_id'],"actionVar"=>$actionVar));
          $this->salida .= "<td align=\"center\"><a href=\"$action\"><img title=\"Ver Programacion\" border=\"0\" src=\"".GetThemePath()."/images/fecha_fin.png\" height='15'>&nbsp;".$pacientes[$i]['programacion_id']."</a></td>";
        }
        $linkVerDatos = ModuloGetURL('app','EE_PanelEnfermeria','user','CallMostrarDatosIngreso',array("ingresoID"=>$pacientes[$i]['ingreso'],"retorno"=>"Menu","modulito"=>'EstacionEnfermeria_QX'));
	      $this->salida .= "	<td><a href='$linkVerDatos'>".$pacientes[$i]['nombrepac']."</a></td>\n";
        (list($fecha,$hora)=explode(' ',$pacientes[$i]['fecha_ingreso_estacion']));
        (list($ano,$mes,$dia)=explode('-',$fecha));
        (list($hh,$mm)=explode(':',$hora));
        $this->salida .= "	<td>".ucfirst(strftime("%b %d de %Y %H:%M",mktime($hh,$mm,0,$mes,$dia,$ano)))."</td>\n";
        if(empty($pacientes[$i]['programacion_id'])||empty($pacientes[$i]['qx_quirofano_programacion_id'])||empty($pacientes[$i]['procedimientos'])){
          $this->salida .= "<td align=\"center\">&nbsp;</td>";  
        }else{
          $action=ModuloGetURL('app','EstacionEnfermeria_QX','user','AdmisionEstacionCirugia',array("numeroRegistro"=>$pacientes[$i]['numero_registro']));
          $this->salida .= "<td align=\"center\"><a href=\"$action\"><img title=\"Admitir en la Estacion\" border=\"0\" src=\"".GetThemePath()."/images/arriba.png\" height='15'></a></td>";                   
        }      
        $this->salida .="  </tr>\n";
      }     
      $this->salida .="	  <tr class=\"modulo_table_title\"><td colspan='5' height='30'>&nbsp;</td></tr>\n";
      $this->salida .="  </table><BR>";
    }
    return true;
  }

  /**
	*		FormaListadoProgramaciones
	*
  *   Funcion que despliega el listado de las programaciones pendientes por el departamento y por el dia
	*		@Author Lorena Aragón G.
	*		@access Private
	*		@return boolean
	*/

  function FormaListadoProgramaciones($FiltroProfesionales,$FiltroQuirofanos){
		//ojo cualquier cambio en esta fucion implica un cambio en la funcion que imprime el reporte
    $this->salida .= ThemeAbrirTabla('PROGRAMACIONES DE CIRUGIAS PENDIENTES');
    $action=ModuloGetURL('app','EstacionEnfermeria_QX','user','ModificacionesProgramaciones',array("FiltroProfesionales"=>$FiltroProfesionales));
		$this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
    $this->Encabezado();
    $rango=ModuloGetVar('app', 'Quirurgicos','RangoTurnosQuirofano');
		$SalasCirugia=$this->SeleccionQuirofanosDpto($FiltroQuirofanos);
    $sizeof=sizeof($SalasCirugia);
    $ciclos=(int)($sizeof/4);
    if(($sizeof % 4) > 0){
      $ciclos+=1;
    }
    $inicio=0;
    if($sizeof<4){
      $fin=$sizeof;
    }else{
      $fin=4;
    }
    $SalasCirugiaTotal=$this->SeleccionQuirofanosDpto();
    if($SalasCirugiaTotal){
      $this->salida .= "   <BR><table border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10\">";
      $this->salida .= "   <tr><td colspan=\"".(sizeof($SalasCirugiaTotal)+1)."\" class=\"modulo_table_list_title\" align=\"center\">VER QUIROFANOS</td></tr>";
      $this->salida .= "   <tr>";
      for($l=0;$l<sizeof($SalasCirugiaTotal);$l++){
        $che='';
        if($FiltroQuirofanos[$SalasCirugiaTotal[$l]['quirofano']]==1){
          $che='checked';
        }
        $this->salida .= "   <td class=\"modulo_list_claro\" align=\"center\"><input type=\"checkbox\" $che name=\"QuiroSelect[".$SalasCirugiaTotal[$l]['quirofano']."]\" value=\"1\">&nbsp&nbsp;".$SalasCirugiaTotal[$l]['abreviatura']."</td>";
      }
      $this->salida .= "   <td width=\"10%\" nowrap class=\"modulo_list_claro\" align=\"center\"><input type=\"submit\" class=\"input-submit\" name=\"FILTRAR\" value=\"Seleccionar\"></td>";
      $this->salida .= "   </tr>";
      $this->salida .= "   </table><BR>";
    }
    $this->salida .= "   <table border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10\">";
    if($FiltroProfesionales==1){
      $actionNoFiltro=ModuloGetURL('app','EstacionEnfermeria_QX','user','LlamaFormaListadoProgramaciones',array("FiltroQuirofanos"=>$FiltroQuirofanos));
      $this->salida .= "   <tr><td class=\"label\"><a href=\"$actionNoFiltro\"><img border=\"0\" src=\"".GetThemePath()."/images/activo.gif\">&nbsp&nbsp;VER PACIENTES</a></td></tr>";
    }else{
      $actionFiltro=ModuloGetURL('app','EstacionEnfermeria_QX','user','LlamaFormaListadoProgramaciones',array("FiltroProfesionales"=>1,"FiltroQuirofanos"=>$FiltroQuirofanos));
      $this->salida .= "   <tr><td class=\"label\"><a href=\"$actionFiltro\"><img border=\"0\" src=\"".GetThemePath()."/images/usuarios.png\">&nbsp&nbsp;VER PROFESIONALES ASIGNADOS</a></td></tr>";
    }
    $this->salida .= "   </table>";
    if($SalasCirugia){
      for($cil=0;$cil<$ciclos;$cil++){
        $colspan=($fin-$inicio)*2;
        $this->salida .= "   <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "   <tr><td class=\"modulo_table_list_title\" align=\"center\" colspan=\"$colspan\"><label class=\"Cliente\">".strtoupper(strftime("%A %d de  %B de %Y",mktime(0,0,0,date("m"),date("d"),date("Y"))))."</label></td></tr>";
        $this->salida .= "   <tr>";
        for($i=$inicio;$i<$fin;$i++){
          $Quiro=$SalasCirugia[$i]['quirofano'];
          $abreviatura=$SalasCirugia[$i]['abreviatura'];
          $this->salida .= "   <td align=\"center\" colspan=\"2\" class=\"modulo_table_list_title\">$abreviatura</td>";
        }
        $this->salida .= "   </tr>";
        if($tipoHorario=='Completo'){
          $HoraInincio='0';
          $MinutosInicio='0';
          $dia=date("d");
          $mes=date("m");
          $ano=date("Y");
          $SumaInicio=date("Y-m-d H:i:s",mktime($HoraInincio,$MinutosInicio,0,$mes,$dia,$ano));
          $SumaFinal=date('Y-m-d H:i:s',mktime(($HoraInincio+24),$MinutosInicio,0,$mes,$dia,$ano));
          $SumaHora=$SumaInicio;
        }else{
          $rangoInicio=ModuloGetVar('app', 'Quirurgicos','RangoInicioTurnoQuirofano');
          $rangoDuracion=ModuloGetVar('app', 'Quirurgicos','RangoDuracionTurnoQuirofano');
          $cadena=explode(':',$rangoInicio);
          $HoraInincio=$cadena[0];
          $MinutosInicio=$cadena[1];
          $dia=date("d");
          $mes=date("m");
          $ano=date("Y");
          $SumaInicio=date("Y-m-d H:i:s",mktime($HoraInincio,$MinutosInicio,0,$mes,$dia,$ano));
          $SumaFinal=date('Y-m-d H:i:s',mktime(($HoraInincio+$rangoDuracion),$MinutosInicio,0,$mes,$dia,$ano));
          $SumaHora=$SumaInicio;
        }
        while($SumaHora<$SumaFinal){
          if($y % 2){$y++;}
          (list($Fecha,$HoraMosDef)=explode(' ',$SumaHora));
          (list($HoraMos,$MinutosMos)=explode(':',$HoraMosDef));
          $this->salida .= "   <tr>";
          for($i=$inicio;$i<$fin;$i++){
            if($y % 2){$estilo='hc_table_submodulo';}else{$estilo='modulo_list_claro';}
            $Quiro=$SalasCirugia[$i]['quirofano'];
            $abreviatura=$SalasCirugia[$i]['abreviatura'];
            $comprobacion=$this->ComprobarExisReserva($Quiro,$SumaHora,$rango,'0','0','0','0');						
            if($comprobacion==1){												
              $programacion=$this->consultaProgramacion($Quiro,$SumaHora,$rango);
              if($programacion[0]){								
                if($FiltroProfesionales==1){
                  $actionVer=ModuloGetURL('app','EstacionEnfermeria_QX','user','EditarProfesionalesProgramacion',array("programacionId"=>$programacion[0]['programacion_id']));
                  $this->salida .= " <td width=\"3%\" nowrap align=\"left\" class=\"modulo_list_oscuro\"><a href=\"$actionVer\" class=\"link\">$HoraMos : $MinutosMos</a></td>\n";
                  $this->salida .= " <td width=\"20%\" nowrap align=\"left\" class=\"$estilo\">";
                  $this->salida .= " <img border=\"0\" src=\"".GetThemePath()."/images/inactivoip.gif\" width=11 heigth=9 title=\"Anestesiologo\">&nbsp;";
                  if($programacion[0]['anestesiologo']){
                    $this->salida .= " ".$programacion[0]['anestesiologo']."<BR>";
                  }else{
                    $this->salida .= " <label class=\"normal_10N_menu\">SIN ASIGNAR</label><BR>";
                  }
                  $this->salida .= " <img border=\"0\" src=\"".GetThemePath()."/images/inactivoip.gif\" width=11 heigth=9 title=\"Ayudante\">&nbsp;";
                  if($programacion[0]['ayudante']){
                    $this->salida .= " ".$programacion[0]['ayudante']."<BR>";
                  }else{
                    $this->salida .= " <label class=\"normal_10N_menu\">SIN ASIGNAR</label><BR>";
                  }
                  $this->salida .= " <img border=\"0\" src=\"".GetThemePath()."/images/inactivoip.gif\" width=11 heigth=9 title=\"Instrumentador\">&nbsp;";
                  if($programacion[0]['instrumentador']){
                    $this->salida .= " ".$programacion[0]['instrumentador']."<BR>";
                  }else{
                    $this->salida .= " <label class=\"normal_10N_menu\">SIN ASIGNAR</label><BR>";
                  }
                  $this->salida .= " <img border=\"0\" src=\"".GetThemePath()."/images/inactivoip.gif\" width=11 heigth=9 title=\"Circulante\">&nbsp;";
                  if($programacion[0]['circulante']){
                    $this->salida .= " ".$programacion[0]['circulante']."<BR>";
                  }else{
                    $this->salida .= " <label class=\"normal_10N_menu\">SIN ASIGNAR</label><BR>";
                  }
                  $this->salida .= " </td>\n";
                }else{
                  $actionVer=ModuloGetURL('app','EstacionEnfermeria_QX','user','ConsultaProgramacionQX',array("programacionId"=>$programacion[0]['programacion_id']));
                  $this->salida .= " <td width=\"3%\" nowrap align=\"left\" class=\"modulo_list_oscuro\"><a href=\"$actionVer\" class=\"link\">$HoraMos : $MinutosMos</a></td>\n";
                  $this->salida .= " <td width=\"20%\" nowrap align=\"left\" class=\"$estilo\">";
                  $this->salida .= " <img border=\"0\" src=\"".GetThemePath()."/images/inactivo.gif\" width=11 heigth=9 title=\"Cirujano\">&nbsp;";
                  if($programacion[0]['cirujano']){
                    $this->salida .= " ".$programacion[0]['cirujano']."<BR>";
                  }else{
                    $this->salida .= " <label class=\"normal_10N_menu\">SIN ASIGNAR</label><BR>";
                  }
                  $this->salida .= " <img border=\"0\" src=\"".GetThemePath()."/images/activo.gif\" width=11 heigth=9 title=\"Paciente\">&nbsp;";
                  $this->salida .= " ".$programacion[0]['nombre_pac']."<BR>";
                  $this->salida .= " <img border=\"0\" src=\"".GetThemePath()."/images/pcargos.png\" width=11 heigth=9 title=\"".$programacion[1]['descripcion']."\">&nbsp;";
                  $this->salida .= " ".substr($programacion[1]['descripcion'],0,25)."<BR>";
                  $this->salida .= " </td>\n";
                }
              }else{								
                $programacion=$this->consultaProgramacionCliente($Quiro,$SumaHora,$rango);
                if($programacion){
                  $actionVer=ModuloGetURL('app','EstacionEnfermeria_QX','user','LlamaConsultaProgramacionCliente',array("programacionId"=>$programacion['qx_quirofano_programacion_id']));
                  $this->salida .= " <td width=\"3%\" nowrap align=\"left\" class=\"modulo_list_oscuro\"><a href=\"$actionVer\" class=\"link\">$HoraMos : $MinutosMos</a></td>\n";
                  $this->salida .= " <td width=\"20%\" nowrap align=\"left\" class=\"$estilo\">";
                  $this->salida .= " <img border=\"0\" src=\"".GetThemePath()."/images/inactivo.gif\" width=11 heigth=9 title=\"Cliente\">&nbsp;";
                  $this->salida .= " ".substr($programacion['nombre_tercero'],0,25)."";
                  $this->salida .= " </td>\n";
                }else{
                  $programacion=$this->consultaProgramacionPlan($Quiro,$SumaHora,$rango);
                  if($programacion){
                    $actionVer=ModuloGetURL('app','EstacionEnfermeria_QX','user','LlamaConsultaProgramacionPlan',array("programacionId"=>$programacion['qx_quirofano_programacion_id']));
                    $this->salida .= " <td width=\"3%\" nowrap align=\"left\" class=\"modulo_list_oscuro\"><a href=\"$actionVer\" class=\"link\">$HoraMos : $MinutosMos</a></td>\n";
                    $this->salida .= " <td width=\"20%\" nowrap align=\"left\" class=\"$estilo\">";
                    $this->salida .= " <img border=\"0\" src=\"".GetThemePath()."/images/pcopiar.png\" width=11 heigth=9 title=\"Plan\">&nbsp;";
                    $this->salida .= " ".substr($programacion['plan_descripcion'],0,25)."";
                    $this->salida .= " </td>\n";
                  }else{
                    $this->salida .= " <td width=\"20%\" nowrap align=\"left\" class=\"$estilo\">&nbsp;</td>\n";
                  }
                }
              }
            }else{
              $this->salida .= " <td width=\"3%\" nowrap align=\"left\" class=\"$estilo\">$HoraMos : $MinutosMos</td>\n";
              $this->salida .= " <td width=\"20%\" nowrap align=\"left\" class=\"$estilo\">&nbsp;</td>\n";
            }
            $y++;
          }
          $this->salida .= "   </tr>";
          (list($Fecha,$HoraDef)=explode(' ',$SumaHora));
          (list($ano,$mes,$dia)=explode('-',$Fecha));
          (list($Hora,$Minutos)=explode(':',$HoraDef));
          $SumaHora=date('Y-m-d H:i:s',mktime($Hora,($Minutos+$rango),0,$mes,$dia,$ano));
        }
        $this->salida .= "   </table><BR>";
        $inicio+=4;
        $resta=($sizeof-$fin);
        if($resta<4){
          $fin+=$resta;
        }else{
          $fin+=4;
        }
      }
		}
		$this->salida .= "   <table border=\"0\" width=\"100%\" align=\"center\">";		
		$rep= new GetReports();
		$mostrar=$rep->GetJavaReport('app','EstacionEnfermeria_QX','programacionesQX_html',array('fechaConsulta'=>date("Y-m-d"),"Empresa"=>$_SESSION['ESTACION_ENFERMERIA_QX']['NombreEmp'],"CentroUtilidad"=>$_SESSION['ESTACION_ENFERMERIA_QX']['NombreCU'],"UnidadFuc"=>$_SESSION['ESTACION_ENFERMERIA_QX']['NombreFunc'],"dpto"=>$_SESSION['ESTACION_ENFERMERIA_QX']['NombreDpto'],"departamento"=>$_SESSION['ESTACION_ENFERMERIA_QX']['Departamento'],"FiltroQuirofanos"=>$FiltroQuirofanos),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
		$nombre_funcion=$rep->GetJavaFunction();
		$this->salida .=$mostrar;
		$this->salida .= "   <tr><td align=\"right\">";
		$this->salida .= "	 		</BR><a class=\"Menu\" href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR</a>";
		$this->salida .= "	</td></tr>";
		$this->salida .= "   </table>";
    $refresh = ModuloGetURL('app','EstacionEnfermeria_QX','user','LlamaFormaListadoProgramaciones',array("LlenarQuirofanos"=>1));
		$href = ModuloGetURL('app','EstacionEnfermeria_QX','user','Menu');
		$this->salida .= "<div class='normal_10' align='center'><br>\n";
		$this->salida .= "	<a href='".$href."'>Volver</a>&nbsp;&nbsp;-&nbsp;&nbsp;\n";
		$this->salida .= "	<a href='$refresh'>Refrescar</a><br>\n";

    $this->salida .= "<form>";
    $this->salida .= ThemeCerrarTabla();
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
	*		FrmEditarProfesionalesProgramacion
	*
  *   Funcion que muestra los profesionales del departamento para tener la posobilidad de cambiarlos en la programación
	*		@Author Lorena Aragón G.
	*		@access Private
	*		@return boolean
	*/

  function FrmEditarProfesionalesProgramacion($programacionId){

    $this->salida .= ThemeAbrirTabla('PROFESIONALES ASIGNADOS A LA PROGRAMACION');
    $vector=$this->DatosProgramacionQX($programacionId);
    $action=ModuloGetURL('app','EstacionEnfermeria_QX','user','ModificacionesProfesionalesProgramaciones',array("programacionId"=>$programacionId));
		$this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
    $this->Encabezado();
    $this->salida .= "   <br><table border=\"0\" width=\"70%\" align=\"center\" class=\"normal_10\">";
    $this->salida .= "   <tr><td class=\"modulo_table_list_title\" colspan=\"2\">".strtoupper(strftime("%A %d de  %B de %Y",mktime(0,0,0,date("m"),date("d"),date("Y"))))."</td></tr>";
    $this->salida .= "   <tr><td class=\"modulo_table_title\" colspan=\"2\">PACIENTE: ".$vector[0]['nombre_pac']."</td></tr>";
    $this->salida .= "   <tr><td class=\"modulo_table_title\" colspan=\"2\">CIRUJANO: ".$vector[0]['cirujano']."</td></tr>";
    (list($FechaProgramIni,$HoraProgramIni)=explode(' ',$vector[0]['hora_inicio']));
    (list($anoIni,$mesIni,$diaIni)=explode('-',$FechaProgramIni));
    (list($HoraIni,$MinutosIni)=explode(':',$HoraProgramIni));
    $this->salida .= "   <tr><td class=\"modulo_table_title\" colspan=\"2\">HORA INICIO (HH:mm): ".$HoraIni.":".$MinutosIni."</td></tr>";
    (list($FechaProgramFin,$HoraProgramFin)=explode(' ',$vector[0]['hora_fin']));
    (list($anoFin,$mesFin,$diaFin)=explode('-',$FechaProgramFin));
    (list($HoraFin,$MinutosFin)=explode(':',$HoraProgramFin));
    $DuracionMin=(mktime($HoraFin,$MinutosFin,0,$mesFin,$diaFin,$anoFin)-mktime($HoraIni,$MinutosIni,0,$mesIni,$diaIni,$anoIni))/60;
    $HorasDura=(int)($DuracionMin/60);
    $MinutosDura=($DuracionMin%60);
    $Duracion=str_pad($HorasDura,2,0,STR_PAD_LEFT).':'.str_pad($MinutosDura,2,0,STR_PAD_LEFT);
    $this->salida .= "   <tr><td class=\"modulo_table_title\" colspan=\"2\">DURACION (HH:mm): ".$Duracion."</td></tr>";
    $this->salida .= "		<tr class=\"modulo_list_claro\">";
    $this->salida .= "		<td class=\"".$this->SetStyle("anestesiologo")."\" width=\"25%\">ANESTESIOLOGO</td>";
    $this->salida .= "		<td><select name=\"anestesiologo\" class=\"select\">";
    $this->salida .="         <option value=\"-1\">-------SELECCIONE-------</option>";
	  $profesionales=$this->profesionalesEspecialistaAnestecistas();
    for($i=0;$i<sizeof($profesionales);$i++){
      if($profesionales[$i]['tipo_id_tercero'].",".$profesionales[$i]['tercero_id'].",".$profesionales[$i]['nombre']==$vector[0]['tipo_id_tercero_aneste'].','.$vector[0]['tercero_id_aneste'].','.$vector[0]['anestesiologo']){
        $this->salida .="       <option value=\"".$profesionales[$i]['tipo_id_tercero'].",".$profesionales[$i]['tercero_id'].",".$profesionales[$i]['nombre']."\" selected>".$profesionales[$i]['nombre']."</option>";
      }else{
        $this->salida .="       <option value=\"".$profesionales[$i]['tipo_id_tercero'].",".$profesionales[$i]['tercero_id'].",".$profesionales[$i]['nombre']."\">".$profesionales[$i]['nombre']."</option>";
      }
    }
    $this->salida .= "    </select></td>";
    $this->salida .= "		</tr>";
    $this->salida .= "		<tr class=\"modulo_list_claro\">";
    $this->salida .= "		<td class=\"".$this->SetStyle("ayudante")."\" width=\"25%\">AYUDANTE</td>";
    $this->salida .= "		<td><select name=\"ayudante\" class=\"select\">";
    $this->salida .="         <option value=\"-1\">-------SELECCIONE-------</option>";
	  $profesionales=$this->profesionalesAyudantes();
    for($i=0;$i<sizeof($profesionales);$i++){
      if($profesionales[$i]['tipo_id_tercero'].",".$profesionales[$i]['tercero_id'].",".$profesionales[$i]['nombre']==$vector[0]['tipo_id_tercero_ayud'].','.$vector[0]['tercero_id_ayud'].','.$vector[0]['ayudante']){
        $this->salida .="       <option value=\"".$profesionales[$i]['tipo_id_tercero'].",".$profesionales[$i]['tercero_id'].",".$profesionales[$i]['nombre']."\" selected>".$profesionales[$i]['nombre']."</option>";
      }else{
        $this->salida .="       <option value=\"".$profesionales[$i]['tipo_id_tercero'].",".$profesionales[$i]['tercero_id'].",".$profesionales[$i]['nombre']."\">".$profesionales[$i]['nombre']."</option>";
      }
    }
    $this->salida .= "    </select></td>";
    $this->salida .= "		</tr>";
    $this->salida .= "		<tr class=\"modulo_list_claro\">";
    $this->salida .= "		<td class=\"".$this->SetStyle("instrumentador")."\" width=\"25%\">INSTRUMENTADOR</td>";
    $this->salida .= "		<td><select name=\"instrumentador\" class=\"select\">";
    $this->salida .="         <option value=\"-1\">-------SELECCIONE-------</option>";
	  $profesionales=$this->profesionalesEspecialistaInstrumentistas();
    for($i=0;$i<sizeof($profesionales);$i++){
      if($profesionales[$i]['tipo_id_tercero'].",".$profesionales[$i]['tercero_id'].",".$profesionales[$i]['nombre']==$vector[0]['tipo_id_tercero_instru'].','.$vector[0]['tercero_id_instru'].','.$vector[0]['instrumentador']){
          $this->salida .="       <option value=\"".$profesionales[$i]['tipo_id_tercero'].",".$profesionales[$i]['tercero_id'].",".$profesionales[$i]['nombre']."\" selected>".$profesionales[$i]['nombre']."</option>";
      }else{
        $this->salida .="       <option value=\"".$profesionales[$i]['tipo_id_tercero'].",".$profesionales[$i]['tercero_id'].",".$profesionales[$i]['nombre']."\">".$profesionales[$i]['nombre']."</option>";
      }
    }
    $this->salida .= "    </select></td>";
    $this->salida .= "		</tr>";
    $this->salida .= "		<tr class=\"modulo_list_claro\">";
    $this->salida .= "		<td class=\"".$this->SetStyle("circulante")."\" width=\"25%\">CIRCULANTE</td>";
    $this->salida .= "		<td><select name=\"circulante\" class=\"select\">";
    $this->salida .="         <option value=\"-1\">-------SELECCIONE-------</option>";
	  $profesionales=$this->profesionalesEspecialistaCiculantes();
    for($i=0;$i<sizeof($profesionales);$i++){
      if($profesionales[$i]['tipo_id_tercero'].",".$profesionales[$i]['tercero_id'].",".$profesionales[$i]['nombre']==$vector[0]['tipo_id_tercero_circu'].','.$vector[0]['tercero_id_circu'].','.$vector[0]['circulante']){
          $this->salida .="       <option value=\"".$profesionales[$i]['tipo_id_tercero'].",".$profesionales[$i]['tercero_id'].",".$profesionales[$i]['nombre']."\" selected>".$profesionales[$i]['nombre']."</option>";
      }else{
        $this->salida .="       <option value=\"".$profesionales[$i]['tipo_id_tercero'].",".$profesionales[$i]['tercero_id'].",".$profesionales[$i]['nombre']."\">".$profesionales[$i]['nombre']."</option>";
      }
    }
    $this->salida .= "    </select></td>";
    $this->salida .= "		</tr>";
    $this->salida .= "		<tr class=\"modulo_list_claro\"><td colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"Guardar\" value=\"GUARDAR\" class=\"input-submit\"></td></tr>";
    $this->salida .= "   </table>";
    $this->salida .= "    <form>";
		$href = ModuloGetURL('app','EstacionEnfermeria_QX','user','LlamaFormaListadoProgramaciones',array("FiltroProfesionales"=>1,"LlenarQuirofanos"=>1));
		$this->salida .= "<div class='normal_10' align='center'><br>\n";
		$this->salida .= "	<a href='".$href."'>Volver</a>\n";
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
      $this->salida .= "            <tr class=\"modulo_list_claro\"><td width=\"20%\" class=\"label\">HORA INICIO (HH:mm)</td><td>".strtoupper(strftime("%b %d de %Y %H:%M",mktime($HoraIni,$MinutosIni,0,$mesIni,$diaIni,$anoIni)))."</td><td width=\"20%\" class=\"label\">DURACION (HH:mm)</td><td>".$Duracion."</td></tr>";
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
        $this->salida .= "            <tr class=\"modulo_table_list_title\"><td colspan=\"3\">PROCEDIMIENTOS DE LA PROGRAMACION</td></tr>";
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
		$this->salida .= "	<a href='".$href."'>Volver</a>\n";
    $this->salida .= ThemeCerrarTabla();
    return true;
  }

  /**
	*		FrmConsultaProgramacionPlan
	*
  *   Funcion que muestra laconsulta de los datos de la programacion
	*		@Author Lorena Aragón G.
	*		@access Private
	*		@return boolean
	*/

  function FrmConsultaProgramacionPlan($programacionId){
    $this->salida .= ThemeAbrirTabla('DATOS DE LA PROGRAMACION');
    $vector=$this->DatosProgramacionQXPlan($programacionId);
    $this->salida .= "          <BR><table border=\"0\" width=\"90%\" align=\"center\">";
    $this->salida .= "          <tr><td class=\"modulo_list_claro\">";
    $this->salida .= "          <BR><table border=\"0\" cellspacing=\"3\" cellpadding=\"3\" width=\"95%\" align=\"center\" class=\"normal_10\">";
    $this->salida .= "          <tr class=\"modulo_table_list_title\">";
    $this->salida .= "          <td colspan=\"4\">".strtoupper(strftime("%A %d de  %B de %Y",mktime(0,0,0,date("m"),date("d"),date("Y"))))."</td>";
    $this->salida .= "          </tr>";
    $this->salida .= "          <tr class=\"modulo_list_oscuro\"><td colspan=\"4\">";
    $this->salida .= "            <BR><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
    $this->salida .= "            <tr class=\"modulo_table_list_title\"><td colspan=\"4\">PROGRAMACION DE LA RESERVA</td></tr>";
    $this->salida .= "            <tr class=\"modulo_list_claro\"><td width=\"20%\" class=\"label\">TIPO RESERVA</td><td>".$vector['tipo_reserva']."</td><td width=\"20%\" class=\"label\">PLAN</td><td>".$vector['plan_descripcion']."</td></tr>";
    (list($FechaProgramIni,$HoraProgramIni)=explode(' ',$vector['hora_inicio']));
    (list($anoIni,$mesIni,$diaIni)=explode('-',$FechaProgramIni));
    (list($HoraIni,$MinutosIni)=explode(':',$HoraProgramIni));
    (list($FechaProgramFin,$HoraProgramFin)=explode(' ',$vector['hora_fin']));
    (list($anoFin,$mesFin,$diaFin)=explode('-',$FechaProgramFin));
    (list($HoraFin,$MinutosFin)=explode(':',$HoraProgramFin));
    $DuracionMin=(mktime($HoraFin,$MinutosFin,0,$mesFin,$diaFin,$anoFin)-mktime($HoraIni,$MinutosIni,0,$mesIni,$diaIni,$anoIni))/60;
    $HorasDura=(int)($DuracionMin/60);
    $MinutosDura=($DuracionMin%60);
    $Duracion=str_pad($HorasDura,2,0,STR_PAD_LEFT).':'.str_pad($MinutosDura,2,0,STR_PAD_LEFT);
    $this->salida .= "            <tr class=\"modulo_list_claro\"><td width=\"20%\" class=\"label\">HORA INICIO</td><td>$HoraIni:$MinutosIni</td><td width=\"20%\" class=\"label\">DURACION</td><td>$Duracion</td></tr>";
    $this->salida .= "            </table><BR>";
    $this->salida .= "          </td></tr>";
    $this->salida .= "          </table><BR>";
    $this->salida .= "          </td></tr>";
    $this->salida .= "          </table>";
    $href = ModuloGetURL('app','EstacionEnfermeria_QX','user','LlamaFormaListadoProgramaciones',array("LlenarQuirofanos"=>1));
		$this->salida .= "<div class='normal_10' align='center'><br>\n";
		$this->salida .= "	<a href='".$href."'>Volver</a>\n";
    $this->salida .= ThemeCerrarTabla();
    return true;
  }

  /**
	*		FrmConsultaProgramacionCliente
	*
  *   Funcion que muestra laconsulta de los datos de la programacion
	*		@Author Lorena Aragón G.
	*		@access Private
	*		@return boolean
	*/

  function FrmConsultaProgramacionCliente($programacionId){
    $this->salida .= ThemeAbrirTabla('DATOS DE LA PROGRAMACION');
    $vector=$this->DatosProgramacionQXCliente($programacionId);
    $this->salida .= "          <BR><table border=\"0\" width=\"90%\" align=\"center\">";
    $this->salida .= "          <tr><td class=\"modulo_list_claro\">";
    $this->salida .= "          <BR><table border=\"0\" cellspacing=\"3\" cellpadding=\"3\" width=\"95%\" align=\"center\" class=\"normal_10\">";
    $this->salida .= "          <tr class=\"modulo_table_list_title\">";
    $this->salida .= "          <td colspan=\"4\">".strtoupper(strftime("%A %d de  %B de %Y",mktime(0,0,0,date("m"),date("d"),date("Y"))))."</td>";
    $this->salida .= "          </tr>";
    $this->salida .= "          <tr class=\"modulo_list_oscuro\"><td colspan=\"4\">";
    $this->salida .= "            <BR><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
    $this->salida .= "            <tr class=\"modulo_table_list_title\"><td colspan=\"4\">PROGRAMACION DE LA RESERVA</td></tr>";
    $this->salida .= "            <tr class=\"modulo_list_claro\"><td width=\"20%\" class=\"label\">TIPO RESERVA</td><td>".$vector['tipo_reserva']."</td><td width=\"20%\" class=\"label\">CLIENTE</td><td>".$vector['nombre_tercero']."</td></tr>";
    (list($FechaProgramIni,$HoraProgramIni)=explode(' ',$vector['hora_inicio']));
    (list($anoIni,$mesIni,$diaIni)=explode('-',$FechaProgramIni));
    (list($HoraIni,$MinutosIni)=explode(':',$HoraProgramIni));
    (list($FechaProgramFin,$HoraProgramFin)=explode(' ',$vector['hora_fin']));
    (list($anoFin,$mesFin,$diaFin)=explode('-',$FechaProgramFin));
    (list($HoraFin,$MinutosFin)=explode(':',$HoraProgramFin));
    $DuracionMin=(mktime($HoraFin,$MinutosFin,0,$mesFin,$diaFin,$anoFin)-mktime($HoraIni,$MinutosIni,0,$mesIni,$diaIni,$anoIni))/60;
    $HorasDura=(int)($DuracionMin/60);
    $MinutosDura=($DuracionMin%60);
    $Duracion=str_pad($HorasDura,2,0,STR_PAD_LEFT).':'.str_pad($MinutosDura,2,0,STR_PAD_LEFT);
    $this->salida .= "            <tr class=\"modulo_list_claro\"><td width=\"20%\" class=\"label\">HORA INICIO</td><td>$HoraIni:$MinutosIni</td><td width=\"20%\" class=\"label\">DURACION</td><td>$Duracion</td></tr>";
    $this->salida .= "            </table><BR>";
    $this->salida .= "          </td></tr>";
    $this->salida .= "          </table><BR>";
    $this->salida .= "          </td></tr>";
    $this->salida .= "          </table>";
    $href = ModuloGetURL('app','EstacionEnfermeria_QX','user','LlamaFormaListadoProgramaciones',array("LlenarQuirofanos"=>1));
		$this->salida .= "<div class='normal_10' align='center'><br>\n";
		$this->salida .= "	<a href='".$href."'>Volver</a>\n";
    $this->salida .= ThemeCerrarTabla();
    return true;
  }

  /**
	*		FrmReservaQuirofanoProgramacion
	*
  *   Funcion que muestra la forma para seleccionar un rango en la programacion de un quirofano
	*		@Author Lorena Aragón G.
	*		@access Private
	*		@return boolean
	*/

  function FrmReservaQuirofanoProgramacion($numeroRegistro){

    $this->salida .= ThemeAbrirTabla('RESERVAS DEL QUIROFANO');
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

    $rango=ModuloGetVar('app', 'Quirurgicos','RangoTurnosQuirofano');
    $this->Encabezado();
    $datosPaciente=$this->DatosPacienteAsignarProgramQX($numeroRegistro);
    $accion = ModuloGetURL('app','EstacionEnfermeria_QX','user','InsertarProgramacionQxPaciente',array("numeroRegistro"=>$numeroRegistro,"tipoIdPaciente"=>$datosPaciente['tipo_id_paciente'],"PacienteId"=>$datosPaciente['paciente_id']));
		$this->salida .= "   <form name=\"forma\" action=\"$accion\" method=\"post\">";
    $colspan=sizeof($SalasCirugia)*2;
    $this->salida .= "   <BR><table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
    $this->salida .= "   <tr>";
    $this->salida .= "   <td class=\"modulo_table_list_title\" align=\"center\">PACIENTE</td></tr>";
		$this->salida .= "   <td class=\"hc_table_submodulo\" align=\"center\">".$datosPaciente['identificacion']."</td>";
    $this->salida .= "   </tr>";
    $this->salida .= "  </table>";
    $this->salida .= "   <table border=\"0\" width=\"95%\" align=\"center\">";
    $this->salida .= "  <tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "  </td></tr>";
    $this->salida .= "  </table>";

    $FechaEspe=date('Y-m-d');
		$anoProgram=date("Y");
		$mesProgram=date("m");
		$diaProgram=date("d");
		$FechaConver=mktime(0,0,0,$mesProgram,$diaProgram,$anoProgram);
    $SalasCirugia=$this->SeleccionQuirofanosDpto();
    if($SalasCirugia){
		$this->salida .= "   <BR><table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
		$colspan=sizeof($SalasCirugia)*2;
    $this->salida .= "   <tr><td class=\"modulo_table_list_title\" align=\"center\" colspan=\"$colspan\">PROGRAMACIONES DE CIRUGIAS PARA EL DIA&nbsp;&nbsp;".strftime("%A %d de  %B de %Y",$FechaConver)."</td></tr>";
		$this->salida .= "   <tr><td class=\"modulo_table_list_title\" align=\"center\" colspan=\"$colspan\">SALAS QUIRURGICAS</td></tr>";
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
			$dia=date("d");
			$mes=date("m");
			$ano=date("Y");
			$SumaInicio=date("Y-m-d H:i:s",mktime($HoraInincio,$MinutosInicio,0,$mes,$dia,$ano));
			$SumaFinal=date('Y-m-d H:i:s',mktime(($HoraInincio+24),$MinutosInicio,0,$mes,$dia,$ano));
			$SumaHora=$SumaInicio;
		}else{
      $rangoInicio=ModuloGetVar('app', 'Quirurgicos','RangoInicioTurnoQuirofano');
			$rangoDuracion=ModuloGetVar('app', 'Quirurgicos','RangoDuracionTurnoQuirofano');
			$cadena=explode(':',$rangoInicio);
      $HoraInincio=$cadena[0];
			$MinutosInicio=$cadena[1];
			$dia=date("d");
			$mes=date("m");
			$ano=date("Y");
			$SumaInicio=date("Y-m-d H:i:s",mktime($HoraInincio,$MinutosInicio,0,$mes,$dia,$ano));
			$SumaFinal=date('Y-m-d H:i:s',mktime(($HoraInincio+$rangoDuracion),$MinutosInicio,0,$mes,$dia,$ano));
			$SumaHora=$SumaInicio;
		}

		while($SumaHora<$SumaFinal){
		  if($y % 2){$y++;}
      (list($FechaMosDef,$HoraMosDef)=explode(' ',$SumaHora));
      (list($HoraMos,$MinutosMos)=explode(':',$HoraMosDef));
			$this->salida .= "   <tr>";
      for($i=0;$i<sizeof($SalasCirugia);$i++){
			  if($y % 2){$estilo='hc_table_submodulo';}else{$estilo='modulo_list_claro';}
				$Quiro=$SalasCirugia[$i]['quirofano'];
				$abreviatura=$SalasCirugia[$i]['abreviatura'];
				$comprobacion=$this->ComprobarExisReserva($Quiro,$SumaHora,$rango,'0','0','0','0');
				if($comprobacion==1){
				  $this->salida .= " <td align=\"left\" class=\"modulo_list_oscuro\">$HoraMos : $MinutosMos</td>\n";
          $programacion=$this->consultaProgramacion($Quiro,$SumaHora,$rango);
          if(empty($programacion)){
            $programacion=$this->consultaProgramacionCliente($Quiro,$SumaHora,$rango);
            if(empty($programacion)){
              $programacion=$this->consultaProgramacionPlan($Quiro,$SumaHora,$rango);
              $NoProgramacion=$programacion['qx_quirofano_programacion_id'];
              $origen=3;
            }else{
              $NoProgramacion=$programacion['programacion_id'];
              $origen=2;
            }
          }else{
            $NoProgramacion=$programacion['programacion_id'];
            $origen=1;
          }
          $action = ModuloGetURL('app','EstacionEnfermeria_QX','user','CancelarReservaQuirofano',array("NoProgramacion"=>$NoProgramacion,"origen"=>$origen));
					//$this->salida .= " <td width=\"5%\" align=\"center\" class=\"modulo_list_oscuro\"><a href=\"$action\"><img src=\"".GetThemePath()."/images/fallo.png\" border=0 width=12 heigth=12></a></td>\n";
          $this->salida .= " <td width=\"5%\" align=\"center\" class=\"modulo_list_oscuro\">&nbsp;</td>\n";
				}else{
          $this->salida .= " <td align=\"left\" class=\"$estilo\">$HoraMos : $MinutosMos</td>\n";
					$this->salida .= " <td width=\"5%\" align=\"center\" class=\"$estilo\"><input type=\"checkbox\" name=\"seleccionReserv[]\" value=\"$Quiro/$SumaHora\" onclick=\"IntervalosCheck(this.form,this.value,'$rango')\"></td>\n";
				}
				$y++;
			}
			$this->salida .= "   </tr>";
      (list($Fecha,$HoraDef)=explode(' ',$SumaHora));
      (list($ano,$mes,$dia)=explode('-',$Fecha));
      (list($Hora,$Minutos)=explode(':',$HoraDef));
      $SumaHora=date('Y-m-d H:i:s',mktime($Hora,($Minutos+$rango),0,$mes,$dia,$ano));
		}
		$this->salida .= "   </table>";
    $this->salida .= "   <table border=\"0\" width=\"95%\" align=\"center\">";
    $this->salida .= "   <tr><td align=\"center\"><input type=\"submit\" name=\"Reservar\" value=\"CREAR RESERVA\" class=\"input-submit\"></td></tr>";
    $this->salida .= "   </table><BR>";
		}

    $refresh = ModuloGetURL('app','EstacionEnfermeria_QX','user','ReservaQuirofanoProgramacion',array("ingreso"=>$ingreso));
		$href = ModuloGetURL('app','EstacionEnfermeria_QX','user','Menu');
		$this->salida .= "<div class='normal_10' align='center'><br>\n";
		$this->salida .= "	<a href='".$href."'>Volver</a>&nbsp;&nbsp;-&nbsp;&nbsp;\n";
		$this->salida .= "	<a href='$refresh'>Refrescar</a><br>\n";
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





}//fin class
?>
