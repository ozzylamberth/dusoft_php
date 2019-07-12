<?
//ESTE ES EL QUE VA A QUED
/**
* Submodulo de Reserva de Sangre.
*
* Submodulo para manejar la reserva y/o cruzada de sangre.
* @author Claudia Liliana Zuñiga Cañon <claudia_zc@hotmail.com
* @version 1.0
* @package SIIS
* $Id: hc_SolicitudPatologica.php,v 1.7 2006/12/19 21:00:15 jgomez Exp $
*/


class SolicitudPatologica extends hc_classModules
{
  var $limit;
  var $conteo;
//clzc
	function SolicitudPatologica()
	{
	  $this->limit=GetLimitBrowser();
		return true;
	}


/**
* Esta función retorna los datos de concernientes a la version del submodulo
* @access private
*/

// 	function GetVersion()
// 	{
// 		$informacion=array(
// 		'version'=>'1',
// 		'subversion'=>'0',
// 		'revision'=>'0',
// 		'fecha'=>'01/27/2005',
// 		'autor'=>'LORENA ARAGON G.',
// 		'descripcion_cambio' => '',
// 		'requiere_sql' => false,
// 		'requerimientos_adicionales' => '',
// 		'version_kernel' => '1.0'
// 		);
// 		return $informacion;
// 	}

/**
* Esta función verifica si este submodulo fue utilizado para la atencion de un paciente.
*
* @access private
* @return text Datos HTML de la pantalla.
*/

	function GetEstado()
	{
    return true;
	}



/**
* GetReporte_Html - Esta metodo captura los datos de la impresión de la Historia Clinica.
*
* @return text Datos HTML de la pantalla.
*/

	function GetReporte_Html()
	{
		$imprimir=$this->frmHistoria();
		if($imprimir==false)
		{
			return true;
		}
		return $imprimir;
	}

/**
* GetForma - Esta función retorna la presentación del submodulo (consulta o inserción).
*
* @return text Datos HTML de la pantalla.*
*/

