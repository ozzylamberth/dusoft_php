<?php
	/*********************************************************************************************
	* $Id: app_Os_CentralAutorizacionGeneral_user.php,v 1.1 2007/04/16 20:46:39 hugo Exp $
	*
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* $Revision: 1.1 $
	*
	* @autor Hugo F. Manrique
	***********************************************************************************************/
	class app_Os_CentralAutorizacionGeneral_user extends classModulo
	{
		/**
		* @var $action array Variable para guardar los links
		***/
		var $action = array();
		/**
		* @var $request array Variable para guardar los datos del request
		***/
		var $request = array();
		
		var $paciente = array();
		
		function app_Os_CentralAutorizacionGeneral_user(){}
		/********************************************************************************** 
		* 
		* 
		* @return boolean
		***********************************************************************************/
		function SetActionVolver($link)
		{
			SessionDelVar("ActionVolver_OCG");
			SessionSetVar("ActionVolver_OCG",$link);
		}
		/********************************************************************************** 
		* Función principal del módulo 
		* 
		* @return boolean
		***********************************************************************************/
		function Main()
		{
			IncludeClass('ConsultaAutorizacionGrl','','app','Os_CentralAutorizacionGeneral');
			$cng = new ConsultaAutorizacionGrl();

			SessionDelVar("AtencionGral");
			$this->empresa = $cng->ObtenerPermisos(UserGetUID());
			if (sizeof($this->empresa) == 1)
			{
				foreach($this->empresa as $key => $atencion)
				{
					$_REQUEST['Atencion'] = $atencion;
					$_REQUEST['Atencion']['sw_main'] = 1;
				}
				return false;
			}
			return true;
		}
		/********************************************************************************** 
		* Función principal del módulo 
		* 
		* @return boolean
		***********************************************************************************/
		function MenuPrincipal()
		{
			$this->request = $_REQUEST;
			if(!empty($this->request['Atencion']))
				SessionSetVar("AtencionGral",$this->request['Atencion']);
			
			$this->permisos = SessionGetVar("AtencionGral");
			if(!$this->permisos['sw_main'])
				$this->action['volver'] = ModuloGetURL('app','Os_CentralAutorizacionGeneral','user','FormaMain');
			else
				$this->action['volver'] = ModuloGetURL('system','Menu');
			$this->action['buscar'] = ModuloGetURL('app','Os_CentralAutorizacionGeneral','user','FormaMenuPrincipal');
			$this->action['ordenes'] = ModuloGetURL('app','Os_CentralAutorizacionGeneral','user','FormaListarOrdenes');
			$this->action['autorizar'] = ModuloGetURL('app','Os_CentralAutorizacionGeneral','user','FormaDatosAutorizacionOs');
			$this->action['solicitud'] = ModuloGetURL('app','Os_CentralAutorizacionGeneral','user','FormaSolicitudesManuales');
		}
		/********************************************************************************** 
		* 
		* @return boolean
		***********************************************************************************/
		function DatosAutorizacionOs()
		{
			$this->request = $_REQUEST;
			
			$this->action['volver'] = ModuloGetURL('app','Os_CentralAutorizacionGeneral','user','FormaMenuPrincipal');
			$this->action['anular'] = ModuloGetURL('app','Os_CentralAutorizacionGeneral','user','FormaAnulacionSolicitud');
			$this->action['recarga'] = ModuloGetURL('app','Os_CentralAutorizacionGeneral','user','FormaDatosAutorizacionOs',array("paciente"=>$this->request['paciente']));
			$this->action['autorizar'] = ModuloGetURL('app','Os_CentralAutorizacionGeneral','user','FormaAutorizarCargos',array("paciente"=>$this->request['paciente']));
		}
		/********************************************************************************** 
		* 
		* @return boolean
		***********************************************************************************/
		function AutorizarCargos()
		{
			$this->request = $_REQUEST;
			$this->paciente = $this->request['paciente'];
			
			$this->paciente['plan_id'] = $this->request['plan_num'];
						
			$this->paciente['idp'] = $this->paciente['paciente_id'];
			$this->paciente['tipoid'] = $this->paciente['tipo_id_paciente'];
			
			SessionSetVar("CargosOSSeleccionados",array("cargos1"=>$this->request['cargos']));

			$this->action['volver'] = ModuloGetURL('app','Os_CentralAutorizacionGeneral','user','FormaDatosAutorizacionOs',array("paciente"=>$this->request['paciente']));
			$this->action['aceptar'] = ModuloGetURL('app','Os_CentralAutorizacionGeneral','user','FormaMostrarCargosOS',array("paciente"=>$this->request['paciente']));
		}
		/********************************************************************************** 
		* 
		* @return boolean
		***********************************************************************************/
		function MostrarCargosOS()
		{		
			$this->request = $_REQUEST;
			
			$this->auto = $this->request['autorizacion'];
			
			$this->cargos = SessionGetVar("CargosOSSeleccionados");

			$this->paciente = array("rango" =>$this->auto['rango'],"ingreso" =>$this->auto['ingreso'],
												"plan_id" =>$this->auto['plan_id'],"semanas" =>$this->auto['semanas'],
												"paciente_id" =>$this->auto['paciente_id'],"tipo_id_paciente" =>$this->auto['tipo_id_paciente'],
												"tipo_afiliado_id" =>$this->auto['tipoafiliado'],"numero_autorizacion" =>$this->auto['numero_autorizacion']);
			
			SessionSetVar("DatosPaciente",$this->paciente);

			$this->action['volver'] = ModuloGetURL('app','Os_CentralAutorizacionGeneral','user','FormaDatosAutorizacionOs',array("paciente"=>$this->paciente));
			$this->action['aceptar'] = ModuloGetURL('app','Os_CentralAutorizacionGeneral','user','FormaCrearOrdenesServicio',array("numero_autorizacion"=>$this->paciente['numero_autorizacion']));
		}
		/********************************************************************************** 
		* 
		* @return boolean
		***********************************************************************************/
		function AnulacionSolicitud()
		{
			$this->request = $_REQUEST;		
			$this->action['aceptar'] = ModuloGetURL('app','Os_CentralAutorizacionGeneral','user','FormaAnularSolicitud');
		}
		/********************************************************************************** 
		* 
		* @return boolean
		***********************************************************************************/
		function AnularSolicitud()
		{
			IncludeClass('AutorizacionGeneralOs','','app','Os_CentralAutorizacionGeneral');

			$this->request = $_REQUEST;
			$aos = new AutorizacionGeneralOs();
			$rst = $aos->AnularSolicitud($this->request);
			if($rst)
				$this->frmError['MensajeError'] = "LA SOLICITUD Nº ".$this->request['hc_os_solicitud_id']." FUE ANULADA CORRECtAMENTE";
			else
				$this->frmError['MensajeError'] = $aos->frmError['MensajeError'];
				
			$this->action['aceptar'] = "javascript:window.opener.document.recarga.submit();window.close()";
		}
		/********************************************************************************** 
		* Funcion donde se obtienen los datos de variables de sesion y del request para la 
		* creacion de las ordenes de servicio
		* @return boolean
		***********************************************************************************/
		function CrearOrdenesServicio()
		{		
			IncludeClass('AutorizacionGeneralOs','','app','Os_CentralAutorizacionGeneral');
			$this->request = $_REQUEST;
			
			$paciente = SessionGetVar("DatosPaciente");
			$ordenes = SessionGetVar("OrdenesServicio");
					
			$aos = new AutorizacionGeneralOs();
			if(!empty($paciente))
			{
				$orden = $aos->CrearOrdenServicio($ordenes,$paciente);
		
				if($orden)
				{
					$this->frmError['MensajeError'] = "LA(S) ORDEN(ES) DE SERVICIO: ".$orden.", SE HAN CREADO SATISFACTORIAMENTE";
					SessionDelVar("DatosPaciente");
					SessionDelVar("OrdenesServicio");
				}
				else
				{
					$this->frmError['MensajeError']  = "HA OCURRIDO UN ERROR DURANTE LA CREACION DE LAS ORDENES DE SERVICIO:";
					$this->frmError['MensajeError'] .= "<b class=\"label_error\">".$aos->frmError['MensajeError']."</b>";
				}
				
			}
			
			if(!$paciente['nombre'])
			{
				$filtro['tipodocumento'] = $paciente['tipo_id_paciente'];
				$filtro['documento'] = $paciente['paciente_id'];
				$paciente = $aos->ObtenerDatosPaciente($filtro);
				
				$this->action['aceptar'] = ModuloGetURL('app','Os_CentralAutorizacionGeneral','user','FormaListarOrdenes',array("paciente"=>$paciente[0]));
			}
			else
				$this->action['aceptar'] = ModuloGetURL('app','Os_CentralAutorizacionGeneral','user','FormaDatosAutorizacionOs',array("paciente"=>$paciente));

			return $orden;
		}
		/********************************************************************************** 
		* 
		* @return boolean
		***********************************************************************************/
		function ListarOrdenes()
		{
			$this->request = $_REQUEST;
			$this->action['volver'] = ModuloGetURL('app','Os_CentralAutorizacionGeneral','user','FormaMenuPrincipal');
			$this->action['fechas'] = ModuloGetURL('app','Os_CentralAutorizacionGeneral','user','FormaCambiarFechas');
			$this->action['anular'] = ModuloGetURL('app','Os_CentralAutorizacionGeneral','user','FormaAnularOrdenes');
			$this->action['ordenes'] = ModuloGetURL('app','Os_CentralAutorizacionGeneral','user','FormaListarOrdenes',array("paciente"=>$this->request['paciente']));
			$this->action['listado'] = ModuloGetURL('app','Os_CentralAutorizacionGeneral','user','FormaOrdenesVencidas',array("paciente"=>$this->request['paciente']));
		}
		/********************************************************************************** 
		* 
		* @return boolean
		***********************************************************************************/
		function AnularOrdenes()
		{
			$this->request = $_REQUEST;
			$this->action['aceptar'] = ModuloGetURL('app','Os_CentralAutorizacionGeneral','user','FormaRegistrarAnulacionOrden',array("orden_servicio_id"=>$this->request['orden_servicio_id']));
		}
		/********************************************************************************** 
		* 
		* @return boolean
		***********************************************************************************/
		function RegistrarAnulacionOrden()
		{
			$this->request = $_REQUEST;
			
			IncludeClass('AutorizacionGeneralOs','','app','Os_CentralAutorizacionGeneral');
				
			$ags = new AutorizacionGeneralOs();
			$rst = $ags->AnularOrdenesServicio($this->request);
			
			if(!$rst)
				$this->frmError['MensajeError'] = $aos->frmError['MensajeError'];
			else
				$this->frmError['MensajeError'] = "LA ORDEN DE SERVICIO Nº ".$this->request['orden_servicio_id'].", HA SIDO ANULADA";
			
			$this->action['aceptar'] = "javascript:window.opener.document.recarga.submit();window.close()";
		}
		/********************************************************************************** 
		* 
		* @return boolean
		***********************************************************************************/
		function CambiarFechas()
		{
			$this->request = $_REQUEST;
			
			$fechas['refrendar'] = $this->request['refrendar'];
			$fechas['activacion'] = $this->request['activacion'];
			$fechas['vencimiento'] = $this->request['vencimiento'];
			$fechas['orden_servicio_id'] = $this->request['orden_servicio_id'];
						
			$this->action['aceptar'] = ModuloGetURL('app','Os_CentralAutorizacionGeneral','user','FormaCambiarFechas',$fechas);
			if($this->request['aceptar'])
			{
				IncludeClass("ClaseUtil");
				$clut = new ClaseUtil();
				
				$rst = $clut->ValidarFecha($fechas['refrendar'],"/");
				if(!$rst)
				{
					$this->frmError['MensajeError'] .= "LA FECHA PARA REFRENDAR POSSE UN FORMATO INCORRECTO O NO ES VALIDA";
					return false;
				}
													
				$f1 = explode("/",$fechas['vencimiento']);
				
				if(($f1[2]."-".$f1[1]."-".$f1[0]) < date("Y-m-d"))
				{
					$this->frmError['MensajeError'] .= "SUGERENCIA: LA FECHA DE VENCIMIENTO DEBE SER MAYOR O IGUAL A LA FECHA ACTUAL ".date("d/m/Y")." ";
					return false;
				}
				
				IncludeClass('AutorizacionGeneralOs','','app','Os_CentralAutorizacionGeneral');
				
				$ags = new AutorizacionGeneralOs();
				$rst = $ags->ActualizarFechaRefrendar($f1[2]."-".$f1[1]."-".$f1[0],$fechas['orden_servicio_id']);
				
				if(!$rst)
				{
					$this->frmError['MensajeError'] = $ags->frmError['MensajeError'];
					return false;
				}
				$this->frmError['MensajeError'] = "LA FECHA PARA REFRENDAR LA ORDEN DE SERVICIO Nº ".$this->request['orden_servicio_id'].", SE HA REALIZADO CORRECTAMENTE";
				$this->action['aceptar'] = "javascript:window.opener.document.recarga.submit();window.close()";
				return true;
			}

			return false;
		}
		/********************************************************************************** 
		* 
		* @return boolean
		***********************************************************************************/
		function OrdenesVencidas()
		{
			$this->request = $_REQUEST;			
			$this->action['aceptar'] = "javascript:window.close()";
		}
		/********************************************************************************** 
		* 
		* @return boolean
		***********************************************************************************/
		function SolicitudesManuales()
		{
			$this->request = $_REQUEST;
			
			IncludeClass('ConsultaAutorizaciones','','app','NCAutorizaciones');
			$caut = new ConsultaAutorizaciones();
			
			$this->plan = $caut->ObtenerPlanes($this->request['plan_id']);
			
			if($this->plan[$this->request['plan_id']]['sw_tipo_plan'] == '1')
				return "1";
			
			return "2";
		}
		/********************************************************************************** 
		* Función principal del módulo 
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
			$datos['evento_soat'] = $this->request['evento_soat'];
			
			$this->action['cancelar'] = ModuloGetURL('app','Os_CentralAutorizacionGeneral','user','FormaMenuPrincipal',array("grupo"=>1));
			$this->action['volver'] = ModuloGetURL('app','Os_CentralAutorizacionGeneral','user','FormaIngresarCargos',$datos);
		}
		/*************************************************************************
		*
		**************************************************************************/
		function IngresarCargos()
		{
			$this->request = $_REQUEST;
			$datos = array("evento_soat"=>$this->request['evento_soat'],"tipo_id_paciente"=>$this->request['tipo_id_paciente'],"paciente_id"=>$this->request['paciente_id'],"plan_id"=>$this->request['plan_id'],"afiliado"=>$this->request['afilia']);
			//print_r($this->request);
			$this->action['aceptar'] = ModuloGetURL('app','Os_CentralAutorizacionGeneral','user','FormaCrearSolicitud',$datos);
			$this->action['cancelar'] = ModuloGetURL('app','Os_CentralAutorizacionGeneral','user','FormaMenuPrincipal',array("grupo"=>1));
		}
		/*************************************************************************
		*
		**************************************************************************/
		function SeleccionarEventoSoat()
		{
			IncludeClass('Pacientes','','app','DatosPaciente');
			$pct = new Pacientes();
			
			$this->request = $_REQUEST;
			$this->paciente = $pct->ObtenerDatosPaciente($this->request['tipo_id_paciente'],$this->request['paciente_id']);
			
			$datos = array();
			$datos['tipo_id_paciente'] = $this->request['tipo_id_paciente'];
			$datos['paciente_id'] = $this->request['paciente_id'];
			$datos['plan_id'] = $this->request['plan_id'];
			
			$this->action['aceptar'] = ModuloGetURL('app','Os_CentralAutorizacionGeneral','user','FormaDatosPaciente',$datos);
			$this->action['cancelar'] = ModuloGetURL('app','Os_CentralAutorizacionGeneral','user','FormaMenuPrincipal',array("grupo"=>1));
		}
		/*************************************************************************
		*
		**************************************************************************/
		function CrearSolicitud()
		{
			$this->request = $_REQUEST;
			
			$crgadd = SessionGetVar("CargosAdicionados");

			$datos = SessionGetVar("AtencionGral");
			IncludeClass('SolicitudManual','','app','Os_CentralAtencion');
			$slm = new SolicitudManual();
			
			$cargos = $slm->IngresarSolictudManual($this->request,$crgadd,$datos);
			$this->action['cancelar'] = ModuloGetURL('app','Os_CentralAutorizacionGeneral','user','FormaMenuPrincipal',array("grupo"=>1));
			
			if($cargos === false)
			{
				$this->frmError['MensajeError'] = $slm->frmError['MensajeError'];
				return false;
			}
			
			//$rst['idp'] = $this->request['paciente_id'];
			//$rst['tipoid'] = $this->request['tipo_id_paciente'];
			$rst['cargos'][$this->request['plan_id']] = $cargos;
			$rst['plan_num'] = $this->request['plan_id'];
			$rst['paciente']['afiliado'] = $this->request['afiliado'];
			$rst['paciente']['paciente_id'] = $this->request['paciente_id'];
      $rst['paciente']['tipo_id_paciente'] = $this->request['tipo_id_paciente'];
						
			$this->action['aceptar'] = ModuloGetURL('app','Os_CentralAutorizacionGeneral','user','FormaAutorizarCargos',$rst);
			return true;
		}
	}
?>