<?

 /**
 * $Id: app_EstacionEnfermeria_userclasses_HTML.php,v 1.26 2006/02/20 20:55:10 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo de Estacion de Enfermeria modulo para la atencion del paciente 
 */



/**
*		class app_EstacionEnfermeria_userclasses_HTML
*
*		Clase que maneja todas las funciones de vistas y consultas a la base de datos
*		relacionadas a la estaci&oacute;n de Enfermer&iacute;a
*		ubicacion => app_modules/EstacionEnfermeria/userclasses/app_EstacionEnfermeria_userclasses_HTML.php
*		fecha creaci&oacute;n => 04/05/2004 10:35 am
*
*		@Author jairo Duvan Diaz Martinez
*		@version =>
*		@package SIIS
*/
class app_EstacionEnfermeria_userclasses_HTML extends app_EstacionEnfermeria_user
{

	/**
	*		app_EstacionEnfermeria_userclasses_HTML()
	*
	*		constructor
	*
	*		@Author jairo Duvan Diaz Martinez.
	*		@access Private
	*		@return boolean
	*/
	function app_EstacionEnfermeria_userclasses_HTML()
	{
	  $this->app_EstacionEnfermeria_user(); //Constructor del padre 'modulo'
		$this->salida = "";
		return true;
	}

	/**
	*		FrmLogueoEstacion
	*
	*		@Author Arley Velasquez Castillo
	*		@access Private
	*		@param array
	*		@return bool
	*/
	function FrmLogueoEstacion($modulo,$metodo)
	{
		$Datos=$this->GetLogueoEstacion($modulo,$metodo);
		if (!is_array($Datos)){
			return false;
		}
		$this->salida .= gui_theme_menu_acceso("SELECCION DE ESTACI&Oacute;N DE ENFERMERIA",$Datos[0],$Datos[1],$Datos[2]);
		return true;
	}

	/**
	*		Menu
	*
	*		@Author Jairo Duvan Diaz Martinez
	*		@access Private
	*		@param array
	*		@return string
	*/
	function Menu($datos,$graphic)
	{
          $refresh = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array('estacion'=>$datos));
          $this->salida="<script language=javascript>\n";
          $this->salida.="function load_page()\n";
          $this->salida.="{\n";
          $this->salida.="location.reload();\n";
          $this->salida.="}\n";
          $this->salida.="</script>\n";


          $this->salida.="<body onload=compt=setTimeout('load_page();',300000)>\n";
          unset($_SESSION['ESTACION']['VECTOR_SOL']);//var de session que tiene el vector de solicitud de medicamentos.
          unset($_SESSION['ESTACION']['VECTOR_DESP']);//var de session que tiene el vector de despacho de medicamentos.
          unset($_SESSION['ESTACION']['VECTOR_DEV']); //var de session q tiene el vector de devoluciones de medicamentos.
          unset($_SESSION['ESTACION']['VECTOR_DESP_INS']);//var de session que tiene el vector de despacho de medicamentos.
          unset($_SESSION['ESTACION']['VECTOR_SOL_INS']);//var de session que tiene el vector de solicitud de medicamentos.
          unset($_SESSION['ESTACION']['VECTOR_DEV_INS']); //var de session q tiene el vector de devoluciones de medicamentos.

          //esta variable de session la usamos para trabajar esta forma indiferente de
          //q sea medicamentos o insumos,para llamar frmshowbodega
          unset($_SESSION['ESTACION_MEDICAMENTOS']['ACTION']);
          
          if(!$_SESSION['HISTORIACLINICA']['DATOS']['ESTACION'])
          {
               $_SESSION['ESTACION_ENFERMERIA']['NOM']=$datos['descripcion5'];
               $_SESSION['ESTACION_ENFERMERIA']['EMP']=$datos['descripcion1'];
               $_SESSION['ESTACION_ENFERMERIA']['hc_modulo_enfermera']=$datos['hc_modulo_enfermera'];
          }
          else
          {
               $datos=$_SESSION['HISTORIACLINICA']['DATOS']['ESTACION'];
          }
          $this->salida .= ThemeAbrirTabla("MEN&Uacute; ESTACI&Oacute;N DE ENFERMERIA - [ ".$datos['descripcion5']." ]");
          $this->salida .= "<center>\n";
          $this->salida .= "				<table class='modulo_table_title' border='0' width='100%'>\n";
          $this->salida .= "					<tr class='modulo_table_title'>\n";
          $this->salida .= "						<td>Empresa</td>\n";
          $this->salida .= "						<td>Centro Utilidad</td>\n";
          $this->salida .= "						<td>Unidad Funcional</td>\n";
          $this->salida .= "						<td>Departamento</td>\n";
          $this->salida .= "					</tr>\n";
          $this->salida .= "					<tr class='modulo_list_oscuro'>\n";
          $this->salida .= "						<td>".$datos['descripcion1']."</td>\n";
          $this->salida .= "						<td>".$datos['descripcion2']."</td>\n";
          $this->salida .= "						<td>".$datos['descripcion3']."</td>\n";
          $this->salida .= "						<td>".$datos['descripcion4']."</td>\n";
          $this->salida .= "					</tr>\n";
          $this->salida .= "				</table>\n";

