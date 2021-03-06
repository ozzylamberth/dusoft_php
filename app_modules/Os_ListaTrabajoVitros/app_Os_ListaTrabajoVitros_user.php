<?php

/**
* $Id: app_Os_ListaTrabajoVitros_user.php,v 1.50 2006/02/20 16:00:03 mauricio Exp $
*
* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
* @package IPSOFT-SIIS
*
* @author    Mauricio Bejarano L. 
* @version   $Revision: 1.50 $
* @package   Os_ListaTrabajoVitros
* 
* Modulo de Listas de Trabajo para VITROS.
* Modulo para el manejo de listas de trabajo en Interface con VITROS
* El codigo fue tomado de Os_ListaTrabajoDatalab y modificado para Vitros
*/


class app_Os_ListaTrabajoVitros_user extends classModulo
{
	var $limit;
	var $conteo;//para saber cuantos registros encontró

	function app_Os_ListaTrabajoVitros_user()
	{
			$this->limit=GetLimitBrowser();
			return true;
	}

	/**
	* La funcion main es la principal y donde se llama FormaPrincipal
	* que muestra los diferentes tipos de busqueda de una cuenta para hospitalización.
	* @access public
	* @return boolean
	*/
	function main()
	{
			if(!$this->BuscarDatosUser())
			{
					return false;
			}
			return true;
	}
	
	/**
	* Muestra en rojo el campo donde se presento el error con su descripcion
	* @param  SetStyle($campo): $campo en el campo en donde se presento el error
	* @return lebel del error
	*/
   function SetStyle($campo)
	{
				if ($this->frmError[$campo] || $campo=="MensajeError"){
					if ($campo=="MensajeError"){
						return ("<tr><td class='label_error' colspan='3' print_r(align='center'>".$this->frmError["MensajeError"]."</td></tr>");
					}
					return ("label_error");
				}
			return ("label");
	}
/*******************************************************************/   

   /**
	* Cambia el formato de la fecha de dd/mm/YY a YY/mm/dd
	* @access private
	* @return string
	* @param date fecha
	* @var 	  cad	Cadena con el nuevo formato de la fecha
	*/
	function ConvFecha($fecha)
	{	
		if($fecha){
			$fech = strtok ($fecha,"-");
			for($i=0;$i<3;$i++)
			{
				$date[$i]=$fech;
				$fech = strtok ("-");
			}
			$cad = $date[2]."-".$date[1]."-".$date[0];
			return $cad;
		}
    }/**/
/*******************************************************************/   
   /**
	* Separa la Fecha del formato timestamp
	* @access private
	* @return string
	* @param date fecha
	*/
	function FechaStamp($fecha)
	{
		if($fecha){
				$fech = strtok ($fecha,"-");
				for($l=0;$l<3;$l++)
				{
					$date[$l]=$fech;
					$fech = strtok ("-");
				}

				return  ceil($date[2])."/".str_pad(ceil($date[1]),2,0,STR_PAD_LEFT)."/".str_pad(ceil($date[0]),2,0,STR_PAD_LEFT);
		}
    }/**/

/*******************************************************************/	
	/**
	* Separa la hora del formato timestamp
	* @access private
	* @return string
	* @param date hora
	*/
	function HoraStamp($hora)
    {
    	$hor = strtok ($hora," ");
    	for($l=0;$l<4;$l++)
    	{
    	  $time[$l]=$hor;
    	  $hor = strtok (":");
    	}
		$x=explode('.',$time[3]);
    	return  $time[1].":".$time[2].":".$x[0];
   }/**/
	 
	 
function GetForma()
{
			$this->Consultar_Cumplimiento($_REQUEST['numero_cumplimiento'],
			$_REQUEST['fecha_cumplimiento'],$_REQUEST['departamento'],
		  $_REQUEST['tipo_id_paciente'], $_REQUEST['paciente_id'],
			$_REQUEST['nombre'], $_REQUEST['edad_paciente']);
			return $this->salida;
}

	/**
		* La funcion BuscarDatosUser recibe todas las variables de manejo y verifica si el
		* usuario posee los permisos para acceder al modulo del laboratorio.
		* Nota: las variables pueden llegar por REQUEST o por Parametros.
		* @access private
		* @return boolean
		*/
		function BuscarDatosUser()
		{
						list($dbconn) = GetDBconn();
						GLOBAL $ADODB_FETCH_MODE;
			      $query="SELECT 	b.nombre_tercero, 
														b.usuario_id,
														c.departamento,
														c.descripcion as dpto,
														d.centro_utilidad,
														d.descripcion as centro, 
														e.empresa_id,
														e.razon_social as emp,
														f.sw_mostrar_listas,
														c.sw_maneja_vitros
										FROM 	profesionales_usuarios a,
													terceros b, 
													departamentos c,
													centros_utilidad d, 
													empresas e,
													userpermisos_os_lista_trabajo_interface_vitros f
										WHERE a.usuario_id=f.usuario_id AND
													a.tipo_tercero_id=b.tipo_id_tercero AND
													a.tercero_id=b.tercero_id AND
													c.empresa_id=d.empresa_id AND
													d.centro_utilidad=c.centro_utilidad AND
													e.empresa_id=c.empresa_id AND
													f.usuario_id=".UserGetUID()."	AND 
													f.departamento=c.departamento
										ORDER BY centro";
										
  					$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
						$resulta = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
								$this->error = "Error al consultar \"BuscarDatosUser\"";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
						}
						while($data = $resulta->FetchRow())
						{
								$laboratorio[$data['emp']][$data['centro']][$data['dpto']]=$data;
						}
						
						$url[0]='app';
						$url[1]='Os_ListaTrabajoVitros';
						$url[2]='user';
						$url[3]='Menuatencion';
						$url[4]='ListaTrabajoVitros';

						$arreglo[0]='EMPRESA';
						$arreglo[1]='CENTRO UTILIDAD';
						$arreglo[2]='ATENCION DE LISTA DE TRABAJO';

						$this->salida.= gui_theme_menu_acceso('ATENCION DE LISTA DE TRABAJO CON VITROS',$arreglo,$laboratorio,$url);
						return true;
		}


		/**
		* Menuatencion, esta funcion  es donde desemboca el menu iteractivo creado por arley.
		* @access public
		* @return boolean
		*/
function Menuatencion()
{
	//if(empty($_SESSION['LABORATORIO']['EMPRESA_ID']))
	//{
			$_SESSION['LTRABAJO_VITROS']['EMPRESA_ID']=$_REQUEST['ListaTrabajoVitros']['empresa_id'];
			$_SESSION['LTRABAJO_VITROS']['CENTROUTILIDAD']=$_REQUEST['ListaTrabajoVitros']['centro_utilidad'];
			$_SESSION['LTRABAJO_VITROS']['NOM_CENTRO']=$_REQUEST['ListaTrabajoVitros']['centro'];
			$_SESSION['LTRABAJO_VITROS']['NOM_EMP']=$_REQUEST['ListaTrabajoVitros']['emp'];
			$_SESSION['LTRABAJO_VITROS']['NOM_DPTO']=$_REQUEST['ListaTrabajoVitros']['dpto'];
			$_SESSION['LTRABAJO_VITROS']['DPTO']=$_REQUEST['ListaTrabajoVitros']['departamento'];
			$_SESSION['LTRABAJO_VITROS']['MOSTRAR_LISTAS']=$_REQUEST['ListaTrabajoVitros']['sw_mostrar_listas'];
			$_SESSION['LTRABAJO_VITROS']['MANEJA_VITROS']=$_REQUEST['ListaTrabajoVitros']['sw_maneja_vitros'];

	//}
		unset ($_SESSION['IMAGENES']['LISTAS']);
		if($_REQUEST['ListaTrabajoVitros']['sw_maneja_vitros']=='1'){
			if(!$this->FormaMetodoBuscar())
			{
					return false;
			}
		}else{
			$this->BuscarDatosUser();
		}
			return true;
}


//************************la funcion para clasificar el dpto de imagenes
//funcion que hace que el usuario de imagenologia pueda ver la clasificacion
//de las listas.
function GetListasTrabajo()
{
	list($dbconn) = GetDBconn();
	 $query="SELECT 	a.usuario_id,
									c.departamento, 
									c.descripcion as dpto, 
									d.descripcion as centro, 
									d.centro_utilidad, 
									e.empresa_id, 
									e.razon_social as emp,
									f.nombre_lista, 
									f.tipo_os_lista_id
					FROM 	userpermisos_os_lista_trabajo_interface_vitros a,
								departamentos c,
								centros_utilidad d,
								empresas e, 
								tipos_os_listas_trabajo f 
					WHERE a.usuario_id=".UserGetUID()." AND 
								a.departamento = '".$_SESSION['LTRABAJO_VITROS']['DPTO']."' AND
								a.departamento=c.departamento AND
								c.departamento = f.departamento AND 
								c.centro_utilidad=d.centro_utilidad AND 
								d.empresa_id=e.empresa_id AND 
								e.empresa_id=c.empresa_id 
					ORDER BY centro,f.tipo_os_lista_id";
	//$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
	$result = $dbconn->Execute($query);
	/**  OJO comentar este error no se esta mostrando */
	if ($dbconn->ErrorNo() != 0)
	{
			$this->error = "Error al consultar las listas";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
	}
	while(!$result->EOF)
	{
			$var[]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
	}
	return $var;
}
//************************fin



		/**
		* Realiza la busqueda según el plan,documento .. de los pacientes que
		* tienen ordenes de servicios pendientes
		* @access private
		* @return boolean
		*/
