<?php

/**
 * $Id: app_Reportes_Consulta_Externa_userclasses_HTML.php,v 1.9 2005/06/02 
23:19:09 leydi Exp $ * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo para los reportes de las agendas médicas de Consulta Externa
 */

/**
* app_Reportes_Consulta_Externa_user.php
*
* Clase que establece los métodos de acceso y búsqueda de información de los 
turnos* de las agendas médicas para la atención de pacientes en consulta externa
**/

class app_Reportes_Consulta_Externa_userclasses_HTML extends app_Reportes_Consulta_Externa_user{

	function app_Reportes_Consulta_Externa_user_HTML(){
		$this->app_Reportes_Consulta_Externa_user(); //Constructor del padre 'modulo'
		$this->salida='';
		return true;
	}//Función principal que da las opciones para tener acceso a los datos de CARTERA

	function PantallaInicial2()//Llama a todas as opciones posibles
	{
		UNSET($_SESSION['recoex']);
		UNSET($_SESSION['recone']);
		UNSET($_SESSION['recon1']);
		if($this->UsuariosRepconsultaExterna()==false){
			return false;
		}
		return true;
	}
    /**
    * Funcion donde se crea la cabecera que se imprime al principio de cada pantalla
    *
    * @return void
    */
  	function Cabecera()
    {
  		$this->salida .= "  <table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
  		$this->salida .= "    <tr class=\"normal_10AN\">\n";
  		$this->salida .= "      <td class=\"formulacion_table_list\" width=\"30%\">EMPRESA</td>\n";
  		$this->salida .= "      <td width=\"70%\">".$_SESSION['recoex']['razonso']."</td>\n";
  		$this->salida .= "    </tr>\n";
  		if($_SESSION['recoex']['descentro'])
      {
  			$this->salida .= "      <tr class=\"normal_10AN\">";
  			$this->salida .= "        <td class=\"formulacion_table_list\" >CENTRO DE UTILIDAD</td>";
  			$this->salida .= "        <td>".$_SESSION['recoex']['descentro']."</td>";
  			$this->salida .= "      </tr>";
  		}
  		if($_SESSION['recoex']['desunidadfun'])
      {
  			$this->salida .= "      <tr class=\"normal_10AN\">";
  			$this->salida .= "        <td class=\"formulacion_table_list\">UNIDAD FUNCIONAL</td>";
  			$this->salida .= "        <td>".$_SESSION['recoex']['desunidadfun']."</td>";
  			$this->salida .= "      </tr>";
  		}
  		$this->salida .= "      </table><br>";
  	}
	
	function SeleccionDepartamentoUnificado(){
		
		if(empty($_REQUEST['permisoreconex']['empresa_id']) AND
      empty($_SESSION['recoex']['empresa'])){			
			$this->frmError["MensajeError"]="SELECCIONE UNA EMPRESA";
			$this->PantallaInicial2();
			return true;
		}
		if(empty($_SESSION['recoex']['empresa']))
		{
			$_SESSION['recoex']['empresa']=$_REQUEST['permisoreconex']['empresa_id'];
			$_SESSION['recoex']['razonso']=$_REQUEST['permisoreconex']['descripcion1'];
			$_SESSION['recoex']['auditor']=$_REQUEST['permisoreconex']['auditor'];			
	  }		
		$this->salida = ThemeAbrirTabla('SELECCION DEPARTAMENTO');
		$accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','GuardarSeleccionDepartamentoUnificado');
		$this->salida .= "  <form name=\"data\" action=\"$accion\" method=\"post\">";
		$RUTA = "app_modules/Reportes_Consulta_Externa/buscador.php?sign=";
		$mostrar ="\n<script language='javascript'>\n";
		$mostrar.="var rem=\"\";\n";
		$mostrar.="  function xxx(a){\n";
		$mostrar.="    var nombre=\"\"\n";
		$mostrar.="    var url2=\"\"\n";
		$mostrar.="    var str=\"\"\n";
		$mostrar.="    var nombre=\"REPORTE\";\n";
		$mostrar.="    var str =\"width=450,height=180,resizable=no,location=no, status=no,scrollbars=yes\";\n";
		$mostrar.="    var url2 ='$RUTA';\n";
		$mostrar.="    url2 +=a;\n";
		$mostrar.="    rem = window.open(url2, nombre, str)};\n";
		$mostrar.="</script>\n";
    $this->salida .= $mostrar;
		$this->salida .= "  		<table border=\"0\" width=\"50%\" align=\"center\">";
		$this->salida .= "  		<tr><td>";
		$this->salida .= "  			<fieldset><legend class=\"field\">INGRESO DE	DATOS</legend>";
		$this->salida .= "    		<table border=\"0\" width=\"95%\" align=\"center\">";
		$this->salida .= "					<tr><td width=\"15%\" class=\"label\">CENTRO UTILIDAD: </td>";
		$this->salida .= "					<td width=\"15%\"><input type=\"text\" class=\"input-text\" name=\"centroutilidad\" value=\"".$_REQUEST['centroutilidad']."\" maxlength=\"100\" size=\"40\" readonly></td></tr>";
		$this->salida .= "					<tr><td width=\"20%\" class=\"label\">UNIDAD FUNCIONAL: </td>";
		$this->salida .= "					<td width=\"15%\"><input type=\"text\" class=\"input-text\" name=\"unidadfunc\" value=\"".$_REQUEST['unidadfunc']."\" maxlength=\"100\" size=\"40\" readonly></td></tr>";
		$this->salida .= "					<tr><td width=\"15%\" class=\"label\">DEPARTAMENTO: </td>";
		$this->salida .= "					<td width=\"15%\"><input type=\"text\" class=\"input-text\" name=\"departamento\" value=\"".$_REQUEST['departamento']."\" maxlength=\"100\" size=\"40\" readonly>";
		$this->salida .= "					&nbsp;<a href=\"javascript:xxx(1)\"><img title='Busqueda del Departamento' src=\"". GetThemePath() ."/images/auditoria.png\" border='0' width='14' height='14'></td></tr>";		
		$this->salida .= "					<input type=\"hidden\" name=\"centroU\" value=\"".$_REQUEST['centroU']."\" class=\"input-text\">";
		$this->salida .= "					<input type=\"hidden\" name=\"centroUDescripcion\" value=\"\" class=\"input-text\">";
		$this->salida .= "					<input type=\"hidden\" name=\"unidadF\" value=\"".$_REQUEST['unidadF']."\" class=\"input-text\">";
		$this->salida .= "					<input type=\"hidden\" name=\"unidadFDescripcion\" value=\"\" class=\"input-text\">";
		$this->salida .= "					<input type=\"hidden\" name=\"DptoSel\" value=\"".$_REQUEST['DptoSel']."\" class=\"input-text\">";		
		$this->salida .= "					<input type=\"hidden\" name=\"DptoSelDescripcion\" value=\"\" class=\"input-text\">";		
		$this->salida .= "      	</table>";
		$this->salida .= "  			</fieldset>";
		$this->salida .= "  		</td></tr>";
		$this->salida .= "  		</table>";	
		$this->salida .= "  		<table border=\"0\" width=\"50%\" align=\"center\">";
		$this->salida .= "  		<tr><td align=\"center\">";		
		$this->salida .= "  		<input type=\"submit\" name=\"Seleccionar\" value=\"SELECCIONAR\" class=\"input-submit\">";
    $this->salida .= "      <input type=\"submit\" name=\"Volver\" value=\"VOLVER\" class=\"input-submit\">";
		$this->salida .= "  		</td></tr>";
		$this->salida .= "  		</table>";	
		$this->salida .= "  </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
	
	function PantallaInicial($centroutilidad,$centroU,$unidadfunc,$unidadF,$departamento,$DptoSel){
		
		
		$this->salida = ThemeAbrirTabla('REPORTES CONSULTA EXTERNA');
		$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";		$this->salida .= "  <tr><td>";
		$this->Cabecera();
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"formulacion_table_list\" width=\"100%\" colspan=\"1\">";
		$this->salida .= "      MENÚ REPORTES ESTADISTICOS";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_oscuro\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaFormaSeleccion',array("centroutilidad"=>$centroutilidad,"centroU"=>$centroU,"unidadfunc"=>$unidadfunc,"unidadF"=>$unidadF,"departamento"=>$departamento,"DptoSel"=>$DptoSel))."\"><b>AGENDAS MÉDICAS</b></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_oscuro\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"".ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaReporteCancelacionCitas',array("centroutilidad"=>$centroutilidad,"centroU"=>$centroU,"unidadfunc"=>$unidadfunc,"unidadF"=>$unidadF,"departamento"=>$departamento,"DptoSel"=>$DptoSel))."\"><b>MOTIVOS DE CANCELACION DE CITAS MÉDICAS</b></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_oscuro\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"".ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaReporteCancelacionCitasConsolidado',array("centroutilidad"=>$centroutilidad,"centroU"=>$centroU,"unidadfunc"=>$unidadfunc,"unidadF"=>$unidadF,"departamento"=>$departamento,"DptoSel"=>$DptoSel))."\"><b>MOTIVOS DE CANCELACION DE CITAS MÉDICAS CONSOLIDADO</b></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_oscuro\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"".ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaReporteCancelacionCitasConsolidadoEntidad',array("centroutilidad"=>$centroutilidad,"centroU"=>$centroU,"unidadfunc"=>$unidadfunc,"unidadF"=>$unidadF,"departamento"=>$departamento,"DptoSel"=>$DptoSel))."\"><b>MOTIVOS DE CANCELACION DE CITAS MÉDICAS CONSOLIDADO EN LA ENTIDAD</b></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_oscuro\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"".ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaReporteCausasCitasMedicas',array("centroutilidad"=>$centroutilidad,"centroU"=>$centroU,"unidadfunc"=>$unidadfunc,"unidadF"=>$unidadF,"departamento"=>$departamento,"DptoSel"=>$DptoSel))."\"><b>CAUSAS DE CONSULTAS MÉDICAS</b></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		
          //Tizziano Perea
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_oscuro\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"".ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaReporteEstadisticasCausasTipo',array("centroutilidad"=>$centroutilidad,"centroU"=>$centroU,"unidadfunc"=>$unidadfunc,"unidadF"=>$unidadF,"departamento"=>$departamento,"DptoSel"=>$DptoSel))."\"><b>CAUSAS Y TIPOS DE CITAS MÉDICAS</b></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
          //Tizziano Perea
          
          //Tizziano Perea
          $this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_oscuro\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"".ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaReporteEstadisticoOrdenesServicio',array("centroutilidad"=>$centroutilidad,"centroU"=>$centroU,"unidadfunc"=>$unidadfunc,"unidadF"=>$unidadF,"departamento"=>$departamento,"DptoSel"=>$DptoSel))."\"><b>ORDENES DE SERVICIO</b></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
          
          //Tizziano Perea
          $this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_oscuro\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"".ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaReporteEstadisticoCaracteristicasPacientes',array("centroutilidad"=>$centroutilidad,"centroU"=>$centroU,"unidadfunc"=>$unidadfunc,"unidadF"=>$unidadF,"departamento"=>$departamento,"DptoSel"=>$DptoSel))."\"><b>CARACTERISTICAS DE PACIENTES</b></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
          //Tizziano Perea
		
          $this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_oscuro\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"".ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaReporteEstadisticoRendimientoProf',array("centroutilidad"=>$centroutilidad,"centroU"=>$centroU,"unidadfunc"=>$unidadfunc,"unidadF"=>$unidadF,"departamento"=>$departamento,"DptoSel"=>$DptoSel))."\"><b>RENDIMIENTO DE PROFESIONALES</b></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";

		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_oscuro\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"".ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaReporteEstadisticoOportunidadCE',array("centroutilidad"=>$centroutilidad,"centroU"=>$centroU,"unidadfunc"=>$unidadfunc,"unidadF"=>$unidadF,"departamento"=>$departamento,"DptoSel"=>$DptoSel))."\"><b>OPORTUNIDADES DE CITAS MÉDICAS</b></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";

		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_oscuro\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"".ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaReporteEstadisticoRendimientoPersonal',array("centroutilidad"=>$centroutilidad,"centroU"=>$centroU,"unidadfunc"=>$unidadfunc,"unidadF"=>$unidadF,"departamento"=>$departamento,"DptoSel"=>$DptoSel))."\"><b>RENDIMIENTO DEL PERSONAL</b></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
          
          $this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_oscuro\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"".ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaReporteEstadisticoCitasTratamientoOdontologico',array("centroutilidad"=>$centroutilidad,"centroU"=>$centroU,"unidadfunc"=>$unidadfunc,"unidadF"=>$unidadF,"departamento"=>$departamento,"DptoSel"=>$DptoSel))."\"><b>CITAS DE TRATAMIENTO ODONTOLOGICO</b></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_oscuro\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"".ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaReporteHCAbiertasCerradas',array("centroutilidad"=>$centroutilidad,"centroU"=>$centroU,"unidadfunc"=>$unidadfunc,"unidadF"=>$unidadF,"departamento"=>$departamento,"DptoSel"=>$DptoSel))."\"><b>HISTORIAS CLINICAS ABIERTAS Y CERRADAS</b></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";

		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','PantallaInicial2');
		$this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\"><br>";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	
	
  function FormaSeleccion($centroutilidad,$centroU,$unidadfunc,$unidadF,$departamento,$DptoSel)
  {
		UNSET($_SESSION['recone']);
		UNSET($_SESSION['recon1']);
		$this->salida = ThemeAbrirTabla('FILTRO DE DATOS PARA EL REPORTE DE AGENDAS MÉDICAS');
		$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->Cabecera();
		if($this->frmError["MensajeError"]<>NULL)//
		{
			$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "</table><br>";
		}
          
/*          $RUTA = "app_modules/Reportes_Consulta_Externa/buscador.php?sign=";
          $mostrar ="\n<script language='javascript'>\n";
          $mostrar.="var rem=\"\";\n";
          $mostrar.="  function xxx(a){\n";
          $mostrar.="    var nombre=\"\"\n";
          $mostrar.="    var url2=\"\"\n";
          $mostrar.="    var str=\"\"\n";
          $mostrar.="    var nombre=\"REPORTE\";\n";
          $mostrar.="    var str =\"width=450,height=180,resizable=no,location=no, status=no,scrollbars=yes\";\n";
          $mostrar.="    var url2 ='$RUTA';\n";
          $mostrar.="    url2 +=a;\n";
          $mostrar.="    rem = window.open(url2, nombre, str)};\n";
					$mostrar.="</script>\n";
          $this->salida .= $mostrar;
*/
		$accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaFormaAgendaMedica');
		$this->salida .= "  <form name=\"data\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  		<table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  		<tr>";
		$this->salida .= "  		<td>";
		$this->salida .= "  		<fieldset><legend class=\"field\">INGRESO DE	DATOS</legend>";
		$this->salida .= "    	<table border=\"0\" width=\"95%\" align=\"center\">";
		/*$this->salida .= "<tr><td width=\"15%\" class=\"label\">CENTRO UTILIDAD: </td>";
		$this->salida .= "<td width=\"15%\"><input type=\"text\" class=\"input-text\" name=\"centroutilidad\" value=\"".$_REQUEST['centroutilidad']."\" maxlength=\"100\" size=\"40\" readonly></td></tr>";
		$this->salida .= "<tr><td width=\"20%\" class=\"label\">UNIDAD FUNCIONAL: </td>";
		$this->salida .= "<td width=\"15%\"><input type=\"text\" class=\"input-text\" name=\"unidadfunc\" value=\"".$_REQUEST['unidadfunc']."\" maxlength=\"100\" size=\"40\" readonly></td></tr>";
		$this->salida .= "<tr><td width=\"15%\" class=\"label\">DEPARTAMENTO: </td>";
		$this->salida .= "<td width=\"15%\"><input type=\"text\" class=\"input-text\" name=\"departamento\" value=\"".$_REQUEST['departamento']."\" maxlength=\"100\" size=\"40\" readonly>";
		$this->salida .= "&nbsp;<a href=\"javascript:xxx(1)\"><img title='Busqueda del Departamento' src=\"". GetThemePath()."/images/auditoria.png\" border='0' width='14' height='14'></td></tr>";
		*/
		$this->salida .= "<input type=\"hidden\" name=\"centroutilidad\" value=\"".$centroutilidad."\" class=\"input-text\">";
		$this->salida .= "<input type=\"hidden\" name=\"unidadfunc\" value=\"".$unidadfunc."\" class=\"input-text\">";
		$this->salida .= "<input type=\"hidden\" name=\"departamento\" value=\"".$departamento."\" class=\"input-text\">";
		$this->salida .= "<input type=\"hidden\" name=\"centroU\" value=\"".$centroU."\" class=\"input-text\">";
		$this->salida .= "<input type=\"hidden\" name=\"unidadF\" value=\"".$unidadF."\" class=\"input-text\">";
		$this->salida .= "<input type=\"hidden\" name=\"DptoSel\" value=\"".$DptoSel."\" class=\"input-text\">";

		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"label\" width=\"50%\">PROFESIONAL:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		if($_SESSION['recoex']['disabled']['profesional']==1){$vardis3='disabled';}
		$this->salida .= "      <select name=\"profesional\" class=\"select\" $vardis3>";    		
		$consul=$this->BuscarProf($_REQUEST['depto'],$_REQUEST['tipoconsul']);
    if(sizeof($consul)>1){
      $this->salida .= "      <option value=\"-1\">--  SELECCIONE--</option>";
    }
    $a=explode(',',$_REQUEST['profesional']);
		for($i=0;$i<sizeof($consul);$i++){
 		  if($consul[$i]['tipo_id_tercero']==$a[0] AND $consul[$i]['tercero_id']==$a[1] AND $consul[$i]['nombre_tercero']==$a[2])
			{
				$this->salida .="<option value=\"".$consul[$i]['tipo_id_tercero']."".','."".$consul[$i]['tercero_id']."".','."".$consul[$i]['nombre_tercero']."\" selected>".$consul[$i]['nombre_tercero']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$consul[$i]['tipo_id_tercero']."".','."".$consul[$i]['tercero_id']."".','."".$consul[$i]['nombre_tercero']."\">".$consul[$i]['nombre_tercero']."</option>";
			}
		}		
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"50%\">";
		$this->salida .= "      <label class=\"label\">FECHA INICIAL:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td class=\"label\">";
		if(!$_REQUEST['feinictra']){
          	$_REQUEST['feinictra']=date('01/m/Y');
		}
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"feinictra\" value=\"".$_REQUEST['feinictra']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "      ".ReturnOpenCalendario('data','feinictra','/')."";
		$this->salida .= "</td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"50%\">";
		$this->salida .= "      <label class=\"label\">FECHA FINAL:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td class=\"label\">";
		if(!$_REQUEST['fefinctra']){
			$_REQUEST['fefinctra']=date('d/m/Y');
		}
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fefinctra\" value=\"".$_REQUEST['fefinctra']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "      ".ReturnOpenCalendario('data','fefinctra','/')."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td colspan=\"2\" align=\"center\"><br>";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"GENERAR REPORTE\">";
		$this->salida .= "      </td>";
		$this->salida .= "  		</form>";
		$this->salida .= "      </tr>";
		$this->salida .= "  		</fieldset>";
		$this->salida .= "  		</table><br>";
		$this->salida .= "  		</td>";
		$this->salida .= "  		</tr>";
		$this->salida .= "  		</table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr>";
		if($_SESSION['REPORTES_CONSULTA_EXTERNA']['RETORNO']){
			$accion=ModuloGetURL($_SESSION['REPORTES_CONSULTA_EXTERNA']['RETORNO']['contenedor'],$_SESSION['REPORTES_CONSULTA_EXTERNA']['RETORNO']['modulo'],
			$_SESSION['REPORTES_CONSULTA_EXTERNA']['RETORNO']['tipo'],$_SESSION['REPORTES_CONSULTA_EXTERNA']['RETORNO']['metodo'],$_SESSION['REPORTES_CONSULTA_EXTERNA']['RETORNO']['variables']);
		}else{
			$accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','GuardarSeleccionDepartamentoUnificado',array("centroutilidad"=>$centroutilidad,"centroU"=>$centroU,"unidadfunc"=>$unidadfunc,"unidadF"=>$unidadF,"departamento"=>$departamento,"DptoSel"=>$DptoSel));
		}
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" 	method=\"post\">";
		$this->salida .= "  <td align=\"center\">";
		$this->salida	.= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" 	value=\"VOLVER\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "	</table>";
		$this->salida .=
		ThemeCerrarTabla();
		return true;
	}

