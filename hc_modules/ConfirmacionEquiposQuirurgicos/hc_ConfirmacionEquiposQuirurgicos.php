<?php

/**
* Submodulo de ConfirmacionEquiposQuirurgicos.
*
* Submodulo para manejar las notas de los Hallazgos de la Cirugia.
* @author Tizziano Perea <tizzianop@gmail.com>
* @version 1.0
* @package SIIS
* $Id: hc_ConfirmacionEquiposQuirurgicos.php,v 1.1 2006/03/22 19:59:58 lorena Exp $
*/


/**
* ConfirmacionEquiposQuirurgicos
*
* Clase para accesar los metodos privados de la clase de presentación, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserción y la consulta del
* submodulo de ConfirmacionEquiposQuirurgicos.
*/

class ConfirmacionEquiposQuirurgicos extends hc_classModules
{

/**
* Esta función Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/

	var $limit;
	var $conteo;


	function ConfirmacionEquiposQuirurgicos()
	{
		$this->limit=GetLimitBrowser();
		$this->salida = '';
		return true;
	}


/**
* Esta función retorna los datos de concernientes a la version del submodulo
* @access private
*/

	function GetVersion()
	{
		$informacion=array(
		'version'=>'1',
		'subversion'=>'0',
		'revision'=>'0',
		'fecha'=>'17/27/2005',
		'autor'=>'LORENA ARAGON',
		'descripcion_cambio' => '',
		'requiere_sql' => false,
		'requerimientos_adicionales' => '',
		'version_kernel' => '1.0'
		);
		return $informacion;
	}


/**
* Esta función verifica si este submodulo fue utilizado para la atencion de un paciente.
*
* @access private
* @return text Datos HTML de la pantalla.
*/

	function GetEstado()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$query="SELECT count(*) as registros
			   FROM hc_equipos_qx_moviles_confirmados
                  WHERE evolucion_id=".$this->evolucion.";";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$estadoMovil=$resulta->GetRowAssoc($ToUpper = false);
			$query="SELECT count(*) as registros
				FROM hc_equipos_qx_fijos_confirmados
									WHERE evolucion_id=".$this->evolucion.";";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}else{
				$estadoFijo=$resulta->GetRowAssoc($ToUpper = false);
			}
		}	
		if ($estadoMovil[registros] > 0 || $estadoFijo[registros] > 0)
		{
			return true;
		}
		else
		{
		 	return false;
		}
	}
	
	function ProgramacionActiva(){		
		list($dbconn) = GetDBconn();
		$query="(SELECT *		
		FROM qx_programaciones a
		WHERE a.estado='1' AND a.tipo_id_paciente='".$this->tipoidpaciente."' AND a.paciente_id='".$this->paciente."'	
		)";	
		$result = $dbconn->Execute($query);		
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{			
			if($result->RecordCount() > 0){
				return true;			
			}			
		}
		return false;			
	}


