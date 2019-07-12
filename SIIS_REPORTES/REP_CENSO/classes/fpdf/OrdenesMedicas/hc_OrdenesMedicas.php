<?
/* hc_modules/OrdenesMedicas.php  20/01/2004
* ----------------------------------------------------------------------
* Autor: ARLEY VELÁSQUEZ C.
* Proposito: Manejador de la ordenes medicas para los pacientes de
* hospitalización.
* Editado Por: Tizziano Perea Ocoro
* ----------------------------------------------------------------------
* $Id: hc_OrdenesMedicas.php,v 1.8 2006/10/12 20:45:47 tizziano Exp $
*/

class OrdenesMedicas extends hc_classModules
{
		function OrdenesMedicas() //Constructor Padre
		{
			$this->frmError = array();
			$this->error='';
			return true;
		}//End function


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
			'fecha'=>'01/27/2005',
			'autor'=>'TIZZIANO PEREA OCORO',
			'descripcion_cambio' => '',
			'requiere_sql' => false,
			'requerimientos_adicionales' => '',
			'version_kernel' => '1.0'
			);
			return $informacion;
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


/**
* Esta función retorna los datos para la impresión que se realizara en el archivo PDF.
*
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


		/*
		* GetForma()
		*/
		function GetForma()
		{
			$action='';
			if (!empty($_REQUEST['subModuloAction'])){
				$action=$_REQUEST['subModuloAction'];
			}
			$this->FrmForma($action);
			return $this->salida;
		}//End function


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
	$query="SELECT count(*)
			FROM hc_controles_paciente
			WHERE evolucion_id=".$this->evolucion.";";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while(!$resulta->EOF)
		{
			$estado=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}

		if ($estado[count] == 0)
		{
			return false;
		}
		else
		{
		 	return true;
		}
	}



		function GetControles()
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$controles=array();
			$query="SELECT c.*, a.descripcion
					FROM hc_controles_paciente c,
					hc_tipos_controles_paciente a
					WHERE c.ingreso=".$this->ingreso." AND
					c.control_id=a.control_id";

			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado) {
				$this->error = "Error al buscar los controles del paciente en \"hc_controles_paciente\"<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			while ($data = $resultado->FetchRow()) {
				$controles[]=$data;
			}
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			return $controles;
		}


		function GetAllTipoControles($tabla,$frecuencia_id,$valor)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			switch ($valor)
			{
				case 0:
								$ctrl_gral=array();
								$query = "SELECT *
										 FROM $tabla
										 WHERE frecuencia_id='".$frecuencia_id."'";
								$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
								$resultado=$this->Verifica_Conexion($query,$dbconn);
									if (!$resultado) {
										$this->error = "Error, no se encuentra el registro en \"$tabla\" con la frecuencia_id \"$frecuencia_id\"";
										$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
										return false;
									}
								$ctrl_gral = $resultado->FetchRow();
								if ($ctrl_gral===false){
									$this->error = "Error al consultar la tabla";
									$this->mensajeDeError = "No se encuentran registros en \"$tabla\".";
								}
								$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
								return $ctrl_gral;
				break;
				case 1:
								$query = "SELECT * FROM $tabla";
								$resultado=$this->Verifica_Conexion($query,$dbconn);
									if (!$resultado) {
										$this->error = "Error, la tabla $tabla no contiene registros";
										$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
										return false;
									}
								return $this->GetAllTipoControlesOpt($resultado,$frecuencia_id);
				break;
			}
		}


		function GetAllControles($tabla,$control)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$data=array();
			if (is_array($control) && !empty($control['evolucion_id'])){
				$query="SELECT * FROM $tabla WHERE evolucion_id=".$control['evolucion_id'];
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$resultado=$this->Verifica_Conexion($query,$dbconn);
				if (!$resultado)
				{
					$this->error = "Error al consultar la tabla \"$tabla\" con evolucion_id=".$control['evolucion_id'];
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					return false;
				}
				$data=$resultado->FetchRow();
				if ($data===false){
					$this->error = "Error al consultar la tabla";
					$this->mensajeDeError = "No se encuentran registros en \"$tabla\".";
				}
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			}
			return $data;
		}