          GLOBAL $ADODB_FETCH_MODE;
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		
          //vamos a utilizar estas dos funciones de medicamentos para insumos tambien.
		$PacientesConOrdenes = $this->GetPacientesConMedicamentosPorDesp($datos);
          
		if(empty($PacientesConOrdenes[0]) AND empty($PacientesConOrdenes[1]))
		{
               $enlacepend = "Confirmacion Despacho: Insumos y Medicamentos Pendientes";
			$imgpend='';
			$sw=0;
		}
		
          elseif($PacientesConOrdenes[0]==1 OR $PacientesConOrdenes[1]==1 )
		{
			$enlacepend = "<a href=\"".ModuloGetURL('app','EstacionE_Medicamentos','user','FrmShowBodega',array("datos_estacion"=>$datos,'switche'=>'despacho')) ."\" target=\"Contenido\">Confirmacion Despacho: Insumos y Medicamentos Pendientes</a>";
			$imgpend = "<img src=\"".GetThemePath()."/images/EstacionEnfermeria/alarma.png\" border=0 width=12 heigth=12>";
		}
		
		unset($PacientesConOrdenes);
		$PacientesConOrdenes = $this->GetPacientesConMedicamentosPorSolicitar($datos);
		if(empty($PacientesConOrdenes[0]) AND empty($PacientesConOrdenes[1])){
			$enlace = "Listado Solicitudes Realizadas: Insumos Y Medicamentos";
		}
		elseif($PacientesConOrdenes[0]==1 OR $PacientesConOrdenes[1]==1){
			$sw=1;
			$enlace = "<a href=\"".ModuloGetURL('app','EstacionE_Medicamentos','user','FrmShowBodega',array("datos_estacion"=>$datos,'switche'=>'recibir')) ."\" target=\"Contenido\">Listado Solicitudes Realizadas: Insumos y Medicamentos</a>";
			$img = "<img src=\"".GetThemePath()."/images/EstacionEnfermeria/alarma.png\" border=0 width=12 heigth=12>";
		}

		
		//Solicitudes de suministro por estacion.
          $sol_solicitud = "<a href=\"".ModuloGetURL('app','EstacionE_Medicamentos','user','FrmShowBodega',array("datos_estacion"=>$datos,'switche'=>'Solicitar_sol')) ."\" target=\"Contenido\">Realizar Solicitudes de Suministro x Estacion</a>";
          $ConSolicitudes = $this->BusquedaSolicitudes_Estacion($datos);
		if($ConSolicitudes >= 1){
			$sw=1;
			$con_solicitud = "<a href=\"".ModuloGetURL('app','EstacionE_Medicamentos','user','FrmShowBodega',array("datos_estacion"=>$datos,'switche'=>'Confirmar_sol')) ."\" target=\"Contenido\">Confirmar Solicitudes de Suministro x Estacion</a>";
			$img2 = "<img src=\"".GetThemePath()."/images/EstacionEnfermeria/alarma.png\" border=0 width=12 heigth=12>";
		}else
          {
          	$con_solicitud = "Confirmar Solicitudes de Suministro x Estacion";          
          }

		$this->salida .= "<table align='center' border='0' width='100%'>\n";
		$this->salida .= "<tr><td>\n";
		$this->ReturnMetodoExterno('app','EstacionE_ControlPacientes','user','CallListRevisionPorSistemas',array("estacion"=>$datos))."\" \n";

		$this->salida .= "</td></tr>\n";
		$this->salida .= "</table>\n";