	function GetForma()
	{

		$pfj=$this->frmPrefijo;
		if(empty($_REQUEST['accion'.$pfj])){
	    $this->frmForma();
		}elseif($_REQUEST['accion'.$pfj]=='insertar'){
		  if($_REQUEST['buscarDiagn'.$pfj]){
				$this->FormaBuscadorDiagnostico($_REQUEST['Solicitud'.$pfj],$_REQUEST['tratamientos'.$pfj],$_REQUEST['hallazgos'.$pfj],$_REQUEST['observaciones'.$pfj],$_REQUEST['quirofano'.$pfj]);
				return true;
			}
      if($_REQUEST['buscarTejido'.$pfj]){
        $this->BucadorTejidosPatologicosBuscador($_REQUEST['Solicitud'.$pfj],$_REQUEST['tratamientos'.$pfj],$_REQUEST['hallazgos'.$pfj],$_REQUEST['observaciones'.$pfj],$_REQUEST['quirofano'.$pfj]);
				return true;
			}
			if($this->InsertDatos()==true){
			  $this->frmForma();
				return true;
		  }
		}elseif($_REQUEST['accion'.$pfj]=='diagnostico'){
      if($_REQUEST['Salir'.$pfj]){
        $this->frmForma($_REQUEST['Solicitud'.$pfj],$_REQUEST['tratamientos'.$pfj],$_REQUEST['hallazgos'.$pfj],$_REQUEST['observaciones'.$pfj],$_REQUEST['quirofano'.$pfj]);
				return true;
			}
			$this->FormaBuscadorDiagnostico($_REQUEST['Solicitud'.$pfj],$_REQUEST['tratamientos'.$pfj],$_REQUEST['hallazgos'.$pfj],$_REQUEST['observaciones'.$pfj],$_REQUEST['quirofano'.$pfj]);
			return true;
		}elseif($_REQUEST['accion'.$pfj]=='tejido'){
      if($_REQUEST['Salir'.$pfj]){
        $this->frmForma($_REQUEST['Solicitud'.$pfj],$_REQUEST['tratamientos'.$pfj],$_REQUEST['hallazgos'.$pfj],$_REQUEST['observaciones'.$pfj],$_REQUEST['quirofano'.$pfj]);
				return true;
			}
			$this->BucadorTejidosPatologicosBuscador($_REQUEST['Solicitud'.$pfj],$_REQUEST['tratamientos'.$pfj],$_REQUEST['hallazgos'.$pfj],$_REQUEST['observaciones'.$pfj],$_REQUEST['quirofano'.$pfj]);
			return true;
		}elseif($_REQUEST['accion'.$pfj]=='SeleccionDiagnostico'){
		  if($_REQUEST['elimina'.$pfj]){
        unset($_SESSION['PATOLOGIA']['DIAGNOSTICOS'][$_REQUEST['codigoDiagnostico'.$pfj]]);
			}else{
        $_SESSION['PATOLOGIA']['DIAGNOSTICOS'][$_REQUEST['codigoDiagnostico'.$pfj]]=$_REQUEST['nombreDiagnostico'.$pfj];
			}
			$this->frmForma($_REQUEST['Solicitud'.$pfj],$_REQUEST['tratamientos'.$pfj],$_REQUEST['hallazgos'.$pfj],$_REQUEST['observaciones'.$pfj],$_REQUEST['quirofano'.$pfj]);
			return true;
		}elseif($_REQUEST['accion'.$pfj]=='SeleccionTejido'){
		  if($_REQUEST['elimina'.$pfj]){
        unset($_SESSION['PATOLOGIA']['TEJIDOS'][$_REQUEST['codigoTejido'.$pfj]]);
			}else{
        $_SESSION['PATOLOGIA']['TEJIDOS'][$_REQUEST['codigoTejido'.$pfj]]=$_REQUEST['nombreTejido'.$pfj];
			}
			$this->frmForma($_REQUEST['Solicitud'.$pfj],$_REQUEST['tratamientos'.$pfj],$_REQUEST['hallazgos'.$pfj],$_REQUEST['observaciones'.$pfj],$_REQUEST['quirofano'.$pfj]);
			return true;
		}
		return $this->salida;
	}
/**
* InsertDatos - Esta función inserta los datos del submodulo en la base de datos.
*
* @return boolean
*/
	function InsertDatos()
	{
		$pfj=$this->frmPrefijo;
	  list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
    $query="SELECT b.nombre FROM profesionales_usuarios a,profesionales b WHERE a.usuario_id='".UserGetUID()."' AND a.tipo_tercero_id=b.tipo_id_tercero AND a.tercero_id=b.tercero_id";
		$result = $dbconn->Execute($query);
		$responsableSolicitud=$result->fields[0];
    $query="SELECT nextval('patologias_solicitudes_patologia_solicitud_id_seq')";
    $result = $dbconn->Execute($query);
		$SolicitudId=$result->fields[0];
		if($_REQUEST['quirofano'.$this->frmPrefijo]==-1){
      $quirofano='NULL';
		}else{
      $quirofano="'".$_REQUEST['quirofano'.$this->frmPrefijo]."'";
		}
		$query="INSERT INTO patologias_solicitudes(patologia_solicitud_id,evolucion_id,tipo_id_paciente,paciente_id,
		quirofano,ubicacion_paciente,departamento,responsable_solicitud,tratamientos_efectuados,hallazgos,observaciones,
		usuario_id,fecha_registro,solicitud,ingreso)VALUES('$SolicitudId','".$this->evolucion."','".$this->tipoidpaciente."',
		'".$this->paciente."',$quirofano,'','".$this->departamento."','$responsableSolicitud','".$_REQUEST['tratamientos'.$this->frmPrefijo]."',
		'".$_REQUEST['hallazgos'.$this->frmPrefijo]."','".$_REQUEST['observaciones'.$this->frmPrefijo]."',
		'".UserGetUID()."','".date("Y-m-d H:i:s")."','".$_REQUEST['Solicitud'.$this->frmPrefijo]."','".$this->ingreso."')";
    $result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo en la tabla pacientes";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}else{
		  $diags=$_SESSION['PATOLOGIA']['DIAGNOSTICOS'];
      foreach($diags as $codigo=>$nombreDiag){
        $query="INSERT INTO patologias_solicitudes_diagnosticos(patologia_solicitud_id,diagnostico_id)
				VALUES('$SolicitudId','$codigo')";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo en la tabla pacientes";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
			}
			$tejidos=$_SESSION['PATOLOGIA']['TEJIDOS'];
      foreach($tejidos as $codigo=>$nombreTeji){
        $query="INSERT INTO patologias_solicitudes_detalle(patologia_solicitud_id,tejido_id,estado)
				VALUES('$SolicitudId','$codigo','1')";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo en la tabla pacientes";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
			}
			$dbconn->CommitTrans();
		}
		unset($_SESSION['PATOLOGIA']['TEJIDOS']);
		unset($_SESSION['PATOLOGIA']['DIAGNOSTICOS']);
    unset($_REQUEST['solicitud']);
    unset($_REQUEST['tratamientos']);
		unset($_REQUEST['hallazgos']);
		unset($_REQUEST['observaciones']);
		unset($_REQUEST['quirofano']);
		 $this->RegistrarSubmodulo($this->GetVersion());            
    return true;
	}

