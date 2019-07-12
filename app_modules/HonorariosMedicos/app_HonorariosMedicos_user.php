<?php

/**
* $Id: app_HonorariosMedicos_user.php,v 1.11 2007/04/18 19:17:07 luis Exp $
* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* @author  luis alejandro vargas
* @package IPSOFT-SIIS
*/

class app_HonorariosMedicos_user extends classModulo
{
	function app_HonorariosMedicos_user()
	{
		return true;
	}
    
	function main()
	{
		unset($_SESSION['evolucion']);
		unset($_SESSION['mod']);
		$this->FormaConsultaProfesionales();
		return true;
	}
	
	function PermisosUsuarios()
	{
		list($dbconn) = GetDBconn();
		global $ADODB_FETCH_MODE;
		
		$query="SELECT pvh.usuario_id,pvh.empresa_id
						FROM userpermisos_voucher_honorarios pvh, system_usuarios u
						WHERE pvh.usuario_id=".UserGetUID()."
						AND pvh.usuario_id=u.usuario_id
						AND u.activo='1'";
     
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado=$dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		
		if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "HonorariosMedicos - PermisosUsuarios - SQL ERROR 1";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
		}
		
		while($res=$resultado->FetchRow())
			$filas[]=$res;
		
		$resultado->Close();
		