		$arr_ingresos=$_SESSION['ESTACION']['VECT'];
		$conteo_transf=$this->GetNoControlTransfusiones($arr_ingresos,24,$datos['departamento']);

		//estas imagenes de
		$imgegreso = "<img src=\"".GetThemePath()."/images/egreso.png\" border=0 title='Pacientes que estan pendientes por salir'  width=14 heigth=14><label class='label_mark'>Pendiente. Egreso</label>&nbsp;";
		$imgsalida = "<img src=\"".GetThemePath()."/images/egresook.png\" border=0  title='Pacientes listos para salir'  width=14 heigth=14><label class='label_mark'>Egreso Efectuado</label>&nbsp;";
		$imgingreso = "<img src=\"".GetThemePath()."/images/ingresar.png\" border=0 title='Pacientes para ingresar a la estación' width=14 heigth=14><label class='label_mark'>Ingresar Paciente</label>&nbsp;";
		$imghospi = "<img src=\"".GetThemePath()."/images/honorarios.png\" border=0 title='Pacientes hospitalizados' width=14 heigth=14><label class='label_mark'>Hospitalizados</label>&nbsp;";
		$imgcons = "<img src=\"".GetThemePath()."/images/consulta_ur.png\" border=0 title='Pacientes en consulta' width=14 heigth=14><label class='label_mark'>Consulta Urgencias</label>&nbsp;";
		if(empty($_SESSION['CONTEO']['GLUCO'])){$conteo_gluco=0;}else{$conteo_gluco=$_SESSION['CONTEO']['GLUCO'];}
		if(empty($_SESSION['CONTEO']['NEURO'])){$conteo_neuro=0;}else{$conteo_neuro=$_SESSION['CONTEO']['NEURO'];}


		$conto_liq=$_SESSION['ESTACION_ENF']['CONTEO']['HOSP'] + $_SESSION['ESTACION_ENF']['CONTEO']['EGRESO']+$_SESSION['ESTACION_ENF']['CONTEO']['CONSULTA']+$_SESSION['ESTACION_ENF']['CONTEO']['INGRESO'];

		/*if($_SESSION['ESTACION_ENF']['CONTEO']['HOSP']< 1 AND $_SESSION['ESTACION_ENF']['CONTEO']['CONSULTA']< 1 AND $_SESSION['ESTACION_ENF']['CONTEO']['INGRESO'] < 1)
		{
			$enlaceAgendaControles = "Agenda de Controles Programados";
			$informacion='verdadero';
          	$enlaceLiquidos = "Liquidos";
		}
		else
		{
			$informacion='';
			$data = $this->QControlesEstacion($datos['departamento'],$datos['estacion_id'],"");
			if (!empty($data))
			{*/

				$hora_inicio_turno=ModuloGetVar('app','EstacionEnfermeria','HoraInicioTurnoControles');
				$rango_turno=ModuloGetVar('app','EstacionEnfermeria','RangoVerTurnosControles');

				list($hh,$mm, $ss) = explode(" ",$hora_inicio_turno);
				$NextDay = date("Y-m-d H:i:s", mktime(($hh+($rango_turno)), ($mm-1), $ss, date("m"),(date("d")),date("Y")));
				$vectorAgenda = $this->GetAgendaPorHoras($datos['estacion_id'],date("Y-m-d $hora_inicio_turno"),$NextDay);

				/*if($vectorAgenda != "ShowMensaje" OR !$vectorAgenda)
				{*/
					$enlaceAgendaControles = "<a href=\"".ModuloGetURL('app','EstacionE_ControlPacientes','user','CallAgendaControlesXhoras',array("estacion"=>$datos))."\" target=\"Contenido\">Agenda de Controles Programados</a>";
					$imgAgendaControles = "<img src=\"".GetThemePath()."/images/EstacionEnfermeria/alarma.png\" border=0 width=12 heigth=12>";
				//}
			/*	else
				{
					$enlaceAgendaControles = "Agenda de Controles Programados";

				}
			}
			else
			{
				//ojo mas abajo intent&eacute; poner el codigo para que este link se active cuando se necesite pero no funcion&oacute;
				$enlaceAgendaControles = "Agenda de Controles Programados";
			}
		}*/