/**
* HallarDiagnosticosPatologia - Diagnosticos de la base de datos
*
* @return vector
* @param $codigoBus Filtro de busqueda por el codigo del diagnostico
* @param $procedimientoBus Filtro de busqueda por la descripcion del diagnostico
*/

	function HallarDiagnosticosPatologia($codigoBus,$procedimientoBus){

		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
    $query="SELECT diagnostico_id,diagnostico_nombre FROM diagnosticos";
    $query1="SELECT count(*) FROM diagnosticos";
		if($codigoBus){
      $query.=" WHERE diagnostico_id LIKE '%$codigoBus%'";
			$query1.=" WHERE diagnostico_id LIKE '%$codigoBus%'";
			$ya=1;
		}
		if($procedimientoBus){
      if($ya==1){
        $query.=" AND diagnostico_nombre LIKE '%".strtoupper($procedimientoBus)."%'";
			  $query1.=" AND diagnostico_nombre LIKE '%".strtoupper($procedimientoBus)."%'";
			}else{
        $query.=" WHERE diagnostico_nombre LIKE '%".strtoupper($procedimientoBus)."%'";
			  $query1.=" WHERE diagnostico_nombre LIKE '%".strtoupper($procedimientoBus)."%'";
			}
		}
		if(empty($_REQUEST['conteo'.$pfj])){
			$result = $dbconn->Execute($query1);
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			list($this->conteo)=$result->fetchRow();
		}else{
			$this->conteo=$_REQUEST['conteo'.$pfj];
		}
		if(!$_REQUEST['Of'.$pfj]){
			$Of='0';
		}else{
			$Of=$_REQUEST['Of'.$pfj];
      if($Of > $this->conteo){
				$Of=0;
				$_REQUEST['Of'.$pfj]=0;
				$_REQUEST['paso1'.$pfj]=1;
			}
		}
		$query.=" LIMIT " . $this->limit . " OFFSET $Of";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			while(!$result->EOF){
				$var[]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
   	if($this->conteo==='0'){
		  $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
			return false;
		}
		$result->Close();
		return $var;
	}

/**
* HallarTejidosPatologia - tipos de Tejidos existentes en la base de datos
*
* @return vector
* @param $codigoBus Filtro de busqueda por el codigo del tejido
* @param $procedimientoBus Filtro de busqueda por la descripcion del tejido
*/

	function HallarTejidosPatologia($codigoBus,$procedimientoBus){
    $pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
    $query="SELECT tejido_id,descripcion FROM tipos_tejidos";
    $query1="SELECT count(*) FROM tipos_tejidos";
		if($codigoBus){
      $query.=" WHERE tejido_id LIKE '%$codigoBus%'";
			$query1.=" WHERE tejido_id LIKE '%$codigoBus%'";
			$ya=1;
		}
		if($procedimientoBus){
      if($ya==1){
        $query.=" AND descripcion LIKE '%".strtoupper($procedimientoBus)."%'";
			  $query1.=" AND descripcion LIKE '%".strtoupper($procedimientoBus)."%'";
			}else{
        $query.=" WHERE descripcion LIKE '%".strtoupper($procedimientoBus)."%'";
			  $query1.=" WHERE descripcion LIKE '%".strtoupper($procedimientoBus)."%'";
			}
		}
    if(empty($_REQUEST['conteo'.$pfj])){
			$result = $dbconn->Execute($query1);
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			list($this->conteo)=$result->fetchRow();
		}else{
			$this->conteo=$_REQUEST['conteo'.$pfj];
		}
		if(!$_REQUEST['Of'.$pfj]){
			$Of='0';
		}else{
			$Of=$_REQUEST['Of'.$pfj];
      if($Of > $this->conteo){
				$Of=0;
				$_REQUEST['Of'.$pfj]=0;
				$_REQUEST['paso1'.$pfj]=1;
			}
		}
		$query.=" LIMIT " . $this->limit . " OFFSET $Of";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			while(!$result->EOF){
				$var[]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
   	if($this->conteo==='0'){
		  $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
			return false;
		}
		$result->Close();
		return $var;
	}

/**
* DatosCirugiaPaciente - funcion que retorna los datos de la cirugia de la cual proviene la patologia
*
* @return vector
*/

	function DatosCirugiaPaciente(){

    list($dbconn) = GetDBconn();
    $query="SELECT dcir.quirofano_id,dcirs.diagnostico_id,proced.hallazgos_quirurgicos,diag.diagnostico_nombre
		FROM ingresos ing,hc_evoluciones evol,hc_notas_operatorias_cirugias dcir,hc_notas_operatorias_cirujanos dcirs
		LEFT JOIN diagnosticos diag ON(dcirs.diagnostico_id=diag.diagnostico_id),
		profesionales_usuarios profus,hc_notas_operatorias_procedimientos proced
		WHERE ing.tipo_id_paciente='".$this->tipoidpaciente."' AND ing.paciente_id='".$this->paciente."' AND ing.ingreso=evol.ingreso AND
		evol.evolucion_id=dcir.evolucion_id AND dcir.hc_nota_operatoria_cirugia_id=dcirs.hc_nota_operatoria_cirugia_id AND
		dcirs.tipo_id_cirujano=profus.tipo_tercero_id AND dcirs.cirujano_id=profus.tercero_id AND profus.usuario_id='".UserGetUID()."' AND
		dcirs.hc_nota_operatoria_cirugia_id=proced.hc_nota_operatoria_cirugia_id AND dcirs.tipo_id_cirujano=proced.tipo_id_cirujano AND dcirs.cirujano_id=proced.cirujano_id";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
				while(!$result->EOF){
					$var[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
		}
		return $var;
	}

/**
* TotalQuirofanos - funcion que retorna los quirofanos de la base de datos
*
* @return vector
*/

	function TotalQuirofanos(){
		list($dbconn) = GetDBconn();
		$query = "SELECT quirofano,descripcion FROM qx_quirofanos";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
			  while (!$result->EOF) {
					$vars[$result->fields[0]]=$result->fields[1];
					$result->MoveNext();
				}
			}
		}
		$result->Close();
 		return $vars;
	}

/**
* SolicitudesPatologicasHC - funcion que retorna las solicitudes patologias desde la historia clinica de un mismo paciente en un mismo ingreso
*
* @return vector
*/

	function SolicitudesPatologicasHC(){
    list($dbconn) = GetDBconn();
		$query = "SELECT date(a.fecha_registro) as fecha,a.patologia_solicitud_id,a.quirofano,b.descripcion as nomquirofano,a.tratamientos_efectuados,a.hallazgos,a.observaciones,a.solicitud
		FROM patologias_solicitudes a
		LEFT JOIN qx_quirofanos b ON(a.quirofano=b.quirofano)
		WHERE a.tipo_id_paciente='".$this->tipoidpaciente."' AND
		a.paciente_id='".$this->paciente."' AND a.ingreso='".$this->ingreso."' AND
		a.usuario_id='".UserGetUID()."' ORDER BY a.fecha_registro DESC";
		//c.tejido_id,d.descripcion as nomtejido,e.diagnostico_id,f.diagnostico_nombre
		//AND a.patologia_solicitud_id=c.patologia_solicitud_id AND c.tejido_id=d.tejido_id
		//LEFT JOIN patologias_solicitudes_diagnosticos e ON(a.patologia_solicitud_id=e.patologia_solicitud_id)
		//LEFT JOIN diagnosticos f ON(e.diagnostico_id=f.diagnostico_id)
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
				while(!$result->EOF){
					$var[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
		}
		//$result->Close();
 		return $var;
	}

/**
* DiagnosticosPatologicasHC - funcion que retorna las solicitudes patologias desde la historia clinica de un mismo paciente en un mismo ingreso
*
* @return vector
* @param $patologia_solicitud_id - numero unico que identifica la Solicitud
*/

	function DiagnosticosPatologicasHC($patologia_solicitud_id){
    list($dbconn) = GetDBconn();
		$query = "SELECT b.diagnostico_nombre
		FROM patologias_solicitudes_diagnosticos a,diagnosticos b
		WHERE a.patologia_solicitud_id='$patologia_solicitud_id' AND
		a.diagnostico_id=b.diagnostico_id";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
				while(!$result->EOF){
					$var[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
		}
		//$result->Close();
 		return $var;
	}

/**
* TejidosPatologicasHC - funcion que retorna los tejidos de la base de datos
*
* @return vector
* @param $patologia_solicitud_id - numero unico que identifica la Solicitud
*/

	function TejidosPatologicasHC($patologia_solicitud_id){
	  list($dbconn) = GetDBconn();
	  $query="SELECT b.descripcion
		FROM patologias_solicitudes_detalle a,tipos_tejidos b
		WHERE a.patologia_solicitud_id='$patologia_solicitud_id' AND a.tejido_id=b.tejido_id";
    $result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
				while(!$result->EOF){
					$var[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
		}
		//$result->Close();
 		return $var;
	}

/**
* GetConsulta - funcion que hace el llamado de la funcion que muecstra la consulta de registros insertados en la base de datos
*
* @return text
*/

	function GetConsulta()
	{
    	$accion='accion'.$pfj;
		if(empty($_REQUEST[$accion]))
		{
			$this->frmConsulta();
		}
		return $this->salida;
	}


}
?>
