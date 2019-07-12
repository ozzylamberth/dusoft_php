<?php
/**
 * $Id: app_Censo_userclasses_HTML.php,v 1.13 2007/07/06 16:36:28 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Clase que maneja todas las funciones de vistas y consultas a la base de datos relacionadas
 * a la estación de Enfermería
 */

/**
 * Clase HTML del modulo Censo
 * 
 *
 * @author    Ehudes Garcia <efgarcia@ipsoft-sa.com>
 * @version   $Revision: 1.13 $
 * @package   IPSOFT-SIIS-CENSO
 */
class app_Censo_userclasses_HTML extends app_Censo_user
{
	/**
	 * Constructor
	 */
	function app_Censo_userclasses_HTML()
	{
		$this->app_Censo_user();
		$this->empresa_id = $_SESSION['CENSO']['EMPRESA']['ID'];
		$this->razon_social = $_SESSION['CENSO']['EMPRESA']['RAZONSOCIAL'];
		$this->salida = "";
		$this->app_Censo_user();
		return true;
	}//Fin constructor

	/**
	 * Forma con los filtros del censo este modulo utiliza Remote Scripting
	 * para cargar las estaciones de un departamento y para cargar los
	 * planes de un tercero
	 * 
	 * @access Private
	 * @return boolean
	 */
	function FrmCenso()
	{
		$this->IncludeJS('RemoteScripting');
		$this->IncludeJS('RemoteScripting/misfunciones.js', $contenedor='app', $modulo='Censo');
		$action=ModuloGetURL('app','Censo','user','CallFrmCensoResultado',array("datos"=>$datos));
		$this->salida .= "<form name=\"frmCenso\" action=\"$action\" method=\"post\">\n";
		$this->salida .= "	<table width=\"80%\" border=\"0\" align=\"center\">\n";
		$this->salida .= "		<tr>\n";
		$this->salida .= "			<td >\n";
		$this->salida .= "				<fieldset><legend class=\"field\">BUSCADOR AVANZADO</legend>\n";
		$this->salida .= "					<table cellspacing=\"2\" cellpadding=\"3\"border=\"0\" align=\"center\">\n";
		$this->salida .= "						<tr align=\"center\">\n";
		$this->salida .= "							<td width=\"50%\" valign=\"top\">\n";
		$this->salida .= "								<fieldset><legend class=\"field\">UBIACIÓN</legend>\n";
		$this->salida .= "									<table cellspacing=\"2\" cellpadding=\"3\" border=\"0\" align=\"center\">\n";
		$this->salida .= "										<tr>\n";
		$this->salida .= "											<td class=\"label\">DEPARTAMENTO</td>\n";
		$this->salida .= "											<td>";
		$this->salida .= "											<select name=\"departamento\" class=\"select\" onChange=\"GetEstaciones(this.value);\">\n";
		$this->salida .= "												<option value=\"\">--TODOS--</option>";
		$dptos = $this->GetDepartamentos();
		foreach($dptos as $key => $value)
		{
			if($value['departamento'] == $_SESSION['CENSO']['FRMCENSO']['DPTOELEGIDO']) {$selected4 = "selected";} else $selected4 = "";
			$this->salida .= "								<option value=\"".$value['departamento']."\" $selected4>".$value['descripcion']."</option>\n";
		}
		$this->salida .= "											</select>\n";
		$this->salida .= "											</td>\n";
		$this->salida .= "										</tr>\n";
		$this->salida .= "										<tr>\n";
		$this->salida .= "											<td class=\"label\">ESTACION</td>\n";
		$this->salida .= "											<td>\n";
		$this->salida .= "											<div id=\"Estaciones\">\n";
		$this->salida .= "											<select name=\"estacion\" class=\"select\">\n";
		$this->salida .= "												<option value=\"\">--TODAS--</option>";
		$Estaciones = $this->GetEstaciones($_SESSION['CENSO']['FRMCENSO']['DPTOELEGIDO']);
		foreach($Estaciones as $key => $value)
		{
		if($value['estacion_id'] == $_REQUEST['estacion']) {$selected4 = "selected";} else $selected4 = "";
			$this->salida .= "								<option value=\"".$value['estacion_id']."\" $selected4>".$value['descripcion']."</option>\n";
		}
		$this->salida .= "											</select>\n";
		$this->salida .= "											</td>\n";
		$this->salida .= "										</tr>\n";
		$this->salida .= "									</table>\n";
		$this->salida .= "								</fieldset>\n";
		$this->salida .= "							</td>\n";
		$this->salida .= "							<td width=\"50%\" valign=\"top\">\n";
		$this->salida .= "								<fieldset><legend class=\"field\">TERCERO</legend>\n";
		$this->salida .= "									<table cellspacing=\"2\" cellpadding=\"3\" border=\"0\" align=\"center\">\n";
		$this->salida .= "										<tr>\n";
		$this->salida .= "											<td class=\"label\">ENTIDAD</td>\n";
		$this->salida .= "											<td>";
		$this->salida .= "											<select name=\"tercero\" class=\"select\" onChange=\"GetPlanes(this.value)\">\n";
		$this->salida .= "												<option value=\"\">--TODOS--</option>";
		$tercero = $this->GetTerceros();
		for($i=0; $i<sizeof($tercero); $i++)
		{
			if(strcmp($tercero[$i]['tercero_id'].".-.".$tercero[$i]['tipo_id_tercero'],$_SESSION['CENSO']['FRMCENSO']['TERCEROELEGIDO']['ID'].".-.".$_SESSION['CENSO']['FRMCENSO']['TERCEROELEGIDO']['TIPOID'])==0) {$selected4 = "selected";} else $selected4 = "";
			$this->salida .= "								<option value=\"".$tercero[$i]['tercero_id'].".-.".$tercero[$i]['tipo_id_tercero']."\" $selected4>".$tercero[$i]['nombre_tercero']."</option>\n";
		}
		$this->salida .= "											</select>\n";
		$this->salida .= "											</td>\n";
		$this->salida .= "										</tr>\n";
		$this->salida .= "										<tr>\n";
		$this->salida .= "											<td class=\"label\">PLAN</td>\n";
		$this->salida .= "											<td>";
		$this->salida .= "											<div id=\"Planes\">";
		$this->salida .= "											<select name=\"plan\" class=\"select\">\n";
		$this->salida .= "												<option value=\"\">--TODOS--</option>";
		$planes = $this->GetPlanes($_SESSION['CENSO']['FRMCENSO']['TERCEROELEGIDO']['TIPOID'],$_SESSION['CENSO']['FRMCENSO']['TERCEROELEGIDO']['ID']);
		for($i=0; $i<sizeof($planes); $i++)
		{
			if(strcmp($planes[$i]['plan_id'],$_REQUEST['plan'])==0) {$selected4 = "selected";} else $selected4 = "";
			$this->salida .= "								<option value=\"".$planes[$i]['plan_id']."\" $selected4>".$planes[$i]['plan_descripcion']."</option>\n";
		}
		$this->salida .= "											</select>\n";
		$this->salida .= "											</div>\n";
		$this->salida .= "											</td>\n";
		$this->salida .= "										</tr>\n";
		$this->salida .= "									</table>\n";
		$this->salida .= "								</fieldset>\n";	
		$this->salida .= "							</td>\n";
		$this->salida .= "						</tr>\n";
		/*$this->salida .= "						<tr>\n";
		$this->salida .= "							<td class=\"label\">TIPO  INFORME:\n";
		$this->salida .= "								<select class=\"select\" name=\"tipoInforme\">";
		$this->salida .= "									<option value=\"detallado\" selected>DETALLADO</option>";
		$this->salida .= "									<option value=\"resumido\">RESUMIDO</option>";
		$this->salida .= "								</select>";
		$this->salida .= "							</td>\n";*/
		$this->salida .= "						</tr>\n";
		$this->salida .= "					</table>\n";
		$this->salida .= "				</fieldset>\n";
		$this->salida .= "			</td>\n";
		$this->salida .= "		</tr>\n";
		$this->salida .= "	</table>\n";
		$this->salida .= "<table align=\"center\">\n";
		$this->salida .= "	<tr>\n";
		$this->salida .= "		<td width=\"50%\">\n";
		$this->salida .= "			<input type=\"submit\" class=\"input-submit\" value=\"CENSAR\">\n";
		$this->salida .= "		</td>\n";
		$this->salida .= "</form>";
		$this->salida .= "		<td width=\"50%\">\n";
		$accion=ModuloGetUrl('app','Censo','user','CallMenu');
		$this->salida .= "			<form name=\"frmVolver\" action=\"$accion\" method=\"post\">\n";
		$this->salida .= "			<input type=\"submit\" class=\"input-submit\" value=\"VOLVER\">\n";
		$this->salida .= "			</form>\n";
		$this->salida .= "		</td>\n";
		$this->salida .= "	</tr>\n";
		$this->salida .= "</table>\n";
	}//Fin FrmCenso