		if($_SESSION['ESTACION_ENF']['CONTEO']['HOSP']< 1 AND $_SESSION['ESTACION_ENF']['CONTEO']['CONSULTA']< 1 AND $_SESSION['ESTACION_ENF']['CONTEO']['INGRESO'] < 1)
		{
			$enlaceLiquidos = "Liquidos";
			//$enlaceDietas='Dietas';
		}
		else
		{
			$enlaceLiquidos = "<a href=\"".ModuloGetURL('app','EstacionE_ControlPacientes','user','CallControlesPacientes',array("control_id"=>6,"control_descripcion"=>"CONTROL LIQUIDOS","estacion"=>$datos)) ."\" target=\"Contenido\">Liquidos</a>\n";
			$imgLiquidos = "<img src=\"".GetThemePath()."/images/EstacionEnfermeria/editar.gif\" border=0 width=12 heigth=12>";
		}
          
          //$enlaceDietas = "<a href=\"".ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmPrescripcionDietas',array("datos_estacion"=>$datos))."\" target=\"Contenido\">Dietas</a>";
          $enlaceDietas = "<a href=\"".ModuloGetURL('app','EE_SolicitudDietas','user','FrmPanelEstacion',array("datos_estacion"=>$datos))."\" target=\"Contenido\">Dietas</a>";
          $imgDietas = "<img src=\"".GetThemePath()."/images/EstacionEnfermeria/recetaDietas.gif\" align='middle' border=0 width=12 heigth=12>";


//	if($informacion != "verdadero")
//	{
		$arr_conteo=array($_SESSION['ESTACION_ENF']['CONTEO']['INGRESO'],$_SESSION['ESTACION_ENF']['CONTEO']['HOSP'],$_SESSION['ESTACION_ENF']['CONTEO']['CONSULTA'],$_SESSION['ESTACION_ENF']['CONTEO']['EGRESO']);
	/*	if($_SESSION['IMAGEN']['CONTEO'] < ($_SESSION['ESTACION_ENF']['CONTEO']['INGRESO']+ $_SESSION['ESTACION_ENF']['CONTEO']['HOSP']+ $_SESSION['ESTACION_ENF']['CONTEO']['CONSULTA']))
		{
			IncludeLib("jpgraph/Barras_Estacion"); //cargamos la libreria de presion diastolica.
			$_SESSION['IMAGEN']['CONTEO'] = $_SESSION['ESTACION_ENF']['CONTEO']['INGRESO']+ $_SESSION['ESTACION_ENF']['CONTEO']['HOSP']+ $_SESSION['ESTACION_ENF']['CONTEO']['CONSULTA'] + $_SESSION['ESTACION_ENF']['CONTEO']['EGRESO'];
			if($_SESSION['IMAGEN']['CONTEO'] < 50)
			{
				$graphic=GraficarBarras($arr_conteo);
				$grp="<img align='center' src=$graphic border='0'>"; //aqui se imprime para mostrar el grafico
				$_SESSION['IMAGEN']['PATH']=$graphic;//guardamos la direccion de la imagen.
			}
			else
			{
				$a=ModuloGetURL('app','EstacionEnfermeria','user','GenerarGraphica',array('estacion'=>$datos));
				$grp="<a href='$a'><sub>GENERAR GRAFICO</sub></a>";
				$grp="<sub>GENERAR GRAFICO</sub>";
			}
		}
		else
		{
			if(!$graphic)
			$graphic=$_SESSION['IMAGEN']['PATH'];//guardamos la direccion de la imagen.
			$grp="<img align='center' src=$graphic border='0'>"; //aqui se imprime para mostrar el grafico
			$_SESSION['IMAGEN']['CONTEO']= $_SESSION['ESTACION_ENF']['CONTEO']['INGRESO']+ $_SESSION['ESTACION_ENF']['CONTEO']['HOSP']+ $_SESSION['ESTACION_ENF']['CONTEO']['CONSULTA']+ $_SESSION['ESTACION_ENF']['CONTEO']['EGRESO'];
		}*/

          if ($_SESSION['CONTEO']['GLUCO'] > 0){
               $enlaceGlucometria = "<a href=\"".ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmFrecuenciaControlesP',array("control"=>8,"descripcion"=>"CONTROL GLUCOMETR&Iacute;A","estacion"=>$datos,"href_action_hora"=>"CallFrmIngresarDatosGlucometria","href_action_control"=>array(0=>"CallFrmResumenGlucometria"))) ."\" target=\"Contenido\">Glucometr&iacute;a</a>\n";
               $imgGlucometria = "<img src=\"".GetThemePath()."/images/EstacionEnfermeria/alarma.png\" border=0 width=12 heigth=12>\n";
               $_SESSION['GLOBAL']['VECT_GLUCO']=$_SESSION['CONTEO']['GLUCO_VECT'];//vector para controlar los ingresos para cuandovayamos a ver loscontroles de nuero y gluco
          }
          else{
               $enlaceGlucometria = "Glucometr&iacute;a";
          }

