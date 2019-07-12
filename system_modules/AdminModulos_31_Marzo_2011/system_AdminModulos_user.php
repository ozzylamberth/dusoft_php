<?php
	/********************************************************************************* 
 	* $Id: system_AdminModulos_user.php,v 1.20 2007/10/11 20:51:03 hugo Exp $ 
 	* 
 	* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 	* @package IPSOFT-SIIS 
 	* 
	* @author    Hugo F. Manrique. 
	* @version   $Revision: 1.20 $ 
	* @package   AdminModulos 
	* 
	* Modulo que permite el manejo de la informacion de los modulos del sistema 
 	**********************************************************************************/
	$path = GetVarConfigAplication('DIR_SIIS')."classes/nusoap/lib/";
	require_once($path."nusoap.php"); 
	
	class system_AdminModulos_user extends classModulo
	{	
		var $Nusoap;
		
		function system_AdminModulos_user()
		{
			$direccion = "http://".ModuloGetVar('system','AdminModulos','webservices');
			$proxyhost = ModuloGetVar('system','AdminModulos','proxyhost');
			$proxyport = ModuloGetVar('system','AdminModulos','proxyport');
			
			//$this->Nusoap = new soapclient($direccion,true,$proxyhost,$proxyport);
			return true;
		}
		/************************************************************************ 
		* 
		* @return boolean 
		*************************************************************************/
		function main()
		{
			$this->action1 = ModuloGetURL('system','Menu','user','main',array("uid"=>""));
			$this->action2 = ModuloGetURL('system','AdminModulos','user','RegistrarModulo');			
			$this->action3 = ModuloGetURL('system','AdminModulos','user','MostrarModulos');			
			$this->action4 = ModuloGetURL('system','AdminModulos','user','MostrarMenus');
			$this->action5 = ModuloGetURL('system','AdminModulos','user','MostrarSubmodulos');
			unset($_SESSION['cadena']);
			unset($_SESSION['modulos']);
			unset($_SESSION['SqlBuscarModulo']);
			
		//	$this->ObtenerInformacionWebservices();
			
			$this->Menu();

			return true;
		}
		/************************************************************************ 
		* Funcion que permite presentar la interfaz de ingreso del modulo 
		* 
		* @return boolean 
		*************************************************************************/
		function RegistrarModulo()
		{
			($_REQUEST['llamado'])? $this->Llamado = $_REQUEST['llamado'] : $this->Llamado = "0";
			
			switch($this->Llamado)
			{
				case '0':
						$this->actionM = ModuloGetURL('system','AdminModulos','user','main');
				break;
				case '1':
						$this->actionM = ModuloGetURL('system','AdminModulos','user','MostrarModulos',
													   array("offset"=>$_REQUEST['offset'],"buscarModulo"=>$_REQUEST['buscarModulo']));
				break;
			}
		
			$this->VersionModulo = "1.00";
				
			$this->action4 = ModuloGetURL('system','AdminModulos','user','IngresarModuloBD', 
										   array("offset"=>$_REQUEST['offset'],"buscarModulo"=>$_REQUEST['buscarModulo']));			
			$this->FormaRegistrarModulo();
			
			return true;
		}
		/************************************************************************ 
		* Funcion que permite revisar si el modulo que se trata de crear ya 
		* existe. si no existe ingresa el modulo y muestra la forma de ingresar
		* menu 
		* 
		* @return boolean 
		*************************************************************************/
		function IngresarModuloBD()
		{
			$this->Llamado = $_REQUEST['llamado'];
			$this->NombreModulo = $_REQUEST['nombre_modulo'];
			$this->TipoModulo = $_REQUEST['tipo_modulo'];
			$this->DescripcionModulo = $_REQUEST['descripcion'];
			$this->InfoVersionModulo = $_REQUEST['infoVersion'];
			$this->VersionModulo = $_REQUEST['version'];
			$this->EstadoModulo = $_REQUEST['disponibilidad'];
			$this->SwUserModulo = $_REQUEST['funcionU'];
			$this->SwAdminModulo = $_REQUEST['funcionA'];
			
			if($this->NombreModulo == "" )
			{
				$this->frmError["MensajeError"] = "EL NOMBRE DEL MODULO ES OBLIGATORIO";
				$this->RegistrarModulo();
			}
			else if($this->TipoModulo == "0")
			{
				$this->frmError["MensajeError"] = "FALTA INGRERSAR EL TIPO DE MODULO";
				$this->RegistrarModulo();
			}
			else
			{
				list($dbconn) = GetDBconn();
				$query = "SELECT modulo FROM system_modulos WHERE modulo ILIKE '".$this->NombreModulo."' AND modulo_tipo ILIKE '".$_REQUEST['tipoModulo']."'";
				$result=$dbconn->Execute($query);
			
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"] = "Error DB : " . $dbconn->ErrorMsg();
					$this->RegistrarModulo();

					return true;
				}
			
				$i=0;
				while (!$result->EOF)
				{
					$result->MoveNext();
					$i++;
				}
				$result->Close();
				
				if($i > 0)
				{
					$this->frmError["MensajeError"] = "El Modulo : ".$this->NombreModulo." Ya Existe!";
					$this->RegistrarModulo();
					return true;
				}
				else
				{
					$version = explode (".",$this->VersionModulo);
					if(sizeof($version) > 2)
					{
						$this->frmError["MensajeError"] = "El numero de la version es invalido";
						$this->RegistrarModulo();
						return true;
					}
					if(strlen($version[1]) > 2)
					{
						$this->frmError["MensajeError"] = "El numero de versión solo debe tener dos cifras decimales";
						$this->RegistrarModulo();
						return true;
					}
					
					$TipoModulo = $_REQUEST['tipoModulo'];
					
					list($dbconn) = GetDBconn();
					$query  = "INSERT INTO system_modulos 
									  (modulo,
									   modulo_tipo,
									   descripcion,
									   version_numero,
									   version_info,
									   activo,
									   sw_user,
									   sw_admin)";
					$query .= "VALUES ('".$this->NombreModulo."',
					  	   	  '".$this->TipoModulo."',
					   		  '".$this->DescripcionModulo."',
					   		   ".$this->VersionModulo.",
					   		  '".$this->InfoVersionModulo."',
					   		  '".$this->EstadoModulo."',
					   		  '".$this->SwUserModulo."',
					   		  '".$this->SwAdminModulo."');";
					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->frmError["MensajeError"] = "Error DB: ".$dbconn->ErrorMsg();
						$this->RegistrarModulo();
						return true;
					}
					else
					{			
						$result->Close();
						if($_REQUEST['ingresarMenu'] == '1')
						{
							$this->RegistrarMenu();
						}
						else
						{
							switch($this->Llamado)
							{
								case '0':
										$this->action = ModuloGetURL('system','AdminModulos','user','main');
								break;
								case '1':
										$this->action = ModuloGetURL('system','AdminModulos','user','MostrarModulos',
																	   array("offset"=>$_REQUEST['offset'],"buscarModulo"=>$_REQUEST['buscarModulo']));
								break;
							}
							$informacion = "EL MODULO: ".$this->NombreModulo." HA SIDO CREADO";
							$this->FormaInformacion($informacion);
						}
					}
				}
			}
			return true;
		}
		/******************************************************************************* 
		* Funcion para mostrar los modulos la primera vez
		* 
		* @return boolean 
		********************************************************************************/
		function MostrarModulos()
		{
			unset($_SESSION['SqlBuscarModulo']);
			unset($_SESSION['SqlConteoModulos']);
			
			$this->Modulos = $_SESSION['modulos'];
			
			$this->MetodoReto = "MostrarModulos";
			$this->consulta = $this->ObtenerSqlMostrarModulos();
			
			$this->actionBuscador = ModuloGetURL('system','AdminModulos','user','BuscarModulos');
			$this->actionPaginador = ModuloGetURL('system','AdminModulos','user','MostrarModulos');
			$this->action1 = ModuloGetURL('system','AdminModulos','user','main');
			
			$this->actionMoulosInactivos = ModuloGetURL('system','AdminModulos','user','VerMenusInactivos',
														 array("pagina"=>$this->paginaActual,"metodo_retorno"=>$this->MetodoReto));

			$this->actionCrear = ModuloGetURL('system','AdminModulos','user','RegistrarModulo',
											   array("metodo_retorno"=>$this->MetodoReto,
											         "offset"=>$this->paginaActual,"llamado"=>"1"));			
			
			$this->actionVariablesSistema = ModuloGetURL('system','AdminModulos','user','MostrarVariablesModulos',
														  array("pagina"=>$this->paginaActual,
														  		"metodo_retorno"=>$this->MetodoReto,
														  		"nombre_modulo"=>'',"tipo_modulo"=>''));
			$this->FormaMostrarModulos();
			return true;
		}
		/*******************************************************************************  
		* Funcion para mostrar los modulos cuando se hace una busqueda 
		* 
		* @return boolean file:/var/www/html/SIIS/
		********************************************************************************/
		function BuscarModulos()
		{
			$this->MetodoReto = "BuscarModulos";

			if($_REQUEST['nuevaBusqueda'] == '1')
			{
				unset($_SESSION['SqlBuscarModulo']);
				unset($_SESSION['SqlConteoModulos']);
			}
			$this->mensaje = "&nbsp;";

			$this->actionBuscador = ModuloGetURL('system','AdminModulos','user','BuscarModulos');
			$this->actionPaginador = $this->actionBuscador;
			$this->action1 = ModuloGetURL('system','AdminModulos','user','main');
			$this->consulta = $this->ObtenerSqlBuscarModulos();
			$this->actionCrear = ModuloGetURL('system','AdminModulos','user','RegistrarModulo',
											   array("offset"=>$this->paginaActual,"llamado"=>"1",
											   		 "metodo_retorno"=>$this->MetodoReto));			
			$this->actionVariablesSistema = ModuloGetURL('system','AdminModulos','user','MostrarVariablesModulos',
														  array("nombre_modulo"=>'',"tipo_modulo"=>'',
														  		"offset"=>$this->paginaActual,"metodo_retorno"=>$this->MetodoReto));

			$this->FormaMostrarModulos();
			return true;
		}
		/******************************************************************************* 
		* Funcion que ejecuta los sql de buscar modulo y mostrar modulo 
		* 
		* @return array datos de la consulta 
		********************************************************************************/
		function ObtenerModulos()
		{
			list($dbconn) = GetDBconn();
			
			$result=$dbconn->Execute($this->consulta);

			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError["MensajeError"] = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$i=0;
			while (!$result->EOF)
			{
				$datos[$i]=$result->fields[0]."/".$result->fields[1]."/".$result->fields[2]."/".$result->fields[3]."/".$result->fields[4];
				$result->MoveNext();
				$i++;
			}
			$result->Close();
			
			return $datos;
		}
		/**************************************************************************  
		* Funcion que devuelve el sql que muestra los modulos y el conteo de 
		* cuantos modulos hay 
		* 
		*@return string sql para la busqueda 
		****************************************************************************/
		function ObtenerSqlMostrarModulos()
		{		
			$ordenar = "1";
			
			$sqlConteo = "SELECT COUNT(*) FROM system_modulos 
							  WHERE modulo_tipo NOT LIKE '' 
							  AND modulo_tipo NOT LIKE 'hc' ";
			
			$this->ProcesarSqlConteo($sqlConteo);
			
			$sql  = "SELECT modulo,modulo_tipo,descripcion,version_numero,";
			$sql .= "		  CASE WHEN activo = '1' THEN 'ACTIVO'";
			$sql .= "		  	   WHEN activo = '0' THEN 'INACTIVO'";
			$sql .= "		  END AS \"estado\" ";
			$sql .= "FROM system_modulos ";
			$sql .= "WHERE modulo_tipo <> '' AND modulo_tipo NOT LIKE 'hc' ";
			
			if($_REQUEST['criterio'])
			{
				$ordenar = $_REQUEST['criterio'];
			}
			
			$sql .= " ORDER BY ".$ordenar;
			$sql .= " LIMIT ".$this->limit." OFFSET ".$this->offset; 		       		

			return $sql;
		}
		/*************************************************************************** 
		* Funcion que obtiene el sql de busqueda de modulos por tipo y por nombre 
		* moduko 
		* 
		* @return string sql de busqueda 
		*****************************************************************************/
		function ObtenerSqlBuscarModulos()
		{
			if(!$_SESSION['SqlConteoModulos'])
			{
				$sqlConteo = "SELECT COUNT(*) FROM system_modulos 
								  WHERE modulo_tipo <> '' 
								  AND modulo_tipo NOT LIKE 'hc' ";

				if($_REQUEST['cadena_buscar'] != "")
				{
					if($_REQUEST['criterio'] == "1")
					{
						$sqlConteo .= "AND  modulo ILIKE '%".$_REQUEST['cadena_buscar']."%' ";
					}
					else if($_REQUEST['criterio'] == "2")
						 {
							$sqlConteo .= "AND  modulo_tipo ILIKE '%".$_REQUEST['cadena_buscar']."%' ";
						 }				
				}
				$_SESSION['SqlConteoModulos'] = $sqlConteo;
				
    	}
    	else
    	{
    		$sqlConteo = $_SESSION['SqlConteoModulos'];
    	}		       		
			
			$this->ProcesarSqlConteo($sqlConteo);
					
			$ordenar = "1";
			/*if(!$_SESSION['SqlBuscarModulo'])
			{*/
				$sql  = "SELECT modulo,modulo_tipo,descripcion,version_numero,";
				$sql .= "		  CASE WHEN activo = '1' THEN 'ACTIVO'";
				$sql .= "		  	   WHEN activo = '0' THEN 'INACTIVO'";
				$sql .= "		  END AS estado ";
				$sql .= "FROM system_modulos ";
				$sql .= "WHERE modulo_tipo <> '' AND modulo_tipo NOT LIKE 'hc' ";
				
				if($_REQUEST['cadena_buscar'] != "")
				{
					if($_REQUEST['criterio'] == "1")
					{
						$sql .= "AND  modulo ILIKE '%".$_REQUEST['cadena_buscar']."%' ";
						$ordenar =" 2,1 ";
					}
					else if($_REQUEST['criterio'] == "2")
						 {
							$sql .= "AND  modulo_tipo ILIKE '%".$_REQUEST['cadena_buscar']."%' ";
							$ordenar =" 1,2 ";
						 }
					
					$this->mensaje  = "El Buscador arrojo el siguiente resultado para la busqueda de la cadena: ";
					$this->mensaje .= $_REQUEST['cadena_buscar'];
				}
			
				$_SESSION['SqlBuscarModulo'] = $sql;
			/*}
			else
			{
				$sql = $_SESSION['SqlBuscarModulo'];
			}*/
			
			$sql .= " ORDER BY ".$ordenar;
			$sql .= " LIMIT ".$this->limit." OFFSET ".$this->offset;

			return $sql;
		}
		/************************************************************************ 
		* Funcion que consulta en la base de datos la informacion que contienen 
		* los modulos 
		* 
		* @return boolean 
		*************************************************************************/
		function ObtenerInformacionModulo()
		{
			$this->NombreModulo = $_REQUEST['nombre_modulo'];
			$this->TipoModulo   = $_REQUEST['tipo_modulo'];

			$query .= "SELECT modulo,modulo_tipo,descripcion,version_numero,version_info,activo,sw_user,sw_admin ";
			$query .= "FROM system_modulos ";
			$query .= "WHERE modulo LIKE '".$this->NombreModulo."' ";
			$query .= "AND modulo_tipo LIKE '".$this->TipoModulo."' ";

			list($dbconn) = GetDBconn();
			$result=$dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError["MensajeError"] = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			if(!$result->EOF)
			{
				$this->DescripcionModulo = $result->fields[2];
				$this->VersionModulo     = $result->fields[3];
				$this->InfoVersionModulo = $result->fields[4];
				$this->EstadoModulo  = $result->fields[5];
				$this->SwUserModulo  = $result->fields[6];
				$this->SwAdminModulo = $result->fields[7];
			}
			
			$result->MoveNext();
			$result->Close();
			
			$this->actionM = ModuloGetURL('system','AdminModulos','user',$_REQUEST['metodo_retorno'],
										   array("offset"=>$_REQUEST['offset']));
			
			return true;
		}
		/************************************************************************
		* Funcion que consulta en la base de datos si el modulo solicitado tiene 
		* informacion relacionada en system_menus_items 
		* 					
		* @return array datos de system_menus_items 							
		*************************************************************************/
		function ObtenerInfoMenuItem()
		{		
			list($dbconn) = GetDBconn();
			
			$query  = "SELECT sm.menu_id,sm.menu_nombre,mi.titulo,mi.tipo,mi.metodo,mi.descripcion,mi.indice_de_orden ";
			$query .= "FROM system_menus_items mi, system_menus sm ";
			$query .= "WHERE modulo LIKE '".$this->modulo."' ";
			$query .= "AND modulo_tipo LIKE '".$this->tipoModulo."' ";
			$query .= "AND mi.menu_id = sm.menu_id";

			$i=0;
			$result=$dbconn->Execute($query);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError["MensajeError"] = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			while (!$result->EOF)
			{
				$datos[$i] = $result->fields[0]."/".$result->fields[1]."/".$result->fields[2]."/".$result->fields[3]."/".$result->fields[4]."/".$result->fields[5]."/".$result->fields[6];
				$result->MoveNext();
				$i++;
	    	}
			
			$result->Close();
			return $datos;
		}
		/************************************************************************ 
		* Funcion que consulta en la base de datos si el modulo solicitado tiene 
		* informacion relacionada con system_modulos_default 
		* 
		* @return array datos de system_modulos_default 
		*************************************************************************/
		function ObtenerInfoModuloDefault()
		{
			$query  = "SELECT smd.ip_host,su.nombre,smd.parametros,";
			$query .= "		  CASE WHEN smd.activo = '1' THEN 'ACTIVO' ";
			$query .= "		 	   ELSE 'INACTIVO'";
			$query .= "		  END AS \"activo\" ";
			$query .= "FROM system_modulos_default smd, system_usuarios su ";
			$query .= "WHERE modulo LIKE '".$this->modulo."' ";
			$query .= "AND modulo_tipo LIKE '".$this->tipoModulo."'";
			$query .= "AND smd.usuario_id = su.usuario_id";
			
			$i=0;
			list($dbconn) = GetDBconn();
			$result=$dbconn->Execute($query);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError["MensajeError"] = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			while (!$result->EOF)
			{
				$datos[$i] = $result->fields[0]."/".$result->fields[1]."/".$result->fields[2]."/".$result->fields[3];
				$result->MoveNext();
				$i++;
	    	}
			$result->Close();
			
			return $datos;
		}
		/************************************************************************ 
		* Funcion que consulta en la base de datos si el modulo solicitado tiene 
		* informacion relacionada en system_modulos_variales 
		* 
		* @return array datos de system_modulos_variables 
		*************************************************************************/
		function ObtenerInfoModuloVariables()
		{
			list($dbconn) = GetDBconn();
			
			$query .= "SELECT variable,valor ";
			$query .= "FROM system_modulos_variables ";
			$query .= "WHERE modulo LIKE '".$this->modulo."' ";
			$query .= "AND modulo_tipo LIKE '".$this->tipoModulo."'";
			
			$i=0;
			$result=$dbconn->Execute($query);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError["MensajeError"] = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			while (!$result->EOF)
			{
				$datos[$i] = $result->fields[0]."/".$result->fields[1];
				$result->MoveNext();
				$i++;
	    	}
			$result->Close();
			
			return $datos;
		}
		/************************************************************************ 
		* Funcion que consulta en la base de datos si el modulo solicitado tiene 
		* informacion relacionada en la tabla system_permisos 
		* 
		* @return array datos de system_permisos 
		*************************************************************************/
		function ObtenerInfoSystemPermisos()
		{
			list($dbconn) = GetDBconn();
			
			$query  = "SELECT permiso_nombre,descripcion "; 
			$query .= "FROM system_permisos ";
			$query .= "WHERE modulo LIKE '".$this->modulo."' ";
			$query .= "AND modulo_tipo LIKE '".$this->tipoModulo."'";
			
			$i=0;
			$result=$dbconn->Execute($query);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError["MensajeError"] = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			while (!$result->EOF)
			{
				$datos[$i] = $result->fields[0]."/".$result->fields[1];
				$result->MoveNext();
				$i++;
	    	}
			$result->Close();
			
			return $datos;
		}
		/************************************************************************ 
		* Funcion que consulta en la base de datos si el modulo solicitado tiene
		* informacion relacionada con la tabla system_reports 
		* 
		* @return array datos de la tabla system_reports
		*************************************************************************/
		function ObtenerInfoSystemReportes()
		{
			list($dbconn) = GetDBconn();
			
			$query  = "SELECT sr.report_name,emp.razon_social,sr.class_type,sr.class_name "; 
			$query .= "FROM system_reports sr, empresas emp ";
			$query .= "WHERE modulo LIKE '".$this->modulo."' ";
			$query .= "AND modulo_tipo LIKE '".$this->tipoModulo."' ";
			$query .= "AND sr.empresa_id = emp.empresa_id ";
			
			$i=0;
			$result=$dbconn->Execute($query);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError["MensajeError"] = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			while (!$result->EOF)
			{
				$datos[$i] = $result->fields[0]."/".$result->fields[1];
				$result->MoveNext();
				$i++;
	    	}
			$result->Close();
			
			return $datos;
		}
		/************************************************************************
		* Funcion que permite visualizar la informacion del modulo que se desea 
		* eliminar 
		* 
		* @return boolean 
		*************************************************************************/
		function EliminarModulos()
		{			
			$this->ObtenerInformacionModulo();
			
			$this->modulo = $_REQUEST['nombre_modulo'];
			$this->tipoModulo = $_REQUEST['tipo_modulo'];
	    	$this->actionA = ModuloGetURL('system','AdminModulos','user','MostrarModulos',
	    								   array("offset"=>$_REQUEST['offset']));
	    	$this->actionE = ModuloGetURL('system','AdminModulos','user','EliminarModulosBD',
	    								   array("offset"=>$_REQUEST['offset'],"modulo_nombre"=>$this->modulo,"modulo_tipo"=>$this->tipoModulo));
	    	$this->FormaEliminarModulo();
			return true;
		}
		/******************************************************************************** 
		* Funcion, mediante la cual se eliminan los modulos y las relaciones que poseen 
		* 
		* @return boolean 
		*********************************************************************************/
		function EliminarModulosBD()
		{
			$modulo = $_REQUEST['modulo_nombre'];
			$tipoModulo = $_REQUEST['modulo_tipo'];
			
			$sql .= "DELETE FROM system_menus_items ";
			$sql .= "WHERE modulo LIKE '".$modulo."' ";
			$sql .= "AND modulo_tipo LIKE '".$tipoModulo."';";
			
		/*	$sql .= "DELETE FROM system_reports ";
			$sql .= "WHERE modulo LIKE '".$modulo."' ";
			$sql .= "AND modulo_tipo LIKE '".$tipoModulo."';";*/
			
			$sql .= "DELETE FROM system_modulos_default ";
			$sql .= "WHERE modulo LIKE '".$modulo."' ";
			$sql .= "AND modulo_tipo LIKE '".$tipoModulo."';";
			
			$sql .= "DELETE FROM system_modulos_variables ";
			$sql .= "WHERE modulo LIKE '".$modulo."' ";
			$sql .= "AND modulo_tipo LIKE '".$tipoModulo."';";
			
			$sql .= "DELETE FROM system_permisos ";
			$sql .= "WHERE modulo LIKE '".$modulo."' ";
			$sql .= "AND modulo_tipo LIKE '".$tipoModulo."';";

			$sql .= "DELETE FROM system_modulos ";
			$sql .= "WHERE modulo LIKE '".$modulo."' ";
			$sql .= "AND modulo_tipo LIKE '".$tipoModulo."';";

			list($dbconn) = GetDBconn();
			$result=$dbconn->Execute($sql);

			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError["MensajeError"] = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			
			$informacion = "EL MODULO: ".$modulo." FUE ELIMINADO SATISFACTORIAMENTE DE LA BASE DE DATOS";
			$this->action = ModuloGetURL('system','AdminModulos','user','MostrarModulos',array("offset"=>$_REQUEST['offset']));
			
			$this->FormaInformacion($informacion);
			return true;	
		}
		/************************************************************************ 
		* Funcion, donde se averigua la informacion del modulo seleccionado 
		* 
		* @return boolean 
		*************************************************************************/
		function InformacionModulos()
		{
			$this->ObtenerInformacionModulo();
			$this->action4 = ModuloGetUrl('system','AdminModulos','user','ActualizarModulo',
										   array("offset"=>$_REQUEST['offset'],
										  		 "tipo_modulo"=>$this->TipoModulo,
										  		 "nombre_modulo"=>$this->NombreModulo,
										  		 "tipo_modulo_anterior"=>$this->TipoModulo,
										  		 "nombre_modulo_anterior"=>$this->NombreModulo,
										  		 "metodo_retorno"=>$_REQUEST['metodo_retorno']));
			$this->FormaRegistrarModulo();
			return true;
		}
		/************************************************************************* 
		* Funcion que actualiza los datos de un Modulo 
		* 
		* @return boolean 
		**************************************************************************/
		function ActualizarModulo()
		{
			$this->NombreModulo = $_REQUEST['nombre_modulo'];
			$this->TipoModulo   = $_REQUEST['tipo_modulo'];
			
			$this->DescripcionModulo = $_REQUEST['descripcion'];
			$this->VersionModulo     = $_REQUEST['version'];
			$this->InfoVersionModulo = $_REQUEST['infoVersion'];
			$this->EstadoModulo  = $_REQUEST['disponibilidad'];
			$this->SwUserModulo  = $_REQUEST['funcionU'];
			$this->SwAdminModulo = $_REQUEST['funcionA'];
			
			$version = explode (".",$_REQUEST['version']);
			if(sizeof($version) > 2)
			{
				$this->actionU = ModuloGetUrl('system','AdminModulos','user','ActualizarModulo',
										  	  array("nombre_modulo"=>$this->NombreModulo,"tipo_modulo"=>$this->TipoModulo,"offset"=>$_REQUEST['offset'],
										  	  		"tipo_modulo_anterior"=>$_REQUEST['tipo_modulo_anterior'],"nombre_modulo_anterior"=>$_REQUEST['nombre_modulo_anterior']));
				$this->actionM = ModuloGetURL('system','AdminModulos','user','MostrarModulos',array("offset"=>$_REQUEST['offset']));
				
				$this->frmError["MensajeError"] = "El numero de la version es invalido";
				$this->FormaMostrarInformacionModulo();
				return true;
			}
			if(strlen($version[1]) > 2)
			{
				$this->actionU = ModuloGetUrl('system','AdminModulos','user','ActualizarModulo',
										  	  array("nombre_modulo"=>$this->NombreModulo,"tipo_modulo"=>$this->TipoModulo,"offset"=>$_REQUEST['offset'],
										  	  		"tipo_modulo_anterior"=>$_REQUEST['tipo_modulo_anterior'],"nombre_modulo_anterior"=>$_REQUEST['nombre_modulo_anterior']));
				$this->actionM = ModuloGetURL('system','AdminModulos','user','MostrarModulos',array("offset"=>$_REQUEST['offset']));
				
				$this->frmError["MensajeError"] = "El numero de versión solo debe tener dos cifras decimales";
				$this->FormaMostrarInformacionModulo();
				return true;
			}

			$sql .= "UPDATE system_modulos ";
			$sql .= "SET modulo = '".$this->NombreModulo."', "; 
			$sql .= "	 modulo_tipo = '".$this->TipoModulo."', ";			
			$sql .= "	 descripcion = '".$this->DescripcionModulo."', ";
			$sql .= "	 version_numero=".$this->VersionModulo.", ";
			$sql .= "	 version_info= '".$this->InfoVersionModulo."', ";
			$sql .= "	 activo = '".$this->EstadoModulo."', ";
			$sql .= "	 sw_user= '".$this->EstadoModulo."', ";
			$sql .= "	 sw_admin='".$this->SwAdminModulo."' ";
			$sql .= "WHERE modulo LIKE '".$_REQUEST['nombre_modulo_anterior']."' ";
			$sql .= "AND  modulo_tipo LIKE '".$_REQUEST['tipo_modulo_anterior']."' ";
			
			$informacion = "Tamaño variable 1".$version[1]."<br>".$sql;
			
			list($dbconn) = GetDBconn();
			$result=$dbconn->Execute($sql);

			if ($dbconn->ErrorNo() != 0)
			{
				$informacion = "Error DB : " . $dbconn->ErrorMsg();
			}
			else
			{
				$informacion = "EL MODULO: ".$_REQUEST['nombre_modulo_anterior']." FUE ACTUALIZADO CORRECTAMENTE";	
			}

			$this->action = ModuloGetURL('system','AdminModulos','user','MostrarModulos',array("offset"=>$_REQUEST['offset']));	
			$this->FormaInformacion($informacion);
			return true;
		}
		/********************************************************************************
		* Funcion que raliza el llamado a la forma de ingresar variables
		* 
		* @return boolean 
		*********************************************************************************/
		function AgregarVariable()
		{
			$this->NombreModulo = $_REQUEST['nombre_modulo'];
			if($this->NombreModulo == "")
			{
				$this->NombreModulo = "del sistema";
			}
			$this->actionV = ModuloGetUrl('system','AdminModulos','user','IngresarVariables',
										   array("offset"=>$_REQUEST['offset'],"pagina"=>$_REQUEST['pagina'],"nombre_modulo"=>$_REQUEST['nombre_modulo'],
										   		 "tipo_modulo"=>$_REQUEST['tipo_modulo'],"metodo_retorno"=>$_REQUEST['metodo_retorno']));
			$this->actionM = ModuloGetURL('system','AdminModulos','user','MostrarVariablesModulos',
										   array("offset"=>$_REQUEST['offset'],"pagina"=>$_REQUEST['pagina'],"nombre_modulo"=>$_REQUEST['nombre_modulo'],
										  		 "tipo_modulo"=>$_REQUEST['tipo_modulo'],"metodo_retorno"=>$_REQUEST['metodo_retorno']));
			$this->FormaAdicionarVariables();
			return true;
		}
		/********************************************************************************* 
		* Funcion, mediante la cual se ingresa la nueva variable a la base da datos
		* 
		* @return boolean 
		**********************************************************************************/
		function IngresarVariables()
		{
			$this->Campo = "";
			$this->NombreModulo  = $_REQUEST['nombre_modulo'];
			$this->VariableModulo= $_REQUEST['variable'];
			$this->ValorVariable = $_REQUEST['valorVariable'];
			$this->TipoModulo = $_REQUEST['tipo_modulo'];
			$this->DescripcionVariable = $_REQUEST['descripcion_variable'];
			
			if($this->VariableModulo == "")
			{
				$this->Campo = "MensajeError";
				$this->frmError["MensajeError"] = "El nombre de la variable del modulo es obligatorio";
				$this->AgregarVariable();
				return true;
			}
			else
			{
				list($dbconn) = GetDBconn();
				
				$sql  = "SELECT variable ";
				$sql .= "FROM system_modulos_variables ";
				$sql .= "WHERE modulo LIKE '".$this->NombreModulo."' ";
				$sql .= "AND modulo_tipo LIKE '".$this->TipoModulo."' ";
				$sql .= "AND variable LIKE '".$this->VariableModulo."' ";
				
				$result=$dbconn->Execute($sql);
				
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"] = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
	
				if(!$result->EOF)
				{
					$campo = $result->fields[0];
		    	}
		    	if($campo == "")
		    	{
					$sql  = "INSERT INTO system_modulos_variables ";
					$sql .= "			(modulo, ";
					$sql .= "			 modulo_tipo, ";
					$sql .= "			 variable, ";
					$sql .= "			 valor, ";
					$sql .= "			 descripcion) ";
					$sql .= "VALUES ('".$this->NombreModulo."',
									 '".$this->TipoModulo."',
									 '".$this->VariableModulo."',
									 '".$this->ValorVariable."',
									 '".$this->DescripcionVariable."')";
					$result=$dbconn->Execute($sql);
	
					if ($dbconn->ErrorNo() != 0)
					{
						$this->Campo = "MensajeError";
						$this->frmError["MensajeError"] = "Error DB : " . $dbconn->ErrorMsg();
						$this->AgregarVariable();
						return true;
					}
					else
					{
						if($_REQUEST['nuevaVariable'] == '1')
						{
							$this->VariableModulo= "";
							$this->ValorVariable = "";
              $this->DescripcionVariable = "";
              
							$this->Campo = "Informacion";
							$this->frmError["Informacion"] = "LA VARIABLE: ".$this->VariableModulo.", HA SIDO CREADA ";
							$this->AgregarVariable();
							return true;
						}
						else
						{
							$informacion = "La variable: ".$this->VariableModulo.", ha sido creada ";
							$this->action = ModuloGetURL('system','AdminModulos','user','MostrarVariablesModulos',
											  			  array("offset"=>$_REQUEST['offset'],"pagina"=>$_REQUEST['pagina'],"nombre_modulo"=>$_REQUEST['nombre_modulo'],
											  			  		"tipo_modulo"=>$_REQUEST['tipo_modulo'],"metodo_retorno"=>$_REQUEST['metodo_retorno']));
							$this->FormaInformacion($informacion);
						}
					}
				}
				else
				{
					$this->Campo = "MensajeError";
					$this->frmError["MensajeError"] = "LA VARIABLE ".$this->VariableModulo." YA EXISTE EN EL MODULO";
					$this->AgregarVariable();
				}
			}
			return true;
		}
		/*******************************************************************************
		* Funcion que permite desplegar la informacion que esta relacionada con el menu 
		* 
		* @return boolean 
		********************************************************************************/
		function EliminarMenu()
		{
	    	$this->actionV = ModuloGetURL('system','AdminModulos','user',$_REQUEST['metodo_retorno'],
	    								   $this->ObtenerDatosRequestMenu(0));
	    	$this->actionX = ModuloGetURL('system','AdminModulos','user','EliminarMenuBD',
	    								   $this->ObtenerDatosRequestMenu(1));
	    	$this->ObtenerInfoMenu();
	    	$this->FormaInformacionMenuEliminar();
	    	return true;
		}
		/******************************************************************************* 
		* Funcion donde se consulta la informacion del menu solicitado 
		* 
		* @return boolean  
		********************************************************************************/
		function ObtenerInfoMenu()
		{
			$sql .= "SELECT menu_nombre,descripcion,sw_system ";
			$sql .= "FROM system_menus ";
			$sql .= "WHERE menu_id = ".$_REQUEST['codigo_menu'];
			
			
			list($dbconn) = GetDBconn();
			
			$i=0;
			$result=$dbconn->Execute($sql);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError["MensajeError"] = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			if(!$result->EOF)
			{
				$this->NombreMenu = $result->fields[0];
				$this->DescripcionMenu = $result->fields[1];
				$this->SwSystem = $result->fields[2];
				$result->MoveNext();
	    	}
	    	$result->Close();
	    	return true;
		}
		/************************************************************************
		* Funcion que consulta en la base de datos si el menu solicitado tiene 
		* informacion relacionada en system_menus_items 
		* 					
		* @return array datos de system_menus_items 							
		*************************************************************************/
		function ObtenerInfoMenuItemMenu()
		{		
			list($dbconn) = GetDBconn();
			
			$sql  = "SELECT titulo,tipo,metodo,descripcion,indice_de_orden ";
			$sql .= "FROM system_menus_items ";
			$sql .= "WHERE menu_id = ".$_REQUEST['codigo_menu'];

			$i=0;
			$result=$dbconn->Execute($sql);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError["MensajeError"] = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			while (!$result->EOF)
			{
				$datos[$i] = $result->fields[0]."/".$result->fields[1]."/".$result->fields[2]."/".$result->fields[3]."/".$result->fields[4];
				$result->MoveNext();
				$i++;
	    	}
			
			$result->Close();
			return $datos;
		}
		/************************************************************************
		* Funcion que consulta en la base de datos si el menu solicitado tiene 
		* informacion relacionada en system_usuarios_menus 
		* 					
		* @return array datos de system_usuarios_menus y system_usuarios  							
		*************************************************************************/
		function ObtenerDatosUsuariosMenu()
		{
			$sql .= "SELECT su.usuario_id,su.usuario,su.nombre ";
			$sql .= "FROM system_usuarios su, system_usuarios_menus um ";
			$sql .= "WHERE um.menu_id =".$_REQUEST['codigo_menu']." ";
			$sql .= "AND um.usuario_id = su.usuario_id ";
			
			list($dbconn) = GetDBconn();
			
			$i=0;
			$result=$dbconn->Execute($sql);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError["MensajeError"] = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			while (!$result->EOF)
			{
				$datos[$i] = $result->fields[0]."/".$result->fields[1]."/".$result->fields[2];
				$result->MoveNext();
				$i++;
	    	}
	    	
	    	return $datos;
		}
		/************************************************************************
		* Funcion que consulta en la base de datos si el menu solicitado tiene 
		* informacion relacionada en system_perfiles_menus  
		* 					
		* @return array datos de system_usuarios_menus y system_usuarios  							
		*************************************************************************/
		function ObtenerDatosPerfiles()
		{
			$sql .= "SELECT per.descripcion,em.razon_social ";
			$sql .= "FROM system_perfiles_menus sp, system_perfiles per, empresas em ";
			$sql .= "WHERE sp.menu_id =".$_REQUEST['codigo_menu']." ";
			$sql .= "AND sp.perfil_id = per.perfil_id  ";
			$sql .= "AND per.empresa_id = per.empresa_id  ";
			
			list($dbconn) = GetDBconn();
			$i=0;
			$result=$dbconn->Execute($sql);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError["MensajeError"] = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			while (!$result->EOF)
			{
				$datos[$i] = $result->fields[0]."/".$result->fields[1];
				$result->MoveNext();
				$i++;
	    	}
	    	
	    	return $datos;
		}
		/******************************************************************************* 
		* Funcion donde se eñimina el menu de la base de datos 
		* 
		* @return boolean 
		********************************************************************************/
		function EliminarMenuBD()
		{
			$sql .= "DELETE FROM system_menus ";
			$sql .= "WHERE menu_id =".$_REQUEST['codigo_menu']." ";
			
			list($dbconn) = GetDBconn();
			
			$result=$dbconn->Execute($sql);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError["MensajeError"] = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				$this->action = ModuloGetURL('system','AdminModulos','user',$_REQUEST['metodo_retorno'],
								   			   array("offset"=>$_REQUEST['offset'],"nombre_modulo"=>$_REQUEST['nombre_modulo'],
								   			         "tipo_modulo"=>$_REQUEST['tipo_modulo'],"pagina"=>$_REQUEST['pagina']));
						
						
				$informacion = "EL MENÚ HA SIDO ELIMINADO<br> ";
				$this->FormaInformacion($informacion);
			}
			return true;	
		} 
		/******************************************************************************* 
		* Funcion mediante la cual se visualiza la forma de mostrar menus 
		* 
		* @return boolean 
		********************************************************************************/
		function MantenimientoMenu()
		{			
			$this->actionCrear = ModuloGetURL('system','AdminModulos','user','RegistrarMenu',
											   array("offset"=>$_REQUEST['offset'],"pagina"=>$_REQUEST['pagina'],"llamado"=>"2","metodo_retorno"=>'MantenimientoMenu',
											   		 "nombre_modulo"=>$_REQUEST['nombre_modulo'],"tipo_modulo"=>$_REQUEST['tipo_modulo']));			

			$this->action1 = ModuloGetURL('system','AdminModulos','user','MostrarModulos',
										   array("offset"=>$_REQUEST['pagina']));
			$this->actionPaginador = ModuloGetURL('system','AdminModulos','user','MantenimientoMenu');
			$this->FormaMostrarMenus();
			return true;
		}
		/******************************************************************************* 
		* Funcion, donde se consultan los menus asociados a el modulo seleccionado
		* 
		* @return array resultado de la consulta
		********************************************************************************/
		function DatosMenus()
		{	
			$sqlConteo .= "SELECT COUNT(*)"; 
			$sqlConteo .= "FROM system_menus sm, system_menus_items mi ";
			$sqlConteo .= "WHERE sm.menu_id = mi.menu_id ";
			$sqlConteo .= "AND mi.modulo LIKE '".$_REQUEST['nombre_modulo']."' ";
			$sqlConteo .= "AND mi.modulo_tipo LIKE '".$_REQUEST['tipo_modulo']."' ";
			
			$this->ProcesarSqlConteo($sqlConteo);

			$sql .= "SELECT sm.menu_id, sm.menu_nombre, sm.descripcion,";
			$sql .= "CASE WHEN sm.sw_system = '0' THEN 'NO' ";
			$sql .= "ELSE 'SI' END AS \"sw\" ";
			$sql .= "FROM system_menus sm, system_menus_items mi ";
			$sql .= "WHERE sm.menu_id = mi.menu_id ";
			$sql .= "AND mi.modulo LIKE '".$_REQUEST['nombre_modulo']."' ";
			$sql .= "AND mi.modulo_tipo LIKE '".$_REQUEST['tipo_modulo']."' ";
			$sql .= "ORDER BY 1 ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;

			list($dbconn) = GetDBconn();
			
			$i=0;
			$result=$dbconn->Execute($sql);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError["MensajeError"] = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			while (!$result->EOF)
			{
				$datos[$i] = $result->fields[0]."/".$result->fields[1]."/".$result->fields[2]."/".$result->fields[3];
				$result->MoveNext();
				$i++;
	    	}
	    	$result->Close();
	    	return $datos;
	    }
	    /*******************************************************************************
	    * Funcion mediante la cual se permite registrar un menu 
	    * 
	    * @return boolean 
	    ********************************************************************************/
	    function RegistrarMenu()
	    {
			$this->NombreModulo = $_REQUEST['nombre_modulo'];
			$this->TipoModulo = $_REQUEST['tipo_modulo'];
			$this->Llamado = $_REQUEST['llamado'];

			switch($this->Llamado)
			{
				case '0':
						$this->actionV = ModuloGetURL('system','AdminModulos','user','main');
				break;
				case '1':
						$this->actionV = ModuloGetURL('system','AdminModulos','user','MostrarModulos',
													   array("offset"=>$_REQUEST['offset']));
				break;
				case '2':
						$this->actionV = ModuloGetURL('system','AdminModulos','user','MantenimientoMenu',
										   			   array("offset"=>$_REQUEST['offset'],"nombre_modulo"=>$_REQUEST['nombre_modulo'],
										   			         "tipo_modulo"=>$_REQUEST['tipo_modulo'],"metodo_retorno"=>$_REQUEST['metodo_retorno']));
				break;
				case '4':case'5':
						$this->actionV = ModuloGetURL('system','AdminModulos','user',$_REQUEST['metodo_retorno'],
										   			   array("offset"=>$_REQUEST['pagina']));
				break;
			}

	    	$this->action3 = ModuloGetURL('system','AdminModulos','user','IngresarMenuBD',
	    								   array("offset"=>$_REQUEST['pagina'],"nombre_modulo"=>$_REQUEST['nombre_modulo'],"asociado"=>$_REQUEST['asociado'],
	    								   		 "tipo_modulo"=>$_REQUEST['tipo_modulo'],"metodo_retorno"=>$_REQUEST['metodo_retorno']));
			$this->FormaIngresarMenu();
			
			return true;
	    }
	    /*******************************************************************************
	    * Funcion que ingresa un nuevo menu a la base de datos 
	    * 
	    * @return boolean 
	    ********************************************************************************/
	    function IngresarMenuBD()
	    {
			$this->Llamado = $_REQUEST['llamado'];
			switch($this->Llamado)
			{
				case '0':
						$this->actionV = ModuloGetURL('system','AdminModulos','user','main');
				break;
				case '1':
						$this->actionV = ModuloGetURL('system','AdminModulos','user','MostrarModulos',
													   array("offset"=>$_REQUEST['offset']));
				break;
				case '2':
						$this->actionV = ModuloGetURL('system','AdminModulos','user','MantenimientoMenu',
										   			   array("offset"=>$_REQUEST['offset'],"nombre_modulo"=>$_REQUEST['nombre_modulo'],
										   			         "tipo_modulo"=>$_REQUEST['tipo_modulo'],"metodo_retorno"=>$_REQUEST['metodo_retorno']));
				break;
				case '4':case '5':
						$this->actionV = ModuloGetURL('system','AdminModulos','user',$_REQUEST['metodo_retorno'],
										   			   array("offset"=>$_REQUEST['offset']));
				break;
			}

	    	$this->action3 = ModuloGetURL('system','AdminModulos','user','IngresarMenuBD',
	    								   array("offset"=>$_REQUEST['offset'],"nombre_modulo"=>$_REQUEST['nombre_modulo'],"asociado"=>$_REQUEST['asociado'],
	    								   		 "tipo_modulo"=>$_REQUEST['tipo_modulo'],"metodo_retorno"=>$_REQUEST['metodo_retorno']));

			$this->NombreMenu = $_REQUEST['nombre_menu'];
			$this->TipoModulo = $_REQUEST['tipo_modulo'];
			$this->NombreModulo = $_REQUEST['nombre_modulo'];
			$this->SwSystemMenu = $_REQUEST['menuA'];
			$this->DescripcionMenu = $_REQUEST['descripcion'];

			if($this->NombreMenu == "")
			{
				$this->frmError["MensajeError"] = "El nombre del menu es obligatorio";
				$this->FormaIngresarMenu();
			}
			else
			{
				list($dbconn) = GetDBconn();
				$query = "SELECT menu_nombre FROM system_menus WHERE menu_nombre ILIKE '".$this->NombreMenu."'";
				$result=$dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"] = "Error DB : " . $dbconn->ErrorMsg();
					$this->$this->FormaIngresarMenu();
					return true;
				}
			
				$i=0;
				while (!$result->EOF)
				{
					$result->MoveNext();
					$i++;
				}
				$result->Close();
								
				if($i > 0)
				{
					$this->frmError["MensajeError"] = "El Nombre de Menu : ".$this->NombreMenu." Ya Existe!";
					$this->FormaIngresarMenu();
					return true;
				}
				
				$sql .= "SELECT SETVAL ('public.system_menus_menu_id_seq',(SELECT MAX(menu_id) FROM system_menus),true); ";		
				$sql .= "INSERT INTO system_menus(menu_nombre, descripcion,sw_system) ";
				$sql .= "VALUES ('".$this->NombreMenu."',
							   	 '".$this->DescripcionMenu."',
							   	 '".$this->SwSystemMenu."');";
				$result=$dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"] = "Error DB : " . $dbconn->ErrorMsg();
					$this->RegistrarMenu();
				}
				else
				{
					if($_REQUEST['ingresaMenuItem'] == '1')
					{
						$this->RegistrarMenuItem();
					}
					else
					{
						switch($this->Llamado)
						{
							case '0':
									$this->action = ModuloGetURL('system','AdminModulos','user','main');
							break;
							case '1':
									$this->action = ModuloGetURL('system','AdminModulos','user','MostrarModulos',
																   array("offset"=>$_REQUEST['offset']));
							break;
							case '2':
									$this->action = ModuloGetURL('system','AdminModulos','user','MantenimientoMenu',
													   			   array("offset"=>$_REQUEST['offset'],"nombre_modulo"=>$_REQUEST['nombre_modulo'],"tipo_modulo"=>$_REQUEST['tipo_modulo']));
							break;
							case '5':
									$this->action = ModuloGetURL('system','AdminModulos','user',$_REQUEST['metodo_retorno'],
													   			   array("offset"=>$_REQUEST['offset'],"nombre_modulo"=>$_REQUEST['nombre_modulo'],"tipo_modulo"=>$_REQUEST['tipo_modulo']));
							break;
						}
						
						$informacion = "EL MENÚ: ".$this->NombreMenu." FUE CREADO ";
						$this->FormaInformacion($informacion);
					}
					$result->Close();
				}
			}
						
			return true;
	    }
		/*******************************************************************************
		* Funcion mediante la cual se permite registrar un Menu Item 
		* 
		* @return boolean 
		********************************************************************************/
	  function RegistrarMenuItem()
	  {
			$this->IndiceDeOrden = '0';
			$this->Llamado = $_REQUEST['llamado'];
			$this->asociado = $_REQUEST['asociado'];
			$this->NombreMenu = $_REQUEST['nombre_menu'];
			
			if($this->asociado !="NO")
			{
				$this->TipoModulo = $_REQUEST['tipo_modulo'];
				$this->NombreModulo = $_REQUEST['nombre_modulo'];
			}
			
			$this->ObtenerActionRegistrarMenuItem($this->Llamado);
			
	    $this->action4 = ModuloGetURL('system','AdminModulos','user','IngresarMenuItemBD',$this->ObtenerRequestMenuItem(3));
			$this->FormaIngresarMenuItem();
			
			return true;
	  }
	  /************************************************************************ 
		* Funcion que ingresa un nuevo Item de Menu a la base de datos 
		* 
		* @return boolean 
		*************************************************************************/
		function IngresarMenuItemBD()
		{			
			$this->ObtenerActionRegistrarMenuItem($this->Llamado);
			
	    	$this->action4 = ModuloGetURL('system','AdminModulos','user','IngresarMenuItemBD',
	    								   $this->ObtenerRequestMenuItem(3));

			list($dbconn) = GetDBconn();
			$this->Llamado = $_REQUEST['llamado'];
			$this->asociado = $_REQUEST['asociado'];
			$this->TipoMenuItem = $_REQUEST['tipoMI'];
			$this->IndiceDeOrden= $_REQUEST['indice'];
			$this->MenuNombre   = $_REQUEST['nombre_menu'];
			$this->TituloMenu   = $_REQUEST['titulo_menu'];
			$this->NombreMetodo = $_REQUEST['metodoNombre'];
			$this->NombreModulo = $_REQUEST['nombre_modulo'];
			$this->DescripcionMenuItem = $_REQUEST['descripcion'];
			
			if($_REQUEST['asociado'] != 'NO')
			{
				$this->TipoModulo   = $_REQUEST['tipo_modulo'];
			}
			if($this->TituloMenu == "")
			{
				$this->frmError["MensajeError"] = "El titulo del Item de Menu es obligatorio";
				$this->FormaIngresarMenuItem();
			}
			else if($this->TipoMenuItem == "")
				  {
				  		$this->frmError["MensajeError"] = "El tipo asociado al Item de Menu es obligatorio";
				  		$this->FormaIngresarMenuItem();
				  }
				  
				  else if($this->NombreMetodo == "")
				  		{
				  			$this->frmError["MensajeError"] = "El nombre del Metodo es obligatorio";
				  			$this->FormaIngresarMenuItem();
				  		}
				  		else{
							
							if($this->IndiceDeOrden == "")
								$this->IndiceDeOrden = 0;
							
							if($_REQUEST['asociado'] == 'NO')
							{
								$modulo = explode("/",$_REQUEST['nombre_modulo']);
								$this->NombreModulo = $modulo[1];
								$this->TipoModulo = $modulo[0];
							}
							
							$sql .= "SELECT SETVAL ('public.system_menus_items_menu_item_id_seq',(SELECT MAX(menu_item_id) FROM system_menus_items),true);";
							$sql .= "INSERT INTO system_menus_items (menu_id, titulo, modulo_tipo, modulo, tipo, metodo, descripcion, indice_de_orden) "; 
							$sql .= "VALUES ((select menu_id from system_menus where menu_nombre='".$this->MenuNombre."'),
					   		  		 '".$this->TituloMenu."',
					   		  		 '".$this->TipoModulo."',
					   		  		 '".$this->NombreModulo."',
					   		  		 '".$this->TipoMenuItem."',
					   		  		 '".$this->NombreMetodo."',
					   		  		 '".$this->DescripcionMenuItem."',
					   		   		  ".$this->IndiceDeOrden.");";
							
							$result=$dbconn->Execute($sql);
							if ($dbconn->ErrorNo() != 0)
							{
				  				echo $sql;
				  				$this->frmError["MensajeError"] = "Error DB : " . $dbconn->ErrorMsg();
				  				$this->FormaIngresarMenuItem();
								return true;
							}
							$result->Close();
							if($_REQUEST['nuevo_item'] == "1")
							{
								$this->TipoMenuItem = "";
								$this->IndiceDeOrden= "";
								$this->TituloMenu   = "";
								$this->NombreMetodo = "";
								$this->DescripcionMenuItem = "";
								
								$this->FormaIngresarMenuItem();
								return true;
							}
							else
							{				
								switch($this->Llamado)
								{
									case '0':
											$this->action = ModuloGetURL('system','AdminModulos','user','main');
									break;
									case '1':
											$this->action = ModuloGetURL('system','AdminModulos','user','MostrarModulos',
																		  array("offset"=>$_REQUEST['offset']));
									break;
									case '2':
											$this->action = ModuloGetURL('system','AdminModulos','user','MantenimientoMenu',
															   			  $this->ObtenerRequestMenuItem(2));
									break;
									case '3':case '5':
											$this->action = ModuloGetURL('system','AdminModulos','user',$_REQUEST['metodo_retorno'],
													   					  $this->ObtenerRequestMenuItem(3));
									break;
								}
								$informacion = "EL ITEM DE MENÚ HA SIDO CREADO";
			
								$this->FormaInformacion($informacion);
								return true;
							}
						}
			//echo "Valor".$this->NombreMetodo;

			return true;
		}
	    /******************************************************************************* 
	    * Funcion en la que se consulta la informacion del modulo y permite visualizar 
	    * estos datos en pantalla 
	    * 
	    * @return boolean 
	    ********************************************************************************/
	    function EditarMenu()
	    {
	    	$metodo = "MantenimientoMenu";
	    	if($_REQUEST['metodo_retorno'])
	    	{
	    		$metodo = $_REQUEST['metodo_retorno'];
	    	}
				$this->actionV = ModuloGetURL('system','AdminModulos','user',$metodo,
											   $this->ObtenerDatosRequestMenu(0));
				$this->action3 = ModuloGetURL('system','AdminModulos','user','ActualizarMenu',
											   $this->ObtenerDatosRequestMenu(1));
				
				$this->NombreModulo = $_REQUEST['nombre_modulo'];
				$this->TipoModulo = $_REQUEST['tipo_modulo'];
				
				$sql .= "SELECT menu_id,menu_nombre,descripcion,sw_system ";
				$sql .= "FROM system_menus ";
				$sql .= "WHERE menu_id = ".$_REQUEST['codigo_menu'];

				if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

				if(!$rst->EOF)
				{
					$this->Llamado = '3';
					$this->IdMenu = $rst->fields[0];
					$this->NombreMenu = $rst->fields[1];
					$this->SwSystemMenu = $rst->fields[3];
					$this->DescripcionMenu = $rst->fields[2];
				
					$rst->MoveNext();
		    }
				$rst->Close();

				$this->FormaIngresarMenu();
				return true;
	    }
	    /******************************************************************************* 
	    * Funcion donde se actualiza la informnacion del menu en la base de datos 
	    * 
	    * @return boolean 
	    ********************************************************************************/
	    function ActualizarMenu()
	    {
			$this->actionV = ModuloGetURL('system','AdminModulos','user',$_REQUEST['metodo_retorno'],
										   $this->ObtenerDatosRequestMenu(0));
			$this->action3 = ModuloGetURL('system','AdminModulos','user','ActualizarMenu',
										   $this->ObtenerDatosRequestMenu(1));

			$this->Llamado = $_REQUEST['llamado'];
			$this->IdMenu = $_REQUEST['codigo_menu'];
			$this->NombreMenu = $_REQUEST['nombre_menu'];
			$this->TipoModulo = $_REQUEST['tipo_modulo'];
			$this->NombreModulo = $_REQUEST['nombre_modulo'];
			$this->SwSystemMenu = $_REQUEST['menuA'];
			$this->DescripcionMenu = $_REQUEST['descripcion'];
			
			if($this->NombreMenu == "")
			{
				$this->frmError["MensajeError"] = "El nombre del menu es obligatorio";
				$this->FormaIngresarMenu();
			}
			else
			{
				$sql .= "UPDATE system_menus ";
				$sql .= "SET menu_nombre ='".$this->NombreMenu."', ";
				$sql .= "	 descripcion ='".$this->DescripcionMenu."', ";
				$sql .= "	 sw_system ='".$this->SwSystemMenu."' ";
				$sql .= "WHERE menu_id = ".$this->IdMenu;
								
				list($dbconn) = GetDBconn();
			
				$result=$dbconn->Execute($sql);
			
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"] = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else
				{
					$informacion = "EL MENU FUE ACTUALIZADO CORRECTAMENTE";
					$this->action = $this->actionV;
			
					$this->FormaInformacion($informacion);
					return true;
				}
			}
			return true;
	    }
	    /******************************************************************************* 
	    * Funcion que permite mostrar los items de menú asociados al menu del modulo 
	    * seleccionado 
	    * 
	    * @return boolean 
	    ********************************************************************************/
	    function MostrarItemsMenu()
	    {
	    	$this->Titulo = $_REQUEST['nombre_menu'];
	    	
	    	$this->metodo = "MantenimientoMenu";
	    	if($_REQUEST['metodo_retorno'])
	    	{
	    		$this->metodo = $_REQUEST['metodo_retorno'];
	    	}
	    	$this->actionV = ModuloGetURL('system','AdminModulos','user',$this->metodo,$this->ObtenerRequestMenuItem(0));
				$this->actionCrear = ModuloGetURL('system','AdminModulos','user','RegistrarMenuItem',$this->ObtenerRequestMenuItem(1));
				$this->actionPaginador = ModuloGetURL('system','AdminModulos','user','MostrarItemsMenu',$this->ObtenerRequestMenuItem(0));
				
	    	$this->FormaMantenimientoMenuItem();
	    	return true;
	    }
	    /*******************************************************************************
	    * Funcion donde se averiguan los datos de los items de menú asociados a un menu 
	    * especifico 
	    * 
	    * @return boolean 
	    ********************************************************************************/
	    function DatosItemsMenus()
	    {	
	    	$sqlConteo .= "SELECT COUNT(*) ";
				$sqlConteo .= "FROM system_menus_items ";
	    	$sqlConteo .= "WHERE menu_id = ".$_REQUEST['codigo_menu']." ";
	    	if($_REQUEST['nombre_modulo'])
	    	{
	    		$sqlConteo .= "AND modulo LIKE '".$_REQUEST['nombre_modulo']."' ";
	    		$sqlConteo .= "AND modulo_tipo LIKE '".$_REQUEST['tipo_modulo']."' ";
	    	}
	    	$this->ProcesarSqlConteo($sqlConteo);
	    	
	    	$sql .= "SELECT  menu_item_id,titulo,descripcion,tipo,metodo,modulo||' '||modulo_tipo,indice_de_orden ";
	    	$sql .= "FROM system_menus_items ";
	    	$sql .= "WHERE menu_id = ".$_REQUEST['codigo_menu']." ";
	    	if($_REQUEST['asociado'] != "NO")
	    	{
		    	$sql .= "AND modulo LIKE '".$_REQUEST['nombre_modulo']."' ";
		    	$sql .= "AND modulo_tipo LIKE '".$_REQUEST['tipo_modulo']."' ";
	    	}
	    	$sql .= "ORDER BY 2 ";
	    	$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
	    	
			list($dbconn) = GetDBconn();
			
			$i=0;
			$result=$dbconn->Execute($sql);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError["MensajeError"] = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			while (!$result->EOF)
			{
				$datos[$i] = $result->fields[0]."/".$result->fields[1]."/".$result->fields[2]."/".$result->fields[3]."/".$result->fields[4]."/".$result->fields[5]."/".$result->fields[6];
				$result->MoveNext();
				$i++;
	    	}
	    	
	    	return $datos;
	    }
		 /******************************************************************************* 
	    * Funcion en la que se consulta la informacion del modulo y permite visualizar 
	    * estos datos en pantalla 
	    * 
	    * @return boolean 
	    ********************************************************************************/
	    function EditarMenuItem()
	    {
			$this->actionV = ModuloGetURL('system','AdminModulos','user','MostrarItemsMenu',
										   $this-> ObtenerRequestMenuItem(3));
			$this->action4 = ModuloGetURL('system','AdminModulos','user','ActualizarMenuItem',
										   $this-> ObtenerRequestMenuItem(3));
			
			$sql .= "SELECT titulo,descripcion,tipo,metodo,modulo,modulo_tipo,indice_de_orden ";
			$sql .= "FROM system_menus_items ";
			$sql .= "WHERE menu_id = ".$_REQUEST['codigo_menu']." ";
			$sql .= "AND menu_item_id = ".$_REQUEST['codigo_menu_item']." ";
			
			list($dbconn) = GetDBconn();
			
			$i=0;
			$result=$dbconn->Execute($sql);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError["MensajeError"] = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			
			$this->asociado = $_REQUEST['asociado'];
			
			if(!$result->EOF)
			{
				$this->Llamado = '4';
				if($this->asociado == 'NO')
				{
					$this->NombreModulo = $result->fields[5]."/".$result->fields[4];
				}
				else
				{
					$this->NombreModulo = $result->fields[4];
					$this->TipoModulo = $result->fields[5];
				}
				$this->TituloMenu = $result->fields[0];
				$this->TipoMenuItem = $result->fields[2];
				$this->DescripcionMenuItem = $result->fields[1];
				$this->IndiceDeOrden = $result->fields[6];
				$this->NombreMetodo = $result->fields[3];
				
				$result->MoveNext();
				$result->Close();
	    	}

			$this->FormaIngresarMenuItem();
			return true;
	    }
	    /******************************************************************************* 
	    * Funcion donde se actualiza la informnacion del item de menu en la base de 
	    * datos 
	    * 
	    * @return boolean 
	    ********************************************************************************/
	    function ActualizarMenuItem()
	    {
			$this->Llamado = $_REQUEST['llamado'];
			$this->asociado = $_REQUEST['asociado'];
			$this->TipoMenuItem	= $_REQUEST['tipoMI'];
			$this->IndiceDeOrden = $_REQUEST['indice'];
			$this->CodigoMenu = $_REQUEST['codigo_menu'];
			$this->TituloMenu = $_REQUEST['titulo_menu'];
			$this->TipoModulo = $_REQUEST['tipo_modulo'];
			$this->NombreMetodo = $_REQUEST['metodoNombre'];
			$this->NombreModulo = $_REQUEST['nombre_modulo'];
			$this->CodigoMenuItem = $_REQUEST['codigo_menu_item'];
			$this->DescripcionMenuItem = $_REQUEST['descripcion'];
			
			$this->actionV = ModuloGetURL('system','AdminModulos','user','MostrarItemsMenu',
										   $this-> ObtenerRequestMenuItem(3));
			$this->action4 = ModuloGetURL('system','AdminModulos','user','ActualizarMenuItem',
										   $this-> ObtenerRequestMenuItem(3));
			if($this->TituloMenu == "")
			{
				$this->frmError["MensajeError"] = "El titulo del Item de Menu es obligatorio";
				$this->FormaIngresarMenuItem();
			}
			else if($this->TipoMenuItem == "")
				  {
				  		$this->frmError["MensajeError"] = "El tipo asociado al Item de Menu es obligatorio";
				  		$this->FormaIngresarMenuItem();
				  }
				  else if($this->NombreMetodo == "")
				  		{
				  			$this->frmError["MensajeError"] = "El nombre del Metodo es obligatorio";
				  			$this->FormaIngresarMenuItem();
				  		}
				  		else
				  		{
				  			$sql .= "UPDATE system_menus_items ";
				  			$sql .= "SET titulo ='".$this->TituloMenu."',";
				  			$sql .= "	 tipo ='".$this->TipoMenuItem."',";
				  			$sql .= "	 metodo ='".$this->NombreMetodo."',";
				  			$sql .= "	 descripcion ='".$this->DescripcionMenuItem."',";
				  			$sql .= "	 indice_de_orden =".$this->IndiceDeOrden." ";
				  			if($this->asociado == "NO")
				  			{
				  				$contenido = explode("/",$this->NombreModulo);
				  				$sql .= ",	 modulo_tipo ='".$contenido[0]."',";
				  				$sql .= "	 modulo ='".$contenido[1]."' ";
				  			}
							$sql .= "WHERE menu_item_id = ".$this->CodigoMenuItem;
							
							list($dbconn) = GetDBconn();
						
							$result=$dbconn->Execute($sql);
						
							if ($dbconn->ErrorNo() != 0)
							{
								$this->frmError["MensajeError"] = "Error DB : " . $dbconn->ErrorMsg();
								return false;
							}
							else
							{
								$this->action = $this->actionV;
								$informacion = "EL ITEM DE MENÚ FUE ACTUALIZADO CORRECTAMENTE";
								$this->FormaInformacion($informacion);

							}
				  		}
  			return true;		
  		}
	    /**
	    * Funcion que permite mostrar los items de menú asociados al menu del modulo 
	    * seleccionado 
	    * 
	    * @return boolean 
	    */
	    function MostrarVariablesModulos()
	    {
	    	$this->actionV = ModuloGetURL('system','AdminModulos','user',$_REQUEST['metodo_retorno'],
	    								array("offset"=>$_REQUEST['pagina'],"nombre_modulo"=>$_REQUEST['nombre_modulo'],"tipo_modulo"=>$_REQUEST['tipo_modulo']));
        $this->actionCrear = ModuloGetURL('system','AdminModulos','user','AgregarVariable',
										  array("nombre_modulo"=>$_REQUEST['nombre_modulo'],"tipo_modulo"=>$_REQUEST['tipo_modulo'],"pagina"=>$_REQUEST['pagina'],
                            "metodo_retorno"=>$_REQUEST['metodo_retorno'],"offset"=>$this->paginaActual));
	    	$this->actionPaginador = ModuloGetURL('system','AdminModulos','user','MostrarVariablesModulos',
										  array("tipo_modulo"=>$_REQUEST['tipo_modulo'],"nombre_modulo"=>$_REQUEST['nombre_modulo'],
                            "metodo_retorno"=>$_REQUEST['metodo_retorno']));
	    	$this->FormaMantenimientoVariables();
	    	return true;
	    }
	    /*******************************************************************************
	    * Funcion donde se averiguan los datos de las variables asociadas al modulo  
	    * seleccionado
	    * 
	    * @return array datos de las variables del modulo 
	    ********************************************************************************/
	    function DatosVariablesModulos()
	    {	
	    	$sqlConteo .= "SELECT COUNT(*) ";
	    	$sqlConteo .= "WHERE modulo LIKE '".$_REQUEST['nombre_modulo']."' ";
	    	$sqlConteo .= "AND modulo_tipo LIKE '".$_REQUEST['tipo_modulo']."' ";
	    	
	    	$this->ProcesarSqlConteo($sqlConteo);
			
        $sql .= "SELECT variable,valor,descripcion ";
        $sql .= "FROM   system_modulos_variables ";
	    	$sql .= "WHERE  modulo = '".$_REQUEST['nombre_modulo']."' ";
	    	$sql .= "AND    modulo_tipo = '".$_REQUEST['tipo_modulo']."' ";
	    	$sql .= "ORDER BY 1 ";
	    	$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
				
        if(!$rst = $this->ConexionBaseDatos($sql)) return false;

        while (!$rst->EOF)
        {
          $datos[] = $rst->GetRowAssoc($ToUpper = false);
          $rst->MoveNext();
	    	}
        $rst->Close();
	    	return $datos;
	    }
	    /******************************************************************************* 
	    * Funcion en la que se consulta la informacion de las variables del modulo 
	    * 
	    * @return boolean 
	    ********************************************************************************/
	    function EditarVariables()
	    {
			$this->actionM = ModuloGetURL('system','AdminModulos','user','MostrarVariablesModulos',
										   array("offset"=>$_REQUEST['offset'],"pagina"=>$_REQUEST['pagina'],"nombre_modulo"=>$_REQUEST['nombre_modulo'],
										         "tipo_modulo"=>$_REQUEST['tipo_modulo'],"metodo_retorno"=>$_REQUEST['metodo_retorno']));
			$this->actionV = ModuloGetURL('system','AdminModulos','user','ActualizarVariable',
										   array("offset"=>$_REQUEST['offset'],"pagina"=>$_REQUEST['pagina'],
										   		 "nombre_modulo"=>$_REQUEST['nombre_modulo'],"tipo_modulo"=>$_REQUEST['tipo_modulo'],
										   		 "codigo_menu"=>$_REQUEST['codigo_menu'],"nombre_variable"=>$_REQUEST['nombre_variable'],
										   		 "metodo_retorno"=>$_REQUEST['metodo_retorno']));
			
			$this->NombreModulo = $_REQUEST['nombre_modulo'];
			$this->TipoModulo = $_REQUEST['tipo_modulo'];
			
			$sql .= "SELECT variable,valor,descripcion ";
			$sql .= "FROM system_modulos_variables ";
			$sql .= "WHERE modulo LIKE '".$_REQUEST['nombre_modulo']."' ";
			$sql .= "AND modulo_tipo LIKE '".$_REQUEST['tipo_modulo']."' ";
			$sql .= "AND variable LIKE '".$_REQUEST['nombre_variable']."' ";
			
			list($dbconn) = GetDBconn();
			
			$i=0;
			$result=$dbconn->Execute($sql);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError["MensajeError"] = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			if(!$result->EOF)
			{
				$this->Llamado = '1';
				$this->ValorVariable = $result->fields[1];
				$this->VariableModulo = $result->fields[0];
				$this->DescripcionVariable = $result->fields[2];
				
				$result->MoveNext();
				$result->Close();
	    	}

			$this->FormaAdicionarVariables();
			return true;
	    }
	    /******************************************************************************* 
	    * Funcion donde se actualiza la informnacion de las variables del modulo  
	    * 
	    * @return boolean 
	    ********************************************************************************/
	   function ActualizarVariable()
	   {
			$this->actionM = ModuloGetURL('system','AdminModulos','user','MostrarVariablesModulos',
										   array("offset"=>$_REQUEST['offset'],"pagina"=>$_REQUEST['pagina'],
										   		 "tipo_modulo"=>$_REQUEST['tipo_modulo'],
										   		 "nombre_modulo"=>$_REQUEST['nombre_modulo'],
												 "metodo_retorno"=>$_REQUEST['metodo_retorno']));
			$this->actionV = ModuloGetURL('system','AdminModulos','user','ActualizarVariable',
										   array("offset"=>$_REQUEST['offset'],"pagina"=>$_REQUEST['pagina'],
										   		 "nombre_modulo"=>$_REQUEST['nombre_modulo'],"tipo_modulo"=>$_REQUEST['tipo_modulo'],
										   		 "codigo_menu"=>$_REQUEST['codigo_menu'],"nombre_variable"=>$_REQUEST['nombre_variable'],
										   		 "metodo_retorno"=>$_REQUEST['metodo_retorno']));
			$this->Llamado = '1';
			$this->ValorVariable = $_REQUEST['valorVariable'];
			$this->VariableModulo = $_REQUEST['variable'];
			$this->DescripcionVariable = $_REQUEST['descripcion_variable'];
			
			if($this->VariableModulo == "")
			{
				$this->Campo = "MensajeError";
				$this->frmError["MensajeError"] = "El nombre de la variable del modulo es obligatorio";
				$this->FormaAdicionarVariables();
				return true;
			}
			else
			{
				if($this->VariableModulo != $_REQUEST['nombre_variable'])
				{
					list($dbconn) = GetDBconn();
					
					$sql  = "SELECT variable ";
					$sql .= "FROM system_modulos_variables ";
					$sql .= "WHERE modulo LIKE '".$this->NombreModulo."' ";
					$sql .= "AND modulo_tipo LIKE '".$this->TipoModulo."' ";
					$sql .= "AND variable LIKE '".$this->VariableModulo."' ";
					
					$result=$dbconn->Execute($sql);
					
					if ($dbconn->ErrorNo() != 0)
					{
						$this->frmError["MensajeError"] = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
		
					if(!$result->EOF)
					{
						$campo = $result->fields[0];
			    	}
		    	}
		    	if($campo == "")
		    	{					
					$sql  = "UPDATE system_modulos_variables ";
					$sql .= "SET valor ='".$this->ValorVariable."', ";
					$sql .= "	 variable ='".$this->VariableModulo."', ";
					$sql .= "	 descripcion ='".$this->DescripcionVariable."' ";
					$sql .= "WHERE modulo LIKE '".$_REQUEST['nombre_modulo']."' ";
					$sql .= "AND modulo_tipo LIKE '".$_REQUEST['tipo_modulo']."' ";
					$sql .= "AND variable LIKE '".$_REQUEST['nombre_variable']."'";
					
					list($dbconn) = GetDBconn();
			
					$result=$dbconn->Execute($sql);
			
					if ($dbconn->ErrorNo() != 0)
					{
						$this->frmError["MensajeError"] = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
					else
					{
						$informacion = "LA VARIABLE DEL MODULO FUE ACTUALIZADA CORRECTAMENTE";
						$this->action = $this->actionM;
						$this->confirmacion = "1";
						$this->FormaInformacion($informacion);
						return true;
					}
				}
				else
				{
					$this->Campo = "MensajeError";
					$this->frmError["MensajeError"] = "LA VARIABLE ".$this->VariableModulo." YA EXISTE EN EL MODULO ".$_REQUEST['nombre_modulo'];
					$this->FormaAdicionarVariables();
				}
			}
			return true;
	    }
	   /*******************************************************************************
	   * Funcion en la que se pregunta si se desea borrar o no una variable 
	   * 
	   * @return boolean 
	   ********************************************************************************/
	   function EliminarVariables()
	   {
			$this->actionM = ModuloGetURL('system','AdminModulos','user','MostrarVariablesModulos',
										   array("offset"=>$_REQUEST['offset'],"pagina"=>$_REQUEST['pagina'],"nombre_modulo"=>$_REQUEST['nombre_modulo'],
										         "tipo_modulo"=>$_REQUEST['tipo_modulo'],"metodo_retorno"=>$_REQUEST['metodo_retorno']));
			$this->action = ModuloGetURL('system','AdminModulos','user','EliminarVariableBD',
										   array("offset"=>$_REQUEST['offset'],"pagina"=>$_REQUEST['pagina'],"nombre_modulo"=>$_REQUEST['nombre_modulo'],
										         "tipo_modulo"=>$_REQUEST['tipo_modulo'],"nombre_variable"=>$_REQUEST['nombre_variable'],"metodo_retorno"=>$_REQUEST['metodo_retorno']));
			
			$nombre = $_REQUEST['nombre_modulo'];
			if($nombre == "")
			{
				$nombre = "DEL SISTEMA";
			}
			
			$informacion = "ESTA SEGURO QUE DESEA ELIMINAR LA VARIABLE: ".$_REQUEST['nombre_variable']." DEL MODULO: ".$nombre;
			$this->FormaInformacion($informacion);
			
			return true;
	    }
	   /******************************************************************************* 
	   * Funcion en la que se borran las variables del modulo 
	   * 
	   * @return boolean 
	   ********************************************************************************/
	   function EliminarVariableBD()
	   {
	    	$sql .= "DELETE FROM system_modulos_variables ";
			$sql .= "WHERE modulo LIKE '".$_REQUEST['nombre_modulo']."' ";
			$sql .= "AND modulo_tipo LIKE '".$_REQUEST['tipo_modulo']."' ";
			$sql .= "AND variable LIKE '".$_REQUEST['nombre_variable']."' ";
			
			list($dbconn) = GetDBconn();
			
			$result=$dbconn->Execute($sql);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError["MensajeError"] = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				$informacion = "LA VARIABLE ".$_REQUEST['nombre_variable']." DEL MODULO ".$_REQUEST['nombre_modulo']." HA SIDO BORRADA";
				$this->action = ModuloGetURL('system','AdminModulos','user','MostrarVariablesModulos',
										   array("offset"=>$_REQUEST['offset'],"pagina"=>$_REQUEST['pagina'],"nombre_modulo"=>$_REQUEST['nombre_modulo'],
										         "tipo_modulo"=>$_REQUEST['tipo_modulo']));
			
				$this->FormaInformacion($informacion);
				return true;
			}
			return true;
	    }
		/******************************************************************************* 
		* Funcion que permite visualizar en pantalla los usuarios que estan asociados 
		* al menu seleccionado 
		* 
		* @return boolean 
		********************************************************************************/
		function UsuariosModulos()
		{
			$this->action1 = ModuloGetURL('system','AdminModulos','user',$_REQUEST['metodo_retorno'],
										   array("offset"=>$_REQUEST['pagina1'],"pagina"=>$_REQUEST['pagina'],"nombre_modulo"=>$_REQUEST['nombre_modulo'],"tipo_modulo"=>$_REQUEST['tipo_modulo']));
			$this->actionPaginador = ModuloGetURL('system','AdminModulos','user','UsuariosModulos');
			$this->NombreMenu = $_REQUEST['nombre_menu'];
			
			$this->FormaMostrarUsuariosMenus();
			return true;
		}
		/******************************************************************************* 
		* Funcion donde se buscan los dato de los usuarios asociados al menu 
		* seleccionado 
		* 
		* @return array datos de los usuarios asociados al menu 
		********************************************************************************/
		function DatosUsuariosModulos()
		{
			$sqlConteo .= "SELECT COUNT(*) "; 
			$sqlConteo .= "FROM system_usuarios su, system_usuarios_menus um ";
			$sqlConteo .= "WHERE um.menu_id =".$_REQUEST['codigo_menu']." ";
			$sqlConteo .= "AND su.usuario_id = um.usuario_id";
			
			$this->ProcesarSqlConteo($sqlConteo);

			$sql .= "SELECT su.usuario_id,su.usuario,su.nombre ";
			$sql .= "FROM system_usuarios su, system_usuarios_menus um ";
			$sql .= "WHERE um.menu_id =".$_REQUEST['codigo_menu']." ";
			$sql .= "AND su.usuario_id = um.usuario_id ";
			$sql .= "ORDER BY 1 ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;

			
			list($dbconn) = GetDBconn();
			
			$i=0;
			$result=$dbconn->Execute($sql);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError["MensajeError"] = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			while (!$result->EOF)
			{
				$datos[$i] = $result->fields[0]."/".$result->fields[1]."/".$result->fields[2];
				$result->MoveNext();
				$i++;
	    	}
	    	
	    	return $datos;
		}
		/******************************************************************************* 
		* Funcion, donde se inicializan las variables de paginaActual, offset y conteo,
		* importantes a la hora de referenciar al paginador
		* 
		* @param String Cadena que contiene la consulta sql del conteo 
		* @return boolean 
		********************************************************************************/
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
		/*********************************************************************** 
		* Funcion que obtiene los tipos de modulos que se pueden ingresar 
		* 
		* @return array datos de la tabla sysetm_tipos_modulos  
		************************************************************************/		
		function ObtenerTipoModulo()
		{
			list($dbconn) = GetDBconn();
			$consulta = "SELECT modulo_tipo 
					 FROM system_tipos_modulos 
					 WHERE modulo_tipo <> 'hc'";
			$result=$dbconn->Execute($consulta);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError["MensajeError"] = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			
			$i=0;
			while (!$result->EOF)
			{
				$datos[$i]=$result->fields[0];
				$result->MoveNext();
				$i++;
			}
			
			$result->Close();
			return $datos;
		}
		/******************************************************************************* 
		* Funcion para mostrar los menus la primera vez
		* 
		* @return boolean 
		********************************************************************************/
		function MostrarMenus()
		{
			unset($_SESSION['SqlBuscarMenus']);
			unset($_SESSION['SqlContar']);

			$this->retorno = "MostrarMenus";

			$this->consulta = $this->ObtenerSqlMostrarMenus();
			
			$this->actionBuscador = ModuloGetURL('system','AdminModulos','user','BuscarMenus');
			$this->actionPaginador = ModuloGetURL('system','AdminModulos','user','MostrarMenus');
			$this->action1 = ModuloGetURL('system','AdminModulos','user','main');
			
			$this->actionCrear = ModuloGetURL('system','AdminModulos','user','RegistrarMenu',
											   array("pagina"=>$this->paginaActual,"llamado"=>"5",
											   		 "metodo_retorno"=>$this->retorno,"asociado"=>"NO"));			
			
			$this->FormaMostrarMantenimientoMenus();
			return true;
		}
		/*******************************************************************************  
		* Funcion para mostrar los modulos cuando se hace una busqueda 
		* 
		* @return boolean 
		********************************************************************************/
		function BuscarMenus()
		{
			if($_REQUEST['nuevaBusqueda'] == '1')
			{
				unset($_SESSION['SqlBuscarMenus']);
				unset($_SESSION['SqlContar']);
			}
						
			$this->retorno = "BuscarMenus";
			$this->mensaje = "&nbsp;";

			$this->actionBuscador = ModuloGetURL('system','AdminModulos','user','BuscarMenus');
			$this->actionPaginador = $this->actionBuscador;
			$this->action1 = ModuloGetURL('system','AdminModulos','user','main');
			
			$this->consulta = $this->ObtenerSqlMostrarMenus();
			$this->actionCrear = ModuloGetURL('system','AdminModulos','user','RegistrarMenu',
											   array("pagina"=>$this->paginaActual,"llamado"=>"5",
														"metodo_retorno"=>$this->retorno,"asociado"=>"NO"));			

			$this->FormaMostrarMantenimientoMenus();
			return true;
		}
		/**************************************************************************  
		* Funcion que devuelve el sql que muestra los menus y el conteo de 
		* cuantos modulos hay 
		* 
		* @return string sql para la busqueda 
		****************************************************************************/
		function ObtenerSqlMostrarMenus()
		{	
			if(!$_SESSION['SqlContar'])
			{
				$sqlConteo = "SELECT COUNT(*) FROM system_menus ";
				
				if($_REQUEST['cadena_buscar'])
				{
					$sqlConteo .= "WHERE menu_nombre ILIKE '%".$_REQUEST['cadena_buscar']."%' ";
				}				
				$_SESSION['SqlContar'] = $sqlConteo;
    		}
    		else
    		{
    			$sqlConteo = $_SESSION['SqlContar'];
    		}		       		
			
			$this->ProcesarSqlConteo($sqlConteo);
			
			if(!$_SESSION['SqlBuscarMenus']) 
			{
				$sql  = "SELECT sm.menu_id,sm.menu_nombre,sm.descripcion,";
				$sql .= "		  CASE WHEN sm.sw_system = '1' THEN 'SI'";
				$sql .= "		  	   WHEN sm.sw_system = '0' THEN 'NO'";
				$sql .= "		  END AS \"admin\",coalesce(smi.cantidad,'0') ";
				$sql .= "FROM system_menus sm LEFT JOIN ";
				$sql .= "	 (SELECT COUNT(*) AS \"cantidad\",menu_id ";
				$sql .= "	  FROM system_menus_items ";
				$sql .= "	  GROUP BY menu_id) AS \"smi\" ON ";
				$sql .= "(sm.menu_id = smi.menu_id) ";
				
				if($_REQUEST['cadena_buscar'])
				{
					$sql .= "WHERE sm.menu_nombre ILIKE '%".$_REQUEST['cadena_buscar']."%' ";
					$this->mensaje = "El Buscador arrojo el siguiente resultado para la busqueda de la cadena: ".$_REQUEST['cadena_buscar'];
				}

				$_SESSION['SqlBuscarMenus'] = $sql;
			}
			else
			{
				$sql = $_SESSION['SqlBuscarMenus'];
			}
			
			$sql .= "ORDER BY 2 ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			return $sql;
		}
		/******************************************************************************* 
		* Funcion que permite mostrar la informacion de un menu item antes de borrarlo 
		* 
		* @return boolean 
		********************************************************************************/
		function EliminarMenuItem()
		{
			$this->action1 = ModuloGetURL('system','AdminModulos','user','MostrarItemsMenu',
										  $this->ObtenerRequestMenuItem(4));
			$this->action2 = ModuloGetURL('system','AdminModulos','user','EliminarMenuItemBD',
										  $this->ObtenerRequestMenuItem(4));
			
			$sql .= "SELECT titulo,modulo_tipo,modulo,tipo,metodo,descripcion,indice_de_orden "; 
			$sql .= "FROM system_menus_items ";
			$sql .= "WHERE menu_item_id =".$_REQUEST['codigo_menu_item']; 

			list($dbconn) = GetDBconn();
			$result=$dbconn->Execute($sql);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError["MensajeError"] = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			
			if (!$result->EOF)
			{
				$this->TipoMenuItem =$result->fields[3];
				$this->MetodoModulo =$result->fields[4];
				$this->IndiceDeOrden =$result->fields[6];
				$this->TituloMenuItem =$result->fields[0];
				$this->ModuloTipoAsoc =$result->fields[1];
				$this->ModuloAsociado =$result->fields[2];
				$this->DescripcionMenuItem =$result->fields[5];
				$this->NombreMenu =$_REQUEST['nombre_menu'];
				$result->MoveNext();
				$i++;
			}

			$this->FormaMostrarInfoMenuItem();
			return true;
		}
		/******************************************************************************* 
		* Funcion donde se elimina el item de menu en la base de datos 
		* 
		* @return boolean 
		********************************************************************************/
		function EliminarMenuItemBD()
		{
			$sql .= "DELETE FROM system_menus_items ";
			$sql .= "WHERE menu_item_id = ".$_REQUEST['codigo_menu_item'];
			
			list($dbconn) = GetDBconn();
			$result=$dbconn->Execute($sql);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError["MensajeError"] = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			
			$informacion = "EL ITEM DE MENÚ FUE ELIMINADO CORRECTAMENTE";
			$this->action = ModuloGetURL('system','AdminModulos','user','MostrarItemsMenu',
										  $this->ObtenerRequestMenuItem(5));
			$this->FormaInformacion($informacion);
			return true;
		}
		/******************************************************************************* 
		* Funcion que consulta en la base ce datos los modulos que existen para ser 
		* asociados a un item de menu 
		* 
		* @return array datos de los modulos 
		********************************************************************************/
		function ObtenerModulosSistema()
		{
			$sql .= "SELECT modulo_tipo,modulo ";
			$sql .= "FROM system_modulos ";
			$sql .= "WHERE modulo NOT LIKE '' ";
			$sql .= "AND modulo_tipo NOT LIKE '' ";
			$sql .= "AND modulo_tipo NOT LIKE 'hc' ";
			$sql .= "ORDER BY 1,2";

			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			while (!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
				$i++;
			}
			
			$rst->Close();
			return $datos;
		}		
		/******************************************************************************* 
		* Funcion que permite obtener el LimitRowBrowser del usuario 
		* 
		* @return bolean 
		********************************************************************************/
		function ObtenerLimite()
		{
			$uid = UserGetUID();
      $this->limit = UserGetVar($uid,'LimitRowsBrowser');
      return true;
		}
		/***********************************************************************************
		* Funcion que obtiene los datos que deben ir en el request del menu 
		* 
		* @return array datos del request 
		************************************************************************************/
		function ObtenerDatosRequestMenu($opc)
		{
			$arreglo = array("tipo_modulo"=>$_REQUEST['tipo_modulo'],
							 "nombre_modulo"=>$_REQUEST['nombre_modulo']);
			switch($opc)
			{
				case 0:
					$arreglo["offset"] = $_REQUEST['pagina'];
				break;
				case 1:
					$arreglo["offset"] = $_REQUEST['offset'];
					$arreglo["pagina"] = $_REQUEST['pagina'];
			    	$arreglo["codigo_menu"] = $_REQUEST[ 'codigo_menu'];
			    	$arreglo["tipo_modulo"]= $_REQUEST['tipo_modulo'];
			    	$arreglo["metodo_retorno"] = $_REQUEST['metodo_retorno'];
				break;
			}
			return $arreglo;
		
		}
		/********************************************************************************* 
		* Funcion donde se obtiene el action que se necesita segun la parte desde donde 
		* se llama a la funcion RegistrarMenuItem 
		**********************************************************************************/
		function ObtenerActionRegistrarMenuItem($opc)
		{
			switch($opc)
			{
				case '0':
						$this->actionV = ModuloGetURL('system','AdminModulos','user','main');
				break;
				case '1':
						$this->actionV = ModuloGetURL('system','AdminModulos','user','MostrarModulos',
													   array("offset"=>$_REQUEST['offset']));
				break;
				case '2':
						$this->actionV = ModuloGetURL('system','AdminModulos','user','MantenimientoMenu',
										   			   $this->ObtenerRequestMenuItem(2));
				break;
				case '3':
						$this->actionV = ModuloGetURL('system','AdminModulos','user','MostrarItemsMenu',
													   $this->ObtenerRequestMenuItem(3));
				break;
				case '5':
						$this->actionV = ModuloGetURL('system','AdminModulos','user',$_REQUEST['metodo_retorono'],
													  $this->ObtenerRequestMenuItem(3));
				break;
			}
		} 
		/***********************************************************************************
		* Funcion que obtiene los datos que deben ir en el request del menu item 
		* 
		* @return array datos del request 
		************************************************************************************/
		function ObtenerRequestMenuItem($opc)
		{
			$arreglo = array("nombre_modulo"=>$_REQUEST['nombre_modulo'],
	    					 "tipo_modulo"=>$_REQUEST['tipo_modulo'],
	    					 "nombre_menu"=>$_REQUEST['nombre_menu'],
	    					 "codigo_menu"=>$_REQUEST['codigo_menu'],
	    					 "codigo_menu_item"=>$_REQUEST['codigo_menu_item']);
			switch($opc)
			{
				case 0:
					$arreglo["offset"] = $_REQUEST['pagina'];
				break;
				case 1:
					$arreglo["llamado"]='3';
					$arreglo["offset"] =$_REQUEST['offset'];
					$arreglo["pagina"] = $_REQUEST['pagina'];
					$arreglo["asociado"] = $_REQUEST['asociado'];
					$arreglo["metodo_retorno"] = $_REQUEST['metodo_retorno'];
				break;
				case 2:
					$arreglo["offset"] =$_REQUEST['offset'];
				break;
				case 3:
					$arreglo["offset"] =$_REQUEST['offset'];
					$arreglo["pagina1"]=$_REQUEST['pagina1'];
					$arreglo["pagina"] = $_REQUEST['pagina'];
					$arreglo["asociado"] = $_REQUEST['asociado'];
					$arreglo["metodo_retorno"] = $_REQUEST['metodo_retorno'];
				break;
				case 4:
					$arreglo["offset"] =$_REQUEST['pagina'];
					$arreglo["metodo_retorno"] = $_REQUEST['metodo_retorno'];
				break;
				case 5:
					$arreglo["offset"] = $_REQUEST['offset'];
					$arreglo["pagina"] = $_REQUEST['pagina'];
				break;
			}
			return $arreglo;
		}
		/***************************************************************************************
		* Funcion que pèrmite mostrar la forma de mostrar submodulos
		* 
		* @return boolean 
		****************************************************************************************/
		function MostrarSubmodulos()
		{
			$this->Submodulos = $this->ObtenerSubmodulos();
			
			$this->action1 = ModuloGetURL('system','AdminModulos','user','main');
			$this->action2 = ModuloGetURL('system','AdminModulos','user','MostrarSubmodulos');
			
			$this->Modulos = $_SESSION['modulos'];
			
			$this->FormaMostrarSubmodulos();
			return true;
		}
		/***************************************************************************************
		* Funcion que permite buscar los submodulos de historia clinica en la base de datos 
		* 
		* @return array
		****************************************************************************************/
		function ObtenerSubmodulos()
		{
			$where .= "FROM	system_hc_submodulos ";
			
			if($_REQUEST['nuevaBusqueda'] == "1")
			{
				unset($_SESSION['cadena']);
			}

			if($_SESSION['cadena'] != "")
			{
				$where .= "WHERE submodulo ILIKE '%".$_SESSION['cadena']."%' ";
				$this->mensaje = "La busqueda de la cadena: ".$_SESSION['cadena']." arrojo el siguiente resultado:";
			}
			
			if($_REQUEST['busqueda'] != "")
			{
				$where .= "WHERE submodulo ILIKE '%".$_REQUEST['busqueda']."%' ";
				$this->mensaje = "La busqueda de la cadena: ".$_REQUEST['busqueda']." arrojo el siguiente resultado:";
				$_SESSION['cadena'] = $_REQUEST['busqueda'];
			}
			
			
			$sqlCont  = "SELECT COUNT(*) ";
			$sqlCont .= $where;
			
			$this->ProcesarSqlConteo($sqlCont);
			
			$sql  = "SELECT submodulo, ";
			$sql .= "		descripcion,";
			$sql .= "		version_numero,";
			$sql .= "		CASE WHEN activo = '1' THEN 'ACTIVO' ";
			$sql .= "		ELSE 'INACTIVO' END ";
			$sql .= $where;
			$sql .= "ORDER BY 1 ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
						
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

			$i=0;
			while (!$rst->EOF)
			{
				$datos[$i]=$rst->fields[0]."*".$rst->fields[1]."*".$rst->fields[2]."*".$rst->fields[3];
				$rst->MoveNext();
				$i++;
			}
			
			return $datos;
		}
		/***************************************************************************************
		* Funcion que averigua la informacion de los modulos que se han subido, por medio de un 
		* webservices
		* 
		* @return boolean 
		****************************************************************************************/
		function ObtenerInformacionWebservices()
		{
			$usuario = ModuloGetVar('system','AdminModulos','usuario');
			$password = ModuloGetVar('system','AdminModulos','password');
			$cliente_id = ModuloGetVar('system','AdminModulos','cliente_id');
			
			$param = array( 'ProductoId' => '1',
							'User' => $usuario,
							'Pass'=>$password,
							'ClienteId'=>$cliente_id);
			
			$result=$this->Nusoap->call('GetModulos',$param);
			if ($sError = $this->Nusoap->getError())
			{ 
				$this->Error=1;
				echo $this->MsjError=$sError;
				return false;
			} 
			$result = unserialize($result);
			
			foreach($result as $valor)
			{
				$modulos[$valor['tipo']][$valor['modulo_id']]=$valor;
			}
			
			$_SESSION['modulos'] = $modulos;
			
			return true;
		}
		/***************************************************************************************
		* Funcion que permite desplegar la informacion del modulo, que se toma del webservices
		*
		* @return boolean
		****************************************************************************************/
		function ActualizarModulos()
		{
			$this->Version = $_REQUEST['version'];
			$this->Modulo_id = $_REQUEST['nombre_modulo'];
			$this->Tipo_Modulo = $_REQUEST['tipo_modulo'];
			
			$this->Pagina = $_REQUEST['pagina'];
			$this->Retorno = $_REQUEST['metodo_retorno'];
			$this->Informacion = $this->ObtenerInfoModuloWS();
			
			if(sizeof($this->Informacion) > 0)
			{
				$this->action1 = ModuloGetURL('system','AdminModulos','user',$this->Retorno,array("offset"=>$this->Pagina));
						
				$this->FormaActualizarModulos();
			}
			else
			{
				switch($this->Tipo_Modulo)
				{
					case 'hc':
						$this->MostrarSubmodulos();
					break;
					default:
						$this->MostrarModulos();
					break;
				}
			}
			return true;
		}
		/***************************************************************************************
		* Obtiene la informacion del modulo seleccionado para desplegarlo al usuario
		*
		* @return arry datos del modulo
		****************************************************************************************/
		function ObtenerInfoModuloWS()
		{
			$usuario = ModuloGetVar('system','AdminModulos','usuario');
			$password = ModuloGetVar('system','AdminModulos','password');
			$cliente_id = ModuloGetVar('system','AdminModulos','cliente_id');
			
			$param = array('ProductoId' => '1',
						   'User' => $usuario,
						   'Pass'=>$password,
						   'ModuloId'=>$this->Modulo_id,
						   'TipoModulo'=>$this->Tipo_Modulo,
						   'Version'=>$this->Version,
						   'ClienteId'=>$cliente_id);
			
			$result=$this->Nusoap->call('GetActualizacionesModulo',$param);
			if ($sError = $this->Nusoap->getError())
			{ 
				$this->Error=1;
				echo $this->MsjError=$sError;
				return false;
			} 
			$result = unserialize($result);				
			
			return $result;
		}
		/***************************************************************************************
		* Funcion que permite descargar el modulo y actualizar en la base de datos la descarga 
		* hecha
		*
		* @return boolean
		***************************************************************************************/
		function DescargarModulo()
		{
			$this->Version = $_REQUEST['version'];
			$this->VersionS = $_REQUEST['version_s'];
			$this->Modulo_id = $_REQUEST['nombre_modulo'];
			$this->Tipo_Modulo = $_REQUEST['tipo_modulo'];
			
			$this->Pagina = $_REQUEST['pagina'];
			$this->Retorno = $_REQUEST['metodo_retorno'];
			
			if(!$this->ObtenerArchivoWS())
				return false;
			
			$sql .= "INSERT INTO system_modulos_actualizaciones ";
			$sql .= "		(modulo,modulo_tipo,fecha_descarga,version)";
			$sql .= "VALUES ('".$this->Modulo_id."', ";
			$sql .= "		 '".$this->Tipo_Modulo."', ";
			$sql .= "		  NOW(), ";
			$sql .= "		  ".$this->VersionS.")";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

			$this->action = ModuloGetURL('system','AdminModulos','user','ActualizarModulos',
										  array("nombre_modulo"=>$this->Modulo_id,"tipo_modulo"=>$this->Tipo_Modulo,
											 	"pagina"=>$this->Pagina,"metodo_retorno"=>$this->Retorno,"version"=>$this->Version));
			
			$informacion = "EL PAQUETE: ".$this->Tipo_Modulo."_".$this->Modulo_id." 
							HA SIDO DESCARGADO Y GUARDADO EN LA SIGUIENTE RUTA: ".$this->Ruta;
			$this->FormaInformacion($informacion);
			return true;
		}
		/***************************************************************************************
		* Funcion que permite descargar el archivo del webservices, decodificarlo y gaurdarlo en 
		* una ruta dada por una variable de modulo
		* Si el archivo, no se puede crear devuelve falso, en caso contrario verdarero
		*
		* @return boolean 
		****************************************************************************************/
		function ObtenerArchivoWS()
		{
			$usuario = ModuloGetVar('system','AdminModulos','usuario');
			$password = ModuloGetVar('system','AdminModulos','password');
			$cliente_id = ModuloGetVar('system','AdminModulos','cliente_id');

			$param = array(	'ProductoId' => '1',
							'User' => $usuario,
							'Pass'=>$password,
							'ModuloId'=>$this->Modulo_id,
							'TipoModulo'=>$this->Tipo_Modulo,
							'Version'=>$this->VersionS,
							'ClienteId'=>$cliente_id);
			
			$datos = $this->Nusoap->call('GetPaquete',$param);
			if ($sError = $this->Nusoap->getError())
			{ 
				$this->Error=1;
				echo $this->MsjError=$sError;
				return false;
			}
			
			$vrs = explode(".",$this->VersionS);

			if($vrs[1] <= 0)
			{
				$versiones = $vrs[0];	
			}
			else
			{
				$versiones = $this->VersionS;
			}
			
			$descargas = ModuloGetVar('system','AdminModulos','descargas');
			
			$archivo = base64_decode($datos);
			$ruta = $descargas.$this->Tipo_Modulo."_".$this->Modulo_id."_".$versiones.".tar.gz";
			
			if(!$files = fopen($ruta,"w"))
			{
				$this->frmError['MensajeError'] = "LA RUTA ESPECIFICADA NO EXISTE";
				return false;
			}
				
			fwrite($files,$archivo);
			fclose($files);
			
			$this->Ruta = $ruta;
			return true;
		}
		/***************************************************************************************
		* Funcion que obtiene la informacion del modulo o el submodulo solicitado, para saber 
		* si ya fue descargado
		*
		* @params	string 	Identificador del modulo o submodulo
		*			string 	Tipo de modulo o submodulo
		*			int		Version del modulo o submodulo 
		*
		* @return	string	Datos de si ya se ha bajado o no 
		****************************************************************************************/
		function ObtenerInstalacion($modulo,$tipo,$version)
		{
			$sql .= "SELECT sw_instalacion ";
			$sql .= "FROM 	system_modulos_actualizaciones ";
			$sql .= "WHERE 	modulo = '".$modulo."' ";
			$sql .= "AND  	modulo_tipo = '".$tipo."' ";
			$sql .= "AND  	sw_instalacion = '0' ";
			$sql .= "AND  	version = ".$version." ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			if (!$rst->EOF)
			{
				$datos = $rst->fields[0];
				$rst->MoveNext();
			}
			
			return $datos;
		}
		/***************************************************************************************
		* Funcion que permite desplegar una confirmacion por parte del usuario para poder 
		* actualizar la version del modulo o el submodulo en la base de datos
		*
		* @return boolean
		***************************************************************************************/
		function InstalarModulo()
		{
			$this->Version = $_REQUEST['version'];
			$this->VersionS = $_REQUEST['version_s'];
			$this->Modulo_id = $_REQUEST['nombre_modulo'];
			$this->Tipo_Modulo = $_REQUEST['tipo_modulo'];
			
			$this->Pagina = $_REQUEST['pagina'];
			$this->Retorno = $_REQUEST['metodo_retorno'];
			
			$this->action = ModuloGetURL('system','AdminModulos','user','InstalarModuloBD',
										  array("nombre_modulo"=>$this->Modulo_id,"tipo_modulo"=>$this->Tipo_Modulo,
											 	"pagina"=>$this->Pagina,"metodo_retorno"=>$this->Retorno,"version"=>$this->VersionS));
			
			$this->actionM = ModuloGetURL('system','AdminModulos','user','ActualizarModulos',
										   array("nombre_modulo"=>$this->Modulo_id,"tipo_modulo"=>$this->Tipo_Modulo,
											 	 "pagina"=>$this->Pagina,"metodo_retorno"=>$this->Retorno,"version"=>$this->Version));
											 	 
			$informacion = "<br>EL PAQUETE: ".$this->Tipo_Modulo."_".$this->Modulo_id."
							HA SIDO INSTALADO CAORRECTAMENTE?<br> ";
			$this->FormaInformacion($informacion);
			return true;
		}
		/***************************************************************************************
		* Funcion que pèrmite Actualizar en la base de datos las versiones del modulo y 
		* el submodulo despues de recibir la confirmacion del usuario
		*
		* @return boolean
		****************************************************************************************/
		function InstalarModuloBD()
		{
			$this->Version = $_REQUEST['version'];
			$this->Modulo_id = $_REQUEST['nombre_modulo'];
			$this->Tipo_Modulo = $_REQUEST['tipo_modulo'];
			
			$this->Pagina = $_REQUEST['pagina'];
			$this->Retorno = $_REQUEST['metodo_retorno'];
			
			switch($this->Tipo_Modulo)
			{
				case 'hc':
					$sql .= "UPDATE system_hc_submodulos ";
					$sql .= "SET 	version_numero=".$this->Version." ";
					$sql .= "WHERE 	submodulo = '".$this->Modulo_id."'; ";
				break;
				default:
					$sql .= "UPDATE system_modulos ";
					$sql .= "SET 	version_numero=".$this->Version." ";
					$sql .= "WHERE 	modulo = '".$this->Modulo_id."' ";
					$sql .= "AND  	modulo_tipo = '".$this->Tipo_Modulo."'; ";
				break;
			}
			$sql .= "UPDATE system_modulos_actualizaciones ";
			$sql .= "SET 	sw_instalacion = '1', ";
			$sql .= "	 	fecha_instalacion = NOW() ";
			$sql .= "WHERE 	modulo = '".$this->Modulo_id."' ";
			$sql .= "AND  	modulo_tipo = '".$this->Tipo_Modulo."' ";
			$sql .= "AND  	sw_instalacion = '0'; ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$this->ActualizarModulos();
			return true;
		}
		/***************************************************************************************
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la consulta sql 
		* 
		* @param string sentencia sql a ejecutar 
		* @return rst 
		****************************************************************************************/
		function ConexionBaseDatos($sql)
		{
			list($dbconn)=GetDBConn();
      $dbconn->debug = false;
			$rst = $dbconn->Execute($sql);
				
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				return false;
			}
			
			return $rst;
		}
	}
?>