/***********************************************************/
/***********************************************************/
//Insert de los controles
/***********************************************************/
/***********************************************************/
		function InsertCtrlPosicion($posicion,$observaciones,$ctrlPosicion,$controles)
		{
			list($dbconn) = GetDBconn();

			if (empty($ctrlPosicion['ingreso'])) {
				$query="INSERT INTO hc_controles_paciente(ingreso,control_id,evolucion_id)
								VALUES (".$this->ingreso.",'1',".$this->evolucion.")";
				$resultado=$dbconn->Execute($query);
					if (!$resultado) {
						$this->error = "Error al insertar en \"hc_controles_paciente\" el control de Posiciones<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						return false;
					}
			}
			else
			{
				$query="UPDATE hc_controles_paciente SET evolucion_id=".$this->evolucion." WHERE ingreso=".$ctrlPosicion['ingreso']." AND control_id='".$ctrlPosicion['control_id']."'";
				$dbconn->BeginTrans();
				$resultado=$dbconn->Execute($query);
					if (!$resultado) {
						$this->error = "Error al insertar en \"hc_controles_paciente\" el control de Posiciones<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						return false;
					}
			}

			$query="SELECT * FROM hc_posicion_paciente WHERE evolucion_id=".$this->evolucion;
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado)
			{
				$this->error = "Error al consultar las posiciones del paciente en \"hc_posicion_paciente\" con evolucion_id=".$this->evolucion;
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			if (!$resultado->RecordCount()) {
				$query="INSERT INTO hc_posicion_paciente(evolucion_id,observaciones,posicion_id)
								VALUES (".$this->evolucion.",'$observaciones','$posicion')";
				$resultado=$dbconn->Execute($query);
					if ($resultado) {
						$dbconn->CommitTrans();
					}
					else {
						$this->error = "Error al insertar en \"hc_posicion_paciente\"<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				$this->FrmForma("");
			}
			else {
				$query="UPDATE hc_posicion_paciente SET observaciones='$observaciones', posicion_id='$posicion' WHERE evolucion_id=".$this->evolucion;
				$resultado=$dbconn->Execute($query);
					if ($resultado) {
						$dbconn->CommitTrans();
					}
					else {
						$this->error = "Error al insertar en \"hc_posicion_paciente\"<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				$this->FrmForma("");
			}
			return true;
		}


		function EditCtrlPosicion()
		{
			list($dbconn) = GetDBconn();
			$datos=array();

			$controles=$this->GetControles();
			$ctrlPosicion=$this->FindControles($controles,1,$this->ingreso);

			$query="SELECT * FROM hc_posicion_paciente WHERE evolucion_id=".$ctrlPosicion['evolucion_id'];
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado) {
				$this->error = "Error al buscar las posiciones del paciente en \"hc_posicion_paciente\" con la evolucion \"".$ctrlPosicion['evolucion_id']."\"<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			$data=$resultado->FetchNextObject($toUpper=false);
			$posicion=$this->GetControlPosicion($data->posicion_id,0);
			$datos['posicion_id']=$posicion[0]['posicion_id'];
			$datos['observacion']=$data->observaciones;
			$_REQUEST[$this->frmPrefijo."Posicion"]=$datos;
			$this->FrmForma($this->frmPrefijo."AddCtrlPosicion");
			return true;
		}


		function DelCtrlPosicion($controles,$ctrlPosicion)
		{
			list($dbconn) = GetDBconn();
			$fecha=date("d-m-Y H:i");
			$datos=array();

			$controles=$this->GetControles();
			$ctrlPosicion=$this->FindControles($controles,1,$this->ingreso);

			$query="DELETE FROM hc_controles_paciente WHERE ingreso=".$this->ingreso." AND control_id='".$ctrlPosicion['control_id']."'";
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado) {
				$this->error = "Error al tratar de eliminar el control del paciente en \"hc_controles_paciente\" con el ingreso \"".$this->ingreso."\"<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			$this->FrmForma("");
			return true;
		}


		function InsertCtrlOxig($concentracion,$metodo,$flujo,$ctrlOxig,$observaciones)
		{
			list($dbconn) = GetDBconn();

			$controles=$this->GetControles();
			$ctrlOxig=$this->FindControles($controles,2,$this->ingreso);

			if (empty($ctrlOxig['ingreso'])) {
				$query="INSERT INTO hc_controles_paciente(ingreso,control_id,evolucion_id)
								VALUES (".$this->ingreso.",'2',".$this->evolucion.")";
				$dbconn->BeginTrans();
				$resultado=$dbconn->Execute($query);
					if (!$resultado) {
						$this->error = "Error al insertar en \"hc_controles_paciente\" el control de Oxigenoterapia.<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						return false;
					}
			}
			else
			{
				$query="UPDATE hc_controles_paciente SET evolucion_id=".$this->evolucion." WHERE ingreso=".$ctrlOxig['ingreso']." AND control_id=".$ctrlOxig['control_id'];
				$dbconn->BeginTrans();
				$resultado=$dbconn->Execute($query);
					if (!$resultado) {
						$this->error = "Error al insertar en \"hc_controles_paciente\" el control de Oxigenoterapia.<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						return false;
					}
			}

			$query="SELECT * FROM hc_oxigenoterapia WHERE evolucion_id=".$this->evolucion;
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado)
			{
				$this->error = "Error al consultar la tabla \"hc_oxigenoterapia\" con evolucion_id=".$this->evolucion;
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			if (!$resultado->RecordCount()) {
				$query="INSERT INTO hc_oxigenoterapia(evolucion_id,observaciones,metodo_id,concentracion_id,flujo_id)
								VALUES (".$this->evolucion.",'$observaciones','$metodo','$concentracion','$flujo')";
				$resultado=$dbconn->Execute($query);
					if ($resultado) {
						$dbconn->CommitTrans();
					}
					else {
						$this->error = "Error al insertar en \"hc_oxigenoterapia\"<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				$this->FrmForma("");
			}
			else {
				$query="UPDATE hc_oxigenoterapia SET observaciones='$observaciones', metodo_id='$metodo', concentracion_id='$concentracion', flujo_id='$flujo' WHERE evolucion_id=".$this->evolucion;
				$resultado=$dbconn->Execute($query);
					if ($resultado) {
						$dbconn->CommitTrans();
					}
					else {
						$this->error = "Error al insertar en \"hc_oxigenoterapia\"<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				$this->FrmForma("");
			}
			return true;
		}


		function EditCtrlOxig()
		{
			list($dbconn) = GetDBconn();
			$datos=array();

			$controles=$this->GetControles();
			$ctrlOxig=$this->FindControles($controles,2,$this->ingreso);
			$query="SELECT * FROM hc_oxigenoterapia WHERE evolucion_id=".$ctrlOxig['evolucion_id'];
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado) {
				$this->error = "Error al buscar en la tabla \"hc_oxigenoterapia\" con la evolucion \"".$ctrlOxig['evolucion_id']."\"<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			$data=$resultado->FetchNextObject($toUpper=false);
			$metodo=$this->GetControlOxiMetodo($data->metodo_id,0);
			$concentracion=$this->GetControlOxiConcentraciones($data->concentracion_id,0);
			$flujo=$this->GetControlOxiFlujo($data->flujo_id,0);
			$datos['metodo_id']=$metodo[0]['metodo_id'];
			$datos['concentracion_id']=$concentracion[0]['concentracion_id'];
			$datos['flujo_id']=$flujo[0]['flujo_id'];
			$datos['observacion']=$data->observaciones;
			$_REQUEST[$this->frmPrefijo."Oxig"]=$datos;
			$this->FrmForma($this->frmPrefijo."AddCtrlOxig");
			return true;
		}


		function DelCtrlOxig()
		{
			list($dbconn) = GetDBconn();
			$fecha=date("d-m-Y H:i");
			$datos=array();

			$controles=$this->GetControles();
			$ctrlOxig=$this->FindControles($controles,2,$this->ingreso);

			$query="DELETE FROM hc_controles_paciente WHERE ingreso=".$this->ingreso." AND control_id='".$ctrlOxig['control_id']."'";
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado) {
				$this->error = "Error al tratar de eliminar el control del paciente en \"hc_controles_paciente\" con el ingreso \"".$this->ingreso."\"<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			$this->FrmForma("");
			return true;
		}


		function InsertCtrlReposo($reposo,$observaciones)
		{
			list($dbconn) = GetDBconn();

			$controles=$this->GetControles();
			$ctrlReposo=$this->FindControles($controles,3,$this->ingreso);

			if (empty($ctrlReposo['ingreso'])) {
				$query="INSERT INTO hc_controles_paciente(ingreso,control_id,evolucion_id)
								VALUES (".$this->ingreso.",'3',".$this->evolucion.")";
				$dbconn->BeginTrans();
				$resultado=$dbconn->Execute($query);
					if (!$resultado) {
						$this->error = "Error al insertar en \"hc_controles_paciente\" el control de Reposo.<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						return false;
					}
			}
			else
			{
				$query="UPDATE hc_controles_paciente SET evolucion_id=".$this->evolucion." WHERE ingreso=".$ctrlReposo['ingreso']." AND control_id=".$ctrlReposo['control_id'];
				$dbconn->BeginTrans();
				$resultado=$dbconn->Execute($query);
					if (!$resultado) {
						$this->error = "Error al insertar en \"hc_controles_paciente\" el control de Reposo.<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						return false;
					}
			}

			$query="SELECT * FROM hc_reposo_paciente WHERE evolucion_id=".$this->evolucion;
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado)
			{
				$this->error = "Error al consultar la tabla \"hc_reposo_paciente\" con evolucion_id=".$this->evolucion;
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			if (!$resultado->RecordCount()) {
				$query="INSERT INTO hc_reposo_paciente(evolucion_id,observaciones)
								VALUES (".$this->evolucion.",'$observaciones')";
				$resultado=$dbconn->Execute($query);
					if ($resultado) {
						foreach ($reposo as $key) {
							$query="INSERT INTO hc_reposo_paciente_detalle(evolucion_id,tipo_reposo_id)
											VALUES (".$this->evolucion.",'$key')";
							$resultado=$dbconn->Execute($query);
							if (!$resultado) {
								$dbconn->RollbackTrans();
								return false;
							}
						}
						$dbconn->CommitTrans();
						$this->FrmForma("");
					}
					else {
						$this->error = "Error al insertar en la tabla \"hc_reposo_paciente\"<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
			}
			else {
				$query="UPDATE hc_reposo_paciente SET observaciones='$observaciones' WHERE evolucion_id=".$this->evolucion;
				$resultado=$dbconn->Execute($query);
					if ($resultado) {
						$query="DELETE FROM hc_reposo_paciente_detalle WHERE evolucion_id=".$this->evolucion;
						$resultado=$dbconn->Execute($query);
						if ($resultado) {
							foreach ($reposo as $key) {
								$query="INSERT INTO hc_reposo_paciente_detalle(evolucion_id,tipo_reposo_id)
												VALUES (".$this->evolucion.",'$key')";
								$resultado=$dbconn->Execute($query);
								if (!$resultado) {
									$dbconn->RollbackTrans();
									return false;
								}
							}
						}
						$dbconn->CommitTrans();
						$this->FrmForma("");
					}
					else {
						$this->error = "Error al insertar en la tabla \"hc_reposo_paciente\"<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
			}
			return true;
		}


		function EditCtrlReposo()
		{
			list($dbconn) = GetDBconn();
			$datos=array();

			$controles=$this->GetControles();
			$ctrlReposo=$this->FindControles($controles,3,$this->ingreso);
			$query="SELECT * FROM hc_reposo_paciente WHERE evolucion_id=".$ctrlReposo['evolucion_id'];
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado) {
				$this->error = "Error al buscar en la tabla \"hc_reposo_paciente\" con la evolucion \"".$ctrlReposo['evolucion_id']."\"<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			$data=$resultado->FetchNextObject($toUpper=false);

			$query="SELECT * FROM hc_reposo_paciente_detalle WHERE evolucion_id=".$ctrlReposo['evolucion_id'];
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado) {
				$this->error = "Error al buscar en la tabla \"hc_reposo_paciente_detalle\" con la evolucion \"".$ctrlReposo['evolucion_id']."\"<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			while ($dataDtalle=$resultado->FetchNextObject($toUpper=false)) {
				$reposo[]=$dataDtalle->tipo_reposo_id;
			}
			$datos['CtlReposo']=$reposo;
			$datos['observacion']=$data->observaciones;
			$_REQUEST[$this->frmPrefijo."Reposo"]=$datos;
			$this->FrmForma($this->frmPrefijo."AddCtrlReposo");
			return true;
		}


		function DelCtrlReposo()
		{
			list($dbconn) = GetDBconn();
			$datos=array();

			$controles=$this->GetControles();
			$ctrlReposo=$this->FindControles($controles,3,$this->ingreso);

			$query="DELETE FROM hc_controles_paciente WHERE ingreso=".$this->ingreso." AND control_id='".$ctrlReposo['control_id']."'";
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado) {
				$this->error = "Error al tratar de eliminar el control del paciente en \"hc_controles_paciente\" con el ingreso \"".$this->ingreso."\"<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			$this->FrmForma("");
			return true;
		}


		function InsertCtrlLiquidos($observaciones)
		{
			list($dbconn) = GetDBconn();
			$controles=array();

			$controles=$this->GetControles();
			$ctrlLiquidos=$this->FindControles($controles,6,$this->ingreso);

			if (empty($observaciones)){
				$observaciones="Control Permanente";
			}

			if (empty($ctrlLiquidos['ingreso'])) {
				$query="INSERT INTO hc_controles_paciente(ingreso,control_id,evolucion_id)
								VALUES (".$this->ingreso.",'6',".$this->evolucion.")";

				$dbconn->BeginTrans();
				$resultado=$dbconn->Execute($query);
					if (!$resultado) {
						$this->error = "Error al insertar en \"hc_controles_paciente\" el control de Liquidos.<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						return false;
					}
			}
			else
			{
				$query="UPDATE hc_controles_paciente SET evolucion_id=".$this->evolucion." WHERE ingreso=".$controles[0]['ingreso']." AND control_id=".$controles[0]['control_id'];
				$dbconn->BeginTrans();
				$resultado=$dbconn->Execute($query);
					if (!$resultado) {
						$this->error = "Error al insertar en \"hc_controles_paciente\" el control de Liquidos.<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						return false;
					}
			}

			$query="SELECT * FROM hc_control_liquidos WHERE evolucion_id=".$this->evolucion;
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado)
			{
				$this->error = "Error al consultar la tabla \"hc_control_liquidos\" con evolucion_id=".$this->evolucion;
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			if (!$resultado->RecordCount()) {
				$query="INSERT INTO hc_control_liquidos(evolucion_id,observaciones)
								VALUES (".$this->evolucion.",'$observaciones')";
				$resultado=$dbconn->Execute($query);
					if ($resultado) {
						$dbconn->CommitTrans();
					}
					else {
						$this->error = "Error al insertar en la tabla \"hc_control_liquidos\"<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				$this->FrmForma("");
			}
			else {
				$query="UPDATE hc_control_liquidos SET observaciones='$observaciones' WHERE evolucion_id=".$this->evolucion;
				$resultado=$dbconn->Execute($query);
					if ($resultado) {
						$dbconn->CommitTrans();
					}
					else {
						$this->error = "Error al insertar en la tabla\"hc_control_liquidos\"<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				$this->FrmForma("");
			}
			return true;
		}


		function EditCtrlLiquidos()
		{
			list($dbconn) = GetDBconn();
			$datos=array();

			$controles=$this->GetControles();
			$ctrlLiquidos=$this->FindControles($controles,6,$this->ingreso);
			$query="SELECT * FROM hc_control_liquidos WHERE evolucion_id=".$ctrlLiquidos['evolucion_id'];
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado) {
				$this->error = "Error al buscar en la tabla \"hc_control_liquidos\" con la evolucion \"".$ctrlLiquidos['evolucion_id']."\"<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			$data=$resultado->FetchNextObject($toUpper=false);
			$datos['observacion']=$data->observaciones;
			$_REQUEST[$this->frmPrefijo."Liquidos"]=$datos;
			$this->FrmForma($this->frmPrefijo."AddCtrlLiquidos");
			return true;
		}


		function DelCtrlLiquidos()
		 {
				list($dbconn) = GetDBconn();
				$datos=array();

				$controles=$this->GetControles();
				$ctrlLiquidos=$this->FindControles($controles,6,$this->ingreso);

				$query="DELETE FROM hc_controles_paciente WHERE ingreso=".$this->ingreso." AND control_id='".$ctrlLiquidos['control_id']."'";
				$resultado=$this->Verifica_Conexion($query,$dbconn);
				if (!$resultado) {
					$this->error = "Error al tratar de eliminar el control del paciente en \"hc_controles_paciente\" con el ingreso \"".$this->ingreso."\"<br>";
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					return false;
				}
				$this->FrmForma("");
				return true;
		 }


		 function InsertCtrlPerAbdominal($observaciones)
		 {
				list($dbconn) = GetDBconn();

				$controles=array();
				$controles=$this->GetControles();
				$ctrlPerAbdominal=$this->FindControles($controles,12,$this->ingreso);

				if (empty($ctrlPerAbdominal['ingreso'])) {
					$query="INSERT INTO hc_controles_paciente(ingreso,control_id,evolucion_id)
									VALUES (".$this->ingreso.",'12',".$this->evolucion.")";
					$dbconn->BeginTrans();
					$resultado=$dbconn->Execute($query);
						if (!$resultado) {
							$this->error = "Error al insertar en \"hc_controles_paciente\" el control de Perimetro Abdominal.<br>";
							$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
							return false;
						}
				}
				else
				{
					$query="UPDATE hc_controles_paciente SET evolucion_id=".$this->evolucion." WHERE ingreso=".$controles[0]['ingreso']." AND control_id=".$controles[0]['control_id'];
					$dbconn->BeginTrans();
					$resultado=$dbconn->Execute($query);
						if (!$resultado) {
							$this->error = "Error al insertar en \"hc_controles_paciente\" el control de Perimetro Abdominal.<br>";
							$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
							return false;
						}
				}

				$query="SELECT * FROM hc_control_perimetro_abdominal WHERE evolucion_id=".$this->evolucion;
				$resultado=$this->Verifica_Conexion($query,$dbconn);
				if (!$resultado)
				{
					$this->error = "Error al consultar la tabla \"hc_control_perimetro_abdominal\" con evolucion_id=".$this->evolucion;
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					return false;
				}
				if (!$resultado->RecordCount()) {
					$query="INSERT INTO hc_control_perimetro_abdominal(evolucion_id,observaciones)
									VALUES (".$this->evolucion.",'$observaciones')";
					$resultado=$dbconn->Execute($query);
						if ($resultado) {
							$dbconn->CommitTrans();
						}
						else {
							$this->error = "Error al insertar en la tabla \"hc_control_perimetro_abdominal\"<br>";
							$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}
					$this->FrmForma("");
				}
				else {
					$query="UPDATE hc_control_perimetro_abdominal SET observaciones='$observaciones' WHERE evolucion_id=".$this->evolucion;
					$resultado=$dbconn->Execute($query);
						if ($resultado) {
							$dbconn->CommitTrans();
						}
						else {
							$this->error = "Error al insertar en la tabla\"hc_control_perimetro_abdominal\"<br>";
							$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}
					$this->FrmForma("");
				}
				return true;
		 }


		function EditCtrlPerAbdominal()
		{
			list($dbconn) = GetDBconn();
			$datos=array();

			$controles=$this->GetControles();
			$ctrlPerAbdominal=$this->FindControles($controles,12,$this->ingreso);
			$query="SELECT * FROM hc_control_perimetro_abdominal WHERE evolucion_id=".$ctrlPerAbdominal['evolucion_id'];
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado) {
				$this->error = "Error al buscar en la tabla \"hc_control_perimetro_abdominal\" con la evolucion \"".$ctrlLiquidos['evolucion_id']."\"<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			$data=$resultado->FetchNextObject($toUpper=false);
			$datos['observacion']=$data->observaciones;
			$_REQUEST[$this->frmPrefijo."PerAbdominal"]=$datos;
			$this->FrmForma($this->frmPrefijo."AddCtrlPerAbdominal");
			return true;
		}


		function DelCtrlPerAbdominal()
		{
			list($dbconn) = GetDBconn();
			$datos=array();

			$controles=$this->GetControles();
			$ctrlPerAbdominal=$this->FindControles($controles,12,$this->ingreso);

			$query="DELETE FROM hc_controles_paciente WHERE ingreso=".$this->ingreso." AND control_id='".$ctrlPerAbdominal['control_id']."'";
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado) {
				$this->error = "Error al tratar de eliminar el control del paciente en \"hc_controles_paciente\" con el ingreso \"".$this->ingreso."\"<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			$this->FrmForma("");
			return true;
		}


		function InsertCtrlPerCefalico($observaciones)
		{
			list($dbconn) = GetDBconn();
			$controles=array();

			$controles=$this->GetControles();
			$ctrlPerCefalico=$this->FindControles($controles,13,$this->ingreso);

			if (empty($ctrlPerCefalico['ingreso'])) {
				$query="INSERT INTO hc_controles_paciente(ingreso,control_id,evolucion_id)
								VALUES (".$this->ingreso.",'13',".$this->evolucion.")";
				$dbconn->BeginTrans();
				$resultado=$dbconn->Execute($query);
					if (!$resultado) {
						$this->error = "Error al insertar en \"hc_controles_paciente\" el control de Perimetro Cefalico.<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						return false;
					}
			}
			else
			{
				$query="UPDATE hc_controles_paciente SET evolucion_id=".$this->evolucion." WHERE ingreso=".$controles[0]['ingreso']." AND control_id=".$controles[0]['control_id'];
				$dbconn->BeginTrans();
				$resultado=$dbconn->Execute($query);
					if (!$resultado) {
						$this->error = "Error al insertar en \"hc_controles_paciente\" el control de Perimetro Cefalico.<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						return false;
					}
			}

			$query="SELECT * FROM hc_control_perimetro_cefalico WHERE evolucion_id=".$this->evolucion;
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado)
			{
				$this->error = "Error al consultar la tabla \"hc_control_perimetro_cefalico\" con evolucion_id=".$this->evolucion;
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			if (!$resultado->RecordCount()) {
				$query="INSERT INTO hc_control_perimetro_cefalico(evolucion_id,observaciones)
								VALUES (".$this->evolucion.",'$observaciones')";
				$resultado=$dbconn->Execute($query);
					if ($resultado) {
						$dbconn->CommitTrans();
					}
					else {
						$this->error = "Error al insertar en la tabla \"hc_control_perimetro_cefalico\"<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				$this->FrmForma("");
			}
			else {
				$query="UPDATE hc_control_perimetro_cefalico SET observaciones='$observaciones' WHERE evolucion_id=".$this->evolucion;
				$resultado=$dbconn->Execute($query);
					if ($resultado) {
						$dbconn->CommitTrans();
					}
					else {
						$this->error = "Error al insertar en la tabla\"hc_control_perimetro_cefalico\"<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				$this->FrmForma("");
			}
			return true;
		}


		function EditCtrlPerCefalico()
		{
			list($dbconn) = GetDBconn();
			$datos=array();

			$controles=$this->GetControles();
			$ctrlPerCefalico=$this->FindControles($controles,13,$this->ingreso);
			$query="SELECT * FROM hc_control_perimetro_cefalico WHERE evolucion_id=".$ctrlPerCefalico['evolucion_id'];
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado) {
				$this->error = "Error al buscar en la tabla \"hc_control_perimetro_cefalico\" con la evolucion \"".$ctrlLiquidos['evolucion_id']."\"<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			$data=$resultado->FetchNextObject($toUpper=false);
			$datos['observacion']=$data->observaciones;
			$_REQUEST[$this->frmPrefijo."PerCefalico"]=$datos;
			$this->FrmForma($this->frmPrefijo."AddCtrlPerCefalico");
			return true;
		}


		function DelCtrlPerCefalico()
		{
			list($dbconn) = GetDBconn();
			$datos=array();

			$controles=$this->GetControles();
			$ctrlPerCefalico=$this->FindControles($controles,13,$this->ingreso);

			$query="DELETE FROM hc_controles_paciente WHERE ingreso=".$this->ingreso." AND control_id='".$ctrlPerCefalico['control_id']."'";
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado) {
				$this->error = "Error al tratar de eliminar el control del paciente en \"hc_controles_paciente\" con el ingreso \"".$this->ingreso."\"<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			$this->FrmForma("");
			return true;
		}


		function InsertCtrlPerExtremidades($extremidad,$observaciones)
		{
			list($dbconn) = GetDBconn();

			$controles=$this->GetControles();
			$ctrlPerExtremidades=$this->FindControles($controles,14,$this->ingreso);

			if (empty($ctrlPerExtremidades['ingreso'])) {
				$query="INSERT INTO hc_controles_paciente(ingreso,control_id,evolucion_id)
								VALUES (".$this->ingreso.",'14',".$this->evolucion.")";
				$dbconn->BeginTrans();
				$resultado=$dbconn->Execute($query);
					if (!$resultado) {
						$this->error = "Error al insertar en \"hc_controles_paciente\" el control de Perimetro de Extremidades.<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						return false;
					}
			}
			else
			{
				$query="UPDATE hc_controles_paciente SET evolucion_id=".$this->evolucion." WHERE ingreso=".$ctrlPerExtremidades['ingreso']." AND control_id=".$ctrlPerExtremidades['control_id'];
				$dbconn->BeginTrans();
				$resultado=$dbconn->Execute($query);
					if (!$resultado) {
						$this->error = "Error al insertar en \"hc_controles_paciente\" el control de Perimetro de Extremidades.<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						return false;
					}
			}

			$query="SELECT * FROM hc_control_perimetro_extremidades WHERE evolucion_id=".$this->evolucion;
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado)
			{
				$this->error = "Error al consultar la tabla \"hc_control_perimetro_extremidades\" con evolucion_id=".$this->evolucion;
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			if (!$resultado->RecordCount()) {
				$query="INSERT INTO hc_control_perimetro_extremidades(evolucion_id,observaciones)
								VALUES (".$this->evolucion.",'$observaciones')";
				$resultado=$dbconn->Execute($query);
					if ($resultado) {
						foreach ($extremidad as $key) {
							$query="INSERT INTO hc_control_perimetro_extremidades_detalle(evolucion_id,tipo_extremidad_id)
											VALUES (".$this->evolucion.",'$key')";
							$resultado=$dbconn->Execute($query);
							if (!$resultado) {
								$dbconn->RollbackTrans();
								return false;
							}
						}
						$dbconn->CommitTrans();
						$this->FrmForma("");
					}
					else {
						$this->error = "Error al insertar en la tabla \"hc_control_perimetro_extremidades\"<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
			}
			else {
				$query="UPDATE hc_control_perimetro_extremidades SET observaciones='$observaciones' WHERE evolucion_id=".$this->evolucion;
				$resultado=$dbconn->Execute($query);
					if ($resultado) {
						$query="DELETE FROM hc_control_perimetro_extremidades_detalle WHERE evolucion_id=".$this->evolucion;
						$resultado=$dbconn->Execute($query);
						if ($resultado) {
							foreach ($extremidad as $key) {
								$query="INSERT INTO hc_control_perimetro_extremidades_detalle(evolucion_id,tipo_extremidad_id)
												VALUES (".$this->evolucion.",'$key')";
								$resultado=$dbconn->Execute($query);
								if (!$resultado) {
									$dbconn->RollbackTrans();
									return false;
								}
							}
						}
						$dbconn->CommitTrans();
						$this->FrmForma("");
					}
					else {
						$this->error = "Error al insertar en la tabla \"hc_control_perimetro_extremidades\"<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
			}
			return true;
		}


		function EditCtrlPerExtremidades()
		{
			list($dbconn) = GetDBconn();
			$extremidad=$datos=array();

			$controles=$this->GetControles();
			$ctrlPerExtremidades=$this->FindControles($controles,14,$this->ingreso);
			$query="SELECT * FROM hc_control_perimetro_extremidades WHERE evolucion_id=".$ctrlPerExtremidades['evolucion_id'];
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado) {
				$this->error = "Error al buscar en la tabla \"hc_control_perimetro_extremidades\" con la evolucion \"".$ctrlPerExtremidades['evolucion_id']."\"<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			$data=$resultado->FetchNextObject($toUpper=false);

			$query="SELECT * FROM hc_control_perimetro_extremidades_detalle WHERE evolucion_id=".$ctrlPerExtremidades['evolucion_id'];
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado) {
				$this->error = "Error al buscar en la tabla \"hc_control_perimetro_extremidades_detalle\" con la evolucion \"".$ctrlPerExtremidades['evolucion_id']."\"<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			while ($dataDtalle=$resultado->FetchNextObject($toUpper=false)) {
				$extremidad[]=$dataDtalle->tipo_extremidad_id;
			}
			$datos['CtlPerExtremidades']=$extremidad;
			$datos['observacion']=$data->observaciones;
			$_REQUEST[$this->frmPrefijo."PerExtremidades"]=$datos;
			$this->FrmForma($this->frmPrefijo."AddCtrlPerExtremidades");
			return true;
		}


		function DelCtrlPerExtremidades()
		{
			list($dbconn) = GetDBconn();
			$datos=array();

			$controles=$this->GetControles();
			$ctrlPerExtremidades=$this->FindControles($controles,14,$this->ingreso);

			$query="DELETE FROM hc_controles_paciente WHERE ingreso=".$this->ingreso." AND control_id='".$ctrlPerExtremidades['control_id']."'";
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado) {
				$this->error = "Error al tratar de eliminar el control del paciente en \"hc_controles_paciente\" con el ingreso \"".$this->ingreso."\"<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			$this->FrmForma("");
			return true;
		}


		function InsertCtrlParto($observaciones)
		{
			list($dbconn) = GetDBconn();
			$controles=array();

			$controles=$this->GetControles();
			$ctrlParto=$this->FindControles($controles,11,$this->ingreso);

			if (empty($ctrlParto['ingreso'])) {
				$query="INSERT INTO hc_controles_paciente(ingreso,control_id,evolucion_id)
								VALUES (".$this->ingreso.",'11',".$this->evolucion.")";
				$dbconn->BeginTrans();
				$resultado=$dbconn->Execute($query);
					if (!$resultado) {
						$this->error = "Error al insertar en \"hc_controles_paciente\" el control de Trabajo de parto.<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						return false;
					}
			}
			else
			{
				$query="UPDATE hc_controles_paciente SET evolucion_id=".$this->evolucion." WHERE ingreso=".$controles[0]['ingreso']." AND control_id=".$controles[0]['control_id'];
				$dbconn->BeginTrans();
				$resultado=$dbconn->Execute($query);
					if (!$resultado) {
						$this->error = "Error al insertar en \"hc_controles_paciente\" el control de Trabajo de parto.<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						return false;
					}
			}

			$query="SELECT * FROM hc_control_trabajo_parto WHERE evolucion_id=".$this->evolucion;
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado)
			{
				$this->error = "Error al consultar la tabla \"hc_control_trabajo_parto\" con evolucion_id=".$this->evolucion;
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			if (!$resultado->RecordCount()) {
				$query="INSERT INTO hc_control_trabajo_parto(evolucion_id,observaciones)
								VALUES (".$this->evolucion.",'$observaciones')";
				$resultado=$dbconn->Execute($query);
					if ($resultado) {
						$dbconn->CommitTrans();
					}
					else {
						$this->error = "Error al insertar en la tabla \"hc_control_trabajo_parto\"<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				$this->FrmForma("");
			}
			else {
				$query="UPDATE hc_control_trabajo_parto SET observaciones='$observaciones' WHERE evolucion_id=".$this->evolucion;
				$resultado=$dbconn->Execute($query);
					if ($resultado) {
						$dbconn->CommitTrans();
					}
					else {
						$this->error = "Error al insertar en la tabla\"hc_control_trabajo_parto\"<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				$this->FrmForma("");
			}
			return true;
		}


		function EditCtrlParto()
		{
			list($dbconn) = GetDBconn();
			$datos=array();

			$controles=$this->GetControles();
			$ctrlParto=$this->FindControles($controles,11,$this->ingreso);
			$query="SELECT * FROM hc_control_trabajo_parto WHERE evolucion_id=".$ctrlParto['evolucion_id'];
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado) {
				$this->error = "Error al buscar en la tabla \"hc_control_trabajo_parto\" con la evolucion \"".$ctrlParto['evolucion_id']."\"<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			$data=$resultado->FetchNextObject($toUpper=false);
			$datos['observacion']=$data->observaciones;
			$_REQUEST[$this->frmPrefijo."Parto"]=$datos;
			$this->FrmForma($this->frmPrefijo."AddCtrlParto");
			return true;
		}


		function DelCtrlParto()
		{
			list($dbconn) = GetDBconn();
			$datos=array();

			$controles=$this->GetControles();
			$ctrlParto=$this->FindControles($controles,11,$this->ingreso);

			$query="DELETE FROM hc_controles_paciente WHERE ingreso=".$this->ingreso." AND control_id='".$ctrlParto['control_id']."'";
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado) {
				$this->error = "Error al tratar de eliminar el control del paciente en \"hc_controles_paciente\" con el ingreso \"".$this->ingreso."\"<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			$this->FrmForma("");
			return true;
		}


		function InsertCtrlGral($tabla,$control_id,$frecuencia,$observaciones)
		{
			list($dbconn) = GetDBconn();
			$controles=array();

			$controles=$this->GetControles();
			$ctrl_gral=$this->FindControles($controles,$control_id,$this->ingreso);

			if (empty($ctrl_gral['ingreso'])) {
				$query="INSERT INTO hc_controles_paciente(ingreso,control_id,evolucion_id)
								VALUES (".$this->ingreso.",'$control_id',".$this->evolucion.")";
				$dbconn->BeginTrans();
				$resultado=$dbconn->Execute($query);
					if (!$resultado) {
						$this->error = "Error al insertar en \"hc_controles_paciente\" el control de ".$_REQUEST[$this->frmPrefijo.'control_descripcion'].".<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						return false;
					}
			}
			else
			{
				$query="UPDATE hc_controles_paciente
								SET evolucion_id=".$this->evolucion."
								WHERE ingreso=".$ctrl_gral['ingreso']." AND
											control_id=".$ctrl_gral['control_id'];
				$dbconn->BeginTrans();
				$resultado=$dbconn->Execute($query);
					if (!$resultado) {
						$this->error = "Error al insertar en \"hc_controles_paciente\" el control de ".$_REQUEST[$this->frmPrefijo.'control_descripcion'].".<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						return false;
					}
			}

			$query="SELECT *
							FROM $tabla
							WHERE evolucion_id=".$this->evolucion;
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado)
			{
				$this->error = "Error al consultar la tabla \"$tabla\" con evolucion_id=".$this->evolucion;
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			if (!$resultado->RecordCount()) {
				$query="INSERT INTO $tabla(evolucion_id,observaciones,frecuencia_id)
								VALUES (".$this->evolucion.",'$observaciones','$frecuencia')";
				$resultado=$dbconn->Execute($query);
					if ($resultado) {
						$dbconn->CommitTrans();
					}
					else {
						$this->error = "Error al insertar en la tabla \"$tabla\"<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				$this->FrmForma("");
			}
			else {
				$query="UPDATE $tabla
								SET observaciones='$observaciones',
										frecuencia_id='$frecuencia'
								WHERE evolucion_id=".$this->evolucion;
				$resultado=$dbconn->Execute($query);
					if ($resultado) {
						$dbconn->CommitTrans();
					}
					else {
						$this->error = "Error al insertar en la tabla\"$tabla\"<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				$this->FrmForma("");
			}
			return true;
		}


		function EditCtrlGral()
		{
			list($dbconn) = GetDBconn();
			$control_id=$_REQUEST[$this->frmPrefijo.'control_id'];
			$tabla=$_REQUEST[$this->frmPrefijo.'tabla'];
			$datos=array();

			$controles=$this->GetControles();
			$ctrl_gral=$this->FindControles($controles,$control_id,$this->ingreso);
			$query="SELECT *
							FROM $tabla
							WHERE evolucion_id=".$ctrl_gral['evolucion_id'];

			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado) {
				$this->error = "Error al buscar en la tabla \"$tabla\" con la evolucion \"".$ctrl_gral['evolucion_id']."\"<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			$data=$resultado->FetchNextObject($toUpper=false);
			$frecuencia=$this->GetAllTipoControles($_REQUEST[$this->frmPrefijo.'tabla_tipo'],$data->frecuencia_id,0);
			$datos['frecuencia_id']=$frecuencia['frecuencia_id'];
			$datos['observacion']=$data->observaciones;
			$_REQUEST[$this->frmPrefijo."CtrlGral"]=$datos;
			$this->FrmForma($this->frmPrefijo."AddCtrlGral");
			return true;
		}


		function DelCtrlGral()
		{
			list($dbconn) = GetDBconn();
			$control_id=$_REQUEST[$this->frmPrefijo.'control_id'];

			$query="DELETE
							FROM hc_agenda_controles
							WHERE ingreso=".$this->ingreso." AND
										control_id='".$control_id."'";

			$dbconn->BeginTrans();
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if ($resultado) {
				$query="DELETE
								FROM hc_controles_paciente
								WHERE ingreso=".$this->ingreso." AND
											control_id='".$control_id."'";

				$resultado=$this->Verifica_Conexion($query,$dbconn);
				if (!$resultado) {
					$this->error = "Error al tratar de eliminar el control del paciente en \"hc_controles_paciente\" con el ingreso \"".$this->ingreso."\"<br>";
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
				else
					$dbconn->CommitTrans();
			}
			else{
				$this->error = "Error al tratar de eliminar el control del paciente en \"hc_agenda_controles\" con el ingreso \"".$this->ingreso."\"<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			$this->FrmForma("");
			return true;
		}


		function TraerInformacionAyuno()
		{
			list($dbconn) = GetDBconn();
			$query="SELECT motivo,hora_fin_ayuno,hora_inicio_ayuno FROM hc_solicitudes_dietas_ayunos WHERE ingreso=".$this->ingreso."
												AND fecha='".date("Y-m-d")."'";
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado) {
				$this->error = "Error al buscar en la tabla \"hc_control_dietas\" con la evolucion \"".$ctrlDietas['evolucion_id']."\"<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}

			if($resultado->EOF)
			{return '';}

			$var[0]=$resultado->fields[0];
			$var[1]=$resultado->fields[1];
			$var[2]=$resultado->fields[2];
			return $var;
		}


		function EditCtrlDietas()
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$datos=array();

			$controles=$this->GetControles();
			$ctrlDietas=$this->FindControles($controles,25,$this->ingreso);
			$query="SELECT * FROM hc_solicitudes_dietas WHERE evolucion_id=".$ctrlDietas['evolucion_id'];
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado) {
				$this->error = "Error al buscar en la tabla \"hc_control_dietas\" con la evolucion \"".$ctrlDietas['evolucion_id']."\"<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			$data=$resultado->FetchNextObject($toUpper=false);

			$query="SELECT *
                       FROM hc_solicitudes_dietas
                       WHERE evolucion_id=".$ctrlDietas['evolucion_id'];
			
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado) {
				$this->error = "Error al buscar en la tabla \"hc_control_dietas\" con la evolucion \"".$ctrlDietas['evolucion_id']."\"<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			while ($data_d=$resultado->FetchRow()) {
				$dieta_d=$data_d['hc_dieta_id'];
                    $fraccionada = $data_d['sw_fraccionada'];
				$horario[$data_d['horario_id']][]=$data_d['hc_dieta_id'];
			}
               $datos['fraccionada']= $fraccionada;
               $datos['CtlDietas']=$dieta_d;
			$vect=array_keys($horario);
			foreach($vect as $key){
				$_REQUEST[$this->frmPrefijo."CtlDietas_".$key]=$horario[$key];
			}

			$datos['observacion']=$data->observaciones;
			$_REQUEST[$this->frmPrefijo."Dietas"]=$datos;
			$this->FrmForma($this->frmPrefijo."AddCtrlDietas");
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			return true;
		}


		function DelCtrlDietas()
		{
			list($dbconn) = GetDBconn();
			$fecha=date("d-m-Y H:i");
			$datos=array();

			$controles=$this->GetControles();
			$ctrlDietas=$this->FindControles($controles,25,$this->ingreso);

			$query="DELETE FROM hc_controles_paciente WHERE ingreso=".$this->ingreso." AND control_id='".$ctrlDietas['control_id']."'";
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado) {
				$this->error = "Error al tratar de eliminar el control del paciente en \"hc_controles_paciente\" con el ingreso \"".$this->ingreso."\"<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			$this->FrmForma("");
			return true;
		}



		function InsertCtrlTransfusiones($observaciones)
		{
			list($dbconn) = GetDBconn();
			$controles=array();

			$controles=$this->GetControles();
			$ctrlTransfusiones=$this->FindControles($controles,24,$this->ingreso);

			if (empty($ctrlTransfusiones['ingreso'])) {
				$query="INSERT INTO hc_controles_paciente(ingreso,control_id,evolucion_id)
								VALUES (".$this->ingreso.",'24',".$this->evolucion.")";

				$dbconn->BeginTrans();
				$resultado=$dbconn->Execute($query);
					if (!$resultado) {
						$this->error = "Error al insertar en \"hc_controles_paciente\" el control de Transfusiones.<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						return false;
					}
			}
			else
			{
				$query="UPDATE hc_controles_paciente SET evolucion_id=".$this->evolucion." WHERE ingreso=".$controles[0]['ingreso']." AND control_id=".$controles[0]['control_id'];
				$dbconn->BeginTrans();
				$resultado=$dbconn->Execute($query);
					if (!$resultado) {
						$this->error = "Error al insertar en \"hc_controles_paciente\" el control de Transfusiones.<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						return false;
					}
			}

			$query="SELECT * FROM hc_transfusiones WHERE evolucion_id=".$this->evolucion;
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado)
			{
				$this->error = "Error al consultar la tabla \"hc_transfusiones\" con evolucion_id=".$this->evolucion;
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			if (!$resultado->RecordCount()) {
				$query="INSERT INTO hc_transfusiones(evolucion_id,observaciones)
								VALUES (".$this->evolucion.",'$observaciones')";
				$resultado=$dbconn->Execute($query);
					if ($resultado) {
						$dbconn->CommitTrans();
					}
					else {
						$this->error = "Error al insertar en la tabla \"hc_transfusiones\"<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				$this->FrmForma("");
			}
			else {
				$query="UPDATE hc_transfusiones SET observaciones='$observaciones' WHERE evolucion_id=".$this->evolucion;
				$resultado=$dbconn->Execute($query);
					if ($resultado) {
						$dbconn->CommitTrans();
					}
					else {
						$this->error = "Error al insertar en la tabla\"hc_transfusiones\"<br>";
						$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				$this->FrmForma("");
			}
			return true;
		}


		function EditCtrlTransfusiones()
		{
			list($dbconn) = GetDBconn();
			$datos=array();

			$controles=$this->GetControles();
			$ctrlTransfusiones=$this->FindControles($controles,24,$this->ingreso);
			$query="SELECT * FROM hc_transfusiones WHERE evolucion_id=".$ctrlTransfusiones['evolucion_id'];
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado) {
				$this->error = "Error al buscar en la tabla \"hc_transfusiones\" con la evolucion \"".$ctrlTransfusiones['evolucion_id']."\"<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			$data=$resultado->FetchNextObject($toUpper=false);
			$datos['observaciones']=$data->observaciones;
			$_REQUEST[$this->frmPrefijo."Transfusiones"]=$datos;
			$this->FrmForma($this->frmPrefijo."AddCtrlTransfusiones");
			return true;
		}


		function DelCtrlTransfusiones()
          {
               list($dbconn) = GetDBconn();
               $datos=array();

               $controles=$this->GetControles();
               $ctrlTransfusiones=$this->FindControles($controles,24,$this->ingreso);

               $query="DELETE FROM hc_controles_paciente WHERE ingreso=".$this->ingreso." AND control_id='".$ctrlTransfusiones['control_id']."'";
               $resultado=$this->Verifica_Conexion($query,$dbconn);
               if (!$resultado) {
                    $this->error = "Error al tratar de eliminar el control del paciente en \"hc_controles_paciente\" con el ingreso \"".$this->ingreso."\"<br>";
                    $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
                    return false;
               }
               $this->FrmForma("");
               return true;
          }

          function InsertCtrlAdicionales($observaciones)
     	{
               list($dbconn) = GetDBconn();
               $controles=array();
     
               $controles=$this->GetControles();
               $ctrlAdicionales=$this->FindControles($controles,27,$this->ingreso);
     
               if (empty($ctrlAdicionales['ingreso'])) {
                    $query="INSERT INTO hc_controles_paciente(ingreso,control_id,evolucion_id)
                                        VALUES (".$this->ingreso.",'27',".$this->evolucion.")";
     
                    $dbconn->BeginTrans();
                    $resultado=$dbconn->Execute($query);
                    if (!$resultado) {
                         $this->error = "Error al insertar en \"hc_controles_paciente\" el control de Transfusiones.<br>";
                         $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
                         return false;
                    }
               }
               else
               {
                    $query="UPDATE hc_controles_paciente SET evolucion_id=".$this->evolucion." WHERE ingreso=".$controles[0]['ingreso']." AND control_id=".$controles[0]['control_id'];
                    $dbconn->BeginTrans();
                    $resultado=$dbconn->Execute($query);
                         if (!$resultado) {
                              $this->error = "Error al insertar en \"hc_controles_paciente\" el control de Transfusiones.<br>";
                              $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
                              return false;
                         }
               }
     
               $query="SELECT * FROM hc_control_adicionales WHERE evolucion_id=".$this->evolucion;
               $resultado=$this->Verifica_Conexion($query,$dbconn);
               if (!$resultado)
               {
                    $this->error = "Error al consultar la tabla \"hc_control_adicionales\" con evolucion_id=".$this->evolucion;
                    $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
                    return false;
               }
               if (!$resultado->RecordCount()) {
                    $query="INSERT INTO hc_control_adicionales (evolucion_id, observaciones)
                                             VALUES (".$this->evolucion.", '$observaciones')";
                    $resultado=$dbconn->Execute($query);
                    if ($resultado) {
                         $dbconn->CommitTrans();
                    }
                    else {
                         $this->error = "Error al insertar en la tabla \"hc_control_adicionales\"<br>";
                         $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
                         $dbconn->RollbackTrans();
                         return false;
                    }
                    $this->FrmForma("");
               }
               else {
                    $query="UPDATE hc_control_adicionales SET observaciones='$observaciones' WHERE evolucion_id=".$this->evolucion;
                    $resultado=$dbconn->Execute($query);
                    if ($resultado) {
                         $dbconn->CommitTrans();
                    }
                    else {
                         $this->error = "Error al insertar en la tabla\"hc_control_adicionales\"<br>";
                         $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
                         $dbconn->RollbackTrans();
                         return false;
                    }
                    $this->FrmForma("");
               }
               return true;
          }
          
          function EditCtrlAdicionales()
		{
			list($dbconn) = GetDBconn();
			$datos=array();

			$controles=$this->GetControles();
			$ctrlAdicionales=$this->FindControles($controles,27,$this->ingreso);
			$query="SELECT * FROM hc_control_adicionales WHERE evolucion_id=".$ctrlAdicionales['evolucion_id'];
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado) {
				$this->error = "Error al buscar en la tabla \"hc_control_adicionales\" con la evolucion \"".$ctrlAdicionales['evolucion_id']."\"<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			$data=$resultado->FetchNextObject($toUpper=false);
			$datos['observaciones']=$data->observaciones;
			$_REQUEST[$this->frmPrefijo."Adicionales"]=$datos;
			$this->FrmForma($this->frmPrefijo."AddCtrlAdicionales");
			return true;
		}
          
          function DelCtrlAdicionales()
          {
               list($dbconn) = GetDBconn();
               $datos=array();

               $controles=$this->GetControles();
               $ctrlAdicionales=$this->FindControles($controles,27,$this->ingreso);

               $query="DELETE FROM hc_controles_paciente WHERE ingreso=".$this->ingreso." AND control_id='".$ctrlAdicionales['control_id']."'";
               $resultado=$this->Verifica_Conexion($query,$dbconn);
               if (!$resultado) {
                    $this->error = "Error al tratar de eliminar el control del paciente en \"hc_controles_paciente\" con el ingreso \"".$this->ingreso."\"<br>";
                    $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
                    return false;
               }
               $this->FrmForma("");
               return true;
          }


          function InsertCtrlDrenajes($observaciones, $tipo_drenaje)
     	{
               list($dbconn) = GetDBconn();
               $controles=array();
     
               $controles=$this->GetControles();
               $ctrlDrenajes=$this->FindControles($controles,26,$this->ingreso);
     
               if (empty($ctrlDrenajes['ingreso'])) {
                    $query="INSERT INTO hc_controles_paciente(ingreso,control_id,evolucion_id)
                                        VALUES (".$this->ingreso.",'26',".$this->evolucion.")";
     
                    $dbconn->BeginTrans();
                    $resultado=$dbconn->Execute($query);
                    if (!$resultado) {
                         $this->error = "Error al insertar en \"hc_controles_paciente\" el control de Transfusiones.<br>";
                         $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
                         return false;
                    }
               }
               else
               {
                    $query="UPDATE hc_controles_paciente SET evolucion_id=".$this->evolucion." WHERE ingreso=".$controles[0]['ingreso']." AND control_id=".$controles[0]['control_id'];
                    $dbconn->BeginTrans();
                    $resultado=$dbconn->Execute($query);
                         if (!$resultado) {
                              $this->error = "Error al insertar en \"hc_controles_paciente\" el control de Transfusiones.<br>";
                              $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
                              return false;
                         }
               }
     
               $query="SELECT * FROM hc_control_drenajes WHERE evolucion_id=".$this->evolucion;
               $resultado=$this->Verifica_Conexion($query,$dbconn);
               if (!$resultado)
               {
                    $this->error = "Error al consultar la tabla \"hc_control_drenajes\" con evolucion_id=".$this->evolucion;
                    $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
                    return false;
               }
               if (!$resultado->RecordCount()) {
                    $query="INSERT INTO hc_control_drenajes (evolucion_id, observaciones, tipo_drenaje)
                                             VALUES (".$this->evolucion.", '$observaciones', ".$tipo_drenaje.")";
                    $resultado=$dbconn->Execute($query);
                    if ($resultado) {
                         $dbconn->CommitTrans();
                    }
                    else {
                         $this->error = "Error al insertar en la tabla \"hc_control_drenajes\"<br>";
                         $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
                         $dbconn->RollbackTrans();
                         return false;
                    }
                    $this->FrmForma("");
               }
               else {
                    $query="UPDATE hc_control_drenajes 
                    	   SET observaciones = '$observaciones', 
                                tipo_drenaje = ".$tipo_drenaje." 
                            WHERE evolucion_id=".$this->evolucion;
                    $resultado=$dbconn->Execute($query);
                    if ($resultado) {
                         $dbconn->CommitTrans();
                    }
                    else {
                         $this->error = "Error al insertar en la tabla\"hc_control_drenajes\"<br>";
                         $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
                         $dbconn->RollbackTrans();
                         return false;
                    }
                    $this->FrmForma("");
               }
               return true;
          }
          
          
          function EditCtrlDrenajes()
		{
			list($dbconn) = GetDBconn();
			$datos=array();

			$controles=$this->GetControles();
			$ctrlDrenajes=$this->FindControles($controles,26,$this->ingreso);
			$query="SELECT * FROM hc_control_drenajes WHERE evolucion_id=".$ctrlDrenajes['evolucion_id'];
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado) {
				$this->error = "Error al buscar en la tabla \"hc_control_drenajes\" con la evolucion \"".$ctrlDrenajes['evolucion_id']."\"<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			
               $data = $resultado->FetchNextObject($toUpper=false);
			$datos['observaciones'] = $data->observaciones;
               $datos['tipo_drenaje'] = $data->tipo_drenaje;
			
               $_REQUEST[$this->frmPrefijo."Drenajes"] = $datos;
			$this->FrmForma($this->frmPrefijo."AddCtrlDrenajes");
			return true;
		}
          
          
          function DelCtrlDrenajes()
          {
               list($dbconn) = GetDBconn();
               $datos=array();

               $controles=$this->GetControles();
               $ctrlDrenajes=$this->FindControles($controles,26,$this->ingreso);

               $query="DELETE FROM hc_controles_paciente WHERE ingreso=".$this->ingreso." AND control_id='".$ctrlDrenajes['control_id']."'";
               $resultado=$this->Verifica_Conexion($query,$dbconn);
               if (!$resultado) {
                    $this->error = "Error al tratar de eliminar el control del paciente en \"hc_controles_paciente\" con el ingreso \"".$this->ingreso."\"<br>";
                    $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
                    return false;
               }
               $this->FrmForma("");
               return true;
          }
         

//Fin de los insert, edit y del de los controles
//**************************************************/


		function Gestacion($datos_hc)
		{
			list($dbconn) = GetDBconn();
			$query="SELECT * FROM gestacion WHERE tipo_id_paciente='".$datos_hc['tipoidpaciente']."' AND paciente_id='".$datos_hc['paciente_id']."' ";

			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado) {
				$this->error = "Error al ejecutar el query <br>".$query;
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			return $resultado->FetchNextObject($toUpper=false);
		}


		function GetCControlPosicion($control)
		{
			list($dbconn) = GetDBconn();
			$query="SELECT * FROM hc_posicion_paciente WHERE evolucion_id=".$control['evolucion_id'];

			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado)
			{
				$this->error = "Error al consultar las posiciones del paciente en \"hc_posicion_paciente\" con evolucion_id=".$control['evolucion_id'];
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			return $resultado->FetchNextObject($toUpper=false);
		}


		function GetCOxigenoterapia($control)
		{
			list($dbconn) = GetDBconn();
			$query="SELECT * FROM hc_oxigenoterapia WHERE evolucion_id=".$control['evolucion_id'];
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado)
			{
				$this->error = "Error al consultar las posiciones del paciente en \"hc_oxigenoterapia\" con evolucion_id=".$control['evolucion_id'];
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			return $resultado->FetchNextObject($toUpper=false);
		}


		function GetCControlReposoDetalle($control)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$query="SELECT reposo_d.*,
											tipo_r.descripcion
							FROM hc_reposo_paciente_detalle reposo_d,
										hc_tipos_reposo_paciente tipo_r
							WHERE reposo_d.evolucion_id=".$control['evolucion_id']." AND
										tipo_r.tipo_reposo_id=reposo_d.tipo_reposo_id ;
							";

			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado)
			{
				$this->error = "Error al consultar la tabla \"hc_reposo_paciente_detalle\" con evolucion_id=".$control['evolucion_id'];
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			while ($data = $resultado->FetchRow()) {
				$reposo_d[]=$data;
			}
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			return $reposo_d;
		}


		function GetCControlReposo($control)
		{
			list($dbconn) = GetDBconn();
			$query2="SELECT * FROM hc_reposo_paciente WHERE evolucion_id=".$control['evolucion_id'];
			$resultado2=$this->Verifica_Conexion($query2,$dbconn);
			if (!$resultado2)
			{
				$this->error = "Error al consultar la tabla \"hc_reposo_paciente_detalle\" con evolucion_id=".$control['evolucion_id'];
				$this->mensajeDeError = $query2."<br>".$dbconn->ErrorMsg();
				return false;
			}
			return $resultado2->FetchNextObject($toUpper=false);
		}

		function GetCControlLiquidos($control)
		{
			list($dbconn) = GetDBconn();
			$query="SELECT * FROM hc_control_liquidos WHERE evolucion_id=".$control['evolucion_id'];
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado)
			{
				$this->error = "Error al consultar la tabla \"hc_control_liquidos\" con evolucion_id=".$control['evolucion_id'];
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			return $resultado->FetchNextObject($toUpper=false);
		}


		function GetCPerimetroAbdominal($control)
		{
			list($dbconn) = GetDBconn();
			$query="SELECT * FROM hc_control_perimetro_abdominal WHERE evolucion_id=".$control['evolucion_id'];
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado)
			{
				$this->error = "Error al consultar la tabla \"hc_control_perimetro_abdominal\" con evolucion_id=".$control['evolucion_id'];
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			return $resultado->FetchNextObject($toUpper=false);
		}


		function GetCPerimetroCefalico($control)
		{
			list($dbconn) = GetDBconn();
			$query="SELECT * FROM hc_control_perimetro_cefalico WHERE evolucion_id=".$control['evolucion_id'];
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado)
			{
				$this->error = "Error al consultar la tabla \"hc_control_perimetro_cefalico\" con evolucion_id=".$control['evolucion_id'];
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			return $resultado->FetchNextObject($toUpper=false);
		}


		function GetCPerimetroExtremidadesDetalle($control)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$query="SELECT per_d.*,
											tipo_ext.descripcion
							FROM hc_control_perimetro_extremidades_detalle per_d,
									hc_tipos_extremidades_paciente tipo_ext
							WHERE per_d.evolucion_id=".$control['evolucion_id']." AND
										tipo_ext.tipo_extremidad_id=per_d.tipo_extremidad_id
							";
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado)
			{
				$this->error = "Error al consultar la tabla \"hc_control_perimetro_extremidades_detalle\" con evolucion_id=".$control['evolucion_id'];
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			while ($data = $resultado->FetchRow()) {
				$extremidades_d[]=$data;
			}
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			return $extremidades_d;
		}

		function GetCPerimetroExtremidades($control)
		{
			list($dbconn) = GetDBconn();
			$query2="SELECT * FROM hc_control_perimetro_extremidades WHERE evolucion_id=".$control['evolucion_id'];
			$resultado2=$this->Verifica_Conexion($query2,$dbconn);
			if (!$resultado2)
			{
				$this->error = "Error al consultar la tabla \"hc_control_perimetro_extremidades\" con evolucion_id=".$control['evolucion_id'];
				$this->mensajeDeError = $query2."<br>".$dbconn->ErrorMsg();
				return false;
			}
			return $resultado2->FetchNextObject($toUpper=false);
		}


		function GetCControlParto($control)
		{
			list($dbconn) = GetDBconn();
			$query="SELECT * FROM hc_control_trabajo_parto WHERE evolucion_id=".$control['evolucion_id'];
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado)
			{
				$this->error = "Error al consultar la tabla \"hc_control_trabajo_parto\" con evolucion_id=".$control['evolucion_id'];
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			return $resultado->FetchNextObject($toUpper=false);
		}


		function GetCControlDietas($control)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$query="SELECT *
                       FROM hc_solicitudes_dietas
                       WHERE evolucion_id=".$control['evolucion_id'];
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado)
			{
				$this->error = "Error al consultar la tabla \"hc_control_dietas_detalle\" con evolucion_id=".$control['evolucion_id'];
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			return $resultado->FetchRow();
		}


		function GetCControlDietasDetalle($control)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$query="SELECT dietas_d.*, dietas.descripcion
				   FROM hc_solicitudes_dietas dietas_d,
					   hc_tipos_dieta dietas
				   WHERE dietas_d.evolucion_id=".$control['evolucion_id']." 
                       AND dietas.hc_dieta_id=dietas_d.hc_dieta_id;";
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado)
			{
				$this->error = "Error al consultar la tabla \"hc_control_dietas\" con evolucion_id=".$control['evolucion_id'];
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			while ($data = $resultado->FetchRow()) {
				$dietas_d[]=$data;
			}
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			return $dietas_d;
		}


		function GetCControlTransfusiones($control)
		{
			list($dbconn) = GetDBconn();
			$query="SELECT * FROM hc_transfusiones WHERE evolucion_id=".$control['evolucion_id'];
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado)
			{
				$this->error = "Error al consultar la tabla \"hc_transfusiones\" con evolucion_id=".$control['evolucion_id'];
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			return $resultado->FetchNextObject($toUpper=false);
		}
          
          
		function GetCControlesAdicionales($control)
          {
			list($dbconn) = GetDBconn();
			$query="SELECT * FROM hc_control_adicionales WHERE evolucion_id=".$control['evolucion_id'];
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado)
			{
				$this->error = "Error al consultar la tabla \"hc_control_adicionales\" con evolucion_id=".$control['evolucion_id'];
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			return $resultado->FetchNextObject($toUpper=false);
		}
          
		
          function GetCControlDrenajes($control)
          {
			list($dbconn) = GetDBconn();
			$query="SELECT * FROM hc_control_drenajes WHERE evolucion_id=".$control['evolucion_id'];
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado)
			{
				$this->error = "Error al consultar la tabla \"hc_control_drenajes\" con evolucion_id=".$control['evolucion_id'];
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			return $resultado->FetchNextObject($toUpper=false);
		}

          function GetControlTipoDrenajes()
          {
			GLOBAL $ADODB_FETCH_MODE;
               
               list($dbconn) = GetDBconn();
			$query="SELECT * FROM hc_tipo_control_drenaje;";
               
               $Drenajes=array();
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado)
			{
				$this->error = "Error al consultar la tabla \"hc_control_drenajes\" con evolucion_id=".$control['evolucion_id'];
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
               
               while ($data = $resultado->FetchRow()) {
				$Drenajes[]=$data;
			}
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			return $Drenajes;
		}
		
          function GetControlPosicion($posicion_id,$valor)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			switch ($valor)
			{
				case 0:
								$posicion=array();
								$query = "SELECT * FROM hc_tipos_posicion_paciente WHERE posicion_id='".$posicion_id."'";
								$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
								$resultado=$this->Verifica_Conexion($query,$dbconn);
									if (!$resultado) {
										$this->error = "Error, no se encuentra el registro en \"hc_tipos_posicion_paciente\" con la posicion \"$posicion_id\"";
										$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
										return false;
									}
								while ($data = $resultado->FetchRow()) {
									$posicion[]=$data;
								}
								$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
								return $posicion;
				break;
				case 1:
								$query = "SELECT * FROM hc_tipos_posicion_paciente";
								$resultado=$this->Verifica_Conexion($query,$dbconn);
									if (!$resultado) {
										$this->error = "Error, la tabla hc_tipos_posicion_paciente no contiene registros";
										$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
										return false;
									}
								return $this->GetControlPosicionOpt($resultado,$posicion_id);
				break;
			}
		}


		function GetControlOxiMetodo($posicion_id,$valor)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$option="";
			switch ($valor)
			{
				case 0:
								$metodo=array();
								$query = "SELECT * FROM hc_tipos_metodos_oxigenoterapia WHERE metodo_id='".$posicion_id."'";
								$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
								$resultado=$this->Verifica_Conexion($query,$dbconn);
									if (!$resultado) {
										$this->error = "Error, no se encuentra el registro en \"hc_tipos_metodos_oxigenoterapia\" con el metodo_id \"$posicion_id\"";
										$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
										return false;
									}
								while ($data = $resultado->FetchRow()) {
									$metodo[]=$data;
								}
								$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
								return $metodo;
				break;
				case 1:
								$query = "SELECT * FROM hc_tipos_metodos_oxigenoterapia";
								$resultado=$this->Verifica_Conexion($query,$dbconn);
									if (!$resultado) {
										$this->error = "Error, la tabla hc_tipos_metodos_oxigenoterapia no contiene registros";
										$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
										return false;
									}
								return $this->GetControlOxiMetodoOpt($resultado,$posicion_id);
				break;
			}
		}


		function GetControlOxiConcentraciones($posicion_id,$valor)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$option="";
			switch ($valor)
			{
				case 0:
								$conc=array();
								$query = "SELECT * FROM hc_tipos_concentracion_oxigenoterapia WHERE concentracion_id='".$posicion_id."'";
								$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
								$resultado=$this->Verifica_Conexion($query,$dbconn);
									if (!$resultado) {
										$this->error = "Error, no se encuentra el registro en \"hc_tipos_concentracion_oxigenoterapia\" con la concentracion_id \"$posicion_id\"";
										$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
										return false;
									}
								while ($data = $resultado->FetchRow()) {
									$conc[]=$data;
								}
								$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
								return $conc;
				break;
				case 1:
								$query = "SELECT * FROM hc_tipos_concentracion_oxigenoterapia ORDER BY concentracion_id";
								$resultado=$this->Verifica_Conexion($query,$dbconn);
									if (!$resultado) {
										$this->error = "Error, la tabla hc_tipos_concentracion_oxigenoterapia no contiene registros";
										$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
										return false;
									}
								return $this->GetControlOxiConcentracionesOpt($resultado,$posicion_id);
				break;
			}
		}


		function GetControlOxiFlujo($posicion_id,$valor)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$option="";
			switch ($valor)
			{
				case 0:
								$flujo=array();
								$query = "SELECT * FROM hc_tipos_flujos_oxigenoterapia WHERE flujo_id='".$posicion_id."'";
								$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
								$resultado=$this->Verifica_Conexion($query,$dbconn);
									if (!$resultado) {
										$this->error = "Error, no se encuentra el registro en \"hc_tipos_flujos_oxigenoterapia\" con el flujo_id \"$posicion_id\"";
										$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
										return false;
									}
								while ($data = $resultado->FetchRow()) {
									$flujo[]=$data;
								}
								$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
								return $flujo;
				break;
				case 1:
								$query = "SELECT * FROM hc_tipos_flujos_oxigenoterapia ORDER BY flujo_id";
								$resultado=$this->Verifica_Conexion($query,$dbconn);
									if (!$resultado) {
										$this->error = "Error, la tabla hc_tipos_flujos_oxigenoterapia no contiene registros";
										$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
										return false;
									}
								return $this->GetControlOxiFlujoOpt($resultado,$posicion_id);
				break;
			}
		}


		function GetControlLiquidos($evolucion_id)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$liquidos=array();
			$query = "SELECT * FROM hc_control_liquidos WHERE evolucion_id='".$evolucion_id."'";
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$this->Verifica_Conexion($query,$dbconn);
				if (!$resultado) {
					$this->error = "Error, no se encuentra el registro en \"hc_control_liquidos\" con la evolucion_id \"$evolucion_id\"";
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					return false;
				}
			while ($data = $resultado->FetchRow()) {
				$liquidos[]=$data;
			}
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			return $liquidos;
		}


		function GetControlPerAbdominal($evolucion_id)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$perAbd=array();
			$query = "SELECT * FROM hc_control_perimetro_abdominal WHERE evolucion_id='".$evolucion_id."'";
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$this->Verifica_Conexion($query,$dbconn);
				if (!$resultado) {
					$this->error = "Error, no se encuentra el registro en \"hc_control_perimetro_abdominal\" con la evolucion_id \"$evolucion_id\"";
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					return false;
				}
			while ($data = $resultado->FetchRow()) {
				$perAbd[]=$data;
			}
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			return $perAbd;
		}


		function GetControlPerCefalico($evolucion_id)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$perCefalico=array();
			$query = "SELECT * FROM hc_control_perimetro_cefalico WHERE evolucion_id='".$evolucion_id."'";
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$this->Verifica_Conexion($query,$dbconn);
				if (!$resultado) {
					$this->error = "Error, no se encuentra el registro en \"hc_control_perimetro_cefalico\" con la evolucion_id \"$evolucion_id\"";
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					return false;
				}
			while ($data = $resultado->FetchRow()) {
				$perCefalico[]=$data;
			}
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			return $perCefalico;
		}


		function GetControlReposo($posicion_id,$valor)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$option="";
			switch ($valor)
			{
				case 0:
								$reposo=array();
								$query = "SELECT * FROM hc_tipos_reposo_paciente WHERE tipo_reposo_id='".$posicion_id."'";
								$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
								$resultado=$this->Verifica_Conexion($query,$dbconn);
									if (!$resultado) {
										$this->error = "Error, no se encuentra el registro en \"hc_tipos_posicion_paciente\" con el tipo_reposo_id \"$posicion_id\"";
										$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
										return false;
									}
								while ($data = $resultado->FetchRow()) {
									$reposo[]=$data;
								}
								$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
								return $reposo;
				break;
				case 1:
								$query = "SELECT * FROM hc_tipos_reposo_paciente";
								$resultado=$this->Verifica_Conexion($query,$dbconn);
									if (!$resultado) {
										$this->error = "Error, la tabla hc_tipos_reposo_paciente no contiene registros";
										$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
										return false;
									}
								return $this->GetControlReposoOpt($resultado,$posicion_id);
				break;
				case 2:
								$reposo=array();
								$query = "SELECT * FROM hc_tipos_reposo_paciente";
								$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
								$resultado=$this->Verifica_Conexion($query,$dbconn);
									if (!$resultado) {
										$this->error = "Error, la tabla hc_tipos_reposo_paciente no contiene registros";
										$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
										return false;
									}
								while ($data = $resultado->FetchRow()) {
									$reposo[]=$data;
								}
								$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
								return $reposo;
				break;
			}
		}


		function GetControlPerExtremidades($posicion_id,$valor)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$option="";
			switch ($valor)
			{
				case 0:
								$extremidad=array();
								$query = "SELECT * FROM hc_tipos_extremidades_paciente WHERE tipo_extremidad_id='".$posicion_id."'";
								$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
								$resultado=$this->Verifica_Conexion($query,$dbconn);
									if (!$resultado) {
										$this->error = "Error, no se encuentra el registro en \"hc_tipos_extremidades_paciente\" con el tipo_extremidad_id \"$posicion_id\"";
										$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
										return false;
									}
								while ($data = $resultado->FetchRow()) {
									$extremidad[]=$data;
								}
								$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
								return $extremidad;
				break;
				case 1:
								$query = "SELECT * FROM hc_tipos_extremidades_paciente";
								$resultado=$this->Verifica_Conexion($query,$dbconn);
									if (!$resultado) {
										$this->error = "Error, la tabla hc_tipos_extremidades_paciente no contiene registros";
										$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
										return false;
									}
								return $this->GetControlPerExtremidadesOpt($resultado,$posicion_id);
				break;
				case 2:
								$extremidad=array();
								$query = "SELECT * FROM hc_tipos_extremidades_paciente";
								$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
								$resultado=$this->Verifica_Conexion($query,$dbconn);
									if (!$resultado) {
										$this->error = "Error, la tabla hc_tipos_extremidades_paciente no contiene registros";
										$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
										return false;
									}
								while ($data = $resultado->FetchRow()) {
									$extremidad[]=$data;
								}
								$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
								return $extremidad;
				break;
			}
		}


		function GetControlParto($evolucion_id)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$parto=array();
			$query = "SELECT * FROM hc_control_trabajo_parto WHERE evolucion_id='".$evolucion_id."'";
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$this->Verifica_Conexion($query,$dbconn);
				if (!$resultado) {
					$this->error = "Error, no se encuentra el registro en \"hc_control_trabajo_parto\" con la evolucion_id \"$evolucion_id\"";
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					return false;
				}
			while ($data = $resultado->FetchRow()) {
				$parto[]=$data;
			}
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			return $parto;
		}


		function GetControlDietas()
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$dieta=array();
			$query = "SELECT * FROM hc_tipos_dieta
               		WHERE abreviatura != 'NVO'";
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$this->Verifica_Conexion($query,$dbconn);
				if (!$resultado) {
					$this->error = "Error, la tabla hc_tipos_dieta no contiene registros";
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					return false;
				}
			while ($data = $resultado->FetchRow()) {
				$dieta[]=$data;
			}
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			return $dieta;
		}

		function GetNadaViaOral()
		{
			list($dbconn) = GetDBconn();
			$query = "SELECT hc_dieta_id FROM hc_tipos_dieta
               		WHERE abreviatura = 'NVO'";
			$resultado=$this->Verifica_Conexion($query,$dbconn);
               if (!$resultado) {
                    $this->error = "Error, la tabla hc_tipos_dieta no contiene registros";
                    $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
                    return false;
               }
               list($NVO)= $resultado->FetchRow();
			return $NVO;
		}

		function GetControlTransfusiones($evolucion_id)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$transfusiones=array();
			$query = "SELECT * FROM hc_transfusiones WHERE evolucion_id='".$evolucion_id."'";

			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$this->Verifica_Conexion($query,$dbconn);
				if (!$resultado) {
					$this->error = "Error, no se encuentra el registro en \"hc_transfusiones\" con la evolucion_id \"$evolucion_id\"";
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					return false;
				}
			while ($data = $resultado->FetchRow()) {
				$transfusiones[]=$data;
			}
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			return $transfusiones;
		}

		function GetControlesAdicionales($evolucion_id)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$transfusiones=array();
			$query = "SELECT * FROM hc_control_adicionales WHERE evolucion_id='".$evolucion_id."'";
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$this->Verifica_Conexion($query,$dbconn);
               if (!$resultado) {
                    $this->error = "Error, no se encuentra el registro en \"hc_control_adicionales\" con la evolucion_id \"$evolucion_id\"";
                    $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
                    return false;
               }
			while ($data = $resultado->FetchRow()) {
				$ctrlAdicionales[] = $data;
			}
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			return $ctrlAdicionales;
		}
          
          function GetControlDrenajes($evolucion_id)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$Drenajes=array();
			$query = "SELECT A.*, B.* 
               		FROM hc_control_drenajes AS A,
                         hc_tipo_control_drenaje AS B
               		WHERE evolucion_id='".$evolucion_id."'
                         AND A.tipo_drenaje = B.tipo_drenaje";

			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$this->Verifica_Conexion($query,$dbconn);
				if (!$resultado) {
					$this->error = "Error, no se encuentra el registro en \"hc_control_drenajes\" con la evolucion_id \"$evolucion_id\"";
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					return false;
				}
			while ($data = $resultado->FetchRow()) {
				$Drenajes[]=$data;
			}
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			return $Drenajes;
		}