          if ($_SESSION['CONTEO']['NEURO'] > 0)
          {
               $enlaceNeurologico = "<a href=\"".ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmFrecuenciaControlesP',array("control"=>10,"descripcion"=>"CONTROL NEUROLOGICO","estacion"=>$datos,"href_action_hora"=>"CallFrmControlNeurologico","href_action_control"=>array(0=>"CallFrmResumenHojaNeurologica"))) ."\" target=\"Contenido\">Neurol&oacute;gico</a>";
               $imgNeurologico = "<img src=\"".GetThemePath()."/images/EstacionEnfermeria/alarma.png\" border=0 width=12 heigth=12>\n";
               $_SESSION['GLOBAL']['VECT_NEURO']=$_SESSION['CONTEO']['NEURO_VECT'];//vector para controlar los ingresos para cuandovayamos a ver loscontroles de nuero y gluco
          }
          else{
               $enlaceNeurologico = "Neurol&oacute;gico";
          }

          if ($conteo_transf > 0)
          {
               $enlaceTransfusiones = "<a href=\"".ModuloGetURL('app','EstacionE_ControlPacientes','user','CallControlesPacientes',array("control_id"=>24,"control_descripcion"=>"CONTROL DE TRANSFUSIONES","estacion"=>$datos)) ."\" target=\"Contenido\">Transfusiones</a>\n";
               $imgTransfusiones = "<img src=\"".GetThemePath()."/images/EstacionEnfermeria/editar.gif\" border=0 width=12 heigth=12>";
          }
          else{
               $enlaceTransfusiones = "Transfusiones";
          }


          /****parte del enlace de pacientes **/
          if($_SESSION['ESTACION_ENF']['CONTEO']['HOSP']< 1 AND $_SESSION['ESTACION_ENF']['CONTEO']['CONSULTA']< 1  AND $_SESSION['ESTACION_ENF']['CONTEO']['INGRESO'] < 1)
          {
               $enlacePac = "Pacientes Estaci&oacute;n de Enfermeria";
          }
          else
          {
               $enlaceAgregarCargos = "<a href=\"".ModuloGetURL('app','EstacionEnfermeriaCargos','user','main',array("estacion"=>$datos,"tipoa"=>1)) ."\" target=\"Contenido\">Agregar Cargos</a>";
               $enlaceAgregarInsumos = "<a href=\"".ModuloGetURL('app','EstacionEnfermeriaCargos','user','main',array("estacion"=>$datos,"tipoa"=>2)) ."\" target=\"Contenido\">Agregar Insumos</a>";
               $enlacePac = "<a href=\"".ModuloGetURL('app','EstacionE_Pacientes','user','CallListadoPacientesEstacion',array("datos_estacion"=>$datos)) ."\" target=\"Contenido\">Pacientes Estaci&oacute;n de Enfermeria</a>";
               $img = "<img src=\"".GetThemePath()."/images/EstacionEnfermeria/info.png\" border=0 width=12 heigth=12>";

          }

          if($_SESSION['ESTACION_ENF']['CONTEO']['HOSP']< 1)
          {
               $enlacePac = "Pacientes Estaci&oacute;n de Enfermeria";
               $enlaceCensoEstacion = "Censo de la Estaci&oacute;n";

          }
          else
          {
               $enlaceCensoEstacion = "<a href=\"".ModuloGetURL('app','Censo','user','CallCensoEstacion',array("datos_estacion"=>$datos)) ."\" target=\"Contenido\">Censo de la Estaci&oacute;n</a>";
               $imgCensoEstacion = "<img src='".GetThemePath()."/images/EstacionEnfermeria/info.png' border=0 width=12 heigth=12>";

               $enlacePac = "<a href=\"".ModuloGetURL('app','EstacionE_Pacientes','user','CallListadoPacientesEstacion',array("datos_estacion"=>$datos)) ."\" target=\"Contenido\">Pacientes Estaci&oacute;n de Enfermeria</a>";
               $img = "<img src=\"".GetThemePath()."/images/EstacionEnfermeria/info.png\" border=0 width=12 heigth=12>";
          }
          