/**
* Esta función retorna la presentación del submodulo (consulta o inserción).
*
* @access public
* @return text Datos HTML de la pantalla.
* @param text Determina la acción a realizar.
*/
	function GetForma(){
	  
		$pfj=$this->frmPrefijo;
		if(empty($_REQUEST['accion'.$pfj])){			
			unset($_SESSION['CONFIMACION_EQUIPOS_QX_FIJOS']);
			unset($_SESSION['CONFIMACION_EQUIPOS_QX_MOVILES']);			
			if($this->GetEstado()==true){
				$this->EquiposConfimadosFijos();
				$this->EquiposComfimadosMoviles();
			}elseif($this->ProgramacionActiva()==true){				
				$this->EquiposEnlaProgramacionFijos();
				$this->EquiposEnlaProgramacionMoviles();
			}
			$this->frmForma();			
		}elseif($_REQUEST['accion'.$pfj]=='ModificarDatos'){
			if($_REQUEST['EliminarEquipo'.$pfj]==1){	
				if($_REQUEST['fijo'.$pfj]==1){
					unset($_SESSION['CONFIMACION_EQUIPOS_QX_FIJOS']['equipo'][$_REQUEST['dpto'.$pfj]][$_REQUEST['quirofano'.$pfj]][$_REQUEST['tipoEquipo'.$pfj]][$_REQUEST['equipo'.$pfj]]);
					unset($_SESSION['CONFIMACION_EQUIPOS_QX_FIJOS']['duracion'][$_REQUEST['dpto'.$pfj]][$_REQUEST['quirofano'.$pfj]][$_REQUEST['tipoEquipo'.$pfj]][$_REQUEST['equipo'.$pfj]]);
				}else{				
					unset($_SESSION['CONFIMACION_EQUIPOS_QX_MOVILES']['equipo'][$_REQUEST['dpto'.$pfj]][$_REQUEST['tipoEquipo'.$pfj]][$_REQUEST['equipo'.$pfj]]);
					unset($_SESSION['CONFIMACION_EQUIPOS_QX_MOVILES']['duracion'][$_REQUEST['dpto'.$pfj]][$_REQUEST['tipoEquipo'.$pfj]][$_REQUEST['equipo'.$pfj]]);
				}	
			}
			$this->frmForma();	
		}elseif($_REQUEST['accion'.$pfj]=='BuscadorEquipos'){
			if($_REQUEST['Volver'.$pfj]){
				$this->frmForma();
			}else{
				$this->Forma_Seleccion_EquiposQX($_REQUEST['tipoEquipo'.$pfj],$_REQUEST['Quirofano'.$pfj],$_REQUEST['Departamento'.$pfj],$_REQUEST['descripcionEquipo'.$pfj]);
			}
		}elseif($_REQUEST['accion'.$pfj]=='SeleccionarEquipos'){
			if($_REQUEST['fijo'.$pfj]=='F'){
				$_SESSION['CONFIMACION_EQUIPOS_QX_FIJOS']['equipo'][$_REQUEST['dpto'.$pfj]][$_REQUEST['quirofano'.$pfj]][$_REQUEST['tipoEquipoVec'.$pfj]][$_REQUEST['equipo'.$pfj]]=$_REQUEST['nom_equipo'.$pfj];
				$_SESSION['CONFIMACION_EQUIPOS_QX_FIJOS']['duracion'][$_REQUEST['dpto'.$pfj]][$_REQUEST['quirofano'.$pfj]][$_REQUEST['tipoEquipoVec'.$pfj]][$_REQUEST['equipo'.$pfj]]=0;
			}else{
				$_SESSION['CONFIMACION_EQUIPOS_QX_MOVILES']['equipo'][$_REQUEST['dpto'.$pfj]][$_REQUEST['tipoEquipoVec'.$pfj]][$_REQUEST['equipo'.$pfj]]=$_REQUEST['nom_equipo'.$pfj];
				$_SESSION['CONFIMACION_EQUIPOS_QX_MOVILES']['duracion'][$_REQUEST['dpto'.$pfj]][$_REQUEST['tipoEquipoVec'.$pfj]][$_REQUEST['equipo'.$pfj]]=0;
			}
			$this->frmForma();
		}else{
			if($this->InsertDatos()==true){
				$this->frmForma();
			}					
		}
		return $this->salida;
	}