/***********************************************************/
//Fin insert de los controles
/***********************************************************/

		/*
		* InsertDatos()
		* Valida e Inserta los datos en la base de datos
		*/
		function InsertDatos()
		{
			list($dbconn) = GetDBconn();
			$resultado=$dbconn->Execute($query);
				if (!$resultado)
				{
					$this->frmError[$this->frmPrefijo."MedicamentoID"]=1;
					if (!empty($this->error))
					{
						die(MsgOut($Modulo->error,$Modulo->mensajeDeError));
					}
					$this->frmError["MensajeError"]="El codigo del medicamento ya se encuentra en la formula medica";
					return false;
				}
				return true;
		}//End function


		function GetListFechas($hora,$control_id){
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$datos_fecha=array();
			switch ($control_id)
			{
				case 5:
								$query="SELECT substring(fecha from 1 for 10) as fechas
												FROM hc_signos_vitales
												WHERE ingreso = ".$this->ingreso." AND
												temp_piel > 0
												group by fechas
												ORDER BY fechas DESC";

								$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
								$resultado=$this->Verifica_Conexion($query,$dbconn);
								if (!$resultado) {
									$this->error = "Error al ejecutar la consulta<br>";
									$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
									return false;
								}
								$cont=0;
								$datos_fecha2=array();
								while ($data=$resultado->FetchRow()){
									$query2=" SELECT  a.*
														FROM
														(
															SELECT min(fecha) as fechas
															FROM hc_signos_vitales
															WHERE ingreso = ".$this->ingreso." AND
															substring(fecha from 1 for 10)='".$data['fechas']."'
														) as a ";

									$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
									$resultado2=$this->Verifica_Conexion($query2,$dbconn);
									if (!$resultado2) {
										$this->error = "Error al ejecutar la consulta<br>";
										$this->mensajeDeError = $query2."<br>".$dbconn->ErrorMsg();
										return false;
									}
									$data2=$resultado2->FetchRow();
									if (substr($data2['fechas'],11,2) >= $hora){
										if (!in_array(substr($data2['fechas'],0,10),$datos_fecha2)){
											$datos_fecha[$cont]['fechas']=substr($data2['fechas'],0,10);
											array_push($datos_fecha2,substr($data2['fechas'],0,10));
										}
									}
									else{
										list($fecha,$tiempo)=explode(" ",$data2['fechas']);
										list($Y,$m,$d)=explode("-",$fecha);
										if (!in_array(date("Y-m-d",mktime(0,0,0,$m,($d-1),$Y)),$datos_fecha2)){
											$datos_fecha[$cont]['fechas']=date("Y-m-d",mktime(0,0,0,$m,($d-1),$Y));
											array_push($datos_fecha2,date("Y-m-d",mktime(0,0,0,$m,($d-1),$Y)));
										}
									}
									$cont++;
								}
								$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
								return $datos_fecha;
				break;
				case 8:
								$query="SELECT substring(fecha from 1 for 10) as fechas
												FROM hc_control_diabetes
												WHERE ingreso = ".$this->ingreso." AND
												glucometria IS NOT NULL
												group by fechas
												ORDER BY fechas DESC";

								$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
								$resultado=$this->Verifica_Conexion($query,$dbconn);
								if (!$resultado) {
									$this->error = "Error al ejecutar la consulta<br>";
									$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
									return false;
								}
								$cont=0;
								$datos_fecha2=array();
								while ($data=$resultado->FetchRow()){
									$query2=" SELECT  a.*
														FROM
														(
															SELECT min(fecha) as fechas
															FROM hc_control_diabetes
															WHERE ingreso = ".$this->ingreso." AND
															substring(fecha from 1 for 10)='".$data['fechas']."'
														) as a ";

									$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
									$resultado2=$this->Verifica_Conexion($query2,$dbconn);
									if (!$resultado2) {
										$this->error = "Error al ejecutar la consulta<br>";
										$this->mensajeDeError = $query2."<br>".$dbconn->ErrorMsg();
										return false;
									}
									$data2=$resultado2->FetchRow();
									if (substr($data2['fechas'],11,2) >= $hora){
										if (!in_array(substr($data2['fechas'],0,10),$datos_fecha2)){
											$datos_fecha[$cont]['fechas']=substr($data2['fechas'],0,10);
											array_push($datos_fecha2,substr($data2['fechas'],0,10));
										}
									}
									else{
										list($fecha,$tiempo)=explode(" ",$data2['fechas']);
										list($Y,$m,$d)=explode("-",$fecha);
										if (!in_array(date("Y-m-d",mktime(0,0,0,$m,($d-1),$Y)),$datos_fecha2)){
											$datos_fecha[$cont]['fechas']=date("Y-m-d",mktime(0,0,0,$m,($d-1),$Y));
											array_push($datos_fecha2,date("Y-m-d",mktime(0,0,0,$m,($d-1),$Y)));
										}
									}
									$cont++;
								}
								$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
								return $datos_fecha;
				break;
				case 18:
								$query="SELECT substring(fecha from 1 for 10) as fechas
												FROM hc_signos_vitales
												WHERE ingreso = ".$this->ingreso." AND
												pvc > 0
												group by fechas
												ORDER BY fechas DESC";

								$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
								$resultado=$this->Verifica_Conexion($query,$dbconn);
								if (!$resultado) {
									$this->error = "Error al ejecutar la consulta<br>";
									$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
									return false;
								}
								$cont=0;
								$datos_fecha2=array();
								while ($data=$resultado->FetchRow()){
									$query2=" SELECT  a.*
														FROM
														(
															SELECT min(fecha) as fechas
															FROM hc_signos_vitales
															WHERE ingreso = ".$this->ingreso." AND
															substring(fecha from 1 for 10)='".$data['fechas']."'
														) as a ";

									$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
									$resultado2=$this->Verifica_Conexion($query2,$dbconn);
									if (!$resultado2) {
										$this->error = "Error al ejecutar la consulta<br>";
										$this->mensajeDeError = $query2."<br>".$dbconn->ErrorMsg();
										return false;
									}
									$data2=$resultado2->FetchRow();
									if (substr($data2['fechas'],11,2) >= $hora){
										if (!in_array(substr($data2['fechas'],0,10),$datos_fecha2)){
											$datos_fecha[$cont]['fechas']=substr($data2['fechas'],0,10);
											array_push($datos_fecha2,substr($data2['fechas'],0,10));
										}
									}
									else{
										list($fecha,$tiempo)=explode(" ",$data2['fechas']);
										list($Y,$m,$d)=explode("-",$fecha);
										if (!in_array(date("Y-m-d",mktime(0,0,0,$m,($d-1),$Y)),$datos_fecha2)){
											$datos_fecha[$cont]['fechas']=date("Y-m-d",mktime(0,0,0,$m,($d-1),$Y));
											array_push($datos_fecha2,date("Y-m-d",mktime(0,0,0,$m,($d-1),$Y)));
										}
									}
									$cont++;
								}
								$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
								return $datos_fecha;
				break;
				case 21:
								$query="SELECT substring(fecha from 1 for 10) as fechas
												FROM hc_signos_vitales
												WHERE ingreso = ".$this->ingreso." AND
												fc > 0
												group by fechas
												ORDER BY fechas DESC";
								$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
								$resultado=$this->Verifica_Conexion($query,$dbconn);
								if (!$resultado) {
									$this->error = "Error al ejecutar la consulta<br>";
									$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
									return false;
								}
								$cont=0;
								$datos_fecha2=array();
								while ($data=$resultado->FetchRow()){
									$query2=" SELECT  a.*
														FROM
														(
															SELECT min(fecha) as fechas
															FROM hc_signos_vitales
															WHERE ingreso = ".$this->ingreso." AND
															substring(fecha from 1 for 10)='".$data['fechas']."'
														) as a ";
									$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
									$resultado2=$this->Verifica_Conexion($query2,$dbconn);
									if (!$resultado2) {
										$this->error = "Error al ejecutar la consulta<br>";
										$this->mensajeDeError = $query2."<br>".$dbconn->ErrorMsg();
										return false;
									}
									$data2=$resultado2->FetchRow();
									if (substr($data2['fechas'],11,2) >= $hora){
										if (!in_array(substr($data2['fechas'],0,10),$datos_fecha2)){
											$datos_fecha[$cont]['fechas']=substr($data2['fechas'],0,10);
											array_push($datos_fecha2,substr($data2['fechas'],0,10));
										}
									}
									else{
										list($fecha,$tiempo)=explode(" ",$data2['fechas']);
										list($Y,$m,$d)=explode("-",$fecha);
										if (!in_array(date("Y-m-d",mktime(0,0,0,$m,($d-1),$Y)),$datos_fecha2)){
											$datos_fecha[$cont]['fechas']=date("Y-m-d",mktime(0,0,0,$m,($d-1),$Y));
											array_push($datos_fecha2,date("Y-m-d",mktime(0,0,0,$m,($d-1),$Y)));
										}
									}
									$cont++;
								}
								$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
								return $datos_fecha;
				break;
				case 22:
								$query="SELECT substring(fecha from 1 for 10) as fechas
												FROM hc_asistencia_ventilatoria
												WHERE ingreso = ".$this->ingreso." AND
												fr_respiratoria > 0
												group by fechas
												ORDER BY fechas DESC";
								$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
								$resultado=$this->Verifica_Conexion($query,$dbconn);
								if (!$resultado) {
									$this->error = "Error al ejecutar la consulta<br>";
									$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
									return false;
								}
								$cont=0;
								$datos_fecha2=array();
								while ($data=$resultado->FetchRow()){
									$query2=" SELECT  a.*
														FROM
														(
															SELECT min(fecha) as fechas
															FROM hc_asistencia_ventilatoria
															WHERE ingreso = ".$this->ingreso." AND
															substring(fecha from 1 for 10)='".$data['fechas']."'
														) as a ";
									$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
									$resultado2=$this->Verifica_Conexion($query2,$dbconn);
									if (!$resultado2) {
										$this->error = "Error al ejecutar la consulta<br>";
										$this->mensajeDeError = $query2."<br>".$dbconn->ErrorMsg();
										return false;
									}
									$data2=$resultado2->FetchRow();
									if (substr($data2['fechas'],11,2) >= $hora){
										if (!in_array(substr($data2['fechas'],0,10),$datos_fecha2)){
											$datos_fecha[$cont]['fechas']=substr($data2['fechas'],0,10);
											array_push($datos_fecha2,substr($data2['fechas'],0,10));
										}
									}
									else{
										list($fecha,$tiempo)=explode(" ",$data2['fechas']);
										list($Y,$m,$d)=explode("-",$fecha);
										if (!in_array(date("Y-m-d",mktime(0,0,0,$m,($d-1),$Y)),$datos_fecha2)){
											$datos_fecha[$cont]['fechas']=date("Y-m-d",mktime(0,0,0,$m,($d-1),$Y));
											array_push($datos_fecha2,date("Y-m-d",mktime(0,0,0,$m,($d-1),$Y)));
										}
									}
									$cont++;
								}
								$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
								return $datos_fecha;
				break;
			}
		}


		function GetFindControles($control_id){
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$controles=array();
			$query="SELECT *
							FROM hc_tipos_controles_paciente
							WHERE control_id='$control_id'";
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado) {
				$this->error = "Error al buscar el tipo de control en \"hc_tipos_controles_paciente\"<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			$controles = $resultado->FetchRow();
			if ($controles===false){
				$this->error = "Error al consultar la tabla";
				$this->mensajeDeError = "No se encuentran registros en \"hc_tipos_controles_paciente\".";
			}
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			return $controles;
		}

		
		function CallFrmResumenGlucometria()
		{
			if(!$this->FrmResumenGlucometria($_REQUEST['paciente'],$_REQUEST['estacion']))
				return false;
			return true;
		}



		function GetRangoControl($control_id,$datos_paciente){
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$controles=array();

			$query="SELECT *
								FROM  hc_rangos_controles
								WHERE control_id='$control_id' AND
											sexo = '".$datos_paciente["sexo"]."' AND
											".$datos_paciente["edad"]["anos"]." BETWEEN edad_min AND edad_max
							";
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado) {
				$this->error = "Error al buscar el tipo de control en \"hc_tipos_controles_paciente\"<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			$controles = $resultado->FetchRow();
			if (!empty($controles["control_id"])){
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				return $controles;
			}
			else{
				$query="SELECT *
								FROM  hc_rangos_tipos_controles
								WHERE control_id='$control_id'";
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$resultado=$this->Verifica_Conexion($query,$dbconn);
				if (!$resultado) {
					$this->error = "Error al buscar el tipo de control en \"hc_tipos_controles_paciente\"<br>";
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					return false;
				}
				$controles = $resultado->FetchRow();
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				return $controles;
			}
		}



		function GetAllFechas($hora_inicio_turno,$rango_turno,$control_id){
			GLOBAL $ADODB_FETCH_MODE;
			list($h,$m,$s)=explode(":",$hora_inicio_turno);
			list($dbconn) = GetDBconn();
			$datos_d=array();

			switch ($control_id)
			{
				case 5 :
								$query="SELECT substring(fecha from 1 for 10) as fechas
												FROM hc_signos_vitales
												WHERE ingreso = ".$this->ingreso." AND
												temp_piel > 0
												group by fechas
												order by fechas";
								$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
								$resultado=$this->Verifica_Conexion($query,$dbconn);
								if (!$resultado) {
									$this->error = "Error al ejecutar la consulta<br>";
									$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
									return false;
								}

								while ($data=$resultado->FetchRow()){
									$query2=" SELECT  a.*,
																		b.*,
																		c.*
														FROM
														(
															SELECT avg(temp_piel) as media
															FROM hc_signos_vitales
															WHERE ingreso = ".$this->ingreso." AND
															(
															fecha >= (timestamp '".$data['fechas']." $hora_inicio_turno') AND
															fecha <= (timestamp '".$data['fechas']." ".date("H:i:s",mktime($h,$m-1,$s,date("m"),date("d"),date("Y")))."' + interval '$rango_turno hours')
															) AND
															temp_piel > 0
														) as a,
														(
															select min(temp_piel) as vmin
															from hc_signos_vitales
															WHERE ingreso = ".$this->ingreso." AND
																		(
																		fecha >= (timestamp '".$data['fechas']." $hora_inicio_turno') AND
																		fecha <= (timestamp '".$data['fechas']." ".date("H:i:s",mktime($h,$m-1,$s,date("m"),date("d"),date("Y")))."' + interval '$rango_turno hours')
																		) AND
															temp_piel > 0
														) as b,
														(
															select max(temp_piel) as vmax
															from hc_signos_vitales
															WHERE ingreso = ".$this->ingreso." AND
																		(
																		fecha >= (timestamp '".$data['fechas']." $hora_inicio_turno') AND
																		fecha <= (timestamp '".$data['fechas']." ".date("H:i:s",mktime($h,$m-1,$s,date("m"),date("d"),date("Y")))."' + interval '$rango_turno hours')
																		) AND
															temp_piel > 0
														) as c ";
									$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
									$resultadoI=$this->Verifica_Conexion($query2,$dbconn);
									if (!$resultadoI) {
										$this->error = "Error al ejecutar la consulta<br>";
										$this->mensajeDeError = $query2."<br>".$dbconn->ErrorMsg();
										return false;
									}
									while ($data2=$resultadoI->FetchRow()){
										$datos_d[$data['fechas']]=$data2;
									}//End While
								}//End While
				break;
				case 8 :
								$query="SELECT substring(fecha from 1 for 10) as fechas
												FROM hc_control_diabetes
												WHERE ingreso = ".$this->ingreso." AND
												glucometria IS NOT NULL
												group by fechas
												order by fechas";
								$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
								$resultado=$this->Verifica_Conexion($query,$dbconn);
								if (!$resultado) {
									$this->error = "Error al ejecutar la consulta<br>";
									$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
									return false;
								}
								while ($data=$resultado->FetchRow()){
									$query2=" SELECT  a.*,
																		b.*,
																		c.*
														FROM
														(
															SELECT avg(glucometria) as media
															FROM hc_control_diabetes
															WHERE ingreso = ".$this->ingreso." AND
															(
															fecha >= (timestamp '".$data['fechas']." $hora_inicio_turno') AND
															fecha <= (timestamp '".$data['fechas']." ".date("H:i:s",mktime($h,$m-1,$s,date("m"),date("d"),date("Y")))."' + interval '$rango_turno hours')
															)
														) as a,
														(
															select min(glucometria) as vmin
															from hc_control_diabetes
															WHERE ingreso = ".$this->ingreso." AND
																		(
																		fecha >= (timestamp '".$data['fechas']." $hora_inicio_turno') AND
																		fecha <= (timestamp '".$data['fechas']." ".date("H:i:s",mktime($h,$m-1,$s,date("m"),date("d"),date("Y")))."' + interval '$rango_turno hours')
																		)
														) as b,
														(
															select max(glucometria) as vmax
															from hc_control_diabetes
															WHERE ingreso = ".$this->ingreso." AND
																		(
																		fecha >= (timestamp '".$data['fechas']." $hora_inicio_turno') AND
																		fecha <= (timestamp '".$data['fechas']." ".date("H:i:s",mktime($h,$m-1,$s,date("m"),date("d"),date("Y")))."' + interval '$rango_turno hours')
																		)
														) as c ";
									$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
									$resultadoI=$this->Verifica_Conexion($query2,$dbconn);
									if (!$resultadoI) {
										$this->error = "Error al ejecutar la consulta<br>";
										$this->mensajeDeError = $query2."<br>".$dbconn->ErrorMsg();
										return false;
									}
									while ($data2=$resultadoI->FetchRow()){
										$datos_d[$data['fechas']]=$data2;
									}//End While
								}//End While
				break;
				case 18 :
								$query="SELECT substring(fecha from 1 for 10) as fechas
												FROM hc_signos_vitales
												WHERE ingreso = ".$this->ingreso." AND
												pvc > 0
												group by fechas
												order by fechas";
								$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
								$resultado=$this->Verifica_Conexion($query,$dbconn);
								if (!$resultado) {
									$this->error = "Error al ejecutar la consulta<br>";
									$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
									return false;
								}

								while ($data=$resultado->FetchRow()){
									$query2=" SELECT  a.*,
																		b.*,
																		c.*
														FROM
														(
															SELECT avg(pvc) as media
															FROM hc_signos_vitales
															WHERE ingreso = ".$this->ingreso." AND
															(
															fecha >= (timestamp '".$data['fechas']." $hora_inicio_turno') AND
															fecha <= (timestamp '".$data['fechas']." ".date("H:i:s",mktime($h,$m-1,$s,date("m"),date("d"),date("Y")))."' + interval '$rango_turno hours')
															) AND
															pvc > 0
														) as a,
														(
															select min(pvc) as vmin
															from hc_signos_vitales
															WHERE ingreso = ".$this->ingreso." AND
																		(
																		fecha >= (timestamp '".$data['fechas']." $hora_inicio_turno') AND
																		fecha <= (timestamp '".$data['fechas']." ".date("H:i:s",mktime($h,$m-1,$s,date("m"),date("d"),date("Y")))."' + interval '$rango_turno hours')
																		) AND
															pvc > 0
														) as b,
														(
															select max(pvc) as vmax
															from hc_signos_vitales
															WHERE ingreso = ".$this->ingreso." AND
																		(
																		fecha >= (timestamp '".$data['fechas']." $hora_inicio_turno') AND
																		fecha <= (timestamp '".$data['fechas']." ".date("H:i:s",mktime($h,$m-1,$s,date("m"),date("d"),date("Y")))."' + interval '$rango_turno hours')
																		) AND
															pvc > 0
														) as c ";
									$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
									$resultadoI=$this->Verifica_Conexion($query2,$dbconn);
									if (!$resultadoI) {
										$this->error = "Error al ejecutar la consulta<br>";
										$this->mensajeDeError = $query2."<br>".$dbconn->ErrorMsg();
										return false;
									}
									while ($data2=$resultadoI->FetchRow()){
										$datos_d[$data['fechas']]=$data2;
									}//End While
								}//End While
				break;
				case 21 :
								$query="SELECT substring(fecha from 1 for 10) as fechas
												FROM hc_signos_vitales
												WHERE ingreso = ".$this->ingreso." AND
												fc > 0
												group by fechas
												order by fechas";

								$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
								$resultado=$this->Verifica_Conexion($query,$dbconn);
								if (!$resultado) {
									$this->error = "Error al ejecutar la consulta<br>";
									$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
									return false;
								}

								while ($data=$resultado->FetchRow()){
									$query2=" SELECT  a.*,
																		b.*,
																		c.*
														FROM
														(
															SELECT avg(fc) as media
															FROM hc_signos_vitales
															WHERE ingreso = ".$this->ingreso." AND
															(
															fecha >= (timestamp '".$data['fechas']." $hora_inicio_turno') AND
															fecha <= (timestamp '".$data['fechas']." ".date("H:i:s",mktime($h,$m-1,$s,date("m"),date("d"),date("Y")))."' + interval '$rango_turno hours')
															) AND
															fc > 0
														) as a,
														(
															select min(fc) as vmin
															from hc_signos_vitales
															WHERE ingreso = ".$this->ingreso." AND
																		(
																		fecha >= (timestamp '".$data['fechas']." $hora_inicio_turno') AND
																		fecha <= (timestamp '".$data['fechas']." ".date("H:i:s",mktime($h,$m-1,$s,date("m"),date("d"),date("Y")))."' + interval '$rango_turno hours')
																		) AND
															fc > 0
														) as b,
														(
															select max(fc) as vmax
															from hc_signos_vitales
															WHERE ingreso = ".$this->ingreso." AND
																		(
																		fecha >= (timestamp '".$data['fechas']." $hora_inicio_turno') AND
																		fecha <= (timestamp '".$data['fechas']." ".date("H:i:s",mktime($h,$m-1,$s,date("m"),date("d"),date("Y")))."' + interval '$rango_turno hours')
																		) AND
															fc > 0
														) as c ";

									$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
									$resultadoI=$this->Verifica_Conexion($query2,$dbconn);
									if (!$resultadoI) {
										$this->error = "Error al ejecutar la consulta<br>";
										$this->mensajeDeError = $query2."<br>".$dbconn->ErrorMsg();
										return false;
									}
									while ($data2=$resultadoI->FetchRow()){
										$datos_d[$data['fechas']]=$data2;
									}//End While
								}//End While
				break;
				case 22 :
								$query="SELECT substring(fecha from 1 for 10) as fechas
												FROM hc_asistencia_ventilatoria
												WHERE ingreso = ".$this->ingreso." AND
												fr_respiratoria > 0
												group by fechas
												order by fechas";

								$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
								$resultado=$this->Verifica_Conexion($query,$dbconn);
								if (!$resultado) {
									$this->error = "Error al ejecutar la consulta<br>";
									$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
									return false;
								}

								while ($data=$resultado->FetchRow()){
									$query2=" SELECT  a.*,
																		b.*,
																		c.*
														FROM
														(
															SELECT avg(fr_respiratoria) as media
															FROM hc_asistencia_ventilatoria
															WHERE ingreso = ".$this->ingreso." AND
															(
															fecha >= (timestamp '".$data['fechas']." $hora_inicio_turno') AND
															fecha <= (timestamp '".$data['fechas']." ".date("H:i:s",mktime($h,$m-1,$s,date("m"),date("d"),date("Y")))."' + interval '$rango_turno hours')
															) AND
															fr_respiratoria > 0
														) as a,
														(
															select min(fr_respiratoria) as vmin
															from hc_asistencia_ventilatoria
															WHERE ingreso = ".$this->ingreso." AND
																		(
																		fecha >= (timestamp '".$data['fechas']." $hora_inicio_turno') AND
																		fecha <= (timestamp '".$data['fechas']." ".date("H:i:s",mktime($h,$m-1,$s,date("m"),date("d"),date("Y")))."' + interval '$rango_turno hours')
																		) AND
															fr_respiratoria > 0
														) as b,
														(
															select max(fr_respiratoria) as vmax
															from hc_asistencia_ventilatoria
															WHERE ingreso = ".$this->ingreso." AND
																		(
																		fecha >= (timestamp '".$data['fechas']." $hora_inicio_turno') AND
																		fecha <= (timestamp '".$data['fechas']." ".date("H:i:s",mktime($h,$m-1,$s,date("m"),date("d"),date("Y")))."' + interval '$rango_turno hours')
																		) AND
															fr_respiratoria > 0
														) as c ";

									$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
									$resultadoI=$this->Verifica_Conexion($query2,$dbconn);
									if (!$resultadoI) {
										$this->error = "Error al ejecutar la consulta<br>";
										$this->mensajeDeError = $query2."<br>".$dbconn->ErrorMsg();
										return false;
									}
									while ($data2=$resultadoI->FetchRow()){
										$datos_d[$data['fechas']]=$data2;
									}//End While
								}//End While
				break;
			}
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			return $datos_d;
		}


		function GetFechas($fecha,$hora_inicio_turno,$rango_turno,$control_id){
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			list($h,$m,$s)=explode(":",$hora_inicio_turno);

			switch($control_id)
			{
				case 5:
								$query="SELECT temp_piel, fecha
												FROM hc_signos_vitales
												WHERE ingreso = ".$this->ingreso." AND
															(
																fecha >= (timestamp '".$fecha." ".$hora_inicio_turno."') AND
																fecha <= (timestamp '".$fecha." ".date("H:i:s",mktime($h,$m-1,$s,date("m"),date("d"),date("Y")))."' + interval '$rango_turno hours')
															) AND
															temp_piel > 0
												GROUP BY fecha, temp_piel
												ORDER BY fecha ASC";
				break;
				case 8:
								$query="SELECT extract(hour from fecha) as horas, glucometria, fecha
												FROM hc_control_diabetes
												WHERE ingreso = ".$this->ingreso." AND
															(
																fecha >= (timestamp '".$fecha." ".$hora_inicio_turno."') AND
																fecha <= (timestamp '".$fecha." ".date("H:i:s",mktime($h,$m-1,$s,date("m"),date("d"),date("Y")))."' + interval '$rango_turno hours')
															) AND
															glucometria IS NOT NULL
												GROUP BY horas, fecha, glucometria
												ORDER BY fecha,horas ASC";
				break;
				case 18:
								$query="SELECT pvc, fecha
												FROM hc_signos_vitales
												WHERE ingreso = ".$this->ingreso." AND
															(
																fecha >= (timestamp '".$fecha." ".$hora_inicio_turno."') AND
																fecha <= (timestamp '".$fecha." ".date("H:i:s",mktime($h,$m-1,$s,date("m"),date("d"),date("Y")))."' + interval '$rango_turno hours')
															) AND
															pvc > 0
												GROUP BY fecha, pvc
												ORDER BY fecha ASC";
				break;
				case 21:
								$query="SELECT fc, fecha
												FROM hc_signos_vitales
												WHERE ingreso = ".$this->ingreso." AND
															(
																fecha >= (timestamp '".$fecha." ".$hora_inicio_turno."') AND
																fecha <= (timestamp '".$fecha." ".date("H:i:s",mktime($h,$m-1,$s,date("m"),date("d"),date("Y")))."' + interval '$rango_turno hours')
															) AND
															fc > 0
												GROUP BY fecha, fc
												ORDER BY fecha ASC";
				break;
				case 22:
								$query="SELECT fr_respiratoria, fecha
												FROM hc_asistencia_ventilatoria
												WHERE ingreso = ".$this->ingreso." AND
															(
																fecha >= (timestamp '".$fecha." ".$hora_inicio_turno."') AND
																fecha <= (timestamp '".$fecha." ".date("H:i:s",mktime($h,$m-1,$s,date("m"),date("d"),date("Y")))."' + interval '$rango_turno hours')
															) AND
															fr_respiratoria > 0
												GROUP BY fecha, fr_respiratoria
												ORDER BY fecha ASC";
				break;
			}

			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$this->Verifica_Conexion($query,$dbconn);
			if (!$resultado) {
				$this->error = "Error al ejecutar la consulta<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
			while ($data=$resultado->FetchRow()){
				$datos[]=$data;
			}
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			return $datos;
		}


          /**
          *	Obtiene los registros del control de glucometira del ingreso X
          *
          *	@Author Rosa Maria Angel
          *	@access Public
          *	@return bool-array-string
          */
          function GetResumenGlucometria()
          {
               $query = "SELECT CDG.ingreso,
                              CDG.fecha,
                              CDG.glucometria,
                              CDG.valor_cristalina,
                              CDG.via_cristalina,
                              TVIA.descripcion as viacristalina,
                              CDG.valor_nph,
                              CDG.via_nph,
                              TVIB.descripcion as vianph
                         FROM hc_control_diabetes CDG
                         LEFT JOIN hc_tipos_vias_insulina TVIA ON (TVIA.tipo_via_insulina_id = CDG.via_cristalina)
                         LEFT JOIN hc_tipos_vias_insulina TVIB ON (TVIB.tipo_via_insulina_id = CDG.via_nph)
                         WHERE CDG.ingreso = ".$this->ingreso."
                         ORDER BY CDG.fecha DESC;";
               GLOBAL $ADODB_FETCH_MODE;
               list($dbconn) = GetDBconn();
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
               $result = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Ocurrió un error al intentar obtener los controles de diabetes del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    return false;
               }
               else
               {
                    if($result->EOF){
                         return "ShowMensaje";
                    }
                    else
                    {
                         while ($data = $result->FetchRow())
                         {
                              $controles[$data[fecha]][] = $data;
                         }
                         return $controles;
                    }
               }
          }//Fin GetResumenGlucometria

          
		/*function GetResumenGlucometria($ingreso,$hora_inicio_turno,$rango_turno,$fecha)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			list($h,$m,$s)=explode(":",$hora_inicio_turno);
               
			if ($fecha=="todos"){
				$query="SELECT substring(fecha from 1 for 10) as fechas
								FROM hc_control_diabetes
								WHERE ingreso = ".$ingreso." AND
								glucometria IS NOT NULL
								group by fechas
								order by fechas desc";
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$resultado=$this->Verifica_Conexion($query,$dbconn);
				if (!$resultado) {
					$this->error = "Error al ejecutar la consulta<br>";
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					return false;
				}
				while ($data=$resultado->FetchRow()){
					$query2="
											SELECT 	CDG.ingreso,
															CDG.fecha,
															CDG.glucometria,
															CDG.valor_cristalina,
															CDG.via_cristalina,
															TVIA.descripcion as viacristalina,
															CDG.valor_nph,
															CDG.via_nph,
															TVIB.descripcion as vianph
											FROM hc_control_diabetes CDG
											LEFT JOIN hc_tipos_vias_insulina TVIA ON (TVIA.tipo_via_insulina_id = CDG.via_cristalina)
											LEFT JOIN hc_tipos_vias_insulina TVIB ON (TVIB.tipo_via_insulina_id = CDG.via_nph)
											WHERE CDG.ingreso = $ingreso AND
											substring(CDG.fecha from 1 for 10)= '".$data['fechas']."'
											ORDER BY CDG.fecha DESC ";

					$query3="
											SELECT avg(glucometria) as media
											FROM hc_control_diabetes
											WHERE ingreso = ".$this->ingreso." AND
											substring(fecha from 1 for 10)= '".$data['fechas']."' AND
											glucometria is not null
					";

					$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
					$resultadoI=$this->Verifica_Conexion($query2,$dbconn);
					if (!$resultadoI) {
						$this->error = "Error al ejecutar la consulta<br>";
						$this->mensajeDeError = $query2."<br>".$dbconn->ErrorMsg();
						return false;
					}

					$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
					$resultadoII=$this->Verifica_Conexion($query3,$dbconn);
					if (!$resultadoII) {
						$this->error = "Error al ejecutar la consulta<br>";
						$this->mensajeDeError = $query3."<br>".$dbconn->ErrorMsg();
						return false;
					}

					while ($data2=$resultadoI->FetchRow()){
						$data2['media']=$resultadoII->fields['media'];
						$datos_d[$data['fechas']][]=$data2;
					}//End While
				}
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				return $datos_d;
			}
			else
			{
				$query = "
									SELECT 	CDG.ingreso,
													CDG.fecha,
													CDG.glucometria,
													CDG.valor_cristalina,
													CDG.via_cristalina,
													TVIA.descripcion as viacristalina,
													CDG.valor_nph,
													CDG.via_nph,
													TVIB.descripcion as vianph
									FROM hc_control_diabetes CDG
									LEFT JOIN hc_tipos_vias_insulina TVIA ON (TVIA.tipo_via_insulina_id = CDG.via_cristalina)
									LEFT JOIN hc_tipos_vias_insulina TVIB ON (TVIB.tipo_via_insulina_id = CDG.via_nph)
									WHERE CDG.ingreso = $ingreso AND
												(
													CDG.fecha >= (timestamp '".$fecha." ".$hora_inicio_turno."') AND
													CDG.fecha <= (timestamp '".$fecha." ".date("H:i:s",mktime($h,$m-1,$s,date("m"),date("d"),date("Y")))."' + interval '$rango_turno hours')
												) AND
												CDG.glucometria IS NOT NULL
									ORDER BY CDG.fecha DESC;";

				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al ejecutar la conexion";
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					return false;
				}
				else{
					if($result->EOF){
						return "ShowMensaje";
					}
					else{
						while ($data = $result->FetchRow()){
							$controles[$data['fecha']][] = $data;
						}
						$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
						return $controles;
					}
				}//Fin else
			}//Fin else
		}//Fin GetResumenGlucometria*/


		function GetResumenCurvaTermica($ingreso,$hora_inicio_turno,$rango_turno,$fecha)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			list($h,$m,$s)=explode(":",$hora_inicio_turno);

			if ($fecha=="todos"){
				$query="SELECT substring(fecha from 1 for 10) as fechas
								FROM hc_signos_vitales
								WHERE ingreso = ".$ingreso." AND
								extract(hour from fecha) > $h AND
								temp_piel > 0
								group by fechas
								order by fechas desc";

				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$resultado=$this->Verifica_Conexion($query,$dbconn);
				if (!$resultado) {
					$this->error = "Error al ejecutar la consulta<br>";
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					return false;
				}
				while ($data=$resultado->FetchRow()){
					$cont=0;
					$query2="SELECT	a.*
									FROM
									(
											SELECT avg(temp_piel) as media
											FROM hc_signos_vitales
											WHERE ingreso = ".$this->ingreso." AND
											(
											fecha >= (timestamp '".$data['fechas']." $hora_inicio_turno') AND
											fecha <= (timestamp '".$data['fechas']." ".date("H:i:s",mktime($h,$m-1,$s,date("m"),date("d"),date("Y")))."' + interval '$rango_turno hours')
											) AND
											temp_piel > 0
									) as a
									";

					$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
					$resultadoI=$this->Verifica_Conexion($query2,$dbconn);
					if (!$resultadoI) {
						$this->error = "Error al ejecutar la consulta<br>";
						$this->mensajeDeError = $query2."<br>".$dbconn->ErrorMsg();
						return false;
					}
					while ($data2=$resultadoI->FetchRow()){
						$datos_d[$data['fechas']][$cont]=$data2;
						$cont++;
					}//End While
				}
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				return $datos_d;
			}
			else
			{
				$query = "SELECT ingreso, fecha, temp_piel
									FROM
										hc_signos_vitales
									WHERE ingreso = $ingreso AND
												(
													fecha >= (timestamp '".$fecha." ".$hora_inicio_turno."') AND
													fecha <= (timestamp '".$fecha." ".date("H:i:s",mktime($h,$m-1,$s,date("m"),date("d"),date("Y")))."' + interval '$rango_turno hours')
												) AND
												temp_piel > 0
									ORDER BY fecha DESC;";

				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al ejecutar la conexion";
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					return false;
				}
				else{
					if($result->EOF){
						return "ShowMensaje";
					}
					else{
						while ($data = $result->FetchRow()){
							$controles[$data['fecha']][] = $data;
						}
						$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
						return $controles;
					}
				}//Fin else
			}//Fin else
		}//Fin GetResumenGlucometria


		function GetResumenFrecuenciaCardiaca($ingreso,$hora_inicio_turno,$rango_turno,$fecha)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			list($h,$m,$s)=explode(":",$hora_inicio_turno);

			if ($fecha=="todos"){
				$query="SELECT substring(fecha from 1 for 10) as fechas
								FROM hc_signos_vitales
								WHERE ingreso = ".$ingreso." AND
								extract(hour from fecha) > $h AND
								fc > 0
								group by fechas
								order by fechas desc";

				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$resultado=$this->Verifica_Conexion($query,$dbconn);
				if (!$resultado) {
					$this->error = "Error al ejecutar la consulta<br>";
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					return false;
				}
				while ($data=$resultado->FetchRow()){
					$cont=0;
					$query2="SELECT	a.*
									FROM
									(
											SELECT avg(fc) as media
											FROM hc_signos_vitales
											WHERE ingreso = ".$this->ingreso." AND
											(
											fecha >= (timestamp '".$data['fechas']." $hora_inicio_turno') AND
											fecha <= (timestamp '".$data['fechas']." ".date("H:i:s",mktime($h,$m-1,$s,date("m"),date("d"),date("Y")))."' + interval '$rango_turno hours')
											) AND
											fc > 0
									) as a
									";

					$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
					$resultadoI=$this->Verifica_Conexion($query2,$dbconn);
					if (!$resultadoI) {
						$this->error = "Error al ejecutar la consulta<br>";
						$this->mensajeDeError = $query2."<br>".$dbconn->ErrorMsg();
						return false;
					}
					while ($data2=$resultadoI->FetchRow()){
						$datos_d[$data['fechas']][$cont]=$data2;
						$cont++;
					}//End While
				}
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				return $datos_d;
			}
			else
			{
				$query = "SELECT ingreso, fecha, fc
									FROM
										hc_signos_vitales
									WHERE ingreso = $ingreso AND
												(
													fecha >= (timestamp '".$fecha." ".$hora_inicio_turno."') AND
													fecha <= (timestamp '".$fecha." ".date("H:i:s",mktime($h,$m-1,$s,date("m"),date("d"),date("Y")))."' + interval '$rango_turno hours')
												) AND
												fc > 0
									ORDER BY fecha DESC;";

				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al ejecutar la conexion";
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					return false;
				}
				else{
					if($result->EOF){
						return "ShowMensaje";
					}
					else{
						while ($data = $result->FetchRow()){
							$controles[$data['fecha']][] = $data;
						}
						$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
						return $controles;
					}
				}//Fin else
			}//Fin else
		}//Fin GetResumenGlucometria



		/**
		*		GetTransfusiones
		*
		*		Obtiene los registros de transfusiones
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool - array - string
		*		@param integer => numero de ingreso del paciente
		*/
		function GetTransfusiones($ingreso)
		{
			$query = "SELECT *
					FROM hc_control_transfusiones where ingreso=$ingreso
					ORDER BY fecha DESC";

			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();

			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Error al intentar obtener los registros de transfusiones del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				if($result->EOF){
					return "ShowMensaje";
				}
				else
				{
					while ($data = $resultado->FetchRow()) {
						$transfusionesPaciente[] = $data;
					}
					return $transfusionesPaciente;
				}
			}
		}//GetTransfusiones


		/**
		*		GetLiquidosAdministrados
		*
		*		Muestra los liquidos administrados en la fecha dada al ingreso x
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool ó array
		*		@param integer => numero de ingreso del paciente
		*		@param timestamp => fecha inicial del rango
		*		@param timestamp => fecha final del rango
		*/
		function GetLiquidosAdministrados($ingreso,$fechaReciente,$fechaProxima)
		{
			$query = "SELECT A.*,
											B.descripcion
								FROM (
											SELECT extract(hour from fecha) as horas,
														sum(cantidad) as sumas,
														tipo_liquido_administrado_id,
														substring(fecha from 1 for 10) as fechas
											FROM hc_control_liquidos_administrados
											WHERE ingreso = $ingreso AND
														(fecha between '$fechaReciente' AND '$fechaProxima')
											GROUP BY horas, tipo_liquido_administrado_id, fechas
											) as A,
											hc_tipo_liquidos_administrados B
								WHERE		A.tipo_liquido_administrado_id=B.tipo_liquido_administrado_id
								ORDER BY A.fechas, A.horas";

			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurrió un error al intentar hacer balance de liquidos administrados.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				while ($data = $result->FetchRow()){
					$vLiquido[$data[horas]][] = $data;
				}
				return $vLiquido;
			}
		}//fin GetLiquidosAdministrados