	/**
	 * FrmLogueo
	 *
	 * @Author Rosa Maria Angel
	 * @access Private
	 * @return bool
	 */
	function FrmLogueo()
	{
		unset($_SESSION['CENSO']);
		$Datos = $this->GetLogueo();//$modulo,$metodo
		if (!is_array($Datos)){
			return false;
		}
		$this->salida .= gui_theme_menu_acceso("CENSO - SELECCION DE EMPRESA",$Datos[0],$Datos[1],$Datos[2], ModuloGetURL('system','Menu'));
		return true;
	}//FrmLogueo

	/**
	 * Funcion Menu
	 *
	 * Muestra las opciones del menú que tiene el censo
	 *
	 * @Author Arley Velásquez
	 * @access Private
	 * @param array
	 * @return string
	 */
	function Menu()
	{
		$this->salida .= themeAbrirTabla("CENSO - OPCIONES","70%");
		unset($_SESSION['CENSO']['FRMCENSO']);//Variable de sesion del formulario
		$this->FrmEncabezado();
		$this->salida .= "<table class='normal_10' border='0' width='95%' align='center'>\n";
		$this->salida .= "	<tr><td align='center'><b><a href=\"".ModuloGetURL('app','Censo','user','CallFrmListadoPacientesHospitalizados')."\">LISTADO DE PACIENTES HOSPITALIZADOS</a></b></td></tr>\n";
		$this->salida .= "	<tr><td align='center'><b><a href=\"".ModuloGetURL('app','Censo','user','CallFrmCenso',array("datos"=>$datos))."\">BUSCAR PACIENTES HOSPITALIZADOS</a></b></td></tr>\n";
		$this->salida .= "	<tr><td align='center'><b><a href=\"".ModuloGetURL('app','Censo','user','CallFrmListadoPacientesPorIngresar')."\">LISTADO DE PACIENTES POR INGRESAR</a></b></td></tr>\n";
		$this->salida .= "	<tr><td align='center'><b><a href=\"".ModuloGetURL('app','Censo','user','CallFrmListadoProgramacionQx')."\">LISTADO DE PROGRAMACIÓN Qx</a></b></td></tr>\n";
    $this->salida .= "  <tr><td align='center'><b><a href=\"".ModuloGetURL('app','Censo','user','CallFrmListadoPacientesObsEnUrgencias')."\">LISTADO DE PACIENTES EN OBSERVACION DE URGENCIAS</a></b></td></tr>\n";
    $this->salida .= "  <tr><td align='center'><b><a href=\"".ModuloGetURL('app','Censo','user','CallFrmListadoPacientesEnConsultaUrgencias')."\">LISTADO DE PACIENTES EN CONSULTA DE URGENCIAS</a></b></td></tr>\n";
		//$this->salida .= "	<tr><td align='center'><a href=\"".ModuloGetURL('app','Censo','user','Pruebas')."\">PRUEBAS</a></td></tr>\n";
		$this->salida .= "</table>\n";
		$this->salida .= "<br>";
		$accion=ModuloGetUrl('app','Censo','user','main');
		$this->salida .= "<div align=\"center\" class=\"normal_10\"><b><a href=\"$accion\">VOLVER</a></b></div>";
		$this->salida .= themeCerrarTabla();
		return true;
	}//Fin Menu

