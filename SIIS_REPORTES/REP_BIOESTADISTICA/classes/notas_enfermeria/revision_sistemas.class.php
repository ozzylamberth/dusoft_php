<?php

class RevisionSistemas
{

  var $salida='';
	var $estacion_id='';
	var $ingreso='';
	var $sistema_id='';
	var $url='';
	var $url_origen='';
	var $error='';
	var $mensajeDeError='';
	var $plantilla='';

	function RevisionSistemas($estacion_id,$ingreso,$url,$url_origen)
	{
	  $this->salida='';
		$this->estacion_id=$estacion_id;
		$this->url=$url;
		$this->url_origen=$url_origen;
		$this->ingreso=$ingreso;
		return true;
	}

	function GetSalida()
	{
    return $this->salida;
	}

	function Error()
	{
		return $this->error;
	}

	function ErrorMsg()
	{
		return $this->mensajeDeError;
	}

	function Iniciar()
	{
		GLOBAL $VISTA;
		GLOBAL $ADODB_FETCH_MODE;
		$datos_sistemas=array();
		//echo "<br><br>R->>";print_r($_REQUEST);echo "<br>";

		if (!empty($_REQUEST['opc_seleccion'])){
			foreach($_REQUEST['opc_seleccion'] as $key =>$value){
				$opc_seleccion[$value]=$this->GetDetalleSistema($value);
				if (!empty($_REQUEST['opc_complemento'][$value])){
					$opc_complemento[$value]=$_REQUEST['opc_complemento'][$value];
				}
			}
			if (!empty($opc_complemento) && !empty($opc_seleccion)){
				$_SESSION['REVISION_SISTEMAS'][$this->ingreso][$_REQUEST['sistema']]=$opc_seleccion;
				$_SESSION['REVISION_SISTEMAS_TXT'][$this->ingreso][$_REQUEST['sistema']]=$opc_complemento;
			}
			elseif (!empty($opc_seleccion) && empty($opc_complemento)){
				$_SESSION['REVISION_SISTEMAS'][$this->ingreso][$_REQUEST['sistema']]=$opc_seleccion;
			}
		}
		else{
			if (!empty($_SESSION['REVISION_SISTEMAS'][$this->ingreso][$_REQUEST['sistema']]) && !empty($_REQUEST['sistema']) && empty($_REQUEST['inicio_sistemas'])){
				unset($_SESSION['REVISION_SISTEMAS'][$this->ingreso][$_REQUEST['sistema']]);
				unset($_SESSION['REVISION_SISTEMAS_TXT'][$this->ingreso][$_REQUEST['sistema']]);
			}
		}

		if (empty($_SESSION['REVISION_SISTEMAS'][$this->ingreso])){
				unset($_SESSION['REVISION_SISTEMAS']);
				unset($_SESSION['REVISION_SISTEMAS_TXT']);
		}
		$_REQUEST['sistema']=$_REQUEST['sistema_n'];
		unset($_REQUEST['opc_seleccion']);
		unset($_REQUEST['opc_complemento']);


		//echo "<br><br>S->";print_r($_SESSION);echo "<br><br>";

		if(!IncludeFile("classes/notas_enfermeria/$VISTA/RevisionSistemas.$VISTA.php")){
				$this->error="Error en la clase de REVISION DE SISTEMAS";
        $this->mensajeDeError="ERROR AL INCLUIR  EL ARCHIVO: classes/notas_enfermeria/$VISTA/RevisionSistemas.$VISTA.php";
				return false;
		}

		if ($_REQUEST['resumen'] && !$_REQUEST['finalizar']){
			$info_sistema=$this->GetSistemas();
			if (!$info_sistema){
				return false;
			}
			$info_usuario=$this->GetInfoUser(UserGetUID());
			if (!$info_usuario){
				return false;
			}

			foreach($_SESSION['REVISION_SISTEMAS'][$this->ingreso] as $key => $value){
				foreach($value as $k1 => $valor){
					$categoria[$key][$this->GetCategoria($key,$k1)][$k1][]=$valor;
				}
			}
			$this->salida.=SetResumenSistemas($this->ingreso,$info_sistema,$info_usuario,$categoria);
			//$this->salida.='';
			$this->salida.=ConfirmarCerrar($this->url);
			return true;
		}

		if ($_REQUEST['resumen'] && $_REQUEST['finalizar']){
			$info_sistema=$this->GetSistemas();
			if (!$info_sistema){
				return false;
			}
			$info_usuario=$this->GetInfoUser(UserGetUID());
			if (!$info_usuario){
				return false;
			}

			foreach($_SESSION['REVISION_SISTEMAS'][$this->ingreso] as $key => $value){
				foreach($value as $k1 => $valor){
					$categoria[$key][$this->GetCategoria($key,$k1)][$k1][]=$valor;
				}
			}

			//parte de arley o rosa por si la mia no funciona..
			//$insercion=$this->InsertarSistemas($info_usuario['usuario_id'],SetResumenSistemas($this->ingreso,$info_sistema,$info_usuario,$categoria));

			//parte de duvan
			$insercion=$this->InsertarSistemas($info_usuario['usuario_id'],$_SESSION['RXS']['TABLA']);
			if (!$insercion){
				return false;
			}
			elseif ($insercion==='empty'){
				$this->salida.=Cerrar($this->url,$this->url_origen,1);
				return true;
			}
			$this->salida.=Cerrar($this->url,$this->url_origen);
			return true;
		}

		list($dbconn) = GetDBconn();
    $query = "SELECT hc_ne_plantilla_sistema_id FROM hc_ne_plantillas_estacion WHERE estacion_id='". $this->estacion_id."'";
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0) {
        $this->error = "Error al Cargar Plantilla de la Clase REVISION DE SISTEMAS";
        $this->mensajeDeError = $dbconn->ErrorMsg();
        return false;
    }

