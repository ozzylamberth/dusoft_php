<?php

/***************
 * $Id: app_Os_Listas_Trabajo_Administracion_user.php,v 1.0 2006/03/14 21:59:58 luis Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @autor luis alejandor vargas
 * @package IPSOFT-SIIS
 *
 * Modulo para la administracion de listas de trabajo
 ***************/

class app_Os_Listas_Trabajo_Administracion_user extends classModulo
{
	function app_Os_Listas_Trabajo_Administracion_user()
	{
		$this->limit=GetLimitBrowser();
  		return true;
	}
	
	/***
	* Funcion donde se llama la funcion Menu Principal
	* @return boolean
	*/
	function main()
	{	
		$this->BuscarPermisos();
		return true;
	}
	
	/***
	* Funcion para obtener los permisos del departamento que el usuario administra
	* @return array
	*/
	function BuscarPermisos()
	{
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		
		$query="SELECT DISTINCT e.empresa_id,e.razon_social as emp,c.centro_utilidad,c.descripcion as centro, u.unidad_funcional,u.descripcion as unidad,d.departamento, d.descripcion as dpto
			FROM	userpermisos_os_listatra_administradores_apoyod uadm,system_usuarios_departamentos sud, departamentos d, empresas e,centros_utilidad c, unidades_funcionales u
			WHERE	d.departamento=sud.departamento
			AND 	d.centro_utilidad=c.centro_utilidad
      AND 	d.empresa_id=c.empresa_id
			AND 	d.empresa_id=e.empresa_id
			AND 	d.unidad_funcional=u.unidad_funcional
			AND 	uadm.usuario_id=sud.usuario_id
			AND 	uadm.departamento=sud.departamento
			AND 	uadm.usuario_id=".UserGetUID()."
			AND 	uadm.sw_estado='0'
			AND 	uadm.departamento IN 
						(
							SELECT  DISTINCT d.departamento
							FROM	system_usuarios_departamentos sud, departamentos d,
								empresas e,centros_utilidad c, unidades_funcionales u
							WHERE	d.departamento=sud.departamento
							AND 	d.centro_utilidad=c.centro_utilidad
							AND 	d.empresa_id=e.empresa_id
							AND 	d.unidad_funcional=u.unidad_funcional
							AND 	sud.usuario_id=".UserGetUID()."
						)";

		$ADODB_FETCH_MODE = ADODB_FETCH_ROW;
		$resultado = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
               		$this->error = "Os_Listas_Trabajo_Administracion - BuscarPermisos - SQL ERROR 1";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
     		}
		
		while($data = $resultado->FetchRow())
		{
				$trabajos[$data['emp']][$data['centro']][$data['unidad']][$data['dpto']]=$data;
		}
		
		while(!$resultado->EOF)
		{
				$var[]=$resultado->FetchRow();
				$resulta->MoveNext();
		}
		$_SESSION['datos']=$var;
		
		$resultado->Close();
		
		$mtz[0]="EMPRESA";
		$mtz[1]="CENTRO UTILIDAD";
		$mtz[2]="UNIDAD FUNCIONAL";
		$mtz[3]="DEPARTAMENTO";
	
		$url[0]='app';
		$url[1]='Os_Listas_Trabajo_Administracion';
		$url[2]='user';
		$url[3]='Inicio';
		$url[4]='administracion';
	 	
		$this->salida .= gui_theme_menu_acceso("ADMINISTRACION DE LISTAS DE TRABAJO",$mtz,$trabajos,$url);
		