	/**
	 * Arma la forma del buscado del censo
	 *
	 */
	function CallFrmCenso()
	{
		$this->salida .= ThemeAbrirTabla("CENSO - BUSCAR PACIENTES HOSPITALIZADOS");
		$this->FrmEncabezado();
		$this->FrmCenso();
		$this->salida .= ThemeCerrarTabla();
		return true;
	}//Fin CallFrmCenso

	/**
	 * Function CallMenu
	 *
	 * Esta función lista el menu de la estacion de enfermeria.
	 *
	 * @Author Rosa Maria Angel
	 * @access Public
	 * @return bool
	 */
	function CallMenu($datos)
	{
		if(!$datos)
		{
			$datos = $_REQUEST['datos'];
		}
		if(empty($_SESSION['CENSO']['EMPRESA']['ID']))
		{
			$this->empresa_id = $_SESSION['CENSO']['EMPRESA']['ID'] = $_REQUEST['datos'][empresa_id];
			$this->razon_social = $_SESSION['CENSO']['EMPRESA']['RAZONSOCIAL'] = $_REQUEST['datos'][descripcion1];
		}
		else
		{
			$this->empresa_id = $_SESSION['CENSO']['EMPRESA']['ID'];
			$this->razon_social = $_SESSION['CENSO']['EMPRESA']['RAZONSOCIAL'];
		}
		if(!$this->Menu())
		{
			return false;
		}
		return true;
	}//Fin CallMenu
	
	/**
	 * Encabezado con el nombre de la empresa seleccionada
	 */
	function FrmEncabezado()
	{
		$this->salida .= "	<table border=\"0\" width=\"500\" align=\"center\" class=\"modulo_table_list\">\n";
		$this->salida .= "		<tr>\n";
		$this->salida .= "			<td width=\"15%\" class=\"modulo_table_list_title\">EMPRESA:</td>\n";
		$this->salida .= "			<td width=\"%\" class=\"label\" style=\"text-indent:11pt\">".$this->razon_social."</td>\n";
		$this->salida .= "		</tr>\n";
		$this->salida .= "	</table><br>\n";
	}//Fin FrmEcabezado

