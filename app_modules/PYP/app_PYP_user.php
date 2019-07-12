<?php

class app_PYP_user extends classModulo
{

	function app_PYP_user()
	{
		return true;
	}

	function main()
	{
    if(!$this->Menu())
		{
			return false;
		}
		return true;
	}


	function BuscarCronicos()
	{
		list($dbconn) = GetDBconn();
		$query ="SELECT * FROM tipo_cronicos";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
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

	function LlamarListadoAdministrativo()
	{
		if(!$this->ListadoAdministrativo())
		{
			return false;
		}
		return true;
	}

	function LlamarCreacionProtocolos()
	{
		if(!$this->CreacionProtocolos())
		{
			return false;
		}
		return true;
	}

	function EliminarCronico()
	{
		list($dbconn) = GetDBconn();
		$query ="DELETE FROM tipo_cronicos WHERE tipo_cronico_id=".$_REQUEST['dat']."";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
				$this->frmError["MensajeError"]="No se puede Borrar.";
		}
		else
		{ $this->frmError["MensajeError"]="El Programa se Borro.";   }

		if(!$this->ListadoAdministrativo())
		{
			return false;
		}
		return true;
	}

	function LlamarFormaCrear()
	{
		if(!$this->FormaCrear()){
			return false;
		}
		return true;
	}

	function LlamarFormaCrearProtocolo()
	{
		if(!$this->FormaCrearProtocolo()){
			return false;
		}
		return true;
	}

	function RelacionarCronicosProtocolos()
	{
		list($dbconn) = GetDBconn();
		foreach($_REQUEST as $k=>$v)
		{
			if(substr_count ($k,'caracteristica')==1)
			{
				if($v!='-1')
				{
					$a=explode('-',$k);
					$tiempo='tiempo'.$a[1];
					if(!empty($_REQUEST[$tiempo]))
					{
						$protocolo='protocolo_cronico'.$a[1];
						if(empty($_REQUEST[$protocolo]))
						{
							$sql="insert into protocolo_cronico(tipo_protocolo_id, tipo_cronico_id, tiempo, caracteristica)values(".$a[1]." ,".$_REQUEST['dat']." ,".$_REQUEST[$tiempo]." ,'".$v."');";
						}
						else
						{
							$sql="update protocolo_cronico set tiempo=".$_REQUEST[$tiempo].", caracteristica='".$v."' where protocolo_cronico_id=".$_REQUEST[$protocolo].";";
						}
					}
					else
					{
						$protocolo='protocolo_cronico'.$a[1];
						if(!empty($_REQUEST[$protocolo]))
						{
							$sql="delete from protocolo_cronico where protocolo_cronico_id=".$_REQUEST[$protocolo].";";
						}
						else
						{
							$this->frmError["MensajeError"]="Falta el tiempo de aparición del protocolo.";
							$sql="";
						}
					}
				}
				else
				{
					$a=explode('-',$k);
					$tiempo='tiempo'.$a[1];
					if(!empty($_REQUEST[$tiempo]))
					{
						$protocolo='protocolo_cronico'.$a[1];
						if(empty($_REQUEST[$protocolo]))
						{
							$sql="insert into protocolo_cronico(tipo_protocolo_id, tipo_cronico_id, tiempo)values(".$a[1]." ,".$_REQUEST['dat']." ,".$_REQUEST[$tiempo].");";
						}
						else
						{
							$sql="update protocolo_cronico set tiempo=".$_REQUEST[$tiempo]." where protocolo_cronico_id=".$_REQUEST[$protocolo].";";
						}
					}
					else
					{
						$protocolo='protocolo_cronico'.$a[1];
						if(!empty($_REQUEST[$protocolo]))
						{
							$sql="delete from protocolo_cronico where protocolo_cronico_id=".$_REQUEST[$protocolo].";";
						}
						else
						{
							$this->frmError["MensajeError"]="Falta el tiempo de aparición del protocolo.";
							$sql="";
						}
					}
				}
				if(!empty($sql))
				{
					$result = $dbconn->Execute($sql);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
				}
			}
		}
		if($this->ListadoProtocolo()==false)
		{
			return false;
		}
		return  true;
	}


	function InsertarCronico()
	{
		if( !$_REQUEST['Nombre'] ){
			if(!$_REQUEST['Nombre']){ $this->frmError["Nombre"]=1; }
			$this->frmError["MensajeError"]="Debe digitar el nombre.";
			if(!$this->FormaCrear()){
				return false;
			}
			return true;
		}

		list($dbconn) = GetDBconn();
		$query ="INSERT INTO tipo_cronicos(nombre)
					VALUES('".$_REQUEST['Nombre']."')";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}


		$this->frmError["MensajeError"]="El Cronico se Creo Correctamente.";
		if(!$this->ListadoAdministrativo())
		{
			return false;
		}
		return true;
	}

