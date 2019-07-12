<?php
	/**************************************************************************************
	* $Id: app_FacturacionMovimientos_user.php,v 1.1 2007/05/24 21:43:06 hugo Exp $
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* $Revision: 1.1 $ 	
	* @author Hugo Freddy Manrique Arango
	***************************************************************************************/
	class app_FacturacionMovimientos_user extends classModulo 
	{
		var $action = array();
		
		var $empresas = array();
		
		var $grupos = array();
		
		var $menu = array();
		
		var $request = array();
		
		function app_FacturacionMovimientos_user(){}
		/**************************************************************************************
		* @return boolean
		***************************************************************************************/
		function Main()
		{
			IncludeClass('Movimientos','','app','FacturacionMovimientos');
			$mvs = new Movimientos();
			
			$this->empresas = $mvs->ObtenerPermisos(UserGetUID());
		}
		/**************************************************************************************
		* @return boolean
		***************************************************************************************/
		function MenuMovimientos()
		{
			$this->request = $_REQUEST;
			SessionDelVar("FacturasSeleccionadas");
			
			if(!empty($this->request['Movimiento']))
			{
				SessionDelVar('MovimientosEmpresa');
				SessionSetVar('MovimientosEmpresa',$this->request['Movimiento']);
			}
			
			IncludeClass('Movimientos','','app','FacturacionMovimientos');
			$mvs = new Movimientos();
			
			$this->empresas = SessionGetVar('MovimientosEmpresa');
			$this->menu = $mvs->ObtenerGrupos($this->empresas['empresa_id'],UserGetUID());
			
			$this->action['volver'] = ModuloGetURL('app','FacturacionMovimientos','user','FormaMain');
			$this->action['siguiente'] = ModuloGetURL('app','FacturacionMovimientos','user','FormaMostrarFacturas');
		}
		/**************************************************************************************
		* @return boolean
		***************************************************************************************/
		function MostrarFacturas()
		{			
			$this->request = $_REQUEST;
			if(!empty($this->request['grupo']))
			{
				SessionDelVar('MovimientosGrupos');
				SessionSetVar('MovimientosGrupos',$this->request['grupo']);
			}
			
			$this->grupos = SessionGetVar('MovimientosGrupos');
			$this->empresas = SessionGetVar('MovimientosEmpresa');
			$this->facturas = SessionGetVar("FacturasSeleccionadas");
			
			$this->action['volver'] = ModuloGetURL('app','FacturacionMovimientos','user','FormaMenuMovimientos');
			$this->action['asignar'] = ModuloGetURL('app','FacturacionMovimientos','user','FormaIngresarMovimientos');
			$this->action['buscador'] = ModuloGetURL('app','FacturacionMovimientos','user','FormaMostrarFacturas');
			$this->action['paginador'] = ModuloGetURL('app','FacturacionMovimientos','user','FormaMostrarFacturas',array("buscador"=>$this->request['buscador']));
		}
		/**************************************************************************************
		* @return boolean
		***************************************************************************************/
		function IngresarMovimientos()
		{
			$this->request = $_REQUEST;
			IncludeClass('Movimientos','','app','FacturacionMovimientos');
			$mvs = new Movimientos();
			
			$this->facturas = SessionGetVar("FacturasSeleccionadas");
			$this->empresas = SessionGetVar('MovimientosEmpresa');
			$this->grupos = SessionGetVar('MovimientosGrupos');
			
			$rst = $mvs->ActualizarFacturas($this->facturas,$this->empresas['empresa_id'],$this->request['usuario_seleccion'],$this->request['grupo_seleccion'],$this->request['sw_estado']);
			
			if($rst)
			{
				$this->frmError['MensajeError'] = "LAS FACTURAS SELECCIONADAS FUERON ASIGNADAS CORRECTAMENTE";
				SessionDelVar("FacturasSeleccionadas");
			}
			else
				$this->frmError['MensajeError'] = "HA OCURRIDO UN ERROR DURANTE EL PROCESO<br>".$mvs->frmError['MensajeError'];
			
			$this->action['aceptar'] = ModuloGetURL('app','FacturacionMovimientos','user','FormaMostrarFacturas');

		}
	}
?>