	function FormaAgendaMedica()
	{ $archivoPlano='';
		$this->salida  = ThemeAbrirTabla('REPORTE ESTADISTICO AGENDAS MÉDICAS');
		$reporte= new GetReports();//FALSE

		$mostrar=$reporte->GetJavaReport('app','Reportes_Consulta_Externa','ReporteConsultaExterna',array('var'=>$_SESSION['recone'],'variables'=>$_REQUEST),array('rpt_name'=>'ConsultaExternas','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
		$funcion=$reporte->GetJavaFunction();
		$this->salida .= "$mostrar";
		$this->salida .= "  <table border=\"0\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->Cabecera();
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		
          if(!empty($_REQUEST['centroutilidad']))
		{
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">CENTRO DE UTILIDAD";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "      ".$_REQUEST['centroutilidad']."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}

		if(!empty($_REQUEST['unidadfunc']))
		{
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">UNIDAD FUNCIONAL";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "      ".$_REQUEST['unidadfunc']."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}

		if(!empty($_REQUEST['departamento']))
		
		{
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DEPARTAMENTO";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "      ".$_REQUEST['departamento']."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}
		
          if($_SESSION['recone']['documentos']<>NULL)
		{
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL PROFESIONAL";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "      ".$_SESSION['recone']['nombreprof']."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}
		if($_SESSION['recone']['fechadesde']<>NULL)
		{
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">FECHA INICIAL";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "      ".$_SESSION['recone']['fechadesde']."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}
		if($_SESSION['recone']['fechahasta']<>NULL)
		{
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">FECHA FINAL";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "      ".$_SESSION['recone']['fechahasta']."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}
		if(!($_SESSION['recone']['codigodepa']<>NULL OR
		$_SESSION['recone']['codigotico']<>NULL OR
		$_SESSION['recone']['documentos']<>NULL OR
		$_SESSION['recone']['fechadesde']<>NULL OR
		$_SESSION['recone']['fechahasta']<>NULL))
		{
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PARAMETROS DE BUSQUEDA:";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "NINGUNO";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table width=\"1510\" border=\"0\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"4%\">No.</td>";
    $archivoPlano.='No.'.'|';
		$this->salida .= "      <td width=\"7%\" >TIPO ID</td>";
    $archivoPlano.='TIPO ID'.'|';
		$this->salida .= "      <td width=\"15%\">IDENTIFICACIÓN</td>";
    $archivoPlano.='IDENTIFICACIÓN'.'|';
		$this->salida .= "      <td width=\"45%\">NOMBRE</td>";
    $archivoPlano.='NOMBRE'.'|';
		$this->salida .= "      <td width=\"22%\">ESPECIALIDAD</td>";
    $archivoPlano.='ESPECIALIDAD'.'|';
		$this->salida .= "      <td width=\"7%\" >ESTADO</td>";
    $archivoPlano.='ESTADO'."\n";
	$this->salida .= "      </tr>";
		$i=0;
		$j=0;
		$ciclo=sizeof($_SESSION['recon1']['datos']);
    $cls = AutoCarga::factory('Afiliados','classes','app','Reportes_Consulta_Externa');

		while($i<$ciclo)
		{
			if($j==0)
			{
				$color="class=modulo_list_claro";
				$color1="class=modulo_list_oscuro";
				$j=1;
			}
			else
			{
				$color="class=modulo_list_oscuro";
				$color1="class=modulo_list_claro";
				$j=0;
			}
			$this->salida .= "<tr $color>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".($i+1)."";
      $archivoPlano.=($i+1).'|';
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".$_SESSION['recon1']['datos'][$i]['tipo_id_tercero']."";//plan_id
      $archivoPlano.=$_SESSION['recon1']['datos'][$i]['tipo_id_tercero'].'|';
			$this->salida .= "</td>";
      $this->salida .= "<td>";
			$this->salida .= "".$_SESSION['recon1']['datos'][$i]['tercero_id']."";
      $archivoPlano.=$_SESSION['recon1']['datos'][$i]['tercero_id'].'|';
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$_SESSION['recon1']['datos'][$i]['nombre_tercero']."";
      $archivoPlano.=$_SESSION['recon1']['datos'][$i]['nombre_tercero'].'|';
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$_SESSION['recon1']['datos'][$i]['descripcion']."";
      $archivoPlano.=$_SESSION['recon1']['datos'][$i]['descripcion'].'|';
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			if($_SESSION['recon1']['datos'][$i]['estado']==0)
			{
				$this->salida .= "INACTIVO";
        $archivoPlano.='INACTIVO'."\n";
			}
			elseif($_SESSION['recon1']['datos'][$i]['estado']==1)
			{
				$this->salida .= "ACTIVO";
        $archivoPlano.='ACTIVO'."\n";
			}
			$datosCargo= $this->ObtenerSWCargosDepto($_SESSION['BusquedaAgenda']['departamento']);//naydu si teine cargos adicionales
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= "<tr $color>";
			$this->salida .= "<td colspan=\"6\" align=\"center\" class=\"formulacion_table_list\">";
			$this->salida .= "  <table align=\"center\" width=\"1510\" $color>\n";
			$this->salida .= "    <tr class=\"formulacion_table_list\">";
			$this->salida .= "      <td width=\"20\">Nro.</td>";
      $archivoPlano.='Nro.'.'|';      
			$this->salida .= "      <td width=\"50\">FECHA TURNO</td>";
      $archivoPlano.='FECHA TURNO'.'|';      
			$this->salida .= "      <td width=\"50\">HORA</td>";
      $archivoPlano.='HORA'.'|';      
			$this->salida .= "      <td width=\"40\">DURACIÓN</td>";
      $archivoPlano.='DURACIÓN'.'|';    
	if($datosCargo[0]['sw_cargos_adicionales']!=1){	  
			$this->salida .= "      <td width=\"50\">CONSULTORIO</td>";
      $archivoPlano.='CONSULTORIO'.'|';      
	  }
			$this->salida .= "      <td width=\"150\">PLAN</td>";
      $archivoPlano.='PLAN'.'|';      
			$this->salida .= "      <td width=\"80\">ESTADO</td>";
      $archivoPlano.='ESTADO'.'|'; 
			// aqui cambio naydu
			$this->salida .= "     <td width=\"100\">COTIZANTE</td>";
			// arhivo plano
			$this->salida .= "      <td width=\"150\">NOMBRE PACIENTE</td>";
      $archivoPlano.='NOMBRE PACIENTE'.'|';      
			$this->salida .= "     <td width=\"100\">IDENT. PACIENTE</td>";
      $archivoPlano.='IDENT. PACIENTE'.'|';      
			if($_SESSION['BusquedaAgenda']['sw_mostrar_historia']=='1')
			{
				$this->salida .= "<td width=\"50\" align=\"center\">VER HISTORIA</td>";
			}
			$this->salida .= "     <td width=\"30\">EDAD</td>\n";
			$this->salida .= "     <td width=\"150\">TELEFONO</td>\n";
			if($datosCargo[0]['sw_cargos_adicionales']==1){
			$this->salida .= "<td width=\"500\" >CARGOS ADICIONALES</td>";	  
			}//este es uno naydu
			$this->salida .= "     <td width=\"80\">TIPO AFILIADO</td>\n";
			$this->salida .= "     <td width=\"80\">RANGO</td>\n";
			$this->salida .= "     <td width=\"80\">PUNTO ATENCION</td>\n";
			if($datosCargo[0]['sw_cargos_adicionales']!=1){
			$this->salida .= "     <td width=\"50\">APERTURA</td>";
      $archivoPlano.='APERTURA'.'|';      
			$this->salida .= "     <td width=\"50\">CIERRE</td>";
      $archivoPlano.='CIERRE'.'|';      
			$this->salida .= "     <td width=\"50\">DURACIÓN</td>";
      $archivoPlano.='DURACIÓN'."\n";    
	}	  
	  
			
			$this->salida .= "</tr>";

			$datos=$this->BuscarFormaDetalleAgenda($_SESSION['recon1']['datos'][$i]['agenda_turno_id']);
			$k=0;
			$AgendaPadreIdAnt=-1;
			$ciclo1=sizeof($datos);
			$PacienteAnt=-1;
      $hhoraAnt=-1;
			while($k<$ciclo1)
      {
        $afiliacion = array();
        if($datos[$k]['tipo_id_paciente'])
          $afiliacion = $cls->ObtenerInformacionAfiliado($datos[$k]);
			  $AgendaPadreId=$datos[$k]['agenda_cita_id_padre'];
			  if($AgendaPadreId!=$AgendaPadreIdAnt && $PacienteAnt!=$datos[$k]['tipo_id_paciente'].'-'.$datos[$k]['paciente_id'])
        {
				  $kk=0;
					$contarows=1;
				  while($kk<$ciclo1){
					  $PacienteAnt11=-1;            
					  if($AgendaPadreId==$datos[$kk]['agenda_cita_id_padre'] && !empty($datos[$kk]['agenda_cita_id_padre']) && $datos[$k]['tipo_id_paciente'].'-'.$datos[$k]['paciente_id']==$datos[$kk]['tipo_id_paciente'].'-'.$datos[$kk]['paciente_id'] && $datos[$k]['hora']!=$datos[$kk]['hora']){
						  $contarows++;
						}
                              $kk++;
					}
          
					$this->salida .= "<tr $color1>";
					$this->salida .= "<td align=\"center\">";
					$this->salida .= "".($k+1)."";
          $archivoPlano.=($k+1).'|';      
					$this->salida .= "</td>";
					$this->salida .= "<td align=\"center\">";
					$this->salida .=  "".$_SESSION['recon1']['datos'][$i]['fecha_turno']."";//plan_id
          $archivoPlano.=$_SESSION['recon1']['datos'][$i]['fecha_turno'].'|';      
					$this->salida .= "</td>";
					$this->salida .= "<td align=\"center\">";
					$this->salida .= "".$datos[$k]['hora']."";
          $archivoPlano.=$datos[$k]['hora'].'|';      
					$this->salida .= "</td>";
					$this->salida .= "<td align=\"center\" rowspan=\"$contarows\">";
					$this->salida .= "".$_SESSION['recon1']['datos'][$i]['duracion']."";
          $archivoPlano.=$_SESSION['recon1']['datos'][$i]['duracion'].'|';      
					$this->salida .= "</td>";
					if($datosCargo[0]['sw_cargos_adicionales']!=1){	 
					$this->salida .= "<td rowspan=\"$contarows\">";
					$this->salida .= "".$_SESSION['recon1']['datos'][$i]['consultorio_id']."";
          $archivoPlano.=$_SESSION['recon1']['datos'][$i]['consultorio_id'].'|';      
					$this->salida .= "</td>";}
					$this->salida .= "<td rowspan=\"$contarows\">";
					$this->salida .= "".$datos[$k]['plan_descripcion']."";
          $archivoPlano.=$datos[$k]['plan_descripcion'].'|';      
					$this->salida .= "</td>";
					$this->salida .= "<td align=\"center\" rowspan=\"$contarows\">";
					if($datos[$k][sw_atencion]==1)
          {
						$this->salida .="CANCELADA";
            $archivoPlano.='CANCELADA'.'|';      
						$accionCon=ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaVerInfoCancelacion',array("tipocancel"=>$datos[$k]['tipocancel'],"obsercancel"=>$datos[$k]['obsercancel'],"fechacancel"=>$datos[$k]['fechacancel'],"hora"=>$datos[$k]['hora'],
						"tipoIdPaciente"=>$datos[$k]['tipo_id_paciente'],"PacienteId"=>$datos[$k]['paciente_id'],"NombrePaciente"=>$datos[$k]['nombre'],"fechaTurno"=>$_SESSION['recon1']['datos'][$i]['fecha_turno'],"nombreUsuario"=>$datos[$k]['nombre_usuario']));
						$this->salida .="<a href=\"$accionCon\"><img title=\"Consultar\" border=\"0\" src=\"".GetThemePath()."/images/pconsultar.png\"></a>";
					}
          elseif($datos[$k][sw_atencion]==3)
          {
						$this->salida .="ATENDIDA";
            $archivoPlano.='ATENDIDA'.'|';      
					}
          else
          {
						if($datos[$k][sw_estado]==2)
            {
							$this->salida .="PAGA";
              $archivoPlano.='PAGA'.'|';      
						}
            elseif($datos[$k][sw_estado]==3)
              {
  							$this->salida .="CUMPLIDA";
                $archivoPlano.='CUMPLIDA'.'|';      
  						}
              elseif($datos[$k][sw_agenda_citas]==3)
                {
    							$this->salida .="CANCELADA";
                  $archivoPlano.='CANCELADA'.'|';      
    						}
                else
                  {
      							$this->salida .="ACTIVA";
                    $archivoPlano.='ACTIVA'.'|';      
      						}
					}
					$this->salida .= "</td>";
					// aqui cambio naydu
					 $afiliacionCotizante = array();
					 $afiliacionCotizante = $cls->ObtenerCotizanteAfiliado($datos[$k]);
					if(count($afiliacionCotizante) > 0 ){
					$this->salida .= "<td align=\"center\" rowspan=\"$contarows\">".$afiliacionCotizante['tipo_id']." - ".$afiliacionCotizante['id']."</td>";
					}else{
					$this->salida .= "<td align=\"center\" rowspan=\"$contarows\"> </td>";
					}
				
					$this->salida .= "<td align=\"center\" rowspan=\"$contarows\">";
					if(!empty($datos[$k]['tipo_id_paciente']) && !empty($datos[$k]['paciente_id']))
          {
            $dato2=RetornarWinOpenDatosPaciente($datos[$k]['tipo_id_paciente'],$datos[$k]['paciente_id'],$datos[$k]['nombre']);
						$this->salida .=$dato2;
            $archivoPlano.=$datos[$k]['nombre'].'|'; 
          }
          elseif($datos[$k]['sw_agenda_citas']==3)
          {
            $this->salida .= "TURNO CANCELADO";
            $archivoPlano.='TURNO CANCELADO'.'|'; 
					}
          else
          {
						$this->salida .= "&nbsp;";
            $archivoPlano.='|'; 
					}
					$this->salida .= "</td>";

					$this->salida .= "<td rowspan=\"$contarows\">";
					if($datos[$k]['tipo_id_paciente'] && $datos[$k]['paciente_id'])
          {
						$this->salida .= "".$datos[$k]['tipo_id_paciente']."".' - '."".$datos[$k]['paciente_id']."";
            $archivoPlano.=$datos[$k]['tipo_id_paciente'].' - '.$datos[$k]['paciente_id'].'|'; 
					}
          else
          {
						$this->salida .= "&nbsp;";
            $archivoPlano.='|'; 
					}
					$this->salida .= "</td>";
					if($_SESSION['BusquedaAgenda']['sw_mostrar_historia']=='1')
          {
						$this->salida .="<td align=\"center\" rowspan=\"$contarows\">";
						if(!empty($datos[$k]['tipo_id_paciente']) and !empty($datos[$k]['paciente_id'])){
							$a=$this->BusquedaIngresoPaciente($datos[$k]['tipo_id_paciente'],$datos[$k]['paciente_id']);	if($a=='Historia Vacia'){
							$this->salida.=$a;              
							}else{
								$accion=ModuloHCGetURL($a,'',0,'','',array());
								$this->salida.="<a href='$accion'>Ver Historia</a>";
							}
						}
						$this->salida .= "</td>";
					}
          $label_edad = "&nbsp;";
          if($datos[$k]['edad_paciente']!= '')
          {
            $edad_paciente = explode(":",$datos[$k]['edad_paciente']);
            if($edad_paciente[0] > 0) 
              $label_edad = $edad_paciente[0]." año(s)";
            else if($edad_paciente[1] > 0) 
              $label_edad = $edad_paciente[1]." mes(es)";
            else
              $label_edad = $edad_paciente[2]." día(es)";
          }
					$this->salida .= "    <td rowspan=\"$contarows\">".$label_edad."</td>\n";
					$this->salida .= "    <td rowspan=\"$contarows\">".$datos[$k]['residencia_telefono']."</td>\n";
					if($datosCargo[0]['sw_cargos_adicionales']==1){
					//naydu este es otro
					$this->salida .= "  <td rowspan=\"".$contarows."\" align=\"center\">";
					if($datosn=$this->CargosCitasAdicionales($datos[$k]['agenda_cita_asignada_id'])){
					for ($n = 0; $n < count($datosn[0]); $n++){
							$this->salida .= "<table> <td rowspan=\"".$contarows."\" align=\"center\">";
							$this->salida .= "  <label class=\"normal_10AN\" >".$datosn[1][$n]."</label></td></table>";
					} 
					}
					}
					$this->salida .= "    <td rowspan=\"$contarows\">".$afiliacion['tipo_afiliado_atencion']."</td>\n";
					$this->salida .= "    <td rowspan=\"$contarows\">".$afiliacion['rango_afiliado_atencion']."</td>\n";
					$this->salida .= "    <td rowspan=\"$contarows\">".$afiliacion['eps_punto_atencion_nombre']."</td>\n";
					if($datosCargo[0]['sw_cargos_adicionales']!=1){
					$this->salida .= "<td align=\"center\" rowspan=\"$contarows\">".$datos[$k][fecha_abre]."</td>";
          $archivoPlano.=$datos[$k][fecha_abre].'|'; 
					$this->salida .= "<td align=\"center\" rowspan=\"$contarows\">".$datos[$k][fecha_cierre]."</td>";
          $archivoPlano.=$datos[$k][fecha_cierre].'|'; 
					$this->salida .= "<td align=\"center\" rowspan=\"$contarows\">".$datos[$k][fecha_duracion]."</td>";
          $archivoPlano.=$datos[$k][fecha_duracion]."\n";}
					
					$this->salida .= "</td>";
					$this->salida .= "</tr>";
					if($datos[$k]['tipo_id_paciente'] && $datos[$k]['paciente_id']){
					  $PacienteAnt=$datos[$k]['tipo_id_paciente'].'-'.$datos[$k]['paciente_id'];
					}
          $hhoraAnt=$datos[$k]['hora'];
				}
        else
        {
          $this->salida .= "<tr $color1>";
					$this->salida .= "<td align=\"center\">";
					$this->salida .= "".($k+1)."";
          $archivoPlano.=($k+1).'|'; 
					$this->salida .= "</td>";
					$this->salida .= "<td align=\"center\">";
					$this->salida .= "".$_SESSION['recon1']['datos'][$i]['fecha_turno']."";//plan_id
          $archivoPlano.=$_SESSION['recon1']['datos'][$i]['fecha_turno'].'|'; 
					$this->salida .= "</td>";
					$this->salida .= "<td align=\"center\">";
					$this->salida .= "".$datos[$k]['hora']."";
          $archivoPlano.=$datos[$k]['hora'].'|'; 
					$this->salida .= "</td>";
					//Cambio para que aparezcan citas caceladas y activas en la misma cita
					if($AgendaPadreIdAnt==$AgendaPadreId && $PacienteAnt!=$datos[$k]['tipo_id_paciente'].'-'.$datos[$k]['paciente_id'] || $hhoraAnt==$datos[$k]['hora']){
						$this->salida .= "<td align=\"center\" rowspan=\"$contarows\">";
						$this->salida .= "".$_SESSION['recon1']['datos'][$i]['duracion']."";
            $archivoPlano.=$_SESSION['recon1']['datos'][$i]['duracion'].'|'; 
						$this->salida .= "</td>";
						$this->salida .= "<td rowspan=\"$contarows\">";
						$this->salida .= "".$_SESSION['recon1']['datos'][$i]['consultorio_id']."";
            $archivoPlano.=$_SESSION['recon1']['datos'][$i]['consultorio_id'].'|'; 
						$this->salida .= "</td>";
						$this->salida .= "<td rowspan=\"$contarows\">";
						$this->salida .= "".$datos[$k]['plan_descripcion']."";
            $archivoPlano.=$datos[$k]['plan_descripcion'].'|'; 
						$this->salida .= "</td>";
						$this->salida .= "<td align=\"center\" rowspan=\"$contarows\">";
						if($datos[$k][sw_atencion]==1){
							$this->salida .="CANCELADA";
              $archivoPlano.='CANCELADA'.'|'; 
							$accionCon=ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaVerInfoCancelacion',array("tipocancel"=>$datos[$k]['tipocancel'],"obsercancel"=>$datos[$k]['obsercancel'],"fechacancel"=>$datos[$k]['fechacancel'],"hora"=>$datos[$k]['hora'],
							"tipoIdPaciente"=>$datos[$k]['tipo_id_paciente'],"PacienteId"=>$datos[$k]['paciente_id'],"NombrePaciente"=>$datos[$k]['nombre'],"fechaTurno"=>$_SESSION['recon1']['datos'][$i]['fecha_turno']));
							$this->salida .="<a href=\"$accionCon\"><img title=\"Consultar\" border=\"0\" src=\"".GetThemePath()."/images/pconsultar.png\"></a>";
						}elseif($datos[$k][sw_atencion]==3){
							$this->salida .="ATENDIDA";
              $archivoPlano.='ATENDIDA'.'|'; 
						}else{
							if($datos[$k][sw_estado]==2){
								$this->salida .="PAGA";
                $archivoPlano.='PAGA'.'|'; 
							}elseif($datos[$k][sw_estado]==3){
								$this->salida .="CUMPLIDA";
                $archivoPlano.='CUMPLIDA'.'|'; 
							}elseif($datos[$k][sw_agenda_citas]==3){
								$this->salida .="CANCELADA";
                $archivoPlano.='CANCELADA'.'|'; 
							}else{
								$this->salida .="ACTIVA";
                $archivoPlano.='ACTIVA'.'|'; 
							}
						}
						$this->salida .= "</td>";
						// aqui cambio naydu
					 $afiliacionCotizante = array();
					 $afiliacionCotizante = $cls->ObtenerCotizanteAfiliado($datos[$k]);
					if(count($afiliacionCotizante) > 0 ){
					$this->salida .= "<td align=\"center\" rowspan=\"$contarows\">".$afiliacionCotizante['tipo_id']." - ".$afiliacionCotizante['id']."</td>";
					}else{
					$this->salida .= "<td align=\"center\" rowspan=\"$contarows\"> </td>";
					}
					
						$this->salida .= "<td align=\"center\" rowspan=\"$contarows\">";
						if(!empty($datos[$k]['tipo_id_paciente']) && !empty($datos[$k]['paciente_id'])){
							$dato2=RetornarWinOpenDatosPaciente($datos[$k]['tipo_id_paciente'],$datos[$k]['paciente_id'],$datos[$k]['nombre']);
							$this->salida .=$dato2;
              $archivoPlano.=$datos[$k]['nombre'].'|'; 
						}elseif($datos[$k]['sw_agenda_citas']==3){
							$this->salida .= "TURNO CANCELADO";
              $archivoPlano.='TURNO CANCELADO'.'|'; 
						}else{
							$this->salida .= "&nbsp;";
              $archivoPlano.='|'; 
						}
						$this->salida .= "</td>";
						$this->salida .= "<td rowspan=\"$contarows\">";
						if($datos[$k]['tipo_id_paciente'] && $datos[$k]['paciente_id']){
							$this->salida .= "".$datos[$k]['tipo_id_paciente']."".' - '."".$datos[$k]['paciente_id']."";
              $archivoPlano.=$datos[$k]['tipo_id_paciente'].' - '.$datos[$k]['paciente_id'].'|'; 
						}else{
							$this->salida .= "&nbsp;";
              $archivoPlano.='|'; 
						}
						$this->salida .= "</td>";
						if($_SESSION['BusquedaAgenda']['sw_mostrar_historia']=='1'){
							$this->salida .="<td align=\"center\" rowspan=\"$contarows\">";
							if(!empty($datos[$k]['tipo_id_paciente']) and !empty($datos[$k]['paciente_id'])){
								$a=$this->BusquedaIngresoPaciente($datos[$k]['tipo_id_paciente'],$datos[$k]['paciente_id']);	if($a=='Historia Vacia'){
								$this->salida.=$a;
								}else{
									$accion=ModuloHCGetURL($a,'',0,'','',array());
									$this->salida.="<a href='$accion'>Ver Historia</a>";
								}
							}
							$this->salida .= "</td>";
						}
            $label_edad = "&nbsp;";
            if($datos[$k]['edad_paciente']!= '')
            {
              $edad_paciente = explode(":",$datos[$k]['edad_paciente']);
              if($edad_paciente[0] > 0) 
                $label_edad = $edad_paciente[0]." año(s)";
              else if($edad_paciente[1] > 0) 
                $label_edad = $edad_paciente[1]." mes(es)";
              else
                $label_edad = $edad_paciente[2]." día(es)";
            }
            $this->salida .= "    <td rowspan=\"$contarows\">".$label_edad."</td>\n";
            $this->salida .= "    <td rowspan=\"$contarows\">".$datos[$k]['residencia_telefono']."</td>\n";
            $this->salida .= "    <td rowspan=\"$contarows\">".$afiliacion['tipo_afiliado_atencion']."</td>\n";
            $this->salida .= "    <td rowspan=\"$contarows\">".$afiliacion['rango_afiliado_atencion']."</td>\n";
            $this->salida .= "    <td rowspan=\"$contarows\">".$afiliacion['eps_punto_atencion_nombre']."</td>\n";
						$this->salida .= "<td align=\"center\" rowspan=\"$contarows\">".$datos[$k][fecha_abre]."</td>";
            $archivoPlano.=$datos[$k][fecha_abre].'|'; 
						$this->salida .= "<td align=\"center\" rowspan=\"$contarows\">".$datos[$k][fecha_cierre]."</td>";
            $archivoPlano.=$datos[$k][fecha_cierre].'|'; 
						$this->salida .= "<td align=\"center\" rowspan=\"$contarows\">".$datos[$k][fecha_duracion]."</td>";
            $archivoPlano.=$datos[$k][fecha_duracion]."\n"; 
					}
          $hhoraAnt=$datos[$k]['hora'];
					//Fin Cambio
					$this->salida .= "</tr>";
				}
				if(!empty($AgendaPadreId)){
					$AgendaPadreIdAnt=$AgendaPadreId;
				}
				$k++;
			}
			$this->salida .= "</table>";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$i++;
		}
		if(empty($_SESSION['recon1']['datos']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"6\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRARON DATOS'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td align=\"center\" colspan=\"6\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"button\" name=\"imprimir1\" value=\"IMPRIMIR REPORTE\" onclick=\"javascript:$funcion\">";
		$this->salida .="       </td></tr>";
		$this->salida .= "  </table>";
		$this->salida .= "  </form>";
		$this->salida .= "  </td></tr>";
		(list($dia,$mes,$ano)=explode('/',$_REQUEST['feinictra']));
          $_REQUEST['DiaEspe']=$ano.'-'.$mes.'-'.$dia;
		$dato1['DiaEspe']=$_REQUEST['DiaEspe'];

		$dato=$this->CitasDia($_REQUEST['DiaEspe'],$_REQUEST['DiaEspe'],$_REQUEST['DiaEspe']);		$_SESSION['BusquedaAgenda']['datos_impresion']=$dato;
		$accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','FuncionParaImprimir',$dato1);
		$this->salida .="   <tr><td>";
		$this->salida .="   <table border=0 align=\"right\">";
		$this->salida .="   <tr>";
		$this->salida .="   <td align=\"right\"><a href=\"$accion\">Imprimir Resumen</a></td>";
		$this->salida .="   </tr>";
		$this->salida .="   </table>";
		$this->salida .="   </td></tr>";
    
    $this->salida .="   <tr><td>";
    $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";        
    $this->salida .= "      <tr><td class=\"label\" align=\"center\">";
    $_SESSION['DESCARGA_DATOS_REPORTES']['DATOS']=$archivoPlano;            
    (list($ano,$mes,$dia)=explode('-',$_SESSION['recone']['fechadesde']));
    $feinictra=$dia.'/'.$mes.'/'.$ano;
    (list($ano,$mes,$dia)=explode('-',$_SESSION['recone']['fechahasta']));
    $fechahasta=$dia.'/'.$mes.'/'.$ano;
    $descarga=ModuloGetURL('app','Reportes_Consulta_Externa','user','DescargaDatosAgendasMedicas',array('centroutilidad'=>$_REQUEST['centroutilidad'],'unidadfunc'=>$_REQUEST['unidadfunc'],
    'departamento'=>$_REQUEST['departamento'],'profesional_escojer'=>$_REQUEST['profesional_escojer'],"feinictra"=>$feinictra,"fefinctra"=>$fechahasta,
    'centroU'=>$_REQUEST['centroU'],'unidadF'=>$_REQUEST['unidadF'],'DptoSel'=>$_REQUEST['DptoSel']));          
    $this->salida .= "      <a href=\"$descarga\">Descargar Datos <img title=\"Descargar Archivo\" border=\"0\" src=\"".GetThemePath()."/images/abajo.png\"></a>";
    $this->salida .="       </td></tr>";
    $this->salida .= "      </table><BR>"; 
    $this->salida .="   </td></tr>";
    
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\"><br>";
		if($_SESSION['REPORTES_CONSULTA_EXTERNA']['RETORNO'] && $_SESSION['REPORTES_CONSULTA_EXTERNA']['RETORNO']['filtro']!=1){
     		$accion=ModuloGetURL($_SESSION['REPORTES_CONSULTA_EXTERNA']['RETORNO']['contenedor'],$_SESSION['REPORTES_CONSULTA_EXTERNA']['RETORNO']['modulo'],$_SESSION['REPORTES_CONSULTA_EXTERNA']['RETORNO']['tipo'],$_SESSION['REPORTES_CONSULTA_EXTERNA']['RETORNO']['metodo'],$_SESSION['REPORTES_CONSULTA_EXTERNA']['RETORNO']['variables']);
		}else{
     		$accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaFormaSeleccion',array("depto"=>$_REQUEST['depto'],"tipoconsul"=>$_REQUEST['tipoconsul'],"profesional"=>$_REQUEST['profesional'],"feinictra"=>$_REQUEST['feinictra'],"fefinctra"=>$_REQUEST['fefinctra'],
               'centroutilidad'=>$_REQUEST['centroutilidad'],'unidadfunc'=>$_REQUEST['unidadfunc'],
          	'departamento'=>$_REQUEST['departamento'],'centroU'=>$_REQUEST['centroU'],'unidadF'=>$_REQUEST['unidadF'],'DptoSel'=>$_REQUEST['DptoSel']));
		}
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function VerInfoCancelacion($tipocancel,$obsercancel,$fechacancel,$hora,
		$tipoIdPaciente,$PacienteId,$NombrePaciente,$fechaTurno,$nombreUsuario)
     {
		$this->salida = ThemeAbrirTabla('DATOS CANCELACION CITA');
		$this->salida .= "  <table border=\"0\" width=\"50%\" align=\"center\">";
		$this->salida .= "  <tr class=\"modulo_table_list_title\"><td width=\"20%\" colspan=\"2\">".$tipoIdPaciente." ".$PacienteId." - ".$NombrePaciente."</td></tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\"><td width=\"30%\" class=\"label\">FECHA TURNO</td><td>".$fechaTurno."</td></tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\"><td width=\"30%\" class=\"label\">HORA TURNO</td><td>".$hora."</td></tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\"><td width=\"30%\" class=\"label\">JUSTIFICACION</td><td>".$tipocancel."</td></tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\"><td width=\"30%\" class=\"label\">OBSERVACIONES</td><td>".$obsercancel."</td></tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\"><td width=\"30%\" class=\"label\">FECHA CANCELACION</td><td>".$fechacancel."</td></tr>";
    $this->salida .= "  <tr class=\"modulo_list_claro\"><td width=\"30%\" class=\"label\">USUARIO CANCELO</td><td>".$nombreUsuario."</td></tr>";
		$accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','FormaAgendaMedica');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <tr class=\"modulo_list_claro\"><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\"></td></tr>";
		$this->salida .= "  </form>";
		$this->salida .= "	</table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

     function FormaReportesCancelacionCitas($centroutilidad,$centroU,$unidadfunc,$unidadF,$departamento,$DptoSel)
     {    
          unset($_SESSION['reconecc']);
          unset($_SESSION['CITAS_CANCELADAS']['DATOS']);
          $this->salida = ThemeAbrirTabla('FILTRO DE DATOS PARA EL REPORTE DE MOTIVOS DE CANCELACION DE CITAS MÉDICAS');
          $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
          $this->salida .= "  <tr><td>";
          $this->Cabecera();
          $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
          $this->salida .=    $this->SetStyle("MensajeError");
          $this->salida .= "  </table><br>";

          /*$RUTA = "app_modules/Reportes_Consulta_Externa/buscador.php?sign=";
          $mostrar ="\n<script language='javascript'>\n";
          $mostrar.="var rem=\"\";\n";
          $mostrar.="  function xxx(a){\n";
          $mostrar.="    var nombre=\"\"\n";
          $mostrar.="    var url2=\"\"\n";
          $mostrar.="    var str=\"\"\n";
          $mostrar.="    var nombre=\"REPORTE\";\n";
          $mostrar.="    var str =\"width=450,height=180,resizable=no,location=no, status=no,scrollbars=yes\";\n";
          $mostrar.="    var url2 ='$RUTA';\n";
          $mostrar.="    url2 +=a;\n";
          $mostrar.="    rem = window.open(url2, nombre, str)};\n";
		$mostrar.="</script>\n";
          $this->salida .= $mostrar;
*/
          $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaReporteCitasCanceladas');
          $this->salida .= "  <form name=\"data\" action=\"$accion\" method=\"post\">";
          $this->salida .= "  		<table border=\"0\" width=\"100%\" align=\"center\">";
          $this->salida .= "  		<tr>";
          $this->salida .= "  		<td>";
          $this->salida .= "  		<fieldset><legend class=\"field\">INGRESO DE DATOS</legend>";
          $this->salida .= "    	<table border=\"0\" width=\"95%\" align=\"center\">";
          
          /*$this->salida .= "<tr><td width=\"15%\" class=\"label\">CENTRO UTILIDAD: </td>";
          $this->salida .= "<td width=\"15%\"><input type=\"text\" class=\"input-text\" name=\"centroutilidad\" value=\"".$_REQUEST['centroutilidad']."\" maxlength=\"100\" size=\"40\" readonly></td></tr>";
          $this->salida .= "<tr><td width=\"20%\" class=\"label\">UNIDAD FUNCIONAL: </td>";
          $this->salida .= "<td width=\"15%\"><input type=\"text\" class=\"input-text\" name=\"unidadfunc\" value=\"".$_REQUEST['unidadfunc']."\" maxlength=\"100\" size=\"40\" readonly></td></tr>";
          $this->salida .= "<tr><td width=\"15%\" class=\"label\">DEPARTAMENTO: </td>";
          $this->salida .= "<td width=\"15%\"><input type=\"text\" class=\"input-text\" name=\"departamento\" value=\"".$_REQUEST['departamento']."\" maxlength=\"100\" size=\"40\" readonly>";
          $this->salida .= "&nbsp;<a href=\"javascript:xxx(1)\"><img title='Busqueda del Departamento' src=\"". GetThemePath() ."/images/auditoria.png\" border='0' width='14' height='14'></td></tr>";
          */
					$this->salida .= "<input type=\"hidden\" name=\"centroutilidad\" value=\"".$centroutilidad."\" class=\"input-text\">";
					$this->salida .= "<input type=\"hidden\" name=\"unidadfunc\" value=\"".$unidadfunc."\" class=\"input-text\">";
					$this->salida .= "<input type=\"hidden\" name=\"departamento\" value=\"".$departamento."\" class=\"input-text\">";
					$this->salida .= "<input type=\"hidden\" name=\"centroU\" value=\"".$centroU."\" class=\"input-text\">";
					$this->salida .= "<input type=\"hidden\" name=\"unidadF\" value=\"".$unidadF."\" class=\"input-text\">";
					$this->salida .= "<input type=\"hidden\" name=\"DptoSel\" value=\"".$DptoSel."\" class=\"input-text\">";          
          
          $this->salida .= "      <tr>";
          $this->salida .= "      <td class=\"label\" width=\"50%\">PROFESIONAL:";
          $this->salida .= "      </td>";
          $this->salida .= "      <td>";
          $this->salida .= "      <select name=\"profesional\" class=\"select\">";          
          //$consul=$this->BuscarProf($_REQUEST['depto'],$_REQUEST['tipoconsul']);
          $consul=$this->Get_Profesionales();
          if(sizeof($consul)>1){
            $this->salida .= "      <option value=\"-1\">--  SELECCIONE--</option>";
          }
          $a=explode(',',$_REQUEST['profesional']);
          for($i=0;$i<sizeof($consul);$i++)
		{
               if($consul[$i]['tipo_id_tercero']==$a[0] AND $consul[$i]['tercero_id']==$a[1]){
                    $this->salida .="<option value=\"".$consul[$i]['tipo_id_tercero']."".','."".$consul[$i]['tercero_id']."".','."".$consul[$i]['nombre']."\" selected>".$consul[$i]['nombre']."</option>";
               }
               else{
                    $this->salida .="<option value=\"".$consul[$i]['tipo_id_tercero']."".','."".$consul[$i]['tercero_id']."".','."".$consul[$i]['nombre']."\">".$consul[$i]['nombre']."</option>";
               }
          }

          $this->salida .= "      </select>";
          $this->salida .= "      </td>";
          $this->salida .= "      </tr>";
          $this->salida .= "      <tr>";
          $this->salida .= "      <td class=\"label\" width=\"50%\">JUSTIFICACIONES:";
          $this->salida .= "      </td>";		$this->salida .= "      <td width=\"50%\">";
          $this->salida .= "      <select name=\"justificacion\" class=\"select\">";
          $this->salida .= "      <option value=\"-1\" selected>--  TODOS  --</option>";
          $justificaciones=$this->BuscarJustificacion();
          $a=explode(',',$_REQUEST['justificacion']);
          for($i=0;$i<sizeof($justificaciones);$i++){
          if($justificaciones[$i]['tipo_cancelacion_id']==$a[0]){
               $this->salida .="<option value=\"".$justificaciones[$i]['tipo_cancelacion_id']."".','."".$justificaciones[$i]['descripcion']."\"selected>".$justificaciones[$i]['descripcion']."</option>";
               }else{
                    $this->salida .="<option value=\"".$justificaciones[$i]['tipo_cancelacion_id']."".','."".$justificaciones[$i]['descripcion']."\">".$justificaciones[$i]['descripcion']."</option>";
               }
          }
          $this->salida .= "      </select>";
          $this->salida .= "      </td>";
          $this->salida .= "      </tr>";
          $this->salida .= "      <tr>";
          $this->salida .= "      <td width=\"50%\">";
          $this->salida .= "      <label class=\"label\">FECHA INICIAL:</label>";
          $this->salida .= "      </td>";
          $this->salida .= "      <td class=\"label\">";
          if(!$_REQUEST['feinictra']){
               $_REQUEST['feinictra']=date('01/m/Y');
          }
          $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"feinictra\" value=\"".$_REQUEST['feinictra']."\" maxlength=\"10\" size=\"10\">";
          $this->salida .= "      ".ReturnOpenCalendario('data','feinictra','/')."";
          $this->salida .= "      </td>";
          $this->salida .= "      </tr>";
          $this->salida .= "      <tr>";
          $this->salida .= "      <td width=\"50%\">";
          $this->salida .= "      <label class=\"label\">FECHA FINAL:</label>";
          $this->salida .= "      </td>";
          $this->salida .= "      <td class=\"label\">";
          if(!$_REQUEST['fefinctra']){
               $_REQUEST['fefinctra']=date('d/m/Y');
          }
          $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fefinctra\" value=\"".$_REQUEST['fefinctra']."\" maxlength=\"10\" size=\"10\">";
          $this->salida .= "      ".ReturnOpenCalendario('data','fefinctra','/')."";
          $this->salida .= "      </td>";
          $this->salida .= "      </tr>";
          $this->salida .= "      <tr>";
          $this->salida .= "      <td colspan=\"2\" align=\"center\"><br>";
          $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"GENERAR REPORTE\">";
          $this->salida .= "      </td>";
          $this->salida .= "  		</form>";
          $this->salida .= "      </tr>";
          $this->salida .= "  		</fieldset>";
          $this->salida .= "  		</table><br>";
          $this->salida .= "  		</td>";
          $this->salida .= "  		</tr>";
          $this->salida .= "  		</table>";
          $this->salida .= "  </td></tr>";
          $this->salida .= "  <tr>";
          $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','GuardarSeleccionDepartamentoUnificado',array("centroutilidad"=>$centroutilidad,"centroU"=>$centroU,"unidadfunc"=>$unidadfunc,"unidadF"=>$unidadF,"departamento"=>$departamento,"DptoSel"=>$DptoSel));
          $this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
          $this->salida .= "  <td align=\"center\">";
          $this->salida.= "   <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
          $this->salida .= "  </td>";
          $this->salida .= "  </form>";
          $this->salida .= "  </tr>";
          $this->salida .= "	</table>";
          $this->salida .= ThemeCerrarTabla();
          return true;
     }
		 
		 function FormaReporteHCAbiertasCerradas($centroutilidad,$centroU,$unidadfunc,$unidadF,$departamento,$DptoSel)
     {    
          unset($_SESSION['reconecc']);
          unset($_SESSION['CITAS_CANCELADAS']['DATOS']);
          $this->salida = ThemeAbrirTabla('FILTRO DE DATOS PARA EL REPORTE DE HISTORIAS CLINICAS ABIERTAS Y CERRADAS');
          $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
          $this->salida .= "  <tr><td>";
          $this->Cabecera();
          $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
          $this->salida .=    $this->SetStyle("MensajeError");
          $this->salida .= "  </table><br>";

 /*         $RUTA = "app_modules/Reportes_Consulta_Externa/buscador.php?sign=";
          $mostrar ="\n<script language='javascript'>\n";
          $mostrar.="var rem=\"\";\n";
          $mostrar.="  function xxx(a){\n";
          $mostrar.="    var nombre=\"\"\n";
          $mostrar.="    var url2=\"\"\n";
          $mostrar.="    var str=\"\"\n";
          $mostrar.="    var nombre=\"REPORTE\";\n";
          $mostrar.="    var str =\"width=450,height=180,resizable=no,location=no, status=no,scrollbars=yes\";\n";
          $mostrar.="    var url2 ='$RUTA';\n";
          $mostrar.="    url2 +=a;\n";
          $mostrar.="    rem = window.open(url2, nombre, str)};\n";
		$mostrar.="</script>\n";
          $this->salida .= $mostrar;
*/
          $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaReporteHCAbiertasyCerradas');
          $this->salida .= "  <form name=\"data\" action=\"$accion\" method=\"post\">";
          $this->salida .= "  		<table border=\"0\" width=\"100%\" align=\"center\">";
          $this->salida .= "  		<tr>";
          $this->salida .= "  		<td>";
          $this->salida .= "  		<fieldset><legend class=\"field\">INGRESO DE DATOS</legend>";
          $this->salida .= "    	<table border=\"0\" width=\"95%\" align=\"center\">";
          
          /*$this->salida .= "<tr><td width=\"15%\" class=\"label\">CENTRO UTILIDAD: </td>";
          $this->salida .= "<td width=\"15%\"><input type=\"text\" class=\"input-text\" name=\"centroutilidad\" value=\"".$_REQUEST['centroutilidad']."\" maxlength=\"100\" size=\"40\" readonly></td></tr>";
          $this->salida .= "<tr><td width=\"20%\" class=\"label\">UNIDAD FUNCIONAL: </td>";
          $this->salida .= "<td width=\"15%\"><input type=\"text\" class=\"input-text\" name=\"unidadfunc\" value=\"".$_REQUEST['unidadfunc']."\" maxlength=\"100\" size=\"40\" readonly></td></tr>";
          $this->salida .= "<tr><td width=\"15%\" class=\"label\">DEPARTAMENTO: </td>";
          $this->salida .= "<td width=\"15%\"><input type=\"text\" class=\"input-text\" name=\"departamento\" value=\"".$_REQUEST['departamento']."\" maxlength=\"100\" size=\"40\" readonly>";
          $this->salida .= "&nbsp;<a href=\"javascript:xxx(1)\"><img title='Busqueda del Departamento' src=\"". GetThemePath() ."/images/auditoria.png\" border='0' width='14' height='14'></td></tr>";
          */
					$this->salida .= "<input type=\"hidden\" name=\"centroutilidad\" value=\"".$centroutilidad."\" class=\"input-text\">";
					$this->salida .= "<input type=\"hidden\" name=\"unidadfunc\" value=\"".$unidadfunc."\" class=\"input-text\">";
					$this->salida .= "<input type=\"hidden\" name=\"departamento\" value=\"".$departamento."\" class=\"input-text\">";
					$this->salida .= "<input type=\"hidden\" name=\"centroU\" value=\"".$centroU."\" class=\"input-text\">";
					$this->salida .= "<input type=\"hidden\" name=\"unidadF\" value=\"".$unidadF."\" class=\"input-text\">";
					$this->salida .= "<input type=\"hidden\" name=\"DptoSel\" value=\"".$DptoSel."\" class=\"input-text\">";
		
          
          
          $this->salida .= "      <tr>";
          $this->salida .= "      <td class=\"label\" width=\"50%\">PROFESIONAL:";
          $this->salida .= "      </td>";
          $this->salida .= "      <td>";
          $this->salida .= "      <select name=\"profesional\" class=\"select\">";          
          //$consul=$this->BuscarProf($_REQUEST['depto'],$_REQUEST['tipoconsul']);
          $consul=$this->Get_Profesionales();
          if(sizeof($consul)>1){
            $this->salida .= "      <option value=\"-1\">--  SELECCIONE--</option>";
          }
          $a=explode(',',$_REQUEST['profesional']);
          for($i=0;$i<sizeof($consul);$i++)
		{
               if($consul[$i]['tipo_id_tercero']==$a[0] AND $consul[$i]['tercero_id']==$a[1]){
                    $this->salida .="<option value=\"".$consul[$i]['tipo_id_tercero']."".','."".$consul[$i]['tercero_id']."".','."".$consul[$i]['nombre']."\" selected>".$consul[$i]['nombre']."</option>";
               }
               else{
                    $this->salida .="<option value=\"".$consul[$i]['tipo_id_tercero']."".','."".$consul[$i]['tercero_id']."".','."".$consul[$i]['nombre']."\">".$consul[$i]['nombre']."</option>";
               }
          }

          $this->salida .= "      </select>";
          $this->salida .= "      </td>";
          $this->salida .= "      </tr>";          
          $this->salida .= "      <tr>";
          $this->salida .= "      <td width=\"50%\">";
          $this->salida .= "      <label class=\"label\">FECHA INICIAL:</label>";
          $this->salida .= "      </td>";
          $this->salida .= "      <td class=\"label\">";
          if(!$_REQUEST['feinictra']){
               $_REQUEST['feinictra']=date('01/m/Y');
          }
          $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"feinictra\" value=\"".$_REQUEST['feinictra']."\" maxlength=\"10\" size=\"10\">";
          $this->salida .= "      ".ReturnOpenCalendario('data','feinictra','/')."";
          $this->salida .= "      </td>";
          $this->salida .= "      </tr>";
          $this->salida .= "      <tr>";
          $this->salida .= "      <td width=\"50%\">";
          $this->salida .= "      <label class=\"label\">FECHA FINAL:</label>";
          $this->salida .= "      </td>";
          $this->salida .= "      <td class=\"label\">";
          if(!$_REQUEST['fefinctra']){
               $_REQUEST['fefinctra']=date('d/m/Y');
          }
          $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fefinctra\" value=\"".$_REQUEST['fefinctra']."\" maxlength=\"10\" size=\"10\">";
          $this->salida .= "      ".ReturnOpenCalendario('data','fefinctra','/')."";
          $this->salida .= "      </td>";
          $this->salida .= "      </tr>";
          $this->salida .= "      <tr>";
          $this->salida .= "      <td colspan=\"2\" align=\"center\"><br>";
          $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"GENERAR REPORTE\">";
          $this->salida .= "      </td>";
          $this->salida .= "  		</form>";
          $this->salida .= "      </tr>";
          $this->salida .= "  		</fieldset>";
          $this->salida .= "  		</table><br>";
          $this->salida .= "  		</td>";
          $this->salida .= "  		</tr>";
          $this->salida .= "  		</table>";
          $this->salida .= "  </td></tr>";
          $this->salida .= "  <tr>";
          $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','GuardarSeleccionDepartamentoUnificado',array("centroutilidad"=>$centroutilidad,"centroU"=>$centroU,"unidadfunc"=>$unidadfunc,"unidadF"=>$unidadF,"departamento"=>$departamento,"DptoSel"=>$DptoSel));
          $this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
          $this->salida .= "  <td align=\"center\">";
          $this->salida.= "   <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
          $this->salida .= "  </td>";
          $this->salida .= "  </form>";
          $this->salida .= "  </tr>";
          $this->salida .= "	</table>";
          $this->salida .= ThemeCerrarTabla();
          return true;
     }
		/**
    * Funcion donde se crea el buscador del reporte de cancelacion de citas consolidado
    *
    * @param string $centroutilidad Descripcion del centro de utilidad
    * @param string $centroU Referencia al centro de utilidad
    * @param string $unidadfunc Descripcion de la unidad funcional
    * @param string $unidadF Referencia a la unidad funcional
    * @param string $departamento Descripcion del departamento
    * @param string $DptoSel Referencia al departamento
    * @param array $planes Arreglo de datos de los planes que son de afiliados
    *
    * @return boolean
    */
		function FormaReportesCancelacionCitasConsolidado($centroutilidad,$centroU,$unidadfunc,$unidadF,$departamento,$DptoSel,$planes)
    {
      unset($_SESSION['reconecc']);
      unset($_SESSION['CITAS_CANCELADAS']['DATOS']);
      $this->salida  = "<script>\n";
      $this->salida .= "  function textoPlan(objeto)\n";
      $this->salida .= "  {\n";
      $this->salida .= "    objeto.descripcion_plan.value= objeto.plan_afiliacion.options[objeto.plan_afiliacion.selectedIndex].text; \n";
      $this->salida .= "  }\n";
      $this->salida .= "</script>\n";
      $this->salida .= ThemeAbrirTabla('FILTRO DE DATOS PARA EL REPORTE DE MOTIVOS DE CANCELACION DE CITAS MÉDICAS CONSOLIDADO');
      $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
      $this->salida .= "  <tr><td>";
      $this->Cabecera();
      $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
      $this->salida .=    $this->SetStyle("MensajeError");
      $this->salida .= "  </table><br>";

      $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaReporteCitasCanceladasConsolidado');
      $this->salida .= "  <form name=\"data\" action=\"$accion\" method=\"post\">";
      $this->salida .= "    <input type=\"hidden\" name=\"centroutilidad\" value=\"".$centroutilidad."\" class=\"input-text\">";
      $this->salida .= "    <input type=\"hidden\" name=\"unidadfunc\" value=\"".$unidadfunc."\" class=\"input-text\">";
      $this->salida .= "    <input type=\"hidden\" name=\"departamento\" value=\"".$departamento."\" class=\"input-text\">";
      $this->salida .= "    <input type=\"hidden\" name=\"centroU\" value=\"".$centroU."\" class=\"input-text\">";
      $this->salida .= "    <input type=\"hidden\" name=\"unidadF\" value=\"".$unidadF."\" class=\"input-text\">";
      $this->salida .= "    <input type=\"hidden\" name=\"DptoSel\" value=\"".$DptoSel."\" class=\"input-text\">";
      $this->salida .= "    <input type=\"hidden\" name=\"descripcion_plan\" value=\"".$_REQUEST['descripcion_plan']."\">\n";
      $this->salida .= "    <table border=\"0\" width=\"100%\" align=\"center\">";
      $this->salida .= "  	  <tr>";
      $this->salida .= "  		  <td>";
      $this->salida .= "  		    <fieldset class=\"fieldset\"><legend class=\"normal_10AN\">INGRESO DE DATOS</legend>";
      $this->salida .= "    	      <table border=\"0\" width=\"95%\" align=\"center\">";
      $this->salida .= "              <tr class=\"label\" >";
      $this->salida .= "                <td width=\"50%\">PROFESIONAL:</td>";
      $this->salida .= "                <td>";
      $this->salida .= "                  <select name=\"profesional\" class=\"select\">";          
      //$consul=$this->BuscarProf($_REQUEST['depto'],$_REQUEST['tipoconsul']);
      $consul=$this->Get_Profesionales();
      if(sizeof($consul)>1)
      {
        $this->salida .= "                    <option value=\"-1\">--  SELECCIONE--</option>";
      }
      $a=explode(',',$_REQUEST['profesional']);
      for($i=0;$i<sizeof($consul);$i++)
      {
        if($consul[$i]['tipo_id_tercero']==$a[0] AND $consul[$i]['tercero_id']==$a[1])
        {
          $this->salida .="<option value=\"".$consul[$i]['tipo_id_tercero']."".','."".$consul[$i]['tercero_id']."".','."".$consul[$i]['nombre']."\" selected>".$consul[$i]['nombre']."</option>";
        }
        else
        {
          $this->salida .="<option value=\"".$consul[$i]['tipo_id_tercero']."".','."".$consul[$i]['tercero_id']."".','."".$consul[$i]['nombre']."\">".$consul[$i]['nombre']."</option>";
        }
      }
      $this->salida .= "                  </select>";
      $this->salida .= "                </td>";
      $this->salida .= "              </tr>";
      $this->salida .= "              <tr class=\"label\">";
      $this->salida .= "                <td >JUSTIFICACIONES:</td>";		
      $this->salida .= "                <td >";
      $this->salida .= "                  <select name=\"justificacion\" class=\"select\">";
      $this->salida .= "                    <option value=\"-1\" selected>--  TODOS  --</option>";
      $justificaciones=$this->BuscarJustificacion();
      $a=explode(',',$_REQUEST['justificacion']);
      for($i=0;$i<sizeof($justificaciones);$i++){
      if($justificaciones[$i]['tipo_cancelacion_id']==$a[0]){
           $this->salida .="<option value=\"".$justificaciones[$i]['tipo_cancelacion_id']."".','."".$justificaciones[$i]['descripcion']."\"selected>".$justificaciones[$i]['descripcion']."</option>";
           }else{
                $this->salida .="<option value=\"".$justificaciones[$i]['tipo_cancelacion_id']."".','."".$justificaciones[$i]['descripcion']."\">".$justificaciones[$i]['descripcion']."</option>";
           }
      }
      $this->salida .= "                  </select>";
      $this->salida .= "                </td>";
      $this->salida .= "              </tr>";
      
      $this->salida .= "              <tr class=\"label\">\n";
      $this->salida .= "                <td >PLAN DE AFILIACIÓN:</td>\n";		
      $this->salida .= "                <td >\n";
      $this->salida .= "                  <select name=\"plan_afiliacion\" class=\"select\" onchange=\"textoPlan(document.data)\">\n";
      $this->salida .= "                    <option value=\"-1\" selected>-- SELECCIONAR --</option>\n";
      $s = "";
      foreach($planes as $key => $dtl)
      {
        ($key == $_REQUEST['plan_afiliacion'])? $s = "selected": $s = "";
        $this->salida .= "                    <option value=\"".$key."\" ".$s.">".$dtl['plan_descripcion']."</option>\n";
      }
      $this->salida .= "                  </select>\n";
      $this->salida .= "                </td>\n";
      $this->salida .= "              </tr>\n";
      
      $this->salida .= "      <tr>";
      $this->salida .= "      <td width=\"50%\">";
      $this->salida .= "      <label class=\"label\">FECHA INICIAL:</label>";
      $this->salida .= "      </td>";
      $this->salida .= "      <td class=\"label\">";
      if(!$_REQUEST['feinictra']){
           $_REQUEST['feinictra']=date('01/m/Y');
      }
      $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"feinictra\" value=\"".$_REQUEST['feinictra']."\" maxlength=\"10\" size=\"10\">";
      $this->salida .= "      ".ReturnOpenCalendario('data','feinictra','/')."";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr>";
      $this->salida .= "      <td width=\"50%\">";
      $this->salida .= "      <label class=\"label\">FECHA FINAL:</label>";
      $this->salida .= "      </td>";
      $this->salida .= "      <td class=\"label\">";
      if(!$_REQUEST['fefinctra']){
           $_REQUEST['fefinctra']=date('d/m/Y');
      }
      $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fefinctra\" value=\"".$_REQUEST['fefinctra']."\" maxlength=\"10\" size=\"10\">";
      $this->salida .= "      ".ReturnOpenCalendario('data','fefinctra','/')."";
      $this->salida .= "      </td>";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr>";
      $this->salida .= "      <td colspan=\"2\" align=\"center\"><br>";
      $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"GENERAR REPORTE\">";
      $this->salida .= "      </td>";
      $this->salida .= "  		</form>";
      $this->salida .= "      </tr>";
      $this->salida .= "  		</fieldset>";
      $this->salida .= "  		</table><br>";
      $this->salida .= "  		</td>";
      $this->salida .= "  		</tr>";
      $this->salida .= "  		</table>";
      $this->salida .= "  </td></tr>";
      $this->salida .= "  <tr>";
      $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','GuardarSeleccionDepartamentoUnificado',array("centroutilidad"=>$centroutilidad,"centroU"=>$centroU,"unidadfunc"=>$unidadfunc,"unidadF"=>$unidadF,"departamento"=>$departamento,"DptoSel"=>$DptoSel));
      $this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
      $this->salida .= "  <td align=\"center\">";
      $this->salida.= "   <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
      $this->salida .= "  </td>";
      $this->salida .= "  </form>";
      $this->salida .= "  </tr>";
      $this->salida .= "	</table>";
      $this->salida .= ThemeCerrarTabla();
      return true;
    }
		/**
    * Funcion donde se crea el buscador del reporte de cancelacion de citas consolidado
    * por entidad
    *
    * @param string $centroutilidad Descripcion del centro de utilidad
    * @param string $centroU Referencia al centro de utilidad
    * @param string $unidadfunc Descripcion de la unidad funcional
    * @param string $unidadF Referencia a la unidad funcional
    * @param string $departamento Descripcion del departamento
    * @param string $DptoSel Referencia al departamento
    * @param array $planes Arreglo de datos de los planes que son de afiliados
    *
    * @return boolean
    */
		function FormaReportesCancelacionCitasConsolidadoEntidad($centroutilidad,$centroU,$unidadfunc,$unidadF,$departamento,$DptoSel,$planes)
    {
      unset($_SESSION['reconecc']);
      unset($_SESSION['CITAS_CANCELADAS']['DATOS']);
      $this->salida  = "<script>\n";
      $this->salida .= "  function textoPlan(objeto)\n";
      $this->salida .= "  {\n";
      $this->salida .= "    objeto.descripcion_plan.value= objeto.plan_afiliacion.options[objeto.plan_afiliacion.selectedIndex].text; \n";
      $this->salida .= "  }\n";
      $this->salida .= "</script>\n";
      $this->salida .= ThemeAbrirTabla('FILTRO DE DATOS PARA EL REPORTE DE MOTIVOS DE CANCELACION DE CITAS MÉDICAS CONSOLIDADO EN LA ENTIDAD');
      $this->salida .= "<table border=\"0\" width=\"60%\" align=\"center\">";
      $this->salida .= "  <tr>\n";
      $this->salida .= "    <td>";
      $this->Cabecera();
      $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
      $this->salida .= $this->SetStyle("MensajeError");
      $this->salida .= "      </table><br>";

      $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaReporteCitasCanceladasConsolidadoEntidad');
      $this->salida .= "      <form name=\"data\" action=\"$accion\" method=\"post\">";
      $this->salida .= "        <input type=\"hidden\" name=\"centroutilidad\" value=\"".$centroutilidad."\" class=\"input-text\">";
      $this->salida .= "        <input type=\"hidden\" name=\"unidadfunc\" value=\"".$unidadfunc."\" class=\"input-text\">";
      $this->salida .= "        <input type=\"hidden\" name=\"departamento\" value=\"".$departamento."\" class=\"input-text\">";
      $this->salida .= "        <input type=\"hidden\" name=\"centroU\" value=\"".$centroU."\" >";
      $this->salida .= "        <input type=\"hidden\" name=\"unidadF\" value=\"".$unidadF."\" >";
      $this->salida .= "        <input type=\"hidden\" name=\"DptoSel\" value=\"".$DptoSel."\" >";
      $this->salida .= "        <input type=\"hidden\" name=\"descripcion_plan\" value=\"".$_REQUEST['descripcion_plan']."\">\n";
      $this->salida .= "  		  <table border=\"0\" width=\"100%\" align=\"center\">";
      $this->salida .= "  		    <tr>";
      $this->salida .= "  		      <td>";
      $this->salida .= "  		        <fieldset class=\"fieldset\">\n";
      $this->salida .= "                <legend class=\"normal_10AN\">INGRESO DE DATOS</legend>";
      $this->salida .= "    	          <table border=\"0\" width=\"95%\" align=\"center\">";
      $this->salida .= "                  <tr class=\"label\">";
      $this->salida .= "                    <td width=\"50%\">JUSTIFICACIONES:</td>";		
      $this->salida .= "                    <td width=\"50%\">";
      $this->salida .= "                      <select name=\"justificacion\" class=\"select\">";
      $this->salida .= "                        <option value=\"-1\" selected>--  TODOS  --</option>";
      $justificaciones=$this->BuscarJustificacion();
      $a=explode(',',$_REQUEST['justificacion']);
      for($i=0;$i<sizeof($justificaciones);$i++){
      if($justificaciones[$i]['tipo_cancelacion_id']==$a[0]){
           $this->salida .="                        <option value=\"".$justificaciones[$i]['tipo_cancelacion_id']."".','."".$justificaciones[$i]['descripcion']."\"selected>".$justificaciones[$i]['descripcion']."</option>";
           }else{
                $this->salida .="                        <option value=\"".$justificaciones[$i]['tipo_cancelacion_id']."".','."".$justificaciones[$i]['descripcion']."\">".$justificaciones[$i]['descripcion']."</option>";
           }
      }
      $this->salida .= "                      </select>";
      $this->salida .= "                    </td>";
      $this->salida .= "                  </tr>";
      $this->salida .= "                  <tr class=\"label\">\n";
      $this->salida .= "                    <td >PLAN DE AFILIACIÓN:</td>\n";		
      $this->salida .= "                    <td >\n";
      $this->salida .= "                      <select name=\"plan_afiliacion\" class=\"select\" onchange=\"textoPlan(document.data)\">\n";
      $this->salida .= "                        <option value=\"-1\" selected>-- SELECCIONAR --</option>\n";
      $s = "";
      foreach($planes as $key => $dtl)
      {
        ($key == $_REQUEST['plan_afiliacion'])? $s = "selected": $s = "";
        $this->salida .= "                        <option value=\"".$key."\" ".$s.">".$dtl['plan_descripcion']."</option>\n";
      }
      $this->salida .= "                      </select>\n";
      $this->salida .= "                    </td>\n";
      $this->salida .= "                  </tr>\n";
      $this->salida .= "                  <tr class=\"label\">";
      $this->salida .= "                    <td >FECHA INICIAL:</td>";
      $this->salida .= "                    <td>";
      if(!$_REQUEST['feinictra']){
           $_REQUEST['feinictra']=date('01/m/Y');
      }
      $this->salida .= "                      <input type=\"text\" class=\"input-text\" name=\"feinictra\" value=\"".$_REQUEST['feinictra']."\" maxlength=\"10\" size=\"10\">";
      $this->salida .= "                      ".ReturnOpenCalendario('data','feinictra','/')."";
      $this->salida .= "                    </td>";
      $this->salida .= "                  </tr>";
      $this->salida .= "                  <tr class=\"label\">";
      $this->salida .= "                    <td >FECHA FINAL:</td>";
      $this->salida .= "                    <td >";
      if(!$_REQUEST['fefinctra']){
           $_REQUEST['fefinctra']=date('d/m/Y');
      }
      $this->salida .= "                      <input type=\"text\" class=\"input-text\" name=\"fefinctra\" value=\"".$_REQUEST['fefinctra']."\" maxlength=\"10\" size=\"10\">";
      $this->salida .= "                      ".ReturnOpenCalendario('data','fefinctra','/')."";
      $this->salida .= "                    </td>";
      $this->salida .= "                  </tr>";
      $this->salida .= "                  <tr>";
      $this->salida .= "                    <td colspan=\"2\" align=\"center\"><br>";
      $this->salida .= "                      <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"GENERAR REPORTE\">";
      $this->salida .= "                    </td>";
      $this->salida .= "                  </tr>";
      $this->salida .= "  		          </table>";
      $this->salida .= "  		        </fieldset>";
      $this->salida .= "  		      </td>";
      $this->salida .= "  		    </tr>";
      $this->salida .= "  		  </table>";
      $this->salida .= "      </form>";
      $this->salida .= "    </td>\n";
      $this->salida .= "  </tr>";
      $this->salida .= "  <tr>";
      $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','GuardarSeleccionDepartamentoUnificado',array("centroutilidad"=>$centroutilidad,"centroU"=>$centroU,"unidadfunc"=>$unidadfunc,"unidadF"=>$unidadF,"departamento"=>$departamento,"DptoSel"=>$DptoSel));
      $this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
      $this->salida .= "  <td align=\"center\">";
      $this->salida.= "   <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
      $this->salida .= "  </td>";
      $this->salida .= "  </form>";
      $this->salida .= "  </tr>";
      $this->salida .= "	</table>";
      $this->salida .= ThemeCerrarTabla();
      return true;
    }


     function FormaReporteEstadisticasCausasTipo($centroutilidad,$centroU,$unidadfunc,$unidadF,$departamento,$DptoSel)
     {
          unset($_SESSION['reconecc']);
          unset($_SESSION['CITAS_CANCELADAS']['DATOS']);
          
          /*$RUTA = "app_modules/Reportes_Consulta_Externa/buscador.php?sign=";
          $mostrar ="\n<script language='javascript'>\n";
          $mostrar.="var rem=\"\";\n";
          $mostrar.="  function xxx(a){\n";
          $mostrar.="    var nombre=\"\"\n";
          $mostrar.="    var url2=\"\"\n";
          $mostrar.="    var str=\"\"\n";
          $mostrar.="    var nombre=\"REPORTE\";\n";
          $mostrar.="    var str =\"width=450,height=180,resizable=no,location=no, status=no,scrollbars=yes\";\n";
          $mostrar.="    var url2 ='$RUTA';\n";
          $mostrar.="    url2 +=a;\n";
          $mostrar.="    rem = window.open(url2, nombre, str)};\n";
		$mostrar.="</script>\n";
          $this->salida .= $mostrar;
          */
          $this->salida .= ThemeAbrirTabla('FILTRO DE DATOS PARA EL REPORTE DE CAUSAS Y TIPOS DE CITAS MÉDICAS');
          $this->salida .= "  <table border=\"0\" width=\"70%\" align=\"center\">";
          $this->salida .= "  <tr><td>";
          $this->Cabecera();
          $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
          $this->salida .=    $this->SetStyle("MensajeError");
          $this->salida .= "  </table><br>";
         
          $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','BusquedaReporteEstadisticasCausasTipo');
          $this->salida .= "  <form name=\"data\" action=\"$accion\" method=\"post\">";
          $this->salida .= "	<table border=\"0\" width=\"100%\" align=\"center\">";
          $this->salida .= "<tr>";
          $this->salida .= "<td>";
          $this->salida .= "<fieldset><legend class=\"field\">INGRESO DE DATOS</legend>";
          $this->salida .= "<table border=\"0\" width=\"95%\" align=\"center\">";

          /*$this->salida .= "<tr><td width=\"15%\" class=\"label\">CENTRO UTILIDAD: </td>";
          $this->salida .= "<td width=\"15%\"><input type=\"text\" class=\"input-text\" name=\"centroutilidad\" value=\"".$_REQUEST['centroutilidad']."\" maxlength=\"100\" size=\"40\" readonly></td></tr>";
          $this->salida .= "<tr><td width=\"20%\" class=\"label\">UNIDAD FUNCIONAL: </td>";
          $this->salida .= "<td width=\"15%\"><input type=\"text\" class=\"input-text\" name=\"unidadfunc\" value=\"".$_REQUEST['unidadfunc']."\" maxlength=\"100\" size=\"40\" readonly></td></tr>";
          $this->salida .= "<tr><td width=\"15%\" class=\"label\">DEPARTAMENTO: </td>";
          $this->salida .= "<td width=\"15%\"><input type=\"text\" class=\"input-text\" name=\"departamento\" value=\"".$_REQUEST['departamento']."\" maxlength=\"100\" size=\"40\" readonly>";
          $this->salida .= "&nbsp;<a href=\"javascript:xxx(1)\"><img title='Busqueda del Departamento' src=\"". GetThemePath() ."/images/auditoria.png\" border='0' width='14' height='14'></td></tr>";
          */
						
					$this->salida .= "<input type=\"hidden\" name=\"centroutilidad\" value=\"".$centroutilidad."\" class=\"input-text\">";
					$this->salida .= "<input type=\"hidden\" name=\"unidadfunc\" value=\"".$unidadfunc."\" class=\"input-text\">";
					$this->salida .= "<input type=\"hidden\" name=\"departamento\" value=\"".$departamento."\" class=\"input-text\">";
					$this->salida .= "<input type=\"hidden\" name=\"centroU\" value=\"".$centroU."\" class=\"input-text\">";
					$this->salida .= "<input type=\"hidden\" name=\"unidadF\" value=\"".$unidadF."\" class=\"input-text\">";
					$this->salida .= "<input type=\"hidden\" name=\"DptoSel\" value=\"".$DptoSel."\" class=\"input-text\">";

          $this->salida .= "      <tr>";
          $this->salida .= "      <td class=\"label\" width=\"50%\">TIPOS DE CITA:";
          $this->salida .= "      </td>";
          $this->salida .= "      <td>";
          $this->salida .= "      <select name=\"tipocita\" class=\"select\">";
          $this->salida .= "      <option value=\"-1\">--  SELECCIONE  --</option>";
          $tipo_cita = $this->BuscarTipoCitas();
          $a = $_REQUEST['tipocita'];		
          for($i=0;$i<sizeof($tipo_cita);$i++)
          {
               if($tipo_cita[$i]['tipo_cita'] == $a){
                    $this->salida .="<option value=\"".$tipo_cita[$i]['tipo_cita'].",".$tipo_cita[$i]['descripcion']."\" selected>".$tipo_cita[$i]['descripcion']."</option>";
               }else{
          	     $this->salida .="<option value=\"".$tipo_cita[$i]['tipo_cita'].",".$tipo_cita[$i]['descripcion']."\">".$tipo_cita[$i]['descripcion']."</option>";
               }
     	}
          $this->salida .= " </select>";
          $this->salida .= "      </td>";
          $this->salida .= "      </tr>";
                    
          $this->salida .= "<tr><td class=\"label\">PROFESIONAL: </td><td><select name=\"profesional_escojer\" class=\"select\">";          
          $vector_P=$this->Get_Profesionales();
          if(sizeof($vector_P)>1){
            $this->salida .= "      <option value=\"-1\">--  SELECCIONE--</option>";
          }
          $this->GetHtmlProfesional($vector_P,$_REQUEST['profesional_escojer']);
          $this->salida .= "</select></td></tr>";

          $this->salida .= "      <tr>";
          $this->salida .= "      <td width=\"50%\">";
          $this->salida .= "      <label class=\"label\">FECHA INICIAL:</label>";
          $this->salida .= "      </td>";
          $this->salida .= "      <td class=\"label\">";
					if(empty($_REQUEST['feinictra'])){
								$_REQUEST['feinictra']=date('01/m/Y');
					}
          $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"feinictra\" value=\"".$_REQUEST['feinictra']."\" maxlength=\"10\" size=\"10\">";
          $this->salida .= "      ".ReturnOpenCalendario('data','feinictra','/')."";
          $this->salida .= "      </td>";
          $this->salida .= "      </tr>";
          $this->salida .= "      <tr>";
          $this->salida .= "      <td width=\"50%\">";
          $this->salida .= "      <label class=\"label\">FECHA FINAL:</label>";
          $this->salida .= "      </td>";
          $this->salida .= "      <td class=\"label\">";
					if(empty($_REQUEST['fefinctra'])){
								$_REQUEST['fefinctra']=date('d/m/Y');
					}
          $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fefinctra\" value=\"".$_REQUEST['fefinctra']."\" maxlength=\"10\" size=\"10\">";
          $this->salida .= "      ".ReturnOpenCalendario('data','fefinctra','/')."";
          $this->salida .= "      </td>";
          $this->salida .= "      </tr>";
          $this->salida .= "      <tr>";
          $this->salida .= "      <td colspan=\"2\" align=\"center\"><br>";
          $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"GENERAR REPORTE\">";
          $this->salida .= "      </td>";
          $this->salida .= "</form>";
          $this->salida .= "      </tr>";
          $this->salida .= "  		</fieldset>";
          $this->salida .= "  		</table><br>";
          $this->salida .= "  		</td>";
          $this->salida .= "  		</tr>";
          $this->salida .= "  		</table>";
          $this->salida .= "  </td></tr>";
          $this->salida .= "  <tr>";
          $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','GuardarSeleccionDepartamentoUnificado',array("centroutilidad"=>$centroutilidad,"centroU"=>$centroU,"unidadfunc"=>$unidadfunc,"unidadF"=>$unidadF,"departamento"=>$departamento,"DptoSel"=>$DptoSel));
          $this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
          $this->salida .= "  <td align=\"center\">";
          $this->salida.= "   <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
          $this->salida .= "  </td>";
          $this->salida .= "  </form>";
          $this->salida .= "  </tr>";
          $this->salida .= "	</table>";
          $this->salida .= ThemeCerrarTabla();
          return true;
     }
     
     
     function ResultadosEstadisticosTipoCitas($Total_consulta,$total_tipo_cita,$Finalidad,$Origen)
    {
      $archivoPlano='';
      unset($_SESSION['reconeccc']);
     	$this->salida = ThemeAbrirTabla('REPORTE ESTADISTICO DE CAUSAS Y TIPOS DE CITAS MÉDICAS');
		  $this->salida .= "<table border=\"0\" width=\"90%\" align=\"center\">";
		  $this->salida .= "<tr><td>";
		  $this->Cabecera();
		  $this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\">";
		  $this->salida .=  $this->SetStyle("MensajeError");
		  $this->salida .= "</table><br>";
      $this->salida .= "</td></tr>";
      $this->salida .= "</table>";
		  $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','RegistrosReporteCausasCitasMedicas');
		  $this->salida .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
		  $this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_list_oscuro\">";
		  $this->salida .= "<tr>";
		  $this->salida .= "<td align=\"center\" class=\"modulo_table_list_title\" colspan=\"2\">DATOS DE LA BUSQUEDA</td>";
          $this->salida .= "</tr>";
          $this->salida .= "<tr>";
          $this->salida .= "<td class=\"modulo_table_title\" width=\"40%\" align=\"left\">CENTRO DE UTILIDAD</td>";
          if(!empty($_REQUEST['centroutilidad']))
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\">".$_REQUEST['centroutilidad']."</td>"; }
          else
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\"><label class=\"label_mark\">SIN DATOS</label></td>"; }
          $this->salida .= "</tr>";
          
          $this->salida .= "<tr>";
          $this->salida .= "<td class=\"modulo_table_title\" width=\"40%\" align=\"left\">UNIDAD FUNCIONAL</td>";
          if(!empty($_REQUEST['unidadfunc']))
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\">".$_REQUEST['unidadfunc']."</td>"; }
          else
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\"><label class=\"label_mark\">SIN DATOS</label></td>"; }
          $this->salida .= "</tr>";
          
          $this->salida .= "<tr>";
          $this->salida .= "<td class=\"modulo_table_title\" width=\"40%\" align=\"left\">DEPARTAMENTO</td>";
          if(!empty($_REQUEST['departamento']))
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\">".$_REQUEST['departamento']."</td>"; }
          else
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\"><label class=\"label_mark\">SIN DATOS</label></td>"; }
          $this->salida .= "</tr>";
               
