<?php

/**
* $Id: app_Consulta_Fallas_Sistema_user.php,v 1.3 2006/04/05 19:39:53 luis Exp $
* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* @author  luis alejandro vargas
* @package IPSOFT-SIIS
*/

class app_Consulta_Fallas_Sistema_user extends classModulo
{
     
	function app_Registro_Fallas_Sistema_user()
	{
		$this->limit=GetLimitBrowser();
		return true;
	}
     
	function main()
	{
		$this->FormaConsultaPermisos();
		return true;
	}
     	/************************************************************************ 
	* Funcion que consulta los permisos del usuario al modulo
	* 
	* @return array 
	*************************************************************************/
	function ConsultaPermisos()
	{
		list($dbconn) = GetDBconn();
		global $ADODB_FETCH_MODE;
		
		$query="SELECT * 
			FROM userpermisos_fallas_sistema
			WHERE usuario_id=".UserGetUID()."
			AND sw_estado='0'";
		
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado=$dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

		if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "Consulta_Fallas_Sistema - ConsultaPermisos - SQL ERROR 1";
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

	/************************************************************************ 
	* Funcion que realiza la consulta de los registros
	* 
	* @return array 
	*************************************************************************/
     
	function ConsultarFallasSistema($fecha_ini,$fecha_fin,$tipo_falla,$profesional,$criterio_pro)
	{
		list($dbconn) = GetDBconn();
		global $ADODB_FETCH_MODE;

		if(empty($fecha_ini) AND empty($fecha_fin) AND empty($tipo_falla) AND empty($profesional))
		{
			
			$sqlCont="	SELECT COUNT(*)
					FROM registros_fallas_sistema a 
					LEFT JOIN tipos_fallas_sistema as b on(b.tipo_falla_id=a.tipo_falla_id)
					LEFT JOIN profesionales_usuarios as c on(c.usuario_id=a.usuario_id) 
					LEFT JOIN profesionales as d on(d.tipo_id_tercero=c.tipo_tercero_id AND d.tercero_id=c.tercero_id)";
		
			$this->ProcesarSqlConteo($sqlCont);
		
			
			$query="SELECT a.*, b.tipo_falla, d.tipo_id_tercero, d.tercero_id, d.nombre  
				FROM registros_fallas_sistema a 
				LEFT JOIN tipos_fallas_sistema as b on(b.tipo_falla_id=a.tipo_falla_id)
				LEFT JOIN profesionales_usuarios as c on(c.usuario_id=a.usuario_id) 
				LEFT JOIN profesionales as d on(d.tipo_id_tercero=c.tipo_tercero_id AND d.tercero_id=c.tercero_id)
				ORDER BY a.fecha_registro desc
				LIMIT ".$this->limit." OFFSET ".$this->offset;
		}
		else
		{
			if(!empty($fecha_ini) AND !empty($fecha_fin))
			{
				$sql="WHERE date(a.fecha_registro) >= '$fecha_ini' AND date(a.fecha_registro) <='$fecha_fin'";		
			}
			if(!empty($tipo_falla))
			{
				$sql="WHERE a.tipo_falla_id=$tipo_falla";	
			}
			if(!empty($profesional))
			{
				if($criterio_pro==1)
				{
					$sql="WHERE d.tercero_id='$profesional'";
				}
				else if($criterio_pro==2)
				{
					$sql="WHERE lower(d.nombre) like '%".strtolower($profesional)."%'";
				}	
			}
			
			$sqlCont="	SELECT COUNT(*)
					FROM registros_fallas_sistema a 
					LEFT JOIN tipos_fallas_sistema as b on(b.tipo_falla_id=a.tipo_falla_id)
					LEFT JOIN profesionales_usuarios as c on(c.usuario_id=a.usuario_id) 
					LEFT JOIN profesionales as d on(d.tipo_id_tercero=c.tipo_tercero_id AND d.tercero_id=c.tercero_id)
					$sql"; 
			
			$this->ProcesarSqlConteo($sqlCont);
			
			$query="SELECT a.*, b.tipo_falla, d.tipo_id_tercero, d.tercero_id, d.nombre  
				FROM registros_fallas_sistema a 
				LEFT JOIN tipos_fallas_sistema as b on(b.tipo_falla_id=a.tipo_falla_id)
				LEFT JOIN profesionales_usuarios as c on(c.usuario_id=a.usuario_id) 
				LEFT JOIN profesionales as d on(d.tipo_id_tercero=c.tipo_tercero_id AND d.tercero_id=c.tercero_id)
				$sql 
				ORDER BY a.fecha_registro desc
				LIMIT ".$this->limit." OFFSET ".$this->offset;
		}
		
		$ADODB_FETCH_MODE = ADODB_FETCH_ROW;
		$resultado=$dbconn->Execute($query);
		//$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

		if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "Consulta_Fallas_Sistema - ConsultarFallasSistema - SQL ERROR 2";
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
			$this->error = "Registro_Fallas_Sistema - LlamarTiposFallas - SQL ERROR 3";
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
		$this->limit = UserGetVar($uid,'LimitRows');
		
		if(empty($this->limit) || is_null($this->limit))
		{
			UserSetVar($uid,'LimitRows','10');
			$this->limit = UserGetVar($uid,'LimitRows');
		}

		return true;
	}
	
}//fin de la clase

?>
