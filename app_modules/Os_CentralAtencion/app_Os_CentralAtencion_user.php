<?php
	/**************************************************************************************
	* $Id: app_Os_CentralAtencion_user.php,v 1.1 2010/01/20 20:58:30 hugo Exp $
	*
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* $Revision: 1.1 $
	*
	* @autor Hugo F  Manrique
	***************************************************************************************/
	IncludeClass('AtencionOs','','app','Os_CentralAtencion');
	class app_Os_CentralAtencion_user extends classModulo
	{
		
		
		/**
		* @var $ASIGNADAS VARIABLE DONDE SE GUARDA LA CANTIDAD DE CITAS AUTOMATICAS ASIGNADAS  EN 1ER CUMPLIMIENTO
		**/
		var $ASIGNADAS;
		/**
		* @var $CNT_CITA_ASIGNAR CANTIDAD DE CITAS QUE SE DEBEN ASIGNAR EN EL PRIMER CUMPLIMIENTO
		**/
		var $CNT_CITA_ASIGNAR;
		
		
		/**
		* @var $CITAS_ASIGNADAS ARREGLO CON LAS CITAS ASIGNDAS AUTOMATICAMENTE
		**/
		var $CITAS_ASIGNADAS;
		
		/**
		* @var $action Variable donde se guardan los action de las formsa
		**/
		var $action = array();
		var $citas = array();
		/**
		* @var $Opcion Variable donde se guardan la opcion que viene por request del menu principal
		**/
		var $Opcion = array();
		/**
		* @var $Permisos Variable donde se guardan los permisos del usuario
		**/
		var $Permisos = array();
		/**
		*  @var $Plantillas Variable para las plantillas de la historia clinica
		**/
		var $Plantillas = array();
		/**
		*  @var $GMedicamentos Variable para los grupos de medicamentos
		**/
		var $GMedicamentos = array();
		/**
		* @var $GMSolucion Variable para los medicamentos de un grupo de soluciones 
		**/
		var $GMSolucion = array();
		function app_Os_CentralAtencion_user(){}
		/********************************************************************************** 
		* Funci� donde se obtienen los permisos del ususrio sobre el modulo
		* 
		* @return boolean
		***********************************************************************************/
		function PermisosUsuario()
		{
			SessionDelVar("CentralAtecion");
			$aos = new AtencionOs();
			$this->Permisos = $aos->BuscarPermisosOs(UserGetUID());
			$this->action = ModuloGetURL('system','Menu','user','main');
		}
		/********************************************************************************** 
		* Funci� principal del m�ulo 
		* 
		* @return boolean
		***********************************************************************************/
		function MenuAtencion()
		{
			IncludeClass('Caja','','app','Cuentas');
			$cj = new Caja();
			
			$aos = new AtencionOs();
			$envio = array();
			$this->request = $_REQUEST;
			
			if(!SessionIsSetVar("CentralAtecion")) SessionSetVar("CentralAtecion",$this->request['os_atencion']);
			
			$this->Datos = SessionGetVar("CentralAtecion");
			$this->Rst['tiposid'] = $aos->TiposIdPaciente();
			$this->Rst['responsables'] = $aos->Responsables($this->Datos['empresa_id']);
			$this->Rst['obligatorios'] = $aos->ObtenerCamposObligatorios();
			
			if($this->request['grupo'])
				$this->grupo = $this->request['grupo'];
			else if(!$this->grupo) 
				$this->grupo = 0;
			
			if($this->request['buscador'] == '1')
			{
				if($this->request['numIngreso'])
					$this->Rst['ordeness'] = $aos->BuscarOrdenesPorNumero($this->Datos['departamento'],$this->request);
				else if($this->request['documento'])
					$this->Rst['ordeness'] = $aos->BuscarOrdenesPorId($this->Datos['departamento'],$this->request);
				else
					$this->Rst['ordeness'] = $aos->BuscarOrdenes($this->Datos['departamento'],$this->request);
				
				$envio['tipoDocumento'] = $this->request['tipoDocumento'];
				$envio['responsable'] = $this->request['responsable'];
				$envio['numIngreso'] = $this->request['numIngreso'];
				$envio['apellidos1'] = $this->request['apellidos1']; 
				$envio['apellidos2'] = $this->request['apellidos2']; 
				$envio['documento'] = $this->request['documento'];
				$envio['buscador'] = '1';
				$envio['nombres1'] = $this->request['nombres1'];
				$envio['nombres2'] = $this->request['nombres2'];
				$envio['registros'] = $aos->conteo;
				
				$this->conteo = $aos->conteo;
				$this->paginaA = $aos->paginaActual;
			}
			
			$rst = $cj->ObtenerPermisosCaja(UserGetUID(),$this->Datos['departamento'],$this->Datos['empresa_id']);
						
			if(empty($rst))
			{
				$this->frmError['Nota'] = "SU USUARIO NO POSEE PERMISOS PARA REALIZAR PAGOS";
				SessionSetVar("PermisoCajasRapidas",0);
			}
			else
			{
				$dat = array();
				$datos_caja = array();
				foreach($rst as $key => $empresa)
				{
					foreach($empresa as $keyI => $departamento)
					{
						foreach($departamento as $key => $caja)
						{
							$datos_caja = $caja;
							$dat = $cj->ValidarRecibosSinCuadrar($caja['caja_id'],$this->Datos['empresa_id'],UserGetUID());
						}
					}
				}
								
				if(!empty($dat))
				{
					$this->frmError['Nota'] = "LA CAJA RAPIDA DE ".$this->Datos['dpto'].", TIENE RECIBOS DE CAJA SIN CUADRAR";
					SessionSetVar("PermisoCajasRapidas",0);
				}
				else
				{
					$cnt = $this->ReturnModuloExterno('app','Cuentas','user');
					$cnt->SetDatosCaja($datos_caja);
					SessionSetVar("PermisoCajasRapidas",1);
				}
			}
			
      $this->action[0] = ModuloGetURL('app','Os_CentralAtencion','user','FormaMenuAtencion',array("buscador"=>'1'));
      $this->action[2] = ModuloGetURL('app','Os_CentralAtencion','user','FormaMenuAtencion',$envio);
			$this->action[1] = ModuloGetURL('app','Os_CentralAtencion','user','main');
			$this->action[3] = ModuloGetURL('app','Os_CentralAtencion','user','FormaDatosPaciente',array("opcion"=>'1'));
			$this->action[4] = ModuloGetURL('app','Os_CentralAtencion','user','EvaluarRequest',array("opcion"=>'2'));
			$this->action[5] = ModuloGetURL('app','Os_CentralAtencion','user','FormaMenuAtencion',array("grupo"=>'3'));
			$this->action['ordenar'] = ModuloGetURL('app','Os_CentralAtencion','user','FormaOrdenar');
			$this->action['paginador'] = ModuloGetURL('app','Os_CentralAtencion','user','FormaMenuAtencion',array("grupo"=>'3',"citas"=>$this->request['citas']));
		}
		/********************************************************************************** 
		* Funci� principal del m�ulo 
		* 
		* @return boolean
		***********************************************************************************/
		function BuscarListaOs()
		{
			$this->grupo = 1;
			$aos = new AtencionOs();
			
			$this->Datos = SessionGetVar("CentralAtecion");
			
			$envio = array();
			$this->request = $_REQUEST;
			
			$this->Rst['listaos'] = $aos->ObtenerListadoOS($this->Datos['departamento'],$this->request);
			$this->conteo1 = $aos->conteo;
			$this->paginaA1 = $aos->paginaActual;
		}
		/********************************************************************************** 
		* Funci� donde se crean las variables usadas en la funcion FormaOrdenar
		* 
		* @return boolean
		***********************************************************************************/
		function Ordenar()
		{
			$aos = new AtencionOs();			
			$this->Datos = SessionGetVar("CentralAtecion");
			
			$this->request = $_REQUEST;
			
			$this->Paciente = $aos->ObtenerDatosPaciente($this->request['tipoid'],$this->request['idp']);
      			if($this->Paciente['ingreso'])
				$this->Ingreso = $aos->GetDatosPaciente($this->Paciente['ingreso'],$this->request['tipoid'],$this->request['idp']);
			
			$plan = "";
			if($this->request['orden_servicio_id'])
				$plan = $this->request['plan_id'];
			
			$this->Contador = $aos->OrdenesPorAutorizar($this->request['tipoid'],$this->request['idp'],$this->Datos['departamento']);
			$this->Ordenes = $aos->ObtenerOrdenesC($this->Datos['departamento'],$this->request['idp'],$this->request['tipoid'],"I",$this->request['orden_servicio_id']);
			$this->Cargos = $aos->ObtenerCargosOrdenesC($this->Datos['departamento'],$this->request['idp'],$this->request['tipoid'],null,null,$plan,$this->request['orden_servicio_id']);
			
			$this->CuentasActivas = $aos->ObtenerCuentasOrdenesServicios($this->request['idp'],$this->request['tipoid'],$this->Datos['departamento']);
			
			if($this->Ingreso['plan_id']) $this->request['plan_id'] = $this->Ingreso['plan_id'];
			
			$array_request = array("tipoid"=>$this->request['tipoid'],"idp"=>$this->reuqest['idp'],"idp"=>$this->request['idp'],"grupo"=>$this->request['grupo'],"plan_id"=>$this->request['plan_id'],'orden_servicio_id'=>$this->request['orden_servicio_id']);
			
			$this->permiso = SessionGetVar("PermisoCajasRapidas");
			
			$this->action[0] = ModuloGetURL('app','Os_CentralAtencion','user','FormaMenuAtencion',array("grupo"=>$this->request['grupo']));
			$this->action[1] = ModuloGetURL('app','Os_CentralAtencion','user','FormaLiquidarCargos',
														array("ingreso"=>$this->Paciente['ingreso'],"paciente_id"=>$this->request['idp'],"tipo_id"=>$this->request['tipoid']));
			$this->action[2] = ModuloGetURL('app','Os_CentralAtencion','user','FormaSolicitudes',$array_request);
			
			$this->action[3] = ModuloGetURL('app','Os_CentralAtencion','user','FormaCargosOrdenesServicio',
														array("ingreso"=>$this->Paciente['ingreso'],"numerodecuenta"=>$this->Ingreso['numerodecuenta'],"idp"=>$this->request['idp'],"tipoid"=>$this->request['tipoid'],"plan_id"=>$this->request['plan_id']));
			$this->action[4] = ModuloGetURL('app','Os_CentralAtencion','user','FormaDatosCuenta',array("retorno"=>$array_request));

			$this->action[5] = ModuloGetURL('app','Os_CentralAtencion','user','FormaCumplirOrdenes',
														array("ingreso"=>$this->Paciente['ingreso'],"paciente_id"=>$this->request['idp'],"tipo_id"=>$this->request['tipoid']));
			$this->action[6] = ModuloGetURL('app','Os_CentralAtencion','user','FormaOrdenar',$array_request);
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function CrearNuevaAutorizacion($rqst)
		{
			$this->action['cancelar'] = "javascript:window.close()";
			$this->action['aceptar'] = ModuloGetURL('app','Os_CentralAtencion','user','FormaLiquidarCargos',
														array("ingreso"=>$this->request['ingreso'],"paciente_id"=>$this->request['paciente_id'],"tipo_id"=>$this->request['tipo_id'],
																	"autorizado"=>"1","cargos"=>$this->request['cargos'],"orden_id"=>$this->request['orden_id'],"plan_id"=>$this->request['plan_id']));

		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ValidarAutorizacion($rqst)
		{
			IncludeClass('ConsultaAutorizaciones','','app','NCAutorizaciones');
			$cnt = new  ConsultaAutorizaciones();
			$planes = $cnt->ObtenerPlanes($rqst['plan_id']);
			if($planes[$rqst['plan_id']]['sw_afiliacion'] == 1)
				return false;
			
			return true;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function LiquidarCargos()
		{
			$aos = new AtencionOs();
			$this->ingreso = false;
			$this->request = $_REQUEST;
			$this->datos = SessionGetVar("CentralAtecion");
			$this->request['idp'] = $this->request['paciente_id'];
			$this->request['tipoid'] = $this->request['tipo_id'];
			SessionDelVar("CargosSel");
			
			if(!$this->request['autorizado'])
			{
				$rst = $this->ValidarAutorizacion($this->request);
				if($rst === false) return false;
			}
			else
			{
				$this->request['autorizacion'] = $this->request['autorizacion']['numero_autorizacion'];
			}
				
			SessionSetVar("CargosSel",$this->request['cargos']);
			IncludeClass('ConsultaAtencionOs','','app','Os_CentralAtencion');
			
			$caos = new ConsultaAtencionOs();
			$this->cuentas = $caos->ObtenerDatosCuentas($this->request);
			
			if(empty($this->cuentas))
				$this->frmError['MensajeError'] = "EL PACIENTE NO POSSE CUENTAS ACTIVAS NI AMBULATORIAS, SE PROCEDER�A LA CREACION DE UNA CUENTA";
			else
			{
				$activas = $this->cuentas['1'];
				$inactivas = $this->cuentas['2'];

				
				if(!empty($activas[$this->datos['departamento']]))
				{
					$this->frmError['MensajeError'] = "EL PACIENTE POSEE LA(S) SIGUIENTE(S) CUENTA(S) AMBULATORIA(S):";
					
					foreach($activas[$this->datos['departamento']] as $key => $datos)
					{
						
						$this->cuenta[$i]['numerodecuenta'] = $datos['numerodecuenta'];
						$this->cuenta[$i++]['estado'] = "ACTIVA";
						//$this->cuenta[$i]['plan'] = $datos['plan_id'];
					}
				}
				else if(!empty($activas[0]) || !empty($inactivas[0]))
				{
					if($activas[0][0]['ingreso'] == $this->request['ingreso'] || $inactivas[0][0]['ingreso'] == $this->request['ingreso'])
					{
						$i = 0;
						$this->frmError['MensajeError'] = "EL PACIENTE POSEE LAS SIGUIENTES CUENTAS: ";
						
						if($activas[0][0]['numerodecuenta'])
						{
							
							$this->cuenta[$i]['numerodecuenta'] = $activas[0][0]['numerodecuenta'];
							$this->cuenta[$i++]['estado'] = "ACTIVA";
							//$this->cuenta[$i+=1]['plan'] = $datos['plan_id'];
						}
						
						foreach($inactivas[0] as $key => $datos)
						{
							
						
							$this->cuenta[$i]['numerodecuenta'] = $datos['numerodecuenta'];
							$this->cuenta[$i++]['estado'] = "INACTIVA";
							//$this->cuenta[$i+=1]['plan'] = $datos['plan_id'];
						}
					}
					else
					{
						$this->frmError['MensajeError'] = "EL PACIENTE NO POSSE CUENTAS ACTIVAS NI AMBULATORIAS, SE PROCEDER�A LA CREACION DE UNA CUENTA";
					}
				}
				else
				{
					$this->frmError['MensajeError'] = "EL PACIENTE NO POSSE CUENTAS ACTIVAS NI AMBULATORIAS PARA EL DEPARTAMENTO ".$this->datos['dpto'].", SE PROCEDER�A LA CREACION DE UNA CUENTA";
				}
			}
			return true;
		}
		
		
		function CantidadOrdenesCuentaDpto($Cuenta,$Depto,$Plan)
		{
		
			list($dbconn) = GetDBconn();
			$sql="SELECT COUNT(OS.numero_orden_id) 
			
			FROM  os_maestro OS, os_ordenes_servicios OOS
			
			WHERE OOS.plan_id = ".$Plan."
			AND OOS.departamento = '".$Depto."'
			AND OOS.paciente_id ='".$this->request[paciente_id]."'
			AND OOS.tipo_id_paciente = '".$this->request[tipo_id]."'
			AND OOS.orden_servicio_id = OS.orden_servicio_id
			AND OS.numerodecuenta = ".$Cuenta."
			AND OS.sw_estado = '3';
			";
			
			$result = $dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$result->Close();
			return $result->fields[0];
			//return true;
		}
		
		function NombrePlanCuenta($cuenta)
		{
		
			list($dbconn) = GetDBconn();
			$sql="SELECT PL.plan_descripcion, PL.plan_id FROM cuentas AS C, planes as PL WHERE C.numerodecuenta = ".$cuenta."
			AND C.plan_id = PL.plan_id";
			$result = $dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$result->Close();
			return $result->fields[0].",".$result->fields[1];
			//return true;
		}
		
		
		/********************************************************************************** 
		* Funci� principal del m�ulo 
		* 
		* @return boolean
		***********************************************************************************/
		function Solicitudes()
		{		
			$this->request = $_REQUEST;
			$this->Datos = SessionGetVar("CentralAtecion");
			
			$this->action['volver'] = ModuloGetURL('app','Os_CentralAtencion','user','FormaOrdenar',
							array("idp"=>$this->request['idp'],"tipoid"=>$this->request['tipoid'],"plan_id"=>$this->request['plan_id'],"grupo"=>$this->request['grupo']));
			$this->action['autorizar'] = ModuloGetURL('app','Os_CentralAtencion','user','FormaAutorizarCargos',
							array("idp"=>$this->request['idp'],"tipoid"=>$this->request['tipoid'],"plan_id"=>$this->request['plan_id'],"ingreso"=>$this->request['ingreso']));
		}
		/********************************************************************************** 
		* Funci� principal del m�ulo 
		* 
		* @return boolean
		***********************************************************************************/
		function AutorizarCargos($flag)
		{
			IncludeClass('Autorizaciones','','app','NCAutorizaciones');
			
			$aos = new AtencionOs();
			$aut = new Autorizaciones();
			
			$this->request = $_REQUEST;
			$this->Datos = SessionGetVar("CentralAtecion");
			SessionSetVar("CargosOSSeleccionados",array("cargos1"=>$this->request['cargos']));
			
			$this->action['cancelar'] = ModuloGetURL('app','Os_CentralAtencion','user','FormaSolicitudes',
							array("idp"=>$this->request['idp'],"tipoid"=>$this->request['tipoid'],"plan_id"=>$this->request['plan_id'],"ingreso"=>$this->request['ingreso']));
			$this->action['aceptar'] = ModuloGetURL('app','Os_CentralAtencion','user','FormaCargosOrdenesServicio',
							array("idp"=>$this->request['idp'],"tipoid"=>$this->request['tipoid'],"plan_id"=>$this->request['plan_id']));
			//$this->Ingreso = $aos->GetDatosPaciente($this->request['ingreso'],$this->request['tipoid'],$this->request['idp'],$ubicacion = 0);

			$this->action['buscarOs'] = ModuloGetURL('app','Os_CentralAtencion','user','FormaAutorizarCargos',
														array("idp"=>$this->request['idp'],"tipoid"=>$this->request['tipoid'],"plan_id"=>$this->request['plan_id'],"ingreso"=>$this->request['ingreso']));
		}
		/********************************************************************************** 
		* 
		* @return boolean
		***********************************************************************************/
		function CargosOrdenesServicio()
		{		
			$this->request = $_REQUEST;
			$this->Datos = SessionGetVar("CentralAtecion");
			if($this->request['autorizacion']) 
				$this->auto = $this->request['autorizacion'];
			else
				$this->auto = $this->request;
			
			$this->cargos = SessionGetVar("CargosOSSeleccionados");
			$paciente = array("rango" =>$this->auto['rango'],"ingreso" =>$this->auto['ingreso'],
												"plan_id" =>$this->auto['plan_id'],"semanas" =>$this->auto['semanas'],
												"paciente_id" =>$this->auto['paciente_id'],"tipo_id_paciente" =>$this->auto['tipo_id_paciente'],
												"tipo_afiliado_id" =>$this->auto['tipoafiliado'],"numero_autorizacion" =>$this->auto['numero_autorizacion']);
			
			SessionSetVar("DatosPaciente".$paciente['numero_autorizacion'],$paciente);
	
			$this->action['volver'] = ModuloGetURL('app','Os_CentralAtencion','user','FormaOrdenar',
							array("idp"=>$this->auto['paciente_id'],"tipoid"=>$this->auto['tipo_id_paciente'],"plan_id"=>$this->auto['plan_id'],"grupo"=>$this->request['grupo']));
			$this->action['aceptar'] = ModuloGetURL('app','Os_CentralAtencion','user','FormaCrearOrden',
							array("numero_autorizacion"=>$paciente['numero_autorizacion']));
		}
		/********************************************************************************** 
		* Funcion donde se obtienen los datos de variables de sesion y del request para la 
		* creacion de las ordenes de servicio
		* @return boolean
		***********************************************************************************/
		function CrearOrden()
		{		
			$this->request = $_REQUEST;

			$aos = new AtencionOs();
			$paciente = SessionGetVar("DatosPaciente".$this->request['numero_autorizacion']);
			$centroatencion = SessionGetVar("CentralAtecion");

			$orden = $aos->IngresarOrdenServicio($this->request['ordenes'],$paciente,$this->request['proveedor']);
			if($orden)
			{
				$this->frmError['MensajeError'] = "LA(S) ORDEN(ES) DE SERVICIO: ".$orden.", SE HAN CREADO SATISFACTORIAMENTE";
				SessionDelVar("CargosOSSeleccionados");
				SessionDelVar("DatosPaciente".$this->request['numero_autorizacion']);
			}
			else
			{
				$this->frmError['MensajeError']  = "HA OCURRIDO UN ERROR DURANTE LA CREACION DE LAS ORDENES DE SERVICIO:";
				$this->frmError['MensajeError'] .= "<b class=\"label_error\">".$aos->frmError['MensajeError']."</b>";
			}
			
			
			$this->action['aceptar'] = ModuloGetURL('app','Os_CentralAtencion','user','FormaOrdenar',
							array("idp"=>$paciente['paciente_id'],"tipoid"=>$paciente['tipo_id_paciente'],"plan_id"=>$paciente['plan_id']));

			return $orden;
		}
		/********************************************************************************** 
		* Funci� principal del m�ulo 
		* 
		* @return boolean
		***********************************************************************************/
		function DatosPaciente()
		{
			$this->request = $_REQUEST;
			$datos = array();
			
			SessionDelVar("CargosAdicionados");
			$datos['tipo_id_paciente'] = $this->request['tipo_id_paciente'];
			$datos['paciente_id'] = $this->request['paciente_id'];
			$datos['plan_id'] = $this->request['plan_id'];
			$this->action['cancelar'] = ModuloGetURL('app','Os_CentralAtencion','user','FormaMenuAtencion',array("grupo"=>2));
			$this->action['volver'] = ModuloGetURL('app','Os_CentralAtencion','user','FormaIngresarCargos',$datos);
		}
		/********************************************************************************** 
		* Funci� principal del m�ulo 
		* 
		* @return boolean
		***********************************************************************************/
		function DatosCuenta()
		{
			$this->request = $_REQUEST;
			if($this->request['numerodecuenta'])
				$this->numerodecuenta = $this->request['numerodecuenta'];
			else
				$this->numerodecuenta = SessionGetVar("NumeroCuentaSeleccionada");
				
			$this->action['volver'] = ModuloGetURL('app','Os_CentralAtencion','user','FormaOrdenar',$this->request['retorno']);
		}	
		/********************************************************************************** 
		* 
		* @return boolean
		***********************************************************************************/
		function CumplirOrdenes()
		{
			$this->request = $_REQUEST;
//print_r($this->request); 
			IncludeClass('Cumplimiento','','app','Os_CentralAtencion');
			$cmp = new Cumplimiento();
			
			$this->Datos = SessionGetVar("CentralAtecion");
			$this->cumplidos = $cmp->ObtenerDatosCumplimiento($this->request,$this->Datos['departamento']);

			//$n_cumplimiento = $cmp->ObtenerCantidadCumplimientos($this->request,$this->Datos['departamento']);
			echo $n_cumplimiento;
			$n_cumplimiento = 0;
			if(!empty($this->request['cargos']))
				$this->cargos = $this->request['cargos'];
			else
			{
				IncludeClass('AtencionOs','','app','Os_CentralAtencion');
				$aos = new AtencionOs();
				$this->cargos = $aos->ObtenerCargosOrdenesC($this->Datos['departamento'],$this->request['paciente_id'],$this->request['tipo_id'],null,null,$this->request['plan_id'],$this->request['orden_servicio_id'],$this->request['sw_fecha_vencimiento']);
			}
			
			foreach($this->cargos as $key => $os_maestro_cargos)
			{
				if($this->cumplidos[$os_maestro_cargos]['sw_liquidar_honario'] == '0')
				{
					if($this->cumplidos[$os_maestro_cargos]['sw_cumplido_automatico'] == '1')
					{
						if(!$n_cumplimiento = $cmp->ActualizarCumplimientoOrden($this->request,$this->Datos['departamento'],$this->cumplidos[$os_maestro_cargos],$n_cumplimiento,null,$this->cumplidos[$os_maestro_cargos]['cantidad_pendiente'],$os_maestro_cargos))
						{
							$this->frmError['MensajeError'] = $cmp->frmError['MensajeError'];
							return false;
						}
						unset($this->cargos[$key]);
					}
				}
			}
			
			$this->action['cerrar'] = "window.close()";
			if(empty($this->cargos))
				$this->action['cerrar'] = "javascript:Cerrar()";

			$this->action['aceptar'] = ModuloGetURL('app','Os_CentralAtencion','user','FormaIngresarCumplimiento',
														array("numero_cumplimiento"=>$n_cumplimiento,"orden_id"=>$this->request['orden_id'],"paciente_id"=>$this->request['paciente_id'],"tipo_id"=>$this->request['tipo_id'],"plan_id"=>$this->request['plan_id'],"cargos"=>$this->cargos));

			return true;
		}
		/********************************************************************************** 
		* Funci� principal del m�ulo 
		* 
		* @return boolean
		***********************************************************************************/
		function IngresarCumplimiento()
		{
			$this->request = $_REQUEST;
      
			IncludeClass('Cumplimiento','','app','Os_CentralAtencion');
			$cmp = new Cumplimiento();
			
			$cargos = $this->request['cargos'];
			$profesional = $this->request['profesional'];
			$cantidades = $this->request['cantidad'];
			
			$this->frmError['MensajeError'] = "SE HA REGISTRADO EL CUMPLIMIENTO DE LOS CARGOS CORRECTAMENTE";
			//$this->request
			$datos = SessionGetVar("CentralAtecion");
			$this->cumplidos = $cmp->ObtenerDatosCumplimiento($this->request,$datos['departamento']);
			
			$n_cumplimiento = $this->request['numero_cumplimiento'];
			if(!$n_cumplimiento) $n_cumplimiento = 0;
			
			$this->action['cerrar'] = "javascript:Cerrar()";
			
      //print_r($this->cumplidos);
     
      
      
			//ASIGNAR CITAS RESTANTES
//			$cmp->AsignarCitas($cumplidos,$this->request);
			//FIN ASIGNAR CITAS RESTANTES
						
			$cmp->AsignarCitasAutomaticas($this->cumplidos,$this->request);
			
			$this->ASIGNADAS=$cmp->ASIGNO;
			$this->CNT_CITA_ASIGNAR=$cmp->cnt_citas_asignar;
			$this->CITAS_ASIGNADAS=$cmp->turno_cita_asignada;
			foreach($cargos as $key => $os_maestro_cargos)
			{
				if(!$n_cumplimiento = $cmp->ActualizarCumplimientoOrden($this->request,$datos['departamento'],$this->cumplidos[$os_maestro_cargos],$n_cumplimiento,$profesional[$os_maestro_cargos],$cantidades[$os_maestro_cargos],$os_maestro_cargos))
				{
					$this->frmError['MensajeError'] = $cmp->frmError['MensajeError'];
					return false;
				}
			}
      //print_r($n_cumplimiento);
			
			//ASIGNAR CITAS RESTANTES
			
			
			//FIN ASIGNAR CITAS RESTANTES
			
			return true;
		}
		/*************************************************************************
		*
		**************************************************************************/
		function IngresarCargos()
		{
			$this->request = $_REQUEST;
			$datos = array("tipo_id_paciente"=>$this->request['tipo_id_paciente'],"paciente_id"=>$this->request['paciente_id'],"plan_id"=>$this->request['plan_id'],"afiliado"=>$this->request['afilia']);
			
			$this->depart = SessionGetVar("CentralAtecion");
			$this->action['aceptar'] = ModuloGetURL('app','Os_CentralAtencion','user','FormaCrearSolicitud',$datos);
			$this->action['cancelar'] = ModuloGetURL('app','Os_CentralAtencion','user','FormaMenuAtencion',array("grupo"=>2));
		}
		/*************************************************************************
		*
		**************************************************************************/
		function CrearSolicitud()
		{
			$this->request = $_REQUEST;
			
			$crgadd = SessionGetVar("CargosAdicionados");
			$datos = SessionGetVar("CentralAtecion");

			IncludeClass('SolicitudManual','','app','Os_CentralAtencion');
			$slm = new SolicitudManual();
			
			$cargos = $slm->IngresarSolictudManual($this->request,$crgadd,$datos);
			$this->action['cancelar'] = ModuloGetURL('app','Os_CentralAtencion','user','FormaMenuAtencion',array("grupo"=>2));
			
			if($cargos === false)
			{
				$this->frmError['MensajeError'] = $slm->frmError['MensajeError'];
				return false;
			}
			
			$rst['idp'] = $this->request['paciente_id'];
			$rst['tipoid'] = $this->request['tipo_id_paciente'];
			$rst['plan_id'] = $this->request['plan_id'];
			$rst['afiliado'] = $this->request['afiliado'];
			$rst['cargos'] = $cargos;
			
			$this->action['aceptar'] = ModuloGetURL('app','Os_CentralAtencion','user','FormaAutorizarCargos',$rst);
			return true;
		}
	}
?>