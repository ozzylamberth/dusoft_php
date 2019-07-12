<?
//ESTE ES EL QUE VA A QUED
/**
* Submodulo de DatosPacienteAdicionales.
*
* Submodulo para guardar información adicional del paciente.
* @author Jairo Duvan Diaz Martinez <planetjd@hotmail.com
* @version 1.0
* @package SIIS
* $Id: hc_DatosPacienteAdicionales.php,v 1.6 2006/08/26 18:09:29 lorena Exp $
*/


class DatosPacienteAdicionales extends hc_classModules
{

//clzc
	function DatosPacienteAdicionales()
	{
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
		'fecha'=>'06/30/2005',
		'autor'=>'JAIRO DUVAN DIAZ.',
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
* GetForma - Forma que recibe la accion para direccionarlo a la forma
*
* @return boolean
*/


	//clzc
	function GetForma()
	{

	  $pfj=$this->frmPrefijo;
    if(empty($_REQUEST["accion".$pfj])){
	    $this->frmForma();
			return $this->salida;
		}else{
			if($_REQUEST["accion".$pfj]=='ReservaComponentes'){
			  if($_REQUEST["ModificarFactor".$pfj] || $_REQUEST["SeleccionFactor".$pfj]){
				  $comp=$this->ConsultaComponente();
					$i=0;
					while($i<sizeof($comp)){
						$v='Cantidad'.$comp[$i]['hc_tipo_componente'].$pfj;
						if($_REQUEST[$v]){
              $vect[$v]=$_REQUEST[$v];
						}
						$i++;
					}
          $this->RegistroFactorSanguineoPaciente($_REQUEST["ModificarFactor".$pfj],$_REQUEST["SeleccionFactor".$pfj],$_REQUEST["grupo_sanguineo".$pfj],$_REQUEST["rh".$pfj],$_REQUEST["sw_urgencia".$pfj],
					$_REQUEST["fecha_reserva".$pfj],$_REQUEST["hora".$pfj],$_REQUEST["minutos".$pfj],$_REQUEST["embarazos_previos".$pfj],$_REQUEST["fecha_ultimo_embarazo".$pfj],$_REQUEST["estado_gestacion".$pfj],$_REQUEST["motivo_reserva".$pfj],
					$_REQUEST["confirmarR".$pfj],$vect);
					return $this->salida;
				}else{
          $this->InsertDatos();
					return $this->salida;
				}
			}elseif($_REQUEST["accion".$pfj]=='GuardarRegistroFactor'){
        $this->InsertDatosFactor();
				return $this->salida;
			}elseif($_REQUEST["accion".$pfj]=='ConfirmarComponentes'){
        if($this->RealizarConfirmacionComponentes()==true){
				  $this->frmForma();
				}
			}elseif($_REQUEST["accion".$pfj]=='ResistroRecepcionBolsa'){
			  if($_REQUEST['guardarDatos'.$pfj]){
					if($this->RegistrarDatosRecepcionBolsa()==true){
						$this->frmForma();
					}
				}elseif($_REQUEST['cancelarDatos'.$pfj]){
          $this->frmForma();
				}else{
          $this->FormaRegistroRecepcionBolsa($_REQUEST['IngresoId'.$pfj],$_REQUEST['alicuota'.$pfj],$_REQUEST['bolsaId'.$pfj]);
				}
			}
		}
		return $this->salida;
	}

/**
* ConsultaFactor - Funcion que consuta de la Base de Datos los grupos snguineos existentes
*
* @return vector
*/

//clzc
	function ConsultaFactor()
	{
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query = "select DISTINCT grupo_sanguineo from hc_tipos_sanguineos";
		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al consultar hc_tipos_sanguineos";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
			$datos=$result->RecordCount();
			if($datos){
			  while(!$result->EOF){
					$vars[$result->fields[0]]=$result->fields[0];
					$result->MoveNext();
				}
			}
		}
	  return $vars;
  }

  
  
  

	function Get_datos_Adicionales()
	{
		list($dbconn) = GetDBconn();
		$query ="SELECT a.* FROM pacientes_datos_adicionales a    
		WHERE a.paciente_id='".$this->paciente."'
		AND a.tipo_id_paciente='".$this->tipoidpaciente."';";
		 $res = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al traer información de la tabla pacientes_datos_adicionales ";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}

		if($res->EOF)
		{
			return '';
		}
		return $res->GetRowAssoc($ToUpper = false);
	}



/**
* InsertDatos - Funcion que inserta los datos a la base de datos
*
* @return boolean
*/