		return true;
	}
	
	/***
	* Funcion para obtener los datos de las listas de trabajo que el usuario administra
	* @return array
	*/
	function VerListasTrabajos($departamento,$lista,$criterio)
	{
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		
		switch($criterio)
		{
			case 1:
				$condicion=" AND lt.tipo_os_lista_id=$lista";
			break;
			
			case 2:
				$condicion=" AND lt.nombre_lista like '%".strtoupper($lista)."%'
						OR lt.nombre_lista like '%".strtolower($lista)."%'";
			break;
		}
	
		$SqlConteo="	SELECT COUNT(*)
				FROM tipos_os_listas_trabajo lt, departamentos d 
				WHERE lt.departamento=d.departamento
				AND lt.departamento = '".$_SESSION['departamento']."'
				$condicion";
		
		$this->ProcesarSqlConteo($SqlConteo);

		$query="SELECT lt.tipo_os_lista_id,lt.nombre_lista, d.descripcion,lt.sw_consulta_examen_sin_firmar
			FROM tipos_os_listas_trabajo lt, departamentos d 
			WHERE lt.departamento=d.departamento
			AND lt.departamento = '".$_SESSION['departamento']."' 
			$condicion
			ORDER BY lt.nombre_lista";
		
		$ADODB_FETCH_MODE = ADODB_FETCH_ROW;
		$resultado = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
               		$this->error = "Os_Listas_Trabajo_Administracion - VerListasTrabajos - SQL ERROR 2";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
     		}
		
		while($res1=$resultado->FetchRow())
		{
			$filas[]=$res1;
		}
		
		$resultado->Close();
		
		return $filas;
	}
	/***
	* Funcion para obtener los datos del tipo cargo y grupo tipo cargo que el usuario administra
	* @return array
	*/
	
	
	function EliminarLista()
	{
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		
		$lista=$_REQUEST['lista'];
		
		$grupo_tipo_cargo=$this->ListaTrabajoDetalle($lista[0],'grupo_tipo_cargo');
		$tipo_cargo=$this->ListaTrabajoDetalle($lista[0],'tipo_cargo');
		
		$ADODB_FETCH_MODE = ADODB_FETCH_ROW;
		
		foreach($grupo_tipo_cargo as $gtp)
		{
			foreach($tipo_cargo as $tp)
			{
				$query="DELETE
					FROM tipos_os_listas_trabajo_detalle
					WHERE tipo_os_lista_id=$lista[0]
					AND tipo_cargo='$tp[0]'
					AND grupo_tipo_cargo='$gtp[0]'";
					
				$resultado = $dbconn->Execute($query);	
			}
		}
		
		for($i=0;$i<3;$i++)
		{	
			switch($i)
			{
				case 0:	
					$query="DELETE
						FROM userpermisos_os_lista_trabajo_detalle
						WHERE tipo_os_lista_id=$lista[0]";
					
					$resultado = $dbconn->Execute($query);
				break;
				case 1:
					$query="DELETE
						FROM userpermisos_os_listas_trabajo_apoyod_detalle
						WHERE tipo_os_lista_id=$lista[0]";
					
					$resultado = $dbconn->Execute($query);	
				break;
				case 2:
					$query="DELETE
						FROM user_permisos_os_listatra_apoyod_detalle_profesionales
						WHERE tipo_os_lista_id=$lista[0]";
					
					$resultado = $dbconn->Execute($query);
				break;
			}	
		}
		
		
		$query="DELETE
			FROM tipos_os_listas_trabajo
			WHERE tipo_os_lista_id=$lista[0]";
			
		$resultado = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
               		$this->error = "Os_Listas_Trabajo_Administracion - EliminarLista - SQL ERROR 2_1";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
     		}

		$resultado->Close();
		
		$this->FormaVerListasTrabajos();
		
		return true;
	}
	
	function ListaTrabajoDetalle($lista,$mod)
	{
	
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		
		if($mod=='tipo_cargo')
		{
			$query="SELECT tipo_cargo
			FROM tipos_os_listas_trabajo_detalle
			WHERE tipo_os_lista_id=$lista";
		}
		
		if($mod=='grupo_tipo_cargo')
		{
			$query="SELECT grupo_tipo_cargo
			FROM tipos_os_listas_trabajo_detalle
			WHERE tipo_os_lista_id=$lista";
		}
		
		$ADODB_FETCH_MODE = ADODB_FETCH_ROW;
		$resultado = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
               		$this->error = "Os_Listas_Trabajo_Administracion - ListaTrabajoDetalle - SQL ERROR 3";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
     		}
		
		while($res1=$resultado->FetchRow())
		{
			$filas[]=$res1;
		}
		
		$resultado->Close();
		
		return $filas;
	}
	/***
	* Funcion para crear las listas de trabajo
	* @return boolean
	*/
	function CrearListaTrabajo()
	{
		$filas=$this->CalcularNumeroLista();
		
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		
		$query="INSERT INTO tipos_os_listas_trabajo VALUES($filas,'".strtoupper($_REQUEST['nombre'])."','".$_REQUEST['departamento']."','".$_REQUEST['sw_examen']."')";
		
		$ADODB_FETCH_MODE = ADODB_FETCH_ROW;
		$resultado = $dbconn->Execute($query);
		
		$tipo_cargo=$_REQUEST['tipo_cargo'];

		for($i=0;$i<sizeof($tipo_cargo);$i++)
		{
			$query1="INSERT INTO tipos_os_listas_trabajo_detalle VALUES($filas,'".$tipo_cargo[$i]."','".$_REQUEST['grupo_tipo_cargo']."')";
		
			$resultado = $dbconn->Execute($query1);
		}

		if ($dbconn->ErrorNo() != 0)
		{
               		$this->error = "Os_Listas_Trabajo_Administracion - CrearListaTrabajo - SQL ERROR 4";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
     		}
		else
		{
			$this->mensaje('SE HA INSERTADO UN NUEVO REGISTRO EXITOSAMENTE');
		}
		
		$resultado->Close();
		
		return true;
	}
	
	function CalcularNumeroLista()
	{
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		
		$sql="SELECT max(tipo_os_lista_id)
		FROM tipos_os_listas_trabajo";
		
		$ADODB_FETCH_MODE = ADODB_FETCH_ROW;
		$resultado = $dbconn->Execute($sql);
		
		while($res1=$resultado->FetchRow())
			$filas=$res1[0]+1;
	
		$resultado->Close();
		
		return $filas;
	}
	
	/***
	* Funcion para editar las listas de trabajo
	* @return boolean
	*/
	
	function EditarListaTrabajo()
	{
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		
		//print_r($_REQUEST);
		
		$query="UPDATE tipos_os_listas_trabajo 
		SET nombre_lista='".$_REQUEST['nombre']."',departamento='".$_REQUEST['departamento']."',sw_consulta_examen_sin_firmar='".$_REQUEST['sw_examen']."' WHERE tipo_os_lista_id=".$_REQUEST['nlista'];
		
		$ADODB_FETCH_MODE = ADODB_FETCH_ROW;
		$resultado = $dbconn->Execute($query);
		
		//$tipo_cargo=$_REQUEST['tipo_cargo'];
		
		//print_r($tipo_cargo);

		/*for($i=0;$i<sizeof($tipo_cargo);$i++)
		{
			echo $query1="UPDATE tipos_os_listas_trabajo_detalle SET tipo_cargo='".$tipo_cargo[$i]."', grupo_tipo_cargo='".$_REQUEST['grupo_tipo_cargo']."' WHERE tipo_os_lista_id=".$_REQUEST['nlista'];
		
			$resultado = $dbconn->Execute($query1);
		}*/
		
		if ($dbconn->ErrorNo() != 0)
		{
               		$this->error = "Os_Listas_Trabajo_Administracion - EditarListaTrabajo - SQL ERROR 5";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
     		}
		else
		{
			$this->mensaje('SE HA ACTUALIZADO EL REGISTRO EXITOSAMENTE');
		}
		
		$resultado->Close();
		
		return true;
	}
	
	/***
	* Funcion para ingresar un adminstrador a un departamento
	* @return boolean
	*/
	
	function UsuarioAdmin()
	{
		global $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		
		$usuario=$_REQUEST['datos'];
		$departamento=$_REQUEST['departamento'];
		$sw_estado=$_REQUEST['sw_estado'];
		
		$usuario_ex=$this->BuscarUsuarioAdmin($usuario[0],$departamento);
		
		if(!empty($usuario_ex))
		{
			$query="UPDATE userpermisos_os_listatra_administradores_apoyod 
				SET sw_estado='$sw_estado' 
				WHERE usuario_id=$usuario[0] 
				AND departamento='$departamento'";
		}
		else
		{
			$query="INSERT INTO userpermisos_os_listatra_administradores_apoyod 
				VALUES($usuario[0],'$departamento','$sw_estado')";
		}
		
		$ADODB_FETCH_MODE = ADODB_FETCH_ROW;
		$resultado=$dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "Os_Listas_Trabajo_Administracion - UsuarioAdmin - SQL ERROR 6";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
		}
		
		$this->FormaPermisosUsuariosListas();
		
		$resultado->Close();
		
		return true;
		
	}
	
	/***
	* Funcion que busca si el usuario administra un departamento para las listas de trabajo
	* @param int id del usuario
	* @return array
	*/
	
	function BuscarUsuarioAdmin($usuario,$departamento)
	{
		global $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		
		$query="SELECT *
			FROM userpermisos_os_listatra_administradores_apoyod
			WHERE usuario_id=$usuario 
			AND departamento='$departamento'";
	
		$ADODB_FETCH_MODE = ADODB_FETCH_ROW;
		$resultado=$dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "Os_Listas_Trabajo_Administracion - BuscarUsuarioAdmin - SQL ERROR 6_1";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
		}
	
		while($res=$resultado->FetchRow())
			$filas[]=$res;
		
		$resultado->Close();
		
		return $filas;
	}
	
	/***
	* Funcion para obtener los datos de los usuarios que pertencen a una lista de trabajo
	* @return array
	*/
	
	function UsuariosListaTrabajo($lista_id,$departamento)
	{
		global $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		
		$query="SELECT 	DISTINCT usuario_id,usuario,nombre,descripcion
			FROM 	system_usuarios
			WHERE	usuario_id IN
			(

				SELECT 	su.usuario_id
				FROM 	userpermisos_os_lista_trabajo_detalle upd, system_usuarios su, departamentos d
				WHERE 	su.usuario_id=upd.usuario_id 
				AND 	upd.tipo_os_lista_id=$lista_id
				AND 	d.departamento=upd.departamento
				AND 	d.descripcion='$departamento'
			)
			OR	usuario_id IN 
			(
	
				SELECT 	su.usuario_id
				FROM 	userpermisos_os_listas_trabajo_apoyod_detalle upapo, system_usuarios su, departamentos d 
				WHERE 	su.usuario_id=upapo.usuario_id 
				AND 	upapo.tipo_os_lista_id=$lista_id 
				AND 	d.departamento=upapo.departamento
				AND 	d.descripcion='$departamento'
			)
			OR	usuario_id IN 
			(

				SELECT 	su.usuario_id
				FROM 	user_permisos_os_listatra_apoyod_detalle_profesionales upapopro, system_usuarios su, departamentos d 
				WHERE 	su.usuario_id=upapopro.usuario_id 
				AND	upapopro.tipo_os_lista_id=$lista_id 
				AND 	d.departamento=upapopro.departamento
				AND 	d.descripcion='$departamento'
			)
			ORDER BY usuario";
	
		
		$ADODB_FETCH_MODE = ADODB_FETCH_ROW;
		$resultado=$dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "Os_Listas_Trabajo_Administracion - UsuariosListaTrabajo - SQL ERROR 7";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
		}
	
		while($res=$resultado->FetchRow())
			$filas[]=$res;
		
		$resultado->Close();
		
		return $filas;
	}
	
	/*function DepartamentosListaTrabajo()
	{
		global $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		
		$query="SELECT departamento,descripcion 
			FROM departamentos
			WHERE empresa_id='01'
			AND centro_utilidad='01'
			ORDER BY descripcion";
     
		$ADODB_FETCH_MODE = ADODB_FETCH_ROW;
		$resultado=$dbconn->Execute($query);
   
		if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "Os_Listas_Trabajo_Administracion - DepartamentosListaTrabajo - SQL ERROR 6";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
		}

		while($res1=$resultado->FetchRow())
			$filas[]=$res1;
		
		
		$resultado->Close();
		
		return $filas;
	}*/
	
	/***
	* Funcion que lista los usuarios del sistema que no estan registrados en el 
	* departamento para asignarle las listas
	* @return array
	*/	

	function ListarUsuarios($departamento,$datos,$criterio)
	{
		global $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();

		if(!empty($_REQUEST['buscar']))
		{
			switch($criterio)
			{
				case 1:
					$condicion=" AND B.usuario_id = $datos";
				break;
				case 2:
					$condicion=" AND ( B.usuario like '%".strtoupper($datos)."%'
							OR B.usuario like '%".strtolower($datos)."%')";
				break;
				case 3:
					$condicion=" AND B.nombre like '%".strtoupper($datos)."%'
							OR B.nombre like '%".strtolower($datos)."%'";
				break;
			}
			
			$SqlConteo="	SELECT COUNT(*)
					FROM 
					(
						SELECT usuario_id,usuario,nombre,descripcion,activo
						FROM system_usuarios
						WHERE usuario_id <> 
						ALL
						(
							SELECT A.usuario_id
							FROM
							(
								SELECT su.usuario_id,su.usuario,su.nombre,su.descripcion
								FROM system_usuarios su, system_usuarios_departamentos d 
								WHERE  d.usuario_id=su.usuario_id
								AND d.departamento='$departamento'
								AND su.activo='1' 
							) AS A
						)
						AND activo='1'
					)AS B
					WHERE B.activo='1'
					$condicion";
				
			$this->ProcesarSqlConteo($SqlConteo);
			
			
			$query="SELECT B.*
				FROM 
				(
					SELECT usuario_id,usuario,nombre,descripcion,activo
					FROM system_usuarios
					WHERE usuario_id <> 
					ALL
					(
						SELECT A.usuario_id
						FROM
						(
							SELECT su.usuario_id,su.usuario,su.nombre,su.descripcion
							FROM system_usuarios su, system_usuarios_departamentos d
							WHERE  d.usuario_id=su.usuario_id
							AND d.departamento='$departamento'
							AND su.activo='1' 
						) AS A
					)
					AND activo='1'
					ORDER BY usuario
				)AS B
				WHERE B.activo='1'
				$condicion
				ORDER BY B.usuario";
		}
		else
		{
			$SqlConteo="	SELECT COUNT(*)
					FROM system_usuarios
					WHERE usuario_id <>
					ALL(
						SELECT A.usuario_id
						FROM
						(
							SELECT su.usuario_id,su.usuario,su.nombre,su.descripcion
							FROM system_usuarios su, system_usuarios_departamentos d
							WHERE  d.usuario_id=su.usuario_id
							AND d.departamento='$departamento'
							AND su.activo='1' 
						) AS A
					)
					AND activo='1'";
			
			$this->ProcesarSqlConteo($SqlConteo);
			
			$query="SELECT usuario_id,usuario,nombre,descripcion
				FROM system_usuarios
				WHERE usuario_id <> 
				ALL
				(
					SELECT A.usuario_id
					FROM
					(
						SELECT su.usuario_id,su.usuario,su.nombre,su.descripcion
						FROM system_usuarios su, system_usuarios_departamentos d
						WHERE  d.usuario_id=su.usuario_id
						AND d.departamento='$departamento'
						AND su.activo='1' 
					) AS A
				)
				AND activo='1'
				ORDER BY usuario
				LIMIT ".$this->limit ." OFFSET ".$this->offset;
		}
			
		$ADODB_FETCH_MODE = ADODB_FETCH_ROW;
		$resultado=$dbconn->Execute($query);
   
		if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "Os_Listas_Trabajo_Administracion - ListarUsuarios - SQL ERROR 8";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
		}
		
		while($res1=$resultado->FetchRow())
			$filas[]=$res1;
		
		
		$resultado->Close();
		
		return $filas;
	
	}
	
	/***
	* Funcion que trae el grupo tipo cargo de la lista
	* @return array
	*/
	
	function GruposTiposCargo()
	{
		global $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		
		$query="SELECT grupo_tipo_cargo,descripcion
			FROM grupos_tipos_cargo
			ORDER BY descripcion";
			
		$ADODB_FETCH_MODE = ADODB_FETCH_ROW;
		$resultado=$dbconn->Execute($query);
   
		if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "Os_Listas_Trabajo_Administracion - GruposTiposCargo - SQL ERROR 9";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
		}
		
		while($res1=$resultado->FetchRow())
			$filas[]=$res1;
		
		
		$resultado->Close();
		
		return $filas;
	}
	/***
	* Funcion que trae los tipos cargo de la lista
	* @param String grupo tipo cargo
	* @return array
	*/
	function TiposCargo($grupotipocargo)
	{
		global $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
	
		
		$query="SELECT tipo_cargo,descripcion
			FROM tipos_cargos
			WHERE grupo_tipo_cargo like '$grupotipocargo'
			ORDER BY descripcion";
			
		$ADODB_FETCH_MODE = ADODB_FETCH_ROW;
		$resultado=$dbconn->Execute($query);
   
		if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "Os_Listas_Trabajo_Administracion - TiposCargo - SQL ERROR 10";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
		}
		
		while($res1=$resultado->FetchRow())
			$filas[]=$res1;		
			
		$resultado->Close();
		
		return $filas;
	}
	
	/***
	* Funcion que adiciona un usuario al departamento correspondiente
	* @return boolean
	*/
	
	function AdicionarUsuario()
	{
		global $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		
		$datos=$_REQUEST['datos'];
		$departamento=$_REQUEST['departamento'];
		$usuario_id=$datos[0];
		
		$query="INSERT INTO system_usuarios_departamentos VALUES($usuario_id,'$departamento')";
		
		$ADODB_FETCH_MODE = ADODB_FETCH_ROW;
		$resultado=$dbconn->Execute($query);
   
		if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "Os_Listas_Trabajo_Administracion - AdicionarUsuario - SQL ERROR 11";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
		}
		
		$resultado->Close();
		
		$this->FormaPermisosUsuariosListas($usuario_id);
		
		return true;
	}
	
	/***
	* Funcion que elimina un usuario en el departamento correspondiente
	* @return boolean
	*/
	
	function EliminarUsuario()
	{
		global $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		
		$datos=$_REQUEST['datos'];
		$departamento=$_REQUEST['departamento'];
		$usuario_id=$datos[0];
		
		if($_REQUEST['Eliminar'])
		{
			$query="DELETE 
				FROM system_usuarios_departamentos
				WHERE usuario_id=$usuario_id 
				AND departamento='$departamento'";
					
			$ADODB_FETCH_MODE = ADODB_FETCH_ROW;
			$resultado=$dbconn->Execute($query);
	
			if ($dbconn->ErrorNo() != 0) 
			{
				$this->error = "Os_Listas_Trabajo_Administracion - EliminarUsuario - SQL ERROR 12";
				$this->mensajeDeError = $dbconn->ErrorMsg();
				return false;
			}
			
			$resultado->Close();
			
			$this->FormaPermisosUsuariosListas();
		}

		return true;	
	}

	/***
	* Funcion que busca los usuario del departamento
	* @return array
	*/
	
	function PermisosUsuariosListas($departamento,$datos,$criterio)
	{
		global $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		
		$condicion="";
		
		if(!empty($datos))
		{
			switch($criterio)
			{
				case 1:
					$condicion=" AND su.usuario_id = $datos";
				break;
				case 2:
					$condicion=" AND ( su.usuario like '%".strtoupper($datos)."%'
							OR su.usuario like '%".strtolower($datos)."%')";
				break;
				case 3:
					$condicion=" AND su.nombre like '%".strtoupper($datos)."%'
							OR su.nombre like '%".strtolower($datos)."%'";
				break;
			}
		}
		
		$SqlConteo="SELECT COUNT(*)
				FROM
				(
					SELECT su.usuario_id,su.usuario,su.nombre,su.descripcion
					FROM system_usuarios su, system_usuarios_departamentos d
					WHERE  d.usuario_id=su.usuario_id
					AND d.departamento='$departamento' 
					AND su.activo='1' 
					$condicion
				) AS A";
		
		$this->ProcesarSqlConteo($SqlConteo);
		
		$query="SELECT A.*
			FROM
			(
				SELECT su.usuario_id,su.usuario,su.nombre,su.descripcion
				FROM system_usuarios su, system_usuarios_departamentos d
				WHERE  d.usuario_id=su.usuario_id
				AND d.departamento='$departamento' 
				AND su.activo='1' 
				$condicion
			) AS A
			ORDER BY A.usuario
			LIMIT ".$this->limit ." OFFSET ".$this->offset;
		
		$ADODB_FETCH_MODE = ADODB_FETCH_ROW;
		$resultado=$dbconn->Execute($query);
   
		if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "Os_Listas_Trabajo_Administracion - PermisosUsuariosListas - SQL ERROR 13";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
		}	
		
		
		
		while($res=$resultado->FetchRow())
			$filas[]=$res;
		
		$resultado->Close();
		
		return $filas;
	}
	
	/***
	* Funcion que trae las listas del departamento correspondiente
	* @return array
	*/
	
	function ListasTrabajosPorDepartamento($departamento)
	{
		global $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
			
		$query="SELECT lt.tipo_os_lista_id,lt.nombre_lista,d.descripcion
			FROM tipos_os_listas_trabajo lt, departamentos d
			WHERE lt.departamento=d.departamento 
			AND d.departamento='$departamento'
			ORDER BY lt.tipo_os_lista_id";
			
		$ADODB_FETCH_MODE = ADODB_FETCH_ROW;
		$resultado=$dbconn->Execute($query);
   
		if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "Os_Listas_Trabajo_Administracion - ListasTrabajosPorDepartamento - SQL ERROR 14";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
		}
		
		while($res1=$resultado->FetchRow())
			$filas[]=$res1;
		
		
		$resultado->Close();
		
		return $filas;
	}
	
	/***
	* Funcion que trae el departamento de un usuario
	* @return array
	*/
	
	/*function DepartamentosUsuarios($usuario_id,$departamento)
	{
		global $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		
		$query="SELECT sud.departamento,dept.descripcion,e.razon_social
			FROM system_usuarios_departamentos sud, departamentos dept, empresas e 
			WHERE dept.centro_utilidad='01'
			AND dept.departamento=sud.departamento
			AND dept.empresa_id=e.empresa_id
			AND sud.usuario_id=$usuario_id 
			AND sud.departamento='$departamento'
			ORDER BY dept.departamento";
     
		$ADODB_FETCH_MODE = ADODB_FETCH_ROW;
		$resultado=$dbconn->Execute($query);
   
		if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "Os_Listas_Trabajo_Administracion - DepartamentosUsuarios - SQL ERROR 11";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
		}

		while($res1=$resultado->FetchRow())
			$filas[]=$res1;
		
		
		$resultado->Close();
		
		return $filas;
	}*/

	/***
	* Funcion que asigna los permisos de un usuario a las listas de trabajo
	* @return boolean
	*/
	
	function AsignacionPermisosListas()
	{
		//print_r($_REQUEST);
		
		$tipo=$_REQUEST['tp'];

		$num_deptos=$_REQUEST['num_deptos'];
		
		$tercero_id=$_REQUEST['tercero_id'];
		
		$tipo_id_tercero=$_REQUEST['tipo_id_tercero'];
		
		$usuario_id=$_REQUEST['usuario_id'];
		
		$num_listas=$_REQUEST['num_listas'];
		
		$departamento=$_REQUEST['departamento'];
		
		/*$_SESSION[$tipo]['NUM_DEPTOS']=$num_deptos;
		$_SESSION[$tipo]['TERCERO_ID']=$tercero_id;
		$_SESSION[$tipo]['TIPO_ID_TERCERO']=$tipo_id_tercero;
		$_SESSION[$tipo]['USUARIO_ID']=$usuario_id;
		$_SESSION[$tipo]['NUM_LISTAS']=$num_listas;
		$_SESSION[$tipo]['DEPARTAMENTO']=$departamento;*/
		
		if(!empty($_REQUEST['guardar']))
		{
			for($i=0;$i<$num_deptos;$i++)
			{
				$tipo_presentacion[$i]=$_REQUEST['tipo_presentacion'.$i];
				//$_SESSION[$tipo]['TIPO_PRESENTACION'][$i]=$tipo_presentacion[$i];
				//$sw[$i]=$_REQUEST['sw_mostrar_listas'.$i];
				
				$sw[$i]=NULL;
				
				for($j=0;$j<$num_listas[$i];$j++)
				{
					$tipo_lista_id[$i][$j]=$_REQUEST['listas_id'.$i."".$j];
					//$_SESSION[$tipo][$i][$j]=$tipo_lista_id[$i][$j];
				}
			}
		
				
			for($i=0;$i < $num_deptos;$i++)
			{	
				$usuario=$this->BuscarUsuarioExistente($usuario_id,$tipo_id_tercero,$tercero_id,$departamento[$i],$tipo);	
	
				if(!empty($usuario))
				{
	
					$this->ActualizarUserPermisos($usuario_id,$departamento[$i],$sw[$i],$tipo_presentacion[$i],$tipo_id_tercero,$tercero_id,$tipo);
	
					$listas_sel=$this->BuscarPermisosListas($usuario_id,$departamento[$i],$tipo);	
					
					if(!empty($listas_sel))
					{
						foreach($listas_sel as $listas)
						{
							$bandera=0;
							
							for($j=0;$j< $num_listas[$i];$j++)
							{
								
								if($listas[0]==$tipo_lista_id[$i][$j])
								{
									
									$tipo_lista_id[$i][$j]="";
									$bandera=1;
									break;
								}	
							}
	
							if($bandera==0)
							{
									
								$this->EliminarUserListas($usuario_id,$tipo_id_tercero,$tercero_id,$departamento[$i],$listas[0],$tipo);
	
							}		
						}
					
						for($j=0;$j< $num_listas[$i];$j++)
						{	
							if(!empty($tipo_lista_id[$i][$j]))
							{
								$this->InsertarUserListas($usuario_id,$tipo_id_tercero,$tercero_id,$departamento[$i],$tipo_lista_id[$i][$j],$tipo);
	
							}
						}
					}
					else{	
						for($j=0;$j< $num_listas[$i];$j++)
						{	
							if(!empty($tipo_lista_id[$i][$j]))
							{
								
								$this->InsertarUserListas($usuario_id,$tipo_id_tercero,$tercero_id,$departamento[$i],$tipo_lista_id[$i][$j],$tipo);
							}
						}
					}
						
				}	
				else
				{
					if(!empty($departamento[$i]))
					{
						$this->InsertarUserPermisos($usuario_id,$departamento[$i],$sw[$i],$tipo_presentacion[$i],$tipo_id_tercero,$tercero_id,$tipo);
							
						for($j=0;$j< $num_listas[$i];$j++)
						{	
							if(!empty($tipo_lista_id[$i][$j]))
							{
								$this->InsertarUserListas($usuario_id,$tipo_id_tercero,$tercero_id,$departamento[$i],$tipo_lista_id[$i][$j],$tipo);	
							}
						}
					}
				}
			}		
		}
		
		$usuario=$_REQUEST['usuario'];
		$tipo=$_REQUEST['tp'];
		$llamado=$_REQUEST['llamado'];
		
		$this->FormaAsignacionPermisosListas($usuario,$tipo,$llamado);

		return true;
	}
	
	/***
	* Funcion que actualiza los permisos de un usuario
	* @return boolean
	*/
	function ActualizarUserPermisos($usuario_id,$departamento,$sw,$tipo_presentacion,$tipo_id_tercero,$tercero_id,$tipo)
	{
		global $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		
		switch($tipo)
		{
			case 'tomado':
				$query="UPDATE userpermisos_os_lista_trabajo 
					SET sw_mostrar_listas='$sw' 
					WHERE usuario_id=$usuario_id 
					AND departamento='$departamento'";
			break;
			case 'transcripcion':
				$query="UPDATE userpermisos_os_listas_trabajo_apoyod 
					SET sw_todos='$sw',tipo_presentacion='$tipo_presentacion' 
					WHERE usuario_id=$usuario_id 
					AND departamento='$departamento'";
			
			break;
			case 'firmado':
				$query="UPDATE user_permisos_os_listatra_apoyod_profesionales 
					SET sw_todos='$sw',tipo_presentacion='$tipo_presentacion' 
					WHERE usuario_id=$usuario_id
					AND tipo_id_tercero='$tipo_id_tercero'
					AND tercero_id='$tercero_id'
					AND departamento='$departamento'";
			break;
		}
		
		$ADODB_FETCH_MODE = ADODB_FETCH_ROW;
		$resultado=$dbconn->Execute($query);
		
																if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "Os_Listas_Trabajo_Administracion - ActualizarUserPermisos - SQL ERROR";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
		}
		
		$resultado->Close();
		
		return true;
	}
	
	/***
	* Funcion que inserta permisos a un usuario
	* @return boolean
	*/
	
	function InsertarUserPermisos($usuario_id,$departamento,$sw,$tipo_presentacion,$tipo_id_tercero,$tercero_id,$tipo)
	{
		
		global $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		
		switch($tipo)
		{
			case 'tomado':
				
				$query="INSERT INTO userpermisos_os_lista_trabajo VALUES($usuario_id,'$departamento','$sw')";
			
			break;
			case 'transcripcion':
				
				$query="INSERT INTO userpermisos_os_listas_trabajo_apoyod  VALUES($usuario_id,'$departamento','$sw','$tipo_presentacion')";
			
			break;
			case 'firmado':
				
				$query="INSERT INTO user_permisos_os_listatra_apoyod_profesionales  VALUES($usuario_id,'$tipo_id_tercero','$tercero_id','$departamento','$sw','$tipo_presentacion')";
			
			break;
		}
					
		$ADODB_FETCH_MODE = ADODB_FETCH_ROW;
		$resultado=$dbconn->Execute($query);
			
		if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "Os_Listas_Trabajo_Administracion - InsertarUserPermisos - SQL ERROR";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
		}
		
		$resultado->Close();
		
		return true;
	}

	/***
	* Funcion que inserta las listas de un usuario
	* @return boolean
	*/
	function InsertarUserListas($usuario_id,$tipo_id_tercero,$tercero_id,$departamento,$lista_id,$tipo)
	{
		global $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		
		switch($tipo)
		{
			case 'tomado':
				
				$query="INSERT INTO userpermisos_os_lista_trabajo_detalle VALUES($usuario_id,'$departamento',$lista_id)";
			
			break;
			case 'transcripcion':
				
				$query="INSERT INTO userpermisos_os_listas_trabajo_apoyod_detalle VALUES($usuario_id,'$departamento',$lista_id)";
			
			break;
			case 'firmado':
				
				$query="INSERT INTO user_permisos_os_listatra_apoyod_detalle_profesionales VALUES($usuario_id,'$tipo_id_tercero','$tercero_id','$departamento',$lista_id)";
			
			break;
		}
								
		$ADODB_FETCH_MODE = ADODB_FETCH_ROW;
		$resultado=$dbconn->Execute($query);
	
		if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "Os_Listas_Trabajo_Administracion - InsertarUserListas - SQL ERROR";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
		}
		
		$resultado->Close();
		
		return true;
	}
	
	/***
	* Funcion que elimina las listas de un usuario
	* @return boolean
	*/
	function EliminarUserListas($usuario_id,$tipo_id_tercero,$tercero_id,$departamento,$lista_id,$tipo)
	{
		global $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		
		switch($tipo)
		{
			case 'tomado':
				
				$query="DELETE 
					FROM userpermisos_os_lista_trabajo_detalle  
					WHERE usuario_id=$usuario_id 
					AND  departamento='$departamento'
					AND  tipo_os_lista_id=$lista_id";	
			
			break;
			case 'transcripcion':
				
				$query="DELETE 
					FROM userpermisos_os_listas_trabajo_apoyod_detalle  
					WHERE usuario_id=$usuario_id  
					AND  departamento='$departamento' 
					AND  tipo_os_lista_id=$lista_id";	
			
			break;
			case 'firmado':
				
				$query="DELETE 
					FROM user_permisos_os_listatra_apoyod_detalle_profesionales  
					WHERE usuario_id=$usuario_id
					AND tipo_id_tercero='$tipo_id_tercero' 
					AND tercero_id='$tercero_id'
					AND departamento='$departamento' 
					AND tipo_os_lista_id=$lista_id";	
			
			break;
		}
		
		$ADODB_FETCH_MODE = ADODB_FETCH_ROW;
		$resultado=$dbconn->Execute($query);
																if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "Os_Listas_Trabajo_Administracion - EliminarUserListas - SQL ERROR";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
		}
		
		$resultado->Close();
		
		return true;
	}
	
	/***
	* Funcion que busca si el usuario existe en cualquier tipo de permiso(tomado, transcripcion, firmado )
	* @return array
	*/
	function BuscarUsuarioExistente($usuario_id,$tipo_id_tercero,$tercero_id,$departamento,$tipo)
	{
		global $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		
		switch($tipo)
		{
			case 'tomado':
				
				$query="SELECT * 
					FROM userpermisos_os_lista_trabajo
					WHERE usuario_id=$usuario_id 
					AND departamento='$departamento'";
			break;
			case 'transcripcion':
				
				$query="SELECT * 
					FROM userpermisos_os_listas_trabajo_apoyod
					WHERE usuario_id=$usuario_id 
					AND departamento='$departamento'";	
			break;
			case 'firmado':
				
				$query="SELECT * 
					FROM user_permisos_os_listatra_apoyod_profesionales 
					WHERE usuario_id=$usuario_id 
					AND tipo_id_tercero='$tipo_id_tercero' 
					AND tercero_id='$tercero_id' 
					AND departamento='$departamento'";	
			break;
		}
				
		$ADODB_FETCH_MODE = ADODB_FETCH_ROW;
		$resultado=$dbconn->Execute($query);				
		
		if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "Os_Listas_Trabajo_Administracion - BuscarUsuarioExistente - SQL ERROR 15";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
		}
		
		while($res1=$resultado->FetchRow())
			$filas[]=$res1;
		
		$resultado->Close();	
		
		return $filas;
	}
	
	/***
	* Funcion que busca las listas que el usuario tenga permiso
	* @return array
	*/
	function BuscarPermisosListas($usuario_id,$departamento,$tipo)
	{
		global $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		
		if(!empty($departamento))
			$dept=" AND departamento='$departamento'";
					
		switch($tipo)
		{	
			case 'tomado':
				$query="SELECT tipo_os_lista_id 
					FROM  userpermisos_os_lista_trabajo_detalle 
					WHERE usuario_id=$usuario_id $dept 
					ORDER BY tipo_os_lista_id";
			break;
			case 'transcripcion':
				$query="SELECT tipo_os_lista_id 
					FROM  userpermisos_os_listas_trabajo_apoyod_detalle 
					WHERE usuario_id=$usuario_id $dept
					ORDER BY tipo_os_lista_id"; 
		
			break;
			case 'firmado':
				$query="SELECT tipo_os_lista_id,tipo_id_tercero,tercero_id
					FROM  user_permisos_os_listatra_apoyod_detalle_profesionales
					WHERE usuario_id=$usuario_id $dept 
					ORDER BY tipo_os_lista_id";
			break;	
		}
			
		$ADODB_FETCH_MODE = ADODB_FETCH_ROW;
		$resultado=$dbconn->Execute($query);
   
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Os_Listas_Trabajo_Administracion - BuscarPermisosListas - SQL ERROR 16";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
		}

		while($res1=$resultado->FetchRow())
			$filas[]=$res1;
		
		$resultado->Close();
		
		return $filas;
	}
	
	/***
	* Funcion que permite mostrar las listas al usuario
	* @return array
	*/
	
	function MostrarListas($usuario_id,$departamento,$tp)
	{
		global $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		
		switch($tp)
		{	
			case 'tomado':
				$query="SELECT sw_mostrar_listas
					FROM  userpermisos_os_lista_trabajo
					WHERE usuario_id=$usuario_id 
					AND departamento = '$departamento'";
			break;
			case 'transcripcion':
				$query="SELECT sw_todos,tipo_presentacion
					FROM userpermisos_os_listas_trabajo_apoyod
					WHERE usuario_id=$usuario_id 
					AND departamento='$departamento'";
			break;
			case 'firmado':
				$query="SELECT sw_todos,tipo_presentacion,tipo_id_tercero,tercero_id
					FROM user_permisos_os_listatra_apoyod_profesionales
					WHERE usuario_id=$usuario_id 
					AND departamento='$departamento'";
			break;	
		}
     
		$ADODB_FETCH_MODE = ADODB_FETCH_ROW;
		$resultado=$dbconn->Execute($query);
   
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Os_Listas_Trabajo_Administracion - MostrarListas - SQL ERROR 17";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
		}

		while($res1=$resultado->FetchRow())
			$filas[]=$res1;
		
		$resultado->Close();
		
		return $filas;
	}
	
	/***
	* Funcion que busca el tipo de presentacion(normal o agrupada)
	* @return array
	*/
	
	function BuscarTipoPresentacion($usuario_id,$departamento,$tabla)
	{
		global $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		
		$query="SELECT sw_todos,tipo_presentacion
			FROM $tabla
			WHERE usuario_id=$usuario_id 
			AND departamento='$departamento'";
     
		$ADODB_FETCH_MODE = ADODB_FETCH_ROW;
		$resultado=$dbconn->Execute($query);
   
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Os_Listas_Trabajo_Administracion - BuscarTipoPresentacion - SQL ERROR 18";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
		}

		while($res1=$resultado->FetchRow())
			$filas[]=$res1;
		
		$resultado->Close();
		
		return $filas;
	}
	
	/***
	* Funcion que busca si un usuario es profesioanl
	* @param int id del usuario
	* @return array
	*/
	
	function BuscarProfesionales($usuario_id)
	{
		global $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		
		$query="SELECT tercero_id
			FROM profesionales
			WHERE usuario_id=$usuario_id";
     
		$ADODB_FETCH_MODE = ADODB_FETCH_ROW;
		$resultado=$dbconn->Execute($query);
   
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Os_Listas_Trabajo_Administracion - BuscarProfesionales - SQL ERROR 19";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
		}

		while($res1=$resultado->FetchRow())
			$filas=$res1[0];
		
		$resultado->Close();
		
		return $filas;
	}
	
	/***
	* Funcion que trae los datos de los terceros asociados a los profesionales
	* @return array
	***/
	
	function TraerDatosTerceros()
	{
		global $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		
		$query="SELECT tipo_id_tercero
			FROM tipo_id_terceros
			ORDER BY indice_de_orden";
     
		$ADODB_FETCH_MODE = ADODB_FETCH_ROW;
		$resultado=$dbconn->Execute($query);
   
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Os_Listas_Trabajo_Administracion - TraerDatosTerceros - SQL ERROR 20";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
		}

		while($res1=$resultado->FetchRow())
			$filas[]=$res1;
		
		$resultado->Close();
		
		return $filas;
	}
	
	/***
	* Funcion que busca si el usuario administra un departamento para las listas de trabajo
	* @param int id del usuario
	* @return array
	*/
	
	/*function BuscarUsuarioAdministrador($usuario_id)
	{
		global $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		
		$query="SELECT upapoadmin.departamento,d.descripcion,upapoadmin.sw_estado
			FROM userpermisos_os_listatra_administradores_apoyod upapoadmin,departamentos d
			WHERE usuario_id=$usuario_id
			AND upapoadmin.departamento=d.departamento
			ORDER BY d.descripcion";
     
		$ADODB_FETCH_MODE = ADODB_FETCH_ROW;
		$resultado=$dbconn->Execute($query);
   
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Os_Listas_Trabajo_Administracion - BuscarUsuarioAdministrador - SQL ERROR 21";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
		}

		while($res1=$resultado->FetchRow())
		{
			$filas[]=$res1;
		}
		
		$resultado->Close();
		
		return $filas;	
	}*/
	
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
	
	function ObtenerLimite()
	{
		$uid = UserGetUID();
		$this->limit = UserGetVar($uid,'LimitRowsBrowser');
		
		if(empty($this->limit) || is_null($this->limit))
		{
			UserSetVar($uid,'LimitRowsBrowser','20');
			$this->limit = UserGetVar($uid,'LimitRowsBrowser');
		}
		
		return true;
	}

	/*Esta funcion se devuleve al modulo en donde se pueden ver los modulos y los
	* departamentos segun el permiso del usuario.
	*/
/*	function Retornar()
	{
		$this->ReturnMetodoExterno($_SESSION['USER_ADMIN_MOD']['CONTENEDOR'], $_SESSION['USER_ADMIN_MOD']['MOD'], $_SESSION['USER_ADMIN_MOD']['TIPO'], $_SESSION['USER_ADMIN_MOD']['METODO'],array("mod"=>$_SESSION['USER_ADMIN_MOD']['MODULO']));
		return true;
	}

	function RetornarPermisos()
	{
		$this->ReturnMetodoExterno($_SESSION['USER_ADMIN_MOD']['CONTENEDOR'], $_SESSION['USER_ADMIN_MOD']['MOD'], $_SESSION['USER_ADMIN_MOD']['TIPO'], 'TraerDatos',array("tabla"=>'cajas_usuarios',"permiso"=>'CAJA GENERAL'));
		return true;
	}
	*/
}//fin clase admin

?>