	/**
	 * Resultado del censo
	 */
	function FrmCensoResultado()
	{
		if(!empty($_REQUEST['tercero']))
		{
				list($TerceroId,$TipoIdTercero)=explode('.-.',$_REQUEST['tercero']);
		}
		$censo = $this->GetCenso($_REQUEST['departamento'],$_REQUEST['estacion'],$TipoIdTercero,$TerceroId,$_REQUEST['plan'],true);
		if(sizeof($censo)<=0)
		{
			$this->salida .= "<div align=\"center\" class='label_error'>NO SE ENCONTRARON DATOS</div>";
			return true;
		}
		$PacientesPorIngresar = $this->GetCantidadPacientesPorIngresar();
		$CamasNormales = $this->GetCantidadCamasActivasEstaciones();
		$PacientesEnCamas =  $this->GetCantidadPacientesEnCamas();//Pacientes en camas normales y virtuales
		//$this->salida .= "<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list\">\n";
		//$this->salida .= "	<tr class=\"modulo_table_title\"><td colspan='2' height='30'>PACIENTES HOSPITALIZADOS</td></tr>\n";
			setlocale(LC_TIME,"es_ES");
			$hoy = strftime("Fecha de Consulta: %A, %e de %B de %Y. HORA: %R" , time());
			$this->salida .= "<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list\">\n";
			$this->salida .= "	<tr class=\"modulo_table_title\" align=\"center\">\n";
			$this->salida .= "		<td align =\"center\" colspan=\"2\">$hoy</td>";
			$this->salida .= "	</tr>\n";
		foreach($censo as $departamento_id=>$departamento)
		{
			$this->salida .= "<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list\">\n";
			$desc_departamento=current(current($departamento));
			$this->salida .= "	<tr class=\"modulo_table_title\" align=\"center\">\n";
			$this->salida .= "		<td align =\"center\" colspan=\"2\">{$desc_departamento['desc_departamento']}</td>";
			$this->salida .= "	</tr>\n";
			
			
			
			foreach($departamento as $estacion_id=>$estacion)
			{
				$desc_estacion=current($estacion);
				$this->salida .= "	<tr class=\"modulo_table_title\">\n";
				$this->salida .= "		<td width=\"18%\">\n";
				$this->salida .= "			<table class=\"modulo_table_title\" width=\"100%\"  border=\"1\">\n";
				$this->salida .= "				<tr class=\"label\" align=\"center\">\n";
				$this->salida .= "					<td>{$desc_estacion['desc_estacion']}</td>\n";
				$this->salida .= "				</tr>\n";
				$this->salida .= "				<tr>\n";
				$ocupacionEstacion= round(($PacientesPorIngresar[$estacion_id]+$PacientesEnCamas[$estacion_id])/$CamasNormales[$estacion_id],2)*100;
				$this->salida .= "					<td align=\"left\" class=\"modulo_list_claro\">OCUPACION $ocupacionEstacion%</td>\n";
				$this->salida .= "				</tr>\n";
				$this->salida .= "				<tr>\n";
				if(empty($PacientesPorIngresar[$estacion_id]))
					$PacientesPorIngresar[$estacion_id]=0;
				$this->salida .= "					<td align=\"left\"  class=\"modulo_list_oscuro\">$PacientesPorIngresar[$estacion_id] PACIENTES POR ING.</td>";
				$this->salida .= "				</tr>\n";
				$this->salida .= "			</table>\n";
				$this->salida .= "		</td>\n";
				$this->salida .= "		<td width=\"82%\">\n";
				$this->salida .= "			<table width=\"100%\" class=\"modulo_table_list_title\" border=\"1\">\n";
				$this->salida .= "				<tr>\n";
				$this->salida .= "					<td width=\"5%\">HAB.</td>\n";
				$this->salida .= "					<td width=\"5%\">CAMA</td>\n";
				$this->salida .= "					<td width=\"10%\">ID</td>\n";
				$this->salida .= "					<td width=\"20%\">PACIENTE</td>\n";
				$this->salida .= "					<td width=\"10%\">INGRESO</td>\n";
				$this->salida .= "					<td width=\"15%\">TIEMPO<BR>HOSP.</td>\n";
				$this->salida .= "					<td width=\"15%\">TERCERO</td>\n";
				$this->salida .= "					<td width=\"20%\">PLAN</td>\n";
				$this->salida .= "				</tr>\n";
				$i=0;
				foreach ($estacion as $key => $value)
				{
					if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
					$this->salida .= "				<tr class=\"$estilo\">\n";
					$this->salida .= "					<td align=\"center\">".$value['pieza']."</td>\n";
					$this->salida .= "					<td align=\"center\">".$value['cama']."</td>\n";
					$this->salida .= "					<td align=\"left\">".$value['paciente_id']."</td>\n";
					$this->salida .= "					<td align=\"left\">".$value['nombre_completo']."</td>\n";
					$this->salida .= "					<td align=\"center\">".date('Y-m-d g:i a',strtotime($value['fecha_ingreso']))."</td>\n";
					$this->salida .= "					<td align=\"center\">".$this->GetDiasHospitalizacion($value['fecha_ingreso'])."</td>\n";
					$this->salida .= "					<td align=\"left\">".$value['nombre_tercero']."</td>\n";
					$this->salida .= "					<td align=\"left\">".$value['plan_descripcion']."</td>\n";
					$this->salida .= "				</tr>\n";
					$i++;
				}
				$this->salida .= "				</table>";
				$this->salida .= "			</td>\n";
				$this->salida .= "		</tr>\n";
			}
		$this->salida .= "</table>";
		$this->salida .= "<br>";
		}
		
	}//Fin FrmCensoResultado
	
