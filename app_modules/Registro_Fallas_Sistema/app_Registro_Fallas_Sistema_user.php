<?php

/**
* $Id: app_Registro_Fallas_Sistema_user.php,v 1.3 2006/05/17 14:02:08 luis Exp $
* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* @author  luis alejandro vargas
* @package IPSOFT-SIIS
*/

class app_Registro_Fallas_Sistema_user extends classModulo
{
	function app_Registro_Fallas_Sistema_user()
	{
		$this->limit=GetLimitBrowser();
		return true;
	}
     
	function main()
	{
		unset($_SESSION['error']);
		unset($_SESSION['registro']);
		$this->FormaRegistro();
		return true;
	}
     
	/************************************************************************ 
	* Funcion donde se ingresa o actualiza los registros en la base de datos por parte 
	* de los profesionales  
	* 
	* @return array 
	*************************************************************************/
     
	function IngresarRegistroFallaSistema()
	{
		$tipo_falla=$_REQUEST['tipo_falla'];
		$fecha_ocurrio=$this->FechaStamp($_REQUEST['fecha_ocurrio']);
		$hora_ocurrio=$_REQUEST['hora'];
		$descripcion=$_REQUEST['descripcion'];

		$editar=$_REQUEST['editar'];

		if(!empty($fecha_ocurrio) && !empty($hora_ocurrio) && !empty($descripcion) && !empty($tipo_falla))
		{
			list($dbconn) = GetDBconn();
			global $ADODB_FETCH_MODE;

			$query="SELECT max(registro_id) FROM registros_fallas_sistema";
			
			$ADODB_FETCH_MODE = ADODB_FETCH_ROW;
			$resultado=$dbconn->Execute($query);
			
			while($res=$resultado->FetchRow()) 
			{
				$filas=$res[0]+1;
			}
			
			if(!empty($editar))
			{
				$query="UPDATE registros_fallas_sistema 
				SET fecha_ocurrio='".date("$fecha_ocurrio $hora_ocurrio")."', tipo_falla_id='".$tipo_falla."',descripcion='".$descripcion."' 
				WHERE registro_id=".$_SESSION['registro']." AND usuario_id=".UserGetUID();
			}
			else
			{
				$query="INSERT INTO registros_fallas_sistema VALUES($filas,'$tipo_falla','$descripcion',".UserGetUID().",'".date('Y-m-d h:i:s')."','".date("$fecha_ocurrio $hora_ocurrio")."')";
			}
			
			unset($_SESSION['editar']);
			
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
	
			if ($dbconn->ErrorNo() != 0) 
			{
				$this->error = "Registro_Fallas_Sistema - IngresarRegistroFallaSistema - SQL ERROR 1";
				$this->mensajeDeError = $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				$this->FormaRegistro();
			}
			$_SESSION['error']=0;
			$resultado->Close();
		}
		else
		{
			$_SESSION['error']=0;
			$this->FormaRegistro($tipo_falla,$fecha_ocurrio,$hora_ocurrio,$descripcion);
		}
		
		return true;
	}
	
	/****
	* Funcion que realiza un listado de todas los registros de fallas del sistema
	* @access private
	* @return boolean
	****/
	
	function ListarRegistros()
	{
	
		list($dbconn) = GetDBconn();
		global $ADODB_FETCH_MODE;
		
		$sqlCont="SELECT count(*) 
			FROM registros_fallas_sistema
			WHERE usuario_id=".UserGetUID();
		
		$this->ProcesarSqlConteo($sqlCont);
		
		$query="SELECT a.registro_id,a.tipo_falla_id,b.tipo_falla,a.descripcion,a.usuario_id,a.fecha_registro,a.fecha_ocurrio
			FROM registros_fallas_sistema a, tipos_fallas_sistema b
			WHERE usuario_id=".UserGetUID()."
			AND a.tipo_falla_id=b.tipo_falla_id
			ORDER BY fecha_ocurrio desc
			LIMIT ".$this->limit." OFFSET ".$this->offset;
		
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado=$dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		
		if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "Registro_Fallas_Sistema - ListarRegistros - SQL ERROR 2";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
		}
		
		while($res=$resultado->FetchRow()) 
		{
			$filas[]=$res;
		}
		
		$resultado->Close();
		
		return $filas;
	}
	
	/****
	* Funcion que elimina un registro de un usuario
	* @access private
	* @return boolean
	****/
	
	function EliminarRegistro()
	{
		list($dbconn) = GetDBconn();
		global $ADODB_FETCH_MODE;
	
		if($_REQUEST['eliminar'])
		{
			$registros=$_REQUEST['registros'];
			$query="DELETE FROM registros_fallas_sistema 
			WHERE registro_id=".$registros['registro_id']." AND usuario_id=".UserGetUID();
		}

		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado=$dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		
		if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "Registro_Fallas_Sistema - EliminarRegistro - SQL ERROR 3";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
		}
		
		$this->FormaRegistro();
		
		$resultado->Close();
		return true;
	}
	
	/****
	* Separa la Fecha del formato timestamp
	* @access private
	* @return string
	* @param date fecha
	****/
	function FechaStamp($fecha)
	{
		if($fecha)
		{
			$fech = strtok ($fecha,"-");
			for($l=0;$l<3;$l++)
			{
				$date[$l]=$fech;
				$fech = strtok ("-");
			}

			return  ceil($date[2])."-".str_pad(ceil($date[1]),2,0,STR_PAD_LEFT)."-".str_pad(ceil($date[0]),2,0,STR_PAD_LEFT);
		}
	}
	
	/***
	* Funcion, donde se inicializan las variables de paginaActual, offset y conteo,
	* importantes a la hora de referenciar al paginador
	* 
	* @param String Cadena que contiene la consulta sql del conteo 
	* @return boolean 
	***/
	
	function ProcesarSqlConteo($sqlCont)
	{
		$this->paginaActual = 1;
		$this->offset = 0;
		$this->ObtenerLimite();
		
		if($_REQUEST['offset'])
		{
			$this->paginaActual = intval($_REQUEST['offset']);
			if($this->paginaActual > 1)
			{
				$this->offset = ($this->paginaActual - 1) * ($this->limit);
			}
		}		
		
		list($dbconn) = GetDBconn();
		$result=$dbconn->Execute($sqlCont);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->frmError['MensajeError'] = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}

		if(!$result->EOF)
		{
			$this->conteo = $result->fields[0];
			$result->MoveNext();
			$result->Close();
		}
		
		return true;
	}
	
	/***
	* Funcion que trae los tipos falla que hay
	* @return array 
	***/
	
	function LlamarTiposFallas()
	{
		list($dbconn) = GetDBconn();
		global $ADODB_FETCH_MODE;
		
		$query="SELECT * FROM tipos_fallas_sistema";

		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado=$dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		
		if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "Registro_Fallas_Sistema - LlamarTiposFallas - SQL ERROR 4";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
		}
		
		while($res=$resultado->FetchRow()) 
		{
			$filas[]=$res;
		}
		
		$resultado->Close();
		
		return $filas;
	}
	
	
	/***
	* Funcion, donde se inicializan las variables de paginaActual, offset y conteo,
	* importantes a la hora de referenciar al paginador
	* 
	* @param String Cadena que contiene la consulta sql del conteo 
	* @return boolean 
	***/
	
	function ObtenerLimite()
	{
		$uid = UserGetUID();
		UserSetVar($uid,'LimitRows','10');
		$this->limit = UserGetVar($uid,'LimitRows');

		return true;
	}
     
}//fin de la clase

?>
