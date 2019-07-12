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
class app_QXEjecucion_userclasses_HTML extends app_QXEjecucion_user
{
	/**
	*Constructor de la clase app_ProgramacionQX_user_HTML
	*El constructor de la clase app_ProgramacionQX_user_HTML se encarga de llamar
	*a la clase app_ProgramacionQX_user quien se encarga de el tratamiento
	*de la base de datos.
	*/

  function app_QXEjecucion_user_HTML()
	{
		$this->salida='';
		$this->app_QXEjecucion_user();
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
			$url[1]='QXEjecucion';
			$url[2]='user';
			$url[3]='consultaLogueo';
			$url[4]='datos_query';
			$this->salida .= gui_theme_menu_acceso("SELECCION DEL DEPARTAMENTO",$Empresas[0],$Empresas[1],$url,ModuloGetURL('system','Menu'));
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

	/**
	* Funcion que muestra las distintas opciones del menu para el usuario
	* @return boolean
	*/
	function MenuQXEjecucion(){
		$this->salida .= ThemeAbrirTabla('MENU CIRUGIAS');
    $this->Encabezado();
		$this->salida .= "			      <table width=\"35%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" align=\"center\">MENU EJECUCION CIRUGIAS</td></tr>";
		$action=ModuloGetURL('app','QXEjecucion','user','BuscarPacienteCumplimiento');
		$action1=ModuloGetURL('app','QXEjecucion','user','LlamaEjecucionQX');
		$this->salida .= "				       <tr><td class=\"modulo_list_oscuro\" align=\"center\"><a href=\"$action\" class=\"link\"><b>CUMPLIMIENTO CIRUGIA</b></a></td></tr>";
    $this->salida .= "				       <tr><td class=\"modulo_list_oscuro\" align=\"center\"><a href=\"$action1\" class=\"link\"><b>PRESUPUESTO CIRUGIA</b></a></td></tr>";
		$this->salida .= "			     </table><BR>";
		$accion=ModuloGetURL('app','QXEjecucion','user','FrmLogueoCirugias');
		$this->salida .= "			      <table width=\"35%\" align=\"center\">";
		$this->salida .= "              <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "				       <tr><td align=\"center\"><input type=\"submit\" name=\"VOLVER\" class=\"input-submit\" value=\"VOLVER\"></td></tr>";
    $this->salida .= "              </form>";
		$this->salida .= "			     </table><BR>";
		$this->salida .= ThemeCerrarTabla();
		return true;
  }

	function BusquedaPacienteCumplimiento(){
	  $this->salida .= ThemeAbrirTabla('DATOS PACIENTE Y PLAN');
    $this->Encabezado();
    $this->salida .=" <script>";
		$this->salida .="   function cambioResponsable(frm){";
		$this->salida .= "    window.location.href='".ModuloGetURL('app','QXEjecucion','user','BusquedaPacienteCumplimiento')."&Responsable='+frm.Responsable.value+'&TipoDocumento='+frm.TipoDocumento.value+'&Documento='+frm.Documento.value+'&nombrePac='+frm.nombrePac.value+'&semanas='+frm.semanas.value;\n";
    $this->salida .="   }";
		$this->salida .=" </script>";
		$accion=ModuloGetURL('app','QXEjecucion','user','GuardaSeleccionTipoAfil');
		$this->salida .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "<table class=\"normal_10\" border=\"0\" width=\"70%\" align=\"center\">";
		$this->salida .= "<tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "</td></tr>";
		$this->salida .= "<tr class=\"modulo_table_title\"><td align=\"center\" width=\"100%\">DATOS DEL PACIENTE</td></tr>";
    $this->salida .= "<tr class=\"modulo_list_oscuro\"><td>";
		$this->salida .= "    <BR><table class=\"normal_10\" width=\"95%\" border=\"0\" align=\"center\">";
    $this->salida .= "    <tr class=\"modulo_list_claro\"><td width=\"25%\" class=\"label\">TIPO DOCUMENTO </td>";
		$this->salida .= "    <td><select name=\"TipoDocumento\" class=\"select\">";
		$tipo_id=$this->tipo_id_paciente();
		$this->Mostrar($tipo_id,'False',$_REQUEST['TipoDocumento']);
		$this->salida .= "    </select></td></tr>";
		$this->salida .= "	  <tr class=\"modulo_list_claro\"><td width=\"25%\" class=\"".$this->SetStyle("Documento")."\">DOCUMENTO </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" value=\"".$_REQUEST['Documento']."\"></td></tr>";
		$this->salida .= "    <tr class=\"modulo_list_claro\"><td width=\"25%\" class=\"label\">NOMBRE PACIENTE </td><td><input type=\"text\" size=\"45\" class=\"input-text\" name=\"nombrePac\" value=\"".$_REQUEST['nombrePac']."\"></td></tr>";
		$this->salida .= "</table><BR>";
    $this->salida .= "</td></tr>";
		$this->salida .= "<tr class=\"modulo_table_title\"><td align=\"center\" width=\"100%\">PLAN</td></tr>";
    $this->salida .= "<tr class=\"modulo_list_oscuro\"><td>";
		$this->salida .= "    <BR><table class=\"normal_10\" width=\"95%\" border=\"0\" align=\"center\">";
		$this->salida .= "    <tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("Responsable")."\">PLAN </td>";
		$this->salida .= "    <td colspan=\"3\"><select name=\"Responsable\"  class=\"select\" onchange=\"cambioResponsable(this.form)\" >";
		$responsables=$this->CallMetodoExterno('app','Triage','user','responsables');
		$this->MostrarResponsable($responsables,$_REQUEST['Responsable']);
    $this->salida .= "    </td>";
		$this->salida .= "    <tr class=\"modulo_list_claro\">";
    $this->salida .= "    <td class=\"".$this->SetStyle("tipoAfil")."\">TIPOS AFILIADOS</td>";
		$this->salida .= "    <td><select name=\"tipoAfil\" class=\"select\">\n";
		$tipos=$this->tiposAfiliadoRango($_REQUEST['Responsable']);
		$this->BuscartiposAfiliadoRango($tipos,'False',$_REQUEST['tipoAfil']);
		$this->salida .= "    </select></td>";
		$this->salida .= "    <td class=\"".$this->SetStyle("rango")."\">TIPOS RANGOS</td>";
		$this->salida .= "    <td><select name=\"rango\" class=\"select\">\n";
		$rangos=$this->tiposRangosAfil($_REQUEST['Responsable']);
		$this->MostrasSelect($rangos,'False',$_REQUEST['rango']);
		$this->salida .= "    </select></td>";
		$this->salida .= "    </tr>";
		$this->salida .= "    <tr class=\"modulo_list_claro\">";
		if(empty($_REQUEST['semanas'])){
      $_REQUEST['semanas']=0;
		}
		$this->salida .= "    <td class=\"".$this->SetStyle("semanas")."\" width=\"15%\">NUMERO</td>";
    $this->salida .= "    <td colspan=\"3\"><input size=\"4\" type=\"text\" name=\"semanas\" class=\"input-submit\" value=\"".$_REQUEST['semanas']."\"></td>";
		$this->salida .= "    </tr>";
		$this->salida .= "    </table><BR>";
		$this->salida .= "</td></tr>";
		$this->salida .= "</table>";
		$this->salida .= "<table class=\"normal_10\" width=\"95%\" border=\"0\" align=\"center\">";
    $this->salida .= "  <tr><td align=\"center\">";
		$this->salida .= "  <input type=\"submit\" class=\"input-submit\" value=\"VOLVER\" name=\"Regresar\">";
		$this->salida .= "  <input type=\"submit\" class=\"input-submit\" value=\"ACEPTAR\" name=\"Aceptar\">";
		$this->salida .= "  </td></tr>";
    $this->salida .= "</table>";
		$this->salida .= "</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function SeleccionTipoAfiliado($TipoDocumento,$Documento,$nombrePac,$Responsable,$tipoAfil,$rango,$nombretipoafil,$semanas){
    $this->salida .= ThemeAbrirTabla('TIPO AFILIADO Y RANGO');
    $this->Encabezado();
		$accion=ModuloGetURL('app','QXEjecucion','user','GuardaSeleccionTipoAfil',array("TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePac"=>$nombrePac,"Responsable"=>$Responsable,"nombretipoafil"=>$nombretipoafil));
		$this->salida .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "       <table class=\"normal_10\" border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "       <tr><td width=\"100%\">";
		$this->salida .= "       <fieldset><legend class=\"field\">DATOS AFILIADO</legend>";
		$this->salida .= "       <br><table cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
		if($TipoDocumento && $Documento){
		$this->salida .= "	      <tr class=\"modulo_list_claro\">";
		$this->salida .= "	      <td  width=\"30%\"><label class=\"label\">IDENTIFICACION PACIENTE</td><td>$TipoDocumento $Documento</td>";
		$this->salida .= "	      </tr>";
		}
		if($nombrePac){
		$this->salida .= "	      <tr class=\"modulo_list_claro\">";
		$this->salida .= "	      <td width=\"30%\"><label class=\"label\">NOMBRE PACIENTE</td><td>$nombrePac</td>";
		$this->salida .= "	      </tr>";
		}
		$NombreResponsable=$this->Responsable($Responsable);
		$NombrePlan=$this->PlanNombre($Responsable);
		$this->salida .= "	      <tr class=\"modulo_list_claro\">";
		$this->salida .= "	      <td  width=\"30%\"><label class=\"label\">RESPONSABLE Y PLAN</td><td>".$NombreResponsable."&nbsp&nbsp&nbsp;".$NombrePlan."</td>";
		$this->salida .= "	      </tr>";
		$this->salida .= "			  </table><br>";
		$this->salida .= "		    </fieldset></td>";
		$this->salida .= "       </table><BR>";
		$this->salida .= "<table class=\"normal_10\" border=\"0\" width=\"70%\" align=\"center\">";
		$this->salida .= "<tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "</td></tr>";
		$this->salida .= "<tr class=\"modulo_table_title\"><td align=\"center\" width=\"100%\">SELECCION TIPO AFILIADO Y RANGO</td></tr>";
    $this->salida .= "<tr class=\"modulo_list_oscuro\"><td>";
		$this->salida .= "    <BR><table class=\"normal_10\" width=\"95%\" border=\"0\" align=\"center\">";
		if((!$tipoAfil || $tipoAfil==-1) || (!$rango || $rango==-1)){
		$this->salida .= "    <input type=\"hidden\" name=\"noexiste\" value=\"1\">";
		$this->salida .= "    <tr class=\"modulo_list_claro\">";
		$this->salida .= "    <td width=\"20%\" class=\"".$this->SetStyle("tipoAfil")."\">TIPOS AFILIADOS</td><td><select name=\"tipoAfil\" class=\"select\">\n";
		$tipos=$this->tiposAfiliadoRango($Responsable);
		$this->BuscartiposAfiliadoRango($tipos,'False',$tipoAfil);
		$this->salida .= "    </select></td>";
		$this->salida .= "    <td width=\"20%\" class=\"".$this->SetStyle("rango")."\">TIPOS RANGOS</td><td><select name=\"rango\" class=\"select\">\n";
		$rangos=$this->tiposRangosAfil($Responsable);
		$this->MostrasSelect($rangos,'False',$rango);
		$this->salida .= "    </select></td>";
		$this->salida .= "    </tr>";
		}else{
			$this->salida .= "    <tr class=\"modulo_list_claro\">";
			$this->salida .= "    <td width=\"20%\" class=\"label\">TIPOS AFILIADOS</td>";
			$this->salida .= "    <td>$nombretipoafil</td>";
			$this->salida .= "    <td width=\"20%\" class=\"label\">TIPOS RANGOS</td>";
			$this->salida .= "    <td>$rango</td>";
			$this->salida .= "    <input type=\"hidden\" name=\"tipoAfil\" value=\"$tipoAfil\">";
			$this->salida .= "    <input type=\"hidden\" name=\"nombretipoafil\" value=\"$nombretipoafil\">";
			$this->salida .= "    <input type=\"hidden\" name=\"rango\" value=\"$rango\">";
			$this->salida .= "    </tr>";
		}
		$this->salida .= "    </table><BR>";
		$this->salida .= "</td></tr>";
		$this->salida .= "<tr class=\"modulo_list_oscuro\"><td>";
		$this->salida .= "    <BR><table class=\"normal_10\" width=\"95%\" border=\"0\" align=\"center\">";
		$this->salida .= "    <tr class=\"modulo_table_title\"><td align=\"center\" colspan=\"2\" width=\"100%\">SEMANAS COTIZADAS</td></tr>";
		$this->salida .= "    <tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("semanas")."\" width=\"15%\">NUMERO</td>";
    $this->salida .= "    <td><input type=\"text\" name=\"semanas\" class=\"input-submit\" value=\"$semanas\"></td></tr>";
		$this->salida .= "    </select></td></tr>";
		$this->salida .= "    </table><BR>";
		$this->salida .= "</td></tr>";
    $this->salida .= "</table>";
		$this->salida .= "<table class=\"normal_10\" width=\"95%\" border=\"0\" align=\"center\">";
    $this->salida .= "  <tr><td align=\"center\">";
		$this->salida .= "  <input type=\"submit\" class=\"input-submit\" value=\"VOLVER\" name=\"Regresar\">";
		$this->salida .= "  <input type=\"submit\" class=\"input-submit\" value=\"ACEPTAR\" name=\"Aceptar\">";
		$this->salida .= "  </td></tr>";
    $this->salida .= "</table>";
    $this->salida .= "</form>";
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
	function BuscartiposAfiliadoRango($tipos,$Seleccionado='False',$TipoAfil=''){
		switch($Seleccionado){
			case 'False':{
        $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
				for($i=0;$i<sizeof($tipos);$i++){
				  $value=$tipos[$i]['tipo_afiliado_id'].'/'.$tipos[$i]['tipo_afiliado_nombre'];
					$titulo=$tipos[$i]['tipo_afiliado_nombre'];
					if($value==$TipoAfil){
					$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				  }else{
            $this->salida .=" <option value=\"$value\">$titulo</option>";
				  }
			  }
			  break;
		  }case 'True':{
			  for($i=0;$i<sizeof($tipos);$i++){
			    $value=$tipos[$i]['tipo_afiliado_id'].'/'.$tipos[$i]['tipo_afiliado_nombre'];
					$titulo=$tipos[$i]['tipo_afiliado_nombre'];
				  if($value==$TipoAfil){
				    $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				  }
				  $this->salida .=" <option value=\"$value\">$titulo</option>";
			  }
			  break;
		  }
	  }
	}
	/**
* Funcion que visulaiza la forma donde se muestran los datos introducidos en una programacion Quirurjica
* @return boolean
*/
	function FormaPresupuestoCirugia($TipoDocumento,$Documento,$nombrePac,$Responsable,$tipoAfil,$rango,$semanas){
	  $this->salida.="<script>\n";
    $this->salida.="  function desabilitaQuirofano(frm,valor){";
		$this->salida.="    cadena=valor.split('/');";
		$this->salida.="    if(cadena[1]==0 || valor==-1){";
		$this->salida.="  		frm.quirofano.disabled=true;\n";
		$this->salida.="  		frm.noquiro.value='0';\n";
		$this->salida.="  	}else{\n";
		$this->salida.="  		frm.quirofano.disabled=false;\n";
		$this->salida.="  		frm.noquiro.value='1';\n";
		$this->salida.="  	}\n";
		$this->salida.="  }\n";
		$this->salida.="  function SeleccionarProcedimientosCirUno(valor){";
    $this->salida.="    document.forma.numeroCirujano.value=valor;";
    $this->salida.="    document.forma.submit();";
    $this->salida.="  }\n";
		$this->salida.="  function SeleccionarCirUno(valor){";
    $this->salida.="    document.forma.numeroCirujanoSelect.value=valor;";
    $this->salida.="    document.forma.submit();";
    $this->salida.="  }\n";
		$this->salida.="  function EliminarCirujano(valor){";
    $this->salida.="    document.forma.EliminaCirujano.value=valor;";
    $this->salida.="    document.forma.submit();";
    $this->salida.="  }\n";
		$this->salida.="</script>\n";
		$this->salida .= ThemeAbrirTabla('PRESUPUESTO DE LA CIRUGIA');
    $this->Encabezado();
    $this->salida .= "       <table class=\"normal_10\" border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "       <tr><td width=\"100%\">";
		$this->salida .= "       <fieldset><legend class=\"field\">DATOS AFILIADO</legend>";
		$this->salida .= "       <br><table cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
		if($TipoDocumento && $Documento){
		$this->salida .= "	      <tr class=\"modulo_list_claro\">";
		$this->salida .= "	      <td  width=\"30%\"><label class=\"label\">IDENTIFICACION PACIENTE</td><td>$TipoDocumento $Documento</td>";
		$this->salida .= "	      </tr>";
		}
		if($nombrePac){
		$this->salida .= "	      <tr class=\"modulo_list_claro\">";
		$this->salida .= "	      <td width=\"30%\"><label class=\"label\">NOMBRE PACIENTE</td><td>$nombrePac</td>";
		$this->salida .= "	      </tr>";
		}
		$NombreResponsable=$this->Responsable($Responsable);
		$NombrePlan=$this->PlanNombre($Responsable);
		$this->salida .= "	      <tr class=\"modulo_list_claro\">";
		$this->salida .= "	      <td  width=\"30%\"><label class=\"label\">RESPONSABLE Y PLAN</td><td>".$NombreResponsable."&nbsp&nbsp&nbsp;".$NombrePlan."</td>";
		$this->salida .= "	      </tr>";
		if($tipoAfil || $rango){
		  (list($tipoAfiliado,$nombretipoafil)=explode('/',$tipoAfil));
			$this->salida .= "	      <tr class=\"modulo_list_claro\">";
			$this->salida .= "	      <td  width=\"30%\"><label class=\"label\">TIPO AFILIADO</td><td>".$nombretipoafil."&nbsp&nbsp&nbsp;RANGO:&nbsp&nbsp;".$rango."</td>";
			$this->salida .= "	      </tr>";
		}
		$this->salida .= "	      <tr class=\"modulo_list_claro\">";
		$this->salida .= "	      <td  width=\"30%\"><label class=\"label\">SEMANAS COTIZADAS</td><td>".$semanas."</td>";
		$this->salida .= "	      </tr>";
		$this->salida .= "			  </table><br>";
		$this->salida .= "		    </fieldset></td>";
		$this->salida .= "       </table><BR>";
		$accion=ModuloGetURL('app','QXEjecucion','user','DatosRequeridosPresupuesto',array("TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePac"=>$nombrePac,"Responsable"=>$Responsable,"tipoAfil"=>$tipoAfil,"rango"=>$rango,"semanas"=>$semanas));
		$this->salida .= "      <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <input type=\"hidden\" name=\"numeroCirujanoSelect\">";
    $this->salida .= "      <input type=\"hidden\" name=\"numeroCirujano\">";
		$this->salida .= "      <input type=\"hidden\" name=\"EliminaCirujano\">";
		$this->salida .= "   		<table width=\"80%\" border=\"0\" align=\"center\" class=\"normal_10\" >";
		$this->salida .= "<tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "</td></tr>";
    $this->salida .= "   		</table>";
		$this->salida .= "   		<table width=\"80%\" border=\"0\" align=\"center\" class=\"normal_10\" >";
		$this->salida .= "			<tr class=\"modulo_table_list_title\"><td colspan=\"4\" class=\"modulo_table_list_title\">DATOS CIRUGIA</td></tr>";
		$che='';
		if($_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO1']['SW']==1){
      $che='checked';
		}
		$this->salida .= "			<tr class=\"modulo_list_claro\">";
    $this->salida .= "			<td width=\"20%\" class=\"".$this->SetStyle("cirujanoUno")."\" rowspan=\"2\">PRIMER CIRUJANO</td>";
		if(empty($_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO1']['NOMBRE'])){
		  $this->salida .= "		<td colspan=\"3\"><input $che type=\"checkbox\" value=\"1\" name=\"cirujanoUno\">&nbsp&nbsp&nbsp;";
		  $this->salida .= "		<a href=\"javascript:SeleccionarCirUno(1)\"><b>SELECCION CIRUJANO</b></a>";
			$this->salida .= "		</td>";
		}else{
		  $this->salida .= "			<td colspan=\"2\"><input $che type=\"checkbox\" value=\"1\" name=\"cirujanoUno\">&nbsp&nbsp&nbsp;";
      (list($tipoIdCirujano1,$IdCirujano1,$nombreCirujano1)=explode('/',$_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO1']['NOMBRE']));
			$this->salida .= "			$nombreCirujano1";
			$this->salida .= "			</td>";
			$this->salida .= "			<td><a href=\"javascript:EliminarCirujano(1)\"><img title=\"Eliminar Cirujano\" border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
		}
    $this->salida .= "			</tr>";
    $this->salida .= "			<tr class=\"modulo_list_claro\">";
    $this->salida .= "			<td colspan=\"3\" align=\"center\">";
		if(sizeof($_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO1']['PROCEDIMIENTOS'])>0){
      $this->salida .= "   		  <table width=\"98%\" border=\"0\" align=\"center\" class=\"normal_10\" >";
			foreach($_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO1']['PROCEDIMIENTOS'] as $indice=>$procedimiento){
			  (list($cargo,$descripcion,$sw_bilateral)=explode('||//',$procedimiento));
				$this->salida .= "			  <tr class=\"modulo_list_oscuro\">";
				$this->salida .= "			  <td>$descripcion</td>";
        if($sw_bilateral==1){
          $che='';
          if($_SESSION['PRESUPUESTO_CIRUGIA']['PROCEDIMIENTOS_BILATERAL'][$cargo]==1){
            $che='checked';
          }
          $this->salida .= "    <td width=\"5%\"><input $che type=\"checkbox\" name=\"bilateral[".$cargo."]\" value=\"1\"></td>";
        }else{
        $this->salida .= "    <td width=\"5%\">&nbsp;</td>";
        }
        $che='';
				$actionEliminaPro=ModuloGetURL('app','QXEjecucion','user','EliminaProcedimientoPresupuesto',array("TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePac"=>$nombrePac,"Responsable"=>$Responsable,"tipoAfil"=>$tipoAfil,"rango"=>$rango,"semanas"=>$semanas,'indice'=>$indice,'cirujanoNum'=>1));
				$this->salida .= "			  <td width=\"5%\"><a href=\"$actionEliminaPro\"><img title=\"Eliminar Cirujano\" border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
				$this->salida .= "			  </tr>";
			}
      $this->salida .= "   		  </table>";
		}
		$this->salida .= "			<BR><a href=\"javascript:SeleccionarProcedimientosCirUno(1)\"><b>SELECCION PROCEDIMIENTOS</b></a>";
		$this->salida .= "			</td>";
		$this->salida .= "			</tr>";
		$che='';
		if($_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO2']['SW']==1){
      $che='checked';
		}
		$this->salida .= "			<tr class=\"modulo_list_claro\">";
    $this->salida .= "			<td width=\"20%\" class=\"".$this->SetStyle("cirujanoDos")."\" rowspan=\"2\">SEGUNDO CIRUJANO</td>";
		if(empty($_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO2']['NOMBRE'])){
		  $this->salida .= "			<td colspan=\"3\"><input $che type=\"checkbox\" value=\"1\" name=\"cirujanoDos\">&nbsp&nbsp&nbsp;";
      $this->salida .= "		  <a href=\"javascript:SeleccionarCirUno(2)\"><b>SELECCION CIRUJANO</b></a>";
			$this->salida .= "			</td>";
		}else{
		  $this->salida .= "			<td colspan=\"2\"><input $che type=\"checkbox\" value=\"1\" name=\"cirujanoDos\">&nbsp&nbsp&nbsp;";
      (list($tipoIdCirujano2,$IdCirujano2,$nombreCirujano2)=explode('/',$_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO2']['NOMBRE']));
			$this->salida .= "			$nombreCirujano2";
			$this->salida .= "			</td>";
			$this->salida .= "			<td><a href=\"javascript:EliminarCirujano(2)\"><img title=\"Eliminar Cirujano\" border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
		}
    $this->salida .= "			</tr>";
		$this->salida .= "			<tr class=\"modulo_list_claro\">";
    $this->salida .= "			<td colspan=\"3\" align=\"center\">";
		if(sizeof($_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO2']['PROCEDIMIENTOS'])>0){
      $this->salida .= "   		  <table width=\"98%\" border=\"0\" align=\"center\" class=\"normal_10\" >";
			foreach($_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO2']['PROCEDIMIENTOS'] as $indice=>$procedimiento){
			  (list($cargo,$descripcion)=explode('||//',$procedimiento));
				$this->salida .= "			  <tr class=\"modulo_list_oscuro\">";
				$this->salida .= "			  <td>$descripcion</td>";
				$actionEliminaPro=ModuloGetURL('app','QXEjecucion','user','EliminaProcedimientoPresupuesto',array("TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePac"=>$nombrePac,"Responsable"=>$Responsable,"tipoAfil"=>$tipoAfil,"rango"=>$rango,"semanas"=>$semanas,'indice'=>$indice,'cirujanoNum'=>2));
				$this->salida .= "			  <td width=\"5%\"><a href=\"$actionEliminaPro\"><img title=\"Eliminar Cirujano\" border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
				$this->salida .= "			  </tr>";
			}
      $this->salida .= "   		  </table>";
		}
		$this->salida .= "			<BR><a href=\"javascript:SeleccionarProcedimientosCirUno(2)\"><b>SELECCION PROCEDIMIENTOS</b></a>";
		$this->salida .= "			</td>";
		$this->salida .= "			</tr>";

		/*$che='';
		if($_SESSION['PRESUPUESTO_CIRUGIA']['AYUDANTE']==1){
      $che='checked';
		}
		$this->salida .= "			<tr class=\"modulo_list_claro\">";
    $this->salida .= "			<td width=\"20%\" class=\"".$this->SetStyle("Ayudante")."\">AYUDANTE</td>";
    $this->salida .= "			<td><input $che type=\"checkbox\" value=\"1\" name=\"Ayundante\"></td>";
		$che='';
		if($_SESSION['PRESUPUESTO_CIRUGIA']['ANESTESIOLOGO']==1){
      $che='checked';
		}
    $this->salida .= "			<td width=\"20%\" class=\"".$this->SetStyle("Anestesiologo")."\">ANESTESIOLOGO</td>";
    $this->salida .= "			<td><input $che type=\"checkbox\" value=\"1\" name=\"Anestesiologo\"></td>";
    $this->salida .= "			</tr>";*/

		$this->salida .= "			<tr class=\"modulo_list_claro\">";
		$this->salida .= "			<td width=\"10%\" nowrap class=\"".$this->SetStyle("TipoSala")."\">TIPO SALA</td>";
		$this->salida .= "			<td width=\"20%\" nowrap ><select name=\"TipoSala\" onchange=\"desabilitaQuirofano(this.form,this.value)\" class=\"select\">";
		$this->salida .="       <option value=\"-1\" selected>---seleccione---</option>";
	  $TiposSala=$this->TiposDeSalas();
    for($i=0;$i<sizeof($TiposSala);$i++){
      $value=$TiposSala[$i]['tipo_sala_id'].'/'.$TiposSala[$i]['sw_quirofano'];
			$titulo=$TiposSala[$i]['descripcion'];
			if($value==$_SESSION['PRESUPUESTO_CIRUGIA']['TIPO_SALA']){
				$this->salida .="   <option value=\"$value\" selected>$titulo</option>";
			}else{
				$this->salida .="   <option value=\"$value\">$titulo</option>";
			}
		}
	  $this->salida .= "      </select></td>";
    $this->salida .= "			<td colspan=\"2\">";
		$desabilitar2='';
    if(empty($_SESSION['PRESUPUESTO_CIRUGIA']['TIPO_SALA']) || $_SESSION['PRESUPUESTO_CIRUGIA']['NO_QUIRO']!='1'){
      $desabilitar2='disabled';
		}
    $this->salida .= "			    <input type=\"hidden\" name=\"noquiro\" value=\"".$_SESSION['Liquidacion_QX']['NO_QUIRO']."\">";
		/*$this->salida .= "			   <BR><table width=\"100%\" align=\"center\" border=\"0\">\n";
		$this->salida .= "			   <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "				 <td class=\"".$this->SetStyle("quirofano")."\">QUIROFANO</td>";
		$this->salida .= "				 <td><select name=\"quirofano\" class=\"select\" $desabilitar2>";
		$this->salida .="          <option value=\"-1\" selected>---seleccione---</option>";
	  $Quirofanos=$this->TiposQuirofanosTotal();
		foreach($Quirofanos as $value=>$titulo){
			if($value==$_SESSION['PRESUPUESTO_CIRUGIA']['QUIROFANO']){
				$this->salida .="      <option value=\"$value\" selected>$titulo</option>";
			}else{
				$this->salida .="      <option value=\"$value\">$titulo</option>";
			}
		}
	  $this->salida .= "      		</select></td>";
		$this->salida .= "		   		</tr>";
		$this->salida .= "					</table><BR>";*/
		$this->salida .= "			 </td>";
		$this->salida .= "			 </tr>";
    $this->salida .= "			 <tr class=\"modulo_list_claro\">";
		$this->salida .= "			 <td class=\"".$this->SetStyle("duracion")."\">DURACION</td>";
    $this->salida .= "			 <td colspan=\"3\">";
		$this->salida .= "			 <select size=\"1\" name=\"hora\" class=\"select\">";
		$this->salida.="         <option value = -1>Horas</option>";
	  for($j=0;$j<=23; $j++){
			$j=str_pad($j,2,'0',STR_PAD_LEFT);
			if($_SESSION['PRESUPUESTO_CIRUGIA']['DURACION_HORAS']==$j){
				$this->salida.="     <option selected value = \"$j\">$j</option>";
			}else{
				$this->salida.="     <option value = \"$j\">$j</option>";
			}
    }
    $this->salida.="         </select>&nbsp;";
		$this->salida.="         <select size=\"1\"  name=\"minutos\" class=\"select\">";
	  $this->salida.="         <option value = -1>Minutos</option>";
		for($j=0;$j<=59; $j++){
			$j=str_pad($j,2,'0',STR_PAD_LEFT);
			if($_SESSION['PRESUPUESTO_CIRUGIA']['DURACION_MINUTOS']==$j){
				$this->salida.="    <option selected value = \"$j\" >$j</option>";
			}else{
				$this->salida.="    <option value=\"$j\">$j</option>";
			}
    }
    $this->salida.="        </select></td>";
    $this->salida .= "			</tr>";
    $this->salida .= "			<tr class=\"modulo_list_claro\">";
		$this->salida .= "			<td class=\"".$this->SetStyle("via")."\">VIAS DE ACCESO</td>";
		$this->salida .= "			<td colspan=\"3\"><select name=\"via\" class=\"select\">";
		$this->salida .="      <option value=\"-1\" selected>---seleccione---</option>";
	  $vias=$this->TiposViasCirugia();
		foreach($vias as $value=>$titulo){
			if($value==$_SESSION['PRESUPUESTO_CIRUGIA']['VIAS_ACCESO']){
				$this->salida .="  <option value=\"$value\" selected>$titulo</option>";
			}else{
				$this->salida .="  <option value=\"$value\">$titulo</option>";
			}
		}
	  $this->salida .= "      </select></td>";
		$this->salida .= "		   </tr>";
		$this->salida .= "			<tr class=\"modulo_table_list_title\"><td colspan=\"4\" class=\"modulo_table_list_title\">DATOS PARA LA RECUPERACION</td></tr>";
    $this->salida .= "			<tr class=\"modulo_list_claro\">";
		$this->salida .= "			<td class=\"".$this->SetStyle("salaRecuperacion")."\">RECUPERACION EN SALA ESPECIAL</td>";
		$this->salida .= "			<td colspan=\"3\"><select name=\"salaRecuperacion\" class=\"select\">";
		$che='';
		if($_SESSION['PRESUPUESTO_CIRUGIA']['SALA_RECUPERACION']==1){$che='selected';}
		if($_SESSION['PRESUPUESTO_CIRUGIA']['SALA_RECUPERACION']==2){$che='selected';}
		$this->salida .="      <option value=\"-1\" selected>---seleccione---</option>";
		$this->salida .="      <option value=\"1\" $che>UNIDAD DE CUIDADOS INTENSIVOS</option>";
		$this->salida .="      <option value=\"2\" $che>UNIDAD DE CUIDADOS INTERMEDIOS</option>";
	  $this->salida .= "     </select></td>";
		$this->salida .= "		  </tr>";
		$this->salida .= "			<tr class=\"modulo_list_claro\">";
		$this->salida .= "			<td class=\"".$this->SetStyle("internacionPreQX")."\">INTERNACION PRE-QUIRURGICA</td>";
		$this->salida .= "			<td colspan=\"3\">";
		$this->salida .= "     <select name=\"internacionPreQX\" class=\"select\">";
		$tiposCamas=$this->TiposCamasQX();
		$this->salida .="      <option value=\"-1\" selected>---seleccione---</option>";
		for($i=0;$i<sizeof($tiposCamas);$i++){
		  $value=$tiposCamas[$i]['tipo_cama_id'];
			$titulo=$tiposCamas[$i]['descripcion'];
			if($value==$_SESSION['PRESUPUESTO_CIRUGIA']['SALA_RECUPERACION_PRE_QX']){
				$this->salida .="  <option value=\"$value\" selected>$titulo</option>";
			}else{
				$this->salida .="  <option value=\"$value\">$titulo</option>";
			}
		}
	  $this->salida .= "     </select>";
		$this->salida .= "     <select name=\"diasInternacionPreQX\" class=\"select\">";
		$this->salida .="      <option value=\"-1\" selected>---Cantidad Dias---</option>";
		for($i=1;$i<=10;$i++){
		  $value=$i;
			if($value==$_SESSION['PRESUPUESTO_CIRUGIA']['DIAS_RECUPERACION_PRE_QX']){
				$this->salida .="  <option value=\"$value\" selected>$value</option>";
			}else{
				$this->salida .="  <option value=\"$value\">$value</option>";
			}
		}
	  $this->salida .= "     </select>";
		$this->salida .= "     </td>";
		$this->salida .= "		  </tr>";

		$this->salida .= "			<tr class=\"modulo_list_claro\">";
		$this->salida .= "			<td class=\"".$this->SetStyle("internacionPostQX")."\">INTERNACION POST-QUIRURGICA</td>";
		$this->salida .= "			<td colspan=\"3\">";
		$this->salida .= "     <select name=\"internacionPostQX\" class=\"select\">";
		$tiposCamas=$this->TiposCamasQX();
		$this->salida .="      <option value=\"-1\" selected>---seleccione---</option>";
		for($i=0;$i<sizeof($tiposCamas);$i++){
		  $value=$tiposCamas[$i]['tipo_cama_id'];
			$titulo=$tiposCamas[$i]['descripcion'];
			if($value==$_SESSION['PRESUPUESTO_CIRUGIA']['SALA_RECUPERACION_POST_QX']){
				$this->salida .="  <option value=\"$value\" selected>$titulo</option>";
			}else{
				$this->salida .="  <option value=\"$value\">$titulo</option>";
			}
		}
	  $this->salida .= "     </select>";
		$this->salida .= "     <select name=\"diasInternacionPostQX\" class=\"select\">";
		$this->salida .="      <option value=\"-1\" selected>---Cantidad Dias---</option>";
		for($i=1;$i<=10;$i++){
		  $value=$i;
			if($value==$_SESSION['PRESUPUESTO_CIRUGIA']['DIAS_RECUPERACION_POST_QX']){
				$this->salida .="  <option value=\"$value\" selected>$value</option>";
			}else{
				$this->salida .="  <option value=\"$value\">$value</option>";
			}
		}
	  $this->salida .= "     </select>";
		$this->salida .= "     </td>";
		$this->salida .= "		  </tr>";
    $this->salida .= "			</table>";

    $this->salida .= "  		<table width=\"98%\" border=\"0\" align=\"center\" class=\"normal_10\" >";
		$this->salida .= "  		<tr><td align=\"center\">";
		$this->salida .= "  		<input type=\"submit\" class=\"input-submit\" value=\"VOLVER\" name=\"Salir\">";
		$this->salida .= "  		<input type=\"submit\" class=\"input-submit\" value=\"LIQUIDAR\" name=\"Liquidar\">";
		$this->salida .= "  		</td></tr>";
		$this->salida .= "			</table>";
    $this->salida .= "      </form>";
	  $this->salida .= ThemeCerrarTabla();
		return true;

	  //,$ambitoCirugia,$TipoAnestesia,$gasAnestesico,$gasAnestesicoMe,$DuracionGas,$Horaduracionquiro,$Minduracionquiro,$perfusionista,$adicionCir,$adicionPro,$tipoIdCirPro,$cirujanoPro,$bilateral,$codigos,$procedimiento
		/*$cadenaTipoA=explode('/',$TipoAnestesia);
		if($cadenaTipoA[1]==0){
			$desabilitar='disabled';
		}
		if($perfusionista){
			$var='checked';
		}
		$this->salida .= ThemeAbrirTabla('PRESUPUESTO DE LA CIRUGIA');
		$RUTA = $_ROOT ."classes/classbuscador/buscador.php?forma=forma&tipo=";
		$this->salida.="<script language='javascript'>\n";
		$this->salida.="  function desabilita(frm,valor){";
		$this->salida.="    cadena=valor.split('/');";
		$this->salida.="    if(cadena[1]==0 || valor==-1){";
		$this->salida.="  		frm.gasAnestesico.disabled=true;\n";
		$this->salida.="  		frm.gasAnestesicoMe.disabled=true;\n";
		$this->salida.="  		frm.DuracionGas.disabled=true;\n";
		$this->salida.="  	}else{\n";
		$this->salida.="  		frm.gasAnestesico.disabled=false;\n";
		$this->salida.="  		frm.gasAnestesicoMe.disabled=false;\n";
		$this->salida.="  		frm.DuracionGas.disabled=false;\n";
		$this->salida.="  	}\n";
		$this->salida.="  }\n";
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
		$this->salida.="    rem = window.open(url2, nombre, str);";
		$this->salida.="    }\n";
		$this->salida.="</script>\n";
		$this->salida .= "		<table width=\"100%\" align=\"center\" border=\"0\">\n";
		$this->salida .= "		<tr><td colspan=\"4\" align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "		</td></tr>";
		$this->salida .= "		<tr class=\"modulo_table_title\"><td align=\"center\">DATOS PRINCIPALES DE LA CIRUGIA</td></tr>";
		$this->salida .= "		<tr><td class=\"modulo_list_oscuro\">\n";
		$accion=ModuloGetURL('app','QXEjecucion','user','DatosPrincipalesPresupuesto',array("TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,
		"nombrePac"=>$nombrePac,"Responsable"=>$Responsable,"tipoAfil"=>$tipoAfil,"rango"=>$rango,"nombretipoafil"=>$nombretipoafil,"semanas"=>$semanas));
		$this->salida .= "      <form name=\"formaUno\" action=\"$accion\" method=\"post\">";
		$this->salida .= "   		<BR><table width=\"95%\" border=\"0\" align=\"center\" class=\"normal_10\" >";
		$this->salida .= "			<tr class=\"modulo_table_list_title\"><td colspan=\"3\" class=\"modulo_table_list_title\">DATOS CIRUGIA</td></tr>";
		$this->salida .= "			<tr class=\"modulo_list_claro\">";
		$this->salida .= "			<td width=\"15%\" class=\"".$this->SetStyle("ambitoCirugia")."\">AMBITO CIRUGIA</td><td colspan=\"2\"><select name=\"ambitoCirugia\" class=\"select\">";
	  $tiposAmbitos=$this->TiposdeAmbitosdeCirugia();
	  $this->MostrasSelect($tiposAmbitos,'False',$ambitoCirugia);
	  $this->salida .= "    	</select></td></tr>";
		$this->salida .= "			<tr class=\"modulo_list_claro\">";
		$this->salida .= "			<td class=\"".$this->SetStyle("TipoAnestesia")."\">TIPO ANESTESIA</td><td><select name=\"TipoAnestesia\" onchange=\"desabilita(this.form,this.value)\" class=\"select\">";
	  $TiposAnestesias=$this->TiposDeAnestesias();
	  $this->MostrarTiposDeAnestesias($TiposAnestesias,'False',$TipoAnestesia);
	  $this->salida .= "      </select></td>";
		$this->salida .= "      <td>";
		$this->salida .= "					<BR><table width=\"90%\" align=\"center\" border=\"0\">\n";
		$this->salida .= "					<tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("gasAnestesico")."\">GAS ANESTESICO</td><td><select name=\"gasAnestesico\" class=\"select\" $desabilitar>";
	  $TipoGases=$this->TiposGasesAnestesicos('A');
	  $this->MostrasSelect($TipoGases,'False',$gasAnestesico);
	  $this->salida .= "      		</select></td>";
		$this->salida .= "					</td></tr>";
		$this->salida .= "					<tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("gasAnestesicoMe")."\">GAS MEDICINAL</td><td><select name=\"gasAnestesicoMe\" class=\"select\" $desabilitar>";
	  $TipoGases=$this->TiposGasesAnestesicos('M');
	  $this->MostrasSelect($TipoGases,'False',$gasAnestesicoMe);
	  $this->salida .= "      		</select></td>";
		$this->salida .= "		   		</tr>";
		$this->salida .= "					<tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("DuracionGas")."\">MINUTOS SUMISTRO GAS</td>";
		$this->salida .= "					<td><input size=\"4\" type=\"text\" class=\"input-text\" name=\"DuracionGas\" value=\"$DuracionGas\" $desabilitar></td>";
		$this->salida .= "		   		</tr>";
		$this->salida .= "					</table><BR>";
		$this->salida .= "    	</td>";
		$this->salida .= "     	</tr>";
		$this->salida .= "			<tr class=\"modulo_list_claro\">";
		$this->salida .= "			<td class=\"".$this->SetStyle("duracionquiro")."\">TIEMPO QUIROFANO</td>";
		$this->salida .= "			<td><input size=\"4\" type=\"text\" class=\"input-text\" name=\"Horaduracionquiro\" value=\"$Horaduracionquiro\"><label class=\"label\">&nbsp&nbsp&nbsp;HORAS</label></td>";
		$this->salida .= "			<td><input size=\"4\" type=\"text\" class=\"input-text\" name=\"Minduracionquiro\" value=\"$Minduracionquiro\"><label class=\"label\">&nbsp&nbsp&nbsp;MINUTOS</label></td>";
		$this->salida .= "     	</tr>";
		$this->salida .= "			<tr class=\"modulo_list_claro\">";
		$this->salida .= "			<td class=\"".$this->SetStyle("perfusionista")."\">PERFUSIONISTA</td>";
		$this->salida .= "			<td colspan=\"2\"><input type=\"checkbox\" $var class=\"input-text\" name=\"perfusionista\"></td>";
		$this->salida .= "     	</tr>";		
		$this->salida .= "      <tr class=\"modulo_list_claro\"><td colspan=\"3\" align=\"center\">\n";
    $this->salida .= "      <input type=\"submit\" name=\"Guardar\" value=\"GUARDAR\" class=\"input-submit\">";
    $this->salida .= "      </td></tr>";
		$this->salida .= "    	</table><BR>";
		$this->salida .= "			</form>";
		if($adicionCir){
		$accion=ModuloGetURL('app','QXEjecucion','user','CirujanosPresupuesto',array("TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePac"=>$nombrePac,"Responsable"=>$Responsable,"tipoAfil"=>$tipoAfil,"rango"=>$rango,"nombretipoafil"=>$nombretipoafil,"semanas"=>$semanas,
		"ambitoCirugia"=>$ambitoCirugia,"TipoAnestesia"=>$TipoAnestesia,"gasAnestesico"=>$gasAnestesico,"gasAnestesicoMe"=>$gasAnestesicoMe,"DuracionGas"=>$DuracionGas,"Horaduracionquiro"=>$Horaduracionquiro,"Minduracionquiro"=>$Minduracionquiro,"perfusionista"=>$perfusionista));
		$this->salida .= "      <form name=\"formaUno\" action=\"$accion\" method=\"post\">";
		$this->salida .= "   		<table width=\"95%\" border=\"0\" align=\"center\" class=\"normal_10\" >";
		$this->salida .= "			<tr class=\"modulo_table_list_title\"><td colspan=\"2\" class=\"modulo_table_list_title\">ADICION CIRUJANOS</td></tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("cirujanos")."\" align=\"center\">CIRUJANOS</td>\n";
		$this->salida .= "      <td align=\"center\">\n";
		$this->salida .= "    	<select name=\"cirujanoPro\" class=\"select\">\n";
		$profesionales=$this->profesionalesEspecialista();
		$this->BuscarProfesionlesEspecialistas($profesionales,'False',$cirujanoPro);
		$this->salida .= "    	</select>\n";
		$this->salida .= "      &nbsp&nbsp&nbsp;<input class=\"input-submit\" type=\"submit\" name=\"adicionarCir\" value=\"ADICIONAR CIRUJANO AL ACTO\"></td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\"><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Salir\" value=\"CANCELAR\"></td></tr>";
		$this->salida .= "    	</table><BR>";
		$this->salida .= "			</form>";
		}
		if($adicionPro){
		$accion=ModuloGetURL('app','QXEjecucion','user','InserProcedimientosCirujanosPresupuesto',array("TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePac"=>$nombrePac,"Responsable"=>$Responsable,"tipoAfil"=>$tipoAfil,"rango"=>$rango,"nombretipoafil"=>$nombretipoafil,"semanas"=>$semanas,
		"ambitoCirugia"=>$ambitoCirugia,"TipoAnestesia"=>$TipoAnestesia,"gasAnestesico"=>$gasAnestesico,"gasAnestesicoMe"=>$gasAnestesicoMe,"DuracionGas"=>$DuracionGas,"Horaduracionquiro"=>$Horaduracionquiro,"Minduracionquiro"=>$Minduracionquiro,"perfusionista"=>$perfusionista,
		"tipoIdCirPro"=>$tipoIdCirPro,"cirujanoPro"=>$cirujanoPro));

		$this->salida .= "      <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "   		<table width=\"95%\" border=\"0\" align=\"center\" class=\"normal_10\" >";
		$nombreCir=$this->NombreProfesional($cirujanoPro,$tipoIdCirPro);
		$this->salida .= "		 	<tr class=\"modulo_table_list_title\"><td colspan=\"2\">NUEVO PROCEDIMIENTO REALIZADO POR EL CIRUJANO ".$nombreCir['nombre']."</td></tr>";
		$this->salida .= "		 	<tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("tipoProcedimiento")."\">TIPOS PROCEDIMIENTOS</td><td><select name=\"tipoProcedimiento\" class=\"select\">";
	  $tiposProcedimientos=$this->tiposdeProcedimientos();
	  $this->MostrartiposdeProcedimientos($tiposProcedimientos,'False',$tipoProcedimiento);
	  $this->salida .= "    	</select>&nbsp&nbsp&nbsp;";
		$this->salida.= "     	<input type=\"button\" name=\"buscar2\" value=\"BUSCAR\" onclick=abrirVentana(this.name,this.form) class=\"input-submit\"></td></tr>";
		$this->salida.= "     	<tr class=\"modulo_list_claro\">";
		$this->salida.= "     	<td><label class=\"".$this->SetStyle("procedimiento")."\">PROCEDIMIENTO</label></td>";
		$this->salida.= "     	<td><input type=\"text\" name=\"codigos\" maxlength=\"6\" size=\"6\" class=\"input-text\" value=\"$codigos\" READONLY>&nbsp&nbsp;";
    $this->salida.= "     	<input type=\"text\" name=\"procedimiento\" maxlength=\"600\" size=\"80\" class=\"input-text\" value=\"$procedimiento\" READONLY></td>";
		$this->salida.= "     	</tr>";
		if($bilateral){
			$this->salida .= "			<input type=\"hidden\" name=\"IndicaBilateral\" value=\"1\">";
			$this->salida .= "			<tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("viabilateral")."\">TIPO VIA:</td><td><select name=\"viabilateral\" class=\"select\">";
			$vias=$this->tiposViaBilaterales();
			$this->MostrasSelect($vias,'False',$viabilateral);
			$this->salida .= "      </select></td></tr>";
		}
		$this->salida.= "     	<tr class=\"modulo_list_claro\">";
		$this->salida.= "     	<td align =\"center\" colspan=\"2\"><input type=\"submit\" name=\"Salir\" value=\"CANCELAR\" class=\"input-submit\">";
		$this->salida.= "     	<input type=\"submit\" name=\"insertarprocedimiento\" value=\"INSERTAR PROCEDIMIENTO\" class=\"input-submit\"></td>";
		$this->salida.= "     	</tr>";
		$this->salida .= "    	</table><BR>";
		$this->salida .= "			</form>";
		}
		$accion=ModuloGetURL('app','QXEjecucion','user','LiquidacionPresupuesto');
		$this->salida .= "      <form name=\"formaUno\" action=\"$accion\" method=\"post\">";
		$this->salida .= "   		<table width=\"95%\" border=\"0\" align=\"center\" class=\"normal_10\" >";
		$this->salida .= "			<tr class=\"modulo_table_list_title\"><td colspan=\"4\" class=\"modulo_table_list_title\">CIRUJANOS Y PROCEDIMIENTTOS INSERTADOS</td></tr>";
		$this->salida .= "			<tr class=\"modulo_table_list_title\">";
		$this->salida .= "			<td nowrap width=\"40%\" colspan=\"2\" nowrap>CIRUJANO</td>";
		$this->salida .= "			<td nowrap>PROCEDIMIENTO</td>";
		$this->salida .= "			<td nowrap width=\"17%\">VIA</td>";
		$this->salida .= "			</tr>";
		$cirujanos=$this->consultaCirujanosPresupuesto();
		if($cirujanos){
			for($i=0;$i<sizeof($cirujanos);$i++){
				$this->salida .= "	 <tr class=\"modulo_list_claro\">\n";
				$this->salida .= "			<td nowrap width=\"30%\">".$cirujanos[$i]['nombre']."</td>";
				$procedimientos=$this->ProcedimientosCirujanoPresupuesto($cirujanos[$i]['tipo_id_cirujano'],$cirujanos[$i]['cirujano_id']);
				if(!$procedimientos){
				$actionEliminaCir=ModuloGetURL('app','QXEjecucion','user','EliminarCirPresupuesto',array("TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePac"=>$nombrePac,"Responsable"=>$Responsable,"tipoAfil"=>$tipoAfil,"rango"=>$rango,"nombretipoafil"=>$nombretipoafil,"semanas"=>$semanas,
				"ambitoCirugia"=>$ambitoCirugia,"TipoAnestesia"=>$TipoAnestesia,"gasAnestesico"=>$gasAnestesico,"gasAnestesicoMe"=>$gasAnestesicoMe,"DuracionGas"=>$DuracionGas,"Horaduracionquiro"=>$Horaduracionquiro,"Minduracionquiro"=>$Minduracionquiro,"perfusionista"=>$perfusionista,
				"tipoIdCirPro"=>$cirujanos[$i]['tipo_id_cirujano'],"cirujanoPro"=>$cirujanos[$i]['cirujano_id']));
				$this->salida .= "      <td nowrap width=\"5%\"><a href=\"$actionEliminaCir\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
				}else{
					$this->salida .= "    <td nowrap width=\"5%\">&nbsp;</td>";
				}
				if($procedimientos){
					$this->salida .= "		<td rowspan=\"2\" colspan=\"2\">";
					$this->salida .= "   				<table width=\"98%\" border=\"0\" align=\"center\" class=\"normal_10\" >";
					for($j=0;$j<sizeof($procedimientos);$j++){
						$this->salida .= "	 				<tr class=\"modulo_list_oscuro\">\n";
						$this->salida .= "					<td nowrap width=\"15%\">".$procedimientos[$j]['procedimiento_qx']."</td>";
						$this->salida .= "					<td>".$procedimientos[$j]['nom_procedimiento']."</td>";
						$this->salida .= "					<td nowrap width=\"20%\">".$procedimientos[$j]['viaacceso']."</td>";
						$actionElimProd=ModuloGetURL('app','QXEjecucion','user','EliminarProcPresupuesto',array("TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePac"=>$nombrePac,"Responsable"=>$Responsable,"tipoAfil"=>$tipoAfil,"rango"=>$rango,"nombretipoafil"=>$nombretipoafil,"semanas"=>$semanas,
						"ambitoCirugia"=>$ambitoCirugia,"TipoAnestesia"=>$TipoAnestesia,"gasAnestesico"=>$gasAnestesico,"gasAnestesicoMe"=>$gasAnestesicoMe,"DuracionGas"=>$DuracionGas,"Horaduracionquiro"=>$Horaduracionquiro,"Minduracionquiro"=>$Minduracionquiro,"perfusionista"=>$perfusionista,
						"tipoIdCirPro"=>$cirujanos[$i]['tipo_id_cirujano'],"cirujanoPro"=>$cirujanos[$i]['cirujano_id'],"procedimiento"=>$procedimientos[$j]['procedimiento_qx']));
						$this->salida .= "          <td nowrap width=\"5%\"><a href=\"$actionElimProd\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
						$this->salida .= "					</tr>";
					}
					$this->salida .= "					</table>";
					$this->salida .= "		</td>";
				}else{
					$this->salida .= "	<td rowspan=\"2\" colspan=\"2\">&nbsp;</td>";
				}
				$this->salida .= "		</tr>";
				$this->salida .= "	 <tr class=\"modulo_list_claro\">\n";
				$actionAdicProc=ModuloGetURL('app','QXEjecucion','user','LlamaAdicionProcedimientoActo',array("TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePac"=>$nombrePac,"Responsable"=>$Responsable,"tipoAfil"=>$tipoAfil,"rango"=>$rango,"nombretipoafil"=>$nombretipoafil,"semanas"=>$semanas,
				"ambitoCirugia"=>$ambitoCirugia,"TipoAnestesia"=>$TipoAnestesia,"gasAnestesico"=>$gasAnestesico,"gasAnestesicoMe"=>$gasAnestesicoMe,"DuracionGas"=>$DuracionGas,"Horaduracionquiro"=>$Horaduracionquiro,"Minduracionquiro"=>$Minduracionquiro,"perfusionista"=>$perfusionista,
				"tipoIdCirPro"=>$cirujanos[$i]['tipo_id_cirujano'],"cirujanoPro"=>$cirujanos[$i]['cirujano_id']));
				$this->salida .= "			<td colspan=\"2\"><a href=\"$actionAdicProc\" class=\"link\"><b>ADIONAR PROCEDIMIENTO</b></a></td>";
				$this->salida .= "		</tr>";
			}
		}
		$actionAdicCir=ModuloGetURL('app','QXEjecucion','user','LlamaAdicionCirujanoActo',array("TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePac"=>$nombrePac,"Responsable"=>$Responsable,"tipoAfil"=>$tipoAfil,"rango"=>$rango,"nombretipoafil"=>$nombretipoafil,"semanas"=>$semanas,
		"ambitoCirugia"=>$ambitoCirugia,"TipoAnestesia"=>$TipoAnestesia,"gasAnestesico"=>$gasAnestesico,"gasAnestesicoMe"=>$gasAnestesicoMe,"DuracionGas"=>$DuracionGas,"Horaduracionquiro"=>$Horaduracionquiro,"Minduracionquiro"=>$Minduracionquiro,"perfusionista"=>$perfusionista));
		$this->salida .= "			<tr><td><a href=\"$actionAdicCir\" class=\"link\"><b><img border=\"0\" src=\"".GetThemePath()."/images/atencion_citas.png\"></b></a>&nbsp;<b>ADIONAR CIRUJANO</b></td></tr>";
		$this->salida .= "			</table>";		
		$this->salida .= "  		<table width=\"98%\" border=\"0\" align=\"center\" class=\"normal_10\" >";
		$this->salida .= "  		<tr><td align=\"center\">";
		$this->salida .= "  		<input type=\"submit\" class=\"input-submit\" value=\"SALIR\" name=\"Salir\">";
		$this->salida .= "  		<input type=\"submit\" class=\"input-submit\" value=\"LIQUIDAR\" name=\"Liquidar\">";
		$this->salida .= "  		</td></tr>";
		$this->salida .= "			</table>";
		$this->salida .= "			</form>";
		$this->salida .= "</td></tr>";
		$this->salida .= "</table><BR><BR>";
		$this->salida .= ThemeCerrarTabla();
		return true;*/
	}


  function BuscadorProfesional($TipoDocumento,$Documento,$nombrePac,$Responsable,$tipoAfil,$rango,$semanas,$numeroCirujanoSelect,$TipoDocumentoBus,$DocumentoBus,$NomcirujanoBus){

		$this->salida .= ThemeAbrirTabla('BUSCADOR ESPECIALISTA');
		$action=ModuloGetURL('app','QXEjecucion','user','SeleccionProfesionalBuscador',array("TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePac"=>$nombrePac,"Responsable"=>$Responsable,"tipoAfil"=>$tipoAfil,"rango"=>$rango,"semanas"=>$semanas,"numeroCirujanoSelect"=>$numeroCirujanoSelect,"TipoDocumentoBus"=>$TipoDocumentoBus,"DocumentoBus"=>$DocumentoBus,"NomcirujanoBus"=>$NomcirujanoBus));
		$this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->Encabezado();
    $this->salida .= "    <table width=\"60%\" border=\"0\" align=\"center\">";
		$this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"3\">PARAMENTROS DE BUSQUEDA</td></tr>";
		$this->salida .= "    <tr class=\"modulo_list_claro\">";
		$this->salida .= "    <td class=\"label\">IDENTIFICACION</td>";
    $this->salida .= "    <td><select name=\"TipoDocumentoBus\" class=\"select\">";
		$tipos=$this->tipo_id_paciente();
		foreach($tipos as $value=>$titulo){
			if($value==$TipoDocumentoBus){
				$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
			}else{
				$this->salida .=" <option value=\"$value\">$titulo</option>";
			}
		}
		$this->salida .= "     </select></td>";
		$this->salida .= "		 <td><input type=\"text\" class=\"input-text\" name=\"DocumentoBus\" size=\"32\" maxlength=\"32\" value=\"$DocumentoBus\"></td>";
    $this->salida .= "     </tr>";
		$this->salida .= "     <tr class=\"modulo_list_claro\">";
		$this->salida .= "     <td class=\"label\">NOMBRES</td>";
		$this->salida .= "     <td colspan=\"2\">";
		$this->salida .= "     <input size=\"50\" type=\"text\" name=\"NomcirujanoBus\" value=\"".$NomcirujanoBus."\" class=\"input-submit\">&nbsp&nbsp&nbsp;";
		$this->salida .= "     <input class=\"input-submit\" type=\"submit\" name=\"filtrar\" value=\"FILTRAR\" class=\"input-submit\">";
		$this->salida .= "     <input class=\"input-submit\" type=\"submit\" name=\"Volver\" value=\"VOLVER\" class=\"input-submit\">";
		$this->salida .= "     </td>";
		$this->salida .= "	  </table><BR>";
		$profesionales=$this->profesionalesEspecialistas($TipoDocumentoBus,$DocumentoBus,$NomcirujanoBus,$barra=1);
		if($profesionales){
		  $this->salida .= "    <table width=\"60%\" border=\"0\" align=\"center\">";
      $this->salida .= "    <tr class=\"modulo_table_list_title\">";
			$this->salida .= "    <td>IDENTIFICACION</td>";
			$this->salida .= "    <td>NOMBRE</td>";
			$this->salida .= "    <td>&nbsp;</td>";
			$this->salida .= "    </tr>";
			for($i=0;$i<sizeof($profesionales);$i++){
        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
        $this->salida .= "    <tr class=\"$estilo\">";
        $this->salida .= "    <td width=\"30%\">".$profesionales[$i]['tipo_id_tercero']." ".$profesionales[$i]['tercero_id']."</td>";
				$this->salida .= "    <td>".$profesionales[$i]['nombre']."</td>";
				$actionSelect=ModuloGetURL('app','QXEjecucion','user','SeleccionProfesionalBuscador',array("TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePac"=>$nombrePac,"Responsable"=>$Responsable,"tipoAfil"=>$tipoAfil,"rango"=>$rango,"semanas"=>$semanas,"numeroCirujanoSelect"=>$numeroCirujanoSelect,"profesional"=>$profesionales[$i]['tipo_id_tercero']."/".$profesionales[$i]['tercero_id']."/".$profesionales[$i]['nombre']));
				$this->salida .= "    <td align=\"center\" width=\"5%\"><a href=\"$actionSelect\"><img title=\"Seleccionar Cirujano\" border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"><a></td>";
				$this->salida .= "    </tr>";
				$y++;
			}
			$this->salida .= "	  </table><BR>";
			$this->salida .=$this->RetornarBarra(3);
		}else{
      $this->salida .= "    <table width=\"60%\" border=\"0\" align=\"center\">";
      $this->salida .= "    <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON REGISTROS CON ESTOS PARAMETROS</td></tr>";
      $this->salida .= "	  </table><BR>";
		}
    $this->salida .= "		</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function InsertarProcedReqLiquidacion($TipoDocumento,$Documento,$nombrePac,$Responsable,$tipoAfil,$rango,$semanas,$numeroCirujano,$procedimientoBus,$codigoBus,$tipoProcedimiento){
    $this->salida .= ThemeAbrirTabla('BUSCADOR PROCEDIMIENTOS QX');
		$action=ModuloGetURL('app','QXEjecucion','user','SeleccionProcedimientoQX',array("TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePac"=>$nombrePac,"Responsable"=>$Responsable,"tipoAfil"=>$tipoAfil,"rango"=>$rango,"semanas"=>$semanas,"numeroCirujano"=>$numeroCirujano));
		$this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->Encabezado();
		$this->salida .= "	  <table width=\"70%\" border=\"0\" class=\"normal_10\" align=\"center\">";
		$this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"4\">PARAMENTROS DE BUSQUEDA</td></tr>";
		$this->salida .= "		  <tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("tipoProcedimiento")."\">TIPOS PROCEDIMIENTOS:</td>";
		$this->salida .= "		  <td colspan=\"3\"><select name=\"tipoProcedimiento\" class=\"select\">";
	  $tiposProcedimientos=$this->tiposdeProcedimientos();
		$this->salida .="       <option value=\"-1\">----seleccionar---</option>";
		for($i=0;$i<sizeof($tiposProcedimientos);$i++){
			$value=$tiposProcedimientos[$i]['tipo_cargo'].'/'.$tiposProcedimientos[$i]['grupo_tipo_cargo'];
			$titulo=$tiposProcedimientos[$i]['descripcion'];
			if($value==$tipoProcedimiento){
			$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
			}else{
				$this->salida .=" <option value=\"$value\">$titulo</option>";
			}
		}
	  $this->salida .= "      </select></td>";
		$this->salida.= "       </tr>";
		$this->salida .= "    <tr class=\"modulo_list_claro\">";
    $this->salida .= "    <td class=\"label\">PROCEDIMIENTO</td>";
    $this->salida .= "    <td><input size=\"60\" type=\"text\" name=\"procedimientoBus\" value=\"".$procedimientoBus."\" class=\"input-submit\"></td>";
    $this->salida .= "    <td class=\"label\">CODIGO</td>";
		$this->salida .= "    <td><input type=\"text\" size=\"10\" name=\"codigoBus\" value=\"".$codigoBus."\" class=\"input-submit\"></td>";
    $this->salida .= "    </tr>";
		$this->salida .= "    <tr class=\"modulo_list_claro\"><td colspan=\"4\" align=\"center\">";
    $this->salida .= "    <input type=\"submit\" name=\"filtrar\" value=\"FILTRAR\" class=\"input-submit\">";
		$this->salida .= "    <input type=\"submit\" name=\"volver\" value=\"VOLVER\" class=\"input-submit\">";
    $this->salida .= "    </td></tr>";
		$this->salida .= "	  </table><BR>";
    $procedimientos=$this->BusquedaProcedimientosQX($tipoProcedimiento,$codigoBus,$procedimientoBus);
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
				if($procedimientos[$i]['sw_bilateral']=='1'){
          $sw_bilateral='1';
				}else{
          $sw_bilateral='0';
				}
				$actionSelect=ModuloGetURL('app','QXEjecucion','user','SeleccionProcedimientoQX',array("TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePac"=>$nombrePac,"Responsable"=>$Responsable,"tipoAfil"=>$tipoAfil,"rango"=>$rango,"semanas"=>$semanas,"numeroCirujano"=>$numeroCirujano,"cargo"=>$procedimientos[$i]['cargo'].'||//'.$procedimientos[$i]['descripcion'].'||//'.$sw_bilateral));
				$this->salida .= "  <td align=\"center\" width=\"5%\"><a href=\"$actionSelect\"><img border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"><a></td>";
				$this->salida .= "  </tr>";
				$y++;
			}
			$this->salida .= "    </table>";
			$this->salida .=$this->RetornarBarra(2);
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
* Funcion que se encarga de listar los nombres de los profesionales especialistas y visualizarlos
* @return array
* @param array codigos y valores de los profesionales de la base de datos
* @param boolean indicador de selecion de un elemento en el objeto donde se imprimen los profesionales
* @param string elemento seleccionado en el objeto donde se imprimen los profesionales
*/
	function MostrarTiposDeAnestesias($tipos,$Seleccionado='False',$TipoAfil=''){
		switch($Seleccionado){
			case 'False':{
        $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
				for($i=0;$i<sizeof($tipos);$i++){
				  $value=$tipos[$i]['qx_tipo_anestesia_id'].'/'.$tipos[$i]['sw_uso_gases'];
					$titulo=$tipos[$i]['descripcion'];
					if($value==$TipoAfil){
					$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				  }else{
            $this->salida .=" <option value=\"$value\">$titulo</option>";
				  }
			  }
			  break;
		  }case 'True':{
			  for($i=0;$i<sizeof($tipos);$i++){
			    $value=$tipos[$i]['qx_tipo_anestesia_id'].'/'.$tipos[$i]['sw_uso_gases'];
					$titulo=$tipos[$i]['descripcion'];
				  if($value==$TipoAfil){
				    $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				  }
				  $this->salida .=" <option value=\"$value\">$titulo</option>";
			  }
			  break;
		  }
	  }
	}

	function SeleccionCargosEquivalentes($procedimientos,$datosPresupuesto){
		IncludeLib("tarifario_cargos_qx");
		$this->salida .= ThemeAbrirTabla('LIQUIDACION PRESUPUESTO DE LA CIRUGIA');
		$this->Encabezado();
		$accion=ModuloGetURL('app','QXEjecucion','user','LiquidarProcPresupuesto',array("datosPresupuesto"=>$datosPresupuesto));
		$this->salida .= "	<form name=\"formaUno\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  	<table class=\"normal_10\" border=\"0\" width=\"95%\" align=\"center\">";
		$this->salida .= "    <tr class=\"modulo_list_oscuro\"><td>";
		for($i=0;$i<sizeof($procedimientos);$i++){
			$arreglo=BuscarCargoEquivalente($procedimientos[$i]['procedimiento_qx'],$procedimientos[$i]['plan_id']);									
			if(empty($arreglo['cargo']) && empty($arreglo['tarifario']) && ($arreglo['codigo_error']==1)){			
			$this->salida .= "  			<BR><table class=\"normal_10\" border=\"0\" width=\"98%\" align=\"center\">";								
			$this->salida .= "    		<tr class=\"modulo_list_claro\">";
			$this->salida .= "    		<td width=\"5%\" class=\"label\">CARGO</td>";
			$this->salida .= "    		<td width=\"10%\">".$procedimientos[$i]['procedimiento_qx']."</td>";
			$this->salida .= "    		<td>".$procedimientos[$i]['descripcion']."</td>";
			$this->salida .= "    		<td width=\"5%\" class=\"label\">VIA</td>";
			$this->salida .= "    		<td width=\"15%\">".$procedimientos[$i]['via']."</td>";
			$this->salida .= "				<tr class=\"modulo_table_title\"><td colspan=\"5\" align=\"center\" >EQUIVALENCIAS</td></tr>";
			$this->salida .= "    		</tr>";			
			$this->salida .= "    		<tr class=\"modulo_list_claro\">";			
			$this->salida .= "    		<td align=\"center\" colspan=\"5\" class=\"label_error\">".$arreglo['mensaje_error']."</td>";
			$this->salida .= "    		</tr>";
			$this->salida .= "  			</table><BR>";								
			}elseif(empty($arreglo['cargo']) && empty($arreglo['tarifario']) && ($arreglo['codigo_error']==2)){
			$this->salida .= "  			<BR><table class=\"normal_10\" border=\"0\" width=\"98%\" align=\"center\">";								
			$this->salida .= "    		<tr class=\"modulo_list_claro\">";
			$this->salida .= "    		<td width=\"5%\" class=\"label\">CARGO</td>";
			$this->salida .= "    		<td width=\"10%\">".$procedimientos[$i]['procedimiento_qx']."</td>";
			$this->salida .= "    		<td>".$procedimientos[$i]['descripcion']."</td>";			
			$this->salida .= "    		<td class=\"label\">VIA</td>";
			$this->salida .= "    		<td colspan=\"2\">".$procedimientos[$i]['via']."</td>";
			$this->salida .= "    		</tr>";			
			$this->salida .= "				<tr class=\"modulo_table_title\"><td colspan=\"6\" align=\"center\" >EQUIVALENCIAS</td></tr>";
			$this->salida .= "    		<tr class=\"modulo_list_claro\">";				
			$this->salida .= "    		<td align=\"center\" colspan=\"6\" class=\"label_error\">".$arreglo['mensaje_error']."</td>";			
			$this->salida .= "    		</tr>";
			$cargos=$arreglo['cargos'];
			for($j=0;$j<sizeof($cargos);$j++){				
				$this->salida .= "    	<tr class=\"modulo_list_claro\">";
				$this->salida .= "    	<td class=\"label\">CARGO</td>";							
				$this->salida .= "    	<td>".$cargos[$j]['cargo']."</td>";
				$this->salida .= "    	<td>".$cargos[$j]['descripcion']."</td>";
				$nombreTarif=$this->HallarNombreTarifario($cargos[$j]['tarifario']);	
				$this->salida .= "    	<td width=\"5%\" class=\"label\">TARIFARIO</td>";															
				$this->salida .= "    	<td width=\"20%\">".$nombreTarif['descripcion']."</td>";				
				$this->salida .= "    	<td width=\"5%\"><input type=\"checkbox\" name=\"SeleccionTarif[]\" value=\"".$procedimientos[$i]['tipo_id_cirujano']."|/".$procedimientos[$i]['cirujano_id']."|/".$cargos[$j]['cargo']."|/".$cargos[$j]['tarifario']."|/".$cargos[$j]['tipo_liquidacion_qx']."\"></td>";				
			}			
			$this->salida .= "  			</table><BR>";																							
			}else{
				$this->salida .= "  			<BR><table class=\"normal_10\" border=\"0\" width=\"98%\" align=\"center\">";								
				$this->salida .= "    		<tr class=\"modulo_list_claro\">";
				$this->salida .= "    		<td width=\"5%\" class=\"label\">CARGO</td>";
				$this->salida .= "    		<td width=\"10%\">".$procedimientos[$i]['procedimiento_qx']."</td>";
				$this->salida .= "    		<td>".$procedimientos[$i]['descripcion']."</td>";
				$this->salida .= "    		<td class=\"label\">VIA</td>";
				$this->salida .= "    		<td>".$procedimientos[$i]['via']."</td>";
				$this->salida .= "    		</tr>";
				$this->salida .= "				<tr class=\"modulo_table_title\"><td colspan=\"5\" align=\"center\" >EQUIVALENCIAS</td></tr>";		
				$this->salida .= "    		<input type=\"hidden\" name=\"arregloLiqui[]\" value=\"".$procedimientos[$i]['tipo_id_cirujano']."|/".$procedimientos[$i]['cirujano_id']."|/".$arreglo['cargo']."|/".$arreglo['tarifario']."|/".$arreglo['tipo_liquidacion_qx']."\">";
				$this->salida .= "    		<tr class=\"modulo_list_claro\">";
				$this->salida .= "    		<td class=\"label\">CARGO</td>";							
				$this->salida .= "    		<td>".$arreglo['cargo']."</td>";
				$this->salida .= "    		<td>".$arreglo['descripcion']."</td>";
				$nombreTarif=$this->HallarNombreTarifario($arreglo['tarifario']);							
				$this->salida .= "    		<td width=\"5%\" class=\"label\">TARIFARIO</td>";							
				$this->salida .= "    		<td width=\"20%\">".$nombreTarif['descripcion']."</td>";					
				$this->salida .= "    		</tr>";
				$this->salida .= "  			</table><BR>";																																	
			}
		}	
		$this->salida .= "   	</td></tr>";				
		$this->salida .= "    </table>";
		$this->salida .= "  	<table width=\"98%\" border=\"0\" align=\"center\" class=\"normal_10\" >";
		$this->salida .= "  	<tr><td align=\"center\">";		
		$this->salida .= "  	<input type=\"submit\" class=\"input-submit\" value=\"CANCELAR\" name=\"Cancelar\">";
		$this->salida .= "  	<input type=\"submit\" class=\"input-submit\" value=\"LIQUIDAR\" name=\"Liquidar\">";
		$this->salida .= "  	</td></tr>";
		$this->salida .= "		</table>";		
		$this->salida .= "	</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function ProgramacionesDelDiaQX($programaciones){
	  $this->salida .= ThemeAbrirTabla('PROGRAMACIONES DEL DIA');
		$this->Encabezado();
		//$accion=ModuloGetURL('app','QXEjecucion','user','LiquidarProcPresupuesto',array("datosPresupuesto"=>$datosPresupuesto));
		$this->salida .= "<form name=\"formaUno\" action=\"$accion\" method=\"post\">";
		$this->salida .= "    <table class=\"normal_10\" border=\"0\" width=\"95%\" align=\"center\">";
		if($programaciones){
		$this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"7\">PROGRAMACIONES</td></tr>";
		$this->salida .= "    <tr class=\"modulo_table_list_title\">";
		$this->salida .= "    <td>CODIGO</td>";
		$this->salida .= "    <td>ID PACIENTE</td>";
		$this->salida .= "    <td>NOMBRE PACIENTE</td>";
		$this->salida .= "    <td>ID CIRUJANO</td>";
		$this->salida .= "    <td>NOMBRE CIRUJANO</td>";
		$this->salida .= "    <td>QUIROFANO</td>";
		$this->salida .= "    <td>&nbsp;</td>";
		$this->salida .= "    </tr>";
		$y=0;
		for($i=0;$i<sizeof($programaciones);$i++){
		  if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
      $this->salida .= "    <tr class=\"$estilo\">";
			$this->salida .= "    <td width=\"10%\">".$programaciones[$i]['programacion_id']."</td>";
			$this->salida .= "    <td>".$programaciones[$i]['tipo_id_paciente']." ".$programaciones[$i]['paciente_id']."</td>";
			$this->salida .= "    <td>".$programaciones[$i]['nombrepac']."</td>";
			$this->salida .= "    <td>".$programaciones[$i]['tipo_id_cirujano']." ".$programaciones[$i]['cirujano_id']."</td>";
			$this->salida .= "    <td>".$programaciones[$i]['nombre']."</td>";
			$this->salida .= "    <td>".$programaciones[$i]['quirofano']."</td>";
			$action=ModuloGetURL('app','QXEjecucion','user','AdmitirProgramacionQX',array("programacion"=>$programaciones[$i]['programacion_id'],"tipoIdPaciente"=>$programaciones[$i]['tipo_id_paciente'],"PacienteId"=>$programaciones[$i]['paciente_id']));
			$this->salida .= "    <td><a href=\"$action\" class=\"link\"><b>ADMITIR</b></a></td>";
      $this->salida .= "    </tr>";
			$y++;
		}
		}
		$this->salida .= "    <tr>";
		$action=ModuloGetURL('app','QXEjecucion','user','AdmitirProgramacionQX');
		$this->salida .= "    <td colspan=\"7\"><a href=\"$action\" class=\"link\"><b><BR>ADMITIR SIN PROGRAMACION</b></a></td>";
		$this->salida .= "    </tr>";
		$this->salida .= "    </table>";
    $this->salida .= "	</form>";
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

/**
* Funcion que visulaiza la forma donde se muestran los datos introducidos en una programacion Quirurjica
* @return boolean
*/
	function CumplimientoCirugia($TipoId,$Documento,$Responsable,$cuenta){

		$this->salida .= ThemeAbrirTabla('CUMPLIMIENTO DE LLEGADA DE LA CIRUGIA');
		$accion=ModuloGetURL('app','QXEjecucion','user','SeleccionCumplimiento',array("TipoId"=>$TipoId,"Documento"=>$Documento,"Responsable"=>$Responsable,"cuenta"=>$cuenta));
		$this->salida .= "    <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->Encabezado();
		$this->salida .= "<table border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "<tr><td class=\"modulo_list_oscuro\">";
    $this->salida .= "      <br><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
		$Nombres=$this->BuscarNombresPaciente($TipoId,$Documento);
		$Apellidos=$this->BuscarApellidosPaciente($TipoId,$Documento);
		$FechaNacimiento=$this->Edad($TipoId,$Documento);
		$EdadArr=CalcularEdad($FechaNacimiento,$FechaFin);
    $NombreResponsable=$this->Responsable($Responsable);
		$NombrePlan=$this->PlanNombre($Responsable);
		if($_SESSION['CIRUGIAS']['ACTO']['CODIGO']){
      $datosPaciente=$this->SacaDatosPacienteCumplimiento($_SESSION['CIRUGIAS']['ACTO']['CODIGO']);
      $nombreCir=$this->NombreProfesional($datosPaciente['cirujano_id'],$datosPaciente['tipo_id_cirujano']);
			$diagnostico=$datosPaciente['diagnostico_nombre'];
			$valorboton='MODIFICAR';
			$clase='input-submit';
		}elseif($_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO']){
			$datosPaciente=$this->SacaDatosPacienteProgramQX($_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO'],$TipoId,$Documento);
			$nombreCir=$this->NombreProfesional($datosPaciente['cirujano_id'],$datosPaciente['tipo_id_cirujano']);
			$diagnostico=$datosPaciente['diagnostico_nombre'];
			$valorboton='CUMPLIR';
			$clase='label_error';
		}
		$this->salida .= "		    <tr>";
		$this->salida .= "		    <td class=\"label\"></td>";
		$this->salida .= "		    </tr>";
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
		$this->salida .= "		    <td>$Documento</td>";
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
    if(empty($consulta)){
		$this->salida .= "<td valign=\"bottom\" class=\"modulo_list_oscuro\">";
    $this->salida .= "       <input type=\"submit\" name=\"seleccionPac\" value=\"$valorboton\" class=\"$clase\">";
    $this->salida .= "</td>";
		}
		$this->salida .= "</tr>";

		$this->salida .= "<tr><td class=\"modulo_list_claro\">";
		if(!$_SESSION['CIRUGIAS']['ACTO']['CODIGO']){
      $valorboton='CUMPLIR';
			$clase='label_error';
			$desabiliboton='disabled';
			$datos=$this->obtenerDatosProgramacionQX();
		}elseif(!$_SESSION['CIRUGIAS']['ACTO']['QUIROFANO'] && $_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO']){
      $datos=$this->obtenerDatosProgramacionQX();
			$valorboton='CUMPLIR';
			$clase='label_error';
		}elseif($_SESSION['CIRUGIAS']['ACTO']['QUIROFANO']){
      $datos=$this->obtenerDatosCumplimiento();
			$valorboton='MODIFICAR';
			$clase='input-submit';
		}
		if($datos){
      $Sala=$datos['quirofano_id'];
			$sala=$this->DescripcionQuirofano($Sala);
			$decripsala=$sala['abreviatura'].' / '.$sala['descripcion'];
			$FechaProgramIni=$datos['hora_inicio'];
			$FechaProgramFin=$datos['hora_fin'];
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
      $DuracionMin=(mktime($HoraFin,$MinutosFin,0,$mesFin,$diaFin,$anoFin)-mktime($HoraIni,$MinutosIni,0,$mesIni,$diaIni,$anoIni))/60;
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
		$datosEquipos=$this->SeleccionEquiposProgramacion();
		if($datosEquipos){
		$this->salida .= "       <table border=\"0\" width=\"95%\" align=\"center\">";
		for($i=0;$i<sizeof($datosEquipos);$i++){
		  if(!$_SESSION['CIRUGIAS']['ACTO']['QUIROFANO']){
        if($datosEquipos[$i]['programado']){
          $this->salida .= "		    <tr class=\"modulo_list_claro\"><td>".$datosEquipos[$i]['descripcion']."</td></tr>";
				}
			}else{
        if($datosEquipos[$i]['cumplido']){
          $this->salida .= "		    <tr class=\"modulo_list_claro\"><td>".$datosEquipos[$i]['descripcion']."</td></tr>";
				}
			}
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
		if(empty($consulta)){
    $this->salida .= "</td>";
		$this->salida .= "<td valign=\"bottom\" class=\"modulo_list_claro\">";
    $this->salida .= "       <input type=\"submit\" name=\"reservar\" value=\"$valorboton\" class=\"$clase\" $desabiliboton>";
    $this->salida .= "</td>";
		}
		$this->salida .= "</tr>";
		$this->salida .= "<tr><td class=\"modulo_list_oscuro\">";
		$this->salida .= "      <br><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
		if(!$_SESSION['CIRUGIAS']['ACTO']['CODIGO']){
      $valorboton='CUMPLIR';
			$clase='label_error';
			$desabiliboton='disabled';
			$datos=$this->obtenerDatosAnestesiologoQX($_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO']);
      $nombreAn=$this->NombreProfesional($datos['tercero_id'],$datos['tipo_id_tercero']);
			$nombreIn=$this->NombreProfesional($datos['instrumentista_id'],$datos['tipo_id_instrumentista']);
			$nombreCi=$this->NombreProfesional($datos['circulante_id'],$datos['tipo_id_circulante']);
		}elseif(!$_SESSION['CIRUGIAS']['ACTO']['PROFESIONALES'] && $_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO']){
      $datos=$this->obtenerDatosAnestesiologoQX($_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO']);
      $nombreAn=$this->NombreProfesional($datos['tercero_id'],$datos['tipo_id_tercero']);
			$nombreIn=$this->NombreProfesional($datos['instrumentista_id'],$datos['tipo_id_instrumentista']);
			$nombreCi=$this->NombreProfesional($datos['circulante_id'],$datos['tipo_id_circulante']);
			$valorboton='CUMPLIR';
			$clase='label_error';
		}elseif($_SESSION['CIRUGIAS']['ACTO']['PROFESIONALES']){
      $datos=$this->DatosAnestesiologoCumplimiento($_SESSION['CIRUGIAS']['ACTO']['PROFESIONALES']);
      $nombreAn=$this->NombreProfesional($datos['anestesiologo_id'],$datos['tipo_id_anestesiologo']);
			$nombreIn=$this->NombreProfesional($datos['instrumentista_id'],$datos['tipo_id_instrumentista']);
			$nombreCi=$this->NombreProfesional($datos['circulante_id'],$datos['tipo_id_circulante']);
			$valorboton='MODIFICAR';
			$clase='input-submit';
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
		$this->salida .= "		    <td colspan=\"4\">ANESTESIOLOGO Y CITA PREANESTESICA</td>";
		$this->salida .= "		    </tr>";
		$this->salida .= "		    <tr class=\"modulo_list_claro\">";
		$this->salida .= "		    <td width=\"17%\" class=\"label\">ANESTESIOLOGO</td>";
		$this->salida .= "		    <td colspan=\"3\">".$nombreAn['nombre']."</td>";
		$this->salida .= "		    </tr>";
		$this->salida .= "		    <tr>";
		$this->salida .= "		    <td colspan=\"2\">&nbsp;</td>";
		$this->salida .= "		    </tr>";
    $this->salida .= "		    <tr class=\"modulo_list_claro\">";
		$this->salida .= "		    <td width=\"15%\" class=\"label\">FECHA Y HORA CITA</td>";
		$this->salida .= "		    <td colspan=\"3\">".$datos['fecha_turno']."&nbsp&nbsp&nbsp&nbsp;".$datos['hora']."</td>";
		$this->salida .= "		    </tr>";
		$this->salida .= "		    <tr class=\"modulo_list_claro\">";
		$this->salida .= "		    <td width=\"15%\" class=\"label\">PROFESIONAL ASIGNADO</td>";
		$nombrePr=$this->NombreProfesional($datos['profesional_id'],$datos['tipo_id_profesional']);
		$this->salida .= "		    <td colspan=\"3\">".$nombrePr['nombre']."</td>";
		$this->salida .= "		    </tr>";
		$this->salida .= "		    <tr class=\"modulo_list_claro\">";
		$this->salida .= "		    <td width=\"15%\" class=\"label\">CONSULTORIO</td>";
		$this->salida .= "		    <td colspan=\"3\">".$datos['consultorio_id']."</td>";
		$this->salida .= "		    </tr>";
		$this->salida .= "		    <tr><td></td></tr>";
		$this->salida .= "		    </tr>";
		$this->salida .= "		</table><br>";
    $this->salida .= "</td>";
		if(empty($consulta)){
		$this->salida .= "<td valign=\"bottom\" class=\"modulo_list_oscuro\">";
    $this->salida .= "       <input type=\"submit\" name=\"Selprofesionales\" value=\"$valorboton\" class=\"$clase\" $desabiliboton>";
    $this->salida .= "</td>";
		}
		$this->salida .= "</tr>";
		$this->salida .= "<tr><td class=\"modulo_list_claro\">";
		$this->salida .= "    <br><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";

    if($_SESSION['CIRUGIAS']['ACTO']['CODIGO'] && $_SESSION['CIRUGIAS']['ACTO']['DATOSPROCEDIMIENTOS']){
			$datosQX=$this->DatosProgramacionCumplimiento($_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO']);
		}elseif($_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO']){
			$datosQX=$this->DatosProgramacionQX($_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO']);
		}
		$this->salida .= "		 <tr class=\"modulo_table_list_title\">";
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
    $this->salida .= "         </table><BR>";
		$this->salida .= "		  </td>";
    $this->salida .= "		 </tr>";

		if(!$_SESSION['CIRUGIAS']['ACTO']['CODIGO']){
      $valorboton='CUMPLIR';
			$clase='label_error';
			$desabiliboton='disabled';
      $procedimientos=$this->BusquedaProcedimientosProgram($_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO']);
		}elseif(!$_SESSION['CIRUGIAS']['ACTO']['PROCEDIMIENTOS'] && $_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO']){
      $procedimientos=$this->BusquedaProcedimientosProgram($_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO']);
			$valorboton='CUMPLIR';
			$clase='label_error';
		}elseif($_SESSION['CIRUGIAS']['ACTO']['PROCEDIMIENTOS']){
      $procedimientos=$this->BusquedaProcedimientosCumplimiento();
			$valorboton='MODIFICAR';
			$clase='input-submit';
		}
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
		$this->salida .= "		      <tr class=\"modulo_list_claro\">";
		$nombreAn=$this->NombreProfesional($procedimientos[$i]['ayudante_id'],$procedimientos[$i]['tipo_id_ayudante']);
    $this->salida .= "		      <td width=\"15%\" class=\"label\">AYUDANTE</td>";
		$this->salida .= "		      <td colspan=\"3\">".$nombreAn['nombre']."</td>";
    $this->salida .= "		      </tr>";
		if($procedimientos[$i]['pediatra_id'] && $procedimientos[$i]['tipo_id_pediatra']){
			$this->salida .= "		      <tr class=\"modulo_list_claro\">";
			$nombrePe=$this->NombreProfesional($procedimientos[$i]['pediatra_id'],$procedimientos[$i]['tipo_id_pediatra']);
			$this->salida .= "		      <td width=\"15%\" class=\"label\">PEDIATRA</td>";
			$this->salida .= "		      <td colspan=\"3\">".$nombrePe['nombre']."</td>";
			$this->salida .= "		      </tr>";
		}
		if($procedimientos[$i]['via_procedimiento_bilateral']){
			$this->salida .= "		      <tr class=\"modulo_list_claro\">";
			$this->salida .= "		      <td width=\"15%\" class=\"label\">VIA ACCESO</td>";
			$this->salida .= "		      <td colspan=\"3\">".$procedimientos[$i]['nomvia']."</td>";
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
		if(empty($consulta)){
		$this->salida .= "<td valign=\"bottom\" class=\"modulo_list_oscuro\">";
    $this->salida .= "       <input type=\"submit\" name=\"procedimientosSelec\" value=\"$valorboton\" class=\"$clase\" $desabiliboton>";
    $this->salida .= "</td>";
		}

		$this->salida .= "</td></tr>";
		$this->salida .= "<tr><td class=\"modulo_list_oscuro\">";
		$this->salida .= "      <br><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
		if(!$_SESSION['CIRUGIAS']['ACTO']['CODIGO']){
      $valorboton='CUMPLIR';
			$desabiliboton='disabled';
			$clase='label_error';
			$paquetesIns=$this->paquetesInsertadosRequeridos();
		}elseif(!$_SESSION['CIRUGIAS']['ACTO']['PAQUETES'] && $_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO']){
      $paquetesIns=$this->paquetesInsertadosRequeridos();
			$valorboton='CUMPLIR';
			$clase='label_error';
		}elseif($_SESSION['CIRUGIAS']['ACTO']['PAQUETES']){
      $paquetesIns=$this->paquetesCumplimientoRequeridos();
			$valorboton='MODIFICAR';
			$clase='input-submit';
		}
		$this->salida .= "		  <tr>";
		$this->salida .= "		  <td class=\"label\"></td>";
		$this->salida .= "		  </tr>";
		$this->salida .= "		  <tr class=\"modulo_table_list_title\">";
		$this->salida .= "		  <td>PAQUETES DE INSUMOS REQUERIDOS</td>";
		$this->salida .= "		  </tr>";
		$this->salida .= "			<tr class=\"modulo_list_claro\">";
		$this->salida .= "			<td>";
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
		if(empty($consulta)){
		$this->salida .= "<td valign=\"bottom\" class=\"modulo_list_oscuro\">";
    $this->salida .= "       <input type=\"submit\" name=\"paquetes_insumos\" value=\"$valorboton\" class=\"$clase\" $desabiliboton>";
    $this->salida .= "</td>";
		}
		$this->salida .= "</tr>";

		$this->salida .= "<tr><td class=\"modulo_list_claro\">";
		$this->salida .= "      <br><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
		if(!$_SESSION['CIRUGIAS']['ACTO']['CODIGO']){
      $valorboton='CUMPLIR';
			$desabiliboton='disabled';
			$clase='label_error';
			$Insumos=$this->InsumosInsertadosRequeridos();
		}elseif(!$_SESSION['CIRUGIAS']['ACTO']['INSUMOS'] && $_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO']){
      $Insumos=$this->InsumosInsertadosRequeridos();
			$valorboton='CUMPLIR';
			$clase='label_error';
		}elseif($_SESSION['CIRUGIAS']['ACTO']['INSUMOS']){
      $Insumos=$this->InsumosInsertadosCumplimiento();
			$valorboton='MODIFICAR';
			$clase='input-submit';
		}
		$this->salida .= "		  <tr>";
		$this->salida .= "		  <td class=\"label\"></td>";
		$this->salida .= "		  </tr>";
		$this->salida .= "		  <tr class=\"modulo_table_list_title\">";
		$this->salida .= "		  <td>INSUMOS Y MATERIAL DE OSTEOSINTESIS REQUERIDOS</td>";
		$this->salida .= "		  </tr>";
		$this->salida .= "			<tr class=\"modulo_list_oscuro\">";
		$this->salida .= "			<td>";
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
		if(empty($consulta)){
		$this->salida .= "<td valign=\"bottom\" class=\"modulo_list_claro\">";
    $this->salida .= "       <input type=\"submit\" name=\"insumos\" value=\"$valorboton\" class=\"$clase\" $desabiliboton>";
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
		$datosSangre=$this->SacarDatosReservaSangre($TipoId,$Documento);
		if($datosSangre){
			$this->salida .= "      <br><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
			$salida1 ="	    <tr class=\"modulo_table_list_title\">";
			$salida1.="		  <td width=\"5%\">FACTOR</td>";
			$salida1.="		  <td width=\"5%\">RH</td>";
			$salida1.="		  <td>FECHA RESERVA</td>";
			$salida2 ="		  <tr class=\"modulo_list_oscuro\">";
			$salida2.="		  <td>".$datosSangre['grupo_sanguineo']."</td>";
			$salida2.="		  <td>".$datosSangre['rh']."</td>";
			$salida2.="		  <td>".$datosSangre['fecha_hora_reserva']."</td>";
			$comp=$this->ConsultaComponente($datosSangre['hc_reserva_sangre_id']);
			$i=0;
			while($i<sizeof($comp)){
			  $salida1.="   <td>".$comp[$i]['componente']."</td>";
				$salida2.="  <td>".$comp[$i]['hc_tipo_componente']."</td>";
        $i++;
			}
			$salida1.="		 </tr>";
			$salida2.="	 </tr>";
			$this->salida .= $salida1;
			$this->salida .= $salida2;
			$this->salida .= "</table><br>";
		}
		$this->salida .= "		  </td>";
		$this->salida .= "		  </tr>";
		$this->salida .= "		  </table><BR>";
		$this->salida .= "</td>";
    $this->salida .= "      <input type=\"hidden\" name=\"tipoIdPac\" value=\"".$datosPaciente['tipo_id_paciente']."\">";
		$this->salida .= "      <input type=\"hidden\" name=\"PacienteId\" value=\"".$datosPaciente['paciente_id']."\">";
		if(empty($consulta)){
		$this->salida .= "<td valign=\"bottom\" class=\"modulo_list_oscuro\">";
    $this->salida .= "       <input type=\"submit\" name=\"sangre\" value=\"CONSULTAR\" class=\"input-submit\">";
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
		if(empty($consulta)){
		$this->salida .= "<td valign=\"bottom\" class=\"modulo_list_claro\">";
		$this->salida .= "       <input type=\"hidden\" name=\"FechaProgramFin\" value=\"$FechaProgramFin\">";
    $this->salida .= "       <input type=\"submit\" name=\"reservaCama\" value=\"VER\" class=\"input-submit\" $desabilitado>";
    $this->salida .= "</td>";
		}*/
    /*$this->salida .= "<tr><td class=\"modulo_list_oscuro\">";
		$this->salida .= "      <br><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "		  <tr>";
		$this->salida .= "		  <td class=\"label\"></td>";
		$this->salida .= "		  </tr>";
		$this->salida .= "		  <tr class=\"modulo_table_list_title\">";
		$this->salida .= "		  <td colspan=\"4\">CONFIRMACION DEL CONSENTIMIENTO DE LA CIRUGIA</td>";
		$this->salida .= "		  </tr>";
		//$consentimientos=$this->ConsentimientosdelPacienteQX();
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
    if(empty($consulta)){
		$this->salida .= "<td valign=\"bottom\" class=\"modulo_list_oscuro\">";
    $this->salida .= "       <input type=\"submit\" name=\"consentimiento\" value=\"SELECCION\" class=\"input-submit\" $desabilitado>";
    $this->salida .= "</td>";
		}
		$this->salida .= "</tr>";
		*/
		$this->salida .= "<tr><td align=\"center\">";
		$this->salida .= "       <input type=\"submit\" name=\"Salir\" value=\"SALIR\" class=\"input-submit\">&nbsp&nbsp&nbsp;";
    if($ProgramacionId){
			//$arr=$this->LlamaImprimirProgramacionQX();
			if(!empty($arr)){
				IncludeLib("reportes/programacion_qx");
				GenerarReporteProgramacionQX($arr);
  		$this->salida .= "    <input class=\"input-submit\" name=\"Cancelar\" type=\"button\" value=\"IMPRIMIR\" onclick=\"javascript:abreVentana()\">";
  	}
		}
		$this->salida .= "</td></tr>";

		$this->salida .= "</table><br>";
		$this->salida .= "		  </form>";
		unset($_SESSION['PACIENTES']);
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
	function FormaDatosPrincipales($TipoId,$Documento,$Responsable,$cuenta)
	{
	  $mostrar=ReturnClassBuscador('diagnostico','','','forma');
    $this->salida .= ThemeAbrirTabla('SELECCION DATOS PACIENTE Y PROFESIONAL PRINCIPAL');
		$this->salida.=$mostrar;
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
		$action=ModuloGetURL('app','QXEjecucion','user','GuardarDatosCumplimiento',array("TipoId"=>$TipoId,"Documento"=>$Documento,"Responsable"=>$Responsable,"cuenta"=>$cuenta));
		$this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->Encabezado();
		$Nombres=$this->BuscarNombresPaciente($TipoId,$Documento);
		$Apellidos=$this->BuscarApellidosPaciente($TipoId,$Documento);
		$FechaNacimiento=$this->Edad($TipoId,$Documento);
		$EdadArr=CalcularEdad($FechaNacimiento,$FechaFin);
    $NombreResponsable=$this->Responsable($Responsable);
		$NombrePlan=$this->PlanNombre($Responsable);
		if($_SESSION['CIRUGIAS']['ACTO']['CODIGO']){
		  $datosPaciente=$this->SacaDatosPacienteCumplimiento($_SESSION['CIRUGIAS']['ACTO']['CODIGO']);
			$cirujano=$datosPaciente['cirujano_id'].'/'.$datosPaciente['tipo_id_cirujano'];
			$cargo=$datosPaciente['diagnostico_nombre'];
			$codigo=$datosPaciente['diagnostico_id'];
			$valor='disabled';
		}elseif($_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO']){
		  $datosPaciente=$this->SacaDatosPacienteProgramQX($_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO']);
		  $cirujano=$datosPaciente['cirujano_id'].'/'.$datosPaciente['tipo_id_cirujano'];
      $cargo=$datosPaciente['diagnostico_nombre'];
			$codigo=$datosPaciente['diagnostico_id'];
			$valor='disabled';
		}
		$this->salida .= "<table width=\"85%\" align=\"center\" border=\"0\">\n";
		$this->salida .= "<tr><td colspan=\"4\" align=\"center\">";
		$this->salida .=  $this->SetStyle("MensajeError");
		$this->salida .= "</td></tr>";
		$this->salida .= "<tr><td class=\"modulo_list_oscuro\">";
		$this->salida .= "   <BR><table width=\"90%\" border=\"0\" align=\"center\" class=\"normal_10\" >";
    $this->salida .= "		<tr class=\"modulo_table_list_title\"><td colspan=\"2\" class=\"modulo_table_list_title\">DATOS DEL PACIENTE</td></tr>";
		$this->salida .= "		<tr class=\"modulo_list_claro\"><td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"TipoDocumento\" class=\"select\" $valor>";
		$tipo_id=$this->tipo_id_paciente();
		$this->Mostrar($tipo_id,'False',$TipoId);
		$this->salida .= "    </select></td></tr>";
		$this->salida .= "		<tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("Documento")."\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" value=\"$Documento\" $valor></td></tr>";
		$this->salida .= "		<tr class=\"modulo_list_claro\" height=\"20\"><td class=\"".$this->SetStyle("Responsable")."\">PLAN: </td><td><select name=\"Responsable\"  class=\"select\" $valor>";
		$responsables=$this->CallMetodoExterno('app','Triage','user','responsables');
		$this->MostrarResponsable($responsables,$Responsable);
		$this->salida .= "    </select></td></tr>";
		$this->salida .= "		 </table><BR>";
		$this->salida .= "</td></tr>";
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
		$this->salida.= "<input type=\"button\" name=\"buscar\" value=\"BUSCAR\" onclick=abrirVentana() class=\"input-submit\"></td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table><BR>";
		$this->salida .= "</td></tr>";
		$this->salida .= "</td></tr>";
		$this->salida .= "</table><BR>\n";
		$this->salida .= "</td></tr>\n";
    $this->salida .= "</table>";
		$this->salida .= "   <table width=\"90%\" border=\"0\" align=\"center\"class=\"normal_10\" >";
    $this->salida .= "		<tr><td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"GUARDAR\">";
		$this->salida .= "	 <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
		$this->salida .= "		</td></tr>";
    $this->salida .= "</table>";
		$this->salida .= "</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
/**
* Funcion que visualiza los datos requeridos del anestesiologo
* @return boolean
*/
	function SeleccionProfesionalesPx($TipoId,$Documento,$Responsable,$cuenta){
		$this->salida .= ThemeAbrirTabla('DATOS Y REQUERIMIENTOS DE PROFESIONALES');
		$accion=ModuloGetURL('app','QXEjecucion','user','ValidacionProfesionalesQx',array("TipoId"=>$TipoId,"Documento"=>$Documento,"Responsable"=>$Responsable,"cuenta"=>$cuenta));
		$this->salida .= "    <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->Encabezado();
    $this->salida .= "   <table border=\"0\" width=\"60%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "     <tr><td></td></tr>";
		$this->salida .= "      <tr><td><fieldset><legend class=\"field\">Datos</legend>";
		$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
		if($_SESSION['CIRUGIAS']['ACTO']['CODIGO'] && $_SESSION['CIRUGIAS']['ACTO']['PROFESIONAL']){
      $datos=$this->DatosAnestesiologoCumplimiento();
      $anestesista=$datos['anestesiologo_id'].'/'.$datos['tipo_id_anestesiologo'];
			$instrumentista=$datos['instrumentista_id'].'/'.$datos['tipo_id_instrumentista'];
			$circulante=$datos['circulante_id'].'/'.$datos['tipo_id_circulante'];
		}elseif($_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO']){
			$datos=$this->obtenerDatosAnestesiologoQX($_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO']);
      $anestesista=$datos['tercero_id'].'/'.$datos['tipo_id_tercero'];
			$instrumentista=$datos['instrumentista_id'].'/'.$datos['tipo_id_instrumentista'];
			$circulante=$datos['circulante_id'].'/'.$datos['tipo_id_circulante'];
		}
		$this->salida .= "      <tr><td colspan=\"4\" align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "      </td></tr>";
		$this->salida .= "			  <tr><td class=\"".$this->SetStyle("instrumentista")."\">INSTRUMENTADOR(A):</td><td><select name=\"instrumentista\" class=\"select\">";
	  $instrumentistas=$this->profesionalesEspecialistaInstrumentistas();
	  $this->BuscarProfesionlesEspecialistas($instrumentistas,'False',$instrumentista);
	  $this->salida .= "       </select></td></tr>";
		$this->salida .= "			  <tr><td class=\"".$this->SetStyle("circulante")."\">CIRCULANTE:</td><td><select name=\"circulante\" class=\"select\">";
	  $ciruculantes=$this->profesionalesEspecialistaCiculantes();
	  $this->BuscarProfesionlesEspecialistas($ciruculantes,'False',$circulante);
	  $this->salida .= "       </select></td></tr>";
		$this->salida .= "			  <tr><td class=\"".$this->SetStyle("anestesista")."\">ANESTESIOLOGO:</td><td><select name=\"anestesista\" class=\"select\">";
	  $profesionales=$this->profesionalesEspecialistaAnestecistas();
	  $this->BuscarProfesionlesEspecialistas($profesionales,'False',$anestesista);
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

	function Reserva_Paquetes_Insumos_qx($TipoId,$Documento,$Responsable,$cuenta,$bandera,$cadena){

		$ProgramacionId=$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO'];
		$this->salida .= ThemeAbrirTabla('INSUMOS REQUERIDOS CIRUGIA');
		$accion=ModuloGetURL('app','QXEjecucion','user','InsertarReqPaqutes',array("TipoId"=>$TipoId,"Documento"=>$Documento,"Responsable"=>$Responsable,"cuenta"=>$cuenta));
		$this->Encabezado();
		$this->salida .= "    <form name=\"forma\" action=\"$accion\" method=\"post\">";
    if($_SESSION['CIRUGIAS']['ACTO']['CODIGO'] && $_SESSION['CIRUGIAS']['ACTO']['PAQUETES']){
			$paquetesIns=$this->paquetesCumplimientoRequeridos();
		}elseif($_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO']){
      $paquetesIns=$this->paquetesInsertadosRequeridos();
			$indica=1;
		}
		if($paquetesIns){
			$this->salida .= "    <BR><table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">";
			$this->salida .= "		<td colspan=\"3\">PAQUETES REQUERIDOS INSERTADOS</td>";
			$this->salida .= "		</tr>";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">";
			$this->salida .= "		<td>PAQUETE</td>";
			$this->salida .= "		<td width=\"5%\">CANTIDAD</td>";
			$this->salida .= "		<td width=\"5%\">&nbsp;</td>";
			$this->salida .= "		</tr>";
			for($i=0;$i<sizeof($paquetesIns);$i++){
				if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
				$this->salida .= "		<tr class=\"$estilo\">";
				$this->salida .= "		<td>".$paquetesIns[$i]['descripcion']."</td>";
				if($indica!=1){
				  $this->salida .= "		<td>".$paquetesIns[$i]['cantidad']."</td>";
					$actionEliminar=ModuloGetURL('app','QXEjecucion','user','EliminarPauqeteInsertado',array("paquete"=>$paquetesIns[$i]['paquete_insumos_id'],"TipoId"=>$TipoId,"Documento"=>$Documento,"Responsable"=>$Responsable,"cuenta"=>$cuenta));
					$this->salida .= "		<td><a href=\"$actionEliminar\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"><a></td>";
				}else{
				  $this->salida .= "		<td><input type=\"text\" name=\"cantidadpaq[".$paquetesIns[$i]['paquete_insumos_id']."]\" value=\"".$paquetesIns[$i]['cantidad']."\"></td>";
          $this->salida .= "		<td><input type=\"checkbox\" checked name=\"paquetes[]\" value=\"".$paquetesIns[$i]['paquete_insumos_id']."\"></td>";
				}
				$this->salida .= "		</tr>";
			}
			$this->salida .= "    </table><BR>";
		}
		if($indica==1){
		$this->salida .= "    <table border=\"0\" width=\"70%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "    <tr>";
		$this->salida .= "    <td align=\"right\">";
		$this->salida .= "    <input type=\"submit\" class=\"input-submit\" name=\"GuardarCant\" value=\"GUARDAR CANTIDAD\">";
		$this->salida .= "    </td></tr>";
		$this->salida .= "    </table><BR>";
		}
    if(!$bandera){
		$this->salida .= "    <table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "    <tr>";
		$this->salida .= "    <td align=\"left\">";
		$actionBusqueda=ModuloGetURL('app','QXEjecucion','user','BusquedaNuevoPaqueteInsumos',array("TipoId"=>$TipoId,"Documento"=>$Documento,"Responsable"=>$Responsable,"cuenta"=>$cuenta));
		$this->salida .= "  <a href=\"$actionBusqueda\" class=\"link\"><b>BUSCAR OTRO PAQUETE</b></a>";
		$this->salida .= "    </td>";
		$this->salida .= "    </tr>";
		$this->salida .= "    </table>";
		}
		if($bandera){
			$this->salida .= "    <table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">";
			$this->salida .= "		<td>BUSQUEDA DE OTRO PAQUETE</td>";
			$this->salida .= "		</tr>";
			$this->salida .= "    <tr class=\"modulo_list_claro\"><td valign=\"bottom\" align=\"center\">";
			$this->salida .= "    <input size=\"80\" type=\"text\" class=\"input-text\" name=\"nombuscar\">&nbsp&nbsp&nbsp;";
			$this->salida .= "    <input type=\"submit\" class=\"input-submit\" value=\"BUSCAR\" name=\"buscar\">";
			$this->salida .= "    </td></tr>";
			$this->salida .= "    </table><BR>";
			if($cadena){
				$paquetesNuv=$this->buscarPaquetesNuevos($cadena);
				if($paquetesNuv){
					$this->salida .= "    <BR><table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
					$this->salida .= "		<tr class=\"modulo_table_list_title\">";
					$this->salida .= "		<td colspan=\"4\">PAQUETES DE INSUMOS ENCONTRADOS</td>";
					$this->salida .= "		</tr>";
					$this->salida .= "		<tr class=\"modulo_table_list_title\">";
					$this->salida .= "		<td>PAQUETE</td>";
					$this->salida .= "		<td>INSUMOS</td>";
					$this->salida .= "		<td>CANTIDAD REQUERIDA</td>";
					$this->salida .= "		<td>&nbsp;</td>";
					$this->salida .= "		</tr>";
					for($i=0;$i<sizeof($paquetesNuv);$i++){
						if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
						if($y % 2){$estilo1='modulo_list_oscuro';}else{$estilo1='modulo_list_claro';}
						$this->salida .= "		<tr class=\"$estilo\">";
						$this->salida .= "		<td>".$paquetesNuv[$i]['descripcion']."</td>";
						$this->salida .= "		<td>";
						$insumosPaquete=$this->BuscarInsumosPaquete($paquetesNuv[$i]['paquete_insumos_id']);
						if($insumosPaquete){
							$this->salida .= "    	<table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
							for($z=0;$z<sizeof($insumosPaquete);$z++){
							$this->salida .= "			<tr class=\"$estilo1\">";
							$this->salida .= "			<td>".$insumosPaquete[$z]['insumo']."</td>";
							$this->salida .= "			<td width=\"15%\">".$insumosPaquete[$z]['cantidad']."</td>";
							$this->salida .= "			</tr>";
							}
							$this->salida .= "    	</table>";
						}
						$this->salida .= "		</td>";
						$this->salida .= "		<td width=\"5%\"><input class=\"input-text\" type=\"text\" name=\"cantidad_paqDos[".$paquetesNuv[$i]['paquete_insumos_id']."]\" size=\"8\"></td>";
						$this->salida .= "		<td width=\"5%\" align=\"center\"><input type=\"checkbox\" value=\"".$paquetesNuv[$i]['paquete_insumos_id']."\" name=\"seleccionDos[]\"></td>";
						$this->salida .= "		<tr>";
					}
					$this->salida .= "    	</table><BR>";
					$this->salida .= "    <table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
					$this->salida .= "    <tr>";
					$this->salida .= "    <td align=\"right\">";
					$this->salida .= "    <input type=\"submit\" class=\"input-submit\" name=\"GuardarDos\" value=\"GUARDAR CANTIDAD\">";
					$this->salida .= "    </td></tr>";
					$this->salida .= "    </table><BR>";
				}
			}
		}
		$this->salida .= "    <table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "    <tr>";
		$this->salida .= "    <td align=\"center\">";
		$this->salida .= "    <input type=\"submit\" class=\"input-submit\" name=\"Cancelar\" value=\"SALIR\">";
		$this->salida .= "    </td></tr>";
		$this->salida .= "    </table>";
		$this->salida .= "		</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function Reserva_Insumos_qx($TipoId,$Documento,$Responsable,$cuenta,$grupo,$clasePr,$subclase,$codigoPro,$descripcionPro,$bandera){

		$ProgramacionId=$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO'];
		$this->salida .= ThemeAbrirTabla('INSUMOS Y MATERIAL DE OSTEOSINTESIS REQUERIDOS PARA LA CIRUGIA');
		$accion=ModuloGetURL('app','QXEjecucion','user','InsertarInsumosQX',array("TipoId"=>$TipoId,"Documento"=>$Documento,"Responsable"=>$Responsable,"cuenta"=>$cuenta,"conteo"=>$this->conteo,"paso"=>$_REQUEST['paso'],"Of"=>$_REQUEST['Of'],
		"TipoId"=>$TipoId,"Documento"=>$Documento,"Responsable"=>$Responsable,"cuenta"=>$cuenta));
		$this->Encabezado();
		$this->salida .= "    <form name=\"forma\" action=\"$accion\" method=\"post\">";
		if($_SESSION['CIRUGIAS']['ACTO']['CODIGO'] && $_SESSION['CIRUGIAS']['ACTO']['INSUMOS']){
			$InsumosQX=$this->InsumosInsertadosCumplimiento();
		}elseif($_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO']){
      $InsumosQX=$this->InsumosInsertadosRequeridos();
			$indica=1;
		}
		if($InsumosQX){
			$this->salida .= "    <BR><table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
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
				if($indica!=1){
				  $this->salida .= "		<td>".$InsumosQX[$i]['cantidad']."</td>";
					$actionEliminar=ModuloGetURL('app','QXEjecucion','user','EliminarInsumoQXInsertado',array("TipoId"=>$TipoId,"Documento"=>$Documento,"Responsable"=>$Responsable,"cuenta"=>$cuenta,
					"insumo"=>$InsumosQX[$i]['codigo_producto'],"grupo"=>$grupo,"clasePr"=>$clasePr,"subclase"=>$subclase,"codigoPro"=>$codigoPro,"descripcionPro"=>$descripcionPro));
				  $this->salida .= "		<td><a href=\"$actionEliminar\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"><a></td>";
				}else{
          $this->salida .= "		<td><input type=\"text\" name=\"cantidadInsumos[".$InsumosQX[$i]['codigo_producto']."]\" value=\"".$InsumosQX[$i]['cantidad']."\"></td>";
          $this->salida .= "		<td><input type=\"checkbox\" checked name=\"insumos[]\" value=\"".$InsumosQX[$i]['codigo_producto']."\"></td>";
				}
				$this->salida .= "		</tr>";
			}
			$this->salida .= "    </table><BR>";
		}
		if($indica==1){
		$this->salida .= "    <table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "    <tr>";
		$this->salida .= "    <td align=\"right\">";
		$this->salida .= "    <input type=\"submit\" class=\"input-submit\" name=\"GuardarCant\" value=\"GUARDAR CANTIDAD\">";
		$this->salida .= "    </td></tr>";
		$this->salida .= "    </table><BR>";
		}
		if(!$bandera){
		$this->salida .= "    <table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "    <tr>";
		$this->salida .= "    <td align=\"left\">";
		$actionBusqueda=ModuloGetURL('app','QXEjecucion','user','BusquedaNuevoInsumos',array("TipoId"=>$TipoId,"Documento"=>$Documento,"Responsable"=>$Responsable,"cuenta"=>$cuenta));
		$this->salida .= "  <a href=\"$actionBusqueda\" class=\"link\"><b>BUSCAR NUEVO INSUMO</b></a>";
		$this->salida .= "    </td>";
		$this->salida .= "    </tr>";
		$this->salida .= "    </table>";
		}
		if($bandera){
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
			$this->salida .= "		    <td width=\"5%\"><input class=\"input-text\" type=\"text\" value=\"$cantidad\" name=\"cantidadInsumosUno[".$TotalInventario[$i]['codigo_producto']."]\" size=\"8\"></td>";
			$this->salida .= "		    <td width=\"5%\" align=\"center\"><input type=\"checkbox\" value=\"".$TotalInventario[$i]['codigo_producto']."\" name=\"seleccion[]\" $varchecked></td>";
			$this->salida .= "      </tr>";
			$y++;
		}
		$this->salida .="          </table>";
		$this->salida .= "        <table border=\"0\" width=\"85%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "        <tr><td align=\"right\">";
		$this->salida .= "        <input type=\"submit\" class=\"input-submit\" name=\"GuardarCantUno\" value=\"GUARDAR CANTIDAD\">";
		$this->salida .= "        </td></tr>";
		$this->salida .="         </table>";
		$this->salida .=$this->RetornarBarra(1);
		$this->salida .= "			   <BR>";
		}else{
      $this->salida .= "         <table class=\"normal_10N\"  border=\"0\" width=\"85%\" align=\"center\">";
		  $this->salida .= "         <tr><td  align=\"center\" class=\"label_error\">NO SE ENCONTRARON DATOS CON ESTOS PARAMETROS DE BUSQUEDA</td></tr>";
      $this->salida .= "			   </table><BR>";
		}
		}
		$this->salida .= "    <table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "    <tr>";
		$this->salida .= "    <td align=\"center\">";
		$this->salida .= "    <input type=\"submit\" class=\"input-submit\" name=\"Salir\" value=\"SALIR\">";
		//$this->salida .= "    <input type=\"submit\" class=\"input-submit\" name=\"SalirGuardar\" value=\"SALIR\">";
		$this->salida .= "    </td></tr>";
		$this->salida .= "    </table>";
		$this->salida .= "		</form>";
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
      $accion=ModuloGetURL('app','QXEjecucion','user','LlamaReserva_Insumos_qx',array('conteo'=>$this->conteo,"paso"=>$_REQUEST['paso'],"Of"=>$_REQUEST['Of'],"grupo"=>$_REQUEST['grupo'],"clasePr"=>$_REQUEST['clasePr'],"subclase"=>$_REQUEST['subclase'],"codigoPro"=>$_REQUEST['codigoPro'],"descripcionPro"=>$_REQUEST['descripcionPro'],"TipoId"=>$_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cuenta']));
		}elseif($origen==2){
		  $accion=ModuloGetURL('app','QXEjecucion','user','SeleccionProcedimientoQX',array('conteo'=>$this->conteo,"paso"=>$_REQUEST['paso'],"Of"=>$_REQUEST['Of'],"filtrar"=>1,"TipoDocumento"=>$_REQUEST['TipoDocumento'],"Documento"=>$_REQUEST['Documento'],"nombrePac"=>$_REQUEST['nombrePac'],"Responsable"=>$_REQUEST['Responsable'],"tipoAfil"=>$_REQUEST['tipoAfil'],"rango"=>$_REQUEST['rango'],"semanas"=>$_REQUEST['semanas'],"numeroCirujano"=>$_REQUEST['numeroCirujano'],"procedimientoBus"=>$_REQUEST['procedimientoBus'],"codigoBus"=>$_REQUEST['codigoBus'],"tipoProcedimiento"=>$_REQUEST['tipoProcedimiento']));
		}elseif($origen==3){
      $accion=ModuloGetURL('app','QXEjecucion','user','BuscadorProfesional',array('conteo'=>$this->conteo,"paso"=>$_REQUEST['paso'],"Of"=>$_REQUEST['Of'],"filtrar"=>1,"TipoDocumento"=>$_REQUEST['TipoDocumento'],"Documento"=>$_REQUEST['Documento'],"nombrePac"=>$_REQUEST['nombrePac'],"Responsable"=>$_REQUEST['Responsable'],"tipoAfil"=>$_REQUEST['tipoAfil'],"rango"=>$_REQUEST['rango'],"semanas"=>$_REQUEST['semanas'],"numeroCirujanoSelect"=>$_REQUEST['numeroCirujanoSelect'],"TipoDocumentoBus"=>$_REQUEST['TipoDocumentoBus'],"DocumentoBus"=>$_REQUEST['DocumentoBus'],"NomcirujanoBus"=>$_REQUEST['NomcirujanoBus']));
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
	function ProcedimientosQuirurgicos($TipoId,$Documento,$Responsable,$cuenta,$codigos,$procedimiento,$cirujano,$ayudante,$ResponsablePro,$numerorden,$pediatrico,$pediatra,$bilateral,$modificar){

    //if(!$cirujano,$ayudantedefecto)
		$ProgramacionId=$_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO'];
    $_ROOT='';
		GLOBAL $ADODB_FETCH_MODE;
		$vectorCargo=array();
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
		$this->salida.="</script>\n";
		$this->salida .= ThemeAbrirTabla('PROCEDIMIENTOS PROGRAMACION CIRUGIA');
		$this->salida .= "			<br><br>";
		$action=ModuloGetURL('app','QXEjecucion','user','InsercionDatosProgramCirugias',array("TipoId"=>$TipoId,"Documento"=>$Documento,"Responsable"=>$Responsable,"cuenta"=>$cuenta));
		$this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->salida.= "     <input type=\"hidden\" name=\"codigos\" value=\"$codigos\" READONLY>";
		$this->salida.= "     <input type=\"hidden\" name=\"procedimiento\" value=\"$procedimiento\" READONLY>";
		$this->salida.= "     <input type=\"hidden\" name=\"cirujano\" value=\"$cirujano\" READONLY>";
		$this->salida.= "     <input type=\"hidden\" name=\"ayudante\" value=\"$ayudante\" READONLY>";
		$this->Encabezado();
		if($_SESSION['CIRUGIAS']['ACTO']['CODIGO'] && $_SESSION['CIRUGIAS']['ACTO']['DATOSPROCEDIMIENTOS']){
			$datosQX=$this->DatosProgramacionCumplimiento($_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO']);
		}elseif($_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO']){
			$datosQX=$this->DatosProgramacionQX($_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO']);
		}
    if($_SESSION['CIRUGIAS']['ACTO']['CODIGO'] && $_SESSION['CIRUGIAS']['ACTO']['PROCEDIMIENTOS']){
		  $procedimientos=$this->BusquedaProcedimientosCumplimiento($_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO']);
		}elseif($_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO']){
      $procedimientos=$this->BusquedaProcedimientosProgram($_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO']);
			$datosQX=$this->DatosProgramacionQX($_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO']);
			$indica=1;
		}
		$this->salida .= "    <br><table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "		 <tr class=\"modulo_table_list_title\">";
    $this->salida .= "		 <td>DATOS CIRUGIA</td>";
    $this->salida .= "		 </tr>";
		$this->salida .= "		 <tr class=\"modulo_list_oscuro\">";
    $this->salida .= "		 <td>";
		$this->salida .= "      <BR><table border=\"0\" width=\"92%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "			  <tr class=\"modulo_list_claro\"><td width=\"15%\" class=\"".$this->SetStyle("viaAcceso")."\">VIA ACCESO:</td><td><select name=\"viaAcceso\" class=\"select\">";
		$viaAcceso=$datosQX['via_acceso'];
	  $accesos=$this->ViaAccesosCirugia();
	  $this->MostrasSelect($accesos,'False',$viaAcceso);
	  $this->salida .= "        </select></td>";
		$this->salida.= "         </tr>";
		$this->salida .= "			  <tr class=\"modulo_list_claro\"><td width=\"15%\" class=\"".$this->SetStyle("tipoCirugia")."\">TIPO CIRUGIA:</td><td><select name=\"tipoCirugia\" class=\"select\">";
		$tipoCirugia=$datosQX['tipo_cirugia'];
	  $tiposCirugias=$this->TiposdeCirugia();
	  $this->MostrasSelect($tiposCirugias,'False',$tipoCirugia);
	  $this->salida .= "        </select></td>";
		$this->salida.= "         </tr>";
		$this->salida .= "			  <tr class=\"modulo_list_claro\"><td width=\"15%\" class=\"".$this->SetStyle("ambitoCirugia")."\">AMBITO CIRUGIA:</td><td><select name=\"ambitoCirugia\" class=\"select\">";
		$ambitoCirugia=$datosQX['ambito_cirugia'];
	  $tiposAmbitos=$this->TiposdeAmbitosdeCirugia();
	  $this->MostrasSelect($tiposAmbitos,'False',$ambitoCirugia);
	  $this->salida .= "        </select>&nbsp&nbsp&nbsp&nbsp;<input class=\"input-submit\" type=\"submit\" name=\"Seleccionar\" value=\"GUARDAR\"></td>";
		$this->salida.= "         </tr>";
    $this->salida .= "         </table><BR>";
		$this->salida .= "		  </td>";
    $this->salida .= "		  </tr>";
		$this->salida .= "    </table>";
    $this->salida .= "    </form>";
		$accion=ModuloGetURL('app','QXEjecucion','user','InsertarProcedimientosQururgicos',array("TipoId"=>$TipoId,"Documento"=>$Documento,"Responsable"=>$Responsable,"cuenta"=>$cuenta));
		$this->salida .= "    <form name=\"formaProgracionQx\" action=\"$accion\" method=\"post\">";
		$this->salida.= "     <input type=\"hidden\" name=\"viaAcceso\" value=\"$viaAcceso\" READONLY>";
		$this->salida.= "     <input type=\"hidden\" name=\"tipoCirugia\" value=\"$tipoCirugia\" READONLY>";
		$this->salida.= "     <input type=\"hidden\" name=\"ambitoCirugia\" value=\"$ambitoCirugia\" READONLY>";
		$this->salida.= "     <input type=\"hidden\" name=\"codigos1\" value=\"$codigos\" READONLY>";
		$this->salida .= "    <table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "     <tr><td colspan=\"4\" align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "     </td></tr>";
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
		$this->salida .= "		      <tr class=\"modulo_list_claro\">";
    $nombreAn=$this->NombreProfesional($procedimientos[$i]['ayudante_id'],$procedimientos[$i]['tipo_id_ayudante']);
    $this->salida .= "		      <td width=\"15%\" class=\"label\">AYUDANTE</td>";
		$this->salida .= "		      <td colspan=\"3\">".$nombreAn['nombre']."</td>";
    $this->salida .= "		      </tr>";
    if($procedimientos[$i]['pediatra_id'] && $procedimientos[$i]['tipo_id_pediatra']){
			$this->salida .= "		      <tr class=\"modulo_list_claro\">";
			$nombrePe=$this->NombreProfesional($procedimientos[$i]['pediatra_id'],$procedimientos[$i]['tipo_id_pediatra']);
			$this->salida .= "		      <td width=\"15%\" class=\"label\">PEDIATRA</td>";
			$this->salida .= "		      <td colspan=\"3\">".$nombrePe['nombre']."</td>";
			$this->salida .= "		      </tr>";
		}
		if($procedimientos[$i]['via_procedimiento_bilateral']){
			$this->salida .= "		      <tr class=\"modulo_list_claro\">";
			$this->salida .= "		      <td width=\"15%\" class=\"label\">VIA ACCESO</td>";
			$this->salida .= "		      <td colspan=\"3\">".$procedimientos[$i]['nomvia']."</td>";
			$this->salida .= "		      </tr>";
		}
		if($indica){
			$this->salida .= "		      <tr class=\"modulo_list_claro\">";
			$this->salida .= "		      <td colspan=\"4\" align=\"right\"><input type=\"checkbox\" name=\"seleccion[]\" value=\"".$procedimientos[$i]['procedimiento_qx']."\"></td>";
			$this->salida .= "		      </tr>";
		}else{
      $actionElim=ModuloGetURL('app','QXEjecucion','user','LlamaConfirmarAccion',array("Titulo"=>'ELIMINAR PROCEDIMIENTO',"mensaje"=>'Esta Seguro de Eliminar este Procedimiento',"boton1"=>'ACEPTAR',"boton2"=>'CANCELAR',"arreglo"=>array('Procedimiento'=>$procedimientos[$i]['procedimiento_qx'],"TipoId"=>$TipoId,"Documento"=>$Documento,"Responsable"=>$Responsable,"cuenta"=>$cuenta),"c"=>'app',"m"=>'QXEjecucion',"me"=>'EliminarProcedimientoCumplimiento',"me2"=>'ProcedimientosQuirurgicos'));
			$actionModidy=ModuloGetURL('app','QXEjecucion','user','ModificarProcedimientoQX',array('Procedimiento'=>$procedimientos[$i]['procedimiento_qx'],"TipoId"=>$TipoId,"Documento"=>$Documento,"Responsable"=>$Responsable,"cuenta"=>$cuenta));
			$this->salida .= "		      <td align=\"right\" colspan=\"4\"><a href=\"$actionElim\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"><a>&nbsp&nbsp&nbsp;<a href=\"$actionModidy\"><img border=\"0\" src=\"".GetThemePath()."/images/modificar.png\"><a></td>";
		}
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
		$TipoAYUult=$procedimientos[$i]['ayudante_id'];
		$AyudanteUlt=$procedimientos[$i]['tipo_id_ayudante'];
		}
		$CiruPrincipal=$this->BuscarCirujanoPrincipalQX($ProgramacionId);
		if(!$cirujano || $cirujano==-1){
    $cirujano=$CiruPrincipal['cirujano_id'].'/'.$CiruPrincipal['tipo_id_cirujano'];
		}
    $this->salida .= "      </table><BR>";
		if($indica){
		$this->salida .= "    <table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
    $this->salida .= "    <tr><td align=\"right\"><input class=\"input-submit\" type=\"submit\" value=\"GUARDAR\" name=\"Guardar\"></td></tr>";
		$this->salida .= "    </table><BR>";
		}
    $Ordenes=$this->OrdenesPendientesPaciente();
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
				$actionProgramar=ModuloGetURL('app','QXEjecucion','user','ProcedimientoAProgramacion',array("ordenId"=>$Ordenes[$i]['numero_orden_id'],"cargo"=>$Ordenes[$i]['cargo_cups'],"TipoId"=>$TipoId,"Documento"=>$Documento,"Responsable"=>$Responsable,"cuenta"=>$cuenta));
				$this->salida .= "    <td align=\"center\" width=\"10%\"><a href=\"$actionProgramar\" class=\"link\"><img border=\"0\" src=\"".GetThemePath()."/images/pguardar.png\"></a></td>";
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
		$this->salida .= "			  <tr><td class=\"".$this->SetStyle("tipoProcedimiento")."\">TIPOS PROCEDIMIENTOS:</td><td><select name=\"tipoProcedimiento\" class=\"select\">";
	  $tiposProcedimientos=$this->tiposdeProcedimientos();
	  $this->MostrartiposdeProcedimientos($tiposProcedimientos,'False',$tipoProcedimiento);
	  $this->salida .= "        </select>&nbsp&nbsp&nbsp;";
		$this->salida.= "         <input type=\"button\" name=\"buscar2\" value=\"BUSCAR\" onclick=abrirVentana(this.name,this.form) class=\"input-submit\"></td>";
		$this->salida.= "         </tr>";
		$this->salida.= "<tr>";
		$this->salida.= "<td>";
		$this->salida.= "<label class=\"".$this->SetStyle("procedimiento")."\">PROCEDIMIENTO</label>";
		$this->salida.= "</td>";
		$this->salida.= "<td>";
    $this->salida.= "<input type=\"text\" name=\"codigos\" maxlength=\"6\" size=\"6\" class=\"input-text\" value=\"$codigos\" READONLY>&nbsp&nbsp;";
    $this->salida.= "<input type=\"text\" name=\"procedimiento\" maxlength=\"600\" size=\"80\" class=\"input-text\" value=\"$procedimiento\" READONLY>";
		//$this->salida.= "<input type=\"hidden\" name=\"grupo_tipo_cargo\" value=\"$grupo_tipo_cargo\" READONLY>";
		$this->salida.= "</td>";
		$this->salida.= "</tr>";
		$this->salida .= "		<tr height=\"20\"><td class=\"".$this->SetStyle("ResponsablePro")."\">PLAN: </td><td><select name=\"ResponsablePro\"  class=\"select\">";
		$responsables=$this->CallMetodoExterno('app','Triage','user','responsables');
		$this->MostrarResponsable($responsables,$ResponsablePro);
		$this->salida .= "    </select></td></tr>";
		$this->salida .= "			  <tr><td class=\"".$this->SetStyle("cirujano")."\">CIRUJANO:</td><td><select name=\"cirujano\" class=\"select\">";
	  $profesionales=$this->profesionalesEspecialista();
	  $this->BuscarProfesionlesEspecialistas($profesionales,'False',$cirujano);
	  $this->salida .= "        </select></td></tr>";
		$this->salida .= "				<tr><td class=\"".$this->SetStyle("ayudante")."\">AYUDANTE:</td><td><select name=\"ayudante\" class=\"select\">";
		if($ayudante==-1 || empty($ayudante)){$ayudante=$TipoAYUult.'/'.$AyudanteUlt;}
	  $profesionales=$this->profesionalesAyudantes();
	  $this->BuscarProfesionlesEspecialistas($profesionales,'False',$ayudante);
	  $this->salida .= "        </select></td></tr>";
		if($pediatrico){
		  $this->salida .= "			  <input type=\"hidden\" name=\"pediatrico\" value=\"$pediatrico\">";
			$this->salida .= "			  <tr><td class=\"".$this->SetStyle("pediatra")."\">PEDIATRA:</td><td><select name=\"pediatra\" class=\"select\">";
			$profesionales=$this->profesionalesEspecialistaPediatria();
			$this->BuscarProfesionlesEspecialistas($profesionales,'False',$pediatra);
			$this->salida .= "        </select></td></tr>";
		}
		if($bilateral){
		  $this->salida .= "			  <input type=\"hidden\" name=\"bilateral\" value=\"$bilateral\">";
			$this->salida .= "			  <tr><td class=\"".$this->SetStyle("viabilateral")."\">TIPO VIA:</td><td><select name=\"viabilateral\" class=\"select\">";
			$vias=$this->tiposViaBilaterales();
			$this->MostrasSelect($vias,'False',$viabilateral);
			$this->salida .= "        </select></td></tr>";
		}
		$this->salida .= "       <tr><td align=\"center\" colspan=\"4\"><BR>";
		$this->salida .= "       <input type=\"hidden\" name=\"NumerOrden\" value=\"$numerorden\">";
		if($modificar==1){
		  $this->salida .= "       <input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"CANCELAR\">";
      $this->salida .= "       <input class=\"input-submit\" type=\"submit\" name=\"Modificar\" value=\"MODIFICAR PROCEDIMIENTO\">";
		}else{
      $this->salida .= "       <input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"GUARDAR\">";
		}
		$this->salida .= "       </td></tr>";
		$this->salida .= "		    </fieldset></td></tr></table>";
		$this->salida .= "			  </table>";
		$this->salida .= "       <table border=\"0\" width=\"85%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "       <tr><td  align=\"center\"><BR><input class=\"input-submit\" type=\"submit\" name=\"Salir\" value=\"SALIR\">";
		$this->salida .= "       </td></tr>";
		$this->salida .= "			  </table>";
		$this->salida .= "		</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}


	function LlamaReserva_Sangre_qx($TipoId,$Documento,$Responsable,$cuenta){

		$this->salida .= ThemeAbrirTabla('RESERVA DE SANGRE y/o CRUZADA');
		$accion=ModuloGetURL('app','QXEjecucion','user','RegresaReservaSangre',array("TipoId"=>$TipoId,"Documento"=>$Documento,"Responsable"=>$Responsable,"cuenta"=>$cuenta));
		$this->Encabezado();
		$datosSangre=$this->SacarDatosReservaSangre($tipoIdPac,$PacienteId);
		$sw_urgencia=$datosSangre['sw_urgencia'];
		$preparacion=$datosSangre['preparacion'];
		$fecha_reserva=$datosSangre['fecha_hora_reserva'];
		$Fecha=$this->FechaStamp($fecha_reserva);
		$infoCadena = explode ('/', $Fecha);
		$dia=$infoCadena[0];$dia=str_pad($dia,2,0, STR_PAD_LEFT);
		$mes=$infoCadena[1];$mes=str_pad($mes,2,0, STR_PAD_LEFT);
		$ano=$infoCadena[2];
		$HoraDef=$this->HoraStamp($fecha_reserva);
		$infoCadena = explode (':',$HoraDef);
		$Hora=$infoCadena[0];
		$minutos=$infoCadena[1];
		$fecha_reserva=$dia.'/'.$mes.'/'.$ano;
    $cruzar=$datosSangre['cruzar'];
    $transfuciones_ant=$datosSangre['transfuciones_ant'];
    $reacciones_adv=$datosSangre['reacciones_adv'];
    $descripcion_reac=$datosSangre['descripcion_reac'];
    $embarazos_previos=$datosSangre['embarazos_previos'];
    $fecha_ultimo_embarazo=$datosSangre['fecha_ultimo_embarazo'];
		$infoCadena = explode ('-', $fecha_ultimo_embarazo);
		$dia=$infoCadena[2];$dia=str_pad($dia,2,0, STR_PAD_LEFT);
		$mes=$infoCadena[1];$mes=str_pad($mes,2,0, STR_PAD_LEFT);
		$ano=$infoCadena[0];
		$fecha_ultimo_embarazo=$dia.'/'.$mes.'/'.$ano;
    $motivo_reserva=$datosSangre['motivo_reserva'];
		$grupo_sanguineo=$datosSangre['grupo_sanguineo'];
		$rh=$datosSangre['rh'];
		$estado_gestacion=$datosSangre['estado_gestacion'];
		$desabilitado='disabled';
		$desabilitado1='readonly';
		$this->salida.="<form name=\"forma\" action=\"$accion\" method=\"post\">";
    $this->salida.="<table width=\"85%\" border=\"0\" align=\"center\">";
		$this->salida.="<tr class=\"modulo_table_list_title\">";
		$this->salida.="<td align=\"center\" colspan=\"4\">SOLICITUD DE RESERVA DE SANGRE</td>";
		$this->salida.="</tr>";
    $this->salida.="<tr class=\"modulo_list_oscuro\"><td>";
		$this->salida.="        <BR><table width=\"90%\" border=\"0\" align=\"center\">";
		$this->salida.="        <tr class=\"modulo_list_claro\">";
		$this->salida.="        <td width=\"25%\" colspan=\"2\" align=\"left\" class=\"label\">NIVEL DE URGENCIA</td>";
		if ($sw_urgencia!= '1'){
			$this->salida.="      <td width=\"25%\" colspan=\"1\" align=\"left\">URGENTE<input type=\"radio\"  name=\"sw_urgencia\" value=\"1\" $desabilitado></td>";
			$this->salida.="      <td width=\"20%\" colspan=\"1\" align=\"left\">RESERVAR<input type=\"radio\"  name=\"sw_urgencia\" value=\"0\" checked $desabilitado></td>";
		}else{
			$this->salida.="      <td width=\"25%\" colspan=\"1\" align=\"left\">URGENTE<input type=\"radio\"  name=\"sw_urgencia\" value=\"1\" checked $desabilitado></td>";
			$this->salida.="      <td width=\"20% \" colspan=\"1\" align=\"left\">RESERVAR<input type=\"radio\"  name=\"sw_urgencia\" value=\"0\" $desabilitado></td>";
		}
		$this->salida.="        </tr>";
    $this->salida.="        <tr class=\"modulo_table_list_title\">";
		$this->salida.="        <td align=\"left\" colspan =\"4\">GRUPO SANGUINEO</td>";
		$this->salida.="        </tr>";
		$this->salida.="        <tr class = modulo_list_claro>";
		$this->salida .= "      <td class=\"".$this->SetStyle("grupo_sanguineo")."\">FACTOR </td><td><select name=\"grupo_sanguineo\" class=\"select\" $desabilitado>";
		$facts=$this->ConsultaFactor();
		$this->MostrasSelect($facts,'False',$grupo_sanguineo);
		$this->salida .= "      </select></td>";
		$this->salida.="        <td align=\"left\" class=\"".$this->SetStyle("rh")."\">RH </td>";
    $this->salida.="        <td align=\"left\" >";
		$this->salida.="        <select size=\"1\" name =\"rh\" class =\"select\" $desabilitado>";
		if($rh=='+'){
      $checkeado='selected';
		}else{
      $checkeado1='selected';
		}
		$this->salida.="        <option value = -1>-Seleccione-</option>";
    $this->salida.="        <option value=\"+\" $checkeado> Positivo </option>";
		$this->salida.="        <option value=\"-\" $checkeado1> Negativo </option>";
    $this->salida.="        </select>";
		$this->salida.="        </td>";
		$this->salida.="        </tr>";
		$this->salida.="        <tr class=\"modulo_list_claro\">";
		$this->salida.="        <td align=\"left\" class=\"label\">LABORATORIO</td>";
		$this->salida.="        <td align=\"left\" colspan=\"3\"><input type=\"text\" name=\"laboratorio\" class=\"input-text\" value =\"".$_REQUEST['laboratorio']."\" $desabilitado1></td>";
	  $this->salida.="        </tr>";
    $this->salida.="        <tr class=\"modulo_table_list_title\" ><br>";
		$this->salida.="        <td align=\"left\" colspan=\"4\">COMPONENTES A RESERVAR</td>";
    $this->salida.="        </tr>";
		$comp =$this->ConsultaComponente($datosSangre['hc_reserva_sangre_id']);
    $i=0;
		while($i<sizeof($comp)){
		  $this->salida.="      <tr class =\"modulo_list_claro\">";
		  $this->salida.="      <td align=\"left\" class=\"label\">".$comp[$i][componente]."</td>";
  		$this->salida.="      <td align=\"left\" colspan=\"3\">";
		  $this->salida.="      <select size = \"1\" name = \"Cantidad\" class =\"select\" $desabilitado>";
		  $this->salida.="      <option value = -1>-Seleccione-</option>";
		  for ($j=1;$j<=20; $j++){
				if($j==$comp[$i]['cantidad_componente']){
					$this->salida.="  <option value =\"".$j.",".$comp[$i][hc_tipo_componente]."\" selected>";
					$this->salida.= $j;
					$this->salida.="  </option>";
				}else{
					$this->salida.="  <option value =\"".$j.",".$comp[$i][hc_tipo_componente]."\" >";
					$this->salida.= $j;
					$this->salida.="  </option>";
				}
      }
			$this->salida.="      </select>";
			$this->salida.="      <label class=\"label\">Und</label>";
			$this->salida.="      </td>";
			$this->salida.="      </tr>";
			$i++;
		}
		$this->salida.="        <tr class=\"modulo_list_claro\">";
		$this->salida.="        <td align=\"left\" class=\"label\">PREPARACION</td>";
		$this->salida.="        <td align=\"left\" colspan =\"3\"><textarea style=\"width:100%\" name=\"preparacion\" class =\"textarea\" rows=\"3\" cols=\"60\" $desabilitado1>$preparacion</textarea></td>";
    $this->salida.="        </tr>";

		$this->salida .="       <tr class = \"modulo_list_claro\">";
		$this->salida .="       <td class=\"".$this->SetStyle("fecha_reserva")."\" align=\"left\">FECHA DE LA RESERVA</td>";
		$this->salida .="       <td align=\"left\"><input type=\"text\" readonly class=\"input-text\" size=\"10\" maxlength=\"10\" value=\"$fecha_reserva\" name=\"fecha_reserva\" onBlur=\"IsValidDate(this,'YYYY-MM-DD')\" onKeyUp=\"setDate(this,'YYYY-MM-DD','es')\">
		".ReturnOpenCalendario('forma','fecha_reserva','-')."</td>" ;
		$this->salida.="        <td align=\"left\" colspan =\"2\">";
    $this->salida.="        <table>";
		$this->salida.="          <td class=".$this->SetStyle("hora")." align=\"left\">HORA DE LA RESERVA</td>";
	  $this->salida.="          <td>";
		$this->salida.="          <select size=\"1\" name=\"hora\" class=\"select\" $desabilitado>";
		$this->salida.="          <option value = -1>Seleccione Hora </option>";
	  for ($j=1;$j<=24; $j++){
      if (($j >= 0) AND ($j<= 9)){
				$hora = '0'.$j;
				if($Hora==$hora){
				  $this->salida.="    <option selected value = \"$hora\">0$j</option>";
				}else{
				  $this->salida.="    <option value = \"$hora\">0$j</option>";
				}
			}else{
			  if($Hora==$j){
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
		for ($j=1;$j<=59; $j++){
			if(($j >= 0) AND ($j<= 9)){
				$min = '0'.$j;
				if($minutos==$min){
					$this->salida.="    <option selected value = \"$min\" >0$j</option>";
				}else{
					$this->salida.="    <option value=\"$min\">0$j</option>";
				}
			}else{
			  if($minutos==$j){
					$this->salida.="    <option selected value=$j>$j</option>";
				}else{
					$this->salida.="    <option value=$j>$j</option>";
				}
			}
    }
    $this->salida.="          </select>";
    $this->salida.="          </td>";
		$this->salida.="        </table>";
    $this->salida.="        </tr>";
		$this->salida.="        </tr>";
		$this->salida.="        <tr class = \"modulo_list_claro\">";
		if ($cruzar!='1'){
			$this->salida.="      <td align=\"left\" colspan=\"4\" class=\"label\">CRUZAR<input type=\"checkbox\" name=\"cruzar\" value=\"1\" $desabilitado></td>";
		}else{
			$this->salida.="      <td align=\"left\" colspan=\"4\" class=\"label\">CRUZAR<input type=\"checkbox\" checked name=\"cruzar\" value=\"1\" $desabilitado></td>";
		}
    $this->salida.="        </tr>";
		$this->salida.="        <tr class = \"modulo_list_claro\">";
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
		$this->salida.="        <tr class=\"modulo_list_claro\">";
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
		}*/
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
		if($datosSangre['sexo_id'] == 'F'){
      $this->salida.="      <tr class = \"modulo_table_list_title\">";
		  $this->salida.="      <td align = \"left\" colspan = \"4\">GESTACIONES</td>";
      $this->salida.="      </tr>";
      $this->salida.="      <tr class = \"modulo_list_claro\">";
		  $this->salida.="      <td align = \"left\" class=\"label\" colspan = \"1\">EMBARAZOS PREVIOS</td>";
      //$gesta =$this->ConsultaGestacion();
		  if($embarazos_previos){
        $this->salida.="    <td align=\"left\" colspan=\"1\">Si<input type=\"radio\"  name=\"embarazos_previos\" value=\"1\" checked $desabilitado></td>";
        $this->salida.="    <td align=\"left\" colspan=\"2\">No<input type=\"radio\"  name=\"embarazos_previos\" value=\"0\" $desabilitado></td>";
      }else{
        $this->salida.="    <td align=\"left\" colspan=\"1\">Si<input type=\"radio\"  name=\"embarazos_previos\" value=\"1\" $desabilitado></td>";
        $this->salida.="    <td align=\"left\" colspan=\"2\">No<input type=\"radio\"  name=\"embarazos_previos\" value=\"0\" checked $desabilitado></td>";
		  }
      $this->salida.="      </tr>";
			$this->salida.="      <tr class = \"modulo_list_claro\">";
			$this->salida.="      <td align = \"left\" colspan = \"1\" class=\"label\">EN GESTACION</td>";
      if($estado_gestacion == '1'){
        $this->salida.="    <td align = left colspan =\"1\">Si<input type = \"radio\"  name = \"estado_gestacion\" value = \"1\" checked $desabilitado></td>";
        $this->salida.="    <td align = left colspan =\"2\">No<input type = \"radio\"  name = \"estado_gestacion\" value = \"0\" $desabilitado></td>";
			}else{
        $this->salida.="    <td align = \"left\" colspan = \"1\">Si<input type = \"radio\"  name = \"estado_gestacion\" value = \"1\" $desabilitado></td>";
        $this->salida.="    <td align = \"left\" colspan = \"2\">No<input type = \"radio\"  name = \"estado_gestacion\" value = \"0\" checked $desabilitado></td>";
		  }
       $this->salida.="     </tr>";
			 $this->salida.="     <tr class = \"modulo_list_claro\">";
		   $this->salida.="     <td align = \"left\" class=\"label\">FECHA ULTIMO EMBARAZO</td>";
       $this->salida.="     <td align = \"left\" colspan = \"3\"><input type = \"text\" value = \"$fecha_ultimo_embarazo\" name = \"fecha_ultimo_embarazo\" class =\"input-text\" $desabilitado1></td>";
       $this->salida.="     </tr>";
		}
		$this->salida.="        <tr class=\"modulo_list_claro\">";
		$this->salida.="        <td align=\"left\" class=\"label\">MOTIVO DE LA RESERVA</td>";
    $this->salida.="        <td align=\"left\" colspan=\"3\"><textarea style=\"width:100%\" name=\"motivo_reserva\" class=\"textarea\" rows=\"3\" cols=\"60\" $desabilitado1>$motivo_reserva</textarea></td>";
    $this->salida.="        </tr>";
		$this->salida.="        <tr>";
		$this->salida.="        <td colspan=\"5\" align=\"center\"><input type=\"submit\" value=\"SALIR\" name=\"salir\" class=\"input-submit\"></td>";
		$this->salida.="        </tr>";
    $this->salida.="        </table><BR>";
		$this->salida.="</td></tr>";
		$this->salida.="</table>";
    $this->salida.="	  </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function ReserveEquiposQuirofanos($TipoId,$Documento,$Responsable,$cuenta){

		$this->salida .= ThemeAbrirTabla('QUIROFANO Y EQUIPOS');
		$accion=ModuloGetURL('app','QXEjecucion','user','GuardarDatosQuirofanos',array("TipoId"=>$TipoId,"Documento"=>$Documento,"Responsable"=>$Responsable,"cuenta"=>$cuenta));
		$this->Encabezado();
		$this->salida.="<form name=\"forma\" action=\"$accion\" method=\"post\">";
    $this->salida.="<table width=\"70%\" border=\"0\" align=\"center\">";
		$this->salida.="<tr class=\"modulo_table_list_title\">";
		$this->salida.="<td align=\"center\" colspan=\"6\">SOLICITUD DE RESERVA DE SANGRE</td>";
		if($_SESSION['CIRUGIAS']['ACTO']['CODIGO'] && $_SESSION['CIRUGIAS']['ACTO']['QUIROFANO']){
			$datos=$this->obtenerDatosCumplimiento();
		}elseif($_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO']){
			$datos=$this->obtenerDatosProgramacionQX();
		}
		$datosEquipos=$this->SeleccionEquiposProgramacion();
		$quirofano=$datos['quirofano_id'];
		$FechaProgramIni=$datos['hora_inicio'];
		$FechaProgramFin=$datos['hora_fin'];
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
		$DuracionMin=(mktime($HoraFin,$MinutosFin,0,$mesFin,$diaFin,$anoFin)-mktime($HoraIni,$MinutosIni,0,$mesIni,$diaIni,$anoIni))/60;
		$HorasDura=(int)($DuracionMin/60);
		$HorasDura=str_pad($HorasDura,2,0,STR_PAD_LEFT);
		$MinutosDura=($DuracionMin%60);
		$MinutosDura=str_pad($MinutosDura,2,0,STR_PAD_LEFT);
		$Duracion=$HorasDura.':'.$MinutosDura;
		$diaIni=str_pad($diaIni,2,0, STR_PAD_LEFT);
		$mesIni=str_pad($mesIni,2,0, STR_PAD_LEFT);
		$anoIni=str_pad($anoIni,2,0, STR_PAD_LEFT);
		$FechaProgramacion=$diaIni.'/'.$mesIni.'/'.$anoIni;
		$datosEquipos=$this->SeleccionEquiposProgramacion();
		$this->salida .= "	      <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	      <td class=\"".$this->SetStyle("fechaCirugia")."\" width=\"25%\">FECHA CIRUGIA</td>";
		$this->salida .= "	      <td><input type=\"text\" class=\"text-input\" maxlength=\"10\" value=\"$FechaProgramacion\" name=\"fechaCirugia\" size=\"10\">&nbsp;(dd/mm/aaaa)</td>";
		$this->salida .= "	      <td class=\"".$this->SetStyle("horaInicio")."\" width=\"13%\">HORA INICIO</td>";
		$this->salida .= "	      <td><input type=\"text\" class=\"text-input\" maxlength=\"2\" value=\"$HoraIni\" name=\"horaInicio\" size=\"2\"></td>";
		$this->salida .= "	      <td class=\"".$this->SetStyle("minInicio")."\"  width=\"15%\">MINUTOS INICIO</td>";
		$this->salida .= "	      <td><input type=\"text\" class=\"text-input\" maxlength=\"2\" value=\"$MinutosIni\" name=\"minInicio\" size=\"2\"></td>";
		$this->salida .= "	      </tr>";
		$this->salida .= "	      <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("quirofano")."\" width=\"25%\">QUIROFANO </td><td><select name=\"quirofano\" class=\"select\">";
		$quirofanos=$this->TotalQuirofanos();
		$this->MostrasSelect($quirofanos,'False',$quirofano);
		$this->salida .= "       </select></td>";
		$this->salida .= "	      <td class=\"".$this->SetStyle("HorasDura")."\" width=\"13%\">DURACION (H)</td>";
		$this->salida .= "	      <td><input type=\"text\" class=\"text-input\" maxlength=\"2\" value=\"$HorasDura\" name=\"HorasDura\" size=\"2\"></td>";
		$this->salida .= "	      <td class=\"".$this->SetStyle("MinutosDura")."\" width=\"13%\">DURACION(m)</td>";
		$this->salida .= "	      <td><input type=\"text\" class=\"text-input\" maxlength=\"2\" value=\"$MinutosDura\" name=\"MinutosDura\" size=\"2\"></td>";
		$this->salida .= "	      </tr>";
    $this->salida .= "        </table>";
		if($datosEquipos){
      $this->salida.="<BR><BR><table width=\"90%\" border=\"0\" align=\"center\">";
		  $this->salida.="<tr class=\"modulo_table_list_title\">";
			$this->salida.="<td>EQUIPO</td>";
			$this->salida.="<td>DPTO</td>";
			$this->salida.="<td>&nbsp;</td>";
			$this->salida.="<td>EQUIPO</td>";
			$this->salida.="<td>DPTO</td>";
			$this->salida.="<td>&nbsp;</td>";
			$this->salida.="</tr>";
			$y=1;
			$z=0;
			$this->salida.="<tr class=\"modulo_list_claro\">";
			for($i=0;$i<sizeof($datosEquipos);$i++){
			  $checked='';
			  if($z % 2){
					$this->salida.="<td>".$datosEquipos[$i]['descripcion']."</td>";
					$this->salida.="<td>".$datosEquipos[$i]['departamento']."</td>";
					if(!$_SESSION['CIRUGIAS']['ACTO']['CODIGO']){if($datosEquipos[$i]['programado']==1){$checked='checked';}
					}else{if($datosEquipos[$i]['cumplido']==1){$checked='checked';}}
					$this->salida.="<td><input type=\"checkbox\" name=\"seleccion[]\" value=\"".$datosEquipos[$i]['equipo_id']."\" $checked></td>";
					$this->salida.="</tr>";
					$y++;
					if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
					$this->salida .= "<tr class=\"$estilo\">";
  			}else{
				  $this->salida.="<td>".$datosEquipos[$i]['descripcion']."</td>";
					$this->salida.="<td>".$datosEquipos[$i]['departamento']."</td>";
					if(!$_SESSION['CIRUGIAS']['ACTO']['CODIGO']){if($datosEquipos[$i]['programado']==1){$checked='checked';}
					}else{if($datosEquipos[$i]['cumplido']==1){$checked='checked';}}
					$this->salida.="<td><input type=\"checkbox\" name=\"seleccion[]\" value=\"".$datosEquipos[$i]['equipo_id']."\" $checked></td>";
				}
				$z++;
			}
			if($z % 2){
        $this->salida .= "      <td align=\"center\">&nbsp;</td>";
				$this->salida .= "      <td align=\"center\">&nbsp;</td>";
				$this->salida .= "      <td align=\"center\">&nbsp;</td>";
			}
			$this->salida .= "		</table><BR>";
		}
    $this->salida .= "       <table width=\"60%\" border=\"0\" align=\"center\">";
		$this->salida .= "       <tr><td align=\"center\">";
		$this->salida .= "       <input class=\"input-submit\" type=\"submit\" name=\"cancelar\"  value=\"CANCELAR\">";
		$this->salida .= "       <input class=\"input-submit\" type=\"submit\" name=\"guardar\"  value=\"GUARDAR\">";
		$this->salida .= "       </td></tr>";
    $this->salida .= "        </table>";
    $this->salida.="</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}




















































/**
* Funcion que visulaiza la forma donde se muestran los datos introducidos en una programacion Quirurjica
* @return boolean
*/
	function CumplirLlegadaPac($TipoDocumento,$Documento,$nombrePac,$Responsable,$tipoAfil,$rango,$semanas){
    if($_SESSION['Cirugia']['Cumplimiento']['Acto']){
      $DatosPrincipales=$this->DatosCumplimiento();
      $cirujano=$DatosPrincipales['cirujano_principal'].'/'.$DatosPrincipales['tipo_id_cirujano'];
			$anestesista=$DatosPrincipales['anestesiologo'].'/'.$DatosPrincipales['tipo_id_anestesiologo'];
			$quirofano=$DatosPrincipales['quirofano_id'];
      $FechaInicial=$DatosPrincipales['fecha_hora_inicio'];
			$HoraIni='';
			$MinIni='';
			$FechaFinal=$DatosPrincipales['fecha_hora_final'];
			$HoraFin='';
			$MinFin='';
			$viaAcceso=$DatosPrincipales['via_acceso'];
			$tipoCirugia=$DatosPrincipales['tipo_cirugia_id'];
      $ambitoCirugia=$DatosPrincipales['ambito_cirugia_id'];
			$circulante1=$DatosPrincipales['circulante_uno'].'/'.$DatosPrincipales['tipo_id_circulante_uno'];
			$instrumentista=$DatosPrincipales['tipo_id_instrumentista'].'/'.$DatosPrincipales['instrumentista'];
      $gasAnestesico=$DatosPrincipales['gas_anestesico'];
      $gasAnestesicoMe=$DatosPrincipales['gas_medicinal'];
			$codigos=$DatosPrincipales['diagnostico_id'];
			$procedimiento=$DatosPrincipales['diagnostico_nombre'];
			$TipoAnestesia=$DatosPrincipales['tipo_anestesia'];
		}else{
		  $DatosPrincipales=$this->DatosProgramacion($TipoDocumento,$Documento);
      $cirujano=$DatosPrincipales['cirujano_id'].'/'.$DatosPrincipales['tipo_id_cirujano'];
			$anestesista=$DatosPrincipales['anestesiologo'].'/'.$DatosPrincipales['tipo_id_anestesiologo'];
			$quirofano=$DatosPrincipales['quirofano_id'];
      $FechaInicial=$DatosPrincipales['hora_inicio'];
			$HoraIni='';
			$MinIni='';
			$FechaFinal=$DatosPrincipales['hora_fin'];
			$HoraFin='';
			$MinFin='';
			$viaAcceso=$DatosPrincipales['via_acceso'];
			$tipoCirugia=$DatosPrincipales['tipo_cirugia_id'];
      $ambitoCirugia=$DatosPrincipales['ambito_cirugia_id'];
			$circulante1=$DatosPrincipales['circulante_id'].'/'.$DatosPrincipales['tipo_id_circulante'];
			$instrumentista=$DatosPrincipales['tipo_id_instrumentista'].'/'.$DatosPrincipales['instrumentista'];
      $gasAnestesico=$DatosPrincipales['gas_anestesico'];
      $gasAnestesicoMe=$DatosPrincipales['gas_medicinal'];
			$codigos=$DatosPrincipales['diagnostico_id'];
			$procedimiento=$DatosPrincipales['diagnostico_nombre'];
			$TipoAnestesia=$DatosPrincipales['tipo_anestesia'];
		}
		$this->salida .= ThemeAbrirTabla('CUMPLIMIENTO CIRUGIA');
		$RUTA = $_ROOT ."classes/classbuscador/buscador.php?forma=forma&tipo=";
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
		$this->salida.="    rem = window.open(url2, nombre, str);";
		$this->salida.="    }\n";
		$this->salida.="  function abrirVentanaDiagnostico(nom){\n";
		$this->salida.="    var nombre=\"\";\n";
		$this->salida.="    var url2=\"\";\n";
		$this->salida.="    var str=\"\";\n";
		$this->salida.="    var nombre=\"buscador_General\";\n";
		$this->salida.="    if(nom=='buscar1'){\n";
		$this->salida.="      var tipo=\"diagnostico\";\n";
		$this->salida.="      var alias=\"car1\";\n";
		$this->salida.="    }\n";
		$this->salida.="    if(nom=='buscar2'){\n";
		$this->salida.="      var tipo=\"procedimiento\";\n";
		$this->salida.="      var alias=\"codigos\";\n";
		$this->salida.="    }\n";
		$this->salida.="    if(nom=='buscar'){\n";
		$this->salida.="      var tipo=\"diagnostico\";\n";
		$this->salida.="      var alias=\"codigo\";\n";
		$this->salida.="    }\n";
		$this->salida.="    var str =\"width=450,height=250,resizable=no,status=no,scrollbars=yes\";\n";
		$this->salida.="    var url2 ='$RUTA'+tipo+'&alias='+alias+'&key=cargo'+'&forma=formaUno';\n";
		$this->salida.="    rem = window.open(url2, nombre, str);";
		$this->salida.="  }\n";
		$this->salida.="  function PonFecha(frm){";
    $this->salida.="    frm.FechaIncioGas.value=frm.FechaInicial.value;";
    $this->salida.="  }";
		$this->salida.="  function PonFechaUno(frm){";
    $this->salida.="    frm.FechaFinGas.value=frm.FechaInicial.value;";
    $this->salida.="  }";
		$this->salida.="  function PonFechaDos(frm){";
    $this->salida.="    frm.FechaIngresoRecuperacion.value=frm.FechaInicial.value;";
    $this->salida.="  }";
		$this->salida.="  function PonFechaTres(frm){";
    $this->salida.="    frm.FechaEgresoRecuperacion.value=frm.FechaInicial.value;";
    $this->salida.="  }";
		$this->salida .= "function chequeoTotal(frm,x){";
    $this->salida .= "  if(x==true){";
    $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
    $this->salida .= "      cadena=frm.elements[i].value;";
    $this->salida .= "      if(frm.elements[i].type=='checkbox'){";
    $this->salida .= "        frm.elements[i].checked=true";
		$this->salida .= "      }";
		$this->salida .= "    }";
    $this->salida .= "  }else{";
    $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
		$this->salida .= "      cadena=frm.elements[i].value;";
    $this->salida .= "      if(frm.elements[i].type=='checkbox'){";
    $this->salida .= "        frm.elements[i].checked=false";
		$this->salida .= "      }";
		$this->salida .= "    }";
		$this->salida .= "  }";
    $this->salida .= "}";
		$this->salida.="</script>\n";
		$accion=ModuloGetURL('app','QXEjecucion','user','SalirFormaEjecucion',array("TipoDocumento"=>$TipoDocumento,
		"Documento"=>$Documento,"Responsable"=>$Responsable,"destino"=>$destino));
		$this->salida .= "    <form name=\"formaUno\" action=\"$accion\" method=\"post\">";
		$this->Encabezado();
		if($TipoDocumento && $Documento){
			$Nombres=$this->BuscarNombresPaciente($TipoDocumento,$Documento);
			$Apellidos=$this->BuscarApellidosPaciente($TipoDocumento,$Documento);
			$FechaNacimiento=$this->Edad($TipoDocumento,$Documento);
			$EdadArr=CalcularEdad($FechaNacimiento,$FechaFin);
			$this->salida .= "       <table class=\"normal_10\" border=\"0\" width=\"60%\" align=\"center\">";
			$this->salida .= "       <tr><td width=\"100%\">";
			$this->salida .= "       <fieldset><legend class=\"field\">DATOS DE LA LIQUIDACION</legend>";
			$this->salida .= "       <br><table cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
			if($_SESSION['EJECUCION']['CIRUGIAS']['CODIGO']){
			  $titulo='No. PROGRAMACION'.' '.$_SESSION['EJECUCION']['CIRUGIAS']['CODIGO'].'&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp;';
			}
			if($_SESSION['EJECUCION']['CIRUGIAS']['ACTO']){
			  $titulo.='No. EJECUCION'.' '.$_SESSION['EJECUCION']['CIRUGIAS']['ACTO'];
			}
			$this->salida .= "	      <tr class=\"modulo_table_title\"><td colspan=\"2\" align=\"center\">$titulo</td></tr>";
			$this->salida .= "	      <tr class=\"modulo_list_claro\">";
			$this->salida .= "	      <td  width=\"30%\"><label class=\"label\">IDENTIFICACION PACIENTE</td><td>$TipoDocumento $Documento</td>";
			$this->salida .= "	      </tr>";
			$this->salida .= "	      <tr class=\"modulo_list_claro\">";
			$this->salida .= "	      <td width=\"30%\"><label class=\"label\">NOMBRE PACIENTE</td><td>$Nombres $Apellidos</td>";
			$this->salida .= "	      </tr>";
			$this->salida .= "	      <tr class=\"modulo_list_claro\">";
			$this->salida .= "	      <td  width=\"30%\"><label class=\"label\">EDAD PACIENTE</td><td>".$EdadArr['edad_aprox']."</td>";
			$this->salida .= "	      </tr>";
			$this->salida .= "			  </table><br>";
			$this->salida .= "		    </fieldset></td>";
			$this->salida .= "       </table><BR>";
			$this->salida .= "<table width=\"100%\" align=\"center\" border=\"0\">\n";
			$this->salida .= "<tr><td colspan=\"4\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "</td></tr>";
			$this->salida .= "<tr class=\"modulo_table_title\"><td align=\"center\">DATOS PRINCIPALES DE LA CIRUGIA</td></tr>";
		}else{
		  $this->salida .= "<table width=\"100%\" align=\"center\" border=\"0\">\n";
			$this->salida .= "<tr><td colspan=\"4\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "</td></tr>";
			$this->salida .= "<tr class=\"modulo_table_title\"><td align=\"center\">DATOS PRINCIPALES DE LA CIRUGIA</td></tr>";
      $this->salida .= "<tr><td class=\"modulo_list_oscuro\">\n";
      $this->salida .= "    <BR><table width=\"90%\" border=\"0\" align=\"center\" class=\"normal_10\" >";
			$this->salida .= "		<tr class=\"modulo_table_list_title\"><td colspan=\"2\" class=\"modulo_table_list_title\">DATOS DEL PACIENTE</td></tr>";
			$this->salida .= "		<tr class=\"modulo_list_claro\"><td class=\"label\">TIPO DOCUMENTO </td><td><select name=\"TipoDocumento\" class=\"select\">";
			$tipo_id=$this->tipo_id_paciente();
			$this->Mostrar($tipo_id,'False',$TipoId);
			$this->salida .= "    </select></td></tr>";
			$this->salida .= "		<tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("Documento")."\">DOCUMENTO </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" value=\"$Documento\"></td></tr>";
			$this->salida .= "   </table><BR>";
      $this->salida .= "</td></tr>";
		}
		$this->salida .= "<tr><td class=\"modulo_list_oscuro\">\n";
		$this->salida .= "   <BR><table width=\"95%\" border=\"0\" align=\"center\" class=\"normal_10\" >";
		$this->salida .= "		<tr class=\"modulo_table_list_title\"><td colspan=\"6\" class=\"modulo_table_list_title\">DATOS CIRUGIA</td></tr>";
    $this->salida .= "		<tr class=\"modulo_list_claro\" height=\"20\"><td class=\"".$this->SetStyle("Responsable")."\">PLAN: </td><td colspan=\"5\"><select name=\"Responsable\"  class=\"select\">";
		$responsables=$this->CallMetodoExterno('app','Triage','user','responsables');
		$this->MostrarResponsable($responsables,$Responsable);
		$this->salida .= "    </select></td></tr>";
    $this->salida .= "		<tr class=\"modulo_list_claro\">";
    $this->salida .= "    <td width=\"10%\" nowrap class=\"".$this->SetStyle("cirujano")."\">CIRUJANO PRINCIPAL</td>";
		$this->salida .= "    <td colspan=\"5\"><select name=\"cirujano\" class=\"select\">\n";
		$profesionales=$this->profesionalesEspecialista();
		$this->BuscarProfesionlesEspecialistas($profesionales,'False',$cirujano);
		$this->salida .= "    </select>&nbsp&nbsp&nbsp;<input class=\"input-submit\" name=\"AdicionProfe\" type=\"submit\" value=\"ADICIONAR PROFESIONAL\"></td>\n";
		$this->salida .= "    </tr>";
		$this->salida .= "		<tr class=\"modulo_list_claro\">";
		$this->salida .= "	  <td class=\"".$this->SetStyle("anestesista")."\">ANESTESIOLOGO</td><td colspan=\"5\"><select name=\"anestesista\" class=\"select\">";
	  $profesionales=$this->profesionalesEspecialistaAnestecistas();
	  $this->BuscarProfesionlesEspecialistas($profesionales,'False',$anestesista);
	  $this->salida .= "    </select></td></tr>";
		$this->salida .= "		<tr class=\"modulo_list_claro\">";
		$this->salida .= "	  <td class=\"".$this->SetStyle("quirofano")."\">QUIROFANO</td><td colspan=\"5\"><select name=\"quirofano\" class=\"select\">";
	  $quirofanos=$this->TotalQuirofanos();
	  $this->MostrasSelect($quirofanos,'False',$quirofano);
	  $this->salida .= "    </select></td></tr>";
		$this->salida .= "		<tr class=\"modulo_list_claro\">";
		$this->salida .= "	  <td class=\"".$this->SetStyle("FechaInicial")."\">FECHA INICIO</td>";
		$this->salida .= "	  <td><input type=\"text\" name=\"FechaInicial\" value=\"$FechaInicial\" class=\"input-text\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\"><input type=\"hidden\" value=\"$FechaNacimientoCalculada\" name=\"FechaNacimientoCalculada\">";
		$this->salida .= "	  &nbsp&nbsp&nbsp;".ReturnOpenCalendario('formaUno','FechaInicial','/')."</td>";
		$this->salida .= "	  <td class=\"".$this->SetStyle("HoraIni")."\">HORA INICIO</td>";
    $this->salida .= "	  <td><input size=\"2\" maxlength=\"2\" type=\"text\" class=\"input-text\" name=\"HoraIni\" value=\"$HoraIni\"></td>";
		$this->salida .= "	  <td class=\"".$this->SetStyle("MinIni")."\">MIN INICIO</td>";
    $this->salida .= "	  <td><input size=\"2\" maxlength=\"2\" type=\"text\" class=\"input-text\" name=\"MinIni\" value=\"$MinIni\"></td>";
    $this->salida .= "	  </tr>";
		$this->salida .= "		<tr class=\"modulo_list_claro\">";
		$this->salida .= "	  <td class=\"".$this->SetStyle("FechaFinal")."\">FECHA FIN</td>";
		$this->salida .= "	  <td><input type=\"text\" name=\"FechaFinal\" value=\"$FechaFinal\" class=\"input-text\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\"><input type=\"hidden\" value=\"$FechaNacimientoCalculada\" name=\"FechaNacimientoCalculada\">";
		$this->salida .= "	  &nbsp&nbsp&nbsp;".ReturnOpenCalendario('formaUno','FechaFinal','/')."</td>";
		$this->salida .= "	  <td class=\"".$this->SetStyle("HoraFin")."\">HORA FIN</td>";
    $this->salida .= "	  <td><input size=\"2\" maxlength=\"2\" type=\"text\" class=\"input-text\" name=\"HoraFin\" value=\"$HoraFin\"></td>";
		$this->salida .= "	  <td class=\"".$this->SetStyle("MinFin")."\">MIN FIN</td>";
    $this->salida .= "	  <td><input size=\"2\" maxlength=\"2\" type=\"text\" class=\"input-text\" name=\"MinFin\" value=\"$MinFin\"></td>";
    $this->salida .= "	  </tr>";
		$this->salida .= "		<tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("viaAcceso")."\">VIA ACCESO</td><td colspan=\"5\"><select name=\"viaAcceso\" class=\"select\">";
		$accesos=$this->ViaAccesosCirugia();
		$this->MostrasSelect($accesos,'False',$viaAcceso);
		$this->salida .= "    </select></td></tr>";
		$this->salida .= "		<tr class=\"modulo_list_claro\"><td width=\"15%\" class=\"".$this->SetStyle("tipoCirugia")."\">TIPO CIRUGIA</td><td colspan=\"5\"><select name=\"tipoCirugia\" class=\"select\">";
	  $tiposCirugias=$this->TiposdeCirugia();
	  $this->MostrasSelect($tiposCirugias,'False',$tipoCirugia);
	  $this->salida .= "    </select></td>";
		$this->salida.= "     </tr>";
		$this->salida .= "		<tr class=\"modulo_list_claro\"><td width=\"15%\" class=\"".$this->SetStyle("ambitoCirugia")."\">AMBITO CIRUGIA</td><td colspan=\"5\"><select name=\"ambitoCirugia\" class=\"select\">";
	  $tiposAmbitos=$this->TiposdeAmbitosdeCirugia();
	  $this->MostrasSelect($tiposAmbitos,'False',$ambitoCirugia);
	  $this->salida .= "    </select></td>";
		$this->salida.= "     </tr>";
		$this->salida .= "		<tr class=\"modulo_list_claro\"><td width=\"15%\" class=\"".$this->SetStyle("finalidadCirugia")."\">FINALIDAD CIRUGIA</td><td colspan=\"5\"><select name=\"finalidadCirugia\" class=\"select\">";
	  $tiposFinalidades=$this->TiposfinalidadesCirugia();
	  $this->MostrasSelect($tiposFinalidades,'False',$finalidadCirugia);
	  $this->salida .= "    </select></td>";
		$this->salida.= "     </tr>";
		$this->salida.= "<tr class=\"modulo_list_claro\"><td colspan=\"6\">";
    $this->salida .= "   <BR><table width=\"95%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" >";
		$this->salida.= "     <tr class=\"modulo_list_oscuro\"><td colspan=\"5\">&nbsp;</td></tr>";
		$this->salida.= "     <tr class=\"modulo_list_oscuro\">";
		$this->salida.= "     <td class=\"".$this->SetStyle("cargo")."\">DIAGNOSTICO</td><td><input type=\"text\" name=\"cargo\" maxlength=\"256\" size=\"80\" class=\"input-text\" value=\"$cargo\" READONLY></td>";
		$this->salida.= "     <td class=\"".$this->SetStyle("codigo")."\">CODIGO</td><td><input type=\"text\" name=\"codigo\" maxlength=\"6\" size=\"6\" class=\"input-text\" value=\"$codigo\" READONLY></td>";
		$this->salida.= "     <td><input type=\"button\" name=\"buscar\" value=\"BUSCAR\" onclick=\"abrirVentanaDiagnostico(this.name)\" class=\"input-submit\"></td>";
    $this->salida.= "     </tr>";
		$this->salida.= "     <tr class=\"modulo_list_oscuro\"><td colspan=\"5\">&nbsp;</td></tr>";
    $this->salida.= "    </table><BR>";
		$this->salida.= "</td></tr>";
		$this->salida.= "<tr class=\"modulo_list_claro\"><td width=\"100%\" colspan=\"6\">";
		$this->salida .= "   <BR><table width=\"95%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" >";
		$this->salida.= "     <tr class=\"modulo_list_oscuro\"><td colspan=\"5\">&nbsp;</td></tr>";
		$this->salida.= "     <tr class=\"modulo_list_oscuro\">";
		$this->salida.= "     <td class=\"".$this->SetStyle("cargo1")."\">COMPLICACION</td><td><input type=\"text\" name=\"cargo1\" maxlength=\"256\" size=\"80\" class=\"input-text\" value=\"$cargo1\" READONLY></td>";
		$this->salida.= "     <td class=\"".$this->SetStyle("codigo1")."\">CODIGO</td><td><input type=\"text\" name=\"codigo1\" maxlength=\"6\" size=\"6\" class=\"input-text\" value=\"$codigo1\" READONLY></td>";
		$this->salida.= "     <td><input type=\"button\" name=\"buscar1\" value=\"BUSCAR\" onclick=\"abrirVentanaDiagnostico(this.name)\" class=\"input-submit\"></td>";
    $this->salida.= "     </tr>";
		$this->salida.= "     <tr class=\"modulo_list_oscuro\"><td colspan=\"5\">&nbsp;</td></tr>";
		$this->salida.= "    </table><BR>";
		$this->salida.= "</td></tr>";
  /*$this->salida .= "		<tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("circulante1")."\">CIRCULANTE UNO</td><td><select name=\"circulante1\" class=\"select\">";
	  $profesionales=$this->profesionalesAyudantes();
	  $this->BuscarProfesionlesEspecialistas($profesionales,'False',$circulante1);
	  $this->salida .= "    </select></td>";
		$this->salida .= "		 <td class=\"".$this->SetStyle("circulante2")."\">CIRCULANTE DOS</td><td colspan=\"3\"><select name=\"circulante2\" class=\"select\">";
	  $profesionales=$this->profesionalesAyudantes();
	  $this->BuscarProfesionlesEspecialistas($profesionales,'False',$circulante2);
	  $this->salida .= "     </select></td></tr>";
		$this->salida .= "		  <tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("instrumentista")."\">INSTRUMENTISTA</td><td colspan=\"5\"><select name=\"instrumentista\" class=\"select\">";
	  $profesionales=$this->profesionalesAyudantes();
	  $this->BuscarProfesionlesEspecialistas($profesionales,'False',$instrumentista);
	  $this->salida .= "     </select></td></tr>";*/
		$this->salida .= "			<tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("gasAnestesico")."\">GAS ANESTESICO</td><td><select name=\"gasAnestesico\" class=\"select\">";
	  $TipoGases=$this->TiposGasesAnestesicos('A');
	  $this->MostrasSelect($TipoGases,'False',$gasAnestesico);
	  $this->salida .= "      </select></td>";
		$this->salida .= "			<td class=\"".$this->SetStyle("gasAnestesicoMe")."\">GAS ANESTESICO MEDICINAL</td><td colspan=\"3\"><select name=\"gasAnestesicoMe\" class=\"select\">";
	  $TipoGases=$this->TiposGasesAnestesicos('M');
	  $this->MostrasSelect($TipoGases,'False',$gasAnestesicoMe);
	  $this->salida .= "      </select></td>";
		$this->salida .= "		   </tr>";
		$this->salida .= "			<tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("TipoAnestesia")."\">TIPO ANESTESIA</td><td colspan=\"5\"><select name=\"TipoAnestesia\" class=\"select\">";
	  $TiposAnestesias=$this->TiposDeAnestesias();
	  $this->MostrasSelect($TiposAnestesias,'False',$TipoAnestesia);
	  $this->salida .= "      </select></td></tr>";
		$this->salida .= "		  <tr class=\"modulo_list_claro\">";
		$this->salida .= "	  	  <td width=\"35%\" class=\"".$this->SetStyle("FechaIncioGas")."\">FECHA Y HORA INICIO ANESTESIA</td>";
		$this->salida .= "	  	  <td><input type=\"text\" name=\"FechaIncioGas\" value=\"$FechaIncioGas\" class=\"input-text\" onFocus=\"PonFecha(this.form)\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\"><input type=\"hidden\" value=\"$FechaNacimientoCalculada\" name=\"FechaNacimientoCalculada\">";
		$this->salida .= "	  	  ".ReturnOpenCalendario('formaUno','FechaIncioGas','/')."</td>";
    $this->salida .= "        <td class=\"".$this->SetStyle("HoraInicioGas")."\"><b>HORA INICIO</b><td><input maxlength=\"2\" size=\"2\" name=\"HoraInicioGas\" value=\"$HoraInicioGas\" class=\"input-text\"></td>";
    $this->salida .= "        <td class=\"".$this->SetStyle("MinutosInicioGas")."\">MIN INICIO</td><td><input size=\"2\" name=\"MinutosInicioGas\" maxlength=\"2\" value=\"$MinutosInicioGas\" class=\"input-text\"></td>";
    $this->salida .= "		  </tr>";
		$this->salida .= "		  <tr class=\"modulo_list_claro\">";
		$this->salida .= "	  	  <td width=\"35%\" class=\"".$this->SetStyle("FechaFinGas")."\">FECHA FIN ANESTESIA</td>";
		$this->salida .= "	  	  <td><input type=\"text\" name=\"FechaFinGas\" value=\"$FechaFinGas\" class=\"input-text\" onFocus=\"PonFechaUno(this.form)\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\"><input type=\"hidden\" value=\"$FechaNacimientoCalculada\" name=\"FechaNacimientoCalculada\">";
		$this->salida .= "	  	  ".ReturnOpenCalendario('formaUno','FechaFinGas','/')."</td>";
    $this->salida .= "       <td class=\"".$this->SetStyle("HoraFinGas")."\">HORA FIN</td><td><input maxlength=\"2\" size=\"2\" name=\"HoraFinGas\" value=\"$HoraFinGas\" class=\"input-text\"></td>";
    $this->salida .= "       <td class=\"".$this->SetStyle("MinutosFinGas")."\">MIN FIN</td><td><input size=\"2\" maxlength=\"2\" name=\"MinutosFinGas\" value=\"$MinutosFinGas\" class=\"input-text\"></td>";
		$this->salida .= "		  </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\"><td class=\"label\" width=\"30%\">ESTADO SALIDA</td>";
		if(!$estadoSalida || $estadoSalida==1){
      $var='checked';
		}else{
      $var1='checked';
		}
		$this->salida .= "      <td colspan=\"5\" class=\"label\" width=\"30%\">VIVO";
    $this->salida .= "      <input type=\"radio\" name=\"estadoSalida\" value=\"1\" $var>MUERTO";
		$this->salida .= "      <input type=\"radio\" name=\"estadoSalida\" value=\"2\" $var1></td></tr>";
		$this->salida .= "		   <tr class=\"modulo_list_claro\"><td width=\"15%\" class=\"".$this->SetStyle("protocolo")."\">PROTOCOLO QX</td><td colspan=\"5\"><select name=\"protocolo\" class=\"select\">";
	  $protocolos=$this->Protocolos_quirurgicos();
	  $this->MostrasSelect($protocolos,'False',$protocolo);
	  $this->salida .= "      </select></td>";
		$this->salida.= "       </tr>";
		$this->salida .= "		   <tr class=\"modulo_list_claro\">";
		$this->salida .= "	  	  <td width=\"35%\" class=\"".$this->SetStyle("FechaIngresoRecuperacion")."\">FECHA Y INGRESO RECUPERACION:</td>";
		$this->salida .= "	  	  <td><input type=\"text\" name=\"FechaIngresoRecuperacion\" value=\"$FechaIngresoRecuperacion\" class=\"input-text\" onFocus=\"PonFechaDos(this.form)\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\"><input type=\"hidden\" value=\"$FechaNacimientoCalculada\" name=\"FechaNacimientoCalculada\">";
		$this->salida .= "	  	  ".ReturnOpenCalendario('formaUno','FechaIngresoRecuperacion','/')."</td>";
    $this->salida .= "        <td class=\"".$this->SetStyle("HoraIngresoRecuperacion")."\">HORA INICIO</td><td><input maxlength=\"2\" size=\"2\" name=\"HoraIngresoRecuperacion\" value=\"$HoraIngresoRecuperacion\" class=\"input-text\"></td>";
    $this->salida .= "        <td class=\"".$this->SetStyle("MinutosIngresoRecuperacion")."\">MIN INICIO</td><td><input size=\"2\" name=\"MinutosIngresoRecuperacion\" maxlength=\"2\" value=\"$MinutosIngresoRecuperacion\" class=\"input-text\"></td>";
    $this->salida .= "		   </tr>";
    $this->salida .= "		   <tr class=\"modulo_list_claro\">";
		$this->salida .= "	  	  <td width=\"35%\" class=\"".$this->SetStyle("FechaEgresoRecuperacion")."\">FECHA Y EGRESO RECUPERACION:</td>";
		$this->salida .= "	  	  <td><input type=\"text\" name=\"FechaEgresoRecuperacion\" value=\"$FechaEgresoRecuperacion\" class=\"input-text\" onFocus=\"PonFechaTres(this.form)\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\"><input type=\"hidden\" value=\"$FechaNacimientoCalculada\" name=\"FechaNacimientoCalculada\">";
		$this->salida .= "	  	  ".ReturnOpenCalendario('formaUno','FechaEgresoRecuperacion','/')."</td>";
    $this->salida .= "        <td class=\"".$this->SetStyle("HoraEgresoRecuperacion")."\">HORA FIN</td><td><input maxlength=\"2\" size=\"2\" name=\"HoraEgresoRecuperacion\" value=\"$HoraEgresoRecuperacion\" class=\"input-text\"></td>";
    $this->salida .= "        <td class=\"".$this->SetStyle("MinutosEgresoRecuperacion")."\">MIN FIN</td><td><input size=\"2\" name=\"MinutosEgresoRecuperacion\" maxlength=\"2\" value=\"$MinutosEgresoRecuperacion\" class=\"input-text\"></td>";
		$this->salida .= "		   </tr>";
		$this->salida .= "      <tr><td colspan=\"6\" align=\"center\">\n";
    $this->salida .= "      <input type=\"submit\" name=\"Guardar\" value=\"GUARDAR\" class=\"input-submit\">";
    $this->salida .= "      </td></tr>";
		$this->salida .= "    </table><BR>";
		$this->salida .= "</td></tr>";
		$this->salida .= "</table><BR><BR>";
		$this->salida .= "</form>";
		$accion=ModuloGetURL('app','QXEjecucion','user','SalirFormaEjecucionProcedimientos',array("TipoDocumento"=>$TipoDocumento,
		"Documento"=>$Documento,"Responsable"=>$Responsable,"cirujano"=>$cirujano,"anestesista"=>$anestesista,
		"quirofano"=>$quirofano,"FechaInicial"=>$FechaInicial,"HoraIni"=>$HoraIni,"MinIni"=>$MinIni,
		"FechaFinal"=>$FechaFinal,"HoraFin"=>$HoraFin,"MinFin"=>$MinFin,"viaAcceso"=>$viaAcceso,"tipoCirugia"=>$tipoCirugia,"ambitoCirugia"=>$ambitoCirugia,
		"circulante1"=>$circulante1,"circulante2"=>$circulante2,"instrumentista"=>$instrumentista,
		"gasAnestesico"=>$gasAnestesico,"gasAnestesicoMe"=>$gasAnestesicoMe,"destino"=>$destino,
		"cargo"=>$cargo,"codigo"=>$codigo,"cargo1"=>$cargo1,"codigo1"=>$codigo1,"TipoAnestesia"=>$TipoAnestesia,"FechaIncioGas"=>$FechaIncioGas,
		"HoraInicioGas"=>$HoraInicioGas,"MinutosInicioGas"=>$MinutosInicioGas,"FechaFinGas"=>$FechaFinGas,"HoraFinGas"=>$HoraFinGas,"MinutosFinGas"=>$MinutosFinGas,
		"estadoSalida"=>$estadoSalida,"protocolo"=>$protocolo,"FechaIngresoRecuperacion"=>$FechaIngresoRecuperacion,"HoraIngresoRecuperacion"=>$HoraIngresoRecuperacion,
		"MinutosIngresoRecuperacion"=>$MinutosIngresoRecuperacion,"FechaEgresoRecuperacion"=>$FechaEgresoRecuperacion,"HoraEgresoRecuperacion"=>$HoraEgresoRecuperacion,
		"MinutosEgresoRecuperacion"=>$MinutosEgresoRecuperacion));
		$this->salida .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "<table width=\"100%\" align=\"center\" border=\"0\">\n";
		$this->salida .= "<tr class=\"modulo_table_title\"><td align=\"center\">PROCEDIMIENTOS DE LA CIRUGIA</td></tr>";
		$this->salida .= "<tr><td class=\"modulo_list_oscuro\">\n";
		$this->salida .= "    <BR><table width=\"95%\" border=\"0\" align=\"center\" class=\"normal_10\" >";
		$this->salida .= "		 <tr class=\"modulo_table_list_title\"><td colspan=\"4\">NUEVO PROCEDIMIENTO</td></tr>";
		$this->salida .= "		 <tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("tipoProcedimiento")."\">TIPOS PROCEDIMIENTOS</td><td colspan=\"3\" ><select name=\"tipoProcedimiento\" class=\"select\">";
	  $tiposProcedimientos=$this->tiposdeProcedimientos();
	  $this->MostrartiposdeProcedimientos($tiposProcedimientos,'False',$tipoProcedimiento);
	  $this->salida .= "    </select>&nbsp&nbsp&nbsp;";
		$this->salida.= "     <input type=\"button\" name=\"buscar2\" value=\"BUSCAR\" onclick=abrirVentana(this.name,this.form) class=\"input-submit\"></td></tr>";
		$this->salida.= "     <tr class=\"modulo_list_claro\">";
		$this->salida.= "     <td><label class=\"".$this->SetStyle("procedimiento")."\">PROCEDIMIENTO</label></td>";
		$this->salida.= "     <td colspan=\"3\"><input type=\"text\" name=\"codigos\" maxlength=\"6\" size=\"6\" class=\"input-text\" value=\"$codigos\" READONLY>&nbsp&nbsp;";
    $this->salida.= "     <input type=\"text\" name=\"procedimiento\" maxlength=\"600\" size=\"80\" class=\"input-text\" value=\"$procedimiento\" READONLY></td>";
		$this->salida.= "     </tr>";
    $this->salida .= "		<tr class=\"modulo_list_claro\">";
    $this->salida .= "    <td width=\"10%\" nowrap class=\"".$this->SetStyle("cirujanoPro")."\">CIRUJANO</td>";
		$this->salida .= "    <td><select name=\"cirujanoPro\" class=\"select\">\n";
		$profesionales=$this->profesionalesEspecialista();
		$this->BuscarProfesionlesEspecialistas($profesionales,'False',$cirujanoPro);
		$this->salida .= "    </select></td>\n";
		$this->salida .= "		<td class=\"".$this->SetStyle("ayudante")."\">AYUDANTE</td><td><select name=\"ayudante\" class=\"select\">";
	  $profesionales=$this->profesionalesAyudantes();
	  $this->BuscarProfesionlesEspecialistas($profesionales,'False',$ayudante);
	  $this->salida .= "    </select></td></tr>";
    $this->salida .= "		<tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("ResponsablePro")."\">PLAN: </td><td colspan=\"3\"><select name=\"ResponsablePro\"  class=\"select\">";
		$responsables=$this->CallMetodoExterno('app','Triage','user','responsables');
		$this->MostrarResponsable($responsables,$ResponsablePro);
		$this->salida .= "    </select></td></tr>";
		$this->salida .= "    <input type=\"hidden\" name=\"ordenid\" value=\"$ordenid\">";
		$this->salida .= "    <tr class=\"modulo_list_claro\"><td colspan=\"4\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"insertar\" value=\"INSERTAR\">";
		$this->salida .= "    <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\"></td></tr>";
    $this->salida .= "    </td></tr>";
    $this->salida .= "    </table><BR>";
		$this->salida .= "</td></tr>";
		$procedimientos=$this->ProcedimientosEjecucionQX($_SESSION['EJECUCION']['CIRUGIAS']['ACTO']);
		if($procedimientos){
    $this->salida .= "<tr><td class=\"modulo_list_oscuro\">\n";
    $this->salida .= "    <BR><table width=\"90%\" border=\"0\" align=\"center\" class=\"normal_10\" >";
		$this->salida .= "     <tr class=\"modulo_table_list_title\"><td>PROCEDIMIENTOS INSERTADOS</td></tr>";
    $this->salida .= "      <tr class=\"modulo_list_claro\"><td>";
		$this->salida .= "        <br><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "        <tr class=\"modulo_table_list_title\">";
		$this->salida .= "        <td>CODIGO</td>";
		$this->salida .= "        <td>DESCRIPCION</td>";
		$this->salida .= "        <td>PLAN</td>";
		$this->salida .= "        <td>CIRUJANO</td>";
		$this->salida .= "        <td width=\"5%\">&nbsp;</td>";
    if($destino==1){
		  $this->salida .= "        <td width=\"5%\"><input type=\"checkbox\" name=\"chequeo\" onClick=\"chequeoTotal(this.form,this.checked)\"></td>";
		}
		$this->salida .= "        </tr>";
		$y=0;
		for($i=0;$i<sizeof($procedimientos);$i++){
		$this->salida .= "        <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "        <td width=\"10%\">".$procedimientos[$i]['procedimiento_qx']."</td>";
		$this->salida .= "        <td>".$procedimientos[$i]['descripcion']."</td>";
		$this->salida .= "        <td>".$procedimientos[$i]['plan_descripcion']."</td>";
		$this->salida .= "        <td>".$procedimientos[$i]['nombre']."</td>";
		$actionElim=ModuloGetURL('app','QXEjecucion','user','EliminaProcedimientoEj',array("TipoDocumento"=>$TipoDocumento,
		"Documento"=>$Documento,"Responsable"=>$Responsable,"cirujano"=>$cirujano,"anestesista"=>$anestesista,
		"quirofano"=>$quirofano,"FechaInicial"=>$FechaInicial,"HoraIni"=>$HoraIni,"MinIni"=>$MinIni,
		"FechaFinal"=>$FechaFinal,"HoraFin"=>$HoraFin,"MinFin"=>$MinFin,"viaAcceso"=>$viaAcceso,"tipoCirugia"=>$tipoCirugia,"ambitoCirugia"=>$ambitoCirugia,
		"circulante1"=>$circulante1,"circulante2"=>$circulante2,"instrumentista"=>$instrumentista,
		"gasAnestesico"=>$gasAnestesico,"gasAnestesicoMe"=>$gasAnestesicoMe,"codigoProc"=>$procedimientos[$i]['procedimiento_qx'],"destino"=>$destino,
		"cargo"=>$cargo,"codigo"=>$codigo,"cargo1"=>$cargo1,"codigo1"=>$codigo1,"TipoAnestesia"=>$TipoAnestesia,"FechaIncioGas"=>$FechaIncioGas,
		"HoraInicioGas"=>$HoraInicioGas,"MinutosInicioGas"=>$MinutosInicioGas,"FechaFinGas"=>$FechaFinGas,"HoraFinGas"=>$HoraFinGas,"MinutosFinGas"=>$MinutosFinGas,
		"estadoSalida"=>$estadoSalida,"protocolo"=>$protocolo,"FechaIngresoRecuperacion"=>$FechaIngresoRecuperacion,"HoraIngresoRecuperacion"=>$HoraIngresoRecuperacion,
		"MinutosIngresoRecuperacion"=>$MinutosIngresoRecuperacion,"FechaEgresoRecuperacion"=>$FechaEgresoRecuperacion,"HoraEgresoRecuperacion"=>$HoraEgresoRecuperacion,
		"MinutosEgresoRecuperacion"=>$MinutosEgresoRecuperacion));
		$this->salida .= "        <td><a href=\"$actionElim\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
		if($destino==1){
		  $this->salida .= "        <td align=\"center\"><input type=\"checkbox\" name=\"paraEjecucion[]\" value=\"".$procedimientos[$i]['procedimiento_qx']."\"></td>";
		}
		$this->salida .= "        </tr>";
		$y++;
		}
		$this->salida .= "		    </table><BR>";
		$this->salida .= "    </td></tr>";
		$this->salida .= "		</table><br>";
    $this->salida .= "</td></tr>";
		}
		if($_SESSION['EJECUCION']['CIRUGIAS']['CODIGO']){
		$procedimientosProgram=$this->HallarProcedimientosProgram();
		$this->salida .= "<tr><td class=\"modulo_list_oscuro\">\n";
		if($procedimientosProgram){
		$y=0;
		$this->salida .= "    <BR><table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "    <tr><td class=\"modulo_list_claro\">";
		$this->salida .= "    <br><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"6\">PROCEDIMIENTOS PROGRAMADOS</td></tr>";
		for($i=0;$i<sizeof($procedimientosProgram);$i++){
			$this->salida .= "    <tr class=\"modulo_table_list_title\">";
			$this->salida .= "    <td class=\"label\" width=\"10%\" nowrap>CODIGO</td><td>".$procedimientosProgram[$i]['procedimiento_qx']."</td>";
			$this->salida .= "    <td class=\"label\" width=\"12%\" nowrap>DESCRIPCION</td><td align=\"left\" colspan=\"2\">".$procedimientosProgram[$i]['descripcion']."</td>";
			$actionProgramar=ModuloGetURL('app','QXEjecucion','user','ProcedimientoAProgramacion',array("TipoDocumento"=>$TipoDocumento,
			"Documento"=>$Documento,"Responsable"=>$Responsable,"cirujano"=>$cirujano,"anestesista"=>$anestesista,
			"quirofano"=>$quirofano,"FechaInicial"=>$FechaInicial,"HoraIni"=>$HoraIni,"MinIni"=>$MinIni,
			"FechaFinal"=>$FechaFinal,"HoraFin"=>$HoraFin,"MinFin"=>$MinFin,"viaAcceso"=>$viaAcceso,"tipoCirugia"=>$tipoCirugia,"ambitoCirugia"=>$ambitoCirugia,
			"circulante1"=>$circulante1,"circulante2"=>$circulante2,"instrumentista"=>$instrumentista,
			"gasAnestesico"=>$gasAnestesico,"gasAnestesicoMe"=>$gasAnestesicoMe,
			"codigoPro"=>$procedimientosProgram[$i]['procedimiento_qx'],"descripcionPro"=>$procedimientosProgram[$i]['descripcion'],
			"Ciru"=>$procedimientosProgram[$i]['cirujano_id'],"tipoCiru"=>$procedimientosProgram[$i]['tipo_id_cirujano'],
			"Ayu"=>$procedimientosProgram[$i]['ayudante_id'],"tipoAyu"=>$procedimientosProgram[$i]['tipo_id_ayudante'],
      "planPro"=>$procedimientosProgram[$i]['plan_id'],"destino"=>$destino,
			"cargo"=>$cargo,"codigo"=>$codigo,"cargo1"=>$cargo1,"codigo1"=>$codigo1,"TipoAnestesia"=>$TipoAnestesia,"FechaIncioGas"=>$FechaIncioGas,
			"HoraInicioGas"=>$HoraInicioGas,"MinutosInicioGas"=>$MinutosInicioGas,"FechaFinGas"=>$FechaFinGas,"HoraFinGas"=>$HoraFinGas,"MinutosFinGas"=>$MinutosFinGas,
			"estadoSalida"=>$estadoSalida,"protocolo"=>$protocolo,"FechaIngresoRecuperacion"=>$FechaIngresoRecuperacion,"HoraIngresoRecuperacion"=>$HoraIngresoRecuperacion,
			"MinutosIngresoRecuperacion"=>$MinutosIngresoRecuperacion,"FechaEgresoRecuperacion"=>$FechaEgresoRecuperacion,"HoraEgresoRecuperacion"=>$HoraEgresoRecuperacion,
			"MinutosEgresoRecuperacion"=>$MinutosEgresoRecuperacion));
			$this->salida .= "    <td><a href=\"$actionProgramar\" class=\"link\"><img border=\"0\" src=\"".GetThemePath()."/images/pguardar.png\"></a></td>";
			$this->salida .= "    </tr>";
			$this->salida .= "    <tr class=\"modulo_list_oscuro\">";
      $this->salida .= "    <td class=\"label\">No. ORDEN</td><td>".$procedimientosProgram[$i]['numero_orden_id']."</td>";
			$this->salida .= "    <td class=\"label\">PLAN</td><td colspan=\"3\">".$procedimientosProgram[$i]['plan_descripcion']."</td>";
			$this->salida .= "    </tr>";
      $this->salida .= "    <tr class=\"modulo_list_oscuro\">";
      $nombreCir=$this->NombreProfesional($procedimientosProgram[$i]['cirujano_id'],$procedimientosProgram[$i]['tipo_id_cirujano']);
      $this->salida .= "    <td class=\"label\">CIRUJANO</td><td>".$nombreCir['nombre']."</td>";
			$nombreAy=$this->NombreProfesional($procedimientosProgram[$i]['ayudante_id'],$procedimientosProgram[$i]['tipo_id_ayudante']);
			$this->salida .= "    <td class=\"label\">AYUDANTE</td><td colspan=\"3\">".$nombreAy['nombre']."</td>";
			$this->salida .= "    </tr>";
			$this->salida .= "    <tr class=\"modulo_list_oscuro\">";
      $this->salida .= "    <td class=\"label\">TIPO CIRUGIA</td><td>".$procedimientosProgram[$i]['tipcirugia']."</td>";
			$this->salida .= "    <td class=\"label\">AMBITO CIRUGIA</td><td>".$procedimientosProgram[$i]['amcirugia']."</td>";
			$this->salida .= "    <td class=\"label\" width=\"10%\" nowrap>FINALIDAD CIRUGIA</td><td>".$procedimientosProgram[$i]['fincirugia']."</td>";
			$this->salida .= "    </tr>";
			$y++;
		}
		$this->salida .= "		 </table><BR>";
		$this->salida .= "    </td></tr>";
		$this->salida .= "		 </table><br>";
		}
    }
		$this->salida .= "<tr><td align=\"center\">";
		if($destino!=1){
		  $this->salida .= "<input type=\"submit\" name=\"SalirsinGuardar\" class=\"input-submit\" value=\"SALIR SIN GUARDAR\">&nbsp&nbsp&nbsp;";
		}
		$this->salida .= "<input type=\"submit\" name=\"Salir\" class=\"input-submit\" value=\"SALIR\">";
		if($destino==1){
    $this->salida .= "<input type=\"submit\" name=\"Ejecucion\" class=\"input-submit\" value=\"EJECUCION\">";
		}
    $this->salida .= "</td></tr>";
		$this->salida .= "</table>";
		$this->salida .= "</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function FormaBusquedaProgramacion($Busqueda,$centinela,$programaciones){
    $this->salida .= ThemeAbrirTabla('FILTRO DE BUSQUEDA DE UNA PROGRAMACION PARA REALIZAR SU CUMPLIMIENTO');
    $this->Encabezado();
		$accion=ModuloGetURL('app','QXEjecucion','user','BusquedaProgramacion');
		$this->salida .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "<table class=\"normal_10\" border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "<tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "</td></tr>";
    $this->salida .= "<tr class=\"modulo_list_oscuro\"><td align=\"center\" width=\"70%\">";
    $this->salida .= "    <BR><table class=\"normal_10\" width=\"95%\" border=\"0\" align=\"center\">";
    $this->salida .= "    <tr class=\"modulo_table_title\"><td align=\"center\" colspan=\"2\" width=\"100%\">PARAMETROS DE BUSQUEDA DE LA PROGRAMACION</td></tr>";
    $this->salida .= "    <input type=\"hidden\" name=\"Busqueda\" value=\"$Busqueda\">";
    if(!$Busqueda || $Busqueda==0){
		  $this->salida .= "    <tr class=\"modulo_list_claro\"><td width=\"25%\" class=\"label\">TIPO DOCUMENTO </td><td><select name=\"TipoDocumento\" class=\"select\">";
			$tipo_id=$this->tipo_id_paciente();
			$this->Mostrar($tipo_id,'False',$TipoId);
			$this->salida .= "     </select></td></tr>";
			$this->salida .= "		  <tr class=\"modulo_list_claro\"><td width=\"25%\" class=\"".$this->SetStyle("Documento")."\">DOCUMENTO </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" value=\"$Documento\"></td></tr>";
		}elseif($Busqueda==1){
		  $this->salida .= "      <tr class=\"modulo_list_claro\"><td width=\"25%\" class=\"".$this->SetStyle("cirujano")."\">CIRUJANO</td><td><select name=\"cirujano\" class=\"select\">\n";
			$profesionales=$this->profesionalesEspecialista();
			$this->BuscarProfesionlesEspecialistas($profesionales,'False',$cirujano);
			$this->salida .= "      </select></td></tr>";
		}elseif($Busqueda==2){
		  $this->salida .= "		   <tr class=\"modulo_list_claro\">";
			$this->salida .= "	  	 <td class=\"".$this->SetStyle("FechaInicial")."\" width=\"25%\">FECHA INICIAL </td>";
			$this->salida .= "	  	 <td><input type=\"text\" name=\"FechaInicial\" value=\"$FechaInicial\" class=\"input-text\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\"><input type=\"hidden\" value=\"$FechaNacimientoCalculada\" name=\"FechaNacimientoCalculada\">";
			$this->salida .= "	  	 ".ReturnOpenCalendario('forma','FechaInicial','/')."</td>";
			$this->salida .= "		   </tr>";
      $this->salida .= "		   <tr class=\"modulo_list_claro\">";
			$this->salida .= "	  	 <td class=\"".$this->SetStyle("FechaFinal")."\" width=\"25%\">FECHA FINAL </td>";
			$this->salida .= "	  	 <td><input type=\"text\" name=\"FechaFinal\" value=\"$FechaFinal\" class=\"input-text\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\"><input type=\"hidden\" value=\"$FechaNacimientoCalculada\" name=\"FechaNacimientoCalculada\">";
			$this->salida .= "	  	 ".ReturnOpenCalendario('forma','FechaFinal','/')."</td>";
			$this->salida .= "		   </tr>";
		}elseif($Busqueda==3){
      $this->salida .= "       <tr class=\"modulo_list_claro\"><td class=\"label\">PROGRAMACION </td>";
      $this->salida .= "       <td><input type=\"text\" class=\"input-text\" name=\"numeroProgramacion\"></td></tr>";
		}elseif($Busqueda==4){
      $this->salida .= "	      <tr class=\"modulo_list_claro\">";
      $this->salida .= "	      <td class=\"".$this->SetStyle("quirofano")."\" width=\"25%\">No. QUIROFANO </td><td><select name=\"quirofano\" class=\"select\">";
			$quirofanos=$this->TotalQuirofanos();
	    $this->MostrasSelect($quirofanos,'False',$quirofano);
			$this->salida .= "       </select></td></tr>";
		}elseif($Busqueda==5){
      $this->salida .= "		   <tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("nombres")."\" width=\"25%\">NOMBRES</td><td><input type=\"text\" class=\"input-text\" name=\"nombres\" size=\"32\" maxlength=\"32\"></td></tr>";
      $this->salida .= "		   <tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("apellidos")."\" width=\"25%\">APELLIDOS</td><td><input type=\"text\" class=\"input-text\" name=\"apellidos\" size=\"32\" maxlength=\"32\"></td></tr>";
			$this->salida .= "       <input type=\"hidden\" name=\"nompacientes\" value=\"$nompacientes\">";
		}
    $this->salida .= "         <tr class=\"modulo_list_claro\"><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"BuscarTotal\" value=\"BUSCAR TODOS\">";
    $this->salida .= "         <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\">";
		$this->salida .= "         </td></tr>";
		$action=ModuloGetURL('app','QXEjecucion','user','LlamaFormaEjecucionCirugia');
		$this->salida .= "	       <tr><td width=\"5%\" colspan=\"2\" ><a href=\"$action\" class=\"link\"><b>CUMPLIMIENTO CIRUGIA</b></a></td></tr>";
		$this->salida .= "			   </table><BR>";
		$this->salida .= "</td>";
		$this->salida .= "<td>";
		$this->salida .= "       <BR><table class=\"normal_10\" width=\"95%\" border=\"0\" align=\"center\">";
		$this->salida .= "       <tr class=\"modulo_table_title\"><td align=\"center\" width=\"100%\" colspan=\"2\">BUSQUEDA AVANZADA</td></tr>";
		$this->salida .= "	      <tr class=\"modulo_list_claro\">";
		$this->salida .= "				<td class=\"label\">TIPO<br>BUSQUEDA: </td><td><select name=\"TipoBusquedaInv\" class=\"select\">";
		$this->salida .="         <option value=\"0\" selected>Identificacion Paciente</option>";
		$this->salida .="         <option value=\"1\">Cirujano</option>";
		$this->salida .="         <option value=\"2\">Fecha Programacion</option>";
		$this->salida .="         <option value=\"3\">Codigo Programacion</option>";
		$this->salida .="         <option value=\"4\">Quirofano</option>";
		$this->salida .="         <option value=\"5\">Nombres Pacientes</option>";
		$this->salida .= "        </select></td></tr>";
		$this->salida .= "				<tr class=\"modulo_list_claro\"><td colspan=\"2\" align=\"center\"><BR><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"></td></tr>";
		$this->salida .= "	      </tr>";
		$this->salida .= "			  </table><BR>";
		$this->salida .= "</td></tr>";
		$this->salida .= "</table><BR><BR>";
		if($centinela==1){
      if($programaciones){
				$this->salida .= "			<table width=\"90%\" class=\"modulo_table_list\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
				$this->salida .= "			<tr class=\"modulo_table_list_title\"><td colspan=\"7\">DATOS DE LAS PROGRAMACIONES</td></tr>\n";
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "			<td width=\"10%\">CODIGO</td>\n";
				$this->salida .= "			<td width=\"10%\">ID PACIENTE</td>\n";
				$this->salida .= "			<td>NOMBRE PACIENTE</td>\n";
				$this->salida .= "			<td>CIRUJANO</td>\n";
				$this->salida .= "			<td width=\"15%\">HORA INICIO</td>\n";
				$this->salida .= "			<td width=\"15%\">QUIROFANO</td>";
				$this->salida .= "			<td width=\"5%\">&nbsp;</td>";
				$this->salida .= "			</tr>\n";
				$y=0;
				for($i=0;$i<sizeof($programaciones);$i++){
					if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
					$this->salida .= "	 <tr class=\"$estilo\">\n";
			    $this->salida .= "	 <td>".$programaciones[$i]['programacion_id']."</td>";
					$this->salida .= "	 <td>".$programaciones[$i]['tipo_id_paciente']."  ".$programaciones[$i]['paciente_id']."</td>";
					$Nombres=$this->BuscarNombresPaciente($programaciones[$i]['tipo_id_paciente'],$programaciones[$i]['paciente_id']);
					$Apellidos=$this->BuscarApellidosPaciente($programaciones[$i]['tipo_id_paciente'],$programaciones[$i]['paciente_id']);
					$this->salida .= "	 <td>$Nombres  $Apellidos</td>";
          $nombreCir=$this->NombreProfesional($programaciones[$i]['cirujano_id'],$programaciones[$i]['tipo_id_cirujano']);
					$this->salida .= "	 <td>".$nombreCir['nombre']."</td>";
					$this->salida .= "	 <td>".$programaciones[$i]['hora_inicio']."</td>";
          $sala=$this->DescripcionQuirofano($programaciones[$i]['quirofano_id']);
					$this->salida .= "	 <td>".$sala['descripcion']."</td>";
          $action=ModuloGetURL('app','QXEjecucion','user','LlamaFormaEjecucionCirugia',array("programacion"=>$programaciones[$i]['programacion_id']));
					$this->salida .= "	 <td width=\"5%\"><a href=\"$action\" class=\"link\"><b>CUMPLIMIENTO</b></a></td>";
					$this->salida .= "	 </tr>";
			  }
				$this->salida .= "			  </table><BR>";
			}
		}
		$this->salida .= "       <table class=\"normal_10\" width=\"95%\" border=\"0\" align=\"center\">";
    $this->salida .= "       <tr><td align=\"center\"><input  type=\"submit\" class=\"input-submit\" value=\"REGRESAR\" name=\"Regresar\"></td></tr>";
    $this->salida .= "       </table>";
		$this->salida .= "</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
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
* Funcion que se encarga de listar los tipos de responsables para mostrarlos por pantalla
* @return array
* @param array codigos y valores de los tipos de responsables de la base de datos
* @param string elemento seleccionado en el objeto donde se imprimen los tipo de responsables
*/
	function MostrarResponsable($responsables,$Responsable){
		$this->salida .=" <option value=\"-1\">-------Seleccione-------</option>";
		for($i=0; $i<sizeof($responsables); $i++){
			if($responsables[$i][plan_id]==$Responsable){
					$this->salida .=" <option value=\"".$responsables[$i][plan_id]."\" selected>".$responsables[$i][plan_descripcion]."</option>";
			}else{
					$this->salida .=" <option value=\"".$responsables[$i][plan_id]."\">".$responsables[$i][plan_descripcion]."</option>";
			}
		}
	}

/**
* Funcion que pide el tipo y numero que identifica al profesional
* @return boolean
*/
	function IdentificacionNuevoProfesional(){
    $this->salida .= ThemeAbrirTabla('DATOS DEL PROFESIONAL');
		$this->Encabezado();
		$accion=ModuloGetURL('app','QXEjecucion','user','LlamaAdicionProfesional');
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
    $this->salida .= "    </form>";
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
	/**
	* Funcion que se encarga de separar la hora del formato timestamp
	* @return array
	*/
  function HoraStamp($hora){
   $hor = strtok ($hora," ");
   for($l=0;$l<4;$l++){
		 $time[$l]=$hor;
     $hor = strtok (":");
	 }
   return  $time[1].":".$time[2].":".$time[3];
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

	/*function FormaEjecucionCirugiaTarifa(){
    $this->salida .= ThemeAbrirTabla('DATOS DEL PROFESIONAL');
		$this->Encabezado();
		$accion=ModuloGetURL('app','QXEjecucion','user','LiquidacionTarifCirugia');
		$this->salida .= "    <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "	   <table width=\"40%\" border=\"0\" class=\"normal_10\" align=\"center\">";
		$this->salida .= "    <tr><td>";
		$this->salida .= "    <fieldset><legend class=\"field\">IDENTIFICACION</legend>";
		$this->salida .= "    <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
    $this->salida .= "		<tr><td>No. ACTO</td>";
		$this->salida .= "		<td><input type=\"text\" name=\"numacto\"></td></tr>";
		$this->salida .= "		</table>";
		$this->salida .= "		</fieldset></td></tr>";
		$this->salida .= "    </table>";
    $this->salida .= "	   <table border=\"0\" class=\"normal_10\" align=\"center\">";
    $this->salida .= "	   <tr><td><input type=\"submit\" name=\"ACEPTAR\" value=\"ACEPTAR\"></td></tr>";
    $this->salida .= "    </table>";
    $this->salida .= "    </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}*/

	function FormaProdedimientosliquidados($vector,$noCumplimiento){
	  IncludeLib("tarifario");
	  $this->salida .= ThemeAbrirTabla('LIQUIDACION QURURGICA');
		$this->Encabezado();
		$accion=ModuloGetURL('app','QXEjecucion','user','LlamaConsultaCumplimiento');
		$this->salida .= "    <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "    <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "    <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td align=\"center\">PROCEDIMIENTO</td>";
		$this->salida .= "      <td>CIRUJANO</td>";
		$this->salida .= "      <td>ANESTESIOLOGO</td>";
    $this->salida .= "      <td>AYUDANTE</td>";
		$this->salida .= "      <td>SALA</td>";
    $this->salida .= "      <td>MATERIALES</td>";
		$this->salida .= "    </tr>";
		$procedimientos=$this->ProcedimientosTotalesQX($noCumplimiento);
		$y=0;
		if($procedimientos){
		  $valorCirTot=$valorAneTot=$valorAyuTot=$valorSalTot=$valorMatTot=0;
      for($j=0;$j<sizeof($procedimientos);$j++){
        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
				$this->salida .= "	 <tr class=\"$estilo\">\n";
				$valorCir=$valorAne=$valorAyu=$valorSal=$valorMat=0;
        $this->salida .= "    <td>".$procedimientos[$j]['procedimiento_qx']."  ".$procedimientos[$j]['descripcion']."</td>";
				for($i=0;$i<sizeof($vector[0]);$i++){
          if($vector[0][$i]['proced']==$procedimientos[$j]['procedimiento_qx']){
            $valorCir=$vector[0][$i]['valorCir'];
					}
				}
				for($i=0;$i<sizeof($vector[1]);$i++){
          if($vector[1][$i]['proced']==$procedimientos[$j]['procedimiento_qx']){
            $valorAne=$vector[1][$i]['valorAne'];
					}
				}
				for($i=0;$i<sizeof($vector[2]);$i++){
          if($vector[2][$i]['proced']==$procedimientos[$j]['procedimiento_qx']){
            $valorAyu=$vector[2][$i]['valorAyu'];
					}
				}
				for($i=0;$i<sizeof($vector[3]);$i++){
          if($vector[3][$i]['proced']==$procedimientos[$j]['procedimiento_qx']){
            $valorSal=$vector[3][$i]['valorSal'];
					}
				}
				for($i=0;$i<sizeof($vector[4]);$i++){
          if($vector[4][$i]['proced']==$procedimientos[$j]['procedimiento_qx']){
            $valorMat=$vector[4][$i]['valorMat'];
					}
				}
				$valorCirTot+=$valorCir;
				$valorAneTot+=$valorAne;
				$valorAyuTot+=$valorAyu;
				$valorSalTot+=$valorSal;
				$valorMatTot+=$valorMat;
				$this->salida .= "    <td>".FormatoValor($valorCir)."</td>";
				$this->salida .= "    <td>".FormatoValor($valorAne)."</td>";
				$this->salida .= "    <td>".FormatoValor($valorAyu)."</td>";
				$this->salida .= "    <td>".FormatoValor($valorSal)."</td>";
				$this->salida .= "    <td>".FormatoValor($valorMat)."</td>";
        $this->salida .= "    </tr>";
				$y++;
			}
			if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
			$this->salida .= "	 <tr class=\"$estilo\">\n";
			$this->salida .= "    <td class=\"label\">TOTALES</td>";
			$this->salida .= "    <td class=\"label\">".FormatoValor($valorCirTot)."</td>";
			$this->salida .= "    <td class=\"label\">".FormatoValor($valorAneTot)."</td>";
			$this->salida .= "    <td class=\"label\">".FormatoValor($valorAyuTot)."</td>";
			$this->salida .= "    <td class=\"label\">".FormatoValor($valorSalTot)."</td>";
			$this->salida .= "    <td class=\"label\">".FormatoValor($valorMatTot)."</td>";
			$this->salida .= "   </tr>";
		}
    $this->salida .= "    </table>";
		$this->salida .= "    <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
    $this->salida .= "    <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"regresar\" value=\"REGRESAR\"></td></tr>";
    $this->salida .= "    </table>";

    $this->salida .= "    </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function BusquedaConsultaCumplimiento($Busqueda,$centinela,$cumplimientos){

		$this->salida .= ThemeAbrirTabla('CONSULTA CUMPLIMIENTOS QX REALIZADOS');
		$this->Encabezado();
		$accion=ModuloGetURL('app','QXEjecucion','user','BusquedaCumplimientoQX');
		$this->salida .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "<table class=\"normal_10\" border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "<tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "</td></tr>";
    $this->salida .= "<tr class=\"modulo_list_oscuro\"><td align=\"center\" width=\"70%\">";
    $this->salida .= "    <BR><table class=\"normal_10\" width=\"95%\" border=\"0\" align=\"center\">";
    $this->salida .= "    <tr class=\"modulo_table_title\"><td align=\"center\" colspan=\"2\" width=\"100%\">PARAMETROS DE BUSQUEDA DEL CUMPLIMIENTO DE LA CIRUGIA</td></tr>";
    $this->salida .= "    <input type=\"hidden\" name=\"Busqueda\" value=\"$Busqueda\">";
    if(!$Busqueda || $Busqueda==0){
		  $this->salida .= "    <tr class=\"modulo_list_claro\"><td width=\"25%\" class=\"label\">TIPO DOCUMENTO </td><td><select name=\"TipoDocumento\" class=\"select\">";
			$tipo_id=$this->tipo_id_paciente();
			$this->Mostrar($tipo_id,'False',$TipoId);
			$this->salida .= "     </select></td></tr>";
			$this->salida .= "		  <tr class=\"modulo_list_claro\"><td width=\"25%\" class=\"".$this->SetStyle("Documento")."\">DOCUMENTO </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" value=\"$Documento\"></td></tr>";
		}elseif($Busqueda==1){
		  $this->salida .= "      <tr class=\"modulo_list_claro\"><td width=\"25%\" class=\"".$this->SetStyle("cirujano")."\">CIRUJANO</td><td><select name=\"cirujano\" class=\"select\">\n";
			$profesionales=$this->profesionalesEspecialista();
			$this->BuscarProfesionlesEspecialistas($profesionales,'False',$cirujano);
			$this->salida .= "      </select></td></tr>";
		}elseif($Busqueda==2){
		  $this->salida .= "		   <tr class=\"modulo_list_claro\">";
			$this->salida .= "	  	 <td class=\"".$this->SetStyle("FechaInicial")."\" width=\"25%\">FECHA INICIAL </td>";
			$this->salida .= "	  	 <td><input type=\"text\" name=\"FechaInicial\" value=\"$FechaInicial\" class=\"input-text\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\"><input type=\"hidden\" value=\"$FechaNacimientoCalculada\" name=\"FechaNacimientoCalculada\">";
			$this->salida .= "	  	 ".ReturnOpenCalendario('forma','FechaInicial','/')."</td>";
			$this->salida .= "		   </tr>";
      $this->salida .= "		   <tr class=\"modulo_list_claro\">";
			$this->salida .= "	  	 <td class=\"".$this->SetStyle("FechaFinal")."\" width=\"25%\">FECHA FINAL </td>";
			$this->salida .= "	  	 <td><input type=\"text\" name=\"FechaFinal\" value=\"$FechaFinal\" class=\"input-text\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\"><input type=\"hidden\" value=\"$FechaNacimientoCalculada\" name=\"FechaNacimientoCalculada\">";
			$this->salida .= "	  	 ".ReturnOpenCalendario('forma','FechaFinal','/')."</td>";
			$this->salida .= "		   </tr>";
		}elseif($Busqueda==3){
      $this->salida .= "       <tr class=\"modulo_list_claro\"><td class=\"label\">No. CUMPLIMIENTO</td>";
      $this->salida .= "       <td><input type=\"text\" class=\"input-text\" name=\"numeroProgramacion\"></td></tr>";
		}elseif($Busqueda==4){
      $this->salida .= "	      <tr class=\"modulo_list_claro\">";
      $this->salida .= "	      <td class=\"".$this->SetStyle("quirofano")."\" width=\"25%\">No. QUIROFANO </td><td><select name=\"quirofano\" class=\"select\">";
			$quirofanos=$this->TotalQuirofanos();
	    $this->MostrasSelect($quirofanos,'False',$quirofano);
			$this->salida .= "       </select></td></tr>";
		}elseif($Busqueda==5){
      $this->salida .= "		   <tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("nombres")."\" width=\"25%\">NOMBRES</td><td><input type=\"text\" class=\"input-text\" name=\"nombres\" size=\"32\" maxlength=\"32\"></td></tr>";
      $this->salida .= "		   <tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("apellidos")."\" width=\"25%\">APELLIDOS</td><td><input type=\"text\" class=\"input-text\" name=\"apellidos\" size=\"32\" maxlength=\"32\"></td></tr>";
			$this->salida .= "       <input type=\"hidden\" name=\"nompacientes\" value=\"$nompacientes\">";
		}
    $this->salida .= "         <tr class=\"modulo_list_claro\"><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"BuscarTotal\" value=\"BUSCAR TODOS\">";
    $this->salida .= "         <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\">";
		$this->salida .= "         </td></tr>";
		$this->salida .= "			   </table><BR>";
		$this->salida .= "</td>";
		$this->salida .= "<td>";
		$this->salida .= "       <BR><table class=\"normal_10\" width=\"95%\" border=\"0\" align=\"center\">";
		$this->salida .= "       <tr class=\"modulo_table_title\"><td align=\"center\" width=\"100%\" colspan=\"2\">BUSQUEDA AVANZADA</td></tr>";
		$this->salida .= "	      <tr class=\"modulo_list_claro\">";
		$this->salida .= "				<td class=\"label\">TIPO<br>BUSQUEDA: </td><td><select name=\"TipoBusquedaInv\" class=\"select\">";
		$this->salida .="         <option value=\"0\" selected>Identificacion Paciente</option>";
		$this->salida .="         <option value=\"1\">Cirujano</option>";
		$this->salida .="         <option value=\"2\">Fecha Cumplimiento</option>";
		$this->salida .="         <option value=\"3\">Codigo Cumplimiento</option>";
		$this->salida .="         <option value=\"4\">Quirofano</option>";
		$this->salida .="         <option value=\"5\">Nombres Pacientes</option>";
		$this->salida .= "        </select></td></tr>";
		$this->salida .= "				<tr class=\"modulo_list_claro\"><td colspan=\"2\" align=\"center\"><BR><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"></td></tr>";
		$this->salida .= "	      </tr>";
		$this->salida .= "			  </table><BR>";
		$this->salida .= "</td></tr>";
		$this->salida .= "</table><BR><BR>";
		if($centinela==1){
      if($cumplimientos){
				$this->salida .= "			<table width=\"90%\" class=\"modulo_table_list\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
				$this->salida .= "			<tr class=\"modulo_table_list_title\"><td colspan=\"6\">DATOS DE LAS PROGRAMACIONES</td></tr>\n";
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "			<td>CODIGO</td>\n";
				$this->salida .= "			<td>ID PACIENTE</td>\n";
				$this->salida .= "			<td>NOMBRE PACIENTE</td>\n";
				$this->salida .= "			<td>CIRUJANO</td>\n";
				$this->salida .= "			<td>HORA INICIO</td>\n";
				$this->salida .= "			<td>&nbsp;</td>";
				$this->salida .= "			</tr>\n";
				$y=0;
				for($i=0;$i<sizeof($cumplimientos);$i++){
					if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
					$this->salida .= "	 <tr class=\"$estilo\">\n";
			    $this->salida .= "	 <td>".$cumplimientos[$i]['qx_acto_id']."</td>";
					$this->salida .= "	 <td>".$cumplimientos[$i]['tipo_id_paciente']."  ".$cumplimientos[$i]['paciente_id']."</td>";
					$this->salida .= "	 <td>".$cumplimientos[$i]['nom']."  ".$cumplimientos[$i]['prenom']."</td>";
          $nombreCir=$this->NombreProfesional($cumplimientos[$i]['cirujano_principal'],$cumplimientos[$i]['tipo_id_cirujano']);
					$this->salida .= "	 <td>".$nombreCir['nombre']."</td>";
					$this->salida .= "	 <td>".$cumplimientos[$i]['fecha_hora_inicio']."</td>";
          $action=ModuloGetURL('app','QXEjecucion','user','ConsultaCumplimientoCirugia',array("noActo"=>$cumplimientos[$i]['qx_acto_id']));
					$this->salida .= "	 <td width=\"5%\"><a href=\"$action\" class=\"link\"><b>CONSULTAR</b></a></td>";
					$this->salida .= "	 </tr>";
			  }
				$this->salida .= "			  </table><BR>";
			}
		}
		$this->salida .= "       <table class=\"normal_10\" width=\"95%\" border=\"0\" align=\"center\">";
    $this->salida .= "       <tr><td align=\"center\"><input  type=\"submit\" class=\"input-submit\" value=\"REGRESAR\" name=\"Regresar\"></td></tr>";
    $this->salida .= "       </table>";
		$this->salida .= "</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

  function FormaEquivalentesLiquidacion($TipoDocumento,$Documento,$nombrePac,$Responsable,$tipoAfil,$rango,$semanas){

    $this->salida .= ThemeAbrirTabla('TARIFARIOS EQUIVALENTES DE LOS PROCEDIMIENTOS');

    /*$this->salida .="<script>\n\n";
    $this->salida .=" function VerificacionEquivalentes(frm){";
    $dat=$this->TraeProcedimientosCirugia($NoLiquidacion);
    for($i=0;$i<sizeof($dat);$i++){
      $this->salida .="   if(frm.Seleccion".$dat[$i]['consecutivo_procedimiento'].".value==''){";
      $this->salida .="     alert('Por cada Procedimiento debe Realizar la Seleccion del Tarifario con el que desea Liquidar');";
      $this->salida .="     return false;";
      $this->salida .="   }";
    }
    $this->salida .=" }";
    $this->salida .="</script>\n\n";*/
    $this->Encabezado();
    $this->salida .= "       <table class=\"normal_10\" border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "       <tr><td width=\"100%\">";
		$this->salida .= "       <fieldset><legend class=\"field\">DATOS AFILIADO</legend>";
		$this->salida .= "       <br><table cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
		if($TipoDocumento && $Documento){
		$this->salida .= "	      <tr class=\"modulo_list_claro\">";
		$this->salida .= "	      <td  width=\"30%\"><label class=\"label\">IDENTIFICACION PACIENTE</td><td>$TipoDocumento $Documento</td>";
		$this->salida .= "	      </tr>";
		}
		if($nombrePac){
		$this->salida .= "	      <tr class=\"modulo_list_claro\">";
		$this->salida .= "	      <td width=\"30%\"><label class=\"label\">NOMBRE PACIENTE</td><td>$nombrePac</td>";
		$this->salida .= "	      </tr>";
		}
		$NombreResponsable=$this->Responsable($Responsable);
		$NombrePlan=$this->PlanNombre($Responsable);
		$this->salida .= "	      <tr class=\"modulo_list_claro\">";
		$this->salida .= "	      <td  width=\"30%\"><label class=\"label\">RESPONSABLE Y PLAN</td><td>".$NombreResponsable."&nbsp&nbsp&nbsp;".$NombrePlan."</td>";
		$this->salida .= "	      </tr>";
		if($tipoAfil || $rango){
		  (list($tipoAfiliado,$nombretipoafil)=explode('/',$tipoAfil));
			$this->salida .= "	      <tr class=\"modulo_list_claro\">";
			$this->salida .= "	      <td  width=\"30%\"><label class=\"label\">TIPO AFILIADO</td><td>".$nombretipoafil."&nbsp&nbsp&nbsp;RANGO:&nbsp&nbsp;".$rango."</td>";
			$this->salida .= "	      </tr>";
		}
		$this->salida .= "	      <tr class=\"modulo_list_claro\">";
		$this->salida .= "	      <td  width=\"30%\"><label class=\"label\">SEMANAS COTIZADAS</td><td>".$semanas."</td>";
		$this->salida .= "	      </tr>";
		$this->salida .= "			  </table><br>";
		$this->salida .= "		    </fieldset></td>";
		$this->salida .= "       </table><BR>";
    $action=ModuloGetURL('app','QXEjecucion','user','GuardarDatosPresupuesto',array("TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,
    "nombrePac"=>$nombrePac,"Responsable"=>$Responsable,"tipoAfil"=>$tipoAfil,"rango"=>$rango,"semanas"=>$semanas));
		$this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\" onsubmit=\"return VerificacionEquivalentes(this)\">";

    $this->salida .= "    <table border=\"0\" width=\"25%\" align=\"center\" class=\"normal_10\">";
    $this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"2\">SELECCION DE DERECHOS PARA LIQUIDAR</td></tr>";
    $chequeado='';
    if($_REQUEST['der_cirujano']){$chequeado='checked';}
    $this->salida .= "    <tr class=\"modulo_list_claro\"><td class=\"label\">CIRUJANO</td><td width=\"5%\"><input $chequeado type=\"checkbox\" value=\"1\" name=\"der_cirujano\"></td></tr>";
    $chequeado='';
    if($_REQUEST['der_anestesiologo']){$chequeado='checked';}
    $this->salida .= "    <tr class=\"modulo_list_claro\"><td class=\"label\">ANESTESIOLOGO</td><td width=\"5%\"><input $chequeado type=\"checkbox\" value=\"1\" name=\"der_anestesiologo\"></td></tr>";
    $chequeado='';
    if($_REQUEST['der_ayudante']){$chequeado='checked';}
    $this->salida .= "    <tr class=\"modulo_list_claro\"><td class=\"label\">AYUDANTE</td><td width=\"5%\"><input $chequeado type=\"checkbox\" value=\"1\" name=\"der_ayudante\"></td></tr>";
    $chequeado='';
    if($_REQUEST['der_sala']){$chequeado='checked';}
    $this->salida .= "    <tr class=\"modulo_list_claro\"><td class=\"label\">SALA</td><td width=\"5%\"><input $chequeado type=\"checkbox\" value=\"1\" name=\"der_sala\"></td></tr>";
    $chequeado='';
    if($_REQUEST['der_materiales']){$chequeado='checked';}
    $this->salida .= "    <tr class=\"modulo_list_claro\"><td class=\"label\">MATERIALES</td><td width=\"5%\"><input $chequeado type=\"checkbox\" value=\"1\" name=\"der_materiales\"></td></tr>";
    $this->salida .= "	 </table><BR>";

    $this->salida .= "    <table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
    $this->salida .= "<tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "</td></tr>";
    $this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"2\">PROCEDIMIENTOS Y SELECCION DE EQUIVALENCIAS</td></tr>";
    if(!empty($_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO1']['NOMBRE'])){
      (list($tipoIdCirujano1,$IdCirujano1,$nombreCirujano1)=explode('/',$_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO1']['NOMBRE']));
      $this->salida .= "  <tr class=\"modulo_table_list_title\"><td colspan=\"2\">$nombreCirujano1</td></tr>";
    }else{
      $this->salida .= "  <tr class=\"modulo_table_list_title\"><td colspan=\"2\">CIRUJANO UNO</td></tr>";
    }
    if(sizeof($_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO1']['PROCEDIMIENTOS'])>0){
      foreach($_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO1']['PROCEDIMIENTOS'] as $pos=>$vector){
        (list($codigo,$desProcedimiento)=explode('||//',$vector));
        $this->salida .= "    <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "    <td class=\"label\">".$codigo." - ".$desProcedimiento."</td>";
        if($bilateral==1){
        $this->salida .= "    <td width=\"15%\" nowrap class=\"label\">BILATERAL</td>";
        }else{
        $this->salida .= "    <td class=\"label\">&nbsp;</td>";
        }
        $this->salida .= "    </tr>";
        $cargosTarifarios=$this->GetEquivalenciasCargosLiquidacion($codigo,$Responsable);
        if($cargosTarifarios){
          $this->salida .= "    <tr class=\"modulo_list_oscuro\"><td colspan=\"2\">";
          $this->salida .= "    <table border=\"0\" width=\"98%\" align=\"center\" class=\"normal_10\">";
          for($j=0;$j<sizeof($cargosTarifarios);$j++){
            if($j==0){$che='checked';}else{$che='';}
            $this->salida .= "        <tr class=\"modulo_list_claro\">";
            $this->salida .= "        <td width=\"10%\">".$cargosTarifarios[$j]['nomtarifario']."</td>";
            $this->salida .= "        <td width=\"10%\">".$cargosTarifarios[$j]['cargo']."</td>";
            $this->salida .= "        <td>".$cargosTarifarios[$j]['descripcion']."</td>";
            $this->salida .= "        <td width=\"5%\"><input type=\"text\" size=\"2\" name=\"Cantidad$codigo\" value=\"1\"></td>";
            $this->salida .= "        <td width=\"5%\"><input $che type=\"radio\" name=\"Seleccion$codigo\" value=\"".$cargosTarifarios[$j]['tarifario_id']."||//".$cargosTarifarios[$j]['cargo']."\"></td>";
            $this->salida .= "        </tr>";
          }
          $this->salida .= "    </table>";
          $this->salida .= "    </td></tr>";
        }
      }
    }
    if(!empty($_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO2']['NOMBRE'])){
      (list($tipoIdCirujano2,$IdCirujano2,$nombreCirujano2)=explode('/',$_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO2']['NOMBRE']));
      $this->salida .= "  <tr class=\"modulo_table_list_title\"><td colspan=\"2\">$nombreCirujano2</td></tr>";
    }else{
      $this->salida .= "  <tr class=\"modulo_table_list_title\"><td colspan=\"2\">CIRUJANO DOS</td></tr>";
    }
    if(sizeof($_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO2']['PROCEDIMIENTOS'])>0){
      foreach($_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO2']['PROCEDIMIENTOS'] as $pos=>$vector){
        (list($codigo,$desProcedimiento)=explode('||//',$vector));
        $this->salida .= "    <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "    <td class=\"label\">".$codigo." - ".$desProcedimiento."</td>";
        if($bilateral==1){
        $this->salida .= "    <td width=\"15%\" nowrap class=\"label\">BILATERAL</td>";
        }else{
        $this->salida .= "    <td class=\"label\">&nbsp;</td>";
        }
        $this->salida .= "    </tr>";
        $cargosTarifarios=$this->GetEquivalenciasCargosLiquidacion($codigo,$Responsable);
        if($cargosTarifarios){
          $this->salida .= "    <tr class=\"modulo_list_oscuro\"><td colspan=\"2\">";
          $this->salida .= "    <table border=\"0\" width=\"98%\" align=\"center\" class=\"normal_10\">";
          for($j=0;$j<sizeof($cargosTarifarios);$j++){
            if($j==0){$che='checked';}else{$che='';}
            $this->salida .= "        <tr class=\"modulo_list_claro\">";
            $this->salida .= "        <td width=\"10%\">".$cargosTarifarios[$j]['nomtarifario']."</td>";
            $this->salida .= "        <td width=\"10%\">".$cargosTarifarios[$j]['cargo']."</td>";
            $this->salida .= "        <td>".$cargosTarifarios[$j]['descripcion']."</td>";
            $this->salida .= "        <td width=\"5%\"><input type=\"text\" size=\"2\" name=\"Cantidad$codigo\" value=\"1\"></td>";
            $this->salida .= "        <td width=\"5%\"><input $che type=\"radio\" name=\"Seleccion$codigo\" value=\"".$cargosTarifarios[$j]['tarifario_id']."||//".$cargosTarifarios[$j]['cargo']."\"></td>";
            $this->salida .= "        </tr>";
          }
          $this->salida .= "    </table>";
          $this->salida .= "    </td></tr>";
        }
      }
    }
    $this->salida .= "	  </table>";
    $this->salida .= "    <table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
    $this->salida .= "    <tr><td align=\"right\"><input type=\"submit\" name=\"Liquidar\" value=\"LIQUIDAR\" class=\"input-submit\"></td></tr>";
    $this->salida .= "	  </table>";
    $this->salida .= "		</form>";
    $action=ModuloGetURL('app','QXEjecucion','user','LlamaAdicionCirujanoActo',array("TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,
    "nombrePac"=>$nombrePac,"Responsable"=>$Responsable,"tipoAfil"=>$tipoAfil,"rango"=>$rango,"semanas"=>$semanas));
		$this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
    $this->salida .= "    <table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
    $this->salida .= "    <tr><td align=\"center\"><input type=\"submit\" name=\"Volver\" value=\"VOLVER\" class=\"input-submit\"></td></tr>";
    $this->salida .= "	  </table>";
    $this->salida .= "		</form>";
    $this->salida .= ThemeCerrarTabla();
		return true;
  }







//pg_dump -u -s SIIS2 > /home/lorena/Desktop/alex.sql
}//fin clase user
?>

