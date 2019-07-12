<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: app_UV_CxPEstados_controller.php,v 1.1 2008/10/10 22:27:29 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Control: UV_CxPEstados
  * Clase encargada del control de llamado de metodos en el modulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class app_UV_CxPEstados_controller extends classModulo
  {
    /**
    * Constructor de la clase
    */
    function app_UV_CxPEstados_controller(){}
    /**
    * Funcion de control para el menu
    * 
    * @return boolean
    */
    function MenuGestionEstados()
    {
      $request = $_REQUEST;
      
      $action['volver'] = ModuloGetURL('app','UV_CuentasXPagar','controller','Menu');
      $action['estados_facturas'] = ModuloGetURL('app','UV_CxPEstados','controller','ListarFacturas');
      $action['estados_ordenes_gasto'] = ModuloGetURL('app','UV_CxPEstados','controller','ListarOrdenesPagos');
      
      $html = AutoCarga::factory('MensajesModuloHTML','views','app','UV_CxPEstados');
      $this->salida = $html->FormaMenuInicial($action);
      return true;
    }
    /**
    *
    */
    function ListarFacturas()
    {
      $request = $_REQUEST;
      
      $action['volver'] = ModuloGetURL('app','UV_CxPEstados','controller','MenuGestionEstados');
      $action['buscar'] = ModuloGetURL('app','UV_CxPEstados','controller','ListarFacturas',array("buscador"=>$request['buscador']));
      $action['cambiar_estado'] = ModuloGetURL('app','UV_CxPEstados','controller','CambiarEstadosFacturas');
      $action['paginador'] = ModuloGetURL('app','UV_CxPEstados','controller','ListarFacturas',array("buscador"=>$request['buscador']));
      
      $gp = AutoCarga::factory('GestionEstados','','app','UV_CxPEstados');
      $tiposdocumentos = $gp->ObtenerTipoIdTerceros();
      $facturas = array();
      if(!empty($request['buscador']))
      {
        $empresa = SessionGetVar("EmpresasCuentas");
        $facturas = $gp->ObtenerFacturas($empresa['empresa'],$request['buscador'],$request['offset']);
      }
      
      $estados = $gp->ObtenerEstados();
      
      $html = AutoCarga::factory('GestionEstadosHTML','views','app','UV_CxPEstados');
      $this->salida .= $html->FormaListadoFacturas($action,$request['buscador'],$tiposdocumentos,$facturas,$estados,$gp->conteo,$gp->pagina);
      return true;
    }
		/**
    *
    */
		function CambiarEstadosFacturas()
    {
      $request = $_REQUEST;
      
      $empresa = SessionGetVar("EmpresasCuentas");
      
      $gp = AutoCarga::factory('GestionEstados','','app','UV_CxPEstados');
      $rst = $gp->ActualizarEstado($request,$empresa['empresa']);
      
      $action['volver'] = ModuloGetURL('app','UV_CxPEstados','controller','ListarFacturas');;
      $html = AutoCarga::factory('MensajesModuloHTML','views','app','UV_CxPEstados');
      
      if(!$rst) 
        $mensaje = "ERROR: ".$gp->mensajeDeError;
      else
        $mensaje = "EL CAMBIO DE ESTADO DE LAS FACTURAS SELECCIONADAS SE REALIZO CORRECTAMENTE";
      
      
      $this->salida = $html->FormaMensajeModulo($action,$mensaje);
      return true;
    }
    /**
    *
    */
    function ListarOrdenesPagos()
    {
      $request = $_REQUEST;
            
      $action['volver'] = ModuloGetURL('app','UV_CxPEstados','controller','MenuGestionEstados');
      $action['buscar'] = ModuloGetURL('app','UV_CxPEstados','controller','ListarOrdenesPagos');
      $action['paginador'] = ModuloGetURL('app','UV_CxPEstados','controller','ListarOrdenesPagos',array("buscador"=>$request['buscador']));
      $action['acciones'] = ModuloGetURL('app','UV_CxPEstados','controller','GestionOrdenesPago',array("buscador"=>$request['buscador']));
      
      $gp = AutoCarga::factory('GestionEstados','','app','UV_CxPEstados');
      if(!empty($request['buscador']))
      {
        $empresa = SessionGetVar("EmpresasCuentas");
        $ordpg = $gp->ObtenerPreOrdenesPagos($empresa['empresa'],$request['buscador'],$request['offset'],"0','3","OG");
      }
      
      $html = AutoCarga::factory('GestionEstadosHTML','views','app','UV_CxPEstados');
      $this->salida = $html->FormaListadoOrdenesPago($action,$request['buscador'],$request['offset'],$ordpg,$gp->conteo,$gp->pagina);
      
      return true;
    }
    /**
    *
    */
    function GestionOrdenesPago()
    {
      $request = $_REQUEST;
      $action['volver'] = ModuloGetURL('app','UV_CxPEstados','controller','ListarOrdenesPagos',array("buscador"=>$request['buscador']));
      
      $empresa = SessionGetVar("EmpresasCuentas");
      $gp = AutoCarga::factory('GestionEstados','','app','UV_CxPEstados');
      $facturas = $gp->ObtenerFacturasPreOrdenPago($empresa['empresa'],$request['cxp_orden_pago_id']);
      
      switch($request['opcion'])
      {
        case 'anular': 
          $estado_factura = "RD";
          $estado_orden = "0";
          $mensaje = "LA ORDEN DE PAGO Nº".$request['cxp_orden_pago_id'].", HA SIDO ANULADA";
        break;
        case 'pagar': 
          $estado_factura = "PA";
          $estado_orden = "3";
          $mensaje = "LA ORDEN DE PAGO Nº".$request['cxp_orden_pago_id'].", HA SIDO CAMBIADA AL ESTADO PAGADA";
        break;
      }
      $rst = $gp->CambiarEstadoOrdenPago($facturas,$estado_factura,$request['cxp_orden_pago_id'],$estado_orden,$empresa['empresa']);
      $html = AutoCarga::factory('MensajesModuloHTML','views','app','UV_CxPEstados');
      
      if(!$rst) 
        $mensaje = "HA OCURRIDO UN ERROR MIENTRAS SE REALIZABA LA OPERACION: ".$gp->mensajeDeError;
      
      $this->salida = $html->FormaMensajeModulo($action,$mensaje);
      return true;
    }
  }
?>