function BuscarOrden()
{	//validacion de si selecciono una lista
//print_r($_REQUEST);
//echo "<br>-----<br>";
	if ($_REQUEST['Buscar_Orden_Cargar_Session'] != '')//= a buscar
	{
		$tipo_documento=$_REQUEST['TipoDocumento'];
		$documento=$_REQUEST['Documento'];
		$nombres = strtolower($_REQUEST['Nombres']);
		$fecha=$_REQUEST['Fecha'];
		if(empty($fecha)){
				$fecha = date("Y-m-d");
		}
		elseif($fecha=='TODAS LAS FECHAS'){
				$fecha = '';
		}
		else
			$fecha=$this->FechaStamp($_REQUEST['Fecha']);//Convierte la fecha para asegurar que la fecha queda en formato yyyy-mm-dd
		$Mfecha = $fecha;
		$cont=substr_count($_REQUEST['Cumplimiento'], "-");
		if($cont==1){
			$cumplimiento= explode("-",$_REQUEST['Cumplimiento']);
			$fecha=$cumplimiento[0];
			$cumplimiento= $cumplimiento[1];
			$fecha=date( "Y-m-d",strtotime ("$fecha"));
			$Mfecha = $fecha;
		}else{
			$cumplimiento = $_REQUEST['Cumplimiento'];
		}
		//filtro adicional de pacientes
    $opcion_pacientes=$_REQUEST['opcion_pacientes'];

		list($dbconn) = GetDBconn();
		$filtroTipoDocumento = '';
		$filtroDocumento='';
		$filtroNombres='';
		$filtroCumplimiento='';
		$filtroFecha='';
		$filtroPacientes='';
		
    if((!empty($tipo_documento)) AND ($tipo_documento != -1)){
				$filtroTipoDocumento=" AND a.tipo_id_paciente = '$tipo_documento'";
		}

		if ($documento != ''){
				$filtroDocumento =" AND a.paciente_id LIKE '$documento%'";
		}

		if ($nombres != ''){
				$a=explode(' ',$nombres);
				foreach($a as $k=>$v){
						if(!empty($v)){
							$filtroNombres.=" AND (upper(b.primer_nombre||' '||b.segundo_nombre||' '||
																b.primer_apellido||' '||b.segundo_apellido) like '%".strtoupper($v)."%')";
						}
				}
		}

		if ($fecha != ''){
				$filtroFecha ="AND date(a.fecha_cumplimiento) = date('$fecha')";
		}

		if($cumplimiento != ''){
			$filtroCumplimiento="AND a.numero_cumplimiento = $cumplimiento";
		}
		/**%%verificar%*/
		//PACIENTES PENDIENTE SIN PROCESAR EN VITROS
		//mirar si esta cumplida
		if ($opcion_pacientes == 1){
				$filtroPacientes ="AND d.sw_estado = '3' AND c.sw_estado = '1'"; 
				//$filtroPacientes ='';
		}//EXA. EN PROCESO
		elseif ($opcion_pacientes == 2){
        $filtroPacientes ="AND d.sw_estado = '3' AND c.sw_estado = '1'";
		}//TODOS LOS PACIENTES
    elseif ($opcion_pacientes == 3){
        $filtroPacientes ="";
		}

  }
	else{
			list($dbconn) = GetDBconn();
			$filtroTipoDocumento = $_SESSION['BUSQUEDA_ORDEN']['filtroTipoDocumento'];
			$filtroDocumento = $_SESSION['BUSQUEDA_ORDEN']['filtroDocumento'];
			$filtroNombres = $_SESSION['BUSQUEDA_ORDEN']['filtroNombres'];
			$filtroFecha = $_SESSION['BUSQUEDA_ORDEN']['filtroFecha'];
			$filtroPacientes = $_SESSION['BUSQUEDA_ORDEN']['filtroPacientes'];
			$filtroCumplimiento = $_SESSION['BUSQUEDA_ORDEN']['filtroCumplimiento'];
	}
	
	if(empty($_REQUEST['conteo'.$pfj]))
	{
		$query = "SELECT COUNT(*)
											FROM  (SELECT DISTINCT 	b.fecha_nacimiento, 
															f.descripcion as servicio_descripcion,
															a.numero_cumplimiento, 
															a.departamento, 
															a.tipo_id_paciente, 
															a.paciente_id,
															a.fecha_cumplimiento, 
															btrim(b.primer_nombre||' '||b.segundo_nombre||' '||
															b.primer_apellido|| ' '||b.segundo_apellido,'') as nombre,
															c.sw_estado
												FROM 	os_cumplimientos a, 
															pacientes b,
															os_cumplimientos_detalle c, 
															os_maestro d, 
															os_ordenes_servicios e, 
															servicios f,
															interface_vitros_cargo g,
															cups i,
															os_internas j
												WHERE a.paciente_id = b.paciente_id AND 
															a.tipo_id_paciente = b.tipo_id_paciente AND 
															a.departamento = '".$_SESSION['LTRABAJO_VITROS']['DPTO']."' AND 
															a.numero_cumplimiento = c.numero_cumplimiento AND 
															a.fecha_cumplimiento = c.fecha_cumplimiento AND 
															a.departamento = c.departamento AND
															c.numero_orden_id = d.numero_orden_id AND 
															d.orden_servicio_id = e.orden_servicio_id AND
															e.servicio = f.servicio AND
															c.numero_orden_id = j.numero_orden_id AND
															d.cargo_cups = g.codigo_cups AND
															g.codigo_cups = i.cargo 
															$filtroTipoDocumento 
															$filtroDocumento 
															$filtroNombres 
															$filtroFecha 
															$filtroCumplimiento
															$filtroPacientes
											) AS a ";

						$resulta = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
								$this->error = "Error al Cargar el Modulo";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
						}
						list($this->conteo)=$resulta->fetchRow();
					}
					else
					{
						$this->conteo=$_REQUEST['conteo'.$pfj];
					}
					if(!$_REQUEST['Of'.$pfj])
					{
						$Of='0';
					}
					else
					{
						$Of=$_REQUEST['Of'.$pfj];
						if($Of > $this->conteo)
						{
								$Of=0;
								$_REQUEST['Of'.$pfj]=0;
								$_REQUEST['paso1'.$pfj]=1;
						}
					}
				
					  $query = "SELECT *
											FROM  (SELECT DISTINCT 	b.fecha_nacimiento, 
																							f.descripcion as servicio_descripcion,
																							a.numero_cumplimiento, 
																							a.departamento, 
																							a.tipo_id_paciente, 
																							a.paciente_id,
																							a.fecha_cumplimiento, 
																							btrim(b.primer_nombre||' '||b.segundo_nombre||' '||
																							b.primer_apellido|| ' '||b.segundo_apellido,'') as nombre,
																							c.sw_estado
															FROM 	os_cumplimientos a, 
																		pacientes b,
																		os_cumplimientos_detalle c, 
																		os_maestro d, 
																		os_ordenes_servicios e, 
																		servicios f,
																		interface_vitros_cargo g,
																		cups i,
																		os_internas j
															WHERE a.paciente_id = b.paciente_id AND 
																		a.tipo_id_paciente = b.tipo_id_paciente AND 
																		a.departamento = '".$_SESSION['LTRABAJO_VITROS']['DPTO']."' AND 
																		a.numero_cumplimiento = c.numero_cumplimiento AND 
																		a.fecha_cumplimiento = c.fecha_cumplimiento AND 
																		a.departamento = c.departamento AND
																		c.numero_orden_id = d.numero_orden_id AND 
																		d.orden_servicio_id = e.orden_servicio_id AND
																		e.servicio = f.servicio AND
																		c.numero_orden_id = j.numero_orden_id AND
																		d.cargo_cups = g.codigo_cups AND
																		g.codigo_cups = i.cargo 
																		$filtroTipoDocumento 
																		$filtroDocumento 
																		$filtroNombres 
																		$filtroFecha 
																		$filtroCumplimiento
																		$filtroPacientes
														) AS  a ORDER BY 	a.fecha_cumplimiento, 
																			a.numero_cumplimiento 
											LIMIT ".$this->limit." OFFSET $Of
								;";
			//interface_vitros_control_examen_detalle h,
			$resulta = $dbconn->Execute($query);
			//$this->conteo=$resulta->RecordCount();
				if($this->conteo==='0')
				{
						$this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO PARA $Mfecha";
						$this->FormaMetodoBuscar($var);
						return true;
				}

			if ($dbconn->ErrorNo() != 0)
			{
					$this->error = "Error al Cargar BD en Modulo BuscarOrden (2)";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$i=0;
			while(!$resulta->EOF)
			{
				$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
				$i++;
			}
			$resulta->Close();
	//recarga variables en session
  if ($_REQUEST['Buscar_Orden_Cargar_Session'] != '')
  {
			unset ($_SESSION['BUSQUEDA_ORDEN']);
			$_SESSION['BUSQUEDA_ORDEN']['filtroTipoDocumento'] = $filtroTipoDocumento;
			$_SESSION['BUSQUEDA_ORDEN']['filtroDocumento'] = $filtroDocumento;
			$_SESSION['BUSQUEDA_ORDEN']['filtroNombres'] = $filtroNombres;
			$_SESSION['BUSQUEDA_ORDEN']['filtroFecha'] =  $filtroFecha;
			$_SESSION['BUSQUEDA_ORDEN']['filtroPacientes'] = $filtroPacientes;
			$_SESSION['BUSQUEDA_ORDEN']['filtroCumplimiento'] = $filtroCumplimiento;
  }

	$this->FormaMetodoBuscar($var);
	return true;
}//fin del metodo BuscarOrden


