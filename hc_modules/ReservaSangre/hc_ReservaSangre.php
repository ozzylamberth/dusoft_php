<?
//ESTE ES EL QUE VA A QUED
/**
* Submodulo de Reserva de Sangre.
*
* Submodulo para manejar la reserva y/o cruzada de sangre.
* @author Claudia Liliana Zuñiga Cañon <claudia_zc@hotmail.com
* @version 1.0
* @package SIIS
* $Id: hc_ReservaSangre.php,v 1.12 2006/12/19 21:00:14 jgomez Exp $
*/


class ReservaSangre extends hc_classModules
{

//clzc
	function ReservaSangre()
	{
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

/**
* InsertDatos - Funcion que inserta los datos a la base de datos
*
* @return boolean
*/

	function InsertDatos(){

	  $pfj=$this->frmPrefijo;
    if(!$_REQUEST["fecha_reserva".$pfj] || $_REQUEST["hora".$pfj]==-1 || $_REQUEST["minutos".$pfj]==-1){
			if(!$_REQUEST["fecha_reserva".$pfj]){$this->frmError["fecha_reserva"]=1;}
			if($_REQUEST["hora".$pfj]==-1){$this->frmError["hora"]=1;}
			if($_REQUEST["minutos".$pfj]==-1){$this->frmError["minutos"]=1;}
      $this->frmError["MensajeError"]="Faltan Datos Obligatorios";
      $this->frmForma();
			return true;
		}
		$bandera=1;
		$encuentra=1;
		$comp=$this->ConsultaComponente();
		$i=0;
		while($i<sizeof($comp) && $bandera==1){
			$v='Cantidad'.$comp[$i]['hc_tipo_componente'].$pfj;
			if($_REQUEST[$v]){
			  $encuentra=2;
				if(!is_numeric($_REQUEST[$v])){
					$bandera=2;
				}
			}
			$i++;
		}
		if($bandera!=1 || $encuentra==1){
      $this->frmError["MensajeError"]="Las Cantidades Seleccionadas para los Componentes deben ser Enteras";
      $this->frmForma();
			return true;
		}
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();

		(list($dia,$mes,$ano)=explode('-',$_REQUEST["fecha_reserva".$pfj]));
    $fecha=$ano.'-'.$mes.'-'.$dia.' '.$_REQUEST["hora".$pfj].':'.$_REQUEST["minutos".$pfj];
		if($_REQUEST["fecha_ultimo_embarazo".$pfj]){
			(list($dia,$mes,$ano)=explode('-',$_REQUEST["fecha_ultimo_embarazo".$pfj]));
			$fechaUltEmbarazo="'$ano-$mes-$dia'";
		}else{
      $fechaUltEmbarazo='NULL';
		}
		$query ="SELECT nextval('banco_sangre_reserva_solicitud_reserva_sangre_id_seq')";
    $result = $dbconn->Execute($query);
    $SolitudReserva=$result->fields[0];
		if(!$_REQUEST["grupo_sanguineo".$pfj]){$grupo_sanguineo='NULL';}else{$grupo_sanguineo="'".$_REQUEST["grupo_sanguineo".$pfj]."'";}
		if(!$_REQUEST["rh".$pfj]){$rh='NULL';}else{$rh="'".$_REQUEST["rh".$pfj]."'";}
		if($_REQUEST['autologa'.$pfj]){$autologa='1';}else{$autologa='0';}
		$query ="INSERT INTO banco_sangre_reserva(solicitud_reserva_sangre_id,paciente_id,tipo_id_paciente,
		                                            ubicacion_paciente,responsable_solicitud,
																								departamento,sw_urgencia,grupo_sanguineo,
																								rh,
																								fecha_hora_reserva,
																								transfuciones_ant,reacciones_adv,
																								descripcion_reac,embarazos_previos,
																								fecha_ultimo_embarazo,motivo_reserva,
																								sw_estado,estado_gestacion,
																								usuario_id,fecha_registro,reserva_autologa)VALUES(
																								'$SolitudReserva',
																								'".$this->paciente."','".$this->tipoidpaciente."',
																								'".$_REQUEST["ubicacionPaciente".$pfj]."','".$_REQUEST["responsableSolicitud".$pfj]."',
																								'".$this->departamento."','".$_REQUEST["sw_urgencia".$pfj]."',
																								$grupo_sanguineo,$rh,
																								'".$fecha."','".$_REQUEST["transfuciones_ant".$pfj]."',
																								'".$_REQUEST["reacciones_adv".$pfj]."','".$_REQUEST["descripcion_reac".$pfj]."',
																								'".$_REQUEST["embarazos_previos".$pfj]."',".$fechaUltEmbarazo.",
																								'".$_REQUEST["motivo_reserva".$pfj]."','1','".$_REQUEST["estado_gestacion".$pfj]."',
																								'".UserGetUID()."','".date('Y-m-d H:i:s')."','$autologa')";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}else{
		  $comp =$this->ConsultaComponente();
			$i=0;
			while($i<sizeof($comp)){
				$v='Cantidad'.$comp[$i]['hc_tipo_componente'].$pfj;
				if($_REQUEST[$v]){
				  if($_REQUEST["confirmarR".$pfj]){
            $estado='2';
					}else{
            $estado='1';
					}
					$query ="INSERT INTO banco_sangre_reserva_detalle(solicitud_reserva_sangre_id,tipo_componente_id,cantidad_componente,sw_estado)
					VALUES('$SolitudReserva','".$comp[$i]['hc_tipo_componente']."','".$_REQUEST[$v]."','$estado')";
					$result = $dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				}
				$i++;
			}

			/*$selecciones=$_REQUEST['seleccion'.$pfj];
			if(sizeof($selecciones)>0){
        for($i=0;$i<sizeof($selecciones);$i++){
          $query ="INSERT INTO banco_sangre_reserva_otros_servicios(solicitud_reserva_sangre_id,cargo)
					VALUES('$SolitudReserva','".$selecciones[$i]."')";
					$result = $dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				}
			}*/
			$query ="INSERT INTO banco_sangre_reserva_hc(solicitud_reserva_sangre_id,evolucion_id,ingreso)
			VALUES('$SolitudReserva','".$this->evolucion."','".$this->ingreso."');";
      $result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
			$dbconn->CommitTrans();
			$comp=$this->ConsultaComponente();
			$i=0;
			while($i<sizeof($comp)){
				$v='Cantidad'.$comp[$i]['hc_tipo_componente'].$pfj;
				if($_REQUEST[$v]){
					unset($_REQUEST[$v]);
				}
				$i++;
			}
			unset($_REQUEST["ModificarFactor".$pfj]);
			unset($_REQUEST["SeleccionFactor".$pfj]);
			unset($_REQUEST["grupo_sanguineo".$pfj]);
			unset($_REQUEST["rh".$pfj]);
			unset($_REQUEST["sw_urgencia".$pfj]);
			unset($_REQUEST["fecha_reserva".$pfj]);
			unset($_REQUEST["hora".$pfj]);
			unset($_REQUEST["minutos".$pfj]);
			unset($_REQUEST["embarazos_previos".$pfj]);
			unset($_REQUEST["fecha_ultimo_embarazo".$pfj]);
			unset($_REQUEST["estado_gestacion".$pfj]);
			unset($_REQUEST["motivo_reserva".$pfj]);
			unset($_REQUEST["confirmarR".$pfj]);
      unset($_REQUEST['seleccion'.$pfj]);
		}
		$this->frmForma();
		 $this->RegistrarSubmodulo($this->GetVersion());            
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
		if($_REQUEST["bacteriologo".$pfj]==-1 || $_REQUEST["grupo_sanguineoReg".$pfj]==-1 || $_REQUEST["rh".$pfj]==-1 || !$_REQUEST["fecha_examen".$pfj]){
		  if($_REQUEST["bacteriologo".$pfj]==-1){$this->frmError["bacteriologo"]=1;}
      if($_REQUEST["grupo_sanguineoReg".$pfj]==-1){$this->frmError["grupo_sanguineo"]=1;}
			if($_REQUEST["rh".$pfj]==-1){$this->frmError["rh"]=1;}
			if(!$_REQUEST["fecha_examen".$pfj]){$this->frmError["fecha_examen"]=1;}
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
		$query="INSERT INTO pacientes_grupo_sanguineo(tipo_id_paciente,paciente_id,grupo_sanguineo,rh,laboratorio,observaciones,fecha_examen,
		tipo_id_bacteriologo,bacteriologo_id,usuario_id,fecha_registro,estado)VALUES('".$this->tipoidpaciente."','".$this->paciente."',
		'".$_REQUEST["grupo_sanguineoReg".$pfj]."','".$_REQUEST["rh".$pfj]."','".$_REQUEST["laboratorio".$pfj]."','".$_REQUEST["observaciones".$pfj]."','$anoExa-$mesExa-$diaExa',
		'$Tipobacteriologo','$bacteriologo','".UserGetUID()."','".date('Y-m-d H:i:s')."','1')";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    $this->frmForma();
	  $this->RegistrarSubmodulo($this->GetVersion());            
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
* ConsultaTransfusiones - Funcion que consulta las transfusiones del paciente en un mismo ingreso
*
* @return vector
*/

//clzc
	function ConsultaTransfuciones()
	{
		list($dbconnect) = GetDBconn();
	  $query = "select ingreso, reaccion_adversa from hc_control_transfusiones where ingreso = ".$this->ingreso."";
	  $result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{
      $i=0;
			while (!$result->EOF)
			{
			$transf_ant[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
			}
		}
	  return $transf_ant;
  }

/**
* ConsultaGestacion - Funcion que consulta si la paciente se encuentra en estado de gestacion
*
* @return vector
*/

//clzc
	function ConsultaGestacion()
	{
		list($dbconnect) = GetDBconn();
	  $query = "select gestacion_num_embarazo, estado, gestacion_fecha_fin from gestacion where paciente_id ='".$this->paciente."' and tipo_id_paciente ='".$this->tipoidpaciente."'";
	  $result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{

			$gesta=$result->GetRowAssoc($ToUpper = false);
		}
	  return $gesta;
  }

/**
* Eliminar_Reserva_Sangre - Funcion que elimina alguna reserva de sangre
*
* @return boolean
* @param $reserva_id numero unico que identifica la reserva
*/

//clzc - si - *
function Eliminar_Reserva_Sangre($reserva_id)
{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$query="DELETE FROM hc_solicitud_reserva_sangre WHERE hc_reserva_sangre_id = ".$reserva_id."
		AND evolucion_id = ".$this->evolucion."";
		$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->frmError["MensajeError"]="NO SE LOGRO ELIMINAR LA RESERVA DE SANGRE";
			return false;
		}
		$this->frmError["MensajeError"]="RESERVA ELIMINADA.";
 return true;
}

/**
* BuscarReservaId - Funcion que retorna el numero unico que identifica la reserva
*
* @return int
*/

//clzc
	function BuscarReservaId()
	{
		list($dbconn) = GetDBconn();
		$query="SELECT nextval('hc_solicitud_reserva_sangre_hc_reserva_sangre_id_seq')";

		$result=$dbconn->Execute($query);
		$reserva=$result->fields[0];
		return $reserva;
	}

//clzc
	/*function InsertDatos()
	{
		$pfj=$this->frmPrefijo;
		if	($_REQUEST['rh'.$pfj] ==-1	|| $_REQUEST['grupo_sanguineo'.$pfj] == -1 ||
					empty($_REQUEST['fecha_reserva'.$pfj]) || $_REQUEST['hora'.$pfj]== -1
					|| $_REQUEST['minutos'.$pfj]== -1 )
			{
				if($_REQUEST['rh'.$pfj]==-1)
				{
					$this->frmError["rh"]=1;
				}
				if($_REQUEST['grupo_sanguineo'.$pfj]==-1)
				{
					$this->frmError["grupo_sanguineo"]=1;
				}
				if(empty($_REQUEST['fecha_reserva'.$pfj]))
				{
					$this->frmError["fecha_reserva"]=1;
				}
				if($_REQUEST['hora'.$pfj] == -1 || $_REQUEST['minutos'.$pfj] == -1)
				{
					$this->frmError["hora"]=1;
				}

				$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
				return false;
			}
    if (empty($_REQUEST['fecha_ultimo_embarazo'.$pfj]))
		  {
				$emb='NULL';
			}
		else
			{
				$emb="'".$_REQUEST['fecha_ultimo_embarazo'.$pfj]."'";
			}
		$reserva=$this->BuscarReservaId();
		list($dbconn) = GetDBconn();

		$fecha_hora_reserva= $_REQUEST['fecha_reserva'.$pfj];
		$cad=explode ('-',$fecha_hora_reserva);
    $dia = $cad[0];
    $mes = $cad[1];
    $ano = $cad[2];
		if (date("Y-m-d",mktime(0,0,0,$mes,$dia,$ano)) < date("Y-m-d",mktime(0,0,0,date('m'),date('d'),date('Y'))))
			 {
           $this->frmError["fecha_reserva"]=1;
			     $this->frmError["MensajeError"]="FECHA INVALIDA.";
					 return false;
		   }
		$fecha_hora_reserva=$cad[2].'-'.$cad[1].'-'.$cad[0].' '.$_REQUEST['hora'.$pfj].':'.$_REQUEST['minutos'.$pfj].':00';

    $query="insert into hc_solicitud_reserva_sangre (hc_reserva_sangre_id,sw_urgencia,
		fecha_hora_reserva,grupo_sanguineo, rh, preparacion, cruzar, transfuciones_ant,
		reacciones_adv, descripcion_reac, embarazos_previos, fecha_ultimo_embarazo,
		motivo_reserva, estado_gestacion, evolucion_id )values(
				$reserva,
       '".$_REQUEST['sw_urgencia'.$pfj]."',
			    '".$fecha_hora_reserva."',
       '".$_REQUEST['grupo_sanguineo'.$pfj]."',
       '".$_REQUEST['rh'.$pfj]."',
       '".$_REQUEST['preparacion'.$pfj]."',
			 '".$_REQUEST['cruzar'.$pfj]."',
			 '".$_REQUEST['transfuciones_ant'.$pfj]."',
			 '".$_REQUEST['reacciones_adv'.$pfj]."',
			 '".$_REQUEST['descripcion_reac'.$pfj]."',
			 '".$_REQUEST['embarazos_previos'.$pfj]."',
			 $emb,
			 '".$_REQUEST['motivo_reserva'.$pfj]."',
			 '".$_REQUEST['estado_gestacion'.$pfj]."', ".$this->evolucion.")";

		$dbconn->BeginTrans();
		$dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		  {
				$this->error = "Error al insertar en la tabla hc_solicitud_reserva_sangre";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
		else
	    {
				$paciente = $this->paciente;
				$tipoid   = $this->tipoidpaciente;
		    $c2= "update hc_pacientes_hemoclasificacion set
				grupo_sanguineo = '".$_REQUEST['grupo_sanguineo'.$pfj]."', rh = '".$_REQUEST['rh'.$pfj]."', laboratorio = '".$_REQUEST['laboratorio'.$pfj]."' where paciente_id ='$paciente' and tipo_id_paciente ='$tipoid'";
				$dbconn->Execute($c2);
				if ($dbconn->ErrorNo() != 0)
				  {
						$this->error = "Error al insertar en la tabla hc_solicitud_reserva_sangre";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				else
						{//k es el nombre del request y v es el arreglo
								foreach($_REQUEST as $k => $v)
									{
							  		if(substr_count($k,'Cantidad'))
											{
												if($v!=-1)
													{
														$x=explode(',',$v);
														$query = "insert into hc_solicitud_reserva_sangre_detalle
														(hc_tipo_componente_id,cantidad_componente,hc_reserva_sangre_id)
														values($x[1],$x[0],$reserva)";
														if(!$dbconn->Execute($query))
															{
								  							$dbconn->FailTrans();
																$this->error = "Error al insertar en la tabla hc_solicitud_reserva_sangre";
																$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																$dbconn->RollbackTrans();
																return false;
															}
													}
											}
									}
						}
			}
			$dbconn->CommitTrans();
      $this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
			$_REQUEST='';
			return true;

	}*/

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
* ConsultaReservaSangre - Funcion que retorna los datos de las reservas realizadas para un paciente en un mismo ingreso
*
* @return array
*/

