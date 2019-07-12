<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: app_UV_CxPGestionPagos_controller.php,v 1.2 2008/10/23 22:09:09 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Control: UV_CxPGestionPagos
  * Clase encargada del control de llamado de metodos en el modulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class app_UV_CxPGestionPagos_controller extends classModulo
  {
    /**
    * Constructor de la clase
    */
    function app_UV_CxPGestionPagos_controller(){}
    /**
    * Funcion de control para el menu
    * 
    * @return boolean
    */
    function MenuGestionPagos()
    {
      $request = $_REQUEST;
      
      $action['volver'] = ModuloGetURL('app','UV_CuentasXPagar','controller','Menu');
      $action['crear_op'] = ModuloGetURL('app','UV_CxPGestionPagos','controller','ListarFacturas');
      $action['consultar_op'] = ModuloGetURL('app','UV_CxPGestionPagos','controller','MostrarOrdenesPagos');
      
      $html = AutoCarga::factory('MensajesModuloHTML','views','app','UV_CxPGestionPagos');
      $this->salida = $html->FormaMenuInicial($action);
      return true;
    }
    /**
    *
    */
    function ListarFacturas()
    {
      $request = $_REQUEST;
      
      $action['volver'] = ModuloGetURL('app','UV_CxPGestionPagos','controller','MenuGestionPagos');
      $action['buscar'] = ModuloGetURL('app','UV_CxPGestionPagos','controller','ListarFacturas',array("buscador"=>$request['buscador']));
      $action['crear_op'] = ModuloGetURL('app','UV_CxPGestionPagos','controller','CrearOrdenPago');
      
      $gp = AutoCarga::factory('GestionPagos','','app','UV_CxPGestionPagos');
      $tiposdocumentos = $gp->ObtenerTipoIdTerceros();
      $facturas = array();
      if(!empty($request['buscador']))
      {
        $empresa = SessionGetVar("EmpresasCuentas");
        $facturas = $gp->ObtenerFacturas($empresa['empresa'],$request['buscador'],"RD","OG");
      }
      
      $html = AutoCarga::factory('GestionPagosHTML','views','app','UV_CxPGestionPagos');
      $this->salida .= $html->FormaListadoFacturas($action,$request['buscador'],$tiposdocumentos,$facturas);
      return true;
    }
    /**
    *
    */
    function CrearOrdenPago()
    {
      $request = $_REQUEST;
      
      $empresa = SessionGetVar("EmpresasCuentas");
      
      $gp = AutoCarga::factory('GestionPagos','','app','UV_CxPGestionPagos');
      $rst = $gp->IngresarOrdenpago($request,$empresa['empresa'],"OP");
      
      $action['volver'] = ModuloGetURL('app','UV_CxPGestionPagos','controller','ListarFacturas');;
      if(!$rst) 
      {
        $html = AutoCarga::factory('MensajesModuloHTML','views','app','UV_CxPGestionPagos');
        $mensaje = "ERROR: ".$gp->mensajeDeError;
        $this->salida = $html->FormaMensajeModulo($action,$mensaje);
      }
      else
      {
        $html = AutoCarga::factory('GestionPagosHTML','views','app','UV_CxPGestionPagos');
        $this->salida = $html->FormaImprimir($action,$rst);
      }
      
      return true;
    }
    /**
    *
    */
    function MostrarOrdenesPagos()
    {
      $request = $_REQUEST;
      
      global $xajax;
      list($xajax) = getXajax();
      $xajax->setCharEncoding('ISO-8859-1');
      
      IncludeFileModulo('GestionPagos','RemoteXajax','app','UV_CxPGestionPagos');
      $this->SetXajax(array("IngresarNumeroRadicacion","RegistrarNumeroRadicacion","IngresarEstadoObservacion","RegistrarEstadoObservacion"));
      $this->IncludeJS("CrossBrowser");
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
      
      $action['volver'] = ModuloGetURL('app','UV_CxPGestionPagos','controller','MenuGestionPagos');
      $action['buscar'] = ModuloGetURL('app','UV_CxPGestionPagos','controller','MostrarOrdenesPagos');
      $action['paginador'] = ModuloGetURL('app','UV_CxPGestionPagos','controller','MostrarOrdenesPagos',array("buscador"=>$request['buscador']));
      $action['detalle'] = ModuloGetURL('app','UV_CxPGestionPagos','controller','MostrarDetalleOrdenPago',array("buscador"=>$request['buscador']));
      
      $gp = AutoCarga::factory('GestionPagos','','app','UV_CxPGestionPagos');
      if(!empty($request['buscador']))
      {
        $empresa = SessionGetVar("EmpresasCuentas");
        $ordpg = $gp->ObtenerPreOrdenesPagos($empresa['empresa'],$request['buscador'],$request['offset'],"0","OG");
      }
      
      $html = AutoCarga::factory('GestionPagosHTML','views','app','UV_CxPGestionPagos');
      $this->salida = $html->FormaListadoOrdenesPago($action,$request['buscador'],$request['offset'],$ordpg,$gp->conteo,$gp->pagina);
      
      return true;
    }
    /**
    *
    */
    function MostrarDetalleOrdenPago()
    {
      $request = $_REQUEST;
      
      $action['volver'] = ModuloGetURL('app','UV_CxPGestionPagos','controller','MostrarOrdenesPagos',array("buscador"=>$request['buscador']));

      $gp = AutoCarga::factory('GestionPagos','','app','UV_CxPGestionPagos');
      $empresa = SessionGetVar("EmpresasCuentas");
      $facturas = $gp->ObtenerFacturasPreOrdenPago($empresa['empresa'],$request['cxp_orden_pago_id']);
      $detalle = $gp->ObtenerDetallePreOrdenPago($empresa['empresa'],$request['cxp_orden_pago_id']);
      $tercero = $gp->ObtenerProveedores($detalle);
      $cuentas = $gp->ObtenerDetalleOrdenGasto($empresa['empresa'],$request['cxp_orden_pago_id']);
      $pacientes = $gp->ObtenerPacientes($empresa['empresa'],$request['cxp_orden_pago_id']);
      $ordenes = $gp->ObtenerOrdenesServicio($empresa['empresa'],$request['cxp_orden_pago_id']);
      
      $estamentos = array();
      foreach($pacientes as $k => $d1)
      {
        foreach($d1 as $e => $d2)
        {
          $estamentos[$d2['descripcion_estamento']]['valor'] += $ordenes[$k][$e]['valor']; 
          $estamentos[$d2['descripcion_estamento']]['cantidad'] += $ordenes[$k][$e]['cantidad']; 
        }
      }
      
      $html = AutoCarga::factory('GestionPagosHTML','views','app','UV_CxPGestionPagos');
      $this->salida = $html->FormaFormatoRevision($action,$detalle,$tercero,$facturas,$cuentas,$estamentos,$empresa['empresa'],$request['cxp_orden_pago_id']);
      
      return true;
    }
  }
 ?>