	/**
	 * Ejecuta el censo
	 */
	function CallFrmCensoResultado()
	{
		$this->salida .= ThemeAbrirTabla("CENSO - PACIENTES HOSPITALIZADOS");
		$this->FrmEncabezado();
		$this->FrmCenso();
		$this->salida .= "<br>";
		$this->FrmCensoResultado();
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
	
	/**
	 * Ejecuta el listado de pacientes hospitalizados
	 */
	function CallFrmListadoPacientesHospitalizados()
	{
		$this->salida .= ThemeAbrirTabla("CENSO - LISTADO DE PACIENTES HOSPITALIZADOS");
		$this->FrmEncabezado();
		$this->FrmListadoPacientesHospitalizados();
		$this->salida .= "<br>";
		$this->salida .= "<div align=\"center\">\n";
		$accion=ModuloGetUrl('app','Censo','user','CallMenu');
		$this->salida .= "	<form name=\"frmVolver\" action=\"$accion\" method=\"post\">\n";
		$this->salida .= "		<input type=\"submit\" class=\"input-submit\" value=\"VOLVER\">\n";
		$this->salida .= "	</form>\n";
		$reporte = new GetReports();
		$mostrar =  $reporte->GetJavaReport('app','Censo','Censo_Pacientes',array('empresa_id'=>$this->empresa_id));
		$nombreFuncion =  $reporte->GetJavaFunction();
		$this->salida .= $mostrar;
		$this->salida .= "	<a href=\"javascript:$nombreFuncion\"><img border=\"0\" src=\"".GetThemePath()."/images/imprimir.png\">Imprimir</a>";
		$this->salida .= "</div>\n";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}//Fin CallFrmListadoPacientesHospitalizados
  
  /**
   * Ejecuta el listado de pacientes en urgencias
   */
  function CallFrmListadoPacientesObsEnUrgencias()
  {
    $this->salida .= ThemeAbrirTabla("CENSO - LISTADO DE PACIENTES EN OBSERVACION DE URGENCIAS");
    $this->FrmEncabezado();
    $this->FrmListadoPacientesObsEnUrgencias();
    $this->salida .= "<br>";
    $this->salida .= "<div align=\"center\">\n";
    $accion=ModuloGetUrl('app','Censo','user','CallMenu');
    $this->salida .= "  <form name=\"frmVolver\" action=\"$accion\" method=\"post\">\n";
    $this->salida .= "    <input type=\"submit\" class=\"input-submit\" value=\"VOLVER\">\n";
    $this->salida .= "  </form>\n";
    $reporte = new GetReports();
    $mostrar =  $reporte->GetJavaReport('app','Censo','Censo_Pacientes_ObsUrgencias',array('empresa_id'=>$this->empresa_id));
    $nombreFuncion =  $reporte->GetJavaFunction();
    $this->salida .= $mostrar;
    $this->salida .= "  <a href=\"javascript:$nombreFuncion\"><img border=\"0\" src=\"".GetThemePath()."/images/imprimir.png\">Imprimir</a>";
    $this->salida .= "</div>\n";
    $this->salida .= ThemeCerrarTabla();
    return true;
  }//Fin CallFrmListadoPacientesEnUrgencias
  
  
	/**
   * Ejecuta el listado de pacientes en urgencias
   */
  function CallFrmListadoPacientesEnConsultaUrgencias()
  {
    $this->salida .= ThemeAbrirTabla("CENSO - LISTADO DE PACIENTES EN CONSULTA DE URGENCIAS");
    $this->FrmEncabezado();
    $this->FrmListadoPacientesEnConsultaUrgencias();
    $this->salida .= "<br>";
    $this->salida .= "<div align=\"center\">\n";
    $accion=ModuloGetUrl('app','Censo','user','CallMenu');
    $this->salida .= "  <form name=\"frmVolver\" action=\"$accion\" method=\"post\">\n";
    $this->salida .= "    <input type=\"submit\" class=\"input-submit\" value=\"VOLVER\">\n";
    $this->salida .= "  </form>\n";
    $reporte = new GetReports();
    $mostrar =  $reporte->GetJavaReport('app','Censo','Censo_Pacientes_ConsultaUrgencias',array('empresa_id'=>$this->empresa_id));
    $nombreFuncion =  $reporte->GetJavaFunction();
    $this->salida .= $mostrar;
    $this->salida .= "  <a href=\"javascript:$nombreFuncion\"><img border=\"0\" src=\"".GetThemePath()."/images/imprimir.png\">Imprimir</a>";
    $this->salida .= "</div>\n";
    $this->salida .= ThemeCerrarTabla();
    return true;
  }//Fin CallFrmListadoPacientesEnConsultaUrgencias
  
	/**
	 * Imprime un listado con todos los pacientes hospitalizados
	 */
	function FrmListadoPacientesHospitalizados()
	{
		$censo=$this->GetCenso();//Trae todos los pacientes hospitalizados ordenados alfabeticamente
		$this->salida .= "			<table width=\"100%\" class=\"modulo_table_list_title\" border=\"1\">\n";
		$this->salida .= "				<tr>\n";
		$this->salida .= "					<td>PACIENTE</td>\n";
		$this->salida .= "					<td>ID</td>\n";
		$this->salida .= "					<td>HABITACION</td>\n";
		$this->salida .= "					<td>CAMA</td>\n";
		$this->salida .= "					<td>ESTACION</td>\n";
		$this->salida .= "					<td>INGRESO11</td>\n";
		$this->salida .= "					<td>TIEMPO<BR>HOSP.</td>\n";
		$this->salida .= "					<td>TERCERO</td>\n";
		$this->salida .= "					<td>PLAN</td>\n";
		$this->salida .= "					<td>DIAGNÓSTICO</td>\n";
		$this->salida .= "				</tr>\n";
		$i=0;
		foreach ($censo as $key => $value)
		{
			if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
			
			$diagnostico = $this->ObtenerDiagnostico($value['ingreso']); 
			
			$this->salida .= "				<tr class=\"$estilo\">\n";
			$this->salida .= "					<td align=\"left\">".$value['nombre_completo']."</td>\n";
			$this->salida .= "					<td align=\"left\">".$value['paciente_id']."</td>\n";
			$this->salida .= "					<td align=\"center\">".$value['pieza']."</td>\n";
			$this->salida .= "					<td align=\"center\">".$value['cama']."</td>\n";
			$this->salida .= "					<td align=\"left\">".$value['desc_estacion']."</td>\n";
			$this->salida .= "					<td align=\"center\">".date('Y-m-d g:i a',strtotime($value['fecha_ingreso']))."</td>\n";
			$this->salida .= "					<td align=\"center\">".$this->GetDiasHospitalizacion($value['fecha_ingreso'])."</td>\n";
			$this->salida .= "					<td align=\"left\">".$value['nombre_tercero']."</td>\n";
			$this->salida .= "					<td align=\"left\">".$value['plan_descripcion']."</td>\n";
			$this->salida .= "					<td align=\"left\">".$diagnostico."</td>\n";
			$this->salida .= "				</tr>\n";
			$i++;
		}
		$this->salida .= "				</table>";
	}//Fin FrmListadoPacientesHospitalizados
  
  /**
   * Imprime un listado con todos los pacientes en urgencias
   */
  function FrmListadoPacientesObsEnUrgencias()
  {
    $censo=$this->GetCensoPacientesObsUrgencias();//Trae todos los pacientes hospitalizados ordenados alfabeticamente
    $this->salida .= "      <table width=\"100%\" class=\"modulo_table_list_title\" border=\"1\">\n";
    foreach($censo as $estacion_id => $vector){
      foreach($vector as $nom_estacion => $vector1){
        $this->salida .= "        <tr><td align=\"left\" colspan=\"10\">ESTACION:&nbsp;&nbsp;&nbsp;&nbsp;$nom_estacion</td></td>\n";
        $this->salida .= "        <tr>\n";
        $this->salida .= "          <td>No.</td>\n";
        $this->salida .= "          <td>PACIENTE</td>\n";
        $this->salida .= "          <td>ID</td>\n";
        $this->salida .= "          <td>HABITACION</td>\n";
        $this->salida .= "          <td>CAMA</td>\n";        
        $this->salida .= "          <td>INGRESO</td>\n";
        $this->salida .= "          <td>TIEMPO<BR>HOSP.</td>\n";
        $this->salida .= "          <td>TERCERO</td>\n";
        $this->salida .= "          <td>PLAN</td>\n";
        //$this->salida .= "          <td>DIAGNÓSTICO</td>\n";
        $this->salida .= "        </tr>\n";
        $i=1;
        foreach($vector1 as $ingreso => $value){
          if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";      
          //$diagnostico = $this->ObtenerDiagnostico($value['ingreso']);       
          $this->salida .= "        <tr class=\"$estilo\">\n";
          $this->salida .= "          <td align=\"left\">$i</td>\n";
          $this->salida .= "          <td align=\"left\">".$value['nombre_completo']."</td>\n";
          $this->salida .= "          <td align=\"left\">".$value['tipo_id_paciente']." ".$value['paciente_id']."</td>\n";
          $this->salida .= "          <td align=\"center\">".$value['pieza']."</td>\n";
          $this->salida .= "          <td align=\"center\">".$value['cama']."</td>\n";          
          $this->salida .= "          <td align=\"center\">".date('Y-m-d g:i a',strtotime($value['fecha_ingreso']))."</td>\n";
          $this->salida .= "          <td align=\"center\">".$this->GetDiasHospitalizacion($value['fecha_ingreso'])."</td>\n";
          $this->salida .= "          <td align=\"left\">".$value['nombre_tercero']."</td>\n";
          $this->salida .= "          <td align=\"left\">".$value['plan_descripcion']."</td>\n";
          //$this->salida .= "          <td align=\"left\">".$diagnostico."</td>\n";
          $this->salida .= "        </tr>\n";
          $i++;   
        }
      }
    }     
    $this->salida .= "        </table>";
  }//Fin FrmListadoPacientesEnUrgencias
	
  /**
   * Imprime un listado con todos los pacientes en urgencias
   */
  function FrmListadoPacientesEnConsultaUrgencias()
  {
    $censo=$this->GetCensoPacientesConsultaUrgencias();//Trae todos los pacientes hospitalizados ordenados alfabeticamente
    $this->salida .= "      <table width=\"100%\" class=\"modulo_table_list_title\" border=\"1\">\n";    
    $this->salida .= "        <tr>\n";
    $this->salida .= "          <td>No.</td>\n";
    $this->salida .= "          <td>PACIENTE</td>\n";
    $this->salida .= "          <td>ID</td>\n";
    //$this->salida .= "          <td>HABITACION</td>\n";
    //$this->salida .= "          <td>CAMA</td>\n";        
    $this->salida .= "          <td>INGRESO</td>\n";
    $this->salida .= "          <td>TIEMPO<BR>HOSP.</td>\n";
    $this->salida .= "          <td>TERCERO</td>\n";
    $this->salida .= "          <td>PLAN</td>\n";
    //$this->salida .= "          <td>DIAGNÓSTICO</td>\n";
    $this->salida .= "        </tr>\n";
    $i=1;
    foreach($censo as $cont => $value){
      if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";      
      //$diagnostico = $this->ObtenerDiagnostico($value['ingreso']);       
      $this->salida .= "        <tr class=\"$estilo\">\n";
      $this->salida .= "          <td align=\"left\">$i</td>\n";
      $this->salida .= "          <td align=\"left\">".$value['nombre_completo']."</td>\n";
      $this->salida .= "          <td align=\"left\">".$value['tipo_id_paciente']." ".$value['paciente_id']."</td>\n";
      //$this->salida .= "          <td align=\"center\">".$value['pieza']."</td>\n";
      //$this->salida .= "          <td align=\"center\">".$value['cama']."</td>\n";          
      $this->salida .= "          <td align=\"center\">".date('Y-m-d g:i a',strtotime($value['fecha_ingreso']))."</td>\n";
      $this->salida .= "          <td align=\"center\">".$this->GetDiasHospitalizacion($value['fecha_ingreso'])."</td>\n";
      $this->salida .= "          <td align=\"left\">".$value['nombre_tercero']."</td>\n";
      $this->salida .= "          <td align=\"left\">".$value['plan_descripcion']."</td>\n";
      //$this->salida .= "          <td align=\"left\">".$diagnostico."</td>\n";
      $this->salida .= "        </tr>\n";
      $i++;   
    }        
    $this->salida .= "        </table>";
  }//Fin FrmListadoPacientesEnUrgencias
  
	/**
	 * Listado de pacientes por ingresar
	 */
	function FrmListadoPacientesPorIngresar()
	{
		$pacientes = $this->GetPacientesPorIngresar();
		$this->salida .= "			<table width=\"50%\" align=\"center\" class=\"modulo_table_list_title\" border=\"1\">\n";
		$this->salida .= "				<tr>\n";
		$this->salida .= "					<td>PACIENTE</td>\n";
		$this->salida .= "					<td>ID</td>\n";
		$this->salida .= "					<td>ESTACION</td>\n";
		$this->salida .= "				</tr>\n";
		$i=0;
		foreach ($pacientes as $key => $value)
		{
			if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
			$this->salida .= "				<tr class=\"$estilo\">\n";
			$this->salida .= "					<td align=\"left\">".$value['nombre_completo']."</td>\n";
			$this->salida .= "					<td align=\"left\">".$value['paciente_id']."</td>\n";
			$this->salida .= "					<td align=\"left\">".$value['desc_estacion']."</td>\n";
			$this->salida .= "				</tr>\n";
			$i++;
		}
		$this->salida .= "				</table>";
		return true;
	}//Fin FrmListadoPacientesPorIngresar
	
	/**
	 * Muestra el listado de pacientes por ingresar
	 */
	function CallFrmListadoPacientesPorIngresar()
	{
		$this->salida .= ThemeAbrirTabla('CENSO - LISTADO DE PACIENTES POR INGRESAR');
		$this->FrmEncabezado();
		$this->FrmListadoPacientesPorIngresar();
		$this->salida .= "<br>";
		$accion=ModuloGetUrl('app','Censo','user','CallMenu');
		$this->salida .= "<div align=\"center\">\n";
		$this->salida .= "	<form name=\"frmVolver\" action=\"$accion\" method=\"post\">\n";
		$this->salida .= "		<input type=\"submit\" class=\"input-submit\" value=\"VOLVER\">\n";
		$this->salida .= "	</form>\n";
		$reporte = new GetReports();
		$mostrar =  $reporte->GetJavaReport('app','Censo','Censo_PacientesPorIngresar',array('empresa_id'=>$this->empresa_id));
		$nombreFuncion =  $reporte->GetJavaFunction();
		$this->salida .= $mostrar;
		$this->salida .= "	<a href=\"javascript:$nombreFuncion\"><img border=\"0\" src=\"".GetThemePath()."/images/imprimir.png\">Imprimir</a>";
		$this->salida .= "</div>\n";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}//Fin CallFrmListadoPacientesPorIngresar
	
	/**
	 * Listado de programacion Qx
	 * 
	 * @param date fecha 
	 */
	function FrmListaProgramacionQx($fecha)
	{
		if(empty($fecha))
			$fecha = date('Y-m-d');
		$programacionQxEmpresa = $this->GetProgramacionQx($fecha);
		foreach($programacionQxEmpresa  as $departamento_id => $programacionQxDpto)
		{
			$this->salida .= "<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list\">\n";
			$departamento_descripcion=current(current(current($programacionQxDpto)));
			$this->salida .= "	<tr class=\"modulo_table_title\" align=\"center\">\n";
			$this->salida .= "		<td align =\"center\" colspan=\"2\">{$departamento_descripcion['departamento_descripcion']} ".strftime ( "%A, %d de %B de %Y", strtotime($fecha) )." </td>";
			$this->salida .= "	</tr>\n";
			foreach($programacionQxDpto as $quirofano_id => $programacionQxQuirofano)
			{
				$quirofano_descripcion=current(current($programacionQxQuirofano));
				$this->salida .= "	<tr class=\"modulo_table_title\">\n";
				$this->salida .= "		<td>\n";
				$this->salida .= "			<table class=\"modulo_table_title\" width=\"100%\"  border=\"1\">\n";
				$this->salida .= "				<tr class=\"label\" align=\"center\">\n";
				$this->salida .= "					<td>{$quirofano_descripcion['quirofano']}</td>\n";
				$this->salida .= "				</tr>\n";
				$this->salida .= "			</table>\n";
				$this->salida .= "		</td>\n";
				$this->salida .= "		<td>\n";
				$this->salida .= "			<table width=\"100%\" class=\"modulo_table_list_title\" border=\"1\">\n";
				$this->salida .= "				<tr>\n";
				$this->salida .= "					<td width=\"7%\">INICIO</td>\n";
				$this->salida .= "					<td width=\"7%\">FIN</td>\n";
				$this->salida .= "					<td width=\"23%\">PACIENTE</td>\n";
				$this->salida .= "					<td width=\"23%\">PROCEDIMIENTO</td>\n";
				$this->salida .= "					<td width=\"20%\">CIRUJANO</td>\n";
				$this->salida .= "					<td width=\"20%\">PLAN</td>\n";
          		$this->salida .= "					<td width=\"20%\">OBSERVACIONES</td>\n";          
				$this->salida .= "				</tr>\n";
				foreach($programacionQxQuirofano as $programacion_id => $programacionQx)
				{
					$value = current($programacionQx);//obtiene el primer procedimiento qx
					if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
					$rowspan = sizeof($programacionQx);
					$cargo = $value['cargo']." - ".$value['cargo_descripcion'];
					if(strlen($cargo)>50)
						$adicionar = "...";
					else
						$adicionar = "";
					$cargo = substr($cargo,0,50).$adicionar;
					$this->salida .= "				<tr class=\"$estilo\">\n";
					$this->salida .= "					<td rowspan =\"$rowspan\" align=\"center\">".date('h:i a',strtotime($value['hora_inicio'])+60)."</td>\n";
					$this->salida .= "					<td rowspan =\"$rowspan\" align=\"center\">".date('h:i a',strtotime($value['hora_fin'])+60)."</td>\n";
					$this->salida .= "					<td rowspan =\"$rowspan\" align=\"left\">".$value['paciente']."</td>\n";
					$this->salida .= "					<td align=\"left\">".$cargo."</td>\n";
					$this->salida .= "					<td align=\"left\">".$value['cirujano']."</td>\n";
					$this->salida .= "					<td align=\"left\">".$value['plan_descripcion']."</td>\n";
					$this->salida .= "					<td align=\"left\"> ".$value['observaciones']."</td>\n";
					$this->salida .= "				</tr>\n";
					
                         unset($programacionQx[$value['cargo']]);
					foreach($programacionQx as $procedimeinto_id => $procedimientoQx)
					{
						$cargo = $procedimientoQx['cargo']." - ".$procedimientoQx['cargo_descripcion'];
						if(strlen($cargo)>50)
							$adicionar = "...";
						else
							$adicionar = "";
						$cargo = substr($cargo,0,50).$adicionar;
						$this->salida .= "				<tr class=\"$estilo\">\n";
						$this->salida .= "					<td align=\"left\">".$cargo."</td>\n";
						$this->salida .= "					<td align=\"left\">".$procedimientoQx['cirujano']."</td>\n";
						$this->salida .= "					<td align=\"left\">".$procedimientoQx['plan_descripcion']."</td>\n";
						$this->salida .= "					<td align=\"left\">".$procedimientoQx['observaciones']."</td>\n";
						$this->salida .= "				</tr>\n";
					}
					$i++;
				}
                    /*$this->salida .= "				<tr>\n";
                    $this->salida .= "					<td class=\"modulo_table_list_title\" align=\"left\" colspan=\"2\">OBSERVACIONES</td>\n";
                    $this->salida .= "					<td class=\"$estilo\" align=\"left\" colspan=\"4\">".$value['observaciones']."</td>\n";
                    $this->salida .= "				</tr>\n";*/
                    $this->salida .= "			</table>\n";
				$this->salida .= "		</td>\n";
				$this->salida .= "	</tr>\n";
			}
			$this->salida .= "</table>\n";
			$this->salida .= "<br>\n";
		}
		return true;
	}//Fin FrmListaProgramacionQx
	
	/**
	 * Muestra el listado de programacino Qx
	 */
	function CallFrmListadoProgramacionQx()
	{
		$this->salida .= ThemeAbrirTabla("CENSO - LISTADO PROGRAMACIÓN Qx");
		$this->FrmEncabezado();
		if(!empty($_REQUEST['fecha']))
			$DiaActual = $_REQUEST['fecha'];
		else
			$DiaActual = date('Y-m-d');
		$DiaAnterior = date('Y-m-d',strtotime($DiaActual)-86400);//86400 segundos equivalen a un dia
		$DiaSiguiente = date('Y-m-d',strtotime($DiaActual)+86400);//86400 segundos equivalen a un dia
		$accion = ModuloGetUrl('app','Censo','user','CallFrmListadoProgramacionQx',array('fecha'=>$DiaAnterior));
		$LinkDiaAnterior = "<a href=\"$accion\" title=\"Programación Qx día anterior\"><img src=\"".GetThemePath()."/images/anterior.png\" border=\"0\"></a>";
		$accion = ModuloGetUrl('app','Censo','user','CallFrmListadoProgramacionQx',array('fecha'=>$DiaSiguiente));
		$LinkDiaSiguiente = "<a href=\"$accion\" title=\"Día siguiente\"><img src=\"".GetThemePath()."/images/siguiente.png\" border=\"0\"></a>";
		$this->salida .= "<table align=\"center\" width=\"60%\"><tr align=\"center\"><td>$LinkDiaAnterior</td><td>$LinkDiaSiguiente</td></tr></table>";
		$this->FrmListaProgramacionQx($DiaActual);
		$accion=ModuloGetUrl('app','Censo','user','CallMenu');
		$this->salida .= "<div align=\"center\">\n";
		$this->salida .= "	<form name=\"frmVolver\" action=\"$accion\" method=\"post\">\n";
		$this->salida .= "		<input type=\"submit\" class=\"input-submit\" value=\"VOLVER\">\n";
		$this->salida .= "	</form>\n";
		$reporte = new GetReports();
		$mostrar =  $reporte->GetJavaReport('app','Censo','Censo_ProgramacionQx',array('empresa_id'=>$this->empresa_id, 'fecha'=>$DiaActual));
		$nombreFuncion =  $reporte->GetJavaFunction();
		$this->salida .= $mostrar;
		$this->salida .= "	<a href=\"javascript:$nombreFuncion\"><img border=\"0\" src=\"".GetThemePath()."/images/imprimir.png\"> Imprimir</a>";
		$this->salida .= "</div>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}//Fin CallFrmListadoProgramacionQx
	
	/**
	 * Método de pruebas del modulo
	 */
	function Pruebas()
	{
		return true;
	}//Fin Pruebas
}//fin class
?>