/**
* Consulta el nombre del examen Vitros dado su codigo
* @param array datos codigos vitros a ser analizados
* @param string	cargo Codigo cups
* @return array vector de entrada complementado con los nombre de los examenes
*/
function ConsultaCodigosVitros($datos,$cargo){
	list($dbconnect) = GetDBconn();
	$examen='';
	for($i=0;$i<sizeof($datos);$i++){
		$cargo=$datos[$i][cargo];
		$query="SELECT nombre_reporte 	
						FROM 	interface_vitros_cargo 
						WHERE codigo_cups=$cargo 
						";
		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0){
				$this->error = "Error en la busqueda";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
		}
		else{
			$examen[$i]=$result->GetRowAssoc($ToUpper = false);
			$datos[$i][nombre_reporte]=$examen[$i][nombre_reporte];
		}
	}
	$result->Close();
	return $datos;
}//fin function ConsultaCodigosVitros

/**
* Consulta el estado de los examenes
* @param array datos contiene los datos de los examens a analizar
* @return array datos vector de entrada complementado con el estado en las tablas de control
*/
function ConsultarEstadoExamen($datos){
	list($dbconnect) = GetDBconn();
	$estado='';
	for($i=0;$i<sizeof($datos);$i++){
		$os_id=$datos[$i][orden_servicio_id];
		$no_id=$datos[$i][numero_orden_id];
		$cargo_vitros=$datos[$i][cargo];
		
		$query="SELECT b.sw_estado_examen 
						FROM 	interface_vitros_control_examen a,
									interface_vitros_control_examen_detalle b,
									interface_vitros_cargo c
						WHERE b.numero_orden_id= $no_id AND
									c.codigo_cups = $cargo_vitros AND
									c.codigo_vitros = b.codigo_vitros
						";
		
		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0){
				$this->error = "Error en la busqueda de sw_estado_examen";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
		}
		else{
			$cad=$result->fields[0];
			$datos[$i][estado_examen]=$cad;
		}
	}
	$result->Close();
	return $datos;
}//fin function ConsultarEstadoExamen


/**
* Consulta las ordenes čndientes de cada paciente
* @param string	$numero_cumplimiento
* @param date		$fecha_cumplimiento
* @param string	$departamento
* @return array datos Contiene los datos de los estados de los examenes
*/
function ConsultaOrdenesPaciente($numero_cumplimiento, $fecha_cumplimiento, $departamento)
{
		list($dbconnect) = GetDBconn();
		unset($query);
			/** %%verificar*///que traiga solo los de vitros
			$query = "SELECT 	b.orden_servicio_id, 
												a.numero_orden_id,
												a.sw_estado as sw_estado_cumplimiento, 
												c.cargo, 
												c.descripcion,
												d.nombre_prueba ,
												d.lab_examen_id,
												d.codigo_vitros,
												d.nombre_reporte
									FROM 	os_cumplimientos_detalle as a,
												os_maestro as b, 
												cups as c,
												interface_vitros_cargo as d
									WHERE a.numero_cumplimiento = ".$numero_cumplimiento." AND
												a.fecha_cumplimiento = '".$fecha_cumplimiento."' AND
												a.departamento = '".$departamento."' AND 
												a.numero_orden_id = b.numero_orden_id AND 
												b.cargo_cups = c.cargo AND
												b.cargo_cups = d.codigo_cups AND
												b.sw_estado = 3
									ORDER BY 	b.orden_servicio_id asc, 
														a.numero_orden_id asc
									";
									
// 									b.cargo_cups = c.cargo  AND
// 									c.cargo = d.codigo_cups
//									b.cargo_cups = d.codigo_cups
	  $result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0){
				$this->error = "Error en la busqueda en ConsultaOrdenesPaciente";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
		}
		else{
			$i=0;
			while (!$result->EOF){
				$datos[$i]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
				$i++;
			}
		}
		
		/** %%verificar*/
		//TRAYENDO el codigo Vitros para cada examen
// 		if(sizeof($datos)>0){
// 			$datos=$this->ConsultaCodigosVitros($datos,$cargo);
// 		}
		
		//TRAYENDO el estado de examen datalle para cadauno
		if(sizeof($datos)>0){
			$datos=$this->ConsultarEstadoExamen($datos);
	  }
		$result->Close();
		
	  return $datos;
}//fin function ConsultaOrdenesPaciente

/**
	* La funcion tipo_id_paciente se encarga de obtener de la base de datos
	* los diferentes tipos de identificacion de los paciente.
	* @access public
	* @return array
*/
	function tipo_id_paciente()
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT * FROM tipos_id_pacientes ORDER BY indice_de_orden";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		else
		{
				if($result->EOF)
				{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "La tabla maestra 'tipos_id_pacientes' esta vacia ";
						return false;
				}
				while (!$result->EOF)
				{
						$vars[$result->fields[0]]=$result->fields[1];
						$result->MoveNext();
				}
    }
		$result->Close();
		return $vars;
  }

	/**
	* La funcion TiposFluido se encarga de obtener de la base de datos
	* los diferentes tipos de fluidos de las muestras que ingresan a vitros
	* @access public
	* @return array
*/
	function TiposFluido($cargo)
	{
		list($dbconn) = GetDBconn();
		$query="SELECT --suero,csf, orina
										suero, orina, csf
						FROM interface_vitros_cargo
						WHERE codigo_cups = '".$cargo."'";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al Seleccionar fluidos";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		else{
			$suero=$result->fields[0];
			$orina=$result->fields[1];
			$csf=$result->fields[2];
    }
		if($orina=='1'){$orina='3';}
		if($csf=='1'){$csf='2';}
		$query = "SELECT * 
							FROM interface_vitros_fluidos 
							WHERE fluido_id = '".$suero."' OR
										fluido_id = '".$csf."' OR
										fluido_id = '".$orina."' 
							ORDER BY fluido_id";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al Seleccionar fluidos";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		else{
			while (!$result->EOF){
					$tipos[$result->fields[0]]=$result->fields[1];
					$result->MoveNext();
			}
		}
		$result->Close();
		
		return $tipos;
  }
	
/**
	* La funcion TiposAnalizador se encarga de obtener de la base de datos
	* los diferentes tipos de equipos vitros
	* @access public
	* @return array
*/
	function TiposAnalizador()
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT analizador_id, descripcion_analizador FROM interface_vitros_analizador ORDER BY descripcion_analizador";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al consultar tablas";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		while(!$result->EOF){
				//$var[]=$result->GetRowAssoc($ToUpper = false);
				//$i=0;
				//$var[$i]=$result->fields[0];
				//$result->MoveNext();
				//$i++;
				$var=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
		}
			$result->Close();
			return $var;
}

/**
	* La funcion TiposBandeja se encarga de obtener de la base de datos
	* los diferentes tipos de bandejas utilizados por la vitros
	* @access public
	* @return array
*/
	function TiposBandeja()
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT * FROM interface_vitros_bandeja ORDER BY bandeja_id";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al consultar tablas";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		while(!$result->EOF){
				$var[$result->fields[0]]=$result->fields[1];
				$result->MoveNext();
		}
			$result->Close();
			return $var;
}