  function ConsultaReservaSangre(){

		list($dbconnect) = GetDBconn();
		$query= "SELECT a.solicitud_reserva_sangre_id,c.tipo_componente_id,b.fecha_hora_reserva,dpto.descripcion as departamento,b.sw_urgencia,pac.grupo_sanguineo,pac.rh,
		c.cantidad_componente,tiposcom.componente,c.sw_estado,
		(SELECT sum(cantidad_confirmado) FROM banco_sangre_reserva_detalle_confirmadas w WHERE w.solicitud_reserva_sangre_id=c.solicitud_reserva_sangre_id AND w.tipo_componente_id=c.tipo_componente_id) as confirmadas
		FROM banco_sangre_reserva_hc a,banco_sangre_reserva b
		LEFT JOIN departamentos dpto ON(dpto.departamento=b.departamento)
		LEFT JOIN pacientes_grupo_sanguineo pac ON(pac.tipo_id_paciente=b.tipo_id_paciente AND pac.paciente_id=b.paciente_id)
		,banco_sangre_reserva_detalle c
		LEFT JOIN hc_tipos_componentes tiposcom ON(tiposcom.hc_tipo_componente=c.tipo_componente_id)
		WHERE a.ingreso='".$this->ingreso."' AND
		a.solicitud_reserva_sangre_id=b.solicitud_reserva_sangre_id AND
		b.solicitud_reserva_sangre_id=c.solicitud_reserva_sangre_id AND b.sw_estado='1' AND
		b.tipo_id_paciente='".$this->tipoidpaciente."' AND b.paciente_id='".$this->paciente."' ORDER BY b.fecha_hora_reserva";
		$result = $dbconnect->Execute($query);
		if($dbconnect->ErrorNo() != 0){
			$this->error = "Error al Consultar la Sangre Reservada";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
			while (!$result->EOF){
				$fact[$result->fields[0]][$result->fields[1]]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
	  return $fact;
  }

/**
* ConsultaReservaSangreDetalles - Funcion que  consulta el detalle de la reserva especificada
*
* @return array
* @param $hc_reserva_sangre_id numero unico que identifica la reserva
*/


  function ConsultaReservaSangreDetalles($hc_reserva_sangre_id){

		list($dbconnect) = GetDBconn();
		$query= "SELECT b.componente, a.cantidad_componente FROM hc_solicitud_reserva_sangre_detalle as a, hc_tipos_componentes as b WHERE hc_reserva_sangre_id = ".$hc_reserva_sangre_id." and a.hc_tipo_componente_id = b.hc_tipo_componente";
		$result = $dbconnect->Execute($query);
		if($dbconnect->ErrorNo() != 0){
			$this->error = "Error al consultar el detalle de la reserva";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
		  $i=0;
			while (!$result->EOF){
				$fact[$i]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
				$i++;
			}
		}
	  return $fact;
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
/**
* OtrosServiciosSolicitud - Funcion que consulta los tipos de apoyos adicionales que pueden acompañar al examen de compatibilidad de la sangre
*
* @return array
*/
	function OtrosServiciosSolicitud(){
    list($dbconn) = GetDBconn();
		$query="SELECT c.descripcion,c.cargo
		FROM banco_sangre_departamento a,departamentos_cargos b,cups c
		WHERE a.departamento=b.departamento AND b.cargo=c.cargo";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      $datos=$result->RecordCount();
			if($datos){
			  while(!$result->EOF){
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		return $vars;
	}
/**
* RealizarConfirmacionComponentes - Funcion que actualiza los componentes reservados y les cambia el estado
*
* @return boolean
*/

	function RealizarConfirmacionComponentes(){
    $pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
    foreach($_REQUEST['Solicitar'.$pfj] as $componenteTot=>$valor){
      (list($componente,$reserva)=explode('||//',$componenteTot));
			$valores=$_REQUEST['ValorPendiente'.$pfj];
			$valor=$valores[$componente.'||//'.$reserva];
			if($_REQUEST[$componente.'||//'.$reserva.$pfj]>$valor){
        $this->frmError["MensajeError"]="Las Cantidades para Confirmar no pueden ser mayores a las cantidades Pendientes";
				return true;
			}
			$query="INSERT INTO banco_sangre_reserva_detalle_confirmadas(solicitud_reserva_sangre_id,tipo_componente_id,cantidad_confirmado,fecha_confirmacion,usuario_id)
			VALUES('".$reserva."','".$componente."','".$_REQUEST[$componente.'||//'.$reserva.$pfj]."','".date("Y-m-d H:i:s")."','".UserGetUID()."')";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
		}
		$dbconn->CommitTrans();
		unset($_REQUEST['Solicitar'.$pfj]);
		 $this->RegistrarSubmodulo($this->GetVersion());            
    return true;
  }

/**
* UnidadesPatinaje - Funcion que selecciona los componentes que van a entregarse al patinador
*
* @return array
* @param $Solicitud numero unico que identifica la solicitud
* @param $Componente tipo de componente
*/

	function UnidadesPatinaje($Solicitud,$Componente){
    list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
    $query="SELECT a.ingreso_bolsa_id,a.numero_alicuota,c.bolsa_id,
		(SELECT 1 FROM banco_sangre_recepcion_bolsas w WHERE a.ingreso_bolsa_id=w.ingreso_bolsa_id AND a.numero_alicuota=w.numero_alicuota) as recibido
		FROM banco_sangre_entrega_bolsas a,banco_sangre_bolsas c
		WHERE a.solicitud_reserva_sangre_id='$Solicitud' AND a.tipo_componente_id='$Componente' AND
		a.ingreso_bolsa_id||' '||a.numero_alicuota IN (SELECT b.ingreso_bolsa_id||' '||b.numero_alicuota FROM banco_sangre_entrega_bolsas_enrega_confirmacion b) AND
		a.ingreso_bolsa_id=c.ingreso_bolsa_id";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}else{
			if($result->RecordCount()>0){
			  while(!$result->EOF){
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		return $vars;
	}

/**
* RegistrarDatosRecepcionBolsa - Funcion que inserta los datos de la bolsa
*
* @return boolean
*/

	function RegistrarDatosRecepcionBolsa(){
	  $pfj=$this->frmPrefijo;
    list($dbconn) = GetDBconn();
    $query="INSERT INTO banco_sangre_recepcion_bolsas (ingreso_bolsa_id,numero_alicuota,observaciones,fecha_recepcion,usuario_id)
		VALUES('".$_REQUEST['IngresoId'.$pfj]."','".$_REQUEST['alicuota'.$pfj]."','".$_REQUEST['observaciones'.$pfj]."','".date("Y-m-d H:i:s")."','".UserGetUID()."')";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
     $this->RegistrarSubmodulo($this->GetVersion());            
    return true;
	}

}
?>