          $usuario_id = explode(',',$_REQUEST['profesional_escojer']);

          $this->salida .= "<tr>";
          $this->salida .= "<td class=\"modulo_table_title\" width=\"40%\" align=\"left\">PROFESIONAL</td>";
          if(!empty($usuario_id[1]))
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\">".$usuario_id[1]."</td>"; }
          else
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\"><label class=\"label_mark\">SIN DATOS</label></td>"; }
          $this->salida .= "</tr>";
               
          $tipo_cita = explode(',',$_REQUEST['tipocita']);

          $this->salida .= "<tr>";
          $this->salida .= "<td class=\"modulo_table_title\" width=\"40%\" align=\"left\">TIPO DE CITA</td>";
          if(!empty($tipo_cita[1]))
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\">".$tipo_cita[1]."</td>"; }
          else
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\"><label class=\"label_mark\">SIN DATOS</label></td>"; }
          $this->salida .= "</tr>";
          
          $this->salida .= "<tr>";
          $this->salida .= "<td class=\"modulo_table_title\" width=\"40%\" align=\"left\">FECHA INICIAL</td>";
          if(!empty($_REQUEST['feinictra']))
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\">".$_REQUEST['feinictra']."</td>"; }
          else
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\"><label class=\"label_mark\">SIN DATOS</label></td>"; }
          $this->salida .= "</tr>";

          $this->salida .= "<tr>";
          $this->salida .= "<td class=\"modulo_table_title\" width=\"40%\" align=\"left\">FECHA FINAL</td>";
          if(!empty($_REQUEST['fefinctra']))
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\">".$_REQUEST['fefinctra']."</td>"; }
          else
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\"><label class=\"label_mark\">SIN DATOS</label></td>"; }
          $this->salida .= "</tr>";

          $this->salida .= "</table><br>";
          
		$this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_list_oscuro\">";
		$this->salida .= "<tr>";
		$this->salida .= "<td align=\"center\" class=\"modulo_table_list_title\" colspan=\"2\">ESTADISTICAS</td>";
          $archivoPlano.='ESTADISTICAS'."\n";
          $this->salida .= "</tr>";
          		
          $this->salida .= "<tr>";
          $this->salida .= "<td class=\"modulo_list_claro\" width=\"50%\" align=\"left\">TOTAL DE LAS CONSULTAS : </td>";
          $archivoPlano.='TOTAL DE LAS CONSULTAS'.'|';
          $this->salida .= "<td align=\"justify\" width=\"50%\" class=\"modulo_list_claro\"><b>".$Total_consulta[total_consulta]."</b></td>";
          $archivoPlano.=$Total_consulta[total_consulta]."\n\n";
          $this->salida .= "</tr>";
		
          $this->salida .= "<tr>";
		     $this->salida .= "<td align=\"center\" class=\"modulo_table_list_title\" colspan=\"2\">&nbsp;</td>";
          $this->salida .= "</tr>";
          if(!empty($total_tipo_cita))
          {
               $this->salida .= "<tr>";
               $this->salida .= "<td align=\"center\" class=\"modulo_table_list_title\" colspan=\"2\">ESTADISTICAS DE TIPOS DE CONSULTA</td>";
               $archivoPlano.='ESTADISTICAS DE TIPOS DE CONSULTA'."\n";
               $this->salida .= "</tr>";
               
               for($j=0;$j<sizeof($total_tipo_cita); $j++)
               {
                    $this->salida .= "<tr>";
                    $this->salida .= "<td class=\"modulo_list_claro\" width=\"50%\" align=\"left\">CONSULTAS DE: ".$total_tipo_cita[$j][tipos_de_citas]."</td>";
                    $archivoPlano.='CONSULTAS DE:'.$total_tipo_cita[$j][tipos_de_citas].'|';
                    $this->salida .= "<td align=\"justify\" width=\"50%\" class=\"modulo_list_claro\"><b>".$total_tipo_cita[$j][total_tipo_cita]."</b></td>";
                    $archivoPlano.=$total_tipo_cita[$j][total_tipo_cita]."\n";
                    $this->salida .= "</tr>";
               }
               
               $this->salida .= "<tr>";
               $this->salida .= "<td align=\"center\" class=\"modulo_table_list_title\" colspan=\"2\">&nbsp;</td>";
               $this->salida .= "</tr>";
          }
          $archivoPlano.="\n";
          if(!empty($Finalidad))
          {
               $this->salida .= "<tr>";
               $this->salida .= "<td align=\"center\" class=\"modulo_table_list_title\" colspan=\"2\">ESTADISTICAS DE CONSULTAS POR FINALIDAD</td>";
               $archivoPlano.='ESTADISTICAS DE CONSULTAS POR FINALIDAD'."\n";
               $this->salida .= "</tr>";
               
               $this->salida .= "<tr>";
               $this->salida .= "<td class=\"modulo_table_list_title\" width=\"60%\" align=\"left\">FINALIDAD</td>";
               $archivoPlano.='FINALIDAD'.'|';
               $this->salida .= "<td align=\"justify\" width=\"40%\" class=\"modulo_table_list_title\"><b>CONSULTAS</td>";
               $archivoPlano.='CONSULTAS'."\n";
               $this->salida .= "</tr>";
     
               for($a=0;$a<sizeof($Finalidad); $a++)
               {
                    $this->salida .= "<tr>";
                    $this->salida .= "<td class=\"modulo_list_claro\" width=\"50%\" align=\"left\">".$Finalidad[$a][detalle]."</td>";
                    $archivoPlano.=$Finalidad[$a][detalle].'|';
                    $this->salida .= "<td align=\"justify\" width=\"50%\" class=\"modulo_list_claro\"><b>".$Finalidad[$a][total_citas_finalidad]."</b></td>";
                    $archivoPlano.=$Finalidad[$a][total_citas_finalidad]."\n\n";
                    $this->salida .= "</tr>";
               }
     
               $this->salida .= "<tr>";
               $this->salida .= "<td align=\"center\" class=\"modulo_table_list_title\" colspan=\"2\">&nbsp;</td>";
               $this->salida .= "</tr>";
          }
          
          if(!empty($Origen))
          {
               $this->salida .= "<tr>";
               $this->salida .= "<td align=\"center\" class=\"modulo_table_list_title\" colspan=\"2\">ESTADISTICAS DE CONSULTAS POR ORIGEN DE ATENCION</td>";
               $archivoPlano.='ESTADISTICAS DE CONSULTAS POR ORIGEN DE ATENCION'."\n";
               $this->salida .= "</tr>";
               
               $this->salida .= "<tr>";
               $this->salida .= "<td class=\"modulo_table_list_title\" width=\"60%\" align=\"left\">ORIGEN ATENCION</td>";
               $archivoPlano.='ORIGEN ATENCION'.'|';
               $this->salida .= "<td align=\"justify\" width=\"40%\" class=\"modulo_table_list_title\"><b>CONSULTAS</td>";
               $archivoPlano.='CONSULTAS'."\n";
               $this->salida .= "</tr>";
     
               for($x=0;$x<sizeof($Origen); $x++)
               {
                    $this->salida .= "<tr>";
                    $this->salida .= "<td class=\"modulo_list_claro\" width=\"50%\" align=\"left\">".$Origen[$x][detalle]."</td>";
                    $archivoPlano.=$Origen[$x][detalle].'|';
                    $this->salida .= "<td align=\"justify\" width=\"50%\" class=\"modulo_list_claro\"><b>".$Origen[$x][total_citas_origen]."</b></td>";
                    $archivoPlano.=$Origen[$x][total_citas_origen]."\n";
                    $this->salida .= "</tr>";
               }
          }
		
          $reporte= new GetReports();
		$mostrar=$reporte->GetJavaReport('app','Reportes_Consulta_Externa','ReporteEstadisticasTiposCitas',array('variables'=>$_REQUEST),array('rpt_name'=>'ConsultaExternas','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));		
		$nombre_funcion=$reporte->GetJavaFunction();
		$this->salida .=$mostrar;
		echo $mostrar;

          $this->salida .= "<tr>";
		$this->salida .= "<td align=\"right\" class=\"modulo_list_oscuro\" colspan=\"2\"><a href=\"javascript:$nombre_funcion\"><b>Imprimir Reporte</b></a></td>";
          $this->salida .= "</tr>";
         
		$this->salida .= "</table>";
          $this->salida .= "</form>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";        
        $this->salida .= "      <tr><td class=\"label\" align=\"center\">";
        $_SESSION['DESCARGA_DATOS_REPORTES']['DATOS']=$archivoPlano;                    
        $descarga=ModuloGetURL('app','Reportes_Consulta_Externa','user','DescargaDatosCausasTiposCitasMedicas',array('centroutilidad'=>$_REQUEST['centroutilidad'],'unidadfunc'=>$_REQUEST['unidadfunc'],
        'departamento'=>$_REQUEST['departamento'],'profesional_escojer'=>$_REQUEST['profesional_escojer'],"feinictra"=>$_REQUEST['feinictra'],"fefinctra"=>$_REQUEST['fefinctra'],
        'centroU'=>$_REQUEST['centroU'],'unidadF'=>$_REQUEST['unidadF'],'DptoSel'=>$_REQUEST['DptoSel']));          
        $this->salida .= "      <a href=\"$descarga\">Descargar Datos <img title=\"Descargar Archivo\" border=\"0\" src=\"".GetThemePath()."/images/abajo.png\"></a>";
        $this->salida .="       </td></tr>";
        $this->salida .= "      </table><BR>";  
        
        //$accion=ModuloGetURL('app','ParametrizacionPYP','admin','GuardarAyudasPaciente');
        //$accion1=ModuloGetURL('app','ParametrizacionPYP','admin','FrmAyudasUsuario');
        /*$this->salida .= "            <form name=\"formarchivo\" action=\"$accion\" method=\"post\" enctype=\"multipart/form-data\">";
        $this->salida .= "            <table class=\"normal_10\" width=\"60%\" align=\"center\" border=\"0\">";        
        $this->salida .= "            <tr><td width=\"15%\" class=\"label\" align=\"left\"><label class=\"".$this->SetStyle("ubicacion")."\" width=\"55%\">ARCHIVO</label></td>";
        $this->salida .= "            <td width=\"85%\" colspan=\"2\" align=\"left\"><input name=\"ubicacion\" type=\"file\" value=\"".$_REQUEST['ubicacion']."\" class=\"input-text\"></td></tr>";
        $this->salida .= "            <input type=\"hidden\" name=\"userfilename\" id=\"userfilename\">";                
        $this->salida .= "            </table>";
        $this->salida .= "            </form>";*/
        
        
          $actionM=ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaReporteEstadisticasCausasTipo',array('centroutilidad'=>$_REQUEST['centroutilidad'],'unidadfunc'=>$_REQUEST['unidadfunc'],
          'departamento'=>$_REQUEST['departamento'],'profesional_escojer'=>$_REQUEST['profesional_escojer'],'feinictra'=>$_REQUEST['feinictra'],'fefinctra'=>$_REQUEST['fefinctra'],
          'centroU'=>$_REQUEST['centroU'],'unidadF'=>$_REQUEST['unidadF'],'DptoSel'=>$_REQUEST['DptoSel'],'tipocita'=>$tipo_cita[0]));
          $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
          $this->salida .= "<br><table align=\"center\" width=\"100%\"><tr><td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"><br></td></tr></table></form>";
          $this->salida .= "</form>";

          $this->salida .= ThemeCerrarTabla();        
     	return true;
    }

     
     function ResultadosEstadisticosOrdenesServicio($Total_consulta,$total_frmedicas,$total_apoyos,$total_Qx,$total_NoQx,$total_Int,$total_Inca,
		 	$centroU,$centroutilidad,$unidadF,$unidadfunc,$DptoSel,$departamento,$profesional_escojer,$feinictra,$fefinctra)
     {    $archivoPlano=''; 
          if(empty($Total_consulta) && empty($total_frmedicas) && empty($total_apoyos) && empty($total_Qx) && empty($total_NoQx) && empty($total_Int) && empty($total_Inca)){
						$Total_consulta=$_REQUEST['Total_consulta'];
						$total_frmedicas=$_REQUEST['total_frmedicas'];
						$total_apoyos=$_REQUEST['total_apoyos'];
						$total_Qx=$_REQUEST['total_Qx'];
						$total_NoQx=$_REQUEST['total_NoQx'];
						$total_Int=$_REQUEST['total_Int'];
						$total_Inca=$_REQUEST['total_Inca'];
					}
					unset($_SESSION['reconeccc']);
     	$this->salida = ThemeAbrirTabla('REPORTE ESTADISTICO DE ORDENES DE SERVICIO');
		$this->salida .= "<table border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "<tr><td>";
		$this->Cabecera();
		$this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\">";
		$this->salida .=  $this->SetStyle("MensajeError");
		$this->salida .= "</table><br>";
          $this->salida .= "</td></tr>";
          $this->salida .= "</table>";
		$accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','VerDetalleReporteEstadisticoOS',array("centroU"=>$centroU,"centroutilidad"=>$centroutilidad,"unidadF"=>$unidadF,"unidadfunc"=>$unidadfunc,
		"DptoSel"=>$DptoSel,"departamento"=>$departamento,"profesional_escojer"=>$profesional_escojer,"feinictra"=>$feinictra,'fefinctra'=>$fefinctra,
		"Total_consulta"=>$Total_consulta,"total_frmedicas"=>$total_frmedicas,"total_apoyos"=>$total_apoyos,"total_Qx"=>$total_Qx,"total_NoQx"=>$total_NoQx,"total_Int"=>$total_Int,"total_Inca"=>$total_Inca));
		$this->salida .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_list_oscuro\">";
		$this->salida .= "<tr>";
		$this->salida .= "<td align=\"center\" class=\"modulo_table_list_title\" colspan=\"2\">DATOS DE LA BUSQUEDA</td>";
          $this->salida .= "</tr>";
          $this->salida .= "<tr>";
          $this->salida .= "<td class=\"modulo_table_title\" width=\"40%\" align=\"left\">CENTRO DE UTILIDAD</td>";
          if(!empty($_REQUEST['centroutilidad']))
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\">".$_REQUEST['centroutilidad']."</td>"; }
          else
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\"><label class=\"label_mark\">SIN DATOS</label></td>"; }
          $this->salida .= "</tr>";
          
          $this->salida .= "<tr>";
          $this->salida .= "<td class=\"modulo_table_title\" width=\"40%\" align=\"left\">UNIDAD FUNCIONAL</td>";
          if(!empty($_REQUEST['unidadfunc']))
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\">".$_REQUEST['unidadfunc']."</td>"; }
          else
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\"><label class=\"label_mark\">SIN DATOS</label></td>"; }
          $this->salida .= "</tr>";
          
          $this->salida .= "<tr>";
          $this->salida .= "<td class=\"modulo_table_title\" width=\"40%\" align=\"left\">DEPARTAMENTO</td>";
          if(!empty($_REQUEST['departamento']))
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\">".$_REQUEST['departamento']."</td>"; }
          else
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\"><label class=\"label_mark\">SIN DATOS</label></td>"; }
          $this->salida .= "</tr>";
               
          $usuario_id = explode(',',$_REQUEST['profesional_escojer']);

          $this->salida .= "<tr>";
          $this->salida .= "<td class=\"modulo_table_title\" width=\"40%\" align=\"left\">PROFESIONAL</td>";
          if(!empty($usuario_id[1]))
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\">".$usuario_id[1]."</td>"; }
          else
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\"><label class=\"label_mark\">SIN DATOS</label></td>"; }
          $this->salida .= "</tr>";
               
          $this->salida .= "<tr>";
          $this->salida .= "<td class=\"modulo_table_title\" width=\"40%\" align=\"left\">FECHA INICIAL</td>";
          if(!empty($_REQUEST['feinictra']))
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\">".$_REQUEST['feinictra']."</td>"; }
          else
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\"><label class=\"label_mark\">SIN DATOS</label></td>"; }
          $this->salida .= "</tr>";

          $this->salida .= "<tr>";
          $this->salida .= "<td class=\"modulo_table_title\" width=\"40%\" align=\"left\">FECHA FINAL</td>";
          if(!empty($_REQUEST['fefinctra']))
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\">".$_REQUEST['fefinctra']."</td>"; }
          else
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\"><label class=\"label_mark\">SIN DATOS</label></td>"; }
          $this->salida .= "</tr>";

          $this->salida .= "</table><br>";
          
		      $this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_list_oscuro\">";
		      $this->salida .= "<tr>";
		      $this->salida .= "<td align=\"center\" class=\"modulo_table_list_title\" colspan=\"3\">ESTADISTICAS</td>";
          $archivoPlano.='ESTADISTICAS'."\n"; 
          $this->salida .= "</tr>";
          		
          $this->salida .= "<tr>";
          $this->salida .= "<td class=\"modulo_table_title\" width=\"50%\" align=\"left\">TOTAL DE LAS CONSULTAS : </td>";
          $archivoPlano.='TOTAL DE LAS CONSULTAS'.'|';
          $this->salida .= "<td align=\"justify\" width=\"50%\" class=\"modulo_list_claro\" colspan=\"2\"><b>".$Total_consulta[total_consulta]."</b></td>";
          $archivoPlano.=$Total_consulta[total_consulta]."\n\n";
          $this->salida .= "</tr>";
		
          $this->salida .= "<tr>";
		      $this->salida .= "<td align=\"center\" class=\"modulo_table_list_title\" colspan=\"3\">&nbsp;</td>";
          $this->salida .= "</tr>";
          $this->salida .= "<tr>";
		      $this->salida .= "<td align=\"center\" class=\"modulo_table_list_title\" colspan=\"3\">ESTADISTICAS DE SOLICITUDES</td>";
          $archivoPlano.='ESTADISTICAS DE SOLICITUDES'."\n";
          $this->salida .= "</tr>";
       	
          $this->salida .= "<tr>";
          $this->salida .= "<td class=\"modulo_table_title\" width=\"50%\" align=\"left\">FORMULAS MEDICAS</td>";
          $archivoPlano.='FORMULAS MEDICAS'.'|';
          $this->salida .= "<td align=\"justify\" width=\"50%\" class=\"modulo_list_claro\" colspan=\"2\"><b>".$total_frmedicas[total_formulas_medicas]."</b></td>";					
          $archivoPlano.=$total_frmedicas[total_formulas_medicas]."\n";
          $this->salida .= "</tr>";
          
          $this->salida .= "<tr>";
          $this->salida .= "<td class=\"modulo_table_title\" width=\"50%\" align=\"left\">APOYOS DIAGNOSTICOS</td>";
          $archivoPlano.='APOYOS DIAGNOSTICOS'.'|';
          $this->salida .= "<td align=\"justify\" width=\"50%\" class=\"modulo_list_claro\"><b>".$total_apoyos[total_solicitudes_apd]."</b></td>";
          $archivoPlano.=$total_apoyos[total_solicitudes_apd]."\n";
					$this->salida .= "<td align=\"justify\" width=\"5%\" class=\"modulo_list_claro\"><input type=\"checkbox\" name=\"DetalleAD\"></td>";
          $this->salida .= "</tr>";
          
          $this->salida .= "<tr>";
          $this->salida .= "<td class=\"modulo_table_title\" width=\"50%\" align=\"left\">PROCEDIMIENTOS QUIRURGICOS</td>";
          $archivoPlano.='PROCEDIMIENTOS QUIRURGICOS'.'|';
          $this->salida .= "<td align=\"justify\" colspan=\"2\" width=\"50%\" class=\"modulo_list_claro\"><b>".$total_Qx[total_solicitudes_qx]."</b></td>";					
          $archivoPlano.=$total_Qx[total_solicitudes_qx]."\n";
          $this->salida .= "</tr>";
          
          $this->salida .= "<tr>";
          $this->salida .= "<td class=\"modulo_table_title\" width=\"50%\" align=\"left\">PROCEDIMIENTOS NO QUIRURGICOS</td>";
          $archivoPlano.='PROCEDIMIENTOS NO QUIRURGICOS'.'|';
          $this->salida .= "<td align=\"justify\" colspan=\"2\" width=\"50%\" class=\"modulo_list_claro\"><b>".$total_NoQx[total_solicitudes_nqx]."</b></td>";					
          $archivoPlano.=$total_NoQx[total_solicitudes_nqx]."\n";
          $this->salida .= "</tr>";
		
          $this->salida .= "<tr>";
          $this->salida .= "<td class=\"modulo_table_title\" width=\"50%\" align=\"left\">INTERCONSULTAS</td>";
          $archivoPlano.='INTERCONSULTAS'.'|';
          $this->salida .= "<td align=\"justify\" width=\"50%\" class=\"modulo_list_claro\"><b>".$total_Int[total_solicitudes_interconsultas]."</b></td>";
          $archivoPlano.=$total_Int[total_solicitudes_interconsultas]."\n";
					$this->salida .= "<td align=\"justify\" width=\"5%\" class=\"modulo_list_claro\"><input type=\"checkbox\" name=\"DetalleINTER\"></td>";
          $this->salida .= "</tr>";
          
          $this->salida .= "<tr>";
          $this->salida .= "<td class=\"modulo_table_title\" width=\"50%\" align=\"left\">INCAPACIDADES</td>";
          $archivoPlano.='INCAPACIDADES'.'|';
          $this->salida .= "<td align=\"justify\" width=\"50%\" class=\"modulo_list_claro\"><b>".$total_Inca[total_incapacidades]."</b></td>";
          $archivoPlano.=$total_Inca[total_incapacidades]."\n";
					$this->salida .= "<td align=\"justify\" width=\"5%\" class=\"modulo_list_claro\"><input type=\"checkbox\" name=\"DetalleINCA\"></td>";
          $this->salida .= "</tr>";
					$this->salida .= "<tr>";
					$this->salida .= "<td align=\"right\" class=\"modulo_list_oscuro\" colspan=\"3\"><input type=\"submit\" class=\"input-submit\" name=\"verDetalle\" value=\"VER DETALLE\"></td>";
          $this->salida .= "</tr>";
          
          $reporte= new GetReports();
		      $mostrar=$reporte->GetJavaReport('app','Reportes_Consulta_Externa','ReporteEstadisticasOrdenServicio',array('Total_consulta'=>$Total_consulta,'total_frmedicas'=>$total_frmedicas,'total_apoyos'=>$total_apoyos,'total_Qx'=>$total_Qx,'total_NoQx'=>$total_NoQx,'total_Int'=>$total_Int,'total_Inca'=>$total_Inca,'variables'=>$_REQUEST),array('rpt_name'=>'ConsultaExternas','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));		
		      $nombre_funcion=$reporte->GetJavaFunction();
		      $this->salida .=$mostrar;
		      echo $mostrar;

          $this->salida .= "<tr>";
		      $this->salida .= "<td align=\"center\" class=\"modulo_list_oscuro\" colspan=\"3\"><a href=\"javascript:$nombre_funcion\"><b>Imprimir Reporte</b></a></td>";
          $this->salida .= "</tr>";
         
		      $this->salida .= "</table>";
          $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";        
          $this->salida .= "      <tr><td class=\"label\" align=\"center\">";
          $_SESSION['DESCARGA_DATOS_REPORTES']['DATOS']=$archivoPlano;                    
          $descarga=ModuloGetURL('app','Reportes_Consulta_Externa','user','DescargaDatosOrdenesServicio',array('centroutilidad'=>$_REQUEST['centroutilidad'],'unidadfunc'=>$_REQUEST['unidadfunc'],
          'departamento'=>$_REQUEST['departamento'],'profesional_escojer'=>$_REQUEST['profesional_escojer'],"feinictra"=>$_REQUEST['feinictra'],"fefinctra"=>$_REQUEST['fefinctra'],
          'centroU'=>$_REQUEST['centroU'],'unidadF'=>$_REQUEST['unidadF'],'DptoSel'=>$_REQUEST['DptoSel']));          
          $this->salida .= "      <a href=\"$descarga\">Descargar Datos <img title=\"Descargar Archivo\" border=\"0\" src=\"".GetThemePath()."/images/abajo.png\"></a>";
          $this->salida .="       </td></tr>";
          $this->salida .= "      </table><BR>";   
          $this->salida .= "</form>";
          
          $actionM=ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaReporteEstadisticoOrdenesServicio',array('centroutilidad'=>$centroutilidad,'unidadfunc'=>$unidadfunc,
          'departamento'=>$departamento,'profesional_escojer'=>$profesional_escojer,'feinictra'=>$feinictra,'fefinctra'=>$fefinctra,
          'centroU'=>$centroU,'unidadF'=>$unidadF,'DptoSel'=>$DptoSel));
          $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
          $this->salida .= "<br><table align=\"center\" width=\"100%\"><tr><td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"><br></td></tr></table></form>";
          $this->salida .= "</form>";

          $this->salida .= ThemeCerrarTabla();        
     	return true;
     }
		 
		 
		function ResultadosEstadisticosOrdenesServicioDetalle($total_apoyos,$total_Int,$total_Inca,
			$centroU,$centroutilidad,$unidadF,$unidadfunc,$DptoSel,$departamento,$profesional_escojer,$feinictra,$fefinctra,
			$Total_consultaT,$total_frmedicasT,$total_apoyosT,$total_QxT,$total_NoQxT,$total_IntT,$total_IncaT){
			
			unset($_SESSION['reconeccc']);
     	$this->salida = ThemeAbrirTabla('REPORTE ESTADISTICO DETALLADO DE ORDENES DE SERVICIO');
			$this->salida .= "<table border=\"0\" width=\"90%\" align=\"center\">";
			$this->salida .= "<tr><td>";
			$this->Cabecera();
			$this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\">";
			$this->salida .=  $this->SetStyle("MensajeError");
			$this->salida .= "</table><br>";
			$this->salida .= "</td></tr>";
			$this->salida .= "</table>";
			$accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','VerDetalleReporteEstadisticoOS',array("centroU"=>$centroU,"unidadF"=>$unidadF,"DptoSel"=>$DptoSel,"profesional_escojer"=>$profesional_escojer,"feinictra"=>$feinictra,"fefinctra"=>$fefinctra));
			$this->salida .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
			$this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_list_oscuro\">";
			$this->salida .= "<tr>";
			$this->salida .= "<td align=\"center\" class=\"modulo_table_list_title\" colspan=\"2\">DATOS DE LA BUSQUEDA</td>";
			$this->salida .= "</tr>";
			$this->salida .= "<tr>";
			$this->salida .= "<td class=\"modulo_table_title\" width=\"40%\" align=\"left\">CENTRO DE UTILIDAD</td>";
			if(!empty($centroutilidad))
			{ $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\">".$centroutilidad."</td>"; }
			else
			{ $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\"><label class=\"label_mark\">SIN DATOS</label></td>"; }
			$this->salida .= "</tr>";
			
			$this->salida .= "<tr>";
			$this->salida .= "<td class=\"modulo_table_title\" width=\"40%\" align=\"left\">UNIDAD FUNCIONAL</td>";
			if(!empty($unidadfunc))
			{ $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\">".$unidadfunc."</td>"; }
			else
			{ $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\"><label class=\"label_mark\">SIN DATOS</label></td>"; }
			$this->salida .= "</tr>";
			
			$this->salida .= "<tr>";
			$this->salida .= "<td class=\"modulo_table_title\" width=\"40%\" align=\"left\">DEPARTAMENTO</td>";
			if(!empty($departamento))
			{ $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\">".$departamento."</td>"; }
			else
			{ $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\"><label class=\"label_mark\">SIN DATOS</label></td>"; }
			$this->salida .= "</tr>";
						
			$usuario_id = explode(',',$profesional_escojer);

			$this->salida .= "<tr>";
			$this->salida .= "<td class=\"modulo_table_title\" width=\"40%\" align=\"left\">PROFESIONAL</td>";
			if(!empty($usuario_id[1]))
			{ $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\">".$usuario_id[1]."</td>"; }
			else
			{ $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\"><label class=\"label_mark\">SIN DATOS</label></td>"; }
			$this->salida .= "</tr>";
						
			$this->salida .= "<tr>";
			$this->salida .= "<td class=\"modulo_table_title\" width=\"40%\" align=\"left\">FECHA INICIAL</td>";
			if(!empty($feinictra))
			{ $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\">".$feinictra."</td>"; }
			else
			{ $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\"><label class=\"label_mark\">SIN DATOS</label></td>"; }
			$this->salida .= "</tr>";

			$this->salida .= "<tr>";
			$this->salida .= "<td class=\"modulo_table_title\" width=\"40%\" align=\"left\">FECHA FINAL</td>";
			if(!empty($fefinctra))
			{ $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\">".$fefinctra."</td>"; }
			else
			{ $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\"><label class=\"label_mark\">SIN DATOS</label></td>"; }
			$this->salida .= "</tr>";

			$this->salida .= "</table><br>";
			
			$this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_list_oscuro\">";			
			$this->salida .= "<tr>";
			$this->salida .= "<td align=\"center\" class=\"modulo_table_list_title\" colspan=\"2\">ESTADISTICAS</td>";
      $this->salida .= "</tr>"; 
			if($total_apoyos){         		
			$this->salida .= "<tr>";
			$this->salida .= "<td colspan=\"2\" class=\"modulo_table_title\" width=\"50%\" align=\"left\">APOYOS DIAGNOSTICOS</td>";			
			$this->salida .= "</tr>";
			$suma=0;
				foreach($total_apoyos as $id=>$datos){
					$this->salida .= "<tr>";
					$this->salida .= "<td class=\"modulo_list_claro\" width=\"50%\" align=\"left\">".$datos['descripcion']."</td>";			
					$this->salida .= "<td class=\"modulo_list_claro\" width=\"50%\" align=\"left\">".$datos['total_tipo']."</td>";			
					$this->salida .= "</tr>";	
					$suma+=$datos['total_tipo'];
				}
				$this->salida .= "<tr class=\"modulo_list_claro\" >";
				$this->salida .= "<td width=\"50%\" align=\"left\" class=\"label\">TOTAL</td>";			
				$this->salida .= "<td width=\"50%\" align=\"left\" class=\"label\">".$suma."</td>";			
				$this->salida .= "</tr>";	
			}
			if($total_Int){
			$this->salida .= "<tr>";
			$this->salida .= "<td colspan=\"2\" class=\"modulo_table_title\" width=\"50%\" align=\"left\">INTERCONSULTAS</td>";			
			$this->salida .= "</tr>";
			$suma=0;
				foreach($total_Int as $id=>$datos){
					$this->salida .= "<tr>";
					$this->salida .= "<td class=\"modulo_list_claro\" width=\"50%\" align=\"left\">".$datos['descripcion']."</td>";			
					$this->salida .= "<td class=\"modulo_list_claro\" width=\"50%\" align=\"left\">".$datos['total_tipo']."</td>";			
					$this->salida .= "</tr>";	
					$suma+=$datos['total_tipo'];
				}
				$this->salida .= "<tr class=\"modulo_list_claro\" >";
				$this->salida .= "<td width=\"50%\" align=\"left\" class=\"label\">TOTAL</td>";			
				$this->salida .= "<td width=\"50%\" align=\"left\" class=\"label\">".$suma."</td>";			
				$this->salida .= "</tr>";	
			}
			
			if($total_Inca){
			$this->salida .= "<tr>";
			$this->salida .= "<td colspan=\"2\" class=\"modulo_table_title\" width=\"50%\" align=\"left\">INCAPACIDADES</td>";			
			$this->salida .= "</tr>";
			$suma=0;
				foreach($total_Inca as $id=>$datos){
					$this->salida .= "<tr>";
					$this->salida .= "<td class=\"modulo_list_claro\" width=\"50%\" align=\"left\">".$datos['descripcion']."</td>";			
					$this->salida .= "<td class=\"modulo_list_claro\" width=\"50%\" align=\"left\">".$datos['total_tipo']."</td>";			
					$this->salida .= "</tr>";	
					$suma+=$datos['total_tipo'];
				}
				$this->salida .= "<tr class=\"modulo_list_claro\" >";
				$this->salida .= "<td width=\"50%\" align=\"left\" class=\"label\">TOTAL</td>";			
				$this->salida .= "<td width=\"50%\" align=\"left\" class=\"label\">".$suma."</td>";			
				$this->salida .= "</tr>";	
			}
						
			$reporte= new GetReports();
			$mostrar=$reporte->GetJavaReport('app','Reportes_Consulta_Externa','ReporteEstadisticasOrdenServicioDetalle',array('total_apoyos'=>$total_apoyos,'total_Int'=>$total_Int,'total_Inca'=>$total_Inca,'variables'=>$_REQUEST),array('rpt_name'=>'ConsultaExternas','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));		
			$nombre_funcion=$reporte->GetJavaFunction();
			$this->salida .=$mostrar;
			echo $mostrar;		
			$this->salida .= "<tr>";
			$this->salida .= "<td align=\"center\" class=\"modulo_list_oscuro\" colspan=\"3\"><a href=\"javascript:$nombre_funcion\"><b>Imprimir Reporte</b></a></td>";         					
			$this->salida .= "</tr>";
			$this->salida .= "</table><br>";
			$this->salida .= "</form>";
			$actionM=ModuloGetURL('app','Reportes_Consulta_Externa','user','RegistrosReporteOrdenesServicio',array("centroU"=>$centroU,"centroutilidad"=>$centroutilidad,"unidadF"=>$unidadF,"unidadfunc"=>$unidadfunc,
			"DptoSel"=>$DptoSel,"departamento"=>$departamento,"profesional_escojer"=>$profesional_escojer,"feinictra"=>$feinictra,"fefinctra"=>$fefinctra,
			"Total_consulta"=>$Total_consultaT,"total_frmedicas"=>$total_frmedicasT,"total_apoyos"=>$total_apoyosT,"total_Qx"=>$total_QxT,"total_NoQx"=>$total_NoQxT,"total_Int"=>$total_IntT,"total_Inca"=>$total_IncaT));
			$this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
			$this->salida .= "<br><table align=\"center\" width=\"100%\"><tr><td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"><br></td></tr></table></form>";
			$this->salida .= "</form>";
			$this->salida .= ThemeCerrarTabla();        
     	return true;
			
		}

     
     function FormaReporteCausasCitasMedicas($centroutilidad,$centroU,$unidadfunc,$unidadF,$departamento,$DptoSel)
     {
		 
		 		/*echo $centroutilidad;
				echo '-';
				echo $centroU;
				echo '-';
				echo $unidadfunc;
				echo '-';
				echo $unidadF;
				echo '-';
				echo $departamento;
				echo '-';
				echo $DptoSel;*/
		unset($_SESSION['reconeccc']);
		unset($_SESSION['CAUSAS_CITAS']['DATOS']);

          $this->salida = ThemeAbrirTabla('FILTRO DE DATOS PARA EL REPORTE DE CAUSAS DE CONSULTAS MÉDICAS');

          /*$RUTA = "app_modules/Reportes_Consulta_Externa/buscador.php?sign=";
          $mostrar ="\n<script language='javascript'>\n";
          $mostrar.="var rem=\"\";\n";
          $mostrar.="  function xxx(a){\n";
          $mostrar.="    var nombre=\"\"\n";
          $mostrar.="    var url2=\"\"\n";
          $mostrar.="    var str=\"\"\n";
          $mostrar.="    var nombre=\"REPORTE\";\n";
          $mostrar.="    var str =\"width=450,height=180,resizable=no,location=no, status=no,scrollbars=yes\";\n";
          $mostrar.="    var url2 ='$RUTA';\n";
          $mostrar.="    url2 +=a;\n";
          $mostrar.="    rem = window.open(url2, nombre, str)};\n";
		$mostrar.="</script>\n";
          $this->salida .= $mostrar;
*/
          $this->salida .= "  <table border=\"0\" width=\"50%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->Cabecera();
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .=    $this->SetStyle("MensajeError");
		$this->salida .= "  </table><br>";
		$accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','RegistrosReporteCausasCitasMedicas');
		$this->salida .= "  <form name=\"data\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  		<table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  		<tr>";
		$this->salida .= "  		<td>";
		$this->salida .= "  		<fieldset><legend class=\"field\">INGRESO DE DATOS</legend>";
		
          $this->salida .= "<br><table border=\"0\" width=\"95%\" align=\"center\">";
          /*$this->salida .= "<tr><td width=\"15%\" class=\"label\">CENTRO UTILIDAD: </td>";
          $this->salida .= "<td width=\"15%\"><input type=\"text\" class=\"input-text\" name=\"centroutilidad\" value=\"".$_REQUEST['centroutilidad']."\" maxlength=\"100\" size=\"40\" readonly></td></tr>";
          $this->salida .= "<tr><td width=\"20%\" class=\"label\">UNIDAD FUNCIONAL: </td>";
          $this->salida .= "<td width=\"15%\"><input type=\"text\" class=\"input-text\" name=\"unidadfunc\" value=\"".$_REQUEST['unidadfunc']."\" maxlength=\"100\" size=\"40\" readonly></td></tr>";
          $this->salida .= "<tr><td width=\"15%\" class=\"label\">DEPARTAMENTO: </td>";
          $this->salida .= "<td width=\"15%\"><input type=\"text\" class=\"input-text\" name=\"departamento\" value=\"".$_REQUEST['departamento']."\" maxlength=\"100\" size=\"40\" readonly>";
          $this->salida .= "&nbsp;<a href=\"javascript:xxx(1)\"><img title='Busqueda del Departamento' src=\"". GetThemePath() ."/images/auditoria.png\" border='0' width='14' height='14'></td></tr>";
*/
					$this->salida .= "<input type=\"hidden\" name=\"centroutilidad\" value=\"".$centroutilidad."\" class=\"input-text\">";
					$this->salida .= "<input type=\"hidden\" name=\"unidadfunc\" value=\"".$unidadfunc."\" class=\"input-text\">";
					$this->salida .= "<input type=\"hidden\" name=\"departamento\" value=\"".$departamento."\" class=\"input-text\">";
					$this->salida .= "<input type=\"hidden\" name=\"centroU\" value=\"".$centroU."\" class=\"input-text\">";
					$this->salida .= "<input type=\"hidden\" name=\"unidadF\" value=\"".$unidadF."\" class=\"input-text\">";
					$this->salida .= "<input type=\"hidden\" name=\"DptoSel\" value=\"".$DptoSel."\" class=\"input-text\">";

		      $this->salida .= "<tr><td class=\"label\">PROFESIONAL: </td><td><select name=\"profesional_escojer\" class=\"select\">";          
          $vector_P=$this->Get_Profesionales();
          if(sizeof($vector_P)>1){
            $this->salida .= "      <option value=\"-1\">--  SELECCIONE--</option>";
          }
          $this->GetHtmlProfesional($vector_P,$_REQUEST['profesional_escojer']);
          $this->salida .= "</select></td></tr>";
          
          $this->salida .= "      <tr>";
          $this->salida .= "      <td width=\"30%\">";
          $this->salida .= "      <label class=\"label\">FECHA INICIAL</label>";
          $this->salida .= "      </td>";
          $this->salida .= "      <td class=\"label\">";
          if(empty($_REQUEST['feinictra'])){
               $_REQUEST['feinictra']=date('01/m/Y');
          }
          $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"feinictra\" value=\"".$_REQUEST['feinictra']."\" maxlength=\"10\" size=\"10\">";
          $this->salida .= "      ".ReturnOpenCalendario('data','feinictra','/')."";
          $this->salida .= "      </td>";
          $this->salida .= "      </tr>";
          $this->salida .= "      <tr>";
          $this->salida .= "      <td width=\"30%\">";
          $this->salida .= "      <label class=\"label\">FECHA FINAL</label>";
          $this->salida .= "      </td>";
          $this->salida .= "      <td class=\"label\">";
          if(empty($_REQUEST['fefinctra'])){
               $_REQUEST['fefinctra']=date('d/m/Y');
          }
          $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fefinctra\" value=\"".$_REQUEST['fefinctra']."\" maxlength=\"10\" size=\"10\">";
          $this->salida .= "      ".ReturnOpenCalendario('data','fefinctra','/')."";
          $this->salida .= "      </td>";
          $this->salida .= "      </tr>";
          $this->salida .= "      <tr>";
          $this->salida .= "      <td colspan=\"2\" align=\"center\"><br>";
          $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"GENERAR REPORTE\">";
          $this->salida .= "      </td>";
          $this->salida .= "  		</form>";
          $this->salida .= "      </tr>";
          $this->salida .= "  		</fieldset>";
          $this->salida .= "  		</table><br>";
          $this->salida .= "  		</td>";
          $this->salida .= "  		</tr>";
          $this->salida .= "  		</table>";
          $this->salida .= "  </td></tr>";
          $this->salida .= "  <tr>";
          $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','GuardarSeleccionDepartamentoUnificado',array("centroutilidad"=>$centroutilidad,"centroU"=>$centroU,"unidadfunc"=>$unidadfunc,"unidadF"=>$unidadF,"departamento"=>$departamento,"DptoSel"=>$DptoSel));
          $this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
          $this->salida .= "  <td align=\"center\">";
          $this->salida.= "   <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
          $this->salida .= "  </td>";
          $this->salida .= "  </form>";
          $this->salida .= "  </tr>";
          $this->salida .= "	</table>";
          $this->salida .= ThemeCerrarTabla();
          return true;
	}


     function FormaReporteCaracteristicasPacientes($centroutilidad,$centroU,$unidadfunc,$unidadF,$departamento,$DptoSel)
     {
		unset($_SESSION['reconeccc']);
		unset($_SESSION['CAUSAS_CITAS']['DATOS']);
          
          $this->salida = ThemeAbrirTabla('FILTRO DE DATOS PARA EL REPORTE DE CARACTERISTICAS DE PACIENTES');
          
          /*$RUTA = "app_modules/Reportes_Consulta_Externa/buscador.php?sign=";
          $mostrar ="\n<script language='javascript'>\n";
          $mostrar.="var rem=\"\";\n";
          $mostrar.="  function xxx(a){\n";
          $mostrar.="    var nombre=\"\"\n";
          $mostrar.="    var url2=\"\"\n";
          $mostrar.="    var str=\"\"\n";
          $mostrar.="    var nombre=\"REPORTE\";\n";
          $mostrar.="    var str =\"width=450,height=180,resizable=no,location=no, status=no,scrollbars=yes\";\n";
          $mostrar.="    var url2 ='$RUTA';\n";
          $mostrar.="    url2 +=a;\n";
          $mostrar.="    rem = window.open(url2, nombre, str)};\n";
		$mostrar.="</script>\n";
          $this->salida .= $mostrar;
          */
          $this->salida .= "  <table border=\"0\" width=\"50%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->Cabecera();
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .=    $this->SetStyle("MensajeError");
		$this->salida .= "  </table><br>";
		$accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','RegistrosReporteCaracteristicasPaciente');
		$this->salida .= "  <form name=\"data\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  		<table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  		<tr>";
		$this->salida .= "  		<td>";
		$this->salida .= "  		<fieldset><legend class=\"field\">INGRESO DE DATOS</legend>";
		
          $this->salida .= "<br><table border=\"0\" width=\"95%\" align=\"center\">";
          /*$this->salida .= "<tr><td width=\"15%\" class=\"label\">CENTRO UTILIDAD: </td>";
          $this->salida .= "<td width=\"15%\"><input type=\"text\" class=\"input-text\" name=\"centroutilidad\" value=\"".$_REQUEST['centroutilidad']."\" maxlength=\"100\" size=\"40\" readonly></td></tr>";
          $this->salida .= "<tr><td width=\"20%\" class=\"label\">UNIDAD FUNCIONAL: </td>";
          $this->salida .= "<td width=\"15%\"><input type=\"text\" class=\"input-text\" name=\"unidadfunc\" value=\"".$_REQUEST['unidadfunc']."\" maxlength=\"100\" size=\"40\" readonly></td></tr>";
          $this->salida .= "<tr><td width=\"15%\" class=\"label\">DEPARTAMENTO: </td>";
          $this->salida .= "<td width=\"15%\"><input type=\"text\" class=\"input-text\" name=\"departamento\" value=\"".$_REQUEST['departamento']."\" maxlength=\"100\" size=\"40\" readonly>";
          $this->salida .= "&nbsp;<a href=\"javascript:xxx(1)\"><img title='Busqueda del Departamento' src=\"". GetThemePath() ."/images/auditoria.png\" border='0' width='14' height='14'></td></tr>";
          */
					$this->salida .= "<input type=\"hidden\" name=\"centroutilidad\" value=\"".$centroutilidad."\" class=\"input-text\">";
					$this->salida .= "<input type=\"hidden\" name=\"unidadfunc\" value=\"".$unidadfunc."\" class=\"input-text\">";
					$this->salida .= "<input type=\"hidden\" name=\"departamento\" value=\"".$departamento."\" class=\"input-text\">";
					$this->salida .= "<input type=\"hidden\" name=\"centroU\" value=\"".$centroU."\" class=\"input-text\">";
					$this->salida .= "<input type=\"hidden\" name=\"unidadF\" value=\"".$unidadF."\" class=\"input-text\">";
					$this->salida .= "<input type=\"hidden\" name=\"DptoSel\" value=\"".$DptoSel."\" class=\"input-text\">";
		         
          
          $this->salida .= "<tr><td class=\"label\">PROFESIONAL: </td><td><select name=\"profesional_escojer\" class=\"select\">";          
          $vector_P=$this->Get_Profesionales();
          if(sizeof($vector_P)>1){
            $this->salida .= "      <option value=\"-1\">--  SELECCIONE--</option>";
          }
          $this->GetHtmlProfesional($vector_P,$_REQUEST['profesional_escojer']);
          $this->salida .= "</select></td></tr>";
          
          $this->salida .= "      <tr>";
          $this->salida .= "      <td width=\"30%\">";
          $this->salida .= "      <label class=\"label\">FECHA INICIAL</label>";
          $this->salida .= "      </td>";
          $this->salida .= "      <td class=\"label\">";
          if(empty($_REQUEST['feinictra'])){
               $_REQUEST['feinictra']=date('01/m/Y');
          }
          $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"feinictra\" value=\"".$_REQUEST['feinictra']."\" maxlength=\"10\" size=\"10\">";
          $this->salida .= "      ".ReturnOpenCalendario('data','feinictra','/')."";
          $this->salida .= "      </td>";
          $this->salida .= "      </tr>";
          $this->salida .= "      <tr>";
          $this->salida .= "      <td width=\"30%\">";
          $this->salida .= "      <label class=\"label\">FECHA FINAL</label>";
          $this->salida .= "      </td>";
          $this->salida .= "      <td class=\"label\">";
          if(empty($_REQUEST['fefinctra'])){
               $_REQUEST['fefinctra']=date('d/m/Y');
          }
          $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fefinctra\" value=\"".$_REQUEST['fefinctra']."\" maxlength=\"10\" size=\"10\">";
          $this->salida .= "      ".ReturnOpenCalendario('data','fefinctra','/')."";
          $this->salida .= "      </td>";
          $this->salida .= "      </tr>";
          $this->salida .= "      <tr>";
          $this->salida .= "      <td colspan=\"2\" align=\"center\"><br>";
          $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"GENERAR REPORTE\">";
          $this->salida .= "      </td>";
          $this->salida .= "  		</form>";
          $this->salida .= "      </tr>";
          $this->salida .= "  		</fieldset>";
          $this->salida .= "  		</table><br>";
          $this->salida .= "  		</td>";
          $this->salida .= "  		</tr>";
          $this->salida .= "  		</table>";
          $this->salida .= "  </td></tr>";
          $this->salida .= "  <tr>";
          $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','GuardarSeleccionDepartamentoUnificado',array("centroutilidad"=>$centroutilidad,"centroU"=>$centroU,"unidadfunc"=>$unidadfunc,"unidadF"=>$unidadF,"departamento"=>$departamento,"DptoSel"=>$DptoSel));
          $this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
          $this->salida .= "  <td align=\"center\">";
          $this->salida.= "   <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
          $this->salida .= "  </td>";
          $this->salida .= "  </form>";
          $this->salida .= "  </tr>";
          $this->salida .= "	</table>";
          $this->salida .= ThemeCerrarTabla();
          return true;
	}

     
     function FormaReporteCitasTratamientoOdontologico($centroutilidad,$centroU,$unidadfunc,$unidadF,$departamento,$DptoSel)
     {
					unset($_SESSION['reconeccc']);
					unset($_SESSION['CAUSAS_CITAS']['DATOS']);
          
          $this->salida = ThemeAbrirTabla('FILTRO DE DATOS PARA EL REPORTE DE CITAS DE TRATAMIENTO ODONTOLOGICO');
          
          /*$RUTA = "app_modules/Reportes_Consulta_Externa/buscador.php?sign=";
          $mostrar ="\n<script language='javascript'>\n";
          $mostrar.="var rem=\"\";\n";
          $mostrar.="  function xxx(a){\n";
          $mostrar.="    var nombre=\"\"\n";
          $mostrar.="    var url2=\"\"\n";
          $mostrar.="    var str=\"\"\n";
          $mostrar.="    var nombre=\"REPORTE\";\n";
          $mostrar.="    var str =\"width=450,height=180,resizable=no,location=no, status=no,scrollbars=yes\";\n";
          $mostrar.="    var url2 ='$RUTA';\n";
          $mostrar.="    url2 +=a;\n";
          $mostrar.="    rem = window.open(url2, nombre, str)};\n";
					$mostrar.="</script>\n";
          $this->salida .= $mostrar;
          */
          $this->salida .= "  <table border=\"0\" width=\"50%\" align=\"center\">";
					$this->salida .= "  <tr><td>";
					$this->Cabecera();
					$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
					$this->salida .=    $this->SetStyle("MensajeError");
					$this->salida .= "  </table><br>";
					$accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','RegistrosReporteCitasTratamientoOdontologico');
					$this->salida .= "  <form name=\"data\" action=\"$accion\" method=\"post\">";
					$this->salida .= "  		<table border=\"0\" width=\"100%\" align=\"center\">";
					$this->salida .= "  		<tr>";
					$this->salida .= "  		<td>";
					$this->salida .= "  		<fieldset><legend class=\"field\">INGRESO DE DATOS</legend>";
		
          $this->salida .= "<br><table border=\"0\" width=\"95%\" align=\"center\">";
          /*$this->salida .= "<tr><td width=\"15%\" class=\"label\">CENTRO UTILIDAD: </td>";
          $this->salida .= "<td width=\"15%\"><input type=\"text\" class=\"input-text\" name=\"centroutilidad\" value=\"".$_REQUEST['centroutilidad']."\" maxlength=\"100\" size=\"40\" readonly></td></tr>";
          $this->salida .= "<tr><td width=\"20%\" class=\"label\">UNIDAD FUNCIONAL: </td>";
          $this->salida .= "<td width=\"15%\"><input type=\"text\" class=\"input-text\" name=\"unidadfunc\" value=\"".$_REQUEST['unidadfunc']."\" maxlength=\"100\" size=\"40\" readonly></td></tr>";
          $this->salida .= "<tr><td width=\"15%\" class=\"label\">DEPARTAMENTO: </td>";
          $this->salida .= "<td width=\"15%\"><input type=\"text\" class=\"input-text\" name=\"departamento\" value=\"".$_REQUEST['departamento']."\" maxlength=\"100\" size=\"40\" readonly>";
          $this->salida .= "&nbsp;<a href=\"javascript:xxx(1)\"><img title='Busqueda del Departamento' src=\"". GetThemePath() ."/images/auditoria.png\" border='0' width='14' height='14'></td></tr>";
          */
					$this->salida .= "<input type=\"hidden\" name=\"centroutilidad\" value=\"".$centroutilidad."\" class=\"input-text\">";
					$this->salida .= "<input type=\"hidden\" name=\"unidadfunc\" value=\"".$unidadfunc."\" class=\"input-text\">";
					$this->salida .= "<input type=\"hidden\" name=\"departamento\" value=\"".$departamento."\" class=\"input-text\">";
					$this->salida .= "<input type=\"hidden\" name=\"centroU\" value=\"".$centroU."\" class=\"input-text\">";
					$this->salida .= "<input type=\"hidden\" name=\"unidadF\" value=\"".$unidadF."\" class=\"input-text\">";
					$this->salida .= "<input type=\"hidden\" name=\"DptoSel\" value=\"".$DptoSel."\" class=\"input-text\">";          
          
          $this->salida .= "<tr><td class=\"label\">PROFESIONAL: </td><td><select name=\"profesional_escojer\" class=\"select\">";          
          $vector_P=$this->Get_Profesionales();
          if(sizeof($vector_P)>1){
            $this->salida .= "      <option value=\"-1\">--  SELECCIONE--</option>";
          }
          $this->GetHtmlProfesional($vector_P,$_REQUEST['profesional_escojer']);
          $this->salida .= "</select></td></tr>";
          
          $this->salida .= "      <tr>";
          $this->salida .= "      <td width=\"30%\">";
          $this->salida .= "      <label class=\"label\">FECHA INICIAL</label>";
          $this->salida .= "      </td>";
          $this->salida .= "      <td class=\"label\">";
          if(empty($_REQUEST['feinictra'])){
               $_REQUEST['feinictra']=date('01/m/Y');
          }
          $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"feinictra\" value=\"".$_REQUEST['feinictra']."\" maxlength=\"10\" size=\"10\">";
          $this->salida .= "      ".ReturnOpenCalendario('data','feinictra','/')."";
          $this->salida .= "      </td>";
          $this->salida .= "      </tr>";
          $this->salida .= "      <tr>";
          $this->salida .= "      <td width=\"30%\">";
          $this->salida .= "      <label class=\"label\">FECHA FINAL</label>";
          $this->salida .= "      </td>";
          $this->salida .= "      <td class=\"label\">";
          if(empty($_REQUEST['fefinctra'])){
               $_REQUEST['fefinctra']=date('d/m/Y');
          }
          $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fefinctra\" value=\"".$_REQUEST['fefinctra']."\" maxlength=\"10\" size=\"10\">";
          $this->salida .= "      ".ReturnOpenCalendario('data','fefinctra','/')."";
          $this->salida .= "      </td>";
          $this->salida .= "      </tr>";
          $this->salida .= "      <tr>";
          $this->salida .= "      <td colspan=\"2\" align=\"center\"><br>";
          $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"GENERAR REPORTE\">";
          $this->salida .= "      </td>";
          $this->salida .= "  		</form>";
          $this->salida .= "      </tr>";
          $this->salida .= "  		</fieldset>";
          $this->salida .= "  		</table><br>";
          $this->salida .= "  		</td>";
          $this->salida .= "  		</tr>";
          $this->salida .= "  		</table>";
          $this->salida .= "  </td></tr>";
          $this->salida .= "  <tr>";
          $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','GuardarSeleccionDepartamentoUnificado',array("centroutilidad"=>$centroutilidad,"centroU"=>$centroU,"unidadfunc"=>$unidadfunc,"unidadF"=>$unidadF,"departamento"=>$departamento,"DptoSel"=>$DptoSel));
          $this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
          $this->salida .= "  <td align=\"center\">";
          $this->salida.= "   <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
          $this->salida .= "  </td>";
          $this->salida .= "  </form>";
          $this->salida .= "  </tr>";
          $this->salida .= "	</table>";
          $this->salida .= ThemeCerrarTabla();
          return true;
	}

               
     function FormaReporteEstadisticoOrdenesServicio($centroutilidad,$centroU,$unidadfunc,$unidadF,$departamento,$DptoSel)
     {
		unset($_SESSION['reconeccc']);
		unset($_SESSION['CAUSAS_CITAS']['DATOS']);
          
          $this->salida = ThemeAbrirTabla('FILTRO DE DATOS PARA EL REPORTE DE ORDENES DE SERVICIO');
          
          /*$RUTA = "app_modules/Reportes_Consulta_Externa/buscador.php?sign=";
          $mostrar ="\n<script language='javascript'>\n";
          $mostrar.="var rem=\"\";\n";
          $mostrar.="  function xxx(a){\n";
          $mostrar.="    var nombre=\"\"\n";
          $mostrar.="    var url2=\"\"\n";
          $mostrar.="    var str=\"\"\n";
          $mostrar.="    var nombre=\"REPORTE\";\n";
          $mostrar.="    var str =\"width=450,height=180,resizable=no,location=no, status=no,scrollbars=yes\";\n";
          $mostrar.="    var url2 ='$RUTA';\n";
          $mostrar.="    url2 +=a;\n";
          $mostrar.="    rem = window.open(url2, nombre, str)};\n";
		$mostrar.="</script>\n";
          $this->salida .= $mostrar;
          */
          $this->salida .= "  <table border=\"0\" width=\"50%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->Cabecera();
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .=    $this->SetStyle("MensajeError");
		$this->salida .= "  </table><br>";
		$accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','RegistrosReporteOrdenesServicio');
		$this->salida .= "  <form name=\"data\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  		<table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  		<tr>";
		$this->salida .= "  		<td>";
		$this->salida .= "  		<fieldset><legend class=\"field\">INGRESO DE DATOS</legend>";
		
          $this->salida .= "<br><table border=\"0\" width=\"95%\" align=\"center\">";
          /*$this->salida .= "<tr><td width=\"15%\" class=\"label\">CENTRO UTILIDAD: </td>";
          $this->salida .= "<td width=\"15%\"><input type=\"text\" class=\"input-text\" name=\"centroutilidad\" value=\"".$_REQUEST['centroutilidad']."\" maxlength=\"100\" size=\"40\" readonly></td></tr>";
          $this->salida .= "<tr><td width=\"20%\" class=\"label\">UNIDAD FUNCIONAL: </td>";
          $this->salida .= "<td width=\"15%\"><input type=\"text\" class=\"input-text\" name=\"unidadfunc\" value=\"".$_REQUEST['unidadfunc']."\" maxlength=\"100\" size=\"40\" readonly></td></tr>";
          $this->salida .= "<tr><td width=\"15%\" class=\"label\">DEPARTAMENTO: </td>";
          $this->salida .= "<td width=\"15%\"><input type=\"text\" class=\"input-text\" name=\"departamento\" value=\"".$_REQUEST['departamento']."\" maxlength=\"100\" size=\"40\" readonly>";
          $this->salida .= "&nbsp;<a href=\"javascript:xxx(1)\"><img title='Busqueda del Departamento' src=\"". GetThemePath() ."/images/auditoria.png\" border='0' width='14' height='14'></td></tr>";
          */
					$this->salida .= "<input type=\"hidden\" name=\"centroutilidad\" value=\"".$centroutilidad."\" class=\"input-text\">";
					$this->salida .= "<input type=\"hidden\" name=\"unidadfunc\" value=\"".$unidadfunc."\" class=\"input-text\">";
					$this->salida .= "<input type=\"hidden\" name=\"departamento\" value=\"".$departamento."\" class=\"input-text\">";
					$this->salida .= "<input type=\"hidden\" name=\"centroU\" value=\"".$centroU."\" class=\"input-text\">";
					$this->salida .= "<input type=\"hidden\" name=\"unidadF\" value=\"".$unidadF."\" class=\"input-text\">";
					$this->salida .= "<input type=\"hidden\" name=\"DptoSel\" value=\"".$DptoSel."\" class=\"input-text\">";
          
          $this->salida .= "<tr><td class=\"label\">PROFESIONAL: </td><td><select name=\"profesional_escojer\" class=\"select\">";          
          $vector_P=$this->Get_Profesionales();
          if(sizeof($vector_P)>1){
            $this->salida .= "      <option value=\"-1\">--  SELECCIONE--</option>";
          }
          $this->GetHtmlProfesional($vector_P,$_REQUEST['profesional_escojer']);
          $this->salida .= "</select></td></tr>";
          
          $this->salida .= "      <tr>";
          $this->salida .= "      <td width=\"30%\">";
          $this->salida .= "      <label class=\"label\">FECHA INICIAL</label>";
          $this->salida .= "      </td>";
					if(empty($_REQUEST['feinictra'])){
								$_REQUEST['feinictra']=date('01/m/Y');
					}
          $this->salida .= "      <td class=\"label\">";
          $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"feinictra\" value=\"".$_REQUEST['feinictra']."\" maxlength=\"10\" size=\"10\">";
          $this->salida .= "      ".ReturnOpenCalendario('data','feinictra','/')."";
          $this->salida .= "      </td>";
          $this->salida .= "      </tr>";
          $this->salida .= "      <tr>";
          $this->salida .= "      <td width=\"30%\">";
          $this->salida .= "      <label class=\"label\">FECHA FINAL</label>";
          $this->salida .= "      </td>";
          $this->salida .= "      <td class=\"label\">";
					if(empty($_REQUEST['fefinctra'])){
								$_REQUEST['fefinctra']=date('d/m/Y');
					}
          $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fefinctra\" value=\"".$_REQUEST['fefinctra']."\" maxlength=\"10\" size=\"10\">";
          $this->salida .= "      ".ReturnOpenCalendario('data','fefinctra','/')."";
          $this->salida .= "      </td>";
          $this->salida .= "      </tr>";
          $this->salida .= "      <tr>";
          $this->salida .= "      <td colspan=\"2\" align=\"center\"><br>";
          $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"GENERAR REPORTE\">";
          $this->salida .= "      </td>";
          $this->salida .= "  		</form>";
          $this->salida .= "      </tr>";
          
          $this->salida .= "  		</fieldset>";
          $this->salida .= "  		</table><br>";
          $this->salida .= "  		</td>";
          $this->salida .= "  		</tr>";
          $this->salida .= "  		</table>";
          $this->salida .= "  </td></tr>";
          $this->salida .= "  <tr>";
          $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','GuardarSeleccionDepartamentoUnificado',array("centroutilidad"=>$centroutilidad,"centroU"=>$centroU,"unidadfunc"=>$unidadfunc,"unidadF"=>$unidadF,"departamento"=>$departamento,"DptoSel"=>$DptoSel));
          $this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
          $this->salida .= "  <td align=\"center\">";
          $this->salida.= "   <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
          $this->salida .= "  </td>";
          $this->salida .= "  </form>";
          $this->salida .= "  </tr>";
          $this->salida .= "	</table>";
          $this->salida .= ThemeCerrarTabla();
          return true;
	}

         
	function ReporteCitasCanceladas($depto,$tipoconsul,$profesional,$feinictra,$fefinctra)
     {$archivoPlano='';
		$this->salida  = ThemeAbrirTabla('REPORTE ESTADISTICO DE MOTIVOS DE CANCELACION DE CITAS MÉDICAS');
		$this->Cabecera();
		$reporte= new GetReports();//FALSE
		$mostrar=$reporte->GetJavaReport('app','Reportes_Consulta_Externa','ReporteCancelacionCitasCE',array('datos'=>$_SESSION['reconecc'],'variables'=>$_REQUEST),array('rpt_name'=>'ConsultaExternas','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
		$funcion=$reporte->GetJavaFunction();
		$this->salida .= "$mostrar";
		$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "<tr><td>";
		$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
          if(!empty($_REQUEST['centroutilidad']))
		{
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">CENTRO DE UTILIDAD";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "      ".$_REQUEST['centroutilidad']."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}

		if(!empty($_REQUEST['unidadfunc']))
		{
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">UNIDAD FUNCIONAL";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "      ".$_REQUEST['unidadfunc']."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}

		if(!empty($_REQUEST['departamento']))
		{
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DEPARTAMENTO";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "      ".$_REQUEST['departamento']."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}
		if($_SESSION['reconecc']['documentos']<>NULL){
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL PROFESIONAL";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "      ".$_SESSION['reconecc']['nombreprof']."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}
		if($_SESSION['reconecc']['justificacion']<>NULL){
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">JUSTIFICACION";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "      ".strtoupper($_SESSION['reconecc']['justificacion'])."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}
		if($_SESSION['reconecc']['fechadesde']<>NULL){
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">FECHA INICIAL";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "      ".$_SESSION['reconecc']['fechadesde']."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}
		if($_SESSION['reconecc']['fechahasta']<>NULL){
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">FECHA FINAL";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "      ".$_SESSION['reconecc']['fechahasta']."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}
		if(!($_SESSION['reconecc']['codigodepa']<>NULL OR
		$_SESSION['reconecc']['codigotico']<>NULL OR
		$_SESSION['reconecc']['documentos']<>NULL OR
		$_SESSION['reconecc']['fechadesde']<>NULL OR
		$_SESSION['reconecc']['fechahasta']<>NULL OR
		$_SESSION['reconecc']['justificacion']<>NULL)){
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PARAMETROS DE BUSQUEDA:";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "NINGUNO";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}
		$this->salida .= "      </table><br>";
		$datos=$_SESSION['CITAS_CANCELADAS']['DATOS'];
		$j=0;
		if($datos){
		foreach($datos as $identificacion=>$vector){
          	$justificacionAnt=-1;
			foreach($vector as $justificacion=>$vector1){
				$i=1;
				  foreach($vector1 as $identificacionPac=>$vector2){
						foreach($vector2 as $citaId=>$vectorDatos){
				    if($identificacion!=$identificacionAnt){
						  $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
							$this->salida .= "      <tr class=\"modulo_table_title\">";
							$this->salida .= "      <td width=\"4%\">No.</td>";
              $archivoPlano.='No.'.'|';
							$this->salida .= "      <td width=\"7%\" >TIPO ID</td>";
              $archivoPlano.='TIPO ID'.'|';
							$this->salida .= "      <td width=\"10%\">IDENTIFICACIÓN</td>";
              $archivoPlano.='IDENTIFICACIÓN'.'|';
							$this->salida .= "      <td width=\"45%\">NOMBRE DEL PROFESIONAL</td>";
              $archivoPlano.='NOMBRE DEL PROFESIONAL'.'|';
							$this->salida .= "      <td width=\"27%\">ESPECIALIDAD</td>";
              $archivoPlano.='ESPECIALIDAD'.'|';
							$this->salida .= "      <td width=\"7%\" >ESTADO</td>";
              $archivoPlano.='ESTADO'."\n";
							$this->salida .= "      </tr>";
							$this->salida .= "      <tr class=hc_table_submodulo_list_title>";
							$this->salida .= "      <td align=\"center\">$j</td>";
              $archivoPlano.=$j.'|';
							(list($tipoId,$Identificacion)=explode('-',$identificacion));
							$this->salida .= "      <td align=\"center\">$tipoId</td>";
              $archivoPlano.=$tipoId.'|';
							$this->salida .= "      <td align=\"center\">$Identificacion</td>";
              $archivoPlano.=$Identificacion.'|';
							$this->salida .= "      <td align=\"center\">".$vectorDatos['nombre_tercero']."</td>";
              $archivoPlano.=$vectorDatos['nombre_tercero'].'|';
							$this->salida .= "      <td align=\"center\">".$vectorDatos['especialidad']."</td>";
              $archivoPlano.=$vectorDatos['especialidad'].'|';
							if($vectorDatos['estado']=='0'){
								$this->salida .= "    <td align=\"center\">INACTIVO</td>";
                $archivoPlano.='INACTIVO'."\n";
							}elseif($vectorDatos['estado']=='1'){
								$this->salida .= "    <td align=\"center\">ACTIVO</td>";
                $archivoPlano.='ACTIVO'."\n";
							}
              $this->salida .= "      </tr>";
              $identificacionAnt=$identificacion;
						if($justificacion!=$justificacionAnt){
								$this->salida .= "      <tr class=modulo_list_claro>";
								$this->salida .= "      <td colspan=\"3\" class=\"modulo_table_list_title\">JUSTIFICACION INASISTENCIA</td>";
                $archivoPlano.='JUSTIFICACION INASISTENCIA'.'|';
								$this->salida .= "      <td colspan=\"3\" class=\"modulo_list_claro\"><b>".strtoupper($vectorDatos['tipojustificacion'])."</b></td>";
                $archivoPlano.='|'.'|'.strtoupper($vectorDatos['tipojustificacion'])."\n";
								$this->salida .= "      </tr>";
								$justificacionAnt=$justificacion;
								$this->salida .= "      <tr class=modulo_list_claro>";
								$this->salida .= "      <td colspan=\"6\">";
								$this->salida .= "      <table border=\"1\" width=\"100%\" align=\"center\">";
								$this->salida .= "      <tr class=\"modulo_list_claro\">";
								$this->salida .= "      <td width=\"5%\" nowrap class=\"label\">Nro.</td>";
                $archivoPlano.='Nro.'.'|';
								$this->salida .= "      <td width=\"10%\" nowrap class=\"label\">FECHA TURNO</td>";
                $archivoPlano.='FECHA TURNO'.'|';
								$this->salida .= "      <td width=\"10%\" nowrap class=\"label\">HORA</td>";
                $archivoPlano.='HORA'.'|';
								$this->salida .= "      <td width=\"10%\" nowrap class=\"label\">DURACIÓN</td>";
                $archivoPlano.='DURACIÓN'.'|';
								$this->salida .= "      <td width=\"25%\" nowrap class=\"label\">NOMBRE PACIENTE</td>";
                $archivoPlano.='NOMBRE PACIENTE'.'|';
								$this->salida .= "      <td width=\"20%\" nowrap class=\"label\">IDENT. PACIENTE</td>";
                $archivoPlano.='IDENT. PACIENTE'.'|';
								$this->salida .= "      <td width=\"20%\" nowrap class=\"label\">OPORTUNIDAD DE CANCELACION</td>";
                $archivoPlano.='OPORTUNIDAD DE CANCELACION'."\n";
								$this->salida .= "      </tr>";
								$this->salida .= "      </table>";
								$this->salida .= "      </td>";
								$this->salida .= "      </tr>";
							}
							$this->salida .= "      <tr class=modulo_list_claro>";
							$this->salida .= "      <td colspan=\"6\">";
							$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
							$this->salida .= "      <tr class=\"modulo_list_oscuro\">";
							$this->salida .= "      <td width=\"5%\"  nowrap>$i</td>";
              $archivoPlano.=$i.'|';
							$this->salida .= "      <td width=\"10%\" nowrap>".$vectorDatos['fecha_turno']."</td>";
              $archivoPlano.=$vectorDatos['fecha_turno'].'|';
							$this->salida .= "      <td width=\"10%\" nowrap>".$vectorDatos['hora']."</td>";
              $archivoPlano.=$vectorDatos['hora'].'|';
							$this->salida .= "      <td width=\"10%\" nowrap>".$vectorDatos['duracion']."</td>";
              $archivoPlano.=$vectorDatos['duracion'].'|';
							$this->salida .= "      <td width=\"25%\" nowrap>".$vectorDatos['nombre']."</td>";
              $archivoPlano.=$vectorDatos['nombre'].'|';
							$this->salida .= "      <td width=\"20%\" nowrap>".$vectorDatos['identificacionpac']."</td>";
              $archivoPlano.=$vectorDatos['identificacionpac'].'|';
                                   (list($fechaCancel,$horaTotCancel)=explode(' ',$vectorDatos['fechacancelacion']));
                                   (list($anoCancel,$mesCancel,$diaCancel)=explode('-',$fechaCancel));
                                   (list($horaCancel,$minCancel)=explode(':',$horaTotCancel));
                                   (list($anoCita,$mesCita,$diaCita)=explode('-',$vectorDatos['fecha_turno']));
                                   (list($horaCita,$minCita)=explode(':',$vectorDatos['hora']));
							$dias=(((mktime($horaCita,$minCita,0,$mesCita,$diaCita,$anoCita)-mktime($horaCancel,$minCancel,0,$mesCancel,$diaCancel,$anoCancel))/60)/60)/24;
							$this->salida .= "      <td width=\"20%\" nowrap>".round($dias,1)."</td>";
              $archivoPlano.=round($dias,1)."\n";
							$this->salida .= "      </tr>";
							$this->salida .= "      </table>";
							$this->salida .= "      </td>";
							$this->salida .= "      </tr>";
							$i++;
				    }else{
                                   if($justificacion!=$justificacionAnt){
                                        $this->salida .= "      <tr class=modulo_list_claro>";
								$this->salida .= "      <td colspan=\"3\" class=\"modulo_table_list_title\">JUSTIFICACION  INASISTENCIA</td>";
                $archivoPlano.='JUSTIFICACION  INASISTENCIA'.'|';
								$this->salida .= "      <td colspan=\"3\" class=\"modulo_list_claro\"><b>".strtoupper($vectorDatos['tipojustificacion'])."</b></td>";
                $archivoPlano.='|'.'|'.strtoupper($vectorDatos['tipojustificacion'])."\n";
								$justificacionAnt=$justificacion;
								$this->salida .= "      <tr class=modulo_list_claro>";
								$this->salida .= "      <td colspan=\"6\">";
								$this->salida .= "      <table border=\"1\" width=\"100%\" align=\"center\">";
								$this->salida .= "      <tr class=\"modulo_list_claro\">";
								$this->salida .= "      <td width=\"5%\" nowrap class=\"label\">Nro.</td>";
                $archivoPlano.='Nro.'.'|';
								$this->salida .= "      <td width=\"10%\" nowrap class=\"label\">FECHA TURNO</td>";
                $archivoPlano.='FECHA TURNO'.'|';
								$this->salida .= "      <td width=\"10%\" nowrap class=\"label\">HORA</td>";
                $archivoPlano.='HORA'.'|';
								$this->salida .= "      <td width=\"10%\" nowrap class=\"label\">DURACIÓN</td>";
                $archivoPlano.='DURACIÓN'.'|';
								$this->salida .= "      <td width=\"25%\" nowrap class=\"label\">NOMBRE PACIENTE</td>";
                $archivoPlano.='NOMBRE PACIENTE'.'|';
								$this->salida .= "      <td width=\"20%\" nowrap class=\"label\">IDENT. PACIENTE</td>";
                $archivoPlano.='IDENT. PACIENTE.'.'|';
								$this->salida .= "      <td width=\"20%\" nowrap class=\"label\">OPORTUNIDAD DE CANCELACION</td>";
                $archivoPlano.='OPORTUNIDAD DE CANCELACION'."\n";
								$this->salida .= "      </tr>";
								$this->salida .= "      </table>";
								$this->salida .= "      </td>";
								$this->salida .= "      </tr>";
							}
							$this->salida .= "      <tr class=modulo_list_claro>";
							$this->salida .= "      <td colspan=\"6\">";
							$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
							$this->salida .= "      <tr class=\"modulo_list_oscuro\">";
							$this->salida .= "      <td nowrap width=\"5%\">$i</td>";
              $archivoPlano.=$i.'|';
							$this->salida .= "      <td nowrap width=\"10%\">".$vectorDatos['fecha_turno']."</td>";
              $archivoPlano.=$vectorDatos['fecha_turno'].'|';
							$this->salida .= "      <td nowrap width=\"10%\">".$vectorDatos['hora']."</td>";
              $archivoPlano.=$vectorDatos['hora'].'|';
							$this->salida .= "      <td nowrap width=\"10%\">".$vectorDatos['duracion']."</td>";
              $archivoPlano.=$vectorDatos['duracion'].'|';
							$this->salida .= "      <td nowrap width=\"25%\">".$vectorDatos['nombre']."</td>";
              $archivoPlano.=$vectorDatos['nombre'].'|';
							$this->salida .= "      <td nowrap width=\"20%\">".$vectorDatos['identificacionpac']."</td>";
              $archivoPlano.=$vectorDatos['identificacionpac'].'|';
							(list($fechaCancel,$horaTotCancel)=explode(' ',$vectorDatos['fechacancelacion']));
              (list($anoCancel,$mesCancel,$diaCancel)=explode('-',$fechaCancel));
              (list($horaCancel,$minCancel)=explode(':',$horaTotCancel));
              (list($anoCita,$mesCita,$diaCita)=explode('-',$vectorDatos['fecha_turno']));
              (list($horaCita,$minCita)=explode(':',$vectorDatos['hora']));
							$dias=(((mktime($horaCita,$minCita,0,$mesCita,$diaCita,$anoCita)-mktime($horaCancel,$minCancel,0,$mesCancel,$diaCancel,$anoCancel))/60)/60)/24;
							$this->salida .= "      <td width=\"20%\" nowrap>".round($dias,1)."</td>";
              $archivoPlano.=round($dias,1)."\n";
							$this->salida .= "      </tr>";
							$this->salida .= "      </table>";
							$this->salida .= "      </td>";
							$this->salida .= "      </tr>";
							$i++;
						}
					}	
				  }
			  }
				$j++;
				$this->salida .= "      </table><br>";
			}

		}else{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= "      <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON DATOS CON ESTOS PARAMETROS</td></tr>";
			$this->salida .= "      </table><br>";
		}
		$this->salida .= "</td></tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td align=\"center\" colspan=\"6\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"button\" name=\"imprimir1\" value=\"IMPRIMIR REPORTE\" onclick=\"javascript:$funcion\">";
          $this->salida .="       </td></tr>";

		(list($anoH,$mesH,$diaH)=explode('-',$_SESSION['reconecc']['fechadesde']));
		(list($anoF,$mesF,$diaF)=explode('-',$_SESSION['reconecc']['fechahasta']));
		$accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaReporteCancelacionCitas',array("depto"=>$_SESSION['reconecc']['codigodepa'],
		"justificacion"=>$_SESSION['reconecc']['justificacionId'].','.$_SESSION['reconecc']['justificacion'],
		"tipoconsul"=>$_SESSION['reconecc']['codigotico'],
		"profesional"=>$_SESSION['reconecc']['tipodocume'].','.$_SESSION['reconecc']['documentos'].','.$_SESSION['reconecc']['nombreprof'],
		"feinictra"=>$diaH.'/'.$mesH.'/'.$anoH,"fefinctra"=>$diaF.'/'.$mesF.'/'.$anoF,
          'centroutilidad'=>$_REQUEST['centroutilidad'],'unidadfunc'=>$_REQUEST['unidadfunc'],
          'departamento'=>$_REQUEST['departamento'],'centroU'=>$_REQUEST['centroU'],'unidadF'=>$_REQUEST['unidadF'],'DptoSel'=>$_REQUEST['DptoSel'],$_REQUEST['profesional']));
		
    $this->salida .= "  <tr><td align=\"center\">";    
    $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";        
    $this->salida .= "      <tr><td class=\"label\" align=\"center\">";
    $_SESSION['DESCARGA_DATOS_REPORTES']['DATOS']=$archivoPlano;                    
    $descarga=ModuloGetURL('app','Reportes_Consulta_Externa','user','DescargaDatosCitasCanceladas',array('centroutilidad'=>$_REQUEST['centroutilidad'],'unidadfunc'=>$_REQUEST['unidadfunc'],
    'departamento'=>$_REQUEST['departamento'],'profesional_escojer'=>$_REQUEST['profesional_escojer'],"feinictra"=>$_REQUEST['feinictra'],"fefinctra"=>$_REQUEST['fefinctra'],
    'centroU'=>$_REQUEST['centroU'],'unidadF'=>$_REQUEST['unidadF'],'DptoSel'=>$_REQUEST['DptoSel']));          
    $this->salida .= "      <a href=\"$descarga\">Descargar Datos <img title=\"Descargar Archivo\" border=\"0\" src=\"".GetThemePath()."/images/abajo.png\"></a>";
    $this->salida .="       </td></tr>";
    $this->salida .= "      </table><BR>";
    $this->salida .= "  </td></tr>";   
    
    $this->salida .= "  <tr><td align=\"center\">";          
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
		$this->salida .= "  </form>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "</table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
	
	function ReporteHCAbiertasCerradas($depto,$tipoconsul,$profesional,$feinictra,$fefinctra)
  { $archivoPlano='';
		$this->salida  = ThemeAbrirTabla('REPORTE ESTADISTICO DE HISTORIAS CLINICAS ABIERTAS Y CERRADAS');
		$this->Cabecera();
		$reporte= new GetReports();//FALSE
		$mostrar=$reporte->GetJavaReport('app','Reportes_Consulta_Externa','ReporteHCAbiertasCerradas',array('datos'=>$_SESSION['reconecc'],'variables'=>$_REQUEST),array('rpt_name'=>'ConsultaExternas','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
		$funcion=$reporte->GetJavaFunction();
		$this->salida .= "$mostrar";
		$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "<tr><td>";
		$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
          if(!empty($_REQUEST['centroutilidad']))
		{
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">CENTRO DE UTILIDAD";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "      ".$_REQUEST['centroutilidad']."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}

		if(!empty($_REQUEST['unidadfunc']))
		{
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">UNIDAD FUNCIONAL";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "      ".$_REQUEST['unidadfunc']."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}

		if(!empty($_REQUEST['departamento']))
		{
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DEPARTAMENTO";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "      ".$_REQUEST['departamento']."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}
		if($_SESSION['reconecc']['documentos']<>NULL){
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL PROFESIONAL";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "      ".$_SESSION['reconecc']['nombreprof']."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}		
		if($_SESSION['reconecc']['fechadesde']<>NULL){
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">FECHA INICIAL";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "      ".$_SESSION['reconecc']['fechadesde']."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}
		if($_SESSION['reconecc']['fechahasta']<>NULL){
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">FECHA FINAL";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "      ".$_SESSION['reconecc']['fechahasta']."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}
		if(!($_SESSION['reconecc']['codigodepa']<>NULL OR
		$_SESSION['reconecc']['codigotico']<>NULL OR
		$_SESSION['reconecc']['documentos']<>NULL OR
		$_SESSION['reconecc']['fechadesde']<>NULL OR
		$_SESSION['reconecc']['fechahasta']<>NULL 
		)){
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PARAMETROS DE BUSQUEDA:";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "NINGUNO";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}
		$this->salida .= "      </table><br>";
		$vectorDatos=$_SESSION['CITAS_CANCELADAS']['DATOS'];
		$j=1;
		if($vectorDatos){
		
			for($i=0;$i<sizeof($vectorDatos);$i++){				    
				$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
				$this->salida .= "      <tr class=\"modulo_table_title\">";
				$this->salida .= "      <td width=\"4%\">No.</td>";
        $archivoPlano.='No.'.'|';
				$this->salida .= "      <td width=\"7%\" >TIPO ID</td>";
        $archivoPlano.='TIPO ID'.'|';
				$this->salida .= "      <td width=\"10%\">IDENTIFICACIÓN</td>";
        $archivoPlano.='IDENTIFICACIÓN'.'|';
				$this->salida .= "      <td width=\"45%\">NOMBRE DEL PROFESIONAL</td>";
        $archivoPlano.='NOMBRE DEL PROFESIONAL'.'|';
				$this->salida .= "      <td width=\"27%\">ESPECIALIDAD</td>";
        $archivoPlano.='ESPECIALIDAD'.'|';
				$this->salida .= "      <td width=\"7%\" >ESTADO</td>";
        $archivoPlano.='ESTADO'."\n";
				$this->salida .= "      </tr>";
				$this->salida .= "      <tr class=hc_table_submodulo_list_title>";
				$this->salida .= "      <td align=\"center\">$j</td>";
        $archivoPlano.=$j.'|';
				(list($tipoId,$Identificacion)=explode('-',$vectorDatos[$i]['identificacionprof']));
				$this->salida .= "      <td align=\"center\">$tipoId</td>";
        $archivoPlano.=$tipoId.'|';
				$this->salida .= "      <td align=\"center\">$Identificacion</td>";
        $archivoPlano.=$Identificacion.'|';
				$this->salida .= "      <td align=\"center\">".$vectorDatos[$i]['nombre_tercero']."</td>";
        $archivoPlano.=$vectorDatos[$i]['nombre_tercero'].'|';
				$this->salida .= "      <td align=\"center\">".$vectorDatos[$i]['especialidad']."</td>";
        $archivoPlano.=$vectorDatos[$i]['especialidad'].'|';
				if($vectorDatos[$i]['estado']=='0'){
					$this->salida .= "    <td align=\"center\">INACTIVO</td>";
          $archivoPlano.='INACTIVO'."\n";
				}elseif($vectorDatos[$i]['estado']=='1'){
					$this->salida .= "    <td align=\"center\">ACTIVO</td>";
          $archivoPlano.='ACTIVO'."\n";
				}
        $this->salida .= "      </tr>";        				
				$this->salida .= "      </table>";				
				$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
				$this->salida .= "      <tr>";						
				$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"50%\">HISTORIAS CLINICAS ABIERTAS</td>";
        $archivoPlano.='HISTORIAS CLINICAS ABIERTAS'.'|';
				$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"50%\">HISTORIAS CLINICAS CERRADAS</td>";
        $archivoPlano.='HISTORIAS CLINICAS ABIERTAS'."\n";
				$this->salida .= "      </tr>";										
				$this->salida .= "      <tr class=modulo_list_claro>";								
				$this->salida .= "      <td align=\"center\" class=\"modulo_list_claro\"><b>".$vectorDatos[$i]['hc_abiertass']."</b></td>";
        $archivoPlano.=$vectorDatos[$i]['hc_abiertass'].'|';
				$this->salida .= "      <td align=\"center\" class=\"modulo_list_claro\"><b>".$vectorDatos[$i]['hc_cerradass']."</b></td>";
        $archivoPlano.=$vectorDatos[$i]['hc_cerradass']."\n";
				$this->salida .= "      </tr>";					
				$this->salida .= "      </table><br>";	
				$j++;		
			}
		}else{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= "      <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON DATOS CON ESTOS PARAMETROS</td></tr>";
			$this->salida .= "      </table><br>";
		}
		$this->salida .= "</td></tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td align=\"center\" colspan=\"6\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"button\" name=\"imprimir1\" value=\"IMPRIMIR REPORTE\" onclick=\"javascript:$funcion\">";
    $this->salida .="       </td></tr>";

    $this->salida .= "  <tr><td align=\"center\">";    
    $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";        
    $this->salida .= "      <tr><td class=\"label\" align=\"center\">";
    $_SESSION['DESCARGA_DATOS_REPORTES']['DATOS']=$archivoPlano;                    
    $descarga=ModuloGetURL('app','Reportes_Consulta_Externa','user','DescargaDatosHCAbiertasCerradas',array('centroutilidad'=>$_REQUEST['centroutilidad'],'unidadfunc'=>$_REQUEST['unidadfunc'],
    'departamento'=>$_REQUEST['departamento'],'profesional_escojer'=>$_REQUEST['profesional_escojer'],"feinictra"=>$_REQUEST['feinictra'],"fefinctra"=>$_REQUEST['fefinctra'],
    'centroU'=>$_REQUEST['centroU'],'unidadF'=>$_REQUEST['unidadF'],'DptoSel'=>$_REQUEST['DptoSel']));          
    $this->salida .= "      <a href=\"$descarga\">Descargar Datos <img title=\"Descargar Archivo\" border=\"0\" src=\"".GetThemePath()."/images/abajo.png\"></a>";
    $this->salida .="       </td></tr>";
    $this->salida .= "      </table><BR>";
    $this->salida .= "  </td></tr>";
    
		(list($anoH,$mesH,$diaH)=explode('-',$_SESSION['reconecc']['fechadesde']));
		(list($anoF,$mesF,$diaF)=explode('-',$_SESSION['reconecc']['fechahasta']));
		$accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaReporteHCAbiertasCerradas',array("depto"=>$_SESSION['reconecc']['codigodepa'],
		"justificacion"=>$_SESSION['reconecc']['justificacionId'].','.$_SESSION['reconecc']['justificacion'],
		"tipoconsul"=>$_SESSION['reconecc']['codigotico'],
		"profesional"=>$_SESSION['reconecc']['tipodocume'].','.$_SESSION['reconecc']['documentos'].','.$_SESSION['reconecc']['nombreprof'],
		"feinictra"=>$diaH.'/'.$mesH.'/'.$anoH,"fefinctra"=>$diaF.'/'.$mesF.'/'.$anoF,
          'centroutilidad'=>$_REQUEST['centroutilidad'],'unidadfunc'=>$_REQUEST['unidadfunc'],
          'departamento'=>$_REQUEST['departamento'],'centroU'=>$_REQUEST['centroU'],'unidadF'=>$_REQUEST['unidadF'],'DptoSel'=>$_REQUEST['DptoSel'],$_REQUEST['profesional']));
		$this->salida .= "  <tr><td align=\"center\">";
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
		$this->salida .= "  </form>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "</table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
	
	function ReporteCitasCanceladasConsolidado($depto,$tipoconsul,$profesional,$feinictra,$fefinctra){
	  $archivoPlano='';
		$this->salida  = ThemeAbrirTabla('REPORTE ESTADISTICO  DE MOTIVOS DE CANCELACION DE CITAS MÉDICAS CONSOLIDADO');
		$this->Cabecera();
		$reporte= new GetReports();//FALSE
		$mostrar=$reporte->GetJavaReport('app','Reportes_Consulta_Externa','ReporteCancelacionCitasCEConsolidado',array('datos'=>$_SESSION['reconecc'],'variables'=>$_REQUEST),array('rpt_name'=>'ConsultaExternas','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
		$funcion=$reporte->GetJavaFunction();
		$this->salida .= "$mostrar";
		$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "<tr><td>";
		$this->salida .= "<table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
    if(!empty($_REQUEST['centroutilidad']) && empty($_SESSION['recoex']['descentro']))
		{
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">CENTRO DE UTILIDAD";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "      ".$_REQUEST['centroutilidad']."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}

		if(!empty($_REQUEST['unidadfunc']) && empty($_SESSION['recoex']['desunidadfun']))
		{
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">UNIDAD FUNCIONAL";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "      ".$_REQUEST['unidadfunc']."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}

		if(!empty($_REQUEST['departamento']) )
		{
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DEPARTAMENTO";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "      ".$_REQUEST['departamento']."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}
		if($_SESSION['reconecc']['documentos']<>NULL){
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL PROFESIONAL";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "      ".$_SESSION['reconecc']['nombreprof']."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}
		if($_SESSION['reconecc']['justificacion']<>NULL){
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">JUSTIFICACION";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "      ".strtoupper($_SESSION['reconecc']['justificacion'])."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}		
    
    if($_SESSION['reconecc']['descripcion_plan'])
    {
			$this->salida .= "  <tr class=modulo_list_claro>\n";
			$this->salida .= "    <td class=\"modulo_table_list_title\">PLAN DE AFILIACIÓN</td>";
			$this->salida .= "    <td align=\"center\">\n";
			$this->salida .= "      ".strtoupper($_SESSION['reconecc']['descripcion_plan'])."";
			$this->salida .= "    </td>\n";
			$this->salida .= "  </tr>\n";
		}
    
		if($_SESSION['reconecc']['fechadesde']<>NULL){
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">FECHA INICIAL";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "      ".$_SESSION['reconecc']['fechadesde']."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}
		if($_SESSION['reconecc']['fechahasta']<>NULL){
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">FECHA FINAL";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "      ".$_SESSION['reconecc']['fechahasta']."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}
		if(!($_SESSION['reconecc']['codigodepa']<>NULL OR
		$_SESSION['reconecc']['codigotico']<>NULL OR
		$_SESSION['reconecc']['documentos']<>NULL OR
		$_SESSION['reconecc']['fechadesde']<>NULL OR
		$_SESSION['reconecc']['fechahasta']<>NULL OR
		$_SESSION['reconecc']['justificacion']<>NULL)){
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PARAMETROS DE BUSQUEDA:";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "NINGUNO";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}
		$this->salida .= "      </table><br>";
		$datos=$_SESSION['CITAS_CANCELADAS_CONSOLIDADO']['DATOS'];	
		$i=0;
		if($datos){				
			foreach($datos as $tipo_id_prof=>$vector){				
				foreach($vector as $id_prof=>$vector1){
					foreach($vector1 as $nom_prof=>$vector2){					
						foreach($vector2 as $espe_prof=>$vector3){												
							$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
							$this->salida .= "      <tr class=\"modulo_table_title\">";
							$this->salida .= "      <td width=\"4%\">No.</td>";
              $archivoPlano.='No.'.'|';
							$this->salida .= "      <td width=\"7%\" >TIPO ID</td>";
              $archivoPlano.='TIPO ID'.'|';
							$this->salida .= "      <td width=\"10%\">IDENTIFICACIÓN</td>";
              $archivoPlano.='IDENTIFICACIÓN'.'|';
							$this->salida .= "      <td width=\"45%\">NOMBRE DEL PROFESIONAL</td>";
              $archivoPlano.='NOMBRE DEL PROFESIONAL'.'|';
							$this->salida .= "      <td width=\"27%\">ESPECIALIDAD</td>";
              $archivoPlano.='ESPECIALIDAD'.'|';
							$this->salida .= "      <td width=\"7%\" >ESTADO</td>";
              $archivoPlano.='ESTADO'."\n";
							$this->salida .= "      </tr>";							
							$this->salida .= "      <tr class=hc_table_submodulo_list_title>";
							$this->salida .= "      <td align=\"center\">$i</td>";						
              $archivoPlano.=$i.'|';
							$this->salida .= "      <td align=\"center\">$tipo_id_prof</td>";
              $archivoPlano.=$tipo_id_prof.'|';
							$this->salida .= "      <td align=\"center\">$id_prof</td>";
              $archivoPlano.=$id_prof.'|';
							$this->salida .= "      <td align=\"center\">$nom_prof</td>";
              $archivoPlano.=$nom_prof.'|';
							$this->salida .= "      <td align=\"center\">$espe_prof</td>";
              $archivoPlano.=$espe_prof.'|';
							foreach($vector3 as $tipo_cancel=>$vector4){
								$estado_prof=$vector4['estado'];
								break;
							}
							if($estado_prof=='0'){
								$this->salida .= "    <td align=\"center\">INACTIVO</td>";
                $archivoPlano.='INACTIVO'."\n";
							}elseif($estado_prof=='1'){
								$this->salida .= "    <td align=\"center\">ACTIVO</td>";
                $archivoPlano.='ACTIVO'."\n";
							}
							$i++;
							$this->salida .= "      </tr>";						
							$this->salida .= "      </table>";
							$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
							$this->salida .= "      <tr>";						
							$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"60%\">JUSTIFICACION INASISTENCIA</td>";
              $archivoPlano.='JUSTIFICACION INASISTENCIA'.'|';
							$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"40%\">CANTIDAD</td>";
              $archivoPlano.='CANTIDAD'."\n";
							$this->salida .= "      </tr>";						
							//PRINT_r($vector3);
							//ECHO '<br>';
							foreach($vector3 as $tipo_cancel=>$vector4){
								$this->salida .= "      <tr class=modulo_list_claro>";								
								$this->salida .= "      <td class=\"modulo_list_claro\"><b>".$vector4['tipojustificacion']."</b></td>";
                $archivoPlano.=$vector4['tipojustificacion'].'|';
								$this->salida .= "      <td class=\"modulo_list_claro\"><b>".$vector4['cantidad']."</b></td>";
                $archivoPlano.=$vector4['cantidad']."\n";
								$this->salida .= "      </tr>";	
							}
							$this->salida .= "      </table><br>";														
						}	
					}
				}
			}	
		}else{		
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= "      <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON DATOS CON ESTOS PARAMETROS</td></tr>";
			$this->salida .= "      </table><br>";
		}
		$this->salida .= "			</td></tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"button\" name=\"imprimir1\" value=\"IMPRIMIR REPORTE\" onclick=\"javascript:$funcion\">";
    $this->salida .="       </td>";
		$this->salida .="       </tr>";	
    
		$this->salida .= "      <tr><td align=\"center\">";    
    $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";        
    $this->salida .= "      <tr><td class=\"label\" align=\"center\">";
    $_SESSION['DESCARGA_DATOS_REPORTES']['DATOS']=$archivoPlano;                    
    $descarga=ModuloGetURL('app','Reportes_Consulta_Externa','user','DescargaDatosCitasCanceladasConsolidado',array('centroutilidad'=>$_REQUEST['centroutilidad'],'unidadfunc'=>$_REQUEST['unidadfunc'],
    'departamento'=>$_REQUEST['departamento'],'profesional_escojer'=>$_REQUEST['profesional_escojer'],"feinictra"=>$_REQUEST['feinictra'],"fefinctra"=>$_REQUEST['fefinctra'],
    'centroU'=>$_REQUEST['centroU'],'unidadF'=>$_REQUEST['unidadF'],'DptoSel'=>$_REQUEST['DptoSel']));          
    $this->salida .= "      <a href=\"$descarga\">Descargar Datos <img title=\"Descargar Archivo\" border=\"0\" src=\"".GetThemePath()."/images/abajo.png\"></a>";
    $this->salida .="       </td></tr>";
    $this->salida .= "      </table><BR>";    
    $this->salida .= "      </td></tr>";
    
		$this->salida .= "  		<tr><td align=\"center\">";
		(list($anoH,$mesH,$diaH)=explode('-',$_SESSION['reconecc']['fechadesde']));
		(list($anoF,$mesF,$diaF)=explode('-',$_SESSION['reconecc']['fechahasta']));
		$accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaReporteCancelacionCitasConsolidado',array("depto"=>$_SESSION['reconecc']['codigodepa'],
		"justificacion"=>$_SESSION['reconecc']['justificacionId'].','.$_SESSION['reconecc']['justificacion'],
		"tipoconsul"=>$_SESSION['reconecc']['codigotico'],"plan_afiliacion"=>$_SESSION['reconecc']['plan_afiliacion'],
		"profesional"=>$_SESSION['reconecc']['tipodocume'].','.$_SESSION['reconecc']['documentos'].','.$_SESSION['reconecc']['nombreprof'],
		"feinictra"=>$diaH.'/'.$mesH.'/'.$anoH,"fefinctra"=>$diaF.'/'.$mesF.'/'.$anoF,
		'centroutilidad'=>$_REQUEST['centroutilidad'],'unidadfunc'=>$_REQUEST['unidadfunc'],
    "descripcion_plan"=>$_REQUEST['descripcion_plan'],
		'departamento'=>$_REQUEST['departamento'],'centroU'=>$_REQUEST['centroU'],'unidadF'=>$_REQUEST['unidadF'],'DptoSel'=>$_REQUEST['DptoSel'],$_REQUEST['profesional']));	
		$this->salida .= "  	<form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  	<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
		$this->salida .= "  	</form>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "	</table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
    /**
    *
    */
  	function ReporteCitasCanceladasConsolidadoEntidad($depto,$tipoconsul,$profesional,$feinictra,$fefinctra)
    {
  	  $archivoPlano='';
  		$this->salida  = ThemeAbrirTabla('REPORTE ESTADISTICO DE MOTIVOS DE CANCELACION DE CITAS MÉDICAS CONSOLIDADO EN LA ENTIDAD');
  		$this->Cabecera();
  		$reporte= new GetReports();//FALSE
  		$mostrar=$reporte->GetJavaReport('app','Reportes_Consulta_Externa','ReporteCancelacionCitasCEConsolidadoEntidad',array('datos'=>$_SESSION['reconecc'],'variables'=>$_REQUEST),array('rpt_name'=>'ConsultaExternas','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
  		$funcion=$reporte->GetJavaFunction();
  		$this->salida .= "$mostrar";
  		$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10\">";
  		$this->salida .= "<tr><td>";
  		$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
            
        
      if(!empty($_REQUEST['centroutilidad']) && empty($_SESSION['recoex']['descentro']))
  		{
  			$this->salida .= "      <tr class=modulo_list_claro>";
  			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">CENTRO DE UTILIDAD";
  			$this->salida .= "      </td>";
  			$this->salida .= "      <td align=\"center\" width=\"70%\">";
  			$this->salida .= "      ".$_REQUEST['centroutilidad']."";
  			$this->salida .= "      </td>";
  			$this->salida .= "      </tr>";
  		}

  		if(!empty($_REQUEST['unidadfunc']) && empty($_SESSION['recoex']['desunidadfun']))
  		{
  			$this->salida .= "      <tr class=modulo_list_claro>";
  			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">UNIDAD FUNCIONAL";
  			$this->salida .= "      </td>";
  			$this->salida .= "      <td align=\"center\" width=\"70%\">";
  			$this->salida .= "      ".$_REQUEST['unidadfunc']."";
  			$this->salida .= "      </td>";
  			$this->salida .= "      </tr>";
  		}

  		if(!empty($_REQUEST['departamento']))
  		{
  			$this->salida .= "      <tr class=modulo_list_claro>";
  			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DEPARTAMENTO";
  			$this->salida .= "      </td>";
  			$this->salida .= "      <td align=\"center\" width=\"70%\">";
  			$this->salida .= "      ".$_REQUEST['departamento']."";
  			$this->salida .= "      </td>";
  			$this->salida .= "      </tr>";
  		}
  		if($_SESSION['reconecc']['documentos']<>NULL){
  			$this->salida .= "      <tr class=modulo_list_claro>";
  			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL PROFESIONAL";
  			$this->salida .= "      </td>";
  			$this->salida .= "      <td align=\"center\" width=\"70%\">";
  			$this->salida .= "      ".$_SESSION['reconecc']['nombreprof']."";
  			$this->salida .= "      </td>";
  			$this->salida .= "      </tr>";
  		}
  		if($_SESSION['reconecc']['justificacion']<>NULL){
  			$this->salida .= "      <tr class=modulo_list_claro>";
  			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">JUSTIFICACION";
  			$this->salida .= "      </td>";
  			$this->salida .= "      <td align=\"center\" width=\"70%\">";
  			$this->salida .= "      ".strtoupper($_SESSION['reconecc']['justificacion'])."";
  			$this->salida .= "      </td>";
  			$this->salida .= "      </tr>";
  		}  		
      
      if($_SESSION['reconecc']['plan_afiliacion']<>NULL)
      {
  			$this->salida .= "  <tr class=modulo_list_claro>\n";
  			$this->salida .= "    <td class=\"modulo_table_list_title\" >PLAN DE AFILIACIÓN</td>";
  			$this->salida .= "    <td align=\"center\">";
  			$this->salida .= "      ".strtoupper($_SESSION['reconecc']['descripcion_plan'])."";
  			$this->salida .= "      </td>";
  			$this->salida .= "      </tr>";
  		}
  		
      if($_SESSION['reconecc']['fechadesde']<>NULL){
  			$this->salida .= "      <tr class=modulo_list_claro>";
  			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">FECHA INICIAL";
  			$this->salida .= "      </td>";
  			$this->salida .= "      <td align=\"center\" width=\"70%\">";
  			$this->salida .= "      ".$_SESSION['reconecc']['fechadesde']."";
  			$this->salida .= "      </td>";
  			$this->salida .= "      </tr>";
  		}
  		if($_SESSION['reconecc']['fechahasta']<>NULL){
  			$this->salida .= "      <tr class=modulo_list_claro>";
  			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">FECHA FINAL";
  			$this->salida .= "      </td>";
  			$this->salida .= "      <td align=\"center\" width=\"70%\">";
  			$this->salida .= "      ".$_SESSION['reconecc']['fechahasta']."";
  			$this->salida .= "      </td>";
  			$this->salida .= "      </tr>";
  		}
  		if(!($_SESSION['reconecc']['codigodepa']<>NULL OR
  		$_SESSION['reconecc']['codigotico']<>NULL OR
  		$_SESSION['reconecc']['documentos']<>NULL OR
  		$_SESSION['reconecc']['fechadesde']<>NULL OR
  		$_SESSION['reconecc']['fechahasta']<>NULL OR
  		$_SESSION['reconecc']['justificacion']<>NULL)){
  			$this->salida .= "      <tr class=modulo_list_claro>";
  			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PARAMETROS DE BUSQUEDA:";
  			$this->salida .= "      </td>";
  			$this->salida .= "      <td align=\"center\" width=\"70%\">";
  			$this->salida .= "NINGUNO";
  			$this->salida .= "      </td>";
  			$this->salida .= "      </tr>";
  		}
  		$this->salida .= "      </table><br>";
  		$vector3=$_SESSION['CITAS_CANCELADAS_CONSOLIDADO']['DATOS'];	
  		$i=0;
  		if($vector3)
      {							
  			$this->salida .= "      <table border=\"0\" width=\"60%\" align=\"center\">";
  			$this->salida .= "      <tr>";						
  			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"60%\">JUSTIFICACION INASISTENCIA</td>";
        $archivoPlano.='JUSTIFICACION INASISTENCIA'.'|';
  			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"40%\">CANTIDAD</td>";
        $archivoPlano.='CANTIDAD'."\n";
  			$this->salida .= "      </tr>";						
  			//PRINT_r($vector3);
  			//ECHO '<br>';
  			foreach($vector3 as $tipo_cancel=>$vector4){
  				$this->salida .= "      <tr class=modulo_list_claro>";								
  				$this->salida .= "      <td class=\"modulo_list_claro\"><b>".$vector4['tipojustificacion']."</b></td>";
          $archivoPlano.=$vector4['tipojustificacion'].'|';
  				$this->salida .= "      <td class=\"modulo_list_claro\"><b>".$vector4['cantidad']."</b></td>";
          $archivoPlano.=$vector4['cantidad']."\n";
  				$this->salida .= "      </tr>";	
  			}
  			$this->salida .= "      </table><br>";														
  		}else{		
  			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
  			$this->salida .= "      <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON DATOS CON ESTOS PARAMETROS</td></tr>";
  			$this->salida .= "      </table><br>";
  		}
  		$this->salida .= "			</td></tr>";
  		$this->salida .= "      <tr>";
  		$this->salida .= "      <td align=\"center\">";
  		$this->salida .= "      <input class=\"input-submit\" type=\"button\" name=\"imprimir1\" value=\"IMPRIMIR REPORTE\" onclick=\"javascript:$funcion\">";
      $this->salida .="       </td>";
  		$this->salida .="       </tr>";	
  		
      $this->salida .= "      <tr><td align=\"center\">";    
      $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";        
      $this->salida .= "      <tr><td class=\"label\" align=\"center\">";
      $_SESSION['DESCARGA_DATOS_REPORTES']['DATOS']=$archivoPlano;                    
      $descarga=ModuloGetURL('app','Reportes_Consulta_Externa','user','DescargaDatosCitasCanceladasConsolidadoEntidad',array('centroutilidad'=>$_REQUEST['centroutilidad'],'unidadfunc'=>$_REQUEST['unidadfunc'],
      'departamento'=>$_REQUEST['departamento'],'profesional_escojer'=>$_REQUEST['profesional_escojer'],"feinictra"=>$_REQUEST['feinictra'],"fefinctra"=>$_REQUEST['fefinctra'],
      'centroU'=>$_REQUEST['centroU'],'unidadF'=>$_REQUEST['unidadF'],'DptoSel'=>$_REQUEST['DptoSel']));          
      $this->salida .= "      <a href=\"$descarga\">Descargar Datos <img title=\"Descargar Archivo\" border=\"0\" src=\"".GetThemePath()."/images/abajo.png\"></a>";
      $this->salida .="       </td></tr>";
      $this->salida .= "      </table><BR>";    
      $this->salida .= "      </td></tr>";
      
  		$this->salida .= "  		<tr><td align=\"center\">";
  		(list($anoH,$mesH,$diaH)=explode('-',$_SESSION['reconecc']['fechadesde']));
  		(list($anoF,$mesF,$diaF)=explode('-',$_SESSION['reconecc']['fechahasta']));
  		$accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaReporteCancelacionCitasConsolidadoEntidad',array("depto"=>$_SESSION['reconecc']['codigodepa'],
  		"justificacion"=>$_SESSION['reconecc']['justificacionId'].','.$_SESSION['reconecc']['justificacion'],
  		"tipoconsul"=>$_SESSION['reconecc']['codigotico'],
  		"profesional"=>$_SESSION['reconecc']['tipodocume'].','.$_SESSION['reconecc']['documentos'].','.$_SESSION['reconecc']['nombreprof'],
  		"feinictra"=>$diaH.'/'.$mesH.'/'.$anoH,"fefinctra"=>$diaF.'/'.$mesF.'/'.$anoF,
  		'centroutilidad'=>$_REQUEST['centroutilidad'],'unidadfunc'=>$_REQUEST['unidadfunc'],
  		'departamento'=>$_REQUEST['departamento'],'centroU'=>$_REQUEST['centroU'],'unidadF'=>$_REQUEST['unidadF'],'DptoSel'=>$_REQUEST['DptoSel'],$_REQUEST['profesional'],
      'plan_afiliacion'=>$_SESSION['reconecc']['plan_afiliacion'],
      'descripcion_plan'=>$_SESSION['reconecc']['descripcion_plan']
      ));	
  		$this->salida .= "  	<form name=\"forma\" action=\"$accion\" method=\"post\">";
  		$this->salida .= "  	<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
  		$this->salida .= "  	</form>";
  		$this->salida .= "  </td></tr>";
  		$this->salida .= "	</table>";
  		$this->salida .= ThemeCerrarTabla();
  		return true;
  	}

     function ReporteCausasCitas($Tipo_sexo,$Rango_Edades)
     {    $archivoPlano='';
		$this->salida  = ThemeAbrirTabla('REPORTE ESTADISTICO DE CAUSAS DE CONSULTAS MÉDICAS');
          $this->salida .= "<table border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "<tr>";
		$this->salida .= "<td align=\"center\">";
          $this->Cabecera();		
          $this->salida .= "</td>";
          $this->salida .= "</tr>";         
		$this->salida .= "</table>";
          $this->salida .= "<br><table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_list_oscuro\">";
		$this->salida .= "<tr>";
		$this->salida .= "<td align=\"center\" class=\"modulo_table_list_title\" colspan=\"2\">DATOS DE LA BUSQUEDA</td>";
          $this->salida .= "</tr>";
          $this->salida .= "<tr>";
          $this->salida .= "<td class=\"modulo_table_title\" width=\"40%\" align=\"left\">CENTRO DE UTILIDAD</td>";
          if(!empty($_REQUEST['centroutilidad']))
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\">".$_REQUEST['centroutilidad']."</td>"; }
          else
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\"><label class=\"label_mark\">SIN DATOS</label></td>"; }
          $this->salida .= "</tr>";
          
          $this->salida .= "<tr>";
          $this->salida .= "<td class=\"modulo_table_title\" width=\"40%\" align=\"left\">UNIDAD FUNCIONAL</td>";
          if(!empty($_REQUEST['unidadfunc']))
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\">".$_REQUEST['unidadfunc']."</td>"; }
          else
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\"><label class=\"label_mark\">SIN DATOS</label></td>"; }
          $this->salida .= "</tr>";
          
          $this->salida .= "<tr>";
          $this->salida .= "<td class=\"modulo_table_title\" width=\"40%\" align=\"left\">DEPARTAMENTO</td>";
          if(!empty($_REQUEST['departamento']))
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\">".$_REQUEST['departamento']."</td>"; }
          else
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\"><label class=\"label_mark\">SIN DATOS</label></td>"; }
          $this->salida .= "</tr>";
               
          $usuario_id = explode(',',$_REQUEST['profesional_escojer']);

          $this->salida .= "<tr>";
          $this->salida .= "<td class=\"modulo_table_title\" width=\"40%\" align=\"left\">PROFESIONAL</td>";
          if(!empty($usuario_id[1]))
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\">".$usuario_id[1]."</td>"; }
          else
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\"><label class=\"label_mark\">SIN DATOS</label></td>"; }
          $this->salida .= "</tr>";
               
          $this->salida .= "<tr>";
          $this->salida .= "<td class=\"modulo_table_title\" width=\"40%\" align=\"left\">FECHA INICIAL</td>";
          if(!empty($_REQUEST['feinictra']))
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\">".$_REQUEST['feinictra']."</td>"; }
          else
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\"><label class=\"label_mark\">SIN DATOS</label></td>"; }
          $this->salida .= "</tr>";

          $this->salida .= "<tr>";
          $this->salida .= "<td class=\"modulo_table_title\" width=\"40%\" align=\"left\">FECHA FINAL</td>";
          if(!empty($_REQUEST['fefinctra']))
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\">".$_REQUEST['fefinctra']."</td>"; }
          else
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\"><label class=\"label_mark\">SIN DATOS</label></td>"; }
          $this->salida .= "</tr>";					
          $this->salida .= "</table><br>";
          
          $datos=$_SESSION['CAUSAS_CITAS']['DATOS'];          
					if($datos){
						$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
						$this->salida .= "      <tr class=\"modulo_table_list_title\">";
						$this->salida .= "      <td width=\"5%\">CODIGO</td>";
            $archivoPlano.='CODIGO'.'|';
						$this->salida .= "      <td>DESCRIPCION</td>";
            $archivoPlano.='DESCRIPCION'.'|';
						foreach($Tipo_sexo as $k => $v){	
							$this->salida .= "		<td width=\"5%\">".strtoupper($k)."</td>";
              $archivoPlano.=strtoupper($k).'|';
            }						
						$this->salida .= "      <td width=\"8%\">Menor de 1</td>";
            $archivoPlano.='Menor de 1'.'|';
						$this->salida .= "      <td width=\"8%\">Entre 1 y 5</td>";
            $archivoPlano.='Entre 1 y 5'.'|';
						$this->salida .= "      <td width=\"8%\">Entre 5 y 14</td>";
            $archivoPlano.='Entre 5 y 14'.'|';
						$this->salida .= "      <td width=\"8%\">Entre 15 y 44</td>";
            $archivoPlano.='Entre 15 y 44'.'|';
						$this->salida .= "      <td width=\"8%\">Entre 45 y 69</td>";
            $archivoPlano.='Entre 45 y 69'.'|';
						$this->salida .= "      <td width=\"8%\">Mayor de 70</td>";
            $archivoPlano.='Mayor de 70'.'|';
						$this->salida .= "      <td width=\"5%\">TOTAL</td>";
            $archivoPlano.='TOTAL'."\n";            
						$this->salida .= "</tr>";
						$Sumamenor1='0';$Sumaentre1y5='0';$Sumaentre5y14='0';$Sumaentre15y44='0';$Sumaentre45y69='0';$Sumamayor70='0';$totFil='0';																					
						foreach($datos as $k1 => $V5 ){
							
							
							for($i=0; $i<sizeof($V5); $i++){  
								
								if ($V5[$i]['diagnostico_id'] != $V5[$i-1]['diagnostico_id']){	
									$a = $i;
									$estilo='hc_table_submodulo_list_title';								
									$estilo1='modulo_list_claro';								
									$this->salida .= "<tr>";
									$this->salida .= "<td align=\"left\" class=\"$estilo\">".$V5[$i]['diagnostico_id']."</td>";
                  $archivoPlano.=$V5[$i]['diagnostico_id'].'|';
									$this->salida .= "<td align=\"left\" class=\"$estilo\"><b>".$V5[$i]['diagnostico_nombre']."</b></td>";
                  $archivoPlano.=$V5[$i]['diagnostico_nombre'].'|';
									foreach($Tipo_sexo as $k => $v){									
										
										if($k != $V5[$a]['sexo']){
											//$a = $a-1;
											$this->salida .= "<td align=\"center\" class=\"$estilo\"><font color=\"green\">0</font></td>";
                      $archivoPlano.='0'.'|';
										}elseif($k == $V5[$a]['sexo']){
											$this->salida .= "<td align=\"center\" class=\"$estilo\"><b><font color=\"green\">".$V5[$a]['cantidad']."</font></b></td>"; 
                      $archivoPlano.=$V5[$a]['cantidad'].'|';
											$a ++;
										}
										
									}
									$menor1 =0;$entre1y5 =0; $entre5y14 =0; $entre15y44 =0; $entre45y69 =0; $mayor70 =0;                   								
									for($z=0; $z<sizeof($Rango_Edades); $z++){
										if($Rango_Edades[$z][tipo_diagnostico_id] == $V5[$i][diagnostico_id]){											
											if($Rango_Edades[$z][cantidad_menor_1]){
											if(empty($menor1)){
												$menor1 = $Rango_Edades[$z][cantidad_menor_1]; 
												$Sumamenor1+=$menor1;
											}
											}
											if($Rango_Edades[$z][cantidad_entre_1_5]){											
											if(empty($entre1y5)){
												$entre1y5 = $Rango_Edades[$z][cantidad_entre_1_5]; 
												$Sumaentre1y5+=$entre1y5;
											}
											}
											if($Rango_Edades[$z][cantidad_entre_5_14]){
											if(empty($entre5y14)){
												$entre5y14 = $Rango_Edades[$z][cantidad_entre_5_14]; 
												$Sumaentre5y14+=$entre5y14;
											}
											}
											if($Rango_Edades[$z][cantidad_entre_15_44]){
											if(empty($entre15y44)){
												$entre15y44 = $Rango_Edades[$z][cantidad_entre_15_44]; 
												$Sumaentre15y44+=$entre15y44;												
											}
											}
											if($Rango_Edades[$z][cantidad_entre_45_69]){
											if(empty($entre45y69)){
												$entre45y69 = $Rango_Edades[$z][cantidad_entre_45_69]; 
												$Sumaentre45y69+=$entre45y69;
											}
											}
											if($Rango_Edades[$z][cantidad_mayor_70]){
											if(empty($mayor70)){
												$mayor70 = $Rango_Edades[$z][cantidad_mayor_70]; 
												$Sumamayor70+=$mayor70;
											}
											}
										}
									}
									$this->salida .= "<td class=\"$estilo1\">".$menor1."</td>";																
                  $archivoPlano.=$menor1.'|';
									$this->salida .= "<td class=\"$estilo1\">".$entre1y5."</td>";                
                  $archivoPlano.=$entre1y5.'|';
									$this->salida .= "<td class=\"$estilo1\">".$entre5y14."</td>";								
                  $archivoPlano.=$entre5y14.'|';
									$this->salida .= "<td class=\"$estilo1\">".$entre15y44."</td>";								
                  $archivoPlano.=$entre15y44.'|';
									$this->salida .= "<td class=\"$estilo1\">".$entre45y69."</td>";               								
                  $archivoPlano.=$entre45y69.'|';
									$this->salida .= "<td class=\"$estilo1\">".$mayor70."</td>"; 									
                  $archivoPlano.=$mayor70.'|';
                  $totFil1=($menor1+$entre1y5+$entre5y14+$entre15y44+$entre45y69+$mayor70);
									$this->salida .= "<td class=\"$estilo1\"><label class=\"label\">".$totFil1."</label></td>"; 									
                  $archivoPlano.=$totFil1."\n";                  
									$this->salida .= "</tr>";										
									$totFil+=$totFil1;
								}																	
							}	
						}
						$this->salida .= "     <tr>";	
						$this->salida .= "      <td class=\"hc_table_submodulo_list_title\" align=\"right\" colspan=\"4\">TOTALES</td>";
            $archivoPlano.='|'.'|'.'|'.'TOTALES'.'|';
						$this->salida .= "      <td class=\"$estilo1\"><label class=\"label\">".$Sumamenor1."</label></td>";                
            $archivoPlano.=$Sumamenor1.'|';
						$this->salida .= "      <td class=\"$estilo1\" class=\"label\"><label class=\"label\">".$Sumaentre1y5."</label></td>";								
            $archivoPlano.=$Sumaentre1y5.'|';
						$this->salida .= "      <td class=\"$estilo1\" class=\"label\"><label class=\"label\">".$Sumaentre5y14."</label></td>";								
            $archivoPlano.=$Sumaentre5y14.'|';
						$this->salida .= "      <td class=\"$estilo1\" class=\"label\"><label class=\"label\">".$Sumaentre15y44."</label></td>"; 
            $archivoPlano.=$Sumaentre15y44.'|';
						$this->salida .= "      <td class=\"$estilo1\" class=\"label\"><label class=\"label\">".$Sumaentre45y69."</label></td>";               								
            $archivoPlano.=$Sumaentre45y69.'|';
						$this->salida .= "      <td class=\"$estilo1\" class=\"label\"><label class=\"label\">".$Sumamayor70."</label></td>"; 
            $archivoPlano.=$Sumamayor70.'|';
						$this->salida .= "      <td class=\"$estilo1\" class=\"label\"><label class=\"label\">".$totFil."</label></td>";               																										
            $archivoPlano.=$totFil."\n";
						$this->salida .= "		</tr>";		
						$reporte= new GetReports();
						$mostrar=$reporte->GetJavaReport('app','Reportes_Consulta_Externa','ReporteCausasConsultasCitas',array('variables'=>$_REQUEST),array('rpt_name'=>'ConsultaExternas','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));		
						$nombre_funcion=$reporte->GetJavaFunction();
						$this->salida .=$mostrar;
						echo $mostrar;
						
						$this->salida .= "      <tr><td class=\"label\" align=\"center\" colspan=\"11\">";
						$this->salida .= "      <a href=\"javascript:$nombre_funcion\"><b>Imprimir Reporte</b></a>";
						$this->salida .="       </td></tr>";
						$this->salida .= "			</table><BR>";	
            
            
            $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";				
            $this->salida .= "      <tr><td class=\"label\" align=\"center\">";
            $_SESSION['DESCARGA_DATOS_REPORTES']['DATOS']=$archivoPlano;            
            (list($ano,$mes,$dia)=explode('-',$_SESSION['reconeccc']['fechadesde']));
            $feinictra=$dia.'/'.$mes.'/'.$ano;
            (list($ano,$mes,$dia)=explode('-',$_SESSION['reconeccc']['fechahasta']));
            $fechahasta=$dia.'/'.$mes.'/'.$ano;
            $descarga=ModuloGetURL('app','Reportes_Consulta_Externa','user','DescargaDatosCausasCitasMedicas',array('centroutilidad'=>$_REQUEST['centroutilidad'],'unidadfunc'=>$_REQUEST['unidadfunc'],
            'departamento'=>$_REQUEST['departamento'],'profesional_escojer'=>$_REQUEST['profesional_escojer'],"feinictra"=>$feinictra,"fefinctra"=>$fechahasta,
            'centroU'=>$_REQUEST['centroU'],'unidadF'=>$_REQUEST['unidadF'],'DptoSel'=>$_REQUEST['DptoSel']));          
            $this->salida .= "      <a href=\"$descarga\">Descargar Datos <img title=\"Descargar Archivo\" border=\"0\" src=\"".GetThemePath()."/images/abajo.png\"></a>";
            $this->salida .="       </td></tr>";
            $this->salida .= "      </table><BR>";  
            
					}
					
          /*if($datos)
          {
               $this->salida .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_list_oscuro\">";
               $this->salida .= "      <tr class=\"modulo_table_title\">";
               $this->salida .= "      <td width=\"15%\">CODIGO</td>";
               $this->salida .= "      <td width=\"30%\">DESCRIPCION</td>";
								$XX = 0;
               foreach($Tipo_sexo as $k => $v)
               {	
               	$XX++;
                    $this->salida .= "<td width=\"10%\">".strtoupper($k)."</td>";
               }
               $XX = $XX + 2;
               $this->salida .= "      </tr>";
							 	
               foreach($datos as $k1 => $V5 )
               {		
										for($i=0; $i<sizeof($V5); $i++)
                    {  
                         if( $i % 2){ $estilo='modulo_list_oscuro';}
                         else {$estilo='modulo_list_claro';}
                         
                         if ($V5[$i]['diagnostico_id'] != $V5[$i-1]['diagnostico_id'])
                         {	
                         	$a = $i;
                              $this->salida .= "<tr>";
                              $this->salida .= "<td align=\"center\" width=\"8%\" class=\"$estilo\">".$V5[$i]['diagnostico_id']."</td>";
                              $this->salida .= "<td align=\"justify\" width=\"50%\" class=\"$estilo\"><b>".$V5[$i]['diagnostico_nombre']."</b></td>";
                              foreach($Tipo_sexo as $k => $v)
                              {
                              	if($k != $V5[$a]['sexo'])
                                   {
                                   	$a = $a-1;
                                        $this->salida .= "<td align=\"center\" width=\"11%\" class=\"$estilo\"><font color=\"green\">0</font></td>";
                                   }
                                   elseif($k == $V5[$a]['sexo'])
                                   { $this->salida .= "<td align=\"center\" width=\"11%\" class=\"$estilo\"><b><font color=\"green\">".$V5[$a]['cantidad']."</font></b></td>"; }
                                   $a ++;
                              }
                              $this->salida .= "      </tr>";
                              
                              $this->salida .= "<tr class=\"$estilo\">";
                              $this->salida .= "<td colspan=\"$XX\">";
               
                              $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
                              $this->salida .= "      <tr>";
                              $this->salida .= "      <td width=\"100%\">";
                              $this->salida .= "<table border=\"1\" width=\"100%\">";
                              
                              for($z=0; $z<sizeof($Rango_Edades); $z++)
                              {
                              	if($Rango_Edades[$z][tipo_diagnostico_id] == $V5[$i][diagnostico_id])
                                   {
                                   	if(empty($menor1))
                                        { $menor1 = $Rango_Edades[$z][cantidad_menor_1]; }
                                   	if(empty($entre1y5))
                                        { $entre1y5 = $Rango_Edades[$z][cantidad_entre_1_5]; }
                                        if(empty($entre5y14))
                                        { $entre5y14 = $Rango_Edades[$z][cantidad_entre_5_14]; }
                                        if(empty($entre15y44))
                                        { $entre15y44 = $Rango_Edades[$z][cantidad_entre_15_44]; }
                                        if(empty($entre45y69))
                                        { $entre45y69 = $Rango_Edades[$z][cantidad_entre_45_69]; }
                                        if(empty($mayor70))
                                        { $mayor70 = $Rango_Edades[$z][cantidad_mayor_70]; }
                                   }
                              }
                              $this->salida .= "<tr class=\"$estilo\">";
                              $this->salida .= "<td width=\"10%\" align=\"center\">".$V5[$i]['diagnostico_id']."</td>";               
                              $this->salida .= "<td align=\"justify\" width=\"50%\" class=\"$estilo\"><b>".$V5[$i]['diagnostico_nombre']."</b></td>";
                              $this->salida .= "<td width=\"40%\"><table align=\"center\" width=\"100%\">";
                              $this->salida .= "      <tr>";                              
                              $this->salida .= "      <td align=\"center\" class=\"hc_table_submodulo_list_title\" width=\"100%\">DISTRIBUCION POR EDADES</td>";
                              $this->salida .= "      </tr>";
                              $this->salida .= "      <tr class=\"$estilo\">";
                              $this->salida .= "      <td width=\"10%\">Menor de 1: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><font color=\"#990000\">".$menor1."</font></b></td>";
                              $this->salida .= "      </tr>";
                              $this->salida .= "      <tr class=\"$estilo\">";                              
                              $this->salida .= "      <td width=\"10%\">Entre 1 y 5: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><font color=\"#990000\">".$entre1y5."</font></b></td>";
                         	$this->salida .= "      </tr>";
                              $this->salida .= "      <tr class=\"$estilo\">";                                                            
                              $this->salida .= "      <td width=\"10%\">Entre 5 y 14: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><font color=\"#990000\">".$entre5y14."</font></b></td>";
                              $this->salida .= "      </tr>";
                              $this->salida .= "      <tr class=\"$estilo\">";                                                                                          
                              $this->salida .= "      <td width=\"10%\">Entre 15 y 44: &nbsp;&nbsp;&nbsp;<b><font color=\"#990000\">".$entre15y44."</font></b></td>";
                              $this->salida .= "      </tr>";                              
                              $this->salida .= "      <tr class=\"$estilo\">";                                                                                                                        
                              $this->salida .= "      <td width=\"10%\">Entre 45 y 69: &nbsp;&nbsp;&nbsp;<b><font color=\"#990000\">".$entre45y69."</font></b></td>";               
                              $this->salida .= "      </tr>";
                              $this->salida .= "      <tr class=\"$estilo\">";                                                                                                                                                      
                              $this->salida .= "      <td width=\"10%\">Mayor de 70: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><font color=\"#990000\">".$mayor70."</font></b></td>";               
                              $this->salida .= "      </tr>";                         
                              $this->salida .= "</table></td>";               
                              $this->salida .= "</tr>";
           				$menor1 ="";$entre1y5 =""; $entre5y14 =""; $entre15y44 =""; $entre45y69 =""; $mayor70 ="";                   
                              $this->salida .= "</table>";               
                              $this->salida .= "</td>";
                              $this->salida .= "</tr>";
                              $this->salida .= "</table>";               
                              $this->salida .= "</td>";
                              $this->salida .= "</tr>";
                         }
     
                    }
               }

               $reporte= new GetReports();
               $mostrar=$reporte->GetJavaReport('app','Reportes_Consulta_Externa','ReporteCausasConsultasCitas',array('variables'=>$_REQUEST),array('rpt_name'=>'ConsultaExternas','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));		
               $nombre_funcion=$reporte->GetJavaFunction();
               $this->salida .=$mostrar;
               echo $mostrar;
               
               $this->salida .= "      <tr><td align=\"center\" colspan=\"$XX\">";
               $this->salida .= "      <a href=\"javascript:$nombre_funcion\"><b>Imprimir Reporte</b></a>";
               $this->salida .="       </td></tr>";
               $this->salida .= "      </table><br>";
     	}*/
			
			else
     	{
               $this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
               $this->salida .= "  <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON REGISTROS </td></tr>";
               $this->salida .= "  </table><br>";
     	}
          
          $this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
          $this->salida .= "  <tr><td align=\"center\">";
          (list($ano,$mes,$dia)=explode('-',$_SESSION['reconeccc']['fechadesde']));
          $feinictra=$dia.'/'.$mes.'/'.$ano;
          (list($ano,$mes,$dia)=explode('-',$_SESSION['reconeccc']['fechahasta']));
          $fechahasta=$dia.'/'.$mes.'/'.$ano;
          $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaReporteCausasCitasMedicas',array('centroutilidad'=>$_REQUEST['centroutilidad'],'unidadfunc'=>$_REQUEST['unidadfunc'],
          'departamento'=>$_REQUEST['departamento'],'profesional_escojer'=>$_REQUEST['profesional_escojer'],"feinictra"=>$feinictra,"fefinctra"=>$fechahasta,
          'centroU'=>$_REQUEST['centroU'],'unidadF'=>$_REQUEST['unidadF'],'DptoSel'=>$_REQUEST['DptoSel']));
          $this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
          $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
          $this->salida .= "  </form>";
          $this->salida .= "  </td></tr>";
          $this->salida .= "  </table>";
          $this->salida .= ThemeCerrarTabla();
          return true;
     }

     function ReporteCaracteristicaPacientes($Rango_Edades)
     {$archivoPlano='';
		$this->salida  = ThemeAbrirTabla('REPORTE ESTADISTICO DE CARACTERISTICAS DE PACIENTES');
          
          $this->salida .= "<table border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "<tr>";
		$this->salida .= "<td align=\"center\">";
          $this->Cabecera();		
          $this->salida .= "</td>";
          $this->salida .= "</tr>";         
		$this->salida .= "</table>";
          $this->salida .= "<br><table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_list_oscuro\">";
		$this->salida .= "<tr>";
		$this->salida .= "<td align=\"center\" class=\"modulo_table_list_title\" colspan=\"2\">DATOS DE LA BUSQUEDA</td>";
          $this->salida .= "</tr>";
          $this->salida .= "<tr>";
          $this->salida .= "<td class=\"modulo_table_title\" width=\"40%\" align=\"left\">CENTRO DE UTILIDAD</td>";
          if(!empty($_REQUEST['centroutilidad']))
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\">".$_REQUEST['centroutilidad']."</td>"; }
          else
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\"><label class=\"label_mark\">SIN DATOS</label></td>"; }
          $this->salida .= "</tr>";
          
          $this->salida .= "<tr>";
          $this->salida .= "<td class=\"modulo_table_title\" width=\"40%\" align=\"left\">UNIDAD FUNCIONAL</td>";
          if(!empty($_REQUEST['unidadfunc']))
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\">".$_REQUEST['unidadfunc']."</td>"; }
          else
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\"><label class=\"label_mark\">SIN DATOS</label></td>"; }
          $this->salida .= "</tr>";
          
          $this->salida .= "<tr>";
          $this->salida .= "<td class=\"modulo_table_title\" width=\"40%\" align=\"left\">DEPARTAMENTO</td>";
          if(!empty($_REQUEST['departamento']))
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\">".$_REQUEST['departamento']."</td>"; }
          else
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\"><label class=\"label_mark\">SIN DATOS</label></td>"; }
          $this->salida .= "</tr>";
               
          $usuario_id = explode(',',$_REQUEST['profesional_escojer']);

          $this->salida .= "<tr>";
          $this->salida .= "<td class=\"modulo_table_title\" width=\"40%\" align=\"left\">PROFESIONAL</td>";
          if(!empty($usuario_id[1]))
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\">".$usuario_id[1]."</td>"; }
          else
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\"><label class=\"label_mark\">SIN DATOS</label></td>"; }
          $this->salida .= "</tr>";
               
          $this->salida .= "<tr>";
          $this->salida .= "<td class=\"modulo_table_title\" width=\"40%\" align=\"left\">FECHA INICIAL</td>";
          if(!empty($_REQUEST['feinictra']))
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\">".$_REQUEST['feinictra']."</td>"; }
          else
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\"><label class=\"label_mark\">SIN DATOS</label></td>"; }
          $this->salida .= "</tr>";

          $this->salida .= "<tr>";
          $this->salida .= "<td class=\"modulo_table_title\" width=\"40%\" align=\"left\">FECHA FINAL</td>";
          if(!empty($_REQUEST['fefinctra']))
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\">".$_REQUEST['fefinctra']."</td>"; }
          else
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\"><label class=\"label_mark\">SIN DATOS</label></td>"; }
          $this->salida .= "</tr>";

          $this->salida .= "</table><br>";

          $this->salida .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_list_oscuro\">";
          $this->salida .= "      <tr class=\"modulo_table_title\">";
          $this->salida .= "      <td width=\"35%\">EDAD PACIENTE</td>";
          $archivoPlano.='EDAD PACIENTE'.'|';
          $this->salida .= "      <td width=\"15%\">FEMENINO</td>";
          $archivoPlano.='FEMENINO'.'|';
          $this->salida .= "      <td width=\"15%\">MASCULINO</td>";
          $archivoPlano.='MASCULINO'.'|';
          $this->salida .= "      <td width=\"15%\">CITAS POR RANGO</td>";
          $archivoPlano.='CITAS POR RANGO'."\n";
          $this->salida .= "      </tr>";

          $total_f = '0';
          $total_m = '0';
          $total_ne = '0';

          for($i=0; $i<sizeof($Rango_Edades); $i++)
          {                          
     		$estilo='modulo_list_claro';
          	
               $femenino = '0';
               $masculino = '0';
               $NE = '0';
                
               if($Rango_Edades[$i][tipo] != $Rango_Edades[$i-1][tipo]) 
               {
                    for($J=0; $J<sizeof($Rango_Edades); $J++)
                    {
                         if($Rango_Edades[$J][tipo] == $Rango_Edades[$i][tipo]) 
                         {
                              if($Rango_Edades[$J][sexo_id] == "F")
                              { $femenino = $Rango_Edades[$J][total_citas_edad];
                                $total_f = $total_f + $femenino; }
                                
                              if($Rango_Edades[$J][sexo_id] == "M")
                              { $masculino = $Rango_Edades[$J][total_citas_edad]; 
                                $total_m = $total_m + $masculino; }
                              
                              if($Rango_Edades[$J][sexo_id] == "0")
                              { $NE = $Rango_Edades[$J][total_citas_edad]; 
                                $total_ne = $total_ne + $NE; }
                         } 
                    }
                         
                    $this->salida .= "      <tr class=\"$estilo\">";
                    $this->salida .= "      <td align=\"justify\"><b>".$Rango_Edades[$i][tipo]."</b></td>";
                    $archivoPlano.=$Rango_Edades[$i][tipo].'|';
                    $this->salida .= "      <td align=\"center\">$femenino</td>";
                    $archivoPlano.=$femenino.'|';
                    $this->salida .= "      <td align=\"center\">$masculino</td>";
                    $archivoPlano.=$masculino.'|';
                    $x_rango = $femenino + $masculino;
                    $this->salida .= "      <td align=\"center\" class=\"label\">".$x_rango."</td>";                              
                    $archivoPlano.=$x_rango."\n";
                    $this->salida .= "      </tr>";
               }
          }          
          $this->salida .= "      <tr class=\"$estilo\">";
          $this->salida .= "      <td align=\"center\" class=\"modulo_table_list_title\">CITAS POR SEXO: </td>";
          $archivoPlano.='CITAS POR SEXO:'.'|';
          $this->salida .= "      <td align=\"center\" class=\"label\">$total_f</td>";
          $archivoPlano.=$total_f.'|';
          $this->salida .= "      <td align=\"center\" class=\"label\">$total_m</td>";
          $archivoPlano.=$total_m."\n";
          $this->salida .= "      <td align=\"center\" class=\"label\">&nbsp;</td>";
          $this->salida .= "      </tr>";
    
          $this->salida .= "      <tr class=\"$estilo\">";
          $this->salida .= "      <td align=\"center\" class=\"modulo_table_list_title\">TOTAL DE CITAS: </td>";
          $archivoPlano.='TOTAL DE CITAS:'.'|';
          $total_citas = ($total_f + $total_m + $total_ne);
          $this->salida .= "      <td align=\"center\" colspan=\"3\" class=\"label\">$total_citas</td>";
          $archivoPlano.='|'.'|'.$total_citas."\n";
          $this->salida .= "      </tr>";

          $reporte= new GetReports();
		      $mostrar=$reporte->GetJavaReport('app','Reportes_Consulta_Externa','ReporteCaracteristicasPaciente',array('Rango_Edades'=>$Rango_Edades,'variables'=>$_REQUEST),array('rpt_name'=>'ConsultaExternas','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));		
		      $nombre_funcion=$reporte->GetJavaFunction();
		      $this->salida .=$mostrar;
		      echo $mostrar;          
          $this->salida .= "      <tr><td align=\"center\" colspan=\"4\"><a href=\"javascript:$nombre_funcion\"><b>Imprimir Reporte</b></a>";
          $this->salida .="       </td></tr>";
          $this->salida .= "      </table><br>";
          
          
          $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";        
          $this->salida .= "      <tr><td class=\"label\" align=\"center\">";
          $_SESSION['DESCARGA_DATOS_REPORTES']['DATOS']=$archivoPlano;                    
          $descarga=ModuloGetURL('app','Reportes_Consulta_Externa','user','DescargaDatosCaracteristicasPacientes',array('centroutilidad'=>$_REQUEST['centroutilidad'],'unidadfunc'=>$_REQUEST['unidadfunc'],
          'departamento'=>$_REQUEST['departamento'],'profesional_escojer'=>$_REQUEST['profesional_escojer'],"feinictra"=>$_REQUEST['feinictra'],"fefinctra"=>$_REQUEST['fefinctra'],
          'centroU'=>$_REQUEST['centroU'],'unidadF'=>$_REQUEST['unidadF'],'DptoSel'=>$_REQUEST['DptoSel']));          
          $this->salida .= "      <a href=\"$descarga\">Descargar Datos <img title=\"Descargar Archivo\" border=\"0\" src=\"".GetThemePath()."/images/abajo.png\"></a>";
          $this->salida .="       </td></tr>";
          $this->salida .= "      </table><BR>";  
          
          $this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
          $this->salida .= "  <tr><td align=\"center\">";
          $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaReporteEstadisticoCaracteristicasPacientes',array('centroutilidad'=>$_REQUEST['centroutilidad'],'unidadfunc'=>$_REQUEST['unidadfunc'],
          'departamento'=>$_REQUEST['departamento'],'profesional_escojer'=>$_REQUEST['profesional_escojer'],
          'centroU'=>$_REQUEST['centroU'],'unidadF'=>$_REQUEST['unidadF'],'DptoSel'=>$_REQUEST['DptoSel'],'feinictra'=>$_REQUEST['feinictra'],'fefinctra'=>$_REQUEST['fefinctra']));
          $this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
          $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
          $this->salida .= "  </form>";
          $this->salida .= "  </td></tr>";
          $this->salida .= "  </table>";
          $this->salida .= ThemeCerrarTabla();
          return true;
     }

     
     function ReporteCitasTratamientoOdontologico($TI,$TT)
     {$archivoPlano='';
					$this->salida  = ThemeAbrirTabla('REPORTE ESTADISTICO CITAS DE CITAS DE TRATAMIENTO ODONTOLOGICO');
          
          $this->salida .= "<table border=\"0\" width=\"90%\" align=\"center\">";
					$this->salida .= "<tr>";
					$this->salida .= "<td align=\"center\">";
          $this->Cabecera();		
          $this->salida .= "</td>";
          $this->salida .= "</tr>";         
					$this->salida .= "</table>";
          $this->salida .= "<br><table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_list_oscuro\">";
					$this->salida .= "<tr>";
					$this->salida .= "<td align=\"center\" class=\"modulo_table_list_title\" colspan=\"2\">DATOS DE LA BUSQUEDA</td>";
          $this->salida .= "</tr>";
          $this->salida .= "<tr>";
          $this->salida .= "<td class=\"modulo_table_title\" width=\"40%\" align=\"left\">CENTRO DE UTILIDAD</td>";
          if(!empty($_REQUEST['centroutilidad']))
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\">".$_REQUEST['centroutilidad']."</td>"; }
          else
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\"><label class=\"label_mark\">SIN DATOS</label></td>"; }
          $this->salida .= "</tr>";
          
          $this->salida .= "<tr>";
          $this->salida .= "<td class=\"modulo_table_title\" width=\"40%\" align=\"left\">UNIDAD FUNCIONAL</td>";
          if(!empty($_REQUEST['unidadfunc']))
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\">".$_REQUEST['unidadfunc']."</td>"; }
          else
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\"><label class=\"label_mark\">SIN DATOS</label></td>"; }
          $this->salida .= "</tr>";
          
          $this->salida .= "<tr>";
          $this->salida .= "<td class=\"modulo_table_title\" width=\"40%\" align=\"left\">DEPARTAMENTO</td>";
          if(!empty($_REQUEST['departamento']))
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\">".$_REQUEST['departamento']."</td>"; }
          else
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\"><label class=\"label_mark\">SIN DATOS</label></td>"; }
          $this->salida .= "</tr>";
               
          $usuario_id = explode(',',$_REQUEST['profesional_escojer']);

          $this->salida .= "<tr>";
          $this->salida .= "<td class=\"modulo_table_title\" width=\"40%\" align=\"left\">PROFESIONAL</td>";
          if(!empty($usuario_id[1]))
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\">".$usuario_id[1]."</td>"; }
          else
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\"><label class=\"label_mark\">SIN DATOS</label></td>"; }
          $this->salida .= "</tr>";
               
          $this->salida .= "<tr>";
          $this->salida .= "<td class=\"modulo_table_title\" width=\"40%\" align=\"left\">FECHA INICIAL</td>";
          if(!empty($_REQUEST['feinictra']))
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\">".$_REQUEST['feinictra']."</td>"; }
          else
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\"><label class=\"label_mark\">SIN DATOS</label></td>"; }
          $this->salida .= "</tr>";

          $this->salida .= "<tr>";
          $this->salida .= "<td class=\"modulo_table_title\" width=\"40%\" align=\"left\">FECHA FINAL</td>";
          if(!empty($_REQUEST['fefinctra']))
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\">".$_REQUEST['fefinctra']."</td>"; }
          else
          { $this->salida .= "<td align=\"justify\" width=\"60%\" class=\"modulo_list_claro\"><label class=\"label_mark\">SIN DATOS</label></td>"; }
          $this->salida .= "</tr>";

          $this->salida .= "</table><br>";

          $this->salida .= "      <table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_list_oscuro\">";
          $this->salida .= "      <tr class=\"modulo_table_list_title\">";
          $this->salida .= "      <td width=\"70%\" colspan=\"2\">TRATAMIENTOS ODONTOLOGICOS</td>";
          $archivoPlano.='TRATAMIENTOS ODONTOLOGICOS'."\n";
          $this->salida .= "      </tr>";
          
          $this->salida .= "      <tr class=\"modulo_table_title\">";
          $this->salida .= "      <td width=\"35%\">TRATAMIENTOS ODONTOLOGICOS INICIADOS</td>";
          $archivoPlano.='TRATAMIENTOS ODONTOLOGICOS INICIADOS'.'|';
          $this->salida .= "      <td width=\"35%\">TRATAMIENTOS ODONTOLOGICOS TERMINADOS</td>";
          $archivoPlano.='TRATAMIENTOS ODONTOLOGICOS TERMINADOS'."\n";
          $this->salida .= "      </tr>";

          $this->salida .= "      <tr class=\"modulo_list_claro\">";
          $this->salida .= "      <td align=\"center\" class=\"label\" width=\"35%\">".$TI."</td>";
          $archivoPlano.=$TI.'|';
          $this->salida .= "      <td align=\"center\" class=\"label\" width=\"35%\">".$TT."</td>";
          $archivoPlano.=$TI."\n";
          $this->salida .= "      </tr>";

          $reporte= new GetReports();
		      $mostrar=$reporte->GetJavaReport('app','Reportes_Consulta_Externa','ReporteCitasTratamientoOdontologico',array('TI'=>$TI,'TT'=>$TT,'variables'=>$_REQUEST),array('rpt_name'=>'ConsultaExternas','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));		
		      $nombre_funcion=$reporte->GetJavaFunction();
		      $this->salida .=$mostrar;
		      echo $mostrar;          
          $this->salida .= "      <tr><td align=\"center\" colspan=\"2\"><a href=\"javascript:$nombre_funcion\"><b>Imprimir Reporte</b></a>";
          $this->salida .= "       </td></tr>";
          $this->salida .= "      </table><br>";
          
          $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";        
          $this->salida .= "      <tr><td class=\"label\" align=\"center\">";
          $_SESSION['DESCARGA_DATOS_REPORTES']['DATOS']=$archivoPlano;                    
          $descarga=ModuloGetURL('app','Reportes_Consulta_Externa','user','DescargaDatosTratamientoOdontologico',array('centroutilidad'=>$_REQUEST['centroutilidad'],'unidadfunc'=>$_REQUEST['unidadfunc'],
          'departamento'=>$_REQUEST['departamento'],'profesional_escojer'=>$_REQUEST['profesional_escojer'],"feinictra"=>$_REQUEST['feinictra'],"fefinctra"=>$_REQUEST['fefinctra'],
          'centroU'=>$_REQUEST['centroU'],'unidadF'=>$_REQUEST['unidadF'],'DptoSel'=>$_REQUEST['DptoSel']));          
          $this->salida .= "      <a href=\"$descarga\">Descargar Datos <img title=\"Descargar Archivo\" border=\"0\" src=\"".GetThemePath()."/images/abajo.png\"></a>";
          $this->salida .="       </td></tr>";
          $this->salida .= "      </table><BR>";  
          
          
          $this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
          $this->salida .= "  <tr><td align=\"center\">";
          $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaReporteEstadisticoCitasTratamientoOdontologico',array('centroutilidad'=>$_REQUEST['centroutilidad'],'unidadfunc'=>$_REQUEST['unidadfunc'],
          'departamento'=>$_REQUEST['departamento'],'profesional_escojer'=>$_REQUEST['profesional_escojer'],'feinictra'=>$_REQUEST['feinictra'],'fefinctra'=>$_REQUEST['fefinctra'],
          'centroU'=>$_REQUEST['centroU'],'unidadF'=>$_REQUEST['unidadF'],'DptoSel'=>$_REQUEST['DptoSel']));
          $this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
          $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
          $this->salida .= "  </form>";
          $this->salida .= "  </td></tr>";
          $this->salida .= "  </table>";
          $this->salida .= ThemeCerrarTabla();
          return true;
     }

            
     function GetHtmlProfesional($vect,$TipoId)
     {         
     	$vector=explode(',',$TipoId);
          foreach($vect as $value=>$titulo)
          {
               if($titulo[usuario_id]==$vector[0]){
                    $this->salida .=" <option value=\"".$titulo[usuario_id].",".strtoupper($titulo[nombre])."\" selected>".strtoupper($titulo[nombre])."</option>";
               }else{
                    $this->salida .=" <option value=\"".$titulo[usuario_id].",".strtoupper($titulo[nombre])."\">".strtoupper($titulo[nombre])."</option>";
               }
          }
     }

  function ReporteEstadisticoRendimientoProf($centroutilidad,$centroU,$unidadfunc,$unidadF,$departamento,$DptoSel){

    unset($_SESSION['reconeccc']);
	  unset($_SESSION['CAUSAS_CITAS']['DATOS']);
    $this->salida = ThemeAbrirTabla('FILTRO DE DATOS PARA EL REPORTE DE RENDIMIENTO DE PROFESIONALES');

/*    $RUTA = "app_modules/Reportes_Consulta_Externa/buscador.php?sign=";
    $mostrar ="\n<script language='javascript'>\n";
    $mostrar.="var rem=\"\";\n";
    $mostrar.="  function xxx(a){\n";
    $mostrar.="    var nombre=\"\"\n";
    $mostrar.="    var url2=\"\"\n";
    $mostrar.="    var str=\"\"\n";
    $mostrar.="    var nombre=\"REPORTE\";\n";
    $mostrar.="    var str =\"width=450,height=180,resizable=no,location=no, status=no,scrollbars=yes\";\n";
    $mostrar.="    var url2 ='$RUTA';\n";
    $mostrar.="    url2 +=a;\n";
    $mostrar.="    rem = window.open(url2, nombre, str)};\n";
    $mostrar.="</script>\n";
    $this->salida .= $mostrar;
*/
    $this->salida .= "  <table border=\"0\" width=\"50%\" align=\"center\">";
    $this->salida .= "  <tr><td>";
    $this->Cabecera();
    $this->salida .= "  <table border=\"0\" width=\"100%\"  align=\"center\">";
    $this->salida .=    $this->SetStyle("MensajeError");
    $this->salida .= "  </table><br>";
    $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaEstadisticoRendimientoProf');
    $this->salida .= "  <form name=\"data\" action=\"$accion\" method=\"post\">";
    $this->salida .= "  		<table border=\"0\" width=\"100%\"   align=\"center\">";
    $this->salida .= "  		<tr>";
    $this->salida .= "  		<td>";
    $this->salida .= "  		<fieldset><legend class=\"field\">INGRESO DE DATOS</legend>";
    $this->salida .= "    <br><table border=\"0\" width=\"95%\"  align=\"center\">";
    /*$this->salida .= "    <tr><td width=\"15%\" class=\"label\">CENTRO UTILIDAD: </td>";
    $this->salida .= "    <td width=\"15%\"><input type=\"text\" class=\"input-text\" name=\"centroutilidad\" value=\"".$_REQUEST['centroutilidad']."\" maxlength=\"100\" size=\"40\" readonly></td></tr>";
    $this->salida .= "    <tr><td width=\"20%\" class=\"label\">UNIDAD FUNCIONAL: </td>";
    $this->salida .= "    <td width=\"15%\"><input type=\"text\" class=\"input-text\" name=\"unidadfunc\" value=\"".$_REQUEST['unidadfunc']."\" maxlength=\"100\" size=\"40\" readonly></td></tr>";
    $this->salida .= "    <tr><td width=\"15%\" class=\"label\">DEPARTAMENTO: </td>";
    $this->salida .= "    <td width=\"15%\"><input type=\"text\" class=\"input-text\" name=\"departamento\" value=\"".$_REQUEST['departamento']."\" maxlength=\"100\" size=\"40\" readonly>";
    $this->salida .= "    &nbsp;<a href=\"javascript:xxx(1)\"><img title='Busqueda del Departamento' src=\"". GetThemePath() ."/images/auditoria.png\" border='0' width='14' height='14'></td></tr>";
*/
 
		$this->salida .= "<input type=\"hidden\" name=\"centroutilidad\" value=\"".$centroutilidad."\" class=\"input-text\">";
		$this->salida .= "<input type=\"hidden\" name=\"unidadfunc\" value=\"".$unidadfunc."\" class=\"input-text\">";
		$this->salida .= "<input type=\"hidden\" name=\"departamento\" value=\"".$departamento."\" class=\"input-text\">";
		$this->salida .= "<input type=\"hidden\" name=\"centroU\" value=\"".$centroU."\" class=\"input-text\">";
		$this->salida .= "<input type=\"hidden\" name=\"unidadF\" value=\"".$unidadF."\" class=\"input-text\">";
		$this->salida .= "<input type=\"hidden\" name=\"DptoSel\" value=\"".$DptoSel."\" class=\"input-text\">";
  
    
   
    $this->salida .= "    <tr><td class=\"formulacion_table_list\">PROFESIONAL: </td><td><select name=\"profesional_escojer\" class=\"select\">";    
    $vector_P=$this->Get_Profesionales();
    if(sizeof($vector_P)>1){
      $this->salida .= "      <option value=\"-1\">--  SELECCIONE--</option>";
    }
    $this->GetHtmlProfesional($vector_P,$_REQUEST['profesional_escojer']);
    $this->salida .= "</select></td></tr>";

    $this->salida .= "      <tr>";
    $this->salida .= "      <td class=\"formulacion_table_list\" width=\"30%\">";
    $this->salida .= "      <label  >FECHA INICIAL</label>";
    $this->salida .= "      </td>";
    $this->salida .= "      <td class=\"label\">";
    if(empty($_REQUEST['feinictra'])){
        $_REQUEST['feinictra']=date('01/m/Y');
    }
    $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"feinictra\" value=\"".$_REQUEST['feinictra']."\" maxlength=\"10\" size=\"15\">";
    $this->salida .= "      ".ReturnOpenCalendario('data','feinictra','/')."";
    $this->salida .= "      </td>";
    $this->salida .= "      </tr>";
    $this->salida .= "      <tr>";
    $this->salida .= "      <td class=\"formulacion_table_list\" width=\"30%\">";
    $this->salida .= "      <label  >FECHA FINAL</label>";
    $this->salida .= "      </td>";
    $this->salida .= "      <td class=\"label\">";
    if(empty($_REQUEST['fefinctra'])){
        $_REQUEST['fefinctra']=date('d/m/Y');
    }
    $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fefinctra\" value=\"".$_REQUEST['fefinctra']."\" maxlength=\"10\" size=\"15\">";
    $this->salida .= "      ".ReturnOpenCalendario('data','fefinctra','/')."";
    $this->salida .= "      </td>";
    $this->salida .= "      </tr>";
    $this->salida .= "      <tr>";
    $this->salida .= "      <td  colspan=\"2\" align=\"center\"><br>";
    $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"GENERAR REPORTE\">";
    $this->salida .= "      </td>";
    $this->salida .= "  		</form>";
    $this->salida .= "      </tr>";
    $this->salida .= "  		</fieldset>";
    $this->salida .= "  		</table><br>";
    $this->salida .= "  		</td>";
    $this->salida .= "  		</tr>";
    $this->salida .= "  		</table>";
    $this->salida .= "  </td></tr>";
    $this->salida .= "  <tr>";
    $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','GuardarSeleccionDepartamentoUnificado',array("centroutilidad"=>$centroutilidad,"centroU"=>$centroU,"unidadfunc"=>$unidadfunc,"unidadF"=>$unidadF,"departamento"=>$departamento,"DptoSel"=>$DptoSel));
    $this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
    $this->salida .= "  <td align=\"center\">";
    $this->salida.= "   <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
    $this->salida .= "  </td>";
    $this->salida .= "  </form>";
    $this->salida .= "  </tr>";
    $this->salida .= "	</table>";
    $this->salida .= ThemeCerrarTabla();
    return true;
	}

	function EstadisticaRendimientoProf($centroU,$centroutilidad,$unidadF,$unidadfunc,$departamento,$DptoSel,$profesional_escojer,$feinictra,$fefinctra)
	{ $archivoPlano='';
    $this->salida  = ThemeAbrirTabla('REPORTE ESTADISTICO DE RENDIMIENTO PROFESIONALES');
		$this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
    $this->salida .= "<tr><td>";
    $this->Cabecera();
    $this->salida .= "</td></tr>";
		$this->salida .= "<tr><td>";
		$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
    if(!empty($centroutilidad)){
		$this->salida .= "      <tr >";
		$this->salida .= "      <td class=\"formulacion_table_list\" width=\"30%\">CENTRO UTILIDAD";
		$this->salida .= "      </td>";
		$this->salida .= "      <td  class=\"modulo_list_oscuro\" align=\"center\"width=\"70%\">";
		$this->salida .= "      ".$centroutilidad."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		}
    if(!empty($unidadfunc)){
		$this->salida .= "      <tr >";
		$this->salida .= "      <td class=\"formulacion_table_list\" width=\"30%\">UNIDAD FUNCIONAL";
		$this->salida .= "      </td>";
		$this->salida .= "      <td class=\"modulo_list_oscuro\" align=\"center\"width=\"70%\">";
		$this->salida .= "      ".$unidadfunc."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		}
		if(!empty($departamento)){
		$this->salida .= "      <tr >";
		$this->salida .= "      <td class=\"formulacion_table_list\"width=\"30%\">DEPARTAMENTO";
		$this->salida .= "      </td>";
		$this->salida .= "      <td class=\"modulo_list_oscuro\" align=\"center\"width=\"70%\">";
		$this->salida .= "      ".$departamento."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		}
		if($profesional_escojer!=-1){
			$this->salida .= "      <tr >";
			$this->salida .= "      <td class=\"formulacion_table_list\" width=\"30%\">NOMBRE DEL PROFESIONAL";
			$this->salida .= "      </td>";
			$this->salida .= "      <td  class=\"modulo_list_oscuro\" align=\"center\" width=\"70%\">";
      $vector=explode(',',$profesional_escojer);
			$this->salida .= "      ".$vector[1]."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}
		if(!empty($feinictra)){
			$this->salida .= "      <tr >";
			$this->salida .= "      <td class=\"formulacion_table_list\" width=\"30%\">FECHA INICIAL";
			$this->salida .= "      </td>";
			$this->salida .= "      <td class=\"modulo_list_oscuro\" align=\"center\" width=\"70%\">";
			$this->salida .= "      ".$feinictra."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}
		if(!empty($fefinctra)){
			$this->salida .= "      <tr >";
			$this->salida .= "      <td class=\"formulacion_table_list\" width=\"30%\">FECHA FINAL";
			$this->salida .= "      </td>";
			$this->salida .= "      <td  class=\"modulo_list_oscuro\" align=\"center\" width=\"70%\">";
			$this->salida .= "      ".$fefinctra."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}
		if(empty($centroutilidad) && empty($unidadfunc) && empty($DptoSel) && empty($feinictra) && empty($fefinctra) && $profesional_escojer!=-1){
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PARAMETROS DE BUSQUEDA:";
			$this->salida .= "      </td>";
			$this->salida .= "      <td  class=\"modulo_list_oscuro\" align=\"center\" width=\"70%\">";
			$this->salida .= "NINGUNO";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}
    $this->salida .= "      </table><br>";
    $this->salida .= "    </td></tr>";
    $this->salida .= "   </table><br>";
    $registros=$this->ConsultaEstadisticaRendimientoProf($centroU,$unidadF,$DptoSel,$profesional_escojer,$feinictra,$fefinctra);
		if($registros){
      $this->salida .= "      <table border=\"0\" width=\"80%\" class=\"modulo_table_list\"  align=\"center\">";
      $this->salida .= "      <tr >";      
      $this->salida .= "      <td class=\"formulacion_table_list\" >PROFESIONALES</td>";
      $archivoPlano.='PROFESIONALES'.'|';
      $this->salida .= "      <td class=\"formulacion_table_list\"  width=\"9%\">ASIGNADAS</td>";
      $archivoPlano.='ASIGNADAS'.'|';
      $this->salida .= "      <td class=\"formulacion_table_list\"  width=\"10%\">CANCELADAS</td>";
      $archivoPlano.='CANCELADAS'.'|';
      $this->salida .= "      <td class=\"formulacion_table_list\"   width=\"10%\">ATENDIDAS</td>";
      $archivoPlano.='ATENDIDAS'.'|';
      $this->salida .= "      <td class=\"formulacion_table_list\"  width=\"10%\">HC ABIERTAS</td>";
      $archivoPlano.='HC ABIERTAS'.'|';
			$this->salida .= "      <td class=\"formulacion_table_list\"  width=\"10%\">DIAS</td>";
      $archivoPlano.='DIAS'.'|';
      $this->salida .= "      <td class=\"formulacion_table_list\"  width=\"15%\">PROMEDIO DE ATENCION (HH:mm)</td>";
      $archivoPlano.='PROMEDIO DE ATENCION (HH:mm)'.'|';
      $this->salida .= "      <td  class=\"formulacion_table_list\"  width=\"15%\">PROMEDIO CONSULTAS POR DIA</td>";
      $archivoPlano.='PROMEDIO CONSULTAS POR DIA'."\n";
      $this->salida .= "      </tr>";
			//------------nuevo dar
			for($i=0; $i<sizeof($registros); $i++)
			{
					if($i % 2){ $estilo='modulo_list_oscuro';}else{$estilo='modulo_list_claro';}
					$this->salida .= "      <tr class=\"$estilo\">";
					$this->salida .= "      <td>".$registros[$i]['nombre']."</td>";
          $archivoPlano.=$registros[$i]['nombre'].'|';
					$this->salida .= "      <td>".$registros[$i]['asignadas']."</td>";
          $archivoPlano.=$registros[$i]['asignadas'].'|';
					$this->salida .= "      <td>".$registros[$i]['canceladas']."</td>";
          $archivoPlano.=$registros[$i]['canceladas'].'|';
					$this->salida .= "      <td>".$registros[$i]['atendidas']."</td>";
          $archivoPlano.=$registros[$i]['atendidas'].'|';
					$this->salida .= "      <td>".$registros[$i]['abiertas']."</td>";	
          $archivoPlano.=$registros[$i]['abiertas'].'|';
					if($registros[$i]['promedio'])
					{
							$diasConsulta=$this->DiasLaboradosProfesional($feinictra,$fefinctra,$registros[$i]['usuario']);
							$this->salida .= "      <td>".$diasConsulta."</td>";
              $archivoPlano.=$diasConsulta.'|';
							(list($duracion,$minutos)=explode(':',$registros[$i]['promedio']));
							$this->salida .= "      <td>".$duracion.":".$minutos."</td>";					
              $archivoPlano.=$duracion.":".$minutos.'|';
							$this->salida .= "      <td>".round($registros[$i]['atendidas']/$diasConsulta,1)."</td>";
              $archivoPlano.=round($registros[$i]['atendidas']/$diasConsulta,1)."\n";
					}
					else
					{
							$this->salida .= "      <td>&nbsp;</td>";
              $archivoPlano.='|';
							$this->salida .= "      <td>&nbsp;</td>";
              $archivoPlano.='|';
							$this->salida .= "      <td>&nbsp;</td>";
              $archivoPlano.="\n";
					}					
			}
			//------------fin nuevo dar			
     $this->salida .= "      </table><br>";
		}else{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= "      <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON DATOS CON ESTOS PARAMETROS</td></tr>";
			$this->salida .= "      </table><br>";
		}
    $reporte= new GetReports();//FALSE
		$mostrar=$reporte->GetJavaReport('app','Reportes_Consulta_Externa','ReporteRendimientoProfesionales',array("empresa"=>$_SESSION['recoex']['razonso'],"centroutilidad"=>$centroutilidad,"unidadfunc"=>$unidadfunc,
    "departamento"=>$departamento,"profesional_escojer"=>$profesional_escojer,"feinictra"=>$feinictra,"fefinctra"=>$fefinctra,"centroU"=>$centroU,"unidadF"=>$unidadF,"DptoSel"=>$DptoSel),array('rpt_name'=>'ConsultaExternas','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
		$funcion=$reporte->GetJavaFunction();
		$this->salida .= "$mostrar";
		$this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
		$this->salida .= "  <tr><td align=\"left\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"button\" name=\"imprimir1\" value=\"IMPRIMIR REPORTE\" onclick=\"javascript:$funcion\">";
    $this->salida .="   </td></tr>";
    $this->salida .= "  </table><br>";
    
    $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";        
    $this->salida .= "      <tr><td class=\"label\" align=\"center\">";
    $_SESSION['DESCARGA_DATOS_REPORTES']['DATOS']=$archivoPlano;                    
    $descarga=ModuloGetURL('app','Reportes_Consulta_Externa','user','DescargaDatosRendimientoProf',array('centroutilidad'=>$_REQUEST['centroutilidad'],'unidadfunc'=>$_REQUEST['unidadfunc'],
    'departamento'=>$_REQUEST['departamento'],'profesional_escojer'=>$_REQUEST['profesional_escojer'],"feinictra"=>$_REQUEST['feinictra'],"fefinctra"=>$_REQUEST['fefinctra'],
    'centroU'=>$_REQUEST['centroU'],'unidadF'=>$_REQUEST['unidadF'],'DptoSel'=>$_REQUEST['DptoSel']));          
    $this->salida .= "      <a href=\"$descarga\">Descargar Datos <img title=\"Descargar Archivo\" border=\"0\" src=\"".GetThemePath()."/images/abajo.png\"></a>";
    $this->salida .="       </td></tr>";
    $this->salida .= "      </table><BR>";  
              
		$accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaReporteEstadisticoRendimientoProf',array("centroU"=>$centroU,
    "centroutilidad"=>$centroutilidad,"unidadF"=>$unidadF,"unidadfunc"=>$unidadfunc,"departamento"=>$departamento,"DptoSel"=>$DptoSel,
    "profesional_escojer"=>$profesional_escojer,"feinictra"=>$feinictra,"fefinctra"=>$fefinctra));
    $this->salida .= " <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td align=\"center\">";
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
		$this->salida .= "  </form>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "</table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
  }

  function ReporteEstadisticoOportunidadCE($centroutilidad,$centroU,$unidadfunc,$unidadF,$departamento,$DptoSel){

    unset($_SESSION['reconeccc']);
	  unset($_SESSION['CAUSAS_CITAS']['DATOS']);
    $this->salida = ThemeAbrirTabla('FILTRO DE DATOS PARA EL REPORTE DE OPORTUNIDADES DE CITAS MÉDICAS');

    /*$RUTA = "app_modules/Reportes_Consulta_Externa/buscador.php?sign=";
    $mostrar ="\n<script language='javascript'>\n";
    $mostrar.="var rem=\"\";\n";
    $mostrar.="  function xxx(a){\n";
    $mostrar.="    var nombre=\"\"\n";
    $mostrar.="    var url2=\"\"\n";
    $mostrar.="    var str=\"\"\n";
    $mostrar.="    var nombre=\"REPORTE\";\n";
    $mostrar.="    var str =\"width=450,height=180,resizable=no,location=no, status=no,scrollbars=yes\";\n";
    $mostrar.="    var url2 ='$RUTA';\n";
    $mostrar.="    url2 +=a;\n";
    $mostrar.="    rem = window.open(url2, nombre, str)};\n";
    $mostrar.="</script>\n";
    $this->salida .= $mostrar;
*/
    $this->salida .= "  <table border=\"0\" width=\"50%\" align=\"center\">";
    $this->salida .= "  <tr><td>";
    $this->Cabecera();
    $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
    $this->salida .=    $this->SetStyle("MensajeError");
    $this->salida .= "  </table><br>";
    $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaEstadisticoOportunidadCE');
    $this->salida .= "  <form name=\"data\" action=\"$accion\" method=\"post\">";
    $this->salida .= "  		<table border=\"0\" width=\"100%\" align=\"center\">";
    $this->salida .= "  		<tr>";
    $this->salida .= "  		<td>";
    $this->salida .= "  		<fieldset><legend class=\"field\">INGRESO DE DATOS</legend>";
    $this->salida .= "    <br><table border=\"0\" width=\"95%\" align=\"center\">";
    /*$this->salida .= "    <tr><td width=\"15%\" class=\"label\">CENTRO UTILIDAD: </td>";
    $this->salida .= "    <td width=\"15%\"><input type=\"text\" class=\"input-text\" name=\"centroutilidad\" value=\"".$_REQUEST['centroutilidad']."\" maxlength=\"100\" size=\"40\" readonly></td></tr>";
    $this->salida .= "    <tr><td width=\"20%\" class=\"label\">UNIDAD FUNCIONAL: </td>";
    $this->salida .= "    <td width=\"15%\"><input type=\"text\" class=\"input-text\" name=\"unidadfunc\" value=\"".$_REQUEST['unidadfunc']."\" maxlength=\"100\" size=\"40\" readonly></td></tr>";
    $this->salida .= "    <tr><td width=\"15%\" class=\"label\">DEPARTAMENTO: </td>";
    $this->salida .= "    <td width=\"15%\"><input type=\"text\" class=\"input-text\" name=\"departamento\" value=\"".$_REQUEST['departamento']."\" maxlength=\"100\" size=\"40\" readonly>";
    $this->salida .= "    &nbsp;<a href=\"javascript:xxx(1)\"><img title='Busqueda del Departamento' src=\"". GetThemePath() ."/images/auditoria.png\" border='0' width='14' height='14'></td></tr>";
*/
		$this->salida .= "<input type=\"hidden\" name=\"centroutilidad\" value=\"".$centroutilidad."\" class=\"input-text\">";
		$this->salida .= "<input type=\"hidden\" name=\"unidadfunc\" value=\"".$unidadfunc."\" class=\"input-text\">";
		$this->salida .= "<input type=\"hidden\" name=\"departamento\" value=\"".$departamento."\" class=\"input-text\">";
		$this->salida .= "<input type=\"hidden\" name=\"centroU\" value=\"".$centroU."\" class=\"input-text\">";
		$this->salida .= "<input type=\"hidden\" name=\"unidadF\" value=\"".$unidadF."\" class=\"input-text\">";
		$this->salida .= "<input type=\"hidden\" name=\"DptoSel\" value=\"".$DptoSel."\" class=\"input-text\">";

    $this->salida .= "    <tr><td class=\"label\">PROFESIONAL: </td><td><select name=\"profesional_escojer\" class=\"select\">";    
    $vector_P=$this->Get_Profesionales();
    if(sizeof($vector_P)>1){
      $this->salida .= "      <option value=\"-1\">--  SELECCIONE--</option>";
    }
    $this->GetHtmlProfesional($vector_P,$_REQUEST['profesional_escojer']);
    $this->salida .= "</select></td></tr>";

    $this->salida .= "      <tr>";
    $this->salida .= "      <td width=\"30%\">";
    $this->salida .= "      <label class=\"label\">FECHA INICIAL</label>";
    $this->salida .= "      </td>";
    $this->salida .= "      <td class=\"label\">";
    if(empty($_REQUEST['feinictra'])){
        $_REQUEST['feinictra']=date('01/m/Y');
    }
    $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"feinictra\" value=\"".$_REQUEST['feinictra']."\" maxlength=\"10\" size=\"10\">";
    $this->salida .= "      ".ReturnOpenCalendario('data','feinictra','/')."";
    $this->salida .= "      </td>";
    $this->salida .= "      </tr>";
    $this->salida .= "      <tr>";
    $this->salida .= "      <td width=\"30%\">";
    $this->salida .= "      <label class=\"label\">FECHA FINAL</label>";
    $this->salida .= "      </td>";
    $this->salida .= "      <td class=\"label\">";
    if(empty($_REQUEST['fefinctra'])){
        $_REQUEST['fefinctra']=date('d/m/Y');
    }
    $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fefinctra\" value=\"".$_REQUEST['fefinctra']."\" maxlength=\"10\" size=\"10\">";
    $this->salida .= "      ".ReturnOpenCalendario('data','fefinctra','/')."";
    $this->salida .= "      </td>";
    $this->salida .= "      </tr>";
    $this->salida .= "      <tr>";
    $this->salida .= "      <td colspan=\"2\" align=\"center\"><br>";
    $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"GENERAR REPORTE\">";
    $this->salida .= "      </td>";
    $this->salida .= "  		</form>";
    $this->salida .= "      </tr>";
    $this->salida .= "  		</fieldset>";
    $this->salida .= "  		</table><br>";
    $this->salida .= "  		</td>";
    $this->salida .= "  		</tr>";
    $this->salida .= "  		</table>";
    $this->salida .= "  </td></tr>";
    $this->salida .= "  <tr>";
    $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','GuardarSeleccionDepartamentoUnificado',array("centroutilidad"=>$centroutilidad,"centroU"=>$centroU,"unidadfunc"=>$unidadfunc,"unidadF"=>$unidadF,"departamento"=>$departamento,"DptoSel"=>$DptoSel));
    $this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
    $this->salida .= "  <td align=\"center\">";
    $this->salida.= "   <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
    $this->salida .= "  </td>";
    $this->salida .= "  </form>";
    $this->salida .= "  </tr>";
    $this->salida .= "	</table>";
    $this->salida .= ThemeCerrarTabla();
    return true;
	}

  function EstadisticaOportunidadCE($centroU,$centroutilidad,$unidadF,
    $unidadfunc,$departamento,$DptoSel,$profesional_escojer,$feinictra,$fefinctra){
    $archivoPlano='';
    $this->salida  = ThemeAbrirTabla('REPORTE ESTADISTICO DE OPORTUNIDADES DE CITAS MÉDICAS');
		$this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
    $this->salida .= "<tr><td>";
    $this->Cabecera();
    $this->salida .= "</td></tr>";
		$this->salida .= "<tr><td>";
		$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
    if(!empty($centroutilidad)){
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">CENTRO UTILIDAD";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\"width=\"70%\">";
		$this->salida .= "      ".$centroutilidad."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		}
    if(!empty($unidadfunc)){
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">UNIDAD FUNCIONAL";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\"width=\"70%\">";
		$this->salida .= "      ".$unidadfunc."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		}
		if(!empty($departamento)){
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DEPARTAMENTO";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\"width=\"70%\">";
		$this->salida .= "      ".$departamento."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		}
		if($profesional_escojer!=-1){
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL PROFESIONAL";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
      $vector=explode(',',$profesional_escojer);
			$this->salida .= "      ".$vector[1]."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}
		if(!empty($feinictra)){
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">FECHA INICIAL";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "      ".$feinictra."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}
		if(!empty($fefinctra)){
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">FECHA FINAL";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "      ".$fefinctra."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}
		if(empty($centroutilidad) && empty($unidadfunc) && empty($DptoSel) && empty($feinictra) && empty($fefinctra) && $profesional_escojer!=-1){
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PARAMETROS DE BUSQUEDA:";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "NINGUNO";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}
    $this->salida .= "      </table><br>";
    $this->salida .= "    </td></tr>";
    $this->salida .= "   </table><br>";
    $registros=$this->ConsultaEstadisticaOportunidadCE($centroU,$unidadF,$DptoSel,$profesional_escojer,$feinictra,$fefinctra);
    if($registros){
      $CantRegistrosTotal=0;
      $diasTotales=0;
      foreach($registros as $usuarioId=>$vector){
        foreach($vector as $nombreProf=>$vectorUno){
          $diasTotalProf=0;
          $CantRegistros=0;
          $this->salida .= "      <table border=\"0\" width=\"80%\" align=\"center\">";
          $this->salida .= "      <tr class=\"modulo_table_list_title\">";
          $this->salida .= "      <td colspan=\"4\">".$nombreProf."</td>";
          $archivoPlano.=$nombreProf."\n";
          $this->salida .= "      </tr>";
          $this->salida .= "      <tr class=\"modulo_table_title\">";
          $this->salida .= "      <td>PACIENTE</td>";
          $archivoPlano.='PACIENTE'.'|';
          $this->salida .= "      <td width=\"20%\">FECHA ASIGNACION</td>";
          $archivoPlano.='FECHA ASIGNACION'.'|';
          $this->salida .= "      <td width=\"20%\">FECHA ATENCION</td>";
          $archivoPlano.='FECHA ATENCION'.'|';
          $this->salida .= "      <td width=\"20%\">DIAS TRANSCURRIDOS</td>";
          $archivoPlano.='DIAS TRANSCURRIDOS'."\n";
          $this->salida .= "      </tr>";
          foreach($vectorUno as $citaId=>$Datos){
              $this->salida .= "      <tr class=\"modulo_list_claro\">";
              $this->salida .= "      <td>".$Datos['nombre_pac']."</td>";
              $archivoPlano.=$Datos['nombre_pac'].'|';
              (list($fechaIn,$HoraIn)=explode(' ',$Datos['fecha_registro']));
              (list($anoIn,$mesIn,$diaIn)=explode('-',$fechaIn));
              (list($horIn,$minutosIn)=explode(':',$HoraIn));
              $this->salida .= "      <td>".ucfirst(strftime("%b %d de %Y %H:%M",mktime($horIn,$minutosIn,0,$mesIn,$diaIn,$anoIn)))."</td>";
              $archivoPlano.=ucfirst(strftime("%b %d de %Y %H:%M",mktime($horIn,$minutosIn,0,$mesIn,$diaIn,$anoIn))).'|';
              (list($fechaFn,$HoraFn)=explode(' ',$Datos['fecha']));
              (list($anoFn,$mesFn,$diaFn)=explode('-',$fechaFn));
              (list($horFn,$minutosFn)=explode(':',$HoraFn));
              $this->salida .= "      <td>".ucfirst(strftime("%b %d de %Y %H:%M",mktime($horFn,$minutosFn,0,$mesFn,$diaFn,$anoFn)))."</td>";
              $archivoPlano.=ucfirst(strftime("%b %d de %Y %H:%M",mktime($horFn,$minutosFn,0,$mesFn,$diaFn,$anoFn))).'|';
              $dias=(int)((((mktime($horFn,$minutosFn,0,$mesFn,$diaFn,$anoFn)-mktime($horIn,$minutosIn,0,$mesIn,$diaIn,$anoIn))/60)/60)/24);
              $this->salida .= "      <td align=\"left\">".$dias."</td>";
              $archivoPlano.=$dias."\n";
              $this->salida .= "      </tr>";
              $diasTotalProf+=$dias;
              $CantRegistros++;
          }
          $diasTotales+=$diasTotalProf;
          $CantRegistrosTotal+=$CantRegistros;
          $this->salida .= "      <tr class=\"modulo_list_oscuro\">";
          $this->salida .= "      <td colspan=\"3\" align=\"left\" class=\"label\">TOTAL DIAS</td>";
          $archivoPlano.='|'.'|'.'TOTAL DIAS'.'|';
          $this->salida .= "      <td align=\"left\">".$diasTotalProf."</td>";
          $archivoPlano.=$diasTotalProf."\n";
          
          $this->salida .= "      </tr>";
          $this->salida .= "      <tr class=\"modulo_list_oscuro\">";
          $this->salida .= "      <td colspan=\"3\" align=\"left\" class=\"label\">TOTAL CONSULTAS</td>";
          $archivoPlano.='|'.'|'.'TOTAL CONSULTAS'.'|';
          $this->salida .= "      <td align=\"left\">".$CantRegistros."</td>";
          $archivoPlano.=$CantRegistros."\n";
          $this->salida .= "      </tr>";
          $this->salida .= "      <tr class=\"modulo_list_oscuro\">";
          $this->salida .= "      <td colspan=\"3\" align=\"left\" class=\"label\">PROMEDIO</td>";
          $archivoPlano.='|'.'|'.'PROMEDIO'.'|';
          $this->salida .= "      <td align=\"left\">".round(($diasTotalProf/$CantRegistros),2)."</td>";
          $archivoPlano.=round(($diasTotalProf/$CantRegistros),2)."\n";
          $this->salida .= "      </tr>";
          $this->salida .= "      </table><br>";
        }
      }
      $this->salida .= "      <table border=\"0\" width=\"80%\" align=\"center\">";
			 $this->salida .= "      <tr class=\"modulo_table_title\">";
      $this->salida .= "      <td colspan=\"2\">TOTALES</td>";
      $archivoPlano.='TOTALES'."\n";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=\"modulo_list_oscuro\">";
      $this->salida .= "      <td align=\"left\" class=\"label\">TOTAL DIAS</td>";
      $archivoPlano.='TOTAL DIAS'.'|';
      $this->salida .= "      <td width=\"20%\" align=\"left\">".$diasTotales."</td>";
      $archivoPlano.=$diasTotales."\n";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=\"modulo_list_oscuro\">";
      $this->salida .= "      <td align=\"left\" class=\"label\">TOTAL CONSULTAS</td>";
      $archivoPlano.='TOTAL CONSULTAS'.'|';
      $this->salida .= "      <td width=\"20%\" align=\"left\">".$CantRegistrosTotal."</td>";
      $archivoPlano.=$CantRegistrosTotal."\n";
      $this->salida .= "      </tr>";
      $this->salida .= "      <tr class=\"modulo_list_oscuro\">";
      $this->salida .= "      <td align=\"left\" class=\"label\">PROMEDIO</td>";
      $archivoPlano.='PROMEDIO'.'|';
      $this->salida .= "      <td width=\"20%\" align=\"left\">".round(($diasTotales/$CantRegistrosTotal),2)."</td>";
      $archivoPlano.=round(($diasTotales/$CantRegistrosTotal),2)."\n";
      $this->salida .= "      </tr>";
			$this->salida .= "      </table><br>";
		}else{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= "      <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON DATOS CON ESTOS PARAMETROS</td></tr>";
			$this->salida .= "      </table><br>";
		}
    $reporte= new GetReports();//FALSE
    $mostrar=$reporte->GetJavaReport('app','Reportes_Consulta_Externa','ReporteOportunidadesCE',array("empresa"=>$_SESSION['recoex']['razonso'],"centroutilidad"=>$centroutilidad,"centroU"=>$centroU,"unidadfunc"=>$unidadfunc,
    "unidadF"=>$unidadF,"DptoSel"=>$DptoSel,"departamento"=>$departamento,"profesional_escojer"=>$profesional_escojer,"feinictra"=>$feinictra,"fefinctra"=>$fefinctra),array('rpt_name'=>'ConsultaExternas','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
    $funcion=$reporte->GetJavaFunction();
    $this->salida .= "$mostrar";
    $this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
    $this->salida .= "  <tr><td align=\"left\">";
    $this->salida .= "  <input class=\"input-submit\" type=\"button\" name=\"imprimir1\" value=\"IMPRIMIR REPORTE\" onclick=\"javascript:$funcion\">";
    $this->salida .="   </td></tr>";
    $this->salida .= "  </table><br>";

    $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";        
    $this->salida .= "      <tr><td class=\"label\" align=\"center\">";
    $_SESSION['DESCARGA_DATOS_REPORTES']['DATOS']=$archivoPlano;                    
    $descarga=ModuloGetURL('app','Reportes_Consulta_Externa','user','DescargaDatosOportunidadCE',array('centroutilidad'=>$_REQUEST['centroutilidad'],'unidadfunc'=>$_REQUEST['unidadfunc'],
    'departamento'=>$_REQUEST['departamento'],'profesional_escojer'=>$_REQUEST['profesional_escojer'],"feinictra"=>$_REQUEST['feinictra'],"fefinctra"=>$_REQUEST['fefinctra'],
    'centroU'=>$_REQUEST['centroU'],'unidadF'=>$_REQUEST['unidadF'],'DptoSel'=>$_REQUEST['DptoSel']));          
    $this->salida .= "      <a href=\"$descarga\">Descargar Datos <img title=\"Descargar Archivo\" border=\"0\" src=\"".GetThemePath()."/images/abajo.png\"></a>";
    $this->salida .="       </td></tr>";
    $this->salida .= "      </table><BR>"; 
    
		$accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaReporteEstadisticoOportunidadCE',array("centroU"=>$centroU,
    "centroutilidad"=>$centroutilidad,"unidadF"=>$unidadF,"unidadfunc"=>$unidadfunc,"departamento"=>$departamento,"DptoSel"=>$DptoSel,
    "profesional_escojer"=>$profesional_escojer,"feinictra"=>$feinictra,"fefinctra"=>$fefinctra));
    $this->salida .= " <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td align=\"center\">";
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
		$this->salida .= "  </form>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "</table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
  }
    /**
    * Funcion donde se crea el buscador del reporte del rendimiento del personal
    *
    * @param string $centroutilidad Descripcion del centro de utilidad
    * @param string $centroU Identificador del centro de utilidad
    * @param string $unidadfunc Descripcion de la unidad funcional
    * @param string $unidadF Identificador de la unidad funcional
    * @param string $departamento Descripcion del departamento
    * @param string $DptoSel Identificador del departamento
    * @param array $planes Arreglo de datos con los planes
    *
    */
    function ReporteEstadisticoRendimientoPersonal($centroutilidad,$centroU,$unidadfunc,$unidadF,$departamento,$DptoSel, $planes)
    {
      unset($_SESSION['reconeccc']);
  	  unset($_SESSION['CAUSAS_CITAS']['DATOS']);
      
      $this->salida  = "<script>\n";
      $this->salida .= "  function textoPlan(objeto)\n";
      $this->salida .= "  {\n";
      $this->salida .= "    objeto.descripcion_plan.value= objeto.plan_afiliacion.options[objeto.plan_afiliacion.selectedIndex].text; \n";
      $this->salida .= "  }\n";
      $this->salida .= "</script>\n";
      $this->salida .= ThemeAbrirTabla('FILTRO DE DATOS PARA EL REPORTE DE RENDIMIENTO DEL PERSONAL');
      $this->salida .= "<table border=\"0\" width=\"60%\" align=\"center\">\n";
      $this->salida .= "  <tr>\n";
      $this->salida .= "    <td>\n";
      $this->Cabecera();
      $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
      $this->salida .= $this->SetStyle("MensajeError");
      $this->salida .= "      </table><br>";
      $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaEstadisticoRendimientoPersonal');
      $this->salida .= "      <form name=\"data\" action=\"$accion\" method=\"post\">";
      $this->salida .= "  		  <table border=\"0\" width=\"100%\" align=\"center\">";
      $this->salida .= "  		    <tr>";
      $this->salida .= "  		      <td>";
  		$this->salida .= "              <input type=\"hidden\" name=\"centroutilidad\" value=\"".$centroutilidad."\" class=\"input-text\">";
  		$this->salida .= "              <input type=\"hidden\" name=\"unidadfunc\" value=\"".$unidadfunc."\" class=\"input-text\">";
  		$this->salida .= "              <input type=\"hidden\" name=\"departamento\" value=\"".$departamento."\" class=\"input-text\">";
  		$this->salida .= "              <input type=\"hidden\" name=\"centroU\" value=\"".$centroU."\" class=\"input-text\">";
  		$this->salida .= "              <input type=\"hidden\" name=\"unidadF\" value=\"".$unidadF."\" class=\"input-text\">";
  		$this->salida .= "              <input type=\"hidden\" name=\"DptoSel\" value=\"".$DptoSel."\" class=\"input-text\">";
      $this->salida .= "              <input type=\"hidden\" name=\"descripcion_plan\" value=\"".$_REQUEST['descripcion_plan']."\">\n";
      $this->salida .= "  		        <fieldset class=\"fieldset\">\n";
      $this->salida .= "                <legend class=\"normal_10AN\">INGRESO DE DATOS</legend>\n";
      $this->salida .= "                <table border=\"0\" width=\"95%\" align=\"center\">";
      $this->salida .= "                  <tr class=\"label\" >\n";
      $this->salida .= "                    <td width=\"30%\">USUARIO: </td>\n";
      $this->salida .= "                    <td>\n";
      $this->salida .= "                      <select name=\"usuario_escojer\" class=\"select\">";    
      $vector_U=$this->Get_UsuariosAsignanCitas();
      if(sizeof($vector_U)>1)
        $this->salida .= "                        <option value=\"-1\">--  SELECCIONE--</option>";
      
      $this->GetHtmlProfesional($vector_U,$_REQUEST['usuario_escojer']);
      $this->salida .= "                      </select>\n";
      $this->salida .= "                    </td>\n";
      $this->salida .= "                  </tr>";
      $this->salida .= "                  <tr class=\"label\">\n";
      $this->salida .= "                    <td >PLAN DE AFILIACIÓN:</td>\n";		
      $this->salida .= "                    <td >\n";
      $this->salida .= "                      <select name=\"plan_afiliacion\" class=\"select\" onChange=\"textoPlan(document.data)\">\n";
      $this->salida .= "                        <option value=\"-1\" selected>-- SELECCIONAR --</option>\n";
      $s = "";
      foreach($planes as $key => $dtl)
      {
        ($key == $_REQUEST['plan_afiliacion'])? $s = "selected": $s = "";
        $this->salida .= "                        <option value=\"".$key."\" ".$s.">".$dtl['plan_descripcion']."</option>\n";
      }
      $this->salida .= "                      </select>\n";
      $this->salida .= "                    </td>\n";
      $this->salida .= "                  </tr>\n";
      $this->salida .= "                  <tr class=\"label\">\n";
      $this->salida .= "                    <td>FECHA INICIAL</td>";
      $this->salida .= "                    <td>";
      if(empty($_REQUEST['feinictra']))
        $_REQUEST['feinictra']=date('01/m/Y');
      
      $this->salida .= "                      <input type=\"text\" class=\"input-text\" name=\"feinictra\" value=\"".$_REQUEST['feinictra']."\" maxlength=\"10\" size=\"10\">";
      $this->salida .= "                      ".ReturnOpenCalendario('data','feinictra','/')."";
      $this->salida .= "                    </td>";
      $this->salida .= "                  </tr>";
      $this->salida .= "                  <tr class=\"label\">\n";
      $this->salida .= "                    <td>FECHA FINAL</td>\n";
      $this->salida .= "                    <td>\n";
      if(empty($_REQUEST['fefinctra']))
          $_REQUEST['fefinctra']=date('d/m/Y');
      
      $this->salida .= "                      <input type=\"text\" class=\"input-text\" name=\"fefinctra\" value=\"".$_REQUEST['fefinctra']."\" maxlength=\"10\" size=\"10\">";
      $this->salida .= "                      ".ReturnOpenCalendario('data','fefinctra','/')."";
      $this->salida .= "                    </td>";
      $this->salida .= "                  </tr>";
      $this->salida .= "                  <tr>";
      $this->salida .= "                    <td colspan=\"2\" align=\"center\"><br>";
      $this->salida .= "                      <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"GENERAR REPORTE\">";
      $this->salida .= "                    </td>";
      $this->salida .= "  		            </form>";
      $this->salida .= "                  </tr>";
      $this->salida .= "  		          </table><br>";
      $this->salida .= "  		        </fieldset>";
      $this->salida .= "  		      </td>";
      $this->salida .= "  		    </tr>";
      $this->salida .= "  		  </table>";
      $this->salida .= "      </td>\n";
      $this->salida .= "    </tr>";
      $this->salida .= "  <tr>";
      $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','GuardarSeleccionDepartamentoUnificado',array("centroutilidad"=>$centroutilidad,"centroU"=>$centroU,"unidadfunc"=>$unidadfunc,"unidadF"=>$unidadF,"departamento"=>$departamento,"DptoSel"=>$DptoSel));
      $this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
      $this->salida .= "  <td align=\"center\">";
      $this->salida.= "   <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
      $this->salida .= "  </td>";
      $this->salida .= "  </form>";
      $this->salida .= "  </tr>";
      $this->salida .= "	</table>";
      $this->salida .= ThemeCerrarTabla();
      return true;
  	}

    function EstadisticaRendimientoPersonal($centroU,$centroutilidad,$unidadF,
      $unidadfunc,$departamento,$DptoSel,$usuario_escojer,$feinictra,$fefinctra)
    {
      $archivoPlano='';
      $this->salida  = ThemeAbrirTabla('REPORTE ESTADISTICO DE RENDIMIENTO DEL PERSONAL');
  		$this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
      $this->salida .= "<tr><td>";
      $this->Cabecera();
      $this->salida .= "</td></tr>";
  		$this->salida .= "<tr><td>";
  		$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";

      if(!empty($centroutilidad) && empty($_SESSION['recoex']['descentro']))
      {
    		$this->salida .= "      <tr class=modulo_list_claro>";
    		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">CENTRO UTILIDAD";
    		$this->salida .= "      </td>";
    		$this->salida .= "      <td align=\"center\"width=\"70%\">";
    		$this->salida .= "      ".$centroutilidad."";
    		$this->salida .= "      </td>";
    		$this->salida .= "      </tr>";
  		}
      if(!empty($unidadfunc) && empty($_SESSION['recoex']['desunidadfun']))
      {
    		$this->salida .= "      <tr class=modulo_list_claro>";
    		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">UNIDAD FUNCIONAL";
    		$this->salida .= "      </td>";
    		$this->salida .= "      <td align=\"center\"width=\"70%\">";
    		$this->salida .= "      ".$unidadfunc."";
    		$this->salida .= "      </td>";
    		$this->salida .= "      </tr>";
  		}
  		if(!empty($departamento))
      {
    		$this->salida .= "      <tr class=modulo_list_claro>";
    		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DEPARTAMENTO";
    		$this->salida .= "      </td>";
    		$this->salida .= "      <td align=\"center\"width=\"70%\">";
    		$this->salida .= "      ".$departamento."";
    		$this->salida .= "      </td>";
    		$this->salida .= "      </tr>";
  		}
      if($usuario_escojer!=-1)
      {
  			$this->salida .= "      <tr class=modulo_list_claro>";
  			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL USUARIO";
  			$this->salida .= "      </td>";
  			$this->salida .= "      <td align=\"center\" width=\"70%\">";
        $vector=explode(',',$usuario_escojer);
  			$this->salida .= "      ".$vector[1]."";
  			$this->salida .= "      </td>";
  			$this->salida .= "      </tr>";
  		}      
      if($_REQUEST['descripcion_plan'])
      {
  			$this->salida .= "    <tr class=modulo_list_claro>\n";
  			$this->salida .= "      <td class=\"modulo_table_list_title\">PLAN DE AFILIACIÓN</td>\n";
  			$this->salida .= "      <td align=\"center\">".$_REQUEST['descripcion_plan']."</td>\n";
  			$this->salida .= "    </tr>\n";
  		}
  		if(!empty($feinictra))
      {
  			$this->salida .= "      <tr class=modulo_list_claro>";
  			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">FECHA INICIAL";
  			$this->salida .= "      </td>";
  			$this->salida .= "      <td align=\"center\" width=\"70%\">";
  			$this->salida .= "      ".$feinictra."";
  			$this->salida .= "      </td>";
  			$this->salida .= "      </tr>";
  		}
  		if(!empty($fefinctra))
      {
  			$this->salida .= "      <tr class=modulo_list_claro>";
  			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">FECHA FINAL";
  			$this->salida .= "      </td>";
  			$this->salida .= "      <td align=\"center\" width=\"70%\">";
  			$this->salida .= "      ".$fefinctra."";
  			$this->salida .= "      </td>";
  			$this->salida .= "      </tr>";
  		}
  		if(empty($centroutilidad) && empty($unidadfunc) && empty($DptoSel) && empty($feinictra) && empty($fefinctra) && $profesional_escojer!=-1){
  			$this->salida .= "      <tr class=modulo_list_claro>";
  			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PARAMETROS DE BUSQUEDA:";
  			$this->salida .= "      </td>";
  			$this->salida .= "      <td align=\"center\" width=\"70%\">NINGUNO</td>";
  			$this->salida .= "      </tr>";
  		}
      $this->salida .= "      </table><br>";
      $this->salida .= "    </td></tr>";
      $this->salida .= "   </table><br>";
      $registros=$this->ConsultaEstadisticaRendimientoPersonal($centroU,$unidadF,$DptoSel,$usuario_escojer,$feinictra,$fefinctra,$_REQUEST['plan_afiliacion']);
      if($registros)
      {
        $this->salida .= "      <table border=\"0\" width=\"80%\" class=\"modulo_table_list\" align=\"center\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td>USUARIO</td>";
        $archivoPlano.='USUARIO'.'|';
        $this->salida .= "      <td width=\"15%\">CITAS ASIGNADAS</td>";
        $archivoPlano.='CITAS ASIGNADAS'.'|';
        $this->salida .= "      <td width=\"15%\">CITAS CUMPLIDAS</td>";
        $archivoPlano.='CITAS CUMPLIDAS'.'|';
        $this->salida .= "      <td width=\"15%\">CITAS CANCELADAS</td>";
        $archivoPlano.='CITAS CANCELADAS'.'|';
        $this->salida .= "      <td width=\"15%\">PROMEDIO</td>";
        $archivoPlano.='PROMEDIO'."\n";
        $this->salida .= "      </tr>";
        $TotalDias=0;
        $valorAsignadasTotal=0;
        $plan_afiliacion = $_REQUEST['plan_afiliacion'];
        foreach($registros as $usuario_id=>$Datos)
        {
          if($i % 2){ $estilo='modulo_list_oscuro';}else{$estilo='modulo_list_claro';}
          $this->salida .= "      <tr class=\"$estilo\">";
          $this->salida .= "      <td>".$Datos['nombre']."</td>";
          $archivoPlano.=$Datos['nombre'].'|';
          $asignadasCancel=$this->CitasAsignadasCanceladasRendimientoPersonal($usuario_id,$centroU,$unidadF,$DptoSel,$usuario_escojer,$feinictra,$fefinctra,$plan_afiliacion);
          $this->salida .= "      <td>".$asignadasCancel['asignadas']."</td>";
          $archivoPlano.=$asignadasCancel['asignadas'].'|';
          $this->salida .= "      <td>".$asignadasCancel['cumplimiento']."</td>";
          $archivoPlano.=$asignadasCancel['cumplimiento'].'|';
          $this->salida .= "      <td>".$asignadasCancel['canceladas']."</td>";
          $archivoPlano.=$asignadasCancel['canceladas'].'|';
          $this->salida .= "      <td>".round((($valorAsignadas=$asignadasCancel['asignadas'] - $asignadasCancel['canceladas'])/$asignadasCancel['cantidaddias']),2)."</td>";
          $archivoPlano.=round((($valorAsignadas=$asignadasCancel['asignadas'] - $asignadasCancel['canceladas'])/$asignadasCancel['cantidaddias']),2)."\n";
          $this->salida .= "      </tr>";
          $TotalDias+=$asignadasCancel['cantidaddias'];
          $valorAsignadasTotal+=$valorAsignadas;
  			}
        $this->salida .= "      <tr class=\"$estilo\">";
        $this->salida .= "      <td colspan=\"4\" class=\"label\">TOTAL</td>";
        $archivoPlano.='|'.'|'.'TOTAL'.'|';
        $this->salida .= "      <td>".round(($valorAsignadasTotal/$TotalDias),2)."</td>";
        $archivoPlano.=round(($valorAsignadasTotal/$TotalDias),2)."\n";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table><br>";
  		}else{
  			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
  			$this->salida .= "      <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON DATOS CON ESTOS PARAMETROS</td></tr>";
  			$this->salida .= "      </table><br>";
  		}
      $reporte= new GetReports();//FALSE
  		$mostrar=$reporte->GetJavaReport('app','Reportes_Consulta_Externa','ReporteRendimientoPersonal',array("empresa"=>$_SESSION['recoex']['razonso'],"centroutilidad"=>$centroutilidad,"unidadfunc"=>$unidadfunc,
      "centroU"=>$centroU,"unidadF"=>$unidadF,"DptoSel"=>$DptoSel,"departamento"=>$departamento,"usuario_escojer"=>$usuario_escojer,"feinictra"=>$feinictra,"fefinctra"=>$fefinctra,"plan_afiliacion"=>$_REQUEST['plan_afiliacion'],
      "descripcion_plan"=>$_REQUEST['descripcion_plan']),array('rpt_name'=>'ConsultaExternas','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
  		$funcion=$reporte->GetJavaFunction();
  		$this->salida .= "$mostrar";
  		$this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
  		$this->salida .= "  <tr><td align=\"left\">";
  		$this->salida .= "  <input class=\"input-submit\" type=\"button\" name=\"imprimir1\" value=\"IMPRIMIR REPORTE\" onclick=\"javascript:$funcion\">";
      $this->salida .="   </td></tr>";    
      $this->salida .= "  </table><br>";
      
      $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";        
      $this->salida .= "      <tr><td class=\"label\" align=\"center\">";
      $_SESSION['DESCARGA_DATOS_REPORTES']['DATOS']=$archivoPlano;                    
      $descarga=ModuloGetURL('app','Reportes_Consulta_Externa','user','DescargaDatosRendimientoPersonal',array('centroutilidad'=>$_REQUEST['centroutilidad'],'unidadfunc'=>$_REQUEST['unidadfunc'],
      'departamento'=>$_REQUEST['departamento'],'profesional_escojer'=>$_REQUEST['profesional_escojer'],"feinictra"=>$_REQUEST['feinictra'],"fefinctra"=>$_REQUEST['fefinctra'],
      'centroU'=>$_REQUEST['centroU'],'unidadF'=>$_REQUEST['unidadF'],'DptoSel'=>$_REQUEST['DptoSel'],"plan_afiliacion"=>$_REQUEST['plan_afiliacion']));          
      $this->salida .= "      <a href=\"$descarga\">Descargar Datos <img title=\"Descargar Archivo\" border=\"0\" src=\"".GetThemePath()."/images/abajo.png\"></a>";
      $this->salida .="       </td></tr>";
      $this->salida .= "      </table><BR>";     
      
  		$accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaReporteEstadisticoRendimientoPersonal',array("centroU"=>$centroU,
      "centroutilidad"=>$centroutilidad,"unidadF"=>$unidadF,"unidadfunc"=>$unidadfunc,"departamento"=>$departamento,"DptoSel"=>$DptoSel,
      "usuario_escojer"=>$usuario_escojer,"feinictra"=>$feinictra,"fefinctra"=>$fefinctra,"plan_afiliacion"=>$_REQUEST['plan_afiliacion'],"descripcion_plan"=>$_REQUEST['descripcion_plan']));
      $this->salida .= " <table border=\"0\" width=\"100%\" align=\"center\">";
  		$this->salida .= "  <tr><td align=\"center\">";
  		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
  		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
  		$this->salida .= "  </form>";
  		$this->salida .= "  </td></tr>";
  		$this->salida .= "</table>";
  		$this->salida .= ThemeCerrarTabla();
  		return true;
    }
  	
	function SetStyle($campo)//Mensaje de error en caso de no encontrar los datos
	{
			if ($this->frmError[$campo] || $campo=="MensajeError")
			{
					if ($campo=="MensajeError")
					{
							return ("<tr><td class='label_error' colspan='2' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
					}
					else
					{
							return ("label_error");
					}
			}
			return ("label");
	}
  
    /**
    * Funcion encargada de retornar un mensaje para el usuario
    *
    * @param string mensaje a retornar para el usuario
    * @param string titulo de la ventana a mostrar
    * @param string lugar a donde debe retornar la ventana
    * @param boolean tipo boton de la ventana
    *
    * @return boolean
    */
    function FormaMensaje($mensaje,$titulo,$accion,$download)
    {
      $this->salida .= ThemeAbrirTabla($titulo);
      $this->salida .= "            <table class=\"normal_10\" width=\"60%\" align=\"center\">";    
      $this->salida .= "               <tr><td colspan=\"2\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>";    
      $this->salida .= "               <tr><td width=\"50%\" align=\"right\">";    
      $this->salida .= "               $download";   
      $this->salida .= "               </td>";        
      $this->salida .= "               <td width=\"50%\" align=\"left\">";    
      $this->salida .= "               <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
      $this->salida .= "               <input class=\"input-submit\" type=\"submit\" name=\"Salir\" value=\"SALIR\">";  
      $this->salida .= "               </form>";
      $this->salida .= "               </td></tr>";        
      $this->salida .= "           </table>";
      $this->salida .= ThemeCerrarTabla();
      return true;
    }
  }
?>