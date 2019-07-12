 <?php
	/**************************************************************************************
	* $Id: app_AdmisionHospitalizacion_user.php,v 1.37 2006/10/19 13:16:35 hugo Exp $
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* $Revision: 1.37 $ 	
	* @author Hugo Freddy Manrique Arango
	*
	* Codigo tomado del modulo de Triage.
	***************************************************************************************/
	class app_AdmisionHospitalizacion_user extends classModulo 
	{
		function app_AdmisionHospitalizacion_user()
		{
			$this->frmError=array();
			return true;
		}
		/**************************************************************************************
		* Funcion principal donde se crea el menu de entrada con la empresa, el centro de 
		* utilidad y el deparatamento
		* 
		* @return boolean
		***************************************************************************************/
		function main()
		{	
			unset($_SESSION['AdmHospitalizacion']);
			$url[0]='app';
			$url[1]='AdmisionHospitalizacion';
			$url[2]='user';
			$url[3]='MenuHospitalizacion';
			$url[4]='Hosp';
			$arreglo[0]='EMPRESA';
			$arreglo[1]='CENTRO UTILIDAD';
			$arreglo[2]='UNIDAD FUNCIONAL';
			$arreglo[3]='DEPARTAMENTO';
			$arreglo[4]='ADMISIONES';
			$this->BuscarPermisos();
		
			$_SESSION['AdmHospitalizacion']['puntos']=$this->seguridad;
			
			$forma = gui_theme_menu_acceso('ADMISIONES',$arreglo,$this->admon,$url,ModuloGetURL('system','Menu'));
			$this->FormaMostrarMenuHospitalizacion($forma);
			return true;
		}
		/**************************************************************************************
		* Busca los puntos de admision de hospitalizacion a los que tiene permiso el usuario
		* 
		* @return boolean
		***************************************************************************************/
		function BuscarPermisos()
		{
			$sql = "SELECT	PA.tipo_admision_id, 
											PA.descripcion AS descripcion5, 
											DP.empresa_id,
											DP.centro_utilidad, 
											EM.razon_social AS descripcion1,
											CU.descripcion AS descripcion2, 
											PA.tipo_admision_id,
											PA.punto_admision_id, 
											PA.sw_triage, 
											PA.departamento, 
											DP.descripcion AS descripcion4,
											UF.unidad_funcional, 
											UF.descripcion AS descripcion3, 
											PA.sw_soat, 
											DP.servicio
							FROM		puntos_admisiones_usuarios PU, 
											puntos_admisiones PA,
											departamentos DP, 
											empresas EM, 
											centros_utilidad CU,
											unidades_funcionales UF
							WHERE		PU.usuario_id = ".UserGetUID()."
							AND 		PA.tipo_admision_id IN ('HS','UR')
							AND 		PU.punto_admision_id = PA.punto_admision_id 
							AND 		PA.departamento = DP.departamento
							AND 		EM.empresa_id = DP.empresa_id 
							AND	 		DP.empresa_id = CU.empresa_id
							AND 		DP.centro_utilidad = CU.centro_utilidad 
							AND 		CU.empresa_id = UF.empresa_id
							AND 		CU.centro_utilidad = UF.centro_utilidad 
							AND 		DP.unidad_funcional = UF.unidad_funcional
							ORDER BY 3,4,12";

			list($dbconn) = GetDBconn();
			GLOBAL $ADODB_FETCH_MODE;
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
						
			$rst = $dbconn->Execute($sql);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0) 
			{
				$this->error = "Error al Guardar en la Base de Datos";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			while ($data = $rst->FetchRow()) 
			{
				$this->admon[$data['descripcion1']][$data['descripcion2']][$data['descripcion3']][$data['descripcion4']][$data['descripcion5']]=$data;
				$this->seguridad[$data['empresa_id']][$data['centro_utilidad']][$data['unidad_funcional']][$data['departamento']][$data['punto_admision_id']]=1;
			}
			return true;
		}
		/**************************************************************************************
		* Llama la forma del menu de admisiones
		* 
		* @return boolean
		***************************************************************************************/
		function MenuHospitalizacion()
		{
			unset($_SESSION['AdmHospitalizacion']['menu']);
			unset($_SESSION['AdmHospitalizacion']['tipoorden']);
			unset($_SESSION['AdmHospitalizacion']['orden1']);
			
			if(empty($_SESSION['AdmHospitalizacion']['empresa']))
			{
				if(empty($_SESSION['AdmHospitalizacion']['puntos'][$_REQUEST['Hosp']['empresa_id']][$_REQUEST['Hosp']['centro_utilidad']][$_REQUEST['Hosp']['unidad_funcional']][$_REQUEST['Hosp']['departamento']][$_REQUEST['Hosp']['punto_admision_id']]))
				{
					$this->error = "Error de Seguridad.";
					$this->mensajeDeError = "VIOLACION DE SEGURIDAD";
					return false;
				}		
				
				$_SESSION['AdmHospitalizacion']['ctrutilidad'] = $_REQUEST['Hosp']['centro_utilidad'];
				$_SESSION['AdmHospitalizacion']['ufuncional'] = $_REQUEST['Hosp']['unidad_funcional'];
				$_SESSION['AdmHospitalizacion']['tipoadmon'] = $_REQUEST['Hosp']['tipo_admision_id'];
				$_SESSION['AdmHospitalizacion']['ptoadmon'] = $_REQUEST['Hosp']['punto_admision_id'];
				$_SESSION['AdmHospitalizacion']['swtriage'] = $_REQUEST['Hosp']['sw_triage'];
				$_SESSION['AdmHospitalizacion']['servicio'] = $_REQUEST['Hosp']['servicio'];
				$_SESSION['AdmHospitalizacion']['empresa'] = $_REQUEST['Hosp']['empresa_id'];
				$_SESSION['AdmHospitalizacion']['deptno'] = $_REQUEST['Hosp']['departamento'];
				$_SESSION['AdmHospitalizacion']['swsoat'] = $_REQUEST['Hosp']['sw_soat'];
				
				$_SESSION['AdmHospitalizacion']['tipo']='HOSPITALIZACION';
			}
			
			$this->triage = $_SESSION['AdmHospitalizacion']['swtriage'];
				
			$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','main');
			$this->FormaMenuHospitalizacion();       
			return true;
		}
		/**************************************************************************************
		* Funcion que permite mostrar la lista de pacientes pendientes por hospitalizar
		* 
		* @return boolean
		***************************************************************************************/
		function ListadoAdmisionHospitalizacion()
		{
			$this->PacienteDocumento = $_REQUEST['documento'];
			$this->PacienteTipoId = $_REQUEST['tipo_id'];
			$this->NumeroOrden = $_REQUEST['numero_orden'];
			
			$this->actionB = ModuloGetURL('app','AdmisionHospitalizacion','user','BuscarAdmisionHospitalizacion');
			$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','MenuHospitalizacion');
			$this->action0 = ModuloGetURL('app','AdmisionHospitalizacion','user','ListadoAdmisionHospitalizacion');
			$this->FormaListadoAdmisionHospitalizacion();
			return true;
		}
		/*************************************************************************************
		*
		**************************************************************************************/
		function BuscarAdmisionHospitalizacion()
		{
			$this->paso = 1;
			$this->PacienteDocumento = $_REQUEST['documento'];
			$this->PacienteTipoId = $_REQUEST['tipo_id'];
			$this->NumeroOrden = $_REQUEST['numero_orden'];

			if($this->PacienteDocumento == "" && $this->NumeroOrden == "" &&
				($this->PacienteTipoId == "0" || $this->PacienteTipoId == "") )
			{
				$this->Ordenes = $this->BuscarCompletoH();
			}
			else if($this->PacienteDocumento != "" && $this->PacienteTipoId != "0")
				{
					$this->Ordenes = $this->BuscarH1();
				}
				else if ($this->NumeroOrden != "")
					{
						$this->Ordenes = $this->BuscarH4();
					}
			
			$this->ListadoAdmisionHospitalizacion();
			return true;
		}
		/************************************************************************************ 
		* Funcion domde se seleccionan los tipos de id de los terceros 
		* 
		* @return array datos de tipo_id_terceros 
		*************************************************************************************/
		function ObtenerTipoId()
		{
			$sql  = "SELECT tipo_id_tercero,descripcion FROM tipo_id_terceros";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
				
			$i = 0;
			while (!$rst->EOF)
			{
				$documentos[$i] = $rst->fields[0]."/".$rst->fields[1];
				$rst->MoveNext();
				$i++;
				}
			$rst->Close();
			
			return $documentos;
		}
		/************************************************************************************
		* En esta funcion se supone que solo se muestran las ordenes internas y no las de
		* traslado?
		*************************************************************************************/
		function BuscarCompletoH()
		{
			$sql .= "SELECT	A.departamento,";
			$sql .= "				A.orden_hospitalizacion_id,"; 
			$sql .= " 			A.tipo_orden_id, ";
			$sql .= " 			A.fecha_orden, ";
			$sql .= " 			D.descripcion,";
			$sql .= "       A.fecha_programacion,";
			$sql .= "       A.tipo_id_paciente,"; 
			$sql .= "       A.paciente_id,";
			$sql .= "       B.primer_nombre||' '||B.segundo_nombre||' '||B.primer_apellido||' '||B.segundo_apellido as completo,";
			$sql .= "       C.historia_numero, ";
			$sql .= "       C.historia_prefijo ";            
			
			$where .= "FROM	ordenes_hospitalizacion A,"; 
			$where .= "		 	pacientes B, ";
			$where .= "		 	historias_clinicas C,";
			$where .= "     tipos_orden D ";
			$where .= "WHERE A.hospitalizado ='0' ";
			//Cambio !! 
			$where .= "AND 	 A.tipo_orden_id = 1 ";
			$where .= "AND	 A.tipo_id_paciente = B.tipo_id_paciente ";
			$where .= "AND	 A.paciente_id = B.paciente_id ";
			$where .= "AND 	 A.tipo_id_paciente = C.tipo_id_paciente ";
			$where .= "AND	 A.paciente_id = C.paciente_id ";
			$where .= "AND	 A.tipo_orden_id=D.tipo_orden_id ";
					
			$sqlCont = "SELECT COUNT(*) ".$where;
			if(!$this->ProcesarSqlConteo($sqlCont))
				return false;
					
			$sql .= $where;
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
											
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
					
			while(!$rst->EOF)
			{
				$vars[]=$rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
					
			$rst->Close();
					
			return $vars;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function BuscarH1()
		{
			$sql .= "SELECT	A.departamento,
											A.orden_hospitalizacion_id, 
											A.tipo_orden_id, 
											A.fecha_orden, 
											D.descripcion,
											A.fecha_programacion,
											A.tipo_id_paciente, 
											A.paciente_id,
											B.primer_nombre||' '||B.segundo_nombre||' '||B.primer_apellido||' '||B.segundo_apellido as completo,
											C.historia_numero, 
											C.historia_prefijo ";			
			
			$where = "FROM	ordenes_hospitalizacion A, 
											pacientes B, 
											historias_clinicas C,
											tipos_orden D
								WHERE A.hospitalizado = '0' 
								AND		A.tipo_orden_id != 0 
								AND		A.tipo_id_paciente = '".$this->PacienteDocumento."' 
								AND		A.paciente_id = '".$this->PacienteTipoId."'
								AND		A.tipo_id_paciente = B.tipo_id_paciente 
								AND		A.paciente_id = B.paciente_id
								AND		A.tipo_id_paciente = C.tipo_id_paciente 
								AND		A.paciente_id = C.paciente_id
								AND		A.tipo_orden_id = D.tipo_orden_id ";

			$sqlCont = "SELECT COUNT(*) ".$where;
			if(!$this->ProcesarSqlConteo($sqlCont))
					return false;

			$sql .= $where;
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;

			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
						
			while(!$rst->EOF)
			{
				$vars[]=$rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
						
			return $vars;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function BuscarH4()
		{
			$sql .= "SELECT	A.departamento,
											A.orden_hospitalizacion_id, 
											A.tipo_orden_id, 
											A.fecha_orden,
											A.fecha_programacion,
											A.tipo_id_paciente, 
											A.paciente_id,
											B.primer_nombre||' '||B.segundo_nombre||' '||B.primer_apellido||' '||B.segundo_apellido as completo,
											C.historia_numero, 
											C.historia_prefijo, 
											D.descripcion ";
			$where .= "	FROM	ordenes_hospitalizacion A, 
												pacientes B, 
												historias_clinicas C,
												tipos_orden D
									WHERE A.hospitalizado = '0' 
									AND	 A.tipo_orden_id != 0 
									AND	 A.orden_hospitalizacion_id = ".$this->NumeroOrden."
									AND	 A.tipo_id_paciente = B.tipo_id_paciente 
									AND	 A.paciente_id = B.paciente_id
									AND	 A.tipo_id_paciente = C.tipo_id_paciente 
									AND	 A.paciente_id = C.paciente_id
									AND	 A.tipo_orden_id = D.tipo_orden_id ";
						
			$sqlCont = "SELECT COUNT(*) ".$where;
			if(!$this->ProcesarSqlConteo($sqlCont))
				return false;
												
			$sql .= $where;
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
						
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
						
			while(!$rst->EOF)
			{
				$vars[]=$rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
						
			return $vars;
		}
		/************************************************************************************
		* Valida si el paciente tiene una cuenta abierta o si tiene ingreso o no
		*************************************************************************************/
		function VerificarDatosHospitalizacion()
		{	
			$EmpresaId = $_SESSION['AdmHospitalizacion']['empresa'];
			$ordenes = $_REQUEST['datos'];

			$this->PacienteId = $ordenes[paciente_id];
			$this->TipoId = $ordenes[tipo_id_paciente];
			$Orden = $ordenes[orden_hospitalizacion_id];

			$_SESSION['AdmHospitalizacion']['dptoest'] = $ordenes[departamento];
			$_SESSION['AdmHospitalizacion']['tipoorden'] = $ordenes[descripcion];
						
			$sql .= "SELECT	paciente_fallecido,";
			$sql .= "				primer_nombre,";
			$sql .= "				segundo_nombre,";
			$sql .= "       primer_apellido,";
			$sql .= "       segundo_apellido,";
			$sql .= "       tipo_id_paciente,";
			$sql .= "       paciente_id ";
			$sql .= "FROM		pacientes ";
			$sql .= "WHERE	paciente_id='".$this->PacienteId."' ";
			$sql .= "AND		tipo_id_paciente ='".$this->TipoId."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
		
			$vars = $rst->GetRowAssoc($ToUpper = false);

			if($vars[paciente_fallecido])
			{
				$accion = "";
				$this->FormaMensajePacienteFallecido($vars,$accion);
				return true;
			}

			$sql  = "SELECT	A.ingreso, ";
			$sql .= "				C.numerodecuenta,"; 
			$sql .= "				C.plan_id ";
			$sql .= "FROM		ingresos A, ";
			$sql .= "				cuentas C ";
			$sql .= "WHERE 	A.estado = 1 "; 
			$sql .= "AND		A.paciente_id = '".$this->PacienteId."' ";
			$sql .= "AND		A.tipo_id_paciente ='".$this->TipoId."' ";
			$sql .= "AND		A.ingreso = C.ingreso  ";
			$sql .= "AND		C.empresa_id = '".$EmpresaId."' ";
			$sql .= "AND		C.estado = 1 ";
										
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
	
			$_SESSION['AdmHospitalizacion']['orden'] = $Orden;
			
			if(!$rst->EOF)
			{
				$Ingreso = $rst->fields[0];
				$Plan = $rst->fields[2];
								
				$_SESSION['AdmHospitalizacion']['plan'] = $Plan;
				$_SESSION['AdmHospitalizacion']['paciente']['plan_id'] = $Plan;
				$_SESSION['AdmHospitalizacion']['paciente']['ingreso'] = $Ingreso;
				$_SESSION['AdmHospitalizacion']['paciente']['paciente_id'] = $this->PacienteId;
				$_SESSION['AdmHospitalizacion']['paciente']['tipo_id_paciente'] = $this->TipoId;
																										
				$this->AutorizarPaciente();
								return true;
			}//no hay ingreso
			else
			{
				$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','ValidarPacienteHospitalizacion',
																			array('TipoId'=>$this->TipoId,'PacienteId'=>$this->PacienteId));
				$this->action2 = ModuloGetURL('app','AdmisionHospitalizacion','user','ListadoAdmisionHospitalizacion');
				
				$this->FormaRegistroResponsable();
				return true;
			}   
		}
		/************************************************************************************
		* 
		* 
		* @return boolean
		*************************************************************************************/
		function ValidarPacienteHospitalizacion()
		{
			$this->TipoDocumento = $_REQUEST['TipoId'];
			$this->Documento = trim($_REQUEST['PacienteId']);
			$this->Plan = $_REQUEST['Plan'];

			if($this->Plan==-1)
			{
				if($this->Plan==-1)
				{ 
					$this->frmError["MensajeError"]= "POR FAVOR SELECCIONAR RESPONSABLE"; 
				}
				
				$this->FormaRegistroResponsable();
				return true;
			}
			
			$this->AutorizarPaciente();
			return true;
		}
		/************************************************************************************
		* Llama el modulo de autorizaciones
		* @access public
		* @return boolean
		* @param string tipo de documento
		* @param int numero de documento
		* @param int plan_id
		*************************************************************************************/
		function AutorizarPaciente($td = null,$doc= null,$plan= null)
		{
			unset($_SESSION['AUTORIZACIONES']);
			
			if(!empty($_SESSION['AdmHospitalizacion']['paciente']['paciente_id'])
						AND !empty($_SESSION['AdmHospitalizacion']['paciente']['tipo_id_paciente']))
			{
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'] = $_SESSION['AdmHospitalizacion']['paciente']['plan_id'];
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id'] = $_SESSION['AdmHospitalizacion']['paciente']['paciente_id'];
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente'] = $_SESSION['AdmHospitalizacion']['paciente']['tipo_id_paciente'];
			}
			else
			{
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']=$this->Plan;
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id']=$this->Documento;
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente']=$this->TipoDocumento;
			}
			
			if(empty($_SESSION['AdmHospitalizacion']['protocolo']))
			{
				$sql  = "SELECT	protocolos ";
				$sql .= "FROM 	planes ";
				$sql .= "WHERE	plan_id = ".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']."";
				
				if(!$rst = $this->ConexionBaseDatos($sql))
					return false;
								
				$_SESSION['AdmHospitalizacion']['protocolo'] = $rst->fields[0];
				$rst->Close();                
			}

			$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_AUTORIZACION'] = 'Admon';
				
			//if($_SESSION['AdmHospitalizacion']['menu'] == "urgencias")
			//	$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO'] = "URGENCIAS";
			//else
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO'] = $_SESSION['AdmHospitalizacion']['tipo'];
			
			$_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARGUMENTOS'] = array('NoAutorizacion'=>$_SESSION['AUTOPACIENTE']['NoAutorizacion']);
			$_SESSION['AUTORIZACIONES']['AUTORIZAR']['EMPLEADOR'] = true;
			$_SESSION['AUTORIZACIONES']['AUTORIZAR']['SERVICIO'] = $_SESSION['AdmHospitalizacion']['servicio'];
				
			$_SESSION['AUTORIZACIONES']['RETORNO']['contenedor'] = 'app';
			$_SESSION['AUTORIZACIONES']['RETORNO']['modulo'] = 'AdmisionHospitalizacion';
			$_SESSION['AUTORIZACIONES']['RETORNO']['metodo'] = 'RetornoAutorizacion';
			$_SESSION['AUTORIZACIONES']['RETORNO']['tipo'] = 'user';
	
			$this->ReturnMetodoExterno('app','Autorizacion','user','SolicitudAutorizacion');
			return true;
		}
		/************************************************************************************ 
		* Funcion, donde se inicializan las variables de paginaActual, offset y conteo,
		* importantes a la hora de referenciar al paginador
		* 
		* @param String Cadena que contiene la consulta sql del conteo 
		* @param int numero que define el limite de datos,cuando no se desa el del 
		* 			 usuario,si no se pasa se tomara por defecto el del usuario 
		* @return boolean 
		*************************************************************************************/
		function ProcesarSqlConteo($consulta,$limite=null)
		{
			$this->offset = 0;
			$this->paginaA = 1;
			$this->limit = $limite;
			if($limite == null)
			{
				$this->limit = UserGetVar(UserGetUID(),'LimitRowsBrowser');
				if(!$this->limit) $this->limit = 20;
			}
			
			if($_REQUEST['offset'])
			{
				$this->paginaA = intval($_REQUEST['offset']);
				if($this->paginaA > 1)
				{
					$this->offset = ($this->paginaA - 1) * ($this->limit);
				}
			}		
			
			if(!$_REQUEST['registros'])
			{
				if(!$result = $this->ConexionBaseDatos($consulta))
					return false;
	
				if(!$result->EOF)
				{
					$this->conteo = $result->fields[0];
					$result->MoveNext();
				}
				$result->Close();
			}
			else
			{
				$this->conteo = $_REQUEST['registros'];
			}
			return true;
		}
		/************************************************************************************
		* Llama el modulo de autorizaciones
		* @access public
		* @return boolean
		*************************************************************************************/
		function RetornoAutorizacion()
		{
			unset($_SESSION['ADMISIONES']);

			$_SESSION['ADMISIONES']['PACIENTE']['rango'] = $_SESSION['AUTORIZACIONES']['RETORNO']['rango'];
			$_SESSION['ADMISIONES']['PACIENTE']['semanas'] = $_SESSION['AUTORIZACIONES']['RETORNO']['semanas'];
			$_SESSION['ADMISIONES']['PACIENTE']['plan_id'] = $_SESSION['AUTORIZACIONES']['RETORNO']['plan_id'];
			$_SESSION['ADMISIONES']['PACIENTE']['paciente_id'] = $_SESSION['AUTORIZACIONES']['RETORNO']['paciente_id'];
			$_SESSION['ADMISIONES']['PACIENTE']['AUTORIZACION'] = $_SESSION['AUTORIZACIONES']['RETORNO']['NumAutorizacion'];
			$_SESSION['ADMISIONES']['PACIENTE']['AUTORIZACIONEXT'] = $_SESSION['AUTORIZACIONES']['RETORNO']['ext'];
			$_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'] = $_SESSION['AUTORIZACIONES']['RETORNO']['tipo_id_paciente'];
			$_SESSION['ADMISIONES']['PACIENTE']['tipo_afiliado_id'] = $_SESSION['AUTORIZACIONES']['RETORNO']['tipo_afiliado_id'];
			$_SESSION['ADMISIONES']['PACIENTE']['observacion_ingreso'] = $_SESSION['AUTORIZACIONES']['RETORNO']['observacion_ingreso'];
			
			$Mensaje = $_SESSION['AUTORIZACIONES']['RETORNO']['Mensaje'];
			$TipoServicio = $_SESSION['AUTORIZACIONES']['RETORNO']['TIPO_SERVICIO'];
			
			if(empty($TipoServicio))
			{  
				$TipoServicio = $_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO'];  
			}
			if(empty($_SESSION['AUTORIZACIONES']['RETORNO']['ext']))
			{  
				$_SESSION['AUTORIZACIONES']['RETORNO']['ext']='NULL'; 
			}
			
			$_SESSION['AdmHospitalizacion']['paciente']['autorizacion'] = $_SESSION['AUTORIZACIONES']['RETORNO']['NumAutorizacion'];
						//empleador
			if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']))
			{
				$_SESSION['ADMISIONES']['PACIENTE']['id_empleador']=$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['id_empleador'];
				$_SESSION['ADMISIONES']['PACIENTE']['tipo_empleador']=$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['tipo_empleador'];
			}
			if(!empty($_SESSION['AUTORIZACIONES']['NOAUTO']))
			{
				if(empty($Mensaje))
				{  
					$Mensaje = 'NO SE AUTORIZO LA ADMISION.';   
				}
				
				$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','Buscar');
				$this-> FormaMensaje($Mensaje,'AUTORIZACIONES');
				return true;
			}
				
			unset($_SESSION['AUTORIZACIONES']);
			if(empty($_SESSION['AdmHospitalizacion']['paciente']['autorizacion']))
			{
				if(empty($Mensaje))
				{   
					$Mensaje = 'NO SE PUDO REALIZAR LA AUTORIZACIÓN PARA LA ADMISIÓN HOSPITALIZACIÓN.';   
				}
				
				if($_SESSION['AdmHospitalizacion']['tipoorden'] == 'Externa')
				{  
					$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','Buscar',array('TIPOORDEN'=>'Externa'));  
				}
				else
				{  
					$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','MetodoBuscarHospitalizacion');
				}
				
				$this-> FormaMensaje($Mensaje,'AUTORIZACIONES');
				return true;
			}
			unset($_SESSION['PACIENTE']['INGRESO']);
			/***********************************************************************
			* NOTA: Anteriormente se validaba si la persona tenia un ingreso o no y 
			* si lo tenia se enviaba el paciente a pendientes por hospitalizar, pero 
			* al ejecutar el query ocurria un error, por que faltaba un dato
			************************************************************************/ 
			if(empty($_SESSION['AdmHospitalizacion']['variable']))
			{
				$_SESSION['ADMISION']['RETORNO']['tipo'] = 'user';
				$_SESSION['ADMISION']['RETORNO']['modulo'] = 'AdmisionHospitalizacion';
				$_SESSION['ADMISION']['RETORNO']['metodo'] = 'LlamarFormaPedirNivel';
				$_SESSION['ADMISION']['RETORNO']['contenedor'] = 'app';
			}
			if(!empty($_SESSION['AdmHospitalizacion']['paciente']['admitir']))
			{
				$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','AdmisionSinClasificacion');  
			}
				//llamado al metodo de ingreso
			$_SESSION['ADMISIONES']['RETORNO']['tipo'] = 'user';
			$_SESSION['ADMISIONES']['RETORNO']['modulo'] = 'AdmisionHospitalizacion';
			$_SESSION['ADMISIONES']['RETORNO']['metodo'] = 'RetornoIngreso';
			$_SESSION['ADMISIONES']['RETORNO']['contenedor'] = 'app';
			$_SESSION['ADMISIONES']['RETORNO']['argumentos'] = array();
			$_SESSION['ADMISIONES']['TIPO'] = $_SESSION['AdmHospitalizacion']['tipo'];
			$_SESSION['ADMISIONES']['SWSOAT'] = $_SESSION['AdmHospitalizacion']['swsoat'];
			$_SESSION['ADMISIONES']['EMPRESA'] = $_SESSION['AdmHospitalizacion']['empresa'];
			$_SESSION['ADMISIONES']['CENTROUTILIDAD'] = $_SESSION['AdmHospitalizacion']['ctrutilidad'];
			$_SESSION['ADMISIONES']['PACIENTE']['REMISION'] = $_SESSION['AdmHospitalizacion']['paciente']['remision'];
			$_SESSION['ADMISIONES']['PACIENTE']['triage_id'] = $_SESSION['AdmHospitalizacion']['paciente']['triage_id'];
			$_SESSION['ADMISIONES']['PACIENTE']['departamento'] = $_SESSION['AdmHospitalizacion']['deptno'];
			$_SESSION['ADMISIONES']['PACIENTE']['punto_admision_id'] = $_SESSION['AdmHospitalizacion']['ptoadmon'];
						
			if(empty($_SESSION['AdmHospitalizacion']['paciente']['admitir']))
			{
				$_SESSION['ADMISIONES']['INGRESO']['ingreso'] = true;
			}
				
			if(!empty($_SESSION['AdmHospitalizacion']['menu']))
			{
				$this->ElegirEstacion();
			}
			else
			{
				$this->ElegirEstacionOrden();
			}
			//$this->ReturnMetodoExterno('app','Admisiones','user','LlamarFormaIngreso');
			return true;
		}
		/************************************************************************************
		* Funcion donde se actualiza la tabla de ordenes de hospitalizacion y se ingresa un 
		* paciente en pendiente por hospitalizar
		* 
		* @return boolean
		*************************************************************************************/
		function TerminarIngreso()
		{       
			$Dpto = $_SESSION['AdmHospitalizacion']['paciente']['departamento'];
			if(empty($Dpto)) $Dpto = $_SESSION['ADMISIONES']['PACIENTE']['departamento'];
						
			$Orden = $_SESSION['AdmHospitalizacion']['orden'];
			$Nivel = $_SESSION['AdmHospitalizacion']['paciente']['nivel'];
			$TipoId = $_SESSION['AdmHospitalizacion']['paciente']['tipo_id_paciente'];
			$PlanId = $_SESSION['AdmHospitalizacion']['paciente']['plan_id'];
			$Ingreso = $_SESSION['AdmHospitalizacion']['paciente']['ingreso'];
			$Estacion = $_SESSION['AdmHospitalizacion']['paciente']['estacion_id'];
			$PacienteId = $_SESSION['AdmHospitalizacion']['paciente']['paciente_id'];
			
			$arreglo = array("tipo_id_paciente"=>$TipoId,"paciente_id"=>$PacienteId);
			
			$numeroCuenta = $this->ValidarIngreso($arreglo,$Ingreso);
			
			list($dbconn) = GetDBconn();

			if($_SESSION['AdmHospitalizacion']['tipo'] == 'HOSPITALIZACION' 
					&& $_SESSION['AdmHospitalizacion']['tipoorden'] == 'Interna')
			{	
				$sql .= "INSERT INTO estaciones_enfermeria_ingresos_pendientes( ";
				$sql .= "			numerodecuenta, ";
				$sql .= "			estacion_id, ";
				$sql .= " 		fecha_registro,";
				$sql .= " 		usuario_registro) ";
				$sql .= "VALUES (";
				$sql .= "			".$numeroCuenta.",";
				$sql .= "			'".$Estacion."',";
				$sql .= "			now(),";
				$sql .= "			".UserGetUID()." ";
				$sql .= ")";
																					
				$dbconn->BeginTrans();
				$resulta = $dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Guardar en pendientes_x_hospitalizar";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
				else
				{
					$Auto = $_SESSION['AdmHospitalizacion']['paciente']['autorizacion'];
					$Ingreso = $_SESSION['AdmHospitalizacion']['paciente']['ingreso'];
	
					$sql  = "UPDATE ordenes_hospitalizacion ";
					$sql .= "SET	hospitalizado = '2',";
					$sql .= "			autorizacion = $Auto,";
					$sql .= "			ingreso = $Ingreso ";
					$sql .= "WHERE	orden_hospitalizacion_id = $Orden ";
																				
					$resulta = $dbconn->Execute($sql);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "ERROR AL ACTUALIZAR EN LA TABLA ordenes_hospitalizacion";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return true;
					}
					else
					{  
						$dbconn->CommitTrans();  
					}
				}
			}

			switch($_SESSION['AdmHospitalizacion']['tipoorden'])
			{
				case 'Externa':
					if(!empty($_SESSION['AdmHospitalizacion']['menu']))
						$this->CrearConsultaUrgencia();
					else
						$this->LlamarFormaOrdenExterna();
				break;
				case 'Interna':
						$this->MensajeFinal();
				break;
				case 'Translado':
						$this->BuscarTranslado();
				break;
				default:
						$this->MensajeFinal();
				break;
			}
					
			return true;
		}
		/************************************************************************************
		* Llama la FormaBuscar que busca los pacientes
		* @access public
		* @return boolean
		*************************************************************************************/
		function Buscar()
		{
			unset($_SESSION['AdmHospitalizacion']['autorizaciones']['arreglo']);
			unset($_SESSION['AdmHospitalizacion']['paciente']);
			
			$this->Plan = $_REQUEST['PlanId'];
			$this->TipoId = $_REQUEST['TipoId'];
			$this->PacienteId = $_REQUEST['PacienteId'];
			
			$this->TipoId = $this->TipoDocumento;
			$this->PacienteId = $this->Documento;
			$this->Responsable = $this->Plan;
			
			$this->triage = $_SESSION['AdmHospitalizacion']['swtriage'];
			
			if(!empty($_REQUEST['TIPOORDEN'])) $_SESSION['AdmHospitalizacion']['tipoorden'] = $_REQUEST['TIPOORDEN'];

			if(!empty($_REQUEST['menu']))	$_SESSION['AdmHospitalizacion']['menu'] = $_REQUEST['menu'];
						
			$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','BuscarIngresoPaciente');
			$this->action2 = ModuloGetURL('app','AdmisionHospitalizacion','user','MenuHospitalizacion');

			$this->FormaBuscar();
			return true;
		}
		/************************************************************************************
		* Llama la FormaBuscar que busca los pacientes
		* @access public
		* @return boolean
		*************************************************************************************/
		function Reporte()
		{
			$this->Plan = $_REQUEST['PlanId'];
			$this->TipoId = $_REQUEST['TipoId'];
			$this->PacienteId = $_REQUEST['PacienteId'];
			
			$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','BuscarPacienteIngreso');
			$this->action2 = ModuloGetURL('app','AdmisionHospitalizacion','user','MenuHospitalizacion');

			$this->FormaReporte();
			return true;
		}
		/************************************************************************************
		* Funcion donde se busca la informacion del ingreso de un paciente en la base de 
		* datos, para mostrar un reporte
		*************************************************************************************/
		function BuscarPacienteIngreso()
		{		
			$paciente = $_REQUEST['Documento'];
			$tipo_id_paciente = $_REQUEST['TipoDocumento'];
			
			if($paciente != "")
			{
				$sql  = "SELECT PC.paciente_id, ";
				$sql .= "				PC.tipo_id_paciente, ";
				$sql .= "				PC.primer_apellido ||' '|| PC.segundo_apellido AS apellidos, ";
				$sql .= "				PC.primer_nombre ||' '|| PC.segundo_nombre AS nombres, ";
				$sql .= "				IG.ingreso, ";
				$sql .= "				TO_CHAR(IG.fecha_ingreso,'DD/ MM/ YYYY') AS fecha_ingreso, ";
				$sql .= "				DE.descripcion, ";
				$sql .= "				VI.via_ingreso_nombre, ";
				$sql .= "				CASE 	WHEN IG.estado = '0' THEN 'CERRADO'";
				$sql .= "							WHEN IG.estado = '1' THEN 'ACTIVO' ";
				$sql .= "							WHEN IG.estado = '2' THEN	'LISTO PARA SALIR' END AS estado ";
				$sql .= "FROM		pacientes PC, ";
				$sql .= "				ingresos IG, ";
				$sql .= "				vias_ingreso VI, ";
				$sql .= "				tipos_id_pacientes TI, ";
				$sql .= "				departamentos DE, ";
				$sql .= "				system_usuarios SU ";
				$sql .= "WHERE	PC.tipo_id_paciente = TI.tipo_id_paciente ";
				$sql .= "AND		IG.paciente_id = PC.paciente_id ";
				$sql .= "AND		IG.tipo_id_paciente = PC.tipo_id_paciente ";
				$sql .= "AND		DE.departamento = IG.departamento ";
				$sql .= "AND		VI.via_ingreso_id = IG.via_ingreso_id ";
				$sql .= "AND		SU.usuario_id = IG.usuario_id ";
				$sql .= "AND		PC.tipo_id_paciente = '".$tipo_id_paciente."' ";
				$sql .= "AND		PC.paciente_id = '".$paciente."' ";
				$sql .= "ORDER BY 5 DESC ";
				if(!$rst = $this->ConexionBaseDatos($sql))
					return false;	
			
				while(!$rst->EOF)
				{
					$this->vars[] = $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
				}
				$rst->Close();
			}
		
			$this->Reporte();
			return true;
		}
		/************************************************************************************
		* Busca si el paciente tiene una cuenta abierta
		* @access public
		* @return boolean
		************************************************************************************/
		function BuscarIngresoPaciente()
		{
			unset($_SESSION['AUTOPACIENTE']['NoAutorizacion']);
			unset($_SESSION['AdmHospitalizacion']['paciente']);
			
			$CU = $_SESSION['AdmHospitalizacion']['ctrutilidad'];
			$pto = $_SESSION['AdmHospitalizacion']['ptoadmon'];
			$dpto = $_SESSION['AdmHospitalizacion']['deptno'];
			$empresa = $EmpresaId=$_SESSION['AdmHospitalizacion']['empresa'];
			
			$this->Documento = trim($_REQUEST['Documento']);
			$this->TipoDocumento = $_REQUEST['TipoDocumento'];
			$this->Responsable = $this->Plan = $_REQUEST['Responsable'];

			$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','BuscarIngresoPaciente');
			$this->action2 = ModuloGetURL('app','AdmisionHospitalizacion','user','MenuHospitalizacion');
			
			$_SESSION['PACIENTES']['PACIENTE']['tipo'] = 'user';
			$_SESSION['PACIENTES']['PACIENTE']['metodo'] = 'Terminar';
			$_SESSION['PACIENTES']['PACIENTE']['modulo'] = 'AdmisionHospitalizacion';
			$_SESSION['PACIENTES']['PACIENTE']['contenedor'] = 'app';
			$_SESSION['PACIENTES']['PACIENTE']['plan_id'] = $this->Plan;
			$_SESSION['PACIENTES']['PACIENTE']['paciente_id'] = $this->TipoDocumento;
			$_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente'] = $this->Documento;
			$_SESSION['AUTOPACIENTE']['NoAutorizacion'] = $_REQUEST['NoAutorizacion'];
			
			$validar = $this->ValidarDatosPrincipales();
			$Paciente = $this->ReturnModuloExterno('app','Pacientes','user');
			if($validar)
			{
				$_SESSION['PACIENTES']['RETORNO']['argumentos'] = array('TipoDocumento'=>$_REQUEST['TipoDocumento'],
																																'Documento'=>$_REQUEST['Documento'],
																																'Responsable'=>$_REQUEST['Responsable'],
																																'HOMONIMO'=>true);
				$_SESSION['PACIENTES']['RETORNO']['tipo'] = 'user';
				$_SESSION['PACIENTES']['RETORNO']['metodo'] = "BuscarIngresoPaciente";
				$_SESSION['PACIENTES']['RETORNO']['modulo'] = 'AdmisionHospitalizacion';
				$_SESSION['PACIENTES']['RETORNO']['contenedor'] = 'app';

				if(($this->TipoDocumento == 'AS' OR $this->TipoDocumento == 'MS') 
					AND empty($this->Documento))
				{  
					$this->Documento = $this->CallMetodoExterno('app','Pacientes','user','IdentifiacionNN');  
				}
				
				if(!is_object($Paciente))
				{
					$this->error = "La clase Pacientes no se pudo instanciar";
					$this->mensajeDeError = "";
					return false;
				}
				$metodo = $this->ObtenerMetodo();
				if($this->ValidarIngresoPaciente())
				{
					$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','Buscar',
																				 array('TIPOORDEN'=>$_SESSION['AdmHospitalizacion']['tipoorden'],
																							 "menu"=>$_SESSION['AdmHospitalizacion']['menu']));
					$this->FormaMostrarInfoIngreso();
					return true;
				}
				else
				{
					unset($Paciente);
					$this->PedirDatosPaciente();
					return true;
				}
			}
			else
			{
				unset($Paciente);
				$this->Buscar();
				return true;
			}			
		}
		/************************************************************************************
		* Valida los datos de la ventana inicial para buscar el paciente
		* @access public
		* @return boolean
		*************************************************************************************/
		function ValidarDatosPrincipales()
		{
			$this->TipoId = $this->TipoDocumento;
			$this->PacienteId = $this->Documento;
			$this->Responsable = $this->Plan;
			
			if($this->TipoDocumento != 'AS' && $this->TipoDocumento != 'MS')
			{
				if(!$this->Documento || !$this->TipoDocumento || $this->Plan==-1)
				{
					if(!$this->Documento)
					{ 
						$this->frmError["MensajeError"]= "FALTA EL NUMERO DEL DOCUMENTO"; 
					}
					if(!$this->TipoDocumento)
					{ 
						$this->frmError["MensajeError"]= "FAVOR SELECCIONAR EL TIPO DE DOCUMENTO"; 
					}
					if($this->Plan=="-1")
					{ 
						$this->frmError["MensajeError"]= "FAVOR SELECCIONAR EL PLAN"; 
					}
					return false;
				}
				return true;
			}
			else
			{
				if($this->Plan==-1)
				{
					if($this->Plan==-1)
					{ 
						$this->frmError["MensajeError"]= "FAVOR SELECCIONAR EL PLAN";  
					}
					return false;
				}
				return true;
			}
		}
		/************************************************************************************
		* Aqui continua el proceso de admision cuando es el caso que el paciente no esta en la
		* tabla de pacientes y se debe referir al modulo de Pacientes. Este es el retorno de 
		* pacientes.
		* @access public  
		* @return boolean
		*************************************************************************************/
		function Terminar()
		{		
			
			$sql = "SELECT	sw_tipo_plan
							FROM 	planes
							WHERE	estado='1' 
							AND		plan_id='".$_SESSION['PACIENTES']['PACIENTE']['plan_id']."'
							AND		fecha_final >= now() 
							AND 	fecha_inicio <= now() ";
	
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
		
			if($rst->fields[0]==1)
			{
				$this->TipoId = $_SESSION['PACIENTES']['PACIENTE']['paciente_id'];
				$this->PlanId = $_SESSION['PACIENTES']['PACIENTE']['plan_id'];
				$this->PacienteId = $_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente'];
				
				$this->PedirDatosPaciente();
				//unset($Paciente);
				return true;
			}
			else
			{
				$this->Plan = $_SESSION['PACIENTES']['PACIENTE']['plan_id'];
				$this->Documento = $_SESSION['PACIENTES']['PACIENTE']['paciente_id'];
				$this->TipoDocumento = $_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente'];
				$this->AutorizarPaciente();
				//unset($Paciente);
				return true;
			}
		}
		/************************************************************************************
		* Llama la forma de pedir datos del modulo pacientes
		* @access public
		* @return boolean
		*************************************************************************************/
		function PedirDatosPaciente()
		{
			$_SESSION['PACIENTES']['PACIENTE']['plan_id'] = $this->Plan;
			$_SESSION['PACIENTES']['PACIENTE']['paciente_id'] = $this->PacienteId;
			$_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente'] = $this->TipoId;
			
			$_SESSION['PACIENTES']['RETORNO']['argumentos'] = array();
			$_SESSION['PACIENTES']['RETORNO']['contenedor'] = 'app';
			$_SESSION['PACIENTES']['RETORNO']['modulo'] = 'AdmisionHospitalizacion';
			$_SESSION['PACIENTES']['RETORNO']['tipo'] = 'user';
			
			if($_REQUEST['remision'] == "2") $metodo = 'Remision';  
			else if($_REQUEST['remision'] == "3") $metodo = 'AdmitirTriage'; 
			else $metodo = 'AutoHospitalizacion'; 
			
			$_SESSION['PACIENTES']['RETORNO']['metodo'] = $metodo;
			$_SESSION['PACIENTES']['PACIENTE']['ARREGLO'] = $_SESSION['AdmHospitalizacion']['autorizacion']['arreglo'];
	
			$this->ReturnMetodoExterno('app','Pacientes','user','PedirDatos');
			return true;
		}
		/************************************************************************************
		* Llama la forma FormaOrdenExterna.
		* @access public
		* @return boolean
		*************************************************************************************/
		function LlamarFormaOrdenExterna()
		{
			$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','ValidarOrdenExterna');
			$this->action2 = ModuloGetURL('app','AdmisionHospitalizacion','user','Buscar',array('TIPOORDEN'=>'Externa'));
			
			if(!$this->FormaOrdenExterna())
			{
				return false;
			}
			return true;
		}
		/************************************************************************************
		* Busca las entidad de las cuales puede venir la orden del paciente (orden externa)
		* @access public
		* @return array
		*************************************************************************************/
		function EntidadesOrigen()
		{
			$sql = "SELECT sgsss,nombre_sgsss FROM sgsss ";
				
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;  
				
			while(!$rst->EOF)
			{
				$vars[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
				
			$rst->Close();
			return $vars;
		}
		/************************************************************************************
		* Busca el mensaje final segun el caso del tipo de admision (UR y HS).
		* @access public
		* @return boolean
		*************************************************************************************/
		function MensajeFinal()
		{
			$this->Caja = 1;
			$this->Imprimir = 1;
			$this->action3 = ModuloGetURL('app','AdmisionHospitalizacion','user','PagarEnCaja');
			$this->PacienteId = $_SESSION['AdmHospitalizacion']['paciente']['paciente_id'];
			$this->Ingreso = $_SESSION['AdmHospitalizacion']['paciente']['ingreso'];
			$this->TipoId = $_SESSION['AdmHospitalizacion']['paciente']['tipo_id_paciente'];
			
			unset($_SESSION['AdmHospitalizacion']['paciente']);

			$mesanje = 'EL PACIENTE FUE ADMITIDO.';
			if($_SESSION['AdmHospitalizacion']['tipoorden'] == 'Externa')
			{
				$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','Buscar');  
			}
			else if(!empty($_SESSION['AdmHospitalizacion']['orden1']))
				{
					$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','ListarPacientesTriages');
				}
				else
					{  
						$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','ListadoAdmisionHospitalizacion');
					}
	
			$this->FormaMensaje($mesanje,'MENSAJE');
			return true;
		}
		/************************************************************************************
		* Busca los diferentes tipos de identificacion de los paciente
		* @access public
		* @return array
		************************************************************************************/
		function TipoIdPaciente()
		{
			$sql = "SELECT * FROM tipos_id_pacientes ORDER BY indice_de_orden";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			while (!$rst->EOF)
			{
				$vars[$rst->fields[0]] = $rst->fields[1];
				$rst->MoveNext();
			}
			$rst->Close();
			return $vars;
		}
		/************************************************************************************
		* Busca los datos basicos del paciente con su ingreso
		* @access public
		* @return array
		*************************************************************************************/
		function DatosBasicosPaciente()
		{
			$Ingreso = $_SESSION['ADMISION']['RETORNO']['ingreso'];
			
			$sql = "SELECT	b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido AS completo,
											a.ingreso, 
											a.paciente_id, 
											a.tipo_id_paciente
							FROM		ingresos as a, 
											pacientes as b
							WHERE		a.ingreso = $Ingreso 
							AND 		a.tipo_id_paciente = b.tipo_id_paciente
							AND 		a.paciente_id = b.paciente_id ";
							
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
	
			while(!$rst->EOF)
			{
				$vars[]=$rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
						
			return $vars;
		}
		/*************************************************************************************
		* Funcion donde se evalua si un afecha es correcta o no 
		* 
		* @param $fecha dato a evaluar
		* @return boolean 
		**************************************************************************************/
		function ValidarFecha($fecha)
		{			
			$f = explode("-",$fecha); 
			
			$resultado = checkdate($f[1],$f[2],$f[0]);
			if($resultado != 1 || sizeof($f) != 3)
			{
				$this->frmError["MensajeError"] = "EL FORMATO DE FECHA ES INCORRECTO ";
				return false;
			}
			
			return true;
		}
		/************************************************************************************
		* Busca los diferentes tipos de responsable (planes)
		* @access public
		* @return array
		*************************************************************************************/
		function Responsables($op = null)
		{
			$sql = "SELECT	plan_id,
											plan_descripcion,
											tercero_id,
											tipo_tercero_id 
							FROM 		planes
							WHERE 	fecha_final >= now() 
							AND 		estado=1 
							AND 		fecha_inicio <= now() 
							ORDER BY plan_descripcion ";
										
			if(!$result = $this->ConexionBaseDatos($sql))
				return false;
	
			if($op != null)
				$arreglo[] = array("plan_descripcion"=>"-------SELECCIONAR-------","paln_id"=>"-1");
			
			while (!$result->EOF)
			{
				$arreglo[] = $result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
					
			$result->Close();
			return $arreglo;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function AutoHospitalizacion()
		{	
			$_SESSION['AdmHospitalizacion']['paciente']['plan_id'] = $_SESSION['PACIENTES']['PACIENTE']['plan_id'];
			$_SESSION['AdmHospitalizacion']['paciente']['paciente_id'] = $_SESSION['PACIENTES']['PACIENTE']['paciente_id'];
			$_SESSION['AdmHospitalizacion']['paciente']['tipo_id_paciente'] = $_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente'];
			
			$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','BuscarIngresoPaciente');
			$this->action2 = ModuloGetURL('app','AdmisionHospitalizacion','user','MenuHospitalizacion');
			
			if(!$_SESSION['PACIENTES']['RETORNO']['PASO'])
			{
				$this->Buscar();
				return true;
			}
			
			unset($_SESSION['PACIENTES']);
			$this->AutorizarPaciente();
			return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function RetornoIngreso()
		{
			if(!empty($_SESSION['ADMISIONES']['RETORNO']['CANCELAR']))
			{
				$this->MenuHospitalizacion();
				return true;
			}
			$_SESSION['ADMISION']['RETORNO']['ingreso'] = $_SESSION['ADMISIONES']['PACIENTE']['INGRESO'];
			$_SESSION['AdmHospitalizacion']['paciente']['ingreso'] = $_SESSION['ADMISIONES']['PACIENTE']['INGRESO'];
			$_SESSION['AdmHospitalizacion']['paciente']['estacion_id'] = $_SESSION['ADMISIONES']['PACIENTE']['estacion_id'];
			
			$this->TerminarIngreso();
			return true;
		}
		/************************************************************************************
		* Valida que todos los datos de la orden externa sean correctos
		* @access public
		* @return boolean
		*************************************************************************************/
		function ValidarOrdenExterna()
		{
			$this->Medico=$_REQUEST['Medico'];
			$this->Cargo=$_REQUEST['cargo'];
			$this->Codigo=$_REQUEST['codigo'];
			$this->Diagnostico=$_REQUEST['Diagnostico'];
			$this->Origen=$_REQUEST['Origen'];
			$this->Observacion=$_REQUEST['Observacion'];
			$this->Fecha=$_REQUEST['Fecha'];
			$this->Hora=$_REQUEST['Hora'];
			$this->Min=$_REQUEST['Min'];
			
			$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','ValidarOrdenExterna');
			if($this->Origen==-1 || !$this->Medico || !$this->Codigo || !$this->Fecha || !$this->Hora || !$this->Min)
			{
				if($this->Origen==-1)
				{ 
					$this->frmError["MensajeError"] = "POR FAVOR SELECCIONAR LA ENTIDAD"; 
				}
				elseif(!$this->Medico )
				{ 
					$this->frmError["MensajeError"] = "SE DEBE INDICAR EL NOMBRE DEL MEDICO";
				}
				elseif(!$this->Codigo)
					{ 
						$this->frmError["MensajeError"] = "SE DEBE INDICAR EL DIAGNOSTICO"; 
					}
					elseif(!$this->Fecha)
						{ 
							$this->frmError["MensajeError"] = "SE DEBE INDICAR LA FECHA";
						}
						elseif(!$this->Hora)
							{ 
								$this->frmError["MensajeError"] = "SE DEBE INDICAR LA HORA"; 
							}
							elseif(!$this->Min)
								{ 
									$this->frmError["MensajeError"] = "SE DEBEN INCAR LOS MINUTOS"; 
								}
								
				if(!$this->FormaOrdenExterna())
				{
					return false;
				}
				return true;
			}
						
			$f = explode('/',$this->Fecha);
			$this->fec = $f[2].'-'.$f[1].'-'.$f[0];
	
			if(!$this->ValidarFecha($this->fec))
			{
				$this->FormaOrdenExterna();
				return true;
			}
	
			$datos = $this->DatosBasicosPaciente();
			
			$FechaRegistro = date("Y-m-d H:i:s");
			$PacienteId = $datos[0][paciente_id];
			$TipoId = $datos[0][tipo_id_paciente];
			$FechaP = $this->fec." ".$Hora.":".$Min;
			
			$dpto = $_SESSION['AdmHospitalizacion']['deptno'];
			$Auto = $_SESSION['AdmHospitalizacion']['paciente']['autorizacion'];
			$Ingreso = $_SESSION['AdmHospitalizacion']['paciente']['ingreso'];
			
			$arreglo = array("tipo_id_paciente"=>$TipoId,"paciente_id"=>$PacienteId);
			$numeroCuenta = $this->ValidarIngreso($arreglo,$Ingreso);
	
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			
			$sql = "SELECT nextval('ordenes_hospitalizacion_orden_hospitalizacion_id_seq')";
			$result = $dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "ERROR AL GUARDAR EN LA BASE DE DATOS0";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}						
						
			$Orden = $result->fields[0];
	
			$sql = "INSERT INTO ordenes_hospitalizacion(
											orden_hospitalizacion_id,
											fecha_orden,
											fecha_programacion,
											hospitalizado,
											paciente_id,
											tipo_id_paciente,
											departamento,
											tipo_orden_id,
											autorizacion,
											ingreso)
							VALUES(	$Orden,
											'$FechaRegistro',
											'$FechaP',
											'0',
											'$PacienteId',
											'$TipoId',
											'$dpto',
											'0',
											$Auto,
											$Ingreso) ";
					
			$dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0) 
			{
				$this->error = "Error al Guardar en la Base de Datos1";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
			else
			{
				$sql = "INSERT INTO ordenes_hospitalizacion_externas(
												orden_hospitalizacion_id,
												nombre_medico,
												observaciones,
												diagnostico_id,
												sgsss)
								VALUES(	 ".$Orden.",
												'".$this->Medico."',
												'".$this->Observacion."',
												'".$this->Codigo."',
												'".$this->Origen."')";      
				$dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Guardar en la Base de Datos2";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
				else
				{
					$Estacion = $_SESSION['AdmHospitalizacion']['paciente']['estacion_id'];
											
					$sql  = "INSERT INTO estaciones_enfermeria_ingresos_pendientes( ";
					$sql .= " 		numerodecuenta, ";
					$sql .= "			estacion_id, ";
					$sql .= " 		fecha_registro,";
					$sql .= " 		usuario_registro,";
					$sql .= "			diagnostico_id,";
					$sql .= "			observaciones,";
					$sql .= "			nombre_medico_externo) ";
					$sql .= "VALUES (";
					$sql .= "		 	 ".$numeroCuenta.",";
					$sql .= "			'".$Estacion."',";
					$sql .= "			 now(),";
					$sql .= "			".UserGetUID().", ";
					$sql .= "			'".$this->Codigo."',";
					$sql .= "			'".$this->Observacion."',";
					$sql .= "			'".$this->Medico."' ";
					$sql .= ")";
	
					$dbconn->BeginTrans();
					$resulta=$dbconn->Execute($sql);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Guardar en estaciones_enfermeria_ingresos_pendientes ";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
					else
					{
						$dbconn->CommitTrans();
						$this->MensajeFinal();
						return true;
					}
				}
			}
		}
		/************************************************************************************
		*****								 					CIRUGIA 																	*****
		*************************************************************************************/
		/************************************************************************************
		* Funcion que permite mostrar la forma donde se ven las programaciones que aun no se 
		* han realizado
		* 
		* @return boolean 
		*************************************************************************************/
		function OrdenHospitalizacionCirugia()
		{
			$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','MenuHospitalizacion');
			$this->action2 = ModuloGetURL('app','AdmisionHospitalizacion','user','BuscarOrdenes',$this->arreglo);
			$this->actionB = ModuloGetURL('app','AdmisionHospitalizacion','user','BuscarOrdenes');
			$this->MostrarOrdenHospitalizacionCirugia();
			return true;
		}
		/************************************************************************************
		* Funcion donde se buscan las programaciones de cirugia para un paciente o para todos 
		* los pacientes
		*
		* @return boolean
		*************************************************************************************/
		function BuscarOrdenes()
		{
			$this->PacienteTipoId = $_REQUEST['tipo_id'];
			$this->PacienteDocumento = $_REQUEST['documento'];
			$this->Programacion = $_REQUEST['programacion'];
			$this->FechaFin = $_REQUEST['fecha_fin'];
			$this->FechaInicio = $_REQUEST['fecha_inicio'];
			
			$sql .= "SELECT QX.programacion_id,";
			$sql .= "		QX.departamento, ";
			$sql .= "		DE.descripcion, ";
			$sql .= "		TO_CHAR(QP.hora_inicio,'DD/MM/YYYY HH:MI') AS fecha, ";
			$sql .= "		TO_CHAR(QP.hora_fin,'DD/MM/YYYY HH:MI') AS fechafin, ";
			$sql .= "		OS.evento_soat, ";
			$sql .= "		PA.tipo_id_paciente, ";
			$sql .= "		PA.paciente_id, ";
			$sql .= "		PA.primer_nombre||' '||PA.segundo_nombre||' '||PA.primer_apellido||' '||PA.segundo_apellido AS paciente ";
			
			$where .= "FROM 	pacientes PA,";
			$where .= "				departamentos DE, ";
			$where .= "				qx_quirofanos_programacion QP, ";
			$where .= "		 		qx_programaciones QX LEFT JOIN ";
			$where .= "				(	SELECT	OS.evento_soat,";
			$where .= "									QO.programacion_id, ";
			$where .= "									OM.fecha_vencimiento AS fecha ";
			$where .= "					FROM		qx_programaciones_ordenes QO,";
			$where .= "									os_maestro OM, ";
			$where .= "									os_ordenes_servicios OS ";
			$where .= "					WHERE		QO.numero_orden_id = OM.numero_orden_id ";
			$where .= "					AND			OM.orden_servicio_id = OS.orden_servicio_id ";
			$where .= "				) AS OS ";
			$where .= "				ON( QX.programacion_id = OS.programacion_id) ";
			$where .= "WHERE	QX.estado = '1' ";
			$where .= "AND		QX.programacion_id NOT IN(";
			$where .= "					SELECT 	COALESCE(programacion_id,0) ";
			$where .= "					FROM	estacion_enfermeria_qx_pendientes_ingresar)";
			$where .= "AND		QX.programacion_id NOT IN(";
			$where .= "					SELECT 	programacion_id ";
			$where .= "					FROM	estacion_enfermeria_qx_pacientes_ingresados)";			
			$where .= "AND		QX.programacion_id = QP.programacion_id ";
			$where .= "AND		QX.paciente_id = PA.paciente_id ";
			$where .= "AND		QX.tipo_id_paciente = PA.tipo_id_paciente ";
			$where .= "AND		QX.departamento = DE.departamento ";
			$where .= "AND		QP.qx_tipo_reserva_quirofano_id = '3' ";
			$where .= "AND		QP.hora_inicio > '".date('Y-m-d')." 00:00'";
						
			if($this->PacienteTipoId != "0" && $this->PacienteDocumento != "")
			{
				$where .= "AND		QX.tipo_id_paciente = '".$this->PacienteTipoId ."' ";
				$where .= "AND		QX.paciente_id = '".$this->PacienteDocumento."' ";
			}
			else if($this->Programacion != "")
			{
				$where .= "AND		QX.programacion_id = ".$this->Programacion." ";
			}
			
			if($this->FechaInicio != "")
			{
				$this->fec = explode("/",$this->FechaInicio);
				$where .= "AND		QP.hora_inicio >= '".$this->fec[2]."-".$this->fec[1]."-".$this->fec[0]." 00:00:00' ";
			}
			if($this->FechaFin != "")
			{
				$this->fec = explode("/",$this->FechaFin);
				$where .= "AND		QP.hora_fin <= '".$this->fec[2]."-".$this->fec[1]."-".$this->fec[0]." 00:00:00' ";
			}
			
			$sqlCont = "SELECT COUNT(*) ".$where;
			if(!$this->ProcesarSqlConteo($sqlCont))
				return false;
						
			$sql .= $where;
			$sql .= "ORDER BY 1 ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
						
			$i = 0;
			
			while(!$rst->EOF)
			{
				$this->Datos[$i]=$rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
				$i++;
			}
			$rst->Close();
			
			$this->paso = 1;
			$this->arreglo = array("tipo_id"=>$this->PacienteTipoId,"documento"=>$this->PacienteDocumento, "programacion"=>$this->Programacion);
			$this->OrdenHospitalizacionCirugia();
			return true;
		}
		/************************************************************************************
		* Funcion que permite mostrar la interfaz donde se pide el departamento
		*
		* @return boolean
		*************************************************************************************/
		function SeleccionarDepartamento() 
		{
			$datos = $_REQUEST['datos'];
			$this->Documento = $datos['paciente_id'];
			$this->TipoDocumento = $datos['tipo_id_paciente'];
			
			$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','BuscarOrdenes');			
			if($this->ValidarIngresoPaciente())
			{
				$this->FormaMostrarInfoIngreso();
			}
			else
			{
				$this->action2 = ModuloGetURL('app','AdmisionHospitalizacion','user','CrearIngreso',array("datos"=>$_REQUEST['datos']));
				$this->FormaSeleccionarDepartamento();
			}
			return true;
		}
		/************************************************************************************
		* Funcion donde se buscan los departamentos de la estacion de enfermeria
		* 
		* @return boolean
		*************************************************************************************/
		function BuscarDepartamento() 
		{
			$sql .= "SELECT	EF.departamento, ";
			$sql .= "		DE.descripcion ";
			$sql .= "FROM	estacion_enfermeria_qx_departamentos EF, ";
			$sql .= "		departamentos DE ";
			$sql .= "WHERE	EF.departamento = DE.departamento ";
			$sql .= "ORDER BY 2 ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
						
			while(!$rst->EOF)
			{
				$departamentos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
				$i++;
			}
			$rst->Close();
						
			return $departamentos;
		}
		/************************************************************************************
		* Funcion que permite desplegar la intefaz para crear el ingresop del paciente, donde
		* se pide el plan el tipo de afiliacion y el rango
		*
		* @return boolean
		*************************************************************************************/
		function CrearIngreso() 
		{
			$this->Departamento = $_REQUEST['deptno'];
			if($this->Departamento == "-1" || empty($this->Departamento))
			{
				$this->frmError['MensajeError'] = "POR FAVOR SELECCIONAR EL DEPARTAMENTO";
				$this->SeleccionarDepartamento();
				return true;
			}
			
			$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','SeleccionarDepartamento');
			$this->action2 = ModuloGetURL('app','AdmisionHospitalizacion','user','SolicitarAutorizacion',array("datos"=>$_REQUEST['datos'],"deptno"=>$this->Departamento));
			$this->FormaCrearIngreso();
			return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function SolicitarAutorizacion()
		{
			$this->Responsable = $_REQUEST['Responsable'];
			if($this->Responsable == "")
			{
				$this->frmError["MensajeError"] = "POR FAVOR SELECCIONAR EL PLAN"; 
				$this->CrearIngreso();
				return true;			
			}
			else
			{
				$this->datos = $_REQUEST['datos'];
				$_SESSION['AdmHospitalizacion']['cirugia']['datos'] = $this->datos;
				$_SESSION['AdmHospitalizacion']['cirugia']['deptno'] = $_REQUEST['deptno'];
				$_SESSION['AdmHospitalizacion']['cirugia']['responsable'] = $this->Responsable;
        $_SESSION['AdmHospitalizacion']['cirugia']['observaciones'] = $_REQUEST['observaciones'];
				
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'] = $this->Responsable;
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id'] = $this->datos['paciente_id'];
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente'] = $this->datos['tipo_id_paciente'];
			
				if(empty($_SESSION['AdmHospitalizacion']['protocolo']))
				{
					$sql  = "SELECT	protocolos ";
					$sql .= "FROM 	planes ";
					$sql .= "WHERE	plan_id = ".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']."";
					
					if(!$rst = $this->ConexionBaseDatos($sql))
						return false;
									
					$_SESSION['AdmHospitalizacion']['protocolo'] = $rst->fields[0];
					$rst->Close();                
				}
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_AUTORIZACION'] = 'Admon';
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO'] = $_SESSION['AdmHospitalizacion']['tipo'];
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARGUMENTOS'] = array('NoAutorizacion'=>$_SESSION['AUTOPACIENTE']['NoAutorizacion']);
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['EMPLEADOR'] = true;
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['SERVICIO'] = $_SESSION['AdmHospitalizacion']['servicio'];
				
				$_SESSION['AUTORIZACIONES']['RETORNO']['contenedor'] = 'app';
				$_SESSION['AUTORIZACIONES']['RETORNO']['modulo'] = 'AdmisionHospitalizacion';
				$_SESSION['AUTORIZACIONES']['RETORNO']['metodo'] = 'CrearIngresoBD';
				$_SESSION['AUTORIZACIONES']['RETORNO']['tipo'] = 'user';
				$this->ReturnMetodoExterno('app','Autorizacion','user','SolicitudAutorizacion');
			}
			return true;
		}
		/************************************************************************************
		* Funcion donde se validan los campos de la forma crear ingreso y se hace un ingreso 
		* a un paciente, creando la cuenta y remitiendolo a pendientes por ingresar en la 
		* estacion de enfermeria
		*
		* @return boolean 
		*************************************************************************************/
		function CrearIngresoBD()
		{
			$this->datos = $_SESSION['AdmHospitalizacion']['cirugia']['datos'];
			$this->Nivel = $_SESSION['AUTORIZACIONES']['RANGO'];
			$this->Semanas = $_SESSION['AUTORIZACIONES']['SEMANAS'];			
			$this->Responsable = $_SESSION['AdmHospitalizacion']['cirugia']['responsable'] ;
			$this->TipoAfiliado = $_SESSION['AUTORIZACIONES']['AFILIADO'];
			
			$Hora =  explode(" ",$this->datos['fecha']);
			$Fecha =  explode("/",$Hora[0]);
			$FechaHora = $Fecha[2]."-".$Fecha[1]."-".$Fecha[0]." ".$Hora[1];
			
			$this->NumeroIngreso = "";
			$cuentas = $this->ValidarIngreso($this->datos);
												
			list($dbconn) = GetDBconn();
			//$dbconn->debug=true;
			$dbconn->BeginTrans();
									
			if($cuentas == "")
			{  
				$sql = "SELECT nextval('ingresos_ingreso_seq') ";
				$rst = $dbconn->Execute($sql);
									
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error ingresos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg()."<br>".$sql;
					$dbconn->RollbackTrans();
					return false;
				}
									
				$Ingreso = $rst->fields[0];
									
				$sql  = "INSERT INTO ingresos "; 
				$sql .= "			(	ingreso,";
				$sql .= "				tipo_id_paciente,";
				$sql .= "				paciente_id,";
				$sql .= "				fecha_ingreso,";
				$sql .= "				causa_externa_id,";
				$sql .= "				via_ingreso_id,";
				$sql .= "				departamento,";
				$sql .= "				estado,";
				$sql .= "				fecha_registro,";
				$sql .= "				usuario_id,";
				$sql .= "				departamento_actual,";
        $sql .= "       comentario) ";
				$sql .= "VALUES( ".$Ingreso.",";
				$sql .= "				'".$this->datos['tipo_id_paciente']."',";
				$sql .= "				'".$this->datos['paciente_id']."',";
				$sql .= "		 			now(),";
				$sql .= "				'15',";
				$sql .= "				'3',";
				$sql .= "				'".$this->datos['departamento']."',";
				$sql .= "				'1',";
				$sql .= "		 			now(),";
				$sql .= "				 ".UserGetUID().",";
				$sql .= "				'".$this->datos['departamento']."',";
        $sql .= "       '".$_SESSION['AdmHospitalizacion']['cirugia']['observaciones']."');";
							
				$dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error ingresos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg()."<br>".$sql;
					$dbconn->RollbackTrans();
					return false;
				}
									
				$sql = "SELECT nextval('cuentas_numerodecuenta_seq') ";
				$rst = $dbconn->Execute($sql);
								
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error secuencia";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg()."<br>".$sql;
					$dbconn->RollbackTrans();
					return false;
				}
									
				$NumeroCuenta = $rst->fields[0];
						
				$sql  = "INSERT INTO cuentas ";
				$sql .= "	  ( numerodecuenta,";
				$sql .= "		empresa_id,";
				$sql .= "		centro_utilidad,";
				$sql .= "		ingreso,";
				$sql .= "		plan_id,";
				$sql .= "		estado,";
				$sql .= "		usuario_id,";
				$sql .= "		fecha_registro,";
				$sql .= "		tipo_afiliado_id,";
				$sql .= "		rango,";
				$sql .= "		semanas_cotizadas)";
				$sql .= "VALUES( ".$NumeroCuenta.", ";
				$sql .= "		'".$_SESSION['AdmHospitalizacion']['empresa']."',";
				$sql .= "		'".$_SESSION['AdmHospitalizacion']['ctrutilidad']."',";
				$sql .= "		 ".$Ingreso.",";
				$sql .= "		 ".$this->Responsable.",";
				$sql .= "		 1,";
				$sql .= "		 ".UserGetUID().",";
				$sql .= "		 now(),";
				$sql .= "		'".$this->TipoAfiliado."',";
				$sql .= "		'".$this->Nivel."',";
				$sql .= "		".$this->Semanas.");";
									
				$dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error cuentas";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg()."<br>".$sql;
					$dbconn->RollbackTrans();
					return false;
				}
						
				$informacion  = "<br>PARA LA PROGRMACION: ".$this->datos['programacion_id']." ";
				$informacion .= "SE CREO UN INGRESO Y UNA CUENTA"."<br>";
				$this->NumeroIngreso = $Ingreso;
			}
			else
				{
					$informacion  = "EL PACIENTE IDENTIFICADO CON LA, ".$this->datos['tipo_id_paciente']." ".$this->datos['paciente_id']." <br>";
					$informacion .= "YA POSEE UN INGRESO ";
					$NumeroCuenta = $cuentas;
				}
															
			$deptno = $_SESSION['AdmHospitalizacion']['cirugia']['deptno'];
									
			$sql  = "INSERT INTO estacion_enfermeria_qx_pendientes_ingresar ";
			$sql .= "	  ( numerodecuenta,";
			$sql .= "		departamento,";
			$sql .= "		fecha_registro,";
			$sql .= "		usuario_id,";
			$sql .= "		programacion_id,";
			$sql .= "		fecha_ingreso_estacion) ";
			$sql .= "VALUES( ".$NumeroCuenta.", ";
			$sql .= "		'".$deptno."',";
			$sql .= "		   now(),";
			$sql .= "		 ".UserGetUID().",";
			$sql .= "		 ".$this->datos['programacion_id'].",";
			$sql .= "		'".$FechaHora."');";
									
			$dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error estacion_enfermeria_qx_pendientes_ingresar";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg()."<br>".$sql;
				$dbconn->RollbackTrans();
				return false;
			}
			
			
			if($this->datos['evento_soat'])
			{
				$sql  = "INSERT INTO ingresos_soat (ingreso,evento) ";
				$sql .= "VALUES (".$this->NumeroIngreso.",".$this->datos['evento_soat'].")"; 
				$dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Ingresar evento SOAT";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg()."<br>".$sql;
					$dbconn->RollbackTrans();
					return false;
				}
			}
			//$dbconn->RollbackTrans();
			$dbconn->CommitTrans();
					
			$this->Caja = 1;
			$this->Imprimir = 1;
			$this->TipoId = $this->datos['tipo_id_paciente'];
			$this->Ingreso = $Ingreso;
			$this->PacienteId = $this->datos['paciente_id'];
			$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','OrdenHospitalizacionCirugia');
			$this->action3 = ModuloGetURL('app','AdmisionHospitalizacion','user','PagarEnCaja');
			$this->FormaMensaje($informacion,"MENSAJE");
									
			return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function ValidarIngreso($paciente,$ingreso = null)
		{
			$sql .= "SELECT C.numerodecuenta AS NC,I.ingreso ";
			$sql .= "FROM	ingresos I, cuentas C ";
			$sql .= "WHERE	I.tipo_id_paciente = '".$paciente['tipo_id_paciente']."' ";
			$sql .= "AND	I.paciente_id = '".$paciente['paciente_id']."' ";
			$sql .= "AND	I.estado = '1' ";
			$sql .= "AND 	I.ingreso = C.ingreso ";

			if($ingreso != null)
				$sql .= "AND 	I.ingreso = ".$ingreso." ";
	
			$sql .= "ORDER BY 1 DESC ";

			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
	
			if(!$rst->EOF)
			{
				$datos = $rst->fields[0];
				$this->NumeroIngreso = $rst->fields[1];
				$rst->MoveNext();
			}
			$rst->Close();
									
			return $datos;
		}
		/************************************************************************************
		* Funcion que permite mostrar la forma donde se eligen las estaciones, para cuando se
		* hace una admision de urgencias
		*************************************************************************************/
		function ElegirEstacion()
		{
			unset($_SESSION['ADMISIONES']['PACIENTE']['estacion_id']);
			
			$this->Opcion = "2";
			
			$this->Consulta = $this->ObtenerEstaciones(0);
			$this->Observa = $this->ObtenerEstaciones(1);
			
			$this->action = ModuloGetURL('app','AdmisionHospitalizacion','user','ValidarEstacion');
			$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','MenuHospitalizacion');
			$this->FormaElegirEstacion();
			return true;
		}
		/************************************************************************************
		* Funcion donde se valida si el usuario selecciono una estacion o no
		*
		* @return boolean
		*************************************************************************************/
		function ValidarEstacion()
		{
			if($_REQUEST['estacion1'] == "-1" || $_REQUEST['estacion2'] == "-1")
			{
				$this->frmError["MensajeError"]="SE DEBE INDICAR LA ESTACIÓN A LA QUE IRA EL PACIENTE";
				
				$this->ElegirEstacion();
				return true;
			}
	
			$Estacion = $_REQUEST['estacion1'];
			$_SESSION['AdmHospitalizacion']['menu'] = "CONSULTA_URG";
			if(!$Estacion)
			{
				$Estacion = $_REQUEST['estacion2'];
				$_SESSION['AdmHospitalizacion']['menu'] = "ORDEN_URG";
			}
			
			$this->ReturnMetodoExterno('app','Admisiones','user','LlamarIngreso',array("Estacion"=>$Estacion));
				
			return true;
		}
		/************************************************************************************
		* Funcion donde se obtienen las estaciones para consulta urgencias u observacion ç
		* urgencias
		* 
		* @param int indica que estaciones se van a buscar  1->observacion urgencias
		*																										0->consulta urgencias
		* @return array
		*************************************************************************************/
		function ObtenerEstaciones($op)
		{
			$sql .= "	SELECT	estacion_id, descripcion, departamento
								FROM		estaciones_enfermeria as a ";
			if($op == "1")
				$sql .= "WHERE	sw_observacion_urgencia = '1' ";
			else
				$sql .= "WHERE	sw_consulta_urgencia = '1' ";
	
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
						
			$i = 0;
			while(!$rst->EOF)
			{
				$datos[$i] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
				$i++;
			}
			$rst->Close();
			return $datos;
		}
		/************************************************************************************
		* Funcion donde se crea la consulta de urgencias, si el usuario decidio enviar el 
		* paciente a una consulta de urgencias, se va al metodo donde se selecciona el medico 
		* que atiende
		* 
		* @return boolean 
		*************************************************************************************/
		function CrearConsultaUrgencia()
		{
			$Ingreso = $_SESSION['AdmHospitalizacion']['paciente']['ingreso'];
			$Estacion = $_SESSION['AdmHospitalizacion']['paciente']['estacion_id'];
			
			if(!empty($_SESSION['AdmHospitalizacion']['paciente']['remision']))
			{
				$sql  = "UPDATE pacientes_remitidos ";
				$sql .= "SET		ingreso = ".$Ingreso." ";
				$sql .= "WHERE	paciente_remitido_id = ".$_SESSION['AdmHospitalizacion']['paciente']['remision']." ";
				$sql .= "AND		tipo_id_paciente = '".$_SESSION['AdmHospitalizacion']['paciente']['tipo_id_paciente']."' ";
				$sql .= "AND		paciente_id = '".$_SESSION['AdmHospitalizacion']['paciente']['paciente_id']."' ";
				if(!$rst = $this->ConexionBaseDatos($sql))
					return false;			
			}
			if($_SESSION['AdmHospitalizacion']['menu'] == "CONSULTA_URG")
			{
				$triage = "NULL";
				if($_SESSION['AdmHospitalizacion']['paciente']['triage_id'])
					$triage = $_SESSION['AdmHospitalizacion']['paciente']['triage_id'];
					
				$sql  = "INSERT INTO pacientes_urgencias( ";
				$sql .= "				ingreso,";
				$sql .= "				estacion_id,";
				$sql .= "				triage_id ) ";
				$sql .= "VALUES( ".$Ingreso.", ";
				$sql .= "				'".$Estacion."',";
				$sql .= "				 ".$triage." );";
				
				if(!$rst = $this->ConexionBaseDatos($sql))
					return false;
				
				$this->ElegirProfesionalAtender($Ingreso);
			}
			elseif($_SESSION['AdmHospitalizacion']['menu'] == "ORDEN_URG")
			{
					$arreglo = array("tipo_id_paciente"=>$_SESSION['AdmHospitalizacion']['paciente']['tipo_id_paciente'],
													 "paciente_id"=>$_SESSION['AdmHospitalizacion']['paciente']['paciente_id']);
					$numeroCuenta = $this->ValidarIngreso($arreglo,$Ingreso);
					
					$sql  = "INSERT INTO estaciones_enfermeria_ingresos_pendientes( ";
					$sql .= "			numerodecuenta, ";
					$sql .= "			estacion_id, ";
					$sql .= "			fecha_registro,";
					$sql .= "			usuario_registro) ";
					$sql .= "VALUES (";
					$sql .= "			".$numeroCuenta.",";
					$sql .= "			'".$Estacion."',";
					$sql .= "			now(),";
					$sql .= "			".UserGetUID()." ";
					$sql .= ")";
					
					if(!$rst = $this->ConexionBaseDatos($sql))
						return false;
				
				$this->MensajeFinal();
			}	
			return true;		
		}
		/************************************************************************************
		* Funcion donde se decide si se muestra la forma donde se selecciona el profesional 
		* que atiende
		* 
		* @return boolean
		*************************************************************************************/
		function ElegirProfesionalAtender($Ingreso)
		{
			$deptno = $_SESSION['AdmHospitalizacion']['deptno'];
			
			$sql .= "SELECT PR.tipo_id_tercero,";
			$sql .= "				PR.tercero_id,";
			$sql .= "				PR.usuario_id,";
			$sql .= "				PR.nombre ";
			$sql .= "FROM		profesionales PR,";
			$sql .= "				estaciones_urgencias_profesionales_consultas EU ";
			$sql .= "WHERE	PR.tercero_id = EU.tercero_id ";
			$sql .= "AND		PR.tipo_id_tercero = EU.tipo_id_tercero ";
			$sql .= "AND		EU.departamento = '".$deptno."' ";
			$sql .= "AND		PR.tipo_profesional IN ('1','2') ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			while(!$rst->EOF)
			{
				$this->profesionales[] = $rst->GetRowAssoc($ToUpper = false); 
				$rst->MoveNext();
			}
			$rst->Close();	
			
			if(sizeof($this->profesionales) <= 0 || !$this->profesionales)
			{
				$this->MensajeFinal();
			}
			else
			{
				$this->action2 = ModuloGetURL('app','AdmisionHospitalizacion','user','Buscar');
				$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','CrearConsulta',array("ingreso"=>$Ingreso));
				$this->FormaElegirProfesionalAtender();
			}
			
			return true;
		}
		/************************************************************************************
		* Funcion donde se actualiza la tabla de pacientes urgencia, adicionando el 
		* profesional que debe atender 
		*
		* @return boolean
		*************************************************************************************/
		function CrearConsulta()
		{
			if($_REQUEST['terceros'] != "")
			{
				$informacion = explode("-",$_REQUEST['terceros']);
				$ingreso = $_REQUEST['ingreso'];
				
				$sql .= "UPDATE pacientes_urgencias ";
				$sql .= "SET		tipo_id_tercero = '".$informacion[0]."',";
				$sql .= "				tercero_id = '".$informacion[1]."', ";
				$sql .= "				usuario_id = '".$informacion[2]."' ";
				$sql .= "WHERE	ingreso = ".$ingreso." ";
				
				if(!$rst = $this->ConexionBaseDatos($sql))
					return false;
			}	
			$this->MensajeFinal();
			
			return true; 
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function ObtenerTercerosPlan($PlanId)
		{
			$sql .= "SELECT tipo_tercero_id,tercero_id ";
			$sql .= "FROM		planes ";
			$sql .= "WHERE	plan_id = ".$PlanId." ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			if(!$rst->EOF)
			{
				$terceros[0] = $rst->fields[0];
				$terceros[1] = $rst->fields[1];
				$rst->MoveNext();
			}
			$rst->Close();
						
			return $terceros;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function ValidarIngresoPaciente()
		{
			$sql  = "SELECT	COUNT(*) ";
			$sql .= "FROM		ingresos ";
			$sql .= "WHERE	paciente_id = '".$this->Documento."' "; 
			$sql .= "AND		tipo_id_paciente = '".$this->TipoDocumento."' "; 
			$sql .= "AND		estado ='1' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			if(!$rst->EOF)
			{
				$cantidad  = $rst->fields[0];
				$rst->MoveNext();
			}
			$rst->Close();
			
			if($cantidad > 0)
			{
				$sql  = "SELECT	IG.ingreso, ";
				$sql .= "				TO_CHAR(IG.fecha_ingreso,'DD/ MM/ YYYY') AS fecha_ingreso, ";
				$sql .= "				PC.paciente_id, ";
				$sql .= "				PC.tipo_id_paciente, ";
				$sql .= "				PC.primer_apellido ||' '|| PC.segundo_apellido AS apellidos,";
				$sql .= "				PC.primer_nombre ||' '|| PC.segundo_nombre AS nombres, ";
				$sql .= "				VI.via_ingreso_nombre ";
				$sql .= "FROM		ingresos IG, pacientes PC,vias_ingreso VI ";
				$sql .= "WHERE	IG.paciente_id = '".$this->Documento."' "; 
				$sql .= "AND		IG.tipo_id_paciente = '".$this->TipoDocumento."' ";
				$sql .= "AND		IG.estado ='1' ";
				$sql .= "AND		IG.paciente_id = PC.paciente_id ";
				$sql .= "AND		IG.tipo_id_paciente = PC.tipo_id_paciente ";
				$sql .= "AND		VI.via_ingreso_id = IG.via_ingreso_id ";
				
				if(!$rst = $this->ConexionBaseDatos($sql))
					return true;
				
				while(!$rst->EOF)
				{
					$this->ingreso[] = $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
				}
				$rst->Close();
				
				$sql  = "SELECT CU.numerodecuenta, ";
				$sql .= "				CU.total_cuenta, ";
				$sql .= "				CE.descripcion, ";
				$sql .= "				PL.plan_descripcion ";
				$sql .=	"FROM 	cuentas CU, ";
				$sql .=	"				planes PL, ";
				$sql .= "				cuentas_estados CE ";
				$sql .= "WHERE	CU.ingreso = ".$this->ingreso[0]['ingreso']." ";
				$sql .= "AND		CU.plan_id = PL.plan_id ";
				$sql .= "AND		CU.estado = CE.estado ";
				
				if(!$rst = $this->ConexionBaseDatos($sql))
					return true;
				
				while(!$rst->EOF)
				{
					$this->cuentas[] = $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
				}
				$rst->Close();
				$this->frmError['Informacion'] = "EL PACIENTE YA SE ENCUENTRA CON UN INGRESO ACTIVO, EL CUAL POSEE LA SIGUIENTE INFORMACIÓN";
				return true;
			}
			return false;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function ElegirEstacionOrden()
		{
			$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','Buscar',
																		array('TIPOORDEN'=>$_SESSION['AdmHospitalizacion']['tipoorden'],
																					"menu"=>$_SESSION['AdmHospitalizacion']['menu']));
			$this->BuscarRecomendacion();
			$this->FormaElegirEstacionOrden();
			return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function ContinuarAdmision()
		{
			$Estacion = $_REQUEST['estacion'];
			$this->ReturnMetodoExterno('app','Admisiones','user','LlamarIngreso',array("Estacion"=>$Estacion));
			return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function BuscarHabitacion($estacion)
		{
			$sql .= "SELECT COUNT(CA.cama) AS total, ";
			$sql .= "				COUNT(MH.cama) AS ocupadas,";
			$sql .= "				TC.descripcion ";
			$sql .= "FROM		estaciones_enfermeria EE,";
			$sql .= "				piezas PZ,";
			$sql .= "				tipos_camas TC,";
			$sql .= "				camas CA LEFT JOIN";
			$sql .= "				movimientos_habitacion MH";
			$sql .= "				ON(	CA.cama = MH.cama AND";
			$sql .= "						MH.fecha_egreso IS NULL) ";
			$sql .= "WHERE	CA.pieza = PZ.pieza ";
			$sql .= "AND		CA.tipo_cama_id = TC.tipo_cama_id ";
			$sql .= "AND		CA.SW_virtual = '1' ";
			$sql .= "AND		PZ.estacion_id = EE.estacion_id ";
			$sql .= "AND		EE.estacion_id = ".$estacion." ";
			$sql .= "GROUP BY 3";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return true;
				
			while(!$rst->EOF)
			{
				$habitacion[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $habitacion;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function BuscarRecomendacion()
		{
			$sql .= "SELECT protocolo_internacion ";
			$sql .= "FROM		planes ";
			$sql .= "WHERE	plan_id = ".$_SESSION['AdmHospitalizacion']['paciente']['plan_id']." ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return true;
				
			if(!$rst->EOF)
			{
				$this->frmError['Informacion'] = $rst->fields[0];
				$rst->MoveNext();
			}
			$rst->Close();
			
			return true;
		}
		/************************************************************************************
		*											BUSCAR PACIENTES																							*
		*************************************************************************************/
		function BuscarPaciente()
		{
			$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','BuscarPacientesBD');
			$this->action2 = ModuloGetURL('app','AdmisionHospitalizacion','user','MenuHospitalizacion');
			$this->FormaBuscarPacientes();
			return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function BuscarPacientesBD()
		{
			$this->Nombres = trim($_REQUEST['nombres']);
			$this->TipoDoc = $_REQUEST['tipodocumento'];
			$this->Documento = $_REQUEST['documento'];
			$this->Apellidos = trim($_REQUEST['apellidos']);
			$this->paso = 1;
			
			$this->arreglo = array("nombres"=>$_REQUEST['nombres'],"tipodocumento"=>$_REQUEST['tipodocumento'],
											 "documento"=>$_REQUEST['documento'],"apellidos"=>$_REQUEST['apellidos']);
			
			/*********************************************************
			* Ingresos activos del paciente solicitado
			**********************************************************/
			$sql  = "SELECT	I.ingreso ";
			$sql .= "FROM		ingresos I ";
			if($this->Nombres != "" || $this->Apellidos !="")
			{
				$sql .= "				,pacientes P ";
			}
			
			$sql .= "WHERE	I.estado ='1' ";
			
			if($this->TipoDoc != "" && $this->Documento != "")
			{
				
				$sql .= "AND		I.paciente_id = '".$this->Documento."' "; 
				$sql .= "AND		I.tipo_id_paciente = '".$this->TipoDoc."' "; 
				
			}
			if($this->Nombres != ""|| $this->Apellidos !="")
			{
				$nombres = strtoupper(str_replace(" ","|",$this->Nombres));
				$apellidos = strtoupper(str_replace(" ","|",$this->Apellidos));
				
				$sql .= "AND		I.paciente_id = P.paciente_id "; 
				$sql .= "AND		I.tipo_id_paciente = P.tipo_id_paciente ";
				if($this->Nombres != "")
				{
					$sql .= "AND		(	P.primer_nombre SIMILAR TO '%(".$nombres.")%' "; 
					$sql .= "					OR P.segundo_nombre SIMILAR TO '%(".$nombres.")%') ";
				}
				if($this->Apellidos !="")
				{
					$sql .= "AND 		(	P.primer_apellido SIMILAR TO '%(".$apellidos.")%' ";
					$sql .= "					OR P.segundo_apellido SIMILAR TO '%(".$apellidos.")%') "; 
				}
			}
			
			if(!$rst = $this->ConexionBaseDatos($sql))
					return true;
				
			while(!$rst->EOF)
			{
				$this->ingresos .= $rst->fields[0]." ";
				$rst->MoveNext();
			}
			$rst->Close();
			
			if(strlen($this->ingresos) > 0)
			{
				$i = 0;
				$ingresosIn = $this->ingresos;
				$this->ingresos = str_replace(" ",",",trim($this->ingresos));
				/***************************************************************************
				* ingresos que estan relacionados con pacientes urgencias
				****************************************************************************/
				$sql  = "SELECT	IG.ingreso, ";
				$sql .= "				TO_CHAR(IG.fecha_ingreso,'DD/ MM/ YYYY') AS fecha_ingreso, ";
				$sql .= "				PC.paciente_id, ";
				$sql .= "				PC.tipo_id_paciente, ";
				$sql .= "				PC.primer_apellido ||' '|| PC.segundo_apellido AS apellidos,";
				$sql .= "				PC.primer_nombre ||' '|| PC.segundo_nombre AS nombres, ";
				$sql .= "				CU.estado ||' '|| CU.numerodecuenta AS estado, ";
				$sql .= "				DE.descripcion, ";
				$sql .= "				EF.descripcion AS estacion, ";
				$sql .= "				'URG' AS tabla ";
				$sql .= "FROM		ingresos IG, pacientes PC,pacientes_urgencias PU,cuentas CU, ";
				$sql .= "				departamentos DE, estaciones_enfermeria EF ";
				$sql .= "WHERE	IG.ingreso IN (".$this->ingresos.") ";
				$sql .= "AND		IG.estado ='1' ";
				$sql .= "AND		IG.paciente_id = PC.paciente_id ";
				$sql .= "AND		IG.tipo_id_paciente = PC.tipo_id_paciente ";
				$sql .= "AND		IG.ingreso = PU.ingreso ";
				$sql .= "AND		PU.sw_estado = '1' ";
				$sql .= "AND		IG.ingreso = CU.ingreso ";
				$sql .= "AND		IG.departamento_actual = DE.departamento ";
				$sql .= "AND		EF.departamento = DE.departamento ";
				$sql .= "AND		PU.estacion_id = EF.estacion_id ";
				$sql .= "ORDER BY 1 ";
				
				if(!$rst = $this->ConexionBaseDatos($sql))
					return true;
				
				while(!$rst->EOF)
				{
					$this->paciente[$i] = $rst->GetRowAssoc($ToUpper = false);
					$ingresosIn = str_replace($this->paciente[$i]['ingreso']." ","",$ingresosIn);
					
					$rst->MoveNext();
					$i++;
				}
			
				$rst->Close();
				/***************************************************************************
				* ingresos que estan relacionados con esatciones enfernerias
				****************************************************************************/
				$sql  = "SELECT	IG.ingreso, ";
				$sql .= "				TO_CHAR(IG.fecha_ingreso,'DD/ MM/ YYYY') AS fecha_ingreso, ";
				$sql .= "				PC.paciente_id, ";
				$sql .= "				PC.tipo_id_paciente, ";
				$sql .= "				PC.primer_apellido ||' '|| PC.segundo_apellido AS apellidos,";
				$sql .= "				PC.primer_nombre ||' '|| PC.segundo_nombre AS nombres, ";
				$sql .= "				CU.estado||' '|| CU.numerodecuenta AS estado, ";
				$sql .= "				DE.descripcion, ";
				$sql .= "				EF.descripcion AS estacion, ";
				$sql .= "				'EEF' AS tabla ";
				$sql .= "FROM		ingresos IG, pacientes PC, cuentas CU,";
				$sql .= "				estaciones_enfermeria_ingresos_pendientes EP, ";
				$sql .= "				departamentos DE, estaciones_enfermeria EF ";
				$sql .= "WHERE	IG.ingreso IN (".$this->ingresos.") ";
				$sql .= "AND		IG.estado ='1' ";
				$sql .= "AND		IG.paciente_id = PC.paciente_id ";
				$sql .= "AND		IG.tipo_id_paciente = PC.tipo_id_paciente ";
				$sql .= "AND		IG.ingreso = CU.ingreso ";
				$sql .= "AND		CU.numerodecuenta = EP.numerodecuenta ";
				$sql .= "AND		IG.departamento_actual = DE.departamento ";
				$sql .= "AND		EP.estacion_id = EF.estacion_id ";
				$sql .= "ORDER BY 1 ";
				
				if(!$rst = $this->ConexionBaseDatos($sql))
					return true;
				
				while(!$rst->EOF)
				{
					$this->paciente[$i] = $rst->GetRowAssoc($ToUpper = false);
					$ingresosIn = str_replace($this->paciente[$i]['ingreso']." ","",$ingresosIn);
					
					
					$rst->MoveNext();
					$i++;
				}
				$rst->Close();
				/***************************************************************************
				* ingresos que estan relacionados con movimientos habitacion
				****************************************************************************/
				$sql  = "SELECT	IG.ingreso, ";
				$sql .= "				TO_CHAR(IG.fecha_ingreso,'DD/ MM/ YYYY') AS fecha_ingreso, ";
				$sql .= "				PC.paciente_id, ";
				$sql .= "				PC.tipo_id_paciente, ";
				$sql .= "				PC.primer_apellido ||' '|| PC.segundo_apellido AS apellidos,";
				$sql .= "				PC.primer_nombre ||' '|| PC.segundo_nombre AS nombres, ";
				$sql .= "				CU.estado||' '|| CU.numerodecuenta AS estado, ";
				$sql .= "				DE.descripcion, ";
				$sql .= "				EF.descripcion AS estacion, ";
				$sql .= "				CA.pieza, CA.cama, CA.ubicacion, ";
				$sql .= "				'MVH' AS tabla ";
				$sql .= "FROM		ingresos IG, pacientes PC, cuentas CU,";
				$sql .= "				estaciones_enfermeria_ingresos_pendientes EP, ";
				$sql .= "				movimientos_habitacion MH, camas CA, ";
				$sql .= "				departamentos DE,estaciones_enfermeria EF ";
				$sql .= "WHERE	IG.ingreso IN (".$this->ingresos.") ";
				$sql .= "AND		IG.estado ='1' ";
				$sql .= "AND		IG.paciente_id = PC.paciente_id ";
				$sql .= "AND		IG.tipo_id_paciente = PC.tipo_id_paciente ";
				$sql .= "AND		MH.ingreso = IG.ingreso ";
				$sql .= "AND		IG.ingreso = CU.ingreso ";
				$sql .= "AND		IG.departamento_actual = DE.departamento ";
				$sql .= "AND		EP.estacion_id = EF.estacion_id ";
				$sql .= "AND		CA.cama = MH.cama ";
				$sql .= "ORDER BY 1 ";
				
				while(!$rst->EOF)
				{
					$this->paciente[$i] = $rst->GetRowAssoc($ToUpper = false);
					$ingresosIn = str_replace($this->paciente[$i]['ingreso']." ","",$ingresosIn);
					
					$rst->MoveNext();
					$i++;
				}
				$rst->Close();
				if($ingresosIn != "")
				{
					/***************************************************************************
					* ingresos que no tienen cuentas
					****************************************************************************/
					$ingresosIn = str_replace(" ",",",trim($ingresosIn));
					$sql  = "SELECT	IG.ingreso, ";
					$sql .= "				TO_CHAR(IG.fecha_ingreso,'DD/ MM/ YYYY') AS fecha_ingreso, ";
					$sql .= "				PC.paciente_id, ";
					$sql .= "				PC.tipo_id_paciente, ";
					$sql .= "				PC.primer_apellido ||' '|| PC.segundo_apellido AS apellidos,";
					$sql .= "				PC.primer_nombre ||' '|| PC.segundo_nombre AS nombres, ";
					$sql .= "				DE.descripcion, ";
					$sql .= "				CU.estado,";
					$sql .= "				CU.numerodecuenta,";
					$sql .= "				'CUE' AS tabla ";
					$sql .= "FROM		ingresos IG LEFT JOIN cuentas CU ";
					$sql .= "				ON(";
					$sql .= "					IG.ingreso = CU.ingreso  ";
					$sql .= "				),";
					$sql .= " 			pacientes PC, ";
					$sql .= "				departamentos DE ";
					$sql .= "WHERE	IG.ingreso IN (".$ingresosIn.") ";
					$sql .= "AND		IG.estado ='1' ";
					$sql .= "AND		IG.paciente_id = PC.paciente_id ";
					$sql .= "AND		IG.tipo_id_paciente = PC.tipo_id_paciente ";
					$sql .= "AND		IG.departamento_actual = DE.departamento ";
					$sql .= "ORDER BY 1 ";

					if(!$rst = $this->ConexionBaseDatos($sql))
						return true;
					
					while(!$rst->EOF)
					{
						$this->paciente[$i] = $rst->GetRowAssoc($ToUpper = false);
						$rst->MoveNext();
						$i++;
					}
					$rst->Close();
				}	
			}
			$this->BuscarPaciente();
			return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function ElegirEstacionPA()
		{
			$arreglo = array("nombres"=>$_REQUEST['nombres'],"tipodocumento"=>$_REQUEST['tipodocumento'],
											 "documento"=>$_REQUEST['documento'],"apellidos"=>$_REQUEST['apellidos']);

			$this->action2 = ModuloGetURL('app','AdmisionHospitalizacion','user','BuscarPacientesBD',$arreglo);
			$arreglo["cuenta"] = $_REQUEST['cuenta'];
			$arreglo["ingreso"] = $_REQUEST['ingreso'];
			
			$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','AsignarEstacion',$arreglo);
			
			$this->FormaElegirEstacionPA();
			return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function AsignarEstacion()
		{
			if($_REQUEST['estacion1'] == "-1" || !$_REQUEST['estacion1'])
			{
				$this->frmError['MensajeError'] = "SELECCIONAR LA ESTACION A LA QUE DESEA ENVIAR EL APCIENTE";
				$this->ElegirEstacionPA();
			}
			else
			{
				$numerocuenta = $_REQUEST['cuenta'];
				$estacion = explode(",",$_REQUEST['estacion1']);
				
				$sql .= "INSERT INTO estaciones_enfermeria_ingresos_pendientes( ";
				$sql .= "			numerodecuenta, ";
				$sql .= "			estacion_id, ";
				$sql .= " 		fecha_registro,";
				$sql .= " 		usuario_registro) ";
				$sql .= "VALUES (";
				$sql .= "			".$numerocuenta.",";
				$sql .= "			'".$estacion[0]."',";
				$sql .= "			now(),";
				$sql .= "			".UserGetUID()." ";
				$sql .= ")";
				
				if(!$rst = $this->ConexionBaseDatos($sql))
					return true;
				
				$arreglo = array("nombres"=>$_REQUEST['nombres'],"tipodocumento"=>$_REQUEST['tipodocumento'],
											 "documento"=>$_REQUEST['documento'],"apellidos"=>$_REQUEST['apellidos']);
				$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','BuscarPacientesBD',$arreglo);
				
				$informacion = "EL PACIENTE FUE UBICADO EN LA ESTACION ".$estacion[2]." ";
				$this->FormaMensaje($informacion,"MENSAJE");
			}
			return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function ElegirEstacionMC()
		{
			$arreglo = array("nombres"=>$_REQUEST['nombres'],"tipodocumento"=>$_REQUEST['tipodocumento'],
											 "documento"=>$_REQUEST['documento'],"apellidos"=>$_REQUEST['apellidos']);

			$this->action2 = ModuloGetURL('app','AdmisionHospitalizacion','user','BuscarPacientesBD',$arreglo);
			$arreglo["cuenta"] = $_REQUEST['cuenta'];
			$arreglo["ingreso"] = $_REQUEST['ingreso'];
			
			$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','ElegirMedicoConsulta',$arreglo);
			
			$this->FormaElegirEstacionPA(1);
			return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function ElegirMedicoConsulta()
		{
			if($_REQUEST['estacion1'] == "-1" || !$_REQUEST['estacion1'])
			{
				$this->frmError['MensajeError'] = "SELECCIONAR LA ESTACION A LA QUE DESEA ENVIAR EL APCIENTE";
				$this->ElegirEstacionMC();
			}
			else
			{
				$deptno = $_SESSION['AdmHospitalizacion']['deptno'];
			
				$sql .= "SELECT PR.tipo_id_tercero,";
				$sql .= "				PR.tercero_id,";
				$sql .= "				PR.usuario_id,";
				$sql .= "				PR.nombre ";
				$sql .= "FROM		profesionales PR,";
				$sql .= "				estaciones_urgencias_profesionales_consultas EU ";
				$sql .= "WHERE	PR.tercero_id = EU.tercero_id ";
				$sql .= "AND		PR.tipo_id_tercero = EU.tipo_id_tercero ";
				$sql .= "AND		EU.departamento = '".$deptno."' ";
				//$sql .= "AND		PR.tipo_profesional IN ('1','2') ";
			
				if(!$rst = $this->ConexionBaseDatos($sql))
					return false;
			
				while(!$rst->EOF)
				{
					$this->profesionales[] = $rst->GetRowAssoc($ToUpper = false); 
					$rst->MoveNext();
				}
				$rst->Close();	
				
				$arreglo = array("nombres"=>$_REQUEST['nombres'],"tipodocumento"=>$_REQUEST['tipodocumento'],
												 "documento"=>$_REQUEST['documento'],"apellidos"=>$_REQUEST['apellidos']);

				$this->action2 = ModuloGetURL('app','AdmisionHospitalizacion','user','BuscarPacientesBD',$arreglo);
				$arreglo["cuenta"] = $_REQUEST['cuenta'];
				$arreglo["ingreso"] = $_REQUEST['ingreso'];
				$arreglo["estacion"] = $_REQUEST['estacion1'];
				
				$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','AsignarMedicoConsulta',$arreglo);
			
				$this->FormaElegirProfesionalAtender();
			}
			return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function AsignarMedicoConsulta()
		{
			$estacion = explode(",",$_REQUEST['estacion']);
			$ingreso = $_REQUEST['ingreso'];
			$medico = explode("-",$_REQUEST['terceros']);
			
			if($medico[0])
			{
				$medico[0] = "'".$medico[0]."'";
				$medico[1] = "'".$medico[1]."'";
			}
			else
			{
				$medico[0] = "NULL";
				$medico[1] = "NULL";
				$medico[2] = "NULL";
			}
			
			$sql  = "SELECT COUNT(*) ";
			$sql .= "FROM		pacientes_urgencias ";
			$sql .= "WHERE	ingreso = ".$ingreso." ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
					return false;
			
			if(!$rst->EOF)
			{
					$conteo = $rst->fields[0]; 
					$rst->MoveNext();
			}
			$rst->Close();

			if($conteo == "" || $conteo == "0")
			{
				$sql  = "INSERT INTO pacientes_urgencias( ";
				$sql .= "				ingreso,";
				$sql .= "				estacion_id, ";
				$sql .= "				tipo_id_tercero,";
				$sql .= "				tercero_id , ";
				$sql .= "				usuario_id ) ";
				$sql .= "VALUES( ".$ingreso.", ";
				$sql .= "				'".$estacion[0]."',";
				$sql .= "				 ".$medico[0].", ";
				$sql .= "				 ".$medico[1].", ";
				$sql .= "				 ".$medico[2]."  ";
				$sql .= ");";
			}
			else
			{
				$sql  = "UPDATE pacientes_urgencias ";
				$sql .= "SET		estacion_id = '".$estacion[0]."', ";
				$sql .= "				tipo_id_tercero = ".$medico[0].",";
				$sql .= "				tercero_id = ".$medico[1].", ";
				$sql .= "				usuario_id = ".$medico[2]." ";
				$sql .= "WHERE	ingreso = ".$ingreso." ";
				
			}
			
			if(!$rst = $this->ConexionBaseDatos($sql))
					return false;
			
			$sql  = "UPDATE ingresos ";
			$sql .= "SET		departamento_actual = '".$estacion[1]."' ";
			$sql .= "WHERE	ingreso = ".$ingreso."; ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$arreglo = array("nombres"=>$_REQUEST['nombres'],"tipodocumento"=>$_REQUEST['tipodocumento'],
											 "documento"=>$_REQUEST['documento'],"apellidos"=>$_REQUEST['apellidos']);
			$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','BuscarPacientesBD',$arreglo);
				
			$informacion = "EL PACIENTE ESTA EN CONSULTA DE URGENCIAS";
			if($medico[0]) $informacion .= ", CON EL MEDICO ".$medico[3];
			
			$this->FormaMensaje($informacion,"MENSAJE");
			
			return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function BuscarEstacionesPA()
		{
			$PtoAdmon = $_SESSION['AdmHospitalizacion']['ptoadmon'];
			
			$sql  = "SELECT	PA.estacion_id,"; 
			$sql .= "				EF.descripcion,"; 
			$sql .= "				EF.departamento ";
			$sql .= "FROM 	puntos_admisiones_estaciones PA, ";
			$sql .= "				estaciones_enfermeria EF ";
			$sql .= "WHERE 	PA.punto_admision_id = '".$PtoAdmon."' ";
			$sql .= "AND 		PA.estacion_id = EF.estacion_id ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return true;
			
			while(!$rst->EOF)
			{
				$estaciones[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $estaciones;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function CerrarIngreso()
		{
			$arreglo = array("nombres"=>$_REQUEST['nombres'],"tipodocumento"=>$_REQUEST['tipodocumento'],
											 "documento"=>$_REQUEST['documento'],"apellidos"=>$_REQUEST['apellidos']);

			$this->action2 = ModuloGetURL('app','AdmisionHospitalizacion','user','BuscarPacientesBD',$arreglo);
			$arreglo["cuenta"] = $_REQUEST['cuenta'];
			$arreglo["ingreso"] = $_REQUEST['ingreso'];
			
			$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','CerrarIngresoBD',$arreglo);
				
			$informacion = "ESTA SEGURO DE QUE DESEA CERRAR EL INGRESO Nº ".$_REQUEST['ingreso']." ? ";			
			$this->FormaMensaje($informacion,"MENSAJE","Cancelar");
			return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function CerrarIngresoBD()
		{
			$sql .= "UPDATE ingresos ";
			$sql .= "SET		estado = '0' ";
			$sql .= "WHERE	ingreso = ".$_REQUEST['ingreso']."; ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$arreglo = array("nombres"=>$_REQUEST['nombres'],"tipodocumento"=>$_REQUEST['tipodocumento'],
											 "documento"=>$_REQUEST['documento'],"apellidos"=>$_REQUEST['apellidos']);
			
			$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','BuscarPacientesBD',$arreglo);
				
			$informacion = "El INGRESO Nº ".$_REQUEST['ingreso']." SE HA CERRADO ";			
			$this->FormaMensaje($informacion,"MENSAJE");
			return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function Remision()
		{
			$this->action2 = ModuloGetURL('app','AdmisionHospitalizacion','user','Buscar',
																		 array("menu"=>$_SESSION['AdmHospitalizacion']['menu'],
																					 'TIPOORDEN'=>$_SESSION['AdmHospitalizacion']['tipoorden']));
			$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','CrearRemision');
			$this->FormaRemision();
			return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function CentrosRemision()
		{
			$sql = "SELECT * FROM centros_remision";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

			while (!$rst->EOF)
			{
				$vars[]=$rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}

			$rst->Close();
			return $vars;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function CrearRemision($op=null)
		{
			if(!$this->EvaluarRequestRemision())
			{
				if($op != null) return false;
				
				$this->Remision();
				return true;
			}
			
			if(!empty($this->Cargo) AND !empty($this->Entidad) AND  !empty($this->Fecha))
			{
				$sql = " SELECT nextval('pacientes_remitidos_paciente_remitido_id_seq')";
				if(!$rst = $this->ConexionBaseDatos($sql))
					return false;
				
				$this->Numremision = $rst->fields[0];
				$hora='NULL';
				
				if(!empty($this->Hora) AND !empty($this->Min)) $hora = "'".$this->Hora.":".$this->Min."'";
				
				if(empty($this->Remision))	$this->Remision ='NULL';
					
				$sql = "INSERT INTO pacientes_remitidos (
														paciente_remitido_id,
														tipo_id_paciente,
														paciente_id,
														centro_remision,
														numero_remision,
														diagnostico_id,
														observacion,
														fecha_registro,
														usuario_id,
														fecha_remision,
														hora_remision)
								VALUES(	 ".$this->Numremision.",
												'".$_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente']."',
												'".$_SESSION['PACIENTES']['PACIENTE']['paciente_id']."',
												'".$this->Entidad."',
												 ".$this->Remision.",
												'".$this->Codigo."',
												'".$this->Observacion."',
												'now()',
												 ".UserGetUID().",
												'".$this->fec."',
												 ".$hora.")";
					
					if(!$rst = $this->ConexionBaseDatos($sql))
						return false;

					$_SESSION['AdmHospitalizacion']['paciente']['remision'] = $this->Numremision;
					
					if($op != null)	return true;
			}

			$this->AutoHospitalizacion();
			return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function EvaluarRequestRemision()
		{
			$this->Fecha = $_REQUEST['Fecha'];
			$this->Hora = trim($_REQUEST['Hora']);
			$this->Min = trim($_REQUEST['Min']);
			$this->Entidad = $_REQUEST['entidad'];
			$this->Remision = $_REQUEST['remision'];
			$this->Codigo = $_REQUEST['codigo'];
			$this->Cargo = $_REQUEST['cargo'];
			$this->Observacion = $_REQUEST['Observacion'];
			
			//si hay algo tiene que completar toda la remision
			if(!empty($this->Cargo) || !empty($this->Remision) || !empty($this->Entidad) || !empty($this->Fecha))
			{
				if(!empty($this->Hora))
				{
					if(!is_numeric($this->Hora) || $this->Hora >24)
					{
						$this->frmError["MensajeError"]="EL FORMATO DE LA HORA ES INCORRECTO.";
						return false;
					}
				}
				
				if(!empty($this->Min))
				{
					if(!is_numeric($this->Min) || $this->Min >60 )
					{
						$this->frmError["MensajeError"]="EL FORMATO DE LOS MINUTOS ES INCORRECTO.";
						return false;
					}
				}

				if((!empty($this->Hora) && empty($this->Min))
						|| (empty($this->Hora) && !empty($this->Min)))
				{
					$this->frmError["MensajeError"]="DEBE DIGITAR LA HORA COMPLETA.";
					return false;
				}

				if(!empty($this->Fecha))
				{
					$f = explode('/',$this->Fecha);
					
					if(sizeof($f) > 3)
					{
						$this->frmError["MensajeError"] = "EL FORMATO DE FECHA ES INCORRECTO ";
						return false;
					}
					
					$this->fec = $f[2].'-'.$f[1].'-'.$f[0];
			
					if(!$this->ValidarFecha($this->fec))
					{
						return false;
					}
				}

				if(empty($this->Cargo)  || empty($this->Entidad) || empty($this->Fecha))
				{
						if(empty($this->Cargo))	$this->frmError["MensajeError"] = "FALTA EL DIAGNOSTOICO"; 
						
						if(empty($this->Fecha)) $this->frmError["MensajeError"] = "FALTA LA FECHA";  

						if(empty($this->Entidad)) $this->frmError["MensajeError"] = "SELECIONAR LA ENTIDAD";
						
						return false;
				}
				
				if(!empty($this->Remision))
				{
					if(is_numeric($this->Remision)==0)
					{
						$this->frmError["MensajeError"] = "EL NÚMERO DE REMISION DEBE SER NUMERICO.";
						return false;
					}
				}
			}
			return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function BuscarPacientesModificar()
		{
			unset($_SESSION['PACIENTES']);
			$this->action2 = ModuloGetURL('app','AdmisionHospitalizacion','user','MenuHospitalizacion');
			$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','BuscarIngreso');
			
			$this->FormaBuscarPacientesModificar();
      return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function BuscarIngreso()
 		{
			$this->paso = '1';

			$this->Ingreso = $_REQUEST['Ingreso'];
			$this->Historia = $_REQUEST['historia'];
			$this->Documento = $_REQUEST['Documento'];
			$this->TipoId = $_REQUEST['TipoDocumento'];
			$this->Nombres = strtoupper($_REQUEST['nombres']);
			$this->Prefijo = strtoupper($_REQUEST['prefijo']);
			$this->Apellidos = strtoupper($_REQUEST['apellidos']);
			
			$sql  = "SELECT 	PC.tipo_id_paciente, ";
			$sql .= "					PC.paciente_id,";
			$sql .= "					PC.primer_apellido||' '||PC.segundo_apellido AS apellidos, ";
			$sql .= "					PC.primer_nombre||' ' ||PC.segundo_nombre AS nombres, ";
			$sql .= "					IG.ingreso,";
			$sql .= "					CASE WHEN IG.estado = '1' THEN 'ACTIVO' ELSE 'LISTO PARA SALIR' END AS estado, ";
			$sql .= "					CU.tipo_afiliado_id, ";
			$sql .= "					CU.rango, ";
			$sql .= "					CU.plan_id,";
			$sql .= "					CU.numerodecuenta,";
			$sql .= "					HC.historia_numero, ";
			$sql .= "					HC.historia_prefijo ";
									
			$where .= "FROM		cuentas CU, ";
			$where .= "				ingresos IG LEFT JOIN ingresos_soat SI ";
			$where .= "				ON (IG.ingreso = SI.ingreso),";
			$where .= "				pacientes PC, ";
			$where .= "				historias_clinicas HC ";
			$where .= "WHERE 	CU.ingreso = IG.ingreso ";
			$where .= "AND 		IG.estado !=0 ";
			$where .= "AND 		IG.tipo_id_paciente = PC.tipo_id_paciente ";
			$where .= "AND 		IG.paciente_id = PC.paciente_id ";
			$where .= "AND 		IG.tipo_id_paciente = HC.tipo_id_paciente ";
			$where .= "AND 		IG.paciente_id = HC.paciente_id ";				

			if(!empty($this->Prefijo))
				$where .= "AND 		HC.historia_prefijo='".$this->Prefijo."' ";

			if(!empty($this->Historia))
				$where .= "AND 		HC.historia_numero='".$this->Historia."' ";
			
			if (!empty($this->Documento))
			{
				$where .= "AND 		IG.tipo_id_paciente = '".$this->TipoId."' ";
				$where .= "AND 		IG.paciente_id = '".$this->Documento."' ";
			}
			if($this->Nombres != "")
			{
				$nombres = str_replace(" ","|",$this->Nombres);
				$where .= "AND		(	PC.primer_nombre SIMILAR TO '%(".$nombres.")%' "; 
				$where .= "					OR PC.segundo_nombre SIMILAR TO '%(".$nombres.")%') ";
			}
			if($this->Apellidos !="")
			{
				$apellidos = str_replace(" ","|",$this->Apellidos);
				$where .= "AND 		(	PC.primer_apellido SIMILAR TO '%(".$apellidos.")%' ";
				$where .= "					OR PC.segundo_apellido SIMILAR TO '%(".$apellidos.")%') "; 
			}
			
			if(!empty($this->Ingreso))
				$where .= "AND 		IG.ingreso =".$this->Ingreso." ";

			$sqlCont = "SELECT COUNT(*) ".$where;

			if(!$this->ProcesarSqlConteo($sqlCont))
				return false;

			$sql .= $where;
			$sql .= "ORDER BY 1,2 ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			while(!$rst->EOF)
			{
				$this->pacientes[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			$this->action0 = ModuloGetURL('app','AdmisionHospitalizacion','user','BuscarIngreso',
																		array("Ingreso"=>$this->Ingreso,"historia"=>$this->Historia,
																					"Documento"=>$this->Documento,"TipoDocumento"=>$this->TipoId,
																					"nombres"=>$this->Nombres,"prefijo"=>$this->Prefijo, 
																					"apellidos"=>$this->Apellidos ));
			$this->BuscarPacientesModificar();
			return true;
    }
    /************************************************************************************
    *
    *************************************************************************************/
    function MetodoModificarAdmision()
    {
      $this->Nivel = $_REQUEST['Nivel'];
      $this->PlanId = $_REQUEST['PlanId'];
      $this->TipoId = $_REQUEST['TipoId'];
      $this->Ingreso = $_REQUEST['Ingreso'];
      $this->PacienteId = $_REQUEST['PacienteId'];

      if(empty($_SESSION['PACIENTES']))
      {
	      $_SESSION['PACIENTES']['PACIENTE']['paciente_id']=$_REQUEST['PacienteId'];
	      $_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente']=$_REQUEST['TipoId'];
	      $_SESSION['PACIENTES']['PACIENTE']['plan_id']=$_REQUEST['PlanId'];
	      $_SESSION['PACIENTES']['PACIENTE']['nivel']=$_REQUEST['Nivel'];
	      $_SESSION['PACIENTES']['PACIENTE']['ingreso']=$_REQUEST['Ingreso'];
	      $_SESSION['PACIENTES']['RETORNO']['argumentos']=array();
	      $_SESSION['PACIENTES']['RETORNO']['contenedor']='app';
	      $_SESSION['PACIENTES']['RETORNO']['modulo']='AdmisionHospitalizacion';
	      $_SESSION['PACIENTES']['RETORNO']['tipo']='user';
	      $_SESSION['PACIENTES']['RETORNO']['metodo']='MetodoModificarAdmision';
			}
			else
			{
				$this->PacienteId =$_SESSION['PACIENTES']['PACIENTE']['paciente_id'];
	      $this->Ingreso = $_SESSION['PACIENTES']['PACIENTE']['ingreso'];
	      $this->TipoId = $_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente'];
	      $this->PlanId = $_SESSION['PACIENTES']['PACIENTE']['plan_id'];
	      $this->Nivel = $_SESSION['PACIENTES']['PACIENTE']['nivel'];
			}
			$_SESSION['AdmHospitalizacion']['paciente']['plan_id'] = $this->PlanId;
			
			$Paciente = $this->ReturnModuloExterno('app','Pacientes','user');
			if(!is_object($Paciente))
			{
				$this->error = "La clase Pacientes no se pudo instanciar";
				$this->mensajeDeError = "";
				return false;
			}
			if(!$Paciente->LlamarFormaDatosPacienteCreado($this->TipoId,$this->PacienteId,$this->PlanId,$this->Nivel))
			{
				$this->error = $Paciente->error ;
				$this->mensajeDeError = $Paciente->mensajeDeError;
				unset($Paciente);
				return false;
			}
			
			$this->paciente = $Paciente->GetSalida();
			unset($this->Paciente);
			$arreglo = array('TipoId'=>$this->TipoId,'PacienteId'=>$this->PacienteId,
											 'Responsable'=>$this->PlanId,'Ingreso'=>$this->Ingreso);
			
			$this->actionC = ModuloGetURL('app','AdmisionHospitalizacion','user','ModificarIdentificacion',
																		 array('TipoId'=>$this->TipoId,'PacienteId'=>$this->PacienteId));
			
			$this->actionU = ModuloGetURL('app','AdmisionHospitalizacion','user','UnificarHistorias',
																		 array('TipoId'=>$this->TipoId,'PacienteId'=>$this->PacienteId));
			
			$this->action2 = ModuloGetURL('app','AdmisionHospitalizacion','user','BuscarPacientesModificar');
			
			$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','ModificarDatosAdmision',$arreglo);
						
			if(sizeof($this->ObtenerRemisiones()) > 1)
			{
				$this->action3 = ModuloGetURL('app','AdmisionHospitalizacion','user','ModificarDatosRemision',$arreglo);
				$this->Boton = "Modificar Remision" ;
			}
			else
			{
				$this->action3 = ModuloGetURL('app','AdmisionHospitalizacion','user','AgregarDatosRemision',$arreglo);
				$this->Boton = "Adicionar Remision" ;
			}
				
    	$this->FormaModificarAdmision();
    	return true;
    }
    /************************************************************************************
    *
    *************************************************************************************/
    function BuscarPlanes($PlanId,$Ingreso)
    {
    	$sql = "SELECT sw_tipo_plan FROM planes WHERE plan_id=".$PlanId." ";
      if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				
			if(!$rst->EOF)
			{
				$this->sw_tipo_plan = $rst->fields[0];
				$rst->MoveNext();
			}
			$rst->Close();
			 
      switch($this->sw_tipo_plan)
      {
      	case '0':
        	$sql  = "SELECT PL.tipo_tercero_id AS tipo_id_tercero,";
        	$sql .= "				PL.tercero_id, ";
        	$sql .= "				PL.plan_descripcion,"; 
        	$sql .= "				PL.protocolos,";
        	$sql .= "				TC.nombre_tercero ";
          $sql .= "FROM 	planes PL, "; 
          $sql .= "				terceros TC ";
          $sql .= "WHERE 	PL.plan_id = ".$PlanId." "; 
          $sql .= "AND 		PL.tipo_tercero_id = TC.tipo_id_tercero ";
          $sql .= "AND 		PL.tercero_id = TC.tercero_id ";
        break;
      	case '1':
      		$sql  = "SELECT TC.nombre_tercero, ";
      		$sql .= "				SP.tipo_id_tercero, ";
      		$sql .= "				SP.tercero_id, ";
      		$sql .= "				PL.protocolos, ";
          $sql .= "				PL.plan_descripcion ";
          $sql .= "FROM 	ingresos_soat SI, "; 
          $sql .= " 			terceros TC, "; 
          $sql .= "       soat_eventos SE,";
          $sql .= "	      soat_polizas SP, ";
          $sql .= "				planes PL ";
          $sql .= "WHERE 	SI.ingreso = ".$Ingreso." "; 
          $sql .= "AND 		SI.evento = SE.evento ";
          $sql .= "AND 		SP.tipo_id_tercero = TC.tipo_id_tercero ";
          $sql .= "AND 		SP.tercero_id = TC.tercero_id ";
          $sql .= "AND 		SE.poliza = SP.poliza ";
          $sql .= "AND 		PL.tipo_tercero_id = TC.tipo_id_tercero ";
          $sql .= "AND 		PL.tercero_id = TC.tercero_id ";
      	break;
      	case '2'://PARTICULAR
          $sql  = "SELECT PC.primer_nombre||' '||PC.segundo_nombre||' '||PC.primer_apellido||' '||PC.segundo_apellido AS nombre_tercero,";
					$sql .= "				PC.tipo_id_paciente AS tipo_id_tercero, ";
					$sql .= "				PC.paciente_id AS tercero_id, ";
					$sql .= "				PL.plan_descripcion, ";
					$sql .= "				PL.protocolos ";
					$sql .= "FROM		ingresos IG, ";
					$sql .= "				pacientes PC, ";
					$sql .= "				planes PL ";
					$sql .= "WHERE 	IG.ingreso =".$Ingreso." ";
					$sql .= "AND 		IG.paciente_id = PC.paciente_id ";
					$sql .= "AND 		IG.tipo_id_paciente = PC.tipo_id_paciente ";
					$sql .= "AND 		PL.plan_id=".$PlanId." ";
      	break;
      	case '3'://CAPITADO
					$sql  = "SELECT PL.tipo_tercero_id AS tipo_id_tercero,";
					$sql .= "				PL.tercero_id, ";
					$sql .= "				PL.plan_descripcion, ";
					$sql .= "				PL.protocolos,";
					$sql .= "				TC.nombre_tercero "; 
					$sql .= "FROM		planes PL, ";
					$sql .= "				terceros TC ";
					$sql .= "WHERE 	PL.plan_id=".$PlanId." "; 
					$sql .= "AND 		PL.tipo_tercero_id = TC.tipo_id_tercero ";
					$sql .= "AND 		PL.tercero_id = TC.tercero_id ";

      	break;
      }

			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				
			if(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
    	return $datos;
    }
    /************************************************************************************
    *
    *************************************************************************************/
    function BuscarDatosIngresoPaciente($Ingreso)
    {
    	$sql  = "SELECT SE.poliza,";
    	$sql .= "				TO_CHAR(IG.fecha_ingreso,'DD/MM/YYYY') AS fecha_ingreso, ";
    	$sql .= "				IG.causa_externa_id, ";
    	$sql .= "				IG.via_ingreso_id,";
      $sql .= "       IG.comentario, ";
      $sql .= "       CU.tipo_afiliado_id ";
      $sql .= "FROM 	ingresos IG LEFT JOIN ingresos_soat SI";
      $sql .= " 			ON (IG.ingreso = SI.ingreso) ";
      $sql .= "       LEFT JOIN soat_eventos SE ";
      $sql .= "				ON (SI.evento = SE.evento), ";
      $sql .= "				cuentas CU ";
      $sql .= "WHERE 	IG.ingreso = ".$Ingreso." ";
      $sql .= "AND 		CU.ingreso = IG.ingreso ";
      
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				
			if(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
      return $datos ;
    }
    /************************************************************************************
    *
    *************************************************************************************/
    function BuscarViaIngreso()
    {
    	$sql = "SELECT * FROM vias_ingreso order by via_ingreso_id";
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;                        

      while (!$rst->EOF)
      {
      	$vars[$rst->fields[0]] = $rst->fields[1];
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $vars;
    }
    /************************************************************************************
    *
    *************************************************************************************/
    function BuscarTipoAfiliado($plan)
		{
				$sql  = "SELECT DISTINCT TA.tipo_afiliado_nombre, ";
				$sql .= "				TA.tipo_afiliado_id ";
				$sql .= "FROM 	tipos_afiliado TA,";
				$sql .= "				planes_rangos PR ";
				$sql .= "WHERE 	PR.plan_id= ".$plan." ";
				$sql .= "and 		PR.tipo_afiliado_id = TA.tipo_afiliado_id ";
				
				if(!$rst = $this->ConexionBaseDatos($sql))
					return false;

				while(!$rst->EOF)
				{
					$vars[] = $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
				}
				$rst->Close();
				return $vars;
		}
		/************************************************************************************
		*
		*************************************************************************************/
	  function BuscarNiveles($plan)
   	{
			$sql = "SELECT DISTINCT rango FROM planes_rangos WHERE plan_id = ".$plan." ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

			while(!$rst->EOF)
			{
				$nivel[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $nivel;
   	}
   	/************************************************************************************
   	*
   	*************************************************************************************/
   	function ModificarIdentificacion()
    {
      $_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente'] = $_REQUEST['TipoId'];
      $_SESSION['PACIENTES']['PACIENTE']['paciente_id'] = $_REQUEST['PacienteId'];
      $_SESSION['PACIENTES']['RETORNO']['argumentos'] = array();
	    $_SESSION['PACIENTES']['RETORNO']['contenedor'] = 'app';
	    $_SESSION['PACIENTES']['RETORNO']['modulo'] = 'AdmisionHospitalizacion';
	    $_SESSION['PACIENTES']['RETORNO']['metodo'] = 'MetodoModificarAdmision';
	    $_SESSION['PACIENTES']['RETORNO']['tipo'] = 'user';

      $this->ReturnMetodoExterno('app','Pacientes','user','CambiarIdentificacionPaciente');
      return true;
    }
    /************************************************************************************
    *
    *************************************************************************************/
    function UnificarHistorias()
		{
		  $_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente'] = $_REQUEST['TipoId'];
		  $_SESSION['PACIENTES']['PACIENTE']['paciente_id'] = $_REQUEST['PacienteId'];
		  $_SESSION['PACIENTES']['RETORNO']['argumentos'] = array();
		  $_SESSION['PACIENTES']['RETORNO']['contenedor'] = 'app';
		  $_SESSION['PACIENTES']['RETORNO']['modulo'] = 'AdmisionHospitalizacion';
		  $_SESSION['PACIENTES']['RETORNO']['metodo'] = 'MetodoModificarAdmision';
		  $_SESSION['PACIENTES']['RETORNO']['tipo'] = 'user';
		
		  $this->ReturnMetodoExterno('app','Pacientes','user','UnificarHistorias');
		  return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function ModificarDatosRemision()
		{
      $this->PlanId = $_REQUEST['Responsable'];
      $this->TipoId = $_REQUEST['TipoId'];
      $this->Ingreso = $_REQUEST['Ingreso'];
      $this->PacienteId = $_REQUEST['PacienteId'];
      
			$arreglo = $this->ObtenerRemisiones();
    	$hora = explode(":",$arreglo['hora_remision']);
			
			$this->Min = $hora[1];
			$this->Hora = $hora[0];
			$this->Fecha = $arreglo['fecha_remision'];
			$this->Cargo = $arreglo['diagnostico_nombre'];
			$this->Codigo = $arreglo['diagnostico_id'];
			$this->Entidad = $arreglo['centro_remision'];
			$this->Remision = $arreglo['numero_remision'];
			$this->Observacion = $arreglo['observacion'];
			
			$request = array("remisionId"=>$arreglo['paciente_remitido_id'],"TipoId"=>$this->TipoId,
											 "PlanId"=>$this->PlanId,"Ingreso"=>$this->Ingreso,"PacienteId"=>$this->PacienteId);
			
			$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','ModificarRemisionBD',$request);
			$this->action2 = ModuloGetURL('app','AdmisionHospitalizacion','user','MetodoModificarAdmision');
			$this->FormaRemision();
			return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function AgregarDatosRemision()
		{
 			$this->PlanId = $_REQUEST['Responsable'];
      $this->TipoId = $_REQUEST['TipoId'];
      $this->Ingreso = $_REQUEST['Ingreso'];
      $this->PacienteId = $_REQUEST['PacienteId'];
			
			$request = array("TipoId"=>$this->TipoId,"PlanId"=>$this->PlanId,
											 "Ingreso"=>$this->Ingreso,"PacienteId"=>$this->PacienteId);
			
			$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','AgregarDatosRemisionBD',$request);
			$this->action2 = ModuloGetURL('app','AdmisionHospitalizacion','user','MetodoModificarAdmision');
			$this->FormaRemision();
			return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function AgregarDatosRemisionBD()
		{
			$this->PlanId = $_REQUEST['PlanId'];
      $this->TipoId = $_REQUEST['TipoId'];
      $this->Ingreso = $_REQUEST['Ingreso'];
      $this->PacienteId = $_REQUEST['PacienteId'];
			
			if(!$this->CrearRemision(1))
			{
				$request = array("TipoId"=>$this->TipoId,"PlanId"=>$this->PlanId,
												 "Ingreso"=>$this->Ingreso,"PacienteId"=>$this->PacienteId);
				
				$this->action2 = ModuloGetURL('app','AdmisionHospitalizacion','user','MetodoModificarAdmision');
				$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','AgregarDatosRemisionBD',$request);
				$this->FormaRemision();
				return true;
			}				
			
			$sql  = "UPDATE pacientes_remitidos ";
			$sql .= "SET		ingreso = ".$this->Ingreso." ";
			$sql .= "WHERE	paciente_remitido_id = ".$this->Numremision." ";
			$sql .= "AND		tipo_id_paciente = '".$this->TipoId."' ";
			$sql .= "AND		paciente_id = '".$this->PacienteId."' ";
				
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','MetodoModificarAdmision');
			$mensaje = "LOS DATOS DE LA REMISION FUERON GUARDADOS CORRECTAMENTE";					
			$this->FormaMensaje($mensaje,'MENSAJE');
			return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function ModificarRemisionBD()
		{
			$this->PlanId = $_REQUEST['PlanId'];
      $this->TipoId = $_REQUEST['TipoId'];
      $this->Ingreso = $_REQUEST['Ingreso'];
      $this->PacienteId = $_REQUEST['PacienteId'];
			
			if(!$this->EvaluarRequestRemision())
			{
				$request = array("remisionId"=>$_REQUEST['remisionId'],"TipoId"=>$this->TipoId,
												 "PlanId"=>$this->PlanId,"Ingreso"=>$this->Ingreso,"PacienteId"=>$this->PacienteId);
				
				$this->action2 = ModuloGetURL('app','AdmisionHospitalizacion','user','MetodoModificarAdmision');
				$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','ModificarRemisionBD',$request);
				$this->FormaRemision();
				return true;
			}
			
			if(!empty($this->Cargo) AND !empty($this->Entidad) AND  !empty($this->Fecha))
			{
				$hora='NULL';
				$remision = $_REQUEST['remisionId'];
				
				if(!empty($this->Hora) && !empty($this->Min)) $hora = "'".$this->Hora.":".$this->Min."'";
				
				if(empty($this->Remision))	$this->Remision ='NULL';
					
				$sql  = "UPDATE pacientes_remitidos ";
				$sql .= "SET		centro_remision = '".$this->Entidad."', ";
				$sql .= "				numero_remision = ".$this->Remision.",";
				$sql .= "				diagnostico_id = '".$this->Codigo."',";
				$sql .= "				observacion = '".$this->Observacion."',";
				$sql .= "				fecha_remision = '".$this->fec."',";
				$sql .= "				hora_remision = ".$hora." ";
				$sql .= "WHERE	paciente_remitido_id = ".$remision." ";
				$sql .= "AND		tipo_id_paciente = '".$this->TipoId."' ";
				$sql .= "AND		paciente_id = '".$this->PacienteId."' ";
				$sql .= "AND		ingreso = ".$this->Ingreso." ";
				
				if(!$rst = $this->ConexionBaseDatos($sql))
					return false;
				
				$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','MetodoModificarAdmision');
				$mensaje = "LOS DATOS DE LA REMISION SE ACTUALIZARON CORRECTAMENTE";					
				$this->FormaMensaje($mensaje,'MENSAJE');
				return true;
			}
			
			$this->MetodoModificarAdmision();
			return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function ModificarDatosAdmision()
    {
			$TipoId = $_REQUEST['TipoId'];
			$PlanId = $_REQUEST['Responsable'];
			$Ingreso = $_REQUEST['Ingreso'];
			$PacienteId = $_REQUEST['PacienteId'];
			
			$Nivel = $_REQUEST['Nivel'];
			$Poliza = $_REQUEST['Poliza'];
			$PolizaAnt = $_REQUEST['PolizaAnt'];
			$SwTipoPlan = $_REQUEST['SwPlan'];
			$ViaIngreso = $_REQUEST['ViaIngreso'];
			$Comentarios = $_REQUEST['Comentario'];
			$FechaIngreso = $_REQUEST['FechaIngreso'];
			$TipoAfiliado = $_REQUEST['TipoAfiliado'];
			
			switch($SwTipoPlan)
			{
				case '1':
						if($ViaIngreso==-1 || $Estado==-1 || $Poliza=='')
						{
							if($ViaIngreso==-1) $this->frmError["MensajeError"] = "SE DEBE SELECCIONAR LA VIA DE INGRESO DEL PACIENTE"; 
							
							if($Poliza=='')	$this->frmError["MensajeError"] = "SE DEBE INDICAR LA POLIZA"; 
							
							$this->MetodoModificarAdmision();
							return true;						
						}
				break;
				default:
						if($ViaIngreso ==-1 || $TipoAfiliado ==-1)
						{
							if($ViaIngreso==-1)	$this->frmError["MensajeError"] = "SE DEBE SELECCIONAR LA VIA DE INGRESO DEL PACIENTE"; 
							
							if($TipoAfiliado==-1)	$this->frmError["MensajeError"] = "SE DEBE SELECCIONAR EL TIPO DE AFILIADO"; 
							
							$this->MetodoModificarAdmision();
							return true;
						}
				break;
			}
			
			$f = explode('/',$FechaIngreso);
			$this->fec = $f[2].'-'.$f[1].'-'.$f[0];
			
			if(!$this->ValidarFecha($this->fec))
			{
				$this->MetodoModificarAdmision();
				return true;
			}

			if($Poliza)
			{
				$sql .= "UPDATE soat_polizas "; 
				$sql .= "SET		poliza = '".$Poliza."'  ";
				$sql .= "WHERE 	poliza = '".$PolizaAnt."'; ";

			}
			$sql .= "UPDATE ingresos ";
			$sql .= "SET		fecha_ingreso='".$this->fec."', ";
			$sql .= "				via_ingreso_id='".$ViaIngreso."', ";
			$sql .= "				comentario='".$Comentarios."' ";
			$sql .= "WHERE 	ingreso = ".$Ingreso."; ";
			
			$sql .= "UPDATE cuentas ";
			$sql .= "SET		tipo_afiliado_id='".$TipoAfiliado."', ";
			$sql .= "				rango ='".$Nivel."' ";
			$sql .= "WHERE 	ingreso= ".$Ingreso."; ";

			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

			$mensaje = "LA ACTUALIZACIÓN SE REALIZÓ CORRECTAMENTE";					
			$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','BuscarPacientesModificar');
			$this->FormaMensaje($mensaje,'MENSAJE');
			return true;
    }
    /************************************************************************************
    *
    *************************************************************************************/
    function ListarPacientesTriages()
    {
			if(!empty($_REQUEST['TIPOORDEN'])) 
				$_SESSION['AdmHospitalizacion']['tipoorden'] = $_REQUEST['TIPOORDEN'];
			
			if(!empty($_REQUEST['menu']))
				$_SESSION['AdmHospitalizacion']['menu'] = $_REQUEST['menu'];
			
			if(!empty($_REQUEST['orden1']))
				$_SESSION['AdmHospitalizacion']['orden1'] = $_REQUEST['orden1'];
			
			$this->action0 = ModuloGetURL('app','AdmisionHospitalizacion','user','ListarPacientesTriages');
			$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','MenuHospitalizacion');

			$this->FormaListarPacientesTriages();
			
			return true;  
    }
    /************************************************************************************
    *
    *************************************************************************************/
    function BuscarPacientesTriages()
    {
    	$pto = $_SESSION['AdmHospitalizacion']['ptoadmon'];
    	$dpto = $_SESSION['AdmHospitalizacion']['deptno'];
    	$empresa = $_SESSION['AdmHospitalizacion']['empresa'];

			$sql  = "SELECT 	TR.nivel_triage_id, ";
			$sql .= "					TR.triage_id,";
			$sql .= "					TR.plan_id,";
			$sql .= "					TR.nivel_triage_asistencial,";
			$sql .= "					TR.punto_admision_id, ";
			$sql .= "					TR.paciente_id, ";
			$sql .= "					TR.motivo_consulta, ";
			$sql .= "					TR.tipo_id_paciente,";
			$sql .= "					TO_CHAR(hora_llegada,'DD/MM/YYYY') AS hora_llegada, ";
			$sql .= "					TO_CHAR(hora_llegada,'HH24:MM:SS') AS fecha_ingreso, ";
			$sql .= "					PC.primer_nombre||' '||PC.segundo_nombre AS nombres, ";
			$sql .= "					PC.primer_apellido||' '||PC.segundo_apellido AS apellidos ";
			$where  = "FROM 	triages TR, ";
			$where .= "				pacientes PC ";
			$where .= "WHERE 	TR.sw_estado in (1,4) ";
			$where .= "AND 		TR.empresa_id = '".$empresa."' ";
			$where .= "AND 		TR.sw_no_atender = 0 ";
			$where .= "AND 		TR.punto_admision_id = ".$pto." ";
			$where .= "AND		TR.tipo_id_paciente = PC.tipo_id_paciente ";
			$where .= "AND		TR.paciente_id = PC.paciente_id ";
			
			$sqlCont = "SELECT COUNT(*) ".$where;
			if(!$this->ProcesarSqlConteo($sqlCont))
				return false;
					
			$sql .= $where;
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
											
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

			while(!$rst->EOF)
			{
				$triages[]=$rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $triages;
    }
    /************************************************************************************
    *
    *************************************************************************************/
    function ExcluirPacienteLista()
		{
			$this->TipoIdPaciente = $_REQUEST['TipoId'];
			$this->PacienteId = $_REQUEST['PacienteId'];
			$this->TriageId = $_REQUEST['Triage'];
			
			$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','ExcluirPacienteBD',
																		 array('triage_id'=>$this->TriageId,
																		 			 'tipo_id_paciente'=>$this->TipoIdPaciente,
																		 			 'paciente_id'=>$this->PacienteId));
																		 			 
			$this->action2 = ModuloGetURL('app','AdmisionHospitalizacion','user','ListarPacientesTriages');
			
			$this->frmError['Informacion'] = "EL PACIENTE: ".$_REQUEST['Nombre'].", 
																				IDENTIFICADO CON ".$this->TipoIdPaciente." ".$this->PacienteId.",
																				SERÁ EXCLUIDO DEL LISTADO Y SE CANCELARA SU PROCESO DE ATENCION EN LA 
																				INSTITUCION, POR FAVOR ESPECIFIQUE EL MOTIVO DE LA EXCLUSIÓN";
						
			$this->FormaExcluirPacienteLista();
			return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function ExcluirPacienteBD()
		{
			if(empty($_REQUEST['observacion']))
			{
				$this->frmError["MensajeError"] = 'DEBE ESCRIBIR EL MOTIVO.';
				$this->ExcluirPacienteLista();
				return true;
			}

			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
	 	  $sql = "INSERT INTO egresos_no_atencion (
													tipo_id_paciente,
													paciente_id,
													triage_id,
													observacion,
													fecha_registro,
													usuario_id)
							VALUES(	'".$_REQUEST['tipo_id_paciente']."',
											'".$_REQUEST['paciente_id']."',
											 ".$_REQUEST['triage_id'].",
											'".$_REQUEST['observacion']."',
											'now()',
											".UserGetUID().")";
			$dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error INSERT INTO egresos_no_atencion";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}

			if(!empty($_REQUEST['triage_id']))
			{
				$sql = "UPDATE triages SET sw_estado=9
								WHERE triage_id=".$_REQUEST['triage_id']."";
				$results = $dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0) 
				{
					$this->error = "Error update PACIENTES_URGENCIAS";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
			}
			
			$dbconn->CommitTrans();
			
			$this->frmError["Informacion"]='EL PACIENTE FUE EXCLUIDO DE LA LISTA';
			$this->ListarPacientesTriages();
			return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function AdmitirPaciente()
		{
			$_SESSION['AdmHospitalizacion']['paciente']['tipo_id_paciente'] = $_REQUEST['TipoId'];
			$_SESSION['AdmHospitalizacion']['paciente']['paciente_id'] = $_REQUEST['PacienteId'];
			$_SESSION['AdmHospitalizacion']['paciente']['triage_id'] = $_REQUEST['Triage'];
			$_SESSION['AdmHospitalizacion']['paciente']['plan_id'] = $_REQUEST['Responsable'];
			$_SESSION['AdmHospitalizacion']['paciente']['admitir'] = $_REQUEST['Nivel'];
			unset($_SESSION['AUTORIZACIONES']);
			$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'] = $_SESSION['AdmHospitalizacion']['paciente']['plan_id'];
			$_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id'] = $_SESSION['AdmHospitalizacion']['paciente']['paciente_id'];
			$_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente'] = $_SESSION['AdmHospitalizacion']['paciente']['tipo_id_paciente'];

			if(empty($_SESSION['AdmHospitalizacion']['protocolo']))
			{
				$sql = "SELECT protocolos FROM planes	WHERE plan_id=".$_REQUEST['Responsable']." ";
				if(!$rst = $this->ConexionBaseDatos($sql))
					return false;
	
				$_SESSION['AdmHospitalizacion']['protocolo'] = $rst->fields[0];
				$rst->Close();
			}
			
			$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO'] = $_SESSION['AdmHospitalizacion']['tipo'];
			$_SESSION['AUTORIZACIONES']['AUTORIZAR']['EMPLEADOR'] = true;
			$_SESSION['AUTORIZACIONES']['AUTORIZAR']['SERVICIO'] = $_SESSION['AdmHospitalizacion']['servicio'];
			$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_AUTORIZACION']='Admon';
			$_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARGUMENTOS'] = array();
			$_SESSION['AUTORIZACIONES']['RETORNO']['contenedor'] = 'app';
			$_SESSION['AUTORIZACIONES']['RETORNO']['modulo'] = 'AdmisionHospitalizacion';
			$_SESSION['AUTORIZACIONES']['RETORNO']['metodo'] = 'RetornoAutorizacion';
			$_SESSION['AUTORIZACIONES']['RETORNO']['tipo'] = 'user';
			
			$this->ReturnMetodoExterno('app','Autorizacion','user','SolicitudAutorizacion');
			return true;
    }
    /************************************************************************************
    *
    *************************************************************************************/
    function ObtenerRemisiones()
    {
    	$sql  = "SELECT	RE.paciente_remitido_id,";
    	$sql .= "				RE.paciente_id,";
    	$sql .= "				RE.tipo_id_paciente,";
    	$sql .= "				RE.numero_remision,";
    	$sql .= "				TO_CHAR(RE.fecha_remision,'DD/MM/YYYY') AS fecha_remision,";
    	$sql .= "				TO_CHAR(RE.hora_remision,'HH24:MM') AS hora_remision,";
    	$sql .= "				RE.observacion,";
    	$sql .= "				RE.fecha_registro,"; 	 	
    	$sql .= "				RE.triage_id,";
    	$sql .= "				RE.centro_remision,";
    	$sql .= "				DG.diagnostico_id, ";
    	$sql .= "				DG.diagnostico_nombre ";
    	$sql .= "FROM		pacientes_remitidos RE LEFT JOIN diagnosticos DG ";
    	$sql .= "				ON (RE.diagnostico_id = DG.diagnostico_id)  ";
    	$sql .= "WHERE	RE.paciente_id = '".$this->PacienteId."' ";
    	$sql .= "AND		RE.tipo_id_paciente = '".$this->TipoId."' ";
    	$sql .= "AND		RE.ingreso = ".$this->Ingreso." ";
    	
    	if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

			if(!$rst->EOF)
			{
				$remision = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
    	
    	return $remision;
    }
		/***********************************************************************************
		*
		************************************************************************************/
		function PagarEnCaja()
		{
			$sql = "SELECT	a.caja_id, 
											b.sw_todos_cu, 
											b.empresa_id, 
											b.centro_utilidad,
											b.ip_address,
											b.descripcion as descripcion3, 
											b.tipo_numeracion, 
											d.razon_social as descripcion1,
											e.descripcion as descripcion2, 
											b.cuenta_tipo_id, 
											a.caja_id,
											b.tipo_numeracion_devoluciones
							FROM 		cajas_usuarios as a, 
											cajas as b, 
											documentos as c, 
											empresas as d, 
											centros_utilidad as e
							WHERE 	a.usuario_id=".UserGetUID()." 
							AND 		a.caja_id=b.caja_id
							AND			b.centro_utilidad='".$_SESSION['AdmHospitalizacion']['ctrutilidad']."' 
							AND 		b.cuenta_tipo_id='01'
							AND 		b.empresa_id=d.empresa_id 
							AND 		d.empresa_id=e.empresa_id
							AND 		b.centro_utilidad=e.centro_utilidad 
							AND 		b.tipo_numeracion=c.documento_id
							ORDER BY d.empresa_id, b.centro_utilidad, a.caja_id";
			list($dbconn) = GetDBconn();
      GLOBAL $ADODB_FETCH_MODE;
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$rst = $dbconn->Execute($sql);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				return false;
			}
			
			$metodo = $this->ObtenerMetodo();
			
			if(!$rst->EOF)
			{	
				while($data = $rst->FetchRow())
				{
					$caja[$data['descripcion1']][$data['descripcion2']][$data['descripcion3']]=$data;
				}

				$url[0]='app';
				$url[1]='AdmisionHospitalizacion';
				$url[2]='user';
				$url[3]='Cajas';
				$url[4]='Caja';
				$arreglo[0]='EMPRESA';
				$arreglo[1]='CENTRO UTILIDAD';
				$arreglo[2]='CAJA';	
				
				$this->salida.= gui_theme_menu_acceso('CAJAS',$arreglo,$caja,$url,
														ModuloGetURL('app','AdmisionHospitalizacion','user',$metodo));
			}
			else
			{	
				$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user',$metodo);  
	
				$mensaje = "ESTE USUARIO NO POSEE CAJAS ASOCIADAS";
				$this->FormaMensaje($mesanje,'MENSAJE');
			}
			return true;
		}
    /************************************************************************************
		*
		*************************************************************************************/
		function Cajas()
		{
			$metodo = $this->ObtenerMetodo();
			
      if(GetIPAddress()!=$_REQUEST['Caja']['ip_address'] AND !empty($_REQUEST['Caja']['ip_address']))
      {
        $this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user',$metodo); 
				$mensaje = "NO PUEDE ACCESAR A LA CAJA [ ".$_REQUEST['Caja']['descripcion3']." ] DESDE LA IP [ ".GetIPAddress()." ]";
        $this->FormaMensaje($mensaje,'ERROR DE CONEXION A CAJA');
        return true;
      }

			unset($_SESSION['CAJA']);
			$_SESSION['CAJA']['CU'] = $_REQUEST['Caja']['sw_todos_cu'];
			$_SESSION['CAJA']['CAJAID'] = $_REQUEST['Caja']['caja_id'];
			$_SESSION['CAJA']['EMPRESA'] = $_REQUEST['Caja']['empresa_id'];
			$_SESSION['CAJA']['TIPOCUENTA'] = $_REQUEST['Caja']['cuenta_tipo_id'];
			$_SESSION['CAJA']['CENTROUTILIDAD'] = $_REQUEST['Caja']['centro_utilidad'];
			$_SESSION['CAJA']['TIPONUMERACION'] = $_REQUEST['Caja']['tipo_numeracion'];
			$_SESSION['CAJA']['TIPONUMERACION_DEVOLUCIONES'] = $_REQUEST['Caja']['tipo_numeracion_devoluciones'];

			$sql = "SELECT	a.tipo_id_paciente, 
											a.paciente_id, 
											b.numerodecuenta, 
											b.plan_id,
											b.rango, 
											b.ingreso, 
											a.fecha_registro
							FROM 		ingresos as a, 
											cuentas as b
							WHERE 	a.ingreso = ".$_SESSION['ADMISIONES']['PACIENTE']['INGRESO']."
							AND 		a.ingreso = b.ingreso";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

			$var = $rst->GetRowAssoc($ToUpper = false);
			$rst->Close();

			$_SESSION['CAJA']['CUENTA'] = $var[numerodecuenta];
			$_SESSION['CAJA']['RETORNO']['tipo'] = 'user';
			$_SESSION['CAJA']['RETORNO']['metodo'] = 'PagarEnCaja';
			$_SESSION['CAJA']['RETORNO']['modulo'] = 'AdmisionHospitalizacion';
			$_SESSION['CAJA']['RETORNO']['contenedor'] = 'app';
			$_SESSION['CAJA']['RETORNO']['argumentos'] = array();

			$vector=array('Cuenta'=>$var[numerodecuenta],'TipoId'=>$var[tipo_id_paciente],'PacienteId'=>$var[paciente_id],'Nivel'=>$var[rango],'PlanId'=>$var[plan_id],'FechaC'=>$var[fecha_registro],'Ingreso'=>$var[ingreso]);
			$this->ReturnMetodoExterno('app','CajaGeneral','user','CajaHospitalaria',$vector);
			return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function ObtenerMetodo()
		{
			$metodo = "";
			if($_SESSION['AdmHospitalizacion']['tipoorden'] == 'Externa')
			{
				$metodo = "Buscar";  
			}
			else if(!empty($_SESSION['AdmHospitalizacion']['orden1']))
				{
					$metodo = "ListarPacientesTriages";
				}
				else if(!empty($_SESSION['AdmHospitalizacion']['cirugia']))
					{
						$metodo = "OrdenHospitalizacionCirugia";
					}
					else
						{  
							$metodo = "ListadoAdmisionHospitalizacion";
						}
			return $metodo; 
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function AdmitirTriage()
		{
			if(!$_SESSION['PACIENTES']['RETORNO']['PASO'])
			{
				$this->Buscar();
				return true;
			}
			$this->Puntos = $this->PuntosDeTriage($_SESSION['AdmHospitalizacion']['ptoadmon']);
			$this->action[0] = ModuloGetURL('app','AdmisionHospitalizacion','user','AdmisionPacienteTriage',
																			 array('datos'=>$this->Puntos));
			$this->action[1] = ModuloGetURL('app','AdmisionHospitalizacion','user','Buscar',
																			 array('TIPOORDEN'=>$_SESSION['AdmHospitalizacion']['tipoorden'],
																						 'menu'=>$_SESSION['AdmHospitalizacion']['menu']));
			$this->FormaAdmitirTriage();
			return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/		
		function PuntosDeTriage($punto)
		{
			$datos = array();
			$sql .= " SELECT	PA.punto_triage_id, 
												PT.descripcion || ' [ ' ||( SELECT COUNT(*) AS numero
																										FROM   triages 
																										WHERE  sw_estado='0' 
																										AND    punto_triage_id = PA.punto_triage_id  
																									)|| ' ]' AS descripcion
								FROM 		puntos_triage_admision PA, 
												puntos_triage PT
								WHERE 	PA.punto_triage_id = PT.punto_triage_id
								AND 		PA.punto_admision_id = ".$punto." ";
		 	if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function AdmisionPacienteTriage()
    {
			if($_REQUEST['punto_triage'] == '-1')
			{
				$this->frmError['MensajeError'] = "POR FAVOR SELECCIONAR UN PUNTO DE TRIAGE";
				$this->AdmitirTriage();
			}
			else
			{
				$PuntoTriage = explode("ç",$_REQUEST['punto_triage']);
				
				$PacienteId = $_SESSION['PACIENTES']['PACIENTE']['paciente_id'];
				$Cutilidad = $_SESSION['AdmHospitalizacion']['ctrutilidad'];
				$Empresa = $_SESSION['AdmHospitalizacion']['empresa'];
		    $TipoId = $_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente'];
		    $PlanId = $_SESSION['PACIENTES']['PACIENTE']['plan_id'];
				$Dpto = $_SESSION['AdmHospitalizacion']['deptno'];
				$Pto = $_SESSION['AdmHospitalizacion']['ptoadmon'];
				
	      list($dbconn) = GetDBconn();
				$dbconn->BeginTrans();

				$sql ="SELECT nextval('triages_triage_id_seq') ";
				$rst = $dbconn->Execute($sql);
				$TriageId = $rst->fields[0];

				$sql  = "INSERT INTO triages ";
				$sql .= "				(	triage_id,";
				$sql .= "					hora_llegada,";
				$sql .= "					tipo_id_paciente,";
				$sql .= "					paciente_id,";
				$sql .= "					plan_id,";
				$sql .= "					nivel_triage_id,";
				$sql .= "					usuario_id,";
				$sql .= "					empresa_id,";
				$sql .= "					centro_utilidad,";
				$sql .= "					punto_admision_id,";
				$sql .= "					departamento,";
				$sql .= "					punto_triage_id";
				$sql .= "				) ";
				$sql .= "VALUES( 	";
				$sql .= "					 ".$TriageId .",";
				$sql .= "					 NOW(),";
				$sql .= "					'".$TipoId."',";
				$sql .= "					'".$PacienteId."',";
				$sql .= "					 ".$PlanId.",";
				$sql .= "					'0',";
				$sql .= "					 ".UserGetUID().",";
				$sql .= "					'".$Empresa."',";
				$sql .= "					'".$Cutilidad."',";
				$sql .= "					 ".$Pto.",";
				$sql .= "					'".$Dpto."',";
				$sql .= "					 ".$PuntoTriage[0].")";
				
				$dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0) 
				{
					$this->error = "Error al Guardar en triages";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg()." ".$sqlF;
					$dbconn->RollbackTrans();
					return false;
				}
				else
				{				
					$dbconn->CommitTrans();
					$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','Buscar',
																			   array('TIPOORDEN'=>$_SESSION['AdmHospitalizacion']['tipoorden'],
																							 'menu'=>$_SESSION['AdmHospitalizacion']['menu']));
					$nombrepto = explode("[",$PuntoTriage[1]);																		 
					$mensaje = "EL PACIENTE PASA A CLASIFICACIÓN DE TRIAGE AL PUNTO ".$nombrepto[0];
					$this->FormaMensaje($mensaje,'PACIENTES CLASIFICACION TRIAGE');
				}
			}
			return true;	
    }
		/************************************************************************************
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la consulta 
		* sql 
		* 
		* @param string sentencia sql a ejecutar 
		* @return rst 
		*************************************************************************************/
		function ConexionBaseDatos($sql)
		{
			list($dbconn)=GetDBConn();
			//$dbconn->debug=true;
			$rst = $dbconn->Execute($sql);
				
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				return false;
			}
			return $rst;
		}
	}//fin clase user
?>