	function InsertDatos(){

	  $pfj=$this->frmPrefijo;
    $dir_tra=$_REQUEST["dir_tra".$pfj];
		$tel_tra=$_REQUEST["tel_tra".$pfj];
		$tel=$_REQUEST["tel".$pfj];
		$nom=$_REQUEST["nom".$pfj];
       
    
    list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$query ="SELECT * FROM pacientes_datos_adicionales
		WHERE paciente_id='".$this->paciente."'
		AND tipo_id_paciente='".$this->tipoidpaciente."';";
    $result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
    if($result->RecordCount() > 0){
		  $query ="UPDATE pacientes_datos_adicionales
      SET direccion_trabajo='$dir_tra', telefono_trabajo='$tel_tra',
		      nombre_aviso='$nom', telefono_aviso='$tel'
      WHERE paciente_id='".$this->paciente."' AND tipo_id_paciente='".$this->tipoidpaciente."';";
    }else{
      $query ="INSERT INTO
      pacientes_datos_adicionales(paciente_id, tipo_id_paciente, direccion_trabajo, telefono_trabajo,
      nombre_aviso, telefono_aviso,)
      VALUES('".$this->paciente."','".$this->tipoidpaciente."','$dir_tra','$tel_tra','$nom',
      '$tel');";
    }
    $result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$dbconn->CommitTrans();		
		$this->frmError["MensajeError"]="Datos Guardados Satisfactoriamente";
		$this->frmForma();
		return true;
	}
/**
* InsertDatosFactor - Funcion que inserta los datos de la hemoclasificacion del paciente en la base de datos
*
* @return boolean
*/

	function InsertDatosFactor(){

		$pfj=$this->frmPrefijo;
	  if($_REQUEST["Cancelar".$pfj]){
      $this->frmForma();
			return true;
		}
		if($_REQUEST["grupo_sanguineoReg".$pfj]==-1 || $_REQUEST["rh".$pfj]==-1){
		 // if($_REQUEST["bacteriologo".$pfj]==-1){$this->frmError["bacteriologo"]=1;}
      if($_REQUEST["grupo_sanguineoReg".$pfj]==-1){$this->frmError["grupo_sanguineo"]=1;}
			if($_REQUEST["rh".$pfj]==-1){$this->frmError["rh"]=1;}
			//if(!$_REQUEST["fecha_examen".$pfj]){$this->frmError["fecha_examen"]=1;}
			$this->frmError["MensajeError"]="Complete los Datos Obligatorios.";
			$comp=$this->ConsultaComponente();
			$i=0;
			while($i<sizeof($comp)){
				$v='Cantidad'.$comp[$i]['hc_tipo_componente'].$pfj;
				if($_REQUEST[$v]){
					$vect[$v]=$_REQUEST[$v];
				}
				$i++;
			}
      $this->RegistroFactorSanguineoPaciente($_REQUEST["ModificarFactor".$pfj],$_REQUEST["SeleccionFactor".$pfj],$_REQUEST["grupo_sanguineo".$pfj],$_REQUEST["rh".$pfj],$_REQUEST["sw_urgencia".$pfj],
			$_REQUEST["fecha_reserva".$pfj],$_REQUEST["hora".$pfj],$_REQUEST["minutos".$pfj],$_REQUEST["embarazos_previos".$pfj],$_REQUEST["fecha_ultimo_embarazo".$pfj],$_REQUEST["estado_gestacion".$pfj],$_REQUEST["motivo_reserva".$pfj],
			$_REQUEST["confirmarR".$pfj],$vect);
			return true;
		}
		list($dbconn) = GetDBconn();
		if($_REQUEST["ModificarFactor".$pfj]){
      $query="UPDATE pacientes_grupo_sanguineo SET estado='0' WHERE tipo_id_paciente='".$this->tipoidpaciente."' AND paciente_id='".$this->paciente."'";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
		}  
    
		$fechaExamen=ereg_replace("-","/",$_REQUEST["fecha_examen".$pfj]);
		(list($diaExa,$mesExa,$anoExa)=explode('/',$fechaExamen));
		(list($bacteriologo,$Tipobacteriologo)=explode('/',$_REQUEST["bacteriologo".$pfj]));


		if(empty($diaExa) and empty($mesExa) and empty($anoExa))
		{$fech='NULL';}else{$fech="'".$anoExa.'-'.$mesExa.'-'.$diaExa."'";}
		if(empty($bacteriologo) or $bacteriologo =='-1')
		{$bacteriologo='NULL';}else{$bacteriologo="'".$bacteriologo."'";}
		if(empty($Tipobacteriologo))
		{	$Tipobacteriologo='NULL';}else{$Tipobacteriologo="'".$Tipobacteriologo."'";}


		$query="INSERT INTO pacientes_grupo_sanguineo(tipo_id_paciente,paciente_id,grupo_sanguineo,rh,laboratorio,observaciones,fecha_examen,
		tipo_id_bacteriologo,bacteriologo_id,usuario_id,fecha_registro,estado)VALUES('".$this->tipoidpaciente."','".$this->paciente."',
		'".$_REQUEST["grupo_sanguineoReg".$pfj]."','".$_REQUEST["rh".$pfj]."','".$_REQUEST["laboratorio".$pfj]."','".$_REQUEST["observaciones".$pfj]."',$fech,
		$Tipobacteriologo,$bacteriologo,'".UserGetUID()."','".date('Y-m-d H:i:s')."','1')";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    $this->frmForma();
		return true;
	}