    if ($result->EOF) {
			$this->salida.="LA ESTACION NO TIENE ASIGNADA UNA PLANTILLA PARA LA REVISION DE SISTEMAS";
			return true;
    }
		else{
			list($this->plantilla)=$result->FetchRow();
		}

		if (!empty($_REQUEST['sistema_n'])){
			$query="SELECT b.*, f.descripcion as sistema, e.descripcion as categoria, a.descripcion as opcion, d.descripcion
							FROM	hc_ne_sistemas_revision_detalle a,
										hc_ne_plantilla_detalle b,
										hc_ne_plantillas_estacion c,
										hc_ne_plantillas_sistema d,
										hc_ne_sistemas_revision e,
										hc_ne_sistemas f
							WHERE
										c.estacion_id = '".$this->estacion_id."' AND
										c.hc_ne_plantilla_sistema_id = d.hc_ne_plantilla_sistema_id AND
										b.hc_ne_plantilla_sistema_id = c.hc_ne_plantilla_sistema_id AND
										b.hc_ne_sistema_id = ".$_REQUEST['sistema_n']." AND
										b.hc_ne_detalle_id = a.hc_ne_detalle_id AND
										b.hc_ne_sistema_id = a.hc_ne_sistema_id AND
										b.hc_ne_sistema_revision_id = a.hc_ne_sistema_revision_id AND
										e.hc_ne_sistema_revision_id = b.hc_ne_sistema_revision_id AND
										e.hc_ne_sistema_id = b.hc_ne_sistema_id AND
										f.hc_ne_sistema_id = b.hc_ne_sistema_id
									 ";
			//echo "<br>Q->".$query;
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Consultar las Opciones del Sistema de la Clase REVISION DE SISTEMAS";
					$this->mensajeDeError = $dbconn->ErrorMsg();
					return false;
			}
			while ($data=$result->FetchRow()){
				$datos_sistemas[]=$data;
			}
			$datos_paciente=$this->GetDatosPaciente();
			if (empty($datos_paciente)){
				return false;
			}
			elseif (!is_array($datos_paciente)){
				return true;
			}
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			$this->salida.=GetRevisionSistemas($datos_sistemas,$datos_paciente,$_REQUEST['vector_sistema'],$_REQUEST['sistema'],$_REQUEST['sistema_n'],$this->url,$this->url_origen);
		}//End if
		else{
			//Es por que tiene que ir a la pantalla inicial y selecciono todos los sistemas que
			// tiene asignada la estacion en plantilla_detalle
			$query="SELECT b.hc_ne_sistema_id,b.descripcion
							FROM
										hc_ne_plantilla_detalle a,
										hc_ne_sistemas b,
										hc_ne_plantillas_estacion c
							WHERE
										c.estacion_id = '".$this->estacion_id."' AND
										c.hc_ne_plantilla_sistema_id = a.hc_ne_plantilla_sistema_id AND
										a.hc_ne_sistema_id = b.hc_ne_sistema_id
							GROUP BY b.hc_ne_sistema_id, b.descripcion
							ORDER BY b.hc_ne_sistema_id
										";
			//echo "<br>Q->".$query;
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Consultar las Opciones del Sistema de la Clase REVISION DE SISTEMAS";
					$this->mensajeDeError = $dbconn->ErrorMsg();
					return false;
			}
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($result->EOF) {
				$this->salida.="LA ESTACION NO TIENE ASIGNADO ALGUN SISTEMA PARA SU REVISIÓN.";
				return true;
			}
			else{
				while ($data=$result->FetchRow()){
					$datos_sistemas[]=$data;
				}
				$datos_paciente=$this->GetDatosPaciente();
				if (empty($datos_paciente)){
					return false;
				}
				elseif (!is_array($datos_paciente)){
					return true;
				}
  				$this->salida.=GetRevisionSistemaInico($datos_sistemas,$datos_paciente,$this->url,$this->url_origen);
			}
		}//End else
		return true;
	}


	function GetCategoria($sistema_id,$detalle_id)
	{
    list($dbconn) = GetDBconn();
		$query="SELECT a.descripcion
						FROM
									hc_ne_sistemas_revision a,
									hc_ne_sistemas_revision_detalle b
						WHERE
									b.hc_ne_sistema_id=$sistema_id AND
									b.hc_ne_detalle_id=$detalle_id AND
									a.hc_ne_sistema_id = b.hc_ne_sistema_id AND
									a.hc_ne_sistema_revision_id = b.hc_ne_sistema_revision_id
									";
		//echo "<br>Q->".$query;
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Consultar las Opciones del Sistema de la Clase REVISION DE SISTEMAS";
				$this->mensajeDeError = $dbconn->ErrorMsg();
				return false;
		}
		list($datos)=$result->FetchRow();
		return $datos;
	}

	function InsertarSistemas($usuario_id,$info)
	{
		if (!empty($info)){
			list($dbconn) = GetDBconn();
			$query="INSERT INTO hc_notas_enfermeria(fecha,ingreso,usuario_id,notas)
							VALUES ('".date("Y-m-d H:i:s")."',".$this->ingreso.",".$usuario_id.",'".addslashes($info)."')";

			$resultado=$dbconn->Execute($query);
			if (!$resultado){
				$this->error = "Error al Insertar los registros de Revision de Sistemas de la Clase REVISION DE SISTEMAS";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}
		}
		else{
			return "empty";
		}

		unset($_SESSION['REVISION_SISTEMAS']);
		unset($_SESSION['REVISION_SISTEMAS_TXT']);
		unset($_REQUEST['sistema']);
		unset($_REQUEST['sistema_n']);
		unset($_SESSION['RXS']['TABLA_MUESTRA']);//tabla que se muestra cuando se inserta la nota.
		unset($_SESSION['RXS']['TABLA']);//tabla que se muestra en el resumen de la historia clinica.
		return true;
	}


	function GetInfoUser($usuario_id)
	{
    list($dbconn) = GetDBconn();
		GLOBAL $ADODB_FETCH_MODE;
		$query="SELECT *
						FROM system_usuarios
						WHERE usuario_id=".$usuario_id;
		//echo "<br><br>Q->".$query;
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado=$dbconn->Execute($query);
		if (!$resultado){
			$this->error = "Error al Consultar los datos del usuario de la Clase REVISION DE SISTEMAS";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
		}
		$datos=$resultado->FetchRow();
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		return $datos;
	}


	function GetSistema($sistema_id)
	{
    list($dbconn) = GetDBconn();
		$query="SELECT descripcion
						FROM
									hc_ne_sistemas
						WHERE
									hc_ne_sistema_id=".$sistema_id."; ";
		//echo "<br>Q->".$query;
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Consultar la descripción del Sistema de la Clase REVISION DE SISTEMAS";
				$this->mensajeDeError = $dbconn->ErrorMsg();
				return false;
		}
		list($datos)=$result->FetchRow();
		return $datos;
	}


	function GetDetalleSistema($detalle_id)
	{
    list($dbconn) = GetDBconn();
		$query="SELECT descripcion
						FROM
									hc_ne_sistemas_revision_detalle
						WHERE
									hc_ne_detalle_id=".$detalle_id."; ";
		//echo "<br>Q->".$query;
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Consultar las Opciones del Sistema de la Clase REVISION DE SISTEMAS";
				$this->mensajeDeError = $dbconn->ErrorMsg();
				return false;
		}
		list($datos)=$result->FetchRow();
		return $datos;
	}


	function GetSistemas()
	{
		GLOBAL $ADODB_FETCH_MODE;
		$datos_sistemas=array();
    list($dbconn) = GetDBconn();

			$query="SELECT b.hc_ne_sistema_id,b.descripcion
							FROM
										hc_ne_plantilla_detalle a,
										hc_ne_sistemas b,
										hc_ne_plantillas_estacion c
							WHERE
										c.estacion_id = '".$this->estacion_id."' AND
										c.hc_ne_plantilla_sistema_id = a.hc_ne_plantilla_sistema_id AND
										a.hc_ne_sistema_id = b.hc_ne_sistema_id
							GROUP BY b.hc_ne_sistema_id, b.descripcion
							ORDER BY b.hc_ne_sistema_id
										";

		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);

		if($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Consultar los Datos del paciente de la Clase REVISION DE SISTEMAS con el ingreso ".$this->ingreso;
				$this->mensajeDeError = $dbconn->ErrorMsg();
				return false;
		}
		while ($data=$result->FetchRow()){
			$datos_sistemas[$data['hc_ne_sistema_id']]=$data;
		}

		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		$datos=$result->FetchRow();
		return $datos_sistemas;
	}//End function


	function GetDatosPaciente()
	{
		GLOBAL $ADODB_FETCH_MODE;
		$datos=array();
    list($dbconn) = GetDBconn();

		$query = "SELECT
										PAC.primer_apellido,
										PAC.segundo_apellido,
										PAC.segundo_nombre,
										PAC.primer_nombre,
										INGS.ingreso,
										CTS.numerodecuenta,
										MH.ingreso_dpto_id,
										CAMA.cama,
										PIEZA.pieza
							FROM  movimientos_habitacion MH,
										cuentas CTS,
										camas CAMA,
										piezas PIEZA,
										ingresos INGS,
										pacientes PAC
							WHERE	INGS.ingreso=".$this->ingreso." AND
										INGS.estado='1' AND
										PAC.paciente_id=INGS.paciente_id AND
										PAC.tipo_id_paciente=INGS.tipo_id_paciente AND
										CTS.ingreso=INGS.ingreso AND
										CTS.estado='1' AND
										MH.numerodecuenta=CTS.numerodecuenta AND
										MH.fecha_egreso IS NULL AND
										MH.fecha_ingreso IS NOT NULL AND
										MH.cama=CAMA.cama AND
										CAMA.pieza=PIEZA.pieza";

		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);

		if($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Consultar los Datos del paciente de la Clase REVISION DE SISTEMAS con el ingreso ".$this->ingreso;
				$this->mensajeDeError = $dbconn->ErrorMsg();
				return false;
		}
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($result->EOF) {
			$this->salida.="EL PACIENTE NO SE ENCUENTRA EN LA ESTACIÓN.";
			return true;
		}
		else{
			$datos=$result->FetchRow();
			return $datos;
		}
	}//End function



}//End class

?>
