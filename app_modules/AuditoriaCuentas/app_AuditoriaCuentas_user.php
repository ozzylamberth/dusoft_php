<?php
	/****************************************************************************************  
	* $Id: app_AuditoriaCuentas_user.php,v 1.23 2009/03/19 20:32:41 cahenao Exp $ 
	* 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	* 
	* $Revision: 1.23 $ 
	* 
	* @autor Hugo F  Manrique 
	*****************************************************************************************/
	class app_AuditoriaCuentas_user extends classModulo
	{
		function app_AuditoriaCuentas_user()
		{
			return true;
		}
		/************************************************************************************ 
		* 
		* @access private 
		*************************************************************************************/
		function SetActionVolver($link,$datos)
		{
			SessionDelVar("ActionVolverRespuesta");
			SessionSetVar("ActionVolverRespuesta",$link);
			SessionSetVar("PermisoCrearNota",$datos['notas']);
			
			$_SESSION['Auditoria']['id'] = $datos['id'];
			$_SESSION['Auditoria']['razon'] = $datos['razon'];
			$_SESSION['Auditoria']['empresa'] = $datos['empresa'];
			$_SESSION['Auditoria']['clientes'] = $datos['clientes'];
			$_SESSION['Auditoria']['categorias'] = $datos['categorias'];
			$_SESSION['Auditoria']['municipio'] = $datos['municipio'];
			$_SESSION['Auditoria']['tipo_id_tercero'] = $datos['tipo_id_tercero'];
		}
		/************************************************************************************ 
		* 
		* @access private 
		*************************************************************************************/
		function SetActionNotaCreada($link)
		{
			SessionDelVar("ActionNotaCreada");
			SessionSetVar("ActionNotaCreada",$link);
		}
		/************************************************************************************ 
		* Función principal del módulo 
		* 
		* @access private 
		*************************************************************************************/
		function main()
		{
			SessionDelVar("ActionNotaCreada");
			SessionDelVar("ActionVolverRespuesta");
			$this->MostrarMenuEmpresasAuditoria();
			return true;
		}
		/************************************************************************************
		* Muestra el menu de las empresas
		* 
		* @access public 
		*************************************************************************************/
		function MostrarMenuEmpresasAuditoria()
		{
			unset($_SESSION['Auditoria']);
			
			$Empresas = $this->BuscarEmpresasUsuario();
			$titulo[0]='EMPRESAS';
			
			$url[0]='app';													//contenedor 
			$url[1]='AuditoriaCuentas';							//módulo 
			$url[2]='user';													//clase 
			$url[3]='MostrarMenuPrincipalAuditoria';//método 
			$url[4]='permisos_ac';									//indice del request
			
			$action = ModuloGetURL('system','Menu');
			$forma .= gui_theme_menu_acceso('AUDITORÍA DE GLOSAS',$titulo,$Empresas,$url,$action);

			$this->FormaMostrarMenuEmpresasAuditoria($forma);
			return true;
		}
		/************************************************************************************
		* Retorna las empresas a las cuales tiene permisos el usuario de acceder 
		* 
		* @access public
		*************************************************************************************/
		function BuscarEmpresasUsuario($empresa)
		{
			$usuario=UserGetUID();	
			$sql .= "SELECT B.empresa_id AS empresa, ";
			$sql .= "				B.razon_social AS razon, ";
			$sql .= "				A.sw_todos_clientes AS clientes,";
			$sql .= " 			A.sw_todas_categorias AS categorias, ";
			$sql .= " 			A.sw_crear_nota AS notas, ";
			$sql .= "				B.tipo_id_tercero,";
			$sql .= "				B.id,";
			$sql .= " 			C.municipio ";
			$sql .= "FROM		userpermisos_auditoria_cuentas A,empresas B,tipo_mpios C ";
			$sql .= "WHERE	A.usuario_id = $usuario ";
			$sql .= "AND		A.empresa_id = B.empresa_id ";
			$sql .= "AND		B.tipo_mpio_id 	 = C.tipo_mpio_id  ";
			$sql .= "AND		B.tipo_dpto_id 	 = C.tipo_dpto_id  ";
			$sql .= "AND		B.tipo_pais_id 	 = C.tipo_pais_id  ";
			
			if($empresa)
				$sql .= "AND		A.empresa_id = '".$empresa."' ";
				
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				
			while(!$rst->EOF)
			{
				$empresas[$rst->fields[1]]=$rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $empresas;
		}
		/************************************************************************************
		* Funcion que permite mostrar el menu de auditoria de cuentas
		* 
		* @return boolean 
		*************************************************************************************/
		function MostrarMenuPrincipalAuditoria()
		{
			if(empty($_SESSION['Auditoria']['empresa']))
			{
				$_SESSION['Auditoria']['razon'] = $_REQUEST['permisos_ac']['razon'];
				$_SESSION['Auditoria']['empresa'] = $_REQUEST['permisos_ac']['empresa'];
				$_SESSION['Auditoria']['clientes'] = $_REQUEST['permisos_ac']['clientes'];
				$_SESSION['Auditoria']['categorias'] = $_REQUEST['permisos_ac']['categorias'];
				$_SESSION['Auditoria']['municipio'] = $_REQUEST['permisos_ac']['municipio'];
				$_SESSION['Auditoria']['tipo_id_tercero'] = $_REQUEST['permisos_ac']['tipo_id_tercero'];
				$_SESSION['Auditoria']['id'] = $_REQUEST['permisos_ac']['id'];
				SessionSetVar("PermisoCrearNota", $_REQUEST['permisos_ac']['notas']);
			}
			
			unset($_SESSION['SqlContar']);		
			unset($_SESSION['SqlBuscar']);
			
			$this->action1 = ModuloGetURL('app','AuditoriaCuentas','user','MostrarMenuEmpresasAuditoria');
			$this->FormaMostrarMenuPrincipalAuditoria();
			return true;
		}
		/************************************************************************************ 
		* Función principal del módulo 
		* 
		* @access private 
		*************************************************************************************/
		function AceptarGlosaNota()
		{
			IncludeClass('AuditoriaGlosas','','app','AuditoriaCuentas');
			$au = new AuditoriaGlosas();
			$this->action = array();
			
			$this->request = $_REQUEST;
			$datos = SessionGetvar("DatosBuscador");
			
			if(SessionIsSetVar("ActionNotaCreada"))
				$this->action['volver'] = SessionGetVar("ActionNotaCreada");
			else
				$this->action['volver'] = ModuloGetURL('app','AuditoriaCuentas','user','MostrarInformacionFacturasGlosadas',$datos);
			
			switch($this->request['opcion_auditoria'])
			{
				case '1':
					$rst = $au->AceptarGlosaCuenta($_SESSION['Auditoria']['empresa'], $this->request,$_SESSION['Auditoria']['sistema']);
					if($this->request['cantidad'] == '1') 
						$this->codigo = "NT";
					else
						$this->codigo = "NI";
				break;
				case '2':
					$rst = $au->AceptarGlosaCargos($_SESSION['Auditoria']['empresa'], $this->request,$_SESSION['Auditoria']['sistema']);
					$this->codigo = "NI";
				break;
				default:
          $rst = $au->AceptarGlosaFactura($_SESSION['Auditoria']['empresa'], $this->request,$_SESSION['Auditoria']['sistema']);
					$this->codigo = "NI";
				break;
			}
			
			if(!$rst)
			{
				$this->frmError['MensajeError'] = "HA OCURRIDO UN ERROR ".$au->frmError['MensajeError'];
			}
			else
			{
				$this->frmError['MensajeError']  = "SE HA ACEPTADO LA GLOSA POR UN VALOR DE $".$this->request['valor_aceptado'];
				$this->frmError['MensajeError'] .= " ,UN VALOR NO ACEPTADO DE $".$this->request['valor_noaceptado'];
				$this->frmError['MensajeError'] .= " Y SE CREO LA NOTA CREDITO: ".$rst['prefijo']." ".$rst['numeracion']." ";
			}
			$this->nota = $rst;
				
			return true;
		}
		/************************************************************************************ 
		* Funcuion que permnite desplegar la informacion de la factura al usuario 
		* 
		* @return boolean 
		*************************************************************************************/ 
		function MostrarInformacionFacturasGlosadas()
		{	
			unset($_SESSION['Auditoria']['sistema']);
			$this->Numero = $_SESSION['Auditoria']['buscador']['numero'];
			$this->BVGlosa = $_SESSION['Auditoria']['buscador']['valor_glosa'];
			$this->ComboBSQ = $_SESSION['Auditoria']['buscador']['combo'];
			$this->FechaFin = $_SESSION['Auditoria']['buscador']['fecha_fin'];
			$this->BVFactura = $_SESSION['Auditoria']['buscador']['valor_factura'];
			$this->FechaInicio = $_SESSION['Auditoria']['buscador']['fecha_inicio'];
			$this->OperadorGlosa = $_SESSION['Auditoria']['buscador']['comparacionglosa'];
			$this->OperadorFactura = $_SESSION['Auditoria']['buscador']['comparacionfactura'];
			
			$this->TerceroNombre = $_REQUEST['nombre_tercero'];
			$this->TerceroTipoId = $_REQUEST['tipo_id_tercero'];
			$this->TerceroDocumento = $_REQUEST['tercero_id'];
			
			$request = array("tercero_id"=>$this->TerceroDocumento,"nombre_tercero"=>$this->TerceroNombre,
											 "tipo_id_tercero"=>$this->TerceroTipoId,"pagina"=>$_REQUEST['pagina']);
			$this->rqs = $_REQUEST;
			
			$this->action1 = ModuloGetURL('app','AuditoriaCuentas','user','MostrarClientes',array("offset"=>$_REQUEST['pagina']));
			$this->actionP = ModuloGetURL('app','AuditoriaCuentas','user','MostrarInformacionFacturasGlosadas',$request);
			
			$datos = array("tercero_id"=>$this->TerceroDocumento,"nombre_tercero"=>$this->TerceroNombre,"tipo_id_tercero"=>$this->TerceroTipoId);
			
			$this->actionB = ModuloGetURL('app','AuditoriaCuentas','user','ObtenerSqlBuscarFacturas',$datos);
			$this->action2 = ModuloGetURL('app','AuditoriaCuentas','user','ObtenerSqlBuscarFacturas',$datos);
			
			$this->FormaMostrarInformacionFacturasGlosadas();
			return true;
		}
		/************************************************************************************
		* Funcion que permite mostrar el listado de clientes 
		* 
		* @return boolean 
		*************************************************************************************/
		function MostrarClientes()
		{
			unset ($_SESSION['SqlBuscarFA']);
			unset ($_SESSION['SqlContarFA']);
			$this->request = $_REQUEST;
			
			$this->Tipos = $this->ObtenerTipoIdTerceros();
			
			if(!SessionIsSetVar("PrefijosAuditoria"))
			{
				$prefijos = $this->ObtenerPrefijosTodasFacturas($_SESSION['Auditoria']['empresa']);
				SessionSetVar("PrefijosAuditoria",$prefijos);
			}
			
			$this->Prefijos = SessionGetVar("PrefijosAuditoria");
			if($this->request['buscadorf']['factura_f'])
				$this->request['buscador'] = $this->ObtenerDatosClienteXFactura($this->request['buscadorf'],$_SESSION['Auditoria']['empresa']);
				
			if(!empty($this->request['buscador']))
				 $this->Clientes = $this->ObtenerSqlBuscarDatosCliente($this->request['buscador'],$this->request['buscadorf']);
									
			$this->action['buscar']  = ModuloGetURL('app','AuditoriaCuentas','user','MostrarClientes');
			$this->actionPg = ModuloGetURL('app','AuditoriaCuentas','user','MostrarClientes',$this->request['buscador']);
			
			if(!empty($this->request['buscadorf']))
				$this->action3 = ModuloGetURL('app','AuditoriaCuentas','user','ObtenerSqlBuscarFacturas');
			else
				$this->action3 = ModuloGetURL('app','AuditoriaCuentas','user','MostrarInformacionFacturasGlosadas',array("pagina"=>$this->paginaActual));
			
			$this->action1 = ModuloGetURL('app','AuditoriaCuentas','user','MostrarMenuPrincipalAuditoria');
			$this->FormaMostrarClientes();
			return true;
		}
		/************************************************************************************ 
		* Funcion donde se consulta la informacion de la glosa de la factura seleccionada 
		* 
		* @return boolean 
		*************************************************************************************/
		function ConsultarInformacionGlosa()
		{	
			(empty($_REQUEST['sistema']))? $this->Sistema = $_SESSION['Auditoria']['sistema']: $this->Sistema = $_REQUEST['sistema'];
			$_SESSION['Auditoria']['sistema'] = $this->Sistema;
			
			$this->ObtenerInformacionGlosaFactura();
			
			$request1 = array("tipo_id_tercero"=>$_REQUEST['tipo_id_tercero'],"tercero_id"=>$_REQUEST['tercero_id'],"num_envio"=>$_REQUEST['num_envio'],
							  				"pagina"=>$_REQUEST['pagina'],"nombre_tercero"=>$_REQUEST['nombre_tercero'],"pagina1"=>$_REQUEST['pagina1']);
			
			$this->Arreglo = $request1;
			$this->Arreglo['glosa_id'] = $this->GlosaId;
			$this->Arreglo['factura'] = $this->FacturaNumero;
			
			$request2 = $request1;
			$request1['offset']	= $_REQUEST['pagina1'];
			
			if(SessionIsSetVar("ActionVolverRespuesta"))
				$this->action1 = SessionGetVar("ActionVolverRespuesta");
			else
				$this->action1 = ModuloGetURL('app','AuditoriaCuentas','user','MostrarInformacionFacturasGlosadas',$request1);
				
			$this->action3 = ModuloGetURL('app','AuditoriaCuentas','user','AceptarGlosaFactura',$this->Arreglo);
		 	$this->action4 = ModuloGetURL('app','AuditoriaCuentas','user','IngresarObservacionGlosa',
		 								   array("menu"=>"respuesta","glosa_id"=>$this->GlosaId));
		 	
			SessionSetvar("DatosBuscador",$request1);
			$this->CrearNota = SessionGetVar("PermisoCrearNota");
			$this->action['nota'] = ModuloGetURL('app','AuditoriaCuentas','user','FormaAceptarGlosaNota',array('glosa_id'=>$this->GlosaId,'sw_glosa_factura'=>'1'));
			$this->action['no_aceptar'] = ModuloGetURL('app','AuditoriaCuentas','user','FormaNoAceptarGlosa',array('glosa_id'=>$this->GlosaId,"datos"=>$this->Arreglo));
			
			$this->FormaMostrarConsultaGlosa();
			return true; 
		}
		/************************************************************************************
		* Funcion que permite mostrar elpantallazo de mostrar la glosa de las cuentas
		*
		* @return boolean
		*************************************************************************************/
		function MostrarGlosaCuentas()
		{
			$this->Factura = $_REQUEST['factura'];
			$this->GlosaId = $_REQUEST['glosa_id'];
			
			$arreglo = array("pagina"=>$_REQUEST['pagina'],"glosa_id"=>$_REQUEST['glosa_id'],
		 					 "factura"=>$_REQUEST['factura'],"pagina1"=>$_REQUEST['pagina1'],"num_envio"=>$_REQUEST['num_envio'],
		 					 "nombre_tercero"=>$_REQUEST['nombre_tercero'],"cantidad"=>$_REQUEST['cantidad'],
		 					 "tercero_id"=>$_REQUEST['tercero_id'],"tipo_id_tercero"=>$_REQUEST['tipo_id_tercero']);
		 			 	
		 	$arreglo1 = $arreglo;
			
			$metodo = "ConsultarInformacionGlosa"; 
			
			$this->action = ModuloGetURL('app','AuditoriaCuentas','user',$metodo,$arreglo);
			$this->action2 = ModuloGetURL('app','AuditoriaCuentas','user','MostrarGlosaCuentas',$arreglo);			
			$this->FormaMostrarInformacionCuentas();
			return true;
		}
		/************************************************************************************
		* Funcion que permite desplegar la interface, donde se muestra la informacion de la 
		* glosa de una cuenta y que permite ingresar los valores aceptados y no aceptados 
		* de la cuenta los cargos y/o los insumos 
		*
		* @return boolean 
		*************************************************************************************/
		function MostrarInformacionCuentaGlosada()
		{
			$this->Factura = $_REQUEST['factura'];
			$this->GlosaId = $_REQUEST['glosa_id'];
			$this->Cuenta = $_REQUEST['numero_cuenta'];
			
			$this->ObtenerInformacionDetalleCuentaGlosada($this->GlosaId,$this->Cuenta);			
			
			$arreglo = array("pagina"=>$_REQUEST['pagina'],"glosa_id"=>$_REQUEST['glosa_id'],
						 					 "factura"=>$_REQUEST['factura'],"pagina1"=>$_REQUEST['pagina1'],"cantidad"=>$_REQUEST['cantidad'],
						 					 "nombre_tercero"=>$_REQUEST['nombre_tercero'],"num_envio"=>$_REQUEST['num_envio'],
						 					 "tercero_id"=>$_REQUEST['tercero_id'],"tipo_id_tercero"=>$_REQUEST['tipo_id_tercero']);
		 			 	
		 	$arreglo1 = $arreglo;
			
			if($_REQUEST['retorno'])
			{
				$metodo = $_REQUEST['retorno'];
			}
			else
			{
				$metodo = "ConsultarInformacionGlosa"; 
			}
			
			$this->action1 = ModuloGetURL('app','AuditoriaCuentas','user',$metodo,$arreglo);
			
			$arreglo['retorno'] = $metodo;
			$arreglo['numero_cuenta'] = $this->Cuenta;
			$arreglo['glosa_id_cuenta'] = $this->GlosaCuentaId;
			
			$this->action2 = ModuloGetURL('app','AuditoriaCuentas','user','AceptarGlosaCuenta',$arreglo);
			$this->action3 = ModuloGetURL('app','AuditoriaCuentas','user','AceptarGlosaCargos',$arreglo);
			$this->action4 = ModuloGetURL('app','AuditoriaCuentas','user','IngresarObservacionGlosaCuenta',
									 								   array("menu"=>"respuesta","glosa_id"=>$this->GlosaId,
									 								   		 "glosa_id_cuenta"=>$this->GlosaCuentaId));
			$this->action5 = ModuloGetURL('app','AuditoriaCuentas','user','IngresarObservacionGlosaCargo',
									 								   array("menu"=>"respuesta","glosa_id"=>$this->GlosaId,
									 								   		 "glosa_id_cuenta"=>$this->GlosaCuentaId));
		 	$this->action6 = ModuloGetURL('app','AuditoriaCuentas','user','IngresarObservacionGlosaInsumo',
									 								   array("menu"=>"respuesta","glosa_id"=>$this->GlosaId,
									 								   		 "glosa_id_cuenta"=>$this->GlosaCuentaId));
			$this->ValorGlosaTotal = 0;
			
			$this->Cargos = $this->ObtenerInformacionGlosaCargos($this->Cuenta);
			$this->Insumo = $this->ObtenerInformacionGlosaInsumos($this->Cuenta);
			
			//$arreglo['opcion_auditoria'] = '1';
			$this->CrearNota = SessionGetVar("PermisoCrearNota");
			$arreglo['sw_glosa_parcial'] = '0';
			$this->action['nota'] = ModuloGetURL('app','AuditoriaCuentas','user','FormaAceptarGlosaNota',$arreglo);

			$this->FormaMostrarInformacionCuentaGlosada();
			return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function MostrarActasConciliacion()
		{
			$this->FechaFin = $_REQUEST['fecha_fin'];
			$this->AuditorSel = $_REQUEST['auditor_sel'];
			$this->FechaInicio = $_REQUEST['fecha_inicio'];
			$this->TerceroNombre = $_REQUEST['nombre_tercero'];
			$this->TerceroTipoId = $_REQUEST['tipo_id_tercero'];
			$this->TerceroDocumento = $_REQUEST['tercero_id'];
			
			$request = array("fecha_fin"=>$this->FechaFin,"auditor_sel"=>$this->AuditorSel,
											 "fecha_inicio"=>$this->FechaInicio,"nombre_tercero"=>$this->TerceroNombre,
											 "tipo_id_tercero"=>$this->TerceroTipoId,"tercero_id"=>$this->TerceroDocumento);	
			
			$this->action1 = ModuloGetURL('app','AuditoriaCuentas','user','MostrarMenuPrincipalAuditoria');
			$this->action2 = ModuloGetURL('app','AuditoriaCuentas','user','MostrarActasConciliacion',$request);
			$this->action3 = ModuloGetURL('app','AuditoriaCuentas','user','MostrarActasConciliacion');
			
			$this->FormaMostrarActasConciliacion();
			return true;
		}
		/************************************************************************************
		* Funcion que permite mostrar la interfaz donde se crean las actas de conciliacion 
		* y las actas que no se han cerrado
		*
		* @return boolean
		*************************************************************************************/
		function ConciliarCuentas()
		{	
			$this->Tercero = $_REQUEST['tercero'];
			$this->Id = explode("/",$this->Tercero);
			$this->Cliente = $this->ObtenerTercero($this->Tercero);
			$this->action1 = ModuloGetURL('app','AuditoriaCuentas','user','MostrarClientesConciliar');
			$this->action2 = ModuloGetURL('app','AuditoriaCuentas','user','CrearActaConciliacionBD',array("tercero"=>$_REQUEST['tercero']));
			$this->FormaConciliarCuentas();
			
			return true;
		}
		/************************************************************************************
		* Funcion que permite mostrar en pantalla el listado de clientes a los cuales se 
		* les puede crear un acta de conciliacion 
		*
		* @return boolean
		*************************************************************************************/
		function MostrarClientesConciliar()
		{	
			$this->TerceroNombre = $_REQUEST['nombre_tercero'];
			$this->TerceroTipoId = $_REQUEST['tipo_id_tercero'];
			$this->TerceroDocumento = $_REQUEST['tercero_id'];
			
			$this->action1 = ModuloGetURL('app','AuditoriaCuentas','user','MostrarMenuPrincipalAuditoria');
			$this->actionB = ModuloGetURL('app','AuditoriaCuentas','user','MostrarClientesConciliar');
			$this->FormaMostrarClientesConciliar();
			
			return true;
		}
		/************************************************************************************
		* Funcion que permite desplegar un listado con las facturas que tienen glosas y 
		* pueden ser incluidas en un acta de conciliacion 
		*
		* @return boolean
		*************************************************************************************/
		function ConciliarGlosas()
		{
			$this->ActaId = $_REQUEST['acta_id'];
			$this->Tercero = $_REQUEST['tercero'];
			$this->Empresa = $_SESSION['Auditoria']['razon'];
			$this->Entidad = $this->ObtenerTercero($this->Tercero);
			
			$this->ObtenerInformacionActasConciliacion($this->ActaId);
			$this->action1 = ModuloGetURL('app','AuditoriaCuentas','user','ConciliarCuentas',array("tercero"=>$_REQUEST['tercero']));

			$this->FormaConciliarGlosas();
			return true;
		}
		/************************************************************************************
		* Funcion que permite desplegar la informacion de la glosa y donde se puede 
		* ingresar los valores aceptados y no aceptados de una glosa 
		*
		* @return boolean
		*************************************************************************************/
		function MostrarConciliarFactura()
		{
			$this->Arreglo = array("tercero"=>$_REQUEST['tercero'],"acta_id"=>$_REQUEST['acta_id']);
			$this->action1 = ModuloGetURL('app','AuditoriaCuentas','user','ConciliarGlosas',$this->Arreglo);
			
			
			$this->Entidad = $this->ObtenerTercero($_REQUEST['tercero']);
			$this->ObtenerInformacionFactura();
			
			$this->Arreglo['glosa_id'] = $this->GlosaId;
			$this->Arreglo['factura'] = $_REQUEST['factura'];
			
			$this->action2 = ModuloGetURL('app','AuditoriaCuentas','user','ConciliarFacturaBD',$this->Arreglo);
			$this->action4 = ModuloGetURL('app','AuditoriaCuentas','user','IngresarObservacionGlosa',
		 								   array("auditoria"=>"on","glosa_id"=>$this->GlosaId,"acta_id"=>$_REQUEST['acta_id']));

			$this->NuemeroGlosas = $this->ContarCuentasGlosadas($this->GlosaId);
			
			$this->FormaMostrarConciliarFactura();
			return true;
		}
		/************************************************************************************
		* Funcion que permite mostrar un listado de cuentas, si el numero de cuentas es 
		* mayor a uno, si el numero de cuentas es uno, se re direcciona al metodo que 
		* muestra la informacion de la cuenta para que sean ingresados los valores 
		* aceptados y no aceptados
		*
		* @return boolean
		*************************************************************************************/
		function ConciliarCuentasGlosa()
		{
			$this->ActaId = $_REQUEST['acta_id'];
			$this->GlosaId = $_REQUEST['glosa_id'];
			$this->Tercero = explode("/",$_REQUEST['tercero']);
			$this->Factura = explode("/",$_REQUEST['factura']);
			
			$this->NuemeroCuentas = $this->ObtenerCantidadCuentasFactura($this->GlosaId);
			if(sizeof($this->NuemeroCuentas) == 1)
			{
				$this->ConciliarGlosaCuenta();
				return true;
			}
			$this->Arreglo = array("acta_id"=>$_REQUEST['acta_id'],"tercero"=>$_REQUEST['tercero'],"factura"=>$_REQUEST['factura']);
							 
			$this->action1 = ModuloGetURL('app','AuditoriaCuentas','user','MostrarConciliarFactura',$this->Arreglo);
			$this->action2 = ModuloGetURL('app','AuditoriaCuentas','user','ConciliarCuentasGlosa',$this->Arreglo);
			
			$this->Arreglo['glosa_id'] = $_REQUEST['glosa_id'];
			$this->Arreglo['actanumero'] = $_REQUEST['actanumero'];
			$this->Arreglo['totalcuentas'] = sizeof($this->NuemeroCuentas);
			
			$this->FormaConciliarCuentasGlosa();
			return true;
		}
		/************************************************************************************
		* Funcion donde se muestra la informacion de la cuenta para que sean ingresados los 
		* valores aceptados y no aceptados, tanto para la cuenta como para los cargos e
		* insumos
		* 
		* @return boolean
		*************************************************************************************/
		function ConciliarGlosaCuenta()
		{
			$arreglo = array("factura"=>$_REQUEST['factura'],"acta_id"=>$_REQUEST['acta_id'],"tercero"=>$_REQUEST['tercero']);
			if($this->NuemeroCuentas != "")
			{
				$total = sizeof($this->NuemeroCuentas);
				$numerocuenta = $this->NuemeroCuentas[0];
			}
			else
			{
				$total = $_REQUEST['totalcuentas'];
				$numerocuenta = $_REQUEST['numerocuenta'];
				$this->ActaId = $_REQUEST['acta_id'];
				$this->GlosaId = $_REQUEST['glosa_id'];
				$this->Tercero = explode("/",$_REQUEST['tercero']);
				$this->Factura = explode("/",$_REQUEST['factura']);
			}
			
			$arreglo['glosa_id'] = $_REQUEST['glosa_id'];
			$this->ObtenerInformacionGlosaCuenta($numerocuenta);
			if($total == 1)
			{
				$this->action1 = ModuloGetURL('app','AuditoriaCuentas','user','MostrarConciliarFactura',$arreglo);
			}
			else
			{
				$this->action1 = ModuloGetURL('app','AuditoriaCuentas','user','ConciliarCuentasGlosa',$arreglo);
			}
			
			$arreglo['actanumero'] = $_REQUEST['actanumero'];
			$arreglo['actualizar'] = $this->actualizar;
			$arreglo['numerocuenta'] = $numerocuenta;
			$arreglo['totalcuentas'] = $total;
			$arreglo['glosacuentaid'] = $this->GlosaCuentaId;
			
			$this->action2 = ModuloGetURL('app','AuditoriaCuentas','user','ConciliarGlosaCuentaBD',$arreglo);
			$this->action3 = ModuloGetURL('app','AuditoriaCuentas','user','ConciliarDetalleGlosaCuentaBD',$arreglo);

			$this->action5 = ModuloGetURL('app','AuditoriaCuentas','user','IngresarObservacionGlosaCuenta',
		 								   array("auditoria"=>"on","glosa_id"=>$_REQUEST['glosa_id'],
		 								   		 "acta_id"=>$_REQUEST['acta_id'],"glosa_id_cuenta"=>$this->GlosaCuentaId));
			$this->action6 = ModuloGetURL('app','AuditoriaCuentas','user','IngresarObservacionGlosaCargo',
		 								   array("auditoria"=>"on","glosa_id"=>$_REQUEST['glosa_id'],
		 								   		 "acta_id"=>$_REQUEST['acta_id'],"glosa_id_cuenta"=>$this->GlosaCuentaId));

			$this->action7 = ModuloGetURL('app','AuditoriaCuentas','user','IngresarObservacionGlosaInsumo',
		 								   array("auditoria"=>"on","glosa_id"=>$_REQUEST['glosa_id'],
		 								   		 "acta_id"=>$_REQUEST['acta_id'],"glosa_id_cuenta"=>$this->GlosaCuentaId));

			$this->Cargos = $this->ObtenerInformacionGlosaCargos($numerocuenta);
			$this->Insumo = $this->ObtenerInformacionGlosaInsumos($numerocuenta);
			$this->GlosaValor += $this->VGlosa;
			
			$this->FormaConciliarGlosaCuenta();
			return true;
		}
		/************************************************************************************
		* Funcion donde se crean las actas de conciliacion
		* 
		* @return boolean
		*************************************************************************************/
		function CrearActaConciliacionBD()
		{
			$this->AuditorE = $_REQUEST['tercero'];
			$this->IdAuditorE = $_REQUEST['id_auditor'];
			$this->Observacion = $_REQUEST['observacion'];
			$this->CargoAuditorE = $_REQUEST['cargo_auditor'];
			$this->NombreAuditorE = $_REQUEST['auditorexterno'];
			$this->TipoIdAuditorE = $_REQUEST['tipo_id_auditor'];
			
			$this->parametro = "MensajeError";
			
			if( $this->NombreAuditorE == "")
			{
				$this->frmError['MensajeError'] = "SE DEBE INDICAR CUAL ES EL NOMBRE DEL AUDITOR";
			}
			else if($this->TipoIdAuditorE != "0" && $this->IdAuditorE == "")
				{
					$this->frmError['MensajeError'] = "SE DEBE INDICAR EL NUMERO DE INDENTIFICACIÓN";
				}
				else if($this->TipoIdAuditorE == "0" && $this->IdAuditorE != "")
					{
						$this->frmError['MensajeError'] = "SE DEBE INDICAR EL TIPO DE INDENTIFICACIÓN";
					}
					else
					{
						/**********************************************
						* Falta ingresar los datos adicionales
						***********************************************/
						$tercero = explode("/",$this->AuditorE);
						
						$sql .= "INSERT INTO actas_conciliacion_glosas ";
						$sql .= "		(auditor_id,";
						$sql .= "		auditor_empresa,";
						$sql .= "		tipo_id_tercero,";
						$sql .= "		tercero_id,";
						$sql .= "		fecha_acta,";
						$sql .= "		observacion";
						$sql .= "		) ";
						$sql .= "VALUES (";
						$sql .= "		 ".UserGetUID().", ";
						$sql .= "		'".strtoupper($this->NombreAuditorE)."', ";
						$sql .= "		'".$tercero[0]."', ";
						$sql .= "		'".$tercero[1]."', ";
						$sql .= "		   NOW(), ";
						$sql .= "		'".$this->Observacion."' ";
						$sql .= ")";
						
						$this->parametro = "MensajeError";
						if(!$rst = $this->ConexionBaseDatos($sql))
							return false;
						
						$this->parametro = "Informacion";
						$this->frmError['Informacion'] = "EL ACTA DE CONCILIACIÓN FUE CREADA";	
						$this->AuditorE = "";
						$this->IdAuditorE = "";
						$this->Observacion = "";
						$this->CargoAuditorE = "";
						$this->NombreAuditorE = "";
						$this->TipoIdAuditorE = "0";
					}
			
			$this->ConciliarCuentas();
			return true;
		}
		/************************************************************************************
		* Funcion donde se confirma si se desea cerra un acta de conciliacion o no
		*
		* @return boolean
		*************************************************************************************/
		function CerrarActaConciliacion()
		{
			$this->action = ModuloGetURL('app','AuditoriaCuentas','user','CerrarActaConciliacionBD',
										  array("tercero"=>$_REQUEST['tercero'],"acta_id"=>$_REQUEST['acta_id']));
			$this->actionM = ModuloGetURL('app','AuditoriaCuentas','user','ConciliarCuentas',array("tercero"=>$_REQUEST['tercero']));
			
			$informacion = "REALMENTE DESEA CERRAR EL ACTA DE CONCILIACION Nº ".$_REQUEST['acta_id']." ESTABLECIDA CON LA ENTIDAD ".$_REQUEST['nombre']." ?";
			$this->FormaInformacion($informacion);
			return true;
		}
		/************************************************************************************
		* Funcion por la cual se cambia el estado de un acta de conciliacion a cerrada en 
		* la base de datos
		* 
		* @return boolean
		*************************************************************************************/
		function CerrarActaConciliacionBD()
		{
			$tercero = explode("/",$_REQUEST['tercero']);
			
			$sql .= "UPDATE actas_conciliacion_glosas ";
			$sql .= "SET	sw_activo = '1' ";
			$sql .= "WHERE 	acta_conciliacion_id = ".$_REQUEST['acta_id']." ";
			$sql .= "AND	tipo_id_tercero = '".$tercero[0]."' ";
			$sql .= "AND	tercero_id = '".$tercero[1]."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				
			$this->ConciliarCuentas();
			return true;
		}
		/************************************************************************************
		* Funcion donde se crea el detalle de conciliacion para una factura en la base de
		* datos
		*
		* @return boolean
		*************************************************************************************/
		function ConciliarFacturaBD()
		{
			$valorGlosa = $_REQUEST['valor_glosa'];
			$this->Observacion = $_REQUEST['observacion'];
			$this->GlosaAceptado = $_REQUEST['valor_aceptado'];
			$this->GlosaNoAceptado = $_REQUEST['valor_noaceptado'];
			
			$this->Parametro = "MensajeError";
			
			if( ($this->GlosaAceptado == "" && $this->GlosaNoAceptado == "")
				|| ($this->GlosaAceptado == 0 && $this->GlosaNoAceptado == 0))
			{
				$this->frmError['MensajeError'] = "SE DEBEN INGRESAR VALORES VALIDOS PARA HACER LA CONCILIACION";
			}
			else
			{			
				if($this->GlosaAceptado == "") $this->GlosaAceptado = 0;
				if($this->GlosaNoAceptado == "") $this->GlosaNoAceptado = 0; 
							
				if(!is_numeric($this->GlosaAceptado))
				{
					$this->frmError['MensajeError'] = "EL VALOR ACEPTADO INGRESADO, NO ES VALIDO";
				}
				else if(!is_numeric($this->GlosaNoAceptado))
					 {
						$this->frmError['MensajeError'] = "EL VALOR NO ACEPTADO INGRESADO, NO ES VALIDO";
					 }
					 else if($this->GlosaAceptado+$this->GlosaNoAceptado > $valorGlosa)
					 	{
					 		$this->frmError['MensajeError'] = "LA SUMA DEL VALOR NO ACEPTADO Y EL VALOR ACEPTADO, NO DEBEM SER MAYOR QUE EL VALOR DE LA GLOSA";
					 	}
					 	else
					 	{
					 		$id_glosa = $_REQUEST['glosa_id'];
					 		$acta_id = $_REQUEST['acta_id'];
					 		$actanumero = $_REQUEST['actanumero'];
					 		$empresa = $_SESSION['Auditoria']['empresa'];
					 		$factura = explode("/",$_REQUEST['factura']);
					 		
					 		if($_SESSION['Auditoria']['sistema'] == "SIIS")
					 		{
								$sql .= "UPDATE	glosas_detalle_cuentas ";
								$sql .= "SET	sw_estado = '2' ";
								$sql .= "WHERE	glosa_id = ".$id_glosa." ";
								$sql .= "AND	sw_estado = '1'; ";
				
								$sql .= "UPDATE	glosas_detalle_cargos ";
								$sql .= "SET	sw_estado = '2' ";
								$sql .= "WHERE	glosa_id = ".$id_glosa." ";
								$sql .= "AND	sw_estado = '1'; ";
				
								$sql .= "UPDATE	glosas_detalle_inventarios ";
								$sql .= "SET	sw_estado = '2' ";
								$sql .= "WHERE	glosa_id = ".$id_glosa." ";
								$sql .= "AND	sw_estado  = '1'; ";
							}
							
							$sql .= "UPDATE	glosas ";
							$sql .= "SET	sw_estado = '2', ";
							$sql .= "		sw_glosa_parcial = '1', ";
							$sql .= "		valor_aceptado = ".$this->GlosaAceptado.",";
							$sql .= "		valor_no_aceptado = ".$this->GlosaNoAceptado." ";
							$sql .= "WHERE	glosa_id = ".$id_glosa."; ";
							
							if($actanumero == "")
							{
								$sql .= "INSERT INTO actas_conciliacion_glosas_detalle ";
								$sql .= "		(";
								$sql .= "		 acta_conciliacion_id,";
								$sql .= "		 empresa_id,";
								$sql .= "		 prefijo,";
								$sql .= "		 factura_fiscal, ";
								$sql .= "		 glosa_id ";
								$sql .= "		)";
								$sql .= "VALUES (";
								$sql .= "		 ".$acta_id.",";
								$sql .= "		'".$empresa."',";
								$sql .= "		'".$factura[0]."',";
								$sql .= "		 ".$factura[1].", ";
								$sql .= "		 ".$id_glosa." ";
								$sql .= "		);";
							}
							
							if(!$rst = $this->ConexionBaseDatos($sql))
								return false;
							$this->Parametro = "Informacion";	
							$this->frmError['Informacion'] = "LA INFORMACIÓN DE LA GLOSA Y EL DETALLE DEL ACTA DE CONCILIACION FUE ACTUALIZADA CORRECTAMENTE";
							empty($_REQUEST['valor_aceptado']);
			
						}
			}
			$this->MostrarConciliarFactura();
			return true;
		}
		/************************************************************************************
		* Funcion que permite crear un detalle de acta en la base de datos para una cuenta
		*
		* @return boolean
		*************************************************************************************/
		function ConciliarGlosaCuentaBD()
		{
			$valor = $_REQUEST['valor_glosa'];
			$this->Observacion = $_REQUEST['observacion'];
			$this->GlosaValorAceptado   = $_REQUEST['valor_aceptado'];
			$this->GlosaValorNoAceptado = $_REQUEST['valor_noaceptado'];
						
			$this->Parametro = "MensajeError";
			if( ($this->GlosaValorAceptado == "" && $this->GlosaValorNoAceptado == "")
				|| ($this->GlosaValorAceptado == 0 && $this->GlosaValorNoAceptado == 0))
			{
				$this->frmError['MensajeError'] = "SE DEBEN INGRESAR VALORES VALIDOS PARA HACER LA CONCILIACION";
			}
			else
			{
				if($this->GlosaValorAceptado == "") $this->GlosaValorAceptado = 0;
				if($this->GlosaValorNoAceptado == "") $this->GlosaValorNoAceptado = 0; 
							
				if(!is_numeric($this->GlosaValorAceptado))
				{
					$this->frmError['MensajeError'] = "EL VALOR ACEPTADO INGRESADO, NO ES VALIDO";
				}
				else if(!is_numeric($this->GlosaValorNoAceptado))
					 {
						$this->frmError['MensajeError'] = "EL VALOR NO ACEPTADO INGRESADO, NO ES VALIDO";
					 }
					 else if($this->GlosaValorAceptado+$this->GlosaValorNoAceptado > $valor)
					 	{
					 		$this->frmError['MensajeError'] = "LA SUMA DEL VALOR NO ACEPTADO Y EL VALOR ACEPTADO, NO DEBEM SER MAYOR QUE EL VALOR DE LA GLOSA";
					 	}
						else
						{
							$actaid = $_REQUEST['acta_id'];
							$glosaid = $_REQUEST['glosa_id'];
							$actualizar = $_REQUEST['actualizar'];
							$numerocuenta = $_REQUEST['numerocuenta'];
							$glosacuentaid = $_REQUEST['glosacuentaid'];
							$empresa = $_SESSION['Auditoria']['empresa'];
					 		$factura = explode("/",$_REQUEST['factura']);
						
							$sql .= "UPDATE	glosas_detalle_cuentas ";
							$sql .= "SET	valor_aceptado = ".$this->GlosaValorAceptado.", ";
							$sql .= "		valor_no_aceptado = ".$this->GlosaValorNoAceptado.", ";
							$sql .= "		sw_estado = '2' ";
							$sql .= "WHERE	glosa_detalle_cuenta_id = ".$glosacuentaid." ";
							$sql .= "AND	glosa_id = ".$glosaid." ";
							$sql .= "AND	numerodecuenta = ".$numerocuenta."; ";
				
							$sql .= "UPDATE	glosas_detalle_cargos ";
							$sql .= "SET	sw_estado = '2' ";
							$sql .= "WHERE	glosa_detalle_cuenta_id = ".$glosacuentaid." ";
							$sql .= "AND	glosa_id = ".$glosaid." ";
							$sql .= "AND	sw_estado NOT IN ('3','0'); ";
							
							$sql .= "UPDATE	glosas_detalle_inventarios ";
							$sql .= "SET	sw_estado = '2' ";
							$sql .= "WHERE	glosa_detalle_cuenta_id = ".$glosacuentaid." ";
							$sql .= "AND	glosa_id = ".$glosaid." ";
							$sql .= "AND	sw_estado NOT IN ('3','0'); ";
	
							$sql .= "UPDATE	glosas ";
							$sql .= "SET	sw_estado = '2', ";
							$sql .= "		sw_glosa_parcial = '1' ";
							$sql .= "WHERE	glosa_id = ".$glosaid."; ";
						
							if($actualizar == 0)
							{
								if(empty($_REQUEST['actanumero']))
								{	
									$sql .= "INSERT INTO actas_conciliacion_glosas_detalle ";
									$sql .= "		(";
									$sql .= "		 acta_conciliacion_id,";
									$sql .= "		 empresa_id,";
									$sql .= "		 glosa_id,";
									$sql .= "		 prefijo,";
									$sql .= "		 factura_fiscal";
									$sql .= "		)";
									$sql .= "VALUES (";
									$sql .= "		 ".$glosaid.",";
									$sql .= "		'".$empresa."',";
									$sql .= "		 ".$actaid.",";
									$sql .= "		'".$factura[0]."',";
									$sql .= "		 ".$factura[1]." ";
									$sql .= "		);";
								}
							}
										
							if(!$rst = $this->ConexionBaseDatos($sql))
								return false;
		 								  
		 					$this->Parametro = "Informacion";	
							$this->frmError['Informacion'] = "LOS VALORES DE LA CUENTA Nº ".$numerocuenta." SE HAN MODIFICADO CORRECTAMENTE";
						}
		 	}
		 	$this->ConciliarGlosaCuenta();
			return true;
		}
		/************************************************************************************
		* Funcion que permite crear un detalle de acta en la base de datos para un cargo y 
		* para los insumos
		*
		* @return boolean
		*************************************************************************************/
		function ConciliarDetalleGlosaCuentaBD()
		{
			$this->ActualizarActas = $_REQUEST['actasnumero'];
			$this->ObservacionC = $_REQUEST['observacion'];
			$this->VNoAceptado = $_REQUEST['valor_noaceptado'];
			$this->VAceptado = $_REQUEST['valor_aceptado'];
			$this->VGlosa = $_REQUEST['valor_glosa'];
			
			$this->Actaid = $_REQUEST['acta_id'];
			$this->GlosaId = $_REQUEST['glosa_id'];
			$this->Empresa = $_SESSION['Auditoria']['empresa'];
			$this->Factura = explode("/",$_REQUEST['factura']);
			$this->CuentaId = $_REQUEST['numerocuenta'];
			
			$actualizar = $_REQUEST['actualizar'];
			
			$this->VGlosaT = $_REQUEST['valorglosacuenta'];
			$this->VAceptadoT = $_REQUEST['valoraceptadocuenta'];
			$this->VNAceptadoT = $_REQUEST['valornoaceptadocuenta'];
			
			if($actualizar == 0)
			{
				if(empty($_REQUEST['actanumero']))
				{	
					$sql .= "INSERT INTO actas_conciliacion_glosas_detalle ";
					$sql .= "		(";
					$sql .= "		 acta_conciliacion_id,";
					$sql .= "		 empresa_id,";
					$sql .= "		 glosa_id,";
					$sql .= "		 prefijo,";
					$sql .= "		 factura_fiscal";
					$sql .= "		)";
					$sql .= "VALUES (";
					$sql .= "		 ".$this->Actaid.",";
					$sql .= "		'".$this->Empresa."',";
					$sql .= "		'".$this->GlosaId."',";
					$sql .= "		'".$this->Factura[0]."',";
					$sql .= "		 ".$this->Factura[1]." ";
					$sql .= "		);";
				}						
			}
			
		
			$ejecutar = true;
			if($sql2 = $this->ConciliarCargo())
				$sql .= $sql2;
			else
				$ejecutar = false;
			
			if($this->VAceptadoT + $this->VNAceptadoT > $this->VGlosaT)
			{
				$this->frmError['MensajeError'] = "LA SUMA DEL VALOR NO ACEPTADO Y EL VALOR ACEPTADO, DEL TOTAL DE LA CUENTA 
					 							   NO DEBEN SER MAYOR QUE EL VALOR DE LA GLOSA";
				$ejecutar = false;
			}
				
			if($ejecutar)
			{	
				if(!$rst = $this->ConexionBaseDatos($sql))
					return false;
		 								  
		 		$this->Parametro = "Informacion";	
				$this->frmError['Informacion'] = "LOS VALORES DE LA CUENTA Nº ".$this->CuentaId." SE HAN MODIFICADO CORRECTAMENTE";
				
			}
			
		 	$this->ConciliarGlosaCuenta();
			return true;
		}
		/************************************************************************************
		* Funcion donde se crea el sql para realizar la conciliacion de los cargos
		*
		* @return string
		*************************************************************************************/
		function ConciliarCargo()
		{
			$this->Parametro = "MensajeError";
			
			$valor = $_REQUEST['valor_glosa'];
			$transaccion = $_REQUEST['transaccion'];
			$glosacuentaid = $_REQUEST['glosacuentaid'];
			
			for($i=0; $i<sizeof($transaccion); $i++)
			{
	
				if( ($this->VAceptado[$i] == "" && $this->VNoAceptado[$i] == "")
					|| ($this->VAceptado[$i] == 0 && $this->VNoAceptado[$i] == 0))
				{
					$this->frmError['MensajeError'] = "SE DEBEN INGRESAR VALORES VALIDOS PARA HACER LA CONCILIACION";
					return false;
				}
				else
				{
					if($this->VAceptado[$i] == "") $this->VAceptado[$i] = 0;
					if($this->VNoAceptado[$i] == "") $this->VNoAceptado[$i] = 0; 
							
					if(!is_numeric($this->VAceptado[$i]))
					{
						$this->frmError['MensajeError'] = "EN LA TRANSACCIÓN Nº ".$transaccion[$i]." EL VALOR ACEPTADO INGRESADO, NO ES VALIDO";
						return false;
					}
					else if(!is_numeric($this->VNoAceptado[$i]))
					 	{
							$this->frmError['MensajeError'] = "EN LA TRANSACCIÓN Nº ".$transaccion[$i]." EL VALOR NO ACEPTADO INGRESADO, NO ES VALIDO";
							return false;
					 	}
					 	else if($this->VAceptado[$i]+$this->VNoAceptado[$i] > $valor[$i])
					 		{
					 			$this->frmError['MensajeError'] = "EN LA TRANSACCIÓN Nº ".$transaccion[$i]."
					 											   LA SUMA DEL VALOR NO ACEPTADO Y EL VALOR ACEPTADO, 
					 											   NO DEBEM SER MAYOR QUE EL VALOR DE LA GLOSA";
					 			return false;
					 		}
					 		else
					 		{
					 			$this->VAceptadoT += $this->VAceptado[$i];
					 			$this->VNAceptadoT += $this->VNoAceptado[$i];
					 			
					 			$sql .= "UPDATE	glosas_detalle_cargos ";
								$sql .= "SET	valor_aceptado = ".$this->VAceptado[$i].", ";
								$sql .= "		valor_no_aceptado = ".$this->VNoAceptado[$i].", ";
								$sql .= "		sw_estado = '2' ";
								$sql .= "WHERE	glosa_detalle_cargo_id = ".$glosacuentaid[$i]." ";
								$sql .= "AND	glosa_id = ".$this->GlosaId." ";
								$sql .= "AND	sw_estado NOT IN ('3','0'); ";
							}
				}
			}
			
			if($sql3 = $this->ConciliarInsumo())
				$sql .= $sql3;
				
			return $sql;
		}
		/************************************************************************************
		* Funcion donde se crea el sql para realizar la conciliacion de los insumos
		*
		* @return string
		*************************************************************************************/
		function ConciliarInsumo()
		{
			$this->Parametro = "MensajeError";
			
			$valor = $_REQUEST['valor_glosa'];
			$producto = $_REQUEST['producto'];
			$glosacuentaid = $_REQUEST['glosacuentaid'];
			
			
			for($i= $_REQUEST['cargoconcilia']; $i<sizeof($producto); $i++)
			{
	
				if( ($this->VAceptado[$i] == "" && $this->VNoAceptado[$i] == "")
					|| ($this->VAceptado[$i] == 0 && $this->VNoAceptado[$i] == 0))
				{
					$this->frmError['MensajeError'] = "SE DEBEN INGRESAR VALORES VALIDOS PARA HACER LA CONCILIACION";
					return false;
				}
				else
				{
					if($this->VAceptado[$i] == "") $this->VAceptado[$i] = 0;
					if($this->VNoAceptado[$i] == "") $this->VNoAceptado[$i] = 0; 
							
					if(!is_numeric($this->VAceptado[$i]))
					{
						$this->frmError['MensajeError'] = "PARA EL INSUMO CON CODIGO Nº ".$producto[$i]." EL VALOR ACEPTADO INGRESADO, NO ES VALIDO";
						return false;
					}
					else if(!is_numeric($this->VNoAceptado[$i]))
					 	{
							$this->frmError['MensajeError'] = "PARA EL INSUMO CON CODIGO Nº ".$producto[$i]." EL VALOR NO ACEPTADO INGRESADO, NO ES VALIDO";
							return false;
					 	}
					 	else if($this->VAceptado[$i]+$this->VNoAceptado[$i] > $valor[$i])
					 		{
					 			$this->frmError['MensajeError'] = "PARA EL INSUMO CON CODIGO Nº ".$producto[$i]." LA SUMA DEL VALOR NO ACEPTADO " .
					 																				"Y EL VALOR ACEPTADO, NO DEBEM SER MAYOR QUE EL VALOR DE LA GLOSA";
					 			return false;
					 		}
					 		else
					 		{
					 			$this->VAceptadoT += $this->VAceptado[$i];
					 			$this->VNAceptadoT += $this->VNoAceptado[$i];
					 			
					 			$sql .= "UPDATE	glosas_detalle_inventarios ";
								$sql .= "SET	valor_aceptado = ".$this->VAceptado[$i].", ";
								$sql .= "		valor_no_aceptado = ".$this->VNoAceptado[$i].", ";
								$sql .= "		sw_estado = '2' ";
								$sql .= "WHERE	glosa_detalle_inventario_id = ".$glosacuentaid[$i]." ";
								$sql .= "AND	glosa_id = ".$this->GlosaId." ";
								$sql .= "AND	sw_estado NOT IN ('3','0'); ";
							}
				}
			}
			
			return $sql;
		}
		/************************************************************************************
		* Funcion que pernmite mostrar el acta de conciliacion para editar la observacion que
		* se le adiciono al principio
		*
		* @return boolean
		*************************************************************************************/
		function EditarActaConciliacion()
		{
			$this->Empresa = $_SESSION['Auditoria']['razon'];
			$this->Cliente = $this->ObtenerTercero($_REQUEST['tercero']);
			$this->action1 = ModuloGetURL('app','AuditoriaCuentas','user','ConciliarCuentas',array("tercero"=>$_REQUEST['tercero']));
			$this->action2 = ModuloGetURL('app','AuditoriaCuentas','user','EditarActaConciliacionBD',
										   array("tercero"=>$_REQUEST['tercero'],"acta_id"=>$_REQUEST['acta_id']));

			$this->ObtenerInformacionActasConciliacion($_REQUEST['acta_id']);
			$this->FormaEditarActaConciliacion();
			return true;
		}
		/************************************************************************************
		* Funcion que pernmite modificar el acta de conciliacion en la base de datos
		*
		* @return boolean
		*************************************************************************************/
		function EditarActaConciliacionBD()
		{
			$actaid = $_REQUEST['acta_id'];
			$tercero = explode("/",$_REQUEST['tercero']);
			$observacionA = $_REQUEST['observacionA'];
			
			$sql .= "UPDATE actas_conciliacion_glosas ";
			$sql .= "SET	observacion = '".$observacionA."' ";
			$sql .= "WHERE	acta_conciliacion_id = ".$actaid." ";
			$sql .= "AND	tipo_id_tercero = '".$tercero[0]."' ";
			$sql .= "AND	tercero_id = '".$tercero[1]."'; ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$informacion  = "EL ACTA DE CONCILIACION REALIZADA ENTRE <b class=\"label_mark\">".$_SESSION['Auditoria']['razon']."</b> ";
			$informacion .= "Y <b class=\"label_mark\">".$this->ObtenerTercero($_REQUEST['tercero'])."</b>, SE HA MODIFICADO <br>";
			$this->action = ModuloGetURL('app','AuditoriaCuentas','user','EditarActaConciliacion',
										  array("tercero"=>$_REQUEST['tercero'],"acta_id"=>$_REQUEST['acta_id']));
		
			$this-> FormaInformacion($informacion);
			return true;
		}
		/************************************************************************************
		* Funcion que permite aceptar los valores de aceptados y no aceptados de la factura
		* 
		* @return boolean
		*************************************************************************************/
		function AceptarGlosaFactura()
		{
			$this->ValorGlosaR = $_REQUEST['valor_glosa'];
			$this->GlosaAceptado = $_REQUEST['valor_aceptado'];
			$this->GlosaNoAceptado = $_REQUEST['valor_noaceptado'];
			
						
			if($this->GlosaNoAceptado == "" && $this->GlosaAceptado == "")
			{
				$this->frmError['MensajeError'] = "POR FAVOR INGRESAR VALORES VALIDOS";
				$this->ConsultarInformacionGlosa();
				return true;
			}

			if($this->GlosaAceptado == "") $this->GlosaAceptado = 0;
			if($this->GlosaNoAceptado == "") $this->GlosaNoAceptado = 0;
			
			if($this->GlosaNoAceptado == 0 && $this->GlosaAceptado == 0)
			{
				$this->ConsultarInformacionGlosa();
				return true;
			}
			
			if(!is_numeric($this->GlosaAceptado))
			{
				$this->frmError['MensajeError'] = "EL VALOR INGRESADO NO ES VALIDO";
				$this->ConsultarInformacionGlosa();
				return true;
			}
			
			if(!is_numeric($this->GlosaNoAceptado))
			{
				$this->frmError['MensajeError'] = "EL VALOR INGRESADO NO ES VALIDO";
				$this->ConsultarInformacionGlosa();
				return true;
			}
			
			if(($this->GlosaNoAceptado+$this->GlosaAceptado) > $this->ValorGlosaR )
			{
				$this->frmError['MensajeError'] = "LA SUMA DEL VALOR ACEPTADO Y EL VALOR NO ACEPTADO, 
												   NO DEBE SER MAYOR AL VALOR DE LA GLOSA";
				$this->ConsultarInformacionGlosa();
				return true;
			}
		
			$id_glosa = $_REQUEST['glosa_id'];
			$id_cuenta = $_REQUEST['glosa_id_cuenta'];
			$numero_cuenta = $_REQUEST['numero_cuenta'];
			
			if($_SESSION['Auditoria']['sistema'] == "SIIS")
			{
				$sql .= "UPDATE	glosas_detalle_cuentas ";
				$sql .= "SET	sw_estado = '2' ";
				$sql .= "WHERE	glosa_id = ".$id_glosa." ";
				$sql .= "AND	sw_estado = '1'; ";
				
				$sql .= "UPDATE	glosas_detalle_cargos ";
				$sql .= "SET	sw_estado = '2' ";
				$sql .= "WHERE	glosa_id = ".$id_glosa." ";
				$sql .= "AND	sw_estado = '1'; ";
				
				$sql .= "UPDATE	glosas_detalle_inventarios ";
				$sql .= "SET	sw_estado = '2' ";
				$sql .= "WHERE	glosa_id = ".$id_glosa." ";
				$sql .= "AND	sw_estado  = '1'; ";
			}
			$sql .= "UPDATE	glosas ";
			$sql .= "SET		sw_estado = '2', ";
			$sql .= "				sw_glosa_parcial = '1', ";
			$sql .= "				valor_aceptado = ".$this->GlosaAceptado.",";
			$sql .= "				valor_no_aceptado = ".$this->GlosaNoAceptado." ";
			$sql .= "WHERE	glosa_id = ".$id_glosa."; ";
					
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$request1 = array("tipo_id_tercero"=>$_REQUEST['tipo_id_tercero'],"tercero_id"=>$_REQUEST['tercero_id'],"num_envio"=>$_REQUEST['num_envio'],
							  "pagina"=>$_REQUEST['pagina'],"nombre_tercero"=>$_REQUEST['nombre_tercero'],"pagina1"=>$_REQUEST['pagina1'],
							  "factura"=>$_REQUEST['factura'],"glosa_id"=>$_REQUEST['glosa_id']);

			
			$this->action = ModuloGetURL('app','AuditoriaCuentas','user','ConsultarInformacionGlosa',$request1);
		 	
		 	$this->Imprimir = 1;					  
		 	$informacion = "LA GLOSA Nº ".$id_glosa." SE HA 
		 									MODIFICADO CON UN VALOR ACEPTADO DE $".$this->GlosaAceptado." Y UN VALOR NO ACEPTADO DE $".$this->GlosaNoAceptado;
		 	$this->FormaInformacion($informacion);
			return true;
		}
		/************************************************************************************
		* Funcion que permite ingresar los valores aceptados y no acepatdos de las cuentas
		* 
		* @return boolean
		*************************************************************************************/
		function AceptarGlosaCuenta()
		{
			$this->VAceptadoC = $_REQUEST['valor_aceptado'];
			$this->VNoAceptadoC = $_REQUEST['valor_noaceptado'];
			$this->ValorGlosaR = $_REQUEST['valor_glosa'];
						
			if($this->VNoAceptadoC  == "" && $this->VAceptadoC == "")
			{
				$this->frmError['MensajeError'] = "POR FAVOR INGRESAR VALORES VALIDOS";
				$this->MostrarInformacionCuentaGlosada();
				return true;
			}
			
			if($this->VAceptadoC  == "") $this->VAceptadoC  = 0;
			if($this->VNoAceptadoC  == "") $this->VNoAceptadoC  = 0;
									
			if($this->VNoAceptadoC  == 0 && $this->VAceptadoC == 0)
			{
				$this->MostrarInformacionCuentaGlosada();
				return true;
			}

			if(!is_numeric($this->VAceptadoC ))
			{
				$this->frmError['MensajeError'] = "EL VALOR INGRESADO NO ES VALIDO";
				$this->MostrarInformacionCuentaGlosada();
				return true;
			}
			
			if(!is_numeric($this->VNoAceptadoC ))
			{
				$this->frmError['MensajeError'] = "EL VALOR INGRESADO NO ES VALIDO";
				$this->MostrarInformacionCuentaGlosada();
				return true;
			}
			
			if(($this->VNoAceptadoC + $this->VAceptadoC ) > $this->ValorGlosaR )
			{
				$this->frmError['MensajeError'] = "LA SUMA DEL VALOR ACEPTADO Y EL VALOR NO ACEPTADO, 
												   NO DEBE SER MAYOR AL VALOR DE LA GLOSA";
				$this->MostrarInformacionCuentaGlosada();
				return true;
			}
		
			$id_cuenta = $_REQUEST['glosa_id_cuenta'];
			$id_glosa = $_REQUEST['glosa_id'];
			$numero_cuenta = $_REQUEST['numero_cuenta'];
			
			
			$sql .= "UPDATE	glosas_detalle_cuentas ";
			$sql .= "SET	valor_aceptado = ".$this->VAceptadoC .", ";
			$sql .= "			valor_no_aceptado = ".$this->VNoAceptadoC .", ";
			$sql .= "			sw_estado = '2' ";
			$sql .= "WHERE	glosa_detalle_cuenta_id = ".$id_cuenta." ";
			$sql .= "AND		glosa_id = ".$id_glosa." ";
			$sql .= "AND		numerodecuenta = ".$numero_cuenta."; ";
			
			$sql .= "UPDATE	glosas_detalle_cargos ";
			$sql .= "SET		sw_estado = '2', ";
			$sql .= "				valor_aceptado = 0, ";
			$sql .= "				valor_no_aceptado = 0 ";
			
			$sql .= "WHERE	glosa_detalle_cuenta_id = ".$id_cuenta." ";
			$sql .= "AND		glosa_id = ".$id_glosa." ";
			$sql .= "AND		sw_estado NOT IN ('3','0'); ";
			
			$sql .= "UPDATE	glosas_detalle_inventarios ";
			$sql .= "SET		sw_estado = '2', ";
			$sql .= "				valor_aceptado = 0, ";
			$sql .= "				valor_no_aceptado = 0 ";

			$sql .= "WHERE	glosa_detalle_cuenta_id = ".$id_cuenta." ";
			$sql .= "AND		glosa_id = ".$id_glosa." ";
			$sql .= "AND		sw_estado NOT IN ('3','0'); ";

			if($_REQUEST['cantidad'] == '1')
			{
				$sql .= "UPDATE	glosas ";
				$sql .= "SET	sw_estado = '2', ";
				$sql .= "		sw_glosa_parcial = '1' ";
				$sql .= "WHERE	glosa_id = ".$id_glosa."; ";
			}
			else
			{
				$sql .= "UPDATE	glosas ";
				$sql .= "SET	sw_estado = '2', ";
				$sql .= "		sw_glosa_parcial = '0' ";
				$sql .= "WHERE	glosa_id = ".$id_glosa."; ";
			}
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$arreglo = array("pagina"=>$_REQUEST['pagina'],"glosa_id"=>$_REQUEST['glosa_id'],"num_envio"=>$_REQUEST['num_envio'],
		 					 "factura"=>$_REQUEST['factura'],"pagina1"=>$_REQUEST['pagina1'],"cantidad"=>$_REQUEST['cantidad'],
		 					 "nombre_tercero"=>$_REQUEST['nombre_tercero'],"tipo_id_tercero"=>$_REQUEST['tipo_id_tercero'],
		 					 "tercero_id"=>$_REQUEST['tercero_id']);
			
			$this->action = ModuloGetURL('app','AuditoriaCuentas','user',$_REQUEST['retorno'],$arreglo);
		 								  
		 	$informacion = "LA CUENTA Nº ".$numero_cuenta." DE LA GLOSA Nº ".$id_glosa." SE HA 
		 					MODIFICADO CON UN VALOR ACEPTADO DE $".$this->VAceptadoC ." Y UN VALOR NO ACEPTADO DE $".$this->VNoAceptadoC ;
		 	$this->FormaInformacion($informacion);
			return true;
		}
		/************************************************************************************
		* Funcion que permite ingresar los valores aceptados y no aceptados de los cargos e 
		* insumos
		*
		* @return boolean
		*************************************************************************************/
		function AceptarGlosaCargos()
		{
			$bool = true;
			
			$this->Insumos = $_REQUEST['insumo'];
			$this->Transaccion = $_REQUEST['transaccion'];
			$this->GlosasDetalleId = $_REQUEST['glosa_detalle_id'];
			$this->ValorGlosaDetalle = $_REQUEST['valor_glosa'];
			$this->VAceptado = $_REQUEST['valor_aceptado'];
			$this->VNoAceptado = $_REQUEST['valor_noaceptado'];
			
			$this->ValorAceptadoCuenta = $_REQUEST['valoraceptadocuenta'];
			$this->ValorNoAceptadoCuenta = $_REQUEST['valornoaceptadocuenta'];
			
			$id_glosa = $_REQUEST['glosa_id'];
			$id_cuenta = $_REQUEST['glosa_id_cuenta'];
			$numero_cuenta = $_REQUEST['numero_cuenta'];
			
			for($i=0; $i<sizeof($this->Transaccion); $i++)
			{
				if($this->VAceptado[$i] != "" || $this->VNoAceptado[$i] != "")
				{	
					$mensaje = "EN LA TRANSACCION Nº ".$this->Transaccion[$i];
					
					if($this->VAceptado[$i] == "") $this->VAceptado[$i] = 0;
					if($this->VNoAceptado[$i] == "") $this->VNoAceptado[$i] = 0;
			
					if(!is_numeric($this->VAceptado[$i]))
					{
						$this->frmError['MensajeError2'] = $mensaje." EL VALOR INGRESADO NO ES VALIDO";
						$bool = false;
						break;
					}
			
					if(!is_numeric($this->VNoAceptado[$i]))
					{
						$this->frmError['MensajeError2'] = $mensaje." EL VALOR INGRESADO NO ES VALIDO";
						$bool = false;
						break;
					}
			
					if(($this->VAceptado[$i]+$this->VNoAceptado[$i]) > $this->ValorGlosaDetalle[$i])
					{
						$this->frmError['MensajeError2'] = $mensaje." LA SUMA DEL VALOR ACEPTADO Y EL VALOR NO ACEPTADO, 
												   		  NO DEBE SER MAYOR AL VALOR DE LA GLOSA";
						$bool = false;
						break;
					}
					
					if($this->VAceptado[$i] == "") $this->VAceptado[$i] = 0;
					if($this->VNoAceptado[$i] == "") $this->VNoAceptado[$i] = 0;
					
					$this->ValorAceptadoCuenta += $this->VAceptado[$i];
					$this->ValorNoAceptadoCuenta += $this->VNoAceptado[$i];
					
					$set = "";
					if(!empty($_REQUEST[conceptoscargos][$i]))
					{
						$CC = explode("||//",$_REQUEST[conceptoscargos][$i]);
						$set = ", codigo_concepto_general = '".$CC[0]."', codigo_concepto_especifico = '".$CC[1]."'";
					}
					$sql .= "UPDATE	glosas_detalle_cargos ";
					$sql .= "SET	sw_estado = '2', ";
					$sql .= "		valor_aceptado = ".$this->VAceptado[$i].",";
					$sql .= "		valor_no_aceptado = ".$this->VNoAceptado[$i]." $set ";
					$sql .= "WHERE	glosa_detalle_cuenta_id = ".$id_cuenta." ";
					$sql .= "AND 	glosa_detalle_cargo_id = ".$this->GlosasDetalleId[$i]." ";
					$sql .= "AND	glosa_id = ".$id_glosa." ";
					$sql .= "AND	sw_estado <> '0'; ";
				}
			}
			
			if(!$bool)
			{
				$this->MostrarInformacionCuentaGlosada();
				return true;
			}

			for($i; $i<sizeof($this->Insumos)+sizeof($this->Transaccion); $i++)
			{
				if($this->VAceptado[$i] != "" ||	$this->VNoAceptado[$i] != "")
				{			
					$mensaje = "PARA EL PRODUCTO CODIGO Nº ".$this->Insumos[$i];
					
					if($this->VAceptado[$i] == "") $this->VAceptado[$i] = 0;
					if($this->VNoAceptado[$i] == "") $this->VNoAceptado[$i] = 0;
			
					if(!is_numeric($this->VAceptado[$i]))
					{
						$this->frmError['MensajeError2'] = $mensaje." EL VALOR INGRESADO NO ES VALIDO";
						$bool = false;
						break;
					}
			
					if(!is_numeric($this->VNoAceptado[$i]))
					{
						$this->frmError['MensajeError2'] = $mensaje." EL VALOR INGRESADO NO ES VALIDO";
						$bool = false;
						break;
					}
			
					if(($this->VAceptado[$i]+$this->VNoAceptado[$i]) > $this->ValorGlosaDetalle[$i])
					{
						$this->frmError['MensajeError2'] = $mensaje." LA SUMA DEL VALOR ACEPTADO Y EL VALOR NO ACEPTADO, 
												   		  NO DEBE SER MAYOR AL VALOR DE LA GLOSA";
						$bool = false;
						break;
					}
					
					if($this->VAceptado[$i] == "") $this->VAceptado[$i] = 0;
					if($this->VNoAceptado[$i] == "") $this->VNoAceptado[$i] = 0;
	
					$this->ValorAceptadoCuenta += $this->VAceptado[$i];
					$this->ValorNoAceptadoCuenta += $this->VNoAceptado[$i];
	
					
					$set = "";
					if(!empty($_REQUEST[conceptosinsumos][$i]))
					{
						$CC = explode("||//",$_REQUEST[conceptosinsumos][$i]);
						$set = ", codigo_concepto_general = '".$CC[0]."', codigo_concepto_especifico = '".$CC[1]."'";
					}
					$sql .= "UPDATE	glosas_detalle_inventarios ";
					$sql .= "SET	sw_estado = '2', ";
					$sql .= "		valor_aceptado = ".$this->VAceptado[$i].",";
					$sql .= "		valor_no_aceptado = ".$this->VNoAceptado[$i]." $set ";
					$sql .= "WHERE	glosa_detalle_cuenta_id = ".$id_cuenta." ";
					$sql .= "AND 	glosa_detalle_inventario_id = ".$this->GlosasDetalleId[$i]." ";
					$sql .= "AND	glosa_id = ".$id_glosa." ";
					$sql .= "AND	sw_estado <> '0'; ";
				}
			}
			
			if(!$bool)
			{
				$this->MostrarInformacionCuentaGlosada();
				return true;
			}
						
			$sql .= "UPDATE	glosas_detalle_cuentas ";
			$sql .= "SET	sw_estado = '2', ";
			$sql .= "			valor_aceptado = 0,";
			$sql .= "			valor_no_aceptado = 0 ";

			$sql .= "WHERE	glosa_detalle_cuenta_id = ".$id_cuenta." ";
			$sql .= "AND	glosa_id = ".$id_glosa." ";
			$sql .= "AND	numerodecuenta = ".$numero_cuenta."; ";
			$sql .= "UPDATE	glosas ";
			$sql .= "SET	sw_estado = '2', ";
			$sql .= "		sw_glosa_parcial = '0' ";
			$sql .= "WHERE	glosa_id = ".$id_glosa."; ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$arreglo = array("pagina"=>$_REQUEST['pagina'],"glosa_id"=>$_REQUEST['glosa_id'],"num_envio"=>$_REQUEST['num_envio'],
		 					 "factura"=>$_REQUEST['factura'],"pagina1"=>$_REQUEST['pagina1'],"cantidad"=>$_REQUEST['cantidad'],
		 					 "nombre_tercero"=>$_REQUEST['nombre_tercero'],"tipo_id_tercero"=>$_REQUEST['tipo_id_tercero'],
		 					 "tercero_id"=>$_REQUEST['tercero_id']);

			
			$this->action = ModuloGetURL('app','AuditoriaCuentas','user',$_REQUEST['retorno'],$arreglo);
		 								  
		 	$informacion = "LOS CARGOS DE LA CUENTA Nº ".$numero_cuenta." DE LA GLOSA Nº ".$id_glosa." HAN SIDO MODIFICADOS";
		 	$this->FormaInformacion($informacion);
			return true;
		}
		/************************************************************************************
		* Funcion donde se obtienen los datos de identificacion de los clientes cuando se 
		* hace una busqueda por prefijo y numero de la factura
		* 
		* @params array $datos vector con los datos de prefijo y factura_f 
		* @params array $empresa Identificador de la empresa en la cual se esta trabajado 
		* @return array 
		*************************************************************************************/
		function ObtenerDatosClienteXFactura($datos, $empresa)
		{
			$sql  = "SELECT tipo_id_tercero,";
			$sql .= "				tercero_id ";
			$sql .= "FROM		view_fac_facturas ";
			$sql .= "WHERE	prefijo = '".$datos['prefijo']."' ";
			$sql .= "AND		factura_fiscal = ".$datos['factura_f']." ";
			$sql .= "AND		empresa_id = '".$empresa."' ";
						
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$clente = array();
			while (!$rst->EOF)
			{
				$cliente = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $cliente;
		}
		/************************************************************************************
		* Funcion donde se obtiene el sql que busca los clientes
		* 
		* @return boolean 
		*************************************************************************************/
		function ObtenerSqlBuscarDatosCliente($datos,$factura = array())
		{
			$empresa = $_SESSION['Auditoria']['empresa'];
			
			$sql .= "SELECT DISTINCT T.tipo_id_tercero,"; 
			$sql .= "				T.tercero_id, ";
			$sql .= "				T.nombre_tercero ";
			$sql .= "FROM 	terceros T, ";
			$sql .= "				view_fac_facturas FF, ";
			$sql .= "				glosas GL ";
			
			if($_SESSION['Auditoria']['clientes'] == "0")
				$sql .= "		,userpermisos_auditoria_cuentas_clientes UC ";
						
			$sql .= "WHERE 	FF.tipo_id_tercero = T.tipo_id_tercero ";
			$sql .= "AND 	 	FF.tercero_id = T.tercero_id ";
			$sql .= "AND 		GL.prefijo = FF.prefijo  ";
			$sql .= "AND 		GL.factura_fiscal = FF.factura_fiscal  ";
			$sql .= "AND 		GL.empresa_id = FF.empresa_id  ";
			$sql .= "AND 		GL.valor_glosa > 0  ";
			$sql .= "AND 		GL.sw_estado IN ('1'::bpchar,'2'::bpchar) ";
      
			if($_SESSION['Auditoria']['clientes'] == "0")
			{ 
				$sql .= "AND	 T.tipo_id_tercero = UC.tipo_id_tercero ";
				$sql .= "AND 	 T.tercero_id = UC.tercero_id ";
			}
						
			if($datos['nombre_tercero'] != "")
				$sql .= "AND 		T.nombre_tercero ILIKE '%".$datos['nombre_tercero']."%' ";
			
			if($datos['tercero_id'] != "")
			{
				$sql .= "AND 		T.tercero_id = '".$datos['tercero_id']."' ";
				
				if($datos['tipo_id_tercero'] != "0" && $datos['tipo_id_tercero'] != "")
					$sql .= "AND 		T.tipo_id_tercero = '".$datos['tipo_id_tercero']."' ";
			}
			if($factura['factura_f'])
			{
				$sql .= "AND		FF.prefijo = '".$factura['prefijo']."' ";
				$sql .= "AND		FF.factura_fiscal = ".$factura['factura_f']." ";
			}
			$sql .= "ORDER BY 3 ";
									
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$clientes = array();
			
			while (!$rst->EOF)
			{
				$clientes[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $clientes;			
		}
		/************************************************************************************
		* Funcion donde se procesan los sql que buscan los datos de las facturas 
		* 
		* @return array 
		*************************************************************************************/
		function ObtenerDatosFacturasGlosadas()
		{
			if(!$_REQUEST['registros'])
			{
				if(!$rst = $this->ConexionBaseDatos($_SESSION['SqlBuscarFA'])) return false;
				
				$_REQUEST['registros'] = 0;
				if(!$rst->EOF) $_REQUEST['registros'] = $rst->RecordCount();
			}
			
			$this->ProcesarSqlConteo($_SESSION['SqlContarFA']);
			
			if($this->limit < $this->conteo)
			{
				$_SESSION['Auditoria']['buscador']['combo'] = $this->ComboBSQ;
				$_SESSION['Auditoria']['buscador']['numero'] = $this->Numero ;
				$_SESSION['Auditoria']['buscador']['fecha_fin'] = $this->FechaFin;
				$_SESSION['Auditoria']['buscador']['valor_glosa'] = $this->BVGlosa;
				$_SESSION['Auditoria']['buscador']['fecha_inicio'] = $this->FechaInicio;
				$_SESSION['Auditoria']['buscador']['valor_factura'] = $this->BVFactura;
				$_SESSION['Auditoria']['buscador']['comparacionglosa'] = $this->OperadorGlosa;
				$_SESSION['Auditoria']['buscador']['comparacionfactura'] = $this->OperadorFactura;
			}
			
			$sql  = $_SESSION['SqlBuscarFA'];	
			$sql .= "ORDER BY 1,2 ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
					
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

			while (!$rst->EOF)
			{
				$facturas[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			return $facturas;
		}
		/************************************************************************************ 
		* Funcion donde se obtiene el sql que hace la busqueda de facturas segun los 
		* criterios que se hayan dado para la misma, se suben los dos sql (en el que se 
		* cuenta el numero de registros y el que busca los datos y se suben a session) 
		* 
		* @return boolean 
		*************************************************************************************/
		function ObtenerSqlBuscarFacturas()
		{		
			unset($_SESSION['Auditoria']['buscador']);
			
			$_SESSION['Auditoria']['sistema'] = $this->Sistema;

			$this->Numero = $_REQUEST['numero'];
			$this->BVGlosa = $_REQUEST['valor_glosa'];
			$this->ComboBSQ = $_REQUEST['combo'];
			$this->FechaFin = $_REQUEST['fecha_fin'];
			$this->BVFactura = $_REQUEST['valor_factura'];
			$this->FechaInicio = $_REQUEST['fecha_inicio'];
			$this->OperadorGlosa = $_REQUEST['comparacionglosa'];
			$this->OperadorFactura = $_REQUEST['comparacionfactura'];
			$empresa = $_SESSION['Auditoria']['empresa'];
			
			$datos = $_REQUEST;
			
			$sql  = "SELECT	FF.*,";
			$sql .= "				ED.envio_id ";
			$sql .= "FROM		(	SELECT 	GL.glosa_id,";
			$sql .= "									TO_CHAR(GL.fecha_glosa,'DD/MM/YYYY') AS fecha_glosa, ";
			$sql .= "									FF.prefijo, ";
			$sql .= "									FF.factura_fiscal,";
			$sql .= "									GL.valor_glosa, ";
			$sql .= "									CASE WHEN GL.sw_estado='1' THEN 'SIN RESPUESTA' ";
			$sql .= "											ELSE 'CON RESPUESTA' END AS estado, ";
			$sql .= "									FF.total_factura, ";
			$sql .= "									FF.sistema, ";
			$sql .= "									FF.empresa_id,"; 
			$sql .= "									GL.sw_estado ";
			$sql .= "					FROM		glosas GL,	";
			$sql .= "									view_fac_facturas FF ";
			$sql .= "					WHERE 	GL.empresa_id = FF.empresa_id ";
			$sql .= "					AND GL.sw_estado IN ('1','2') ";
			$sql .= "					AND GL.prefijo = FF.prefijo ";
			$sql .= "					AND GL.factura_fiscal = FF.factura_fiscal ";
			$sql .= "					AND GL.valor_glosa > 0 ";
			$sql .= "					AND FF.empresa_id = '".$empresa."' ";
			$sql .= "					AND FF.saldo >= 0 ";
			$sql .= "					AND FF.tipo_id_tercero = '".$datos['tipo_id_tercero']."' ";
			$sql .= "					AND FF.tercero_id = '".$datos['tercero_id']."' ";
			
			if($this->Numero != "")
			{
				switch($this->ComboBSQ)
				{
					case '02':
						$sql .= "			AND			GL.glosa_id = ".$this->Numero ." ";
					break;
					default:
						$sql .= "			AND			FF.prefijo = '".$this->ComboBSQ."' ";
						$sql .= "			AND 		FF.factura_fiscal = ".$this->Numero." ";
					break;
				}
			}
			if($this->ComboBSQ != '01' && $this->ComboBSQ != '02')
				$sql .= "			AND			FF.prefijo = '".$this->ComboBSQ."' ";
			
			if($_SESSION['Auditoria']['categorias'] == "0")
				$sql .= "					AND  permisos_auditoria(".UserGetUID().",GL.glosa_id) >0 ";
			
			if(is_numeric($this->BVGlosa))
				$sql .= "					AND GL.valor_glosa ".$this->OperadorGlosa." ".$this->BVGlosa." ";
			
			if(is_numeric($this->BVFactura))
				$sql .= "					AND FF.total_factura ".$this->OperadorFactura." ".$this->BVFactura." ";
			
			$fecha1 = explode("/",$this->FechaInicio);
			if(sizeof($fecha1) == 3)		
				$sql .= "					AND GL.fecha_glosa >= '".$fecha1[2]."-".$fecha1[1]."-".$fecha1[0]." 00:00:00' ";

			$fecha2 = explode("/",$this->FechaFin);
			if(sizeof($fecha2) == 3)
				$sql .= "					AND GL.fecha_glosa <= '".$fecha2[2]."-".$fecha2[1]."-".$fecha2[0]." 00:00:00' ";

			$sql .= "				) AS FF ";
			$sql .= "				LEFT JOIN ";
			$sql .= "				(	SELECT 	ED.prefijo,";
			$sql .= "									ED.factura_fiscal,";
			$sql .= "									ED.empresa_id, ";
			$sql .= "									ED.envio_id ";
			$sql .= "					FROM 		envios_detalle ED, ";
			$sql .= "									envios EN ";
			$sql .= "					WHERE		ED.envio_id = EN.envio_id  ";
			$sql .= "					AND			EN.sw_estado <> '2' ";
			$sql .= "					AND			ED.empresa_id = '".$empresa."' ";
			if($this->Numero != "")
			{
				switch($this->ComboBSQ)
				{
					case '01':
						$sql .= "		AND		EN.envio_id = ".$this->Numero ." ";
					break;
					default:
						$sql .= "			AND 		ED.factura_fiscal = ".$this->Numero." ";
					break;
				}
			}
			if($this->ComboBSQ != '01' && $this->ComboBSQ != '02')
				$sql .= "			AND			ED.prefijo = '".$this->ComboBSQ."' ";
			
			$sql .= "				) AS ED ";
			$sql .= "				ON( ED.prefijo = FF.prefijo AND ";
			$sql .= "						ED.factura_fiscal = FF.factura_fiscal AND ";
			$sql .= "						ED.empresa_id = FF.empresa_id ";
			$sql .= "					) ";
			
			if($this->Numero != "")
			{
				switch($this->ComboBSQ)
				{
					case '01':
						$sql .= "WHERE	ED.envio_id = ".$this->Numero ." ";
					break;
					case '02':
						$sql .= "WHERE	FF.glosa_id = ".$this->Numero ." ";
					break;
					default:
						$sql .= "WHERE	FF.prefijo = '".$this->ComboBSQ."' ";
						$sql .= "AND 		FF.factura_fiscal = ".$this->Numero." ";
					break;
				}
			}
			
			$_SESSION['SqlBuscarFA'] = $sql.$where;
			$_SESSION['SqlContarFA'] = "SELECT COUNT(*) ".$where;
			
			if($this->PrimeraVez != 1)
			{
				$this->MostrarInformacionFacturasGlosadas();
			}
			return true;
		}
		/************************************************************************************ 
		* Funcion domde se seleccionan los tipos de id de los terceros 
		* 
		* @return array datos de tipo_id_terceros 
		*************************************************************************************/
		function ObtenerTipoIdTerceros()
		{
			$sql  = "SELECT tipo_id_tercero,descripcion FROM tipo_id_terceros ORDER BY 2 ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				
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
			$this->paginaActual = 1;
			if($limite == null)
			{
				$this->limit = UserGetVar(UserGetUID(),'LimitRowsBrowser');
				if(!$this->limit)
					$this->limit = 20;
			}
			else
			{
				$this->limit = $limite;
			}
			
			if($_REQUEST['offset'])
			{
				$this->paginaActual = intval($_REQUEST['offset']);
				if($this->paginaActual > 1)
				{
					$this->offset = ($this->paginaActual - 1) * ($this->limit);
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
		* Funcion en donde se obtienen los prefijos que maneja la empresa 
		* 
		* @return array datos de la tabla documentos
		*************************************************************************************/
		function ObtenerPrefijos()
		{	
			$sql  = "SELECT DISTINCT FF.prefijo ";
			$sql .= "FROM		view_fac_facturas FF, glosas GL ";
			$sql .= "WHERE	FF.empresa_id = '".$_SESSION['Auditoria']['empresa']."' ";
			$sql .= "AND		FF.tercero_id = '".$_REQUEST['tercero_id']."' ";
			$sql .= "AND		FF.tipo_id_tercero = '".$_REQUEST['tipo_id_tercero']."' ";
			$sql .= "AND		FF.empresa_id = GL.empresa_id ";
			$sql .= "AND		FF.prefijo = GL.prefijo ";
			$sql .= "AND		FF.factura_fiscal = GL.factura_fiscal ";
			$sql .= "AND		GL.sw_estado IN ('1','2') ";
			$sql .= "ORDER BY 1 ";
						
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
	
			$i = 0;
			while (!$rst->EOF)
			{
				$datos[$i] = $rst->fields[0];
				$rst->MoveNext();
				$i++;
		    }
			$rst->Close();
			
			return $datos;  		       		
		}
		/************************************************************************************ 
		* Funcion en donde se obtienen los prefijos que maneja la empresa 
		* 
		* @return array datos de la tabla documentos
		*************************************************************************************/
		function ObtenerPrefijosTodasFacturas($empresa)
		{	
			$sql  = "SELECT DISTINCT GL.prefijo ";			
			$sql .= "FROM 	glosas GL ";
			$sql .= "WHERE 	GL.empresa_id = '".$empresa."' ";
			$sql .= "AND		GL.sw_estado IN ('1'::bpchar,'2'::bpchar) ";
			$sql .= "ORDER BY 1 ";
						
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
	
			while (!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos;  		       		
		}
		/************************************************************************************
		* Funcion que permite obtener la informacion de la glosa de una factura 
		* 
		* @return boolean 
		*************************************************************************************/
		function ObtenerInformacionGlosaFactura()
		{
			$empresa = $_SESSION['Auditoria']['empresa'];
			
			$this->GlosaId = $_REQUEST['glosa_id'];
			$this->TerceroId = $_REQUEST['tercero_id'];
			$this->TerceroTipo = $_REQUEST['tipo_id_tercero'];
			$this->EnvioNumero = $_REQUEST['num_envio'];
			$this->TerceroNombre = $_REQUEST['nombre_tercero'];
			
			$sql  = "SELECT GL.observacion,";
			$sql .= "				GL.documento_interno_cliente_id,";
			$sql .= "				GL.valor_glosa,";
			$sql .= "				GL.valor_aceptado,";
			$sql .= "				GL.valor_no_aceptado,";
			$sql .= "				GL.prefijo,";
			$sql .= "				GL.factura_fiscal,";
			$sql .= "				GL.auditor_id,";
			$sql .= "				GL.sw_glosa_total_factura,";
			$sql .= "				TO_CHAR(GL.fecha_glosa,'DD/MM/YYYY') AS fecha_glosa,";
			$sql .= "				TO_CHAR(GL.fecha_registro,'DD/MM/YYYY') AS fecha_registro,";
			$sql .= "				GM.motivo_glosa_descripcion,";
			$sql .= "				GT.descripcion,";
			$sql .= "				SU.nombre, ";
			$sql .= "				CG.descripcion_concepto_general ,";
			$sql .= "				CE.descripcion_concepto_especifico ";
			$sql .= "FROM		glosas GL LEFT JOIN glosas_motivos GM";
			$sql .= "				ON(GL.motivo_glosa_id = GM.motivo_glosa_id) LEFT JOIN ";
			$sql .= "				glosas_tipos_clasificacion GT ";
			$sql .= "				ON(GL.glosa_tipo_clasificacion_id = GT.glosa_tipo_clasificacion_id) ";
			$sql .= "				LEFT JOIN glosas_concepto_general CG ON (CG.codigo_concepto_general = GL.codigo_concepto_general) ";
			$sql .= "				LEFT JOIN glosas_concepto_especifico CE ON (CE.codigo_concepto_especifico = GL.codigo_concepto_especifico),";
			$sql .= "				system_usuarios SU ";
			$sql .= "WHERE 	GL.glosa_id = ".$this->GlosaId." "; 
			$sql .= "AND 		GL.empresa_id = '".$empresa."' ";
			$sql .= "AND 		GL.usuario_id = SU.usuario_id ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			if(!$rst->EOF)
			{
				$glosa = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			
			$this->FFiscal = $glosa['factura_fiscal'];
			$this->FPrefijo = $glosa['prefijo'];
			$this->GlosaFecha = $glosa['fecha_glosa'];
			$this->GlosaValor = $glosa['valor_glosa'];
			$this->GlosaMotivo = $glosa['motivo_glosa_descripcion'];
			$this->GlosaCG = $glosa['descripcion_concepto_general'];
			$this->GlosaCE = $glosa['descripcion_concepto_especifico'];
			$this->GlosaSwTotal = $glosa['sw_glosa_total_factura'];
			$this->GlosaRegistro = $glosa['fecha_registro'];
			$this->GlosaDocInterno = $glosa['documento_interno_cliente_id'];
			$this->GlosaObservacion = $glosa['observacion'];
			$this->GlosaResponsable = $glosa['nombre'];
			$this->GlosaClasificacion = $glosa['descripcion'];
			$this->GlosaAuditor = $this->ObtenerNombreUsuario($glosa['auditor_id']);
			
			if(!$_REQUEST['valor_aceptado'])
			{
				$this->GlosaAceptado = $glosa['valor_aceptado'];
				$this->GlosaNoAceptado = $glosa['valor_no_aceptado'];
			}
			$this->ObtenerInformacionFacturaII();
			return true;
		}
		/************************************************************************************
		* Funcion donde se cuentan el numero total de cuentas y cargos glosados de 
		* una factura 
		* 
		* @param  int numeor de la glosa 
		* @return int 
		*************************************************************************************/
		function ContarCuentasGlosadas($glosa)
		{
			$sql  = "SELECT  A.cont + B.cont + C.cont ";
			$sql .= "FROM (	SELECT 	COUNT(*) AS cont ";
			$sql .= "		FROM    glosas_detalle_cargos ";
			$sql .= "		WHERE   glosa_id = ".$glosa." ";
			$sql .= "		AND sw_estado IN ('1','2')) AS A,";
			$sql .= "     (	SELECT 	COUNT(*) AS cont";
			$sql .= "		FROM    glosas_detalle_inventarios";
			$sql .= "		WHERE   glosa_id = ".$glosa."  ";
			$sql .= "		AND sw_estado IN ('1','2')) AS B,";
			$sql .= "     (	SELECT 	COUNT(*) AS cont ";
			$sql .= "		FROM    glosas_detalle_cuentas ";
			$sql .= "		WHERE   glosa_id = ".$glosa."  ";
			$sql .= "		AND sw_estado IN ('1','2') ) AS C ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$total = 0;
			if (!$rst->EOF)
			{
				$total = $rst->fields[0];
				$rst->MoveNext();
			}
			$rst->Close();

			return $total;
		}
		/************************************************************************************
		* Funcion donde se toma de la base de datos el nombre del auditor 
		* 
		* @return string nombre del auditor 
		*************************************************************************************/
		function ObtenerNombreUsuario($id)
		{
			if($id != null && $id != 0)
			{
				$sql  = "SELECT nombre FROM system_usuarios WHERE usuario_id = ".$id;
				if(!$rst = $this->ConexionBaseDatos($sql))
					return false;
	
				if (!$rst->EOF)
				{
					$nombreUsuario = $rst->fields[0];
					$rst->MoveNext();
		    	}
				$rst->Close();
			}
	 		return $nombreUsuario;
		}
		/************************************************************************************
		* Funcion donde se calcula el numero de cuentas que posee un factura 
		* 
		* @params int numero de la glosa  
		* @return string 
		*************************************************************************************/
		function ObtenerCantidadCuentasFactura($glosa)
		{
			$numero = $num = null;
			if($numero == null)
			{
				$numero = $_REQUEST['factura_numero'];
			}
			
			$sql  = "SELECT FF.numerodecuenta ";
			$sql .= "FROM 	fac_facturas_cuentas FF,";
			$sql .= "		glosas GL, ";
			$sql .= "		glosas_detalle_cuentas GC ";
			$sql .= "WHERE	GL.glosa_id = ".$glosa." ";
			$sql .= "AND	GL.empresa_id = FF.empresa_id ";
			$sql .= "AND	GL.prefijo = FF.prefijo ";
			$sql .= "AND	GL.factura_fiscal = FF.factura_fiscal ";
			$sql .= "AND	GL.glosa_id = GC.glosa_id ";
			$sql .= "AND	GC.numerodecuenta = FF.numerodecuenta ";
			$sql .= "AND	GC.sw_estado NOT IN('0','3') ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			$i = 0;
			while (!$rst->EOF)
			{
				$datos[$i] = $rst->fields[0];
				$rst->MoveNext();
				$i++;
			}
			$rst->Close();
			
			return $datos;
		}
		/************************************************************************************
		* Funcion donde se toma de la base de datos las cuentas con la descripcion de las 
		* mismas 
		* 
		* @return array datos de las cuentas
		*************************************************************************************/
		function ObtenerInformacionDetalleCuentas($glosa)
		{	
			$where .= "FROM	glosas GL,";
			$where .= "		planes PL,";
			$where .= "		cuentas CU,";
			$where .= "		pacientes PA,";
			$where .= "		fac_facturas_cuentas FC,";
			$where .= "		ingresos IG, ";
			$where .= "		glosas_detalle_cuentas GC ";
			$where .= "WHERE GL.glosa_id = ".$glosa." ";
			$where .= "AND	 GL.empresa_id = '".$_SESSION['Auditoria']['empresa']."' ";
			$where .= "AND 	 FC.prefijo = GL.prefijo ";
			$where .= "AND 	 FC.factura_fiscal = GL.factura_fiscal ";
			$where .= "AND 	 FC.empresa_id = GL.empresa_id ";
			$where .= "AND 	 CU.numerodecuenta = FC.numerodecuenta ";
			$where .= "AND 	 CU.ingreso = IG.ingreso ";
			$where .= "AND 	 IG.tipo_id_paciente = PA.tipo_id_paciente ";
			$where .= "AND 	 IG.paciente_id = PA.paciente_id ";
			$where .= "AND 	 CU.plan_id = PL.plan_id ";
			$where .= "AND	 GC.numerodecuenta = CU.numerodecuenta ";
			$where .= "AND	 GC.sw_estado NOT IN ('0','3') ";
			$where .= "AND	 GC.glosa_id = GL.glosa_id ";

			$sqlCon  = "SELECT COUNT(*) ";
			$sqlCon .= $where;
			$this->ProcesarSqlConteo($sqlCon,25);
					
			$sql  = "SELECT CU.numerodecuenta,";
			$sql .= "		PA.tipo_id_paciente,";
			$sql .= "		PA.paciente_id,";
			$sql .= "		PA.primer_nombre||' '||PA.segundo_nombre||' '||PA.primer_apellido||' '||PA.segundo_apellido,";
			$sql .= "		PL.plan_descripcion,";
			$sql .= "		GC.sw_glosa_total_cuenta ";
			$sql .= $where;

			$sql .= "ORDER BY 1 ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

			$i = 0;
			while (!$rst->EOF)
			{
				$datos[$i]  = $rst->fields[0]."*".$rst->fields[1]."".$rst->fields[2]."*".$rst->fields[3];
				$datos[$i] .= "*".$rst->fields[4]."*".$rst->fields[5];
				$rst->MoveNext();
				$i++;
		    }
			$rst->Close();
			return $datos;
		}
		/************************************************************************************
		* Funcion donde se toma de la base de datos las cuentas con la descripcion de las 
		* mismas 
		* 
		* @return array datos de las cuentas
		*************************************************************************************/
		function ObtenerInformacionDetalleCuentaGlosada($glosa,$numero)
		{	
			$sql  = "SELECT PL.plan_descripcion,";
			$sql .= "		PL.plan_id, ";
			$sql .= "		IG.ingreso,";
			$sql .= "		PA.tipo_id_paciente||' '||PA.paciente_id,";
			$sql .= "		PA.primer_nombre||' '||PA.segundo_nombre||' '||PA.primer_apellido||' '||PA.segundo_apellido,";
			$sql .= "		CU.valor_cuota_paciente,";
			$sql .= "		CU.valor_cuota_moderadora,";
			$sql .= "		CU.valor_total_empresa, ";
			$sql .= "		CU.total_cuenta,";
			$sql .= "		GC.observacion, ";
			$sql .= "		GC.valor_glosa_copago, ";
			$sql .= "		GC.valor_glosa_cuota_moderadora, ";
			$sql .= "		GC.sw_glosa_total_cuenta, ";
			$sql .= "		GC.glosa_detalle_cuenta_id, ";
			$sql .= "		GC.valor_aceptado, ";
			$sql .= "		GC.valor_no_aceptado, ";
			$sql .= "		GM.motivo_glosa_descripcion ";
			$sql .= "FROM	ingresos IG,";
			$sql .= "		fac_facturas_cuentas FC,";
			$sql .= "		planes PL,";
			$sql .= "		pacientes PA, ";
			$sql .= "		glosas GL,";
			$sql .= "		cuentas CU,";
			$sql .= "		glosas_detalle_cuentas GC LEFT JOIN glosas_motivos GM ";
			$sql .= "		ON(GM.motivo_glosa_id = GC.motivo_glosa_id) ";
			$sql .= "WHERE 	GL.glosa_id = ".$glosa." ";
			$sql .= "AND	GL.empresa_id = '".$_SESSION['Auditoria']['empresa']."' ";
			$sql .= "AND 	CU.numerodecuenta =".$numero." ";
			$sql .= "AND	FC.prefijo = GL.prefijo ";
			$sql .= "AND	FC.factura_fiscal = GL.factura_fiscal ";
			$sql .= "AND	FC.empresa_id = GL.empresa_id ";
			$sql .= "AND	FC.numerodecuenta = CU.numerodecuenta ";
			$sql .= "AND 	CU.ingreso = IG.ingreso ";
			$sql .= "AND	IG.tipo_id_paciente = PA.tipo_id_paciente ";
			$sql .= "AND	IG.paciente_id = PA.paciente_id ";
			$sql .= "AND	CU.plan_id = PL.plan_id ";
			$sql .= "AND	CU.numerodecuenta = GC.numerodecuenta ";
			$sql .= "AND	GC.glosa_id = GL.glosa_id ";
			$sql .= "AND	GC.sw_estado NOT IN ('0','3') ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

			if (!$rst->EOF)
			{
				$this->PlanDescripcion = $rst->fields[0];
				$this->PlanId = $rst->fields[1];
				$this->IngresoNum = $rst->fields[2];
				$this->PacienteIdentificacion = $rst->fields[3];
				$this->PacienteNombre = $rst->fields[4];
				$this->CuentaCuotaPaciente = $rst->fields[5];
				$this->CuentaCuotaModeradora = $rst->fields[6];
				$this->CuentaValorEmpresa = $rst->fields[7];
				$this->CuentaValor = $rst->fields[8];
				$this->GlosaObservacion = $rst->fields[9];
				$this->GlosaValorCopago = $rst->fields[10];
				$this->GlosaValorCuota = $rst->fields[11];
				$this->GlosaSwTotalCuenta = $rst->fields[12];
				$this->GlosaCuentaId = $rst->fields[13];
				
				if(!$_REQUEST['valor_aceptado'] || !$_REQUEST['cuentaresponde'])
				{
					$this->VAceptadoC = $rst->fields[14];
					$this->VNoAceptadoC = $rst->fields[15];
				}
				
				$this->GlosaMotivoDescripcion = $rst->fields[16];
				$rst->MoveNext();
		    }
			$rst->Close();
			return true;
		}
		/************************************************************************************ 
		* Funcion donde se seleccionan el nombre de los terceros que son clientes y 
		* tienen envios radicados para que se pueda filtrar por ellos en una busqueda  
		* 
		* @return array datos de tipo_id_terceros 
		*************************************************************************************/
		function ObtenerNombresTerceros()
		{
			$empresa = $_SESSION['Auditoria']['empresa'];

			$sql  = "SELECT DISTINCT TE.nombre_tercero, ";
			$sql .= "		TE.tipo_id_tercero,";
			$sql .= "		TE.tercero_id ";
			$sql .= "FROM	terceros TE, ";
			$sql .= "		fac_facturas FF, ";
			$sql .= "       envios EN, ";
			$sql .= "       envios_detalle ED ";
			$sql .= "WHERE	TE.tipo_id_tercero = FF.tipo_id_tercero ";
			$sql .= "AND	TE.tercero_id = FF.tercero_id ";
			$sql .= "AND	FF.empresa_id = '".$empresa."' ";
			$sql .= "AND    FF.sw_clase_factura='1' ";
			$sql .= "AND    FF.estado <> '2' ";
			$sql .= "AND    FF.prefijo = ED.prefijo ";
			$sql .= "AND    FF.factura_fiscal = ED.factura_fiscal ";
			$sql .= "AND    FF.saldo > 0 ";
			$sql .= "AND    EN.sw_estado='1' ";
			$sql .= "AND    EN.fecha_radicacion IS NOT NULL ";
			$sql .= "AND    EN.envio_id = ED.envio_id ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				
			$i = 0;
			while (!$rst->EOF)
			{
				$documentos[$i] = $rst->fields[0]."*".$rst->fields[1]."/".$rst->fields[2];
				$rst->MoveNext();
				$i++;
		    }
			$rst->Close();
						
			return $documentos;
		}
		/************************************************************************************ 
		* Funcion donde se averiguan los auditores internos asociados al plan de la factura 
		* 
		* @return array datos de las clasificaciones de las glosas 
		*************************************************************************************/
		function ObtenerAuditoresInternos($op = null)
		{
			$sql  = "SELECT U.usuario_id,U.nombre ";
			$sql .= "FROM 	system_usuarios U, auditores_internos A ";
			$sql .= "WHERE 	U.usuario_id = A.usuario_id ";
			$sql .= "AND 		A.estado = '1' ";
			
			if(!$op)
				$sql .= "AND	 	A.usuario_id = ".UserGetUID()." ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			if(!$op)
			{
				(!$rst->EOF)? $datos = true:	$datos = false;
	   	}
	   	else
	   	{
				while(!$rst->EOF)
				{
					$this->Auditores[] = $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
		    }
	   	}
			$rst->Close();
	 		return $datos;
		}
		/************************************************************************************
		* Funcion que permite obtener el nombre de un tercero seleccionado
		*
		* @return string Nombre del tercero 
		*************************************************************************************/
		function ObtenerTercero($tercero)
		{
			$this->TerceroC = explode("/",$tercero);
			
			$sql .= "SELECT nombre_tercero ";
			$sql .= "FROM	terceros ";
			$sql .= "WHERE	tipo_id_tercero = '".$this->TerceroC[0]."' ";
			$sql .= "AND	tercero_id = '".$this->TerceroC[1]."' ";
						
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;	
			
			if(!$rst->EOF)
			{
				$nombre = $rst->fields[0];
				$rst->MoveNext();
			}
			$rst->Close();
			return $nombre;
		}
		/************************************************************************************ 
		* Funcion que permite traer la informacion de la glosa y el detalle del acta de 
		* conciliacion (si la hay) de las factura pertenecientes a un cliente
		* 
		* @return array datos de las facturas
		*************************************************************************************/
		function ObtenerFacturasGlosadas()
		{		
			$empresa = $_SESSION['Auditoria']['empresa'];
			$sql  = "SELECT FF.prefijo, ";
			$sql .= "				FF.factura_fiscal,";
			$sql .= "       SUM(GL.valor_glosa) AS valor_glosa, ";
			$sql .= "       SUM(GL.valor_aceptado) AS valor_aceptado, ";
			$sql .= "       SUM(GL.valor_no_aceptado) AS valor_no_aceptado, ";
			$sql .= "       SUM(GL.valor_pendiente)AS valor_pendiente, ";
			$sql .= "				FF.total_factura, ";
			$sql .= "				FF.sistema, ";
			$sql .= "				AD.acta_conciliacion_id ";
			$sql .= "FROM		glosas GL, ";
			$sql .= "				view_fac_facturas FF LEFT JOIN";			
			$sql .= "				(SELECT AD.empresa_id,";
			$sql .= "								AD.prefijo, ";
			$sql .= "								AD.factura_fiscal,";
			$sql .= "								AD.acta_conciliacion_id ";
			$sql .= "		 		 FROM		actas_conciliacion_glosas_detalle AD ,";
			$sql .= "								actas_conciliacion_glosas AC ";
			$sql .= "		 		 WHERE	AD.acta_conciliacion_id = AC.acta_conciliacion_id ";
			$sql .= "		 		 AND		AC.sw_activo = '0' ";
			$sql .= "		 		 AND		AC.acta_conciliacion_id = ".$this->ActaId." ";
			$sql .= "				) AS AD ";
			$sql .= "				ON( FF.empresa_id = AD.empresa_id AND";
			$sql .= "						FF.prefijo = AD.prefijo AND";
			$sql .= "						FF.factura_fiscal = AD.factura_fiscal ) ";
			$sql .= "WHERE 	FF.empresa_id = '".$empresa."' ";
			$sql .= "AND		GL.prefijo = FF.prefijo ";
			$sql .= "AND 		GL.factura_fiscal = FF.factura_fiscal ";	
			$sql .= "AND		GL.sw_estado IN ('1','2') ";
			$sql .= "AND		FF.tipo_id_tercero = '".$this->TerceroC[0]."' ";
			$sql .= "AND 		FF.tercero_id = '".$this->TerceroC[1]."' ";
			$sql .= "AND		valor_glosa > 0 ";
			$sql .= "GROUP BY 1,2,7,8,9 ";
			$sql .= "ORDER BY 1,2 ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;	
			
			while(!$rst->EOF)
			{
				$facturas[] = $rst->GetRowAssoc($ToUpper = false);
				
				$this->Totales['glosa'] += $rst->fields[2];
				$this->Totales['aceptado'] += $rst->fields[3];
				$this->Totales['no_aceptado'] += $rst->fields[4];
				$this->Totales['total_factura'] += $rst->fields[6];
				
				$rst->MoveNext();
			}
			$rst->Close();
			return $facturas;
		}
		/************************************************************************************
		* Funcion que permite obtener un listado conlas actas pendientes de los clientes
		* 
		* @return array datos de las actas
		*************************************************************************************/
		function ObtenerActasConciliacion()
		{
			$tercero = explode("/",$this->Tercero);
			
			$sql .= "SELECT	AC.acta_conciliacion_id, ";
			$sql .= "		AC.tipo_id_tercero, ";
			$sql .= "		AC.tercero_id, ";
			$sql .= "		AC.auditor_empresa, ";
			$sql .= "		TO_CHAR(AC.fecha_acta,'DD/MM/YYYY'), ";
			$sql .= "		TE.nombre_tercero ";
			$sql .= "FROM	actas_conciliacion_glosas AC,";
			$sql .= "		terceros TE ";
			$sql .= "WHERE	AC.auditor_id = ".UserGetUID()." ";
			$sql .= "AND	AC.sw_activo = '0' ";
			$sql .= "AND	AC.tipo_id_tercero = TE.tipo_id_tercero ";
			$sql .= "AND	AC.tercero_id = TE.tercero_id ";
			$sql .= "AND	TE.tipo_id_tercero = '".$tercero[0]."' ";
			$sql .= "AND	TE.tercero_id = '".$tercero[1]."' ";

			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;	
			
			$i = 0; 
			while(!$rst->EOF)
			{
				$actas[$i]  = $rst->fields[0]."*".$rst->fields[1]."*".$rst->fields[2]."*".$rst->fields[3];
				$actas[$i] .= "*".$rst->fields[4]."*".$rst->fields[5];
				
				$rst->MoveNext();
				$i++;
			}
			$rst->Close();
			return $actas;
		}
		/************************************************************************************
		* Funcion donde se obtiene la informacion de un acta de conciliacion
		* 
		* @params int numero de acta
		* @return boolean
		*************************************************************************************/
		function ObtenerInformacionActasConciliacion($acta_id)
		{
		
			$sql .= "SELECT	SU.nombre, ";
			$sql .= "		AC.auditor_empresa, ";
			$sql .= "		TO_CHAR(AC.fecha_acta,'DD /MM /YYYY'), ";
			$sql .= "		AC.observacion ";
			$sql .= "FROM	actas_conciliacion_glosas AC,";
			$sql .= "		terceros TE, ";
			$sql .= "		system_usuarios SU ";
			$sql .= "WHERE	AC.auditor_id = ".UserGetUID()." ";
			$sql .= "AND	AC.auditor_id = SU.usuario_id ";
			$sql .= "AND	AC.acta_conciliacion_id = ".$acta_id." ";
			$sql .= "AND	AC.tipo_id_tercero = TE.tipo_id_tercero ";
			$sql .= "AND	AC.tercero_id = TE.tercero_id ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;	
			
 
			if(!$rst->EOF)
			{
				$this->AuditorClinica = $rst->fields[0];
				$this->AuditorEmpresa = $rst->fields[1];
				$this->Fecha = $rst->fields[2];
				$this->ObservacionA = $rst->fields[3];
				
				$rst->MoveNext();
			}
			$rst->Close();
			return true;
		}
		/************************************************************************************
		* Funcion donde se obtiene la informacion de laglosa de un afactura y el detalle del 
		* acta de conciliacion si lo hay
		*
		* @return boolean
		*************************************************************************************/
		function ObtenerInformacionFactura()
		{
			$empresa = $_SESSION['Auditoria']['empresa'];
			
			$this->Factura = explode("/",$_REQUEST['factura']);
			$this->Tecero = explode("/",$_REQUEST['tercero']);
			
			$sql  = "SELECT GL.valor_glosa,";
			$sql .= "				GL.valor_aceptado,";
			$sql .= "				GL.valor_no_aceptado,";
			$sql .= "				GL.sw_glosa_total_factura, ";
			$sql .= "				GL.glosa_id, ";
			$sql .= "				AD.acta_conciliacion_id ";
			$sql .= "FROM		glosas GL, ";
			$sql .= "				view_fac_facturas FF LEFT JOIN";
			$sql .= "				(	SELECT 	AD.empresa_id,";
			$sql .= "									AD.prefijo, ";
			$sql .= "									AD.factura_fiscal,";
			$sql .= "									AD.acta_conciliacion_id ";
			$sql .= "		 			FROM		actas_conciliacion_glosas_detalle AD ,";
			$sql .= "									actas_conciliacion_glosas AC ";
			$sql .= "		 			WHERE		AD.acta_conciliacion_id = AC.acta_conciliacion_id ";
			$sql .= "		 			AND 		AC.sw_activo = '0') AS AD ";
			$sql .= "					ON( FF.empresa_id = AD.empresa_id AND";
			$sql .= "							FF.prefijo = AD.prefijo AND";
			$sql .= "							FF.factura_fiscal = AD.factura_fiscal )";
			$sql .= "WHERE 	FF.tercero_id = '".$this->Tecero[1]."' ";
			$sql .= "AND 		FF.tipo_id_tercero = '".$this->Tecero[0]."' ";
			$sql .= "AND 		FF.empresa_id = '".$empresa."' ";
			$sql .= "AND 		FF.prefijo = '".$this->Factura[0]."' ";
			$sql .= "AND 		FF.factura_fiscal = ".$this->Factura[1]." ";
			$sql .= "AND 		FF.prefijo = GL.prefijo ";
			$sql .= "AND 		FF.factura_fiscal = GL.factura_fiscal ";
			$sql .= "AND 		GL.sw_estado IN ('1','2') ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			if(!$rst->EOF)
			{
				$glosa = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
						
			$this->GlosaId = $glosa['glosa_id'];
			$this->GlosaValor = $glosa['valor_glosa'];
			$this->GlosaSwTotal = $glosa['sw_glosa_total_factura'];
			if(!$_REQUEST['valor_aceptado'])
			{
				$this->GlosaAceptado = $glosa['valor_aceptado'];
				$this->GlosaNoAceptado = $glosa['valor_no_aceptado'];
			}	
			if ($glosa['acta_conciliacion_id'])	$this->Arreglo['actanumero'] = $glosa['acta_conciliacion_id'];
			$this->ObtenerInformacionFacturaII();
			
			return true;
		}
		/********************************************************************************** 
		* Funcion donde se consulta la informacion de la factura 
		* 
		* @return boolean 
		***********************************************************************************/
		function ObtenerInformacionFacturaII()
		{
			$empresa_id = $_SESSION['Auditoria']['empresa'];
			$this->Sistema = $_SESSION['Auditoria']['sistema'] ;
			
			(empty($_REQUEST['sistema']))? $this->Sistema = $_SESSION['Auditoria']['sistema']: $this->Sistema = $_REQUEST['sistema'];
			
			$_SESSION['Auditoria']['sistema'] = $this->Sistema;
			if($this->FFiscal)
			{
				$this->Factura[1] = $this->FFiscal;
				$this->Factura[0] = $this->FPrefijo;
				$this->FacturaNumero = $this->FPrefijo." ".$this->FFiscal;
			}
			
			switch($this->Sistema)
			{
				case "EXT":
					$sql  = "SELECT F.tipo_id_tercero,";
					$sql .= "				F.tercero_id,";
					$sql .= "				F.saldo, ";
					$sql .= "				F.total_factura,";
					$sql .= "				TO_CHAR(F.fecha_registro,'DD/MM/YYYY') AS fecha_registro ";
					$sql .= "FROM 	facturas_externas F ";
					$sql .= "WHERE 	F.empresa_id = '".$empresa_id."' "; 
					$sql .= "AND 		F.prefijo = '".$this->Factura[0]."' ";
					$sql .= "AND 		F.factura_fiscal = ".$this->Factura[1]." ";
				break;
				case "SIIS":
					$sql  = "SELECT F.tipo_id_tercero,";
					$sql .= "				F.tercero_id,";
					$sql .= "				F.total_factura,";
					$sql .= "				TO_CHAR(F.fecha_registro,'DD/MM/YYYY') AS fecha_registro,";
					$sql .= "				P.num_contrato,"; 
					$sql .= "				P.plan_descripcion,";
					$sql .= "				P.plan_id ";
					$sql .= "FROM 	fac_facturas F,";
					$sql .= "	  		planes P ";
					$sql .= "WHERE 	F.empresa_id = '".$empresa_id."' "; 
					$sql .= "AND 		F.prefijo = '".$this->Factura[0]."' ";
					$sql .= "AND 		F.factura_fiscal = ".$this->Factura[1]." ";
					$sql .= "AND 		F.sw_clase_factura = '1' ";
					$sql .= "AND 		F.plan_id = P.plan_id ";
					$sql .= "AND 		F.empresa_id = P.empresa_id ";
				break;
			}			

			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			if(!$rst->EOF)
			{
				$factura = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			$this->PlanId = $factura['plan_id'];
			$this->FacturaTotal =$factura['total_factura'];
			$this->SaldoFactura = $factura['saldo'];
			$this->FacturaRegistro = $factura['fecha_registro'];
			$this->PlanNumContrato = $factura['num_contrato'];
			$this->PlanDescripcion = $factura['plan_descripcion'];

			if($this->FechaGlosamiento == "")	$this->FechaGlosamiento = date("d/m/Y");
			
			return true;
		}
		/************************************************************************************
		* Funcion donde se obtiene la informacion de la glosa de una cuenta y el detalle del 
		* acta de conciliacion si lo hay
		* 
		* @params int numero de cuenta
		* @return boolean
		*************************************************************************************/
		function ObtenerInformacionGlosaCuenta($numero = null)
		{	
			$empresa = $_SESSION['Auditoria']['empresa'];
			
			$sql  = "SELECT PL.plan_descripcion,";
			$sql .= "		IG.ingreso,";
			$sql .= "		PA.tipo_id_paciente,";
			$sql .= "		PA.paciente_id,";
			$sql .= "		PA.primer_nombre||' '||PA.segundo_nombre||' '||PA.primer_apellido||' '||PA.segundo_apellido,";
			$sql .= "		GC.valor_glosa_copago, ";
			$sql .= "		GC.valor_glosa_cuota_moderadora, ";
			$sql .= "		GC.sw_glosa_total_cuenta, ";
			$sql .= "		GC.valor_aceptado, ";
			$sql .= "		GC.valor_no_aceptado, ";
			$sql .= "		GC.numerodecuenta, ";
			$sql .= "		GC.glosa_detalle_cuenta_id ";
			$sql .= "FROM	ingresos IG,";
			$sql .= "		planes PL,";
			$sql .= "		pacientes PA, ";
			$sql .= "		glosas GL,";
			$sql .= "		glosas_detalle_cuentas GC,";
			$sql .= "		cuentas CU,";
			$sql .= "		fac_facturas_cuentas FC ";
			$sql .= "WHERE 	GL.glosa_id = ".$this->GlosaId." ";
			$sql .= "AND	GL.empresa_id = '".$empresa."' ";
			$sql .= "AND	FC.prefijo = GL.prefijo ";
			$sql .= "AND	FC.factura_fiscal = GL.factura_fiscal ";
			$sql .= "AND	FC.empresa_id = GL.empresa_id ";
			$sql .= "AND	FC.numerodecuenta = CU.numerodecuenta ";
			$sql .= "AND 	CU.ingreso = IG.ingreso ";
			$sql .= "AND	IG.tipo_id_paciente = PA.tipo_id_paciente ";
			$sql .= "AND	IG.paciente_id = PA.paciente_id ";
			$sql .= "AND	CU.plan_id = PL.plan_id ";
			$sql .= "AND	CU.numerodecuenta = GC.numerodecuenta ";
			$sql .= "AND	GC.glosa_id = GL.glosa_id ";
			$sql .= "AND	GC.sw_estado NOT IN ('0','3') ";
			
			if($numero != null)
				$sql .= "AND 	CU.numerodecuenta =".$numero." ";
				
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

			if (!$rst->EOF)
			{
				$this->PlanDescripcion = $rst->fields[0];
				$this->Ingreso = $rst->fields[1];
				$this->PacienteTipoId = $rst->fields[2];
				$this->PacienteId = $rst->fields[3];
				$this->PacienteNombre = $rst->fields[4];
				$this->GlosaValor = $rst->fields[5] + $rst->fields[6];
				$this->GlosaSwTotalCuenta = $rst->fields[7];
				if(!$_REQUEST['valor_aceptado'] || !$_REQUEST['cuentaconcilia'])
				{
					$this->GlosaValorAceptado = $rst->fields[8];
					$this->GlosaValorNoAceptado = $rst->fields[9];
				}
				$this->NumeroCuenta = $rst->fields[10];
				$this->GlosaCuentaId = $rst->fields[11]; 
												
				($rst->fields[13] == "")? $this->actualizar = "0":$this->actualizar = "1";

				$rst->MoveNext();
		    }
			$rst->Close();
			return true;
		}
		/************************************************************************************
		* Funcion donde se obtiene la informacion de la glosa de los cargos y el detalle del 
		* acta de conciliacion si lo hay
		* 
		* @params int numero de cuenta
		* @return array datos de los cargos
		*************************************************************************************/
		function ObtenerInformacionGlosaCargos($cuenta)
		{
			$empresa = $_SESSION['Auditoria']['empresa'];
			
			$sql .= "SELECT	TO_CHAR(CD.fecha_registro,'DD/MM/YYYY') AS fecha,";
			$sql .= "		CD.transaccion, ";
			$sql .= "		CD.cargo_cups, ";
			$sql .= "		TD.tarifario_id, ";
			$sql .= "		TD.descripcion, ";
			$sql .= "		COALESCE(GC.valor_glosa,'0') AS glosa, ";
			$sql .= "		COALESCE(GC.valor_aceptado,'0') AS aceptado,"; 
			$sql .= "		COALESCE(GC.valor_no_aceptado,'0') AS no_aceptado,"; 
			$sql .= "		GC.glosa_detalle_cargo_id, ";
			$sql .= "		GC.codigo_concepto_general, ";
			$sql .= "		GC.codigo_concepto_especifico, ";
			$sql .= "		GG.descripcion_concepto_general, ";
			$sql .= "		GE.descripcion_concepto_especifico ";
			$sql .= "FROM 	tarifarios_detalle TD, ";
			$sql .= "		cuentas_detalle CD LEFT JOIN glosas_detalle_cargos GC ";
			$sql .= "		ON(	CD.transaccion = GC.transaccion AND ";
			$sql .= "			GC.sw_estado NOT IN ('0','3') AND ";
			$sql .= "			GC.glosa_id = ".$this->GlosaId." AND ";
			$sql .= "			GC.glosa_detalle_cuenta_id = ".$this->GlosaCuentaId.") ";
			$sql .= "		LEFT JOIN glosas_concepto_general GG ON (GG.codigo_concepto_general = GC.codigo_concepto_general ) ";
			$sql .= "		LEFT JOIN glosas_concepto_especifico GE ON (GE.codigo_concepto_especifico = GC.codigo_concepto_especifico ) ";
			$sql .= "WHERE 	CD.numerodecuenta = ".$cuenta." ";
			$sql .= "AND 	CD.empresa_id = '".$empresa."'  ";
			$sql .= "AND 	CD.facturado = '1' ";
			$sql .= "AND 	CD.tarifario_id != 'SYS' "; 
			$sql .= "AND 	TD.cargo = CD.cargo ";
			$sql .= "AND 	TD.tarifario_id = CD.tarifario_id ";
			$sql .= "AND 	CD.valor_cargo >= 0 ";
			$sql .= "ORDER BY 1,2 ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				
			$i = 0;
			$this->j = 0;
			$this->VGlosa = 0;
			while (!$rst->EOF)
			{
				if($rst->fields[5] == 0) $rst->fields[8] = null;
				
				$datos[$i] = $rst->GetRowAssoc($ToUpper = false);
				
				$this->VGlosa += $rst->fields[5];
				
				if($rst->fields[8] && (!$_REQUEST['valor_aceptado']
					 || !$_REQUEST['cargoconcilia']))
				{
					
					$this->VAceptado[$this->j] = $rst->fields[6];
					$this->VNoAceptado[$this->j] = $rst->fields[7];
					$this->j++;
				}
				$rst->MoveNext();
				$i++;
		  }
			$rst->Close();
			return $datos;
		}
		/************************************************************************************
		* Funcion donde se obtiene la informacion de la glosa de los insumos y el detalle del 
		* acta de conciliacion si lo hay
		* 
		* @params int numero de cuenta
		* @return array datos de los insumos
		*************************************************************************************/
		function ObtenerInformacionGlosaInsumos($cuenta)
		{
			$empresa = $_SESSION['Auditoria']['empresa'];
			
			$sql  = "SELECT	IR.codigo_producto, "; 
			$sql .= "				SUM(CD.cantidad),";
			$sql .= "				IR.descripcion, ";
			$sql .= "				COALESCE(GI.valor_glosa,0), ";
			$sql .= "				COALESCE(GI.valor_aceptado,0), ";
			$sql .= "				COALESCE(GI.valor_no_aceptado,0), ";
			$sql .= "				GI.glosa_detalle_inventario_id, ";
			$sql .= "				CD.transaccion, ";
			$sql .= "		GG.descripcion_concepto_general, ";
			$sql .= "		GE.descripcion_concepto_especifico ";
			$sql .= "FROM	bodegas_documentos_d BD,";
			$sql .= "			cuentas_detalle CD,  ";
			$sql .= "			inventarios_productos IR ";
			$sql .= "			LEFT JOIN glosas_detalle_inventarios GI ";
			$sql .= "	    ON(	IR.codigo_producto = GI.codigo_producto AND ";
			$sql .= "		   		GI.sw_estado NOT IN ('0','3') AND ";
			$sql .= "		   		GI.glosa_id = ".$this->GlosaId." AND ";
			$sql .= "		   		GI.glosa_detalle_cuenta_id = ".$this->GlosaCuentaId.") ";			
			$sql .= "		LEFT JOIN glosas_concepto_general GG ON (GG.codigo_concepto_general = GI.codigo_concepto_general ) ";
			$sql .= "		LEFT JOIN glosas_concepto_especifico GE ON (GE.codigo_concepto_especifico = GI.codigo_concepto_especifico ) ";
			$sql .= "WHERE 	CD.numerodecuenta = ".$cuenta." ";
			$sql .= "AND	CD.facturado = '1' ";
			$sql .= "AND	CD.empresa_id = '".$empresa."' ";
			$sql .= "AND	CD.valor_cargo >= 0 ";
			$sql .= "AND	CD.consecutivo = BD.consecutivo ";
			$sql .= "AND	BD.codigo_producto = IR.codigo_producto ";

			$sql .= "GROUP BY 1,3,4,5,6,7,8,9,10 ";
			$sql .= "ORDER BY 1 ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				
			$i = 0;
			while (!$rst->EOF)
			{
				if($rst->fields[3] == 0) $rst->fields[6] = null; 
				
				$datos[$i]  = $rst->fields[0]."*".$rst->fields[1]."*".$rst->fields[2]."*".$rst->fields[3];
				$datos[$i] .= "*".$rst->fields[4]."*".$rst->fields[5]."*".$rst->fields[6]."*".$rst->fields[7]."*".$rst->fields[8]."*".$rst->fields[9];
				
				if($rst->fields[6]  && (!$_REQUEST['valor_aceptado']
					 || !$_REQUEST['cargoconcilia']))
				{
					$this->VAceptado[$this->j] = $rst->fields[4];
					$this->VNoAceptado[$this->j] = $rst->fields[5];
					$this->j++;
				}
				
				$this->VGlosa += $rst->fields[3];
				
				$rst->MoveNext();
				$i++;
		  }
			$rst->Close();
			return $datos;
		}
		/************************************************************************************
		* Funcion donde se obtiene el listado de cuentas de la factura y el detalle del 
		* acta de conciliacion si lo hay
		* 
		* @return array datos de las cuentas
		*************************************************************************************/
		function ObtenerInformacionCuentas()
		{	
			$empresa = $_SESSION['Auditoria']['empresa'];
			
			$sql  = "SELECT CU.numerodecuenta,";
			$sql .= "		PA.tipo_id_paciente,";
			$sql .= "		PA.paciente_id,";
			$sql .= "		PA.primer_nombre||' '||PA.segundo_nombre||' '||PA.primer_apellido||' '||PA.segundo_apellido,";
			$sql .= "		PL.plan_descripcion ";
			
			$where .= "FROM		glosas GL,";
			$where .= "				planes PL,";
			$where .= "				cuentas CU,";
			$where .= "				ingresos IG, ";
			$where .= "				pacientes PA,";
			$where .= "				glosas_detalle_cuentas GC, ";
			$where .= "				fac_facturas_cuentas FC ";
			$where .= "WHERE 	GL.glosa_id = ".$this->GlosaId." ";
			$where .= "AND		GL.empresa_id = '".$empresa."' ";
			$where .= "AND 		FC.prefijo = GL.prefijo ";
			$where .= "AND 		FC.factura_fiscal = GL.factura_fiscal ";
			$where .= "AND 		FC.empresa_id = GL.empresa_id ";
			$where .= "AND 		CU.numerodecuenta = FC.numerodecuenta ";
			$where .= "AND 		CU.ingreso = IG.ingreso ";
			$where .= "AND 		IG.tipo_id_paciente = PA.tipo_id_paciente ";
			$where .= "AND 		IG.paciente_id = PA.paciente_id ";
			$where .= "AND 		CU.plan_id = PL.plan_id ";
			$where .= "AND		GC.numerodecuenta = CU.numerodecuenta ";
			$where .= "AND		GC.sw_estado NOT IN ('0','3') ";
			$where .= "AND		GC.glosa_id = GL.glosa_id ";
			
			$sqlCon  = "SELECT COUNT(*) ".$where;
			$this->ProcesarSqlConteo($sqlCon,25);

			$sql .= $where;	
			$sql .= "ORDER BY 1 ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

			$i = 0;
			while (!$rst->EOF)
			{
				$datos[$i]  = $rst->fields[0]."*".$rst->fields[1]." ".$rst->fields[2]."*".$rst->fields[3];
				$datos[$i] .= "*".$rst->fields[4]."*".$rst->fields[5];
				$rst->MoveNext();
				$i++;
		    }
			$rst->Close();
			return $datos;
		}
		/************************************************************************************
		* Funcion donde se obtiene un listado con los terceros a los cuales se les puede 
		* crear un acta de conciliacion
		*
		* @return array datos de los terceros
		*************************************************************************************/
		function ObtenerTerceros()
		{
			$empresa = $_SESSION['Auditoria']['empresa'];
			
			$sql  = "SELECT TE.nombre_tercero, ";
			$sql .= "				TE.tipo_id_tercero,";
			$sql .= "				TE.tercero_id ";
			$where .= "FROM	terceros TE, ";
			$where .= "			(SELECT	DISTINCT FF.tipo_id_tercero, ";
      $where .= "		 					FF.tercero_id  ";
      $where .= "		 	 FROM 	fac_facturas FF, ";
			$where .= "       			envios EN, ";
			$where .= "      	 			envios_detalle ED, ";
			$where .= "							glosas GL ";            
			$where .= "		 	 WHERE 	FF.empresa_id = '".$empresa."'  ";
			$where .= "		 	 AND    FF.sw_clase_factura='1' ";
			$where .= "		 	 AND    FF.estado <> '2' ";
			$where .= "		   AND    FF.prefijo = ED.prefijo ";
			$where .= "		 	 AND    FF.factura_fiscal = ED.factura_fiscal ";
			$where .= "		 	 AND    FF.prefijo = GL.prefijo ";
			$where .= "		 	 AND    FF.factura_fiscal = GL.factura_fiscal ";
			$where .= "		 	 AND    FF.saldo > 0 ";
			$where .= "		 	 AND    EN.sw_estado='1' ";
			$where .= "		 	 AND    GL.sw_estado IN ('1','2') ";
			$where .= "		 	 AND    EN.fecha_radicacion IS NOT NULL ";
			$where .= "		 	 AND    EN.envio_id = ED.envio_id ";
			$where .= "			 AND		valor_glosa > 0 ";
			$where .= "		 	 UNION DISTINCT ";
			$where .= "		 	 SELECT DISTINCT FF.tipo_id_tercero, ";
			$where .= "		 					FF.tercero_id ";
			$where .= "		 	 FROM		facturas_externas FF, ";
			$where .= "							glosas GL "; 
			$where .= "		 	 WHERE 	FF.empresa_id = '".$empresa."'  ";
			$where .= "		 	 AND 		FF.estado <> '2' ";
			$where .= "		 	 AND    FF.prefijo = GL.prefijo ";
			$where .= "		 	 AND    GL.sw_estado IN ('1','2') ";
			$where .= "		 	 AND    FF.factura_fiscal = GL.factura_fiscal ";
			$where .= "			) AS FF ";
			$where .= "WHERE TE.tipo_id_tercero = FF.tipo_id_tercero ";
			$where .= "AND	 TE.tercero_id = FF.tercero_id ";
			
			if($this->TerceroNombre != "")
			{
				$where .= "AND	 TE.nombre_tercero ILIKE '%".$this->TerceroNombre."%' ";
			}
			if($this->TerceroTipoId != "0" && $this->TerceroTipoId != "")
			{
				$where .= "AND	 TE.tipo_id_tercero = '".$this->TerceroTipoId."' ";	
			}
			if($this->TerceroDocumento !="")
			{
				$where .= "AND	 TE.tercero_id = '".$this->TerceroDocumento."' ";
			}
			
			$sqlCon  = "SELECT COUNT(*) ".$where;
			if(!$this->ProcesarSqlConteo($sqlCon))
				return false;

			$sql .= $where;	
			$sql .= "ORDER BY 1 ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$i=0;
			while (!$rst->EOF)
			{
				$datos[$i]  = $rst->fields[0]."*".$rst->fields[1]."*".$rst->fields[2];
				$rst->MoveNext();
				$i++;
		    }
			$rst->Close();
			return $datos;
		}
		/************************************************************************************
		* Funcion que permite mostrar la interfaz para agregar observaciones a la aceptacion 
		* o conciliacion de una glosa
		*
		* @return boolean
		*************************************************************************************/
		function IngresarObservacionGlosa()
		{
			$this->GlosaId = $_REQUEST['glosa_id'];
			
			$this->Arreglo = array("menu"=>$_REQUEST['menu'],"glosa_id"=>$_REQUEST['glosa_id'],
								   "acta_id"=>$_REQUEST['acta_id'],"auditoria"=>$_REQUEST['auditoria']);
			$this->action2 = ModuloGetURL('app','AuditoriaCuentas','user','IngresarObservacionGlosaBD',$this->Arreglo);
			$this->action1 = ModuloGetURL('app','AuditoriaCuentas','user','IngresarObservacionGlosa',$this->Arreglo);
			
			$this->metodo = "AgregarObservacionActaGlosaBD";
			
			$this->Conciliacion = $_REQUEST['auditoria'];
			$this->Datos = $this->ObtenerObservacionesGlosas();
			$this->FormaIngresarObservacion();
			return true;
		}
		/************************************************************************************
		* Funcion que permite mostrar la interfaz para agregar observaciones a la aceptacion 
		* o conciliacion de una cuenta
		*
		* @return boolean
		*************************************************************************************/
		function IngresarObservacionGlosaCuenta()
		{
			$this->GlosaId = $_REQUEST['glosa_id'];
			$this->GlosaCuenta = $_REQUEST['glosa_id_cuenta'];
			$this->Conciliacion = $_REQUEST['auditoria'];
			
			$this->metodo = "AgregarObservacionActaGlosaCuentaBD";
			
			$this->Arreglo = array("auditoria"=>$_REQUEST['auditoria'],"glosa_id"=>$_REQUEST['glosa_id'],
								   "glosa_id_cuenta"=>$_REQUEST['glosa_id_cuenta'],"acta_id"=>$_REQUEST['acta_id']);
			$this->action2 = ModuloGetURL('app','AuditoriaCuentas','user','IngresarObservacionGlosaCuentaBD',$this->Arreglo);
			$this->action1 = ModuloGetURL('app','AuditoriaCuentas','user','IngresarObservacionGlosaCuenta',$this->Arreglo);
			$this->Datos = $this->ObtenerObservacionesGlosasCuentas();
			$this->FormaIngresarObservacion();
			
			return true;
		}
		/************************************************************************************
		* Funcion que permite mostrar la interfaz para agregar observaciones a la aceptacion 
		* o conciliacion de un cargo
		*
		* @return boolean
		*************************************************************************************/
		function IngresarObservacionGlosaCargo()
		{
			$this->GlosaId = $_REQUEST['glosa_id'];
			$this->GlosaCuenta = $_REQUEST['glosa_id_cuenta'];
			$this->GlosaCargo = $_REQUEST['glosa_detalle_id'];
			$this->Conciliacion = $_REQUEST['auditoria'];
			
			$this->metodo = "AgregarObservacionActaGlosaCargoBD";
			
			$this->Arreglo = array("auditoria"=>$_REQUEST['auditoria'],"glosa_id"=>$_REQUEST['glosa_id'],"acta_id"=>$_REQUEST['acta_id'],
							 	   "glosa_id_cuenta"=>$_REQUEST['glosa_id_cuenta'],"glosa_detalle_id"=>$_REQUEST['glosa_detalle_id']);
			
			$this->action2 = ModuloGetURL('app','AuditoriaCuentas','user','IngresarObservacionGlosaCargoBD',$this->Arreglo);
			$this->action1 = ModuloGetURL('app','AuditoriaCuentas','user','IngresarObservacionGlosaCargo',$this->Arreglo);
			$this->Datos = $this->ObtenerObservacionesGlosasCargos();
			$this->FormaIngresarObservacion();
			
			return true;
		}
		/************************************************************************************
		* Funcion que permite mostrar la interfaz para agregar observaciones a la aceptacion 
		* o conciliacion de un insumo
		*
		* @return boolean
		*************************************************************************************/
		function IngresarObservacionGlosaInsumo()
		{
			$this->GlosaId = $_REQUEST['glosa_id'];
			$this->GlosaCuenta = $_REQUEST['glosa_id_cuenta'];
			$this->GlosaInsumo = $_REQUEST['glosa_detalle_id'];
			$this->Conciliacion = $_REQUEST['auditoria'];
			
			$this->metodo = "AgregarObservacionActaGlosaInsumoBD";
			
			$this->Arreglo = array("auditoria"=>$_REQUEST['auditoria'],"glosa_id"=>$_REQUEST['glosa_id'],"acta_id"=>$_REQUEST['acta_id'],
							 	   "glosa_id_cuenta"=>$_REQUEST['glosa_id_cuenta'],"glosa_detalle_id"=>$_REQUEST['glosa_detalle_id']);
			
			$this->action2 = ModuloGetURL('app','AuditoriaCuentas','user','IngresarObservacionGlosaInsumoBD',$this->Arreglo);
			$this->action1 = ModuloGetURL('app','AuditoriaCuentas','user','IngresarObservacionGlosaInsumo',$this->Arreglo);
			$this->Datos = $this->ObtenerObservacionesGlosasInsumos();
			$this->FormaIngresarObservacion();
			
			return true;
		}		
		/************************************************************************************
		* Funcion mediante la cual se consulta en la base de datos las observaciones que se 
		* la han asignado a una glosa en general, cuando se acepto una glosa o se concilio
		*
		* @return boolean
		*************************************************************************************/
		function ObtenerObservacionesGlosas()
		{
			$sql .= "SELECT RG.observacion,";
			$sql .= "		TO_CHAR(RG.fecha_registro,'DD / MM / YYYY'),";
			$sql .= "		SU.nombre, ";
			$sql .= "		RG.acta_conciliacion_id, ";
			$sql .= "		RG.respuesta_glosa_id ";
			$where .= "FROM  respuesta_glosas RG, ";
			$where .= "		 system_usuarios SU ";
			$where .= "WHERE RG.glosa_id = ".$this->GlosaId." ";
			$where .= "AND	 RG.usuario_id = SU.usuario_id ";
			
			$sqlCon  = "SELECT COUNT(*) ".$where;
			if(!$this->ProcesarSqlConteo($sqlCon))
				return false;
			
			$sql .= $where;
			$sql .= "ORDER BY 2,1";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			$i=0;
			while (!$rst->EOF)
			{
				$datos[$i]  = $rst->fields[0]."*".$rst->fields[1]."*".$rst->fields[2]."*".$rst->fields[3]."*".$rst->fields[4];
				$rst->MoveNext();
				$i++;
		    }
			
			$rst->Close();
			return $datos;
		}
		/************************************************************************************
		* Funcion mediante la cual se consulta en la base de datos las observaciones que se 
		* la han asignado a una cuenta, cuando se acepto una glosa o se concilio
		* de acuerdo a la glosa a la que pertenece
		*
		* @return boolean
		*************************************************************************************/
		function ObtenerObservacionesGlosasCuentas()
		{
			$sql .= "SELECT RG.observacion,";
			$sql .= "		TO_CHAR(RG.fecha_registro,'DD / MM / YYYY'),";
			$sql .= "		SU.nombre, ";
			$sql .= "		RG.acta_conciliacion_id, ";
			$sql .= "		RG.respuesta_glosas_detalle_cuenta_id ";
			$where .= "FROM		respuesta_glosas_detalle_cuenta RG, ";
			$where .= "		 		system_usuarios SU ";
			$where .= "WHERE RG.glosa_id = ".$this->GlosaId." ";
			$where .= "AND	 RG.usuario_id = SU.usuario_id ";
			$where .= "AND	 RG.glosa_detalle_cuenta_id = ".$this->GlosaCuenta." ";
			
			$sqlCon  = "SELECT COUNT(*) ".$where;
			if(!$this->ProcesarSqlConteo($sqlCon))
				return false;
			
			$sql .= $where;
			$sql .= "ORDER BY 2,1";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			$i=0;
			while (!$rst->EOF)
			{
				$datos[$i]  = $rst->fields[0]."*".$rst->fields[1]."*".$rst->fields[2]."*".$rst->fields[3]."*".$rst->fields[4];
				$rst->MoveNext();
				$i++;
		    }
			
			$rst->Close();
			return $datos;
		}
		/************************************************************************************
		* Funcion mediante la cual se consulta en la base de datos las observaciones que se 
		* la han asignado a un cargo, cuando se acepto una glosa o se concilio
		* de acuerdo a la cuenta y a la glosa a la que pertenece
		*
		* @return boolean
		*************************************************************************************/
		function ObtenerObservacionesGlosasCargos()
		{
			$sql .= "SELECT RG.observacion,";
			$sql .= "		TO_CHAR(RG.fecha_registro,'DD / MM / YYYY'),";
			$sql .= "		SU.nombre, ";
			$sql .= "		RG.acta_conciliacion_id, ";
			$sql .= "		RG.respuesta_glosa_detalle_cargo_id ";
			
			$where .= "FROM  respuesta_glosas_detalle_cargos RG, ";
			$where .= "		 system_usuarios SU ";
			$where .= "WHERE RG.glosa_id = ".$this->GlosaId." ";
			$where .= "AND	 RG.usuario_id = SU.usuario_id ";
			$where .= "AND	 RG.glosa_detalle_cuenta_id = ".$this->GlosaCuenta." ";
			$where .= "AND	 RG.glosa_detalle_cargo_id = ".$this->GlosaCargo." ";
			
			$sqlCon  = "SELECT COUNT(*) ".$where;
			if(!$this->ProcesarSqlConteo($sqlCon))
				return false;
			
			$sql .= $where;
			$sql .= "ORDER BY 2";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			$i=0;
			while (!$rst->EOF)
			{
				$datos[$i]  = $rst->fields[0]."*".$rst->fields[1]."*".$rst->fields[2]."*".$rst->fields[3]."*".$rst->fields[4];
				$rst->MoveNext();
				$i++;
		    }
			
			$rst->Close();
			return $datos;
		}
		/************************************************************************************
		* Funcion mediante la cual se consulta en la base de datos las observaciones que se 
		* la han asignado a un insumo o medicamento, cuando se acepto una glosa o se concilio
		* de acuerdo a la cuenta y a la glosa a la que pertenece
		*
		* @return boolean
		*************************************************************************************/
		function ObtenerObservacionesGlosasInsumos()
		{
			$sql .= "SELECT RG.observacion,";
			$sql .= "		TO_CHAR(RG.fecha_registro,'DD / MM / YYYY'),";
			$sql .= "		SU.nombre, ";
			$sql .= "		RG.acta_conciliacion_id, ";
			$sql .= "		RG.respuesta_glosa_detalle_inventario_id ";
			
			$where .= "FROM  respuesta_glosas_detalle_inventarios RG, ";
			$where .= "		 system_usuarios SU ";
			$where .= "WHERE RG.glosa_id = ".$this->GlosaId." ";
			$where .= "AND	 RG.usuario_id = SU.usuario_id ";
			$where .= "AND	 RG.glosa_detalle_cuenta_id = ".$this->GlosaCuenta." ";
			$where .= "AND	 RG.glosa_detalle_inventario_id = ".$this->GlosaInsumo." ";
			
			$sqlCon  = "SELECT COUNT(*) ".$where;
			if(!$this->ProcesarSqlConteo($sqlCon))
				return false;
			
			$sql .= $where;
			$sql .= "ORDER BY 2";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			$i=0;
			while (!$rst->EOF)
			{
				$datos[$i]  = $rst->fields[0]."*".$rst->fields[1]."*".$rst->fields[2]."*".$rst->fields[3]."*".$rst->fields[4];
				$rst->MoveNext();
				$i++;
		    }
			
			$rst->Close();
			return $datos;
		}
		/************************************************************************************
		* Funcion mediante la cual se ingresa una observacion, de una glosa cuando se esta
		* aceptado, sin incluir en un acta de conciliacion y cunado se esta conciliando
		*
		* @return boolean
		*************************************************************************************/
		function IngresarObservacionGlosaBD()
		{
			$this->Observacion = $_REQUEST['observacion'];
			if($this->Observacion != "")
			{	
				$this->ActaId = $_REQUEST['acta_id'];
				$this->GlosaId = $_REQUEST['glosa_id'];
				$usuario = UserGetUID();
				
				$sql .= "INSERT INTO  respuesta_glosas";
				$sql .= "		( ";
				$sql .= "		observacion,";
				$sql .= "		glosa_id,";
				$sql .= "		usuario_id,";
				
				if($this->ActaId)
					$sql .= "		acta_conciliacion_id, ";
					
				$sql .= "		fecha_registro";
				$sql .= "		) ";
				$sql .= "VALUES (";
				$sql .= "		'".$this->Observacion."',";
				$sql .= "		 ".$this->GlosaId.",";
				$sql .= "		 ".$usuario.",";
				
				if($this->ActaId)
					$sql .= "		".$this->ActaId.", ";
					
				$sql .= "		   NOW()";
				$sql .= "		) ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			}
			
			$this->IngresarObservacionGlosa();
			return true;
		}
		/************************************************************************************
		* Funcion mediante la cual se ingresa una observacion, de una cuenta cuando se esta
		* aceptado, sin incluir en un acta de conciliacion y cunado se esta conciliando
		*
		* @return boolean
		*************************************************************************************/
		function IngresarObservacionGlosaCuentaBD()
		{
			$this->Observacion = $_REQUEST['observacion'];
			if($this->Observacion != "")
			{	
				$this->ActaId = $_REQUEST['acta_id'];
				$this->GlosaId = $_REQUEST['glosa_id'];
				$this->GlosaCuenta = $_REQUEST['glosa_id_cuenta'];
				$usuario = UserGetUID();
				
				$sql .= "INSERT INTO  respuesta_glosas_detalle_cuenta";
				$sql .= "		( ";
				$sql .= "		observacion,";
				$sql .= "		glosa_id,";
				$sql .= "		glosa_detalle_cuenta_id,";
				$sql .= "		usuario_id,";
				
				if($this->ActaId)
					$sql .= "		acta_conciliacion_id, ";
									
				$sql .= "		fecha_registro";
				$sql .= "		) ";
				$sql .= "VALUES (";
				$sql .= "		'".$this->Observacion."',";
				$sql .= "		 ".$this->GlosaId.",";
				$sql .= "		 ".$this->GlosaCuenta.",";
				$sql .= "		 ".$usuario.",";
				
				if($this->ActaId)
					$sql .= "		".$this->ActaId.", ";
				
				$sql .= "		   NOW()";
				$sql .= "		) ";
			
				if(!$rst = $this->ConexionBaseDatos($sql))
					return false;
			}
			
			$this->IngresarObservacionGlosaCuenta();
			return true;
		}
		/************************************************************************************
		* Funcion mediante la cual se ingresa una observacion, de un cargo cuando se esta
		* aceptado, sin incluir en un acta de conciliacion y cunado se esta conciliando
		*
		* @return boolean
		*************************************************************************************/
		function IngresarObservacionGlosaCargoBD()
		{
			$this->Observacion = $_REQUEST['observacion'];
			if($this->Observacion != "")
			{	
				$this->ActaId = $_REQUEST['acta_id'];
				$this->GlosaId = $_REQUEST['glosa_id'];
				$this->GlosaCuenta = $_REQUEST['glosa_id_cuenta'];
				$this->GlosaCargo = $_REQUEST['glosa_detalle_id'];
				
				$usuario = UserGetUID();
				
				$sql .= "INSERT INTO  respuesta_glosas_detalle_cargos";
				$sql .= "		( ";
				$sql .= "		observacion,";
				$sql .= "		glosa_id,";
				$sql .= "		glosa_detalle_cuenta_id,";
				$sql .= "		glosa_detalle_cargo_id,";
				$sql .= "		usuario_id,";
				
				if($this->ActaId)
					$sql .= "		acta_conciliacion_id, ";

				$sql .= "		fecha_registro";
				$sql .= "		) ";
				$sql .= "VALUES (";
				$sql .= "		'".$this->Observacion."',";
				$sql .= "		 ".$this->GlosaId.",";
				$sql .= "		 ".$this->GlosaCuenta.",";
				$sql .= "		 ".$this->GlosaCargo.",";
				$sql .= "		 ".$usuario.",";
				
				if($this->ActaId)
					$sql .= "		".$this->ActaId.", ";
				
				$sql .= "		   NOW()";
				$sql .= "		) ";
				
				if(!$rst = $this->ConexionBaseDatos($sql))
					return false;
			}
			
			$this->IngresarObservacionGlosaCargo();
			return true;
		}
		/************************************************************************************
		* Funcion mediante la cual se ingresa una observacion, de un insumo cuando se esta
		* aceptado, sin incluir en un acta de conciliacion y cunado se esta conciliando
		* 
		* @return boolean
		*************************************************************************************/
		function IngresarObservacionGlosaInsumoBD()
		{
			$this->Observacion = $_REQUEST['observacion'];
			if($this->Observacion != "")
			{	
				$usuario = UserGetUID();
				$this->ActaId = $_REQUEST['acta_id'];
				$this->GlosaId = $_REQUEST['glosa_id'];
				$this->GlosaCuenta = $_REQUEST['glosa_id_cuenta'];
				$this->GlosaInsumo = $_REQUEST['glosa_detalle_id'];
				
				$sql .= "INSERT INTO  respuesta_glosas_detalle_inventarios";
				$sql .= "		( ";
				$sql .= "		observacion,";
				$sql .= "		glosa_id,";
				$sql .= "		glosa_detalle_cuenta_id,";
				$sql .= "		glosa_detalle_inventario_id,";
				$sql .= "		usuario_id,";
				
				if($this->ActaId)
					$sql .= "		acta_conciliacion_id, ";
				
				$sql .= "		fecha_registro";
				$sql .= "		) ";
				$sql .= "VALUES (";
				$sql .= "		'".$this->Observacion."',";
				$sql .= "		 ".$this->GlosaId.",";
				$sql .= "		 ".$this->GlosaCuenta.",";
				$sql .= "		 ".$this->GlosaInsumo.",";
				$sql .= "		 ".$usuario.",";
				
				if($this->ActaId)
					$sql .= "		acta_conciliacion_id, ";
				
				$sql .= "		   NOW()";
				$sql .= "		) ";
				
				if(!$rst = $this->ConexionBaseDatos($sql))
					return false;
			}
			
			$this->IngresarObservacionGlosaInsumo();
			return true;
		}
		/************************************************************************************
		* Funcion mediante la cual se modifica una observacion, agregandola a un acta o 
		* removiendola de ella para las cuentas
		* 
		* @return boolean
		*************************************************************************************/
		function AgregarObservacionActaGlosaBD()
		{
			
			$this->ActaId = $_REQUEST['acta_id'];
			$this->GlosaId = $_REQUEST['glosa_id'];
			$this->Id = $_REQUEST['identificacion'];
			
			if($_REQUEST['agregar'] == "on")
			{	
				$sql .= "UPDATE respuesta_glosas ";
				$sql .= "SET	acta_conciliacion_id =".$this->ActaId ." ";
				$sql .= "WHERE	glosa_id = ".$this->GlosaId." ";
				$sql .= "AND	respuesta_glosa_id = ".$this->Id." ";
				
				if(!$rst = $this->ConexionBaseDatos($sql))
					return false;
			}
			
			if($_REQUEST['agregar'] == "off")
			{	
				$sql .= "UPDATE respuesta_glosas ";
				$sql .= "SET	acta_conciliacion_id = NULL ";
				$sql .= "WHERE	glosa_id = ".$this->GlosaId." ";
				$sql .= "AND	respuesta_glosa_id = ".$this->Id." ";
				$sql .= "AND	acta_conciliacion_id =".$this->ActaId ." ";

				if(!$rst = $this->ConexionBaseDatos($sql))
					return false;
			}
			
			$this->IngresarObservacionGlosa();
			return true;
		}
		/************************************************************************************
		* Funcion mediante la cual se modifica una observacion, agregandola a un acta o 
		* removiendola de ella para los cargos
		* 
		* @return boolean
		*************************************************************************************/
		function AgregarObservacionActaGlosaCuentaBD()
		{	
			$this->ActaId = $_REQUEST['acta_id'];
			$this->GlosaId = $_REQUEST['glosa_id'];
			$this->Id = $_REQUEST['identificacion'];
			$this->GlosaCuenta = $_REQUEST['glosa_id_cuenta'];
			
			if($_REQUEST['agregar'] == "on")
			{	
				$sql .= "UPDATE respuesta_glosas_detalle_cuenta ";
				$sql .= "SET	acta_conciliacion_id =".$this->ActaId ." ";
				$sql .= "WHERE	glosa_id = ".$this->GlosaId." ";
				$sql .= "AND	respuesta_glosas_detalle_cuenta_id = ".$this->Id." ";
				$sql .= "AND	glosa_detalle_cuenta_id = ".$this->GlosaCuenta." ";
				
				if(!$rst = $this->ConexionBaseDatos($sql))
					return false;
			}
			
			if($_REQUEST['agregar'] == "off")
			{	
				$sql .= "UPDATE respuesta_glosas_detalle_cuenta ";
				$sql .= "SET	acta_conciliacion_id = NULL ";
				$sql .= "WHERE	glosa_id = ".$this->GlosaId." ";
				$sql .= "AND	respuesta_glosas_detalle_cuenta_id = ".$this->Id." ";
				$sql .= "AND	acta_conciliacion_id =".$this->ActaId ." ";
				$sql .= "AND	glosa_detalle_cuenta_id = ".$this->GlosaCuenta." ";
				
				if(!$rst = $this->ConexionBaseDatos($sql))
					return false;
			}
			
			$this->IngresarObservacionGlosaCuenta();
			return true;
		}
		/************************************************************************************
		* Funcion mediante la cual se modifica una observacion, agregandola a un acta o 
		* removiendola de ella para los insumos
		* 
		* @return boolean
		*************************************************************************************/
		function AgregarObservacionActaGlosaCargoBD()
		{
			$this->ActaId = $_REQUEST['acta_id'];
			$this->GlosaId = $_REQUEST['glosa_id'];
			$this->Id = $_REQUEST['identificacion'];
			$this->GlosaCuenta = $_REQUEST['glosa_id_cuenta'];
			$this->GlosaCargo = $_REQUEST['glosa_detalle_id'];
			
			if($_REQUEST['agregar'] == "on")
			{	
				$sql .= "UPDATE respuesta_glosas_detalle_cargos ";
				$sql .= "SET	acta_conciliacion_id =".$this->ActaId ." ";
				$sql .= "WHERE	glosa_id = ".$this->GlosaId." ";
				$sql .= "AND	respuesta_glosa_detalle_cargo_id = ".$this->Id." ";
				$sql .= "AND	glosa_detalle_cuenta_id = ".$this->GlosaCuenta." ";
				$sql .= "AND	glosa_detalle_cargo_id = ".$this->GlosaCargo." ";
				
				if(!$rst = $this->ConexionBaseDatos($sql))
					return false;
			}
			
			if($_REQUEST['agregar'] == "off")
			{	
				$sql .= "UPDATE respuesta_glosas_detalle_cargos ";
				$sql .= "SET	acta_conciliacion_id = NULL ";
				$sql .= "WHERE	glosa_id = ".$this->GlosaId." ";
				$sql .= "AND	respuesta_glosa_detalle_cargo_id = ".$this->Id." ";
				$sql .= "AND	acta_conciliacion_id =".$this->ActaId ." ";
				$sql .= "AND	glosa_detalle_cuenta_id = ".$this->GlosaCuenta." ";
				$sql .= "AND	glosa_detalle_cargo_id = ".$this->GlosaCargo." ";
				
				if(!$rst = $this->ConexionBaseDatos($sql))
					return false;
			}
			
			$this->IngresarObservacionGlosaCargo();
			return true;
		}
		/************************************************************************************
		* Funcion mediante la cual se modifica una observacion, agregandola a un acta o 
		* removiendola de ella para los insumos 
		*
		* @return boolean
		*************************************************************************************/
		function AgregarObservacionActaGlosaInsumoBD()
		{
			
			$this->ActaId = $_REQUEST['acta_id'];
			$this->GlosaId = $_REQUEST['glosa_id'];
			$this->Id = $_REQUEST['identificacion'];
			$this->GlosaCuenta = $_REQUEST['glosa_id_cuenta'];
			$this->GlosaInsumo = $_REQUEST['glosa_detalle_id'];
			
			if($_REQUEST['agregar'] == "on")
			{	
				$sql .= "UPDATE respuesta_glosas_detalle_inventarios ";
				$sql .= "SET	acta_conciliacion_id =".$this->ActaId ." ";
				$sql .= "WHERE	glosa_id = ".$this->GlosaId." ";
				$sql .= "AND	respuesta_glosa_detalle_inventario_id = ".$this->Id." ";
				$sql .= "AND	glosa_detalle_cuenta_id = ".$this->GlosaCuenta." ";
				$sql .= "AND	glosa_detalle_inventario_id = ".$this->GlosaInsumo." ";
				
				if(!$rst = $this->ConexionBaseDatos($sql))
					return false;
			}
			
			if($_REQUEST['agregar'] == "off")
			{	
				$sql .= "UPDATE respuesta_glosas_detalle_inventarios ";
				$sql .= "SET	acta_conciliacion_id = NULL ";
				$sql .= "WHERE	glosa_id = ".$this->GlosaId." ";
				$sql .= "AND	respuesta_glosa_detalle_inventario_id = ".$this->Id." ";
				$sql .= "AND	acta_conciliacion_id =".$this->ActaId ." ";
				$sql .= "AND	glosa_detalle_cuenta_id = ".$this->GlosaCuenta." ";
				$sql .= "AND	glosa_detalle_inventario_id = ".$this->GlosaInsumo." ";
				
				if(!$rst = $this->ConexionBaseDatos($sql))
					return false;
			}
			
			$this->IngresarObservacionGlosaInsumo();
			return true;
		}
		/************************************************************************************
		* Funcion que permite obtener un listado conlas actas pendientes de los clientes
		* 
		* @return array datos de las actas
		*************************************************************************************/
		function ObtenerActasConciliacionCerradas()
		{			
			$sql .= "SELECT	AC.acta_conciliacion_id AS acta, ";
			$sql .= "				AC.tipo_id_tercero, ";
			$sql .= "				AC.tercero_id, ";
			$sql .= "				AC.auditor_empresa, ";
			$sql .= "				TO_CHAR(AC.fecha_acta,'DD/MM/YYYY') AS fecha, ";
			$sql .= "				TE.nombre_tercero, ";
			$sql .= "				SU.nombre ";
			$where .= "FROM		actas_conciliacion_glosas AC,";
			$where .= "				terceros TE, ";
			$where .= "				system_usuarios SU ";
			$where .= "WHERE	AC.sw_activo = '1' ";
			$where .= "AND		AC.tipo_id_tercero = TE.tipo_id_tercero ";
			$where .= "AND		AC.tercero_id = TE.tercero_id ";
			$where .= "AND		AC.auditor_id = SU.usuario_id ";
					
			if($this->AuditorSel)
				$where .= "AND		AC.auditor_id = ".$this->AuditorSel." ";
				
			if($this->TerceroNombre)
				$where .= "AND		TE.nombre_tercero ILIKE '%".$this->TerceroNombre."%' ";
				
			if($this->TerceroTipoId != '0' && !empty($this->TerceroTipoId))
				$where .= "AND		AC.tipo_id_tercero = '".$this->TerceroTipoId."' ";
				
			if($this->TerceroDocumento)
				$where .= "AND		AC.tercero_id = '".$this->TerceroDocumento."' ";
			
			if($this->FechaFin)
			{
				$f = explode("/",$this->FechaFin);
				$where .= "AND 		AC.fecha_acta <= '".$f[2]."-".$f[1]."-".$f[0]." 00:00:00' ";
			}
			
			if($this->FechaInicio) 
			{
				$d = explode("/",$this->FechaInicio);
				$where .= "AND 		AC.fecha_acta >= '".$d[2]."-".$d[1]."-".$d[0]." 00:00:00' ";
			}

			if(!$this->ProcesarSqlConteo("SELECT COUNT(*) $where"))
				return false;
			
			$where .= "ORDER BY 1 DESC ";
			$where .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
			if(!$rst = $this->ConexionBaseDatos($sql.$where))
				return false;	
			
			while(!$rst->EOF)
			{
				$this->Actas[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
		}
		
		/****************************************************************************************
		* Funcion donde se obtienen las glosas que una factura ha tenido
		*****************************************************************************************/
		function ObtenerConceptosGenerales()
		{
			$sql .= "SELECT DISTINCT GCG.codigo_concepto_general, GCG.descripcion_concepto_general, ";
			$sql .= "	CGE.codigo_concepto_especifico, CGE.descripcion_concepto_especifico ";
			$sql .= "FROM 	glosas_concepto_general GCG, ";
			$sql .= "	glosas_concepto_especifico CGE, ";
			$sql .= "	glosas_concepto_general_especifico GCGE ";
			$sql .= "WHERE GCG.codigo_concepto_general = GCGE.codigo_concepto_general ";
			$sql .= "AND GCGE.codigo_concepto_especifico = CGE.codigo_concepto_especifico ";
			$sql .= "AND CGE.codigo_concepto_especifico <> '-1'; ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
	
			$datos = array();
			while (!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			return $datos;
		}
		
		/************************************************************************************
		* 
		* @param string sentencia sql a ejecutar 
		* @return rst 
		*************************************************************************************/
		function NoAceptarGlosa()
		{
			$this->request = $_REQUEST;
			//print_r($this->request);
			$this->action['volver'] = ModuloGetURL('app','AuditoriaCuentas','user','ConsultarInformacionGlosa',$this->request['datos']);
			$this->action['volverx'] = ModuloGetURL('app','AuditoriaCuentas','user','MostrarInformacionFacturasGlosadas',$this->request['datos']);
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
	}
?>