          /****IMPRESION DE LA EPICRISIS******/
          $imgepi = "<img src=\"".GetThemePath()."/images/inactivoip.gif\" border=0 title='Impresión Epicrisis' width=12 heigth=12>";
          $imprimirEpicrisis = "<a href=\"".ModuloGetURL('app','ResumenEpicrisis','user','FormaMenus',array("datos_estacion"=>$datos,"ubicacion"=>"estacion")) ."\" target=\"Contenido\">Impresión Epicrisis</a>";
		/****IMPRESION DE LA EPICRISIS******/
          
          
          /****parte del enlace de pacientes **/
		$this->salida .= "<table align='center' border='0' width='100%' class='modulo_list_table'>\n";
		$this->salida .= "<tr class='modulo_table_title'><td width='35%'>Insumos Y Medicamentos</td><td width='15%'>Controles Paciente</td><td width='25%'>Estadisticas</td></tr>\n";//<td width='25%'>Grafica Censo</td>
		
          $this->salida .= "<tr class='modulo_list_claro'><td>$imgpend $enlacepend</td><td>$imgLiquidos $enlaceLiquidos &nbsp;($conto_liq)</td><td> $imgCensoEstacion $enlacePac</td></tr>\n";//<td rowspan='7'><TABLE BORDER='1'width='75%' align='center'><TR><TD>$grp</TD></TR></TABLE></td>
		
          $this->salida .= "<tr class='modulo_list_oscuro'><td>$img $enlace</td><td>$imgGlucometria $enlaceGlucometria &nbsp;($conteo_gluco)</td><td> $imgCensoEstacion $enlaceCensoEstacion</td></tr>\n";
		$this->salida .= "<tr class='modulo_list_claro'><td class='modulo_table_title'>Agenda x Programaci&oacute;n</td><td>$imgNeurologico $enlaceNeurologico &nbsp;($conteo_neuro)</td><td>$imgCensoEstacion&nbsp;<label class=label_mark>Pacientes por Ingresar=&nbsp;(".FormatoValor($_SESSION['ESTACION_ENF']['CONTEO']['INGRESO']).")</label></td></tr>\n";


		$this->salida .= "<tr class='modulo_list_oscuro'><td>$imgAgendaControles $enlaceAgendaControles</td><td>$imgTransfusiones $enlaceTransfusiones &nbsp;($conteo_transf)</td><td>$imgCensoEstacion&nbsp;<label class=label_mark>Pacientes Hospitalizados=&nbsp;(".FormatoValor($_SESSION['ESTACION_ENF']['CONTEO']['HOSP']).")</label></td></tr>\n";

		$this->salida .= "<tr colspan='5'><td class='modulo_table_title'>Cargar Insumos a la Cuenta</td><td class='modulo_list_claro'>$imgDietas $enlaceDietas &nbsp;($conto_liq)</td><td class='modulo_list_claro'>$imgCensoEstacion&nbsp;<label class=label_mark>Pacientes por Egresar=&nbsp;(".FormatoValor($_SESSION['ESTACION_ENF']['CONTEO']['EGRESO']).")</label></td></tr>\n";
		$this->salida .= "<tr class='modulo_list_oscuro'><td>$enlaceAgregarInsumos</td><td></td><td>$imgCensoEstacion&nbsp;<label class=label_mark>Pacientes en Consulta =&nbsp;(".FormatoValor($_SESSION['ESTACION_ENF']['CONTEO']['CONSULTA']).")</label></td></tr>\n";
		//Imprimir Epicrisis
		$this->salida .= "<tr colspan='8'><td class='modulo_table_title'>Solicitud Suministros x Estación</td><td class='modulo_list_claro' align=\"center\">&nbsp;</td><td class='modulo_list_claro' align=\"left\">$imgepi&nbsp;$imprimirEpicrisis</td></tr>\n";	
		//Modificar enlace de insumo. $enlaceInsumo
		$this->salida .= "<tr colspan='8'><td class='modulo_list_claro'>$img $sol_solicitud</td><td class='modulo_list_oscuro' rowspan='3' align=\"center\" colspan='2'>$imgegreso  $imgingreso $imghospi $imgcons</td></tr>\n";
		//$imgi Modificar enlace de insumo. $enlacei
          $this->salida .= "<tr colspan='8'><td class='modulo_list_claro'>$img2 $con_solicitud</td></tr>\n";
		