/*
		*		GetPesoPaciente
		*
		*		Selecciona el peso mas reciente del paciente
		*
		*		@Author Arley Velásquez
		*		@param integer => numero de ingreso del paciente
		*/
		function GetPesoPaciente($ingreso)
		{
			$peso=0;
			$query="SELECT peso, max(fecha) as fecha
							FROM hc_signos_vitales
							WHERE ingreso=$ingreso AND
										peso !=0
							GROUP BY peso ";
			list($dbconn) = GetDBconn();
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurrió un error al intentar obtener el peso del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			if ($result->RecordCount())
			{
				$peso=$result->FetchObject(false);
				return $peso->peso;
			}
			else	return -1;
		}

			/**
		*		GetLiquidosEliminados
		*
		*		Muestra los liquidos eliminados en el rango de fecha dada al ingreso x
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool ó array
		*		@param integer => numero de ingreso del paciente
		*		@param timestamp => fecha inicial del rango
		*		@param timestamp => fecha final del rango
		*/
		function GetLiquidosEliminados($ingreso, $fechaReciente, $fechaProxima)
		{
			$query = "SELECT A.*,
											B.descripcion
								FROM (
											SELECT extract(hour from fecha) as horas,
														sum(cantidad) as sumas,
														tipo_liquido_eliminado_id,
														substring(fecha from 1 for 10) as fechas,
														deposicion
											FROM hc_control_liquidos_eliminados
											WHERE ingreso = $ingreso AND
														(fecha between '$fechaReciente' AND '$fechaProxima')
											GROUP BY horas,
														tipo_liquido_eliminado_id,
														fechas,
														deposicion
											) as A,
											hc_tipo_liquidos_eliminados B
								WHERE		A.tipo_liquido_eliminado_id = B.tipo_liquido_eliminado_id
								ORDER BY A.fechas, A.horas ";

			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurrió un error al intentar hacer balance de liquidos administrados.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				while ($data = $result->FetchRow()){
					$vLiquido[$data[horas]][] = $data;
				}
				return $vLiquido;
			}
		}//fin GetLiquidosAdministrados



			/**
		*		GetDatosUsuarioSistema
		*
		*		Obtiene el nombre de usuario del sistema
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*		@param integer => usuario_id
		*/
		function GetDatosUsuarioSistema($usuario)
		{
			$query = "SELECT usuario,
					nombre
								FROM system_usuarios
								WHERE usuario_id = $usuario";

			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurrió un error al intentar obtener los datos del usuario.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				if($result->EOF){
					return "ShowMensaje";
				}
				else
				{
					while ($data = $result->FetchRow()){
						$DatosUser[] = $data;
					}
					return $DatosUser;
				}
			}
		}/// GetDatosUsuarioSistema


	/**
	*	Listar_ControlesNeurologicos
	*	@Author Tizziano Perea O.
	*	@access Public
	*	@return bool-array-string
	*	@param array
	*/
	function Listar_ControlesNeurologicos()
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT A.*, B.descripcion
				 FROM hc_controles_neurologia
				 AS A left join hc_tipos_nivel_consciencia AS B
				 on (B.nivel_consciencia_id=A.tipo_nivel_consciencia_id)
				 WHERE ingreso=".$this->ingreso."
				 ORDER BY fecha_registro DESC ";

		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while(!$resulta->EOF)
		{
			$VectorControl[$i]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}
		return $VectorControl;
	}


	/**
	*		Listar_ControlesNeurologicos
	*
	*		Lista los resultados de las inserciones
	*
	*		@Author Tizziano Perea O.
	*		@access Public
	*		@return bool-array-string
	*		@param array,
	*		@param array
	*/