/**
*
*/
	function ReorganizaDatos(){
		//Validaciones necesarias
// 	print_r($_REQUEST);
// 	echo "<br>";
// 	exit;
	$sw_validacion=0;
	$donde='';
	$mensaje='';
		if($_REQUEST['tipo_analizador']=='-1'){
			$sw_validacion=1;	
			$donde='analizador';
			$mensaje='DEBE SELECCIONAR UN ANALIZADOR.';
			return true;
		}elseif(($_REQUEST['bandeja']!='0')&&($_REQUEST['posicion_copa']=='0')){
			$sw_validacion=1;
			$donde='copa';
			$mensaje='DEBE SELECCIONAR UNA POSICION DE LA COPA.';
			return true;
		}/*elseif($_REQUEST['tipo_fluido']=='-1'){
			$sw_validacion=1;
			$donde='fluido';
			$mensaje='DEBE SELECCIONAR EL TIPO DE FLUIDO SUMINISTRADO A LA VITROS.';
			return true;
		}*/elseif($_REQUEST['dilucion']==''){
			$sw_validacion=1;
			$donde='dilucion';
			$mensaje='DEBE SUMINISTRAR EL FACTOR DE DILUCION DE LA MUESTRA.';
			return true;
		}
		//gestiona error en la validacion
		if($sw_validacion==1){
			$this->frmError[$donde]=1;
			$this->frmError["MensajeError"]=$mensaje;
			$this->Consultar_Cumplimiento($_REQUEST['numero_cumplimiento'], $_REQUEST['fecha_cumplimiento'],
																		$_REQUEST['departamento'], $_REQUEST['tipo_id_paciente'],
																		$_REQUEST['paciente_id'],$_REQUEST['nombre'],$_REQUEST['edad_paciente']);
		}
		
		//echo "request->";print_r($_REQUEST);echo "<br>";
		//$cumplimiento es la forma compuesta de fecha+numero de  $numero_cumplimiento
		//$cumplimiento=$_REQUEST['cumplimiento'];
		$numero_cumplimiento=$_REQUEST['numero_cumplimiento'];
		$fecha_cumplimiento=$_REQUEST['fecha_cumplimiento'];
		$departamento=$_REQUEST['departamento'];
		$tipo_id_paciente=$_REQUEST['tipo_id_paciente'];
		$paciente_id=$_REQUEST['paciente_id'];
		$nombre=$_REQUEST['nombre'];
		$tipo_analizador=$_REQUEST['tipo_analizador'];
		$tipo_fluido=$_REQUEST['tipo_fluido'];
		$dilucion=$_REQUEST['dilucion'];
		$prueba=$_REQUEST['prueba'];
		$cont_datos=$_REQUEST['cont_datos'];
		$bandeja=$_REQUEST['bandeja'];
		$posicion_copa=$_REQUEST['posicion_copa'];
		$sw_repeticion='0';
		$fecha=Date('Y-m-d');

		//se reorganizan los datos por cada uno de los fluidos que llegan
		foreach($prueba as $pr){
			if($pr['op']=='1'){
				switch($pr['tipo_fluido'])
				{
					case 1:
						$fluido1[]=$pr;
						break;
					case 2:
						$fluido2[]=$pr;
						break;
					case 3:
						$fluido3[]=$pr;
				}
			}
		}

		if(!empty($fluido1)){
			$this->ActualizaDatos($numero_cumplimiento,$fecha_cumplimiento,$departamento,$tipo_id_paciente,
														$paciente_id,$nombre,$tipo_analizador,$tipo_fluido,$dilucion,$fluido1,$cont_datos,
														$sw_repeticion,$fecha,$bandeja,$posicion_copa);
		}
		if(!empty($fluido2)){
			$this->ActualizaDatos($numero_cumplimiento,$fecha_cumplimiento,$departamento,$tipo_id_paciente,
														$paciente_id,$nombre,$tipo_analizador,$tipo_fluido,$dilucion,$fluido2,$cont_datos,
														$sw_repeticion,$fecha,$bandeja,$posicion_copa);
		}
		if(!empty($fluido3)){
			$this->ActualizaDatos($numero_cumplimiento,$fecha_cumplimiento,$departamento,$tipo_id_paciente,
														$paciente_id,$nombre,$tipo_analizador,$tipo_fluido,$dilucion,$fluido3,$cont_datos,
														$sw_repeticion,$fecha,$bandeja,$posicion_copa);
		}
		
		return true;
		
	}//fin ReorganizaDatos


	/**
	* Actualiza en la base de datos de control vitros los examenes que se envian a la Vitros
	* y los deja en espera de respuesta despues de validar que se ingresaron todos los datos obligatorios
	* @access public
	* @return null
	*/
		function ActualizaDatos($numero_cumplimiento,$fecha_cumplimiento,$departamento,$tipo_id_paciente,
													$paciente_id,$nombre,$tipo_analizador,$tipo_fluido,$dilucion,$prueba,$cont_datos,
													$sw_repeticion,$fecha,$bandeja,$posicion_copa){

		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$repeticiones_cargo = 1;
//		$prefijo=0;
			$primera_vez=1;
			//for($i=0;$i<$cont_datos;$i++){
			$i=0;
			foreach($prueba as $pru){
				//si se chequeo en el form
				if($prueba[$i][op]=='1'){
					$cumplimiento=$prueba[$i]['cumplimiento'];
					$cargo=$prueba[$i]['cargo'];
					$sufijo_cargo=$this->ConsultaSufijoCargo($cargo);
					$volumen=$prueba[$i]['volumen'];
					$tipo_fluido=$prueba[$i]['tipo_fluido'];
					$lab_examen_id = $prueba[$i] ['lab_examen_id'];
					$orden_servicio=$prueba[$i]['orden_servicio_id'];
					$numero_orden_id=$prueba[$i]['numero_orden_id'];
					$codigo_vitros=$prueba[$i]['codigo_vitros'];
					
					if(empty($codigo_vitros))
					{
						$codigo_vitros = $_SESSION['CODIGO_VITROS'][$i];
					}

					//Si el sufijo el mayor a 1 significa que es una glucosa
					//Caso especial del especial, ;) 
					//cuando el consecutivo del cumplimiento sobrepasa el 99
					//el numero_id sobrepasa lo s 12 caracters que se visualizan
					//en la vitros. para este caso se modifica el cumplimiento
					//de 060220-999-G1, pasa a 0220-999-G1
					
					if($sufijo_cargo > 1)
					{
						$this->CargoCasoEspecial(&$dbconn,$numero_cumplimiento,$fecha_cumplimiento,$departamento,$tipo_id_paciente,
																			$paciente_id,$tipo_analizador,$tipo_fluido,$dilucion,$orden_servicio,$cargo,
																			$fecha,$bandeja,$posicion_copa,$volumen,$numero_orden_id,$sufijo_cargo,
																			$cumplimiento,$codigo_vitros);
					}
					else
					{
						//if($repeticiones_cargo>1){$sw_repeticion='1';}
						//for($j=0;$j<$repeticiones_cargo;$j++){
							if(($primera_vez==1) OR ($sw_repeticion=='1')){
									$muestra_id=$this->GeneraSufijoMuestraId($j,$repeticiones_cargo,$cumplimiento);

									$condicion="WHERE bandeja_id = '$bandeja' AND		posicion_copa = '$posicion_copa'";
									$this->InsertaControlExamen(&$dbconn,$muestra_id,$numero_cumplimiento,$fecha_cumplimiento,$departamento,
																	$tipo_analizador,$fecha,$tipo_id_paciente,$paciente_id,$bandeja,$tipo_fluido,
																	$posicion_copa,$dilucion,$volumen);
									$this->InsertaControlExamenDetalle(&$dbconn,$bandeja,$posicion_copa,$muestra_id,$numero_orden_id,
																					$orden_servicio,$cargo,$lab_examen_id,$condicion,$codigo_vitros);
									if($sw_repeticion=='1'){
										$primera_vez=1;
									}else{
										$primera_vez=0;
									}
								
								/**%%Validar*///Actualiza tomado
								//$this->ActualizaTomado($muestra_id,$numero_orden_id,'1');
							}else{//if existe copa
									$condicion="";
								$cargo=$prueba[$i]['cargo'];
								$numero_orden_id=$prueba[$i]['numero_orden_id'];
								$this->InsertaControlExamenDetalle(&$dbconn,$bandeja,$posicion_copa,$muestra_id,$numero_orden_id,
																										$orden_servicio,$cargo,$lab_examen_id,$condicion,$codigo_vitros);
								/**%%Validar*///Actualiza tomado
								//$this->ActualizaTomado($muestra_id,$numero_orden_id,'1');
							}//fin else
						//}//for  repeticiones
					}//if de caso especial
					$sw_repeticion='0';
				}//if op==1
				$i++;
			}//fin for
		$dbconn->CommitTrans();
		
		$this->Consultar_Cumplimiento($numero_cumplimiento, $fecha_cumplimiento, $departamento, $tipo_id_paciente, $paciente_id,$nombre, $edad_paciente);
		return true;
	}// fin ActualizaDatos()
	
	/**
	*
	*/
	function CargoCasoEspecial(&$dbconn,$numero_cumplimiento,$fecha_cumplimiento,$departamento,$tipo_id_paciente,
														$paciente_id,$tipo_analizador,$tipo_fluido,$dilucion,$orden_servicio,$cargo,
														$fecha,$bandeja,$posicion_copa,$volumen,$numero_orden_id,$sufijo_cargo,$cumplimiento,
														$codigo_vitros)
	{
		
		$condicion="";
		$cargo_cups=$cargo;
		//quito los dos primeros caracteres del ańo
		print_R($cumplimiento);
		$cumplimiento = substr($cumplimiento , 2);
		print_R($cumplimiento);
		for($i=1;$i<=$sufijo_cargo;$i++)
		{
			$muestra_id=$cumplimiento."-G".$i;
			$cargo=$cargo_cups."-G".$i;
			$tmp=$this->ConsultaLabExamen($cargo);
			$lab_examen_id=$tmp[0];
			//$codigo_vitros=$tmp[1];
			
			$this->InsertaControlExamen(&$dbconn,$muestra_id,$numero_cumplimiento,$fecha_cumplimiento,$departamento,
																	$tipo_analizador,$fecha,$tipo_id_paciente,$paciente_id,$bandeja,$tipo_fluido,
																	$posicion_copa,$dilucion,$volumen);
			$this->InsertaControlExamenDetalle(&$dbconn,$bandeja,$posicion_copa,$muestra_id,$numero_orden_id,
																					$orden_servicio,$cargo_cups,$lab_examen_id,$condicion,$codigo_vitros);
		}
		
		return true;
	}
	
	/**
	*
	*/
	function ConsultaLabExamen($cargo)
	{
		list($dbconnect) = GetDBconn();
		$query="SELECT 	lab_examen_id,codigo_vitros
						FROM		interface_vitros_cargo_especial
						WHERE		codigo_cups = '".$cargo."';";
		$result=$dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0){
				$this->error = "Error al consultar lab_examen";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				$dbconnect->RollbackTrans();
				echo "Error en consulta lab_examen";
				return false;
		}
		$res=$result->fields[0];
		return $res;
	}
	

	/**
	*
	*/
	function InsertaControlExamen(&$dbconn,$muestra_id,$numero_cumplimiento,$fecha_cumplimiento,$departamento,
																$tipo_analizador,$fecha,$tipo_id_paciente,$paciente_id,$bandeja,$tipo_fluido,
																$posicion_copa,$dilucion,$volumen)
	{
		$query="INSERT INTO interface_vitros_control_examen
							(	interface_vitros_control_examen_id,
								muestra_id,
								nombre_archivo,
								numero_cumplimiento,
								fecha_cumplimiento,
								departamento,
								analizador_id,
								usuario_id,
								fecha,
								tipo_id_paciente,
								paciente_id,
								bandeja_id,
								fluido_id,
								estado,
								posicion_copa,
								dilucion,
								volumen)
						VALUES(	NEXTVAL ('interface_vitros_control_examen_id'),
										'$muestra_id',
										NEXTVAL ('interface_vitros_nombre_archivo'),
										'$numero_cumplimiento',
										'$fecha_cumplimiento',
										'$departamento',
										'$tipo_analizador',
										".UserGetUID().",
										'$fecha',
										'$tipo_id_paciente',
										'$paciente_id',
										'$bandeja',
										'$tipo_fluido',
										'0',
										'$posicion_copa',
										'$dilucion',
										'$volumen'
									);
						";
		$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al insertar tabla interface_vitros_control_examen";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				echo "Error al insertar tabla interface_vitros_control_examen(InsertaControlExamen)\n ". $dbconn->ErrorMsg();
				return false;
		}
		return true;
	}
	/**
	*
	*/
	function InsertaControlExamenDetalle(&$dbconn,$bandeja,$posicion_copa,$muestra_id,$numero_orden_id,
																				$orden_servicio,$cargo,$lab_examen_id,$condicion,$codigo_vitros)
	{
		if($codigo_vitros == "'")
		{
			$codigo_vitros=addslashes($codigo_vitros);
		}
		
		$query="INSERT INTO interface_vitros_control_examen_detalle
													(interface_vitros_control_examen_id,
													muestra_id,
													numero_orden_id,
													orden_servicio_id,
													codigo_vitros,
													sw_estado_examen,
													error_vitros,
													codigo_cups,
													lab_examen_id,
													tecnica_id)
						VALUES			(
												(SELECT SETVAL('interface_vitros_control_examen_id ',
																			(SELECT MAX(interface_vitros_control_examen_id) 
																			FROM interface_vitros_control_examen
																			$condicion
																			))),
												'$muestra_id',
												$numero_orden_id,
												$orden_servicio,
												'".$codigo_vitros."',
												'1',
												'0050',
												'$cargo',
												".$lab_examen_id.",
												4
												);";
		$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al insertar tabla interface_vitros_control_examen_detalle";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				echo "Error al insertar tabla interface_vitros_control_examen_detalle(InsertaControlExamenDetalle)\n". $dbconn->ErrorMsg();
				return false;
		}
		return true;
	}
	
	/**
	*
	*/
	function ConsultaSubexamenes($cargo){
		$query="SELECT	cargo,
										lab_examen_id,
										nombre_examen
						FROM		lab_examenes
						WHERE 	cargo = '$cargo' AND
										tecnica_id = '4'";
		list($dbconn) = GetDBconn();
		if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al consultar lab_examen";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		while(!$result->EOF)
		{
				$var[]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
		}
		return $var;
	}
	
	/**
	* Se encarga de actualizar el swiche de os_cumplimientos_detalle a tomado o no tomado dependiendo de la 
	* accion que se haya seleccionado
	* @param		$cumplimiento
	* @param		$num_orden_id
	* @param		$estado_sw  estado en el que se quiere porner el sw 0 - 1
	* @return		cantidad de datos actualizados
	*/
	function ActualizaTomado($cumplimiento, $num_orden_id, $estado_sw){
		$cumplimiento = explode("-",$cumplimiento);
		$fecha=$cumplimiento[0];
		$cumplimiento= $cumplimiento[1];
		$fecha=date( "Y-m-d",strtotime ("$fecha"));
		list($dbconn) = GetDBconn();
		$query="UPDATE	os_cumplimientos_detalle
						SET			sw_estado = '$estado_sw'
						WHERE 	departamento = '".$_SESSION['LTRABAJO_VITROS']['DPTO']."'  AND
										fecha_cumplimiento = '$fecha'  AND
										numero_cumplimiento = $cumplimiento AND
										numero_orden_id = $num_orden_id
						";	
						
						
						//esto es de prubas se quita
		$path=GetVarConfigAplication('DIR_SIIS')."Interface_Files/Vitros250/";
		$nombre_archivo=$path.'recibido.txt';
		if(!$fichero = fopen($nombre_archivo,'a')){
			return 'errorF';
		}
		fputs($fichero,"query ".$query);
		fclose($fichero);
						
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al actualizar tomado en cumplimineto detalle";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		$cont=$result->fields[0];
		$result->Close();
		return $cont;
	}//fin function ActualizaTomado
	
	/**
	* Se encarga de consultar cuantos examenes se deben realizar de un cargo determionado
	* ejemplo en la glucosa hay un examen que se saca cada hora en cuatro oportunidades
	* por lo que a la vitros se le envia de una vez los 4 examenes a analizar
	* @param 	$cargo
	* @return	integer
	*/
	 function ConsultaSufijoCargo($cargo){
		list($dbconn) = GetDBconn();
		$query="SELECT	DISTINCT a.sufijo_muestra_id
						FROM		interface_vitros_cargo a
						WHERE		a.codigo_cups='$cargo' 
						";	
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al consultar sufijo muestra";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		$cont=$result->fields[0];
		$result->Close();
		return $cont;
	}
	
	/**
	*	GeneraSufijoMuestraId, Genera el numnero consecutivo de la muestra, teniendo en cuenta
	* la configuracion de reinicio, que puede ser por dias o meses y la cantidad de veces 
	* que debe repetirse n examen determinado
	* @param cont Contiene el numero de repeticion del examen
	* @return muestra_id El identificador de la muestra dado el formato "fecha"_"consecutivo"_"repeticion"
	*/
	function GeneraSufijoMuestraId($cont,$repeticion,$cumplimiento){
		if($repeticion==1){
			$sufijo='';}
		else{
			$sufijo="-".$cont;}
		
		$muestra_id=$cumplimiento.$sufijo;
		/**%%validar reinicio de contador*/
		/*
		--select setval ('interface_vitros_consecutivo_id_muestra',1)
		select nextval ('interface_vitros_consecutivo_id_muestra')
		*/
		return $muestra_id;
	}//fin GeneraSufijoMuestraId()
	
	
	
	/**
	*	Construye la cadena con los examenes que se envian a la Vitros
	* @access public
	*	@param array 	var	Contiene la informacion de los examnes seleccionados para enviarlos a la Vitros
	* @return String	cadena contiene la cadena con el formato Vitros lista para ser enviada  a la Vitros
	*/
	function ConstruyeCadena($var,$tipo_id_paciente,$paciente_id){
		$can_var=sizeof($var);
		$can_ban=5;
		$can_copa=10;
		$cadena='';
		$cod_vit='';
		$fin_examen=']';
		$fin_muestra='}';
		$analizador='';
		$salto="\n";
		$sw=0;
		$sw_fin=0;
		$tmp_muestra_id='';
		
		foreach($var as $muestra){
			foreach($muestra as $prueba ){
				if($sw==0){
					if(($prueba[bandeja_id]=='0')&&($prueba[copa_equivalencia]==' ')){
						//$bandej=str_pad('',15, " ", STR_PAD_RIGHT);
						//caso c
						$bandej='';
						//%%verificar y borrar los otros casos
						//caso b
						//$bandej=str_pad('',16, " ", STR_PAD_RIGHT);
						//caso a
						//$bandej="|".str_pad('',15, " ", STR_PAD_RIGHT);
						$copa_id=' ';
					}else{
						$bandej="|".str_pad($prueba[descripcion_bandeja],15, " ", STR_PAD_RIGHT);
						$copa_id=$prueba[copa_equivalencia ];
					}
					$id_muestra=str_pad($prueba[muestra_id],15, " ", STR_PAD_RIGHT);
					$fluido=$prueba[fluido_id];
					$bandera_estado=$prueba[estado];
					if(is_float($prueba[dilucion])){
						$dilucion=str_pad($prueba[dilucion],5, "0", STR_PAD_RIGHT);
					}else{
						$dilucion=$prueba[dilucion].".";
						$dilucion=str_pad($dilucion,5, "0", STR_PAD_RIGHT);
					}
					
					$cadena=$bandej.$id_muestra.$fluido.$bandera_estado.$copa_id.$dilucion;
					$file="S".str_pad($prueba[nombre_archivo],7, "0", STR_PAD_LEFT);
					$tmp_muestra_id=$prueba[muestra_id];
					$sw=1;
				}
				$cadena.=$prueba[codigo_vitros];
				
				$this->ActualizaEstado($prueba[muestra_id],$prueba[numero_orden_id],$prueba[codigo_vitros]);
			}//for prueba
			$nombre_paciente=$this->RetornaNombrePaciente($tipo_id_paciente,$paciente_id);
			$cadena.="|".$nombre_paciente.$fin_examen.$salto;
			$resfile=$this->CreaArchivoFisico($file,$cadena);
			//$resfile='ok';
			//echo "<br>creafile-> ".$resfile;
			if($resfile=='ok'){
				$res=$this->EnviaDatosWebService($file,$cadena);
				//echo "<br>resWS-> ".$resfile;
				if ($res=='error'){
					$this->ActualizaErrorWs($tmp_muestra_id,'0057');
				}else{
						//%%$eli=unlink(GetVarConfigAplication('DIR_SIIS')."Interface_Files/Vitros250/".$file);
				}
			}
			$cadena='';
			$sw=0;
		}//for id_muestra
		//echo "<br> sale";
		return true;
	}//fin function ConstruyeArchivo
		
	/**
	* Retorna el nombre del paciente deseado
	* @param $tipo_id_paciente
	* @param	$paciente_id
	* @return String
	*/
	function RetornaNombrePaciente($tipo_id_paciente,$paciente_id){
		list($dbconn) = GetDBconn();
		$query="SELECT primer_apellido,
									primer_nombre
						FROM pacientes
						WHERE paciente_id = '$paciente_id' AND
									tipo_id_paciente = '$tipo_id_paciente'";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
				//echo "<br>Error BD " . $dbconn->ErrorMsg();
				return false;
		}
		$apellido=substr(str_pad($result->fields[0],15, " ", STR_PAD_RIGHT),0,15);
		$nombre=substr(str_pad($result->fields[1],10, " ", STR_PAD_RIGHT),0,10);
		return $apellido.$nombre;
	}
	
	/**
	* Consulta el valor real para vitros de una copa
	* @param 	$copa
	* @return	char
	*/
	function ConsultaValorRealCopa($copa){
		list($dbconn) = GetDBconn();
		$query="SELECT 	copa_equivalencia
						FROM 		interface_vitros_copas
						WHERE 	copa_id = $copa";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
				//echo "<br>Error BD " . $dbconn->ErrorMsg();
				return false;
		}
		while(!$result->EOF)
		{
				$res=$result->fields[0];
				$result->MoveNext();
		}
		//echo "copa-> ".$res;
		return $res;
	}//fin function ConsultaValorRealCopa
	
	/**
	*	Crea Fisicamente el archivo que se enviara a la Vitros
	*	@param $file 		Nombre del archivo
	*	@param $cadena	Cadena con la informacion a almacenar en le archivo
	* @access public
	* @return true
	*/
	function CreaArchivoFisico($file,$cadena){
	// Aqui se crea el archivo
		$path=GetVarConfigAplication('DIR_SIIS')."Interface_Files/Vitros250/";
		$nombre_archivo=$path.$file;
		//echo "<br>ruta->".$nombre_archivo;
		if(!$fichero = fopen($nombre_archivo,'w')){
			$this->frmError["MensajeError"]='NO SE PUDO CREAR EL ARCHIVO EN SIIS.VERIFIQUE PERMISOS';
			$this->ActualizaErrorVitros("0054",$file);
			return 'error';
		}
		fclose($fichero);
		
		if(!$fichero = fopen($nombre_archivo,'a')){
			$this->frmError["MensajeError"]='NO SE PUDO ańadir EL ARCHIVO EN SIIS.VERIFIQUE PERMISOS';
			$this->ActualizaErrorVitros("0054",$file);
			return 'error';
		}else{
			fputs($fichero,$cadena);
		}		
		fclose($fichero);
		return 'ok';
	}//fin function CreaArchivoFisico
	
	/**
	*	Se conecta con el WebService y envia una cadena con la informacion de los examenes
	*	que debenser analizados en la Vitros
	* @param string	file	Nombre del archivo 
	* @param string	cadena informacion de los examenes a procesar
	* @access public
	* @return null
	*/
	function EnviaDatosWebService($file,$cuerpo){
// 		echo "<br>entra ws<br>";
// 		echo "<br>cadenaWS-> ".$cuerpo;
		$path=GetVarConfigAplication('DIR_SIIS')."classes/nusoap/lib/";
		require_once($path."nusoap.php" ); 
		if(!IncludeClass('Vitros','Vitros')){
			//echo "ERROR AL INCLUIR LA CLASE :S";
			return false;
		}
		if(class_exists('Vitros')){ 
			$vitros = new Vitros();

			$ip=ModuloGetVar('app','Os_ListaTrabajoVitros','IP');
			$puerto=ModuloGetVar('app','Os_ListaTrabajoVitros','PUERTO');
			$proy_name=ModuloGetVar('app','Os_ListaTrabajoVitros','PROYECTO_NAME');
			$wsdl=ModuloGetVar('app','Os_ListaTrabajoVitros','JWS');
			$metodo=ModuloGetVar('app','Os_ListaTrabajoVitros','WEB_SERVICE_NAME_VITROS');
			
			
			$wsdl="http://".$ip.":".$puerto."/".$proy_name."/".$wsdl."?wsdl";
			//echo "<br> wsdl-> ".$wsdl;
			 // ErrorSistema($mensaje){
			//$client=new soapclient($wsdl, true, false, false, false, false, 7, 7);
			$client=new soapclient($wsdl, true);
			$sError = $client->getError();
			if ($sError) {
				$this->ErrorSistema("error 1--> Error En la Coneccion  con el WebService 'app'[" . $sError . "]");
				return 'error';break;
				//die();
			}else{
				
				/**Cali*/
				$param=array('validacion'=>'Guardar','nombre'=>$file,'cuerpo'=>$cuerpo);
				/**Tulua*/
				//$param=array('in0'=>'Guardar','in1'=>$file,'in2'=>$cuerpo);
				
				$respuesta= $client->call("webS", $param);
				//echo $client->getDebug(); 
				//$this->ErrorSistema("debug-->".$client->getDebug());
				if ($client->fault){ // Si
							$this->ErrorSistema("error 2--> Error en la Comunicacion con el WebService 'app'".$client->getDebug());
							return 'error';break;
				}
				else{ // No
							$sError = $client->getError();
							// Hay algun error ?
							if ($sError) { // Si
								//echo '<br>Error/Tomcat: ' . $sError;
								//$this->MuestraErrorConeccion("Error en la comunicacion con el WebService/Tomcat.");
								return 'error';break;
							}
				}
				//echo "<br>Termino enviar";
				$this->ControlTransmision($respuesta);
			}
		}
		return 'ok';
	}//fin function EnviaDatosWebService

	/**
	*
	*/
	/**%%validar*///Error el muestra_id no debe ser la de c_e_detalle
	function ActualizaErrorWs($muestra_id,$error){
	//echo "<br><br>-> Entra a errorWS";
		list($dbconn) = GetDBconn();
		$query="UPDATE	interface_vitros_control_examen_detalle
						SET			sw_estado_examen = '5',
										error_vitros = '$error'
						WHERE		muestra_id = '$muestra_id'";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al actualizar error WS";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		return true;
	}//fin function ActualizaErrorWs
	
	/**
	* Recupera la informacion que sera enviada  a la vitros de la base de Datos y la guarda en un array
	* Crea el archivo en formato Vitros
	* Guarda el archivo fisicamente
	* Envia el archivo a travez del WebService
	* @access public
	* @return null
	*/
	function CrearArchivo(){
		//print_r($_REQUEST);
		//echo "<br>------------------<br>";
		$numero_cumplimiento=$_REQUEST['numero_cumplimiento'];
		$fecha_cumplimiento=$_REQUEST['fecha_cumplimiento'];
		$departamento=$_REQUEST['departamento'];
		$tipo_id_paciente=$_REQUEST['tipo_id_paciente'];
		$paciente_id=$_REQUEST['paciente_id'];
		$nombre=$_REQUEST['nombre'];
		list($dbconn) = GetDBconn();
		//se obtienen los datos necesarios para la creacion del archivo
		//retorna el codigo de la copa numerico
		$query="SELECT 	CASE WHEN b.bandeja_id='0' THEN '' ELSE c.descripcion_bandeja END,
										b.bandeja_id,
										b.muestra_id,
										b.fluido_id,
										b.estado,
										CASE WHEN f.copa_id='0' THEN ' ' ELSE f.copa_equivalencia END,
										b.dilucion,
										e.codigo_vitros,
										b.nombre_archivo,
										e.numero_orden_id
						FROM		interface_vitros_control_examen b,
										interface_vitros_bandeja c,
										interface_vitros_cargo d,
										interface_vitros_control_examen_detalle e,
										interface_vitros_copas f
						WHERE		e.sw_estado_examen = '1' AND
										b.bandeja_id=c.bandeja_id AND
										e.codigo_vitros = d.codigo_vitros AND
										b.interface_vitros_control_examen_id = e.interface_vitros_control_examen_id AND
										b.posicion_copa = f.copa_id AND
										e.codigo_cups = d.codigo_cups
						
						UNION
						
						SELECT 	CASE WHEN b.bandeja_id='0' THEN '' ELSE c.descripcion_bandeja END,
										b.bandeja_id,
										b.muestra_id,
										b.fluido_id,
										b.estado,
										CASE WHEN f.copa_id='0' THEN ' ' ELSE f.copa_equivalencia END,
										b.dilucion,
										e.codigo_vitros,
										b.nombre_archivo,
										e.numero_orden_id
						FROM		interface_vitros_control_examen b,
										interface_vitros_bandeja c,
										interface_vitros_cargo_especial d,
										interface_vitros_control_examen_detalle e,
										interface_vitros_copas f
						WHERE		e.sw_estado_examen = '1' AND
										b.bandeja_id=c.bandeja_id AND
										e.codigo_vitros = d.codigo_vitros AND
										b.interface_vitros_control_examen_id = e.interface_vitros_control_examen_id AND
										b.posicion_copa = f.copa_id AND
										e.codigo_cups = d.codigo_cups
						ORDER BY 9,3
						";

					
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al consultar en CrearArchivo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		
		while(!$result->EOF){
				$var[$result->fields[8]][]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
		}
		$result->Close();

  //PRINT_R($var); EXIT;
		
		if($var==null){
			$this->MuestraError("No hay examenes para enviar a la Vitros");
		}
		else{
			$cadena='';
			$this->ConstruyeCadena($var,$tipo_id_paciente,$paciente_id); //construye la cadena 
			//Se actualiza el estado del examen
			
			$this->Consultar_Cumplimiento($_REQUEST['numero_cumplimiento'],
																		$_REQUEST['fecha_cumplimiento'],$_REQUEST['departamento'],
																		$_REQUEST['tipo_id_paciente'], $_REQUEST['paciente_id'],
																		$_REQUEST['nombre'], $_REQUEST['edad_paciente']);
		}//fin else
		
		return true;
	}//fin function CrearArchivo
	
	/**
	* Actualiza el estado de un examen en interface_vitros_control_examen_detalle
	* para saber cuando se han enviado los examenes a la vitros o llego el resultado
	* @param		$muestra_id
	* @param		$numero_orden_id
	* @param		$codigo_vitros
	* @return		boolean
	*/
	function ActualizaEstado($muestra_id,$numero_orden_id,$codigo_vitros){
		if($codigo_vitros == "'")
		{
			$codigo_vitros=addslashes($codigo_vitros);
		}
		list($dbconn) = GetDBconn();

			$query="UPDATE interface_vitros_control_examen_detalle
							SET sw_estado_examen='2'
							WHERE	muestra_id= '".$muestra_id."' AND
										numero_orden_id= '".$numero_orden_id."' AND
										codigo_vitros= '".$codigo_vitros."' AND
										sw_estado_examen='1'";
			$this->ControlQuery("InsertaHcResultados-hc_resultados_sistema ".$query);
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al actualizar en CrearArchivo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					echo "error al actualizar";
					return false;
			}

		$result->Close();
		return true;
	}
	/**
	* Caso de eliminacion de un examen de la lista de examenes preseleccionados para ser enviados a la Vitros.
	* los libera para ser trabajados en otra ubicacion en la maquina o manalmente
	* @access public
	* @return null
	*/
	function EliminaExamenListaVitros(){
		$numero_orden_id=$_REQUEST[numero_orden_id];
		$paciente_id=$_REQUEST['paciente_id'];
		$tipo_id_paciente=$_REQUEST['tipo_id_paciente'];
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$query="SELECT DISTINCT a.interface_vitros_control_examen_id,
										a.muestra_id
						FROM  interface_vitros_control_examen_detalle a,
									interface_vitros_control_examen b
						WHERE a.numero_orden_id = $numero_orden_id AND
									b.tipo_id_paciente = '$tipo_id_paciente' AND
									b.paciente_id = '$paciente_id'
						";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al eliminar interface_vitros_control_examen 1";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
		}
		$control_id=$result->fields[0];
		$muestra_id=$result->fields[1];
		
		$query="DELETE FROM  interface_vitros_control_examen_detalle
						WHERE numero_orden_id=$numero_orden_id AND
									interface_vitros_control_examen_id = $control_id
						";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al eliminar en interface_vitros_control_examen_detalle 1";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
		}
		
		$query="SELECT COUNT (numero_orden_id)
						FROM interface_vitros_control_examen_detalle
						WHERE interface_vitros_control_examen_id = $control_id";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al contar en interface_vitros_control_examen_detalle 1";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
		}
		$cont=$result->fields[0];
	
		if($cont==0){
			$query="DELETE FROM  interface_vitros_control_examen
							WHERE interface_vitros_control_examen_id = $control_id AND
										paciente_id = '$paciente_id' AND
										tipo_id_paciente = '$tipo_id_paciente'
							";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al eliminar en interface_vitros_control_examen 2 ";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
			}
		}
		//$this->ActualizaTomado($muestra_id,$numero_orden_id,'0');
		$dbconn->CommitTrans();
		$result->Close();
		
		$this->Consultar_Cumplimiento($_REQUEST['numero_cumplimiento'],
			$_REQUEST['fecha_cumplimiento'],$_REQUEST['departamento'],
		  $_REQUEST['tipo_id_paciente'], $_REQUEST['paciente_id'],
			$_REQUEST['nombre'], $_REQUEST['edad_paciente']);
		
		
		return $this->salida;
	}//fin function EliminaExamenListaVitros
	
	/**					ANALIZAR SI SE NECESITA
	* Consulta el error generado por la vitros
	* @access public
	* @return boolean
	*/
	function ConsultaError($respuesta){
		list($dbconn) = GetDBconn();
		$query="SELECT descripcion_error
						FROM	interface_vitros_error_transmision
						WHERE error_id='$respuesta'
		";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al consultar en ConsultaError";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		$error=$result->fields[0];
		$result->Close();
		//echo "error ".$error."<br>";
		if($error!=null){ return $error;}
		else {return true;}
	}//fin function ConsultaError
	
	/**        OJO MODIFICAR GRAN PARTE DE ESTO SE MENEJA EN EL WS
	*
	* @access public
	* @var $respuesta	Contiene informacion de error generado por la vitros o por el kermit
	*	@var						durante el proceso de transmision de archivos
	* @var $file			Nombre del archivo generado por la vitros
	* @var $cadena		Informacion enviada por la vitros
	* @return array / false
	*/
	//este control se realiza en el momento del envio el respo pasa al webservice
	function ControlTransmision($respuesta,$file='',$cadena=''){
		//echo "<br>Se recibe => ".$respuesta."<br>";
		$res=explode('/',$respuesta);
		//print_r($res);
 		$min_E_vitros=ModuloGetVar('app','Os_ListaTrabajoVitros','min_E_vitros');
 		$max_E_vitros=ModuloGetVar('app','Os_ListaTrabajoVitros','max_E_vitros');
 		$min_E_trans=ModuloGetVar('app','Os_ListaTrabajoVitros','min_E_trans');
 		$max_E_trans=ModuloGetVar('app','Os_ListaTrabajoVitros','max_E_trans');
		
// 			$min_E_vitros='0000';
// 			$max_E_vitros='0015';
// 			$min_E_trans='0051';
// 			$max_E_trans='0059';
		
		//if($res>=0){
		if(($res[0]>=$min_E_vitros)&&($res[0]<=$max_E_trans)&&($res[0]>=$min_E_trans)&&($res[0]<=$max_E_trans)){
			//echo "<br>Error de trasnmiison";
			$this-> ActualizaErrorVitros($res[0],$res[1]);
		}

		return true;
	}//fin function ControlTransmision
	
	/**
	*	Actualiza en error generado por la vitros y lo actualiza en la base de datos
	* @param	$respuesta	respuesta generada	
	* @param	$file				archivo al que hay que aplicarle el codigo de error
	*/
	function ActualizaErrorVitros($respuesta,$file){
		//echo "<br>file1: ".$file;
		$file=substr($file,1);//quito la S
		$file=(int)$file;
		//echo "<br>file2: ".$file;
		list($dbconn) = GetDBconn();
		$query="UPDATE  interface_vitros_control_examen_detalle
						SET			error_vitros='$respuesta',
										sw_estado_examen = '5'
						WHERE interface_vitros_control_examen.nombre_archivo = $file AND
									interface_vitros_control_examen.interface_vitros_control_examen_id = interface_vitros_control_examen_detalle.interface_vitros_control_examen_id
		";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al actualizar error vitros";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		$result->Close();
	}//fin ActualizaErrorVitros
	/**
	* Retorna la copa que esta disponible segun la bandeja seleccionada
	* @param $bandeja Bandeja seleccionada por el usuario
	* @return array	Vector con la informacion de las copas disponibles
	*/
	function RetornaCopaDisponible($bandeja){
			list($dbconn) = GetDBconn();
			$pos=0;
			$salida='';
			if(($bandeja!=null)&&($bandeja!=-0)){
				$query="SELECT 	posicion_copa
								FROM 		interface_vitros_control_examen
								WHERE 	bandeja_id='$bandeja'";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0){
						//echo "<br>Error BD " . $dbconn->ErrorMsg();
						return false;
				}
				while(!$result->EOF)
				{
						$var[]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
				}
				$salida='';
				for($i=0;$i<10;$i++){
					$salida[$i]=$i+1;
				}
				//unset($salida[1]);
				for($j=0;$j<sizeof($var);$j++){
						unset($salida[$var[$j][posicion_copa]]);
				}
			$result->Close();
			}
			
			return $salida;
		}//fin function RetornaCopaDisponible
		
		/**
		* Consulta el estado de los examenes
		*/
		function Consulta_Vitros(){
			$this->Consultar_Estado_Os_Vitros($_REQUEST['numero_cumplimiento'],
			$_REQUEST['fecha_cumplimiento'],$_REQUEST['departamento'],
		  $_REQUEST['tipo_id_paciente'], $_REQUEST['paciente_id'],
			$_REQUEST['nombre'], $_REQUEST['edad_paciente']);
			return $this->salida;
		}//fin Consulta_Vitros
		
				/**
		* Consulta el estado de los examenes
		*/
		function Consulta_Ordenes(){
			$this->Consultar_Ordenes_Vitros($_REQUEST['numero_cumplimiento'],
			$_REQUEST['fecha_cumplimiento'],$_REQUEST['departamento'],
		  $_REQUEST['tipo_id_paciente'], $_REQUEST['paciente_id'],
			$_REQUEST['nombre'], $_REQUEST['edad_paciente']);
			return $this->salida;
		}//fin Consulta_Vitros
		/**
		*%%validar% sw_estado_examen 
		* Consulta el estado de las muestras en la vitros
		* @return vector
		*/
		function ConsultaEstadoOS(){
			list($dbconn) = GetDBconn();

			$query="SELECT 	DISTINCT a.numero_cumplimiento,
											b.numero_orden_id,
											a.muestra_id,
											a.nombre_archivo,
											a.bandeja_id,
											a.posicion_copa,
											CASE WHEN c.codigo_cups IS NULL
											THEN d.codigo_cups
											ELSE c.codigo_cups END  as cargo,
											CASE WHEN c.nombre_prueba IS NULL
											THEN d.nombre_prueba
											ELSE c.nombre_prueba END,
											b.sw_estado_examen as estado_examen,
											f.descripcion_error
							FROM 		interface_vitros_control_examen a,
											interface_vitros_control_examen_detalle b
											LEFT JOIN interface_vitros_cargo c 
											ON (b.codigo_cups = c.codigo_cups AND 
											b.codigo_vitros = c.codigo_vitros )
											LEFT JOIN interface_vitros_cargo_especial d 
											ON (b.codigo_cups = d.codigo_cups_real AND 
											b.codigo_vitros = d.codigo_vitros ),
											interface_vitros_error_transmision f
							WHERE		a.interface_vitros_control_examen_id = b.interface_vitros_control_examen_id AND
											b.error_vitros = f.error_id  
							ORDER BY  a.bandeja_id,
											a.posicion_copa
											";
											//sw_estado_examen =1 or = 2
											//a.muestra_id = b.muestra_id  AND
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
					//echo "<br>Error BD " . $dbconn->ErrorMsg();
					return false;
			}
			while(!$result->EOF)
			{
					$var[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
			}
			return $var;
		}//fin ConsultaEstadoOS
		
		/**
		* Se encarga de reenviar un archivo despues de generarase un error
		*/
		function ReenviaArchivo(){
			$file=$_REQUEST['file'];
			$path=GetVarConfigAplication('DIR_SIIS')."classes/nusoap/lib/";
			require_once($path."nusoap.php" ); 
			if(!IncludeClass('Vitros','Vitros')){
				//echo "ERROR AL INCLUIR LA CLASE :S";
				return false;
			}
			if(class_exists('Vitros')){ 
				$vitros = new Vitros();
				$res=$vitros->ReenviaArchivo($file);
			}
			$this->Consultar_Cumplimiento($_REQUEST['numero_cumplimiento'],
			$_REQUEST['fecha_cumplimiento'],$_REQUEST['departamento'],
		  $_REQUEST['tipo_id_paciente'], $_REQUEST['paciente_id'],
			$_REQUEST['nombre'], $_REQUEST['edad_paciente']);
				return true;
		} //fin 
		
		
		/**
		* Concatena el numero_cumplimiento y la fecha_cumplimiento para crear le numero
		*  de cumplimiento que debe ser visto por el medico y que servira de guia para
		* el controld e los examenes, en el formato que se configuro en la tabla departamentos
		*  para cada uno d elos departamentos existentes en la empresa
		* @param $fecha_cumplimiento
		* @param $numero_cumplimiento
		* @param $departamento
		* @return $cumplimiento
		*/
		function ConvierteCumplimiento($fecha_cumplimiento,$numero_cumplimiento,$departamento){
			list($dbconn) = GetDBconn();
			$query="SELECT	formato_cumplimiento
							FROM		departamentos
							WHERE		departamento = '$departamento'";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
					//echo "<br>Error BD " . $dbconn->ErrorMsg();
					return false;
			}
			$res=$result->fields[0];
			$result->Close();
			if($res=='0'){
				$fecha=substr(str_replace("-","",$fecha_cumplimiento),2);
			}elseif($res=='1'){
				$fecha=substr(str_replace("-","",$fecha_cumplimiento),2,-2);
			}elseif($res=='2'){
				$fecha=substr(str_replace("-","",$fecha_cumplimiento),2,-4);
			}