/**
* SangrePaciente - Funcion que consulta los datos de la hemoclasificacion del paciente en la base de datos
*
* @return vector
*/

  //clzc
  function SangrePaciente(){
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
	  $query = "select grupo_sanguineo, rh  from hc_pacientes_hemoclasificacion where paciente_id ='".$this->paciente."' and tipo_id_paciente ='".$this->tipoidpaciente."'";
	  $result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al consultar hc_pacientes_hemoclasificacion";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{
			$sangrepac=$result->GetRowAssoc($ToUpper = false);
		}
	  return $sangrepac;
  }

/**
* ConsultaComponente - Funcion que consulta los datos de la hemoclasificacion del paciente en la base de datos
*
* @return vector
*/

	//clzc
	function ConsultaComponente()
	{
		list($dbconnect) = GetDBconn();
		$query = "select hc_tipo_componente, componente from hc_tipos_componentes";
		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
  	else
		{ $i=0;
			while (!$result->EOF)
			{
			$comp[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
			}
		}
	  return $comp;
  }

/**
* GetConsulta - Funcion que llama la funcion para la consulta de registros insertados en el ingreso
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



/**
* FactorPaciente - Funcion que  consulta el grupo sanguineo y el rh del paciente
*
* @return array
*/

  function FactorPaciente(){

		list($dbconn) = GetDBconn();
		$query="SELECT grupo_sanguineo,rh
		FROM pacientes_grupo_sanguineo
		WHERE tipo_id_paciente='".$this->tipoidpaciente."' AND paciente_id='".$this->paciente."' AND estado='1'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      $datos=$result->RecordCount();
			if($datos){
				$vars=$result->GetRowAssoc($toUpper=false);
			}
		}
		return $vars;
	}

/**
* SexodePaciente - Funcion que  consulta el sexo del paciente
*
* @return int
*/

  function SexodePaciente(){
		list($dbconn) = GetDBconn();
		$sql="SELECT sexo_id FROM pacientes
		WHERE tipo_id_paciente='".$this->tipoidpaciente."'
		AND paciente_id='".$this->paciente."';";
		$result = $dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      return $result->fields[0];
		}
		return 0;

	}

/**
* TotalBacteriologos - Funcion que consulta los bacteriologos de la base de datos
*
* @return array
*/

	function TotalBacteriologos(){

		list($dbconn) = GetDBconn();
		$query="SELECT b.tipo_id_tercero,b.tercero_id,b.nombre FROM banco_sangre_profesionales a,profesionales b
		WHERE a.tipo_id_tercero=b.tipo_id_tercero AND a.tercero_id=b.tercero_id AND b.tipo_profesional='6' AND b.estado=1 ORDER BY nombre";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$datos=$result->RecordCount();
			if($datos){
				while(!$result->EOF) {
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		$result->Close();
 		return $vars;
	}


}
?>