/*
	function Listar_ControlesNeurologicos($ingreso)
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		if(empty($_REQUEST['conteo'.$pfj]))
		{
			 $query = "SELECT count(*)
			 		   FROM hc_controles_neurologia
					   WHERE ingreso='".$ingreso."';";

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

		$query = "SELECT A.*, B.descripcion
				 FROM hc_controles_neurologia
				 AS A left join hc_tipos_nivel_consciencia AS B
				 on (B.nivel_consciencia_id=A.tipo_nivel_consciencia_id)
				 WHERE ingreso='".$ingreso."'
				 ORDER BY fecha_registro
				 DESC ";
				 //.$this->limit." OFFSET $Of;";

		$resulta = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while(!$resulta->EOF)
		{
			$VectorControl[$i]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}
		return $VectorControl;
	}
*/




	/**
		*		GetResumenHojaNeurologica
		*
		*		Obtiene los registros del control de neurologia de un ingreso X
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool-array-string
		*		@return bool-array-string
		*		@param integer => ingreso paciente
		*		@param integer => limit del query
		*		@param integer => ingreso offset del query
		*/
		function GetResumenHojaNeurologica($ingreso,$limit,$offset)
		{
			$query = "SELECT  HN.fecha,
												HN.tipo_apertura_ocular_id,
												HN.tipo_respuesta_verbal_id,
												HN.tipo_respuesta_motora_id,
												(SELECT descripcion FROM hc_tipos_talla_pupila WHERE talla_pupila_id=HN.pupila_talla_d) as tallaPupilaDer,
												(SELECT descripcion FROM hc_tipos_talla_pupila WHERE talla_pupila_id=HN.pupila_talla_i) as tallaPupilaIzq,
												(SELECT descripcion FROM hc_tipos_reaccion_pupila WHERE reaccion_pupila_id=HN.pupila_reaccion_d) as ReaccionPupilaDer,
												(SELECT descripcion FROM hc_tipos_reaccion_pupila WHERE reaccion_pupila_id=HN.pupila_reaccion_i) as ReaccionPupilaIzq,
												(SELECT descripcion FROM hc_tipos_nivel_consciencia WHERE nivel_consciencia_id=HN.tipo_nivel_consciencia_id) as NivelConciencia,
												(SELECT descripcion FROM hc_tipos_fuerza WHERE fuerza_id=HN.fuerza_brazo_d) as FuerzaBrazoDer,
												(SELECT descripcion FROM hc_tipos_fuerza WHERE fuerza_id=HN.fuerza_brazo_i) as FuerzaBrazoIzq,
												(SELECT descripcion FROM hc_tipos_fuerza WHERE fuerza_id=HN.fuerza_pierna_d) as FuerzaPiernaDer,
												(SELECT descripcion FROM hc_tipos_fuerza WHERE fuerza_id=HN.fuerza_pierna_i) as FuerzaPiernaIzq,
												(SELECT descripcion FROM hc_tipos_apertura_ocular WHERE apertura_ocular_id=HN.tipo_apertura_ocular_id) as AperturaOcular,
												(SELECT descripcion FROM hc_tipos_respuesta_verbal WHERE respuesta_verbal_id=HN.tipo_respuesta_verbal_id) as RespuestaVerbal,
												(SELECT descripcion FROM hc_tipos_respuesta_motora WHERE respuesta_motora_id=HN.tipo_respuesta_motora_id) as RespuestaMotora
								FROM hc_hoja_neurologica HN
								WHERE HN.ingreso = $ingreso
								ORDER BY HN.fecha DESC
								LIMIT $limit OFFSET $offset;";

			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurrió un error al intentar obtener los controles del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				if($result->EOF){
					return "ShowMensaje";
				}
				else
				{
					while ($data = $result->FetchRow()){
						$controles[$data[fecha]][] = $data;
					}
					return $controles;
				}
			}
		}//Fin GetResumenHojaNeurologica




		function GetResumenFrecuenciaRespiratoria($ingreso,$hora_inicio_turno,$rango_turno,$fecha)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			list($h,$m,$s)=explode(":",$hora_inicio_turno);

			if ($fecha=="todos"){
				$query="SELECT substring(fecha from 1 for 10) as fechas
								FROM hc_asistencia_ventilatoria
								WHERE ingreso = ".$ingreso." AND
								extract(hour from fecha) > $h AND
								fr_respiratoria > 0
								group by fechas
								order by fechas desc";

				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$resultado=$this->Verifica_Conexion($query,$dbconn);
				if (!$resultado) {
					$this->error = "Error al ejecutar la consulta<br>";
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					return false;
				}
				while ($data=$resultado->FetchRow()){
					$cont=0;
					$query2="SELECT	a.*
									FROM
									(
											SELECT avg(fr_respiratoria) as media
											FROM hc_asistencia_ventilatoria
											WHERE ingreso = ".$this->ingreso." AND
											(
											fecha >= (timestamp '".$data['fechas']." $hora_inicio_turno') AND
											fecha <= (timestamp '".$data['fechas']." ".date("H:i:s",mktime($h,$m-1,$s,date("m"),date("d"),date("Y")))."' + interval '$rango_turno hours')
											) AND
											fr_respiratoria > 0
									) as a
									";

					$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
					$resultadoI=$this->Verifica_Conexion($query2,$dbconn);
					if (!$resultadoI) {
						$this->error = "Error al ejecutar la consulta<br>";
						$this->mensajeDeError = $query2."<br>".$dbconn->ErrorMsg();
						return false;
					}
					while ($data2=$resultadoI->FetchRow()){
						$datos_d[$data['fechas']][$cont]=$data2;
						$cont++;
					}//End While
				}

				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				return $datos_d;
			}
			else
			{
				$query = "SELECT ingreso, fecha, fr_respiratoria
									FROM
										hc_asistencia_ventilatoria
									WHERE ingreso = $ingreso AND
												(
													fecha >= (timestamp '".$fecha." ".$hora_inicio_turno."') AND
													fecha <= (timestamp '".$fecha." ".date("H:i:s",mktime($h,$m-1,$s,date("m"),date("d"),date("Y")))."' + interval '$rango_turno hours')
												) AND
												fr_respiratoria > 0
									ORDER BY fecha DESC;";

				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al ejecutar la conexion";
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					return false;
				}
				else{
					if($result->EOF){
						return "ShowMensaje";
					}
					else{
						while ($data = $result->FetchRow()){
							$controles[$data['fecha']][] = $data;
						}
						$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
						return $controles;
					}
				}//Fin else
			}//Fin else
		}//Fin GetResumenGlucometria


		function GetResumenPresionVenosaCentral($ingreso,$hora_inicio_turno,$rango_turno,$fecha)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			list($h,$m,$s)=explode(":",$hora_inicio_turno);

			if ($fecha=="todos"){
				$query="SELECT substring(fecha from 1 for 10) as fechas
								FROM hc_signos_vitales
								WHERE ingreso = ".$ingreso." AND
								extract(hour from fecha) > $h AND
								pvc > 0
								group by fechas
								order by fechas desc";
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$resultado=$this->Verifica_Conexion($query,$dbconn);
				if (!$resultado) {
					$this->error = "Error al ejecutar la consulta<br>";
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					return false;
				}
				while ($data=$resultado->FetchRow()){
					$cont=0;
					$query2="SELECT	a.*
									FROM
									(
											SELECT avg(pvc) as media
											FROM hc_signos_vitales
											WHERE ingreso = ".$this->ingreso." AND
											(
											fecha >= (timestamp '".$data['fechas']." $hora_inicio_turno') AND
											fecha <= (timestamp '".$data['fechas']." ".date("H:i:s",mktime($h,$m-1,$s,date("m"),date("d"),date("Y")))."' + interval '$rango_turno hours')
											) AND
											pvc > 0
									) as a
									";

					$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
					$resultadoI=$this->Verifica_Conexion($query2,$dbconn);
					if (!$resultadoI) {
						$this->error = "Error al ejecutar la consulta<br>";
						$this->mensajeDeError = $query2."<br>".$dbconn->ErrorMsg();
						return false;
					}
					while ($data2=$resultadoI->FetchRow()){
						$datos_d[$data['fechas']][$cont]=$data2;
						$cont++;
					}//End While
				}

				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				return $datos_d;
			}
			else
			{
				$query = "SELECT ingreso, fecha, pvc
									FROM
										hc_signos_vitales
									WHERE ingreso = $ingreso AND
												(
													fecha >= (timestamp '".$fecha." ".$hora_inicio_turno."') AND
													fecha <= (timestamp '".$fecha." ".date("H:i:s",mktime($h,$m-1,$s,date("m"),date("d"),date("Y")))."' + interval '$rango_turno hours')
												) AND
												pvc > 0
									ORDER BY fecha DESC;";

				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al ejecutar la conexion";
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					return false;
				}
				else{
					if($result->EOF){
						return "ShowMensaje";
					}
					else{
						while ($data = $result->FetchRow()){
							$controles[$data['fecha']][] = $data;
						}
						$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
						return $controles;
					}
				}//Fin else
			}//Fin else
		}//Fin GetResumenGlucometria

          function GetDietas_Caracteristicas($valor,$sw)
          {
			GLOBAL $ADODB_FETCH_MODE;
          	list($dbconn) = GetDBconn();
               if($sw==1)
               {
                    $query = "SELECT A.hc_dieta_id, A.caracteristica_id, B.descripcion
                              FROM hc_tipos_dieta_caracteristicas AS A, hc_solicitudes_dietas_caracteristicas AS B
                              WHERE A.caracteristica_id=B.caracteristica_id
                              AND B.sw_generica='0'
                              AND B.sw_activo='1'
                              AND A.hc_dieta_id=".$valor."
                              ORDER BY B.indice_orden ASC;";
               }elseif($sw==2)
               {
                    $query = "SELECT *
                              FROM hc_solicitudes_dietas_caracteristicas
                              WHERE sw_generica='1'
                              AND sw_activo='1'
                              ORDER BY codigo_agrupamiento ASC, indice_orden ASC;";
               }
               
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
               $resultado = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error en la consulta en hc_solicitudes_suministros_estacion_detalle";
                    $this->mensajeDeError = "Ocurrió un error al insertar la solicitud de insumos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    return false;
               }
               while ($datos = $resultado->FetchRow())
               {
                    $caracteristicas[] = $datos;
               }
               return $caracteristicas;
               
          }
          
     
          /*INSERCION DE LA DIETA ASIGNADA AL PACIENTE*/
          function InsertCtrlDietas($tipo_dieta,$fraccionada,$observaciones,$caracteristicas,$ayuno,$horafin,$observacion_Ayuno,$hora_inicio,$nada_via_oral)
          {
               list($dbconn) = GetDBconn();
               $dbconn->BeginTrans();

               $controles=$this->GetControles();
               $ctrlDietas=$this->FindControles($controles,25,$this->ingreso);

               if($nada_via_oral != '')
               {
                   $tipo_dieta = $nada_via_oral;
               }
     
               if (empty($ctrlDietas['ingreso'])) {
	               
                    $query="INSERT INTO hc_controles_paciente(ingreso,control_id,evolucion_id)
                              VALUES (".$this->ingreso.",'25',".$this->evolucion.")";
                    $resultado=$dbconn->Execute($query);
                    if (!$resultado){
                         $this->error = "Error al insertar en \"hc_controles_paciente\" el control de Dietas.<br>";
                         $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
                         $dbconn->RollbackTrans();
                         return false;
                    }
               }
               else
               {
                    $query="UPDATE hc_controles_paciente SET evolucion_id=".$this->evolucion." WHERE ingreso=".$ctrlDietas['ingreso']." AND control_id=".$ctrlDietas['control_id'];
                    $resultado=$dbconn->Execute($query);
                    if (!$resultado){
                         $this->error = "Error al insertar en \"hc_controles_paciente\" el control de Reposo.<br>";
                         $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
                         return false;
                    }
               }
               
			$query="SELECT count(*) FROM hc_solicitudes_dietas WHERE evolucion_id=".$this->evolucion;
               $resultado=$dbconn->Execute($query);
               if (!$resultado)
               {
                    $this->error = "Error al consultar la tabla \"hc_control_dietas\" con evolucion_id=".$this->evolucion;
                    $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
               list($contar) = $resultado->FetchRow();
               if($contar != 0)
               {
               	$query_borrar = "DELETE FROM hc_solicitudes_dietas_detalle
                    			  WHERE ingreso=".$this->ingreso."
                                     AND evolucion_id=".$this->evolucion.";";
	               $resultado=$dbconn->Execute($query_borrar);
                    if (!$resultado)
                    {
                         $this->error = "Error al eliminar la tabla \"hc_control_dietas\" con evolucion_id=".$this->evolucion;
                         $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
                         $dbconn->RollbackTrans();
                         return false;
                    }
               	
                    $query_borrar = "DELETE FROM hc_solicitudes_dietas
                    			  WHERE ingreso=".$this->ingreso."
                                     AND evolucion_id=".$this->evolucion.";";
	               $resultado=$dbconn->Execute($query_borrar);
                    if (!$resultado)
                    {
                         $this->error = "Error al eliminar la tabla \"hc_control_dietas\" con evolucion_id=".$this->evolucion;
                         $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
                         $dbconn->RollbackTrans();
                         return false;
                    }
                                        
                    $query_borrar = "DELETE FROM hc_solicitudes_dietas_ayunos
                    			  WHERE ingreso=".$this->ingreso."
                                     AND fecha='".date('Y-m-d')."';";
	               $resultado=$dbconn->Execute($query_borrar);
                    if (!$resultado)
                    {
                         $this->error = "Error al eliminar la tabla \"hc_solicitudes_dietas_ayunos\" con evolucion_id=".$this->evolucion;
                         $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
                         $dbconn->RollbackTrans();
                         return false;
                    }

               }

               $query_Insert = "INSERT INTO hc_solicitudes_dietas (ingreso,
                                                                   evolucion_id,
                                                                   usuario_id,
                                                                   hc_dieta_id,
                                                                   sw_fraccionada,
                                                                   sw_ayuno,
                                                                   observaciones,
                                                                   fecha_registro)
                                                         VALUES(".$this->ingreso.",
                                                                 ".$this->evolucion.",
                                                                 ".$this->usuario_id.",
                                                                 ".$tipo_dieta.",
                                                                 '$fraccionada',
                                                                 '$ayuno',
                                                                 ".$observaciones.",
                                                                 now())";
               $resultado=$dbconn->Execute($query_Insert);
               if (!$resultado) {
                    $this->error = "Error al insertar en la tabla \"hc_solicitudes_dietas\"<br>";
                    $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
               
               if(!empty($caracteristicas))
               {
                    for($i=0;$i<sizeof($caracteristicas);$i++)
                    {
                         $query_Carac = "INSERT INTO hc_solicitudes_dietas_detalle (ingreso,
                                                                                evolucion_id,
                                                                                caracteristica_id)
                                                                           VALUES  (".$this->ingreso.",
                                                                                ".$this->evolucion.",
                                                                                ".$caracteristicas[$i].");";
                         
                         $resultado=$dbconn->Execute($query_Carac);
                         if (!$resultado) {
                              $this->error = "Error al insertar en la tabla \"hc_solicitudes_dietas_detalle\"<br>";
                              $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
                              $dbconn->RollbackTrans();
                              return false;
                         }
                    }
               }

               /*if($ayuno != '0')
               {
                    $query="SELECT * FROM hc_solicitudes_dietas_ayunos WHERE ingreso=".$this->ingreso."
                            AND fecha='".date("Y-m-d")."'";
                    $resultado=$dbconn->Execute($query);
                    if (!$resultado) {
                         $this->error = "Error al consultar la tabla \"hc_solicitudes_dietas_ayunos\"<br>";
                         $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
                         $dbconn->RollbackTrans();
                         return false;
                    }
                    if (!$resultado->RecordCount()) 
                    {
                    	//insertamos el ayuno.
                         $query="INSERT INTO hc_solicitudes_dietas_ayunos(ingreso,fecha,motivo,usuario_id,fecha_registro,hora_fin_ayuno,hora_inicio_ayuno)
                                 VALUES (".$this->ingreso.",'".date("Y-m-d")."','$observacion_Ayuno',".UserGetUID().",now(),'$horafin','$hora_inicio')";
                         $resultado=$dbconn->Execute($query);
                         if (!$resultado){
                              $this->error = "Error al insertar en la tabla \"hc_solicitudes_dietas_ayunos\"<br>";
                              $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
                              $dbconn->RollbackTrans();
                              return false;
                         }
                    }
                    else
                    {
                         $query="UPDATE hc_solicitudes_dietas_ayunos SET  motivo='$observacion_Ayuno',
                                 		hora_fin_ayuno='$horafin', hora_inicio_ayuno='$hora_inicio' 
                                 WHERE ingreso=".$this->ingreso."
                                 AND fecha=".date("Y-m-d")."";
                         $resultado=$dbconn->Execute($query);
                         if (!$resultado){
                              $this->error = "Error al actualizar la tabla \"hc_solicitudes_dietas_ayunos\"<br>";
                              $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
                              $dbconn->RollbackTrans();
                              return false;
                         }
                    }
               }*/
			$dbconn->CommitTrans();
               $this->FrmForma("");
               return true;
          }
          
          function GetCaracteristicas_Info()
          {
			GLOBAL $ADODB_FETCH_MODE;
          	list($dbconn) = GetDBconn();
               $query = "SELECT * FROM hc_solicitudes_dietas_detalle
               		WHERE ingreso=".$this->ingreso."
                         AND evolucion_id=".$this->evolucion."
                         ORDER BY caracteristica_id ASC;";
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
               $resultado = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error en la consulta en hc_solicitudes_suministros_estacion_detalle";
                    $this->mensajeDeError = "Ocurrió un error al insertar la solicitud de insumos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    return false;
               }
               while ($datos = $resultado->FetchRow())
               {
                    $caracteristicas[] = $datos;
               }
               return $caracteristicas;
          }
          

}//End class
?>