/**
* Esta función retorna los datos de la impresión de la consulta del submodulo.
*
* @access private
* @return text Datos HTML de la pantalla.
*/
	function GetConsulta()
	{
	     if($this->frmConsulta()==false)
		{
			return true;
		}
		return $this->salida;
	}
	
	
	function EquiposEnlaProgramacionFijos(){
		list($dbconn) = GetDBconn();
		$query="(SELECT d.equipo_id,d.descripcion as nom_equipo,c.departamento,c.descripcion as quirofano,
		dpto.descripcion as departamento,tipo.descripcion as tipo_equipo,b.hora_inicio,b.hora_fin 
		FROM qx_programaciones a,qx_quirofanos_programacion b,qx_quirofanos c,
		qx_equipos_quirofanos d,departamentos dpto,qx_tipo_equipo_fijo tipo
		WHERE a.estado='1' AND a.programacion_id=b.programacion_id AND b.quirofano_id=c.quirofano AND		
		d.quirofano_id=c.quirofano AND d.estado='1' AND dpto.departamento=c.departamento AND 
		tipo.tipo_equipo_fijo_id=d.tipo_equipo_fijo_id AND a.tipo_id_paciente='".$this->tipoidpaciente."' AND a.paciente_id='".$this->paciente."'
		)";	
		GLOBAL $ADODB_FETCH_MODE;
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;	
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{			
			if($result->RecordCount() > 0){
				while($datos=$result->FetchRow()){					
					(list($fechaIn,$horaIn)=explode(' ',$datos['hora_inicio']));
					(list($anoIn,$mesIn,$diaIn)=explode('-',$fechaIn));
					(list($hhIn,$mmIn)=explode(':',$horaIn));				
					(list($fechaFn,$horaFn)=explode(' ',$datos['hora_fin']));				
					(list($anoFn,$mesFn,$diaFn)=explode('-',$fechaFn));
					(list($hhFn,$mmFn)=explode(':',$horaFn));					
					$duracion=(mktime($hhFn,$mmFn+1,0,$mesFn,$diaFn,$anoFn)-mktime($hhIn,$mmIn,0,$mesIn,$diaIn,$anoIn))/60;				
					$_SESSION['CONFIMACION_EQUIPOS_QX_FIJOS']['equipo'][$datos['departamento']][$datos['quirofano']][$datos['tipo_equipo']][$datos['equipo_id']]=$datos['nom_equipo'];									
					$_SESSION['CONFIMACION_EQUIPOS_QX_FIJOS']['duracion'][$datos['departamento']][$datos['quirofano']][$datos['tipo_equipo']][$datos['equipo_id']]=$duracion;				
				}				
			}			
		}
		return true;						
	}
	
	function EquiposEnlaProgramacionMoviles(){
		list($dbconn) = GetDBconn();
		$query="(SELECT c.equipo_id,d.descripcion as nom_equipo,d.departamento,dpto.descripcion as departamento,
		tipo.descripcion as tipo_equipo,b.hora_inicio,b.hora_fin 
		FROM qx_programaciones a,qx_quirofanos_programacion b,qx_equipos_programacion c,qx_equipos_moviles d,departamentos dpto,qx_tipo_equipo_movil tipo
		WHERE a.estado='1' AND a.programacion_id=b.programacion_id AND b.qx_quirofano_programacion_id=c.qx_quirofano_programacion_id AND 
		c.equipo_id=d.equipo_id AND 
		d.estado='1' AND dpto.departamento=d.departamento AND 
		tipo.tipo_equipo_id=d.tipo_equipo_id AND a.tipo_id_paciente='".$this->tipoidpaciente."' AND a.paciente_id='".$this->paciente."'	
		)";		
		GLOBAL $ADODB_FETCH_MODE;
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{			
			if($result->RecordCount() > 0){
				while($datos=$result->FetchRow()){			
					(list($fechaIn,$horaIn)=explode(' ',$datos['hora_inicio']));
					(list($anoIn,$mesIn,$diaIn)=explode('-',$fechaIn));
					(list($hhIn,$mmIn)=explode(':',$horaIn));				
					(list($fechaFn,$horaFn)=explode(' ',$datos['hora_fin']));				
					(list($anoFn,$mesFn,$diaFn)=explode('-',$fechaFn));
					(list($hhFn,$mmFn)=explode(':',$horaFn));
					$duracion=(mktime($hhFn,$mmFn+1,0,$mesFn,$diaFn,$anoFn)-mktime($hhIn,$mmIn,0,$mesIn,$diaIn,$anoIn))/60;				
					$_SESSION['CONFIMACION_EQUIPOS_QX_MOVILES']['equipo'][$datos['departamento']][$datos['tipo_equipo']][$datos['equipo_id']]=$datos['nom_equipo'];									
					$_SESSION['CONFIMACION_EQUIPOS_QX_MOVILES']['duracion'][$datos['departamento']][$datos['tipo_equipo']][$datos['equipo_id']]=$duracion;
				}				
			}			
		}
		return true;		
	}
	
	function EquiposConfimadosFijos(){
		list($dbconn) = GetDBconn();
		$query="(SELECT d.equipo_id,d.descripcion as nom_equipo,c.departamento,c.descripcion as quirofano,
		dpto.descripcion as departamento,tipo.descripcion as tipo_equipo,a.duracion 
		FROM hc_equipos_qx_fijos_confirmados a,qx_quirofanos c,
		qx_equipos_quirofanos d,departamentos dpto,qx_tipo_equipo_fijo tipo
		WHERE a.evolucion_id='".$this->evolucion."' AND a.equipo_id=d.equipo_id AND		
		d.quirofano_id=c.quirofano AND d.estado='1' AND dpto.departamento=c.departamento AND 
		tipo.tipo_equipo_fijo_id=d.tipo_equipo_fijo_id
		)";	
		GLOBAL $ADODB_FETCH_MODE;
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;	
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{			
			if($result->RecordCount() > 0){
				while($datos=$result->FetchRow()){										
					$_SESSION['CONFIMACION_EQUIPOS_QX_FIJOS']['equipo'][$datos['departamento']][$datos['quirofano']][$datos['tipo_equipo']][$datos['equipo_id']]=$datos['nom_equipo'];									
					$_SESSION['CONFIMACION_EQUIPOS_QX_FIJOS']['duracion'][$datos['departamento']][$datos['quirofano']][$datos['tipo_equipo']][$datos['equipo_id']]=$datos['duracion'];				
				}				
			}			
		}
		return true;						
	}
	
	function EquiposComfimadosMoviles(){
		list($dbconn) = GetDBconn();
		$query="(SELECT a.equipo_id,d.descripcion as nom_equipo,d.departamento,dpto.descripcion as departamento,
		tipo.descripcion as tipo_equipo,a.duracion 
		FROM hc_equipos_qx_moviles_confirmados a,qx_equipos_moviles d,departamentos dpto,qx_tipo_equipo_movil tipo
		WHERE a.evolucion_id='".$this->evolucion."' AND  
		a.equipo_id=d.equipo_id AND 
		d.estado='1' AND dpto.departamento=d.departamento AND 
		tipo.tipo_equipo_id=d.tipo_equipo_id	
		)";		
		GLOBAL $ADODB_FETCH_MODE;
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{			
			if($result->RecordCount() > 0){
				while($datos=$result->FetchRow()){								
					$_SESSION['CONFIMACION_EQUIPOS_QX_MOVILES']['equipo'][$datos['departamento']][$datos['tipo_equipo']][$datos['equipo_id']]=$datos['nom_equipo'];									
					$_SESSION['CONFIMACION_EQUIPOS_QX_MOVILES']['duracion'][$datos['departamento']][$datos['tipo_equipo']][$datos['equipo_id']]=$datos['duracion'];
				}				
			}			
		}
		return true;		
	}