		return $filas;
	}

	function BuscarProfesionales($opcion,$buscar)
	{
		list($dbconn) = GetDBconn();
		global $ADODB_FETCH_MODE;
		
		$where="";
		if(!empty($buscar))
		{
			switch($opcion)
			{
				case 1:
					$where="AND p.usuario_id=$buscar";
				breaK;
				case 2:
					$where="AND u.usuario ILIKE '%".$buscar."%'";
				breaK;
				case 3:
					$where="AND pro.nombre ILIKE '%".$buscar."%'";
				breaK;
			}
		}
		
		
		$sqlCont="SELECT count(*)
						FROM system_usuarios as u
						JOIN profesionales_usuarios as p
											ON
											(
												u.usuario_id=p.usuario_id
											)
						JOIN profesionales as pro
											ON
											(
												p.tipo_tercero_id=pro.tipo_id_tercero
												AND p.tercero_id=pro.tercero_id
											)
						WHERE u.activo='1' $where";
		
		$this->ProcesarSqlConteo($sqlCont);
		
		$query="SELECT 	u.usuario_id,
										u.usuario,
										u.nombre,
										u.descripcion,
										pro.tipo_id_tercero,
										pro.tercero_id,
										pro.nombre as nombre_profesional
						FROM system_usuarios as u
						JOIN profesionales_usuarios as p
											ON
											(
												u.usuario_id=p.usuario_id
											)
						JOIN profesionales as pro
											ON
											(
												p.tipo_tercero_id=pro.tipo_id_tercero
												AND p.tercero_id=pro.tercero_id
											)
						WHERE u.activo='1' $where
						ORDER BY pro.nombre
						LIMIT ".$this->limit." OFFSET ".$this->offset."";
     
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado=$dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		
		if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "HonorariosMedicos - BuscarProfesionales - SQL ERROR 1";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
		}
		
		while($res=$resultado->FetchRow())
			$filas[]=$res;
		
		$resultado->Close();
		
		return $filas;
	}
	
	
	/************************************************************************ 
	* Funcion que trae la informacion del usuario que es profesional
	* 
	* @return Array
	*************************************************************************/
	function BuscarInformacionUsuario($usuario_id=null)
	{
		list($dbconn) = GetDBconn();
		global $ADODB_FETCH_MODE;
	
		if(!$usuario_id)
			$usuario_id=UserGetUID();
		
		$query="SELECT u.usuario_id,u.usuario,u.nombre,u.descripcion,p.tipo_tercero_id,p.tercero_id
						FROM system_usuarios u, profesionales_usuarios p
						WHERE u.usuario_id=p.usuario_id
						AND u.usuario_id=".$usuario_id."
						AND u.activo='1'";
     
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado=$dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		
		if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "HonorariosMedicos - BuscarInformacionUsuario - SQL ERROR 1";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
		}
		
		while($res=$resultado->FetchRow())
			$filas[]=$res;
		
		$resultado->Close();
		
		return $filas;
	}
     
	/************************************************************************ 
	* Funcion que consulta en la base de datos la informacion que contienen 
	* los honorarios
	* 
	* @return array de la consulta
	* @param string tipo_id_tercero
	* @param string tercero_id
	* @param date fecha
	*************************************************************************/
   
	function consulta_permiso_fecha()
	{
	list($dbconn)=GetDBconn();
	global $ADODB_FETCH_MODE;
	
	$query="SELECT sw_consulta_todos, fecha_consulta FROM usuarios_honorarios_consulta where usuario_id = ".UserGetUID()."";
	$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
	$res=$dbconn->Execute($query);
	$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
	
	if($dbconn->ErrorNo() != 0)
	{
		$this->error = "HonorariosMedicos - consulta_permiso_fecha - SQL ERROR 1";
		$this->mensajeDeError = $dbconn->ErrorMsg();
		return false;
	}
	
	while($result=$res->FetchRow())
	{
		$fila[]=$result;		
	}
	return $fila;
	
	}
	
	function MostrarHonorarios($tipo_id_profesional,$profesional_id,$fecha_ini,$fecha_fin)
	{
		list($dbconn) = GetDBconn();
		global $ADODB_FETCH_MODE;

		$query="SELECT a.*,
						c.id,c.razon_social,
						b.descripcion as desc_cargo,f.nombre_tercero,
						d.nombre,e.descripcion as desc_tipo_pro,
						g.plan_descripcion,
						h.primer_apellido, h.segundo_apellido, h.primer_nombre, h.segundo_nombre,
						h.primer_apellido || ' ' || h.segundo_apellido || ' ' || h.primer_nombre || ' ' || h.segundo_nombre as nombre_paciente,
						i.numero as numero_nc,
						i.prefijo as prefijo_nc,
						i.valor as valor_nc,
						J.numero as numero_nD,
						j.prefijo as prefijo_nd,
						j.valor as valor_nd,
						TO_CHAR(a.fecha_radicacion,'YYYY-MM-DD') as fecha_rad,
						a.numero_recibo,
						m.numero_factura_id,
						k.consecutivo_procedimiento
						FROM voucher_honorarios as a
						JOIN cuentas_detalle as x ON(a.transaccion=x.transaccion)
						JOIN tarifarios_detalle as b ON(a.tarifario_id=b.tarifario_id and a.cargo=b.cargo)
						JOIN empresas as c ON(a.empresa_id=c.empresa_id)
						JOIN profesionales as d ON(a.tipo_id_profesional=d.tipo_id_tercero and a.profesional_id=d.tercero_id)
						JOIN tipos_profesionales as e ON(d.tipo_profesional=e.tipo_profesional)
						JOIN terceros as f ON(a.tipo_id_tercero=f.tipo_id_tercero and a.tercero_id=f.tercero_id)
						JOIN planes as g ON(a.plan_id=g.plan_id)
						JOIN pacientes as h ON(a.tipo_id_paciente=h.tipo_id_paciente and a.paciente_id=h.paciente_id)
						LEFT JOIN voucher_honorarios_nc as i ON(a.empresa_id=i.empresa_id and a.prefijo=i.prefijo_voucher and a.numero=i.numero_voucher and i.estado='1')
						LEFT JOIN voucher_honorarios_nd as j ON(a.empresa_id=j.empresa_id and a.prefijo=j.prefijo_voucher and a.numero=j.numero_voucher and j.estado='1')
						LEFT JOIN cuentas_cargos_qx_procedimientos as k ON(a.transaccion=k.transaccion)
						LEFT JOIN voucher_honorarios_facturas_profesionales as l ON (a.empresa_id=l.empresa_id AND a.prefijo=l.prefijo AND a.numero=l.numero)
						LEFT JOIN voucher_honorarios_cuentas_x_pagar as m ON (l.empresa_id=m.empresa_id AND l.prefijo_cxp=m.prefijo AND l.numero_cxp=m.numero)
						WHERE a.tipo_id_profesional='$tipo_id_profesional'
						AND a.profesional_id='$profesional_id'
						AND date(a.fecha_registro)>='$fecha_ini'
						AND date(a.fecha_registro)<='$fecha_fin'
						AND a.valor_real > 0
						AND a.estado='1'";
		
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado=$dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
	
		if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "HonorariosMedicos - MostrarHonorarios - SQL ERROR 2";
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

	function ProcesarSqlConteo($sqlCont)
	{
		$this->paginaActual = 1;
		$this->offset = 0;
		$this->limit=20;
		
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
	
}//fin de la clase

?>
