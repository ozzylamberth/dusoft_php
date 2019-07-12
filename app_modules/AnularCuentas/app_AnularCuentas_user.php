<?php
	/**************************************************************************************  
	* $Id: app_AnularCuentas_user.php,v 1.1 2007/02/12 14:50:35 hugo Exp $ 
	* 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	* 
	* $Revision: 1.1 $ 
	* 
	* @autor Hugo F  Manrique 
	***************************************************************************************/
	class app_AnularCuentas_user extends classModulo
	{
		/**
		* @var $action Variable donde se guardan los action de las formsa
		**/
		var $action = array();
		/**
		* @var $Rta Variable donde se guardan los resultados de las consultas
		**/
		var $Rta = array();
		/**
		* @var $request Variable donde se guardan los valores de los request que se usaran en el HTML
		**/
		var $request = array();
		/**
		*  @var $Mensaje Variable para los mensajes
		**/
		var $Mensaje = "";
		/**
		*  @var $Nota Variable para los mensajes
		**/
		var $Nota = "";
		/**
		* @var $variable Variable donde se guardan datos estaticos para las formas 
		**/
		var $variable = array();
		
		function app_AnularCuentas_user(){	}
		/**********************************************************************************
		* Funcion donde se evalua si el usuario que esta accediendo al modulo tiene o no
		* permisos
		* 
		* @return boolean Indica si tiene permisos true, o no false
		***********************************************************************************/
		function Main()
		{
			IncludeClass('AnularCuenta','','app','AnularCuentas');
			$anc = new AnularCuenta();
			$this->empresas = $anc->ObtenerPermisos(UserGetUID());
			
			SessionDelVar("AnularCuentasPermisos");
			if(sizeof($this->empresas) == 1)
			{
				foreach($this->empresas as $key => $permiso)
				{
					$permiso['cantidad'] = '1';
					SessionSetVar("AnularCuentasPermisos",$permiso);
				}
			}
			$this->action['volver'] = ModuloGetURL('system','Menu','user');
		}
		/**********************************************************************************
		* 
		* @return boolean Indica si tiene permisos true, o no false
		***********************************************************************************/
		function BuscarCuentas()
		{
			$this->request = $_REQUEST;
			$this->rqst = $this->request['buscador'];
			
			if(!SessionIsSetVar("AnularCuentasPermisos"))
				SessionSetVar("AnularCuentasPermisos",$this->request['permiso']);
			
			IncludeClass('AnularCuenta','','app','AnularCuentas');
			$anc = new AnularCuenta();
			$this->Id = $anc->ObtenerTipoIdPaciente();
			
			$dat_empresa = SessionGetVar("AnularCuentasPermisos");
			if(!$dat_empresa['cantidad'])
				$this->action['volver'] = ModuloGetURL('app','AnularCuentas','user','FormaMain');
			else
				$this->action['volver'] = ModuloGetURL('system','Menu','user');
			
			$this->action['buscar'] = ModuloGetURL('app','AnularCuentas','user','FormaBuscarCuentas');			
			if(!empty($this->rqst))
				$this->cuentas = $this->ObtenerCuentas($dat_empresa,$anc);
		}
		/**********************************************************************************
		* 
		* @return boolean Indica si tiene permisos true, o no false
		***********************************************************************************/
		function ObtenerCuentas($dat_empresa,$obj)
		{
			$cuentas = array();
			
			if($this->rqst['Cuenta'] || $this->rqst['Ingreso'])
				$cuentas = $obj->ObtenerCuentasXIngreso($this->rqst,$dat_empresa['empresa_id'],null,$this->request['offset']);
			else if($this->rqst['Documento'])
				$cuentas = $obj->ObtenerCuentasXIdPaciente($this->rqst,$dat_empresa['empresa_id'],null,$this->request['offset']);
				else if($this->rqst['Nombres'] || $this->rqst['Apellidos'])
					$cuentas = $obj->ObtenerCuentasXNombrePaciente($this->rqst,$dat_empresa['empresa_id'],null,$this->request['offset']);
					else
						$cuentas = $obj->ObtenerCuentas($this->rqst,$dat_empresa['empresa_id'],null,$this->request['offset']);
			
      $this->conteo = $obj->conteo;
      $this->pagina = $obj->paginaActual;
			$datos['conteo'] = $obj->conteo;
			$datos['buscador'] = $this->rqst;
			
			$this->action['pagina'] = ModuloGetURL('app','AnularCuentas','user','FormaBuscarCuentas',$datos);			
			$this->action['ver_cn'] = ModuloGetURL('app','AnularCuentas','user','FormaVerResumenCuenta',$datos);			
			$this->action['anular'] = ModuloGetURL('app','AnularCuentas','user','FormaAnularCuenta');			
			
			return $cuentas;
		}
		/**********************************************************************************
		* 
		* @return boolean Indica si tiene permisos true, o no false
		***********************************************************************************/
		function AnularCuenta()
		{
			$this->request = $_REQUEST;
			$this->action{'anular'} = ModuloGetURL('app','AnularCuentas','user','FormaRegistrarAnularCuenta',array("numerodecuenta"=>$this->request['numerodecuenta'],"ingreso"=>$this->request['ingreso'],"forma"=>$this->request['forma']));			
		}
		/**********************************************************************************
		* 
		* @return boolean Indica si tiene permisos true, o no false
		***********************************************************************************/
		function RegistrarAnularCuenta()
		{
			$this->request = $_REQUEST;
			IncludeClass('AnularCuenta','','app','AnularCuentas');
			$anc = new AnularCuenta();
			$dat_empresa = SessionGetVar("AnularCuentasPermisos");
			$rst = $anc->RegistrarAnulacionCuenta($this->request,$dat_empresa['empresa_id']);
			
			if(!$rst)
				$this->frmError['MensajeError'] = $rst->frmError['MensajeError'];
			else
				$this->frmError['MensajeError'] = "LA CUENTA Nº ".$this->request['numerodecuenta'].", HA SIDO ANULADA ";
		}
	}
?>