/**
* Esta metodo captura los datos de la impresión de la Historia Clinica.
* @access private
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
* Esta función inserta los datos del submodulo.
*
* @access private
* @return boolean Informa si lo logro o no.
*/
	function InsertDatos()
	{
		$pfj=$this->frmPrefijo;
		
		list($dbconn) = GetDBconn();
		$vectorEquiposFijos=$_REQUEST['duracionFijo'.$pfj];
		if($_SESSION['CONFIMACION_EQUIPOS_QX_FIJOS']){
			$sql.="DELETE FROM hc_equipos_qx_fijos_confirmados WHERE evolucion_id='".$this->evolucion."';";
			foreach($_SESSION['CONFIMACION_EQUIPOS_QX_FIJOS']['equipo'] as $dpto=>$datos){
				foreach($datos as $quirofano=>$datos1){
					foreach($datos1 as $tipoEquipo=>$datos2){
						foreach($datos2 as $equipo=>$nomequipo){		
							if($vectorEquiposFijos[$equipo]>0){							
								$sql.="INSERT INTO hc_equipos_qx_fijos_confirmados
               				(evolucion_id,
											equipo_id,
											duracion)
										VALUES(".$this->evolucion.",
											'".$equipo."',
											'".$vectorEquiposFijos[$equipo]."');";		
										$_SESSION['CONFIMACION_EQUIPOS_QX_FIJOS']['duracion'][$dpto][$quirofano][$tipoEquipo][$equipo]=$vectorEquiposFijos[$equipo];									
							}				
						}
					}
				}
			}
		}
		$vectorEquiposMoviles=$_REQUEST['duracionMovil'.$pfj];		
		if($_SESSION['CONFIMACION_EQUIPOS_QX_MOVILES']){
			$sql.="DELETE FROM hc_equipos_qx_moviles_confirmados WHERE evolucion_id='".$this->evolucion."';";
			foreach($_SESSION['CONFIMACION_EQUIPOS_QX_MOVILES']['equipo'] as $dpto=>$datos){
				foreach($datos as $tipoEquipo=>$datos1){
					foreach($datos1 as $equipo=>$nomequipo){		
						if($vectorEquiposMoviles[$equipo]>0){
							$sql.="INSERT INTO hc_equipos_qx_moviles_confirmados
										(evolucion_id,
										equipo_id,
										duracion)
									VALUES(".$this->evolucion.",
										'".$equipo."',
										'".$vectorEquiposMoviles[$equipo]."');";		
							$_SESSION['CONFIMACION_EQUIPOS_QX_MOVILES']['duracion'][$dpto][$tipoEquipo][$equipo]=$vectorEquiposMoviles[$equipo];											
							
						}					
					}
				}
			}
		}	
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al insertar los hc_equipos_qx_moviles_confirmados.";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$this->frmError['MensajeError']="Datos Guardados Satisfactoriamente.";
			return true;
		}		
		return true;
	}


	
	
	/**
* Funcion que retorna los tipo de gases anestesicos existentes en la base de datos
* @return array
*/
  function TiposQuirofanosTotal(){

		list($dbconn) = GetDBconn();
		$query = "SELECT quirofano,descripcion FROM qx_quirofanos WHERE estado='1'";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      if($result->RecordCount()>0){
				while(!$result->EOF){
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		$result->Close();
 		return $vars;
	}
	
	/**
* Funcion que consulta en la base de datos los permisos del usuario para trabajar con las bodegas
* @return array
*/
	function TotalDepartamentos(){

		list($dbconn) = GetDBconn();		
		$query = "SELECT departamento,descripcion
		FROM departamentos ORDER BY descripcion";		
		$result = $dbconn->Execute($query);
		if($result->EOF){
			$this->error = "Error al ejecutar la consulta.<br>";
			$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
			return false;
		}else{
      if($result->RecordCount()>0){
        while(!$result->EOF){
          $vars[]=$result->GetRowAssoc($toUpper=false);
          $result->MoveNext();
        }
      }
			return $vars;
		}		
	}
	
	function BusquedaEquiposQX($tipoEquipo,$Quirofano,$departamento,$descripcionEquipo){
		$this->paginaActual = 1;
    $this->offset = 0;
    if($_REQUEST['offset']){
      $this->paginaActual = intval($_REQUEST['offset']);
      if($this->paginaActual > 1){
        $this->offset = ($this->paginaActual - 1) * ($this->limit);
      }
    }
		list($dbconn) = GetDBconn();
		if($Quirofano!=-1 && !empty($Quirofano)){
			$cond=" AND a.quirofano='".$Quirofano."'";
		}
		if($departamento!=-1 && !empty($departamento)){
			$cond1=" AND dpto.departamento='".$departamento."'";
		}
		if($descripcionEquipo){
			$cond2=" AND nom_equipo LIKE '%".STRTOUPPER($descripcionEquipo)."%'";
		}
		if($tipoEquipo=='F'){		
			$query = "SELECT b.equipo_id,b.descripcion as nom_equipo,a.departamento,dpto.descripcion as nom_departamento,'1' as fijo,a.descripcion as quirofano,tipo.descripcion as tipo_equipo 
			FROM qx_quirofanos a,qx_equipos_quirofanos b,departamentos dpto,qx_tipo_equipo_fijo tipo
			WHERE a.quirofano=b.quirofano_id AND b.estado='1' AND dpto.departamento=a.departamento AND tipo.tipo_equipo_fijo_id=b.tipo_equipo_fijo_id $cond $cond1 $cond2
			";
		}elseif($tipoEquipo=='M'){
			$query = "SELECT a.equipo_id,a.descripcion as nom_equipo,a.departamento,dpto.descripcion as nom_departamento,'0' as fijo,NULL as quirofano,tipo.descripcion as tipo_equipo
			FROM qx_equipos_moviles a,departamentos dpto,qx_tipo_equipo_movil tipo
			WHERE a.estado='1' AND a.departamento=dpto.departamento AND tipo.tipo_equipo_id=a.tipo_equipo_id $cond1 $cond2";
		}else{
			$query = "SELECT b.equipo_id,b.descripcion as nom_equipo,a.departamento,dpto.descripcion as nom_departamento,'1' as fijo,a.descripcion as quirofano,tipo.descripcion as tipo_equipo
			FROM qx_quirofanos a,qx_equipos_quirofanos b,departamentos dpto,qx_tipo_equipo_fijo tipo
			WHERE a.quirofano=b.quirofano_id AND b.estado='1' AND dpto.departamento=a.departamento AND tipo.tipo_equipo_fijo_id=b.tipo_equipo_fijo_id $cond $cond1 $cond2
			UNION
			SELECT a.equipo_id,a.descripcion as nom_equipo,a.departamento,dpto.descripcion as nom_departamento,'0' as fijo,NULL as quirofano,tipo.descripcion as tipo_equipo
			FROM qx_equipos_moviles a,departamentos dpto,qx_tipo_equipo_movil tipo
			WHERE a.estado='1' AND a.departamento=dpto.departamento AND tipo.tipo_equipo_id=a.tipo_equipo_id $cond1 $cond2";
		}		
		if(empty($_REQUEST['conteo'])){
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$this->conteo=$result->RecordCount();
		}else{
			$this->conteo=$_REQUEST['conteo'];
		}
		$query.=" LIMIT " . $this->limit . " OFFSET ".$this->offset."";			
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			while(!$result->EOF){
				$vars[]=$result->GetRowAssoc($toUpper=false);
				$result->MoveNext();
			}
		}
		return $vars;
	}
}
?>