// 			echo "<br>-->".$fecha;exit;
			$cumplimiento=$fecha."-".$numero_cumplimiento;
			return $cumplimiento;
		}//fin ConvierteCumplimiento
		/**
		*	Determina si al cargo consultado se le debe pedir el volumen del fluido
		* 
		* @param $cargo	String
		* @return $res	Char
		*/
		function DeterminaPedirVolumen($cargo){
			list($dbconn) = GetDBconn();
			 $query="SELECT	sw_volumen
							FROM		interface_vitros_cargo
							WHERE		codigo_cups = '$cargo'";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
					//echo "<br>Error BD " . $dbconn->ErrorMsg();
					return false;
			}
			$res=$result->fields[0];
			$result->Close();
			return $res;
		}//fin DeterminaPedirVolumen
			
			
				/**
		*%%validar% sw_estado_examen 
		* Consulta el estado de las muestras en la vitros
		* @return vector
		*/
		function ConsultaOrdenesVitros(){
			list($dbconn) = GetDBconn();

			$query="SELECT 	DISTINCT a.numero_cumplimiento,
											a.fecha_cumplimiento,
											b.numero_orden_id,
											a.tipo_id_paciente ,
											a.paciente_id ,
											a.muestra_id,
											a.nombre_archivo,
											a.bandeja_id,
											a.posicion_copa,
											CASE WHEN c.codigo_cups IS NULL
											THEN d.codigo_cups
											ELSE c.codigo_cups END  as cargo,
											CASE WHEN c.nombre_prueba IS NULL
											THEN d.nombre_prueba
											ELSE c.nombre_prueba END,
											b.sw_estado_examen as estado_examen,
											f.descripcion_error
							FROM 		interface_vitros_control_examen a,
											interface_vitros_control_examen_detalle b
											LEFT JOIN interface_vitros_cargo c 
											ON (b.codigo_cups = c.codigo_cups AND 
											b.codigo_vitros = c.codigo_vitros )
											LEFT JOIN interface_vitros_cargo_especial d 
											ON (b.codigo_cups = d.codigo_cups_real AND 
											b.codigo_vitros = d.codigo_vitros ),
											interface_vitros_error_transmision f
							WHERE		a.interface_vitros_control_examen_id = b.interface_vitros_control_examen_id AND
											b.error_vitros = f.error_id  
							ORDER BY  a.bandeja_id,
											a.posicion_copa
											";
											//sw_estado_examen =1 or = 2
											//a.muestra_id = b.muestra_id  AND
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
					//echo "<br>Error BD " . $dbconn->ErrorMsg();
					return false;
			}
			while(!$result->EOF)
			{
					$var[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
			}
			return $var;
		}//fin ConsultaEstadoOS
			/**
		*
		*/
			function ErrorSistema($mensaje){
				$path=GetVarConfigAplication('DIR_SIIS')."Interface_Files/Vitros250/";
				$nombre_archivo=$path.'errorsistema.txt';
				if(!$fichero = fopen($nombre_archivo,'a')){
					return 'errorF';
				}
				fputs($fichero,$mensaje."\n");
				fclose($fichero);
				return true;
				}
		/**
		*
		*/
		function ControlQuery($mensaje){
			$path=GetVarConfigAplication('DIR_SIIS')."Interface_Files/Vitros250/";
			$nombre_archivo=$path.'controlquery.txt';
			if(!$fichero = fopen($nombre_archivo,'a')){
				return 'errorF';
			}
			fputs($fichero,$mensaje."\n");
			fclose($fichero);
			return true;
		}
}//fin clase user

?>