		$this->salida .= "</table>\n";
	//}
     	if(UserGetUID() != 0)
          {
          	$this->IyM_PendientesUsuarios($datos);
          }
          
		unset($_SESSION['ESTACION_ENF']['CONTEO']);//conteo de los pacientes hosp,egreso, ingresar
		unset($informacion);//esta variable tiene si hay o no pacientes en el sistema.
		unset($_SESSION['CONTEO']);//conteo de pacientes con controles de glucometria y neurologico.
		$refresh = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array('estacion'=>$datos));
		$href = ModuloGetURL('app','EstacionEnfermeria','user','main');
		$this->salida .= "<div class='normal_10' align='center'><br>\n";
		$this->salida .= "	<a href='".$href."'>Seleccionar Estaci&oacute;n</a>&nbsp;&nbsp;-&nbsp;&nbsp;\n";
		$this->salida .= "	<a href='$refresh'>Refrescar</a><br>\n";
		$this->salida .= "\n";
		unset($_SESSION['ESTACION_CONTROL']['INGRESO']);//FrmFrecuenciaControlesP 1017 (EstacionE_ControlPacientes).
		unset($_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']); //unseteamos esta var de session
		//q se activa para ver los controles del paciente y los apoyos desde la atencion de urgencias.
		$this->salida .= themeCerrarTabla();
		return true;
	}

     
     function IyM_PendientesUsuarios($datos)
     {
     	$datos_IyM = $this->BuscarDatos_ResponsableIyM($datos);
          if(!empty($datos_IyM))
          {
               $this->salida .= "<br><table class=\"modulo_table_list_title\" width=\"100%\">";
               $this->salida .= "<tr class=\"modulo_table_title\">";
               $usr_Estacion = $this->TraerUsuario(UserGetUID());
               $this->salida .= "<td colspan=\"6\">SUMINISTROS Y MEDICAMENTOS PENDIENTES POR CARGAR A LOS PACIENTES SOLICITADOS POR EL USUARIO ".$usr_Estacion[nombre]."</td>";
               $this->salida .= "</tr>";
               
               $this->salida .= "<tr class=\"modulo_table_title\">";
               $this->salida .= "<td>CODIGO</td>";
               $this->salida .= "<td>DESCRIPCION</td>";
               $this->salida .= "<td>CANTIDAD</td>";
               $this->salida .= "<td>USUARIO BODEGA</td>";
               $this->salida .= "<td>ESTACION</td>";
               $this->salida .= "<td>BODEGA</td>";
               $this->salida .= "</tr>";
               for($i=0; $i<sizeof($datos_IyM); $i++)
               {
                    if($i % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_oscuro";
                    $this->salida .= "<tr $estilo>";
                    $this->salida .= "<td align=\"center\">".$datos_IyM[$i][codigo_producto]."</td>";
                    $this->salida .= "<td align=\"justify\">".$datos_IyM[$i][descripcion]."</td>";
                    $this->salida .= "<td align=\"center\">".$datos_IyM[$i][cantidad]."</td>";
                    $usr_Bodega = $this->TraerUsuario($datos_IyM[$i][usuario_id]);
                    $this->salida .= "<td align=\"justify\">".$usr_Bodega[nombre]."</td>";
                    $nombre_EE = $this->TraerEstacion($datos_IyM[$i][estacion_id]);
                    $this->salida .= "<td align=\"justify\">".$nombre_EE[descripcion]."</td>";
                    $nombre_Bodega = $this->TraerBodega($datos_IyM[$i][bodega],$datos);
                    $this->salida .= "<td align=\"justify\">".$nombre_Bodega[descripcion]."</td>";
                    $this->salida .= "</tr>";
               }
               $this->salida .= "<tr class=\"modulo_table_title\">";
               $AccionCuadrar = ModuloGetURL('app','EstacionEnfermeria_IYM_Usuarios','user','ConsultaMyIDespachosPendientes',array('estacion'=>$datos));
               $this->salida .= "<td colspan=\"6\"><a href=\"$AccionCuadrar\">Cuadrar Suministros</a></td>";
               $this->salida .= "</tr>";
               $this->salida .= "</table>";
          }
          return true;
     }

}//fin class
?>