	function InsertarProtocoloDetalle()
	{
		if( !$_REQUEST['Nombre'] ){
			if(!$_REQUEST['Nombre']){ $this->frmError["Nombre"]=1; }
			$this->frmError["MensajeError"]="Debe digitar el nombre.";
			if(!$this->FormaCrear()){
				return false;
			}
			return true;
		}

		list($dbconn) = GetDBconn();
		$query ="INSERT INTO detalle_protocolo(nombre,tipo_protocolo_id)
					VALUES('".$_REQUEST['Nombre']."',".$_REQUEST['tipo_protocolo_id'].");";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}


		$this->frmError["MensajeError"]="El Cronico se Creo Correctamente.";
		if(!$this->FormaListadoProtocoloDetalle())
		{
			return false;
		}
		return true;
	}

	function ListadoProtocolo()
	{
		if(!$this->FormaListadoProtocolo($_REQUEST['dat'])){
			return false;
		}
		return true;
	}

	function ListadoProtocoloDetalle()
	{
		if(!$this->FormaListadoProtocoloDetalle()){
			return false;
		}
		return true;
	}

	function LlamarFormaCrearProtocoloDetalle()
	{
		if(!$this->FormaCrearProtocoloDetalle()){
			return false;
		}
		return true;
	}

	function BuscarProtocolos($Tipo)
	{
		list($dbconn) = GetDBconn();
		$query ="select a.tipo_protocolo_id, a.nombre, b.protocolo_cronico_id ,b.tiempo, b.caracteristica from tipo_protocolo as a left join protocolo_cronico as b on (a.tipo_protocolo_id=b.tipo_protocolo_id and tipo_cronico_id=$Tipo) order by a.tipo_protocolo_id;";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
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

	function BuscarTodosProtocolos()
	{
		list($dbconn) = GetDBconn();
		$query ="select * from tipo_protocolo order by tipo_protocolo_id;";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
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

	function GuardarProtocolosDetalle()
	{
		list($dbconn) = GetDBconn();
		$query ="update detalle_protocolo set nombre='".$_REQUEST['nombre']."' where detalle_protocolo_id=".$_REQUEST['detalle_protocolo_id'].";";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		if($this->FormaListadoProtocoloDetalle()==false)
		{
			return false;
		}
		return true;
	}

	function EliminarProtocolosDetalle()
	{
		list($dbconn) = GetDBconn();
		$query ="delete from detalle_protocolo where detalle_protocolo_id=".$_REQUEST['detalle_protocolo_id'].";";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		if($this->FormaListadoProtocoloDetalle()==false)
		{
			return false;
		}
		return true;
	}

	function BuscarTodosProtocolosDetalle()
	{
		list($dbconn) = GetDBconn();
		$query ="select * from detalle_protocolo where tipo_protocolo_id='".$_REQUEST['tipo_protocolo_id']."' order by detalle_protocolo_id;";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
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

	function EliminarTipoProtocolo()
	{
		list($dbconn) = GetDBconn();
		$query ="delete from tipo_protocolo where tipo_protocolo_id='".$_REQUEST['tipo_protocolo_id']."';";
		$result = $dbconn->Execute($query);
		if($this->CreacionProtocolos()==false)
		{
			return false;
		}
		return true;
	}

	function ActualizarTipoProtocolo()
	{
		list($dbconn) = GetDBconn();
		$query ="update tipo_protocolo set nombre='".$_REQUEST['nombre']."', caracteristicas='".$_REQUEST['caracteristica']."', tiempo=".$_REQUEST['tiempo'].", sexo='".$_REQUEST['sexo']."', edad_min=".$_REQUEST['edad_min'].", edad_max=".$_REQUEST['edad_max'].", gestante='".$_REQUEST['gestante']."' where tipo_protocolo_id=".$_REQUEST['tipo_protocolo_id'].";";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		if(empty($_REQUEST['PM']))
		{
			$this->ListadoProtocolo();
		}
		else
		{
			$this->CreacionProtocolos();
		}
		return true;
	}

	function InsertarTipoProtocolo()
	{
		list($dbconn) = GetDBconn();
		$query ="insert into tipo_protocolo (nombre, caracteristicas, tiempo, sexo, edad_min, edad_max, gestante) values ('".$_REQUEST['nombre']."', '".$_REQUEST['caracteristica']."', ".$_REQUEST['tiempo'].", '".$_REQUEST['sexo']."', ".$_REQUEST['edad_min'].", ".$_REQUEST['edad_max'].", '".$_REQUEST['gestante']."');";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$this->CreacionProtocolos();
		return true;
	}

	/**
	* Busca los diferentes tipos de sexo utilizados en la aplicacion
	* @access public
	* @return array
	*/
  function sexo()
  {
		list($dbconn) = GetDBconn();
		$result="";
		$query = "SELECT sexo_id,descripcion FROM tipo_sexo ORDER BY indice_de_orden";
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla maestra 'tipo_sexo' esta vacia ";
				return false;
			}
				while (!$result->EOF) {
					$vars[$result->fields[0]]=$result->fields[1];
					$result->MoveNext();
				}
		}
		$result->Close();
		return $vars;
	}
}
?>

